<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script src="<?php echo base_url(); ?>/js/jquery.zclip.min.js"></script>

<style>
a{cursor:pointer;}
</style>

<div class="gtarea" style="padding-left:35px;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <button class="btn-red btn-sm" id="stop_area"><i class="iF">&#xe601;</i> <b>不启用</b></button> &ensp; <button class="btn-org btn-sm" id="use_area"><i class="iF">&#xe61c;</i> <b>启用</b></button>
    </td>
  </tr>
  </table>
</div>

<div class="tabCon">
    <div id='fenyeCon'>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="3%"><span class="checkbox">
              <input type="checkbox" id="check_all">
              <i></i> </span></th>
            <th width="5%">ID </th>
            <th width="20%">广告位名称 </th>
            <th>所属站点</th>
            <th>所属页面</th>
            <th>类型</th>
            <th width="15%">规格名称</th>
            <th>全国</th>
            <th>状态</th>
            <th width="25%">操作</th>
          </tr>
        </thead>
        <tbody id="check_list">
        <?php foreach ($area as $k=>$val) {?>
          <tr>
            <td><span class="checkbox">
              <input type="checkbox" value="<?php echo $val['id']; ?>">
              <i></i> </span></td>
            <td><?php echo $val['id']; ?></td>
            <td><span ondblclick="edit_area_name(this, <?php echo $val['id']?>, '<?php echo $val['area_name']?>')"><a><?php echo $val['area_name']; ?></a></span></td>
            <td><?php echo $val['site_name']; ?></td>
            <td><b class="gray3"><?php echo $val['page_name']; ?></b></td>
            <td><?php if($val['type']==1) {?>图片<?php }elseif($val['type']==2) {?>文字<?php }?></td>
            <td><?php echo $val['size_name']; ?></td>
            <td><?php if($val['is_all']==1) {?><b class="green">是</b><?php }elseif($val['is_all']==0) {?>否<?php }?></td>
            <td id="status<?php echo $val['id']; ?>">
              <b class="<?php 
				if($val['status']==0){
					echo 'org';
				}elseif($val['status']==1){
					echo 'green';
				}
			  ?>"><?php if($val['status']==0){?>未启用<?php }elseif($val['status']==1) {?>启用<?php }?></b></td>
            <td>
              <a href="javascript:use_area(1, <?php echo $val['id']?>);">启用</a> <b class="line">|</b> <a href="javascript:use_area(0, <?php echo $val['id']?>);">不启用</a> <b class="line">|</b> <a href="/ad_area/edit_area/<?php echo $val['id']; ?>">编辑</a> <b class="line">|</b> <a href="javascript:del_area(<?php echo $val['id']; ?>)">删除</a> <b class="line">|</b>  <a href="javascript:get_area_code(<?php echo $val['id'];?>);">获取投放链接</a>
            </td>
          </tr>
        <?php }?>  
        </tbody>
      </table>
    </div>
    
      <div class="tr pdT20">
        <div class="td-3">
          <a href="/ad_area/add_area" class="btn-green btn-lg"><i class="iF iF-newadd vlm"></i> <b>新增广告位</b></a> &nbsp;
         
        </div>
        <div class="td-9">
          <div class="fanye">
            <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/js/laypage/skin/laypage.css">
            <div id="fenye" style="text-align:right">
              <div name="laypage1.3" class="laypage_main laypageskin_default" id="laypage_0">
                <?php echo $page; ?>
                <?php if($page){?>
                  <a>共&nbsp;<?php echo $cur_page; ?>/<?php echo $total_page; ?>&nbsp;页</a>
                  <input type="text" value="" id="go_page">
                  <a href="javascript:go_page('<?php echo $url; ?>', <?php echo $total_page; ?>)">GO</a>
                <?php }?>
              </div>
            </div>
          </div>
        </div>
      </div>
    
  </div>
  
