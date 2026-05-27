<?php

namespace course\models;

use Pingle\Model\Model;

class course_gallery extends Model {

    function newCourseImage($data) {
        $data = $this->Insert($data)->Table('course_gallery')->executeQuery();
    }

    function deleteCourseImageByCourseAndFileName($id, $file) {
        $data = "";
        $data = $this->Delete()->Table('course_gallery')->Where('course_id', $id, '=')->andWhere('image', $file, '=')->executeQuery();
        return true;
    }

    function getImagesDataByCourseID($id) {
        $data = "";
        $data = $this->select()->Table('course_gallery')->Where('course_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function updateCourseImageByCourseAndFileName($data, $id, $file) {
        $this->Table('course_gallery')->Update($data)->Where('course_id', $id, '=')->andWhere('image', $file, '=')->executeQuery();
    }

}
