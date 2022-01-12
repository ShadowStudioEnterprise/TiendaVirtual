<?php
namespace ProyectoWeb\app\controllers;

use ProyectoWeb\repository\CategoryRepository;
use ProyectoWeb\repository\ProductRepository;
use Psr\Container\ContainerInterface;


class PageController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    public function home($request, $response, $args) {
        $title = "Inicio";
        $repositorio = new ProductRepository();
        $carrusel = $repositorio->getCarrusel();
        $destacados = $repositorio->getDestacados();
        $novedades = $repositorio->getNovedades();
        $repositorioCateg = new CategoryRepository();
        $categorias =$repositorioCateg->findAll();
        return $this->container->renderer->render($response, "index.view.php", compact('title','categorias','carrusel','destacados','novedades'));
    }
}
