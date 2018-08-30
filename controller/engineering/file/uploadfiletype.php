<?php
/**
 * 附件分类控制层类
 * @author chengl
 *
 */
class controller_engineering_file_uploadfiletype extends controller_base_action {
	function __construct() {
		$this->objName = "uploadfiletype";
		$this->objPath = "engineering_file";
		parent::__construct ();
	}

	/**
	 * 类型树
	 */
	function c_tree() {
		$projectId = $_GET ['projectId'];
		if (!empty ( $_POST ['projectId'] )) {
			$projectId = $_POST ['projectId'];
		}
		$parentId=$_POST ['id'];
		if(empty($parentId)){
			$parentId=PARENT_ID;
		}
		$searchArr = array ("parentId" => $parentId, "projectId" => $projectId );
		$service = $this->service;
		$service->searchArr = $searchArr;
		$service->asc=false;
		$rows = $service->list_d ();
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 保存类型
	 */
	function c_saveType() {
		$type = $_POST ['type'];
		$type ['name'] = util_jsonUtil::iconvUTF2GB ( $type ['name'] );
		$id = $this->service->saveType_d ( $type );
		echo $id;
	}
}
?>
