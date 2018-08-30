<?php
class controller_product_demand extends model_product_demand
{
	public $show;
	function __construct()
	{
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'product/';
	}
	/**
	 * �б�
	 */
	function c_showlist()
	{
		$typelist = $this->get_typelist ();
		if (is_array($typelist))
		{
			foreach ( $typelist as $row )
			{
				if ($row['status']==1)
				{
					$str .= '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
				}
			}
		}
		$this->show->assign ( 'typelist', $str );
		$this->show->assign ( 'username', $_GET['username'] );
		$this->show->assign ( 'id', $_GET['id']?$_GET['id']:'' );
		$this->show->assign ( 'start_date', $_GET['start_date']?$_GET['start_date']:'' );
		$this->show->assign ( 'end_date', $_GET['end_date']?$_GET['end_date']:'' );
		$this->show->assign ( 'list', $this->model_showlist () );
		if ($this->num > pagenum)
		{
			$showpage = new includes_class_page ();
				$showpage->show_page ( array(
					
							'total'=>$this->num,
							'perpage'=>pagenum
				) );
			$showpage->_set_url ( 'num=' . $this->num . '&typeid=' . $_GET['typeid'] . '&status=' . $_GET['status'] . '&username=' . $_GET['username'] );
			$pagehtml = '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
		}
		$this->show->assign('pagehtml',$pagehtml);
		$this->show->display ( 'list' );
	}
	
