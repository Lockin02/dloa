<?php

class controller_info_new extends model_info_new {

    public $show;

    function __construct() {
        parent::__construct();
        $this->show = new show ();
        $this->show->path = 'info_new_';
        set_time_limit(300);
    }

    /**
     * 公告列表
     */
    function c_index() {
        $keywords = $_GET ['keywords'] ? $_GET ['keywords'] : $_POST ['keywords'];
        $start_date = $_GET ['start_date'] ? $_GET ['start_date'] : $_POST ['start_date'];
        $itype = $_GET ['itype'] ? $_GET ['itype'] : $_POST ['itype'];
        $str = '<select name="itype" id="itype">';
        $str .='<option value="">全选</option>';
        if ($this->itype) {
            foreach ($this->itype as $key=>$val) {
                if($itype==$key)
                    $str .= '<option  value="' . $key . '" selected>' . $val . '</option>';
                else
                    $str .= '<option  value="' . $key . '">' . $val . '</option>';
            }
        }
        $str.='</select>';
        $this->show->assign('itype', $str);
        $this->show->assign('keywords', $keywords);
        $this->show->assign('start_date', $start_date);
        $this->show->assign('list', $this->model_index());
        $this->show->display('index');
    }

    /**
     * 显示公告
     */
    function c_showinfo() {
        $this->tbl_name = 'info_new';
        $row = $this->find(array(
            'id' => $_GET['id'],
            'rand_key' => $_GET['rand_key']
                ), null, '*');
        if ($row) {
            foreach ($row as $key => $val) {
                switch ($key) {
                    case 'start_date' :
                        $val = $val ? date('Y-m-d', $val) : '';
                        break;
                    case 'filename_str' :
                        if ($val) {
                            $filename_arr = explode(',', $val);
                            foreach ($filename_arr as $v) {
                                $filename_list .= ' <br/><a href="' . NEW_FILE_DIR . $row['file_path'] . '/' . rawurlencode(mb_convert_encoding($v, 'utf-8', 'gb2312')) . '" title="点击打开查看该附件！" target="_blank">' . $v . '</a>';
                            }
                            $val = $filename_list;
                        }
                        break;
                }
                if($key=='type'){
                    $this->show->assign('itype', $this->itype[$val]);
                }else{
                    $this->show->assign($key, $val);
                }
                
            }
            $this->show->display('showinfo');
        }
    }

    /**
     * 显示访问记录
     */
    function c_show_raad() {
        $this->tbl_name = 'info_new';
        $row = $this->find(array(
            'id' => $_GET['id'],
            'rand_key' => $_GET['rand_key']
                ), null, 'title');
        $this->show->assign('title', $row['title']);
        $this->show->assign('list', $this->model_show_read($_GET['id'], $_GET['rand_key']));
        $this->show->display('show-read');
    }

    /**
     * 我发布的公告列表
     */
    function c_showlist() {
        $this->show->assign('list', $this->model_list());
        $this->show->display('list');
    }

    /**
     * 审核公告列表
     */
    function c_audit_list() {
        $this->show->assign('list', $this->model_audit_list());
        $this->show->display('audit-list');
    }

    /**
     * 显示添加
     *
     */
    function c_add() {
        global $func_limit;
        $gl = new includes_class_global ();
        /* $rs = $gl->GetUserinfo ( $_SESSION['USER_ID'], array(

          'dept_name',
          'edept_name',
          'assistantdept'
          ) );
          foreach ( $rs as $key=>$val )
          {
          if ($val)
          {
          if ($key=='assistantdept')
          {
          $dept = $gl->GetDept(trim($val,','));
          foreach ($dept as $row)
          {
          $str .= '<input type="radio" name="nametype" value="' . $row['DEPT_NAME'] . '" />' . $row['DEPT_NAME'] . ' ';
          }
          }else{
          $str .= '<input type="radio" name="nametype" value="' . $val . '" />' . $val . ' ';
          }
          }
          } */
        $str = '<select name="itype" id="itype">';
        if ($this->itype) {
            foreach ($this->itype as $key=>$val) {
                $str .= '<option  value="' . $key . '">' . $val . '</option>';
            }
        }
        $str.='</select>';
        $this->show->assign('itype', $str);
        $this->show->assign('content', $this->get_edit_temp_content());
        $this->show->display('add');
    }

