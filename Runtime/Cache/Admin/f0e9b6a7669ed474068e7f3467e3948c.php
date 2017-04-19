<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>系统设置</title>  
    <link rel="stylesheet" href="/Public/css/pintuer.css">
    <link rel="stylesheet" href="/Public/css/admin.css">
    <script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/pintuer.js"></script> 
    <script src="/Public/js/jscolor.js"></script>  
</head>
<body>
<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>系统参数设置</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="<?php echo U('setSystem');?>">   
      <input type="hidden" name="id"  value="" />  
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="form-group">
        <div class="label">
          <label>呼叫机呼叫次数：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="maxCall" value="<?php echo ($vo["maxcall"]); ?>" data-validate="required:请输入呼叫次数" />         
          <div class="tips"></div>
        </div>
        <br>
         <br>
        <div class="label">
          <label>排队机字体大小：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="fontSize" value="<?php echo ($vo["fontsize"]); ?>" data-validate="required:请输入大小" />字体为排队机默认字体倍数0.5--3.0,请设置中间值        
          <div class="tips"></div>
        </div>
         <br>
        <div class="label">
          <label>排队机字体颜色：</label>
        </div>
        <div class="field">
          <input class="jscolor input w50" name='fontColor' value="<?php echo ($vo["fontcolor"]); ?>" />         
          <div class="tips"></div>
        </div>

      <div class="label">
          <label>排队机字体：</label>
        </div>
        <div class="field" class="input w50">
          <select name='fontType'>
	          <option value='huawenxinsong' <?php if($vo["fonttype"] == 'huawenxingsong'): ?>checked<?php endif; ?>>华文行宋</option>
	          <option value='huawenxinwei' <?php if($vo["fonttype"] == 'huawenxingwei'): ?>checked<?php endif; ?>>华文行魏</option>
	          <option value='huawenxinkai' <?php if($vo["fonttype"] == 'huawenxingkai'): ?>checked<?php endif; ?>>华文行楷</option>
	          <option value='youyuan' <?php if($vo["fonttype"] == 'youyuan'): ?>checked<?php endif; ?>>幼圆</option>
          </select>        
          <div class="tips"></div>
        </div>
     </div><?php endforeach; endif; else: echo "" ;endif; ?> 
     <div class="form-group">
        <div class="label">
          <label></label>
        </div>
        <div class="field">
          <button class="button bg-main icon-check-square-o" type="submit"> 提交</button>
        </div>
      </div>
    </form>
  </div>
</div>
</body></html>
<script>
function del(id){
	if(confirm("您确定要删除吗?")){
		
	}
}
function updatesort(id,bid){
	var sort = $("#sort"+id).val();
	$.ajax({    
	    url:'/index.php?m=Admin&c=Bussiness&a=updateSort',  
	    data:{id:bid,sort:sort},    
	    type:'post',    
	    cache:false,    
	    dataType:'',    
	    success:function(data) {
	    	if(data=='-1'){
	    		alert('未更新或更新失败');
	    	}
	    	if(data=='ok'){
	    		alert('更新成功');
	    	}
	    	location.reload();
	     },    
	     error : function() {
	        alert(data);
	     }    
	});
}
	
</script>