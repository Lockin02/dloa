<?php
/**
 * @author Administrator
 * @Date 2011��3��4�� 14:56:13
 * @version 1.0
 * @description:�̻������� Model��
 */
 class model_projectmanagent_chancetracker_chancetracker  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_chance_trackman";
		$this->sql_map = "projectmanagent/chancetracker/chancetrackerSql.php";
		parent::__construct ();
	}


	/**
	 * ������Ŀid��ȡ��Ʒ�б�
	 */
	function getDetail_d($chanceId) {
		$this->searchArr['chanceId'] = $chanceId;
		return $this->list_d();
	}
 }
?>