<?php
/**
 * @author Michael
 * @Date 2014��1��7�� ���ڶ� 10:22:36
 * @version 1.0
 * @description:������Ӧ��-������Ϣ���Ʋ�
 */
class controller_outsourcing_outsourcessupp_vehiclesupp extends controller_base_action {

	function __construct() {
		$this->objName = "vehiclesupp";
		$this->objPath = "outsourcing_outsourcessupp";
		parent::__construct ();
	 }

	/**
	 * ��ת��������Ӧ��-������Ϣ�б�
	 */
	function c_page() {
		// $this->service->setCompany(0); # �����б�,����Ҫ���й�˾����
		$this->view('list');
	}

	/**
	 * ��ת��������Ӧ��-�������б�
	 */
	function c_toBlacklist() {
		$this->view('blacklist');
	}

   /**
	 * ��ת������������Ӧ��-������Ϣҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' ));  //��Ӧ������
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ));  //��Ʊ����
		$this->view ('add' ,true);
	}

   	/**
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName]);
		if ($id) {
			msg('����ɹ���');
		}else{
			msg('����ʧ�ܣ�');
		}
	}

   /**
	 * ��ת���༭������Ӧ��-������Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' ) ,$obj['suppCategory']); //��Ӧ������
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ) ,$obj['invoiceCode']);  //��Ʊ����
		$this->view ( 'edit');
	}

   /**
	 * ��ת���༭������Ӧ��-������Ϣҳ��TAB
	 */
	function c_toEditTab() {
		$this->permCheck (); //��ȫУ��
		$this->assign ( 'id' ,$_GET ['id'] );
		$this->view ( 'edit-tab');
	}

	 /**
	  * �༭
	  */
	 function c_edit() {
		$flag = $this->service->edit_d( $_POST[$this->objName] );
		if( $flag ){
			msgGo('����ɹ�');
		}else{
			msgGo('����ʧ��');
		}
	 }

   /**
	 * ��ת���鿴������Ӧ��-������Ϣҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ('taxPoint' ,$obj['taxPoint'].'%');
		if ($obj['isEquipDriver'] == 1) {
			$obj['isEquipDriver'] = '��';
		} else {
			$obj['isEquipDriver'] = '����';
		}
		$this->assign ('isEquipDriver' ,$obj['isEquipDriver']);

		if ($obj['isDriveTest'] == 1) {
			$obj['isDriveTest'] = '��';
		} else {
			$obj['isDriveTest'] = 'û��';
		}
		$this->assign ('isDriveTest' ,$obj['isDriveTest']);
		$this->view ( 'view' );
	}

   /**
	 * ��ת���鿴������Ӧ��-������Ϣҳ��TAB
	 */
	function c_toViewTab() {
		$this->permCheck (); //��ȫУ��
		$this->assign ( 'id' ,$_GET ['id'] );
		$this->view ( 'view-tab' );
	}

