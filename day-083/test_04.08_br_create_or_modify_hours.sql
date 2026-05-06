/*--------------------------------------------------------------------------------------------------------------------*/

    --INSERT--

-- cas negatif
begin;
do
$test$
begin
        RAISE NOTICE 'TEST BR-08:Vérification que l''alignement a 15 minutes est refusé pour un créneau de 30';
        PERFORM should_fail($$
            INSERT INTO services (id, restaurant, day_of_week, start_time, end_time) VALUES (99, 1, 3, '15:15:00', '18:00:00')
            $$, 'raise_exception');
end
$test$;
rollback;

-- cas positif

begin;
do
$test$
begin
        RAISE NOTICE 'TEST BR-08:Vérification que l''alignement a 30 minutes est acceptée pour un créneau de 30';
INSERT INTO services (id, restaurant, day_of_week, start_time, end_time) VALUES (98,1,3,'15:00:00', '16:00:00');
set constraints all immediate;
end
$test$;
rollback;

        -- UPDATE --

-- cas negatif
begin;
do
$test$
    begin
        RAISE NOTICE 'TEST BR-08: Vérification que l''alignement a 15 minutes est refusé pour un créneau de 30';
        PERFORM should_fail($$
            UPDATE services
            SET start_time = '15:15:00'
            WHERE id = 1
            $$, 'raise_exception');
    end
$test$;
rollback;

-- cas positif

begin;
do
$test$
    begin
        RAISE NOTICE 'TEST BR-08: Vérification que l''alignement a 30 minutes est acceptée pour un créneau de 30';
        UPDATE services
        SET end_time = '14:30:00'
        WHERE id = 1;
        set constraints all immediate;
    end
$test$;
rollback;