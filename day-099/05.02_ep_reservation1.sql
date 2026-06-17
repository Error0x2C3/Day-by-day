set search_path to public, auth;

create or replace function get_reservations_client()
    returns setof json as
$$
begin
    perform auth.check_logged();

    if auth.role() != 'client' then
        raise exception 'access denied: you need to have the client role to execute this action';
    end if;

    return query
        select json_build_object(
                       'id', res.id,
                       'client', res.client,
                       'timestamp', res.datetime,
                       'number_of_guests', res.number_of_guests,
                       'status', res.status,
                       'restaurant_city', r.city,
                       'restaurant_name', r.name
               )
        from reservations res
        join restaurants r on res.restaurant = r.id
        where res.client = auth.id()
        order by datetime desc;
end;
$$ language plpgsql security definer;

grant execute on function get_reservations_client to authenticated;

create or replace function get_reservation_client(reservation_id int)
    returns json as
$$
begin
    perform auth.check_logged();

    if auth.role() != 'client' then
        raise exception 'access denied: you need to have the client role to execute this action';
    end if;

    return (select json_build_object(
                           'id', res.id,
                           'client', res.client,
                           'timestamp', res.datetime,
                           'number_of_guests', res.number_of_guests,
                           'status', res.status,
                           'special_requests', res.special_requests,
                           'restaurant_name', r.name,
                           'restaurant_city', r.city,
                           'restaurant_address', r.address,
                           'restaurant_phone', r.phone
                   )
            from reservations res
            join restaurants r on res.restaurant = r.id
            where res.id = reservation_id
    );
end;
$$ language plpgsql security definer;

grant execute on function get_reservation_client to authenticated;

-- Récupére toutes les réservations pour un restaurant_id donné.
create or replace function get_reservations_manager(restaurant_id int)
    returns setof json as
$$
begin
    perform auth.check_logged();

    if auth.role() != 'manager' then
        raise exception 'access denied: you need to have the manager role to execute this action';
    end if;

    return query
    select json_build_object(
                           'id', res.id,
                           'client', res.client,
                           'owner_name', u.full_name,
                           'timestamp', res.datetime,
                           'number_of_guests', res.number_of_guests,
                           'status', res.status,
                           'restaurant_name', r.name
                   )
            from reservations res
            join restaurants r on res.restaurant = r.id
            join users u on res.client = u.id
            where res.restaurant = restaurant_id
            order by res.datetime desc;
end;
$$ language plpgsql security definer;

grant execute on function get_reservations_manager to authenticated;

create or replace function get_reservation_manager(reservation_id int)
    returns json as
$$
begin
    perform auth.check_logged();

    if auth.role() != 'manager' then
        raise exception 'access denied: you need to have the manager role to execute this action';
    end if;

    return (select json_build_object(
                           'id', res.id,
                           'client', res.client,
                           'restaurant_id', res.restaurant,
                           'timestamp', res.datetime,
                           'number_of_guests', res.number_of_guests,
                           'status', res.status,
                           'special_requests', res.special_requests,
                           'owner', (select row_to_json(r)
                                    from (select full_name, email, phone
                                          from users
                                          where id = res.client
                                          ) r
                                    ),
                           'restaurant_name', (select name
                                               from restaurants
                                               where id = res.restaurant),
                           'tables', (select coalesce(json_agg(ta), '[]'::json)
                                      from (select t.id,t.table_number, t.capacity
                                            from tables t
                                            join reservation_tables rt on rt.table = t.id
                                            where rt.reservation = res.id
                                            ) ta
                                      )
                   )

            from reservations res
            where res.id = reservation_id);
end;
$$ language plpgsql security definer;

grant execute on function get_reservation_manager to authenticated;

-- select auth.login_for_test('gedielman@epfc.eu');
-- select get_reservation_manager(21);
--select auth.logout_for_test();

create or replace function cancel_reservation(reservation_id int)
returns void as
$$
begin
    PERFORM auth.check_logged();

    IF NOT can_cancel_reservation(reservation_id) THEN
        RAISE EXCEPTION 'access denied cancel: vous n''avez pas les droits sur cette réservation ou elle n''existe pas';
    END IF;

    if auth.role() not in ('manager', 'client') then
        raise exception 'access denied: you need to have the manager or the client role to execute this action';
    end if;

    delete from reservation_tables where reservation = reservation_id;

    update reservations
    set status = 'cancelled'
    where id = reservation_id;
