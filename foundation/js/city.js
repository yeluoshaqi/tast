/**
 * make input[type="city"] to city selector
 * @author chen
 */

/** get input[type="city"] **/
var city = $("[type='city']");
city.attr("readonly", "readonly");
var input_name = city.attr("name");
city.removeAttr("name");
//var input_name = city.data("name");


/** add new input[type="hidden"] **/
city.after("<input id='city_copy' type='hidden' name='"+input_name+"' value=''>");


/** selector **/
var box = '<div id="city_box" style="position:absolute;left:0;right:0;margin:0 auto;width:80%;top:100px;display:block;opacity:1;background:#fff;z-index:99;padding:20px;border-radius:3px;"><span style="position:absolute;top:20px;right:20px;" onclick="close_city_box()">X</span><h4>请选择城市</h4><div id="city_content"></div></div>';
var box_bg = '<div id="city_box_bg" style="position:fixed;bottom:0;right:0;z-index:98;width:100%;height:100%;background:rgba(0, 0, 0, 0.45);"></div>';


/** add click event to input[type="city"] **/
city.click(function(){
  $("body").append(box_bg);
  $("body").append(box);
  get_city(1,0);
});



/*************** set city string *******************/
/** set class(".city_value") to city string **/
var city_value = $(".city_value");
var value_num = '';
if(city_value.length > 0){
  for(var i=0; i<city_value.length; i++){
    set_city_name(i);
  }
}




/**************** function ************************/
/** close city box event **/
function close_city_box(){
  $("#city_box_bg").remove();
  $("#city_box").remove();
}


/** get city string by city number **/
function set_city_name(i){
  value_num = $(city_value[i]).html();
  $.ajax({
    url: 'http://www.ipeibama.com/index.php/api/get_city_name/'+value_num,
    type: 'GET',
    global: false,
    success: function(data){
      $(city_value[i]).html(data);
    }
  });
}


/** get city box **/
function get_city(type,e){
  var url = '';
  var string = '';
  var city = '';
  var city_string = '';
  var next = parseInt(type) + 1;

  if(type == 1){
    url = 'http://jjyl.edaixi.com/index.php/api/get_city/0';
    city = '';
  }else{
    var value = $(e).data("id");
    url = 'http://jjyl.edaixi.com/index.php/api/get_city/'+value;
    city = $(e).data("string")+',';
  }

  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
    global: false,
    success: function(data){

      for(var i=0; i<data.length; i++){
        city_string = city + data[i].name;
        if(type == 3){
          string += '<li onclick="set_city(this)" data-string="'+city_string+'" data-id="'+data[i].id+'">'+data[i].name+'</li>';
        }else{
          string += '<li onclick="get_city('+next+',this)" data-string="'+city_string+'" data-id="'+data[i].id+'">'+data[i].name+'</li>';
        }
      }
      $("#city_content").html(string);
    }
  });
}


/** set city value to new input **/
function set_city(e)
{
  var id = $(e).data("id");
  var string = $(e).data("string");
  $("#city_box_bg").remove();
  $("#city_box").remove();

  city.data("id", id);
  city.val(string);
  $("#city_copy").val(id);
}
