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
	 * Ĭ�Ϸ���
	 */
	function c_index()
	{
		$this->show->display ( 'statIndex' );
	}
	function c_indexData()
	{
		
		echo $this->model_administration_statitics_indexData();
	}
	 /* ����Ա������
	 * Enter description here ...
	 */
	function c_exportExcel ( )
	{
	   $this->model_administration_statistics_exExcel();
    }
/* ����Ա������
	 * Enter description here ...
	 */
	function c_deptData ( )
	{
	   echo $this->model_administration_statistics_deptData();
    }
	
    function c_perTabData(){
    	  	$tabData=un_iconv(array(array(
					'title'=>'��������',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=perList'
					),array(
					'title'=>'���۹���',
					'url'=>'?model=administration_appraisal_evaluate_index&action=evalList'
					),array(
					'title'=>'���˹���',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=asseList'
					),array(
					'title'=>'��˹���',
					'url'=>'index1.php?model=administration_appraisal_performance_list&action=auditList'
					)));
		echo  json_encode ( $tabData );
    	
    }
  
	  
	
	function c_importExcel(){ 
		if ($_POST) {
			if($this->model_administration_appraisal_performance_list_importExcel()==2){
	       	  showmsg ( '����ɹ���', 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '����ʧ�ܣ�', 'parent.CloseOpen();', 'button' );
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
	
	 /* ����Ա������
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
						$str.=$val.'����ʧ�ܣ�<br/>';
					}
				}else{
					$str='����ɹ���';
				}
	       	  showmsg ( $str, 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '����ʧ�ܣ�', 'parent.CloseOpen();', 'button' );
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
						$str.=$val.'����ʧ�ܣ�<br/>';
					}
				}else{
					$str='����ɹ���';
				}
	       	  showmsg ( $str, 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '����ʧ�ܣ�', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			showmsg ( '����ʧ�ܣ�', 'parent.CloseOpen();', 'button' );
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