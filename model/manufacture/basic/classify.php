<?php
/**
 * @author Michael
 * @Date 2014年7月25日 15:13:03
 * @version 1.0
 * @description:基础信息-工序 Model层
 */
 class model_manufacture_basic_classify  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_manufacture_classify";
		$this->sql_map = "manufacture/basic/classifySql.php";
		parent::__construct ();
	}

	function get_parent( $id = ''){
		$countsql = empty($id)?'' : ' AND `id` = '.$id;
		$data = $this->_db->getArray ("select * from " . $this->tbl_name . " WHERE 1 AND 1 " . $countsql);

		return $data;
	}

	function add_d( $object ) {
		try {
			$this->start_d();
			$id = parent::add_d($object);

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	function edit_d( $object ) {
		try {

			$this->start_d();

			$id = parent::edit_d($object ,true);

			$this->commit_d();
			return $id;

		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

}
?>