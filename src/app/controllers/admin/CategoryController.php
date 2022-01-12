<?php
namespace ProyectoWeb\app\controllers\admin;

use Psr\Container\ContainerInterface;
use ProyectoWeb\core\App;

use ProyectoWeb\utils\Forms\InputElement;
use ProyectoWeb\utils\Forms\LabelElement;
use ProyectoWeb\utils\Forms\ButtonElement;
use ProyectoWeb\utils\Forms\FormElement;
use ProyectoWeb\utils\Forms\custom\MyFormGroup;
use ProyectoWeb\utils\Forms\custom\MyFormControl;
use ProyectoWeb\utils\Validator\NotEmptyValidator;
use ProyectoWeb\utils\Validator\MimetypeValidator;
use ProyectoWeb\utils\Validator\MaxSizeValidator;
use ProyectoWeb\entity\Category;
use ProyectoWeb\exceptions\QueryException;
use ProyectoWeb\exceptions\NotFoundException;
use ProyectoWeb\database\Connection;
use ProyectoWeb\repository\CategoryRepository;

class CategoryController
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

        $repositorio = new CategoryRepository();
        try{
            $categoria = $repositorio->findFirst();
            return $response->withRedirect($this->container->router->pathFor('edit-category', ['id' => $categoria->getId()]), 303);
        }catch(NotFoundException $nfe){
            return $response->withRedirect($this->container->router->pathFor('new-category'), 303);
        }
    }

    /**
     * Genera el formulario para añadir/editar una categoría
     *
     * @param Category $categoria
     * @return FormElement
     */
    private function getForm(Category $categoria = null): FormElement {
       
        $nombre = new InputElement('text');
        $nombre
        ->setName('nombre')
        ->setId('nombre')
        ->setValidator(new NotEmptyValidator('El nombre es obligatorio', true));
        $nombreWrapper = new MyFormControl($nombre, 'Nombre', 'col-xs-12');

        $icon = new InputElement('text');
        $icon
        ->setName('icon')
        ->setId('icon')
        ->setValidator(new NotEmptyValidator('El icono es obligatorio', true));
        $iconWrapper = new MyFormControl($icon, 'Icono', 'col-xs-12');

        $b = new ButtonElement('Guardar');
        $b->setCssClass('pull-right btn btn-lg');

        if (!is_null($categoria)) {
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

            $id->setDefaultValue($categoria->getId());
            $nombre->setDefaultValue($categoria->getNombre());
            $icon->setDefaultValue($categoria->getIcon());

            $form = new FormElement($this->container->router->pathFor('edit-category', ['id' => $categoria->getId()]));
            $form->appendChild($idWrapper);
        } else {
            $form = new FormElement($this->container->router->pathFor('new-category'));
        }

        $form->setCssClass('form-horizontal');
        $form
        ->appendChild($nombreWrapper)
        ->appendChild($iconWrapper)
        ->appendChild($b);
        if (!is_null($categoria)) {
            $form->appendChild($buttonDelete);
        }

        return $form;
    }

    public function add($request, $response, $args) {
        $pageheader = "Categorías: nueva";
       
        $form = $this->getForm(); 

        $formElements = $form->getFormElements();

        $repositorio = new CategoryRepository();
        $categorias = $repositorio->findAll();

        if ("POST" === $_SERVER["REQUEST_METHOD"]) {
            $form->validate();
             
            if (!$form->hasError()) {
                try {
                    $categoria = new Category(null, $formElements['nombre']->getValue(), $formElements['icon']->getValue());
                    $repositorio->save($categoria);
                    $categoria->setId(App::get('connection')->lastInsertId());
                    $form->reset();
                    $this->container->flash->addMessage('formInfo', 'Categoría guardada correctamente');
                    return $response->withRedirect($this->container->router->pathFor('edit-category', ['id' => $categoria->getId()]), 303);
                }catch(QueryException $qe) {
                    $excepcion = $qe->getMessage();
                    if ((strpos($excepcion, '1062') !== false)) {
                      if ((strpos($excepcion, 'nombre') !== false)) {
                        $form->addError('Ya existe una categoría con dicho nombre');
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

        return $this->container->renderer->render($response, "categorias.view.php", compact('pageheader', 'form', 'categorias'));
    }

    public function edit($request, $response, $args) {
        extract($args);
        $pageheader = "Categorías: editar";
        
        $repositorio = new CategoryRepository();
        try {
            $categoria = $repositorio->findById($id);
        }catch(NotFoundException $nfe) {
            return $response->write("Categoría no encontrada");
        }
        
        $form = $this->getForm($categoria); 

        $formElements = $form->getFormElements();

        $categorias = $repositorio->findAll();
        if ("POST" === $_SERVER["REQUEST_METHOD"]) {
            $form->validate();
            if ($formElements['delete']->isSubmitted()) {
                try {
                    $repositorio->delete($categoria);
                    $this->container->flash->addMessage('formInfo', 'Categoría eliminada correctamente');
                    return $response->withRedirect($this->container->router->pathFor('categorias'), 303);
                }catch(QueryException $qe) {
                    $excepcion = $qe->getMessage();
                    if ((strpos($excepcion, 'foreign key constraint fails') !== false)) {
                       $form->addError('Esta categoría no puede borrarse porque tiene productos asociados');
                    } else {
                        $form->addError($qe->getMessage());
                    }
                }
            }
            
            if (!$form->hasError()) {
                try {
                    $categoria = new Category($formElements['id']->getValue(), $formElements['nombre']->getValue(), $formElements['icon']->getValue());
                    $repositorio->update($categoria);
                    $form->reset();
                    $this->container->flash->addMessage('formInfo', 'Categoría guardada correctamente');
                    return $response->withRedirect($this->container->router->pathFor('edit-category', ['id' => $formElements['id']->getValue()]), 303);
                }catch(QueryException $qe) {
                    $excepcion = $qe->getMessage();
                    if ((strpos($excepcion, '1062') !== false)) {
                      if ((strpos($excepcion, 'nombre') !== false)) {
                        $form->addError('Ya existe una categoría con dicho nombre');
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

        return $this->container->renderer->render($response, "categorias.view.php", compact('pageheader', 'form', 'categorias'));
    }

    public function delete($request, $response, $args) {
        extract($args);
        return $response->write("delete $id");
    }
}
