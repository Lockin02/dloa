<?php
/**
 * @author Administrator
 * @Date 2013年10月22日 星期二 14:51:20
 * @version 1.0
 * @description:供应商资质证书 Model层
 */
 class model_outsourcing_supplier_certify  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_certify";
		$this->sql_map = "outsourcing/supplier/certifySql.php";
		parent::__construct ();
	}

	/**新增供应商資質證書信息*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['typeName'] = $datadictDao->getDataNameByCode($object['typeCode']);

			//新增主表信息
			$id = parent :: add_d($object, true);

			//更新附件关联关系
			$this->updateObjWithFile ( $id);

			//附件处理
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**编辑供应商資質證書信息*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['typeName'] = $datadictDao->getDataNameByCode($object['typeCode']);

			//新增主表信息
			$id = parent :: edit_d($object, true);
			//更新附件关联关系
			$this->updateObjWithFile($object['id']);

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}
 }
?>