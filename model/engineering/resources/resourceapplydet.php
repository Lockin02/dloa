<?php
/**
 * @author show
 * @Date 2013��11��15�� 16:10:52
 * @version 1.0
 * @description:��Ŀ�豸������ϸ Model��
 */
class model_engineering_resources_resourceapplydet extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_applydetail";
		$this->sql_map = "engineering/resources/resourceapplydetSql.php";
		parent :: __construct();
	}

	/**
	 * �����豸��Ϣ
	 */
	function updateBatch($datas){
		try{
			foreach($datas as $v){
				$sql = "update $this->tbl_name set exeNumber = exeNumber + {$v['thisExeNum']},feeBack = '{$v['feeBack']}' where id = {$v['id']}";
				$this->_db->query($sql);
			}
			return true;
		}catch(Excetion $e){
			throw $e;
		}
	}
}