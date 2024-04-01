<?php

trait FileUpload {

    public function uploadFile($file, $uploadDirectory) {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExtension;
        $destination = $uploadDirectory . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $fileName;
        } else {
            return false;
        }
    }
}
