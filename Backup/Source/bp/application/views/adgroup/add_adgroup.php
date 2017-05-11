<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="gtCon">
  <form action="/adgroup/add_adgroup" method="post" id="adgroup_submit">
  <div class=" addnew box">    
     <h6 class="td-12">第一步：输入推广组名称</h6>
     <div class="tr">
         <div class="td-2 txtGt">所属推广计划：</div>
         <div class="td-4">
           <div class="select left select_adgroup" style="z-index:9;">
             <p class="fy"> <span><?php if(!isset($adgroup['campaign_name']) && isset($campaign['campaign_name']) && $campaign['campaign_name']){ echo $campaign['campaign_name'];}else{ echo (isset($adgroup['campaign_name']))?$adgroup['campaign_name']:'请选择推广计划';}?></span> <i class="iF iF-arrdown right"></i></p>
             <input type="hidden" name="campaign_id" value="<?php if(!isset($adgroup['campaign_id']) && isset($campaign['id']) && $campaign['id']){ echo $campaign['id'];}else{ echo isset($adgroup['campaign_id'])?$adgroup['campaign_id']:'';}?>" id="campaign_id"/>
             <ol class="option">
               <li>请选择</li>
             <?php foreach ($campaign_name as $k=>$val) {?>
               <li val="<?php echo $val['id']; ?>"><?php echo $val['campaign_name']; ?></li>
             <?php }?>
             </ol>
           </div>
         </div>
     </div>
     <div class="tr">
         <div class="td-2 txtGt">推广组名称：</div>
         <div class="td-4" style="line-height: 14px;">
           <span class="input-sm">
             <input type="text" name="adgroup_name" id="adgroup_name" onblur="check(this)" value="<?php echo isset($adgroup['adgroup_name'])?$adgroup['adgroup_name']:'';?>">
           </span>
         </div>
         <div class="td-6"><p class="gray9">最多30个字</p> </div>
     </div>
     <h6 class="td-12"> 第二步：设定推广限额</h6>
     <div class="tr">
      
      <div class="td-2 txtGt">每日限额： </div>
      <div class="td-10">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td><p><label><span class="radio"> <input name="sum" type="radio" onclick="set_sum(1)" <?php if(isset($adgroup['day_sum']) && !$adgroup['day_sum']){?>checked='checked'<?php }?>><i></i> </span> <b>不设限额</b></label></p></td>
            </tr>
          <tr>
            <td style="line-height: 14px;"><label><span class="radio"> <input name="sum" type="radio" onclick="set_sum(2)" <?php if(isset($adgroup['day_sum']) && $adgroup['day_sum']){?>checked='checked'<?php }?>><i></i> </span> <b>设置限额</b></label> &emsp; <span class="input-sm"><input type="text" name="day_sum" onblur="check(this)" value="<?php echo isset($adgroup['day_sum'])?sprintf("%1\$.2f", $adgroup['day_sum']/100):'';?>" style="width:120px;" id="day_sum" <?php if(!isset($adgroup['day_sum']) || (isset($adgroup['day_sum']) && !$adgroup['day_sum'])){?>readonly="true"<?php }?>></span> 元，<b class="gray9">请输入数字</b></td>
          </tr>
        </table>
      </div>
      
    </div>
    <input type="hidden" name="campaign_url" value="<?php echo $campaign_url; ?>"/>
    <input type="hidden" name="id" value="<?php echo isset($adgroup['id'])?$adgroup['id']:'';?>">
  </div><!--add new end-->
  </form>
  <div class="box" style="padding:20px 0; background-color:#eee;"><div class="tr">
      <div class="td-2">&nbsp;</div>
      <div class="td-10">
       
          <button class="btn-cyan btn-lg-pdlg" onclick="add_adgroup()">提交</button>
      </div>
    </div></div>
</div>

<script>
$(document).ready(function() {
	$('.select_adgroup').Gfselect({
		toValFn:false,
	});

	if($('#adgroup_name').val()=='默认推广组'){
		$('#adgroup_name').attr('readonly', 'readonly');
	}
});

function check(obj) {
	var val = $(obj).val();
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		if($(obj).attr('id')=='adgroup_name'){
			$.ajax({
				type: 'POST',
				url: '/adgroup/check_adgroup/'+val,
				dataType: 'json',
				success: function (msg) {
					if(msg){
    					if(msg['adgroup_name']!='<?php echo (isset($adgroup['adgroup_name']))?$adgroup['adgroup_name']:'';?>'){
    						$(obj).parent('span').attr('class', 'input-sm input-tip-err');
    						pop_up('推广组名称已存在');
    					}else{
    						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
    					}
					}else{
						$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
					}
				}
			});
			
			if($('#adgroup_name').val().length > 30) {
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

function add_adgroup() {
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
	
	if(!$('#campaign_id').val()) {
		pop_up('请选择所属推广计划');
		return false;
	}
	if(!$('#adgroup_name').val()) {
		pop_up('请输入推广组名称');
		return false;
	}
	if(sum==2) {
    	if($('#adgroup_name').val().length > 30) {
    		pop_up('推广组名称最多可输入30个字');
    		return false;
    	}
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
		url: '/adgroup/get_adgroup_num/',
		dataType: 'json',
		success: function (msg) {
			var res = msg;
			
            if(res>=200){
            	<?php if(!isset($adgroup['id'])){?>
                	pop_up('最多可设置200个推广组');
            	<?php }else{?>
                	$.ajax({
        				type: 'POST',
        				url: '/adgroup/check_adgroup/'+$('#adgroup_name').val(),
        				dataType: 'json',
        				success: function (msg) {
        					if(msg){
            					if(msg['adgroup_name']!='<?php echo (isset($adgroup['adgroup_name']))?$adgroup['adgroup_name']:'';?>'){
            						pop_up('推广组名称已存在');
            					}else{
            						$('#adgroup_submit').submit();
            					}
        					}else{
        						$('#adgroup_submit').submit();
        					}
        				}
        			});
            	<?php }?>
            }else{
            	$.ajax({
    				type: 'POST',
    				url: '/adgroup/check_adgroup/'+$('#adgroup_name').val(),
    				dataType: 'json',
    				success: function (msg) {
    					if(msg){
        					if(msg['adgroup_name']!='<?php echo (isset($adgroup['adgroup_name']))?$adgroup['adgroup_name']:'';?>'){
        						pop_up('推广组名称已存在');
        					}else{
        						$('#adgroup_submit').submit();
        					}
    					}else{
    						$('#adgroup_submit').submit();
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