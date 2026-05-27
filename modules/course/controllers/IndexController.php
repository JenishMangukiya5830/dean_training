<?php

namespace course\controllers;

use course\models\testimonial;
use course\service\CourseGateway;
use layout\Footer;
use layout\NavBar;
use layout\TitleBar;
use library\pingle\SafeInput;
use Pingle\Controller\Controller;
use Pingle\Helper\Helper;
use Pingle\Model\Storage;

Class IndexController extends Controller
{

    function DetailAction()
    {
        $helper = new Helper();
        $var = $this->getGetVar();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $safe = new SafeInput();
        $Course = new CourseGateway($helper, $storage, $db);
        if (isset($var['course'])) {
            $var['course'] = $safe->cleanFilter($var['course']);
            $data = $Course->courseDescription($var['course']);
            $testimonial = new testimonial($storage, $db);
            $testimonial_data = $testimonial->getALlTestimonial();
            $related_course = $Course->loadRelatedCourses(array($data['related_course_1'], $data['related_course_2'], $data['related_course_3']));
            $View = $this->View();
            //get the flag
            $flag = $this->getFlag();
            $View->setSessionFlag($flag['text'], $flag['type']);
            $View->setTitle("Dean Training - {$data['title']}");
            $View->setMetaname("{$data['small_description']}");
            $View->setKeyword("{$data['keyword']}");

            $javaScript = array('/external/jssocials/jssocials.min.js',
                '/external/sticky_kit.js', '/js/course.js');
            $css = array('/external/bootstrap/style/bootstrap_res_table.css');
            if ($data['course_id'] == '21') {
                $javaScript[] = "/external/lightbox/fancybox.js";
                $javaScript[] = "/js/youtube.js";
                $css[] = "/external/lightbox/fancybox.css";
            }

            $dates_for_other_option = null;
            if ($data['course_id'] == '29') {
                $dates_for_other_option = $Course->getCourseDatesForCoursePage('28');
                $dates_for_other_option['price'] = '249';
                $dates_for_other_option['title'] = 'Theory Only';
            }

            $css[] = "/external/jssocials/jssocials.css";
            $css[] = "/external/jssocials/jssocials-theme-flat.css";

            $js = '';
            foreach (range(0, 3) as $range) {
                $js .= "
$('#discount-timer-{$range}').countdowntimer({
    dateAndTime: timeLeft,
    size: 'sm',
    displayFormat: 'MS',
    tickInterval: 1
});";
            }

            $View->AddCSS($css);
            $View->AddJavascript($javaScript);
            $NavBar = new NavBar();
            $Footer = new Footer();
            $TitleBar = new TitleBar();
            $View->Render(array('navbar' => $NavBar->render(array('active' => 'course')),
                'data' => $data,
                'dates_for_other_option' => $dates_for_other_option,
                'testimonial' => $testimonial_data,
                'footer' => $Footer->render(array()),
                'titlebar' => $TitleBar->render(array('title' => $data['title'], 'image' => $data['image'], 'classes' => 'expanded-title-bar')),
                'realated_course' => $related_course), $js);
        } else {
            $helper->Redirect('index', 'index', 'index');
        }
    }

}
