<?php

namespace book\models;

use Pingle\Model\Model;

class location extends Model {

    function newLocation($data) {
        $data = $this->Insert($data)->Table('location')->executeQuery();
    }

    function deleteLocation($id) {
        $data = "";
        $data = $this->Delete()->Table('location')->Where('location_id', $id, '=')->executeQuery();
        return true;
    }

    function getLocationDataByID($id) {
        $data = "";
        $data = $this->select()->Table('location')->Where('location_id', $id, '=')->executeQuery();
        if (is_array($data) && !empty($data)) {
            return $data[0];
        } else {
            return false;
        }
    }

    function updateLocation($data, $id) {
        $this->Table('location')->Update($data)->Where('location_id', $id, '=')->executeQuery();
    }

    function getLocationDropDown($location = "") {
        $data_drop = "";
        $data = $this->select()->Table('location')->executeQuery();
        if (is_array($data) && !empty($data)) {
            foreach ($data as $row) {
                if ($row['location_id'] == $location) {
                    $data_drop .="<option value='{$row['location_id']}' selected>{$row['name']}</option>";
                } else {
                    $data_drop .="<option value='{$row['location_id']}'>{$row['name']}</option>";
                }
            }
            return $data_drop;
        } else {
            return "";
        }
    }

}
