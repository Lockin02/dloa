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

	/**************************** S �û���Ϣ ********************************/

	/**
	 * �����û���Ϣ
	 */
	public function getUserDatas($userId, $find) {
		$rs = $this->_db->getArray ( "
			select a.*,b.*,c.name as jobs_name,d.Name as areaname from user as a
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
	 * �����û�id�����ϼ�����
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
     * �����û����Ʒ����û�ID(���ص����ݰ����������û�)
     */
    function getUserID($userName){
        $sql = "select USER_ID from user where USER_NAME = '$userName' ";
        $userIdArr = $this->_db->getArray($sql);
        return $userIdArr;
    }

    /**
     * �����û������ϼ��쵼��Ϣ
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
	 * ��ȡ�û�����
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
	 * ��ȡ�û��ʺ�
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
	 * ��ȡ�û��˻���Ϣ
	 */
	function getUserInfoByUserNo($userNo){
		if(!empty($userNo)){
            $sql="select u.USER_ID,d.DEPT_NAME,u.DEPT_ID,d.code as DEPT_CODE,h.userCard as userNo,job.id as jobId,job.name as jobName from user u left join hrms h on u.USER_ID = h.USER_ID left join department d on u.DEPT_ID=d.DEPT_ID left join user_jobs job on u.jobs_id=job.id where h.UserCard = '$userNo'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0];
		}
		return null;
	}

	/**
	 *  ���ݲ��Ż�ȡ����id
	 */
	function getDeptId_d($deptName){
	    if(!empty($deptName)){
            $sql="select d.DEPT_ID from department d where d.DEPT_NAME = '$deptName'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0]['DEPT_ID'];
		}
	}

	/**
	 *  ���ݲ��Ż�ȡ������Ϣ
	 */
	function getDeptInfo_d($deptName){
	    if(!empty($deptName)){
            $sql="select d.DEPT_ID,d.Depart_x from department d where d.DEPT_NAME = '$deptName'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0];
		}
	}

	/**
	 *  ����ְλ���ƻ�ȡְλid
	 */
	function getJobId_d($jobName){
	    if(!empty($jobName)){
            $sql="select id from user_jobs d where name = '$jobName'";
	        $rows=$this->_db->getArray($sql);
	        return $rows[0]['id'];
		}
	}

	/**
	 * ��ȡ�û����
	 *
	 * @param $userId �û�ID
	 */
	function getUserCard ($userId) {
		$userSql="select UserCard from hrms where USER_ID='".$userId."'";
		$userCard=mysql_fetch_row($this->query($userSql));
		return $userCard[0];
	}

	/**************************** E �û���Ϣ ********************************/

	/**************************** S ������ ********************************/

	/**
	 * ��֤ǰ�������Ƿ����һ��������true����false
	 */
	public function isLastStep($objId, $objTable) {
		$sql = "select if(max(s.smallId) = max(f.smallid),1,0) as isLastStep from wf_task w left join flow_step_partent f on w.task = f.Wf_task_id left join flow_step s on w.task = s.Wf_task_id where w.Pid = '$objId' and w.code = '$objTable' and f.flag = 0 group by w.task";
		$rs = $this->_db->getArray ( $sql );
		return $rs [0] ['isLastStep'];
	}

	/**
	 * ����spid��ȡ������ҵ����Ϣ �������� w.DBTable
	 */
	public function getWorkflowInfo($spid) {
		$sql = "select w.task as taskId ,w.code as objType , w.pid as objId,w.examines as examines,w.name as formName,w.Enter_user , w.DBTable from flow_step_partent p inner join wf_task w on p.Wf_task_ID = w.task where p.ID = $spid ";
		$rs = $this->_db->getArray ( $sql );

		if (is_array ( $rs ) && count ( $rs ) > 0) {
			return $rs [0];
		} else {
			throw new Exception ( "�޹��������ҵ����Ϣ!" );
			return null;
		}
	}

	/**
	 * ����task��ȡ������Ϣ
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
	 * ��ȡid�������������Ϣ
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

	/**************************** E ������ ********************************/



	/**
	 *  ��ȡģ��Ȩ��
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

		foreach($rs as $key => $val){
			$rows[$val['name']] = $val['content'];
		}
		return $rows;
	}

	/**
	 * ��ȡ��Ŀ�ܱ������
	 */
	function getFeeField($projectCode){
		$sql = "select sum(c.amount) as feeField from cost_summary_list c
			where replace(c.projectno,'-','')=replace('$projectCode','-','') and  c.isproject='1' and c.status <>'���'";

		$rs = $this->_db->getArray($sql);
		if(!empty($rs[0]['feeField'])){
			return $rs[0]['feeField'];
		}else{
			return 0;
		}
	}

}
?>