    /**
     * 保存添加
     */
    function c_save_add() {
        $FlowID = $this->model_save_data('add');
        if ($FlowID) {
            showmsg('发布成功！', 'self.parent.location.reload();', 'button');
        } else {
            showmsg('发布失败！');
        }
    }

    /**
     * 保存修改
     */
    function c_save_edit() {
        $FlowID = $this->model_save_data('edit');
        if ($FlowID) {
            showmsg('修改成功！', 'self.parent.location.reload();', 'button');
        } else {
            showmsg('修改失败！');
        }
    }

    /**
     * 显示修改
     */
    function c_show_edit() {
        $button = '<div style="text-align: center;"><input type="button" onclick="tb_remove();" value=" 确定 "/></div>';
        $this->tbl_name = 'info_new';
        $row = $this->find(array(
            'id' => $_GET['id'],
            'rand_key' => $_GET['rand_key']
                ), null, '*');
        if ($row) {
            foreach ($row as $key => $val) {
                if ($key == 'start_date') {
                    $val = date('Y-m-d', $val);
                } elseif ($key == 'content') {
                    $val = new_htmlspecialchars($val);
                }
                if($key=='type'){
                    $str = '<select name="itype" id="itype">';
                    if ($this->itype) {
                        foreach ($this->itype as $ikey=>$ival) {
                            if($val==$ikey)
                                $str .= '<option  value="' . $ikey . '" selected>' . $ival . '</option>';
                            else
                                $str .= '<option  value="' . $ikey . '">' . $ival . '</option>';
                        }
                    }
                    $this->show->assign('itype', $str);
                }else{
                    $this->show->assign($key, $val);
                }
                
            }
            if ($row['filename_str']) {
                $filename_arr = explode(',', $row['filename_str']);
                foreach ($filename_arr as $v) {
                    $filename_list .= ' <input type="checkbox" name="file[]" value="' . $v . '" /><a href="' . NEW_FILE_DIR . $row['file_path'] . '/' . rawurlencode(mb_convert_encoding($v, 'utf-8', 'gb2312')) . '" title="点击打开查看该附件！" target="_blank">' . $v . '</a><br>';
                }
            }
            $filename_list = $filename_list ? $filename_list . ' <input type="button" onclick="del_file(' . $row['id'] . ')" value="删除选中附件"/>' : $filename_list;
            $this->show->assign('radio', $radio);
            $this->show->assign('file_list', $filename_list);
            $this->show->display('edit');
        } else {
            showmsg('非法参数！');
        }
    }

    /**
     * 审核公告
     */
    function c_audit() {
        $url.='actTo=ewfExam';
        $url.="&billId=" . $_GET['id'];
        $url.="&taskId=" . $_GET['tid'];
        $url.="&spid=" . $_GET['sid'];
        $url.="&rand_key=" . $_GET['rand_key'];
        $url.="&sendToURL=?model=info_notice&action=spreload";
        $this->show->assign('aurl', $url);
        $this->show->display('applyer');
    }

    /**
     * 审核公告
     */
    function c_applyerinfo() {
        $url.='actTo=ewfView';
        $url.="&billId=" . $_GET['id'];
        $url.="&taskId=" . $_GET['tid'];
        $url.="&spid=" . $_GET['sid'];
        $url.="&rand_key=" . $_GET['rand_key'];
        $url.="&sendToURL=?model=info_notice&action=spreload";
        $this->show->assign('aurl', $url);
        $this->show->display('applyer');
    }

