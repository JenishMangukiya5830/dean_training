<?php

/**
  The MIT License (MIT)

  Copyright (c) 2015 Faiz Rasool

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
  SOFTWARE.
 */

namespace library\pingle;

/**
 * 
 * @author Faiz Rasool <faiz708@gmail.com>
 */
class VirtualTable {

    private $limit = 100;
    private $serverIP = "";
    private $secret = "";
    private $page = 1;
    private $db;
    private $post;
    private $countUrl;
    private $totalcount = 0;
    private $dbtables = array();
    private $offset = 0;
    private $start = 0;
    private $totalPages = 0;
    private $maxoffset = 0;
    private $navigation;
    private $dataUrl;

    /**
     * set the page number
     * @param type $page
     */
    function setPage($page) {
        if (is_integer($page)) {
            $this->page = $page;
        }
    }

    /**
     * set the url to send the count
     * @param type $countUrl
     */
    function setCountUrl($countUrl) {
        $this->countUrl = $countUrl;
    }

    /**
     * set the url for the data url
     * @param type $dataUrl
     */
    function setDataUrl($dataUrl) {
        $this->dataUrl = $dataUrl;
    }

    /**
     * set the per page items limit 
     * @param type $limit
     */
    function setPerPageLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * set the sceret to verfiy request is from the server
     * @param type $secret
     */
    function setSecret($secret) {
        $this->secret = $secret;
    }

    /**
     * set the server ip
     * @param type $serverIP
     */
    function setServerIP($serverIP) {
        $this->serverIP = $serverIP;
    }

    /**
     * check if number is in the given range
     * @param type $start
     * @param type $end
     * @param type $number
     * @return boolean
     */
    private function check_in_range($start, $end, $number) {
        if (($number >= $start) && ($number <= $end)) {
            return true;
        }
        return false;
    }

    /**
     * check if 2 ranges are over lapping
     * @param type $x1
     * @param type $x2
     * @param type $y1
     * @param type $y2
     * @return type
     */
    private function is_overlapping($x1, $x2, $y1, $y2) {
        return max(array($x1, $y1)) <= min(array($x2, $y2));
    }

    /**
     * post the data to the desired url
     * @param type $url
     * @param array $post
     * @return type
     */
    private function sendPostData($url, Array $post) {
        $data = "";
        foreach ($post as $key => $row) {
            $row = urlencode($row); //fix the url encoding
            $key = urlencode($key); //fix the url encoding                
            if ($data == "") {
                $data .="$key=$row";
            } else {
                $data .="&$key=$row";
            }
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * get the ip address
     * @return type
     */
    private function getIP() {
        $ip = false;
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * print the data in jason format
     * @param array $array
     */
    function printJason(Array $array) {
        header('Content-Type: application/json');
        echo json_encode($array);
    }

    private function getTableCount(Array $tables) {
        foreach ($tables as $table) {
            //check if month and year table exits            
            if ($this->checkTable($table)) {
                $this->post['table'] = $table;
                $count = json_decode($this->sendPostData($this->countUrl, $this->post));
                $this->totalcount += $count->count;
                $this->dbtables[] = array(
                    'table' => $table,
                    'totalrow' => $count->count,
                    'start' => $this->totalcount - $count->count + 1,
                    'end' => $this->totalcount);
            }
        }
        $this->settings();
    }

    /**
     * set database drivers 
     * @param \PDO $db
     */
    private function setDatabasePDO(\PDO $db) {
        $this->db;
    }

    /**
     * check if table exits
     * @param type $table
     * @return boolean
     */
    private function checkTable($table) {
        $sql = "SHOW TABLES LIKE '$table'";
        $res = $this->db->query($sql);
        if ($res->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Setup the library
     */
    private function settings() {
        // Calculate the offset for the query
        $this->offset = ($this->page - 1) * $this->limit;
        // Some information to display to the user
        $this->start = $this->offset + 1;
        //total pages
        $this->totalPages = ceil($this->totalcount / $this->limit);
        $this->maxoffset = $this->offset + $this->limit;

        //navigation
        $this->navigation = array(
            'start' => $this->start,
            'totalpages' => $this->totalPages,
            'currentpage' => $this->page,
            'totalfound' => $this->totalcount
        );
    }

    /**
     * function to get the table
     * @return array
     */
    function getVirtualTable() {
        $recordsfound = 0;
        $mainData = "";
        if (is_array($this->dbtables)) {
            //print_r($tablesfound);
            //print_r(array('offset' => $offset, 'maxoffset' => $maxoffset));            
            foreach ($this->dbtables as $tablekey => $table) {

                //start is in the range but end is less then limit
                if ($table['start'] >= $this->offset && $table['end'] <= $this->maxoffset) {
                    $dboffset = 0;
                    $dblimit = $table['end'];
                    //echo "Case 1 - " . $table['month'] . "-{$table['year']} | dboffset = $dboffset dblimit= $dblimit <br />";
                    $recordsfound += $dboffset + $dblimit;
                    $this->post['offset'] = $dboffset;
                    $this->post['limit'] = $dblimit;
                    $mainData[] = json_decode($this->sendPostData($this->dataUrl, $this->post));
                    continue;
                }

                //if start is in the offset and but end is greater
                if ($this->check_in_range($this->offset, $this->maxoffset, $table['start']) &&
                        !$this->check_in_range($this->offset, $this->maxoffset, $table['end'])
                ) {
                    $dboffset = 0;
                    $dblimit = $this->limit - $recordsfound;
                    //  echo "Case 2 - " . $table['month'] . "-{$table['year']} | dboffset = $dboffset dblimit= $dblimit  <br />";
                    $recordsfound += $dboffset + $dblimit;
                    $this->post['offset'] = $dboffset;
                    $this->post['limit'] = $dblimit;
                    $mainData[] = json_decode($this->sendPostData($this->dataUrl, $this->post));
                    continue;
                }

                //if both ranges are over lapping
                if ($this->is_overlapping($this->offset, $this->maxoffset, $table['start'], $table['end'])) {
                    $dboffset = $this->limit - ($table['end'] - $table['totalrow']) + ($this->offset - $this->limit);

                    if ($this->maxoffset > $table['end']) {
                        $dblimit = ($table['end'] - $this->offset);
                    } else {
                        $dblimit = $this->limit;
                    }
                    // echo "Case 3 - " . $table['month'] . "-{$table['year']} | dboffset = $dboffset dblimit= $dblimit  <br />";
                    $recordsfound += $dblimit;
                    $this->post['offset'] = $dboffset;
                    $this->post['limit'] = $dblimit;
                    $mainData[] = json_decode($this->sendPostData($this->dataUrl, $this->post));
                    continue;
                }
            }
        }
        return $mainData;
    }

    function configure(Array $tables, $currentPage, \PDO $database, $limit, Array $post, $countUrl, $dataUrl, $secret) {
        $this->page = $currentPage;
        $this->db = $database;
        $this->limit = $limit;
        $this->countUrl = $countUrl;
        $this->dataUrl = $dataUrl;
        $this->secret = $secret;
        $this->post = $post;
        //get the table count
        $this->getTableCount($tables);
    }

}
