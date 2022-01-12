
<div class='col-sm-4 col-lg-4 col-md-4'>
<div class='thumbnail' style='position:relative'>
  <a href='<?=$router->pathFor('ficha', ['nombre' =>  ProyectoWeb\app\utils\Utils::encodeURI($producto->getNombre()), 'id' => $producto->getId()])?>'>
      <img src='<?= ProyectoWeb\entity\Product::RUTA_IMAGENES . '256_' . $producto->getFoto();?>' 
        alt='<?= $producto->getNombre()?>'
        title='<?= $producto->getNombre()?>'>
  </a>
  <div class='caption'>
    <h4><a href='<?=$router->pathFor('ficha', ['nombre' =>  ProyectoWeb\app\utils\Utils::encodeURI($producto->getNombre()), 'id' => $producto->getId()])?>'><?= $producto->getNombre()?></a></h4>
    <p><?= $producto->getDescripcion()?></p>
  </div>
  <h4 class='pull-right'><span class='text text-danger'><?= number_format($producto->getPrecio(), 2, ',', ' ')?> </span></h4>
  <a href='<?=$router->pathFor('cart-add', ['id' => $producto->getId()])?>' class='btn btn-danger'>Comprar</a>
</div>
</div>

