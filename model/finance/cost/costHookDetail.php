<?php
/**
 * @author show
 * @Date 2014年10月15日 14:15:01
 * @version 1.0
 * @description:费用分摊核销明细 Model层
 */
class model_finance_cost_costHookDetail extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_cost_hook_detail";
        $this->sql_map = "finance/cost/costHookDetailSql.php";
        parent::__construct();
    }
}