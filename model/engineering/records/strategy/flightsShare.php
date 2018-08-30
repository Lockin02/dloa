<?php
/*
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/engineering/records/iesmfieldrecord.php');

/**
 * Class model_engineering_records_strategy_flightsShare
 * 计算逻辑说明
 * 1、财务审核费用分摊时，触发此方法
 */
class model_engineering_records_strategy_flightsShare extends model_base implements iesmfieldrecord
{

    /**
     * 决算初始化
     * @param $esmFieldRecordDao
     * @param $category
     * @return int
     */
    function init_d($esmFieldRecordDao, $category)
    {
        // 获取机票配置项
        $otherDatasDao = new model_common_otherdatas();
        $flightsShareId = $otherDatasDao->getConfig('engineering_budget_flights_share_id');

        $list = $esmFieldRecordDao->_db->getArray("SELECT projectId, inPeriod, SUM(costMoney) AS finalAmount " .
            "FROM oa_finance_cost WHERE auditStatus = 1 AND projectId <> 0 " .
            " AND LEFT(belongPeriod, 4) >= 2016 AND costTypeId IN(" . $flightsShareId . ") GROUP BY projectId, belongPeriod");

        $c = 0;
        if ($list) {
            foreach ($list as $v) {
                $periodArr = explode('.', $v['belongPeriod']);
                $year = $periodArr[0];
                $month = $periodArr[1];
                $this->feeUpdate_d($esmFieldRecordDao, $category, $year, $month, $v);

                $c++;
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
        if ($projectId && $year >= 2016) {

            // 查询项目匹配的合同
            $sql = "SELECT SUM(costMoney) AS finalAmount FROM oa_finance_cost WHERE projectId = " . $projectId .
                " AND belongPeriod = '" . $year . "." . $month . "' AND auditStatus = 1 " .
                " AND LEFT(belongPeriod, 4) >= 2016 AND costTypeId IN(" . $sourceParam['flightsShareIds'] . ")";
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
                $sql = "UPDATE oa_esm_project c SET c.feeFlightsShare = " .
                    "IFNULL((SELECT SUM(feeField) FROM oa_esm_records_field f WHERE f.feeFieldType = '" . $category .
                    "' AND c.id = f.projectId),0) " .
                    "WHERE c.id = " . $projectId;
                $esmFieldRecordDao->_db->query($sql);

                // 这边去更新全部项目的决算信息
                $esmProjectDao = new model_engineering_project_esmproject();
                // 计算决算金额
                $esmProjectDao->calProjectFee_d();
                // 计算决算进度
                $esmProjectDao->calFeeProcess_d();
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
}