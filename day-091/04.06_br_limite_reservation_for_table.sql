set search_path to public;

/*(BR-06) Une table ne peut pas faire l'objet de deux réservations terminées
ou confirmées pour une même date et un même service.*/

drop trigger if exists limite_reservation_for_table_trigger on reservation_tables;
drop function if exists limite_reservation_for_table();

create or replace function limite_reservation_for_table() returns trigger
set search_path from current as
$$
declare
    /* Durée de créneau d'un restaurant. */
    _slot_duration int;
    /* id du restaurant. */
    _restaurant_id int;
    /* Timestamp d d'un réservation. */
    _datetime_reservation timestamp;
    /* id du service d'un restaurant. */
    _service_id int;
begin
    select restaurant from reservations where id = new.reservation into  _restaurant_id;
    select slot_duration from restaurants where id = _restaurant_id into _slot_duration;
    select datetime from reservations where id = new.reservation into _datetime_reservation;
    /* (BR-04) L'heure d'une réservation confirmée, terminée ou en attente doit être comprise entre l'heure de début
       et l'heure de fin d'un service pour le même jour de la semaine.*/
    select id into _service_id
        from services
        where restaurant = _restaurant_id
        and day_of_week = extract(ISODOW from _datetime_reservation)
        and _datetime_reservation::time >= start_time
        and _datetime_reservation::time < end_time;
    if( _restaurant_id > 0 AND _slot_duration > 0 AND _service_id is not null ) then
        IF exists(
            select 1
            from reservation_tables rt
            join reservations r on r.id = rt.reservation
            join services s on s.restaurant = r.restaurant
                /* Pour avoir le bon service lié à la bonne réservation. */
                and s.day_of_week = extract(ISODOW from r.datetime)
                and r.datetime::time >= s.start_time
                /* Il faut que la durée de réservation [début,fin] soit compris dans le service. */
                AND r.datetime::time + ( _slot_duration *INTERVAL'1 minute') <= end_time /* Pour être cohérent avec BR_04. */
            where r.status in ('confirmed', 'completed')
              and r.id != new.reservation /* Pour éviter de compter la réservation entrant d'être ajoutée ou modifiée présentement. */
              and r.restaurant = _restaurant_id
              and rt.table = new.table
              and r.datetime::date = _datetime_reservation::date
              /* S'il exite déjà une réservation pour le service dont on est entrain de réserver.*/
              and s.id = _service_id
        )
        then
            raise exception using message = 'BR_06 : Il n est pas permis d avoir plus d une reservation pour une table le même jour à un même service.';
        end if;
    end if;
    return new;
end
$$ language plpgsql security definer;

create constraint trigger limite_reservation_for_table_trigger
    after insert or update
    on reservation_tables
    deferrable initially deferred
    for each row
execute function limite_reservation_for_table();
