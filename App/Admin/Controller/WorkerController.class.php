<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 工人管理
 */
class WorkerController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-18
     **/
    public function index(){
    	//echo session ('ModelKey.Admin');exit;
    	//全部的工种基础数据
    	$groupWorkers = D('User')->getGroupWorks(array(),session('loginCookie'));
    	$this->assign('groupWorkers',$groupWorkers);
    	if(IS_POST){
    		$post_data=I('post.');
			$post_data['first'] = $post_data['rows'] * ($post_data['page'] - 1);
			$param = $this->_search();
			if($post_data['GWC']=='' && count($groupWorkers)>0) {
				$param['GWC'] = key($groupWorkers);
			}
			$result = D('User')->seachUser($param , session('loginCookie'));
			$total = $result->DBCount;
			$_list = $result->DB;
			foreach($_list as $k=>$v) {
				$_list[$k] = (array)$v;
				//2016-02-17 kevin	实名认证 ：新注册、等待认证
				//2016-02-23 常福鹏
				if($_list[$k]['isuse']==2) {
					$_list[$k]['operate'] = '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="auditUser(' . $_list [$k] ['usercode'] . ')">通过</a>|<a href="javascript:void(0);" class="easyui-linkbutton" onclick="auditUserNot(' . $_list [$k] ['usercode'] . ')">不通过</a>';
				}
				$_list[$k]['look'] = '<a href="javascript:void(0);" class="easyui-linkbutton" onclick="showrecent(' . $_list [$k] ['usercode'] . ')">最近施工</a>';
				
				$_list[$k]['_isuse'] = getStatusWorker($v->isuse);
				$_list[$k]['_sex'] = getSexWorker($v->sex);
			}
			$data = array('total'=>$total, 'rows'=>$_list);
			$this->ajaxReturn($data);
    	}else{
    		$this->display('index');
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
				'UC' => isset($post_data['UC']) ? trim($post_data['UC']) : '', // 手机号
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 1, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 50, // 每页大小
				'GWC' => $post_data['GWC'] != "-1" ? $post_data['GWC'] : '', // 工种编号
				'S' => (isset($post_data['S']) && $post_data['S'] <> -1) ? $post_data['S'] : '' // 用户状态
		);

		return $param;
	}
	
	 /* 添加工种
     * Auth   : kevin
     * Time   : 2016-01-18 
     **/
	public function add(){
		if(IS_POST){
			$post_data=I('post.');
			$GWN = trim($post_data['GWN']);

			//验证工种名称是否重复
			$isExsist = false;
			$groupWorkers = D('User')->getGroupWorks(array(),session('loginCookie'));
			foreach((array)$groupWorkers as $k=>$v) {
				if($v==$GWN) {
					$isExsist = true;
				}
			}
			if($isExsist) {
				echo "-1";
				exit;
			}

			//新增
			$param = array('GWN'=>$GWN);
			$result = D('User')->addGroupWork($param , session('loginCookie'));

			echo $result;
		}else{
        	$this->display();
		}
	}

	/* 删除工种
    * Auth   : kevin
    * Time   : 2016-01-18
    **/
	public function deleteGroupWork(){
		if(IS_POST){
			$post_data=I('post.');
			$GWC = $post_data['GWC'];

			$param = array('GWC'=>$GWC);


			$result = D('User')->seachUser(array('UC'=>'','PI'=>1,'PS'=>50,'GWC'=>$GWC,'S'=>'') , session('loginCookie'));
			$DBCount = $result->DBCount;

			if($DBCount==0) {
				$result = D('User')->deleteGroupWork($param , session('loginCookie'));

				if(isset($result) && is_numeric($result) && $result>0){
					echo json_encode(array('status'=>1,'info'=>'操作成功!')); // 成功
				}else{
					echo json_encode(array('status'=>0,'info'=>'操作失败!')); // 失败
				}
			}else {
				echo json_encode(array('status'=>0,'info'=>'此工种有绑定的用户，不可删除。删除失败!')); // 失败
			}

		}else{
			$this->display();
		}
	}

	 /* 认证
     * Auth   : kevin
     * Time   : 2016-01-18 
     **/
	function auditUser()
	{
		$post_data = I('post.');
		$result = D('User')->auditUser(array('UC' => $post_data["UC"], 'T' => 1), session('loginCookie'));

		echo $result;
		exit;
	}

	/* 认证
        * Auth   : kevin
        * Time   : 2016-01-18
        **/
	function auditUserNot()
	{
		$post_data = I('post.');
		$result = D('User')->auditUser(array('UC' => $post_data["UC"], 'T' => 3), session('loginCookie'));

		echo $result;
		exit;
	}
	
	 /* 导出
     * Auth   : kevin
     * Time   : 2016-01-18 
     **/
	function exportcsv()
	{
		$post_data = I('get.');

		$headdata = array(
				'usercode' => '用户编号',
				'susername' => '用户名称',
				'cardno' => '身份证号码',
				'sex' => '性别',
				'telnumber' => '手机',
				'isuse' => '状态',
				'registeTime' => '注册时间',
				'workgroupname' => '工种',
		);
		$param = array(
				'UC' => isset($post_data['UC']) ? trim($post_data['UC']) : '', // 手机号
				'PI' => isset($post_data['page']) ? intval($post_data['page']) : 0, // 页码
				'PS' => isset($post_data['rows']) ? intval($post_data['rows']) : 50, // 每页大小
				'GWC' => "", // 工种编号
				'S' => (isset($post_data['S']) && $post_data['S'] <> -1) ? $post_data['S'] : '' // 用户状态
		);
		$result = D('User')->seachUser($param, session('loginCookie'));
		$result = $result->DB;
		$data = array();
		foreach ($result as $k => $v) {
			$data[$k]['usercode'] = $v->usercode;
			$data[$k]['susername'] = $v->susername;
			$data[$k]['cardno'] = $v->cardno;
			$data[$k]['sex'] = getSexWorker($v->sex);
			$data[$k]['telnumber'] = $v->telnumber;
			$data[$k]['isuse'] = getStatusWorker($v->isuse);
			$data[$k]['registeTime'] = format_datatime(str_replace(':', '', str_replace(' ', '', $v->registeTime)));
			$data[$k]['workgroupname'] = $v->workgroupname;
		}
		exportCsv('工人.csv', $headdata, $data);
		exit;
	}
}
