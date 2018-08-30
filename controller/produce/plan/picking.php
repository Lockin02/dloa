<?php
/**
 * @author Michael
 * @Date 2014��9��3�� 16:55:34
 * @version 1.0
 * @description:�����������뵥���Ʋ�
 */
class controller_produce_plan_picking extends controller_base_action {

	function __construct() {
		$this->objName = "picking";
		$this->objPath = "produce_plan";
		parent::__construct ();
	}

	/**
	 * ��ת�������������뵥tab
	 */
	function c_page() {
		$this->view('list-tab');
	}

	/**
	 * ��дpageJson
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = "c.id";
		$rows = $service->page_d ('select_product');
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				// �Ƿ���Դ������ֳ���
				$itemIds = $this->service->checkOutNum_d($val['id']);
				if (count($itemIds) > 0) {
					$rows[$key]['isCanOut'] = 1;
				} else {
					$rows[$key]['isCanOut'] = 0;
				}

				// �Ƿ���Խ�������
				$itemIds = $this->service->checkBackNum_d($val['id']);
				if (count($itemIds) > 0) {
					$rows[$key]['isCanBack'] = 1;
				} else {
					$rows[$key]['isCanBack'] = 0;
				}
			}
		}
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
	 * ��ת�������������뵥�б�
	 */
	function c_pageList() {
		$this->assign('finish' ,isset($_GET['pickingType']) ? 'yes' : 'no');
		$this->assign('userId' ,$_SESSION['USER_ID']);
		$this->view('list');
	}

	/**
	 * �������ƻ���ת���鿴��������tab
	 */
	function c_toPlanTab() {
		$this->assign('planId' ,$_GET['planId']);
		$this->view('plan-tab' );
	}

	/**
	 * ��ת�������ƻ��������������뵥�б�
	 */
	function c_toPlanPage() {
		$this->assign('planId' ,$_GET['planId']);
		$this->view('list-plan');
	}

	/**
	 * ��ת�������ƻ�����������������Ϣ�б�
	 */
	function c_toProductPage() {
		$this->assign('planId' ,$_GET['planId']);
		$this->view('list-product');
	}

	/**
	 * ��ת�����Ϲ���tab
	 */
	function c_pageManage() {
		$this->view('list-tab-manage');
	}

	/**
	 * ��ת�����������������б�
	 */
	function c_pageListManage() {
		$this->assign('perform' ,isset($_GET['perform']) ? 'yes' : 'no');
		$this->view('list-manage');
	}

	/**
	 * ��ת�����������������뵥ҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //��������

		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		// �������
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		$this->view ('add', true);
	}

	/**
	 * �����ƻ���ת�������������뵥ҳ��
	 */
	function c_toAddByPlan() {
		$planDao = new model_produce_plan_produceplan();
		$planObj = $planDao->get_d($_GET['planId']);
		$typeId = $this->service->get_table_fields('oa_stock_material_type' ," `code`='$planObj[productCode]' AND `deleted`='0' " ,'id');
		$this->assign('typeId' ,$typeId); //��������id
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //��������
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		// �������
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		if(strstr($_GET['planId'], ',')){// ����ƻ���
			$this->assign('planId' ,$_GET['planId']);
			$this->assign('productId' ,isset($_GET['productId']) ? $_GET['productId'] : '');
			$this->view('add-multi-plan' ,true);
		}else{
			$this->assignFunc($planObj);
			$this->assign('productId' ,isset($_GET['productId']) ? $_GET['productId'] : '');
			$this->view('add-plan' ,true);
		}
	}

	/**
	 * �����ƻ���ת�������������뵥ҳ��
	 */
	function c_toAddByPlanPlus() {
		$planDao = new model_produce_plan_produceplan();
		$planObj = $planDao->get_d($_GET['planId']);
		$typeId = $this->service->get_table_fields('oa_stock_material_type' ," `code`='$planObj[productCode]' AND `deleted`='0' " ,'id');
		$this->assign('typeId' ,$typeId); //��������id
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //��������
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		// �������
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		if(strstr($_GET['planId'], ',')){// ����ƻ���
			$this->assign('planId' ,$_GET['planId']);
			$this->assign('productId' ,isset($_GET['productId']) ? $_GET['productId'] : '');
			$this->view('add-multi-plan' ,true);
		}else{
			$this->assignFunc($planObj);
			$this->assign('productId' ,isset($_GET['productId']) ? $_GET['productId'] : '');
			$this->view('add-plan-plus' ,true);
		}
	}


