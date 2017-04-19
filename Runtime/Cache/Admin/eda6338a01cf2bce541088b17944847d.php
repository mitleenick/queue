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
      <th>窗口名字</th>  
      <th>分公司</th>  
      <th>业务</th>  
      <th>窗口编号</th>  
      <th>工作人员</th>   
      <th width="250">操作</th>
    </tr>
 <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
      <td><?php echo ($vo["id"]); ?></td>      
      <td><?php echo ($vo["name"]); ?></td>  
      <td><?php echo ($vo["company"]); ?></td>  
      <td><?php echo ($vo["bussiness"]); ?></td> 
      <td><?php echo ($vo["windownum"]); ?></td> 
      <td><?php echo ($vo["uid"]); ?></td>      
      <td>
      <div class="button-group">
      <a type="button" class="button border-main" href="#"><span class="icon-edit"></span>修改</a>
      </div>
      </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>     
    
  </table>
</div>

<div class="panel admin-panel margin-top">
  <div class="panel-head" id="add"><strong><span class="icon-pencil-square-o"></span>添加窗口</strong></div>
  <div class="body-content">
    <form method="post" class="form-x" action="<?php echo U('setWindows');?>">   
      <input type="hidden" name="id"  value="" />  
      <div class="form-group">
        <div class="label">
          <label>窗口名字：</label>
        </div>
        <div class="field">
		<input name="name" type="text" id="control_date" size="20" maxlength="20"  />		
      </div> 
      </div>
      <div class="form-group">
        <div class="label">
          <label>所属分公司：</label>
        </div>
        <div class="field">
        <select name='company'>
        <?php if(is_array($company)): $i = 0; $__LIST__ = $company;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		</select>
      </div> 
      </div>
      <div class="form-group">
        <div class="label">
          <label>办理业务：</label>
        </div>
        <div class="field">
		<select name='bussiness'>
		<?php if(is_array($bussiness)): $i = 0; $__LIST__ = $bussiness;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		</select>
      </div> 
      </div> 
      <div class="form-group">
        <div class="label">
          <label>窗口号：</label>
        </div>
        <div class="windowNum">
		<input name="pmendtime" type="text" id="control_date" size="20" maxlength="20" />	
      </div> 
      </div>
      <div class="form-group">
        <div class="label">
          <label>该窗口工作人员：</label>
        </div>
        <div class="field">
		<select name='uid'>
		<?php if(is_array($admin)): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
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