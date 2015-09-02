var star = $("[type='star']");
if(star.length > 0){
  var star_list = '<div class="star_list" style="width:80%;float:right;font-size:24px;color:#ccc;"><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i></div>';
  var value = '';
  for(var k=0; k<star.length; k++){
    $(star[k]).after(star_list);
    $(star[k]).attr("type", "hidden");
    value = $(star[k]).val();
    if(value != 0){
      for(var j=0; j<value; j++){
        $(".star_list").eq(k).find("i").eq(j).removeClass("fa-star-o").addClass("fa-star");
      }
    }



  }

  $(".star_list").find("i").click(function(){
    var f_index = $(this).parent().index($(".star_list"));
    var index = $(this).index();
    $(".star_list").eq(f_index).prev("input").val(index+1);
    //$(star[f_index]).val(index+1);
    $(".star_list").eq(f_index).find("i").removeClass("fa-star").addClass("fa-star-o");
    for(var i=0; i<index+1; i++){
      $(".star_list").eq(f_index).find("i").eq(i).removeClass("fa-star-o").addClass("fa-star");
    }

  });
}
