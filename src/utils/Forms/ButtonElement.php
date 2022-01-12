<?php
namespace ProyectoWeb\utils\Forms;

use  ProyectoWeb\utils\Forms\DataElement;

class ButtonElement extends DataElement
{
    /**
     * Texto del botón
     *
     * @var string
     */
    private $text;
    
    public function __construct(string $text)
	{
        $this->text = $text;
        parent::__construct();
    }
    /**
     * Devuelve true si éste ha sido el botón enviado con el formulario
     *
     * @return boolean
     */
    public function isSubmitted(): bool {
        if (empty($this->name)) {
            return false;
        }
        return (isset($_POST[$this->name]));
    }
    public function render(): string
    {
       return 
            "<button type='submit'" . 
                (!empty($this->name) ? " name='$this->name' " : '') .
                $this->renderAttributes() . 
            ">{$this->text}</button>";
       
    }
}