<?php
/*
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/engineering/records/iesmfieldrecord.php');

/**
 * Class model_engineering_records_strategy_field
 * 计算逻辑说明
 * 1、更新现场决算以及补贴决算
 */
class model_engineering_records_strategy_field extends model_base implements iesmfieldrecord
{

    /**
     * 决算初始化
     * @param $esmFieldRecordDao
     * @param $category
     * @return int
     */
    function init_d($esmFieldRecordDao, $category)
    {
        return 0;
    }

    /**
     * 决算更新
     * 计算规则如下：
     * 1、匹配是否更新范围内有多少项目的报销金额发生了变化
     * 2、对于此类项目重新读取决算数据
     * 3、没有发生变化的数据，读取上一次存档的数据，然后复制一份，存为当前月份的数据。
     * @param $esmFieldRecordDao
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function feeUpdate_d($esmFieldRecordDao, $category, $year, $month, $sourceParam)
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');	//设置内存

        // 实例化项目
        $esmProjectDao = new model_engineering_project_esmproject();
        // 获取项目转义数组
        $projectHash = $this->getProjectHash_d($esmFieldRecordDao);

        // 实例化报销
        $expenseDao = new model_finance_expense_expense();
        // 年和月的处理
        $periodInfo = $this->getPeriod_d($year, $month);

        // 日志初始化
        $logDao = new model_engineering_baseinfo_esmlog();

        $logDao->addLog_d(-2, '更新报销支付-锚点', "类初始化：" . $year . "-" . $month);

        // 加入单个的处理 - 如果参数中传入了项目编号，只对这个项目处理相关数据
        if (isset($sourceParam['projectCode']) && $sourceParam['projectCode']) {
            $projectInfo = $esmProjectDao->find(array('projectCode' => $sourceParam['projectCode']), null, 'id');
            if ($projectInfo) {
                $changeProjectCodes = $sourceParam['projectCode'];
                $changeProjectIds = $projectInfo['id'];
            } else {
                $changeProjectCodes = "NONE";
                $changeProjectIds = -1;
            }
            $noChangeData = array();

            $logDao->addLog_d(-2, '更新报销支付-锚点', "单项目更新：" . $sourceParam['projectCode']);
        } else {
            // 构建一个日期
            $filterTime = strtotime($year . "-" . $month . "-01") - 1;

            // 获取最后一次更新的决算信息
            $lastOne = $esmFieldRecordDao->find(" feeFieldType IN('field','subsidy') AND UNIX_TIMESTAMP(thisDate) <= $filterTime ", "ID DESC");

            // 生成检测的时间戳
            $checkBeginTime = strtotime($lastOne['thisDate']); // 开始时间戳
            $checkEndTime = strtotime(date("Y-m-d", time())) + 86399; // 结束时间戳

            $changeProjectList = $expenseDao->getChangeProjectCodeList_d($checkBeginTime, $checkEndTime); // 当前变动过的项目列表
            $changeProjectCodes = implode(',', $changeProjectList);
            $changeProjectIdArr = $this->returnProjectIdArr_d($changeProjectList, $projectHash); // 项目ID数组
            $changeProjectIds = implode(',', $changeProjectIdArr);

            // 如果是当月，直接更新即可
            if ($lastOne['thisYear'] == $year && $lastOne['thisMonth'] == $month) {
                // 更新无变化的数据
                $esmFieldRecordDao->updateNoChangeData_d($year, $month, 'field,subsidy', $changeProjectIds,
                    $periodInfo['thisTime']);
            } else {
                $noChangeData = $esmFieldRecordDao->getNoChangeData_d($lastOne['thisYear'], $lastOne['thisMonth'],
                    'field,subsidy', $changeProjectIds);
            }

            $logDao->addLog_d(-2, '更新报销支付-锚点', "项目获取");
        }

        // 项目决算
        $data = $expenseDao->getAllProjectFee_d($year, $month, $changeProjectCodes);
        $logDao->addLog_d(-2, '更新报销支付-锚点', "项目决算获取");

        // 配置中的补贴名称
        $otherDatasDao = new model_common_otherdatas();
        $subsidyIds = $otherDatasDao->getConfig('engineering_budget_subsidy_id');
        $filterIds = $otherDatasDao->getConfig('engineering_budget_expense_id');

        // 获取补贴部分
        $subsidyData = $expenseDao->getAllProjectSomeFee_d($subsidyIds, $year, $month, $changeProjectCodes);
        $logDao->addLog_d(-2, '更新报销支付-锚点', "获取补贴部分");

        // 获取过滤决算
        $filterData = $expenseDao->getAllProjectSomeFee_d($filterIds, $year, $month, $changeProjectCodes);
        $logDao->addLog_d(-2, '更新报销支付-锚点', "获取过滤决算");

        if ($data) {
            try {
                // 删除当前的数据 , 现场，补贴
                $esmFieldRecordDao->deleteInfo_d($year, $month, 'field', $changeProjectIds);
                $esmFieldRecordDao->deleteInfo_d($year, $month, 'subsidy', $changeProjectIds);
                $logDao->addLog_d(-2, '更新报销支付-锚点', "删除原记录");

                // hash数据转换补贴
                $subsidyHash = !empty($subsidyData) ? $this->hashChange_d($subsidyData, $projectHash) : array();

                // hash数据转换补贴
                $filterHash = !empty($filterData) ? $this->hashChange_d($filterData, $projectHash) : array();

                // 插入数据
                $insertData = array();
                // key 长度
                $dataKeyLength = count($data) - 1;
                // 需要更新的项目id
                $projectIds = array();

                foreach ($data as $k => $v) {

                    $projectId = isset($projectHash[$v['projectNo']]) ? $projectHash[$v['projectNo']] : '';
                    if (!$projectId && $dataKeyLength != $k) {
                        continue;
                    }

                    if ($projectId) {
                        $projectIds[] = $projectId;

                        // 如果存在过滤的决算
                        $v['costMoney'] = isset($filterHash[$projectId]) && $filterHash[$projectId] > 0 ?
                            bcsub($v['costMoney'], $filterHash[$projectId], 2) : $v['costMoney'];

                        // 补贴处理
                        $subsidy = isset($subsidyHash[$projectId]) && $subsidyHash[$projectId] > 0 ?
                            $subsidyHash[$projectId] : 0;

                        if ($subsidy > 0) {
                            // 补贴 - 人力决算
                            $insertData[] = array(
                                'thisYear' => $periodInfo['thisYear'], 'thisMonth' => $periodInfo['thisMonth'],
                                'thisDate' => $periodInfo['thisDate'], 'createTime' => $periodInfo['thisTime'],
                                'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                                'projectId' => $projectId, 'feeField' => $subsidy, 'feeFieldType' => 'subsidy',
                                'isChange' => 1
                            );
                        }

                        // 现场决算 = 现场决算 - 补贴
                        $v['costMoney'] = bcsub($v['costMoney'], $subsidy, 2);

                        if ($v['costMoney'] > 0) {
                            // 现场决算
                            $insertData[] = array(
                                'thisYear' => $periodInfo['thisYear'], 'thisMonth' => $periodInfo['thisMonth'],
                                'thisDate' => $periodInfo['thisDate'], 'createTime' => $periodInfo['thisTime'],
                                'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                                'projectId' => $projectId, 'feeField' => $v['costMoney'], 'feeFieldType' => 'field',
                                'isChange' => 1
                            );
                        }
                    }

                    // 100条获取满足数组长度时，插入数据
                    if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                        // 批量保存
                        $esmFieldRecordDao->createBatch($insertData);
                        $insertData = array();
                    }
                }
                $logDao->addLog_d(-2, '更新报销支付-锚点', "当月有变动的数据处理完成");

                // 处理跨月的没有变动的数据
                if (isset($noChangeData)) {
                    // key 长度
                    $dataKeyLength = count($noChangeData) - 1;

                    foreach ($noChangeData as $k => $v) {
                        // 现场决算
                        $insertData[] = array(
                            'thisYear' => $periodInfo['thisYear'], 'thisMonth' => $periodInfo['thisMonth'],
                            'thisDate' => $periodInfo['thisDate'], 'createTime' => $periodInfo['thisTime'],
                            'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                            'projectId' => $v['projectId'], 'feeField' => $v['feeField'], 'feeFieldType' => $v['feeFieldType'],
                            'isChange' => 1
                        );

                        // 100条获取满足数组长度时，插入数据
                        if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                            // 批量保存
                            $esmFieldRecordDao->createBatch($insertData);
                            $insertData = array();
                        }
                    }
                }
                $logDao->addLog_d(-2, '更新报销支付-锚点', "当月无变动的数据处理完成");

                if (!empty($projectIds)) {
                    $projectIds = implode(',', $projectIds);
                    $logDao->addLog_d(-2, '更新报销支付-锚点', "更新项目预决算:" . $projectIds);

                    // 更新项目的现场决算信息
                    $sql = "UPDATE oa_esm_project c LEFT JOIN oa_esm_records_field f ON " .
                        "c.id = f.projectId  AND f.feeFieldType = 'field' AND f.thisYear = '" . $year .
                        "' AND f.thisMonth = '" . $month . "' SET
                    c.feeField = IFNULL(f.feeField,0) WHERE c.id IN($projectIds)";
                    $esmFieldRecordDao->_db->query($sql);

                    // 更新项目的补贴金额
                    $sql = "UPDATE oa_esm_project c LEFT JOIN oa_esm_records_field f ON " .
                        "c.id = f.projectId  AND f.feeFieldType = 'subsidy' AND f.thisYear = '" . $year .
                        "' AND f.thisMonth = '" . $month . "' SET
                    c.feeSubsidy = IFNULL(f.feeField,0) WHERE c.id IN($projectIds)";
                    $esmFieldRecordDao->_db->query($sql);
                    // 计算决算金额
                    $esmProjectDao->calProjectFee_d(null, $projectIds);
                    // 加上租车登记的预提金额 PMS 715
                    $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
                    $rentalcarDao->updateProjectFieldFeeWithCarFee($projectIds);
                    // 计算决算进度
                    $esmProjectDao->calFeeProcess_d(null, $projectIds);

                    $logDao->addLog_d(-2, '更新报销支付-锚点', "更新项目预决算完成");
                }
            } catch (Exception $e) {
                throw $e;
            }
        }

        // 日志写入
        $logDao->addLog_d(-1, '更新报销支付', count($data) . '|' . $month);
    }

    /**
     * 日期处理
     */
    function getPeriod_d($thisYear, $thisMonth)
    {
        return array(
            'thisYear' => $thisYear,
            'thisMonth' => $thisMonth,
            'thisTime' => date("Y-m-d H:i:s"),
            'thisDate' => $thisYear . '-' . $thisMonth . '-01'
        );
    }

