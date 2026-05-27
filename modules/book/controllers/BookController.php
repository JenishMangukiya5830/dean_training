<?php

namespace book\controllers;

use book\models\location;
use course\models\course;
use course\models\course_date;
use course\service\CourseGateway;
use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use library\pingle\SafeInput;
use Pingle\Controller\Controller;
use Pingle\Model\Storage;

Class BookController extends Controller
{

    public function IndexAction()
    {
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

        $course = new course($storage, $db);
        if (isset($var['date_id'])) {
            $var['date_id'] = $safe->cleanFilter($var['date_id']);
            $course_date = new course_date($storage, $db);
            $course_date_data = $course_date->getCourseDataByDateID($var['date_id']);
            if (is_array($course_date_data)) {
                $course_data = $course->getCourseDataByID($course_date_data['course_id']);
                if (is_array($course_data)) {
                    $course_date_data['course_date_data'] = $course_data;
                }
            }
        }

        $course_id = "";
        if (isset($var['course']) && !isset($var['date_id'])) {
            $var['course'] = $safe->cleanFilter($var['course']);
            $course_id = $var['course'];
        }

        $location_drop = $location->getLocationDropDown("");
        $courseGateway = new CourseGateway($helper, $storage, $db);
        $course_drop = $courseGateway->courseDropDownForBooking();

        $courseInfo = "";
        if ($course_id != '') {
            $courseInfo = $course->getCourseDataByLinkedCourse($course_id);
        }

        $View->AddCSS(array('/external/bootstrapdropdown/style.css'));
        $View->AddJavascript(array('/js/booking.js', '/external/bootstrapdropdown/script.js'));

        $phoneNumbers = '';
        // Close Protection
        if ($course_id == '29') {
            $phoneNumbers = '02035145307';
        }

        // Door Supervision / CCTV / HandCuffing
        if ($course_id == '6') {
            $phoneNumbers = '02035146678';
        }

        // Door Supervision / CCTV / HandCuffing
        if ($course_id == '10') {
            $phoneNumbers = '02035146678';
        }

        // Door Supervision / CCTV / HandCuffing
        if ($course_id == '14') {
            $phoneNumbers = '02035146678';
        }

        // Screening and Vetting
        if ($course_id == '26') {
            $phoneNumbers = '02035146641';
        }

        // CSCS
        if ($course_id == '15') {
            $phoneNumbers = '02035146764';
        }

        // Traffic Marshall
        if ($course_id == '21') {
            $phoneNumbers = '02035146175';
        }

        // APLH
        if ($course_id == '24') {
            $phoneNumbers = '02035144690';
        }


        $View->Render(array(
            'location_drop' => $location_drop,
            'course_drop' => $course_drop,
            'titlebar' => $TitleBar->render(array('title' => 'Book Now', 'image' => '/v2/img/main_london.jpg')),
            'navbar' => $NavBar->render(array('active' => 'booknow')),
            'footer' => $Footer->render(array('phone' => $phoneNumbers)),
            'course_date_data' => $course_date_data,
            'course_id' => $course_id,
            'courseInfo' => $courseInfo
        ));
    }

    public function ThankyouAction()
    {
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
