set search_path to public;

/*(BR-11) Une réservation terminée ou annulée ne peut pas être modifiée.*/

CREATE OR REPLACE FUNCTION  prevent_update_on_final_status()
RETURNS trigger as $$
/*
old != new n'est pas bon  car si une coolonne ex :
Si la colonne special_request est null => old != new = NULL, il est considèré comme False.
 */
BEGIN
    IF old.status IN ('completed' , 'cancelled') AND  old IS DISTINCT FROM new  THEN
        RAISE EXCEPTION 'BR-11: Impossible de modifier la réservation si elle est terminée ou annulée.';
    END IF;
    RETURN NEW;
END;
    $$LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_prevent_update_on_final_status ON reservations;

CREATE TRIGGER trg_prevent_update_on_final_status
    BEFORE UPDATE ON reservations
    FOR EACH ROW
EXECUTE FUNCTION prevent_update_on_final_status();