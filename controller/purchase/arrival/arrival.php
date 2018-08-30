<?php

/**
 * @author Administrator
 * @Date 2011年5月4日 19:49:09
 * @version 1.0
 * @description:收料通知单信息控制层
 */
class controller_purchase_arrival_arrival extends controller_base_action
{

	function __construct() {
		$this->objName = "arrival";
		$this->objPath = "purchase_arrival";
		parent::__construct();
	}

	/*
	 * 跳转到收料通知单信息
	 */
	function c_page() {
		$this->show->display($this->objPath . '_' . $this->objName . '-list');
	}

	/**
	 *跳转到已执行收料通知单列表
	 */
	function c_toarrivalCloseList() {
		$this->display('close-list');
	}

	/**
	 *跳转到未执行收料通知单列表（外购入库）
	 */
	function c_toArrivalStockList() {
		$datasDao = new model_common_otherdatas();
		$datasArr = $datasDao->getUserPriv("stock_instock_stockin", $_SESSION['USER_ID']);

		if ($datasArr ['仓存入库导入']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->display('stock-list');
	}

	/**
	 *跳转到收料通知单列表（资产入库）
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
		//获取默认邮件收件人
		$mailArr = $this->service->mailArr;
		$this->show->assign("TO_ID", $mailArr['sendUserId']);
		$this->show->assign("TO_NAME", $mailArr['sendName']);
		//设置数据字典
		$this->showDatadicts(array('purchaseType' => 'cgfs'));
		$this->showDatadicts(array('checkType' => 'ZJFS'));
		$this->showDatadicts(array('qualityList' => 'CGZJSX'));
		$this->assign("arrivalDate", date("Y-m-d"));
		$this->display('add');
	}

	/**
	 *跳转到我的收料通知单列表
	 */
	function c_toArrivalMyList() {
		$this->display('my-list');
	}

	/**
	 *根据采购合同添加收料通知单
	 */
	function c_toAddByContract() {
		$this->permCheck($_GET['applyId'], 'purchase_contract_purchasecontract');//安全校验
		$purAppId = $_GET['applyId'];
		$purAppCode = $_GET['applyCode'];
		$arrivalType = $_GET['storageType'];

		$arrivalAssInfo = $this->service->getAssInfoById($purAppId, $arrivalType);
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);

		if ($unarriProInfo[0]['purchType'] == "oa_asset_purchase_apply" || $unarriProInfo[0]['purchType'] == "asset") {
			$this->assign('arrivalTypeName', 'asset');

            //获取默认邮件收件人
            $mailArr = $this->service->assetMailArr;

            $applyDao = new model_asset_purchase_apply_apply ();//获取采购申请信息
            $applyRows = $applyDao->get_d($unarriProInfo[0]['sourceID']);
            if($applyRows){
                $mailArr['sendUserId'] = $mailArr['sendUserId'] . ',' .$applyRows['createId'];
                $mailArr['sendName'] = $mailArr['sendName'] . ',' .$applyRows['createName'];
            }

		} else {

			$this->assign('arrivalTypeName', 'ARRIVALTYPE1');
			//获取默认邮件收件人
			$mailArr = $this->service->mailArr;
		}
		if ($unarriProInfo) {
			//获取订单物料的申请采购人名称和ID
			$applyers = $this->service->getApplyers_d($unarriProInfo);
			$arrivalprostr = $this->service->showPurchAppProInfo($unarriProInfo);
			$this->show->assign("purchnotarripro", $arrivalprostr);
		} else {
			$this->show->assign("purchnotarripro", "没有未收料信息");
		}
		if (isset($applyers[1])) {    //对收件人进行合并，并作唯一性判断
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

        // 添加上级归属公司字段
        $this->assign("formBelong",isset($arrivalAssInfo['formBelong'])? $arrivalAssInfo['formBelong'] : '');
        $this->assign("formBelongName",isset($arrivalAssInfo['formBelongName'])? $arrivalAssInfo['formBelongName'] : '');
        $this->assign("businessBelong",isset($arrivalAssInfo['businessBelong'])? $arrivalAssInfo['businessBelong'] : '');
        $this->assign("businessBelongName",isset($arrivalAssInfo['businessBelongName'])? $arrivalAssInfo['businessBelongName'] : '');

		$this->showDatadicts(array('arrivalType' => 'ARRIVALTYPE'));
		$this->assign("arrivalDate", date("Y-m-d"));
		//设置数据字典
		$this->showDatadicts(array('purchaseType' => 'cgfs'));
		$this->showDatadicts(array('checkType' => 'ZJFS'));

		$this->show->display($this->objPath . '_' . $this->objName . '-addbycontract');
	}

	/**重写初始化对象方法
	 *author can
	 *2011-2-22
	 */
	function c_init() {
		$this->permCheck();//安全校验
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d($_GET ['id']);
		foreach ($returnObj as $key => $val) {
			$this->show->assign($key, $val);
			$equipmentDao = new model_purchase_arrival_equipment();
		}
		//获取物料清单
		$itemRows = $this->service->getEquipment_d($_GET ['id']);
		if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
			$purchType = $this->getDataNameByCode($returnObj['arrivalType']);
			$purchMode = $this->getDataNameByCode($returnObj['purchMode']);
			$this->assign('arrivalType', "外购入库");
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
			//			$length=count($itemRows); //获取物料数组的长度
			//			for($i=0;$i<$length;$i++){
			//				$this->showDatadicts ( array ('checkType'.$i => 'ZJFS' ), $itemRows [$i]['checkType'] );
			//			}
			$this->show->display($this->objPath . '_' . $this->objName . '-edit');
		}
	}

