<?php
class controller_device_apply extends model_device_apply
{
	public $show;
	function __construct()
	{
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'device/apply/';
	}
	/**
	 * �ҵ�����
	 */
	function c_myapply_list()
	{
		$this->show->assign ( 'list', $this->model_myapply_list () );
		$this->show->display ( 'myapply-list' );
	
	}
	/**
	 * �鿴��Ϣ
	 */
	function c_applyinfo()
	{
		if (intval ( $_GET['id'] ))
		{
			$data = $this->model_apply_info ( $_GET['id'] );
			foreach ( $data as $key => $val )
			{
				if ($key == 'status')
				{
					$this->show->assign ( $key, ($val == 1 ? '��ͨ�����' : ($val == -1 ? '�����' :($val == -2 ? '��ȡ������' : '<span>�����</span>'))));
				} elseif ($key == 'date')
				{
					$this->show->assign ( $key, date ( 'Y-m-d', $val ) );
				} else
				{
					$this->show->assign ( $key, $val );
				}
			}
			$this->show->assign('reason',($data['status']==-1 ? '<tr><td align="right">�������</td><td align="left">'.$data['reason'].'</td></tr>' : ''));
			$audit_link = thickbox_link ( '��˲���', 'b', 'id=' . $_GET['id'] . '&key=' . $_GET['key'], '��� ' . $data['user_name'] . ' ������', null, 'audit_apply', 300, 250 );
			$edit_iink = thickbox_link ( '�����޸�', 'b', 'id=' . $data['id'] . '&key=' . $data['rand_key'], '�޸�SIM������', null, 'edit', null, 500 );
			$this->show->assign('edit_link',(($_SESSION['USER_ID']==$data['userid'] && $data['status'] < 1) ? $edit_iink : ''));
			$this->show->assign ( 'audit_link', ($_SESSION['USER_ID'] == $data['audit_userid'] && $data['status']==0 ? $audit_link : '') );
			$this->show->display ( 'info' );
		}
	}
	/**
	 * ����б�
	 */
	function c_audit_list()
	{
		$this->show->assign ( 'list', $this->model_audit_list () );
		$this->show->display ( 'audit-list' );
	}
	/**
	 * �������
	 */
	function c_add_apply()
	
