<?php

namespace layout;

use Pingle\Helper\Helper;

Class NavBar implements Layout {

    function render(Array $option) {
        $helper = new Helper();
        $home = $helper->generateRoute('index', 'index', 'index');
        $data = "


<div class='row' id='header'>
       <nav class='navbar'>
        <div class='container-fluid'>
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class='navbar-header'>
                <button type='button' class='navbar-toggle collapsed' data-toggle='collapse' data-target='#mainmenu' aria-expanded='false'>
                    <span class='sr-only'>Toggle navigation</span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>
                <a class='navbar-brand' href='$home'><img src='" . PUBLIC_BASE . "/v2/img/logo.png' /></a>
            </div>
            <div class='collapse navbar-collapse' id='mainmenu'>
            <ul class='nav navbar-nav navbar-right'>";

        $route = $helper->generateRoute('index', 'index', 'index');
        $data .= $option['active'] == 'home' ? "<li class='active'><a href='$route'>HOME</a></li>" : "<li><a href='$route'>HOME</a></li>";
        $route = $helper->generateRoute('book', 'book', 'index');
        $data .= $option['active'] == 'booknow' ? "<li class='active'><a href='$route'>BOOK NOW</a></li>" : "<li><a href='$route'>BOOK NOW</a></li>";

        $route = 'http://isecuredirect.com/partnerportal';
        $data .= $option['active'] == 'partnerportal' ? "<li class='active'><a href='$route'>BECOME PARTNER</a></li>" : "<li><a href='$route'>BECOME PARTNER</a></li>";


        $route = $helper->generateRoute('about', 'about', 'index');
        $data .=" <li><a href='http://www.deansrm.com/' target='_blank'>SECURITY SERVICES</a></li>";
        $data .= $option['active'] == 'aboutus' ? "<li class='active'><a href='$route'>ABOUT US</a></li>" : "<li><a href='$route'>ABOUT US</a></li>";
        $route = $helper->generateRoute('contact', 'contact', 'index');
        $data .= $option['active'] == 'contactus' ? "<li class='active'><a href='$route'>CONTACT US</a></li>" : "<li><a href='$route'>CONTACT US</a></li>";
        $route = $helper->generateRoute('course', 'packages', 'index');        
        $data .=" <li class='active'><a href='$route'>PACKAGES</a></li>";
        $data .="</ul></div></div></nav></div>";
        return $data;
    }

}
