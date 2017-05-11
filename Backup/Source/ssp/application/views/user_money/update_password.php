<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtCon">
  <form action="/user_money/update_password" method="post" id="update_password_submit">
  <div class=" addnew box">  
    <div class="tr">
      <div class="td-2 txtGt">原密码：</div>
      <div class="td-4" style="line-height: 14px;">
         <span class="input-sm"><input type="password" name="old_password" value="" onblur="check_old_password(this)" placeholder="请输入原密码" id="old_password"></span>
      </div>      
    </div>
    <div class="tr">
      <div class="td-2 txtGt">新密码：</div>
      <div class="td-4" style="line-height: 14px;"> <span class="input-sm"><input type="password" name="new_password" value="" onblur="check(this)" placeholder="请输入新密码" id="new_password"></span></div>
    </div>
    <div class="tr">
      <div class="td-2 txtGt">确定密码：</div>
      <div class="td-4" style="line-height: 14px;"> <span class="input-sm"><input type="password" name="password" value="" placeholder="请确认密码" onblur="check(this)" id="password"></span></div>
    </div>
  </div>
  </form>
  <div class="box" style="padding:20px 0; background-color:#eee;"><div class="tr">
      <div class="td-2">&nbsp;</div>
      <div class="td-10">
        <button class="btn-cyan btn-lg-pdlg" onclick="update_password()">确定</button>
      </div>
    </div></div>
</div>

<script>
function check_old_password(obj) {
	var val = $(obj).val();
	var old_password = $('#old_password').val();
	if(!$('#old_password').val()) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
		pop_up('请输入原密码');
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '/user_money/check_password/'+old_password,
		dataType: 'json',
		success: function (msg) {
			if(msg==0){
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('原密码有误');
		    }else if(msg==1){
		    	$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
			}
		}
	});
}

function check(obj) {
	var val = $(obj).val();
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
	}
}

function pop_up(prompt) {
	layer.open({
	    type: 1,
	    title: false,
	    closeBtn: 0,
	    shadeClose: true,
	    skin: 'layui-layer-rim',
	    content: '<div style="font-size:15px;font-weight:900;padding:15px; ">'+prompt+'</div>'
	});
}

function update_password() {
	var old_password = $('#old_password').val();
	if(!$('#old_password').val()) {
		pop_up('请输入原密码');
		return false;
	}
	
	if(!$('#new_password').val()) {
		pop_up('请输入新密码');
		return false;
	}
	if(!$('#password').val()) {
		pop_up('请确认密码');
		return false;
	}
	if($('#new_password').val() != $('#password').val()) {
		pop_up('输入密码有误');
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '/user_money/check_password/'+old_password,
		dataType: 'json',
		success: function (msg) {
			if(msg==0){
				pop_up('原密码有误');
		    }else if(msg==1){
		    	$('#update_password_submit').submit();
			}
		}
	});
}
</script>
