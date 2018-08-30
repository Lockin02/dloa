<?php

/**
 * @author show
 * @Date 2014年9月26日 13:46:51
 * @version 1.0
 * @description:工程设备折旧 Model层
 */
class model_engineering_resources_esmdevicefee extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_resource_fee";
        $this->sql_map = "engineering/resources/esmdevicefeeSql.php";
        parent::__construct();
    }

    /**
     * 更新费用
     * @param $thisYear
     * @param $thisMonth
     * @return bool|string
     */
    function updateFee_d($thisYear, $thisMonth) {
        set_time_limit(0);

        // 初始化一些数据
        $esmProjectDao = new model_engineering_project_esmproject();
        $createTime = date('Y-m-d H:i:s');

        // 开始新OA的折旧
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
            return util_jsonUtil::iconvGB2UTF('折旧失败，请联系管理员，错误信息为：' . $result['data']['error']) ;
        }

        // 项目id缓存
        $projectIdArr = array();

        // 处理新OA资产折旧信息
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
                if(!empty($rs)){// 只处理有关联项目的数据
                    $projectId = $rs['id'];
                    $projectIdArr[] = $projectId;
                    // 判断当月项目的折旧信息是否存在
                    $rs = $this->find(
                        ' projectId = "' .$projectId . '" AND beginDate = "' . $beginDate . '" AND listId IS NULL'
                        , null, 'id,fee');
                    if(!empty($rs)){
                        if($rs['fee'] != $v){// 决算不同时更新
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

        // 日志写入
        $logDao = new model_engineering_baseinfo_esmlog();
        $logDao->addLog_d(-1, '更新设备决算', count($result) . '|' . $thisMonth);

        /************** 旧决算不再计算 ********************/
//
//        // 删除今天的数据，新OA折旧数据除外
//        $this->_db->query("DELETE FROM " . $this->tbl_name . " WHERE DATE_FORMAT(createTime,'%Y-%m-%d') = '" . day_date . "' AND borrowInfoId IS NOT NULL");
//
//        // get top date
//        $dateInfo = $this->_db->getArray("SELECT MAX(createTime) AS maxDate FROM oa_esm_resource_fee WHERE borrowInfoId IS NOT NULL");
//        $maxDate = $dateInfo[0]['maxDate'] ? date('Y-m-d', strtotime($dateInfo[0]['maxDate'])) : day_date;
//        $maxDateCondition = $dateInfo[0]['maxDate'] ?
//			" AND f.borrowIsOver = 0 AND DATE_FORMAT(f.createTime,'%Y-%m-%d') = '" . $maxDate . "'" : "";
//
//        // 这里取以往有更新过的数据
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
//		// 批量处理数据
//		$this->addDataBatch_d($borrowList, $createTime);
//
//		// 获取没有处理过的数据
//		$sql = "SELECT id FROM device_borrow_order_info WHERE id NOT IN(SELECT DISTINCT borrowInfoId FROM oa_esm_resource_fee WHERE borrowInfoId IS NOT NULL)";
//		$idList = $this->_db->getArray($sql);
//
//		// 获取没有处理的设备决算
//		if ($idList) {
//			$i = 0;
//			$ids = array();
//			$idListLength = count($idList);
//			// 1000 个id处理一边
//			foreach ($idList as $v) {
//				$i ++;
//				$ids[] = $v['id'];
//				if ($i % 500 == 0 || $idListLength == $i) {
//					// 查询从未处理过的数据
//					$borrowList = $this->getNeverDealData_d(day_date, implode(',', $ids));
//					if ($borrowList) {
//						$this->addDataBatch_d($borrowList, $createTime);
//					}
//					$ids = array();
//				}
//			}
//		}

        if (!empty($projectIdArr)) {
            // 转成字符串给后面的业务使用
            $projectIds = implode(',', $projectIdArr);
            // 更新项目预决算
            $esmProjectDao->calProjectFee_d(null, $projectIds);
            $esmProjectDao->calFeeProcess_d(null, $projectIds);
        }

        return 1;
    }

	/**
	 * 获取未处理的数据
	 * @param $day_date
	 * @param $ids
	 * @return mixed
	 */
	function getNeverDealData_d($day_date, $ids) {
		if (empty($ids)) return false;

		// 这边取从来没有取过的数据
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
	 * 根据提供数据来设置决算记录
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
     * 获取项目设备决算 - 实时
     * @param $projectId
     * @param string $from 1 旧系统 / 2 新OA
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
     * 获取决算明细
     * @param $projectId
     * @return array|bool
     */
    function searchDetailList_d($projectId) {
        $sql = "SELECT '初始化导入' AS budgetType, feeEqu AS fee, '-' AS yearMonth
            FROM oa_esm_project WHERE id = $projectId
            UNION ALL
            SELECT '资产折旧' AS budgetType, fee, CONCAT(YEAR(beginDate), '.', MONTH(beginDate)) AS yearMonth
            FROM oa_esm_resource_fee WHERE listId IS NULL AND projectId = $projectId
            UNION ALL
            SELECT '旧设备系统'  AS budgetType, SUM(fee) AS fee, '-' AS yearMonth
            FROM oa_esm_resource_fee WHERE listId <> '' AND projectId = $projectId";
        $data = $this->_db->getArray($sql);

        // 做个合计处理
        $feeAll = 0;
        foreach ($data as $v) {
            $feeAll = bcadd($v['fee'], $feeAll, 2);
        }
        $data[] = array(
            'budgetType' => '合计',
            'fee' => $feeAll
        );

        return $data;
    }

    /**
     * 获取区域设备决算
     * @param $officeIds
     * @param $deprMoney 财务折旧
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

        // 如果查询出来了决算数据，则做占比计算
        if (!empty($data)) {
            // 获取区域
            $officeDao = new model_engineering_officeinfo_officeinfo();
            $officeMap = $officeDao->getOfficeMap_d($officeIds);
            // 占比计算
            $deprAll = 0;
            $all = 100;

            // 第一次循环统计总金额以及加入费用归属部门
            foreach ($data as $k => $v) {
                $data[$k]['projectDepr'] = $v['projectDepr'] = $v['projectDepr'] === null ? 0 : $v['projectDepr'];
                $data[$k]['deptName'] = $officeMap[$v['officeId']]['feeDeptName'];
                $deprAll = bcadd($deprAll, $v['projectDepr'], 2);
            }

            $deduct = $shareAll = bcsub($deprMoney, $deprAll, 2);
            $maxIndex = count($data) - 1;

            // 第二次循环计算占比
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