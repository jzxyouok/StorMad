<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

 <div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <form action="/admin_user/add_admin" method="post" id="add_user_submit">
        <tr>
          <th width="17%">管理员账号：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="user_name" id="user_name" onblur="check(this)" placeholder="管理员账号">
            </span></td>
          <th width="17%">真实姓名：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="true_name" id="true_name" onblur="check(this)" placeholder="真实姓名">
            </span></td>
          <th width="12%">&nbsp;</th>         
        </tr>
         <tr>
          <th>管理员密码：</th>
          <td><span class="input-sm">
            <input type="password" name="user_password" id="user_password" onblur="check(this)" placeholder="用户密码">
            </span></td>
          <th>管理员描述：</th>
          <td><textarea name="user_comment" id="user_comment" cols="" rows=""></textarea></td>
          <td colspan="3">&nbsp;</td>
        </tr>
        </form>
        <tr>
          <th>&nbsp;</th>
          <td colspan="4">
           <button class="btn-gray btn-lg" onclick="location.href='/admin_user/admin_list'">返回列表</button>&nbsp;
           <button class="btn-red btn-lg" onclick="add_user()"> 保存账户</button> 
          
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  
<script>
function check(obj) {
	var val = $(obj).val();
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		if($(obj).attr('id')=='user_name'){
			$.ajax({
				type: 'POST',
				url: '/admin_user/check_admin_user/'+val,
				dataType: 'json',
				success: function (msg) {
					if(msg){
						$(obj).parent('span').attr('class', 'input-sm input-tip-err');
						pop_up('管理员账号已存在');
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
		pop_up('请输入管理员账号');
		return false;
	}
	if(!$('#true_name').val()) {
		pop_up('请输入真实姓名');
		return false;
	}
	if(!$('#user_password').val()) {
		pop_up('请输入管理员密码');
		return false;
	}
	if(!$('#user_comment').val()) {
		pop_up('请输入管理员描述');
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '/admin_user/check_admin_user/'+username,
		dataType: 'json',
		success: function (msg) {
			if(msg){
				pop_up('管理员账号已存在');
			}else{
				$('#add_user_submit').submit();
			}
		}
	});
}
</script>