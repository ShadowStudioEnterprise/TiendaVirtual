var mask = $("#mask");
function attachPaginator(){
  $( ".pagination-scroll  a" ).click(function(event) {
    event.preventDefault();
    var href = $( this ).attr('href');
    var curpage = href.split("currentPage=")[1];
    var oSelf = $( this );
      mask.show();
      href += "&state=exclusive"
      var jqxhr = $.get( href, function(data) {
        setTimeout(function(){
          var container = $("#data-container");
          $( data ).appendTo( container );
          //Hacer un scroll a los nuevos datos cargados (que será identiticado por curpage)
          $('html, body').animate({scrollTop:  $(".row.row-" + curpage).offset().top},800);
          mask.hide();
          //Ocultar el botón en el que hemos hecho clic
          oSelf.hide();
           attachPaginator();
           attachModalInfo();
           attachCarro();
         }, 1000);

      })
      .fail(function() {
        alert( "error" );
        mask.hide();
      });


  });
}
attachPaginator();
