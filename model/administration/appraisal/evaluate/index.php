<?php
class model_administration_appraisal_evaluate_index extends model_base
{
	public $last_quarter; //上季度
	public $this_quarter; //本季度
	public $last_quarter_year;//上季度年份
	function __construct()
	{
		parent::__construct ();
		$this->tbl_name = 'appraisal_evaluate';
		
		$config = new model_administration_appraisal_performance_config();
		$year = $config->getYearInDB();
		$season = $config->getSeasonInDB();
		$this->this_quarter = $season;
		$this->last_quarter = $season - 1;
		$this->last_quarter_year = $year;
		
		/*
		$this->last_quarter = (ceil((date('n'))/3)-1) == 0 ? 4 : ceil((date('n'))/3)-1;
		//$this->this_quarter = ceil((date('n'))/3);
		$this->this_quarter = $this->last_quarter;
		$this->last_quarter_year = (ceil((date('n'))/3)-1) == 0 ? (date('Y')-1) : date('Y');
		*/
	}
	/**
	 * 被评介人列表
	 * @param $condition
	 * @param $page
	 * @param $rows
	 */
	function GetDataList($condition = null, $page = null, $rows = null)
	{
		if ($page && $rows && ! $this->num)
		{
			$rs = $this->get_one ( "
									select 
										count(distinct(a.id)) as num 
									from 
										$this->tbl_name as a 
										left join user as b on b.user_id=a.user_id
										left join user as c on c.user_id=a.create_userid
										left join project_info as d on (
																		find_in_set(a.user_id,d.manager) 
																		or find_in_set(a.user_id,d.producmanager)
																		or find_in_set(a.user_id,d.teamleader) 
																		or find_in_set(a.user_id,d.developer) 
																		or find_in_set(a.user_id,d.testleader) 
																		or find_in_set(a.user_id,d.tester)
																		or find_in_set(a.user_id,d.qc)
																		or find_in_set(a.user_id,d.productassistant)
																		)
										" . ($condition ? " where " . $condition : '') . "
										" );
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
									a.*,b.user_name,b.dept_id,b.jobs_id,c.user_name as create_name,d.name as project_name
								from
									$this->tbl_name as a
									left join user as b on b.user_id=a.user_id
									left join user as c on c.user_id=a.create_userid
									left join project_info as d on (
																		find_in_set(a.user_id,d.manager) 
																		or find_in_set(a.user_id,d.producmanager)
																		or find_in_set(a.user_id,d.teamleader) 
																		or find_in_set(a.user_id,d.developer) 
																		or find_in_set(a.user_id,d.testleader) 
																		or find_in_set(a.user_id,d.tester)
																		or find_in_set(a.user_id,d.qc)
																		or find_in_set(a.user_id,d.productassistant)
																		)
								" . ($condition ? " where " . $condition : '') . "
									group by a.id
									order by a.id desc
									" . ($limit ? "limit " . $limit : '') . "
								" );
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data [] = $rs;
		}
		return $data;
	}
	/**
	 * 评价列表
	 * @param $condition
	 * @param $page
	 * @param $rows
	 */
	function GetEvaluateList($condition = null, $page = null, $rows = null)
	{
		if ($page && $rows && ! $this->num)
		{
			$rs = $this->get_one ( "
									select 
										count(0) as num 
									from 
										appraisal_evaluate_list as a 
										left join user as b on b.user_id=a.user_id
										left join user as c on c.user_id=a.evaluators_userid
										left join appraisal_performance_template as d on d.id=a.tpl_id
										left join appraisal_performance as e on e.tpl_id=a.tpl_id and e.user_id=a.user_id
										left join department as f on f.dept_id=b.dept_id
										" . ($condition ? " where " . $condition : '') . "
								" );
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
									a.*,b.user_name,b.dept_id,b.jobs_id,c.user_name as evaluators_name,d.name as tpl_name,e.assess_status,e.id as performance_id,f.dept_name
								from
									appraisal_evaluate_list as a
									left join user as b on b.user_id=a.user_id
									left join user as c on c.user_id=a.evaluators_userid
									left join appraisal_performance_template as d on d.id=a.tpl_id
									left join appraisal_performance as e on e.tpl_id=a.tpl_id and e.user_id=a.user_id
									left join department as f on f.dept_id=b.dept_id
									" . ($condition ? " where " . $condition : '') . "
									order by a.id desc
									" . ($limit ? "limit " . $limit : '') . "
								" );		
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data [] = $rs;
		}
		return $data;
	}
	/*
	 * 统计评价列表
	 */
	function GetEvaluateCountList($condition = null,$years=null,$quarter=null, $page = null, $rows = null)
	{
		$years = $years ? $years : $this->last_quarter_year;
		$quarter = $quarter ? $quarter : $this->last_quarter;
		if ($page && $rows && ! $this->num)
		{
			$rs = $this->get_one("
									select
										count(0) as num
									from
										$this->tbl_name as a
										left join user as b on b.user_id=a.user_id
										left join (select count(distinct(evaluators_userid)) as num ,user_id,years,quarter from appraisal_evaluate_list where years=$years and quarter=$quarter group by user_id) as c on c.user_id=a.user_id
										left join department as d on d.dept_id=b.dept_id
										" . ($condition ? " where " . $condition : '') . "
			");
			$this->num = $rs['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page - 1) * $pagenum) : $this->start;
			$limit = $page && $rows ? $start . ',' . $pagenum : '';
		}
		
		$query = $this->query("select
										a.*,b.user_name,c.years,c.quarter,if(c.num is not null,c.num,0) as num,d.dept_name
									from
										$this->tbl_name as a
										left join user as b on b.user_id=a.user_id
										left join (select count(distinct(evaluators_userid)) as num ,user_id,years,quarter from appraisal_evaluate_list where years=$years and quarter=$quarter group by user_id) as c on c.user_id=a.user_id
										left join department as d on d.dept_id=b.dept_id
										" . ($condition ? " where " . $condition : '') . "
										order by a.id desc
										" . ($limit ? "limit " . $limit : '') . "
								");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * 读取评价信息记录
	 * @param $tid
	 */
	function GetEvaluateInfo($tid)
	{
		$data = array();
		$query = $this->query("select * from appraisal_evaluate_content where tid=$tid");
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	/**
	 * 保存添加或修改评介人
	 * @param unknown_type $data
	 */
	function model_save_evaluators($data)
	{
		if ($data ['evaluators'])
		{
			$evaluators = explode ( ',', mb_iconv ( $data ['evaluators'] ) );
			$in = array ();
			$user_id_arr = array ();
			foreach ( $evaluators as $val )
			{
				if (trim($val))
				{
					$in [] = "'" . trim($val) . "'";
				}
			}
			if ($in)
			{
				$query = $this->query ( "select user_id from user where user_name in(" . implode ( ',', $in ) . ")" );
				while ( ($rs = $this->fetch_array ( $query )) != false )
				{
					$user_id_arr [] = $rs ['user_id'];
				}
			}
			if ($data ['id'])
			{
				return $this->Edit ( array (
												'user_id' => $data ['user_id'], 
												'evaluators' => trim ( mb_iconv ( $data ['evaluators'] ), ',' ), 
												'evaluators_userid' => implode ( ',', $user_id_arr ), 
												'create_userid' => $_SESSION ['USER_ID'], 
												'date' => date ( 'Y-m-d H:i:s' ) 
				), $data ['id'] );
			} else
			{
				return $this->Add ( array (
											'user_id' => $data ['user_id'], 
											'evaluators' => trim ( mb_iconv ( $data ['evaluators'] ), ',' ), 
											'evaluators_userid' => implode ( ',', $user_id_arr ), 
											'create_userid' => $_SESSION ['USER_ID'], 
											'date' => date ( 'Y-m-d H:i:s' ) 
				) );
			}
		} else
		{
			return false;
		}
	}
	/**
	 * 评价人需要评介的对象用户
	 * @param $userid
	 */
	function model_my_evaluators($userid)
	{
		$query = $this->query ( "
								select 
									a.*,b.user_name
								from 
									$this->tbl_name as a 
									left join user as b on b.user_id=a.user_id
								where
									find_in_set('$userid',evaluators_userid)
									" );
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false )
		{
			$data [] = $rs;
		}
		return $data;
	}
	
	/**
	 * 保存评价人
	 * @param $data
	 * @param $user_id
	 * @param $tpl_id
	 */
	function model_save_evaluate($data, $user_id, $tpl_id,$id=null)
	{
		$this->tbl_name = 'appraisal_evaluate_list';
		if ($tpl_id)
		{
			$tpl_obj = new model_administration_appraisal_performance_item ();
			$tpl = $tpl_obj->GetOneInfo ( array (
													'id' => $tpl_id 
			) );
		}
		if ($id)
		{
			$info = $this->GetOneInfo(array('id'=>$id));
			if ($info)
			{
				$this->Edit(array(
									'count_fraction' => $data ['count_evaluate_fraction'],
									'date' => date ( 'Y-m-d H:i:s' )
				),$id);
				
				if ($data ['evaluate_fraction'])
				{
					foreach ( $data ['evaluate_fraction'] as $key => $val )
					{
						$this->query("update appraisal_evaluate_content set fraction='$val',remark='".$data ['evaluate_remark'] [$key]."' where id=$key");
					}
				}
				return true;
			}
		} else
		{
			$list_id = $this->Add ( array (
											'tpl_id' => $tpl_id, 
											'user_id' => $user_id, 
											'evaluators_userid' => $_SESSION ['USER_ID'], 
											'years' => $tpl ['years'], 
											'quarter' => $tpl ['quarter'], 
											'count_fraction' => $data ['count_evaluate_fraction'], 
											'date' => date ( 'Y-m-d H:i:s' ) 
			) );
			
			if ($data ['evaluate_fraction'])
			{
				foreach ( $data ['evaluate_fraction'] as $key => $val )
				{
					$this->query ( "insert into appraisal_evaluate_content(tid,fraction,remark)value($list_id,'$val','" . $data ['evaluate_remark'] [$key] . "')" );
				}
			}
			
			return true;
		}
	}
	/**
	 * 单用户被评价记录
	 * @param $years
	 * @param $quarter
	 * @param $user_id
	 * @param $assess_userid
	 */
	function model_user_evaluate_list($years,$quarter,$user_id)
	{
		$query = $this->query("
								select
									a.*,b.user_name
								from
									appraisal_evaluate_list as a
									left join user as b on b.user_id=a.evaluators_userid
								where
									a.years = $years
									and a.quarter = $quarter
									and a.user_id = '$user_id'
		");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		return $data;
	}
	
	/**
	 * 获取用户ID
	 * @param $username
	 */
	function GetUserID($username)
	{
		$rs = $this->get_one("select user_id from user where user_name='$username'");
		return $rs['user_id'];
	}
	/**
	 * 项目列表
	 */
	function GetProject($dept_id=null)
	{
		$condition = '';
		if ($dept_id)
		{
			if (is_array($dept_id))
			{
				$condition = implode(',',$dept_id);
			}else{
				$condition = $dept_id;
			}
		}
		$query = $this->query("select * from project_info ".($condition ? " where dept_id in ($condition)" : ''));
		$data = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			$data[] = $rs;
		}
		
		return $data;
	}
	/**
	 * 获取未评价人
	 * @param $condition
	 */
	function GetNotEevaluate_list($condition=null,$years=null,$quarter=null)
	{
		$data = array();
		$sql = "select
					a.evaluators_userid
				from
					$this->tbl_name as a
					left join user as b on b.user_id=a.user_id
				".($condition ? " where ".$condition : '');
		$query = $this->query($sql);
		$evaluators_userid_arr = array();
		while (($rs = $this->fetch_array($query))!=false)
		{
			if ($rs['evaluators_userid'])
			{
				$evaluators_userid_arr = array_merge($evaluators_userid_arr,explode(',',$rs['evaluators_userid']));
			}
		}
		if ($evaluators_userid_arr)
		{
			$join = array();
			foreach ($evaluators_userid_arr as $val)
			{
				$join[] = "'$val'";
			}
			$sql = "select
						distinct(a.user_id),a.user_name,a.email,c.dept_name
					from
						user as a
						left join appraisal_evaluate_list as b on b.evaluators_userid=a.user_id and b.years = $years and b.quarter = $quarter
						left join department as c on c.dept_id=a.dept_id
					where
						a.user_id in(".implode(',',$join).") 
						and b.id is null
					";
			$query = $this->query($sql);
			while (($rs = $this->fetch_array($query))!=false)
			{
				$data[] = $rs;
			}
		}
		return $data;
	}
	/**
	 * 添加任务
	 */
	function AddSendEmailNoticeTask()
	{
		
	}
	function model_administration_appraisal_evaluate_index_evalListData(){
		
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
			$sqlstr.=" and (a.evalName like '%$wordkey%' or b.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and b.deptId in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and b.years='$tplYear'";
		}
		
		if($tplStyle){
			$sqlstr.=" and b.quarter='$tplStyle'";
		}
		$sqlc = "SELECT count(0) as num
				FROM appraisal_evaluate_list a 
				LEFT JOIN appraisal_performance b ON a.kId=b.id 
				WHERE 1 and a.evaluators_userid='".$_SESSION['USER_ID']."' $sqlstr ";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " order by a.flag,a.id desc ";
		}
		 $sql=" SELECT b.*,a.evaluators_userid,a.evalName,a.flag,a.count_fraction,evalDate
				FROM appraisal_evaluate_list a 
				LEFT JOIN appraisal_performance b ON a.kId=b.id 
				WHERE 1 AND a.kId IS NOT NULL AND( b.inFlag>2 or (b.inFlag=2 AND b.isAss<>1  )) and a.evaluators_userid='".$_SESSION['USER_ID']."' $sqlstr $order limit $start,$pageSize";				
		$query = $this->query ( $sql );
		$data=array();
		$appList= new model_administration_appraisal_performance_list();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['evalDate']=$row['evalDate']?date('Y-m-d',strtotime($row['evalDate'])):'';
			$row['assess_date']=$row['assess_date']?date('Y-m-d',strtotime($row['assess_date'])):'';
			$row['audit_date']=$row['audit_date']?date('Y-m-d',strtotime($row['audit_date'])):'';
			$row['quarter']=$appList->model_administration_appraisal_transformCycle($row['quarter']);
			if($row['inFlag']==6&&$_SESSION['USER_ID']!=$row['audit_userid']){
				$row['countFraction']=$row['countFraction']-($row['count_audit_fraction']*$row['asAudit']/100);
				$row['count_audit_fraction']='';
				
			}
			array_push($data,un_iconv($row));
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );
	}
	function model_administration_appraisal_evaluate_index_evalManagerData(){
		
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
			$sqlstr.=" and (a.evalName like '%$wordkey%' or b.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and b.deptId in ($deptId)";
		}
		if($tplYear){
			$sqlstr.=" and b.years='$tplYear'";
		}
		
		if($tplStyle){
			$sqlstr.=" and b.quarter='$tplStyle'";
		}
		$sqlc = "SELECT count(0) as num
				FROM appraisal_evaluate_list a 
				LEFT JOIN appraisal_performance b ON a.kId=b.id 
				WHERE 1 AND a.kId IS NOT NULL $sqlstr ";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " order by a.flag,a.id desc ";
		}
		 $sql=" SELECT b.*,a.evaluators_userid,a.evalName,a.flag,a.count_fraction,evalDate
				FROM appraisal_evaluate_list a 
				LEFT JOIN appraisal_performance b ON a.kId=b.id 
				WHERE 1 AND a.kId IS NOT NULL  $sqlstr $order limit $start,$pageSize";				
		$query = $this->query ( $sql );
		$data=array();
		$appList= new model_administration_appraisal_performance_list();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['evalDate']=$row['evalDate']?date('Y-m-d',strtotime($row['evalDate'])):'';
			$row['assess_date']=$row['assess_date']?date('Y-m-d',strtotime($row['assess_date'])):'';
			$row['audit_date']=$row['audit_date']?date('Y-m-d',strtotime($row['audit_date'])):'';
			$row['quarter']=$appList->model_administration_appraisal_transformCycle($row['quarter']);
			if($row['inFlag']==6&&$_SESSION['USER_ID']!=$row['audit_userid']){
				$row['countFraction']=$row['countFraction']-($row['count_audit_fraction']*$row['asAudit']/100);
				$row['count_audit_fraction']='';
				
			}
			array_push($data,un_iconv($row));
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );
	}
function model_administration_appraisal_evaluate_index_exportExcel() {
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
			$sqlstr.=" and (a.evalName like '%$wordkey%' or b.userName like '%$wordkey%' )";
		}
		if($deptId){
			$sqlstr.=" and b.deptId='$deptId'";
		}
		if($tplYear){
			$sqlstr.=" and b.years='$tplYear'";
		}
		
		if($tplStyle){
			$sqlstr.=" and b.quarter='$tplStyle'";
		}
	    $start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " order by a.flag,a.id desc ";
		}
		 $sql=" SELECT b.*,a.evaluators_userid,a.evalName,a.flag,a.count_fraction,evalDate
				FROM appraisal_evaluate_list a 
				LEFT JOIN appraisal_performance b ON a.kId=b.id 
				WHERE 1 AND a.kId IS NOT NULL  $sqlstr $order";				
		$query = $this->query ( $sql );
		$data=array();
		$title = '评价人数据';
		$Title = array (array ($title));
		$data[] = array ("被评价人","部门","职位","模板名称","年份","考核周期","自评总分","评价人","评价分数",
									  "评价时间","评价总分");
		$appList= new model_administration_appraisal_performance_list();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
			$row['evalDate']=$row['evalDate']?date('Y-m-d',strtotime($row['evalDate'])):'';
			$row['assess_date']=$row['assess_date']?date('Y-m-d',strtotime($row['assess_date'])):'';
			$row['audit_date']=$row['audit_date']?date('Y-m-d',strtotime($row['audit_date'])):'';
			$row['quarter']=$appList->model_administration_appraisal_transformCycle($row['quarter']);
			if(($row['inFlag']<6.5)||($_SESSION['USER_ID']!=$row['audit_userid'])){
				$row['countFraction']='';//$row['countFraction']-($row['count_audit_fraction']*$row['asAudit']/100);
				$row['count_audit_fraction']='';
			}
			$data[]=array($row['userName'],$row['deptName'],$row['jobName'],$row['name'],$row['years'],$row['quarter'],$row['count_my_fraction'],$row['evalName'],$row['count_fraction'],$row['evalDate'],$row['pevFraction']);
		}
		$total = ( count ( $data ) - 1 );
		$xls = new includes_class_excel ( $title . '.xls' );
		$xls -> SetTitle ( array ( $title) , $Title );
		$xls -> SetContent (array ($data));
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A1:K1' );
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
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A2:K2' ) -> getFont ( ) -> setBold ( true );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:K500' ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:K500') -> applyFromArray ( $styleArray );
		$xls -> objActSheet[ 0 ] -> setCellValue ( 'A' . ( $total + 5 ) , un_iconv ( '合计：' . $total ) );
		$columnData=array('A','B','C','D','E','F','G','H','I','J','K','M','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');	
		foreach($columnData as $key =>$val){
		  $xls -> objActSheet[ 0 ] -> getColumnDimension ( $val ) -> setWidth ( 15 );
		}
		$xls -> OutPut ( );
	}
}
?>