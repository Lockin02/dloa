<?php

/**
 * @author show
 * @Date 2013年9月22日 14:46:02
 * @version 1.0
 * @description:项目周进度状况 Model层
 */
class model_engineering_weekreport_weekstatus extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_weekstatus";
        $this->sql_map = "engineering/weekreport/weekstatusSql.php";
        parent:: __construct();
    }

    /**
     * 获取周数据
     */
    function getWeekStatus_d($projectId = null, $weekNo = null, $mainId = null)
    {
        $obj = $this->find(array('mainId' => $mainId));
        if ($mainId && !empty($obj)) {
            return $this->getNowWeekStatus_d($projectId, $weekNo, $mainId);
        } else {
            return $this->getNewWeekStatus_d($projectId, $weekNo);
        }
    }

    /**
     * 获取新的项目进展
     */
    function getNewWeekStatus_d($projectId, $weekNo)
    {
        $weekDao = new model_engineering_baseinfo_week();
        //查询周情况
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        //获取日志的开始、结束日期
        $worklogDates = $esmworklogDao->getDates_d($projectId);
        //获取任务信息
        $esmactivityDao = new model_engineering_activity_esmactivity();

        //获取项目信息
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        $esmprojectObj = $esmprojectDao->feeDeal($esmprojectObj);
        $esmprojectObj = $esmprojectDao->contractDeal($esmprojectObj);
        $isAProject = $esmprojectDao->isCategoryAProject_d($esmprojectObj); // 是否是A类项目

        //获取实际周次
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $weekDateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);

        //获取已工作周次 - 如果是没写过日志的,预计周
        if ($worklogDates['beginDate'] && strtotime($worklogDates['beginDate']) < strtotime($esmprojectObj['planBeginDate'])) {
            $workedWeekArr = $weekDao->findEsmRealWeekNo($worklogDates['beginDate'], $weekDateInfo['endDate']);
        } else {
            $workedWeekArr = $weekDao->findEsmRealWeekNo($esmprojectObj['planBeginDate'], $weekDateInfo['endDate']);
        }

        // 费用进展
        $processFee = round($esmprojectObj['feeAllProcess'], 1);

        //获取即时项目进度
        $projectProcess = in_array($esmprojectObj['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) ?
            $esmactivityDao->getActFinanceProcess_d($esmprojectObj, null, $weekDateInfo['endDate'])
            : $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate'], $isAProject);

        // 计划进度
        $processPlan = $projectProcess;

        //项目总进展
        $projectAllProcess = array(
            'weekNo' => '总进展',
            'processPlan' => $processPlan > 100 ? 100 : round($processPlan, 4), // 计划进度
            'processAct' => $projectProcess > 100 ? 100 : round($projectProcess, 4), // 实际进度
            'processFee' => $processFee
        );

        //最近5周
        $lastFiveWeek = array();
        $weekLength = count($workedWeekArr);
        $lastWeekNum = $weekLength - ($weekLength > 5 ? 5 : $weekLength);
        for ($i = $lastWeekNum; $i < $weekLength; $i++) {
            //计算即时进度部分
            $year = '20' . substr($workedWeekArr[$i], 0, 2);
            $week = substr($workedWeekArr[$i], 2, 2);
            $dateInfo = $weekDao->getWeekRange($week, $year);
            $processAct = in_array($esmprojectObj['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) ?
                $esmactivityDao->getActFinanceProcess_d($esmprojectObj, $dateInfo['beginDate'], $dateInfo['endDate'])
                : round($esmactivityDao->getActCountProcess_d($projectId, $dateInfo['beginDate'],
                    $dateInfo['endDate'], $isAProject, true), 1);

            // 计划进度
            $processPlan = $processAct;

            $lastFiveWeek[] = array(
                'weekNo' => $workedWeekArr[$i],
                'processPlan' => $processPlan,
                'processAct' => $processAct,
                'processFee' => '--'
            );
        }
        // 放入总进展，并反转
        $lastFiveWeek[] = $projectAllProcess;
        return array_reverse($lastFiveWeek);
    }

    /**
     * 获取已存在的项目进展信息
     */
    function getNowWeekStatus_d($projectId = null, $weekNo = null, $mainId)
    {
        return $this->findAll(array('mainId' => $mainId));
    }

    /**
     * 显示表格
     */
    function showWeekStatus_d($object)
    {
        if ($object) {
            $strArr = array(
                'head' => "<table class='form_in_table'><thead><tr class='main_tr_header'><th width='10%'>类型</th>",
                'processPlan' => "<tr class='tr_even'><td>计划进展</td>",
                'processAct' => "<tr class='tr_even'><td>实际进展</td>",
                'processFee' => "<tr class='tr_even'><td>费用进展</td>"
            );
            $setWeekProcess = false;
            foreach ($object as $key => $val) {
                $perChar = $val['processFee'] == '--' ? "" : "%"; //是否加入百分号
                $strArr['head'] .= "<th width='10%'>" . $val['weekNo']
                    . "<input type='hidden' name='statusreport[weekstatus][$key][weekNo]' value='$val[weekNo]'/>"
                    . "<input type='hidden' name='statusreport[weekstatus][$key][processPlan]' value='$val[processPlan]'/>"
                    . "<input type='hidden' name='statusreport[weekstatus][$key][processAct]' value='$val[processAct]'/>"
                    . "<input type='hidden' name='statusreport[weekstatus][$key][processFee]' value='$val[processFee]'/>"
                    . "<input type='hidden' name='statusreport[weekstatus][$key][id]' value='$val[id]'/>"
                    . "</th>";
                $inputProcessAct = $val['processAct'];
                $inputProcessPlan = $val['processPlan'];
                $val['processAct'] = round($val['processAct'],2);
                $val['processPlan'] = round($val['processPlan'],2);
                if (is_numeric($val['weekNo'])) {
                    if ($setWeekProcess) {
                        $strArr['processAct'] .= "<td>{$val['processAct']} %</td>";
                    } else {
                        $setWeekProcess = true;
                        $strArr['processAct'] .= "<td>{$val['processAct']} %<input type='hidden' name='statusreport[weekProcess]' value='{$inputProcessAct}'/></td>";
                    }
                    $strArr['processPlan'] .= "<td>" . $val['processPlan'] . " %</td>";
                    $strArr['processFee'] .= "<td>--</td>";
                } else {
                    $strArr['processPlan'] .= "<td>" . $val['processPlan'] . " %</td>";
                    $strArr['processAct'] .= "<td>" . $val['processAct'] . " %" .
                        "<input type='hidden' name='statusreport[projectProcess]' value='{$inputProcessAct}'/>" .
                        "</td>";
                    $strArr['processFee'] .= "<td>" . $val['processFee'] . " $perChar</td>";
                }
            }
            $strArr['head'] .= "<th></th></thead></tr>";
            $strArr['processPlan'] .= "<td></td></tr>";
            $strArr['processAct'] .= "<td></td></tr>";
            $strArr['processFee'] .= "<td></td></tr></table>";
            return implode($strArr);
        }
    }

    /**
     * 显示周报执行情况
     */
    function viewWeekStatus_d($object)
    {
        if ($object) {
            $strArr = array(
                'head' => "<table class='form_in_table'><thead><tr class='main_tr_header'><th width='10%'>类型</th>",
                'processPlan' => "<tr class='tr_even'><td>计划进展</td>",
                'processAct' => "<tr class='tr_even'><td>实际进展</td>",
                'processFee' => "<tr class='tr_even'><td>费用进展</td>"
            );
            foreach ($object as $val) {
                $perChar = $val['processFee'] == '--' ? "" : "%"; //是否加入百分号

                $strArr['head'] .= "<th width='10%'>{$val['weekNo']}</th>";
                if (is_numeric($val['weekNo'])) {
                    $strArr['processAct'] .= "<td>{$val['processAct']} %</td>";
                    $strArr['processPlan'] .= "<td>{$val['processPlan']} %</td>";
                    $strArr['processFee'] .= "<td>--</td>";
                } else {
                    $strArr['processPlan'] .= "<td>{$val['processPlan']} %</td>";
                    $strArr['processAct'] .= "<td>{$val['processAct']} %</td>";
                    $strArr['processFee'] .= "<td>{$val['processFee']} $perChar</td>";
                }
            }
            $strArr['head'] .= "<th></th></thead></tr>";
            $strArr['processPlan'] .= "<td valign='top' align='left'</td></tr>";
            $strArr['processAct'] .= "<td valign='top' align='left'></td></tr>";
            $strArr['processFee'] .= "<td valign='top' align='left'></td></tr></table>";
            return implode($strArr);
        }
    }

    /**
     * 更新表记录
     */
    function update_d($mainId, $weekStatusInfo)
    {
        try {
            $this->start_d();
            foreach ($weekStatusInfo as $val) {
                $this->update(array('mainId' => $mainId, 'weekNo' => $val['weekNo']), $val);
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }
}