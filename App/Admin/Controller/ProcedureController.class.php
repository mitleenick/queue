<?php

namespace Admin\Controller;
//use Common\Controller\CoreController;
/**
 * 工序管理
 */
class ProcedureController extends AdminCoreController
{

	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-31 
     **/
	public function index()
	{
		if (IS_POST) {
			$param = $this->_search();
			$result = D('Active')->getActiveWF($param, session('loginCookie'));
			$result2 = D('Active')->getActiveItemInsStatus($param, session('loginCookie'));

			foreach ($result as $k => $v) {
				foreach ($result2 as $k2 => $v2) {
					if ($v->ActiveItemCode == $v2->ActiveItemCode) {
						$result[$k]->BeginReality = $v2->BeginReality; // 实际开始时间
						$result[$k]->EndReality = $v2->EndReality;// 实际结束时间
						$result[$k]->Status = $v2->Status; // 状态
						$result[$k]->ActiveItemCodeIns = $v2->ActiveItemCodeIns; // 实例编号
						$result[$k]->IsChangeUser = $v2->IsChangeUser; // 实例编号
					}
				}

				$params = "'" . $param['PC'] . "'," . "'" . $v->ActiveItemCode . "'";

				//弹出派工单openDialogPgd
				$result[$k]->operator_pgd = '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="TDialog(' . $params . ')">查看</a>';
				//弹出物料单
				$result[$k]->operator_wld = '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="openDialogWld(' . $param['PC'] . ',' . $v->ActiveItemCode . ')">查看</a>';
				//弹出劳务费
				$result[$k]->operator_gzd = '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="openDialogGzd(' . $param['PC'] . ',' . $v->ActiveItemCode . ')">查看</a>';
				//状态枚举
				$result[$k]->_status = getStatusPzd($v->Status);
			}

			//print_r($result);exit;

			$data = array('total' => count($result), 'rows' => $result);
			$this->ajaxReturn($data);
		} else {
			$get_data = I('get.');
			$projectCode = $get_data['projectCode'];
			//echo $projectCode;exit;
			$result = D('Project')->getProject(array('projectCode' => $projectCode), session('loginCookie'));
			//echo '<pre>';
			//print_r($result);
			//exit;
			$this->assign('result', $result);
			$this->assign('projectCode', $projectCode);
			$this->display();
		}
	}

