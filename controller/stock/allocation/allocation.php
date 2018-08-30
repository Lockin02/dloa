<?php

/**
 * @author huangzf
 * @Date 2011��5��5�� 9:35:03
 * @version 1.0
 * @description:������������Ϣ���Ʋ�
 */
class controller_stock_allocation_allocation extends controller_base_action
{

	function __construct() {
		$this->objName = "allocation";
		$this->objPath = "stock_allocation";
		parent::__construct();
	}

	/**
	 * ��ת��������������Ϣ
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ͬ��������
	 */
	function c_contractPage() {
		$this->assign("contractType", $_GET['contractType']);
		$this->assign("contractId", $_GET['contractId']);
		$this->view('contract-list');
	}

	/**
	 * ��ת��Դ���������������Ϣ
	 */
	function c_relDocPage() {
		$this->assign("relDocType", $_GET['relDocType']);
		$this->assign("relDocId", $_GET['relDocId']);
		$this->assign("initTip", $_GET['initTip']);
		$this->view('reldoc-list');
	}

	/**
	 * ����������ҳ��
	 * @see controller_base_action::c_toAdd()
	 */
	function c_toAdd() {
		$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
		$this->showDatadicts(array('relDocType' => 'DBDYDLX'), null, true);
		$this->assign('auditDate', day_date);
		if($this->service->this_limit['���������Ȩ��']) {
			$this->assign("auditLimit", "1");
		} else {
			$this->assign("auditLimit", "0");
		}
		$this->view("add");
	}

	/**
	 *
	 * ���Ƶ�����
	 * @author huangzf
	 */
	function c_toBluePush() {
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
		$relDocId = isset($_GET['relDocId']) ? $_GET['relDocId'] : null;
		//echo $relDocType;
		if($this->service->this_limit['���������Ȩ��']) {
			$this->assign("auditLimit", "1");
		} else {
			$this->assign("auditLimit", "0");
		}

		$borrowDao = new model_projectmanagent_borrow_borrow();
		$otherdatasDao = new model_common_otherdatas();
		$productinfoDao = new model_stock_productinfo_productinfo();

		if("DBDYDLXFH" == $relDocType) {
			$outPlanDao = new model_stock_outplan_outplan();
			$outPlanObj = $outPlanDao->get_d($relDocId);
			$borrowObj = $borrowDao->get_d($outPlanObj['docId']);
			$outProDao = new model_stock_outplan_outplanProduct();
			$outItemArr = $outProDao->getItemByshipId_d($relDocId);
			if(!empty($outItemArr)) {
				foreach($outItemArr as &$v) {
					$rs = $productinfoDao->find(array('id' => $v['productId']), null, 'ext2');
					$v['k3Code'] = empty($rs) ? "" : $rs['ext2'];
					$v['borrowItemId'] = $v['contEquId'];
				}
			}
			$this->assign("exportStockId", $outPlanObj['stockId']);
			$this->assign("exportStockCode", $outPlanObj['stockCode']);
			$this->assign("exportStockName", $outPlanObj['stockName']);
			$this->assign("contractCode", $outPlanObj['docCode']);
			$this->assign("contractId", $outPlanObj['docId']);
			$this->assign("contractType", $outPlanObj['docType']);
			$this->assign("relDocCode", $outPlanObj['planCode']);
			$this->assign("relDocId", $outPlanObj['id']);
			$this->assign("rObjCode", "");
			$this->assign("saleAddress", $outPlanObj['address']);
			$this->assign("customerName", $outPlanObj['customerName']);
			$this->assign("customerId", $outPlanObj['customerId']);
			$this->assign("allocationItems", $outProDao->showAddList_borrow($outItemArr));

		} else if("DBDYDLXJY" == $relDocType) {
			$borrowequDao = new model_projectmanagent_borrow_borrowequ();
			$borrowObj = $borrowDao->get_d($relDocId);
			$borrowequDao->searchArr['borrowId'] = $relDocId;
			$borrowequDao->searchArr['isDel'] = "0";
			$borrowequDao->searchArr['isTemp'] = "0";
			$outItemArr = $borrowequDao->listBySqlId();
			if(!empty($outItemArr)) {
				foreach($outItemArr as &$v) {
					$rs = $productinfoDao->find(array('id' => $v['productId']), null, 'ext2');
					$v['k3Code'] = empty($rs) ? "" : $rs['ext2'];
					$v['borrowItemId'] = $v['id'];
				}
			}
			$this->assign("exportStockId", "");
			$this->assign("exportStockCode", "");
			$this->assign("exportStockName", "");
			$this->assign("contractCode", $borrowObj['Code']);
			$this->assign("contractId", $borrowObj['id']);
			$this->assign("contractType", "oa_borrow_borrow");
			$this->assign("relDocCode", $borrowObj['Code']);
			$this->assign("relDocId", $borrowObj['id']);
			$this->assign("rObjCode", $borrowObj['objCode']);
			$this->assign("saleAddress", "");
			$this->assign("customerName", $borrowObj['customerName']);
			$this->assign("customerId", $borrowObj['customerId']);
			$this->assign("allocationItems", $borrowequDao->showItemAtEdit($outItemArr));
		}

		$this->assign("relDocType", $relDocType);
		$this->assign("relDocName", "");
		$this->assign('auditDate', day_date);
		$this->assign('outStartDate', $borrowObj['beginTime']);
		$this->assign('outEndDate', $borrowObj['closeTime']);
		$this->assign("auditDate", day_date);
		$this->assign("itemscount", count($outItemArr));
		if($borrowObj['limits'] == "Ա��") {
			$this->assign("pickName", $borrowObj['createName']);
			$this->assign("pickCode", $borrowObj['createId']);
            $userInfoObj = $otherdatasDao->getUserDatas($borrowObj['createId'], array("DEPT_ID", "DEPT_NAME"));
		} else {
			$this->assign("pickName", $borrowObj['salesName']);
			$this->assign("pickCode", $borrowObj['salesNameId']);
            $userInfoObj = $otherdatasDao->getUserDatas($borrowObj['salesNameId'], array("DEPT_ID", "DEPT_NAME"));
		}
		$this->assign("remark", $borrowObj['remark']);
		$this->assign("deptName", $userInfoObj['DEPT_NAME']);
		$this->assign("deptCode", $userInfoObj['DEPT_ID']);
		$this->show->assign("relDocTypeName", $this->getDataNameByCode($relDocType));

		$this->showDatadicts(array('toUse' => 'CHUKUYT'), "CHUKUJY", true);
		$this->view("blue-push");
	}

