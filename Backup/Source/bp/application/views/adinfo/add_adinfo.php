<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/area.css">

<script src="<?php echo base_url(); ?>/js/jquery.zclip.min.js"></script>
<script src="<?php echo base_url(); ?>/js/Popt.js"></script>
<script src="<?php echo base_url(); ?>/js/cityJson.js"></script>
<script src="<?php echo base_url(); ?>/js/citySet.js"></script>
<script src="<?php echo base_url(); ?>/js/jquery.min.js"></script>

<style type="text/css">
.file-box{ position:relative;width:360px}
.file{ position:absolute; top:0; right:276px; height:28px; filter:alpha(opacity:0);opacity: 0;width:83px }

.container{display:none;}
.resizer{overflow: hidden;}
.resizer.have-img button.ok{display: inline-block;}
.resizer.have-img .inner {display: block;}
.inner{width: 100%;position: relative;font-size: 0;overflow: hidden;display: none;}
/*img{width: 100%;}*/
.frames{position: absolute;top: 0;left: 0;border: 0px solid black;cursor: move;outline: rgba(0, 0, 0, 0.6) solid 10000px;}
button.ok{float:right;margin-left: 5px;display: none;}
canvas{max-width: 100%;margin:auto;display: block;}
</style>

<div class="gtCon">
  <form action="/adinfo/add_adinfo" method="post" id="adinfo_submit" enctype="multipart/form-data">
  <div class=" addnew box">    
     <h6 class="td-12">第一步：选择推广计划/推广组</h6>
     <div class="tr">
      <div class="td-2 txtGt">目标推广计划/推广组：</div>
      <div class="td-4">
         <div class="select left select_campaign" style="z-index:9;">
           <p class="fy"> <span><?php if(!isset($adinfo['campaign_name']) && isset($campaign['campaign_name']) && $campaign['campaign_name']){ echo $campaign['campaign_name'];}elseif(!isset($adinfo['campaign_id']) && isset($adgroup['campaign_name']) && $adgroup['campaign_name']){ echo $adgroup['campaign_name'];}else{ echo isset($adinfo['campaign_name'])?$adinfo['campaign_name']:'请选择推广计划';}?></span> <i class="iF iF-arrdown right"></i></p>
           <input type="hidden" value='<?php if(!isset($adinfo['campaign_id']) && isset($campaign_url) && $campaign_url){ echo $campaign_url;}elseif(!isset($adinfo['campaign_id']) && isset($adgroup['campaign_id']) && $adgroup['campaign_id']){ echo $adgroup['campaign_id'];}else{ echo isset($adinfo['campaign_id'])?$adinfo['campaign_id']:'';}?>' name="select_campaign" id="select_campaign">
           <ol class="option" id="campaign_li">
             <li>请选择</li>
             <?php foreach ($campaign_name as $k=>$val){
               if($val['campaign_name']=='默认推广计划'){?>
                 <li val='<?php echo $val['id'];?>'><?php echo $val['campaign_name']; ?></li>
             <?php }}foreach ($campaign_name as $k=>$val){
               if($val['campaign_name']!='默认推广计划'){?>
                 <li val='<?php echo $val['id'];?>'><?php echo $val['campaign_name']; ?></li>
             <?php }}?>
           </ol>
         </div>
      </div>
      <div class="td-4">
         <div class="select left select_adgroup" style="z-index:9;">
           <p class="fy"> <span id="adgroup_name"><?php echo isset($adinfo['adgroup_name'])?$adinfo['adgroup_name']:'请选择推广组';?></span> <i class="iF iF-arrdown right"></i></p>
           <input type="hidden" value='<?php echo isset($adinfo['adgroup_id'])?$adinfo['adgroup_id']:'';?>' name="select_adgroup" id="select_adgroup">
           <ol class="option" id="adgroup">
           </ol>
         </div>
      </div>
     </div>
     <h6 class="td-12"> 第二步：设置广告信息</h6>
     <div class="tr">
         <div class="td-2 txtGt">广告标题：</div>
         <div class="td-4" style="line-height: 14px;">
           <span class="input-sm">
             <input type="text" name="title" id="title" onblur="check(this)" value="<?php echo isset($adinfo['title'])?$adinfo['title']:'';?>">
           </span>
         </div>
     </div>
     <div class="tr">
         <div class="td-2 txtGt">选择广告类型： </div>
         <div class="td-4" id="type">
             <p><label><span class="radio"> <input type="radio" name="type" onclick="select_type(1)" value="1" <?php if(isset($adinfo['type']) && $adinfo['type']==1) {?>checked="checked"<?php }?>><i></i> </span> <b>图片</b></label>
             <label><span class="radio"> <input type="radio" name="type" onclick="select_type(2)" value="2" <?php if(isset($adinfo['type']) && $adinfo['type']==2) {?>checked="checked"<?php }?>><i></i> </span> <b>文字</b></label></p>                                                    
         </div>
     </div>
     <div class="tr">
      
       <div class="td-2 txtGt" style="display:none;" id="ad_type">选择广告规格： </div>
       <div class="td-4" id="size_id">
       </div>
      
    </div>
    
    <div class="tr">
        <div class="td-2 txtGt">上传图片/输入文字素材：</div>
        <div class="td-4"> <span class="input-sm"><input type="text" name="content" value="<?php echo isset($adinfo['content'])?$adinfo['content']:'';?>" id='content'></span></div>
        <div class="td-4" id="file_button">
          <div class="file-box">
            <input type="button" class="btn-cyan btn-sm-pdlg left" value="选择">
            <input type="hidden" name="file_name" value="<?php if(isset($adinfo['type']) && $adinfo['type']==1){ echo isset($adinfo['content'])?$adinfo['content']:'';}?>">
            <input type="file" name="ad_file" accept="image/*" class="file" id="fileField" size="28" onchange="document.getElementById('content').value=this.value" />
          </div> &nbsp; <span style="color:#aaa;">请上传大小不高于2M的jpg/png格式图片</span>
        </div>
    </div>
	
	<input type="hidden" value="size_width" id="size_width">
	<input type="hidden" value="size_height" id="size_height">
    <div class="tr container" style="margin-left: 300px;" id="img_show"></div>
    
    <script type="text/javascript">
	var tmp = $('<div class="resizer">'+
			      '<div id="ad_img" style="width: 100px;height: 100px;border: 1px solid #ccc;">'+
				  '<img src="<?php echo isset($adinfo['content'])?$adinfo['content']:'';?>" style="width:auto;height:auto;max-width:100%;max-height:100%;">'+
				  '</div>'+
			  '</div>');

	$.imageResizer=function(){
		if(Uint8Array&&HTMLCanvasElement&&atob&&Blob){
		
		}else{
			return false;
		}

    	var resizer=tmp.clone();
		resizer.image=resizer.find('img')[0];
		resizer.frames=resizer.find('.frames');
		resizer.okButton=resizer.find('input.ok');
		resizer.frames.offset={
			top:0,
			left:0
		};
		resizer.okButton.click(function(){
			resizer.clipImage();
		});
		resizer.clipImage=function(){
			var nh=this.image.naturalHeight,
			nw=this.image.naturalWidth;
		//	size=nw>nh?nh:nw;
		//	size=size>1000?1000:size;
			var canvas=$('<canvas width="'+nw+'" height="'+nh+'"></canvas>')[0],
			ctx=canvas.getContext('2d'),
			scale=nw/this.offset.width,
			x=this.frames.offset.left*scale,
			y=this.frames.offset.top*scale,
			w=this.frames.offset.size*scale,
			h=this.frames.offset.size*scale;
			ctx.drawImage(this.image,x,y,w,h,0,0,nw,nh);
			var src=canvas.toDataURL();
			this.canvas=canvas;
			this.append(canvas);
			this.addClass('uploading');
			this.removeClass('have-img');
			src=src.split(',')[1];
			if(!src)return this.doneCallback(null);
			src=window.atob(src);
			var ia = new Uint8Array(src.length);
			for (var i = 0; i < src.length; i++) {
				ia[i] = src.charCodeAt(i);
			};
			this.doneCallback(new Blob([ia], {type:"image/png"}));
		};

		resizer.resize=function(file,done){
			this.reset();
			this.doneCallback=done;
			this.setFrameSize(0);
			this.frames.css({
				top:0,
				left:0
			});

	        var reader=new FileReader();
	
	        reader.onload=function(){
				resizer.image.src=reader.result;
				reader=null;
				resizer.addClass('have-img');
				resizer.setFrames();
			};
			reader.readAsDataURL(file);
		};
	
		resizer.reset=function(){
			this.image.src='';
			this.removeClass('have-img');
			this.removeClass('uploading');
			this.find('canvas').detach();
		};

		resizer.setFrameSize=function(size){
			this.frames.offset.size=size;
			return this.frames.css({
				width:size+'px',
				height:size+'px'
			});
		};
	
		resizer.getDefaultSize=function(){
			var width=this.find(".inner").width(),
			height=this.find(".inner").height();
			this.offset={
				width:width,
				height:height
			};
			return width>height?height:width;
	
	    };
	
	    resizer.moveFrames=function(offset){
			var x=offset.x,
			y=offset.y,
			top=this.frames.offset.top,
			left=this.frames.offset.left,
			size=this.frames.offset.size,
			width=this.offset.width,
			height=this.offset.height;
	        if(x+size+left>width){
				x=width-size;
			}else{
				x=x+left;
			};
	
	        if(y+size+top>height){
				y=height-size;
			}else{
				y=y+top;
			};
	
	        x=x<0?0:x;
	
	        y=y<0?0:y;
	
	        this.frames.css({
				top:y+'px',
				left:x+'px'
			});
	
	        this.frames.offset.top=y;
	
	        this.frames.offset.left=x;
		};
	
	    (function(){
			var time;
			function setFrames(){
				var size=resizer.getDefaultSize();
				resizer.setFrameSize(size);
			};
	
	        window.onresize=function(){
				clearTimeout(time)
				time=setTimeout(function(){
					setFrames();
				},1000);
			};
	
	        resizer.setFrames=setFrames;
		})();
	
	    (function(){
			var lastPoint=null;
			function getOffset(event){
				event=event.originalEvent;
				var x,y;
				if(event.touches){
					var touch=event.touches[0];
					x=touch.clientX;
					y=touch.clientY;
				}else{
					x=event.clientX;
					y=event.clientY;
				}
	
	            if(!lastPoint){
					lastPoint={
						x:x,
						y:y
					};
				};
	
	            var offset={
					x:x-lastPoint.x,
					y:y-lastPoint.y
				}
	
	            lastPoint={
					x:x,
					y:y
				};
	
	            return offset;
			};
	
	        resizer.frames.on('touchstart mousedown',function(event){
				getOffset(event);
			});
	
	        resizer.frames.on('touchmove mousemove',function(event){
				if(!lastPoint)return;
				var offset=getOffset(event);
				resizer.moveFrames(offset);
			});
	
	        resizer.frames.on('touchend mouseup',function(event){
				lastPoint=null;
			});
		})();
	
	    return resizer;
	};

	var resizer=$.imageResizer(),
	
	resizedImage;

	if(!resizer){
		resizer=$("<p>Your browser doesn't support these feature:</p><ul><li>canvas</li><li>Blob</li><li>Uint8Array</li><li>FormData</li><li>atob</li></ul>")   
	};

	$('.container').append(resizer);

	$('#fileField').change(function(event){
		var file=this.files[0];
		resizer.resize(file,function(file){

		var fd=new FormData();
        fd.append('image_file',file,"image.png");

		var xhr = new XMLHttpRequest();
		xhr.open('POST', '/adinfo/test', true);
		xhr.send(fd);
		});
		$('.container').show();

	});

	$('button.submit').click(function(){
		var url=$('input.url').val();
		if(!url||!resizedFile)return;
		var fd=new FormData();
		fd.append('file',resizedFile);

    	$.ajax({
			type:'POST',
			url:url,
			data:fd
		});
	});

	</script>
    
    <div class="tr">
        <div class="td-2 txtGt">广告描述：</div>
        <div class="td-4" style="line-height: 14px;">
            <span class="input-sm">
                <input type="text" name="comment" id="comment" onblur="check(this)" value="<?php echo isset($adinfo['comment'])?$adinfo['comment']:'';?>">
            </span>
        </div>
    </div>
    <div class="tr">
        <div class="td-2 txtGt">链接地址：</div>
        <div class="td-4" style="line-height: 14px;">
            <span class="input-sm">
                <input type="text" name="link" id="link" onblur="check(this)" value="<?php echo isset($adinfo['link'])?$adinfo['link']:'';?>">
            </span>
        </div>
        <div class="td-6"><p class="gray9">http://www.stormad.cn</p></div>
    </div>
    <div class="tr">
        <div class="td-2 txtGt">投放时间：</div>
        <div class="td-4" style="line-height: 14px;"><span class="input-sm"><input type="text" name="start_time" id="start_time" onblur="check(this)" value="<?php echo (isset($adinfo['start_time']) && $adinfo['start_time'])?date('Y-m-d H:i:s', $adinfo['start_time']):'';?>" placeholder="开始时间"></span></div>
        <div class="td-4" style="line-height: 14px;"><span class="input-sm"><input type="text" name="end_time" id="end_time" onblur="check(this)" value="<?php echo (isset($adinfo['end_time']) && $adinfo['end_time'])?date('Y-m-d H:i:s', $adinfo['end_time']):'';?>" placeholder="结束时间"></span></div>
    </div>
    <h6>第三步：选择用户群</h6>
    <div class="tr">
     
      <div class="td-2 txtGt">用户群：</div>
      <div class="td-4">
         <div class="select left select_customer" style="z-index:9;">
              <p class="fy"> <span><?php echo isset($adinfo['customer_name'])?$adinfo['customer_name']:'选择用户群';?></span> <i class="iF iF-arrdown right"></i></p>
              <input type="hidden" value="<?php echo isset($adinfo['customer_id'])?$adinfo['customer_id']:'';?>" name="select_customer" id="select_customer"/>
              <ol class="option" id="customer_li">
                <li>请选择</li>
                <?php foreach ($customer_name as $k=>$val){
                  if($val['customer_name']=='默认用户群'){?>
                    <li val="<?php echo $val['id']; ?>"><?php echo $val['customer_name']; ?></li>
                <?php }}foreach ($customer_name as $k=>$val){
                  if($val['customer_name']!='默认用户群'){?>
                    <li val="<?php echo $val['id']; ?>"><?php echo $val['customer_name']; ?></li>
                <?php }}?>
              </ol>
            </div>
      </div>      
    </div>
    
    <h6>第四步：选择定位信息</h6>
    <div class="tr">
      <div class="td-2 txtGt">选择定位：</div>
      <div class="td-4">
              <div id="addef">
					<span id="area" style="cursor:pointer;">添加地区</span>
                    <?php if(isset($val_region)){foreach($val_region as $k=>$val){ ?>
                    <div class="region area_region">
                        <input id="ciy" type="hidden" value="<?php echo $val;?>" name="area[]" />
                        <span class="ciy"><?php echo $show_region[$k];?></span>
                        <div id="cori" class="cori" onclick="colqu(this)">x</div>
                    </div>
                    <?php }} ?>
            	</div>
      </div>      
    </div>
    
    <h6>第五步：消费人群</h6>
    <div class="tr">
      <div class="td-2 txtGt">消费金额：</div>
      <div class="td-4" style="line-height: 14px;">
        <span class="input-sm">
          <input type="text" name="min_pay" id="min_pay" onblur="check(this)" value="<?php echo (isset($adinfo['min_pay']) && $adinfo['min_pay'])?$adinfo['min_pay']:'';?>" placeholder="0.00">
        </span>
      </div>
      <div class="td-6"><p class="gray9">用户人群的最低消费金额</p></div>    
    </div>
    
    <h6>第六步：出价</h6>
    <div class="tr">
      
      <div class="td-2 txtGt">价格：</div>
      <div class="td-4" style="line-height: 14px;">
        <span class="input-sm">
          <input type="text" name="price" id="price" onblur="check(this)" value="<?php echo (isset($adinfo['price']) && $adinfo['price'])?sprintf("%1\$.2f", $adinfo['price']/100):'';?>" placeholder="0.00">
        </span>
      </div>
      <div class="td-6"><p class="gray9">计算出价格，价格可以修改</p></div>   
    </div>
  </div>
  <input type="hidden" name="adgroup_url" value="<?php echo $adgroup_url; ?>"/>
  <input type="hidden" name="campaign_url" value="<?php echo $campaign_url; ?>"/>
  <input type="hidden" name="status" value="<?php echo isset($adinfo['status'])?$adinfo['status']:'';?>">
  <input type="hidden" name="id" value="<?php echo isset($adinfo['id'])?$adinfo['id']:'';?>">
  </form>
  <div class="box" style="padding:20px 0; background-color:#eee;"><div class="tr">
      <div class="td-2">&nbsp;</div>
      <div class="td-10">
         <button class="btn-gray btn-lg-pdlg" onclick="javascript:location.href='/adinfo/adinfo_list'">返回</button> &nbsp;
         <button class="btn-cyan btn-lg-pdlg" onclick="add_adinfo()">完成</button>
      </div>
    </div></div>