	/*
	 * 获取弹出层的详细数据
	 * 前端都是AJAX操作，这坑爹的透传....
	 * 为什么不做成根据单据类型＋工序编号的接口.....
	 * Auth   : kevin
     * Time   : 2016-01-31
	 */
	public function getRecordDetail()
	{

		$post_data = I('post.');
		$dataType = $post_data['datatype'];
		$ac = $post_data['AC'];
		$pc = $post_data['PC'];


		$result = D('Active')->getRecordsByProjectCode(array('PC' => $pc), session('loginCookie'));

		//print_r($result);exit;

		if ($dataType == 1) {
			//派工单
			$RC = '';
			$pgd = '';
			foreach ($result as $k => $v) {
				if ($v->activeItemCode == $ac && $v->rtype == 'W1' && $v->status <> 'E') {
					$RC = $v->recordCode;
					$result[$k]->_status = getStatusPzd($v->status); // 状态枚举
					$pgd = (array)$result[$k];
					break;
				}
			}

			if ($RC) {
				$record = D('Active')->getRecordByCode(array('RC' => $RC, 'PC' => $pc), session('loginCookie'));
				$record->_image1 = 'http://' . getDomain() . '/yhr/' . $record->image1; //开工前图片
				$record->_image2 = 'http://' . getDomain() . '/yhr/' . $record->image2; //开工前图片
				$record->_image3 = 'http://' . getDomain() . '/yhr/' . $record->image3; //开工前图片
				$record->_image4 = 'http://' . getDomain() . '/yhr/' . $record->image4; //申请验收图片
				$record->_image5 = 'http://' . getDomain() . '/yhr/' . $record->image5; //申请验收图片
				$record->_image6 = 'http://' . getDomain() . '/yhr/' . $record->image6; //申请验收图片
                                $record->_image10 = 'http://' . getDomain() . '/yhr/' . $record->image10; //监理审核图片
				$record->_image11 = 'http://' . getDomain() . '/yhr/' . $record->image11; //监理审核图片
				$record->_image12 = 'http://' . getDomain() . '/yhr/' . $record->image12; //监理审核图片
				$record->_image13 = 'http://' . getDomain() . '/yhr/' . $record->image13; //监理审核图片
				$record->_image14 = 'http://' . getDomain() . '/yhr/' . $record->image14; //监理审核图片
				$record->_image15 = 'http://' . getDomain() . '/yhr/' . $record->image15; //监理审核图片
				$record->_image16 = 'http://' . getDomain() . '/yhr/' . $record->image16; //申请验收图片
				$record->_image17 = 'http://' . getDomain() . '/yhr/' . $record->image17; //申请验收图片
				$record->_image18 = 'http://' . getDomain() . '/yhr/' . $record->image18; //申请验收图片
				
				//获取评价的内容
				$activeItemIns = $record->activeItemIns;
				$evaluate = D("Active")->getActiveItemEvaluate(array('PC' => $pc, 'ACCI' => $activeItemIns), session('loginCookie'));
				foreach ($evaluate as $k => $v) {
					$record->w1 = $v->W1;
					$record->w2 = $v->W2;
					$record->evaluateRemark = $v->R;
				}
				$pgd = array_merge($pgd, (array)$record);
			}

			echo json_encode($pgd);
			exit;

		} elseif ($dataType == 2) {
			//物料单
			$wld = array();
			$inarray = array();
			$wldtype = $post_data['wldtype']; // 1.物料数据 2.到场数据 3.增加数据 4.余料数据
			if ($wldtype == 1) {
				$inarray = array('R1', 'R2', 'R3'); //R1主料单 R2辅料单 R3工具单
			} else if ($wldtype == 2) {
				$inarray = array('R5'); //R5到货单
			} else if ($wldtype == 3) {
				$inarray = array('M7', 'M10'); //M7主料调整单(主料) M10辅料调整单(辅料)
			} else if ($wldtype == 4) {
				$inarray = array('R6'); //R6余料单
			}

			foreach ($result as $k => $v) {
				if ($v->activeItemCode == $ac && in_array($v->rtype, $inarray)) {
					//print_r((array)$v);
					$record = D('Active')->getRecordResByCode(array('RC' => $v->recordCode, 'PC' => $pc), session('loginCookie'));
					//print_r((array)$record);

					//找不到单据
					if (!$record) {
						$wld[] = (array)$v;
					}

					//找到单据，并循环拼接
					foreach ($record as $k1 => $v1) {
						$wld[] = array_merge((array)$v, (array)$v1);
					}
				}
			}

			foreach ($wld as $k => $v) {
				$wld[$k]['_rtype'] = getRtype($v['rtype']);
			}

			//print_r($wld);exit;

			echo json_encode($wld);
			exit;

		} elseif ($dataType == 3) {
			//劳务费
			$gzd = array();
			$gzdtype = $post_data['gzdtype']; // 1.工人 2.供应商
			$inarray = $gzdtype == 1 ? array('M1', 'M4', 'M5', 'M6', 'M8') : array('R6', 'M2');//M1劳务费 M4罚款单 M5加薪单 M6加任务单 M8工人调整单 R6余料单 M2供应商工资

			//print_r($gzdtype);
			//print_r($inarray);
			foreach ($result as $k => $v) {
				if ($v->activeItemCode == $ac && in_array($v->rtype, $inarray)) {
					//$gzd[$k] = (array)$v;
					//print_r((array)$v);
					$record = D('Active')->getRecordMoneyByCode(array('RC' => $v->recordCode, 'PC' => $pc), session('loginCookie'));
					//print_r((array)$record);

					//找不到单据
					if (!$record) {
						$gzd[] = (array)$v;
					}

					//找到单据，并循环拼接
					foreach ($record as $k1 => $v1) {
						$gzd[] = array_merge((array)$v, (array)$v1);
					}
				}
			}

			foreach ($gzd as $k => $v) {
				$gzd[$k]['_mtype'] = getMtype($v['mtype']);
			}

			echo json_encode($gzd);
			exit;
		} else {

		}

	}

	/* 搜索
    * Auth   : kevin
    * Time   : 2016-01-18
    **/
	protected function _search()
	{

		$post_data = I('post.');

		$param = array(
				'PC' => isset($post_data['PC']) ? $post_data['PC'] : '-test-', //默认不出数据
		);

		return $param;
	}
	public function getProject()
	{
		if (IS_POST) {
			$param = $this->_search();
			//echo $projectCode;exit;
			$result = D('Project')->getProject($param, session('loginCookie'));
			$this->ajaxReturn($result);
		}
	}
}
