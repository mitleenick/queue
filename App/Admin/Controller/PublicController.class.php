<?php

namespace Admin\Controller;
use Common\Controller\CoreController;
use Think\Log;

class PublicController extends CoreController {
	
	/* 登陆 */
    public function login(){
		layout(false);
        if(IS_POST){
			$username = I ( "post.name", "", "trim" );
			$password = I ( "post.password", "", "trim" );
			$code = I ( "post.code", "", "trim" );
			
			if (empty ( $username ) || empty ( $password )) {
				$this->error ( "用户名或者密码不能为空，请重新输入！", U ( C ( 'AUTH_USER_GATEWAY' ) ) );
			}
			
// 			if(!$this->check_verify($code)){
// 				$this->error ( "验证码错误，请重新输入！", U ( C ( 'AUTH_USER_GATEWAY' ) ) );
// 			}
					
			$logindate = date("Y-m-d", time());
			$param = array (
				'name'=>$username,
			);
			
			$result = D('User')->login($param);
			
			if(!empty($result)){
				if($result[0]['password']!=md5($password)){
					$this->error ( "密码错误！", U ( C ( 'AUTH_USER_GATEWAY' ) ) );
				}
				session('ModelKey.Admin',1);
				session('uid',$result[0]['id']);
				session('name',$result[0]['name']);
				

				$this->redirect(U('Index/index'));		
									
			}else{
				$this->error ( "用户名不存在！", U ( C ( 'AUTH_USER_GATEWAY' ) ) );
			}
			
        } else {
            if(is_login()){
                $this->redirect(U('Index/index'));
            }else{
				//$citys = D('User')->getCitys();
				//$this->assign('citys' , $citys);
                $this->display();
            }
        }
    }
	//验证码
	public function verify(){
		$Verify =     new \Think\Verify();
		$Verify->fontSize = 30;
		$Verify->length   = 4;
		$Verify->useNoise = false;
		$Verify->entry();
	}
	
	//验证验证码是否正确
	function check_verify($code, $id = ''){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}
    
    /* 退出登录 */
    public function logout(){
		layout(false);
		if (!is_login()) {
			$this->error ( "尚未登录", U ( C ( 'AUTH_USER_GATEWAY' ) ) );
		}else{
			//action_log('Admin_Logout', 'User', is_login());

			$wsdl_loginout = D('User')->loginout();
			session ( null );
			S('AdminMenu',null);
			S('domain' , null);
			S('loginCookie' , null);
			S('UserInfo' , null);
			S('ModelKey.Admin' , null);
			S('loginYMD' , null);
			if ($wsdl_loginout->RESULT==1 && session ('ModelKey.Admin')<>1) {
				$this->success ( "退出成功！", U ( C ( 'AUTH_USER_GATEWAY' ) ) );
			}else{
				$this->error ( "退出失败", U ( C ( 'AUTH_USER_INDEX' ) ) );
			}
		}
    }
    
    
    /**
	 * 工程搜索---项目编号模糊匹配
	 */
	public function searchProjectById(){
		$post_data=I('post.');

		if($post_data['q'] <> '') {
			$result = D('Project')->searchProjectById(array('projectCode'=>$post_data['q']) , session('loginCookie'));
			foreach ( $result as $k=>$v) {
				$result[$k]->ProjectName =  $v->ProjectCode . ' - ' . $v->ProjectName;
			}
		}else{
			$result = '';
		}

		echo json_encode($result);
		exit;
	}
	
    /**
	 * 工程搜索---业主名称模糊匹配
	 */
	public function searchProjectByOwner(){
		$post_data=I('post.');

		if($post_data['q'] <> '') {
			$result = D('Project')->searchProjectByOwner(array('owner'=>$post_data['q']) , session('loginCookie'));
		}else{
			$result = '';
		}
		
		echo json_encode($result);
		exit;
	}

    /**
	 * 工程搜索---项目名称模糊匹配
	 */
	public function searchProjectByProjectName(){
		$post_data=I('post.');

		if($post_data['q'] <> '') {
			$result = D('Project')->searchProjectByProjectName(array('projectName'=>$post_data['q']) , session('loginCookie'));
		}else{
			$result = '';
		}

		echo json_encode($result);
		exit;
	}
	
    /**
	 * 工程搜索---装修管家编号或者装修管家名称模糊匹配
	 */
	public function searchProjectByProjectManagerCode(){
		$post_data=I('post.');

		if($post_data['q'] <> '') {
			$result = D('Project')->searchProjectByProjectManagerCode(array('projectManagerCode'=>$post_data['q']) , session('loginCookie'));
			foreach ( $result as $k=>$v) {
				$result[$k]->ProjectName =  $v->ProjectManagerCode . ' - ' . $v->ProjectName;
			}
		}else{
			$result = '';
		}
		
		echo json_encode($result);
		exit;
	}

	/**
	 * 工种－－获取所有工种
	 */
	public function getGroupWorks(){
		$groupWorkers = D('User')->getGroupWorks(array(),session('loginCookie'));

		$result = array();
		foreach((array)$groupWorkers as $key=>$value){
			$obj['k'] = $key;
			$obj['v'] = $value;
			$result[] = $obj;
		}

		echo json_encode($result);
		exit;
	}

	public function acceptance()
	{
		layout(false);
		$get_data = I('get.');

		$pc = $get_data['pc'];
		$activeItemCode = $get_data['activeItemCode'];

		$this->assign('pc', $pc);
		$this->assign('activeItemCode', $activeItemCode);
		$this->display();
	}
	public  function projectSeach()
	{
		layout(false);
		$this->display();
	}
	/* 获取工艺明细数据*/
	public  function queryActiveitemSource()
	{
		layout(false);
		$get_data = I('get.');
		$param = array(
				'PC' => $get_data['pc'],
				'ACI' => $get_data['aci'],
				'ACII' => $get_data['acii']
		);
		$result = D('Active')->queryActiveitemSource($param, session('loginCookie'));
		$this->assign("data", json_encode($result));
		$this->assign("acn", $get_data['acn']);
		$this->assign("aci", $get_data['aci']);
		$this->display();
		exit;
	}
	public  function  planWork()
	{
		layout(false);
		$get_data = I('get.');
		$params = array(
				'beginDate' => $get_data['beginDate'],
				'endDate' => $get_data['endDate'],
				'activeitemins' => $get_data['activeitemins'],
				'ischangeUser' => $get_data['ischangeUser'],
				'projectcode' => $get_data['projectcode'],
				'activeitemcode' => $get_data['activeitemcode'],
				'activeitemname' => $get_data['activeitemname'],
				'workgroupcode' => $get_data['workgroupcode'],
				'money' => $get_data['money'],
		);
		$this->assign("params", json_encode($params));
		$this->display();
		exit;
	}
		//后台核心继承
    public function islogin()
	{
		if (session('ModelKey.Admin') != 1 || session('AdminMenu') == null) {
			echo 1;
		}else{
			echo 0;
		}
	}
	public function getprovincs()
	{
		layout(false);
		$citys = D('User')->getProvinces();
		echo json_encode($citys);
	}
	public function  getcitys()
	{
		layout(false);
		$parentid = I("get.")["provincecode"];
		$citys = D('User')->getCitys($parentid);
		echo json_encode($citys);
	}
}
