<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 出入账凭据管理
 */
class CredentialsController extends AdminCoreController
{

	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
	public function index()
	{
		//全部的工种基础数据
		$groupWorkers = D('User')->getGroupWorks(array(), session('loginCookie'));
		$this->assign('groupWorkers', $groupWorkers);

		//获取所有单据类型
		$rtype = C('RTYPE');//getRtype();
		$this->assign('rtype', $rtype);

		if (IS_POST) {
			$post_data = I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();

			//print_r($param);exit;

			$result = D('Active')->seachRecords2(array("PARAMS" => json_encode($param)), session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->DB;
			foreach ($_list as $k => $v) {
				$_list[$k] = (array)$v;
				$_list[$k]['sbegin'] = format_datatime($v->sbegin);
				$_list[$k]['handlerDate'] = format_datatime($v->handlerDate);
				$_list[$k]['_rtype'] = getRtype($v->rtype);
				$_list[$k]['_status'] = getStatusAudit($v->status);
				$_list[$k]['op'] = $v->isPrint;
			}
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
		$post_data = I('post.');
		$get_data = I('get.');

		//当月第一天和最后一天
		$dateClass = new \Org\Util\Date('');
		$BT = $dateClass->firstDayOfMonth();
		$ET = $dateClass->lastDayOfMonth();


		//拼接工程编号
		//$PCS ='[';
		$PCS = array();
		foreach ($post_data['PCS'] as $k => $v) {
			if ($v <> '') {
				array_push($PCS, $v);
				//$PCS .= '"' . $v . '",';
			}
		}
		//if ($PCS <> '[') {
		//$PCS = substr($PCS, 0, -1);
		//}
		//$PCS .= "]";
		//过滤条件
		//常福鹏 2016-01-29
		$param = array(
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 1, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 50, // 每页大小
				'PCS' => $PCS, // 工程编号集合
				'BT' => isset($post_data['BT']) ? $post_data['BT'] : $BT->format("%Y-%m-%d"), // 开始日期
				'ET' => isset($post_data['ET']) ? $post_data['ET'] : $ET->format("%Y-%m-%d"), // 结束日期
				'S' => 'F', // 数据状态
				'T' => (isset($post_data['T']) && $post_data['T'] <> -1) ? array($post_data['T']) : array("M1", "M4", "M5", "M6", "M7", "M10", "R5", "MA","MB","MC","MD"), // 单数据类型集合
				"UC" => isset($post_data['UC']) ? $post_data["UC"] : '',
				"PRINT" => isset($post_data["PRINT"]) ? $post_data["PRINT"] : '-1',
				"TAGS" => (isset($post_data["TAGS"]) && $post_data["TAGS"] <> -1) ? array($post_data["TAGS"]) : array()
		);
		return $param;
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

		$PCS = array();
		foreach ($post_data['PCS'] as $k => $v) {
			if ($v <> '') {
				array_push($PCS, $v);
			}
		}

		//过滤条件
		//常福鹏 2016-01-29
		$param = array(
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 1, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 50, // 每页大小
				'PCS' =>  sizeof($PCS)==0?array():$PCS, // 工程编号集合
				'BT' => isset($post_data['BT']) ? $post_data['BT'] : $BT->format("%Y-%m-%d"), // 开始日期
				'ET' => isset($post_data['ET']) ? $post_data['ET'] : $ET->format("%Y-%m-%d"), // 结束日期
				'S' => 'F', // 数据状态
				'T' => (isset($post_data['T']) && $post_data['T'] <> -1) ? array($post_data['T']) : array("M1", "M4", "M5", "M6", "M7", "M10", "R5"), // 单数据类型集合
				"UC" => isset($post_data['UC']) ? $post_data["UC"] : '',
				"PRINT" => isset($post_data["PRINT"]) ? $post_data["PRINT"] : '-1',
				"TAGS" => (isset($post_data["TAGS"]) && $post_data["TAGS"] <> -1) ? array($post_data["TAGS"]) : array()
		);

		$data = array();
		$result = D('Active')->seachRecords2(array("PARAMS" => json_encode($param)), session('loginCookie'));
		$result = $result->DB;
		$headdata = array(
				'projectCode' => '工程编号',
				'projectName' => '装修地址',
				'owner' => '客户姓名',
				'productType' => '客户版本号',
				'activeItemName' => '工序名称',
				'projectUserName' => '装修管家',
				'recordCode' => '单据编号',
				'sbegin' => '生成时间',
				'userCode' => '工人编号',
				'userName' => '工人名称',
				'rtype' => '类型',
				'money' => '金额',
				'handleUserCode' => '审核人',
				'handlerDate' => '审核时间',
				'remark' => '描述'
		);

		foreach ($result as $k => $v) {
			$data[$k]['projectCode'] = $v->projectCode;
			$data[$k]['projectName'] = $v->projectName;
			$data[$k]['owner'] = $v->owner;
			$data[$k]['productType'] = $v->productType;
			$data[$k]['activeItemName'] = $v->activeItemName;
			$data[$k]['projectUserName'] = $v->projectUserName;
			$data[$k]['recordCode'] = $v->recordCode;
			$data[$k]['sbegin'] = format_datatime($v->sbegin);
			$data[$k]['userCode'] = $v->userCode;
			$data[$k]['userName'] = $v->userName;
			$data[$k]['rtype'] = getRtype($v->rtype);
			$data[$k]['money'] = $v->money;
			$data[$k]['handleUserCode'] = $v->handleUserCode;
			$data[$k]['handlerDate'] = format_datatime($v->handlerDate);
			$data[$k]['remark'] = $v->remark == 'null' ? '' : $v->remark;
		}

		exportCsv('入账凭证.csv', $headdata, $data);
		exit;
	}

	//转换pdf
	function loadMoneySettlementPDF()
	{
		$post_data = I('POST.');
		$ACII = str_replace(',', '","', str_replace(']', '"]', str_replace('[', '["', $post_data['ACII'])));
		$param = array(
				'ACII' => $ACII);
		$result = D('WorkerInfo')->loadMoneySettlementPDF($param, session('loginCookie'));
		print_r(json_encode($result));
		exit;
	}

	//修改单据标记
	function updateRecordHandleTag()
	{
		$post_data = I('POST.');
		$param = array(
				'RC' => json_encode($post_data["RC"]),
				'TAG' => $post_data["TAG"]);
		$result = D('Active')->updateRecordHandleTag($param, session('loginCookie'));
		print_r(json_encode($result));
		exit;
	}

	//以工序项为单位，判断当前单据数据是否全部审核完成
	function queryActiveitemEnableBilling()
	{
		$post_data = I('POST.');
		$param = array(
				'PC' => $post_data["PCC"],
				'ACI' => $post_data["ACI"],
				'ACII' => $post_data["ACII"]);
		$result = array(ROWS => $post_data["ROWS"], STATUS => D('Active')->queryActiveitemEnableBilling($param, session('loginCookie')));
		$this->ajaxReturn($result);
		exit;
	}
}
