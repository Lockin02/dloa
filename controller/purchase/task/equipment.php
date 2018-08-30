<?php
/**
 * �ɹ��ƻ��豸�������
 */
class controller_purchase_task_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_task";
		parent::__construct ();
	}

/*****************************************��ʾ�ָ���********************************************/


	/**
	 * �ɹ�����ִ��������ͳ���б�
	 */
	function c_toExeEquList(){
		$equNumb = isset( $_GET['equNumb'] )?$_GET['equNumb']:"";
		$equName = isset( $_GET['equName'] )?$_GET['equName']:"";
		$idsArry = isset( $_GET['idsArry'] )?$_GET['idsArry']:"";
		$purchType=isset($_GET['purchType'])?$_GET['purchType']:"";
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$this->assign('purchType',$purchType);
		$searchArr = array (
			"sendUserId" => $_SESSION['USER_ID']
		);

		if(is_array($object)){
			$productNumbArr=array();
			foreach($object as $key=>$val){
				if($val['productNumb']!=""){
					$productNumbArr[$key]=$val['productNumb'];
				}
			}
			if(!empty($productNumbArr)){
				$productNumbStr=implode(',',$productNumbArr);
				$searchArr['productNumbArr'] = $productNumbStr;
			}
		}
		if($equNumb!=""){
			$searchArr['productNumbSear'] = $equNumb;
		}
		if($equName!=""){
			$searchArr['productNameSear'] = $equName;
		}
		if($purchType=="contract_sales"){
			$searchArr['purchTypeArr'] = "oa_sale_order,oa_sale_service,oa_sale_lease,oa_sale_rdproject" ;
		}else if($purchType=="borrow_present"){//�����òɹ�
			$searchArr['purchTypeArr'] = "oa_borrow_borrow,oa_present_present" ;
		}else if($purchType==""){//��ʾȫ���ɹ����͵����ϻ��ܱ�
			//$searchArr['purchTypeArr'] = "" ;
		}else{
			$searchArr['purchTypeArr']=$purchType;
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.productNumb");

		$rows = $service->pageEqu_d();
//		echo "<pre>";
//		print_r($rows);
		$this->pageShowAssign();

		$this->assign('equNumb', $equNumb);
		$this->assign('equName', $equName);
		$this->assign('idsArry', $idsArry);
		$this->assign ( 'purchType', $purchType );
		$this->assign('list', $this->service->showEqulist_s($rows));
		$this->display('list-equ');
		unset($this->show);
		unset($service);
	}

	/**
	 *�ɹ��������ϻ��ܲ�ѯҳ��
	 *
	 */
	 function c_toEquListSearch(){
	 	$this->display('list-equ-search');
	 }
		/**
	 * �ɹ���������ִ������б�
	 */
	function c_toProgressList(){
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//�����ֶ�
		$searchvalue=isset($_GET['searchvalue'])?$_GET['searchvalue']:"";
		$searchArr = array ();
		if($searchvalue!=""){
			$searchArr[$searchCol] = $searchvalue;
		}
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['productNumbSear']!=""){
				$searchArr['productNumbSear']=$object['productNumbSear'];
			}
			if($object['productNameSear']!=""){
				$searchArr['productNameSear']=$object['productNameSear'];;
			}
			if($object['sendNameSear']!=""){
				$searchArr['sendNameSear']=$object['sendNameSear'];
			}
		}
		$service = $this->service;

		/*s:-----------------�̳вɹ�����Ĺ�˾Ȩ�޿��� PMS 2562-------------------*/
		$businessBelong_limit = array();
		$func_sql = "
			select 
				a.content
			from 
				purview_info as a
				left join purview_type as b on b.id=a.typeid
			where 
				(
					a.userid='".$_SESSION['USER_ID']."' 
					or a.jobsid ='".$_SESSION['USER_JOBSID']."'
					or a.deptid='".$_SESSION['DEPT_ID']."'
					or (
              ( a.userid = '' or a.userid is null )
              and  a.jobsid='0'
              and  a.deptid='0'
              )
				)
				and b.name = '��˾Ȩ��' ";
		$purview_rs = $service->get_one("select id,control from purview where models='purchase_plan_basic' and type=1 and control=1");
		if ($purview_rs)
		{
			$sql =$func_sql." and a.tid='".$purview_rs['id']."' ";
			$purview_row = $service->_db->getArray($sql);
			$limitStr = ($purview_row)? $purview_row[0]['content'] : '';
			if($limitStr != '' && strpos($limitStr, ';;') === false){
				$businessBelong_limit = explode(',',$limitStr);
			}else if($limitStr == ''){
				$businessBelong_limit = array($_SESSION['USER_COM']);
			}
		}else{
			$businessBelong_limit = array($_SESSION['USER_COM']);
		}
		if(count($businessBelong_limit) > 0){
			$searchArr['businessBelongPc'] = $businessBelong_limit;
		}
		/*s:-----------------�̳вɹ�����Ĺ�˾Ȩ�޿��� PMS 2562-------------------*/

		/*s:-----------------�������-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------�������-------------------*/
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.id");

		$rows = $service->getAllEqus_d();
		$this->pageShowAssign();


		$this->show->assign('searchvalue', $searchvalue);
		$this->assign ( 'searchCol', $searchCol );
		$this->assign('list', $this->service->showEquProgressList($rows));
		$this->display('list-progress');
		unset($this->show);
		unset($service);
	}

	/**
	 *�ɹ�����ִ�л��ܲ�ѯҳ��
	 *
	 */
	 function c_toProgressSearch(){
	 	$this->display('list-progress-search');
	 }

	/**
	 *�ɹ�����ִ�л��ܵ���ҳ��
	 *
	 */
	 function c_toExprotExcel(){
	 	$this->display('list-progress-export');
	 }

	/**
	 *�ѹرղɹ��������ϵ���ҳ��
	 *
	 */
	 function c_toExprotClose(){
	 	$this->display('list-close-export');
	 }

	 /**
	 * �����ɹ��������
	 *
	 */
	 function c_exportExcel(){
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$searchArr = array ();
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['productId']!=""){
				$searchArr['productId']=$object['productId'];;
			}
			if($object['sendUserId']!=""){
				$searchArr['sendUserId']=$object['sendUserId'];
			}
		}
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.id");

		$rows = $service->getAllEqusByList_d();
		return model_purchase_plan_purchaseExportUtil::exportTaskEqu_e ( $rows);
	 }


	 /**
	 * �����ɹ��������
	 *
	 */
	 function c_exportClose(){
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$searchArr = array ();
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['productId']!=""){
				$searchArr['productId']=$object['productId'];;
			}
			if($object['sendUserId']!=""){
				$searchArr['sendUserId']=$object['sendUserId'];
			}
		}
		$searchArr['state']=4;//�ѹر�����
		$service = $this->service;
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.id");

		$rows = $service->getAllEqusByList_d();
//		print_r($rows);
		return model_purchase_plan_purchaseExportUtil::exportTaskCloseEqu_e ( $rows);
	 }

}
?>
