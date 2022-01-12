<?php if (!empty($formInfo)) : ?>
<div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">x</span>
    </button>
    <div><?=$formInfo;?></div>
</div>
<?php endif;?>

<?php if("POST" === $_SERVER["REQUEST_METHOD"] && $form->hasError()) :?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">x</span>
        </button>
        <ul>
            <?php foreach($form->getErrors() as $error) : ?>
                <li><?=$error;?></li>
            <?php endforeach;?>
        </ul>
    </div>
<?php endif;?>