<?php

/**
 * @author huangzf
 * @Date 2011��5��14�� 9:44:17
 * @version 1.0
 * @description:���ⵥ������Ϣ���Ʋ�
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
	 * ��ת�����ⵥ������Ϣ
	 */
	function c_page() {
		$this->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 * ��ת���б�ҳ
	 */
	function c_toList() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		if ($this->service->this_limit['�ִ���⵼��']) {
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
	 * ��ת������������˻���-�鿴ҳ��-���۳���
	 */
	function c_toAwaitList() {
		$this->assign("id", $_GET['id']);
		$this->view("sales-awaitlist");
	}

	/**
	 * ��ת���������ݲ鿴ҳ��-��������
	 */
	function c_toListForExchange() {
		if ($this->service->this_limit['�ִ���⵼��']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->assign('exchangeId', $_GET['id']);
		$this->view("other-listforexchange");
	}

	/**
	 * �������ⵥҳ��
	 */
	function c_toAdd() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$this->assign('auditDate', day_date);
		//���Ȩ���ж�
		$this->checkAuditLimit($docType);
		// �������
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
			    // ��ȡ���ϱ�����Ҫ�����ı�ע��Ϣ
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
     * ��ת�������ϱ���ҳ��
     */
    function c_toScrapIdleStock() {
        $docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
        $id = isset ($_GET['id']) ? $_GET['id'] : null;
        $this->assign('auditDate', day_date);
        $stockInDao = new model_stock_instock_stockin();
        //���Ȩ���ж�
        $this->checkAuditLimit($docType);
        $service = $this->service;

        $relDocArr = $stockInDao->find(" id={$id}");
        $relDocId = isset($relDocArr['relDocId'])? $relDocArr['relDocId'] : '';
        $stockoutStrategy = $service->stockoutStrategyArr[$docType];
        $stockoutObj = $service->get_d($relDocId, new $stockoutStrategy());
        //���Ȩ���ж�
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

        // ȥ����Ӧ����ԭ����ID
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
        // �������
        $this->showDatadicts(array('module' => 'HTBK'), $stockoutObj['module'],true);
        $this->showDatadicts(array('isWarranty' => 'BXZK'), $stockoutObj['isWarranty'], true);

        $this->view("idleStock-add");
    }

    /**
     * ���ϱ��ϴ���
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
                /*s:--------------���Ȩ�޿���,�����������----------------*/
//                if ($docType == "CKOTHER") {
//                    if (!$service->this_limit['�����������']) {
//                        echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
//                        exit();
//                    }
//                }
                /*s:--------------���Ȩ�޿���,�����������----------------*/

                $stockoutObject['auditerName'] = $_SESSION['USERNAME'];
                $stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
            }

            $id = $service->add_d($stockoutObject);

            if($id){
                if ("audit" == $actType) {
                    $addObj = $service->find(array("id" => $id), null, 'docCode');
                    //���ͺ���ϵͳ�Ĳɹ�����
                    $service->pushERPorder($stockoutObject, $addObj['docCode']);
                    //����Ǻ���ERP��ͬ�����ʼ�֪ͨ����ֹ�
                    if (!empty($conArr['parentName'])) {
                        $infoArr['createName'] = $conArr['createName'];
                        $infoArr['createId'] = $conArr['createId'];
                        $infoArr['cid'] = $conArr['id'];
                        $infoArr['id'] = $id;
                        $infoArr['contractName'] = $conArr['contractName'];
                        $infoArr['contractCode'] = $conArr['contractCode'];

                        $this->service->sendToHWStorer_d($infoArr);
                    }

                    //�˶Գɹ�����������
                    succ_show('controller/stock/outstock/idleScrapEwf_index.php?actTo=ewfSelect&billId='.$id);
//                    echo "<script>alert('��˳��ⵥ�ɹ�!���ݱ��Ϊ:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
                }else {
                    echo "<script>alert('�������ⵥ�ɹ�!');window.opener.window.show_page();window.close();  </script>";
                }
            }
        }catch (Exception $e) {
            echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
        }
    }

    function c_idleScrapEdit(){
            try {
                $service = $this->service;
                $stockoutObject = $_POST[$this->objName];
                $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
                $docType = isset ($stockoutObject['docType']) ? $stockoutObject['docType'] : null;

                /*s:--------------���Ȩ�޿���,�����������----------------*/
                if ("audit" == $actType) {
//                    if ($docType == "CKOTHER") {
//                        if (!$service->this_limit['�����������']) {
//                            echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
//                            exit();
//                        }
//                    }
                    $stockoutObject['auditerName'] = $_SESSION['USERNAME'];
                    $stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
                }
                /*e:--------------���Ȩ�޿���,�����������----------------*/

                //��ֹ�����ظ����
                $lastObj = $service->find(array("id" => $stockoutObject['id']));
                if ($lastObj['docStatus'] == "YSH") {
                    echo "<script>alert('�����Ѿ����,�����޸�,��ˢ���б�!');window.close();</script>";
                    exit();
                }

                // ��鵥���Ƿ�ɱ���
                $stockIDao = new model_stock_instock_stockin();
                $getIdsSql = "select relDocId from oa_stock_instock where id = {$stockoutObject['relDocId']}";
                $inDocRelDocIdArr = $stockIDao->_db->getArray($getIdsSql);
                $inDocRelDocId = $inDocRelDocIdArr? $inDocRelDocIdArr[0]['relDocId'] : '';
                $ItemsArr = $stockIDao->getRelativeItemsCount($inDocRelDocId,"'YSH'");
                if(is_array($ItemsArr) && count($ItemsArr) > 0){
                    $hasEmptyEquNum = 0;// ���ϵ����������Ϊ0�ĸ���
                    foreach ($ItemsArr as $k => $v){
                        $hasEmptyEquNum += ($v['Num'] <= 0)? 1 : 0;
                    }
                    if($hasEmptyEquNum > 0){
                        echo "<script>alert('������������,��˶����й�������ⵥ�ĺ���������!');window.close();</script>";
                        exit();
                    }
                }

                if ($service->edit_d($stockoutObject)) {
                    if ("audit" == $actType) {
                        //���ͺ���ϵͳ�Ĳɹ�����
                        $service->pushERPorder($stockoutObject, $lastObj['docCode']);

                        succ_show('controller/stock/outstock/idleScrapEwf_index.php?actTo=ewfSelect&billId='.$stockoutObject['id']);
//                        echo "<script>alert('��˳��ⵥ�ɹ�!���ݱ��Ϊ:" . $lastObj['docCode'] . "');window.opener.window.show_page();window.close();  </script>";
                    } else {
                        echo "<script>alert('�޸ĳ��ⵥ�ɹ�!');window.opener.window.show_page(); window.close();  </script>";
                    }
                }
            }catch (Exception $e) {
                echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
            }
    }

    /**
     * ������ɺ�ص�����
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
	 * ���ƺ�ɫ���ⵥҳ��
	 */
	function c_toAddRed() {
		$this->permCheck();
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy ());
		//���Ȩ���ж�
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
		// �������
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
                if($stockoutObj['toUse'] == 'CHUKUDLBF'){// ���ϱ��ϵĳ���쵥ԭ��ID��������ID
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
     * ���������ϴ�����ⵥ
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
                // ���ⵥ״̬��Ϊ���(WSH)
                $this->service->update("id = {$id}", array('docStatus'=>'WSH'));

                // ���µ��ݵ����϶�Ӧ�����ϴ��ϲֵ�����
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
	 * ����������˻���-���ƺ������
	 */
	function c_toAddRedByAwait() {
		$id = $_GET['id'];    //�˻���id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//��ȡ�˻�����Ϣ����ʼ��
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

		// ������ͬ���
		$this->showDatadicts(array('module' => 'HTBK'), $contractObj['module'], true);
		//���Ȩ���ж�
		$this->checkAuditLimit("CKSALES");
		$this->view("sales-awaitred");
	}

	/**
	 * �ʲ�����-������ɫ���ⵥ
	 */
	function c_toAddBlueByAsset() {
		$id = $_GET['id'];    //���뵥id
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//��ȡ���뵥��Ϣ����ʼ��
		$requireinDao = new model_asset_require_requirein();
		$obj = $requireinDao->get_d($id);
		$this->assign('relDocTypeName', $this->getDataNameByCode('QTCKZCCK'));//Դ������
		$this->assign('relDocCode', $obj['requireCode']);//Դ�����Ϊ�������뵥��
		$this->assign('remark', $obj['remark']);//��ע
		$this->assign('applyId', $obj['applyId']);//������Ϊ����������
		$this->assign('applyName', $obj['applyName']);
		//��ȡ��ϸ����
		$itemDao = new model_asset_require_requireinitem();
		$item = $itemDao->getOutStockDetail_d($id);
		$this->assign('itemscount', count($item));
		//���Ȩ���ж�
		$this->checkAuditLimit("CKOTHER");
		$this->view("other-assetblue");
	}

	/**
	 * �ʲ�����-������ɫ���ⵥ
	 */
	function c_toAddBlueByAssetNew() {
		$id = $_GET['id'];    //���뵥id
		$ids = $_GET['ids'];    //��ϸid
		$this->assign('relDocId', $id);
		$this->assign('auditDate', day_date);
		//��ȡ���뵥��Ϣ����ʼ��
		$result = util_curlUtil::getDataFromAWS('asset', 'getProductTransferInfo', array(
			"productTransferId" => $id,"itemsId" => $ids
		));
		$productTransferInfo = util_jsonUtil::decode($result['data'], true);

		if(empty($productTransferInfo['data']['details'])){
			msgRf ( 'û�з�������Ҫ���������ϸ��������ϸ�Ƿ��Ѿ�ȫ�����Ƴ������û��ƥ���Ӧ���ϣ�' );
			exit ();
		}
		// ����ֵ����
		if ($productTransferInfo['data']['productTransferInfo']) {
			$this->assign('relDocTypeName', $this->getDataNameByCode('QTCKZCCK'));//Դ������
			$this->assign('relDocCode', $productTransferInfo['data']['productTransferInfo']['requireCode']);//Դ�����Ϊ�������뵥��
			$this->assign('remark', $productTransferInfo['data']['productTransferInfo']['remark']);//��ע
			$this->assign('applyId', $productTransferInfo['data']['productTransferInfo']['applyUserId']);//������
			$this->assign('applyName', $productTransferInfo['data']['productTransferInfo']['applyUser']);
			$this->assign('deptCode', $productTransferInfo['data']['productTransferInfo']['applyDeptId']);//���ڲ���
			$this->assign('deptName', $productTransferInfo['data']['productTransferInfo']['applyDept']);
		}

		$str = "";
		// �ӱ�ֵ����
		if ($productTransferInfo['data']['details']) {
			$itemDao = new model_asset_require_requireinitem();
			$str = $itemDao->showProAtStockOutNew_d($productTransferInfo['data']['details']);
			if($str == ""){
				msgRf ( 'û�з�������Ҫ���������ϸ��������ϸ�Ƿ��Ѿ�ȫ�����Ƴ������û��ƥ���Ӧ���ϣ�' );
				exit ();
			}
		}

		$this->assign('itemscount', count($productTransferInfo['data']['details']));
		$this->assign('itemsbody', $str);
		// �������
		$this->showDatadicts(array('module' => 'HTBK'), null, true);

		//���Ȩ���ж�
		$this->checkAuditLimit("CKOTHER");
		$this->view("other-assetbluenew");
	}

	/**
	 * ��������������ɫ���ⵥ
	 * @author huangzf
	 */
	function c_toBluePush() {
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$relDocType = isset ($_GET['relDocType']) ? $_GET['relDocType'] : null;
		$relDocId = isset ($_GET['relDocId']) ? $_GET['relDocId'] : null;
		$service = $this->service;
		//���Ȩ���ж�
		$this->checkAuditLimit($docType);
		// �������
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		switch ($docType) {
			case 'CKSALES' :
				$outPlanDao = new model_stock_outplan_outplan();
				$outPlanObj = $outPlanDao->get_d($relDocId);
				$outProDao = new model_stock_outplan_outplanProduct();
				$outItemArr = $outProDao->getItemByshipId_d($relDocId);

				// k3������ش���
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
				if ($outPlanObj['docType'] == "oa_contract_contract") { //��ͬ
					$contractDao = new model_contract_contract_contract();
					$contractObj = $contractDao->get_d($outPlanObj['docId']);
					$this->assign("chargeName", $contractObj['prinvipalName']);
					$this->assign("chargeCode", $contractObj['prinvipalId']);

                    $userinfo = $userDao->getUserById($contractObj['prinvipalId']);
                    $chargeDeptName = $userinfo['DEPT_NAME'];
                    $chargeDeptCode = $userinfo['DEPT_ID'];

					// ������ͬ���
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
				if($outPlanObj['docType'] == 'oa_present_present'){// ���͵��������˺Ͳ��������͵������˵���Ϣ ID2203 by huanghaojin 2016-11-14
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

				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$outItemArr = $productinfoDao->k3CodeFormatter_d($outItemArr);

				$this->assign("itemscount", count($outItemArr));
				$this->assign("stockoutItems", $outProDao->showAddOtherList($outItemArr));

				$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
				$this->showDatadicts(array('isWarranty' => 'BXZK'), $outPlanObj['isWarranty']);//���뱣��״��
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

				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$outItemArr = $productinfoDao->k3CodeFormatter_d($outItemArr);

				$this->assign("itemscount", count($outItemArr));
				$this->assign("stockoutItems", $relDetailDao->showAddOtherList($outItemArr));

				$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
				$this->showDatadicts(array('isWarranty' => 'BXZK'), null, true);
				$this->assign("relDocTypeName", $this->getDataNameByCode($relDocType));
				$this->assign("auditDate", day_date);
				/**
				 * �ʼ첿����Ⱦ
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
	 * ��������������ɫ���ⵥ
	 */
	function c_toAddByPicking() {
		$id = $_GET['id'];    //���ϵ�id
		$pickDao = new model_produce_plan_picking();
		$obj = $pickDao->get_d($id);
		$this->assign('relDocCode', $obj['docCode']); //Դ�����
		$this->assign('relDocId', $obj['id']); //Դ��id
		$this->assign('pickName', $obj['createName']); //������
		$this->assign('pickId', $obj['createId']);
		$this->assign('contractId', $obj['relDocId']); //��ͬ��Ϣ
		$this->assign('contractCode', $obj['relDocCode']);
		$this->assign('contractName', $obj['relDocName']);
		$this->assign('contractType', 'oa_contract_contract');

		$otherDao = new model_common_otherdatas();
		$userObj = $otherDao->getUserDatas($obj['createId']);
		$this->assign('deptName', $userObj['DEPT_NAME']); //�����˲���
		$this->assign('deptCode', $userObj['DEPT_ID']);

		$this->assign('auditDate', day_date); //��������
		$this->showDatadicts(array('pickingType' => 'LLLX')); //��������
		$this->showDatadicts(array('otherSubjects' => 'KJKM'), null, true); //��Ŀ����

		$relDocTypeName = $this->getDataNameByCode('LLCKSCRWD'); //Դ����������
		$this->assign('relDocTypeName', $relDocTypeName);
		$this->assign('relDocType', 'LLCKSCRWD');

		//����������Ϣ
		$taskDao = new model_produce_task_producetask();
		$taskObj = $taskDao->get_d($obj['taskId']);
		$this->assign('purpose', $taskObj['purpose']); //������;

		//���Ȩ���ж�
		$this->checkAuditLimit("CKPICKING");
		$this->showDatadicts(array('toUse' => 'CHUKUYT'), null, true);
		// �������
		$this->showDatadicts(array('module' => 'HTBK'), $obj['module'], true);

		$this->view("add-picking", true);
	}

	/**
	 * �޸ĳ��ⵥҳ��
	 */
	function c_toEdit() {
		$this->permCheck();
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());
		//���Ȩ���ж�
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
		// �������
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
                // ��ȡ���ϱ�����Ҫ�����ı�ע��Ϣ
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
	 * �鿴���ⵥҳ��
	 */
	function c_toView() {
		//		$this->permCheck();
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());

		//���Ƶ��� ���Ȩ��
		$stockoutObj['items'] = $service->filterWithoutField('����', $stockoutObj['items'], 'list', array('cost','salecost'));
		$stockoutObj['items'] = $service->filterWithoutField('���', $stockoutObj['items'], 'list', array('subCost','saleSubCost'));

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
	 * ���ⵥ��ӡҳ��
	 */
	function c_toPrint() {
		$this->permCheck();
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null;
		$service = $this->service;

		$stockoutStrategy = $service->stockoutStrategyArr[$docType];
		$stockoutObj = $service->get_d($id, new $stockoutStrategy());
        //���Ƶ��� ���Ȩ��
        $stockoutObj['items'] = $service->filterWithoutField('����', $stockoutObj['items'], 'list', array('cost','salecost'));
        $stockoutObj['items'] = $service->filterWithoutField('���', $stockoutObj['items'], 'list', array('subCost','saleSubCost'));

		$this->show->assign("finance",'�����');//Ĭ�ϻ��Ա�����

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
				//����Ա��ȡ��ͬ�����۸�����
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
	 * ��ӡ - ���ڳɱ���ת
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
	 * �鿴���к���ϸҳ��
	 */
	function c_toViewSerialno() {
		$serialnoNameStr = isset ($_GET['serialnoName']) ? $_GET['serialnoName'] : null;
		$this->assign("serialnoList", $this->service->showSerialno($serialnoNameStr));
		$this->view("serialno-view");
	}

	/**
	 * ��ȡ��ⵥ�б�ҳ������Json
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
	 *�߼�����ҳ��
	 */
	function c_toAdvancedSearch() {
		$this->assign("docType", $_GET['docType']);
		$this->view("search-advanced");
	}

	/**
	 * ��ɫ���ⵥ������ɫ���ⵥ��ʾģ��
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
	 * ��ⵥ���Ƴ��ⵥ��ʾģ��
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
	 * �������ⵥ
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

			/*s:--------------���Ȩ�޿���,�����������----------------*/
			if ("audit" == $actType) {
				if ($docType == "CKSALES") {
					if (!$service->this_limit['���۳������']) {
						echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKPICKING") {
					if (!$service->this_limit['���ϳ������']) {
						echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKOTHER") {
					if (!$service->this_limit['�����������']) {
						echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
						exit();
					}
				}
				$stockoutObject['auditerName'] = $_SESSION['USERNAME'];
				$stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------���Ȩ�޿���,�����������----------------*/

			$id = $service->add_d($stockoutObject);

			$infoArr['createName'] = $conArr['createName'];
			$infoArr['createId'] = $conArr['createId'];
			$infoArr['cid'] = $conArr['id'];
			$infoArr['id'] = $id;
			$infoArr['contractName'] = $conArr['contractName'];
			$infoArr['contractCode'] = $conArr['contractCode'];

            if ($id) {
                if($stockoutObject['isRed'] == 0 && $stockoutObject['stockName'] == '���ϲֿ�' && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF') {// ���ϴ��ϴ���(��ɫ����,���ϱ�����;,�����Ϊ���ϲ�)
                    $addObj = $service->find(array("id" => $id), null, 'docCode');
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityObj = array();// �����ʼ��ύ����
                    $qualityObj['supplierId'] = $stockoutObject['customerId'];
                    $qualityObj['supplierName'] = $stockoutObject['customerName'];
                    $qualityObj['relDocTypeName'] = '���ϱ���֪ͨ��';
                    $qualityObj['relDocType'] = 'ZJSQDLBF';
                    $qualityObj['relDocId'] = $id;
                    $qualityObj['relDocCode'] = $addObj['docCode'];
                    $qualityObj['workDetail'] = '';
                    $qualityObj['applyUserName'] = $_SESSION['USERNAME'];
                    $qualityObj['applyUserCode'] = $_SESSION['USER_ID'];
                    $qualityObj['items'] = array();

                    $getItemsDao = new model_stock_outstock_stockoutitem();
                    $relDocItemsArr = $getItemsDao->getItemByMainId($id);// ��ȡ���ⵥ���������Ϣ
                    if($relDocItemsArr && !empty($relDocItemsArr)){
                        foreach ($relDocItemsArr as $itemk => $itemv){
                            $equArr['productId'] = $itemv['productId'];
                            $equArr['productCode'] = $itemv['productCode'];
                            $equArr['productName'] = $itemv['productName'];
                            $equArr['pattern'] = $itemv['pattern'];
                            $equArr['unitName'] = $itemv['unitName'];
                            $equArr['checkTypeName'] = 'ȫ��';
                            $equArr['checkType'] = 'ZJFSQJ';
                            $equArr['qualityNum'] = $itemv['actOutNum'];
                            $equArr['maxNum'] = $itemv['actOutNum'];
                            $equArr['relDocItemId'] = $itemv['id'];
                            $equArr['serialId'] = $itemv['serialnoId'];
                            $equArr['serialName'] = $itemv['serialnoName'];
                            $qualityObj['items'][] = $equArr;
                        }

                        // ����ʼ�����
                        $qualityApply = $qualityapplyDao->add_d($qualityObj);

                        // ������������״̬Ϊ�ʼ���
                        if($qualityApply){
                            $condition = array("id" => $id);
                            $object = array("docStatus" => "ZJZ");
                            $service->update($condition, $object);
                            echo "<script>alert('�˳��ⵥΪ���ϴ�������,ϵͳ���Զ��ύ�ʼ�!');window.opener.window.show_page();window.close();  </script>";
                        }else{
                            echo "<script>alert('�˳��ⵥΪ���ϴ�������,ϵͳ���Զ��ύ�ʼ�ʧ��!');window.opener.window.show_page();window.close();  </script>";
                        }
                    }else{
                        echo "<script>alert('�������ⵥ�ɹ�!');window.opener.window.show_page();window.close();  </script>";
                    }
				}
				else if((isset($stockoutObject['orgId']) || isset($stockoutObject['relDocId'])) && $stockoutObject['isRed'] == 1 && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF'){// ���ϱ����������ⵥ���ƺ���
                    $mainId = isset($stockoutObject['orgId'])? $stockoutObject['orgId'] : $stockoutObject['relDocId'];// ���ϱ��Ϻ쵥��ԭ��IDΪ��Ӧ������Id
                    $stockInDao = new model_stock_instock_stockin();
                    $inStockObj = $stockInDao->find(array('relDocId'=>$mainId));

                    if($inStockObj){
                        // �رչ����Ĵ����ϴ�����ⵥ
                        $stockInDao->closeIdleScrapInStock($inStockObj['id']);

                        // ����й����Ĵ����ϴ��ϳ��ⵥ��ɾ��
                        $outStockRelDocId = $inStockObj['id'];// �����ϴ��ϳ��ⵥ�ϼ���ⵥID
                        $outStockObj = $this->service->findAll(array('relDocId'=>$outStockRelDocId));
                        if($outStockObj){
                            foreach ($outStockObj as $outStockObjV){
                                $this->service->deletes_d($outStockObjV['id']);
                            }
                        }
                    }
                    echo "<script>alert('�쵥���Ƴɹ�,������������ⵥ�ѹر�!');window.opener.window.show_page();window.close();  </script>";
                }
				else if ("audit" == $actType) {
                    $addObj = $service->find(array("id" => $id), null, 'docCode');
                    //���ͺ���ϵͳ�Ĳɹ�����
                    $service->pushERPorder($stockoutObject, $addObj['docCode']);
                    //����Ǻ���ERP��ͬ�����ʼ�֪ͨ����ֹ�
                    if (!empty($conArr['parentName'])) {
                        $this->service->sendToHWStorer_d($infoArr);
                    }

                    echo "<script>alert('��˳��ⵥ�ɹ�!���ݱ��Ϊ:" . $addObj['docCode'] . "'); window.opener.window.show_page();window.close();  </script>";
                }else {
					echo "<script>alert('�������ⵥ�ɹ�!');window.opener.window.show_page();window.close();  </script>";
				}
			}
			//			else {
			//				if ("audit" == $actType) {
			//					echo "<script>alert('��˳��ⵥʧ��,��ȷ�ϲֿ����ϵĿ���Ƿ��㹻!'); window.opener.window.show_page();window.close();  </script>";
			//				} else {
			//					echo "<script>alert('�������ⵥʧ��,��ȷ�ϵ�����Ϣ�Ƿ�����!');window.opener.window.show_page(); window.close();  </script>";
			//				}
			//			}
		} catch (Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * �޸ĳ��ⵥ
	 * @author huangzf
	 */
	function c_edit() {
		try {
			$service = $this->service;
			$stockoutObject = $_POST[$this->objName];
			$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
			$docType = isset ($stockoutObject['docType']) ? $stockoutObject['docType'] : null;

			/*s:--------------���Ȩ�޿���,�����������----------------*/
			if ("audit" == $actType) {
				if (is_numeric($stockoutObject['relDocId']) && strlen($stockoutObject['relDocId']) < 32 && $stockoutObject['relDocType'] == 'QTCKZCCK') {//Դ������Ϊ�ʲ�����,��OA���ݲ���У��
					$checkResult = $service->checkAudit_d($stockoutObject);//�����������Ƿ������д����
					if ($checkResult == 2) {
						echo "<script>alert('�ʲ�����ϼ����������������������ʵ!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKSALES") {
					if (!$service->this_limit['���۳������']) {
						echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKPICKING") {
					if (!$service->this_limit['���ϳ������']) {
						echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
						exit();
					}
				}
				if ($docType == "CKOTHER") {
					if (!$service->this_limit['�����������']) {
						echo "<script>alert('û��Ȩ�޽������!');window.close();</script>";
						exit();
					}
				}
				$stockoutObject['auditerName'] = $_SESSION['USERNAME'];
				$stockoutObject['auditerCode'] = $_SESSION['USER_ID'];
			}
			/*e:--------------���Ȩ�޿���,�����������----------------*/
			//��ֹ�����ظ����
			$lastObj = $service->find(array("id" => $stockoutObject['id']));
			if ($lastObj['docStatus'] == "YSH") {
				echo "<script>alert('�����Ѿ����,�����޸�,��ˢ���б�!');window.close();</script>";
				exit();
			}

			if ($service->edit_d($stockoutObject)) {
                if($stockoutObject['isRed'] == 0 && $stockoutObject['stockName'] == '���ϲֿ�' && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF') {// ���ϴ��ϴ���(��ɫ����,���ϱ�����;,�����Ϊ���ϲ�)
                    $qualityapplyDao = new model_produce_quality_qualityapply();
                    $qualityObj = array();// �����ʼ��ύ����
                    $qualityObj['supplierId'] = $stockoutObject['customerId'];
                    $qualityObj['supplierName'] = $stockoutObject['customerName'];
                    $qualityObj['relDocTypeName'] = '���ϱ���֪ͨ��';
                    $qualityObj['relDocType'] = 'ZJSQDLBF';
                    $qualityObj['relDocId'] = $stockoutObject['id'];
                    $qualityObj['relDocCode'] = $lastObj['docCode'];
                    $qualityObj['workDetail'] = '';
                    $qualityObj['applyUserName'] = $_SESSION['USERNAME'];
                    $qualityObj['applyUserCode'] = $_SESSION['USER_ID'];
                    $qualityObj['items'] = array();

                    $getItemsDao = new model_stock_outstock_stockoutitem();
                    $relDocItemsArr = $getItemsDao->getItemByMainId($stockoutObject['id']);// ��ȡ���ⵥ���������Ϣ
                    if(is_array($relDocItemsArr)){
                        foreach ($relDocItemsArr as $itemk => $itemv){
                            $equArr['productId'] = $itemv['productId'];
                            $equArr['productCode'] = $itemv['productCode'];
                            $equArr['productName'] = $itemv['productName'];
                            $equArr['pattern'] = $itemv['pattern'];
                            $equArr['unitName'] = $itemv['unitName'];
                            $equArr['checkTypeName'] = 'ȫ��';
                            $equArr['checkType'] = 'ZJFSQJ';
                            $equArr['qualityNum'] = $itemv['actOutNum'];
                            $equArr['maxNum'] = $itemv['actOutNum'];
                            $equArr['relDocItemId'] = $itemv['id'];
                            $equArr['serialId'] = $itemv['serialnoId'];
                            $equArr['serialName'] = $itemv['serialnoName'];
                            $qualityObj['items'][] = $equArr;
                        }

                        // ����ʼ�����
                        $qualityApply = $qualityapplyDao->add_d($qualityObj);

                        // ������������״̬Ϊ�ʼ���
                        if($qualityApply){
                            $condition = array("id" => $stockoutObject['id']);
                            $object = array("docStatus" => "ZJZ");
                            $service->update($condition, $object);
                            echo "<script>alert('�˳��ⵥΪ���ϴ�������,ϵͳ���Զ��ύ�ʼ�!');window.opener.window.show_page();window.close();  </script>";
                        }else{
                            echo "<script>alert('�˳��ⵥΪ���ϴ�������,ϵͳ���Զ��ύ�ʼ�ʧ��!');window.opener.window.show_page();window.close();  </script>";
                        }
                    }else{
                        echo "<script>alert('�޸ĳ��ⵥ�ɹ�!');window.opener.window.show_page();window.close();  </script>";
                    }
                }
                else if(isset($stockoutObject['relDocId']) && $stockoutObject['isRed'] == 1 && "audit" == $actType && $stockoutObject['toUse'] == 'CHUKUDLBF'){// ���ϱ����������ⵥ���ƺ���
                    $mainId = $stockoutObject['relDocId'];// ���ϱ��Ϻ쵥��ԭ��IDΪ��Ӧ������Id
                    $stockInDao = new model_stock_instock_stockin();
                    $inStockObj = $stockInDao->find(array('relDocId'=>$mainId));

                    if($inStockObj){
                        // �رչ����Ĵ����ϴ�����ⵥ
                        $stockInDao->closeIdleScrapInStock($inStockObj['id']);

                        // ����й����Ĵ����ϴ��ϳ��ⵥ��ɾ��
                        $outStockRelDocId = $inStockObj['id'];// �����ϴ��ϳ��ⵥ�ϼ���ⵥID
                        $outStockObj = $this->service->findAll(array('relDocId'=>$outStockRelDocId));
                        if($outStockObj){
                            foreach ($outStockObj as $outStockObjV){
                                $this->service->deletes_d($outStockObjV['id']);
                            }
                        }
                    }
                    echo "<script>alert('�쵥���Ƴɹ�,������������ⵥ�ѹر�!');window.opener.window.show_page();window.close();  </script>";
                }
                else if ("audit" == $actType) {
					//					//���ͺ���ϵͳ�Ĳɹ�����
					$service->pushERPorder($stockoutObject, $lastObj['docCode']);
					echo "<script>alert('��˳��ⵥ�ɹ�!���ݱ��Ϊ:" . $lastObj['docCode'] . "');window.opener.window.show_page();window.close();  </script>";
				} else {
					echo "<script>alert('�޸ĳ��ⵥ�ɹ�!');window.opener.window.show_page(); window.close();  </script>";
				}

			}

			//			else {
			//				if ("audit" == $actType) {
			//					echo "<script>alert('��˳��ⵥʧ��,��ȷ�ϲֿ����ϵĿ���Ƿ��㹻!');window.opener.window.show_page(); window.close();  </script>";
			//				} else {
			//					echo "<script>alert('�޸ĳ��ⵥʧ��,��ȷ�ϵ�����Ϣ�Ƿ�����!'); window.opener.window.show_page();window.close();  </script>";
			//				}
			//			}
		} catch (Exception $e) {
			echo "<script>alert('����ʧ��!�쳣" . $e->getMessage() . "');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * ���ⵥ�����
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
			echo "����ʧ��!�쳣:" . $e->getMessage();
		}
	}
	/**********************���㲿��*********************/

	/**
	 * ���ֳ������
	 */
	function c_toCalList() {
		//��ȡ��ǰ��������
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
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->list_d('select_callist');
		if ($rows && $_POST['ifshow'] == 0) {
			$rows = $service->filterRows_d($rows);
		}
		$rows = $this->sconfig->md5Rows($rows, 'mainId');
        // �ϼƴ���
        if ($rows) {
            $sum = array('id' => 'nocheck', 'actOutNum' => 0, 'subCost' => '0', 'auditDate' => '�ϼ�');
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
	 * �޸ĳ��ⵥҳ��
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
	 * �޸ĳ��ⵥ
	 * @author huangzf
	 */
	function c_editCost() {
		$service = $this->service;
		$stockoutObject = $_POST[$this->objName];
		if ($service->editCost_d($stockoutObject)) {
			msgRf('����ɹ�');
		} else {
			msgRf('����ʧ��');
		}
	}

	/**
	 * �ɱ���ϸ�� -ѡ��ҳ��
	 */
	function c_toDetailList() {
		//��ȡ��ǰ��������
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
	 * �ɱ���ϸ��
	 */
	function c_detailList() {
		//��ȡ��ǰ��������
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
	 * ���³������ĵ���
	 */
	function c_updateProductPrice() {
		set_time_limit(0);
		echo $this->service->updateProductPrice_d($_POST);
	}

	/*******************************��ȷ�����۵���ά��***********************/
	/**
	 * ��ȷ�����۵���ά��
	 * 2011-06-17
	 */
	function c_unpriceCal() {
		$rs = $this->service->rtThisPeriod_d();
		$this->assignFunc($rs);
		$this->display('listcalunprice');
	}

	/***********************************���㲿�����********************************/

	/**
	 *
	 * ����Դ�������¼ҳ��
	 */
	function c_relDocOutPage() {
		$relDocType = isset ($_GET['relDocType']) ? $_GET['relDocType'] : null; //�������Դ������
		$docId = isset ($_GET['docId']) ? $_GET['docId'] : null; //�����������id
		$docType = isset ($_GET['docType']) ? $_GET['docType'] : null; //���������������

        $canPrintOutRecord = ($relDocType == 'XSCKDLHT')? 1 : '';// ��ʱֻ�Ǻ�ͬ�鿴���ⵥʱ�ɴ� PMS 143
        $this->assign("canPrintOutRecord", $canPrintOutRecord);

		$this->assign("relDocType", $relDocType);
		$this->assign("docId", $docId);
		$this->assign("docType", $docType);

		$this->view("reldocout-page");
	}

	/**
	 * ��ȡ����Դ�������¼����
	 *
	 */
	function c_relDocOutJson() {
		$service = $this->service;
        $service->setCompany(0); # �����ù�˾
		$service->getParam($_REQUEST);
		unset ($service->searchArr['docType']);
		unset ($service->searchArr['docId']);
		$service->searchArr['docStatus'] = "YSH";
		switch ($service->searchArr['relDocType']) {
			case "XSCKDLHT" : //��ͬ�鿴���ⵥ
				$service->searchArr['docType'] = $_GET['docType'];
				$contractId = $_GET['docId'];
				//�ۺ�������������������Ϣ
				$contractIdSql = "sql: and (" .
					"(contractType='oa_contract_contract' and contractId='" . $contractId . "') " .
					"or (contractType='oa_contract_exchangeapply' and contractId in(select id from oa_contract_exchangeapply where contractId='" . $contractId . "')) " .
					"or (contractType='oa_present_present' and contractId in(select id from oa_present_present where orderId='" . $contractId . "')))";
				$service->searchArr['contractIdCondition'] = $contractIdSql;
				unset ($service->searchArr['relDocType']);
				break;
			case "XSCKZS" : //���Ͳ鿴���ⵥ
				$service->searchArr['contractId'] = $_GET['docId'];
				$service->searchArr['contractType'] = 'oa_present_present';
				unset ($service->searchArr['relDocType']);
				break;
			case "XSCKFHJH" : //�����ƻ��鿴���ⵥ
				$service->searchArr['relDocId'] = $_GET['docId'];
				break;
			case 'XSCKFHD' : //�������������
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
	 * �������Ȩ���ж�
	 * @param  $docType
	 */
	function checkAuditLimit($docType) {
		switch ($docType) {
			case 'CKSALES' :
				if ($this->service->this_limit['���۳������']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'CKPICKING' :
				//���Ȩ���ж�
				if ($this->service->this_limit['���ϳ������']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'CKOTHER' :
				//���Ȩ���ж�
				if ($this->service->this_limit['�����������']) {
					$this->assign("auditLimit", "1");
				} else {
					$this->assign("auditLimit", "0");
				}
				break;
			case 'CKOTHERGH' :
				//���Ȩ���ж�
				if ($this->service->this_limit['�����������']) {
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
	 * ���ⷴ���Ȩ���ж�
	 * @param  $docType
	 */
	function c_cancelAuditLimit($docType) {
		$docType = isset ($_POST['docType']) ? $_POST['docType'] : null; //���������������
		switch ($docType) {
			case 'CKSALES' :
				if ($this->service->this_limit['���۳��ⷴ���']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'CKPICKING' :
				//���Ȩ���ж�
				if ($this->service->this_limit['���ϳ��ⷴ���']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
			case 'CKOTHER' :
				//���Ȩ���ж�
				if ($this->service->this_limit['�������ⷴ���']) {
					echo 1;
				} else {
					echo 0;
				}
				break;
            case 'CKDLBF' :
                //���Ȩ���ж�
                if ($this->service->this_limit['�����ϴ��ϳ��ⷴ���']) {
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
	 * ��ȡ���ⵥ���������嵥���϶�Ӧ��δִ������
	 */
	function c_findRelDocNotExeNum() {
		$stockoutStrategy = $this->relDocTypeArr[$_POST['relDocType']];
		$relDocDao = new $stockoutStrategy();
		echo $relDocDao->getDocNotExeNum($_POST['relDocId'], $_POST['relDocItemId']);
	}

	/**
	 * �������ⵥEXCEL
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
				$dataArr[$key]['isRed'] = "��ɫ";
			} else {
				$dataArr[$key]['isRed'] = "��ɫ";
			}
		}

		$dao = new model_stock_productinfo_importProductUtil();
		return $dao->exportOutStockExcel($dataArr);
	}

	/**
	 *
	 *��ת������2011��8��ǰ�ĳ��ⵥҳ��
	 */
	function c_toUploadSalesOutExcel() {
		$this->display("sales-import");
	}

	/**
	 *
	 *����2011��8��ǰ�ĳ��ⵥ
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
				echo util_excelUtil::showResult($resultArr, "2011��8��ǰ���ⵥ������", array("��ͬ��", "���"));

			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/*************************************������� �б���*********��ʼ***************************************************/
	/**
	 * ���ϳ�����ϸ����
	 */
	function c_outExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		//��ȡ��ͷ����
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//��ͷId����


		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "������");
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//��ȡ�б�����
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
				$rows[$key]['docStatus'] = "�����";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "����";
			} else {
				$rows[$key]['isRed'] = "����";
			}
			//����
			$rows[$key]['cost'] = number_format($val['cost'],2);
			$rows[$key]['subCost'] = number_format($val['subCost'],2);
		}
		//ƥ�䵼����
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}

		return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, "���ϳ�����ϸ��");
	}

	/**
	 * ����������ϸ����
	 */
	function c_otherExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(600);
		//��ȡ��ͷ����
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "������");
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//��ȡ�б�����
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
				$rows[$key]['docStatus'] = "�����";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "����";
			} else {
				$rows[$key]['isRed'] = "����";
			}
			//������;
			$rows[$key]['toUse'] = isset($toUseArr[$val['toUse']]) ? $toUseArr[$val['toUse']] : '';
			//����
			$rows[$key]['cost'] = number_format($val['cost'],2);
			$rows[$key]['subCost'] = number_format($val['subCost'],2);
		}
		//ƥ�䵼����
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}

		return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, "����������ϸ��");
	}

	/**
	 * ���۳ɱ���ϸ����
	 */
	function c_salesExportExcel() {
		// ִ��ʱ��Ϊ�����ƣ�phpĬ�ϵ�ִ��ʱ����30�룬ͨ��set_time_limit(0)�����ó��������Ƶ�ִ����ȥ
		set_time_limit(0);
		//��ȡ��ͷ����
		$colIdStr = $_GET['colId'];
		$colNameStr = $_GET['colName'];
		//��ͷId����
		$colIdArr = explode(',', $colIdStr);
		$colIdArr = array_filter($colIdArr);
		array_unshift($colIdArr, "isRed");
		//��ͷName����
		$colNameArr = explode(',', $colNameStr);
		$colNameArr = array_filter($colNameArr);
		array_unshift($colNameArr, "������");
		//��ͷ����
		$colArr = array_combine($colIdArr, $colNameArr);
		if($_GET['beginDate'] == '--01'){
			unset($_GET['beginDate']);
		}
		if($_GET['endDate'] == '--31'){
			unset($_GET['endDate']);
		}
		//��ȡ�б�����
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
				$rows[$key]['docStatus'] = "�����";
			}
			if ($val['isRed'] == '1') {
				$rows[$key]['isRed'] = "����";
			} else {
				$rows[$key]['isRed'] = "����";
			}
			//������;
			$rows[$key]['toUse'] = isset($toUseArr[$val['toUse']]) ? $toUseArr[$val['toUse']] : '';
			//����
			$rows[$key]['cost'] = number_format($val['cost'],2);
			$rows[$key]['subCost'] = number_format($val['subCost'],2);
		}
		//ƥ�䵼����
		$dataArr = array();
		$colIdArr = array_flip($colIdArr);
		foreach ($rows as $key => $row) {
			foreach ($colIdArr as $index => $val) {
				$colIdArr[$index] = $row[$index];
			}
			array_push($dataArr, $colIdArr);
		}
		return model_stock_common_stockExcelUtil::exportExcelUtil($colArr, $dataArr, "���۳ɱ���ϸ��");
	}
	/*************************************������� �б���*********end***************************************************/

	/**
	 * ���³����
	 * �������³��ⵥ(��Ϊ0,����)���±��������ڵ���Ϊ0�ĳ��ⵥ���ۼ����
	 */
	function c_updateCostByNewest() {
		echo $this->service->updateCostByNewest_d($_POST);
	}

	/**
	 * �ڳ�����Ȩƽ����
	 * �������´�����(��Ϊ0,����)���±��������ڵ���Ϊ0�ĳ��ⵥ���ۼ����
	 */
	function c_updateCostByStockbalance() {
		echo $this->service->updateCostByStockbalance_d($_POST);
	}

    /**
     * ��ӡ������¼ PMS 143
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
            // ��������
            $outDateObj = $this->service->_db->getArray($getFirstOutDateSql);
            $outDate = ($outDateObj)? $outDateObj[0]['outDate'] : '';
            $this->assign("outDate", $outDate);
            // ��λ����
            $this->assign("customerName", $conArr['customerName']);

            $manConfigs = $dictDao->getDatadictsByParentCodes("NFSR");
            $auditMan = $createMan = "";
            if($manConfigs){
                foreach ($manConfigs['NFSR'] as $k => $v){
                    $auditMan = ($v['dataCode'] == 'NFSR-AUDITMAN')? $v['dataName'] : $auditMan;
                    $createMan = ($v['dataCode'] == 'NFSR-CREATEMAN')? $v['dataName'] : $createMan;
                }
            }

            // �����
            $this->assign("auditMan", $auditMan);
            // �Ƶ���
            $this->assign("createMan", $createMan);

            // ������ϸ
            $service = $this->service;
            $service->searchArr['docType'] = "CKSALES";
            $service->searchArr['relDocType'] = "XSCKDLHT";
            $contractId = $docId;
            //�ۺ�������������������Ϣ
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

            // �����ϼ�
            $this->assign("totalNum", $totalNum);
        }

        $this->view("print-docOutRecord");
    }
}