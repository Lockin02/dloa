<?php
/*
 * Created on 2011-4-27
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_common_otherdatas extends model_base {
	function __construct() {
		parent::__construct ();
	}

	/**************************** S 用户信息 ********************************/

	/**
	 * 返回用户信息
     * ViceManager 副总
     * MajorId 总监
	 */
	public function getUserDatas($userId, $find = null) {
		$rs = $this->_db->getArray ( "
			select a.*,b.*,c.name as jobs_name,d.id AS areaid,d.Name as areaname,b.MajorId,b.ViceManager from user as a
			left join department as b on b.DEPT_ID=a.DEPT_ID
			left join user_jobs as c on c.id=a.jobs_id
			left join area as d on d.id=a.AREA
			where a.USER_ID='$userId'
			" );
		$rs = $rs [0];
		if (is_array ( $find )) {
			$arr = array ();
			foreach ( $find as $val ) {
				$arr [$val] = $rs [$val];
			}
			return $arr;
		} else if(!empty($find)){
			return $rs [$find];
		} else {
			return $rs;
		}
	}

	/**
	 * 根据用户id返回上级名称
	 */
	function getManager($userId){
		$sql = "SELECT
					d.USER_NAME as managerName,d.USER_ID as managerId
				 from
					user u
					left join
					(
						select
							d.DEPT_ID,u.USER_NAME,substring_index(if (d.MajorId is null or d.MajorId = '', d.ViceManager, d.MajorId), ',', 1)  as USER_ID
						from
							department d
						left join user u on substring_index(if(d.MajorId is null or d.MajorId = '' ,d.ViceManager,d.MajorId),',',1) = u.USER_ID
					) d on u.DEPT_ID = d.DEPT_ID where u.USER_ID = '$userId'";
        $managerArr = $this->_db->getArray($sql);
        if(is_array($managerArr)){
			return $managerArr[0];
        }else{
        	return false;
        }
	}

	/**
     * 根据用户名称返回用户ID(返回的数据包括重名的用户)
     */
    function getUserID($userName){
        $sql = "select USER_ID from user where USER_NAME = '$userName' ";
        $userIdArr = $this->_db->getArray($sql);
        return $userIdArr;
    }

    /**
     * 返回用户区域及上级领导信息
     */
    public function getAreaAndLeader($userId) {
        $rs = $this->_db->getArray ( "
            SELECT a.name as thisAreaName,d.Leader_name from user u left join department d on u.DEPT_ID = d.DEPT_ID left join area a  on u.area = a.id
            where u.USER_ID='$userId'
            " );
        $rs = $rs [0];
        return $rs;
    }

	/**
	 * 获取用户名称
	 *
	 * @param $idlist
	 * @return return_type
	 */
	function getUsernameList ($idlist) {
	    $idlist=trim($idlist);
	    $restr="";
	    if(trim($idlist,",")!=""){
	        $sqlstr="";
	        $idarr=explode(",",$idlist);
	        foreach($idarr as $val){
	            if(trim($val)!=""){
	                $sqlstr.="'".$val."',";
	            }
	        }
	        $sqlstr=substr($sqlstr,0,strlen($sqlstr)-1);
	        if(strlen($sqlstr)){
	            $sql="select USER_NAME from user where USER_ID in ($sqlstr)";
	        }else{
	            return $restr;
	        }
	        $this->sort='USER_ID';
	        $rows=$this->listBySql($sql);
	        foreach($rows as $key=>$val){
	        	$restr.=$val['USER_NAME'];
	        }
	        return $restr;
		}
	}

	/**
	 * 获取用户帐号
	 *
	 * @param $idlist
	 * @return return_type
	 */
	function getUserInfo ($user_name) {
	    if(!empty($user_name)){
            $sql="select
					u.USER_ID,
					d.DEPT_NAME,
					u.DEPT_ID,
					d. code as DEPT_CODE,h.userCard as userNo,job.id as jobId,job.name as jobName
				from
					user u left join hrms h on u.USER_ID = h.USER_ID left join department d on u.DEPT_ID=d.DEPT_ID left join user_jobs job on u.jobs_id=job.id
				where
					USER_NAME =  '$user_name'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0];
		}
		return null;
	}

	/**
	 * 获取用户账户信息
	 */
	function getUserInfoByUserNo($userNo){
		if(!empty($userNo)){
            $sql="select u.USER_ID,d.DEPT_NAME,u.DEPT_ID,d.code as DEPT_CODE,h.userCard as userNo,job.id as jobId,job.name as jobName,u.USER_NAME as userName from user u left join hrms h on u.USER_ID = h.USER_ID left join department d on u.DEPT_ID=d.DEPT_ID left join user_jobs job on u.jobs_id=job.id where h.UserCard = '$userNo'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0];
		}
		return null;
	}

	/**
	 *  根据部门获取部门id
	 * $isDel 1（已删除）/0（在用）/ALL（所有）
	 */
	function getDeptId_d($deptName,$isDel = "0"){
	    if(!empty($deptName)){
			if($isDel == 'ALL'){
				$sqlPlus = "";
			}else{
				$sqlPlus = " and d.DelFlag = '$isDel'";
			}
            $sql="select d.DEPT_ID from department d where d.DEPT_NAME = '$deptName'".$sqlPlus;
	        $rows=$this->_db->getArray($sql);
	        return $rows[0]['DEPT_ID'];
		}
	}

    /**
     * 根据部门获取部门信息
     * ViceManager 副总
     * MajorId 总监
     * $isDel 1（已删除）/0 -（在用）/ALL（所有）
     */
    function getDeptById_d($deptId,$isDel = "0"){
        if(!empty($deptId)){
            if($isDel == 'ALL'){
                $sqlPlus = "";
            }else{
                $sqlPlus = " and d.DelFlag = '$isDel'";
            }
            $sql="select d.DEPT_ID,d.DEPT_NAME,d.Depart_x,d.levelflag,d.MajorId,d.ViceManager,d.module from department d where d.DEPT_ID = '$deptId'".$sqlPlus;
            $rows=$this->_db->getArray($sql);
            return $rows[0];
        }
    }

	/**
	 * 根据部门获取部门信息
	 * $isDel 1（已删除）/0 -（在用）/ALL（所有）
	 */
	function getDeptInfo_d($deptName,$isDel = "0"){
	    if(!empty($deptName)){
			if($isDel == 'ALL'){
				$sqlPlus = "";
			}else{
				$sqlPlus = " and d.DelFlag = '$isDel'";
			}
            $sql="select d.DEPT_ID,d.Depart_x,d.levelflag from department d where d.DEPT_NAME = '$deptName'".$sqlPlus;
	        $rows=$this->_db->getArray($sql);
	        return $rows[0];
		}
	}

	function getDeptSMap_d() {
		$sql = "select d.DEPT_ID,d.DEPT_NAME,d.Depart_x from department d";
		$orgData = $this->_db->getArray($sql);

		// 最终映射结果
		$map = array();

		// 部门DEPART_X映射，用于查找部门匹配
		$xmap = array();

		// 先提取二级部门
		foreach ($orgData as $k => $v) {
			// 如果字符长度是2，则此部门是二级部门，直接用这个部门的ID
			if (strlen($v['Depart_x']) == 2) {
				$map[$v['DEPT_ID']] = array('DEPT_ID' => $v['DEPT_ID'], 'DEPT_NAME' => $v['DEPT_NAME']);
				$xmap[$v['Depart_x']] = array('DEPT_ID' => $v['DEPT_ID'], 'DEPT_NAME' => $v['DEPT_NAME']);
				unset($orgData[$k]);
			}
		}

		if (!empty($orgData)) {
			foreach ($orgData as $k => $v) {
				$sdept = substr($v['Depart_x'], 0, 2);
				if (isset($xmap[$sdept])) {
					$map[$v['DEPT_ID']] = $xmap[$sdept];
				}
			}
		}

		return $map;
	}

	/**
	 *  根据职位名称获取职位id
	 */
	function getJobId_d($jobName){
	    if(!empty($jobName)){
            $sql="select id from user_jobs d where name = '$jobName'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0]['id'];
		}
	}

	/**
	 *  根据职位名称和部门ID获取职位id
	 */
	function getJobIdByJobName_d($jobName,$deptId){
	    if(!empty($jobName)){
            $sql="select id from user_jobs d where name = '$jobName' and dept_id='$deptId'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0]['id'];
		}
	}

	/**
	 *  根据职位id获取职位名称
	 */
	function getJobName_d($jobId){
	    if(!empty($jobId)){
            $sql="select name from user_jobs d where id = '$jobId'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0]['name'];
		}
	}

	/**
	 * 获取用户编号
	 *
	 * @param $userId 用户ID
	 */
	function getUserCard ($userId) {
		$userSql="select UserCard from hrms where USER_ID='".$userId."'";
		$userCard=mysql_fetch_row($this->query($userSql));
		return $userCard[0];
	}

	/**
	 * 获取用户信息
	 */
	function getUserAllInfo($userId){
		if(!$userId){
			return null;
		}
		$userSql="select * from hrms where USER_ID='".$userId."'";
		$userInfo=$this->_db->getArray($userSql);
		if($userInfo){
			return $userInfo[0];
		}else{
			return null;
		}
	}

	/**
	 * 获取用户通许录信息
	 */
	function getUserConnectInfo($userId){
		if(!$userId){
			return null;
		}
		$userSql="select * from ecard where USER_ID='".$userId."'";
		$userInfo=$this->_db->getArray($userSql);
		if($userInfo){
			return $userInfo[0];
		}else{
			return null;
		}
	}

	/**
	 * 获取用户信息
	 */
	function getPersonnelInfo_d($userId){
		if(!$userId){
			return null;
		}
		$sql = "select h.CARD_NO as identityCard,h.BIRTHDAY as birthdate,if(e.MOBILE1 is null or e.MOBILE1 = '',e.MOBILE3,e.MOBILE1) as mobile from hrms h left join ecard e on h.USER_ID = e.User_id
where h.USER_ID = '$userId'";
		$userInfo=$this->_db->getArray($sql);
		if($userInfo){
			return $userInfo[0];
		}else{
			return null;
		}
	}

	/*
	 *获得所有分公司名称
	 */
	function getCompanyAndAreaInfo(){
		$sql="select  NameCN , ID  from branch_info ";
		$CompanyAndAreaInfo=$this->_db->getArray($sql);
		return $CompanyAndAreaInfo;
	}

	/*
	 * 获得所有区域
	 */
	function getArea(){
		$sql="select  ID , Name  from area ";
		$areaArr=$this->_db->getArray($sql);
		return $areaArr;
	}

	/*
	 * 根据名称获得区域
	 */
	function getAreaByName($name){
		$sql="select * from area where Name = '".$name."'";
		$rows=$this->_db->getArray($sql);
		return $rows;
	}

	/**
	 * 获取机构人员
	 */
	function getUnitUser(){
		$sql = "select 1 as valid,DEPT_ID as unitID,DEPT_NAME as unitName,PARENT_ID as parentUnitID,levelflag as level,'' as des from department WHERE DelFlag = 0 ORDER BY PARENT_ID,DEPT_ID";
		$rows=$this->_db->getArray($sql);
		$rtArr = array();
		foreach($rows as $v){
			$v['userInfoList'] = $this->getUnitUserSingle($v['unitID']);
			if($v['parentUnitID'] == 0){
				$v['parentUnitID'] = '';
				$rtArr[$v['unitID']]['unitInfo'] = $v;
			}else{
				if(isset($rtArr[$v['parentUnitID']])){
					$rtArr[$v['parentUnitID']]['unitInfo']['subUnitInfoList'][$v['unitID']]['unitInfo'] = $v;
					$rtArr[$v['parentUnitID']]['unitInfo']['subUnitInfoList'][$v['unitID']]['unitInfo']['parentUnitID'] = $rtArr[$v['parentUnitID']]['unitID'];
				}
			}
		}
		$rtArr = array( array(
			'valid' => '',
            'unitID' => '0',
            'unitName' => '集团',
            'parentUnitID' => '',
            'level' => '',
            'des' => '',
            'subUnitInfoList' => $rtArr
		));
		return $rtArr;
	}

	/**
	 * 获取机构成员 - 单个
	 */
	function getUnitUserSingle($DEPT_ID){
		$sql = "select
				USER_ID as userId,USER_ID as userTitle,USER_NAME as userName,`PASSWORD` as UserPassword,DEPT_ID as unitId,'' as duty,
				'' as nation,'' as idCard,'' as nativePlace,'' as education,'' as tempAddress,'' as contact,'' as smsTel,'' as userDes,
				'' as birthday,'' as disabled,'' as canEditPassWord,'' as userType,SEX
			from user where DEPT_ID = '$DEPT_ID' and DEL = 0";
		$rows=$this->_db->getArray($sql);
		foreach($rows as &$v){
			$v['SEX'] = $v['SEX'] == 0 ? '男' : '女';
			$v = array('userInfo' => $v);
		}
		return $rows;
	}

	/**
	 * 职位信息获取
	 */
	function getRoleInfo(){
		$sql = "select id as roleId,name as roleName,'' as des from user_jobs";
		$rows=$this->_db->getArray($sql);
		foreach($rows as &$v){
			$v = array('roleInfo' => $v);
		}
		return $rows;
	}

	/**
	 * 获取角色下用户列表
	 */
	function getUserRelaRole(){
		$sql ="select jobs_id as roleId,USER_ID as userId from user where DEL = 0";
		$rows=$this->_db->getArray($sql);
		foreach($rows as &$v){
			$v = array('userRelaRole' => $v);
		}
		return $rows;
	}
	/**************************** E 用户信息 ********************************/

	/**************************** S 工作流 ********************************/

	/**
	 * 验证前工作流是否最后一步，返回true或者false
	 */
	public function isLastStep($objId, $objTable) {
		$sql = "select if(max(s.smallId) = max(f.smallid),1,0) as isLastStep from wf_task w left join flow_step_partent f on w.task = f.Wf_task_id left join flow_step s on w.task = s.Wf_task_id where w.Pid = '$objId' and w.code = '$objTable' and f.flag = 0 group by w.task ORDER BY w.task DESC";
		$rs = $this->_db->getArray ( $sql );
		return $rs [0] ['isLastStep'];
	}
		/**
	 * 验证前工作流是否第一步，返回true或者false
	 */
	public function isFirstStep($objId, $objTable) {
		$sql = "select if(min(s.smallId) = min(f.smallid),1,0) as isFirstStep from wf_task w left join flow_step_partent f on w.task = f.Wf_task_id left join flow_step s on w.task = s.Wf_task_id where w.Pid = '$objId' and w.code = '$objTable' and f.flag = 0 group by w.task ORDER BY w.task DESC";
		$rs = $this->_db->getArray ( $sql );
		return $rs [0] ['isFirstStep'];
	}

	/**
	 * 根据spid获取工作流业务信息
	 */
	public function getWorkflowInfo($spid) {
		$sql = "select w.task as taskId ,w.code as objType , w.pid as objId,w.examines as examines,w.name as formName,w.Enter_user ,w.DBTable,p.Result,p.isEditPage from flow_step_partent p inner join wf_task w on p.Wf_task_ID = w.task where p.ID = $spid ";
		$rs = $this->_db->getArray ( $sql );

		if (is_array ( $rs ) && count ( $rs ) > 0) {
			return $rs [0];
		} else {
			throw new Exception ( "无工作流相关业务信息!" );
			return null;
		}
	}

	/**
	 * 根据spid获取步骤审批信息
	 */
	public function getStepInfo($spid) {
        if(empty($spid)) return false;
		$sql = "select
				u.USER_NAME,p.User as USER_ID,p.content,
				w.pid as objId,w.Enter_user,w.task,w.examines
			from
				flow_step_partent p inner join wf_task w on p.Wf_task_ID = w.task
				inner join
				user u on p.User = u.USER_ID
			where
				p.ID = $spid ";
		$rs = $this->_db->getArray ( $sql );

		if (is_array ( $rs ) && count ( $rs ) > 0) {
			return $rs [0];
		} else {
			throw new Exception ( "无工作流相关业务信息!" );
			return null;
		}
	}

	/**
	 * 根据task获取工作流息
	 */
	function getWorkflowEnterUser_d($task){
		$sql = "select w.Enter_user,u.USER_NAME from wf_task w inner join user u on w.Enter_user = u.USER_ID where 1=1 and w.task = " . $task;
		$rs = $this->_db->getArray ( $sql );

		if (is_array ( $rs ) && count ( $rs ) > 0) {
			return $rs [0];
		} else {
			return null;
		}
	}

	/**
	 * 获取在执行的审批步骤
	 */
	function getAuditngStepInfo($task){
		$sql = "select
				w.pid as objId,
				w.Enter_user,
				w.task,
				s.isReceive,
				s.isEditPage
			from
				flow_step s inner join wf_task w on s.Wf_task_ID = w.task
			where w.task = $task and s.Endtime is null ";
		$rs = $this->_db->getArray ( $sql );

		if (is_array ( $rs ) && count ( $rs ) > 0) {
			return $rs [0];
		} else {
			return null;
		}
	}

	/**
	 * 根据task获取最后一步工作流信息
	 */
	function getLastWorkflow_d($task){
		$sql = "select w.Enter_user,u.USER_NAME from wf_task w inner join user u on w.Enter_user = u.USER_ID where 1=1 and w.task = " . $task;
		$rs = $this->_db->getArray ( $sql );

		if (is_array ( $rs ) && count ( $rs ) > 0) {
			return $rs [0];
		} else {
			return null;
		}
	}
	/**
	 * 获取id串的最后审批信息
	 */
	function getIdsLastExaInfo_d($ids,$code){
        $sqlstr="";
        $idarr=explode(",",$code);
        foreach($idarr as $val){
            if(empty($sqlstr)){
				$sqlstr = "'" . $val . "'";
            }else{
				$sqlstr .= ",'" . $val . "'";
            }
        }

		$sql = "select
			c.user,c.content,c.task,c.pid,c.code,c.name,u.USER_NAME
			from
			(
			select
				p.user,p.content,c.task,c.pid,c.code,c.name
			from
				(
				select
					c.task,c.pid,c.code,c.name
				from
					wf_task c
				where
					c.code in($sqlstr)
					and
					c.pid in($ids)
				order by c.task desc
				) c
				left join
				(
					select
						p.Wf_task_ID,p.user,p.content
					from
						flow_step_partent p
					order by p.ID desc
				)  p
				on c.task = p.Wf_task_ID
			) c
			left join
			user u
			on c.user = u.USER_ID
			GROUP BY c.pid,c.code";
		$rs = $this->_db->getArray ( $sql );
		if (is_array ( $rs ) && count ( $rs ) > 0) {
			return $rs;
		} else {
			return null;
		}
	}

	/**************************** E 工作流 ********************************/



	/**
	 *  获取模块权限
	 */
	function getUserPriv($thisModel,$userId = null,$deptId = null ,$jobId = null){
		$sqlAdd = null;
		if(!empty($userId)){
			$sqlAdd .= "and (pi.userid = '$userId' ";
		}
		if(!empty($deptId)){
			$sqlAdd .= " or pi.deptid = '$deptId' ";
		}


		if(!empty($jobId)){
			$sqlAdd .= " or pi.jobsid = '$jobId' ";
		}

		$sqlAdd .= ")";

		$sql = "select
					p.id,p.control,ty.name,pi.content
					from purview p left join purview_type ty on  p.id  = ty.tid left join purview_info pi on p.id = pi.tid
					where p.models='$thisModel' and p.type=1 and p.control=1 and ty.id = pi.typeid $sqlAdd ";

		$rs = $this->_db->getArray($sql);

		$rows = array();
        if(!empty($rs)){
            foreach($rs as $key => $val){
                $rows[$val['name']] = $val['content'];
            }
        }
		return $rows;
	}

	/***************************** 费用报销部分 ***************************/
	/**
	 * 获取费用项目
	 */
	function getFeeType(){
        $sql="select d.CostTypeName as feeType from cost_type d where d.CostTypeLeve = (select max(CostTypeLeve) from cost_type) order by d.ParentCostTypeID";
        $rows=$this->_db->getArray($sql);
        return $rows;
	}
	/**
	 * 获取费用项目
	 */
	function issetFeeType($feeType){
        $sql="select d.CostTypeName as feeType from cost_type d where d.CostTypeName = '$feeType'";
        $rs=$this->_db->getArray($sql);
        if(!empty($rs[0]['feeType'])){
			return $rs[0]['feeType'];
        }else{
        	return '';
        }
	}
    /**
     * 获取表单类型
     */
    function getBillType(){
        $sql="select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $rows=$this->_db->getArray($sql);
        return $rows;
    }

    /**
     * 获取报销模版 - 如果没传id则获取最新的一条
     */
    function getModelType($modelId = null){
    	if($modelId){
			$sql = "SELECT id as modelType,name as modelTypeName FROM cost_model where id = $modelId" ;
    	}else{
			$sql = "SELECT id as modelType,name as modelTypeName FROM cost_model order by id desc limit 0,1";
    	}
        $rs=$this->_db->getArray($sql);
        if(!empty($rs[0])){
			return $rs[0];
        }else{
        	return array(
        		'modelType' => '',
        		'modelTypeName' => ''
        	);
        }
    }

    /**
     * 根据费用报销项id获取名称
     */
	function initCostType($ids){
		if($ids){
			$sql = "select group_concat(CostTypeName) as CostTypeName from cost_type where CostTypeID in ($ids) " ;
	        $rs=$this->_db->getArray($sql);
	        if(!empty($rs[0])){
				return $rs[0]['CostTypeName'];
	        }else{
				return "";
	        }
		}else{
			return "";
		}
	}

    /**
     * 判断对应部门是否需要填写省份信息
     */
    function deptIsNeedProvince_d($deptId){
        include (WEB_TOR."includes/config.php");
        //部门费用需要省份的部门
        $expenseNeedProvinceDept = isset($expenseNeedProvinceDept) ? $expenseNeedProvinceDept : null;
        //返回key数组
        $keyArr = array_keys($expenseNeedProvinceDept);
        if(in_array($deptId,$keyArr)){
            return 1;
        }else{
            return 0;
        }
    }

    /****************************** 研发项目部分 *******************************/
	/**
	 * 获取研发项目
	 */
	function getRdproject($projectCode){
		$sql = "select id as projectId,name as projectName ,number as projectCode from project_rd where number = '$projectCode'";
		$rows=$this->_db->getArray($sql);
        if(!empty($rows[0])){
			return $rows[0];
        }else{
			return null;
        }
	}

	/**************************** 系统配置 *********************************/
	/**
	 * 获取配置表中的配置值
	 * @param $name
	 * @param null $type
	 * @param null $returnType
	 * @return null
	 */
	function getConfig($name, $type = null, $returnType = null) {
		$sql = "SELECT value FROM config WHERE name='" . $name . "'";

		if ($type) {
			$sql .= " AND type='" . $type . "'";
		}
		$config = $this->_db->get_one($sql);

		if ($returnType != 'arr') {
			return $config ? $config['value'] : null;
		} else {
			return $config ? explode(',', $config['value']) : array();
		}
	}

    /**
     * 修改配置表中的配置项
     * @param $name
     * @param string $type
     * @param $value
     * @return mixed
     */
    function updateConfig($name, $type = '', $value) {
        $sql = "SELECT value FROM config WHERE name='" . $name . "'";

        if ($type) {
            $sql .= " AND type='" . $type . "'";
        }
        $config = $this->_db->get_one($sql);

        if ($config) {
            $sql = "UPDATE config SET value='". $value . "' WHERE name='" . $name . "'";
            if ($type) {
                $sql .= " AND type='" . $type . "'";
            }
        } else {
            $sql = "INSERT INTO config (value,name,type) VALUES ('". $value . "','". $name . "','". $type . "')";
        }
        return $this->_db->query($sql);
    }

    /**
     * 获取当前用户的Sid
     * @return bool
     */
    function getUserSid(){
        $userId = $_SESSION['USER_ID'];
        $ip = $_SESSION['IP'];
        $skey = md5($userId.$ip.day_date);
        // 从aws获取sid
        $result = util_curlUtil::getDataForSignInAWS ( array (
            "cmd"  => "API_BENCHMARK_SSO_CREATESID",
            "uid"  => $userId,
            "ip"   => $ip,
            "skey" => $skey
        ) );
        if(empty($result)){
            msgRf ( "跳转新OA失败，请联系系统管理员！" );
            exit ();
        }
        $data = util_jsonUtil::decode ( $result ['data'], true );
        if(!isset($data['data']['sid'])){
            return false;
        }else{
            return $data['data']['sid'];
        }
    }

    /**
     *  根据文档在新OA的BINDID获取相应的访问连接
     */
    function getDocUrl($bindid = ''){
        $sid = $this->getUserSid();
        $url = substr(SSOURL,0,strlen(SSOURL)-2)."w";
        $docUrl = $url."?sid=".$sid."&cmd=CLIENT_DW_FORM_READONLYPAGE&bindid={$bindid}";
        return $docUrl;
    }

    /** 获取禁用费用归属部门数据*/
    function getDenyFegsdept(){
        $sql = "select ci.belongDeptNames,ci.belongDeptIds FROM oa_system_configurator c JOIN oa_system_configurator_item ci 
WHERE c.id=ci.mainId AND c.id =11";

        return $this->_db->getArray($sql);
    }
}