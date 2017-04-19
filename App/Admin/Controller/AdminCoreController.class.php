<?php
namespace Admin\Controller;
use Common\Controller\CoreController;
use Think\Log;

class AdminCoreController extends CoreController {
	
	//后台核心继承
    protected function _initialize()
	{
		if (session('ModelKey.Admin') != 1) {
			if (MODULE_NAME != 'login') {
				$this->redirect(U(C('AUTH_USER_GATEWAY')));
				//header("Location:" . U(C('AUTH_USER_GATEWAY')));
				return;
			}
		}
		//继承CoreController的初始化函数
// 		parent::_initialize();
// 		$Auth_Rule = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
// 		log::write("Auth_Rule=" . $Auth_Rule, "INFO");
// 		if (session('ModelKey.Admin') != 1 || session('AdminMenu') == null) {
// 			if (MODULE_NAME != 'login') {
// 				$this->redirect(U(C('AUTH_USER_GATEWAY')));
// 				//header("Location:" . U(C('AUTH_USER_GATEWAY')));
// 				return;
// 			}
// 		}
		/*else {
			ini_set('soap.wsdl_cache_enabled', "0");
			wscall(getDefaultDomain(),"getCitys",array(),null,false);
		}*/
	}
	
	//后台菜单
	/*protected function get_menu(){
		//获取后台菜单缓存
		$AdminMenu=session('AdminMenu');
		//如果缓存为空，即初次登录
		if(count($AdminMenu)!=999){
			$AdminMenu = D('User')->getMens(array(),session('loginCookie'));
			session ( 'AdminMenu', null );
			session('AdminMenu',$AdminMenu);
		}
		
		return $AdminMenu;
		
	}*/
}