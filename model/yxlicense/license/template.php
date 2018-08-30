<?php

/**
 * @author Show
 * @Date 2011��10��9�� ������ 14:39:27
 * @version 1.0
 * @description:(new)licenseģ �� Model��
 */
class model_yxlicense_license_template extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_license_template";
        $this->sql_map = "yxlicense/license/templateSql.php";
        parent::__construct();
    }

    /**
     * ��дadd����
     * @param $object
     * @return bool
     */
    function add_d($object) {
        return parent::add_d($object, true);
    }

    /**
     * ��дedit����
     * @param $object
     * @return mixed
     */
    function edit_d($object) {
        return parent::edit_d($object, true);
    }

    /**
     * ��ȡlicense�����������õ�����
     * @return mixed
     */
    function getLicense_d() {
        $licenseDao = new model_yxlicense_license_baseinfo();
        return $licenseDao->getLicense_d();
    }

    /**
     * ��ȡlicense
     * @return mixed
     */
    function getLicenseAll_d() {
        $licenseDao = new model_yxlicense_license_baseinfo();
        return $licenseDao->getLicenseAll_d();
    }

    /**
     * ���ģ���Ƿ�ʹ��
     * @param $licenseType
     * @return bool|mixed
     */
    function checkExists_d($licenseType) {
        return $this->find(array('licenseType' => $licenseType),null,'id');
    }
}