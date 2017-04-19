<?php 
/**
 *@author mit 来活
 *@date 2017.03 
 */
namespace Admin\Controller;
use Common\Controller\CoreController;

class EvaluateController extends CoreController{
	/* 列表(默认首页)
	 * Auth   : kevin
	* Time   : 2016-01-18
	**/
	public function index(){
		$evaluate = M('evaluate')->select();
		foreach ($evaluate as $key=>$val){
			switch ($val['star']){
				case 1: $evaluate[$key]['star']='一星'; break;
				case 2: $evaluate[$key]['star']='二星'; break;
				case 3: $evaluate[$key]['star']='三星'; break;
				case 4: $evaluate[$key]['star']='四星'; break;
				case 5: $evaluate[$key]['star']='五星'; break;
			}
		}
		$this->assign('list',$evaluate);
		$this->display();
	}

}