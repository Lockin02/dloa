<?php
/**
 * @author show
 * @Date 2014��10��15�� 14:15:01
 * @version 1.0
 * @description:���÷�̯������ϸ Model��
 */
class model_finance_cost_costHookDetail extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_cost_hook_detail";
        $this->sql_map = "finance/cost/costHookDetailSql.php";
        parent::__construct();
    }
}