<!DOCTYPE html>
<html lang="en">
  <head>
  <title><?php echo $layout['title'];?></title>
  
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/global.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/mod.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>/css/inpage.css">
  
  <script src="<?php echo base_url(); ?>/js/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>/js/laydate/laydate.js"></script>
  
  </head>
  <body class="v2" style="height: 633px;">
    <div id="head">
      <div class="top">
        <h1 class="logo"><a href="<?php echo base_url(); ?>"><img src="<?php if($this->session->userdata('type')==4) {echo 'http://bss.stormad.cn/'.$this->session->userdata('logo'); }else{ echo base_url().'/images/logo.png';} ?>"></a></h1>
        <div class="logoarea"> <span class="userpic"><img src="<?php echo base_url(); ?>/images/user.jpg" ></span> <span class="welcome">您好，<em class="white"><?php echo $this->session->userdata('user_name'); ?></em>，欢迎您使用<?php if($this->session->userdata('type')==4){ echo $this->session->userdata('user_name'); }else{?>飞猪<?php } ?>客户系统！</span> </div>
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
    <!--top end-->
    <div id="Lt">
      <div class="LtCon">
        <div id="ltNav">
          <ul>
            <li <?php if($layout['controller']=='' || $layout['controller']=='home'){?>class="on"<?php }?>><a href="<?php echo base_url(); ?>"><i class="iF iF-home"></i> <em>投放系统首页</em></a></li>
            <?php if($this->session->userdata('id')!=10 && $this->session->userdata('id')!=17 && $this->session->userdata('id')!=18 && $this->session->userdata('id')!=20)
			{ ?>
            <li <?php if($layout['controller']=='campaign'){?>class="on"<?php }?>><a href="/campaign/campaign_list"><i class="iF iF-plan"></i> <em>推广计划管理</em></a></li>
            <li <?php if($layout['controller']=='adgroup'){?>class="on"<?php }?>><a href="/adgroup/adgroup_list"><i class="iF iF-fenzu"></i> <em>推广组管理</em></a></li>
            <li <?php if($layout['controller']=='customer'){?>class="on"<?php }?>><a href="/customer/customer_list"><i class="iF iF-users"></i> <em>用户群管理</em></a></li>
            <li <?php if($layout['controller']=='adinfo'){?>class="on"<?php }?>><a href="/adinfo/adinfo_list"><i class="iF iF-AdP"></i> <em>广告管理</em></a></li>
            <li <?php if($layout['controller']=='user_money'){?>class="on"<?php }?>><a href="/user_money/money_list"><i class="iF iF-account"></i> <em>账户中心</em></a></li>
            <li <?php if($layout['controller']=='report'){?>class="on"<?php }?>><a href="/report/report_list"><i class="iF iF-baobiao"></i> <em>报表</em></a></li>
            <li <?php if($layout['controller']=='user_log'){?>class="on"<?php }?>><a href="/user_log/log_list"><i class="iF iF-logs"></i> <em>用户操作日志</em></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <!--Lt end-->
    <div id="gt" style="left:74px;">
      <?php echo $content;?>
    </div>
  
  <script src="<?php echo base_url(); ?>/js/layer/layer.js"></script>
  
  <!--分页插件--> 
  <script src="<?php echo base_url(); ?>/js/laypage/laypage.js"></script> 
  <script src="<?php echo base_url(); ?>/js/giaf.select.js"></script> 
  
  <script>
      function logout(){
    	  layer.msg("你确定要退出系统么？", {
    		  time: 0 //不自动关闭
    		  ,btn: ['确定', '取消']
    		  ,yes: function(index){
    		      location.href = '/login/user_logout';
    		  }
    	  });
      }
  </script>
   
  </body>
</html>
