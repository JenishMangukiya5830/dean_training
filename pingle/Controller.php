<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace Pingle\Controller;

use Pingle\Template\Template;
use Pingle\Helper\Helper;

abstract class Controller {

    private $Module;            // Module name
    private $Controller;        // Controller name
    private $Action;            // Action Name  
    private $getVar;            // Get Variable
    
    function setModule($Module) {
        $this->Module = $Module;
    }

    function setController($Controller) {
        $this->Controller = $Controller;
    }

    function setAction($Action) {
        $this->Action = $Action;
    }

    function View($view = NULL) {
        return new Template($this->Module, $this->Controller, $this->Action, $view);
    }

    function setFlag($content, $type = 'danger') {        
        $_SESSION['flagtext'] = $content;
        $_SESSION['flagtype'] = $type;
    }

    function getFlag() {
        $content['text'] = "";
        $content['type'] = "";
        if (isset($_SESSION['flagtext']) && isset($_SESSION['flagtype'])) {
            $text = $_SESSION['flagtext'];
            $type = $_SESSION['flagtype'];
            $content['text'] = $text;
            $content['type'] = $type;
        }
        $_SESSION['flagtext'] = "";
        return $content;
    }

    function getHelper() {
        return new Helper();
    }

    function getGetVar() {
        return $this->getVar;
    }

    function setGetVar($getVar) {
        $this->getVar = $getVar;
    }

    /*

      private $requestvar;
      private $helper;

      function view() {



      if (method_exists($this, $this->mainaction . "Action")) {
      $myview = new Template("../application/" . 'views', $this->maincontroller,
      $this->mainaction,
      $this->helper);
      } else {
      $myview = new Template("../application/" . 'views', $this->maincontroller, "index", $this->helper);
      }

      return $myview;
      }

      function setvar($var) {
      $this->requestvar = $var;
      }

      function requestvar() {
      return $this->requestvar;
      }

      function sethelper($var) {
      $this->helper = $var;

      //set the database
      $this->helper->database = DB_NAME;
      $this->helper->dbhost = DB_HOST;
      $this->helper->dbusername = DB_USER;
      $this->helper->dbpassword = DB_PASSWORD;
      }

      function gethelper() {
      return $this->helper;
      }

      //render method
      function render($action) {
      if (method_exists($this, $action . "Action")) {
      call_user_func(array($this, $action . "Action"));
      } else {
      call_user_func(array($this, 'IndexAction'));
      }
      }

      function getModel($modelname, $db) {
      $model = "";
      //bind the main model
      require_once(ROOT . DS . 'library' . DS . "model" . '.php');
      if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . $modelname . '.php')) {
      require_once(ROOT . DS . 'application' . DS . 'models' . DS . $modelname . '.php');
      $model = new $modelname($db);
      }
      return $model;
      }

      function setFlag($content, $type = "danger") {
      $_SESSION['flagtext'] = $content;
      $_SESSION['flagtype'] = $type;
      }

      function getFlag() {
      $content['text'] = "";
      $content['type'] = "";
      if (isset($_SESSION['flagtext']) && isset($_SESSION['flagtype'])) {
      $text = $_SESSION['flagtext'];
      $type = $_SESSION['flagtype'];
      $content['text'] = $text;
      $content['type'] = $type;
      }
      $_SESSION['flagtext'] = "";
      return $content;
      }

     */
}
