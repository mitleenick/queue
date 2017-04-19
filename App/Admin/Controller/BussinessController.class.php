<?php 
/**
 *@author mit 来活
 *@date 2017.03 
 */
namespace Admin\Controller;
use Common\Controller\CoreController;

class BussinessController extends CoreController{
	/* 列表(默认首页)
	 * Auth   : kevin
	* Time   : 2016-01-18
	**/
	public function index(){
		$result = M('bussiness')->select();
		$this->assign('list',$result);
		$this->display();
	}
	
	public function setTime(){
		$result = M('shophour')->select();
		$this->assign('list',$result);
		$this->display();
	}
	
	public function addBussiness(){
		$bussiness = I('title');
		$id = M('bussiness')->add(array('name'=>$bussiness));
		if($id){
			$this->success('添加成功',U ('Bussiness/index' ));
		}
	}
	
	public function getAll(){
		$bussiness = M('bussiness')->order('sort asc')->select();
		$infos = A('Statistics')->avgTime();
		foreach ($bussiness as $key=>$val){
			$queues = M('queue')->where(array('bussiness'=>$val['id']))->select();
			$bussiness[$key]['queuecount']=count($queues);			
			
			if($infos['avgtime']){
				$bussiness[$key]['queuetime'] = count($queues)*$infos['avgtime']; //排队时间
			}
		}
		echo base64_encode(json_encode($bussiness,JSON_UNESCAPED_UNICODE));
	
	}
	
	public function addTime(){
		$params['amStartTime'] = I('amstime');
		$params['amEndTime'] = I('amendtime');
		$params['pmStartTime'] = I('pmstime');
		$params['pmEndTime'] = I('pmendtime');
		$params['companyID'] = I('company');
		$params['addTime'] = date("Y-m-d H:i:s",time());
		$params['remark'] = I('remark');
		$id = M('shophour')->add($params);
		if($id){
			$this->success('添加成功',U ('Bussiness/setTime' ));
		}
	}
	
	public function updateSort(){
		$id = I('id');
		$sort = I('sort');
		$id = M('bussiness')->where(array('id'=>$id))->save(array('sort'=>$sort));
		if($id==0||$id==false){
			echo '-1';
		}else{
			echo 'ok';
		}
	}
	//窗口列表
	public function window(){
		$admin = M('admin')->select();
		$bussiness=M('bussiness')->select();
		$company=M('company')->select();
		$window = M('windows')->select();
		$this->assign('list',$window);
		$this->assign('admin',$admin);
		$this->assign('bussiness',$bussiness);
		$this->assign('company',$company);
		$this->display();
	}
	//添加窗口
	public function setWindows(){
		$params['name'] = I('name');
		$params['addTime'] = I('addTime');
		$params['bussiness'] = I('bussiness');
		$params['company'] = I('company');
		$params['uid'] = I('uid');
		$id = M('window')->add($params);
		if($id){
			$this->success('添加成功',U ('Bussiness/window' ));
		}
	}
	
	
}