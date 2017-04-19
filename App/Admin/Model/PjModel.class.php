<?php 
/**
 * 评分管理模型
 */
namespace Admin\Model;
use Think\Model;

class PjModel{
    
    protected static function  getwsdldomain()
    {
    	$domain = getDomain();
    	return 'http://'. C('DEFAULT_DOMAIN') .'/yhr/ws/pjlogService?wsdl';
    }
	/**
	 * 新增评分
	 */
	public function addPj($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"addPjlog",$param,$loginCookie,false);
		return $data->RESULT;
	}
	/**
	 * 获取评分数据
	 */
	public function getPj($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"getPjlog",$param,$loginCookie,false);
		return $data->RESULT;
	}
	/*
	 * 修改功能
	 */
	public function upPj($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"upPjlog",$param,$loginCookie,false);
		return $data->RESULT;
	}	
	/*
	 * 删除功能
	 */
	
	public function delPj($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"deletePjlog",$param,$loginCookie,false);
		return $data->RESULT;
	}
	

}