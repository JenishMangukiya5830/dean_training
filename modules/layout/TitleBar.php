<?php

namespace layout;

Class TitleBar implements Layout {

    function render(Array $option) {
        if (isset($option['image'])) {
            $image = array($option['image']);
        } else {
            $image = array("bristol_main.jpg", "birmingham_main.jpg", "main_london.jpg");
        }
        
        if(!isset($option['classes'])){
            $option['classes'] = "";
        }
        
        $data = "<div class='row course_title {$option['classes']}'>
        <div id='main_image' class='img-responsive'/>            
        <h1>{$option['title']}</h1>            
        </div>
        </div>
        <style>#main_image{ background: url(" . PUBLIC_BASE . "/img/silkscreen.svg),
        url(" . PUBLIC_BASE . "/img/" . $image[array_rand($image, 1)] . ");}</style><br/>";
        return $data;
    }

}
