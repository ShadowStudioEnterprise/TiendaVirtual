<?php
namespace ProyectoWeb\app\controllers;

use ProyectoWeb\core\App;
use ProyectoWeb\exceptions\NotFoundException;
use ProyectoWeb\exceptions\QueryException;
use ProyectoWeb\repository\CategoryRepository;
use ProyectoWeb\repository\ProductRepository;
use Psr\Container\ContainerInterface;
use JasonGrimes\Paginator;

use ProyectoWeb\entity\Product;


class CategoryController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    public function listado($request, $response, $args) {
        extract($args);
        $repositorio = new CategoryRepository();
        try {
            $categoriaActual =$repositorio->findById($id);

        } catch (NotFoundException $th) {
            return $response->write("Categoría no encontrada");
        }
        $title = $categoriaActual->getNombre();
        $repositorioProductos = new ProductRepository();

        $currentPage =($currentPage ?? 1);
        $totalItems=$repositorioProductos->getCountByCategory($categoriaActual->getId());
        $itemsPerPage= App::get('config')['itemsPerPage'];
        $urlPattern = $this->container->router->pathFor('categoria', 
        ['nombre' =>  \ProyectoWeb\app\utils\Utils::encodeURI($categoriaActual->getNombre()),
         'id' => $categoriaActual->getId() ]) . '/page/(:num)';
         $paginator = new Paginator($totalItems,$itemsPerPage,$currentPage,$urlPattern);
        $productos =$repositorioProductos->getByCategory($categoriaActual->getId(),$itemsPerPage,$currentPage);
        $categorias =$repositorio->findAll();
        return $this->container->renderer->render($response, "categoria.view.php", compact('title','categorias','categoriaActual','productos','paginator'));

    }
}
