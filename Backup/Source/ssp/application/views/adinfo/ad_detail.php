<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

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
  <div class=" addnew box">    
     <h6 class="td-12">广告信息</h6>
     <div class="tr">
         <div class="td-2 txtGt">广告标题：</div>
         <div class="td-4">
             <b><?php echo $adinfo['title'];?></b>
         </div>
     </div>
     <div class="tr">
         <div class="td-2 txtGt">广告类型： </div>
         <div class="td-4">
			<?php if($adinfo['type']==1) {?><b>图片</b><?php }else{ ?><b>文字</b><?php } ?>                                              
         </div>
     </div>
	<?php if($adinfo['type']==2) { ?>
    <div class="tr">
         <div class="td-2 txtGt">广告内容： </div>
         <div class="td-4">
			<b><?php echo isset($adinfo['content'])?$adinfo['content']:'';?></b>                                             
         </div>
     </div>
    <?php } ?>
    
    <?php if($adinfo['type']==1) { ?>
	<div class="tr">
         <div class="td-2 txtGt">广告图片： </div>
         <div class="td-4">
			<b><a href="http://bp.stormad.cn/<?php echo $adinfo['content']; ?>" target="_blank" title="查看原图"><img src="http://bp.stormad.cn/<?php echo $adinfo['content']; ?>" style="width:300px; height:167px;" /></a></b>                                             
         </div>
     </div>
    <?php } ?>
    
    <div class="tr">
        <div class="td-2 txtGt">广告描述：</div>
        <div class="td-4">
            <span class="input-sm">
                <b><?php echo $adinfo['comment']; ?></b>
            </span>
        </div>
    </div>
    <div class="tr">
        <div class="td-2 txtGt">链接地址：</div>
        <div class="td-4">
            <span class="input-sm">
                <b><a href="<?php echo $adinfo['link']; ?>" target="_blank" title="预览" ><?php echo $adinfo['link']; ?></a></b>
            </span>
        </div>
    </div>
    <div class="tr">
        <div class="td-2 txtGt">投放时间：</div>
        <div class="td-4"><span class="input-sm"><b><?php echo date('Y-m-d H:i:s', $adinfo['start_time']); ?>---<?php echo (isset($adinfo['end_time']) && $adinfo['end_time'])?date('Y-m-d H:i:s', $adinfo['end_time']):'';?></b></span></div>
    </div>
  </div>
  <div class="box" style="padding:20px 0; background-color:#eee;"><div class="tr">
      <div class="td-2">&nbsp;</div>
      <div class="td-10">
         <button class="btn-gray btn-lg-pdlg" onclick="javascript:location.href='/adinfo/adinfo_list'">返回</button> &nbsp;
      </div>
    </div></div>
</div>