<?php

/**
 * @author Show
 * @Date 2013年5月20日 星期一 11:55:49
 * @version 1.0
 * @description:质检方案 Model层
 */
class model_produce_quality_quaprogram extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_program";
		$this->sql_map = "produce/quality/quaprogramSql.php";
		parent :: __construct();
	}

	/**
	 * 返回是否
	 */
	function rtYesOrNo($thisVal){
		if($thisVal == 1){
			return '是';
		}else{
			return '否';
		}
	}
	/*--------------------------------------------业务操作--------------------------------------------*/

	/**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if (is_array($object['items'])) {
				$id = parent :: add_d($object, true);

				$programitemDao = new model_produce_quality_quaprogramitem();
				$itemsArr = util_arrayUtil :: setItemMainId("mainId", $id, $object['items']);
				$itemsObj = $programitemDao->saveDelBatch($itemsArr);

				$this->commit_d();
				return $id;
			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			if (is_array($object['items'])) {
				$editResult = parent :: edit_d($object, true);

				$programitemDao = new model_produce_quality_quaprogramitem();
				$itemsArr = util_arrayUtil :: setItemMainId("mainId", $object['id'], $object['items']);
				$itemsObj = $programitemDao->saveDelBatch($itemsArr);

				$this->commit_d();
				return $editResult;
			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent :: get_d($id);
		$programitemDao = new model_produce_quality_quaprogramitem();
		$programitemDao->searchArr['mainId'] = $id;
		$object['items'] = $programitemDao->listBySqlId();
		//add chenrf
		$standardModel=new model_produce_quality_standard();
		$uploadFile = new model_file_uploadfile_management ();
		$files = $uploadFile->getFilesByObjId ( $object['standardId'], $standardModel->tbl_name );
		if(is_array($files)&&!empty($files))
			$object['fileImage']='<a href="?model=file_uploadfile_management&action=toDownFileById&fileId='.$files[0]['id'].'" taget="_blank" title="点击下载"><img src="images/icon/icon103.gif" /></a>';
		else 
			$object['fileImage']='';
		return $object;

	}
}
?>