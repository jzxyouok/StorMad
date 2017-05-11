<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
a{cursor:pointer;}
.desc_sort{width: 0;height: 0;position: absolute;top: 15px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: #ccc transparent transparent transparent;}
.desc_sort_new{width: 0;height: 0;position: absolute;top: 15px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: #1dbb73 transparent transparent transparent;}
.asc_sort{width: 0;height: 0;position: absolute;top: 6px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: transparent transparent #ccc transparent;}
.asc_sort_new{width: 0;height: 0;position: absolute;top: 6px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: transparent transparent #1dbb73 transparent;}
</style>

<div class="tips" style="margin-bottom:20px; padding-top:12px; padding-bottom:12px;">
 <div class="left"><strong class="gray3">重要提示：</strong></div>
 <div class="flowH"> 
  <p>1、推广计划是为了方便您进行广告归类和投放条件设定：推广计划修改后，所有该推广计划下的广告投放条件将随之更改。 </p>
  <p>2、推广计划创建后只可修改，不可删除。</p>
  <p>3、最多可设置10个推广计划。</p>
 </div>
</div>
<div class="gtCon">
  <div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td><button class="btn-red btn-sm" id="stop_campaign"><i class="iF">&#xe61a;</i> <b>暂停推广</b></button> &ensp; <button class="btn-org btn-sm" id="join_campaign"><i class="iF">&#xe61b;</i> <b>参与推广</b></button> &ensp; <button class="btn-cyan btn-sm" onclick="add_campaign()"><i class="iF">&#xe603;</i> <b>新建推广计划</b></button></td>
        <td><div class="select select_campaign" style="z-index:8;">
                  <p class="fy"> <span><?php if($time==0){echo '今天'; }elseif($time==1){echo '昨天'; }elseif($time==7){ echo '最近7天'; }elseif($time==30){ echo '最近30天'; }else{ echo '统计时间搜索'; }?></span> <i class="iF iF-arrdown right"></i></p>
                  <input  type="hidden"  value=""/>
                  <ol class="option">
                    <li>请选择</li>
                    <li val="0">今天</li>
                    <li val="1">昨天</li>
                    <li val="7">最近7天</li>
                    <li val="30">最近30天</li>
                  </ol>
                </div></td>
      </tr>
    </table>
  </div>
  <div class="tabCon">
    
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="2%"><span class="checkbox">
              <input type="checkbox" id="check_all">
              <i></i> </span></th>
            <th width="12%">推广计划</th>
            <th>日限额(元)</th>
            <th style="position: relative; ">花费&nbsp;<?php if($order_field=='cost' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($cost_sort) && $cost_sort=='desc'){?>_new<?php }?>" onclick="report_sort('cost', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($cost_sort) && $cost_sort=='asc'){?>_new<?php }?>" onclick="report_sort('cost', 'desc')"></i><?php }?></th>
            <th width="10%" style="position: relative; ">平均点击花费&nbsp;<?php if($order_field=='average_cost' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($average_cost_sort) && $average_cost_sort=='desc'){?>_new<?php }?>" onclick="report_sort('average_cost', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($average_cost_sort) && $average_cost_sort=='asc'){?>_new<?php }?>" onclick="report_sort('average_cost', 'desc')"></i><?php }?></th>
            <th style="position: relative; ">展现量&nbsp;<?php if($order_field=='impressions' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($impressions_sort) && $impressions_sort=='desc'){?>_new<?php }?>" onclick="report_sort('impressions', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($impressions_sort) && $impressions_sort=='asc'){?>_new<?php }?>" onclick="report_sort('impressions', 'desc')"></i><?php }?></th>
            <th style="position: relative; ">点击量&nbsp;<?php if($order_field=='click' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($click_sort) && $click_sort=='desc'){?>_new<?php }?>" onclick="report_sort('click', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($click_sort) && $click_sort=='asc'){?>_new<?php }?>" onclick="report_sort('click', 'desc')"></i><?php }?></th>
            <th style="position: relative; ">点击率&nbsp;<?php if($order_field=='ctr' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($ctr_sort) && $ctr_sort=='desc'){?>_new<?php }?>" onclick="report_sort('ctr', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($ctr_sort) && $ctr_sort=='asc'){?>_new<?php }?>" onclick="report_sort('ctr', 'desc')"></i><?php }?></th>
            <th>推广组个数</th>
            <th>广告条数</th>
            <th>状态</th>
            <th width="7%">操作</th>
          </tr>
        </thead>
        <tbody id="check_list">
        <?php foreach ($campaign as $k=>$val) {?>
          <tr>
            <td><span class="checkbox">
              <input type="checkbox" value="<?php echo $val['id']?>">
              <i></i> </span></td>
            <td><span onclick="location.href='/adgroup/adgroup_list/<?php echo $val['id']?>/1/id/desc/<?php echo $cur_page; ?>'"><a><?php echo $val['campaign_name']?></a></span></td>
            <td>
            <?php if($val['day_sum']){?>
              <span onclick="edit_day_sum(this, <?php echo $val['id']?>, '<?php $sum = sprintf("%1\$.2f", $val['day_sum']/100); echo $sum; ?>')"><a><?php $sum = sprintf("%1\$.2f", $val['day_sum']/100); echo $sum; ?></a></span>
            <?php }else{?>
              <span>不限限额</span>
            <?php }?>
            </td>
            <td><span class="org"><?php echo $val['cost']; ?></span>元</td>
            <td><span class="org"><?php echo $val['average_cost']; ?></span>元</td>
            <td><?php echo $val['impressions']; ?></td>
            <td><?php echo $val['click']; ?></td>
            <td><?php echo $val['ctr']; ?></td>
            <td><span onclick="find_adgroup(<?php echo $val['id']?>)"><a><?php echo $val['adgroup_count']; ?></a></span></td>
            <td><span onclick="find_adinfo(<?php echo $val['id']?>)"><a><?php echo $val['adinfo_count']; ?></a></span></td>
            <td id="status<?php echo $val['id']?>">
              <b class="<?php 
				if($val['status']==1)
				{
					echo 'green';
				}
				elseif($val['status']==0)
				{
					echo 'org';
				}
			  ?>"><?php if($val['status']==1) {?>推广中<?php }elseif($val['status']==0) {?>未推广<?php }?></b></td>
            <td>
              <a href="/campaign/edit_campaign/<?php echo $val['id']?>">编辑</a> 
            </td>
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
			if(!d){
				d = -1;
			}
			search_campaign(d);
		}
	});
	
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
	$("#join_campaign").click(function(){
		var check = '';
		checkList.each(function(){
			if($(this).is(":checked")){
				campaign_status(1,$(this).val());
				check = 1;
			}
        })
        if(check!=1){
            pop_up('请选择推广计划');
        }
	})
    $("#stop_campaign").click(function(){
    	var check = '';
        checkList.each(function(){
            if($(this).is(":checked")){
            	campaign_status(0,$(this).val());
            	check = 1;
            }
        })
        if(check!=1){
            pop_up('请选择推广计划');
        }
    })
});

