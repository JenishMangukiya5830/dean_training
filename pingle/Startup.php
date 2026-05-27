<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace Pingle\Startup;

Class Startup {

    /** Hide signature of php from header * */
    function removePHPHeader() {
        if (function_exists('header_remove')) {
            header_remove('X-Powered-By');      // PHP 5.3+
            header_remove('Server');            // PHP 5.3+
        } else {
            @ini_set('expose_php', 'off');
        }
    }

    /** Check if environment is development and display errors * */
    function setReporting() {
        if (DEVELOPMENT_ENVIRONMENT == true) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'error.log');
        }
    }

    /** Check for Magic Quotes and remove them * */
    function stripSlashesDeep($value) {
        $value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripslashes($value);
        return $value;
    }

    function removeMagicQuotes() {
        if (get_magic_quotes_gpc()) {
            $_GET = $this->stripSlashesDeep($_GET);
            $_POST = $this->stripSlashesDeep($_POST);
            $_COOKIE = $this->stripSlashesDeep($_COOKIE);
        }
    }

    /** Check register globals and remove them * */
    function unregisterGlobals() {
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    /** Main Call Function * */
    function callHook() {
        //get the url
        global $url;
        $url = trim(strtolower($url));

        //load the module
        $modules = ModulesToLoad();
        $modulesloaded = array();
        foreach ($modules as $module) {
            $modulesloaded[] = require_once(ROOT . DS . 'modules' . DS . $module . DS . 'module.php');
        }

        //split the url
        $parts = explode('/', $url);
        if (isset($parts[0]) && $parts[0] != "") {
            $controller = strtolower($parts[0]);
        } else {
            $controller = "index";
        }
        if (isset($parts[1]) && $parts[1] != "") {
            $action = ucfirst(strtolower($parts[1]));
        } else {
            $action = "Index";
        }

        unset($parts[0]);
        unset($parts[1]);
        array_values($parts);
        $getparm = array();
        $lastparm = null;
        foreach ($parts as $index => $IndexValue) {
            if ($index % 2 === 0) {
                $lastparm = $IndexValue;
            } else {
                $getparm[$lastparm] = $IndexValue;
            }
        }

        $foundcontroller = "";
        //match the route
        foreach ($modulesloaded as $key => $module) {
            foreach ($module['routes'] as $router) {
                if ($router['url'] == $controller) {
                    $foundcontroller = array('name' => $module['name'],
                        'controller' => $router['controller']);
                    $testclass = $foundcontroller['controller'];
                    $testclassname = "{$foundcontroller['name']}\controllers\\" . $testclass;
                    $testrender = new $testclassname;
                    //class not found
                    if (is_object($testrender)) {
                        $testcallaction = $action . "Action";
                        //if function found
                        if (method_exists($testrender, $testcallaction)) {
                            unset($testrender);
                            break;
                        }
                    }
                }
            }
        }

        //if file found
        if (isset($foundcontroller['controller'])) {
            $class = $foundcontroller['controller'];
            $classname = "{$foundcontroller['name']}\controllers\\" . $class;
            $render = new $classname;
        } else {
            //404 controller
            $foundcontroller['controller'] = "NotfoundController";
            $foundcontroller['name'] = "application";
            $class = "NotfoundController";
            $classname = "application\controllers\\" . $class;
            $render = new $classname;
        }

        //class not found
        if (!is_object($render)) {
            echo "class not found";
        } else {

            //set the Main Module
            $render->setModule($foundcontroller['name']);

            //set the Module
            $render->setController($foundcontroller['controller']);

            //set the Variable
            $render->setGetVar($getparm);

            $callaction = $action . "Action";

            //check if the action does not found
            if (!method_exists($render, $callaction)) {
                //if index function exits
                if (method_exists($render, "IndexAction")) {
                    $callaction = "IndexAction";
                } else {                   
                    echo "Error function not found";
                }
            }
            
            //set the Action
            $render->setAction($callaction);

            //if function found
            if (method_exists($render, $callaction)) {
                $render->$callaction();
            }
        }
    }

}

session_start();
spl_autoload_register('AutoLoader::loader');         //Module Loader
spl_autoload_register('AutoLoader::libraryLoader');  //External Libraries

$Startup = new Startup();
$Startup->setReporting();
$Startup->removePHPHeader();
$Startup->removeMagicQuotes();
$Startup->unregisterGlobals();
$Startup->callHook();
