<?php

/**
 * @author Show
 * @Date 2011��11��28�� ����һ 15:05:47
 * @version 1.0
 * @description:��Ŀ״̬����(oa_esm_project_statusreport) Model��
 */
class model_engineering_project_statusreport extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_statusreport";
        $this->sql_map = "engineering/project/statusreportSql.php";
        parent::__construct();
    }

    /*********************************ҵ����÷���**********************************/
    /**
     * ��д��������
     * @param $object
     * @return bool
     */
    function add_d($object)
    {
        try {
            $this->start_d();

            //��������״̬
            $object['ExaStatus'] = WAITAUDIT;
            $object['beginTimes'] = strtotime($object['beginDate']);
            $object['endTimes'] = strtotime($object['endDate']);

            //�༭��Ŀ
            $newId = parent::add_d($object, true);

            //�ܱ�Ԥ������
            $weekwarningDao = new model_engineering_weekreport_weekwarning();
            $object['weekwarning'] = util_arrayUtil::setArrayFn(array('mainId' => $newId), $object['weekwarning']);
            $weekwarningDao->saveDelBatch($object['weekwarning']);

            //��չ���ݼ���
            $weekStatus = $this->deal_d($object['weekstatus']);
            $weekStatusDao = new model_engineering_weekreport_weekstatus();
            $weekStatus = util_arrayUtil::setArrayFn(array('mainId' => $newId), $weekStatus);
            $weekStatusDao->saveDelBatch($weekStatus);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            echo util_jsonUtil::iconvGB2UTF($e->getMessage());
        }
    }

    /**
     * ��д�༭����
     * @param $object
     * @return bool
     */
    function edit_d($object)
    {
        try {
            $this->start_d();

            //�༭��Ŀ
            parent::edit_d($object, true);

            //�ܱ�Ԥ������
            $weekwarningDao = new model_engineering_weekreport_weekwarning();
            $object['weekwarning'] = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $object['weekwarning']);
            $weekwarningDao->saveDelBatch($object['weekwarning']);

            //��չ���ݼ���
            $weekStatus = $this->deal_d($object['weekstatus']);
            $weekStatusDao = new model_engineering_weekreport_weekstatus();
            $weekStatus = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $weekStatus);
            $weekStatusDao->saveDelBatch($weekStatus);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �м䴦��
     * @param $weekStatus
     * @return array
     */
    function deal_d($weekStatus)
    {
        $rst = $inner = array();
        $all = $change = '';
        foreach ($weekStatus as $v) {
            if ($v['weekNo'] == '�ܽ�չ') {
                $all = $v;
            } else if ($v['weekNo'] == '�仯') {
                $change = $v;
            } else {
                $inner[] = $v;
            }
        }
        if ($all) $rst[] = $all;
        foreach ($inner as $v) {
            $rst[] = $v;
        }
        if ($change) $rst[] = $change;
        return $rst;
    }

    /**
     * �ж���Ŀ�Ƿ��Ѿ����ڶ�Ӧ����
     * @param $projectId
     * @return bool|int
     */
    function hasSubmitedReport_d($projectId)
    {
        $this->searchArr = array('ExaStatusArr' => '���ύ,���', 'projectId' => $projectId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            if ($rs[0]['ExaStatus'] == '���ύ' || $rs[0]['ExaStatus'] == '���') {
                return $rs[0]['id'];
            } else {
                return -1;
            }
        } else {
            return false;
        }
    }

    /**
     * �ж���Ŀ�Ƿ��Ѿ����ڶ�Ӧ����
     * @param $projectId
     * @param $date
     * @return bool
     */
    function checkLogScored_d($projectId, $date)
    {
        $timestamp = strtotime($date);
        $sql = "SELECT score FROM oa_esm_project_statusreport WHERE projectId = $projectId " .
            "AND (UNIX_TIMESTAMP(beginDate) <= $timestamp AND UNIX_TIMESTAMP(endDate) >= $timestamp)";
        $obj = $this->_db->get_one($sql);
        if ($obj && $obj['score'] !== NULL) {
            return $obj['score'];
        } else {
            return false;
        }
    }

    /**
     * ��Ŀ�����ȡ���´�id - ���ڰ��´�����
     * @return string
     */
    function getOfficeIds_d()
    {
        $officeInfoDao = new model_engineering_officeinfo_officeinfo ();
        return $officeInfoDao->getOfficeIds_d();
    }

    /**
     * ��ȡ���˸���ʡ��
     * @return string
     */
    function getProvinces_d()
    {
        $managerDao = new model_engineering_officeinfo_manager();
        return $managerDao->getProvinces_d();
    }

    /**
     * ��ȡ������ĿȨ��
     * @return array
     */
    function getProjectLimits_d()
    {
        $otherDataDao = new model_common_otherdatas ();
        return $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID']);
    }

    /**
     * ��Ŀ״̬��ͼ
     * @param $rows
     */
    function projectStatusChart_d($rows)
    {
        $outArr = array();
        $weekstatusDao = new model_engineering_weekreport_weekstatus();
        foreach ($rows as $key => $value) {
            $weekStr = $value['weekNo'] . '(' . $value['handupDate'] . ')';
            $weekstatusObj = $weekstatusDao->find(array('mainId' => $rows[$key]['id']), null, 'processPlan,processFee');
            $outArr['���ý���'][$weekStr] = $weekstatusObj['processFee'];
            $outArr['�ƻ�����'][$weekStr] = $weekstatusObj['processPlan'];
            $outArr['���ܽ���'][$weekStr] = $rows[$key]['weekProcess'];
            $outArr['���̽���'][$weekStr] = $rows[$key]['projectProcess'];
        }
        $chartDao = new model_common_fusionCharts ();
        $chartConf = array('exportFileName' => "��Ŀ������ͼ", "showValues" => "true", 'caption' => "��Ŀ������ͼ", 'yAxisMaxValue' => 100, 'numberSuffix' => '%');
        echo "<div align='center'>" . $chartDao->showMixChart($outArr, "ZoomLine.swf", $chartConf, 1000, 600) . "</div>";
    }

    /********************** �±���ȷ�ϲ��� - ������ɺ�ҵ���� **********************/
    /**
     * ������ɺ�ҵ����
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterAudit_d($spid)
    {
        //��ȡ��������Ϣ
        $otherdatas = new model_common_otherdatas ();
        $flowInfo = $otherdatas->getStepInfo($spid);
        // ʵ������Ŀ
        $esmprojectDao = new model_engineering_project_esmproject();
        //����id
        $id = $flowInfo['objId'];
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            // �������&&��Ŀ��ΪA������ �Ż����ܱ����ȸ�����Ŀ����
//            if ($obj['ExaStatus'] == AUDITED &&
//                !($esmprojectDao->isCategoryAProject_d($obj['projectId']) && $esmprojectDao->isAllOutsourcingProject_d($obj['projectId']))
//            ) {

            if ($obj['ExaStatus'] == AUDITED){
                //��ȡ���һ����Ч���ܱ���Ŀ����
                $lastStatusReport = $this->find(array('projectId' => $obj['projectId'], 'ExaStatus' => AUDITED), 'weekNo DESC', 'projectProcess');
                 $esmprojectDao->updateProjectProcess_d($obj['projectId'], $lastStatusReport['projectProcess']);
            }

            //���������Ϊ��ص�ʱ����¿���ϵ��
            if ($obj['ExaStatus'] != BACK) {
                $esmworklogDao = new model_engineering_worklog_esmworklog();
                $esmworklogDao->updateWorkCoefficient_d($obj['projectId'], $obj['weekNo'], $obj['score']);
            }

            //����������Ϣ
            parent::edit_d(array('id' => $id, 'confirmName' => $_SESSION['USERNAME'],
                'confirmId' => $_SESSION['USER_ID'], 'confirmDate' => day_date), true);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * ��ȡ��Ŀ�Ķ�Ӧ��Χid
     * @param $projectId
     * @return mixed
     */
    function getRangeId_d($projectId)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->getRangeId_d($projectId);
    }

    /**
     * ��ȡ��Ŀ�ܱ���Ϣ
     * @param $condition
     * @return array
     */
    function getDatasForProject_d($condition)
    {
        $weekDao = new model_engineering_baseinfo_week();
        $desc = isset($condition['dir']) && $condition['dir'] == 'ASC' ? 'asc' : 'desc'; //�����ܴ�����
        $this->getParam($condition);
        $datas = $this->list_d();
        $newDatas = array();

        //��Ŀʱ�䴦��
        $projectId = $condition['projectId'];
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        if ($esmprojectObj['planBeginDate'] == '0000-00-00' || empty($esmprojectObj['planBeginDate'])) {
            $newDatas = $datas;
        } else {
            //����ת��
            $keyDatas = array();
            foreach ($datas as $val) {
                $keyDatas[$val['weekNo']] = $val;
            }
            $endDate = min($esmprojectObj['planEndDate'], day_date);
            $weekArr = $weekDao->findEsmRealWeekNo($esmprojectObj['planBeginDate'], $endDate, $desc);
            foreach ($weekArr as $v) {
                if (isset($keyDatas[$v])) {
                    array_push($newDatas, $keyDatas[$v]);
                } else {
                    array_push($newDatas, array(
                        'weekNo' => $v,
                        'id' => 'q' . $v,
                        'projectId' => $projectId,
                        'projectCode' => $esmprojectObj['projectCode']
                    ));
                }
            }
        }
        //��ȡ��ʼʱ��ͽ���ʱ��
        foreach ($newDatas as $k => $v) {
            $weekDate = $weekDao->findWeekDate($v['weekNo']);
            $beginEndDate = $weekDao->getWeekRange($weekDate['week'], $weekDate['year']);
            $newDatas[$k]['beginDate'] = $beginEndDate['beginDate'];
            $newDatas[$k]['endDate'] = $beginEndDate['endDate'];
        }

        // ���䲻����ܴ� �߼��� ������һ�ܽ������ڴ�����ĿԤ�ƽ������� �� ��ǰ���ڴ������һ�ܽ������� �� Ԥ�ƽ������ڴ������һ�ܽ�������
//        if (isset($endDate) && $newDatas[0] && strtotime(day_date) > $esmprojectObj['planEndDate']
//            && strtotime($newDatas[0]['endDate']) < strtotime($endDate)
//            && strtotime($newDatas[0]['endDate']) < strtotime($esmprojectObj['planEndDate'])) {
//
//            $appendWeekNo = $newDatas[0]['weekNo'] + 1;
//            if (isset($keyDatas[$appendWeekNo])) {
//                $newDatas = array_merge(array($keyDatas[$appendWeekNo]), $newDatas);
//            } else {
//                $weekDate = $weekDao->findWeekDate($appendWeekNo);
//                $beginEndDate = $weekDao->getWeekRange($weekDate['week'], $weekDate['year']);
//                $appendWeek = array(
//                    'weekNo' => $appendWeekNo, 'id' => 'q' . $appendWeekNo,
//                    'projectId' => $projectId, 'projectCode' => $esmprojectObj['projectCode'],
//                    'beginDate' => $beginEndDate['beginDate'], 'endDate' => $beginEndDate['endDate']
//                );
//                $newDatas = array_merge(array($appendWeek), $newDatas);
//            }
//        }
        

        return $newDatas;
    }

    /**
     * δ��д�ܱ�
     * @param $obj
     * @return array
     */
    function warnView_d($obj)
    {
        $objArr = array(); // δд�ܱ�
        $year = $obj['year'];
        $month = $obj['month'];
        // ����д������£�������ת��Ϊ��ʼ���ںͽ�������
        // Ŀǰֻ��¼��Ԥ���Ż��õ���������Щ�������°�����
        if ($year && $month) {
            $condition = "1";
            $beginDateThan = $year . '-' . $month . '-1';
            $endDateThan = date('Y-m-t', strtotime($beginDateThan));
        } else {
            $beginDateThan = $obj['beginDateThan'];
            $endDateThan = $obj['endDateThan'];
            $condition = "1";
            if (!isset($obj['all'])) $condition .= <<<MARK
                AND c.status = "GCXMZT02" AND c.contractType = "GCXMYD-01"
                AND concat(c.outsourcingName,c.categoryName) <> "����A��"
MARK;
            if ($obj['deptId']) $condition .= " AND c.deptId in (" . $obj['deptId'] . ")";
        }
        $projectId = $obj['projectId'];
        $projectIds = $obj['projectIds'];

        if ($projectId) $condition .= " AND c.id = '" . $projectId . "'";
        if ($projectIds) $condition .= " AND c.id IN(" . $projectIds . ")";

        $weekDao = new model_engineering_baseinfo_week();
        $weekArr = $weekDao->findEsmRealWeekNo($beginDateThan, $endDateThan);

        // ���û�в鵽�ܴ�
        if (!isset($weekArr[0])) {
            return $objArr;
        }

        $weekNo = implode(',', $weekArr);

        //��ѯָ��ʱ�����δ�ύ����Ŀ�ܱ�
        $sql = <<<MARK
    		SELECT c.id AS projectId,c.projectCode,c.projectName,c.managerName,c.officeName,c.planBeginDate,c.planEndDate,
    		        c.categoryName,c.outsourcingName,s.weekNo,s.weekStatus
				FROM
					oa_esm_project c
					LEFT JOIN
					(
						SELECT
							projectId,GROUP_CONCAT(CAST(weekNo AS CHAR(4))) AS weekNo,
							GROUP_CONCAT(CAST(ExaStatus AS CHAR(4))) AS weekStatus
						FROM oa_esm_project_statusreport
						WHERE ExaStatus IN('��������','���') AND weekNo IN($weekNo)
						GROUP BY projectId
					) s
					ON c.id = s.projectId
				WHERE
                    $condition
                    AND TO_DAYS(c.planBeginDate) <= TO_DAYS("$endDateThan") AND TO_DAYS(c.planEndDate) >= TO_DAYS("$beginDateThan")
MARK;
        $rows = $this->findSql($sql);

        // ����δд�ܱ��ܴ�
        if (!empty($rows) && !empty($weekArr)) {
            foreach ($rows as $v) {
                $tempArr = array();
                // ��Ŀ���ܱ���д���
                $myWeekArr = array_combine(explode(',', $v['weekNo']), explode(',', $v['weekStatus']));

                // ��ȡ��ĿԤ��ִ�����ڵ��ܴ�
                $weekDao = new model_engineering_baseinfo_week();
                $objectWeeks = $weekDao->findEsmRealWeekNo($v['planBeginDate'], $v['planEndDate']);

                foreach ($weekArr as $week) {
                    if (in_array($week, $objectWeeks)) { // ֻ����Ŀ�����ܴ��ڲ�ѯ
                        if (empty($tempArr)) {
                            $tempArr = array(
                                'projectId' => $v['projectId'],
                                'projectCode' => $v['projectCode'],
                                'projectName' => $v['projectName'],
                                'managerName' => $v['managerName'],
                                'officeName' => $v['officeName']
                            );
                        }

                        // ����������ܴΣ�����Ϊδ�
                        if (!isset($myWeekArr[$week])) {
                            $tempArr['weekNo'] = $week;
                            $tempArr['msg'] = 'δ�';
                        } else if ($myWeekArr[$week] == '��������') {
                            $tempArr['weekNo'] = $week;
                            $tempArr['msg'] = 'δ���';
                        } else {
                            continue;
                        }
                        array_push($objArr, $tempArr);
                    }
                }
            }
        }
        return $objArr;
    }

    /**
     * ��ȡ�ܱ�����״̬
     * ״̬Ϊ�����ύ���򡾴�ء�����true,���򷵻�false
     * @param $id
     * @return bool
     */
    function getExaStatus_d($id)
    {
        $rs = $this->find(array('id' => $id), null, 'ExaStatus');
        if ($rs['ExaStatus'] == "���ύ" || $rs['ExaStatus'] == "���") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ��ʱ�洢 �澯����
     */
    function timingWarnView_d()
    {
        $obj['beginDateThan'] = date('Y-m-01');
        $obj['endDateThan'] = day_date;
        //��ձ�
        $this->query("DELETE FROM oa_esm_record_warning_report");

        $arr = $this->warnView_d($obj);

        foreach ($arr as $v) {
            $this->query("INSERT INTO oa_esm_record_warning_report(officeName,projectCode,projectName,managerName,weekNo,msg)VALUES( '" .
                $v['officeName'] . "', '" . $v['projectCode'] . "', '" . $v['projectName'] . "', '" .
                $v['managerName'] . "', '" . $v['weekNo'] . "', '" . $v['msg'] . "')");
        }

        return true;
    }

    /**
     * ��ȡ���µĸ澯����
     * @param $projectId
     * @return int
     */
    function getNewestWarningNum_d($projectId)
    {
        $rs = $this->find(array('projectId' => $projectId), 'id DESC', 'warningNum');

        if (empty($rs)) {
            return 0;
        } else {
            return $rs['warningNum'];
        }
    }
}