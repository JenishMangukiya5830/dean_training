<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */
class AutoLoader {

    static public function loader($className) {
        $fileName = str_replace('\\', '/', $className . ".php");
        if (file_exists("../modules/" . $fileName)) {
            include("../modules/" . $fileName);
            if (class_exists($className)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    static public function libraryLoader($className) {
        $fileName = str_replace('\\', '/', $className . ".php");
        if (file_exists("../pingle/" . $fileName)) {
            include("../pingle/" . $fileName);
            if (class_exists($className)) {
                return TRUE;
            }
        }
        return FALSE;
    }

}
