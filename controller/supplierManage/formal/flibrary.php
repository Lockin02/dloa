<?php
class controller_supplierManage_formal_flibrary extends controller_base_action {
	/**
	 * @desription 构造函数
	 * @param tags
	 * @date 2010-11-11 上午10:39:19
	 */
	function __construct() {
		$this->objName = "flibrary";
		$this->objPath = "supplierManage_formal";
		parent::__construct ();
	}
	/**
	 * @desription 跳转到正式库管理列表
	 * @param tags
	 * @date 2010-11-11 上午10:41:06
	 */
	function c_toFsupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * @desription 跳转到供应商合格库列表
	 */
	function c_toPassSupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-pass-list' );
	}
	/**
	 * @desription 跳转到供应商不合格库列表
	 */
	function c_toFailSupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-fail-list' );
	}
	/**
	 * @desription 跳转到其他供应商库列表
	 */
	function c_toOtherSupplist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-other-list' );
	}
	/**
	 * @desription 跳转到修改页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_tobasicEdit() {
		$this->permCheck ();//安全校验
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$rows = $this->service->get_d ( $_GET ['id'] );
		$rows ['file'] = $this->service->getFilesByObjId ( $rows ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$trainDao = new model_supplierManage_formal_bankinfo ();
		$this->assign ( 'list', $trainDao->showTrainEditList ( $Id, array ('KHBank' => 'KHBANK' ) ) );
		$this->showDatadicts ( array ('KHBank' => 'KHBANK' ) );
		$this->showDatadicts ( array ('suppCategory' => 'gyslb' ),$rows['suppCategory'] ); //供应商类别

		$this->display ( 'basicE',true );
	}

	/**
	 * 供应商添加页面
	 */
	function c_toAdd() {
		//对象编号
		$objCode = generatorSerial ();
		//系统编号
		$sysCode = generatorSerial ();
		$this->assign ( 'objCode', $objCode );
		$this->assign ( 'systemCode', $sysCode );

		//获取数据字典里的数据
		$this->showDatadicts ( array ('KHBank' => 'KHBANK' ) ); //开户银行
		$this->showDatadicts ( array ('suppCategory' => 'gyslb' ) ); //供应商类别
		//供应商的初步评估
		$datadictDao = new model_system_datadict_datadict ();
		$datadictArr = $datadictDao->getDatadictsByParentCodes ( "lskpg" );
		$stasseDao = new model_supplierManage_temporary_stasse ();
		$str = $stasseDao->add_s ( $datadictArr ['lskpg'] );
		$this->show->assign ( "str", $str );
		$this->assign ( 'flag', $_GET ['flag'] );
		//组件添加供应商所需
		if (empty ( $_GET ['valPlus'] )) {
			$this->assign ( 'valPlus', '' );
		} else {
			$this->assign ( 'valPlus', $_GET ['valPlus'] );
		}
		$this->display ( 'add',true );
	}
	/**
	 * @desription 跳转到修改页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_toEdit() {
		if($_GET ['suppGrade']=="A"||$_GET ['suppGrade']=="B"||$_GET ['suppGrade']=="C"){
			if(!$this->service->this_limit['供应商编辑']){
				echo "<script>alert('没有权限进行操作!');self.parent.tb_remove();</script>";
				exit();
			}
		}
		$this->permCheck ();//安全校验
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$objCode = isset ( $_GET ['objCode'] ) ? $_GET ['objCode'] : null;
		$this->show->assign ( "id", $Id );
		$this->show->assign ( "objCode", $objCode );
		$this->assign('skey_',$_GET['skey']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-Etab');
	}
	/**
	 * @desription 跳转到查看基本信息页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_tobasicRead() {
		$this->permCheck ();//安全校验
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$rows = $this->service->getSuppInfoById( $Id );

		foreach ( $rows as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$trainDao = new model_supplierManage_formal_bankinfo ();
		$contratDao=new model_supplierManage_formal_sfcontact();
		$this->assign ( 'list', $trainDao->showViewBank ( $Id) );
		$this->assign ( 'contratlist', $contratDao->showViewContact ( $Id) );
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'], false ) );
		$this->display ( 'basicR' );
	}
	/**
	 * @desription 跳转到查看基本信息页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_toassessMsg() {
		$this->permCheck ();//安全校验
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$rows = $this->service->get_d ( $Id );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-toRegMsg' );
	}
	/**
	 * @desription 跳转到查看页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_toRead() {
		$this->permCheck ();//安全校验
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$objCode = isset ( $_GET ['objCode'] ) ? $_GET ['objCode'] : null;
		$this->show->assign ( "id", $Id );
		$this->show->assign ( "objCode", $objCode );
		$this->assign('skey_',$_GET['skey']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-Rtab' );
	}
	/**
	 * @desription 跳转到分配负责人页面
	 * @param tags
	 * @date 2010-11-12 下午01:48:42
	 */
	function c_toAduitP() {
//		if(!$this->service->this_limit['分配负责人']){
//			echo "<script>alert('没有权限进行操作!');self.parent.tb_remove();</script>";
//			exit();
//		}
		$this->permCheck ();//安全校验
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-aduit' );

	}

	/**
	 *
	 * 跳转到供应商资料导入
	 */
	function c_toImportPage() {
		$this->view ( "info-import" );
	}
	/**
	 * 修改对象
	 */
	function c_flibedit() {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object,true )) {
			msg ( '保存成功！' );
		}
	}
	/**
	 * 修改联系人基本信息
	 */
	function c_basicEdit() {
		$this->checkSubmit(); //验证是否重复提交
		$object = $_POST [$this->objName];
		if ($this->service->editinfo_d ( $object, true)) {
			//			echo $_GET['parentId'];
			//			$url ='?model=supplierManage_formal_flibrary&action=tobasicEdit&parentId='+$_GET['parentId']+'&parentCode='+$_GET['parentCode'];
			////			echo $url;
			msgGo ( '编辑成功！' );
		}
	}

	function c_addByExternal() {
		$this->checkSubmit(); //验证是否重复提交
		$supplier = $_POST [$this->objName];
		$id = $this->service->addByExternal_d ( $_POST [$this->objName] );
		$supplier ['id'] = $id;

		if ($id) {
			if (! empty ( $_POST ['valPlus'] )) {
				echo "<script>window.opener.jQuery('#valHidden" . $_POST ['valPlus'] . "').val('" . util_jsonUtil::encode ( $supplier ) . "');</script>";
			}
			msgRf ( '添加成功！' );
		} else {
			msgRf ( '添加失败!' );
		}
	}

	/**
	 * @desription 跳转到我负责的供应商列表
	 * @param tags
	 * @date 2010-11-16 下午01:47:36
	 */
	function c_myReslist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-mylist' );
	}
	/**
	 * @desription 跳转到我注册的供应商列表
	 * @param tags
	 * @date 2010-11-16 下午01:47:36
	 */
	function c_myloglist() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-myloglist' );
	}
	/*
 * 我负责的供应商列表数据获取
 */
	function c_mypageJson() {
		$service = $this->service;
		$manageUserId = isset ( $_SESSION ['USER_ID'] ) ? $_SESSION ['USER_ID'] : null;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		//$this->searchVal ( 'suppName' );
	//	$this->searchVal ( 'products' );
		$rows = $this->service->myResSupp ( $manageUserId );
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/*
 * 我册的供应商列表数据获取
 */
	function c_mylogpageJson() {
		$service = $this->service;
		$createId = isset ( $_SESSION ['USER_ID'] ) ? $_SESSION ['USER_ID'] : null;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$this->searchVal ( 'suppName' );
		$this->searchVal ( 'products' );
		$rows = $this->service->myLogSupp ( $createId );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/*
 * 正式库获取数据方法
 * */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//		$service->asc = false;
		$this->searchVal ( 'suppName' );
		$this->searchVal ( 'products' );
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/*
 * 合格，不合格，其他供应商获取数据方法
 * */
	function c_suppJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//		$service->asc = false;
		$rows = $service->page_d ();
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/*
 * 合格，不合格，其他供应商获取数据方法(含有联系人)
 * */
	function c_suppcontJson() {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		//		$service->asc = false;
		$rows = $service->pageBySqlId('select_cont');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * @desription 跳到到启用供应商页面
	 * @param tags
	 * @date 2010-11-17 下午06:46:19
	 */
	function c_stoc() {
		if(!$this->service->this_limit['启用供应商']){
			echo "<script>alert('没有权限进行操作!');location='?model=supplierManage_formal_flibrary&action=toFsupplist'</script>";
			exit();
		}
		$this->permCheck ();//安全校验
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$flibrary = array ("id" => $Id, "status" => $this->service->statusDao->statusEtoK ( "common" ) );
		$this->operArr = array ();
		$this->beforeMethod ( $flibrary );
		$val = $this->service->edit_d ( $flibrary, true );
		if ($val) {
			$flibrary ['operType_'] = '启动供应商';

			$this->behindMethod ( $flibrary );
			msgGo ( '启动成功！', '?model=supplierManage_formal_flibrary&action=toFsupplist' );
		}
		echo util_jsonUtil::iconvGB2UTF ( $val );

	}

	/**
	 * @desription 跳到到禁用供应商页面
	 * @param tags
	 * @date 2010-11-17 下午06:46:19
	 */
	function c_ctos() {
		if(!$this->service->this_limit['禁用供应商']){
			echo "<script>alert('没有权限进行操作!');location='?model=supplierManage_formal_flibrary&action=toFsupplist'</script>";
			exit();
		}
		$this->permCheck ();//安全校验
		$Id = isset ( $_GET ['id'] ) ? $_GET ['id'] : exit ();
		$flibrary = array ("id" => $Id, "status" => $this->service->statusDao->statusEtoK ( "stop" ) );
		$this->operArr = array ();
		$this->beforeMethod ( $flibrary );
		$val = $this->service->edit_d ( $flibrary, true );
		if ($val) {
			$flibrary ['operType_'] = '禁用供应商';
			$this->behindMethod ( $flibrary );
			msgGo ( '禁用成功！', '?model=supplierManage_formal_flibrary&action=toFsupplist' );
		}
		echo util_jsonUtil::iconvGB2UTF ( $val );
	}

	/**假删除供应商
	 *author can
	 *2011-4-7
	 */
	function c_delSupplier() {
		if(!$this->service->this_limit['删除供应商']){
			echo "2";
			exit();
		}
		$condition = array ('id' => $_POST ['id'] );
		if ($this->service->updateField ( $condition, 'delFlag', '1' )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**以供应商为单位查看付款记录，采购发票记录
	*author can
	*2011-7-21
	*/
	function c_supplierInfo(){
		$this->permCheck ();//安全校验
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$this->assign('id',$id);
		$this->display('infor-tab');
	}

	/**
	 * @desription ajax判断供应商名称是否重复
	 * @param tags
	 * @date 2010-11-10 下午05:06:08
	 */
	function c_ajaxSuppName() {
		$service = $this->service;
		$suppName = isset ($_GET['suppName']) ? $_GET['suppName'] : false;
		$searchArr = array (
			"ajaxSuppName" => $suppName
		);
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : "";
		$isRepeat = $service->isRepeat($searchArr, $id);
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * 获取权限
	 */
	function c_getLimits(){
		$limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	function c_getSuppInfo(){
		$suppId=isset($_POST['id'])?$_POST['id']:"";
		$rows=$this->service->get_d($suppId);
		if(is_array($rows)){
			echo util_jsonUtil::encode ( $rows);
		}else{
			echo "";
		}
	}


	/*********************** excel 部分 ***************************/
	/**
	 * excel导入页面
	 */
	function c_toExcel(){
		$this->display('excel');
	}

	/**
	 * excel导入
	 */
	function c_excelImport(){
		$resultArr = $this->service->excelImport_d ();
		$title = '供应商信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * excel导出
	 */
	function c_excelOutport(){
		set_time_limit(0);
		$service = $this->service;
		$service->sort = 'c.busiCode';
		$service->asc = false;
		$rows = $service->list_d('excel_select');
//		var_dump($rows);

		return util_excelUtil::exportSupplier ( $rows );
	}

	/**
	 * 导入供应商
	 *
	 */
	 function c_importSupplier(){
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$dao = new model_supplierManage_formal_importSupplierUtil ();
			$excelData = $dao->readExcelData ( $filename, $temp_name );
			spl_autoload_register ( '__autoload' );
			$newResult=util_jsonUtil::encode ( $excelData );;
			echo "<script>window.parent.setExcelValue('".$newResult."');self.parent.tb_remove()</script>";
		} else {
			echo "<script>alert('上传文件类型有错,请重新上传!');history.go(-1);</script>";
		}

	 }
}
?>
