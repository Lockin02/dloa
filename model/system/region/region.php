<?php

/**
 * @author Administrator
 * @Date 2011年7月22日 9:41:21
 * @version 1.0
 * @description:oa_system_region Model层 大区---负责人表
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
	 * 根据用户名称返回用户负责区域
	 * 传入用户名
	 * 类型1直接返回数组，否则返回区域Id串
	 * @param $userId
	 * @param int $type
	 * @return null|string
	 */
	function getUserAreaId($userId, $type = 1) {
		//		$this->searchArr['areaPrincipalId'] = $userId;
		$this->searchArr['isStart'] = '0';//只获取开启状态的数据
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
	 * 根据用户名称返回用户负责区域
	 * 传入用户名
	 * 类型1直接返回数组，否则返回区域Id串
	 * @param $userId
	 * @param int $type
	 * @return string
	 */
	function getUserAreaName($userId, $type = 1) {
		$this->searchArr['isStart'] = '0';//只获取开启状态的数据
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
	 * 根据区域id  获取 扩展控制值
	 */
	function getExpandbyId($id) {
		$sql = "select expand from oa_system_region where id = '" . $id . "'";
		$arr = $this->_db->getArray($sql);
		return $arr[0]['expand'];
	}

	/**
	 * 根据区域id 获取 邮件接收人
	 */
	function getTomailId($id) {
		$sql = "select tomailId from oa_system_region where id = '" . $id . "'";
		$arr = $this->_db->getArray($sql);
		return $arr[0]['tomailId'];
	}

    /**
     * 根据区域id 获取 区域负责人
     */
    function getAreaPrincipalId($id) {
        $sql = "select areaPrincipalId from oa_system_region where id = '" . $id . "'";
        $arr = $this->_db->getArray($sql);
        return $arr[0]['areaPrincipalId'];
    }

	/**
	 * 是否区域负责人
	 * @param $userId
	 * @return bool|mixed
	 */
	function isAreaPrincipal_d($userId) {
		return $this->find(array('areaPrincipalId' => $userId), null, 'id') ? true : false;
	}

	/**
	 * 获取销售负责人信息
	 */
	function conModule_d($province, $city, $customerTypeName, $personId)
	{
		//加载引号
		$customerTypeStr = $this->rtStr_d($customerTypeName);
		//加载引号
		$cityStr = $this->rtStr_d($city);
		//返回数据
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
	 * 获取合同所属区域
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
	 * 根据区域ID获取归属区域对应的执行区域
	 * 最后一次更新：2016-12-15 PMS 2313
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
			if(!empty($rsArr) && count($rsArr) > 1){//当有多条数据时，取第一条
				$arr = array();
				array_push($arr, array_shift($rsArr));
				$rsArr = $arr;
			}
		}
		return $rsArr;
	}

	/**
	 * 获取合同所属区域 （中文参数）
	 */
	function conRegionByName_d($customerType, $province, $module, $businessBelong, $needAll = 0)
	{
        // 所属公司
        $businessBelongSql = $businessBelong != '' ?
            " AND FIND_IN_SET('" . $businessBelong . "', s.businessBelongName)" : "";

        // 所属板块
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
	 * 中文加括号
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