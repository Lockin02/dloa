<?php
/*
 * Created on 2010-10-19
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class controller_rdproject_template_rdtnode extends controller_base_action{
	function __construct() {
		$this->objName = "rdtnode";
		$this->objPath = "rdproject_template";
		parent::__construct ();
	}

	/**
	 * 重写新增模板
	 */
	function c_toAdd() {
		$this->show->assign( 'templateId' ,$_GET['templateId']) ;
		$this->show->assign( 'templateName' ,$_GET['templateName']) ;
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 * 查看计划模板的详细内容
	 */
	function c_readPlanTemplate(){
		$this->show->assign( 'id' ,$_GET['id']) ;
		$this->show->assign( 'templateName' ,$_GET['templateName']) ;
		$this->show->display( $this->objPath.'_'.$this->objName.'-list');
	}

	function c_getParentRowsByTemplateId(){
		$service = $this->service;
		$templateId = $_GET['templateId'];
		$service->searchArr['templateId'] = $templateId;
		$service->searchArr['parentId'] = '-1';
		$service->asc = false;
		$tnodeRows = $service->list_d();
//		print_r($tnodeRows);

		$rdttaskDao = new model_rdproject_template_rdttask();
		$rdttaskDao->searchArr['templateId'] = $templateId;
		$rdttaskDao->searchArr['belongNodeId'] = '-1';
		$rdttaskDao->asc = false;
		$rdttaskRows = $rdttaskDao->list_d();

		function createOIdFn($row) {
			if (isset ( $row ['taskType'] )) {
				$row ['oid'] = "t_" . $row ['id']; //以t-开头为任务
				$row ['oParentId'] = "n_" . $row ['belongNodeId'];
				$row['warnIcon']='<img src="images/ico4.gif">';
			} else {
				$row ['oid'] = "n_" . $row ['id']; //以n-为前缀表明为节点
				$row ['oParentId'] = "n_" . $row ['parentId'];
				$row['warnIcon']='<img src="images/ico4-1.gif">';
				$row['name']=$row['nodeName'];
			}
			return $row;
		}
		if($tnodeRows)
			$tnodeRows = array_map ( "createOIdFn", $tnodeRows );
		if($rdttaskRows)
			$rdttaskRows = array_map ( "createOIdFn", $rdttaskRows );

		$returnTNRows = model_common_util::yx_array_merge ( $tnodeRows, $rdttaskRows);

		$arr = array ();
		$arr ['data'] = $returnTNRows;
		$arr ['total'] = count($returnTNRows);
//		$arr ['page'] = $service->page;

		echo util_jsonUtil::encode ( $arr );
	}

	function c_getRowsByTemplateId(){

		function createOIdFn($row) {
			if (isset ( $row ['taskType'] )) {
				$row ['oid'] = "t_" . $row ['id']; //以t-开头为任务
				$row ['oParentId'] = "n_" . $row ['belongNodeId'];
				$row['warnIcon']='<img src="images/ico4.gif">';
			} else {
				$row ['oid'] = "n_" . $row ['id']; //以n-为前缀表明为节点
				$row ['oParentId'] = "n_" . $row ['parentId'];
				$row['warnIcon']='<img src="images/ico4-1.gif">';
				$row['name']=$row['nodeName'];
			}
			return $row;
		}

		$service = $this->service;
		$templateId = $_GET['templateId'];
		$service->searchArr['templateId'] = $templateId;
		$service->searchArr['parentId'] = $_GET['parentId'];
		$service->asc = false;
		$tnodeRows = $service->list_d();

		$rdttaskDao = new model_rdproject_template_rdttask();
		$rdttaskDao->searchArr['templateId'] = $templateId;
		$rdttaskDao->searchArr['belongNodeId'] = $_GET['parentId'];
		$rdttaskDao->asc = false;
		$rdttaskRows = $rdttaskDao->list_d();

		if($tnodeRows)
			$tnodeRows=array_map("createOIdFn",$tnodeRows);

		if($rdttaskRows)
			$rdttaskRows=array_map("createOIdFn",$rdttaskRows);

		$returnTNRows = model_common_util::yx_array_merge ( $tnodeRows, $rdttaskRows);

		if (is_array ( $returnTNRows )) {
			echo util_jsonUtil::encode ( $returnTNRows );
		} else {
			echo 0;
		}
	}

	/**
	 * 简单修改
	 */
	function c_editSimple(){
		if($this->service->editSimple($_POST[$this->objName])){
			msg('修改成功');
		}
	}

	/**
	 * 简单修改
	 */
	function c_addSimple(){
		if($this->service->addSimple($_POST[$this->objName])){
			msg(' 添加成功');
		}
	}
	/**
	 * 确认删除
	 */
	function c_toDelete(){
		$rdttaskDao = new model_rdproject_template_rdttask();
		if($this->service->hasChildren($_GET['id']) || $rdttaskDao->hasChildren($_GET['id'])){
			msg('请先删除节点下的内容');
		}else{
			showmsg('确定删除？',"location='?model=rdproject_template_rdtnode&action=deleteAction&id=".$_GET['id']."'",'button');
		}
	}

//	/**
//	 * 删除操作
//	 */
//	function c_deleteAction(){
//		if($this->service->deletes($_GET['id'])){
//			msg('删除成功');
//		}else{
//			msg('删除失败');
//		}
//	}

		/*
	 *根据父节点id获取节点树信息
	 */
	function c_getTkNodeTree() {
		$searchArr = array (
			"templateId" => $_GET['templateId'],
			"parentId" => $_GET['parentId']
		);
		$service = $this->service;
		$service->searchArr = $searchArr;
		$rows = $service->listBySqlId("select_leaf");
//		echo "<pre>";
//		print_R( $rows );
		function toBoolean($row) {
			$row['leaf'] = $row['leaf'] == 0 ? true : false;
			return $row;
		}
		echo util_jsonUtil :: encode(array_map("toBoolean", $rows));
	}

}
?>
