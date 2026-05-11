set search_path to public;

-- cas negatif
begin;
do
$test$
    begin
        RAISE NOTICE 'TEST BR-06 : Une table ne peut pas faire l''objet de deux réservations terminées ou confirmées pour une même date et un même service.';
        PERFORM should_fail($$
         INSERT INTO reservations (id, client, restaurant, datetime, number_of_guests, status, special_requests)
        VALUES (23, 6, 1, '2024-11-15 19:00:00.000000', 2, 'confirmed', 'visite');
        $$, 'raise_exception');
    end
$test$;
rollback;

-- -- cas positif
-- begin;
-- do
-- $test$
--     DECLARE new_reservations_id int;
--     begin
--         RAISE NOTICE 'TEST BR-06 :le service peut etre admis car aucun service est a cette heure la ';
--         INSERT INTO reservations ( client, restaurant, datetime, number_of_guests, status, special_requests)
--         VALUES ( 6, 1, '2026-11-15 21:00:00.000000', 2, 'confirmed', 'visite')
--         RETURNING id INTO new_reservations_id;
--         INSERT INTO reservation_tables(reservation, "table") VALUES (new_reservations_id ,1);
--
--         set constraints all immediate;
--     end
-- $test$;
-- rollback;