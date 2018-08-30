<?php

/**
 * ���η�ΧModel����
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

    //������Ҫ�����˵���Χ����
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

    //���¸����˵�ʡ����
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

    //��ȡ���´���Ϣ�ķ�������Ϣ
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
     * �ж���Ա�Ƿ������������
     * @param $userId
     * @return mixed
     */
    function userIsManager_d($userId) {
        $sql = "SELECT id FROM $this->tbl_name where find_in_set('$userId',managerId) or FIND_IN_SET('$userId',mainManagerId)";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ȡ��Χid
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
     * ��ȡ��Χ��Ϣ
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
            //��������ת����
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
                //��������ת����
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
     * ��ȡʡ����Ϣ
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
     * ��ȡ�û����ڵ�����Ȩ��
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