<?php 
namespace ProyectoWeb\entity;

use ProyectoWeb\entity\Entity;
class Product extends Entity
{
    const RUTA_IMAGENES = '/images/';
    const RUTA_IMAGENES_CARRUSEL = '/images/carrusel/';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var int
     */
    private $id_categoria;

    /**
     * @var float
    */
    private $precio;
    
    /**
     * @var string
    */
    private $foto;

    /**
     * @var int
    */
    private $destacado;
    
    /**
     * @var string
    */
    private $carrusel;



    /**
     * @param string $nombre
     * @param string $icon
     */
    public function __construct(int $id = null, string $nombre = '', string $descripcion = '', int $id_categoria = 0, 
                       float $precio = 0, string $foto = '', int $destacado = 0, string $carrusel = '' ){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->id_categoria = $id_categoria;
        $this->precio = $precio;
        $this->foto = $foto;
        $this->destacado = $destacado;
        $this->carrusel = $carrusel;
    }

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */ 
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     *
     * @return  string
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @param  string  $nombre
     *
     * @return  self
     */ 
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of descripcion
     *
     * @return  string
     */ 
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     *
     * @param  string  $descripcion
     *
     * @return  self
     */ 
    public function setDescripcion(string $descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of id_categoria
     *
     * @return int
     */ 
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * Set the value of id_categoria
     *
     * @param  int  $id_categoria
     *
     * @return  self
     */ 
    public function setIdCategoria(int $id_categoria)
    {
        $this->id_categoria = $id_categoria;

        return $this;
    }

    /**
     * Get the value of precio
     *
     * @return  float
     */ 
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     *
     * @param  float  $precio
     *
     * @return  self
     */ 
    public function setPrecio(float $precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of foto
     *
     * @return  string
     */ 
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     *
     * @param  string  $foto
     *
     * @return  self
     */ 
    public function setFoto(string $foto)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get the value of destacado
     *
     * @return  int
     */ 
    public function getDestacado()
    {
        return $this->destacado;
    }

    /**
     * Set the value of destacado
     *
     * @param  int  $destacado
     *
     * @return  self
     */ 
    public function setDestacado(int $destacado)
    {
        $this->destacado = $destacado;

        return $this;
    }

    /**
     * Get the value of carrusel
     *
     * @return  string
     */ 
    public function getCarrusel()
    {
        return $this->carrusel;
    }

    /**
     * Set the value of carrusel
     *
     * @param  string  $carrusel
     *
     * @return  self
     */ 
    public function setCarrusel(string $carrusel)
    {
        $this->carrusel = $carrusel;

        return $this;
    }


    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'nombre' => $this->getNombre(),
            'descripcion' => $this->getDescripcion(),
            'id_categoria' => $this->getIdCategoria(),
            'precio' => $this->getPrecio(),
            'foto' => $this->getFoto(),
            'destacado' => $this->getDestacado(),
            'carrusel' => $this->getCarrusel()
        ];
    }
}