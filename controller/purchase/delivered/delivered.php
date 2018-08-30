<?php
/**
 * @author suxc
 * @Date 2011��5��6�� 9:52:24
 * @version 1.0
 * @description:����֪ͨ����Ϣ���Ʋ�
 */
class controller_purchase_delivered_delivered extends controller_base_action {

	function __construct() {
		$this->objName = "delivered";
		$this->objPath = "purchase_delivered";
		parent::__construct ();
	 }

	/*
	 * ��ת��δִ������֪ͨ����Ϣ
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/*
	 * ��ת����ִ������֪ͨ����Ϣ
	 */
    function c_toCloseList() {
      $this->show->display($this->objPath . '_' . $this->objName . '-close-list');
    }

    /**
	 *��ת���������֪ͨ��ҳ��.
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toAdd () {
		$act=isset($_GET['act'])?$_GET['act']:null;
		$this->assign('act',$act);
//		$deliveredCode="delivered-".generatorSerial();
		$this->assign("purchManId" , $_SESSION['USER_ID']);
		$this->assign("purchManName" , $_SESSION['USERNAME']);
//		$this->assign('returnCode',$deliveredCode);
		$this->assign('returnDate',date("Y-m-d"));
		$this->display('add-external');
	}

    /**
	 *���б��������֪ͨ��ҳ��.
	 *
	 * @param tags
	 * @return return_type
	 */
	function c_toAddInGrid () {
		$act=isset($_GET['act'])?$_GET['act']:null;
		$this->assign('act',$act);
//		$deliveredCode="delivered-".generatorSerial();
		$this->assign("purchManId" , $_SESSION['USER_ID']);
		$this->assign("purchManName" , $_SESSION['USERNAME']);
//		$this->assign('returnCode',$deliveredCode);
		$this->assign('returnDate',date("Y-m-d"));
		$this->display('add');
	}

