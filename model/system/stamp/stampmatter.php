<?php
/**
 * @author show
 * @Date 2013��12��5�� 11:48:32
 * @version 1.0
 * @description:����ʹ���������� Model��
 */
class model_system_stamp_stampmatter extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_stamp_matter";
		$this->sql_map = "system/stamp/stampmatterSql.php";
		parent :: __construct();
	}
	
	private $needAuditStatus = array (
			'��','��'
	);
	
	private $isStatus = array(
			'�ر�','����'
	);
	
	public function getNeedAudit($thisVal){
		return $this->needAuditStatus[$thisVal];
	}
	
	public function getIsStatus($thisVal){
		return $this->isStatus[$thisVal];
	}
}