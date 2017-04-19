<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 审计管理
 */
class AuditController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){
    	//全部的工种基础数据
    	$groupWorkers = D('User')->getGroupWorks(array(),session('loginCookie'));
    	$this->assign('groupWorkers',$groupWorkers);
    	
    	//获取所有单据类型
    	$rtype = C('RTYPE');//getRtype();
    	$this->assign('rtype',$rtype);

    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();

			//print_r($param);exit;

			$result = D('Active')->seachRecords($param , session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->DB;
			foreach($_list as $k=>$v) {
				$_list[$k] = (array)$v;
				$_list[$k]['sbegin'] = format_datatime($v->sbegin);
				$_list[$k]['handlerDate'] = format_datatime( $v->handlerDate);
				$_list[$k]['_rtype'] = getRtype($v->rtype);
				$_list[$k]['_status'] = getStatusAudit($v->status);
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
		$get_data = I('get.');

		//当月第一天和最后一天
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();

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
		//过滤条件
		//常福鹏 2016-01-29
		$param = array(
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 1, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 50, // 每页大小
				'PCS' => $PCS, // 工程编号集合
				'BT' => isset($post_data['BT']) ? $post_data['BT'] : $BT, // 开始日期
				'ET' => isset($post_data['ET']) ? $post_data['ET'] : $ET, // 结束日期
				'S' => $post_data['S'] <> -1 ? $post_data["S"] : "", // 数据状态
				'T' => (isset($post_data['T']) && $post_data['T'] <> -1) ? '["' . $post_data['T'] . '"]' :  '["M1","M4","M5","M6","M7","M10","R5","R6","MA","MB","MC","MD"]', // 单数据类型集合
				"P" => I("param.m"),
		);
		//print_r($param);
		//exit;
		return $param;
	}
	
	 /* 
	  * 审核通过或打回
      * Auth   : kevin
      * Time   : 2016-01-27
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

	/* 导出
    * Auth   : kevin
    * Time   : 2016-02-04
    **/
	function exportcsv()
	{
		$post_data = I('get.');

		//当月第一天和最后一天
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();

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
		
		//按周统计
		
		//过滤条件
		//常福鹏 2016-01-29
		$param = array(
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 1, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 50, // 每页大小
				'PCS' => sizeof($PCS)==0?array():$PCS, // 工程编号集合
				'BT' => isset($post_data['BT']) ? $post_data['BT'] : $BT, // 开始日期
				'ET' => isset($post_data['ET']) ? $post_data['ET'] : $ET, // 结束日期
				'S' => (isset($post_data['S']) && $post_data['S'] <> -1) ? $post_data['S'] : '', // 数据状态
				'T' => (isset($post_data['T']) && $post_data['T'] <> -1) ? '["' . $post_data['T'] . '"]' : '["M1","M4","M5","M6","M7","M10","R5","R6","MA","MB","MC","MD"]', // 单数据类型集合
		);

		$data = array();
		$result = D('Active')->seachRecords($param, session('loginCookie'));
		$result = $result->DB;

		$headdata = array(
				'projectCode' => '工程编号',
				'projectName' => '装修地址',
				'owner' => '客户姓名',
				'productType' => '客户版本号',
				'activeItemName' => '工序名称',
				'rtype' => '类型',
				'projectUserTel' => '装修管家电话',
				'projectUserCode' => '装修管家',
				'userCode' => '工人编号',
				'userName' => '工人名称',
				'money' => '金额',
				'status' => '状态',
				'remark' => '描述',
				'recordCode' => '单据编号',
				'sbegin' => '生成时间',
				'handleUserCode' => '审核人',
				'handlerDate' => '审核时间',
		);

		foreach ($result as $k => $v) {
			$data[$k]['projectCode'] = $v->projectCode;
			$data[$k]['projectName'] = $v->projectName;
			$data[$k]['owner'] = $v->owner;
			$data[$k]['productType'] = $v->productType;
			$data[$k]['activeItemName'] = $v->activeItemName;
			$data[$k]['rtype'] = getRtype($v->rtype);
			$data[$k]['projectUserTel'] = $v->projectUserTel;
			$data[$k]['projectUserCode'] = $v->projectUserCode;
			$data[$k]['userCode'] = $v->userCode;
			$data[$k]['userName'] = $v->userName;
			$data[$k]['money'] = $v->money;
			$data[$k]['status'] = getStatusAudit($v->status);
			$data[$k]['remark'] = $v->remark == 'null' ? '' : $v->remark;
			$data[$k]['recordCode'] = $v->recordCode;
			$data[$k]['sbegin'] = format_datatime($v->sbegin);
			$data[$k]['handleUserCode'] = $v->handleUserCode;
			$data[$k]['handlerDate'] = format_datatime($v->handlerDate);
		}

		exportCsv('审计单据.csv', $headdata, $data);
		exit;
	}
}
