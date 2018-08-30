<?php
class controller_sell_area extends model_sell_area {
	public $show;
	
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'sell/';
	}
	/**
	 * �����б�
	 */
	function c_showlist() {
		$this->show->assign ( 'list', $this->model_list () );
		$this->show->display ( 'area-list' );
	}
	/**
	 * �������
	 */
	function c_add() {
		if ($_POST) {
			if ($this->model_save ( $_POST['name'] )) {
				showmsg ( '��ӳɹ���', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '���ʧ�ܣ��������Ա��ϵ��' );
			}
		} else {
			$this->show->display ( 'area-add' );
		}
	}
	/**
	 * �޸�����
	 */
	function c_edit() {
		if ($_POST) {
			if ($this->model_save ( $_POST['name'], 'edit', $_GET['id'], $_GET['key'] )) {
				showmsg ( '�޸ĳɹ���', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '�޸�ʧ�ܣ��������Ա��ϵ��' );
			}
		} else {
			$this->show->display ( 'area-edit' );
		}
	}
	/**
	 * ɾ������
	 */
	function c_delete() {
		if (intval ( $_GET['id'] ) && $_GET['key']) {
			if ($_GET['type'] == 'yes') {
				if ($this->model_delete ( $_GET['id'], $_GET['key'] )) {
					showmsg ( 'ɾ���ɹ���', 'self.parent.location.reload();', 'button' );
				}else{
					showmsg('ɾ��ʧ�ܣ��������Ա��ϵ��');
				}
			} else {
				showmsg ( '��ȷ��Ҫɾ����������', "location.href='?model=sell_area&action=delete&type=yes&id=" . $_GET['id'] . "&key=" . $_GET['key'] . "'",'button' );
			}
		} else {
			showmsg ( '�Ƿ����ʣ�' );
		}
	}
}

?>