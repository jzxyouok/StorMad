<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <tr>
          <th width="14%">管理员名称：</th>
          <td width="31%"><span class="input-sm ">
            <input type="text" value="<?php echo isset($user_name)?$user_name:''?>" placeholder="管理员名称" id="user_name">
            </span></td>
          <th width="14%"></th>
          <td colspan="3"></td>
          <td width="10%" rowspan="2" style="padding-left:20px"><button class="srcBtn-lg" onclick="search()"><i class="iF iF-search"></i></button></td>
        </tr>
        <tr>
          <th>日志内容：</th>
          <td><span class="input-sm">
            <input type="text" value="<?php echo isset($content)?$content:''?>" placeholder="日志内容" id="content">
            </span></td>
          <th>操作时间：</th>
          <td width="14%"><span class="input-sm">
            <input type="text" value="<?php echo isset($start_time)?$start_time:''?>" id="start_time" placeholder="开始时间" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
            </span></td>
          <td width="3%" align="center">到</td>
          <td width="14%"><span class="input-sm">
            <input type="text" value="<?php echo isset($end_time)?$end_time:''?>" id="end_time" placeholder="结束时间" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
            </span></td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="tabCon">
    <div id='fenyeCon'>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="16%">操作时间</th>
            <th width="70%">操作内容</th>
            <th width="14%">操作对象</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($ad_log as $k=>$val) {?>
          <tr>
            <td><?php $add_time = date('Y-m-d H:i:s', $val['add_time']);
                echo $add_time; ?></td>
            <td><?php echo $val['content']; ?></td>
            <td><?php echo $val['true_name'].'('.$val['user_name'].')'; ?></td>
          </tr>
        <?php }?>  
        </tbody>
      </table>
    </div>
    
      <div class="tr pdT20">
        
        <div class="td-7">
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
//广告操作日志搜索
function search() {
	var user = $("#user_name").val() || 0;
	var content = $("#content").val() || 0;
	var start_time = $("#start_time").val() || 0;
	var end_time = $("#end_time").val() || 0;
	
	location.href = '/admin_log/ad_log/'+user+'/'+content+'/'+start_time+'/'+end_time;
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
