
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li>
            <a href="#">About</a>
        </li>
        <li>
            <a href="#">Services</a>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
    </ul>
    <ul class="nav navbar-nav  navbar-right">
        <?php if (isset($_SESSION['username'])): ?>
        <li>
            <a href="/logout"><?=$_SESSION['username']?> <span title='Cerrar Sesión' class='fa fa-sign-out'></span></a>
        </li>
        <?php else : ?>
        <li>
            <a href="/login"><span title='Iniciar Sesión' class='fa fa-sign-in'></span></a>
        </li>
        <?php endif ?>

        <li>
            <a href='<?=$router->pathFor('cart')?>'><span title='Carrito' class='fa fa-shopping-cart'></span> <span class="badge"><?=$cart->howMany()?></span></a>
        </li>
    </ul>
</div>