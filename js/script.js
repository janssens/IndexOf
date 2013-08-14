$(function() {

  $("table").on("click","a.ajax",function(event){
    event.preventDefault();
    var $a = $(this);
    var url = $a.attr("href");
    History.pushState(null,null,url);
  });

  $("a[rel=imageFiles]").fancybox();
  //$("table").tablesorter();

});

var started = false;

window.onpopstate = function(event){
  if (started){
    fillWithDir(History.getState().hash);
    var title = "Index of: "+History.getState().hash;
    $(document).attr('title',title);
    $("h1").html(title);
  }else{
    started = true;
  }
}

function fillWithDir(dir){
  $.ajax({ 
        url : iofUrl,
        type : "post",
        data : {dir:dir},
        complete: function(jqXHR,textStatus){
          //console.log(jqXHR.responseText);
          if (textStatus=="success"){
            $("table tbody").animate({height:0,opacity:0},250,function(){
              var r = jQuery.parseJSON(jqXHR.responseText);
              if (r.type == 'content'){
                $(this).html(r.value).css({height:"auto"}).animate({opacity:1},250);
              }else{
                window.location = r.value;
              }
            });
          }
        }
    });
}