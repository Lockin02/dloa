<?php
/**
 * @author longqb
 * @Date 2011年11月27日 15:50:15
 * @version 1.0
 * @description:零配件订单控制层
 */
class controller_service_accessorder_accessorder extends controller_base_action {

	function __construct() {
		$this->objName = "accessorder";
		$this->objPath = "service_accessorder";
		parent::__construct ();
	}
	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ("select_list");
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到零配件订单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}

	/**
	 * 跳转到待出库零配件订单列表
	 */
	function c_outstockpage() {
		$this->view ( 'outstock-list' );
	}

	/**
	 * 跳转到新增零配件订单页面
	 */
	function c_toAdd() {
		$this->assign ( 'chargeUserName', $_SESSION ['USERNAME'] );
		$this->assign ( 'chargeUserCode', $_SESSION ['USER_ID'] );
		$this->assign ( 'docDate', day_date );
		$configenumDao = new model_system_configenum_configenum ();
		$this->assign ( "warranty", $configenumDao->getEnumFieldVal ( "1", "configEnum1" ) );
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑零配件订单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
        $obj['file'] = $this->service->getFilesByObjId($_GET['id']);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( 'docDate', day_date );
		$configenumDao = new model_system_configenum_configenum ();
		$this->assign ( "warranty", $configenumDao->getEnumFieldVal ( "1", "configEnum1" ) );
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看零配件订单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
        $obj['file'] = $this->service->getFilesByObjId($_GET['id'],false);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		if (isset ( $_GET ['viewBtn'] )) {
			$this->assign ( 'showBtn', 1 );
		} else {
			$this->assign ( 'showBtn', 0 );
		}
		if($obj['isBill']=="0"){
			$this->assign("isBill","否");
		}else{
			$this->assign("isBill","是");
		}
		if($obj['deliveryCondition']=="0"){
			$this->assign("deliveryCondition", "款到发货");
		}else{
			$this->assign("deliveryCondition","货到付款");
		}
		$this->view ( 'view' );
	}

	/**
	 * 新增对象操作
	 * @linzx
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/accessorder/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_service_accessorder&formName=零配件订单审批' );
			} else {
				echo "<script>alert('新增成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('新增失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

	/**
	 * 修改对象操作
	 * @linzx
	 */
	function c_edit() {
		$accessorderObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $accessorderObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/accessorder/ewf_index.php?actTo=ewfSelect&billId=' . $accessorderObj ['id'] . '&examCode=oa_service_accessorder&formName=零配件订单审批' );
			} else {
				echo "<script>alert('修改成功!');self.parent.show_page();self.parent.tb_remove();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			} else {
				echo "<script>alert('修改失败!');self.parent.show_page();self.parent.tb_remove();</script>";
			}

		}
	}

	/**
	 * 查看页面Tab
	 */
	function c_viewTab() {
		$this->permCheck (); //安全校验
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}

	/**
	 *
	 * 确认关闭完成
	 */
	function c_closeFinished() {
		$object = array ("docStatus" => "YWC", "id" => $_GET ['id'] );
		if ($this->service->updateById ( $object ))
			echo 0;
		else
			echo 1;
	}

	/**
	 * 导出未到款完毕订单
	 *
	 */
	function c_toExportNotIncomeExcel() {
		$dataArr = $this->service->listBySqlId ("select_notincome");
		$dao = new model_service_accessorder_serviceExcelUtil ();
//
//		echo "<pre>";
//		print_r($dataArr);
		return $dao->ExportNotIncomeExcel ( $dataArr );
	}

	/**
	 * 判断金额是否为0
	 */
	function c_orderMoneyIsZero(){
		$obj = $this->service->get_d($_POST['id']);
		exit($obj['saleAmount']);
	}
}
?>