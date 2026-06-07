set search_path to public, auth;

create or replace function get_restaurants_manager()
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
                       'name', res.name,
                       'city', res.city,
                       'rating', res.rating,
                       'price_range', res.price_range,
                       'last_reservation_datetime', (select datetime
                                                    from reservations
                                                    where res.id = restaurant
                                                    order by datetime desc
                                                    limit 1
                                                    ),
                       'count_pending_reservation', (select count(*)
                                                     from reservations
                                                     where res.id = restaurant and status = 'pending'
                                                     )
                       )
        from restaurants res
        join restaurant_managers on res.id = restaurant
        where manager = auth.id()
        order by
            (select MAX(datetime)
             from reservations
             where restaurant = res.id
            ) desc nulls last,
            res.name;
end;
$$ language plpgsql security definer;

grant execute on function get_restaurants_manager to authenticated;


create or replace function get_restaurants_client(search_filter text, limit_count int)
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
        from restaurants res
        where trim(search_filter) = ''
        OR res.name ILIKE '%' || trim(search_filter) || '%'
        OR res.description ILIKE '%' || trim(search_filter) || '%'
        OR res.city ILIKE '%' || trim(search_filter) || '%'
        OR res.address ILIKE '%' || trim(search_filter) || '%'
        order by name
        limit limit_count + 1;  -- On prend 1 de plus pour savoir s'il n'y a pas un surplus.
end;
$$ language plpgsql security definer;

grant execute on function get_restaurants_client to authenticated;