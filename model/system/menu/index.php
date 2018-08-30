<?php
class model_system_menu_index extends model_base
{
	function __construct()
	{
		parent::__construct ();
	}
	/**
	 * 获取职位
	 * @param unknown_type $deptid_str
	 * @param unknown_type $jobs_id_arr
	 */
	function get_jobs($deptid_str = '', $jobs_id_arr = '')
	{
		
		$deptid_str = $_GET['deptid_str'] ? rtrim ( $_GET['deptid_str'], ',' ) : $deptid_str;
		$jobs_arr = $jobs_id_arr ? explode ( ',', $jobs_id_arr ) : ($_GET['jobs_str'] ? explode ( ',', $_GET['jobs_str'] ) : '');
		$query = $this->_db->query ( "
		select 
			a.id,a.name,a.dept_id,b.dept_name 
		from 
			user_jobs as a 
			left join department as b on b.dept_id=a.dept_id
			" . ($deptid_str ? ' where a.dept_id !=124 and a.dept_id in(' . $deptid_str . ')' : '') . "
		order by a.dept_id desc
		" );
		$arr = array();
		$j = 0;
		while ( ($rs = $this->_db->fetch_array ( $query )) != false )
		{
			if (empty ( $arr[$rs['dept_name']] ))
			{
				$j ++;
				if ($j == 1)
				{
					$str .= '<span id="dept_' . $rs['dept_id'] . '" style="color:#000000;">';
				} else
				{
					$str .= '</span><span id="dept_' . $rs['dept_id'] . '" style="color:#000000;">';
				}
				$str .= '<input type="checkbox" value="" onclick="all_jobs(\'' . $rs['dept_id'] . '\',this.checked);" /><b>' . $rs['dept_name'] . '</b><br />';
				$arr[$rs['dept_name']] = true;
				$str .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . (($jobs_arr && in_array ( $rs['id'], $jobs_arr )) ? 'checked' : '') . ' onclick="add_jobs()" name="jobsid[]" title="' . $rs['name'] . '" value="' . $rs['id'] . '" />' . $rs['name'] . '<br />';
			} else
			{
				$str .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . (($jobs_arr && in_array ( $rs['id'], $jobs_arr )) ? 'checked' : '') . ' onclick="add_jobs()" name="jobsid[]" title="' . $rs['name'] . '" value="' . $rs['id'] . '" />' . $rs['name'] . '<br />';
			}
		}
		return $str ? '<input type="checkbox" onclick="all_checked(\'_jobs_id_\',\'jobsid[]\',this.checked)" ><b>全选</b><br />' . $str . '</span>' : '';
	}
}

?>