end;
$$language plpgsql security definer;

grant execute on function cancel_reservation to authenticated;

create or replace function completed_reservation(reservation_id int)
returns void as
$$
declare
    _is_authorized boolean := false;
begin
    perform auth.check_logged();

    if auth.role() not in ('manager') then
        raise exception 'access denied: you need to have the manager role to execute this action';
    end if;

    SELECT EXISTS (
        SELECT 1
        FROM reservations res
                 JOIN restaurant_managers rm ON res.restaurant = rm.restaurant
        WHERE res.id = reservation_id AND rm.manager = auth.id()
    ) INTO _is_authorized;

    IF NOT _is_authorized THEN
        RAISE EXCEPTION 'access denied completed: vous n''avez pas les droits sur cette réservation ou elle n''existe pas';
    END IF;

    update reservations
    set status = 'completed'
    where id = reservation_id;
end;
$$language plpgsql security definer;

grant execute on function completed_reservation to authenticated;

create or replace function get_available_slots(restaurantId int, datechosen Date, reservationId int default null)
    returns setof json as
$$
declare
    v_slot_duration int;
    v_current_time timestamp;
begin
    perform auth.check_logged();
    if auth.role() != 'client' then
        raise exception 'access denied: you need to have the client role to execute this action';
    end if;

    select slot_duration into v_slot_duration
    from restaurants r
    where id = restaurantId;

    select simulated_time into v_current_time
    from system_time st
    limit 1;

    return query
        select json_build_object(
                       'time', to_char(slot::time, 'HH24:MI')
               )
        from services s,
             generate_series(
                     (datechosen + s.start_time),
                     (datechosen + s.end_time - (v_slot_duration * interval '1 minute')),
                     (v_slot_duration * interval '1 minute')
             ) as slot
        where s.restaurant = restaurantId
          and s.day_of_week = extract(ISODOW FROM datechosen)
          and slot > v_current_time
          and not exists(select 1
                         from reservations r
                         where r.client = auth.id()
                           and r.datetime::date = datechosen
                           and r.datetime::time >= s.start_time
                           and r.datetime::time < s.end_time
                           and r.restaurant = restaurantId
                           and r.status in ('pending', 'confirmed')
                           and (reservationId is null or r.id != reservationId)
        );
end;
$$language plpgsql security definer;

grant execute on function get_available_slots to authenticated;

create or replace function get_available_capacity(restaurantId int, p_datetime timestamp, reservationId int default null)
returns int as
$$
declare v_total_capacity int;
declare v_already_booked_guests int;
begin
    perform auth.check_logged();

    select coalesce(sum(capacity), 0) into v_total_capacity
    from tables
    where restaurant = restaurantId;

    select coalesce(sum(r.number_of_guests), 0) into v_already_booked_guests
    from reservations r
    where r.datetime = p_datetime
    and r.restaurant = restaurantId
    and r.status in ('pending', 'confirmed')
    and (reservationId is null or r.id != reservationId);

    return v_total_capacity - v_already_booked_guests;
end;
$$language plpgsql security definer;

grant execute on function get_available_capacity to authenticated;

create or replace function create_reservation(restaurantId int, p_datetime timestamp, guests int, requests text)
returns void as
$$
begin
    perform auth.check_logged();

    IF auth.role() != 'client' THEN
        RAISE EXCEPTION 'access denied: seul un client peut créer une réservation.';
    END IF;

    insert into reservations (client, restaurant, datetime, number_of_guests, special_requests, status)
    values (auth.id(), restaurantId, p_datetime, guests, requests, 'pending');
end;
$$language plpgsql security definer;

grant execute on function create_reservation to authenticated;


create or replace function update_reservation(
    reservation_id int,
    new_datetime timestamp,
    new_guests int,
    new_requests text
)
    returns void as
