<?php

/**
 * @author show
 * @Date 2014年12月25日 15:53:13
 * @version 1.0
 * @description:项目决算明细 Model层
 */
class model_engineering_records_esmfielddetail extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_records_fielddetail";
        $this->sql_map = "engineering/records/esmfielddetailSql.php";
        parent::__construct();
    }

    public $feeType = array(
        'budgetPerson' => '人力',
        'budgetField' => '报销支付',
        'budgetEqu' => '设备',
        'budgetOutsourcing' => '外包',
        'budgetOther' => '其他'
    );

    /**
     * 保存决算版本
     * @param $budgetType
     * @param $year
     * @param $month
     * @param $projectCode
     * @return bool
     */
    function saveFeeVersion_d($budgetType, $year, $month, $projectCode)
    {
        // 项目信息获取
        $projectDao = new model_engineering_project_esmproject();

        // 日期设置
        $thisDate = $year . '-' . $month . '-1';
        $lastTime = strtotime($thisDate) - 1;

        // 如果传入了项目编号，那么不进行项目的数据识别。
        if ($projectCode) {
            $condition = array('projectCode' => $projectCode);
        } else {
            // 最后一个数据的匹配
            $lastType = $budgetType == 'budgetOther' ? 'budgetOther,budgetTrial' : $budgetType;
            // 匹配最后一次的更新时间
            $lastOne = $this->find(" budgetType IN(" . util_jsonUtil::strBuild($lastType) . ") AND UNIX_TIMESTAMP(thisDate) <= $lastTime ",
                "ID DESC");
            // 如果最后一次更新是上个月，开始匹配变动项目，否则取全部项目
            if (!empty($lastOne) && strtotime($lastOne['thisDate']) == strtotime($thisDate . '-1 month')) {
                $changeProjectIds = $this->findChangeProjectIds_d($budgetType, $year, $month, $lastOne);
                if ($changeProjectIds) {
                    $changeProjectIds = rtrim($changeProjectIds,",");
                    $condition = " id IN (" . $changeProjectIds . ")";
                } else {
                    $condition = null;
                }
            } else {
                $condition = null;
            }
        }

        $projectData = $projectDao->findAll($condition,
            null, 'id,feeCar,feeFlights,feeEqu,feePerson,contractId,contractType,workRate,productLine,feeSubsidyImport');

        // 日志写入
        $logDao = new model_engineering_baseinfo_esmlog();
        $logDao->addLog_d(-1, '决算数据存档-' . $this->feeType[$budgetType], count($projectData) . '|' . $month);

        // 循环读取项目预决算数据
        if ($projectData) {
            // 数据清除规则
            $deleteCondition = array('thisYear' => $year, 'thisMonth' => $month, 'budgetType' => $budgetType);
            // 如果传入了项目编号，则只处理此项目
            if ($projectCode) {
                $deleteCondition['projectId'] = $projectData[0]['id'];
            }
            $this->delete($deleteCondition);

            // 如果是其他决算，针对PK部分再进行处理
            if ($budgetType == 'budgetOther') {
                $deleteCondition['budgetType'] = 'budgetTrial';
                $this->delete($deleteCondition);
            }

            // 处理数据补偿 - 只针对上期有数据，并且本期没变动的项目
            if (isset($changeProjectIds) && $changeProjectIds && isset($lastOne)) {
                $noChangeData =
                    $this->findAll(" thisDate = '" . $lastOne['thisDate'] . "'
                    AND budgetType = '$budgetType' AND projectId NOT IN(" . $changeProjectIds .")");
                $batchInsert = array();
                foreach ($noChangeData as $v) {
                    unset($v['id']);
                    $v['thisYear'] = $year;
                    $v['thisMonth'] = $month;
                    $v['thisDate'] = $thisDate;
                    $batchInsert[] = $v;
                }
                $this->addBatch_d($batchInsert);

                // PK决算特殊处理
                if ($budgetType == 'budgetOther') {
                    $noChangeData =
                        $this->findAll(" thisDate = '" . $lastOne['thisDate'] . "'
                        AND budgetType = 'budgetTrial' AND projectId NOT IN(" . $changeProjectIds . ")");
                    $batchInsert = array();
                    foreach ($noChangeData as $v) {
                        unset($v['id']);
                        $v['thisYear'] = $year;
                        $v['thisMonth'] = $month;
                        $v['thisDate'] = $thisDate;
                        $batchInsert[] = $v;
                    }
                    $this->addBatch_d($batchInsert);
                }
            }
            // 预决算类
            $budgetDao = new model_engineering_budget_esmbudget();

            foreach ($projectData as $v) {
                // 获取预算
                $budgetData = $budgetDao->getBudgetData_d(array('projectId' => $v['id']));

                // 预算切分
                $budgetCache = $budgetDao->budgetSplit_d($budgetData);

                // 获取决算
                $feeData = $budgetDao->getFee_d($v['id']);

                // 决算切分
                $feeCache = $budgetDao->feeSplit_d($feeData);

                switch ($budgetType) {
                    case 'budgetPerson' :
                        // 人力成本
                        $data = $budgetDao->getPersonFee_d($budgetCache[0], $feeCache[0], $v);
                        break;
                    case 'budgetField' :
                        // 报销支付成本
                        $data = $budgetDao->getFieldFee_d($budgetCache[1], $feeCache[1], $v);
                        break;
                    case 'budgetEqu' :
                        // 设备成本
                        $data = $budgetDao->getEquFee_d($budgetCache[2], $feeCache[2], $v);
                        break;
                    case 'budgetOutsourcing' :
                        // 外包成本
                        $data = $budgetDao->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $v);
                        break;
                    case 'budgetOther' :
                        // 其他成本
                        $data = $budgetDao->getOtherFee_d($budgetCache[4], $feeCache[4], $v);
                        break;
                    default :
                        $data = array();
                }

                // 如果存在数据，就插入
                if ($data) {
                    $batchInsert = array();
                    foreach ($data as $vi) {
                        if ($vi['id'] == 'noId' || $vi['actFee'] == 0) {
                            continue;
                        } else {
                            $batchInsert[] = array(
                                'projectId' => $v['id'],
                                'thisYear' => $year,
                                'thisMonth' => $month,
                                'thisDate' => $thisDate,
                                'budgetName' => $vi['budgetName'],
                                'budgetType' => $vi['budgetType'],
                                'fee' => $vi['actFee'],
                                'feeExpense' => isset($vi['feeExpense']) ? $vi['feeExpense'] : 0,
                                'feeCostMaintain' => isset($vi['feeCostMaintain']) ? $vi['feeCostMaintain'] : 0,
                                'feePayables' => isset($vi['feePayables']) ? $vi['feePayables'] : 0,
                                'feeCar' => isset($vi['feeCar']) ? $vi['feeCar'] : 0,
                                'feeFlights' => isset($vi['feeFlights']) ? $vi['feeFlights'] : 0,
                                'feePK' => isset($vi['feePK']) ? $vi['feePK'] : 0,
                                'feeOther' => isset($vi['feeOther']) ? $vi['feeOther'] : 0,
                                'feeEqu' => isset($vi['feeEqu']) ? $vi['feeEqu'] : 0,
                                'feeEquImport' => isset($vi['feeEquImport']) ? $vi['feeEquImport'] : 0,
                                'feePerson' => isset($vi['feePerson']) ? $vi['feePerson'] : 0,
                                'feeSubsidy' => isset($vi['feeSubsidy']) ? $vi['feeSubsidy'] : 0,
                                'feeSubsidyImport' => isset($vi['feeSubsidyImport']) ? $vi['feeSubsidyImport'] : 0,
                                'feeOutsourcing' => isset($vi['feeOutsourcing']) ? $vi['feeOutsourcing'] : 0
                            );
                        }
                    }
                    $this->addBatch_d($batchInsert);
                }
            }
            return 1;
        }

        return 0;
    }

    /**
     * 匹配变化了的项目ID
     * @param $budgetType
     * @param $year
     * @param $month
     * @param $lastOne
     * @return string
     */
    function findChangeProjectIds_d($budgetType, $year, $month, $lastOne)
    {
        // 项目id
        $projectIdArr = array();

        // 根据类型来判定对应项目
        switch ($budgetType) {
            case 'budgetPerson' :
                // 查询补贴的部分
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('subsidy','subsidyProvision')
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // 查询工资的部分
                $sql = "SELECT projectId FROM oa_esm_project_personfee WHERE thisYear = $year AND thisMonth = $month";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            case 'budgetField' :
                // 查询报销、支付、租车、机票
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('field','payables','rentCar','flightsShare')
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            case 'budgetEqu' :
                // 查询设备决算
                $beginTime = strtotime($year . "-" . $month . "-01");
                $sql = "SELECT projectId FROM oa_esm_resource_fee WHERE UNIX_TIMESTAMP(beginDate) = $beginTime AND listId IS NULL GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            case 'budgetOutsourcing' :
                // 外包决算会有费用维护自动更新到设备预算表，所以这里不做处理
                break;
            case 'budgetOther' :
                // 匹配出存在的PK项目
                $pkProjectIds = array();
                $sql = "SELECT id FROM oa_esm_project WHERE contractType = 'GCXMYD-04'";
                $data = $this->_db->getArray($sql);
                foreach ($data as $v) {
                    $pkProjectIds[] = $v['id'];
                }
                $pkProjectIds = implode(',', $pkProjectIds);

                // 查询补贴的部分
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('subsidy','subsidyProvision')
                        AND projectId IN($pkProjectIds)
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // 查询工资的部分
                $sql = "SELECT projectId FROM oa_esm_project_personfee WHERE thisYear = $year AND thisMonth = $month AND projectId IN($pkProjectIds)";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // 查询报销、支付、租车、机票
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('field','payables','rentCar','flightsShare')
                         AND projectId IN($pkProjectIds)
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // 查询报销、支付、租车、机票
                $beginTime = strtotime($year . "-" . $month . "-01");
                $sql = "SELECT projectId FROM oa_esm_resource_fee WHERE UNIX_TIMESTAMP(beginDate) = $beginTime
                    AND projectId IN($pkProjectIds) AND listId IS NULL GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            default :
        }

        // 生成检测的时间戳
        $checkBeginTime = strtotime($lastOne['thisDate']); // 开始时间戳
        $checkEndTime = strtotime(date("Y-m-d", time())) + 86399; // 结束时间戳

        // 规则一：时间范围内发生了变更
        $sql = "SELECT projectId FROM oa_esm_change_baseinfo WHERE ExaStatus = '完成'
            AND UNIX_TIMESTAMP(ExaDT) BETWEEN $checkBeginTime AND $checkEndTime";
        $changeProjectData = $this->_db->getArray($sql);
        $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $changeProjectData);

        // 规则二：匹配变更了预算的项目
        $sql = "SELECT projectId FROM oa_esm_project_budget WHERE budgetType = '$budgetType'
            AND UNIX_TIMESTAMP(updateTime) BETWEEN $checkBeginTime AND $checkEndTime GROUP BY projectId";
        $changeBudgetData = $this->_db->getArray($sql);
        $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $changeBudgetData);

        if (!empty($projectIdArr)) {
            // 规则三：匹配PK项目，对应的正式项目也需要更新
            $sql = "SELECT m.projectId AS projectId FROM oa_esm_project c
            LEFT JOIN oa_esm_project_mapping m ON c.id = m.pkProjectId
            WHERE c.contractType = 'GCXMYD-04' AND c.id IN(
            " . implode(',', $projectIdArr) . ")";
            $contractProjectFromPK = $this->_db->getArray($sql);
            $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $contractProjectFromPK);
        }

        return implode(',', $projectIdArr);
    }

    function setProjectIdArr_d($projectIdArr, $data)
    {
        foreach ($data as $v) {
            if (!in_array($v['projectId'], $projectIdArr)) {
                $projectIdArr[] = $v['projectId'];
            }
        }
        return $projectIdArr;
    }

    function en2cn($en)
    {
        switch ($en) {
            case 'budgetField' :
                $cn = '报销支付成本合计';
                break;
            case 'budgetOutsourcing' :
                $cn = '外包成本合计';
                break;
            case 'budgetEqu' :
                $cn = '设备成本合计';
                break;
            case 'budgetTrial' :
            case 'budgetOther' :
                $cn = '其他成本合计';
                break;
            case 'budgetPerson' :
                $cn = '人力成本合计';
                break;
            default :
                $cn = '';
        }
        return $cn;
    }

    /**
     * 差异数据查询
     * @param $object
     * @return array|bool
     */
    function viewHistory_d($object)
    {
        $beginSql = "";
        if (!empty($object['begin']) && $object['begin'] != $object['end']) {
            $beginSql = "UNION ALL
                SELECT projectId,budgetName,budgetType,-fee,
                    (CASE
                            WHEN budgetType = 'budgetPerson' THEN 0
                            WHEN budgetType = 'budgetField' THEN 1
                            WHEN budgetType = 'budgetEqu' THEN 2
                            WHEN budgetType = 'budgetOutsourcing' THEN 3
                            ELSE 4
                    END) AS sortNo FROM oa_esm_records_fielddetail WHERE projectId = " . $object['projectId'] . "
                AND thisDate = '" . $object['begin'] . "'";
        }
        $sql = "SELECT
            budgetType AS id,budgetName,budgetType,SUM(fee) AS fee FROM
            (
                SELECT projectId,budgetName,budgetType,fee,
                    (CASE
                            WHEN budgetType = 'budgetPerson' THEN 0
                            WHEN budgetType = 'budgetField' THEN 1
                            WHEN budgetType = 'budgetEqu' THEN 2
                            WHEN budgetType = 'budgetOutsourcing' THEN 3
                            ELSE 4
                    END) AS sortNo FROM oa_esm_records_fielddetail WHERE projectId = " . $object['projectId'] . "
                AND thisDate = '" . $object['end'] . "'
                $beginSql
            ) c
            GROUP BY budgetName,budgetType
            HAVING fee <> 0
            ORDER BY sortNo,budgetType";
        $data = $this->_db->getArray($sql);

        // 最终返回结果
        $result = array();

        // 数据分类
        if ($data) {
            $tmp = '';
            $feeCount = 0;
            $feeAll = 0;
            $maxIndex = count($data) - 1;
            foreach ($data as $k => $v) {
                if ($tmp != $v['budgetType']) {
                    if (!(($tmp == 'budgetOther' && $v['budgetType'] == 'budgetTrial')
                        || ($tmp == 'budgetTrial' && $v['budgetType'] == 'budgetOther'))
                    ) {
                        if ($tmp) {
                            $result[] = array(
                                'id' => 'noId',
                                'budgetType' => $tmp,
                                'budgetName' => $this->en2cn($tmp),
                                'fee' => $feeCount
                            );
                        }
                        $tmp = $v['budgetType'];
                        $feeAll = bcadd($feeAll, $feeCount, 2);
                        $feeCount = 0;
                    }
                }

                // 加入数据
                $result[] = $v;

                // 小计
                $feeCount = bcadd($feeCount, $v['fee'], 2);

                // 合计列
                if ($k == $maxIndex) {
                    $result[] = array(
                        'id' => 'noId',
                        'budgetType' => $tmp,
                        'budgetName' => $this->en2cn($tmp),
                        'fee' => $feeCount
                    );
                    $feeAll = bcadd($feeAll, $feeCount, 2);
                }
            }

            $result[] = array(
                'id' => 'noId',
                'budgetType' => $tmp,
                'budgetName' => '合计',
                'fee' => $feeAll
            );
        }

        return $result;
    }

    /**
     *
     * @param $feeBeginDate
     * @param $feeEndDate
     * @param null $projectIds
     * @return array|bool
     */
    function getHistory_d($feeBeginDate, $feeEndDate, $projectIds = null)
    {
        $beginSql = "";
        if ($feeBeginDate != $feeEndDate) {
            $beginSql = "UNION ALL
                SELECT projectId, -feePayables, -feeFlights, -feeCar,
                    -feeCostMaintain, -feeEqu, -feeEquImport, -feeExpense,
                    -feeOther, -feeOutsourcing, -feePerson, -feePK,
                    -feeSubsidy, -feeSubsidyImport
                FROM oa_esm_records_fielddetail WHERE projectId IN(" . $projectIds . ")
                AND thisDate = '" . $feeBeginDate . "'";
        }
        // 查出的数据做了个人为字段转移
        // feeExpense => feeField（报销决算）
        // feeCostMaintain => feeFieldImport（费用维护决算）
        $sql = "SELECT
            projectId, SUM(feeExpense) AS feeField, SUM(feeCostMaintain) AS feeFieldImport,
            SUM(feePayables) AS feePayables, SUM(feePayables) AS feeFlights, SUM(feeCar) AS feeCar,
            SUM(feeEqu) AS feeEqu, SUM(feeEquImport) AS feeEquImport,
            SUM(feeOther) AS feeOther, SUM(feeOutsourcing) AS feeOutsourcing,
            SUM(feePerson) AS feePerson, SUM(feePK) AS feePK, SUM(feeSubsidy) AS feeSubsidy,
            SUM(feeSubsidyImport) AS feeSubsidyImport
            FROM
            (
                SELECT projectId, feePayables, feeFlights, feeCar,
                    feeCostMaintain, feeEqu, feeEquImport, feeExpense,
                    feeOther, feeOutsourcing, feePerson, feePK,
                    feeSubsidy, feeSubsidyImport
                FROM oa_esm_records_fielddetail WHERE projectId IN(" . $projectIds . ")
                AND thisDate = '" . $feeEndDate . "'
                $beginSql
            ) c
            GROUP BY projectId";
        $data = $this->_db->getArray($sql);

        $result = array();

        // 判定存在数据，则进行转换
        if ($data[0]['projectId']) {
            foreach ($data as $v) {
                $feeAll = 0;
                foreach ($v as $ki => $vi) {
                    if ($ki != 'projectId') {
                        $feeAll = bcadd($feeAll, $vi, 2);
                    }
                }
                $v['feeAll'] = $feeAll;
                $result[$v['projectId']] = $v;
            }
        }

        return $result;
    }
}