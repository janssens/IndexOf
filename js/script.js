$(function() {

  $("table").on("click","a.ajax",function(event){
    event.preventDefault();
    var $a = $(this);
    var url = $a.attr("href");
    History.pushState(null,null,url);
  });

});

var started = false;

window.onpopstate = function(event){
  if (started){
    fillWithDir(History.getState().hash);
  }else{
    started = true;
  }
}

function fillWithDir(dir){
  $.ajax({ 
        url : '',
        type : "post",
        data : {dir:dir},
        complete: function(jqXHR,textStatus){
          //console.log(jqXHR.responseText);
          if (textStatus=="success"){
            $("table tbody").animate({height:0,opacity:0},250,function(){
              $(this).html(jqXHR.responseText).css({height:"auto"}).html(jqXHR.responseText).animate({opacity:1},250);
            });
          }
        }
    });
}