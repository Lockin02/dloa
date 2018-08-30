<?php

/**
 * @author show
 * @Date 2013年10月17日 17:34:30
 * @version 1.0
 * @description:项目成员周日志 Model层
 */
class model_engineering_weekreport_wpweeklog extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_weeklog";
        $this->sql_map = "engineering/weekreport/wpweeklogSql.php";
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
            return $this->getNow_d($mainId);
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

        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $logs = $esmworklogDao->getAssessData_d(array(
            'beginDate' => $weekDateInfo['beginDate'],
            'endDate' => $weekDateInfo['endDate'],
            'projectId' => $projectId
        ));

        if ($logs) {
            //临时用于获取费用
            $sql = "select CostMan,sum(c.amount) as feeField from cost_summary_list c
				where replace(c.projectno,'-','')=replace('$projectCode','-','') and c.status <>'打回' group by CostMan";
            $rs = $this->_db->getArray($sql);
            $feeArr = array();
            if ($rs[0]['CostMan']) {
                foreach ($feeArr as $v) {
                    $feeArr[$v['CostMan']] = $v['feeField'];
                }
            }

            foreach ($logs as &$v) {
                $v['memberId'] = $v['createId'];
                $v['memberName'] = $v['createName'];
                $v['isNew'] = 1;
                if (isset($feeArr[$v['memberId']])) {
                    $v['feeAll'] = $feeArr[$v['memberId']];
                } else {
                    $v['feeAll'] = 0;
                }
            }
        }
        return $logs;
    }

    /**
     * 获取已存在的项目进展信息
     */
    function getNow_d($mainId)
    {
        return $this->findAll(array('mainId' => $mainId));
    }

    /**
     * 显示表格
     */
    function showWeek_d($data)
    {
        if (empty($data) || $data[0]['isNew']) {
            return $this->setTemplate_d($data);
        } else {
            return $this->setOldTemplate_d($data);
        }
    }

    /**
     * 新模板
     * @param $data
     * @return string
     */
    function setTemplate_d($data) {
        $headStr = <<<E
            <thead>
                <tr class="main_tr_header">
                    <th>姓名</th>
                    <th width="12%">考勤投入	</th>
                    <th width="12%">考核得分</th>
                    <th width="12%">统计天数</th>
                    <th width="12%">出勤系数</th>
                    <th width="12%">考核系数</th>
                    <th width="12%">请休假天数</th>
                    <th width="12%">费用</th>
                </tr>
            </thead>
E;
        if ($data) {
            $tdStr = ""; //内容字符串
            foreach ($data as $k => $v) {
                //判断行显示效果
                $trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
                //行字符串
                $tdStr .= <<<EOT
					<tr class="$trClass">
						<td>
							{$v['memberName']}
							<input type="hidden" name="statusreport[wpweeklog][$k][id]" value="{$v['id']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][memberName]" value="{$v['memberName']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][memberId]" value="{$v['memberId']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][inWorkRate]" value="{$v['inWorkRate']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][monthScore]" value="{$v['monthScore']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][countDay]" value="{$v['countDay']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][attendance]" value="{$v['attendance']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][assess]" value="{$v['assess']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][hols]" value="{$v['hols']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][feeAll]" value="{$v['feeAll']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$k][isNew]" value="{$v['isNew']}"/>
						</td>
						<td>{$v['inWorkRate']}</td>
						<td>{$v['monthScore']}</td>
						<td>{$v['countDay']}</td>
						<td>{$v['attendance']}</td>
						<td>{$v['assess']}</td>
						<td>{$v['hols']}</td>
						<td>{$v['feeAll']}</td>
					</tr>
EOT;
            }

            //表头处理
            $str = <<<EOT
				<table class="form_in_table" style="width:1000px;">
					$headStr
					$tdStr
				</table>
EOT;
        } else {
            //表头处理
            $str = <<<EOT
				<table class="form_in_table" style="width:1000px;">
                    $headStr
					<tr class="tr_odd">
						<td colspan="8">-- 没有相关信息 --</td>
					</tr>
				</table>
EOT;
        }
        return $str;
    }

    /**
     * 老模板
     * @param $data
     * @return string
     */
    function setOldTemplate_d($data) {
        $headStr = <<<E
            <thead>
                <tr class="main_tr_header">
                    <th>姓名</th>
                    <th width="16%">人工投入</th>
                    <th width="16%">工作系数</th>
                    <th width="16%">项目完成量</th>
                    <th width="16%">进展系数</th>
                    <th width="16%">费用</th>
                </tr>
            </thead>
E;
        if($data){
            $tdStr = null;//内容字符串
            foreach($data as $key => $val){
                //判断行显示效果
                $trClass = $key%2 == 0 ? 'tr_odd' : 'tr_even';
                //行字符串
                $tdStr.=<<<EOT
					<tr class="$trClass">
						<td>
							{$val['memberName']}
							<input type="hidden" name="statusreport[wpweeklog][$key][id]" value="{$val['id']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][memberName]" value="{$val['memberName']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][memberId]" value="{$val['memberId']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][inWorkRate]" value="{$val['inWorkRate']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][workCoefficient]" value="{$val['workCoefficient']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][projectProcess]" value="{$val['projectProcess']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][processCoefficient]" value="{$val['processCoefficient']}"/>
							<input type="hidden" name="statusreport[wpweeklog][$key][feeAll]" value="{$val['feeAll']}"/>
						</td>
						<td>{$val['inWorkRate']}</td>
						<td>{$val['workCoefficient']}</td>
						<td>{$val['projectProcess']}</td>
						<td>{$val['processCoefficient']}</td>
						<td>{$val['feeAll']}</td>
					</tr>
EOT;
            }

            //表头处理
            $str =<<<EOT
				<table class="form_in_table" style="width:1000px;">
				    $headStr
					$tdStr
				</table>
EOT;
        }else{
            //表头处理
            $str =<<<EOT
				<table class="form_in_table" style="width:1000px;">
				    $headStr
					<tr class="tr_odd">
						<td colspan="6">-- 没有相关信息 --</td>
					</tr>
				</table>
EOT;
        }
        return $str;
    }

    /**
     * 更新表记录
     */
    function update_d($mainId, $weeklogInfo)
    {
        try {
            $this->start_d();
            foreach ($weeklogInfo as $v) {
                //判断该记录是否存在，是则更新，否则新增
                $id = $this->find(array('mainId' => $mainId, 'memberId' => $v['memberId']), null, 'id');
                if (!empty($id)) {
                    $this->update(array('id' => $id), $v);
                } else {
                    $v['mainId'] = $mainId;
                    $this->create($v);
                }
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }
}