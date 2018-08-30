<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:项目任务前置任务model
 *
 */
  class model_rdproject_task_tknode extends model_treeNode {


	function __construct() {
		$this->tbl_name = "oa_rd_task_node";
		$this->sql_map = "rdproject/task/tknodeSql.php";
		$this->treeCondFields = array ('planId' ); //默认根据项目类型跟项目id分组形成多颗树
		$this->pk="id";
		parent::__construct ();
	}

	/* ---------------------------------页面模板显示调用------------------------------------------*/

	/* -----------------------------------业务接口调用-------------------------------------------*/
	/*
	 * 根据查询条件信息获取项目任务节点信息
	 */
	function getAllTkNode_d($planId){
		$this->searchArr=array(
				"parentId"=>0,
				"planId"=>$planId,
				"parentId"=>-1
		);
		$this->asc = false;
		return $this->pageBySqlId ("select_planTask");
		//return parent::echoSelect();
	}
	/**
	 * 根据查询条件信息获取项目任务节点信息不分页
	 */
	function getAllTkNodeList_d($planId){
		$this->searchArr=array(
				"parentId"=>0,
				"planId"=>$planId,
				"parentId"=>-1
		);
		$this->asc = false;
		return $this->listBySqlId ("select_planTask");
		//return parent::echoSelect();
	}
	/*
	 *根据节点id获取下面的节点和任务信息
	 */
	function getTNInNode_d($nodeId){
		//$this->searchArr=$searchArr;
		$this->searchArr['parentId']=-1;
		return $this->pageBySqlId("select_gridinfo");
	}

	/*
	 * 根据节点id删除节点信息,如果节点底下有任务提示不能删除
	 */
	function deletes( $id){

		$tkDao=new model_rdproject_task_rdtask();
		$tkDao->searchArr=array(
			"belongNodeId"=>$id
		);
		$tkNode=$this->get_d($id);

		$leaf=$tkNode['rgt']-$tkNode['lft'];
		if($tkDao->list_d()||$leaf!=1){
			return  "请先删除节点底下信息!";
		}
		else{
			parent::deletes($id);
			return "删除成功!";
		}

	}

	/*
	 * 根据计划id获取计划底下所有的任务与节点信息
	 */
	function getNodeTkInPlan_d($planId){
			$searchArr=array(
				"planId"=>$planId
			);
			$tkDao=new model_rdproject_task_rdtask();
			$tkDao->searchArr=$searchArr;
			$tkDao->asc=false;
			$tkRows=$tkDao->listBySqlId("select_gridinfo");

			$this->searchArr=$searchArr;
			$this->asc=false;
			$nodeRows=$this->listBySqlId();

		return model_common_util::yx_array_merge ( $nodeRows, $tkRows);
	}

	/**
	 * 简单插入
	 */
	function addSimple($object){
		$object = $this->addCreateInfo ( $object );
		$newId = $this->create ( $object );
		return $newId;
	}
}
?>
