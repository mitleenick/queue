<?php 
/**
 *@author mit 来活
 *@date 2017.03 
 */
namespace Admin\Controller;
use Common\Controller\CoreController;

class StatisticsController  extends CoreController{
	/* 列表(默认首页)
	 * Auth   : kevin
	* Time   : 2016-01-18
	**/
	public function index(){
			$this->display();
	}	
	
	public function sumCustomer(){
		
	}
	
	//总数量
	public function sumNum(){
		
	}
	
	public function abandonNum(){
		
	}
	
	public function waitTime(){
		
	}
	
	public function dealTime(){
		
	}

	public function countTel(){
		
	}
	
	public function avgTime(){
		$result = M('conduct')->select();
		$conduct = count($result);  //办理总数

		foreach($result as $key=>$val){
			$conductTime[]=$val['endtime']-$val['calltime'];
		}

		$maxTime= max($conductTime); //最大时间
		$minTime=min($conductTime); //最小时间
		$allTime = array_sum($conductTime);
		$avgTime=ceil($allTime/$conduct); //平均时间
		
		return array('count'=>$conduct,'maxtime'=>$maxTime,'mintime'=>$minTime,'avgtime'=>$avgTime);
	}
	
	public function maxBecall(){
		
	}
	
	public function minBecall(){
		
	}
}