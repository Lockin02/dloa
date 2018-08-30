<?php
/**
 * @author show
 * @Date 2013��12��9�� 19:17:51
 * @version 1.0
 * @description:����������ϸ Model�� 
 */
class model_engineering_resources_erenewdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_erenewdetail";
		$this->sql_map = "engineering/resources/erenewdetailSql.php";
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