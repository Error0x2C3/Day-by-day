/******************************************
  Checks de la table Restaurants
******************************************/

/* 1.Le nom doit avoir au minimum une longueur de 5 caractères. */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_name;

ALTER TABLE restaurants
    ADD CONSTRAINT check_name
    CHECK (LENGTH(TRIM(name)) >= 5);

/* 2.L'adresse doit avoir au minimum une longueur de 5 caractères. */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_address;

ALTER TABLE restaurants
    ADD CONSTRAINT check_address
        CHECK (LENGTH(TRIM(address)) >= 5);

/* 3.La ville doit avoir au minimum une longueur de 3 caractères. */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_city;

ALTER TABLE restaurants
    ADD CONSTRAINT check_city
        CHECK (LENGTH(TRIM(city)) >= 3);

/* 4.Le numéro de téléphone doit avoir un format de numéro belge valide,
    et peut être encodé avec ou sans espaces de séparation entre les groupes de chiffres. (à terminer) */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_phone_number;

ALTER TABLE restaurants
    ADD CONSTRAINT check_phone_number
        CHECK (phone ~ '');

/* 5.La description, si elle est fournie, doit avoir au minimum une longueur de 10 caractères. */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_description;

ALTER TABLE restaurants
    ADD CONSTRAINT check_description
        CHECK (LENGTH(TRIM(description)) >= 10);

/* 6.La note doit être comprise entre 0.0 et 5.0 si elle est définie. */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_rating;

ALTER TABLE restaurants
    ADD CONSTRAINT check_rating
        CHECK (rating BETWEEN 0.0 AND 5.0);

/* 7.La fourchette de prix doit être comprise entre 1 et 4 si elle est définie. */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_price_range;

ALTER TABLE restaurants
    ADD CONSTRAINT check_price_range
        CHECK (price_range BETWEEN 1 AND 4);

/* 8.La durée de créneau de réservation doit être soit 10, 15, 20, 30 ou 60 minutes. */

ALTER TABLE restaurants
    DROP CONSTRAINT IF EXISTS check_slot_duration;

ALTER TABLE restaurants
    ADD CONSTRAINT check_slot_duration
        CHECK (slot_duration IN (10, 15, 20, 30, 60));

/******************************************
  Checks de la table Tables
******************************************/

/* 1.Le numéro de table doit être strictement positif et unique au sein d'un même restaurant. */

ALTER TABLE tables
    DROP CONSTRAINT IF EXISTS check_positif;

ALTER TABLE tables
    ADD CONSTRAINT check_positif
        CHECK (table_number > 0);

/* 2.La capacité doit être strictement positive. */

ALTER TABLE tables
    DROP CONSTRAINT IF EXISTS check_capa_positif;

ALTER TABLE tables
    ADD CONSTRAINT check_capa_positif
        CHECK (capacity > 0);

/* 3.On ne peut pas modifier le restaurant d'une table. */

CREATE OR REPLACE FUNCTION  prevent_restaurant_table_update()
RETURNS trigger as $$
BEGIN
    IF NEW.restaurant <> OLD.restaurant THEN
        RAISE EXCEPTION 'Impossible de modifier le restaurant d''une table';
    END IF;
    RETURN NULL;
END;
    $$LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_prevent_restaurant_table_update ON tables;

CREATE TRIGGER trg_prevent_restaurant_table_update
    BEFORE UPDATE ON tables
    FOR EACH ROW
EXECUTE FUNCTION prevent_restaurant_table_update();



/******************************************
Checks de la table Réservations
******************************************/

/* 1. Le statut d'une réservation doit être soit pending (en attente de confirmation),
   confirmed (confirmée), cancelled (annulée) ou completed (terminée suite à la venue effective du client).
 */

ALTER TABLE reservations
DROP CONSTRAINT IF EXISTS check_status;
/*TODO */
-- ALTER TABLE reservations
--     ADD CONSTRAINT check_status
--         check ( reservations.status);

/* 2. Le nombre de convives d'une réservation doit être strictement positif.*/

ALTER TABLE reservations
DROP CONSTRAINT IF EXISTS positive_guests;

ALTER TABLE reservations
    ADD CONSTRAINT positive_guests
        check ( reservations.number_of_guests > 0);

/* 3. Le texte des demandes spéciales d'une réservation (special_requests),
   s'il est défini, doit avoir une longueur minimale de 10 caractères.*/

ALTER TABLE reservations
DROP CONSTRAINT IF EXISTS check_special_requests;

