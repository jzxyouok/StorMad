<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtarea">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
    <tbody>
      <tr>
        <form action="/ad_scene/add_scene" method="post" id="scene_name_submit">         
        <td width="32%"><span class="input-sm">
          <input type="text" name="scene_name" id="scene_name" value="<?php echo isset($scene_name['scene_name'])?$scene_name['scene_name']:''; ?>" placeholder="输入标签" onblur="check(this)">
          <input type="hidden" name="id" value="<?php echo isset($scene_name['id'])?$scene_name['id']:''; ?>">
          <input type="hidden" name="fid" value="<?php echo $fid; ?>">
          <input type="hidden" name="field_label" value="<?php echo $field_label; ?>">
          </span></td>
        </form> 
        <td width="68%"  style="padding-left:20px"><button class="btn-green btn-sm-pdlg" onclick="add_scene()"> 确定</button> &nbsp; <button class="btn-gray btn-sm-pdlg" onclick="location.href='/ad_scene/scene_list'"> 返回</button></td>
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

function add_scene() {
	if(!$('#scene_name').val()) {
		pop_up('请输入标检名');
		return false;
	}
	$('#scene_name_submit').submit();
}
</script>
