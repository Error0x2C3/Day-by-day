<?php
require_once "framework/Controller.php";
require_once "utils/AppTime.php";
class ControllerTest extends Controller {
    public function index(): void {
        echo "<h1>Hello PRWB_2526_f09 !</h1>";
        echo "<p><a href='time'>Time management</a><p>";
    }
}