// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();
push_noty();

function reset_form(e){
  var form = $(e).parents("form");
  var input = form.find(":input[type!='submit']");
  var select = form.find("select");
  var radio = form.find("radio");

  input.each(function(){
    $(this).val('');
  });

  select.each(function(){
    $(this).val(0);
  });

  radio.each(function(){
    $(this).val(0);
  });

  return false;
}

function all_check(e){
  if(e.hasChildNodes()){
    $(".select_check").html('');
  }else{
    $(".select_check").html("<i class='fa fa-check'></i>");
  }
  action_check();
}

function select_check(e){
  if(e.hasChildNodes()){
    $(e).html('');
  }else{
    $(e).html("<i class='fa fa-check'></i>");
  }
  action_check();
}

function action_check(){
  check = $(".select_item").find("i");
  if(check.length){
    $("#more_action_button").removeAttr('disabled');
  }else{
    $("#more_action_button").attr("disabled", "disabled");
  }
}

function more_delete(e)
{
  var url = $(e).data("href");
  var check = $(".select_item").find("i");
  var ids = new Array();
  check.each(function(index){
    ids[index] = $(this).parents("td").data("id");
  });
  $.post(url, {ids:ids}, function(data){
    if(data){
      check.each(function(){
        $(this).parents("tr").remove();
      });
    }
    $('#delete_check').foundation('reveal', 'close');
  });
}

function push_noty(){
  // $.post('http://www.ipeibama.com/index.php/admin/check_new', function(data) {
  //   if(data == 1){
  //     var n = noty({
  //       layout: 'topRight',
  //       theme: 'relax',
  //       type: 'alert',
  //       text: '您有新的订单！',
  //       animation: {
  //         open: 'animated bounceInRight', // Animate.css class names
  //         close: 'animated bounceOutRight', // Animate.css class names
  //         easing: 'swing', // easing
  //         speed: 500 // opening & closing animation speed
  //       },
  //       timeout: 1000,
  //       closeWith: ['click']
  //     });
  //   }
  //   setTimeout(push_noty,5000);
  // });
}
