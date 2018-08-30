<?php
/**
 * 附件分类控制层类
 * @author chengl
 *
 */
class controller_rdproject_uploadfile_uploadfiletype extends controller_base_action {
	function __construct() {
		$this->objName = "uploadfiletype";
		$this->objPath = "rdproject_uploadfile";
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
		$searchArr = array ("parentId" => $_GET ['parentId'], "projectId" => $projectId );
		$service = $this->service;
		$service->searchArr = $searchArr;
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

	/**
	 * 复制模板到项目类型中
	 */
	function c_copyTemplateToType(){
		$projectType=$_POST['projectType'];//复制的项目类型
		$projectId=$_POST['projectId'];
		$tag=$this->service->copyTemplateToType($projectId,$projectType);
		echo $tag;
	}
}
?>
