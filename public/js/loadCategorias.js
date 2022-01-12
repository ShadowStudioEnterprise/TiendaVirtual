//El elemento DOM con el código HTML de la máscara de cargando
var mask = $("#mask");
////El elemento dom que contiene los productos
var container = $("#data-container");
//Datos de la primera página cargados por ajax. Ver el evento popstate
var firstUrlData = null;
function attachPaginator(){
  //selector css de los enlaces de paginación
  $( ".pagination li a" ).click(function(event) {
    event.preventDefault();
    var href = $( this ).attr('href');
    //No hacerlo si no tiene href o si es la página actual.
    // <li class="active">
    //   <a href="???????>2</a>
    //  </li>
    if (("#" != href) && (!$( this ).parent().hasClass("active"))){
        //Mostrar la máscara
        mask.show();
        //Añadir a la url el estado exclusive
        hrefExclusive = href + "&state=exclusive";
        //Crear el objeto ajax
        var jqxhr = $.get( hrefExclusive, function(data) {
          //Cuando devuelve los datos, hago un scroll animado para que la página se vea desde el principio
          //De otra forma, la página se quedaría con el scroll que tuviera en el momento de hacer la carga
          $('html, body').animate({scrollTop : 0},800);
          //Este timeout sólo lo hago porque de otra forma lo hace
          //tan rápido que no se nota el efecto. De hecho lo podéis quitar
          setTimeout(function(){
            //Actualizar el html de container con los datos devueltos
            container.html( data );
            // El navegador asocia data con este href, de tal forma que al hacer history back
            // le pasa estos datos al evento popstate
            history.pushState(data, null, href);
            //Ocultar la máscara
            mask.hide();
            //Volver a poner los eventos en el paginador
            attachPaginator();
            //Volver a poner los eventos para la ventana modal del producto
            attachModalInfo();
            if (attachCarro)
                attachCarro();
           }, 500);

        })
        .fail(function() {
          alert( "error" );
          mask.hide();
        });

    }
  });
}
attachPaginator();
/**
Este evento va junto a pushState. Es una versión simplificada
*/
window.addEventListener('popstate', function(e) {
	//No hacerlo si se ha pedido una sección de la página
	if (document.location.href.endsWith("#")){
	  return;
	}

  $('html, body').animate({scrollTop : 0},800);
  //Cuando el usuario hace click en el botón de history, el navegador
  //pasa la información que previamente hemos añadido en pushState
  if (e.state !== null){
    //La información está ya almacenada previamente en pushState
    container.html( e.state );
  }else{
    //La primera carga de la página categorias.php no la hacemos por ajax.
    //Es por eso que la tengo que recargar ahora. Pero sólo lo voy a hacer una vez.
    //Los datos devueltos los almacenaré para usarlos en la siguiente llamada al envento
    if (firstUrlData === null){
      mask.show();
      hrefExclusive = document.location.href + "&state=exclusive";
      var jqxhr = $.get( hrefExclusive, function(data) {
        //Añadir los datos para no tener que volver a pedirlos
        firstUrlData = data;
        container.html( data );
        mask.hide();
        attachPaginator();
        attachModalInfo();
        if (attachCarro)
            attachCarro();
      })
      .fail(function() {
        alert( "error" );
        mask.hide();
      });
    }else{
      container.html( firstUrlData );
      attachModalInfo();
      attachPaginator();
      if (attachCarro)
          attachCarro();
    }
  }
});
/**
  Cómo funciona pushState:
  El navegador mantiene una estructura para guardar el history. Cada vez que llamamos a pushState(data, title, url)
  almacena estos datos. Por ejemplo, si llamamos a pushState("datos1"), null, pagina1.php) y
  pushState("datos2"), null, pagina2.php), este será el contenido de dicha estructura del navegador.
  data      title     url
  ====      =====     ===
  datos1    null      pagina1.php
  datos2    null      pagina2.php

  Cuando el usuario visita una de estas páginas mediante los botones del historial Siguiente o Anterior, llama al evento
  popstate y le pasa un evento como parámetro con los datos guardados para esa url.
  Por ejemplo, cuando el usuario visite con los botones del historial la página pagina2.php, el evento contendrá el valor
  "datos2" en el campo state. Si por el contrario, visita la página pagina1.php, el evento contendrá el valor "datos1"
*/
