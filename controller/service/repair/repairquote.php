<?php
/**
 * @author huangzf
 * @Date 2011年12月3日 10:11:04
 * @version 1.0
 * @description:维修报价申报单控制层
 */
class controller_service_repair_repairquote extends controller_base_action {
	
	function __construct() {
		$this->objName = "repairquote";
		$this->objPath = "service_repair";
		parent::__construct ();
	}
	
	/**
	 * 跳转到维修报价申报单列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到维修报价管理列表
	 */
	function c_pageTab() {
		$this->view ( 'tab' );
	}
	
	/**
	 * 跳转到新增维修报价申报单页面
	 */
	function c_toAdd() {
		$spplyItemDao = new model_service_repair_applyitem ();
		if (! empty ( $_GET ['itemIds'] )) {
			$spplyItemDao->searchArr ['ids'] = $_GET ['itemIds'];
			$rs = $spplyItemDao->listBySqlId ();
			$this->assign ( "itemsList", $this->service->showItemAtAdd ( $rs ) );
		}
		$this->assign ( "chargeUserCode", $_SESSION ['USER_ID'] );
		$this->assign ( "chargeUserName", $_SESSION ['USERNAME'] );
		$this->assign ( "docDate", date ( 'Y-m-d H:i:s' ) );
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑维修报价申报单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->assign ( "docDate", date ( 'Y-m-d H:i:s' ) );
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看维修报价申报单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
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
		$this->view ( 'view' );
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
		$object = $_POST [$this->objName];
		$id = $this->service->add_d ( $object, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$maxCost=$this->service->getItemMaxMoney($id);
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/repair/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=' . $maxCost . '&examCode=oa_service_repair_quote&formName=维修报价审批' );
			} else {
				echo "<script>alert('确认报价成功!');window.opener.window.show_page();window.close();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批新增失败!');window.opener.window.show_page();window.close();</script>";
			} else {
				echo "<script>alert('确认报价失败!');window.opener.window.show_page();window.close();</script>";
			}
		
		}
	}
	
	/**
	 * 编辑对象操作
	 *
	 */
	function c_edit() {
		$repairquoteObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $repairquoteObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$maxCost=$this->service->getItemMaxMoney($id);
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/repair/ewf_index.php?actTo=ewfSelect&billId=' . $repairquoteObj ['id'] . '&flowMoney=' . $maxCost . '&examCode=oa_service_repair_quote&formName=维修报价审批' );
			} else {
				echo "<script>alert('修改成功!');window.opener.window.show_page();window.close();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批修改失败!');window.opener.window.show_page();window.close();</script>";
			} else {
				echo "<script>alert('修改失败!');window.opener.window.show_page();window.close();</script>";
			}
		
		}
	}
	
	/**
	 * 
	 * 获取报价审批清单最大费用
	 */
	function c_getItemMaxMoney() {
		echo $this->service->getItemMaxMoney ( $_POST ['id'] );
	}
}
?>