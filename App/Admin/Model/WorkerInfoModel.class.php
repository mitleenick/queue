<?php 
/*
 * 用户管理模型
 * wsdl   : http://124.205.40.87:8080/yhr/ws/userService?wsdl
 */
 
namespace Admin\Model;
use Think\Model;

class WorkerInfoModel{
    
    //const WSDL_URL_USER = 'http://124.205.40.87:8080/yhr/ws/workerInfoService?wsdl';

    protected static function  getwsdldomain(){
        $domain = getDomain();
        return 'http://'.$domain.'/yhr/ws/workerInfoService?wsdl';
    }
    
    /**
     * 派单查询用户
     * @param string workGroupCode 工种编号
     * @param string beginDate 开始时间
     * @param string endDate 结束时间
     * param eg : array('workGroupCode'=>'02','beginDate'=>'2015-12-01 00:00:00','endDate'=>'2016-12-31 00:00:00')
     * @author kevin
     * @date 2016-01-22
     */
    public static function getUserForPlan($param,$loginCookie){
		$data = wscall(self::getwsdldomain(),"getUserForPlan",$param,$loginCookie,false);
		return $data->RESULT;
    }
    
    /**
     * 劳务费-查询
     * @param int pageNumber 页码
     * @param int pageSize 每页数据量
     * @param string type 流水号类型
     * @param string userCode 用户编号
     * @param string beginDate 开始时间
     * @param string endDate 结束时间
     * @author kevin
     * @date 2016-01-29
     */
    public static function searchMoney($param,$loginCookie){
		$data = wscall(self::getwsdldomain(),"searchMoney",$param,$loginCookie,false);
		return $data->RESULT;
    }
    /**
     * 入账凭证转换PDF
     * @author 常福鹏
     * @date 2016-04-20
     */
    public static function loadMoneySettlementPDF($param,$loginCookie){
        $data = wscall(self::getwsdldomain(),"loadMoneySettlementPDF",$param,$loginCookie,false);
        if ($data->STATUS == 200) {
            $data->RESULT = "http://" . getDomain() . "/yhr/" . $data->RESULT;
        }
        return $data;
    }
    /**
 * 提现申请PDF
 * @author 常福鹏
 * @date 2016-04-20
 */
    public static function loadMoneyExtractPDF($param,$loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "loadMoneyExtractPDF", $param, $loginCookie, false);
        if ($data->STATUS == 200) {
            $data->RESULT = "http://" . getDomain() . "/yhr/" . $data->RESULT;
        }
        return $data;
    }
}