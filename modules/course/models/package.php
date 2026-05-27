<?php

namespace course\models;

use Pingle\Model\Model;

class package extends Model {

    function newPackage($data) {
        $data = $this->Insert($data)->Table('package')->executeQuery();
    }

    function deletePackage($id) {
        $data = "";
        $data = $this->Delete()->Table('package')->Where('package_id', $id, '=')->executeQuery();
        return true;
    }

    function getPackageDataByID($id) {
        $data = "";
        $data = $this->select()->Table('package')->Where('package_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }

    function getAllPackageData() {
        $data = "";
        $data = $this->select()->Table('package')->orderBy('name', 'ASC')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function updatePackage($data, $id) {
        $this->Table('package')->Update($data)->Where('package_id', $id, '=')->executeQuery();
    }

}
