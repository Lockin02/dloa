<?php

class controller_user extends model_general_system_user_list {

    public $show;
    function __construct() {
        parent::__construct();
        $this->show = new show ();
    }

    function c_index() {
        $depart = new includes_class_depart ();
        $jobs = new includes_class_jobs ();
        $this->show->assign('depart_select', $depart->depart_selects());
        $this->show->assign('jobs_select', $jobs->jobs_select());
        $this->show->display('general_system_user_list');
    }

    function c_adduser() {
    	
        //echo crypt_util('YIbFr9jHADQn4xUjoUumJXYEEiaYyfngudkkt7liYTk=','decode'); 
        $oId=(int)$_GET['oId']?$_GET['oId']:$_POST['oId'];
        if($oId&&$oId!=0){
          $o= new model_hr_recruitment_entryNotice();
          $oInfo=$o->get_d($oId);
        }
        $dept = new includes_class_depart();
        $area = new includes_class_global();
        
        $this->show->assign('userType_select', $this->model_generalSystemUserGetuserTypeSelect());
        $this->show->assign('dept_select', $dept->depart_selects());
        $this->show->assign('area_select', $area->area_select());
        $this->show->assign('checked2','checked');
        $this->show->assign('checked1','');
        $this->show->assign('useJobId','');
        $this->show->assign('userName', '');
        $this->show->assign('oId', '');
        if($oInfo&&is_array($oInfo)){
        	$this->show->assign('dept_select', $dept->depart_selects($oInfo['deptId']));
            $this->show->assign('area_select', $area->area_select($oInfo['useAreaId']));
            $this->show->assign('useJobId', $oInfo['useJobId']);
            $this->show->assign('userName', $oInfo['userName']);
            $this->show->assign('oId', $oId);
            if($oInfo['sex']=='女'){
            	$this->show->assign('checked1','checked');
            	$this->show->assign('checked2','');
            }else{
            	$this->show->assign('checked2','checked');
            }
        }
           
        
        $this->show->assign('bran_select', $area->toSelectOption($area->getBranchInfo(),'NameCN' ,'NamePT' ));
        $this->show->assign('password', passwordini);
        $this->show->assign('maildomain', maildomain);
        
        
        unset($dept);
        unset($area);
        if ($_SESSION['COM_BRN_PT'] == 'dl') {
            $this->show->display('general_system_user_adduser');
        } elseif (!empty($_SESSION['COM_BRN_PT'])) {
            $user = new includes_class_user();
            $this->show->assign('userid', $user->get_new_usercard());
            $this->show->assign('maildomain', maildomain);
            $this->show->display('general_system_user_adduserext');
        }
      
    }

