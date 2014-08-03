$(function() {

  $("table").on("click","a.ajax",function(event){
    event.preventDefault();
    var $a = $(this);
    var url = $a.attr("href");
    History.pushState(null,null,url);
    jQuery(window).trigger("onpopstate");
  });

  $("table thead th").on("click",function(event){
    event.preventDefault();
    sortTable($("table"),$("table thead th").index(jQuery(this)),jQuery(this).hasClass("asc"));
    jQuery(this).toggleClass("asc");
  });

  $("a[rel=imageFiles]").fancybox();
  //$("table").tablesorter();

});

window.onpopstate = function(event){
    fillWithDir(History.getState().hash);
    var title = "Index of: "+History.getState().hash;
    $(document).attr('title',title);
    $("h1").html(title);
}

function fillWithDir(dir){
  $.ajax({ 
        url : iofUrl,
        type : "post",
        data : {dir:dir},
        complete: function(jqXHR,textStatus){
          //console.log(jqXHR.responseText);
          if (textStatus=="success"){
            var r = jQuery.parseJSON(jqXHR.responseText);
            if (r.type == 'content'){
              $("table tbody").animate({height:0,opacity:0},250,function(){
                $(this).html(r.value).css({height:"auto"}).animate({opacity:1},250);
                History.pushState(null,null,History.getStateByIndex(-2).url);
              });
            }else{
              window.location = r.value;
            }
          }
        }
    });
}

function compareTrWithIndex(e1,e2,index){
  var v1, v2;
  v1 = (e1.find("td:eq("+index+")").attr("val"));
  v2 = (e2.find("td:eq("+index+")").attr("val"));
  return v1>v2;
}


function sortTable(element,index,asc){ //tri bulle
  var lignes;
  var max = element.find("tbody tr").length;
  while (max>0){
    var tmp = 0;
    for (var i=0;i<max-1;i++){
      if (compareTrWithIndex(element.find("tbody tr:eq("+i+")"),element.find("tbody tr:eq("+(i+1)+")"),index) != asc) { //si la valeur à la position i de tableau est supérieure à la valeur à la position i+1   de tableau:
        element.find("tbody tr:eq("+i+")").insertAfter(element.find("tbody tr:eq("+(i+1)+")")); //inverserLesValeursDesPositions(tableau, i+1, i);
        tmp = i+1;
      }
    }
    max = tmp;
  }
}