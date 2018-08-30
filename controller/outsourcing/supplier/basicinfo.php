<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 16:32:24
 * @version 1.0
 * @description:�����Ӧ�̿���Ʋ�
 */
class controller_outsourcing_supplier_basicinfo extends controller_base_action {

	function __construct() {
		$this->objName = "basicinfo";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * ��ת�������Ӧ�̿��б�
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * ��ת�������Ӧ�̿��б�(������)
	 */
    function c_toBlackList() {
      $this->view('black-list');
    }

   /**
	 * ��ת�����������Ӧ�̿�ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('suppTypeCode' => 'WBGYSLX' ));//��Ӧ������
		$this->showDatadicts ( array ('taxPoint' => 'WBZZSD' ) ); //˰��
     	$this->view ( 'add' );
   }

   /**
	 * ��ת���༭�����Ӧ�̿�ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('suppTypeCode' => 'WBGYSLX' ),$obj['suppTypeCode']);//��Ӧ������
		$this->showDatadicts ( array ('taxPoint' => 'WBZZSD' ),$obj['taxPoint'] ); //˰��
      $this->view ( 'edit');
   }

     /**
	 * ��ת�������Ӧ�̵ȼ����ҳ��
	 */
	function c_toChangeSuppGrad() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch($obj['suppGrade']){
			case '1':$suppGrade='��';break;
			case '2':$suppGrade='��';break;
			case '3':$suppGrade='ͭ';break;
			case '4':$suppGrade='������';break;
			case '0':$suppGrade='δ��֤';break;
			default: $suppGrade='';break;
		}
		$this->assign ( "suppGradeCn", $suppGrade );
      $this->view ( 'change-grade');
   }

   /**
	 * ��ת���鿴�����Ӧ�̿�ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : "";
	  $this->assign ( 'actType', $actType );
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch($obj['suppGrade']){
			case '1':$suppGrade='��';break;
			case '2':$suppGrade='��';break;
			case '3':$suppGrade='ͭ';break;
			case '4':$suppGrade='������';break;
			case '0':$suppGrade='δ��֤';break;
			default: $suppGrade='';break;
		}
		$this->assign ( "suppGrade", $suppGrade );

      $this->view ( 'view' );
   }

      /**
	 * ��ת���鿴�����Ӧ�̿�ҳ��
	 */
	function c_toChangeView() {
      $this->permCheck (); //��ȫУ��
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	  $this->assign ( 'actType', $actType );
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		switch($obj['suppGrade']){
			case '1':$suppGrade='��';break;
			case '2':$suppGrade='��';break;
			case '3':$suppGrade='ͭ';break;
			case '4':$suppGrade='������';break;
			case '0':$suppGrade='δ��֤';break;
			default: $suppGrade='';break;
		}
		$this->assign ( "suppGrade", $suppGrade );
		switch($obj['gradeChange']){
			case '1':$gradeChange='��';break;
			case '2':$gradeChange='��';break;
			case '3':$gradeChange='ͭ';break;
			case '4':$gradeChange='������';break;
			case '0':$suppGrade='δ��֤';break;
			default: $gradeChange='';break;
		}
		$this->assign ( "gradeChange", $gradeChange );

      $this->view ( 'change-view' );
   }
  /**
	 * ��ת���鿴�����Ӧ�̿�ҳ��TAB
	 */
	function c_toTabView() {
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : "";
	  $this->assign ( 'actType', $actType );
		$this->assign ( "id", $_GET ['id'] );
      	$this->view ( 'view-tab' );
   }

     /**
	 * ��ת���鿴�����Ӧ�̿�ҳ��TAB
	 */
	function c_toTabChangeView() {
      $actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : "";
	  $this->assign ( 'actType', $actType );
		$this->assign ( "id", $_GET ['id'] );
      	$this->view ( 'change-tab' );
   }
  /**
	 * ��ת���༭�����Ӧ�̿�ҳ��TAB
	 */
	function c_toTabEdit() {
		$this->assign ( "id", $_GET ['id'] );
      	$this->view ( 'edit-tab' );
   }

      /**
	 * ����excel
	 */
	function c_toExcelIn(){
		$this->display('excelin');
	}

      /**
	 * �������excel
	 */
	function c_toExcelUpdate(){
		$this->display('excel-update');
	}

   	/**
	 * �����������
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName]);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '����ɹ���';
		if ($id) {
			msg ( $msg );
		}else{
			msg('����ʧ��');

		}
	}

	 /**
	 *��Ϣ�޸�
	 *
	 */
	 function c_edit(){
		$flag=$this->service->edit_d( $_POST [$this->objName]);
		if($flag){
			msgGo('����ɹ�');
		}else{
			msgGo('����ʧ��');

		}
	 }

	 	 /**
	 *��Ϣ�޸�
	 *
	 */
	 function c_changeGrade(){
		$flag=$this->service->changeGrade_d( $_POST [$this->objName]);
		if($flag){
			succ_show('controller/outsourcing/supplier/ewf_change_index.php?actTo=ewfSelect&billId=' . $_POST [$this->objName]['id']);
			$sql="update oa_outsourcesupp_supplib SET ExaStatus='���' where id=". $_POST [$this->objName]['id'];
	  		$this->service->query($sql);
		}else{
			msg('�ύ���ʧ��');

		}
	 }

	     /**
     * ������������ʼ�
     */
     function c_dealChange(){
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines']=="ok"){  //����ͨ��
				$obj = $this->service->dealChange_d ( $folowInfo['objId'] );
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

		function c_ajaxChange() {
			$flag=$this->service->ajaxChange_d ( $_POST ['id'] );
			if($flag){
				echo 1;
			}else{
				echo 0;
			}
	}

		 	/**
	 * ����excel
	 */
	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '�����Ӧ�̵������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

			 	/**
	 * ����excel
	 */
	function c_excelUpdate(){
		set_time_limit(0);
		$resultArr = $this->service->updateExecelData_d ();

		$title = '�����Ӧ�̵�����½���б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ������������excel
	 */
	 function c_excelOutAll() {
		set_time_limit(0);
		$rows = $this->service->listBySqlId('select_Outall');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		//var_dump($rows);exit();
		$colArr  = array(
//			"suppCode"=>"��Ӧ�̱��",
//			"suppName"=>"��Ӧ������",
//			"suppGrade"=>"��Ӧ�̵ȼ�",
//			"mainBusiness"=>"��Ҫ��Ӫ��Χ",
//			"registeredFunds"=>"ע���ʽ�",
//			"legalRepre"=>"���˴���",
//			"address"=>"��Ӧ�̵�ַ",
//			"bankName"=>"��������",
//			"accountNum"=>"�����˺�",
//			"name"=>"��ϵ��",
//			"jobName"=>"ְλ",
//			"mobile"=>"�绰"
		);
		$modelName = '��Ӧ����Ϣ';
		return model_outsourcing_basic_export2ExcelUtil:: export2ExcelUtil($colArr, $rows, $modelName);
	 }

	//��ɾ����Ӧ��
	function c_deleteSupp() {
		$flag=$this->service->deleteSupp_d ( $_POST ['id'],$_POST ['isDel'] );
		if($flag){
			echo 1;
		}else{
			echo 0;
		}
	}

		/**
	 * ��ȡȨ��
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}
	
	/**
	 * ��ȡʡ�ݱ��룬��ϵ��,�绰,�����˻�������
	 */
	function c_getInfo(){
		$result = $this->service->getInfo_d($_POST);
// 		print_r($result);die();
		echo util_jsonUtil::encode($result);
	} 
 }