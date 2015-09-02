function get_city(type,e){
  var url = '';
  var string = '';
  var city = '';
  var city_string = '';
  var next = parseInt(type) + 1;

  if(type == 1){
    url = 'http://pbm.vertore.cn/index.php/api/get_city/0';
    city = '';
  }else{
    var value = $(e).data("id");
    url = 'http://pbm.vertore.cn/index.php/api/get_city/'+value;
    city = $(e).data("string")+',';
  }

  $.ajax({
    url: url,
    type: 'GET',
    dataType: 'json',
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

function set_city(e)
{
  var id = $(e).data("id");
  var string = $(e).data("string");
  $('#city_selector').foundation('reveal', 'close');
  $("#city").data("id", id);
  $("#city").val(string);
  var name = $("#city").data("name");
  $("#city").after("<input type='hidden' name='"+name+"' value='"+id+"'>");
}
