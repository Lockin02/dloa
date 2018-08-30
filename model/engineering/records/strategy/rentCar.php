<?php
/**
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/engineering/records/iesmfieldrecord.php');

/**
 * Class model_engineering_records_strategy_rentCar
 * 计算逻辑说明
 * 1、租车合同生成的其他发票审核/反审核后，会触发此方法
 * 2、根据合同号查找对应的项目ID，然后通过项目ID查找此项目的所有相关合同
 * 3、通过合同号以及被审核发票的年月确定具体的决算金额，然后更新只项目中
 */
class model_engineering_records_strategy_rentCar extends model_base implements iesmfieldrecord
{

    /**
     * 决算初始化
     * @param $esmFieldRecordDao
     * @param $category
     * @return int
     */
    function init_d($esmFieldRecordDao, $category)
    {

        // 删除已经存在的原决算
        $esmFieldRecordDao->delete(array(
            'feeFieldType' => $category
        ));

        // 更新项目的现场决算信息
        $this->_db->query("UPDATE oa_esm_project c SET c.feeCar = 0");

        // 这边去更新全部项目的决算信息
        $esmProjectDao = new model_engineering_project_esmproject();
        // 计算决算金额
        $esmProjectDao->calProjectFee_d();
        // 计算决算进度
        $esmProjectDao->calFeeProcess_d();

        $list = $this->_db->getArray("SELECT menuNo, period FROM oa_finance_invother
            WHERE sourceType = 'YFQTYD03' AND menuNo <> ''
              AND (period >= 2016.9 OR period = '2016.10' OR period = '2016.11' OR period = '2016.12')");

        $c = 0;
        if ($list) {
            foreach ($list as $v) {
                $periodArr = explode('.', $v['period']);
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
        // 先匹配出这个合同对应的项目
        $projectId = $this->get_table_fields('oa_contract_rentcar',
            "orderCode = '" . $sourceParam['menuNo'] . "' AND isTemp = 0",
            'projectId');

        // 如果存在项目id，那么再往下进行计算
        if ($projectId) {

            // 查询项目匹配的合同
            $sql = "SELECT orderCode FROM oa_contract_rentcar WHERE projectId = " . $projectId;
            $list = $this->_db->getArray($sql);

            // 缓存合同号
            $orderCodeArr = array();

            foreach ($list as $v) {
                if (!in_array($v['orderCode'], $orderCodeArr)) {
                    $orderCodeArr[] = $v['orderCode'];
                }
            }

            $period = $year . '.' . $month;

            // 查询年月对应的发票金额
            $sql = 'SELECT SUM(IF(isRed = 0, finalAmount, -finalAmount)) AS finalAmount FROM oa_finance_invother WHERE ExaStatus = 1 ' .
                ' AND period = "' . $period . '"' .
                ' AND menuNo IN(' . util_jsonUtil::strBuild(implode(',', $orderCodeArr)) . ')';
            $result = $this->_db->get_one($sql);

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
                $sql = "UPDATE oa_esm_project c SET c.feeCar = " .
                    "IFNULL((SELECT SUM(feeField) FROM oa_esm_records_field f WHERE f.feeFieldType = '" . $category .
                    "' AND c.id = f.projectId),0) " .
                    "WHERE c.id = " . $projectId;
                $this->_db->query($sql);

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
    function feeList_d($esmFieldRecordDao, $category, $year, $month, $sourceParam)
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
     * @param $esmFieldRecordDao
     * @param $sourceParam
     * @return mixed
     */
    function getDetailGroupMonth_d($esmFieldRecordDao, $sourceParam)
    {
        return $esmFieldRecordDao->_db->getArray("SELECT CONCAT(thisYear, '.', thisMonth) AS yearMonth, feeField AS actFee
            FROM oa_esm_records_field WHERE feeFieldType = '" . $sourceParam[0] . "' AND projectId = " . $sourceParam[1]);
    }
}