<?php
/*
 * @author huangzhifan
 * @Date 2010-9-15
 * @copyright (c) YXKJ Company.
 * @description:��Ŀ����ǰ������model
 *
 */
  class model_rdproject_task_tknode extends model_treeNode {


	function __construct() {
		$this->tbl_name = "oa_rd_task_node";
		$this->sql_map = "rdproject/task/tknodeSql.php";
		$this->treeCondFields = array ('planId' ); //Ĭ�ϸ�����Ŀ���͸���Ŀid�����γɶ����
		$this->pk="id";
		parent::__construct ();
	}

	/* ---------------------------------ҳ��ģ����ʾ����------------------------------------------*/

	/* -----------------------------------ҵ��ӿڵ���-------------------------------------------*/
	/*
	 * ���ݲ�ѯ������Ϣ��ȡ��Ŀ����ڵ���Ϣ
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
	 * ���ݲ�ѯ������Ϣ��ȡ��Ŀ����ڵ���Ϣ����ҳ
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
	 *���ݽڵ�id��ȡ����Ľڵ��������Ϣ
	 */
	function getTNInNode_d($nodeId){
		//$this->searchArr=$searchArr;
		$this->searchArr['parentId']=-1;
		return $this->pageBySqlId("select_gridinfo");
	}

	/*
	 * ���ݽڵ�idɾ���ڵ���Ϣ,����ڵ������������ʾ����ɾ��
	 */
	function deletes( $id){

		$tkDao=new model_rdproject_task_rdtask();
		$tkDao->searchArr=array(
			"belongNodeId"=>$id
		);
		$tkNode=$this->get_d($id);

		$leaf=$tkNode['rgt']-$tkNode['lft'];
		if($tkDao->list_d()||$leaf!=1){
			return  "����ɾ���ڵ������Ϣ!";
		}
		else{
			parent::deletes($id);
			return "ɾ���ɹ�!";
		}

	}

	/*
	 * ���ݼƻ�id��ȡ�ƻ��������е�������ڵ���Ϣ
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
	 * �򵥲���
	 */
	function addSimple($object){
		$object = $this->addCreateInfo ( $object );
		$newId = $this->create ( $object );
		return $newId;
	}
}
?>
