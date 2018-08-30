<?php
/**
 * 采购计划设备表控制类
 */
class controller_purchase_plan_equipment extends controller_base_action {

	function __construct() {
		$this->objName = "equipment";
		$this->objPath = "purchase_plan";
		parent::__construct ();
	}

	/*****************************************显示分割线********************************************/

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$object = $this->service->add_d ( $_POST [$this->objName] );
		if ($object) {
			msgBack2 ( "添加成功！" );
		} else {
			msgBack2 ( "添加失败！" );
		}
	}
	function c_pageJsonExecute() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true;
		$service->groupBy='p.id';
		$rows = $service->listBySqlId ( "equipment_execute_list" );
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 采购设备-计划统计列表
	 */
	function c_equList() {
		$equNumb = isset ( $_GET ['equNumb'] ) ? $_GET ['equNumb'] : "";
		$equName = isset ( $_GET ['equName'] ) ? $_GET ['equName'] : "";
		$idsArry = isset ( $_GET ['idsArry'] ) ? $_GET ['idsArry'] : "";
		$purchType = isset ( $_GET ['purchType'] ) ? $_GET ['purchType'] : "";
		$this->assign ( 'purchType', $purchType );
		$searchArr = array ();
		if ($equNumb != "") {
			$searchArr ['seachProductNumb'] = $equNumb;
		}
		if ($equName != "") {
			$searchArr ['productName'] = $equName;
		}
		if ($purchType == "contract_sales") {
			$searchArr ['purchTypeArr'] = "HTLX-XSHT,HTLX-ZLHT,HTLX-FWHT,HTLX-YFHT";
		} else if ($purchType == "borrow_present") { //借试用采购
			$searchArr ['purchTypeArr'] = "oa_borrow_borrow,oa_present_present";
		} else if ($purchType == "") { //显示全部采购类型的物料汇总表
		//$searchArr['purchTypeArr'] = "" ;
		} else {
			$searchArr ['purchTypeArr'] = $purchType;
		}
		$searchArr ['isNoAsset'] = "on";
		$service = $this->service;
		$service->getParam ( $_GET );
		$service->__SET ( 'searchArr', $searchArr );
		$service->__SET ( 'groupBy', "p.productId,p.productNumb" );

		$rows = $service->pageEqu_d ();
		$this->pageShowAssign ();

		$this->assign ( 'equNumb', $equNumb );
		$this->assign ( 'equName', $equName );
		$this->assign ( 'idsArry', $idsArry );
		$this->assign ( 'purchType', $purchType );
		$this->assign ( 'list', $this->service->showEqulist_s ( $rows ) );
		$this->display ( 'list-equ' );
		unset ( $this->show );
		unset ( $service );
	}

	/**
	 * 根据主表id获取从表清单
	 */
	function c_pageItemJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->asc = false;
		$rows = $service->list_d ();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		//		$arr = array ();
		//		$arr ['collection'] = $rows;
		//		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		//		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		//		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 根据批次号获取从表清单
	 */
	function c_getBatchEqu() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = true;
		$service->groupBy='p.id';
		$rows = $service->listBySqlId ( "equpment_batch" );
		if(!empty($rows)){
//			echo util_jsonUtil::encode ( $rows );
			//获取显示模板
			$htmlStr=$this->service->batchEquList($rows);
			echo $htmlStr;
		}else{
			echo 0;
		}
	}
	/**
	 * 采购申请物料汇总
	 *
	 */
	 function c_toAllEquList(){
	 	$this->display('allequ-list');
	 }

	 /**
	 * 采购申请物料汇总PageJson
	 *
	 */
	function c_pageJsonAllList() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$service->asc = true;
		$service->groupBy='p.id';
        $service->_isSetCompany =$service->_isSetMyList;//是否单据是否要区分公司,1为区分,0为不区分
		$rows = $service->pageBySqlId ( "equpment_batch" );