	function c_count_list()
	{
		$typelist = $this->get_typelist ();
		foreach ( $typelist as $row )
		{
			if ($row['status']==1)
			{
				$str .= '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
			}
		}
		$this->show->assign ( 'typelist', $str );
		$this->show->assign ( 'list', $this->model_count_list () );
		$this->show->assign ( 'username', $_GET['username'] );
			$this->show->assign ( 'start_date', $_GET['start_date']?$_GET['start_date']:'' );
		$this->show->assign ( 'end_date', $_GET['end_date']?$_GET['end_date']:'' );
	
		$this->show->display ( 'count-list' );
	}
	/**
	 * �鿴��ϸ
	 */
	function c_full_info()
	{
		$data = $this->get_info ( intval ( $_GET['id'] ), $_GET['key'] );
		$typelist = $this->get_typelist ();
		$manager = '';
		$assistant = '';
		if ($typelist)
		{
			foreach ( $typelist as $k => $row )
			{
				if ($row['id'] == $data['product_id'])
				{
					$this->show->assign ( 'typename', $row['product_name'] );
					$manager = $row['manager'];
					$assistant = $row['assistant'];
					break;
				}
			}
		}
		foreach ( $data as $key => $val )
		{
			$this->show->assign ( $key, $val );
		}
		switch ($data['status'])
		{
			case - 1 :
				$status = '<span class="purple">�����</span>';
				$notse = '<tr><td align="right"><strong>��������ɣ�</strong></td><td align="left">' . $data['notse'] . '</td></tr>';
				break;
			case 1 :
				$status = '<span class="green">���ͨ��</span>';
				$notse = '<tr><td align="right"><strong>��ע˵����</strong></td><td align="left">' . $data['notse'] . '</td></tr>';
				break;
			case 2 :
				$status = '��ʵ��';
				break;
			default :
				$status = '<span>�����</span>';
		}
		$this->show->assign ( 'status', $status );
		$this->show->assign ( 'notse_contnet', $notse );
		$audit_link = thickbox_link ( '��˲���', 'b', 'id=' . $data['id'] . '&key=' . $data['rand_key'], '������Ϊ ' . $data['id'] . ' ������', null, 'audit', 500, 400 );
		$edit_audit_link = thickbox_link('�޸����','b','id='.$data['id'].'&key='.$data['rand_key'],'�޸�������Ϊ��'.$data['id'].' �Ĳ�Ʒ����',null,'audit',500,400);
		$realize_link = thickbox_link ( '����Ϊ��ʵ��', 'b', 'id=' . $data['id'] . '&key=' . $data['rand_key'], '�������Ϊ ' . $data['id'] . ' ��������ʵ��', null, 'realize', 500, 400 );
		
		if ( $_SESSION['USER_ID'] == $manager || in_array ( $_SESSION['USER_ID'], explode ( ',', $assistant ) ))
		{
			if ($data['status'] == 0)
			{
				$this->show->assign ( 'audit_link',$audit_link);
			}elseif ($data['status'] !=0 && $data['status'] !=2){
				$this->show->assign ( 'audit_link',$edit_audit_link);
			}else{
				$this->show->assign ( 'audit_link','');
			}
		}else{
			$this->show->assign ( 'audit_link','');
		}
		$this->show->assign ( 'realize_link', ((($_SESSION['USER_ID'] == $manager || in_array ( $_SESSION['USER_ID'], explode ( ',', $assistant ) )) && $data['status'] == 1)) ? $realize_link : ($data['realize_date'] ? '<span>' . $data['realize_date'] . ' </span>' . $data['version'] : '') );
		$this->show->assign ( 'degree', ($data['degree'] == 2 ?  '����':'��ͨ') );
		$this->show->display ( 'info' );
	}
	/**
	 * ����
	 */
	function c_add()
	{
		if ($_POST)
		{
			$data = $_POST;
			$data['userid'] = $_SESSION['USER_ID'];
			$data['date'] = time ();
			if ($this->model_save_add ( $data ))
			{
				showmsg ( '�ύ�ɹ���', 'self.parent.location.reload();', 'button' );
			}
		} else
		{
			$data = $this->get_typelist ();
			foreach ( $data as $row )
			{
				$str .= '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
			}
			$this->show->assign ( 'typelist', $str );
			$this->show->display ( 'add' );
		}
	}
	/**
	 * �޸�
	 */
	function c_edit()
	{
		if ($_POST)
		{
			if (intval ( $_GET['id'] ) && $_GET['key'])
			{
				if ($this->model_save_edit ( $_POST, intval ( $_GET['id'] ), $_GET['key'] ))
				{
					showmsg ( '�޸ĳɹ���', 'self.parent.location.reload();', 'button' );
				} else
				{
					showmsg ( '�޸�ʧ�ܣ��������Ա��ϵ��', 'self.parent.location.reload();', 'button' );
				}
			} else
			{
				showmsg ( '�Ƿ����ʣ�' );
			}
		} else
		{
			$data = $this->get_info ( intval ( $_GET['id'] ), $_GET['key'] );
			$typelist = $this->get_typelist ();
			foreach ( $typelist as $row )
			{
				if ($row['status']==1)
				{
					if ($data['product_id'] == $row['id'])
					{
						$str .= '<option selected value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
					} else
					{
						$str .= '<option value="' . $row['id'] . '">' . $row['product_name'] . '</option>';
					}
				}
			}
			$this->show->assign ( 'typelist', $str );
			foreach ( $data as $key => $val )
			{
				if ($key=='description')
				{
					$this->show->assign('description',new_htmlspecialchars($val));
				}elseif ($key=='upfile'){
					if ($val)
					{
						$this->show->assign($key,'<span id="file_link">������<a target="_blank" href="upfile/product/demand/'.$data['FilePathName'].'/'.$val.'">'.$val.'</a> <a href="javascript:delfile();">ɾ��</a></span>');
					}else{
						$this->show->assign($key,'');
					}
				}else{
					$this->show->assign ( $key, $val );
				}
			}
			$this->show->assign ( 'selected_1', ($data['degree'] == 1 ? 'selected' : '') );
			$this->show->assign ( 'selected_2', ($data['degree'] == 2 ? 'selected' : '') );
			$this->show->display ( 'edit' );
		}
	}
	/**
	 * ɾ��
	 */
	function c_del()
	{
		if ($_GET['type'])
		{
			if ($this->model_delete($_GET['id'],$_GET['key']))
			{
				showmsg('ɾ���ɹ���','self.parent.location.reload();','button');
			}else{
				showmsg('ɾ��ʧ�ܣ�');
			}
		} else
		{
			showmsg ( '��ȷ��Ҫɾ����������', "location.href='?model=product_demand&action=del&id=".$_GET['id']."&key=".$_GET['key']."&type=ok'",'button' );
		}
	}
	/**
	 * ���
	 */
	function c_audit()
	{
		if ($_POST)
		{
			if (intval ( $_GET['id'] ) && $_GET['key'])
			{
				if ($this->model_audit ( $_POST['status'], intval ( $_GET['id'] ), $_GET['key'], $_POST['notse'] ))
				{
					showmsg ( '�����ɹ���', 'self.parent.location.reload();', 'button' );
				} else
				{
					showmsg ( '����ʧ�ܣ�����OA����Ա��ϵ��' );
				}
			}
		} else
		{
			$this->show->display ( 'audit' );
		}
	}
	
