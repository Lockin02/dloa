<?php
/**
 * @author Show
 * @Date 2013年7月15日 11:31:24
 * @version 1.0
 * @description:付款条件设置 Model层
 */
class model_contract_config_payconfig extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_payconfig";
		$this->sql_map = "contract/config/payconfigSql.php";
		parent :: __construct();
	}

    /**
     * 根据回款条款名称查找对应id
     */
    function findIdBypayName($configName){
        $arr = $this->find(array("configName"=>$configName),null,"id");
        return $arr['id'];
    }

    /**
     * 是否
     */
    function rtYesNo_d($thisVal){
        if($thisVal == 1){
            return '是';
        }else{
            return '否';
        }
    }
}
?>