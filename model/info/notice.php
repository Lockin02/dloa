<?php
class model_info_notice extends model_base
{
	public $audit = 0;
	function __construct()
	{
		parent::__construct ();
		//echo noticeext;
	}
	/**
	 * 列表
	 */
	function model_index()
	{
		$keywords = $_GET ['keywords'] ? $_GET ['keywords'] : $_POST ['keywords'];
		$start_date = $_GET ['start_date'] ? $_GET ['start_date'] : $_POST ['start_date'];
		$where = $keywords ? " and (a.title like '%$keywords%' or nametype like '%$keywords%')" : '';
		$where .= $start_date ? ' and a.start_date >=' . strtotime ( $start_date ) . ' and a.start_date <=' . strtotime ( $start_date . ' 23:59:59' ) : '';
		if (! $this->num)
		{
			$rs = $this->_db->get_one ( "
										select 
											count(0) as num 
										from 
											notice as a
											left join (select * from department group by dept_id) as b on b.dept_id in (a.dept_id_str)
										where 
											a.nametype!='admin'
											and a.nametype!='系统管理'
											and a.audit=0 
											and (a.effect=1 or a.start_date < " . time () . ") 
											and a.effect!=2 
											and ( 
													find_in_set('" . $_SESSION ['USER_ID'] . "',a.user_id_str)
                                                	or ( 
                                                			(find_in_set('" . $_SESSION ['DEPT_ID'] . "',a.dept_id_str) or a.dept_id_str is null or a.dept_id_str='')
                                                			and 
                                                			(find_in_set('" . $_SESSION ['AREA'] . "',a.area_id_str) or a.area_id_str is null or a.area_id_str='')
                                                			and 
                                                			(find_in_set('" . $_SESSION ['USER_JOBSID'] . "',a.jobs_id_str) or a.jobs_id_str is null or a.jobs_id_str='')
                                                			and 
                                                			(find_in_set('" . $_SESSION ['USER_ID'] . "',a.user_id_str) or a.user_id_str is null or a.user_id_str='')
                                                			
                                                		)
                                                	or ( find_in_set('" . $_SESSION ['USER_ID'] . "',b.ViceManager) or find_in_set('" . $_SESSION ['USER_ID'] . "',b.MajorId) )
                                                )
											" . ($where ? $where : '') . "
											" );
			$this->num = $rs ['num'];
		}
		if ($this->num > 0)
		{
		               $sql="
										select  
											a.* 
										from 
											notice as a
											left join (select * from department group by dept_id) as b on b.dept_id in (a.dept_id_str)
										where 
											a.nametype!='admin'
											and a.nametype!='系统管理'
											and a.audit=0 
											and (a.effect=1 or a.start_date < " . time () . ") 
											and a.effect!=2 
											and ( 
													find_in_set('" . $_SESSION ['USER_ID'] . "',a.user_id_str)
                                                	or ( 
                                                			(find_in_set('" . $_SESSION ['DEPT_ID'] . "',a.dept_id_str) or a.dept_id_str is null or a.dept_id_str='')
                                                			and 
                                                			(find_in_set('" . $_SESSION ['AREA'] . "',a.area_id_str) or a.area_id_str is null or a.area_id_str='')
                                                			and 
                                                			(find_in_set('" . $_SESSION ['USER_JOBSID'] . "',a.jobs_id_str) or a.jobs_id_str is null or a.jobs_id_str='')
                                                			and 
                                                			(find_in_set('" . $_SESSION ['USER_ID'] . "',a.user_id_str) or a.user_id_str is null or a.user_id_str='')
                                                			
                                                		)
                                                	or ( find_in_set('" . $_SESSION ['USER_ID'] . "',b.ViceManager) or find_in_set('" . $_SESSION ['USER_ID'] . "',b.MajorId) )
                                                )
											" . ($where ? $where : '') . "
										order by edit_time desc , date desc
										limit $this->start," . pagenum . "
									 " ;
			$query = $this->_db->query ($sql);
			while ( ($rs = $this->_db->fetch_array ( $query )) != false )
			{
				$str .= '
						<tr>
							<td>' . $rs ['id'] . '</td>
							<td align="left"><a href="?model=info_notice&action=showinfo&id=' . $rs ['id'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="' . $rs ['title'] . '">' . $rs ['title'] . '</a></td>
							<td>' . $rs ['nametype'] . '</td>
							<td>' . date ( 'Y-m-d', $rs ['start_date'] ) . '</td>
							<td>' . $rs ['num'] . '</td>
							<td><a href="?model=info_notice&action=showinfo&id=' . $rs ['id'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="' . $rs ['title'] . '">查看内容</a></td>
						</tr>
				';
			}
			if ($this->num > pagenum)
			{
				$showpage = new includes_class_page ();
				$showpage->show_page ( array (
												'total' => $this->num, 
												'perpage' => pagenum ) );
				$showpage->_set_url ( 'num=' . $this->num . '&keydowds=' . $keywords . '&start_date=' . $start_date );
				$str .= '<tr><td colspan="10">' . $showpage->show ( 6 ) . '</td></tr>';
			}
			return $str;
		} else
		{
			return false;
		}
	}
	
	/**
	 * 通告列表S
	 *
	 */
	function model_list()
	{

		if (! $this->num)
		{
			$rs = $this->_db->get_one ( "select count(0) as num from notice where " . ($_SESSION ['USER_ID'] == 'admin' ? 'id > 1' : "send_userid = '" . $_SESSION ['USER_ID'] . "'") . "" );
			$this->num = $rs ['num'];
		}
		if ($this->num > 0)
		{
			$sql='	SELECT a.*,b.user_name,f.result,f.id as sid,e.task as tid
					FROM  notice as a
								left join user as b on b.user_id=a.send_userid
								left join department as c on c.dept_id=b.dept_id
								left join wf_task as e on a.id=e.pid  and  e.name="公告审批"
								left join flow_step_partent as f on e.task=f.Wf_task_ID
					WHERE   '. ($_SESSION ['USER_ID'] == "admin" ? "a.id > 1" : "send_userid = '" .$_SESSION ['USER_ID'] . "'").' 
					GROUP BY a.id ORDER BY a.edit_time desc,a.date desc
					LIMIT '.$this->start.','. pagenum;
			$query = $this->_db->query ( $sql );
				while ( ($rs = $this->_db->fetch_array ( $query )) != false )
			{
				$status = '';
				$status_str = '';
				if ($rs ['audit'] == 1)
				{
					if($rs ['result']=='ok'){
						$status = '<span class="purple">审批中</span>';
					}else{
						$status = '<span class="purple">编辑中</span>';
					}
					
				} elseif ($rs ['audit'] == 2)
				{
					$status = '<span>被打回</span>';
				} elseif ($rs ['effect'] == 2)
				{
					$status = '<span>已关闭</span>';
					$status_str = '<a href="javascript:start(' . $rs ['id'] . ')" id="stop_' . $rs ['id'] . '"><span>恢复生效</span></a>';
				} elseif ($rs ['effect'] == 1)
				{
					$status = '已生效';
					$status_str = '<a href="javascript:stop(' . $rs ['id'] . ')" id="stop_' . $rs ['id'] . '">停止生效</a>';
				} elseif ($rs ['start_date'] < time ())
				{
					$status = '已生效';
					$status_str = '<a href="javascript:stop(' . $rs ['id'] . ')" id="stop_' . $rs ['id'] . '">停止生效</a>';
				} else
				{
					$status = '<b>未生效</b>';
					$status_str = '<a href="javascript:start(' . $rs ['id'] . ')" id="stop_' . $rs ['id'] . '">立即生效</a>';
				}
				$str .= '
				<tr id="tr_' . $rs ['id'] . '">
					<td>' . $rs ['id'] . '</td>
					<td>' . $rs ['title'] . '</td>
					<td>' . $rs ['user_name'] . '</td>
					<td>' . ($rs ['dept_id_str'] ? '指定部门' : '所有部门') . ' / ' . ($rs ['area_id_str'] ? '指定区域' : '所有区域') . ' / ' . ($rs ['jobs_id_str'] ? '指定职位' : '所有职位') . ' / ' . ($rs ['user_id_str'] ? '指定职员' : '所有职员') . '</td>
					<!--<td>' . date ( 'Y-m-d', $rs ['start_date'] ) . '</td>-->
					<td id="status_' . $rs ['id'] . '">' . $status . '</td>
					<td>' . $rs ['num'] . '</td>
					<td>' . date ( 'Y-m-d', $rs ['date'] ) . '</td>
					<td align="left"> 
					<a href="?model=info_notice&action=show_info&id=' . $rs ['id'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="' . $rs ['title'] . '">查看内容</a>'
					.($rs ['audit'] == 1&&$rs ['result']=='ok'?'':' | <a href="?model=info_notice&action=show_edit&id=' . $rs ['id'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="修改公告：' . $rs ['title'] . '">修改</a>')
					.(' | <a href="?model=info_notice&action=applyerinfo&id=' . $rs ['id'] .'&tid=' . $rs ['tid'] . '&sid=' . $rs ['sid'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="' . $rs ['title'] . '审批情况">审批情况</a> ');
				if ($rs ['audit'] == 0)
				{
					$str .= ' | <a href="javascript:top(' . $rs ['id'] . ')">置顶</a>';
					$str .= $status_str ? ' | ' . $status_str : '';
					$str .= $rs ['num'] ? ' | <a href="?model=info_notice&action=show_raad&id=' . $rs ['id'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="查看 ' . $rs ['title'] . ' 的阅读记录">阅读记录</a>' : '';
				}
				$str .= '</tr>';
			}
			if ($this->num > pagenum)
			{
				$showpage = new includes_class_page ();
				$showpage->show_page ( array (
												
												'total' => $this->num, 
												'perpage' => pagenum ) );
				$showpage->_set_url ( 'num=' . $this->num );
				$str .= '<tr><td colspan="10">' . $showpage->show ( 6 ) . '</td></tr>';
			}
			return $str;
		}
	}
	/**
	 * 访问记录
	 * @param $id
	 * @param $rand_key
	 */
	function model_show_read($id = null, $rand_key = null)
	{
		$id = $id ? $id : ($_GET ['id'] ? $_GET ['id'] : $_POST ['id']);
		$rand_key = $rand_key ? $rand_key : ($_GET ['rand_key'] ? $_GET ['rand_key'] : $_POST ['rand_key']);
		$query = $this->query ( "
								select 
									a.title,b.num as countnum,b.date,c.user_name,d.dept_name
								from 
									notice as a 
									left join notice_read as b on b.tid=a.id 
									left join user as c on c.user_id=b.userid 
									left join department as d on d.dept_id=c.dept_id
								where 
									a.id=$id and a.rand_key='$rand_key'
			" );
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			if ($rs ['user_name'])
			{
				$str .= '
					<tr>
						<td>' . $rs ['user_name'] . '</td>
						<td>' . $rs ['dept_name'] . '</td>
						<td>' . $rs ['countnum'] . '</td>
						<td>' . date ( 'Y-m-d H:i:s', $rs ['date'] ) . '</td>
					</tr>
			';
			}
		}
		return $str;
	
	}
	
	/**
	 * 审核列表
	 */
	function model_audit_list()
	{
		
		if (! $this->num)
		{
			$rs = $this->_db->get_one ( "select count(0) as num from
					notice as a
					left join user as b on b.user_id=a.send_userid
				    left join wf_task as c on (a.id=c.pid AND c.name='公告审批')
				    left join flow_step_partent as d on c.task=d.Wf_task_ID 
				    where   find_in_set('".$_SESSION ['USER_ID']."',d.User)" ) ;
			$this->num = $rs ['num'];
		}
		if ($this->num > 0)
		{
			$query = $this->_db->query ( "
				select
					a.*,b.user_name,d.id as sid,c.task as tid,d.Result,d.Flag
				from
					notice as a
					left join user as b on b.user_id=a.send_userid
				    left join wf_task as c on (a.id=c.pid AND c.name='公告审批')  
				    left join flow_step_partent as d on c.task=d.Wf_task_ID
				where   find_in_set('".$_SESSION ['USER_ID']."',d.User) 
				group by  a.id order by date DESC, a.effect desc, a.audit desc, a.date desc
				limit $this->start," . pagenum . "
			" );
		 
			while ( ($rs = $this->_db->fetch_array ( $query )) != false )
			{
				$str .= '
						<tr>
							<td>' . $rs ['id'] . '</td>
							<td>' . $rs ['title'] . '</td>
							<td>' . $rs ['user_name'] . '</td>
							<td>' . ($rs ['dept_id_str'] ? '指定部门' : '所有部门') . ' / ' . ($rs ['area_id_str'] ? '指定区域' : '所有区域') . ' / ' . ($rs ['jobs_id_str'] ? '指定职位' : '所有职位') . ' / ' . ($rs ['user_id_str'] ? '指定职员' : '所有职员') . '</td>
							<!--<td>' . date ( 'Y-m-d', $rs ['start_date'] ) . '</td>-->
							<td>' . ($rs ['audit'] == 0 &&$rs ['Result']=='ok'? '<span>已审核</span>' : ($rs ['audit'] == 2 &&$rs ['Result']=='no'? '<b>已打回</b>' : '待审核')) . '</td>
							<td>' . date ( 'Y-m-d', $rs ['date'] ) . '</td>
							<td>' . ($rs ['audit_date'] ? date ( 'Y-m-d', $rs ['audit_date'] ) : '待审核') . '</td>
							<td>' . ($rs ['audit'] == 1 &&($rs ['Result']=='no'||$rs ['Flag']==0)? '<a href="?model=info_notice&action=audit&id=' . $rs ['id'] . '&tid=' . $rs ['tid'] . '&sid=' . $rs ['sid'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="审核 《' . $rs ['title'] . '》">审核操作</a>  |' : '') . ' <a href="?model=info_notice&action=show_info&id=' . $rs ['id'] . '&rand_key=' . $rs ['rand_key'] . '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800" class="thickbox" title="' . $rs ['title'] . '">查看内容</a></td>
						</tr>				
					';
			}
			if ($this->num > pagenum)
			{
				$showpage = new includes_class_page ();
				$showpage->show_page ( array (
												
												'total' => $this->num, 
												'perpage' => pagenum ) );
				$showpage->_set_url ( 'num=' . $this->num );
				$str .= '<tr><td colspan="10">' . $showpage->show ( 6 ) . '</td></tr>';
			}
			return $str;
		}
	}
	
	/**
	 * 保存添加或修改的数据
	 * @$type 　add or edit
	 */
	function model_save_data($type = 'add')
	{
		global $func_limit;
		if (intval ( $_GET ['id'] ) && $_GET ['rand_key'] && $type == 'edit')
		{
			$row = $this->_db->get_one ( "select * from notice where id=" . $_GET ['id'] . " and rand_key='" . $_GET ['rand_key'] . "'" );
			if (! $row)
				showmsg ( '非法参数！' );
		}
		//=============上传附件开始========================
		if ($_FILES ['upfile'])
		{
			$filename_str = '';
			$file_path = $row ['file_path'] ? $row ['file_path'] : md5 ( time () . rand ( 0, 999999 ) );
			foreach ( $_FILES ['upfile'] ['tmp_name'] as $key => $val )
			{
				if ($val)
				{
					$tempname = $_FILES ['upfile'] ['tmp_name'] [$key];
					$filename = $_FILES ['upfile'] ['name'] [$key];
					if (! is_dir ( WEB_TOR . NOTICE_FILE_DIR . $file_path ))
					{
						if (! mkdir ( WEB_TOR . NOTICE_FILE_DIR . $file_path ))
						{
							showmsg ( '上传附件失败，请与管理员联系！！' );
						}
					}
					if (move_uploaded_file ( str_replace ( '\\\\', '\\', $tempname ), WEB_TOR . NOTICE_FILE_DIR . $file_path . '/' . $filename ))
					{
						$filename_str .= $filename . ',';
					} else
					{
						showmsg ( '上传附件失败，请与管理员联系！' );
					}
				}
			}
		}
		//=================上传附件结束======================
		$filename_str = $filename_str ? rtrim ( $filename_str, ',' ) : '';
		$title = trim ( $_POST ['title'] );
		$nametype = ($_POST ['nametype'] == '1' ? $_SESSION ['USERNAME'] : $_POST ['nametype']);
		$start_date = $_POST ['start_date'] ? strtotime ( $_POST ['start_date'] . ' 00:00:00' ) : time ();
		$send_email = $_POST ['send_email'];
		$content = $_POST ['editorValue'];
		$inst = $_POST ['inst'];
		$dept_list_str = '';
		$jobs_list_str = '';
		$area_list_str = '';
		$user_list_str = '';
		if ($_POST ['user_check'] == 1)
		{
			$dept_list_str = $_POST ['dept_id_str'];
			$jobs_list_str = $_POST ['jobs_id_str'];
			$area_list_str = $_POST ['area_id_str'];
			$user_list_str = $_POST ['user_id_str'];
			if (! $user_list_str)
			{
				$where = $area_list_str ? " and a.area in($area_list_str)" : '';
				$where .= $jobs_list_str ? " and c.id in($jobs_list_str)" : '';
				$where .= $dept_list_str ? " and a.dept_id in ($dept_list_str)" : '';
			} else
			{
				$user_arr = explode ( ',', $user_list_str );
				foreach ( $user_arr as $val )
				{
					$user_id_str .= "'$val',";
				}
				$user_id_str = rtrim ( $user_id_str, ',' );
				$where = " and a.user_id in ($user_id_str)";
			}
		}
		$query = $this->_db->query ( "
			select 
				a.user_id,a.dept_id,a.user_name,a.email,b.depart_x,c.id
			 from 
				user as a
				left join department as b on b.dept_id=a.dept_id
				left join user_jobs as c on c.id in(a.jobs_id)
			where a.HAS_LEFT!='1' and a.del!=1 and a.localizers<>2  and a.userType<>2 $where 
			" );

		$email_arr = array ();
		$user_arr = array ();
		$dept_arr = array ();
		$jobs_arr = array ();
		while ( ($rs = $this->_db->fetch_array ( $query )) != false )
		{
			if ($rs ['dept_id'])
			{
				$dept_arr [] = $rs ['dept_id'];
			}
			$user_arr [] = trim ( $rs ['user_id'] );
			$email_arr [] = trim ( $rs ['email'] );
			$jobs_arr [] = $rs ['id'];
		}
		$dept_arr = array_unique ( $dept_arr );
		$jobs_arr = array_unique ( $jobs_arr );
		$user_arr = array_unique ( $user_arr );
		$jobs_arr = $jobs_arr ? implode ( ',', $jobs_arr ) : '';
		
		$audit = 0;
		$gl = new includes_class_global ();
		$dept_obj = new model_system_dept ();
		$dept_id_arr = $dept_obj->GetUpperAndLower_ID ( $_SESSION ['DEPT_ID'] );
		$dept_id_arr = $dept_id_arr ? array_merge ( array (
															$_SESSION ['DEPT_ID'] ), $dept_id_arr ) : array (
																															$_SESSION ['DEPT_ID'] );
		$dept_id_arr = explode ( ',', $func_limit ['免审批部门'] ) ? array_merge ( $dept_id_arr, explode ( ',', $func_limit ['免审批部门'] ) ) : $dept_id_arr;
		if (A_Notice == true)
		{
			if (count ( $dept_arr ) > 1)
			{
				if (array_diff ( $dept_arr, $dept_id_arr ))
				{
					$audit = 1;
				} else
				{
					$audit = 0;
				}
			} elseif (in_array ( $_SESSION ['DEPT_ID'], $dept_arr ) || ! array_diff ( $dept_arr, array_merge ( array (
																														$_SESSION ['DEPT_ID'] ), $dept_id_arr ) ))
			{
				$audit = 0;
			} else
			{
				$audit = 1;
			}
		} else
		{
			$audit = 0; //免审批
		}
		$this->tbl_name = 'notice';
		$data = array (
						'send_userid' => $_SESSION ['USER_ID'], 
						'title' => $title, 
						'nametype' => $nametype, 
						'content' => $content, 
						'dept_id_str' => $dept_list_str, 
						'area_id_str' => $area_list_str, 
						'jobs_id_str' => $jobs_list_str, 
						'user_id_str' => $user_list_str, 
						'user_check' => $_POST ['user_check'], 
						'file_path' => $file_path, 
						'filename_str' => rtrim ( ($row ['filename_str'] ? $row ['filename_str'] . ',' . $filename_str : $filename_str), ',' ), 
						'start_date' => ($row ['start_date'] ? $row ['start_date'] : $start_date), 
						'send_email' => $send_email, 
						'edit_time' => ($row ['edit_time'] ? $row ['edit_time'] : time ()), 
						'audit' => $audit, 
						'audit_date' => '', 
						'inst' => $inst,
						'date' => ($row ['date'] ? $row ['date'] : time ()) 
					);
		if ($type == 'add')
		{
			$update = $this->create ( $data );
			$Flow_Id=$this->_db->insert_id();
		} elseif ($type == 'edit')
		{
			$update = $this->update ( array (
												'id' => $_GET ['id'], 
												'rand_key' => $_GET ['rand_key'] ), $data );
			$Flow_Id=$_GET ['id'];
		}
		$this-> model_inst($Flow_Id);
		$this->audit = $audit;
		@unlink ( WEB_TOR . EDITOR_DIR . '/' . $_SESSION ['USER_ID'] );
		
		if (A_Notice == true)
		{
			
			$enmai_server = new includes_class_sendmail ();
			if ($audit == 1 && $row ['audit'] != 1) //发送给审核人
			{
				return $Flow_Id;
				//$audit_email_title = '提示：有新的通告需要您审核';
				//$audit_email_body .= '<hr /><br />详情请登录ＯＡ查看操作！';
				//$enmai_server->send ( $audit_email_title, $audit_email_body . oaurlinfo, noticeext );
			}

		}
		if ((A_Notice == true && $_POST ['send_email'] == 1 && $audit == 0 && $update) || (A_Notice == false && $_POST ['send_email'] == 1))
		{
			$lead_id = '';
			$lead_email = array ();
			if ($dept_arr)
			{
				$query = $this->query ( "select MajorId,ViceManager from department where dept_id in (" . implode ( ',', array_filter ( $dept_arr ) ) . ")" );
				while ( ($rs = $this->fetch_array ( $query )) != false )
				{
					$lead_id .= $rs ['MajorId'] . $rs ['ViceManager'];
				}
			}
			if ($lead_id)
			{
				$lead_email = $gl->get_email ( explode ( ',', $lead_id ) );
				//$email_arr = array_merge ( $email_arr, $lead_email );
			}
			$content = preg_replace ( '#src="/#is' , 'src="' . ( ( isset ( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] != 'off' ) ? 'https://' : 'http://' ) . $_SERVER[ 'SERVER_NAME' ] . '/' , $content );
			$email_arr = array_unique ( $email_arr );
			$body .= '<P>您好！</P>';
			$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;您有新的公告通知！</P>';
			$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;标题：' . $title . '</P>';
			$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;发布人：' . $nametype . '</P>';
			$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;附件：';
			$body .= ($filename_str || $row ['filename_str'] ? '有</p>' : '无</p>');
			$body .= '<hr />' . $content . '<p>请登录OA查看详细！</p>';
                        if($_SESSION['COM_BRN_PT']=='dl'){
                            return $this->EmialTask ( '公告：' . $title, $data['content']. '<br/><p>请登录OA查看详细！</p>'. aurlinfo, $email_arr,null,'attachment/notify/'.$data['file_path'],$data['filename_str'] );
                        }else{
                            $enmai_server_xp = new includes_class_sendmail ();
                            $enmai_server_xp->send ( '公告：' . $title, $body . oaurlinfo, $email_arr );
                            return true;
                        }
			//return $enmai_server->send ( '公告：' . $title, $body.$oaurl, $email_arr );
		} else //if ($update)
		{
			return true;
		}
		
	}
	/**
	 * 保存审核结果
	 * @param $id
	 * @param $key_rand
	 */
	function model_save_audit($id, $key_rand)
	{
		$gl = new includes_class_global ();
		$enmai_server = new includes_class_sendmail ();
		$oaurl .= '<hr /><br/><font size="2" >';
		$oaurl .= '内网地址：<a href="https://oa.dinglicom.com/">https://oa.dinglicom.com/</a><br /><br />';
		$oaurl .= '外网地址：<a href="http://www.dinglicom.com/vpn">http://www.dinglicom.com/vpn/</a> </font>';
		$this->tbl_name = 'notice';
		$rs = $this->find ( array (
									
									'id' => $id, 
									'rand_key' => $key_rand ), null, '*' );
		;
		if ($rs)
		{
			$this->update ( array (
									'id' => $id, 
									'rand_key' => $key_rand ), array (
																	'audit' => $_POST ['audit'] == 1 ? 0 : 2, 
																	'audit_userid' => $_SESSION ['USER_ID'], 
																	'notse' => $_POST ['audit'] == 1 ? '' : $_POST ['notse'], 
																	'audit_date' => $_POST ['audit'] == 1 ? time () : 0 ) );
			if (trim ( $_POST ['audit'] ) == 1)
			{
				if ($rs ['send_email'])
				{
					$send_user_email = $gl->get_email ( $rs ['send_userid'] );
					$enmai_server->send ( '提示：您发布的公告已经通过审核', '详情请登录OA查看！' . $oaurl, $send_user_email );
					
					//群发邮件
					$email_arr = array ();
					$where = ' where has_left=0 and del=0 and localizers<>2  and userType<>2 ';
					$where .= $rs ['dept_id_str'] ? " and dept_id in (" . $rs ['dept_id_str'] . ") " : '';
					$where .= $rs ['area_id_str'] ? " and area in (" . $rs ['area_id_str'] . ") " : '';
					$where .= $rs ['jobs_id_str'] ? " and jobs_id in(" . $rs ['jobs_id_str'] . ") " : '';
					if ($rs ['user_id_str'])
					{
						$arr = explode ( ',', $rs ['user_id_str'] );
						$user_id = array ();
						foreach ( $arr as $val )
						{
							$user_id [] = sprintf ( "'%s'", $val );
						}
						$where .= " and user_id in(" . implode ( ',', $user_id ) . ") ";
					}
					$query = $this->query ( "select email,dept_id from user $where" );
					$dept_arr = array ();
					while ( ($row = $this->fetch_array ( $query )) != false )
					{
						$email_arr [] = $row ['email'];
						$dept_arr [] = $row ['dept_id'];
					}
					if ($dept_arr)
					{
						$query = $this->query ( "select MajorId,ViceManager from department where dept_id in(" . implode ( ',', $dept_arr ) . ")" );
						$lead_id = array ();
						$lead_email = array ();
						while ( ($ra = $this->fetch_array ( $query )) != false )
						{
							$lead_id = $ra ['MajorId'] ? array_merge ( $lead_id, explode ( ',', $ra ['MajorId'] ) ) : $lead_id;
							$lead_id = $ra ['ViceManager'] ? array_merge ( $lead_id, explode ( ',', $ra ['ViceManager'] ) ) : $lead_id;
						}
						if ($lead_id)
						{
							$lead_email = $gl->get_email ( array_unique ( $lead_id ) );
							$email_arr = array_merge ( $email_arr, $lead_email );
						}
					
					}
					$body .= '<P>您好！</P>';
					$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;您有新的公告通知！</P>';
					$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;标题：' . $rs ['title'] . '</P>';
					$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;发布人：' . $rs ['nametype'] . '</P>';
					$body .= '<P>&nbsp;&nbsp;&nbsp;&nbsp;附件：';
					$body .= ($rs ['filename_str'] ? '有</p>' : '无</p>');
					$body .= '<hr />' . $rs ['content'] . '<p>请登录OA查看详细！</p>';
                                        if($_SESSION['COM_BRN_PT']=='dl'){
                                            return $this->EmialTask ( '公告：' . mysql_escape_string ( $rs ['title'] ), mysql_escape_string ( $rs ['content']. '<br/><p>请登录OA查看详细！</p>'. $oaurl ), array_unique ( $email_arr ),null,'attachment/notify/'.$rs ['file_path'],$rs ['filename_str'] ); //交给群发邮件任务系统
                                        }else{
                                            $enmai_server->send ( '公告：' . mysql_escape_string ( $rs ['title'] ) ,  mysql_escape_string ( $body . $oaurl ), array_unique ( $email_arr ) );
                                        }
					
				//return $enmai_server->send ( '公告：' . $rs['title'], $body.$oaurl, $email_arr );
				} else
				{
					return true;
				}
			} else
			{
				$email_arr = $gl->get_email ( $rs ['send_userid'] );
				return $enmai_server->send ( '提示：您发布的公告被打回', '打回原因：<br />' . $_POST ['notse'] . '<br />详情请登录OA查看！' . $oaurl, $email_arr );
			}
		} else
		{
			showmsg ( '非法参数！' );
		}
	
	}
	/**
	 * 置顶
	 *
	 * @return unknown
	 */
	function update_top()
	{
		if (intval ( $_POST ['id'] ))
		{
			if ($this->_db->query ( "update notice set edit_time='" . time () . "' where id=" . $_POST ['id'] ))
			{
				return 'ok';
			} else
			{
				return 'no';
			}
		}
	}
	/**
	 * 更新生效状态
	 *
	 * @return unknown
	 */
	function update_status()
	{
		if (intval ( $_POST ['id'] ))
		{
			if ($this->_db->query ( "update notice set effect='" . $_POST ['status'] . "' where send_userid='" . $_SESSION ['USER_ID'] . "' and id='" . $_POST ['id'] . "'" ))
			{
				return 'ok';
			} else
			{
				return 'no';
			}
		}
	}
	
	/**
	 * AJAX获取部门
	 *
	 * @return unknown
	 */
	function get_dept($dept_id_arr = '')
	{
		$dept_id_arr = $dept_id_arr ? explode ( ',', $dept_id_arr ) : '';
		$dept = new model_system_dept ();
		$tree = $dept->DeptTree ();
		$str = '<div><ul>';
		if ($tree)
		{
			foreach ( $tree as $rs )
			{
				$str .= '<li style="list-style:none;margin-left:' . ($rs ['level'] * 20) . 'px;">';
				$str .= '<input type="checkbox" onclick="add_dept(\'' . $rs ['DEPT_NAME'] . '\',this.checked);" name="dept_id[]" ' . (($dept_id_arr && in_array ( $rs ['DEPT_ID'], $dept_id_arr )) ? 'checked' : '') . ' value="' . $rs ['DEPT_ID'] . '" /> ' . $rs ['DEPT_NAME'] . '</li>';
			}
		}
		$str .= '</ul></div>';
		return $str;
		/*$query = $this->_db->query ( "select dept_id,depart_x,dept_name from department where length(depart_x)=2 and dept_name!='系统管理' order by dept_id desc" );
		while ( ($rs = $this->_db->fetch_array ( $query )) != false )
		{
			
			$str .= '<input type="checkbox" name="dept_id[]" ' . (($dept_id_arr && in_array ( $rs['dept_id'], $dept_id_arr )) ? 'checked' : '') . ' onclick="add_dept(\'' . $rs['dept_name'] . '\',this.checked);" title="' . $rs['dept_name'] . '" value="' . $rs['dept_id'] . '" />' . $rs['dept_name'] .
					 '<br />';
			$sql = $this->query ( "select dept_id,dept_name from department where length(depart_x)=4 and depart_x like '" . $rs['depart_x'] . "%'" );
			while ( ($row = $this->_db->fetch_array ( $sql )) != false )
			{
				$str .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . (($dept_id_arr && in_array ( $row['dept_id'], $dept_id_arr )) ? 'checked' : '') . ' onclick="add_dept(\'' . $row['dept_name'] . '\',this.checked);" name="dept_id[]" title="' . $row['dept_name'] . '" value="' . $row['dept_id'] .
						 '" />' . $row['dept_name'] . '<br />';
			}
		}
		return $str ? '<input type="checkbox" onclick="all_checked(\'_deptid_\',\'dept_id[]\',this.checked)" ><b>全选</b><br />' . $str : '';*/
	}
	/**
	 * 获取区域
	 *
	 * @return unknown
	 */
	function get_area($area_id_arr = '')
	{
		$area_id_arr = $area_id_arr ? explode ( ',', $area_id_arr ) : '';
		$query = $this->_db->query ( "select id,name from area where del!=1 " );
		while ( ($rs = $this->_db->fetch_array ( $query )) != false )
		{
			$str .= '<input type="checkbox" title="' . $rs ['name'] . '" name="areaid[]" ' . (($area_id_arr && in_array ( $rs ['id'], $area_id_arr )) ? 'checked' : '') . ' onclick="add_area(\'' . $rs ['name'] . '\',this.checked)" value="' . $rs ['id'] . '" /> ' . $rs ['name'] . '<br />';
		}
		return $str ? '<input type="checkbox" onclick="all_checked(\'_area_\',\'areaid[]\',this.checked)" ><b>全选</b><br />' . $str . '</span>' : '';
	}
	/**
	 * 获取职位
	 *
	 * @return unknown
	 */
	function get_jobs($deptid_str = '', $jobs_id_arr = '')
	{
		
		$deptid_str = $_GET ['deptid_str'] ? rtrim ( $_GET ['deptid_str'], ',' ) : $deptid_str;
		$jobs_arr = $jobs_id_arr ? explode ( ',', $jobs_id_arr ) : ($_GET ['jobs_str'] ? explode ( ',', $_GET ['jobs_str'] ) : '');
		$dept = new model_system_dept ();
		$tree = $dept->DeptTree ( $deptid_str );
		if ($tree)
		{
			$jobs = array ();
			$query = $this->query ( "select * from user_jobs where dept_id!=124" . ($deptid_str ? " and dept_id in ($deptid_str)" : '') );
			while ( ($rs = $this->fetch_array ( $query )) != false )
			{
				$jobs [$rs ['dept_id']] [$rs ['id']] = $rs;
			}
			$str = '';
			foreach ( $tree as $rs )
			{
				$str .= '<li id="dept_' . $rs ['DEPT_ID'] . '" style="list-style:none;margin-left:' . ($rs ['level'] * 20) . 'px;">';
				$str .= '<input type="checkbox" onclick="all_jobs(\'' . $rs ['DEPT_ID'] . '\',this.checked);" value="" /> <b>' . $rs ['DEPT_NAME'] . '</b>';
				if ($jobs [$rs ['DEPT_ID']])
				{
					$str .= '<ul style="margin-left:20px;">';
					foreach ( $jobs [$rs ['DEPT_ID']] as $row )
					{
						$str .= '<li style="list-style:none;"><input type="checkbox" onclick="add_jobs()" name="jobsid[]" title="' . $row ['name'] . '" value="' . $row ['id'] . '" /> ' . $row ['name'] . '</li>';
					}
					$str .= '</ul>';
				}
				$str .= '</li>';
			}
		}
		return $str;
		/*$query = $this->_db->query ( "
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
		return $str ? '<input type="checkbox" onclick="all_checked(\'_jobs_id_\',\'jobsid[]\',this.checked)" ><b>全选</b><br />' . $str . '</span>' : '';*/
	}
	/**
	 * 获取用户列表
	 *
	 * @return unknown
	 */
	function get_user_list($dept_id_str = '', $area_id_str = '', $jobs_id_str = '', $user_id_str = '')
	{
		$dept_id_str = $dept_id_str ? $dept_id_str : $_GET ['deptid_str'];
		$area_id_str = $area_id_str ? $area_id_str : $_GET ['area_str'];
		$jobs_id_str = $jobs_id_str ? $jobs_id_str : $_GET ['jobs_str'];
		$user_id_str = $user_id_str ? $user_id_str : $_GET ['user_str'];
		
		$dept_arr = $dept_id_str ? explode ( ',', $dept_id_str ) : '';
		$jobs_arr = $jobs_id_str ? explode ( ',', $jobs_id_str ) : '';
		$user_arr = $user_id_str ? explode ( ',', $user_id_str ) : '';
		
		$where .= $dept_id_str ? "a.dept_id in(" . rtrim ( $dept_id_str, ',' ) . ")" : '';
		$where .= $area_id_str ? ($where ? ' and b.area in(' . rtrim ( $area_id_str, ',' ) . ')' : ' b.area in(' . rtrim ( $area_id_str, ',' ) . ')') : '';
		$where .= ($jobs_id_str ? ($where ? ' and a.id in (' . rtrim ( $jobs_id_str, ',' ) . ')' : ' a.id in(' . rtrim ( $jobs_id_str, ',' ) . ')') : '');
		$where .= $where ? " and c.dept_name!='系统管理' " : "c.dept_name!='系统管理' ";
		$where = ($where ? ' where b.has_left=0 and b.del=0 and b.localizers<>2 and b.userType<>2 and ' . $where : '');
		$query = $this->_db->query ( "
		select 
			a.id,a.dept_id,b.user_id,b.user_name,c.dept_name,a.name as jobs_name
		from 
			user_jobs as a
			left join user as b on find_in_set(a.id,b.jobs_id)
			left join department as c on c.dept_id=a.dept_id
		$where 
		order by a.dept_id desc,a.id desc,a.name desc
		" );
		$dept = array ();
		$jobs = array ();
		$jobs_user = array ();
		$i = 0;
		$h = 0;
		$jobsid_str = '';
		while ( ($rs = $this->_db->fetch_array ( $query )) != false )
		{
			if (empty ( $dept [$rs ['dept_name']] ) && $rs ['dept_name'])
			{
				if ($h > 0)
					$str .= '</span>';
				$h = 0;
				$i ++;
				if ($i > 1)
				{
					$str .= '</span>';
				}
				$dept [$rs ['dept_name']] = true;
				$str .= '<input type="checkbox" value="" onclick="set_jobs_checked(\'' . $rs ['dept_id'] . '\',this.checked)" /><b><span>' . $rs ['dept_name'] . '</span></b> <a href="javascript:show_jobs_list(' . $rs ['dept_id'] . ')" class="a_jobs_' . $rs ['dept_id'] . '"><img src="images/work/' . (($dept_arr && in_array ( $rs ['dept_id'], $dept_arr )) ? 'sub' : 'plus') . '.png" border="0" /></a><br />';
				$str .= '<span class="show_jobs_' . $rs ['dept_id'] . '" style="' . (($dept_arr && in_array ( $rs ['dept_id'], $dept_arr )) ? '' : 'display:none;') . 'color:#000000;">';
			}
			if (empty ( $jobs [$rs ['dept_name'] . '_' . $rs ['jobs_name']] ) && $rs ['jobs_name'])
			{
				$h ++;
				if ($h > 1)
					$str .= '</span>';
				$jobs [$rs ['dept_name'] . '_' . $rs ['jobs_name']] = true;
				$str .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" value="" onclick="set_user_checked(\'' . $rs ['id'] . '\',this.checked)" /><b>' . $rs ['jobs_name'] . '</b> <a href="javascript:show_user_list(' . $rs ['id'] . ')" class="a_user_' . $rs ['id'] . '"><img src="images/work/' . (($jobs_arr && in_array ( $rs ['id'], $jobs_arr )) ? 'sub' : 'plus') . '.png" border="0"/></a><br />';
				$str .= '<span class="show_user_' . $rs ['id'] . '" style="' . (($jobs_arr && in_array ( $rs ['id'], $jobs_arr )) ? '' : 'display:none;') . 'color:#000000;">';
			}
			if ($user_arr && in_array ( $rs ['user_id'], $user_arr ) && ! $jobs_user [$rs ['jobs_name']])
			{
				$jobs_user [$rs ['jobs_name']] = true;
				$jobsid_str .= $rs ['id'] . ',';
			}
			if ($rs ['user_name'])
			{
				$str .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" ' . (($user_arr && in_array ( $rs ['user_id'], $user_arr )) ? 'checked' : '') . ' name="user_id[]" onclick="show_username();" title="' . $rs ['user_name'] . '" value="' . $rs ['user_id'] . '" />' . $rs ['user_name'] . '<br />';
			}
		}
		$str = $str ? $str . '<script type="text/javascript"> var jobsid_str="' . $jobsid_str . '";</script>' : '';
		return $str ? '<input type="checkbox" onclick="all_checked(\'_userid_\',\'user_id[]\',this.checked)" ><b>全选</b><br />' . $str . '</span></span>' : '您选择的条件没有合适的用户！';
	}
	/**
	 * 保存或读取临时编辑器内容
	 *
	 * @param unknown_type $type
	 * @param unknown_type $content
	 * @return unknown
	 */
	function edit_temp_content($type, $content = '')
	{
		$gl = new includes_class_global ();
		if ($type == 'save')
		{
			if ($gl->set_fckeditor_temp_content ( $content ))
			{
				return 'ok';
			} else
			{
				return 'no';
			}
		} else
		{
			return $gl->get_fckeditor_temp_content ();
		}
	}
	/**
	 * 保存临时编辑器内容
	 *
	 * @return unknown
	 */
	function save_edit_temp_content()
	{
		return $this->edit_temp_content ( 'save', $_POST ['content'] );
	}
	/**
	 * 读取临时编辑器内容
	 *
	 * @return unknown
	 */
	function get_edit_temp_content()
	{
		return new_htmlspecialchars ( stripslashes ( $this->edit_temp_content ( 'get' ) ) );
	}
	/**
	 * 删除附件
	 */
	function del_file()
	{
		if (intval ( $_GET ['id'] ) && $_GET ['filename_str'])
		{
			$filename_arr = explode ( ',', rtrim ( $_GET ['filename_str'] ) );
			$rs = $this->_db->get_one ( "select file_path,filename_str from notice where id=" . $_GET ['id'] );
			if ($rs ['filename_str'])
			{
				$file_arr = explode ( ',', $rs ['filename_str'] );
				$result = array_diff ( $file_arr, $filename_arr );
				foreach ( $file_arr as $val )
				{
					if ($val)
					{
						if (! $result || in_array ( $val, $filename_arr ))
						{
							unlink ( WEB_TOR . NOTICE_FILE_DIR . $rs ['file_path'] . '/' . $val );
						}
					}
				}
				if ($result)
				{
					foreach ( $result as $v )
					{
						$data .= '<input type="checkbox" name="file[]" value="' . $v . '" /><a href="' . NOTICE_FILE_DIR . $rs ['file_path'] . $v . '" title="点击打开查看该附件！" target="_blank">' . $v . '</a>';
					}
				}
				$this->_db->query ( "update notice set filename_str='" . ($result ? implode ( ',', $result ) : '') . "' where id=" . $_GET ['id'] );
				return $data ? $data . '<input type="button" onclick="del_file(' . $_GET ['id'] . ')" value="删除选中附件" />' : 'ok';
			} else
			{
				return false;
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 用户关系部门
	 */
	function DeptList()
	{
		global $func_limit;
		$dept_id_arr = $func_limit['免审批部门'] ? explode(',',$func_limit['免审批部门']) : array($_SESSION['DEPT_ID']);
		$query = $this->query("select dept_id from department where find_in_set('".$_SESSION['USER_ID']."',majorid) or find_in_set('".$_SESSION['USER_ID']."',vicemanager)");
		while(($rs = $this->fetch_array($query))!=false)
		{
			$dept_id_arr[] = $rs['dept_id'];
		}
		$query = $this->query("select assistantdept from user where user_id='".$_SESSION['USER_ID']."'");
		while (($rs = $this->fetch_array($query))!=false)
		{
			if ($rs['assistantdept'])
			{
				$dept_id_arr = array_merge($dept_id_arr,explode(',',$rs['assistantdept']));
			}
		}
		return $dept_id_arr;
	}
	
	function get_instTree($sId=''){
		$query = $this->query("select * from file_pub where man='0' and id<>'0' order by id");
		$instr='';
		while (($rs = $this->fetch_array($query))!=false)
		{
			if ($rs['name']&&$rs['id'])
			{
				if($sId==$rs['id']){
					$instr.="<option value='".$rs['id']."' selected>".$rs['name']."</option>";
				}else{
					$instr.="<option value='".$rs['id']."'>".$rs['name']."</option>";	
				}
				
				
			}
		}
		return $instr;
		
		
		
		
	}
	
	function model_inst($id){
		if($id){
			$this->tbl_name = 'notice';
			$data = $this->find ( array('id'=>$id));
			if($data&&is_array($data)&&$data['effect']!=2&&($data['effect']==1||$data['start_date']<time())&&$data['audit']==0&&$data['inst']&&$data['filename_str'])
			{
				$arr = explode ( ',', $data['filename_str'] );
				$nfile = "attachment/file_cab/".$data['file_path'];
				$this->model_delFileUnderDir($nfile);
				$query = "select max(smallid) as max  from file_content where  SORT_ID ='".$data['inst']."' and ATTACHMENT_ID<>'".$data['file_path']."'";
			    $res=$this->get_one( $query );
			    $imgid=0;
			    if ( $res["max"] )
			    {
			        $imgid =$res["max"];
			    }
			    if (!$imgid)
			    {
			        $imgid = 0;
			    }
			    $imgid += 1;
			    $querys = "select name  from file_pub where  id ='".$data['inst']."'";
			    $cab_names = $this->get_one( $querys );
			    $File_NO = $cab_names['name']."【".date("Ym");
			    $this->query("DELETE FROM file_content WHERE ATTACHMENT_ID='".$data['file_path']."'");	
				foreach ( $arr as $key=>$val )
				{
					if($val){
						$File_NOs = $File_NO."-".$imgid."】";
						$ATTACHMENT_ID = substr( md5( uniqid( rand( ) ) ), 0, 32 );
						$ofile = NOTICE_FILE_DIR . $data['file_path']."/$val";
						$nfiles=$nfile.'/'.$val;
						$fileext=end(explode('.', $val)); 
						$pic_size = filesize($ofile); 
						mkdir($nfile, 511 );
						if (file_exists($ofile)&&file_exists($nfile))
						{        
							copy( $ofile, $nfiles);     
						}
						$sql="insert into file_content (`smallid`, `owner_user`, `file_no`, `sort_id`, `subject`, `content`, `send_time`, `ATTACHMENT_ID`, `ATTACHMENT_NAME`, `file_type`, `file_size`, `remark`, `w_user`, `DEPT_ID`, `PRIV_ID`, `TO_ID`, `r_user`, `Flag`,nId) 
							  VALUES ('".$imgid."','".$_SESSION['USER_ID']."','$File_NOs','".$data['inst']."','','',NOW(),'". $data['file_path']."','$val','$fileext','$pic_size','','','".$data['dept_id_str']."','".$data['jobs_id_str']."','".$data['user_id_str']."','','0','$id')";
						$this->query($sql);
					 	++$imgid;
					}	
				}	
					
			}else{
				
				$this->query("DELETE FROM file_content WHERE nId='$id'");
					
			}
		}
		
	}
	
	
	
	function model_delFileUnderDir( $dirName)  
	{  
	if ( $handle = @opendir( "$dirName" ) ) {  
	   while ( false !== ( $item = readdir( $handle ) ) ) {  
	   if ( $item != "." && $item != ".." ) {  
	   if ( is_dir( "$dirName/$item" ) ) {  
	         model_delFileUnderDir( "$dirName/$item" );  
	   } else {  
	   		@unlink( "$dirName/$item" );  
	   }  
	   }  
	   }  
	   closedir( $handle );  
	}  
	}  
	
}
?>