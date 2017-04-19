<?php
function Is_Auth($Auth_Rule){
	$Auth = new \Common\Libs\Auth();
	$AUTH_KEY=session(C('AUTH_KEY'));
	//判断当前认证key是否不在 超级管理组配置中,或者当前模块是否为非认证模块
	if (! is_admin($AUTH_KEY) && ! in_array ( CONTROLLER_NAME, explode ( ",", C ( "NOT_AUTH_MODULE" ) ) )) {
		//当前权限表达式
		$Auth_Rule = MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME;
		if (! $Auth->check ($Auth_Rule,$AUTH_KEY)) {
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}


//后台菜单
function get_menu(){
	//获取后台菜单缓存
	//$AdminMenu=session('AdminMenu');
	$AdminMenu=session('AdminMenu');
	//如果缓存为空，即初次登录
	if( !$AdminMenu ) {
		$AdminMenu = D('User')->getMens(array(), session('loginCookie'));

		//session('AdminMenu', null);
		//session('AdminMenu', $AdminMenu);
		if (!$AdminMenu) {
			session('ModelKey.Admin', null);
			redirect(U(C('AUTH_USER_GATEWAY')));
			//document . location = U(C('AUTH_USER_GATEWAY'));
			//error("请重新登录", U(C('AUTH_USER_GATEWAY')));
		}
		//session ( 'AdminMenu', null );
		//session('AdminMenu',$AdminMenu);
	}

	return $AdminMenu;

}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 获取默认domain
 */
function getDefaultDomain(){
	return 'http://'. C('DEFAULT_DOMAIN') .'/yhr/ws/userService?wsdl';
}

/**
 * 获取domain,登陆后储存在session里的
 */
function getDomain(){
	return session('domain') ;
	//return '124.205.40.87:8080';
}

/**
 * 格式化日期输出
 * 20160127093612 -> 2016-01-27 09:36:12
 */
function format_datatime($str)
{
	$year = substr($str, 0, 4);
	$month = substr($str, 4, 2);
	$day = substr($str, 6, 2);
	$hour = substr($str, 8, 2);
	$min = substr($str, 10, 2);
	$sec = substr($str, 12, 2);

	$temp = $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
	if ($temp != '-- ::') {
		return $temp;
	} else {
		return '';
	}
}


/**
 * 获取指定日期所在季度第一天
 */
function firstDayOfSeason($date=null){
	if(!$date){
		$logindate = session('loginYMD');
		if($logindate) {
			$date = $logindate;
		}else{
			$date = date(time());
		}
	}

	$season = ceil((date('n' , strtotime($date)))/3);//当月是第几季度

	return date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y')));
}

/**
 * 获取指定日期所在季度最后一天
 */
function lastDayOfSeason($date){
	if(!$date){
		$logindate = session('loginYMD');
		if($logindate) {
			$date = $logindate;
		}else{
			$date = date(time());
		}
	}

	$season = ceil((date('n' , strtotime($date)))/3);//当月是第几季度

	return date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y')));
}

/**
 * 返回$date所在月的第一天
 * eg : 2016-03-09
 * return : 2016-03-01
 */
function firstDayOfMonth($date)
{
	if ($date == null)
		$date = time();
	return date('Y-m-01 00:00:00', strtotime($date));
}

/**
 * 返回$date所在月的最后一天
 * eg : 2016-03-09
 * return : 2016-03-31 23:59:59
 */
function lastDayOfMonth($date)
{
	if ($date == null)
		$date = time();
	return date('Y-m-t 23:59:59', strtotime($date));
}

/**
 * 单据类型
 * @author kevin
 * @date 2016-01-27
 */
function getRtype($code){
	
	$rtype = C('RTYPE');
	
	if($code || $code==0){
		return $rtype["$code"] ? $rtype["$code"] : '未知类型';
	}else{
		return $rtype;
	}
}

/**
 * 单据类型
 * @author kevin
 * @date 2016-01-27
 */
function getMtype($code){
	$mtype = C('MTYPE');
	
	if($code  || $code==0){
		return $mtype["$code"] ? $mtype["$code"] : '未知类型';
	}else{
		return $mtype;
	}
}

/**
 * 派工单状态枚举
 * @author kevin
 * @date 2016-02-04
 */
function getStatusPzd($code){
	$statusPzd = C('STATUS_PZD');

	if($code || $code==0){
		return $statusPzd["$code"] ? $statusPzd["$code"] : '未启动';
	}else{
		return $statusPzd;
	}
}

/**
 * 工人状态
 * @author kevin
 * @date 2016-02-04
 */
function getStatusWorker($code){
	$statusWorker = array(
		'0'=>'新注册',
		'1'=>'正常',
		'2'=>'上传待认证',
		'3'=>'审核不通过',
		'4'=>'注销'
	);

	if($code || $code==0){
		return $statusWorker["$code"] ? $statusWorker["$code"] : '未知类型';
	}else{
		return $statusWorker;
	}
}

/**
 * 工人性别
 * @author kevin
 * @date 2016-02-04
 */
function getSexWorker($code){
	$sexWorker = array(
		'0'=>'女',
		'1'=>'男'
	);

	if($code || $code==0){
		return $sexWorker[$code] ? $sexWorker[$code] : '未知';
	}else{
		return $sexWorker;
	}
}

/**
 * 审计状态
 * @author kevin
 * @date 2016-02-04
 */
function getStatusAudit($code){
	$statusAudit = array(
		'0'=>'申请中',
		'1'=>'申请中',
		'F'=>'审核通过',
		'H'=>'通过待入账',
		'E'=>'打回',
	);

	if($code || $code==0){
		return $statusAudit["$code"] ? $statusAudit["$code"] : '未知类型';
	}else{
		return $statusAudit;
	}
}

/**
 * 结算状态
 * @author kevin
 * @date 2016-02-04
 */
function getStatusSettlement($code){
	$statusSettlement = array(
		'0'=>'申请中',
		'F'=>'审核通过',
		'E'=>'审核不通过',
		'G'=>'已打款',
	);

	if($code || $code==0){
		return $statusSettlement["$code"] ? $statusSettlement["$code"] : '未知类型';
	}else{
		return $statusSettlement;
	}
}

/**
 * 劳务费状态
 * @author kevin
 * @date 2016-02-04
 */
function getStatusPayroll($code){
	$statusPayroll = array(
		'1'=>'申请中',
		'F'=>'审核通过',
		'E'=>'审核不通过',
		'H'=>'通过待入账',
		'G'=>'已打款',
	);

	if($code || $code==0){
		return $statusPayroll["$code"] ? $statusPayroll["$code"] : '未知类型';
	}else{
		return $statusPayroll;
	}
}

/**
 * 调用数据接口
 * @param string url 接口地址
 * @param string method 方法名
 * @param array|string param 接口参数
 * @param string cooike 设置请求cookie
 * @param boolen responseHeader 是否返回response header cookie信息
 * @author kevin
 * @date 2016-01-12
 */
function wscall($url , $method ,$param , $cookie=null , $responseHeader=false)
{
	header("Content-Type: text/html;charset=utf-8");
	ini_set('soap.wsdl_cache_enabled', "0");
	try {
		$soap = new \SoapClient($url, array('encoding' => 'UTF-8', 'trace' => 1));
		if ($cookie) {
			$soap->__setCookie('JSESSIONID', $cookie);
		}
		$result = $soap->__soapCall($method, $param);

		$data = json_decode($result);
		$status = $data->STATUS;
		if ($status == 100)//登录操作
		{
			session('ModelKey.Admin', null);
			redirect(U(C('AUTH_USER_GATEWAY')));
			//header('Location: '.U(C('AUTH_USER_GATEWAY')), true, 303);
			//header('Location: '.U(C('AUTH_USER_GATEWAY')), true, 307);
			return $data;
		}


		if ($responseHeader) {
			preg_match('/Set-Cookie: (.*);/iU', $soap->__getLastResponseHeaders(), $str); //正则匹配
			$cookie = $str[1]; //获得COOKIE(SESSIONID)
			$cookie = explode('=', $cookie);
			$data->cookie = $cookie;
		}
		return $data;
	} catch (SoapFault $e) {
		echo $e->getMessage();
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

/**
 * 导出cvs
 * @param string $filename csv文件名 eg:xxx.csv
 * @param array $headdata 表头数组
 * eg:
 * $headdata = array(
            'goods_name'=>'商品名称',
            'sale_type'=>'售卖渠道',
        );
 * @param array $data 数据数组
 * @author kevin
 */
function exportCsv($filename,$headdata,$data)   
{   
    header("Content-type:text/csv");   
    header("Content-Disposition:attachment;filename=".$filename);   
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');   
    header('Expires:0');   
    header('Pragma:public');

	// 打开PHP文件句柄，php://output 表示直接输出到浏览器
	$fp = fopen('php://output', 'a');
	// 输出Excel列名信息
	$head = array();
	foreach($headdata as $key=>$value){
	    $head[] = iconv('utf-8','gb2312',$value);//头信息
	    //$head[] = $value;//头信息
	}
	// 将数据通过fputcsv写到文件句柄
	fputcsv($fp, $head);
	// 计数器
	$cnt = 0;
	// 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
	$limit = 10000;
	// 逐行取出数据，不浪费内存
	$count = count($data);
	
	for($t=0;$t<$count;$t++) {

	    $cnt ++;
	    if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
	        ob_flush();
	        flush();
	        $cnt = 0;
	    }
	    foreach ($data[$t] as $i => $v) {	
	    	$row[$i] = iconv('utf-8','gb2312',$v);
	    	//$row[$i] = $v;
	    	$row[$i] = is_numeric($row[$i]) ? "\t".$row[$i] : $row[$i];
	    }
	    fputcsv($fp, $row);
	    unset($row);
	}   
}