<?php

/**
 * @author huangzf
 * @Date 2011年5月9日 22:10:00
 * @version 1.0
 * @description:仓库期初库存信息
 */

class controller_stock_inventoryinfo_inventoryinfo extends controller_base_action {

	function __construct() {
		$this->objName = "inventoryinfo";
		$this->objPath = "stock_inventoryinfo";
		parent::__construct ();
	}

	/**
	 * @author huangzf
	 * 期初库存列表
	 */
	function c_page() {
		$stockCode = isset ( $_GET ['stockCode'] ) ? $_GET ['stockCode'] : "";
		if (isset ( $_GET ['stockId'] )) {
			$this->show->assign ( 'stockId', $_GET ['stockId'] );
		} else {
			$this->show->assign ( 'stockId', "" );
		}
		if (isset ( $_GET ['stockName'] )) {
			$this->show->assign ( 'stockName', $_GET ['stockName'] );
		} else {
			$this->show->assign ( 'stockName', "" );
		}
		if (isset ( $_GET ['stockCode'] )) {
			$this->show->assign ( 'stockCode', $_GET ['stockCode'] );
		} else {
			$this->show->assign ( 'stockCode', $stockCode );
		}
		$this->display ( "list" );
	}

	/**
	 * @author huangzf
	 * 新增库存信息页面
	 */
	function c_toAdd() {
		if (isset ( $_GET ['stockId'] )) {
			$this->show->assign ( 'stockId', $_GET ['stockId'] );
		} else {
			$this->show->assign ( 'stockId', "" );
		}
		if (isset ( $_GET ['stockName'] )) {
			$this->show->assign ( 'stockName', $_GET ['stockName'] );
		} else {
			$this->show->assign ( 'stockName', "" );
		}
		if (isset ( $_GET ['stockCode'] )) {
			$this->show->assign ( 'stockCode', $_GET ['stockCode'] );
		} else {
			$this->show->assign ( 'stockCode', "" );
		}
		$this->display ( "add" );
	}

