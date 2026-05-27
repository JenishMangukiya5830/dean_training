<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace about\controllers;

use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use Pingle\Controller\Controller;

Class TermsController extends Controller {

    public function IndexAction() {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training - Terms & Conditions");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");

        $NavBar = new NavBar();
        $Footer = new Footer();
        $TitleBar = new TitleBar();
        
        $View->Render(array('navbar' => $NavBar->render(array('active' => '')),
            'titlebar' => $TitleBar->render(array('title' => 'Terms & Conditions')),
            'footer' => $Footer->render(array()),
        ));
    }
    // public function ReedAction() {
    //     $View = $this->View();
    //     //get the flag
    //     $flag = $this->getFlag();
    //     $View->setSessionFlag($flag['text'], $flag['type']);
    //     $View->setTitle("Dean Training - Terms & Conditions");
    //     $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
    //     $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");

    //     $NavBar = new NavBar();
    //     $Footer = new Footer();
    //     $TitleBar = new TitleBar();

    //     $View->Render(array('navbar' => $NavBar->render(array('active' => '')),
    //         'titlebar' => $TitleBar->render(array('title' => 'REED T&C')),
    //         'footer' => $Footer->render(array()),
    //     ));
    // }

}
