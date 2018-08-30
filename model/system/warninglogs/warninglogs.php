<?php
/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:50
 * @version 1.0
 * @description:预警执行记录 Model层
 */
class model_system_warninglogs_warninglogs extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_system_warning_logs";
        $this->sql_map = "system/warninglogs/warninglogsSql.php";
        parent::__construct();
    }
}