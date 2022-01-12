
<div class="list-group">
    <a class="list-group-item text-center active" data-remote="true" href="#" id="categoria_0">
    Categorías
    </a>
    <?php foreach($categorias as $categoria) : ?>
        <a class="list-group-item" data-remote="true"  href="<?=$router->pathFor('categoria', 

['nombre' =>  ProyectoWeb\app\utils\Utils::encodeURI($categoria->getNombre()), 'id' => $categoria->getId()])?>" style="padding-left: 25px;">
        <span class="fa <?=$categoria->getIcon()?> fa-lg fa-fw"></span>
        <span style="margin-left: 25px;"><?=$categoria->getNombre()?></span>
        </a>
    <?php endforeach?>
</div>