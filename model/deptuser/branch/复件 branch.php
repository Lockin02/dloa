<?php
header("Content-type: text/html; charset=gb2312");

/**
 * 组织机构model层类
 */
class model_deptuser_branch_branch extends model_base {

	function __construct() {
		$this->tbl_name = "branch_info";
		$this->sql_map = "deptuser/branch/branchSql.php";
		parent::__construct ();
	}

	/**
	 * 根据公司缩写获取公司信息
	 */
	function getByCode($code) {
		$this->searchArr = array ('NamePT' => $code );
		$object = $this->list_d ();
		if (is_array ( $object ) && count ( $object ) > 0) {
			return $object [0];
		}
		return null;
	}

	/**
	 *根据人员账号，查找公司信息
	 *
	 */
	 function getBrachByUserNo($userNo){
	 	$userDao=new model_deptuser_user_user();
	 	$userRow=$userDao->getUserById($userNo);
	 	$branchRow=$this->getByCode($userRow['Company']);
	 	return $branchRow;
	 }

	/**
	 * 根据code，获取名称
	*author zengzx
	*2012年6月29日
	*/
	function getBranchName_d($code){
    	$condition = array("NamePT" => $code);
    	$NameCN = $this->find($condition,'NameCN');
		return $NameCN;
	}

	/**
	 * 根据公司类型，获取公司下拉模板
	 *
	 */
	 function getBranchStr_d($type){
		$this->searchArr = array ('type' => $type );
		$branchRow= $this->listBySqlId ();
		if(is_array($branchRow)){
			foreach($branchRow as $key=>$val){
				$selectStr .= "<option value='".$val['NameCN']."'>".$val['NameCN']."</option>";
			}
			return $selectStr;
		}else{
			return "";
		}

	 }

}
?>