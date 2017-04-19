<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 评分管理
 */
class QueueController extends CoreController{
	
	//类初始化方法
	public function _initialize() {	
	}
	
	public function requestNum(){
		$data=I('data');
		$data= json_decode(base64_decode($data),true);
		
// 		$param['auth']= 'VIP';
// 		$param['name']= '用户1';
// 		$param['icard'] = '371327198712284312';
// 		$param['avatar'] = 'test';
// 		$param['address'] = '地址';
// 		$param['company'] = '11';
// 		$param['bussiness'] = '6';
// 		echo base64_encode(json_encode($param));
// exit;
		
		$param['auth']= $data['auth'];
		$param['name']= $data['name'];
		$param['icard'] = $data['icard'];
		$param['avatar'] = $data['avatar'];
		$param['address'] = $data['address'];
		$param['company'] = $data['company'];
		$param['bussiness'] = $data['bussiness'];
		;
		if(I('icard')){
			$param['uid'] =D('User')->addUser($param);
		}
		$num = D('Queue')->requestNum($param);
		$queues=$this->getOtherInfo($num);		
		$num=substr(strval($num+10000),1,4);
		$queues['number']=C('pre_num').$num;
		
		echo base64_encode(json_encode($queues));
	}
	
	
	 function getOtherInfo($num){
	 	$map['number'] = array('lt',$num);
		$result = M('queue')->where($map)->select();	
		$queueNum = count($result);
		$infos = A('Statistics')->avgTime();
		if($infos['avgtime']){
			$queueTime = $queueNum*$infos['avgtime']; //排队时间
		}
		return array('num'=>$queueNum,'queuetime'=>$queueTime);
		
	}
	
	
	public function callNum(){
		$param['bussiness']= I('bussiness')?I('bussiness'):'';
		if(I('bussiness')==''){
			
		}else{
			
		}
	}
	
	public function abandonNum($num){
		$result = D('Queue')->abandonNum($num);
		return $result;
	}
	
	/**
	 * 返回分公司列表
	 */	
	public function getCompany(){
		$result = M('company')->select();		
		echo base64_encode(json_encode($result,JSON_UNESCAPED_UNICODE));
	}
	
	public function getSet(){
		$result = M('system')->select();
		echo base64_encode(json_encode($result[0],JSON_UNESCAPED_UNICODE));
	}

}
