<?php

namespace Admin\Controller;

class WsTestController extends AdminCoreController {
	
	const USER_WSDL = 'http://124.205.40.87:8080/yhr/ws/userService?wsdl';
	const WORKER_WSDL = 'http://124.205.40.87:8080/yhr/ws/workerInfoService?wsdl';

	/*-----------------------------------------------------------------
	 * TEST FOR : 
	 * http://124.205.40.87:8080/yhr/ws/userService?wsdl              
	 *-----------------------------------------------------------------
	 */
    public function userlogin(){
		$param = array('UC'=>'18612247158','PWD'=>'123456','R'=>1,'lo'=>0,'la'=>0,'C'=>'12','YMD'=>'2016-01-22','DV'=>'');
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","login",$param);
		
		//setcookie('uc','18612247158');
		//3vbja65t5ske00q3q9hqbd90q1
    }
 
    public function userloginout(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","loginOut",array());
    }

    public function usergetcontext(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","getContext",array());
    }

    public function userGetMens(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","getMens",array());
    }

    public function usergetcitys(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","getCitys",array());
    }    
    
    public function getGroupWorks(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","getGroupWorks",array());
    } 
    
    public function seachUser(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","seachUser",array('UC'=>'','PI'=>1,'PS'=>1,'GWC'=>'','S'=>''));
    } 
    
    public function getUserRole(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","getUserRole",array('UC'=>'15110256547'));
    } 
    
    public function auditUser(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","auditUser",array('UC'=>'13401089424'));
    }
    
    public function addGroupWork(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","addGroupWork",array('GWN'=>'测试1'));
    }
    
   /* public function getUserForPlan(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/userService?wsdl","getUserForPlan",array('workGroupCode'=>'01','beginDate'=>'2016-01-01','endDate'=>'2016-02-01'));
    }*/

	/*-----------------------------------------------------------------
	 * TEST FOR : 
	 * http://124.205.40.87:8080/yhr/ws/workerInfoService?wsdl              
	 *-----------------------------------------------------------------
	 */
	public function getUserInfo(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/workerInfoService?wsdl","getUserInfo",array('userCode'=>'15110256547'));
    }
    public function getUserForPlan(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/workerInfoService?wsdl","getUserForPlan",array('workGroupCode'=>'02','beginDate'=>'2015-12-01 00:00:00','endDate'=>'2016-12-31 00:00:00'));
    }
	 
	/*-----------------------------------------------------------------
	 * TEST FOR : 
	 * http://124.205.40.87:8080/yhr/ws/activeService?wsdl              
	 *-----------------------------------------------------------------
	 */ 
	public function seachIns(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/activeService?wsdl","seachIns",array('PI'=>1,'PS'=>50,'PCS'=>'[10324,10326]','BT'=>'2015-12-01 00:00:00','ET'=>'2016-12-31 00:00:00','S'=>'','WGC'=>'','ISCU'=>0));
    } 
    
    public function myWorks(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/activeService?wsdl","myWorks",array('T'=>'31/1','D'=>'31'));
    } 
    
    public function allotUserToRes(){
		$this->wscall("http://124.205.40.87:8080/yhr/ws/activeService?wsdl","allotUserToRes",array('PC'=>10292,'RECORD'=>'31'));
    }
	 
    /**
	 * method call wsdl
	 * @author kevin 2015-01-12
	 */
    public function wscall($url , $method ,$param){
    	header("Content-Type: text/html;charset=utf-8");
    	try{
	     	$soap = new \SoapClient($url,array('encoding'=>'UTF-8','trace' => 1));
	     	
	     	$soap->__setCookie('JSESSIONID','DDE98FD5C5BAFD30BB6D0C30039BCD73');
	     	
	     	$result2 = $soap->__soapCall($method,$param);
	     	$result = json_decode($result2);
	     	
	     	echo '<b>response</b>:';
	     	echo '<pre>';
	     	print_r($result);
	     	echo '</pre>';
	     	
	     	echo '<pre>';
	     	echo "<b>request headers</b>:\n" . $soap->__getLastRequest() . "\n";
	     	
	     	echo '<pre>';
	     	echo "<b>response headers</b>:\n" . $soap->__getLastResponseHeaders() . "\n";
	     	
	     	preg_match('/Set-Cookie: (.*);/iU',$soap->__getLastResponseHeaders(),$str); //正则匹配  
	     	$cookie = $str[1]; //获得COOKIE(SESSIONID)
	     	$cookie = explode('=',$cookie);
	     	echo '<pre>';
	     	echo "<b>response headers cookie:</b>:\n";
	     	print_r($cookie);
	     	echo "\n" ."\n";
	     	
	     	echo "<b>functions</b> :\n" ;
	     	print_r($soap->__getFunctions  ());
	     	echo '</pre>';
		 }catch(SoapFault $e){
		     echo $e->getMessage();
		 }catch(Exception $e){
		     echo $e->getMessage();
		 }
		 exit; 
    }
}
?>