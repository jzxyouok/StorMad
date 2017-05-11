<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script src="<?php echo base_url(); ?>/js/base.js"></script>

<style>
a{cursor:pointer;}
.desc_sort{width: 0;height: 0;position: absolute;top: 15px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: #ccc transparent transparent transparent;}
.desc_sort_new{width: 0;height: 0;position: absolute;top: 15px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: #1dbb73 transparent transparent transparent;}
.asc_sort{width: 0;height: 0;position: absolute;top: 6px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: transparent transparent #ccc transparent;}
.asc_sort_new{width: 0;height: 0;position: absolute;top: 6px;cursor:pointer;line-height: 0;font-size: 0;border-width: 8px;border-style: solid;border-color: transparent transparent #1dbb73 transparent;}
</style>

<div class="gtCon">
  <div class="srcArea">
  
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <form action="/report/report_list" method="post" id="data_submit">
      <tr  id="data_type">
        <td width="12%" height="32" align="right"><p class="gray6">数据类型：</p></td>
        <td width="12%"><label><span class="checkbox">
                  <input type="checkbox" name="impressions" id="impressions" onclick="select_type(this)" <?php if((isset($impressions) && $impressions) || isset($data_type)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>展现量</b></label></td>
        <td width="12%"><label><span class="checkbox">
                  <input type="checkbox" name="clicks" id="clicks" onclick="select_type(this)" <?php if((isset($clicks) && $clicks) || isset($data_type)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>点击量</b></label></td>
        <td width="12%"><label><span class="checkbox">
                  <input type="checkbox" name="click_rate" id="click_rate" onclick="select_type(this)" <?php if((isset($click_rate) && $click_rate) || isset($data_type)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>点击率</b></label></td>
        <td width="12%"><label><span class="checkbox">
                  <input type="checkbox" name="costs" id="costs" onclick="select_type(this)" <?php if((isset($costs) && $costs) || isset($data_type)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>收益</b></label></td>
        <td width="12%"><label><span class="checkbox">
                  <input type="checkbox" name="click_cost" id="click_cost" onclick="select_type(this)" <?php if((isset($click_cost) && $click_cost) || isset($data_type)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>平均点击收益</b></label></td>
        <td width="12%">&nbsp;</td>
        <td width="2%">&nbsp;</td>
        <td width="12%">&nbsp;</td>
      </tr>
      <tr id="data_time">
        <td height="32" align="right"><p class="gray6">数据时段：</p></td>
        <td><label><span class="radio">
                  <input name="time" type="radio" value="<?php echo date('Y-m-d 00:00:00', time()); ?>" id="today" <?php if(isset($data_time) && (strtotime(date('Y-m-d 00:00:00')) == $data_time)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>今天</b></label></td>
        <td><label><span class="radio">
                  <input name="time" type="radio" value="<?php echo date('Y-m-d 00:00:00', strtotime('-1 day')); ?>" id="yesterday" <?php if(isset($data_time) && (strtotime(date('Y-m-d 00:00:00', strtotime('-1 day'))) == $data_time)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>昨天</b></label></td>
        <td><label><span class="radio">
                  <input name="time" type="radio" value="<?php echo date('Y-m-d 00:00:00', strtotime('-7 day')); ?>" id="week" <?php if(isset($data_time) && (strtotime(date('Y-m-d 00:00:00', strtotime('-7 day'))) == $data_time)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>过去7天</b></label></td>
        <td><label><span class="radio">
                  <input name="time" type="radio" value="<?php echo date('Y-m-d 00:00:00', strtotime('-30 day')); ?>" id="month" <?php if(isset($data_time) && (strtotime(date('Y-m-d 00:00:00', strtotime('-30 day'))) == $data_time)){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>过去30天</b></label></td>
        <td><label><span class="radio">
                  <input name="time" type="radio" id="custom_time" <?php if(!$data_time){?>checked="checked"<?php }?>>
                  <i></i> </span> <b>自定义时间段</b></label></td>
        <td>
      
        <span class="input-sm">
                <input type="text" name="start_time" value="<?php echo (isset($start_time) && $start_time)?$start_time:''; ?>" id="start_time" placeholder="开始时间" readonly="readonly">
                </span></td>
                <td align="center">到</td><td><span class="input-sm">
                <input type="text" name="end_time" value="<?php echo (isset($end_time) && $end_time)?$end_time:''; ?>" id="end_time" placeholder="结束时间" readonly="readonly">
                </span></td>
      </tr>
      <input type="hidden" name="cur_page" value="<?php echo (isset($cur_page) && $cur_page)?$cur_page:''; ?>">
      </form>
      <tr>
        <td height="52" colspan="7" align="center" valign="bottom"><button class="btn-cyan btn-big-pdlg" onclick="search()"><i class="iF iF-search vlm"></i> <b>查询</b></button></td>
      </tr>
    </table>
  
  </div>
  
  <div class="gtarea" style="padding-left:35px;">
<!--    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="right">
        <button class="btn-cyan btn-sm" onclick="download()"><i class="iF">&#xe61d;</i> <b>下载</b></button>
        </td>
      </tr>
    </table>-->
  </div>
  <div class="tabCon" id="adinfo_report">
    <div id='fenyeCon'>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th>广告位名称</th>
            <?php if(isset($costs) && $costs){?><th style="position: relative; ">收益&nbsp;<?php if($order_field=='cost' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($cost_sort) && $cost_sort=='desc'){?>_new<?php }?>" onclick="report_sort('cost', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($cost_sort) && $cost_sort=='asc'){?>_new<?php }?>" onclick="report_sort('cost', 'desc')"></i><?php }?></th><?php }?>
            <?php if(isset($click_cost) && $click_cost){?><th style="position: relative; ">平均点击收益&nbsp;<?php if($order_field=='average_cost' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($average_cost_sort) && $average_cost_sort=='desc'){?>_new<?php }?>" onclick="report_sort('average_cost', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($average_cost_sort) && $average_cost_sort=='asc'){?>_new<?php }?>" onclick="report_sort('average_cost', 'desc')"></i><?php }?></th><?php }?>
            <?php if(isset($impressions) && $impressions){?><th style="position: relative; ">展现量&nbsp;<?php if($order_field=='impressions' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($impressions_sort) && $impressions_sort=='desc'){?>_new<?php }?>" onclick="report_sort('impressions', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($impressions_sort) && $impressions_sort=='asc'){?>_new<?php }?>" onclick="report_sort('impressions', 'desc')"></i><?php }?></th><?php }?>
            <?php if(isset($clicks) && $clicks){?><th style="position: relative; ">点击量&nbsp;<?php if($order_field=='click' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($click_sort) && $click_sort=='desc'){?>_new<?php }?>" onclick="report_sort('click', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($click_sort) && $click_sort=='asc'){?>_new<?php }?>" onclick="report_sort('click', 'desc')"></i><?php }?></th><?php }?>
            <?php if(isset($click_rate) && $click_rate){?><th style="position: relative; ">点击率&nbsp;<?php if($order_field=='ctr' && $order_type=='desc') {?><i class="desc_sort<?php if(isset($ctr_sort) && $ctr_sort=='desc'){?>_new<?php }?>" onclick="report_sort('ctr', 'asc')"></i><?php }else{?><i class="asc_sort<?php if(isset($ctr_sort) && $ctr_sort=='asc'){?>_new<?php }?>" onclick="report_sort('ctr', 'desc')"></i><?php }?></th><?php }?>  
          </tr>
        </thead>
        <tbody>
        <?php foreach ($adinfo as $k=>$val) {?>
          <tr>
            <td><?php echo $val['area_name']?><a href=""></a></td>
            <?php if(isset($costs) && $costs){?>
            <td><span class="org"><?php echo $val['cost']; ?></span>元</td>
            <?php }?>
            <?php if(isset($click_cost) && $click_cost){?>
            <td><span class="org"><?php echo $val['average_cost']; ?></span>元</td>
            <?php }?>
            <?php if(isset($impressions) && $impressions){?>
            <td><?php echo $val['impressions']; ?></td>
            <?php }?>
            <?php if(isset($clicks) && $clicks){?>
            <td><?php echo $val['click']; ?></td>
            <?php }?>
            <?php if(isset($click_rate) && $click_rate){?>
            <td><?php echo $val['ctr']; ?></td>
            <?php }?>
          </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
    <div class="tr pdT20">
      <div class="td-12">
        <div class="fanye">
          <div id="fenye" style="text-align:right">
            <div name="laypage1.3" class="laypage_main laypageskin_default" id="laypage_0">
                <?php echo $page; ?>
                <?php if($page){?>
                  <a>共&nbsp;<?php echo $cur_page; ?>/<?php echo $total_page; ?>&nbsp;页</a>
                  <input type="text" value="" id="go_adinfo_page">
                  <a href="javascript:go_page('adinfo', '<?php echo $url; ?>', <?php echo $total_page; ?>)">GO</a>
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
	if($('#custom_time').is(':checked')){
	    $('#start_time').attr("onclick", "laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})");
	    $('#end_time').attr("onclick", "laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})");
	}else{
		$('#start_time').val('');
	    $('#end_time').val('');
		$('#start_time').removeAttr('onclick');
	    $('#end_time').removeAttr('onclick');
	}
});
	
	//数据排序
	function report_sort(order_field, sort) {
		var time = '<?php echo date('Y-m-d H:i:s', $data_time);?>';
		var start_time = '<?php echo isset($start_time)?$start_time:0;?>';
		var end_time = '<?php echo isset($end_time)?$end_time:0;?>';
		location.href = '/report/report_list/'+time+'/'+start_time+'/'+end_time+'/'+order_field+'/'+sort+'/<?php echo isset($cur_page)?$cur_page:1; ?>';
	}

	//到指定页码
	function go_page(name, link, num) {
		if(name=='adgroup'){
            var go_page = $('#go_adgroup_page').val();
		}
		if(name=='adinfo'){
			var go_page = $('#go_adinfo_page').val();
		}
		if(go_page > num){
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
	
	function select_type(obj){
		if($(obj).is(':checked')){
			$(obj).val('1');  //选中
		}else{
			$(obj).val('');  //不选
		}
	}
	
	var data_time = $('#data_time').find('input[name=time]');
	data_time.click(function(){
		if($('#custom_time').is(':checked')){
		    $('#start_time').attr("onclick", "laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})");
		    $('#end_time').attr("onclick", "laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})");
		}else{
			$('#start_time').val('');
		    $('#end_time').val('');
			$('#start_time').removeAttr('onclick');
		    $('#end_time').removeAttr('onclick');
		}
	})
	
	function search(){
		var start_time = $('#start_time').val();
		var end_time = $('#end_time').val();
		
		if($('#custom_time').is(':checked')){
		    if(!start_time){
				pop_up('请输入开始时间');
				return false;
			}
		    if(!end_time){
				pop_up('请输入结束时间');
				return false;
			}
			
			var date1 = start_time;
			var date2 = end_time;
			 
			var times1 = new Date(date1).getTime();
			var times2 = new Date(date2).getTime();
			var days = (times2 - times1)/60/60/24/1000;
			
			if(Math.round(days)>30){
				pop_up('自定义时间段搜索间隔不能超过30天');
				return false;
			}
		}
		$('#data_submit').submit();
	}

	//下载报表数据
	function download() {
		var time = '<?php echo date('Y-m-d H:i:s', $data_time);?>';
		var start_time = '<?php echo isset($start_time)?$start_time:0;?>';
		var end_time = '<?php echo isset($end_time)?$end_time:0;?>';
		
		location.href = '/report/download/'+'/'+time+'/'+start_time+'/'+end_time;
	}
</script> 

