<?php
class controller_administration_appraisal_performance_statistics extends model_administration_appraisal_performance_statistics
{
	public $show;
	function __construct()
	{
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'administration/appraisal/performance/';
	}
	/**
	 * 默认访问
	 */
	function c_index()
	{
		$this->show->display ( 'statIndex' );
	}
	function c_indexData()
	{
		
		echo $this->model_administration_statitics_indexData();
	}
	 /* 导出员工数据
	 * Enter description here ...
	 */
	function c_exportExcel ( )
	{
	   $this->model_administration_statistics_exExcel();
    }
/* 导出员工数据
	 * Enter description here ...
	 */
	function c_deptData ( )
	{
	   echo $this->model_administration_statistics_deptData();
    }
	
    function c_perTabData(){
    	  	$tabData=un_iconv(array(array(
					'title'=>'自评管理',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=perList'
					),array(
					'title'=>'评价管理',
					'url'=>'?model=administration_appraisal_evaluate_index&action=evalList'
					),array(
					'title'=>'考核管理',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=asseList'
					),array(
					'title'=>'审核管理',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=auditList'
					)));
		echo  json_encode ( $tabData );
    	
    }
  
	  
	
	function c_importExcel(){ 
		if ($_POST) {
			if($this->model_administration_appraisal_performance_list_importExcel()==2){
	       	  showmsg ( '导入成功！', 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			$this->show->display ( 'importExcel');
		}
	} 
	function c_optionStatus(){
		echo $this->model_administration_appraisal_performance_list_optionStatus();
	}
	
	  	
	function c_init(){ 
		echo $this->model_administration_appraisal_performance_list_init();
	}
	
	 /* 导出员工数据
	 * Enter description here ...
	 */
	function c_exportExaExcel ( )
	{
	   $this->model_administration_appraisal_performance_list_exportExaExcel();
    }
    
    function c_importExExcel(){ 
		if ($_POST) {
			$msgI=$this->model_administration_appraisal_performance_list_importExExcel();
			if($msgI['msg']==2){
				if($msgI['err']&&is_array($msgI['err'])){
					foreach($msgI['err'] as $key =>$val){
						$str.=$val.'导入失败！<br/>';
					}
				}else{
					$str='导入成功！';
				}
	       	  showmsg ( $str, 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			$this->show->display ( 'importExExcel');
		}
	}
	
	function c_importUpExcel(){ 
		if ($_POST) {
			$msgI=$this->model_administration_appraisal_performance_list_importUpExcel();
			if($msgI['msg']==2){
				if($msgI['err']&&is_array($msgI['err'])){
					foreach($msgI['err'] as $key =>$val){
						$str.=$val.'导入失败！<br/>';
					}
				}else{
					$str='导入成功！';
				}
	       	  showmsg ( $str, 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
		}
	}
	function c_updateInFlag(){
		
		$inFlag=$_POST['inFlag'];
		$kIdI=explode(',',$_POST['kId']);
		if($kIdI&&is_array($kIdI)&&$inFlag){
			$upDate=array('inFlag'=>$inFlag);
			foreach($kIdI as $key =>$val){
				if($val){
					$flag=$this->update(array('id'=>$val),$upDate);
				}
			}
			echo  $flag; 
		}		
	}        
}
?>