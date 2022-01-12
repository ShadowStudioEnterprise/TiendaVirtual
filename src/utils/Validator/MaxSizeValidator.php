<?php
namespace ProyectoWeb\utils\Validator;
use ProyectoWeb\utils\Validator\Validator;
class MaxSizeValidator extends Validator {

    private $maxSize;
    public function __construct(int $maxSize, string $message, bool $last = false)
    {
        $this->maxSize = $maxSize;
        parent::__construct($message, $last);
    }
    public function doValidate(): bool{
        $ok = true;
        if ($this->data["error"] === UPLOAD_ERR_OK) {
            $ok = !(($this->maxSize > 0) && ($this->data['size'] > $this->maxSize));
        }
        if (!$ok) {
            $this->errors[] =  $this->message;
        }
        
        return $ok;
    } 
}