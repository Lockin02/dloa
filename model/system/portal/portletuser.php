<?php

/**
 *portlet�û����� model��
 */
class model_system_portal_portletuser extends model_base {

	function __construct() {
		$this->tbl_name = "oa_portal_portlet_user";
		$this->sql_map = "system/portal/portletuserSql.php";
		parent :: __construct();
	}

	/**
	 * ������������
	 */
	function addBatch_d($portletIdArr, $portletNameArr){
		try {
			$this->start_d ();
			$idArr=array();
			//��ȡ���û�����protletOrder
			$sql="select max(portletOrder) as num from oa_portal_portlet_user where userId='".$_SESSION['USER_ID']."'";
			$maxOrder=$this->queryCount($sql);;
			if(empty($maxOrder)){
				if($maxOrder==='0'){
					$maxOrder=1;
				}else{
					$maxOrder=0;
				}
			}else{
				$maxOrder++;
			}
			foreach($portletIdArr as $key=>$val){
				$obj=array(
					"portletId"=>$portletIdArr[$key],
					"portletName"=>$portletNameArr[$key],
					"portletOrder"=>$maxOrder++,
					"userId"=>$_SESSION['USER_ID'],
					"userName"=>$_SESSION['USERNAME']
				);
				$id=$this->add_d($obj,true);
				$idArr[$key]=$id;
			}
			$this->commit_d ();
			return $idArr;
		} catch ( Exception $e ) {
			$this->rollBack ();
		}
	}

	/**
	 * ��ȡ��ǰ��¼�û�portlets
	 */
	function getCurUserPortlets(){
		//$this->searchArr['userId']=$_SESSION['USER_ID'];
		//$this->searchArr['muserId']=$_SESSION['USER_ID'];
		//��ȡȨ��
		$permDao=new model_system_portal_portletperm();

		$permDao->searchArr['userId']=$_SESSION['USER_ID'];
		$userPerms=$permDao->list_d();
		$portletIdArr=array();
		foreach($userPerms as $key=>$val){
			$portletIdArr[]=$val['portletId'];
		}

		unset($permDao->searchArr['userId']);
		$permDao->searchArr['roleId']=$_SESSION['USER_JOBSID'];;
		$rolePerms=$permDao->list_d();
		foreach($rolePerms as $key=>$val){
			$portletIdArr[]=$val['portletId'];
		}

		//��ȡû��Ȩ�޿��Ƶ�
		$pertletDao=new model_system_portal_portlet();
		$pertletDao->searchArr['isPerm']=0;
		$pertlets=$pertletDao->list_d();
		foreach($pertlets as $key=>$val){
			$portletIdArr[]=$val['id'];
		}

		$ids=join(",",$portletIdArr);
		$this->searchArr['ids']=$ids;
		return $this->list_d();
	}

	/**
	 * portlet����˳��
	 */
	function saveOrder($savePanel){
		try {
			$this->start_d ();
			//print_r($savePanel);
			foreach($savePanel as $key=>$val){
				$id=substr($key,8);
				$arr=array(
					"id"=>$id,
					"portletOrder"=>$val
				);
				$this->edit_d($arr);
			}
			$this->commit_d ();
		}catch ( Exception $e ) {
			$this->rollBack ();
		}
	}


}
?>