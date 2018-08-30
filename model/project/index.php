<?php
class model_project_index extends model_base
{
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'project_info';
	}
	/**
	 * 列表
	 */
	function model_list()
	{
		global $func_limit;
		if (!$func_limit['管理部门'])
		{
			$where = " where a.dept_id='".$_SESSION['DEPT_ID']."'";
		}else{
			$where = " where a.dept_id in(".$func_limit['管理部门'].")";
		}
		if (!$this->num)
		{
			$rs = $this->get_one("select count(0) as num from project_info as a $where");
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query("select 
										a.*,b.dept_name,c.project_name as ipo_name
									from 
										project_info as a 
										left join department as b on b.dept_id=a.dept_id 
										left join project_ipo as c on c.id=a.ipo_id
										$where 
										order by a.id desc limit $this->start ,".pagenum);
			$data = $this->get_username();
			while (($rs = $this->fetch_array($query))!=false)
			{
				$str .='<tr>';
				$str .='<td>'.$rs['id'].'</td>';
				$str .='<td>'.$rs['name'].'</td>';
				$str .='<td>'.$rs['number'].'</td>';
				$str .='<td>'.$rs['ipo_name'].'</td>';
				$str .='<td>'.$rs['dept_name'].'</td>';
				$str .='<td nowrap>'.($rs['producmanager'] ? implode('、',$this->get_username_list($data,$rs['producmanager'])) : '').'</td>';
				$str .='<td nowrap>'.($rs['productassistant'] ? implode('、',$this->get_username_list($data,$rs['productassistant'])) : '').'</td>';
				$str .='<td nowrap>'.($rs['manager'] ? implode('、',$this->get_username_list($data,$rs['manager'])) : '').'</td>';
				$str .='<td nowrap>'.($rs['teamleader'] ? implode('、',$this->get_username_list($data,$rs['teamleader'])) : '').'</td>';
				$str .='<td nowrap width="150">'.($rs['developer'] ? implode('、',$this->get_username_list($data,$rs['developer'])) : '').'</td>';
				$str .='<td nowrap>'.($rs['testleader'] ? implode('、',$this->get_username_list($data,$rs['testleader'])) : '').'</td>';
				$str .='<td nowrap width="150">'.($rs['tester'] ? implode('、',$this->get_username_list($data,$rs['tester'])) : '').'</td>';
				$str .='<td nowrap width="150">'.($rs['qc'] ? implode('、',$this->get_username_list($data,$rs['qc'])) : '').'</td>';
				$str .='<td>'.date('Y-m-d',$rs['date']).'</td><td>';
				if($rs['flag']=='1'){
					$str .=' <a href="javascript:upflag('.$rs['id'].',2)"> 关闭</a>';
				}
				if($rs['flag']=='2'){
					$str .=' <a href="javascript:upflag('.$rs['id'].',1)"> 开启</a>';
				}
				$str .='&nbsp;&nbsp;&nbsp;'.thickbox_link('查看修改','a','id='.$rs['id'].'&key='.$rs['rand_key'],'查看或修改',null,'edit',600,500).'</td>';
				$str .='</tr>';
			}
			
			if ($this->num > pagenum )
			{
				$showpage = new includes_class_page ();
				$showpage->show_page ( array(
					
							'total'=>$this->num,
							'perpage'=>pagenum
				) );
				$showpage->_set_url ( 'num=' . $this->num );
				return $str.'<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
			}else{
				return $str;
			}
		}
	}
	/**
	 * 添加
	 * @param $data
	 */
	function model_add($data)
	{
		if (is_array($data))
		{
			$data['producmanager'] = $data['producmanager'] ? implode(',',$data['producmanager']) : '';
			$data['productassistant'] = $data['productassistant'] ? implode(',',$data['productassistant']) : '';
			$data['manager'] = $data['manager'] ? implode(',',$data['manager']) : '';
			$data['teamleader'] = $data['teamleader'] ? implode(',',$data['teamleader']) : '';
			$data['developer'] = $data['developer'] ? implode(',',$data['developer']) : '';
			$data['testleader'] = $data['testleader'] ? implode(',',$data['testleader']) : '';
			$data['tester'] = $data['tester'] ? implode(',',$data['tester']) : '';
			$data['qc'] = $data['qc'] ? implode(',',$data['qc']) : '';
			$data['date'] = time();
			$data['product_id_str'] = $data['product_id_str'] ? implode(',', $data['product_id_str']) : '';
			return $this->create($data);
		}else{
			return false;
		}
	}
	/**
	 * 修改
	 * @param $id
	 * @param $key
	 * @param $data
	 */
	function model_edit($id,$key,$data)
	{
		if ($id && $key && is_array($data))
		{
			$data['producmanager'] = $data['producmanager'] ? implode(',',$data['producmanager']) : '';
			$data['productassistant'] = $data['productassistant'] ? implode(',',$data['productassistant']) : '';
			$data['manager'] = $data['manager'] ? implode(',',$data['manager']) : '';
			$data['teamleader'] = $data['teamleader'] ? implode(',',$data['teamleader']) : '';
			$data['developer'] = $data['developer'] ? implode(',',$data['developer']) : '';
			$data['testleader'] = $data['testleader'] ? implode(',',$data['testleader']) : '';
			$data['tester'] = $data['tester'] ? implode(',',$data['tester']) : '';
			$data['qc'] = $data['qc'] ? implode(',',$data['qc']) : '';
			$data['product_id_str'] = $data['product_id_str'] ? implode(',', $data['product_id_str']) : '';
			return $this->update(array('id'=>$id,'rand_key'=>$key),$data);
		}else{
			return false;
		}
	}
	function model_upflag()
	{
		$id=$_POST['id'];
		$flag=$_POST['flag'];
		if ($id && $flag)
		{
			$data['flag']=$flag;
			echo  $this->update(array('id'=>$id),$data);
		}
	}
	/**
	 * 删除
	 * @param $id
	 * @param $key
	 */
	function model_del($id,$key)
	{
		if ($id && $key)
		{
			return $this->delete(array('id'=>$id,'rand_key'=>$key));
		}else{
			return false;
		}
	}
	/**
	 * 获取所有用户名数组，KEY=USER_ID,VALUE=USER_NAME
	 */
	function get_username()
	{
		$query = $this->query("select user_id,user_name from user");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false) {
			$data[$rs['user_id']] = $rs['user_name'];
		}
		return $data;
	}
	/**
	 * 按用户ID返回用户名数组
	 * @param unknown_type $data
	 * @param unknown_type $userid
	 */
	function get_username_list($data,$userid)
	{
		if ($data && $userid)
		{
			$arr = explode(',',$userid);
			$username = array();
			if ($arr)
			{
				foreach ($arr as $val)
				{
					$username[$val] = $data[$val];
				}
			}
			return $username;
		}else{
			return false;
		}
	}
}

?>