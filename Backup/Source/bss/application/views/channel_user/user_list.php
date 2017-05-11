<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?php echo base_url(); ?>/js/jquery.zclip.min.js"></script>

<div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <tr>
          <th width="10%">渠道号：</th>
          <td width="20%"><span class="input-sm">
            <input type="text" placeholder="渠道号" id="distribution_id" value="<?php echo isset($distribution_id)?$distribution_id:''?>">
            </span></td>
          <th width="10%">渠道名称：</th>
          <td width="20%"><span class="input-sm">
            <input type="text" placeholder="渠道名称" id="distribution_name" value="<?php echo isset($distribution_name)?$distribution_name:''?>">
            </span></td>
          <th width="10%">渠道状态：</th>
          <td width="20%"><div class="select select_user_status" style="z-index:8;">
              <p class="fy"> <span id="user_status_name">请选择状态</span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden"  value="" id="status">
              <ol class="option">
                <li val="2">请选择</li>
                <li val="0">停用</li>
                <li val="1">正常</li>
              </ol>
            </div></td>
          <td width="10%"  style="padding-left:20px"><button class="srcBtn-sm" onclick="search_channel()"><i class="iF iF-search"></i></button></td>
        </tr>
        
      </tbody>
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
            <th width="10%">ID</th>
            <th width="20%">渠道号</th>
            <th width="20%">渠道名称</th>
            <th width="16%">渠道状态</th>
            <th width="18%">广告位</th>
            <th width="18%">操作</th>
          </tr>
        </thead>
        <tbody id="check_list">
        <?php foreach ($user as $k=>$val) {?>
          <tr>
          	<td><span class="checkbox">
              <input type="checkbox" value="<?php echo $val['id']; ?>">
              <i></i> </span></td>
            <td><?php echo $val['id']; ?></td>
            <td><?php echo $val['distribution_id']; ?></td>
            <td><?php echo $val['distribution_name']; ?></td>
            <td><b class="<?php 
				if($val['status']==0)
				{
					echo 'org';
				}
				elseif($val['status']==1)
				{
					echo 'green';
				}
			?>" id="status<?php echo $val['id']; ?>"><?php if($val['status']==0) {?>停用<?php }elseif($val['status']==1) {?>正常<?php }?></b></td>
            <td><?php echo $val['distribution_name']; ?></td>
            <td>
             <a href="/channel_user/edit_password/<?php echo $val['id']; ?>">重置密码</a>   <a href="javascript:set_status(0,<?php echo $val['id']; ?>);">停用</a> <b class="line">|</b> <a href="javascript:set_status(1,<?php echo $val['id']; ?>);">启用</a>
            </td>
          </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
    
      <div class="tr pdT20">
        <div class="td-3">
          <a href="/channel_user/add_user" class="btn-green btn-lg"><i class="iF iF-newadd vlm"></i> <b>增加新渠道</b></a> &nbsp;
          <a href="javascript:void(0)" class="btn-green btn-lg" onclick="export_file()"><b>导出数据</b></a>
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
    
    	
      <div>
     
      <tbody>
        <tbody>
          <tr id="data_time">
           <span class="input-sm">
                <input type="text" name="start_time" value="" id="start_time" placeholder="开始时间" readonly="readonly">
                </span></td>
                <td align="center">到</td><td><span class="input-sm">
                <input type="text" name="end_time" value="" id="end_time" placeholder="结束时间" readonly="readonly">
                </span>
                </td>
                </tr>
                </tbody>
      </table>
      </div>
    
  </div>
<script src="<?php echo base_url(); ?>/js/base.js"></script>
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
	

	var start = {
		elem: '#start_time',
		format: 'YYYY/MM/DD hh:mm:ss',
		max: '2099-06-16 23:59:59', //最大日期
		istime: true,
		istoday: false,
		choose: function(datas){
			 end.start = datas //将结束日的初始值设定为开始日
		}
	};
	var end = {
		elem: '#end_time',
		format: 'YYYY/MM/DD hh:mm:ss',
		max: '2099-06-16 23:59:59',
		istime: true,
		istoday: false,
		choose: function(datas){
			start.max = datas; //结束日选好后，重置开始日的最大日期
		}
	};
	laydate(start);
	laydate(end);

	$('.select_user_status').Gfselect({
		toValFn:false,
	});
	<?php 
		if(isset($status) && $status)
		{
    		echo "$('#status').val({$status});";
    		if($status == 0)
			{
    			echo "$('#user_status_name').html('停用');";
    		}
    		elseif($user_status == 1)
			{
    			echo "$('#user_status_name').html('正常');";
    		}
   		}
	?>
});

function export_file()
{
	var checkId=$("#check_list").find("input[type=checkbox]");
	var start_time=(Date.parse(new Date($("#start_time").val())))/ 1000;
	var end_time=(Date.parse(new Date($("#end_time").val())))/ 1000;

	var idarr=[];
	var id='';
	checkId.each(function(){
				if($(this).is(':checked'))
				{
					idarr.push($(this).val());
				}
			})
	
	id=idarr.join('-');
	
	if(id=='')
	{
		pop_up('你没选择任何渠道');
		return false
	}
	if(isNaN(start_time))
	{
		pop_up('选择开始时间');
		return false
	}
	if(isNaN(end_time))
	{
		pop_up('选择结束时间');
		return false
	}
	
	
	$.ajax({
		type: 'POST',
		url: '/channel_user/export/'+id+'/'+start_time+'/'+end_time,
		dataType: 'json',
		success: function (msg) {
			alert(msg)	
		}
	});
		
}

//渠道搜索
function search_channel() {
	
	var distribution_name = $("#distribution_name").val() || 0;
	var distribution_id = $("#distribution_id").val() || 0;
	var status = $("#status").val();
	
	location.href = '/channel_user/user_list/'+distribution_name+'/'+distribution_id+'/'+status;
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

//设置账户状态
function set_status(status, id) {
	if(status==0){
		var str = '停用';
	}else if(status==1){
		var str = '启用';
	}
	layer.msg("你确定<b class='org'>"+str+"</b>该渠道么？", {
	    time: 0 //不自动关闭
	    ,btn: ['确定', '取消']
	    ,yes: function(index){
	    	$.ajax({
	    		type: 'POST',
	    		url: '/channel_user/set_status/'+status+'/'+id,
	    		dataType: 'json',
	    		success: function (msg) {
	    			var res = msg['status'];
	    			
	    			if(res==0){
	    				$('#status'+id).attr('class', 'org');
	    				$('#status'+id).html('停用');
	    				location.reload();
	    			}else if(res==1){
	    				$('#status'+id).attr('class', 'green');
	    				$('#status'+id).html('正常');
	    				location.reload();
	    			}	
	    		}
	    	});
	    }
	});
}
</script>