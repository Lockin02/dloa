<?php

/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:21
 * @version 1.0
 * @description:通用预警功能详细设置 Model层
 */
class model_system_warning_warningSetting extends model_base
{
    function __construct()
    {
        $this->tbl_name = "oa_system_warning_setting";
        $this->sql_map = "system/warning/warningSettingSql.php";
        parent::__construct();
    }
}