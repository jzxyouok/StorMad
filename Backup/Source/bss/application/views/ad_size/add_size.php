<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <form action="/ad_size/add_size" method="post" id="size_name_submit">
        <tr>
            <th>规格名称：</th>
            <td><span class="input-sm">
            <input type="text" name="size_name" id="size_name" onblur="check(this)" value="<?php echo isset($size['size_name'])?$size['size_name']:''?>" placeholder="请输入规格名称">
            </span></td>
            <td width="68%" style="color:#aaa;padding-left:20px">(规格名称为文字则宽度、高度为0)</td>
        </tr>
        <tr>
          <th>规格类型：</th>
          <td><div class="select select_type" style="z-index:8;">
              <p class="fy"> <span><?php if(isset($size['type']) && $size['type']==1){?>图片<?php }elseif(isset($size['type']) && $size['type']==2){?>文字<?php }else{?>请选择类型<?php }?></span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden" name="type" id="type" value="<?php echo isset($size['type'])?$size['type']:'';?>"/>
              <ol class="option">
                <li>请选择</li>
                <li onclick="select_type(1)" val="1">图片</li>
                <li onclick="select_type(2)" val="2">文字</li>
              </ol>
            </div></td>
        </tr>
        <tr>
            <th>规格宽度：</th>
            <td width="32%"><span class="input-sm">
            <input type="text" name="size_width" id="size_width" onblur="check(this)" value="<?php echo isset($size['width'])?$size['width']:'0'?>" placeholder="请输入规格宽度">
            </span></td>
            <td width="68%" style="color:#aaa;padding-left:20px">单位：px</td>
        </tr>
        <tr>
            <th>规格高度：</th>
            <td width="32%"><span class="input-sm">
            <input type="text" name="size_height" id="size_height" onblur="check(this)" value="<?php echo isset($size['height'])?$size['height']:'0'?>" placeholder="请输入规格高度">
            </span></td>
            <td width="68%" style="color:#aaa;padding-left:20px">单位：px</td>
        </tr>
        <input type="hidden" name="id" value="<?php echo isset($size['id'])?$size['id']:''?>">
        </form>
        <tr>
            <th></th>
            <td width="32%"><button class="btn-green btn-sm-pdlg" onclick="add_size()"> 确定</button> &nbsp; <button class="btn-gray btn-sm-pdlg" onclick="location.href='/ad_size/size_list'"> 返回</button></td>
            <td width="68%" style="padding-left:20px"></td>
        </tr>
        
      </tbody>
    </table>
  </div>

<script>
$(document).ready(function() {
	$('.select_type').Gfselect({
		toValFn:false,
	});

	<?php if(isset($size['type']) && $size['type']==2){?>
		$('#size_width').attr('readonly', 'readonly');
		$('#size_height').attr('readonly', 'readonly');
	<?php }?>
});

function select_type(num) {
	if(num==1){
	    $('#size_width').removeAttr('readonly');
	    $('#size_height').removeAttr('readonly');
	}else{
		$('#size_width').attr('readonly', 'readonly');
		$('#size_width').val(0);
		$('#size_height').attr('readonly', 'readonly');
		$('#size_height').val(0);
	}
}

function check(obj) {
	var val = $(obj).val();
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		if($(obj).attr('id')=='size_width'){
			if(isNaN($('#size_width').val())) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('规格宽度必须是数字');
			}else{
				$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
			}
		}else if($(obj).attr('id')=='size_height'){
			if(isNaN($('#size_height').val())) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('规格高度必须是数字');
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

function add_size() {
	if(!$('#size_name').val()) {
		pop_up('请输入规格名称');
		return false;
	}
	if(!$('#size_width').val()) {
		pop_up('请输入规格宽度');
		return false;
	}
	if(isNaN($('#size_width').val())) {
		pop_up('规格宽度必须是数字');
		return false;
	}
	if(!$('#size_height').val()) {
		pop_up('请输入规格高度');
		return false;
	}
	if(isNaN($('#size_height').val())) {
		pop_up('规格高度必须是数字');
		return false;
	}
	$('#size_name_submit').submit();
}
</script>
