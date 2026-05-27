<?php

/**
 * Pingle
 *
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace single_page\controllers;

use course\service\CourseGateway;
use layout\Footer;
use layout\NavBar;
use layout\SlideShow;
use Pingle\Controller\Controller;
use Pingle\Helper\Helper;
use Pingle\Model\Storage;

Class CPController extends Controller
{

    public function closeProtectionAction()
    {
        $view = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $view->setSessionFlag($flag['text'], $flag['type']);
        $view->setTitle("Close Protection | Dean Training");
        $view->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $view->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");


        $view->AddCSS(['/sp/cp/style/base.css',
            '/sp/cp/style/vendor.css',
            '/sp/cp/style/main.css']);


        $view->AddJavascript(['/sp/cp/script/modernizr.js', '/sp/cp/script/pace.min.js', '/sp/cp/script/plugins.js', '/sp/cp/script/main.js']);
        $view->Render();
    }

}
