<?php

/**
 * @author Administrator
 * @Date 2011��7��22�� 9:41:21
 * @version 1.0
 * @description:oa_system_region Model�� ����---�����˱�
 */
class model_system_region_region extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_system_region";
		$this->sql_map = "system/region/regionSql.php";
		parent::__construct();
	}

	/**
	 * @param $areaName
	 * @return mixed
	 */
	function region($areaName) {
		$sql = "select  id  from oa_system_region  where areaName = \"$areaName\"";
		return $this->_db->getArray($sql);
	}

	/**
	 * �����û����Ʒ����û���������
	 * �����û���
	 * ����1ֱ�ӷ������飬���򷵻�����Id��
	 * @param $userId
	 * @param int $type
	 * @return null|string
	 */
	function getUserAreaId($userId, $type = 1) {
		//		$this->searchArr['areaPrincipalId'] = $userId;
		$this->searchArr['isStart'] = '0';//ֻ��ȡ����״̬������
		$this->searchArr['areaSales'] = $userId;
		$rs = $this->list_d();
		if ($type == 1) {
			return $rs;
		} else {
			$ids = null;
            if($rs){
                foreach ($rs as $key => $val) {
                    if ($key) {
                        $ids .= ',' . $val['id'];
                    } else {
                        $ids = $val['id'];
                    }
                }
            }
			return $ids;
		}
	}

	/**
	 * �����û����Ʒ����û���������
	 * �����û���
	 * ����1ֱ�ӷ������飬���򷵻�����Id��
	 * @param $userId
	 * @param int $type
	 * @return string
	 */
	function getUserAreaName($userId, $type = 1) {
		$this->searchArr['isStart'] = '0';//ֻ��ȡ����״̬������
		$this->searchArr['areaSales'] = $userId;
		$rs = $this->list_d();
		if ($type == 1) {
			return $rs;
		} else {
			$areaNameArr = array();
			foreach ($rs as $key => $val) {
				array_push($areaNameArr, $val['areaName']);
			}
			return implode(',', $areaNameArr);
		}
	}

	/**
	 * ��������id  ��ȡ ��չ����ֵ
	 */
	function getExpandbyId($id) {
		$sql = "select expand from oa_system_region where id = '" . $id . "'";
		$arr = $this->_db->getArray($sql);
		return $arr[0]['expand'];
	}

	/**
	 * ��������id ��ȡ �ʼ�������
	 */
	function getTomailId($id) {
		$sql = "select tomailId from oa_system_region where id = '" . $id . "'";
		$arr = $this->_db->getArray($sql);
		return $arr[0]['tomailId'];
	}

    /**
     * ��������id ��ȡ ��������
     */
    function getAreaPrincipalId($id) {
        $sql = "select areaPrincipalId from oa_system_region where id = '" . $id . "'";
        $arr = $this->_db->getArray($sql);
        return $arr[0]['areaPrincipalId'];
    }

	/**
	 * �Ƿ���������
	 * @param $userId
	 * @return bool|mixed
	 */
	function isAreaPrincipal_d($userId) {
		return $this->find(array('areaPrincipalId' => $userId), null, 'id') ? true : false;
	}

	/**
	 * ��ȡ���۸�������Ϣ
	 */
	function conModule_d($province, $city, $customerTypeName, $personId)
	{
		//��������
		$customerTypeStr = $this->rtStr_d($customerTypeName);
		//��������
		$cityStr = $this->rtStr_d($city);
		//��������
		$sql = "SELECT
				r.*
			FROM
				oa_system_saleperson s
			LEFT JOIN oa_system_region r ON (s.salesAreaId = r.id)
			WHERE
				s.isUse = 0
				AND r.isStart = 0
				AND (s.customerTypeName LIKE CONCAT('%',$customerTypeStr,'%') AND s.provinceid = 0)
				OR (customerTypeName LIKE CONCAT('%',$customerTypeStr,'%') AND s.province = '$province'
				AND ( s.city IN ($cityStr) OR s.cityid ='0' ) )
			AND s.personId = '".$personId."'";
		return $this->_db->getArray($sql);
	}

	/**
	 * ��ȡ��ͬ��������
	 */
	function conRegion_d($customerType, $province, $module, $businessBelong,$needAll = false)
	{
        $sql = "SELECT
                r.*, s.exeDeptCode,
                s.exeDeptName
            FROM
                oa_system_saleperson s
            LEFT JOIN oa_system_region r ON (s.salesAreaId = r.id),
            (
                SELECT
                    GROUP_CONCAT(s.UserIds) as usd
                FROM
                    oa_system_saleperson s
                LEFT JOIN oa_system_region r ON (s.salesAreaId = r.id)
                WHERE
                    (
                        s.provinceid = '" . $province . "'
                        OR s.provinceid = '0'
                    )
                AND find_in_set('" . $customerType . "', s.customertype)
                AND s.isUse = 0
                AND r.isStart = 0
                AND r.module = '".$module."'
            ) d
            WHERE
                (
					s.provinceid = '" . $province . "'
					OR s.provinceid = '0'
                )
            AND find_in_set('" . $customerType . "', s.customertype)
            AND s.isUse = 0
            AND r.isStart = 0
			AND r.module = '".$module."'
            AND FIND_IN_SET('" . $businessBelong . "', s.businessBelong)
            AND IF(
                FIND_IN_SET('" . $_SESSION['USER_ID'] . "', d.usd),
                FIND_IN_SET('" . $_SESSION['USER_ID'] . "', s.UserIds),
                (s.UserIds IS NULL OR s.UserIds='')
            )
            GROUP BY r.id";
		$rs = $this->_db->getArray($sql);
        if($needAll){
            $arr['data'] = $rs;
            $arr['count'] = empty($rs)? 0 : count($rs);
            return $arr;
        }else{
            if(empty($rs)){
                return $rs;
            }else{
                $arr = array();
                array_push($arr, array_shift($rs));
                return $arr;
            }
        }
	}

	/**
	 * ��������ID��ȡ���������Ӧ��ִ������
	 * ���һ�θ��£�2016-12-15 PMS 2313
	 */
	function chkExeDeptByAreaId_d($areaCode){
		$rsArr = array();
		if($areaCode != ''){
			$sql = "SELECT
	r.areaPrincipal,s.personId,r.areaName,r.id,s.isUse,s.exeDeptCode,s.exeDeptName
FROM
	oa_system_region r
LEFT JOIN oa_system_saleperson s ON r.areaName = s.salesAreaName WHERE r.id = '{$areaCode}' AND s.isUse = 0 ORDER BY  s.exeDeptCode DESC;";
			$rsArr = $this->_db->getArray($sql);
			if(!empty($rsArr) && count($rsArr) > 1){//���ж�������ʱ��ȡ��һ��
				$arr = array();
				array_push($arr, array_shift($rsArr));
				$rsArr = $arr;
			}
		}
		return $rsArr;
	}

	/**
	 * ��ȡ��ͬ�������� �����Ĳ�����
	 */
	function conRegionByName_d($customerType, $province, $module, $businessBelong, $needAll = 0)
	{
        // ������˾
        $businessBelongSql = $businessBelong != '' ?
            " AND FIND_IN_SET('" . $businessBelong . "', s.businessBelongName)" : "";

        // �������
        $moduleSql = $module != '' ? " AND r.moduleName = '" . $module . "'" : "";

        $sql = "SELECT
                r.*, s.exeDeptCode,
                s.exeDeptName,GROUP_CONCAT(s.personName) as personNames,GROUP_CONCAT(s.personId) as personIds
            FROM
                oa_system_saleperson s
            LEFT JOIN oa_system_region r ON (s.salesAreaId = r.id),
            (
                SELECT
                    GROUP_CONCAT(s.UserIds) as usd
                FROM
                    oa_system_saleperson s
                LEFT JOIN oa_system_region r ON (s.salesAreaId = r.id)
                WHERE
                    (
                        s.province = '" . $province . "'
                        OR s.provinceid = '0'
                    )
                AND find_in_set('" . $customerType . "', s.customerTypeName)
                AND s.isUse = 0
                AND r.isStart = 0
                $moduleSql
            ) d
            WHERE
                (
					s.province = '" . $province . "'
					OR s.provinceid = '0'
                )
            AND find_in_set('" . $customerType . "', s.customerTypeName)
            AND s.isUse = 0
            AND r.isStart = 0
			$moduleSql
            $businessBelongSql
            AND IF(
                FIND_IN_SET('" . $_SESSION['USER_ID'] . "', d.usd),
                FIND_IN_SET('" . $_SESSION['USER_ID'] . "', s.UserIds),
                (s.UserIds IS NULL OR s.UserIds='')
            )
            GROUP BY r.id;";
		$rs = $this->_db->getArray($sql);
		if(empty($rs)){
			return $rs;
		}else{
		    if($needAll){
                return $rs;
            }else{
                $arr = array();
                array_push($arr, array_shift($rs));
                return $arr;
            }
		}
	}

	/**
	 * ���ļ�����
	 */
	function rtStr_d($orgStr)
	{
		$newStr = '';
		$strArr = explode(",", $orgStr);
		foreach ($strArr as $v) {
			$newStr .= "'$v'" . ",";
		}
		return rtrim($newStr, ",");
	}
}