<?php
class controller_sell_area extends model_sell_area {
	public $show;
	
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'sell/';
	}
	/**
	 * 区域列表
	 */
	function c_showlist() {
		$this->show->assign ( 'list', $this->model_list () );
		$this->show->display ( 'area-list' );
	}
	/**
	 * 添加区域
	 */
	function c_add() {
		if ($_POST) {
			if ($this->model_save ( $_POST['name'] )) {
				showmsg ( '添加成功！', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '添加失败，请与管理员联系！' );
			}
		} else {
			$this->show->display ( 'area-add' );
		}
	}
	/**
	 * 修改区域
	 */
	function c_edit() {
		if ($_POST) {
			if ($this->model_save ( $_POST['name'], 'edit', $_GET['id'], $_GET['key'] )) {
				showmsg ( '修改成功！', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '修改失败，请与管理员联系！' );
			}
		} else {
			$this->show->display ( 'area-edit' );
		}
	}
	/**
	 * 删除区域
	 */
	function c_delete() {
		if (intval ( $_GET['id'] ) && $_GET['key']) {
			if ($_GET['type'] == 'yes') {
				if ($this->model_delete ( $_GET['id'], $_GET['key'] )) {
					showmsg ( '删除成功！', 'self.parent.location.reload();', 'button' );
				}else{
					showmsg('删除失败，请与管理员联系！');
				}
			} else {
				showmsg ( '您确定要删除该区域吗？', "location.href='?model=sell_area&action=delete&type=yes&id=" . $_GET['id'] . "&key=" . $_GET['key'] . "'",'button' );
			}
		} else {
			showmsg ( '非法访问！' );
		}
	}
}

?>