	/**
	 * @author huangzf
	 * 查看库存信息页面
	 */
	function c_view() {
		$inventoryinfo = $this->service->get_d ( $_GET ['id'] );
		foreach ( $inventoryinfo as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//		$inventoryinfo = $service->getIventoryByIdandCode($_GET['productId']);
		//
		//		foreach ($inventoryinfo as $key => $val) {
		//			if ($key == 'configurations') {
		//				$str = $this->service->showConfigurationsView($val);
		//				$this->show->assign('configurations', $str);
		//			} else {
		//				$this->show->assign('configurations', "");
		//				$this->show->assign($key, $val);
		//			}
		//		}


		$this->display ( "view" );
	}

	/**
	 * 同一个仓库物料代码重复性校验
	 */
	function c_checkProduct() {
		$stockId = isset ( $_GET ['stockId'] ) ? $_GET ['stockId'] : null;
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : null;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("stockId" => $stockId, "productId" => $productId );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * 产品类型报表页面
	 */
	function c_toReport() {
		$this->show->displayReports ( 'inventory_inventory' );
	}

	/**
	 * @author huangzf
	 * 即时库存查询列表
	 */
	function c_toInTimeList() {

		$stockId = isset ( $_GET ['stockId'] ) ? $_GET ['stockId'] : null;
		$stockName = isset ( $_GET ['stockName'] ) ? isset ( $_GET ['stockName'] ) : null;
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : null;
		$searchArr = array ("stockId" => $stockId, "productId" => $productId );
		$service = $this->service;
		$service->searchArr = $searchArr;
		$service->sort = 'productName';
		if ($stockId) {
			$this->show->assign ( 'stockId', $stockId );
		} else {
			$this->show->assign ( 'stockId', "" );
		}
		if ($stockName) {
			$this->show->assign ( 'stockName', $stockName );
		} else {
			$this->show->assign ( 'stockName', "" );
		}
		if ($productId) {
			$this->show->assign ( 'productId', $productId );
		} else {
			$this->show->assign ( 'productId', "" );
		}
		$this->display ( 'intime-list' );
	}

	/**
	 * 库存状态查询页面
	 *
	 */
	function c_toSubItemPage() {
		$this->display ( 'subitem-list' );
	}

	/**
	 * 库存状态数据查询
	 *
	 */
	function c_pageSubItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->getPageSubItem ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);;//count ( $rows );
		$arr ['page'] = $service->page;
		//		echo "<pre>";
		//		print_R($arr);
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 根据产品ID和仓库编码查看设备信息
	 */
	function c_viewInInvoiceapply() {
		$row = $this->service->find ( array ('stockCode' => $_GET ['stockCode'], 'productId' => $_GET ['productId'] ) );
		foreach ( $row as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->display ( 'viewInInvoiceapply' );
	}

	/***************************************************************/
	/***************************************************************/

	/**
	 * @author huangzf
	 * 获取库存列表Json信息
	 */
	function c_inventoryinfoJson() {
		$service = $this->service;
		$service->getParam ( $_POST );

		$stockId = isset ( $_GET ['stockId'] ) ? $_GET ['stockId'] : null;
		if ($stockId != "" && $stockId) {
			$service->searchArr ['stockId'] = $stockId;
		}

		$rows = $service->pageBySqlId ( 'select_default' );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @author huangzf
	 * 获取即时库存Json信息
	 */
	function c_pageInTimeJson() {
		$service = $this->service;
		$stockId = isset ( $_GET ['stockId'] ) ? $_GET ['stockId'] : null;
		$productId = isset ( $_GET ['productId'] ) ? $_GET ['productId'] : null;
		$service->getParam ( $_POST );

		if ($stockId != "" && $stockId) {
			$service->searchArr ['stockId'] = $stockId;
		}
		if ($productId != "" && $productId) {
			$service->searchArr ['productId'] = $productId;
		}
//		if (isset ( $service->searchArr ['stockId'] )) {
//			if ($service->searchArr ['stockId'] == "-1") {
//				unset ( $service->searchArr ['stockId'] );
//			}
//		}

		$rows = $service->pageBySqlId ( 'select_intimelist' );
//		foreach($rows as $key =>$value){
//			$value
//		}
//		print_r($rows);
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
        $arr ['sql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *
	 * 根据产品id及仓库id获取某条库存信息
	 */
	function c_getInTimeObj() {
		$service = $this->service;

		$service->getParam ( $_POST );
		$rows = $service->listBySqlId ("select_all");

		if(isset($_POST['objType'])){//有关联类型出库的
			$lockDao = new model_stock_lock_lock ();
			$lockArr=$lockDao->subLockNumAtOut($_POST['objType'],$_POST['objId'],$_POST['productId'],$_POST['stockId']);
			if($lockArr['lockNum']>0){
				$rows[0]['exeNum']=$rows[0]['exeNum']+$lockArr['lockNum'];
			}
		}

		if (is_array ( $rows ))
			echo util_jsonUtil::encode ( $rows [0] );
		else
			echo 0;
	}

	/*
	 * 根据仓库及产品信息获取库存信息
	 */
	function c_getInventoryInfos() {
		$stockId = $_POST ['stockId'];
		$productIds = $_POST ['productIds']; //产品以,隔开
		$service = $this->service;
		$service->asc = false;
		$service->sort = 'id';
		$infos = $service->getInventoryInfos ( $stockId, $productIds );
		echo util_jsonUtil::encode ( $infos );
	}

	/**
	 * @desription 根据仓库查找仓库中的产品信息
	 * @param tags
	 * @date 2011-1-10 下午02:02:13
	 * @qiaolong
	 */
	function c_getPdinfoByStockId() {
		$stockId = $_POST ['stockId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->searchArr ['stockId'] = $stockId;
		$rows = $service->pageBySqlId ( 'select_all' );
		//		$rows = $service->getPdinfoByStockId ($stockId);
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = count ( $rows );
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 删除期初库存，并返回结果
	 */
	function c_ajaxdeleteInv() {
		$service = $this->service;
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		if ($service->checkEditInv ( $id )) {
			if ($service->deletes_d ( $id ))
				echo 1;
		} else {
			echo 0;
		}

		//		echo $service->checkEditInv($id);
	}

	/**
	 * 检查是否可以修改期初库存
	 */
	function c_checkEditAjax() {
		$service = $this->service;
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		if ($service->checkEditInv ( $id )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 *
	 * 上传期初库存信息EXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	/**
	 *
	 *导入EXCEL中期初库存信息
	 */
	function c_importInventory() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importInventory ( $excelData );
			if ($resultArr)
				echo util_excelUtil::showResult ( $resultArr, "期初库存导入结果", array ("物料编号", "结果" ) );

		//echo "<script>alert('导入成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 * 导出即时库存信息Excel
	 */
	function c_exportExcelInTime() {
		$service = $this->service;
		set_time_limit(0);
		$rows = $service->listBySqlId ( 'select_intimelist' );
		return model_stock_productinfo_importProductUtil::exportIntimeListExcel ( $rows );
	}

	/**
	 * 根据产品Id，查找产品可执行库存 -- ajax
	 * @author zengzx
	 * 2011年11月2日 13:58:55
	 */
	function c_getExeNum(){
		if($_POST['id']){
				$exeNum=$this->service->getExeNums($_POST['id'],1);
			if($exeNum>0){
				echo $exeNum;
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}

	/**
	 *
	 *根据物料id获取库存json数据
	 */
	function c_getJsonByProductId(){
		$this->service->asc=false;
		$this->service->searchArr=array("productId"=>$_POST['productId']);
		echo  util_jsonUtil::encode($this->service->listBySqlId());
	}

	/**
	 *
	 *根据物料id获取库存json数据
	 */
	function c_getNumByProductId(){
		echo  util_jsonUtil::encode($this->service->getStockNum($_POST['productId']));
	}
	
	/**
	 *
	 * ajaxt获取物料id获取即时库存
	 */
	function c_getProActNum(){
		echo $this->service->getProActNum($_POST['productId']);
	}

	/**
	 * ajax根据物料id及类型获取默认仓库的可执行库存
	 */
	 function c_getExeNumFromDefault(){
	 	$id = $_POST['id'];
	 	$docType = $_POST['docType'];
	 	$sysDao = new model_stock_stockinfo_systeminfo();
	 	$stockArr = $sysDao->getStockByType_d($docType);
	 	$stockArr['stockId'];
	 	$number = $this->service->getInventoryInfoByStockAndProduct($stockArr['stockId'],$id);
	 	if( $number ){
	 		echo $number['exeNum'];
	 	}else{
	 		echo 0;
	 	}
	 }
	 
	 /**
	  * 
	  * 根据物料id获取库存信息Json
	  */
	 function c_getSouceInvent(){
	 	$productId=isset($_POST['productId'])?$_POST['productId']:null;
	 	$systemDao=new model_stock_stockinfo_systeminfo();
	 	$systemObj=$systemDao->get_d(1);
	 	$productDao=new model_stock_productinfo_productinfo();
	 	$productObj=$productDao->get_d($productId);
	 	$this->service->searchArr=array(
	 				"productId"=>$productId,
	 				"nstockId"=>$systemObj['borrowStockId']
	 	);
	 	$result['closeReson']=$productObj['closeReson'];
	 	$result['closeStatus']=$productObj['ext1'];
	 	$result['stock']=$this->service->listBySqlId();
	 	
	 	
	 	echo  util_jsonUtil::encode($result); 
	 }
}
