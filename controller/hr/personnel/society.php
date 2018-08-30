<?php
/**
 * @author Administrator
 * @Date 2012��5��25�� ������ 15:12:03
 * @version 1.0
 * @description:���¹���-������Ϣ-����ϵ���Ʋ�
 */
class controller_hr_personnel_society extends controller_base_action {

	function __construct() {
		$this->objName = "society";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * ��ת�����¹���-������Ϣ-����ϵ�б�
	 */
    function c_page() {
      $this->view('list');
    }


	/**
	 * ��ת�����¹���-������Ϣ-����ϵ-�����б�
	 */
    function c_toPersonnelList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }


	/**
	 * ��ת�����¹���-������Ϣ-����ϵ-�����б�(�������޸�)
	 */
    function c_toEidtList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-editlist');
    }

   /**
	 * ��ת���������¹���-������Ϣ-����ϵҳ��
	 */
	function c_toAdd() {
		$this->assign( 'userName',$_SESSION['USERNAME'] );
		$this->assign( 'userAccount',$_SESSION['USER_ID'] );
		$otherDao=new model_common_otherdatas();     //�½�otherdatas����
		$this->assign('userNo',$otherDao->getUserCard($_SESSION['USER_ID']));
    	$this->view ( 'add' );
   }
   /**
	 * ��ת���������¹���-������Ϣ-����ϵ
	 */
	function c_toMyAdd() {
	  	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
	  	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
	  	$useName=$this->service->get_table_fields('oa_hr_personnel','userNo="'.$userNo.'" or userAccount="'.$userAccount.'"','userName');
	  	$this->assign ( 'userNo', $userNo );
	  	$this->assign ( 'userAccount', $userAccount );
	  	$this->assign ( 'userName', $useName );
	     $this->view ( 'my-add' );
   }

   /**
	 * ��ת���༭���¹���-������Ϣ-����ϵҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���¹���-������Ϣ-����ϵҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
   	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$resultArr = $this->service->addExecelData_d ();

		$title = '����ϵ��Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/*
	 * ����excel
	 */
	 function c_toExcelOut(){
		$this->view('excelout');
	}

	/*
	 * ����excel
	 */
	 function c_excelOut(){
	 	$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userName']))
	 		$this->service->searchArr['userName'] = $object['userName'];
		if(!empty($object['relationName']))
	 		$this->service->searchArr['relationName'] = $object['relationName'];
		if(!empty($object['age']))
			$this->service->searchArr['age'] = $object['age'];
		if(!empty($object['isRelation']))
	 		$this->service->searchArr['isRelation'] = $object['isRelation'];
		if(!empty($object['information']))
			$this->service->searchArr['information'] = $object['information'];
		if(!empty($object['workUnit']))
			$this->service->searchArr['workUnit'] = $object['workUnit'];
		if(!empty($object['job']))
			$this->service->searchArr['job'] = $object['job'];
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['userName']=$planEquRows[$key]['userName'];
			$exportData[$key]['relationName']=$planEquRows[$key]['relationName'];
			$exportData[$key]['age']=$planEquRows[$key]['age'];
			$exportData[$key]['isRelation']=$planEquRows[$key]['isRelation'];
			$exportData[$key]['information']=$planEquRows[$key]['information'];
			$exportData[$key]['workUnit']=$planEquRows[$key]['workUnit'];
			$exportData[$key]['job']=$planEquRows[$key]['job'];
			$exportData[$key]['remark']=$planEquRows[$key]['remark'];
		}
		return $this->service->excelOut ( $exportData );
	}

		  	/**
	 * ��������
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['society']['listSql']))));
		$this->view('excelout-select');

	}

	/**
	 * ��������
	 */
	function c_selectExcelOut(){
//			set_time_limit(600);
		$rows=array();//���ݼ�
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}
//		echo "<pre>";
//		print_r($rows);
		$colNameArr=array();//��������
		include_once ("model/hr/personnel/societyFieldArr.php");
		if(is_array($_POST['society'])){
			foreach($_POST['society'] as $key=>$val){
					foreach($societyFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
//		print_r($_POST['contract']);
//		print_r($societyFieldArr);
		$newColArr=array_combine($_POST['society'],$colNameArr);//�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['society']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutSociety($newColArr,$dataArr);
	}

	/******************* E ���뵼��ϵ�� ************************/
 }
?>