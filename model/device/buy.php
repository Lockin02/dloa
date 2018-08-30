<?php
class model_device_buy extends model_base
{
	function __construct()
	{
		parent::__construct();
	}
	
	function model_typelist()
	{
		$where = $_SESSION['USER_ID']!='admin' ? " a.dept_id=".$_SESSION['DEPT_ID'] : '';
		if (!$this->num)
		{
			$rs = $this->get_one("select count(0) as num from device_buy_type as a $where");
			$this->num = $rs['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->query("
									select
										a.*,b.dept_name,c.user_name
									from
										device_buy_type as 
										left join department as b on b.dept_id=a.dept_id
										left join user as c on c.user_id=a.userid
									$where
									order by a.id desc
									limit $this->start,".pagenum);
			$str ='';
			while (($rs = $this->fetch_array($query))!=false)
			{
				$str .= '<tr>';
				$str .='<td>'.$rs['id'].'</td>';
				$str .='<td>'.$rs['typename'].'</td>';
				$str .='<td>'.$rs['dept_name'].'</td>';
				$str .='<td>'.$rs['user_name'].'</td>';
				$str .='<td>'.date('Y-m-d',$rs['date']).'</td>';
				$str .= '</tr>';
			}
			
			if ($this->num > pagenum)
			{
				$showpage = new includes_class_page ();
				$showpage->show_page ( array(
												
												'total'=>$this->num,
												'perpage'=>pagenum
				) );
				$showpage->_set_url ( 'num=' . $this->num );
				return $str . '<tr><td colspan="20">' . $showpage->show ( 6 ) . '</td></tr>';
			} else
			{
				return $str;
			}
		}
	}
	
	function model_mylist()
	{
		
	}
	
	function model_audit()
	{
		
	}
	
	function model_edit()
	{
		
	}
	
	function model_field_input($typeid)
	{
		$stock = new model_device_stock();
		$fixed = $stock->get_fixed_field_name($typeid,false);
		$field = $stock->model_show_field_name($typeid,false);
		$title_html = '';
		$input_html = '';
		
		if ($field)
		{
			foreach ($field as $key => $row) {
				$title_html .='<td>'.$row['fname'].'</td>';
				$input_html .='<td><input type="text" size="12" name="'.$row['id'].'[]"</td>';
			}
		}
		
		return '<br /><table class="table" width="98%"><tr class="tableheader">'.$title_html.'</tr><tr>'.$input_html.'</tr></table>';
	}
}

?>