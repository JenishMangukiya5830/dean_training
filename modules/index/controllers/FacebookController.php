<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace index\controllers;

use Pingle\Controller\Controller;

Class FacebookController extends Controller {

    public function IndexAction() {        
        header('Location: https://www.facebook.com/247354788671731/');
        die();
    }

}
