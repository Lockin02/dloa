<?php
/**
 * @author Administrator
 * @Date 2012��8��25�� ������ 10:54:13
 * @version 1.0
 * @description:���� Model��
 */
 class model_hr_hols_hols  extends model_base {

	function __construct() {
		$this->tbl_name = "hols";
		$this->sql_map = "hr/hols/holsSql.php";
		parent::__construct ();
	}
	/*
	 * ͨ��UserId��ȡԱ����Ϣ
	 */
	 function getPersonnelByUserId($UserId){

		$sql = "select b.userAccount,b.userName,b.companyName,b.deptNameT,b.deptNameS,b.userNo from oa_hr_personnel b where userAccount='$UserId'";
		$personnelInfo = $this->_db->getArray($sql);
		return $personnelInfo[0];
	 }
 }
?>