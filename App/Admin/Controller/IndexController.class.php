<?php
namespace Admin\Controller;

class IndexController extends AdminCoreController {
	
    public function index(){
		//$this->assign ( 'AdminMenu', $this->get_menu());
		$this->display ();
    }
    
    /* @name:menu
     * @title:导航菜单
     * @type:1
     */
    /*public function menu(){
		$pid=I('get.pid',0);
		$AdminMenu = $this->get_menu();
		$this->assign ( 'LeftMenu', $AdminMenu[$pid]['children']);
		$this->display ('menu_'.C('LEFT_MENU_STYLE'));
    }*/
	
    /* @name:cache
     * @title:缓存
     * @type:1
     */
    /*public function cache(){
		D('Config')->cache();
		D('Action')->cache();
		D('ActionLog')->cache();
		$this->success('缓存更新成功！',U('Admin/Index/Index'));
    }*/
  
}