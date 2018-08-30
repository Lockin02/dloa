<?php
/**
 * @author huangzf
 * @Date 2012年5月11日 星期五 13:40:44
 * @version 1.0
 * @description:生产申请单控制层
 */
class controller_produce_apply_produceapply extends controller_base_action {

	function __construct() {
		$this->objName = "produceapply";
		$this->objPath = "produce_apply";
		parent::__construct ();
	}

	/**
	 * 跳转到生产申请单列表
	 */
	function c_toPageTab() {
		$this->view ( 'list-tab' );
	}

	/**
	 * 跳转到生产申请单列表
	 */
	function c_page() {
		$applyType = isset ( $_GET ['applyType'] ) ? $_GET ['applyType'] : "";
		$this->assign ( "applyType", $applyType );
		$this->view ( 'list' );
	}

	/**
	 * 重写获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		if($_REQUEST['relDocTypeCode'] == 'allContract'){
			$relDocTypeCode = $_REQUEST['relDocTypeCode'];
			unset($_REQUEST['relDocTypeCode']);
		}
		$service->getParam ( $_REQUEST );
		if(isset($relDocTypeCode)){// 合同类型为所有合同
			$service->searchArr['relDocTypeCodeCondition'] = "sql: and relDocTypeCode in('HTLX-XSHT','HTLX-FWHT','HTLX-ZLHT','HTLX-YFHT')";
		}
		$service->groupBy = 'c.id';
		$rows = $service->page_d('select_page');

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 需求分页数据转成Json
	 */
	function c_pageJsonNeed() {
		$service = $this->service;
		if($_REQUEST['relDocTypeCode'] == 'allContract'){
			$relDocTypeCode = $_REQUEST['relDocTypeCode'];
			unset($_REQUEST['relDocTypeCode']);
		}
		$service->getParam ( $_REQUEST );
		if(isset($relDocTypeCode)){// 合同类型为所有合同
			$service->searchArr['relDocTypeCodeCondition'] = "sql: and relDocTypeCode in('HTLX-XSHT','HTLX-FWHT','HTLX-ZLHT','HTLX-YFHT')";
		}
		$service->groupBy = 'c.id';
		$rows = $service->page_d('select_need');

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到我的生产申请单列表
	 */
	function c_mypage() {
		$this->assign ( "userId", $_SESSION ['USER_ID'] );
		$this->view ( 'mylist' );
	}

	/**
	 * 跳转到生产需求tab
	 */
	function c_needTab() {
		$this->view ( 'need-tab' );
	}

	/**
	 * 跳转到生产需求列表（完成与未完成）
	 */
	function c_needPage() {
		$this->assign('issued' ,isset($_GET['issued']) ? 'yes' : 'no');
		$this->view ( 'list-need' );
	}

	/**
	 * 跳转到生产需求列表（下达与未下达）
	 */
	function c_taskPage() {
		$this->assign('issued' ,isset($_GET['issued']) ? 'yes' : 'no');
		$this->view ( 'list-task' );
	}

	/**
	 * 跳转到物料需求汇总列表
	 */
	function c_productPage() {
		$this->view ( 'list-product' );
	}

	/**
	 * 跳转到本周任务tab
	 */
	function c_weekTab() {
		$this->view ( 'week-tab' );
	}

	/**
	 * 跳转到发货计划列表
	 */
	function c_weekPage() {
		$weekDate = $this->service->getWeekDate_d();
		$this->assign('startWeekDate' ,$weekDate['startDate']);
		$this->assign('endWeekDate' ,$weekDate['endDate']);
		$this->view ( 'list-week' );
	}


	/**
	 * 从源单查看已下达的生产生请单
	 */
	function c_toPageFromDoc() {
		$this->assign ( "relDocId", isset($_GET ['relDocId'])?$_GET ['relDocId']:-1 );
		$this->assign ( "relDocType", isset($_GET ['relDocType'])?$_GET ['relDocType']:-1  );
		$this->view ( 'relDoc-list' );
	}

	/**
	 * 跳转到下达生产申请页面
	 */
	function c_toApply() {
		$relDocId = isset ( $_GET ['relDocId'] ) ? $_GET ['relDocId'] : null;
		$equIds = isset ( $_GET ['equIds'] ) ? $_GET ['equIds'] : null;

		$contractDao = new model_contract_contract_contract ();
		$obj = $contractDao->getContractInfo ( $relDocId, array ("equ" ) );
		//获取所有可执行id
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

		//从选中的id中过滤出可执行的id
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
				 ."alert('没有可执行记录!');window.close();"
				 ."</script>";
			exit();
		}

		$this->assignFunc($obj);

		$this->assign("applyUserName" ,$_SESSION['USERNAME']); //下单人
		$this->assign("applyUserId" ,$_SESSION['USER_ID']); //下单人ID
		$this->assign("applyDate" ,date("Y-m-d")); //下单日期

		$this->assign("equIds" ,substr($ids ,0 ,-1));
		$this->view('apply' ,true);
	}

