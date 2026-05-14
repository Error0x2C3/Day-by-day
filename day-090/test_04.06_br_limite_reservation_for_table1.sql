set search_path to public;
/*
Par défaut, la BDD vérifie chaque règle immédiatement après chaque commande,
SET CONSTRAINTS ALL DEFERRED permet de faire toutes les manips (qui rendront les données "fausses" temporairement)
sans bloquer tout et ceux avant de finir /commit.
 */
SET CONSTRAINTS ALL DEFERRED;
-- cas positif
begin;
/* INSERT INTO reservations ( client, restaurant, datetime, number_of_guests, status, special_requests) */
-- INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
-- VALUES (4, 1, '2026-05-14 12:00:00.000000', 2, 'pending');
do
$test$
    DECLARE new_reservations_id int;
    begin
        RAISE NOTICE 'TEST BR-06 : Une table ne peut pas faire l''objet de deux réservations terminées ou confirmées pour une même date et un même service.';
        INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
        VALUES (4, 1, '2026-05-14 21:00:00.000000', 2, 'pending')
        RETURNING id INTO new_reservations_id;
        update reservations set status ='confirmed' where id = new_reservations_id;
        insert into reservation_tables (reservation, "table") VALUES (new_reservations_id,1);
        /*
        SET CONSTRAINTS ALL IMMEDIATE;
        Force la vérification toutes les contraintes
        (y compris les triggers différés car ce sont aussi des contraintes) immédiatement.
        Doit être mis après la commande qui va déclenché l'erreur.
        */
        SET CONSTRAINTS ALL IMMEDIATE;

        --     exception when others then
--         -- Si on a une erreur, il sera affiché dans la console directement.
--         raise notice 'Erreur capturée : %', SQLERRM;
    end
$test$;
rollback;


-- cas négatif (1
begin;

do
$test$
    begin
        RAISE NOTICE 'TEST BR-06 : Une table  ne peut pas faire l''objet de deux réservations terminées ou confirmées pour une même date et un même service.';
        PERFORM should_fail($$
        DO $inner$
        DECLARE
            new_reservations_id1 int;
            new_reservations_id2 int;
        BEGIN
            INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
            VALUES (3, 1, '2026-05-14 20:30:00.000000', 2, 'pending')
            RETURNING id INTO new_reservations_id1;
            update reservations set status ='confirmed' where id = new_reservations_id1;
            insert into reservation_tables (reservation, "table") VALUES (new_reservations_id1,1);

            INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
            VALUES (4, 1, '2026-05-14 21:00:00.000000', 2, 'pending')
            RETURNING id INTO new_reservations_id2;
            update reservations set status ='confirmed' where id = new_reservations_id2;
            insert into reservation_tables (reservation, "table") VALUES (new_reservations_id2,1);
        END $inner$;
            $$, 'raise_exception');
    end
$test$;
rollback;


-- cas négatif idem au cas (1 mais syntaxe différente.
begin;

do
$test$

    DECLARE
        new_reservations_id1 int;
        new_reservations_id2 int;
    begin
        RAISE NOTICE 'TEST BR-06 : Aucune erreur capturée !.';
        INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
        VALUES (3, 1, '2026-05-14 20:30:00.000000', 2, 'pending')
        RETURNING id INTO new_reservations_id1;
        update reservations set status ='confirmed' where id = new_reservations_id1;
        insert into reservation_tables (reservation, "table") VALUES (new_reservations_id1, 1);

        INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
        VALUES (4, 1, '2026-05-14 20:30:00.000000', 2, 'pending')
        RETURNING id INTO new_reservations_id2;
        update reservations set status ='confirmed' where id = new_reservations_id2;
        insert into reservation_tables (reservation, "table") VALUES (new_reservations_id2, 1);
        /*
         Force la vérification toutes les contraintes
         (y compris les triggers différés car ce sont aussi des contraintes) immédiatement.
        */
        SET CONSTRAINTS ALL IMMEDIATE;
        /* Affiche l'erreur dans la console.*/
    EXCEPTION
        WHEN OTHERS THEN
            IF SQLSTATE = 'P0001' THEN /* 'P0001' est le code pour raise_exception. */
                RAISE NOTICE 'TEST BR_06 Succès : La contrainte BR-06 a bien été levée';
            ELSE
                RAISE EXCEPTION 'Erreur inattendue : % (%)', SQLERRM, SQLSTATE;
            END IF;
    end
$test$;
rollback;


