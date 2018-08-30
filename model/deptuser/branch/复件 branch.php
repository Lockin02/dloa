<?php
header("Content-type: text/html; charset=gb2312");

/**
 * ��֯����model����
 */
class model_deptuser_branch_branch extends model_base {

	function __construct() {
		$this->tbl_name = "branch_info";
		$this->sql_map = "deptuser/branch/branchSql.php";
		parent::__construct ();
	}

	/**
	 * ���ݹ�˾��д��ȡ��˾��Ϣ
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
	 *������Ա�˺ţ����ҹ�˾��Ϣ
	 *
	 */
	 function getBrachByUserNo($userNo){
	 	$userDao=new model_deptuser_user_user();
	 	$userRow=$userDao->getUserById($userNo);
	 	$branchRow=$this->getByCode($userRow['Company']);
	 	return $branchRow;
	 }

	/**
	 * ����code����ȡ����
	*author zengzx
	*2012��6��29��
	*/
	function getBranchName_d($code){
    	$condition = array("NamePT" => $code);
    	$NameCN = $this->find($condition,'NameCN');
		return $NameCN;
	}

	/**
	 * ���ݹ�˾���ͣ���ȡ��˾����ģ��
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