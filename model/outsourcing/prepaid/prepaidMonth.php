<?php

/**
 * @author Acan
 * @Date 2014��10��30�� 11:03:34
 * @version 1.0
 * @description:���Ԥ��_�·� Model��
 */
class model_outsourcing_prepaid_prepaidMonth extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_outsourcing_nprepaid_item";
        $this->sql_map = "outsourcing/prepaid/prepaidMonthSql.php";
        parent::__construct();
    }
}