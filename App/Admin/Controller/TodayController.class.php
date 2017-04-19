<?php 
/**
 *@author mit 来活
 *@date 2017.03 
 */
namespace Admin\Controller;
use Common\Controller\CoreController;

class TodayController  extends AdminCoreController{
	/* 列表(默认首页)
	 * Auth   : kevin
	* Time   : 2016-01-18
	**/
	public function queue(){
		$count=M('queue')->count();
		$page=new \Org\Util\Page($count,8);		
		$result =M('queue')->limit($page->firstRow.','.$page->listRows)->select();
		$show=$page->show();
		$this->assign('list',$result);
		$this->assign('page',$show);
		$this->display();
	}	
	/*
	 * 特呼号码
	 */
	public function specialCall(){
		$this->display();
	}
	/*
	 * 转移
	 */
	public function devolve(){
		$this->display();
	}
	/*
	 * 弃号
	 */
	public function abandon(){
		//获取今天00:00
		$todaystart = date('Y-m-d'.' 00:00:00',time());
		//获取今天24:00
		$todayend = date('Y-m-d'.' 00:00:00',time()+3600*24);
		//统计今天注册的用户
		$todayuser['addTime'] = array(between,"$todaystart,$todayend");
		$result = M('abandon')->where($todayuser)->select();
		echo M('abandon')->getLastSql();
		$this->assign('list',$result);
		$this->display();
	}
}