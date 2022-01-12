//El elemento DOM con el código HTML de la máscara de cargando
var mask = $("#mask");
//El elemento dom que contiene los productos
var container = $("#data-container");
//
var productData = [];
function attachPaginator(){
  //Quitar los eventos que ya pudieran tener (en otro caso, al hacer clic, llamará tantas veces como hayamos llamado a la función attachPaginator)
  $( ".pagination-scroll a" ).off( "click");
  //selector css de los enlaces de paginación
  $( ".pagination-scroll  a" ).on("click", (function(event) {
    event.preventDefault();
    var href = $( this ).attr('href');
    //Obtener el parámetro currentPage del enlace
    //http://localhost/categoriascroll.php?id=1&itemsPerPage=3&currentPage=2
    var curpage = href.split("currentPage=")[1];
    //Guadarnos en una variable local un referencia a this, es decir,
    //al enlace en que se ha hecho click. De otra forma no puedo hacer
    //referencia a él en la llamada a $get porque ahí 'this' es la propia función
    var oSelf = $( this );
    //Mostrar la máscara
    mask.show();
    //Añadir a la url el estado exclusive
    var hrefExclusive = href + "&state=exclusive";
    //Crear el objeto ajax
    var jqxhr = $.get( hrefExclusive, function(data) {
      setTimeout(function(){
        if (href.includes("dir=back&")){
            $( data ).prependTo( container );
            var oInfo;
            productData[curpage-1] = data;
            history.pushState(curpage-1, null, href);
            //Hacer un scroll a los nuevos datos cargados (que será identiticado por curpage)
            //Ver categoriascrolladvanced.php
            $('html, body').animate({scrollTop:  $(".container .subtitle").offset().top - $(".container .subtitle").offset().height},800);
        }
        else{
          //Añadir los datos devueltos al contenedor
          $( data ).appendTo( container );
          productData[curpage] = data;
          history.pushState(curpage, null, href);
          //Hacer un scroll a los nuevos datos cargados (que será identiticado por curpage)
          //Ver categoriascrolladvanced.php
          $('html, body').animate({scrollTop:  $(".row.row-" + curpage).offset().top},800);
        }

        //Ocultar la máscara
        mask.hide();
        //Ocultar el botón en el que hemos hecho clic
        oSelf.hide();
        //Volver a poner los eventos en el paginador
        attachPaginator();
        //Volver a poner los eventos para la ventana modal del producto
        attachModalInfo();
      }, 500);

    })
    .fail(function() {
      alert( "error" );
      mask.hide();
    });


  }));
}
attachPaginator();
window.addEventListener('popstate', function(e) {
//No hacerlo si se ha pedido una sección de la página
if (document.location.href.endsWith("#")){
  return;
}

  //Mostrar la máscara
  mask.show();
  $('html, body').animate({scrollTop : 0},800);
  //Cuando el usuario hace click en el botón de history, el navegador
  //pasa la información que previamente hemos añadido en pushState
  if (e.state !== null){
    //La información está ya almacenada previamente en pushState
    //El título de la página está en el campo e.state.nombre
    var totalData = "";
    for (var i = 1; i <= e.state; i++){
      if (typeof productData[i] !== "undefined") {
          totalData += productData[i];
      }
    }
    container.html(totalData);
    for (var i = 1; i < e.state; i++){
      if (typeof productData[i] !== "undefined") {
            container.find(".row.row-" + i + " a.btn.btn-block").hide();
      }
    }
    attachPaginator();
  }else{
    //La primera carga de la página producto.php no la hacemos por ajax.
    //Es por eso que la tengo que recargar ahora.
    var curpage = document.location.href.split("currentPage=")[1] || 1;
    if (typeof productData[curpage] === "undefined") {
      var hrefExclusive = document.location.href + "&state=exclusive";
      var jqxhr = $.get( hrefExclusive, function(data) {
          productData[curpage] = data;
          var totalData = "";
          for (var i = 1; i <= curpage; i++){
            if (typeof productData[i] !== "undefined") {
                totalData += productData[i];
            }
          }
          container.html(totalData);
          for (var i = 1; i < e.state; i++){
            if (typeof productData[i] !== "undefined") {
                container.find(".row.row-" + i + " a.btn.btn-block").hide();
            }
          }
          attachPaginator();
        });
    }else{
      var totalData = "";
      for (var i = 1; i <= curpage; i++){
        if (typeof productData[i] !== "undefined") {
            totalData += productData[i];
        }
      }
      container.html(totalData);
      for (var i = 1; i < e.state; i++){
        if (typeof productData[i] !== "undefined") {
            container.find(".row.row-" + i + " a.btn.btn-block").hide();
        }
      }
      attachPaginator();
    }
  }
  setTimeout(function(){mask.hide();},500);
});
