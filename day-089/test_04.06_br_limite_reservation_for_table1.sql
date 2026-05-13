set search_path to public;
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
          Force la vérification toutes les contraintes
          (y compris les triggers différés car ce sont aussi des contraintes) immédiatement.
         */
        SET CONSTRAINTS ALL IMMEDIATE;

--     exception when others then
--         -- Si on a une erreur, il sera affiché dans la console directement.
--         raise notice 'Erreur capturée : %', SQLERRM;
    end
$test$;
rollback;


