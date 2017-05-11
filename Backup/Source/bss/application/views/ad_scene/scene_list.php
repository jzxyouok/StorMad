<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/inpage.css">

    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="check_list">
    <?php foreach ($scene_class as $k=>$val) {?>
      <tr>
        <th width="3%"><span class="checkbox">
              <input type="radio" name="scene_class" value="<?php echo $val['id']?>" <?php if($val['id']==1){ ?> disabled="disabled" <?php } ?> >
              <i></i> </span></th>
        <th width="10%" valign="top"><?php echo $val['scene_name']?></th>
        <td valign="top" class="tagBox" id="scene<?php echo $val['id']?>">
        <?php foreach ($scene_name[$val['id']] as $k2=>$val2) {?>
        <label><span class=" checkbox">
                  <input name="scene_name" type="radio" value="<?php echo $val2['id']?>">
                  <i></i> </span> <b><?php echo $val2['scene_name']?></b></label>
        <?php }?>
        </td>
        <td width="30%" valign="top"><a href="/ad_scene/add_scene/0/<?php echo $val['id']?>/<?php echo $val['field_label']?>" class="green"><i class="iF iF-newadd"></i> <b>增加标签</b></a>&emsp;
            <a href="javascript:del_scene(<?php echo $val['id']?>);" class="red"><i class="iF iF-minus"></i> <b>删除标签</b></a>&emsp;
            <a href="javascript:edit_scene(<?php echo $val['id']?>);" class="org"><i class="iF iF-edit"></i> <b>修改标签</b></a>
        </td>
      </tr>
    <?php }?>  
    </table>
    <div style=" padding:20px; ">
        <button class="btn-green btn-lg" onclick="location.href='/ad_scene/scene_class'"><b>增加分类</b></button>&emsp;
        <button class="btn-green btn-lg" onclick="del_scene_class()"><b>删除分类</b></button>&emsp;
        <button class="btn-green btn-lg" onclick="edit_scene_class()"><b>修改分类</b></button>
    </div>
   
<script>   
$(document).ready(function() {
	$('#gt').addClass('tags');
});

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

function edit_scene_class() {
	var id = '';
	var checkList = $('#check_list').find('input[type=radio]');
	checkList.each(function(){
		if($(this).is(':checked')){
			id = $(this).val();
		}
	})
	
	if(id){
		location.href='/ad_scene/edit_scene_class/'+id;
	}else{
		pop_up('请选择要修改的分类');
	}
}

function del_scene_class() {
	var id = '';
	var checkList = $('#check_list').find('input[type=radio]');
	checkList.each(function(){
		if($(this).is(':checked')){
			id = $(this).val();
		}
	})
	
	if(id){
    	layer.msg("你确定删除该分类及分类下的标签么？", {
    	    time: 0 //不自动关闭
    	    ,btn: ['确定', '取消']
    	    ,yes: function(index){
    	    	layer.close(location.href = '/ad_scene/del_scene_class/'+id);
    	    }
    	});
	}else{
		pop_up('请选择要删除的分类');
	}
}

function edit_scene(fid) {
	var id = $('#scene'+fid).find('input:checked').val();
	
	if(id) {
		location.href = '/ad_scene/edit_scene/'+id+'/'+fid;
	}else {
		pop_up('请选择要修改的标签');
	}
}

function del_scene(fid) {
	var id = $('#scene'+fid).find('input:checked').val();
	
	if(id) {
		layer.msg('你确定删除么？', {
		    time: 0 //不自动关闭
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		        layer.close(location.href = '/ad_scene/del_scene/'+id);
		    }
		});
	}else {
		pop_up('请选择要删除的标签');
	}
}
</script>
