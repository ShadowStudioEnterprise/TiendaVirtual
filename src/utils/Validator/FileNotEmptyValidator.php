<?php
namespace ProyectoWeb\utils\Validator;

use ProyectoWeb\utils\Validator\Validator;

class FileNotEmptyValidator extends Validator {
    public function doValidate(): bool{
        $ok = ($this->data["error"] === UPLOAD_ERR_OK);
        if (!$ok) {
            $this->errors[] = $this->message;
        }
        return $ok;
    } 
}