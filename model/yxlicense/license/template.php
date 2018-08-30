<?php

/**
 * @author Show
 * @Date 2011年10月9日 星期日 14:39:27
 * @version 1.0
 * @description:(new)license模 板 Model层
 */
class model_yxlicense_license_template extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_license_template";
        $this->sql_map = "yxlicense/license/templateSql.php";
        parent::__construct();
    }

    /**
     * 重写add方法
     * @param $object
     * @return bool
     */
    function add_d($object) {
        return parent::add_d($object, true);
    }

    /**
     * 重写edit方法
     * @param $object
     * @return mixed
     */
    function edit_d($object) {
        return parent::edit_d($object, true);
    }

    /**
     * 获取license管理中已启用的名称
     * @return mixed
     */
    function getLicense_d() {
        $licenseDao = new model_yxlicense_license_baseinfo();
        return $licenseDao->getLicense_d();
    }

    /**
     * 获取license
     * @return mixed
     */
    function getLicenseAll_d() {
        $licenseDao = new model_yxlicense_license_baseinfo();
        return $licenseDao->getLicenseAll_d();
    }

    /**
     * 检测模板是否被使用
     * @param $licenseType
     * @return bool|mixed
     */
    function checkExists_d($licenseType) {
        return $this->find(array('licenseType' => $licenseType),null,'id');
    }
}