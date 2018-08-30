<?php
/**
 * @author longqb
 * @Date 2011年12月3日 10:32:24
 * @version 1.0
 * @description:维修费用减免申请单控制层
 */
class controller_service_reduce_reduceapply extends controller_base_action {

	function __construct() {
		$this->objName = "reduceapply";
		$this->objPath = "service_reduce";
		parent::__construct ();
	}

	/**
	 * 跳转到维修费用减免申请单列表
	 */
	function c_page() {
		$this->assign ( "userId", $_SESSION ['USER_ID'] );
		$this->view ( 'list' );
	}

	/**
	 * 跳转到新增维修费用减免申请单页面
	 */
	function c_toAdd() {
		$this->assign ( 'applyUserName', $_SESSION ['USERNAME'] );
		$this->assign ( "applyUserCode", $_SESSION ['USER_ID'] );
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑维修费用减免申请单页面
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
	 * 跳转到查看维修费用减免申请单页面
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
	 * 新增对象操作
	 * @longqb
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/reduce/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&examCode=oa_service_reduce_apply&formName=费用减免申请单审批' );
			} else {
				echo "<script>alert('新增成功!');window.opener.window.show_page();window.close();</script>";
			}
		} else {
			if ("audit" == $actType) {
				echo "<script>alert('审批新增失败!');window.opener.window.show_page();window.close();</script>";
			} else {
				echo "<script>alert('新增失败!');window.opener.window.show_page();window.close();</script>";
			}

		}
	}

	/**
	 * 修改对象操作
	 * @longqb
	 */
	function c_edit() {
		$reduceapplyObj = $_POST [$this->objName];
		$id = $this->service->edit_d ( $reduceapplyObj, true );
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if ($id) {
			if ("audit" == $actType) {
				succ_show ( 'controller/service/reduce/ewf_index.php?actTo=ewfSelect&billId=' . $reduceapplyObj ['id'] . '&examCode=oa_service_reduce_apply&formName=费用减免申请单审批' );
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
	 * 查看页面Tab
	 */
	function c_viewTab() {
		$this->assign ( "skey", $_GET ['skey'] );
		$this->assign ( 'id', $_GET ['id'] );
		$this->display ( 'view-tab' );
	}

	/**
	 *
	 * 审批完成的时,更新对应申请单的减免费用
	 */
	function c_dealAfterAudit() {
		//审批流回调方法
        $this->service->workflowCallBack($_GET['spid']);
		echo "<script>window.location =\"index1.php?model=common_workflow_workflow&action=auditingList\";</script>";

	}

	/**
	 * 根据传入的信息获取费用减免申请信息
	 */
	function c_getReduceapplyList() {
		$this->assign ( "applyId", $_GET ['applyId'] );
		$this->view ( 'detail-list' );
	}

}
?>