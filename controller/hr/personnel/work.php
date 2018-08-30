<?php
/**
 * @author Administrator
 * @Date 2012��5��25�� ������ 15:11:59
 * @version 1.0
 * @description:���¹���-������Ϣ-�����������Ʋ�
 */
class controller_hr_personnel_work extends controller_base_action {

	function __construct() {
		$this->objName = "work";
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
		$userNo = isset($_GET ['userNo']) ? $_GET['userNo'] : ''; //Ա�����
		$userAccount = isset($_GET ['userAccount']) ? $_GET['userAccount'] : ''; //Ա���˺�
		$this->assign ( 'userNo', $userNo );
		$this->assign ( 'userAccount', $userAccount );
		$this->view('personnel-list');
	}


	/**
	 * ��ת�����¹���-������Ϣ-��������-�����б�(�������޸�)
	 */
	function c_toEidtList() {
		$userNo = isset($_GET ['userNo']) ? $_GET['userNo'] : ''; //Ա�����
		$userAccount = isset($_GET ['userAccount']) ? $_GET['userAccount'] : ''; //Ա���˺�
		$this->assign ( 'userNo', $userNo );
		$this->assign ( 'userAccount', $userAccount );
		$this->view('personnel-editlist');
	}

	/**
	 * ��ת���������¹���-������Ϣ-��������ҳ��
	 */
	function c_toAdd() {
		$this->assign( 'userName',$_SESSION['USERNAME'] );
		$this->assign( 'userAccount',$_SESSION['USER_ID'] );
		$otherDao = new model_common_otherdatas(); //�½�otherdatas����
		$this->assign('userNo',$otherDao->getUserCard($_SESSION['USER_ID']));
		$this->view ( 'add' );
	}

	/**
	 * ��ת���������¹���-������Ϣ-��������ҳ��
	 */
	function c_toMyAdd() {
		$userNo = isset($_GET ['userNo']) ? $_GET['userNo'] : ''; //Ա�����
		$userAccount = isset($_GET ['userAccount']) ? $_GET['userAccount'] : ''; //Ա���˺�
		$useName=$this->service->get_table_fields('oa_hr_personnel','userNo="'.$userNo.'" or userAccount="'.$userAccount.'"','userName');
		$this->assign ( 'userNo', $userNo );
		$this->assign ( 'userAccount', $userAccount );
		$this->assign ( 'userName', $useName );
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
		//��ʾ������Ϣ
		$this->show->assign("file",$this->service->getFilesByObjId($_GET ['id'],true));
		$this->view ( 'edit' );
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

	/**
	 * ����excel
	 */
	function c_toExcelOut(){
		$this->view('excelout');
	}

	/**
	 * ����excel
	 */
	function c_excelOut(){
		$object = $_POST[$this->objName];
		if(!empty($object['userId']))
			$this->service->searchArr['userNo'] = $object['userId'];
		if(!empty($object['company']))
			$this->service->searchArr['companySearch'] = $object['company'];
		if(!empty($object['dept']))
			$this->service->searchArr['deptSearch'] = $object['dept'];
		if(!empty($object['position']))
			$this->service->searchArr['positionSearch'] = $object['position'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDateSearch'] = $object['beginDate'];
		if(!empty($object['closeDate']))
			$this->service->searchArr['closeDateSearch'] = $object['closeDate'];
		if(!empty($object['isSeniority']))
			$this->service->searchArr['isSeniority'] = $object['isSeniority'];
		if(!empty($object['seniority']))
			$this->service->searchArr['seniority'] = $object['seniority'];

		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo'] = $planEquRows[$key]['userNo'];
			$exportData[$key]['userName'] = $planEquRows[$key]['userName'];
			$exportData[$key]['company'] = $planEquRows[$key]['company'];
			$exportData[$key]['dept'] = $planEquRows[$key]['dept'];
			$exportData[$key]['position'] = $planEquRows[$key]['position'];
			$exportData[$key]['treatment'] = $planEquRows[$key]['treatment'];
			$exportData[$key]['beginDate'] = $planEquRows[$key]['beginDate'];
			$exportData[$key]['closeDate'] = $planEquRows[$key]['closeDate'];
			$exportData[$key]['isSeniority'] = $planEquRows[$key]['isSeniority'];
			$exportData[$key]['seniority'] = $planEquRows[$key]['seniority'];
			$exportData[$key]['prove'] = $planEquRows[$key]['prove'];
			$exportData[$key]['leaveReason'] = $planEquRows[$key]['leaveReason'];
			$exportData[$key]['remark'] = $planEquRows[$key]['remark'];
			$exportData[$key]['fujian'] = $planEquRows[$key]['fujian'];
		}
		return $this->service->excelOut ( $exportData );
	 }

	/**
	 * ��������
	 */
	function c_excelOutSelect(){
		$this->assign('listSql',str_replace("&nbsp;"," ",stripslashes(stripslashes($_POST['work']['listSql']))));
		$this->view('excelout-select');
	}

	/**
	 * ��������
	 */
	function c_selectExcelOut(){
		$rows=array();//���ݼ�
		$listSql=str_replace("&nbsp;"," ",stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if(!empty($listSql)){
			$rows = $this->service->_db->getArray($listSql);
		}

		$colNameArr = array();//��������
		include_once ("model/hr/personnel/workFieldArr.php");
		if(is_array($_POST['work'])){
			foreach($_POST['work'] as $key=>$val){
					foreach($workFieldArr as $fKey=>$fVal){
						if($val==$fKey){
							$colNameArr[$key]=$fVal;
						}
					}
			}
		}

		$newColArr=array_combine($_POST['work'],$colNameArr);//�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['work']);
		if(is_array($rows)){
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
		return model_hr_personnel_personnelExcelUtil::excelOutWork($newColArr,$dataArr);
	}

	/******************* E ���뵼��ϵ�� ************************/
 }
?>