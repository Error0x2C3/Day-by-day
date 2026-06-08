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
                                      from (select t.table_number, t.capacity
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
    perform auth.check_logged();

    if auth.role() not in ('manager', 'client') then
        raise exception 'access denied: you need to have the manager or the client role to execute this action';
    end if;

    update reservations
    set status = 'cancelled'
    where id = reservation_id;
end;
$$language plpgsql security definer;

grant execute on function cancel_reservation to authenticated;

create or replace function completed_reservation(reservation_id int)
returns void as
$$
begin
    perform auth.check_logged();

    if auth.role() not in ('manager') then
        raise exception 'access denied: you need to have the manager role to execute this action';
    end if;

    update reservations
    set status = 'completed'
    where id = reservation_id;
end;
$$language plpgsql security definer;

grant execute on function completed_reservation to authenticated;

create or replace function get_available_slots(restaurantId int, datechosen Date)
    returns setof json as
$$
declare v_slot_duration int;
    declare v_current_time timestamp;
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
        );
end;
$$language plpgsql security definer;

grant execute on function get_available_slots to authenticated;

create or replace function get_available_slots_edition(restaurantId int, datechosen Date)
    returns setof json as
$$
declare v_slot_duration int;
    declare v_current_time timestamp;
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
          and s.day_of_week = extract(ISODOW FROM datechosen);
          --and slot > v_current_time;

end;
$$language plpgsql security definer;

grant execute on function get_available_slots_edition to authenticated;

create or replace function check_capacity_warning(restaurantId int, p_datetime timestamp, guests int)
returns boolean as
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
    and r.status in ('pending', 'confirmed');

    return (v_already_booked_guests + guests) > v_total_capacity;
end;
$$language plpgsql security definer;

grant execute on function check_capacity_warning to authenticated;

create or replace function check_capacity_warning_edition(
    reservation_id int,
    restaurant_id int,
    p_datetime timestamp,
    guests int
)
    returns boolean as
$$
declare v_total_capacity int;
    declare v_already_booked_guests int;
begin
    -- Vérification de sécurité
    perform auth.check_logged();

    -- 1. Récupérer la capacité totale de toutes les tables du restaurant
    select coalesce(sum(capacity), 0) into v_total_capacity
    from tables
    where restaurant = restaurant_id;

    -- 2. Calculer le nombre d'invités déjà réservés pour ce créneau,
    -- en excluant la réservation qu'on est en train de modifier (reservation_id)
    select coalesce(sum(r.number_of_guests), 0) into v_already_booked_guests
    from reservations r
    where r.datetime = p_datetime
      and r.restaurant = restaurant_id
      and r.status in ('pending', 'confirmed')
      and r.id != reservation_id; -- C'est ici que se fait la différence

    -- 3. Retourner TRUE si le total (autres réservations + nouveaux convives) dépasse la capacité
    return (v_already_booked_guests + guests) > v_total_capacity;
end;
$$language plpgsql security definer;
-- Ne pas oublier les droits d'exécution
grant execute on function check_capacity_warning_edition to authenticated;

create or replace function create_reservation(restaurantId int, p_datetime timestamp, guests int, requests text)
returns void as
$$
begin
    perform auth.check_logged();
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

    update reservations
    set datetime = new_datetime,
        number_of_guests = new_guests,
        special_requests = new_requests
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
    BEGIN
        perform auth.check_logged();
        if auth.role() not in ('manager') then
            raise exception 'access denied: you need to have the manager role to execute this action';
        end if;
        -- on récupère les infos de la réservation.
       SELECT restaurant, datetime INTO restaurant_id, restaurant_date FROM reservations WHERE id = reservation_id;
       RETURN QUERY
           SELECT json_build_object(
                  'id', t.id,
                  'table_number', t.table_number,
                  'capacity', t.capacity
              )
           FROM tables t
           WHERE t.restaurant = restaurant_id
           -- la table ne doit pas être déjà prise par une autre réservation CONFIRMÉE ou TERMINÉE.
           AND t.id NOT IN (
                  SELECT rt.table
                  FROM reservation_tables rt
                      JOIN reservations r ON r.id = rt.reservation
                  WHERE r.status IN ('confirmed', 'completed')
                    AND r.datetime = restaurant_date -- même créneau horaire.
                    AND r.id != reservation_id -- exclu notre réservation.
                  )
           ORDER BY t.table_number;
    END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
grant execute on function get_available_tables_for_assignment to authenticated;

-- Confirmer et assigner les réservations.
CREATE OR REPLACE FUNCTION confirm_and_assign_tables(p_reservation_id int, p_table_ids int[])
RETURNS void AS $$
    BEGIN
        perform auth.check_logged();
        if auth.role() not in ('manager') then
            raise exception 'access denied: you need to have the manager role to execute this action';
        end if;
        -- BR-12: On change d'abord le statut pour pouvoir ajouter des tables.
        UPDATE reservations SET status = 'confirmed' WHERE id = p_reservation_id;
        -- On insère les tables choisies.
        INSERT INTO reservation_tables (reservation, "table")
        SELECT p_reservation_id, unnest(p_table_ids);
    END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

grant execute on function confirm_and_assign_tables to authenticated;