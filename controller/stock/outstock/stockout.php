<?php

/**
 * @author huangzf
 * @Date 2011年5月14日 9:44:17
 * @version 1.0
 * @description:出库单基本信息控制层
 */
class controller_stock_outstock_stockout extends controller_base_action
{

	function __construct() {
		$this->objName = "stockout";
		$this->objPath = "stock_outstock";
		$this->relDocTypeArr = array(
			"XSCKFHJH" => "model_stock_outplan_outplan",
			"XSCKDLHT" => "model_contract_contract_contract"
		);
		parent::__construct();
	}

	/*
	 * 跳转到出库单基本信息
	 */
	function c_page() {
		$this->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 * 跳转到列表页
	 */
	function c_toList() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		if ($this->service->this_limit['仓存出库导入']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		switch ($docType) {
			case 'CKSALES' :
				$this->view("sales-list");
				break;
			case 'CKPICKING' :
				$this->view("picking-list");
				break;
			case 'CKOTHER' :
				$this->view("other-list");
				break;
            case 'CKDLBF' :
                $this->view("idleStockScrap-list");
                break;
			default :
				break;
		}
	}

	/**
	 * 跳转到待入库销售退货单-查看页面-销售出库
	 */
	function c_toAwaitList() {
		$this->assign("id", $_GET['id']);
		$this->view("sales-awaitlist");
	}

	/**
	 * 跳转到换货单据查看页面-其他出库
	 */
	function c_toListForExchange() {
		if ($this->service->this_limit['仓存出库导入']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->assign('exchangeId', $_GET['id']);
		$this->view("other-listforexchange");
	}

	/**
	 * 新增出库单页面
	 */
	function c_toAdd() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$this->assign('auditDate', day_date);
		//审核权限判断
		$this->checkAuditLimit($docType);
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		switch ($docType) {
			case 'CKSALES' :
				$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
				$this->showDatadicts(array('relDocType' => 'XSCKYDLX'), null, true);
				$this->view("sales-add");
				break;
			case 'CKPICKING' :
				$this->showDatadicts(array('relDocType' => 'LLCKYDLX'), null, true);
				$this->showDatadicts(array('otherSubjects' => 'KJKM'), null, true);
				$this->showDatadicts(array('pickingType' => 'LLLX'));

                $this->assign('DEPT_NAME', $_SESSION['DEPT_NAME']);
                $this->assign('DEPT_ID', $_SESSION['DEPT_ID']);
				$this->assign('pickName', $_SESSION['USERNAME']);
				$this->assign('pickCode', $_SESSION['USER_ID']);
				$this->view("picking-add");
				break;
			case 'CKOTHER' :
			    // 获取呆料报废需要新增的备注信息
                $otherDatasDao = new model_common_otherdatas();
			    $remarkContent = $otherDatasDao->getConfig('stockout_other_dlbfremark');

				$this->showDatadicts(array('relDocType' => 'QTCKYDLX'), null, true);
				$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
				$this->showDatadicts(array('isWarranty' => 'BXZK'), null, true);
                $this->assign('remarkContent', $remarkContent);
				$this->view("other-add");
				break;
			default :
				break;
		}
	}

    /**
     * 跳转新增呆料报废页面
     */
    function c_toScrapIdleStock() {
        $docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
        $id = isset ($_GET['id']) ? $_GET['id'] : null;
        $this->assign('auditDate', day_date);
        $stockInDao = new model_stock_instock_stockin();
        //审核权限判断
        $this->checkAuditLimit($docType);
        $service = $this->service;

        $relDocArr = $stockInDao->find(" id={$id}");
        $relDocId = isset($relDocArr['relDocId'])? $relDocArr['relDocId'] : '';
        $stockoutStrategy = $service->stockoutStrategyArr[$docType];
        $stockoutObj = $service->get_d($relDocId, new $stockoutStrategy());
        //审核权限判断
        $this->checkAuditLimit($docType);

//        $test = $service->showProAtEdit($stockoutObj['items'], new $stockoutStrategy());
//        echo "<pre>";print_r($stockoutObj['items']);exit();
        $stockoutObj['id'] = '';
        $stockoutObj['isRed'] = 0;
        $stockoutObj['docType'] = 'CKDLBF';
        $stockoutObj['docStatus'] = 'WSH';
        $stockoutObj['stockId'] = $relDocArr['inStockId'];
        $stockoutObj['stockCode'] = $relDocArr['inStockCode'];
        $stockoutObj['stockName'] = $relDocArr['inStockName'];
        $stockoutObj['relDocId'] = $relDocArr['id'];
        $stockoutObj['relDocName'] = $relDocArr['docName'];
        $stockoutObj['relDocCode'] = $relDocArr['docCode'];
        $stockoutObj['relDocType'] = $relDocArr['docType'];

        // 去除对应物料原来的ID
        if(is_array($stockoutObj['items'])){
            foreach ($stockoutObj['items'] as $k => $v){
                $stockoutObj['items'][$k]['id'] = '';
            }
        }

        foreach ($stockoutObj as $key => $val) {
            if ($key == 'items') {
                $this->assign("stockoutItems", $service->showProAtEdit($val, new $stockoutStrategy()));
            } else if ($key == 'packitems') {
                $this->assign("stockoutPackItems", $service->showPackItemAtEdit($val));
            } else if($key == 'docCode'){
                $this->assign('docCode', '');
            }else if($key == 'toUse'){
                $this->assign('toUseCode', $val);
                $this->assign('toUse', $this->getDataNameByCode($val));
            }else {
                $this->assign($key, $val);
            }
        }
        $this->assign("itemscount", count($stockoutObj['items']));
        $this->assign("packcount", count($stockoutObj['packitems']));
        $this->assign("relDocTypeName", $this->getDataNameByCode($stockoutObj['relDocType']));
        // 所属板块
        $this->showDatadicts(array('module' => 'HTBK'), $stockoutObj['module'],true);
        $this->showDatadicts(array('isWarranty' => 'BXZK'), $stockoutObj['isWarranty'], true);

        $this->view("idleStock-add");
    }

    /**
     * 呆料报废处理
     */
    function c_idleSrcap(){
        try {
            $service = $this->service;
            $stockoutObject = $_POST[$this->objName];
            $conDao = new model_contract_contract_contract();

            $conArr = $conDao->get_d($stockoutObject['contractId']);
            $docType = $stockoutObject['docType'];
            $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

            if ("audit" == $actType) {
                /*s:--------------审核权限控制,并设置审核人----------------*/
//                if ($docType == "CKOTHER") {
//                    if (!$service->this_limit['其他出库审核']) {
//                        echo "<script>alert('没有权限进行审核!');window.close();</script>";
//                        exit();
//                    }
//                }
                /*s:--------------审核权限控制,并设置审核人----------------*/

                $stockoutObject['auditerName'] = $_SESSION['USERNAME'];
                $stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
            }

            $id = $service->add_d($stockoutObject);

            if($id){
                if ("audit" == $actType) {
                    $addObj = $service->find(array("id" => $id), null, 'docCode');
                    //推送海外系统的采购订单
                    $service->pushERPorder($stockoutObject, $addObj['docCode']);
                    //如果是海外ERP合同发送邮件通知海外仓管
                    if (!empty($conArr['parentName'])) {
                        $infoArr['createName'] = $conArr['createName'];
                        $infoArr['createId'] = $conArr['createId'];
                        $infoArr['cid'] = $conArr['id'];
                        $infoArr['id'] = $id;
                        $infoArr['contractName'] = $conArr['contractName'];
                        $infoArr['contractCode'] = $conArr['contractCode'];

                        $this->service->sendToHWStorer_d($infoArr);
                    }

                    //核对成功进入审批流
                    succ_show('controller/stock/outstock/idleScrapEwf_index.php?actTo=ewfSelect&billId='.$id);
//                    echo "<script>alert('审核出库单成功!单据编号为:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
                }else {
                    echo "<script>alert('新增出库单成功!');window.opener.window.show_page();window.close();  </script>";
                }
            }
        }catch (Exception $e) {
            echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
        }
    }

    function c_idleScrapEdit(){
            try {
                $service = $this->service;
                $stockoutObject = $_POST[$this->objName];
                $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
                $docType = isset ($stockoutObject['docType']) ? $stockoutObject['docType'] : null;

                /*s:--------------审核权限控制,并设置审核人----------------*/
                if ("audit" == $actType) {
//                    if ($docType == "CKOTHER") {
//                        if (!$service->this_limit['其他出库审核']) {
//                            echo "<script>alert('没有权限进行审核!');window.close();</script>";
//                            exit();
//                        }
//                    }
                    $stockoutObject['auditerName'] = $_SESSION['USERNAME'];
                    $stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
                }
                /*e:--------------审核权限控制,并设置审核人----------------*/

                //防止单据重复审核
                $lastObj = $service->find(array("id" => $stockoutObject['id']));
                if ($lastObj['docStatus'] == "YSH") {
                    echo "<script>alert('单据已经审核,不可修改,请刷新列表!');window.close();</script>";
                    exit();
                }

                // 检查单据是否可报废
                $stockIDao = new model_stock_instock_stockin();
                $getIdsSql = "select relDocId from oa_stock_instock where id = {$stockoutObject['relDocId']}";
                $inDocRelDocIdArr = $stockIDao->_db->getArray($getIdsSql);
                $inDocRelDocId = $inDocRelDocIdArr? $inDocRelDocIdArr[0]['relDocId'] : '';
                $ItemsArr = $stockIDao->getRelativeItemsCount($inDocRelDocId,"'YSH'");
                if(is_array($ItemsArr) && count($ItemsArr) > 0){
                    $hasEmptyEquNum = 0;// 物料抵消数量最后为0的个数
                    foreach ($ItemsArr as $k => $v){
                        $hasEmptyEquNum += ($v['Num'] <= 0)? 1 : 0;
                    }
                    if($hasEmptyEquNum > 0){
                        echo "<script>alert('物料数量有误,请核对所有关联出入库单的红蓝单数量!');window.close();</script>";
                        exit();
                    }
                }

                if ($service->edit_d($stockoutObject)) {
                    if ("audit" == $actType) {
                        //推送海外系统的采购订单
                        $service->pushERPorder($stockoutObject, $lastObj['docCode']);

                        succ_show('controller/stock/outstock/idleScrapEwf_index.php?actTo=ewfSelect&billId='.$stockoutObject['id']);
//                        echo "<script>alert('审核出库单成功!单据编号为:" . $lastObj['docCode'] . "');window.opener.window.show_page();window.close();  </script>";
                    } else {
                        echo "<script>alert('修改出库单成功!');window.opener.window.show_page(); window.close();  </script>";
                    }
                }
            }catch (Exception $e) {
                echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
            }
    }

    /**
     * 审批完成后回调函数
     */
    function c_dealAfterAudit(){
        $service = $this->service;
        $docType = isset($_GET['docType'])? $_GET['docType'] : '';
        $spid = isset($_GET['spid'])? $_GET['spid'] : '';
        switch($docType){
            case 'DLBF':
                $service->workflowCallBack_idleScrap($spid);
                break;
            default :
                break;
        }
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

	/**
	 * 下推红色出库单页面
	 */
	function c_toAddRed() {
		$this->permCheck();
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy ());
		//审核权限判断
		$this->checkAuditLimit($docType);
		foreach ($stockoutObj as $key => $val) {
			if ($key == 'items') {
				$this->assign("stockoutItems", $service->showProAddRed($val, new $stockoutStrategy()));
			} else if ($key == 'packitems') {
				$this->assign("stockoutPackItems", $service->showPackItemAddRed($val));
			} else {
				$this->assign($key, $val);
			}
		}

        $this->assign('orgId', $id);
		$this->assign('auditDate', day_date);
		$this->assign("itemscount", count($stockoutObj['items']));
		$this->assign("packcount", count($stockoutObj['packitems']));
		$this->assign("relDocTypeName", $this->getDataNameByCode($stockoutObj['relDocType']));
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), $stockoutObj['module'],true);
		switch ($docType) {
			case 'CKSALES' :
				$this->showDatadicts(array('toUse' => 'CHUKUYT'), $stockoutObj['toUse'], true);
				$this->view("sales-red");
				break;
			case 'CKPICKING' :
				$this->showDatadicts(array('otherSubjects' => 'KJKM'), $stockoutObj['otherSubjects'], true);
				$this->showDatadicts(array('pickingType' => 'LLLX'), $stockoutObj['pickingType']);
				$this->view("picking-red");
				break;
			case 'CKOTHER' :
				$this->showDatadicts(array('toUse' => 'CHUKUYT'), $stockoutObj['toUse'], true);
				$this->showDatadicts(array('isWarranty' => 'BXZK'), $stockoutObj['isWarranty'], true);
                $stockId = $stockName = $stockCode = '';
                if($stockoutObj['toUse'] == 'CHUKUDLBF'){// 呆料报废的出库红单原单ID绑定蓝单的ID
                    $this->assign('relDocId', $id);
                    $this->assign('relDocCode', $stockoutObj['docCode']);
                    $stockId = $stockoutObj['stockId'];
                    $stockName = $stockoutObj['stockName'];
                    $stockCode = $stockoutObj['stockCode'];
                    $this->assign("relDocTypeName", $this->getDataNameByCode($stockoutObj['docType']));
                    $this->assign("relDocType", 'QTCKQTCK');
                }
                $this->assign('stockId', $stockId);
                $this->assign('stockName', $stockName);
                $this->assign('stockCode', $stockCode);
				$this->view("other-red");
				break;
            case 'CKDLBF' :
                $this->assign('toUseCode', $stockoutObj['toUse']);
                $this->assign('toUse', $this->getDataNameByCode($stockoutObj['toUse']));
                $this->showDatadicts(array('isWarranty' => 'BXZK'), $stockoutObj['isWarranty'], true);
                $this->view("idleStock-red");
                break;
			default :
				break;
		}
	}

    /**
     * 重启待报废呆料入库单
     */
	function c_toSetBackIdleScrap(){
	    $id = isset($_POST['id'])? $_POST['id'] : '';
        $docType = isset($_POST['docType'])? $_POST['docType'] : '';
        $backArr = array();
        if($id != '' && $docType == 'CKDLBF'){
            $stockoutStrategy = $this->service->stockoutStrategyArr['CKDLBF'];
            $stockoutStrategy = new $stockoutStrategy();
            $mainObj = $this->service->get_d($id,$stockoutStrategy);
            if(!empty($mainObj)){
                $backArr['msg'] = 'ok';
                $relDocId = isset($mainObj['relDocId'])? $mainObj['relDocId'] : '';
                $items = isset($mainObj['items'])? $mainObj['items'] : array();
                // 出库单状态改为打回(WSH)
                $this->service->update("id = {$id}", array('docStatus'=>'WSH'));

                // 更新单据的物料对应待报废呆料仓的数量
                foreach ($items as $item){
                    $outNum = $item['actOutNum'];
                    $productCode = $item['productCode'];
                    $stockId = $item['stockId'];
                    if($stockId != '' && $productCode != ''){
                        $updateSql = "UPDATE oa_stock_inventory_info set actNum = actNum+{$outNum},exeNum = exeNum+{$outNum} where stockId = {$stockId} and productCode = '{$productCode}';";
                        $this->service->_db->query( $updateSql );
                    }
                }
            }else{
                $backArr['msg'] = 'fail';
            }
        }else{
            $backArr['msg'] = 'fail';
        }
        echo util_jsonUtil::encode($backArr);
    }

	/**
	 * 待入库销售退货单-下推红字入库
	 */
	function c_toAddRedByAwait() {
		$id = $_GET['id'];    //退货单id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//获取退货单信息并初始化
		$returnDao = new model_projectmanagent_return_return();
		$returnObj = $returnDao->get_d($id);
		$this->assign('relDocCode', $returnObj['returnCode']);
		$this->assign('contractId', $returnObj['contractId']);
		$this->assign('contractCode', $returnObj['contractCode']);
		$this->assign('contractName', $returnObj['contractName']);
		$this->assign('contractObjCode', $returnObj['contractObjCode']);
		$this->assign('itemscount', count($returnObj['equipment']));
		$contractDao = new model_contract_contract_contract();
		$contractObj = $contractDao->get_d($returnObj['contractId']);
		$this->assign('customerName', $contractObj['customerName']);
		$this->assign('customerId', $contractObj['customerId']);

		// 带出合同板块
		$this->showDatadicts(array('module' => 'HTBK'), $contractObj['module'], true);
		//审核权限判断
		$this->checkAuditLimit("CKSALES");
		$this->view("sales-awaitred");
	}

	/**
	 * 资产出库-下推蓝色出库单
	 */
	function c_toAddBlueByAsset() {
		$id = $_GET['id'];    //申请单id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//获取申请单信息并初始化
		$requireinDao = new model_asset_require_requirein();
		$obj = $requireinDao->get_d($id);
		$this->assign('relDocTypeName', $this->getDataNameByCode('QTCKZCCK'));//源单类型
		$this->assign('relDocCode', $obj['requireCode']);//源单编号为需求申请单号
		$this->assign('remark', $obj['remark']);//备注
		$this->assign('applyId', $obj['applyId']);//经办人为需求申请人
		$this->assign('applyName', $obj['applyName']);
		//获取明细数量
		$itemDao = new model_asset_require_requireinitem();
		$item = $itemDao->getOutStockDetail_d($id);
		$this->assign('itemscount', count($item));
		//审核权限判断
		$this->checkAuditLimit("CKOTHER");
		$this->view("other-assetblue");
	}

	/**
	 * 资产出库-下推蓝色出库单
	 */
	function c_toAddBlueByAssetNew() {
		$id = $_GET['id'];    //申请单id
		$ids = $_GET['ids'];    //明细id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//获取申请单信息并初始化
		$result = util_curlUtil::getDataFromAWS('asset', 'getProductTransferInfo', array(
			"productTransferId" => $id,"itemsId" => $ids
		));
		$productTransferInfo = util_jsonUtil::decode($result['data'], true);

		if(empty($productTransferInfo['data']['details'])){
			msgRf ( '没有符合下推要求的物料明细，请检查明细是否已经全部下推出库或者没有匹配对应物料！' );
			exit ();
		}
		// 主表赋值处理
		if ($productTransferInfo['data']['productTransferInfo']) {
			$this->assign('relDocTypeName', $this->getDataNameByCode('QTCKZCCK'));//源单类型
			$this->assign('relDocCode', $productTransferInfo['data']['productTransferInfo']['requireCode']);//源单编号为需求申请单号
			$this->assign('remark', $productTransferInfo['data']['productTransferInfo']['remark']);//备注
			$this->assign('applyId', $productTransferInfo['data']['productTransferInfo']['applyUserId']);//经办人
			$this->assign('applyName', $productTransferInfo['data']['productTransferInfo']['applyUser']);
			$this->assign('deptCode', $productTransferInfo['data']['productTransferInfo']['applyDeptId']);//所在部门
			$this->assign('deptName', $productTransferInfo['data']['productTransferInfo']['applyDept']);
		}

		$str = "";
		// 子表赋值处理
		if ($productTransferInfo['data']['details']) {
			$itemDao = new model_asset_require_requireinitem();
			$str = $itemDao->showProAtStockOutNew_d($productTransferInfo['data']['details']);
			if($str == ""){
				msgRf ( '没有符合下推要求的物料明细，请检查明细是否已经全部下推出库或者没有匹配对应物料！' );
				exit ();
			}
		}

		$this->assign('itemscount', count($productTransferInfo['data']['details']));
		$this->assign('itemsbody', $str);
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), null, true);

		//审核权限判断
		$this->checkAuditLimit("CKOTHER");
		$this->view("other-assetbluenew");
	}

	/**
	 * 出库任务下推蓝色出库单
	 * @author huangzf
	 */
	function c_toBluePush() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$relDocType = isset ($_GET['relDocType']) ? $_GET['relDocType'] : null;
		$relDocId = isset ($_GET['relDocId']) ? $_GET['relDocId'] : null;
		$service = $this->service;
		//审核权限判断
		$this->checkAuditLimit($docType);
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		switch ($docType) {
			case 'CKSALES' :
				$outPlanDao = new model_stock_outplan_outplan();
				$outPlanObj = $outPlanDao->get_d($relDocId);
				$outProDao = new model_stock_outplan_outplanProduct();
				$outItemArr = $outProDao->getItemByshipId_d($relDocId);

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$outItemArr = $productinfoDao->k3CodeFormatter_d($outItemArr);
                $chargeDeptName = $chargeDeptCode = '';

				$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
				$this->assign("relDocCode", $outPlanObj['planCode']);
				$this->assign("relDocId", $outPlanObj['id']);
				$this->assign("relDocType", $relDocType);
				$this->assign("relDocName", "");
				$this->assign("stockId", $outPlanObj['stockId']);
				$this->assign("stockCode", $outPlanObj['stockCode']);
				$this->assign("stockName", $outPlanObj['stockName']);
				$this->assign("contractCode", $outPlanObj['docCode']);
				$this->assign("contractName", $outPlanObj['docName']);
				$this->assign("contractType", $outPlanObj['docType']);
				$this->assign("contractId", $outPlanObj['docId']);
				$this->assign("contractObjCode", $outPlanObj['rObjCode']);

                $userDao = new model_deptuser_user_user();
				if ($outPlanObj['docType'] == "oa_contract_contract") { //合同
					$contractDao = new model_contract_contract_contract();
					$contractObj = $contractDao->get_d($outPlanObj['docId']);
					$this->assign("chargeName", $contractObj['prinvipalName']);
					$this->assign("chargeCode", $contractObj['prinvipalId']);

                    $userinfo = $userDao->getUserById($contractObj['prinvipalId']);
                    $chargeDeptName = $userinfo['DEPT_NAME'];
                    $chargeDeptCode = $userinfo['DEPT_ID'];

					// 带出合同板块
					$this->showDatadicts(array('module' => 'HTBK'), $contractObj['module'], true);
				} else {
					$presentDao = new model_projectmanagent_present_present();
					$presentObj = $presentDao->get_d($outPlanObj['docId']);
					$this->assign("chargeName", $presentObj['salesName']);
					$this->assign("chargeCode", $presentObj['salesNameId']);

                    $userinfo = $userDao->getUserById($presentObj['salesNameId']);
                    $chargeDeptName = $userinfo['DEPT_NAME'];
                    $chargeDeptCode = $userinfo['DEPT_ID'];
				}

				$this->assign("saleAddress", $outPlanObj['address']);
				$this->assign("auditDate", day_date);
				$this->assign("itemscount", count($outItemArr));
				$this->assign("customerName", $outPlanObj['customerName']);
				$this->assign("customerId", $outPlanObj['customerId']);
				$this->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
				$this->assign("stockoutItems", $outProDao->showAddList($outItemArr));
                $this->assign("chargeDeptName", $chargeDeptName);
                $this->assign("chargeDeptCode", $chargeDeptCode);
				$this->view("sales-blue-push");
				break;
			case 'CKPICKING' :
				$this->view("picking-blue-push");
				break;
			case 'CKOTHER' :
				$outPlanDao = new model_stock_outplan_outplan();
				$outPlanObj = $outPlanDao->get_d($relDocId);

				$contractCode = (isset($outPlanObj['contractCode']))? $outPlanObj['contractCode'] : '';
				$this->assign("relDocCode", $outPlanObj['planCode']);
				$this->assign("relDocId", $outPlanObj['id']);
				$this->assign("relDocType", $relDocType);
				$this->assign("relDocName", "");
				$this->assign("contractCode", $contractCode);
				$this->assign("contractName", $outPlanObj['docName']);
				$this->assign("contractType", $outPlanObj['docType']);
				$this->assign("contractId", $outPlanObj['contractId']);
				$this->assign("contractObjCode", $outPlanObj['rObjCode']);
				$this->assign("customerName", $outPlanObj['customerName']);
				$this->assign("customerId", $outPlanObj['customerId']);
				if($outPlanObj['docType'] == 'oa_present_present'){// 赠送单的领料人和部门用赠送单申请人的信息 ID2203 by huanghaojin 2016-11-14
					$sql = "select p.salesName,u.USER_ID,d.DEPT_ID,d.DEPT_NAME from oa_present_present p LEFT JOIN user u on p.salesNameId = u.USER_ID LEFT JOIN department d on d.DEPT_ID = u.DEPT_ID where p.Code = '{$outPlanObj['docCode']}';";
					$presentData = $service->_db->getArray($sql);
					$this->assign("pickCode", is_array($presentData[0])? $presentData[0]['USER_ID'] : '');
					$this->assign("pickName", is_array($presentData[0])? $presentData[0]['salesName'] : '');
					$this->assign("deptCode", is_array($presentData[0])? $presentData[0]['DEPT_ID'] : '');
					$this->assign("deptName", is_array($presentData[0])? $presentData[0]['DEPT_NAME'] : '');
				}else{
					$this->assign("pickCode", '');
					$this->assign("pickName", '');
					$this->assign("deptCode", '');
					$this->assign("deptName", '');
				}

				$outProDao = new model_stock_outplan_outplanProduct();
				$outItemArr = $outProDao->getItemByshipId_d($relDocId);

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$outItemArr = $productinfoDao->k3CodeFormatter_d($outItemArr);

				$this->assign("itemscount", count($outItemArr));
				$this->assign("stockoutItems", $outProDao->showAddOtherList($outItemArr));

				$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
				$this->showDatadicts(array('isWarranty' => 'BXZK'), $outPlanObj['isWarranty']);//传入保修状况
				$this->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
				$this->assign("auditDate", day_date);
				$this->view("other-blue-push");
				break;
			case 'CKOTHERGH' :
				$relObjDao = new model_projectmanagent_borrowreturn_borrowreturnDis();
				$relObj = $relObjDao->get_d($relDocId);
				$this->assign("relDocCode", $relObj['Code']);
				$this->assign("relDocId", $relObj['id']);
				$this->assign("relDocType", $relDocType);
				$this->assign("relDocName", "");
				$this->assign("contractCode", $relObj['docCode']);
				$this->assign("contractName", $relObj['docName']);
				$this->assign("contractType", $relObj['docType']);
				$this->assign("contractId", $relObj['docId']);
				$this->assign("contractObjCode", $relObj['rObjCode']);
				$this->assign("customerName", $relObj['customerName']);
				$this->assign("customerId", $relObj['customerId']);
				$this->assign("pickCode", $relObj['chargerId']);
				$this->assign("pickName", $relObj['chargerName']);
				$this->assign("deptName", $relObj['deptName']);
				$this->assign("deptCode", $relObj['deptId']);

				$relDetailDao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
				$outItemArr = $relDetailDao->getItemByshipId_d($relDocId);

				// k3编码加载处理
				$productinfoDao = new model_stock_productinfo_productinfo();
				$outItemArr = $productinfoDao->k3CodeFormatter_d($outItemArr);

				$this->assign("itemscount", count($outItemArr));
				$this->assign("stockoutItems", $relDetailDao->showAddOtherList($outItemArr));

				$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
				$this->showDatadicts(array('isWarranty' => 'BXZK'), null, true);
				$this->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
				$this->assign("auditDate", day_date);
				/**
				 * 质检部分渲染
				 */
				$borrowReturnDao = new model_projectmanagent_borrowreturn_borrowreturn();
				$borrowReturnObj = $borrowReturnDao->get_d($relObj['borrowreturnId']);
				$this->assign('qualityObjId', $borrowReturnObj['id']);
				$this->assign('qualityObjType', 'ZJSQYDGH');

				$this->view("other-blue-push");
				break;
			default :
				break;
		}
	}

	/**
	 * 生产领料下推蓝色出库单
	 */
	function c_toAddByPicking() {
		$id = $_GET['id'];    //领料单id
		$pickDao = new model_produce_plan_picking();
		$obj = $pickDao->get_d($id);
		$this->assign('relDocCode', $obj['docCode']); //源单编号
		$this->assign('relDocId', $obj['id']); //源单id
		$this->assign('pickName', $obj['createName']); //领料人
		$this->assign('pickId', $obj['createId']);
		$this->assign('contractId', $obj['relDocId']); //合同信息
		$this->assign('contractCode', $obj['relDocCode']);
		$this->assign('contractName', $obj['relDocName']);
		$this->assign('contractType', 'oa_contract_contract');

		$otherDao = new model_common_otherdatas();
		$userObj = $otherDao->getUserDatas($obj['createId']);
		$this->assign('deptName', $userObj['DEPT_NAME']); //领料人部门
		$this->assign('deptCode', $userObj['DEPT_ID']);

		$this->assign('auditDate', day_date); //单据日期
		$this->showDatadicts(array('pickingType' => 'LLLX')); //领料类型
		$this->showDatadicts(array('otherSubjects' => 'KJKM'), null, true); //科目类型

		$relDocTypeName = $this->getDataNameByCode('LLCKSCRWD'); //源单类型名称
		$this->assign('relDocTypeName', $relDocTypeName);
		$this->assign('relDocType', 'LLCKSCRWD');

		//生产任务信息
		$taskDao = new model_produce_task_producetask();
		$taskObj = $taskDao->get_d($obj['taskId']);
		$this->assign('purpose', $taskObj['purpose']); //物料用途

		//审核权限判断
		$this->checkAuditLimit("CKPICKING");
		$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), $obj['module'], true);

		$this->view("add-picking", true);
	}

	/**
	 * 修改出库单页面
	 */
	function c_toEdit() {
		$this->permCheck();
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());
		//审核权限判断
		$this->checkAuditLimit($docType);
		foreach ($stockoutObj as $key => $val) {
			if ($key == 'items') {
				$this->assign("stockoutItems", $service->showProAtEdit($val, new $stockoutStrategy()));
			} else if ($key == 'packitems') {
				$this->assign("stockoutPackItems", $service->showPackItemAtEdit($val));
			} else {
				$this->assign($key, $val);
			}
		}
		$this->assign("itemscount", count($stockoutObj['items']));
		$this->assign("packcount", count($stockoutObj['packitems']));
		$this->assign("relDocTypeName", $this->getDataNameByCode($stockoutObj['relDocType']));
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), $stockoutObj['module'],true);
		switch ($docType) {
			case 'CKSALES' :
				$this->showDatadicts(array('toUse' => 'CHUKUYT'), $stockoutObj['toUse'], true);
				$this->view("sales-edit");
				break;
			case 'CKPICKING' :
				$this->showDatadicts(array('otherSubjects' => 'KJKM'), $stockoutObj['otherSubjects'], true);
				$this->showDatadicts(array('pickingType' => 'LLLX'), $stockoutObj['pickingType']);
				$this->view("picking-edit");
				break;
			case 'CKOTHER' :
                // 获取呆料报废需要新增的备注信息
                $otherDatasDao = new model_common_otherdatas();
                $remarkContent = $otherDatasDao->getConfig('stockout_other_dlbfremark');
                $this->assign('remarkContent', $remarkContent);
			    if($stockoutObj['toUse'] == "CHUKUDLBF" && $stockoutObj['remark'] == ''){
                    $this->assign('remark', $remarkContent);
                }
				$this->showDatadicts(array('toUse' => 'CHUKUYT'), $stockoutObj['toUse'], true);
				$this->showDatadicts(array('isWarranty' => 'BXZK'), $stockoutObj['isWarranty'], true);
				$this->view("other-edit");
				break;
            case 'CKDLBF' :
                $this->assign('toUseCode', $stockoutObj['toUse']);
                $this->assign('toUse', $this->getDataNameByCode($stockoutObj['toUse']));
                $this->showDatadicts(array('isWarranty' => 'BXZK'), $stockoutObj['isWarranty'], true);
                $this->view("idleStock-edit");
                break;
			default :
				break;
		}
	}

	/**
	 * 查看出库单页面
	 */
	function c_toView() {
		//		$this->permCheck();
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());

		//控制单价 金额权限
		$stockoutObj['items'] = $service->filterWithoutField('单价', $stockoutObj['items'], 'list', array('cost','salecost'));
		$stockoutObj['items'] = $service->filterWithoutField('金额', $stockoutObj['items'], 'list', array('subCost','saleSubCost'));

		foreach ($stockoutObj as $key => $val) {
			if ($key == 'items') {
				$this->assign("stockoutItems", $service->showProAtView($val, new $stockoutStrategy()));
			} else if ($key == 'packitems') {
				$this->assign("stockoutPackItems", $service->showPackItemAtView($val));
			} else {
				$this->assign($key, $val);
			}
		}
		$this->assign("relDocType", $this->getDataNameByCode($stockoutObj['relDocType']));

		switch ($docType) {
			case 'CKSALES' :
				$this->assign("toUse", $this->getDataNameByCode($stockoutObj['toUse']));
				$this->view("sales-view");
				break;
			case 'CKPICKING' :
				$this->assign("otherSubjects", $this->getDataNameByCode($stockoutObj['otherSubjects']));
				$this->assign("pickingType", $this->getDataNameByCode($stockoutObj['pickingType']));
				$this->view("picking-view");
				break;
			case 'CKOTHER' :
				$this->assign("toUse", $this->getDataNameByCode($stockoutObj['toUse']));
				$this->view("other-view");
				break;
            case 'CKDLBF' :
                $this->assign("toUse", $this->getDataNameByCode($stockoutObj['toUse']));
                $this->assign("actType", $_GET['actType']);
                $this->view("other-view");
                break;
			default :
				break;
		}
	}

	/**
	 *
	 * 出库单打印页面
	 */
	function c_toPrint() {
		$this->permCheck();
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());
        //控制单价 金额权限
        $stockoutObj['items'] = $service->filterWithoutField('单价', $stockoutObj['items'], 'list', array('cost','salecost'));
        $stockoutObj['items'] = $service->filterWithoutField('金额', $stockoutObj['items'], 'list', array('subCost','saleSubCost'));

		$this->show->assign("finance",'冯锦华');//默认会计员冯锦华

		foreach ($stockoutObj as $key => $val) {
			if ($key == 'items') {
				if($stockoutObj['isRed'] == '1'){
					$this->assign("stockoutItems", $service->showRedProAtPrint($val, new $stockoutStrategy()));
				}else{
					$this->assign("stockoutItems", $service->showProAtPrint($val, new $stockoutStrategy()));
				}
			} else if ($key == 'packitems') {
				$this->assign("stockoutPackItems", $service->showPackItemAtView($val));
			} else {
				$this->assign($key, $val);
			}
		}
		$this->assign("relDocType", $this->getDataNameByCode($stockoutObj['relDocType']));
        $this->show->assign("year",date("Y",strtotime($stockoutObj['auditDate'])));
        $this->show->assign("sortyear",date("y",strtotime($stockoutObj['auditDate'])));
        $this->show->assign("month",date("m",strtotime($stockoutObj['auditDate'])));
        $this->show->assign("day",date("d",strtotime($stockoutObj['auditDate'])));
        $this->show->assign("itemCount",count($stockoutObj['items']));
        $this->show->assign("photoPath",str_replace('\\','/',WEB_TOR));
        $this->show->assign("stockMan",$_SESSION['USERNAME']);
		switch ($docType) {
			case 'CKSALES' :
				//销售员读取合同的销售负责人
				$contractDao = new model_contract_contract_contract();
				$rs = $contractDao->find(array('id' => $stockoutObj['contractId']) ,null,'prinvipalName');
				$this->assign("linkmanName", $rs['prinvipalName']);
				$this->view("sales-print");
				break;
			case 'CKPICKING' :
				$arr=explode('-',$stockoutObj['relDocName']);
				$relDocName=$arr[0];
				$this->assign("relDocName", $relDocName);
				$this->assign("otherSubjects", $this->getDataNameByCode($stockoutObj['otherSubjects']));
				$this->assign("pickingType", $this->getDataNameByCode($stockoutObj['pickingType']));
				$this->view("picking-print");
				break;
			case 'CKOTHER' :
				$this->assign("toUse", $this->getDataNameByCode($stockoutObj['toUse']));
				$this->view("other-print");
				break;
			default :
				break;
		}
	}

	/**
	 * 打印 - 用于成本结转
	 */
	function c_toPrintForCarry() {
		$this->permCheck();
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());
		foreach ($stockoutObj as $key => $val) {
			if ($key == 'items') {
				$this->assign("stockoutItems", $service->showPrintForCarry($val, new $stockoutStrategy()));
			} else {
				$this->assign($key, $val);
			}
		}
		$this->view("sales-printforcarry");
	}

	/**
	 * 查看序列号详细页面
	 */
	function c_toViewSerialno() {
		$serialnoNameStr = isset ($_GET['serialnoName']) ? $_GET['serialnoName'] : null;
		$this->assign("serialnoList", $this->service->showSerialno($serialnoNameStr));
		$this->view("serialno-view");
	}

	/**
	 * 获取入库单列表页面数据Json
	 */
	function c_pageListGridJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->page_d("select_listgrid");
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 *高级搜索页面
	 */
	function c_toAdvancedSearch() {
		$this->assign("docType", $_GET['docType']);
		$this->view("search-advanced");
	}

	/**
	 * 红色出库单下推蓝色出库单显示模板
	 */
	function c_showRelItem() {
		$id = isset ($_POST['id']) ? $_POST['id'] : null;
		$docType = isset ($_POST['docType']) ? $_POST['docType'] : null;
		$service = $this->service;
		$itemDao = new model_stock_outstock_stockoutitem();
		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$itemObjs = $itemDao->getItemByMainId($id);
		$itemStr = $this->service->showRelItem($itemObjs, new $stockoutStrategy());
		echo $itemStr;
	}

	/**
	 * 入库单下推出库单显示模板
	 */
	function c_showItemAtInStock() {
		$id = isset ($_POST['id']) ? $_POST['id'] : null;
		$docType = isset ($_POST['docType']) ? $_POST['docType'] : null;
		$service = $this->service;
		$itemDao = new model_stock_outstock_stockoutitem();
		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$itemObjs = $itemDao->getItemByMainId($id);
		$itemStr = $this->service->showItemAtInStock($itemObjs, new $stockoutStrategy());
		echo $itemStr;
	}

	/**
	 * 新增出库单
	 * @author huangzf
	 * TODO
	 */
	function c_add() {
		try {
			$service = $this->service;
			$stockoutObject = $_POST[$this->objName];
			$conDao = new model_contract_contract_contract();

			$conArr = $conDao->get_d($stockoutObject['contractId']);
			$docType = $stockoutObject['docType'];
			$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;

			/*s:--------------审核权限控制,并设置审核人----------------*/
			if ("audit" == $actType) {
				if ($docType == "CKSALES") {
					if (!$service->this_limit['销售出库审核']) {
						echo "<script>alert('没有权限进行审核!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKPICKING") {
					if (!$service->this_limit['领料出库审核']) {
						echo "<script>alert('没有权限进行审核!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKOTHER") {
					if (!$service->this_limit['其他出库审核']) {
						echo "<script>alert('没有权限进行审核!');window.close();</script>";
						exit();
					}
				}
				$stockoutObject['auditerName'] = $_SESSION['USERNAME'];
				$stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------审核权限控制,并设置审核人----------------*/

			$id = $service->add_d($stockoutObject);

			$infoArr['createName'] = $conArr['createName'];
			$infoArr['createId'] = $conArr['createId'];
			$infoArr['cid'] = $conArr['id'];
			$infoArr['id'] = $id;
			$infoArr['contractName'] = $conArr['contractName'];
			$infoArr['contractCode'] = $conArr['contractCode'];

            if ($id) {
                if($stockoutObject['isRed'] == 0 && $stockoutObject['stockName'] == '呆料仓库' && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF') {// 报废呆料处理(蓝色单据,呆料报废用途,出库仓为呆料仓)
                    $addObj = $service->find(array("id" => $id), null, 'docCode');
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityObj = array();// 申请质检提交数组
                    $qualityObj['supplierId'] = $stockoutObject['customerId'];
                    $qualityObj['supplierName'] = $stockoutObject['customerName'];
                    $qualityObj['relDocTypeName'] = '呆料报废通知单';
                    $qualityObj['relDocType'] = 'ZJSQDLBF';
                    $qualityObj['relDocId'] = $id;
                    $qualityObj['relDocCode'] = $addObj['docCode'];
                    $qualityObj['workDetail'] = '';
                    $qualityObj['applyUserName'] = $_SESSION['USERNAME'];
                    $qualityObj['applyUserCode'] = $_SESSION['USER_ID'];
                    $qualityObj['items'] = array();

                    $getItemsDao = new model_stock_outstock_stockoutitem();
                    $relDocItemsArr = $getItemsDao->getItemByMainId($id);// 获取出库单相关物料信息
                    if($relDocItemsArr && !empty($relDocItemsArr)){
                        foreach ($relDocItemsArr as $itemk => $itemv){
                            $equArr['productId'] = $itemv['productId'];
                            $equArr['productCode'] = $itemv['productCode'];
                            $equArr['productName'] = $itemv['productName'];
                            $equArr['pattern'] = $itemv['pattern'];
                            $equArr['unitName'] = $itemv['unitName'];
                            $equArr['checkTypeName'] = '全检';
                            $equArr['checkType'] = 'ZJFSQJ';
                            $equArr['qualityNum'] = $itemv['actOutNum'];
                            $equArr['maxNum'] = $itemv['actOutNum'];
                            $equArr['relDocItemId'] = $itemv['id'];
                            $equArr['serialId'] = $itemv['serialnoId'];
                            $equArr['serialName'] = $itemv['serialnoName'];
                            $qualityObj['items'][] = $equArr;
                        }

                        // 添加质检申请
                        $qualityApply = $qualityapplyDao->add_d($qualityObj);

                        // 更新其他出库状态为质检中
                        if($qualityApply){
                            $condition = array("id" => $id);
                            $object = array("docStatus" => "ZJZ");
                            $service->update($condition, $object);
                            echo "<script>alert('此出库单为报废呆料申请,系统已自动提交质检!');window.opener.window.show_page();window.close();  </script>";
                        }else{
                            echo "<script>alert('此出库单为报废呆料申请,系统已自动提交质检失败!');window.opener.window.show_page();window.close();  </script>";
                        }
                    }else{
                        echo "<script>alert('新增出库单成功!');window.opener.window.show_page();window.close();  </script>";
                    }
				}
				else if((isset($stockoutObject['orgId']) || isset($stockoutObject['relDocId'])) && $stockoutObject['isRed'] == 1 && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF'){// 呆料报废其它出库单下推红字
                    $mainId = isset($stockoutObject['orgId'])? $stockoutObject['orgId'] : $stockoutObject['relDocId'];// 呆料报废红单的原单ID为对应蓝单的Id
                    $stockInDao = new model_stock_instock_stockin();
                    $inStockObj = $stockInDao->find(array('relDocId'=>$mainId));

                    if($inStockObj){
                        // 关闭关联的待报废呆料入库单
                        $stockInDao->closeIdleScrapInStock($inStockObj['id']);

                        // 如果有关联的待报废呆料出库单则删掉
                        $outStockRelDocId = $inStockObj['id'];// 待报废呆料出库单上级入库单ID
                        $outStockObj = $this->service->findAll(array('relDocId'=>$outStockRelDocId));
                        if($outStockObj){
                            foreach ($outStockObj as $outStockObjV){
                                $this->service->deletes_d($outStockObjV['id']);
                            }
                        }
                    }
                    echo "<script>alert('红单下推成功,关联待报废入库单已关闭!');window.opener.window.show_page();window.close();  </script>";
                }
				else if ("audit" == $actType) {
                    $addObj = $service->find(array("id" => $id), null, 'docCode');
                    //推送海外系统的采购订单
                    $service->pushERPorder($stockoutObject, $addObj['docCode']);
                    //如果是海外ERP合同发送邮件通知海外仓管
                    if (!empty($conArr['parentName'])) {
                        $this->service->sendToHWStorer_d($infoArr);
                    }

                    echo "<script>alert('审核出库单成功!单据编号为:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
                }else {
					echo "<script>alert('新增出库单成功!');window.opener.window.show_page();window.close();  </script>";
				}
			}
			//			else {
			//				if ("audit" == $actType) {
			//					echo "<script>alert('审核出库单失败,请确认仓库物料的库存是否足够!'); window.opener.window.show_page();window.close();  </script>";
			//				} else {
			//					echo "<script>alert('新增出库单失败,请确认单据信息是否完整!');window.opener.window.show_page(); window.close();  </script>";
			//				}
			//			}
		} catch (Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 修改出库单
	 * @author huangzf
	 */
	function c_edit() {
		try {
			$service = $this->service;
			$stockoutObject = $_POST[$this->objName];
			$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
			$docType = isset ($stockoutObject['docType']) ? $stockoutObject['docType'] : null;

			/*s:--------------审核权限控制,并设置审核人----------------*/
			if ("audit" == $actType) {
				if (is_numeric($stockoutObject['relDocId']) && strlen($stockoutObject['relDocId']) < 32 && $stockoutObject['relDocType'] == 'QTCKZCCK') {//源单类型为资产出库,新OA单据不做校验
					$checkResult = $service->checkAudit_d($stockoutObject);//检查出库数量是否存在填写错误
					if ($checkResult == 2) {
						echo "<script>alert('资产出库合计数量大于申请数量，请核实!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKSALES") {
					if (!$service->this_limit['销售出库审核']) {
						echo "<script>alert('没有权限进行审核!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKPICKING") {
					if (!$service->this_limit['领料出库审核']) {
						echo "<script>alert('没有权限进行审核!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKOTHER") {
					if (!$service->this_limit['其他出库审核']) {
						echo "<script>alert('没有权限进行审核!');window.close();</script>";
						exit();
					}
				}
				$stockoutObject['auditerName'] = $_SESSION['USERNAME'];
				$stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------审核权限控制,并设置审核人----------------*/
			//防止单据重复审核
			$lastObj = $service->find(array("id" => $stockoutObject['id']));
			if ($lastObj['docStatus'] == "YSH") {
				echo "<script>alert('单据已经审核,不可修改,请刷新列表!');window.close();</script>";
				exit();
			}

			if ($service->edit_d($stockoutObject)) {
                if($stockoutObject['isRed'] == 0 && $stockoutObject['stockName'] == '呆料仓库' && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF') {// 报废呆料处理(蓝色单据,呆料报废用途,出库仓为呆料仓)
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityObj = array();// 申请质检提交数组
                    $qualityObj['supplierId'] = $stockoutObject['customerId'];
                    $qualityObj['supplierName'] = $stockoutObject['customerName'];
                    $qualityObj['relDocTypeName'] = '呆料报废通知单';
                    $qualityObj['relDocType'] = 'ZJSQDLBF';
                    $qualityObj['relDocId'] = $stockoutObject['id'];
                    $qualityObj['relDocCode'] = $lastObj['docCode'];
                    $qualityObj['workDetail'] = '';
                    $qualityObj['applyUserName'] = $_SESSION['USERNAME'];
                    $qualityObj['applyUserCode'] = $_SESSION['USER_ID'];
                    $qualityObj['items'] = array();

                    $getItemsDao = new model_stock_outstock_stockoutitem();
                    $relDocItemsArr = $getItemsDao->getItemByMainId($stockoutObject['id']);// 获取出库单相关物料信息
                    if(is_array($relDocItemsArr)){
                        foreach ($relDocItemsArr as $itemk => $itemv){
                            $equArr['productId'] = $itemv['productId'];
                            $equArr['productCode'] = $itemv['productCode'];
                            $equArr['productName'] = $itemv['productName'];
                            $equArr['pattern'] = $itemv['pattern'];
                            $equArr['unitName'] = $itemv['unitName'];
                            $equArr['checkTypeName'] = '全检';
                            $equArr['checkType'] = 'ZJFSQJ';
                            $equArr['qualityNum'] = $itemv['actOutNum'];
                            $equArr['maxNum'] = $itemv['actOutNum'];
                            $equArr['relDocItemId'] = $itemv['id'];
                            $equArr['serialId'] = $itemv['serialnoId'];
                            $equArr['serialName'] = $itemv['serialnoName'];
                            $qualityObj['items'][] = $equArr;
                        }

                        // 添加质检申请
                        $qualityApply = $qualityapplyDao->add_d($qualityObj);

                        // 更新其他出库状态为质检中
                        if($qualityApply){
                            $condition = array("id" => $stockoutObject['id']);
                            $object = array("docStatus" => "ZJZ");
                            $service->update($condition, $object);
                            echo "<script>alert('此出库单为报废呆料申请,系统已自动提交质检!');window.opener.window.show_page();window.close();  </script>";
                        }else{
                            echo "<script>alert('此出库单为报废呆料申请,系统已自动提交质检失败!');window.opener.window.show_page();window.close();  </script>";
                        }
                    }else{
                        echo "<script>alert('修改出库单成功!');window.opener.window.show_page();window.close();  </script>";
                    }
                }
                else if(isset($stockoutObject['relDocId']) && $stockoutObject['isRed'] == 1 && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF'){// 呆料报废其它出库单下推红字
                    $mainId = $stockoutObject['relDocId'];// 呆料报废红单的原单ID为对应蓝单的Id
                    $stockInDao = new model_stock_instock_stockin();
                    $inStockObj = $stockInDao->find(array('relDocId'=>$mainId));

                    if($inStockObj){
                        // 关闭关联的待报废呆料入库单
                        $stockInDao->closeIdleScrapInStock($inStockObj['id']);

                        // 如果有关联的待报废呆料出库单则删掉
                        $outStockRelDocId = $inStockObj['id'];// 待报废呆料出库单上级入库单ID
                        $outStockObj = $this->service->findAll(array('relDocId'=>$outStockRelDocId));
                        if($outStockObj){
                            foreach ($outStockObj as $outStockObjV){
                                $this->service->deletes_d($outStockObjV['id']);
                            }
                        }
                    }
                    echo "<script>alert('红单下推成功,关联待报废入库单已关闭!');window.opener.window.show_page();window.close();  </script>";
                }
                else if ("audit" == $actType) {
					//					//推送海外系统的采购订单
					$service->pushERPorder($stockoutObject, $lastObj['docCode']);
					echo "<script>alert('审核出库单成功!单据编号为:" . $lastObj['docCode'] . "');window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('修改出库单成功!');window.opener.window.show_page(); window.close();  </script>";
				}

			}

			//			else {
			//				if ("audit" == $actType) {
			//					echo "<script>alert('审核出库单失败,请确认仓库物料的库存是否足够!');window.opener.window.show_page(); window.close();  </script>";
			//				} else {
			//					echo "<script>alert('修改出库单失败,请确认单据信息是否完整!'); window.opener.window.show_page();window.close();  </script>";
			//				}
			//			}
		} catch (Exception $e) {
			echo "<script>alert('操作失败!异常" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * 出库单反审核
	 */
	function c_cancelAudit() {
		try {
			$service = $this->service;
			$id = isset ($_POST['id']) ? $_POST['id'] : null;
			$docType = isset ($_POST['docType']) ? $_POST['docType'] : null;
			$stockoutStrategy = $service->stockoutStrategyArr[$docType];
			if ($service->ctCancelAudit($id, new $stockoutStrategy())) {
				echo 1;
			}
		} catch (Exception $e) {
			echo "操作失败!异常:" . $e->getMessage();
		}
	}
	/**********************核算部分*********************/

	/**
	 * 红字出库核算
	 */
	function c_toCalList() {
		//获取当前财务周期
		$rs = $this->service->rtThisPeriod_d();
		$this->assignFunc($rs);

		$this->view('listcal');
	}

	/**
	 * pagejson
	 */
	function c_calPageJson() {
		$service = $this->service;
		if($_POST['beginDate'] == '--01'){
			unset($_POST['beginDate']);
		}
		if($_POST['endDate'] == '--31'){
			unset($_POST['endDate']);
		}
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->list_d('select_callist');
		if ($rows && $_POST['ifshow'] == 0) {
			$rows = $service->filterRows_d($rows);
		}
		$rows = $this->sconfig->md5Rows($rows, 'mainId');
        // 合计处理
        if ($rows) {
            $sum = array('id' => 'nocheck', 'actOutNum' => 0, 'subCost' => '0', 'auditDate' => '合计');
            foreach ($rows as $v) {
                $sum['actOutNum'] = bcadd($v['actOutNum'], $sum['actOutNum']);
                $sum['subCost'] = bcadd($v['subCost'], $sum['subCost'], 2);
            }
            $rows[] = $sum;
        }
		$arr = array();
		$arr['collection'] = $rows;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 修改出库单页面
	 */
	function c_toEditCost() {
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());
		foreach ($stockoutObj as $key => $val) {
			if ($key == 'items') {
				$this->assign("stockoutItems", $service->showProAtEditCal($val, new $stockoutStrategy()));
			} else if ($key == 'packitems') {
				$this->assign("stockoutPackItems", $service->showPackItemAtEditCal($val));
			} else {
				$this->assign($key, $val);
			}
		}

		$this->assign("itemscount", count($stockoutObj['items']));
		$this->assign("packcount", count($stockoutObj['packitems']));
		$this->assign("relDocTypeName", $this->getDataNameByCode($stockoutObj['relDocType']));

		switch ($docType) {
			case 'CKSALES' :
				$this->view("sales-editcost");
				break;
			case 'CKPICKING' :
				$this->assign('otherSubjects', $this->getDataNameByCode($stockoutObj['otherSubjects']));
				$this->assign('pickingType', $this->getDataNameByCode($stockoutObj['pickingType']));
				$this->view("picking-editcost");
				break;
			case 'CKOTHER' :
				$this->assign('toUse', $this->getDataNameByCode($stockoutObj['toUse']));
				$this->view("other-editcost");
				break;
			default :
				break;
		}
	}

	/**
	 * 修改出库单
	 * @author huangzf
	 */
	function c_editCost() {
		$service = $this->service;
		$stockoutObject = $_POST[$this->objName];
		if ($service->editCost_d($stockoutObject)) {
			msgRf('保存成功');
		} else {
			msgRf('保存失败');
		}
	}

	/**
	 * 成本明细账 -选择页面
	 */
	function c_toDetailList() {
		//获取当前财务周期
		$rs = $this->service->rtThisPeriod_d();
		$this->assignFunc($rs);
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : 'CKSALES';
		$this->assign('docType', $docType);
		$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
		switch ($docType) {
			case 'CKSALES' :
				$this->view('sales-todetail');
				break;
			case 'CKPICKING' :
				$this->view('picking-todetail');
				break;
			case 'CKOTHER' :
				$this->view('other-todetail');
				break;
			default :
				break;
		}
	}

	/**
	 * 成本明细账
	 */
	function c_detailList() {
		//获取当前财务周期
		$this->assignFunc($_GET);
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : 'CKSALES';
		switch ($docType) {
			case 'CKSALES' :
				$this->view('sales-listdetail');
				break;
			case 'CKPICKING' :
				$this->view('picking-listdetail');
				break;
			case 'CKOTHER' :
				$this->view('other-listdetail');
				break;
			default :
				break;
		}
	}

	/**
	 * 更新出库核算的单价
	 */
	function c_updateProductPrice() {
		set_time_limit(0);
		echo $this->service->updateProductPrice_d($_POST);
	}

	/*******************************不确定单价单据维护***********************/
	/**
	 * 不确定单价单据维护
	 * 2011-06-17
	 */
	function c_unpriceCal() {
		$rs = $this->service->rtThisPeriod_d();
		$this->assignFunc($rs);
		$this->display('listcalunprice');
	}

	/***********************************核算部分完结********************************/

	/**
	 *
	 * 关联源单出库记录页面
	 */
	function c_relDocOutPage() {
		$relDocType = isset ($_GET['relDocType']) ? $_GET['relDocType'] : null; //出库关联源单类型
		$docId = isset ($_GET['docId']) ? $_GET['docId'] : null; //出库关联需求id
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null; //出库关联需求类型

        $canPrintOutRecord = ($relDocType == 'XSCKDLHT')? 1 : '';// 暂时只是合同查看出库单时可打单 PMS 143
        $this->assign("canPrintOutRecord", $canPrintOutRecord);

		$this->assign("relDocType", $relDocType);
		$this->assign("docId", $docId);
		$this->assign("docType", $docType);

		$this->view("reldocout-page");
	}

	/**
	 * 获取关联源单出库记录数据
	 *
	 */
	function c_relDocOutJson() {
		$service = $this->service;
        $service->setCompany(0); # 不启用公司
		$service->getParam($_REQUEST);
		unset ($service->searchArr['docType']);
		unset ($service->searchArr['docId']);
		$service->searchArr['docStatus'] = "YSH";
		switch ($service->searchArr['relDocType']) {
			case "XSCKDLHT" : //合同查看出库单
				$service->searchArr['docType'] = $_GET['docType'];
				$contractId = $_GET['docId'];
				//售后出库包含换货、赠送信息
				$contractIdSql = "sql: and (" .
					"(contractType='oa_contract_contract' and contractId='" . $contractId . "') " .
					"or (contractType='oa_contract_exchangeapply' and contractId in(select id from oa_contract_exchangeapply where contractId='" . $contractId . "')) " .
					"or (contractType='oa_present_present' and contractId in(select id from oa_present_present where orderId='" . $contractId . "')))";
				$service->searchArr['contractIdCondition'] = $contractIdSql;
				unset ($service->searchArr['relDocType']);
				break;
			case "XSCKZS" : //赠送查看出库单
				$service->searchArr['contractId'] = $_GET['docId'];
				$service->searchArr['contractType'] = 'oa_present_present';
				unset ($service->searchArr['relDocType']);
				break;
			case "XSCKFHJH" : //发货计划查看出库单
				$service->searchArr['relDocId'] = $_GET['docId'];
				break;
			case 'XSCKFHD' : //配件订单发货单
				$outShipDao = new model_stock_outplan_ship();
				$shipIdArr = $outShipDao->findIdArrByDocInfo($_GET['docId'], $_GET['docType']);
				if (count($shipIdArr) >= 1) {
					$service->searchArr['relDocIdArr'] = $shipIdArr;
				} else
					$service->searchArr['relDocIdArr'] = -1;
				break;
			default :
				break;
		}

		$rows = $service->page_d();
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
        $arr['pageSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 *
	 * 出库审核权限判断
	 * @param  $docType
	 */
	function checkAuditLimit($docType) {
		switch ($docType) {
			case 'CKSALES' :
				if ($this->service->this_limit['销售出库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'CKPICKING' :
				//审核权限判断
				if ($this->service->this_limit['领料出库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'CKOTHER' :
				//审核权限判断
				if ($this->service->this_limit['其他出库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'CKOTHERGH' :
				//审核权限判断
				if ($this->service->this_limit['其他出库审核']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			default :
				break;
		}
	}

	/**
	 *
	 * 出库反审核权限判断
	 * @param  $docType
	 */
	function c_cancelAuditLimit($docType) {
		$docType = isset ($_POST['docType']) ? $_POST['docType'] : null; //出库关联需求类型
		switch ($docType) {
			case 'CKSALES' :
				if ($this->service->this_limit['销售出库反审核']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'CKPICKING' :
				//审核权限判断
				if ($this->service->this_limit['领料出库反审核']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'CKOTHER' :
				//审核权限判断
				if ($this->service->this_limit['其他出库反审核']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
            case 'CKDLBF' :
                //审核权限判断
                if ($this->service->this_limit['待报废呆料出库反审核']) {
                    echo 1;
                } else {
                    echo 0;
                }
                break;
			default :
				break;
		}
	}

	/**
	 *
	 * 获取出库单关联单据清单物料对应的未执行数量
	 */
	function c_findRelDocNotExeNum() {
		$stockoutStrategy = $this->relDocTypeArr[$_POST['relDocType']];
		$relDocDao = new $stockoutStrategy();
		echo $relDocDao->getDocNotExeNum($_POST['relDocId'], $_POST['relDocItemId']);
	}

	/**
	 * 导出出库单EXCEL
	 */
	function c_exportExcel() {
		$dataArr = array();
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;
		$service->searchArr = array("docType" => $docType, "docStatus" => "YSH");
		$service->sort = "c.auditDate,oi.productCode";
		$dataArr = $service->listBySqlId("select_export");
		$relDocTypeArrList = $this->getDatadicts(array('XSCKYDLX'));
		$relDocTypeArr = $relDocTypeArrList['XSCKYDLX'];
		foreach ($dataArr as $key => $value) {
			foreach ($relDocTypeArr as $key1 => $relType) {
				if ($value['relDocType'] == $relType['dataCode']) {
					$dataArr[$key]['relDocType'] = $relType['dataName'];
				}
			}
			if ($value['isRed'] == "0") {
				$dataArr[$key]['isRed'] = "蓝色";
			} else {
				$dataArr[$key]['isRed'] = "红色";
			}
		}

		$dao = new model_stock_productinfo_importProductUtil();
		return $dao->exportOutStockExcel($dataArr);
	}

	/**
	 *
	 *跳转到导入2011年8月前的出库单页面
	 */
	function c_toUploadSalesOutExcel() {
		$this->display("sales-import");
	}

	/**
	 *
	 *导入2011年8月前的出库单
	 */
	function c_importSalesStockout() {
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil();
			$excelData = $dao->readExcelData($filename, $temp_name);
			spl_autoload_register('__autoload');
			$resultArr = $service->importSalesStockout($excelData);
			if ($resultArr)
				echo util_excelUtil::showResult($resultArr, "2011年8月前出库单导入结果", array("合同号", "结果"));

			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/*************************************存货分析 列表导出*********开始***************************************************/
	/**
	 * 领料出库明细表导出
	 */
	function c_outExportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
		//获取表头数据
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//表头Id数组


		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "红蓝字");
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//获取列表内容
		$searchArr['docStatus'] = 'YSH';
		$searchArr['docType'] = $_GET['docType'];
		$searchArr['beginDate'] = $_GET['beginDate'];
		$searchArr['endDate'] = $_GET['endDate'];
		$searchArr['pickCode'] = $_GET['pickCode'];
		$searchArr['deptCode'] = $_GET['deptCode'];
		$searchArr['docCode'] = $_GET['docCode'];
		$searchArr['toUseLike'] = $_GET['toUseLike'];
		$searchArr['iPattern'] = $_GET['iPattern'];
		$searchArr['iSerialnoName'] = $_GET['iSerialnoName'];
		$searchArr['iProductId'] = $_GET['iProductId'];
		$searchArr['iActOutNum'] = $_GET['iActOutNum'];
		$searchArr['iCost'] = $_GET['iCost'];
		$searchArr['iSubCost'] = $_GET['iSubCost'];
		foreach ($searchArr as $k => $v) {
			if (empty ($searchArr[$k])) {
				unset ($searchArr[$k]);
			}
		}
		$this->service->searchArr = $searchArr;
		$rows = $this->service->listBySqlId('select_callist');
		foreach ($rows as $key => $val) {
			if ($val['docStatus'] == 'YSH') {
				$rows[$key]['docStatus'] = "已审核";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "红字";
			} else {
				$rows[$key]['isRed'] = "蓝字";
			}
			//金额处理
			$rows[$key]['cost'] = number_format($val['cost'],2);
			$rows[$key]['subCost'] = number_format($val['subCost'],2);
		}
		//匹配导出列
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}

		return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, "领料出库明细表");
	}

	/**
	 * 其他出库明细表导出
	 */
	function c_otherExportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(600);
		//获取表头数据
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "红蓝字");
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//获取列表内容
		$searchArr['docStatus'] = 'YSH';
		$searchArr['docType'] = $_GET['docType'];
		$searchArr['beginDate'] = $_GET['beginDate'];
		$searchArr['endDate'] = $_GET['endDate'];
		$searchArr['pickCode'] = $_GET['pickCode'];
		$searchArr['deptCode'] = $_GET['deptCode'];
		$searchArr['customerId'] = $_GET['customerId'];
		$searchArr['docCode'] = $_GET['docCode'];
		$searchArr['toUseLike'] = $_GET['toUseLike'];
		$searchArr['iPattern'] = $_GET['iPattern'];
		$searchArr['iSerialnoName'] = $_GET['iSerialnoName'];
		$searchArr['iProductId'] = $_GET['iProductId'];
		$searchArr['iActOutNum'] = $_GET['iActOutNum'];
		$searchArr['iCost'] = $_GET['iCost'];
		$searchArr['iSubCost'] = $_GET['iSubCost'];
		foreach ($searchArr as $k => $v) {
			if (empty ($searchArr[$k])) {
				unset ($searchArr[$k]);
			}
		}
		$this->service->searchArr = $searchArr;
		$rows = $this->service->listBySqlId('select_callist');
		$datadictDao = new model_system_datadict_datadict();
		$toUseArr = $datadictDao->getDataDictList_d('CHUKUYT', array('isUse' => 0));
		foreach ($rows as $key => $val) {
			if ($val['docStatus'] == 'YSH') {
				$rows[$key]['docStatus'] = "已审核";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "红字";
			} else {
				$rows[$key]['isRed'] = "蓝字";
			}
			//出库用途
			$rows[$key]['toUse'] = isset($toUseArr[$val['toUse']]) ? $toUseArr[$val['toUse']] : '';
			//金额处理
			$rows[$key]['cost'] = number_format($val['cost'],2);
			$rows[$key]['subCost'] = number_format($val['subCost'],2);
		}
		//匹配导出列
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}

		return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, "其他出库明细表");
	}

	/**
	 * 销售成本明细表导出
	 */
	function c_salesExportExcel() {
		// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		set_time_limit(0);
		//获取表头数据
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//表头Id数组
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//表头Name数组
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "红蓝字");
		//表头数组
		$colArr = array_combine($colIdArr, $colNameArr);
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//获取列表内容
		$searchArr['docStatus'] = 'YSH';
		$searchArr['docType'] = $_GET['docType'];
		$searchArr['beginDate'] = $_GET['beginDate'];
		$searchArr['endDate'] = $_GET['endDate'];
		$searchArr['customerId'] = $_GET['customerId'];
		$searchArr['contractId'] = $_GET['contractId'];
		$searchArr['docCode'] = $_GET['docCode'];
		$searchArr['deptName'] = $_GET['deptName'];
		$searchArr['toUseLike'] = $_GET['toUseLike'];
		$searchArr['iPattern'] = $_GET['iPattern'];
		$searchArr['iSerialnoName'] = $_GET['iSerialnoName'];
		$searchArr['iProductId'] = $_GET['iProductId'];
		$searchArr['iActOutNum'] = $_GET['iActOutNum'];
		$searchArr['iCost'] = $_GET['iCost'];
		$searchArr['iSubCost'] = $_GET['iSubCost'];
		foreach ($searchArr as $k => $v) {
			if (empty ($searchArr[$k])) {
				unset ($searchArr[$k]);
			}
		}
		$this->service->searchArr = $searchArr;
		$rows = $this->service->listBySqlId('select_callist');
		$datadictDao = new model_system_datadict_datadict();
		$toUseArr = $datadictDao->getDataDictList_d('CHUKUYT', array('isUse' => 0));
		foreach ($rows as $key => $val) {
			if ($val['docStatus'] == 'YSH') {
				$rows[$key]['docStatus'] = "已审核";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "红字";
			} else {
				$rows[$key]['isRed'] = "蓝字";
			}
			//出库用途
			$rows[$key]['toUse'] = isset($toUseArr[$val['toUse']]) ? $toUseArr[$val['toUse']] : '';
			//金额处理
			$rows[$key]['cost'] = number_format($val['cost'],2);
			$rows[$key]['subCost'] = number_format($val['subCost'],2);
		}
		//匹配导出列
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, "销售成本明细表");
	}
	/*************************************存货分析 列表导出*********end***************************************************/

	/**
	 * 最新出库价
	 * 根据最新出库单(不为0,若有)更新本财务周期单价为0的出库单单价及金额
	 */
	function c_updateCostByNewest() {
		echo $this->service->updateCostByNewest_d($_POST);
	}

	/**
	 * 期初余额加权平均价
	 * 根据最新存货余额(不为0,若有)更新本财务周期单价为0的出库单单价及金额
	 */
	function c_updateCostByStockbalance() {
		echo $this->service->updateCostByStockbalance_d($_POST);
	}

    /**
     * 打印发货记录 PMS 143
     */
    function c_toPrintShipList() {
        $conDao = new model_contract_contract_contract();
        $equDao = new model_contract_contract_equ();
        $stockitemDao = new model_stock_outstock_stockoutitem();
        $dictDao = new model_system_datadict_datadict();
        $docId = isset($_GET['docId'])? $_GET['docId'] : '';
        $getFirstOutDateSql = "select MIN(c.auditDate) as outDate from oa_stock_outstock c where 1=1 and ( c.docStatus='YSH') and( c.docType='CKSALES') and (contractType='oa_contract_contract' and contractId='{$docId}') order by id DESC";
        if($docId != ''){
            $conArr = $conDao->get_d($docId);
            // 发货日期
            $outDateObj = $this->service->_db->getArray($getFirstOutDateSql);
            $outDate = ($outDateObj)? $outDateObj[0]['outDate'] : '';
            $this->assign("outDate", $outDate);
            // 单位名称
            $this->assign("customerName", $conArr['customerName']);

            $manConfigs = $dictDao->getDatadictsByParentCodes("NFSR");
            $auditMan = $createMan = "";
            if($manConfigs){
                foreach ($manConfigs['NFSR'] as $k => $v){
                    $auditMan = ($v['dataCode'] == 'NFSR-AUDITMAN')? $v['dataName'] : $auditMan;
                    $createMan = ($v['dataCode'] == 'NFSR-CREATEMAN')? $v['dataName'] : $createMan;
                }
            }

            // 审核人
            $this->assign("auditMan", $auditMan);
            // 制单人
            $this->assign("createMan", $createMan);

            // 物料明细
            $service = $this->service;
            $service->searchArr['docType'] = "CKSALES";
            $service->searchArr['relDocType'] = "XSCKDLHT";
            $contractId = $docId;
            //售后出库包含换货、赠送信息
            $contractIdSql = "sql: and (" .
                "(contractType='oa_contract_contract' and contractId='" . $contractId . "') " .
                "or (contractType='oa_contract_exchangeapply' and contractId in(select id from oa_contract_exchangeapply where contractId='" . $contractId . "')) " .
                "or (contractType='oa_present_present' and contractId in(select id from oa_present_present where orderId='" . $contractId . "')))";
            $service->searchArr['contractIdCondition'] = $contractIdSql;
            unset ($service->searchArr['relDocType']);
            $detailMainArr = $service->page_d();
            foreach($detailMainArr as $datailVal){
                $detailArrTmp[] = $stockitemDao->getItemByMainId($datailVal['id']);
            }

            $equCatchArr = $detailArr = $detailCatchArr = array();
            foreach($detailArrTmp as $a){
                foreach($a as $b){
                    $detailCatchArr[] = $b;
                }
            }

            foreach($detailCatchArr as $k => $v){
                if(!isset($equCatchArr[$v['productCode']])){
                    $index = count($detailArr);
                    $equCatchArr[$v['productCode']]['index'] = $index;
                    $equCatchArr[$v['productCode']]['num'] = $v['actOutNum'];
                    $detailArr[$index] = $v;
                }else{
                    $num = bcadd($equCatchArr[$v['productCode']]['num'],$v['actOutNum']);
                    $equCatchArr[$v['productCode']]['num'] = $num;
                    $detailArr[$equCatchArr[$v['productCode']]['index']]['actOutNum'] = $num;
                }
            }

            $totalNum = 0;
            $detailStr = "";
            if($detailArr){
                $index = 1;
                foreach ($detailArr as $k => $equ){
                    if($equ['actOutNum'] > 0){
                        $detailStr .= "<tr>
                        <td><input class='chkToPrint' value='{$equ['productName']}|PCS|{$equ['actOutNum']}' type='checkbox'></td>
                        <td>{$index}</td>
                        <td style=\"text-align: left;padding-left: 5px;\">{$equ['productName']}</td>
                        <td>PCS</td>
                        <td>{$equ['actOutNum']}</td>
                        <td><span> </span></td><td><span> </span></td>
                    </tr>";
                        $index += 1;
                        $totalNum += $equ['actOutNum'];
                    }
                }
            }
            $this->assign("detailStr", $detailStr);

            // 数量合计
            $this->assign("totalNum", $totalNum);
        }

        $this->view("print-docOutRecord");
    }
}