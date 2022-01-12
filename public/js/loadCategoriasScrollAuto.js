var mask = $("#mask");
$( window ).scroll(function() {
  var el = $(".btn.btn-block.not-done");
  if (el){
    if (elementInViewport(el[ 0 ])){
        attachPaginator(el);
    }
  }
});
function attachPaginator(el){
    var href = $( el ).attr('href');
    var curpage = href.split("currentPage=")[1];
    var oSelf = $( el );
    if (oSelf.hasClass("not-done")){
      //mask.show();
      oSelf.removeClass("not-done");
      href += "&state=exclusive"
      var jqxhr = $.get( href, function(data) {

          var container = $("#data-container");
          $( data ).appendTo( container );

          //Ocultar el botón en el que hemos hecho clic

          $( window ).off( "scroll");
          $( window ).scroll(function() {
            var el = $(".btn.btn-block.not-done");
            console.log(el);
            if (el){
              if (elementInViewport(el[ 0 ])){
                  attachPaginator(el);
              }
            }
          });
           //attachPaginator();
           attachModalInfo();
           oSelf.hide();
      })
      .fail(function() {
        alert( "error" );
        mask.hide();
      });
    }
}
//attachPaginator();
function elementInViewport(el) {
  if (!el) return;
  var top = el.offsetTop;
  var left = el.offsetLeft;
  var width = el.offsetWidth;
  var height = el.offsetHeight;

  while(el.offsetParent) {
    el = el.offsetParent;
    top += el.offsetTop;
    left += el.offsetLeft;
  }

  return (
    top >= window.pageYOffset &&
    left >= window.pageXOffset &&
    (top + height) <= (window.pageYOffset + window.innerHeight) &&
    (left + width) <= (window.pageXOffset + window.innerWidth)
  );
}
$( document ).ready(function() {
  var el = $(".btn.btn-block.not-done");
  if (el){
    if (elementInViewport(el[ 0 ])){
        attachPaginator(el);
    }
  }
});
