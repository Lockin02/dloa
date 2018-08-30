<?php
/**
 * @author Administrator
 * @Date 2012��6��22�� ������ 11:09:55
 * @version 1.0
 * @description:���¹���-������Ϣ-������Ϣ���Ʋ�
 */
class controller_hr_personnel_health extends controller_base_action {

	function __construct() {
		$this->objName = "health";
		$this->objPath = "hr_personnel";
		parent::__construct ();
	 }

	/**
	 * ��ת�����¹���-������Ϣ-������Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת���������¹���-������Ϣ-������Ϣҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭���¹���-������Ϣ-������Ϣҳ��
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
	 * ��ת���鿴���¹���-������Ϣ-������Ϣҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }

    function c_toPersonnelList(){
    	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo);
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }
    /******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

	/*
	 * ����excel
	 */
	function c_toExcelOut(){
		$this->view('excelout');
	}

	/**
	 * ����excel
	 */
	function c_excelIn(){
		$resultArr = $this->service->addExecelData_d ();

		$title = '������Ϣ�������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/*
	 * ����excel
	 */
	  function c_excelOut(){
		$object = $_POST[$this->objName];
		//print_r($object);
		if(!empty($object['userName']))
	 		$this->service->searchArr['userNameSearch'] = $object['userName'];
		if(!empty($object['userNo']))
	 		$this->service->searchArr['userNo'] = $object['userNo'];
		if(!empty($object['hospital']))
	 		$this->service->searchArr['hospital'] = $object['hospital'];
		if(!empty($object['beginDate']))
			$this->service->searchArr['beginDate'] = $object['beginDate'];
		if(!empty($object['endDate']))
	 		$this->service->searchArr['endDate'] = $object['endDate'];
		if(!empty($object['checkResult']))
			$this->service->searchArr['checkResult'] = $object['checkResult'];
		if(!empty($object['remark']))
			$this->service->searchArr['remark'] = $object['remark'];
		set_time_limit(600);
		$planEquRows = $this->service->list_d();
		$exportData = array();
		foreach ( $planEquRows as $key => $val ){
			$exportData[$key]['userNo']=$planEquRows[$key]['userNo'];
			$exportData[$key]['userName']=$planEquRows[$key]['userName'];
			$exportData[$key]['hospital']=$planEquRows[$key]['hospital'];
			$exportData[$key]['checkDate']=$planEquRows[$key]['checkDate'];
			$exportData[$key]['checkResult']=$planEquRows[$key]['checkResult'];
			$exportData[$key]['remark']=$planEquRows[$key]['remark'];
			$exportData[$key]['hospitalOpinion']=$planEquRows[$key]['hospitalOpinion'];
		}
		return $this->service->excelOut ( $exportData );
	 }
 }
?>