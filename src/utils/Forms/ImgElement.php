<?php
namespace ProyectoWeb\utils\Forms;

use ProyectoWeb\utils\Forms\Element;

class ImgElement extends Element
{
    /**
     * @var string
     */
    private $src;

    /**
     * @var string
     */
    private $alt;

    /**
     * @var string
     */
    private $title;


    public function __construct(string $src, string $alt = '', string $title = '')
    {
        $this->src = $src;
        $this->alt = $alt;
        $this->title = $title;

        parent::__construct();
    }
    public function render(): string
    {
        $html = 
            "<img src='" . $this->src . "' alt='" .  $this->alt . "' title='" .  $this->title . "' " . 
            $this->renderAttributes() .
            ">";
        return $html;
    }
}