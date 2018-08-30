<?php
/**
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/engineering/records/iesmfieldrecord.php');

/**
 * Class model_engineering_records_strategy_payables
 * 计算逻辑说明
 * 1、财务审核费用分摊时，触发此方法
 */
class model_engineering_records_strategy_payables extends model_base implements iesmfieldrecord
{

    private $periodOut = " AND LEFT(c.inPeriod, 4) >= 2017 ";

    /**
     * 决算初始化
     * @param $esmFieldRecordDao
     * @param $category
     * @return int
     */
    function init_d($esmFieldRecordDao, $category)
    {
        // 获取暂时启用的各项费用名称
        $otherDatasDao = new model_common_otherdatas();
        $payables = $otherDatasDao->getConfig('engineering_budget_payables');

        // 查询原来存在的项目决算
        $projectData = $esmFieldRecordDao->_db->getArray("SELECT projectId FROM " . $esmFieldRecordDao->tbl_name .
            " WHERE feeFieldType = '" . $category . "' GROUP BY projectId");

        $projectIdArr = array();

        foreach ($projectData as $v) {
            $projectIdArr[$v['projectId']] = $v['projectId'];
        }

        // 删除已经存在的原决算
        $esmFieldRecordDao->delete(array(
            'feeFieldType' => $category
        ));

        // 过滤不适用的费用项
        $payablesSql = $payables ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($payables) . ") " : "";

        $sql = "SELECT projectId, inPeriod FROM (
            SELECT projectId,inPeriod
            FROM oa_finance_cost c
            WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0  AND projectId > 0 " . $this->periodOut . $payablesSql . "
            UNION ALL
            SELECT p.id AS projectId,c.inPeriod
            FROM oa_finance_cost c INNER JOIN (
                    SELECT id,contractId FROM oa_esm_project
                    WHERE contractId >= 1 AND contractType = 'GCXMYD-01' GROUP BY contractId
                ) p ON c.contractId = p.contractId
            WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0 AND c.contractId >= 1
                " . $this->periodOut . $payablesSql . "
        ) p GROUP BY projectId, inPeriod";

        $list = $esmFieldRecordDao->_db->getArray($sql);

