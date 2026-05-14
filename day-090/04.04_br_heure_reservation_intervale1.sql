set search_path to public;

/*(BR-04) L'heure d'une réservation confirmée, terminée ou en attente doit être comprise
entre l'heure de début et l'heure de fin d'un service pour le même jour de la semaine.*/

CREATE OR REPLACE FUNCTION check_reservation_intervale()
    RETURNS TRIGGER AS
$$
DECLARE
    _days_of_week INT;
BEGIN
    _days_of_week := EXTRACT(ISODOW FROM NEW.datetime);

    IF NEW.status IN ('pending', 'confirmed', 'completed') THEN

        IF NOT EXISTS (SELECT 1
                       FROM services
                       WHERE restaurant = NEW.restaurant
                         AND day_of_week = _days_of_week
                         AND NEW.datetime::time >= start_time
                         AND NEW.datetime::time <= end_time) THEN
            RAISE EXCEPTION 'L''heure de la réservation doit être comprise dans un horaire de service valide';
        END IF;

    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE TRIGGER trigger_reservation_hour_intervale
    BEFORE INSERT OR UPDATE
    ON reservations
    FOR EACH ROW
EXECUTE FUNCTION check_reservation_intervale();

