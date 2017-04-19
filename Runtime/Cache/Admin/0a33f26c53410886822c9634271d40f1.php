<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>网站信息</title>  
    <link rel="stylesheet" href="/Public/css/pintuer.css">
    <link rel="stylesheet" href="/Public/css/admin.css">
    <script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/pintuer.js"></script>  
</head>
<body>
<div class="panel admin-panel">
  <div class="panel-head"><strong class="icon-reorder"> 内容列表</strong></div>
  <div class="padding border-bottom">  
  <a class="button border-yellow" href=""><span class="icon-plus-square-o"></span> 添加内容</a>
  </div> 
  <table class="table table-hover text-center">
    <tr>
      <th width="5%">ID</th>     
      <th>业务名称</th>  
      <th>排序</th>     
    </tr>
<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
      <td><?php echo ($vo["id"]); ?></td>      
      <td><?php echo ($vo["name"]); ?></td>  
      <td><input type="text" id='sort<?php echo ($vo["id"]); ?>' name="sort<?php echo ($vo["id"]); ?>"  value="<?php echo ($vo["sort"]); ?>" />
      <a style='margin-left:10px' id="<?php echo ($vo["id"]); ?>" href='#' onClick='updatesort(this.id,<?php echo ($vo["id"]); ?>)'>更新</a></td>      
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>   
  </table>
</div>

<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>增加内容</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="<?php echo U('addBussiness');?>">   
      <input type="hidden" name="id"  value="" />  
      <div class="form-group">
        <div class="label">
          <label>业务名称：</label>
        </div>
        <div class="field">
          <input type="text" class="input w50" name="title" value="" data-validate="required:请输入标题" />         
          <div class="tips"></div>
        </div>
      </div> 
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