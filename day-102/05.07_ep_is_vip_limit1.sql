set search_path to public, auth;

create or replace function toggle_is_vip(reservationsId int, newIsVip bool)
    returns json as
$$
declare

begin
    perform auth.check_logged();
    if auth.role() != 'manager' then
        raise exception 'access denied: you need to have the manager role to execute this action';
    end if;

    -- l'utilisateur connecté doit être un manager du restaurant de la réservation;
    if not exists(
        select 1
        from restaurant_managers
        where restaurant in (select reservations.restaurant from reservations where reservations = reservationsId limit 1)
        and restaurant_managers.manager = auth.id()
    )then
        raise exception 'You must be the manager of this restaurant';
    end if;
    -- la réservation doit exister;
    if not exists(
        select 1
        from reservations
        where reservations.id = reservationsId
    )then
        raise exception 'The reservations doesn''t exist';
    end if;
    -- la réservation doit avoir un statut pending ou confirmed;
    if not exists(
        select 1
        from reservations
        where reservations.id = reservationsId
        and reservations.status in ('pending','confirmed')
    )then
        raise exception 'The reservations''S status must be pending or confirmed';
    end if;

    -- la date/heure de la réservation doit être dans le futur (par rapport à l'heure du time travel).
    if not exists(
        select 1
        from reservations
        where reservations.id = reservationsId
          and reservations.status in ('pending','confirmed')
          and reservations.datetime > (select system_time.simulated_time from system_time order by simulated_time DESC limit 1)
    )then
        raise exception 'The reservations must be in the future';
    end if;
    update reservations SET is_vip = newIsVip where id = reservationsId;
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
                           ),
                           'is_vip', newIsVip
                   )

            from reservations res
            where res.id = reservationsId);
end;
$$ language  plpgsql security definer;

grant execute on function  toggle_is_vip to authenticated;


