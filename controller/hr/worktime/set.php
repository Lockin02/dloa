<?php
/**
 * @author Michael
 * @Date 2014年4月24日 9:50:35
 * @version 1.0
 * @description:法定节假日控制层
 */
class controller_hr_worktime_set extends controller_base_action {

	function __construct() {
		$this->objName = "set";
		$this->objPath = "hr_worktime";
		parent::__construct ();
	}

	/**
	 * 跳转到法定节假日列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增法定节假日页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 新增
	 */
	function c_add() {
		$rs = $this->service->add_d($_POST[$this->objName]);
		if($rs) {
			msg( '保存成功！' );
		} else{
			msg( '保存失败！' );
		}
	}

	/**
	 * 跳转到编辑法定节假日页面
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
	 * 编辑
	 */
	function c_edit() {
		$rs = $this->service->edit_d($_POST[$this->objName]);
		if($rs) {
			msg( '保存成功！' );
		} else{
			msg( '保存失败！' );
		}
	}

	/**
	 * 跳转到查看法定节假日页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 检查该年份是否已经存在记录
	 */
	function c_checkYear() {
		$year = $_POST['year'];
		$rs = $this->service->findCount(array('year' => $year));
		if ($rs > 0) {
			echo "no";
		} else {
			echo "yes";
		}
	}
 }
?>