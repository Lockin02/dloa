<?php

/**
 * @author Show
 * @Date 2012��11��6�� ���ڶ� 11:42:10
 * @version 1.0
 * @description:�豸����-�����Ϣ Model��
 */
class model_engineering_device_esmdevice extends model_base
{

    function __construct() {
        $this->tbl_name = "device_list";
        $this->sql_map = "engineering/device/esmdeviceSql.php";
        parent:: __construct();
    }

    /**
     * ��ȡ�豸��������
     * @param $condition
     * @return mixed
     */
    function getFormType_d($condition) {
        // ������OA���豸���ͻ�ȡ����
        $result = util_curlUtil::getDataFromAWS('asset', 'SecClassifiAslp', array('a' => 1
        ));
        $data = util_jsonUtil::decode($result['data'], true);

        $rs = array();

        // ������ڷ��࣬������ٴ�ת��
        if ($data['data']['array']) {
            foreach ($data['data']['array'] as $v) {
                $rs[] = array(
                    'text' => $v['FULLNAME'],
                    'value' => $v['ID']
                );
            }
        }

//        if (isset($condition['myself'])) {
//            $this->searchArr = array('oUserid' => $_SESSION['USER_ID'], 'notReturn' => 1);
//            $this->asc = false;
//            $this->sort = "g.typename";
//            $this->groupBy = "g.id";
//            $rs = $this->list_d('select_deviceTypeMy');
//        } else {
//            //���벿�Ź���
//            include(WEB_TOR . "includes/config.php");
//            $deptIds = isset($defaultEsmDept) ? implode(',', array_keys($defaultEsmDept)) : '';
//            $this->searchArr = array('dept_id_in' => $deptIds);
//            $this->asc = false;
//            $this->sort = "c.typename";
//            $rs = $this->list_d('select_deviceType');
//        }
        return $rs;
    }

    /**
     * ��ȡ�豸��¼ʱ��
     * @param $id
     * @return mixed
     */
    function getLogDate_d($id) {
        $sql = "
            SELECT
            IF (
                date = 0,
                '',
                FROM_UNIXTIME(date, '%Y-%m-%d')
            ) AS date,
            IF (
                returndate = 0,
                '',
                FROM_UNIXTIME(returndate, '%Y-%m-%d')
            ) AS returndate
            FROM
                device_borrow_order_info
            WHERE
                id ='" . $id . "'";
        return $this->findSql($sql);
    }

    /**
     * ��ȡ������Ŀ
     * @param $projectId
     * @return mixed
     */
    function getMyEqu_d($projectId) {
        return $this->_db->getArray("SELECT
			a.id
        FROM
			device_borrow_order_info a
			LEFT JOIN device_info b ON b.id=a.info_id
			LEFT JOIN device_borrow_order o ON o.id=a.orderid
			LEFT JOIN device_list c ON c.id=b.list_id
			LEFT JOIN project_info p ON o.project_id=p.id
        WHERE p.projectId = $projectId AND o.confirm = 1 AND o.userid='" . $_SESSION['USER_ID'] .
            "' AND a.amount > a.return_num");
    }

    /**
     * �����豸��¼ʱ��
     * @param $dateInfo
     * @return mixed
     */
    function updateLogDate_d($dateInfo) {
        $sql = "
            UPDATE device_borrow_order_info
            SET date =
            IF (
                '" . $dateInfo['date'] . "' = '',
                NULL,
                UNIX_TIMESTAMP('" . $dateInfo['date'] . "')
            ),
             returndate =
            IF (
                '" . $dateInfo['returndate'] . "' = '',
                NULL,
                UNIX_TIMESTAMP('" . $dateInfo['returndate'] . "')
            )
            WHERE
                id = '" . $dateInfo['id'] . "'";
        return $this->query($sql);
    }

