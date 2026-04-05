set search_path to public;
-- Contraintes de check pour les règles métier invariantes (hors BR-xx)

/*
Utilisateurs :
    -Le nom complet doit avoir une longueur d'au moins 3 caractères.
    -Le mot de passe doit avoir au minimum une longueur de 8 caractères,
      doit contenir au moins un chiffre, une lettre majuscule,
      une lettre minuscule et un caractère spécial parmi les suivants : ,;.:!?/$%&@#.
    -Le mot de passe doit être stocké de manière sécurisée (hachage + sel).
On ne peut pas modifier le rôle d'un utilisateur.
 */

Alter TABLE users
    drop constraint if exists email_unique;

ALTER TABLE users
    ADD CONSTRAINT email_unique
        UNIQUE (email); --Voir schéma--

Alter TABLE users
    drop constraint if exists full_name_unique;

ALTER TABLE users
    ADD CONSTRAINT full_name_unique
        UNIQUE (full_name);--Voir schéma--

alter table users
    drop constraint if exists count_nom;

alter table users
    add constraint count_nom
    --Le nom complet doit avoir une longueur d'au moins 3 caractères.--
        check (lower(trim(full_name)) >= 3);

alter table users
    drop constraint if exists mtp_constraint;

alter table users
    ADD CONSTRAINT mdp_constraint
        /*
         -Le mot de passe doit avoir au minimum une longueur de 8 caractères,
            doit contenir au moins un chiffre, une lettre majuscule,
            une lettre minuscule et un caractère spécial parmi les suivants : ,;.:!?/$%&@#.
         */
        check ( lower(trim(password)) >= 8 and lower(trim(password)) ILIKE '/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[,;.:!?\/$%&@#]).+$/');

alter table users
    drop constraint if exists mdp_hash;

alter table users
    ADD CONSTRAINT mdp_hash
        check ( hash = auth.crypt(new.password, auth.gen_salt('bf')); )