	/**
	 * �޸ĵ�����ҳ��
	 * @see controller_base_action::c_toEdit()
	 */
	function c_toEdit() {

		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
		$allocationObj = $service->get_d($id);
		foreach($allocationObj as $key => $val) {
			if($key == 'items') {
				$this->show->assign("allocationItems", $service->showItemAtEdit($allocationObj['items']));
			} else {
				$this->show->assign($key, $val);
			}
		}
		if($this->service->this_limit['���������Ȩ��']) {
			$this->assign("auditLimit", "1");
		} else {
			$this->assign("auditLimit", "0");
		}
		//$this->showDatadicts( array('relDocType' => 'DBDYDLX' ), $allocationObj['relDocType'], true );
		$this->show->assign("relDocTypeName", $this->getDataNameByCode($allocationObj['relDocType']));
		$this->show->assign("itemscount", count($allocationObj['items']));
		$this->showDatadicts(array('toUse' => 'CHUKUYT'), $allocationObj['toUse'], true);
		$this->view("edit");
	}

	/**
	 * �鿴������ҳ��
	 * @see controller_base_action::c_toView()
	 */
	function c_toView() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
		$allocationObj = $service->get_d($id);

		//���Ƶ��� ���Ȩ��
		$allocationObj['items'] = $service->filterWithoutField('����', $allocationObj['items'], 'list', array('cost'));
		$allocationObj['items'] = $service->filterWithoutField('���', $allocationObj['items'], 'list', array('subCost'));

