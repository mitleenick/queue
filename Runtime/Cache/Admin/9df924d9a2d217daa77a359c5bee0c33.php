<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台管理中心</title>  
    <link rel="stylesheet" href="/Public/css/pintuer.css">
    <link rel="stylesheet" href="/Public/css/admin.css">
    <script src="/Public/js/jquery.js"></script>   
</head>
<body style="background-color:#f2f9fd;">
<div class="header bg-main">
  <div class="logo margin-big-left fadein-top">
    <h1><img src="/Public/images/y.jpg" class="radius-circle rotate-hover" height="50" alt="" />后台管理中心</h1>
  </div>
  <div class="head-l"><a class="button button-little bg-green" href="" target="_blank"><span class="icon-home"></span> 前台首页</a> &nbsp;&nbsp;<a href="##" class="button button-little bg-blue"><span class="icon-wrench"></span> 清除缓存</a> &nbsp;&nbsp;<a class="button button-little bg-red" href="login.html"><span class="icon-power-off"></span> 退出登录</a> </div>
</div>
<div class="leftnav">
  <div class="leftnav-title"><strong><span class="icon-list"></span>排队机系统</strong></div>
    <h2><span class="icon-flash (alias)"></span>用户管理</h2>
   <ul>
    <li><a href="index.php?m=&&m=Admin&c=User&a=vip" target="right"><span class="icon-caret-right"></span>VIP用户</a></li>
    <li><a href="index.php?m=&&m=Admin&c=User&a=common" target="right"><span class="icon-caret-right"></span>普通用户</a></li>
    <li><a href="index.php?m=&&m=Admin&c=User&a=officer" target="right"><span class="icon-caret-right"></span>工作人员</a></li>
   </ul>
  <h2><span class="icon-home"></span>系统统计</h2>
  <ul>
    <li><a href="index.php?m=&&m=Admin&c=Statistics&a=index" target="right"><span class="icon-caret-right"></span>平均办理时间</a></li>
    <li><a href="index.php?m=&&m=Admin&c=Statistics&a=index" target="right"><span class="icon-caret-right"></span>最长最短时间</a></li>
    <li><a href="index.php?m=&&m=Admin&c=Statistics&a=index" target="right"><span class="icon-caret-right"></span>最多呼叫次数</a></li>  
    <li><a href="index.php?m=&&m=Admin&c=Statistics&a=index" target="right"><span class="icon-caret-right"></span>平均呼叫次数</a></li>   
    <li><a href="index.php?m=&&m=Admin&c=Statistics&a=business" target="right"><span class="icon-caret-right"></span>客户业务汇总</a></li>  
  </ul>   
  <h2><span class="icon-pencil-square-o"></span>自动转移</h2>
  <ul>
    <li><a href="index.php?m=&&m=Admin&c=Devolve&a=index" target="right"><span class="icon-caret-right"></span>业务转移</a></li>        
  </ul>
	<h2><span class="icon-user"></span>今日排号</h2>
   <ul>
    <li><a href="index.php?m=&&m=Admin&c=Today&a=queue" target="right"><span class="icon-caret-right"></span>排号队列</a></li>
    <li><a href="index.php?m=&&m=Admin&c=Today&a=abandon" target="right"><span class="icon-caret-right"></span>弃号</a></li>
    <li><a href="index.php?m=&&m=Admin&c=Today&a=specialCall" target="right"><span class="icon-caret-right"></span>特呼</a></li>
    <li><a href="index.php?m=&&m=Admin&c=Today&a=devolve" target="right"><span class="icon-caret-right"></span>转移</a></li>
  </ul>
  <h2><span class="icon-user"></span>历史排号</h2>
   <ul>
    <li><a href="index.php?m=&&m=Admin&c=History&a=queue" target="right"><span class="icon-caret-right"></span>排号统计</a></li>
    <li><a href="index.php?m=&&m=Admin&c=History&a=specialCall" target="right"><span class="icon-caret-right"></span>特呼统计</a></li>
    <li><a href="index.php?m=&&m=Admin&c=History&a=devolve" target="right"><span class="icon-caret-right"></span>转移统计</a></li>
    <li><a href="index.php?m=&&m=Admin&c=History&a=abandon" target="right"><span class="icon-caret-right"></span>弃号统计</a></li>
  </ul>
  <h2><span class="icon-reorder (alias)"></span>业务管理</h2>
   <ul>
    <li><a href="index.php?m=&&m=Admin&c=Bussiness&a=index" target="right"><span class="icon-caret-right"></span>业务管理</a></li>
	<li><a href="index.php?m=&&m=Admin&c=Bussiness&a=window" target="right"><span class="icon-caret-right"></span>窗口管理</a></li>
  </ul>
  <h2><span class="icon-laptop"></span>业务时间设置</h2>
   <ul>
    <li><a href="index.php?m=&&m=Admin&c=Bussiness&a=setTime" target="right"><span class="icon-caret-right"></span>业务时间设置</a></li>
  </ul>
    <h2><span class="icon-group (alias)"></span>评价系统</h2>
   <ul>
    <li><a href="index.php?m=&&m=Admin&c=Evaluate&a=index" target="right"><span class="icon-caret-right"></span>评价管理</a></li>

  </ul>
  <h2><span class="icon-pencil"></span>温馨提示</h2>
   <ul>
    <li><a href="wz-list.html" target="right"><span class="icon-caret-right"></span>内容编辑</a></li>
    <li><a href="cate.html" target="right"><span class="icon-caret-right"></span>语音播报</a></li>        
  </ul>
  <h2><span class="icon-cube"></span>业务数据</h2>
   <ul>
    <li><a href="ddgl-list-5.html" target="right"><span class="icon-caret-right"></span>数据下载</a></li>
    <li><a href="add-product.html" target="right"><span class="icon-caret-right"></span>数据上传</a></li>
  </ul>
  <h2><span class="icon-cube"></span>模块化管理</h2>
   <ul>
    <li><a href="ddgl-list-5.html" target="right"><span class="icon-caret-right"></span>微电脑控制</a></li>
    <li><a href="ddgl-list-5.html" target="right"><span class="icon-caret-right"></span>PC控制</a></li>
  </ul>  
  <h2><span class="icon-cube"></span>系统设置</h2>
   <ul>
    <li><a href="index.php?m=&&m=Admin&c=System&a=setSystem" target="right"><span class="icon-caret-right"></span>系统参数设置</a></li>
  </ul>  

</div>
<script type="text/javascript">
$(function(){
  $(".leftnav h2").click(function(){
	  $(this).next().slideToggle(200);	
	  $(this).toggleClass("on"); 
  })
  $(".leftnav ul li a").click(function(){
	    $("#a_leader_txt").text($(this).text());
  		$(".leftnav ul li a").removeClass("on");
		$(this).addClass("on");
  })
 $(".leftnav").children("ul:last-child").css("padding-bottom","100px");
});
</script>
<ul class="bread">
  <li><a href="<?php echo U('Index/info');?>" target="right" class="icon-home"> 首页</a></li>
  <li><a href="##" id="a_leader_txt">网站信息</a></li>
  <li><b>当前语言：</b><span style="color:red;">中文</php></span>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;切换语言：<a href="##">中文</a> &nbsp;&nbsp;<a href="##">英文</a> </li>
</ul>
<div class="admin">
  <iframe scrolling="auto" rameborder="0" src="info.html" name="right" width="100%" height="100%"></iframe>
</div>
</body>
</html>