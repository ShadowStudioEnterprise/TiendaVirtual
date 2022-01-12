<?php
namespace ProyectoWeb\repository;

use ProyectoWeb\database\QueryBuilder;

class CategoryRepository extends QueryBuilder
{
    public function __construct(){
        parent::__construct('categorias', 'Category');
    }
}