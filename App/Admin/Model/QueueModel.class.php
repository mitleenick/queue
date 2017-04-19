<?php 
/**
 *@author mit 来活
 *@date 2017.03 
 */
namespace Admin\Model;
use Think\Model;

class QueueModel extends CoreModel{
    
	public function init(){
		$this->model =new Model();
	}
	
	public function requestNum($param){
		$model=new Model();
        $model->startTrans();
        $flag=false;
        $maxNum = M('queue')->where(array('auth'=>$param['auth']))->max('number');     
		if(!$maxNum){
			$maxNum='0001';
		}else{
			$maxNum=substr(strval($maxNum+10001),1,4);
		}
		$data['number']=$maxNum;
		$data['sort']=1;
		$data['addTime']=date('Y-m-d H:i:s');
		$data['company']=$param['company'];
		$data['auth']=$param['auth'];
		$data['bussiness']=$param['bussiness'];
		$data['uid']=$param['uid'];
		$id =M('queue')->add($data);

		if($id){
			$flag=true;
		}		
        if(!$flag){ 
               $model->rollback();
        }else{
            $model->commit();
       }
        return $maxNum;
	}
	
	public function callNum($bussiness){
		
		$M('queue')->where(array('bussiness'=>array('in'=>$bussiness)))->select();
	}
	
	public function popNum(){
		
		
	}
	
	
	

}