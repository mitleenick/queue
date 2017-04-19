<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 工程管理
 */
class WorkController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-29
     **/
    public function index(){ 
    	// $param['key']=0;
    	// $res=D('User')->erpEvent($param , session('loginCookie'));
    	// var_dump($res);
    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
   
			//print_r($param);exit;

			$result = D('Project')->getProjects($param , session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->Data;
    	    foreach ($_list as $key=>$val){
	    		$_list[$key]->showpic='<a href="#" onClick="showpic('.$val->projectCode.')">查看合同</a>';   		
	    		if($val->contract){
					if(strpos($val->contract,'|')){
						$contract = explode('|', $val->contract);
						$_list[$key]->img1='http://' . getDomain() . '/yhr/' .$contract[0];
						$_list[$key]->img2='http://' . getDomain() . '/yhr/' .$contract[1];
						$_list[$key]->img3='http://' . getDomain() . '/yhr/' .$contract[2];
					}else{
						$_list[$key]->img1='http://' . getDomain() . '/yhr/' .$val->contract;
						$_list[$key]->img2='';
						$_list[$key]->img3='';
					}
	    		}else{
	    			$_list[$key]->img1='';
	    			$_list[$key]->img2='';
	    			$_list[$key]->img3='';
	    		}    	
    	 	}
			
			$data = array('total'=>$total, 'rows'=>$_list);
			$this->ajaxReturn($data);
    	}else{
    		$this->display();
    	}
    }
    
    //16.11.16
    public function erpEvent(){
    	$param['key']=0;
    	$res=D('User')->erpEvent($param , session('loginCookie'));
    	var_dump($res);
    }

 	/* 搜索
     * Auth   : kevin
     * Time   : 2016-01-29
     **/
	protected function _search() {
	
		$post_data=I('post.');
		
		// 当月第一天和最后一天
		//$dateClass = new \Org\Util\Date('');
		//$BT = $dateClass->firstDayOfMonth();
		//$ET = $dateClass->lastDayOfMonth();

		//当前日期所在季度开始时间和结束时间
		$BT = date("Y-m-d", time() - 86400 * 60);
		$ET = date("Y-m-d", time() + 86400 * 60);
		
		// 拼接工程编号
		$projectCodes = "[";
		foreach ( $post_data['projectCodes'] as $k=>$v){
			if($v<>''){
				$projectCodes .= '"'. $v .'",' ; 
			}
		}
		if($projectCodes<>'[') {
			$projectCodes = substr($projectCodes, 0, -1);
		}
		$projectCodes .= "]";
		if(!isset($post_data['delOrderId'])){
              $delOrderId='';
		}else{
			  $delOrderId=explode(',', $post_data['delOrderId']);
			  array_filter($delOrderId);
			  $delOrderId=json_encode($delOrderId);
		}

		//过滤条件
		$param = array (
			'beginDate' => isset( $post_data['beginDate'] ) ?  $post_data['beginDate'] : $BT, // 开始日期
			'endDate' => isset( $post_data['endDate'] ) ?  $post_data['endDate'] : $ET, // 结束日期
			'owner' => isset( $post_data['owner'] ) ?  $post_data['owner'] : '', // 业主名称
			'projectName' => isset( $post_data['projectName'] ) ?  $post_data['projectName'] : '', // 工程名称
			'projectManagerCode' => isset( $post_data['projectManagerCode'] ) ?  $post_data['projectManagerCode'] : '', // 工程经理名称
			'status'  => ( isset( $post_data['S'] ) &&  $post_data['S']<>-1 ) ? $post_data['S'] : '', // 数据状态
			'projectCodes'=> $projectCodes,// 工程编号集合
			'pageNumber' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
			'pageSize' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 50, // 每页大小
			'delOrderId' =>$delOrderId  // 删除的订单号
		);
		
		return $param;
	}
	
	/*@author mit
	 * 上传合同，添加新字段Contract
	 */	
	function upContract(){
		$projectCode=I('projectId');
		foreach ($_FILES as $key=>$val){
			if($val['tmp_name']){
				$content=file_get_contents($val['tmp_name']);
				//$content = base64_encode($content);
				$param=array('contentType'=>0,'content'=>(string)$content);				
				$data[] = D("User")->uploadFile_new($param , session('loginCookie'));				
			}
		}
		foreach ($data as $key=>$val){
			if($val->STATUS==200) $urls[]=$val->RESULT;
		}
		
		$update_contract=array('projectCode'=>$projectCode,"images"=>json_encode($urls)); 
	
 		$result = D("Project")->updateContract($update_contract, session('loginCookie'));
 		if($result){
 			$this->success('上传成功',"/index.php?m=Admin&c=Work&a=index");
 		}
		
	}
	
	/**
	 * @author mit
	 * @ajax请求，显示合同图片
	 */
	function getContract(){
		$param['projectCode']=I('ProjectCode');
		$result = D('Project')->getProject($param,session('loginCookie'));
	    if($result->contract){
			if(strpos($result->contract,'|')){
				$contract = explode('|', $result->contract);
				$data['img1']='http://' . getDomain() . '/yhr/' .$contract[0];
				$data['img2']='http://' . getDomain() . '/yhr/' .$contract[1];
				$data['img3']='http://' . getDomain() . '/yhr/' .$contract[2];
			}else{
				$data['img1']='http://' . getDomain() . '/yhr/' .$result->contract;
				$data['img2']='';
				$data['img3']='';
			}
    	}else{
    		$data['img1']='';
    		$data['img2']='';
    		$data['img3']='';
    	}
    	echo json_encode($data);
	}
	
	 /* 
	  * 通过
      * Auth   : kevin
      * Time   : 2016-01-29
      **/
	function auditRec(){
		$post_data=I('post.');
		$re = (array)$post_data['RE'];
		$RecordCods = "[";
		foreach($re as $k=>$v){
			$RecordCods .= '"'. $v['recordCode'] .'"' ; 
			if($k<count($re)-1){
				$RecordCods .= ",";
			}
		}
		
		$RecordCods   .= "]";
		
		$param= array(
			'RecordCods'=>$RecordCods,
			'S'=>$post_data['S']
		);
		
		if($post_data['S']=='E'){  //审核不通过情况，带上R参数
			$param['R'] = $post_data['R'];
		}
		
		$status = D('Active')->auditRec($param , session('loginCookie'));
		
		print_r($status) ;
		exit;
	}	

	/*
	 * 地图查看未知
     * Auth   : kevin
     * Time   : 2016-02-04
	 */
	function map (){
		layout(false);
		$get_data = I('get.');

		$lo = $get_data['lo'];
		$la = $get_data['la'];
		$projectCode = $get_data['projectCode'];
		$projectName = $get_data['projectName'];

		$this->assign('lo' , $lo);
		$this->assign('la' , $la);
		$this->assign('projectCode' , $projectCode);
		$this->assign('projectName' , $projectName);

		$this->display ();

	}
	function getCurActiveItemInStatus()
	{
		if (IS_POST) {
			$post_data = I('post.');
			$param = array(
					'PC' => $post_data['PC'], // 工程编号
			);

			$result = D('Active')->getCurActiveItemInStatus($param, session('loginCookie'));
			if (isset( $result->Status)) {
				$result->Status = getStatusPzd($result->Status);
			}
			$data = array('db' => $result, 'PC' => $post_data['PC']);
			$this->ajaxReturn($data);
		} else {
			$this->display();
		}
	}
}
