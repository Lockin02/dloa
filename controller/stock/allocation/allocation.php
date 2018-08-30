<?php

/**
 * @author huangzf
 * @Date 2011年5月5日 9:35:03
 * @version 1.0
 * @description:调拨单基本信息控制层
 */
class controller_stock_allocation_allocation extends controller_base_action
{

	function __construct() {
		$this->objName = "allocation";
		$this->objPath = "stock_allocation";
		parent::__construct();
	}

	/**
	 * 跳转到调拨单基本信息
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 合同关联单据
	 */
	function c_contractPage() {
		$this->assign("contractType", $_GET['contractType']);
		$this->assign("contractId", $_GET['contractId']);
		$this->view('contract-list');
	}

	/**
	 * 跳转到源单相关联调拨单信息
	 */
	function c_relDocPage() {
		$this->assign("relDocType", $_GET['relDocType']);
		$this->assign("relDocId", $_GET['relDocId']);
		$this->assign("initTip", $_GET['initTip']);
		$this->view('reldoc-list');
	}

	/**
	 * 新增调拨单页面
	 * @see controller_base_action::c_toAdd()
	 */
	function c_toAdd() {
		$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
		$this->showDatadicts(array('relDocType' => 'DBDYDLX'), null, true);
		$this->assign('auditDate', day_date);
		if($this->service->this_limit['调拨单审核权限']) {
			$this->assign("auditLimit", "1");
		} else {
			$this->assign("auditLimit", "0");
		}
		$this->view("add");
	}

