<?php

namespace course\controllers;

use course\service\CourseGateway;
use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use Pingle\Controller\Controller;
use Pingle\Helper\Helper;
use Pingle\Model\Storage;

Class PackagesController extends Controller {

    function IndexAction() {
        $helper = new Helper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $packages = $course->packageForPackagesPage();
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training - Package Deals");
        $View->setMetaname("Package Deals");
        $View->setKeyword("Package Deals");
        //$View->AddCSS();
        //$View->AddJavascript();
        $NavBar = new NavBar();
        $Footer = new Footer();
        $TitleBar = new TitleBar();
        $View->Render(array('navbar' => $NavBar->render(array('active' => 'course')),
            'footer' => $Footer->render(array()),
            'titlebar' => $TitleBar->render(array('title' => "Package Deals")),
            'packages' => $packages
        ));
    }

}
