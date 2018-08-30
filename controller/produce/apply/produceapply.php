<?php
/**
 * @author huangzf
 * @Date 2012��5��11�� ������ 13:40:44
 * @version 1.0
 * @description:�������뵥���Ʋ�
 */
class controller_produce_apply_produceapply extends controller_base_action {

	function __construct() {
		$this->objName = "produceapply";
		$this->objPath = "produce_apply";
		parent::__construct ();
	}

	/**
	 * ��ת���������뵥�б�
	 */
	function c_toPageTab() {
		$this->view ( 'list-tab' );
	}

	/**
	 * ��ת���������뵥�б�
	 */
	function c_page() {
		$applyType = isset ( $_GET ['applyType'] ) ? $_GET ['applyType'] : "";
		$this->assign ( "applyType", $applyType );
		$this->view ( 'list' );
	}

	/**
	 * ��д��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		if($_REQUEST['relDocTypeCode'] == 'allContract'){
			$relDocTypeCode = $_REQUEST['relDocTypeCode'];
			unset($_REQUEST['relDocTypeCode']);
		}
		$service->getParam ( $_REQUEST );
		if(isset($relDocTypeCode)){// ��ͬ����Ϊ���к�ͬ
			$service->searchArr['relDocTypeCodeCondition'] = "sql: and relDocTypeCode in('HTLX-XSHT','HTLX-FWHT','HTLX-ZLHT','HTLX-YFHT')";
		}
		$service->groupBy = 'c.id';
		$rows = $service->page_d('select_page');

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �����ҳ����ת��Json
	 */
	function c_pageJsonNeed() {
		$service = $this->service;
		if($_REQUEST['relDocTypeCode'] == 'allContract'){
			$relDocTypeCode = $_REQUEST['relDocTypeCode'];
			unset($_REQUEST['relDocTypeCode']);
		}
		$service->getParam ( $_REQUEST );
		if(isset($relDocTypeCode)){// ��ͬ����Ϊ���к�ͬ
			$service->searchArr['relDocTypeCodeCondition'] = "sql: and relDocTypeCode in('HTLX-XSHT','HTLX-FWHT','HTLX-ZLHT','HTLX-YFHT')";
		}
		$service->groupBy = 'c.id';
		$rows = $service->page_d('select_need');

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���ҵ��������뵥�б�
	 */
	function c_mypage() {
		$this->assign ( "userId", $_SESSION ['USER_ID'] );
		$this->view ( 'mylist' );
	}

	/**
	 * ��ת����������tab
	 */
	function c_needTab() {
		$this->view ( 'need-tab' );
	}

	/**
	 * ��ת�����������б������δ��ɣ�
	 */
	function c_needPage() {
		$this->assign('issued' ,isset($_GET['issued']) ? 'yes' : 'no');
		$this->view ( 'list-need' );
	}

	/**
	 * ��ת�����������б��´���δ�´
	 */
	function c_taskPage() {
		$this->assign('issued' ,isset($_GET['issued']) ? 'yes' : 'no');
		$this->view ( 'list-task' );
	}

	/**
	 * ��ת��������������б�
	 */
	function c_productPage() {
		$this->view ( 'list-product' );
	}

	/**
	 * ��ת����������tab
	 */
	function c_weekTab() {
		$this->view ( 'week-tab' );
	}

	/**
	 * ��ת�������ƻ��б�
	 */
	function c_weekPage() {
		$weekDate = $this->service->getWeekDate_d();
		$this->assign('startWeekDate' ,$weekDate['startDate']);
		$this->assign('endWeekDate' ,$weekDate['endDate']);
		$this->view ( 'list-week' );
	}


	/**
	 * ��Դ���鿴���´���������뵥
	 */
	function c_toPageFromDoc() {
		$this->assign ( "relDocId", isset($_GET ['relDocId'])?$_GET ['relDocId']:-1 );
		$this->assign ( "relDocType", isset($_GET ['relDocType'])?$_GET ['relDocType']:-1  );
		$this->view ( 'relDoc-list' );
	}

	/**
	 * ��ת���´���������ҳ��
	 */
	function c_toApply() {
		$relDocId = isset ( $_GET ['relDocId'] ) ? $_GET ['relDocId'] : null;
		$equIds = isset ( $_GET ['equIds'] ) ? $_GET ['equIds'] : null;

		$contractDao = new model_contract_contract_contract ();
		$obj = $contractDao->getContractInfo ( $relDocId, array ("equ" ) );
		//��ȡ���п�ִ��id
		$objEquIds = '';
		$equDao = new model_contract_contract_equ();
		if (is_array($obj['equ'])) {
			foreach ($obj['equ'] as $key => $val) {
				$equObj = $equDao->get_d( $val['id'] );
				if ($equObj['number'] - $equObj['issuedProNum'] > 0) {
					$objEquIds .= $val['id'] . ',';
				}
			}
		}

		//��ѡ�е�id�й��˳���ִ�е�id
		$equIdsStr = '';
		if ($equIds) {
			$equIdsArr = explode(',' ,$equIds);
			if (is_array($equIdsArr)) {
				foreach ($equIdsArr as $key => $val) {
					$equObj = $equDao->get_d( $val );
					if ($equObj['number'] - $equObj['issuedProNum'] > 0) {
						$equIdsStr .= $val . ',';
					}
				}
			}
		}

		$ids = $equIds ? $equIdsStr : $objEquIds;
		if (!$ids) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�п�ִ�м�¼!');window.close();"
				 ."</script>";
			exit();
		}

		$this->assignFunc($obj);

		$this->assign("applyUserName" ,$_SESSION['USERNAME']); //�µ���
		$this->assign("applyUserId" ,$_SESSION['USER_ID']); //�µ���ID
		$this->assign("applyDate" ,date("Y-m-d")); //�µ�����

		$this->assign("equIds" ,substr($ids ,0 ,-1));
		$this->view('apply' ,true);
	}

