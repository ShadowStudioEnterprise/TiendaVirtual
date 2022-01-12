<div class="row">

    <h2 class='subtitle'><?= $categoriaActual->getNombre()?></h2>

    <?php
    foreach($productos as $producto){
        include "thumbnail-producto.part.php";
    }
    ?>

</div>