	/**资产确认收料页面
	 *author can
	 *2011-2-22
	 */
	function c_toConfAsset() {
		$this->permCheck();//安全校验
		$returnObj = $this->objName;
		$returnObj = $this->service->get_d($_GET ['id']);
		foreach ($returnObj as $key => $val) {
			$this->show->assign($key, $val);
			$equipmentDao = new model_purchase_arrival_equipment();
		}
		//获取物料清单
		$itemRows = $this->service->getEquipment_d($_GET ['id']);
		$purchType = $this->getDataNameByCode($returnObj['arrivalType']);
		$purchMode = $this->getDataNameByCode($returnObj['purchMode']);
		$this->assign('arrivalType', "外购入库");
		$this->assign('purchMode', $purchMode);
		$list = $equipmentDao->showAssetList($itemRows);
		$this->show->assign('list', $list);
		$this->show->display($this->objPath . '_' . $this->objName . '-asset-edit');
	}

	/**资产确认收料
	 *author can
	 *2011-2-22
	 */
	function c_confAsset() {
		$id = $this->service->confAsset_d($_POST [$this->objName]);
		if ($id) {
			msg('确认成功！');
		} else {
			msg('确认失败！');
		}
	}

	function c_add() {
		$id = $this->service->add_d($_POST [$this->objName]);
		if ($id) {
			if ($_GET['type']) {
				msgGo('保存成功', "index1.php?model=purchase_arrival_arrival&action=toAdd");
			} else {
				msg('保存成功！');
			}

		} else {

			if ($_GET['type']) {
				msgGo('保存失败', "index1.php?model=purchase_arrival_arrival&action=toAdd");
			} else {
				msg('保存失败！');
			}
		}
	}
	/**
	 * 编辑收料通知单
	 */
	//	function c_edit () {
	//	}
	/**
	 * 获取物料清单
	 */
	function c_addItemList() {
		$purAppId = isset($_POST['contractId']) ? $_POST['contractId'] : null;
		$arrivalType = isset($_POST['storageType']) ? $_POST['storageType'] : null;
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);
		$arrivalprostr = $this->service->showPurchAppProInfo($unarriProInfo);
		echo $arrivalprostr;
	}

	/**
	 * 获取物料清单（带采购属性）
	 */
	function c_itemListByAdd() {
		$purAppId = isset($_POST['contractId']) ? $_POST['contractId'] : null;
		$arrivalType = isset($_POST['storageType']) ? $_POST['storageType'] : null;
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);
		$arrivalprostr = $this->service->addPurchProInfo($unarriProInfo);
		echo $arrivalprostr;
	}

	/**获取申请人名称和ID*/
	function c_getApplyUser() {
		$purAppId = isset($_POST['contractId']) ? $_POST['contractId'] : null;
		$arrivalType = isset($_POST['storageType']) ? $_POST['storageType'] : null;
		$unarriProInfo = $this->service->getAssUnArrPros($purAppId, $arrivalType);
		$applyUser = $this->service->getApplyers_d($unarriProInfo);
		echo util_jsonUtil::encode($applyUser);
		//		echo $applyUser[0];

	}

	/**
	 * 添加入库单时，动态添加从表模板
	 */
	function c_getItemList() {
		$arrivalId = isset($_POST['arrivalId']) ? $_POST['arrivalId'] : null;
		$list = $this->service->getEquList_d($arrivalId);
		echo $list;
	}

    /**
     * 添加入库单时，动态添加从表模板
     */
    function c_getItemListJson() {
        $arrivalId = isset($_POST['arrivalId']) ? $_POST['arrivalId'] : null;
        $list = $this->service->getEquListJson_d($arrivalId);
        echo $list;
    }

	/**删除收料通知单
	 *author can
	 *2010-12-29
	 */
	function c_deletesInfo() {
		$deleteId = isset($_POST['id']) ? $_POST['id'] : exit;
		$delete = $this->service->deletesInfo_d($deleteId);
		//如果删除成功输出1，否则输出0
		if ($delete) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**判断收料通知单是否可以删除
	 *author can
	 *2010-12-29
	 */
	function c_deletesConfirm() {
		$deleteId = isset($_POST['id']) ? $_POST['id'] : exit;
		$qualityDao = new model_produce_quality_qualityapply();
		$rowCount = $qualityDao->checkExsitQuality($deleteId, 'ZJSQYDSL');
		//如果删除成功输出1，否则输出0
		if ($rowCount == 0) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**修改收料通知单状态（ajax）
	 *author can
	 *2010-12-29
	 */
	function c_changStateClose() {
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$flag = $this->service->updateArrivalForClose($id);
		//如果成功输出1，否则输出0
		if ($flag) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 根据采购订单ID，跳转到相关的收料通知单列表
	 */
	function c_toListByOrder() {
		$this->permCheck($_GET['obj']['objId'], 'purchase_contract_purchasecontract');//安全校验
		$obj = $_GET['obj'];
		$this->assignFunc($obj);
		$this->assign('skey', $_GET['skey']);
		$this->display('contract-list');
	}

	/**
	 * 获取分页数据转成Json-我的采购，收料通知单
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		//$service->asc = false;
		$service->_isSetCompany = $service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 获取分页数据转成Json-待入库收料通知单
	 */
	function c_stockPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$rows = $service->pageStock_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}


	/**
	 * 获取分页数据转成Json-我的采购，收料通知单
	 */
	function c_contractPageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //设置前台获取的参数信息
		//$service->asc = false;
		$service->_isSetCompany = $service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->page_d('select_equ');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**根据采购订单的物料id.查看该物料的收料情况
	 *
	 */
	function c_itemView() {
		$equId = isset($_GET['equId']) ? $_GET['equId'] : null;
		$productName = isset($_GET['productName']) ? $_GET['productName'] : "";
		//根据采购订单的物料ID，查找所有的收料物料信息
		$equDao = new model_purchase_arrival_equipment();
		$equRows = $equDao->getItemByContractEquId_d($equId);
		$this->assign('list', $equDao->showArrivalList($equRows));
		$this->assign('productName', $productName);
		$this->display('item-view');
	}

	//根据订单ID获取物料信息模板
	function c_getItemModel() {
		$arrivalId = isset($_POST['arrivalId']) ? $_POST['arrivalId'] : "";
		$equDao = new model_purchase_arrival_equipment();
		//获取物料清单
		$itemRows = $this->service->getEquipment_d($arrivalId);
		$list = $equDao->showItemModel($itemRows);       //获取物料模板
		echo $list;
	}

	/**
	 * 待入库收料 tab
	 * create by kuangzw on 2013-07-17
	 */
	function c_arrivalTab() {
		$this->display('tab');
	}

	/**
	 *跳转到未执行收料通知单列表（外购入库）
	 * create by kuangzw on 2013-07-17
	 */
	function c_toArrivalStockDetailList() {
		$datasDao = new model_common_otherdatas();
		$datasArr = $datasDao->getUserPriv("stock_instock_stockin", $_SESSION['USER_ID']);

		if ($datasArr ['仓存入库导入']) {
			$this->assign("importLimit", "1");
		} else {
			$this->assign("importLimit", "0");
		}
		$this->display('stock-list-detail');
	}

	/**
	 * 查询未执行收料明细数据源
	 */
	function c_stockPageDetailJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		$rows = $service->page_d('select_detail');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows, 'arrivalId');
		$arr = array();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 跳转到发送收料通知页面
	 */
	function c_toReceiveNotice() {
		$this->assign("id", $_GET['id']);
		$this->view('receivenotice');
	}

	/**
	 * 发送收料通知
	 */
	function c_receiveNotice() {
		$obj = $_POST['mail'];
		$flag = $this->service->receiveNotice_d($obj);
		if ($flag) {
			msg("发送成功！");
		} else {
			msg("发送失败！");
		}
	}
}

?>