	/**
	 * ԭ�ϼ�����ת�������������뵥ҳ��
	 */
	function c_toAddByMaterial() {
		$data = $_POST['data'][0];
		$bomConfigId = $data['bomConfigId']; //bom����id
//echo "<pre>";
//print_R($data);
		//bom�������ֺͱ��
		$bomName = $this->service->get_table_fields('oa_stock_material_finished' ," id='$bomConfigId' " ,'name');
		$this->assign('relDocName' ,$bomName);
		$bomcode = $this->service->get_table_fields('oa_stock_material_finished' ," id='$bomConfigId' " ,'code');
		$this->assign('relDocCode' ,$bomcode);

		unset($data['bomConfigId']);
		if (is_array($data)) {
			$productDao = new model_stock_productinfo_productinfo();
			$productObjs = array();
			$rows = array(); //�ӱ������
			foreach ($data as $key => $val) {
				if ($val > 0) {
					$tmp = array();
					if (empty($productObjs[$key])) {
						$productObj = $productDao->find(array('productCode' => $key));
						$productObjs[$key] = $productObj;
					}
					$numArr = $this->service->getProductNum_d($productObjs[$key]['productCode']);
					$tmp['JSBC']        = $numArr['JSBC']; //���豸������
					$tmp['KCSP']        = $numArr['KCSP']; //�����Ʒ������
					$tmp['SCC']         = $numArr['SCC'];  //����������
					$tmp['bomConfigId'] = $bomConfigId;
					$tmp['productId']   = $productObjs[$key]['id'];
					$tmp['productCode'] = $productObjs[$key]['productCode'];
					$tmp['productName'] = $productObjs[$key]['productName'];
					$tmp['pattern']     = $productObjs[$key]['pattern'];
					$tmp['unitName']    = $productObjs[$key]['unitName'];
					$tmp['applyNum']    = $val;
					array_push($rows ,$tmp);
				}
			}
		}
		$this->assign('productJson' ,util_jsonUtil::encode($rows));

		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //��������
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		$this->view('add-material' ,true);
	}

