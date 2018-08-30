<?php
class model_system_jobs
{
	public $db;

	function __construct()
	{
		$this->db = new mysql();
	}
	/**
	 * 职位数据
	 * @param $dept_id
	 */
	function GetJobData($dept_id=null)
	{
		if ($dept_id && is_array($dept_id))
		{
			$condition = implode(',',$dept_id);
		}elseif ($dept_id){
			$condition = $dept_id;
		}
		
		$query = $this->db->query("select * from user_jobs ".($condition ? " where dept_id in ($condition)" : ''));
		$data = array();
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	function showlist()
	{
		$query = $this->db->query("select a.*,b.DEPT_NAME from user_jobs as a left join department as b on b.DEPT_ID=a.dept_id order by a.dept_id desc,a.level asc");
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			$str .='
		<tr id="tr_'.$rs['id'].'">
			<td height="25" align="center">'.$rs['id'].'</td>
			<td align="center" id="dept_'.$rs['id'].'">'.$rs['DEPT_NAME'].'</td>
			<td align="center" id="name_'.$rs['id'].'">'.$rs['name'].'</td>
			<td align="center" id="level_'.$rs['id'].'">'.$rs['level'].'</td>
			<td align="center" id="m_'.$rs['id'].'"> 
				<a href="javascript:edit('.$rs['id'].')">修改职位</a> | 
				<a href="?model=jobs&action=edit_func&id='.$rs['id'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" class="thickbox" title="编辑《'.$rs['name'].'》职位权限">编辑权限</a> | 
				<a href="javascript:del('.$rs['id'].')">删除职位</a></td>
		</tr>
		';
		}
		return $str;
	}
	function insert()
	{
		@extract($_POST);
		@extract($_GET);
		$this->db->query("insert into user_jobs(name,dept_id,level)values('$name','$deprtid','$level')");
		return true;
	}
	function update()
	{
		@extract($_POST);
		@extract($_GET);
		if ($name && $id)
		{
			//$name = iconv('UTF-8','gb2312',$name);
			$row = $this->db->get_one("select id from user_jobs where id=$id");
			if ($row)
			{
				$query = "update user_jobs set dept_id='$dept_id', name='$name',level='$level' where id=$id";
				$this->db->query($query);
				echo 1;
			}else{
				echo 2;
			}
		}
	}
	function delete()
	{
		if ($_GET['id'])
		{
			$row = $this->db->get_one("select id from user_jobs where id=".$_GET['id']);
			if ($row)
			{
				$rs = $this->db->get_one("select * from user where find_in_set('".$_GET['id']."',jobs_id)");
				if ($rs)
				{
					echo 2;
				}else{
					$this->db->query("delete from user_jobs where id=".$_GET['id']);
					echo 1;
				}
			}else{
				echo 0;
			}
		}else{
			echo 0;
		}
	}
	function edit_purview()
	{
		global $user_func_id;
		if (intval($_GET['id']))
		{
			$rs = $this->db->get_one("select func_id_str from user_jobs where id=".$_GET['id']);
			$func_id_str = explode(',',$rs['func_id_str']);
		}else{
			showmsg('非法访问！');
		}
		$powerFun=array();
		$query=$this->db->query("select left(menuid,2) as m1 , left(menuid,4) as m2  from purview");
		while(($rs = $this->db->fetch_array($query))!=false){
			$menuSql .= $rs["m1"].',';
			$funcSql .= $rs['m2'].',';
		}
		$menuSql = implode(',',array_unique(explode(',',$menuSql)));
		$funcSql = implode(',',array_unique(explode(',',$funcSql)));
		$menuSql = rtrim($menuSql,',');
		$funcSql = rtrim($funcSql,',');
		$str .='<ul><div class="submit"><input type="submit" value=" 修 改 " /> <input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " /></div></ul>';
		
		$query = $this->db->query("
									select 
										* 
									from 
										sys_menu 
									where 
										MENU_ID in ($menuSql)  
									order by taxis_id asc
								");
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			if (!in_array($rs['MENU_ID'],$user_func_id)) continue;
			$str .='
			<ul id="title_'.$rs['MENU_ID'].'">
			<div class="title"><input type="checkbox" onclick="check(\''.$rs['MENU_ID'].'\')" />'.$rs['MENU_NAME'].'</div>';
			$sql = $this->db->query("select * from sys_function where MENU_ID like '".$rs["MENU_ID"]."%' and length(MENU_ID)=4 and menu_id in ($funcSql)  order by taxis_id ASC");
			$i=0;
			$num = $this->db->num_rows($sql);
			while (($row = $this->db->fetch_array($sql))!=false)
			{
				if (!in_array($row['MENU_ID'],$user_func_id)) continue;
				$i++;
				if ($i==1) $str .='<div class="menulist">';
				$str .='<li id="title_'.$row['MENU_ID'].'"><input type="checkbox" onclick="check(\''.$row['MENU_ID'].'\')" name="id[]" value="_'.$row['MENU_ID'].'" '.$this->checked($func_id_str,'_'.$row['MENU_ID']).'/><b>'.$row['FUNC_NAME'].'</b>';
				$psql = $this->db->query("select * from purview where menuid='".$row['MENU_ID']."'");
				while (($ra = $this->db->fetch_array($psql))!=false)
				{
					if (!in_array($ra['id'],$user_func_id)) continue;
					if ($ra['name']!='访问')
					{
						$str .='<ul><li><input type="checkbox" name="id[]" '.$this->checked($func_id_str,$ra['id']).' value="'.$ra['id'].'">'.$ra['name'].'</li></ul>';
					}
				}
				$mysql = $this->db->query( "select * from sys_function where MENU_ID like '".$row["MENU_ID"]."%' and length(MENU_ID)>4 $sqlPower order by taxis_id ASC ");
				while (($rss = $this->db->fetch_array($mysql))!=false)
				{
					if (!in_array($rss['MENU_ID'],$user_func_id)) continue;
					$str .='<ul id="title_'.$rss['MENU_ID'].'"><li><input type="checkbox" onclick="check(\''.$rss['MENU_ID'].'\')" name="id[]" value="_'.$rss['MENU_ID'].'" '.$this->checked($func_id_str,'_'.$rss['MENU_ID']).'/><span style="color:#0099FF;">'.$rss['FUNC_NAME'].'</span>';
					$msql = $this->db->query("select * from purview where menuid='".$rss['MENU_ID']."'");
					while (($rr = $this->db->fetch_array($msql))!=false)
					{
						if(!in_array($rr['id'],$user_func_id)) continue;
						if ($rr['name']!='访问')
						{
							$str .='<ul><li><input type="checkbox" name="id[]" '.$this->checked($func_id_str,$rr['id']).' value="'.$rr['id'].'">'.$rr['name'].'</li></ul>';
						}
					}
					$str .='</li></ul>';
				}
				$str .='</li>';
				if ($i%6==0) $str .='</div>';
				if ($i%6==0 && $i!=$num) $str .='<div class="menulist">';
			}
			$str .='</ul>';
		}
		$str .='<ul><div class="submit"><input type="submit" value=" 修 改 " /> <input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " /></div></ul>';
		return $str;
	}

	function model_save_func()
	{
		if (intval($_GET['jobs_id']))
		{
			if ($_POST['id'])
			{
				global $user_func_id;
				$rs = $this->db->get_one("select func_id_str from user_jobs where id=".$_GET['jobs_id']);
				$raw_func_id = $rs['func_id_str'] ? explode(',',$rs['func_id_str']) : null;
				$chcked = array();
				$notchecked = array();
				$new_func_id = array();
				foreach ($user_func_id as $val)
				{
					if (in_array($val,$_POST['id']))
					{
						$chcked[] = $val;
					}else{
						$notchecked[] = $val;
					}
				}
				if ($raw_func_id)
				{
					foreach ($raw_func_id as $val)
					{
						if (!in_array($val,$notchecked))
						{
							$new_func_id[] = $val;
						}
					}
				}
				$func_id_str = array_unique(array_merge($new_func_id,$chcked));
				return $this->db->query("update user_jobs set func_id_str='".$func_id_str."' where id=".$_GET['jobs_id']);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function checked($func_id_str,$menuid)
	{
		if (in_array($menuid,$func_id_str))
		{
			return 'checked';
		}else{
			return '';
		}
	}
}
?>