<?php
class controller_purview extends model_purview
{
	public $show;
	public $menu;
	function __construct()
	{
		@extract($_POST);
		@extract($_GET);
		parent::__construct();
		$this->show = new show();
		$this->menu = new includes_class_global();
	}
	function c_index()
	{
		if ($_GET['areapv']=='0')
		{
			$selected_0 = 'selected';
		}elseif ($_GET['areapv']==1)
		{
			$selected_1 = 'selected';
		}else{
			$selected_all = 'selected';
		}
		$modelName = $this->clean($_GET['modelName']); //����ģ��Ĺؼ���
		$this->show->assign('selected_all',$selected_all);
		$this->show->assign('selected_1',$selected_1);
		$this->show->assign('selected_0',$selected_0);
		$this->show->assign('modelName',$modelName);
		$this->show->assign('select_models',$this->model_select_models());
		$this->show->assign('list',$this->showlist());
		$this->show->assign('menu_select',$this->menu->menu_select($_GET['menuid']));
		$this->show->display('purview_list');
		unset($this->menu,$this->show);
	}

	function c_save()
	{
		if ($this->model_update())
		{
			showmsg('�޸ĳɹ���','self.parent.tb_remove();self.parent.location.reload();','button');
		}else{
			showmsg('�޸�ʧ�ܣ�','self.parent.tb_remove();self.parent.location.reload();','button');
		}
	}
	function c_new_add()
	{
		if ($this->insert())
		{
			showmsg('��ӳɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('���ʧ�ܣ�','self.parent.tb_remove();','button');
		}
	}
	function c_showdelinfo()
	{
		showmsg('��ȷ��Ҫɾ��������Ϣ��',"location.href='?model=purview&action=del&id=".$_GET['id']."'",'button');
	}
	function c_del()
	{
		if ($this->delete())
		{
			showmsg('ɾ���ɹ���','self.parent.tb_remove();self.parent.location.reload();','button');
		}else{
			showmsg('ɾ��ʧ�ܣ�','self.parent.tb_remove();self.parent.location.reload();','button');
		}
	}
	function c_edit()
	{
		$rs = $this->getpurview($_GET['id']);
		if ($rs['control']==1)
		{
			$display ='block';
			$checked_1 = 'checked';
		}else{
			$checked_0 = 'checked';
			$display = 'none';
		}
		$this->show->assign('type_1',($rs['type']==1 ? 'checked':''));
		$this->show->assign('type_0',($rs['type']==0 ? 'checked':''));
		$this->show->assign('checked_1',$checked_1);
		$this->show->assign('checked_0',$checked_0);
		$this->show->assign('display',$display);
		$this->show->assign('control_list',$this->model_pv_control($_GET['id']));
		unset($area);
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('type',$rs['type']);
		$this->show->assign('name',$rs['name']);
		$this->show->assign('models',$rs['models']);
		$this->show->assign('func',$rs['func']);
		$this->show->assign('menu_select',$this->menu->menu_select($rs['menuid']));
		$this->show->display('purview_edit');
		unset($this->menu,$this->show);
	}
	/**
	 * ���ģ�����
	 *
	 */
	function c_show_add_model()
	{
		$this->show->assign('menu_select',$this->menu->menu_select());
		$this->show->display('purview_add-model');
	}
	//���Ȩ�޽���
	function c_show_add()
	{
		$area = new includes_class_global();
		$this->show->assign('menu_select',$this->menu->menu_select());
		$this->show->assign('area',$area->area_checkbox());
		unset($area);
		$this->show->display('purview_add');
	}
	function c_control_list()
	{
		if ($_GET['typeid']==1)
		{
			$selected_1 = 'selected';
		}elseif ($_GET['typeid']=='all'){
			$selected_all = 'selected';
		}elseif ($_GET['typeid']==0){
			$selected_0 = 'selected';
		}
		$this->show->assign('selected_all',$selected_all);
		$this->show->assign('selected_1',$selected_1);
		$this->show->assign('selected_0',$selected_0);
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('control_select',$this->model_control_select($_GET['id']));
		$this->show->assign('list',$this->model_control_list());
		$this->show->display('purview_control-list');
	}
	function c_show_add_userpv()
	{
		$rs = $this->getpurview($_GET['id']);
		if ($rs['areaid'])
		{
			$query = $this->db->query("select * from area where id in(".$rs['areaid'].")");
		}else{
			$query = $this->db->query("select * from area");
		}

		while ($row = $this->db->fetch_array($query))
		{
			$str .='<input type="checkbox" name="area[]" value="'.$row['ID'].'" />'.$row['Name'].' ';
		}
		$this->show->assign('area',$str);
		$this->show->assign('id',$_GET['id']);
		$this->show->display('purview_adduserpv');
	}
	function c_show_edit_userpv()
	{
		$rs = $this->db->get_one("select a.*,b.areaid from user_area_pv as a left join purview as b on b.id=a.pvid where a.id=".$_GET['id']);
		if ($rs)
		{
			$gl = new includes_class_global();
			$u_pv = explode(',',$rs['area']);
			$arr = $gl->area_call($rs['areaid']);
			if ($arr)
			{
				foreach ($arr as $v)
				{
					if (in_array($v['ID'],$u_pv))
					{
						$str .='<input type="checkbox" checked name="area[]" value="'.$v['ID'].'" />'.$v['Name'];
					}else{
						$str .='<input type="checkbox" name="area[]" value="'.$v['ID'].'" />'.$v['Name'];
					}
				}
			}

			$this->show->assign('area',$str);
			$this->show->assign('id',$_GET['id']);
			$this->show->display('purview_edituserpv');
		}else{
			showmsg('�Ƿ�ID������');
		}
	}
	function c_save_edit_pv()
	{
		if ($this->model_seve_edit_pv())
		{
			showmsg('�޸ĳɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('�޸�ʧ�ܣ�','self.parent.location.reload();','button');
		}
	}
	/**
	 * ���
	 *
	 */
	function c_add_pv()
	{
		if ($this->model_add_pv())
		{
			showmsg('��ӳɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('���ʧ�ܣ�');
		}
	}
	function c_show_delete_pv()
	{
		showmsg('��ȷ��Ҫɾ��������Ϣ��',"location.href='?model=purview&action=delete_pv&id=".$_GET['id']."'",'button');
	}
	/**
	 * ɾ��
	 */
	function c_delete_pv()
	{
		if ($this->model_delete_pv())
		{
			showmsg('ɾ���ɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('ɾ��ʧ�ܣ�','self.parent.location.reload();','button');
		}
	}
	//=========================================================================
	/**
	 * ��������Ȩ���û��б�
	 *
	 */
	function c_audit_apply_user_list()
	{
		if ($_GET['type']=='0')
		{
			$selected_0 = 'selected';
		}elseif ($_GET['type']=='1'){
			$selected_1 = 'selected';
		}
		$this->show->assign('selected_1',$selected_1);
		$this->show->assign('selected_0',$selected_0);
		$this->show->assign('list',$this->model_audit_user());
		$this->show->display('purview_user-list');
	}

	function c_add_apply_audit_user()
	{
		$this->show->display('purview_add-user');
	}
	/**
	 * �����û�Ȩ��
	 *
	 */
	function c_show_purview_list()
	{
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('list',$this->model_show_purview_list());
		$this->show->display('purview_set-user-purview');
	}
	/**
	 * ����û�
	 *
	 */
	function c_save_apply_audit_user()
	{
		if ($this->model_save_apply_audit_user())
		{
			showmsg('��ӳɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('���ʧ�ܣ�','self.parent.location.reload();','button');
		}
	}
	function c_set_user_audit_apply_purivew()
	{
		if ($this->model_set_user_audit_apply_purivew())
		{
			showmsg('���óɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('����ʧ�ܣ�','self.parent.location.reload();','button');
		}
	}
	//=====================================
	/**
	 * �����б�
	 *
	 */
	function c_apply_list()
	{
		$this->show->assign($_GET['status'],'selected');
		$this->show->assign('list',$this->model_apply_list());
		$this->show->display('purview_apply-list');
	}
	/**
	 * ����б�
	 */
	function c_audit_list()
	{
		$this->show->assign('list',$this->model_audit_list());
		$this->show->display('purview_audit-list');
	}
	/**
	 * ��ʾ������ҳ
	 *
	 */
	function c_add_apply()
	{
		$this->show->assign('id','');
		$this->show->assign('userid','');
		$this->show->assign('username','');
		$this->show->assign('description','');
		$this->show->assign('return_notse','');
		$this->show->assign('list',$this->model_show_func());
		$this->show->assign('action','save_apply_user_purview');
		$this->show->display('purview_add-apply');
	}
	/**
	 * ��������
	 *
	 */
	function c_save_apply_user_purview()
	{
		if ($this->model_save_apply_user_purview())
		{
			showmsg('����ɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('����ʧ�ܣ�');
		}
	}
	function c_edit_apply()
	{
		if ($_GET['id'] && $_GET['userid'])
		{
			$rs = $this->db->get_one("select description from purview_apply where id=".$_GET['id']);
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('userid',$_GET['userid']);
			$this->show->assign('username',$_GET['username']);
			$this->show->assign('action','save_edit_apply');
			$this->show->assign('description',$rs['description']);
			$this->show->assign('return_notse','�����<span>��ɫ</span>�����Ϊ���˻ص����룡');
			$this->show->assign('list',$this->model_show_func('edit'));
			$this->show->display('purview_add-apply');
		}
	}
	function c_save_edit_apply()
	{
		if ($this->model_save_edit_apply())
		{
			showmsg('����ɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('����ʧ�ܣ��������Ա��ϵ��','self.parent.location.reload();','button');
		}
	}
	/**
	 * ���Ȩ��
	 *
	 */
	function c_audit_func()
	{
		$this->show->assign('id',$_GET['id']);
		$rs = $this->db->get_one("select user_name from user where user_id='".$_GET['userid']."'");
		$this->show->assign('username',$rs['user_name']);
		$rs = $this->db->get_one("select description from purview_apply where id=".$_GET['id']);
		$this->show->assign('description',$rs['description']);
		$this->show->assign('list',$this->model_show_audit_func());
		$this->show->display('purview_audit-func');
	}
	/**
	 * �������
	 *
	 */
	function c_save_audit()
	{
		if ($_POST['submit']==' ͨ������ ' || $_POST['submit']==' ȷ���˻� ')
		{
			if ($this->model_asve_audit())
			{
				showmsg('�����ɹ���','self.parent.location.reload();','button');
			}else{
				showmsg('����ʧ�ܣ�','self.parent.location.reload();','button');
			}
		}elseif ($_POST['submit']==' �˻����� '){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('action','save_audit');
			$this->show->display('purview_return-msg');
		}
	}
	function c_undo_apply()
	{
		if (intval($_GET['id']) && $_GET['username'])
		{
			showmsg('��ȷ��Ҫɾ�� '.$_GET['username'].' ��������',"location.href='?model=purview&action=delete_apply&id=".$_GET['id']."'",'button');
		}else{
			showmsg('�Ƿ�����');
		}
	}
	function c_delete_apply()
	{
		if ($this->model_delete_apply())
		{
			showmsg('�����ɹ���','self.parent.location.reload();','button');
		}else{
			showmsg('����ʧ�ܣ�');
		}
	}

	function c_new_apply()
	{
		$this->show->assign('list',$this->model_new_apply());
		$this->show->display('purview_new-apply');
	}
	function c_treat_apply()
	{
		$this->show->assign('id',$_GET['id']);
		$rs = $this->db->get_one("select userid,description from purview_apply where id=".$_GET['id']);
		$this->show->assign('description',$rs['description']);
		$this->show->assign('userid',$rs['userid']);
		$rs = $this->db->get_one("select user_name from user where user_id='".$rs['userid']."'");
		$this->show->assign('username',$rs['user_name']);
		$this->show->assign('list',$this->model_show_func('treat'));
		$this->show->display('purview_treat-apply');
	}
	/**
	 * ����û������Ȩ��
	 *
	 */
	function c_set_treat_apply()
	{
		if ($_POST['submit']==' ͨ������ ' || $_POST['submit']==' ȷ���˻� ')
		{
			if ($this->model_set_treat_apply())
			{
				showmsg('�����ɹ�','self.parent.location.reload();','button');
			}else{
				showmsg('����ʧ�ܣ�');
			}
		}elseif ($_POST['submit'] == ' �˻����� '){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('action','set_treat_apply');
			$this->show->display('purview_return-msg');
		}
	}
	/**
	 * �鿴�����Ȩ��
	 */
	function c_show_apply_func()
	{
		$this->show->assign('id',$_GET['id']);
		$rs = $this->db->get_one("select userid,description from purview_apply where id=".$_GET['id']);
		$this->show->assign('description',$rs['description']);
		$this->show->assign('userid',$rs['userid']);
		$rs = $this->db->get_one("select user_name from user where user_id='".$rs['userid']."'");
		$this->show->assign('username',$rs['user_name']);
		$this->show->assign('list',$this->model_show_func('show_apply'));
		$this->show->display('purview_audit-func');
	}
	/**
	 * �鿴��˵�Ȩ��
	 */
	function c_show_audit_func()
	{
		$this->show->assign('id',$_GET['id']);
		$rs = $this->db->get_one("select userid,description from purview_apply where id=".$_GET['id']);
		$this->show->assign('description',$rs['description']);
		$this->show->assign('userid',$rs['userid']);
		$rs = $this->db->get_one("select user_name from user where user_id='".$rs['userid']."'");
		$this->show->assign('username',$rs['user_name']);
		$this->show->assign('list',$this->model_show_func('show_audit'));
		$this->show->display('purview_audit-func');
	}
        /**
         *Ա��Ȩ����ʾ
         */
        function c_user_pv(){
            $this->show->display('purview_user-pv');
        }
        function c_user_tree(){
            echo $this->model_user_tree();
        }
        /**
         *Ȩ����Ϣ
         */
        function c_pv_info(){
            $this->show->assign('url', 'index1.php?model=purview&action=pv_info_list&pvkey='.$_GET['pvkey']);
            $this->show->display('purview_pv-info');
        }
        function c_pv_info_list(){
            echo $this->model_pv_info();
        }
}

?>