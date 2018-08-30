<?php
class model_ajax_user extends includes_class_page
{
	public $db;
	function __construct()
	{
		if ($_POST)
		{
			$_POST = mb_iconv($_POST);
		}
		$this->db = new mysql();
	}
	/**
	 * 用户列表
	 */
	function depart_user_list()
	{
		$depart_id = $_GET['depart_id'] ? $_GET['depart_id'] : $_POST['depart_id'];
		$num = $_GET['num'] ? $_GET['num'] : $_POST['num'];
		$page = $_GET['page'] ? $_GET['page'] :($_POST['page'] ? $_POST['page'] : 1);
		$start = ($page==1) ? 0 : ($page - 1) * pagenum;
		if (!$num)
		{
			if ($depart_id)
			{
				$rs = $this->db->get_one("select count(0) as num from user where DEPT_ID=".$depart_id."");
			}else{
				$rs = $this->db->get_one("select count(0) as num from user");
			}
			$num = $rs['num'];
		}
		if ($depart_id)
		{
			$query = $this->db->query("select a.USER_ID,a.LogName,a.USER_NAME,a.SORT,a.HAS_LEFT,a.ready_left,a.jobs_id,b.DEPT_NAME from user as a left join department as b on b.DEPT_ID=a.DEPT_ID where a.DEPT_ID=".$depart_id." order by a.HAS_LEFT,a.CreatDT desc limit $start,".pagenum."");
		}else{
			$query = $this->db->query("select a.USER_ID,a.LogName,a.USER_NAME,a.SORT,a.HAS_LEFT,a.ready_left,a.jobs_id,b.DEPT_NAME from user as a left join department as b on b.DEPT_ID=a.DEPT_ID order by a.HAS_LEFT,a.CreatDT desc limit $start,".pagenum."");
		}
                if($_SESSION['COM_BRN_PT']=='dl'||$_SESSION['COM_BRN_PT']=='bx'){
                    $editlist='edit_func_list';
                }else{
                    $editlist='edit_func_list_new';
                }
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			$str .='
				<tr>
        		<td align="center" height="25">'.$rs['SORT'].'</td>
        		<td align="center">'.$rs['LogName'].'</td>
        		<td align="center">'.$rs['USER_NAME'].'</td>
        		<td align="center">'.$rs['DEPT_NAME'].'</td>
        		<td align="center">'.includes_class_jobs::get_job_name($rs['jobs_id']).'</td>
        		';
				if ($rs['HAS_LEFT']=='1')
				{
					$str .='<td><span style="color: red;">离职</span></td>';
				}elseif($rs['ready_left']==1){
					$str .='<td><span style="color: red;">准备离职</span></td>';
				}else{
					$str .='<td>在职</td>';
				}
				if ($rs['HAS_LEFT']=='1')
				{
					$str .='<td align="center">已离职，不可操作</td>';
				}else{
				$str .='<td align="center">
	        		<a href="?model=user&action=edit&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600" title="编辑《'.$rs['USER_ID'].'》用户" class="thickbox">编辑用户</a>
	        		| <a href="?model=user&action='.$editlist.'&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" title="修改《'.$rs['USER_ID'].'》用户权限" class="thickbox">权限设置</a>
	        		| <a href="?model=user&action=update_password&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=500" class="thickbox" title="重设 《'.$rs['USER_ID'].'》用户密码">重设密码</a>
	        		</td>';
				}
			$str .='</tr>';
		}
		$pageclass = $this->show_page(array('total'=>$num,'perpage'=>pagenum,'ajax'=>'show_page','page_name'=>'page'));
		$this->_set_url('ajax.php?num='.$num.'&model=user&action=depart_user_list&depart_id='.$depart_id.'&');
		return $str.'<tr><td colspan="7" height="25">'.$this->show(6).'</td></tr>';
	}
	/**
	 * 职位用户
	 */
	function jobs_user_list()
	{
		$jobs_id = $_GET['jobs_id'] ? $_GET['jobs_id'] : $_POST['jobs_id'];
		$jobs_name = $_GET['jobs_name'] ? $_GET['jobs_name'] : $_POST['jobs_name'];
		$num = $_GET['num'] ? $_GET['num'] : $_POST['num'];
		$page = $_GET['page'] ? $_GET['page'] :($_POST['page'] ? $_POST['page'] : 1);
		$start = ($page==1) ? 0 : ($page - 1) * pagenum;
		if (!$num){
			if($jobs_name){
				$rs = $this->db->get_one("SELECT COUNT(0) AS num FROM USER LEFT JOIN user_jobs ON USER.jobs_id=user_jobs.id WHERE user_jobs.`name`='".$jobs_name."'");
			}else if($jobs_id){
				$rs = $this->db->get_one("select count(0) as num from user where find_in_set(".$jobs_id.",jobs_id)");
			}else{
				$rs = $this->db->get_one("select count(0) as num from user");
			}
			$num = $rs['num'];
		}
		if($jobs_name){ //根据职位名称过滤所有用户
			$query = $this->db->query("select a.USER_ID,a.LogName,a.USER_NAME,a.SORT,a.HAS_LEFT,a.ready_left,a.jobs_id,b.DEPT_NAME from user as a left join department as b on b.DEPT_ID=a.DEPT_ID LEFT JOIN user_jobs j ON a.jobs_id = j.id where j.`name`='".$jobs_name."' order by a.HAS_LEFT,a.CreatDT desc limit $start,".pagenum."");
		}else if ($jobs_id){
			$query = $this->db->query("select a.USER_ID,a.LogName,a.USER_NAME,a.SORT,a.HAS_LEFT,a.ready_left,a.jobs_id,b.DEPT_NAME from user as a left join department as b on b.DEPT_ID=a.DEPT_ID where find_in_set(".$jobs_id.",jobs_id) order by a.HAS_LEFT,a.CreatDT desc limit $start,".pagenum."");
		}else{
			$query = $this->db->query("select a.USER_ID,a.LogName,a.USER_NAME,a.SORT,a.HAS_LEFT,a.ready_left,a.jobs_id,b.DEPT_NAME from user as a left join department as b on b.DEPT_ID=a.DEPT_ID order by a.HAS_LEFT,a.CreatDT desc limit $start,".pagenum."");
		}
        if($_SESSION['COM_BRN_PT']=='dl'||$_SESSION['COM_BRN_PT']=='bx'){
            $editlist='edit_func_list';
        }else{
            $editlist='edit_func_list_new';
        }
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			$str .='
				<tr>
        		<td align="center" height="25">'.$rs['SORT'].'</td>
        		<td align="center">'.$rs['LogName'].'</td>
        		<td align="center">'.$rs['USER_NAME'].'</td>
        		<td align="center">'.$rs['DEPT_NAME'].'</td>
        		<td align="center">'.includes_class_jobs::get_job_name($rs['jobs_id']).'</td>
        		<td align="center">';
				if ($rs['HAS_LEFT']=='1')
				{
					$str .='<span style="color: red;">离职</span>';
				}elseif($rs['ready_left']==1){
					$str .='<span style="color: red;">准备离职</span>';
				}else{
					$str .='在职';
				}
			$str .='</td>
        		<td align="center">
        		<a href="?model=user&action=edit&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600" title="编辑《'.$rs['USER_ID'].'》用户" class="thickbox">编辑用户</a>
        		| <a href="?model=user&action='.$editlist.'&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" title="修改《'.$rs['USER_ID'].'》用户权限" class="thickbox">权限设置</a>
        		| <a href="?model=user&action=update_password&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=500" class="thickbox" title="重设 《'.$rs['USER_ID'].'》用户密码">重设密码</a>
        		</td>
       			</tr>
				';
		}
		$pageclass = $this->show_page(array('total'=>$num,'perpage'=>pagenum,'ajax'=>'show_page','page_name'=>'page'));
		$this->_set_url('ajax.php?num='.$num.'&model=user&action=jobs_user_list&jobs_id='.$jobs_id.'&');
		return $str.'<tr><td colspan="7" height="25">'.$this->show(6).'</td></tr>';
	}
	/**
	 * 搜索用户
	 */
	function search_user_list()
	{
		$sotype = $_GET['sotype'] ? $_GET['sotype']: $_POST['sotype'];
		$keyword = $_GET['keyword'] ? $_GET['keyword'] : $_POST['keyword'];
		$num = $_GET['num'] ? $_GET['num'] : $_POST['num'];
		$page = $_GET['page'] ? $_GET['page'] :($_POST['page'] ? $_POST['page'] : 1);
		if ($sotype=='all'){
			$where = "where (a.USER_ID like '%$keyword%' or a.USER_NAME like '%$keyword%')";
		}else{
			$where = "where a.$sotype like '%$keyword%'";
		}
		$start = ($page==1) ? 0 : ($page - 1) * pagenum;
		if (!$num)
		{
			$rs = $this->db->get_one("select count(0) as num from user as a $where");
			$num = $rs['num'];
		}
		if ($num > 0)
		{
                        if($_SESSION['COM_BRN_PT']=='dl'||$_SESSION['COM_BRN_PT']=='bx'){
                            $editlist='edit_func_list';
                        }else{
                            $editlist='edit_func_list_new';
                        }
			$query = $this->db->query("select a.USER_ID,a.LogName,a.USER_NAME,a.SORT,a.HAS_LEFT,a.ready_left,a.jobs_id,b.DEPT_NAME from user as a left join department as b on b.DEPT_ID=a.DEPT_ID $where  order by a.HAS_LEFT,a.CreatDT desc limit $start,".pagenum."");
			while (($rs = $this->db->fetch_array($query))!=false)
			{
				$str .='
				<tr>
        		<td align="center" height="25">'.$rs['SORT'].'</td>
        		<td align="center">'.$rs['LogName'].'</td>
        		<td align="center">'.$rs['USER_NAME'].'</td>
        		<td align="center">'.$rs['DEPT_NAME'].'</td>
        		<td align="center">'.includes_class_jobs::get_job_name($rs['jobs_id']).'</td>
        		<td align="center">';
				if ($rs['HAS_LEFT']=='1')
				{
					$str .='<span style="color: red;">离职</span>';
				}elseif($rs['ready_left']==1){
					$str .='<span style="color: red;">准备离职</span>';
				}else{
					$str .='在职';
				}
				$str .='</td>
				<td align="center">
        		<a href="?model=user&action=edit&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600" title="编辑《'.$rs['USER_ID'].'》用户" class="thickbox">编辑用户</a>
        		| <a href="?model=user&action='.$editlist.'&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" title="修改《'.$rs['USER_ID'].'》用户权限" class="thickbox">权限设置</a>
        		| <a href="?model=user&action=update_password&userid='.$rs['USER_ID'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=500" class="thickbox" title="重设 《'.$rs['USER_ID'].'》用户密码">重设密码</a>
        		</td>
       			</tr>
				';
			}
			$pageclass = $this->show_page(array('total'=>$num,'perpage'=>pagenum,'ajax'=>'show_page','page_name'=>'page'));
			$this->_set_url('num='.$num.'&model=user&action=jobs_user_list&keyword='.rawurlencode($keyword).'&');
			return $str.'<tr><td colspan="7" height="25">'.$this->show(6).'</td></tr>';
		}
	}
	/**
	 * 检查用户ID
	 */
	function check_userid()
	{
		$userid = $_GET['userid'] ? $_GET['userid'] : $_POST['userid'];
		$rs = $this->db->get_one("select USER_ID from user where USER_ID='$userid'");
		if ($rs)
		{
			return $rs['USER_ID'];
		}else{
			return false;
		}
	}
	/**
	 * 取得部门人员排序号
	 */
	function get_user_max()
	{
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$rs = $this->db->get_one("select max(SORT) as num from user where DEPT_ID=$dept_id");
		return $rs['num'] + 1;
	}
	/**
	 * 检查登陆用户名
	 */
	function check_logname()
	{
		$logname =$_GET['logname'] ? $_GET['logname'] : $_POST['logname'];
		$rs = $this->db->get_one("select LogName from user where LogName='$logname'");
		if ($rs)
		{
			return $rs['LogName'];
		}else{
			return false;
		}
	}
	/**
	 * 获取用户ID
	 */
	function get_userid()
	{
		$username = $_GET['username'] ? $_GET['username'] : $_POST['username'];
		if ($username)
		{
			$rs = $this->db->get_one("select * from user where 1 and del=0 and USER_NAME='".$username."'");
			if ($rs)
			{
				return $rs['USER_ID'];
			}else{
				return false;
			}
		}
	}
	/**
	 * 获取姓名
	 */
	function get_username(){
		$userid = $_GET['userid'] ? $_GET['userid'] : $_POST['userid'];
		$rs = $this->db->get_one("select user_name from user where 1 and del=0 and user_id='".$userid."'");
		if ($rs) return $rs['user_name'];
	}
	/**
	 * AJAX获取部门
	 */
	function get_dept()
	{
		$userid = $_GET['userid'] ? $_GET['userid'] : $_POST['userid'];
		if ($userid)
		{
			$rs = $this->db->get_one("select a.dept_id,b.dept_name from user as a
			left join department as b on b.dept_id=a.dept_id where a.USER_ID='".$userid."'");
			if ($rs)
			{
				return $rs['dept_id'].'|'.$rs['dept_name'];
			}else{
				return false;
			}
		}
	}
	/**
	 * 按姓名获取邮件
	 */
	function get_email()
	{
		if ($_POST['username'])
		{
			$rs = $this->db->get_one("select EMAIL from user where has_left=0 and del=0 and USER_NAME='".$_POST['username']."'");
			if ($rs) {
				return trim($rs['EMAIL']);
			}else{
				return false;
			}
		}
	}
	/**
	 * 按部门获取职位多选
	 */
	function get_jobs()
	{
		if ($_POST['departid'])
		{
			$arr = explode(',',$_SESSION['USER_JOBSID']);
			$query = $this->db->query("
				select
					a.id,a.name,b.DEPT_NAME
				from
					user_jobs as a
					left join department as b on b.dept_id=a.dept_id
				where
					a.dept_id='".$_POST['departid']."'");
			while (($rs =$this->db->fetch_array($query))!=false) {
				if (in_array($rs['id'],$arr))
				{
					$str .='<input type="checkbox" checked name="jobs[]" value="'.$rs['id'].'" />'.$rs['name'].'--'.$rs['DEPT_NAME'].'<br />';
				}else{
					$str .='<input type="checkbox" name="jobs[]" value="'.$rs['id'].'" />'.$rs['name'].'--'.$rs['DEPT_NAME'].'<br />';
				}
			}
			return $str;
		}
	}
	/**
	 * 显示用户权限
	 */
	function show_func()
	{
		$userid = $_GET['userid'] ? $_GET['userid'] : $_POST['userid'];
		$ready_left = $_GET['ready_left'] ? $_GET['ready_left'] : $_POST['ready_left'];
		$$ready_left = $ready_left ? true : false;
		$rs = $this->db->get_one("select a.func_id_yes,a.func_id_no,b.func_id_str from user as a left join user_jobs as b on b.id=a.jobs_id where a.user_id='$userid'");
		if ($rs)
		{
			$func_id_yes = $rs['func_id_yes'] ? explode(',',$rs['func_id_yes']) : array();
			$func_id_no = $rs['func_id_no'] ? explode(',',$rs['func_id_no']) : array();
			$func_jobs = $rs['func_id_str'] ? explode(',',$rs['func_id_str']) : array();
			$user_func = array_merge($func_id_yes,$func_jobs);
			//$user_func = array_diff($user_func,$func_id_no);
			$func_str = $user_func ? implode(',',$user_func) : '';
			$func = new model_general_system_user_showfunc();
			return $func->edit_func_list($userid,$func_str,$ready_left);
		}else{
			return false;
		}
	}
	/**
	 * 用户权限控制
	 */
	function show_user_control_func()
	{
		if ($_GET['tid'])
		{
			$pvurl = new model_ajax_pvurl();
			$query = $this->db->query("
				select
					a.* ,b.content
				from
					purview_type as a
					left join purview_info as b on b.typeid=a.id and type=1 and userid='".$_GET['userid']."'
				where a.tid=".$_GET['tid']);
			while (($rs = $this->db->fetch_array($query))!=false)
			{
				$temp = '';
				$_GET['typeid'] = $rs['id'];
				$_GET['type'] = 1;
				$_GET['uid'] = $_GET['userid'];
				if ($rs['content'])
				{
					$pvurl->content = $rs['content'];
				}else{
					$pvurl->get_checked_content();
				}
				$temp = $pvurl->get_list();
				$str .= ($temp ? '<b>'.$rs['name'].'</b><br>'.str_replace('content[]','content['.$_GET['tid'].']['.$rs['id'].'][]',$temp.'<input type="hidden" name="content[]" value="null" />').'<hr>' : '');
			}
			return $str;
		}
	}
	/**
	 * 部门领导
	 */
	function get_loader()
	{
		if ($_POST['user_id'])
		{
			$user_arr = explode(',',$_POST['user_id']);
			$user_id = array();
			foreach ($user_arr as $val)
			{
				$user_id[] = sprintf("'%s'",$val);
			}
			$query = $this->db->query("
										select
											b.MajorId,b.ViceManager
										from
											user as a
											left join department as b on b.dept_id=a.dept_id
										where
											user_id in (".implode(',',$user_id).")

									");
		}elseif ($_POST['jobs_id']){

			$query = $this->db->query("
										select
											b.MajorId,b.ViceManager
										from
											user_jobs as a
											left join department as b on b.dept_id=a.dept_id
										where
											a.id in (".trim($_POST['jobs_id'],',').")
											".($_POST['dept_id'] ? " and a.dept_id in (".trim($_POST['dept_id'],',').")" : '')."
									");
		}elseif ($_POST['area_id']){
			$query = $this->db->query("
										select
											b.MajorId,b.ViceManager
										from
											user as a
											left join department as b on b.dept_id=a.dept_id
										where
											a.area in (".trim($_POST['area_id'],',').")
											".($_POST['dept_id'] ? " and a.dept_id in (".trim($_POST['dept_id'],',').")" : '')."
									");
		}elseif ($_POST['dept_id']){
			$query = $this->db->query("
										select
											MajorId,ViceManager
										from
											department
										where
											dept_id in (".trim($_POST['dept_id'],',').")
									");
		}
		if ($query)
		{
			$loader_id = array();
			while (($rs = $this->db->fetch_array($query))!=false)
			{
				$loader_id = $rs['MajorId'] ? array_merge($loader_id,explode(',',$rs['MajorId'])) : $loader_id;
				$loader_id = $rs['ViceManager'] ? array_merge($loader_id,explode(',',$rs['ViceManager'])) : $loader_id;
			}
			if ($loader_id)
			{
				$gl = new includes_class_global();
				$data = $gl->GetUserName($loader_id);
			}
			return implode('、',$data);
		}else{
			return '所有领导';
		}
	}
	/**
	 * 中文用户名转拼音
	 */
	function get_username_pinyin()
	{
		$username = $_GET['username'] ? $_GET['username'] : $_POST['username'];
		if ($username)
		{
			$len = iconv_strlen($username,'GBK');
			$word = array();
			for ($j = 1;$j<$len;$j++)
			{
				for ($i = 0;$i<$len;$i++)
				{
					$word[] = iconv_substr($username,$i,$j,'GBK');
				}
			}
			if ($word)
			{
				$pinyin = new includes_class_pinyin();
				$name = '';
				for ($i=1;$i<$len;$i++)
				{
					$name .=$word[$i];
				}
				return $pinyin->GetPinyin($name).'.'.$pinyin->GetPinyin($word[0]);
			}
		}
	}


	function user_select()
	{
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
		$area = $_GET['area'] ? $_GET['area'] : $_POST['area'];
		$jobs_id = $_GET['jobs_id'] ? $_GET['jobs_id'] : $_POST['jobs_id'];
		$user_id = $_GET['user_id'] ? $_GET['user_id'] : $_POST['user_id'];
		$user_id = $user_id ? explode(',',$user_id) : null;

		if ($dept_id && !is_array($dept_id))
		{
			$dept_id = explode(',',$dept_id);
		}
		$dept = new model_system_dept();
		$dept_data = $dept->DeptList($dept_id);

		$new_data = array();
		foreach ($dept_data as $key => $rs) {

			if (($temp = $dept->GetParent_ID($rs['DEPT_ID']))!=false)
			{
				asort($temp);
				$new_data = array_merge($new_data,$temp,array($rs['DEPT_NAME']=>$rs['DEPT_ID']));
			}else{
				$new_data = array_merge($new_data,array($rs['DEPT_NAME']=>$rs['DEPT_ID']));
			}
		}
		$new_data = $dept_id ? array_merge($dept_id,$new_data) : $new_data;
		file_put_contents('tty.txt',json_encode($new_data));
		$dept_data = $dept->DeptTree($new_data);

		$dept_id = is_array($dept_id) ? implode(',',$dept_id) : $dept_id;
		$area = is_array($area) ? implode(',',$area) : $area;
		$jobs_id = is_array($jobs_id) ? implode(',',$jobs_id) : $jobs_id;
		$condition = "a.del=0 and has_left=0";
		$condition .= $dept_id ? " and a.dept_id in($dept_id)" : '';
		$condition .= $area ? " and a.area in($area)" : '';
		$condition .= $jobs_id ? " and a.jobs_id in ($jobs_id)" : '';

		$query = $this->db->query("
									select
										a.user_id,a.user_name,a.dept_id,a.area,a.jobs_id,b.parent_id,b.dept_name,c.name as jobs_name
									from
										user as a
										left join department as b on b.dept_id=a.dept_id
										left join user_jobs as c on c.id=a.jobs_id
									where
										$condition
		");
		$data = array();
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			$data[$rs['dept_id']][$rs['jobs_id']][] = $rs;
		}
		$str ='';

		foreach ($dept_data as $rs)
		{

			$parent_data =array();
			$parent_data = $dept->GetParent($rs['DEPT_ID'],null,true);
			$dept_class = '';
			if ($parent_data)
			{
				foreach ($parent_data as $v)
				{
					$dept_class .='dept_'.$v['DEPT_ID'].' ';
				}
			}
			$str .='<option notselect="true" '.($dept_class ? 'class="'.$dept_class.'"' : '').' func="select_jobs('.$rs['DEPT_ID'].',this.checked);" level="'.($rs['level']-1).'" value="'.$rs['DEPT_ID'].'">'.$rs['DEPT_NAME'].'</option>';
			if ($data[$rs['DEPT_ID']])
			{
				$parent_data = $dept->GetParent($rs['DEPT_ID'],null,true);
				$dept_class = '';
				if ($parent_data)
				{
					foreach ($parent_data as $v)
					{
						$dept_class .='dept_'.$v['DEPT_ID'].' ';
					}
				}
				foreach ($data[$rs['DEPT_ID']] as $row)
				{
					$show_jobs = array();
					foreach ($row as $v)
					{
						if (!in_array($v['jobs_id'],$show_jobs))
						{
							$show_jobs[] = $v['jobs_id'];
							$str .='<option notselect="true" class="'.$dept_class.' dept_'.$rs['DEPT_ID'].'" func="select_user('.$v['jobs_id'].',this.checked);" level="'.$rs['level'].'" value="'.$v['jobs_id'].'">'.$v['jobs_name'].'</option>';
						}
						$str .='<option '.($user_id && in_array($v['user_id'],$user_id) ? 'selected="selected"' : '').' class="'.$dept_class.' dept_'.$rs['DEPT_ID'].' jobs_'.$v['jobs_id'].'" level="'.($rs['level']+1).'" value="'.$v['user_id'].'">'.$v['user_name'].'</option>';
					}

				}
			}
		}
		return $str;
	}

	function show_user()
	{
		$user = new controller_user();
		return $user->c_show_user(true);
	}

	function get_jobs_options() {
		$dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
	}
}
?>