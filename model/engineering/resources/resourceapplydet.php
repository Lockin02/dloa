<?php
/**
 * @author show
 * @Date 2013年11月15日 16:10:52
 * @version 1.0
 * @description:项目设备申请明细 Model层
 */
class model_engineering_resources_resourceapplydet extends model_base {

	function __construct() {
		$this->tbl_name = "oa_esm_resource_applydetail";
		$this->sql_map = "engineering/resources/resourceapplydetSql.php";
		parent :: __construct();
	}

	/**
	 * 更新设备信息
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