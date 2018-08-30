<?php
class controller_device_purchase extends model_device_purchase
{
	public $show;
	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'purchase';
		$this->pk = 'id';
		$this->show = new show ();
		$this->show->path = 'purchase/';
	}
	
	function c_index()
	{
	
	}
	
	function c_typelist()
	{
		$this->show->assign ( 'list', $this->model_typelist () );
		$this->show->display ( 'typelist' );
	
	}
	/**
	 * ��ʾ������ϸ
	 */
	function c_show_info()
	{
		global $func_limit;
		if (intval ( $_GET['id'] && $_GET['key'] ))
		{
			$data = $this->model_get_order_info ( $_GET['id'] );
			if ($data)
			{
				foreach ( $data as $key => $row )
				{
					if ($key == 0)
					{
						foreach ( $row as $k => $val )
						{
							if ($k == 'fixed')
							{
								$this->show->assign ( 'fixed', ($val == 0 ? '�̶��ʲ�' : '����') );
							
							} elseif ($k == 'laplop')
							{
								$this->show->assign ( 'laplop', ($val == 1 ? '��' : '��') );
							} else
							{
								$this->show->assign ( $k, $val );
							}
						}
					}
					$str .= '
							<tr>
                                <td>' . $row['device_name'] . '</td>
                                <td>' . $row['norm'] . '</td>
                                <td>' . $row['amount'] . ' </td>
                                <td>' . $row['unit'] . '</td>
                                <td>' . $row['delivery_date'] . '</td>
                                <td>' . $row['notse'] . ' </td>
                            </tr>
						';
				}
				$st = $data[0]['status'];
				$edit_link = ((($st == 0 || $st == - 1 || $st == - 2) && $data[0]['userid'] == $_SESSION['USER_ID']) ? '<input type="button" onclick="location.href=\'?model=' . $_GET['model'] . '&action=edit&id=' . $_GET['id'] . '&key=' . $_GET['key'] . '\'" value=" �޸� " />' : '');
				$audit_link = $func_limit['���Ȩ��'] ? thickbox_link('�������','b','id='.$_GET['id'].'&key='.$_GET['key'],'��˲ɹ�����',null,'audit',300,200) : '';
				$this->show->assign ( 'edit_link', $edit_link );
				$this->show->assign('audit_link',$audit_link);
				$this->show->assign ( 'list', $str );
				$this->show->display ( 'applyinfo' );
			}
		}
	}
	function c_add_type()
	{
		if ($_POST)
		{
			$this->tbl_name = 'device_purchase_type';
			if ($this->create ( $_POST ))
			{
				showmsg ( '��ӳɹ���', 'self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '���ʧ�ܣ��������Ա��ϵ��' );
			}
		} else
		{
			$data = $this->model_get_type_list ( '0' );
			if ($data)
			{
				foreach ( $data as $key => $row )
				{
					$select_type .= '<option value="' . $row['id'] . '">' . $row['typename'] . '</option>';
				}
			}
			$this->show->assign ( 'dept_id', $_SESSION['DEPT_ID'] );
			$this->show->assign ( 'select_project', $select_type );
			$this->show->display ( 'add-type' );
		}
	
	}
	/**
	 * ��ʾ����ɹ�ҳ
	 *
	 */
	function c_apply()
	{
		
		if ($_POST)
		{
			if ($this->model_insert_apply ( $_POST ))
			{
				showmsg ( '�ύ�ɹ���', 'self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '�ύʧ�ܣ��������Ա��ϵ��' );
			}
		} else
		{
			$data = $this->model_get_type_list ( '0', 'asc' );
			if ($data)
			{
				foreach ( $data as $row )
				{
					$select_project .= '<option value="' . $row['id'] . '">' . $row['typename'] . '</option>';
				}
			}
			$this->show->assign ( 'USER_NAME', $_SESSION['USERNAME'] );
			$this->show->assign ( 'userid', $_SESSION['USER_ID'] );
			$this->show->assign ( 'deptid', $_SESSION['DEPT_ID'] );
			$this->show->assign ( 'DEPT_NAME', $this->get_table_fields ( 'department', "DEPT_ID=" . $_SESSION['DEPT_ID'] . "", 'DEPT_NAME' ) );
			$this->show->assign ( 'phone', $this->get_table_fields ( 'ecard', "user_id='" . $_SESSION['USER_ID'] . "'", 'mobile1' ) );
			$this->show->assign ( 'select_project', $select_project );
			$this->show->display ( 'apply' );
		}
	}
	/**
	 * �޸Ĳɹ�����
	 *
	 */
	function c_edit()
	{
		if ($_POST)
		{
			if ($this->model_save_edit ( $_GET['id'], $_GET['key'], $_POST ))
			{
				showmsg ( '�޸ĳɹ���', 'self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '�޸�ʧ��,�������Ա��ϵ��' );
			}
		} else
		{
			if (intval ( $_GET['id'] ) && $_GET['key'])
			{
				$data = $this->model_get_order_info ( $_GET['id'] );
				if ($data)
				{
					$num = 0;
					foreach ( $data as $key => $row )
					{
						$num ++;
						if ($key == 0)
						{
							foreach ( $row as $k => $val )
							{
								if ($k == 'fixed')
								{
									$this->show->assign ( 'fixed_0', ($val == 0 ? 'checked' : '') );
									$this->show->assign ( 'fixed_1', ($val == 1 ? 'checked' : '') );
								}
								if ($k == 'laplop')
								{
									$this->show->assign ( 'laplop_1', ($val == 1 ? 'checked' : '') );
									$this->show->assign ( 'laplop_0', ($val == 0 ? 'checked' : '') );
								}
								$this->show->assign ( $k, $val );
							}
						}
						$str .= '
							<tr id="tr_' . $num . '">
                                <td><input type="text" class="device_name" name="device_name[' . $row['id'] . ']" value="' . $row['device_name'] . '" /></td>
                                <td><input type="text" name="norm[' . $row['id'] . ']" value="' . $row['norm'] . '" /></td>
                                <td><input size="5" type="text" class="amount" name="amount[' . $row['id'] . ']" onKeyUp="value=value.replace(/[^\d]/g,\'\')" value="' . $row['amount'] . '"/></td>
                                <td><input size="5" type="text" name="unit[' . $row['id'] . ']" value="' . $row['unit'] . '" /></td>
                                <td><input size="15" type="text" name="delivery_date[' . $row['id'] . ']" readonly class="Wdate" onClick="WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:\'%y-%M-%d\'})" value="' .
									 $row['delivery_date'] . '" /></td>
                                <td><input type="text" name="notse[' . $row['id'] . ']" value="' . $row['notse'] . '" /></td>
                                <td id="edit_0"><input type="button" onclick="del_tr(' . $num . ');" value="ɾ��" /></td>
                            </tr>
						';
					}
					$type = $this->model_get_type_list ( '0', 'asc' );
					if ($type)
					{
						foreach ( $type as $row )
						{
							if ($data[0]['tid'] == $row['id'])
							{
								$select_project .= '<option selected value="' . $row['id'] . '">' . $row['typename'] . '</option>';
							} else
							{
								$select_project .= '<option value="' . $row['id'] . '">' . $row['typename'] . '</option>';
							}
						}
					}
					$this->show->assign ( 'tr_num', $num + 1 );
					$this->show->assign ( 'select_project', $select_project );
					$this->show->assign ( 'list', $str );
				}
				$this->show->display ( 'editapply' );
			}
		}
	}
	/**
	 * ��������
	 *
	 */
	function c_update_apply()
	{
		if (intval ( $_GET['id'] ))
		{
			if ($this->model_update_applay ())
			{
				showmsg ( '�����ɹ���', 'self.parent.tb_remove();self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '����������', 'self.parent.tb_remove();', 'button' );
			}
		} else
		{
			showmsg ( '�Ƿ�������', 'self.parent.tb_remove();', 'button' );
		}
	}
	/**
	 * �ύ����
	 *
	 */
	function c_add_apply()
	{
		if ($this->model_insert_apply ())
		{
			showmsg ( '�ύ�ɹ���' );
		} else
		{
			showmsg ( '�ύʧ�ܣ�' );
		}
	}
	/**
	 * ��ʾ�����¼�б�
	 *
	 */
	function c_applylist()
	{
		$this->show->assign ( 'list', $this->model_applylist () );
		$this->show->display ( 'applylist' );
	}
	/**
	 * ������¼
	 *
	 */
	function c_auditlist()
	{
		$this->show->assign ( 'list', $this->model_applylist ( 'audit' ) );
		$this->show->display ( 'auditlist' );
	}
	/**
	 * �ȴ��ɹ���Ϣ
	 *
	 */
	function c_wait_buy()
	{
		$this->show->assign ( 'list', $this->model_applylist ( 'wait' ) );
		$this->show->display ( 'waitbuylist' );
	}
	/**
	 * ȷ�����
	 *
	 */
	function c_audit_confirm()
	{
		$this->show->assign ( 'id', $_GET['id'] );
		$this->show->assign ( 'state', $_GET['state'] );
		if ($_GET['state'] == 1)
		{
			$this->show->assign ( 'msg', '��ȷ��Ҫͨ������������' );
			$this->show->assign ( 'action', 'approval' );
			$this->show->display ( 'audit' );
		} elseif ($_GET['state'] == 2)
		{
			$this->show->assign ( 'msg', '��ȷ����ʼ�ɹ����豸��' );
			$this->show->assign ( 'action', 'set_state' );
			$this->show->display ( 'audit' );
		} elseif ($_GET['state'] == 3)
		{
			$this->show->assign ( 'msg', '��ȷ�����豸�ɹ��������' );
			$this->show->assign ( 'action', 'set_state' );
			$this->show->display ( 'audit' );
		} elseif ($_GET['state'] == - 1)
		{
			$this->show->assign ( 'id', $_GET['id'] );
			$this->show->assign ( 'state', $_GET['state'] );
			$this->show->assign ( 'title', '�˻�����' );
			$this->show->display ( 'cancel' );
		} elseif ($_GET['state'] == - 2)
		{
			$this->show->assign ( 'id', $_GET['id'] );
			$this->show->assign ( 'state', $_GET['state'] );
			$this->show->assign ( 'title', '�޷��ɹ�' );
			$this->show->display ( 'cancel' );
		}
	}
	/**
	 * ����
	 *
	 */
	function c_approval()
	{
		if (intval ( $_GET['id'] ) && $_GET['state'] <= 1)
		{
			if ($this->model_set_state ())
			{
				showmsg ( '�����ɹ���', 'self.parent.tb_remove();self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '����ʧ�ܣ�', 'self.parent.tb_remove();', 'button' );
			}
		} else
		{
			showmsg ( '�Ƿ�������', 'self.parent.tb_remove();', 'button' );
		}
	}
	function c_cancel_confirm()
	{
		if (intval ( $_GET['id'] ) && $_GET['state'] == - 3)
		{
			$this->show->assign ( 'id', $_GET['id'] );
			$this->show->assign ( 'state', $_GET['state'] );
			$this->show->assign ( 'title', 'ȡ���ɹ�' );
			$this->show->display ( 'cancel' );
		
		} else
		{
			showmsg ( '�Ƿ�������', 'self.parent.tb_remove();', 'button' );
		}
	}
	/**
	 * ȡ������
	 *
	 */
	function c_cancel()
	{
		if (intval ( $_GET['id'] && $_GET['state'] < 0 && $_POST['notse'] ))
		{
			if ($this->update ( 'id=' . $_GET['id'], array(
				
						'state'=>$_GET['state'],
						'notse'=>$_POST['notse']
			) ))
			{
				showmsg ( '�����ɹ���', 'self.parent.tb_remove();self.parent.location.reload();', 'button' );
			}
		} else
		{
			showmsg ( '����ʧ�ܣ�', 'self.parent.tb_remove();', 'button' );
		}
	}
	/**
	 * ����״̬
	 *
	 */
	function c_set_state()
	{
		if (intval ( $_GET['id'] ) && $_GET['state'] > 1)
		{
			if ($this->model_set_state ())
			{
				showmsg ( '�����ɹ���', 'self.parent.tb_remove();self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '����ʧ�ܣ�', 'self.parent.tb_remove();', 'button' );
			}
		}
	}
	/**
	 * ��ʾ�����¼������ϸ��Ϣ
	 *
	 */
	function c_applyinfo()
	{
		$row = $this->model_applyinfo ();
		if ($row)
		{
			foreach ( $row as $key => $val )
			{
				if ($key == 'date')
					$val = date ( 'Y-m-d H:i:s' );
				if ($key == 'use_date')
					$val = date ( 'Y-m-d' );
				if ($key == 'state')
				{
					if ($val == - 1)
					{
						$val = '�˻�����';
						$notse = '<tr><td><b>�˻�˵����</b></td><td>' . $row['notse'] . '</td><tr>';
					} elseif ($val == - 2)
					{
						$val = '�޷��ɹ�';
						$notse = '<tr><td><b>�޷��ɹ���</b></td><td>' . $row['notse'] . '</td><tr>';
					} elseif ($val == - 3)
					{
						$val = 'ȡ���ɹ�';
						$notse = '<tr><td><b>ȡ��˵����</b></td><td>' . $row['notse'] . '</td><tr>';
					} elseif ($val == 0)
					{
						$val = '�����';
					} elseif ($val == 1)
					{
						$val = '���ɹ�';
					} elseif ($val == 2)
					{
						$val = '�ɹ���';
					} elseif ($val == 3)
					{
						$val = '�ɹ����';
					}
				}
				$this->show->assign ( 'notse_html', $notse );
				$this->show->assign ( $key, $val );
			}
			$this->show->display ( 'applyinfo' );
		} else
		{
			showmsg ( '����ID������' );
		}
	}
}
?>