</div>

<script>
$(document).ready(function() {
	
		$("#area").click(function (e) {
	SelCity(this,e);
});

	$('.select_campaign').Gfselect({
		toValFn:false,
		valFn:function(a,b,c,d){
			check_campaign(d);
		}
	});

	$('.select_customer').Gfselect({
		toValFn:false,
	});

	<?php if(isset($adinfo['type']) && $adinfo['type']) {?>
		select_type(<?php echo (isset($adinfo['type']) && $adinfo['type'])?$adinfo['type']:'';?>);
    <?php }?>
    <?php if(isset($adinfo['campaign_id']) && $adinfo['campaign_id']) {?>
    	default_campaign(<?php echo $adinfo['campaign_id']; ?>);
    <?php }?>

    <?php if(!isset($adinfo['campaign_id']) && isset($campaign_url) && $campaign_url){?>
        check_campaign(<?php echo $campaign_url; ?>);
    <?php }elseif(!isset($adinfo['campaign_id']) && isset($adgroup['campaign_id']) && $adgroup['campaign_id']){?>
    	$('#adgroup_name').html('<?php echo $adgroup['adgroup_name']?>');
    	$('#select_adgroup').val(<?php echo $adgroup['id']?>);
    <?php }?>

    get_size(<?php echo (isset($adinfo['size_id']) && $adinfo['size_id'])?$adinfo['size_id']:'';?>);
});

