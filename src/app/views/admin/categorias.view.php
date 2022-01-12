<?php
  include __DIR__ . "/partials/inicio-doc.part.php";
?>
<div class="row">
  <a class='btn btn-default' href='<?=$router->pathFor('new-category');?>'>Añadir</a>
<div>
<hr>
<div class="row">
  <div class="col-lg-2">
    <ul>
      <?php foreach($categorias as $categoria) :?>
        <li><a href='<?=$router->pathFor('edit-category', ['id' => $categoria->getId()])?>'><?=$categoria->getNombre();?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="col-lg-6">
    <?php
          include __DIR__ . "/partials/show-messages.part.php";
      ?>
      <?=$form->render();?>
  </div>
</div>             
<?php
  include __DIR__ . "/partials/fin-doc.part.php";
?>