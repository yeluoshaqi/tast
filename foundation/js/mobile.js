// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

/** comment check **/
function comment_check(){
  var score = $(".star_list").prev().val();
  if(score == ''){
    alert('请对本次服务评分');
    return false;
  }
}

/*
添加家庭成员
*/
function family_valid(){
  var title = $("#title").val();
  var name = $("#name").val();
  var mobile = $("#mobile").val();
  var city = $("#city").val();
  var address = $("#address").val();
  if(title ==''){
    alert("请选择关系！");
    return false;
  }
  else if(name==''){
    alert("请输入您的姓名！");
    return false;
  }
  else if(name.length<2 || name.length>5){
    alert("请输入正确的姓名！");
    return false;
  }
  else if(mobile == ''){
    alert("请输入您的手机号！");
    return false;
  }else if(!isMobile(mobile)){
    alert("请输入正确的手机号！");
    return false;
  }
   else if(city == ''){
    alert("请选择您所在街道！");
    return false;
  }else if(address == ''){
    alert("请输入您的详细地址！");
    return false;
  }else if(address.length<4){
    alert("请填写正确的地址！");
    return false;
  }

} 
function member_valid(){

  var name = $("#name").val();
  var mobile = $("#mobile").val();
  var city = $("#city").val();
  if(name==''){
    alert("请输入您的姓名！");
    return false;
  }
  else if(name.length<2 || name.length>5){
    alert("请输入正确的姓名！");
    return false;
  }
  else if(mobile == ''){
    alert("请输入您的手机号！");
    return false;
  }else if(!isMobile(mobile)){
    alert("请输入正确的手机号！");
    return false;
  }
   else if(city == ''){
    alert("请选择您所在街道！");
    return false;
  }

}
//判断手机号是否正确
function isMobile(s)
{
  var patrn=/^\s*(18\d{9}|15\d{9}|17\d{9}|14\d{9}|13[0-9]\d{8})\s*$/;
  var patrn0 = /^d{11}$/;
    if(!patrn.exec(s))
    {
        return false;
    }
    return true;
} 
