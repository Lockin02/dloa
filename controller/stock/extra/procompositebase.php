<?php
/**
 * @author huangzf
 * @Date 2012年6月1日 星期五 11:27:45
 * @version 1.0
 * @description:产品物料库存采购销售综合表基本信息控制层 
 */
class controller_stock_extra_procompositebase extends controller_base_action {
	
	function __construct() {
		$this->objName = "procompositebase";
		$this->objPath = "stock_extra";
		parent::__construct ();
	}
	
	/**
	 * 跳转到产品物料库存采购销售综合表基本信息列表
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增产品物料库存采购销售综合表基本信息页面
	 */
	function c_toAdd() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year - 3; $i < $year + 3; $i ++) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( "yearStr", $yearStr );
		$this->view ( 'add' );
	}
	
	/**
	 * 跳转到编辑产品物料库存采购销售综合表基本信息页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$year = $obj ['activeYear'];
		$yearStr = "";
		for($i = $year - 3; $i < $year + 3; $i ++) {
			if ($i == $year) {
				$yearStr .= "<option value=$i selected>" . $i . "年</option>";
			} else {
				$yearStr .= "<option value=$i>" . $i . "年</option>";
			}
		
		}
		$this->assign ( "yearStr", $yearStr );
		$this->view ( 'edit' );
	}
	
	/**
	 * 跳转到查看产品物料库存采购销售综合表基本信息页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ) );
		$this->view ( 'view' );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see controller_base_action::c_add()
	 */
	function c_add() {
		$service = $this->service;
		if ($service->add_d ( $_POST [$this->objName] )) {
			echo "<script>alert('新增成功!'); window.opener.window.show_page();window.close();  </script>";
		} else {
			echo "<script>alert('新增失败!'); window.opener.window.show_page();window.close();  </script>";
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see controller_base_action::c_edit()
	 */
	function c_edit() {
		$service = $this->service;
		if ($service->edit_d ( $_POST [$this->objName] )) {
			echo "<script>alert('修改成功!'); window.opener.window.show_page();window.close();  </script>";
		} else {
			echo "<script>alert('修改失败!'); window.opener.window.show_page();window.close();  </script>";
		}
	}
	
	/**
	 * 
	 * 激活报表
	 */
	function c_activeReport() {
		$service = $this->service;
		if ($service->activeReport ( $_POST ['id'] )) {
			echo "0";
		} else {
			echo "1";
		}
	}
	
	/**
	 * 
	 * 获取销售合同设备所有附属物料的执行中数量
	 */
	function c_getConEquNum() {
		$service = $this->service;
		echo $service->getConEquNum ( $_POST ['id'] );
	}
	
	/**
	 * 
	 * 获取设备附属物料的库存商品仓数量
	 */
	function c_getProActNum() {
		$service = $this->service;
		echo $service->getProActNum ( $_POST ['id'] );
	}
	
	/**
	 * 
	 * 查看常用设备激活报表
	 */
	function c_toViewActReport() {
		$this->permCheck (); //安全校验
		$object = $this->service->find ( array ("isActive" => "1" ) );
		
		if ($object) {
			$obj = $this->service->get_d ( $object ['id'] );
			foreach ( $obj as $key => $val ) {
				$this->assign ( $key, $val );
			}
			$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ) );
			$this->view ( 'view' );
		} else {
			echo "没有有效信息";
		}
	
	}
}
?>