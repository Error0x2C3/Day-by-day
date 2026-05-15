set search_path to public;

begin;
--  commence juste avant la fin du service mais dépasse sur l'heure de fermeture.
do
$test$
    begin
        raise notice 'TEST BR-04: invalide_1';
        perform should_fail($$
            INSERT INTO reservations (client, restaurant, datetime, number_of_guests)
            VALUES (3, 1, '2026-04-27 11:00:00', 2);
        $$, 'raise_exception');
    end
$test$;
rollback;

begin;
do
$test$
    begin
        raise notice 'TEST BR-04: invalide_2';
        perform should_fail($$
            INSERT INTO reservations (client, restaurant, datetime, number_of_guests)
            VALUES (3, 1, '2026-04-27 23:00:00', 2);
        $$, 'raise_exception');
    end
$test$;
rollback;

begin;
do
$test$
    begin
        raise notice 'TEST BR-04: invalide_3';
        perform should_fail($$
            INSERT INTO reservations (client, restaurant, datetime, number_of_guests)
            VALUES (3, 1, '2026-05-03 23:00:00', 2);
        $$, 'raise_exception');
    end
$test$;
rollback;

begin;
do
$test$
    begin
        raise notice 'TEST BR-04: valide_1';
        INSERT INTO reservations (client, restaurant, datetime, number_of_guests)
        VALUES (3, 1, '2026-04-27 20:00:00', 2);

        set constraints all immediate;
    end
$test$;
rollback;

begin;
do
$test$
    begin
        raise notice 'TEST BR-04: valide_2';
        INSERT INTO reservations (client, restaurant, datetime, number_of_guests)
        VALUES (3, 1, '2026-04-27 13:00:00', 2);

        set constraints all immediate;
    end
$test$;
rollback;

begin;
do
$test$
    begin
        raise notice 'TEST BR-04: valide_3';
        INSERT INTO reservations (client, restaurant, datetime, number_of_guests)
        VALUES (3, 1, '2026-05-03 13:00:00', 2);

        set constraints all immediate;
    end
$test$;
rollback;