$$
begin
    perform auth.check_logged();

    DELETE FROM reservation_tables WHERE reservation = reservation_id;

    update reservations
    set datetime = new_datetime,
        number_of_guests = new_guests,
        special_requests = new_requests,
        status = 'pending'
    where id = reservation_id
      and client = auth.id(); -- Seul le propriétaire peut modifier la résrvation.

    -- Si aucune ligne n'a été modifiée, c'est que soit l'ID n'existe pas,
    -- soit ce n'est pas la réservation du client.
    if not found then
        raise exception 'Modification impossible : réservation introuvable ou accès refusé.';
    end if;
end;
$$language plpgsql security definer;

grant execute on function update_reservation to authenticated;


create or replace function update_reservation_edition(
    reservation_id int,
    new_datetime timestamp,
    new_guests int,
    new_requests text
)
    returns void as
$$
begin
    perform auth.check_logged();

    -- On supprime les sièges liés à cette réservation,
    -- Car une réservation 'pending' (est changé plus bas) ne peut pas avoir de table (BR-12).
    delete from reservation_tables where reservation =  reservation_id;
    update reservations
    set datetime = new_datetime,
        number_of_guests = new_guests,
        special_requests = new_requests,
        status = 'pending' -- On fait passé la réservation de 'confirmed' à  'pending'.
    where id = reservation_id
      and client = auth.id(); -- Seul le propriétaire peut modifier la résrvation.

    -- Si aucune ligne n'a été modifiée, c'est que soit l'ID n'existe pas,
    -- soit ce n'est pas la réservation du client.
    if not found then
        raise exception 'Modification impossible : réservation introuvable ou accès refusé.';
    end if;
end;
$$language plpgsql security definer;

grant execute on function update_reservation_edition to authenticated;


-- Récupérer les tables disponibles dans un restaurant donnée pour assigner les réservations.
CREATE OR REPLACE FUNCTION get_available_tables_for_assignment(reservation_id int)
RETURNS SETOF json AS $$
    DECLARE
        restaurant_id int;
        restaurant_date timestamp;
        service_id_for_réservation int;
    BEGIN
        perform auth.check_logged();
        if auth.role() not in ('manager') then
            raise exception 'access denied: you need to have the manager role to execute this action';
        end if;
        -- On récupère les infos de la réservation pour restaurant_id et restaurant_date.
       SELECT restaurant, datetime INTO restaurant_id, restaurant_date FROM reservations WHERE id = reservation_id;

        -- On récupére l'id du service correspondant à l'heure de réservation.
       select s.id into service_id_for_réservation
        from services s
        where s.restaurant = restaurant_id
            and s.day_of_week =  extract( isodow from restaurant_date)::int -- jour dans la semaine.
            and s.start_time <= (select r.datetime::time from reservations r where r.id = reservation_id)
            and s.end_time >= (select r.datetime::time from reservations r where r.id = reservation_id);

       RETURN QUERY
           SELECT json_build_object(
                  'id', t.id,
                  'table_number', t.table_number,
                  'capacity', t.capacity,
                  'is_available', Not exists (
                      -- Vérifie s'il éxiste une réservation confirmée et complétée pour le restaurant et au même service.
                      select 1
                      from reservation_tables rt
                      join reservations r on r.id = rt.reservation
                      join services s on s.restaurant = r.restaurant
                      where  rt."table" = t.id
                        -- Trie sur Restaurant
                        and r.restaurant = restaurant_id
                        -- Pour avoir la réservation correspondant au jour prvis de la réseration ( mais pas à la même heure).
                        --  timestamp ex: 2024-12-05 20:00:00 / Date ex: 2024-12-05.
                        and r.datetime::date = restaurant_date::date
                        and r.status in ('confirmed','completed') -- A travers ça on a les tables occupées.
                        and r.id != reservation_id -- On exclu notre réservation.
                        -- Trie sur Service
                        and s.id = service_id_for_réservation -- Pour prendre le service liée correspondant à l'heure de notre réservation.
                        -- L'heure de la réservation doit être compris entre celle du service restaurant.
                        and r.datetime::time >= s.start_time
                        and r.datetime:: time <= s.end_time
                  )
              )
           FROM tables t
           WHERE t.restaurant = restaurant_id
