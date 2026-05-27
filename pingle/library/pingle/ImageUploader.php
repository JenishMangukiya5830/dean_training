<?php

namespace library\pingle;

class ImageUploader {

    private $fileName = null;
    private $uploadDir = null;
    private $maxSize = null;
    private $allowedExtension = null;
    private $ready = FALSE;
    private $imageResize = null;
    private $thumbDir = null;

    public function uploadConfig($imageResize, $fileName, $uploadDir, $thumbDir, $maxSize, Array $allowedExtension) {
        $this->imageResize = $imageResize;
        $this->fileName = $fileName;
        $this->uploadDir = $uploadDir;
        $this->maxSize = $maxSize;
        $this->allowedExtension = $allowedExtension;
        $this->thumbDir = $thumbDir;
        $this->ready = TRUE;
    }

    function uplaod() {
        if (!$this->ready) {
            return "";
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
                if (!in_array(strtolower($extension), $this->allowedExtension)) {
                    return false;
                }
                $randomnumber1 = rand(1, 10000);
                $randomnumber2 = rand(1, 10000);
                $randomnumber3 = rand(1, 10000);
                $actual_id_name = "IMG_{$randomnumber1}_{$randomnumber2}_{$randomnumber3}" . time() . "." . $extension;
                $tmp = $_FILES[$this->fileName]['tmp_name'];
                move_uploaded_file($tmp, $this->uploadDir . $actual_id_name);
                $orginalfile = $this->uploadDir . $actual_id_name;
                $newfile = $this->thumbDir . $actual_id_name;
                $this->imageResize->smart_resize_image($orginalfile, $width = 350, $height = 350, true, $newfile, $delete_original = false, false);
                $this->reset();
                return $actual_id_name;
            }
            $this->reset();
            return "";
        }
        $this->reset();
        return "";
    }

    private function reset() {
        $this->fileName = null;
        $this->uploadDir = null;
        $this->maxSize = null;
        $this->allowedExtension = null;
        $this->ready = FALSE;
    }

}
