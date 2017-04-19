<?php 

namespace Admin\Model;
use Think\Model;

class ActiveModel
{

    //const WSDL_URL_USER = 'http://124.205.40.87:8080/yhr/ws/activeService?wsdl';
    const T1 = 'http://124.205.40.87:8080/yhr/ws/workerInfoService?wsdl';

    protected static function  getwsdldomain()
    {
        $domain = getDomain();
        //var_dump($domain) ;
        return 'http://' . $domain . '/yhr/ws/activeService?wsdl';
    }

    /**
     * 派单-获取等待派单任务(T，S，PCS 空则不进行过滤)
     * @param int PI 页码
     * @param int PS 每页数据量
     * @param string PCS 项目编号集合[]
     * @param string BT 开始时间
     * @param string ET 结束时间
     * @param string S 状态
     * @param string WGC 工种编号
     * @param string ISCU 是否改人单 0,1
     * param eg : array('PI'=>1,'PS'=>50,'PCS'=>'[10324,10326]','BT'=>'2015-12-01 00:00:00','ET'=>'2016-12-31 00:00:00','S'=>'','WGC'=>'','ISCU'=>0)
     * @author kevin
     * @date 2016-01-22
     */
    public static function seachIns($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "seachIns", $param, $loginCookie, true);
        return $data->RESULT;
    }

    /**
     * 派单-锁定待派单任务
     * @param datatype paramname description
     * @author kevin
     * @date 2016-01-22
     */
    public static function lockWorks($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "LockWorks", $param, $loginCookie, false);
        return $data->STATUS;
    }

    /**
     * 派单-获取用户的工作内容
     * @param string T T=D天/M月
     * @param int D D=31
     * @author kevin
     * @date 2016-01-22
     */
    public static function myWorks($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "myWorks", $param, $loginCookie, false);
        return $data->RESULT;
    }
    
    /**
     * 派单-获取用户的工作内容
     * @param string UC 工人ID
     * @param int limit记录条数
     * @author mit
     */
    public static function myRecentWorks($param, $loginCookie)
    {
    	$data = wscall(self::getwsdldomain(), "myRecentWorks", $param, $loginCookie, false);
    	return $data->RESULT;
    }

    /**
     * 派单
     * @param string WorkUserCode 工人编号
     * @param string Instince 实例编号
     * @param string BeginDate 开始时间
     * @param string EndDate 结束时间
     * @param string Money 金额
     * @author kevin
     * @date 2016-01-22
     */
    public static function planWork($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "planWork", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 材料单-查询
     * @param int PI 页码
     * @param int PS 每页数据量
     * @param string PCS 项目编号集合 eg:[10901,10902,10903]
     * @param string BT 开始时间 eg:2016-01-01 00:00:00
     * @param string ET 结束时间 eg:2016-12-31 00:00:00
     * @param string S 单据状态[0=>申请 , F=通过 , G=配送完成] 需要字符串类型
     * @param string U 供应商编号
     */
    public static function seachResource($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "seachResource", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 材料单-审核
     * @param string RecordCods 单据编号集合,数据来源activeItemIns eg : ['20160120154500621','2016012016300178']
     * @param string ResourceCods 物料编号集合,数据来源recordCode  eg : ['20160120163001151','20160120170500743']
     * @param string S 审核状态 [E=不通过,F=通过]
     * @param string R 描述
     */
    public static function auditResource($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "auditResource", $param, $loginCookie, false);
        return $data->STATUS;
    }

    /**
     * 材料单-资源分配供应商
     * @param string PC 工程编号
     * @param string RECORD 单据编号
     * @param string UC 供应商编号
     * @param string ResourceCode 资源编号
     */
    public static function allotUserToRes($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "allotUserToRes", $param, $loginCookie, false);
        return $data;
    }

    /**
     * 审计-查询  / 结算-查询
     * @param int PI 页码
     * @param int PS 每页数据量
     * @param string PCS 项目编号集合 eg:[10901,10902,10903]
     * @param string BT 开始时间 eg:2016-01-01 00:00:00
     * @param string ET 结束时间 eg:2016-12-31 00:00:00
     * @param string S  数据状态  审计-[1=>运行中 , E=打回 , F=完成] ; 结算-[0=申请中 ,F=审核通过 , E=不通过 , G=打款]
     * @param string T  单数据类型集合
     *    审计['M1'=劳务费,'M4'=罚款单,'M5'=加薪单,'M6'=加任务单,'M7'=主料调整单(主料),'M10'=辅料调整单(辅料单),'R5'=供应商费用];
     *    结算[M2=供应商,M9=工人]
     */
    public static function seachRecords($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "seachRecords", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 审计-查询  / 结算-查询
     * 多两个查询条件
     * @param int PI 页码
     * @param int PS 每页数据量
     * @param string PCS 项目编号集合 eg:[10901,10902,10903]
     * @param string BT 开始时间 eg:2016-01-01 00:00:00
     * @param string ET 结束时间 eg:2016-12-31 00:00:00
     * @param string S  数据状态  审计-[1=>运行中 , E=打回 , F=完成] ; 结算-[0=申请中 ,F=审核通过 , E=不通过 , G=打款]
     * @param string T  单数据类型集合
     * @param int PRINT 是否打印，空条件忽略
     * @param array TAGS 标签集合，Json集合 []条件忽略
     *    审计['M1'=劳务费,'M4'=罚款单,'M5'=加薪单,'M6'=加任务单,'M7'=主料调整单(主料),'M10'=辅料调整单(辅料单),'R5'=供应商费用];
     *    结算[M2=供应商,M9=工人]
     */
    public static function seachRecords2($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "seachRecords2", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 审计-通过或打回
     * @param string RecordCods 单据编号集合,数据来源activeItemIns eg : ['20160120154500621','2016012016300178']
     * @param string S 审核状态 [E=打回,F=通过]
     * @param string R 描述
     */
    public static function auditRec($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "auditRec", $param, $loginCookie, false);
        return $data->STATUS;
    }

    /**提供给ERP 测试
     * @param string RecordCods 单据编号集合,数据来源activeItemIns eg : ['20160120154500621','2016012016300178']
     * @param string S 审核状态 [E=打回,F=通过]
     * @param string R 描述
     */
    public static function moneyExtractFinish($param, $loginCookie)
    {
        $data = wscall('http://' . getDomain() . '/yhr/ws/workerInfoService?wsdl', "moneyExtractFinish", $param, $loginCookie, false);
        return $data->STATUS;
    }

    /**
     * 配送-查询
     * @param int PI 页码
     * @param int PS 每页数据量
     * @param string PCS 工程编号集合 eg:[10901,10902,10903]
     * @param string BT 开始时间 eg:2016-01-01 00:00:00
     * @param string ET 结束时间 eg:2016-12-31 00:00:00
     * @param string S  数据状态[1=>配送中 , F=完成]
     * @param string U  配送员账号
     */
    public static function seachResourceByalloc($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "seachResourceByalloc", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工序-查询基础数据
     * @param string PC 工程编号 eg:10901
     */
    public static function getActiveWF($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getActiveWF", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工序-查询状态
     * @param string PC 工程编号 eg:10901
     */
    public static function getActiveItemInsStatus($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getActiveItemInsStatus", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工序-单据数据
     * @param string PC 工程编号 eg:10901
     */
    public static function getRecordsByProjectCode($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getRecordsByProjectCode", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工序-获取改派图片和改派原因
     * @param string PC 工程编号 eg:10281
     * @param string ACII 工序编号 eg:20160225095503144
     */
    public static function queryKillWorkReason($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "queryKillWorkReason", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工序-派工单补充
     * @param string RC 单据编号
     * @param string PC 工程编号
     */
    public static function getRecordByCode($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getRecordByCode", $param, $loginCookie, false);
        return $data->RESULT;
    }

 
    /**
     * 工序-物料单补充
     * @param string RC 单据编号
     * @param string PC 工程编号
     */
    public static function getRecordResByCode($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getRecordResByCode", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工序-劳务费补充
     * @param string RC 单据编号
     * @param string PC 工程编号
     */
    public static function getRecordMoneyByCode($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getRecordMoneyByCode", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工程统计－查询
     * @param string RC 单据编号
     * @param string PC 工程编号
     */
    public static function getProjects($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getProjects", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /**
     * 工程统计－查询
     * @param string PC 工程编号
     */
    public static function getCurActiveItemInStatus($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getCurActiveItemInStatus", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /*
     *获取工序项实例
    */
    public static function   getActiveItemInStatus($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getActiveItemInStatus", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /*
     *获取评价
    */
    public static function   getActiveItemEvaluate($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getActiveItemEvaluate", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /*
     *获取工序项统计
    */
    public static function   getActiveitemSummaryReport($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "getActiveitemSummaryReport", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /*
     *获取工序项工价
    */
    public static function  calculateWorkFee($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "calculateWorkFee", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /*
     *获取工序项工艺明细
    */
    public static function  queryActiveitemSource($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "queryActiveitemSource", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /*
     *修改单据状态
    */
    public static function  updateRecordHandleTag($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "updateRecordHandleTag", $param, $loginCookie, false);
        return $data->RESULT;
    }

    /*
     *以工序项为单位，判断当前单据数据是否全部审核完成
    */
    public static function  queryActiveitemEnableBilling($param, $loginCookie)
    {
        $data = wscall(self::getwsdldomain(), "queryActiveitemEnableBilling", $param, $loginCookie, false);
        return $data->RESULT;
    }
}