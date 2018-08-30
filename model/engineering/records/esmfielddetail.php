<?php

/**
 * @author show
 * @Date 2014��12��25�� 15:53:13
 * @version 1.0
 * @description:��Ŀ������ϸ Model��
 */
class model_engineering_records_esmfielddetail extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_records_fielddetail";
        $this->sql_map = "engineering/records/esmfielddetailSql.php";
        parent::__construct();
    }

    public $feeType = array(
        'budgetPerson' => '����',
        'budgetField' => '����֧��',
        'budgetEqu' => '�豸',
        'budgetOutsourcing' => '���',
        'budgetOther' => '����'
    );

    /**
     * �������汾
     * @param $budgetType
     * @param $year
     * @param $month
     * @param $projectCode
     * @return bool
     */
    function saveFeeVersion_d($budgetType, $year, $month, $projectCode)
    {
        // ��Ŀ��Ϣ��ȡ
        $projectDao = new model_engineering_project_esmproject();

        // ��������
        $thisDate = $year . '-' . $month . '-1';
        $lastTime = strtotime($thisDate) - 1;

        // �����������Ŀ��ţ���ô��������Ŀ������ʶ��
        if ($projectCode) {
            $condition = array('projectCode' => $projectCode);
        } else {
            // ���һ�����ݵ�ƥ��
            $lastType = $budgetType == 'budgetOther' ? 'budgetOther,budgetTrial' : $budgetType;
            // ƥ�����һ�εĸ���ʱ��
            $lastOne = $this->find(" budgetType IN(" . util_jsonUtil::strBuild($lastType) . ") AND UNIX_TIMESTAMP(thisDate) <= $lastTime ",
                "ID DESC");
            // ������һ�θ������ϸ��£���ʼƥ��䶯��Ŀ������ȡȫ����Ŀ
            if (!empty($lastOne) && strtotime($lastOne['thisDate']) == strtotime($thisDate . '-1 month')) {
                $changeProjectIds = $this->findChangeProjectIds_d($budgetType, $year, $month, $lastOne);
                if ($changeProjectIds) {
                    $changeProjectIds = rtrim($changeProjectIds,",");
                    $condition = " id IN (" . $changeProjectIds . ")";
                } else {
                    $condition = null;
                }
            } else {
                $condition = null;
            }
        }

        $projectData = $projectDao->findAll($condition,
            null, 'id,feeCar,feeFlights,feeEqu,feePerson,contractId,contractType,workRate,productLine,feeSubsidyImport');

        // ��־д��
        $logDao = new model_engineering_baseinfo_esmlog();
        $logDao->addLog_d(-1, '�������ݴ浵-' . $this->feeType[$budgetType], count($projectData) . '|' . $month);

        // ѭ����ȡ��ĿԤ��������
        if ($projectData) {
            // �����������
            $deleteCondition = array('thisYear' => $year, 'thisMonth' => $month, 'budgetType' => $budgetType);
            // �����������Ŀ��ţ���ֻ�������Ŀ
            if ($projectCode) {
                $deleteCondition['projectId'] = $projectData[0]['id'];
            }
            $this->delete($deleteCondition);

            // ������������㣬���PK�����ٽ��д���
            if ($budgetType == 'budgetOther') {
                $deleteCondition['budgetType'] = 'budgetTrial';
                $this->delete($deleteCondition);
            }

            // �������ݲ��� - ֻ������������ݣ����ұ���û�䶯����Ŀ
            if (isset($changeProjectIds) && $changeProjectIds && isset($lastOne)) {
                $noChangeData =
                    $this->findAll(" thisDate = '" . $lastOne['thisDate'] . "'
                    AND budgetType = '$budgetType' AND projectId NOT IN(" . $changeProjectIds .")");
                $batchInsert = array();
                foreach ($noChangeData as $v) {
                    unset($v['id']);
                    $v['thisYear'] = $year;
                    $v['thisMonth'] = $month;
                    $v['thisDate'] = $thisDate;
                    $batchInsert[] = $v;
                }
                $this->addBatch_d($batchInsert);

                // PK�������⴦��
                if ($budgetType == 'budgetOther') {
                    $noChangeData =
                        $this->findAll(" thisDate = '" . $lastOne['thisDate'] . "'
                        AND budgetType = 'budgetTrial' AND projectId NOT IN(" . $changeProjectIds . ")");
                    $batchInsert = array();
                    foreach ($noChangeData as $v) {
                        unset($v['id']);
                        $v['thisYear'] = $year;
                        $v['thisMonth'] = $month;
                        $v['thisDate'] = $thisDate;
                        $batchInsert[] = $v;
                    }
                    $this->addBatch_d($batchInsert);
                }
            }
            // Ԥ������
            $budgetDao = new model_engineering_budget_esmbudget();

            foreach ($projectData as $v) {
                // ��ȡԤ��
                $budgetData = $budgetDao->getBudgetData_d(array('projectId' => $v['id']));

                // Ԥ���з�
                $budgetCache = $budgetDao->budgetSplit_d($budgetData);

                // ��ȡ����
                $feeData = $budgetDao->getFee_d($v['id']);

                // �����з�
                $feeCache = $budgetDao->feeSplit_d($feeData);

                switch ($budgetType) {
                    case 'budgetPerson' :
                        // �����ɱ�
                        $data = $budgetDao->getPersonFee_d($budgetCache[0], $feeCache[0], $v);
                        break;
                    case 'budgetField' :
                        // ����֧���ɱ�
                        $data = $budgetDao->getFieldFee_d($budgetCache[1], $feeCache[1], $v);
                        break;
                    case 'budgetEqu' :
                        // �豸�ɱ�
                        $data = $budgetDao->getEquFee_d($budgetCache[2], $feeCache[2], $v);
                        break;
                    case 'budgetOutsourcing' :
                        // ����ɱ�
                        $data = $budgetDao->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $v);
                        break;
                    case 'budgetOther' :
                        // �����ɱ�
                        $data = $budgetDao->getOtherFee_d($budgetCache[4], $feeCache[4], $v);
                        break;
                    default :
                        $data = array();
                }

                // ����������ݣ��Ͳ���
                if ($data) {
                    $batchInsert = array();
                    foreach ($data as $vi) {
                        if ($vi['id'] == 'noId' || $vi['actFee'] == 0) {
                            continue;
                        } else {
                            $batchInsert[] = array(
                                'projectId' => $v['id'],
                                'thisYear' => $year,
                                'thisMonth' => $month,
                                'thisDate' => $thisDate,
                                'budgetName' => $vi['budgetName'],
                                'budgetType' => $vi['budgetType'],
                                'fee' => $vi['actFee'],
                                'feeExpense' => isset($vi['feeExpense']) ? $vi['feeExpense'] : 0,
                                'feeCostMaintain' => isset($vi['feeCostMaintain']) ? $vi['feeCostMaintain'] : 0,
                                'feePayables' => isset($vi['feePayables']) ? $vi['feePayables'] : 0,
                                'feeCar' => isset($vi['feeCar']) ? $vi['feeCar'] : 0,
                                'feeFlights' => isset($vi['feeFlights']) ? $vi['feeFlights'] : 0,
                                'feePK' => isset($vi['feePK']) ? $vi['feePK'] : 0,
                                'feeOther' => isset($vi['feeOther']) ? $vi['feeOther'] : 0,
                                'feeEqu' => isset($vi['feeEqu']) ? $vi['feeEqu'] : 0,
                                'feeEquImport' => isset($vi['feeEquImport']) ? $vi['feeEquImport'] : 0,
                                'feePerson' => isset($vi['feePerson']) ? $vi['feePerson'] : 0,
                                'feeSubsidy' => isset($vi['feeSubsidy']) ? $vi['feeSubsidy'] : 0,
                                'feeSubsidyImport' => isset($vi['feeSubsidyImport']) ? $vi['feeSubsidyImport'] : 0,
                                'feeOutsourcing' => isset($vi['feeOutsourcing']) ? $vi['feeOutsourcing'] : 0
                            );
                        }
                    }
                    $this->addBatch_d($batchInsert);
                }
            }
            return 1;
        }

        return 0;
    }

    /**
     * ƥ��仯�˵���ĿID
     * @param $budgetType
     * @param $year
     * @param $month
     * @param $lastOne
     * @return string
     */
    function findChangeProjectIds_d($budgetType, $year, $month, $lastOne)
    {
        // ��Ŀid
        $projectIdArr = array();

        // �����������ж���Ӧ��Ŀ
        switch ($budgetType) {
            case 'budgetPerson' :
                // ��ѯ�����Ĳ���
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('subsidy','subsidyProvision')
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // ��ѯ���ʵĲ���
                $sql = "SELECT projectId FROM oa_esm_project_personfee WHERE thisYear = $year AND thisMonth = $month";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            case 'budgetField' :
                // ��ѯ������֧�����⳵����Ʊ
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('field','payables','rentCar','flightsShare')
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            case 'budgetEqu' :
                // ��ѯ�豸����
                $beginTime = strtotime($year . "-" . $month . "-01");
                $sql = "SELECT projectId FROM oa_esm_resource_fee WHERE UNIX_TIMESTAMP(beginDate) = $beginTime AND listId IS NULL GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            case 'budgetOutsourcing' :
                // ���������з���ά���Զ����µ��豸Ԥ����������ﲻ������
                break;
            case 'budgetOther' :
                // ƥ������ڵ�PK��Ŀ
                $pkProjectIds = array();
                $sql = "SELECT id FROM oa_esm_project WHERE contractType = 'GCXMYD-04'";
                $data = $this->_db->getArray($sql);
                foreach ($data as $v) {
                    $pkProjectIds[] = $v['id'];
                }
                $pkProjectIds = implode(',', $pkProjectIds);

                // ��ѯ�����Ĳ���
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('subsidy','subsidyProvision')
                        AND projectId IN($pkProjectIds)
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // ��ѯ���ʵĲ���
                $sql = "SELECT projectId FROM oa_esm_project_personfee WHERE thisYear = $year AND thisMonth = $month AND projectId IN($pkProjectIds)";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // ��ѯ������֧�����⳵����Ʊ
                $sql = "SELECT projectId FROM oa_esm_records_field
                    WHERE isChange = 1 AND thisYear = $year AND thisMonth = $month AND feeFieldType IN('field','payables','rentCar','flightsShare')
                         AND projectId IN($pkProjectIds)
                    GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                // ��ѯ������֧�����⳵����Ʊ
                $beginTime = strtotime($year . "-" . $month . "-01");
                $sql = "SELECT projectId FROM oa_esm_resource_fee WHERE UNIX_TIMESTAMP(beginDate) = $beginTime
                    AND projectId IN($pkProjectIds) AND listId IS NULL GROUP BY projectId";
                $data = $this->_db->getArray($sql);
                $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $data);

                break;
            default :
        }

        // ���ɼ���ʱ���
        $checkBeginTime = strtotime($lastOne['thisDate']); // ��ʼʱ���
        $checkEndTime = strtotime(date("Y-m-d", time())) + 86399; // ����ʱ���

        // ����һ��ʱ�䷶Χ�ڷ����˱��
        $sql = "SELECT projectId FROM oa_esm_change_baseinfo WHERE ExaStatus = '���'
            AND UNIX_TIMESTAMP(ExaDT) BETWEEN $checkBeginTime AND $checkEndTime";
        $changeProjectData = $this->_db->getArray($sql);
        $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $changeProjectData);

        // �������ƥ������Ԥ�����Ŀ
        $sql = "SELECT projectId FROM oa_esm_project_budget WHERE budgetType = '$budgetType'
            AND UNIX_TIMESTAMP(updateTime) BETWEEN $checkBeginTime AND $checkEndTime GROUP BY projectId";
        $changeBudgetData = $this->_db->getArray($sql);
        $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $changeBudgetData);

        if (!empty($projectIdArr)) {
            // ��������ƥ��PK��Ŀ����Ӧ����ʽ��ĿҲ��Ҫ����
            $sql = "SELECT m.projectId AS projectId FROM oa_esm_project c
            LEFT JOIN oa_esm_project_mapping m ON c.id = m.pkProjectId
            WHERE c.contractType = 'GCXMYD-04' AND c.id IN(
            " . implode(',', $projectIdArr) . ")";
            $contractProjectFromPK = $this->_db->getArray($sql);
            $projectIdArr = $this->setProjectIdArr_d($projectIdArr, $contractProjectFromPK);
        }

        return implode(',', $projectIdArr);
    }

    function setProjectIdArr_d($projectIdArr, $data)
    {
        foreach ($data as $v) {
            if (!in_array($v['projectId'], $projectIdArr)) {
                $projectIdArr[] = $v['projectId'];
            }
        }
        return $projectIdArr;
    }

    function en2cn($en)
    {
        switch ($en) {
            case 'budgetField' :
                $cn = '����֧���ɱ��ϼ�';
                break;
            case 'budgetOutsourcing' :
                $cn = '����ɱ��ϼ�';
                break;
            case 'budgetEqu' :
                $cn = '�豸�ɱ��ϼ�';
                break;
            case 'budgetTrial' :
            case 'budgetOther' :
                $cn = '�����ɱ��ϼ�';
                break;
            case 'budgetPerson' :
                $cn = '�����ɱ��ϼ�';
                break;
            default :
                $cn = '';
        }
        return $cn;
    }

    /**
     * �������ݲ�ѯ
     * @param $object
     * @return array|bool
     */
    function viewHistory_d($object)
    {
        $beginSql = "";
        if (!empty($object['begin']) && $object['begin'] != $object['end']) {
            $beginSql = "UNION ALL
                SELECT projectId,budgetName,budgetType,-fee,
                    (CASE
                            WHEN budgetType = 'budgetPerson' THEN 0
                            WHEN budgetType = 'budgetField' THEN 1
                            WHEN budgetType = 'budgetEqu' THEN 2
                            WHEN budgetType = 'budgetOutsourcing' THEN 3
                            ELSE 4
                    END) AS sortNo FROM oa_esm_records_fielddetail WHERE projectId = " . $object['projectId'] . "
                AND thisDate = '" . $object['begin'] . "'";
        }
        $sql = "SELECT
            budgetType AS id,budgetName,budgetType,SUM(fee) AS fee FROM
            (
                SELECT projectId,budgetName,budgetType,fee,
                    (CASE
                            WHEN budgetType = 'budgetPerson' THEN 0
                            WHEN budgetType = 'budgetField' THEN 1
                            WHEN budgetType = 'budgetEqu' THEN 2
                            WHEN budgetType = 'budgetOutsourcing' THEN 3
                            ELSE 4
                    END) AS sortNo FROM oa_esm_records_fielddetail WHERE projectId = " . $object['projectId'] . "
                AND thisDate = '" . $object['end'] . "'
                $beginSql
            ) c
            GROUP BY budgetName,budgetType
            HAVING fee <> 0
            ORDER BY sortNo,budgetType";
        $data = $this->_db->getArray($sql);

        // ���շ��ؽ��
        $result = array();

        // ���ݷ���
        if ($data) {
            $tmp = '';
            $feeCount = 0;
            $feeAll = 0;
            $maxIndex = count($data) - 1;
            foreach ($data as $k => $v) {
                if ($tmp != $v['budgetType']) {
                    if (!(($tmp == 'budgetOther' && $v['budgetType'] == 'budgetTrial')
                        || ($tmp == 'budgetTrial' && $v['budgetType'] == 'budgetOther'))
                    ) {
                        if ($tmp) {
                            $result[] = array(
                                'id' => 'noId',
                                'budgetType' => $tmp,
                                'budgetName' => $this->en2cn($tmp),
                                'fee' => $feeCount
                            );
                        }
                        $tmp = $v['budgetType'];
                        $feeAll = bcadd($feeAll, $feeCount, 2);
                        $feeCount = 0;
                    }
                }

                // ��������
                $result[] = $v;

                // С��
                $feeCount = bcadd($feeCount, $v['fee'], 2);

                // �ϼ���
                if ($k == $maxIndex) {
                    $result[] = array(
                        'id' => 'noId',
                        'budgetType' => $tmp,
                        'budgetName' => $this->en2cn($tmp),
                        'fee' => $feeCount
                    );
                    $feeAll = bcadd($feeAll, $feeCount, 2);
                }
            }

            $result[] = array(
                'id' => 'noId',
                'budgetType' => $tmp,
                'budgetName' => '�ϼ�',
                'fee' => $feeAll
            );
        }

        return $result;
    }

    /**
     *
     * @param $feeBeginDate
     * @param $feeEndDate
     * @param null $projectIds
     * @return array|bool
     */
    function getHistory_d($feeBeginDate, $feeEndDate, $projectIds = null)
    {
        $beginSql = "";
        if ($feeBeginDate != $feeEndDate) {
            $beginSql = "UNION ALL
                SELECT projectId, -feePayables, -feeFlights, -feeCar,
                    -feeCostMaintain, -feeEqu, -feeEquImport, -feeExpense,
                    -feeOther, -feeOutsourcing, -feePerson, -feePK,
                    -feeSubsidy, -feeSubsidyImport
                FROM oa_esm_records_fielddetail WHERE projectId IN(" . $projectIds . ")
                AND thisDate = '" . $feeBeginDate . "'";
        }
        // ������������˸���Ϊ�ֶ�ת��
        // feeExpense => feeField���������㣩
        // feeCostMaintain => feeFieldImport������ά�����㣩
        $sql = "SELECT
            projectId, SUM(feeExpense) AS feeField, SUM(feeCostMaintain) AS feeFieldImport,
            SUM(feePayables) AS feePayables, SUM(feePayables) AS feeFlights, SUM(feeCar) AS feeCar,
            SUM(feeEqu) AS feeEqu, SUM(feeEquImport) AS feeEquImport,
            SUM(feeOther) AS feeOther, SUM(feeOutsourcing) AS feeOutsourcing,
            SUM(feePerson) AS feePerson, SUM(feePK) AS feePK, SUM(feeSubsidy) AS feeSubsidy,
            SUM(feeSubsidyImport) AS feeSubsidyImport
            FROM
            (
                SELECT projectId, feePayables, feeFlights, feeCar,
                    feeCostMaintain, feeEqu, feeEquImport, feeExpense,
                    feeOther, feeOutsourcing, feePerson, feePK,
                    feeSubsidy, feeSubsidyImport
                FROM oa_esm_records_fielddetail WHERE projectId IN(" . $projectIds . ")
                AND thisDate = '" . $feeEndDate . "'
                $beginSql
            ) c
            GROUP BY projectId";
        $data = $this->_db->getArray($sql);

        $result = array();

        // �ж��������ݣ������ת��
        if ($data[0]['projectId']) {
            foreach ($data as $v) {
                $feeAll = 0;
                foreach ($v as $ki => $vi) {
                    if ($ki != 'projectId') {
                        $feeAll = bcadd($feeAll, $vi, 2);
                    }
                }
                $v['feeAll'] = $feeAll;
                $result[$v['projectId']] = $v;
            }
        }

        return $result;
    }
}