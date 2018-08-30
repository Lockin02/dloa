<?php
class model_administration_appraisal_performance_item extends model_base
{
	public  $last_quarter; //上季度
	public  $this_quarter; //本季度
	public $last_quarter_year;//上季度年份
	function __construct()
	{
		parent::__construct();
		$this->tbl_name = 'appraisal_performance_template';
		$this->pk='id';
		$config = new model_administration_appraisal_performance_config();
		$year = $config->getYearInDB();
		$season = $config->getSeasonInDB();
		$this->this_quarter = $season;
		$this->last_quarter = $season - 1;
		$this->last_quarter_year = $year;
		$this -> filebase = "attachment/appraisal/appTpl/";//上传附件
		/*
		$this->last_quarter = (ceil((date('n'))/3)-1) == 0 ? 4 : ceil((date('n'))/3)-1;
		$this->this_quarter = ceil((date('n'))/3);
		$this->last_quarter_year = (ceil((date('n'))/3)-1) == 0 ? (date('Y')-1) : date('Y');
		*/
	}
	//自动更新模板列表
	final function UpdateTemplateList($years,$quarter,$to_years,$to_quarter,$dept_id=null)
	{
		/*
		return $this->query("insert into appraisal_performance_template(copy_id,name,dept_id,years,quarter,assess_userid,audit_userid,content,dept_id_str,area_id_str,jobs_id_str,user_id_str,date)
						select 
							a.id,a.name,a.dept_id,$to_years,$to_quarter,a.assess_userid,a.audit_userid,a.content,a.dept_id_str,a.area_id_str,a.jobs_id_str,a.user_id_str,now()
						from
							appraisal_performance_template as a
							left join appraisal_performance_template as b on b. years=$to_years and b.quarter=$to_quarter and b.copy_id=a.id
						where
							a.years=$years
							and a.quarter=$quarter
							".($dept_id ? "and a.dept_id in($dept_id)" : '')."
							and b.name is null
							and a.name is not null
						");
		*/
		$SQL = "insert into appraisal_performance_template(copy_id,name,dept_id,years,quarter,assess_userid,audit_userid,content,dept_id_str,area_id_str,jobs_id_str,user_id_str,date)
						select 
							a.id,a.name, '108',$to_years,$to_quarter,a.assess_userid,a.audit_userid,a.content,a.dept_id_str,a.area_id_str,a.jobs_id_str,a.user_id_str,now()
						from
							appraisal_performance_template as a
							left join appraisal_performance_template as b on b. years=$to_years and b.quarter=$to_quarter and b.copy_id=a.id
						where
							a.years=$years
							and a.quarter=$quarter
							".($dept_id ? "and a.dept_id in($dept_id)" : '')."
							and b.name is null
							and a.name is not null
						";
		
		
		return $this->query($SQL);
	}
	/**
	 * 读取单条记录
	 * @param $condition
	 */
	function GetOneData($condition=null)
	{
		return  $this->get_one("
								select
									a.*, c.user_name as assess_user_name,d.user_name as audit_user_name
								from
									$this->tbl_name as a
									left join user as c on c.user_id=a.assess_userid
									left join user as d on d.user_id=a.audit_userid
								".($condition ? " where ".$condition : '')."
		");
	}
	/**
	 * 读取数据列表
	 * @param unknown_type $condition
	 */
	function GetDataList($condition = null, $page=null,$rows=null)
	{
		if ($page && $rows && !$this->num)
		{
			$rs = $this->get_one("select 
											count(0) as num 
										from 
											$this->tbl_name as a 
											left join user as b on b.user_id=a.assess_userid
											left join user as c on c.user_id=a.audit_userid
											left join department as d on d.dept_id=a.dept_id
										".($condition ? " where ".$condition : '')."	
										");
			$this->num = $rs['num'];
		}
		if ($page && $rows && $this->num > 0)
		{
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page-1)*$pagenum) : $this->start;
			$limit = $page &&  $rows ? $start . ',' . $pagenum : '';
		}
		
		$query = $this->query("select
									a.*,b.user_name as assess_username,c.user_name as audit_username,d.dept_name
								from 
									$this->tbl_name as a
									left join user as b on b.user_id=a.assess_userid
									left join user as c on c.user_id=a.audit_userid
									left join department as d on d.dept_id=a.dept_id
									".($condition ? " where ".$condition : '')."
									order by a.id desc
									".($limit ? "limit ".$limit : '')."
								");
		$data = array();
		while (($rs = $this->fetch_array($query))!=false) {
			$fanwei = array();
			if ($rs['dept_id_str'])
			{
				$fanwei[] =  '指定部门';
			}
			if ($rs['area_id_str'])
			{
				$fanwei[] = '指定区域';
			}
			if ($rs['jobs_id_str'])
			{
				$fanwei[] = '指定职位';
			}
			if ($rs['user_id_str'])
			{
				$fanwei[] = '指定用户';
			}
			
			$rs['fanwei'] = $fanwei ? implode('/',$fanwei) : '所有人';
			$data[] = $rs;
		}
		return $data;
	}


	/**
	 * 获取个人季度模板
	 * @param $userid
	 */
	function GetTemplate($userid,$years=null,$quarter=null)
	{
		$rs = $this->get_one("select * from user where user_id='$userid'");
		if ($rs)
		{
			$query = $this->query("
									select
										a.id,a.name
									from
										$this->tbl_name as a
									where
										a.years = ".($years ? $years : $this->last_quarter_year)."
										and a.quarter = ".($quarter ? $quarter : $this->last_quarter)."
										and ( 
												find_in_set('" . $rs['USER_ID'] . "',a.user_id_str)
	                                                or ( 
		                                                	(find_in_set('" . $rs ['DEPT_ID'] . "',a.dept_id_str) or a.dept_id_str is null or a.dept_id_str='')
		                                                	and 
		                                                	(find_in_set('" . $rs ['AREA'] . "',a.area_id_str) or a.area_id_str is null or a.area_id_str='')
		                                                	and 
		                                                	(find_in_set('" . $rs ['jobs_id'] . "',a.jobs_id_str) or a.jobs_id_str is null or a.jobs_id_str='')
		                                                	and 
		                                                	(find_in_set('" . $rs ['USER_ID'] . "',a.user_id_str) or a.user_id_str is null or a.user_id_str='')
	                                                	
	                                                	)
		                                       )
										
			");
			$data = array();
			while (($row = $this->fetch_array($query))!=false)
			{
				$data[] = $row;
			}
			return $data;
		}else{
			return false;
		}
	}


	
	function model_administration_appraisal_performance_item_listData() {
		global $func_limit;
		$wordkey = mb_iconv($this->ReQuest("wordkey"));
		$pageIndex = $this->ReQuest("pageIndex");
		$pageSize = $this->ReQuest("pageSize");
		$sortField = $this->ReQuest("sortField");
		$sortOrder = $this->ReQuest("sortOrder");
		$deptId = $this->ReQuest("deptId");
		$tplYear = $this->ReQuest("tplYear");
		$tplStyle = $this->ReQuest("tplStyle");
		$deptId=$deptId?$deptId:($func_limit['管理部门'] ? $func_limit['管理部门'] : $_SESSION['DEPT_ID']);
		$deptId=str_replace(';;','',$deptId);
		$deptId=trim($deptId,',');
		$sqlstr='';
		if($wordkey){
			$sqlstr.=" and (a.name like '%$wordkey%' or a.deptName  like '%$wordkey%' or b.userStrName like '%$wordkey%' or b.assessName like '%$wordkey%' or b.auditName like '%$wordkey%' or
		        a.remark like '%$wordkey%')";
		}
		if($deptId){
			$sqlstr.=" and a.dept_id in ($deptId)";
		}

		if($tplYear){
			$sqlstr.=" and a.years='$tplYear'";
		}
		if($tplStyle){
			$sqlstr.=" and a.quarter='$tplStyle'";
		}
		$sqlc = "select COUNT( DISTINCT a.id) as num 
				 FROM 
				 $this->tbl_name as a 
				 LEFT JOIN  appraisal_template_userlist  as b  ON  b.tId=a.id
			     WHERE  1  $sqlstr";							
		$rec = $this->get_one ( $sqlc );
		$total=$rec["num"];
		$start = $pageIndex * $pageSize;
		if(!empty($sortField)){
			if ($sortOrder != "desc") $sortOrder = "asc";
			$order = " order by " . $sortField . " " . $sortOrder;
		}else{
			$order = " order by a.years desc, a.quarter desc,a.id desc ";
		}
		$sql="select a.*
			  FROM 
			  $this->tbl_name as a
			  LEFT JOIN  appraisal_template_userlist  as b  ON  b.tId=a.id
			  WHERE  1  $sqlstr   GROUP BY a.id  $order limit $start,$pageSize";				
		$query = $this->query ( $sql );
		$data=array();
		$list =new model_administration_appraisal_performance_list();
		while ( ($row = $this->fetch_array ( $query )) != false )
		{
		    $row['date']=date('Y-m-d',strtotime($row['date']));
			$row['quarter']=$list->model_administration_appraisal_transformCycle($row['quarter']);
			array_push($data,un_iconv($row));
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );
	}
	function model_administration_appraisal_performance_item_tplDeptData() {
		global $func_limit;
		$deptIds = $func_limit['管理部门'] ? $func_limit['管理部门']: $_SESSION['DEPT_ID'];
		$deptIds=str_replace(';;','',$deptIds);
		$deptIds=trim($deptIds,',');
		if($deptIds){
			$str=" and dept_id IN ($deptIds) ";
		}	
	    $sql="SELECT dept_id,parent_id,dept_name FROM `department` WHERE  1=1 $str ";
	    			
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
	
	function model_administration_appraisal_performance_item_userData() {
		$key=mb_iconv($_POST['key']);
		if($key){
		    $sql="SELECT a.user_id,a.user_name,b.dept_name FROM  user a left join `department` b  on (a.dept_id=b.dept_id) WHERE   1 and user_name like '%$key%'";				
		    $query = $this->query ( $sql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$data[]=un_iconv(array(
					'id'=>$row['user_id'],
					'text'=>$row['user_name'],
					'deptName'=>$row['dept_name']
				));
			}
		}
		return json_encode ( $data );

	}

function model_administration_appraisal_performance_item_addTpl(){
		$data=  ( $_POST );
	    $infoData =json_decode(stripslashes($data['infoData']),true);
	    $userData =json_decode(stripslashes($data['userData']),true);
		$projectData =json_decode(stripslashes($data['projectData']),true);
		$columnsData =json_decode(stripslashes($data['columnsData']),true);
		$infoData=mb_iconv($infoData);
		$userData=mb_iconv($userData);
		$projectData=mb_iconv($projectData);
		$columnsData=mb_iconv($columnsData);
		if($infoData['tplName']&&$infoData['deptId']){
		    $createInfoData['name']=($infoData['tplName']);
			$createInfoData['dept_id']=$infoData['deptId'];
			$createInfoData['deptName']=$infoData['deptIdName'];
			$createInfoData['years']=$infoData['tplYear'];
			$createInfoData['quarter']=$infoData['tplStyle'];
			$createInfoData['userType']=$infoData['userType'];
			$createInfoData['user_id_str']=$infoData['userStr'];
		    $createInfoData['tplStyleFlag']=$infoData['tplStyleFlag'];
			$createInfoData['remark']=$infoData['remark'];
			$createInfoData['assess_userid']=$infoData['assess'];
			$createInfoData['audit_userid']=$infoData['audit'];
			$createInfoData['date']=date('Y-m-d H:i:s');
			$createInfoData['assessName']=$infoData['assessName'];
			$createInfoData['auditName']=$infoData['auditName'];
			$createInfoData['userStrName']=$infoData['userStrName'];
			if($infoData['isAss']==2){
				$infoData['asPers']='';
			}
			$createInfoData['asPers']=$infoData['asPers'];
			$createInfoData['asAss']=$infoData['asAss'];
			$createInfoData['asAudit']=$infoData['asAudit'];
			$createInfoData['isAss']=$infoData['isAss'];
		}				
		try{
			$this->_db->query ( "START TRANSACTION" );
			$this -> tbl_name = 'appraisal_performance_template';
			if($createInfoData&&is_array($createInfoData))
			{
				$this -> create ($createInfoData);
				$pid = $this -> _db -> insert_id ( );
				if($pid&&$userData&&is_array($userData)){
					$this -> tbl_name = 'appraisal_template_userlist';
					foreach($userData as $userDataKey=>$_userData)
					{
						if($_userData&&is_array($_userData)&&$_userData['userStr']){
						  $createUserData[$userDataKey]['tId']=$pid;
						  $createUserData[$userDataKey]['assess']=$_userData['assess'];
						  $createUserData[$userDataKey]['assessName']=$_userData['assessName'];
						  $createUserData[$userDataKey]['auditName']=$_userData['auditName'];
						  $createUserData[$userDataKey]['audit']=$_userData['audit'];
						  $createUserData[$userDataKey]['userStr']=$_userData['userStr'];
						  $createUserData[$userDataKey]['userStrName']=$_userData['userStrName'];
						  $createUserData[$userDataKey]['userType']=$_userData['userType'];
						  $createUserData[$userDataKey]['createDate']=date('Y-m-d H:i:s');
						}
					}
					if($createUserData&&is_array($createUserData)){
						foreach($createUserData as $key =>$val){
							if($val&&is_array($val)){
								$this -> create ($val);
							}
						}
					}
				}
				if($pid&&$projectData&&is_array($projectData)){
					$this -> tbl_name = 'appraisal_template_projectname';
					foreach($projectData as $projectDataKey=>$_projectData)
					{
						if($_projectData&&is_array($_projectData)&&$_projectData['projectName']!=''){
						  $createProjectData[$projectDataKey]['tId']=$pid;
						  $createProjectData[$projectDataKey]['projectName']=$_projectData['projectName'];
						  $createProjectData[$projectDataKey]['createDate']=date('Y-m-d H:i:s');
						}
					}
					if($createProjectData&&is_array($createProjectData)){
						foreach($createProjectData as $key =>$val){
							if($val&&is_array($val)){
								$this -> create ($val);
							}
						}
					}
				}
				if($pid&&$columnsData&&is_array($columnsData)){
					$this -> tbl_name = 'appraisal_template_columnname';
					foreach($columnsData as $columnsDataKey=>$_columnsData)
					{
						if($_columnsData&&is_array($_columnsData)&&$_columnsData['columnsName']!=''){
						  $createColumnsData[$columnsDataKey]['tId']=$pid;
						  $createColumnsData[$columnsDataKey]['columnsName']=$_columnsData['columnsName'];
						  $createColumnsData[$columnsDataKey]['createDate']=date('Y-m-d H:i:s');
						}
					}
					if($createColumnsData&&is_array($createColumnsData)){
						foreach($createColumnsData as $key =>$val){
							if($val&&is_array($val)){
								$this -> create ($val);
							}
						}
					}
				}
			$this->_db->query ( "COMMIT" );
			echo 2;
		  }
		}catch(Exception $e){
			$this->_db->query ( "ROLLBACK" );
			return false;
		}
    }
	function model_administration_appraisal_performance_item_editTpl(){
		$data=  ( $_POST );
	    $infoData =json_decode(stripslashes($data['infoData']),true);
	    $userData =json_decode(stripslashes($data['userData']),true);
		$projectData =json_decode(stripslashes($data['projectData']),true);
		$columnsData =json_decode(stripslashes($data['columnsData']),true);
		$infoData=mb_iconv($infoData);
		$userData=mb_iconv($userData);
		$projectData=mb_iconv($projectData);
		$columnsData=mb_iconv($columnsData);
		if($infoData['tplName']&&$infoData['deptId']){
		    $createInfoData['name']=($infoData['tplName']);
			$createInfoData['dept_id']=$infoData['deptId'];
			$createInfoData['deptName']=$infoData['deptIdName'];
			$createInfoData['years']=$infoData['tplYear'];
			$createInfoData['quarter']=$infoData['tplStyle'];
			$createInfoData['userType']=$infoData['userType'];
			$createInfoData['user_id_str']=$infoData['userStr'];
		    $createInfoData['tplStyleFlag']=$infoData['tplStyleFlag'];
			$createInfoData['remark']=$infoData['remark'];
			$createInfoData['assess_userid']=$infoData['assess'];
			$createInfoData['audit_userid']=$infoData['audit'];
			$createInfoData['date']=date('Y-m-d H:i:s');
			$createInfoData['assessName']=$infoData['assessName'];
			$createInfoData['auditName']=$infoData['auditName'];
			$createInfoData['userStrName']=$infoData['userStrName'];
			if($infoData['isAss']==2){
				$infoData['asPers']='';
			}
			$createInfoData['asPers']=$infoData['asPers'];
			$createInfoData['asAss']=$infoData['asAss'];
			$createInfoData['asAudit']=$infoData['asAudit'];
			$createInfoData['isAss']=$infoData['isAss'];
		}
		if($infoData['keyId']&&$userData&&is_array($userData)){
				foreach($userData as $userDataKey=>$_userData)
				{
					if($_userData&&is_array($_userData)&&$_userData['userStr']){
						if($_userData['_state']=='added'){
						  $createUserData[$userDataKey]['tId']=$infoData['keyId'];
						  $createUserData[$userDataKey]['assess']=$_userData['assess'];
						  $createUserData[$userDataKey]['assessName']=$_userData['assessName'];
						  $createUserData[$userDataKey]['auditName']=$_userData['auditName'];
						  $createUserData[$userDataKey]['audit']=$_userData['audit'];
						  $createUserData[$userDataKey]['userStr']=$_userData['userStr'];
						  $createUserData[$userDataKey]['userStrName']=$_userData['userStrName'];
						  $createUserData[$userDataKey]['userType']=$_userData['userType'];
						  $createUserData[$userDataKey]['createDate']=date('Y-m-d H:i:s');	
						}
						 if(($_userData['_state']=='modified')&&$_userData['userStr']){
							  $updateUserData[$_userData['id']]['tId']=$infoData['keyId'];
							  $updateUserData[$_userData['id']]['assess']=$_userData['assess'];
							  $updateUserData[$_userData['id']]['assessName']=$_userData['assessName'];
							  $updateUserData[$_userData['id']]['auditName']=$_userData['auditName'];
							  $updateUserData[$_userData['id']]['audit']=$_userData['audit'];
							  $updateUserData[$_userData['id']]['userStr']=$_userData['userStr'];
							  $updateUserData[$_userData['id']]['userStrName']=$_userData['userStrName'];
							  $updateUserData[$_userData['id']]['userType']=$_userData['userType'];
							  $updateUserData[$_userData['id']]['createDate']=date('Y-m-d H:i:s');						
						  }
						 if($_userData['_state']=='removed'){
						   $delUserData[$_userData['id']]['id']=$_userData['id'];	
						}
					  }
				}
			}
		if($infoData['keyId']&&$projectData&&is_array($projectData)){
				foreach($projectData as $projectDataKey=>$_projectData)
				{
					if($_projectData&&is_array($_projectData)&&$_projectData['projectName']){
						if($_projectData['_state']=='added'){
						  $createProjectData[$projectDataKey]['tId']=$infoData['keyId'];
						  $createProjectData[$projectDataKey]['projectName']=$_projectData['projectName'];
						  $createProjectData[$projectDataKey]['createDate']=date('Y-m-d H:i:s');	
						}
						 if(($_projectData['_state']=='modified')&&$_projectData['projectName']){
						  $updateProjectData[$_projectData['id']]['tId']=$infoData['keyId'];
						  $updateProjectData[$_projectData['id']]['projectName']=$_projectData['projectName'];
						  $updateProjectData[$_projectData['id']]['createDate']=date('Y-m-d H:i:s');	
						}
						 if($_projectData['_state']=='removed'){
						  $delProjectData[$_projectData['id']]['id']=$_projectData['id'];	
						}
					  }
				}
			}	
		if($infoData['keyId']&&$columnsData&&is_array($columnsData)){
			foreach($columnsData as $columnsDataKey=>$_columnsData)
			{
				if($_columnsData&&is_array($_columnsData)&&$_columnsData['columnsName']){
					if($_columnsData['_state']=='added'){
					  $createColumnsData[$columnsDataKey]['tId']=$infoData['keyId'];
					  $createColumnsData[$columnsDataKey]['columnsName']=$_columnsData['columnsName'];
					  $createColumnsData[$columnsDataKey]['createDate']=date('Y-m-d H:i:s');	
					}
					 if(($_columnsData['_state']=='modified')&&$_columnsData['columnsName']){
					  $updateColumnsData[$_columnsData['id']]['tId']=$infoData['keyId'];
					  $updateColumnsData[$_columnsData['id']]['columnsName']=$_columnsData['columnsName'];
					  $updateColumnsData[$_columnsData['id']]['createDate']=date('Y-m-d H:i:s');	
					}
					 if($_columnsData['_state']=='removed'){
					  $delColumnsData[$_columnsData['id']]['id']=$_columnsData['id'];	
					}
				  }
			}
		}								
		try{
			$this->_db->query ( "START TRANSACTION" );
			$this -> tbl_name = 'appraisal_performance_template';
			if($createInfoData&&is_array($createInfoData)&&$infoData['keyId'])
			{
				$this -> update ( array ( 'id' =>$infoData['keyId']) , $createInfoData);
			}
			$this -> tbl_name = 'appraisal_template_userlist';
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
							$sqls = "delete from appraisal_template_userlist  where id in($_key)";
							$res = $this->query ( $sqls );
						}
					}
			}	 
			$this -> tbl_name = 'appraisal_template_projectname';
			if($createProjectData&&is_array($createProjectData)){
					foreach($createProjectData as $key =>$val){
						if($val&&is_array($val)){
							$this -> create ($val);
						}
						
					}
			}
			if($updateProjectData&&is_array($updateProjectData)){
					foreach($updateProjectData as $keys =>$vals){
						if($vals&&is_array($vals)&&$keys){
							$this -> update ( array ( 'id' =>$keys) , $vals);
						}
					}
			}
			if($delProjectData&&is_array($delProjectData)){
					foreach($delProjectData as $_key =>$_vals){
						if($_vals&&is_array($_vals)&&$_key){
							$sqls = "delete from appraisal_template_projectname  where id in($_key)";
							$res = $this->query ( $sqls );
						}
					}
			}
			$this -> tbl_name = 'appraisal_template_columnname';
			if($createColumnsData&&is_array($createColumnsData)){
					foreach($createColumnsData as $key =>$val){
						if($val&&is_array($val)){
							$this -> create ($val);
						}
						
					}
			}
			if($updateColumnsData&&is_array($updateColumnsData)){
					foreach($updateColumnsData as $keys =>$vals){
						if($vals&&is_array($vals)&&$keys){
							$this -> update ( array ( 'id' =>$keys) , $vals);
						}
					}
			}
			if($delColumnsData&&is_array($delColumnsData)){
					foreach($delColumnsData as $_key =>$_vals){
						if($_vals&&is_array($_vals)&&$_key){
							$sqls = "delete from appraisal_template_columnname  where id in($_key)";
							$res = $this->query ( $sqls );
						}
					}
			}
			
			$this->_db->query ( "COMMIT" );
			echo 2;
		}catch(Exception $e){
			$this->_db->query ( "ROLLBACK" );
			return false;
		}
    }

	
	function model_administration_appraisal_performance_item_getGridProjectNameData(){
		$keyId=$_GET['keyId'];
		if($keyId){
			$sql = " SELECT  *
					 FROM  appraisal_template_projectname
					 where tid='$keyId'
					 ORDER BY id";
			$query = $this->query ( $sql );
			$data = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$data[]=un_iconv(array(
					'id'=>$row['id'],
					'projectName'=>$row['projectName']
					));
			}
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );
	}
	
	function model_administration_appraisal_performance_item_getGridUserListData(){
		$keyId=$_GET['keyId']?$_GET['keyId']:$_POST['keyId'];
		if($keyId){
			$sql = " SELECT  *
					 FROM  appraisal_template_userlist
					 where tid='$keyId'
					 ORDER BY id";
			$query = $this->query ( $sql );
			$data = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$data[]=un_iconv(array(
					'id'=>$row['id'],
					'userStrName'=>$row['userStrName'],
					'userStr'=>$row['userStr'],
					'assess'=>$row['assess'],
					'assessName'=>$row['assessName'],
					'audit'=>$row['audit'],
					'auditName'=>$row['auditName'],
					'userType'=>$row['userType']
					));
			}
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );
	}
	function model_administration_appraisal_performance_item_getGridColumnsNameData(){
		$keyId=$_GET['keyId'];
		if($keyId){
			$sql = " SELECT  *
					 FROM  appraisal_template_columnname
					 where tid='$keyId'
					 ORDER BY id";
			$query = $this->query ( $sql );
			$data = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$data[]=un_iconv(array(
					'id'=>$row['id'],
					'columnsName'=>$row['columnsName']
					));
			}
		}
		$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $resultData );
	}
	
	function model_administration_appraisal_performance_item_getColProjectNameData(){
		$keyId=$_GET['tid'];
		if($keyId){
			$sql = " SELECT  *
					 FROM  appraisal_template_projectname
					 where tid='$keyId'
					 ORDER BY id";
			$query = $this->query ( $sql );
			$data = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$data[]=un_iconv(array(
					'id'=>$row['id'],
					'text'=>$row['projectName']
					));
			}
		}
		//$resultData = array("total"=>$total,"data"=>$data);
	    return json_encode ( $data );
	}
	function model_getProjectNameArr($id){
		if($id){
			$sql = " SELECT  *
					 FROM  appraisal_template_projectname
					 where id='$id'
					 ORDER BY id";
			$query = $this->query ( $sql );
			$data = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{

					$projectName=$row['projectName'];
			}
		}
		//$resultData = array("total"=>$total,"data"=>$data);
	    return $projectName;
	}
	function model_administration_appraisal_performance_item_addTplContent(){
		$data=  ( $_POST );
	    $contentData =json_decode(stripslashes($data['contentData']),true);
		$tid =$data['tid'];
		$contentData=mb_iconv($contentData);
		if($contentData&&is_array($contentData)&&$contentData[0]['projectId']&&$tid){
		   foreach($contentData as $contentKey =>$_contentData){
			   if(is_array($_contentData)&&$_contentData['projectId']){
					 $createContentData[$contentKey]['tId']=$tid;
					 $createContentData[$contentKey]['projectId']=$_contentData['projectId'];
					 $createContentData[$contentKey]['projectName']=$this->model_getProjectNameArr($_contentData['projectId']);
					 $createContentData[$contentKey]['kpiRight']=$_contentData['kpiRight'];
					 $createContentData[$contentKey]['projectDesc']=$_contentData['projectDesc'];
					 $createContentData[$contentKey]['kpiDescription']=$_contentData['kpiDescription'];
					 for($i=0;$i<10;$i++){
						 if($_contentData['columnName'.$i]){
							$createContentData[$contentKey]['columnName'.$i]=$_contentData['columnName'.$i];
						 }
					 }
					 $createContentData[$contentKey]['createDate']=date("Y-m-d");
				}
		   }	
		}		
		try{
			$this->_db->query ( "START TRANSACTION" );
			$this -> tbl_name = 'appraisal_template_contents';
			if($createContentData&&is_array($createContentData))
			{
				foreach($createContentData as $key =>$val){
					if($val&&is_array($val)){
						$this -> create ($val);
						$isflag = $this -> _db -> insert_id ( );
					}
			   }
			  if($isflag){
				  $this -> tbl_name = 'appraisal_performance_template';
				  $upFlagData=array( 'addTplFlag' =>2);
				  $this -> update ( array ( 'id' =>$tid) , $upFlagData); 
			  }
			$this->_db->query ( "COMMIT" );
			echo 2;
		  }
		}catch(Exception $e){
			$this->_db->query ( "ROLLBACK" );
			return false;
		}
	}
	
	function model_administration_appraisal_performance_item_editTplInContent(){
		$data= $_POST;
	    $keyId =$data['keyId'];
	    if($keyId){ 
			$contentData =json_decode(stripslashes($data['contentData']),true);
			$contentData=mb_iconv($contentData);
			if($keyId&&$contentData&&is_array($contentData)){
					foreach($contentData as $contentKey=>$_contentData)
					{
						if($_contentData&&is_array($_contentData)&&$_contentData['projectId']){
							if($_contentData['_state']=='added'){
								 $createContentData[$contentKey]['tId']=$keyId;
								 $createContentData[$contentKey]['projectId']=$_contentData['projectId'];
								 $createContentData[$contentKey]['projectName']=$this->model_getProjectNameArr($_contentData['projectId']);
								 $createContentData[$contentKey]['kpiRight']=$_contentData['kpiRight'];
								 $createContentData[$contentKey]['projectDesc']=$_contentData['projectDesc'];
								 $createContentData[$contentKey]['kpiDescription']=$_contentData['kpiDescription'];
								 for($i=0;$i<10;$i++){
									 if($_contentData['columnName'.$i]){
										$createContentData[$contentKey]['columnName'.$i]=$_contentData['columnName'.$i];
									 }
								 }
						 		$createContentData[$contentKey]['createDate']=date("Y-m-d");	
							}else if(($_contentData['_state']=='modified')&&$_contentData['projectId']){
								 $updateContentData[$_contentData['id']]['tId']=$keyId;
								 $updateContentData[$_contentData['id']]['projectId']=$_contentData['projectId'];
								 $updateContentData[$_contentData['id']]['projectName']=$this->model_getProjectNameArr($_contentData['projectId']);
								 $updateContentData[$_contentData['id']]['kpiRight']=$_contentData['kpiRight'];
								 $updateContentData[$_contentData['id']]['projectDesc']=$_contentData['projectDesc'];
								 $updateContentData[$_contentData['id']]['kpiDescription']=$_contentData['kpiDescription'];
								 for($i=0;$i<10;$i++){
									 if($_contentData['columnName'.$i]){
										$updateContentData[$_contentData['id']]['columnName'.$i]=$_contentData['columnName'.$i];
									 }
								 }
						 		$updateContentData[$_contentData['id']]['createDate']=date("Y-m-d");	
	
							}else if($_contentData['_state']=='removed'){
							  $delContentData[$_contentData['id']]['id']=$_contentData['id'];	
							}
						  }
					}
				}				
			try{
				$this->_db->query ( "START TRANSACTION" );
				$this -> tbl_name = 'appraisal_performance_template';
				$upFlagData=array( 'addTplFlag' =>2);
				if($keyId)
				{
					$this -> update ( array ( 'id' =>$keyId) , $upFlagData);
				}	 
				$this -> tbl_name = 'appraisal_template_contents';
				if($createContentData&&is_array($createContentData)){
						foreach($createContentData as $key =>$val){
							if($val&&is_array($val)){
								$this -> create ($val);
							}
						}
				}
				if($updateContentData&&is_array($updateContentData)){
						foreach($updateContentData as $keys =>$vals){
							if($vals&&is_array($vals)&&$keys){
								$this -> update ( array ( 'id' =>$keys) , $vals);
							}
						}
				}
				if($delContentData&&is_array($delContentData)){
						foreach($delContentData as $_key =>$_vals){
							if($_vals&&is_array($_vals)&&$_key){
								$sqls = "delete from appraisal_template_contents  where id in($_key)";
								$res = $this->query ( $sqls );
							}
						}
				}
				$this->_db->query ( "COMMIT" );
				echo 2;
		}catch(Exception $e){
			$this->_db->query ( "ROLLBACK" );
			return false;
		}
	  }
    }
 
	function model_administration_appraisal_performance_item_importTplExContent(){
		set_time_limit (0);
		ini_set ( 'memory_limit' , '128M' );
		$msg = 0;
		$tid=$_POST['tid'];
		$msg = $this -> model_administration_appraisal_performance_item_upfile ();
		if ( $msg == 1&&$tid)
		{
			$msg = $this -> model_administration_appraisal_performance_item_importdata ( $_FILES[ 'upfile' ][ 'name' ],$tid);
			if($msg==2){
				 $this -> tbl_name = 'appraisal_performance_template';
				 $upFlagData=array( 'addTplFlag' =>2);
				 $this -> update ( array ( 'id' =>$tid) , $upFlagData); 
			}
		}
		return $msg;
	}
	
	/*
	 * 上传文件
	 * @param  $_FILES
	 */
	function model_administration_appraisal_performance_item_upfile ()
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
																"xls" 
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
	function model_administration_appraisal_performance_item_getexcel ( $filename , $sheet = '' )
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
	function model_administration_appraisal_performance_item_importdata ( $filename,$tid )
	{
		try
		{
			$this -> _db -> query ( "START TRANSACTION" );
			$this -> tbl_name = 'consume_info';
			if ( $filename && file_exists ( $this -> filebase . $filename ) )
			{
				$Excel = mb_iconv ( $this -> model_administration_appraisal_performance_item_getexcel ( $this -> filebase . $filename , 0 ) );
				
				if ( $Excel[ 0 ]&&is_array($Excel[ 0 ]) )
				{
					if($Excel[ 0 ][1]&&is_array($Excel[ 0 ][1])){
						foreach($Excel[ 0 ][1] as $ckey =>$_cVal){
						   if($_cVal&&$ckey>3){
						      $createColumnsData[$ckey]['tId']=$tid;
					          $createColumnsData[$ckey]['columnsName']=$_cVal;
					          $createColumnsData[$ckey]['createDate']=date('Y-m-d H:i:s'); 	
						   }
						 }
						 if($createColumnsData&&is_array($createColumnsData)){
						 	$this -> tbl_name = 'appraisal_template_columnname';
							foreach($createColumnsData as $key =>$val){
								if($val&&is_array($val)){
									$this -> create ($val);
								}
							}
						 } 
					}
					foreach ( $Excel[ 0 ] as $key => $vI )
					{
						if ( $key > 1 )
						{
							if ( is_array ( $vI ) && ( $vI[ '0' ]))
							{
								$i=0;
								foreach($vI as $vkey=>$_vI){
									if($vkey>3&&$vkey<14){
										$conlumu['columnName'.$i]=$_vI;
										$i++;
									}
								}
								$this -> tbl_name = 'appraisal_template_projectname';
								$tmpProject = array ('tId' =>$tid,'projectName' => $vI[ '0' ]);
								$projectI=$this -> find ($tmpProject ); 
								$projectId=$projectI['id'];
								if (!$projectI['id'])
								{
									$tmpProject['createDate']=date('Y-m-d');
									$this -> create ( $tmpProject );
									$projectId=$this -> _db -> insert_id ( ); 
								} 
								 $tmpTplConcent=array('tId' =>$tid,
									 					   'projectId' =>$projectId, 
									                       'projectName' =>$vI[ '0' ],
									                       'projectDesc' =>$vI[ '1' ],
									                       'kpiDescription' =>$vI[ '2' ],
									                       'kpiRight' =>(float)$vI[ '3' ]
									                       );
								  $tmpTplConcent=array_merge($tmpTplConcent,$conlumu);
								  $tmpTplConcentI[]=$tmpTplConcent;                       
							}
						}
					}
					if($tmpTplConcentI&&is_array($tmpTplConcentI)){
						 	$this -> tbl_name = 'appraisal_template_contents';
							foreach($tmpTplConcentI as $key =>$val){
								if($val&&is_array($val)&&$val['projectId']){
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
	
	 /* 导出员工数据
	 * Enter description here ...
	 */
	function model_exportexcels ( )
	{
		$title = '员工消费数据';
		$Title = array ( 
						array ( 
								$title 
						) 
		);
		$this -> ExcelData[ ] = array ( 
										'工号' , 
										'姓名' , 
										'部门名称' , 
										'机号' , 
										'消费时间' , 
										'消费金额' , 
										'余额' 
		);
		$this -> model_managedata ( true );
		$data = $this -> ExcelData;
		$xls = new includes_class_excel ( $title . '.xls' );
		$xls -> SetTitle ( array ( 
									$title 
		) , $Title );
		$xls -> SetContent ( array ( 
									$data 
		) );
		$xls -> objActSheet[ 0 ] -> mergeCells ( 'A1:K1' );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A2:K2' ) -> getFont ( ) -> setBold ( true );
		$xls -> objActSheet[ 0 ] -> getStyle ( 'A1:P500' ) -> getAlignment ( ) -> setHorizontal ( PHPExcel_Style_Alignment :: HORIZONTAL_CENTER );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'A' ) -> setWidth ( 10 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'B' ) -> setWidth ( 10 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'C' ) -> setWidth ( 15 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'D' ) -> setWidth ( 40 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'E' ) -> setWidth ( 10 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'F' ) -> setWidth ( 10 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'G' ) -> setWidth ( 30 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'H' ) -> setWidth ( 10 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'I' ) -> setWidth ( 10 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'J' ) -> setWidth ( 10 );
		$xls -> objActSheet[ 0 ] -> getColumnDimension ( 'K' ) -> setWidth ( 10 );
		
		$xls -> OutPut ( );
	
	}
	
	function model_administration_appraisal_performance_item_copyTplData(){
	    $ids=trim($_POST['keys'],',');
		if($ids){
			try
			{
				$this -> _db -> query ( "START TRANSACTION" );
			    $sql="SELECT * FROM appraisal_performance_template WHERE   1 and id in ($ids) and addTplFlag=3";
			    $query = $this->query ( $sql );
				$data = array();
				while ( ($row = $this->fetch_array ( $query )) != false )
				{
					array_push($data,$row);
				}
				$this->tbl_name='appraisal_performance_template';
				if($data&&is_array($data)){
				   foreach($data as $key =>$val){
				   	if($val['id']){ 
				      $val['copy_id']=$val['id'];
				      $val['addTplFlag']=2;
				      $val['isFlag']=1;
				      $val['date']=date('Y-m-d');
				      unset($val['id']);
				      $this->create($val);
				      $newId=$this -> _db -> insert_id ( );
				      $tplData[$val['copy_id']]['id']=$newId;
				      $tplData[$val['copy_id']]['pid']=$val['copy_id'];
				      $pids=$val['copy_id'].','.$pids;
				   	}
				   }
				}
				$pids=trim($pids,',');
				if($pids){
                	$this->tbl_name='appraisal_template_columnname';
                	$sql="SELECT * FROM appraisal_template_columnname WHERE   1 and tid in ($pids)";			
				    $query = $this->query ( $sql );
				    $colData = array();
					while ( ($row = $this->fetch_array ( $query )) != false )
					{
						array_push($colData,$row);
					}
                	if($colData&&is_array($colData)){
                	   foreach($colData as $key =>$val){
                	   	   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']){ 
						   	  $val['tId']=$tplData[$val['tId']]['id']; 
						      $val['createDate']=date('Y-m-d H:i:s');
						       unset($val['id']);
						       $this->create($val);
						      }
					   }	
                		
                	}
                	$this->tbl_name='appraisal_template_projectname';
                	$sql="SELECT * FROM appraisal_template_projectname WHERE   1 and tid in ($pids)";				
				    $query = $this->query ( $sql );
				    $proData = array();
					while ( ($row = $this->fetch_array ( $query )) != false )
					{
						array_push($proData,$row);
					}
                	if($proData&&is_array($proData)){
                	   foreach($proData as $key =>$val){
						   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']){ 
						   	  $val['tId']=$tplData[$val['tId']]['id']; 
						      $val['createDate']=date('Y-m-d H:i:s');
						      $val['isFlag']=1;
						      unset($val['id']);
						      $this->create($val);
						      $proId=$this -> _db -> insert_id ( );
						      $tmpProData[$val['projectName']]['id']=$proId;
						      $tmpProData[$val['projectName']]['name']=$val['projectName'];
						     }
					   }	
                		
                	}
                	$this->tbl_name='appraisal_template_contents';
                	$sql="SELECT * FROM appraisal_template_contents WHERE   1 and tid in ($pids)";				
				    $query = $this->query ( $sql );
				    $conData = array();
					while ( ($row = $this->fetch_array ( $query )) != false )
					{
						array_push($conData,$row);
					}
                	if($conData&&is_array($conData)){
                	   foreach($conData as $key =>$val){
						   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']&&$tmpProData[$val['projectName']]['name']==$val['projectName']){ 
						   	  $val['tId']=$tplData[$val['tId']]['id'];
						   	  $val['projectId']=$tmpProData[$val['projectName']]['id']; 
						      $val['createDate']=date('Y-m-d H:i:s');
						      unset($val['id']);
						      $this->create($val);
						     }
					   }	
                		
                	}
                	$this->tbl_name='appraisal_template_userlist';
                	$sql="SELECT * FROM appraisal_template_userlist WHERE   1 and tid in ($pids)";				
				    $query = $this->query ( $sql );
				    $userData= array();
					while ( ($row = $this->fetch_array ( $query )) != false )
					{
						array_push($userData,$row);
					}
                	if($userData&&is_array($userData)){
                	   foreach($userData as $key =>$val){
						   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']){ 
						   	  $val['tId']=$tplData[$val['tId']]['id']; 
						      $val['createDate']=date('Y-m-d H:i:s');
						      unset($val['id']);
						      $this->create($val);
						     }
					   }	
                		
                	}
                }
			$this -> _db -> query ( "COMMIT" );
			return 2;
		} catch ( Exception $e )
		{
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		}
		}		
	}
	function model_administration_appraisal_performance_item_copyTypeTplData(){
		$infoData =json_decode(stripslashes($_POST['infoData']),true);
		$infoData=mb_iconv($infoData);
	    $odlYear=trim($infoData['odlYear']);
	    $odlStyle=trim($infoData['odlStyle']);
	    $odlDeptId=trim($infoData['odlDeptId']);
	    $newYear=trim($infoData['newYear']);
	    $newStyle=trim($infoData['newStyle']);
	    $newDeptId=trim($infoData['newDeptId']);
		if($odlYear&&$odlStyle&&$odlDeptId&&$newYear&&$newStyle&&$newDeptId){
			try
			{
				$this -> _db -> query ( "START TRANSACTION" );
			    $sql="SELECT * FROM appraisal_performance_template WHERE 1 and years='$odlYear' and  dept_id='$odlDeptId' and quarter='$odlStyle' and addTplFlag=3";
			    $query = $this->query ( $sql );
				$data = array();
				while ( ($row = $this->fetch_array ( $query )) != false )
				{
					array_push($data,$row);
				}
				$this->tbl_name='appraisal_performance_template';
				if($data&&is_array($data)){
				   foreach($data as $key =>$val){
				   	if($val['id']){ 
				      $val['copy_id']=$val['id'];
				      $val['addTplFlag']=2;
				      $val['isFlag']=1;
				      $val['years']=$newYear;
				      $val['dept_id']=$newDeptId;
				      $val['quarter']=$newStyle;
				      $val['date']=date('Y-m-d');
				      unset($val['id']);
				      $this->create($val);
				      $newId=$this -> _db -> insert_id ( );
				      $tplData[$val['copy_id']]['id']=$newId;
				      $tplData[$val['copy_id']]['pid']=$val['copy_id'];
				      $pids=$val['copy_id'].','.$pids;
				   	}
				   }
				}
				$pids=trim($pids,',');
				if($pids&&$tplData&&is_array($tplData)){
					$this->model_administration_appraisal_performance_item_copyInData($pids,$tplData);
				}
			$this -> _db -> query ( "COMMIT" );
			return 2;
		} catch ( Exception $e )
		{
			$this -> _db -> query ( "ROLLBACK" );
			return false;
		}
		}		
	}
	function model_administration_appraisal_performance_item_copyInData($pids,$tplData){
        $pids=trim($pids,',');
		if($pids&&$tplData&&is_array($tplData)){
        	$this->tbl_name='appraisal_template_columnname';
        	$sql="SELECT * FROM appraisal_template_columnname WHERE   1 and tid in ($pids)";			
		    $query = $this->query ( $sql );
		    $colData = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				array_push($colData,$row);
			}
        	if($colData&&is_array($colData)){
        	   foreach($colData as $key =>$val){
        	   	   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']){ 
				   	  $val['tId']=$tplData[$val['tId']]['id']; 
				      $val['createDate']=date('Y-m-d H:i:s');
				       unset($val['id']);
				       $this->create($val);
				      }
			   }	
        		
        	}
        	$this->tbl_name='appraisal_template_projectname';
        	$sql="SELECT * FROM appraisal_template_projectname WHERE   1 and tid in ($pids)";				
		    $query = $this->query ( $sql );
		    $proData = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				array_push($proData,$row);
			}
        	if($proData&&is_array($proData)){
        	   foreach($proData as $key =>$val){
				   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']){ 
				   	  $val['tId']=$tplData[$val['tId']]['id']; 
				      $val['createDate']=date('Y-m-d H:i:s');
				      unset($val['id']);
				      $this->create($val);
				      $proId=$this -> _db -> insert_id ( );
				      $tmpProData[$val['projectName']]['id']=$proId;
				      $tmpProData[$val['projectName']]['name']=$val['projectName'];
				     }
			   }	
        		
        	}
        	$this->tbl_name='appraisal_template_contents';
        	$sql="SELECT * FROM appraisal_template_contents WHERE   1 and tid in ($pids)";				
		    $query = $this->query ( $sql );
		    $conData = array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				array_push($conData,$row);
			}
        	if($conData&&is_array($conData)){
        	   foreach($conData as $key =>$val){
				   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']&&$tmpProData[$val['projectName']]['name']==$val['projectName']){ 
				   	  $val['tId']=$tplData[$val['tId']]['id'];
				   	  $val['projectId']=$tmpProData[$val['projectName']]['id']; 
				      $val['createDate']=date('Y-m-d H:i:s');
				      unset($val['id']);
				      $this->create($val);
				     }
			   }	
        		
        	}
        	$this->tbl_name='appraisal_template_userlist';
        	$sql="SELECT * FROM appraisal_template_userlist WHERE   1 and tid in ($pids)";				
		    $query = $this->query ( $sql );
		    $userData= array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				array_push($userData,$row);
			}
        	if($userData&&is_array($userData)){
        	   foreach($userData as $key =>$val){
				   	if($val['id']&&$tplData[$val['tId']]['pid']==$val['tId']){ 
				   	  $val['tId']=$tplData[$val['tId']]['id']; 
				      $val['createDate']=date('Y-m-d H:i:s');
				      unset($val['id']);
				      $this->create($val);
				     }
			   }	
        		
        	}
        }
	}
	
	function model_administration_appraisal_performance_item_createTplData(){
		$infoData =json_decode(stripslashes($_POST['infoData']),true);
		$infoData=mb_iconv($infoData);
	    $createDeptId=trim($infoData['createDeptId']);
	    $createYear=trim($infoData['createYear']);
	    $createStyle=trim($infoData['createStyle']);
	    $now=date('Y-m-d');
	    if($createStyle==1){
	    	if(strtotime($now)>strtotime(date('Y').'-04-15')){
	    	   return 3;
	        }
	    }else if($createStyle==2){
	    	if(strtotime($now)>strtotime(date('Y').'-07-15')){
	    	   return 3;
	        }
	    }else if($createStyle==3){
	    	if(strtotime($now)>strtotime(date('Y').'-10-21')){
	    	   return 3;
	        }
	    }else if($createStyle==4){
	    	if(strtotime($now)>strtotime((date('Y')+1).'-01-15')){
	    	   return 3;
	        }
	    }
		if($createDeptId&&$createYear&&$createStyle){
			try
			{
			   $this -> _db -> query ( "START TRANSACTION" );
			   /*
			   $this->tbl_name = 'appraisal_performance';
     	       $userData=$this->findAll(array('deptId'=>$createDeptId,'years'=>$createYear,'quarter'=>$createStyle));
			   if($userData&&is_array($userData)){
			     foreach($userData as $key=>$val){
			   	   $str.="'".$val['user_id']."',";
			     }
			     $str=trim($str,',');
			     if($str){
			     	$sql=" AND a.USER_ID not in($str) ";
			     }	
			   }*/
			   $sql = "insert into appraisal_performance(user_id,userName,comeInDate,ReguDate,deptId,deptName,jobId,jobName,tpl_id,name,
			   			years,quarter,tplStyleFlag,assess_userid,assessName,audit_userid,auditName,date,isAss,asAss,asAudit,asPers,userNo)
					   (SELECT a.USER_ID,a.USER_NAME,d.COME_DATE,d.JOIN_DATE,a.DEPT_ID,e.DEPT_NAME,a.jobs_id,f.`name`,c.id,c.`name`,
					    c.years,c.`quarter`,c.tplStyleFlag,b.assess,b.assessName,b.audit,b.auditName ,now(),c.isAss,c.asAss,c.asAudit,
					    c.asPers,d.UserCard
						FROM  `user` a LEFT JOIN  
						appraisal_template_userlist b ON ((find_in_set(a.USER_ID,b.userStr) AND b.userType=1) or (find_in_set(a.jobs_id,b.userStr) AND b.userType=2)) 
						LEFT JOIN  appraisal_performance_template  c  ON (b.tId=c.id AND c.addTplFlag=3 AND c.isFlag=1 ) 
						LEFT JOIN hrms d ON (a.USER_ID=d.USER_ID) 
						LEFT JOIN department e ON (a.DEPT_ID=e.DEPT_ID)
						LEFT JOIN user_jobs  f ON (a.jobs_id=f.id)
						WHERE c.addTplFlag=3 AND c.isFlag=1 AND c.dept_id='$createDeptId' AND c.years='$createYear' AND c.`quarter`='$createStyle' AND a.dept_id='$createDeptId'  AND a.HAS_LEFT=0 AND a.DEL=0
						GROUP BY c.id,a.USER_ID )	
				";
			$flag=$this->query($sql);
			
			$upSql = "SELECT c.id,a.EMAIL,a.user_id,a.user_name,c.name
						FROM  `user` a LEFT JOIN  
						appraisal_template_userlist b ON ((find_in_set(a.USER_ID,b.userStr) AND b.userType=1) or (find_in_set(a.jobs_id,b.userStr) AND b.userType=2)) 
						LEFT JOIN  appraisal_performance_template  c  ON (b.tId=c.id AND c.addTplFlag=3 AND c.isFlag=1 )
						LEFT JOIN hrms d ON (a.USER_ID=d.USER_ID) 
						LEFT JOIN department e ON (a.DEPT_ID=e.DEPT_ID)
						LEFT JOIN user_jobs  f ON (a.jobs_id=f.id)
						WHERE c.addTplFlag=3 AND c.isFlag=1 AND c.dept_id='$createDeptId' AND c.years='$createYear' AND c.`quarter`='$createStyle' AND a.dept_id='$createDeptId'  AND a.HAS_LEFT=0 AND a.DEL=0
						GROUP BY c.id,a.USER_ID 	
			";
			$query = $this->query ( $upSql );
			$strData=array();
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$strData[]=$row['id'];
				$userData[$row[ 'user_id' ]]['email'] = $row[ 'EMAIL' ];
				$userData[$row[ 'user_id' ]]['tplName'] = $row[ 'name' ];
				$userData[$row[ 'user_id' ]]['userName'] = $row[ 'user_name' ];
			}
			$mail_server = new includes_class_sendmail ( );
			$tplWeek=$createYear.'年  '.($createStyle==5?' 上半年':($createStyle==6?' 下半年':$createStyle==7?' 全年':' 第'.$createStyle.'季')).'度';
			       
			if($userData&&is_array($userData)){
				foreach($userData as $key => $val){
				  $tpLName=$val['tplName'].' '.$tplWeek.'考核表';
				  $body = "您好，" . $val[ 'userName' ] . "$tpLName 已发布，请您登录OA查看。<br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
			      $mail_server -> send ($tpLName.'已发布' , $body , $val['email'] );		
				}
			}
			
			$strId=implode(',',$strData);
			if($strId){
			   $upTsql="UPDATE appraisal_performance_template SET isFlag=2 WHERE id in ($strId)";	
			}
			$this->query($upTsql);
			
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
	
	function model_administration_appraisal_performance_item_begineUpData(){
		$infoData =json_decode(stripslashes($_POST['infoData']),true);
		$infoData=mb_iconv($infoData);
	    $createDeptId=trim($infoData['begineDeptId']);
	    $createYear=trim($infoData['begineYear']);
	    $createStyle=trim($infoData['begineStyle']);
		if($createDeptId&&$createYear&&$createStyle){
			try
			{
			$sltSql ="  SELECT id,EMAIL,user_id,user_name,name,flag 
						FROM (
						(
						SELECT  a.id,b.EMAIL,b.user_id,b.user_name,a.name,2 AS flag
						FROM  appraisal_performance  a 
						LEFT JOIN `user` b ON (a.user_id=b.USER_ID)
						WHERE a.inFlag=1  AND a.isAss=1 AND b.HAS_LEFT=0 AND b.DEL=0
						AND a.deptId='$createDeptId' AND a.years='$createYear' AND a.`quarter`='$createStyle' 
						)UNION(
						SELECT b.id,c.EMAIL,c.user_id,c.user_name,b.name,3 AS flag
						FROM appraisal_evaluate_list a 
						LEFT JOIN appraisal_performance b ON a.kId=b.id 
						LEFT JOIN `user` c ON a.evaluators_userid=c.USER_ID 
						WHERE b.inFlag=1  AND b.isAss<>1 AND b.isEval=2
						AND c.HAS_LEFT=0 AND c.DEL=0
						AND b.deptId='$createDeptId' AND b.years='$createYear' AND b.`quarter`='$createStyle' AND c.dept_id='$createDeptId' 
						)UNION(
						SELECT  a.id,b.EMAIL,b.user_id,b.user_name,a.name,4 AS flag
						FROM  appraisal_performance  a 
						LEFT JOIN `user` b ON (a.assess_userid=b.USER_ID  )
						WHERE a.inFlag=1  AND a.isAss<>1 AND a.isEval=1
						AND   b.HAS_LEFT=0 AND b.DEL=0
						AND a.deptId='$createDeptId' AND a.years='$createYear' AND a.`quarter`='$createStyle'
						)UNION(
						SELECT  a.id,b.EMAIL,b.user_id,b.user_name,a.name,5 AS flag
						FROM  appraisal_performance  a 
						LEFT JOIN `user` b ON (a.audit_userid=b.USER_ID  )
						WHERE a.inFlag=1  AND a.isAss<>1 AND a.isEval=1 AND a.assess_userid=''
						AND   b.HAS_LEFT=0 AND b.DEL=0
						AND a.deptId='$createDeptId' AND a.years='$createYear' AND a.`quarter`='$createStyle'
						)
						) s";
			$query = $this->query ( $sltSql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$userData[$row[ 'id' ]]['email'] = $row[ 'EMAIL' ];
				$userData[$row[ 'id' ]]['tplName'] = $row[ 'name' ];
				$userData[$row[ 'id' ]]['userName'] = $row[ 'user_name' ];
				$userData[$row[ 'id' ]]['flag'] =$row[ 'flag' ];
				$upData[$row[ 'flag' ]][]=$row[ 'id' ];
			}
		    if($upData&&is_array($upData)){
		    	foreach($upData as $key=>$val){
		    		if($val&&is_array($val)){
		    			$idStr=implode(',',$val);
		    		    $sql = "update appraisal_performance set inFlag='$key'
					    WHERE id in($idStr)";
			         	$flag=$this->query($sql);	
		    		}
		    	}
		    }
		    $mail_server = new includes_class_sendmail ( );
			$tplWeek=$createYear.'年  '.($createStyle==5?' 上半年':($createStyle==6?' 下半年':$createStyle==7?' 全年':' 第'.$createStyle.'季')).'度';
			if($userData&&is_array($userData)){
				foreach($userData as $key => $val){
				  $flagStr='操作';
				  //$tpLName=$val['tplName'].' '.$tplWeek.'考核表';
				  if($val[ 'flag' ]=='2'){
				  	$flagStr='自评';
				  }else if($val[ 'flag' ]=='3'){
				  	$flagStr='评价';
				  }else if($val[ 'flag' ]=='4'){
				  	$flagStr='考核';
				  }else if($val[ 'flag' ]=='5'){
				  	$flagStr='审核';
				  }
				  $tpLName= $tplWeek.'考核';
				  $body = " 您好，" . $val[ 'userName' ] . "!<br/><br/> ".$tpLName."已开始考核，请您登录OA开始进行 ".$flagStr."。<br /> <br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
			      $mail_server -> send ($tpLName."已开始$flagStr" , $body , $val['email'] );		
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
	function model_administration_appraisal_performance_item_begineUpData_test(){
		$infoData =json_decode(stripslashes($_POST['infoData']),true);
		$infoData=mb_iconv($infoData);
	    $createDeptId=trim($infoData['begineDeptId']);
	    $createYear=trim($infoData['begineYear']);
	    $createStyle=trim($infoData['begineStyle']);
		if($createDeptId&&$createYear&&$createStyle){
			try
			{
		    $upSql ="SELECT  a.id,b.EMAIL,b.user_id,b.user_name,a.name
					FROM  appraisal_performance  a LEFT JOIN  
					`user` b ON (a.user_id=b.USER_ID)
					WHERE a.inFlag=1  AND a.isAss=1 AND a.deptId='$createDeptId' AND a.years='$createYear' AND a.`quarter`='$createStyle'  AND b.HAS_LEFT=0 AND b.DEL=0	
			";
			$query = $this->query ( $upSql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$userData[$row[ 'id' ]]['email'] = $row[ 'EMAIL' ];
				$userData[$row[ 'id' ]]['tplName'] = $row[ 'name' ];
				$userData[$row[ 'id' ]]['userName'] = $row[ 'user_name' ];
				$userData[$row[ 'id' ]]['flag'] =1;
			}
			$upSql ="SELECT b.*,a.evaluators_userid,a.evalName,a.flag,a.count_fraction,evalDate
				FROM appraisal_evaluate_list a 
				LEFT JOIN appraisal_performance b ON a.kId=b.id 
                LEFT JOIN `user` c ON a.evaluators_userid=c.USER_ID 
				WHERE b.inFlag=1  AND b.isAss<>1 AND b.isEval=2 AND c.HAS_LEFT=0 AND c.DEL=0 and b.deptId='$createDeptId' AND b.years='$createYear' AND b.`quarter`='$createStyle' AND c.dept_id='$createDeptId' 
			";
			$query = $this->query ( $upSql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$userData[$row[ 'id' ]]['email'] = $row[ 'EMAIL' ];
				$userData[$row[ 'id' ]]['tplName'] = $row[ 'name' ];
				$userData[$row[ 'id' ]]['userName'] = $row[ 'user_name' ];
				$userData[$row[ 'id' ]]['flag'] =2;
			}
			$upSql ="SELECT  a.id,b.EMAIL,b.user_id,b.user_name,a.name
					FROM  appraisal_performance  a LEFT JOIN  
					`user` b ON (a.assess_userid=b.USER_ID  )
					WHERE a.inFlag=1  AND a.isAss<>1 AND a.isEval=1 AND a.deptId='$createDeptId' AND a.years='$createYear' AND a.`quarter`='$createStyle' AND   b.HAS_LEFT=0 AND b.DEL=0	
			";
			$query = $this->query ( $upSql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$userData[$row[ 'id' ]]['email'] = $row[ 'EMAIL' ];
				$userData[$row[ 'id' ]]['tplName'] = $row[ 'name' ];
				$userData[$row[ 'id' ]]['userName'] = $row[ 'user_name' ];
				$userData[$row[ 'id' ]]['flag'] =3;
			}
			
			$mail_server = new includes_class_sendmail ( );
			$tplWeek=$createYear.'年  '.($createStyle==5?' 上半年':($createStyle==6?' 下半年':$createStyle==7?' 全年':' 第'.$createStyle.'季')).'度';
			       
			if($userData&&is_array($userData)){
				foreach($userData as $key => $val){
				  $tpLName=$val['tplName'].' '.$tplWeek.'考核表';
				  if($val[ 'flag' ]=='1'){
				  	$flagStr='自评';
				  }else if($val[ 'flag' ]=='2'){
				  	$flagStr='评价';
				  }else if($val[ 'flag' ]=='3'){
				  	$flagStr='考核';
				  }
				  $body = " 您好，" . $val[ 'userName' ] . "$tpLName 已开始考核，请您登录OA开始进行$flagStr。<br /><br /><br />栏目路径:个人办公-->工作任务-->人事类-->绩效考核<br /><br />" . oaurlinfo;
			      $mail_server -> send ($tpLName."已开始$flagStr" , $body , $val['email'] );		
				}
			}
			$sql = "update appraisal_performance set inFlag=2
					    WHERE inFlag=1  AND deptId='$createDeptId' AND years='$createYear' AND `quarter`='$createStyle' 
					   ";
					   
			$flag=$this->query($sql);
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
	function model_administration_appraisal_performance_item_init(){
	 set_time_limit (0);
	 ini_set ( 'memory_limit' , '128M' );
  	 $Sql ="SELECT  a.*,b.DEPT_NAME
            FROM   appraisal_performance_template a  LEFT JOIN  department b ON a.dept_id=b.DEPT_ID 
           ";
			$query = $this->query ( $Sql );
			while ( ($row = $this->fetch_array ( $query )) != false )
			{
				$upFlagData=array('deptName'=>$row['DEPT_NAME'],'addTplFlag'=>3,'isFlag'=>2);
				$this->tbl_name = 'appraisal_performance_template';
				$flag=$this -> update ( array ( 'id' =>$row['id']) , $upFlagData); 
				
			}
		
	}
}
?>