	{
		if ($_POST)
		{
			if ($this->model_save_apply ( $_POST ))
			{
				showmsg ( '�ύ�ɹ���', 'self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '�ύʧ�ܣ����ڹ���Ա��ϵ��' );
			}
		} else
		{
			$this->show->display ( 'add' );
		}
	}
	
	function c_edit()
	{
		if (intval ( $_GET['id'] ) && $_GET['key'])
		{
			if ($_POST)
			{
				if ($this->model_save_edit($_GET['id'],$_GET['key'],$_POST))
				{
					showmsg('�޸ĳɹ���','self.parent.location.reload();', 'button');
				}else{
					showmsg('�޸�ʧ�ܣ��������Ա��ϵ��');
				}
			}else{
				$data = $this->model_apply_info($_GET['id']);
				if ($data)
				{
					foreach ($data as $key=>$val)
					{
						$this->show->assign($key,$val);
					}
				}
				$this->show->display('edit');
			}
		}
	}
	
	function c_audit_apply()
	{
		if ($_POST)
		{
			if ($this->model_audit_apply($_GET['id'],$_GET['key'],$_POST))
			{
				showmsg('�����ɹ���','self.parent.location.reload();', 'button');
			}else{
				showmsg('����ʧ�ܣ��������Ա��ϵ��');
			}
		}else{
			$this->show->display('audit');
		}
	}
	
	function c_show_add()
	{
		$info_list = '';
		if ($_POST['id'])
		{
			$stock =new model_device_stock();
			$info_list = $stock->model_device_info($_POST['id']);
			$info_list = str_replace('<td>�������</td>','<td>�������</td><td>��������</td>',$info_list);
		}
		$gl = new includes_class_global();
		$dept_name = $gl->GetUserinfo($_SESSION['USER_ID'],'dept_name');
		global $func_limit;
		 $this->show->assign('display',$func_limit[ '������Ŀ' ]?'none':'');
		$this->show->assign('userid',$_SESSION['USER_ID']);
		$this->show->assign('username',$_SESSION['USERNAME']);
		$this->show->assign('dept_id',$_SESSION['DEPT_ID']);
		$this->show->assign('dept_name',$dept_name);
		$this->show->assign('list',$info_list);
		$this->show->display('show-add');
	}
	
	function c_add()
	{
		if ($_POST)
		{
			if ($this->model_add($_POST))
			{
				setcookie('info','');
				showmsg('�����ɹ���','?model='.$_GET['model'].'&action=apply_list','link');
			}else{
				showmsg('����ʧ�ܣ��������Ա��ϵ��');
			}
		}
	}
	
	function c_apply_list()
	{
		$this->show->assign('list',$this->model_apply_list());
		$this->show->display('apply-list');
	}
	
	function c_edit_order()
	{
		if ($_GET['orderid'] && $_GET['key'] && $_GET['audit'])
		{
			if ($_POST)
			{
				if ($this->model_apply_audit_order($_GET['orderid'] , $_GET['key'],$_POST))
				{
					showmsg('�����ɹ���','self.parent.location.reload();', 'button');
				}else{
					showmsg('����ʧ�ܣ��������Ա��ϵ��');
				}
			}else{
				$rs = $this->model_apply_order_info($_GET['orderid'] , $_GET['key']);
				foreach ($rs as $key=>$val)
				{
					if ($key=='ls')
					{
						$this->show->assign('ls_name',($val==0 ? '����':'��Ŀ'));
						$this->show->assign('dt_none',($val==0 ? '' : 'none'));
						$this->show->assign('xm_none',($val==1 ? '' : 'none'));
					}elseif ($key=='status'){
						if ($val==0)
						{
							$this->show->assign('post_button','<input type="submit" name="input" value=" ͨ������ " /> <input type="button" onclick="show_return();" value=" ������� " />');
						}else{
							if ($_GET['audit']=='accept' && (($rs['accept_num']+$rs['accept_return_num'])< $rs['total']))
							{
								$this->show->assign('post_button','<input type="submit" name="input" value=" ���ѡ�� " />');
								$this->show->assign('return_button','<input type="button" onclick="show_return();" name="input" value=" ���ѡ�� " />');
							}else{
								$this->show->assign('post_button','');
								$this->show->assign('return_button','');
							}
						}
					}elseif ($key=='target_date'){
						$val = date('Y-m-d',$val);
					}
					$this->show->assign($key,$val);
				}
				if ($_GET['audit']=='yes')
				{
					$info_list = $this->model_apply_order_info_list($_GET['orderid']);
					$info_list = str_replace('<td><input type="checkbox"','<td style="display:none"><input type="hidden"',$info_list);
				}elseif($_GET['audit']=='accept'){
					$info_list = $this->model_apply_order_info_list($_GET['orderid'],$_SESSION['DEPT_ID'],true);
					$info_list = str_replace('<td>��������</td>','<td>��������</td><td>Ԥ�ƹ黹����</td><td width="100">��ע</td>',$info_list);
					$info_list = preg_replace('/<td>(\\d)<\/td><td>(\\d)<\/td><td class="amount_(.*?)">(.*?)<\/td>/',
											  '<td id="amount_${3}">${1}</td><td>${2}</td>
											  	<td class="amount"><input type="text" size="5" name="amount[${3}]" onKeyUp="value=this.value.replace(/\\D/g,\'\');checkmax(${3},this)" value="${4}"/></td>
											  	<td><input type="" size="12" readonly name="info_target_date[${3}]" class="Wdate" onClick="WdatePicker({dateFmt:\'yyyy-MM-dd\',minDate:\'%y-%M-%d\'})" value="'.date('Y-m-d',$rs['target_date']).'" /></td>
											  	<td><input type="text" size="12" name="notes[${3}]" value="" /></td>',
												$info_list);
					
				}else{
					$info_list = $this->model_apply_order_info_list($_GET['orderid']);
				}
				$this->show->assign('list',$info_list);
				if ($_GET['audit']=='yes')
				{
					$this->show->display('audit-order');
				}else{
					$this->show->display('accept-order');
				}
			}
		}elseif ($_GET['orderid'] && $_GET['key'])
		{
			global $func_limit;
			if ($_POST)
			{
				if ($this->model_edit_order($_GET['orderid'] , $_GET['key'],$_POST))
				{
					showmsg('�����ɹ���','self.parent.location.reload();', 'button');
				}else{
					showmsg('����ʧ�ܣ�');
				}
			}else{
				$rs = $this->model_apply_order_info($_GET['orderid'] , $_GET['key']);
				foreach ($rs as $key=>$val)
				{
					if ($key=='ls')
					{
						$this->show->assign('ls_checked_0',($val==0 ? 'checked' : ''));
						$this->show->assign('ls_checked_1',($val==1 ? 'checked' : ''));
						$this->show->assign('dt_none',($val==0 ? '' : 'none'));
						$this->show->assign('xm_none',($val==1 ? '' : 'none'));
					}elseif ($key=='status'){
						if ($val==-1 || $val == 0)
						{
							$this->show->assign('post_button','<input type="submit" value=" �ύ�޸� " />');
						}elseif($val == -2){
							$this->show->assign('post_button','<input type="button" onclick="recovery_apply('.$_GET['orderid'].',\''.$_GET['key'].'\')" value=" �ָ����� " />');
						}else{
							$this->show->assign('post_button','');
						}
						
						if ($val == 0)
						{
							$this->show->assign('exit_button','<input type="button" onclick="exit_apply('.$_GET['orderid'].',\''.$_GET['key'].'\');" value=" ȡ������ " />');
						}else{
							$this->show->assign('exit_button','');
						}
					}elseif ($key=='target_date'){
						$val = date('Y-m-d',$val);
					}
					$this->show->assign($key,$val);
				}
				$info_list = $this->model_apply_order_info_list($_GET['orderid']);
				if ($rs['status']==1)
				{
					$info_list = str_replace('<td><input type="checkbox"','<td style="display:none"><input type="hidden"',$info_list);
				}
				$this->show->assign('list',$info_list);
				
		        $this->show->assign('display',$func_limit[ '������Ŀ' ]?'none':'block');
				$this->show->display('edit-order');
			}
		}else{
			showmsg('�Ƿ�������');
		}
	}
	/**
	 * �����б�
	 */
	function c_apply_audit_list()
	{
		$this->show->assign('list',$this->model_apply_audit_list());
		$this->show->display('apply-audit-list');
	}
	/**
	 * �����б�
	 */
	function c_apply_accept_list()
	{
		$this->show->assign('list',$this->model_apply_accept_list());
		$this->show->display('apply-list');
	}
	/**
	 * ����ʱ���
	 */
	function c_accept_return()
	{
		if ($_POST['orderid'] && $_POST['id'])
		{
			$info_id_data = explode(',',trim($_POST['id'],','));
			if ($this->accept_return($_POST['orderid'],$info_id_data))
			{
				echo 1;
			}else{
				echo -1;
			}
		}else{
			
		}
		
	}
	
}

?>