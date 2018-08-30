<?php
/**
 * @author Administrator
 * @Date 2013年3月6日 星期三 17:29:11
 * @version 1.0
 * @description:质量标准 Model层
 */
class model_produce_quality_standard  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_standard";
		$this->sql_map = "produce/quality/standardSql.php";
		parent::__construct ();
	}
	/**
	 * 添加对象
	 */
	function add_d($object) {
		if ($isAddInfo) {
			$object = $this->addCreateInfo ( $object );
		}
		$newId = $this->create ( $object );
		$this->updateObjWithFile ( $newId );
		return $newId;
	}

}
?>