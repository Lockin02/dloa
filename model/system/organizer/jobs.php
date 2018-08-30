<?php

class model_system_organizer_jobs extends model_base {

    public $db;

    function __construct() {
        parent :: __construct();
        $this->db = new mysql ();
    }

    /**
     * 职位列表
     */
    function showlist() {
        $searchdept = $_GET['searchdept'] ? $_GET['searchdept'] : $_POST['searchdept'];
        $deptsql = '';
        global $func_limit;
		$dept = new includes_class_depart ();
		$deptI=(array)explode(',',$func_limit['管理部门权限']?$func_limit['管理部门权限']:0);
        $sql = "select a.*,b.DEPT_NAME from user_jobs as a left join department as b on b.DEPT_ID=a.dept_id where 1 and a.dept_id in(".implode(',',(array)$deptI).") order by a.dept_id desc,a.level asc";
        if ($searchdept) {
            $deptsql = " and b.dept_id='$searchdept' ";
            $sql = "select a.*,b.DEPT_NAME from user_jobs as a , department as b where b.DEPT_ID=a.dept_id  and a.dept_id in(".implode(',',(array)$deptI).") $deptsql order by a.dept_id desc,a.level asc";
        }
        $query = $this->db->query($sql);
        while (($rs = $this->db->fetch_array($query)) != false) {
           // $show_user_link = thickbox_link('查看职位人员', 'a', 'id=' . $rs['id'] . '&dept_id=' . $rs['dept_id'], '查看 ' . $rs['name'] . ' 的所有员工', null, 'show_user', 500, 400);
            $detail_link = thickbox_link('查看职位人员', 'a', 'id=' . $rs['id'] . '&dept_id=' . $rs['dept_id'], '查看 ' . $rs['name'] . ' 的所有员工', null, 'detail', 500, 400);
            if ($rs['dept_id'] == 1 && $_SESSION['USER_ID'] != 'admin')
                continue;
            $str .= '
		    <tr id="tr_' . $rs['id'] . '">
			<td height="25" align="center">' . $rs['id'] . '</td>
			<td align="center" id="dept_' . $rs['id'] . '">' . $rs['DEPT_NAME'] . '</td>
			<td align="center" id="name_' . $rs['id'] . '">' . $rs['name'] . '</td>
			<td align="center" id="level_' . $rs['id'] . '">' . $rs['level'] . '</td>
			<td align="center" id="m_' . $rs['id'] . '"> ' .
				($func_limit['修改权限']? (' 
				<a href="javascript:edit(' . $rs['id'] . ')">修改职位</a> | 
				<a href="javascript:del(' . $rs['id'] . ')">删除职位</a> | '):'').$detail_link.' |
				<a href="?model=jobs&action=edit_func&id=' . $rs['id'] . '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" class="thickbox" title="编辑《' . $rs['name'] . '》职位权限">编辑权限</a>  
			</td>
		</tr>
		';
        }
        return $str;
    }

    /**
     * 职位用户
     * @param unknown_type $jobs_id
     */
    function model_show_user($jobs_id, $field = 'user_name') {
        $query = $this->db->query("select $field from user where jobs_id=$jobs_id and del=0 and has_left=0");
        $data = array();
        while (($rs = $this->db->fetch_array($query)) != false) {
            $data[] = $rs[$field];
        }
        return $data;
    }

    /**
     * 部门用户
     * @param unknown_type $dept_id
     */
    function model_dept_user($dept_id, $jobs_id = '') {
        $order = $jobs_id ? 'order by jobs_id=' . $jobs_id . ' desc , user_name asc' : '';
        $query = $this->db->query("select * from user where dept_id='$dept_id' and del=0 and has_left=0 $order ");
        $data = array();
        while (($rs = $this->db->fetch_array($query)) != false) {
            $data[] = $rs;
        }
        return $data;
    }

    /**
     * 修改职位用户
     * @param $jobs_id
     * @param $data
     */
    function model_edit_jobs_user($jobs_id, $data) {
        $jobs_user = $this->model_show_user($jobs_id, 'user_id');
        if ($jobs_user) {
            foreach ($jobs_user as $user_id) {
                if ($user_id && $data['user_id'] && !in_array($user_id, $data['user_id'])) {
                    $this->db->query("update user set user_priv='$jobs_id', jobs_id='41' where user_id='$user_id'");
                } elseif (!$data['user_id']) {
                    $this->db->query("update user set user_priv='$jobs_id', jobs_id='41' where user_id='$user_id'");
                }
            }
        }
        if ($data['user_id']) {
            foreach ($data['user_id'] as $val) {
                $this->db->query("update user set jobs_id='$jobs_id', user_priv='$jobs_id' where user_id='$val'");
            }
        }
        return true;
    }

    /**
     * 添加职位
     */
    function insert() {
        $name = $_GET['name'] ? $_GET['name'] : $_POST['name'];
        $deprtid = $_GET['deprtid'] ? $_GET['deprtid'] : $_POST['deprtid'];
        if ($name && $deprtid) {
            $rs = $this->db->get_one("select id from user_jobs where name='$name' and dept_id=$deprtid");
            if ($rs) {
                showmsg('该职位已经存在！');
            }
        }
        $level = $_GET['level'] ? $_GET['level'] : $_POST['level'];
        $rs = $this->db->get_one("select func_id_str from user_jobs where id=41");
        $this->db->query("insert into user_jobs(name,dept_id,level,func_id_str)values('$name','$deprtid','$level','" . $rs['func_id_str'] . "')");
        $jobinsertid = $this->db->insert_id();
        $this->db->query("insert into user_priv(priv_name,user_priv)values('$name','$jobinsertid')");
        return true;
    }

    /**
     * 修改职位
     */
    function update() {
        $name = $_GET['name'] ? $_GET['name'] : $_POST['name'];
        $id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
        $dept_id = $_GET['dept_id'] ? $_GET['dept_id'] : $_POST['dept_id'];
        $level = $_GET['level'] ? $_GET['level'] : $_POST['level'];
        if ($name && $id) {
            //$name = iconv('UTF-8','gb2312',$name);
            $row = $this->db->get_one("select id from user_jobs where id=$id");
            if ($row) {
                $query = "update user_jobs set dept_id='$dept_id', name='$name',level='$level' where id=$id";
                $this->db->query($query);
                $query = "update user_priv set priv_name='$name' where user_priv=$id";
                $this->db->query($query);
                echo 1;
            } else {
                echo 2;
            }
        }
    }

    /**
     * 删除职位
     */
    function delete() {
        $id = $_GET['id'] ? $_GET['id'] : $_POST['id'];
        if ($id) {
            $row = $this->db->get_one("select id from user_jobs where id=$id");
            if ($row) {
                $rs = $this->db->get_one("select * from user where find_in_set('$id',jobs_id)");
                if ($rs) {
                    echo 2;
                } else {
                    $this->db->query("delete from user_jobs where id=$id");
                    echo 1;
                }
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    /**
     * 编辑权限
     */
    function edit_purview() {
        global $user_func_id;
        if ($user_func_id) {
            $menuArr = array();
            foreach ($user_func_id as $val) {
                if (stripos($val, '_') !== false) {
                    $menuArr[] = substr(str_replace('_', '', $val), 0, 2);
                }
            }
            $menuArr = array_unique($menuArr);
        }
        $MenuId = implode(',', $menuArr);
        if (intval($_GET['id'])) {
            $rs = $this->db->get_one("select func_id_str from user_jobs where id=" . $_GET['id']);
            $func_id_str = explode(',', $rs['func_id_str']);
        } else {
            showmsg('非法访问！');
        }
        $powerFun = array();
        $query = $this->db->query("select left(menuid,2) as m1 , left(menuid,4) as m2  from purview");
        while (($rs = $this->db->fetch_array($query)) != false) {
            $menuSql .= $rs["m1"] . ',';
            $funcSql .= $rs['m2'] . ',';
        }
        $menuSql = implode(',', array_unique(explode(',', $menuSql)));
        $funcSql = implode(',', array_unique(explode(',', $funcSql)));
        $menuSql = rtrim($menuSql, ',');
        $funcSql = rtrim($funcSql, ',');
        $str .= '<ul><div class="submit"><input type="submit" value=" 修 改 " /> <input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " /></div></ul>';
        $query = $this->db->query("select * from sys_menu where MENU_ID in ($menuSql) and MENU_ID in($MenuId)  order by taxis_id asc");
        while (($rs = $this->db->fetch_array($query)) != false) {
            //if (!in_array($rs['MENU_ID'],$user_func_id)) continue;
            $str .= '
			<ul id="title_' . $rs['MENU_ID'] . '">
			<div class="title"><input type="checkbox" onclick="check(\'' . $rs['MENU_ID'] . '\')" />' . $rs['MENU_NAME'] . '</div>';
            $sql = $this->db->query("select * from sys_function where MENU_ID like '" . $rs["MENU_ID"] . "%' and length(MENU_ID)=4 and menu_id in ($funcSql)  order by taxis_id ASC");
            $i = 0;
            $num = $this->db->num_rows($sql);
            while (($row = $this->db->fetch_array($sql)) != false) {
                if (!in_array('_' . $row['MENU_ID'], $user_func_id))
                    continue;
                $i++;
                if ($i == 1)
                    $str .= '<div class="menulist">';
                $str .= '<li id="title_' . $row['MENU_ID'] . '"><input type="checkbox" onclick="check(\'' . $row['MENU_ID'] . '\')" name="id[]" value="_' . $row['MENU_ID'] . '" ' . $this->checked($func_id_str, '_' . $row['MENU_ID']) . '/><b>' . $row['FUNC_NAME'] . '</b>';
                $psql = $this->db->query("select * from purview where menuid='" . $row['MENU_ID'] . "'");
                $_arr = array();
                while (($ra = $this->db->fetch_array($psql)) != false) {
                    if (!in_array($ra['id'], $user_func_id))
                        continue;
                    $set_url = '';
                    if ($ra['name'] != '访问') {
                        if ($ra['control'] == 1) {
                            $set_url = '&nbsp;<a href="javascript:show_control(' . $ra['id'] . ');">详细</a>';
                        }
                        if ($ra['type'] == 1) {
                            $_arr[$ra['models']] = '&nbsp;&nbsp;';
                            $str .= '<ul><li><input type="checkbox" name="id[]" ' . $this->checked($func_id_str, $ra['id']) . ' value="' . $ra['id'] . '"><font color="#CC33FF">' . $ra['name'] . $set_url . '</font></li></ul>';
                        } else {
                            $str .= '<ul><li>' . $_arr[$ra['models']] . '<input type="checkbox" name="id[]" ' . $this->checked($func_id_str, $ra['id']) . ' value="' . $ra['id'] . '">' . $ra['name'] . $set_url . '</li></ul>';
                        }
                    }
                }
                $mysql = $this->db->query("select * from sys_function where MENU_ID like '" . $row["MENU_ID"] . "%' and length(MENU_ID)>4 order by taxis_id ASC ");
                while (($rss = $this->db->fetch_array($mysql)) != false) {
                    if (!in_array('_' . $rss['MENU_ID'], $user_func_id))
                        continue;
                    $str .= '<ul id="title_' . $rss['MENU_ID'] . '"><li><input type="checkbox" onclick="check(\'' . $rss['MENU_ID'] . '\')" name="id[]" value="_' . $rss['MENU_ID'] . '" ' . $this->checked($func_id_str, '_' . $rss['MENU_ID']) . '/><span style="color:#0099FF;">' . $rss['FUNC_NAME'] . '</span>';
                    $msql = $this->db->query("select * from purview where menuid='" . $rss['MENU_ID'] . "'");
                    $_arr = array();
                    while (($rr = $this->db->fetch_array($msql)) != false) {
                        if (!in_array($rr['id'], $user_func_id))
                            continue;
                        $set_url = '';
                        if ($rr['name'] != '访问') {
                            if ($rr['control'] == 1) {
                                $set_url = '&nbsp;<a href="javascript:show_control(' . $rr['id'] . ');">详细</a>';
                            }
                            if ($rr['type'] == 1) {
                                $_arr[$ra['models']] = '&nbsp;&nbsp;';
                                $str .= '<ul><li><input type="checkbox" name="id[]" ' . $this->checked($func_id_str, $rr['id']) . ' value="' . $rr['id'] . '"><font color="#CC33FF">' . $rr['name'] . $set_url . '</font></li></ul>';
                            } else {
                                $str .= '<ul><li>' . $_arr[$ra['models']] . '<input type="checkbox" name="id[]" ' . $this->checked($func_id_str, $rr['id']) . ' value="' . $rr['id'] . '">' . $rr['name'] . $set_url . '</li></ul>';
                            }
                        }
                    }
                    $str .= '</li></ul>';
                }
                $str .= '</li>';
                if ($i % 6 == 0)
                    $str .= '</div>';
                if ($i % 6 == 0 && $i != $num)
                    $str .= '<div class="menulist">';
            }
            $str .= '</ul>';
        }
        $str .= '<ul><div class="submit"><input type="submit" value=" 修 改 " /> <input type="button" onclick="self.parent.tb_remove();" value=" 返 回 " /></div></ul>';
        return $str;
    }

    /**
     * 修改保存权限
     */
    function model_save_func_old() {
        if (intval($_GET['jobs_id'])) {
            $rs = $this->db->get_one("select * from user_jobs where id=" . $_GET['jobs_id']);
            if ($rs) {
                if ($_POST['id']) {
                    global $user_func_id;

                    $jobs_func_id_arr = $rs['func_id_str'] ? explode(',', $rs['func_id_str']) : array();
                    $add_func_id_arr = array_diff($_POST['id'], $jobs_func_id_arr);  //得到新增
                    $del_func_id_arr = array_diff($jobs_func_id_arr, $_POST['id']); //得到需要删除的
                    $del_func_id_arr = array_diff($del_func_id_arr, array_diff($del_func_id_arr, $user_func_id)); //和当前登录用户权限对比后再得到可删除的权限
                    $jobs_func_id_arr = array_diff($jobs_func_id_arr, $del_func_id_arr);  //删除掉去掉选中的
                    $jobs_func_id_arr = array_merge($jobs_func_id_arr, $add_func_id_arr); //增加新选中的
                    $func_id_str = implode(',', $jobs_func_id_arr);
                    $this->db->query("update user_jobs set func_id_str='" . $func_id_str . "' where id=" . $_GET['jobs_id']);
                    if ($_POST['content']) {
                        foreach ($_POST['content'] as $key => $row) {
                            foreach ($row as $k => $v) {
                                if ($v) {
                                    $k_id = array_search('null', $v);
                                    unset($v[$k_id]);
                                }
                                $row = $this->db->get_one("
								select 
									id 
								from 
									purview_info 
								where 
								tid='$key' and typeid='$k' and type=2 and jobsid='" . $_GET['jobs_id'] . "'
								");
                                if ($row['id']) {
                                    $this->db->query("
									update 
										purview_info 
									set 
										content='" . ($v ? implode(',', $v) : '') . "'
									where
										id='" . $row['id'] . "'
									");
                                } else {
                                    if ($v) {
                                        $content = implode(',', $v);
                                        $query = $this->db->query("
										select
											id
										from 
											purview_info
										where 
											deptid='" . $rs['dept_id'] . "'
										");
                                        $last = true;
                                        while (($rr = $this->db->fetch_array($query)) != false) {
                                            if ($rr['content'] == $content) {
                                                $last == false;
                                                break;
                                            }
                                        }
                                        if ($last) {
                                            $this->db->query("
											insert into purview_info
												(tid,typeid,type,jobsid ,content,date)
											values
												('$key','$k',2,'" . $_GET['jobs_id'] . "','$content','" . time() . "')
											");
                                        }
                                    }
                                }
                            }
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                showmsg('职位不存在！');
            }
        } else {
            return false;
        }
    }

    /**
     * 设置选定
     * @param $func_id_str
     * @param $menuid
     */
    function checked($func_id_str, $menuid) {
        if (in_array($menuid, $func_id_str)) {
            return 'checked';
        } else {
            return '';
        }
    }

    /**
     * 模块权限
     */
    function show_user_control_func() {
        if ($_GET['tid']) {
            $pvurl = new model_ajax_pvurl ();
            $query = $this->db->query("
				select 
					a.* ,b.content
				from 
					purview_type as a
					left join purview_info as b on b.typeid=a.id and type=2 and jobsid='" . $_GET['jobsid'] . "'
				where a.tid=" . $_GET['tid']);
            while (($rs = $this->db->fetch_array($query)) != false) {
                $temp = '';
                $_GET['typeid'] = $rs['id'];
                $_GET['type'] = 2;
                $_GET['uid'] = $_GET['jobsid'];
                if ($rs['content']) {
                    $pvurl->content = $rs['content'];
                } else {
                    $pvurl->get_checked_content();
                }
                $temp = $pvurl->get_list();
                $str .= ( $temp ? '<b>' . $rs['name'] . '</b><br>' . str_replace('content[]', 'content[' . $_GET['tid'] . '][' . $rs['id'] . '][]', $temp . '<input type="hidden" name="content[]" value="null" />') . '<hr>' : '');
            }
            return $str;
        }
    }

    /*
     * 保存权限
     * 
     */

    function model_save_func() {
        global $user_func_id;
        $ckv = $_POST['ckv'];
        $cki = $_POST['cki'];
        $cku = $_POST['jid'];
        //是否修改的大栏目
        $ckv = explode(',', trim($ckv, ','));
        $cki = explode(',', trim($cki, ','));
        //获取用户权限
        $sql = "select j.func_id_str 
            from user_jobs j 
            where id='" . $cku . "' ";
        $res = $this->_db->get_one($sql);
        $funcJ = $res["func_id_str"]; //职位权限
        $funcJ = explode(',', trim($funcJ, ','));

        //未选中
        $funcJnc = array();
        foreach ($funcJ as $key => $val) {
            if (in_array(substr($val, 1, 2), $ckv) == false) {
                $funcJnc[] = $val;
            }
        }
        //权限外
        if($_SESSION['USER_ID']=='admin'){
            $funcW = array();
        }else{
            $funcW = array_diff($funcJ, $user_func_id);
        }
        //合起
        $funcY = array_unique(array_merge($funcJnc, $funcW, $cki));
        $sql = "update user_jobs 
                set func_id_str = '" . implode(',', $funcY) . "'
              where id='" . $cku . "' ";
        $this->pf($sql);
        if (false != $this->_db->query($sql)) {
            return '修改成功！';
        } else {
            return '修改失败！';
        }
    }

}

?>