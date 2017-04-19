<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 结算管理
 */
class SettlementController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){
    	//一年多少周
    	$weeks = date("W",mktime(0,0,0,12,28,date("Y")));
    	$this->assign('week',$weeks+1);
    	
    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			$result = D('Active')->seachRecords($param , session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->DB;
			
			foreach($_list as $k=>$v) {
				$_list[$k] = (array)$v;
				$_list[$k]['sbegin'] = format_datatime($v->sbegin);
				$_list[$k]['handlerDate'] = format_datatime($v->handlerDate);
				$param_tg = "'" . $v->recordCode . "'" . ',' . "'F'";
				$param_btg = "'" . $v->recordCode . "'" . ',' . "'E'";
				$param_exttact = "'" . $v->recordCode . "'";
				if ($v->status == '0') {
					$_list[$k]['operator'] = '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="op_auditExtract(' . $param_tg . ')">审核通过</a>';
					$_list[$k]['operator'] .= ' | ';
					$_list[$k]['operator'] .= '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="op_auditExtract(' . $param_btg . ')">打回</a>';
				} else if ($v->status == 'F') {
					$_list[$k]['operator'] .= '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="moneyExtractFinish(' . $param_exttact . ')">模拟ERP打款</a>';
					$_list[$k]['operator'] .= '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="print(\'' . $v->recordCode . '\')">打印</a>';
				} else if($v->status=="G"){
					$_list[$k]['operator'] .= '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="print(\'' . $v->recordCode . '\')">打印</a>';
				}
				$_list[$k]['_status'] = getStatusSettlement($v->status);
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
		
		// 单数据类型
		if($post_data['T']==null){
			$T='["M9"]';
		}else {
			$T = "[";
			foreach ($post_data['T'] as $k => $v) {
				if ($v <> '') {
					$T .= '"' . $v . '",';
				}
			}
			if ($T <> '[') {
				$T = substr($T, 0, -1);
			}
			$T .= "]";
		}
		
		//过滤条件
		$param = array (
			'PI' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
			'PS' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 20, // 每页大小
			'PCS'=> ( isset( $post_data['PCS'] ) &&  $post_data['PCS']<>'' ) ? $post_data['PCS'] : '[]',// 工程编号集合
			'BT' => isset( $post_data['BT'] ) ?  $post_data['BT'] : $BT, // 开始日期
			'ET' => isset( $post_data['ET'] ) ?  $post_data['ET'] : $ET, // 结束日期
			'S'  => ( isset( $post_data['S'] ) &&  $post_data['S']<>-1 ) ? $post_data['S'] : '', // 数据状态
			'T'  => $T<>'[]' ? $T : '[""]', // 单数据类型集合
		);
		
		return $param;
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

	/**
	 *  点击操作里的通过、不通过，单条操作
	 */
	function opAuditRec(){
		$post_data=I('post.');

		if(!$post_data['recordCode']){
			print_r('201');
			exit;
		}

		$param= array(
			'RecordCods'=>'["' . $post_data['recordCode'] . '"]',
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
 * 模拟ERP打款成功
 * Auth   : 常福鹏
 * Time   : 2016-01-31
 **/
	function moneyExtractFinish()
	{
		$post_data = I('post.');
		$re = $post_data['RE'];
		$t = $post_data["T"];
		$RecordCod = array(
				'RECORDCODE' => $re,
				'T8' => $t
		);
		$param = array(
				'jsonArray' => json_encode(array($RecordCod))
		);

		$status = D('Active')->moneyExtractFinish($param, session('loginCookie'));
		print_r($status);
		exit;
	}


	/* 导出
	 * Auth   : kevin
     * Time   : 2016-02-04
     **/
	function exportcsv(){
		$post_data=I('get.');

		//当月第一天和最后一天
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();

		// 单数据类型
		$T = "[";
		foreach ( $post_data['T'] as $k=>$v){
			if($v<>''){
				$T .= '"'. $v .'",' ;
			}
		}
		if($T<>'[') {
			$T = substr($T, 0, -1);
		}
		$T   .= "]";

		//过滤条件
		$param = array (
			'PI' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
			'PS' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 50, // 每页大小
			'PCS'=> ( isset( $post_data['PCS'] ) &&  $post_data['PCS']<>'' ) ? $post_data['PCS'] : '[]',// 工程编号集合
			'BT' => isset( $post_data['BT'] ) ?  $post_data['BT'] : $BT, // 开始日期
			'ET' => isset( $post_data['ET'] ) ?  $post_data['ET'] : $ET, // 结束日期
			'S'  => ( isset( $post_data['S'] ) &&  $post_data['S']<>-1 ) ? $post_data['S'] : '', // 数据状态
			'T'  => $T<>'[]' ? $T : '["M2","M9"]', // 单数据类型集合
		);
		
	//按周计算开始时间 结束时间
		$week = (int)$_GET['week'];
		
		if($week){
			$year = date("Y");
			$weeks = date("W", mktime(0, 0, 0, 12, 28, $year));
				
			if ($week > $weeks || $week <= 0)
			{
				$week = 1;
			}
				
			if ($week < 10)
			{
				$week = '0' . $week;
			}
			$timestamp['start'] = strtotime($year . 'W' . $week); //每周的开始时间
			$timestamp['end'] = strtotime('+1 week -1 day', $timestamp['start']); //每周结束时间
				
			$param['BT']=date('Y-m-d',$timestamp['start']);
			$param['ET']=date("Y-m-d",$timestamp['end']);
		}
		
		$data = array();
		$result = D('Active')->seachRecords($param , session('loginCookie'));
		$result = $result->DB;
		if($week){
			$headdata=array();
			$data=array();
			$headdata = array(
					'week' => '提款周为周期',
					'owner' => '客户姓名',
					'money' => '入钱包金额',
			);
			$temp=array();
			foreach ($result as $k=>$v) {
				$v=(array)$v;
				if($temp[$v['userCode']]){
					$temp[$v['userCode']]['money']+=$v['money'];
				}else{
					$temp[$v['userCode']]=$v;
				}
			}
			$i=0;
			foreach ($temp as $k => $v) {
				$data[$i]['week'] = "第".$week."周";
				$data[$i]['owner'] = $v['userName'];
				$data[$i]['money'] = $v['money'];
				$i++;
			}
		
		}else{
		$headdata = array (
			'recordCode'=>'单据编号',
			'qijian'=>'期间',
			'sbegin'=>'申请日期',
			'userCode'=>'用户编号',
			'userName'=>'用户名称',
			'money'=>'金额',
			'handleUserCode'=>'审核人',
			'handlerDate'=>'审核时间',
			'remark'=>'审核意见',
				't5'=>'汇款人',
				't7'=>'汇款时间',
				't8'=>'汇款回执单',
			'status'=>'状态',
				't2'=>'提现银行',
				't1'=>'银行卡',
				't3'=>'支行'
		);

		foreach ( $result as $k=>$v ) {
			$data[$k]['recordCode'] = $v->recordCode;
			$data[$k]['qijian'] = '';
			$data[$k]['sbegin'] = '' . format_datatime($v->sbegin);
			$data[$k]['userCode'] = '' . $v->userCode;
			$data[$k]['userName'] = '' . $v->userName;
			$data[$k]['money'] = '' . $v->money;
			$data[$k]['handleUserCode'] = '' . $v->handleUserCode;
			$data[$k]['handlerDate'] = '' . format_datatime($v->handlerDate);
			$data[$k]['remark'] = '' . $v->remark;
			$data[$k]['t5'] = '' . $v->t5;
			$data[$k]['t7'] = '' . $v->t7;
			$data[$k]['t8'] = '' . $v->t8;
			$data[$k]['status'] = getStatusSettlement($v->status);
			$data[$k]['t2'] = '' . $v->t2;			
			$data[$k]['t1'] = '' . $v->t1;
			$data[$k]['t3'] = '' . $v->t3;
		}

	  }
	  exportCsv('结算管理.csv', $headdata, $data);
	  exit;
	}	
	
	//转换pdf
	function loadMoneyExtractPDF()
	{
		$post_data = I('POST.');
		$param = array('RC' => $post_data['RC']);
		$result = D('WorkerInfo')->loadMoneyExtractPDF($param, session('loginCookie'));
		print_r(json_encode($result));
		exit;
	}


}
