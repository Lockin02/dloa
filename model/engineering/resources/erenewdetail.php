<?php
/**
 * @author show
 * @Date 2013年12月9日 19:17:51
 * @version 1.0
 * @description:续借申请明细 Model层 
 */
class model_engineering_resources_erenewdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_erenewdetail";
		$this->sql_map = "engineering/resources/erenewdetailSql.php";
		parent::__construct ();
	}

    /**
     * 获取转借明细
     */
    function getDetail_d($mainId){
        return $this->findAll(array('mainId' => $mainId),'id ASC');
    }

    /**
     * 从表数据更新
     */
    function updateStatusByMainId_d($mainId,$status = 1){
        return $this->update(array('mainId' => $mainId),array('status' => $status));
    }
}