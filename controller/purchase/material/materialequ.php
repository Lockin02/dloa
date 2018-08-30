<?php
/**
 * @author Show
 * @Date 2013年12月10日 星期二 17:12:50
 * @version 1.0
 * @description:物料协议价明细表控制层
 */
class controller_purchase_material_materialequ extends controller_base_action {

	function __construct() {
		$this->objName = "materialequ";
		$this->objPath = "purchase_material";
		parent::__construct ();
	 }

	/**
	 * 跳转到物料协议价明细表列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增物料协议价明细表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑物料协议价明细表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

   /**
	 * 跳转到查看物料协议价明细表页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );

		if ($obj['isEffective'] == 'on') {
			$obj['isEffective'] ='是';
		}else {
			$obj['isEffective'] = '否';
		}

		if ($obj['lowerNum'] == 0) {
			$obj['lowerNum'] = "<span style='color:red'>-</span>";
		}
		if ($obj['ceilingNum'] == 0) {
			$obj['ceilingNum'] = "<span style='color:red'>-</span>";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 }
?>