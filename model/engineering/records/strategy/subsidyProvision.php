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
 * �����߼�˵��
 * 1��������Ŀ�Ĳ������� - �������������£�����һ����Ŀһ����Ŀ����
 */
class model_engineering_records_strategy_subsidyProvision extends model_base implements iesmfieldrecord
{

    /**
     * �����ʼ��
     * @param $esmFieldRecordDao
     * @param $category
     * @return int
     */
    function init_d($esmFieldRecordDao, $category)
    {
        return 0;
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
        //���¾���
        $gl = new includes_class_global();
        $data = $gl->get_salarypro_info($year, $month);

        if (empty($data)) {
            throw new Exception("�������ݻ�ȡʧ�ܣ������Ի�����ϵ����Ա");
        }

        // ��ȡ��Ŀת������
        $projectHash = $this->getProjectHash_d($esmFieldRecordDao);

        try {
            // ɾ����ǰ��������
            $esmFieldRecordDao->delete(array('thisYear' => $year, 'thisMonth' => $month,
                'feeFieldType' => $category));

            // ��������
            $insertData = array();
            // key ����
            $dataKeyLength = count($data) - 1;

            // ��������
            $date = $year . '-' . $month . '-1';

            // ����ʱ��
            $time = date('Y-m-d H:i:s');

            foreach ($data as $k => $v) {

                $projectId = isset($projectHash[$v['projectCode']]) ? $projectHash[$v['projectCode']] : '';
                if (!$projectId && $dataKeyLength != $k) {
                    continue;
                }

                // �ֳ�����
                $insertData[] = array(
                    'thisYear' => $year, 'thisMonth' => $month,
                    'thisDate' => $date, 'createTime' => $time,
                    'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                    'projectId' => $projectId, 'feeField' => $v['payTotal'], 'feeFieldType' => $category
                );

                // 100����ȡ�������鳤��ʱ����������
                if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                    // ��������
                    $esmFieldRecordDao->createBatch($insertData);
                    $insertData = array();
                }
            }

            // ������Ŀ����
            $sql = "UPDATE oa_esm_project c LEFT JOIN
                (
                    SELECT projectId,SUM(feeField) AS fee FROM oa_esm_records_field
                    WHERE feeFieldType = '" . $category . "' GROUP BY projectId
                ) f ON c.id = f.projectId
                SET c.feeSubsidyImport = IFNULL(f.fee, 0)";
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

    /**
     * ȡ�����Ჹ��
     * @param $esmFieldRecordDao
     * @param $category
     * @param $year
     * @param $month
     * @param $sourceParam
     * @throws Exception
     */
    function feeCancel_d($esmFieldRecordDao, $category, $year, $month, $sourceParam)
    {
        // ʵ������Ŀ
        $esmProjectDao = new model_engineering_project_esmproject();

        $projectId = '';
        if ($sourceParam['projectCode']) {
            $project = $esmProjectDao->find(array('projectCode' => $sourceParam['projectCode']), null, 'id');
            $projectId = $project ? $project['id'] : '';
        }

        try {
            if ($projectId) {
                // ɾ����ǰ��������
                $esmFieldRecordDao->delete(array('thisYear' => $year, 'thisMonth' => $month,
                    'feeFieldType' => $category, 'projectId' => $projectId));

                // ������Ŀ����
                $sql = "UPDATE oa_esm_project c LEFT JOIN
                (
                    SELECT projectId,SUM(feeField) AS fee FROM oa_esm_records_field
                    WHERE feeFieldType = '" . $category . "' AND projectId = $projectId GROUP BY projectId
                ) f ON c.id = f.projectId
                SET c.feeSubsidyImport = IFNULL(f.fee, 0) WHERE c.id = $projectId";
                $esmFieldRecordDao->_db->query($sql);
                // ���������
                $esmProjectDao->calProjectFee_d(null, $projectId);
                // ����������
                $esmProjectDao->calFeeProcess_d($projectId);
            } else {
                // ɾ����ǰ��������
                $esmFieldRecordDao->delete(array('thisYear' => $year, 'thisMonth' => $month,
                    'feeFieldType' => $category));

                // ������Ŀ����
                $sql = "UPDATE oa_esm_project c LEFT JOIN
                (
                    SELECT projectId,SUM(feeField) AS fee FROM oa_esm_records_field
                    WHERE feeFieldType = '" . $category . "' GROUP BY projectId
                ) f ON c.id = f.projectId
                SET c.feeSubsidyImport = IFNULL(f.fee, 0)";
                $esmFieldRecordDao->_db->query($sql);
                // ���������
                $esmProjectDao->calProjectFee_d();
                // ����������
                $esmProjectDao->calFeeProcess_d();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ��Ŀ�ĵı������ array(projectCode => projectId)
     * @param $esmFieldRecordDao
     * @return array
     */
    function getProjectHash_d($esmFieldRecordDao)
    {
        $projectHash = array();

        // ��ʾ��ȡ��Ŀ
        $projectData = $esmFieldRecordDao->_db->getArray('SELECT id, projectCode FROM oa_esm_project WHERE ExaStatus = "���"');

        if ($projectData) {
            foreach ($projectData as $v) {
                $projectHash[$v['projectCode']] = $v['id'];
            }
        }

        return $projectHash;
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