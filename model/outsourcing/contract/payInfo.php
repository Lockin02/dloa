<?php
/**
 * @author Michael
 * @Date 2017��10��23�� ����һ 23:10:23
 * @version 1.0
 * @description:�⳵��ͬ������Ϣ Model��
 */
class model_outsourcing_contract_payInfo  extends model_base {

    function __construct() {
        $this->tbl_name = "oa_contract_rentcar_payinfos";
        $this->sql_map = "outsourcing/contract/rentcarSql.php";
        parent::__construct ();
    }
}