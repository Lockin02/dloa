<?php

/**
 * @author show
 * @Date 2014��9��26�� 13:46:51
 * @version 1.0
 * @description:�����豸�۾� Model��
 */
class model_engineering_resources_esmdevicefee extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_resource_fee";
        $this->sql_map = "engineering/resources/esmdevicefeeSql.php";
        parent::__construct();
    }

    /**
     * ���·���
     * @param $thisYear
     * @param $thisMonth
     * @return bool|string
     */
    function updateFee_d($thisYear, $thisMonth) {
        set_time_limit(0);

        // ��ʼ��һЩ����
        $esmProjectDao = new model_engineering_project_esmproject();
        $createTime = date('Y-m-d H:i:s');

        // ��ʼ��OA���۾�
        $result = util_curlUtil::getDataFromAWS('asset', 'DepreciationAslp', array(
            'deprNo' => $thisYear . '.' . $thisMonth
        ), array(
            'ct' => 600
        ));

		if ($_SESSION['USER_ID'] == 'oazy') {
            print_r($result);
        }
		
        $result = util_jsonUtil::decode($result['data'], true);
        if ($result['data']['key'] != 'OK') {
            return util_jsonUtil::iconvGB2UTF('�۾�ʧ�ܣ�����ϵ����Ա��������ϢΪ��' . $result['data']['error']) ;
        }

        // ��Ŀid����
        $projectIdArr = array();

        // ������OA�ʲ��۾���Ϣ
        $result = util_curlUtil::getDataFromAWS('asset', 'selectProjectTotalDepreciation', array(
            'deprYear' => $thisYear, 'deprMonth' => $thisMonth
        ));
        $result = util_jsonUtil::decode($result['data'], true);
        $result = $result['data']['projectDepreciation'];
        if(!empty($result)){
            $beginDate = $thisYear . '-' . $thisMonth . '-01';
            $i = 0;
            $feeArr = array();
            $len = count($result);
            foreach ($result as $k => $v){
                $i ++;
                $rs = $esmProjectDao->find(array('projectCode' => $k), null, 'id');
                if(!empty($rs)){// ֻ�����й�����Ŀ������
                    $projectId = $rs['id'];
                    $projectIdArr[] = $projectId;
                    // �жϵ�����Ŀ���۾���Ϣ�Ƿ����
                    $rs = $this->find(
                        ' projectId = "' .$projectId . '" AND beginDate = "' . $beginDate . '" AND listId IS NULL'
                        , null, 'id,fee');
                    if(!empty($rs)){
                        if($rs['fee'] != $v){// ���㲻ͬʱ����
                            $this->updateById(array(
                                'id' => $rs['id'],
                                'fee' => $v,
                                'createId' => $_SESSION['USER_ID'],
                                'createName' => $_SESSION['USERNAME'],
                                'createTime' => $createTime
                            ));
                        }
                    }else{
                        $temp['projectId'] = $projectId;
                        $temp['beginDate'] = $beginDate;
                        $temp['fee'] = $v;
                        $temp['createId'] = $_SESSION['USER_ID'];
                        $temp['createName'] = $_SESSION['USERNAME'];
                        $temp['createTime'] = $createTime;
                        $feeArr[] = $temp;
                    }
                }
                if ($i % 500 == 0 || $len == $i) {
                    if ($feeArr) {
                        $this->createBatch($feeArr);
                    }
                    $feeArr = array();
                }
            }
        }

        // ��־д��
        $logDao = new model_engineering_baseinfo_esmlog();
        $logDao->addLog_d(-1, '�����豸����', count($result) . '|' . $thisMonth);

        /************** �ɾ��㲻�ټ��� ********************/
//
//        // ɾ����������ݣ���OA�۾����ݳ���
//        $this->_db->query("DELETE FROM " . $this->tbl_name . " WHERE DATE_FORMAT(createTime,'%Y-%m-%d') = '" . day_date . "' AND borrowInfoId IS NOT NULL");
//
//        // get top date
//        $dateInfo = $this->_db->getArray("SELECT MAX(createTime) AS maxDate FROM oa_esm_resource_fee WHERE borrowInfoId IS NOT NULL");
//        $maxDate = $dateInfo[0]['maxDate'] ? date('Y-m-d', strtotime($dateInfo[0]['maxDate'])) : day_date;
//        $maxDateCondition = $dateInfo[0]['maxDate'] ?
//			" AND f.borrowIsOver = 0 AND DATE_FORMAT(f.createTime,'%Y-%m-%d') = '" . $maxDate . "'" : "";
//
//        // ����ȡ�����и��¹�������
//        $sql = "SELECT
//			a.id AS borrowInfoId,b.id AS listId,b.price,a.amount AS borrowNum,
//			IF(f.borrowIsOver IS NULL,FROM_UNIXTIME(a.date,'%Y-%m-%d'),f.endDate) AS beginDate,
//			if(a.returndate = 0 OR a.returndate IS NULL,'" . day_date . "',FROM_UNIXTIME(a.returndate,'%Y-%m-%d')) AS endDate,
//			if(a.returndate IS NOT NULL,1,0) AS borrowIsOver,
//			round((if(a.returndate = 0 OR a.returndate IS NULL,UNIX_TIMESTAMP('" . day_date . "'),a.returndate) - IF(f.borrowIsOver IS NULL,a.date,UNIX_TIMESTAMP(f.endDate)))/86400) AS days,
//			p.id AS projectId,b.depreciation,b.depreciationYear,
//			IF(b.date = 0 OR b.date IS NULL, NULL, FROM_UNIXTIME(b.date,'%Y-%m-%d')) AS inDate,
//			IF(b.depreciationYear IS NULL,NULL,(ADDDATE(FROM_UNIXTIME(b.date,'%Y-%m-%d'),INTERVAL b.depreciationYear MONTH))) AS overDate,
//			l.managementExpenses
//		FROM
//			device_borrow_order_info AS a
//			LEFT JOIN device_info AS b ON b.id=a.info_id
//			LEFT JOIN device_list AS l ON l.id = b.list_id
//			LEFT JOIN device_borrow_order AS c ON c.id=a.orderid
//			LEFT JOIN project_info AS d ON d.id = c.project_id
//			LEFT JOIN oa_esm_project AS p ON p.projectCode = d.number
//			LEFT JOIN oa_esm_resource_fee AS f ON f.borrowInfoId = a.id
//		WHERE d.number <> '' AND p.id IS NOT NULL" . $maxDateCondition . " ORDER BY a.id";
//        $borrowList = $this->_db->getArray($sql);
//
//		// ������������
//		$this->addDataBatch_d($borrowList, $createTime);
//
//		// ��ȡû�д����������
//		$sql = "SELECT id FROM device_borrow_order_info WHERE id NOT IN(SELECT DISTINCT borrowInfoId FROM oa_esm_resource_fee WHERE borrowInfoId IS NOT NULL)";
//		$idList = $this->_db->getArray($sql);
//
//		// ��ȡû�д�����豸����
//		if ($idList) {
//			$i = 0;
//			$ids = array();
//			$idListLength = count($idList);
//			// 1000 ��id����һ��
//			foreach ($idList as $v) {
//				$i ++;
//				$ids[] = $v['id'];
//				if ($i % 500 == 0 || $idListLength == $i) {
//					// ��ѯ��δ�����������
//					$borrowList = $this->getNeverDealData_d(day_date, implode(',', $ids));
//					if ($borrowList) {
//						$this->addDataBatch_d($borrowList, $createTime);
//					}
//					$ids = array();
//				}
//			}
//		}

        if (!empty($projectIdArr)) {
            // ת���ַ����������ҵ��ʹ��
            $projectIds = implode(',', $projectIdArr);
            // ������ĿԤ����
            $esmProjectDao->calProjectFee_d(null, $projectIds);
            $esmProjectDao->calFeeProcess_d(null, $projectIds);
        }

        return 1;
    }

	/**
	 * ��ȡδ���������
	 * @param $day_date
	 * @param $ids
	 * @return mixed
	 */
	function getNeverDealData_d($day_date, $ids) {
		if (empty($ids)) return false;

		// ���ȡ����û��ȡ��������
		$sql = "SELECT
			a.id AS borrowInfoId,b.id AS listId,b.price,a.amount AS borrowNum,
			FROM_UNIXTIME(a.date,'%Y-%m-%d') AS beginDate,
			if(a.returndate = 0 OR a.returndate IS NULL,'" . $day_date . "',FROM_UNIXTIME(a.returndate,'%Y-%m-%d')) AS endDate,
			if(a.returndate IS NOT NULL,1,0) AS borrowIsOver,
			round((if(a.returndate = 0 OR a.returndate IS NULL,UNIX_TIMESTAMP('" . $day_date . "'),a.returndate) - a.date)/86400) AS days,
			p.id AS projectId,b.depreciation,b.depreciationYear,
			IF(b.date = 0 OR b.date IS NULL, NULL, FROM_UNIXTIME(b.date,'%Y-%m-%d')) AS inDate,
			IF(b.depreciationYear IS NULL,NULL,(ADDDATE(FROM_UNIXTIME(b.date,'%Y-%m-%d'),INTERVAL b.depreciationYear MONTH))) AS overDate,
			l.managementExpenses
		FROM
			device_borrow_order_info AS a
			LEFT JOIN device_info AS b ON b.id=a.info_id
			LEFT JOIN device_list AS l ON l.id = b.list_id
			LEFT JOIN device_borrow_order AS c ON c.id=a.orderid
			LEFT JOIN project_info AS d ON d.id = c.project_id
			LEFT JOIN oa_esm_project AS p ON p.projectCode = d.number
		WHERE d.number <> '' AND p.id IS NOT NULL AND a.id IN ($ids);";
		return $this->_db->getArray($sql);
	}

	/**
	 * �����ṩ���������þ����¼
	 * @param $borrowList
	 * @param $createTime
	 * @return bool
	 */
	function addDataBatch_d($borrowList, $createTime) {
		$inArray = array(); // fee records
		$i = 0;
		foreach ($borrowList as $v) {
			if (empty($v['depreciation']) && empty($v['overDate'])) {
				$fee = 0;
			} elseif ($v['overDate'] < $v['beginDate']) {
				$fee = bcmul($v['managementExpenses'], (strtotime($v['endDate']) - strtotime($v['beginDate'])) / 86400, 2);
			} elseif ($v['overDate'] < $v['endDate']) { // if over date small than end date,calculate useful fee
				$fee = bcmul($v['depreciation'], (strtotime($v['overDate']) - strtotime($v['beginDate'])) / 86400, 2);
				$manageFee = bcmul($v['managementExpenses'], (strtotime($v['endDate']) - strtotime($v['overDate'])) / 86400, 2);
				$fee = bcadd($fee, $manageFee); // merge fee and manage fee
			} else {
				$fee = bcmul($v['depreciation'], $v['days'], 2); // if over date bigger than end date,calculate all fee
			}
			array_push($inArray, array(
				'listId' => $v['listId'], 'borrowInfoId' => $v['borrowInfoId'], 'projectId' => $v['projectId'],
				'beginDate' => $v['beginDate'], 'endDate' => $v['endDate'], 'days' => $v['days'],
				'borrowIsOver' => $v['borrowIsOver'], 'fee' => $fee,
				'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'], 'createTime' => $createTime
			));

			$i ++;
			if ($i > 1000) {
				$this->createBatch($inArray);
				$i = 0;
				$inArray = array(); // clear fee records
			}
		}

		// batch add data
		if (!empty($inArray)) $this->createBatch($inArray);

		return true;
	}

    /**
     * ��ȡ��Ŀ�豸���� - ʵʱ
     * @param $projectId
     * @param string $from 1 ��ϵͳ / 2 ��OA
     * @return int
     */
    function getDeviceFee_d($projectId, $from = '1') {
        if ($projectId) {
            if ($from == '1') {
                $sql = "SELECT SUM(fee) AS fee FROM " . $this->tbl_name . " WHERE projectId = " . $projectId .
                    " AND listId IS NOT NULL";
            } else {
                $sql = "SELECT SUM(fee) AS fee FROM " . $this->tbl_name . " WHERE projectId = " . $projectId .
                    " AND listId IS NULL";
            }
            $rs = $this->_db->getArray($sql);
            return $rs[0]['fee'] ? $rs[0]['fee'] : 0;
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ������ϸ
     * @param $projectId
     * @return array|bool
     */
    function searchDetailList_d($projectId) {
        $sql = "SELECT '��ʼ������' AS budgetType, feeEqu AS fee, '-' AS yearMonth
            FROM oa_esm_project WHERE id = $projectId
            UNION ALL
            SELECT '�ʲ��۾�' AS budgetType, fee, CONCAT(YEAR(beginDate), '.', MONTH(beginDate)) AS yearMonth
            FROM oa_esm_resource_fee WHERE listId IS NULL AND projectId = $projectId
            UNION ALL
            SELECT '���豸ϵͳ'  AS budgetType, SUM(fee) AS fee, '-' AS yearMonth
            FROM oa_esm_resource_fee WHERE listId <> '' AND projectId = $projectId";
        $data = $this->_db->getArray($sql);

        // �����ϼƴ���
        $feeAll = 0;
        foreach ($data as $v) {
            $feeAll = bcadd($v['fee'], $feeAll, 2);
        }
        $data[] = array(
            'budgetType' => '�ϼ�',
            'fee' => $feeAll
        );

        return $data;
    }

    /**
     * ��ȡ�����豸����
     * @param $officeIds
     * @param $deprMoney �����۾�
     * @param $thisYear
     * @param $thisMonth
     * @return mixed
     */
    function getOfficeEquFee_d($officeIds, $deprMoney, $thisYear, $thisMonth) {
        $searchTime = strtotime($thisYear . '-' . $thisMonth . '-01');
        $sql = "SELECT
                officeId,officeName,SUM(e.fee) AS projectDepr
            FROM
                oa_esm_project c
                LEFT JOIN
                (
                    SELECT SUM(fee) as fee,projectId
                    FROM oa_esm_resource_fee
                    WHERE UNIX_TIMESTAMP(beginDate) = $searchTime AND listId IS NULL GROUP BY projectId
                ) e ON e.projectId = c.id
            WHERE officeId IN($officeIds)
            GROUP BY officeId";
        $data = $this->_db->getArray($sql);

        // �����ѯ�����˾������ݣ�����ռ�ȼ���
        if (!empty($data)) {
            // ��ȡ����
            $officeDao = new model_engineering_officeinfo_officeinfo();
            $officeMap = $officeDao->getOfficeMap_d($officeIds);
            // ռ�ȼ���
            $deprAll = 0;
            $all = 100;

            // ��һ��ѭ��ͳ���ܽ���Լ�������ù�������
            foreach ($data as $k => $v) {
                $data[$k]['projectDepr'] = $v['projectDepr'] = $v['projectDepr'] === null ? 0 : $v['projectDepr'];
                $data[$k]['deptName'] = $officeMap[$v['officeId']]['feeDeptName'];
                $deprAll = bcadd($deprAll, $v['projectDepr'], 2);
            }

            $deduct = $shareAll = bcsub($deprMoney, $deprAll, 2);
            $maxIndex = count($data) - 1;

            // �ڶ���ѭ������ռ��
            foreach ($data as $k => $v) {
                if ($maxIndex == $k) {
                    $data[$k]['projectDeprRate'] = $all;
                    $data[$k]['feeIn'] = $deduct;
                } else {
                    $rate = $deprAll ? bcdiv($v['projectDepr'], $deprAll, 5) : 0.00;
                    $data[$k]['projectDeprRate'] = round(bcmul($rate, 100, 4), 2);
                    $data[$k]['feeIn'] = round(bcmul($rate, $shareAll, 4), 2);
                    $all = bcsub($all, $data[$k]['projectDeprRate'], 2);
                    $deduct = bcsub($deduct, $data[$k]['feeIn'], 2);
                }
            }
        }

        return $data;
    }
}