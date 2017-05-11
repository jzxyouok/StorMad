<!DOCTYPE html>
<html lang="en">
  <head>
  <title><?php echo $layout['title'];?></title>
  
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/global.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/mod.css">
    
    <script src="<?php echo base_url(); ?>/js/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>/js/base.js"></script>
  
  </head>
  <body>
    <!--Top start-->
    <div id="head">
      <div class="top">
        <h1 class="logo"><a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>/images/logo.png"></a></h1>
        <div class="logoarea"> <span class="userpic"><img src="<?php echo base_url(); ?>/images/user.jpg" ></span> <span class="welcome">您好，<em class="white"><?php echo $this->session->userdata('user_name'); ?></em>，欢迎您使用飞猪业务支撑系统！</span> </div>
        <div class="topGt">
          <ul>
            <li><i class="iF iF-home"></i><a href="<?php echo base_url(); ?>">系统主页</a></li>
            <li><i class="iF iF-help"></i><a href="">帮助中心</a></li>
            <li><i class="iF iF-exit"></i><a href="javascript:logout()">退出系统</a></li>
          </ul>
        </div>
      </div>
      <div class="breadNav clearFix">
        <div class="con"> <a href="<?php echo base_url(); ?>">主页</a> &gt; <a href="<?php echo '/'.uri_string(); ?>"><?php echo $layout['title'];?></a> </div>
      </div>
    </div>
    <!--Top end-->
    <!--Lt start-->
    <div id="Lt">
      <div class="LtCon">
        <div id="ltNav">
          <ul>
            <li <?php if($layout['controller']=='ad'){?>class="on"<?php }?>><a href="javascript:location.href='/ad/ad_list';"><i class="iF iF-audit"></i> <em>广告审核</em></a></li>
            <li <?php if($layout['controller']=='user'){?>class="on"<?php }?>><a href="javascript:location.href='/user/user_list';"><i class="iF iF-advertiser"></i> <em>广告主管理</em></a></li>
            <li <?php if($layout['controller']=='channel_user'){?>class="on"<?php }?>><a href="javascript:location.href='/channel_user/user_list';"><i class="iF iF-advertiser"></i> <em>渠道管理</em></a></li>
            <li <?php if($layout['controller']=='admin_log'){?>class="on"<?php }?>><a href="javascript:location.href='/admin_log/ad_log';"><i class="iF iF-logs"></i> <em>广告操作日志</em></a></li>
            <li <?php if($layout['controller']=='ad_area'){?>class="on"<?php }?>><a href="javascript:location.href='/ad_area/area_list';"><i class="iF iF-AdP"></i> <em>广告位管理</em></a></li>
            <li <?php if($layout['controller']=='ad_scene'){?>class="on"<?php }?>><a href="javascript:location.href='/ad_scene/scene_list';"><i class="iF iF-tags"></i> <em>标签管理</em></a></li>
            <li <?php if($layout['controller']=='ad_size'){?>class="on"<?php }?>><a href="javascript:location.href='/ad_size/size_list';"><i class="iF iF-norms"></i> <em>规格管理</em></a></li>
            <li <?php if($layout['controller']=='admin_user'){?>class="on"<?php }?>><a href="javascript:location.href='/admin_user/admin_list';"><i class="iF iF-advertiser"></i> <em>管理员</em></a></li>
          </ul>
        </div>
      </div>
    </div>
    <!--Lt end-->
    <!--Rt start-->
    <div id="gt" style="left:74px;">
        <?php echo $content;?>
    </div>
    <!--Rt end-->
    
    <script src="<?php echo base_url(); ?>/js/layer/layer.js"></script>
    <script src="<?php echo base_url(); ?>/js/laydate/laydate.js"></script>
    <!--分页插件--> 
    <script src="<?php echo base_url(); ?>/js/laypage/laypage.js"></script> 
    <script src="<?php echo base_url(); ?>/js/giaf.select.js"></script>
    
    <script>
    	function logout(){
    		layer.msg("你确定要退出系统么？", {
    		    time: 0 //不自动关闭
    		    ,btn: ['确定', '取消']
    		    ,yes: function(index){
    		    	location.href = '/login/admin_logout';
    		    }
    		});
    	}
    </script>
    
  </body>
</html>
