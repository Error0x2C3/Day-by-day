set search_path to public;

/*(BR-06) Une table ne peut pas faire l'objet de deux réservations terminées
ou confirmées pour une même date et un même service.*/

drop trigger if exists limite_reservation_for_table_trigger on reservations;
drop function if exists limite_reservation_for_table();

create or replace function limite_reservation_for_table() returns trigger
set search_path from current as
$$
declare
    /* Nombre de réservation (terminées ou confirmée) faîtes à la même date et au même service dans un restaurant. */
    _slot int;
begin
    select  slot_duration from restaurants where id = new.restaurant into _slot;
    IF exists(
        select 1
        from reservations r
        left join reservation_tables rt on reservation = r.id
        left join tables t on t.id = rt.table
        left join services s on s.restaurant = r.restaurant
        where r.status in ('confirmed', 'completed')
        and r.id != new.id
        and r.restaurant = new.restaurant
        and r.datetime::date = new.datetime::date
        and (extract(ISODOW from new.datetime ) = s.day_of_week
            and (new.datetime::time >= s.start_time and
                 new.datetime::time +(_slot *INTERVAL'1 minute') <= s.end_time
                 )
            )

    )
    then
        raise exception using message = 'Il n est pas permis d avoir plus d une rerservation pour une table le même jour à la même heure.';
    end if;
    return new;
end
$$ language plpgsql security definer;

create constraint trigger limite_reservation_for_table_trigger
    after insert or update
    on reservations
    deferrable initially deferred
    for each row
execute function  limite_reservation_for_table();
set search_path to public;

/*(BR-06) Une table ne peut pas faire l'objet de deux réservations terminées
ou confirmées pour une même date et un même service.*/

drop trigger if exists limite_reservation_for_table_trigger on reservations;
drop function if exists limite_reservation_for_table();

create or replace function limite_reservation_for_table() returns trigger
set search_path from current as
$$
declare
    /* Nombre de réservation (terminées ou confirmée) faîtes à la même date et au même service dans un restaurant. */
    _slot int;
begin
    select  slot_duration from restaurants where id = new.restaurant into _slot;
    IF exists(
        select 1
        from reservations r
        left join reservation_tables rt on reservation = r.id
        left join tables t on t.id = rt.table
        left join services s on s.restaurant = r.restaurant
        where r.status in ('confirmed', 'completed')
        and r.id != new.id
        and r.restaurant = new.restaurant
        and r.datetime::date = new.datetime::date
        and (extract(ISODOW from new.datetime ) = s.day_of_week
            and (new.datetime::time >= s.start_time and
                 new.datetime::time +(_slot *INTERVAL'1 minute') <= s.end_time
                 )
            )

    )
    then
        raise exception using message = 'Il n est pas permis d avoir plus d une rerservation pour une table le même jour à la même heure.';
    end if;
    return new;
end
$$ language plpgsql security definer;

create constraint trigger limite_reservation_for_table_trigger
    after insert or update
    on reservations
    deferrable initially deferred
    for each row
execute function  limite_reservation_for_table();