		foreach($allocationObj as $key => $val) {
			if($key == 'items') {
				$this->show->assign("allocationItems", $service->showItemAtView($allocationObj['items']));
			} else {
				$this->show->assign($key, $val);
			}
		}
		$this->show->assign("relDocType", $this->getDataNameByCode($allocationObj['relDocType']));
		$this->show->assign("toUse", $this->getDataNameByCode($allocationObj['toUse']));
		$this->view("view");
	}

	/**
	 * �鿴������ҳ��
	 * @see controller_base_action::c_toView()
	 */
	function c_toPrint() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
		$allocationObj = $service->get_d($id);
		foreach($allocationObj as $key => $val) {
			if($key == 'items') {
                $allocationItems = $service->showItemAtPrint($allocationObj['items']);
				$this->show->assign("allocationItems", $allocationItems);
                $this->show->assign("itemCount", count($allocationObj['items']));
			} else if($key == 'auditDate'){
                $this->show->assign($key, $val);
                $year = $sortyear = $month = $day = "";
                if($val != ""){
                    $dateArr = explode("-",$val);
                    $year = $dateArr[0];
                    $sortyear = substr($year, -2);
                    $month = $dateArr[1];
                    $day = $dateArr[2];
                }

                $this->show->assign("year", $year);
                $this->show->assign("sortyear", $sortyear);
                $this->show->assign("month", $month);
                $this->show->assign("day", $day);
            }else {
				$this->show->assign($key, $val);
			}
		}

		$this->show->assign("toUse", $this->getDataNameByCode($allocationObj['toUse']));
		$this->view("print");
	}

	/**
	 *
	 * ����������ҳ��
	 */
	function c_toCopy() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
		$allocationObj = $service->get_d($id);

		if($allocationObj['relDocId'] != "0") {
			$borroweDao = new model_projectmanagent_borrow_borrow();
			if($allocationObj['relDocType'] == "DBDYDLXFH") {
				$outplanDao = new model_stock_outplan_outplan();
				$outPlanObj = $outplanDao->get_d($allocationObj['relDocId']);
				$borrowObj = $borroweDao->get_d($outPlanObj['docId']);
			} else {
				//�жϽ�����뵥�Ƿ����
				$borrowObj = $borroweDao->get_d($allocationObj['relDocId']);

			}
			if(!is_array($borrowObj)) {
				echo "<script>alert('��Ӧ������뵥��ɾ��,����������黹������!');window.close();</script>";
			}
		}

		foreach($allocationObj as $key => $val) {
			if($key == 'items') {
				$this->assign("allocationItems", $service->showItemAtCopy($allocationObj));
			} else {
				$this->assign($key, $val);
			}
		}
		if($this->service->this_limit['���������Ȩ��']) {
			$this->assign("auditLimit", "1");
		} else {
			$this->assign("auditLimit", "0");
		}
		$this->assign('auditDate', day_date);
		$this->show->assign("relDocTypeName", $this->getDataNameByCode('DBDYDLXDB'));
		$this->assign("relDocType", "DBDYDLXDB");
		$this->assign("relDocId", $id);
		$this->assign("relDocCode", $allocationObj['docCode']);
		$this->assign("itemscount", count($allocationObj['items']));
		$this->showDatadicts(array('toUse' => 'CHUKUYT'), "CHUKUGUIH", true);
		$this->view("copy");
	}

	/**
	 *
	 * ���ݹ黹���뵥���Ƶ����黹��
	 */
	function c_toPushReturn() {
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
		$relDocId = isset($_GET['relDocId']) ? $_GET['relDocId'] : null;
		$equIdArr = isset($_GET['equIdArr']) ? $_GET['equIdArr'] : null;
		if($this->service->this_limit['���������Ȩ��']) {
			$this->assign("auditLimit", "1");
		} else {
			$this->assign("auditLimit", "0");
		}

		$borrowDao = new model_projectmanagent_borrow_borrow();
		$borrowReturnDisDao = new model_projectmanagent_borrowreturn_borrowreturnDis();
		$otherdatasDao = new model_common_otherdatas();
		$productinfoDao = new model_stock_productinfo_productinfo();

		if("DBDYDLXGH" == $relDocType) {
			// ��ȡ���е���������
			$saveAllocation = $this->service->getSaveAllocation_d($relDocId, $relDocType);
			if ($saveAllocation) {
				msg("�����Ƶ�����" . $saveAllocation . "�������ظ��ύ");
			} else {
				$borrowequDao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
				$borrowReturnDisObj = $borrowReturnDisDao->get_d($relDocId);
				$borrowObj = $borrowDao->get_d($borrowReturnDisObj['borrowId']);
				if($equIdArr != null) {
					$borrowequDao->searchArr['equIdArr'] = $equIdArr;
				}
				$borrowequDao->searchArr['disposeId'] = $relDocId;
				$borrowequDao->searchArr['isDel'] = "0";
				$borrowequDao->searchArr['isTemp'] = "0";
				$borrowequDao->searchArr['disNumSql'] = "sql: and(c.disposeNum - c.backNum != '0')";
				$outItemArr = $borrowequDao->listBySqlId();

				if(!empty($outItemArr)) {
					foreach($outItemArr as $k => $v) {
						$rs = $productinfoDao->find(array('id' => $v['productId']), null, 'ext2');
						$outItemArr[$k]['k3Code'] = empty($rs) ? "" : $rs['ext2'];
					}
				}

				$this->assign("exportStockId", "");//�ֿ���Ϣ
				$this->assign("exportStockCode", "");
				$this->assign("exportStockName", "");
				$this->assign("contractCode", $borrowObj['Code']);//�������뵥��Ϣ
				$this->assign("contractId", $borrowObj['id']);
				$this->assign("contractType", "oa_borrow_borrow");
				$this->assign("relDocCode", $borrowReturnDisObj['borrowreturnCode']);//Դ����Ź���������黹���ݱ��
				$this->assign("relDocId", $relDocId);
				$this->assign("rObjCode", "");
				$this->assign("saleAddress", "");
				$this->assign("customerName", $borrowObj['customerName']);
				$this->assign("customerId", $borrowObj['customerId']);
				$this->assign("linkmanName", "");
				$userInfoObj = $otherdatasDao->getUserDatas($borrowObj['createId'], array("DEPT_ID", "DEPT_NAME"));
				$this->assign("relDocType", $relDocType);
				$this->assign("relDocName", "");
				$this->assign('auditDate', day_date);
				$this->assign('outStartDate', $borrowObj['beginTime']);
				$this->assign('outEndDate', $borrowObj['closeTime']);
				$this->assign("auditDate", day_date);
				$this->assign("itemscount", count($outItemArr));
				$this->assign("pickName", $borrowObj['createName']);
				$this->assign("pickCode", $borrowObj['createId']);
				$this->assign("remark", $borrowObj['remark']);
				$this->assign("deptName", $userInfoObj['DEPT_NAME']);
				$this->assign("deptCode", $userInfoObj['DEPT_ID']);
				$this->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
				$this->showDatadicts(array('toUse' => 'CHUKUYT'), "CHUKUGUIH", true);
				$this->assign("allocationItems", $borrowequDao->showItemAtEdit($outItemArr));//�ӱ��嵥

				/**
				 * �ʼ첿����Ⱦ
				 */
				$borrowReturnDao = new model_projectmanagent_borrowreturn_borrowreturn();
				$borrowReturnObj = $borrowReturnDao->get_d($borrowReturnDisObj['borrowreturnId']);
				$this->assign('qualityObjId', $borrowReturnObj['id']);
				$this->assign('qualityObjType', 'ZJSQYDGH');
			}
		} else {
			echo "<script>alert('Դ����������!')</script>";
		}
		$this->view("return" ,true);
	}

	/**
	 *�߼�����ҳ��
	 */
	function c_toAdvancedSearch() {
		$this->view("search-advanced");
	}

	/**
	 * �鿴���к���ϸҳ��
	 */
	function c_toViewSerialno() {
		$serialnoNameStr = isset($_GET['serialnoName']) ? $_GET['serialnoName'] : null;
		$this->assign("serialnoList", $this->service->showSerialno($serialnoNameStr));
		$this->view("serialno-view");
	}

	/**
	 * ����δ�黹������EXCEL
	 */
	function c_exportExcel() {
		$service = $this->service;
		$dataArr = $service->getNotBackWithRelDoc();
		$dao = new model_stock_productinfo_importProductUtil();
		return $dao->exportNotAllBackExcel($dataArr);
	}

	/**
	 * ����������
	 * @author huangzf
	 */
	function c_add() {
        $this->checkSubmit();
		try {
			$service = $this->service;
			$allocationObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
			/*s:--------------���Ȩ�޿���----------------*/
			if("audit" == $actType) {
				if(!$service->this_limit['���������Ȩ��']) {
					echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
					exit();
				}
				$allocationObject['auditerName'] = $_SESSION['USERNAME'];
				$allocationObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------���Ȩ�޿���----------------*/
			$id = $service->add_d($allocationObject);

			if($id) {
				if("audit" == $actType) {
					$addObj = $service->find(array("id" => $id), null, 'docCode');
					echo "<script>alert('��˵������ɹ�!���ݱ��Ϊ:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('�����������ɹ�!'); window.opener.window.show_page();window.close();  </script>";
				}
			}

			//			else {
			//				if("audit" == $actType) {
			//					echo "<script>alert('ȷ�ϵ���ʧ��,��ȷ�ϵ����ֿ��Ƿ����㹻���,����ֿ��Ƿ��ʼ�������ϣ�'); window.opener.window.show_page();window.close();  </script>";
			//				} else {
			//					echo "<script>alert('ȷ�ϵ���ʧ��,��ȷ�ϵ����ֿ��Ƿ����㹻���,����ֿ��Ƿ��ʼ�������ϣ�'); window.opener.window.show_page();window.close();  </script>";
			//				}
			//			}
		} catch(Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * �޸ĵ�����
	 * @author huangzf
	 */
	function c_edit() {
		try {
			$service = $this->service;
			$allocationObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
			/*s:--------------���Ȩ�޿���----------------*/
			if("audit" == $actType) {
				if(!$service->this_limit['���������Ȩ��']) {
					echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
					exit();
				}
				$allocationObject['auditerName'] = $_SESSION['USERNAME'];
				$allocationObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------���Ȩ�޿���----------------*/
			//��ֹ�����ظ����
			$lastObj = $service->find(array("id" => $allocationObject['id']));
			if($lastObj['docStatus'] == "YSH") {
				echo "<script>alert('�����Ѿ����,�����޸�,��ˢ���б�!');window.close();</script>";
				exit();
			}

			if($service->edit_d($allocationObject)) {
				if("audit" == $actType) {
					echo "<script>alert('��˵������ɹ������ݱ��Ϊ:" . $lastObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('�޸ĵ������ɹ�!'); window.opener.window.show_page();window.close();  </script>";
				}
			}

			//		 else {
			//			if("audit" == $actType) {
			//				echo "<script>alert('ȷ�ϵ���ʧ��,��ȷ�ϵ����ֿ��Ƿ����㹻���,����ֿ��Ƿ��ʼ�������ϣ�'); window.opener.window.show_page();window.close();  </script>";
			//			} else {
			//				echo "<script>alert('�޸ĵ�����ʧ��,��ȷ�ϵ�����Ϣ�Ƿ�����!'); window.opener.window.show_page();window.close();  </script>";
			//			}
			//		}
		} catch(Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * �����������
	 */
	function c_cancelAudit() {
		try {
			$service = $this->service;
			$id = isset($_POST['id']) ? $_POST['id'] : null;
			if($service->cancelAudit($id)) {
				echo 1;
			}

			//		else
			//			echo 0;
		} catch(Exception $e) {
			echo "����ʧ��!�쳣:" . $e->getMessage();
		}
	}

	/**
	 * �����������Ȩ���ж�
	 */
	function c_cancelAuditLimit() {
		if($this->service->this_limit['�����������Ȩ��']) {
			echo 1;
		} else
			echo 0;
	}

	/**
	 * ��ȡ�������б�ҳ������Json
	 */
	function c_pageListGridJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->page_d("select_listgrid");
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count ? $service->count :($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 *
	 * ��ȡԴ�������������json����
	 */
	function c_pageRelDocJson() {
		$service = $this->service;
		$relDocId = isset($_POST['relDocId']) ? $_POST['relDocId'] : "";
		$relDocType = isset($_POST['relDocType']) ? $_POST['relDocType'] : "";
		$service->getParam($_POST);

		//����Դ�����Ͳ�ѯֱ�ӹ���������
		$service->searchArr['relDocId'] = $relDocId;
		$service->searchArr['relDocType'] = $relDocType;
		$jyrows = $service->listBySqlId("select_listgrid");

		if($relDocType == "DBDYDLXJY") { //�����õ���Ҫ��ѯ�����䷢���ƻ����е�����
			$outplanDao = new model_stock_outplan_outplan();
			$outPlanIdArr = $outplanDao->getPlanByOrderId($relDocId, "oa_borrow_borrow");

			unset($service->searchArr['relDocId']);
			$service->searchArr['relDocIdArr'] = $outPlanIdArr;
			$service->searchArr['relDocType'] = "DBDYDLXFH";
			$fhrows = $service->listBySqlId("select_listgrid");
			if(is_array($jyrows)) {
				foreach($fhrows as $value) {
					array_push($jyrows, $value);
				}
				$rows = $jyrows;
			} else {
				$rows = $fhrows;
			}
		} else {
			$rows = $jyrows;
		}

		if($_POST['initTip'] == "0") { //��������������黹��Ϣ
			$jcRelDocIdArr = array();
			foreach($rows as $value) {
				array_push($jcRelDocIdArr, $value['id']);
			}
			$service->searchArr['relDocType'] = 'DBDYDLXDB';
			if(count($jcRelDocIdArr) > 0) {
				$service->searchArr['relDocIdArr'] = $jcRelDocIdArr;
			}
		} else {
			//�������������黹��Ϣ
			$borrowRefAllocatDao = new model_projectmanagent_borrow_borrowRefAllocat();
			$idArr = $borrowRefAllocatDao->getAllocatIdArr($_POST['relDocId']);
			unset($service->searchArr['relDocIdArr']);
			unset($service->searchArr['relDocType']);
			$service->searchArr = array("idArr" => $idArr);
		}
		$backArr = $service->listBySqlId("select_listgrid");

		if(is_array($backArr)) {
			foreach($backArr as $value) {
				array_push($rows, $value);
			}
		}
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 *
	 * ��ת����ʼ��������ҳ��
	 */
	function c_toLendInit() {
		$this->view("lendinit");
	}

	/**
	 *
	 * ��ʼ�����������
	 */
	function c_initAllLend() {
		$allocationObj = $this->service->initialLendAllocation();
		try {
			if($this->service->add_d($allocationObj)) {
				echo "�ɹ�!";
			} else {
				echo "ʧ��!";
			}
		} catch(Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.close();</script>";
		}
	}

	/**
	 *
	 * ��ȡ���������������嵥���϶�Ӧ��δִ������
	 */
	function c_findRelDocNotExeNum() {
		//$stockoutStrategy = $this->$service->stockoutStrategyArr[$_POST['relDocType']];
		//      $relDocDao = new $stockoutStrategy();
		if("DBDYDLXJY" == $_POST['relDocType']) {
			$relDocDao = new model_projectmanagent_borrow_borrow();
		} else if("DBDYDLXGH" == $_POST['relDocType']) {
			$relDocDao = new model_projectmanagent_borrowreturn_borrowreturnDis();
		} else {
			$relDocDao = new model_stock_outplan_outplan();
		}
		echo $relDocDao->getDocNotExeNum($_POST['relDocId'], $_POST['relDocItemId']);
	}

	/**
	 *
	 * Ĩƽ����ָ��������
	 */
	function c_initLendStock() {
		$allocationObj = $this->service->allocatLendStock();
		//		print_r($allocationObj);
		try {
			if($this->service->add_d($allocationObj)) {
				echo "�ɹ�!";
			} else {
				echo "ʧ��!";
			}
		} catch(Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.close();</script>";
		}
	}
}