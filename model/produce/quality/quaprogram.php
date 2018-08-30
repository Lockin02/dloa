<?php

/**
 * @author Show
 * @Date 2013��5��20�� ����һ 11:55:49
 * @version 1.0
 * @description:�ʼ췽�� Model��
 */
class model_produce_quality_quaprogram extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_program";
		$this->sql_map = "produce/quality/quaprogramSql.php";
		parent :: __construct();
	}

	/**
	 * �����Ƿ�
	 */
	function rtYesOrNo($thisVal){
		if($thisVal == 1){
			return '��';
		}else{
			return '��';
		}
	}
	/*--------------------------------------------ҵ�����--------------------------------------------*/

	/**
	 * ��������
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
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * �޸ı���
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
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ͨ��id��ȡ��ϸ��Ϣ
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
			$object['fileImage']='<a href="?model=file_uploadfile_management&action=toDownFileById&fileId='.$files[0]['id'].'" taget="_blank" title="�������"><img src="images/icon/icon103.gif" /></a>';
		else 
			$object['fileImage']='';
		return $object;

	}
}
?>