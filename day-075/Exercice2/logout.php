<?php
    require_once("functions.php");
    $_SESSION = array();
    session_destroy();
    redirect('index.php');
?>
