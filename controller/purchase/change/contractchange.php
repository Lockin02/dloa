<?php
class controller_purchase_change_contractchange extends controller_base_action {
	/**
	 * ���캯��
	 */
	function __construct() {
		$this->objName = "contractchange";
		$this->objPath = "purchase_change";

		parent::__construct ();
	}

	/**
	 * @description ����ı��淽��
	 * @author qian
	 * @date 2011-2-14 11:26
	 */
	function c_change() {
		$service = $this->service;
		$contract = $_POST ['contract'];
		$id = $service->change_d ( $contract );

		if ($_GET ['act'] == "app") {
			$phpurl = 'controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic_version&formName=�ɹ���ͬ����';
			$phpurl = str_replace ( " ", "", $phpurl );
			echo "<script>parent.location.replace('" . $phpurl . "');</script>";
		}

		if ($id) {
			//����ɹ�����ת�������˰칫��-���ҵ����롱
			//		echo "<script>alert('����ɹ�');parent.location='?model=purchase_change_contractchange&action=toMyChangeList'</script>";
			msgGo ( '����ɹ�!', "?model=purchase_contract_purchasecontract&action=myPurchaseContractTab#tab3" );
		}

	}

	/**
	 * @description �ɹ���ͬ���������Tabҳ
	 * @author qian
	 * @date 2011-2-14 16:32
	 */
	function c_toApprovalTab() {
		$this->display ( 'tab-approval' );
	}

	/**
	 * @description δ�����Ĳɹ���ͬ�б�ҳ
	 * @author qian
	 * @date 2011-2-14 16:38
	 */
	function c_toApprovalNo() {
		$this->display ( 'change-approvalNo' );
	}

	/**
	 * add by chengl 2011-05-12
	 * ȷ�Ϻ�ͬ���������ת��δ�����Ĳɹ���ͬ�б�ҳ
	 *
	 */
	function c_confirmChangeToApprovalNo() {
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			$objId = $folowInfo ['objId'];
			if (! empty ( $objId )) {
				$contractDao=new model_purchase_contract_purchasecontract();
				$contract = $this->service->get_d ( $objId );
				$contractDao->dealChange_d($contract);
				$changeLogDao = new model_common_changeLog ( 'purchasecontract' );
				$changeLogDao->confirmChange_d ( $contract );
			}
		}

		//��ֹ�ظ�ˢ��
		$urlType = isset($_GET['urlType']) ? $_GET['urlType'] : null;
		//��ֹ�ظ�ˢ��
		if($urlType){
			echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
		}else{
			echo "<script>this.location='?model=purchase_contract_purchasecontract&action=pcApprovalNo'</script>";
		}

