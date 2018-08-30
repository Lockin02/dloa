<?php

/**
 * 部门费用表
 * Class model_bi_deptFee_deptFee
 */
class model_bi_deptFee_deptFee extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_bi_dept_fee";
        $this->sql_map = "bi/deptFee/deptFeeSql.php";
        parent::__construct();
    }

    public $feeType = array(
        'person' => '人力',
        'expense' => '报销',
        'pay' => '支付',
        'pk' => 'PK'
    );


    /**
     * 更新历史费用数据
     * @param $orgParam
     * @param $newParam
     * @throws Exception
     */
    function updateOldData_d($orgParam, $newParam)
    {
        $sql = "UPDATE $this->tbl_name SET ";

        $i = 0;
        foreach ($newParam as $k => $v) {
            $sql .= $i ? "," . $k . " = '" . $v . "'" : $k . " = '" . $v . "'";
            $i = 1;
        }

        $sql .= " WHERE ";

        $j = 0;
        foreach ($orgParam as $k => $v) {
            $sql .= $j ? " AND " . $k . " = '" . $v . "'" : $k . " = '" . $v . "'";
            $j = 1;
        }
        $this->_db->query($sql);
    }

    /**
     * 决算更新
     * @param $year
     * @param $month
     * @param $feeType
     * @return string
     */
    function updateFee_d($year, $month, $feeType)
    {
        // 取到的数据
        $data = array();
        // 费用类型中文
        $feeTypeName = $this->feeType[$feeType];

        switch ($feeType) {
            case 'person':
                $data = $this->getPerson_d($year, $month);
                break;
            case 'expense':
                $data = $this->getExpense_d($year, $month);
                break;
            case 'pay':
                $data = $this->getPay_d($year, $month);
                break;
            case 'pk':
                $data = $this->getPK_d($year, $month);
                break;
            default:
        }

        // 如果为空时，返回提示
        if (empty($data)) {
            return array(
                'feeType' => $feeType,
                'feeTypeName' => $feeTypeName,
                'rst' => 'none',
                'feeAll' => 0,
                'ignore' => 0,
                'ignoreInfo' => ''
            );
        } else {
            $time = strtotime($year . '-' . $month . '-1'); // 时间

            // 删除旧数据
            $this->delete(array(
                'thisTime' => $time, 'costType' => $feeTypeName, 'isImport' => 0
            ));

            // 部门映射获取
            $deptMappingDao = new model_bi_deptFee_deptMapping();
            $deptMapping = $deptMappingDao->getMapping_d($feeTypeName);

            // 做个汇总
            $feeAll = 0; // 更新的金额
            $ignore = 0; // 忽略的金额
            $ignoreInfo = array(); // 忽略数据

            // 循环插入数据
            foreach ($data as $k => $v) {
                // 插入数据
                $inArr = array(
                    'thisYear' => $year, 'thisMonth' => $month, 'thisTime' => $time,
                    'costType' => $feeTypeName, 'isImport' => 0, 'fee' => $v['fee']
                );

                // 如果数据源有板块，则将部门和板块拼接在一起处理
                $dept = isset($v['module']) && $v['module'] ?
                    $v['deptName'] . '_' . $v['module'] : $v['deptName'];

                // 匹配映射，匹配商了，优先处理，匹配不先放置
                if (isset($deptMapping[$dept])) {
                    $inArr = array_merge($inArr, $deptMapping[$dept]);
                    $inArr['orgDept'] = $dept;
                    $this->add_d($inArr);
                } elseif (isset($deptMapping[$v['deptName']])) {
                    $inArr = array_merge($inArr, $deptMapping[$v['deptName']]);
                    $inArr['orgDept'] = $v['deptName'];
                    $this->add_d($inArr);
                } else {
                    $ignore = bcadd($ignore, $v['fee'], 2);
                    $ignoreInfo[] = $dept . "|" . $v['fee'];
                    continue;
                }
                $feeAll = bcadd($feeAll, $v['fee'], 2);
            }

            return array(
                'feeType' => $feeType,
                'feeTypeName' => $feeTypeName,
                'rst' => 'ok',
                'feeAll' => $feeAll,
                'ignore' => $ignore,
                'ignoreInfo' => implode(',', $ignoreInfo)
            );
        }
    }

    /**
     * 获取明细i数据
     * @param $year
     * @param $month
     * @param $feeTypes
     * @return array
     */
    function getDetail_d($year, $month, $feeTypes)
    {
        $data = array();
        $feeTypeArr = explode(',', $feeTypes);
        foreach ($feeTypeArr as $feeType) {
            switch ($feeType) {
                case 'person':
                    $data[] = $this->getPerson_d($year, $month);
                    break;
                case 'expense':
                    $data[] = $this->getExpenseDetail_d($year, $month);
                    break;
                case 'pay':
                    $data[] = $this->getPayDetail_d($year, $month);
                    break;
                case 'pk':
                    $data[] = $this->getPKDetail_d($year, $month);
                    break;
                default:
                    $data[] = array();
            }
        }
        return $data;
    }

    /**
     * 获取人力费用
     * @param $year
     * @param $month
     * @return array
     */
    function getPerson_d($year, $month)
    {
        // 从薪资模块提取数据
        $gl = new includes_class_global();
        return $gl->get_salaryDept_info($year, $month);
    }

    /**
     * 报销费用获取
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getExpense_d($year, $month)
    {
        // 获取过滤掉的费用项
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // 过滤不适用的费用项
        $filterSql = $filterType ? " AND d.CostType NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // 查询脚本
        $sql = "SELECT
                l.CostBelongDeptName AS deptName,SUM(d.CostMoney) AS fee,d.moduleName AS module
            FROM
            (
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '完成'
                    AND l.DetailType IN(1,3,5)
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
                UNION ALL
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '完成'
                    AND l.DetailType = 4 AND l.projectId = 0
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
            ) l
            INNER JOIN oa_finance_costshare d ON d.BillNo = l.BillNo
            WHERE 1 $filterSql
            GROUP BY l.CostBelongDeptName,d.moduleName";
        return $this->_db->getArray($sql);
    }

    /**
     * 报销明细获取
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getExpenseDetail_d($year, $month) {
        // 获取过滤掉的费用项
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // 过滤不适用的费用项
        $filterSql = $filterType ? " AND d.CostType NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        $sql = "SELECT
                l.CostBelongDeptName AS deptName,l.BillNo,SUM(d.CostMoney) AS fee,d.moduleName AS module
            FROM
            (
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '完成'
                    AND l.DetailType IN(1,3,5)
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
                UNION ALL
                SELECT CostBelongDeptName,BillNo FROM cost_summary_list l
                WHERE l.isNew = 1 AND l.Status = '完成'
                    AND l.DetailType = 4 AND l.projectId = 0
                    AND YEAR(l.PayDT) = " . $year . " AND MONTH(l.PayDT) = " . $month . "
            ) l
            INNER JOIN oa_finance_costshare d ON d.BillNo = l.BillNo
            WHERE 1 $filterSql
            GROUP BY l.CostBelongDeptName,l.BillNo,d.moduleName";
        return $this->_db->getArray($sql);
    }

    /**
     * 获取支付费用
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getPay_d($year, $month)
    {
        // 入账周期
        $period = $year . "." . $month;

        // 获取过滤掉的费用项
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // 过滤不适用的费用项
        $filterSql = $filterType ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // 查询脚本
        $sql = "SELECT
		    belongDeptName AS deptName, SUM(costMoney) AS fee, moduleName AS module
        FROM (
            SELECT
                    belongDeptName, costMoney, moduleName
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType IN(1,3,5) AND inPeriod = '" . $period . "' $filterSql
            UNION ALL
            SELECT
                    belongDeptName, costMoney, moduleName
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType = 4 AND projectId = 0 AND inPeriod = '" . $period . "' $filterSql
            ) c
        GROUP BY belongDeptName, moduleName";
        return $this->_db->getArray($sql);
    }

    /**
     * 获取支付费用
     * @param $year
     * @param $month
     * @return array|bool
     */
    function getPayDetail_d($year, $month)
    {
        // 入账周期
        $period = $year . "." . $month;

        // 获取过滤掉的费用项
        $otherDatasDao = new model_common_otherdatas();
        $filterType = $otherDatasDao->getConfig('deptFee_filter_costType');

        // 过滤不适用的费用项
        $filterSql = $filterType ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($filterType) . ")" : "";

        // 查询脚本
        $sql = "SELECT
            belongDeptName AS deptName, SUM(costMoney) AS fee, moduleName AS module, objCode
        FROM (
            SELECT
                    belongDeptName, costMoney, moduleName, objCode
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType IN(1,3,5) AND inPeriod = '" . $period . "' $filterSql
            UNION ALL
            SELECT
                    belongDeptName, costMoney, moduleName, objCode
                FROM oa_finance_cost
                WHERE isTemp = 0 AND isDel = 0 AND auditStatus = 1
                    AND DetailType = 4 AND projectId = 0 AND inPeriod = '" . $period . "' $filterSql
            ) c
        GROUP BY belongDeptName, moduleName, objCode";
        return $this->_db->getArray($sql);
    }

    /**
     * 获取PK项目部门费用
     * @param $year
     * @param $month
     * @return array
     */
    function getPK_d($year, $month)
    {
        // 获取前一个月
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;

        // 返回的数据
        $rst = array();

        // 获取项目对应的映射部门
        $deptMappingDao = new model_bi_deptFee_deptMapping();
        $deptMapping = $deptMappingDao->getProjectMapping_d();
        $deptMapping = $deptMappingDao->dealPKMapping_d($deptMapping);

        // 当月转正项目ID
        $turnPKProjectIdList = $this->getTurnPKProjectIds_d($year, $month);

        // 如果存在本月转正项目ID，则获取这些项目的当月决算与所有决算
        if (!empty($turnPKProjectIdList)) {
            $turnPKProjectIds = implode(',', $turnPKProjectIdList);
            // 获取本月转正项目本月决算
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);
            // 如果查询到数据，则开始转换
            if ($rs) {
                foreach ($rs as $v) {
                    $dept = isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "";
                    if ($dept) {
                        // 如果已经存在，则叠加
                        if (isset($rst[$dept])) {
                            $rst[$dept] = bcadd($v['fee'], $rst[$dept], 2);
                        } else {
                            $rst[$dept] = $v['fee'];
                        }
                    }
                }
            }

            // 获取本月转正项目全部决算
            $sql = "SELECT
                    projectId, SUM(fee) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ") AND (thisYear = $year AND thisMonth = $month)
                GROUP BY projectId";
            $rs = $this->_db->getArray($sql);
            // 如果查询到数据，则开始转换
            if ($rs) {
                foreach ($rs as $v) {
                    $dept = isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "";
                    if ($dept) {
                        // 如果已经存在，则叠加
                        if (isset($rst[$dept])) {
                            $rst[$dept] = bcsub($rst[$dept], $v['fee'], 2);
                        } else {
                            $rst[$dept] = -$v['fee'];
                        }
                    }
                }
            }
        }
        // 未转正项目ID
        $PKProjectIdList = $this->getPKProjectIds_d($year, $month);

        // 如果存在未转正项目ID，则获取这些项目的当月决算
        if (!empty($PKProjectIdList)) {
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . implode(',', $PKProjectIdList) . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);

            // 如果查询到数据，则开始转换
            if ($rs) {
                foreach ($rs as $v) {
                    $dept = isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "";
                    if ($dept) {
                        // 如果已经存在，则叠加
                        if (isset($rst[$dept])) {
                            $rst[$dept] = bcadd($v['fee'], $rst[$dept], 2);
                        } else {
                            $rst[$dept] = $v['fee'];
                        }
                    }
                }
            }
        }

        $rst2 = array();
        foreach ($rst as $k => $v) {
            $rst2[] = array(
                'deptName' => $k,
                'fee' => $v
            );
        }
        return $rst2;
    }

    /**
     * 获取PK项目部门费用 - 明细
     * @param $year
     * @param $month
     * @return array
     */
    function getPKDetail_d($year, $month)
    {
        // 获取前一个月
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;

        // 返回的数据
        $rst = array();

        // 获取项目对应的映射部门
        $deptMappingDao = new model_bi_deptFee_deptMapping();
        $deptMapping = $deptMappingDao->getProjectMapping_d();
        $projectCodeMapping = $deptMappingDao->getProjectCodeMapping_d();

        // 当月转正项目ID
        $turnPKProjectIdList = $this->getTurnPKProjectIds_d($year, $month);

        // 如果存在本月转正项目ID，则获取这些项目的当月决算与所有决算
        if (!empty($turnPKProjectIdList)) {
            $turnPKProjectIds = implode(',', $turnPKProjectIdList);
            // 获取本月转正项目本月决算
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);
            // 如果查询到数据，则开始转换
            if ($rs) {
                foreach ($rs as $v) {
                    $rst[$v['projectId']] = array(
                        'projectCode' => $projectCodeMapping[$v['projectId']],
                        'deptName' => isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "",
                        'feeMonth' => $v['fee'],
                        'feeAll' => 0,
                        'feeNotTurn' => 0
                    );
                }
            }

            // 获取本月转正项目全部决算
            $sql = "SELECT
                    projectId, SUM(fee) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . $turnPKProjectIds . ") AND (thisYear = $year AND thisMonth = $month)
                GROUP BY projectId";
            $rs = $this->_db->getArray($sql);
            // 如果查询到数据，则开始转换
            if ($rs) {
                foreach ($rs as $v) {
                    if (isset($rst[$v['projectId']])) {
                        $rst[$v['projectId']]['feeAll'] = $v['fee'];
                    } else {
                        $rst[$v['projectId']] = array(
                            'projectCode' => $projectCodeMapping[$v['projectId']],
                            'deptName' => isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "",
                            'feeMonth' => 0,
                            'feeAll' => $v['fee'],
                            'feeNotTurn' => 0
                        );
                    }
                }
            }
        }
        // 未转正项目ID
        $PKProjectIdList = $this->getPKProjectIds_d($year, $month);

        // 如果存在未转正项目ID，则获取这些项目的当月决算
        if (!empty($PKProjectIdList)) {
            $sql = "SELECT
                    projectId,
                    SUM(IF(thisYear = $year AND thisMonth = $month, fee, 0)) -
                        SUM(IF(thisYear = $prevYear AND thisMonth = $prevMonth, fee, 0)) AS fee
                FROM oa_esm_records_fielddetail
                WHERE projectId IN(" . implode(',', $PKProjectIdList) . ")
                    AND ((thisYear = $prevYear AND thisMonth = $prevMonth)
                        OR (thisYear = $year AND thisMonth = $month))
                GROUP BY projectId HAVING fee <> 0";
            $rs = $this->_db->getArray($sql);

            // 如果查询到数据，则开始转换
            if ($rs) {
                foreach ($rs as $v) {
                    if (isset($rst[$v['projectId']])) {
                        $rst[$v['projectId']]['feeNotTurn'] = $v['fee'];
                    } else {
                        $rst[$v['projectId']] = array(
                            'projectCode' => $projectCodeMapping[$v['projectId']],
                            'deptName' => isset($deptMapping[$v['projectId']]) ? $deptMapping[$v['projectId']] : "",
                            'feeMonth' => 0,
                            'feeAll' => 0,
                            'feeNotTurn' => $v['fee']
                        );
                    }
                }
            }
        }
        $rst2 = array();
        foreach ($rst as $v) {
            $rst2[] = $v;
        }
        return $rst2;
    }

    /**
     * 查询本月转正的PK项目
     * @param $year
     * @param $month
     * @return array
     */
    function getTurnPKProjectIds_d($year, $month)
    {
        // 年月，用于匹配项目转正时间
        $yearMonth = $year . str_pad($month, 2, '0', STR_PAD_LEFT);

        $sql = "SELECT id FROM oa_esm_project
            WHERE
            contractType = 'GCXMYD-04'
            AND contractId IN(
				SELECT id FROM oa_trialproject_trialproject
				WHERE turnStatus = '已转正' AND DATE_FORMAT(turnDate, '%Y%m') = $yearMonth
		    )";
        $turnPKProject = $this->_db->getArray($sql);

        // 当月转正项目ID
        $turnPKProjectIdList = array();

        // 如果存在本月转正项目，则开始拼装条件
        if (!empty($turnPKProject)) {
            foreach ($turnPKProject as $v) {
                $turnPKProjectIdList[] = $v['id'];
            }
        }
        return $turnPKProjectIdList;
    }

    /**
     * 获取未转正PK项目
     * @param $year
     * @param $month
     * @return array
     */
    function getPKProjectIds_d($year, $month)
    {
        // 年月，用于匹配项目转正时间
        $yearMonth = $year . str_pad($month, 2, '0', STR_PAD_LEFT);

        // 获取未转正PK项目
        $sql = "SELECT id FROM oa_esm_project
            WHERE
                contractType = 'GCXMYD-04'
                AND contractId IN(
                    SELECT id FROM oa_trialproject_trialproject
                    WHERE ExaStatus = '完成'
                    AND (turnStatus <> '已转正' OR (turnStatus = '已转正' AND DATE_FORMAT(turnDate, '%Y%m') > $yearMonth))
                )";
        $PKProject = $this->_db->getArray($sql);

        // 当月转正项目ID
        $PKProjectIdList = array();

        // 如果存在本月转正项目，则开始拼装条件
        if (!empty($PKProject)) {
            foreach ($PKProject as $v) {
                $PKProjectIdList[] = $v['id'];
            }
        }
        return $PKProjectIdList;
    }

    /**
     * 获取费用类型列表
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @return array|bool
     */
    function getCostTypeList_d($beginYear, $beginMonth, $endYear, $endMonth)
    {
//        $beginTime = strtotime($beginYear . '-' . $beginMonth . '-1');
//        $endTime = strtotime($endYear . '-' . $endMonth . '-1');
//
//        $sql = "SELECT costType FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginTime AND $endTime GROUP BY costType";
//        $data = $this->_db->getArray($sql);
//
//        // 拼接一个索引再返回
//        foreach ($data as $k => $v) {
//            $data[$k]['key'] = 'c' . $k;
//        }
        // 按新版要求改成固定列头
        $data = array(
            array('costType' => '人力', 'key' => 'c0'),
            array('costType' => '报销', 'key' => 'c1'),
            array('costType' => '支付', 'key' => 'c2'),
            array('costType' => '折旧及分摊', 'key' => 'c3'),
            array('costType' => 'PK', 'key' => 'c4'),
            array('costType' => '税金', 'key' => 'c5'),
            array('costType' => '年终奖', 'key' => 'c6'),
            array('costType' => '其他', 'key' => 'c7'),
            array('costType' => '修正值', 'key' => 'c8')
        );

        return $data;
    }

    /**
     * 获取汇总列表数据
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @return array|bool
     */
    function summaryList_d($beginYear, $beginMonth, $endYear, $endMonth)
    {
        // 获取部门显示层级
        $otherDatasDao = new model_common_otherdatas();
        $deptLevel = $otherDatasDao->getConfig('deptFee_filter_deptLevel');

        // 如果没有设置部门显示层级，直接返回空
        if ($deptLevel) {
            // 部门字段数组
            $keyArr = array('c.business', 'c.secondDept', 'c.thirdDept', 'c.fourthDept');
            $keyStr = implode(',', array_slice($keyArr, 0, $deptLevel));
            $conditionArr = array("c.business = f.business", "c.secondDept = f.secondDept",
                "c.thirdDept = f.thirdDept", "c.fourthDept = f.fourthDept");
            $conditionStr = implode(' AND ', array_slice($conditionArr, 0, $deptLevel));

            // 时间处理
            $beginTime = strtotime($beginYear . '-' . $beginMonth . '-1');
            $endTime = strtotime($endYear . '-' . $endMonth . '-1');
            $sql = "SELECT c.costCategory,c.productLine,$keyStr,f.budget,f.fee FROM
                (
                    SELECT c.sortOrder,c.costCategory,c.productLine,c.filterStartDate,c.filterEndDate,$keyStr
                    FROM oa_bi_dept_mapping c GROUP BY $keyStr,c.costCategory,c.productLine
                ) c LEFT JOIN
                (
                    SELECT
                        $keyStr,SUM(c.budget) AS budget,SUM(c.fee) AS fee
                     FROM
                    (
                        SELECT
                            $keyStr,(budget) AS budget,(fee) AS fee
                        FROM
                            oa_bi_dept_fee c
                        WHERE
                            thisTime BETWEEN $beginTime AND $endTime
                        UNION ALL
                        SELECT
                            $keyStr,0 AS budget,(c.feeIn) AS fee
                        FROM
                            oa_bi_asset_depreciation s
                            LEFT JOIN oa_bi_asset_share c ON s.id = c.deprId
                        WHERE
                            thisTime BETWEEN $beginTime AND $endTime
                    ) c
                        GROUP BY $keyStr
                ) f ON $conditionStr 
                where
                 ((c.filterStartDate is null or c.filterStartDate = '') and (c.filterEndDate is null or c.filterEndDate = ''))
								or
                IF(c.filterStartDate <> '' and c.filterEndDate <> '',
                      ((c.filterStartDate BETWEEN $beginTime AND $endTime) and c.filterEndDate BETWEEN $beginTime AND $endTime),
						IF(c.filterStartDate <> '',
						  ((c.filterStartDate BETWEEN $beginTime AND $endTime)),
						  (c.filterEndDate BETWEEN $beginTime AND $endTime)
						)
				  )
                ORDER BY c.sortOrder,$keyStr";
            $data = $this->_db->getArray($sql);

            // 拼接一个索引再返回
            foreach ($data as $k => $v) {
                $data[$k]['budget'] = $v['budget'] ? $v['budget'] : 0.00;
                $data[$k]['fee'] = $v['fee'] ? $v['fee'] : 0.00;
                $data[$k]['feeProcess'] = $v['budget'] ?
                    round(bcmul(bcdiv($v['fee'], $v['budget'], 6), 100, 4), 2) : 0.00;
            }
            return $data;
        } else {
            return array();
        }
    }

    /**
     * @param $rowNum
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @param $business
     * @param $secondDept
     * @param $thirdDept
     * @param $fourthDept
     * @return array|bool
     */
    function summaryDetail_d($rowNum, $beginYear, $beginMonth, $endYear, $endMonth, $business, $secondDept,
                             $thirdDept, $fourthDept)
    {
        // 获取部门显示层级
        $otherDatasDao = new model_common_otherdatas();
        $deptLevel = $otherDatasDao->getConfig('deptFee_filter_deptLevel');

        // 如果没有设置部门显示层级，直接返回空
        if ($deptLevel) {
            $beginTime = strtotime($beginYear . '-' . $beginMonth . '-1');
            $endTime = strtotime($endYear . '-' . $endMonth . '-1');

            // 部门字段数组
            $keyArr = array(
                "AND c.business = '$business'", "AND c.secondDept = '$secondDept'",
                "AND c.thirdDept = '$thirdDept'", "AND c.fourthDept = '$fourthDept'"
            );
            $keyStr = implode(' ', array_slice($keyArr, 0, $deptLevel));

            $sql = "SELECT
                costType,SUM(fee) AS fee
            FROM
                oa_bi_dept_fee c
            WHERE thisTime BETWEEN $beginTime AND $endTime
                $keyStr
            GROUP BY costType";
            $data = $this->_db->getArray($sql);

            $rst = array();

            // 拼接一个索引再返回
            foreach ($data as $v) {
                $rst[$v['costType']] = $v['fee'];
            }

            // 获取设备决算
            $sql = "SELECT SUM(c.feeIn) AS fee
                FROM
                    oa_bi_asset_depreciation s
                    LEFT JOIN oa_bi_asset_share c ON s.id = c.deprId
                    $keyStr
                WHERE thisTime BETWEEN $beginTime AND $endTime $keyStr";
            $deviceFeeRow = $this->get_one($sql);

            if ($deviceFeeRow) {
                $rst['折旧及分摊'] = isset($rst['折旧及分摊']) ? bcadd($rst['折旧及分摊'], $deviceFeeRow['fee'], 2) : $deviceFeeRow['fee'];
            }

            return array('rowNum' => $rowNum, 'rows' => $rst);
        } else {
            return array('rowNum' => $rowNum, 'rows' => array());
        }
    }

    /**
     * 其他费用汇总
     * @param $beginYear
     * @param $beginMonth
     * @param $endYear
     * @param $endMonth
     * @param $isImport
     * @return array|bool
     */
    function otherFeeSummary_d($beginYear, $beginMonth, $endYear, $endMonth, $isImport)
    {
        $beginStamp = strtotime($beginYear . "-" . $beginMonth . "-1");
        $endStamp = strtotime($endYear . "-" . $endMonth . "-1");

        // 查询脚本 - 合计部分
        $sql = "SELECT business,secondDept,thirdDept,fourthDept,costType,SUM(budget) AS budget, SUM(fee) AS fee
                FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginStamp AND $endStamp AND isImport = $isImport
                GROUP BY business,secondDept,thirdDept,fourthDept,costType";
        $data = $this->_db->getArray($sql);

        if (empty($data)) {
            return array();
        }

        // 查询脚本 - 分项部分
        $sql = "SELECT business,secondDept,thirdDept,fourthDept,costType,thisYear,thisMonth,fee
                FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginStamp AND $endStamp AND isImport = $isImport";
        $itemData = $this->_db->getArray($sql);

        // 数据转换
        $itemDataMap = array();
        foreach ($itemData as $k => $v) {
            $key = $v['business'] . '_' . $v['secondDept'] . '_' . $v['thirdDept'] . '_' .
                $v['fourthDept'] . '_' . $v['costType'] . $v['thisYear'] . str_pad($v['thisMonth'], 2, 0, STR_PAD_LEFT);
            $itemDataMap[$key] = $v;
        }

        // 生成月存储字段
        foreach ($data as $k => $v) {
            for ($i = $beginYear; $i <= $endYear; $i++) {
                $begin = 1;
                $end = 12;
                if ($i == $beginYear) {
                    $begin = $beginMonth;
                }
                if ($i == $endYear) {
                    $end = $endMonth;
                }
                // 月份列渲染
                for ($j = $begin; $j <= $end; $j++) {
                    // 生成月份
                    $yearMonth = $j >= 10 ? $i . $j : $i . str_pad($j, 2, 0, STR_PAD_LEFT);

                    // 生成key
                    $key = $v['business'] . '_' . $v['secondDept'] . '_' . $v['thirdDept'] . '_' .
                        $v['fourthDept'] . '_' . $v['costType'] . $yearMonth;

                    // 载入月的数据
                    $data[$k]['d' . $yearMonth] = isset($itemDataMap[$key]) ? $itemDataMap[$key]['fee'] : 0;
                }
            }
        }
        return $data;
    }

    function otherFeeDetail_d($beginYear, $beginMonth, $endYear, $endMonth, $isImport)
    {
        $beginStamp = strtotime($beginYear . "-" . $beginMonth . "-1");
        $endStamp = strtotime($endYear . "-" . $endMonth . "-1");

        // 查询脚本 - 分项部分
        $sql = "SELECT business,secondDept,thirdDept,fourthDept,costType,thisYear,thisMonth,fee,budget
                FROM oa_bi_dept_fee WHERE thisTime BETWEEN $beginStamp AND $endStamp AND isImport = $isImport
                ORDER BY thisYear DESC,thisMonth DESC";
        $data = $this->_db->getArray($sql);

        if (empty($data)) {
            return array();
        }
        return $data;
    }

    // 导入标题
    private $importTitle = array(
        '事业部' => 'business', '二级部门' => 'secondDept', '三级部门' => 'thirdDept', '四级部门' => 'fourthDept',
        '年' => 'thisYear', '月' => 'thisMonth', '费用类型' => 'costType', '预算' => 'budget',
        '决算' => 'fee'
    );

    // 必填标题
    private $needTitle = array(
        '事业部' => 'business', '二级部门' => 'secondDept', '年' => 'thisYear', '月' => 'thisMonth',
        '费用类型' => 'costType', '预算' => 'budget', '决算' => 'fee'
    );

    /**
     * 导入方法
     * @return array
     */
    function import_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array(); //结果数组
        $tempArr = array();
        // 判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");

            $titleRow = $excelData[0];
            unset($excelData[0]);

            if (is_array($excelData)) {
                foreach ($excelData as $key => $val) {
                    // 空行处理
                    if ($val[0] == "" && $val[1] == "") {
                        continue;
                    }

                    $actNum = $key + 2;
                    // 格式化数组
                    $val = $this->formatArray_d($val, $titleRow);

                    $allIn = true;

                    // 必填校验
                    foreach ($this->needTitle as $k => $v) {
                        if (!isset($val[$v])) {
                            $resultArr[] = array(
                                'docCode' => '第' . $actNum . '条数据', 'result' => '没有填写' . $k
                            );
                            $allIn = false;
                            break;
                        }
                    }

                    // 如果存在非必填，则跳出
                    if (!$allIn) {
                        continue;
                    }

                    // 年份
                    if ($val['thisYear'] > date('Y') || $val['thisYear'] < 2000) {
                        $resultArr[] = array(
                            'docCode' => '第' . $actNum . '条数据', 'result' => '年份填写错误'
                        );
                        continue;
                    }

                    // 月份
                    if ($val['thisMonth'] > 12 || $val['thisMonth'] < 1) {
                        $resultArr[] = array(
                            'docCode' => '第' . $actNum . '条数据', 'result' => '月份填写错误'
                        );
                        continue;
                    }

                    // 预算
                    if (!is_numeric($val['budget'])) {
                        $resultArr[] = array(
                            'docCode' => '第' . $actNum . '条数据', 'result' => '预算填写错误'
                        );
                        continue;
                    }

                    // 决算
                    if (!is_numeric($val['fee'])) {
                        $resultArr[] = array(
                            'docCode' => '第' . $actNum . '条数据', 'result' => '决算填写错误'
                        );
                        continue;
                    }

                    // 导入标志
                    $val['isImport'] = 1;

                    try {
                        // 条件拼装
                        $conditionArr = array(
                            'business' => $val['business'], 'secondDept' => $val['secondDept'],
                            'thirdDept' => $val['thirdDept'], 'fourthDept' => $val['fourthDept'],
                            'costType' => $val['costType'], 'thisYear' => $val['thisYear'],
                            'thisMonth' => $val['thisMonth'], 'isImport' => 1
                        );
                        // 如果预算和决算为0，删除
                        if ($val['budget'] == 0 && $val['fee'] == 0) {
                            $this->delete($conditionArr);
                        } else {
                            // 匹配是否已存在
                            $deptFee = $this->find($conditionArr, null, 'id');
                            if ($deptFee) {
                                //更新费用
                                $this->update($deptFee, array(
                                        'budget' => $val['budget'], 'fee' => $val['fee']
                                    )
                                );
                                $tempArr['result'] = '更新成功';
                            } else {
                                $val['thisTime'] = strtotime($val['thisYear'] . '-' . $val['thisMonth'] . '-1');
                                $val['thirdDept'] = isset($val['thirdDept']) ? $val['thirdDept'] : "";
                                $val['fourthDept'] = isset($val['fourthDept']) ? $val['fourthDept'] : "";
                                $val['orgDept'] = isset($val['orgDept']) ? $val['orgDept'] : "";
                                $this->add_d($val);
                                $tempArr['result'] = '导入成功';
                            }
                        }
                    } catch (Exception $e) {
                        $tempArr['result'] = '更新失败' . $e->getMessage();
                    }

                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                    array_push($resultArr, $tempArr);
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    /**
     * 匹配excel字段
     * @param $data
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($data, $titleRow)
    {
        // 构建新的数组
        foreach ($titleRow as $k => $v) {
            // 如果数据为空，则删除
            if (trim($data[$k]) === '') {
                unset($data[$k]);
                continue;
            }
            // 如果存在已定义内容，则进行键值替换
            if (isset($this->importTitle[$v])) {
                // 格式化更新数组
                $data[$this->importTitle[$v]] = trim($data[$k]);
            }
            // 处理完成后，删除该项
            unset($data[$k]);
        }
        return $data;
    }
}