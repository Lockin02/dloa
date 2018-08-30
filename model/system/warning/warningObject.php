<?php

/**
 * @author Administrator
 * @Date 2014年3月17日 14:21:21
 * @version 1.0
 * @description:通用预警功能详细设置 Model层
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
     * 添加对象
     */
    function add_d($object)
    {
        $object['objCode'] = mysql_real_escape_string($object['objCode']);
        return parent::add_d($object);
    }

    /**
     * 添加对象
     */
    function edit_d($object)
    {
        $object['objCode'] = mysql_real_escape_string($object['objCode']);
        return parent::edit_d($object);
    }

    /**
     * 获取对应的配置
     */
    function getHash_d() {
        // 返回结果
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