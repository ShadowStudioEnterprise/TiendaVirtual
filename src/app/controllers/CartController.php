<?php

namespace ProyectoWeb\app\controllers;

use Psr\Container\ContainerInterface;

use ProyectoWeb\entity\Product;

use ProyectoWeb\exceptions\QueryException;

use ProyectoWeb\exceptions\NotFoundException;

use ProyectoWeb\database\Connection;

use ProyectoWeb\repository\ProductRepository;

use ProyectoWeb\core\App;

class CartController

{

    protected $container;

   
    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    public function render($request, $response, $args) {
        extract($args);
        $title = " Carrito ";
        $header = "Carrito de la compra";
        $checkout = false;
        $withCategories = false;
        $repositorio = new ProductRepository();
        $productos =$repositorio->getFromCart($this->container->cart);
        return $this->container->renderer->render($response, "cart.view.php", compact('title', 'header','checkout','withCategories', 'productos'));
    }
    public function add($request, $response, $args) {

        extract($args);
    
        $quantity = ($quantity ?? 1);
    
        $respitorio = new ProductRepository;
    
        try {
    
            $producto = $respitorio->findById($id);
    
            $this->container->cart->addItem($id, $quantity);
    
            
    
        }catch(NotFoundException $nfe){
            ;
    
        }
        return $response->withRedirect($this->container->router->pathFor('cart'), 303);
    
    }
    public function empty($request, $response, $args) {
        extract($args);
        $this->container['cart']->empty();
        return $response->withRedirect($this->container->router->pathFor('cart'), 303);
    
    }
    public function delete($request, $response, $args) {
        extract($args);
        $this->container['cart']->deleteItem($id);
        return $response->withRedirect($this->container->router->pathFor('cart'), 303);
    
    }
    public function checkout($request, $response, $args) {
        if (!isset($_SESSION['username'])) {

            return $response->withRedirect($this->container->router->pathFor('login') . 
    
            "?returnToUrl=" . $this->container->router->pathFor('cart-checkout'), 303);
    
        }
        extract($args);
        $title = " Finalizar compra";
        $header = "Pagar con PayPal";
        $withCategories = false;
        $checkout = true;

        $repositorio = new ProductRepository();
        $productos = $repositorio->getFromCart($this->container->cart);
        return $this->container->renderer->render($response, "cart.view.php", compact('title', 'header','checkout','withCategories', 'productos'));

    }
    public function thankyou($request, $response, $args) {
        $title = "Gracias por su compra";
        $withCategories = false;
        $this->container['cart']->empty();
        return $this->container->renderer->render($response, "thankyou.view.php", compact('title', 'withCategories'));
    }

    public function update($request, $response, $args) {
        extract($args);
        $quantity ??= 1; // Por defecto es 1
        $repositorio = new ProductRepository;
        try {
            //Añadimos al carrito
            $productoActual = $repositorio->findById($id);
            $this->container->cart->addItem($id, $quantity);
        }catch(NotFoundException $nfe){
            return json_encode([]);
        }
        //Obtenemos el total del carro
        $productos = $repositorio->findAll();
        $total = 0;
        foreach ($productos as $producto){
            $total += $this->container->cart->getItemCount($producto->getId()) * $producto->getPrecio();
        }
        //Devolvemos los datos formateados como json
        return json_encode(["totalCarro" => number_format($total, 2, ",", "."),]);
    }
    public function addJSON($request, $response, $args) {

        extract($args);
    
        $quantity = 1; //Por add sólo añadimos 1 si no está ya en el carro
    
        $repositorio = new ProductRepository;
    
        try {
    
            //Añadimos al carrito
    
            $productoActual = $repositorio->findById($id);
    
            if (!$this->container->cart->itemExists($id))
    
                $this->container->cart->addItem($id, $quantity);
    
        }catch(NotFoundException $nfe){
    
            return json_encode([]);
    
        }
    
        //Obtenemos el total del carro
    
        $productos = $repositorio->findAll();
    
        $total = 0;
    
        foreach ($productos as $producto){
    
            $total += $this->container->cart->getItemCount($producto->getId()) * $producto->getPrecio();
    
        }
        //Devolvemos los datos formateados como json
    
        return json_encode(["producto" => $productoActual->toArray(),
    
                            "itemCount" => $this->container->cart->getItemCount($id),
    
                            "totalCarro" => number_format($total, 2, ",", "."),
    
                            "rutaImagen" => \ProyectoWeb\entity\Product::RUTA_IMAGENES,
    
                            "totalItems" => $this->container->cart->howMany($id)]);
    
    }
    
}