		//$this->display ( 'change-approvalNo' );
	}

	/**
	 * @description �������Ĳɹ���ͬ�б�ҳ
	 * @author qian
	 * @date 2011-2-14 16:38
	 */
	function c_toApprovalYes() {
		$this->display ( 'change-approvalYes' );
	}

	/**
	 * @description ������Ĳɹ���ͬ���
	 * @author qian
	 * @date 2011-2-14 19:20
	 */
	function c_toMyChangeList() {
		$this->display ( 'list-change' );
	}

	/**
	 * @description ��ת���鿴��ͬ��Ϣ��Tabҳ
	 * @author qian
	 * @date 2011-2-22
	 */
	function c_toTabView() {
		$id = $_GET ['id'];
		$this->assign ( 'id', $id );
		$applyNumb = $_GET ['applyNumb'];
		$this->assign ( 'applyNumb', $applyNumb );

		$this->display ( 'tab-view' );
	}

	function c_toTabHistory() {
		$id = $_GET ['id'];
		$this->assign ( 'id', $id );
		$applyNumb = $_GET ['applyNumb'];
		$this->assign ( 'applyNumb', $applyNumb );

		$this->display ( 'list-history' );
	}

	/**
	 * �鿴��ʷ�汾
	 */
	function c_toViewVersion() {
		$id = $_GET ['id'];
		$service = $this->service;
		$returnObj = $this->objName;
		//���ݺ�ͬ�Ż�ñ�����ͬ������
		$rows = $service->get_d ( $id );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//���ݺ�ͬ���ҳ���Ӧ���豸����Ϣ
		$equs = $service->getEquipments_d ( $rows );
		$this->show->assign ( "file", $service->getFilesByObjId ( $id, false ) );
		$this->assign ( 'list', $service->addContractEquList_s ( $equs ) );
		//��Ʊ����
		$billingType = $this->getDataNameByCode ( $rows ['billingType'] );
		$this->assign ( 'bType', $billingType );

		//��������
		$paymetType = $this->getDataNameByCode ( $rows ['paymetType'] );
		$this->assign ( 'pType', $paymetType );

		//��������
		$suppBank = $this->getDataNameByCode ( $rows ['suppBank'] );
		$this->assign ( 'suppBank', $suppBank );

		//ǩԼ״̬
		$signStatus = $service->getSignStatus_d ( $rows ['signStatus'] );
		$this->assign ( 'signStatus', $signStatus );

		$this->display ( 'view' );
	}

	/**
	 * �����Ĳ鿴ҳ��
	 */
	function c_toView() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET ['pjId'];
		//���ݺ�ͬ�Ż�ñ�����ͬ������
		$rows = $service->get_d ( $id );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//���ݺ�ͬ���ҳ���Ӧ���豸����Ϣ
		$equs = $service->getEquipments_d ( $rows );
		$this->show->assign ( "file", $this->service->getFilesByObjId ( $id, false ) );
		$this->assign ( 'list', $service->addContractEquList_s ( $equs ) );
		//��Ʊ����
		$billingType = $this->getDataNameByCode ( $rows ['billingType'] );
		$this->assign ( 'bType', $billingType );

		//��������
		$paymetType = $this->getDataNameByCode ( $rows ['paymetType'] );
		$this->assign ( 'pType', $paymetType );

		//��������
		$suppBank = $this->getDataNameByCode ( $rows ['suppBank'] );
		$this->assign ( 'suppBank', $suppBank );

		//��ʷ�汾
		$history = $service->toViewHistory_d ( $rows ['applyNumb'] );
		$this->assign ( 'history', $history );

		//ǩԼ״̬
		$signStatus = $service->getSignStatus_d ( $rows ['signStatus'] );
		$this->assign ( 'signStatus', $signStatus );

		$this->display ( 'view2' );
	}

	/**
	 * �鿴/�༭ҳ��
	 */
	function c_init() {
		$service = $this->service;
		$returnObj = $this->objName;
		$id = $_GET ['id'];
		//���ݺ�ͬ�Ż�ñ�����ͬ������
		$rows = $service->get_d ( $id );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$equs = $service->getEquipments_d ( $rows );

		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {

			$this->assign ( "file", $this->service->getFilesByObjId ( $id, false ) );
			$this->assign ( 'list', $service->addContractEquList_s ( $equs ) );
			//��Ʊ����
			$billingType = $this->getDataNameByCode ( $rows ['billingType'] );
			$this->assign ( 'bType', $billingType );

			//��������
			$paymetType = $this->getDataNameByCode ( $rows ['paymetType'] );
			$this->assign ( 'pType', $paymetType );

			//��������
			$suppBank = $this->getDataNameByCode ( $rows ['suppBank'] );
			$this->assign ( 'suppBank', $suppBank );

			//��ǩԼ״̬��ֵ����ת��
			$signStatus = $service->signStatus_d ( $rows ['signStatus'] );
			$this->assign ( 'signStatus', $signStatus );

			$this->display ( 'viewV' );
		} else {
			$this->assign ( "file", $this->service->getFilesByObjId ( $id, true ) );
			$this->assign ( 'list', $service->editContractEquList_s ( $equs ) );
			$this->showDatadicts ( array ('billingType' => 'FPLX' ), $rows ['billingType'] );
			$this->showDatadicts ( array ('paymetType' => 'fkfs' ), $rows ['paymetType'] );

			//ǩԼ״̬
			$signStatus = $service->editSignStatus_d ( $rows ['signStatus'] );
			$this->assign ( 'signStatus', $signStatus );

			$this->display ( 'edit' );
		}
	}

	/**
	 * @description �޸ı���ĺ�ͬ
	 * @date 2011-3-2
	 */
	function c_editchange() {
		$contract = $_POST ['contract'];
		$equs = $_POST ['equs'];
		$id = $contract ['id'];
		$condiction = array ('id' => $id );

		//���º�ͬ������
		$this->service->update ( $condiction, $contract );
		//�����豸������
		$equDao = new model_purchase_change_equipmentchange ();
		foreach ( $equs as $key => $val ) {
			$condictionEqu = array ('basicId' => $id, 'id' => $val ['id'] );
			$equDao->update ( $condictionEqu, $val );
		}

		if ($_GET ['act'] == "app") {
			$phpurl = 'controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_purch_apply_basic_version&formName=�ɹ���ͬ����';
			$phpurl = str_replace ( " ", "", $phpurl );
			echo "<script>parent.location=('" . $phpurl . "');</script>";
		} else {
			//	 	 echo "<script>alert('����ɹ�');location.href='?model=purchase_change_contractchange&action=init&id=$id';</script>";
			msgGo ( '����ɹ�', "?model=purchase_change_contractchange&action=toMyChangeList" );
		}

	}

	/**
	 * @description �ҵ�����-�ɹ���ͬ����б�
	 * ��Ҫ���˳��ɵİ汾���б�ֻ��ʾ���°汾�ĺ�ͬ
	 * �ɲ鿴��ʷ����汾
	 * @author qian
	 * @date 2011-2-17 14:57
	 */
	function c_pageJsonMy() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description �鿴��ʷ�汾
	 */
	function c_pageJsonHistory() {

		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['updateId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$rows = $service->pageBySqlId ( 'select_history' );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );

	}

	/**
	 * δ�����Ĳɹ���ͬ���
	 */
	function c_pageJsonNo() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 0;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$rows = $service->pageBySqlId ( 'sql_examine' );
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription ��ȡ��ҳ����ת��JSON--������
	 * @author qian
	 * @date 2011-1-6 ����03:23:39
	 */
	function c_pageJsonYes() {
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['findInName'] = $_SESSION ['USER_ID'];
		$service->searchArr ['Flag'] = 1;
		$service->searchArr ['workFlowCode'] = $service->tbl_name;
		$service->asc = true;
		$service->groupBy = 'c.id';
		$rows = $service->pageBySqlId ( 'sql_examine2' );
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @description �鿴��ʷ�汾
	 * @author qian
	 * @date 2011-2-17 16:37
	 */
	//	function c_toViewHistory(){
	//		$service = $this->service;
	//		$applyNumb = $_GET['applyNumb'];
	//		$service->searchArr = array( "applyNumb" => $applyNumb );
	//		$rows = $service->pageBySqlId();
	//
	//		return $rows;
	//	}


	/**
	 * @description ������ͨ���Ĳɹ���ͬ������ݸ��ǵ��ɹ���ͬ����
	 * @author qian
	 * @date 2011-2-16 9:51
	 */
	function c_coverChange($id) {
		$id = $_POST ['id'];
		$service = $this->service;
		//���ݰ汾��������ͨ���ĺ�ͬID��ú�ͬ���豸������
		$rows = $service->getRows_d ( $id );

		//�Բɹ���ͬ���豸�����ݽ��и���
		$flag = $service->coverChange_d ( $rows );
		$condiction = array ("id" => $id );
		$service->updateField ( $condiction, "isChanged", "0" );
		if ($flag) {
			//      echo "<script>alert('����ɹ�');parent.parent.location = '?model=purchase_contract_purchasecontract&action=myPurchaseContractTab';</script>";
			//      echo "<script>alert('ȷ�ϳɹ�');location = '?model=purchase_change_contractchange&action=toApprovalYes';</script>";
			echo 1;
		}
	}

}
?>
