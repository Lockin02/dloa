<?php

/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:21
 * @version 1.0
 * @description:ͨ��Ԥ��������ϸ���� Model��
 */
class model_system_warning_warningObject extends model_base
{
    function __construct()
    {
        $this->tbl_name = "oa_system_warning_object";
        $this->sql_map = "system/warning/warningObjectSql.php";
        parent::__construct();
    }

    /**
     * ��Ӷ���
     */
    function add_d($object)
    {
        $object['objCode'] = mysql_real_escape_string($object['objCode']);
        return parent::add_d($object);
    }

    /**
     * ��Ӷ���
     */
    function edit_d($object)
    {
        $object['objCode'] = mysql_real_escape_string($object['objCode']);
        return parent::edit_d($object);
    }

    /**
     * ��ȡ��Ӧ������
     */
    function getHash_d() {
        // ���ؽ��
        $result = array();
        $list = $this->findAll();
        if ($list) {
            foreach ($list as $v) {
                $result[$v['id']] = stripslashes($v['objCode']);
            }
        }
        return $result;
    }
}