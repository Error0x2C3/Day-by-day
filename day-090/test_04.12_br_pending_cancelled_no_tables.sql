set search_path to public;
/*
Par défaut, la BDD vérifie chaque règle immédiatement après chaque commande,
SET CONSTRAINTS ALL DEFERRED permet de faire toutes les manips (qui rendront les données "fausses" temporairement)
sans bloquer tout et ceux avant de finir /commit.
 */
SET CONSTRAINTS ALL DEFERRED;
/*(BR-12) Une réservation en attente ou annulée ne peut être associée à aucune une table.*/


-- Cas positif
begin;
do
$test$

    DECLARE
        new_reservations_id1 int;
    begin
        raise notice 'TEST BR-12: Une reservation pending ou cancelled ne peut pas avoir de table associée.';
        Insert Into reservations(client, restaurant, datetime, number_of_guests,status)
        VALUES (3,1,'2026-11-19 19:00:00.000000',2,'pending')
        RETURNING id into new_reservations_id1;
        update reservations set status ='confirmed' where id = new_reservations_id1;
        insert into reservation_tables (reservation, "table") VALUES (new_reservations_id1,1);
        /*
        SET CONSTRAINTS ALL IMMEDIATE;
        Force la vérification toutes les contraintes
        (y compris les triggers différés car ce sont aussi des contraintes) immédiatement.
        Doit être mis après la commande qui va déclenché l'erreur.
        */
        SET CONSTRAINTS ALL IMMEDIATE;
    end
$test$;
rollback;


-- cas négatif (1
begin;

do
$test$
    begin
        RAISE NOTICE 'TEST BR-12: Une reservation pending ou cancelled ne peut pas avoir de table associée.';
        PERFORM should_fail($$
        DO $inner$
        DECLARE
            new_reservations_id1 int;
            new_reservations_id2 int;
        BEGIN
            Insert Into reservations(client, restaurant, datetime, number_of_guests,status)
            VALUES (3,1,'2026-11-19 19:00:00.000000',2,'pending')
            RETURNING id into new_reservations_id1;
            insert into reservation_tables (reservation, "table") VALUES (new_reservations_id1,1);


            INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
            VALUES (4, 1, '2026-05-14 20:30:00.000000', 2, 'pending')
            RETURNING id INTO new_reservations_id2;
            update reservations set status ='cancelled' where id = new_reservations_id2;
            insert into reservation_tables (reservation, "table") VALUES (new_reservations_id2, 1);
        END $inner$;
            $$, 'raise_exception');
    end
$test$;
rollback;


-- Cas négatif (1 mais syntaxe différente.
begin;
do
$test$

    DECLARE
        new_reservations_id1 int;
        new_reservations_id2 int;
    begin
        raise notice 'Aucune erreur capturée !.';
        Insert Into reservations(client, restaurant, datetime, number_of_guests,status)
        VALUES (3,1,'2026-11-19 19:00:00.000000',2,'pending')
        RETURNING id into new_reservations_id1;
        insert into reservation_tables (reservation, "table") VALUES (new_reservations_id1,1);


        INSERT INTO reservations (client, restaurant, datetime, number_of_guests, status)
        VALUES (4, 1, '2026-05-14 20:30:00.000000', 2, 'pending')
        RETURNING id INTO new_reservations_id2;
        update reservations set status ='cancelled' where id = new_reservations_id2;
        insert into reservation_tables (reservation, "table") VALUES (new_reservations_id2, 1);
        /*
        SET CONSTRAINTS ALL IMMEDIATE;
        Force la vérification toutes les contraintes
        (y compris les triggers différés car ce sont aussi des contraintes) immédiatement.
        Doit être mis après la commande qui va déclenché l'erreur.
        */
        SET CONSTRAINTS ALL IMMEDIATE;
    EXCEPTION
        WHEN OTHERS THEN
            IF SQLSTATE = 'P0001' THEN /* 'P0001' est le code pour raise_exception. */
                RAISE NOTICE 'TEST BR-12 Succès : La contrainte BR-012 a bien été levée';
            ELSE
                RAISE EXCEPTION 'Erreur inattendue : % (%)', SQLERRM, SQLSTATE;
            END IF;
    end
$test$;
rollback;


begin;
do
$test$
    begin
        raise notice 'TEST BR-12: invalide reservation_tables';
        perform should_fail($$
            INSERT INTO reservation_tables (reservation, "table")
            VALUES (8, 1);
        $$, 'raise_exception');
    end
$test$;
rollback;

begin;
do
$test$
    begin
        raise notice 'TEST BR-12: valide reservation_tables';
        INSERT INTO reservation_tables (reservation, "table")
        VALUES (1, 2);

        set constraints all immediate;
    end
$test$;
rollback;

begin;
do
$test$
    begin
        raise notice 'TEST BR-12: valide suppression reservations';
        update reservations
        SET status = 'cancelled'
        WHERE id = 6;

        set constraints all immediate;
    end
$test$;
rollback;