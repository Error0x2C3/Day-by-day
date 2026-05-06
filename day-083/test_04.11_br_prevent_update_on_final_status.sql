
-- cas négatif pour completed --

BEGIN;
DO $test$
BEGIN
    RAISE NOTICE 'TEST BR-11: Modification d''un status completed';
    PERFORM should_fail($$
        UPDATE reservations SET number_of_guests = 10 WHERE id = 12
        $$, 'raise_exception');
END $test$;
ROLLBACK;

-- cas négatif pour cancelled -- 

BEGIN;
DO $test$
    BEGIN
        RAISE NOTICE 'TEST BR-11: Modification d''un status cancelled';
        PERFORM should_fail($$
        UPDATE reservations SET number_of_guests = 1 WHERE id = 8;
        $$, 'raise_exception');
    END $test$;
ROLLBACK;

-- cas positif --

BEGIN;
DO $test$
    BEGIN
        RAISE NOTICE 'TEST BR-11: Modification d''un status confirmed';
        UPDATE reservations SET number_of_guests = 1 WHERE id = 1;
        SET CONSTRAINTS ALL IMMEDIATE;
    END $test$;
ROLLBACK;