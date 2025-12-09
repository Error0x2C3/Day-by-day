<?php
require_once "framework/Controller.php";

class ControllerBrowseItems extends Controller {
    public function index(): void {
        $this->view();
    }

    public function view(){
        (new view("browseItems"))->show();
    }
}