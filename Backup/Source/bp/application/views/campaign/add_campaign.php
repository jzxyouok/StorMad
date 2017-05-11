<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtCon">
  <form action="/campaign/add_campaign" method="post" id="campaign_submit">
  <div class=" addnew box">    
     <h6 class="td-12">第一步：输入计划名称</h6>
     <div class="tr">
         <div class="td-2 txtGt">推广计划名称：</div>
         <div class="td-4" style="line-height: 14px;">
           <span class="input-sm">
             <input type="text" name="campaign_name" id="campaign_name" onblur="check(this)" value="<?php echo (isset($campaign['campaign_name']))?$campaign['campaign_name']:'';?>">
           </span>
         </div>
         <div class="td-6"><p class="gray9">最多30个字</p> </div>
     </div>
     <h6 class="td-12"> 第二步：设定推广限额</h6>
     <div class="tr">
      <div class="td-2 txtGt">每日限额： </div>
      <div class="td-10">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" id="set_day_sum">
          <tr>
            <td><p><label><span class="radio"> <input name="sum" type="radio" onclick="set_sum(1)" <?php if(isset($campaign['day_sum']) && !$campaign['day_sum']){?>checked='checked'<?php }?>><i></i> </span> <b>不设限额</b></label></p></td>
          </tr>
          <tr>
            <td style="line-height: 14px;"><label><span class="radio"> <input name="sum" type="radio" onclick="set_sum(2)" <?php if(isset($campaign['day_sum']) && $campaign['day_sum']){?>checked='checked'<?php }?>><i></i> </span> <b>设置限额</b></label> &emsp; <span class="input-sm"><input type="text" name="day_sum" onblur="check(this)" value="<?php echo isset($campaign['day_sum'])?sprintf("%1\$.2f", $campaign['day_sum']/100):'';?>" style="width:120px;" id="day_sum" <?php if(!isset($campaign['day_sum']) || (isset($campaign['day_sum']) && !$campaign['day_sum'])){?>readonly="true"<?php }?>></span> 元，<b class="gray9">请输入30-1000000之间的数字</b></td>
          </tr>
        </table>
      </div>
    </div>
  </div><!--add new end-->
  <input type="hidden" name="id" value="<?php echo isset($campaign['id'])?$campaign['id']:'';?>"/>
  </form>
  <div class="box" style="padding:20px 0; background-color:#eee;"><div class="tr">
      <div class="td-2">&nbsp;</div>
      <div class="td-10">       
          <button class="btn-cyan btn-lg-pdlg" onclick="add_campaign()">提交</button>
      </div>
    </div></div>
</div>

<script>
$(document).ready(function() {
	if($('#campaign_name').val()=='默认推广计划'){
		$('#campaign_name').attr('readonly', 'readonly');
	}
});

function check(obj) {
	var val = $(obj).val();
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		if($(obj).attr('id')=='campaign_name'){
			$.ajax({
				type: 'POST',
				url: '/campaign/check_campaign/'+val,
				dataType: 'json',
				success: function (msg) {
					if(msg){
    					if(msg['campaign_name']!='<?php echo (isset($campaign['campaign_name']))?$campaign['campaign_name']:'';?>'){
    						$(obj).parent('span').attr('class', 'input-sm input-tip-err');
    						pop_up('推广计划名称已存在');
    					}else{
    						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
    					}
					}else{
						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
					}
				}
			});
			
			if($('#campaign_name').val().length > 30) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('推广计划名称最多可输入30个字');
			}else{
				$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
			}
		}else if($(obj).attr('id')=='day_sum'){
			if(isNaN($('#day_sum').val())) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('每日限额必须是数字');
			}else if($('#day_sum').val()<30){
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('最少要输入30元');
			}else if($('#day_sum').val()>1000000){
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('最多可输入1000000元');
			}else{
				$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
			}
		}else{
			$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
		}
	}
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

function add_campaign() {
	var day_sum = $("#set_day_sum").find("input[type=radio]");
	var sum = '';
	day_sum.each(function(){
		if($(this).is(":checked")){
			if($(this).attr('onclick')=='set_sum(1)'){
				sum = 1;
			}else if($(this).attr('onclick')=='set_sum(2)'){
				sum = 2;
			}
		}
	})
	
	if(!$('#campaign_name').val()) {
		pop_up('请输入推广计划名称');
		return false;
	}
	if($('#campaign_name').val().length > 30) {
		pop_up('推广计划名称最多可输入30个字');
		return false;
	}
	if(sum==2) {
    	if(!$('#day_sum').val()) {
    		pop_up('请设定每日限额');
    		return false;
    	}
    	if(isNaN($('#day_sum').val())) {
    		pop_up('每日限额必须是数字');
    		return false;
    	}
    	if($('#day_sum').val()<30) {
    		pop_up('最少要输入30元');
    		return false;
    	}
    	if($('#day_sum').val()>1000000) {
    		pop_up('最多可输入1000000元');
    		return false;
    	}
	}
	
	$.ajax({
		type: 'POST',
		url: '/campaign/get_campaign_num/',
		dataType: 'json',
		success: function (msg) {
			var res = msg;
			
            if(res>=10){
            	<?php if(!isset($campaign['id'])){?>
            		pop_up('最多可设置10个推广计划');
            	<?php }else{?>
                	$.ajax({
        				type: 'POST',
        				url: '/campaign/check_campaign/'+$('#campaign_name').val(),
        				dataType: 'json',
        				success: function (msg) {
        					if(msg){
            					if(msg['campaign_name']!='<?php echo (isset($campaign['campaign_name']))?$campaign['campaign_name']:'';?>'){
            						$('#campaign_name').parent('span').attr('class', 'input-sm input-tip-err');
            						pop_up('推广计划名称已存在');
            					}else{
            						$('#campaign_submit').submit();
            					}
        					}else{
        						$('#campaign_submit').submit();
        					}
        				}
        			});
            	<?php }?>
            }else{
            	$.ajax({
    				type: 'POST',
    				url: '/campaign/check_campaign/'+$('#campaign_name').val(),
    				dataType: 'json',
    				success: function (msg) {
    					if(msg){
        					if(msg['campaign_name']!='<?php echo (isset($campaign['campaign_name']))?$campaign['campaign_name']:'';?>'){
        						pop_up('推广计划名称已存在');
        					}else{
        						$('#campaign_submit').submit();
        					}
    					}else{
    						$('#campaign_submit').submit();
    					}
    				}
    			});
            }
		}
	});
}

//设置每日限额
function set_sum(num) {
	if(num==1){
		$('#day_sum').attr('readonly', true);
		$('#day_sum').val('0');
		$('#day_sum').parent('span').attr('class', 'input-sm input-tip-ok');
	}else if(num==2){
		$('#day_sum').attr('readonly', false);
		$('#day_sum').val('');
		if($('#day_sum').val()<30){
			$('#day_sum').parent('span').attr('class', 'input-sm input-tip-err');
		}else{
			$('#day_sum').parent('span').attr('class', 'input-sm input-tip-ok');
		}
	}
}
</script>
