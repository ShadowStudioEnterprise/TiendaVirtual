<div class="modal fade" id="infoCarroProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

  <div class="modal-dialog modal-lg" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Producto añadido correctamente a su carrito</h4>

      </div>

      <div class="modal-body">

        <div id='data-container'>

          <div class="row">

            <div class="col-md-3">

                <img class='img-thumbnail img-responsive' style='max-width:128px' src=''>

            </div>

            <div class="col-md-9">

                <h4 class='title'></h4>

                <p class='desc'></p>

                <input type='number' min='1' id='cantidad' value=1><button class='update' class='btn'>Actualizar</button>

            </div>

          </div>

          <hr>

          <div class="row">

            <div class="col-md-4" >

              <a href="<?=$router->pathFor('cart')?>" class="btn btn-primary">Ver carrito</a>

            </div>

            <div class="col-md-4 col-md-offset-4">

                <div class='pull-right'>

                  <b>Total carrito: <span class='total'></span> </b>

                </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</div>