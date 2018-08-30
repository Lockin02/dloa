<?php
/*
 * Created on 2010-7-17
 *	��Ʒ������ϢController
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
	 * ������������Ϣ�б�
	 */
	function c_toProInfoTypePage() {
		$this->assign ( 'productUpdate', $this->service->this_limit ['����'] );
		$this->view ( "list" );
	}

	/**
	 * ѡ�����ϵ�����ѡ��ҳ��
	 *
	 */
	function c_selectProduct() {
		$this->assign ( 'showButton', $_GET ['showButton'] );
		$this->assign ( 'showcheckbox', $_GET ['showcheckbox'] );
		$this->assign ( 'checkIds', $_GET ['checkIds'] );
		$this->view ( "select" );
	}

	/**
	 * �����������ͻ�ȡ��������Ϣ�б�����
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
	 * ���ϴ����ظ���У��
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
	 * �������ϻ�����Ϣҳ��
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

			//����������������嵥
			$configDao = new model_stock_productinfo_configuration ();
			$configArr = $configDao->getAccessForType ( $proTypeId );
			$this->assign ( "itemAccessBody", $service->showAccessAtAdd ( $configArr ) );
			$this->assign ( "accessCount", count ( $configArr ) );

			//ѡ��Ҷ�ӽڵ�Ĵ�����ˮ��
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
	 * �޸�������Ϣҳ��
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
		$this->assign('methodType',$_GET['methodType']);  //�жϱ༭ҳ��Ϊ�ύҳ���ȷ��ҳ��
		$this->view ( 'edit' );

	}
	/**
	 * �鿴������ϢTabҳ��
	 */
	function c_toViewTab() {
		$this->assign ( "id", $_GET ['id'] );
		$this->assign ( "tableName", "oa_stock_product_info" );
		$this->display ( "view-tab" );
	}
	/**
	 * �鿴������Ϣҳ��
	 */
	function c_view() {
		$productinfo = $this->service->get_d ( $_GET ['id'] );
		//�б�����Ȩ�޴���
		$productinfo = $this->service->filterWithoutField('�ֶ�Ȩ��',$productinfo,'form',array('priCost'));
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

		/*s:----------------��������������չʾ����----------------*/
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
		/*e:----------------��������������չʾ����----------------*/

		$this->show->assign ( "checkType", $this->getDataNameByCode ( $productinfo ['checkType'] ) );
		$this->show->assign ( "properties", $this->getDataNameByCode ( $productinfo ['properties'] ) );
		$this->show->assign ( "accountingCode", $this->getDataNameByCode ( $productinfo ['accountingCode'] ) );
		$this->show->assign ( "ext1", $this->getDataNameByCode ( $productinfo ['ext1'] ) );
		$this->show->assign ( "statType", $this->getDataNameByCode ( $productinfo ['statType'] ) );
		if ($productinfo ['encrypt'] == "on") {
			$this->show->assign ( 'encrypt', "��" );
		} else {
			$this->show->assign ( 'encrypt', "��" );
		}
		if ($productinfo ['esmCanUse'] == "1") {
			$this->show->assign ( 'esmCanUse', "��" );
		} else {
			$this->show->assign ( 'esmCanUse', "��" );
		}

		$this->display ( 'view' );

	}
	/**
	 * ���ݲ�Ʒ����id��ȡ��Ʒ��Ϣ
	 */
	function c_page() {
		$service = $this->service;
		$searchArr = array ("proTypeId" => $_GET ['proTypeId'] );
		$service->asc = false;
		$service->searchArr = $searchArr;
		parent::c_page ();
	}

	/**
	 * ���ݲ�Ʒ����id��ȡ���ڱ���Ʒ�������µĲ�Ʒ��Ϣ
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
	 * ���ݲ�Ʒ����id��ȡ���ڱ��������µĲ�Ʒ��Ϣ
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
	 * @desription ���ݲֿ���Ҳֿ��еĲ�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-1-10 ����02:02:13
	 * @qiaolong
	 */
	function c_getPdinfoByStockId() {
		$stockId = $_GET ['stockId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getPdinfoByStockId ( $stockId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = count ( $rows );
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription ����������뵥����������뵥�еĲ�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-1-10 ����02:02:13
	 * @qiaolong
	 */
	function c_storageproPJ() {
		$relatedProcessId = $_GET ['relatedProcessId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getProInfoBystorageId ( $relatedProcessId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription ���ݵ�����Id���ҵ������еĲ�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-3-2 ����11:24:33
	 * @qiaolong
	 */
	function c_getPdinfoByArrivalId() {
		$relatedProcessId = $_GET ['relatedProcessId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getProInfoByArrivalId ( $relatedProcessId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription �����˻���Id���ҵ������еĲ�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-3-3 ����02:01:23
	 * @qiaolong
	 */
	function c_getPdinfoBydeliverId() {
		$relatedProcessId = $_GET ['relatedProcessId'];
		$service = $this->service;
		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getProInfoBydeliverId ( $relatedProcessId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/********************************��ʱ���ϴ���************* by LiuB************2011��6��29��9:45:08************************************/
	/**
	 * ��ʱ�����б�
	 * by LiuB
	 */
	function c_producttemp() {
		$this->display ( "templist" );
	}
	/**
	 * ��ת-��ʱ����������������Ϣҳ
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
	 * �����������
	 * by LiuB
	 */
	function c_tempProadd($isAddInfo = false) {
		$id = $this->service->tempadd_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			echo "<script>alert('�����ɹ���'); window.opener.parent.show_page();window.opener.parent.tb_remove();window.close();  </script>";
		}

		//$this->listDataDict();
	}

	/**
	 *
	 * ���������������
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
	 * ��������
	 */
	function c_editAccess(){
//		echo "<pre>";
//		print_r($_POST[$this->objName]);
//
//		$this->service->editAccess($_POST[$this->objName]);
//
		if($this->service->editAccess($_POST[$this->objName])){
			msg('�����ɹ���');
//			echo "<script>alert('�����ɹ���'); window.opener.show_page();window.opener.tb_remove(); </script>";
		}
	}

	/**
	 * ������ʱ����
	 * by LiuB
	 */
	function c_handle() {
		$this->assign ( 'tempId', $_GET ['id'] );
		$this->assign ( 'type', $_GET ['type'] );
		$this->display ( "handle" );
	}

	/**
	 * ��ת--��������ʱ�޸ĺ�ͬ��Ʒ�嵥
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
	 * ���Զ����嵥����д���Ʒ�嵥����
	 */
	function c_tempProedit($isAddInfo = false) {
		$id = $this->service->tempedit_d ( $_POST [$this->objName], $isAddInfo );
		if ($id) {
			echo "<script>alert('�����ɹ���'); window.opener.parent.show_page();window.opener.parent.tb_remove();window.close();  </script>";
		}
	}

	/**
	 *
	 * ��������������Ϣ�ϴ�EXCEL
	 */
	function c_toUploadExcel() {
		$this->display ( "import" );
	}

	/**
	 *
	 * �������������Ϣ�ϴ�EXCEL
	 */
	function c_toUploadUpdateExcel() {
		$this->display ( "import-update" );
	}
	/**
	 *
	 * �������K3������Ϣ
	 */
	function c_toUploadK3Excel() {
		$this->display ( "import-k3" );
	}
	/**
	 *
	 * ����������ϳɱ�
	 */
	function c_toUpdatePriceExcel() {
		$this->display ( "import-price" );
	}
	/**
	 *
	 *��������EXCEL��������Ϣ
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
				echo util_excelUtil::showResult ( $resultArr, "������Ϣ�����������", array ("���ϱ��", "���" ) );
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}

	/**
	 *
	 *�������EXCEL��������Ϣ
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
				echo util_excelUtil::showResult ( $resultArr, "������Ϣ������½��", array ("���ϱ��", "���" ) );
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 *
	 *����������ϳɱ�
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
				echo util_excelUtil::showResult ( $resultArr, "���ϳɱ����½��", array ("���ϱ��", "���" ) );
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 *
	 *����������ϳɱ�
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
				echo util_excelUtil::showResult ( $resultArr, "���ϳɱ����½��", array ("���ϱ��", "���" ) );
			else
				echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove()();</script>";
		}
	}
	/**
	 *
	 * ����������ϢEXCEL
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
	 * �������������Ϣ edit by kuangzw
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
	 * ���˳�Ʒ���⹺����� PageJson
	 */
	function c_productPageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		$cont = array ("��Ʒ", "�⹺��Ʒ", "���" );
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
	 * ��ת��������ҳ�棨�������Ϲ�����ҵ����Ϣ��
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
	 * ��ȡ���Ϲ���ҵ�����
	 */
	function c_getProductInfoRelationArr() {
		include_once ("model/stock/productinfo/productinfoRelationTableArr.php");
		echo util_jsonUtil::encode ( $productRelationTableArr );
	}

	/**
	 * �����������ҵ����Ϣ
	 */
	function c_updateProductinfo() {
		$productinfo = $_POST ['productinfo'];
		$relationArr = $_POST ['checked']; //ѡ�и��µ�ҵ�����
		if ($_POST ['updateType'] == 1) {
			//����id����
			$this->service->updateProductinfo ( $productinfo, $relationArr );
		} else if ($_POST ['updateType'] == 2) {
			//�������Ƹ���
			$this->service->updateBusProductinfoByName ( $productinfo, $relationArr );
		} else {
			//���ݱ������
			$this->service->updateBusProductinfoByCode ( $productinfo, $relationArr );
		}
		msg ( '���³ɹ���' );
	}

	/**
	 * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
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
	 * ��ת�����ϼ��ҳ��
	 */
	function c_toViewRelation() {
		$msg = $this->service->productRelateMsg ( $_GET ['id'] );
		$this->assign ( 'msg', $msg );
		$this->view ( "relation" );
	}

	/**
	 *
	 * ��������Ƿ���ڹ���ҵ����Ϣ
	 */
	function c_checkExistBusiness() {
		$businessId = $_POST ['id'];
		echo $this->service->checkRelatedResult ( $businessId );
	}

	/**
	 *
	 * ����Ӧ���������Ƿ��й���Ȩ��
	 */
	function c_checkProType() {
		$limitTypeIds = $this->service->this_limit ['�������͹���'];
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
	 * �����Ƿ�ر�/���ڿ�����
	 * -1������;0���ر��ҿ��Ϊ0;����0���ر��ҿ�治Ϊ0
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

	/****************************** S ����ʹ�ò��� **************************************/
	/**
	 * ����������Ϣ�б�
	 */
	function c_toEsmProInfoPage() {
		$this->view ( "listesm" );
	}

	/**
	 * ����������Ϣ�б�json
	 */
	function c_pageEsmProInfoJson() {
		$service = $this->service;

		//Ĭ�ϲ���
		$service->getParam ( $_REQUEST );

		//��ȡ�¼��б�
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

		//�ʲ����ͳ�ƹ���
		ESM_DEPT_ID;

		$rows = $service->page_d ( 'select_esm' );

		if (is_array ( $rows )) {
			//���غϼ���
			$service->sort = "";
			$objArr = $service->listBySqlId ( 'select_esmcount' );
			if (is_array ( $objArr )) {
				$rsArr = $objArr [0];
				$rsArr ['proType'] = 'ȫ���ϼ�';
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
	 * ��������
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
	 * ��������
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
/****************************** E ����ʹ�ò��� **************************************/
	/**
	 * �������ύ
	 * 
	 */
	function c_addSubmit(){
		$this->checkSubmit();		
		$id = $this->service->add_d ($_POST [$this->objName] , true,'submit' );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '�ύ�ɹ���';
		if ($id) {
			$this->service->thisMail_d($this->service->get_d($id));		
			msg ( $msg );
		}
		
	}
	/**
	 * �༭���ύ
	 */
	function c_editSubmit(){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object,true, 'submit' )) {
			$this->service->thisMail_d($this->service->get_d($object['id']));
			msg ( '�ύ�ɹ���' );
		}
	}
	/**
	 * �ҵ��б�Json
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
	 * ��ת���ύҳ��
	 * Enter description here ...
	 */
	function c_toConfirmPage(){
		$this->view('confirmPage');
	}
	/**
	 * ȷ�������б�Json
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
	 * �ύ״̬
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
	 * �༭��ȷ��״̬
	 */
	function c_changeConfirm(){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object)) {
			if(	$this->service->changeState($object['id'],2)){
				$this->service->thisMail_d($this->service->get_d($object['id']));		
				msg ( '�ύ�ɹ���' );
			}				
		}		
	}
	/**
	 * ��ز���
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
