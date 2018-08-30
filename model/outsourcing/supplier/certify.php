<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:51:20
 * @version 1.0
 * @description:��Ӧ������֤�� Model��
 */
 class model_outsourcing_supplier_certify  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_certify";
		$this->sql_map = "outsourcing/supplier/certifySql.php";
		parent::__construct ();
	}

	/**������Ӧ���Y�|�C����Ϣ*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['typeName'] = $datadictDao->getDataNameByCode($object['typeCode']);

			//����������Ϣ
			$id = parent :: add_d($object, true);

			//���¸���������ϵ
			$this->updateObjWithFile ( $id);

			//��������
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

	/**�༭��Ӧ���Y�|�C����Ϣ*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['typeName'] = $datadictDao->getDataNameByCode($object['typeCode']);

			//����������Ϣ
			$id = parent :: edit_d($object, true);
			//���¸���������ϵ
			$this->updateObjWithFile($object['id']);

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}
 }
?>