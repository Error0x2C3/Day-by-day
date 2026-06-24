set search_path to public;
/*
(BR-15) Un restaurant ne peut pas avoir plus de 3 réservations VIP actives.
Sont considérées comme actives les réservations dont le statut est pending ou confirmed et dont la date/heure est dans le futur.

Analyse :

insert : oui
update : oui
delete : non
before : oui
Avec before doit vérifié si :

Before Insert :
Vérifier Si la réservation que je veux inséré est bien active et VIP ET
compter le nbr de réservations actives et VIPS du restaurant (de la réservation)
pour saovir si >=3.

Before Update :
Vérifier si la réservation que je veux mettre à jour va être active et VIP et
si le nbr de réservation actives et VIPS du restaurant (de la réservation)  en comptant celle en cour de modif
est >3
*/

drop trigger if exists  is_vip_limit_trigger on reservations;
drop function if exists is_vip_limit();
create or replace function is_vip_limit() returns trigger as
$$
declare
    _current_time timestamp;
    _nbr_vips int;
begin
    select simulated_time into _current_time from system_time order by simulated_time DESC limit 1;

    if tg_op ='INSERT' then
        if(new.is_vip is true and new.status in ('pending', 'confirmed') and new.datetime > _current_time) then
            select count(*) into _nbr_vips
            from reservations r
            where r.is_vip = true
                and r.status in ('pending', 'confirmed')
                and r.datetime > _current_time
                and r.restaurant = new.restaurant;

            if(_nbr_vips >=3 )then
                RAISE EXCEPTION 'BR-15: Un restaurant ne peut pas avoir plus de 3 réservations actifs pour un même restaurant !';
            end if;
        end if;
    elsif  tg_op ='UPDATE' then
        if(new.is_vip is true and new.status in ('pending', 'confirmed') and new.datetime > _current_time) then
            select count(*) into _nbr_vips
            from reservations r
            where r.is_vip = true
              and r.status in ('pending', 'confirmed')
              and r.datetime > _current_time
              and r.restaurant = new.restaurant
              and r.id <> new.id;

            if(_nbr_vips >=3 )then
                RAISE EXCEPTION 'BR-15: Un restaurant ne peut pas avoir plus de 3 réservations actifs pour un même restaurant !';
            end if;
        end if;
    end if;

    return new;
end
$$ language plpgsql;

create trigger is_vip_limit_trigger
    before insert or update
    on reservations
    for each row
execute function is_vip_limit();