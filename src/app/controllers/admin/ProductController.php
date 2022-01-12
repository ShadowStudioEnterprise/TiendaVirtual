<?php
namespace ProyectoWeb\app\controllers\admin;

use Psr\Container\ContainerInterface;
use ProyectoWeb\core\App;
use ProyectoWeb\utils\Forms\InputElement;
use ProyectoWeb\utils\Forms\TextareaElement;
use ProyectoWeb\utils\Forms\LabelElement;
use ProyectoWeb\utils\Forms\ButtonElement;
use ProyectoWeb\utils\Forms\FormElement;
use ProyectoWeb\utils\Forms\SelectElement;
use ProyectoWeb\utils\Forms\OptionElement;
use ProyectoWeb\utils\Forms\NumberElement;
use ProyectoWeb\utils\Forms\CheckboxElement;
use ProyectoWeb\utils\Forms\ImgElement;
use ProyectoWeb\utils\Forms\FileElement;
use ProyectoWeb\utils\Forms\custom\MyFormGroup;
use ProyectoWeb\utils\Forms\custom\MyFormControl;
use ProyectoWeb\utils\Validator\NotEmptyValidator;
use ProyectoWeb\utils\Validator\MimetypeValidator;
use ProyectoWeb\utils\Validator\MaxSizeValidator;
use ProyectoWeb\utils\Validator\FileNotEmptyValidator;
use ProyectoWeb\entity\Category;
use ProyectoWeb\entity\Product;
use ProyectoWeb\exceptions\QueryException;
use ProyectoWeb\exceptions\NotFoundException;
use ProyectoWeb\database\Connection;
use ProyectoWeb\repository\CategoryRepository;
use ProyectoWeb\repository\ProductRepository;

class ProductController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    public function home($request, $response, $args) {
        //Cuidado, al hacer redirección el mensaje flash se ha borrado en index.php
        //Por eso, hay que regerarlo
        $previousFlashMessage = $this->container->renderer->getAttributes()['formInfo'] ?? '';
        $this->container->flash->addMessage('formInfo',  $previousFlashMessage);

