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

// Dynamically build the public base URL (works on localhost subfolders and live domains)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('PUBLIC_BASE', $protocol . '://' . $host . $scriptDir);

$url = isset($_GET['url']) ? $_GET['url'] : "/";

require_once (ROOT . DS . 'pingle' . DS . 'Bootstrap.php');
