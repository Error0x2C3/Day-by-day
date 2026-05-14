set search_path to public;

/*(BR-12) Une réservation en attente ou annulée ne peut être associée à aucune une table.*/

CREATE OR REPLACE FUNCTION  check_reserv_pen_canc_no_tables_m_tables()
    RETURNS TRIGGER AS $$

DECLARE
    reservation_id int;
    reserv_status status_type;
BEGIN

    SELECT reservation INTO reservation_id
    FROM reservation_tables rt
    WHERE rt.reservation = new.id;

    SELECT status INTO reserv_status
    FROM reservations r
    WHERE r.id = reservation_id;
    IF (reservation_id is not null) THEN
        IF (reserv_status IN ('pending', 'cancelled')) THEN
            RAISE EXCEPTION 'Une reservation pending ou cancelled ne peut pas avoir de table associée.';
        END IF;
    END IF;
    RETURN new;
END;
$$LANGUAGE plpgsql;
--
-- CREATE OR REPLACE FUNCTION  check_reserv_pen_canc_no_tables_reserv()
--     RETURNS TRIGGER AS $$
--
-- BEGIN
--     IF (NEW.status IN ('cancelled', 'pending')) THEN
--         DELETE FROM reservation_tables WHERE reservation = new.id;
--         RAISE NOTICE 'BR-12: suppression des tables associées';
--     END IF;
--
--     RETURN new;
-- END;
-- $$LANGUAGE plpgsql;

create constraint trigger reserv_pend_canc_no_tables
    after insert or update
    on reservations
    deferrable initially deferred
    FOR EACH ROW
EXECUTE FUNCTION check_reserv_pen_canc_no_tables_m_tables();

-- CREATE OR REPLACE TRIGGER reserv_pend_canc_no_tables
--     BEFORE UPDATE OF status
--     ON reservations
--     FOR EACH ROW
-- EXECUTE FUNCTION check_reserv_pen_canc_no_tables_reserv();