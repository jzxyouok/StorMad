<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/area.css">

<script src="<?php echo base_url(); ?>/js/jquery.zclip.min.js"></script>
<script src="<?php echo base_url(); ?>/js/Popt.js"></script>
<script src="<?php echo base_url(); ?>/js/cityJson.js"></script>
<script src="<?php echo base_url(); ?>/js/citySet.js"></script>
<script src="<?php echo base_url(); ?>/js/jquery.min.js"></script>

<div class="gtCon">
  <div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <form action="/ad_area/add_area" method="post" id="add_area_submit">
        <tr>
          <th width="17%">广告位名称：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="area_name" id="area_name" onblur="check(this)" value="<?php echo isset($area['area_name'])?$area['area_name']:'';?>" placeholder="广告位名称">
            </span></td>
          <th width="17%">站点名称：</th>
          <td width="27%"><span class="input-sm">
            <input type="text" name="site_name" id="site_name" onblur="check(this)" value="<?php echo isset($area['site_name'])?$area['site_name']:'';?>" placeholder="站点名称">
            </span></td>
          <th width="12%">&nbsp;</th>         
        </tr>
         <tr>
          <th>页面名称：</th>
          <td><span class="input-sm">
            <input type="text" name="page_name" id="page_name" onblur="check(this)" value="<?php echo isset($area['page_name'])?$area['page_name']:'';?>" placeholder="页面名称">
            </span></td>
          <th>广告位描述：</th>
          <td><textarea rows="" cols="" name="comment" id="comment"><?php echo isset($area['comment'])?$area['comment']:'';?></textarea></td>
          <th>&nbsp;</th>         
        </tr>
        <tr>
          <th>选择广告位类型：</th>
          <td><div class="select select_type" style="z-index:9;">
              <p class="fy"> <span><?php if(isset($area['type']) && $area['type']==1){?>图片<?php }elseif(isset($area['type']) && $area['type']==2){?>文字<?php }else{?>请选择类型<?php }?></span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden" name="type" id="type" value="<?php echo isset($area['type'])?$area['type']:'';?>"/>
              <ol class="option">
                <li>请选择</li>
                <li onclick="select_type(1)" val="1">图片</li>
                <li onclick="select_type(2)" val="2">文字</li>
              </ol>
            </div></td>
          <th>选择广告位规格：</th>
          <td><div class="select select_size" style="z-index:8;">
              <p class="fy" onclick="select_size()"> <span id="size_name"><?php echo (isset($area['size_name']) && $area['size_name'])?$area['size_name']:'请选择规格';?></span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden" name="size_id"  id="size_id" value="<?php echo isset($area['size_id'])?$area['size_id']:''?>"/>
              <ol class="option"  id="area_size">
              </ol>
            </div></td>
        </tr>
        <tr>
          <th>选择所属渠道：</th>
          <td><div class="select select_type" style="z-index:8;">
              <p class="fy"> <span><?php echo (isset($area['distribution_name']) && $area['distribution_name'])?$area['distribution_name']:'请选择渠道';?></span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden" name="channel_id" id="channel_id" value="<?php echo isset($area['channel_id'])?$area['channel_id']:''?>"/>
              <?php if(!isset($area['channel_id'])){ ?>
              <ol class="option">
                <?php foreach($channel as $k=>$val) { ?>
                <li val="<?php echo $val['id']; ?>"><?php echo $val['distribution_name']; ?></li>
                <?php } ?>
              </ol>
              <?php } ?>
            </div></td>
            <th>是否全量渠道：</th>
            <td>
              <span class="checkbox"><input type="radio" name="is_all" value="0" <?php if(!isset($area['is_all']) || $area['is_all']==0){ ?> checked="checked" <?php } ?> ><i></i></span><b>否</b>
              <span class="checkbox"><input type="radio" name="is_all" value="1" <?php if(isset($area['is_all']) && $area['is_all']==1){ ?> checked="checked" <?php } ?> ><i></i></span><b>是</b>
            </td>
        </tr>
        <tr>
          <th>选择定位信息：</th>
          <td colspan="3">
            <div class="tabCon  tags" style="padding-top:0;">
            	<div id="addef">
					<span id="area">添加地区</span>
                    <?php if(isset($val_region)>0){foreach($val_region as $k=>$val){ ?>
                    <div class="region area_region">
                        <input id="ciy" type="hidden" value="<?php echo $val;?>" name="area[]" />
                        <span class="ciy"><?php echo $show_region[$k];?></span>
                        <div id="cori" class="cori" onclick="colqu(this)">x</div>
                    </div>
                    <?php }} ?>
            	</div>
            </div>
          </td>
        </tr>
        <tr>
          <th>选择广告位的场景标签：</th>
          <td colspan="3">
            <div class="tabCon  tags">
              <table width="100%" border="0" cellpadding="0" cellspacing="0" id="check_list">
                <?php foreach ($scene_class as $k=>$val) {?>
                  <tr>
                    <th width="12%" align="right" valign="top"><?php echo $val['scene_name']?>：</th>
                    <td valign="top" class="tagBox">
                    <?php foreach ($scene_name[$val['id']] as $k2=>$val2) {?>
                      <label><span class=" checkbox">
                        <input name="" type="checkbox" value="<?php echo $val2['id']?>"
                        <?php if (isset($area_scene)) {
                            foreach ($area_scene as $key=>$value) { 
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
            </div>
          </td>
        </tr>
        <input type="hidden" name="scene_id" value="" id="scene_id">
        <input type="hidden" name="id" value="<?php echo isset($area['id'])?$area['id']:'';?>">
        </form>
        <tr>
          <th>&nbsp;</th>
          <td colspan="4">
            <button class="btn-gray btn-lg" onclick="location.href='/ad_area/area_list'">返回列表</button>&nbsp;
            <button class="btn-red btn-lg" onclick="add_area()"> 保存广告位</button> 
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
$(document).ready(function() {
	$('.select_type').Gfselect({
		toValFn:false,
	});

	<?php if(isset($area['type'])){?>
		default_type(<?php echo $area['type'];?>);
	<?php }?>
});

$("#area").click(function (e) {
	SelCity(this,e);
});

function default_type(type) {
	$.ajax({
		type: 'POST',
		url: '/ad_size/find_type_size/'+type,
		dataType: 'json',
		success: function (msg) {
			var res = msg;
            
			$('#area_size').empty();
			$('#size_id').val('<?php echo isset($area['size_id'])?$area['size_id']:'';?>');
			$('#size_name').html('<?php echo isset($area['size_name'])?$area['size_name']:'请选择规格';?>');
			$('#area_size').append("<li>请选择</li>");
			for(var i in res) {
				$('#area_size').append("<li val="+res[i]['id']+">"+res[i]['size_name']+"</li>");
			}
			$('.select_size').Gfselect({
				toValFn:false,
			});	
		}
	});
}
function select_type(type) {
	$.ajax({
		type: 'POST',
		url: '/ad_size/find_type_size/'+type,
		dataType: 'json',
		success: function (msg) {
			var res = msg;
            
			$('#area_size').empty();
			$('#size_id').val('');
			$('#size_name').html('请选择规格');
			$('#area_size').append("<li>请选择</li>");
			for(var i in res) {
				$('#area_size').append("<li val="+res[i]['id']+">"+res[i]['size_name']+"</li>");
			}
			$('.select_size').Gfselect({
				toValFn:false,
			});	
		}
	});
}

function select_size(){
	if(!$('#type').val()){
		pop_up('请先选择广告位类型');
	}
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
	    closeBtn: 1,
	    shadeClose: true,
	    skin: 'layui-layer-rim',
	    content: '<div style="font-size:15px;font-weight:900;padding:15px; ">'+prompt+'</div>'
	});
}

function add_area() {
	if(!$('#area_name').val()) {
		pop_up('请输入广告位名称');
		return false;
	}
	if(!$('#site_name').val()) {
		pop_up('请输入站点名称');
		return false;
	}
	if(!$('#page_name').val()) {
		pop_up('请输入页面名称');
		return false;
	}
	if(!$('#size_id').val()) {
		pop_up('请选择广告位规格');
		return false;
	}
	if(!$('#type').val()) {
		pop_up('请选择广告位类型');
		return false;
	}
	if(!$('#channel_id').val()) {
		pop_up('请选择所属渠道');
		return false;
	}
	if(!$('#comment').val()) {
		pop_up('请输入广告位描述');
		return false;
	}
	
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
	
	$('#add_area_submit').submit();
}
</script>