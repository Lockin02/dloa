<?php

/**
 * @author Show
 * @Date 2012年11月6日 星期二 11:42:10
 * @version 1.0
 * @description:设备管理-库存信息 Model层
 */
class model_engineering_device_esmdevice extends model_base
{

    function __construct() {
        $this->tbl_name = "device_list";
        $this->sql_map = "engineering/device/esmdeviceSql.php";
        parent:: __construct();
    }

    /**
     * 获取设备类型数组
     * @param $condition
     * @return mixed
     */
    function getFormType_d($condition) {
        // 接入新OA的设备类型获取方法
        $result = util_curlUtil::getDataFromAWS('asset', 'SecClassifiAslp', array('a' => 1
        ));
        $data = util_jsonUtil::decode($result['data'], true);

        $rs = array();

        // 如果存在分类，则进行再次转换
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
//            //加入部门过滤
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
     * 获取设备记录时间
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
     * 获取个人项目
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
     * 更新设备记录时间
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
     * 删除设备记录
     * @param $id
     * @throws Exception
     */
    function delete_d($id) {
        try {
            $this->start_d();

            //设备信息
            $sql = "SELECT id,orderid,info_id,typeid,list_id,amount,return_num,targetdate,returndate FROM device_borrow_order_info WHERE id = '" . $id . "'";
            $equInfo = $this->_db->get_one($sql);

            //设备记录删除
            $sql = "DELETE FROM device_borrow_order_info WHERE id = '" . $id . "'";
            $this->_db->query($sql);

            //未归还,更新出库数量
            if (!$equInfo['returndate']) {
                $thisReturnNum = $equInfo['amount'] - $equInfo['return_num'];
                $sql = "UPDATE device_info SET borrow_num = borrow_num - " . $thisReturnNum . ",state = if(borrow_num=0,0,1) where id = '" . $equInfo['info_id'] . "'";
                $this->_db->query($sql);
            }

            //获取单据剩余设备
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
     * 设置设备使用状态
     * @param $rows
     * @return mixed
     */
    function setUsedInfo_d($rows) {
        $listIdArray = array(); // 缓存list_id的数组
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
     * 设备管理的库存信息入库
     * @param array $object 包含内容 [['assetCardId' => '卡片id', 'assetCardCode' => '卡片编码', 'dept_id' => '所属部门id',
     *                           'area' => '区域id', 'list_id' => 'device_list表的id', 'amount' => '数量'],...]
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
     * 设备管理库存信息退库
     * @param array $assetCardIdArray 资产卡片id号数组 [1,2,3,4,5]
     * @return bool
     * @throws Exception
     */
    function deleteDeviceInfo_d($assetCardIdArray) {
        $deviceInfo = $this->_db->getArray("SELECT id,amount,borrow_num,dept_id FROM device_info WHERE assetCardId IN(" .
            implode($assetCardIdArray) . ")");
        if ($deviceInfo[0]['id']) {
            // 做一个设备处理
            $allNum = 0;
            $allAmount = 0;
            $idArray = array();
            foreach ($deviceInfo as $v) {
                $allNum = $v['borrow_num'] ? $allNum + $v['borrow_num'] : $allNum; // 统计所有的借出数量
                $allAmount = $v['amount'] ? $allAmount + $v['amount'] : $allAmount; // 统计归还的数量
                $idArray[] = $v['id'];
            }
            if (!$allNum)
                throw new Exception('卡片对应的设备信息未完全归还。');

            $timeStamp = time();

            try {
                // 将设置信息设置为退库
                $this->_db->query("UPDATE device_info SET quit = 0 WHERE id IN(" . implode($idArray) . ")");

                // 新增退库单据
                $this->_db->query("INSERT INTO device_quit_order (dept_id,operatorid,amount,date,rand_key) VALUE
                  (" . $_SESSION['DEPT_ID'] . "," . $_SESSION['USER_ID'] . "," . $allAmount . "," . $timeStamp . ",'" .
                    md5($timeStamp.rand()) . "')");
                $orderId = $this->_db->insert_id();

                // 新增入库明细
                $sql = "INSERT INTO device_quit_order_info (dept_id,orderid,info_id,amount,notse,date,rand_key)
                    VALUES ";
                $dataSql = "";
                foreach ($deviceInfo as $v) {
                    if ($dataSql) $dataSql .= ',';
                    $dataSql .= "
                    (" . $v['dept_id'] . "," . $orderId . "," . $v['id'] . "," . $v['amount'] .
                        ",'固定资产归还卡片，系统自动生成'," . $timeStamp . ",'" . md5($timeStamp.rand()) . "')";
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
     * 批量归还/续借/转借
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
     * 字符串切分
     */
    function strBuild_d($str){
        if(!$str) return ''; // 空的时候直接返回空字符串
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