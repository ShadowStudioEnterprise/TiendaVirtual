<?php
namespace ProyectoWeb\core;

class Cart
{
    /** 
     * Sin parámetros. Sólo crea la variable de sesión
    */
    public function __construct() {
        $this->create();
    }
    /**
     * Crea el carrito si no lo está ya
     */
    public function create(){
        if (!isset($_SESSION['cart'])){
            $_SESSION['cart'] = array();
        }
    }
    /**
     * @return array
     */
    public function getCart(): array {
        return $_SESSION['cart'];
    }
    
    /**
    *	Añade o actualiza la cantidad de un item en el carrito
    *	@id ID del producto a añadir / modificar
    */
    public function addItem($id, $cantidad) {
        $_SESSION['cart'][$id] = $cantidad;
    }

    /**
    *	Comprueba si el item está o no en el carrito
    *	@id ID del producto a comprobar
    *	@return true si el producto ya está en el carrito o false en caso contrario
    */
    public function itemExists($id) {
        return isset($_SESSION['cart'][$id]);
    }
    /**
    *	Devuelve la cantidad comprada de un producto
    *	@return La cantidad comprada o 0 si no está en el carrito
    */
    public function getItemCount($id) {
      if (!$this->itemExists($id))
        return 0;
      else
        return $_SESSION['cart'][$id];
    }

    /**
    *	Elimina un item del carrito
    *	@id ID del producto a eliminar
    */
    public function deleteItem($id) {
      unset($_SESSION['cart'][$id]);
    }

    /**
    *	Elimina todos los items del carrito
    */
    public function empty() {
      unset($_SESSION['cart']);
      $this->create();
    }
    
    /**
     * Devuelve true si el carro está vacío
     *
     * @return boolean
     */
    public function isEmpty(): bool {
       return ($this->howMany() == 0);
    }
  
    /**
    * Devuelve el número total de productos comprados
    * @return el número total de productos comprados o 0 si no hay ninguno
    */
    public function howMany(){
      return array_sum($_SESSION['cart']);
    }
}