//数据排序
function report_sort(type, sort) {
	location.href = '/campaign/campaign_list/<?php echo $time?>/'+type+'/'+sort+'/<?php echo $cur_page; ?>';
}

//修改推广计划名称
function edit_campaign_name(obj, id, value) {
	var str = "<input type=\"text\" value=\""+value+"\" id=\"campaign_name"+id+"\" style=\"width:120px;height:20px;color:#666;border:1px solid #ccc;border-radius:4px;padding:0 10px;\">&nbsp;&nbsp;<button onclick=\"confirm_edit("+id+")\" style=\"width:30px;height:20px;color:#fff;background-color:#1dbb73;border-color:#0c9c62;border:0;\">确定</button>";
	$(obj).html(str);
	$(obj).removeAttr('onclick');
}
function confirm_edit(id) {
	var name = $('#campaign_name'+id).val();
	if(!name) {
		pop_up('请输入推广计划名称');
		return false;
	}
	if(name.length > 30) {
		pop_up('推广计划名称最多可输入30个字');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/campaign/edit_campaign_name/'+id+'/'+name,
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

//修改推广计划日限额
function edit_day_sum(obj, id, value) {
	var str = "<input type=\"text\" value=\""+value+"\" id=\"day_sum"+id+"\" style=\"width:60px;height:20px;color:#666;border:1px solid #ccc;border-radius:4px;padding:0 10px;\">&nbsp;&nbsp;<button onclick=\"confirm_day_sum("+id+")\" style=\"width:30px;height:20px;color:#fff;background-color:#1dbb73;border-color:#0c9c62;border:0;\">确定</button>&nbsp;&nbsp;";
	$(obj).html(str);
	$(obj).removeAttr('onclick');
}
function confirm_day_sum(id) {
	var value = $('#day_sum'+id).val();
	if(isNaN(value)) {
		pop_up('每日限额必须是数字');
		return false;
	}
	if(value<20) {
		pop_up('最少要输入20元');
		return false;
	}
	if(value>1000000) {
		pop_up('最多可输入1000000元');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/campaign/edit_day_sum/'+id+'/'+value,
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

//查找指定推广计划下的推广组
function find_adgroup(campaign_id) {
	location.href = '/adgroup/adgroup_list/'+campaign_id+'/<?php echo $time;?>/id/desc/<?php echo $cur_page; ?>';
}

//查找指定推广计划下的广告
function find_adinfo(campaign_id) {
	location.href = '/adinfo/adinfo_list/'+campaign_id+'/0/0/<?php echo $time;?>/id/desc/<?php echo $cur_page; ?>';
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

//推广计划搜索
function search_campaign(time) {
	location.href = '/campaign/campaign_list/'+time+'/id/desc/<?php echo $cur_page; ?>';
}

//设置推广计划状态
function campaign_status(status, id){
	$.ajax({
		type: 'POST',
		url: '/campaign/status/'+status+'/'+id,
		dataType: 'json',
		success: function (msg) {
			var res = msg['status'];
			
			if(res==1){
				$('#status'+id).children('b').html('推广中');
				$('#status'+id).children('b').attr('class', 'green');
				location.reload();
			}else if(res==0){
				$('#status'+id).children('b').html('未推广');
				$('#status'+id).children('b').attr('class', 'org');
				location.reload();
			}	
		}
	});
}

//新增推广计划判断
function add_campaign(){
	$.ajax({
		type: 'POST',
		url: '/campaign/get_campaign_num/',
		dataType: 'json',
		success: function (msg) {
			var res = msg;
			
            if(res>=10){
            	pop_up('最多可设置10个推广计划');
        		return false;
            }else{
            	location.href='/campaign/add_campaign';
            }
		}
	});
}
</script>
