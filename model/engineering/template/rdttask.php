<?php
/*
 * Created on 2010-10-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_engineering_template_rdttask extends model_base{
	function __construct(){
		$this->tbl_name = "oa_rd_template_task";
		$this->sql_map = "engineering/template/rdttaskSql.php";
		parent::__construct ();
	}

	/**
	 * ����ģ��ID��ȡ����ģ��
	 */
	function getTasks($templateId){
		$this->searchArr['templateId'] = $templateId;
		$this->asc = false;
		return $this->listBySqlId('select_default');
	}

	/**
	 * �ڵ����Ƿ��������
	 */
	function hasChildren($id){
		return $this->find(array('belongNodeId' => $id),null,'id');
	}
}
?>
