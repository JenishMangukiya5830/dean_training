<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

require_once (ROOT . DS . 'config' . DS . 'config.php');                         // Main Config File
require_once (ROOT . DS . 'config' . DS . 'application.php');                    // Module to load
require_once (ROOT . DS . 'pingle' . DS . 'Autoloader.php');                     // Autoloader
require_once (ROOT . DS . 'pingle' . DS . 'external' . DS . "autoload.php");     // External File Autoload
require_once (ROOT . DS . 'pingle' . DS . "Helper" . '.php');                    // Load the Helper
require_once (ROOT . DS . 'pingle' . DS . "Template" . '.php');                  // Load the Template
require_once (ROOT . DS . 'pingle' . DS . "Storage" . '.php');                   // Load the Storage
require_once (ROOT . DS . 'pingle' . DS . "Model" . '.php');                     // Load the Model
require_once (ROOT . DS . 'pingle' . DS . "Controller" . '.php');                // Load the Controller
require_once (ROOT . DS . 'pingle' . DS . 'Startup.php');                        // Startup