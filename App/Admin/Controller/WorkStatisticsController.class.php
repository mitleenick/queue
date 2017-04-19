<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 工程统计管理 (暂缓)
 */
class WorkStatisticsController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){
    	//全部的工种基础数据
    	//$groupWorkers = D('User')->getGroupWorks(array(),session('loginCookie'));
    	//$this->assign('groupWorkers',$groupWorkers);
    	
    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			$result = D('Project')->getProjects($param , session('loginCookie'));
			//echo '<pre>';print_r($result);exit;
			$total = $result->PageCount;
			$_list = $result->Data;
			foreach($_list as $k=>$v) {
				$_list[$k] = (array)$v;
				$_list[$k]['begin'] = format_datatime($v->begin);
				$_list[$k]['end'] = format_datatime($v->end);
			}
			$data = array('total'=>$total, 'rows'=>$_list);
			$this->ajaxReturn($data);
    	}else{
    		$this->display();
    	}
    }

 	/* 搜索
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
	protected function _search()
	{

		$post_data = I('post.');

		//当月第一天和最后一天
		//$dateClass = new \Org\Util\Date('');
		//$BT = $dateClass->date('Y-m-d',strtotime("-1 month"));
		//$ET = $dateClass->date('Y-m-d',strtotime("+1 month"));

		$currDate = date('Y-m-d', time());
		$BT = date("Y-m-d", time() - 86400 * 90);
		$ET = lastDayOfMonth($currDate);

		//拼接工程编号
		$PCS = "[";
		foreach ($post_data['projectCodes'] as $k => $v) {
			if ($v <> '') {
				$PCS .= '"' . $v . '",';
			}
		}
		if ($PCS <> '[') {
			$PCS = substr($PCS, 0, -1);
		}
		$PCS .= "]";

		//过滤条件
		$param = array(
				'beginDate' => isset($post_data['beginDate']) ? $post_data['beginDate'] : $BT, // 开始日期
				'endDate' => isset($post_data['endDate']) ? $post_data['endDate'] : $ET, // 结束日期
				'owner' => '', //业主,空
				'projectName' => '',//工程名,空
				'projectManagerCode' => isset($post_data['projectManagerCode']) ? $post_data['projectManagerCode'] : '',//装修管家编号
				'status' => 'F',// 状态，只能F
				'projectCodes' => $PCS, // 工程编号集合
				'pageNumber' => '1', // 页码,空
				'pageSize' => '20', // 每页大小,空
		);

		return $param;
	}
	
	 /* 
	  * 审核
      * Auth   : kevin
      * Time   : 2016-01-27
      **/
	function auditResource(){
		$post_data=I('post.');
		$re = (array)$post_data['RE'];
		$RecordCods = "[";
		$ResourceCods = "[";
		foreach($re as $k=>$v){
			$RecordCods .= '"'. $v['recordCode'] .'"' ; 
			$ResourceCods .= '"' . $v['resourcecode'] .'"';  
			if($k<count($re)-1){
				$RecordCods .= ",";
				$ResourceCods .= ",";
			}
		}
		
		$RecordCods   .= "]";
		$ResourceCods .=  "]";
		
		
		$param= array(
			'RecordCods'=>$RecordCods,
			'ResourceCods'=>$ResourceCods,
			'S'=>$post_data['S']
		);
		
		if($post_data['S']=='E'){  //审核不通过情况，带上R参数
			$param['R'] = $post_data['R'];
		}
		
		$status = D('Active')->auditResource($param , session('loginCookie'));
		
		print_r($status) ;
		exit;
	}	

	 /* 
	  * 供应商分配
      * Auth   : kevin
      * Time   : 2016-01-27
      **/
	function allotUserToRes(){
		$post_data=I('post.');
		
		$param= array(
			'PC'=>$post_data['PC'],
			'RECORD'=>$post_data['RECORD'],
			'UC'=>$post_data['UC'],
			'ResourceCode'=>$post_data['ResourceCode']
		);
		
		$status = D('Active')->allotUserToRes($param , session('loginCookie'));
		
		print_r($status) ;
		exit;
	}	


	
	
	
	 /* 添加
     * Auth   : kevin
     * Time   : 2016-01-18 
     **/
	/*public function add(){
		if(IS_POST){
			$post_data=I('post.');
			$GWN = $post_data['GWN'];
 			
			$result = D('User')->addGroupWork($param , session('loginCookie'));
			
			if(isset($result) && is_numeric($result) && $result>0){
				echo json_encode(array('status'=>0,'info'=>'操作失败!')); // 成功
			}else{
                echo json_encode(array('status'=>1,'info'=>'操作成功!')); // 失败
			}
		}else{
        	$this->display();
		}
	}*/
	
	 /* 认证
     * Auth   : kevin
     * Time   : 2016-01-18 
     **/
	/*function auditUser(){
		$post_data=I('post.');
		$param['UC'] = $post_data['UC'];
		
		$result = D('User')->auditUser($param , session('loginCookie'));
		
		echo $result;
		exit;
	}*/
	
	 /* 导出
     * Auth   : kevin
     * Time   : 2016-01-18 
     **/
	function exportcsv(){
		$post_data=I('post.');
		
		$param = array('UC'=>$post_data['UC'],'PI'=>0,'PS'=>1,'GWC'=>$post_data['GMC'],'S'=>$post_data['S']);
		
		$data = array();
		$result = D('User')->seachUser($param , session('loginCookie'));
		$result = $result->DB;
		
		$headdata = array ('usercode'=>'用户编号','susername'=>'用户名称');
		foreach ( $result as $k=>$v ){
			$data[$k]['usercode'] = $v->usercode;
			$data[$k]['susername'] = $v->susername;
		}
		
		exportCsv('test.csv', $headdata, $data);
		exit;
	}
	///查询工程统计信息
	function statisticsProject()
	{
		if (IS_POST) {
			$post_data = I('post.');
			$param = array(
					'projectCode' => $post_data['PC'], // 工程编号
			);

			$result = D('Project')->statisticsProject($param, session('loginCookie'));
			$data = array('db' => $result, 'PC' => $post_data['PC']);
			$this->ajaxReturn($data);
		} else {
			$this->display();
		}
	}
}
