<?php
class controller_sell_clue extends model_sell_clue {
	public $show;
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'sell/';
	}
	/**
	 * ���������б�
	 */
	function c_showlist() {
		$this->show->assign ( 'area_select', $this->model_area_select ( $_GET['areaid'] ) );
		$this->show->assign ( 'list', $this->model_list () );
		$this->show->assign('username',$_GET['username']);
		$this->show->display ( 'clue-list' );
	}
	
	function c_countlist()
	{
		$this->show->assign ( 'area_select', $this->model_area_select ( $_GET['areaid'] ) );
		$this->show->assign('username',$_GET['username']);
		$this->show->assign('list',$this->model_count_list());
		$this->show->display('clue-count-list');
	}
	/**
	 * ��ʾ����������ϸ
	 */
	function c_show_clue() {
		if (intval ( $_GET['id'] ) && $_GET['key']) {
			$data = $this->get_clue ( $_GET['id'], $_GET['key'] );
			if ($data) {
				$set_contract_link = thickbox_link('��ͬת������','b','id='.$_GET['id'].'&key='.$_GET['key'],'��ͬת������',null,'set_contract',400,300);
				foreach ( $data as $key => $val ) {
					if ($key == 'date')
						$val = date ( 'Y-m-d', $val );
					if ($key == 'status')
						$val = ($val == 1 ? '���ͨ�� <span>�ѷ��ɸ���'.$data['sales_name'].'</span>' : ($val == - 1 ? '�����' : '�����'));
					if ($key == 'contract')
						$val = $val==1 ? '��' : ($val==-1 ? 'ʧ��' : 'δ����');
					$this->show->assign ( $key, $val );
				}
			}
			$row = $this->get_explorer ( $data['areaid'] );
			if ($_SESSION['USER_ID'] == $row['userid'] && $data['status']==0) {
				$audit_link = thickbox_link ( '�������', 'b', 'id=' . $_GET['id'] . '&key=' . $_GET['key'], null, null, 'audit_clue', 400, 250 );
			}
			if ($data['status']==-1)
			{
				$retrun = '<tr><td align="right">������ɣ�</td><td align="left">'.$data['notse'].'</td></tr>';
			}
			if ($data['contract']==-1)
			{
				$reason = '<tr><td align="right">ʧ�����ɣ�</td><td align="left">'.$data['reason'].'</td></tr>';
			}
			$this->show->assign('return',$retrun);
			$this->show->assign('reason', $reason);
			$this->show->assign ( 'audit_link', $audit_link );
			$this->show->assign('set_contract_link',(($data['sales']==$_SESSION['USER_ID'] || $_SESSION['USER_ID'] == $row['userid']) && $data['status']==1 && $data['contract']==0 ? $set_contract_link : ''));
			$this->show->display ( 'clue-info' );
		}
	}
	/**
	 * �������
	 */
	function c_add_clue() {
		if ($_POST) {
			$data = $_POST;
			$data['userid'] = $_SESSION['USER_ID'];
			$data['date'] = time ();
			if ($this->model_save_clue ( $data )) {
				showmsg ( '��ӳɹ���', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '����ʧ�ܣ��������Ա��ϵ��' );
			}
		} else {
			$this->show->assign ( 'area_select', $this->model_area_select () );
			$this->show->display ( 'clue-add' );
		}
	}
	/**
	 * �޸�����
	 */
	function c_edit_clue() {
		
		if ($_GET['id'] && $_GET['key']) {
			if ($_POST) {
				$data = $_POST;
				if ($this->model_save_clue ( $data, 'update', $_GET['id'], $_GET['key'] )) {
					showmsg ( '�޸ĳɹ���', 'self.parent.location.reload();', 'button' );
				} else {
					showmsg ( '�޸�ʧ�ܣ��������Ա��ϵ��' );
				}
			} else {
				$data = $this->get_clue ( $_GET['id'], $_GET['key'] );
				if ($data) {
					foreach ( $data as $key => $val ) {
						$this->show->assign ( $key, $val );
					}
				}
				$this->show->assign ( 'area_select', $this->model_area_select ( $data['areaid'] ) );
				$this->show->display ( 'clue-edit' );
			}
		} else {
			showmsg ( '�Ƿ����ʣ�' );
		
		}
	}
	/**
	 * �������
	 */
	function c_audit_clue() {
		if ($_POST) {
			$sales = $_POST['status'] == 1 ? $_POST['sales'] : '';
			$notse = $_POST['status']!=1 ? $_POST['notse'] : '';
			if ($this->model_audit_clue($_GET['id'], $_GET['key'],$_POST['status'],$sales,$notse))
			{
				showmsg('��˳ɹ���', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('��˳ɹ����������Ա��ϵ��', 'self.parent.location.reload();', 'button');
			}
		} else {
			$data = $this->get_clue ( $_GET['id'], $_GET['key'] );
			if ($data) {
				$row = $this->get_explorer ( $data['areaid'] );
				$user = $this->get_lower ( $row['tid'] );
				if ($user) {
					foreach ( $user as $rs ) {
						$user_select .= '<option value="' . $rs['userid'] . '">' . $rs['user_name'] . '</option>';
					}
				}
				$this->show->assign ( 'user_select', $user_select );
				$this->show->display ( 'clue-audit' );
			}
		}
	}
	/**
	 * ���ú�ͬ״̬
	 */
	function c_set_contract()
	{
		if ($_POST)
		{
			if ($this->model_set_contract($_GET['id'],$_GET['key'],$_POST['contract'],$_POST['reason']))
			{
				showmsg('���óɹ���', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('����ʧ�ܣ��������Ա��ϵ��');
			}
		}else{
			$this->show->display('set-contract');
		}
	}
	
	/**
	 * ����EXCEL��
	 */
	function c_export()
	{
		if ($_GET['type']=='list')
		{
			$this->model_list_export();
		}else if($_GET['type']=='count'){
			$this->model_count_export();
		}
	}
}

?>