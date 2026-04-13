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


/*

Utilisateurs :

      doit contenir au moins un chiffre, une lettre majuscule,

      une lettre minuscule et un caractère spécial parmi les suivants : ,;.:!?/$%&@#.

    -Le mot de passe doit être stocké de manière sécurisée (hachage + sel).

On ne peut pas modifier le rôle d'un utilisateur.

 */

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

alter table users
    drop constraint if exists password_limit;
/* Le nom complet doit avoir une longueur d'au moins 3 caractères. */
alter table users
    /* Le mot de passe doit avoir au minimum une longueur de 8 caractères. */
    add constraint password_limit
        check (LENGTH(TRIM(password)) >=8 AND (password ~ '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[,;.:!?/$%&@#.]).+$'));


