<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace application\controllers;

use Pingle\Controller\Controller;

Class NotfoundController extends Controller {

    public function IndexAction() {
        $helper = $this->getHelper();
        $helper->Redirect('index', 'index');
    }

}
