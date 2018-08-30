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
		$modelName = $this->clean($_GET['modelName']); //搜索模块的关键字
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
			showmsg('修改成功！','self.parent.tb_remove();self.parent.location.reload();','button');
		}else{
			showmsg('修改失败！','self.parent.tb_remove();self.parent.location.reload();','button');
		}
	}
	function c_new_add()
	{
		if ($this->insert())
		{
			showmsg('添加成功！','self.parent.location.reload();','button');
		}else{
			showmsg('添加失败！','self.parent.tb_remove();','button');
		}
	}
	function c_showdelinfo()
	{
		showmsg('您确定要删除该条信息？',"location.href='?model=purview&action=del&id=".$_GET['id']."'",'button');
	}
	function c_del()
	{
		if ($this->delete())
		{
			showmsg('删除成功！','self.parent.tb_remove();self.parent.location.reload();','button');
		}else{
			showmsg('删除失败！','self.parent.tb_remove();self.parent.location.reload();','button');
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
	 * 添加模块界面
	 *
	 */
	function c_show_add_model()
	{
		$this->show->assign('menu_select',$this->menu->menu_select());
		$this->show->display('purview_add-model');
	}
	//添加权限界面
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
			showmsg('非法ID参数！');
		}
	}
	function c_save_edit_pv()
	{
		if ($this->model_seve_edit_pv())
		{
			showmsg('修改成功！','self.parent.location.reload();','button');
		}else{
			showmsg('修改失败！','self.parent.location.reload();','button');
		}
	}
	/**
	 * 添加
	 *
	 */
	function c_add_pv()
	{
		if ($this->model_add_pv())
		{
			showmsg('添加成功！','self.parent.location.reload();','button');
		}else{
			showmsg('添加失败！');
		}
	}
	function c_show_delete_pv()
	{
		showmsg('您确定要删除该条信息？',"location.href='?model=purview&action=delete_pv&id=".$_GET['id']."'",'button');
	}
	/**
	 * 删除
	 */
	function c_delete_pv()
	{
		if ($this->model_delete_pv())
		{
			showmsg('删除成功！','self.parent.location.reload();','button');
		}else{
			showmsg('删除失败！','self.parent.location.reload();','button');
		}
	}
	//=========================================================================
	/**
	 * 申请或审核权限用户列表
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
	 * 设置用户权限
	 *
	 */
	function c_show_purview_list()
	{
		$this->show->assign('id',$_GET['id']);
		$this->show->assign('list',$this->model_show_purview_list());
		$this->show->display('purview_set-user-purview');
	}
	/**
	 * 添加用户
	 *
	 */
	function c_save_apply_audit_user()
	{
		if ($this->model_save_apply_audit_user())
		{
			showmsg('添加成功！','self.parent.location.reload();','button');
		}else{
			showmsg('添加失败！','self.parent.location.reload();','button');
		}
	}
	function c_set_user_audit_apply_purivew()
	{
		if ($this->model_set_user_audit_apply_purivew())
		{
			showmsg('设置成功！','self.parent.location.reload();','button');
		}else{
			showmsg('设置失败！','self.parent.location.reload();','button');
		}
	}
	//=====================================
	/**
	 * 申请列表
	 *
	 */
	function c_apply_list()
	{
		$this->show->assign($_GET['status'],'selected');
		$this->show->assign('list',$this->model_apply_list());
		$this->show->display('purview_apply-list');
	}
	/**
	 * 审核列表
	 */
	function c_audit_list()
	{
		$this->show->assign('list',$this->model_audit_list());
		$this->show->display('purview_audit-list');
	}
	/**
	 * 显示申请面页
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
	 * 保存申请
	 *
	 */
	function c_save_apply_user_purview()
	{
		if ($this->model_save_apply_user_purview())
		{
			showmsg('申请成功！','self.parent.location.reload();','button');
		}else{
			showmsg('操作失败！');
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
			$this->show->assign('return_notse','下面带<span>红色</span>交叉的为被退回的申请！');
			$this->show->assign('list',$this->model_show_func('edit'));
			$this->show->display('purview_add-apply');
		}
	}
	function c_save_edit_apply()
	{
		if ($this->model_save_edit_apply())
		{
			showmsg('保存成功！','self.parent.location.reload();','button');
		}else{
			showmsg('保存失败，请与管理员联系！','self.parent.location.reload();','button');
		}
	}
	/**
	 * 审核权限
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
	 * 保存审核
	 *
	 */
	function c_save_audit()
	{
		if ($_POST['submit']==' 通过申请 ' || $_POST['submit']==' 确定退回 ')
		{
			if ($this->model_asve_audit())
			{
				showmsg('操作成功！','self.parent.location.reload();','button');
			}else{
				showmsg('操作失败！','self.parent.location.reload();','button');
			}
		}elseif ($_POST['submit']==' 退回申请 '){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('action','save_audit');
			$this->show->display('purview_return-msg');
		}
	}
	function c_undo_apply()
	{
		if (intval($_GET['id']) && $_GET['username'])
		{
			showmsg('您确定要删除 '.$_GET['username'].' 的申请吗？',"location.href='?model=purview&action=delete_apply&id=".$_GET['id']."'",'button');
		}else{
			showmsg('非法请求！');
		}
	}
	function c_delete_apply()
	{
		if ($this->model_delete_apply())
		{
			showmsg('操作成功！','self.parent.location.reload();','button');
		}else{
			showmsg('操作失败！');
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
	 * 添加用户申请的权限
	 *
	 */
	function c_set_treat_apply()
	{
		if ($_POST['submit']==' 通过申请 ' || $_POST['submit']==' 确定退回 ')
		{
			if ($this->model_set_treat_apply())
			{
				showmsg('操作成功','self.parent.location.reload();','button');
			}else{
				showmsg('操作失败！');
			}
		}elseif ($_POST['submit'] == ' 退回申请 '){
			$this->show->assign('id',$_GET['id']);
			$this->show->assign('action','set_treat_apply');
			$this->show->display('purview_return-msg');
		}
	}
	/**
	 * 查看申请的权限
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
	 * 查看审核的权限
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
         *员工权限显示
         */
        function c_user_pv(){
            $this->show->display('purview_user-pv');
        }
        function c_user_tree(){
            echo $this->model_user_tree();
        }
        /**
         *权限信息
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