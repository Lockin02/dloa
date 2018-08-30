<?php
/*
 * Created on 2010-7-17
 *	产品基本信息Controller
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_stock_productinfo_productinfoAdd extends controller_base_action {

	function __construct() {
		$this->objName = "productinfoAdd";
		$this->objPath = "stock_productinfo";
		parent::__construct ();
	}
	/**
	 * 左树右物料信息列表
	 */
	function c_toProInfoTypePage() {
		$this->assign ( 'productUpdate', $this->service->this_limit ['更新'] );
		$this->view ( "list" );
	}

	/**
	 * 选择物料弹出的选择页面
	 *
	 */
	function c_selectProduct() {
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}

	/**
	 * 根据物料类型获取有物料信息列表数据
	 */
/*	function c_pageProInfoJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		if (isset ( $service->searchArr ['proTypeId'] )) {
			$proTypeDao = new model_stock_productinfo_producttype ();
			$node = $proTypeDao->get_d ( $service->searchArr ['proTypeId'] );
			$productTypes = $proTypeDao->getChildrenByNode ( $node, 'self' );

			$proTypeIdArr = array ();
			for($i = 0; $i < count ( $productTypes ); $i ++) {
				array_push ( $proTypeIdArr, $productTypes [$i] ['id'] );
			}
			$service->searchArr ['proTypeId'] = $proTypeIdArr;
		}

		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}*/

	/**
	 * 物料代码重复性校验
	 */
	function c_checkProInfo() {
		$productCode = isset ( $_GET ['productCode'] ) ? $_GET ['productCode'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("yproductCode" => $productCode );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}


	/**
	 * 新增物料基本信息页面
	 */
	function c_toAdd() {
		$proType = "";
		$proTypeId = "";
		$arrivalPeriod = "";
		$service = $this->service;
		if (isset ( $_GET ['proType'] )) {
			$proType = $_GET ['proType'];
			$proTypeId = $_GET ['proTypeId'];
			$arrivalPeriod = $_GET ['arrivalPeriod'];
		}
		$this->show->assign ( "proType", $proType );
		$this->show->assign ( "proTypeId", $proTypeId );
		$this->show->assign ( "arrivalPeriod", $arrivalPeriod );
		$proTypeDao = new model_stock_productinfo_producttype ();

		$this->showDatadicts ( array ('checkType' => 'ZJFS' ), "ZJFSQJ" );
		$this->showDatadicts ( array ('properties' => 'WLSX' ) );
		$this->showDatadicts ( array ('accountingCode' => 'KJKM' ) );
		$this->showDatadicts ( array ('ext1' => 'WLSTATUS' ) );
		$this->showDatadicts ( array ('statType' => 'TJLX' ) );
		$this->showDatadictsByName ( array ('unitName' => 'PROUNIT' ) );
		$this->showDatadictsByName ( array ('aidUnit' => 'PROUNIT' ), null, true, null );
		$this->assign ( "productCode", "" );

		if (! empty ( $proTypeId )) {
			$proObj = $proTypeDao->get_d ( $proTypeId );
			if (! empty ( $proObj ['properties'] )) {
				$this->showDatadicts ( array ('properties' => 'WLSX' ), $proObj ['properties'] );
			}

			//带出物料类型配件清单
			$configDao = new model_stock_productinfo_configuration ();
			$configArr = $configDao->getAccessForType ( $proTypeId );
			$this->assign ( "itemAccessBody", $service->showAccessAtAdd ( $configArr ) );
			$this->assign ( "accessCount", count ( $configArr ) );

			//选择叶子节点的带出流水号
			if (($proObj ['rgt'] - $proObj ['lft']) == "1") {
				$this->assign ( "productCode", $service->produceSerialCode ( $proTypeId ) );
			}

		} else {
			$this->assign ( "accessCount", 0 );
			$this->assign ( "itemAccessBody", "" );
		}
		$this->view ( 'add' );

	}
	/**
	 * 修改物料信息页面
	 */
	function c_init() {
		$productinfo = $this->service->get_d ( $_GET ['id'] );
		$productinfo ['file'] = $this->service->getFilesByObjId ( $_GET ['id'] );
		foreach ( $productinfo as $key => $val ) {
			if ($key == 'configurations') {
				$strArr = $this->service->showItemAtEdit ( $val );
				$this->show->assign ( 'configList', $strArr [0] );
				$this->assign ( "configCount", $strArr [2] );
				$this->show->assign ( 'accessList', $strArr [1] );
				$this->assign ( "accessCount", $strArr [3] );
			} else {
				$this->show->assign ( $key, $val );
			}
		}

		$this->showDatadicts ( array ('checkType' => 'ZJFS' ), $productinfo ['checkType'] );
		$this->showDatadicts ( array ('properties' => 'WLSX' ), $productinfo ['properties'] );
		$this->showDatadicts ( array ('accountingCode' => 'KJKM' ), $productinfo ['accountingCode'] );
		$this->showDatadicts ( array ('ext1' => 'WLSTATUS' ), $productinfo ['ext1'] );
		$this->showDatadicts ( array ('statType' => 'TJLX' ), $productinfo ['statType'] );
		$this->showDatadictsByName ( array ('unitName' => 'PROUNIT' ), $productinfo ['unitName'] );
		$this->showDatadictsByName ( array ('aidUnit' => 'PROUNIT' ), $productinfo ['aidUnit'], true, null );

		try {
			$isRelated = $this->service->isProductinfoRelated ( $_GET ['id'] );
		} catch ( Exception $e ) {
			$isRelated = true;
		}
		$this->assign ( "isRelated", $isRelated );
		$this->assign('methodType',$_GET['methodType']);  //判断编辑页面为提交页面或确定页面
		$this->view ( 'edit' );

	}
	/**
	 * 查看物料信息Tab页面
	 */
	function c_toViewTab() {
		$this->assign ( "id", $_GET ['id'] );
		$this->assign ( "tableName", "oa_stock_product_info" );
		$this->display ( "view-tab" );
	}
	/**
	 * 查看物料信息页面
	 */
	function c_view() {
		$productinfo = $this->service->get_d ( $_GET ['id'] );
		//列表数据权限处理
		$productinfo = $this->service->filterWithoutField('字段权限',$productinfo,'form',array('priCost'));
		$productinfo ['file'] = $this->service->getFilesByObjId ( $_GET ['id'], false );
		foreach ( $productinfo as $key => $val ) {
			if ($key == 'configurations') {
				$str = $this->service->showItemAtView ( $val );
				$this->show->assign ( 'configurations', $str );
			} else {
				$this->show->assign ( 'configruations', "<tr></tr>" );
				$this->show->assign ( $key, $val );
			}
		}

		/*s:----------------把物料类型完整展示出来----------------*/
		$productTypeDao = new model_stock_productinfo_producttype ();
		$proTypeObj = $productTypeDao->get_d ( $productinfo ['proTypeId'] );
		$productTypeDao->searchArr = array ("xlft" => $proTypeObj ['lft'], "drgt" => $proTypeObj ['rgt'] );
		$productTypeDao->sort = "c.lft";
		$productTypeDao->asc = false;
		$proTypeParent = $productTypeDao->list_d ();
		$allProType = "";

		foreach ( $proTypeParent as $key => $val ) {

			$allProType .= $val ['proType'] . " / ";
		}
		$allProType .= $productinfo ['proType'];
		$this->show->assign ( "proType", $allProType );
		/*e:----------------把物料类型完整展示出来----------------*/

		$this->show->assign ( "checkType", $this->getDataNameByCode ( $productinfo ['checkType'] ) );
		$this->show->assign ( "properties", $this->getDataNameByCode ( $productinfo ['properties'] ) );
		$this->show->assign ( "accountingCode", $this->getDataNameByCode ( $productinfo ['accountingCode'] ) );
		$this->show->assign ( "ext1", $this->getDataNameByCode ( $productinfo ['ext1'] ) );
		$this->show->assign ( "statType", $this->getDataNameByCode ( $productinfo ['statType'] ) );
		if ($productinfo ['encrypt'] == "on") {
			$this->show->assign ( 'encrypt', "是" );
		} else {
			$this->show->assign ( 'encrypt', "否" );
		}
		if ($productinfo ['esmCanUse'] == "1") {
			$this->show->assign ( 'esmCanUse', "是" );
		} else {
			$this->show->assign ( 'esmCanUse', "否" );
		}

		$this->display ( 'view' );

	}
	/**
	 * 根据产品类型id获取产品信息
	 */
	function c_page() {
		$service = $this->service;
		$searchArr = array ("proTypeId" => $_GET ['proTypeId'] );
		$service->asc = false;
		$service->searchArr = $searchArr;
		parent::c_page ();
	}

	/**
	 * 根据产品类型id获取属于本产品类型以下的产品信息
	 */
	function c_getProductInfoByTypeId() {
		$service = $this->service;
		$service->asc = false;
		if ($_GET ['proTypeId'] == - 1) {
			$rows = $service->pageBySqlId ( "select_productinfo" );
			$this->show->assign ( "proType", "" );
			$this->show->assign ( "proTypeId", "-1" );
		} else {
			$searchArr = array ("parentId" => $_GET ['proTypeId'] );
			$service->searchArr = $searchArr;
			$rows = $service->pageBySqlId ( "select_proinfo_typeparent" );
			$this->show->assign ( "proType", $_GET ['proType'] );
			$this->show->assign ( "proTypeId", $_GET ['proTypeId'] );
		}
		$showpage = new includes_class_page ();
		$showpage->show_page ( array ('total' => count ( $rows ) ) );

		$this->show->assign ( 'list', $service->showlist ( $rows, $showpage ) );

		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );

	}

	/**
	 * 根据产品类型id获取属于本类型以下的产品信息
	 */
	function c_getInNodeProInfo() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		if (! isset ( $_REQUEST ['parentId'] )) {
			$service->searchArr = array ("parentId" => "-1" );
		}
		$rows = $service->page_d ( "select_proinfo_typeparent" );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 根据仓库查找仓库中的产品信息
	 * @param tags
	 * @date 2011-1-10 下午02:02:13
	 * @qiaolong
	 */
	function c_getPdinfoByStockId() {
		$stockId = $_GET ['stockId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->getPdinfoByStockId ( $stockId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = count ( $rows );
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription 根据入库申请单查找入库申请单中的产品信息
	 * @param tags
	 * @date 2011-1-10 下午02:02:13
	 * @qiaolong
	 */
	function c_storageproPJ() {
		$relatedProcessId = $_GET ['relatedProcessId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->getProInfoBystorageId ( $relatedProcessId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription 根据到货单Id查找到货单中的产品信息
	 * @param tags
	 * @date 2011-3-2 上午11:24:33
	 * @qiaolong
	 */
	function c_getPdinfoByArrivalId() {
		$relatedProcessId = $_GET ['relatedProcessId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->getProInfoByArrivalId ( $relatedProcessId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription 根据退货单Id查找到货单中的产品信息
	 * @param tags
	 * @date 2011-3-3 下午02:01:23
	 * @qiaolong
	 */
	function c_getPdinfoBydeliverId() {
		$relatedProcessId = $_GET ['relatedProcessId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->getProInfoBydeliverId ( $relatedProcessId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/********************************临时物料处理************* by LiuB************2011年6月29日9:45:08************************************/
	/**
	 * 临时物料列表
	 * by LiuB
	 */
	function c_producttemp() {
		$this->display ( "templist" );
	}
	/**
	 * 跳转-临时物料新增至物料信息页
	 * by LiuB
	 */
	function c_tempadd() {
		$rows = $this->service->typeInfo ( $_GET ['type'], $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'type', $_GET ['type'] );
		$this->showDatadicts ( array ('checkType' => 'ZJFS' ) );
		$this->showDatadicts ( array ('properties' => 'WLSX' ) );
		$this->showDatadicts ( array ('accountingCode' => 'KJKM' ) );
		$this->display ( "tempadd" );
	}

	/**
	 * 新增对象操作
	 * by LiuB
	 */
	function c_tempProadd($isAddInfo = false) {
		$id = $this->service->tempadd_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			echo "<script>alert('新增成功！'); window.opener.parent.show_page();window.opener.parent.tb_remove();window.close();  </script>";
		}

		//$this->listDataDict();
	}

	/**
	 *
	 * 批量更新物料配件
	 */
	function c_toEditProAccess(){
		$this->assign("proTypeId", $_GET['proTypeId']);
//		$accessDao=new model_stock_productinfo_configuration();
//
//		$accessDao->searchArr=array(
//			"hardWareId"=>$_GET['proTypeId'],
//			"configType"=>"typeaccess"
//		);
//		$accessArr=$accessDao->listBySqlId();
//		foreach ($accessArr  as $key=>$Access){
//
//		}
//		$editCondition=array("configType"=>"typeaccess",
//							"hardWareId"=>$productObj['productId'],
//							"configId"=>$access['productId']);
		$this->service->searchArr=array(
								"proTypeId"=>$_GET['proTypeId']
							);
		$productArr=$this->service->listBySqlId();
//		print_r($productArr);

		$this->assign("itemList",$this->service->showEditProAccessItem($productArr));


		$this->view("access-edit");
	}

	/**
	 *
	 * 更新物料
	 */
	function c_editAccess(){
//		echo "<pre>";
//		print_r($_POST[$this->objName]);
//
//		$this->service->editAccess($_POST[$this->objName]);
//
		if($this->service->editAccess($_POST[$this->objName])){
			msg('操作成功！');
//			echo "<script>alert('操作成功！'); window.opener.show_page();window.opener.tb_remove(); </script>";
		}
	}

	/**
	 * 处理临时物料
	 * by LiuB
	 */
	function c_handle() {
		$this->assign ( 'tempId', $_GET ['id'] );
		$this->assign ( 'type', $_GET ['type'] );
		$this->display ( "handle" );
	}

	/**
	 * 跳转--已有物料时修改合同产品清单
	 */
	function c_tempedit() {
		$rows = $this->service->typeInfo ( $_GET ['type'], $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( 'type', $_GET ['type'] );
		$this->assign ( 'tempId', $_GET ['id'] );
		$this->display ( "tempedit" );
	}

	/**
	 * 将自定义清单物料写入产品清单方法
	 */
	function c_tempProedit($isAddInfo = false) {
		$id = $this->service->tempedit_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			echo "<script>alert('操作成功！'); window.opener.parent.show_page();window.opener.parent.tb_remove();window.close();  </script>";
		}
	}

	/**
	 *
	 * 导入新增物料信息上传EXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	/**
	 *
	 * 导入更新物料信息上传EXCEL
	 */
	function c_toUploadUpdateExcel() {
		$this->display ( "import-update" );
	}
	/**
	 *
	 * 导入更新K3编码信息
	 */
	function c_toUploadK3Excel() {
		$this->display ( "import-k3" );
	}
	/**
	 *
	 * 导入更新物料成本
	 */
	function c_toUpdatePriceExcel() {
		$this->display ( "import-price" );
	}
	/**
	 *
	 *导入新增EXCEL中物料信息
	 */
	function c_importProduct() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->importProInfo ( $excelData );
			if ($resultArr)
				echo util_excelUtil::showResult ( $resultArr, "物料信息导入新增结果", array ("物料编号", "结果" ) );
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 *导入更新EXCEL中物料信息
	 */
	function c_importUpdatePro() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->updateProByExcel ( $excelData );
			if ($resultArr)
				echo util_excelUtil::showResult ( $resultArr, "物料信息导入更新结果", array ("物料编号", "结果" ) );
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 *
	 *导入更新物料成本
	 */
	function c_importUpdatePrice() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->updatePriceByExcel ( $excelData );
			if ($resultArr)
				echo util_excelUtil::showResult ( $resultArr, "物料成本更新结果", array ("物料编号", "结果" ) );
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 *
	 *导入更新物料成本
	 */
	function c_importUpdateK3() {
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_stock_productinfo_importProductUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->updateK3ByExcel ( $excelData );
			if ($resultArr)
				echo util_excelUtil::showResult ( $resultArr, "物料成本更新结果", array ("物料编号", "结果" ) );
			else
				echo "<script>alert('导入失败!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 *
	 * 导出物料信息EXCEL
	 */
	function c_toExportExcel() {
		$dataArr = $this->service->listBySqlId ();
		$dao = new model_stock_productinfo_importProductUtil ();
		$statTypeArrs = $this->getDatadicts ( array ('TJLX' ) );
		$statTypeArr = $statTypeArrs ['TJLX'];
		$ext1StatusArrs = $this->getDatadicts ( array ('WLSTATUS' ) );
		$ext1StatusArr = $ext1StatusArrs ['WLSTATUS'];
		//		print_r($ext1StatusArr);
		foreach ( $dataArr as $key => $val ) {
			if (! empty ( $val ['statType'] )) {
				foreach ( $statTypeArr as $index => $rows ) {
					if ($val ['statType'] == $rows ['dataCode']) {
						$dataArr [$key] ['statTypeName'] = $rows ['dataName'];
					}
				}
			}
			if (! empty ( $val ['ext1'] )) {
				foreach ( $ext1StatusArr as $index => $rows ) {
					if ($val ['ext1'] == $rows ['dataCode']) {
						$dataArr [$key] ['ext1Status'] = $rows ['dataName'];
					}
				}
			}
		}
		//		echo "<pre>";
		//		print_r($dataArr);
		return $dao->exportProductExcel ( $dataArr );
	}

	/**
	 * 导出物料配件信息 edit by kuangzw
	 */
	function c_toArmatureExcel() {
		$proTypeId = $_GET ['proTypeId'];
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		if (! empty ( $proTypeId )) {
			$proTypeDao = new model_stock_productinfo_producttype ();
			$node = $proTypeDao->get_d ( $proTypeId );
			$productTypes = $proTypeDao->getChildrenByNode ( $node, 'self' );

			$proTypeIdArr = array ();
			for($i = 0; $i < count ( $productTypes ); $i ++) {
				array_push ( $proTypeIdArr, $productTypes [$i] ['id'] );
			}
			$service->searchArr ['proTypeId'] = $proTypeIdArr;
		}

		$rows = $service->list_d ( 'select_productandconf' );
		//echo "<pre>";
		//print_R($rows);
		$dao = new model_stock_productinfo_importProductUtil ();
		return $dao->exportProductInfoAndCongigExcel ( $rows );
	}

	/*************************************************************************************************************************/
	/**
	 * 过滤成品、外购、软件 PageJson
	 */
	function c_productPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息


		$cont = array ("成品", "外购商品", "软件" );
		$productTypeDao = new model_stock_productinfo_producttype ();
		$proTypeAllArr = $productTypeDao->productArr_d ( $cont );

		$proTypeIdArr = array ();
		foreach ( $proTypeAllArr as $key => $proTypeObj ) {
			array_push ( $proTypeIdArr, $proTypeObj ['id'] );
		}
		//		print_r($proTypeIdArr);
		$service->searchArr ['proTypeId'] = $proTypeIdArr;
		$rows = $service->page_d ();
		;
		//print_r($rows);
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转更新物料页面（更新物料关联的业务信息）
	 * add by chengl 2011-10-20
	 */
	function c_toUpdate() {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadictsByName ( array ('unitName' => 'PROUNIT' ), $rows ['unitName'] );
		$this->view ( 'update' );
	}

	/**
	 * 获取物料关联业务对象
	 */
	function c_getProductInfoRelationArr() {
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		echo util_jsonUtil::encode ( $productRelationTableArr );
	}

	/**
	 * 更新物料相关业务信息
	 */
	function c_updateProductinfo() {
		$productinfo = $_POST ['productinfo'];
		$relationArr = $_POST ['checked']; //选中更新的业务对象
		if ($_POST ['updateType'] == 1) {
			//根据id更新
			$this->service->updateProductinfo ( $productinfo, $relationArr );
		} else if ($_POST ['updateType'] == 2) {
			//根据名称更新
			$this->service->updateBusProductinfoByName ( $productinfo, $relationArr );
		} else {
			//根据编码更新
			$this->service->updateBusProductinfoByCode ( $productinfo, $relationArr );
		}
		msg ( '更新成功！' );
	}

	/**
	 * ajax方式批量删除对象（应该把成功标志跟消息返回）
	 */
	function c_ajaxdeletes() {
		try {
			$this->service->deletes_d ( $_POST ['id'] );
			echo 1;
		} catch ( Exception $e ) {
			echo util_jsonUtil::iconvGB2UTF ( $e->getMessage () );
		}
	}

	/**
	 * 跳转到物料检测页面
	 */
	function c_toViewRelation() {
		$msg = $this->service->productRelateMsg ( $_GET ['id'] );
		$this->assign ( 'msg', $msg );
		$this->view ( "relation" );
	}

	/**
	 *
	 * 检查物料是否存在关联业务信息
	 */
	function c_checkExistBusiness() {
		$businessId = $_POST ['id'];
		echo $this->service->checkRelatedResult ( $businessId );
	}

	/**
	 *
	 * 检查对应物料类型是否有管理权限
	 */
	function c_checkProType() {
		$limitTypeIds = $this->service->this_limit ['物料类型管理'];
		$typeId = isset ( $_POST ['typeId'] ) ? $_POST ['typeId'] : false;
		$checkResult = 0;
		if (! empty ( $limitTypeIds ) && $typeId) {
			$proTypeDao = new model_stock_productinfo_producttype ();
			$typeObj = $proTypeDao->get_d ( $typeId );
			$proTypeDao->searchArr ['ids'] = $limitTypeIds;
			$proTypeArr = $proTypeDao->listBySqlId ();
			foreach ( $proTypeArr as $key => $value ) {
				if ($value ['lft'] <= $typeObj ['lft'] && $value ['rgt'] >= $typeObj ['rgt']) {
					$checkResult = 1;
					break;
				}
			}
		}
		echo $checkResult;
	}

	/**
	 *
	 * 物料是否关闭/存在库存检验
	 * -1：开放;0：关闭且库存为0;大于0：关闭且库存不为0
	 */
	function c_checkStockAndClose() {
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$productinfo = $this->service->get_d ( $id );
		if ($productinfo ['ext1'] == "WLSTATUSKF") {
			echo - 1;
		} else {
			$inventoryinfoDao = new model_stock_inventoryinfo_inventoryinfo ();
			$exeNum = $inventoryinfoDao->getExeNums ( $productinfo ['id'], 1 );
			if ($exeNum > 0) {
				echo $exeNum;
			} else {
				echo 0;
			}

		}
	}

	/****************************** S 工程使用部分 **************************************/
	/**
	 * 工程物料信息列表
	 */
	function c_toEsmProInfoPage() {
		$this->view ( "listesm" );
	}

	/**
	 * 工程物料信息列表json
	 */
	function c_pageEsmProInfoJson() {
		$service = $this->service;

		//默认参数
		$service->getParam ( $_REQUEST );

		//获取下级列表
		if (isset ( $service->searchArr ['proTypeId'] )) {
			$proTypeDao = new model_stock_productinfo_producttype ();
			$node = $proTypeDao->get_d ( $service->searchArr ['proTypeId'] );
			$productTypes = $proTypeDao->getChildrenByNode ( $node, 'self' );

			$proTypeIdArr = array ();
			for($i = 0; $i < count ( $productTypes ); $i ++) {
				array_push ( $proTypeIdArr, $productTypes [$i] ['id'] );
			}
			$service->searchArr ['proTypeId'] = $proTypeIdArr;
		}

		//资产库存统计过滤
		ESM_DEPT_ID;

		$rows = $service->page_d ( 'select_esm' );

		if (is_array ( $rows )) {
			//加载合计列
			$service->sort = "";
			$objArr = $service->listBySqlId ( 'select_esmcount' );
			if (is_array ( $objArr )) {
				$rsArr = $objArr [0];
				$rsArr ['proType'] = '全部合计';
				$rsArr ['id'] = 'noId';
			}
			$rows [] = $rsArr;
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 工程启用
	 */
	function c_openEsmCanUse() {
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$typeId = isset ( $_POST ['typeId'] ) ? $_POST ['typeId'] : null;
		$rs = $this->service->openEsmCanUse_d ( $id, $typeId );
		if ($rs) {
			echo "1";
		} else {
			echo "0";
		}
		exit ();
	}

	/**
	 * 工程启用
	 */
	function c_closeEsmCanUse() {
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : null;
		$typeId = isset ( $_POST ['typeId'] ) ? $_POST ['typeId'] : null;
		$rs = $this->service->closeEsmCanUse_d ( $id, $typeId );
		if ($rs) {
			echo "1";
		} else {
			echo "0";
		}
		exit ();
	}
/****************************** E 工程使用部分 **************************************/
	/**
	 * 新增并提交
	 * 
	 */
	function c_addSubmit(){
		$this->checkSubmit();		
		$id = $this->service->add_d ($_POST [$this->objName] , true,'submit' );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '提交成功！';
		if ($id) {
			$this->service->thisMail_d($this->service->get_d($id));		
			msg ( $msg );
		}
		
	}
	/**
	 * 编辑并提交
	 */
	function c_editSubmit(){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object,true, 'submit' )) {
			$this->service->thisMail_d($this->service->get_d($object['id']));
			msg ( '提交成功！' );
		}
	}
	/**
	 * 我的列表Json
	 */
	function c_myPageJson() {
	
		$service = $this->service;
		
		$service->getParam ( $_REQUEST );
		if (isset ( $service->searchArr ['proTypeId'] )) {
			$proTypeDao = new model_stock_productinfo_producttype ();
			$node = $proTypeDao->get_d ( $service->searchArr ['proTypeId'] );
			$productTypes = $proTypeDao->getChildrenByNode ( $node, 'self' );

			$proTypeIdArr = array ();
			for($i = 0; $i < count ( $productTypes ); $i ++) {
				array_push ( $proTypeIdArr, $productTypes [$i] ['id'] );
			}
			$service->searchArr ['proTypeId'] = $proTypeIdArr;
		}
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 跳转到提交页面
	 * Enter description here ...
	 */
	function c_toConfirmPage(){
		$this->view('confirmPage');
	}
	/**
	 * 确认物料列表Json
	 */
	function c_submitJson() {
	
		$service = $this->service;
		
		$service->getParam ( $_REQUEST );
		if (isset ( $service->searchArr ['proTypeId'] )) {
			$proTypeDao = new model_stock_productinfo_producttype ();
			$node = $proTypeDao->get_d ( $service->searchArr ['proTypeId'] );
			$productTypes = $proTypeDao->getChildrenByNode ( $node, 'self' );

			$proTypeIdArr = array ();
			for($i = 0; $i < count ( $productTypes ); $i ++) {
				array_push ( $proTypeIdArr, $productTypes [$i] ['id'] );
			}
			$service->searchArr ['proTypeId'] = $proTypeIdArr;
		}
		$service->searchArr ['status'] = 1;
		//$service->searchArr ['state'] = 1;
		$rows = $service->page_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * 提交状态
	 */
	function c_submitStatus(){
		$id=$_GET['id'];
		if($this->service->changeStatus($id,'submit')){		
			$this->service->thisMail_d($this->service->get_d($id));		
			echo 1;
			exit;
		}else 
			echo 0;				
	}
	/**
	 * 编辑并确认状态
	 */
	function c_changeConfirm(){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object)) {
			if(	$this->service->changeState($object['id'],2)){
				$this->service->thisMail_d($this->service->get_d($object['id']));		
				msg ( '提交成功！' );
			}				
		}		
	}
	/**
	 * 打回操作
	 */
	function c_ajaxBack(){
		$id=$_POST['id'];
		if(empty($id)){
			echo 0;
			exit;
		}
		$service=$this->service;
		if($service->changeCommit($id,'save',3)){	
			$service->thisMail_d($service->get_d($id));		
			echo 1;
			exit;
		}else 
			echo 0;		
	}
}
?>
