<?php
header("Content-type: text/html; charset=gb2312");
/**
 * ��֯����model����
 */
class model_deptuser_dept_dept extends model_base {

	function __construct() {
		$this->tbl_name = "department";
		$this->sql_map = "deptuser/dept/deptSql.php";
		parent::__construct ();
	}


	/**
	 * �첽������֯����
	 */
	function tree_d($deptName) {
		$result = array ();
		$this->searchArr = array ('DelFlag'=>0 );
		if (! empty ( $deptName )) {
			$this->searchArr ['deptName'] = $deptName;
			$result = $this->list_d ();
		} else {
			$result = $this->list_d ();
		}

		return $result;
	}


	/**
	 * �ݹ��ȡ��֯������������

	function allTree_d($deptName) {
		$result = array ();
		if (! empty ( $deptName )) {
			$this->searchArr = array ('deptName' => $deptName );
			$result = $this->list_d ();
		} else {
			$depts = $this->list_d ();
			$deptsMap = $this->changeDeptsToTree ( $depts );
			foreach ( $deptsMap as $key => $value ) {
				array_push ( $result, $value );
			}
		}

		return $result;
	} */

	/**
	 * ����֯������װ����������
	 * @param  $depts
	 */
	function changeDeptsToTree($depts) {
		$this->sort = 'Dflag';
		$this->asc = false;
		$allDepts_tmp = $this->list_d ();
		foreach ( $allDepts_tmp as $key => $value ) {
			$allDepts [$value ['Depart_x']] = $value;
		}
		$deptsMap = array ();
		foreach ( $depts as $k => $v ) {
			//�����һ���ڵ�
			if ($v ['Dflag'] == 0) {
				$depart_x = $v ['Depart_x'];
				if (empty ( $deptsMap [$depart_x] )) {
					$deptsMap [$depart_x] = $v;
					unset ( $allDepts [$depart_x] );
					$children = $this->searchChildren ( $v, $allDepts );
					if (is_array ( $children ) && count ( $children ) > 0) {
						$deptsMap [$depart_x] ['nodes'] = array_merge ( $children, $deptsMap [$depart_x] ['nodes'] );
					}
				}
			}
		}
		return $deptsMap;
	}

	/**
	 *�ݹ�����ָ�������µ��Ӳ���
	 */
	function searchChildren($dept, $allDepts, $i = 0) {
		$children = array ();
		$i = $i + 1;
		foreach ( $allDepts as $key => $value ) {
			$parentDX = substr ( $value ['Depart_x'], 0, 2 * $i );
			if ($dept ['Depart_x'] == $parentDX && $value ['Dflag'] == $i) {
				unset ( $allDepts [$value ['Depart_x']] );
				$c = $this->searchChildren ( $value, $allDepts, $i );
				if (is_array ( $c ) && count ($c) > 0) {
					$value ['nodes'] = array_merge ( $c, $dept ['nodes'] );
				}else{
					//$value ['nodes'] =$dept ['nodes'];
				}
				//$value ['nodes'] = $this->searchChildren ( $value, $allDepts, $i );
			}
//			else{
//				$value ['nodes'] =$dept ['nodes'];
//			}
			array_push ( $children, $value );
		}
		return $children;
	}

	/**
	 *���ݲ���id��ȡ������Ϣ
	 */
	function getDeptById($deptId){
    	$this->searchArr = array("cid" => $deptId);
    	$list=$this->list_d();
    	return $list[0];
	}

	/**���ݲ���ID����ȡ��������
	*author can
	*2011-8-18
	*edit by linzx
	*/
	function getDeptName_d($deptId){
    	$deptId = array("DEPT_ID" => $deptId);
    	$deptName = $this->find($deptId,'DEPT_NAME');
		return $deptName;
	}

	/**���ݲ���ID����ȡ���ŵȼ�
	*author can
	*2011-8-18
	*edit by linzx
	*/
	function getDeptLevel_d($deptId){
    	$deptId = array("DEPT_ID" => $deptId);
    	$levelflag = $this->find($deptId,'levelflag');
		return $levelflag;
	}


