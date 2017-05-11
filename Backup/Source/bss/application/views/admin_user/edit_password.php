<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
      
        <tr>
          <th width="6%">请输入密码：</th>
          <form action="edit_password" method="post" id="edit_password_submit">
          <td width="26%"><span class="input-sm">
            <input type="password" name="user_password" onblur="check(this)" placeholder="输入密码" id="user_password">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            </span></td>
          </form> 
          <td width="68%"  style="padding-left:20px"><button class="btn-green btn-sm-pdlg" onclick="edit_password()"> 确定</button> </td>
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
		$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
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

function edit_password() {
	if(!$('#user_password').val()) {
		pop_up('请输入密码');
		return false;
	}
	$('#edit_password_submit').submit();
}
</script>