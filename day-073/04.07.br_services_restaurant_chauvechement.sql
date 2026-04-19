/*
    Pour un même jour de la semaine, les services d'un restaurant ne peuvent pas se chevaucher.
 */

drop trigger if exists services_restaurant_chevauchement_trigger on reservation_tables;
drop function if exists services_restaurant_chevauchement();

create or replace function  services_restaurant_chevauchement() returns trigger as
$$
begin
    if exists(
            select day_of_week
            from services
            /*  services.id != new.id
            Car au moment de l'insertion, le service en court d'insertion va être comptée. */
            where services.id != new.id
              and day_of_week = new.day_of_week
              and new.start_time < end_time
              and new.end_time > start_time
              and restaurant = new.restaurant
        ) then raise exception 'Année d''écriture de l''oeuvre antérieure à l''année de naissance d''un des auteurs';
    end if;
    return new;
end
$$ language plpgsql;

create trigger services_restaurant_chevauchement_trigger
    before insert or update
    on services
    for each row
execute procedure services_restaurant_chevauchement();
