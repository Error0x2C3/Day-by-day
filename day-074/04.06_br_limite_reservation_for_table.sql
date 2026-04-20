/*
 Une table ne peut pas faire l'objet de deux réservations terminées ou confirmées pour une même date et un même service.
 */

drop trigger if exists limite_reservation_for_table_trigger on reservation_tables;
drop function if exists limite_reservation_for_table();

create or replace function  limite_reservation_for_table() returns trigger as
$$
declare
    /* Nombre de réservation (terminées ou confirmée) faîtes à la même date et au même service dans un restaurant. */
    _conflit int;
begin
    select count(*)
    from tables t
    join reservation_tables rt on rt.table = t.id
    join reservations r on rt.reservation = r.id
    /* r.id != new.reservation
       Car au moment de l'insertion, la réservation en court d'insertion va être comptée. */
    where t.id = new.table AND r.id != new.reservation /* Eviter les collisions. */
    and (r.status = 'confirmed'::status_type or r.status='completed'::status_type)
    and r.datetime in (select datetime from reservations where reservations.id = new.reservation ) into _conflit;

    if _conflit > 0 then
        raise exception using message = 'Il n est pas permis d avoir plus d une rerservation pour une table le même jour à la même heure.';
    end if;
    return new;
end
$$ language plpgsql;

create trigger limite_reservation_for_table_trigger
    before insert or update
    on reservation_tables
    for each row
execute function limite_reservation_for_table();
