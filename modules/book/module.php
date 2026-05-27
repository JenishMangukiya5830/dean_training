<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace book;

return array(
    'name' => 'book',
    'routes' => array(
        array(
            'url' => 'book-now',
            'controller' => 'BookController'
        ),
        array(
            'url' => 'group-booking',
            'controller' => 'GroupbookingController'
        ),
        array(
            'url' => 'package-booking',
            'controller' => 'PackageController'
        ), array(
            'url' => 'request-certificate',
            'controller' => 'CertificateController'
        ),
    )
);

