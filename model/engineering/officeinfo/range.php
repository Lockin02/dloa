<?php

/**
 * 责任范围Model层类
 * Created on 2010-12-1
 * author can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class model_engineering_officeinfo_range extends model_base
{
    function __construct() {
        $this->tbl_name = "oa_esm_office_range";
        $this->sql_map = "engineering/officeinfo/rangeSql.php";
        parent::__construct();
    }

    //更新主要负责人到范围表中
    function updateEsmManager_d($officeId, $businessBelong, $productLine) {
        $sql = "update
				$this->tbl_name c
					left join
				oa_esm_office_managerinfo p
					on c.proId = p.provinceId
			set
				c.managerName = p.managerName ,c.managerId = p.managerId
			where officeId = '$officeId' and c.businessBelong = '$businessBelong' and p.businessBelong = '$businessBelong' and p.productLine = '$productLine'";
        $this->query($sql);
    }

    //更新负责人到省份中
    function updateEsmManagerPro_d($provinceId, $businessBelong, $productLine) {
        $sql = "update
				$this->tbl_name c
					left join
				oa_esm_office_managerinfo p
					on c.proId = p.provinceId
			set
				c.managerName = p.managerName ,c.managerId = p.managerId
			where p.provinceId = '$provinceId' and c.businessBelong = '$businessBelong' and p.businessBelong = '$businessBelong' and p.productLine = '$productLine'";
        $this->query($sql);
    }

    //获取办事处信息的服务经理信息
    function getManager_d($officeId) {
        $rs = $this->findAll(array('officeId' => $officeId));
        if ($rs) {
            $newManagerNameArr = array();
            $newManagerIdArr = array();
            foreach ($rs as $key => $val) {
                if (empty($val['managerId'])) {
                    continue;
                }
                $managerIdArr = explode(',', $val['managerId']);
                $managerNameArr = explode(',', $val['managerName']);
                if (count($managerIdArr) > 1) {
                    foreach ($managerIdArr as $k => $v) {
                        if (!in_array($v, $newManagerIdArr)) {
                            array_push($newManagerIdArr, $v);
                            array_push($newManagerNameArr, $managerNameArr[$k]);
                        }
                    }
                } else if (count($managerIdArr) == 1) {
                    if (!in_array($val['managerId'], $newManagerIdArr)) {
                        array_push($newManagerIdArr, $val['managerId']);
                        array_push($newManagerNameArr, $val['managerName']);
                    }
                }
            }
            return array('managerId' => implode($newManagerIdArr, ','), 'managerName' => implode($newManagerNameArr, ','));
        } else {
            return array('managerId' => '', 'managerName' => '');
        }
    }

    /**
     * 判断人员是否服务经理、区域经理
     * @param $userId
     * @return mixed
     */
    function userIsManager_d($userId) {
        $sql = "SELECT id FROM $this->tbl_name where find_in_set('$userId',managerId) or FIND_IN_SET('$userId',mainManagerId)";
        return $this->_db->getArray($sql);
    }

    /**
     * 获取范围id
     * @param $provinceId
     * @param $productLine
     * @param $officeId
     * @return mixed
     */
    function getRangeId_d($provinceId, $productLine, $officeId) {
        $rangeInfo = $this->find(array('proId' => $provinceId, 'productLine' => $productLine, 'officeId' => $officeId), null, 'id');
        if ($rangeInfo) {
            return $rangeInfo['id'];
        } else {
            $rangeInfo = $this->find(array('productLine' => $productLine, 'officeId' => $officeId), null, 'id');
            return $rangeInfo['id'];
        }
    }

    /**
     * 获取范围信息
     * @param $provinceId
     * @param $productLine
     * @param $officeId
     * @return mixed
     */
    function getRangeInfo_d($provinceId, $productLine, $officeId) {
        $rangeInfo = $this->find(
            array('proId' => $provinceId, 'productLine' => $productLine, 'officeId' => $officeId)
        );
        if ($rangeInfo) {
            return $rangeInfo;
        } else {
            return $this->find(array('productLine' => $productLine, 'officeId' => $officeId));
        }
    }

    /**
     * @param $provinceId
     * @param $deptId
     * @return mixed
     */
    function getRangeByProvinceAndDept_d($provinceId, $deptId) {
        $esmrangeArr = $this->findAll(array('proId' => $provinceId), null, 'id');
        $rangeLength = count($esmrangeArr);
        if ($rangeLength == 1) {
            return $esmrangeArr[0]['id'];
        } else {
            //三级部门转二级
            $sql = "select r.id
				from
					oa_esm_office_baseinfo c
					left join oa_esm_office_range r on c.id = r.officeId
					left join department d on FIND_IN_SET(d.pdeptid,c.feeDeptId)
                where d.DEPT_ID = '$deptId' and r.proId = '$provinceId'";
            $esmrangeArr = $this->_db->getArray($sql);
            if (count($esmrangeArr) == 1) {
                return $esmrangeArr[0]['id'];
            } else {
                //三级部门转二级
                $sql = "select
						r.id
					from
						oa_esm_office_baseinfo c
							left join
						oa_esm_office_range r on c.id = r.officeId
							left join department d on c.feeDeptId = d.pdeptid
					where d.DEPT_ID = '$deptId'";
                $esmrangeArr = $this->_db->getArray($sql);
                return $esmrangeArr[0]['id'];
            }
        }
    }

    /**
     * 获取省份信息
     * @param null $userId
     * @return string
     */
    function getProvinces_d($userId = null) {
        $userId = empty($userId) ? $_SESSION['USER_ID'] : $userId;
        $this->searchArr = array('findManagerId' => $userId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            $provinceNameArr = array();
            foreach ($rs as $val) {
                array_push($provinceNameArr, $val['proName']);
            }
            return implode($provinceNameArr, ',');
        } else {
            return '';
        }
    }

    /**
     * 获取用户所在的区域权限
     */
    function getOfficeIds_d() {
        $sql = "SELECT c.officeId
            FROM oa_esm_office_range c
            WHERE FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.mainManagerId)
                OR FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.managerId)
                OR FIND_IN_SET('" . $_SESSION['USER_ID'] . "', c.headId)
            GROUP BY officeId";
        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            $ids = array();
            foreach ($data as $v) {
                $ids[] = $v['officeId'];
            }
            return implode(',', $ids);
        } else {
            return "";
        }
    }
}