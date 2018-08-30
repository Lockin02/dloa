<?php

class model_yxlicense_license_categoryoptions extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_license_category_options";
        $this->sql_map = "yxlicense/license/categoryoptionsSql.php";
        parent::__construct();
    }

    /**
     * 获取主表对应的数据
     * @param $titleId
     * @return array
     */
    function getCategoryItemInfo_d($titleId) {
        return $this->findAll(array('title' => $titleId));
    }

    /**
     * 获取条数
     * @param $titleId
     * @return array
     */
    function getCategoryItemCount_d($titleId) {
        $sql = "SELECT COUNT(titleId) AS num FROM " . $this->tbl_name . " WHERE titleId = ".$titleId;
        return $this->_db->getArray($sql);
    }

    /**
     * 获取options表对应数据
     * @param $formId
     * @return array
     */
    function getOptionsName_d($formId) {
        return $this->findAll(array('formId' => $formId),'id ASC');
    }

    /**
     * 获取options表对应ID数据数目
     * @param $id
     * @return int
     */
    function getOptionsNum_d($id) {
        $sql = "SELECT COUNT(id) AS num FROM " . $this->tbl_name . " WHERE formId = " . $id;
        $rs = $this->_db->getArray($sql);
        return $rs[0]['num'] ? $rs[0]['num'] : 0;
    }
}