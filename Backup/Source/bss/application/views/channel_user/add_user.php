<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

 <div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <form action="/channel_user/add_user" method="post" id="add_user_submit">
        <tr>
          <th width="17%">用户账号：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="user_name" id="user_name" placeholder="用户账号" onblur="check(this)">
            </span></td>
          <th width="17%">渠道名称：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="distribution_name" id="distribution_name" placeholder="渠道名称" onblur="check(this)">
            </span></td>
          <th width="12%">&nbsp;</th>         
        </tr>
         <tr>
          <th>用户密码：</th>
          <td><span class="input-sm">
            <input type="password" name="password" id="password" placeholder="用户密码" onblur="check(this)">
            </span></td>
          <th width="17%">渠道号：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="distribution_id" id="distribution_id" readonly="readonly" value="<?php echo $distribution_id; ?>">
            </span></td>
          <th>&nbsp;</th>         
        </tr>
        <tr>
          <th>渠道描述：</th>
          <td><textarea name="comment" id="comment" cols="" rows=""></textarea></td>
          <td colspan="3">&nbsp;</td>
        </tr>
        </form>
        <tr>
          <th>&nbsp;</th>
          <td colspan="4">
           <button class="btn-gray btn-lg" onclick="location.href='/channel_user/user_list'">返回列表</button>&nbsp;
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
				url: '/channel_user/check_user/'+val,
				dataType: 'json',
				success: function (msg) {
					if(msg){
						$(obj).parent('span').attr('class', 'input-sm input-tip-err');
						pop_up('用户名已存在');
					}else{
						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
					}
				}
			});
		}
		else if(($(obj).attr('id')=='distribution_name'))
		{
			$.ajax({
				type: 'POST',
				url: '/channel_user/check_distribution_name/'+val,
				dataType: 'json',
				success: function (msg) {
					if(msg){
						$(obj).parent('span').attr('class', 'input-sm input-tip-err');
						pop_up('渠道名称已存在');
					}else{
						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
					}
				}
			});	
		}
		else
		{
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
	if(!$('#distribution_name').val()) {
		pop_up('请输入渠道名称');
		return false;
	}
	if(!$('#password').val()) {
		pop_up('请输入用户密码');
		return false;
	}
	if(!$('#comment').val()) {
		pop_up('请输入渠道描述');
		return false;
	}
	if(!$('#distribution_id').val()) {
		pop_up('请生成渠道号');
		return false;
	}

	$.ajax({
		type: 'POST',
		url: '/channel_user/check_user/'+username,
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