    function c_detailaudit() {
        if ($_GET['id'] && $_GET['rand_key']) {
            $this->tbl_name = 'info_new';
            $data = $this->find(array(
                'id' => $_GET['id'],
                'rand_key' => $_GET['rand_key']
                    ), null, '*');
            if ($data) {
                $gl = new includes_class_global();
                if ($data['dept_id_str']) {
                    $arr = $gl->GetDept(trim($data['dept_id_str'], ','));
                    $dept_str = '';
                    if ($arr) {
                        foreach ($arr as $key => $val) {
                            $dept_str .=$val['DEPT_NAME'] . '、';
                        }
                    }
                    $dept_str = '<b>指定部门：</b>' . trim($dept_str, '、');
                }

                if ($data['area_id_str']) {
                    $arr = $gl->get_area(explode(',', $data['area_id_str']));
                    $area_str = '';
                    if ($arr) {
                        foreach ($arr as $key => $val) {
                            $area_str .= $val . '、';
                        }
                    }
                    $area_str = '<b>指定区域：</b>' . trim($area_str, '、');
                }

                if ($data['jobs_id_str']) {
                    $arr = $gl->GetJobs(trim($data['jobs_id_str'], ','));
                    $jobs_str = '';
                    if ($arr) {
                        foreach ($arr as $key => $val) {
                            $jobs_str .= $val['name'] . '、';
                        }
                    }
                    $jobs_str = "<b>指定职位：</b>" . $jobs_str;
                }

                if ($data['user_check'] && $data['user_id_str']) {
                    $arr = $gl->GetUser(explode(',', $data['user_id_str']));
                    if ($arr) {
                        $user_str = '';
                        foreach ($arr as $key => $val) {
                            $user_str .=$val['USER_NAME'] . '、';
                        }
                        $user_str = '<b>指定职员：</b>' . $user_str;
                    }
                }
                $this->show->assign('limit', ($dept_str ? $dept_str : '所有部门') . ' <hr />' . ($area_str ? $area_str : '所有区域') . ' <hr /> ' . ($jobs_str ? $jobs_str : '所有职位') . ' <hr /> ' . ($user_str ? $user_str : '所有职员'));
                foreach ($data as $key => $val) {
                    switch ($key) {
                        case 'start_date' :
                            $val = date('Y-m-d', $val);
                            break;
                        case 'filename_str' :
                            $arr = explode(',', $val);
                            foreach ($arr as $v) {
                                $temp .= '<a href="' . NEW_FILE_DIR . $data['file_path'] . '/' . rawurlencode(mb_convert_encoding($v, 'utf-8', 'gb2312')) . '">' . $v . '</a> ';
                            }
                            $val = $temp;
                            break;
                        case 'send_email' :
                            $val = $val ? '是' : '否';
                            break;
                    }
                    $this->show->assign($key, $val);
                }
                $this->show->display('audit-info');
            } else {
                showmsg('非法参数！');
            }
        } else {
            showmsg('非法参数！');
        }
    }

    /**
     * 保存审核操作
     */
    function c_set_audit() {
        $url.='actTo=ewfExam';
        $url.="&billId=" . $_GET['id'];
        $url.="&taskId=" . $_GET['tid'];
        $url.="&spid=" . $_GET['sid'];
        $url.="&sendToURL=?model=info_notice&action=spreload";
        $url.="&detailUrl=?model=info_notice&action=detailaudit";
        $this->show->assign('aurl', $url);
        $this->show->display('applyer');

        die();
        if ($this->model_save_audit($_GET['id'], $_GET['rand_key'])) {
            showmsg('操作成功！', 'self.parent.location.reload();', 'button');
        } else {
            showmsg('操作失败，请与管理员联系！', 'self.parent.location.reload();', 'button');
        }
    }

    /**
     * 显示公告详细
     */
    function c_show_info() {
        if ($_GET['id'] && $_GET['rand_key']) {
            $this->tbl_name = 'info_new';
            $data = $this->find(array(
                'id' => $_GET['id'],
                'rand_key' => $_GET['rand_key']
                    ), null, '*');
            $this->show->assign('limit', ($data['dept_id_str'] ? '指定部门' : '所有部门') . ' / ' . ($data['area_id_str'] ? '指定区域' : '所有区域') . ' / ' . ($data['jobs_id_str'] ? '指定职位' : '所有职位') . ' / ' . ($data['user_check'] ? '指定职员' : '所有职员'));
            if ($data) {
                foreach ($data as $key => $val) {
                    switch ($key) {
                        case 'start_date' :
                            $val = date('Y-m-d', $val);
                            break;
                        case 'filename_str' :
                            if ($val) {
                                $arr = explode(',', $val);
                                foreach ($arr as $v) {
                                    $temp .= '<a href="' . NEW_FILE_DIR . $data['file_path'] . '/' . rawurlencode(mb_convert_encoding($v, 'utf-8', 'gb2312')) . '">' . $v . '</a> ';
                                }
                                $val = $temp;
                            }
                            break;

                        case 'send_email' :
                            $val = $val ? '是' : '否';
                            break;
                        case 'audit' :
                            $val = $val == 1 ? '待审核' : ($val == 2 ? '被打回' : '已通过审核');
                            break;
                        case 'audit_date' :
                            $val = $val ? date('Y-m-d', $val) : '尚未审核';
                            break;
                        case 'notse' :
                            $val = $data['audit'] == 2 ? '<tr><td>打回理由：</td><td align="left">' . $val . '</td></tr>' : '';
                    }
                    $this->show->assign($key, $val);
                }
                $this->show->display('show-info');
            } else {
                showmsg('非法参数！');
            }
        }
    }

