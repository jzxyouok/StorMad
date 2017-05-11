<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<style>
a{cursor:pointer;}
</style>

<div class="tips" style="margin-bottom:20px; padding-top:12px; padding-bottom:12px;">
 <div class="left"><strong class="gray3">重要提示：</strong></div>
 <div class="flowH"> 
  <p>1、用户群是投放广告时选择的广告定向投放的人群。</p>
  <p>2、如果您的广告具有针对消费场景等特殊要求，请合理设置用户群。最多可设置20个用户群。</p>
  <p>3、修改某个用户群中的定向条件后，投放到该用户群的广告的定向投放条件随之修改。</p>
  <p>4、为了提升广告的点击率和购买转化率，得到更好的广告投放效果，请务必设置用户群（做好定向）。</p>
 </div>
</div>
<div class="gtCon">
  <div class="gtarea" style="padding-left:35px;">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    <button class="btn-red btn-sm" id="del_customer"><i class="iF">&#xe601;</i> <b>删除</b></button> &ensp; <button class="btn-cyan btn-sm" onclick="location.href='/customer/add_customer'"><i class="iF">&#xe603;</i> <b>新建用户群</b></button></td>
  </tr>
  </table>
  </div>
  <div class="tabCon">
    
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <thead>
          <tr>
            <th width="4%"><span class="checkbox">
              <input type="checkbox" id="check_all">
              <i></i> </span></th>
            <th width="20%"><small>用户群</small> <small>名称</small> </th>
            <th width="70%">场景标签</th>
            <th width="10%">操作</th>
          </tr>
        </thead>
        <tbody id="check_list">
          <?php foreach ($customer_name as $k=>$val) {
            if($val['customer_name']=='默认用户群'){?>
              <tr id="customer<?php echo $val['id']; ?>">
                <td><span class="checkbox">
                  <input type="" value="<?php echo $val['id']; ?>">
                  <i></i> </span></td>
                <td><span><?php echo $val['customer_name']; ?></span></td>
                <td>
                <?php foreach ($scene_name[$val['id']] as $k2=>$val2) {?>
                  &nbsp;&nbsp;<?php echo $val2['scene_name']; ?>&nbsp;&nbsp;
                <?php }?>
                </td>
                <td>
                  <a href="/customer/edit_customer/<?php echo $val['id']?>">编辑</a> 
                </td>
              </tr>
          <?php }}foreach ($customer_name as $k=>$val) {
            if($val['customer_name']!='默认用户群'){?>
              <tr id="customer<?php echo $val['id']; ?>">
                <td><span class="checkbox">
                  <input type="checkbox" value="<?php echo $val['id']; ?>">
                  <i></i> </span></td>
                <td><span><?php echo $val['customer_name']; ?></span></td>
                <td>
                <?php foreach ($scene_name[$val['id']] as $k2=>$val2) {?>
                  &nbsp;&nbsp;<?php echo $val2['scene_name']; ?>&nbsp;&nbsp;
                <?php }?>
                </td>
                <td>
                  <a href="/customer/edit_customer/<?php echo $val['id']?>">编辑</a> 
                </td>
              </tr>
          <?php }}?>
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
	$("#del_customer").click(function(){
		var check = '';
		checkList.each(function(){
			if($(this).is(":checked")){
				del_customer($(this).val());
				check = 1;
			}
        })
        if(check!=1){
            pop_up('请选择要删除的用户群');
        }
	})
});

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

//修改用户群名称
/* function edit_customer_name(obj, id, value) {
	var str = "<input type=\"text\" value=\""+value+"\" id=\"customer_name"+id+"\" style=\"width:120px;height:20px;color:#666;border:1px solid #ccc;border-radius:4px;padding:0 10px;\">&nbsp;&nbsp;<button onclick=\"confirm_edit("+id+")\" style=\"width:30px;height:20px;color:#fff;background-color:#1dbb73;border-color:#0c9c62;border:0;\">确定</button>";
	$(obj).html(str);
	$(obj).removeAttr('onclick');
}
function confirm_edit(id) {
	var name = $('#customer_name'+id).val();
	if(!name) {
		pop_up('请输入用户群名称');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/customer/edit_customer_name/'+id+'/'+name,
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
} */

//删除用户群
function del_customer(id){
	$.ajax({
		type: 'POST',
		url: '/customer/del_customer/'+id,
		dataType: 'json',
		success: function (msg) {
			if(msg){
				$('#customer'+id).remove();
				location.reload();
			}
		}
	});
}
</script>