	/**���ݲ������ƣ���ȡ����ID
	*author zengzx
	*2011��10��18�� 16:25:14
	*/
	function getDeptId_d($deptName){
    	$deptName = array("DEPT_NAME" => $deptName,'DelFlag' => 0);
    	$deptId = $this->find($deptName,'DEPT_ID');
		return $deptId;
	}

	/**
	 * �����û�id��ȡ������Ϣ
	 */
		function getDeptByUserId($userId){

		$userDao=new model_deptuser_user_user();
		$user=$userDao->getUserById($userId);

		if(empty($user['DEPT_ID'])){
			throw new Exception($userId."���û�û����������.");
		}

		$dept=$this->getDeptById($user['DEPT_ID']);
		return $dept;
	}
    /**
     * �����û�ID��ȡ������Ϣ��������ְ�ģ�
     */
	function getDeptByUserIdHas($userId){

		$userDao=new model_deptuser_user_user();
		$user=$userDao->find(array("USER_ID"=>$userId));

//		if(empty($user['DEPT_ID'])){
//			throw new Exception($userId."���û�û����������.");
//		}

		$dept=$this->getDeptById($user['DEPT_ID']);
		return $dept;
	}

	/**���ݲ���ID����ȡ���ŵ������ϼ�������Ϣ
	 * @author suxc
	 *
	 */
	 function getSuperiorDeptById_d($deptId,$levelflag=null){
	 	if($levelflag==""||$levelflag==null){
	 		$dept=$this->getDeptLevel_d($deptId);
	 		$levelflag=$dept['levelflag'];
	 	}
	 		$row=array();
	 		//ֱ������
			$row['deptCode']="";
			$row['deptName']="";
			$row['deptId']="";
			//��������
			$row['deptCodeS']="";
			$row['deptNameS']="";
			$row['deptIdS']="";
			//��������
			$row['deptNameT']="";
			$row['deptCodeT']="";
			$row['deptIdT']="";
			if($levelflag==1){//ֱ������
				$deptRow=$this->getDeptById($deptId);
				$row['deptCode']=$deptRow['Depart_x'];
				$row['deptName']=$deptRow['name'];
				$row['deptId']=$deptId;
			}else if($levelflag==2){//��������
				$deptRow=$this->getDeptById($deptId);
				$row['deptCodeS']=$deptRow['Depart_x'];
				$row['deptNameS']=$deptRow['name'];
				$row['deptIdS']=$deptId;
				if($deptRow['PARENT_ID']>0){//��ȡֱ������
					$parentdeptRow=$this->getDeptById($deptRow['PARENT_ID']);
					$row['deptCode']=$parentdeptRow['Depart_x'];
					$row['deptName']=$parentdeptRow['name'];
					$row['deptId']=$deptRow['PARENT_ID'];
				}else{
					$row['deptCode']=$deptRow['Depart_x'];
					$row['deptName']=$deptRow['name'];
					$row['deptId']=$deptId;
				}

			}else if($levelflag==3){//��������
				$deptRow=$this->getDeptById($deptId);
				$row['deptCodeT']=$deptRow['Depart_x'];
				$row['deptNameT']=$deptRow['name'];
				$row['deptIdT']=$deptId;
				if($deptRow['PARENT_ID']>0){//��������
					$parentdeptRow=$this->getDeptById($deptRow['PARENT_ID']);
					$row['deptCodeS']=$parentdeptRow['Depart_x'];
					$row['deptNameS']=$parentdeptRow['name'];
					$row['deptIdS']=$deptRow['PARENT_ID'];
					if($parentdeptRow['PARENT_ID']>0){//��ȡֱ������
						$highDeptRow=$this->getDeptById($parentdeptRow['PARENT_ID']);
						$row['deptCode']=$highDeptRow['Depart_x'];
						$row['deptName']=$highDeptRow['name'];
						$row['deptId']=$parentdeptRow['PARENT_ID'];
					}else{
						$row['deptCode']=$parentdeptRow['Depart_x'];
						$row['deptName']=$parentdeptRow['name'];
						$row['deptId']=$deptRow['PARENT_ID'];
					}
				}
			}
			return $row;
	 }
}
?>