<script>
$(document).ready(function() {
	var checkAll=$("#check_all");
	var checkList=$("#check_list").find("input[type=checkbox]");
    checkAll.click(function(){			 
    	if($(this).is(":checked")){
			checkList.each(function(){
				$(this).prop("checked", true);
			})
		}else{
			checkList.each(function(){
				$(this).prop("checked", false);
			})
		}
	})
	$("#stop_area").click(function(){
		var check = '';
		checkList.each(function(){
			if($(this).is(":checked")){
				use_area(0, $(this).val());
				check = 1;
			}
        })
        if(check!=1){
            pop_up('请选择不启用的广告位');
        }
	})
	$("#use_area").click(function(){
		var check = '';
		checkList.each(function(){
			if($(this).is(":checked")){
				use_area(1, $(this).val());
				check = 1;
			}
        })
        if(check!=1){
            pop_up('请选择要启用的广告位');
        }
	})
});

//修改广告位名称
function edit_area_name(obj, id, value) {
	var str = "<input type=\"text\" value=\""+value+"\" id=\"area_name"+id+"\" style=\"width:120px;height:20px;color:#666;border:1px solid #ccc;border-radius:4px;padding:0 10px;\">&nbsp;&nbsp;<button onclick=\"confirm_edit("+id+")\" style=\"width:30px;height:20px;color:#fff;background-color:#1dbb73;border-color:#0c9c62;border:0;\">确定</button>";
	$(obj).html(str);
	$(obj).removeAttr('onclick');
}
function confirm_edit(id) {
	var name = $('#area_name'+id).val();
	if(!name) {
		pop_up('请输入广告位名称');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/ad_area/edit_area_name/'+id+'/'+name,
		dataType: 'json',
		success: function (msg) {
			if(msg){
				pop_up('修改成功');
				location.reload();
			}else{
				pop_up('修改失败');
			}		
		}
	});
}

//是否启用广告位
function use_area(status, id){
	$.ajax({
		type: 'POST',
		url: '/ad_area/use_area/'+status+'/'+id,
		dataType: 'json',
		success: function (msg) {
			var res = msg['status'];
			
			if(res==0){
				$('#status'+id).children('b').html('未启用');
				$('#status'+id).children('b').attr('class', 'org');
				location.reload();
			}else if(res==1){
				$('#status'+id).children('b').html('启用');
				$('#status'+id).children('b').attr('class', 'green');
				location.reload();
			}
		}
	});
}

//删除广告位
function del_area(id) {
	layer.msg("你确定<b class='org'>删除</b>么？", {
	    time: 0 //不自动关闭
	    ,btn: ['确定', '取消']
	    ,yes: function(index){
	        layer.close(location.href = '/ad_area/del_area/'+id);
	    }
	});
}

//到指定页码
function go_page(link, num) {
	var go_page = $('#go_page').val();
	if(go_page>num){
		pop_up('没有这个页码');
		return false;
	}
	if(isNaN(go_page)) {
		pop_up('页码必须是数字');
		return false;
	}
	location.href = link + go_page;
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

function get_area_code(id) {
    $.ajax({
        type: 'GET',
        url: '/ad_area/get_code/'+id,
        dataType: 'json',
        success: function (msg) {
			var textarea = '<div style="text-align:center;font-size:16px;height:25px;"><a id="copyBtn"><strong>点击此链接复制推广代码</strong></a></div><div style="text-align:center;"><textarea style="height:80px;" id="ad_template">'+msg.ad_template+'</textarea></div>';
            var content = '<div style="width:320px;height:130px;">'+textarea+'</div><div style="text-align:center;font-size:14px;height:25px;"><strong>广告预览</strong></div><iframe width="320px" frameborder="no" src="/ad_area/view/'+id+'"></iframe>';
            layer.open({
                type: 1,
                title: '获取推广代码',
                closeBtn: 1,
                shadeClose: true,
                skin: 'layui-layer-rim',
                content: content
            });
			$('#copyBtn').zclip({
                path: "<?php echo base_url(); ?>/js/ZeroClipboard.swf",
                copy: function(){
                    return $('#ad_template').val();
      　　　 　　		}
            });
        }
    });

}

</script>
