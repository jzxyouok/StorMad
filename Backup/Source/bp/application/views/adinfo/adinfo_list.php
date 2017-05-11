<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo base_url(); ?>/js/jquery.min.js"></script>
<style>
a{cursor:pointer;}
.desc_sort{width: 0;height: 0;position: absolute;top: 15px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: #ccc transparent transparent transparent;}
.asc_sort{width: 0;height: 0;position: absolute;top: 6px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: transparent transparent #ccc transparent;}
</style>

<div class="tips" style="margin-bottom:20px; padding-top:12px; padding-bottom:12px;">
 <div class="left"><strong class="gray3">注意事项：</strong></div>
 <div class="flowH"> 
 <p>1.广告数不可以超过200个。</p>
 </div>
</div>
<div class="gtCon">
  <div class="gtarea" style="padding-left:35px;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    <button class="btn-cyan btn-sm" onclick="add_adinfo()"><i class="iF">&#xe603;</i> <b>新建广告</b></button></td>
    <td>
        <div class="tr" >
            <div class="td-4">
              <div class="select left select_campaign" style="z-index:9;">
                <p class="fy"> <span><?php echo isset($campaign['campaign_name'])?$campaign['campaign_name']:'请选择推广计划';?></span> <i class="iF iF-arrdown right"></i></p>
                <input type="hidden" value='<?php echo isset($campaign['id'])?$campaign['id']:'';?>' name="select_campaign" id="select_campaign">
                <ol class="option">
                  <li>请选择</li>
                  <?php foreach ($campaign_name as $k=>$val){?>
                     <li val='<?php echo $val['id'];?>'><?php echo $val['campaign_name']; ?></li>
                  <?php }?>
                </ol>
              </div>
            </div>
        
            <div class="td-4">
              <div class="select left select_adgroup" style="z-index:9;">
                <p class="fy"> <span id="adgroup_name"><?php echo isset($adgroup['adgroup_name'])?$adgroup['adgroup_name']:'请选择推广组';?></span> <i class="iF iF-arrdown right"></i></p>
                <input type="hidden" value='<?php echo isset($adgroup['id'])?$adgroup['id']:'';?>' name="select_adgroup" id="select_adgroup">
                <ol class="option" id="adgroup">
                </ol>
              </div>
            </div>
            
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
            <th>出价(元)</th>
            <th>排名</th>
            <th>推广计划</th>
            <th>推广组</th>
            <th>用户群</th>
            <th>状态</th>
            <th width="15%">操作</th>
          </tr>
        </thead>
        <tbody id="check_list">
        <?php foreach ($adinfo as $k=>$val) {?>
          <tr>
            <td><span onclick="edit_title(this, <?php echo $val['id']?>, '<?php echo $val['title']?>')"><a><?php echo $val['title']; ?></a></span></td>
            <td><?php echo $val['type']==1 ? '图片' : '文字'; ?></td>
            <td><?php echo $val['size_name'];?></td>
            <td><span onclick="edit_price(this, <?php echo $val['id']?>, '<?php $price = sprintf("%1\$.2f", $val['price']/100); echo $price; ?>')"><a><?php $price = sprintf("%1\$.2f", $val['price']/100); echo $price; ?></a></span></td>
            <td><span id="ranking"><?php echo $val['rank']?></span></td>
            <td><?php echo $val['campaign_name']?></td>
            <td><?php echo $val['adgroup_name']; ?></td>
            <td><?php echo $val['customer_name']; ?></td>
            <td id="status<?php echo $val['id']; ?>">
              <b class="<?php 
				if($val['status']==0){
					echo 'org';
				}elseif($val['status']==1){
					echo 'org';
				}elseif($val['status']==2){
					echo 'green';
				}elseif($val['status']==3){
					echo 'org';
				}
			  ?>"><?php if($val['status']==0){?>待审核<?php }elseif($val['status']==1) {?>暂停<?php }elseif($val['status']==2){?>启用<?php }elseif($val['status']==3){?>审核不通过<?php }?></b></td>
            <td><a onclick="use_adinfo(1, <?php echo $val['id'];?>, <?php echo $val['status'];?>)">暂停</a><b class="line">|</b><a onclick="use_adinfo(2, <?php echo $val['id'];?>, <?php echo $val['status'];?>)">启用</a><b class="line">|</b><a href="javascript:view_ad(<?php echo $val['id'];?>, '<?php echo $val['title']?>');">预览</a><b class="line">|</b><a href="/adinfo/edit_adinfo/<?php echo $val['id']; ?>/<?php echo $campaign_url; ?>/<?php echo $adgroup_url; ?>">编辑</a><b class="line">|</b><a href="javascript:del_adinfo(<?php echo $val['id']; ?>, '<?php echo $val['title']; ?>')">删除</a></td>
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
$(document).ready(function() {
	
	$('.select_campaign').Gfselect({
		toValFn:false,
		valFn:function(a,b,c,d){
			check_campaign(d);
		}
	});
	$('.select_adgroup').Gfselect({
		toValFn:false,
	});
});

function check_campaign(campaign_id) {
	$.ajax({
		type: 'POST',
		url: '/adinfo/get_ajax_adgroup/'+campaign_id,
		dataType: 'json',
		success: function (msg) {
			var res = msg;
            
			$('#adgroup').empty();
			$('#select_adgroup').val('');
			$('#adgroup_name').html('请选择推广组');
			$('#adgroup').append("<li>请选择</li>");
			for(var i in res) {
				$('#adgroup').append("<li val="+res[i]['id']+">"+res[i]['adgroup_name']+"</li>");
			} 		
	        $('.select_adgroup').Gfselect({
                toValFn:false
            });
		}
	});
}

//数据排序
function report_sort(type, sort) {
	location.href = '/adinfo/adinfo_list/<?php echo $campaign_id?>/<?php echo $adgroup_id?>/<?php echo isset($adinfo_title)?$adinfo_title:0?>/<?php echo $time?>/'+type+'/'+sort+'/<?php echo $cur_page; ?>';
}

//修改广告标题
function edit_title(obj, id, value) {
	var str = "<input type=\"text\" value=\""+value+"\" id=\"ad_title"+id+"\" style=\"width:120px;height:20px;color:#666;border:1px solid #ccc;border-radius:4px;padding:0 10px;\">&nbsp;&nbsp;<button onclick=\"confirm_edit("+id+")\" style=\"width:30px;height:20px;color:#fff;background-color:#1dbb73;border-color:#0c9c62;border:0;\">确定</button>&nbsp;&nbsp;";
	$(obj).html(str);
	$(obj).removeAttr('onclick');
}
function confirm_edit(id) {
	var title = $('#ad_title'+id).val();
	if(!title) {
		pop_up('请输入广告标题');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/adinfo/edit_title/'+id+'/'+title,
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

//修改广告价格
function edit_price(obj, id, value) {
	var str = "<input type=\"text\" value=\""+value+"\" id=\"ad_price"+id+"\" style=\"width:60px;height:20px;color:#666;border:1px solid #ccc;border-radius:4px;padding:0 10px;\">&nbsp;&nbsp;<button onclick=\"confirm_price("+id+")\" style=\"width:30px;height:20px;color:#fff;background-color:#1dbb73;border-color:#0c9c62;border:0;\">确定</button>&nbsp;&nbsp;";
	$(obj).html(str);
	$(obj).removeAttr('onclick');
}

function confirm_price(id) {
	var value = $('#ad_price'+id).val();
	if(isNaN(value)) {
		pop_up('价格必须是数字');
		return false;
	}
	if(value>1000000) {
		pop_up('最多可输入1000000元');
		return false;
	}
	if(value<0.7) {
		pop_up('最小出价不低于0.7元');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/adinfo/edit_price/'+id+'/'+value,
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
function use_adinfo(status, id, old_status){
	if(old_status==0 || old_status==3){
		pop_up('广告未通过审核');
		return false;
	}
	$.ajax({
		type: 'POST',
		url: '/adinfo/use_adinfo/'+status+'/'+id,
		dataType: 'json',
		success: function (msg) {
			var res = msg['status'];
			
			if(res==1){
				$('#status'+id).children('b').html('暂停');
				$('#status'+id).children('b').attr('class', 'org');
				location.reload();
			}else if(res==2){
				$('#status'+id).children('b').html('启用');
				$('#status'+id).children('b').attr('class', 'green');
				location.reload();
			}
		}
	});
}

//广告搜索
function search() {
	var title = $('#adinfo_title').val() || 0;
	var campaign_id = $('#select_campaign').val() || 0;
	var adgroup_id = $('#select_adgroup').val() || 0;

	location.href = '/adinfo/adinfo_list/'+campaign_id+'/'+adgroup_id+'/'+title+'/0/id/desc/<?php echo $cur_page; ?>';
}

//删除广告
function del_adinfo(id, title) {
	layer.msg("你确定删除<b class='org'>"+title+"</b>这条广告么？", {
	    time: 0 //不自动关闭
	    ,btn: ['确定', '取消']
	    ,yes: function(index){
	    	$.ajax({
	    		type: 'POST',
	    		url: '/adinfo/del_adinfo/'+id,
	    		dataType: 'json',
	    		success: function (msg) {
		    		if(msg){
	    				location.reload();
	    			}else if(res==0){
	    				pop_up('删除失败');
	    			}
	    		}
	    	})
	    }
	});
}

//新增广告判断
function add_adinfo(){
	$.ajax({
		type: 'POST',
		url: '/adinfo/get_adinfo_num/',
		dataType: 'json',
		success: function (msg) {
			var res = msg;
			
            if(res>=200){
            	pop_up('广告数不可以超过200个');
        		return false;
            }else{
            	location.href='/adinfo/add_adinfo/0/<?php echo $campaign_url;?>/<?php echo $adgroup_url;?>';
            }
		}
	});
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

</script>
