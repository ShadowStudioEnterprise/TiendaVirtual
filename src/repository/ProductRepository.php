<?php
namespace ProyectoWeb\repository;

use ProyectoWeb\database\QueryBuilder;
use ProyectoWeb\core\Cart;


class ProductRepository extends QueryBuilder
{
    public function __construct(){
        parent::__construct('productos', 'Product');
    }

    public function getCarrusel()
    {
        $sql = "SELECT * FROM $this->table WHERE carrusel IS NOT NULL AND carrusel != ''";
        return $this->executeQuery($sql);
    }
    public function getDestacados()
    {
        $sql = "SELECT * FROM $this->table WHERE destacado = 1";
        return $this->executeQuery($sql);
    }
    public function getNovedades()
    {
        $sql = "SELECT * FROM $this->table ORDER BY fecha DESC LIMIT 6";
        return $this->executeQuery($sql);
    }
    public function getRelacionados(int $id,int $id_categoria)
    {
        $sql = "SELECT * FROM $this->table WHERE id_categoria = $id_categoria IS NOT NULL AND id != $id ORDER BY RAND() LIMIT 6";
        return $this->executeQuery($sql);
    }
    public function getByCategory(int $id_categoria, int $itemsPerPage, int $currentPage)
    {
        $sql = "SELECT * FROM $this->table WHERE id_categoria = $id_categoria LIMIT $itemsPerPage OFFSET ".$itemsPerPage*($currentPage-1);
        return $this->executeQuery($sql);
    }
    public function getCountByCategory(int $id_categoria)
    {
        $sql = "SELECT count(*) as cuenta FROM $this->table WHERE id_categoria = $id_categoria ";
        $statement =$this->connection->prepare($sql);
        $statement->execute();
        return $statement->fetch(\PDO::FETCH_ASSOC)["cuenta"];
    }
    public function getFromCart(Cart $cart)
    {
        if (empty($cart->getCart())) {
            return array();
        }
        $ids = implode(',',array_keys($cart->getCart()));
        $sql = "SELECT * FROM productos WHERE id IN ($ids)";
        return $this->executeQuery($sql);
    }
}