<?php

/**
 * @author show
 * @Date 2014��5��13�� 15:39:29
 * @version 1.0
 * @description:��־����� Model��
 */
class model_engineering_baseinfo_esmdeadline extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_baseinfo_log_deadline";
        $this->sql_map = "engineering/baseinfo/esmdeadlineSql.php";
        parent::__construct();
    }

    /**
     * ��ȡ�������־��ֹ�����
     */
    function getDeadlineInfo_d()
    {
        // ��ȡ��ǰʱ��
        $now = microtime(true);
        $day = date('d', $now);

        // ��ȡ��ǰ������
        $thisMonth = $this->find(array('month' => date('n', $now)));

        if ($thisMonth['day'] >= $day) {
            $rangeId = $thisMonth['useRangeId'];
            $startMonth = date('n', strtotime("last month", $now));
        } else {
            // ��ȡ�¸�������
            $nextMonth = $this->find(array('month' => date('n', strtotime("next month", $now))));
            $rangeId = $nextMonth['useRangeId'];
            $startMonth = $thisMonth['month'];
        }
        // �����ǰ�·���1�£�����ݿ�1
        $year = $startMonth == 12 ? date('Y', $now) - 1 : date('Y', $now);

        // �������
        return array(
            'date' => $year . '-' . $startMonth . '-1',
            'useRangeId' => $rangeId
        );
    }

    /**
     * ��ȡ��ֹ����
     * @return array
     */
    function getDeadMonth_d()
    {
        // ��ȡ��ǰʱ��
        $now = time();
        $day = date('d', $now);
        $month = date('n', $now);

        // ��ȡ��ǰ������
        $thisMonth = $this->find(array('month' => date('n', $now)));

        // ������ڷ��񱣻����ڣ���ȡ�ϸ���
        if ($day > $thisMonth['saveDayForSer']) {
            $rst = array(
                'year' => date('Y', $now),
                'month' => $month
            );
        } else if ($day == $thisMonth['saveDayForSer']) {
            $rst = array(
                'year' => date('Y', strtotime("last month", $now)),
                'month' => date('n', strtotime("last month", $now)),
                'needSaveData' => 1
            );
        } else {
            $rst = array(
                'year' => date('Y', strtotime("last month", $now)),
                'month' => date('n', strtotime("last month", $now))
            );
        }
        return $rst;
    }

    /**
     * ���½�ֹ���µ�ĿǰΪֹ������
     * @param string $category
     * @return string
     * @throws Exception
     */
    function updateESMData_d($category = '')
    {
        set_time_limit(0);

        // �������������飬�����ת��
        if (is_array($category)) {
            $category = $category['category'];
        }

        // ��ȡ��Ҫ���µ�����
        $deadMonth = $this->getDeadMonth_d();

        try {
            switch ($category) {
                case 'field' : // ���±���֧��
                    $recordDao = new model_engineering_records_esmfieldrecord();
                    $recordDao->businessFeeUpdate_d('field', $deadMonth['year'], $deadMonth['month']);
                    break;
                case 'equ' : // �����豸����
                    $deviceDao = new model_engineering_resources_esmdevicefee();
                    $deviceDao->updateFee_d($deadMonth['year'], $deadMonth['month']);
                    break;
                case 'person' : // ������������
                    $personDao = new model_engineering_person_esmperson();
                    $personDao->updateSalary_d($deadMonth['year'], $deadMonth['month']);
                    break;
                case 'projectInfo' : // ������Ŀ����
                    $projectDao = new model_engineering_project_esmproject();
                    $projectDao->updateProjectFields_d('', $deadMonth['month']);
                    break;
                case 'fee' : // �������ݴ浵
                    if (isset($deadMonth['needSaveData'])) {
                        $detailDao = new model_engineering_records_esmfielddetail();
                        $detailDao->saveFeeVersion_d('budgetPerson', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetField', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetEqu', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetOutsourcing', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetOther', $deadMonth['year'], $deadMonth['month'], '');
                    }
                    break;
                case 'income' : // ��Ŀ����浵
                    if (isset($deadMonth['needSaveData'])) {
                        $incomeDao = new model_engineering_records_esmincome();
                        $incomeDao->updateIncome_d('', $deadMonth['month']);
                    }
                    break;
                default :
            }
        } catch (Exception $e) {
            throw $e;
        }
        return 'success';
    }

    /**
     * ��ȡ��ǰ�����ڵ���ֹ��
     * @return array
     */
    function getCurrentSaveDateRange()
    {
        // ��ȡ��ǰʱ��
        $now = microtime(true);

        // ��ȡ��ǰ������
        $thisMonthArr = $this->find(array('month' => date('n', $now)));
        $backData = array();
        if ($thisMonthArr) {
            $saveDayForPro = $thisMonthArr['saveDayForPro'];
            if (!empty($saveDayForPro) && $saveDayForPro !== 0) {
                $saveDayForPro = ($saveDayForPro < 10) ? "0" . $saveDayForPro : $saveDayForPro;
                $year = date('Y', $now);
                $month = date('m', $now);
                $today = strtotime(date('Y-m-d', $now));
                $backData['startDate'] = "{$year}-{$month}-01";
                $backData['endDate'] = "{$year}-{$month}-{$saveDayForPro}";
                $backData['inRange'] = ($today >= strtotime($backData['startDate']) && $today <= strtotime($backData['endDate'])) ? "1" : "0";
            }
        }

        return $backData;
    }
}