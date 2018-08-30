<?php

/**
 * @author huangzf
 * @Date 2011��5��9�� 22:10:00
 * @version 1.0
 * @description:�ֿ��ڳ������Ϣ
 */

class controller_stock_inventoryinfo_inventoryinfo extends controller_base_action {

	function __construct() {
		$this->objName = "inventoryinfo";
		$this->objPath = "stock_inventoryinfo";
		parent::__construct ();
	}

	/**
	 * @author huangzf
	 * �ڳ�����б�
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
	 * ���������Ϣҳ��
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
	 * �鿴�����Ϣҳ��
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
	 * ͬһ���ֿ����ϴ����ظ���У��
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
	 * ��Ʒ���ͱ���ҳ��
	 */
	function c_toReport() {
		$this->show->displayReports ( 'inventory_inventory' );
	}

	/**
	 * @author huangzf
	 * ��ʱ����ѯ�б�
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
	 * ���״̬��ѯҳ��
	 *
	 */
	function c_toSubItemPage() {
		$this->display ( 'subitem-list' );
	}

	/**
	 * ���״̬���ݲ�ѯ
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
	 * ���ݲ�ƷID�Ͳֿ����鿴�豸��Ϣ
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
	 * ��ȡ����б�Json��Ϣ
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
	 * ��ȡ��ʱ���Json��Ϣ
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
	 * ���ݲ�Ʒid���ֿ�id��ȡĳ�������Ϣ
	 */
	function c_getInTimeObj() {
		$service = $this->service;

		$service->getParam ( $_POST );
		$rows = $service->listBySqlId ("select_all");

		if(isset($_POST['objType'])){//�й������ͳ����
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
	 * ���ݲֿ⼰��Ʒ��Ϣ��ȡ�����Ϣ
	 */
	function c_getInventoryInfos() {
		$stockId = $_POST ['stockId'];
		$productIds = $_POST ['productIds']; //��Ʒ��,����
		$service = $this->service;
		$service->asc = false;
		$service->sort = 'id';
		$infos = $service->getInventoryInfos ( $stockId, $productIds );
		echo util_jsonUtil::encode ( $infos );
	}

	/**
	 * @desription ���ݲֿ���Ҳֿ��еĲ�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-1-10 ����02:02:13
	 * @qiaolong
	 */
	function c_getPdinfoByStockId() {
		$stockId = $_POST ['stockId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
	 * ɾ���ڳ���棬�����ؽ��
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
	 * ����Ƿ�����޸��ڳ����
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
	 * �ϴ��ڳ������ϢEXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	/**
	 *
	 *����EXCEL���ڳ������Ϣ
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
				echo util_excelUtil::showResult ( $resultArr, "�ڳ���浼����", array ("���ϱ��", "���" ) );

		//echo "<script>alert('����ɹ�!');self.parent.show_page();self.parent.tb_remove();</script>";
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 * ������ʱ�����ϢExcel
	 */
	function c_exportExcelInTime() {
		$service = $this->service;
		set_time_limit(0);
		$rows = $service->listBySqlId ( 'select_intimelist' );
		return model_stock_productinfo_importProductUtil::exportIntimeListExcel ( $rows );
	}

	/**
	 * ���ݲ�ƷId�����Ҳ�Ʒ��ִ�п�� -- ajax
	 * @author zengzx
	 * 2011��11��2�� 13:58:55
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
	 *��������id��ȡ���json����
	 */
	function c_getJsonByProductId(){
		$this->service->asc=false;
		$this->service->searchArr=array("productId"=>$_POST['productId']);
		echo  util_jsonUtil::encode($this->service->listBySqlId());
	}

	/**
	 *
	 *��������id��ȡ���json����
	 */
	function c_getNumByProductId(){
		echo  util_jsonUtil::encode($this->service->getStockNum($_POST['productId']));
	}
	
	/**
	 *
	 * ajaxt��ȡ����id��ȡ��ʱ���
	 */
	function c_getProActNum(){
		echo $this->service->getProActNum($_POST['productId']);
	}

	/**
	 * ajax��������id�����ͻ�ȡĬ�ϲֿ�Ŀ�ִ�п��
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
	  * ��������id��ȡ�����ϢJson
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