    /**
     * 获取项目的的编号索引 array(projectCode => projectId)
     * @param $esmFieldRecordDao
     * @return array
     */
    function getProjectHash_d($esmFieldRecordDao)
    {
        $projectHash = array();

        // 显示获取项目
        $projectData = $esmFieldRecordDao->_db->getArray('SELECT id, projectCode FROM oa_esm_project');

        if ($projectData) {
            foreach ($projectData as $v) {
                $projectHash[$v['projectCode']] = $v['id'];
            }
        }

        return $projectHash;
    }

    /**
     * 返回变动的项目ID
     * @param $data
     * @param $projectHash
     * @return array
     */
    function returnProjectIdArr_d($data, $projectHash)
    {
        $idArr = array();
        foreach ($data as $v) {
            if (isset($projectHash[$v['projectNo']])) {
                $idArr[] = $projectHash[$v['projectNo']];
            }
        }
        return $idArr;
    }

    /**
     * HASH转化
     * @param $data
     * @param $projectHash
     * @return array
     */
    function hashChange_d($data, $projectHash)
    {
        $hash = array();

        // 转义处理
        foreach ($data as $v) {
            if ($projectHash[$v['projectNo']]) {
                $hash[$projectHash[$v['projectNo']]] = $v['costMoney'];
            }
        }

        return $hash;
    }

    /**
     * 获取明细列表
     * @param $esmFieldRecordDao
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @return mixed
     */
    function feeList_d($esmFieldRecordDao, $category, $year = '', $month = '', $sourceParam)
    {
        $condition = array('feeFieldType' => $category, 'projectId' => $sourceParam['projectId']);
        if ($year) {
            $condition['thisYear'] = $year;
        }
        if ($month) {
            $condition['thisMonth'] = $month;
        }
        return $esmFieldRecordDao->findAll($condition);
    }
}