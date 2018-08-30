<?php

/**
 * ��֯����model����
 */
class model_deptuser_branch_branch extends model_base
{

    function __construct() {
        $this->tbl_name = "branch_info";
        $this->sql_map = "deptuser/branch/branchSql.php";
        parent::__construct();
    }

    /**
     * ���ݹ�˾��д��ȡ��˾��Ϣ
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
     * ������Ա�˺ţ����ҹ�˾��Ϣ
     * @param $userNo
     * @return null
     */
    function getBrachByUserNo($userNo) {
        $userDao = new model_deptuser_user_user();
        $userRow = $userDao->getUserById($userNo);
        return $this->getByCode($userRow['Company']);
    }

    /**
     * ����code����ȡ����
     * @param $code
     * @return bool|mixed
     */
    function getBranchName_d($code) {
        $condition = array("NamePT" => $code);
        return $this->find($condition, 'NameCN');
    }

    /**
     * ���ݹ�˾���ͣ���ȡ��˾����ģ��
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
     * ��ȡ��˾��Ϣ
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