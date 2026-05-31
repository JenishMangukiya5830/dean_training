<?php

namespace course\controllers;

use course\models\course;
use course\models\course_date;
use course\models\package;
use course\models\package_course;
use course\service\CourseGateway;
use library\pingle\SafeInput;
use Pingle\Controller\Controller;
use Pingle\Helper\Helper;
use Pingle\Model\Storage;

Class ApiController extends Controller
{

    function GetAction()
    {
        $content = file_get_contents("http://isecuredirect.com/api/");
        $courses = json_decode($content, true);
        $helper = new Helper();
        $db = $helper->DatabaseConnection();
        $storage = new Storage();
        $course = new course($storage, $db);
        $course_date = new course_date($storage, $db);

        $course_latest_date = "";
        $course_price = [];
        foreach ($courses as $date_row) {
            $course_data = $course->getCourseDataByLinkedCourse($date_row['courseid']);
            if (is_array($course_data)) {
                $course_date->deleteCourseDate($course_data['course_id']);
                if (!isset($course_latest_date[$course_data['course_id']])) {
                    $course_latest_date[$course_data['course_id']] = $date_row['start'];
                }

                if (!isset($course_price[$course_data['course_id']])) {
                    $course_price[$course_data['course_id']] = $date_row['price'];
                }
            }
        }

        foreach ($course_latest_date as $k => $date) {
            $course->updateCourse(array('next_course_date' => $date, 'price' => $course_price[$k]), $k);
        }

        foreach ($courses as $date_row) {
            $course_data = $course->getCourseDataByLinkedCourse($date_row['courseid']);
            if (is_array($course_data)) {
                $data = array('course_id' => $course_data['course_id'], 'date' => $date_row['start'], 'location' => $date_row['location'], 'date_id' => $date_row['dateid']);
                $course_date->newCourseDate($data);
            }
        }
    }

    function GetpackageAction()
    {
        $content = file_get_contents("http://isecuredirect.com/api/package");
        $packages = json_decode($content, true);
        $helper = new Helper();
        $db = $helper->DatabaseConnection();
        $storage = new Storage();

        $package = new package($storage, $db);
        $package_course = new package_course($storage, $db);
        if (is_array($packages)) {
            foreach ($packages as $current_package) {
                $package_id = "";
                $check_package_exits = $package->getPackageDataByID($current_package['id']);
                if (is_array($check_package_exits)) {
                    $package->updatePackage(array('name' => $current_package['name'], 'price' => $current_package['price']), $current_package['id']);
                    $package_id = $current_package['id'];
                } else {
                    $package->newPackage(array('name' => $current_package['name'], 'price' => $current_package['price'], 'package_id' => $current_package['id']));
                    $package_id = $package->lastInsertId();
                }
                if ($package_id != '') {
                    $package_course->deletePackageCourseByPackageId($package_id);
                    if (isset($current_package['course']) && is_array($current_package['course'])) {
                        foreach ($current_package['course'] as $course) {
                            $package_course->newPackageCourse(array('package_id' => $package_id, 'course_id' => $course));
                        }
                    }
                }
            }
        }
        die();
    }

    function NextDateAction()
    {

        $helper = new Helper();
        $var = $this->getGetVar();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $safe = new SafeInput();
        $Course = new CourseGateway($helper, $storage, $db);

        $text = "Error";
        if (isset($var['id'])) {
            $var = $safe->clean($var);
            $courseDescription = $Course->courseDescription($var['id'], false);
            if (isset($courseDescription['next_course_date'])) {
                $text = $courseDescription['next_course_date'];
            }
        }

        $my_img = imagecreate(100, 20);                             //width & height
        $background = imagecolorallocate($my_img, 255, 255, 255);
        $text_colour = imagecolorallocate($my_img, 232, 22, 6);
        imagestring($my_img, 5, 0, 0, $text, $text_colour);
        imagesetthickness($my_img, 5);
        header("Content-type: image/png");
        imagepng($my_img);
        imagecolordeallocate($text_color);
        imagecolordeallocate($background);
        imagedestroy($my_img);
    }

    public function traffic_marshal_next_dateAction()
    {
        header('Access-Control-Allow-Origin: http://trainfortrafficmarshal.com');
        $helper = new Helper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $data = $course->courseDescription('traffic-marshall');
        echo $data['next_course_date'];
    }

    /**
     * Returns packages that contain a given linked_course_id as JSON.
     * URL: /sync/api/getpackagesbycourse/course_id/{linked_course_id}
     */
    public function GetpackagesbycourseAction()
    {
        header('Content-Type: application/json');
        $helper = new Helper();
        $var    = $this->getGetVar();
        $safe   = new SafeInput();
        $storage = new Storage();
        $db     = $helper->DatabaseConnection();

        if (!isset($var['course_id'])) {
            echo json_encode([]);
            die();
        }

        $linked_course_id = (int) $safe->cleanFilter($var['course_id']);

        // Resolve linked_course_id → local course_id
        $courseModel   = new course($storage, $db);
        $course_data   = $courseModel->getCourseDataByLinkedCourse($linked_course_id);

        if (!is_array($course_data)) {
            echo json_encode([]);
            die();
        }

        $local_course_id = $course_data['course_id'];

        // Find all package_course rows for this local course_id
        $packageCourseModel = new package_course($storage, $db);
        $packageModel       = new package($storage, $db);

        // package_course stores the linked_course_id (external ID) in course_id column
        // (populated by GetpackageAction from iSecureDirect API)
        // So we query by the linked_course_id directly
        $rows = $packageCourseModel->getPackageCourseDataPackageID(null); // fetch all, filter below

        // Build result: find packages whose course list includes this linked_course_id
        $result = [];
        $allPackages = $packageModel->getAllPackageData();
        if (is_array($allPackages)) {
            foreach ($allPackages as $pkg) {
                $pkg_courses = $packageCourseModel->getPackageCourseDataPackageID($pkg['package_id']);
                if (!is_array($pkg_courses)) {
                    continue;
                }
                foreach ($pkg_courses as $pc) {
                    if ((int)$pc['course_id'] === $linked_course_id) {
                        $result[] = [
                            'package_id' => $pkg['package_id'],
                            'name'       => $pkg['name'],
                            'price'      => $pkg['price'],
                            'book_url'   => '/DeanTraining/package-booking/index/package/' . $pkg['package_id'],
                        ];
                        break;
                    }
                }
            }
        }

        echo json_encode($result);
        die();
    }

    public function traffic_marshalAction()
    {
        header('Access-Control-Allow-Origin: http://trainfortrafficmarshal.com');

        $helper = new Helper();
        $storage = new Storage();
        $db = $helper->DatabaseConnection();
        $course = new CourseGateway($helper, $storage, $db);
        $dates_for_other_option = $course->getCourseDatesForCoursePage('21');

        $dates_for_other_option['price'] = '49';


        if (!is_null($dates_for_other_option)) {

            echo "<div class='row-fluid'>
                <div class='col-md-12' id='booknow'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'><i class='fa fa-check-circle-o'></i> Course Date
                        </div>
                        <div class='panel-body' id='no-more-tables'>
                            <table class='table table-striped table-condensed table-bordered'>
                                <thead>
                                <tr>
                                    <th width='50%'>Location</th>
                                    <th width='20%'>Date</th>
                                    <th width='20%'>Price</th>
                                    <th width='10%'>Booking</th>
                                </tr>
                                </thead>
                                <tbody>";
            if (is_array($dates_for_other_option)) {

                $price = $dates_for_other_option['price'];
                unset($dates_for_other_option['price']);

                foreach ($dates_for_other_option as $date) {
                    $book_link = $this->getHelper()->generateRoute('book', 'book', 'index', array('course' => $date['course_id'], 'date_id' => $date['date_id']));
                    echo " <tr><td data-title='Location'>{$date['location']}</td><td data-title='Date'>{$date['date']}</td><td class='text-center' data-title='Price'>&pound;{$price}</td><td data-title='Booking' class='text-center'><a href='$book_link' class='btn btn-success btn-sm'>BOOK NOW</a></td></tr>";
                }
            }
            echo "
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>";
        }
    }

}
