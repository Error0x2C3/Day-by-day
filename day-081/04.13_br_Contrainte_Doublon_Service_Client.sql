set search_path to public;
/*  Un client ne peut pas avoir deux réservations en attente, confirmées ou terminées pour une même date et un même service. */

CREATE OR REPLACE FUNCTION  no_doublon_reservation()
RETURNS trigger as $$
declare
    _days_of_week int;
    _hour_start time;
BEGIN
    /* ISODOW : le jour de la semaine de 1-7. */
  _days_of_week = extract( ISODOW from new.datetime);
  /* On extrait l'heure du début du service lié au restaurant courrant et du jour courant. */
  /* ::time => Permet d'avoir l'heure et les minutes contrairement à HOUR. */
  _hour_start = new.datetime::time;
  IF  new.status IN ('pending'::status_type,'confirmed'::status_type, 'completed'::status_type) THEN
      IF EXISTS (SELECT *
                   FROM reservations rs
                   JOIN services s on rs.restaurant = s.restaurant
                   WHERE rs.client = new.client
                     /* rs.id != new.id
                     Car au moment de l'insertion, la réservation en court d'insertion va être comptée. */
                     AND rs.id <> new.id
                     AND rs.status = new.status
                     AND s.day_of_week = _days_of_week
                     AND s.start_time = _hour_start
                     ) THEN
          raise exception using message = 'Le client ne peut pas avoir deux réservations de type' || new.status ||'.';
      END IF;
END IF;
RETURN NEW;
END;
    $$LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_no_doublon_reservation ON reservations;

CREATE TRIGGER trg_no_doublon_reservation
    BEFORE INSERT ON reservations
    FOR EACH ROW
    EXECUTE FUNCTION no_doublon_reservation();