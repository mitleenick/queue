<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 配送单管理
 * @todo 1.工程编号过滤增加触发事件 2.供应商过滤  3.导出
 */
class DistributionController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){
    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			$result = D('Active')->seachResourceByalloc($param , session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->DB;
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
	protected function _search() {
	
		$post_data=I('post.');
		
		//当月第一天和最后一天
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();
		
		//过滤条件
		$param = array (
			'PI' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
			'PS' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 20, // 每页大小
			'PCS'=> ( isset( $post_data['PCS'] ) &&  $post_data['PCS']<>'') ? '["' .$post_data['PCS']. '"]'  : '[]', // 工程编号集合
			'BT' => isset( $post_data['BT'] ) ?  $post_data['BT'] : $BT, // 开始日期
			'ET' => isset( $post_data['ET'] ) ?  $post_data['ET'] : $ET, // 结束日期
			'S'  => ( isset( $post_data['S'] ) &&  $post_data['S']<>-1 ) ? $post_data['S'] : '', // 配送单状态
			'U'  => ( isset( $post_data['U'] ) &&  $post_data['U']<>-1 ) ? $post_data['U'] : '', // 配送员账号
		);
		
		return $param;
	}
	
	 /* 
	  * 配送完成
      * Auth   : kevin
      * Time   : 2016-01-28
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
	function exportcsv(){
		$post_data=I('get.');

		//当月第一天和最后一天
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();

		//过滤条件
		$param = array (
			'PI' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
			'PS' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 100000000000, // 每页大小
			'PCS'=> ( isset( $post_data['PCS'] ) &&  $post_data['PCS']<>'') ? '["' .$post_data['PCS']. '"]'  : '[]', // 工程编号集合
			'BT' => isset( $post_data['BT'] ) ?  $post_data['BT'] : $BT, // 开始日期
			'ET' => isset( $post_data['ET'] ) ?  $post_data['ET'] : $ET, // 结束日期
			'S'  => ( isset( $post_data['S'] ) &&  $post_data['S']<>-1 ) ? $post_data['S'] : '', // 配送单状态
			'U'  => ( isset( $post_data['U'] ) &&  $post_data['U']<>-1 ) ? $post_data['U'] : '', // 配送员账号
		);

		$data = array();
		$result = D('Active')->seachResourceByalloc($param , session('loginCookie'));
		$result = $result->DB;

		$headdata = array (
			'recordCode'=>'单据号',
			'projectCode'=>'工程编号',
			'projectName'=>'工程名称',
			'activeItemName'=>'工序项名称',
			'resourcecode'=>'资源编号',
			'begin'=>'开始时间',
			'end'=>'结束时间',
			'resourcename'=>'材料',
			'suppliercode'=>'供应商编号',
			'suppliername'=>'供应商名称',
			'norms'=>'品类',
			'number'=>'数量',
			'moneyunit'=>'单价',
			'status'=>'状态'
		);

		foreach ( $result as $k=>$v ){
			$data[$k]['recordCode'] = ' '.$v->recordCode;
			$data[$k]['projectCode'] = ' '. $v->projectCode;
			$data[$k]['projectName'] =  $v->projectName;
			$data[$k]['activeItemName'] =  $v->activeItemName;
			$data[$k]['resourcecode'] =  ' '.$v->resourcecode;
			$data[$k]['begin'] =  ' '.$v->begin;
			$data[$k]['end'] =  ' '.$v->end;
			$data[$k]['resourcename'] =  $v->resourcename;
			$data[$k]['suppliercode'] =  ' '.$v->suppliercode;
			$data[$k]['suppliername'] =  $v->suppliercode;
			$data[$k]['norms'] =  $v->norms;
			$data[$k]['number'] =  $v->number;
			$data[$k]['moneyunit'] =  $v->moneyunit;
			$data[$k]['status'] = $this->getStatus($v->status);
		}

		exportCsv('配送单.csv', $headdata, $data);
		exit;
	}

	protected function getStatus ($code){
		if($code == 1) {
			return '配送中';
		}elseif($code == 'F') {
			return '完成';
		}else {
			return '未知';
		}
	}
}
