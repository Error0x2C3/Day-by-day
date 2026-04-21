<?php
    try
    {
        $pdo = new PDO("mysql:host=localhost;dbname=my_social_network_base;charset=utf8", "root", "root");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $pdo->prepare("SELECT * FROM Members");
        $query->execute();
        $members = $query->fetchAll();
        echo "<p>Tout semble bien fonctionner</p>";
        echo "<p>Voici le contenu de la table Members dans un tableau</p>";
        echo "<pre>";
        print_r($members);
        echo "</pre>";
    }
    catch (Exception $exc)
    {
        abort("Erreur lors de l'accès à la base de données.");
    }
?>