<?php

namespace course\models;

use Pingle\Model\Model;

class package_course extends Model {

    function newPackageCourse($data) {
        $data = $this->Insert($data)->Table('package_course')->executeQuery();
    }

    function deletePackageCourse($id) {
        $data = "";
        $data = $this->Delete()->Table('package_course')->Where('package_course_id', $id, '=')->executeQuery();
        return true;
    }

    function deletePackageCourseByPackageId($id) {
        $data = "";
        $data = $this->Delete()->Table('package_course')->Where('package_id', $id, '=')->executeQuery();
        return true;
    }

    function getPackageCourseDataByID($id) {
        $data = "";
        $data = $this->select()->Table('package_course')->Where('package_course_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }

    function getPackageCourseDataPackageID($id) {
        $data = "";
        $data = $this->select()->Table('package_course')->Where('package_id', $id, '=')->executeQuery();        
        if (is_array($data) && !empty($data)) {            
            return $data;
        } else {
            return false;
        }
    }

    function updatePackageCourse($data, $id) {
        $this->Table('package_course')->Update($data)->Where('package_course_id', $id, '=')->executeQuery();
    }

}
