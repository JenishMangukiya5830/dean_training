<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace library\pingle;

Class Password {

    private $salt;

    function generate($password) {
        if (empty($this->salt)) {
            $this->generateRandomSalt();
        }
        $hash = array('hash' => crypt($password, $this->salt), 'salt' => $this->salt);
        $this->salt = "";
        return $hash;
    }

    function generateRandomSalt($cost = 10) {
        $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
        $this->salt = sprintf("$2a$%02d$", $cost) . $salt;
    }

    function setSalt($salt) {
        $this->salt = $salt;
    }

    function checkPassword($hash, $password, $salt) {
        $currenthash = crypt($password, $salt);
        if ($currenthash == $hash) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