	/**
	 * 重写add
	 */
	function c_add() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$rs = $this->service->add_d( $obj );
		if($rs) {
			msg( '下达成功！' );
		} else {
			msg( '下达失败！' );
		}
	}

	/**
	 * 跳转到新增生产申请单页面
	 */
	function c_toAdd() {
		$this->assign("applyDate" ,date("Y-m-d"));
		$this->assign("applyUserCode" ,$_SESSION['USER_ID']);
		$this->assign("applyUserName" ,$_SESSION['USERNAME']);
		$this->view('add' ,true);
	}

	/**
	 * 跳转到其他部门新增生产申请单页面
	 */
	function c_toAddDepartment() {
		$this->showDatadicts(array("relDocTypeCode" => 'SCYDLX')); //源单类型
		$this->assign("applyDate" ,date("Y-m-d"));
		$this->assign("applyUserId" ,$_SESSION['USER_ID']);
		$this->assign("applyUserName" ,$_SESSION['USERNAME']);
		$this->view('add-department' ,true);
	}

	/**
	 * 其他部门新增生产申请单
	 */
	function c_addDepartment() {
		$this->checkSubmit(); //验证是否重复提交
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$obj = $_POST[$this->objName];
		$id = $this->service->addDepartment_d( $obj );
		if($id) {
			if($actType) {
				succ_show('controller/produce/apply/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_SESSION["DEPT_ID"]);
			} else {
				msg( '保存成功！' );
			}
		} else {
			if($actType) {
				msg( '提交失败！' );
			} else {
				msg( '保存失败！' );
			}
		}
	}

	/**
	 * 跳转到编辑生产申请单页面
	 */
	function c_toEdit() {
		$obj = $this->service->get_d( $_GET ['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign("applyDate" ,date("Y-m-d"));

		if (isset($_GET["department"])) { //其他部门的编辑
			$this->showDatadicts(array("relDocTypeCode" => 'SCYDLX') ,$obj["relDocTypeCode"]); //源单类型
			$this->view('edit-department' ,true);
		} else {
			$this->view('edit' ,true);
		}
	}

	/**
	 * 重写edit
	 */
	function c_edit() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$rs = $this->service->edit_d( $obj );
		if($rs) {
			msg( '下达成功！' );
		} else {
			msg( '下达失败！' );
		}
	}

	/**
	 * 其他部门编辑生产申请单
	 */
	function c_editDepartment() {
		$this->checkSubmit(); //验证是否重复提交
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$obj = $_POST[$this->objName];
		$id = $this->service->editDepartment_d( $obj );
		if($id) {
			if($actType) {
				succ_show('controller/produce/apply/ewf_index.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_SESSION["DEPT_ID"]);
			} else {
				msg( '保存成功！' );
			}
		} else {
			if($actType) {
				msg( '提交失败！' );
			} else {
				msg( '保存失败！' );
			}
		}
	}

	/**
	 * 跳转到查看生产申请单页面
	 */
	function c_toView() {
		$service = $this->service;

		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->assign('showRelDoc' ,$service->getShowRelDoc_d($obj['relDocTypeCode'])); //显示合同或者源单
		$this->assign("actType" ,isset($_GET["actType"]) ? 'none' : ''); //审批页面隐藏按钮

		$contractDao = new model_contract_contract_contract();
		$contractObj = $contractDao->get_d($obj['relDocId']);
//		echo "<pre>";
//		print_r($contractObj) ;
		$this->assign('contractCreateId' ,$contractObj['createId']);
		$this->assign('areaPrincipalId' ,$contractObj['areaPrincipalId']);
		$this->view ( 'view' );
	}

	/**
	 * 跳转到查看生产申请单页面
	 */
	function c_toViewTab() {
		$this->assign("id" ,$_GET ['id']);
		if (isset($_GET["noSee"])) { //是否有查看任务和计划的权限
			$this->view ('view-tab-no');
		} else {
			$this->view ('view-tab');
		}
	}

	/**
	 * 跳转到变更生产申请单页面
	 */
	function c_toChange() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		if (isset($_GET["department"])) { //其他部门的变更
			$this->showDatadicts(array("relDocTypeCode" => 'SCYDLX') ,$obj["relDocTypeCode"]); //源单类型
			$this->view('change-department' ,true);
		} else {
			$this->view('change' ,true);
		}
	}

	/**
	 * 变更保存生产申请单
	 */
	function c_change() {
		$this->checkSubmit(); //验证是否重复提交
		try {
			$object = $_POST[$this->objName];
			$id = $this->service->change_d($object);
			succ_show('controller/produce/apply/ewf_change.php?actTo=ewfSelect&billId='.$id.'&billDept='.$_SESSION["DEPT_ID"]);
		} catch (Exception $e) {
			msgBack2("变更失败！失败原因：".$e->getMessage());
		}
	}

	/**
	 * 变更查看tab
	 */
	function c_toChangeTab(){
		$this->permCheck (); //安全校验
		$newId = $_GET['id'];
		$this->assign('id' ,$newId);

		$obj = $this->service->find(array('id' => $newId ) ,null ,'originalId');
		$this->assign('originalId' ,$obj['originalId']);

		$this->assign("actType" ,isset($_GET["actType"]) ? 'true' : 'false'); //审批页面隐藏按钮
		$this->display('change-tab');
	}

	/**
	 * 查看(变更需求-原需求)区别
	 */
	function c_toViewChange(){
		$id = $_GET['id'];
		$obj = $this->service->get_d( $id );
		$this->assignFunc($obj);

		$this->assign("actType" ,$_GET["actType"] == 'true' ? 'none' : ''); //审批页面隐藏按钮

		$contractDao = new model_contract_contract_contract();
		$contractObj = $contractDao->get_d($obj['relDocId']);
		$this->assign('contractCreateId' ,$contractObj['createId']);
		$this->assign('areaPrincipalId' ,$contractObj['areaPrincipalId']);

		$this->view('view-change');
	}

	/**
	 * 关闭生产申请
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
	 * 开启生产申请
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
	 * 跳转到打回生产申请单页面
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
	 * 打回
	 */
	function c_back() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$rs = $this->service->back_d($obj);
		if ($rs) {
			msg('打回成功！');
		} else {
			msg('打回失败！');
		}
	}

	/**
	 * 跳转到查看打回生产申请原因页面
	 */
	function c_toViewBack() {
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign('backReason' ,addslashes($obj['backReason'])); //对字符串的引号进行转义，防止前端读取数据错误

		$this->view("view-back");
	}

	/**
	 * 跳转到关闭生产申请单页面
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
	 * 关闭
	 */
	function c_close() {
		$this->checkSubmit(); //验证是否重复提交
		if ($this->service->close_d($_POST[$this->objName])) {
			msg('关闭成功！');
		} else {
			msg('关闭失败！');
		}
	}

	/**
	 * 跳转到查看关闭生产申请原因页面
	 */
	function c_toViewClose() {
		$obj = $this->service->get_d( $_GET['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}

		$this->view("view-close");
	}

	/**
	 * 审批处理
	 */
	function c_dealAfterAudit() {
		if (!empty($_GET['spid'])) {
			$otherdatas = new model_common_otherdatas();
			$folowInfo = $otherdatas->getWorkflowInfo($_GET['spid']);
			if($folowInfo['examines'] == "ok") {  //审批通过
				$this->service->dealAfterAudit_d($folowInfo['objId']);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 变更审批完成后处理方法
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
	 * 发货交付报表
	 */
	function c_toSendplanReport(){
		// $object=isset($_POST['apply'])?$_POST['apply']:"";
		$beginDate=isset($_GET['beginDate'])?$_GET['beginDate']:"";
		if($beginDate){//判断是否传入查询条件
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
	 * 跳转到查看物料汇总需求信息页面
	 */
	function c_toViewProduct() {
		$productDao = new model_stock_productinfo_productinfo();
		$productObj = $productDao->get_d($_GET['productId']);
		$this->assignFunc($productObj);
		$this->view('view-product');
	}

	/**
	 * 物料汇总需求查看页面Json
	 */
	function c_productListJson() {
		$service = $this->service;
		$service->searchArr['productId'] = $_POST['productId'];
		$service->groupBy = 'c.id';
		// $service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_product');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 跳转到物料组成页面
	 */
	function c_toStatisticsProduct() {
		$code = isset($_GET['code']) ? $_GET['code'] : '';
		if (empty($code)) {
			msg('该产品不存在！'); //退出
			echo "<script>window.close();</script>";
			return 0;
		}

		$productDao = new model_stock_productinfo_productinfo();
		$productObj = $productDao->find(array('productCode' => $code));
		if (empty($productObj)) {
			msg('该产品不存在！'); //退出
			echo "<script>window.close();</script>";
			return 0;
		}
		$this->assignFunc($productObj);

		$typeId = $this->service->get_table_fields('oa_stock_material_type' ," `code`='$code' AND `deleted`='0' " ,'id');
		if (empty($typeId)) {
			msg('该产品未配置BOM单！'); //退出
			echo "<script>window.close();</script>";
		}
		$this->assign('typeId' ,$typeId);

		$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
		$inventory = $inventoryDao->getExeNumsByStockType($productObj['id']); //库存数量
		$this->assign('inventory' ,$inventory);

		$this->assign('code' ,$code);
		$this->assign('num' ,$_GET['num'] ? $_GET['num'] : 1); //数量
		$this->view('statistics-product');
	}

	/**
	 * 物料组成查看页面Json
	 */
	function c_statisticsListJson() {
		$service = $this->service;
		$SQL = "SELECT * FROM oa_stock_material_semiFinished WHERE parentId='$_POST[typeId]' AND `deleted`='0' ORDER BY id ASC";
		$rows = $service->findSql($SQL);
		if (is_array($rows)) {
			$num = $_POST['num'] > 0 ? $_POST['num'] : 1; //数量关系
			$productDao = new model_stock_productinfo_productinfo();
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			$pickDao = new model_produce_plan_picking();
			foreach ($rows as $key => $val) {
				if ($val['name'] == '配件' || $val['name'] == '结构件') {
					$rows[$key]['pattern']       = ''; //规格型号
					$rows[$key]['unitName']      = ''; //单位
					$rows[$key]['num']           = $num; //成品跟半成品的关系暂且设定为一对一
					$rows[$key]['inventory']     = '';
					$rows[$key]['onwayAmount']   = '';
					$rows[$key]['simplifiedNum'] = '';
					$rows[$key]['isChildren']    = '1';
				} else {
					$productObj = $productDao->find(array('productCode' => $val['code']));
					$rows[$key]['pattern']  = $productObj['pattern']; //规格型号
					$rows[$key]['unitName'] = $productObj['unitName']; //单位
					$rows[$key]['num']      = $num; //成品跟半成品的关系暂且设定为一对一
					$rows[$key]['inventory']   = $inventoryDao->getExeNumsByStockType($productObj['id']); //库存数量
					$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $productObj['id'])); //在途数量
					$numArr = $pickDao->getProductNum_d($val['productCode']);
					$rows[$key]['JSBC'] = $numArr['JSBC']; //旧设备仓数量
					$rows[$key]['KCSP'] = $numArr['KCSP']; //库存商品仓数量
					$rows[$key]['SCC']  = $numArr['SCC']; //生产仓数量
					// 差异数=总需求-库存（库存商品仓/旧库仓/生产仓）数-已申请数
					$rows[$key]['simplifiedNum'] = $num - $numArr['JSBC'] - $numArr['KCSP'] - $numArr['SCC'] - $rows[$key]['onwayAmount']; // 差异数
					$rows[$key]['isChildren'] = '0';
				}
			}
		}

		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 配件、结构件组成查看页面Json
	 */
	function c_childrenListJson() {
		$service = $this->service;
		$materialDao = new model_stock_material_material();
		$rows = $materialDao->loadParts($_POST['parentId']);
		$showNum = $_POST['showNum'] ? true : false; //是否显示旧设备仓数量，库存商品仓数量，生产仓数量
		if (is_array($rows)) {
			$num = $_POST['num'] > 0 ? $_POST['num'] : 1; //数量关系
			$productDao = new model_stock_productinfo_productinfo();
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			$pickDao = new model_produce_plan_picking();
			foreach ($rows as $key => $val) {
				$productObj = $productDao->find(array('productCode' => $val['code']));
				$rows[$key]['productId']   = $productObj['id']; //id
				$rows[$key]['productName'] = $productObj['productName']; //名称
				$rows[$key]['productCode'] = $productObj['productCode']; //编码
				$rows[$key]['pattern']     = $productObj['pattern']; //规格型号
				$rows[$key]['unitName']    = $productObj['unitName']; //单位
				$rows[$key]['num']         = $val['total'] * $num; //需求数量
				$rows[$key]['inventory']   = $inventoryDao->getExeNumsByStockType($productObj['id']); //库存数量
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $productObj['id'])); //在途数量
				if ($showNum) {
					$numArr = $pickDao->getProductNum_d($val['code']);
					$rows[$key]['JSBC'] = $numArr['JSBC']; //旧设备仓数量
					$rows[$key]['KCSP'] = $numArr['KCSP']; //库存商品仓数量
					$rows[$key]['SCC']  = $numArr['SCC']; //生产仓数量
				} else {
					$rows[$key]['JSBC'] = 0; //旧设备仓数量
					$rows[$key]['KCSP'] = 0; //库存商品仓数量
					$rows[$key]['SCC']  = 0; //生产仓数量
				}
				// 差异数=总需求-库存（库存商品仓/旧库仓/生产仓）数-已申请数
				$rows[$key]['simplifiedNum'] = $num - $numArr['JSBC'] - $numArr['KCSP'] - $numArr['SCC'] - $rows[$key]['onwayAmount']; // 差异数
			}
		}

		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 跳转到编辑部分打回的生产申请单页面
	 */
	function c_toEditBack() {
		$obj = $this->service->get_d( $_GET ['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}

		$this->view('edit-back' ,true);
	}

	/**
	 * 编辑部分打回
	 */
	function c_editBack() {
		$this->checkSubmit(); //验证是否重复提交
		$obj = $_POST[$this->objName];
		$rs = $this->service->editBack_d( $obj );
		if($rs) {
			msg( '下达成功！' );
		} else {
			msg( '下达失败！' );
		}
	}

	/**
	 * 下达任务时验证
	 */
	function c_taskCheck() {
		echo util_jsonUtil::encode($this->service->taskCheck_d($_POST['id'],isset($_POST['itemIds']) ? $_POST['itemIds'] : null));
	}
}