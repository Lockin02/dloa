<?php
/**
 * @author show
 * @Date 2013年12月5日 11:48:32
 * @version 1.0
 * @description:盖章使用事项配置 Model层
 */
class model_system_stamp_stampmatter extends model_base {

	function __construct() {
		$this->tbl_name = "oa_system_stamp_matter";
		$this->sql_map = "system/stamp/stampmatterSql.php";
		parent :: __construct();
	}
	
	private $needAuditStatus = array (
			'否','是'
	);
	
	private $isStatus = array(
			'关闭','开启'
	);
	
	public function getNeedAudit($thisVal){
		return $this->needAuditStatus[$thisVal];
	}
	
	public function getIsStatus($thisVal){
		return $this->isStatus[$thisVal];
	}
}