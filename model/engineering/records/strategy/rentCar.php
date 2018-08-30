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
 * �����߼�˵��
 * 1���⳵��ͬ���ɵ�������Ʊ���/����˺󣬻ᴥ���˷���
 * 2�����ݺ�ͬ�Ų��Ҷ�Ӧ����ĿID��Ȼ��ͨ����ĿID���Ҵ���Ŀ��������غ�ͬ
 * 3��ͨ����ͬ���Լ�����˷�Ʊ������ȷ������ľ����Ȼ�����ֻ��Ŀ��
 */
class model_engineering_records_strategy_rentCar extends model_base implements iesmfieldrecord
{

    /**
     * �����ʼ��
     * @param $esmFieldRecordDao
     * @param $category
     * @return int
     */
    function init_d($esmFieldRecordDao, $category)
    {

        // ɾ���Ѿ����ڵ�ԭ����
        $esmFieldRecordDao->delete(array(
            'feeFieldType' => $category
        ));

        // ������Ŀ���ֳ�������Ϣ
        $this->_db->query("UPDATE oa_esm_project c SET c.feeCar = 0");

        // ���ȥ����ȫ����Ŀ�ľ�����Ϣ
        $esmProjectDao = new model_engineering_project_esmproject();
        // ���������
        $esmProjectDao->calProjectFee_d();
        // ����������
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
        // ��ƥ��������ͬ��Ӧ����Ŀ
        $projectId = $this->get_table_fields('oa_contract_rentcar',
            "orderCode = '" . $sourceParam['menuNo'] . "' AND isTemp = 0",
            'projectId');

        // ���������Ŀid����ô�����½��м���
        if ($projectId) {

            // ��ѯ��Ŀƥ��ĺ�ͬ
            $sql = "SELECT orderCode FROM oa_contract_rentcar WHERE projectId = " . $projectId;
            $list = $this->_db->getArray($sql);

            // �����ͬ��
            $orderCodeArr = array();

            foreach ($list as $v) {
                if (!in_array($v['orderCode'], $orderCodeArr)) {
                    $orderCodeArr[] = $v['orderCode'];
                }
            }

            $period = $year . '.' . $month;

            // ��ѯ���¶�Ӧ�ķ�Ʊ���
            $sql = 'SELECT SUM(IF(isRed = 0, finalAmount, -finalAmount)) AS finalAmount FROM oa_finance_invother WHERE ExaStatus = 1 ' .
                ' AND period = "' . $period . '"' .
                ' AND menuNo IN(' . util_jsonUtil::strBuild(implode(',', $orderCodeArr)) . ')';
            $result = $this->_db->get_one($sql);

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
                $sql = "UPDATE oa_esm_project c SET c.feeCar = " .
                    "IFNULL((SELECT SUM(feeField) FROM oa_esm_records_field f WHERE f.feeFieldType = '" . $category .
                    "' AND c.id = f.projectId),0) " .
                    "WHERE c.id = " . $projectId;
                $this->_db->query($sql);

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