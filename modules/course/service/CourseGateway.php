<?php

namespace course\service;

use course\models\course;
use course\models\course_date;
use course\models\course_gallery;
use course\models\package;
use course\models\package_course;
use library\pingle\Time;
use PDO;
use Pingle\Helper\Helper;
use Pingle\Model\Storage;

Class CourseGateway
{

    private $helper;
    private $storage;
    private $db;
    private $course;

    public function __construct(Helper $helper, Storage $storage, PDO $db)
    {
        $this->helper = $helper;
        $this->storage = $storage;
        $this->db = $db;
        $this->course = new course($this->storage, $this->db);
    }

    function homePageelments()
    {
        $data = $this->course->getCoursesForHomePage();
        $time = new Time();
        if (is_array($data)) {
            foreach ($data as $k => $course) {
                if ($course['next_course_date'] == "0000-00-00") {
                    $data[$k]['next_course_date'] = "TBC";
                    continue;
                }

                # if today date is greater than date today
                if (time() > strtotime($course['next_course_date'])) {
                    $data[$k]['next_course_date'] = "TBC";
                    # if date is today date skip
                    if ($course['next_course_date'] != date('Y-m-d')) {
                        continue;
                    }
                }


                $data[$k]['next_course_date'] = $time->convertTime($course['next_course_date'], "d/m/Y", "Y-m-d");
            }
        }
        return $data;
    }

    function servicePersonalCourses()
    {
        $data = $this->course->getCoursesForHomePage();
        $time = new Time();
        if (is_array($data)) {
            foreach ($data as $k => $course) {
                if (in_array($course['course_id'], array('1', '12', '10', '11'))) {
                    if ($course['next_course_date'] == "0000-00-00") {
                        $data[$k]['next_course_date'] = "TBC";
                        continue;
                    }
                    $data[$k]['next_course_date'] = $time->convertTime($course['next_course_date'], "d/m/Y", "Y-m-d");
                }
            }
        }
        return $data;
    }

    function courseDescription($slag, $ridrect = true, $overRideId = null)
    {
        if (!is_null($overRideId)) {
            $data = $this->course->getCourseDataByID($overRideId);
        } else {
            $data = $this->course->getCourseDataByURL($slag);
        }

        if (!is_array($data)) {
            if ($ridrect) {
                $this->helper->Redirect('index', 'index', 'index');
            }
        }
        $course_gallery = new course_gallery($this->storage, $this->db);
        $course_date = new course_date($this->storage, $this->db);
        $course_gallery_data = $course_gallery->getImagesDataByCourseID($data['course_id']);
        $data['gallery'] = $course_gallery_data;
        $time = new Time();
        $data['next_course_date'] = $time->convertTime($data['next_course_date'], 'd/m/Y', 'Y-m-d');
        $data['course_date'] = $this->getCourseDatesForCoursePage($data['course_id']);
        return $data;
    }

    function getCourseDatesForCoursePage($courseId)
    {
        $course_date = new course_date($this->storage, $this->db);
        $course_date_data = $course_date->getCourseDataByID($courseId);
        $time = new Time();
        if (is_array($course_date_data)) {
            foreach ($course_date_data as $k => $coruse) {
                $course_date_data[$k]['date'] = $time->convertTime($coruse['date'], 'd/m/Y', 'Y-m-d');
            }
        }
        return $course_date_data;
    }

    function loadRelatedCourses($course_ids)
    {
        $time = new Time();
        $return = array();
        foreach ($course_ids as $course) {
            $data = $this->course->loadCoursesthumb($course);
            if (!is_array($data)) {
                continue;
            }
            if ($data['next_course_date'] == "0000-00-00") {
                $data['next_course_date'] = "TBC";
            } else {
                if ($data['next_course_date'] != "") {
                    $data['next_course_date'] = $time->convertTime($data['next_course_date'], "d/m/Y", "Y-m-d");
                }
            }
            $return[] = $data;
        }
        return $return;
    }

    function courseDropDown($select = "")
    {
        $data = $this->course->getCoursesForHomePage();
        $time = new Time();
        $dropdown = "";
        if (is_array($data)) {
            foreach ($data as $k => $course) {
                if ($course['next_course_date'] == "") {
                    continue;
                }
                if ($select == $course['slag']) {
                    $dropdown .= "<option value='{$course['slag']}' selected>{$course['title']} - £{$course['price']}</option>";
                } else {
                    $dropdown .= "<option value='{$course['slag']}'>{$course['title']} - £{$course['price']}</option>";
                }
            }
        }
        return $dropdown;
    }

    function courseDropDownForBooking($select = "")
    {
        $data = $this->course->getCoursesForBooking();
        $time = new Time();
        $dropdown = "";
        if (is_array($data)) {
            foreach ($data as $k => $course) {
                if ($course['next_course_date'] == "") {
                    continue;
                }
                if ($select == $course['slag']) {
                    $dropdown .= "<option value='{$course['linked_course_id']}' selected>{$course['title']}</option>";
                } else {
                    $dropdown .= "<option value='{$course['linked_course_id']}'>{$course['title']}</option>";
                }
            }
        }
        return $dropdown;
    }

    function courseDropDownForPostage($select = "")
    {
        $data = $this->course->getCoursesForHomePage();
        $dropdown = "";
        if (is_array($data)) {
            foreach ($data as $k => $course) {
                if ($select == $course['slag']) {
                    $dropdown .= "<option value='{$course['title']}' selected>{$course['title']}</option>";
                } else {
                    $dropdown .= "<option value='{$course['title']}'>{$course['title']}</option>";
                }
            }
        }
        return $dropdown;
    }

    function packageDropDownForBooking($select = "")
    {
        $package = new package($this->storage, $this->db);
        $data = $package->getAllPackageData();
        $dropdown = "";
        if (is_array($data)) {
            foreach ($data as $k => $package) {
                if ($select == $package['package_id']) {
                    $dropdown .= "<option value='{$package['package_id']}' selected>{$package['name']}</option>";
                } else {
                    $dropdown .= "<option value='{$package['package_id']}'>{$package['name']}</option>";
                }
            }
        }
        return $dropdown;
    }

    function packageForPackagesPage()
    {
        $package = new package($this->storage, $this->db);
        $course = new course($this->storage, $this->db);
        $package_course = new package_course($this->storage, $this->db);
        $data = $package->getAllPackageData();
        $return = "";
        if (is_array($data)) {
            foreach ($data as $k => $package) {
                $return[$k]['id'] = $package['package_id'];
                $return[$k]['name'] = $package['name'];
                $return[$k]['price'] = $package['price'];
                $return[$k]['image'] = $package['image'];
                $package_courses = $package_course->getPackageCourseDataPackageID($package['package_id']);
                if (is_array($package_courses)) {
                    foreach ($package_courses as $current_package_course) {
                        $course_data = $course->getCourseDataByLinkedCourse($current_package_course['course_id']);
                        if (is_array($course_data)) {
                            $return[$k]['course'][] = array('name' => $course_data['title'], 'price' => $course_data['price']);
                        }
                    }
                }
            }
        }
        return $return;
    }

}
