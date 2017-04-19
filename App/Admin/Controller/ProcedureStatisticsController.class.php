<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 工序费用统计管理
 */
class ProcedureStatisticsController extends AdminCoreController {
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){

    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			$result = D('Active')->getActiveitemSummaryReport($param , session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->DB;

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
		$dateClass = new \Org\Util\Date('');
		$BT = date("Y-m-d", time() - 86400 * 60);
		$ET = date("Y-m-d", time() + 86400);
		$pc = $post_data["PC"];

		//过滤条件
		$param = array(
				'PC' => $pc,//工程编号
				'BT' => isset($post_data['beginDate']) ? $post_data['beginDate'] : $BT, // 开始日期
				'ET' => isset($post_data['endDate']) ? $post_data['endDate'] : $ET, // 结束日期
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 1, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 100, // 每页大小
		);

		return $param;
	}
}