        $repositorio = new ProductRepository();
        try{
            $producto = $repositorio->findFirst();
            return $response->withRedirect($this->container->router->pathFor('edit-product', ['id' => $producto->getId()]), 303);
        }catch(NotFoundException $nfe){
            return $response->withRedirect($this->container->router->pathFor('new-product'), 303);
        }
        
   }
     /**
     * Genera el formulario para añadir/editar un producto
     *
     * @param Product $producto
     * @return FormElement
     */
    private function getForm(Product $producto = null): FormElement {
       
        $nombre = new InputElement('text');
        $nombre
        ->setName('nombre')
        ->setId('nombre')
        ->setValidator(new NotEmptyValidator('El nombre es obligatorio', true));
        $nombreWrapper = new MyFormControl($nombre, 'Nombre', 'col-xs-12');

        $descripcion = new TextareaElement();
        $descripcion
        ->setName('descripcion')
        ->setId('descripcion')
        ->setValidator(new NotEmptyValidator('La descripción es obligatoria', true));
        $descripcionWrapper = new MyFormControl($descripcion, 'Descripción', 'col-xs-12');

        $repositorioCategoria = new CategoryRepository();
  
        $categoriasEl = new SelectElement(false);
       
        $categoriasEl
        ->setName('categoria')
        ->setId('categoria');
    
        $categorias = $repositorioCategoria->findAll();
        foreach ($categorias as $categoria) {
            $option = new OptionElement($categoriasEl, $categoria->getNombre());
    
            $option->setDefaultValue( $categoria->getId());
            
            $categoriasEl->appendChild($option);
        }
        
        $categoriaWrapper = new MyFormControl($categoriasEl, 'Categoría', 'col-xs-12');
        
        $precio = new NumberElement();
        $precio
        ->setName('precio')
        ->setId('precio')
        ->setValidator(new NotEmptyValidator('El precio es obligatorio', true));
        $precioWrapper = new MyFormControl($precio, 'Precio', 'col-xs-12');
        
        $foto = new FileElement();
        $foto
        ->setName('foto')
        ->setId('foto');

        if (!is_null($producto)) { 
            $fv = new MimetypeValidator(['image/jpeg', 'image/jpg', 'image/png'], 'Formato no soportado', true);
        } else {
            $fv = new FileNotEmptyValidator('La foto no puede estar vacía', true);
            $fv->setNextValidator(new MimetypeValidator(['image/jpeg', 'image/jpg', 'image/png'], 'Formato no soportado', true));
        }
        $foto->setValidator($fv);
        
        $labelFoto = new LabelElement('Foto', $foto);
        $labelFoto->setStyle('display:block');

        $carrusel = new FileElement();
        $carrusel
          ->setName('carrusel')
          ->setId('carrusel')
          ->setValidator(new MimetypeValidator(['image/jpeg', 'image/jpg', 'image/png'], 'Formato no soportado', true));
    
        $labelCarrusel = new LabelElement('Carrusel', $carrusel);
       
        $b = new ButtonElement('Guardar');
        $b->setCssClass('pull-right btn btn-lg');

        if (!is_null($producto)) {
            $destacado = new CheckboxElement("Destacado", $producto->getDestacado() == 1 ? true: false);

            $destacado        
              ->setName("destacado")        
              ->setDefaultValue("1");

            $id = new InputElement('text');
            $id
              ->setName('id')
              ->setId('id')
              ->setAttribute('readonly', '');
            $idWrapper = new MyFormControl($id, 'ID', 'col-xs-12');

            $buttonDelete =  new ButtonElement('Eliminar');
            $buttonDelete
              ->setId('delete')
              ->setName('delete')
              ->setAttribute('onclick', "return checkDelete();");

            $buttonDelete->setCssClass('pull-right btn btn-lg');

            $id->setDefaultValue($producto->getId());
            $nombre->setDefaultValue($producto->getNombre());
            $descripcion->setDefaultValue($producto->getDescripcion());
            $categoriasEl->setDefaultValue($producto->getIdCategoria());
            $precio->setDefaultValue($producto->getPrecio());
            $foto->setDefaultValue($producto->getFoto());
            $carrusel->setDefaultValue($producto->getCarrusel());
            $destacado->setDefaultValue($producto->getDestacado() == 1 ? true: false);
            
            $imgFoto = new ImgElement(Product::RUTA_IMAGENES . '256_'. $producto->getFoto());
            $imgFoto->setStyle('display:block');

            if (!empty($producto->getCarrusel())) {
                $imgCarrusel = new ImgElement(Product::RUTA_IMAGENES_CARRUSEL . $producto->getCarrusel());
                $imgCarrusel->setStyle('width:256px; display:block');
            }

            $form = new FormElement($this->container->router->pathFor('edit-product', ['id' => $producto->getId()]), 'multipart/form-data');
            $form->appendChild($idWrapper);

        } else {
            $destacado = new CheckboxElement("Destacado", false);

            $destacado        
              ->setName("destacado")        
              ->setDefaultValue("1");
            $form = new FormElement($this->container->router->pathFor('new-product'), 'multipart/form-data');
        }

        $form->setCssClass('form-horizontal');
        $form
        ->appendChild($nombreWrapper)
        ->appendChild($descripcionWrapper)
        ->appendChild($categoriaWrapper)
        ->appendChild($precioWrapper)
        ->appendChild($destacado)
        ->appendChild($labelFoto)
        ->appendChild($foto);
        if (!is_null($producto)) {
            $form->appendChild($imgFoto);
        }
        $form->appendChild($labelCarrusel)
        ->appendChild($carrusel);
        if (!is_null($producto) && isset($imgCarrusel)) {
            $form->appendChild($imgCarrusel);
        }
        $form        
        ->appendChild($b);

        if (!is_null($producto)) {
            $form->appendChild($buttonDelete);
        }

        return $form;
    }

    public function edit($request, $response, $args) {
        extract($args);
        $pageheader = "Productos: Editar";
       
        $repositorio = new ProductRepository();
        try {
            $producto = $repositorio->findById($id);
        }catch(NotFoundException $nfe) {
            return $response->write("Producto no encontrada");
        }
        
        $form = $this->getForm($producto); 

        $formElements = $form->getFormElements();

        $productos = $repositorio->findAll();
        if ("POST" === $_SERVER["REQUEST_METHOD"]) {
            $form->validate();
            if ($formElements['delete']->isSubmitted()) {
                try {
                    $repositorio->delete($producto);
                    $this->container->flash->addMessage('formInfo', 'Producto eliminado correctamente');
                    return $response->withRedirect($this->container->router->pathFor('productos'), 303);
                }catch(QueryException $qe) {
                    $form->addError($qe->getMessage());
                }
            }
            
            if (!$form->hasError()) {
                try {
                    $simpleImage = new \claviska\SimpleImage();
                    $foto = $formElements['foto'];
                    if (!empty($foto->getFileName())) {
                        $foto->saveUploadedFile(APP::get('rootDir') . Product::RUTA_IMAGENES); 
                        $simpleImage
                        ->fromFile(APP::get('rootDir') . Product::RUTA_IMAGENES . $foto->getFileName())  
                        ->resize(600)
                        ->toFile(APP::get('rootDir') . Product::RUTA_IMAGENES . '600_' . $foto->getFileName())
                        ->resize(256)
                        ->toFile(APP::get('rootDir') . Product::RUTA_IMAGENES . '256_' . $foto->getFileName()); 
                    }

                    $carrusel = $formElements['carrusel'];
                    if (!empty($carrusel->getFileName())) {
                        $carrusel->saveUploadedFile(APP::get('rootDir') . Product::RUTA_IMAGENES_CARRUSEL); 
                        $simpleImage
                        ->fromFile(APP::get('rootDir') . Product::RUTA_IMAGENES_CARRUSEL . $carrusel->getFileName())  
                        ->resize(800, 300)
                        ->toFile(APP::get('rootDir') . Product::RUTA_IMAGENES_CARRUSEL . $carrusel->getFileName());
                    }
                    $producto = new Product($formElements['id']->getValue(), $formElements['nombre']->getValue(), 
                                           $formElements['descripcion']->getValue(), $formElements['categoria']->getValue(),
                                           $formElements['precio']->getValue(), 
                                           !empty($foto->getFileName()) ? $formElements['foto']->getFileName() : $producto->getFoto(),
                                           ($formElements['destacado']->isChecked() ? 1: 0), 
                                           !empty($carrusel->getFileName()) ? $formElements['carrusel']->getFileName() : $producto->getCarrusel());
                                           
                    $repositorio->update($producto);
                    $form->reset();
                    $this->container->flash->addMessage('formInfo', 'Producto guardado correctamente');
                    return $response->withRedirect($this->container->router->pathFor('edit-product', ['id' => $formElements['id']->getValue()]), 303);
                }catch(QueryException $qe) {
                    $excepcion = $qe->getMessage();
                    if ((strpos($excepcion, '1062') !== false)) {
                      if ((strpos($excepcion, 'nombre') !== false)) {
                        $form->addError('Ya existe un producto con dicho nombre');
                      } else {
                        $form->addError($qe->getMessage());
                      }
                    } else {
                      $form->addError($qe->getMessage());
                    }
                }
                catch(Exception $err) {
                    $form->addError($err->getMessage());
                }
            }
        }    

        return $this->container->renderer->render($response, "productos.view.php", compact('pageheader', 'form', 'productos'));
    }    
    public function add($request, $response, $args) {
    $pageheader = "Productos: nuevo";
   
    $form = $this->getForm(); 

    $formElements = $form->getFormElements();

    $repositorio = new ProductRepository();
    $productos = $repositorio->findAll();

    if ("POST" === $_SERVER["REQUEST_METHOD"]) {
        $form->validate();
         
        if (!$form->hasError()) {
            try {
                $foto = $formElements['foto'];
                $foto->saveUploadedFile(APP::get('rootDir') . Product::RUTA_IMAGENES); 
                // Create a new SimpleImage object
                $simpleImage = new \claviska\SimpleImage();
                $simpleImage
                ->fromFile(APP::get('rootDir') . Product::RUTA_IMAGENES . $foto->getFileName())  
                ->resize(600)
                ->toFile(APP::get('rootDir') . Product::RUTA_IMAGENES . '600_' . $foto->getFileName())
                ->resize(256)
                ->toFile(APP::get('rootDir') . Product::RUTA_IMAGENES . '256_' . $foto->getFileName()); 

                $carrusel = $formElements['carrusel'];
                if (!empty($carrusel->getFileName())) {
                    $carrusel->saveUploadedFile(APP::get('rootDir') . Product::RUTA_IMAGENES_CARRUSEL); 
                    $simpleImage
                    ->fromFile(APP::get('rootDir') . Product::RUTA_IMAGENES . $foto->getFileName())  
                    ->resize(800, 300)
                    ->toFile(APP::get('rootDir') . Product::RUTA_IMAGENES_CARRUSEL . $foto->getFileName());
                }

                $producto = new Product(null, $formElements['nombre']->getValue(), 
                                       $formElements['descripcion']->getValue(), $formElements['categoria']->getValue(),
                                       $formElements['precio']->getValue(), $formElements['foto']->getFileName(),
                                       ($formElements['destacado']->isChecked() ? 1: 0), $formElements['carrusel']->getFileName());
                $repositorio->save($producto);
                $producto->setId(App::get('connection')->lastInsertId());
                $form->reset();
                $this->container->flash->addMessage('formInfo', 'Producto guardado correctamente');
                return $response->withRedirect($this->container->router->pathFor('edit-product', ['id' => $producto->getId()]), 303);
            }catch(QueryException $qe) {
                $excepcion = $qe->getMessage();
                if ((strpos($excepcion, '1062') !== false)) {
                  if ((strpos($excepcion, 'nombre') !== false)) {
                    $form->addError('Ya existe un producto con dicho nombre');
                  } else {
                    $form->addError($qe->getMessage());
                  }
                } else {
                  $form->addError($qe->getMessage());
                }
            }
            catch(Exception $err) {
                $form->addError($err->getMessage());
            }
        }
    }    

    return $this->container->renderer->render($response, "productos.view.php", compact('pageheader', 'form', 'productos'));
}

}
