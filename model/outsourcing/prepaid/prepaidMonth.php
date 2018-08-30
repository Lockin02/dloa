<?php

/**
 * @author Acan
 * @Date 2014年10月30日 11:03:34
 * @version 1.0
 * @description:外包预提_月份 Model层
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