<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */
/** Configuration Variables * */
define('DEVELOPMENT_ENVIRONMENT', true);

/** Database * */
define('DB_NAME', 'cl16-dtv2');
define('DB_USER', 'cl16-dtv2');
define('DB_PASSWORD', 'WX^MHDx64');
define('DB_HOST', '79.170.40.168');


define('DISCOUNT_TIME', '');

/** set time zone * */
date_default_timezone_set('Europe/London');

/** name of session * */
session_name("deantraining");

/** share the session to sub domains * */
session_set_cookie_params(0, '/', '.deantraining.co.uk');

# set if Promotion is running
define('PROMOTION_RUNNING', false);
