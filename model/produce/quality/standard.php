<?php
/**
 * @author Administrator
 * @Date 2013��3��6�� ������ 17:29:11
 * @version 1.0
 * @description:������׼ Model��
 */
class model_produce_quality_standard  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_standard";
		$this->sql_map = "produce/quality/standardSql.php";
		parent::__construct ();
	}
	/**
	 * ��Ӷ���
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