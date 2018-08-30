<?php

/**
 * @author show
 * @Date 2013��10��29�� 17:16:28
 * @version 1.0
 * @description:��Ŀ�������� Model��
 */
class model_engineering_person_esmpersonfee extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_personfee";
        $this->sql_map = "engineering/person/esmpersonfeeSql.php";
        parent:: __construct();
    }

    /**
     * ͬ����Ա������
     * ҵ���߼�������
     * 1����ȡ��ǰ���е�����
     * 2���Ա����ݲ���
     * 3������������ݣ��䶯��ɾ����������ȱ�ٵ�������ɾ����ɾ����
     * @param $thisYear
     * @param $thisMonth
     * @param $salary
     * @param $logData
     * @return array
     * @throws Exception
     */
    function synLogInfo_d($thisYear, $thisMonth, $salary, $logData)
    {
        $insertData = $realInsertData = $projectIdArr = array(); // ���������

        // ���������Ҫ�������ȥ�����߼�
        if ($logData) {
            $userId = $_SESSION['USER_ID'];
            $userName = $_SESSION['USER_ID'];
            $now = date("Y-m-d H:i:s");

            // ��Ҫɾ��������
            $delIdArr = array();

            // ��ȡ������������
            $thisPeriodData = $this->getThisPeriodData_d($thisYear, $thisMonth);
            try {

                //����ͬ��
                foreach ($logData as $k => $v) {
                    $v['userId'] = $v['createId'];
                    $v['userName'] = $v['createName'];
                    $v['feePerson'] = isset($salary[$v['userId']]) ?
                        bcmul($v['inWorkRate'], $salary[$v['userId']]['price'], 2) : 0;
                    $v['updateId'] = $v['createId'] = $userId;
                    $v['updateName'] = $v['createName'] = $userName;
                    $v['updateTime'] = $v['createTime'] = $now;

                    // �����ǰ
                    $index = $v['projectId'] . "_" . $v['userId'];
                    if (!isset($thisPeriodData[$index]) ||
                        (
                            isset($thisPeriodData[$index]) && ($thisPeriodData[$index]['feePerson'] != $v['feePerson']
                            || $thisPeriodData[$index]['inWorkRate'] != $v['inWorkRate'])
                        )
                    ) {
                        // ������������
                        $realInsertData[] = $v;

                        // ������Ҫ�������Ŀid
                        if (!in_array($v['projectId'], $projectIdArr)) {
                            $projectIdArr[] = $v['projectId'];
                        }

                        // �ж��Ƿ���Ҫɾ��
                        if (isset($thisPeriodData[$index])) {
                            $delIdArr[] = $thisPeriodData[$index]['id'];
                        }
                        // ɾ������
                        unset($thisPeriodData[$index]);

                    } else if(isset($thisPeriodData[$index])) { // ����Ѵ��ڣ������ޱ仯��˵���ⲿ�־��㲻��Ҫ�䶯
                        // ɾ������
                        unset($thisPeriodData[$index]);
                    }
                }

                // ����ǿգ�˵����Щ�����Ѿ���ɾ������Ҫ����
                if (!empty($thisPeriodData)) {
                    foreach ($thisPeriodData as $v) {
                        $delIdArr[] = $v['id'];

                        // ������Ҫ�������Ŀid
                        if (!in_array($v['projectId'], $projectIdArr)) {
                            $projectIdArr[] = $v['projectId'];
                        }
                    }
                }

                if (!empty($realInsertData)) {
                    if (!empty($delIdArr)) {
                        // ��ɾ��ԭ��������
                        $this->_db->query("DELETE FROM $this->tbl_name WHERE id IN (" . implode(',', $delIdArr) . ")");
                    }

                    // �����µ�����
                    $dataKeyLength = count($realInsertData) - 1; // key ����

                    foreach ($realInsertData as $k => $v) {
                        $insertData[] = $v;
                        // 100����ȡ�������鳤��ʱ����������
                        if (($k % 100 == 0 && $k != 0) || $dataKeyLength == $k) {
                            // ��������
                            $this->createBatch($insertData);
                            $insertData = array();
                        }
                    }
                }
            } catch (Exception $e) {
                throw $e;
            }
        }

        // ��־д��
        $logDao = new model_engineering_baseinfo_esmlog();
        $logDao->addLog_d(-1, '������������', count($realInsertData) . '|' . $thisMonth);

        return $projectIdArr;
    }

    /**
     * ��ȡ��ǰ���е���������
     * @param $thisYear
     * @param $thisMonth
     * @return array
     */
    function getThisPeriodData_d($thisYear, $thisMonth)
    {
        // ��ѯ���µľ�������
        $data = $this->findAll(array('thisYear' => $thisYear, 'thisMonth' => $thisMonth));
        $rs = array();

        foreach ($data as $v) {
            $rs[$v['projectId'] . "_" . $v['userId']] = $v;
        }
        return $rs;
    }
}