<?php

namespace course\models;

use Pingle\Model\Model;

class course extends Model {

    function newCourse($data) {
        $data = $this->Insert($data)->Table('course')->executeQuery();
    }

    function deleteCourse($id) {
        $data = "";
        $data = $this->Delete()->Table('course')->Where('course_id', $id, '=')->executeQuery();
        return true;
    }

    function getCourseDataByID($id) {
        $data = "";
        $data = $this->select()->Table('course')->Where('course_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }

    function getCourseDataByLinkedCourse($id) {
        $data = "";
        $data = $this->select()->Table('course')->Where('linked_course_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }
    
    function getCoursesForBooking() {
        $data = "";
        $data = $this->select(array('title', 'image', 'slag', 'price', 'next_course_date'))->Table('course')->orderBy('title', 'ASC')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function getCoursesForHomePage() {
        $data = "";
        $data = $this->select(array('title', 'image', 'slag', 'price', 'next_course_date'))->Table('course')->Where('home_page_order', '0', '<>')->orderBy('home_page_order', 'ASC')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function getCourseDataByURL($slag) {
        $data = "";
        $data = $this->select()->Table('course')->Where('slag', $slag, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }

    function loadCoursesthumb($course_id) {
        $data = "";
        $data = $this->select(array('title', 'image', 'slag', 'price', 'next_course_date'))->Table('course')->Where('course_id', $course_id, '=')->orderBy('home_page_order', 'ASC')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }

    function updateCourse($data, $id) {
        $this->Table('course')->Update($data)->Where('course_id', $id, '=')->executeQuery();
    }

}
