<?php
class model_purview
{
	public $db;
	private $page;
	function __construct()
	{
		$this->db = new mysql();
	}
	function getpurview($id)
	{
		$rs = $this->db->get_one("select * from purview where id=$id");
		return $rs;
	}
	/**
	 * 显示权限列表
	 *
	 */
	function showlist()
	{
		@extract($_POST);
		@extract($_GET);
		$page = $page ? $page : 1 ;
		$start = ($page==1) ? 0 : ($page-1) * pagenum;
		$where = ' where 1=1 ';
		if ($menuid)
		{
			$where .= " and a.menuid='$menuid'";
		}
		if ($areapv)
		{
			$where .= ' and a.areapv='.$areapv;
		}
		if ($models)
		{
			$where .= " and a.models='".$models."'";
		}
		if($modelName){ //模糊查询权限名称,模块名称（e.g:stock_safetystock_safetystock）
			$where .= " and (a.name like '%$modelName%' or a.models like '%$modelName%' or p.name like '%$modelName%')";
		}
		if($modelstr){ //模糊查询模块
			$modelArr = split(",",$modelstr);
			$where.=" OR (";
			foreach($modelArr as $key=>$val){
				if($val!=''){
					if($key!=count($modelArr)-2){
						$where.=" a.models='".$val."' or ";
					}else{
						$where.=" a.models='".$val."'";
					}
				}
			}
			$where .= " )";
		}
        $where.=' and a.type=1 ';
		if (!$num)
		{
			$rs = $this->db->get_one("SELECT COUNT(0) AS num FROM (SELECT a.* FROM purview as a LEFT JOIN purview_type AS p ON a.id=p.tid $where GROUP BY a.id) AS TOTAL");
			$num = $rs['num'];
		}
		if ($num > 0)
		{

			$showpage = new includes_class_page();
			$showpage->show_page(array('total'=>$num,'perpage'=>pagenum));
			$showpage->_set_url('num='.$num.'&menuid='.$menuid.'&');
			$query = $this->db->query("select a.*,b.func_name from purview as a left join sys_function as b on b.MENU_ID=a.menuid LEFT JOIN purview_type AS p ON a.id=p.tid $where group by a.id order by a.menuid desc ,a.id desc limit $start,".pagenum."");
			$menu = new includes_class_global();
			while ($rs = $this->db->fetch_array($query))
			{
				if ($rs['control']==1)
				{
					$pv = '是';
				}else{
					$pv = '否';
				}
				$str .='
				<tr>
				<td>'.$rs['id'].'</td>
				<td style="text-align:left;">'.$menu->showmenu($rs['menuid']).'</td>
				<td>&nbsp;'.$rs['name'].'</td>
				<td>&nbsp;'.$rs['models'].'</td>
				<td>&nbsp;'.$rs['func'].'</td>
				<td>'.$pv.'</li>
				<td style="text-align:center;">　<a href="?model=purview&action=edit&id='.$rs['id'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false" class="thickbox" title="修改《'.$rs['name'].'》">修改</a>　｜　<a href="?model=purview&action=showdelinfo&id='.$rs['id'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=150&width=300" class="thickbox" title="删除《'.$rs['name'].'》">删除</a> </a>';
				if ($rs['control']==1)
				{
					$str .='｜ <a href="?model=purview&action=control_list&id='.$rs['id'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false" class="thickbox" title="《'.($rs['type']==1 ? $rs['models'] : $rs['func']).'》的详细控制">详细控制</a>';
				}
				$str .='</td>
				</tr>
				';
			}
			return $str.'<tr><td colspan="7" style="text-align:center;">'.$showpage->show(6).'</td></tr>';
		}
	}
	/**
	 * 显示用户或职位区域列表
	 *
	 * @return string
	 */
	function model_control_list()
	{
		if (intval($_GET['id']))
		{
			$typeid = $this->clean($_GET['typeid']);
			if ($typeid){
				$where = " and a.typeid=$typeid";
			}
            $pv_ini=array();//自带权限脚本数组
            $pv_ini_data=array();
            $sql="select a.pv_ini_sql , a.name from purview_type as a where
				a.tid=".$_GET['id']." and a.pv_ini_sql is not null and a.pv_ini_sql!='' ".$where;
            $query = $this->db->query($sql);
            while ($rs = $this->db->fetch_array($query)){
                $pv_ini[$rs['name']]=$rs['pv_ini_sql'];
            }
            if(!empty($pv_ini)){
                //过滤
                $search ="'1=1.*?group'si";
                $replace = "1=1 group";
                foreach($pv_ini as $key=>$val){
                    if(!empty($val)&&strripos($val,'select')!==false){
                        $sql=preg_replace ($search, $replace, $val);
                        $query = $this->db->query($sql);
                        while ($rs = $this->db->fetch_array($query))
                        {
                            $rs['typename']=$key;
                            $pv_ini_data[]=$rs;
                        }
                    }
                }
            }
            if(!empty($pv_ini_data)){
                foreach($pv_ini_data as $key=>$val){
                    $str .='
					<tr>
						<td>自带权限</td>
						<td>'.$val['typename'].'</td>
						<td>用户</td>
						<td>'.$val['pvobjname'].'</td>
						<td colspan="2">'.$val['pvname'].($val['pvext']?'<br><font color="red">附带权限</font>：'.$val['pvext']:'').'</td>
					</tr>
					';
                }
        	}
        	$sql = "select
					a.*,b.name,b.typeid as btypeid,b.act,c.user_name,d.name as jobs_name,e.dept_name,d.dept_id
				from
					purview_info as a
					left join purview_type as b on b.id=a.typeid
					left join user as c on c.user_id=a.userid
					left join user_jobs as d on d.id=a.jobsid
					left join department as e on e.dept_id=a.deptid
				where 1=1 ";
			if($typeid){
				$sql.=" and b.id=$typeid";
			}
			$sql.=" and a.tid=".$_GET['id']." order by a.id desc";
			$query = $this->db->query($sql);
			while ($rs = $this->db->fetch_array($query))
			{
				$url = '<a href="?model=pvurl&action=';
				$url .= ($rs['btypeid']==1) ? 'show_act' :($rs['btypeid']==2 ? 'show_field' :'index&content='.$rs['content']);
				$url .= '&act='.$rs['act'].'&visit='.$rs['visit'].'&id='.$rs['id'].'&tid='.$rs['tid'].'&type='.$rs['type'].'&typeid='.$rs['typeid'].'&userid='.$rs['userid'].'&username='.$rs['user_name'].'&jobsid='.$rs['jobsid'].'&deptid='.$rs['deptid'].'&dept_id='.$rs['dept_id'];
				$url .='&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=500" class="thickbox" ';
				$url .=' title="修改 '.($rs['type']==1 ? '用户' :($rs['type']==2 ? '职位' : '部门')).' 对象 '.($rs['type']==1 ? $rs['user_name'] : ($rs['type']==2 ? $rs['jobs_name'] : $rs['dept_name'])).' 的 '.$rs['name'].' 设置">修改</a>';
				$url .='| <a href="?model=purview&action=show_delete_pv&id='.$rs['id'].'&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=200" class="thickbox" title="删除该条记录">删除</a>';
				$str .='
				<tr>
					<td>'.$rs['id'].'</td>
					<td>'.$rs['name'].'</td>
					<td>'.($rs['type']==1 ? '用户' :($rs['type']==2 ? '职位' : ($rs['type']==3 ? '部门' : '所有人'))).'</td>
					<td>'.($rs['type']==1 ? $rs['user_name'] : ($rs['type']==2 ? $rs['jobs_name'] : ($rs['type']==3 ? $rs['dept_name'] : '所有人'))).'</td>
					<td>'.$rs['content'].'</td>
					<td>'.$url.'</td>
				</tr>
				';
			}
			return $str;
		}
	}

	function model_control_select($tid)
	{
		if ($tid)
		{
			$typeId = $_GET['typeid'];
			$query = $this->db->query("select * from purview_type where tid=".$tid);
			while ($rs = $this->db->fetch_array($query))
			{
				if($typeId==$rs['id']){
					$str .='<option selected value="'.$rs['typeid'].'|'.$rs['id'].'|'.$rs['act'].'">'.$rs['name'].'</option>';
				}else{
					$str .='<option value="'.$rs['typeid'].'|'.$rs['id'].'|'.$rs['act'].'">'.$rs['name'].'</option>';
				}
			}
			return $str;
		}else{
			return false;
		}
	}

	function model_pv_control($tid)
	{
		$query = $this->db->query("select * from purview_type where tid=".$tid);
		$i=0;
		while ($rs = $this->db->fetch_array($query))
		{
			$act = $rs['typeid'] ? '<input type="text" size="30" id="act_'.$rs['id'].'" name="act['.$rs['id'].']" value="'.$rs['act'].'" />' : '';
			$str .='
			<tr id="tr_'.$rs['id'].'">
					<td >控制名：<input type="text" class="control_name" size="10" name="control_name['.$rs['id'].']" value="'.$rs['name'].'" /></td>
					<td >方式：<select name="typeid['.$rs['id'].']" id="type_'.$rs['id'].'" onchange="show_act_input('.$rs['id'].');">
							<option '.(!$rs['typeid'] ? 'selected' : '').' value="0">是或否</option>
							<option '.($rs['typeid']==1 ? 'selected' : '').' value="1">对应设置函数</option>
							<option '.($rs['typeid']==2 ? 'selected' : '').' value="2">对应表名</option>
						</select>
					</td>
					<td id="td_'.$rs['id'].'">'.$act.'</td>
					<td id="_link_'.$rs['id'].'">'.($rs['typeid']==2 ? '<a href="#" id="link_'.$rs['id'].'" onclick="javascript:show_field('.$rs['id'].',true);">选择字段</a>' : '').'</td>
					<td>自带权限：<textarea id="pv_ini_'.$rs['id'].'" name="pv_ini['.$rs['id'].']">'.$rs['pv_ini_sql'].'</textarea></td>
                                        <td><a href="javascript:del_control('.$rs['id'].');">删除</a></td>
					<div id="field_'.$rs['id'].'" style="display:none;">'.($rs['typeid']==2 ? $this->get_table_checkbox($rs['act'],$rs['id'],$rs['field']).'<p width="100%" style="text-align:center;"><input type="button" onclick="tb_remove()" value=" 确定 " /></p>' : '').'</div>
				</tr>
			';
		}
		return $str;
	}
	/**
	 * 模块下拉
	 *
	 */
	function model_select_models()
	{
		$query = $this->db->query("select p.models , p.name , f.func_name ,fp.func_name as fpn from purview p
                                            left join sys_function f on (p.menuid=f.menu_id)
											left join sys_function fp on (fp.menu_id=left(f.menu_id,4))
                                          where p.models!='' order by fp.func_name ,f.func_name , p.name ");
		while ($rs = $this->db->fetch_array($query))
		{
			if ($_GET['models']==$rs['models'])
			{
				$str .='<option selected value="'.$rs['models'].'">'.$rs['fpn'].'->'.$rs['func_name'].'->'.$rs['name'].'</option>';
			}else{
				$str .='<option value="'.$rs['models'].'">'.$rs['fpn'].'->'.$rs['func_name'].'->'.$rs['name'].'</option>';
			}
		}
		return $str;
	}
	/**
	 * 显示区域
	 *
	 * @param unknown_type $areaid
	 * @return unknown
	 */
	function show_area($areaid)
	{
		$query = $this->db->query("select * from area where id in($areaid)");
		while ($rs = $this->db->fetch_array($query))
		{
			$str .=$rs['Name'].',';
		}
		return rtrim($str,',');
	}
	function model_add_pv()
	{
		if (intval($_GET['tid']) && intval($_GET['typeid']))
		{
			return $this->db->query("
			insert into purview_info
				(tid,typeid,visit,type,userid,jobsid,deptid,content,date)
			values
				(
					'".$_GET['tid']."',
					'".$_GET['typeid']."',
					'".$_POST['visit']."',
					'".$_POST['type']."',
					'".($_POST['type']==1 ? $_POST['userid'] : null)."',
					'".($_POST['type']==2 ? $_POST['jobsid'] : null)."',
					'".($_POST['type']==3 ? $_POST['dept_id'] : null)."',
					'".(is_array($_POST['content']) ? implode(',',$_POST['content']) : trim($_POST['content']))."',
					'".time()."'
					)
			");
		}
	}
	function model_seve_edit_pv()
	{
		if (intval($_GET['id']) && intval($_GET['typeid']))
		{
			return $this->db->query("
				update
					purview_info
				set
					visit='".$_POST['visit']."',
					type='".$_POST['type']."',
					userid = '".($_POST['type']==1 ? $_POST['userid'] : null)."',
					jobsid = '".($_POST['type']==2 ? $_POST['jobsid'] : null)."',
					deptid = '".($_POST['type']==3 ? $_POST['dept_id'] : null)."',
					content = '".(is_array($_POST['content']) ? implode(',',$_POST['content']) : trim($_POST['content']))."'
				where
					id='".$_GET['id']."'

			");
		}else{
			showmsg('非法参数！');
		}
	}
	/**
	 * 删除
	 *
	 */
	function model_delete_pv()
	{
		if (intval($_GET['id']))
		{
			return $this->db->query("delete from purview_info where id=".intval($_GET['id']));
		}
	}
	/**
	 * AJAX检查用户是否存在
	 *
	 * @return unknown
	 */
	function check_user()
	{
		if ($_POST['userid'] && $_POST['tid'])
		{
			$rs = $this->db->get_one("select id from purview_info where tid='".$_POST['tid']."' and typeid='".$_POST['typeid']."' and userid='".$_POST['userid']."'");
			if ($rs)
			{
				return $rs['id'];
			}else{
				return false;
			}
		}
	}
	/**
	 * AJAX检查职位是否存在
	 *
	 * @return unknown
	 */
	function check_jobs()
	{
		if ($_POST['jobsid'] && $_POST['tid'])
		{
			$rs = $this->db->get_one("select id from purview_info where tid='".$_POST['tid']."' and typeid='".$_POST['typeid']."' and jobsid='".$_POST['jobsid']."'");
			if ($rs)
			{
				return $rs['id'];
			}else{
				return false;
			}
		}
	}
	/**
	 * AJAX检查部门是否存在
	 *
	 * @return unknown
	 */
	function check_dept()
	{
		if ($_POST['dept_id'] && $_POST['tid'])
		{
			$rs = $this->db->get_one("select id from purview_info where tid='".$_POST['tid']."' and typeid='".$_POST['typeid']."' and deptid='".$_POST['dept_id']."'");
			if ($rs)
			{
				return $rs['id'];
			}else{
				return false;
			}
		}
	}

	function show_menu_model()
	{
		if (intval($_POST['menuid']))
		{
			$query = $this->db->query("
				select
					 *
				from
					purview
				where
					(menuid='".substr($_POST['menuid'],0,6)."' or menuid='".substr($_POST['menuid'],0,4)."' or menuid='".substr($_POST['menuid'],0,2)."') and type=1");
			while ($rs = $this->db->fetch_array($query))
			{
				if ($rs['models']==$_POST['models'])
				{
					$str .='<option selected value="'.$rs['models'].'">'.$rs['models'].'</optoon>';
				}else{
					$str .='<option value="'.$rs['models'].'">'.$rs['models'].'</optoon>';
				}
			}
			return $str ? $str : '<option value="">该栏目尚未添加模块</option>';
		}
	}
	/**
	 * 获取表字段并用checkbox返回
	 *
	 */
	function get_table_checkbox($table=null,$id=null,$field=null)
	{
		$_table = $table ? $table : $_POST['table'];
		$_id = $id ? $id : $_POST['id'];
		$arr = ($field ? explode(',',$field) : false);
		if ($_table && $_id)
		{
			$columns = $this->db->getTable($_table);
			if ($columns)
			{
				foreach ($columns as $val)
				{
					if ($arr && in_array($val['Field'],$arr))
					{
						$str .='<input type="checkbox" checked name="field['.$_id.'][]" value="'.$val['Field'].'" />'.$val['Field'].'<br />';
					}else{
						$str .='<input type="checkbox" name="field['.$_id.'][]" value="'.$val['Field'].'" />'.$val['Field'].'<br />';
					}
				}
				return $str;
			}else{
				return -1;
			}
		}else{
			return -2;
		}
	}
	/**
	 * 添加权限
	 *
	 */
	function insert()
	{
		@extract($_POST);
		@extract($_GET);
		if ($_POST)
		{
			$model_name = ($type=='0') ? $modelid: $models;
			$this->db->query("insert into purview(name,menuid,models,func,control,type)values('$name','$menuid','$model_name','$func','$control','$type')");
			if ($control==1)
			{
				$tid = $this->db->insert_id();
				if ($_POST['control_name'])
				{
					foreach ($_POST['control_name'] as $key=>$val)
					{
						if ($val)
						{
							$this->db->query("
							insert into purview_type
								(tid,name,typeid,act,field,pv_ini_sql)
							values
								(
									'$tid',
									'$val',
									'".$_POST['typeid'][$key]."',
									'".$_POST['act'][$key]."',
									'".($_POST['field'][$key] ? implode(',',$_POST['field'][$key]) : null)."'
                                                                        ,'".mysql_real_escape_string($_REQUEST['pv_ini'][$key])."'
									)
								");
						}
					}
				}
			}
			return 1;
		}else{
			return false;
		}
	}
	/**
	 * 更新权限
	 *
	 */
	function model_update()
	{
		@extract($_POST);
		@extract($_GET);
		if (intval($id))
		{
			$this->db->query("update purview set
			name='$name',
			menuid='$menuid',
			models='".($type==1 ? $models : $modelid)."',
			func='$func',
			control='$control',
			type='$type'
			where id='$id'
			");
			if ($control==1)
			{
				$query = $this->db->query("select id,act from purview_type where tid=".$id);
				$field = array();
				while ($rs = $this->db->fetch_array($query))
				{
					$field[$rs['id']] = $rs['act'] ? $rs['act'] : 1;
				}
				if ($_POST['control_name'])
				{
					foreach ($_POST['control_name'] as $key=>$val)
					{
						if ($field[$key])
						{
							unset($field[$key]);
							if ($_POST['typeid'][$key]==1)
							{
								$this->db->query("
									update
										purview_type
									set
										name='".$_POST['control_name'][$key]."',
										typeid=1,
										act='".$_POST['act'][$key]."'
                                                                                ,pv_ini_sql='".mysql_real_escape_string($_REQUEST['pv_ini'][$key])."'
									where
										id=$key
										");
							}elseif ($_POST['typeid'][$key]==2){
								$this->db->query("
									update
										purview_type
									set
										name='".$_POST['control_name'][$key]."',
										typeid=2,
										act='".$_POST['act'][$key]."',
										field='".($_POST['field'][$key] ? implode(',',$_POST['field'][$key]) : null)."'
                                                                                ,pv_ini_sql='".mysql_real_escape_string($_REQUEST['pv_ini'][$key])."'
									where
										id=$key
										");
							}else{
								$this->db->query("
									update
										purview_type
									set
										name='".$_POST['control_name'][$key]."',
										typeid=0,
										act=null
                                                                                ,pv_ini_sql='".mysql_real_escape_string($_REQUEST['pv_ini'][$key])."'
									where
										id=$key
										");
							}
						}else{
							if ($_POST['typeid'][$key]==1)
							{
								$this->db->query("
								insert into purview_type
									(tid,name,typeid,act,pv_ini_sql)
								values
									('$id','".$_POST['control_name'][$key]."','1','".$_POST['act'][$key]."','".mysql_real_escape_string($_REQUEST['pv_ini'][$key])."')
								");
							}elseif ($_POST['typeid'][$key]==2){
								$this->db->query("
								insert into purview_type
									(tid,name,typeid,act,field,pv_ini_sql)
								values
									(
										'$id',
										'".$_POST['control_name'][$key]."',
										'2',
										'".$_POST['act'][$key]."',
										'".($_POST['field'][$key] ? implode(',',$_POST['field'][$key]) : null)."'
                                                                                    ,'".mysql_real_escape_string($_REQUEST['pv_ini'][$key])."'
										)
								");
							}else{
								$this->db->query("
								insert into purview_type
									(tid,name,typeid,pv_ini_sql)
								values
									('$id','".$_POST['control_name'][$key]."','0','".mysql_real_escape_string($_REQUEST['pv_ini'][$key])."')
								");
							}
						}
					}
				}
				if ($field)
				{
					foreach ($field as $k=>$v)
					{
						$this->db->query("delete from purview_type where id=".$k);
					}
				}
			}
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 删除权限
	 *
	 */
	function delete()
	{
		if (intval($_GET['id']))
		{
			$this->db->query("delete from purview where id=".$_GET['id']);
			return true;
		}else{
			return false;
		}
	}
	//================================================
	/**
	 * 用户列表
	 */
	function model_audit_user()
	{
		$page = $_GET['page'] ? $_GET['page'] : 1;
		$num = $_GET['num'] ? $_GET['num'] : false;
		if (!empty($_GET['type']))
		{
			$where = "where type=".$_GET['type'];
		}
		if (!$num)
		{
			$rs = $this->db->get_one("select count(0) as num from purview_audit_apply_user $where");
			$num = $rs['num'];
		}
		$sratr = ($page==1) ? 0 : ($page-1) * pagenum;
		if ($num > 0)
		{
			$query = $this->db->query("
				select
					a.*,b.user_name,c.dept_name
				from
				purview_audit_apply_user as a
				left join user as b on b.user_id=a.userid
				left join department as c on c.dept_id=b.dept_id
				$where
				order by id desc
				limit $sratr,".pagenum);
			while ($rs = $this->db->fetch_array($query))
			{
				$str .='
				<tr id="tr_'.$rs['id'].'">
					<td>'.$rs['id'].'</td>
					<td id="username_'.$rs['id'].'">'.$rs['user_name'].'</td>
					<td id="dept_'.$rs['id'].'">'.$rs['dept_name'].'</td>
					<td id="type_'.$rs['id'].'">'.($rs['type']==1 ? '审核权限' :'申请权限').'</td>
					<td id="edit_'.$rs['id'].'"><a href="?model=purview&action=show_purview_list&id='.$rs['id'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" class="thickbox" title="编辑《'.$rs['user_name'].'》'.($rs['type']==1 ? '审核权限' :'申请权限').'"" >设置权限</a> | <!--<a href="javascript:edit('.$rs['id'].')">修改</a> |--> <a href="javascript:del('.$rs['id'].');">删除</a></td>
				</tr>
				';
			}
			return $str;
		}else{
			return false;
		}
	}
	function model_show_purview_list()
	{
		if (intval($_GET['id']))
		{
			$func_id_str = array();
			$type = 0;
			$rs = $this->db->get_one("select func_id_str,type from purview_audit_apply_user where id=".$_GET['id']);
			if ($rs)
			{
				$type = $rs['type'];
				$func_id_str = explode(',',$rs['func_id_str']);

				if ($rs['type']==1)
				{
					$query = $this->db->query("select func_id_str from purview_audit_apply_user where type=1 and id!=".$_GET['id']);
					while ($row = $this->db->fetch_array($query))
					{
						$apply_func_id_str = $row['func_id_str'].',';
					}
					$apply_func_id_arr = explode(',',$apply_func_id_str);
					$apply_func_id_arr = array_unique($apply_func_id_arr);
				}
			}
			$query=$this->db->query("select left(menuid,2) as m1 , left(menuid,4) as m2  from purview");
			while($rs = $this->db->fetch_array($query)){
				$menuSql .= $rs["m1"].',';
				$funcSql .= $rs['m2'].',';
			}
			$menuSql = rtrim($menuSql,',');
			$funcSql = rtrim($funcSql,',');
			$str .='<ul><div class="submit"><input type="submit" value=" 保 存 " /> <input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " /></div></ul>';
			$query = $this->db->query("select * from sys_menu where MENU_ID in ($menuSql)  order by taxis_id asc");
			while ($rs = $this->db->fetch_array($query))
			{
				$str .='
			<ul id="title_'.$rs['MENU_ID'].'">
			<div class="title"><input type="checkbox" onclick="check(\''.$rs['MENU_ID'].'\')" />'.$rs['MENU_NAME'].'</div>';
				$sql = $this->db->query("select * from sys_function where MENU_ID like '".$rs["MENU_ID"]."%' and length(MENU_ID)=4 and menu_id in ($funcSql)  order by taxis_id ASC");
				$i=0;
				$num = $this->db->num_rows($sql);
				while ($row = $this->db->fetch_array($sql))
				{
					$i++;
					if ($i==1) $str .='<div class="menulist">';
					$str .='<li id="title_'.$row['MENU_ID'].'"><input type="checkbox" onclick="check(\''.$row['MENU_ID'].'\')" name="id[]" value="_'.$row['MENU_ID'].'" '.($type==1 ? ($this->disabled('_'.$row['MENU_ID'],$apply_func_id_arr,$type)) :'').' '.$this->checked($func_id_str,'_'.$row['MENU_ID']).'/><b>'.$row['FUNC_NAME'].'</b>';
					$psql = $this->db->query("select * from purview where menuid='".$row['MENU_ID']."'");
					while ($ra = $this->db->fetch_array($psql))
					{
						if (trim($ra['name'])!='访问')
						{
							$str .='<ul><li><input type="checkbox" name="id[]" '.($type==1 ? ($this->disabled($ra['id'],$apply_func_id_arr,$type)) :'').' '.$this->checked($func_id_str,$ra['id']).' value="'.$ra['id'].'">'.$ra['name'].' '.($ra['control'] ? '详细权限':'').'</li></ul>';
						}
					}
					$mysql = $this->db->query( "select * from sys_function where MENU_ID like '".$row["MENU_ID"]."%' and length(MENU_ID)>4 $sqlPower order by taxis_id ASC ");
					while ($rss = $this->db->fetch_array($mysql))
					{
						$str .='<ul id="title_'.$rss['MENU_ID'].'"><li><input type="checkbox" onclick="check(\''.$rss['MENU_ID'].'\')" name="id[]" value="_'.$rss['MENU_ID'].'" '.($type ? ($this->disabled('_'.$rss['MENU_ID'],$apply_func_id_arr,$type)) :'').' '.$this->checked($func_id_str,'_'.$rss['MENU_ID']).'/><span style="color:#0099FF;">'.$rss['FUNC_NAME'].'</span>';
						$msql = $this->db->query("select * from purview where menuid='".$rss['MENU_ID']."'");
						while ($rr = $this->db->fetch_array($msql))
						{
							if (trim($rr['name'])!='访问')
							{
								$str .='<ul><li><input type="checkbox" name="id[]" '.($type==1 ? ($this->disabled($rr['id'],$apply_func_id_arr,$type)) :'').' '.$this->checked($func_id_str,$rr['id']).' value="'.$rr['id'].'">'.$rr['name'].' '.($ra['control'] ? '详细权限':'').'</li></ul>';
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
			$str .='<ul><div class="submit"><input type="submit" value=" 保 存 " /> <input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " /></div></ul>';
			return $str;
		}
	}
	/**
	 * 设置被选中
	 *
	 * @param string $str
	 * @param arrray $func_id_array
	 * @return string
	 */
	function checked($func_id_str,$menuid,$type='')
	{
		if ($type=='audit')
		{
			if (!in_array($menuid,$func_id_str)) return 'disabled';
		}
		if (in_array($menuid,$func_id_str))
		{
			if ($type=='audit')
			{
				return 'checked onclick="this.checked=true;"';
			}else{
				return 'checked';
			}
		}else{
			return '';
		}
	}

	function return_tag($menuid,$audit_func_id_str)
	{
		if ($menuid && $audit_func_id_str)
		{
			if (in_array($menuid,$audit_func_id_str))
			{
				return '<span>?</span>';
			}else{
				return '';
			}
		}
	}
	/**
	 * 关闭可选
	 *
	 * @param unknown_type $str
	 * @param unknown_type $array
	 * @param unknown_type $type
	 * @return unknown
	 */
	function disabled($str,$array,$type=1)
	{
		if ($type==1)
		{
			if (in_array($str,$array))
			{
				return 'disabled';
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	/**
	 * 添加用户
	 *
	 */
	function model_save_apply_audit_user()
	{
		if ($_POST['userid'] && isset($_POST['type']))
		{
			return $this->db->query("
				insert into purview_audit_apply_user
					(userid,type,date)
				values
					('".$_POST['userid']."','".$_POST['type']."','".time()."')
				");
		}else{
			return false;
		}
	}
	/**
	 * 修改用户
	 */
	function edit_user()
	{
		if ($_POST['id'])
		{
			$rs = $this->db->get_one("select id from purview_audit_apply_user where id=".$_POST['id']);
			if ($rs)
			{
				if ($this->db->query("
					update
						purview_audit_apply_user
					set
						userid='".$_POST['userid']."',
						type='".$_POST['typeid']."'
						where id=".$_POST['id']."
						"))
				{
					return 1;
				}
			}
		}
	}
	/**
	 * 删除用户
	 */
	function del_user()
	{
		if (intval($_POST['id']))
		{
			if ($this->db->query("delete from purview_audit_apply_user where id=".$_POST['id']))
			{
				return 1;
			}else{
				return 0;
			}
		}
	}
	/**
	 * 设置用户审核或申请权限
	 *
	 */
	function model_set_user_audit_apply_purivew()
	{
		if (intval($_GET['tid']))
		{
			if ($_POST['id'])
			{
				$func_id_str = implode(',',$_POST['id']);
				return $this->db->query("update purview_audit_apply_user set func_id_str='$func_id_str' where id=".$_GET['tid']);
			}else{
				return $this->db->query("update purview_audit_apply_user set func_id_str='' where id=".$_GET['tid']);
			}
		}else{
			showmsg('非法参数！');
		}
	}
	//========================================================
	/**
	 * 权限申请列表
	 */
	function model_apply_list()
	{
		$page = $_GET['page'] ? $_GET['page'] : 1;
		$num = $_GET['num'] ? $_GET['num'] : false;
		if ($_GET['status']=='wait')
		{//等待
			$term = " and a.status='0'";
		}elseif ($_GET['status']=='ok')
		{//待处理
			$term = " and a.status='1'";
		}elseif ($_GET['status']=='return')
		{//被退回
			$term = " and a.status='-1'";
		}elseif ($_GET['status']=='treat')
		{//已处理
			$term = " and a.status='2'";
		}
		if (!$num)
		{
			$rs = $this->db->get_one("select count(0) as num from purview_apply as a where a.apply_userid='".$_SESSION['USER_ID']."' $term");
			$num = $rs['num'];
		}
		if ($num > 0)
		{
			$start = ($page==1) ? 0 : ($page-1) * pagenum;
			$query = $this->db->query("
					select
					a.*,b.user_name,c.user_name as apply_user,c.user_name as audit_user
					from
					purview_apply as a
					left join user as b on b.user_id=a.userid
					left join user as c on c.user_id=a.audit_userid
					where apply_userid='".$_SESSION['USER_ID']."' $term
					order by id desc,status
					limit $start,".pagenum."
					");
			while ($rs = $this->db->fetch_array($query))
			{
				$str .='
				<tr>
				<td>'.$rs['id'].'</td>
				<td>'.$rs['user_name'].'</td>
				<td>'.($rs['status']==-1 ? '<span>被退回</span>':($rs['status']==1 ? '<span class="purple">待处理</span>' :($rs['status']==2 ? '<span class="green">申请成功</span>':'待审核'))).'</td>
				<td>'.implode('、',$this->get_username(explode(',',$rs['audit_userid']))).'</td>
				<td>'.$this->get_username_list($rs['audited']).'</td>
				<td>'.$this->get_username_list($rs['return_userid']).'</td>
				<td>'.($rs['audit'] ? date('Y-m-d',$rs['audit_date']) : '').'</td>
				<td>'.date('Y-m-d',$rs['date']).'</td>
				<td>'.($rs['status']==-1 ? '<a href="?model=purview&action=edit_apply&userid='.$rs['userid'].'&username='.$rs['user_name'].'&id='.$rs['id'].'&placeValuesBefore&TB_iframe=true&modal=false&width=1000&height=600" class="thickbox" title="修改 '.$rs['user_name'].' 的权限申请">修改</a>' :($rs['status']==1 ? '<a href="?model=purview&action=undo_apply&id='.$rs['id'].'&username='.$rs['user_name'].'&placeValuesBefore&TB_iframe=true&modal=false&width=300&height=200" class="thickbox" title="撤消 '.$rs['user_name'].' 的申请">撤消申请</a>':'<a href="?model=purview&action=show_apply_func&id='.$rs['id'].'&userid='.$rs['userid'].'&placeValuesBefore&TB_iframe=true&modal=false&width=1000&height=600" class="thickbox" title="查看 '.$rs['user_name'].' 申请的权限">查看</a>')).'</td>
				</tr>
					';
			}
			$showpage = new includes_class_page();
			$showpage->show_page(array('total'=>$num,'perpage'=>pagenum));
			$showpage->_set_url('num='.$num.'&status='.$_GET['status'].'&');
			return $str.'<tr><td colspan="8">'.$showpage->show(6).'</td></tr>';
		}
	}
	function get_username($userid)
	{
		if (!$userid) return false;
		$arr = array();
		if (is_array($userid))
		{
			foreach ($userid as $val)
			{
				$arr[] =sprintf("'%s'",$val);
			}
		}else{
			$arr[] = "'$userid'";
		}
		$query = $this->db->query("select user_id,user_name from user where user_id in (".implode(',',$arr).")");
		$data = array();
		while (($rs = $this->db->fetch_array($query))!=false)
		{
			$data[$rs['user_id']] = $rs['user_name'];
		}
		return $data;
	}
	function model_show_func($type='add')
	{
		if ($type=='add' || $type=='edit' || $type=='show_apply')
		{
			$rs = $this->db->get_one("
				select func_id_str from purview_audit_apply_user where userid='".$_SESSION['USER_ID']."' and type=0
			");
		}
		if ($type=='audit' || $type=='show_audit')
		{
			$rs = $this->db->get_one("
				select func_id_str from purview_audit_apply_user where userid='".$_SESSION['USER_ID']."' and type=1
			");
		}elseif ($type=='treat')
		{
			$rs = $this->db->get_one("select func_id_str_yes from purview_apply where id=".$_GET['id']);
		}
		//=====================
		if (($type=='edit' || $type=='show_apply') && intval($_GET['id'])){
			$row = $this->db->get_one("
					select func_id_str_yes,func_id_str_no,return_userid from purview_apply where apply_userid='".$_SESSION['USER_ID']."' and id=".$_GET['id']."
				");
			if ($row['return_userid'])
			{
				$arr = explode(',',$row['return_userid']);
				$where = '';
				foreach ($arr as $v)
				{
					$where .=($where ? " or userid like '%".$v."%'" :"where userid like '%".$v."%'");
				}
				$query = $this->db->query("select func_id_str from purview_audit_apply_user $where");
				$audit_func_id_str = '';
				while ($rp = $this->db->fetch_array($query))
				{
					$audit_func_id_str = $rp['func_id_str'].',';
				}
				$audit_func_id_str = explode(',',rtrim($audit_func_id_str,','));
			}
		}
		if (($type=='audit' || $type=='show_audit') && intval($_GET['id'])){
			$row = $this->db->get_one("
				select func_id_str_yes,func_id_str_no from purview_apply where id=".$_GET['id']."
				");
		}
		if ($rs)
		{
			if ($row)
			{
				if ($type=='audit' || $type=='show_audit')
				{
					$func_id_arr = explode(',',$rs['func_id_str']);
					$apply_func_id_arr = explode(',',$row['func_id_str_no'].','.$row['func_id_str_yes']);
					foreach ($func_id_arr as $val)
					{
						if (in_array($val,$apply_func_id_arr))
						{
							$apply_func_id_str[] = $val;
						}
					}
				}else{
					$apply_func_id_str =explode(',',$row['func_id_str_yes'].($row['func_id_str_no'] ? ','.$row['func_id_str_no']:''));
				}
			}
			if ($type=='treat')
			{
				$arr = explode(',',$rs['func_id_str_yes']);
				$apply_func_id_str = $arr;
			}else{
				$arr = explode(',',$rs['func_id_str']);
			}

			$menu_id_arr = array();
			$priv_id_arr = array();
			foreach ($arr as $val)
			{
				if (strpos($val,'_')!==false)
				{
					$menu_id_arr[] = str_replace('_','',$val);
				}else {
					$priv_id_arr[] = $val;
				}
			}
			if ($type=='add' && !$menu_id_arr)
			{
				showmsg('您没有任何权限！');
			}
			if($priv_id_arr){
				$priv_id_arr = array_unique($priv_id_arr);
				$priv_id_arr=implode(',',$priv_id_arr);
				$query = $this->db->query("select menuid  from purview where id in ($priv_id_arr) ");
				while ($rs = $this->db->fetch_array($query)){
					$menu_id_arr[]=$rs['menuid'];
				}
			}
			$menu_id_arr = array_unique($menu_id_arr);
			$user_func_id_str = implode(',',$menu_id_arr);
			$query = $this->db->query("
					select
						m.MENU_NAME,m.MENU_ID as sm, f.MENU_ID as fm ,  f.func_name as fn
					from
						sys_function as f
						left join sys_menu as m on m.MENU_ID=left(f.MENU_ID,2)
						where f.MENU_ID in (".trim($user_func_id_str,",").")
						order by f.taxis_id
					");
			$menu = array();
			$func = array();
			while ($rs = $this->db->fetch_array($query))
			{
				$menu[$rs['sm']] = $rs['MENU_NAME'];
				$func[$rs['fm']] = $rs['fn'];
			}
			foreach ($menu as $key=>$val)
			{
				$str .='<ul id="title_'.$key.'"><div class="title"><input type="checkbox" onclick="check(\''.$key.'\')" />'.$val.'</div>';
				$i= 0;
				foreach ($func as $k=>$v)
				{
					$checked_pv = '';
					if ((strlen($k)==4) && (substr($k,0,2)==$key))
					{
						$checked_pv = ($apply_func_id_str ? $this->checked($apply_func_id_str,'_'.$k,$type):'');
						$i++;
						if ($i==1) $str .='<div class="menulist">';
						$str .='<li id="title_'.$k.'"><input type="checkbox" '.$checked_pv.' onclick="check(\''.$k.'\')" name="id[]" value="_'.$k.'"/><b>'.($checked_pv ? $this->return_tag('_'.$k,$audit_func_id_str):'').$v.'</b>';
						$psql = $this->db->query("select * from purview where menuid='".$k."'");
						while ($ra = $this->db->fetch_array($psql))
						{
							$checked_pv='';
							if (trim($ra['name'])!='访问')
							{
								$checked_pv = ($apply_func_id_str ? $this->checked($apply_func_id_str,$ra['id'],$type):'');
								$str .='<ul><li><input type="checkbox" '.$checked_pv.' name="id[]" value="'.$ra['id'].'">'.($checked_pv ? $this->return_tag($ra['id'],$audit_func_id_str) : '').$ra['name'].'</li></ul>';
							}
						}
						foreach ($func as $j=>$s)
						{
							$checked_pv = '';
							if ((strlen($j)==6) && (substr($j,0,4)==$k))
							{
								$checked_pv = ($apply_func_id_str ? $this->checked($apply_func_id_str,'_'.$j,$type):'');
								$str .='<ul id="title_'.$j.'"><li><input type="checkbox" '.$checked_pv.' onclick="check(\''.$j.'\')" name="id[]" value="_'.$j.'" /><span style="color:#0099FF;">'.($checked_pv ? $this->return_tag('_'.$j,$audit_func_id_str):'').$s.'</span>';
								$msql = $this->db->query("select * from purview where menuid='".$j."'");
								while ($rr = $this->db->fetch_array($msql))
								{
									$checked_pv = '';
									if (trim($rr['name'])!='访问')
									{
										$checked_pv = ($apply_func_id_str ? $this->checked($apply_func_id_str,$rr['id'],$type):'');
										$str .='<ul><li><input type="checkbox" '.$checked_pv.' name="id[]" value="'.$rr['id'].'">'.($checked_pv ? $this->return_tag($rr['id'],$audit_func_id_str):'').$rr['name'].'</li></ul>';
									}
								}
								$str .='</li></ul>';
							}
						}
						$str .='</li>';
						if ($i%6==0) $str .='</div>';
						if ($i%6==0 && $i!=$num) $str .='<div class="menulist">';
					}
				}
				$str .='</ul>';
			}
			if ($type=='audit' || $type=='treat')
			{
				$str .='
					<ul>
						<div class="submit">
							<input type="submit" name="submit" value=" 通过申请 " />
							<input type="submit" name="submit" value=" 退回申请 " />
							<input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " />
						</div>
					</ul>';
			}elseif ($type=='show_apply' || $type=='show_audit'){
				$str .='
			<ul>
				<div class="submit">
					<input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " />
				</div>
			</ul>
			';
			}else{
				$str .='<ul><div class="submit"><input type="submit" value=" 保 存 " /> <input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " /></div></ul>';
			}
			return $str;
		}
	}

	function model_save_apply_user_purview()
	{
		if ($_POST['userid'])
		{
			if ($_POST['id'])
			{
				$user_arr = array();
				$func_id_str_yes = array();
				$func_id_str_no = array();
				foreach ($_POST['id'] as $val)
				{
					$rs = $this->db->get_one("select id,userid from purview_audit_apply_user where type=1 and find_in_set('$val',func_id_str)");
					if ($rs)
					{
						$func_id_str_no[] = $val;
						$user_arr[$val] = $rs['userid'];
					}else{
						$func_id_str_yes[] = $val;
					}
				}
				$audit_user_str = ($user_arr ? implode(',',array_unique($user_arr)):'');
				return $this->db->query("
					insert into purview_apply
						(userid,func_id_str_yes,func_id_str_no,audit_userid,apply_userid,description,date)
					values
						('".$_POST['userid']."',
						'".($func_id_str_yes ? implode(',',$func_id_str_yes):'')."',
						'".($func_id_str_no ? implode(',',$func_id_str_no):'')."',
						'$audit_user_str',
						'".$_SESSION['USER_ID']."',
						'".$_POST['description']."',
						'".time()."')
					");
			}else{
				showmsg('请权限权限！');
			}
		}
	}

	function model_save_edit_apply()
	{
		if (intval($_GET['tid']))
		{
			if ($_POST['id'])
			{
				$user_arr = array();
				$func_id_str_yes = array();
				$func_id_str_no = array();
				foreach ($_POST['id'] as $val)
				{
					$rs = $this->db->get_one("select id,userid from purview_audit_apply_user where type=1 and find_in_set('$val',func_id_str)");
					if ($rs)
					{
						$func_id_str_no[] = $val;
						$user_arr[$val] = $rs['userid'];
					}else{
						$func_id_str_yes[] = $val;
					}
				}
				$audit_user_str = ($user_arr ? implode(',',array_unique($user_arr)):'');
				$func_id_str = implode(',',$_POST['id']);
				return $this->db->query("
						update
							purview_apply
						set
						userid='".$_POST['userid']."',
						func_id_str_yes = '".($func_id_str_yes ? implode(',',$func_id_str_yes):'')."',
						func_id_str_no = '".($func_id_str_no ? implode(',',$func_id_str_no):'')."',
						audit_userid = '$audit_user_str',
						description='".$_POST['description']."',
						audited='',
						return_userid='',
						audit_date='',
						status='0',
						notse=''
						where apply_userid='".$_SESSION['USER_ID']."' and id=".$_GET['tid']."
						");
			}
		}
	}

	function model_audit_list()
	{
		$page = $_GET['page'] ? $_GET['page'] : 1;
		$num = $_GET['num'] ? $_GET['num'] : false;
		$start = ($page==1) ? 0 : ($page-1) * pagenum;
		if (!$num)
		{
			$rs = $this->db->get_one("select count(0) as num from purview_apply where find_in_set('".$_SESSION['USER_ID']."',audit_userid)");
			$num = $rs['num'];
		}
		if ($num > 0)
		{
			$query = $this->db->query("
				select
					a.*,b.user_name,c.user_name as apply_user,d.func_id_str
				from
					purview_apply as a
					left join user as b on b.user_id=a.userid
					left join user as c on c.user_id=a.apply_userid
					left join purview_audit_apply_user as d on d.userid='".$_SESSION['USER_ID']."' and type=1
					where  find_in_set('".$_SESSION['USER_ID']."',audit_userid)
					order by id desc
					limit $start,".pagenum."
				");
			while ($rs = $this->db->fetch_array($query))
			{
				$status = 0;
				if ($rs['audited'])
				{
					if (strpos($rs['audited'],$_SESSION['USER_ID'])!==false)
					{
						$status = 1;
					}elseif ($rs['return_userid']){
						if (strpos($rs['return_userid'],$_SESSION['USER_ID'])!==false)
						{
							$status = 2;
						}
					}
				}elseif ($rs['return_userid']){
					if (strpos($rs['return_userid'],$_SESSION['USER_ID'])!==false)
					{
						$status = 2;
					}
				}
				$str .='
					<tr>
						<td>'.$rs['id'].'</td>
						<td>'.$rs['user_name'].'</td>
						<td>'.date('Y-m-d',$rs['date']).'</td>
						<td>'.$rs['apply_user'].'</td>
						<td>'.$rs['description'].'</td>
						<td>'.($status == 0 ? '<span>待审核</span>' : ($status==1 ? '已审核':'已退回')).'</td>
						<td>'.(($status >0) ? '<a href="?model=purview&action=show_audit_func&id='.$rs['id'].'&userid='.$rs['userid'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" class="thickbox" title="查看 '.$rs['user_name'].' 的权限申请">查看</a>' :'<a href="?model=purview&action=audit_func&id='.$rs['id'].'&userid='.$rs['userid'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" class="thickbox" title="审核 '.$rs['user_name'].' 的申请">审核操作</a>').'</td>
					</tr>
					';
			}
			$showpage = new includes_class_page();
			$showpage->show_page(array('total'=>$num,'perpage'=>pagenum));
			$showpage->_set_url('num='.$num.'&status='.$_GET['status'].'&');
			return $str.'<tr><td colspan="7">'.$showpage->show(6).'</td></tr>';
		}
	}

	function check_func_id($audit_id,$apply_id)
	{
		if ($apply_id && $audit_id)
		{
			$apply = explode(',',$apply_id);
			$audit = explode(',',$apply_id);
			foreach ($apply as $val)
			{
				if (in_array($val,$audit))
				{
					return true;
				}
			}
			return false;
		}else{
			return false;
		}
	}
	/**
	 * 审核权限显示界面
	 *
	 */
	function model_show_audit_func()
	{
		if (intval($_GET['id']))
		{
			return $this->model_show_func('audit');
		}else{
			showmsg('非法参数！');
		}
	}

	function model_asve_audit()
	{
		if (intval($_GET['id']))
		{
			$rs = $this->db->get_one("
			select
				func_id_str_yes,func_id_str_no,audited
			from
				purview_apply
				where find_in_set('".$_SESSION['USER_ID']."',audit_userid) and id=".$_GET['id']
				);
				if ($rs['func_id_str_no'])
				{
					$func_id_str_arr = explode(',',$rs['func_id_str_no']);
					$audit_arr = array();
					$apply_arr = array();
					if ($_POST['submit']==' 通过申请 ')
					{
						if ($_POST['id'])
						{
							foreach ($func_id_str_arr as $val)
							{
								if (in_array($val,$_POST['id']))
								{
									$audit_arr[] = $val; //通过申请
								}else{
									$apply_arr[] = $val; //未通过的申请
								}
							}
							$func_id_str_yes = $rs['func_id_str_yes'] ? $rs['func_id_str_yes'].','.($audit_arr ? implode(',',$audit_arr):'') : ($audit_arr ? implode(',',$audit_arr):'');
							$this->db->query("
							update
								purview_apply
							set
								func_id_str_yes='".trim($func_id_str_yes)."',
								func_id_str_no='".implode(',',$apply_arr)."',
								audited='".($rs['audited'] ? $rs['audited'].','.$_SESSION['USER_ID']:$_SESSION['USER_ID'])."',
								audit_date='".time()."'
								where find_in_set('".$_SESSION['USER_ID']."',audit_userid) and id=".$_GET['id']."
							");
							$this->db->query("
								update
									purview_apply
								set
									status='1'
								where
									status='0' and (func_id_str_no=null or func_id_str_no='')
									");
							return true;
						}else{
							showmsg('最少选择一条记录！');
						}
					}elseif ($_POST['submit']==' 确定退回 '){
						if (intval($_GET['id']))
						{
							$rs = $this->db->get_one("
							select
								id,return_userid
							from
								purview_apply
								where find_in_set('".$_SESSION['USER_ID']."',audit_userid) and id=".$_GET['id']
								);
								if ($rs)
								{
									return $this->db->query("
								update
									purview_apply
								set
									return_userid='".($rs['return_userid'] ? $rs['return_userid'].','.$_SESSION['USER_ID'] : $_SESSION['USER_ID'])."',
									status='-1',
									notse='".$_POST['notse']."'
									where find_in_set('".$_SESSION['USER_ID']."',audit_userid) and id=".$_GET['id']);
								}
						}
					}else{
						showmsg('非法参数！');
					}
				}
		}
	}
	/**
	 * （撤消）删除申请记录
	 *
	 */
	function model_delete_apply()
	{
		if (intval($_GET['id']))
		{
			return $this->db->query("delete from purview_apply where apply_userid='".$_SESSION['USER_ID']."' and id=".$_GET['id']);
		}else{
			showmsg('非法请求！');
		}

	}
	/**
	 * 最新申请权限
	 *
	 */
	function model_new_apply()
	{
		$page = $_GET['page'] ? $_GET['page'] : 1;
		$start = ($page==1) ? 0 : ($page-1) * pagenum;
		$num = $_GET['num'] ? $_GET['num'] : false;
		if(!$num)
		{
			$rs = $this->db->get_one("select count(0) as num from purview_apply where status='1'");
			$num = $rs['num'];
		}
		if ($num > 0)
		{
			$query = $this->db->query("
				select
					a.*,b.user_name,c.user_name as apply_user,d.dept_name
				from
					purview_apply as a
					left join user as b on b.user_id=a.userid
					left join user as c on c.user_id=a.apply_userid
					left join department as d on d.dept_id=b.dept_id
				where
					a.status=1
				order by a.id desc
			");
			while ($rs = $this->db->fetch_array($query))
			{
				$str .='
				<tr>
					<td>'.$rs['id'].'</td>
					<td>'.$rs['user_name'].'</td>
					<td>'.$rs['dept_name'].'</td>
					<td>'.$rs['apply_user'].'</td>
					<td>'.$this->get_username_list($rs['audit_userid']).'</td>
					<td>'.date('Y-m-d',$rs['date']).'</td>
					<td><a href="?model=purview&action=treat_apply&id='.$rs['id'].'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" class="thickbox" title="处理 '.$rs['user_name'].' 的权限申请">处理</a></td>
				</tr>
				';
			}
		}
		$showpage = new includes_class_page();
		$showpage->show_page(array('total'=>$num,'perpage'=>pagenum));
		$showpage->_set_url('num='.$num.'&');
		return $str.'<tr><td colspan="7">'.$showpage->show(6).'</td></tr>';
	}
	/**
	 * 获取多个姓名
	 *
	 * @param unknown_type $userid
	 * @return unknown
	 */
	function get_username_list($userid)
	{
		if ($userid)
		{
			$arr = explode(',',$userid);
			$in='';
			foreach ($arr as $v)
			{
				$in .="'$v',";
			}
			if ($in)
			{
				$in = rtrim($in,',');
				$query = $this->db->query("
				select
					user_name
				from
					user
				where
					user_id in ($in)
			");
				$username_str = '';
				while ($rs = $this->db->fetch_array($query)) {
					$username_str .=$rs['user_name'].',';
				}
				return rtrim($username_str,',');
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function model_set_treat_apply()
	{
		if ($_POST['submit']==' 通过申请 ')
		{
			if ($_POST['userid'])
			{
				$rs = $this->db->get_one("select func_id_yes from user where user_id='".$_POST['userid']."'");
				if ($rs)
				{
					$func_id_str = $rs['func_id_yes'] ? $rs['func_id_yes'].','.implode(',',$_POST['id']) : implode(',',$_POST['id']);
					$func_id_str = rtrim($func_id_str,',');
					$func_id_arr = explode(',',$func_id_str);
					$func_id_arr = array_unique($func_id_arr);
					$func_id_str = implode(',',$func_id_arr);
					$this->db->query("update user set func_id_yes='".$func_id_str."' where user_id='".$_POST['userid']."'");
					return $this->db->query("update purview_apply set status='2' where id=".$_GET['id']);
				}
			}
		}elseif ($_POST['submit']==' 确定退回 '){
			if (intval($_GET['id']))
			{
				return $this->db->query("update purview_apply set return_userid='".$_SESSION['USER_ID']."',status='-1',notse='".$_POST['notse']."' where id=".$_GET['id']);
			}
		}
	}
        /**
         *员工列表
         */
        function model_user_tree(){
            $tmp_arr=array();
            global $func_limit;
            $gl=new includes_class_global();
            $id=$_POST['id']?$_POST['id']:'d_0';
            $idarr =  explode('_', $id);
            $seakey=$_REQUEST['seakey'];
            $sqlsea="";
            if(!empty($seakey)){
                $sqlsea=" and u.user_name like '%".$seakey."%' ";
            }
            if($func_limit['部门权限']!=';;'){
                $sqlsea=" and u.dept_id in ('".  str_replace(',', "','", $func_limit['部门权限'])."') ";
            }
            if($id=='d_0'){
                $sql="select d.parent_id , d.dept_id , d.dept_name from department d
                    left join department dt on (dt.parent_id=d.dept_id or d.dept_id=dt.dept_id)
                    left join user u on (u.dept_id = dt.dept_id and u.del='0' and u.has_left='0' )
                    where d.delflag=0 $sqlsea
                    group by d.dept_id ";
                $query = $this->db->query($sql);
                while ($row = $this->db->fetch_array($query)) {
                    $tmp_arr['d_'.$row['parent_id']]['d_'.$row['dept_id']]['name']=$row['dept_name'];
                    $tmp_arr['d_'.$row['parent_id']]['d_'.$row['dept_id']]['state']='closed';
                }
            }elseif($idarr[0]=='d'){
                $sql="SELECT j.id , j.name FROM user_jobs j
                    left join user u on (u.jobs_id = j.id  and u.del='0' and u.has_left='0' )
                    where j.dept_id='".$idarr[1]."' $sqlsea  group by j.id  order by j.name ";
                $query = $this->db->query($sql);
                while ($row = $this->db->fetch_array($query)) {
                    $tmp_arr[$id]['j_'.$row['id']]['name']=$row['name'];
                    $tmp_arr[$id]['j_'.$row['id']]['state']='closed';
                }
            }elseif($idarr[0]=='j'){
                $sql="SELECT user_id as id  , user_name as name  FROM user u where  u.del='0' and u.has_left='0' and jobs_id='".$idarr[1]."' $sqlsea order by user_name ";
                $query = $this->db->query($sql);
                while ($row = $this->db->fetch_array($query)) {
                    $tmp_arr[$id]['u_'.$row['id']]['name']="<a href='#' class='treea' onClick='pvopen(\"" . $row['id'] . "\")'>" . ($row['name']) . "</a>";
                }
            }
            $info=$gl->json_tree_data($tmp_arr,$id);
            return json_encode($info);
        }
        /**
         *权限信息
         */
        function model_pv_info(){
            $gl=new includes_class_global();
            $pvkey = $_GET['pvkey'];
            $pv= array();
            //员工信息
            //验证用户
            $sql="select u.USER_ID,u.DEPT_ID,u.jobs_id as USER_JOBSID
            from user u
            where
                u.user_id='".$pvkey."' ";
            $pvuser=$this->db->get_one($sql);
            //获取员工数据权限
            $pvdata=array();
            $sql="select
                        pvi.type , pvi.content , pvt.name , p.menuid , pvt.pv_ini_sql
                from
                        purview_info as pvi
                        left join purview_type as pvt on pvt.id=pvi.typeid
                        left join purview p on (p.id= pvi.tid)
                where
                        (
                                pvi.userid='" . $pvuser ['USER_ID'] . "'
                                or pvi.jobsid ='" . $pvuser ['USER_JOBSID'] . "'
                                or pvi.deptid='" . $pvuser ['DEPT_ID'] . "'
                                or (
                                ( pvi.userid = '' or pvi.userid is null )
                                and  pvi.jobsid='0'
                                and  pvi.deptid='0'
                                )

                        )";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $pvdata[$row['menuid']][$row['name']]['type']=$row['type'];
                $pvdata[$row['menuid']][$row['name']]['data']=$row['content'];
                $pvdata[$row['menuid']][$row['name']]['name']=$row['name'];
                $pvdata[$row['menuid']][$row['name']]['pv_ini_sql']=$row['pv_ini_sql'];
            }
            $sql="select '1' as 'type'  , '' as 'content'  , pvt.name , p.menuid , pvt.pv_ini_sql
                from
                purview_type as pvt
                left join purview p on ( p.id= pvt.tid )
                where pvt.pv_ini_sql  <> ''";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                if(empty($pvdata[$row['menuid']][$row['name']])){
                    $pvdata[$row['menuid']][$row['name']]['type']=$row['type'];
                    $pvdata[$row['menuid']][$row['name']]['data']=$row['content'];
                    $pvdata[$row['menuid']][$row['name']]['name']=$row['name'];
                    $pvdata[$row['menuid']][$row['name']]['pv_ini_sql']=$row['pv_ini_sql'];
                }
            }
            //读取附带权限
            if(!empty($pvdata)){
                //print_r($pvdata);
                $pv_ini=array();
                $pvckkey=  array_keys($pvuser);
                foreach( $pvdata as $key=>$val ){
                    foreach($val as $vkey=>$vval){
                        if(!empty($vval['pv_ini_sql'])&&strripos($vval['pv_ini_sql'],'select')!==false){
                            $sql=$vval['pv_ini_sql'];
                            foreach($pvckkey as $ckval){
                                $sql=str_replace('S['.$ckval.']', $pvuser[$ckval], $sql);
                            }
                            //echo $sql;
                            $query = $this->db->query($sql);
                            while ($rs = $this->db->fetch_array($query))
                            {
                                $pvdata[$key][$vkey]['data'].='（自带权限：'.$rs['pvdata'].($rs['pvext']?'附带权限：'.$rs['pvext']:'').'）';
                            }
                        }
                    }
                }
            }
            //获取员工栏目权限
            $sql = "select j.func_id_str as jpv , u.func_id_yes as uypv , u.func_id_no as unpv , d.funcstr as dpv
                from user u
                left join user_jobs j on (u.jobs_id = j.id)
                left join department d on (u.dept_id=d.dept_id)
                where user_id='" . $pvkey. "' ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $dpv=$row['dpv'];
                $jpv=$row['jpv'];
                $uypv=$row['uypv'];
                $unpv=$row['unpv'];
            }
            $dpv=explode(',_', trim($dpv,'_'));
            $jpv=explode(',_', trim($jpv,'_'));
            $uypv=explode(',_', trim($uypv,'_'));
            $unpv=explode(',_', trim($unpv,'_'));
            $pv = array_unique(array_merge_recursive($uypv, $jpv, $dpv));
            $pv = array_diff($pv,$unpv);
            $tmpfunc = array();
            if($pv){
                foreach($pv as $val){
                    $vallen=strlen($val);
                    for($i=2;$i<$vallen;){
                        if($i!=0){
                            $tmpfunc[substr($val,0,$i)]=1;
                        }
                        $i+=2;
                    }
                    $tmpfunc[$val]=1;
                }
                $tmpfunc=array_keys($tmpfunc);
                $pv=array();
                $sql = "select f.MENU_ID as fm ,  f.func_name as fn , f.func_code as fc
                    from sys_function f
                    where
                        f.MENU_ID in ('" .trim(implode("','", $tmpfunc), ","). "')
                        and enabled='1'
                    order by f.taxis_id asc ";
                $query = $this->db->query($sql);
                while ($row = $this->db->fetch_array($query)) {
                    $pid=  substr($row['fm'], 0, -2);
                    $pv[$pid][$row['fm']]['name']=$row['fn'];
                    if(!empty($pvdata[$row['fm']])){
                        foreach($pvdata[$row['fm']] as $key=>$val){
                            $pv[$pid][$row['fm']]['datapv'].=$key.'=>'.$val['data'].'；  ';
                        }
                    }
                    $lanMenu[substr($row['fm'], 0, 2)]=substr($row['fm'], 0, 2);
                }
                if(!empty($lanMenu)){
                    $sql = "select menu_id as mi , menu_name as mn  , image as mg
                        from sys_menu where menu_id in ('" . implode("','", $lanMenu) . "')
                            and closed='0'
                        order by  taxis_id ASC";
                    $query = $this->db->query($sql);
                    while ($row = $this->db->fetch_array($query)) {
                        $pv[0][$row['mi']]['name']=$row['mn'];
                    }
                }
            }
            $info=$gl->json_tree_data($pv,0);
            return json_encode($info);
        }
        //安全过滤输入
		function clean($value){
		  $value=trim($value);
		  if(get_magic_quotes_gpc()){
			$value=stripslashes($value);
		  }
		  if(function_exists("mysql_real_escape_string")){
		  	$value=mysql_real_escape_string($value);
		  }else{
		 	$value=addslashes($value);
		  }
		  return $value;
		}
}
?>