    /**
     * ɾ���豸��¼
     * @param $id
     * @throws Exception
     */
    function delete_d($id) {
        try {
            $this->start_d();

            //�豸��Ϣ
            $sql = "SELECT id,orderid,info_id,typeid,list_id,amount,return_num,targetdate,returndate FROM device_borrow_order_info WHERE id = '" . $id . "'";
            $equInfo = $this->_db->get_one($sql);

            //�豸��¼ɾ��
            $sql = "DELETE FROM device_borrow_order_info WHERE id = '" . $id . "'";
            $this->_db->query($sql);

            //δ�黹,���³�������
            if (!$equInfo['returndate']) {
                $thisReturnNum = $equInfo['amount'] - $equInfo['return_num'];
                $sql = "UPDATE device_info SET borrow_num = borrow_num - " . $thisReturnNum . ",state = if(borrow_num=0,0,1) where id = '" . $equInfo['info_id'] . "'";
                $this->_db->query($sql);
            }

            //��ȡ����ʣ���豸
            $sql = "SELECT id FROM device_borrow_order_info WHERE orderid = '" . $equInfo['orderid'] . "'";
            $otherEquInfo = $this->_db->get_one($sql);
            if (!$otherEquInfo) {
                $sql = "DELETE FROM device_borrow_order WHERE id ='" . $equInfo['orderid'] . "'";
                $this->_db->query($sql);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * �����豸ʹ��״̬
     * @param $rows
     * @return mixed
     */
    function setUsedInfo_d($rows) {
        $listIdArray = array(); // ����list_id������
        foreach ($rows as $v) {
            array_push($listIdArray, $v['bid']);
        }
        $listIds = implode(',', $listIdArray);
        $sql = "
            SELECT c.formNo,d.resourceId,d.borrowItemId FROM oa_esm_resource_elent c
            LEFT JOIN oa_esm_resource_elentdetail d ON c.id = d.mainId
            WHERE d.status <> 2 AND resourceId IN($listIds)
            UNION ALL
            SELECT c.formNo,d.resourceId,d.borrowItemId FROM oa_esm_resource_ereturn c
            LEFT JOIN oa_esm_resource_ereturndetail d ON c.id = d.mainId
            WHERE d.status = 0 AND resourceId IN($listIds)
            UNION ALL
            SELECT c.formNo,d.resourceId,d.borrowItemId FROM oa_esm_resource_erenew c
            LEFT JOIN oa_esm_resource_erenewdetail d ON c.id = d.mainId
            WHERE d.status <> 2 AND resourceId IN($listIds)
        ";
        $listStatus = $this->_db->getArray($sql);
        $listStatusFormatted = array();
        foreach ($listStatus as $k => $v) {
            $listStatusFormatted[$v['resourceId']][$v['borrowItemId']] = $v;
        }
        foreach ($rows as $k => $v) {
            $rows[$k]['formNo'] = isset($listStatusFormatted[$v['bid']][$v['borrowItemId']]) ?
                $listStatusFormatted[$v['bid']][$v['borrowItemId']]['formNo'] : '';
        }
        return $rows;
    }

    /**
     * �豸����Ŀ����Ϣ���
     * @param array $object �������� [['assetCardId' => '��Ƭid', 'assetCardCode' => '��Ƭ����', 'dept_id' => '��������id',
     *                           'area' => '����id', 'list_id' => 'device_list���id', 'amount' => '����'],...]
     * @throws $e
     */
    function insertDeviceInfo_d($object) {
        try {
            $timeStamp = time();
            $sql = "INSERT INTO device_info (assetCardId,coding,dept_id,area,list_id,amount,date,rand_key) VALUES ";
            $dataSql = "";
            foreach ($object as $v) {
                if ($dataSql) $dataSql .= ',';
                $dataSql .= "
                (" . $v['assetCardId'] . ",'" . $v['assetCardCode'] . "'," . $v['dept_id'] .
                    "," . $v['area'] . "," . $v['list_id'] . "," . $v['amount'] . "," . $timeStamp . "," .
                    md5($timeStamp.rand()) . ")";
            }
            return $this->_db->query($sql.$dataSql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �豸��������Ϣ�˿�
     * @param array $assetCardIdArray �ʲ���Ƭid������ [1,2,3,4,5]
     * @return bool
     * @throws Exception
     */
    function deleteDeviceInfo_d($assetCardIdArray) {
        $deviceInfo = $this->_db->getArray("SELECT id,amount,borrow_num,dept_id FROM device_info WHERE assetCardId IN(" .
            implode($assetCardIdArray) . ")");
        if ($deviceInfo[0]['id']) {
            // ��һ���豸����
            $allNum = 0;
            $allAmount = 0;
            $idArray = array();
            foreach ($deviceInfo as $v) {
                $allNum = $v['borrow_num'] ? $allNum + $v['borrow_num'] : $allNum; // ͳ�����еĽ������
                $allAmount = $v['amount'] ? $allAmount + $v['amount'] : $allAmount; // ͳ�ƹ黹������
                $idArray[] = $v['id'];
            }
            if (!$allNum)
                throw new Exception('��Ƭ��Ӧ���豸��Ϣδ��ȫ�黹��');

            $timeStamp = time();

            try {
                // ��������Ϣ����Ϊ�˿�
                $this->_db->query("UPDATE device_info SET quit = 0 WHERE id IN(" . implode($idArray) . ")");

                // �����˿ⵥ��
                $this->_db->query("INSERT INTO device_quit_order (dept_id,operatorid,amount,date,rand_key) VALUE
                  (" . $_SESSION['DEPT_ID'] . "," . $_SESSION['USER_ID'] . "," . $allAmount . "," . $timeStamp . ",'" .
                    md5($timeStamp.rand()) . "')");
                $orderId = $this->_db->insert_id();

                // ���������ϸ
                $sql = "INSERT INTO device_quit_order_info (dept_id,orderid,info_id,amount,notse,date,rand_key)
                    VALUES ";
                $dataSql = "";
                foreach ($deviceInfo as $v) {
                    if ($dataSql) $dataSql .= ',';
                    $dataSql .= "
                    (" . $v['dept_id'] . "," . $orderId . "," . $v['id'] . "," . $v['amount'] .
                        ",'�̶��ʲ��黹��Ƭ��ϵͳ�Զ�����'," . $timeStamp . ",'" . md5($timeStamp.rand()) . "')";
                }
                $this->_db->query($sql.$dataSql);

                return true;
            } catch (Exception $e) {
                throw $e;
            }
        } else {
            return true;
        }
    }

    /**
     * �����黹/����/ת��
     */
    function batch_d($object) {
        switch($object['searchKey']){
            case 'dpcoding' : $searchSql = 'b.dpcoding' ; break;
            case 'coding' : $searchSql = 'b.coding' ; break;
            case 'id' : $searchSql = 'b.id' ; break;
        }
        $searchText = $this->strBuild_d($object['searchText']);
        $searchSql .= " in($searchText)";
        $sql = "SELECT
					a.id,p.projectId,p.number AS projectCode,p.name AS projectName,p.manager AS managerId,
					u.USER_NAME AS managerName,p.flag,b.dept_id AS deptId
				FROM
					device_borrow_order_info AS a
				LEFT JOIN device_info AS b ON b.id = a.info_id
				LEFT JOIN device_borrow_order AS o ON o.id = a.orderid
				LEFT JOIN project_info p ON o.project_id = p.id
				LEFT JOIN USER u ON p.manager = u.USER_ID
				WHERE
					a.amount > a.return_num
				AND a.claim = 1
				AND o.userid = '".$_SESSION['USER_ID']."' AND $searchSql";
        return $this->_db->getArray($sql);
    }

    /**
     * �ַ����з�
     */
    function strBuild_d($str){
        if(!$str) return ''; // �յ�ʱ��ֱ�ӷ��ؿ��ַ���
        $strArr = explode("\n",$str);
        $newStr = "'";
        foreach($strArr as $key => $val){
            $val = trim($val);
            if($key){
                $newStr .= "','".$val;
            }else{
                $newStr .= $val;
            }
        }
        $newStr .= "'";
        return $newStr;
    }
}