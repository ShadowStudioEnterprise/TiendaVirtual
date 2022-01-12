<?php
namespace ProyectoWeb\app\controllers\admin;

use Psr\Container\ContainerInterface;
use ProyectoWeb\core\App;

class AdminController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    public function home($request, $response, $args) {
        $pageheader = "Panel de control";
        
        return $this->container->renderer->render($response, "index.view.php", compact('pageheader'));
        
   }
 


}
