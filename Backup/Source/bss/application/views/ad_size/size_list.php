<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
a{cursor:pointer;}
</style>

<div class="tabCon">
    <div id='fenyeCon'>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="20%">规格名称 </th>
            <th>规格类型 </th>
            <th width="15%">规格尺寸</th>
            <th>添加时间</th>
            <th>更新时间</th>
            <th>操作者</th>
            <th width="10%">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($size_name as $k=>$val) {?>
          <tr>
            <td><span ondblclick="edit_size_name(this, <?php echo $val['id']?>, '<?php echo $val['size_name']?>')"><a><?php echo $val['size_name']; ?></a></span></td>
            <td><?php if($val['type']==1) {?>图片<?php }elseif($val['type']==2) {?>文字<?php }?></td>
            <td>宽：<?php echo $val['width'].'&nbsp;px'; ?>&nbsp;&nbsp;&nbsp;&nbsp;高：<?php echo $val['height'].'&nbsp;px'; ?></td>
            <td><?php echo date('Y-m-d H:i:s', $val['add_time']); ?></td>
            <td><?php echo date('Y-m-d H:i:s', $val['update_time']); ?></b></td>
            <td><?php echo $val['true_name'].'('.$val['user_name'].')'; ?></td>
            <td>
              <a href="/ad_size/edit_size/<?php echo $val['id']; ?>">编辑</a> <b class="line">|</b> <a href="javascript:del_size(<?php echo $val['id']; ?>)">删除</a> <b class="line">
            </td>
          </tr>
        <?php }?> 
        </tbody>
      </table>
    </div>
    
      <div class="tr pdT20">
        <div class="td-3">
          <a href="/ad_size/add_size" class="btn-green btn-lg"><i class="iF iF-newadd vlm"></i> <b>新增规格</b></a> &nbsp;
         
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
//修改广告规格名称
function edit_size_name(obj, id, value) {
	var str = "<input type=\"text\" value=\""+value+"\" id=\"size_name"+id+"\" style=\"width:120px;height:20px;color:#666;border:1px solid #ccc;border-radius:4px;padding:0 10px;\">&nbsp;&nbsp;<button onclick=\"confirm_edit("+id+")\" style=\"width:30px;height:20px;color:#fff;background-color:#1dbb73;border-color:#0c9c62;border:0;\">确定</button>";
	$(obj).html(str);
	$(obj).removeAttr('onclick');
}
function confirm_edit(id) {
	var name = $('#size_name'+id).val();
	if(!name) {
		pop_up('请输入广告规格名称');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/ad_size/edit_size_name/'+id+'/'+name,
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

function del_size(id) {
	layer.msg("你确定<b class='org'>删除</b>么？", {
	    time: 0 //不自动关闭
	    ,btn: ['确定', '取消']
	    ,yes: function(index){
	        layer.close(location.href = '/ad_size/del_size/'+id);
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
</script>
