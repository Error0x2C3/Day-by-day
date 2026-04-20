/*
Objectif :
   Au moment de sa création ou de sa modification, l'heure d'une réservation doit correspondre à un créneau de réservation (slot)
   pour le restaurant. Un créneau de réservation doit toujours correspondre à une heure exacte (sans minutes)
   augmentée d'un multiple de la durée du créneau de réservation.

Rappel :
    La durée de créneau permet de définir l'intervalle de temps entre deux réservations successives (par exemple, 30 minutes).
    Ce paramètre est propre à chaque restaurant. Les valeurs autorisées pour la durée de créneau sont 10, 15, 20, 30 ou 60 minutes.
    Cette durée de créneau permet de définir les créneaux de réservation disponibles pour les clients en les alignant
    sur des heures rondes augmentées d'un multiple de la durée du créneau de réservation.
    Par exemple, si la durée de créneau est de 30 minutes, les créneaux de réservation peuvent être à 12:00, 12:30, 13:00, etc.,
    mais pas à 12:15 ou 12:45. Si la durée de créneau est de 20 minutes, les créneaux de réservation peuvent être à 12:00, 12:20, 12:40, etc.,
    mais pas à 12:10 ou 12:30.

Manière de procéder :
    Si la durée de créneau est de 20 minutes.
    Je sais alors que par exemple le client peu réserver à 12h00,12h20,12h40,13h00.
    Comme les minutes sont des multiples du créneau de réservation, si le module est différent de 0 => PAS BON.
    Ex : 12h15, 15%20 = 0,75 => PAS BON;    12h45%20, 45%20 = 2,25 => PAS BON.

    La condition est => DE MORGAN de [ if(  _current_minutes == 0 || MOD(_current_minutes, _slot_duration)==0 ) ]{} ).

*/
drop trigger if exists heure_reservation_correspondance_trigger on reservations;
drop function if exists heure_reservation_correspondance();
create or replace function heure_reservation_correspondance() returns trigger as
$$
declare
    /* La durée de créneau du restaurant. */
    _slot_duration int;
    /* L'heure pile actuelle de la réservation*/
    _current_minutes int;

begin
    /*
        HOUR : retourne un entier (int) représentant l'heure au format 24 heures, compris entre 0 et 23.
        Nous avons besoin de int au format heure =>
                      Heure,minute,seconde.
        SELECT make_time(13, 45, 0);
    */
    _current_minutes =  extract(minute from new.datetime); /* J'ai l'heure pile de la réservation courrante. */
    select slot_duration from restaurants where restaurants.id = new.restaurant into _slot_duration; /* Le slot de réservation pour le restaurant lié à la réservation.*/

    if  _current_minutes != 0 AND  MOD(_current_minutes, _slot_duration) !=0 then
        raise exception using message = 'l heure de réservation pour le créneau de réservation (slot) n est pas bonne..';
    end if;

    return new;
end
$$ language plpgsql;

create trigger heure_reservation_correspondance_trigger
    before insert or update
    on reservations
    for each row
execute function heure_reservation_correspondance();