	/**
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit();
		$actType = isset($_GET['actType']) ? true : false;
		$type = isset($_GET['mutil']) ? true : false;
		$id = $this->service->add_d($_POST[$this->objName],$type);
		if ($id) {
			if($actType) {
				succ_show('controller/produce/plan/ewf_index.php?actTo=ewfSelect&billId='.$id);
			} else {
				msg('����ɹ�');
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * ��ת���༭�����������뵥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX') ,$obj['docTypeCode']); //��������
		$this->showDatadicts(array('module' => 'HTBK') ,$obj['module']); //�������
		if ($_GET['isCaculate']) {
			$this->view ('edit-caculate' ,true);
		} else {
			$this->view ('edit' ,true);
		}
	}

	/**
	 * �༭�������
	 */
	function c_edit() {
		$this->checkSubmit();
		$actType = isset($_GET['actType']) ? true : false;
		$id = $this->service->edit_d($_POST[$this->objName]);
		if ($id) {
			if($actType) {
				succ_show('controller/produce/plan/ewf_index.php?actTo=ewfSelect&billId='.$id);
			} else {
				msg('����ɹ�');
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * ��ת���鿴�����������뵥tabҳ��
	 */
	function c_toViewTab() {
		$this->permCheck (); //��ȫУ��
		$this->assign('id' ,$_GET['id']);
		$this->view ('view-tab' );
	}

	/**
	 * ��ת���鿴�����������뵥ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('actType' ,isset($_GET['actType']) ? 'none' : '');
		$this->view ( 'view' );
	}

	/**
	 * ����ͨ������
	 */
	function c_dealAfterAudit() {
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines'] == "ok") {  //����ͨ��
				 $this->service->dealAfterAudit_d( $folowInfo['objId'] );
			}
		}
//		echo 111;
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ����excel
	 */
	function c_excelOut() {
		set_time_limit(0);

		$obj = $this->service->get_d($_GET['id']);

		if ($obj) {
			$itemDao = new model_produce_plan_pickingitem();
			$obj['item'] = $itemDao->findAll(array('pickingId' => $_GET['id']));

			return model_produce_basic_produceExcelUtil::exportPicking($obj);
		} else {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}
	}

	/**
	 * ��ȡ��������(�����ӱ�)����json
	 */
	function c_listJsonProduct() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_product');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * �������ϱ����ȡ���豸�֡������Ʒ�ֺ�����������
	 * @return Json��ʽ��JSBC,KCSP,SCC��
	 */
	function c_getProductNum() {
		$productCode = $_POST['productCode'];
		$numArr = $this->service->getProductNum_d($productCode);
		echo util_jsonUtil::encode ( $numArr );
	}

	/**
	 * ��ת���������ֳ���ҳ��
	 */
	function c_toAddOut() {
		$this->permCheck (); //��ȫУ��
		$itemIds = $this->service->checkOutNum_d($_GET['id']);

		if (count($itemIds) > 0) {
			$obj = $this->service->get_d($_GET['id']);
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}

			$idStr = implode(',' ,$itemIds);
			$this->assign('idStr' ,$idStr);
			$this->view('add-out' ,true);
		} else {
			msg('û�����������ĵ��ݣ�');
		}
	}

	/**
	 * �������ֳ���
	 */
	function c_addOut() {
		$this->checkSubmit();
		$rs = $this->service->addOut_d($_POST[$this->objName]);
		if ($rs) {
			msg('����ɹ���');
		} else {
			msg('����ʧ�ܣ�');
		}
	}

	/**
	 * ���ϼ�����ת����ҳ��
	 */
	function c_toAddByCaculate() {
		$obj = $_POST[$this->objName];
		if (is_array($obj['item'])) {
			$productDao = new model_stock_productinfo_productinfo();
			$rows = array(); //�ӱ������
			foreach ($obj['item'] as $key => $val) {
				if ($val['isDelTag'] != 1) {
					$numArr = $this->service->getProductNum_d($val['productCode']);
					$val['applyNum'] = $val['number'];  //��������
					$val['JSBC']     = $numArr['JSBC']; //���豸������
					$val['KCSP']     = $numArr['KCSP']; //�����Ʒ������
					$val['SCC']      = $numArr['SCC'];  //����������
					array_push($rows ,$val);
				}
			}
		}
		$this->assign('productJson' ,util_jsonUtil::encode($rows));

		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //��������
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		$this->view('add-caculate' ,true);
	}

	/**
	 * ���ϼ���������
	 */
	function c_addCaculate() {
		$this->checkSubmit();
		$actType = isset($_GET['actType']) ? true : false;
		$id = $this->service->addCaculate_d($_POST[$this->objName]);
		if ($id) {
			if($actType) {
				succ_show('controller/produce/plan/ewf_index.php?actTo=ewfSelect&billId='.$id);
			} else {
				msg('����ɹ�');
				echo "<script type='text/javascript'>window.close();</script>";
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * ��ת����������ҳ��
	 */
	function c_toAddBack() {
		$this->permCheck (); //��ȫУ��
		$itemIds = $this->service->checkBackNum_d($_GET['id']);

		if (count($itemIds) > 0) {
			$obj = $this->service->get_d($_GET['id']);
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}

			$idStr = implode(',' ,$itemIds);
			$this->assign('idStr' ,$idStr);
			$this->view('add-back' ,true);
		} else {
			msg('û�����������ĵ��ݣ�');
		}
	}

	/**
	 * ��������
	 */
	function c_addBack() {
		$this->checkSubmit();
		$rs = $this->service->addBack_d($_POST[$this->objName]);
		if ($rs) {
			msg('����ɹ���');
		} else {
			msg('����ʧ�ܣ�');
		}
	}

	/**
	 * �б�߼���ѯ
	 */
	function c_toSearch() {
		$this->view('search');
	}
 }