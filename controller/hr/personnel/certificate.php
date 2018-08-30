<?php
/**
 * @author Administrator
 * @Date 2012��5��25�� ������ 15:12:08
 * @version 1.0
 * @description:���¹���-������Ϣ-�ʸ�֤����Ʋ�
 */
class controller_hr_personnel_certificate extends controller_base_action {

	function __construct() {
		$this->objName = "certificate";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * ��ת�����¹���-������Ϣ-�ʸ�֤���б�
	 */
    function c_page() {
      $this->view('list');
    }


	/**
	 * ��ת�����¹���-������Ϣ-�ʸ�֤��-�����б�
	 */
    function c_toPersonnelList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }


	/**
	 * ��ת�����¹���-������Ϣ-�ʸ�֤��-�����б�(�������޸�)
	 */
    function c_toEidtList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-editlist');
    }

   /**
	 * ��ת���������¹���-������Ϣ-�ʸ�֤��ҳ��
	 */
	function c_toAdd() {
		$this->assign( 'userName',$_SESSION['USERNAME'] );
		$this->assign( 'userAccount',$_SESSION['USER_ID'] );
		$otherDao=new model_common_otherdatas();     //�½�otherdatas����
		$this->assign('userNo',$otherDao->getUserCard($_SESSION['USER_ID']));
     	$this->view ( 'add' );
   }
   /**
	 * ��ת���������¹���-������Ϣ-�ʸ�֤��ҳ��
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
	 * ��ת���༭���¹���-������Ϣ-�ʸ�֤��ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//��ʾ������Ϣ
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���¹���-������Ϣ-�ʸ�֤��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
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

		$title = '�ʸ�֤����Ϣ�������б�';
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
		if(!empty($object['DateBegin']))
	 		$this->service->searchArr['DateBegin'] = $object['DateBegin'];
		if(!empty($object['DateEnd']))
	 		$this->service->searchArr['DateEnd'] = $object['DateEnd'];
		if(!empty($object['userNo']))
			$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['certifying']))
	 		$this->service->searchArr['certifyingSearch'] = $object['certifying'];
		if(!empty($object['certificates']))
			$this->service->searchArr['certificatesSearch'] = $object['certificates'];
		if(!empty($object['level']))
			$this->service->searchArr['level'] = $object['level'];
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['userName']=$planEquRows[$key]['userName'];
			$exportData[$key]['certificates']=$planEquRows[$key]['certificates'];
			$exportData[$key]['level']=$planEquRows[$key]['level'];
			$exportData[$key]['certifying']=$planEquRows[$key]['certifying'];
			$exportData[$key]['certifyingDate']=$planEquRows[$key]['certifyingDate'];
			$exportData[$key]['fujian']=$planEquRows[$key]['fujian'];
			$exportData[$key]['remark']=$planEquRows[$key]['remark'];
		}
		return $this->service->excelOut ( $exportData );
	}

		  	/**
	 * ��������
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['certificate']['listSql']))));
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
		include_once ("model/hr/personnel/certificateFieldArr.php");
		if(is_array($_POST['certificate'])){
			foreach($_POST['certificate'] as $key=>$val){
					foreach($certificateFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
//		print_r($_POST['contract']);
//		print_r($societyFieldArr);
		$newColArr=array_combine($_POST['certificate'],$colNameArr);//�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['certificate']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutCertificate($newColArr,$dataArr);
	}

	/******************* E ���뵼��ϵ�� ************************/
 }
?>