var start = {
    elem: '#start_time',
    format: 'YYYY/MM/DD hh:mm:ss',
    min: laydate.now(), //设定最小日期为当前日期
    max: '2099-06-16 23:59:59', //最大日期
    istime: true,
    istoday: false,
    choose: function(datas){
         end.min = datas; //开始日选好后，重置结束日的最小日期
         end.start = datas //将结束日的初始值设定为开始日
         check($('#start_time'));
    }
};
var end = {
    elem: '#end_time',
    format: 'YYYY/MM/DD hh:mm:ss',
    min: laydate.now(),
    max: '2099-06-16 23:59:59',
    istime: true,
    istoday: false,
    choose: function(datas){
        start.max = datas; //结束日选好后，重置开始日的最大日期
        check($('#end_time'));
    }
};
laydate(start);
laydate(end);

function select_type(type) {
	if(type==1){
		$('#fileField').show();
		$('#file_button').show();
		$('#img_show').show();
		$('#content').attr('readonly', 'readonly');
	}else{
		$('#fileField').hide();
		$('#file_button').hide();
		$('#img_show').hide();
		$('#content').removeAttr('readonly');
	}
	$('#ad_type').show();
	size_id = '<?php echo isset($adinfo['size_id'])?$adinfo['size_id']:''?>';
	
	$.ajax({
		type: 'POST',
		url: '/adinfo/find_type_size/'+type,
		dataType: 'json',
		success: function (msg) {
			var res = msg;
			var checked = new Array();
			
			$('#size_id').empty();
			for(var i in res) {
				checked[i] = (size_id==res[i]['id']?"checked='checked'":"");
				
				$('#size_id').append("<p><label><span class='radio'> <input type='radio' onclick='get_size(this.value)' name='size_id' value="+res[i]['id']+" id='ad_size"+res[i]['id']+"' "+checked[i]+"><i></i> </span> <b>"+res[i]['size_name']+"</b></label></p>");
			}
		}
	});
}

