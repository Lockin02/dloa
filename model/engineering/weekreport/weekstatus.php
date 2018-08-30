<?php

/**
 * @author show
 * @Date 2013��9��22�� 14:46:02
 * @version 1.0
 * @description:��Ŀ�ܽ���״�� Model��
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
     * ��ȡ������
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
     * ��ȡ�µ���Ŀ��չ
     */
    function getNewWeekStatus_d($projectId, $weekNo)
    {
        $weekDao = new model_engineering_baseinfo_week();
        //��ѯ�����
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        //��ȡ��־�Ŀ�ʼ����������
        $worklogDates = $esmworklogDao->getDates_d($projectId);
        //��ȡ������Ϣ
        $esmactivityDao = new model_engineering_activity_esmactivity();

        //��ȡ��Ŀ��Ϣ
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        $esmprojectObj = $esmprojectDao->feeDeal($esmprojectObj);
        $esmprojectObj = $esmprojectDao->contractDeal($esmprojectObj);
        $isAProject = $esmprojectDao->isCategoryAProject_d($esmprojectObj); // �Ƿ���A����Ŀ

        //��ȡʵ���ܴ�
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $weekDateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);

        //��ȡ�ѹ����ܴ� - �����ûд����־��,Ԥ����
        if ($worklogDates['beginDate'] && strtotime($worklogDates['beginDate']) < strtotime($esmprojectObj['planBeginDate'])) {
            $workedWeekArr = $weekDao->findEsmRealWeekNo($worklogDates['beginDate'], $weekDateInfo['endDate']);
        } else {
            $workedWeekArr = $weekDao->findEsmRealWeekNo($esmprojectObj['planBeginDate'], $weekDateInfo['endDate']);
        }

        // ���ý�չ
        $processFee = round($esmprojectObj['feeAllProcess'], 1);

        //��ȡ��ʱ��Ŀ����
        $projectProcess = in_array($esmprojectObj['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) ?
            $esmactivityDao->getActFinanceProcess_d($esmprojectObj, null, $weekDateInfo['endDate'])
            : $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate'], $isAProject);

        // �ƻ�����
        $processPlan = $projectProcess;

        //��Ŀ�ܽ�չ
        $projectAllProcess = array(
            'weekNo' => '�ܽ�չ',
            'processPlan' => $processPlan > 100 ? 100 : round($processPlan, 4), // �ƻ�����
            'processAct' => $projectProcess > 100 ? 100 : round($projectProcess, 4), // ʵ�ʽ���
            'processFee' => $processFee
        );

        //���5��
        $lastFiveWeek = array();
        $weekLength = count($workedWeekArr);
        $lastWeekNum = $weekLength - ($weekLength > 5 ? 5 : $weekLength);
        for ($i = $lastWeekNum; $i < $weekLength; $i++) {
            //���㼴ʱ���Ȳ���
            $year = '20' . substr($workedWeekArr[$i], 0, 2);
            $week = substr($workedWeekArr[$i], 2, 2);
            $dateInfo = $weekDao->getWeekRange($week, $year);
            $processAct = in_array($esmprojectObj['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) ?
                $esmactivityDao->getActFinanceProcess_d($esmprojectObj, $dateInfo['beginDate'], $dateInfo['endDate'])
                : round($esmactivityDao->getActCountProcess_d($projectId, $dateInfo['beginDate'],
                    $dateInfo['endDate'], $isAProject, true), 1);

            // �ƻ�����
            $processPlan = $processAct;

            $lastFiveWeek[] = array(
                'weekNo' => $workedWeekArr[$i],
                'processPlan' => $processPlan,
                'processAct' => $processAct,
                'processFee' => '--'
            );
        }
        // �����ܽ�չ������ת
        $lastFiveWeek[] = $projectAllProcess;
        return array_reverse($lastFiveWeek);
    }

    /**
     * ��ȡ�Ѵ��ڵ���Ŀ��չ��Ϣ
     */
    function getNowWeekStatus_d($projectId = null, $weekNo = null, $mainId)
    {
        return $this->findAll(array('mainId' => $mainId));
    }

    /**
     * ��ʾ���
     */
    function showWeekStatus_d($object)
    {
        if ($object) {
            $strArr = array(
                'head' => "<table class='form_in_table'><thead><tr class='main_tr_header'><th width='10%'>����</th>",
                'processPlan' => "<tr class='tr_even'><td>�ƻ���չ</td>",
                'processAct' => "<tr class='tr_even'><td>ʵ�ʽ�չ</td>",
                'processFee' => "<tr class='tr_even'><td>���ý�չ</td>"
            );
            $setWeekProcess = false;
            foreach ($object as $key => $val) {
                $perChar = $val['processFee'] == '--' ? "" : "%"; //�Ƿ����ٷֺ�
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
     * ��ʾ�ܱ�ִ�����
     */
    function viewWeekStatus_d($object)
    {
        if ($object) {
            $strArr = array(
                'head' => "<table class='form_in_table'><thead><tr class='main_tr_header'><th width='10%'>����</th>",
                'processPlan' => "<tr class='tr_even'><td>�ƻ���չ</td>",
                'processAct' => "<tr class='tr_even'><td>ʵ�ʽ�չ</td>",
                'processFee' => "<tr class='tr_even'><td>���ý�չ</td>"
            );
            foreach ($object as $val) {
                $perChar = $val['processFee'] == '--' ? "" : "%"; //�Ƿ����ٷֺ�

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
     * ���±��¼
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