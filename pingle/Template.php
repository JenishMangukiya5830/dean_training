<?php

/**
 * Pingle
 *
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace Pingle\Template;

use Pingle\Helper\Helper;

Class Template
{

    private $Module;                   // Module name
    private $Controller;               // Controller name
    private $Action;                   // Action Name 
    private $Header = null;            // Header file
    private $Footer = null;            // Footer file
    private $Javascript = null;        // Alterantive javascript
    private $CSS = null;               // Alterantive CSS
    private $Title = "";               // Title
    private $Metaname = "";            // Metanme
    private $Keyword = "";             // Keyword
    private $Flag = null;              // Session flag
    private $Helper;                   // Helper;
    private $customView;               // Custom view

    function __construct($Module, $Controller, $Action, $view)
    {
        $this->Module = $Module;
        $this->Controller = strtolower(substr($Controller, 0, -10));
        $this->Action = strtolower(substr($Action, 0, -6));
        $this->Helper = new Helper();
        $this->customView = $view;
    }

    function setCustomView($View)
    {
        $this->customView = $View;
    }

    function setHeader($Header)
    {
        $this->Header = $Header;
    }

    function setFooter($Footer)
    {
        $this->Footer = $Footer;
    }

    function AddJavascript(Array $Javascript)
    {
        $this->Javascript = $Javascript;
    }

    function AddCSS(Array $CSS)
    {
        $this->CSS = $CSS;
    }

    function setTitle($Title)
    {
        $this->Title = $Title;
    }

    function setMetaname($Metaname)
    {
        $this->Metaname = $Metaname;
    }

    function setKeyword($Keyword)
    {
        $this->Keyword = $Keyword;
    }

    function setSessionFlag($keyword, $level = 'danger')
    {
        switch ($level) {
            case 'danger':
                $class = 'alert-danger';
                break;
            case 'info':
                $class = 'alert-info';
                break;
            case 'success':
                $class = 'alert-success';
                break;
            default:
                $class = 'alert-danger';
                break;
        }
        if ($keyword != '') {
            $keyword = "<div class='alert {$class}' role='alert' id='spmessage'>{$keyword}</div>";
            $this->Flag = $keyword;
        }
    }

    function Render($element = null, $js = null)
    {
        // Buffer all output so we can rewrite root-relative asset paths
        ob_start();

        //get the header
        if (is_null($this->Header)) {
            $this->Header = file_get_contents(ROOT . DS . "modules" . DS . "application" . DS . "views" . DS . "header" . '.phtml');
        }

        //get the footer
        if (is_null($this->Footer)) {
            $this->Footer = file_get_contents(ROOT . DS . "modules" . DS . "application" . DS . "views" . DS . "footer" . '.phtml');
        }

        //set the title
        $this->headerTitle($this->Title);

        //set the metaname
        $this->headerMetaDescription($this->Metaname);

        //set the keyword
        $this->headerKeywords($this->Keyword);

        //set the css
        $this->headerCSS();

        //set the flag
        $this->headerFlag();

        //set the javascript
        $this->footerJavascript();

        //print the header
        echo $this->Header;

        if ($this->customView == null) {
            //print the view file
            if (file_exists(ROOT . DS . "modules" . DS . $this->Module . DS . "views" . DS . $this->Controller . DS . $this->Action . ".phtml")) {
                include_once(ROOT . DS . "modules" . DS . $this->Module . DS . "views" . DS . $this->Controller . DS . $this->Action . ".phtml");
            } else {
                if (DEVELOPMENT_ENVIRONMENT) {
                    echo "Template does not found under view";
                }
            }
        } else {
            //print the custom view file
            if (file_exists(ROOT . DS . "modules" . DS . $this->Module . DS . "views" . DS . $this->Controller . DS . $this->customView . ".phtml")) {
                include_once(ROOT . DS . "modules" . DS . $this->Module . DS . "views" . DS . $this->Controller . DS . $this->customView . ".phtml");
            } else {
                if (DEVELOPMENT_ENVIRONMENT) {
                    echo "Template does not found under view";
                }
            }
        }
        //print the footer
        echo $this->Footer;

        if (!isset($_SESSION[DISCOUNT_TIME]) || $_SESSION[DISCOUNT_TIME] == '') {
            $time = new \DateTime();
            $time->add(new \DateInterval('PT30M'));
            $time->add(new \DateInterval('PT45S'));
            $_SESSION[DISCOUNT_TIME] = $time->format('Y/m/d H:i:s');
        }

        if (new \DateTime() > \DateTime::createFromFormat('Y/m/d H:i:s', $_SESSION[DISCOUNT_TIME])) {
            $time = new \DateTime();
            $time->add(new \DateInterval('PT30M'));
            $time->add(new \DateInterval('PT45S'));
            $_SESSION[DISCOUNT_TIME] = $time->format('Y/m/d H:i:s');
        }

        $fullJs = "var timeLeft = '{$_SESSION[DISCOUNT_TIME]}';
        $('#the-timer').countdowntimer({
    dateAndTime: timeLeft,
    size: 'sm',
    displayFormat: 'MS',
    tickInterval: 1
});";

        echo "<script>var BASE_URL = '" . PUBLIC_BASE . "'; var SITE_URL = '" . rtrim(str_replace('/public', '', PUBLIC_BASE), '/') . "';</script>";
        echo "<script>{$fullJs}</script>";
        echo "<script>{$js}</script>";

        // Rewrite all root-relative asset paths (src='/img/...' data-src="/img/..." etc.)
        // to use the correct PUBLIC_BASE so it works in localhost subfolders
        $html = ob_get_clean();
        $base  = PUBLIC_BASE;
        // Rewrite src/href/data-src attributes
        $html  = preg_replace(
            '#((?:src|data-src|href)=["\'])/(img|v2|upload|sp|external|js|css)/#',
            '$1' . $base . '/$2/',
            $html
        );
        // Rewrite CSS url() references in inline styles
        $html = preg_replace(
            '#(url\(["\']?)/(img|v2|upload|sp|external|js|css)/#',
            '$1' . $base . '/$2/',
            $html
        );
        echo $html;
    }


    private function headerTitle($title)
    {
        $this->Header = str_replace("%title%", $title, $this->Header);
    }

    private function headerMetaDescription($metaname)
    {
        $this->Header = str_replace("%description%", $metaname, $this->Header);
    }

    private function headerKeywords($keyword)
    {
        $this->Header = str_replace("%keywords%", $keyword, $this->Header);
    }

    private function headerCSS()
    {
        $customcss = "";
        //add custom css
        if (!is_null($this->CSS)) {
            if (is_array($this->CSS)) {
                foreach ($this->CSS as $css) {
                    $customcss .= "<link rel='stylesheet' type='text/css' href='{$css}' />";
                }
            }
        }
        $this->Header = str_replace("%cssfiles%", file_get_contents(ROOT . DS . "modules" . DS . "application" . DS . "views" . DS . "cssfiles" . '.phtml') . $customcss, $this->Header);
        $this->Header = str_replace("%BASEURL%", PUBLIC_BASE, $this->Header);
    }

    private function footerJavascript()
    {
        $customjs = "";
        //add custom css
        if (!is_null($this->Javascript)) {
            if (is_array($this->Javascript)) {
                foreach ($this->Javascript as $js) {
                    $customjs .= "<script src='{$js}' language='javascript' type='text/javascript'></script>";
                }
            }
        }
        $this->Footer = str_replace("%jsfiles%", file_get_contents(ROOT . DS . "modules" . DS . "application" . DS . "views" . DS . "jsfiles" . '.phtml') . $customjs, $this->Footer);
        $this->Footer = str_replace("%BASEURL%", PUBLIC_BASE, $this->Footer);
    }

    private function headerFlag()
    {
        $this->Header = str_replace("%sessionflags%", $this->Flag, $this->Header);
    }

}
