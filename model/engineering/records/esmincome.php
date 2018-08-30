<?php

/**
 * @author show
 * @Date 2014��12��23�� 15:43:22
 * @version 1.0
 * @description:��Ŀ���� Model��
 */
class model_engineering_records_esmincome extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_record_income";
        $this->sql_map = "engineering/records/esmincomeSql.php";
        parent::__construct();
    }

    /**
     * @param $projectCode
     * @param $month
     * @return int
     */
    function updateIncome_d($projectCode, $month)
    {
        $projectDao = new model_engineering_project_esmproject();
        $condition = array('ExaStatus' => AUDITED, 'contractType' => 'GCXMYD-01');
        if ($projectCode) {
            $condition['projectCode'] = $projectCode;
        }
        $projectData = $projectDao->findAll($condition, null);

        // ɾ����ǰ����
        $this->delete(array('versionNo' => day_date));

        // ��־д��
        $logDao = new model_engineering_baseinfo_esmlog();
        $month = $month ? $month : date('n');
        $logDao->addLog_d(-1, '��Ŀ����浵', count($projectData) . '|' . $month);

        // �洢�������
        if ($projectData) {
            $now = date('Y-m-d H:i:s');

            foreach ($projectData as $v) {
                $v = $projectDao->feeDeal($v);
                $v = $projectDao->contractDeal($v);

                $this->add_d(array(
                    'versionNo' => day_date,
                    'projectId' => $v['id'],
                    'contractMoney' => $v['contractMoney'],
                    'workRate' => $v['workRate'],
                    'projectProcess' => $v['projectProcess'],
                    'deductMoney' => $v['deductMoney'],
                    'invoiceMoney' => $v['invoiceMoney'],
                    'reserveEarnings' => $v['reserveEarnings'],
                    'curIncome' => $v['curIncome'],
                    'createId' => $_SESSION['USER_ID'],
                    'createName' => $_SESSION['USER_NAME'],
                    'createTime' => $now
                ));
            }
            return 1;
        }

        return 0;
    }

    /**
     * ��ȡ��ʷ����
     * @param $beginDate
     * @param $endDate
     * @param null $projectIds
     * @return array
     */
    function getHistory_d($beginDate, $endDate, $projectIds = null) {
        $beginSql = "";
        if ($beginDate != $endDate) {
            $beginSql = "UNION ALL
                SELECT projectId, -curIncome
                FROM oa_esm_record_income
                WHERE projectId IN(" . $projectIds .")
                AND versionNo = '" . $beginDate ."'";
        }
        $sql = "SELECT
            projectId, SUM(curIncome) AS curIncome
            FROM
            (
                SELECT projectId, curIncome
                FROM oa_esm_record_income
                WHERE projectId IN(" . $projectIds .")
                AND versionNo = '" . $endDate ."'
                $beginSql
            ) c
            GROUP BY projectId";
        $data =  $this->_db->getArray($sql);

        $result = array();

        // �ж��������ݣ������ת��
        if ($data[0]['projectId']) {
            foreach ($data as $v) {
                $result[$v['projectId']] = $v;
            }
        }

        return $result;
    }
}