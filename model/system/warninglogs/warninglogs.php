<?php
/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:50
 * @version 1.0
 * @description:Ԥ��ִ�м�¼ Model��
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