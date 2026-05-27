<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace book\controllers;

use book\models\location;
use course\service\CourseGateway;
use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use library\pingle\SafeInput;
use Pingle\Controller\Controller;
use Pingle\Model\Storage;

Class PackageController extends Controller {

    public function IndexAction() {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training - Book Now");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $TitleBar = new TitleBar();
        $Footer = new Footer();
        $helper = $this->getHelper();
        $db = $helper->DatabaseConnection();
        $storage = new Storage();
        $location = new location($storage, $db);
        $safe = new SafeInput();
        $course_date_data = "";
        $var = $this->getGetVar();
        $package = "";
        if (isset($var['package'])) {
            $package = $safe->cleanFilter($var['package']);
        }
        $location_drop = $location->getLocationDropDown("");

        $course = new CourseGateway($helper, $storage, $db);
        $package_drop = $course->packageDropDownForBooking();

        $View->AddCSS(array('/external/bootstrapdropdown/style.css'));
        $View->AddJavascript(array('/js/package_booking.js', '/external/bootstrapdropdown/script.js'));
        $View->Render(array(
            'location_drop' => $location_drop,
            'package_drop' => $package_drop,
            'titlebar' => $TitleBar->render(array('title' => 'Package Booking')),
            'navbar' => $NavBar->render(array('active' => 'booknow')),
            'footer' => $Footer->render(array()),
            'course_date_data' => $course_date_data,
            'package_selected' => $package
        ));
    }

    public function ThankyouAction() {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training - Book Now");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $Footer = new Footer();
        $View->Render(array(
            'navbar' => $NavBar->render(array('active' => 'booknow')),
            'footer' => $Footer->render(array()),
        ));
    }

}
