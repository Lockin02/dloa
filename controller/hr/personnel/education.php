<?php
/**
 * @author Administrator
 * @Date 2012��5��25�� ������ 15:11:48
 * @version 1.0
 * @description:���¹���-������Ϣ-�����������Ʋ�
 */
class controller_hr_personnel_education extends controller_base_action {

	function __construct() {
		$this->objName = "education";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * ��ת�����¹���-������Ϣ-���������б�
	 */
    function c_page() {
      $this->view('list');
    }


	/**
	 * ��ת�����¹���-������Ϣ-��������-�����б�
	 */
    function c_toPersonnelList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }


	/**
	 * ��ת�����¹���-������Ϣ-��������-�����б�(�������޸�)
	 */
    function c_toEidtList() {
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-editlist');
    }

	/**
	 * ��������-�����б�Json
	 */
	function c_personnelPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr['userNo']=$_SESSION['USER_ID'];


		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
   /**
	 * ��ת���������¹���-������Ϣ-��������ҳ��
	 */
	function c_toAdd() {
		$this->assign( 'userName',$_SESSION['USERNAME'] );
		$this->assign( 'userAccount',$_SESSION['USER_ID'] );
		$otherDao=new model_common_otherdatas();     //�½�otherdatas����
		$this->assign('userNo',$otherDao->getUserCard($_SESSION['USER_ID']));
		$this->showDatadicts ( array ('education' => 'HRJYXL' ));
    	$this->view ( 'add' );
   }
   /**
	 * ��ת���������¹���-������Ϣ-��������ҳ��
	 */
	function c_toMyAdd() {
	  	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
	  	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
	  	$useName=$this->service->get_table_fields('oa_hr_personnel','userNo="'.$userNo.'" or userAccount="'.$userAccount.'"','userName');
	  	$this->assign ( 'userNo', $userNo );
	  	$this->assign ( 'userAccount', $userAccount );
	  	$this->assign ( 'userName', $useName );
		$this->showDatadicts ( array ('education' => 'HRJYXL' ));
	     $this->view ( 'my-add' );
   }

   /**
	 * ��ת���༭���¹���-������Ϣ-��������ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('education' => 'HRJYXL' ), $obj ['education'] );
		//��ʾ������Ϣ
        $this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
      	$this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���¹���-������Ϣ-��������ҳ��
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

		$title = '����������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/*
	 * ����excel
	 */
	 function c_toExcelOut(){
	 	$this->showDatadicts(array('education' => 'HRJYXL'),null,true);
		$this->view('excelout');
	}

	/*
	 * ����excel
	 */
	 function c_excelOut(){
		$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['organization']))
	 		$this->service->searchArr['organizationSearch'] = $object['organization'];
		if(!empty($object['content']))
			$this->service->searchArr['contentSearch'] = $object['content'];
		if(!empty($object['education']))
	 		$this->service->searchArr['education'] = $object['education'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDateSearch'] = $object['beginDate'];
		if(!empty($object['closeDate']))
			$this->service->searchArr['closeDateSearch'] = $object['closeDate'];
		//	print_R($this->service->searchArr);
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		if(is_array($planEquRows)){
			foreach ( $planEquRows as $key => $val ){
				$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
				$exportData[$key]['userName']=$planEquRows[$key]['userName'];
				$exportData[$key]['organization']=$planEquRows[$key]['organization'];
				$exportData[$key]['content']=$planEquRows[$key]['content'];
				$exportData[$key]['educationName']=$planEquRows[$key]['educationName'];
				$exportData[$key]['certificate']=$planEquRows[$key]['certificate'];
				$exportData[$key]['beginDate']=$planEquRows[$key]['beginDate'];
				$exportData[$key]['closeDate']=$planEquRows[$key]['closeDate'];
				$exportData[$key]['fujian']=$planEquRows[$key]['fujian'];
				$exportData[$key]['remark']=$planEquRows[$key]['remark'];
			}
		}
		return $this->service->excelOut ( $exportData );
	}

		  	/**
	 * ��������
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['education']['listSql']))));
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
		include_once ("model/hr/personnel/educationFieldArr.php");
		if(is_array($_POST['education'])){
			foreach($_POST['education'] as $key=>$val){
					foreach($educationFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}
//		print_r($_POST['contract']);
//		print_r($contractFieldArr);
		$newColArr=array_combine($_POST['education'],$colNameArr);//�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['education']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutEducation($newColArr,$dataArr);
	}
	/******************* E ���뵼��ϵ�� ************************/
 }
?>