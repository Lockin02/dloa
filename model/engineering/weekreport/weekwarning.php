<?php

/**
 * @author show
 * @Date 2013年12月6日 16:31:26
 * @version 1.0
 * @description:项目周报告警记录表 Model层
 */
class model_engineering_weekreport_weekwarning extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_weekwarning";
        $this->sql_map = "engineering/weekreport/weekwarningSql.php";
        parent :: __construct();
    }

    /**
     * 获取
     */
    function getWeek_d($projectId = null, $projectCode = null, $weekNo = null, $mainId = null)
    {
        if ($mainId) {
            $obj = $this->find(array('mainId' => $mainId));
            if (empty($obj)) {
                return $this->getNew_d($projectId, $projectCode, $weekNo);
            }
            return $this->getNow_d($projectId, $projectCode, $weekNo, $mainId);
        } else {
            return $this->getNew_d($projectId, $projectCode, $weekNo);
        }
    }

    /**
     * 获取新纪录
     */
    function getNew_d($projectId, $projectCode, $weekNo)
    {
        //获取实际周次
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $weekDateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);

        //返回数组
        $arr = array();

        //载入预警规则
        array_push($arr, $this->getRule005_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule002_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule003_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule004_d($projectId, $projectCode, $weekDateInfo));
        array_push($arr, $this->getRule001_d($projectId, $projectCode, $weekDateInfo));

        return $arr;
    }

    /**
     * 获取已存在的项目进展信息
     */
    function getNow_d($projectId, $projectCode, $weekNo, $mainId)
    {
        return $this->findAll(array('mainId' => $mainId));
    }

    /**
     * 显示表格
     */
    function showWeek_d($object, $weekNo, $prevFourWeeks)
    {
        $str = "";
        if ($object) {
            $tdStr = null; //内容字符串
            $max = 0; //告警等级
            $warningNum = 0; // 预警数量
            $exgross = 0; // 毛利率

            // 前四周的表头处理
            $prevFourWeeksArray = $this->prevFourView_d($prevFourWeeks);

            foreach ($object as $key => $val) {
                //判断行显示效果
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                //判断告警等级
                $max = max($val['warningLevelId'], $max);
                //告警图片
                $img = $this->waringImg[$val['warningLevelId']];
                //告警值
                $warningLevel = $this->warningLevel[$val['warningLevelId']];
                // 是否需要填写解决反馈
                $feedbackStr = $val['warningLevelId'] == 3 ?
                    '<input type="hidden" id="needFeedback' . $key . '" value="' . $key . '"/>' : "";
                // 记录预警数量
                if ($val['warningLevelId'] == 3) {
                    $warningNum++;
                }
                // 毛利率处理
                if (isset($val['exgross'])) {
                    $exgross = $val['exgross'];
                }
                //行字符串
                $tdStr .= <<<EOT
					<tr class="$trClass">
						<td title="{$val['ruleDesc']}">
							{$val['warningItem']}
							<input type="hidden" name="statusreport[weekwarning][$key][id]" value="{$val['id']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningItem]" value="{$val['warningItem']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningItemId]" value="{$val['warningItemId']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningLevel]" value="$warningLevel"/>
							<input type="hidden" name="statusreport[weekwarning][$key][warningLevelId]" value="{$val['warningLevelId']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][coefficient]" value="{$val['coefficient']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][analysis]" value="{$val['analysis']}"/>
							<input type="hidden" name="statusreport[weekwarning][$key][ruleDesc]" value="{$val['ruleDesc']}"/>
							$feedbackStr
						</td>
						<td>$img</td>
						<td>{$val['coefficient']}</td>
                        {$prevFourWeeksArray[$val['warningItemId']]}
						<td>
						    <input class="txtlong" style="width:300px;"  id="feedback$key"
						           name="statusreport[weekwarning][$key][feedback]" value="{$val['feedback']}">
                        </td>
						<td></td>
					</tr>
EOT;
            }
            //表头处理
            $str = <<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th width="10%">预警项</th>
							<th width="5%">严重程度</th>
							<th width="10%">$weekNo</th>
							{$prevFourWeeksArray['head']}
							<th style="width:300px;">
								解决方案
								<input type="hidden" name="statusreport[warningLevel]" value="{$this->warningLevel[$max]}"/>
								<input type="hidden" name="statusreport[warningLevelId]" value="$max"/>
								<input type="hidden" name="statusreport[warningNum]" value="$warningNum"/>
								<input type="hidden" name="statusreport[exgross]" value="$exgross"/>
							</th>
							<th></th>
						</tr>
					</thead>
					$tdStr
				</table>
EOT;
        }
        return $str;
    }

    /**
     * 前四周预警呈现
     * @param $prevFourWeeks
     * @return array
     */
    function prevFourView_d($prevFourWeeks) {
        // 需要返回的字符串数组
        $strArray = array();
        $i = 0;

        foreach ($prevFourWeeks as $k => $v) {
            $i++;
            if ($i > 4) {
                continue;
            }
            $strArray['head'] .= '<th width="10%">' . $k . '</th>';
            foreach ($v as $ki => $vi) {
                $strArray[$ki] .= '<td>' . $vi . '</td>';
            }
        }

        return $strArray;
    }

    /**
     * 显示表格 - 查看
     */
    function viewWeek_d($object, $weekNo, $prevFourWeeks)
    {
        if ($object) {
            $tdStr = null; //内容字符串

            // 前四周的表头处理
            $prevFourWeeksArray = $this->prevFourView_d($prevFourWeeks);

            foreach ($object as $key => $val) {
                //判断行显示效果
                $trClass = $key % 2 == 0 ? 'tr_odd' : 'tr_even';
                //告警图片
                $img = $this->waringImg[$val['warningLevelId']];
                //行字符串
                $tdStr .= <<<EOT
					<tr class="$trClass">
						<td title="{$val['ruleDesc']}">
							{$val['warningItem']}
						</td>
						<td>$img</td>
						<td>{$val['coefficient']}</td>
                        {$prevFourWeeksArray[$val['warningItemId']]}
						<td align="left">{$val['feedback']}</td>
						<td align="left"></td>
					</tr>
EOT;
            }
            //表头处理
            $str = <<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th width="10%">预警项</th>
							<th width="5%">严重程度</th>
							<th width="10%">$weekNo</th>
							{$prevFourWeeksArray['head']}
							<th style="width:300px;">解决方案</th>
							<th></th>
						</tr>
					</thead>
					$tdStr
				</table>
EOT;
        } else {
            //表头处理
            $str = <<<EOT
				<table class="form_in_table">
					<thead>
						<tr class="main_tr_header">
							<th width="10%">预警项</th>
							<th width="5%">严重程度</th>
							<th width="10%">$weekNo</th>
							<th style="width:300px;">解决方案</th>
							<th></th>
						</tr>
					</thead>
					<tr class="tr_odd">
						<td colspan="5">-- 没有告警信息 --</td>
					</tr>
				</table>
EOT;
        }
        return $str;
    }

    /************************** 告警规则呈现 *********************/
    /**
     * 缓存对象
     */
    public $daoCache = array();

    /**
     * 辅助实例化项目
     */
    function initObj($className)
    {
        if (isset($this->daoCache[$className])) {
            return $this->daoCache[$className];
        } else {
            return $this->daoCache[$className] = new $className();
        }
    }

    /**
     * 告警等级配置
     */
    public $warningLevel = array(
        0 => '',
        1 => '正常',
        2 => '预警',
        3 => '告警',
        4 => '严重'
    );

    /**
     * 告警图标
     */
    public $waringImg = array(
        0 => '',
        1 => '<img src="images/icon/cicle_green.png" title="正常"/>',
        2 => '<img src="images/icon/cicle_yellow.png" title="预警"/>',
        3 => '<img src="images/icon/cicle_red.png" title="警告"/>',
        4 => '<img src="images/icon/cicle_black.png" title="严重"/>'
    );

    /**
     * 回返预警规则记录模板
     */
    function getRTArray_d($warningItemId, $warningItem, $ruleDesc)
    {
        return array(
            'warningItemId' => $warningItemId, 'warningItem' => $warningItem,
            'warningLevelId' => '', 'warningLevel' => '', 'ruleDesc' => $ruleDesc,
            'coefficient' => '0', 'analysis' => '', 'feedBack' => ''
        );
    }

    /**
     * 告警规则 1
     * 项目工期 = 项目预计结束日期-当前日期+1
     * 颜色定义：绿：>30、黄：(30 - 15]、红：(15 - 0]
     */
    function getRule001_d($projectId, $projectCode, $weekDateInfo)
    {
        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);

        $rtArr = $this->getRTArray_d('1', '项目工期', '项目预计结束日期-当前日期+1【绿：>30、黄：(30 - 15]、红：(15 - 0]】');

        //结束日期
        $rtArr['coefficient'] = (strtotime($esmprojectObj['planEndDate']) - strtotime(day_date)) / 86400 + 1;

        //预警规则，详见方法说明
        if ($rtArr['coefficient'] > 30) {
            $rtArr['warningLevelId'] = 1;
        } elseif ($rtArr['coefficient'] <= 30 && $rtArr['coefficient'] > 15) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * 告警规则 2
     * 成本绩效 = CPI = EV(工程进度*总预算)/AC(总决算)
     * 颜色定义：红：[0 - 0.8)、黄：[0.8 - 0.9)、绿：[0.9 - 1.1]、黄：(1.1 - 1.2]、红：(1.2 - ∞)
     */
    function getRule002_d($projectId, $projectCode, $weekDateInfo)
    {
        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        $esmprojectObj = $esmprojectDao->feeDeal($esmprojectObj);

        //获取即时项目进度
        $esmactivityDao = $this->initObj('model_engineering_activity_esmactivity');
        $projectProcess = $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate'], $esmprojectDao->isCategoryAProject_d($esmprojectObj));

        //返回数组
        $rtArr = $this->getRTArray_d('2', 'CPI', '工程进度*总预算/总决算【红：[0 - 0.8)、黄：[0.8 - 0.9)、绿：[0.9 - 1.1]、黄：(1.1 - 1.2]、红：(1.2 - ∞)');

        //计算系数
        $rtArr['coefficient'] = $esmprojectObj['feeAll'] > 0 ?
            round(bcdiv(bcmul(bcdiv($projectProcess, 100, 6), $esmprojectObj['budgetAll'], 6), $esmprojectObj['feeAll'], 6), 1) :
            '-' ;

        //预警规则，详见方法说明
        if ($rtArr['coefficient'] == '-' || ($rtArr['coefficient'] >= 0.9 && $rtArr['coefficient'] <= 1.1)) {
            $rtArr['warningLevelId'] = 1;
        } elseif (($rtArr['coefficient'] >= 0.8 && $rtArr['coefficient'] < 0.9) || ($rtArr['coefficient'] > 0.8 && $rtArr['coefficient'] <= 1.2)) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * 告警规则 3
     * 进度绩效 = SPI = 实际进度/理论进度
     * 颜色定义：红：[0 - 0.8)、黄：[0.8 - 0.9)、绿：[0.9 - 1.1]、黄：(1.1 - 1.2]、红：(1.2 - ∞)
     */
    function getRule003_d($projectId, $projectCode, $weekDateInfo)
    {
        //返回数组
        $rtArr = $this->getRTArray_d('3', 'SPI', '实际进度/理论进度【红：[0 - 0.8)、黄：[0.8 - 0.9)、绿：[0.9 - 1.1]、黄：(1.1 - 1.2]、红：(1.2 - ∞)】');

        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        //如果是A类项目，那么进度绩效为1
        if ($esmprojectObj['category'] == 'XMLBA') {
            $rtArr['coefficient'] = 1;
            $rtArr['warningLevelId'] = 1;
            return $rtArr;
        }

        //获取即时项目进度
        $esmactivityDao = $this->initObj('model_engineering_activity_esmactivity');
        $projectProcess = $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate']);

        //获取预计项目进度
        $weekDao = new model_engineering_baseinfo_week();
        $weekArr = $weekDao->findEsmRealWeekNo($esmprojectObj['planBeginDate'], $esmprojectObj['planEndDate']);
        $perWeekRate = round(100 / count($weekArr), 1); //周平均进度
        $actWeekArr = $weekDao->findEsmRealWeekNo($esmprojectObj['planBeginDate'], $weekDateInfo['endDate']);
        $processPlan = round(count($actWeekArr) * $perWeekRate > 100 ? 100 : count($actWeekArr) * $perWeekRate, 1);

        //计算系数
        $rtArr['coefficient'] = $processPlan > 0 ? round(bcdiv($projectProcess, $processPlan, 2), 1) : 0;

        //预警规则，详见方法说明
        if ($rtArr['coefficient'] >= 0.9 && $rtArr['coefficient'] <= 1.1) {
            $rtArr['warningLevelId'] = 1;
        } elseif (($rtArr['coefficient'] >= 0.8 && $rtArr['coefficient'] < 0.9) || ($rtArr['coefficient'] > 0.8 && $rtArr['coefficient'] <= 1.2)) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * 告警规则 4
     * 缺交日志 = 当前缺交日志的人天数:出现在项目成员的，只要没离开项目，都要写日志。公式：应填写人天数-实际填写人天数
     * 颜色定义：绿：0、黄：<=10、红：>10
     */
    function getRule004_d($projectId, $projectCode, $weekDateInfo)
    {
        //返回数组
        $rtArr = $this->getRTArray_d('4', '缺交日志', '当前缺交日志的人天数:出现在项目成员的，只要没离开项目，都要写日志。公式：应填写人天数-实际填写人天数【绿：0、黄：<=10、红：>10】');

        //获取项目里面的人
        $esmworklogDao = $this->initObj('model_engineering_worklog_esmworklog');

        // 获取用户的数据
        $rows = $esmworklogDao->findMember_d($projectId);

        // 查询用户数据
        $memberIdArr = array();
        foreach ($rows as $v) {
            $memberIdArr[] = $v['memberId'];
        }
        $memberIds = implode(',', $memberIdArr);

        // 日期数据 - 构建用户从开始到结束的日期数据
        $weekDao = new model_engineering_baseinfo_week();
        $dateData = $weekDao->buildDateData($weekDateInfo['beginDate'], $weekDateInfo['endDate']);

        // 用户信息 - 入离职
        $personInfo = $esmworklogDao->getPersonnelInfoMap_d($memberIds);
        // 请休假信息
        $hols = $esmworklogDao->getHolsHash_d(strtotime($weekDateInfo['beginDate']), strtotime($weekDateInfo['endDate']), $memberIds);

        // 加入人员出入处理
        $entryDao = new model_engineering_member_esmentry();
        // 项目出入数据
        $entryData = $entryDao->getEntryMap_d($projectId, $memberIds);

        // 获取人员日志
        $logs = $esmworklogDao->getAssessmentLogMap_d($weekDateInfo['beginDate'], $weekDateInfo['endDate'], $memberIds);

        // 统计天数
        $countDay = count($dateData);
        $needNum = 0;

        foreach ($rows as $k => $v) {
            $innerData = $dateData;

            // 入离职数据处理
            $innerData = $esmworklogDao->dealEntryQuit_d($innerData, '', $personInfo[$v['createId']]);

            // 加入请休假数据
            $innerData = $esmworklogDao->dealHolds_d($innerData, '', '', $v['createId'], $hols);

            // 出入数据
            $innerData = $entryDao->dealEntry_d($innerData, '', $v['createId'], $entryData);

            // 人员日志附加
            $innerData = $esmworklogDao->dealLog_d($innerData, $projectId, $logs[$v['createId']]);

            // 合计
            $sumRow = array('id' => 'noId', 'executionDate' => '合计');

            foreach ($innerData as $vi) {
                $sumRow['inWorkRate'] = bcadd($sumRow['inWorkRate'], $vi['inWorkRate'], 2);
                $sumRow['noNeed'] = bcadd($sumRow['noNeed'], $vi['noNeed'], 2);
            }

            $needNum += $countDay - $sumRow['noNeed'] - $sumRow['inWorkRate'];
        }
        //计算系数
        $rtArr['coefficient'] = $needNum;
        $rtArr['coefficient'] = $rtArr['coefficient'] < 0 ? 0 : $rtArr['coefficient'];

        //预警规则，详见方法说明
        if ($rtArr['coefficient'] == 0) {
            $rtArr['warningLevelId'] = 1;
        } elseif ($rtArr['coefficient'] <= 10) {
            $rtArr['warningLevelId'] = 2;
        } else {
            $rtArr['warningLevelId'] = 3;
        }

        return $rtArr;
    }

    /**
     * 告警规则 5
     * 毛利率
     * 颜色定义：红：当前毛利率 < 预计毛利率；绿：其他条件
     */
    function getRule005_d($projectId, $projectCode, $weekDateInfo)
    {
        //返回数组
        $rtArr = $this->getRTArray_d('5', '毛利率', '红：当前毛利率 < 预计毛利率；绿：其他条件');

        // 项目数据获取
        $esmprojectDao = $this->initObj('model_engineering_project_esmproject');
        $esmprojectObj = $esmprojectDao->get_d($projectId);
        $esmprojectObj = $esmprojectDao->feeDeal($esmprojectObj);

        //获取即时项目进度，并且放入项目中
        $esmactivityDao = $this->initObj('model_engineering_activity_esmactivity');
        $esmprojectObj['projectProcess'] = $esmactivityDao->getActCountProcess_d($projectId, null, $weekDateInfo['endDate'],
            $esmprojectDao->isCategoryAProject_d($esmprojectObj));

        $esmprojectObj = $esmprojectDao->contractDeal($esmprojectObj);

        //查询当前被打回的报销单数量
        $rtArr['exgross'] = $rtArr['coefficient'] = $esmprojectObj['exgross'] . " %";
        $rtArr['warningLevelId'] = $esmprojectObj['exgross'] < $esmprojectObj['budgetExgross'] ? 3 : 1;

        return $rtArr;
    }

    /**
     * 更新表记录
     */
    function update_d($mainId, $weeklogInfo)
    {
        try {
            $this->start_d();
            foreach ($weeklogInfo as $val) {
                $this->update(array('mainId' => $mainId, 'warningItemId' => $val['warningItemId']), $val);
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * 返回前4周的数据
     * @param $projectId
     * @param $weekNo
     * @return array
     */
    function findPrevFourWeeks_d($projectId, $weekNo) {
        // 返回结果
        $rst = array();

        // 查询出相关数据
        $sql = "SELECT *
            FROM oa_esm_project_weekwarning c LEFT JOIN oa_esm_project_statusreport s ON c.mainId = s.id
            WHERE s.projectId = " . $projectId . " AND s.weekNo < $weekNo ORDER BY s.weekNo DESC" ;
        $data = $this->_db->getArray($sql);

        // 如果有查询到，则将数据改成指定格式
        if ($data) {
            foreach ($data as $v) {
                $rst[$v['weekNo']][$v['warningItemId']] = $v['coefficient'];
            }
        }
        return $rst;
    }
}