<?php

namespace Admin\Controller;
use Common\Controller\CoreController;

/**
 * 培训管理
 */
class TrainController extends AdminCoreController {
	
	/* 列表(默认首页)
     * Auth   : kevin
     * Time   : 2016-01-29
     **/
    public function index(){

		$post_data=I('post.');
		$isAjax = $post_data['isAjax'];

		//全部的工种基础数据
		$groupWorkers = D('User')->getGroupWorks(array(),session('loginCookie'));
		$this->assign('groupWorkers',$groupWorkers);
		$groupWorkersKeys = array_keys((array)$groupWorkers);

		//过滤条件
		$param = array (
			'isExam' => isset( $post_data['isExam'] ) ?  $post_data['isExam'] : 1, // 是否考题，默认为考题
			'workGroupCode' =>(  isset( $post_data['workGroupCode'] ) && $post_data['workGroupCode']<>-1) ?  $post_data['workGroupCode'] : $groupWorkersKeys[0], // 结束日期
		);

		//$param = $this->_search();
		$result = D('Exam')->getExams($param , session('loginCookie'));

		if($isAjax==1){
			$this->ajaxReturn($result);
			exit;
		}else{
			$this->assign ( 'list', $result);
			$this->display();
		}
    }

	/* 启用、禁用
     * Auth   : kevin
     * Time   : 2016-01-29
     **/
	public function examStatus(){
		$post_data=I('post.');

		$param = array(
			'examCode'=>$post_data['examCode'],
			'isUse'=>$post_data['isUse']
		);

		$status = D('exam')->examStatus($param , session('loginCookie'));

		echo $status;
		exit;
	}

	public function removeExam(){
		$post_data=I('post.');

		$param = array(
				'examCode'=>$post_data['examCode']
		);

		$status = D('exam')->removeExam($param , session('loginCookie'));
		echo$status;
		exit;
	}

	/**
	 * 上传文件
	 */
	public function uploadfile(){
		header("Content-Type: text/html;charset=utf-8");
		$post_data=I('post.');
		print_r($post_data);
		print_r($_FILES);

		$upload = new \Think\Upload();// 实例化上传类

		$files = $upload->dealFiles($_FILES);

		echo '<br>';
		print_r($files);

		exit;


	}

}
