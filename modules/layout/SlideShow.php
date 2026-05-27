<?php

namespace layout;

Class SlideShow implements Layout {

    function render(Array $option) {
        //birmingham_main.jpg
        $image = array("main_london.jpg");
        $data = "
        <div class='row'>
        <div id='main_image' class='img-responsive'/>            
            <h1>Providing dynamic vocational training to empower our customers to lead tomorrow’s world.</h1>
            <div class='col-md-12 col-md-offset-0 col-xs-10 col-xs-offset-1 div course-select'>
                <h3>Find Course</h3>
                <div id='form'>
                <div class='form-group col-md-8 col-md-offset-2'>                   
                    <select class='form-control selectpicker' required data-live-search='true' id='course_select'><option>Select Course</option>{$option['dropdown']}</select>
                </div>
                <div class='text-center col-md-12'>
                    <button type='submit' class='btn btn-red btn-full' onclick='loadtocourse();'/>Course Detail</button>
                    
                    <br/>
                </div>
                </div>
                
                
            </div>
        </div>
        </div>
        <style>#main_image{ background: url(/v2/img/silkscreen.svg),
        url(/v2/img/" . $image[array_rand($image, 1)] . ");}</style>";
        return $data;
    }

}