--            -- la table ne doit pas être déjà prise/comprise par une autre réservation CONFIRMÉE ou TERMINÉE.
--            AND t.id NOT IN (
--                   SELECT rt.table
--                   FROM reservation_tables rt
--                       JOIN reservations r ON r.id = rt.reservation
--                   WHERE r.status IN ('confirmed', 'completed')
--                     AND r.datetime = restaurant_date -- même créneau horaire.
--                     AND r.id != reservation_id -- exclu notre réservation.
--                   )
           ORDER BY t.table_number;
    END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
grant execute on function get_available_tables_for_assignment to authenticated;



-- Confirmer et assigner les réservations.
CREATE OR REPLACE FUNCTION confirm_reservation_with_tables(reservation_id int, table_ids int[])
RETURNS void AS $$
    BEGIN
        perform auth.check_logged();
        if auth.role() not in ('manager') then
            raise exception 'access denied: you need to have the manager role to execute this action';
        end if;
        -- BR-12: On change d'abord le statut pour pouvoir ajouter des tables.
        UPDATE reservations SET status = 'confirmed' WHERE id = reservation_id;
        -- On insère les tables choisies.
        INSERT INTO reservation_tables (reservation, "table")
        -- déroule la liste des int, et insére chaque élément de la liste d'int.
        SELECT reservation_id, unnest(table_ids);
    END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

grant execute on function confirm_reservation_with_tables to authenticated;



CREATE OR REPLACE FUNCTION get_restaurant_by_reservation(reservation_id int)
RETURNS  json AS $$
    BEGIN
        perform auth.check_logged();
        if auth.role() not in ('manager', 'client') then
            raise exception 'access denied: you need to have the manager or the client role to execute this action';
        end if;
        RETURN
            (
                select json_build_object(
                               'id', res.id,
                               'name', res.name,
                               'address', res.address,
                               'city', res.city,
                               'phone', res.phone,
                               'description', res.description,
                               'rating', res.rating,
                               'price_range', res.price_range,
                               'slot_duration', res.slot_duration,
                               'last_reservation_datetime', (select datetime
                                                             from reservations
                                                             where res.id = restaurant and client = auth.id()
                                                             order by datetime desc
                                                             limit 1
                               ),
                               'count_pending_reservation', (select count(*)
                                                             from reservations
                                                             where res.id = restaurant and status = 'pending' and client = auth.id()
                               )
                )
                FROM restaurants res
                JOIN reservations ON reservations.restaurant = res.id
                WHERE reservations.id = reservation_id
            );
    END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
grant execute on function get_restaurant_by_reservation to authenticated;

grant execute on function update_reservation to authenticated;

create or replace function get_form_context(restaurantId int, date timestamp, reservationId int default null)
    returns json as
$$
declare result json;
begin
    select json_build_object(
                   'slots', (select  json_agg(s) from get_available_slots(restaurantId, date::date, reservationId)s),
                   'capacity', get_available_capacity(restaurantId, date, reservationId),
                   'has_existing', exists(
                select 1 from reservations
                where client = auth.id()
                  and datetime::date = date::date
                  and restaurant = restaurantId
                  and (reservationId is null or id != reservationId)

            )
           ) into result;
    return result;
end;
$$language plpgsql security definer;

grant execute on function get_form_context to authenticated;

create or replace function can_cancel_reservation(reservation_id int)
    returns boolean as
$$
declare
    _is_authorized boolean := false;
begin

    IF auth.role() = 'client' THEN
        SELECT EXISTS (
            SELECT 1
            FROM reservations
            WHERE id = reservation_id AND client = auth.id()
        ) INTO _is_authorized;

    ELSIF auth.role() = 'manager' THEN
        SELECT EXISTS (
            SELECT 1
            FROM reservations res
                JOIN restaurant_managers rm ON res.restaurant = rm.restaurant
            WHERE res.id = reservation_id AND rm.manager = auth.id()
        ) INTO _is_authorized;
    END IF;

    return _is_authorized;
end;
$$language plpgsql security definer;

grant execute on function can_cancel_reservation to authenticated;