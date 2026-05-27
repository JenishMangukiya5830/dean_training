<?php

namespace index\services;

use Pingle\Helper\Helper;

Class Login {

    public function login() {
        $_SESSION['login'] = 1;
    }

    public function logout() {
        //login               
        $_SESSION['login'] = "";
        unset($_SESSION['login']);
    }

    public function checkLogin() {
        if (isset($_SESSION['login'])) {
            if ($_SESSION['login'] == '1') {
                return true;
            }
        }
        $helper = new Helper();
        $helper->Redirect("index", "index", "index");
    }

}
