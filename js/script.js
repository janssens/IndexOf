$(function() {

  $("table").on("click","a.ajax",function(event){
    event.preventDefault();
    var $a = $(this);
    var path = $a.attr("href");

    var pathFromState = History.getState().data.path;
    if (typeof(pathFromState) == "undefined"){ 
      pathFromState = "/";
    }

    fillWithDir(pathFromState+path,true);
  });

  $(document).on("click",function(){
    $("#filter").focus();
  });

  $("table thead th").on("click",function(event){
    event.preventDefault();
    sortTable($("table"),$("table thead th").index(jQuery(this)),jQuery(this).hasClass("asc"));
    jQuery(this).toggleClass("asc");
  });

  $("a[rel=imageFiles]").fancybox();
  //$("table").tablesorter();

  $("#filter").focus();
  $("#filter_value").css({opacity:0});


  $("#filter").on("keyup",function(event){
    if (event.which == 13){
      var dest = firstVisible($("table")).find("a:first").attr("href");
      window.location = dest;
      return;
    }
    filterTable($("table"),$(this).val().toLowerCase());
    $("#filter_value").html($(this).val());
    $("#filter_value").stop().animate({opacity:1},300,function(){
      $(this).animate({opacity:0},300);
    });
  });

});

window.onpopstate = function(event) {
  
  var pathFromState = History.getState().data.path; 
  if (typeof(pathFromState) == "undefined"){ 
    pathFromState = '/';
  }  
  var depthFromState = History.getState().data.depth;
  if (typeof(depthFromState) == "undefined"){ 
    depthFromState = 0;
  }
  var lastDepthFromState = History.getStateByIndex(-2).data.depth;
  if (typeof(lastDepthFromState) == "undefined"){ 
    lastDepthFromState = 0;
  }

  if (depthFromState > lastDepthFromState ){ // forward
    proccessNewPath(pathFromState);
  }else{ // backward
    fillWithDir(pathFromState,false);
    proccessNewPath(pathFromState);
  }

  $("#filter").val('').focus();
  
};

function proccessNewPath(path){
    if (typeof(path) == "undefined"){
      path = '/';
    }
    var title = "Index of: "+path;
    $(document).attr('title',title);
    $("h1").html(title);
}

function fillWithDir(dir,forward){
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
                var depth = History.getState().data.depth;
                if (typeof(depth) == "undefined"){ 
                  depth = 0;
                }
                if (forward){
                  History.pushState({path: dir, depth: depth+1}, " ", dir);
                }
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

function filterTable(element,chain){
  if (typeof(chain)!= "undefined" && chain.length > 0 ){
    element.find("tbody tr").each(function(){
      $(this).find("td:first:not([val*='"+chain+"'])").parent().hide(200);
      $(this).find("td:first[val*='"+chain+"']").parent().show(200);
    });
  }else{
    element.find("tbody tr").show();
  }

}

function firstVisible(element){
  return element.find("tbody tr:visible:first");
}