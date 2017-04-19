<?php 
/**
 *@author mit 
 *@date 2017.03 
 */
namespace Admin\Controller;
use Common\Controller\CoreController;

class UserController  extends AdminCoreController{
	public function vip(){
		$count=M('user')->where(array('auth'=>'VIP'))->count();
		$page=new \Org\Util\Page($count,10);		
		$result =M('user')->where(array('auth'=>'VIP'))->limit($page->firstRow.','.$page->listRows)->select();
		$show=$page->show();
		$this->assign('list',$result);
		$this->assign('page',$show);
		$this->display();
	}	
	public function common(){
		$count=M('user')->where(array('auth'=>'common'))->count();
		$page=new \Org\Util\Page($count,10);		
		$result =M('user')->where(array('auth'=>'common'))->limit($page->firstRow.','.$page->listRows)->select();
		$show=$page->show();
		$this->assign('list',$result);
		$this->assign('page',$show);
		$this->display();
	}
	public function officer(){
		$count=M('admin')->count();
		$page=new \Org\Util\Page($count,10);		
		$result =M('admin')->limit($page->firstRow.','.$page->listRows)->select();
		$show=$page->show();
		$this->assign('list',$result);
		$this->assign('page',$show);
		$this->display();
	}
	public function delete(){
		$id = I('id');
		$id = M('user')->delete(array('id'=>$id));
		if($id){
			$this->success('删除成功',U ('User/vip' ));
		}
	}
	
	public function deleteOfficer(){
		$id = I('id');
		$id = M('admin')->delete(array('id'=>$id));
		if($id){
			$this->success('删除成功',U ('User/common' ));
		}
	}
	
	public function addOfficer(){
		if($_POST){
			$data['name']=I('name');
			$data['password']=I('password');
			$data['icard']=I('icard');
			$data['lastTime']=I('lastTime');
			$id = M('admin')->add($data);
			if($id){
				$this->success('添加成功',U ('User/common' ));
			}
		}else{
			$company = M('company')->select();
			$this->assign('company',$company);
			$this->display();
		}
	}
}