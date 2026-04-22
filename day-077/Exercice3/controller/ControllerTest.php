<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerTest extends Controller {

    public function index() : void {
        
    }

    public function postarray() : void{
        $txt = [];
        $chk = [];
        if (isset($_POST['txt'])) {
            $txt = $_POST['txt'];
        }
        if (isset($_POST['chk'])) {
            $chk = $_POST['chk'];
        }
        (new View("test_postarray"))->show(["txt" => $txt, "chk" => $chk]);
    }
    
    
    public function testparams() : void {
        if(isset($_GET["param1"]))
            echo "param1 : " . $_GET["param1"] . "<br>";
        if(isset($_GET["param2"]))
            echo "param2 : " . $_GET["param2"] . "<br>";
        if(isset($_GET["param3"]))
            echo "param3 : " . $_GET["param3"] . "<br>";   
    }

}
