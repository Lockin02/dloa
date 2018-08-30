<?php
/**
 * @author Michael
 * @Date 2014年9月3日 16:55:34
 * @version 1.0
 * @description:生产领料申请单控制层
 */
class controller_produce_plan_picking extends controller_base_action {

	function __construct() {
		$this->objName = "picking";
		$this->objPath = "produce_plan";
		parent::__construct ();
	}

	/**
	 * 跳转到生产领料申请单tab
	 */
	function c_page() {
		$this->view('list-tab');
	}

	/**
	 * 重写pageJson
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->groupBy = "c.id";
		$rows = $service->page_d ('select_product');
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				// 是否可以从生产仓出库
				$itemIds = $this->service->checkOutNum_d($val['id']);
				if (count($itemIds) > 0) {
					$rows[$key]['isCanOut'] = 1;
				} else {
					$rows[$key]['isCanOut'] = 0;
				}

				// 是否可以进行退料
				$itemIds = $this->service->checkBackNum_d($val['id']);
				if (count($itemIds) > 0) {
					$rows[$key]['isCanBack'] = 1;
				} else {
					$rows[$key]['isCanBack'] = 0;
				}
			}
		}
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
	 * 跳转到生产领料申请单列表
	 */
	function c_pageList() {
		$this->assign('finish' ,isset($_GET['pickingType']) ? 'yes' : 'no');
		$this->assign('userId' ,$_SESSION['USER_ID']);
		$this->view('list');
	}

	/**
	 * 从生产计划跳转到查看生产领料tab
	 */
	function c_toPlanTab() {
		$this->assign('planId' ,$_GET['planId']);
		$this->view('plan-tab' );
	}

	/**
	 * 跳转到生产计划的生产领料申请单列表
	 */
	function c_toPlanPage() {
		$this->assign('planId' ,$_GET['planId']);
		$this->view('list-plan');
	}

	/**
	 * 跳转到生产计划的生产领料物料信息列表
	 */
	function c_toProductPage() {
		$this->assign('planId' ,$_GET['planId']);
		$this->view('list-product');
	}

	/**
	 * 跳转到领料管理tab
	 */
	function c_pageManage() {
		$this->view('list-tab-manage');
	}

	/**
	 * 跳转到待出库生产领料列表
	 */
	function c_pageListManage() {
		$this->assign('perform' ,isset($_GET['perform']) ? 'yes' : 'no');
		$this->view('list-manage');
	}

	/**
	 * 跳转到新增生产领料申请单页面
	 */
	function c_toAdd() {
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //单据类型

		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		$this->view ('add', true);
	}

