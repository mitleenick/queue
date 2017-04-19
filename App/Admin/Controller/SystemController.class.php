<?php 
/**
 *@author mit 来活
 *@date 2017.03 
 */
namespace Admin\Controller;
use Common\Controller\CoreController;

class SystemController extends CoreController{
	/* 列表(默认首页)
	 * Auth   : kevin
	* Time   : 2016-01-18
	**/
	public function index(){
		
	}
	
	/**
	 * 系统设置
	 */
	public function setSystem(){
		if($_POST){
			$data['id']=1;
			$data['fontSize']=I('fontSize');
			$data['fontColor']=I('fontColor');
			$data['maxCall']=I('maxCall');
			$data['fontType']=I('fontType');
			$id = M('system')->save($data);
			if($id){
				$this->success('更新成功',U ('System/setSystem' ));
			}
		}else{
			$result = M('system')->select();
			$this->assign('list',$result);
			$this->display();
		}
		
	}
	
	
}