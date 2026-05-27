<?php

namespace course\models;

use Pingle\Model\Model;

class testimonial extends Model {

    function newTestimonial($data) {
        $data = $this->Insert($data)->Table('testimonial')->executeQuery();
    }

    function getALlTestimonial() {
        $data = $this->select()->Table('testimonial')->executeQuery();
        return $data;
    }

}
