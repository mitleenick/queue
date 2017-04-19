<?php 
/*
 * 用户管理模型
 * wsdl   : http://124.205.40.87:8080/yhr/ws/userService?wsdl
 */
 
namespace Admin\Model;
use Think\Model;

class ExamModel{
    
    //const WSDL_URL_USER = 'http://124.205.40.87:8080/yhr/ws/examService?wsdl';

    protected static function  getwsdldomain(){
        $domain = getDomain();
        return 'http://'.$domain.'/yhr/ws/examService?wsdl';
    }
    
    /**
     * 培训－查询
     * @param int isExam 是否考题 0=培训 1＝考题，后续会变为集合
     * @param string workGroupCode 工种编号
     * @author kevin
     * @date 2016-01-29
     */
    public static function getExams($param,$loginCookie){
		$data = wscall(self::getwsdldomain(),"getExams",$param,$loginCookie,false);
		return $data->RESULT;
    }

    /**
     * 培训－启用禁用
     * @param string examCode  题库编号
     * @param int isUse 1启用，0禁用
     * @author kevin
     * @date 2016-01-29
     */
    public static function examStatus($param,$loginCookie){
        $data = wscall(self::getwsdldomain(),"examStatus",$param,$loginCookie,false);
        return $data->STATUS;
    }
    /**
     * 培训－启用禁用
     * @param string examCode  题库编号
     * @param int isUse 1启用，0禁用
     * @author kevin
     * @date 2016-01-29
     */
    public static function removeExam($param,$loginCookie){
        $data = wscall(self::getwsdldomain(),"removeExam",$param,$loginCookie,false);
        return $data->STATUS;
    }
}