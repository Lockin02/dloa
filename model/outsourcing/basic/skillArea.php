<?php
/**
 * @author Administrator
 * @Date 2013��10��24�� ������ 10:06:46
 * @version 1.0
 * @description:��Ӧ�̼������� Model��
 */
 class model_outsourcing_basic_skillArea  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_skillarea";
		$this->sql_map = "outsourcing/basic/skillAreaSql.php";
		parent::__construct ();
	}

	function add_d($object) {
		try {
			$id=parent::add_d ( $object,true );
			return $id;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
 }
?>