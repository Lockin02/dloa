<?php
class controller_sell_clue extends model_sell_clue {
	public $show;
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'sell/';
	}
	/**
	 * 销售线索列表
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
	 * 显示销售线索详细
	 */
	function c_show_clue() {
		if (intval ( $_GET['id'] ) && $_GET['key']) {
			$data = $this->get_clue ( $_GET['id'], $_GET['key'] );
			if ($data) {
				$set_contract_link = thickbox_link('合同转化设置','b','id='.$_GET['id'].'&key='.$_GET['key'],'合同转化设置',null,'set_contract',400,300);
				foreach ( $data as $key => $val ) {
					if ($key == 'date')
						$val = date ( 'Y-m-d', $val );
					if ($key == 'status')
						$val = ($val == 1 ? '审核通过 <span>已分派给：'.$data['sales_name'].'</span>' : ($val == - 1 ? '被打回' : '待审核'));
					if ($key == 'contract')
						$val = $val==1 ? '是' : ($val==-1 ? '失败' : '未设置');
					$this->show->assign ( $key, $val );
				}
			}
			$row = $this->get_explorer ( $data['areaid'] );
			if ($_SESSION['USER_ID'] == $row['userid'] && $data['status']==0) {
				$audit_link = thickbox_link ( '审核线索', 'b', 'id=' . $_GET['id'] . '&key=' . $_GET['key'], null, null, 'audit_clue', 400, 250 );
			}
			if ($data['status']==-1)
			{
				$retrun = '<tr><td align="right">打回理由：</td><td align="left">'.$data['notse'].'</td></tr>';
			}
			if ($data['contract']==-1)
			{
				$reason = '<tr><td align="right">失败理由：</td><td align="left">'.$data['reason'].'</td></tr>';
			}
			$this->show->assign('return',$retrun);
			$this->show->assign('reason', $reason);
			$this->show->assign ( 'audit_link', $audit_link );
			$this->show->assign('set_contract_link',(($data['sales']==$_SESSION['USER_ID'] || $_SESSION['USER_ID'] == $row['userid']) && $data['status']==1 && $data['contract']==0 ? $set_contract_link : ''));
			$this->show->display ( 'clue-info' );
		}
	}
	/**
	 * 添加线索
	 */
	function c_add_clue() {
		if ($_POST) {
			$data = $_POST;
			$data['userid'] = $_SESSION['USER_ID'];
			$data['date'] = time ();
			if ($this->model_save_clue ( $data )) {
				showmsg ( '添加成功！', 'self.parent.location.reload();', 'button' );
			} else {
				showmsg ( '操作失败，请与管理员联系！' );
			}
		} else {
			$this->show->assign ( 'area_select', $this->model_area_select () );
			$this->show->display ( 'clue-add' );
		}
	}
	/**
	 * 修改线索
	 */
	function c_edit_clue() {
		
		if ($_GET['id'] && $_GET['key']) {
			if ($_POST) {
				$data = $_POST;
				if ($this->model_save_clue ( $data, 'update', $_GET['id'], $_GET['key'] )) {
					showmsg ( '修改成功！', 'self.parent.location.reload();', 'button' );
				} else {
					showmsg ( '修改失败，请与管理员联系！' );
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
			showmsg ( '非法访问！' );
		
		}
	}
	/**
	 * 审核线索
	 */
	function c_audit_clue() {
		if ($_POST) {
			$sales = $_POST['status'] == 1 ? $_POST['sales'] : '';
			$notse = $_POST['status']!=1 ? $_POST['notse'] : '';
			if ($this->model_audit_clue($_GET['id'], $_GET['key'],$_POST['status'],$sales,$notse))
			{
				showmsg('审核成功！', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('审核成功，请与管理员联系！', 'self.parent.location.reload();', 'button');
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
	 * 设置合同状态
	 */
	function c_set_contract()
	{
		if ($_POST)
		{
			if ($this->model_set_contract($_GET['id'],$_GET['key'],$_POST['contract'],$_POST['reason']))
			{
				showmsg('设置成功！', 'self.parent.location.reload();', 'button');
			}else{
				showmsg('设置失败，请与管理员联系！');
			}
		}else{
			$this->show->display('set-contract');
		}
	}
	
	/**
	 * 导出EXCEL表
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