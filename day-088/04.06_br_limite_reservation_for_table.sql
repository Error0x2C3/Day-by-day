set search_path to public;

/*(BR-06) Une table ne peut pas faire l'objet de deux réservations terminées
ou confirmées pour une même date et un même service.*/

drop trigger if exists limite_reservation_for_table_trigger on reservations;
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
begin
    select restaurant from reservations where id = new.reservation into  _restaurant_id;
    select slot_duration from restaurants where id = _restaurant_id into _slot_duration;
    select datetime from reservations where id = new.reservation into _datetime_reservation;
    if( _restaurant_id > 0 AND _slot_duration > 0) then
        IF exists(
            select 1
            from reservation_tables rt
            left join reservations r on r.id = rt.reservation
            left join tables t on t.id = rt.table
            left join services s on s.restaurant = r.restaurant
            where r.status in ('confirmed', 'completed')
              and r.id != new.id /* Pour éviter de compter la réservation entrant d'être ajoutée ou modifiée présentement. */
              and r.restaurant = _restaurant_id
              and t.id = new.table
              and r.datetime::date = _datetime_reservation::date
              and (
                  s.restaurant = _restaurant_id AND
                  s.day_of_week = extract(ISODOW from _datetime_reservation) AND
                  s.start_time <=  _datetime_reservation::time AND
                  s.end_time >= _datetime_reservation::time + ( _slot_duration *INTERVAL'1 minute')
                )
        )
        then
            raise exception using message = 'Il n est pas permis d avoir plus d une rerservation pour une table le même jour à la même heure.';
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
execute function  limite_reservation_for_table();
