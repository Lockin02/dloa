<?php
/**
 * @author Administrator
 * @Date 2014��3��17�� 14:22:22
 * @version 1.0
 * @description:Ԥ���ʼ�֪ͨ��� Model��
 */
class model_system_warningmaillogs_warningmaillogs extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_system_warning_mail_logs";
        $this->sql_map = "system/warningmaillogs/warningmaillogsSql.php";
        parent::__construct();
    }
}