    function c_applered() {
        $spid = $_GET['spid'];
        $start = trim($_GET['start']);
        if ($spid && $start == 'ok') {

            $sql = "	SELECT a.*
					FROM  notice as a
								left join wf_task as e on a.id=e.pid 
								left join flow_step_partent as f on e.task=f.Wf_task_ID
					WHERE 1 and f.id='$spid' and Result='ok'
					GROUP BY a.id ORDER BY a.edit_time desc,a.date desc";
            $rs = $this->get_one($sql);
            if ($rs ['effect'] == 1 && $rs['send_email'] == 1) {
                //群发邮件
                $gl = new includes_class_global ();
                $enmai_server = new includes_class_sendmail ();
                $oaurl .= '<hr /><br/><font size="2" >';
                $oaurl .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
                $oaurl .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font>';
                $email_arr = array();
                $where = ' where has_left=0 and del=0 ';
                $where .= $rs ['dept_id_str'] ? " and dept_id in (" . $rs ['dept_id_str'] . ") " : '';
                $where .= $rs ['area_id_str'] ? " and area in (" . $rs ['area_id_str'] . ") " : '';
                $where .= $rs ['jobs_id_str'] ? " and jobs_id in(" . $rs ['jobs_id_str'] . ") " : '';
                if ($rs ['user_id_str']) {
                    $arr = explode(',', $rs ['user_id_str']);
                    $user_id = array();
                    foreach ($arr as $val) {
                        $user_id [] = sprintf("'%s'", $val);
                    }
                    $where .= " and user_id in(" . implode(',', $user_id) . ") ";
                }
                $query = $this->query("select email,dept_id from user $where");
                $dept_arr = array();
                while (($row = $this->fetch_array($query)) != false) {
                    $email_arr [] = $row ['email'];
                    $dept_arr [] = $row ['dept_id'];
                }
                if ($dept_arr) {
                    $query = $this->query("select MajorId,ViceManager from department where dept_id in(" . implode(',', $dept_arr) . ")");
                    $lead_id = array();
                    $lead_email = array();
                    while (($ra = $this->fetch_array($query)) != false) {
                        $lead_id = $ra ['MajorId'] ? array_merge($lead_id, explode(',', $ra ['MajorId'])) : $lead_id;
                        $lead_id = $ra ['ViceManager'] ? array_merge($lead_id, explode(',', $ra ['ViceManager'])) : $lead_id;
                    }
                    if ($lead_id) {
                        $lead_email = $gl->get_email(array_unique($lead_id));
                        $email_arr = array_merge($email_arr, $lead_email);
                    }
                }
                $body .= '<P>您好！</P>';
                $body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;' . ($rs ['audit_date'] ? '公告修改提示!' : '您有新的公告通知！') . '</P>';
                $body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;标题：' . $rs ['title'] . '</P>';
                $body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;发布人：' . $rs ['nametype'] . '</P>';
                $body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;附件：';
                $body .= ($rs ['filename_str'] ? '有</p>' : '无</p>');
                $body .= '<hr />' . $rs ['content'] . '<p>请登录OA查看详细！</p>';
                if ($_SESSION['COM_BRN_PT'] == 'dl') {
                    $this->EmialTask('公告：' . mysql_escape_string($rs ['title']), mysql_escape_string($body . $oaurl), array_unique($email_arr)); //交给群发邮件任务系统
                } else {
                    $enmai_server->send('公告：' . mysql_escape_string($rs ['title']), mysql_escape_string($body . $oaurl), array_unique($email_arr));
                }
            }
        }
        echo "<script language='javascript'>self.parent.location.reload();</script>";
    }

    function c_spreload() {
        echo "<script language='javascript'>self.parent.location.reload();</script>";
    }

}

?>