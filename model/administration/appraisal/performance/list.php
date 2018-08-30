<?php
class model_administration_appraisal_performance_list extends model_base
{
	public $last_quarter; //上季度
	public $this_quarter; //本季度
	public $last_quarter_year;//上季度年份
	public $ExcelData;
	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'appraisal_performance';
		$this->pk = 'id';
		$config = new model_administration_appraisal_performance_config();
		$year = $config->getYearInDB();
		$season = $config->getSeasonInDB();
		$this->this_quarter = $season;
		$this->last_quarter = $season - 1;
		$this->last_quarter_year = (ceil((date('n'))/3)-1) == 0 ? (date('Y')-1) : date('Y');
		$this->filebase = "attachment/appraisal/att/";
		
		/*
		$this->last_quarter = (ceil((date('n'))/3)-1) == 0 ? 4 : ceil((date('n'))/3)-1;
		//$this->this_quarter = ceil((date('n'))/3);
		$this->this_quarter = $this->last_quarter;
		$this->last_quarter_year = (ceil((date('n'))/3)-1) == 0 ? (date('Y')-1) : date('Y');
		*/
	}
	/**
	 * 覆盖父类
	 * @param $condition
	 */
	function GetOneInfo($condition=null)
	{
		return $this->get_one("
								select 
									a.*,b.user_name,c.user_name as assess_username,d.user_name as audit_username,
									e.name as jobs_name,g.status as release_assess_status,h.status as release_audit_status,
									f.assess_userid as assess_user_id,f.audit_userid as audit_user_id
								from 
									$this->tbl_name as a 
									left join appraisal_performance_template as f on f.id=a.tpl_id
									left join user as b on b.user_id=a.user_id
									left join user as c on c.user_id=f.assess_userid
									left join user as d on d.user_id=f.audit_userid
									left join user_jobs as e on e.id=b.jobs_id
									left join appraisal_performance_result as g on g.typeid=1 and g.user_id=f.assess_userid and g.years=f.years and g.quarter=f.quarter
									left join appraisal_performance_result as h on h.typeid=2 and h.user_id=f.audit_userid and h.years=f.years and h.quarter=f.quarter
									".($condition ? " where ".$condition : '')."
							");
	}
	/**
	 * 自动更新模板列表
	 */
	final function UpdateTemplateList()
	{
		$this->query ( "insert into appraisal_performance_template(name,dept_id,years,quarter,assess_userid,audit_userid,content,dept_id_str,area_id_str,jobs_id_str,user_id_str,date)
						SELECT 
							a.name,a.dept_id,EXTRACT(year from now()),QUARTER(now()),a.assess_userid,a.audit_userid,a.content,a.dept_id_str,a.area_id_str,a.jobs_id_str,a.user_id_str,now()
						from 
							appraisal_performance_template as a
							left join (select * from appraisal_performance_template where years=EXTRACT(year from now()) and quarter=(QUARTER(now()))  group by id ) as b on b.name=a.name
						where 
							a.years = if (QUARTER(now()) =1,EXTRACT(year from now())-1,EXTRACT(year from now()))
							and a.quarter =if (QUARTER(now())=1,4,QUARTER(now())-1)
							and b.name is null
							and a.name is not null
					" );
	}
	/**
	 * 分页数据列表
	 * @param $condition
	 * @param $page
	 * @param $rows
	 */
	function GetDataList($condition = null, $page = null, $rows = null,$sort=null)
	{
		if ($page && $rows && ! $this->num)
		{
			$rs = $this->get_one ( "
									select 
										count(0) as num 
									from 
										$this->tbl_name as a 
										left join appraisal_performance_template as e on e.id=a.tpl_id 
										left join user as b on b.user_id=a.user_id
										" . ($condition ? " where " . $condition : '') );
			$this->num = $rs ['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page - 1) * $pagenum) : $this->start;
			$limit = $page && $rows ? $start . ',' . $pagenum : '';
		}
		$query = $this->query ( "
									select 
										a.*,b.user_name,c.user_name as assess_username,d.user_name as audit_username ,f.dept_name,g.name as jobs_name,
										h.status as release_assess_status,l.status as release_audit_status
										from
											 
											$this->tbl_name as a 
											left join appraisal_performance_template as e on e.id=a.tpl_id
											left join user as b on b.user_id=a.user_id
											left join user as c on c.user_id=e.assess_userid
											left join user as d on d.user_id=e.audit_userid
											left join department as f on f.dept_id=b.dept_id
											left join user_jobs as g on g.id=b.jobs_id
											left join appraisal_performance_result as h on h.typeid=1 and h.years=e.years and h.quarter=e.quarter and h.user_id=e.assess_userid
											left join appraisal_performance_result as l on l.typeid=2 and l.years=e.years and l.quarter=e.quarter and l.user_id=e.audit_userid
										" . ($condition ? 'where ' . $condition : '') . "
										".($sort ? 'order by '.$sort : 'order by a.id desc')." 
										" . ($limit ? "limit " . $limit : '') . "
			" );
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$rs['evaluate'] = $this->GetEvaluate($rs['user_id'],$rs['tpl_id']);
			$data[$rs['years']][$rs['quarter']][$rs['user_id']][] = $rs;
		}
		$temp = array();
		if ($data)
		{
			foreach ($data as $year)
			{
				if (is_array($year))
				{
					foreach ($year as $quarte)
					{
						if (is_array($quarte))
						{
							foreach ($quarte as $user)
							{
								if (is_array($user))
								{
									foreach ($user as $row)
									{
										$temp[] = $row;
									}
								}
							}
						}
					}
				}
			}
		}
		return $temp;
		
	}
	/**
	 * 评价列表
	 * @param $user_id
	 * @param $tpl_id
	 */
	function GetEvaluate($user_id,$tpl_id)
	{
		$data = array();
		$query = $this->query("
								select 
									a.*,b.user_name
								from 
									appraisal_evaluate_list as a
									left join user as b on b.user_id=a.evaluators_userid
								where 
									a.user_id='$user_id' and a.tpl_id=$tpl_id
									
									");
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data['evaluators_userid'][] = $rs['evaluators_userid'];
			$data['user'][]=$rs['user_name'];
			$data['evaluate_list_id'][] = $rs['id'];
		}
		//var_dump($data);
		return $data;
	}
	/**
	 * 季度列表
	 * @param $quarte
	 */
	function GetQuarterList($user_id,$years = null, $quarte = null)
	{
		$query = $this->query ( "
								select 
									*
								from 
									$this->tbl_name
								where 
									user_id='$user_id' 
									and years=" . ($years ? $years : date ( 'Y' )) . " 
									and quarter=" . ($quarte ? $quarte : $this->last_quarter));
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data [] = $rs;
		}
		return $data;
	}
	/**
	 * 考核表内容
	 * @param $id
	 */
	function GetContent($tid)
	{
		$data = array ();
		$query = $this->query ( "select * from appraisal_performance_content where tid=" . $tid );
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data [] = $rs;
		}
		return $data;
	}
	/**
	 * 分割数据类型
	 * @param $data
	 * @param $type
	 */
	function GetTypeContent($data, $type)
	{
		$typedata = array ();
		if ($data)
		{
			foreach ( $data as $rs )
			{
				if ($rs ['type'] == $type)
				{
					$typedata [] = $rs;
				}
			}
		}
		return $typedata;
	}
	/**
	 * 添加
	 * @param $tpl_id
	 * @param $data
	 */
	function Add($tpl_id, $data)
	{
		$rs = $this->get_one ( "select a.*,b.email from appraisal_performance_template as a left join user as b on b.user_id=a.assess_userid where a.id=" . $tpl_id );
		if ($rs)
		{
			$file_path='';
			//=============上传附件开始========================
			if ($_FILES ['upfile'])
			{
				$filename_str = '';
				$file_path = md5 ( time () . rand ( 0, 999999 ));
				foreach ( $_FILES ['upfile'] ['tmp_name'] as $key => $val )
				{
					if ($val)
					{
						$tempname = $_FILES ['upfile'] ['tmp_name'] [$key];
						$filename = $_FILES ['upfile'] ['name'] [$key];
						if (! is_dir ( WEB_TOR . PERFORMANCE_FILE_DIR . $file_path ))
						{
							if (! mkdir ( WEB_TOR . PERFORMANCE_FILE_DIR . $file_path ))
							{
								showmsg ( '上传附件失败，请与管理员联系！！' );
							}
						}
						if (move_uploaded_file ( str_replace ( '\\\\', '\\', $tempname ), WEB_TOR . PERFORMANCE_FILE_DIR . $file_path . '/' . $filename ))
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
			$tid = $this->create ( array (
											'tpl_id' => $tpl_id, 
											'user_id' => $_SESSION ['USER_ID'], 
											'name' => $rs ['name'], 
											'quarter' => $rs ['quarter'], 
											'years' => $rs ['years'], 
											'count_my_fraction' => $data ['count_my_fraction'], 
											'file_path'=>$file_path,
											'filename_str'=>$filename_str,
											'email_status' => 1, 
											'date' => date ( 'Y-m-d H:i:s' ) 
			) );
			if ($tid)
			{
				if ($data ['my_fraction'])
				{
					foreach ( $data ['my_fraction'] as $key => $val )
					{
						$this->query ( "insert into appraisal_performance_content(tid,type,content)values($tid,1,'$val')" );
						$this->query ( "insert into appraisal_performance_content(tid,type,content)values($tid,2,'" . $data ['my_remark'] [$key] . "')" );
					}
				}
				$email = new includes_class_sendmail ();
				$msg = '详情请登录OA查看！' . oaurlinfo;
				return $email->send ( $_SESSION ['USERNAME'] . '提交了' . $rs ['years'] . '年.第' . $rs ['quarter'] . '季度绩效考核表，有劳您登录OA考核。', $msg, $rs ['email'] );
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
	 * 修改自评
	 * @param $data
	 * @param $id
	 */
	function Edit($data, $id)
	{
		$rs = $this->GetOneInfo ('a.id='.$id);
		if ($rs)
		{
			$file_path = '';
			//=============上传附件开始========================
			if ($_FILES ['upfile'])
			{
				$filename_str = '';
				$file_path = $rs['file_path'] ?  $rs['file_path'] : md5 ( time () . rand ( 0, 999999 ));
				foreach ( $_FILES ['upfile'] ['tmp_name'] as $key => $val )
				{
					if ($val)
					{
						$tempname = $_FILES ['upfile'] ['tmp_name'] [$key];
						$filename = $_FILES ['upfile'] ['name'] [$key];
						if (! is_dir ( WEB_TOR . PERFORMANCE_FILE_DIR . $file_path ))
						{
							if (! mkdir ( WEB_TOR . PERFORMANCE_FILE_DIR . $file_path ))
							{
								showmsg ( '上传附件失败，请与管理员联系！！' );
							}
						}
						if (move_uploaded_file ( str_replace ( '\\\\', '\\', $tempname ), WEB_TOR . PERFORMANCE_FILE_DIR . $file_path . '/' . $filename ))
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
			parent::Edit ( array (
									'count_my_fraction' => $data ['count_my_fraction'] ,
									'file_path'=>$file_path,
									'filename_str'=>trim(($rs['filename_str'] ? $rs['filename_str'].','.$filename_str : $filename_str),',')
			), $id );
			if ($data ['my_fraction'])
			{
				foreach ( $data ['my_fraction'] as $key => $val )
				{
					$rs = $this->get_one("select id from appraisal_performance_content where tid=$id and type=1 and id=$key");
					if ($rs)
					{
						$this->query ( "update appraisal_performance_content set content='$val' where id=$key" );
					}else{
						$this->query ( "insert into appraisal_performance_content(tid,type,content)values($id,1,'$val')" );
					}
				}
			}
			
			if ($data ['my_remark'])
			{
				foreach ( $data ['my_remark'] as $key => $val )
				{
					$rs = $this->get_one("select id from appraisal_performance_content where tid=$id and type=2 and id=$key");
					if ($rs)
					{
						$this->query ( "update appraisal_performance_content set content='$val' where id=$key" );
					}else{
						$this->query ( "insert into appraisal_performance_content(tid,type,content)values($id,2,'$val')");
					}
				}
			}
			return true;
		} else
		{
			return false;
		}
	
	}
	function update_filename($data,$id)
	{
		return parent::Edit($data,$id);
	}
	/**
	 * 更新意见
	 * @param $id
	 * @param $content
	 */
	function UpdateOpinion($id, $content)
	{
		$rs = $this->get_one ( "
								select 
									a.my_opinion,c.email,b.audit_userid
								from 
									$this->tbl_name as a 
									left join appraisal_performance_template as b on b.id=a.tpl_id  
									left join user as c on c.user_id=b.audit_userid
								where
									a.id=$id
								" );
		if ($rs)
		{
			if ($rs ['my_opinion'])
			{
				return $this->update ( array (
												'id' => $id 
				), array (
							'my_opinion' => $content, 
							'my_status' => 1 
				) );
			} else
			{
				return $this->update ( array (
																'id' => $id 
							), array (
											'my_opinion' => $content, 
											'my_status' => 1 
							) );
				/*
				if ($rs['audit_userid'])
				{
					if ($rs ['email'])
					{
						$email = new includes_class_sendmail ();
						$msg = '详情请登录OA查看！' . oaurlinfo;
						if ($email->send ( '提示：' . $_SESSION ['USERNAME'] . '提交了季度考核意见，有劳您登录OA审核。', $msg, $rs ['email'] ))
						{
							return $this->update ( array (
																'id' => $id 
							), array (
											'my_opinion' => $content, 
											'my_status' => 1 
							) );
						} else
						{
							return false;
						}
					} else
					{
						return false;
					}
				}else{
					return $this->update ( array (
																'id' => $id 
							), array (
											'my_opinion' => $content, 
											'my_status' => 1 
							) );
				}
				*/
			}
		}
	}
	/**
	 * 保存考核
	 */
	function assess($tid, $data)
	{
		$rs = $this->GetOneInfo ('a.id='.$tid );
		if ($rs)
		{
			$send_email_status = $rs ['assess_status'];
			parent::Edit ( array (
									'count_assess_fraction' => $data ['count_assess_fraction'], 
									'average_assess_fraction' => $data ['average_assess_fraction'], 
									'assess_status' => 1, 
									'assess_date' => date ( 'Y-m-d H:i:s' ), 
									'assess_opinion' => $data ['assess_opinion'] 
			), $tid );
			$row = $this->get_one ( "select * from appraisal_performance_content where type=3 and tid=$tid" );
			if ($row)
			{
				foreach ( $data ['assess_fraction'] as $key => $val )
				{
					$this->query ( "update appraisal_performance_content set content='$val' where id=$key" );
				}
			} else
			{
				foreach ( $data ['assess_fraction'] as $key => $val )
				{
					$this->query ( "insert into appraisal_performance_content(tid,type,content)values($tid,3,'$val')" );
				}
			}
			if ($rs ['audit_user_id'] && $send_email_status != 1)
			{
				$gl = new includes_class_global ();
				$userinfo = $gl->GetUserName($rs['user_id']);
				$title = $_SESSION ['USERNAME'] . '考核了 '.$userinfo[$rs['user_id']].' 的' . $rs ['years'] . '年.第' . $rs ['quarter'] . '季度绩效。';
				$msg = '有劳您登录OA进行审核操作,有劳您登录OA进行审核。<br />详情请登录OA查看！' . oaurlinfo;
				$address = $gl->get_email ( $rs ['audit_user_id'] );
				if ($address)
				{
					$email = new includes_class_sendmail ();
					return $email->send ( $title, $msg, $address );
				}else{
					return false;
				}
			}else{
				return true;
			}
		} else
		{
			return false;
		}
	}
	
	/**
	 * 保存审核
	 */
	function audit($tid, $data)
	{
		$rs = $this->get_one ( "
								select 
									a.years,a.quarter,a.audit_status,a.my_opinion,c.email as assess_email ,d.email as user_email,d.user_name
								from 
									$this->tbl_name as a 
									left join appraisal_performance_template as b on b.id=a.tpl_id
									left join user as c on c.user_id=b.assess_userid
									left join user as d on d.user_id=a.user_id
								where
									a.id=$tid
								" );
		if ($rs)
		{
			$send_email_ststua = $rs ['audit_status'];
			parent::Edit ( array (
									'count_audit_fraction' => $data ['count_audit_fraction'], 
									'average_audit_fraction' => $data ['average_audit_fraction'], 
									'audit_status' => 1, 
									'audit_date' => date ( 'Y-m-d H:i:s' ), 
									'audit_opinion' => $data ['audit_opinion'] 
			), $tid );
			$row = $this->get_one ( "select * from appraisal_performance_content where type=4 and tid=$tid" );
			if ($row)
			{
				foreach ( $data ['audit_fraction'] as $key => $val )
				{
					$this->query ( "update appraisal_performance_content set content='$val' where id=$key" );
				}
			} else
			{
				foreach ( $data ['audit_fraction'] as $key => $val )
				{
					$this->query ( "insert into appraisal_performance_content(tid,type,content)values($tid,4,'$val')" );
				}
			}
			/*
			if ($send_email_ststua != 1)
			{
				$email = new includes_class_sendmail ();
				return $email->send ( $_SESSION ['USERNAME'] . '审核了' . $rs ['user_name'] . '的' . $rs ['years'] . '年.第' . $rs ['quarter'] . '季度考核。','详情请登录OA查看！' . oaurlinfo, array (
																																							$rs ['assess_email'], 
																																							$rs ['user_email'] 
				) );
			} else
			{
				return true;
			}*/
			return true;
		} else
		{
			return false;
		}
	}
	/**
	 * 发布或修改结果
	 * @param $years
	 * @param $quarter
	 * @param $typeid 
	 * @param $status
	 */
	function release_result($user_id,$years,$quarter,$typeid=1,$status=1)
	{
		$rs = $this->get_one("select * from appraisal_performance_result where user_id='".$user_id."' and years=$years and quarter=$quarter and typeid=$typeid");
		if ($rs)
		{
			return $this->query("update appraisal_performance_result set status=$status where id=".$rs['id']);
		}else{
			$query = $this->query("select 
										distinct(c.email)
									from 
										appraisal_performance as a 
										left join appraisal_performance_template as b on b.id=a.tpl_id
										left join user as c on c.user_id=a.user_id 
										where 
											a.years=$years
											and a.quarter = $quarter
											and ".($typeid == 1 ? " b.assess_userid='$user_id'" : " b.audit_userid='$user_id'")
			);
			$email_arr = array();
			while (($row = $this->fetch_array($query))!=false)
			{
				$email_arr[] = $row['email'];
			}
			if ($email_arr)
			{
				$gl = new includes_class_global();
				$userinfo = $gl->GetUserName($user_id);
				$title = $userinfo[$user_id].'发布了'.$years.'年第'.$quarter.'季度'.($typeid==1 ? '考核' : '审核').'结果';
				$this->EmialTask($title,'详情请登录OA查看结果。'.oaurlinfo,$email_arr);
			}
			return $this->query("insert into appraisal_performance_result(typeid,user_id,years,quarter,status,date)values($typeid,'$user_id',$years,$quarter,$status,'".date('Y-m-d H:i:s')."')");
		}
	}
	/**
	 * 获取发布
	 * @param $userid
	 * @param $years
	 * @param $quarter
	 * @param $typeid
	 */
	function GetReleaseResult($userid,$years,$quarter,$typeid=1)
	{
		return $this->get_one("select * from appraisal_performance_result where user_id='$userid' and years=$years and quarter=$quarter and typeid=$typeid");
	}
	/**
	 * 删除
	 * @param $id
	 */
	function Del($id)
	{
		$rs = $this->GetOneInfo('a.id='.$id);
		if ($rs)
		{
			$this->query("delete from appraisal_performance_content where tid=".$rs['id']);
			$query = $this->query("select * from appraisal_evaluate_list where tpl_id=".$rs['tpl_id']." and user_id='".$rs['user_id']."' and years=".$rs['years']." and quarter=".$rs['quarter']);
			while (($row = $this->fetch_array($query))!=false)
			{
				$this->query("delete from appraisal_evaluate_content where tid=".$row['id']);
				$this->query("delete from appraisal_evaluate_list where id=".$row['id']);
			}
			return $this->query("delete from $this->tbl_name where id=".$id);
		}else{
			return false;
		}
	}
	/**
	 * 批量生成季度考核表
	 */
	function bathc_add_table($years,$quarter)
	{
		global $func_limit;
		$sql = "insert into appraisal_performance(tpl_id,name,user_id,years,quarter,assess_userid,audit_userid, date)
				select
					b.id,b.name,a.user_id,b.years,b.quarter,b.assess_userid,b.audit_userid,now()
				from
					user as a
					left join appraisal_performance_template as b on (	
																		find_in_set(a.user_id,b.user_id_str)
																		or(
																			(find_in_set(a.dept_id,b.dept_id_str) or b.dept_id_str is null or b.dept_id_str='')
		                                                					and 
		                                                					(find_in_set(a.area,b.area_id_str) or b.area_id_str is null or b.area_id_str='')
		                                                					and 
		                                                					(find_in_set(a.jobs_id,b.jobs_id_str) or b.jobs_id_str is null or b.jobs_id_str='')
		                                                					and 
		                                                					(find_in_set(a.user_id,b.user_id_str) or b.user_id_str is null or b.user_id_str='')
																			
																			)
																	
																	)
					left join appraisal_performance as c on c.tpl_id=b.id and c.user_id=a.user_id and c.years=$years and c.quarter=$quarter
				where
					b.years=$years 
					and b.quarter=$quarter
					and b.id is not null
					and c.name is null
					and b.dept_id in (".$func_limit['查看部门'].")
	
			";
		return $this->query($sql);
	}
	/**
	 * 设置级别
	 * 
	 * @param unknown_type $level
	 * @param unknown_type $id
	 */
	function SetLevel($level,$id)
	{
		return parent::Edit(array('level'=>$level), $id);
	}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	function model_administration_appraisal_performance_list_deptData() {
		global $func_limit;
		$deptIds = $func_limit['管理部门'] ? $func_limit['管理部门']: $_SESSION['DEPT_ID'];
		$deptIds=str_replace(';;','',$deptIds);
		$deptIds=trim($deptIds,',');
		if($deptIds){
			$str=" and dept_id IN ($deptIds)";
		}	
	    $sql="SELECT dept_id,parent_id,dept_name FROM `department` WHERE  1 $str and delflag=0";				
	    $query = $this->query ( $sql );
	    $data[]=un_iconv(array(
				'id'=>'',
				'text'=>'所属所有部门',
				'pid'=>0
			));
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$data[]=un_iconv(array(
				'id'=>$row['dept_id'],
				'text'=>$row['dept_name'],
				'pid'=>$row['parent_id']
			));
		}
		return json_encode ( $data );

	}
function model_administration_appraisal_performance_list_deptDataAll() {
		    $sql="SELECT dept_id,parent_id,dept_name FROM `department` WHERE  delflag=0";				
		    $query = $this->query ( $sql );
		    $data[]=un_iconv(array(
					'id'=>'',
					'text'=>'所属所有部门',
					'pid'=>0
				));
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$data[]=un_iconv(array(
					'id'=>$row['dept_id'],
					'text'=>$row['dept_name'],
					'pid'=>$row['parent_id']
				));
			}
		return json_encode ( $data );

	}	

function model_administration_appraisal_performance_list_mlistData() {
	    global $func_limit;
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$flag = $this->ReQuest("flag");
		$deptId=$deptId?$deptId:($func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID']);
		$deptId=str_replace(';;','',$deptId);
		$deptId=trim($deptId,',');
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and c.DEPT_ID in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		if($tplStatus&&$flag==1){
			if($tplStatus==1 || $tplStatus==4|| $tplStatus==5|| $tplStatus==6|| $tplStatus==7 || $tplStatus==10){
				$sqlstr.=" and a.inFlag='$tplStatus'";
			}elseif($tplStatus==2){
				$sqlstr.=" and a.inFlag='$tplStatus' and a.isAss=1 ";
			}elseif($tplStatus==3){
				$sqlstr.=" and (a.inFlag='$tplStatus' or (a.inFlag='2' and a.isAss<>1 and a.isEval=2) ) ";
			} 
		}

		//S$this->tbl_name = 'appraisal_performance';
		$sqlc = "select count(0) as num 
					FROM appraisal_performance as a 
				LEFT JOIN  `user`  b  ON a.user_id=b.USER_ID
				LEFT JOIN   department c ON c.DEPT_ID=b.DEPT_ID
						where  1  $sqlstr ";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			if($flag==2){
			   $order = "and inFlag=7  order by a.countFraction DESC";
			}else{
				$order = " order by a.id desc";
			}
			
		}
		 $sql="SELECT a.*,c.DEPT_NAME AS deptNameNow
				FROM appraisal_performance as a 
				LEFT JOIN  `user`  b  ON a.user_id=b.USER_ID
				LEFT JOIN   department c ON c.DEPT_ID=b.DEPT_ID  
				WHERE  1=1  $sqlstr $order limit $start,$pageSize";				
		$query = $this->query ( $sql );
		$data=array();
		$i=1;
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			
			if($flag==2){
			   $row['sortRank']=$i;
			}
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?(date('Y-m-d',strtotime($row['assess_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['assess_date']))):'';
			$row['audit_date']=$row['audit_date']?(date('Y-m-d',strtotime($row['audit_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['audit_date']))):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			array_push($data,un_iconv($row));
			$i++;
		}
		
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );

	}	
	function model_administration_appraisal_performance_list_perListData() {
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.assessName like '%$wordkey%' or a.auditName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.dept_id='$deptId'";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		$sqlc = "select count(0) as num 
					from 
						appraisal_performance as a 
						where  1 and a.user_id='".$_SESSION['USER_ID']."' $sqlstr ";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " ORDER BY a.years DESC,a.quarter DESC, a.id DESC";
		}
		 $sql="select a.*
			    from 
				appraisal_performance as a
				where  1 and a.user_id='".$_SESSION['USER_ID']."' and a.inFlag<>10  $sqlstr $order limit $start,$pageSize";				
		$query = $this->query ( $sql );
		$data=array();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?(date('Y-m-d',strtotime($row['assess_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['assess_date']))):'';
			$row['audit_date']=$row['audit_date']?(date('Y-m-d',strtotime($row['audit_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['audit_date']))):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			if(($row['inFlag']<6.5)||($_SESSION['USER_ID']==$row['audit_userid'])){
				$row['count_assess_fraction']='';
				$row['count_audit_fraction']='';
				$row['finalScore']='';//$row['countFraction']-($row['count_audit_fraction']*$row['asAudit']/100);
				$row['count_audit_fraction']='';
				$row['deptRank']='';
				$row['deptRankPer']='';
			}
			array_push($data,un_iconv($row));
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );

	}	
	
   function model_administration_appraisal_transformCycle($case){
   	 if($case){
   	 	switch ($case)
		{
			case 1:
			    return '一季度';
			  break;  
			case 2:
			   return  '二季度';
			  break;
			case 3:
			   return  '三季度';
			  break;
			case 4:
			   return  '四季度';
			  break;
			case 5:
			   return  '上半年';
			  break;
			case 6:
			   return  '下半年';
			  break;
			case 7:
			   return  '全年';
			  break; 
   	   }
   }
   }
      
   function model_administration_appraisal_performance_list_getHeaderData($tplId){
     if($tplId){
     	$this->tbl_name = 'appraisal_template_columnname';
     	$conData=$this->findAll(array('tId'=>$tplId));
     	if($conData&&is_array($conData)){
     		foreach($conData as $key =>$val){
     			if($val){ 
     			   $str.="<td align='center' ><strong>$val[columnsName]</strong></td>";
     			}
     		}
     	$data['str']=$str;
     	$data['num']=count($conData);	
     	}
     	return $data; 
     }
   }
   
    function model_administration_appraisal_performance_list_getTplContentData(){
     $keyId=$_GET ['keyId'];
	 $tplId=$_GET ['tplId'];
     if($tplId&&$keyId){
			$this->tbl_name = 'appraisal_performance';
     	    $perData=$this->find(array('id'=>$keyId));
     	    $this->tbl_name = 'appraisal_performance_value';
     	    $evalData=$this->findAll(array('kId'=>$keyId,'tId'=>$tplId));
     	    $stepI=$this->model_administration_appraisal_performance_list_getStep($keyId);
     	    if($evalData&&is_array($evalData)){
     	    	foreach($evalData as $key =>$val){
     	    		$evalI[$val['cId']]['perEval']=$val['perEval'];
     	    		$evalI[$val['cId']]['perDesc']=$val['perDesc'];
     	    		$evalI[$val['cId']]['pEval']=$val['pEval'];
     	    		$evalI[$val['cId']]['pDesc']=$val['pDesc'];
     	    		$evalI[$val['cId']]['assEval']=$val['assEval'];
     	    		$evalI[$val['cId']]['assDesc']=$val['assDesc'];
     	    		$evalI[$val['cId']]['auditEval']=$val['auditEval'];
     	    		$evalI[$val['cId']]['auditDesc']=$val['auditDesc'];
	     	    	if($stepI['C']=='1'){
	     	    		switch($perData['inFlag']){   
							case 4:{
								if($stepI['P']=='2'){
								 $evalI[$val['cId']]['assEval']=$val['perEval'];	
								}elseif($stepI['P']=='3'){
								 $evalI[$val['cId']]['assEval']=$val['pEval'];	
								}
							}
							break;
							case 5:{
								if($stepI['P']=='2'){
								 $evalI[$val['cId']]['auditEval']=$val['perEval'];	
								}elseif($stepI['P']=='3'){
								 $evalI[$val['cId']]['auditEval']=$val['pEval'];	
								}elseif($stepI['P']=='4'){
								 $evalI[$val['cId']]['auditEval']=$val['assEval'];	
								}
							}
							break;
							
	     	    		}
	     	    	}
     	      }	
     	    }
     	    /*表头*/
     	    $headerData=$this->model_administration_appraisal_performance_list_getHeaderData($tplId);
			if($headerData&&is_array($headerData)){
			  $colspan=$headerData['num'];	
			}
			/*评价人*/
			if($perData['user_id']){
     		   $isevalI=$this->model_administration_appraisal_performance_list_getEvaluate($keyId);
       		   $iseval=$isevalI['isFlag'];	
     		}
     		/*附件*/
     		 $attI=$this->model_administration_appraisal_performance_list_getAtt($keyId);
     		/*表头小项*/
     		$isProjDesc=$this->model_administration_appraisal_performance_list_getIsProjDesc($tplId);
     		if($isProjDesc){
     			$isProjDescClspan=2;
     		}else{
     			$isProjDescClspan=1;
     		}
     		$isProjDescWidth=40*$isProjDescClspan.'px';
            $strList.=<<<EOT
            <tr >
            <td rowspan="2" ><div  style=" width:25px; text-align:center; vertical-align:middle;font-weight:bold;">序号</div></td>
            <td rowspan="2" colspan="$isProjDescClspan"><div style="max-width:80px; font-size:12px; font-weight:bold; min-width:$isProjDescWidth">考核要项</div></td>
            <td rowspan="2" ><div style="max-width:280px; font-size:12px; font-weight:bold; min-width:100px">详细描述</div></td>
            <td rowspan="2" style="border-right:2px solid #000000;"><div style=" width:30px;font-weight:bold;">权重(%)</div></td>
            <td colspan="$colspan" style="font-weight:bold;" > 考核尺度 </td>
EOT;
     	    if($perData['isAss']==1){
			    $strList.=<<<EOT
			    <td rowspan="2" style="border-left:2px solid #000000;"><div style="max-width:40px; min-width:30px;font-weight:bold;">自评分数</div></td>   
	            <td rowspan="2"><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">自评说明</div></td>
	            <td rowspan="2"><div style="max-width:120px; min-width:120px;font-weight:bold;font-size:12px;">附件</div></td>
EOT;
		    }
 			   
 			if($iseval){
 				if($perData['isAss']!=1){
 				  $styleLeft='style="border-left:2px solid #000000;"';
 				}
 				if($isevalI['userEid']&&is_array($isevalI['userEid'])){
	 			 	foreach($isevalI['userEid'] as $ekey=>$_userEid){
	 			 		if(($iseval&&($perData['inFlag']==3))&&($_SESSION['USER_ID']==$_userEid)&&$isevalI[$_SESSION['USER_ID']]['flag']==1){
	 			 		   $strList.='<td colspan="2" '.$styleLeft.' ><div style="font-weight:bold;">评价人<br/>('.$isevalI[$_SESSION['USER_ID']]['name'].')</div></td>';	
	 			 		   $strListPev.='<td  '.$styleLeft.' ><div style="max-width:40px; min-width:30px;font-weight:bold;">评价分数</div></td>';	
		                   $strListPev.='<td ><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">评价描述</div></td>';
	 			 		}else if($iseval&&($isevalI['cofimNum']>0&&$isevalI[$_userEid]['flag']==2||$perData['inFlag']==1)){
	 			 		   $strList.='<td colspan="2" '.$styleLeft.' ><div style="font-weight:bold;">评价人<br/>('.$isevalI[$_userEid]['name'].')</div></td>';	
		     			   $strListPev.='<td  '.$styleLeft.' ><div style="max-width:40px; min-width:30px;font-weight:bold;">评价分数</div></td>';	
		                   $strListPev.='<td ><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">评价描述</div></td>';
				    }	
		     	   }	
		     	}
		     	if($isevalI['cofimNum']>0){
 				$strList.=<<<EOT
			    <td rowspan="2"  ><div style="max-width:40px; min-width:30px;font-weight:bold;">总评价分</div></td>
EOT;
		     	}
		    }
		    if($perData['assess_userid']){
		    $strList.=<<<EOT
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">考核分数</div></td>
            <td rowspan="2"><div style="max-width:50px; min-width:40px;font-weight:bold;font-size:12px;">考核描述</div></td>
EOT;
		    }
			 $strList.=<<<EOT
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">审核分数</div></td>
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">审核描述</div></td>
          </tr>
EOT;
            if($headerData&&is_array($headerData)){
			  $strList.="<tr style='border-bottom:2px solid #000;'>$headerData[str]$strListPev</tr>";
			}
     	
     	$this->tbl_name = 'appraisal_template_contents';
     	$conData=$this->findAll(array('tId'=>$tplId));
     	if($conData&&is_array($conData)){
     		$tpmData=array();
     		foreach($conData as $key =>$val){
     			if($val['kpiRight']){
     				$kpiRight+=$val['kpiRight'];
     			}
     			if($val['projectId']==$tpmData[$val['projectId']]['id']){
     				$tpmData[$val['projectId']]['val'][]=$val;
     				$tpmData[$val['projectId']]['id']=$val['projectId'];
     			}else{
     				$tpmData[$val['projectId']]['val'][]=$val;
     				$tpmData[$val['projectId']]['id']=$val['projectId'];
     			}
     		}
     		if($tpmData&&array($tpmData)){
     			$nums=0;
     			foreach($tpmData as $key =>$_tpmData){
     				if($_tpmData['val']&&is_array($_tpmData['val'])){
     					$spans=count($_tpmData['val']); 
	     				foreach($_tpmData['val'] as $tkey =>$tval){
	     				   $strs='';
	     				   if($tkey==0){
	     				   	 $nums++;
	     				   	 $strs.="<td align='center' rowspan='$spans'>$nums</td>";
	     				     $strs.="<td align='center' rowspan='$spans'>$tval[projectName]</td>";	
	     				   }
	     				   if($isProjDesc){
	     				   	 $strs.="<td align='center' ><div>$tval[projectDesc]</div></td>";
	     				   } 	
		     			   $strs.="<td align='left' ><div>$tval[kpiDescription]</div></td>";
		     			   $strs.="<td align='center' style='border-right:2px solid #000000;color:#f00'><div><input  type='text' class='mini-hidden' name='kpiRight[$tval[id]]' id='kpiRight$tval[id]' value='$tval[kpiRight]'/>$tval[kpiRight]</div></td>";
		     			   //考核尺度开始
		     			   for($i=0;$i<10;$i++){
			     			   	$spn=1;
			     			   	for($d=$i+1;$d<10;$d++){
			     			   		if(($tval['columnName'.$d]||$tval['columnName'.$d]=='0')&&$tval['columnName'.$d]!='NULL'){
			     			   			 break;
			     			   		}else{
			     			   			if($i<$colspan&&$d<$colspan){
					     			   	   	 $spn=$colspan-$i-($colspan-$d)+1;
					     			   	 }
			     			   		}
			     			   	}
			     			   	if($spn>1){
			     			   		$cols="colspan='$spn' ";
			     			   	}else{
			     			   		$cols='';
			     			   	}
			     			   	if(($tval['columnName'.$i]||$tval['columnName'.$i]=='0')&&$tval['columnName'.$i]!='NULL'){ 
			     			   	   $strs.="<td align='left' $cols ><div style='max-width:380px; min-width:100px;text-align:justify;'>".$tval['columnName'.$i]."</div></td>";	
			     			   	}
		     			   }
		     			   
		     			    //考核尺度结束							
		     			   //
		     			   
		     			  //自评 
		     			 if($perData['isAss']==1&&$perData['inFlag']==2&&$_SESSION['USER_ID']==$perData['user_id']){
		     			   $strs.='<td align="center" style="border-left:2px solid #000000;"><input  id="'.$tval['id'].'" name="perEval['.$tval['id'].']" value="'.$evalI[$tval['id']]['perEval'].'" required="true" class="mini-spinner perAss" onvaluechanged="setEvals" style="width:45px" decimalPlaces="1"  minValue="0" maxValue="11"  /></td>';	
		                   $strs.='<td align="center" ><textarea name="description['.$tval['id'].']" id="Description'.$tval['id'].'" class="mini-textarea" emptyText="请输入描述" style="width:70px;height:120px;">'.$evalI[$tval['id']]['perDesc'].'</textarea></td>';
		     			   $strs.='<td align="center" id="upFile'.$tval['id'].'">';
		     			    $strAtt='';
		     			   if($attI[$tval['id']]&&is_array($attI[$tval['id']])){
		     			   	 foreach($attI[$tval['id']] as $key=>$val){
		     			   	 	$strAtt.='<div id="attDel'.$key.'"><a target="_blank" href="index1.php?model=file_uploadfile_management&action=toDownFile&inDocument=../../'.$this->filebase.$val['keyName'].'&originalName='.$val['trueName'].'" title="'.$val['trueName'].'">'.$val['trueName'].'</a><img onclick="delAtt('.$key.')" src="js/extui/themes/dloa/images/bgs/delete.png" style="border:0px;cursor:pointer;"></div>';
		     			   	 }
		     			   }
		     			   $strs.=$strAtt;
						   $strs.='<div id="upFile_'.$tval['id'].'_0" style=" width:100%;"><input type="file" size="1" onchange="upFile('.$tval['id'].',0,this);"
							name="upFile['.$tval['id'].'][]" value="" /><img onclick="delUpFile('.$tval['id'].',0)" src="js/extui/themes/dloa/images/bgs/delete.png" style="border:0px;cursor:pointer;"></div></td>';
		     			 }else if($perData['isAss']==1){
		     			   $strs.='<td align="center" style="border-left:2px solid #000000;">'.$evalI[$tval['id']]['perEval'].'</td>';	
		                   $strs.='<td align="center" >'.$evalI[$tval['id']]['perDesc'].'</td>';
		                   $strs.='<td align="center" >';
		                   $strAtt='';
		                   if($attI[$tval['id']]&&is_array($attI[$tval['id']])){
		     			   	 foreach($attI[$tval['id']] as $key=>$val){
		     			   	 	$strAtt.='<div><a target="_blank" href="index1.php?model=file_uploadfile_management&action=toDownFile&inDocument=../../'.$this->filebase.$val['keyName'].'&originalName='.$val['trueName'].'" title="'.$val['trueName'].'">'.$val['trueName'].'</a></div>';
		     			   	 }
		     			   }
		                   $strs.=$strAtt.'</td>';
		                  }
		                 //自评意见 
		     			 if($perData['inFlag']==7&&$perData['isAsug']==1&&$_SESSION['USER_ID']==$perData['user_id']){
		     			   $perRemark='<textarea name="perRemark"  id="perRemark" class="mini-textarea" emptyText="请输入自评意见" style="width:100%;">'.$perData['my_opinion'].'</textarea>';
		     			 }else{
		     			   $perRemark=$perData['my_opinion'];
		     			 } 
		     			 //考评
		     			 if($isevalI['userEid']&&is_array($isevalI['userEid'])){
		     			 	foreach($isevalI['userEid'] as $ekey=>$_userEid){
			     			   	 if(($iseval&&$perData['inFlag']==3)&&($_SESSION['USER_ID']==$_userEid)&&$isevalI[$_SESSION['USER_ID']]['flag']==1){
				     			   $pEvalCountFract=$isevalI[$_SESSION['USER_ID']]['countFract'];
				     			   $strs.='<td align="center" '.$styleLeft.'><input  id="'.$tval['id'].'" name="pEval['.$tval['id'].']" required="true" value="'.$isevalI[$_SESSION['USER_ID']]['pEval'][$tval['id']]['fract'].'" class="mini-spinner perAss" onvaluechanged="setPevals" style="width:45px" decimalPlaces="1"  minValue="0" maxValue="11"  /></td>';	
				                   $strs.='<td align="center" ><textarea  name="pDescription['.$tval['id'].']" id="pDescription'.$tval['id'].'" class="mini-textarea" emptyText="请输入评价描述" style="width:70px;height:120px;">'.$isevalI[$_SESSION['USER_ID']]['pEval'][$tval['id']]['desc'].'</textarea></td>';
			     			   	 }else if($iseval&&($isevalI['cofimNum']>0&&$isevalI[$_userEid]['flag']==2||$perData['inFlag']==1)){
				     			   $strs.='<td align="center" '.$styleLeft.'>'.$isevalI[$_userEid]['pEval'][$tval['id']]['fract'].'</td>';	
				                   $strs.='<td align="center" >'.$isevalI[$_userEid]['pEval'][$tval['id']]['desc'].'</td>';
				     			 }	
		     			 	}	
		     			 }
		     			  
		     			 if($iseval&&$isevalI['cofimNum']>0){
		     			   $strs.='<td align="center" >'.$evalI[$tval['id']]['pEval'].'</td>';	
		                 }
		                 
		                 //考核人
		     			 if($perData['inFlag']==4&&$_SESSION['USER_ID']==$perData['assess_userid']){
		     			   $assRemark='<textarea name="assRemark"  id="assRemark" class="mini-textarea" emptyText="请输入考核意见" style="width:100%;">'.$perData['assess_opinion'].'</textarea>';
		     			   $strs.='<td align="center" ><input id="'.$tval['id'].'" name="perEval['.$tval['id'].']"  value="'.$evalI[$tval['id']]['assEval'].'" required="true" class="mini-spinner perAss" style="width:45px" onvaluechanged="setAsEvals"  minValue="0" maxValue="11" decimalPlaces="1" /></td>';	
		                   $strs.='<td align="center" ><textarea  name="description['.$tval['id'].']" id="Description'.$tval['id'].'" class="mini-textarea" emptyText="请输入说明" style="width:80px; height:100%;">'.$evalI[$tval['id']]['assDesc'].'</textarea></td>';
		     			 }else if($perData['assess_userid']){
		     			   $strs.='<td align="center" >'.$evalI[$tval['id']]['assEval'].'</td>';	
		                   $strs.='<td align="center" >'.$evalI[$tval['id']]['assDesc'].'</td>';
		                   $assRemark=$perData['assess_opinion'];
		     			 }
		     			 //审核人
		     			 if($perData['inFlag']==5&&$_SESSION['USER_ID']==$perData['audit_userid']){
		     			   $auditFraction=$perData['inFlag']>1?$perData['count_audit_fraction']:'';	
		     			   $auditRemark='<textarea name="auditRemark"  id="auditRemark" class="mini-textarea" emptyText="请输入审核意见" style="width:100%;">'.$perData['audit_opinion'].'</textarea>';
		     			   $strs.='<td align="center" ><input  id="'.$tval['id'].'" name="perEval['.$tval['id'].']" value="'.$evalI[$tval['id']]['auditEval'].'" required="true" class="mini-spinner perAss" onvaluechanged="setAuditEvals" style="width:45px"  minValue="0" maxValue="11" decimalPlaces="1" /></td>';	
		                   $strs.='<td align="center" ><textarea  name="description['.$tval['id'].']" id="Description'.$tval['id'].'" class="mini-textarea" emptyText="请输入说明" style="width:80px; height:100%;">'.$evalI[$tval['id']]['auditDesc'].'</textarea></td>';
		     			 }else{
		                 	$auditFraction=$perData['inFlag']>1?$perData['count_audit_fraction']:'';
		     			   $strs.='<td align="center" >'.($perData['inFlag']>1?$evalI[$tval['id']]['auditEval']:'').'</td>';
		     			   $strs.='<td align="center" >'.($perData['inFlag']>1?$evalI[$tval['id']]['auditDesc']:'').'</td>';
		     			   $auditRemark=$perData['inFlag']>1?$perData['audit_opinion']:'';	
		                 }
		     			 if($strs){
			     			 $str.="<tr>$strs</tr>
		     			   	 ";
			     		  }
			     		  
	     				}
	     				
     				}
	     		}
     		}
     		//前项合并
     		$countsColspan=($isProjDesc?4:3);
     		$countsColspanAject=$countsColspan+1;
     		//中间项合并	
     		$contentConspan=2;
     		$qConsapn=2;
     		if($perData['isAss']==1){
     			$contentConspan+=3;
     		}
     		if($iseval==1){
     			if($perData['inFlag']==1){
     				$contentConspan+=2*count($isevalI['userEid']);
     			}else{
     				$contentConspan+=2*$isevalI['cofimNum'];
     			}
     			if($isevalI['cofimNum']>0){
     				$contentConspan+=1;
     			}
     			if($isevalI[$_SESSION['USER_ID']]['flag']==1&&($perData[inFlag]==3)){
     				$contentConspan+=2;
     			}
     		}
     		if($perData['assess_userid']){
     			$contentConspan+=2;
     		}
     		
     		if($contentConspan==2){
     			if($colspan<=3){
     			  $contentConspan=$colspan;
     			  $qConsapn=1;	
     			}else if($colspan>3){
     			  $contentConspan=$colspan-2;
     			  $qConsapn=2;
     			}
     		}else if($contentConspan==3){
     			if($colspan<=3){
     			  $contentConspan=$colspan-1;
     			  $qConsapn=2;	
     			}else if($colspan>3){
     			  $contentConspan=$colspan;
     			  $qConsapn=2;
     			}
     		}else if($contentConspan==4){
     			$contentConspan=$colspan;
     			$qConsapn=2;
     		}else if($contentConspan>4){
     			$contentConspan=$colspan+$contentConspan-4;
     			$qConsapn=2;
     		}
     		$str.=<<<EOT
     		<tr style="border-top:2px solid #000000;">
            <td colspan="$countsColspan" rowspan="2" nowrap="nowrap" style="text-align:right; vertical-align:middle;"><strong>合计：</strong>
              <input type="hidden" id="tId" name="tId" value="$tplId">
              <input type="hidden" id="kId" name="kId" value="$keyId">
              <input type="hidden" id="perUserId" name="perUserId" value="$perData[user_id]">
              <input type="hidden" id="seUserId" name="seUserId" value="$_SESSION[USER_ID]">
              <input type="hidden" id="inFlag" name="inFlag" value="$perData[inFlag]">
			  <input type="hidden" id="asPers" name="asPers" value="$perData[asPers]">
			  <input type="hidden" id="asAudit" name="asAudit" value="$perData[asAudit]">
			  <input type="hidden" id="asAss" name="asAss" value="$perData[asAss]">
			  <input type="hidden" id="countFraction" name="countFraction" value="$perData[countFraction]">
			  <input type="hidden" id="asPersFra" name="asPersFra" value="$perData[count_my_fraction]">
			  <input type="hidden" id="asAuditFra" name="asAuditFra" value="$auditFraction">
			  <input type="hidden" id="asAssFra" name="asAssFra" value="$perData[count_assess_fraction]">
			  <input type="hidden" id="asPevFra" name="asPevFra" value="$pEvalCountFract">
			  <input type="hidden" id="isConfim" name="isConfim">
			  <input type="hidden" id="iseval" name="iseval" value="$iseval">
            </td>
            <td nowrap="nowrap"  rowspan="2" style="border-right:2px solid #000000;"><strong>$kpiRight%</strong></td>
            <td colspan="$colspan" nowrap="nowrap" style="text-align:right; vertical-align:middle;"><strong>得分合计：</strong></td>
EOT;
           if($perData['isAss']==1){  
			$str.=<<<EOT
     		<td nowrap="nowrap" style="border-left:2px solid #000000;" id='perAssConts'>$perData[count_my_fraction]</td>
     		<td nowrap="nowrap" >&nbsp;</td>
     		<td nowrap="nowrap" >&nbsp;</td>
EOT;
           }
           if($isevalI['userEid']&&is_array($isevalI['userEid'])){
		 	foreach($isevalI['userEid'] as $ekey=>$_userEid){
 			   	 if(($iseval&&$perData['inFlag']==3)&&($_SESSION['USER_ID']==$_userEid)&&$isevalI[$_SESSION['USER_ID']]['flag']==1){
     			   $str.="<td nowrap='nowrap' $styleLeft id='pAssConts'>".$isevalI[$_userEid]['countFract']."</td>";	
                   $str.='<td nowrap="nowrap" >&nbsp;</td>';
     			 }else if($iseval&&$isevalI['cofimNum']>0&&$isevalI[$_userEid]['flag']==2){
     			   $str.="<td nowrap='nowrap' $styleLeft>".$isevalI[$_userEid]['countFract'].'</td>';	
                   $str.='<td nowrap="nowrap" >&nbsp;</td>';
     			 }	
		 	}	
		 } 
		 
		   if($perData['inFlag']>2&&$perData['inFlag']<6.5&&$perData['user_id']==$_SESSION['USER_ID']){
             		$assess_fraction='';
             		$audit_fraction='';
           }else{
             		$assess_fraction=$perData[count_assess_fraction];
             		$audit_fraction=$auditFraction;
           }
             
           if($iseval&&$isevalI['cofimNum']>0){
			 $str.=<<<EOT
     		<td nowrap="nowrap"  >$perData[pevFraction]</td>
EOT;
             }
                                      
             if($perData['assess_userid']){
             	$str.=<<<EOT
     		<td nowrap="nowrap" id='assConts'>$count_fraction</td>
            <td nowrap="nowrap" >&nbsp;</td>
            <td nowrap="nowrap" id='auditConts'>$audit_fraction</td>
            <td nowrap="nowrap" >&nbsp;</td>
          </tr>
EOT;
             }else{
             	$str.=<<<EOT
            <td nowrap="nowrap" id='auditConts'>$audit_fraction</td>
            <td nowrap="nowrap" ><span stlye="display:none"  id='assConts'></div></td>
          </tr>
EOT;
             }
             $str.=<<<EOT
          <tr style="border-bottom:2px solid #000000;">
            <td colspan="$colspan" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;vertical-align:middle;"><strong>考核总分：</strong></td>
            <td colspan="10" nowrap="nowrap" ><strong id='contAll'></span></td>
          </tr>
EOT;

           if($perData['assess_userid']){
             	$str.=<<<EOT
     		 <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;"><strong>考核人意见：</strong></td>
            <td colspan="$contentConspan" style="width: 100%;">$assRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="$qConsapn" nowrap="nowrap"><p align="center"> <strong>$perData[assessName]</strong></p></td>
          </tr>
EOT;
             }         
     		$str.=<<<EOT
          <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;"><strong>审核人意见：</strong></td>
            <td colspan="$contentConspan" style="width: 100%;">$auditRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="$qConsapn" nowrap="nowrap" ><p align="center"> <strong>$perData[auditName]</strong></p></td>
          </tr>
           <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000; text-align:right"><strong>被考核人意见：</strong></td>
            <td colspan="$contentConspan" style="width:100%">$perRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="$qConsapn" nowrap="nowrap" style="height: 37px"><p align="center"> <strong>$perData[userName]</strong></p></td>
          </tr>
          <tr>
            <td colspan="20"><div  style="text-align:center;margin:0px; border-bottom:0px; border-left:0px; border-right:0px;" > 
EOT;
     	}
		    if(($perData['isAss']==1&&$perData['inFlag']==2&&$_SESSION['USER_ID']==$perData['user_id'])
		    ||($iseval&&$perData['inFlag']==3&&in_array($_SESSION['USER_ID'],$isevalI['userEid'])&&$isevalI[$_SESSION['USER_ID']]['flag']==1) 
		    ||($perData['inFlag']==4&&$_SESSION['USER_ID']==$perData['assess_userid'])
		    ||($perData['inFlag']==5&&$_SESSION['USER_ID']==$perData['audit_userid'])
		    ||($perData['inFlag']==7&&$perData['isAsug']==1&&$_SESSION['USER_ID']==$perData['user_id'])){  
			$str.=<<<EOT
			 <a class="mini-button" style="width:60px;" onclick="submitForm(1)">保存</a> <span style="display:inline-block;width:25px;"></span>
             <a class="mini-button" style="width:60px;" onclick="submitForm(2)">提交</a> <span style="display:inline-block;width:25px;"></span>
EOT;
           }/*else if($perData['inFlag']==6&&$_SESSION['USER_ID']==$perData['audit_userid']){
           	$str.=<<<EOT
			 <a class="mini-button" style="width:60px;" onclick="submitForm(1)">保存</a> <span style="display:inline-block;width:25px;"></span>
EOT;
           }*/
           if((($stepI['P']=='2'&&$_SESSION['USER_ID']==$perData['assess_userid'])||(($stepI['P']=='2'||$stepI['P']=='4')&&$_SESSION['USER_ID']==$perData['audit_userid']))&&$stepI['C']){
           	$str.=<<<EOT
               <a class="mini-button" style="width:60px;" onclick="returnBack($perData[id],$stepI[P])">打回</a><span style="display:inline-block;width:25px;"></span>
          
EOT;
           }
           
           $str.=<<<EOT
               <a class="mini-button" style="width:60px;" onclick="onCancel()">关闭</a></div></td>
          </tr>
EOT;
     	$strList.=$str;
     	return $strList; 
     	
     }
   }
   
   function model_administration_appraisal_performance_list_getStep($kId){

   	   if($kId){
   	   		$msgI['P']='';
     		$msgI['N']='';
     		$msgI['C']=1;
   	   	    $this->tbl_name = 'appraisal_performance';
	     	$appData=$this->find(array('id'=>$kId));
     	   	if($appData&&is_array($appData)){
	     		
	     		switch($appData['inFlag']){   
					case   2:{
						if($appData['isEval']=='2'){
							$msgI['N']=3;
						}elseif($appData['isEval']=='1'&&$appData['assess_userid']){
							$msgI['N']=4;
						}elseif(!$appData['assess_userid']&&$appData['audit_userid']){
							$msgI['N']=5;
						}
						if($appData['my_status']=='1'){
						 	$msgI['C']=2;	
						}
					}
					break;   
					case   3:{
						if($appData['isAss']=='1'){
							$msgI['P']=2;
						}
						if($appData['assess_userid']){
							$msgI['N']=4;
						}elseif(!$appData['assess_userid']&&$appData['audit_userid']){
							$msgI['N']=5;
						}
						
					}
					break;   
					case   4:{
						if($appData['isEval']=='2'){
							$msgI['P']=3;
						}elseif($appData['isEval']=='1'&&$appData['isAss']=='1'){
							$msgI['P']=2;
						}
						if($appData['audit_userid']){
							$msgI['N']=5;
						}
						
						if($appData['assess_status']=='1'){
						 	$msgI['C']=2;	
						}
					}
					break;   
					case   5:{
						if($appData['assess_userid']){
							$msgI['P']=4;
						}elseif(!$appData['assess_userid']&&$appData['isEval']=='2'){
							$msgI['P']=3;
						}elseif($appData['isEval']=='1'&&$appData['isAss']=='1'){
							$msgI['P']=2;
						}
						if($appData['audit_status']=='1'){
						 	$msgI['C']=2;	
						}
					}
					break;  
				} 
	     		
	     		
	     	}
	     
   	   } 
   	  return $msgI;
   }
   
   function model_administration_appraisal_performance_list_getEvaluate($kId){
   	   if($kId){
   	   	    $this->tbl_name = 'appraisal_evaluate_list';
	     	$userData=$this->findAll(array('kId'=>$kId));
	     	$cofimNum=0;
	     	$vData=$this->findAll(array('kId'=>$kId,'flag'=>2));
	     	if($vData&&is_array($vData)){
	     		$cofimNum=count($vData);
	     	}
	     	$this->tbl_name = 'appraisal_evaluate_content';
	     	
	     	if($userData&&is_array($userData)){
	     		foreach($userData as $key =>$val){
	     			if($val['evaluators_userid']){
	     				   $vDataI=array();
	     				   $valData=$this->findAll(array('kId'=>$kId,'userId'=>$val['evaluators_userid']));
	     				   if($valData&&is_array($valData)){
	     				   	 foreach($valData as $key=>$_valData){
	     				   	 	$vDataI[$_valData['cId']]['fract']=$_valData['fraction'];
	     				   	 	$vDataI[$_valData['cId']]['desc']=$_valData['remark'];
	     				   	 }
	     				   }
	     				   $data[$val['evaluators_userid']]['countFract']=$val['count_fraction'];
	     				   $data['userEid'][]=$val['evaluators_userid'];
			     		   $data[$val['evaluators_userid']]['flag']=$val['flag'];
			     		   $data[$val['evaluators_userid']]['name']=$val['evalName'];
			     		   $data[$val['evaluators_userid']]['pEval']=$vDataI;
			     		   $data['isFlag']=1;
			     		   $data['cofimNum']=$cofimNum;		
	     			}
	     		  
	     		}
	     	}	     	
   	   }
   	    
   	  return $data;
   }
   
   function model_administration_appraisal_performance_list_getIsProjDesc($tId){
   	 $false=false;
   	 if($tId){
   	   	    $this->tbl_name = 'appraisal_template_contents';
     	    $conData=$this->findAll(array('tId'=>$tId));
     	    if($conData&&is_array($conData)){
     	      	foreach($conData as $key =>$val){
     	      		if(trim($val['projectDesc'])){
     	      			$false=1;
     	      		}
     	      	}
     	    }
   	   }
   	    
   	  return $false;
   }
   function model_administration_appraisal_performance_list_addEval(){
		$data=  ( $_POST );
	    $infoData =json_decode(stripslashes($data['infoData']),true);
	    $userData =json_decode(stripslashes($data['userData']),true);
		$infoData=mb_iconv($infoData);
		$userData=mb_iconv($userData);
		if($infoData['keyId']&&$infoData['tplId']){				
		try{
			$this->_db->query ( "START TRANSACTION" );
			  if($userData&&is_array($userData)){
			    $this->tbl_name = 'appraisal_performance';
     	        $valData=$this->find(array('id'=>$infoData['keyId']));
				$this -> tbl_name = 'appraisal_evaluate_list';
				foreach($userData as $userDataKey=>$_userData)
				{
					if($_userData&&is_array($_userData)&&$_userData['eval']){
					  $createUserData[$userDataKey]['tpl_id']=$infoData['tplId'];
					  $createUserData[$userDataKey]['kId']=$infoData['keyId'];
					  $createUserData[$userDataKey]['flag']=1;
					  $createUserData[$userDataKey]['evaluators_userid']=$_userData['eval'];
					  $createUserData[$userDataKey]['evalName']=$_userData['evalName'];
					  $createUserData[$userDataKey]['user_id']=$valData['user_id'];
					  $createUserData[$userDataKey]['userName']=$valData['userName'];
					  $createUserData[$userDataKey]['years']=$valData['years'];
					  $createUserData[$userDataKey]['quarter']=$valData['quarter'];
					  $createUserData[$userDataKey]['type']=1;
					  $createUserData[$userDataKey]['date']=date('Y-m-d H:i:s');
					}
				}
				if($createUserData&&is_array($createUserData)){
					foreach($createUserData as $key =>$val){
						if($val&&is_array($val)){
							$this -> create ($val);
						}
					}
				}
				$this -> tbl_name = 'appraisal_evaluate_list';
				$vatData=$this->findAll(array('tpl_id'=>$infoData['tplId'],'kId'=>$infoData['keyId']));
				$this->tbl_name = 'appraisal_performance';
				if($vatData&&is_array($vatData)){
				  	$upFlagData=array('isEval'=>2);
	     	    }else{
					$upFlagData=array('isEval'=>1);
				}
				$this -> update ( array ('id'=>$infoData['keyId']) , $upFlagData);
			}
			
			$this->_db->query ( "COMMIT" );
			echo 2;
		}catch(Exception $e){
			$this->_db->query ( "ROLLBACK" );
			return false;
		}
		}   	
   }
     function model_administration_appraisal_performance_list_getIsEvaI($kId){
   	 if($kId){
   	   	    $this->tbl_name = 'appraisal_evaluate_list';
	     	$pData=$this->find(array('kId'=>$kId));
	     	if($pData['id']){
	     		$data['isFlag']=1;
	     	}
   	   }
   	    
   	  return $data;
   }
   
   function model_administration_appraisal_performance_list_getEvalerData(){
   	$kId=$_GET['keyId']?$_GET['keyId']:$_POST['keyId'];
   	 if($kId){
   	 	$this->tbl_name = 'appraisal_evaluate_list';
	    $pData=$this->findAll(array('kId'=>$kId));
	    if($pData&&is_array($pData)){
	     foreach($pData as $key =>$val){
	    	$pData[$key]['eval']=$val['evaluators_userid'];
	    }	
	    }
	    
	    $resultData = array("total"=>$total,"data"=>un_iconv($pData));
	    return json_encode ( $resultData );
   	 }
   }
   function model_administration_appraisal_performance_list_editEval(){
		$data=  ( $_POST );
	    $infoData =json_decode(stripslashes($data['infoData']),true);
	    $userData =json_decode(stripslashes($data['userData']),true);
		$infoData=mb_iconv($infoData);
		$userData=mb_iconv($userData);
		if($infoData['keyId']&&$infoData['tplId']){
			 if($userData&&is_array($userData)){
			  	$this->tbl_name = 'appraisal_performance';
     	        $valData=$this->find(array('id'=>$infoData['keyId']));
				foreach($userData as $userDataKey=>$_userData)
				{
					if($_userData&&is_array($_userData)&&$_userData['eval']){
						if($_userData['_state']=='added'){
						 $createUserData[$userDataKey]['tpl_id']=$infoData['tplId'];
						  $createUserData[$userDataKey]['kId']=$infoData['keyId'];
						  $createUserData[$userDataKey]['flag']=1;
						  $createUserData[$userDataKey]['evaluators_userid']=$_userData['eval'];
						  $createUserData[$userDataKey]['evalName']=$_userData['evalName'];
						  $createUserData[$userDataKey]['user_id']=$valData['user_id'];
						  $createUserData[$userDataKey]['userName']=$valData['userName'];
						  $createUserData[$userDataKey]['years']=$valData['years'];
						  $createUserData[$userDataKey]['quarter']=$valData['quarter'];
						  $createUserData[$userDataKey]['type']=1;
						  $createUserData[$userDataKey]['date']=date('Y-m-d H:i:s');
						}
						 if(($_userData['_state']=='modified'|| $_userData['_editing']!=false)&&$_userData['eval']){
						 	 $updateUserData[$_userData['id']]['evaluators_userid']=$_userData['eval'];
						     $updateUserData[$_userData['id']]['evalName']=$_userData['evalName'];					
						  }
						 if($_userData['_state']=='removed'){
						   $delUserData[$_userData['id']]['id']=$_userData['id'];	
						}
					  }
				}
			 }					
		try{			 
			$this->_db->query ( "START TRANSACTION" );
			$this -> tbl_name = 'appraisal_evaluate_list';
			if($createUserData&&is_array($createUserData)){
					foreach($createUserData as $key =>$val){
						if($val&&is_array($val)){
							$this -> create ($val);
						}
					}
			}
			if($updateUserData&&is_array($updateUserData)){
					foreach($updateUserData as $keys =>$vals){
						if($vals&&is_array($vals)&&$keys){
							$this -> update ( array ( 'id' =>$keys) , $vals);
						}
					}
			}
			if($delUserData&&is_array($delUserData)){
					foreach($delUserData as $_key =>$_vals){
						if($_vals&&is_array($_vals)&&$_key){
							$sqls = "delete from appraisal_evaluate_list  where id in($_key)";
							$res = $this->query ( $sqls );
						}
					}
			}
			$this -> tbl_name = 'appraisal_evaluate_list';
				$vatData=$this->findAll(array('tpl_id'=>$infoData['tplId'],'kId'=>$infoData['keyId']));
				$this->tbl_name = 'appraisal_performance';
				$perData=$this->find(array('id'=>$infoData['keyId']));
				if($vatData&&is_array($vatData)){
					if($perData['inFlag']==2){
					  $upFlagData=array('isEval'=>2,'inFlag'=>2);	
					}else{
						$upFlagData=array('isEval'=>2,'inFlag'=>3);
					}
	     	    }else{
	     	    	if($perData['inFlag']==2&&$perData['isAss']==1){
					  $upFlagData=array('isEval'=>1,'inFlag'=>2);	
					}else if($perData['assess_userid']){
						$upFlagData=array('isEval'=>1,'inFlag'=>4);
					}else if(!$perData['assess_userid']){
						$upFlagData=array('isEval'=>1,'inFlag'=>5);
					}
				}
				$this -> update ( array ('id'=>$infoData['keyId']) , $upFlagData);
					 
			    $this->_db->query ( "COMMIT" );
			echo 2;
		}catch(Exception $e){
			$this->_db->query ( "ROLLBACK" );
			return false;
		}
	 }	
   }
   
   	function model_administration_appraisal_performance_list_perExInSubmit(){
		$kId=$_POST ['kId'];
		$tId=$_POST ['tId'];
		$inFlag=$_POST ['inFlag'];
		$perEvalI=$_POST ['perEval'];
		$descI=$_POST ['description'];
		$pEvalI=$_POST ['pEval'];
		$pDescI=$_POST ['pDescription'];
		$isConfim=$_POST ['isConfim'];
		$iseval=$_POST ['iseval'];
		$perRemark=$_POST ['perRemark'];
		$assRemark=$_POST ['assRemark'];
		$auditRemark=$_POST ['auditRemark'];
		$countFraction=$_POST['countFraction']==0?'':$_POST['countFraction'];
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		if ($kId&&$tId&&(($perEvalI&&is_array($perEvalI))||($pEvalI&&is_array($pEvalI))||$perRemark||!$perRemark)&&$inFlag)
		{
		 try{ 
		 	 $this -> _db -> query ( "START TRANSACTION" );
			$this->tbl_name = 'appraisal_performance';
			$perData=$this->find(array('id'=>$kId));
			if($perData['user_id']){
     		   $isevalI=$this->model_administration_appraisal_performance_list_getEvaluate($kId);
     		   $iseval=$isevalI['isFlag'];	
     		}
			$this->tbl_name = 'appraisal_performance_value';
     	    $valueData=$this->findAll(array('tId'=>$tId,'kId'=>$kId));
     	    if($valueData&&is_array($valueData)){
     	    	if($perEvalI&&is_array($perEvalI)){
	     	    	foreach($perEvalI as $key => $val){
	     	    	  	if($inFlag==2&&$perData['isAss']==1){
	     	    	  	 $upFlagData=array('perEval'=>$val,'perDesc'=>$descI[$key]);	
	     	    	  	}else if($inFlag==4||($perData['isAss']!=1&&$inFlag==2&&!$iseval)){
	     	    	  	 $upFlagData=array('assEval'=>$val,'assDesc'=>$descI[$key]);	
	     	    	  	}else if($inFlag==5){
	     	    	  	 $upFlagData=array('auditEval'=>$val,'auditDesc'=>$descI[$key]);	
	     	    	  	}
	     	    	    $this -> update ( array ('tId'=>$tId,'kId'=>$kId,'cId'=>$key) , $upFlagData);
	     	    	}
     	    	}
     	    	if($pEvalI&&is_array($pEvalI)&&$inFlag==3){
	     	    	 $this->tbl_name = 'appraisal_evaluate_content';
	     	    	 $pData=$this->find(array('tid'=>$tId,'kId'=>$kId,'userId'=>$_SESSION['USER_ID']));
	     	    	 if($pData&&is_array($pData)){
	     	    	 	foreach($pEvalI as $key => $val){
		     	    	  	 $valI=array('fraction'=>$val,'remark'=>$pDescI[$key]);	
		     	    	     $this -> update ( array ('tid'=>$tId,'kId'=>$kId,'cId'=>$key,'userId'=>$_SESSION['USER_ID']) , $valI);
		     	        }
	     	    	 }else{
	     	    	 	foreach($pEvalI as $key => $val){
		     	    	  	 $valI=array('tid'=>$tId,'kId'=>$kId,'cId'=>$key,'userId'=>$_SESSION['USER_ID'],'fraction'=>$val,'remark'=>$pDescI[$key]);	
		     	    	     $this -> create ( $valI);		
		     	    	  }
	     	    	 }   
	     	    	if($_POST['asPevFra']){
		     	    	$this->tbl_name = 'appraisal_evaluate_list';
		     	    	if($isConfim==2){ 
     	    		    	$oFlag=2;
	     	    		 }else{
	     	    		   $oFlag=1;
	     	    		 }
		     	    	 $pevI=array('tpl_id'=>$tId,'kId'=>$kId,'flag'=>$oFlag,'user_id'=>$perData['user_id'],'evaluators_userid'=>$_SESSION['USER_ID'],'years'=>$perData['years'],'quarter'=>$perData['quarter'],'count_fraction'=>($_POST['asPevFra']==0?'':$_POST['asPevFra']),'evalDate'=>date('Y-m-d'));	
		     	    	 $this -> update (array('tpl_id'=>$tId,'kId'=>$kId,'type'=>1,'user_id'=>$perData['user_id'],'evaluators_userid'=>$_SESSION['USER_ID'],'years'=>$perData['years'],'quarter'=>$perData['quarter']) , $pevI);
	                     $tEvsData=$this->findAll(array('tpl_id'=>$tId,'kId'=>$kId));
		     	    	foreach($tEvsData as $key => $val){
		     	    	  if($val&&is_array($val)){
		     	    	  	   $pevFraction+=sprintf("%.2f",$val['count_fraction']);
		     	    	  }	
		     	    	}
		     	    	$tnums=count($tEvsData);
		     	    	 $this->tbl_name = 'appraisal_performance';
		     	    	$upFlagData=array('pevFraction'=>sprintf("%.2f",$pevFraction/$tnums));
		     	    	$this -> update ( array ('id'=>$kId) , $upFlagData);
	     	         }
	     	         $this->tbl_name = 'appraisal_evaluate_content';
     	    		 $tEvData=$this->findAll(array('tid'=>$tId,'kId'=>$kId));
     	    		 foreach($tEvData as $key => $val){
	     	    	  if($val&&is_array($val)){
	     	    	  	   $tmEvI[$val['cId']]+=sprintf("%.2f",$val['fraction']);
	     	    	  }	
	     	    	}
	     	    	$tnum=count($isevalI['userEid']);
	     	    	$this->tbl_name = 'appraisal_performance_value';
	     	    	foreach($pEvalI as $key => $val){
	     	    	  if($val){
	     	    	  	 $upFlagData=array('pEval'=>sprintf("%.2f",$tmEvI[$key]/$tnum));
	     	    	     $this -> update ( array ('tId'=>$tId,'kId'=>$kId,'cId'=>$key) , $upFlagData);		
	     	    	  }	
	     	    	}
     	    	 }
     	    }else{
     	    	$this->tbl_name = 'appraisal_performance_value';
     	    	if($perEvalI&&is_array($perEvalI)){
     	    	foreach($perEvalI as $key => $val){
     	    	  	if($inFlag==2&&$perData['isAss']==1){
     	    	  	 $valI=array('tId'=>$tId,'kId'=>$kId,'type'=>1,'cId'=>$key,'perEval'=>$val,'perDesc'=>$descI[$key]);	
     	    	  	}else if(($inFlag==4)||($perData['isAss']!=1&&$inFlag==2&&!$iseval)){
     	    	  	 $valI=array('tId'=>$tId,'kId'=>$kId,'type'=>1,'cId'=>$key,'assEval'=>$val,'assDesc'=>$descI[$key]);	
     	    	  	}else if($inFlag==5){
     	    	  	 $valI=array('tId'=>$tId,'kId'=>$kId,'type'=>1,'cId'=>$key,'auditEval'=>$val,'auditDesc'=>$descI[$key]);	
     	    	  	}
     	    	    $this -> create ($valI);
     	    	}
     	     }
     	     if($pEvalI&&is_array($pEvalI)&&($inFlag==3||($perData['isAss']!=1&&$inFlag==2&&$iseval))){ 
	     	    	foreach($pEvalI as $key => $val){
	     	    	  	 $valI=array('tId'=>$tId,'kId'=>$kId,'type'=>1,'cId'=>$key,'pEval'=>$val,'pDesc'=>$pDescI[$key]);	
	     	    	     $this -> create ( $valI);	
	     	    	}
	     	    $this->tbl_name = 'appraisal_evaluate_content';
	     	    	foreach($pEvalI as $key => $val){
	     	    	  	 $valI=array('tid'=>$tId,'kId'=>$kId,'cId'=>$key,'userId'=>$_SESSION['USER_ID'],'fraction'=>$val,'remark'=>$pDescI[$key]);	
	     	    	     $this -> create ( $valI);		
	     	    }	
	     	    if($_POST['asPevFra']){
	     	    	$this->tbl_name = 'appraisal_evaluate_list';
	     	    	if($isConfim==2){ 
     	    		    	$oFlag=2;
	     	    		 }else{
	     	    		    $oFlag=1;
	     	    		 }
	     	    	$pevI=array('tpl_id'=>$tId,'kId'=>$kId,'flag'=>$oFlag,'user_id'=>$perData['user_id'],'evaluators_userid'=>$_SESSION['USER_ID'],'years'=>$perData['years'],'quarter'=>$perData['quarter'],'count_fraction'=>($_POST['asPevFra']==0?'':$_POST['asPevFra']),'evalDate'=>date('Y-m-d'));	
		     	    $this -> update (array('tpl_id'=>$tId,'kId'=>$kId,'type'=>1,'user_id'=>$perData['user_id'],'evaluators_userid'=>$_SESSION['USER_ID'],'years'=>$perData['years'],'quarter'=>$perData['quarter']) , $pevI);
	     	    	$tEvsData=$this->findAll(array('tpl_id'=>$tId,'kId'=>$kId));
     	    		foreach($tEvsData as $key => $val){
	     	    	  if($val&&is_array($val)){
	     	    	  	   $pevFraction+=sprintf("%.2f",$val['count_fraction']);
	     	    	  }	
	     	    	}
	     	    	$tnums=count($tEvsData);
	     	    	 $this->tbl_name = 'appraisal_performance';
	     	    	$upFlagData=array('pevFraction'=>sprintf("%.2f",$pevFraction/$tnums));
	     	    	$this -> update ( array ('id'=>$kId) , $upFlagData);
	     	    }	
     	    }
     	  }
     	         $tplWeek=$perData['years'].'年  '.($perData['quarter']==5?' 上半年':($perData['quarter']==6?' 下半年':($perData['quarter']==7?' 全年':' 第'.$perData['quarter'].'季'))).'度';
     	         $tpLName=$tplWeek.'考核';
     	    	if($inFlag==2&&$perData['isAss']==1){
     	    		 if($isConfim==2){
     	    		 	if($iseval){
     	    		 	    $upInFlag=3;
     	    		 	    $strFlag='评价';
     	    		 	    $userEmail=$isevalI['userEid'];
     	    		    }else if($perData['assess_userid']){
     	    		    	$upInFlag=4;
     	    		    	$strFlag='考核';
     	    		    	$userEmail=$perData['assess_userid'];
     	    		 	}else if($perData['audit_userid']){
     	    		    	$upInFlag=5;
     	    		    	$strFlag='审核';
     	    		    	$userEmail=$perData['audit_userid'];
     	    		 	}
     	    		 	$title=" $tpLName 已自评，请进行$strFlag";
     	    		 	$body = "<br/>您好! $perData[userName]已对".$tpLName."自评，请您登录OA开始进行".$strFlag."。<br /><br /><br /><br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
     	    		 	$this->model_administration_appraisal_performance_list_sendEmail($userEmail,$title,$body);
     	    		 }else{
     	    		   $upInFlag=2;
     	    		 }
     	    	  	 $upFlagData=array('my_status'=>1,'inFlag'=>$upInFlag,'count_my_fraction'=>$_POST['asPersFra']==0?'':$_POST['asPersFra'],'countFraction'=>$countFraction,'finalScore'=>$countFraction);	
     	    	 	}else if($inFlag==3||($perData['isAss']!=1&&$inFlag==2&&$iseval)){
     	    	 		 $this->tbl_name = 'appraisal_evaluate_list';
		     	    	 $pEvaData=$this->findAll(array('kId'=>$kId,'flag'=>1));
		     	    	 if($isConfim==2){
			     	    		if($perData['assess_userid']){
		     	    		    	$upInFlag=4;
		     	    		    	$strFlag='考核';
		     	    		    	$userEmail=$perData['assess_userid'];
		     	    		 	}else if($perData['audit_userid']){
		     	    		    	$upInFlag=5;
		     	    		    	$strFlag='审核';
		     	    		    	$userEmail=$perData['audit_userid'];
		     	    		 	}
			     	    		$title="$perData[userName] $tpLName 已被评价，请进行".$strFlag;
		     	    		 	$body = "您好! $perData[userName] $tpLName 已被评价，请您登录OA开始进行".$strFlag."。<br /><br /><br /><br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
		     	    		 	$this->model_administration_appraisal_performance_list_sendEmail($userEmail,$title,$body);
		     	    	 }else{
		     	    		   $upInFlag=3;
		     	    	 }
		     	    	 if($pEvaData&&is_array($pEvaData)){
		     	    	 	$upInFlag=3;
		     	    	 }
     	    	  	 $upFlagData=array('inFlag'=>$upInFlag);	
     	    	   }else if(($inFlag==4)||($perData['isAss']!=1&&$inFlag==2&&!$iseval)){
     	    	 	if($isConfim==2){ 
     	    		    	$upInFlag=5;
     	    		    	$title=" $tpLName 请进行审核";
	     	    		 	$body = "您好! $perData[assessName] 已对 $perData[userName] $tpLName 考核，请您登录OA开始进行审核。<br /><br /><br /><br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
	     	    		 	$this->model_administration_appraisal_performance_list_sendEmail($perData['audit_userid'],$title,$body);
     	    		 }else{
     	    		   $upInFlag=4;
     	    		 }
     	    	  	 $upFlagData=array('assess_status'=>1,'inFlag'=>$upInFlag,'assess_date'=>date('Y-m-d H:i:s'),'assess_opinion'=>$assRemark,'count_assess_fraction'=>$_POST['asAssFra']==0?'':$_POST['asAssFra'],'countFraction'=>$countFraction,'finalScore'=>$countFraction);	
     	    	 }else if($inFlag==5){
     	    	 	if($isConfim==2){ 
     	    		    	$upInFlag=6;
     	    		    	//$title=" $tpLName 已审核，请填写考核意见";
	     	    		 	//$body = "您好! $perData[auditName] 已 对 $perData[userName]  $tpLName 已审核 ，请您登录OA填写考核意见。<br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
	     	    		 	//$this->model_administration_appraisal_performance_list_sendEmail($perData['user_id'],$title,$body);
     	    		 }else{
     	    		   $upInFlag=5;
     	    		 }
     	    		  $upFlagData=array('audit_status'=>1,'inFlag'=>$upInFlag,'audit_date'=>date('Y-m-d H:i:s'),'audit_opinion'=>$auditRemark,'count_audit_fraction'=>$_POST['asAuditFra']==0?'':$_POST['asAuditFra'],'countFraction'=>$countFraction,'finalScore'=>$countFraction);	
      	  		 }else if($inFlag==7){
     	    	 	 if($isConfim==2){
	 	    		    	$upInFlag=7;
	 	    		    	$isAsug=2;
	 	    		    	$title="$perData[userName] 已对 $tpLName 结果填写意见";
	     	    		 	$body = "您好! $perData[userName] 已对  $tpLName 结果填写意见：<br/>" .
	     	    		 			($perRemark?$perRemark:"无")
	     	    		 			."<br /><br /><br />" . oaurlinfo;
	     	    		 	$this->model_administration_appraisal_performance_list_sendEmail(array($perData['assess_userid'],$perData['audit_userid']),$title,$body);
     	    		 }else{
     	    		   $upInFlag=7;
     	    		   $isAsug=1;
     	    		 }
     	    	  	 $upFlagData=array('inFlag'=>$upInFlag,'isAsug'=>$isAsug,'my_opinion'=>$perRemark);
     	    	  }
     	    	  /*else if(($inFlag==6)&&$perData['assess_status']==1){
     	    	 	if($isConfim==2){ 
     	    		    	$upInFlag=7;
     	    		 }else{
     	    		   $upInFlag=6;
     	    		 }
     	    	  	 $upFlagData=array('audit_status'=>1,'inFlag'=>$upInFlag,'audit_date'=>date('Y-m-d H:i:s'),'audit_opinion'=>$auditRemark,'count_audit_fraction'=>$_POST['asAuditFra']==0?'':$_POST['asAuditFra'],'countFraction'=>$_POST['countFraction']==0?'':$_POST['countFraction']);	
     	    	 }*/
                if($upFlagData){
                  $this->tbl_name = 'appraisal_performance';
                  $this -> update ( array ( 'id' =>$kId) , $upFlagData);	
                }
                
                if($_FILES['upFile']){
                	$this->model_administration_appraisal_performance_list_upFile($kId);
                }
                $this -> _db -> query ( "COMMIT" );
                return true;
            } catch ( Exception $e )
		    {
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		    }    
        }
			
	}

	
function model_administration_appraisal_performance_list_asseList() {
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$flag = $this->ReQuest("flag");
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.assessName like '%$wordkey%' or a.auditName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.deptId IN ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		if($tplStatus&&$flag==1){
			if($tplStatus==1 || $tplStatus==4|| $tplStatus==5|| $tplStatus==6|| $tplStatus==7 || $tplStatus==10){
				$sqlstr.=" and a.inFlag='$tplStatus'";
			}elseif($tplStatus==2){
				$sqlstr.=" and a.inFlag='$tplStatus' and a.isAss=1 ";
			}elseif($tplStatus==3){
				$sqlstr.=" and (a.inFlag='$tplStatus' or (a.inFlag='2' and a.isAss<>1 and a.isEval=2) ) ";
			} 
		}
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " order by a.assess_status ,a.date desc,a.id desc";
		}
		$sqlc = "select count(0) as num 
					from 
						appraisal_performance as a 
						where  1  and a.assess_userid='".$_SESSION['USER_ID']."'  and a.inFlag<>10 $sqlstr ";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		
		/*
		 $sql="select a.*
			    from 
				appraisal_performance as a
				where  1 AND (a.inFlag>3 OR(a.inFlag=2 AND a.isAss<>1 AND a.isEval=1)) AND a.assess_userid='".$_SESSION['USER_ID']."' $sqlstr $order limit $start,$pageSize";
		*/
		$sql="select a.*
			    from 
				appraisal_performance as a
				where  1  AND a.assess_userid='".$_SESSION['USER_ID']."'  and a.inFlag<>10 $sqlstr $order limit $start,$pageSize";
	
		$query = $this->query ( $sql );
		$data=array();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?(date('Y-m-d',strtotime($row['assess_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['assess_date']))):'';
			$row['audit_date']=$row['audit_date']?(date('Y-m-d',strtotime($row['audit_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['audit_date']))):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			if(($row['inFlag']<6.5)||($_SESSION['USER_ID']!=$row['audit_userid'])){
				$row['countFraction']='';//$row['countFraction']-($row['count_audit_fraction']*$row['asAudit']/100);
				$row['count_audit_fraction']='';
			}
			array_push($data,un_iconv($row));
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );

	}	

function model_administration_appraisal_performance_list_auditList() {
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$flag = $this->ReQuest("flag");
		
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.deptId in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		if($tplStatus&&$flag==1){
			if($tplStatus==1 || $tplStatus==4|| $tplStatus==5|| $tplStatus==6|| $tplStatus==7 || $tplStatus==10){
				$sqlstr.=" and a.inFlag='$tplStatus'";
			}elseif($tplStatus==2){
				$sqlstr.=" and a.inFlag='$tplStatus' and a.isAss=1 ";
			}elseif($tplStatus==3){
				$sqlstr.=" and (a.inFlag='$tplStatus' or (a.inFlag='2' and a.isAss<>1 and a.isEval=2) ) ";
			} 
		}
       if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			if($flag==2){
			   $order = "and inFlag in (5,6)  order by a.finalScore DESC ,a.years desc,a.quarter desc,a.inFlag desc,a.audit_status desc,a.date desc,a.id desc";
			}else{
				$order = " order by a.years desc,a.quarter desc,a.inFlag desc, a.audit_status desc,a.date desc,a.id desc";
			}
			
		}
		//S$this->tbl_name = 'appraisal_performance';
		$sqlc = "select count(0) as num 
					from 
						appraisal_performance as a 
						where  1   and a.audit_userid='".$_SESSION['USER_ID']."'   and a.inFlag<>10 $sqlstr $order";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		
		 $sql="select a.*
			    from 
				appraisal_performance as a
				where  1  and a.audit_userid='".$_SESSION['USER_ID']."'  and a.inFlag<>10 $sqlstr $order limit $start,$pageSize";
		$query = $this->query ( $sql );
		$data=array();
		$i=1;
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			
			if($flag==2){
			   $row['sortRank']=$i;
			}
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?(date('Y-m-d',strtotime($row['assess_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['assess_date']))):'';
			$row['audit_date']=$row['audit_date']?(date('Y-m-d',strtotime($row['audit_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['audit_date']))):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			array_push($data,un_iconv($row));
			$i++;
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );

	}
function model_administration_appraisal_performance_list_postAuditList() {
	    global $func_limit;
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$flag = $this->ReQuest("flag");
		$deptId=$deptId?$deptId:($func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID']);
		$deptId=str_replace(';;','',$deptId);
		$deptId=trim($deptId,',');
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.deptId in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		if($tplStatus&&$flag==1){
			if($tplStatus==1 || $tplStatus==4|| $tplStatus==5|| $tplStatus==6|| $tplStatus==7 || $tplStatus==10){
				$sqlstr.=" and a.inFlag='$tplStatus'";
			}elseif($tplStatus==2){
				$sqlstr.=" and a.inFlag='$tplStatus' and a.isAss=1 ";
			}elseif($tplStatus==3){
				$sqlstr.=" and (a.inFlag='$tplStatus' or (a.inFlag='2' and a.isAss<>1 and a.isEval=2) ) ";
			} 
		}

		//S$this->tbl_name = 'appraisal_performance';
		$sqlc = "select count(0) as num 
					from 
						appraisal_performance as a 
						where  1 and a.inFlag<>10 $sqlstr ";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			if($flag==2){
			   $order = "and inFlag in (6,7)  order by CAST(a.finalScore AS decimal) DESC ,a.years desc,a.quarter desc,a.inFlag desc,a.audit_status desc,a.date desc,a.id desc";
			}else{
				$order = " order by a.years desc,a.quarter desc,a.inFlag desc, a.audit_status desc,a.date desc,a.id desc";
			}
			
		}
		 $sql="select a.*
			    from 
				appraisal_performance as a
				where  1 and a.inFlag<>10 $sqlstr $order limit $start,$pageSize";
		$query = $this->query ( $sql );
		$data=array();
		$i=1;
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			
			if($flag==2){
			   $row['sortRank']=$i+$start;
			}
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?(date('Y-m-d',strtotime($row['assess_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['assess_date']))):'';
			$row['audit_date']=$row['audit_date']?(date('Y-m-d',strtotime($row['audit_date']))=='1970-01-01'?'':date('Y-m-d',strtotime($row['audit_date']))):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			array_push($data,un_iconv($row));
			$i++;
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );

	}		
		
function model_administration_appraisal_performance_list_exportExcel() {
		global $func_limit;
		$wordkey = ($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$deptId=$deptId?$deptId:($func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID']);
		$deptId=str_replace(';;','',$deptId);
		$deptId=trim($deptId,',');
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.deptId in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		if($tplStatus){
			if($tplStatus==1 || $tplStatus==4|| $tplStatus==5|| $tplStatus==6|| $tplStatus==7 || $tplStatus==10){
				$sqlstr.=" and a.inFlag='$tplStatus'";
			}elseif($tplStatus==2){
				$sqlstr.=" and a.inFlag='$tplStatus' and a.isAss=1 ";
			}elseif($tplStatus==3){
				$sqlstr.=" and (a.inFlag='$tplStatus' or (a.inFlag='2' and a.isAss<>1 and a.isEval=2) ) ";
			} 
		}
		$title = '员工考核数据';
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$Title = array (array ($title));
		$ExcelData[] = array ("ID","员工号","被考核人","直属部门","二级部门","三级部门","职位","入职日期","转正日期","模板名称","所属部门","年份","考核周期","状态","自评总分",
									  "评价总分","考核人","考核状态","考核总分","考核时间","审核人","审核状态","审核总分","审核时间","加权得分","最终得分","排名","排名比例(前)");
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " order by a.id desc";
		}
		$sql="SELECT a.id,a.userNo,a.userName,b.deptNameS,b.deptNameT,b.deptName as deptNameA,a.deptName,a.jobName,a.comeInDate,a.ReguDate,a.name,a.years,a.quarter,
			  a.pevFraction,a.inFlag,a.count_my_fraction,a.assessName,a.assess_status,a.count_assess_fraction,a.assess_date,a.auditName,
			  a.audit_status,a.count_audit_fraction,a.audit_date,a.countFraction,a.deptRank,a.deptRankPer,a.finalScore,a.isAss
			  FROM appraisal_performance as a LEFT JOIN oa_hr_personnel as b ON (a.user_id=b.userAccount) 
			  WHERE  1  $sqlstr $order ";				
		$query = $this->query ( $sql );
		$data=array();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?date('Y-m-d',strtotime($row['assess_date'])):'';
			$row['audit_date']=$row['audit_date']?date('Y-m-d',strtotime($row['audit_date'])):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			if($row['inFlag']==1){
			  $row['inFlag']='未开始';	 
		     }else if($row['inFlag']==2){
				if($row['isAss']=='1'){
					$row['inFlag']='自评中';	
				}else if($row['isEval']==2&&$row['isAss']!=1){
					$row['inFlag']='评价中';	
				}else{
					$row['inFlag']='考核中';
				} 
		     }else if($row['inFlag']==3){
			  	$row['inFlag']='评价中';	 
		     }else if($row['inFlag']==4){
			  	$row['inFlag']='考核中';	 
		     }else if($row['inFlag']==5){
			  	$row['inFlag']='审核中';	 
		     }else if($row['inFlag']==6){
			   $row['inFlag']='结果发布中';	 
		     }else if($row['inFlag']==7){
		     	if($row['inFlag']==1){
		     	   $row['inFlag']='填写被考核意见中';	
		     	}else{
		     		$row['inFlag']='完成';
		     	}
			   	 
		     }else if($row['inFlag']==10){
		     	$row['inFlag']='关闭';	
		     }
		     if($row['assess_status']==1){
			   $row['assess_status']='已考核';	 
			 }else{
			  $row['assess_status']='待考核';	 
			 }
			  if($row['audit_status']==1){
			     $row['audit_status']='已审核';	 
			 }else{
			    $row['audit_status']='待审核';	 
			 }
			$ExcelData[]=array($row['id'],$row['userNo'],$row['userName'],$row['deptNameA'],$row['deptNameS'],$row['deptNameT'],$row['jobName'],
										$row['comeInDate'],$row['ReguDate'],$row['name'],$row['deptName'],$row['years'],$row['quarter'],
										$row['inFlag'],$row['count_my_fraction'],$row['pevFraction'],$row['assessName'],$row['assess_status'],
										$row['count_assess_fraction'],$row['assess_date'],$row['auditName'],$row['audit_status'],
										$row['count_audit_fraction'],$row['audit_date'],$row['countFraction'],$row['finalScore'],$row['deptRank'],
										$row['deptRankPer']);
			
		}
       	$data = $ExcelData;
       	$total = ( count ( $data ) - 1 );
		$xls = new includes_class_excel ( $title . '.xls' );
		$xls -> SetTitle ( array ( $title) , $Title );
		$xls -> SetContent (array ($data));
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A1:AB1' );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A2:AB2' ) -> getFont ( ) -> setBold ( true );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:AB'.($total+2) ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$styleArray = array ( 
								'borders' => array ( 
													'allborders' => array ( 
																			'style' => PHPExcel_Style_Border :: BORDER_THIN , 
																			'color' => array ( 
																							'argb' => '00000000' 
																			) 
													) 
								) 
			);
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:AB'.($total+2) ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:AB'.($total+2)) -> applyFromArray ( $styleArray );
		$xls -> objActSheet[ 0 ] -> setCellValue ( 'A' . ( $total + 5 ) , un_iconv ( '合计：' . $total ) );
		$columnData=array('A','B','C','D','E','F','G','H','I','J','K','M','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB');	
		foreach($columnData as $key =>$val){
		  $xls -> objActSheet[ 0 ] -> getColumnDimension ( $val ) -> setWidth ( 15 );
		}
		$xls -> OutPut ( );
	}	
  function model_administration_appraisal_performance_list_begineSort(){
  	    set_time_limit(0);
		$infoData =json_decode(stripslashes($_POST['infoData']),true);
		$infoData=mb_iconv($infoData);
	    $createDeptId=trim($infoData['begineDeptId']);
	    $createYear=trim($infoData['begineYear']);
	    $createStyle=trim($infoData['begineStyle']);
	    $now=date('Y-m-d');
	    if($createStyle==1){
	    	if(strtotime($now)<strtotime($createYear.'-04-15')){
	    	   return 3;
	        }
	    }else if($createStyle==2){
	    	if(strtotime($now)<strtotime($createYear.'-07-15')){
	    	   return 3;
	        }
	    }else if($createStyle==3){
	    	if(strtotime($now)<strtotime($createYear.'-10-15')){
	    	   return 3;
	        }
	    }else if($createStyle==4){
	    	if(strtotime($now)<strtotime(($createYear).'-12-15')){
	    	   return 3;
	        }
	    }
		if($createDeptId&&$createYear&&$createStyle){
			$reSql ="SELECT count(0) as num FROM `appraisal_performance` where  inFlag<6 and deptId in ($createDeptId) AND years='$createYear' AND `quarter`='$createStyle'";
			$rec = $this->get_one ( $reSql );
		    if($rec["num"]){
			   return 4;
			}
			try
			{
			  $Sql ="SELECT a.id,a.name,b.USER_NAME,b.EMAIL FROM `appraisal_performance` a LEFT JOIN user b on a.user_id=b.user_id where  inFlag in (6,7) and deptId in ($createDeptId) AND years='$createYear' AND `quarter`='$createStyle' ORDER BY CAST(finalScore as DECIMAL) DESC ";
			$query = $this->query ( $Sql );
			 $i=1;
			$tplWeek=$createYear.'年  '.($createStyle==5?' 上半年':($createStyle==6?' 下半年':$createStyle==7?' 全年':' 第'.$createStyle.'季')).'度';
			$mail_server = new includes_class_sendmail ( );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$upSortData[$i] = $row[ 'id' ];
				$tpLName=$row['name'].' '.$tplWeek.'考核表';
				 $body = " 您好，" . $row[ 'USER_NAME' ] . "<br/> $tpLName 已发布考核结果，请您登录OA进行本此考核意见反馈。<br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
			      $mail_server -> send ($tpLName."已发布考核结果" , $body , $row['EMAIL'] );	
				$i++;
				
			}
			$contDec=count($upSortData);
			if($upSortData&&is_array($upSortData)&&$contDec){
				foreach($upSortData as $key=>$val){
					$Percentage='';
					if($val){
						$Percentage=sprintf("%.2f", ($key/$contDec))*100;
					  $upStr="UPDATE appraisal_performance set  deptRank='$key',deptRankPer='$Percentage',inFlag=7 where id='$val';";	
					  $flag = $this->query ( $upStr );
					}
				}
			}
			
			$this -> _db -> query ( "COMMIT" );
			if($flag){
				$mag=2;
			}else{
				$mag=1;
			}
		    return $mag;
				
			} catch ( Exception $e )
		    {
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		    }
		}
		
	}
	function model_administration_appraisal_performance_list_upFile($kId) {
		if ($_FILES['upFile']&&$kId)
		{
			foreach ( $_FILES['upFile']['tmp_name'] as $key => $val )
			{
				if ($val&&is_array($val))
				{
					foreach($val as $ky =>$_val){
						if($_val){
						    $tempName = $_FILES['upFile']['tmp_name'][$key][$ky];
						    $fileName = $_FILES['upFile']['name'][$key][$ky];
						    $tempKey = md5 ( time () . rand ( 0, 999999 ) );
							$file_type=end(explode("." , trim ( $fileName)));
							$ftypes = array("php", "jsp", "asp", "aspx", "js", "exe","htm","html");
							if (file_exists ( $this->filebase) == "")
							{
								@mkdir ( $this->filebase, 511 );
							}
							if (!in_array(strtolower($file_type), $ftypes)) {
								if (move_uploaded_file ( str_replace('\\\\','\\',$tempName), WEB_TOR . $this->filebase.$tempKey.'.' .$file_type))
								{
									$sqls= "insert into appraisal_performance_upfiles(kId,cId,fileName,fileKey,createDate)values('$kId','$key','"
									.  trim ( $fileName ) . "','".$tempKey.'.' .$file_type."',now())";
									if($sqls){
										$res = @$this->query ( $sqls );
									}
									
								} else
								{
									showmsg ( '上传附件失败，请与管理员联系！' );
								}
							}else
							{
								showmsg ( '上传附件类型不正错，请与管理员联系！' );
							}	
						}
						
					}
				}
			}
		}

	}
	function model_administration_appraisal_performance_list_getAtt($kId){
		if($kId){
			$this->tbl_name = 'appraisal_performance_upfiles';
 	        $attData=$this->findAll(array('kId'=>$kId));
			if($attData&&is_array($attData)){
				foreach($attData as $key=>$val){
					$attI[$val['cId']][$val['id']]['trueName']=$val['fileName'];
					$attI[$val['cId']][$val['id']]['keyName']=$val['fileKey'];
				} 
			}
		}
	   return	$attI;
	}
	function model_administration_appraisal_performance_list_sendEmail($userI,$title,$body){
		if($userI){
			$EmailSserver = new includes_class_sendmail ( );
			if(is_array($userI)){
				$userI=array_filter(array_unique($userI));
				$userStr=implode("','",$userI);
			}else{
				$userStr="$userI";
			}
			if($userStr){
				$sql="SELECT USER_NAME,EMAIL FROM `user` WHERE USER_ID IN ('$userStr') ";	
				$query = $this->query ( $sql );
				while ( ($row = $this->fetch_array ( $query )) != false )
				{
					$Email[]=$row['EMAIL'];
				
				}
			   foreach((array)$Email as $key=>$val){
			   	if($val){
			   		$EmailSserver-> send ($title , $body , $val);
			   	}
			      		
			   }	
		   }
		}
	}
	
  function model_administration_appraisal_performance_list_editManager(){
  	    $this->model_administration_appraisal_performance_list_editEval();
  	    $data=  ( $_POST );
	    $infoData =json_decode(stripslashes($data['infoData']),true);
	    $infoData=mb_iconv($infoData);
		if($infoData['keyId']){
			 $this->tbl_name = 'appraisal_performance';
		     $upFlagData=array('assessName'=>$infoData['assessName'],'auditName'=>$infoData['auditName'],'assess_userid'=>$infoData['assess'],'audit_userid'=>$infoData['audit']);
		     $this -> update ( array ('id'=>$infoData['keyId']) , $upFlagData);
		}
  	 
  }
    function model_administration_appraisal_performance_list_editFinalScore(){
  	    $data=  ( $_POST );
	    $infoData =json_decode(stripslashes($data['infoData']),true);
	    $infoData=mb_iconv($infoData);
		if($infoData['keyId']){
			 $this->tbl_name = 'appraisal_performance';
		     $upFlagData=array('finalScore'=>$infoData['finalScore']);
		     $flag=$this -> update ( array ('id'=>$infoData['keyId']) , $upFlagData);
		     if($flag){
		     	$flag=2;
		     }
		}
  	 return $flag;
  }
	   
    function model_administration_appraisal_performance_list_getDetailTplData(){
 	$tplId=$_GET ['tplId'];
     if($tplId){
			$this->tbl_name = 'appraisal_performance_template';
     	    $perData=$this->find(array('id'=>$tplId));
     	    
     	    /*表头*/
     	    $headerData=$this->model_administration_appraisal_performance_list_getHeaderData($tplId);
			if($headerData&&is_array($headerData)){
			  $colspan=$headerData['num'];	
			}
			/*表头小项*/
     		$isProjDesc=$this->model_administration_appraisal_performance_list_getIsProjDesc($tplId);
     		if($isProjDesc){
     			$isProjDescClspan=2;
     		}else{
     			$isProjDescClspan=1;
     		}
     		$isProjDescWidth=40*$isProjDescClspan.'px';
            $strList.=<<<EOT
            <tr >
            <td rowspan="2" ><div  style=" width:25px; text-align:center; vertical-align:middle;font-weight:bold;">序号</div></td>
            <td rowspan="2" colspan="$isProjDescClspan"><div style="max-width:80px; font-size:12px; font-weight:bold; min-width:$isProjDescWidth">考核要项</div></td>
            <td rowspan="2" ><div style="max-width:280px; font-size:12px; font-weight:bold; min-width:100px">详细描述</div></td>
            <td rowspan="2" style="border-right:2px solid #000000;"><div style=" width:30px;font-weight:bold;">权重(%)</div></td>
            <td colspan="$colspan" style="font-weight:bold;" > 考核尺度 </td>
EOT;
     	    if($perData['isAss']==1){
			    $strList.=<<<EOT
			    <td rowspan="2" style="border-left:2px solid #000000;"><div style="max-width:40px; min-width:30px;font-weight:bold;">自评分数</div></td>   
	            <td rowspan="2"><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">自评说明</div></td>
	            <td rowspan="2"><div style="max-width:120px; min-width:120px;font-weight:bold;font-size:12px;">附件</div></td>
EOT;
		    }
 			   
 			
			 $strList.=<<<EOT
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">考核分数</div></td>
            <td rowspan="2"><div style="max-width:50px; min-width:40px;font-weight:bold;font-size:12px;">考核描述</div></td>
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">审核分数</div></td>
             <td rowspan="2"><div style="max-width:50px; min-width:40px;font-weight:bold;font-size:12px;">审核描述</div></td>
          </tr>
EOT;
            if($headerData&&is_array($headerData)){
			  $strList.="<tr style='border-bottom:2px solid #000;'>$headerData[str]$strListPev</tr>";
			 }
     	
     	$this->tbl_name = 'appraisal_template_contents';
     	$conData=$this->findAll(array('tId'=>$tplId));
     	if($conData&&is_array($conData)){
     		$tpmData=array();
     		foreach($conData as $key =>$val){
     			if($val['kpiRight']){
     				$kpiRight+=$val['kpiRight'];
     			}
     			if($val['projectId']==$tpmData[$val['projectId']]['id']){
     				$tpmData[$val['projectId']]['val'][]=$val;
     				$tpmData[$val['projectId']]['id']=$val['projectId'];
     			}else{
     				$tpmData[$val['projectId']]['val'][]=$val;
     				$tpmData[$val['projectId']]['id']=$val['projectId'];
     			}
     		}
     		if($tpmData&&array($tpmData)){
     			$nums=0;
     			foreach($tpmData as $key =>$_tpmData){
     				if($_tpmData['val']&&is_array($_tpmData['val'])){
     					$spans=count($_tpmData['val']); 
	     				foreach($_tpmData['val'] as $tkey =>$tval){
	     				   $strs='';
	     				   if($tkey==0){
	     				   	 $nums++;
	     				   	 $strs.="<td align='center' rowspan='$spans'>$nums</td>";
	     				     $strs.="<td align='center' rowspan='$spans'>$tval[projectName]</td>";	
	     				   }
	     				   if($isProjDesc){
	     				   	 $strs.="<td align='center' ><div>$tval[projectDesc]</div></td>";
	     				   } 	
		     			   $strs.="<td align='left' ><div>$tval[kpiDescription]</div></td>";
		     			   $strs.="<td align='center' style='border-right:2px solid #000000;color:#f00'><div><input  type='text' class='mini-hidden' name='kpiRight[$tval[id]]' id='kpiRight$tval[id]' value='$tval[kpiRight]'/>$tval[kpiRight]</div></td>";
		     			   //考核尺度开始
		     			   for($i=0;$i<10;$i++){
			     			   	$spn=1;
			     			   	for($d=$i+1;$d<10;$d++){
			     			   		if($tval['columnName'.$d]&&$tval['columnName'.$d]!='NULL'){
			     			   			 break;
			     			   		}else{
			     			   			if($i<$colspan&&$d<$colspan){
					     			   	   	 $spn=$colspan-$i-($colspan-$d)+1;
					     			   	 }
			     			   		}
			     			   	}
			     			   	if($spn>1){
			     			   		$cols="colspan='$spn' ";
			     			   	}else{
			     			   		$cols='';
			     			   	}
			     			   	if($tval['columnName'.$i]&&$tval['columnName'.$i]!='NULL'){ 
			     			   	   $strs.="<td align='left' $cols ><div style='max-width:380px; min-width:100px;text-align:justify;'>".$tval['columnName'.$i]."</div></td>";	
			     			   	}
		     			   }
		     			   
		     			    //考核尺度结束							
		     			   //
		     			   
		     			   
		     			 if($perData['isAss']==1){
		     			   $strs.='<td align="center" style="border-left:2px solid #000000;">'.$evalI[$tval['id']]['perEval'].'</td>';	
		                   $strs.='<td align="center" >'.$evalI[$tval['id']]['perDesc'].'</td>';
		                   $strs.='<td align="center" >';
		                    $strs.='</td>';
		                  }
		     			 if($perData['inFlag']==5&&$_SESSION['USER_ID']==$perData['user_id']){
		     			   $perRemark='<textarea name="perRemark"  id="perRemark" class="mini-textarea" emptyText="请输入意见" style="width:100%;">'.$perData['my_opinion'].'</textarea>';
		     			 }else{
		     			   $perRemark=$perData['my_opinion'];
		     			 } 
		     			  
		     			 
		     			 
		     			   $strs.='<td align="center" >'.$evalI[$tval['id']]['assEval'].'</td>';	
		                   $strs.='<td align="center" >'.$evalI[$tval['id']]['assDesc'].'</td>';
		                   $assRemark=$perData['assess_opinion'];
		     			 
		                 	$auditFraction='';
		     			   $strs.='<td align="center" ></td>';
		     			   $strs.='<td align="center" ></td>';
		     			   $auditRemark=$perData['audit_opinion'];	
		                 if($strs){
			     			 $str.="<tr>$strs</tr>
		     			   	 ";
			     		  }
			     		  
	     				}
	     				
     				}
	     		}
     		}	
     		$contentConspan=1;
     		$qConsapn=2;
     		if($perData['isAss']==1){
     			$contentConspan+=3;
     		}
     		
     		$contentConspan=$contentConspan-1;   	
     		$contentConspan+=$colspan;
     
     		$countsColspan=($isProjDesc?4:3);
     		$countsColspanAject=$countsColspan+1;
     		$str.=<<<EOT
     		<tr style="border-top:2px solid #000000;">
            <td colspan="$countsColspan" rowspan="2" nowrap="nowrap" style="text-align:right; vertical-align:middle;"><strong>合计：</strong>
              <input type="hidden" id="tId" name="tId" value="$tplId">
              <input type="hidden" id="kId" name="kId" value="$keyId">
              <input type="hidden" id="perUserId" name="perUserId" value="$perData[user_id]">
              <input type="hidden" id="seUserId" name="seUserId" value="$_SESSION[USER_ID]">
              <input type="hidden" id="inFlag" name="inFlag" value="$perData[inFlag]">
			  <input type="hidden" id="asPers" name="asPers" value="$perData[asPers]">
			  <input type="hidden" id="asAudit" name="asAudit" value="$perData[asAudit]">
			  <input type="hidden" id="asAss" name="asAss" value="$perData[asAss]">
			  <input type="hidden" id="countFraction" name="countFraction" value="$perData[countFraction]">
			  <input type="hidden" id="asPersFra" name="asPersFra" value="$perData[count_my_fraction]">
			  <input type="hidden" id="asAuditFra" name="asAuditFra" value="$auditFraction">
			  <input type="hidden" id="asAssFra" name="asAssFra" value="$perData[count_assess_fraction]">
			  <input type="hidden" id="asPevFra" name="asPevFra" value="$pEvalCountFract">
			  <input type="hidden" id="isConfim" name="isConfim">
			  <input type="hidden" id="iseval" name="iseval" value="$iseval">
            </td>
            <td nowrap="nowrap"  rowspan="2" style="border-right:2px solid #000000;"><strong>$kpiRight%</strong></td>
            <td colspan="$colspan" nowrap="nowrap" style="text-align:right; vertical-align:middle;"><strong>得分合计：</strong></td>
EOT;
           if($perData['isAss']==1){  
			$str.=<<<EOT
     		<td nowrap="nowrap" style="border-left:2px solid #000000;" id='perAssConts'>$perData[count_my_fraction]</td>
     		<td nowrap="nowrap" >&nbsp;</td>
     		<td nowrap="nowrap" >&nbsp;</td>
EOT;
           }
          
           
     		$str.=<<<EOT
     		<td nowrap="nowrap" id='assConts'>$perData[count_assess_fraction]</td>
            <td nowrap="nowrap" >&nbsp;</td>
            <td nowrap="nowrap" id='auditConts'>$auditFraction</td>
            <td nowrap="nowrap" >&nbsp;</td>
          </tr>
          <tr style="border-bottom:2px solid #000000;">
            <td colspan="$colspan" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;vertical-align:middle;"><strong>考核总分：</strong></td>
            <td colspan="10" nowrap="nowrap" ><strong id='contAll'></span></td>
          </tr>
          <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;"><strong>考核人意见：</strong></td>
            <td colspan="$contentConspan" style="width: 100%;">$assRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="2" nowrap="nowrap"><p align="center"> <strong></strong></p></td>
          </tr>
          
          <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;"><strong>审核人意见：</strong></td>
            <td colspan="$contentConspan" style="width: 100%;">$auditRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="2" nowrap="nowrap" ><p align="center"> <strong></strong></p></td>
          </tr>
          <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000; text-align:right"><strong>被考核人意见：</strong></td>
            <td colspan="$contentConspan" style="width:100%">$perRemark</td>
            <td nowrap="nowrap" colspan="2"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="2" nowrap="nowrap" style="height: 37px"><p align="center"> <strong></strong></p></td>
          </tr>
          <tr>
            <td colspan="20"><div  style="text-align:center;margin:0px; border-bottom:0px; border-left:0px; border-right:0px;" > 
EOT;
     	}
		 $str.=<<<EOT
               <a class="mini-button" style="width:60px;" onclick="onCancel()">关闭</a></div></td>
          </tr>
EOT;
     	$strList.=$str;
     	return $strList; 
     	
     }
   }
   
   function model_administration_appraisal_performance_list_importExcel(){
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$msg = 0;
		$msg = $this -> model_administration_appraisal_performance_list_importUpFile ();
		if ( $msg == 1)
		{
			$msg = $this -> model_administration_appraisal_performance_list_importdata ( $_FILES[ 'upfile' ][ 'name' ]);
		}
		return $msg;
	}
	
	/*
	 * 上传文件
	 * @param  $_FILES
	 */
	function model_administration_appraisal_performance_list_importUpFile ()
	{
		$msg = 0;
		if ( $_FILES )
		{
			$tempname = $_FILES[ 'upfile' ][ 'tmp_name' ];
			$filename = $_FILES[ 'upfile' ][ 'name' ];
			$file_type = end ( explode ( "." , trim ( $filename ) ) );
			if ( file_exists ( $this -> filebase ) == "" )
			{
				@mkdir ( $this -> filebase , 511 );
			}
			if ( in_array ( strtolower ( $file_type ) , array ( 
																"xls","xlsx" 
			) ) )
			{
				if ( move_uploaded_file ( str_replace ( '\\\\' , '\\' , $tempname ) , WEB_TOR . $this -> filebase . $filename ) )
				{
					$msg = 1;
				
				} else
				{
					$msg = 2;
				}
			} else
			{
				$msg = 3;
			}
		}
		return $msg;
	}
	/**
	 * 读取EXCEL文件
	 * @param $filename
	 */
	function model_administration_appraisal_performance_list_getexcel ( $filename , $sheet = '' )
	{
		if ( file_exists ( $filename ) )
		{
			require_once WEB_TOR . 'includes/classes/PHPExcel.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel5.php';
			require_once WEB_TOR . 'includes/classes/PHPExcel/Reader/Excel2007.php';
			$PHPExcel = new PHPExcel ( );
			$PHPReader = new PHPExcel_Reader_Excel2007 ( $PHPExcel );
			if (  ! $PHPReader -> canRead ( $filename ) )
			{
				$PHPReader = new PHPExcel_Reader_Excel5 ( $PHPExcel );
			}
			if ( $PHPReader -> canRead ( $filename ) )
			{
				$Excel = $PHPReader -> load ( $filename );
				$data = array ();
				if ( $sheet || $sheet == 0 )
				{
					$data[ $sheet ] = $Excel -> getSheet ( $sheet ) -> toArray ( );
					$data[ $sheet ][ 'title' ] = $Excel -> getSheet ( $sheet ) -> getTitle ( );
					unset ( $Excel , $PHPReader , $PHPExcel );
					return $data;
				}
				$countnum = $Excel -> getSheetCount ( );
				for ( $i = 0 ; $i < $countnum ; $i ++  )
				{
					$data[ $i ] = $Excel -> getSheet ( $i ) -> toArray ( );
					$data[ $i ][ 'title' ] = $Excel -> getSheet ( $i ) -> getTitle ( );
				
				}
				return $data;
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
	 * 上传文件
	 * @param  $_FILES
	 */
	function model_administration_appraisal_performance_list_importdata ( $filename )
	{
		try
		{
			$this -> _db -> query ( "START TRANSACTION" );
			if ( $filename && file_exists ( $this -> filebase . $filename ) )
			{
				$Excel = mb_iconv ( $this -> model_administration_appraisal_performance_list_getexcel ( $this -> filebase . $filename , 0 ) );
				
				if ( $Excel[ 0 ]&&is_array($Excel[ 0 ]) )
				{ $this -> tbl_name = 'hrms';
					foreach ( $Excel[ 0 ] as $key => $vI )
					{
						if ( $key > 1 && ( $vI[ '0' ]))
						{
							if ( is_array ( $vI ))
							{
								 $userInfo=$this->model_list_getUserInfo($vI[ '0' ]);
								 $deptInfo=$this->model_list_getDeptInfo($vI[ '2' ]);
								 $jobInfo=$this->model_list_getJobInfo($vI[ '2' ],$vI[ '3' ]);
								 $assInfo=$this->model_list_getUserIdInfo($vI[ '8' ]);
								 $auditInfo=$this->model_list_getUserIdInfo($vI[ '11' ]);
								 $tmpTplConcent=array( 'userNo' =>$vI[ '0' ],
								 					   'user_id' =>$userInfo['USER_ID'],
								                       'userName' =>$userInfo[ 'USER_NAME'],
								                       'deptId' =>$deptInfo[ 'DEPT_ID' ],
								                       'deptName' =>$deptInfo[ 'DEPT_NAME' ],
								                       'jobName' =>$jobInfo[ 'name' ],
								                       'jobId' =>$jobInfo[ 'id' ],
								                       'comeInDate' =>$userInfo[ 'COME_DATE' ],
								                       'ReguDate' =>$userInfo[ 'JOIN_DATE' ],
								                       'name' =>$vI[ '4' ],
								                       'years' =>$vI[ '5' ],
								                       'quarter' =>$vI[ '6' ],
								                       'inFlag' =>7,
								                       'count_my_fraction' =>$vI[ '7' ],
								                       'assess_userid' =>$assInfo['USER_ID'],
								                       'assessName' =>$assInfo['USER_NAME'],
								                       'assess_status' =>1,
								                       'count_assess_fraction' =>$vI[ '9' ],
								                       'assess_date' =>$vI[ '10' ],
								                       'audit_userid' =>$auditInfo[ 'USER_ID' ],
								                       'auditName' =>$auditInfo[ 'USER_NAME' ],
								                       'audit_status' =>1,
								                       'count_audit_fraction' =>$vI[ '12' ],
								                       'audit_date' =>$vI[ '13' ],
								                       'countFraction' =>$vI[ '14' ],
								                       'finalScore' =>$vI[ '14' ],
								                       'deptRank' =>$vI[ '15' ],
								                       'deptRankPer' =>$vI[ '16' ],
								                       'isOld' =>'2',
								                       'isAsug' =>'2'
								                       );
								  $tmpTplConcentI[]=$tmpTplConcent;                       
							}
						}
					}
					if($tmpTplConcentI&&is_array($tmpTplConcentI)){
						 	$this -> tbl_name = 'appraisal_performance';
							foreach($tmpTplConcentI as $key =>$val){
								if($val&&is_array($val)&&$val['user_id']){
									$this -> create ($val);
								}
							} 
					}
					$msg = 2;
				} else
				{
					$msg = 5;
				}
			} else
			{
				$msg = 6;
			}
			$this -> _db -> query ( "COMMIT" );
			return $msg;
		} catch ( Exception $e )
		{
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		}
	
	}
function model_list_getUserInfo($userNo){
	if($userNo){
		$Sql =" SELECT a.UserCard,a.COME_DATE,a.JOIN_DATE,b.USER_ID,b.USER_NAME 
				FROM `hrms`  as a LEFT JOIN `user` as b ON a.USER_ID=b.USER_ID
				WHERE  a.UserCard='$userNo';";
			$query = $this->query ( $Sql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$userData = $row;
				
			}
	 return $userData;	
	}
	
	
} 
function model_list_getDeptInfo($deptName){
	if($deptName){
	  $this -> tbl_name = 'department';
	  $deptInfo=$this->find(array('DEPT_NAME'=>trim($deptName)));	
	}
  return $deptInfo;
}
function model_list_getJobInfo($deptName,$jobName){
	if($deptName&&$jobName){
	    $Sql =" SELECT b.`name`,b.id
				FROM  department as a LEFT JOIN user_jobs as b ON a.DEPT_ID=b.dept_id
				WHERE  a.DEPT_NAME='$deptName' AND b.`name`='$jobName' ;";
			$query = $this->query ( $Sql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$jobInfo = $row;
				
			}
	}
  return $jobInfo;
}    
function model_list_getUserIdInfo($userName){
	if($userName){
	  $this -> tbl_name = 'user';
	  $userInfo=$this->find(array('USER_NAME'=>trim($userName)));	
	}
  return $userInfo;
	
} 
 function model_administration_appraisal_performance_list_getTplContentEvalData(){
    $keyId=$_GET ['keyId'];
	$tplId=$_GET ['tplId'];
	$eValer=$_GET ['isEvals'];
     if($tplId&&$keyId){
			$this->tbl_name = 'appraisal_performance';
     	    $perData=$this->find(array('id'=>$keyId));
     	    $this->tbl_name = 'appraisal_performance_value';
     	    $evalData=$this->findAll(array('kId'=>$keyId,'tId'=>$tplId));
     	    if($evalData&&is_array($evalData)){
     	    	foreach($evalData as $key =>$val){
     	    		$evalI[$val['cId']]['perEval']=$val['perEval'];
     	    		$evalI[$val['cId']]['perDesc']=$val['perDesc'];
     	    		$evalI[$val['cId']]['assEval']=$val['assEval'];
     	    		$evalI[$val['cId']]['assDesc']=$val['assDesc'];
     	    		$evalI[$val['cId']]['auditEval']=$val['auditEval'];
     	    		$evalI[$val['cId']]['auditDesc']=$val['auditDesc'];
     	    		$evalI[$val['cId']]['pEval']=$val['pEval'];
     	    		$evalI[$val['cId']]['pDesc']=$val['pDesc'];
     	    	}
     	    	
     	    }
     	    /*表头*/
     	    $headerData=$this->model_administration_appraisal_performance_list_getHeaderData($tplId);
			if($headerData&&is_array($headerData)){
			  $colspan=$headerData['num'];	
			}
			/*评价人*/
			if($perData['user_id']){
     		   $isevalI=$this->model_administration_appraisal_performance_list_getEvaluate($keyId);
       		   $iseval=$isevalI['isFlag'];	
     		}
     		/*附件*/
     		 $attI=$this->model_administration_appraisal_performance_list_getAtt($keyId);
     		/*表头小项*/
     		$isProjDesc=$this->model_administration_appraisal_performance_list_getIsProjDesc($tplId);
     		if($isProjDesc){
     			$isProjDescClspan=2;
     		}else{
     			$isProjDescClspan=1;
     		}
     		$isProjDescWidth=40*$isProjDescClspan.'px';
            $strList.=<<<EOT
            <tr >
            <td rowspan="2" ><div  style=" width:25px; text-align:center; vertical-align:middle;font-weight:bold;">序号</div></td>
            <td rowspan="2" colspan="$isProjDescClspan"><div style="max-width:80px; font-size:12px; font-weight:bold; min-width:$isProjDescWidth">考核要项</div></td>
            <td rowspan="2" ><div style="max-width:280px; font-size:12px; font-weight:bold; min-width:100px">详细描述</div></td>
            <td rowspan="2" style="border-right:2px solid #000000;"><div style=" width:30px;font-weight:bold;">权重(%)</div></td>
            <td colspan="$colspan" style="font-weight:bold;" > 考核尺度 </td>
EOT;
     	    if($perData['isAss']==1&&!$eValer){
			    $strList.=<<<EOT
			    <td rowspan="2" style="border-left:2px solid #000000;"><div style="max-width:40px; min-width:30px;font-weight:bold;">自评分数</div></td>   
	            <td rowspan="2"><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">自评说明</div></td>
	            <td rowspan="2"><div style="max-width:120px; min-width:120px;font-weight:bold;font-size:12px;">附件</div></td>
EOT;
		    }
 			   
 			if($iseval){
 				if($perData['isAss']!=1){
 				  $styleLeft='style="border-left:2px solid #000000;"';
 				}
 				if($isevalI['userEid']&&is_array($isevalI['userEid'])){
	 			 	foreach($isevalI['userEid'] as $ekey=>$_userEid){
	 			 		if(($iseval&&($perData['inFlag']==3||($perData['isAss']!=1&&$perData['inFlag']==2)))&&($_SESSION['USER_ID']==$_userEid)&&$isevalI[$_SESSION['USER_ID']]['flag']==1){
	 			 		   $strList.='<td colspan="2" '.$styleLeft.' ><div style="font-weight:bold;">评价人<br/>('.$isevalI[$_SESSION['USER_ID']]['name'].')</div></td>';	
	 			 		   $strListPev.='<td  '.$styleLeft.' ><div style="max-width:40px; min-width:30px;font-weight:bold;">评价分数</div></td>';	
		                   $strListPev.='<td ><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">评价描述</div></td>';
	 			 		}else if($iseval&&$isevalI[$_userEid]['flag']==2&&$eValer&&$_SESSION['USER_ID']==$_userEid){
	 			 		   $strList.='<td colspan="2" '.$styleLeft.' ><div style="font-weight:bold;">评价人<br/>('.$isevalI[$_userEid]['name'].')</div></td>';	
		     			   $strListPev.='<td  '.$styleLeft.' ><div style="max-width:40px; min-width:30px;font-weight:bold;">评价分数</div></td>';	
		                   $strListPev.='<td ><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">评价描述</div></td>';
					    }else if($iseval&&$isevalI[$_userEid]['flag']==2){
		 			 		   $strList.='<td colspan="2" '.$styleLeft.' ><div style="font-weight:bold;">评价人<br/>('.$isevalI[$_userEid]['name'].')</div></td>';	
			     			   $strListPev.='<td  '.$styleLeft.' ><div style="max-width:40px; min-width:30px;font-weight:bold;">评价分数</div></td>';	
			                   $strListPev.='<td ><div style="max-width:80px; min-width:40px;font-weight:bold;font-size:12px;">评价描述</div></td>';
					    }		
		     	   }	
		     	}
		     	if($isevalI['cofimNum']>0&&!$eValer){
 				$strList.=<<<EOT
			    <td rowspan="2"  ><div style="max-width:40px; min-width:30px;font-weight:bold;">总评价分</div></td>
EOT;
		     	}
		    }
		    if(!$eValer){
			 $strList.=<<<EOT
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">考核分数</div></td>
            <td rowspan="2"><div style="max-width:50px; min-width:40px;font-weight:bold;font-size:12px;">考核描述</div></td>
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">审核分数</div></td>
            <td rowspan="2"><div style="max-width:50px; min-width:30px;font-weight:bold;font-size:12px;">审核描述</div></td>
          </tr>
EOT;
		    }else{
		    	$strList.='</tr>';
		    	
		    }
            if($headerData&&is_array($headerData)){
			  $strList.="<tr style='border-bottom:2px solid #000;'>$headerData[str]$strListPev</tr>";
			 }
     	
     	$this->tbl_name = 'appraisal_template_contents';
     	$conData=$this->findAll(array('tId'=>$tplId));
     	if($conData&&is_array($conData)){
     		$tpmData=array();
     		foreach($conData as $key =>$val){
     			if($val['kpiRight']){
     				$kpiRight+=$val['kpiRight'];
     			}
     			if($val['projectId']==$tpmData[$val['projectId']]['id']){
     				$tpmData[$val['projectId']]['val'][]=$val;
     				$tpmData[$val['projectId']]['id']=$val['projectId'];
     			}else{
     				$tpmData[$val['projectId']]['val'][]=$val;
     				$tpmData[$val['projectId']]['id']=$val['projectId'];
     			}
     		}
     		if($tpmData&&array($tpmData)){
     			$nums=0;
     			foreach($tpmData as $key =>$_tpmData){
     				if($_tpmData['val']&&is_array($_tpmData['val'])){
     					$spans=count($_tpmData['val']); 
	     				foreach($_tpmData['val'] as $tkey =>$tval){
	     				   $strs='';
	     				   if($tkey==0){
	     				   	 $nums++;
	     				   	 $strs.="<td align='center' rowspan='$spans'>$nums</td>";
	     				     $strs.="<td align='center' rowspan='$spans'>$tval[projectName]</td>";	
	     				   }
	     				   if($isProjDesc){
	     				   	 $strs.="<td align='center' ><div>$tval[projectDesc]</div></td>";
	     				   } 	
		     			   $strs.="<td align='left' ><div>$tval[kpiDescription]</div></td>";
		     			   $strs.="<td align='left' style='border-right:2px solid #000000;color:#f00'><div><input  type='text' class='mini-hidden' name='kpiRight[$tval[id]]' id='kpiRight$tval[id]' value='$tval[kpiRight]'/>$tval[kpiRight]</div></td>";
		     			   //考核尺度开始
		     			   for($i=0;$i<10;$i++){
			     			   	$spn=1;
			     			   	for($d=$i+1;$d<10;$d++){
			     			   		if(($tval['columnName'.$d]||$tval['columnName'.$d]=='0')&&$tval['columnName'.$d]!='NULL'){
			     			   			 break;
			     			   		}else{
			     			   			if($i<$colspan&&$d<$colspan){
					     			   	   	 $spn=$colspan-$i-($colspan-$d)+1;
					     			   	 }
			     			   		}
			     			   	}
			     			   	if($spn>1){
			     			   		$cols="colspan='$spn' ";
			     			   	}else{
			     			   		$cols='';
			     			   	}
			     			   	if(($tval['columnName'.$i]||$tval['columnName'.$i]=='0')&&$tval['columnName'.$i]!='NULL'){ 
			     			   	   $strs.="<td align='left' $cols ><div style='max-width:380px; min-width:100px;text-align:justify;'>".$tval['columnName'.$i]."</div></td>";	
			     			   	}
		     			   }
		     			   
		     			    //考核尺度结束							
		     			   //
		     			   
		     			   
		     			 if($perData['isAss']==1&&$perData['inFlag']==2&&$_SESSION['USER_ID']==$perData['user_id']&&!$eValer){
		     			   $strs.='<td align="center" style="border-left:2px solid #000000;"><input  id="'.$tval['id'].'" name="perEval['.$tval['id'].']" value="'.$evalI[$tval['id']]['perEval'].'" required="true" class="mini-spinner perAss" onvaluechanged="setEvals" style="width:45px" decimalPlaces="1"  minValue="0" maxValue="11"  /></td>';	
		                   $strs.='<td align="center" ><textarea name="description['.$tval['id'].']" id="Description'.$tval['id'].'" class="mini-textarea" emptyText="请输入描述" style="width:70px;height:120px;">'.$evalI[$tval['id']]['perDesc'].'</textarea></td>';
		     			   $strs.='<td align="center" id="upFile'.$tval['id'].'">';
		     			    $strAtt='';
		     			   if($attI[$tval['id']]&&is_array($attI[$tval['id']])){
		     			   	 foreach($attI[$tval['id']] as $key=>$val){
		     			   	 	$strAtt.='<div id="attDel'.$key.'"><a target="_blank" href="index1.php?model=file_uploadfile_management&action=toDownFile&inDocument=../../'.$this->filebase.$val['keyName'].'&originalName='.$val['trueName'].'" title="'.$val['trueName'].'">'.$val['trueName'].'</a><img onclick="delAtt('.$key.')" src="js/extui/themes/dloa/images/bgs/delete.png" style="border:0px;cursor:pointer;"></div>';
		     			   	 }
		     			   }
		     			   $strs.=$strAtt;
						   $strs.='<div id="upFile_'.$tval['id'].'_0" style=" width:100%;"><input type="file" size="1" onchange="upFile('.$tval['id'].',0,this);"
							name="upFile['.$tval['id'].'][]" value="" /><img onclick="delUpFile('.$tval['id'].',0)" src="js/extui/themes/dloa/images/bgs/delete.png" style="border:0px;cursor:pointer;"></div></td>';
		     			 }else if($perData['isAss']==1&&!$eValer){
		     			   $strs.='<td align="center" style="border-left:2px solid #000000;">'.$evalI[$tval['id']]['perEval'].'</td>';	
		                   $strs.='<td align="center" >'.$evalI[$tval['id']]['perDesc'].'</td>';
		                   $strs.='<td align="center" >';
		                   $strAtt='';
		                   if($attI[$tval['id']]&&is_array($attI[$tval['id']])){
		     			   	 foreach($attI[$tval['id']] as $key=>$val){
		     			   	 	$strAtt.='<div><a target="_blank" href="index1.php?model=file_uploadfile_management&action=toDownFile&inDocument=../../'.$this->filebase.$val['keyName'].'&originalName='.$val['trueName'].'" title="'.$val['trueName'].'">'.$val['trueName'].'</a></div>';
		     			   	 }
		     			   }
		                   $strs.=$strAtt.'</td>';
		                  }
		     			 if($perData['inFlag']==5&&$_SESSION['USER_ID']==$perData['user_id']){
		     			   $perRemark='<textarea name="perRemark"  id="perRemark" class="mini-textarea" emptyText="请输入意见" style="width:100%;">'.$perData['my_opinion'].'</textarea>';
		     			 }else{
		     			   $perRemark=$perData['my_opinion'];
		     			 } 
		     			 if($isevalI['userEid']&&is_array($isevalI['userEid'])){
		     			 	foreach($isevalI['userEid'] as $ekey=>$_userEid){
			     			   	 if(($iseval&&($perData['inFlag']==3||($perData['isAss']!=1&&$perData['inFlag']==2)))&&($_SESSION['USER_ID']==$_userEid)&&$isevalI[$_SESSION['USER_ID']]['flag']==1){
				     			   $pEvalCountFract=$isevalI[$_SESSION['USER_ID']]['countFract'];
				     			   $strs.='<td align="center" '.$styleLeft.'><input  id="'.$tval['id'].'" name="pEval['.$tval['id'].']" required="true" value="'.$isevalI[$_SESSION['USER_ID']]['pEval'][$tval['id']]['fract'].'" class="mini-spinner perAss" onvaluechanged="setPevals" style="width:45px" decimalPlaces="1"  minValue="0" maxValue="11"  /></td>';	
				                   $strs.='<td align="center" ><textarea  name="pDescription['.$tval['id'].']" id="pDescription'.$tval['id'].'" class="mini-textarea" emptyText="请输入评价描述" style="width:70px;height:120px;">'.$isevalI[$_SESSION['USER_ID']]['pEval'][$tval['id']]['desc'].'</textarea></td>';
			     			   	 }else if($iseval&&$isevalI['cofimNum']>0&&$isevalI[$_userEid]['flag']==2&&$eValer&&$_SESSION['USER_ID']==$_userEid){
				     			   $strs.='<td align="center" '.$styleLeft.'>'.$isevalI[$_userEid]['pEval'][$tval['id']]['fract'].'</td>';	
				                   $strs.='<td align="center" >'.$isevalI[$_userEid]['pEval'][$tval['id']]['desc'].'</td>';
				     			 }else if($iseval&&$isevalI['cofimNum']>0&&$isevalI[$_userEid]['flag']==2&&!$eValer){
				     			   $strs.='<td align="center" '.$styleLeft.'>'.$isevalI[$_userEid]['pEval'][$tval['id']]['fract'].'</td>';	
				                   $strs.='<td align="center" >'.$isevalI[$_userEid]['pEval'][$tval['id']]['desc'].'</td>';
				     			 }	
		     			 	}	
		     			 } 
		     			 if($iseval&&$isevalI['cofimNum']>0&&!$eValer){
		     			   $strs.='<td align="center" >'.$evalI[$tval['id']]['pEval'].'</td>';	
		                 }
		     			 if((($perData['inFlag']==4)||($perData['isAss']!=1&&$perData['inFlag']==2&&!$iseval))&&$_SESSION['USER_ID']==$perData['assess_userid']&&!$eValer){
		     			   $assRemark='<textarea name="assRemark"  id="assRemark" class="mini-textarea" emptyText="请输入意见" style="width:100%;">'.$perData['assess_opinion'].'</textarea>';
		     			   $strs.='<td align="center" ><input id="'.$tval['id'].'" name="perEval['.$tval['id'].']"  value="'.$evalI[$tval['id']]['assEval'].'" required="true" class="mini-spinner perAss" style="width:45px" onvaluechanged="setAsEvals"  minValue="0" maxValue="11" decimalPlaces="1" /></td>';	
		                   $strs.='<td align="center" ><textarea  name="description['.$tval['id'].']" id="Description'.$tval['id'].'" class="mini-textarea" emptyText="请输入说明" style="width:80px; height:100%;">'.$evalI[$tval['id']]['assDesc'].'</textarea></td>';
		     			 }else if(!$eValer){
		     			   $strs.='<td align="center" >'.$evalI[$tval['id']]['assEval'].'</td>';	
		                   $strs.='<td align="center" >'.$evalI[$tval['id']]['assDesc'].'</td>';
		                   $assRemark=$perData['assess_opinion'];
		     			 }
		     			 if(($perData['inFlag']==6&&$perData['assess_status']==1)&&$_SESSION['USER_ID']==$perData['audit_userid']&&!$eValer){
		     			   $auditFraction=$perData['count_audit_fraction'];
		     			   $auditRemark='<textarea name="auditRemark"  id="auditRemark" class="mini-textarea" emptyText="请输入意见" style="width:100%;">'.$perData['audit_opinion'].'</textarea>';
		     			   $strs.='<td align="center" ><input  id="'.$tval['id'].'" name="perEval['.$tval['id'].']" value="'.$evalI[$tval['id']]['auditEval'].'" required="true" class="mini-spinner perAss" onvaluechanged="setAuditEvals" style="width:45px"  minValue="0" maxValue="11" decimalPlaces="1" /></td>';	
		                   $strs.='<td align="center" ><textarea  name="description['.$tval['id'].']" id="Description'.$tval['id'].'" class="mini-textarea" emptyText="请输入说明" style="width:80px; height:100%;">'.$evalI[$tval['id']]['auditDesc'].'</textarea></td>';
		     			 }else if(!$eValer){
		                 	$auditFraction='';
		     			   $strs.='<td align="center" >'.($perData['inFlag']>6?$evalI[$tval['id']]['auditEval']:'').'</td>';
		     			   $strs.='<td align="center" >'.($perData['inFlag']>6?$evalI[$tval['id']]['auditDesc']:'').'</td>';
		     			   $auditRemark=$perData['audit_opinion'];	
		                 }
		     			 if($strs){
			     			 $str.="<tr>$strs</tr>
		     			   	 ";
			     		  }
			     		  
	     				}
	     				
     				}
	     		}
     		}	
     		$contentConspan=1;
     		$qConsapn=2;
     		if($perData['isAss']==1){
     			$contentConspan+=3;
     		}
     		if($iseval==1){
     			$contentConspan+=2*$isevalI['cofimNum'];
     			if($isevalI['cofimNum']>0){
     				$contentConspan+=1;
     			}
     			if($isevalI[$_SESSION['USER_ID']]['flag']==1&&($perData[inFlag]==3||$perData[isAss]!=1)){
     				$contentConspan+=2;
     			}
     		}
     		$contentConspan=$contentConspan;   	
     		$contentConspan+=$colspan-1;
     
     		$countsColspan=($isProjDesc?4:3);
     		$countsColspanAject=$countsColspan+1;
     		$str.=<<<EOT
     		<tr style="border-top:2px solid #000000;">
            <td colspan="$countsColspan" rowspan="2" nowrap="nowrap" style="text-align:right; vertical-align:middle;"><strong>合计：</strong>
              <input type="hidden" id="tId" name="tId" value="$tplId">
              <input type="hidden" id="kId" name="kId" value="$keyId">
              <input type="hidden" id="perUserId" name="perUserId" value="$perData[user_id]">
              <input type="hidden" id="seUserId" name="seUserId" value="$_SESSION[USER_ID]">
              <input type="hidden" id="inFlag" name="inFlag" value="$perData[inFlag]">
			  <input type="hidden" id="asPers" name="asPers" value="$perData[asPers]">
			  <input type="hidden" id="asAudit" name="asAudit" value="$perData[asAudit]">
			  <input type="hidden" id="asAss" name="asAss" value="$perData[asAss]">
			  <input type="hidden" id="countFraction" name="countFraction" value="$perData[countFraction]">
			  <input type="hidden" id="asPersFra" name="asPersFra" value="$perData[count_my_fraction]">
			  <input type="hidden" id="asAuditFra" name="asAuditFra" value="$auditFraction">
			  <input type="hidden" id="asAssFra" name="asAssFra" value="$perData[count_assess_fraction]">
			  <input type="hidden" id="asPevFra" name="asPevFra" value="$pEvalCountFract">
			  <input type="hidden" id="isConfim" name="isConfim">
			  <input type="hidden" id="iseval" name="iseval" value="$iseval">
            </td>
            <td nowrap="nowrap"  rowspan="2" style="border-right:2px solid #000000;"><strong>$kpiRight%</strong></td>
            <td colspan="$colspan" nowrap="nowrap" style="text-align:right; vertical-align:middle;"><strong>得分合计：</strong></td>
EOT;
           if($perData['isAss']==1&&!$eValer){  
			$str.=<<<EOT
     		<td nowrap="nowrap" style="border-left:2px solid #000000;" id='perAssConts'>$perData[count_my_fraction]</td>
     		<td nowrap="nowrap" >&nbsp;</td>
     		<td nowrap="nowrap" >&nbsp;</td>
EOT;
           }
           if($isevalI['userEid']&&is_array($isevalI['userEid'])){
		 	foreach($isevalI['userEid'] as $ekey=>$_userEid){
 			   	 if(($iseval&&($perData['inFlag']==3||($perData['isAss']!=1&&$perData['inFlag']==2)))&&($_SESSION['USER_ID']==$_userEid)&&$isevalI[$_SESSION['USER_ID']]['flag']==1){
     			   $str.="<td nowrap='nowrap' $styleLeft id='pAssConts'>".$isevalI[$_userEid]['countFract']."</td>";	
                   $str.='<td nowrap="nowrap" >&nbsp;</td>';
     			 }else if($iseval&&$isevalI['cofimNum']>0&&$isevalI[$_userEid]['flag']==2&&$eValer&&$_SESSION['USER_ID']==$_userEid){
     			   $str.="<td nowrap='nowrap' $styleLeft>".$isevalI[$_userEid]['countFract'].'</td>';	
                   $str.='<td nowrap="nowrap" >&nbsp;</td>';
     			 }else if($iseval&&$isevalI['cofimNum']>0&&$isevalI[$_userEid]['flag']==2&&!$eValer){
     			   $str.="<td nowrap='nowrap' $styleLeft>".$isevalI[$_userEid]['countFract'].'</td>';	
                   $str.='<td nowrap="nowrap" >&nbsp;</td>';
     			 }	
		 	}	
		 } 
           if($iseval&&$isevalI['cofimNum']>0&&!$eValer){
			 $str.=<<<EOT
     		<td nowrap="nowrap"  >$perData[pevFraction]</td>
EOT;
             }
            if($eValer){
            	$str.=<<<EOT
	           </tr>
	           <tr style="border-bottom:2px solid #000000;">
	             <td colspan="$colspan" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;vertical-align:middle;"><strong>考核总分：</strong></td>
	             <td colspan="10" nowrap="nowrap" ><strong id='contAll'></span></td>
	           </tr>
	           <tr>
               <td colspan="20"><div  style="text-align:center;margin:0px; border-bottom:0px; border-left:0px; border-right:0px;" > 
EOT;
           }else{
            	$str.=<<<EOT
	     		<td nowrap="nowrap" id='assConts'>$perData[count_assess_fraction]</td>
	            <td nowrap="nowrap" >&nbsp;</td>
	            <td nowrap="nowrap" id='auditConts'>$auditFraction</td>
	            <td nowrap="nowrap" >&nbsp;</td>
	          </tr>
	          <tr style="border-bottom:2px solid #000000;">
	            <td colspan="$colspan" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;vertical-align:middle;"><strong>考核总分：</strong></td>
	            <td colspan="10" nowrap="nowrap" ><strong id='contAll'></span></td>
	          </tr>
EOT;               
            }
            if(!$eValer){ 
     		$str.=<<<EOT
          <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;"><strong>考核人意见：</strong></td>
            <td colspan="$contentConspan" style="width: 100%;">$assRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="2" nowrap="nowrap"><p align="center"> <strong>$perData[assessName]</strong></p></td>
          </tr>
          <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000; text-align:right"><strong>被考核人意见：</strong></td>
            <td colspan="$contentConspan" style="width:100%">$perRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="2" nowrap="nowrap" style="height: 37px"><p align="center"> <strong>$perData[userName]</strong></p></td>
          </tr>
          <tr>
            <td colspan="$countsColspanAject" nowrap="nowrap" style="border-right:2px solid #000000;text-align:right;"><strong>审核人意见：</strong></td>
            <td colspan="$contentConspan" style="width: 100%;">$auditRemark</td>
            <td nowrap="nowrap" colspan="$qConsapn"><p align="center"> <strong>签名人：</strong></p></td>
            <td colspan="2" nowrap="nowrap" ><p align="center"> <strong>$perData[auditName]</strong></p></td>
          </tr>
          <tr>
            <td colspan="20"><div  style="text-align:center;margin:0px; border-bottom:0px; border-left:0px; border-right:0px;" > 
EOT;
            }
     	}
		    if($iseval&&$perData['inFlag']==3&&in_array($_SESSION['USER_ID'],$isevalI['userEid'])&&$isevalI[$_SESSION['USER_ID']]['flag']==1) 
		    {  
			$str.=<<<EOT
			 <a class="mini-button" style="width:60px;" onclick="submitForm(1)">保存</a> <span style="display:inline-block;width:25px;"></span>
             <a class="mini-button" style="width:60px;" onclick="submitForm(2)">提交</a> <span style="display:inline-block;width:25px;"></span>
EOT;
           }/*else if($perData['inFlag']==6&&$_SESSION['USER_ID']==$perData['audit_userid']){
           	$str.=<<<EOT
			 <a class="mini-button" style="width:60px;" onclick="submitForm(1)">保存</a> <span style="display:inline-block;width:25px;"></span>
EOT;
           }*/
           $str.=<<<EOT
               <a class="mini-button" style="width:60px;" onclick="onCancel()">关闭</a></div></td>
          </tr>
EOT;
     	$strList.=$str;
     	return $strList; 
     	
     }
   }
 function model_administration_appraisal_performance_list_optionStatus(){
 	$inFlag=$_POST['inFlag'];
 	$keyId=$_POST['keyId'];
 	if($inFlag&&$keyId){
 		$this->tbl_name = 'appraisal_performance';
		$flag=$this -> update ( array ( 'id' =>$keyId) , array('inFlag'=>$inFlag));
		if($flag){
			$flag=2;
		}
 	}
 	return $flag;
 }
  function model_administration_appraisal_performance_list_init(){
  	 set_time_limit (0);
	 ini_set ( 'memory_limit' , '128M' );
  	 $Sql ="SELECT a.id,c.USER_ID,c.USER_NAME,d.COME_DATE,d.JOIN_DATE,c.DEPT_ID,e.DEPT_NAME,c.jobs_id,f.`name`,a.count_audit_fraction,d.UserCard,a.assess_userid,a.audit_userid,
  	 		       a.count_my_fraction,a.count_assess_fraction   
			FROM   appraisal_performance a 
			LEFT JOIN   user c  ON a.user_id=c.USER_ID
			LEFT JOIN hrms d ON (c.USER_ID=d.USER_ID) 
			LEFT JOIN department e ON (c.DEPT_ID=e.DEPT_ID)
			LEFT JOIN user_jobs  f ON (c.jobs_id=f.id)
           ";
			$query = $this->query ( $Sql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$this->tbl_name = 'user';
				$A=$this->find( array ('USER_ID' => trim($row['assess_userid'])) );
				$B=$this->find( array ('USER_ID' => trim($row['audit_userid'])) );
				$upFlagData=array('userName'=>$row['USER_NAME'],'assessName'=>$A['USER_NAME'],'auditName'=>$B['USER_NAME'],
                                  'deptId'=>$row['DEPT_ID'],'deptName'=>$row['DEPT_NAME'],'jobId'=>$row['jobs_id'],'jobName'=>$row['name'],
                                  'comeInDate'=>$row['COME_DATE'],'ReguDate'=>$row['JOIN_DATE'],'my_status'=>1,'userNo'=>$row['UserCard'],
								  'assess_status'=>1,'audit_status'=>1,'countFraction'=>$row['count_audit_fraction']*10,'inFlag'=>7,'isOld'=>2,
								  'count_my_fraction'=>$row['count_my_fraction']*10,'count_assess_fraction'=>$row['count_assess_fraction']*10,'count_audit_fraction'=>$row['count_audit_fraction']*10,);
				$this->tbl_name = 'appraisal_performance';
				$flag=$this -> update ( array ( 'id' =>$row['id']) , $upFlagData); 
				
			}
  }  
 
 
 function model_administration_appraisal_performance_list_exportExaExcel() {
 		global $func_limit;
		$wordkey = ($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$deptId=$deptId?$deptId:($func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID']);
		$deptId=str_replace(';;','',$deptId);
		$deptId=trim($deptId,',');
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and a.deptId in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		if($tplStatus){
			if($tplStatus==1 || $tplStatus==4|| $tplStatus==5|| $tplStatus==6|| $tplStatus==7 || $tplStatus==10){
				$sqlstr.=" and a.inFlag='$tplStatus'";
			}elseif($tplStatus==2){
				$sqlstr.=" and a.inFlag='$tplStatus' and a.isAss=1 ";
			}elseif($tplStatus==3){
				$sqlstr.=" and (a.inFlag='$tplStatus' or (a.inFlag='2' and a.isAss<>1 and a.isEval=2) ) ";
			} 
		}else{
			$sqlstr.=" and ( a.inFlag='6' ) ";
		}
		$title = '员工考核数据';
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$Title = array (array ($title));
		$ExcelData[] = array ("ID","员工号","被考核人","所属部门","职位","年份","考核周期","状态","自评总分",
									  "评价总分","考核总分","审核总分","加权得分","最终得分","排名","排名比例");
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " order by a.id desc";
		}
		$sql="SELECT a.id,a.userNo,a.userName,b.deptNameS,b.deptNameT,b.deptName as deptNameA,a.deptName,a.jobName,a.comeInDate,a.ReguDate,a.name,a.years,a.quarter,
			  a.pevFraction,a.inFlag,a.count_my_fraction,a.assessName,a.assess_status,a.count_assess_fraction,a.assess_date,a.auditName,
			  a.audit_status,a.count_audit_fraction,a.audit_date,a.countFraction,a.deptRank,a.deptRankPer,a.finalScore
			  FROM appraisal_performance as a LEFT JOIN oa_hr_personnel as b ON (a.user_id=b.userAccount) 
			  WHERE  1  $sqlstr $order ";				
		$query = $this->query ( $sql );
		$data=array();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['date']=date('Y-m-d',strtotime($row['date']));
			$row['assess_date']=$row['assess_date']?date('Y-m-d',strtotime($row['assess_date'])):'';
			$row['audit_date']=$row['audit_date']?date('Y-m-d',strtotime($row['audit_date'])):'';
			$row['quarter']=$this->model_administration_appraisal_transformCycle($row['quarter']);
			if($row['inFlag']==1){
			  $row['inFlag']='未开始';	 
		     }else if($row['inFlag']==2){
				if($row['isAss']==1){
					$row['inFlag']='自评中';	
				}else if($row['isEval']==2&&$row['isAss']!=1){
					$row['inFlag']='评价中';	
				}else{
					$row['inFlag']='考核中';
				} 
		     }else if($row['inFlag']==3){
			  	$row['inFlag']='评价中';	 
		     }else if($row['inFlag']==4){
			  	$row['inFlag']='考核中';	 
		     }else if($row['inFlag']==5){
			  	$row['inFlag']='审核中';	 
		     }else if($row['inFlag']==6){
			   $row['inFlag']='结果发布中';	 
		     }else if($row['inFlag']==7){
		     	if($row['inFlag']==1){
		     	   $row['inFlag']='填写被考核意见中';	
		     	}else{
		     		$row['inFlag']='完成';
		     	}
			   	 
		     }else if($row['inFlag']==10){
		     	$row['inFlag']='关闭';	
		     }
		     if($row['assess_status']==1){
			   $row['assess_status']='已考核';	 
			 }else{
			  $row['assess_status']='待考核';	 
			 }
			  if($row['audit_status']==1){
			     $row['audit_status']='已审核';	 
			 }else{
			    $row['audit_status']='待审核';	 
			 }
			$ExcelData[]=array($row['id'],$row['userNo'],$row['userName'],$row['deptName'],$row['jobName'],
										$row['years'],$row['quarter'],
										$row['inFlag'],$row['count_my_fraction'],$row['pevFraction'],
										$row['count_assess_fraction'],
										$row['count_audit_fraction'],$row['countFraction'],$row['finalScore'],$row['deptRank'],$row['deptRankPer']);
			
		}
       	$data = $ExcelData;
       	$total = ( count ( $data ) - 1 );
		$xls = new includes_class_excel ( $title . '.xls' );
		$xls -> SetTitle ( array ( $title) , $Title );
		$xls -> SetContent (array ($data));
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A1:P1' );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A2:P2' ) -> getFont ( ) -> setBold ( true );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:P'.($total+2)) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$styleArray = array ( 
								'borders' => array ( 
													'allborders' => array ( 
																			'style' => PHPExcel_Style_Border :: BORDER_THIN , 
																			'color' => array ( 
																							'argb' => '00000000' 
																			) 
													) 
								) 
			);
		
		
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:P'.($total+2) ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:P'.($total+2)) -> applyFromArray ( $styleArray );
		
		$xls -> objActSheet[ 0 ] -> setCellValue ( 'A' . ( $total + 5 ) , un_iconv ( '合计：' . $total ) );
		$columnData=array('A','B','C','D','E','F','G','H','I','J','K','M','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA');	
		foreach($columnData as $key =>$val){
		  $xls -> objActSheet[ 0 ] -> getColumnDimension ( $val ) -> setWidth ( 15 );
		}
		$xls -> OutPut ( );
	}
	
	
  	function model_administration_appraisal_performance_list_importExExcel(){
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$typeId = $this->ReQuest("typeId");
		$msg = 0;
		$msg = $this -> model_administration_appraisal_performance_list_importUpFile ();
		if ( $msg == 1)
		{
			$msg = $this -> model_administration_appraisal_performance_list_importExdata ( $_FILES[ 'upfile' ][ 'name' ],$typeId);
		}
		return $msg;
	}
	function model_administration_appraisal_performance_list_importExdata ( $filename,$flag)
	{
		try
		{
			$this -> _db -> query ( "START TRANSACTION" );
			$mail_server = new includes_class_sendmail ( );
			if ( $filename && file_exists ( $this -> filebase . $filename ) )
			{
				$Excel = mb_iconv ( $this -> model_administration_appraisal_performance_list_getexcel ( $this -> filebase . $filename , 0 ) );
				
				if ( $Excel[ 0 ]&&is_array($Excel[ 0 ]) )
				{ 
					foreach ( $Excel[ 0 ] as $key => $vI )
					{
						if ( $key > 1 && ( $vI[ '0' ]))
						{
							if ( is_array ( $vI ))
							{								
								 $dataI=$this->model_administration_appraisal_performance_list_checkDate($vI[ '0' ]);
								 if($dataI['id']){
								 	if($flag=='2'){
								   		$tmpTplConcent=array( 'finalScore' =>$vI[ '13' ],'deptRank' =>$vI[ '14' ],'deptRankPer' =>$vI[ '15' ],'inFlag' =>'7');
								 	}else{
								 		$tmpTplConcent=array( 'finalScore' =>$vI[ '13' ]);
								 	}
								   $tmpTplConcentI[$dataI['id']]=$tmpTplConcent;	
								 }else{
								 	$msgI['msg'][]=$vI[ '2' ];
								 }
								                        
							}
						}
					}
					if($tmpTplConcentI&&is_array($tmpTplConcentI)){
						 	$this -> tbl_name = 'appraisal_performance';
							foreach($tmpTplConcentI as $key =>$val){
								if($val&&is_array($val)&&$val['finalScore']){
									
									if($flag=='2'){
										$infoArr=$this->model_list_getAppraisalInfo($key);
										if($infoArr&&is_array($infoArr)){
											$this -> update (array ('id' => $key),$val);
											$tplWeek=$infoArr['years'].'年  '.($infoArr['quarter']==5?' 上半年':($infoArr['quarter']==6?' 下半年':$infoArr['quarter']==7?' 全年':' 第'.$infoArr['quarter'].'季')).'度';
											$tpLName=$infoArr['name'].' '.$tplWeek.'考核表';
				 							$body = " 您好，" . $infoArr[ 'USER_NAME' ] . "<br/> $tpLName 已发布考核结果，请您登录OA进行本此考核意见反馈。<br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
			      							$mail_server -> send ($tpLName."已发布考核结果" , $body , $infoArr['EMAIL'] );
										}
											
									}else{
										$this -> update (array ('id' => $key),$val);
									}
								}
							} 
					}
					$msgI['msg'] = 2;
				} else
				{
					$msgI['msg'] = 5;
				}
			} else
			{
				$msgI['msg'] = 6;
			}
			$this -> _db -> query ( "COMMIT" );
			return $msgI;
		} catch ( Exception $e )
		{
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		}
	
	}
	function  model_administration_appraisal_performance_list_checkDate($id){
		if($id){
			$Sql ="SELECT id FROM `appraisal_performance` WHERE  id='$id';";
			$row = $this->get_one ( $Sql );
		 	return $row;	
		}
	}
	
	function model_administration_appraisal_performance_list_importUpExcel(){
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$msg = 0;
		$msg = $this -> model_administration_appraisal_performance_list_importUpFile ();
		if ( $msg == 1)
		{
			$msg = $this -> model_administration_appraisal_performance_list_importUpdata ( $_FILES[ 'upfile' ][ 'name' ]);
		}
		return $msg;
	}
	function model_administration_appraisal_performance_list_importUpdata ( $filename )
	{
		try
		{
			$this -> _db -> query ( "START TRANSACTION" );
			if ( $filename && file_exists ( $this -> filebase . $filename ) )
			{
				$Excel = mb_iconv ( $this -> model_administration_appraisal_performance_list_getexcel ( $this -> filebase . $filename , 0 ) );
				
				if ( $Excel[ 0 ]&&is_array($Excel[ 0 ]) )
				{ 
					foreach ( $Excel[ 0 ] as $key => $vI )
					{
						if ( $key > 1 && ( $vI[ '0' ]))
						{
							if ( is_array ( $vI ))
							{								
								 $dataI=$this->model_administration_appraisal_performance_list_checkDate($vI[ '0' ]);
								 if($dataI['id']){
								   $tmpTplConcent=array( 'countFraction' =>$vI[ '24' ],
														 'finalScore' =>$vI[ '25' ],
														 'deptRank' =>$vI[ '26' ],
														 'deptRankPer' =>$vI[ '27' ]);
								   $tmpTplConcentI[$dataI['id']]=$tmpTplConcent;	
								 }else{
								 	$msgI['msg'][]=$vI[ '2' ];
								 }
								                        
							}
						}
					}
					if($tmpTplConcentI&&is_array($tmpTplConcentI)){
						 	$this -> tbl_name = 'appraisal_performance';
							foreach($tmpTplConcentI as $key =>$val){
								if($val&&is_array($val)&&$key){
									$this -> update (array ('id' => $key),$val);
								}
							} 
					}
					$msgI['msg'] = 2;
				} else
				{
					$msgI['msg'] = 5;
				}
			} else
			{
				$msgI['msg'] = 6;
			}
			$this -> _db -> query ( "COMMIT" );
			return $msgI;
		} catch ( Exception $e )
		{
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		}
	
	}	
	function  model_list_getAppraisalInfo($id){
		if($id){
			$Sql ="SELECT a.id,a.name,b.USER_NAME,b.EMAIL,a.years,a.quarter FROM `appraisal_performance` a LEFT JOIN user b on a.user_id=b.user_id where a.inFlag=6 AND a.id='$id';";
			$row = $this->get_one ( $Sql );
			return $row;	
		}
	}

	function model_administration_appraisal_toEmail ()
	{
		$tplStatus = $this->ReQuest("tplStatus");
		$deptId = trim($this->ReQuest("deptId"),',');
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$wordkey = $this->ReQuest("wordkey");
		$sqlstr="";
		$tplStyleFlag='';
		if($deptId){
			$sqlstr.=" and a.deptId in ($deptId) ";
		}
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.userName like '%$wordkey%' )";
		}
		
		if($tplStatus==2){
			$tplStyleFlag='自评';
		}else if($tplStatus==4){
			$tplStyleFlag='考核';
		}else if($tplStatus==5){
			$tplStyleFlag='审核';
		}
		$msgI=1;
        if($tplStatus&&$tplYear&&$tplStyle&&$tplStyleFlag){
        	if($tplStyleFlag=='自评'){
        		$Sql ="SELECT a.id,a.name,b.USER_NAME AS 'userName',b.EMAIL FROM `appraisal_performance` a LEFT JOIN user b on a.user_id=b.user_id
        		where  inFlag in ($tplStatus)  AND years='$tplYear' AND `quarter`='$tplStyle' $sqlstr";
        	}elseif ($tplStyleFlag=='考核'){
        		$Sql ="SELECT a.id,a.name,b.USER_NAME AS 'userName',c.USER_NAME AS 'appName',c.EMAIL 
						FROM `appraisal_performance` a 
						LEFT JOIN user b on a.user_id=b.user_id
						LEFT JOIN user c on a.assess_userid=c.user_id
        				where  inFlag in ($tplStatus)  AND years='$tplYear' AND `quarter`='$tplStyle' $sqlstr";
        	}elseif ($tplStyleFlag=='审核'){
        		$Sql ="SELECT a.id,a.name,b.USER_NAME AS 'userName',c.USER_NAME AS 'appName',c.EMAIL 
						FROM `appraisal_performance` a 
						LEFT JOIN user b on a.user_id=b.user_id
						LEFT JOIN user c on a.audit_userid=c.user_id
        		       where  inFlag in ($tplStatus)  AND years='$tplYear' AND `quarter`='$tplStyle' $sqlstr";
        	}
        	echo  $Sql;
			$query = $this->query ( $Sql );
			$i=1;
			$tplWeek=$tplYear.'年'.($tplStyle==5?'上半年':($tplStyle==6?'下半年':$tplStyle==7?'全年':'第'.$tplStyle.'季')).'度';
			$mail_server = new includes_class_sendmail ( );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$body ='';
				$tetle='';
				$tpLName=$row['name'].$tplWeek.'考核信息';
				 if($tplStyleFlag=='自评'){
				 	$body = " 您好，" . $row[ 'userName' ] . ",<br/>您还未完成 ".$tplWeek."自评，请及时登录OA处理。<br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
				    $tetle=$tpLName.'自评提醒'; 
				 }elseif ($tplStyleFlag=='考核'){
				 	$body = " 您好!" . $row[ 'appName' ] . ",<br/>您还未完成对" . $row[ 'userName' ] ." ".$tplWeek."考核信息考核工作，请及时登录OA处理。<br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
				 	$tetle=$tpLName.'考核工作提醒';
				 }elseif ($tplStyleFlag=='审核'){
				 	$body = " 您好!" . $row[ 'appName' ] . ",<br/>您还未完成对" . $row[ 'userName' ] ." ".$tplWeek."考核信息审核工作 ，请及时登录OA处理。<br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
				 	$tetle=$tpLName.'审核工作提醒';
				 }
                 if($body&&$tetle){
                 	$i++;
                 	$mail_server -> send ($tetle , $body , $row['EMAIL'] );
                 	$msgI=2;
                 }
				 	
				
			}
        }	
		return $msgI;

	
	}
	
	
}