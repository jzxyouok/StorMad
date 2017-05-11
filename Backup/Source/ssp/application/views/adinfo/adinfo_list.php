<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
a{cursor:pointer;}
.desc_sort{width: 0;height: 0;position: absolute;top: 15px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: #ccc transparent transparent transparent;}
.asc_sort{width: 0;height: 0;position: absolute;top: 6px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: transparent transparent #ccc transparent;}
</style>
<div class="gtCon">
  <div class="gtarea" style="padding-left:35px;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
        <div class="tr" >   
            <div class="srcbar">
              <span class="input-sm"><input type="text" value="<?php echo isset($adinfo_title)?$adinfo_title:''; ?>" id="adinfo_title" placeholder="请输入广告标题"></span>&nbsp;
              <input type="button" class="Pbtn-cyan" value="搜索" onclick="search()">
            </div>
        </div>
    </td>
  </tr>
  </table>
  </div>
  <div class="tabCon">
    
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="15%"><small>广告</small><small>标题</small> </th>
            <th>类型</th>
            <th>规格名称</th>
            <th>状态</th>
            <th width="15%">操作</th>
          </tr>
        </thead>
        <tbody id="check_list">
        <?php foreach ($adinfo as $k=>$val) {?>
          <tr>
            <td><?php echo $val['title']; ?><a href="javascript:view_ad(<?php echo $val['id']; ?>, '<?php echo $val['title']?>');"><i class="iF iF-yulan"></i> 预览</a></td>
            <td><?php echo $val['type']==1 ? '图片' : '文字'; ?></td>
            <td><?php echo $val['size_name'];?></td>
            <td id="status<?php echo $val['id']; ?>">
              <b class="<?php 
				if($val['status']==2){
					echo 'org';
				}elseif($val['status']==1){
					echo 'green';
				}
			  ?>"><?php if($val['status']==2) {?>暂停<?php }elseif($val['status']==1){?>启用<?php }?></b></td>
            <td><a onclick="use_adinfo(2, <?php echo $val['id'];?>, <?php echo $val['ad_area_id'];?>)">暂停</a><b class="line">|</b><a onclick="use_adinfo(1, <?php echo $val['id'];?>, <?php echo $val['ad_area_id'];?>)">启用</a><b class="line">|</b><a onclick="ad_detail(<?php echo $val['id'];?>)">查看</a></td>
          </tr>
        <?php }?>
        </tbody>
      </table>
   
      <div class="tr pdT20">
        <div class="td-9">
          <div class="fanye">
            
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
</div>

<script>

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

//是否启用广告
function use_adinfo(status, id, ad_area_id){
	$.ajax({
		type: 'POST',
		url: '/adinfo/use_adinfo/'+status+'/'+id+'/'+ad_area_id,
		dataType: 'json',
		success: function (msg) {
			var res = msg['status'];
			
			if(msg['error']==1)
			{
				pop_up('广告不存在，请刷新后操作');
				return false;
			}
			if(msg['error']==2)
			{
				pop_up('广告位不存在，请刷新后操作');
				return false;
			}
			
			if(res==1)
			{
				$('#status'+id).children('b').html('启用');
				$('#status'+id).children('b').attr('class', 'green');			
			}
			else if(res==2)
			{
				$('#status'+id).children('b').html('暂停');
				$('#status'+id).children('b').attr('class', 'org');
			}
		}
	});
}

//广告搜索
function search() {
	var title = $('#adinfo_title').val() || 0;

	location.href = '/adinfo/adinfo_list/'+'/'+title+'/<?php echo $cur_page; ?>';
}

//广告预览
function view_ad(id, title) {
    $.ajax({
        type: 'GET',
        url: '/adinfo/view/'+id,
        dataType: 'json',
        success: function (msg) {
			if(msg.ret==1) {
				if(msg.width==0 && msg.width==0){
					content = '<div style="width:320px;height:200px">'+msg.content+'</div>';
				}else{
            		content = '<div style="width:'+msg.width+'px;height:'+msg.height+'px">'+msg.content+'</div>';
				}
			} else {
				content = msg.content;
			}
            layer.open({
            type: 1,
            title: title,
            closeBtn: 1,
            shadeClose: true,
            skin: 'layui-layer-rim',
            content: content
            });
        }
    });

}

//查看广告详情
function ad_detail(id){
	$.ajax({
		type: 'POST',
		url: '/adinfo/check_adinfo/'+id,
		dataType: 'json',
		success: function (msg) {			
			if(msg['error']==1)
			{
				pop_up('广告不存在，请刷新后操作');
				return false;
			}
			
			location.href = '/adinfo/ad_detail/'+'/'+id;
		}
	});
}

</script>
