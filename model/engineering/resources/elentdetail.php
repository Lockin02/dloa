<?php
/**
 * @author show
 * @Date 2013��12��9�� 19:17:33
 * @version 1.0
 * @description:ת���豸��ϸ Model�� 
 */
class model_engineering_resources_elentdetail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_elentdetail";
		$this->sql_map = "engineering/resources/elentdetailSql.php";
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