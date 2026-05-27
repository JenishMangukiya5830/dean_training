<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace course;

return array(
    'name' => 'course',
    'routes' => array(
        array(
            'url' => 'course',
            'controller' => 'IndexController'
        ),
        array(
            'url' => 'packages',
            'controller' => 'PackagesController'
        ),
        array('url' => 'sync',
            'controller' => 'ApiController')
    )
);
