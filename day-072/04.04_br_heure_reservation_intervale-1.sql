/*
  L'heure d'une réservation confirmée, terminée ou en attente doit être comprise
  entre l'heure de début et l'heure de fin d'un service pour le même jour de la semaine.
*/

drop trigger if exists heure_reservation_intervale_trigger on reservations;
drop function if exists heure_reservation_intervale();
create or replace function heure_reservation_intervale() returns trigger as
$$
declare
    _days_of_week int;
    _hour_start time;
    _hour_end time;
    _current_hour time;
begin
    /* ISODOW : le jour de la semaine de 1-7. */
    _days_of_week = extract( ISODOW from new.datetime);
    /* On extrait l'heure du début du service lié au restaurant courrant et du jour courant. */
    /* ::time => Permet d'avoir l'heure et les minutes contrairement à HOUR. */
    select services.start_time::time from services where day_of_week = _days_of_week and services.restaurant = new.restaurant into _hour_start;
    /* On extrait l'heure de fin du service lié au restaurant courrant et du jour courant. */
    select services.end_time::time from services where day_of_week = _days_of_week and services.restaurant = new.restaurant into _hour_end;
    /* L'heure de la requête. */
    _current_hour = new.datetime::time;
    /* Si on fait une requête insert ou update alors ... */
    if tg_op = 'INSERT' OR tg_op = 'UPDATE' then
        /* */
        if new.status IN ( 'pending'::status_type,'confirmed'::status_type, 'completed'::status_type) then
           if  _current_hour < _hour_start or _current_hour > _hour_end then
               raise exception using message = 'L heure de la réservation doit être compris entre '|| _hour_start || ' et ' || _hour_end;
           end if;
        end if;
    end if;
    return new;
end
$$ language plpgsql;

create trigger heure_reservation_intervale_trigger
    before insert or update
    on reservations
    for each row
execute procedure heure_reservation_intervale();

/*
 CHECK (slot_duration IN (10, 15, 20, 30, 60));
 */