	/**
	 *
	 * 下推调拨单
	 * @author huangzf
	 */
	function c_toBluePush() {
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
		$relDocId = isset($_GET['relDocId']) ? $_GET['relDocId'] : null;
		//echo $relDocType;
		if($this->service->this_limit['调拨单审核权限']) {
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
		if($borrowObj['limits'] == "员工") {
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
	 * 修改调拨单页面
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
		if($this->service->this_limit['调拨单审核权限']) {
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
	 * 查看调拨单页面
	 * @see controller_base_action::c_toView()
	 */
	function c_toView() {
		$this->permCheck();
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;
		$allocationObj = $service->get_d($id);

		//控制单价 金额权限
		$allocationObj['items'] = $service->filterWithoutField('单价', $allocationObj['items'], 'list', array('cost'));
		$allocationObj['items'] = $service->filterWithoutField('金额', $allocationObj['items'], 'list', array('subCost'));

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
	 * 查看调拨单页面
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
	 * 调拨单复制页面
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
				//判断借出申请单是否存在
				$borrowObj = $borroweDao->get_d($allocationObj['relDocId']);

			}
			if(!is_array($borrowObj)) {
				echo "<script>alert('对应借出申请单已删除,请独立新增归还调拨单!');window.close();</script>";
			}
		}

		foreach($allocationObj as $key => $val) {
			if($key == 'items') {
				$this->assign("allocationItems", $service->showItemAtCopy($allocationObj));
			} else {
				$this->assign($key, $val);
			}
		}
		if($this->service->this_limit['调拨单审核权限']) {
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
	 * 根据归还申请单下推调拨归还单
	 */
	function c_toPushReturn() {
		$relDocType = isset($_GET['relDocType']) ? $_GET['relDocType'] : null;
		$relDocId = isset($_GET['relDocId']) ? $_GET['relDocId'] : null;
		$equIdArr = isset($_GET['equIdArr']) ? $_GET['equIdArr'] : null;
		if($this->service->this_limit['调拨单审核权限']) {
			$this->assign("auditLimit", "1");
		} else {
			$this->assign("auditLimit", "0");
		}

		$borrowDao = new model_projectmanagent_borrow_borrow();
		$borrowReturnDisDao = new model_projectmanagent_borrowreturn_borrowreturnDis();
		$otherdatasDao = new model_common_otherdatas();
		$productinfoDao = new model_stock_productinfo_productinfo();

		if("DBDYDLXGH" == $relDocType) {
			// 获取已有调拨单数量
			$saveAllocation = $this->service->getSaveAllocation_d($relDocId, $relDocType);
			if ($saveAllocation) {
				msg("已下推调拨单" . $saveAllocation . "，请勿重复提交");
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

				$this->assign("exportStockId", "");//仓库信息
				$this->assign("exportStockCode", "");
				$this->assign("exportStockName", "");
				$this->assign("contractCode", $borrowObj['Code']);//借用申请单信息
				$this->assign("contractId", $borrowObj['id']);
				$this->assign("contractType", "oa_borrow_borrow");
				$this->assign("relDocCode", $borrowReturnDisObj['borrowreturnCode']);//源单编号关联到申请归还单据编号
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
				$this->assign("allocationItems", $borrowequDao->showItemAtEdit($outItemArr));//从表清单

				/**
				 * 质检部分渲染
				 */
				$borrowReturnDao = new model_projectmanagent_borrowreturn_borrowreturn();
				$borrowReturnObj = $borrowReturnDao->get_d($borrowReturnDisObj['borrowreturnId']);
				$this->assign('qualityObjId', $borrowReturnObj['id']);
				$this->assign('qualityObjType', 'ZJSQYDGH');
			}
		} else {
			echo "<script>alert('源单类型有误!')</script>";
		}
		$this->view("return" ,true);
	}

	/**
	 *高级搜索页面
	 */
	function c_toAdvancedSearch() {
		$this->view("search-advanced");
	}

	/**
	 * 查看序列号详细页面
	 */
	function c_toViewSerialno() {
		$serialnoNameStr = isset($_GET['serialnoName']) ? $_GET['serialnoName'] : null;
		$this->assign("serialnoList", $this->service->showSerialno($serialnoNameStr));
		$this->view("serialno-view");
	}

	/**
	 * 导出未归还的物料EXCEL
	 */
	function c_exportExcel() {
		$service = $this->service;
		$dataArr = $service->getNotBackWithRelDoc();
		$dao = new model_stock_productinfo_importProductUtil();
		return $dao->exportNotAllBackExcel($dataArr);
	}

	/**
	 * 新增调拨单
	 * @author huangzf
	 */
	function c_add() {
        $this->checkSubmit();
		try {
			$service = $this->service;
			$allocationObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
			/*s:--------------审核权限控制----------------*/
			if("audit" == $actType) {
				if(!$service->this_limit['调拨单审核权限']) {
					echo "<script>alert('没有权限进行审核!');window.close();</script>";
					exit();
				}
				$allocationObject['auditerName'] = $_SESSION['USERNAME'];
				$allocationObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------审核权限控制----------------*/
			$id = $service->add_d($allocationObject);

			if($id) {
				if("audit" == $actType) {
					$addObj = $service->find(array("id" => $id), null, 'docCode');
					echo "<script>alert('审核调拨单成功!单据编号为:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('新增调拨单成功!'); window.opener.window.show_page();window.close();  </script>";
				}
			}

			//			else {
			//				if("audit" == $actType) {
			//					echo "<script>alert('确认调拨失败,请确认调出仓库是否有足够库存,调入仓库是否初始化该物料！'); window.opener.window.show_page();window.close();  </script>";
			//				} else {
			//					echo "<script>alert('确认调拨失败,请确认调出仓库是否有足够库存,调入仓库是否初始化该物料！'); window.opener.window.show_page();window.close();  </script>";
			//				}
			//			}
		} catch(Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 修改调拨单
	 * @author huangzf
	 */
	function c_edit() {
		try {
			$service = $this->service;
			$allocationObject = $_POST[$this->objName];
			$actType = isset($_GET['actType']) ? $_GET['actType'] : null;
			/*s:--------------审核权限控制----------------*/
			if("audit" == $actType) {
				if(!$service->this_limit['调拨单审核权限']) {
					echo "<script>alert('没有权限进行审核!');window.close();</script>";
					exit();
				}
				$allocationObject['auditerName'] = $_SESSION['USERNAME'];
				$allocationObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------审核权限控制----------------*/
			//防止单据重复审核
			$lastObj = $service->find(array("id" => $allocationObject['id']));
			if($lastObj['docStatus'] == "YSH") {
				echo "<script>alert('单据已经审核,不可修改,请刷新列表!');window.close();</script>";
				exit();
			}

			if($service->edit_d($allocationObject)) {
				if("audit" == $actType) {
					echo "<script>alert('审核调拨单成功！单据编号为:" . $lastObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('修改调拨单成功!'); window.opener.window.show_page();window.close();  </script>";
				}
			}

			//		 else {
			//			if("audit" == $actType) {
			//				echo "<script>alert('确认调拨失败,请确认调出仓库是否有足够库存,调入仓库是否初始化该物料！'); window.opener.window.show_page();window.close();  </script>";
			//			} else {
			//				echo "<script>alert('修改调拨单失败,请确认单据信息是否完整!'); window.opener.window.show_page();window.close();  </script>";
			//			}
			//		}
		} catch(Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 调拨单反审核
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
			echo "操作失败!异常:" . $e->getMessage();
		}
	}

	/**
	 * 调拨单反审核权限判断
	 */
	function c_cancelAuditLimit() {
		if($this->service->this_limit['调拨单反审核权限']) {
			echo 1;
		} else
			echo 0;
	}

	/**
	 * 获取调拨单列表页面数据Json
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
	 * 获取源单相关联调拨单json数据
	 */
	function c_pageRelDocJson() {
		$service = $this->service;
		$relDocId = isset($_POST['relDocId']) ? $_POST['relDocId'] : "";
		$relDocType = isset($_POST['relDocType']) ? $_POST['relDocType'] : "";
		$service->getParam($_POST);

		//根据源单类型查询直接关联调拨单
		$service->searchArr['relDocId'] = $relDocId;
		$service->searchArr['relDocType'] = $relDocType;
		$jyrows = $service->listBySqlId("select_listgrid");

		if($relDocType == "DBDYDLXJY") { //借试用单还要查询根据其发货计划进行调拨的
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

		if($_POST['initTip'] == "0") { //正常借试用申请归还信息
			$jcRelDocIdArr = array();
			foreach($rows as $value) {
				array_push($jcRelDocIdArr, $value['id']);
			}
			$service->searchArr['relDocType'] = 'DBDYDLXDB';
			if(count($jcRelDocIdArr) > 0) {
				$service->searchArr['relDocIdArr'] = $jcRelDocIdArr;
			}
		} else {
			//导入借试用申请归还信息
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
	 * 跳转到初始化调拨单页面
	 */
	function c_toLendInit() {
		$this->view("lendinit");
	}

	/**
	 *
	 * 初始化借出调拨单
	 */
	function c_initAllLend() {
		$allocationObj = $this->service->initialLendAllocation();
		try {
			if($this->service->add_d($allocationObj)) {
				echo "成功!";
			} else {
				echo "失败!";
			}
		} catch(Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.close();</script>";
		}
	}

	/**
	 *
	 * 获取调拨单关联单据清单物料对应的未执行数量
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
	 * 抹平借出仓负库存数据
	 */
	function c_initLendStock() {
		$allocationObj = $this->service->allocatLendStock();
		//		print_r($allocationObj);
		try {
			if($this->service->add_d($allocationObj)) {
				echo "成功!";
			} else {
				echo "失败!";
			}
		} catch(Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.close();</script>";
		}
	}
}