	/**
	 * ��дadd
	 */
	function c_add() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$rs = $this->service->add_d( $obj );
		if($rs) {
			msg( '�´�ɹ���' );
		} else {
			msg( '�´�ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת�������������뵥ҳ��
	 */
	function c_toAdd() {
		$this->assign("applyDate" ,date("Y-m-d"));
		$this->assign("applyUserCode" ,$_SESSION['USER_ID']);
		$this->assign("applyUserName" ,$_SESSION['USERNAME']);
		$this->view('add' ,true);
	}

	/**
	 * ��ת���������������������뵥ҳ��
	 */
	function c_toAddDepartment() {
		$this->showDatadicts(array("relDocTypeCode" => 'SCYDLX')); //Դ������
		$this->assign("applyDate" ,date("Y-m-d"));
		$this->assign("applyUserId" ,$_SESSION['USER_ID']);
		$this->assign("applyUserName" ,$_SESSION['USERNAME']);
		$this->view('add-department' ,true);
	}

	/**
	 * �������������������뵥
	 */
	function c_addDepartment() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$obj = $_POST[$this->objName];
		$id = $this->service->addDepartment_d( $obj );
		if($id) {
			if($actType) {
				succ_show('controller/produce/apply/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_SESSION["DEPT_ID"]);
			} else {
				msg( '����ɹ���' );
			}
		} else {
			if($actType) {
				msg( '�ύʧ�ܣ�' );
			} else {
				msg( '����ʧ�ܣ�' );
			}
		}
	}

	/**
	 * ��ת���༭�������뵥ҳ��
	 */
	function c_toEdit() {
		$obj = $this->service->get_d( $_GET ['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign("applyDate" ,date("Y-m-d"));

		if (isset($_GET["department"])) { //�������ŵı༭
			$this->showDatadicts(array("relDocTypeCode" => 'SCYDLX') ,$obj["relDocTypeCode"]); //Դ������
			$this->view('edit-department' ,true);
		} else {
			$this->view('edit' ,true);
		}
	}

	/**
	 * ��дedit
	 */
	function c_edit() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$rs = $this->service->edit_d( $obj );
		if($rs) {
			msg( '�´�ɹ���' );
		} else {
			msg( '�´�ʧ�ܣ�' );
		}
	}

	/**
	 * �������ű༭�������뵥
	 */
	function c_editDepartment() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$obj = $_POST[$this->objName];
		$id = $this->service->editDepartment_d( $obj );
		if($id) {
			if($actType) {
				succ_show('controller/produce/apply/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_SESSION["DEPT_ID"]);
			} else {
				msg( '����ɹ���' );
			}
		} else {
			if($actType) {
				msg( '�ύʧ�ܣ�' );
			} else {
				msg( '����ʧ�ܣ�' );
			}
		}
	}

	/**
	 * ��ת���鿴�������뵥ҳ��
	 */
	function c_toView() {
		$service = $this->service;

		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign('showRelDoc' ,$service->getShowRelDoc_d($obj['relDocTypeCode'])); //��ʾ��ͬ����Դ��
		$this->assign("actType" ,isset($_GET["actType"]) ? 'none' : ''); //����ҳ�����ذ�ť

		$contractDao = new model_contract_contract_contract();
		$contractObj = $contractDao->get_d($obj['relDocId']);
//		echo "<pre>";
//		print_r($contractObj) ;
		$this->assign('contractCreateId' ,$contractObj['createId']);
		$this->assign('areaPrincipalId' ,$contractObj['areaPrincipalId']);
		$this->view ( 'view' );
	}

	/**
	 * ��ת���鿴�������뵥ҳ��
	 */
	function c_toViewTab() {
		$this->assign("id" ,$_GET ['id']);
		if (isset($_GET["noSee"])) { //�Ƿ��в鿴����ͼƻ���Ȩ��
			$this->view ('view-tab-no');
		} else {
			$this->view ('view-tab');
		}
	}

	/**
	 * ��ת������������뵥ҳ��
	 */
	function c_toChange() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		if (isset($_GET["department"])) { //�������ŵı��
			$this->showDatadicts(array("relDocTypeCode" => 'SCYDLX') ,$obj["relDocTypeCode"]); //Դ������
			$this->view('change-department' ,true);
		} else {
			$this->view('change' ,true);
		}
	}

	/**
	 * ��������������뵥
	 */
	function c_change() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		try {
			$object = $_POST[$this->objName];
			$id = $this->service->change_d($object);
			succ_show('controller/produce/apply/ewf_change.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_SESSION["DEPT_ID"]);
		} catch (Exception $e) {
			msgBack2("���ʧ�ܣ�ʧ��ԭ��".$e->getMessage());
		}
	}

	/**
	 * ����鿴tab
	 */
	function c_toChangeTab(){
		$this->permCheck (); //��ȫУ��
		$newId = $_GET['id'];
		$this->assign('id' ,$newId);

		$obj = $this->service->find(array('id' => $newId ) ,null ,'originalId');
		$this->assign('originalId' ,$obj['originalId']);

		$this->assign("actType" ,isset($_GET["actType"]) ? 'true' : 'false'); //����ҳ�����ذ�ť
		$this->display('change-tab');
	}

	/**
	 * �鿴(�������-ԭ����)����
	 */
	function c_toViewChange(){
		$id = $_GET['id'];
		$obj = $this->service->get_d( $id );
		$this->assignFunc($obj);

		$this->assign("actType" ,$_GET["actType"] == 'true' ? 'none' : ''); //����ҳ�����ذ�ť

		$contractDao = new model_contract_contract_contract();
		$contractObj = $contractDao->get_d($obj['relDocId']);
		$this->assign('contractCreateId' ,$contractObj['createId']);
		$this->assign('areaPrincipalId' ,$contractObj['areaPrincipalId']);

		$this->view('view-change');
	}

	/**
	 * �ر���������
	 */
	function c_closedApply() {
		$service = $this->service;
		if ($service->update ( array ("id" => $_POST ['id'] ), array ("docStatus" => "3" ) )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * ������������
	 */
	function c_openApply() {
		$service = $this->service;
		if ($service->openApply ( $_POST ['id'] )) {
			echo 0;
		} else {
			echo 1;
		}
	}

	/**
	 * ��ת������������뵥ҳ��
	 */
	function c_toBack() {
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign("backUserId" ,$_SESSION['USER_ID']);
		$this->assign("backUser" ,$_SESSION['USERNAME']);
		$this->assign("backDate" , date("Y-m-d"));
		$this->view ("back" ,true);
	}

	/**
	 * ���
	 */
	function c_back() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$rs = $this->service->back_d($obj);
		if ($rs) {
			msg('��سɹ���');
		} else {
			msg('���ʧ�ܣ�');
		}
	}

	/**
	 * ��ת���鿴�����������ԭ��ҳ��
	 */
	function c_toViewBack() {
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign('backReason' ,addslashes($obj['backReason'])); //���ַ��������Ž���ת�壬��ֹǰ�˶�ȡ���ݴ���

		$this->view("view-back");
	}

	/**
	 * ��ת���ر��������뵥ҳ��
	 */
	function c_toClose() {
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign("closeUserId" ,$_SESSION['USER_ID']);
		$this->assign("closeUser" ,$_SESSION['USERNAME']);
		$this->assign("closeDate" ,date("Y-m-d"));
		$this->view ("close" ,true);
	}

	/**
	 * �ر�
	 */
	function c_close() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if ($this->service->close_d($_POST[$this->objName])) {
			msg('�رճɹ���');
		} else {
			msg('�ر�ʧ�ܣ�');
		}
	}

	/**
	 * ��ת���鿴�ر���������ԭ��ҳ��
	 */
	function c_toViewClose() {
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}

		$this->view("view-close");
	}

	/**
	 * ��������
	 */
	function c_dealAfterAudit() {
		if (!empty($_GET['spid'])) {
			$otherdatas = new model_common_otherdatas();
			$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
			if($folowInfo['examines'] == "ok") {  //����ͨ��
				$this->service->dealAfterAudit_d($folowInfo['objId']);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ���������ɺ�����
	 */
	function c_dealAfterAuditChange(){
		$otherdatas = new model_common_otherdatas();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
		$objId = $folowInfo['objId'];
		$userId = $folowInfo['Enter_user'];
		$this->service->dealAfterAuditChange_d($objId ,$userId);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * ������������
	 */
	function c_toSendplanReport(){
		// $object=isset($_POST['apply'])?$_POST['apply']:"";
		$beginDate=isset($_GET['beginDate'])?$_GET['beginDate']:"";
		if($beginDate){//�ж��Ƿ����ѯ����
			// foreach($object as $key=>$val){
			// 	$logic.=$val['logic'].",";
			// 	$field.=$val['field'].",";
			// 	$relation.=$val['relation'].",";
			// 	$values.=$val['values'].",";
			// }
		 // 	$this->assign("logic",$logic);
		 // 	$this->assign("field",$field);
		 // 	$this->assign("relation",$relation);
		 // 	$this->assign("values",$values);
		 	$this->assign("beginDate",$beginDate);
		}else{
		 	$beginDate=date("Y-m")."-01";
		 	$this->assign("beginDate",$beginDate);
		}
		$this->display('sendplan');
	}

	/**
	 * ��ת���鿴���ϻ���������Ϣҳ��
	 */
	function c_toViewProduct() {
		$productDao = new model_stock_productinfo_productinfo();
		$productObj = $productDao->get_d($_GET['productId']);
		$this->assignFunc($productObj);
		$this->view('view-product');
	}

	/**
	 * ���ϻ�������鿴ҳ��Json
	 */
	function c_productListJson() {
		$service = $this->service;
		$service->searchArr['productId'] = $_POST['productId'];
		$service->groupBy = 'c.id';
		// $service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_product');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ת���������ҳ��
	 */
	function c_toStatisticsProduct() {
		$code = isset($_GET['code']) ? $_GET['code'] : '';
		if (empty($code)) {
			msg('�ò�Ʒ�����ڣ�'); //�˳�
			echo "<script>window.close();</script>";
			return 0;
		}

		$productDao = new model_stock_productinfo_productinfo();
		$productObj = $productDao->find(array('productCode' => $code));
		if (empty($productObj)) {
			msg('�ò�Ʒ�����ڣ�'); //�˳�
			echo "<script>window.close();</script>";
			return 0;
		}
		$this->assignFunc($productObj);

		$typeId = $this->service->get_table_fields('oa_stock_material_type' ," `code`='$code' AND `deleted`='0' " ,'id');
		if (empty($typeId)) {
			msg('�ò�Ʒδ����BOM����'); //�˳�
			echo "<script>window.close();</script>";
		}
		$this->assign('typeId' ,$typeId);

		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$inventory = $inventoryDao->getExeNumsByStockType($productObj['id']); //�������
		$this->assign('inventory' ,$inventory);

		$this->assign('code' ,$code);
		$this->assign('num' ,$_GET['num'] ? $_GET['num'] : 1); //����
		$this->view('statistics-product');
	}

	/**
	 * ������ɲ鿴ҳ��Json
	 */
	function c_statisticsListJson() {
		$service = $this->service;
		$SQL = "SELECT * FROM oa_stock_material_semiFinished WHERE parentId='$_POST[typeId]' AND `deleted`='0' ORDER BY id ASC";
		$rows = $service->findSql($SQL);
		if (is_array($rows)) {
			$num = $_POST['num'] > 0 ? $_POST['num'] : 1; //������ϵ
			$productDao = new model_stock_productinfo_productinfo();
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			$pickDao = new model_produce_plan_picking();
			foreach ($rows as $key => $val) {
				if ($val['name'] == '���' || $val['name'] == '�ṹ��') {
					$rows[$key]['pattern']       = ''; //����ͺ�
					$rows[$key]['unitName']      = ''; //��λ
					$rows[$key]['num']           = $num; //��Ʒ�����Ʒ�Ĺ�ϵ�����趨Ϊһ��һ
					$rows[$key]['inventory']     = '';
					$rows[$key]['onwayAmount']   = '';
					$rows[$key]['simplifiedNum'] = '';
					$rows[$key]['isChildren']    = '1';
				} else {
					$productObj = $productDao->find(array('productCode' => $val['code']));
					$rows[$key]['pattern']  = $productObj['pattern']; //����ͺ�
					$rows[$key]['unitName'] = $productObj['unitName']; //��λ
					$rows[$key]['num']      = $num; //��Ʒ�����Ʒ�Ĺ�ϵ�����趨Ϊһ��һ
					$rows[$key]['inventory']   = $inventoryDao->getExeNumsByStockType($productObj['id']); //�������
					$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $productObj['id'])); //��;����
					$numArr = $pickDao->getProductNum_d($val['productCode']);
					$rows[$key]['JSBC'] = $numArr['JSBC']; //���豸������
					$rows[$key]['KCSP'] = $numArr['KCSP']; //�����Ʒ������
					$rows[$key]['SCC']  = $numArr['SCC']; //����������
					// ������=������-��棨�����Ʒ��/�ɿ��/�����֣���-��������
					$rows[$key]['simplifiedNum'] = $num - $numArr['JSBC'] - $numArr['KCSP'] - $numArr['SCC'] - $rows[$key]['onwayAmount']; // ������
					$rows[$key]['isChildren'] = '0';
				}
			}
		}

		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ������ṹ����ɲ鿴ҳ��Json
	 */
	function c_childrenListJson() {
		$service = $this->service;
		$materialDao = new model_stock_material_material();
		$rows = $materialDao->loadParts($_POST['parentId']);
		$showNum = $_POST['showNum'] ? true : false; //�Ƿ���ʾ���豸�������������Ʒ������������������
		if (is_array($rows)) {
			$num = $_POST['num'] > 0 ? $_POST['num'] : 1; //������ϵ
			$productDao = new model_stock_productinfo_productinfo();
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			$pickDao = new model_produce_plan_picking();
			foreach ($rows as $key => $val) {
				$productObj = $productDao->find(array('productCode' => $val['code']));
				$rows[$key]['productId']   = $productObj['id']; //id
				$rows[$key]['productName'] = $productObj['productName']; //����
				$rows[$key]['productCode'] = $productObj['productCode']; //����
				$rows[$key]['pattern']     = $productObj['pattern']; //����ͺ�
				$rows[$key]['unitName']    = $productObj['unitName']; //��λ
				$rows[$key]['num']         = $val['total'] * $num; //��������
				$rows[$key]['inventory']   = $inventoryDao->getExeNumsByStockType($productObj['id']); //�������
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $productObj['id'])); //��;����
				if ($showNum) {
					$numArr = $pickDao->getProductNum_d($val['code']);
					$rows[$key]['JSBC'] = $numArr['JSBC']; //���豸������
					$rows[$key]['KCSP'] = $numArr['KCSP']; //�����Ʒ������
					$rows[$key]['SCC']  = $numArr['SCC']; //����������
				} else {
					$rows[$key]['JSBC'] = 0; //���豸������
					$rows[$key]['KCSP'] = 0; //�����Ʒ������
					$rows[$key]['SCC']  = 0; //����������
				}
				// ������=������-��棨�����Ʒ��/�ɿ��/�����֣���-��������
				$rows[$key]['simplifiedNum'] = $num - $numArr['JSBC'] - $numArr['KCSP'] - $numArr['SCC'] - $rows[$key]['onwayAmount']; // ������
			}
		}

		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ת���༭���ִ�ص��������뵥ҳ��
	 */
	function c_toEditBack() {
		$obj = $this->service->get_d( $_GET ['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}

		$this->view('edit-back' ,true);
	}

	/**
	 * �༭���ִ��
	 */
	function c_editBack() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$rs = $this->service->editBack_d( $obj );
		if($rs) {
			msg( '�´�ɹ���' );
		} else {
			msg( '�´�ʧ�ܣ�' );
		}
	}

	/**
	 * �´�����ʱ��֤
	 */
	function c_taskCheck() {
		echo util_jsonUtil::encode($this->service->taskCheck_d($_POST['id'],isset($_POST['itemIds']) ? $_POST['itemIds'] : null));
	}
}