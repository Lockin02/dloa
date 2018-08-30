<?php
/**
 * @author zengzx
 * @Date 2011��5��4�� 15:55:01
 * @version 1.0
 * @description:���������Ʋ�
 */
class controller_stock_outplan_ship extends controller_base_action {

	function __construct() {
		$this->objName = "ship";
		$this->objPath = "stock_outplan";
		parent::__construct ();
	}

	/*
	 * ��ת��������
	 */
	function c_page() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$docType = $_GET ['docType'];
		$contId = $_GET ['id'];
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr [$docType];
		$cont = $service->getDocInfo ( $contId, new $relatedStrategy () );
		$this->assign ( 'shipman', $_SESSION ['USERNAME'] );
		$this->assign ( 'shipmanId', $_SESSION ['USER_ID'] );
		$this->assign ( 'docType', $docType );
		switch($cont['docType']){
			case 'oa_contract_contract':
					$contract = new model_contract_contract_contract();
					$row = $contract->find(array('id'=>$cont['docId']));
					$this->assign ( 'receiver', $row ['prinvipalName'] );
					$this->assign ( 'receiverId', $row ['prinvipalId'] );
					break;
			case 'oa_borrow_borrow':
					$borrow = new model_projectmanagent_borrow_borrow();
					$row = $borrow->find(array('id'=>$cont['docId']));
					$this->assign ( 'receiver', $row ['salesName'] );
					$this->assign ( 'receiverId', $row ['salesNameId'] );
					break;
			case 'oa_present_present':
					$present = new model_projectmanagent_present_present();
					$row = $present->find(array('id'=>$cont['docId']));
					$this->assign ( 'receiver', $row ['salesName'] );
					$this->assign ( 'receiverId', $row ['salesNameId'] );
					break;
			case 'oa_contract_exchangeapply':
					$exchangeapply = new model_projectmanagent_exchange_exchange();
					$row = $exchangeapply->find(array('id'=>$cont['docId']));
					$this->assign ( 'receiver', $row ['saleUserName'] );
					$this->assign ( 'receiverId', $row ['saleUserId'] );
					break;
		}
		switch ($docType) {
			case 'oa_service_accessorder' :
				$this->assign ( 'docCode', $cont ['docCode'] );
				$this->assign ( 'docId', $cont ['id'] );
				$this->assign ( 'docTypeName', "�������" );
				$this->assign ( 'customerName', $cont ['customerName'] );
				$this->assign ( 'customerId', $cont ['customerId'] );
				$this->assign ( 'mobil', $cont ['telephone'] );
				$this->assign ( 'linkmanId', $cont ['contactUserId'] );
				$this->assign ( 'linkman', $cont ['contactUserName'] );
				$this->assign ( 'address', $cont ['adress'] );
				$this->assign ( 'shipConcern', $cont ['remark'] );
				$this->assign ( 'shipStatus', "1" );
				$this->assign ( 'products', $service->showItemAddByPlan ( $cont, new $relatedStrategy () ) );
				//������������ʼ��������ÿ�
				$this->assign ( 'receiver', '' );
				$this->assign ( 'receiverId', '' );
				$this->display ( 'accessorder-add' );
				break;
			case 'oa_service_repair_apply' :
				$this->assign ( 'docCode', $cont ['docCode'] );
				$this->assign ( 'docId', $cont ['id'] );
				$this->assign ( 'docTypeName', "ά�����뵥" );
				$this->assign ( 'customerName', $cont ['customerName'] );
				$this->assign ( 'customerId', $cont ['customerId'] );
				$this->assign ( 'mobil', $cont ['telephone'] );
				$this->assign ( 'linkmanId', $cont ['contactUserId'] );
				$this->assign ( 'linkman', $cont ['contactUserName'] );
				$this->assign ( 'address', $cont ['adress'] );
				$this->assign ( 'shipConcern', $cont ['remark'] );
				$this->assign ( 'shipStatus', "0" );
				$this->assign ( 'products', $service->showItemAddByPlan ( $cont, new $relatedStrategy () ) );
				//��ȡά�����뵥�����ʼ�Ĭ�Ͻ�����
				$mailInfo = $this->service->getMailUser_d('shipFHDTZ');
				$this->assign ( 'receiver', $mailInfo['defaultUserName']);
				$this->assign ( 'receiverId', $mailInfo['defaultUserId']);
				$this->display ( 'accessorder-add' );
				break;
			default :
//				$this->permCheck ( $_GET ['id'], 'stock_outplan_outplan' );
				$shipCode = 'SC-' . generatorSerial ();
				if (isset ( $cont ['hasCus'] ) && $cont ['hasCus'] == 1) {
					$this->assign ( 'hasCus', 1 );
				} else {
					$this->assign ( 'hasCus', 0 );
				}
				foreach ( $cont as $key => $val ) {
					$this->assign ( $key, $val );
				}
				$this->showDatadicts ( array ('type' => 'FHXZ' ) );
				$this->assign ( 'products', $service->showItemAddByPlan ( $cont ['details'], new $relatedStrategy () ) );
				$this->assign ( 'docId', $cont ['docId'] );
				$this->assign ( 'docCode', $cont ['docCode'] );
				$this->assign ( 'docName', $cont ['docName'] );
				$this->assign ( 'shipCode', $shipCode );
				$this->assign ( 'pageAction', 'add' );
				$this->display ( 'add' );
				break;

		}

	}

	/**
	 * ��ת������ҳ��
	 */
	function c_toAddWithoutPlan() {
		if (! isset ( $this->service->this_limit ['��������������'] )) {
			showmsg ( 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա' );
		}
		$contId = $_GET ['id'];
		$this->assign ( 'shipman', $_SESSION ['USERNAME'] );
		$this->assign ( 'shipmanId', $_SESSION ['USER_ID'] );
		$this->assign ( 'docType', 'independent' );
		$service = $this->service;
		$this->display ( 'addwithoutplan' );
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$object = $_POST [$this->objName];
		$contractCode = $object['docCode'];
		$object ['docStatus'] = 0;
		if (isset ( $_GET ['act'] )) {
			$object ['shipStatus'] = 1;
			$id = $this->service->addWithoutPlan_d ( $object, $isAddInfo );
		} else {
			if (! isset ( $object ['shipStatus'] ))
				$object ['shipStatus'] = 0;
			$id = $this->service->add_d ( $object, $isAddInfo );
		}
		$shipObj = $this->service->findBy ( 'id', $id );
		$msg = $_GET ["msg"];
		if ($id) {
			//�ʼ�֪ͨ
			$object['id'] = $id;
			$this->service->sendMail_d($object);
			if ($msg) {
				$url = 'index1.php?model=mail_mailinfo&action=toAddByShip&shipId=' . $id . '&shipCode=' . $shipObj ['shipCode'];
				msgGo ( '����ɹ��������ת���ʼ�!', $url );
			} else {
				if (isset ( $_GET ['act'] )) {
					msg ( '��ӳɹ�' );
				} else {
					msgRf ( '��ӳɹ�' );
				}
			}
		} else {
			msg ( '������Ϣ����ʧ��!' );
		}
	}

	/**
	 * ��ת����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck ();
		$shipId = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$docType = isset ( $_GET ['docType'] ) ? $_GET ['docType'] : null;
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr [$docType];
		$shipInfo = $service->get_d ( $shipId );
		if ($_SESSION ['USER_ID'] != $shipInfo ['createId']) {
			if (! isset ( $this->service->this_limit ['�������༭'] )) {
				showmsg ( 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա' );
			}
		}
		foreach ( $shipInfo as $key => $val ) {
			if ($key == 'details') {
				$products = $service->showItemEdit ( $shipInfo ['details'], new $relatedStrategy () );
				$this->show->assign ( "products", $products );
			}
			$this->show->assign ( $key, $val );
		}
		$this->assign ( 'products', $products );
		$this->assign ( 'pageAction', 'edit' );
		switch ($docType) {
			case 'independent' :
				$this->display ( 'editwithoutplan' );
				break;
			case 'oa_service_accessorder' :
				$this->assign ( "docTypeName", "�������" );
				$this->display ( 'accessorder-edit' );
				break;
			case 'oa_service_repair_apply' :
				$this->assign ( "docTypeName", "ά�����뵥" );
				$this->display ( 'accessorder-edit' );
				break;
			default :
				$this->display ( 'edit' );
				break;
		}
	}
	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		$id = $this->service->edit_d ( $object, $isEditInfo );
		$msg = $_GET ["msg"];
		if ($id) {
			if ($msg) {
				$url = 'index1.php?model=mail_mailinfo&action=toAddByShip&shipId=' . $object ['id'] . '&shipCode=' . $object ['shipCode'];
				msgGo ( '����ɹ��������ת���ʼ�!', $url );
			} else {
				msg ( '��ӳɹ�' );
			}
		}
	}
	/**
	 * ��ת���鿴ҳ��
	 */
	function c_toView() {
		$this->permCheck ();
		$shipId = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$docType = isset ( $_GET ['docType'] ) ? $_GET ['docType'] : null;
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr [$docType];
		$shipInfo = $service->get_d ( $shipId );
		foreach ( $shipInfo as $key => $val ) {
			if ($key == 'details') {
				$products = $service->showItemView ( $shipInfo ['details'], new $relatedStrategy () );
				$this->show->assign ( "products", $products );
			}
			$this->show->assign ( $key, $val );
		}
		$this->assign ( 'products', $products );
		$this->assign ( 'pageAction', 'add' );
		switch ($docType) {
			case 'independent' :
				$this->display ( 'viewwithoutplan' );
				break;
			case 'oa_service_accessorder' :
				$this->assign ( "docTypeName", "�������" );
				$this->display ( 'accessorder-view' );
				break;
			case 'oa_service_repair_apply' :
				$this->assign ( "docTypeName", "ά�����뵥" );
				$this->display ( 'accessorder-view' );
				break;
			default :
				$this->display ( 'view' );
				break;
		}
	}

	/**
	 * ��ת���鿴ҳ��
	 */
	function c_toPrint() {
		$this->permCheck ();
		$shipId = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$docType = isset ( $_GET ['docType'] ) ? $_GET ['docType'] : null;
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr [$docType];
		$shipInfo = $service->get_d ( $shipId );
		foreach ( $shipInfo as $key => $val ) {
			if ($key == 'details') {
				$products = $service->showItemPrint ( $shipInfo ['details'], new $relatedStrategy () );
				$this->show->assign ( "products", $products );
			}
			$this->show->assign ( $key, $val );
		}
		$this->assign ( 'products', $products );
		switch ($shipInfo ['shipType']) {
			case 'order' :
				$this->assign ( 'order', 'checked' );
				break;
			case 'borrow' :
				$this->assign ( 'borrow', 'checked' );
				break;
			case 'lease' :
				$this->assign ( 'lease', 'checked' );
				break;
			case 'trial' :
				$this->assign ( 'trial', 'checked' );
				break;
			case 'change' :
				$this->assign ( 'change', 'checked' );
				break;
		}
		switch ($docType) {
			case 'independent' :
				$this->display ( 'printwithoutplan' );
				break;
			case 'oa_service_accessorder' :
				$this->assign ( "docTypeName", "�������" );
				$this->display ( 'print-accessorder' );
				break;
			case 'oa_service_repair_apply' :
				$this->assign ( "docTypeName", "ά�����뵥" );
				$this->display ( 'print-accessorder' );
				break;
			default :
				$this->display ( 'print' );
				break;
		}
	}

	/**
	 * ��ת��ǩ��
	 */
	function c_toSign() {
		$this->permCheck ();
		$shipId = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$docType = isset ( $_GET ['docType'] ) ? $_GET ['docType'] : null;
		$service = $this->service;
		$relatedStrategy = $service->relatedStrategyArr [$docType];
		$shipInfo = $service->get_d ( $shipId );
		foreach ( $shipInfo as $key => $val ) {
			if ($key == 'details') {
				$products = $service->showItemView ( $shipInfo ['details'], new $relatedStrategy () );
				$this->show->assign ( "products", $products );
			}
			$this->show->assign ( $key, $val );
		}
		$this->assign ( 'products', $products );
		switch ($docType) {
			case 'independent' :
				$this->display ( 'signwithoutplan' );
				break;
			case 'oa_service_accessorder' :
				$this->assign ( "docTypeName", "�������" );
				$this->display ( 'sign-accessorder' );
				break;
			case 'oa_service_repair_apply' :
				$this->assign ( "docTypeName", "ά�����뵥" );
				$this->display ( 'sign-accessorder' );
				break;
			default :
				$this->display ( 'sign' );
				break;
		}
	}
	/**
	 * ������ǩ��
	 */
	function c_sign() {
		$planInfo = $_POST [$this->objName];
		$planInfo ['isSign'] = 1;
		if ($this->service->sign_d ( $planInfo, true )) {
			msg ( 'ǩ�ճɹ�' );
		}
	}
	/**
	 * ���ݼƻ�Id���˵õ��ķ������б�
	 */
	function c_listByPlan() {
		$this->assign ( 'planId', $_GET ['planId'] );
		$this->display ( 'listbyplan' );
	}
	/**
	 * ���ݼƻ�Id���˵õ��ķ������б�
	 */
	function c_listByOrder() {
		switch ($_GET ['objType']) {
			case 'oa_contract_contract' :
				$modelName = "contract_contract_contract";
				break;
			case 'oa_sale_order' :
				$modelName = "projectmanagent_order_order";
				break;
			case 'oa_sale_lease' :
				$modelName = "contract_rental_rentalcontract";
				break;
			case 'oa_sale_service' :
				$modelName = "engineering_serviceContract_serviceContract";
				break;
			case 'oa_sale_rdproject' :
				$modelName = "rdproject_yxrdproject_rdproject";
				break;
			case 'oa_borrow_borrow' :
				$modelName = "projectmanagent_borrow_borrow";
				break;
			case 'oa_present_present' :
				$modelName = "projectmanagent_present_present";
				break;
			case 'oa_service_accessorder' :
				$modelName = "service_accessorder_accessorder";
				break;
			case 'oa_service_repair_apply' :
				$modelName = "service_repair_repairapply";
				break;
		}
		$this->permCheck ( $_GET ['orderId'], $modelName );
		$this->assign ( 'orderId', $_GET ['orderId'] );
		$this->assign ( 'objType', $_GET ['objType'] );
		$this->display ( 'listbyorder' );
	}

	/**
	 * ��д���ⵥʱ����̬��Ӵӱ�ģ��
	 */
	function c_getItemList() {
		$shipId = isset ( $_POST ['shipId'] ) ? $_POST ['shipId'] : null;
		$list = $this->service->getEquList_d ( $shipId );
		echo util_jsonUtil::iconvGB2UTF ( $list );
	}

	/**
	 * ��д���ⵥʱ����̬��Ӵӱ�ģ�� - ��������ʹ��
	 */
	function c_getItemListOther() {
		$shipId = isset ( $_POST ['shipId'] ) ? $_POST ['shipId'] : null;
		$list = $this->service->getEquListOther_d ( $shipId );
		echo util_jsonUtil::iconvGB2UTF ( $list );
	}

	/**
	 * ����������excel
	 * 2011��8��1�� 09:41:30
	 * zengzx
	 */
	function c_toExportExcel() {
		$this->permCheck ();
		$shipId = $_GET ['id'];
		$shipInfo = $this->service->get_d ( $shipId );
		return model_stock_outplan_shipExcelUtil::exporTemplate ( $shipInfo );
	}

	/**
	 * ���������ķ������༭ҳ��
	 */
	function c_toEditWithoutPlan() {
		$planId = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$service = $this->service;
		$shipInfo = $service->get_d ( $planId );
		foreach ( $shipInfo as $key => $val ) {
			if ($key == 'details') {
				$products = $service->showItemEditW ( $shipInfo ['details'] );
				$this->show->assign ( "products", $products );
			}
			$this->show->assign ( $key, $val );
		}
		$this->assign ( 'products', $products );
		$this->display ( 'edit' );
	}

	/**
	 * ajaxɾ�������������ң�ɾ���������ʼ���Ϣ��¼���ع������ƻ���ִ��״̬
	 */
	function c_ajaxDelete() {
		$service = $this->service;
		$id = $_POST ['id'];
		$shipObj = $service->get_d ( $id );
		echo $service->deleteObj ( $shipObj );
	}

}
?>