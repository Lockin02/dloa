<?php
class controller_sell_staff extends model_sell_staff {
	public $show;
	
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'sell/';
	}
	
	function c_showlist() {
		$this->show->assign ( 'list', $this->model_list () );
		$this->show->display ( 'staff-list' );
	}
	
	function c_add() {
		if ($_POST) {
			if ($this->model_save ( $_POST['userid'], $_POST['tid'], $_POST['mobile'], $_POST['area'] )) {
				showmsg ( '添加成功！', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '添加失败，请与管理员联系！' );
			}
		} else {
			$data = $this->get_higher ();
			if ($data) {
				foreach ( $data as $row ) {
					$higher .= '<option value="' . $row['id'] . '">' . $row['user_name'] . '</option>';
				}
			}
			$this->show->assign ( 'higher_select', $higher );
			$data = $this->get_area ();
			if ($data) {
				foreach ( $data as $row ) {
					$area .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
				}
			}
			$this->show->assign ( 'area_select', $area );
			$this->show->display ( 'staff-add' );
		}
	}
	
	function c_edit() {
		if ($_POST) {
			if ($this->model_save ( $_POST['userid'], $_POST['tid'], $_POST['mobile'], $_POST['area'], 'edit', $_GET['id'], $_GET['key'] )) {
				showmsg ( '修改成功！', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '修改失败，请与管理员联系！' );
			}
		} else {
			$data = $this->get_info ( intval ( $_GET['id'] ) );
			if ($data) {
				foreach ( $data as $key => $val ) {
					$this->show->assign ( $key, $val );
				}
			}
			$higher = $this->get_higher ();
			if ($higher) {
				foreach ( $higher as $row ) {
					if ($row['id'] == $data['tid']) {
						$select_higher .= '<option selected value="' . $row['id'] . '">' . $row['user_name'] . '</option>';
					} else {
						$select_higher .= '<option value="' . $row['id'] . '">' . $row['user_name'] . '</option>';
					}
				}
			}
			
			$area = $this->get_area ();
			$area_id_arr = $data['area'] ? explode ( ',', $data['area'] ) : array();
			if ($area) {
				foreach ( $area as $row ) {
					if ($area_id_arr && in_array ( $row['id'], $area_id_arr )) {
						$select_area .= '<option selected value="' . $row['id'] . '">' . $row['name'] . '</option>';
					} else {
						$select_area .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
					}
				}
			}
			$this->show->assign ( 'area_select', $select_area );
			$this->show->assign ( 'higher_select', $select_higher );
			$this->show->display ( 'staff-edit' );
		}
	}
	function c_delete() {
		if (intval ( $_GET['id'] ) && $_GET['key']) {
			if ($_GET['type'] == 'yes') {
				if (!$this->get_lower ( $_GET['id'] )) {
					if ($this->model_delete ( $_GET['id'], $_GET['key'] )) {
						showmsg ( '删除成功！', 'self.parent.location.reload();', 'button' );
					} else {
						showmsg ( '删除失败，请与管理联系！', 'self.parent.location.reload();', 'button' );
					}
				}else{
					showmsg('对不起，该用户下级还有其他人员，请先删除下级人员！','self.parent.location.reload();', 'button');
				}
			} else {
				showmsg ( '您确定要删除该条记录吗？', "location.href='?model=" . $_GET['model'] . "&action=" . $_GET['action'] . "&id=" . $_GET['id'] . "&key=" . $_GET['key'] . "&type=yes'", 'button' );
			}
		} else {
			showmsg ( '非法参数！' );
		}
	}
}

?>