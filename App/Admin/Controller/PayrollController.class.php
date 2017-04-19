<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 劳务费管理
 * @todo 1.检索--用户编号userCode
 */
class PayrollController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){
    	
    	// 获取单据类型
    	$mtype = C('MTYPE');//getMtype();
    	$this->assign('mtype',$mtype);
    	
    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			$result = D('WorkerInfo')->searchMoney($param , session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->Data;
			foreach($_list as $k=>$v) {
				$_list[$k]->mtype = getMtype($v->mtype);
				$_list[$k]->status = getStatusPayroll($v->status);
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
	protected function _search() {
	
		$post_data=I('post.');
		
		//当月第一天和最后一天
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();
		
		//过滤条件
		$param = array (
			'pageNumber' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
			'pageSize' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 50, // 每页大小
			'type'=> ( isset( $post_data['type'] ) &&  $post_data['type']<>-1 ) ? $post_data['type'] : '',// 流水号类型
			'userCode'=> ( isset( $post_data['userCode'] ) &&  $post_data['userCode']<>-1 ) ? $post_data['userCode'] : '',// 用户编号
			'beginDate' => isset( $post_data['beginDate'] ) ?  $post_data['beginDate'] : $BT, // 开始日期
			'endDate' => isset( $post_data['endDate'] ) ?  $post_data['endDate'] : $ET, // 结束日期
		);
		
		return $param;
	}
	function exportcsv(){
		$post_data=I('get.');
		$headdata = array (
				'no'=>'流水号',
				'recordCode'=>'单号',
				'userCode'=>'用户编号',
				'userName'=>'用户名称',
				'income'=>'收入',
				'outgo'=>'支出',
				'surplus'=>'余额',
				'auditDateTime'=>'审核时间',
				'_status'=>'状态',
				'_mtype'=>'类型'
		);
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();

		$data = array();
		$param = array (
				'pageNumber' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
				'pageSize' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 50, // 每页大小流水号类型
				'type'=> ( isset( $post_data['type'] ) &&  $post_data['type']<>-1 ) ? $post_data['type'] : '',//
				'userCode'=> ( isset( $post_data['userCode'] ) &&  $post_data['userCode']<>-1 ) ? $post_data['userCode'] : '',// 用户编号
				'beginDate' => isset( $post_data['beginDate'] ) ?  $post_data['beginDate'] :$BT, // 开始日期
				'endDate' => isset( $post_data['endDate'] ) ?  $post_data['endDate'] : $ET, // 结束日期
		);
			$result = D('WorkerInfo')->searchMoney($param , session('loginCookie'));
			$result = $result->Data;

			foreach ( $result as $k=> $v ) {
				$data[$k]['no'] = $v->no;
				$data[$k]['recordCode'] = $v->recordCode;
				$data[$k]['userCode'] = $v->userCode;
				$data[$k]['userName'] = $v->userName;
				$data[$k]['income'] = $v->income;
				$data[$k]['outgo'] = $v->outgo;
				$data[$k]['surplus'] = $v->surplus;
				$data[$k]['auditDateTime'] = $v->auditDateTime;
				$data[$k]['status'] = getStatusPayroll($v->status);
				$data[$k]['mtype'] = getMtype($v->mtype);
			}
		exportCsv('劳务费.csv', $headdata, $data);
		exit;
	}
	 /* 
	  * 通过
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
	

}
