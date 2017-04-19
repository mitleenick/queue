<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title></title>  
    <link rel="stylesheet" href="/Public/css/pintuer.css">
    <link rel="stylesheet" href="/Public/css/admin.css">
    <link rel="stylesheet" href="/Public/css/page.css">
    <script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/pintuer.js"></script>  
</head>
<body>
<form method="post" action="">
  <div class="panel admin-panel b-page">
    <div class="panel-head"><strong class="icon-reorder"> 普通用户</strong></div>
    <table class="table table-hover text-center">
      <tr>
        <th width="120">ID</th>
        <th>姓名</th>       
        <th>电话</th>
        <th>其他</th>
        <th>操作</th>       
      </tr> 
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
          <td><input type="checkbox" name="id[]" value="1" />
            <?php echo ($vo["id"]); ?></td>
          <td><?php echo ($vo["name"]); ?></td>
          <td><?php echo ($vo["telephone"]); ?></td>
          <td><?php echo ($vo["address"]); ?></td>         
          <td><div class="button-group"> <a class="button border-red" href="index.php?m=&&m=Admin&c=User&a=delete&id=<?php echo ($vo["id"]); ?>" ><span class="icon-trash-o"></span> 删除</a> </div></td>
        </tr><?php endforeach; endif; else: echo "" ;endif; ?> 
      <tr>
			<td colspan=5><?php echo ($page); ?></td>      
	</tr>
    </table>
  </div>
</form>
<script type="text/javascript">

function del(id){
	if(confirm("您确定要删除吗?")){
		
	}
}

$("#checkall").click(function(){ 
  $("input[name='id[]']").each(function(){
	  if (this.checked) {
		  this.checked = false;
	  }
	  else {
		  this.checked = true;
	  }
  });
})

function DelSelect(){
	var Checkbox=false;
	 $("input[name='id[]']").each(function(){
	  if (this.checked==true) {		
		Checkbox=true;	
	  }
	});
	if (Checkbox){
		var t=confirm("您确认要删除选中的内容吗？");
		if (t==false) return false; 		
	}
	else{
		alert("请选择您要删除的内容!");
		return false;
	}
}

</script>
</body></html>