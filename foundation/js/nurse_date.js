/** get init time list **/
function get_init_time_list(){
  var time = ["08:00-09:00", "09:00-10:00", "10:00-11:00", "11:00-12:00", "12:00-13:00", "13:00-14:00", "14:00-15:00", "15:00-16:00", "16:00-17:00", "17:00-18:00"];

  var list = '';
  for(var i=0; i<time.length; i++){
    list += '<li onclick="set_time(this)">'+time[i]+'</li>';
  }
  return list;
}

/** make time list active **/
function set_time(e){
  var day = $("#nurse_date").find(".current").html();
  var data = '';
  if($(e).hasClass("active")){
    data = {nurse_id:nurse_id,date:year+'-'+month+'-'+day,time:$(e).html(),action:"delete"};
    $.post(url2,data, function(result){
      if(result == 1){
        $(e).removeClass("active");
      }
    });
  }else{
    data = {nurse_id:nurse_id,date:year+'-'+month+'-'+day,time:$(e).html(),action:"create"};
    $.post(url2,data, function(result){
      if(result == 1){
        $(e).addClass("active");
      }
    });
  }
}

/** get time list by click date **/
function get_time(e){
  if($(e).html() != '&nbsp;'){
    if(!$(e).hasClass("current")){
      check_date_status();
      $("#nurse_date").find("td").removeClass("current");
      $(e).addClass("current");

      var time_list = get_init_time_list();
      $("#nurse_time").html(time_list);
      var time = $("#nurse_time").find("li");
      time.each(function(){
        var time_item = $(this);
        var data = {nurse_id:nurse_id, date:year+'-'+month+'-'+$(e).html(),time:time_item.html(),action:"check"};
        $.post(url2, data, function(result){
          if(result == 1){
            time_item.addClass("active");
          }
        });
      });
    }
  }
}

/** check current date status **/
function check_date_status(){
  var current = $("#nurse_date").find(".current");
  var time_list = $("#nurse_time").find(".active");
  if(time_list.length > 0){
    if(!current.hasClass("active")){
      current.addClass("active");
    }
  }
}
