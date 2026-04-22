<?php

require_once 'View.php';

class Tools {

    //nettoie le string donné
    public static function sanitize(string $var): string {
        return trim(htmlspecialchars($var, ENT_QUOTES, "UTF-8"));
    }

    //dirige vers la page d'erreur
    public static function abort(string $err): void {
        http_response_code(500);
        (new View("error"))->show(array("error" => $err));
        die;
    }
}
