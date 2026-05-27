<?php

namespace course\models;

use Pingle\Model\Model;

class course_date extends Model {

    function newCourseDate($data) {
        $data = $this->Insert($data)->Table('course_date')->executeQuery();
    }

    function deleteCourseDate($id) {
        $data = "";
        $data = $this->Delete()->Table('course_date')->Where('course_id', $id, '=')->executeQuery();
        return true;
    }

    function getCourseDataByID($id) {
        $data = "";
        $data = $this->select()->Table('course_date')->Where('course_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function getCourseDataByDateID($id) {
        $data = "";
        $data = $this->select()->Table('course_date')->Where('date_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }

    function updateCourse($data, $id) {
        $this->Table('course_date')->Update($data)->Where('course_id', $id, '=')->executeQuery();
    }

}
