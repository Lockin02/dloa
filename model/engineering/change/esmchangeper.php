<?php

/**
 * @author Show
 * @Date 2012��12��15�� ������ 15:21:37
 * @version 1.0
 * @description:��Ŀ�������Ԥ�� Model��
 */
class model_engineering_change_esmchangeper extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_change_person";
		$this->sql_map = "engineering/change/esmchangeperSql.php";
		parent :: __construct();
	}

	/**
	 * ��ȡ�������ķ�����Ϣ
	 */
	function getChangePerson_d($changeactId){
		$rs = $this->findAll(array('activityId' => $changeactId));
		return $rs;
	}
}
?>