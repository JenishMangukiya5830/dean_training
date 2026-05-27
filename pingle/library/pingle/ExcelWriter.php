<?php

namespace library\pingle;

Class ExcelWriter {

    /**
     * - variable to store the rows data
     * @var type 
     * 
     */
    private $data = "";

    /**
     * - variable to store the output file name
     * @var type 
     * 
     */
    private $fileName = "";

    /**
     * - variable to count the numnber of rows has been inserted (still not perfect)
     * @var type 
     * 
     */
    private $rowCount = 0;

    /**
     * - function to set the file name
     * @param type $fileName
     * 
     */
    public function setFileName($fileName) {
        $this->fileName = $fileName;
    }

    /**
     * /- add the new row to excel file
     * @param type $Row
     * @param type $Col
     * @param type $Value
     * @return \library\pingle\ExcelWriter
     * @throws Exception
     * 
     */
    public function addRow($Row, $Col, $Value) {
        $Value = utf8_decode($Value);
        if (is_numeric($Value)) {
            $data = pack("sssss", 0x203, 14, $Row, $Col, 0x0);
            $data .= pack("d", $Value);
            $this->data[] = $data;
        } else {
            $L = strlen($Value);
            //if string is greater than 255 character
            if ($L > 255) {
                throw new Exception("String is greater than 255 character");
            }
            $data = pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
            $data .= $Value;
            $this->data[] = $data;
        }
        $this->rowCount++;
        return $this;
    }

    /**
     * /- covert php array to excel rows and columns
     * @param array $array
     * @throws \Exception
     * 
     */
    public function arrayToRows(Array $array) {
        foreach ($array as $key => $row) {
            if (!is_array($row)) {
                throw new \Exception("Not a valid array");
            }
            foreach ($row as $col => $cell) {
                $Value = utf8_decode($cell);
                if (is_numeric($Value)) {
                    $data = pack("sssss", 0x203, 14, $this->rowCount, $col, 0x0);
                    $data .= pack("d", $Value);
                    $this->data[] = $data;
                } else {
                    $L = strlen($Value);
                    //if string is greater than 255 character
                    if ($L > 255) {
                        throw new \Exception("String is greater than 255 character");
                    }
                    $data = pack("ssssss", 0x204, 8 + $L, $this->rowCount, $col, 0x0, $L);
                    $data .= $Value;
                    $this->data[] = $data;
                }
            }
            $this->rowCount++;
        }
    }

    /**
     * - xls file starting header
     * @return type
     * 
     */
    private function BOF() {
        return pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    }

    /**
     * - xls file end of file bits
     * @return type
     * 
     */
    private function EOF() {
        return pack("ss", 0x0A, 0x00);
    }

    /**
     * - file header for the browser
     */
    private function headers() {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=" . $this->fileName . ".xls");
        header("Content-Transfer-Encoding: binary ");
    }

    /**
     * -genrate the actual xls file
     * @throws \Exception
     * 
     */
    public function genrateFile() {
        if ($this->fileName == "")
            throw new \Exception("Please enter the file name");
        if ($this->data == "")
            throw new \Exception("Please select the data");
        //print the header
        $this->headers();
        //print the start of the file       
        echo $this->BOF();
        //print the data
        foreach ($this->data as $row) {
            echo $row;
        }
        //print the end of file
        echo $this->EOF();
    }

}
