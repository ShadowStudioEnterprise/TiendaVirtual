<div class="row carro">
<h2 class="subtitle" style="margin:0;"><?= $header; ?></h2>
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Total</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php if ($cart->isEmpty()) : ?>
      <tr>
        <td colspan='6'>
          <div class='alert alert-danger'>Su carro está vacío.</div>
        </td>
      </tr>
    <?php else : ?>
      <?php $i = 1; $total = 0; foreach ($productos as $producto) :   $total += $cart->getCart()[$producto->getId()] * $producto->getPrecio();
        ?>
      <tr>
        <th scope="row"><?=$i++;?></th>
        <td><a href="<?=$router->pathFor('ficha', ['nombre' =>  ProyectoWeb\app\utils\Utils::encodeURI($producto->getNombre()), 'id' => $producto->getId()])?>"><?= $producto->getNombre()?></a></td>
        <td><?= number_format($producto->getPrecio(), 2, ',', ' ')?> </td>
        <td><?= $cart->getCart()[$producto->getId()] ?></td>
        <td><?=  number_format($cart->getCart()[$producto->getId()] * $producto->getPrecio(), 2, ',', ' '); ?> </td>
        <td><a href="<?=$router->pathFor('cart-delete', ['id' => $producto->getId()])?>" onclick="return confirmDeleteItem();"><span class='fa fa-close'></span></a></td>
      </tr>
    <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
        <td colspan="6" style="text-align:right;"><b>Total: <?= number_format($total, 2, ',', ' ')?> </b> </td>
        </tr>
        <tr>
        <?php if(!$checkout) :?>
        <td colspan="3"><a class="btn btn-danger" href="<?=$router->pathFor('cart-checkout')?>">Finalizar compra</a></td>
        </tr>
        <?php else : ?>
        <td colspan="6">
        <div id="paypal-button-container"></div>
        </td>
        <?php endif ?>
    </tfoot>
    <?php endif ?>

  </table>

</div>
<?php if($checkout): ?>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<script>

        paypal.Button.render({

            env: 'sandbox', // sandbox | production

            // PayPal Client IDs - replace with your own

            // Create a PayPal app: https://developer.paypal.com/developer/applications/create

            client: {

                sandbox:    'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',

                production: '<insert production client id>'

            },

            // Show the buyer a 'Pay Now' button in the checkout flow

            commit: true,

            // payment() is called when the button is clicked

            payment: function(data, actions) {

                // Make a call to the REST api to create the payment

                return actions.payment.create({

                    payment: {

                        transactions: [

                            {

                                amount: { total: '<?=$total?>', currency: 'EUR' }

                            }

                        ]

                    }

                });

            },

            // onAuthorize() is called when the buyer approves the payment

            onAuthorize: function(data, actions) {

                // Make a call to the REST api to execute the payment

                return actions.payment.execute().then(function() {

                    window.alert('Pago Completado!');
                    document.location.href = '<?=$router->pathFor('cart-thankyou')?>';
                });

            }

        }, '#paypal-button-container');

    </script>

<?php endif ?>