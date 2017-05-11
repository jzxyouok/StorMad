<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtCon">
  <form action="/customer/add_customer" method="post" id="customer_submit">
  <div class="gtarea">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="right" width="12%">用户群名称：</td>
    <td width="20%">
      <span class="input-sm">
        <input type="text" name="customer_name" onblur="check(this)" value="<?php echo isset($customer['customer_name'])?$customer['customer_name']:''; ?>" id="customer_name">
      </span>
    </td>
    <td align="right"></td>    
  </tr>
  </table>
  <input type="hidden" name="scene_id" value="" id="scene_id">
  <input type="hidden" name="id" value="<?php echo isset($customer['id'])?$customer['id']:''; ?>">
  </div>
  </form>
  <div class="tabCon  tags">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="check_list">
      <?php foreach ($scene_class as $k=>$val) {?>
      <tr>
        <th width="12%" align="right" valign="top"><?php echo $val['scene_name']?>：</th>
        <td valign="top" class="tagBox">
        <?php foreach ($scene_name[$val['id']] as $k2=>$val2) {?>
          <label><span class=" checkbox">
            <input name="" type="checkbox" value="<?php echo $val2['id']?>"
            <?php if (isset($customer_scene)) {
                foreach ($customer_scene as $key=>$value) { 
                    if ($value['scene_id']==$val2['id']) {?>checked="checked"<?php }
                }
            }?>>
            <i></i> </span> <b><?php echo $val2['scene_name']?></b>
          </label>
        <?php }?>
        </td>
      </tr>
      <?php }?>
    </table>
    <div style=" padding:20px; padding-bottom:0; "> <button class="btn-cyan btn-lg" onclick="add_customer()"><i class="iF  vlm">&#xe614;</i> <b>完成设置</b></button>
    </div>
  </div>
  
</div>

<script>
$(document).ready(function() {
	if($('#customer_name').val()=='默认用户群'){
		$('#customer_name').attr('readonly', 'readonly');
	}
});
            
function check(obj) {
	var val = $(obj).val();
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		if($(obj).attr('id')=='customer_name'){
			$.ajax({
				type: 'POST',
				url: '/customer/check_customer/'+val,
				dataType: 'json',
				success: function (msg) {
					if(msg){
    					if(msg['customer_name']!='<?php echo (isset($customer['customer_name']))?$customer['customer_name']:'';?>'){
    						$(obj).parent('span').attr('class', 'input-sm input-tip-err');
    						pop_up('用户群名称已存在');
    					}else{
    						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
    					}
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
            
function add_customer() {
	var checkList = $("#check_list").find("input[type=checkbox]");
	var scene_id = '';
	checkList.each(function(){
		if($(this).is(":checked")){
			scene_id += $(this).val() + ",";
		}
    })
    if (scene_id.length > 0) {
		scene_id = scene_id.substr(0,scene_id.length - 1);
    }
	$('#scene_id').val(scene_id);

	if(!$('#customer_name').val()) {
		pop_up('请输入用户群名称');
		return false;
	}

	$.ajax({
		type: 'POST',
		url: '/customer/get_customer_num/',
		dataType: 'json',
		success: function (msg) {
			var res = msg;
			
            if(res>=20){
            	<?php if(!isset($customer['id'])){?>
            		pop_up('最多可设置20个用户群');
            	<?php }else{?>
                	$.ajax({
        				type: 'POST',
        				url: '/customer/check_customer/'+$('#customer_name').val(),
        				dataType: 'json',
        				success: function (msg) {
        					if(msg){
            					if(msg['customer_name']!='<?php echo (isset($customer['customer_name']))?$customer['customer_name']:'';?>'){
            						pop_up('用户群名称已存在');
            					}else{
            						$('#customer_submit').submit();
            					}
        					}else{
        						$('#customer_submit').submit();
        					}
        				}
        			});
            	<?php }?>
            }else{
            	$.ajax({
    				type: 'POST',
    				url: '/customer/check_customer/'+$('#customer_name').val(),
    				dataType: 'json',
    				success: function (msg) {
    					if(msg){
        					if(msg['customer_name']!='<?php echo (isset($customer['customer_name']))?$customer['customer_name']:'';?>'){
        						pop_up('用户群名称已存在');
        					}else{
        						$('#customer_submit').submit();
        					}
    					}else{
    						$('#customer_submit').submit();
    					}
    				}
    			});
            }
		}
	});
	//$('#customer_submit').submit();
}
</script>