	/**��д��ʼ�����󷽷�
	*author can
	*2011-2-22
	*/
	function c_init() {
		$this->permCheck ();//��ȫУ��
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $returnObj as $key => $val ) {
			$this->show->assign ( $key, $val );
		$equipmentDao=new model_purchase_delivered_equipment();
		}
		//��ȡ�������뵥�����嵥
		$itemRows=$this->service->getEquipment_d($_GET ['id']);

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$viewType=isset($_GET['actType'])?$_GET['actType']:"";
		    $list=$equipmentDao->showViewList($itemRows);;
			$this->show->assign('list',$list);
			$this->assign('viewType',$viewType);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
		} else {
			$list=$equipmentDao->showEditList($itemRows);
			$this->show->assign('list',$list);
			$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
		}
	}

	/**
	 * �ҵ�����֪ͨ��
	 */
	function c_toMyList () {
		$this->display('my-list');
	}

	/**
	 * �ҵ�����֪ͨ��
	 */
	function c_toPushByArrival () {
		$this->assign("purchManId" , $_SESSION['USER_ID']);
		$this->assign("purchManName" , $_SESSION['USERNAME']);
		$this->assign('returnDate',date("Y-m-d"));
		$arrivalDao=new model_purchase_arrival_arrival();
		$arrivalObj=$arrivalDao->get_d($_GET['arrivalId']);
//		echo "<pre>";
//		print_r($arrivalObj);exit();
        $this->assign("formBelong", $arrivalObj['formBelong']);
        $this->assign("formBelongName", $arrivalObj['formBelongName']);
        $this->assign("businessBelong", $arrivalObj['businessBelong']);
        $this->assign("businessBelongName", $arrivalObj['businessBelongName']);

		$this->assign("purchManId", $arrivalObj['purchManId']);
		$this->assign("purchManName", $arrivalObj['purchManName']);
		$this->assign("supplierId", $arrivalObj['supplierId']);
		$this->assign("supplierName", $arrivalObj['supplierName']);
		$this->assign("sourceId", $arrivalObj['id']);
		$this->assign("sourceCode", $arrivalObj['arrivalCode']);
		$this->assign("deliveryPlace", $arrivalObj['deliveryPlace']);

		$purchMode = $this->getDataNameByCode($arrivalObj['purchMode']);
		$this->assign("purchMode", $purchMode);
		$equDao=new model_purchase_arrival_equipment();
		$rows=$equDao->getItemByBasicIdId_d($_GET['arrivalId']);
		//print_r($rows);
		//echo $this->service->showPurchAppProInfo($rows);
		$this->assign("itemsList", $this->service->showPurchAppProInfo($rows));

		$this->display('arrival-push');
	}


	/**�ҵ�����֪ͨ��PageJson���˷���
	*/
	function c_myApplyPJ() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$applyUserId = isset($_SESSION ['USER_ID'])?$_SESSION ['USER_ID']:null;
		$service->searchArr['createId'] = $applyUserId;
		$rows = $service->pageBySqlId();
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

    /**
	 * �������֪ͨ��
	 *
	 */
	 function c_add(){
	 	$id=$this->service->add_d($_POST[$this->objName]);
	 	if($id){
	 		if($_GET['type']=="external"){     //��grid�б����������֪ͨ�����������תҳ��
	 			msgGo('����ɹ�','?model=purchase_delivered_delivered&action=toAdd');
	 		}else{
				msg('����ɹ�');
	 		}
	 	}else{
	 		if($_GET['type']=="external"){
	 			msgGo('����ʧ��','?model=purchase_delivered_delivered&action=toAdd');
	 		}else{
	 		msg('����ʧ�ܣ�������Ϣ������');
	 		}
	 	}
	 }

	/**
	 * ��ӹ̶��ʲ�����֪ͨ��
	 *
	 */
	 function c_addAsset(){
	 	$id=$this->service->addAsset($_POST[$this->objName]);
	 	if($id){
	 		msg('����ɹ�!');
	 	}else{
	 		msg('����ʧ��!');
	 	}
	 }

	/**
	 * ���вɹ�����ʱֱ���ύ����
	 */
	function c_toSubmitAudit () {
		$id=$this->service->add_d($_POST[$this->objName]);
		if($id){
	 		if($_GET['type']=="external"){     //��grid�б����������֪ͨ�����������תҳ��
	 			succ_show ( 'controller/purchase/delivered/ewf_index.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_purchase_delivered&formName=�ɹ���������' );
	 		}else{
				succ_show ( 'controller/purchase/delivered/ewf_index_parent.php?actTo=ewfSelect&billId='.$id.'&examCode=oa_purchase_delivered&formName=�ɹ���������' );
	 		}
		}else{
	 		if($_GET['type']=="external"){
	 			msgGo('�ύʧ��','?model=purchase_delivered_delivered&action=toAdd');
	 		}else{
	 			msg('�ύʧ�ܣ�������Ϣ������');
	 		}
		}
	}

	/**
	 * ���вɹ�����ʱֱ���ύ����
	 */
	function c_edit () {
		$id=$this->service->edit_d($_POST[$this->objName]);
		if($id){
	 		if($_GET['type']=="aduit"){     //��grid�б����������֪ͨ�����������תҳ��
	 			succ_show ( 'controller/purchase/delivered/ewf_index_parent.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&examCode=oa_purchase_delivered&formName=�ɹ���������' );
	 		}else{
				msg('����ɹ�');
	 		}
		}else{
	 			msg('����ʧ�ܣ�������Ϣ������');
		}
	}
	/**
	 * ��ȡ�����嵥
	 */
	function c_addItemList () {
		$arrivalId=isset($_POST['arrivalId'])?$_POST['arrivalId']:null;
		$arrivalproDao=new model_purchase_arrival_equipment();
		$rows=$arrivalproDao->getItemByBasicIdId_d($arrivalId);
		$arrivalprostr = $this->service->showPurchAppProInfo($rows);
		echo $arrivalprostr;
	}
	/**
	 * �����ⵥʱ����̬��Ӵӱ�ģ��
	 */
	function c_getItemList () {
		$arrivalId=isset($_POST['deliveredId'])?$_POST['deliveredId']:null;
		$list=$this->service->getEquList_d($arrivalId);
		echo $list;
	}

	/**
	 * ��ȡ��ҳ����ת��Json-�ҵĲɹ�������֪ͨ��
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
        $service->_isSetCompany =$service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
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
	 * ��������ͨ����������Ӧ��������
	 */
	 function c_updateApplyPrice(){
		$rows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines']=="ok"){  //����ͨ��
				//������������
				$this->service->updateArrivalNum_d($folowInfo['objId']);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	 }
 }
?>