	/**
	 * �б�߼���ѯ
	 */
	function c_toSearch(){
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' )); //��Ӧ������
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ));  //��Ʊ����
        $this->view('search');
	}

	/**
	 * ��ת����Ӧ����Ϣ����ҳ��
	 */
	function c_toImportPage() {
		$this->view ( "import" );
	}

	/**
	 * ����ҳ��-���빩Ӧ��
	 */
	 function c_importVehiclesupp() {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_outsourcing_outsourcessupp_importVehiclesuppUtil();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$newResult = util_jsonUtil::encode ( $excelData );
			echo "<script>window.parent.setExcelValue('".$newResult."');self.parent.tb_remove()</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');history.go(-1);</script>";
		}
	 }

	/**
	 * ��Ӧ������Ϊ����
	 * ���ݵ绰����������Ƿ��ظ�
	 */
	function c_checkRepeatByPhone() {
		$checkId = "";
		$service = $this->service;
		if (isset ( $_REQUEST ['id'] )) {
			$checkId = $_REQUEST ['id'];
			unset ( $_REQUEST ['id'] );
		}
		if(!isset($_POST['validateError'])){
			$service->getParam ( $_REQUEST );
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			echo $isRepeat;
		}else{
			//����֤���
			$validateId=$_POST['validateId'];
			$validateValue=$_POST['validateValue'];
			$phoneNum = $_REQUEST['phoneNum'];
			$service->searchArr=array(
				$validateId."Eq"=>$validateValue ,
				"linkmanPhoneEq"=>$phoneNum
			);
			$isRepeat = $service->isRepeat ( $service->searchArr, $checkId );
			$result=array(
				'jsonValidateReturn'=>array($_POST['validateId'],$_POST['validateError'])
			);
			if($isRepeat){
				$result['jsonValidateReturn'][2]="false";
			}else{
				$result['jsonValidateReturn'][2]="true";
			}
			echo util_jsonUtil::encode ( $result );
		}
	}

	/**
	 * ����������Ӧ����Ϣ
	 */
	function c_excelOut() {
		set_time_limit(0);
		$rows = $this->service->listBySqlId('select_excelOut');
		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '������Ӧ����Ϣ';
		$startRowNum = 3;
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rows, $modelName ,$startRowNum);
	}

    /**
	 * ��ת���Զ��嵼��excelҳ��
	 */
	function c_toExcelOutCustom() {
		$this->showDatadicts ( array ('suppCategory' => 'WBGYSLX' )); //��Ӧ������
		$this->showDatadicts ( array ('invoiceCode' => 'WBFPZL' ));  //��Ʊ����
		$this->view('excelOutCustom');
	}

	/**
	 * �Զ��嵼��������Ӧ����Ϣ
	 */
	function c_excelOutCustom() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['suppCode'])) //��Ӧ�̱��
			$this->service->searchArr['suppCodeSea'] = $formData['suppCode'];

		if(!empty($formData['suppName'])) //��Ӧ������
			$this->service->searchArr['suppName'] = $formData['suppName'];

		if(!empty($formData['provinceName'])) //ʡ��
			$this->service->searchArr['province'] = $formData['provinceName'];

		if(!empty($formData['cityName'])) //����
			$this->service->searchArr['city'] = $formData['cityName'];

	 	if(!empty($formData['suppCategory'])) //��Ӧ������
			$this->service->searchArr['suppCategory'] = $formData['suppCategory'];

		if(!empty($formData['carAmountLower'])) //������Χ��
			$this->service->searchArr['carAmountLower'] = $formData['carAmountLower'];
		if(!empty($formData['carAmountCeiling'])) //������Χ��
			$this->service->searchArr['carAmountCeiling'] = $formData['carAmountCeiling'];

		if(!empty($formData['driverAmountLower'])) //˾��������
			$this->service->searchArr['driverAmountLower'] = $formData['driverAmountLower'];
		if(!empty($formData['driverAmountCeiling'])) //˾��������
			$this->service->searchArr['driverAmountCeiling'] = $formData['driverAmountCeiling'];

		if(!empty($formData['isEquipDriver'])) //�ܷ��䱸˾��
			$this->service->searchArr['isEquipDriver'] = $formData['isEquipDriver'];

		if(!empty($formData['isDriveTest'])) //����·�⾭��
			$this->service->searchArr['isDriveTest'] = $formData['isDriveTest'];

		$rows = $this->service->listBySqlId('select_excelOut');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
		}

		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}

		$colArr  = array();
		$modelName = '������Ӧ����Ϣ';
		$startRowNum = 3;
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rows, $modelName ,$startRowNum);
	}

    /**
	 * ��ת������excelҳ��
	 */
	function c_toExcelIn() {
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn() {
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '������Ӧ�̵������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ��ת����Ӧ�̴��������ҳ��
	 */
	function c_toBlacklistView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "blacklistview" );
	}

	/**
	 * ��ת����Ӧ�̳���������ҳ��
	 */
	function c_toUndoBlackView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "undoBlackView" );
	}

	/**
	 * ��ת������������ҳ��
	 */
	function c_toAddBlacklist() {
		$this->permCheck (); //��ȫУ��
		$this->view ( "add-blacklist" );
	}

    /**
	 * ��ת��excel���������ҳ��
	 */
	function c_toExcelInBlack() {
		$this->display('excelin-black');
	}

	/**
	 * ����excel������
	 */
	function c_excelInBlack() {
		set_time_limit(0);
		$resultArr = $this->service->blackExecelData_d ();

		$title = '������Ӧ�̺������������б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ���������
	 */
	function c_blacklist() {
		$obj = $_POST[$this->objName];
		$obj['suppLevel'] = 0;
		$flag = $this->service->addBlacklist_d( $obj );
		if( $flag ){
			msg('����ɹ�');
		}else{
			msg('����ʧ��');
		}
	}

	/**
	 * ����������
	 */
	function c_undoBlack() {
		$obj = $_POST[$this->objName];
		$obj['suppLevel'] = '';
		$flag = $this->service->addBlacklist_d( $obj );
		if( $flag ){
			msg('����ɹ�');
		}else{
			msg('����ʧ��');
		}
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits() {
		$limitName = explode(',' ,util_jsonUtil::iconvUTF2GB($_POST['limitArr']));
		$limitData = '';
		foreach ($limitName as $k => $v) {
			$limitData = $limitData.($this->service->this_limit[$v]?$this->service->this_limit[$v]:0).',';
		}
		echo util_jsonUtil::encode ( $limitData );
	}

    /**
     * ��ȡ��֧ͬ����ʽ�����ķ������Ϣ
     */
	function c_getRelativeExpenseTmp(){
	    $payTypeId = isset($_POST['payTypeId'])? $_POST['payTypeId'] : '';
        $rentContId = isset($_POST['rentContId'])? $_POST['rentContId'] : '';
        $backArr = array(
            "result" => "no",
            "data" => array()
        );
        if($payTypeId != '' && $rentContId != ''){
            $chkSql = "select t.id as tmpId,t.expenseId,p.* from oa_contract_rentcar_expensetmp t 
                    left join oa_contract_rentcar_payinfos p on t.payInfoId = p.id 
                    where  t.payMoney > 0 and (t.ExaStatus = 'δ����' or t.isConfirm = 0) and t.rentalContractId = '{$rentContId}' and t.payInfoId = '{$payTypeId}';";
            $result = $this->service->_db->getArray($chkSql);

            if($result){
                $resultArr = $result[0];
                if($resultArr['expenseId'] == ''){// ֻ���ǻ�δ���ɱ����������,���б�������,������
                    $backArr['result'] = "ok";
                    $backArr['data'] = $resultArr;
                    $backArr['sql'] = $chkSql;
                }
            }
        }
        echo util_jsonUtil::encode($backArr);
    }
}
?>