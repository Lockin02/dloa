<?php
/**
 * @author Administrator
 * @Date 2013年5月29日 10:08:38
 * @version 1.0
 * @description:商机设备硬件管理 Model层
 */
 class model_projectmanagent_hardware_hardware  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_hardware";
		$this->sql_map = "projectmanagent/hardware/hardwareSql.php";
		parent::__construct ();
	}



   /**
    * 是否开启设备
    */
   function ajaxUseStatus_d($id,$flag){
   	    try {
			$sql = "update oa_sale_hardware set isUse = '".$flag."' where id = '".$id."'";
			$this->_db->query($sql);

			return true;
		} catch (Exception $e) {
			throw $e;
			return false;
		}
   }

 }
?>