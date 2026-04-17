set search_path to public;

/* --- Contrainte d'unicité ---*/

-- Contrainte d'unicité sur l'email de l'user.
begin;
do
$test$
begin
        raise notice 'TEST: email du user est unique';
        perform should_fail($$
            INSERT INTO users (email, password, full_name, role)
            values ('test0001@epfc.eu', 'Password2,', 'test', 'user'),
                   ('test0001@epfc.eu', 'Password2,', 'test', 'user');
        $$,'unique_violation');
end
$test$;
rollback;

/* --- Contrainte d'unicité ---*/
-- Le nom complet doit avoir une longueur d'au moins 3 caractères.
begin;
do
$test$
    begin
            raise notice 'TEST: email du user est unique';
            perform should_fail($$
                update users set email = 'bepenelle@epfc.eu' where id = 1;
            $$, 'email_unique');
    end
$test$;
rollback;
