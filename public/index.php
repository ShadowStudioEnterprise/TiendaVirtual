<?php
session_start();
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/core/bootstrap.php';

use ProyectoWeb\app\controllers\CartController;
use Slim\Views\PhpRenderer;
use ProyectoWeb\app\controllers\PageController;
use ProyectoWeb\app\controllers\ProductController;
use ProyectoWeb\app\controllers\CategoryController;
use ProyectoWeb\app\controllers\UserController;
use ProyectoWeb\core\App;
use ProyectoWeb\core\Cart;

App::bind('rootDir', __DIR__ . '/');

$app = new \Slim\App(APP::get('config')['slim']);

$container = $app->getContainer();
$container['cart'] = new Cart();
$templateVariables = [
    "basePath" => $container->request->getUri()->getBasePath(),
    "userName" => ($_SESSION['username'] ?? ''),
    "withCategories" => true,
    "router" => $container->router,
    "cart" => $container->cart 

];

$container['renderer'] = new PhpRenderer("../src/app/views", $templateVariables);

$app->get('/', PageController::class . ':home')->setName("home");
$app->get('/producto/{nombre}/{id:[0-9]+}', ProductController::class . ':ficha')->setName("ficha");
$app->get('/categoria/{nombre}/{id:[0-9]+}[/page/{currentPage:[0-9]+}]', CategoryController::class . ':listado')->setName("categoria");

$app->map(['GET', 'POST'], '/login', UserController::class . ':login')->setName("login");
$app->map(['GET', 'POST'], '/register', UserController::class . ':register');
$app->get('/logout', UserController::class . ':logout');
$app->get('/cart', CartController::class . ':render')->setName("cart");
$app->get('/cart/add/{id:[0-9]+}[/{quantity:[0-9]+}]', CartController::class . ':add')->setName("cart-add");
$app->get('/cart/empty', CartController::class . ':empty')->setName("cart-empty");
$app->get('/cart/delete/{id:[0-9]+}', CartController::class . ':delete')->setName("cart-delete");
$app->get('/cart/checkout', CartController::class . ':checkout')->setName("cart-checkout");
$app->get('/cart/thankyou', CartController::class . ':thankyou')->setName("cart-thankyou");
$app->get('/cart/add/json/{id:[0-9]+}[/{quantity:[0-9]+}]', CartController::class . ':addJSON')->setName("cart-add-json");
$app->post('/cart/update/json/{id:[0-9]+}/[{quantity:[0-9]+}]', CartController::class . ':update')->setName("cart-update-json");


$app->run();
