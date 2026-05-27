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

use course\service\CourseGateway;
use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use Pingle\Controller\Controller;
use Pingle\Model\Storage;

Class ServicepersonnelController extends Controller {

    public function IndexAction() {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training - Service Personnel Courses");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $Footer = new Footer();
        $TitleBar = new TitleBar();
        $helper = $this->getHelper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $course_data = $course->servicePersonalCourses();
        $View->Render(array('navbar' => $NavBar->render(array('active' => 'home')),
            'course_data' => $course_data,
            'titlebar' => $TitleBar->render(array('title' => "Service Personnel Courses")),
            'footer' => $Footer->render(array()),
        ));
    }

}
