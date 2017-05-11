<!DOCTYPE html>
<html lang="en" class="bgimg">
<head>
<meta charset="utf-8">
<title><?php echo $title; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/login_global.css">

<style>
.loginTop{ height:60px; background-color:#39433f; position:fixed; top:0; left:0; width:100%; line-height:60px;}
.footer{ background-color:#202020; bottom:0; width:100%; position:fixed; height:80px; line-height:80px; color:#cecece;}
.wAuto{ min-width:990px; max-width:1200px; margin:0 auto;}
.bgimg{ background:url(../images/loginbg.jpg) center center;  height:100%; background-size: cover;}

.loginCt{ display:inline-block; height:100%; line-height:100%; vertical-align:middle; }
.loginBox{ position:fixed; top:60px; bottom:80px; line-height:100%;text-align:center; width:100%; }
.loginCon{width:356px; padding:10px 50px 16px; background-color:#f8f8f8; border-radius:10px; display:inline-block; text-align:left; vertical-align:middle; min-height:320px;}

.loginline{ border-bottom:2px solid #ddd; height:40px; padding-top:20px;}
.loginline .iF{ color:#aaa; font-size:24px; width:40px; vertical-align:middle;}
.loginline input{ line-height:28px; height:28px; border:0; background:none; width:300px; vertical-align:middle; padding:0 8px;}

.loginBtn{ width:100%; border:0; border-bottom:3px solid #008f4e; border-radius:4px; background-color:#14a965; color:#fff; height:40px; line-height:40px; font-size:18px;}
.loginCon li.last{ padding-top:20px; padding-bottom:20px;}
</style>
</head>
<body>

<div class="loginTop">
 <div class="wAuto">
   <span class="left"><img src="<?php echo base_url(); ?>images/logo.png"> </span>
 </div>
</div>
<div class="loginBox">
  <div class="loginCon">
  <form action="/login/admin_login" method="post" id="user_login">
  <ul>
    <li><div class="loginline"><i class="iF">&#xe61f;</i> <input name="user_name" id="username" type="text" value="<?php echo ($this->input->cookie('user_name'))?$this->input->cookie('user_name'):''; ?>" placeholder="请输入管理员账号"></div></li>
    <li><div class="loginline"><i class="iF">&#xe61e;</i> <input name="password" type="password" value="<?php echo ($this->input->cookie('password'))?$this->input->cookie('password'):''; ?>" placeholder="请输入密码" id="password"></div></li>
    <li><div class="loginline" style="width:200px; display:inline-block;"><i class="iF">&#xe620;</i> <input style="width:136px" name="code" id="code" type="text" placeholder="请输入验证码"></div>&emsp;<div style="float:right;padding-top:20px;"><img id="captcha_img" src="/login/get_code"></div></li>
    <li class="last"><label class="gray9"> <input type="checkbox" name="record" class="vlm" id="record" <?php if($this->input->cookie('user_name')){?>checked="checked"<?php }?>>   记住密码</label> </li>
  </ul>
  </form>
  <input type="button" value="登 录" class="loginBtn" onclick="login()">
  <p style=" line-height:24px; padding-top:8px;">注意事项：<b style="color:#ff6817;">请正确输入管理员账号、密码和验证码</b></p>
  </div> <i class="loginCt vlm"></i>
</div>
<div class="footer  txtCt">
 copyright@飞猪系统版权所有
</div>

<script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>js/layer/layer.js"></script>
<script>
$("#captcha_img").click(function() {
    $("#captcha_img").attr("src", '/login/get_code?'+Math.random());
});

$('#record').click(function(){
	if($(this).attr('checked')){
		$(this).removeAttr('checked');
	}else{
		$(this).attr('checked', 'checked');
    }
});

function login() {
	var username = $('#username').val();
	var password = $('#password').val();
	var code = $('#code').val();
	if($('#record').attr('checked')){
		$('#record').val('1');
	}
	if(!username){
	    layer.tips('管理员账号不能为空', '#username', {
		    tips: [1, '#ff8400']
		});
		return false;
	}
	if(!password){
	    layer.tips('密码不能为空', '#password', {
		    tips: [1, '#ff8400']
		});
		return false;
	}
	if(!code){
	    layer.tips('验证码不能为空', '#code', {
		    tips: [1, '#ff8400']
		});
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '/login/check_login/'+username+'/'+password+'/'+code,
		dataType: 'json',
		success: function (msg) {
			if(msg==1){
				layer.tips('该账号不存在或已被停用', '#username', {
				    tips: [1, '#ff8400']
				});
			}else if(msg==2){
				layer.tips('密码输入不正确', '#password', {
				    tips: [1, '#ff8400']
				});
			}else if(msg==3){
				layer.tips('验证码不正确', '#code', {
				    tips: [1, '#ff8400']
				});
			}else{
				$('#user_login').submit();
			}
		}
	});	
}
</script>

</body>
</html>
