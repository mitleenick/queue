<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 评分管理
 */
class PjController{
	
	//类初始化方法
	public function _initialize() {	
		$postkey=I('key');
		$key=$this->key();
		if($key!=$postkey){
			$this->result=array('status'=>'003','msg'=>'参数非法');
			echo json_encode($this->result);
			exit;
		}
	}
	
	//接口加密key
	public function key(){
		$formatter = date('dmY',time());
		$trans = array("0" => "d", "1" => "i", "2" => "b", "3" => "o", "4" => "h", "5" => "z", "6" => "g", "7" => "n", "8" => "a", "9" => "y");
		$key = strtr($formatter, $trans);
		$key = md5($key);
		return $key;
	}
		
	/* 添加评分记录
     * Auth   : mit
     * Time   : 
     **/
    public function addPj(){
    	$param['ReserveId']=I('ReserveId');
    	$param['ProjectCode']=I('ProjectCode');
    	$param['ActiveCode']=I('ActiveCode');
    	$param['pj1']=I('pj1');
    	$param['pj2']=I('pj2');
    	$param['Custom']=I('Custom');
    	$param['AddTime']=time();
    	$param['UserCode']=I('UserCode');
    	$param['Remark']=I('Remark');
		
    	if($param['ReserveId']==''||$param['ProjectCode']==''||
		   $param['ActiveCode']==''||$param['pj1']==''||$param['pj2']==''||$param['Custom']==''||$param['UserCode']==''){
		    	$this->result=array('status'=>'-2','msg'=>'必填字段能不能为空','data'=>$param);
		    	echo json_encode($this->result);
		    	exit;
    	}
    	$result=D('Pj')->addPj(array('data'=>json_encode($param,JSON_UNESCAPED_UNICODE)),session('loginCookie'));

    	if($result){
    		$this->result=array('status'=>'0','msg'=>'信息提交成功','data'=>$param);
    		echo json_encode($this->result);
    		exit;
    	}else{
    		$this->result=array('status'=>'-1','msg'=>'数据添加失败','data'=>$param);  
    		echo json_encode($this->result); 
    		exit;
    	}
    }
 //获取评分记录   
    public function getPj(){
    	$post_data = I('post.');
    	$dataType = $post_data['datatype'];
    	$ac = $post_data['AC'];
    	$pc = $post_data['PC'];
    	
    	if ($dataType == 1) {
    		$param['ProjectCode']=$pc;
    		$param['ActiveCode']=$ac;
			if(empty($param)){
				$param=array();
			}
    		$param_new=array("PI"=>I('PI')?I('PI'):"1","PS"=>I('PS')?I('PS'):"20",'data'=>json_encode($param));

    		$result=D('Pj')->getPj($param_new,session('loginCookie'));
    		
    		if($result){
	    		echo json_encode($result[0]); //返回二维数组取[0]
	    		exit;    			
    		}
    	
    	}
    }
//修改评分记录
	public function upPj(){
    	$temp['ReserveId']=I('ReserveId');
    	$temp['ProjectCode']=I('ProjectCode');
    	$temp['ActiveCode']=I('ActiveCode');
    	$temp['pj1']=I('pj1');
    	$temp['pj2']=I('pj2');
    	$temp['Custom']=I('Custom');
    	$temp['AddTime']=time();
    	$temp['UserCode']=I('UserCode');
    	$temp['Remark']=I('Remark');
		$param=array('id'=>I('id'),'data'=>json_encode($temp));
		$result=D('Pj')->upPj($param,session('loginCookie'));
		if($result){
			echo json_encode(array('status'=>0,'info'=>'更新成功!')); // 成功
		}
	}
//删除评分记录    
	public function delPj(){
		$param['id']=I("id");
		$result=D('Pj')->delPj($param,session('loginCookie'));
		if($result){
			echo json_encode(array('status'=>1,'info'=>'删除成功!')); // 成功
		}else{
			echo json_encode(array('status'=>0,'info'=>'删除失败!')); // 成功
		}
	}
    
}
