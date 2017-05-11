<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.file-box{ position:relative;width:360px}
.file{ position:absolute; top:0; right:276px; height:28px; filter:alpha(opacity:0);opacity: 0;width:83px }
</style>


 <div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <form action="/user/add_user" method="post" id="add_user_submit" enctype="multipart/form-data">
        <tr>
          <th width="17%">用户账号：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="user_name" id="user_name" placeholder="用户账号" onblur="check(this)">
            </span></td>
          <th width="17%">真实姓名：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="true_name" id="true_name" placeholder="真实姓名" onblur="check(this)">
            </span></td>
          <th width="12%">&nbsp;</th>         
        </tr>
         <tr>
          <th>用户密码：</th>
          <td><span class="input-sm">
            <input type="password" name="user_password" id="user_password" placeholder="用户密码" onblur="check(this)">
            </span></td>
          <th>选择用户类型：</th>
          <td><div class="select select_user" style="z-index:8;">
              <p class="fy"> <span>请选择类型</span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden" name="user_type" id="user_type" value="">
              <ol class="option">
                <li>请选择</li>
                <li val="1">企业</li>
                <li val="2">个人</li>
                <li val="3">广告代理商</li>
                <li val="4">OEM</li>
              </ol>
            </div></td>
          <th>&nbsp;</th>         
        </tr>
        <tr>
          <th>用户LOGO：</th>
          <td><span class="input-sm">
          	<input type="text" name="logo" value="" id='logo'>
            </span></td>
          <td colspan="3">
          <div class="td-4" id="file_button">
          <div class="file-box">
          	<input type="button" class="btn-cyan btn-sm-pdlg left" value="选择">
            <input type="file" name="logo_file" accept="image/*" class="file" id="fileField" size="28" onchange="document.getElementById('logo').value=this.value" />
            <span style="color:#aaa;">请上传大小不高于2M,宽260px,高60px的jpg/png格式图片</span>
          </div>
          </div>
            </td>
        </tr>
        <tr>
          <th>用户描述：</th>
          <td><textarea name="user_comment" id="user_comment" cols="" rows=""></textarea></td>
          <td colspan="3">&nbsp;</td>
        </tr>
        </form>
        <tr>
          <th>&nbsp;</th>
          <td colspan="4">
           <button class="btn-gray btn-lg" onclick="location.href='/user/user_list'">返回列表</button>&nbsp;
           <button class="btn-red btn-lg" onclick="add_user()"> 保存用户</button> 
          
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  
<script>
$(document).ready(function() {
	$('.select_user').Gfselect({
		toValFn:false,
	});
});

function check(obj) {
	var val = $(obj).val();
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		if($(obj).attr('id')=='user_name'){
			$.ajax({
				type: 'POST',
				url: '/user/check_user/'+val,
				dataType: 'json',
				success: function (msg) {
					if(msg){
						$(obj).parent('span').attr('class', 'input-sm input-tip-err');
						pop_up('用户账号已存在');
					}else{
						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
					}
				}
			});
		}else{
			$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
		}
	}
}

function pop_up(prompt) {
	layer.open({
	    type: 1,
	    title: false,
	    closeBtn: 1,
	    shadeClose: true,
	    skin: 'layui-layer-rim',
	    content: '<div style="font-size:15px;font-weight:900;padding:15px; ">'+prompt+'</div>'
	});
}

function add_user() {
	var username = $('#user_name').val();
	if(!username) {
		pop_up('请输入用户账号');
		return false;
	}
	if(!$('#true_name').val()) {
		pop_up('请输入真实姓名');
		return false;
	}
	if(!$('#user_password').val()) {
		pop_up('请输入用户密码');
		return false;
	}
	if(!$('#user_type').val()) {
		pop_up('请选择用户类型');
		return false;
	}
	if(!$('#user_comment').val()) {
		pop_up('请输入用户描述');
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '/user/check_user/'+username,
		dataType: 'json',
		success: function (msg) {
			if(msg){
				pop_up('用户账号已存在');
			}else{
				$('#add_user_submit').submit();
			}
		}
	});
}
</script>