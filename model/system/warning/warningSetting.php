<?php

/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:21
 * @version 1.0
 * @description:ͨ��Ԥ��������ϸ���� Model��
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