ALTER TABLE reservations
    ADD CONSTRAINT check_special_requests
        check ( length(trim(reservations.special_requests)) >= 10);

/* 4. On ne peut pas modifier le restaurant ou le client d'une réservation. */

CREATE OR REPLACE FUNCTION prevent_restaurant_client_update()
RETURNS trigger as $$
BEGIN
    IF NEW.restaurant <> OLD.restaurant OR NEW.client <> OLD.client THEN
        RAISE EXCEPTION 'Impossible de mofidier le restaurant ou le client d''un service';
end if;
RETURN NULL;
END;
$$LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_prevent_restaurant_client_update ON reservations;

CREATE TRIGGER trg_prevent_restaurant_client_update
    BEFORE UPDATE ON reservations
    FOR EACH ROW
    EXECUTE FUNCTION prevent_restaurant_client_update();


/******************************************
Checks de la table Services
******************************************/

/* 1. Le jour de la semaine doit être compris entre 1 (lundi) et 7 (dimanche).*/

ALTER TABLE services
DROP CONSTRAINT IF EXISTS check_days_between;

ALTER TABLE services
    ADD CONSTRAINT check_days_between
        check ( services.day_of_week BETWEEN 1 AND 7 );


/* 2.L'heure de fin doit être postérieure à l'heure de début d'au moins une heure.
   Note : l'heure de fin d'un service représente l'heure au-delà de laquelle le restaurant n'accepte plus de clients.*/

ALTER TABLE services
DROP CONSTRAINT IF EXISTS check_duration;

ALTER TABLE services
    ADD CONSTRAINT check_duration
        check ( services.end_time - services.start_time >= INTERVAL '1 HOUR');


/* 3. On ne peut pas modifier le restaurant d'un service.*/

CREATE OR REPLACE FUNCTION prevent_modify_restaurant_service()
    RETURNS trigger as $$
BEGIN
    IF NEW.restaurant <> OLD.restaurant THEN
        RAISE EXCEPTION 'Impossible de mofidier le restaurant d''un service';
end if;
RETURN NULL;
END;
$$LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_prevent_modify_restaurant_service ON services;

CREATE TRIGGER trg_prevent_modify_restaurant_service
    BEFORE UPDATE ON services
    FOR EACH ROW
    EXECUTE FUNCTION prevent_modify_restaurant_service();



/******************************************
  Checks de la table Utilisateurs
******************************************/
/* --- Contrainte d'unicité ---*/
alter table users
drop constraint if exists email_unique;

ALTER TABLE users
    ADD CONSTRAINT email_unique
        UNIQUE (email); --Voir schéma consigne--

Alter TABLE users
drop constraint if exists full_name_unique;

ALTER TABLE users
    ADD CONSTRAINT full_name_unique
        UNIQUE (full_name);--Voir schéma consigne--
/* --- Contrainte d'unicité ---*/

alter table users
drop constraint if exists count_nom;
/* Le nom complet doit avoir une longueur d'au moins 3 caractères. */
alter table users
    add constraint count_nom
        check (LENGTH(TRIM(full_name)) >= 3);

/* Le mot de passe doit avoir au minimum une longueur de 8 caractères, doit contenir au moins un chiffre, une lettre majuscule, une lettre minuscule et un caractère spécial parmi les suivants : ,;.:!?/$%&@#.*/
drop trigger if exists  passe_word_limit_trigger on users;
drop function if exists passe_word_limit();
create or replace function passe_word_limit()
    returns trigger as
$$
begin
    if tg_op = 'INSERT' OR tg_op = 'UPDATE' then
        if LENGTH(TRIM(new.password)) < 8 AND
           not (new.password ~ '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[,;.:!?/$%&@#.]).+$') then
            raise exception using message = 'invalid password';
END IF;
END IF;
return new;
end;
$$ LANGUAGE plpgsql;

create or replace trigger passe_word_limit_trigger
    before insert or update -- on peut créer un trigger pour plusieurs événements (ici insert et update)
                                   on users
                                   for each row
                                   execute function passe_word_limit();
-- on indique la fonction à exécuter


/* On ne peut pas modifier le rôle d'un utilisateur.  */

drop trigger if exists unchange_role_user_trigger on users;
drop function if exists unchange_role_user();

create or replace function unchange_role_user() returns trigger as
$$
begin
    if new.role != old.role then
        raise exception using message = 'invalid password';
end if;
return new;
end
$$ language plpgsql;

create trigger  unchange_role_user_trigger
    before update
    on users
    for each row
    execute procedure unchange_role_user();