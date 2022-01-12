<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location: /login?returnToUrl=' .  urlencode($_SERVER["REQUEST_URI"]));
    exit;
}
require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/core/bootstrap.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;
use \Slim\Flash\Messages;
use ProyectoWeb\app\controllers\admin\AdminController;
use ProyectoWeb\app\controllers\admin\CategoryController;
use ProyectoWeb\app\controllers\admin\ProductController;
use ProyectoWeb\core\App;

App::bind('rootDir', __DIR__ . '/../');

$app = new \Slim\App(APP::get('config')['slim']);

$container = $app->getContainer();

// Register provider
$container['flash'] = function () {
    return new Messages();
};

$templateVariables = [
    "basePath" =>  $container->request->getUri()->getBasePath(),
    "formInfo" => ($container->flash->getMessage('formInfo')[0] ?? ""),
    "router" => $container->router
];

$container['renderer'] = new PhpRenderer("../../src/app/views/admin/", $templateVariables);

$app->get('/', AdminController::class . ':home')->setName("home");
$app->get('/categorias', CategoryController::class . ':home')->setName("categorias");
$app->map(['GET', 'POST'], '/categorias/new', CategoryController::class . ':add')->setName('new-category');
$app->map(['GET', 'POST'], '/categorias/edit/{id:[0-9]+}', CategoryController::class . ':edit')->setName('edit-category');
$app->post('/categorias/delete/{id:[0-9]+}', CategoryController::class . ':delete');
$app->get('/productos', ProductController::class . ':home')->setName("productos");
$app->map(['GET', 'POST'], '/productos/new', ProductController::class . ':add')->setName('new-product');
$app->map(['GET', 'POST'], '/productos/edit/{id:[0-9]+}', ProductController::class . ':edit')->setName('edit-product');
$app->post('/productos/delete/{id:[0-9]+}', ProductController::class . ':delete');
$app->run();
