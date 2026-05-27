<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace Pingle\Model;

class Storage {

    public $db;
    public $query;
    public $table;
    public $columns;
    public $andWhere;
    public $orWhere;
    public $where;
    public $limit;
    public $offset;
    public $orderBy;
    public $action;
    public $Data;
    public $Header;
    public $transaction = False;
    public $transactionResults;
    public $groupBy;
    public $andInWhere;
    public $orInWhere;
    public $in;

    //hex the value
    public function hex($data) {
        if ($data == "") {
            return;
        }
        return $data;
        //DISABLE HEX IN THIS CASE
        return bin2hex($data);
    }

    //unhex the value
    public function unhex($data) {
        if ($data == "") {
            return;
        }
        return $data;
        //DISABLE HEX IN THIS CASE
        return hex2bin($data);
    }

}