//		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
		/**
	 * 获取分页数据转成Json(物料确认)
	 */
	function c_pageJsonForConfirm() {
		$service = $this->service;
		$purchType=$_POST['purchTypeEqu'];
		unset($_POST['purchTypeEqu']);
		if($purchType=='oa_asset_purchase_apply'){
			$_POST['applyId']=$_POST['basicId'];
			unset($_POST['basicId']);
		}

		//$service->asc = false;
		if($purchType=='oa_asset_purchase_apply'){
			$applyEquDao=new model_asset_purchase_apply_applyItem();
			$applyEquDao->getParam ( $_POST );
			$rows = $applyEquDao->listBySqlId ( "select_applyItem_confirm" );
		}else{
			$service->getParam ( $_POST ); //设置前台获取的参数信息
			$rows = $service->listBySqlId ( "equipment_list" );
		}
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
	 *申请物料执行情况查询页面
	 *
	 */
	 function c_toProgressSearch(){
	 	$this->display('list-progress-search');
	 }
		/**
	 * 采购申请物料执行情况列表
	 */
	function c_toProgressList(){
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchvalue=isset($_GET['searchvalue'])?$_GET['searchvalue']:"";
		$searchArr = array ();
		if($searchvalue!=""){
			$searchArr[$searchCol] = $searchvalue;
		}
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['productNumbSear']!=""){
				$searchArr['seachProductNumb']=$object['productNumbSear'];
			}
			if($object['productName']!=""){
				$searchArr['productName']=$object['productName'];;
			}
			if($object['sourceNumb']!=""){
				$searchArr['sourceNumb']=$object['sourceNumb'];
			}
			if($object['customerName']!=""){
				$searchArr['customerName']=$object['customerName'];
			}
		}
		$searchArr['purchTypeArr']='oa_sale_order,oa_sale_lease,oa_sale_rdproject,oa_sale_service,oa_borrow_borrow,oa_present_present';
		$service = $this->service;
		/*s:-----------------排序控制-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------排序控制-------------------*/
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.id");

		$rows = $service->getProcessEqu_d();
		$this->pageShowAssign();


		$this->show->assign('searchvalue', $searchvalue);
		$this->assign ( 'searchCol', $searchCol );
		$this->assign('list', $this->service->showEquProgressList($rows));
		$this->display('list-progress');
		unset($this->show);
		unset($service);
	}

	/**
	 *申请物料执行情况查询页面
	 *
	 */
	 function c_toStockProgressSearch(){
	 	$this->display('list-stockprogress-search');
	 }
		/**
	 * 采购申请物料执行情况列表
	 */
	function c_toStockProgressList(){
		$object=isset($_POST['basic'])?$_POST['basic']:"";
		$searchCol=isset($_GET['searchCol'])?$_GET['searchCol']:"";//搜索字段
		$searchvalue=isset($_GET['searchvalue'])?$_GET['searchvalue']:"";
		$searchArr = array ();
		if($searchvalue!=""){
			$searchArr[$searchCol] = $searchvalue;
		}
		if(is_array($object)){
			if($object['sendBeginTime']!=""){
				$searchArr['sendBeginTime']=$object['sendBeginTime'];
			}
			if($object['sendEndTime']!=""){
				$searchArr['sendEndTime']=$object['sendEndTime'];
			}
			if($object['productNumbSear']!=""){
				$searchArr['seachProductNumb']=$object['productNumbSear'];
			}
			if($object['productName']!=""){
				$searchArr['productName']=$object['productName'];;
			}
			if($object['basicNumbSear']!=""){
				$searchArr['basicNumbSear']=$object['basicNumbSear'];
			}
		}
		$searchArr['purchTypeArr']='stock';
		$service = $this->service;
		/*s:-----------------排序控制-------------------*/
		$sortField=isset($_GET['sortField'])?$_GET['sortField']:"";
		$sortType=isset($_GET['sortType'])?$_GET['sortType']:"";
		if(!empty($sortField)){
			$service->sort=$sortField;
			$service->asc=$sortType;
		}
		$this->assign("sortType", $sortType);
		$this->assign("sortField", $sortField);
		/*e:-----------------排序控制-------------------*/
		$service->getParam($_GET);
		$service->__SET('searchArr', $searchArr);
		$service->__SET('groupBy', "p.id");

		$rows = $service->getProcessEqu_d();
		$this->pageShowAssign();


		$this->show->assign('searchvalue', $searchvalue);
		$this->assign ( 'searchCol', $searchCol );
		$this->assign('list', $this->service->showStockProgressList($rows));
		$this->display('list-stockprogress');
		unset($this->show);
		unset($service);
	}

	/**
	 * 合同交付页面获取所有数据返回json
	 */
	function c_deliveryListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
			$orderEquDao = new model_purchase_contract_equipment();
			$taskEauDao = new model_purchase_task_equipment();
			foreach ($rows as $key => $val) {
				$rows[$key]['dateHope'] = ''; //这里不显示采购任务的预计完成时间
				$rows[$key]['exeNum'] = $inventoryDao->getExeNumsByStockType($val['productId']); //库存数量
				$rows[$key]['onwayAmount'] = $orderEquDao->getEqusOnway(array('productId' => $val['productId'])); //在途数量

				$stockNumbTotal = 0; //入库数量
				$taskEquRows = $taskEauDao->findByPlanEquId($val['id']);
				if(is_array($taskEquRows)) {
					foreach($taskEquRows as $tKey => $tVal) {
						//获取订单物料信息
						$orderEquDao->sort = 'id';
						$orderEquRows = $orderEquDao->getEqusByTaskEquId($tVal['id']);
						if(is_array($orderEquRows)) {
							foreach($orderEquRows as $oKey => $oVal) {
								$stockNumbTotal = $stockNumbTotal + $oVal['amountIssued'];
								$rows[$key]['dateHope'] = $oVal['dateHope']; //采购计划的采购预计到货期
							}
						}
					}
				}
				$rows[$key]['stockNum'] = $stockNumbTotal;
			}
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

}
?>
