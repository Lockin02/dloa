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
	 * ��ʾ����ɹ�ҳ
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
	 * �޸Ĳɹ�����
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
	 * ��������
	 *
	 */
	function c_update_apply()
	{
		if (intval($_GET['id']))
		{
			if ($this->model_update_applay())
			{
				showmsg('�����ɹ���','self.parent.tb_remove();self.parent.location.reload();','button');
			}else{
				showmsg('����������','self.parent.tb_remove();','button');
			}
		}else{
			showmsg('�Ƿ�������','self.parent.tb_remove();','button');
		}
	}
	/**
	 * �ύ����
	 *
	 */
	function c_add_apply()
	{
		if ($this->model_insert_apply())
		{
			showmsg('�ύ�ɹ���');
		}else{
			showmsg('�ύʧ�ܣ�');
		}
	}
	/**
	 * ��ʾ�����¼�б�
	 *
	 */
	function c_applylist()
	{
		$this->show->assign('list',$this->model_applylist());
		$this->show->display('purchase_applylist');
	}
	/**
	 * ������¼
	 *
	 */
	function c_auditlist()
	{
		$this->show->assign('list',$this->model_applylist('audit'));
		$this->show->display('purchase_auditlist');
	}
	/**
	 * �ȴ��ɹ���Ϣ
	 *
	 */
	function c_wait_buy()
	{
		$this->show->assign('list',$this->model_applylist('wait'));
		$this->show->display('purchase_waitbuylist');
	}
	/**
	 * ȷ�����
	 *
	 */
	function c_audit_confirm()
	{
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('state',$_GET['state']);
		if ($_GET['state']==1)
		{
			$this->show->assign('msg','��ȷ��Ҫͨ������������');
			$this->show->assign('action','approval');
			$this->show->display('purchase_audit');
		}elseif ($_GET['state']==2){
			$this->show->assign('msg','��ȷ����ʼ�ɹ����豸��');
			$this->show->assign('action','set_state');
			$this->show->display('purchase_audit');
		}elseif ($_GET['state']==3){
			$this->show->assign('msg','��ȷ�����豸�ɹ��������');
			$this->show->assign('action','set_state');
			$this->show->display('purchase_audit');
		}elseif ($_GET['state']== -1){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('state',$_GET['state']);
			$this->show->assign('title','�˻�����');
			$this->show->display('purchase_cancel');
		}elseif ($_GET['state']== -2){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('state',$_GET['state']);
			$this->show->assign('title','�޷��ɹ�');
			$this->show->display('purchase_cancel');
		}
	}
	/**
	 * ����
	 *
	 */
	function c_approval()
	{
		if (intval($_GET['id']) && $_GET['state'] <= 1)
		{
			if ($this->model_set_state())
			{
				showmsg('�����ɹ���','self.parent.tb_remove();self.parent.location.reload();','button');
			}else{
				showmsg('����ʧ�ܣ�','self.parent.tb_remove();','button');
			}
		}else{
			showmsg('�Ƿ�������','self.parent.tb_remove();','button');
		}
	}
	function c_cancel_confirm()
	{
		if (intval($_GET['id']) && $_GET['state'] == -3)
		{
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('state',$_GET['state']);
			$this->show->assign('title','ȡ���ɹ�');
			$this->show->display('purchase_cancel');
			
		}else{
			showmsg('�Ƿ�������','self.parent.tb_remove();','button');
		}
	}
	/**
	 * ȡ������
	 *
	 */
	function c_cancel()
	{
		if (intval($_GET['id'] && $_GET['state'] < 0 && $_POST['notse']))
		{
			if ($this->update('id='.$_GET['id'],array('state'=>$_GET['state'],'notse'=>$_POST['notse'])))
			{
				showmsg('�����ɹ���','self.parent.tb_remove();self.parent.location.reload();','button');
			}
		}else{
			showmsg('����ʧ�ܣ�','self.parent.tb_remove();','button');
		}
	}
	/**
	 * ����״̬
	 *
	 */
	function c_set_state()
	{
		if (intval($_GET['id']) && $_GET['state'] > 1)
		{
			if ($this->model_set_state())
			{
				showmsg('�����ɹ���','self.parent.tb_remove();self.parent.location.reload();','button');
			}else{
				showmsg('����ʧ�ܣ�','self.parent.tb_remove();','button');
			}
		}
	}
	/**
	 * ��ʾ�����¼������ϸ��Ϣ
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
						$val = '�˻�����';
						$notse = '<tr><td><b>�˻�˵����</b></td><td>'.$row['notse'].'</td><tr>';
					}elseif ($val == -2){
						$val = '�޷��ɹ�';
						$notse = '<tr><td><b>�޷��ɹ���</b></td><td>'.$row['notse'].'</td><tr>';
					}elseif ($val == -3){
						$val = 'ȡ���ɹ�';
						$notse = '<tr><td><b>ȡ��˵����</b></td><td>'.$row['notse'].'</td><tr>';
					}elseif ($val==0){
						$val = '�����';
					}elseif ($val==1){
						$val = '���ɹ�';
					}elseif ($val==2){
						$val = '�ɹ���';
					}elseif ($val==3){
						$val = '�ɹ����';
					}
				}
				$this->show->assign('notse_html',$notse);
				$this->show->assign($key,$val);
			}
			$this->show->display('purchase_applyinfo');
		}else{
			showmsg('����ID������');
		}
	}
}
?>