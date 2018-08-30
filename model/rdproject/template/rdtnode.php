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
		$this->treeCondFields = array ('templateId' ); //默认根据项目类型跟项目id分组形成多颗树
		parent::__construct ();
	}


	/**
	 * 根据模板ID获取数组
	 */
	function getNodes($templateId){
		$this->searchArr['templateId'] = $templateId;
		$this->asc = false;
		return $this->listBySqlId('select_default');
	}

	/**
	 * 简单插入方法
	 */
	function addSimple($object){
		$newId = $this->create ( $object );
		return $newId;
	}

	/**
	 * 简单修改
	 */
	function editSimple($object){
		return $this->updateById ( $object );
	}

	/**
	 * 判断节点是否有子节点
	 */
	function hasChildren($id){
		return $this->find(array('parentId' => $id ) ,null,'id');
	}
}
?>
