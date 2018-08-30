<?php
class controller_purchase extends model_device_purchase
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'purchase';
		$this->pk = 'id';
		$this->show = new show();
	}

	function c_index()
	{

	}
	/**
	 * 显示申请采购页
	 *
	 */
	function c_show_apply()
	{
		$this->tbl_name = 'device_type';
		$this->show->assign('USER_NAME',$_SESSION['USERNAME']);
		$this->show->assign('userid',$_SESSION['USER_ID']);
		$this->show->assign('deptid',$_SESSION['DEPT_ID']);
		$this->show->assign('DEPT_NAME',$this->get_table_fields('department',"DEPT_ID=".$_SESSION['DEPT_ID']."",'DEPT_NAME'));
		$this->show->assign('select_type',$this->select_type());
		$this->show->display('purchase_apply');
	}
	/**
	 * 修改采购申请
	 *
	 */
	function c_edit_apply()
	{
		if (intval($_GET['id']))
		{
			$row = $this->find('id='.$_GET['id']);
			foreach ($row as $key=>$val)
			{
				if ($key=='use_date') $val = date('Y-m-d',$val);
				$this->show->assign($key,$val);
			}
			$this->tbl_name = 'user';
			$rs = $this->find("USER_ID='".$row['userid']."'",null,'USER_NAME');
			$this->show->assign('USER_NAME',$rs['USER_NAME']);
			$this->tbl_name = 'department';
			$rs = $this->find('DEPT_ID='.$row['deptid'],null,'DEPT_NAME');
			$this->show->assign('DEPT_NAME',$rs['DEPT_NAME']);
			$this->show->assign('select_type',$this->select_type($row['typeid']));
			$this->show->display('purchase_editapply');
		}
	}
	/**
	 * 更新申请
	 *
	 */
	function c_update_apply()
	{
		if (intval($_GET['id']))
		{
			if ($this->model_update_applay())
			{
				showmsg('操作成功！','self.parent.tb_remove();self.parent.location.reload();','button');
			}else{
				showmsg('操作操作！','self.parent.tb_remove();','button');
			}
		}else{
			showmsg('非法操作！','self.parent.tb_remove();','button');
		}
	}
	/**
	 * 提交申请
	 *
	 */
	function c_add_apply()
	{
		if ($this->model_insert_apply())
		{
			showmsg('提交成功！');
		}else{
			showmsg('提交失败！');
		}
	}
	/**
	 * 显示申请记录列表
	 *
	 */
	function c_applylist()
	{
		$this->show->assign('list',$this->model_applylist());
		$this->show->display('purchase_applylist');
	}
	/**
	 * 审批记录
	 *
	 */
	function c_auditlist()
	{
		$this->show->assign('list',$this->model_applylist('audit'));
		$this->show->display('purchase_auditlist');
	}
	/**
	 * 等待采购信息
	 *
	 */
	function c_wait_buy()
	{
		$this->show->assign('list',$this->model_applylist('wait'));
		$this->show->display('purchase_waitbuylist');
	}
	/**
	 * 确认审核
	 *
	 */
	function c_audit_confirm()
	{
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('state',$_GET['state']);
		if ($_GET['state']==1)
		{
			$this->show->assign('msg','您确定要通过该条申请吗？');
			$this->show->assign('action','approval');
			$this->show->display('purchase_audit');
		}elseif ($_GET['state']==2){
			$this->show->assign('msg','您确定开始采购该设备吗？');
			$this->show->assign('action','set_state');
			$this->show->display('purchase_audit');
		}elseif ($_GET['state']==3){
			$this->show->assign('msg','您确定该设备采购完毕了吗？');
			$this->show->assign('action','set_state');
			$this->show->display('purchase_audit');
		}elseif ($_GET['state']== -1){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('state',$_GET['state']);
			$this->show->assign('title','退回申请');
			$this->show->display('purchase_cancel');
		}elseif ($_GET['state']== -2){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('state',$_GET['state']);
			$this->show->assign('title','无法采购');
			$this->show->display('purchase_cancel');
		}
	}
	/**
	 * 审批
	 *
	 */
	function c_approval()
	{
		if (intval($_GET['id']) && $_GET['state'] <= 1)
		{
			if ($this->model_set_state())
			{
				showmsg('操作成功！','self.parent.tb_remove();self.parent.location.reload();','button');
			}else{
				showmsg('操作失败！','self.parent.tb_remove();','button');
			}
		}else{
			showmsg('非法操作！','self.parent.tb_remove();','button');
		}
	}
	function c_cancel_confirm()
	{
		if (intval($_GET['id']) && $_GET['state'] == -3)
		{
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('state',$_GET['state']);
			$this->show->assign('title','取消采购');
			$this->show->display('purchase_cancel');
			
		}else{
			showmsg('非法操作！','self.parent.tb_remove();','button');
		}
	}
	/**
	 * 取消购买
	 *
	 */
	function c_cancel()
	{
		if (intval($_GET['id'] && $_GET['state'] < 0 && $_POST['notse']))
		{
			if ($this->update('id='.$_GET['id'],array('state'=>$_GET['state'],'notse'=>$_POST['notse'])))
			{
				showmsg('操作成功！','self.parent.tb_remove();self.parent.location.reload();','button');
			}
		}else{
			showmsg('操作失败！','self.parent.tb_remove();','button');
		}
	}
	/**
	 * 设置状态
	 *
	 */
	function c_set_state()
	{
		if (intval($_GET['id']) && $_GET['state'] > 1)
		{
			if ($this->model_set_state())
			{
				showmsg('操作成功！','self.parent.tb_remove();self.parent.location.reload();','button');
			}else{
				showmsg('操作失败！','self.parent.tb_remove();','button');
			}
		}
	}
	/**
	 * 显示申请记录单条详细信息
	 *
	 */
	function c_applyinfo()
	{
		$row = $this->model_applyinfo();
		if ($row)
		{
			foreach ($row as $key=>$val)
			{
				if ($key=='date') $val = date('Y-m-d H:i:s');
				if ($key=='use_date') $val= date('Y-m-d');
				if ($key=='state')
				{
					if ($val==-1)
					{
						$val = '退回申请';
						$notse = '<tr><td><b>退回说明：</b></td><td>'.$row['notse'].'</td><tr>';
					}elseif ($val == -2){
						$val = '无法采购';
						$notse = '<tr><td><b>无法采购：</b></td><td>'.$row['notse'].'</td><tr>';
					}elseif ($val == -3){
						$val = '取消采购';
						$notse = '<tr><td><b>取消说明：</b></td><td>'.$row['notse'].'</td><tr>';
					}elseif ($val==0){
						$val = '待审核';
					}elseif ($val==1){
						$val = '待采购';
					}elseif ($val==2){
						$val = '采购中';
					}elseif ($val==3){
						$val = '采购完成';
					}
				}
				$this->show->assign('notse_html',$notse);
				$this->show->assign($key,$val);
			}
			$this->show->display('purchase_applyinfo');
		}else{
			showmsg('错误ID参数！');
		}
	}
}
?>