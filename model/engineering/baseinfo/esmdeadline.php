<?php

/**
 * @author show
 * @Date 2014年5月13日 15:39:29
 * @version 1.0
 * @description:日志填报期限 Model层
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
     * 获取最近的日志截止填报日期
     */
    function getDeadlineInfo_d()
    {
        // 获取当前时间
        $now = microtime(true);
        $day = date('d', $now);

        // 获取当前月数据
        $thisMonth = $this->find(array('month' => date('n', $now)));

        if ($thisMonth['day'] >= $day) {
            $rangeId = $thisMonth['useRangeId'];
            $startMonth = date('n', strtotime("last month", $now));
        } else {
            // 获取下个月数据
            $nextMonth = $this->find(array('month' => date('n', strtotime("next month", $now))));
            $rangeId = $nextMonth['useRangeId'];
            $startMonth = $thisMonth['month'];
        }
        // 如果当前月份是1月，则年份扣1
        $year = $startMonth == 12 ? date('Y', $now) - 1 : date('Y', $now);

        // 结果返回
        return array(
            'date' => $year . '-' . $startMonth . '-1',
            'useRangeId' => $rangeId
        );
    }

    /**
     * 获取截止年月
     * @return array
     */
    function getDeadMonth_d()
    {
        // 获取当前时间
        $now = time();
        $day = date('d', $now);
        $month = date('n', $now);

        // 获取当前月数据
        $thisMonth = $this->find(array('month' => date('n', $now)));

        // 如果还在服务保护器内，则取上个月
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
     * 更新截止年月到目前为止的数据
     * @param string $category
     * @return string
     * @throws Exception
     */
    function updateESMData_d($category = '')
    {
        set_time_limit(0);

        // 如果传入参数数组，则进行转换
        if (is_array($category)) {
            $category = $category['category'];
        }

        // 获取需要更新的年月
        $deadMonth = $this->getDeadMonth_d();

        try {
            switch ($category) {
                case 'field' : // 更新报销支付
                    $recordDao = new model_engineering_records_esmfieldrecord();
                    $recordDao->businessFeeUpdate_d('field', $deadMonth['year'], $deadMonth['month']);
                    break;
                case 'equ' : // 更新设备决算
                    $deviceDao = new model_engineering_resources_esmdevicefee();
                    $deviceDao->updateFee_d($deadMonth['year'], $deadMonth['month']);
                    break;
                case 'person' : // 更新人力决算
                    $personDao = new model_engineering_person_esmperson();
                    $personDao->updateSalary_d($deadMonth['year'], $deadMonth['month']);
                    break;
                case 'projectInfo' : // 更新项目数据
                    $projectDao = new model_engineering_project_esmproject();
                    $projectDao->updateProjectFields_d('', $deadMonth['month']);
                    break;
                case 'fee' : // 决算数据存档
                    if (isset($deadMonth['needSaveData'])) {
                        $detailDao = new model_engineering_records_esmfielddetail();
                        $detailDao->saveFeeVersion_d('budgetPerson', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetField', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetEqu', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetOutsourcing', $deadMonth['year'], $deadMonth['month'], '');
                        $detailDao->saveFeeVersion_d('budgetOther', $deadMonth['year'], $deadMonth['month'], '');
                    }
                    break;
                case 'income' : // 项目收入存档
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
     * 获取当前保护期的起止日
     * @return array
     */
    function getCurrentSaveDateRange()
    {
        // 获取当前时间
        $now = microtime(true);

        // 获取当前月数据
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