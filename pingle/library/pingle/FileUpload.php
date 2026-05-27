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

class FileUpload {

    private $fileName = null;
    private $uploadDir = null;
    private $maxSize = null;
    private $allowedExtension = null;
    private $ready = FALSE;
    private $fileIndex = null;

    public function uploadConfig($fileName, $uploadDir, $maxSize,  $allowedExtension = null, $fileIndex = null) {                   
        $this->fileName = $fileName;
        $this->uploadDir = $uploadDir;
        $this->maxSize = $maxSize;
        $this->allowedExtension = $allowedExtension;
        $this->ready = TRUE;
        $this->fileIndex = $fileIndex;        
    }

    function uplaod() {
        if (!$this->ready) {
            return false;
        }
        $actual_id_name = "";
        $error = $_FILES[$this->fileName]["error"];
        $name = $_FILES[$this->fileName]['name'];
        $size = $_FILES[$this->fileName]['size'];
        if (file_exists($_FILES[$this->fileName]['tmp_name'])) {
            if ($size < ($this->maxSize * 1024)) {
                //get the extensions
                $pathfile = $_FILES[$this->fileName]['name'];
                $extension = pathinfo($pathfile, PATHINFO_EXTENSION);
                if (!is_null($this->allowedExtension)) {
                    if (!in_array($extension, $this->allowedExtension)) {
                        return false;
                    }
                }
                $randomnumber1 = rand(1, 10000);
                $randomnumber2 = rand(1, 10000);
                $randomnumber3 = rand(1, 10000);
                $actual_id_name = "DT_{$randomnumber1}_{$randomnumber2}_{$randomnumber3}" . time() . "." . $extension;
                $tmp = $_FILES[$this->fileName]['tmp_name'];
                move_uploaded_file($tmp, $this->uploadDir . $actual_id_name);
                $this->reset();
                return $actual_id_name;
            }
            $this->reset();
            return false;
        }
        $this->reset();
        return false;
    }

    function multiUplaod() {
        if (!$this->ready) {
            return false;
        }
        $actual_id_name = "";
        $error = $_FILES[$this->fileName][$this->fileIndex]["error"];
        $name = $_FILES[$this->fileName][$this->fileIndex]['name'];
        $size = $_FILES[$this->fileName][$this->fileIndex]['size'];
        if (file_exists($_FILES[$this->fileName][$this->fileIndex]['tmp_name'])) {
            if ($size < ($this->maxSize * 1024)) {
                //get the extensions
                $pathfile = $_FILES[$this->fileName][$this->fileIndex]['name'];
                $extension = pathinfo($pathfile, PATHINFO_EXTENSION);
                if (!is_null($this->allowedExtension)) {
                    if (!in_array($extension, $this->allowedExtension)) {
                        return false;
                    }
                }
                $randomnumber1 = rand(1, 10000);
                $randomnumber2 = rand(1, 10000);
                $randomnumber3 = rand(1, 10000);
                $actual_id_name = "DT_{$randomnumber1}_{$randomnumber2}_{$randomnumber3}" . time() . "." . $extension;
                $tmp = $_FILES[$this->fileName]['tmp_name'][$this->fileIndex];
                move_uploaded_file($tmp, $this->uploadDir . $actual_id_name);
                $this->reset();
                return $actual_id_name;
            }
            $this->reset();
            return false;
        }
        $this->reset();
        return false;
    }

    private function reset() {
        $this->fileName = null;
        $this->uploadDir = null;
        $this->maxSize = null;
        $this->allowedExtension = null;
        $this->ready = FALSE;
        $this->fileIndex = null;
    }

}
