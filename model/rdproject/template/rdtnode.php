<?php
/*
 * Created on 2010-10-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_rdproject_template_rdtnode extends model_treeNode{
	function __construct(){
		$this->tbl_name = "oa_rd_template_taskNode";
		$this->sql_map = "rdproject/template/rdtnodeSql.php";
		$this->treeCondFields = array ('templateId' ); //Ĭ�ϸ�����Ŀ���͸���Ŀid�����γɶ����
		parent::__construct ();
	}


	/**
	 * ����ģ��ID��ȡ����
	 */
	function getNodes($templateId){
		$this->searchArr['templateId'] = $templateId;
		$this->asc = false;
		return $this->listBySqlId('select_default');
	}

	/**
	 * �򵥲��뷽��
	 */
	function addSimple($object){
		$newId = $this->create ( $object );
		return $newId;
	}

	/**
	 * ���޸�
	 */
	function editSimple($object){
		return $this->updateById ( $object );
	}

	/**
	 * �жϽڵ��Ƿ����ӽڵ�
	 */
	function hasChildren($id){
		return $this->find(array('parentId' => $id ) ,null,'id');
	}
}
?>
