<?php
/**
 *
 * 资产采购任务控制层类
 * @author fengxw
 *
 */
class controller_asset_purchase_task_task extends controller_base_action {

	function __construct() {
		$this->objName = "task";
		$this->objPath = "asset_purchase_task";
		parent::__construct ();
	}

	/*
	 * 跳转到资产采购任务
	 */
    function c_page() {
      $this->view("list");
    }

    /**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$applyId=isset($_GET['id'])?$_GET['id']:"";
		$this->assign ( 'sendId', $_SESSION ['USER_ID'] );
		$this->assign ( 'sendName', $_SESSION ['USERNAME'] );

		$this->assign('applyId',$applyId);
		$this->assign('sendTime', date("Y-m-d"));
		$this->view ( 'add' );

	}
	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * 跳转到个人采购任务信息列表
	 */
	function c_pageMyList() {
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->view('list-myTs');
	}

	/**
	 * 改变单据状态
 	 */
	function c_submit() {
		try {
			$id = isset ($_GET['id']) ? $_GET['id'] : false;
			$object=array("id"=>$id,"state"=>"已接收","acceptDate"=>date("Y-m-d"));
			$this->service->updateById($object);
			echo 1;
		} catch (Exception $e) {
			throw $e;
			echo 0;
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '下达任务成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
}

?>