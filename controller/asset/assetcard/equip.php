<?php

/**
 * 附属设备控制层类
 * @author chenzb
 */
class controller_asset_assetcard_equip extends controller_base_action {

	public $provArr;

	function __construct() {
		$this->objName = "equip";
		$this->objPath = "asset_assetcard";
		parent::__construct ();
	}
	/**
	 * 跳转到附属设备信息列表
	 */
	function c_page() {
		$this->assign('assetId',$_GET['assetId']);
		$this->assign('assetCode',$_GET['assetCode']);
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('asset_assetcard_assetcard',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$this->assign('sysLimit', $sysLimit['附属设备删除权限']);
		$this->view ( 'list' );
	}
 /*
   *  跳转到附属设备信息列表
	 */
	function c_toPage() {
		$this->assign('assetId',$_GET['assetId']);
		$this->view ( 'grid-list' );
	}


	/**
	 * 跳转到附属设备新增页面
	 */

	function c_toAdd() {
		$this->assign('assetId',$_GET['assetId']);
		$this->assign('assetCode',$_GET['assetCode']);
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
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}


}
?>