<?php

/**
 * @author Administrator
 * @Date 2011��5��4�� 19:49:09
 * @version 1.0
 * @description:����֪ͨ����Ϣ���Ʋ�
 */
class controller_purchase_arrival_arrival extends controller_base_action
{

	function __construct() {
		$this->objName = "arrival";
		$this->objPath = "purchase_arrival";
		parent::__construct();
	}

	/*
	 * ��ת������֪ͨ����Ϣ
	 */
	function c_page() {
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 *��ת����ִ������֪ͨ���б�
	 */
	function c_toarrivalCloseList() {
		$this->display('close-list');
	}

	/**
	 *��ת��δִ������֪ͨ���б��⹺��⣩
	 */
	function c_toArrivalStockList() {
		$datasDao = new model_common_otherdatas();
		$datasArr = $datasDao->getUserPriv("stock_instock_stockin", $_SESSION['USER_ID']);

		if ($datasArr ['�ִ���⵼��']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->display('stock-list');
	}

	/**
	 *��ת������֪ͨ���б��ʲ���⣩
	 */
	function c_toArrivalAssetList() {
		$this->display('asset-list');
	}

	/**
	 *
	 */
	function c_toAdd() {
		$this->assign("purchManId", $_SESSION['USER_ID']);
		$this->assign("purchManName", $_SESSION['USERNAME']);
		//        $this->show->assign("arrivalCode", "arrival" . generatorSerial());
		$this->showDatadicts(array('arrivalType' => 'ARRIVALTYPE'));
		//��ȡĬ���ʼ��ռ���
		$mailArr = $this->service->mailArr;
		$this->show->assign("TO_ID", $mailArr['sendUserId']);
		$this->show->assign("TO_NAME", $mailArr['sendName']);
		//���������ֵ�
		$this->showDatadicts(array('purchaseType' => 'cgfs'));
		$this->showDatadicts(array('checkType' => 'ZJFS'));
		$this->showDatadicts(array('qualityList' => 'CGZJSX'));
		$this->assign("arrivalDate", date("Y-m-d"));
		$this->display('add');
	}

	/**
	 *��ת���ҵ�����֪ͨ���б�
	 */
	function c_toArrivalMyList() {
		$this->display('my-list');
	}

	/**
	 *���ݲɹ���ͬ�������֪ͨ��
	 */
	function c_toAddByContract() {
		$this->permCheck($_GET['applyId'], 'purchase_contract_purchasecontract');//��ȫУ��
		$purAppId = $_GET['applyId'];
		$purAppCode = $_GET['applyCode'];
		$arrivalType = $_GET['storageType'];

		$arrivalAssInfo = $this->service->getAssInfoById($purAppId, $arrivalType);
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);

		if ($unarriProInfo[0]['purchType'] == "oa_asset_purchase_apply" || $unarriProInfo[0]['purchType'] == "asset") {
			$this->assign('arrivalTypeName', 'asset');

            //��ȡĬ���ʼ��ռ���
            $mailArr = $this->service->assetMailArr;

            $applyDao = new model_asset_purchase_apply_apply ();//��ȡ�ɹ�������Ϣ
            $applyRows = $applyDao->get_d($unarriProInfo[0]['sourceID']);
            if($applyRows){
                $mailArr['sendUserId'] = $mailArr['sendUserId'] . ',' .$applyRows['createId'];
                $mailArr['sendName'] = $mailArr['sendName'] . ',' .$applyRows['createName'];
            }

		} else {

			$this->assign('arrivalTypeName', 'ARRIVALTYPE1');
			//��ȡĬ���ʼ��ռ���
			$mailArr = $this->service->mailArr;
		}
		if ($unarriProInfo) {
			//��ȡ�������ϵ�����ɹ������ƺ�ID
			$applyers = $this->service->getApplyers_d($unarriProInfo);
			$arrivalprostr = $this->service->showPurchAppProInfo($unarriProInfo);
			$this->show->assign("purchnotarripro", $arrivalprostr);
		} else {
			$this->show->assign("purchnotarripro", "û��δ������Ϣ");
		}
		if (isset($applyers[1])) {    //���ռ��˽��кϲ�������Ψһ���ж�
			$mailUserId = $applyers[1] . $mailArr['sendUserId'] . ',' . $_SESSION['USER_ID'];
			$mailIdArr = explode(',', $mailUserId);
			$mailUser = array_unique($mailIdArr);
			$mailUserId = implode(',', $mailUser);
			$mailUserName = $applyers[0] . $mailArr['sendName'] . ',' . $_SESSION['USERNAME'];
			$mailNameArr = explode(',', $mailUserName);
			$mailUserArr = array_unique($mailNameArr);
			$mailUserName = implode(',', $mailUserArr);
		} else {
			$mailUserId = $mailArr['sendUserId'] . ',' . $_SESSION['USER_ID'];
			$mailIdArr = explode(',', $mailUserId);
			$mailUser = array_unique($mailIdArr);
			$mailUserId = implode(',', $mailUser);
			$mailUserName = $mailArr['sendName'] . ',' . $_SESSION['USERNAME'];
			$mailNameArr = explode(',', $mailUserName);
			$mailUserArr = array_unique($mailNameArr);
			$mailUserName = implode(',', $mailUserArr);
		}
		$this->show->assign("TO_ID", $mailUserId);
		$this->show->assign("TO_NAME", $mailUserName);
		//
		$this->assign("purchManId", $_SESSION['USER_ID']);
		$this->assign("purchManName", $_SESSION['USERNAME']);
		//        $this->show->assign("arrivalCode", "arrival" . generatorSerial());
		$this->show->assign("purchaseId", $purAppId);
		$this->show->assign("purchaseCode", $arrivalAssInfo['hwapplyNumb']);
		$this->show->assign("supplierName", $arrivalAssInfo['suppName']);
		$this->show->assign("supplierId", $arrivalAssInfo['suppId']);
		$this->show->assign("businessBelong", $arrivalAssInfo['businessBelong']);

        // ����ϼ�������˾�ֶ�
        $this->assign("formBelong",isset($arrivalAssInfo['formBelong'])? $arrivalAssInfo['formBelong'] : '');
        $this->assign("formBelongName",isset($arrivalAssInfo['formBelongName'])? $arrivalAssInfo['formBelongName'] : '');
        $this->assign("businessBelong",isset($arrivalAssInfo['businessBelong'])? $arrivalAssInfo['businessBelong'] : '');
        $this->assign("businessBelongName",isset($arrivalAssInfo['businessBelongName'])? $arrivalAssInfo['businessBelongName'] : '');

		$this->showDatadicts(array('arrivalType' => 'ARRIVALTYPE'));
		$this->assign("arrivalDate", date("Y-m-d"));
		//���������ֵ�
		$this->showDatadicts(array('purchaseType' => 'cgfs'));
		$this->showDatadicts(array('checkType' => 'ZJFS'));

		$this->show->display($this->objPath . '_' . $this->objName . '-addbycontract');
	}

	/**��д��ʼ�����󷽷�
	 *author can
	 *2011-2-22
	 */
	function c_init() {
		$this->permCheck();//��ȫУ��
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d($_GET ['id']);
		foreach ($returnObj as $key => $val) {
			$this->show->assign($key, $val);
			$equipmentDao = new model_purchase_arrival_equipment();
		}
		//��ȡ�����嵥
		$itemRows = $this->service->getEquipment_d($_GET ['id']);
		if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
			$purchType = $this->getDataNameByCode($returnObj['arrivalType']);
			$purchMode = $this->getDataNameByCode($returnObj['purchMode']);
			$this->assign('arrivalType', "�⹺���");
			$this->assign('purchMode', $purchMode);
            if($_GET ['type']=='close'){
                $list = $equipmentDao->showCloseViewList($itemRows);
                $this->show->assign('list', $list);
                $this->show->display($this->objPath . '_' . $this->objName . '-close-view');
            }else{
                $list = $equipmentDao->showViewList($itemRows);
                $this->show->assign('list', $list);
                $this->show->display($this->objPath . '_' . $this->objName . '-view');
            }
		} else {
			$this->showDatadicts(array('purchMode' => 'cgfs'), $returnObj ['purchMode']);
			$purchType = $this->getDataNameByCode($returnObj['arrivalType']);
			$this->assign('arrivalType1', $purchType);
			$list = $equipmentDao->showEditList($itemRows);
			$this->show->assign('list', $list);
			//			$length=count($itemRows); //��ȡ��������ĳ���
			//			for($i=0;$i<$length;$i++){
			//				$this->showDatadicts ( array ('checkType'.$i => 'ZJFS' ), $itemRows [$i]['checkType'] );
			//			}
			$this->show->display($this->objPath . '_' . $this->objName . '-edit');
		}
	}