    function c_insert() {
        $_POST ['user'] ['EMAIL'] = $_POST ['user'] ['EMAIL'] . maildomain;
        $_POST ['user'] ['Creator'] = $_SESSION ['USER_ID'];
        $_POST ['user'] ['CreatDT'] = date('Y-m-d H:i:s');
        $_POST ['user'] ['LogName'] = $_POST ['user'] ['USER_ID'];
        $_POST ['user'] ['oId'] = $_POST ['oId'] ;
         if ($_SESSION['COM_BRN_PT'] == 'bx') {
         	$_POST ['user'] ['PASSWORD'] = md5(passwordini);
         }else{
         	 $_POST ['user'] ['PASSWORDINIT'] = trim($_POST ['user'] ['PASSWORD']);
             $_POST ['user'] ['PASSWORD'] = md5(trim($_POST ['user'] ['PASSWORD']));
             
         }
       
        $user = new model_general_system_user_adduser ();
        
        $MSG=$user->adduser($_POST ['user']);
            $str='';
            if( $MSG&&is_array($MSG)){
            	  foreach ($MSG as $key => $val){
            	  	if($key=='creat_user'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建用户信息成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建用户信息失败！</div>';
            	  		}
            	  	}
            	  	if($key=='creat_Email'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建邮箱地址成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建邮箱地址失败！</div>';
            	  		}
            	  	}
            	  	
            	   	if($key=='creat_im'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建大蚂蚁帐号成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建大蚂蚁帐号失败！</div>';
            	  		}
            	  	}
            	  	if($key=='creat_ecard'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建通信录成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建通信录失败！</div>';
            	  		}
            	  	}
            	  	if($key=='creat_hrms'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新人事档案成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新用人事档案失败！</div>';
            	  		}
            	  	}
            	  }
            }
             $str.= '<div style="width:100%;color:#F00 ">添加 《' . $_POST ['user'] ['USER_ID']. '》成功！</div>';
                showmsg($str, 'self.parent.tb_remove();self.parent.show_page(1);', 'button');
        //if ($user->adduser($_POST ['user'])) {
            //showmsg('添加 ' . $_POST ['user'] ['USER_ID'] . ' 成功！', 'self.parent.tb_remove();self.parent.show_page(1);', 'button');
        //}
    }

    function c_edit() {
        $edituser = new model_general_system_user_edituser ();
        $area = new includes_class_global ();
        $this->show->assign('title', '编辑用户');
        $userinfo = $edituser->userinfo($_GET ['userid']);       
        foreach ($userinfo as $key => $val) {
            if ($key == 'userType') {
                $this->show->assign('userType_select', $this->model_generalSystemUserGetuserTypeSelect($val));
            }elseif ($key == 'DEPT_ID') {
                $dept_select = $edituser->depart_select($val);
                $this->show->assign('dept_select', $dept_select);
                $this->show->assign('det_select', $dept_select);
            } elseif ($key == 'jobs_id') {
                $job_select = $edituser->jobs_select($userinfo ['DEPT_ID'], $val);
                $this->show->assign('jobs_select', $job_select);
            } elseif ($key == 'SEX') {
                if ($val == 0) {
                    $sex0 = 'checked';
                } else {
                    $sex1 = 'checked';
                }
                $this->show->assign('sex0', $sex0);
                $this->show->assign('sex1', $sex1);
            } elseif ($key == 'Company') {
                $Company=$area->toSelectOption($area->getBranchInfo(),'NameCN' ,'NamePT',$val);
                $this->show->assign('company_select', $Company);
            } elseif ($key == 'AREA') {
                $area_select = $area->area_select($val);
                $this->show->assign('area_select', $area_select);
                unset($area);
            } elseif ($key == 'HAS_LEFT') {
                if ($val == 0) {
                    $has = '<input type="checkbox" onclick="set_has(this);" name="user[HAS_LEFT]" value="1"> 正式离职';
                } else {
                    $has = '已离职';
                }
                $this->show->assign('has', $has);
            } elseif ($key == 'ready_left') {
                if ($val == 1) {
                    $this->show->assign('none', '');
                    $ready_left = ' <input type="checkbox" checked onclick="show_link(this);" name="user[ready_left]" value="1" />准备离职';
                } else {
                    $this->show->assign('none', 'none');
                    $ready_left = ' <input type="checkbox" onclick="show_link(this);" name="user[ready_left]" value="1" />准备离职';
                }
                $val = $ready_left;
            } elseif ($key == 'NOT_LOGIN') {
                if ($val) {
                    $login = 'checked';
                }
                $this->show->assign('not_login', $login);
            } elseif ($key == 'EmailFlag') {
				if ($val == 1)
				{
					$tr_email .= '<tr>';
					$tr_email .= '<td align="right"><b>禁用邮箱：</b></td>';
					$tr_email .= '<td><input type="checkbox" value="-1" name="user[EmailFlag]" id="EmialFlags" /> 禁止用户使用邮箱</td>';
					$tr_email .= '</tr>';
					$tr_email .= '<tr>';
					$tr_email .= '<td align="right"><b>Email地址：</b></td>';
					$tr_email .= '<td id="addEmail"><input type="hidden" value="' . $userinfo ['EMAIL'] . '" name="user[email_addres]"/>' . $userinfo ['EMAIL'] . '&nbsp;&nbsp;<a herf="javascrpt:void(this)" style="cursor:pointer;color:#00F; text-decoration:underline;" onclick="modfiy_email(this)" id="editEmail">修改</a></td>';
					$tr_email .= '</tr>';
				} elseif ($val == 0)
				{
					$tr_email .= '<tr>';
					$tr_email .= '<td align="right"><b>启用邮箱：</b></td>';
					$tr_email .= '<td><input type="checkbox" value="1" name="user[EmailFlag]" id="EmialFlag" /> 为该用户开启邮箱</td>';
					$tr_email .= '</tr>';
					$tr_email .= '<tr>';
					$tr_email .= '<td align="right"><b>Email地址：</b></td>';
					$tr_email .= '<td ><input type="text" value="' . (str_replace (maildomain, '', $userinfo ['EMAIL'] )) . '" name="user[EMAIL]" id="EMAIL" /><span id="_email">*</span>(@dinglicom.com)</td>';
					$tr_email .= '</tr>';
				} elseif ($val == - 1)
				{
					$tr_email .= '<tr>';
					$tr_email .= '<td align="right"><b>恢复邮箱：</b></td>';
					$tr_email .= '<td><input type="checkbox" value="1" onclick="click_modfiy(this)"  name="user[EmailFlag]" id="EmialFlag" /> 恢复该用户的邮箱使用权限</td>';
					$tr_email .= '</tr>';
					$tr_email .= '<tr>';
					$tr_email .= '<td align="right"><b>Email地址：</b></td>';
					$tr_email .= '<td id="addEmail"><input type="hidden" value="' . $userinfo ['EMAIL'] . '" name="user[email_addres]"/>' . $userinfo ['EMAIL'] . '&nbsp;&nbsp;<a herf="javascrpt:void(this)" style="display:none;width:50px;cursor:pointer;color:#00F; text-decoration:underline;" onclick="modfiy_email(this)" id="editEmail">修改</a></td>';
				    $tr_email .= '</tr>';
				}
				$val = $tr_email;
			} elseif ($key == 'IMFlag') {
				if ($val == 1)
				{
					$tr_IM .= '<tr>';
					$tr_IM .= '<td align="right"><b>禁用即时通讯：</b></td>';
					$tr_IM .= '<td><input type="checkbox" value="-1" name="user[IMFlag]" onclick="click_IMFlag(this)" id="IMFlags" />删除用户使用即时通讯（大蚂蚁）帐号</td>';
					$tr_IM .= '</tr>';
				} else
				{
					$tr_IM .= '<tr>';
					$tr_IM .= '<td align="right"><b>启用即时通讯：</b></td>';
					$tr_IM .= '<td><input type="checkbox" value="1" name="user[IMFlag]" id="IMFlag" /> 为该用户开通即时通讯（大蚂蚁）帐号</td>';
					$tr_IM .= '</tr>';
				}
				/*
				elseif ($val == 0)
				{
					$tr_IM .= '<tr>';
					$tr_IM .= '<td align="right"><b>启用即时通讯：</b></td>';
					$tr_IM .= '<td><input type="checkbox" value="1" name="user[IMFlag]" id="IMFlag" /> 为该用户开通即时通讯（大蚂蚁）帐号</td>';
					$tr_IM .= '</tr>';
				} elseif ($val == - 1)
				{
					$tr_IM .= '<tr>';
					$tr_IM .= '<td align="right"><b>恢复即时通讯：</b></td>';
					$tr_IM .= '<td><input type="checkbox" value="1" name="user[IMFlag]" id="IMFlag" /> 恢复该用户即时通讯（大蚂蚁）的使用权限</td>';
					$tr_IM .= '</tr>';
				}
				*/
				$val = $tr_IM;
			}
            $this->show->assign($key, $val);
        }
        $this->show->assign('password', passwordini);
        $this->show->assign('maildomain', maildomain);
        if ($_SESSION["COM_BRN_PT"] == 'dl') {
            $this->show->display('general_system_user_edituser');
        } else {
            $this->show->display('general_system_user_edituserext');
        }
        unset($edituser, $userinfo);
    }

    function c_update() {
        if ($_POST ['user']) {
            $_POST ['user'] ['NOT_LOGIN'] = $_POST ['user'] ['NOT_LOGIN'] ? $_POST ['user'] ['NOT_LOGIN'] : 0;
            $_POST ['user'] ['HAS_LEFT'] = $_POST ['user'] ['HAS_LEFT'] ? $_POST ['user'] ['HAS_LEFT'] : 0;
            $_POST ['user'] ['UpdateDT'] = date('Y-m-d H:is');
            $_POST ['user'] ['Updator'] = $_SESSION ['USER_ID'];
            $edituser = new model_general_system_user_edituser ();
          
            if ($_POST ['user'] ['HAS_LEFT'] == 1) {
                if ($edituser->stop_user($_POST ['userid'])) {
                    showmsg('设置《' . $_POST ['userid'] . '》离职成功！', 'self.parent.tb_remove();self.parent.show_page(1);', 'button');
                }else{
                	showmsg('设置《' . $_POST ['userid'] . '》离职失败！', 'self.parent.tb_remove();self.parent.show_page(1);', 'button');
                	
                }
            } else{
            	$MSG=$edituser->update($_POST ['userid']);
            $str='';
            if( $MSG&&is_array($MSG)){
            	  foreach ($MSG as $key => $val){
            	  	if($key=='EmailStop'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">禁止使用邮箱地址成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">禁止使用邮箱地址失败！</div>';
            	  		}
            	  	}
            	  	if($key=='EmailUse'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">恢复使用邮箱地址成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">恢复使用邮箱地址失败！</div>';
            	  		}
            	  	}
            	  	if($key=='EmailCreate'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建邮箱地址成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建邮箱地址失败！</div>';
            	  		}
            	  	}
            	  	
            	  	if($key=='DelIM'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">删除大蚂蚁帐号成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">删除大蚂蚁帐号失败！</div>';
            	  		}
            	  	}
            	  	if($key=='CreateIM'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建大蚂蚁帐号成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">创建大蚂蚁帐号失败！</div>';
            	  		}
            	  	}
            	  	if($key=='EditIM'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">同步大蚂蚁帐号成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">同步大蚂蚁帐号失败！</div>';
            	  		}
            	  	}
            	  	if($key=='user'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新用户信息成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新用户信息失败！</div>';
            	  		}
            	  	}
            	  	if($key=='ecard'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新通信录成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新通信录失败！</div>';
            	  		}
            	  	}
            	  	if($key=='hrms'){
            	  		if($val==1){
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新人事档案成功！</div>';
            	  		}else{
            	  			$str.='<div style="width:100%;height:40px;line-height:40px;">更新用人事档案失败！</div>';
            	  		}
            	  	}
            	  }
            }
             $str.= '<div style="width:100%;color:#F00 ">更新《' . $_POST ['userid'] . '》成功！</div>';
                showmsg($str, 'self.parent.tb_remove();self.parent.show_page(1);', 'button');
            }
        }
    }

    function c_save_func() {
        if ($_POST['userid']) {
            echo un_iconv($this->model_save_func());
        } else {
            echo un_iconv('非法提交！');
        }
    }

    function c_save_func_new() {
        if ($_GET ['userid']) {
            $user = new model_general_system_user_showfunc ();
            if ($user->update_user_func($_GET ['userid'])) {
                showmsg('更新用户权限成功！', 'self.parent.tb_remove();', 'button');
            } else {
                showmsg('更新用户权限失败！', 'self.parent.tb_remove();', 'button');
            }
        } else {
            showmsg('非法提交！');
        }
    }

    function c_edit_func_list_new() {
        $func = new model_general_system_user_showfunc ();
        $this->show->assign('list', $func->edit_func_list($_GET ['userid']));
        $this->show->assign('userid', $_GET ['userid']);
        $this->show->display('general_system_user_showfunc');
    }

    function c_edit_func_list() {
        $this->show->assign('userid', $_GET ['userid']);
        $this->show->display('general_system_user_show-priv');
    }

    function c_get_priv() {
        $ty = $_GET['ty'] ? $_GET['ty'] : 'user';
        $this->getPriv($_GET ['ckmu'], $_GET ['ck'], $ty);
    }

    function c_update_password() {
        $userid = $_GET ['userid'] ? $_GET ['userid'] : $_POST ['userid'];
        showmsg('您确定要重设置该用户密码吗？', "location.href='?model=user&action=set_password&userid=$userid&placeValuesBeforeTB_=savedValues'", 'button');
    }

    function c_set_password() {
        $userid = $_GET ['userid'] ? $_GET ['userid'] : $_POST ['userid'];
        $edituser = new model_general_system_user_edituser ();
        if ($edituser->edit_user_paw($userid)) {
            showmsg('重设置密码成功，请查本人查收邮件！', 'self.parent.tb_remove();self.parent.show_page(1);', 'button');
        }
    }

    /**
     * 树型
     * @param $dept_data
     * @param $parent_id
     * @param $level
     */
    function tree($dept_data, $parent_id, $level=1) {
        $str = '';
        if ($dept_data[$parent_id]) {
            foreach ($dept_data[$parent_id] as $rs) {
                $str.='<ul>';
                $str .='<li>' . $rs['DEPT_NAME'] . '</li>';
                if ($dept_data[$rs['DEPT_ID']]) {
                    $str .='<li>' . $this->tree($dept_data, $rs['DEPT_ID'], ($level + 1)) . '</li>';
                }
                $str.='</ul>';
            }
        }
        return $str;
    }

    /**
     * 选择用户
     */
    function c_select_user() {
        $dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
        $area = $_GET ['area'] ? $_GET ['area'] : $_POST ['area'];
        $jobs_id = $_GET ['jobs_id'] ? $_GET ['jobs_id'] : $_POST ['jobs_id'];
        $data = $this->GetUser($dept_id, $area, $jobs_id);
        //====部门===
        $dept = new model_system_dept();
        $dept_data = $dept->DeptTree();
        $dept_list = '';
        if ($dept_data) {
            foreach ($dept_data as $rs) {
                $parent_data = $dept->GetParent($rs['DEPT_ID'], null, true);
                $dept_class = '';
                if ($parent_data) {
                    foreach ($parent_data as $v) {
                        $dept_class .='dept_' . $v['DEPT_ID'] . ' ';
                    }
                }
                $dept_list .='<option class="' . $dept_class . ' dept_' . $rs['DEPT_ID'] . '" func="select_dept_son(' . $rs['DEPT_ID'] . ',this.checked);" level="' . ($rs['level'] - 1) . '" value="' . $rs['DEPT_ID'] . '">' . $rs['DEPT_NAME'] . '</option>';
            }
        }
        //===区域===
        $area_data = $this->GetArea();
        $area_list = '';
        if ($area_data) {
            foreach ($area_data as $rs) {
                $area_list .='<option value="' . $rs['ID'] . '">' . $rs['Name'] . '</option>';
            }
        }
        //===职位＝＝＝
        if ($dept_data) {
            
        }
        $this->show->assign('dept_list', $dept_list);
        $this->show->assign('area_list', $area_list);
        $this->show->display('general_system_user_select-user');
    }

    /**
     * 职位树型下拉
     */
    function c_select_jobs() {
        $dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
        $dept = new model_system_dept();
        $dept_data = $dept->DeptTree($dept_id);
        $jobs = new model_system_jobs();
    }

    function c_show_user($ajax = false) {
        $dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
        $dept_id = $dept_id ? explode(',', $dept_id) : '';
        $area = $_GET ['area'] ? $_GET ['area'] : $_POST ['area'];
        $jobs_id = $_GET ['jobs_id'] ? $_GET ['jobs_id'] : $_POST ['jobs_id'];
        $jobs_id = $jobs_id ? explode(',', $jobs_id) : '';
        $data = $this->GetUser($dept_id, $area, $jobs_id);
        $dept_user = $this->rest($data, 'dept_id');

        $dept = new model_system_dept();
        $dept_data = $dept->DeptList($dept_id);
        $new_data = array();
        foreach ($dept_data as $key => $rs) {

            if (($temp = $dept->GetParent_ID($rs['DEPT_ID'])) != false) {
                asort($temp);
                $new_data = array_merge($new_data, $temp, array($rs['DEPT_NAME'] => $rs['DEPT_ID']));
            }
        }
        $new_data = $dept->DeptTree($new_data);
        //var_dump($new_data);
        $str .='<script type="text/javascript" src="js/select_user.js"></script>';
        foreach ($new_data as $rs) {
            $str .= '<li style="list-style:none;margin-left:' . ($rs['level'] * 20) . 'px;">';
            $str .= '<input type="checkbox" onclick="select_dept_jobs(' . $rs['DEPT_ID'] . ',this.checked);" value=""> <b>' . $rs['DEPT_NAME'] . '</b>';
            $str .= '<a href="javascript:show_dept_jobs(' . $rs['DEPT_ID'] . ')">';
            $str .= '<img class="dept_img_' . $rs['DEPT_ID'] . '" src="images/work/' . (($dept_id && in_array($rs['DEPT_ID'], $dept_id)) ? 'sub' : 'plus') . '.png" border="0"/></a>';
            $jobs_out = array();
            if ($dept_user[$rs['DEPT_ID']]) {
                $str .='<div class="dept_' . $rs['DEPT_ID'] . '" style="display:' . ($dept_id ? '' : 'none') . '">';
                $jobs_user = $this->rest($dept_user[$rs['DEPT_ID']], 'jobs_id');
                if ($jobs_user) {
                    foreach ($jobs_user as $row) {
                        if ($row) {
                            foreach ($row as $val) {
                                if (!in_array($val['jobs_id'], $jobs_out)) {
                                    $jobs_out[] = $val['jobs_id'];
                                    $str .= '<ul style="margin:0;list-style: none;margin-left:50px;"">';
                                    $str .= '<span style="color:#0000ff;">';
                                    $str .= '<input type="checkbox" onclick="select_jobs_user(' . $val['jobs_id'] . ',this.checked)" />' . $val['jobs_name'];
                                    $str .='<a href="javascript:show_jobs_user(' . $val['jobs_id'] . ');">';
                                    $str .='<img class="jobs_img_' . $val['jobs_id'] . '" src="images/work/plus.png" border="0"/></a>';
                                    $str .='</span>';
                                    $str .= '<div class="jobs_' . $val['jobs_id'] . '" style="display:none;">';
                                }
                                $str .= '<li style="list-style:none;margin-left:30px;">';
                                $str .= '<input type="checkbox" title="' . $val['user_name'] . '" onclick="get_checked_user();" name="user_id[]" value="' . $val['user_id'] . '" />' . $val['user_name'] . '</li>';
                            }
                            $str .= '</div></ul>';
                        }
                    }
                }
                $str .='</div>';
            }
            $str .= '</li>';
        }
        if ($ajax) {
            return '<div id="user_tree" style=" font-size:12px;">' . $str . '</div>';
        } else {
            echo '<div id="user_tree" style=" font-size:12px;">' . $str . '</div>';
            ;
        }
    }

    /**
     * 显示部门
     */
    function c_show_dept() {
        $dept_id = $_GET ['dept_id'] ? $_GET ['dept_id'] : $_POST ['dept_id'];
        $dept = new model_system_dept();
        $dept_data = $dept->DeptTree();
        $dept_list = '';
        if ($dept_data) {
            foreach ($dept_data as $rs) {
                $dept_list .='<option level="' . ($rs['level'] - 1) . '" value="' . $rs['DEPT_ID'] . '">' . $rs['DEPT_NAME'] . '</option>';
            }
        }
        echo $dept_list;
    }

    /**
     * 显示部门
     */
    function c_egroup() {
        $this->tbl_name = 'sys_organizer_mailgroup';
        $email_Data = $this->findAll();
        if ($email_Data) {
            foreach ($email_Data as $rs => $val) {
                $list .='<li><input type="checkbox" title="' . $val['description'] . '" onclick="show_send_username();" name="send_userid[]" value="' . $val . '" />&nbsp;&nbsp;<span title="' . $val['description'] . '">' . $val['groupname'] . '(' . $val['description'] . ')</span></li>';
            }
        }
        $this->show->assign('list', $list);
        //$this->show->assign('area_list',$area_list);
        $this->show->display('general_system_user_email');
    }
    
    /**
	 * 显示部门
	 */
	function c_clickEmail()
	{
	   $email=trim($_POST['email']);
	   if($email){
	   	 $email=$email.'@dinglicom.com';
	   	 echo $this->model_click_email($email);
	   }   
	}
	
	function c_list(){
		global $func_limit;
		$depart = new includes_class_depart ();
        $jobs = new includes_class_jobs ();
        $this->show->assign('addUser',$func_limit['新增权限']?'<a class="mini-button" iconCls="icon-add"  onclick="addUser()">添加用户</a>':'');
		$this->show->assign('proUser',$func_limit['临聘管理']?'<a class="mini-menubutton" plain="false" iconCls="icon-admin" menu="#popupMenu">临聘管理</a>':'');
        $this->show->assign('rightJson',  $func_limit['操作管理']);
        $this->show->assign('depart_select', $depart->depart_selects());
        $this->show->assign('jobs_select', $jobs->jobs_select());
        $this->show->display('general_system_user_manageList');
	}
	function c_importUser(){
		if ($_POST) {
			$msgI=$this->model_generalSystemImportUser();
			if($msgI['msg']==2){
				if($msgI['err']&&is_array($msgI['err'])){
					foreach($msgI['err'] as $key =>$val){
						$str.=$val.'导入失败！<br/>';
					}
				}else{
					$str='导入成功！';
				}
	       	  showmsg ( $str, 'parent.CloseOpen();', 'button' );
	        }else{
	       	  showmsg ( '导入失败！', 'parent.CloseOpen();', 'button' );
	        }
		} else {
			$this->show->display ( 'general_system_user_importExcel');
		}
		
	}
	function c_listData(){
		echo $this->model_generalSystemUserList();
	}
	
	function c_deptData(){
		echo $this->model_generalSystemUserGetdeptData();
	}
	function c_jobData(){
		echo $this->model_generalSystemUserGetJobData();
	}
	function c_userTypeData(){
		echo $this->model_generalSystemUserGetuserTypeData();
	}
	 /* 导出员工数据
	 * Enter description here ...
	 */
	function c_exportExcel ( )
	{
	   $this->model_list_exportExcel();
    }

}

?>