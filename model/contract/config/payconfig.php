<?php
/**
 * @author Show
 * @Date 2013��7��15�� 11:31:24
 * @version 1.0
 * @description:������������ Model��
 */
class model_contract_config_payconfig extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_payconfig";
		$this->sql_map = "contract/config/payconfigSql.php";
		parent :: __construct();
	}

    /**
     * ���ݻؿ��������Ʋ��Ҷ�Ӧid
     */
    function findIdBypayName($configName){
        $arr = $this->find(array("configName"=>$configName),null,"id");
        return $arr['id'];
    }

    /**
     * �Ƿ�
     */
    function rtYesNo_d($thisVal){
        if($thisVal == 1){
            return '��';
        }else{
            return '��';
        }
    }
}
?>