	/**�ʲ�ȷ������ҳ��
	 *author can
	 *2011-2-22
	 */
	function c_toConfAsset() {
		$this->permCheck();//��ȫУ��
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d($_GET ['id']);
		foreach ($returnObj as $key => $val) {
			$this->show->assign($key, $val);
			$equipmentDao = new model_purchase_arrival_equipment();
		}
		//��ȡ�����嵥
		$itemRows = $this->service->getEquipment_d($_GET ['id']);
		$purchType = $this->getDataNameByCode($returnObj['arrivalType']);
		$purchMode = $this->getDataNameByCode($returnObj['purchMode']);
		$this->assign('arrivalType', "�⹺���");
		$this->assign('purchMode', $purchMode);
		$list = $equipmentDao->showAssetList($itemRows);
		$this->show->assign('list', $list);
		$this->show->display($this->objPath . '_' . $this->objName . '-asset-edit');
	}

	/**�ʲ�ȷ������
	 *author can
	 *2011-2-22
	 */
	function c_confAsset() {
		$id = $this->service->confAsset_d($_POST [$this->objName]);
		if ($id) {
			msg('ȷ�ϳɹ���');
		} else {
			msg('ȷ��ʧ�ܣ�');
		}
	}

	function c_add() {
		$id = $this->service->add_d($_POST [$this->objName]);
		if ($id) {
			if ($_GET['type']) {
				msgGo('����ɹ�', "index1.php?model=purchase_arrival_arrival&action=toAdd");
			} else {
				msg('����ɹ���');
			}

		} else {

			if ($_GET['type']) {
				msgGo('����ʧ��', "index1.php?model=purchase_arrival_arrival&action=toAdd");
			} else {
				msg('����ʧ�ܣ�');
			}
		}
	}
	/**
	 * �༭����֪ͨ��
	 */
	//	function c_edit () {
	//	}
	/**
	 * ��ȡ�����嵥
	 */
	function c_addItemList() {
		$purAppId = isset($_POST['contractId']) ? $_POST['contractId'] : null;
		$arrivalType = isset($_POST['storageType']) ? $_POST['storageType'] : null;
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);
		$arrivalprostr = $this->service->showPurchAppProInfo($unarriProInfo);
		echo $arrivalprostr;
	}

	/**
	 * ��ȡ�����嵥�����ɹ����ԣ�
	 */
	function c_itemListByAdd() {
		$purAppId = isset($_POST['contractId']) ? $_POST['contractId'] : null;
		$arrivalType = isset($_POST['storageType']) ? $_POST['storageType'] : null;
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);
		$arrivalprostr = $this->service->addPurchProInfo($unarriProInfo);
		echo $arrivalprostr;
	}

	/**��ȡ���������ƺ�ID*/
	function c_getApplyUser() {
		$purAppId = isset($_POST['contractId']) ? $_POST['contractId'] : null;
		$arrivalType = isset($_POST['storageType']) ? $_POST['storageType'] : null;
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);
		$applyUser = $this->service->getApplyers_d($unarriProInfo);
		echo util_jsonUtil::encode($applyUser);
		//		echo $applyUser[0];

	}

	/**
	 * �����ⵥʱ����̬��Ӵӱ�ģ��
	 */
	function c_getItemList() {
		$arrivalId = isset($_POST['arrivalId']) ? $_POST['arrivalId'] : null;
		$list = $this->service->getEquList_d($arrivalId);
		echo $list;
	}

    /**
     * �����ⵥʱ����̬��Ӵӱ�ģ��
     */
    function c_getItemListJson() {
        $arrivalId = isset($_POST['arrivalId']) ? $_POST['arrivalId'] : null;
        $list = $this->service->getEquListJson_d($arrivalId);
        echo $list;
    }

	/**ɾ������֪ͨ��
	 *author can
	 *2010-12-29
	 */
	function c_deletesInfo() {
		$deleteId = isset($_POST['id']) ? $_POST['id'] : exit;
		$delete = $this->service->deletesInfo_d($deleteId);
		//���ɾ���ɹ����1���������0
		if ($delete) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**�ж�����֪ͨ���Ƿ����ɾ��
	 *author can
	 *2010-12-29
	 */
	function c_deletesConfirm() {
		$deleteId = isset($_POST['id']) ? $_POST['id'] : exit;
		$qualityDao = new model_produce_quality_qualityapply();
		$rowCount = $qualityDao->checkExsitQuality($deleteId, 'ZJSQYDSL');
		//���ɾ���ɹ����1���������0
		if ($rowCount == 0) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**�޸�����֪ͨ��״̬��ajax��
	 *author can
	 *2010-12-29
	 */
	function c_changStateClose() {
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$flag = $this->service->updateArrivalForClose($id);
		//����ɹ����1���������0
		if ($flag) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ���ݲɹ�����ID����ת����ص�����֪ͨ���б�
	 */
	function c_toListByOrder() {
		$this->permCheck($_GET['obj']['objId'], 'purchase_contract_purchasecontract');//��ȫУ��
		$obj = $_GET['obj'];
		$this->assignFunc($obj);
		$this->assign('skey', $_GET['skey']);
		$this->display('contract-list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json-�ҵĲɹ�������֪ͨ��
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
		$service->_isSetCompany = $service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��ȡ��ҳ����ת��Json-���������֪ͨ��
	 */
	function c_stockPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$rows = $service->pageStock_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}


	/**
	 * ��ȡ��ҳ����ת��Json-�ҵĲɹ�������֪ͨ��
	 */
	function c_contractPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		//$service->asc = false;
		$service->_isSetCompany = $service->_isSetMyList;//�Ƿ񵥾��Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
		$rows = $service->page_d('select_equ');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**���ݲɹ�����������id.�鿴�����ϵ��������
	 *
	 */
	function c_itemView() {
		$equId = isset($_GET['equId']) ? $_GET['equId'] : null;
		$productName = isset($_GET['productName']) ? $_GET['productName'] : "";
		//���ݲɹ�����������ID���������е�����������Ϣ
		$equDao = new model_purchase_arrival_equipment();
		$equRows = $equDao->getItemByContractEquId_d($equId);
		$this->assign('list', $equDao->showArrivalList($equRows));
		$this->assign('productName', $productName);
		$this->display('item-view');
	}

	//���ݶ���ID��ȡ������Ϣģ��
	function c_getItemModel() {
		$arrivalId = isset($_POST['arrivalId']) ? $_POST['arrivalId'] : "";
		$equDao = new model_purchase_arrival_equipment();
		//��ȡ�����嵥
		$itemRows = $this->service->getEquipment_d($arrivalId);
		$list = $equDao->showItemModel($itemRows);       //��ȡ����ģ��
		echo $list;
	}

	/**
	 * ��������� tab
	 * create by kuangzw on 2013-07-17
	 */
	function c_arrivalTab() {
		$this->display('tab');
	}

	/**
	 *��ת��δִ������֪ͨ���б��⹺��⣩
	 * create by kuangzw on 2013-07-17
	 */
	function c_toArrivalStockDetailList() {
		$datasDao = new model_common_otherdatas();
		$datasArr = $datasDao->getUserPriv("stock_instock_stockin", $_SESSION['USER_ID']);

		if ($datasArr ['�ִ���⵼��']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->display('stock-list-detail');
	}

	/**
	 * ��ѯδִ��������ϸ����Դ
	 */
	function c_stockPageDetailJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		$rows = $service->page_d('select_detail');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows, 'arrivalId');
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��ת����������֪ͨҳ��
	 */
	function c_toReceiveNotice() {
		$this->assign("id", $_GET['id']);
		$this->view('receivenotice');
	}

	/**
	 * ��������֪ͨ
	 */
	function c_receiveNotice() {
		$obj = $_POST['mail'];
		$flag = $this->service->receiveNotice_d($obj);
		if ($flag) {
			msg("���ͳɹ���");
		} else {
			msg("����ʧ�ܣ�");
		}
	}
}

?>