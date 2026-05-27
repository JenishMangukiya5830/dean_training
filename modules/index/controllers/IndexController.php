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
use layout\SlideShow;
use Pingle\Controller\Controller;
use Pingle\Helper\Helper;
use Pingle\Model\Storage;

class IndexController extends Controller
{


    public function indexAction()
    {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $SlideShow = new SlideShow();
        $Footer = new Footer();

        $helper = new Helper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $course_data = $course->homePageelments();
        $coursedropdown = $course->courseDropDown();
        $View->AddJavascript(array('/external/bootstrapdropdown/script.js'));
        $View->AddCSS(array('/external/bootstrapdropdown/style.css'));


        $js = '';
        foreach (range(0, 40) as $range) {
            $js .= "
$('#discount-timer-{$range}').countdowntimer({
    dateAndTime: timeLeft,
    size: 'sm',
    displayFormat: 'MS',
    tickInterval: 1
});
";
        }
        $View->Render(array('navbar' => $NavBar->render(array('active' => 'home')),
            'slideshow' => $SlideShow->render(array('dropdown' => $coursedropdown)),
            'course_data' => $course_data,
            'footer' => $Footer->render(array()),
        ), $js);
    }

    public function sectorAction()
    {
        $var = $this->getGetVar();
        if (!isset($var['sector'])) {
            die();
        }

        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $Footer = new Footer();

        $helper = new Helper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $course_data = $course->homePageelments();
        $View->AddJavascript(array('/external/bootstrapdropdown/script.js'));
        $View->AddCSS(array('/external/bootstrapdropdown/style.css'));


        $js = '';
        foreach (range(0, 40) as $range) {
            $js .= "
$('#discount-timer-{$range}').countdowntimer({
    dateAndTime: timeLeft,
    size: 'sm',
    displayFormat: 'MS',
    tickInterval: 1
});
";
        }
        $View->Render(array('navbar' => $NavBar->render(array('active' => 'home')),
            'course_data' => $course_data,
            'sector' => $var['sector'],
            'footer' => $Footer->render(array()),
        ), $js);
    }




    public function FirstAidAction()
    {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $SlideShow = new SlideShow();
        $Footer = new Footer();

        $helper = new Helper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $course_data = $course->homePageelments();

        $View->AddJavascript(array('/external/bootstrapdropdown/script.js'));
        $View->AddCSS(array('/external/bootstrapdropdown/style.css'));
        $View->Render(array('navbar' => $NavBar->render(array('active' => 'home')),
            'course_data' => $course_data,
            'footer' => $Footer->render(array()),
        ));
    }


    public function TestAction()
    {
        $view = $this->View();
        $view->AddJavascript(['https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.378/pdf.js', '/js/test.js']);
        $view->Render();
    }

    public function index3Action()
    {
        $View = $this->View();
        //get the flag
        $flag = $this->getFlag();
        $View->setSessionFlag($flag['text'], $flag['type']);
        $View->setTitle("Dean Training");
        $View->setMetaname("Dean Training offer SIA qualified close protection &amp; bodyguard training, including door supervisor courses that will enhance your security qualification portfolio");
        $View->setKeyword("close protection training, sia training, security qualification, sia license training, security industry authority,SIA");
        $NavBar = new NavBar();
        $SlideShow = new SlideShow();
        $Footer = new Footer();

        $helper = new Helper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $course_data = $course->homePageelments();
        $coursedropdown = $course->courseDropDown();
        $View->AddJavascript(array('/external/bootstrapdropdown/script.js'));
        $View->AddCSS(array('/external/bootstrapdropdown/style.css'));


        $js = '';
        foreach (range(0, 40) as $range) {
            $js .= "
$('#discount-timer-{$range}').countdowntimer({
    dateAndTime: timeLeft,
    size: 'sm',
    displayFormat: 'MS',
    tickInterval: 1
});
";
        }
        $View->Render(array('navbar' => $NavBar->render(array('active' => 'home')),
            'slideshow' => $SlideShow->render(array('dropdown' => $coursedropdown)),
            'course_data' => $course_data,
            'footer' => $Footer->render(array()),
        ), $js);
    }


}
