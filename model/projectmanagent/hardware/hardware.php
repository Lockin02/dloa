<?php
/**
 * @author Administrator
 * @Date 2013��5��29�� 10:08:38
 * @version 1.0
 * @description:�̻��豸Ӳ������ Model��
 */
 class model_projectmanagent_hardware_hardware  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_hardware";
		$this->sql_map = "projectmanagent/hardware/hardwareSql.php";
		parent::__construct ();
	}



   /**
    * �Ƿ����豸
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