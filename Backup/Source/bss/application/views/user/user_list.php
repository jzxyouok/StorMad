<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <tr>
          <th width="10%">广告主名称：</th>
          <td width="20%"><span class="input-sm">
            <input type="text" placeholder="广告主名称" id="user_name" value="<?php echo isset($user_name)?$user_name:''?>">
            </span></td>
          <th width="10%">用户类型：</th>
          <td width="20%"><div class="select select_user_type" style="z-index:8;">
              <p class="fy"> <span id="user_type_name">请选择类型</span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden"  value="" id="user_type">
              <ol class="option">
                <li>请选择</li>
                <li val="1">企业</li>
                <li val="2">个人</li>
                <li val="3">广告代理商</li>
              </ol>
            </div></td>
          <th width="10%">账户状态：</th>
          <td width="20%"><div class="select select_user_status" style="z-index:8;">
              <p class="fy"> <span id="user_status_name">请选择状态</span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden"  value="" id="user_status">
              <ol class="option">
                <li>请选择</li>
                <li val="0">停用</li>
                <li val="1">正常</li>
              </ol>
            </div></td>
          <td width="10%"  style="padding-left:20px"><button class="srcBtn-sm" onclick="search()"><i class="iF iF-search"></i></button></td>
        </tr>
        
      </tbody>
    </table>
  </div>
  <div class="tabCon">
    <div id='fenyeCon'>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="10%">ID</th>
            <th width="20%">广告主名称 </th>
            <th width="18%">用户类型</th>
            <th width="16%">账户状态</th>
            <th width="18%">账号余额</th>
            <th width="18%">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($user as $k=>$val) {?>
          <tr>
            <td><?php echo $val['id']; ?></td>
            <td><?php echo $val['true_name'].'('.$val['user_name'].')'; ?></td>
            <td><?php if($val['type']==1) {?>企业<?php }elseif($val['type']==2){?>个人<?php }elseif($val['type']==3){?>广告代理商<?php }?></td>
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
            <td><span class="org"><?php $money = sprintf("%1\$.2f", $val['money']/100); echo $money; ?></span>元</td>
            <td>
              <a href="/user/add_money/<?php echo $val['id']; ?>">添加金额</a>  <a href="/user/edit_password/<?php echo $val['id']; ?>">重置密码</a>   <a href="javascript:set_status(0,<?php echo $val['id']; ?>);">停用</a> <b class="line">|</b> <a href="javascript:set_status(1,<?php echo $val['id']; ?>);">启用</a>
            </td>
          </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
    
      <div class="tr pdT20">
        <div class="td-3">
          <a href="/user/add_user" class="btn-green btn-lg"><i class="iF iF-newadd vlm"></i> <b>增加新用户</b></a> &nbsp;
         
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
	$('.select_user_type').Gfselect({
		toValFn:false,
	});
	$('.select_user_status').Gfselect({
		toValFn:false,
	});
	<?php 
		if(isset($user_type) && $user_type) 
		{
    		echo "$('#user_type').val({$user_type});";
    		if($user_type == 1) 
			{
    			echo "$('#user_type_name').html('企业');";
			}
			elseif($user_type == 2)
			{
    			echo "$('#user_type_name').html('个人');";
			}
			elseif($user_type == 3)
			{
    			echo "$('#user_type_name').html('广告代理商');";
			}
		}
		if(isset($user_status) && $user_status)
		{
    		echo "$('#user_status').val({$user_status});";
    		if($user_status == 0)
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

//广告主搜索
function search() {
	var user = $("#user_name").val() || 0;
	var type = $("#user_type").val() || 0;
	var status = $("#user_status").val();
	
	location.href = '/user/user_list/'+user+'/'+type+'/'+status;
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
	layer.msg("你确定<b class='org'>"+str+"</b>该账户么？", {
	    time: 0 //不自动关闭
	    ,btn: ['确定', '取消']
	    ,yes: function(index){
	    	$.ajax({
	    		type: 'POST',
	    		url: '/user/set_status/'+status+'/'+id,
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