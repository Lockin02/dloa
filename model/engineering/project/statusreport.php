<?php

/**
 * @author Show
 * @Date 2011年11月28日 星期一 15:05:47
 * @version 1.0
 * @description:项目状态报告(oa_esm_project_statusreport) Model层
 */
class model_engineering_project_statusreport extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_statusreport";
        $this->sql_map = "engineering/project/statusreportSql.php";
        parent::__construct();
    }

    /*********************************业务调用方法**********************************/
    /**
     * 重写新增方法
     * @param $object
     * @return bool
     */
    function add_d($object)
    {
        try {
            $this->start_d();

            //加载审批状态
            $object['ExaStatus'] = WAITAUDIT;
            $object['beginTimes'] = strtotime($object['beginDate']);
            $object['endTimes'] = strtotime($object['endDate']);

            //编辑项目
            $newId = parent::add_d($object, true);

            //周报预警数据
            $weekwarningDao = new model_engineering_weekreport_weekwarning();
            $object['weekwarning'] = util_arrayUtil::setArrayFn(array('mainId' => $newId), $object['weekwarning']);
            $weekwarningDao->saveDelBatch($object['weekwarning']);

            //进展数据加入
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
     * 重写编辑方法
     * @param $object
     * @return bool
     */
    function edit_d($object)
    {
        try {
            $this->start_d();

            //编辑项目
            parent::edit_d($object, true);

            //周报预警数据
            $weekwarningDao = new model_engineering_weekreport_weekwarning();
            $object['weekwarning'] = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $object['weekwarning']);
            $weekwarningDao->saveDelBatch($object['weekwarning']);

            //进展数据加入
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
     * 中间处理
     * @param $weekStatus
     * @return array
     */
    function deal_d($weekStatus)
    {
        $rst = $inner = array();
        $all = $change = '';
        foreach ($weekStatus as $v) {
            if ($v['weekNo'] == '总进展') {
                $all = $v;
            } else if ($v['weekNo'] == '变化') {
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
     * 判断项目是否已经存在对应报告
     * @param $projectId
     * @return bool|int
     */
    function hasSubmitedReport_d($projectId)
    {
        $this->searchArr = array('ExaStatusArr' => '待提交,打回', 'projectId' => $projectId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            if ($rs[0]['ExaStatus'] == '待提交' || $rs[0]['ExaStatus'] == '打回') {
                return $rs[0]['id'];
            } else {
                return -1;
            }
        } else {
            return false;
        }
    }

    /**
     * 判断项目是否已经存在对应报告
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
     * 项目报告获取办事处id - 用于办事处经理
     * @return string
     */
    function getOfficeIds_d()
    {
        $officeInfoDao = new model_engineering_officeinfo_officeinfo ();
        return $officeInfoDao->getOfficeIds_d();
    }

    /**
     * 获取个人负责省份
     * @return string
     */
    function getProvinces_d()
    {
        $managerDao = new model_engineering_officeinfo_manager();
        return $managerDao->getProvinces_d();
    }

    /**
     * 获取个人项目权限
     * @return array
     */
    function getProjectLimits_d()
    {
        $otherDataDao = new model_common_otherdatas ();
        return $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID']);
    }

    /**
     * 项目状态视图
     * @param $rows
     */
    function projectStatusChart_d($rows)
    {
        $outArr = array();
        $weekstatusDao = new model_engineering_weekreport_weekstatus();
        foreach ($rows as $key => $value) {
            $weekStr = $value['weekNo'] . '(' . $value['handupDate'] . ')';
            $weekstatusObj = $weekstatusDao->find(array('mainId' => $rows[$key]['id']), null, 'processPlan,processFee');
            $outArr['费用进度'][$weekStr] = $weekstatusObj['processFee'];
            $outArr['计划进度'][$weekStr] = $weekstatusObj['processPlan'];
            $outArr['本周进度'][$weekStr] = $rows[$key]['weekProcess'];
            $outArr['工程进度'][$weekStr] = $rows[$key]['projectProcess'];
        }
        $chartDao = new model_common_fusionCharts ();
        $chartConf = array('exportFileName' => "项目进度视图", "showValues" => "true", 'caption' => "项目进度视图", 'yAxisMaxValue' => 100, 'numberSuffix' => '%');
        echo "<div align='center'>" . $chartDao->showMixChart($outArr, "ZoomLine.swf", $chartConf, 1000, 600) . "</div>";
    }

    /********************** 新报告确认部分 - 审批完成后业务处理 **********************/
    /**
     * 审批完成后业务处理
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterAudit_d($spid)
    {
        //获取工作流信息
        $otherdatas = new model_common_otherdatas ();
        $flowInfo = $otherdatas->getStepInfo($spid);
        // 实例化项目
        $esmprojectDao = new model_engineering_project_esmproject();
        //单据id
        $id = $flowInfo['objId'];
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            // 审批完成&&项目不为A类整包 才会以周报进度更新项目进度
//            if ($obj['ExaStatus'] == AUDITED &&
//                !($esmprojectDao->isCategoryAProject_d($obj['projectId']) && $esmprojectDao->isAllOutsourcingProject_d($obj['projectId']))
//            ) {

            if ($obj['ExaStatus'] == AUDITED){
                //获取最后一份生效的周报项目进度
                $lastStatusReport = $this->find(array('projectId' => $obj['projectId'], 'ExaStatus' => AUDITED), 'weekNo DESC', 'projectProcess');
                 $esmprojectDao->updateProjectProcess_d($obj['projectId'], $lastStatusReport['projectProcess']);
            }

            //审批结果不为打回的时候更新考核系数
            if ($obj['ExaStatus'] != BACK) {
                $esmworklogDao = new model_engineering_worklog_esmworklog();
                $esmworklogDao->updateWorkCoefficient_d($obj['projectId'], $obj['weekNo'], $obj['score']);
            }

            //更新审批信息
            parent::edit_d(array('id' => $id, 'confirmName' => $_SESSION['USERNAME'],
                'confirmId' => $_SESSION['USER_ID'], 'confirmDate' => day_date), true);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * 获取项目的对应范围id
     * @param $projectId
     * @return mixed
     */
    function getRangeId_d($projectId)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->getRangeId_d($projectId);
    }

    /**
     * 获取项目周报信息
     * @param $condition
     * @return array
     */
    function getDatasForProject_d($condition)
    {
        $weekDao = new model_engineering_baseinfo_week();
        $desc = isset($condition['dir']) && $condition['dir'] == 'ASC' ? 'asc' : 'desc'; //处理周次排序
        $this->getParam($condition);
        $datas = $this->list_d();
        $newDatas = array();

        //项目时间处理
        $projectId = $condition['projectId'];
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        if ($esmprojectObj['planBeginDate'] == '0000-00-00' || empty($esmprojectObj['planBeginDate'])) {
            $newDatas = $datas;
        } else {
            //数组转换
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
        //获取开始时间和结束时间
        foreach ($newDatas as $k => $v) {
            $weekDate = $weekDao->findWeekDate($v['weekNo']);
            $beginEndDate = $weekDao->getWeekRange($weekDate['week'], $weekDate['year']);
            $newDatas[$k]['beginDate'] = $beginEndDate['beginDate'];
            $newDatas[$k]['endDate'] = $beginEndDate['endDate'];
        }

        // 补充不足的周次 逻辑是 如果最后一周结束日期大于项目预计结束日期 且 当前日期大于最后一周结束日期 且 预计结束日期大于最后一周结束日期
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
     * 未填写周报
     * @param $obj
     * @return array
     */
    function warnView_d($obj)
    {
        $objArr = array(); // 未写周报
        $year = $obj['year'];
        $month = $obj['month'];
        // 如果有传入年月，则将年月转换为开始日期和结束日期
        // 目前只有录入预警才会用到，所以这些根据年月绑定起来
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
                AND concat(c.outsourcingName,c.categoryName) <> "整包A类"
MARK;
            if ($obj['deptId']) $condition .= " AND c.deptId in (" . $obj['deptId'] . ")";
        }
        $projectId = $obj['projectId'];
        $projectIds = $obj['projectIds'];

        if ($projectId) $condition .= " AND c.id = '" . $projectId . "'";
        if ($projectIds) $condition .= " AND c.id IN(" . $projectIds . ")";

        $weekDao = new model_engineering_baseinfo_week();
        $weekArr = $weekDao->findEsmRealWeekNo($beginDateThan, $endDateThan);

        // 如果没有查到周次
        if (!isset($weekArr[0])) {
            return $objArr;
        }

        $weekNo = implode(',', $weekArr);

        //查询指定时间段内未提交的项目周报
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
						WHERE ExaStatus IN('部门审批','完成') AND weekNo IN($weekNo)
						GROUP BY projectId
					) s
					ON c.id = s.projectId
				WHERE
                    $condition
                    AND TO_DAYS(c.planBeginDate) <= TO_DAYS("$endDateThan") AND TO_DAYS(c.planEndDate) >= TO_DAYS("$beginDateThan")
MARK;
        $rows = $this->findSql($sql);

        // 查找未写周报周次
        if (!empty($rows) && !empty($weekArr)) {
            foreach ($rows as $v) {
                $tempArr = array();
                // 项目的周报填写情况
                $myWeekArr = array_combine(explode(',', $v['weekNo']), explode(',', $v['weekStatus']));

                // 获取项目预计执行所在的周次
                $weekDao = new model_engineering_baseinfo_week();
                $objectWeeks = $weekDao->findEsmRealWeekNo($v['planBeginDate'], $v['planEndDate']);

                foreach ($weekArr as $week) {
                    if (in_array($week, $objectWeeks)) { // 只在项目所需周次内查询
                        if (empty($tempArr)) {
                            $tempArr = array(
                                'projectId' => $v['projectId'],
                                'projectCode' => $v['projectCode'],
                                'projectName' => $v['projectName'],
                                'managerName' => $v['managerName'],
                                'officeName' => $v['officeName']
                            );
                        }

                        // 如果不存在周次，则定义为未填报
                        if (!isset($myWeekArr[$week])) {
                            $tempArr['weekNo'] = $week;
                            $tempArr['msg'] = '未填报';
                        } else if ($myWeekArr[$week] == '部门审批') {
                            $tempArr['weekNo'] = $week;
                            $tempArr['msg'] = '未完成';
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
     * 获取周报审批状态
     * 状态为【待提交】或【打回】返回true,否则返回false
     * @param $id
     * @return bool
     */
    function getExaStatus_d($id)
    {
        $rs = $this->find(array('id' => $id), null, 'ExaStatus');
        if ($rs['ExaStatus'] == "待提交" || $rs['ExaStatus'] == "打回") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 定时存储 告警数据
     */
    function timingWarnView_d()
    {
        $obj['beginDateThan'] = date('Y-m-01');
        $obj['endDateThan'] = day_date;
        //清空表
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
     * 获取最新的告警数量
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