<?php 

namespace Admin\Model;
use Think\Model;

class ProjectModel{
    
    //const WSDL_URL_USER = 'http://124.205.40.87:8080/yhr/ws/projectService?wsdl';


    protected static function  getwsdldomain(){
        $domain = getDomain();
        //var_dump($domain);
        return 'http://'.$domain.'/yhr/ws/projectService?wsdl';
    }
    
    /**
     * 工程搜索---项目编号模糊匹配
     * @param string projectCode eg: 10
     * @author kevin
     * @date 2016-01-27
     */
    public static function searchProjectById($param,$loginCookie){
		$data = wscall(self:: getwsdldomain(),"searchProjectById",$param,$loginCookie,false);
		return $data->RESULT;
    }
    
    /**
     * 工程搜索---业主名称模糊匹配
     * @param string owner eg :王
     * @author kevin
     * @date 2016-01-27
     */
    public static function searchProjectByOwner($param,$loginCookie){
		$data = wscall(self::getwsdldomain(),"searchProjectByOwner",$param,$loginCookie,false);
		return $data->RESULT;
    }
 
     /**
     * 工程搜索---项目名称模糊匹配
     * @param string projectName eg :水
     * @author kevin
     * @date 2016-01-27
     */
    public static function searchProjectByProjectName($param,$loginCookie){
		$data = wscall(self:: getwsdldomain(),"searchProjectByProjectName",$param,$loginCookie,false);
		return $data->RESULT;
    }
       
     /**
     * 工程搜索---装修管家编号或者装修管家名称模糊匹配
     * @param string projectManagerCode eg :王
     * @author kevin
     * @date 2016-01-27
     */
    public static function searchProjectByProjectManagerCode($param,$loginCookie){
		$data = wscall(self:: getwsdldomain(),"searchProjectByProjectManagerCode",$param,$loginCookie,false);
		return $data->RESULT;
    }
    
     /**
     * 材料单-查询
     * @param string beginDate 开始时间 eg:2016-01-01 00:00:00
     * @param string endDate 结束时间 eg:2016-12-31 00:00:00
     * @param string owner 业主名 eg:陈珊
     * @param string projectName 工程名 eg:航定路
     * @param string projectManagerCode 装修管家名 eg:朱思伟
     * @param string S 状态[1=>运行中 , F=完成]
     * @param string projectCodes 工程编号集合 eg:["90"]
     * @param int pageNumber 页码
     * @param int pageSize 每页数据量
     * 
     * 工程统计查询
     * @param string beginDate 开始时间 eg:2016-01-01 00:00:00
     * @param string endDate 结束时间 eg:2016-12-31 00:00:00
     * @param string owner 业主名 空
     * @param string projectName 工程名 空
     * @param string projectManagerCode 装修管家编号
     * @param string status 状态 只能是F
     * @param string projectCodes 工程编号集合 eg:["90"]
     * @param int pageNumber 页码  不分页，使用空
     * @param int pageSize 每页数据量  无意义，空
     * 
     * @author kevin
     * @date 2016-01-29
     */
    public static function getProjects($param,$loginCookie){
		$data = wscall(self:: getwsdldomain(),"getProjects",$param,$loginCookie,false);
		return $data->RESULT;
    }

    /**
 * 获取单个工程
 * @param $param projectCode
 * @param $loginCookie
 * @return mixed
 * @author keivn
 * @date 2016-02-23
 */
    public static function getProject($param , $loginCookie){
        $data = wscall(self:: getwsdldomain(),"getProject",$param,$loginCookie,false);
        return $data->RESULT;
    }
    /**
     * 获取单个工程
     * @param $param pc
     * @param $loginCookie
     */
    public static function statisticsProject($param , $loginCookie){
        $data = wscall(self:: getwsdldomain(),"statisticsProject",$param,$loginCookie,false);
        return $data->RESULT;
    }
        /**
     * @author mit
     * @param projectCode 工程编码
     * @param images 更新的图片
     */
    public static function updateContract($param,$loginCookie){
    	$data = wscall(self:: getwsdldomain(),"updateContract",$param,$loginCookie,false);
    	return $data->RESULT;
    }
}