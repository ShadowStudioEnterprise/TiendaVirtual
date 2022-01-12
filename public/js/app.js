function confirmEmptyCart(){

    //Siempre que una acción no se pueda deshacer hay que pedir confirmación al usuario

    if (confirm("¿Seguro que desea vaciar el carrito? "))

        return true;

    else

        return false;

}
function confirmDeleteItem(){

    //Siempre que una acción no se pueda deshacer hay que pedir confirmación al usuario

    if (confirm("¿Seguro que desea eliminar este producto? "))

        return true;

    else

        return false;

}
//Immediately-Invoked Function Expression (IIFE)

(function(){

    //El elemento dom con la plantilla del carro

    var infoCarro = $("#infoCarroProducto");

    //El elemento dom de la máscara

    var mask = $("#mask");

    //El elemento del header que muestra la cantidad de productoss

    var cuantosEl = $(".nav.navbar-nav.navbar-right .badge");

    

    //Elementos dom a actualizar en la ventana modal del carro

    var cartElements = {

        total: infoCarro.find("#data-container .total"), 

        title: infoCarro.find("#data-container .title"), 

        desc: infoCarro.find("#data-container .desc"),

        img: infoCarro.find("#data-container img"),

        cantidad: infoCarro.find("#data-container #cantidad"),

        updateButton: infoCarro.find("#data-container .update")

    };    

    //selector css del botón comprar del producto

    $( ".thumbnail .btn.btn-danger, #ficha-producto .btn.btn-danger" ).click(function(event) {

        event.preventDefault();

        mask.show();

        //Obtener el elemento clicado

        var el =  $(event.target);

        //y de ahi obtener el atributo href

        var href = el.prop('href');

        //porque es donde se encuentra el id de producto

        var parts = href.split("/");

        //la url es del estilo http://127.0.0.1:8080/cart/add/2/. Es decir el id se encuentra en la posición 6

        var id = parts[5];

        //Realizamos una petición por ajax

        var hrefJson = "/cart/add/json/" + id; 

        var jqxhr = $.get( hrefJson, function(data) {

            //Este timeout sólo lo hago porque de otra forma lo hace

            //tan rápido que no se nota el efecto. De hecho lo podéis quitar

            setTimeout(function(){

                //Parsear los datos json que me ha devuelto ajax

                var jData = JSON.parse(data);               

                //Actualizar el contador de items del header

                cuantosEl.html(jData.totalItems);

                 //una vez tenemos los datos sólo nos queda modificar la ventana modal con los datos devueltos

                cartElements.total.html(jData.totalCarro);

                cartElements.title.html(jData.producto.nombre);

                cartElements.desc.html(jData.producto.descripcion);

                cartElements.img.attr("src", jData.rutaImagen + jData.producto.foto);

                cartElements.cantidad.val(jData.itemCount);

                mask.hide();

                infoCarro.modal();

                cartElements.updateButton.unbind();

                cartElements.updateButton.click(function(event){
                
                event.preventDefault();
                
                var hrefUpdate = "/cart/update/json/" + id; 
                
                //Hacer un post a update con la cantidad introducida por el usuario
                
                var hrefUpdateQuantity = hrefUpdate + "/" + cartElements.cantidad.val();
                
                var actualizar = $.post( hrefUpdateQuantity, {}, function(data) {
                
                jData = JSON.parse(data);
                
                //Actualizar los datos
                
                cartElements.total.html(jData.totalCarro);
                
                cuantosEl.html(jData.cuantos);
                
                });
                
                });

            }, 500);

          })

          .fail(function() {

            alert( "error" );

            mask.hide();

          });

    });

})();