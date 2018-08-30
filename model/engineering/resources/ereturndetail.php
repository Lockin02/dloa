<?php
class model_engineering_resources_ereturndetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_ereturndetail";
		$this->sql_map = "engineering/resources/ereturndetailSql.php";
		parent::__construct ();
	}

    /**
     * ��ȡת����ϸ
     */
    function getDetail_d($mainId){
        return $this->findAll(array('mainId' => $mainId),'id ASC');
    }

    /**
     * �ӱ����ݸ���
     */
    function updateStatusByMainId_d($mainId,$status = 1){
        return $this->update(array('mainId' => $mainId),array('status' => $status));
    }
}