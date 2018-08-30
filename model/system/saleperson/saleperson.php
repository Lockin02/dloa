<?php
/**
 * @author Administrator
 * @Date 2012-12-24 14:48:23
 * @version 1.0
 * @description:���۸����˹��� Model��
 */
class model_system_saleperson_saleperson extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_system_saleperson";
        $this->sql_map = "system/saleperson/salepersonSql.php";
        parent::__construct();
    }

    /**
     * ��дadd_d����
     */
    function add_d($object)
    {
        //������������
        $rows = $this->handleRow($object);
        try {
            $this->start_d();
            $this->createBatch($rows, 'personName');

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��д�༭����
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //���������ֵ��ֶ�
            $datadictDao = new model_system_datadict_datadict();
//            $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
            parent :: edit_d($object, true);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �༭����---���ݸ�����
     */
    function editall_d($object)
    {
        try {
            $this->start_d();
            $sql = "update oa_system_saleperson set " .
                "personName='" . $object['personName'] . "',personId='" . $object['personId'] . "',deptName='" . $object['deptName'] . "',deptId='" . $object['deptId'] .
                "',businessBelongName = '" . $object['businessBelongName'] . "',businessBelong = '" . $object['businessBelong'] . "',exeDeptCode = '" . $object['exeDeptCode'] .
                "',exeDeptName = '" . $object['exeDeptName'] . "'" .
                " where id in (" . $object['ids'] . ")";
            $this->query($sql);
            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /*
     * ������������
     */
    function handleRow($row)
    {
        $i = 0;
        foreach ($row['info'] as $key => $val) {
            $cityArr = explode(",", $val['city']);
            $cityIdArr = explode(",", $val['cityId']);
            $cusArr = explode(",", $val['customerTypeName']);
            $cusCodeArr = explode(",", $val['customerType']);
            foreach ($cityArr as $k => $v) {
                foreach ($cusArr as $ck => $cv) {
                    //�ж��Ƿ����ظ�����
                    $sql = "select count(*) as num from oa_system_saleperson where " .
                        "provinceId = '" . $val['provinceId'] . "' and cityId = '" . $cityIdArr[$k] . "'" .
                        " and customerType = '" . $cusCodeArr[$ck] . "'";
                    $isRepeatArr = $this->_db->getArray($sql);
                    if ($isRepeatArr[0]['num'] == '0' || $row['isDirector'] == '1') {
                        $arr[$i]['city'] = $v;
                        $arr[$i]['cityId'] = $cityIdArr[$k];

                        $arr[$i]['customerTypeName'] = $cv;
                        $arr[$i]['customerType'] = $cusCodeArr[$ck];

                        $arr[$i]['country'] = $val['country'];
                        $arr[$i]['countryId'] = $val['countryId'];
                        $arr[$i]['province'] = $val['province'];
                        $arr[$i]['provinceId'] = $val['provinceId'];
                        $arr[$i]['isUse'] = $val['isUse'];
                        $arr[$i]['isDirector'] = $row['isDirector'];

                        $arr[$i]['areaName'] = $row['areaName'];
                        $arr[$i]['areaNameId'] = $row['areaNameId'];

                        $arr[$i]['personName'] = $row['personName'];
                        $arr[$i]['personId'] = $row['personId'];
                        $arr[$i]['deptName'] = $row['deptName'];
                        $arr[$i]['deptId'] = $row['deptId'];
                        $arr[$i]['createName'] = $_SESSION['USERNAME'];
                        $arr[$i]['createId'] = $_SESSION['USER_ID'];
                        $arr[$i]['createTime'] = date("Y-m-d");

                        $arr[$i]['businessBelongName'] = $row['businessBelongName'];
                        $arr[$i]['businessBelong'] = $row['businessBelong'];
                        $arr[$i]['formBelongName'] = $row['formBelongName'];
                        $arr[$i]['formBelong'] = $row['formBelong'];

                        $arr[$i]['salesAreaName'] = $row['salesAreaName'];
                        $arr[$i]['salesAreaId'] = $row['salesAreaId'];
                        $arr[$i]['salesManNames'] = $row['salesManNames'];
                        $arr[$i]['salesManIds'] = $row['salesManIds'];

                        $arr[$i]['exeDeptCode'] = $row['exeDeptCode'];
                        $arr[$i]['exeDeptName'] = $row['exeDeptName'];
                        $i++;
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * ������������
     */
    function importAdd_d($rows)
    {
        $i = 0;
        $cityArr = explode(",", $rows['city']);
        $cityIdArr = explode(",", $rows['cityId']);
        $cusArr = explode(",", $rows['customerTypeName']);
        $cusCodeArr = explode(",", $rows['customerType']);

        foreach ($cityArr as $k => $v) {
            foreach ($cusArr as $ck => $cv) {
                $arr[$i]['city'] = $v;
                $arr[$i]['cityId'] = $cityIdArr[$k];

                $arr[$i]['customerTypeName'] = $cv;
                $arr[$i]['customerType'] = $cusCodeArr[$ck];

                $arr[$i]['country'] = $rows['country'];
                $arr[$i]['countryId'] = $rows['countryId'];
                $arr[$i]['province'] = $rows['province'];
                $arr[$i]['provinceId'] = $rows['provinceId'];

                $arr[$i]['isDirector'] = $rows['isDirector'];

                $arr[$i]['areaName'] = $rows['areaName'];
                $arr[$i]['areaNameId'] = $rows['areaNameId'];

                $arr[$i]['isUse'] = $rows['isUse'];
                $arr[$i]['personName'] = $rows['personName'];
                $arr[$i]['personId'] = $rows['personId'];
                $arr[$i]['deptName'] = $rows['deptName'];
                $arr[$i]['deptId'] = $rows['deptId'];
                $arr[$i]['createName'] = $_SESSION['USERNAME'];
                $arr[$i]['createId'] = $_SESSION['USER_ID'];
                $arr[$i]['createTime'] = date("Y-m-d");
                $arr[$i]['businessBelongName'] = $rows['businessBelongName'];
                $arr[$i]['businessBelong'] = $rows['businessBelong'];
                $arr[$i]['formBelong'] = $_SESSION['COM_BRN_PT'];
                $arr[$i]['formBelongName'] = $_SESSION['COM_BRN_CN'];

                $i++;
            }


        }

        try {
            $this->start_d();
            $this->createBatch($arr, 'personName');

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���ݸ�����ɾ������
     */
    function deletesAll_d($personId)
    {
        try {
            $sql = "delete from oa_system_saleperson where personId = '" . $personId . "'";
            $this->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /************************����*************************************/
    /**
     * �������ֲ��Ҷ�ӦId
     */
    function user($userName)
    {
        if (!empty($userName)) {
            $userDao = new model_deptuser_user_user();
            $user = $userDao->getUserByName($userName);
            $userId = $user['USER_ID'];
            return $userId;
        } else {
            return "";
        }
    }

    function userArr($userName)
    {
        if (!empty($userName)) {
            $userDao = new model_deptuser_user_user();
            $user = $userDao->getUserByName($userName);
            return $user;
        } else {
            return "";
        }
    }

    /**
     * �жϵ����Ƿ���ȷ�����ң�ʡ���У�
     */
    function isArea($type, $name, $provinceId)
    {
        switch ($type) {
            case "country" :
                $sql = "select id from oa_system_country_info where countryName = '" . $name . "'";
                break;
            case "province" :
                $sql = "select id from oa_system_province_info where provinceName = '" . $name . "'";
                break;
            case "city" :
                if ($name == 'ȫ��') {
                    $sql = "select GROUP_CONCAT(CAST(id AS char)) as id from oa_system_city_info where provinceId = '" . $provinceId . "'";
                    break;
                } else {
                    $sql = "select id from oa_system_city_info where cityName = '" . $name . "'";
                    break;
                }
        }
        $arr = $this->_db->getArray($sql);
        return $arr[0]['id'];
    }

    function allCity($cityId)
    {
        $sql = "select GROUP_CONCAT(cityName) as cityName from oa_system_city_info where id in (" . $cityId . ")";
        $arr = $this->_db->getArray($sql);
        return $arr[0]['cityName'];
    }

    /**
     * �ͻ����ʹ���
     */
    function customerTypeStr($customerType)
    {
        $customerTypeStr = '';
        $customerTypeArr = explode(",", $customerType);
        foreach ($customerTypeArr as $k => $v) {
            $customerTypeStr .= "'$v'" . ",";
        }
        $customerTypeStr = rtrim($customerTypeStr, ",");
        $sql = "SELECT GROUP_CONCAT(dataCode) as dataCode FROM `oa_system_datadict` where dataName in (" . $customerTypeStr . ")";
        $arr = $this->_db->getArray($sql);
        return $arr[0]['dataCode'];
    }

    /**
     * ������˾����
     */
    function businessBelongNameStr($businessBelongName)
    {
        $sql = "select NameCN,NamePT from branch_info where NameCN =  '" . $businessBelongName . "'";
        $arr = $this->_db->getArray($sql);
        return $arr[0];
    }

    /************************����********end****************************/

    /************************** ��ȡ���۸����� ***********************/
    /**
     * ��ȡ���۸�����
     */
    function getDirector_d($province, $city, $customerTypeName)
    {
        //��������
        $customerTypeStr = $this->rtStr_d($customerTypeName);
        //��������
        $cityStr = $this->rtStr_d($city);
        //����Ϊȫʡ�ģ�����ѡ�ĸ����ж��ܴ������۸�����
		$cityStr .= ",'ȫʡ'";
        //�������� 20131009 xgq
        $sql = "select
				personName AS areaName,personId AS areaNameId,deptId,deptName
			from
				oa_system_saleperson
			where
				((customerTypeName like concat('%',$customerTypeStr,'%') and provinceId = 0)
				or (customerTypeName like concat('%',$customerTypeStr,'%') and province = '$province'
				and ( city in ($cityStr) or cityId ='0' ))) and ( FIND_IN_SET('$_SESSION[USER_COM]',businessBelong) ) and isUse = 0
			group by personId";
		 $tempArr = $this->_db->getArray($sql);
		 if(!is_array($tempArr)){
		 	 $sqlT = "select
				personName AS areaName,personId AS areaNameId,deptId,deptName
			from
				oa_system_saleperson
			where
				((customerTypeName like concat('%',$customerTypeStr,'%') and provinceId = 0)
				or (customerTypeName like concat('%',$customerTypeStr,'%') and province = '$province'
				and ( city in ($cityStr) or cityId ='0' ))) and isUse = 0
			group by personId";
			$returnArr = $this->_db->getArray($sqlT);
		 }else{
		 	$returnArr = $tempArr;
		 }
        return $returnArr;
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

    /**
     * ����ʡ�ݻ�ȡ������
     */
    function getPersonByProvince_d($province)
    {
        //��������
        $rsArr = array();
        //��ѯ����
        $sql = "select
				personName,personId
			from
				oa_system_saleperson
			where
				province = '$province'
			group by personId";
        $arr = $this->_db->getArray($sql);
        if ($arr) {
            //���������ʽ
            foreach ($arr as $key => $val) {
                if ($rsArr) {
                    $rsArr['personId'] = array($val['personId']);
                    $rsArr['personName'] = array($val['personName']);
                } else {
                    array_push($rsArr['personId'], $val['personId']);
                    array_push($rsArr['personName'], $val['personName']);
                }
            }
            $rsArr['personId'] = implode(',', $rsArr['personId']);
            $rsArr['personName'] = implode(',', $rsArr['personName']);
        }
        return $rsArr;
    }

    /**
     * ���� userId ��ȡ �������ʡ
     */
    function getProvinceByUserId($userId)
    {
        //��ѯsql
        $sql = "SELECT province FROM oa_system_saleperson where personId = '" . $userId . "'";
        return $this->_db->getArray($sql);
    }

    /**
     *��ȡ�������ʡ����Ϣ��ʡ�ݼ���Ӧ�ͻ����ͣ�
     */
    function getSaleArea($userId)
    {
        $this->searchArr['saleSearch'] = $userId;
        return $this->list_d();
    }

    /**
     * �ж��Ƿ�Ϊ����������������
     */
    function isSalePersonForDL_d($userId){
        return $this->find(array('areaNameId' => $userId,'formBelong' => 'dl'),null,'id');
    }
}