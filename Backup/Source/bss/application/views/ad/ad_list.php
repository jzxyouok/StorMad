<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>

  <div class="gtarea">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="layoutTable">
      <tbody>
        <tr>
          <th width="10%">广告主名称：</th>
          <td width="20%"><span class="input-sm">
            <input type="text" value="<?php echo isset($ad_user)?$ad_user:''?>" placeholder="广告主名称" id="ad_user">
            </span></td>
          <th width="10%">广告标题：</th>
          <td width="20%"><span class="input-sm">
            <input type="text" value="<?php echo isset($ad_title)?$ad_title:''?>" placeholder="广告标题" id="ad_title">
            </span></td>
          <th width="10%">广告状态：</th>
          <td width="20%"><div class="select select_status" style="z-index:9;">
              <p class="fy"> <span id="ad_status_name">请选择状态</span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden" value="" id="ad_status"/>
              <ol class="option">
                <li val="4">请选择</li>
                <li val="0">未审核</li>
                <li val="1">审核通过</li>
                <li val="2">投放中</li>
                <li val="3">审核不通过</li>
              </ol>
            </div></td>
          <td width="10%" rowspan="2" style="padding-left:20px"><button class="srcBtn-lg" onclick="search()"><i class="iF iF-search"></i></button></td>
        </tr>
        <tr>
          <th>广告类型：</th>
          <td><div class="select select_status" style="z-index:9;">
              <p class="fy"> <span id="ad_type_name">请选择类型</span> <i class="iF iF-arrdown right"></i></p>
              <input  type="hidden" value="" id="ad_type"/>
              <ol class="option">
                <li>请选择</li>
                <li val="1">图片</li>
                <li val="2">文字</li>
              </ol>
            </div></td>
          <th>广告ID：</th>
          <td><span class="input-sm">
            <input type="text" placeholder="广告ID" name="ad_id" id="ad_id" value="<?php echo isset($ad_id)?$ad_id:''?>">
            </span></td>
          <th>投放时间：</th>
          <td><span class="input-sm">
            <input type="text" value="<?php echo isset($ad_put_time)?$ad_put_time:''?>" placeholder="投放时间" id="ad_put_time" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
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
            <th width="2%"> <span class="checkbox">
              <input type="checkbox" id="chk_all">
              <i></i> </span> </th>
            <th width="5%">ID</th>
            <th width="20%">广告标题</th>
            <th>类型</th>
            <th width="15%">规格名称</th>
            <th>广告主</th>
            <th width="20%">投放时间</th>
            <th>状态</th>
            <th width="15%">操作</th>
          </tr>
        </thead>
        <tbody id="ads_list">
        <?php foreach ($ad as $k=>$val){?>
          <tr>
            <td><span class="checkbox">
              <input type="checkbox" value="<?php echo $val['id']; ?>" class="chk_list" id="audit<?php echo $val['id']; ?>">
              <i></i> </span></td>
            <td><?php echo $val['id']; ?></td>
            <td><?php echo $val['title']; ?><a href="javascript:view_ad(<?php echo $val['id']; ?>, '<?php echo $val['title']?>');"><i class="iF iF-yulan"></i> 预览</a></td>
            <td><?php echo $val['type']==1 ? '图片' : '文字'; ?></td>
            <td><?php echo $val['size_name']; ?></td>
            <td><?php echo $val['true_name'].'('.$val['user_name'].')'; ?></td>
            <td><?php
                $start_time = date('Y-m-d H:i:s', $val['start_time']);
                $end_time = date('Y-m-d H:i:s', $val['end_time']);
                echo $start_time .'&nbsp至&nbsp'.$end_time; 
                ?></td>
            <td><span class="<?php 
				if($val['status']==0)
				{
					echo '';
				}
				elseif($val['status']==1)
				{
				    echo 'green';
				}
				elseif($val['status']==2)
				{
					echo 'green';
				}
				elseif($val['status']==3)
				{
					echo 'org';
				}
			?>" id="status<?php echo $val['id']; ?>">
            <?php 
				if($val['status']==0)
				{
					echo '未审核';
				}
				elseif($val['status']==1)
				{
				    echo '审核通过';
				}
				elseif($val['status']==2)
				{
					echo '投放中';
				}
				elseif($val['status']==3)
				{
					echo '审核不通过';
				}
			?>
			</span></td>
            <td><a href="javascript:audit(1,<?php echo $val['id']; ?>);">审核通过</a><b class="line">|</b><a href="javascript:audit(3,<?php echo $val['id']; ?>);">审核不通过</a></td>
          </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
    
      <div class="tr pdT20">
        <div class="td-5">
          <button class="btn-green btn-lg" id="ads_audit_pass"><i class="iF iF-pass vlm"></i> <b>审核通过</b></button> &nbsp;
          <button class="btn-org btn-lg" id="ads_audit_not_pass"><i class="iF iF-notpass vlm"></i> <b>审核不通过</b></button>
        </div>
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
$(document).ready(function() {
	$('.select_status').Gfselect({
		toValFn:false,
	});
	$('.select_type').Gfselect({
		toValFn:false,
	});
	<?php 
		if(isset($ad_status) && $ad_status!=4) 
		{
    		echo "$('#ad_status').val({$ad_status});";
    		if($ad_status == 0) 
			{
    			echo "$('#ad_status_name').html('未审核');";
			}
			elseif($ad_status == 1)
			{
    			echo "$('#ad_status_name').html('审核通过');";
			}
			elseif($ad_status == 2)
			{
			    echo "$('#ad_status_name').html('投放中');";
			}
			elseif($ad_status == 3)
			{
    			echo "$('#ad_status_name').html('审核不通过');";
			}
		}
		if(isset($ad_type) && $ad_type)
		{
    		echo "$('#ad_type').val({$ad_type});";
    		if($ad_type == 1)
			{
    			echo "$('#ad_type_name').html('图片');";
    		}
    		elseif($ad_type == 2)
			{
    			echo "$('#ad_type_name').html('文字');";
    		}
   		}
	?>
	var chekcAll=$("#chk_all");
	var chekList=$("#ads_list").find("input[type=checkbox]");
    chekcAll.click(function(){			 
    	if($(this).is(":checked")){
			chekList.each(function(){
				$(this).prop("checked", true);
			})
		}else{
			chekList.each(function(){
				$(this).prop("checked", false);
			})
		}
	})
	$("#ads_audit_pass").click(function(){
		var check = '';
		chekList.each(function(){
			if($(this).is(":checked")){
				audit(1,$(this).val());
				check = 1;
			}
        })
        if(check!=1){
            pop_up('请选择要审核的广告');
        }
	})
    $("#ads_audit_not_pass").click(function(){
    	var check = '';
        chekList.each(function(){
            if($(this).is(":checked")){
                audit(3,$(this).val());
                check = 1;
            }
        })
        if(check!=1){
            pop_up('请选择要审核的广告');
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
                
//广告搜索
function search() {
	var user = $("#ad_user").val() || 0;
	var title = $("#ad_title").val() || 0;
	var type = $("#ad_type").val() || 0;
	var status = $("#ad_status").val() || 0;
	var ad_id = $("#ad_id").val() || 0;
	var put_time = $('#ad_put_time').val() || 0;
	
	location.href = '/ad/ad_list/'+user+'/'+title+'/'+type+'/'+status+'/'+ad_id+'/'+put_time;
}
                
//广告审核
function audit(status, id) {
	$.ajax({
		type: 'POST',
		url: '/ad/audit/'+status+'/'+id,
		dataType: 'json',
		success: function (msg) {
			var res = msg['status'];
			
			$('#status'+id).removeClass();
			if(res==1){
				$('#status'+id).addClass('green');
				$('#status'+id).html('审核通过');
				location.reload();
			}else if(res==2){
				$('#status'+id).addClass('green');
				$('#status'+id).html('投放中');
				location.reload();
			}else if(res==3){
				$('#status'+id).addClass('org');
				$('#status'+id).html('审核不通过');
				location.reload();
			}	
		}
	});
}

//广告预览
function view_ad(id, title) {
	$.ajax({
        type: 'GET',
        url: '/ad/view/'+id,
        dataType: 'json',
        success: function (msg) {
        	if(msg.width==0 && msg.width==0){
				content = '<div style="width:320px;height:200px">'+msg.content+'</div>';
			}else{
        		content = '<div style="width:'+msg.width+'px;height:'+msg.height+'px">'+msg.content+'</div>';
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