function get_size(size_id){
	$.ajax({
		type: 'POST',
		url: '/adinfo/find_size/'+size_id,
		dataType: 'json',
		success: function (msg) {
			if(msg){
				$('#ad_img').css({width:msg['width'],height:msg['height']});
				$('#size_width').val(msg['width']);
				$('#size_height').val(msg['height']);
			}
		}
	});
}

function check(obj) {
	var val = $(obj).val();
	var link = $('#link').val();
	var reg_url = /^\s*http(s)?:\/\/(.)*\s*$/;
	
	if(!val) {
		$(obj).parent('span').attr('class', 'input-sm input-tip-err');
	}else{
		if($(obj).attr('id')=='price'){
			if(isNaN($('#price').val())) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('价格必须是数字');
			}else if($('#price').val()<0.7) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('价格最少要输入0.7元');
			}else if($('#price').val()>1000000) {
				$(obj).parent('span').attr('class', 'input-sm input-tip-err');
				pop_up('价格最多可输入1000000元');
			}else{
				$(obj).parent('span').attr('class', 'input-sm input-tip-ok');
			}
		}else if($(obj).attr('id')=='link'){
        	if(link.search(reg_url)){
        	        $(obj).parent('span').attr('class', 'input-sm input-tip-err');
        	        pop_up('请输入完整链接地址，完整地址需以http://或https://开头');
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

function default_campaign(campaign_id) {
	$.ajax({
		type: 'POST',
		url: '/adinfo/get_ajax_adgroup/'+campaign_id,
		dataType: 'json',
		success: function (msg) {
			var res = msg;
            
			$('#adgroup').empty();
    		$('#select_adgroup').val('<?php echo isset($adinfo['adgroup_id'])?$adinfo['adgroup_id']:'';?>');
    		$('#adgroup_name').html('<?php echo isset($adinfo['adgroup_name'])?$adinfo['adgroup_name']:'请选择推广组';?>');
			$('#adgroup').append("<li>请选择</li>");
			for(var i in res) {
				if(res[i]['adgroup_name']=='默认推广组'){
					$('#adgroup').append("<li val="+res[i]['id']+">"+res[i]['adgroup_name']+"</li>");
				}
			}
			for(var i in res) {
				if(res[i]['adgroup_name']!='默认推广组'){
					$('#adgroup').append("<li val="+res[i]['id']+">"+res[i]['adgroup_name']+"</li>");
				}
			}		
	        $('.select_adgroup').Gfselect({
                toValFn:false
            });
		}
	});
}
function check_campaign(campaign_id) {
	$.ajax({
		type: 'POST',
		url: '/adinfo/get_ajax_adgroup/'+campaign_id,
		dataType: 'json',
		success: function (msg) {
			var res = msg;
            
			$('#adgroup').empty();
    		$('#select_adgroup').val('');
    		$('#adgroup_name').html('请选择推广组');
			$('#adgroup').append("<li>请选择</li>");
			for(var i in res) {
				if(res[i]['adgroup_name']=='默认推广组'){
					$('#adgroup').append("<li val="+res[i]['id']+">"+res[i]['adgroup_name']+"</li>");
				}
			}
			for(var i in res) {
				if(res[i]['adgroup_name']!='默认推广组'){
					$('#adgroup').append("<li val="+res[i]['id']+">"+res[i]['adgroup_name']+"</li>");
				}
			}		
	        $('.select_adgroup').Gfselect({
                toValFn:false
            });
		}
	});
}

function add_adinfo() {
	var link = $('#link').val();
	var campaign_li = $('#campaign_li').find('li').length;
	var reg_url = /^\s*http(s)?:\/\/(.)*\s*$/;
	
	if(campaign_li<=1) {
		layer.msg("你没有推广计划，是否去添加？", {
		    time: 0 //不自动关闭
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		    	location.href='/campaign/add_campaign';
		    }
		});
		return false;
	}
	if(!$('#select_campaign').val() || $('#select_campaign').val()==0) {
		pop_up('请选择目标推广计划');
		return false;
	}
	var adgroup = $('#adgroup').find('li').length;
	if(adgroup<=1 && !$('#select_adgroup').val()) {
		layer.msg("该推广计划下没有推广组，是否去添加？", {
		    time: 0 //不自动关闭
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		    	location.href='/adgroup/add_adgroup/0/'+$('#select_campaign').val();
		    }
		});
		return false;
	}
	if(!$('#select_adgroup').val() || $('#select_adgroup').val()==0) {
		pop_up('请选择目标推广组');
		return false;
	}
	if(!$('#title').val()) {
		pop_up('请输入广告标题');
		return false;
	}
	var type = $('#type').find('input[type=radio]').is(':checked');
	if(!type){
		pop_up('请选择广告类型');
		return false;
	}
	var size_id = $('#size_id').find('input[type=radio]').is(':checked');
    if(!size_id){
    	pop_up('请选择广告规格');
		return false;
    }
	if(!$('#content').val()) {
		pop_up('请上传图片或输入文字素材');
		return false;
	}
	if(!$('#comment').val()) {
		pop_up('请输入广告描述');
		return false;
	}
	if(!$('#link').val()) {
		pop_up('请输入链接地址');
		return false;
	}
	if(link.search(reg_url)){
		pop_up('请输入完整链接地址，完整地址需以http://或https://开头');
		return false;
	}
	if(!$('#start_time').val()) {
		pop_up('请选择投放开始时间');
		return false;
	}
	if(!$('#end_time').val()) {
		pop_up('请选择投放结束时间');
		return false;
	}
	var customer_li = $('#customer_li').find('li').length;
	if(customer_li<=1) {
		layer.msg("你没有用户群，是否去添加？", {
		    time: 0 //不自动关闭
		    ,btn: ['确定', '取消']
		    ,yes: function(index){
		    	location.href='/customer/add_customer';
		    }
		});
		return false;
	}
	if(!$('#select_customer').val()) {
		pop_up('请选择用户群');
		return false;
	}
	if(!$('#price').val()) {
		pop_up('请输入广告价格');
		return false;
	}
	if(isNaN($('#price').val())) {
		pop_up('价格必须是数字');
		return false;
	}
	if($('#price').val()<0.7) {
		pop_up('价格最少要输入0.7元');
		return false;
	}
	if($('#price').val()>1000000) {
		pop_up('最多可输入1000000元');
		return false;
	}
	
	$.ajax({
		type: 'POST',
		url: '/adinfo/get_adinfo_num/',
		dataType: 'json',
		success: function (msg) {
			var res = msg;
			
            if(res>=200){
            	<?php if(!isset($adinfo['id'])){?>
            		pop_up('广告数不可以超过200个');
            	<?php }else{?>
            		$('#adinfo_submit').submit();
            	<?php }?>
            }else{
            	$('#adinfo_submit').submit();
            }
		}
	});
}
</script>
