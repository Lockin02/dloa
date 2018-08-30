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
 * �����߼�˵��
 * 1��������˷��÷�̯ʱ�������˷���
 */
class model_engineering_records_strategy_flightsShare extends model_base implements iesmfieldrecord
{

    /**
     * �����ʼ��
     * @param $esmFieldRecordDao
     * @param $category
     * @return int
     */
    function init_d($esmFieldRecordDao, $category)
    {
        // ��ȡ��Ʊ������
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
     * �������
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

        // ���������Ŀid����ô�����½��м���
        if ($projectId && $year >= 2016) {

            // ��ѯ��Ŀƥ��ĺ�ͬ
            $sql = "SELECT SUM(costMoney) AS finalAmount FROM oa_finance_cost WHERE projectId = " . $projectId .
                " AND belongPeriod = '" . $year . "." . $month . "' AND auditStatus = 1 " .
                " AND LEFT(belongPeriod, 4) >= 2016 AND costTypeId IN(" . $sourceParam['flightsShareIds'] . ")";
            $result = $esmFieldRecordDao->_db->get_one($sql);

            // ��ʼ��������
            try {
                // ɾ�����µ�ԭ����
                $esmFieldRecordDao->delete(array(
                    'thisYear' => $year,
                    'thisMonth' => $month,
                    'projectId' => $projectId,
                    'feeFieldType' => $category
                ));

                if ($result['finalAmount']) {
                    // ���뱾�������ľ���
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

                // ������Ŀ���ֳ�������Ϣ
                $sql = "UPDATE oa_esm_project c SET c.feeFlightsShare = " .
                    "IFNULL((SELECT SUM(feeField) FROM oa_esm_records_field f WHERE f.feeFieldType = '" . $category .
                    "' AND c.id = f.projectId),0) " .
                    "WHERE c.id = " . $projectId;
                $esmFieldRecordDao->_db->query($sql);

                // ���ȥ����ȫ����Ŀ�ľ�����Ϣ
                $esmProjectDao = new model_engineering_project_esmproject();
                // ���������
                $esmProjectDao->calProjectFee_d();
                // ����������
                $esmProjectDao->calFeeProcess_d();
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * ��ȡ��ϸ�б�
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