<?php
/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */
/*
 * 
 * Enable to debug start up
 * 
 */

//ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

$url = isset($_GET['url']) ? $_GET['url'] : "/";

require_once (ROOT . DS . 'pingle' . DS . 'Bootstrap.php');
