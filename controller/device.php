<?php
class controller_device extends model_device
{
	public $show;
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'device';
		$this->pk = 'id';
		$this->show = new show();
	}

	function c_index()
	{

	}
	/**
	 * 添加类别
	 *
	 */
	function c_add_type()
	{
		if ($this->model_insert_type())
		{
			showmsg('添加成功！','?model=device&action=typelist');
		}
	}
	/**
	 * 类别列表
	 *
	 */
	function c_typelist()
	{
		global $func_limit;
		$this->show->assign('list',$this->model_typelist());
		if ($_SESSION['USER_ID']=='admin')
		{
			$dept = $this->newclass('includes_class_depart');
			$select_dept = '所属部门：<select id="deptid" name="deptid"><option value="">请选择部门</option>';
			$select_dept .= $dept->depart_select();
			$select_dept .= '</select>';
			unset($dept);
			$this->show->assign('select_dept',$select_dept);
			$this->show->assign('admin','true');
		}else{
			$this->show->assign('select_dept','&nbsp;');
			$this->show->assign('admin','false');
		}
		$this->show->display('device_typelist');
	}

	function c_del_type()
	{
		if ($this->model_delete_type())
		{
			showmsg('删除成功！','?model=device&action=typelist');
		}else{
			showmsg('删除失败！','?model=device&action=typelist');
		}
	}

	function c_set_field()
	{
		$this->tbl_name = 'device_type_field';
		$field = $this->findAll('typeid='.$_GET['typeid'],'sort asc');
		if ($field)
		{
			foreach ($field as $rs)
			{
				$str .='
			<tr id="tr_'.$rs['id'].'">
				<td>
					字段名称：<input type="text" name="field['.$rs['id'].']" value="'.$rs['fname'].'" /> 
					属性：<select name="property['.$rs['id'].']">'.$this->c_property_select($rs['property']).'</select> 
					唯一值：<select name="only['.$rs['id'].']"><option '.($rs['only']==0 ? 'selected' : '').' value="0">否</option><option '.($rs['only']==1 ? 'selected' : '').' value="1">是</option></select>
					序号：<input type="text" size="5" onKeyUp="this.value=value=this.value.replace(/\\D/g,\'\')" name="sort['.$rs['id'].']" value="'.$rs['sort'].'" />
					<input type="button" onclick="del('.$rs['id'].')" value=" 删除 " />
				</td>
			</tr>
			';
			}
		}
		$this->tbl_name = 'device_type';
		$rs = $this->find('id='.$_GET['typeid'],null,'_coding,_dpcoding,_fitting,_price,_notes');
		foreach ($rs as $key=>$val)
		{
			if ($val)
			{
				$this->show->assign($key.'_1','checked');
				$this->show->assign($key.'_0','');
			}else{
				$this->show->assign($key.'_1','');
				$this->show->assign($key.'_0','checked');
			}
		}
		$this->show->assign('id',$_GET['typeid']);
		$this->show->assign('set_field',$str);
		$this->show->display('device_field');
	}
	function c_property_select($str)
	{
		$arr = array('text'=>'文本','int'=>'数字','date'=>'日期');
		foreach ($arr as $key=>$val)
		{
			if ($key==$str)
			{
				$html .='<option selected value="'.$key.'">'.$val.'</option>';
			}else{
				$html .='<option value="'.$key.'">'.$val.'</option>';
			}
		}
		return $html;
	}
	function c_update_field()
	{
		if ($this->model_update_field())
		{
			showmsg('设置成功！','self.parent.tb_remove();self.parent.location.reload();','button');
		}else{
			showmsg('没有作任何修改！','self.parent.tb_remove();self.parent.location.reload();','button');
		}
	}
	function c_show_apply()
	{
		$this->tbl_name = 'purchase_type';
		$this->show->assign('USER_NAME',$_SESSION['USERNAME']);
		$this->show->assign('DEPT_NAME',$this->get_table_fields('department',"DEPT_ID=".$_SESSION['DEPT_ID']."",'DEPT_NAME'));
		$this->show->assign('select_type',$this->select_type());
		$this->show->display('purchase_apply');
	}
}
?>
