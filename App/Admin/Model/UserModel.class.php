<?php 
/**
 * 用户管理模型
 */
namespace Admin\Model;
use Think\Model;

class UserModel{
    
    //const WSDL_URL_USER = 'http://124.205.40.87:8080/yhr/ws/userService?wsdl';login
    //const WSDL_URL_USER = 'http://'. getDomain .'/yhr/ws/userService?wsdl';
    const USER_LOGIN_FAILT = 0;//登陆不成功
    const USER_LOGIN_SUCCESS = 1 ;//登陆成功
    const USER_LOGIN_ERROR_PWD = 2; //密码错误
    const USER_LOGIN_ERROR_ACCOUNT = 3; //账号错误

	protected static function  getwsdldomain(){
		$domain = getDomain();
		return 'http://'.$domain.'/yhr/ws/userService?wsdl';
	}

	public static function erpEvent($param,$loginCookie){
		$data = wscall(self::getwsdldomain(),"erpEvent",$param,$loginCookie,true);
		return $data;
    }
    
    /**
     * 登陆
     * @author kevin
     * @date 2016-01-12
     */
    public static function login($param){
		$admin=M('admin');
		$result = $admin->select($param);
		return $result;
		
    }
    
    /**
     * 登出
     * @author kevin
     * @date 2016-01-12
     */
    public static function loginOut(){
    	$data = wscall(self::getwsdldomain(),"loginout",array(),null,false);
		return $data;
    }

	/**
     * 获取开通城市
     * @author kevin
     * @date 2016-01-12
     */
	public static function getCitys($parentId)
	{
		$data = wscall(getDefaultDomain(), "getCitys", Array(parentId => $parentId), null, false);
		return $data->RESULT;
	}
	/**
 * 获取开通省份
 * @author 常福鹏
 * @date 2016-07-21
 */
	public static function getProvinces()
	{
		$data = wscall(getDefaultDomain(), "getProvinces", Array(), null, false);
		return $data->RESULT;
	}

	/**
     * 获取上下文(登陆用户相关信息)
     * @author kevin
     * @date 2016-01-12
     */
	public static function getContext($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"getContext",$param,$loginCookie,false);
		return $data->RESULT;
	}
	
	/**
     * 获取用户能够使用的菜单
     * @author kevin
     * @date 2016-01-12
     */
	public static function getMens($param,$loginCookie){
		$data = wscall(self::getwsdldomain(),"getMens",$param,$loginCookie,false);
		$menus = $data->RESULT;
		
		/*foreach ( $menus as $k=>$v) {
			if(!$v->MENUPATH){
				switch ( $v->MENUCODE ) {
					case 'MENU01':  $menus[$k]->MENUPATH = 'Admin/Worker/index'; break;
					case 'MENU02':  $menus[$k]->MENUPATH = 'Admin/Train/index'; break;
					case 'MENU03':  $menus[$k]->MENUPATH = 'Admin/Work/index'; break;
					case 'MENU04':  $menus[$k]->MENUPATH = 'Admin/Material/index'; break;
					case 'MENU05':  $menus[$k]->MENUPATH = 'Admin/Distribution/index'; break;
					case 'MENU06':  $menus[$k]->MENUPATH = 'Admin/Audit/index'; break;
					case 'MENU07':  $menus[$k]->MENUPATH = 'Admin/Payroll/index'; break;
					case 'MENU08':  $menus[$k]->MENUPATH = 'Admin/Settlement/index'; break;
					case 'MENU09':  $menus[$k]->MENUPATH = 'Admin/Procedure/index'; break;
					case 'MENU010': $menus[$k]->MENUPATH = 'Admin/WorkStatistics/index'; break;
					case 'MENU011': $menus[$k]->MENUPATH = 'Admin/Leaflets/index'; break;
					//default: break;
				}
			}
		}*/
		
		/*foreach ( $menus as $k=>$v) {
			$p = explode('/' , $v->MENUPATH) ;
			$menus[$k]->MENUURL = $_SERVER["REQUEST_URI"] . '/index.php?m=' .$p[0]. '&c=' .$p[1]. '&a=' .$p[2];
		}*/
		
		return $menus;
	}
	
	/**
     * 获取全部的工种基础数据
     * @author kevin
     * @date 2016-01-18
     * 
     * demo : 
     	stdClass Object
		(
		    [RESULT] => stdClass Object
		        (
		            [08] => 洁具安装工
		            [04] => 保洁
		            [05] => 油工
		            [06] => 门安装工
		            [07] => 地板安装工
		            [00] => 装修管家
		            [01] => 木工
		            [02] => 瓦工
		            [03] => 水电
		        )
		
		    [STATUS] => 200
		)
     */
	public static function getGroupWorks($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"getGroupWorks",$param,$loginCookie,false);
		return $data->RESULT;
	}
	
	/**
	 * 
	 */
	public static function seachUser($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"seachUser",$param,$loginCookie,false);
		return $data->RESULT;
	}
	
	/**
	 * 实名认证
	 */
	public static function auditUser($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"auditUser",$param,$loginCookie,false);
		return $data->RESULT;
	}
	
	/**
	 * 新增工种
	 */
	public static function addGroupWork($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"addGroupWork",$param,$loginCookie,false);
		return $data->RESULT;
	}

	/**
	 * 删除工种
	 */
	public static function deleteGroupWork($param , $loginCookie){
		$data = wscall(self::getwsdldomain(),"deleteGroupWork",$param,$loginCookie,false);
		return $data->RESULT;
	}
	
	/**
     * 派单查询用户
     * @param string workGroupCode 工种编号
     * @param string beginDate 开始时间
     * @param string endDate 结束时间
     * @author kevin
     * @date 2016-01-12
     */
    public static function getUserForPlan(){
    	$data = wscall(self::getwsdldomain(),"getUserForPlan",array(),null,false);
		return $data;
    }
	
	/**
     * 上传文件
	 * @param int contentType 图片＝0，文本＝1，zip压缩＝2
	 * @param string content base64
     * @author kevin
     * @date 2016-01-12
     */
	public static function uploadFile(){
		$data = wscall(self::getwsdldomain(),"uploadFile",array(),null,false);
		return $data;
	}
        
        	/**
	 * 上传文件
	 * @param int contentType 图片＝0，文本＝1，zip压缩＝2
	 * @param string content base64
	 * @author kevin
	 * @date 2016-01-12
	 */
	public static function uploadFile_new($param,$loginCookie){
		$data = wscall(self::getwsdldomain(),"uploadFile",$param,$loginCookie,false);
		return $data;
	}
}