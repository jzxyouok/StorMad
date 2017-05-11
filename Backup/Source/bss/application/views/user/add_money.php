<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
      
        <tr>
          <th width="6%">金额：</th>
          <form action="/user/add_money" method="post" id="add_money_submit">
          <td width="26%"><span class="input-sm">
            <input type="text" name="user_money" id="user_money" placeholder="最多输入1000000元" onblur="check(this)">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            </span></td>
          </form>
          <td width="68%"  style="padding-left:20px"><button class="btn-green btn-sm-pdlg" onclick="add_money()"> 确定</button> &nbsp; <button class="btn-gray btn-sm-pdlg" onclick="location.href='/user/user_list'"> 返回</button></td>
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
		if($(obj).attr('id')=='user_money'){
			if(isNaN($('#user_money').val())) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('金额必须是数字');
			}else if($('#user_money').val()<=0){
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('金额必须大于0');
			}else if($('#user_money').val()>1000000){
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('最多可输入1000000元');
			}else{
				$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
			}
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

function add_money() {
	if(!$('#user_money').val()) {
		pop_up('请输入金额');
		return false;
	}
	if(isNaN($('#user_money').val())) {
		pop_up('金额必须是数字');
		return false;
	}
	if($('#user_money').val()<=0) {
		pop_up('金额必须大于0');
		return false;
	}
	if($('#user_money').val()>1000000) {
		pop_up('最多可输入1000000元');
		return false;
	}

	layer.msg("你确定添加<b class='org'>"+$('#user_money').val()+"</b>元么？", {
	    time: 0 //不自动关闭
	    ,btn: ['确定', '取消']
	    ,yes: function(index){
	    	$('#add_money_submit').submit();
	    }
	});
}
</script>