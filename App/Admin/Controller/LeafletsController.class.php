<?php

namespace Admin\Controller;
//use Common\Controller\CoreController;

/**
 * 派单管理
 */
class LeafletsController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index()
	{

		//全部的工种基础数据
		$groupWorkers = D('User')->getGroupWorks(array(), session('loginCookie'));
		$this->assign('groupWorkers', $groupWorkers);

		//查询可派单用户


		if (IS_POST) {
			$post_data = I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			//echo '<pre>';
			//print_r($param);
			//exit;
			$result = D('Active')->seachIns($param, session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->DB;
			//echo '<pre>';print_r($_list);exit;
			$data = array('total' => $total, 'rows' => $_list);
			$this->ajaxReturn($data);
		} else {
			$this->display();
		}
	}

 	/* 搜索
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
	protected function _search()
	{

		//当月第一天和最后一天
		//$dateClass = new \Org\Util\Date('');
		//$BT = $dateClass->firstDayOfMonth();
		//$ET = $dateClass->lastDayOfMonth();

		//默认，当前时间－15天 ～ 当前时间＋15天
		$BT = date("Y-m-d", time() - 86400 * 30);
		$ET = date("Y-m-d", time() + 86400 * 30);

		//$BT = '2015-01-01 00:00:00';

		$post_data = I('post.');

		//拼接工程编号
		$PCS = "[";
		foreach ($post_data['PCS'] as $k => $v) {
			if ($v <> '') {
				$PCS .= '"' . $v . '",';
			}
		}
		if ($PCS <> '[') {
			$PCS = substr($PCS, 0, -1);
		}
		$PCS .= "]";
		$S = $post_data['S'];
		if($post_data['S']==null) {
			$S = 1;
		}else if ($post_data['S'] == -1) {
			$S = "2,3,4,5,6,7,8,E,F,G";
		}
		$param = array(
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 1, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 20, // 每页大小
				'PCS' => $PCS, // 工程编号集合
				'BT' => isset($post_data['BT']) ? $post_data['BT'] : $BT, // 开始日期
				'ET' => isset($post_data['ET']) ? $post_data['ET'] : $ET, // 结束日期
				'S' => $S, // 单据状态
				'GWC' => (isset($post_data['GWC']) && $post_data['GWC'] <> -1) ? $post_data['GWC'] : '', // 工种编号
				'ISCU' => (isset($post_data['ISCU']) && $post_data['ISCU'] <> -1) ? $post_data['ISCU'] : '' // 改人单
		);

		return $param;
	}

	// 工人下拉列表
	function  getUserForPlan(){
		$post_data=I('get.');

		$param = array(
			'workGroupCode'=>( isset( $post_data['GWC'] ) && $post_data['GWC']<>-1 ) ? $post_data['GWC'] : '', // 工种编号
			'beginDate' => isset( $post_data['beginDate'] ) ? date('Y-m-d H:i:s' , strtotime($post_data['beginDate'])) : '', // 开始时间
			'endDate' => isset( $post_data['endDate'] ) ? date('Y-m-d H:i:s' , strtotime($post_data['endDate'])) : '', // 结束时间
		);

		// 查询可派单用户
		$userForPlan = D('WorkerInfo')->getUserForPlan($param,session('loginCookie'));

		echo json_encode($userForPlan);
		exit;
	}

	// 工人下拉列表, 类似重载，特殊处理
	function  getUserForPlanBatch(){
		$post_data=I('post.');

		$param = array(
			'workGroupCode'=>( isset( $post_data['GWC'] ) && $post_data['GWC']<>-1 ) ? $post_data['GWC'] : '', // 工种编号
			'beginDate' => isset( $post_data['beginDate'] ) ? date('Y-m-d H:i:s' , strtotime($post_data['beginDate'])) : '', // 开始时间
			'endDate' => isset( $post_data['endDate'] ) ? date('Y-m-d H:i:s' , strtotime($post_data['endDate'])) : '', // 结束时间
		);

		// 查询可派单用户
		$userForPlan = D('WorkerInfo')->getUserForPlan($param,session('loginCookie'));

		echo json_encode(array('userForPlan'=>$userForPlan,'obj'=>$post_data['obj']));
		exit;
	}
	
	 /* 获取工人日程
     * Auth   : kevin
     * Time   : 2016-01-26 
     **/
	function myWorks(){
		$post_data=I('post.');
		$param['UC'] = $post_data['UC'];
		$param['T'] = 'M';
		$param['D'] = $post_data['M'];
		
		$result = D('Active')->myWorks($param , session('loginCookie'));
		
		echo json_encode($result);
		exit;
	}
	
	/* 获取工人最近limit日程
	 * Auth   : kevin
	* Time   : 2016-01-26
	**/
	function myRecentWorks(){
		$post_data=I('post.');
		$param['UC'] = $post_data['UC'];
		$param['limit'] = 10;
		$result = D('Active')->myRecentWorks($param , session('loginCookie'));
	
		echo json_encode($result);
		exit;
	}

	/* 获取改派图片、改派原因
	* Auth   : kevin
	* Time   : 2016-02-27
	**/
	function queryKillWorkReason(){
		$post_data=I('post.');
		$param['PC'] = $post_data['PC'];
		$param['ACII'] = $post_data['ACII'];

		//$param['PC'] = '10281';
		//$param['ACII'] = '20160225095503144';

		$result = D('Active')->queryKillWorkReason($param , session('loginCookie'));

		echo json_encode((array)$result);
		exit;
	}

	/* 锁定
     * Auth   : kevin
     * Time   : 2016-02-04
     **/
	function lockWorks () {
		$post_data=I('post.');

		//拼接 activeitemins
		$re = (array)$post_data['RE'];
		$activeItemInstinces = "[";

		foreach($re as $k=>$v){
			$activeItemInstinces .= '"'. $v['activeitemins'] .'"' ;
			if($k<count($re)-1){
				$activeItemInstinces .= ",";
			}
		}

		$activeItemInstinces   .= "]";

		$param= array(
			'Instances'=>$activeItemInstinces
		);

		$status = D('Active')->lockWorks($param , session('loginCookie'));

		echo $status;
		exit;

	}

	/* 派单
     * Auth   : kevin
     * Time   : 2016-01-26
     **/
	function planWork(){
		$post_data=I('post.');
		$param = array (
			'WorkUserCode'=> $post_data['WorkUserCode'],
			'Instince'=> $post_data['Instince'],
			'BeginDate'=> $post_data['BeginDate'],
			'EndDate'=> $post_data['EndDate'],
			'Money'=> $post_data['Money']
		);
		
		$result = D('Active')->planWork($param , session('loginCookie'));

		echo $result;
		exit;
	}

	/*
	 * 批量派单
	 * Auth   : kevin
     * Time   : 2016-02-29
	 */
	function batchPlanWork(){
		$post_data=I('post.');
		$re = (array)$post_data['RE'];

		//echo '<pre>';print_r($re);exit;

		$count=0;
		foreach($re as $v){
			$v = (array)$v;

			//获取工人列表
			$param = array(
				'workGroupCode'=>( isset( $v['workgroupcode'] ) && $v['workgroupcode']<>-1 ) ? $v['workgroupcode'] : '', // 工种编号
				'beginDate' => isset( $v['begindate'] ) ? format_datatime($v['begindate']) : '', // 开始时间
				'endDate' => isset( $v['enddate'] ) ? format_datatime($v['enddate']) : '', // 结束时间
			);

			// 查询可派单用户
			$userForPlan = D('WorkerInfo')->getUserForPlan($param,session('loginCookie'));
			//echo '<pre>';print_r($userForPlan);exit;
			//UserCode

			//派单
			if(count($userForPlan)>0) {
				$param = array (
					'WorkUserCode'=> $userForPlan[0]->UserCode,
					'Instince'=> $v['activeitemins'],
					'BeginDate'=> date('Y-m-d',strtotime(format_datatime($v['begindate']))),
					'EndDate'=> date('Y-m-d',strtotime(format_datatime($v['enddate']))),
					'Money'=> $v['money']
				);

				$result = D('Active')->planWork($param , session('loginCookie'));
				if($result==1) {
					$count++;
				}
			}
		}

		echo $count;
		exit;
	}
	/* 查询派单工价
     * Auth   : kevin
     * Time   : 2016-01-26
     **/
	function calculateWorkFee(){
		$post_data=I('post.');
		$param = array (
				'PC'=> $post_data['pc'],
				'ACI'=> $post_data['aci'],
				'ACII'=> $post_data['acii']
		);
		$result = D('Active')->calculateWorkFee($param , session('loginCookie'));

		echo $result;
		exit;
	}

}
