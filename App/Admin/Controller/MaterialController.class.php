<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 材料单管理
 */
class MaterialController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){
    	//全部的工种基础数据
    	$groupWorkers = D('User')->getGroupWorks(array(),session('loginCookie'));
    	$this->assign('groupWorkers',$groupWorkers);
    	
    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			$result = D('Active')->seachResource($param , session('loginCookie'));
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
		
		//拼接工程编号
		$PCS = "[";
		foreach ( $post_data['PCS'] as $k=>$v){
			if($v<>''){
				$PCS .= '"'. $v .'",' ; 
			}
		}
		if($PCS<>'[') {
			$PCS = substr($PCS, 0, -1);
		}
		$PCS   .= "]";
		
		//过滤条件
		$param = array (
			'PI' => isset( $post_data['page'] ) ? intval( $post_data['page'] ) : 1, // 页码
			'PS' => isset( $post_data['rows'] ) ? intval( $post_data['rows'] ) : 50, // 每页大小
			'PCS'=> $PCS, // 工程编号集合
			'BT' => isset( $post_data['BT'] ) ?  $post_data['BT'] : $BT, // 开始日期
			'ET' => isset( $post_data['ET'] ) ?  $post_data['ET'] : $ET, // 结束日期
			'S'  => ( isset( $post_data['S'] ) &&  $post_data['S']<>-1 ) ? '["'.$post_data['S'].'"]' : '["0"]', // 单据状态
			'U'  => ( isset( $post_data['U'] ) &&  $post_data['U']<>-1 ) ? $post_data['U'] : '', // 供应商编号
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
			'RECODE'=>$post_data['RECODE'],
			'UC'=>$post_data['UC'],
			'ResourceCode'=>$post_data['ResourceCode']
		);
		
		$status = D('Active')->allotUserToRes($param , session('loginCookie'));

		print_r($status) ;
		exit;
	}
	
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
}
