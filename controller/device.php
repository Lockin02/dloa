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
	 * ������
	 *
	 */
	function c_add_type()
	{
		if ($this->model_insert_type())
		{
			showmsg('��ӳɹ���','?model=device&action=typelist');
		}
	}
	/**
	 * ����б�
	 *
	 */
	function c_typelist()
	{
		global $func_limit;
		$this->show->assign('list',$this->model_typelist());
		if ($_SESSION['USER_ID']=='admin')
		{
			$dept = $this->newclass('includes_class_depart');
			$select_dept = '�������ţ�<select id="deptid" name="deptid"><option value="">��ѡ����</option>';
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
			showmsg('ɾ���ɹ���','?model=device&action=typelist');
		}else{
			showmsg('ɾ��ʧ�ܣ�','?model=device&action=typelist');
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
					�ֶ����ƣ�<input type="text" name="field['.$rs['id'].']" value="'.$rs['fname'].'" /> 
					���ԣ�<select name="property['.$rs['id'].']">'.$this->c_property_select($rs['property']).'</select> 
					Ψһֵ��<select name="only['.$rs['id'].']"><option '.($rs['only']==0 ? 'selected' : '').' value="0">��</option><option '.($rs['only']==1 ? 'selected' : '').' value="1">��</option></select>
					��ţ�<input type="text" size="5" onKeyUp="this.value=value=this.value.replace(/\\D/g,\'\')" name="sort['.$rs['id'].']" value="'.$rs['sort'].'" />
					<input type="button" onclick="del('.$rs['id'].')" value=" ɾ�� " />
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
		$arr = array('text'=>'�ı�','int'=>'����','date'=>'����');
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
			showmsg('���óɹ���','self.parent.tb_remove();self.parent.location.reload();','button');
		}else{
			showmsg('û�����κ��޸ģ�','self.parent.tb_remove();self.parent.location.reload();','button');
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
