<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace Pingle\Helper;

class Helper {

    //genrate the custom PDO connection
    function customDatabaseConnection($host, $dbname, $user, $pass) {
        try {
            return new \PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        } catch (PDOException $e) {
            if (DEVELOPMENT_ENVIRONMENT) {
                echo $e->getMessage();
            }
        }
    }

    //get the default PDO connection
    function DatabaseConnection() {
        try {
            return new \PDO("mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME, DB_USER, DB_PASSWORD, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (PDOException $e) {
            if (DEVELOPMENT_ENVIRONMENT) {
                echo "Configure database variables in the config/config.php.<br>";
                echo $e->getMessage() . "<br/>";
            }
        }
    }

    //redirect
    function Redirect($module, $controller, $action = null, Array $parameters = null) {
        $url = $this->generateURL($module, $controller, $action, $parameters);
        header('Location: ' . $url);
        die();
    }

    //generate a route
    function generateRoute($module, $controller, $action = null, Array $parameters = null) {
        $url = $this->generateURL($module, $controller, $action, $parameters);
        return $url;
    }

    //get the website url
    function siteUrl() {
        if (isset($_SERVER['HTTPS'])) {              // Get protocol HTTP/HTTPS
            $protocol = 'https';
        } else {
            $protocol = 'http';
        }
        $host = $_SERVER['HTTP_HOST'];               // Get  www.domain.com
        $currentUrl = $protocol . '://' . $host;     // Adding all
        return $currentUrl;
    }

    //print the data in jason format
    function printJason(Array $array) {
        header('Content-Type: application/json');
        echo json_encode($array);
    }

    private function generateURL($module, $controller, $action = null, $parameters = null) {
        $route = "";
        $moduleToLoad = "";
        //load the module
        $moduleToLoad = require(ROOT . DS . 'modules' . DS . strtolower($module) . DS . 'module.php');
        if (is_array($moduleToLoad)) {
            foreach ($moduleToLoad['routes'] as $router) {
                if ($router['controller'] == ucfirst(strtolower($controller)) . "Controller") {
                    $route = "/" . $router['url'] . "/";
                }
            }
            if ($action != null) {
                $route .= "$action/";
            }
            $attach = "";
            if (is_array($parameters)) {
                foreach ($parameters as $key => $value) {
                    if (substr($route, -1) == "/") {
                        $attach.=$key . "/" . $value . "/";
                    } else {
                        $attach.="/" . $key . "/" . $value . "/";
                    }
                }
                $route .= $attach;
            }
            return $this->siteUrl()."" . $route;
        } else {
            return;
        }
    }

}
