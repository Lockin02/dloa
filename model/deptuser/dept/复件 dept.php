<?php
header("Content-type: text/html; charset=gb2312");
/**
 * 组织机构model层类
 */
class model_deptuser_dept_dept extends model_base {

	function __construct() {
		$this->tbl_name = "department";
		$this->sql_map = "deptuser/dept/deptSql.php";
		parent::__construct ();
	}


	/**
	 * 异步加载组织机构
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
	 * 递归获取组织机构所有数据

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
	 * 把组织机构组装成树形数组
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
			//如果是一级节点
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
	 *递归搜索指定部门下的子部门
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
	 *根据部门id获取部门信息
	 */
	function getDeptById($deptId){
    	$this->searchArr = array("cid" => $deptId);
    	$list=$this->list_d();
    	return $list[0];
	}

	/**根据部门ID，获取部门名称
	*author can
	*2011-8-18
	*edit by linzx
	*/
	function getDeptName_d($deptId){
    	$deptId = array("DEPT_ID" => $deptId);
    	$deptName = $this->find($deptId,'DEPT_NAME');
		return $deptName;
	}

	/**根据部门ID，获取部门等级
	*author can
	*2011-8-18
	*edit by linzx
	*/
	function getDeptLevel_d($deptId){
    	$deptId = array("DEPT_ID" => $deptId);
    	$levelflag = $this->find($deptId,'levelflag');
		return $levelflag;
	}


	/**根据部门名称，获取部门ID
	*author zengzx
	*2011年10月18日 16:25:14
	*/
	function getDeptId_d($deptName){
    	$deptName = array("DEPT_NAME" => $deptName,'DelFlag' => 0);
    	$deptId = $this->find($deptName,'DEPT_ID');
		return $deptId;
	}

	/**
	 * 根据用户id获取部门信息
	 */
		function getDeptByUserId($userId){

		$userDao=new model_deptuser_user_user();
		$user=$userDao->getUserById($userId);

		if(empty($user['DEPT_ID'])){
			throw new Exception($userId."该用户没有所属部门.");
		}

		$dept=$this->getDeptById($user['DEPT_ID']);
		return $dept;
	}
    /**
     * 根据用户ID获取部门信息（包括离职的）
     */
	function getDeptByUserIdHas($userId){

		$userDao=new model_deptuser_user_user();
		$user=$userDao->find(array("USER_ID"=>$userId));

//		if(empty($user['DEPT_ID'])){
//			throw new Exception($userId."该用户没有所属部门.");
//		}

		$dept=$this->getDeptById($user['DEPT_ID']);
		return $dept;
	}

	/**根据部门ID，获取部门的所有上级部门信息
	 * @author suxc
	 *
	 */
	 function getSuperiorDeptById_d($deptId,$levelflag=null){
	 	if($levelflag==""||$levelflag==null){
	 		$dept=$this->getDeptLevel_d($deptId);
	 		$levelflag=$dept['levelflag'];
	 	}
	 		$row=array();
	 		//直属部门
			$row['deptCode']="";
			$row['deptName']="";
			$row['deptId']="";
			//二级部门
			$row['deptCodeS']="";
			$row['deptNameS']="";
			$row['deptIdS']="";
			//三级部门
			$row['deptNameT']="";
			$row['deptCodeT']="";
			$row['deptIdT']="";
			if($levelflag==1){//直属部门
				$deptRow=$this->getDeptById($deptId);
				$row['deptCode']=$deptRow['Depart_x'];
				$row['deptName']=$deptRow['name'];
				$row['deptId']=$deptId;
			}else if($levelflag==2){//二级部门
				$deptRow=$this->getDeptById($deptId);
				$row['deptCodeS']=$deptRow['Depart_x'];
				$row['deptNameS']=$deptRow['name'];
				$row['deptIdS']=$deptId;
				if($deptRow['PARENT_ID']>0){//获取直属部门
					$parentdeptRow=$this->getDeptById($deptRow['PARENT_ID']);
					$row['deptCode']=$parentdeptRow['Depart_x'];
					$row['deptName']=$parentdeptRow['name'];
					$row['deptId']=$deptRow['PARENT_ID'];
				}else{
					$row['deptCode']=$deptRow['Depart_x'];
					$row['deptName']=$deptRow['name'];
					$row['deptId']=$deptId;
				}

			}else if($levelflag==3){//三级部门
				$deptRow=$this->getDeptById($deptId);
				$row['deptCodeT']=$deptRow['Depart_x'];
				$row['deptNameT']=$deptRow['name'];
				$row['deptIdT']=$deptId;
				if($deptRow['PARENT_ID']>0){//二级部门
					$parentdeptRow=$this->getDeptById($deptRow['PARENT_ID']);
					$row['deptCodeS']=$parentdeptRow['Depart_x'];
					$row['deptNameS']=$parentdeptRow['name'];
					$row['deptIdS']=$deptRow['PARENT_ID'];
					if($parentdeptRow['PARENT_ID']>0){//获取直属部门
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