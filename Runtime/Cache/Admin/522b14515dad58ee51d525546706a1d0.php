<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>营业时间设置</title>  
    <link rel="stylesheet" href="/Public/css/pintuer.css">
    <link rel="stylesheet" href="/Public/css/admin.css">
    <script src="/Public/js/jquery.js"></script>
    <script src="/Public/js/pintuer.js"></script>
    <script src="/Public/js/Calendar.js"></script>
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
      <th>上午营业时间</th>  
      <th>下午营业时间</th>  
      <th>排序</th>     
      <th width="250">操作</th>
    </tr>
 <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
      <td><?php echo ($vo["id"]); ?></td>      
      <td><?php echo ($vo["amstarttime"]); ?>-<?php echo ($vo["amendtime"]); ?></td>  
      <td><?php echo ($vo["pmstarttime"]); ?>-<?php echo ($vo["pmendtime"]); ?></td>  
      <td>1</td>      
      <td>
      <div class="button-group">
      <a type="button" class="button border-main" href="#"><span class="icon-edit"></span>修改</a>
       <a class="button border-red" href="javascript:void(0)" onclick="return del(17)"><span class="icon-trash-o"></span> 删除</a>
      </div>
      </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>     
    
  </table>
</div>

<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>设置营业时间</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="<?php echo U('addTime');?>">   
      <input type="hidden" name="id"  value="" />  
      <div class="form-group">
        <div class="label">
          <label>上午开始时间：</label>
        </div>
        <div class="field">
		<input name="amstime" type="text" id="control_date" size="20" maxlength="20" onclick="new Calendar().show(this,null,1);" />		
      </div> 
      </div>
      <div class="form-group">
        <div class="label">
          <label>上午结束时间：</label>
        </div>
        <div class="field">
		<input name="amendtime" type="text" id="control_date" size="20" maxlength="20" onclick="new Calendar().show(this,null,1);" />		
      </div> 
      </div>
            <div class="form-group">
        <div class="label">
          <label>下午开始时间：</label>
        </div>
        <div class="field">
		<input name="pmstime" type="text" id="control_date" size="20" maxlength="20" onclick="new Calendar().show(this,null,1);" />		
      </div> 
      </div> 
      <div class="form-group">
        <div class="label">
          <label>下午结束时间：</label>
        </div>
        <div class="field">
		<input name="pmendtime" type="text" id="control_date" size="20" maxlength="20" onclick="new Calendar().show(this,null,1);" />	
      </div> 
      </div>
            <div class="form-group">
        <div class="label">
          <label>分公司：</label>
        </div>
        <div class="field">
		<select name='company'>
		<option value='1'>分公司1</option>
		<option value='2'>分公司2</option>
		<option value='3'>分公司3</option>
		<option value='4'>分公司4</option>
		</select>
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
</script>