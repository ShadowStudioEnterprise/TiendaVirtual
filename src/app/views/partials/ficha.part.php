<div id="ficha-producto" class="row">

  <h2 class="subtitle"><?= $producto->getNombre()?></h2>

  <img class="center-block img-responsive img-thumbnail" 

       src="<?= ProyectoWeb\entity\Product::RUTA_IMAGENES . '600_' . $producto->getFoto();?>" 

       alt="<?= $producto->getNombre()?>" 

       title="<?= $producto->getNombre()?>">

  <div class="caption">

    <p class="lead"><?= $producto->getDescripcion()?><p>

  </div>

  <h4 class="pull-right"><span class="text text-danger"><?= number_format($producto->getPrecio(), 2, ',', ' ')?> </span></h4>

  <a href="#" class="btn btn-danger">Comprar</a>  

</div>

