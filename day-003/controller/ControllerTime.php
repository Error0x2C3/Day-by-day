<?php

require_once "framework/Controller.php";
require_once "utils/AppTime.php";

class ControllerTime extends Controller {

    public function index(): void {
        if (!Configuration::is_dev()) {
            $this->redirect();
        }

        (new View("time"))->show();
    }

    private function redirect_back() {
        // Redirect back to the same page 
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer) {
            header("Location: " . $referer);
            die;
        } else {
            $this->redirect();
        }
    }


    public function advance(): void {
        if (!Configuration::is_dev()) {
            $this->redirect();
        }

        $amount = $_POST['amount'] ?? 1;
        $unit = $_POST['unit'] ?? 'hour';

        AppTime::add_period((int)$amount, $unit);

        $this->redirect_back();
    }

    public function reset(): void {
        if (!Configuration::is_dev()) {
            $this->redirect();
        }

        AppTime::reset_offset();

        $this->redirect_back();
    }
}