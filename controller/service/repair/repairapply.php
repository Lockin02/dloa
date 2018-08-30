<?php
/**
 * @author huangzf
 * @Date 2011年12月1日 14:17:53
 * @version 1.0
 * @description:维修申请单控制层
 */
class controller_service_repair_repairapply extends controller_base_action {

	function __construct() {
		$this->objName = "repairapply";
		$this->objPath = "service_repair";
		parent::__construct ();
	}

	/**
	 * 跳转到维修申请单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 个人维修申请
	 */
	function c_myList(){
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->view("mylist");
	}


	/**
	 * 跳转到新增维修申请单页面
	 */
	function c_toAdd() {
		$this->assign ( 'applyUserName', $_SESSION ['USERNAME'] );
		$this->assign ( 'applyUserCode', $_SESSION ['USER_ID'] );
		$this->assign ( 'applyDeptId', $_SESSION ['DEPT_ID'] );
		$this->assign ( 'applyDeptName', $_SESSION ['DEPT_NAME'] );
		$this->assign ( 'docDate', day_date );
		$this->view ( 'add' );
	}
	/**
	 * 跳转到进行确认报价
	 */
	function c_toQuote() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtQuote ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'quote' );
	}

	/**
	 * 跳转到编辑维修申请单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看维修申请单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'view' );
	}

	/**
	 *
	 * 导出搜索
	 */
	function c_toExportSearch() {
		$this->view ( 'export-search' );
	}

	/**
	 *
	 * 导出维修历史EXCEL
	 */
	function c_toExportExcel() {
		$service = $this->service;
		$service->getParam ( $_GET ); //设置前台获取的参数信息
		$dataArr = $service->listBySqlId ( "select_export" );
		$dao = new model_service_accessorder_serviceExcelUtil ();
		return $dao->ExportHistoryRepairExcel ( $dataArr );
	}

	/**
	 *
	 * 保存最终报价信息
	 */
	function c_quote() {
		$service = $this->service;
		if ($service->quote_d ( $_POST [$this->objName] )) {
			echo "<script>alert('确认成功!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('确认失败!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 *
	 * 新增维修费用减免,获取从表数据，拼装从表表单模板
	 */
	function c_getItemStrAtReduce() {
		$obj = $this->service->get_d ( $_POST ['reduceapplyId'] );
		$itemList = $this->service->showItemAtReduce ( $obj ['items'] );
		echo util_jsonUtil::iconvGB2UTF ( $itemList );
	}

	/**
	 *
	 * 新增维修设备更换,获取从表数据，拼装从表表单模板
	 */
	function c_getItemStrAtChange() {
		$obj = $this->service->get_d ( $_POST ['changeapplyId'] );
		$itemList = $this->service->showItemAtChange ( $obj ['items'] );
		echo util_jsonUtil::iconvGB2UTF ( $itemList );
	}

	/**
	 * 查看页面Tab
	 */
	function c_viewTab() {
		$this->assign ( "skey", $_GET ['skey'] );
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}

	/**
	 * 新增对象操作
	 *
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		if ($id) {
			echo "<script>alert('新增成功!');window.opener.window.show_page();window.close();  </script>";
		} else {
			echo "<script>alert('新增失败!');window.opener.window.show_page();window.close();  </script>";
		}
	}

	/**
	 * 修改对象操作
	 *
	 */
	function c_edit() {
		if ($this->service->edit_d ( $_POST [$this->objName], true )) {
			echo "<script>alert('修改成功!');window.opener.window.show_page();window.close(); </script>";
		} else {
			echo "<script>alert('修改失败!');window.opener.window.show_page();window.close(); </script>";
		}
	}

	/**
	 *
	 * 关闭完成
	 */
	function c_closeFinished() {
		$object = array ("docStatus" => "YWC", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}
}
?>