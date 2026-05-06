/* Cas positif */
begin;
do
$test$
begin
        RAISE NOTICE 'TEST BR-05 (cas positif) :  l heure de réservation pour le créneau de réservation (slot) est bonne';
        UPDATE reservations SET datetime = '2024-11-15 20:00:00.000000' WHERE id = 1;
end
$test$;
rollback;

/* Cas négatif */
begin;
do
$test$
    begin
        RAISE NOTICE 'TEST BR-05 (cas négatif):  l heure de réservation pour le créneau de réservation (slot) n est pas bonne..';
        PERFORM should_fail($$
                 UPDATE reservations SET datetime = '2024-11-15 19:15:00.000000' WHERE id = 1;
            $$, 'raise_exception');
    end
$test$;
rollback;