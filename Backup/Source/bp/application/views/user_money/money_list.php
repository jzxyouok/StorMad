<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="tips" style="margin-bottom:20px; padding-top:12px; padding-bottom:12px;">
  财务记录仅保持最近三个月的操作信息，请及时下载保存。
</div>
<div class="gtCon">
  <div class="gtarea">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
  <td align="center" width="5%">时间范围：</td>
  <td width="20%">
    <span class="input-sm">
      <input type="text" value="<?php echo isset($start_time)?$start_time:''; ?>" placeholder="开始时间" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="start_time">
    </span>
  </td>
  <td align="center" width="3%">到</td>
  <td width="20%">
    <span class="input-sm">
      <input type="text" value="<?php echo isset($end_time)?$end_time:''; ?>" placeholder="结束时间" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" id="end_time">
    </span>
  </td>
  <td align="center" width="5%">
    <input type="button" class="Pbtn-cyan" value="搜索" onclick="search()">
  </td>
  <td align="right">
    <button class="btn-green btn-lg" onclick="location.href='/user_money/update_password'"><b>修改密码</b></button> &ensp; <button class="btn-green btn-lg" onclick="download()"><i class="iF">&#xe61d;</i> <b>下载日志</b></button>
  </td>
  </tr>
  </table>
  </div>
  <div class="tabCon">
    
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="22%">日期</th>
            <th width="20%">支出</th>
            <th width="18%">存入</th>
            <th width="20%">账户余额(元)</th>
            <th width="20%">备注</th>            
          </tr>
        </thead>
        <tbody>
          <?php foreach ($money as $k=>$val) {?>
          <tr>          
            <td><?php echo date('Y-m-d H:i:s', $val['add_time']); ?></td>
            <td><?php if($val['type']==1) { $money = sprintf("%1\$.2f", $val['money']/100); echo '<span class="org">'.$money.'</span>元'; }?></td>
            <td><?php if($val['type']==2) { $money = sprintf("%1\$.2f", $val['money']/100); echo '<span class="org">'.$money.'</span>元'; }?></td>
            <td><?php $user_money = sprintf("%1\$.2f", $val['remain_sum']/100); echo '<span class="org">'.$user_money.'</span>元'; ?></td>
            <td><?php echo $val['comment']; ?></td>         
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
function search() {
	var start_time = $("#start_time").val();
	var end_time = $("#end_time").val();
	
	location.href = '/user_money/money_list/'+start_time+'/'+end_time;
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
	    closeBtn: 0,
	    shadeClose: true,
	    skin: 'layui-layer-rim',
	    content: '<div style="font-size:15px;font-weight:900;padding:15px; ">'+prompt+'</div>'
	});
}

//下载日志
function download() {
	location.href = '/user_money/download';
}
</script>
