<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace index;

return array(
    'name' => 'index',
    'routes' => array(
        array(
            'url' => 'index',
            'controller' => 'IndexController'
        ),
        array(
            'url' => 'service-personnel',
            'controller' => 'ServicepersonnelController'
        ),
        array(
            'url' => 'facebook',
            'controller' => 'FacebookController'
        )
    )
);

