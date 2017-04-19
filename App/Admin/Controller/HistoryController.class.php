<?php 
/**
 *@author mit 来活
 *@date 2017.03 
 */
namespace Admin\Controller;
use Common\Controller\CoreController;

class HistoryController  extends AdminCoreController{
	/* 列表(默认首页)
	 * Auth   : kevin
	* Time   : 2016-01-18
	**/
	public function queue(){
			$this->display();
	}	
	
	public function specialCall(){
		$this->display();
	}
	
	public function devolve(){
		$this->display();
	}
	
	public function abandon(){
		$this->display();
	}
}