	/**
	 * 生产计划跳转到生产领料申请单页面
	 */
	function c_toAddByPlan() {
		$planDao = new model_produce_plan_produceplan();
		$planObj = $planDao->get_d($_GET['planId']);
		$typeId = $this->service->get_table_fields('oa_stock_material_type' ," `code`='$planObj[productCode]' AND `deleted`='0' " ,'id');
		$this->assign('typeId' ,$typeId); //配置类型id
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //单据类型
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		if(strstr($_GET['planId'], ',')){// 多个计划单
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
	 * 生产计划跳转到生产领料申请单页面
	 */
	function c_toAddByPlanPlus() {
		$planDao = new model_produce_plan_produceplan();
		$planObj = $planDao->get_d($_GET['planId']);
		$typeId = $this->service->get_table_fields('oa_stock_material_type' ," `code`='$planObj[productCode]' AND `deleted`='0' " ,'id');
		$this->assign('typeId' ,$typeId); //配置类型id
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //单据类型
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		// 所属板块
		$this->showDatadicts(array('module' => 'HTBK'), null, true);
		if(strstr($_GET['planId'], ',')){// 多个计划单
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
	 * 原料计算跳转到生产领料申请单页面
	 */
	function c_toAddByMaterial() {
		$data = $_POST['data'][0];
		$bomConfigId = $data['bomConfigId']; //bom配置id
//echo "<pre>";
//print_R($data);
		//bom配置名字和编号
		$bomName = $this->service->get_table_fields('oa_stock_material_finished' ," id='$bomConfigId' " ,'name');
		$this->assign('relDocName' ,$bomName);
		$bomcode = $this->service->get_table_fields('oa_stock_material_finished' ," id='$bomConfigId' " ,'code');
		$this->assign('relDocCode' ,$bomcode);

		unset($data['bomConfigId']);
		if (is_array($data)) {
			$productDao = new model_stock_productinfo_productinfo();
			$productObjs = array();
			$rows = array(); //从表的数据
			foreach ($data as $key => $val) {
				if ($val > 0) {
					$tmp = array();
					if (empty($productObjs[$key])) {
						$productObj = $productDao->find(array('productCode' => $key));
						$productObjs[$key] = $productObj;
					}
					$numArr = $this->service->getProductNum_d($productObjs[$key]['productCode']);
					$tmp['JSBC']        = $numArr['JSBC']; //旧设备仓数量
					$tmp['KCSP']        = $numArr['KCSP']; //库存商品仓数量
					$tmp['SCC']         = $numArr['SCC'];  //生产仓数量
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

		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //单据类型
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		$this->view('add-material' ,true);
	}

	/**
	 * 新增对象操作
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
				msg('保存成功');
			}
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 跳转到编辑生产领料申请单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('docTypeCode' => 'SCLLLX') ,$obj['docTypeCode']); //单据类型
		$this->showDatadicts(array('module' => 'HTBK') ,$obj['module']); //所属板块
		if ($_GET['isCaculate']) {
			$this->view ('edit-caculate' ,true);
		} else {
			$this->view ('edit' ,true);
		}
	}

	/**
	 * 编辑对象操作
	 */
	function c_edit() {
		$this->checkSubmit();
		$actType = isset($_GET['actType']) ? true : false;
		$id = $this->service->edit_d($_POST[$this->objName]);
		if ($id) {
			if($actType) {
				succ_show('controller/produce/plan/ewf_index.php?actTo=ewfSelect&billId='.$id);
			} else {
				msg('保存成功');
			}
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 跳转到查看生产领料申请单tab页面
	 */
	function c_toViewTab() {
		$this->permCheck (); //安全校验
		$this->assign('id' ,$_GET['id']);
		$this->view ('view-tab' );
	}

	/**
	 * 跳转到查看生产领料申请单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('actType' ,isset($_GET['actType']) ? 'none' : '');
		$this->view ( 'view' );
	}

	/**
	 * 审批通过处理
	 */
	function c_dealAfterAudit() {
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['examines'] == "ok") {  //审批通过
				 $this->service->dealAfterAudit_d( $folowInfo['objId'] );
			}
		}
//		echo 111;
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 导出excel
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
				 ."alert('没有记录!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}
	}

	/**
	 * 获取所有数据(关联从表)返回json
	 */
	function c_listJsonProduct() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_product');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 根据物料编码获取旧设备仓、库存商品仓和生产仓数量
	 * @return Json格式（JSBC,KCSP,SCC）
	 */
	function c_getProductNum() {
		$productCode = $_POST['productCode'];
		$numArr = $this->service->getProductNum_d($productCode);
		echo util_jsonUtil::encode ( $numArr );
	}

	/**
	 * 跳转到从生产仓出库页面
	 */
	function c_toAddOut() {
		$this->permCheck (); //安全校验
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
			msg('没有满足条件的单据！');
		}
	}

	/**
	 * 从生产仓出库
	 */
	function c_addOut() {
		$this->checkSubmit();
		$rs = $this->service->addOut_d($_POST[$this->objName]);
		if ($rs) {
			msg('保存成功！');
		} else {
			msg('保存失败！');
		}
	}

	/**
	 * 物料计算跳转领料页面
	 */
	function c_toAddByCaculate() {
		$obj = $_POST[$this->objName];
		if (is_array($obj['item'])) {
			$productDao = new model_stock_productinfo_productinfo();
			$rows = array(); //从表的数据
			foreach ($obj['item'] as $key => $val) {
				if ($val['isDelTag'] != 1) {
					$numArr = $this->service->getProductNum_d($val['productCode']);
					$val['applyNum'] = $val['number'];  //申请数量
					$val['JSBC']     = $numArr['JSBC']; //旧设备仓数量
					$val['KCSP']     = $numArr['KCSP']; //库存商品仓数量
					$val['SCC']      = $numArr['SCC'];  //生产仓数量
					array_push($rows ,$val);
				}
			}
		}
		$this->assign('productJson' ,util_jsonUtil::encode($rows));

		$this->showDatadicts(array('docTypeCode' => 'SCLLLX')); //单据类型
		$this->assign('createName' ,$_SESSION['USERNAME']);
		$this->assign('createId' ,$_SESSION['USER_ID']);
		$this->assign('docDate' ,day_date);
		$this->view('add-caculate' ,true);
	}

	/**
	 * 物料计算后的新增
	 */
	function c_addCaculate() {
		$this->checkSubmit();
		$actType = isset($_GET['actType']) ? true : false;
		$id = $this->service->addCaculate_d($_POST[$this->objName]);
		if ($id) {
			if($actType) {
				succ_show('controller/produce/plan/ewf_index.php?actTo=ewfSelect&billId='.$id);
			} else {
				msg('保存成功');
				echo "<script type='text/javascript'>window.close();</script>";
			}
		} else {
			msg('保存失败');
		}
	}

	/**
	 * 跳转到生产退料页面
	 */
	function c_toAddBack() {
		$this->permCheck (); //安全校验
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
			msg('没有满足条件的单据！');
		}
	}

	/**
	 * 生产退料
	 */
	function c_addBack() {
		$this->checkSubmit();
		$rs = $this->service->addBack_d($_POST[$this->objName]);
		if ($rs) {
			msg('保存成功！');
		} else {
			msg('保存失败！');
		}
	}

	/**
	 * 列表高级查询
	 */
	function c_toSearch() {
		$this->view('search');
	}
 }