<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace about;

return array(
    'name' => 'about',
    'routes' => array(
        array(
            'url' => 'about',
            'controller' => 'AboutController'
        ),
        array(
            'url' => 'refund',
            'controller' => 'RefundController'
        ),
        array(
            'url' => 'privacy',
            'controller' => 'PrivacyController'
        ),
        array(
            'url' => 'terms',
            'controller' => 'TermsController'
        ),
    )
);

