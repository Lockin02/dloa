<?php

/**
 * 组织机构model层类
 */
class model_deptuser_branch_branch extends model_base
{

    function __construct() {
        $this->tbl_name = "branch_info";
        $this->sql_map = "deptuser/branch/branchSql.php";
        parent::__construct();
    }

    /**
     * 根据公司缩写获取公司信息
     * @param $code
     * @return null
     */
    function getByCode($code) {
        $this->searchArr = array('NamePT' => $code);
        $object = $this->list_d();
        if (is_array($object) && count($object) > 0) {
            return $object [0];
        }
        return null;
    }

    /**
     * 根据人员账号，查找公司信息
     * @param $userNo
     * @return null
     */
    function getBrachByUserNo($userNo) {
        $userDao = new model_deptuser_user_user();
        $userRow = $userDao->getUserById($userNo);
        return $this->getByCode($userRow['Company']);
    }

    /**
     * 根据code，获取名称
     * @param $code
     * @return bool|mixed
     */
    function getBranchName_d($code) {
        $condition = array("NamePT" => $code);
        return $this->find($condition, 'NameCN');
    }

    /**
     * 根据公司类型，获取公司下拉模板
     * @param $type
     * @return string
     */
    function getBranchStr_d($type) {
        $this->searchArr = array('type' => $type);
        $branchRow = $this->listBySqlId();
        if (is_array($branchRow)) {
            $selectStr = '';
            foreach ($branchRow as $key => $val) {
                $selectStr .= "<option value='" . $val['NameCN'] . "'>" . $val['NameCN'] . "</option>";
            }
            return $selectStr;
        } else {
            return "";
        }
    }

    /**
     * 获取公司信息
     */
    function getCompany_d() {
        $list = $this->findAll(null, null, 'NameCN,NamePT');
        $companyArr = array();
        foreach ($list as $v) {
            $companyArr[$v['NamePT']] = $v['NameCN'];
        }
        return $companyArr;
    }
}