	/**
	 * ����Ϊʵ��
	 */
	function c_realize()
	{
		if ($_POST)
		{
			if ($this->model_realize ( intval ( $_GET['id'] ), $_GET['key'], $_POST['realize_date'], $_POST['version'] ))
			{
				showmsg ( '���óɹ���', 'self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '����ʧ�ܣ�����OA����Ա��ϵ��' );
			}
		} else
		{
			$this->show->display ( 'realize' );
		}
	}
	function c_delete()
	{
	
	}
	/**
	 * ����EXCEL��
	 */
	function c_export()
	{
		if ($_GET['type'] == 'list')
		{
			$this->model_list_export ();
		} else if ($_GET['type'] == 'count')
		{
			$this->model_count_export ();
		}
	}
	/**
	 * ���÷���
	 */
	function c_upfd()
	{
		if ($_POST)
		{
			if (intval ( $_GET['id'] ) && $_GET['key'])
			{
				if ($this->model_upfd ( $_GET['id'], $_GET['key'], $_POST['feedback'] ))
				{
					showmsg ( '���óɹ���', 'self.parent.location.reload();', 'button' );
				} else
				{
					showmsg ( '����ʧ�ܣ��������Ա��ϵ��', 'self.parent.location.reload();', 'button' );
				}
			} else
			{
				showmsg ( '�Ƿ�����~��' );
			}
		} else
		{
			$this->show->assign ( 'id', $_GET['id'] );
			$this->show->assign ( 'key', $_GET['key'] );
			$this->show->assign ( 'checked_1', ($_GET['feedback'] == 1 ? 'checked' : '') );
			$this->show->assign ( 'checked_2', ($_GET['feedback'] == 0 ? 'checked' : '') );
			$this->show->display ( 'upfd' );
		}
	}
	//==============================================
	/**
	 * �����б�
	 */
	function c_typelist()
	{
		$this->show->assign ( 'list', $this->model_type_list ($auth) );
		$this->show->display ( 'typelist' );
	}
	/**
	 * �������
	 */
	function c_add_type()
	{
		$type_obj = new model_product_management_type();
		$type_data = $type_obj->GetDataList();
		$type_option='';
		if ($type_data)
		{
			foreach ($type_data as $rs)
			{
				$type_option .='<option value="'.$rs['id'].'">'.$rs['typename'].'</option>';
			}
		}
		$this->show->assign('type_option',$type_option);
		$this->show->display ( 'add-type' );
	}
	/**
	 * �޸�����
	 */
	function c_edit_type()
	{
		if (intval ( $_GET['id'] ) && $_GET['key'])
		{
			$data = $this->get_typeinfo ( intval ( $_GET['id'] ), $_GET['key'] );
			$i = 0;
			foreach ( $data as $key => $val )
			{
				if ($key == 'assistant')
				{
					$assistant= $this->get_username ( $val );
				}else if ($key == 'project_id'){
					$project_inputs = '';
					if ($val)
					{
						$arr = explode(',',$val);
						foreach ($arr as $v)
						{
							$project_inputs .='<input id="p_'.$v.'" type="hidden" name="project_id[]" value="'.$v.'" />';
						}
					}
					$this->show->assign('project_inputs',$project_inputs);
				}
				$this->show->assign ( $key, $val );
			}
			$arr = $this->model_project($_GET['id']);
			if ($arr)
			{
				foreach ($arr as $key=>$row)
				{
					if ($row['id'])
					{
						$str .='<div id="project_'.$row['id'].'">'.$row['name'].'<a href="javascript:del_product('.$row['id'].')">ɾ��</a></div>';
					}
				}
			}
			$type_obj = new model_product_management_type();
			$type_data = $type_obj->GetDataList();
			$type_option='';
			if ($type_data)
			{
				foreach ($type_data as $rs)
				{
					$type_option .='<option '.($rs['id'] == $data['typeid'] ? 'selected' : '').' value="'.$rs['id'].'">'.$rs['typename'].'</option>';
				}
			}
			$this->show->assign ( 'assistant', ($assistant ? implode(', ', $assistant).', ' : ''));
			$this->show->assign('type_option',$type_option);
			$this->show->assign('project_list',$str);
			$this->show->display ( 'edit-type' );
		} else
		{
			showmsg ( '�Ƿ����ʣ�' );
		}
	}
	/**
	 * �����޸Ļ����
	 */
	function c_save_type()
	{
		if ($_GET['type'])
		{
			$data = $_POST;
			if ($this->model_save_type ( $data ))
			{
				if ($_GET['type']=='add')
				{
					$group = new model_system_usergroup_list();
					$group_id = $group->GetId('product_audit'); // ��ȡ��Ʒ������Ա
					$address = $group->GetGroupUserEmail($group_id);
					if ($address)
					{
						$email = new includes_class_sendmail();
						$email->send($_SESSION['USERNAME'].' �ύ�˲�Ʒ�������,��������¼OA����', $_SESSION['USERNAME'].' �ύ�˲�Ʒ������룬��Ʒ���ƣ�'.$_POST['product_name'].',�������¼OA�鿴��'.oaurlinfo, $address);
					}
				}
				showmsg ( '�����ɹ���', 'self.parent.location.reload();', 'button' );
			} else
			{
				showmsg ( '����ʧ��,�������Ա��ϵ��' );
			}
		} else
		{
			$data = $_POST;
		
		}
	}
	/**
	 * ɾ����Ʒ
	 */
	function c_delete_type()
	{
		if (intval ( $_GET['id'] ) && $_GET['key'] && $_GET['confirm'] == 'yes')
		{
			if ($this->model_delete_type ( intval ( $_GET['id'] ), $_GET['key'] ))
			{
				showmsg ( 'ɾ���ɹ�', 'self.parent.location.reload();', 'button' );
			}
		} else
		{
			showmsg ( '��ȷ��Ҫɾ����������', "location.href='?model=product_demand&action=delete_type&confirm=yes&id=" . $_GET['id'] . "&key=" . $_GET['key'] . "'", 'button' );
		}
	}
	/**
	 * ������Ʒ
	 */
	function c_audit_type()
	{
		if ($_POST)
		{
			if ($this->Edit(array('status'=>$_POST['status'],'remark'=>$_POST['remark']), $_GET['id']))
			{
				$info = $this->GetOneInfo("id=".$_GET['id']);
				//ͬ��PMS
				$product = array();
				$product['company'] = 1;
				$product['tid'] = $info['id'];
				$product['name'] = $info['product_name'];
				$product['code'] = $info['id'];
				$product['PO'] = $info['manager'];
				$product['desc'] = $info['description'];
				$product['status'] = 'normal';
				$pms = new api_pms();
				$pms->GetModule('product', 'add',un_iconv($product),'post');
				unset($pms,$product);
				//�����ʼ�
				$group = new model_system_usergroup_list();
				$group_id = $group->GetId('product_manager'); // ��ȡ��Ʒ������Ա
				$address = $group->GetGroupUserEmail($group_id);
				if ($address)
				{
					$email = new includes_class_sendmail();
					$email->send($info['product_name'].' ��Ʒ��������������', $info['product_name'].' ��Ʒ�������'.($_POST['status']==1 ? '����ͨ��' : '�����').',�������¼OA�鿴��'.oaurlinfo, $address);
				}
				showmsg ( '�����ɹ�', 'self.parent.location.reload();', 'button' );
			}
		}else{
			$this->show->display('audit-type');
		}
	}
	
	function c_show_remark()
	{
		$info = $this->GetOneInfo("id=".$_GET['id']);
		
		echo '<div style="font-size:12px;">'.$info['remark'].'</div>';
	}
}
?>