<?php
/**
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/engineering/records/iesmfieldrecord.php');

/**
 * Class model_engineering_records_strategy_subsidyProvision
 * 计算逻辑说明
 * 1、更新项目的补贴计提 - 这里是批量更新，不是一个项目一个项目更新
 */
class model_engineering_records_strategy_subsidyProvision extends model_base implements iesmfieldrecord
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
     * @param $esmFieldRecordDao
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function feeUpdate_d($esmFieldRecordDao, $category, $year, $month, $sourceParam)
    {
        //更新决算
        $gl = new includes_class_global();
        $data = $gl->get_salarypro_info($year, $month);

        if (empty($data)) {
            throw new Exception("计提数据获取失败，请重试或者联系管理员");
        }

        // 获取项目转义数组
        $projectHash = $this->getProjectHash_d($esmFieldRecordDao);

        try {
            // 删除当前计提数据
            $esmFieldRecordDao->delete(array('thisYear' => $year, 'thisMonth' => $month,
                'feeFieldType' => $category));

            // 插入数据
            $insertData = array();
            // key 长度
            $dataKeyLength = count($data) - 1;

            // 数据日期
            $date = $year . '-' . $month . '-1';

            // 创建时间
            $time = date('Y-m-d H:i:s');

            foreach ($data as $k => $v) {

                $projectId = isset($projectHash[$v['projectCode']]) ? $projectHash[$v['projectCode']] : '';
                if (!$projectId && $dataKeyLength != $k) {
                    continue;
                }

                // 现场决算
                $insertData[] = array(
                    'thisYear' => $year, 'thisMonth' => $month,
                    'thisDate' => $date, 'createTime' => $time,
                    'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                    'projectId' => $projectId, 'feeField' => $v['payTotal'], 'feeFieldType' => $category
                );

                // 100条获取满足数组长度时，插入数据
                if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                    // 批量保存
                    $esmFieldRecordDao->createBatch($insertData);
                    $insertData = array();
                }
            }

            // 更新项目决算
            $sql = "UPDATE oa_esm_project c LEFT JOIN
                (
                    SELECT projectId,SUM(feeField) AS fee FROM oa_esm_records_field
                    WHERE feeFieldType = '" . $category . "' GROUP BY projectId
                ) f ON c.id = f.projectId
                SET c.feeSubsidyImport = IFNULL(f.fee, 0)";
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

    /**
     * 取消计提补贴
     * @param $esmFieldRecordDao
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function feeCancel_d($esmFieldRecordDao, $category, $year, $month, $sourceParam)
    {
        // 实例化项目
        $esmProjectDao = new model_engineering_project_esmproject();

        $projectId = '';
        if ($sourceParam['projectCode']) {
            $project = $esmProjectDao->find(array('projectCode' => $sourceParam['projectCode']), null, 'id');
            $projectId = $project ? $project['id'] : '';
        }

        try {
            if ($projectId) {
                // 删除当前计提数据
                $esmFieldRecordDao->delete(array('thisYear' => $year, 'thisMonth' => $month,
                    'feeFieldType' => $category, 'projectId' => $projectId));

                // 更新项目决算
                $sql = "UPDATE oa_esm_project c LEFT JOIN
                (
                    SELECT projectId,SUM(feeField) AS fee FROM oa_esm_records_field
                    WHERE feeFieldType = '" . $category . "' AND projectId = $projectId GROUP BY projectId
                ) f ON c.id = f.projectId
                SET c.feeSubsidyImport = IFNULL(f.fee, 0) WHERE c.id = $projectId";
                $esmFieldRecordDao->_db->query($sql);
                // 计算决算金额
                $esmProjectDao->calProjectFee_d(null, $projectId);
                // 计算决算进度
                $esmProjectDao->calFeeProcess_d($projectId);
            } else {
                // 删除当前计提数据
                $esmFieldRecordDao->delete(array('thisYear' => $year, 'thisMonth' => $month,
                    'feeFieldType' => $category));

                // 更新项目决算
                $sql = "UPDATE oa_esm_project c LEFT JOIN
                (
                    SELECT projectId,SUM(feeField) AS fee FROM oa_esm_records_field
                    WHERE feeFieldType = '" . $category . "' GROUP BY projectId
                ) f ON c.id = f.projectId
                SET c.feeSubsidyImport = IFNULL(f.fee, 0)";
                $esmFieldRecordDao->_db->query($sql);
                // 计算决算金额
                $esmProjectDao->calProjectFee_d();
                // 计算决算进度
                $esmProjectDao->calFeeProcess_d();
            }
        } catch (Exception $e) {
            throw $e;
        }
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
        $projectData = $esmFieldRecordDao->_db->getArray('SELECT id, projectCode FROM oa_esm_project WHERE ExaStatus = "完成"');

        if ($projectData) {
            foreach ($projectData as $v) {
                $projectHash[$v['projectCode']] = $v['id'];
            }
        }

        return $projectHash;
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