        $c = 0;
        if ($list) {
            foreach ($list as $v) {
                $periodArr = explode('.', $v['inPeriod']);
                $year = $periodArr[0];
                $month = $periodArr[1];
                $this->feeUpdate_d($esmFieldRecordDao, $category, $year, $month, $v);

                if (isset($projectIdArr[$v['projectId']])) {
                    unset($projectIdArr[$v['projectId']]);
                }

                $c++;
            }

            if (!empty($projectIdArr)) {
                // 项目ids
                $ids = implode(',', $projectIdArr);

                // 更新项目的现场决算信息
                $sql = "UPDATE oa_esm_project c SET c.feePayables = 0 WHERE c.id IN(" . $ids . ")";
                $esmFieldRecordDao->_db->query($sql);

                // 这边去更新全部项目的决算信息
                $esmProjectDao = new model_engineering_project_esmproject();
                // 计算决算金额
                $esmProjectDao->calProjectFee_d(null, $ids);
                // 计算决算进度
                $esmProjectDao->calFeeProcess_d(null, $ids);
            }
        }
        return $c;
    }

    /**
     * 决算更新
     * @param $esmFieldRecordDao
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function feeUpdate_d($esmFieldRecordDao, $category, $year, $month, $sourceParam)
    {
        $projectId = $sourceParam['projectId'];

        // 如果存在项目id，那么再往下进行计算
        if ($projectId && $year >= 2017) {
            // 获取暂时启用的各项费用名称
            $otherDatasDao = new model_common_otherdatas();
            $payables = $otherDatasDao->getConfig('engineering_budget_payables');

            // 过滤不适用的费用项
            $payablesSql = $payables ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($payables) . ") " : "";

            // 获取项目占比
            $projectDao = new model_engineering_project_esmproject();
            $projectRate = $projectDao->getProjectRate_d($projectId);

            $sql = "SELECT SUM(p.costMoney) AS finalAmount FROM (
                SELECT c.costMoney
                FROM oa_finance_cost c
                WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0
                    AND inPeriod = '" . $year . "." . $month . "'
                    AND c.projectId = " . $projectId . $this->periodOut . $payablesSql . "
                UNION ALL
                SELECT $projectRate / 100 * (c.costMoney) AS costMoney
                FROM oa_finance_cost c INNER JOIN (
                    SELECT contractId FROM oa_esm_project
                    WHERE id = " . $projectId . " AND contractType = 'GCXMYD-01' GROUP BY contractId
                ) p ON c.contractId = p.contractId
                WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0 AND c.contractId >= 1
                    AND inPeriod = '" . $year . "." . $month . "'
                    " . $this->periodOut . $payablesSql . "
            ) p";

            $result = $esmFieldRecordDao->_db->get_one($sql);

            // 开始更新数据
            try {
                // 删除本月的原决算
                $esmFieldRecordDao->delete(array(
                    'thisYear' => $year,
                    'thisMonth' => $month,
                    'projectId' => $projectId,
                    'feeFieldType' => $category
                ));

                if ($result['finalAmount']) {
                    // 插入本月新增的决算
                    $esmFieldRecordDao->create(array(
                        'thisYear' => $year,
                        'thisMonth' => $month,
                        'projectId' => $projectId,
                        'createId' => $_SESSION['USER_ID'],
                        'createName' => $_SESSION['USERNAME'],
                        'createTime' => date('Y-m-d H:i:s'),
                        'feeField' => $result['finalAmount'],
                        'feeFieldImport' => 0,
                        'feeFieldType' => $category,
                        'thisDate' => $year . '-' . $month . '-1'
                    ));
                }

                // 更新项目的现场决算信息
                $sql = "UPDATE oa_esm_project c SET c.feePayables = " .
                    "IFNULL((SELECT SUM(feeField) FROM oa_esm_records_field f WHERE f.feeFieldType = '" . $category .
                    "' AND c.id = f.projectId),0) " .
                    "WHERE c.id = " . $projectId;
                $esmFieldRecordDao->_db->query($sql);

                // 这边去更新全部项目的决算信息
                $esmProjectDao = new model_engineering_project_esmproject();
                // 计算决算金额
                $esmProjectDao->calProjectFee_d(null, $projectId);
                // 计算决算进度
                $esmProjectDao->calFeeProcess_d($projectId);
            } catch (Exception $e) {
                throw $e;
            }
        }
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

    /**
     * 获取明细
     * @param feeDetail_d
     * @param $sourceParam
     * @return mixed
     */
    function feeDetail_d($esmFieldRecordDao, $sourceParam)
    {
        // 获取暂时启用的各项费用名称
        $otherDatasDao = new model_common_otherdatas();
        $payables = $otherDatasDao->getConfig('engineering_budget_payables');

        // 过滤不适用的费用项
        $payablesSql = $payables ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($payables) . ") " : "";

        // 获取项目占比
        $projectDao = new model_engineering_project_esmproject();
        $projectRate = $projectDao->getProjectRate_d($sourceParam[1]);

        $sql = "SELECT costTypeName, SUM(p.costMoney) AS finalAmount FROM (
            SELECT c.costTypeName, c.costMoney
            FROM oa_finance_cost c
            WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0
                AND c.projectId = " . $sourceParam[1] . $this->periodOut . $payablesSql . "
            UNION ALL
            SELECT c.costTypeName, $projectRate / 100 * (c.costMoney) AS costMoney
            FROM oa_finance_cost c INNER JOIN (
                    SELECT contractId FROM oa_esm_project
                    WHERE id = " . $sourceParam[1] . " AND contractType = 'GCXMYD-01' GROUP BY contractId
                ) p ON c.contractId = p.contractId
            WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0 " . $this->periodOut . $payablesSql . "
                AND c.contractId >= 1
        ) p GROUP BY costTypeName";

        $list = $esmFieldRecordDao->_db->getArray($sql);

        // 返回结果
        $result = array();

        if ($list) {
            foreach ($list as $v) {
                $result[$v['costTypeName']] = $v['finalAmount'];
            }
        }

        return $result;
    }

    /**
     * @param $esmFieldRecordDao
     * @param $sourceParam
     * @return mixed
     */
    function getDetailGroupMonth_d($esmFieldRecordDao, $sourceParam)
    {
        // 获取暂时启用的各项费用名称
        $otherDatasDao = new model_common_otherdatas();
        $payables = $otherDatasDao->getConfig('engineering_budget_payables');

        // 过滤不适用的费用项
        $payablesSql = $payables ? " AND costTypeName NOT IN(" . util_jsonUtil::strBuild($payables) . ") " : "";

        // 获取项目占比
        $projectDao = new model_engineering_project_esmproject();
        $projectRate = $projectDao->getProjectRate_d($sourceParam[1]);

        // 查询脚本
        $sql = "SELECT inPeriod AS yearMonth, SUM(p.costMoney) AS actFee, GROUP_CONCAT(belongPeriod) AS remark FROM (
            SELECT c.inPeriod, c.costMoney,c.belongPeriod
            FROM oa_finance_cost c
            WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0
                AND c.projectId = " . $sourceParam[1] . $this->periodOut .
                $this->periodOut . " AND c.costTypeName = '" . $sourceParam[2] . "' $payablesSql
            UNION ALL
            SELECT c.inPeriod, $projectRate / 100 * (c.costMoney) AS costMoney,c.belongPeriod
            FROM oa_finance_cost c INNER JOIN (
                    SELECT contractId FROM oa_esm_project
                    WHERE id = " . $sourceParam[1] . " AND contractType = 'GCXMYD-01' GROUP BY contractId
                ) p ON c.contractId = p.contractId
            WHERE c.auditStatus = 1 AND c.isTemp = 0 AND c.isDel = 0 AND c.contractId >= 1
                " . $this->periodOut . "
                AND c.costTypeName = '" . $sourceParam[2] . "' $payablesSql
        ) p GROUP BY inPeriod ORDER BY LEFT(inPeriod, 4), SUBSTR(inPeriod, 6, 2)";

        $data = $esmFieldRecordDao->_db->getArray($sql);

        if ($data) {
            foreach ($data as $k => $v) {
                if ($v['remark']) {
                    $remarkArr = array_unique(explode($v['remark']));
                    $data[$k]['remark'] = implode(',', $remarkArr);
                }
            }
        }

        return $esmFieldRecordDao->_db->getArray($sql);
    }
}