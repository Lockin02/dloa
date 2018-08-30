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
 * �����߼�˵��
 * 1�������ֳ������Լ���������
 */
class model_engineering_records_strategy_field extends model_base implements iesmfieldrecord
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
     * ����������£�
     * 1��ƥ���Ƿ���·�Χ���ж�����Ŀ�ı��������˱仯
     * 2�����ڴ�����Ŀ���¶�ȡ��������
     * 3��û�з����仯�����ݣ���ȡ��һ�δ浵�����ݣ�Ȼ����һ�ݣ���Ϊ��ǰ�·ݵ����ݡ�
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
        ini_set('memory_limit', '1024M');	//�����ڴ�

        // ʵ������Ŀ
        $esmProjectDao = new model_engineering_project_esmproject();
        // ��ȡ��Ŀת������
        $projectHash = $this->getProjectHash_d($esmFieldRecordDao);

        // ʵ��������
        $expenseDao = new model_finance_expense_expense();
        // ����µĴ���
        $periodInfo = $this->getPeriod_d($year, $month);

        // ��־��ʼ��
        $logDao = new model_engineering_baseinfo_esmlog();

        $logDao->addLog_d(-2, '���±���֧��-ê��', "���ʼ����" . $year . "-" . $month);

        // ���뵥���Ĵ��� - ��������д�������Ŀ��ţ�ֻ�������Ŀ�����������
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

            $logDao->addLog_d(-2, '���±���֧��-ê��', "����Ŀ���£�" . $sourceParam['projectCode']);
        } else {
            // ����һ������
            $filterTime = strtotime($year . "-" . $month . "-01") - 1;

            // ��ȡ���һ�θ��µľ�����Ϣ
            $lastOne = $esmFieldRecordDao->find(" feeFieldType IN('field','subsidy') AND UNIX_TIMESTAMP(thisDate) <= $filterTime ", "ID DESC");

            // ���ɼ���ʱ���
            $checkBeginTime = strtotime($lastOne['thisDate']); // ��ʼʱ���
            $checkEndTime = strtotime(date("Y-m-d", time())) + 86399; // ����ʱ���

            $changeProjectList = $expenseDao->getChangeProjectCodeList_d($checkBeginTime, $checkEndTime); // ��ǰ�䶯������Ŀ�б�
            $changeProjectCodes = implode(',', $changeProjectList);
            $changeProjectIdArr = $this->returnProjectIdArr_d($changeProjectList, $projectHash); // ��ĿID����
            $changeProjectIds = implode(',', $changeProjectIdArr);

            // ����ǵ��£�ֱ�Ӹ��¼���
            if ($lastOne['thisYear'] == $year && $lastOne['thisMonth'] == $month) {
                // �����ޱ仯������
                $esmFieldRecordDao->updateNoChangeData_d($year, $month, 'field,subsidy', $changeProjectIds,
                    $periodInfo['thisTime']);
            } else {
                $noChangeData = $esmFieldRecordDao->getNoChangeData_d($lastOne['thisYear'], $lastOne['thisMonth'],
                    'field,subsidy', $changeProjectIds);
            }

            $logDao->addLog_d(-2, '���±���֧��-ê��', "��Ŀ��ȡ");
        }

        // ��Ŀ����
        $data = $expenseDao->getAllProjectFee_d($year, $month, $changeProjectCodes);
        $logDao->addLog_d(-2, '���±���֧��-ê��', "��Ŀ�����ȡ");

        // �����еĲ�������
        $otherDatasDao = new model_common_otherdatas();
        $subsidyIds = $otherDatasDao->getConfig('engineering_budget_subsidy_id');
        $filterIds = $otherDatasDao->getConfig('engineering_budget_expense_id');

        // ��ȡ��������
        $subsidyData = $expenseDao->getAllProjectSomeFee_d($subsidyIds, $year, $month, $changeProjectCodes);
        $logDao->addLog_d(-2, '���±���֧��-ê��', "��ȡ��������");

        // ��ȡ���˾���
        $filterData = $expenseDao->getAllProjectSomeFee_d($filterIds, $year, $month, $changeProjectCodes);
        $logDao->addLog_d(-2, '���±���֧��-ê��', "��ȡ���˾���");

        if ($data) {
            try {
                // ɾ����ǰ������ , �ֳ�������
                $esmFieldRecordDao->deleteInfo_d($year, $month, 'field', $changeProjectIds);
                $esmFieldRecordDao->deleteInfo_d($year, $month, 'subsidy', $changeProjectIds);
                $logDao->addLog_d(-2, '���±���֧��-ê��', "ɾ��ԭ��¼");

                // hash����ת������
                $subsidyHash = !empty($subsidyData) ? $this->hashChange_d($subsidyData, $projectHash) : array();

                // hash����ת������
                $filterHash = !empty($filterData) ? $this->hashChange_d($filterData, $projectHash) : array();

                // ��������
                $insertData = array();
                // key ����
                $dataKeyLength = count($data) - 1;
                // ��Ҫ���µ���Ŀid
                $projectIds = array();

                foreach ($data as $k => $v) {

                    $projectId = isset($projectHash[$v['projectNo']]) ? $projectHash[$v['projectNo']] : '';
                    if (!$projectId && $dataKeyLength != $k) {
                        continue;
                    }

                    if ($projectId) {
                        $projectIds[] = $projectId;

                        // ������ڹ��˵ľ���
                        $v['costMoney'] = isset($filterHash[$projectId]) && $filterHash[$projectId] > 0 ?
                            bcsub($v['costMoney'], $filterHash[$projectId], 2) : $v['costMoney'];

                        // ��������
                        $subsidy = isset($subsidyHash[$projectId]) && $subsidyHash[$projectId] > 0 ?
                            $subsidyHash[$projectId] : 0;

                        if ($subsidy > 0) {
                            // ���� - ��������
                            $insertData[] = array(
                                'thisYear' => $periodInfo['thisYear'], 'thisMonth' => $periodInfo['thisMonth'],
                                'thisDate' => $periodInfo['thisDate'], 'createTime' => $periodInfo['thisTime'],
                                'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                                'projectId' => $projectId, 'feeField' => $subsidy, 'feeFieldType' => 'subsidy',
                                'isChange' => 1
                            );
                        }

                        // �ֳ����� = �ֳ����� - ����
                        $v['costMoney'] = bcsub($v['costMoney'], $subsidy, 2);

                        if ($v['costMoney'] > 0) {
                            // �ֳ�����
                            $insertData[] = array(
                                'thisYear' => $periodInfo['thisYear'], 'thisMonth' => $periodInfo['thisMonth'],
                                'thisDate' => $periodInfo['thisDate'], 'createTime' => $periodInfo['thisTime'],
                                'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                                'projectId' => $projectId, 'feeField' => $v['costMoney'], 'feeFieldType' => 'field',
                                'isChange' => 1
                            );
                        }
                    }

                    // 100����ȡ�������鳤��ʱ����������
                    if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                        // ��������
                        $esmFieldRecordDao->createBatch($insertData);
                        $insertData = array();
                    }
                }
                $logDao->addLog_d(-2, '���±���֧��-ê��', "�����б䶯�����ݴ������");

                // ������µ�û�б䶯������
                if (isset($noChangeData)) {
                    // key ����
                    $dataKeyLength = count($noChangeData) - 1;

                    foreach ($noChangeData as $k => $v) {
                        // �ֳ�����
                        $insertData[] = array(
                            'thisYear' => $periodInfo['thisYear'], 'thisMonth' => $periodInfo['thisMonth'],
                            'thisDate' => $periodInfo['thisDate'], 'createTime' => $periodInfo['thisTime'],
                            'createId' => $_SESSION['USER_ID'], 'createName' => $_SESSION['USERNAME'],
                            'projectId' => $v['projectId'], 'feeField' => $v['feeField'], 'feeFieldType' => $v['feeFieldType'],
                            'isChange' => 1
                        );

                        // 100����ȡ�������鳤��ʱ����������
                        if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                            // ��������
                            $esmFieldRecordDao->createBatch($insertData);
                            $insertData = array();
                        }
                    }
                }
                $logDao->addLog_d(-2, '���±���֧��-ê��', "�����ޱ䶯�����ݴ������");

                if (!empty($projectIds)) {
                    $projectIds = implode(',', $projectIds);
                    $logDao->addLog_d(-2, '���±���֧��-ê��', "������ĿԤ����:" . $projectIds);

                    // ������Ŀ���ֳ�������Ϣ
                    $sql = "UPDATE oa_esm_project c LEFT JOIN oa_esm_records_field f ON " .
                        "c.id = f.projectId  AND f.feeFieldType = 'field' AND f.thisYear = '" . $year .
                        "' AND f.thisMonth = '" . $month . "' SET
                    c.feeField = IFNULL(f.feeField,0) WHERE c.id IN($projectIds)";
                    $esmFieldRecordDao->_db->query($sql);

                    // ������Ŀ�Ĳ������
                    $sql = "UPDATE oa_esm_project c LEFT JOIN oa_esm_records_field f ON " .
                        "c.id = f.projectId  AND f.feeFieldType = 'subsidy' AND f.thisYear = '" . $year .
                        "' AND f.thisMonth = '" . $month . "' SET
                    c.feeSubsidy = IFNULL(f.feeField,0) WHERE c.id IN($projectIds)";
                    $esmFieldRecordDao->_db->query($sql);
                    // ���������
                    $esmProjectDao->calProjectFee_d(null, $projectIds);
                    // �����⳵�Ǽǵ�Ԥ���� PMS 715
                    $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
                    $rentalcarDao->updateProjectFieldFeeWithCarFee($projectIds);
                    // ����������
                    $esmProjectDao->calFeeProcess_d(null, $projectIds);

                    $logDao->addLog_d(-2, '���±���֧��-ê��', "������ĿԤ�������");
                }
            } catch (Exception $e) {
                throw $e;
            }
        }

        // ��־д��
        $logDao->addLog_d(-1, '���±���֧��', count($data) . '|' . $month);
    }

    /**
     * ���ڴ���
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
     * ��ȡ��Ŀ�ĵı������ array(projectCode => projectId)
     * @param $esmFieldRecordDao
     * @return array
     */
    function getProjectHash_d($esmFieldRecordDao)
    {
        $projectHash = array();

        // ��ʾ��ȡ��Ŀ
        $projectData = $esmFieldRecordDao->_db->getArray('SELECT id, projectCode FROM oa_esm_project');

        if ($projectData) {
            foreach ($projectData as $v) {
                $projectHash[$v['projectCode']] = $v['id'];
            }
        }

        return $projectHash;
    }

    /**
     * ���ر䶯����ĿID
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
     * HASHת��
     * @param $data
     * @param $projectHash
     * @return array
     */
    function hashChange_d($data, $projectHash)
    {
        $hash = array();

        // ת�崦��
        foreach ($data as $v) {
            if ($projectHash[$v['projectNo']]) {
                $hash[$projectHash[$v['projectNo']]] = $v['costMoney'];
            }
        }

        return $hash;
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