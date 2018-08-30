<?php

/**
 * @author Show
 * @Date 2012年6月18日 星期一 17:35:04
 * @version 1.0
 * @description:项目人力预算(oa_esm_project_person) Model层
 */
class model_engineering_person_esmperson extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_person";
        $this->sql_map = "engineering/person/esmpersonSql.php";
        parent:: __construct();
    }

    /****************************获取外部信息方法***************************/

    /**
     * 获取项目信息
     * @param $projectId
     * @return bool|mixed
     */
    function getEsmprojectInfo_d($projectId)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,projectName');
    }

    /**
     * 获取活动信息
     * @param $activityId
     * @return bool|mixed
     */
    function getActivityInfo_d($activityId)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->find(array('id' => $activityId), null,
            'activityName,planBeginDate,planEndDate,days,workContent,remark');
    }

    /**
     * 获取活动信息
     * @param $projectId
     * @return mixed
     */
    function getActivityArr_d($projectId)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->findAll(array('projectId' => $projectId), 'lft',
            'id,activityName,planBeginDate,planEndDate,lft,rgt,days,workContent,remark,parentId,projectId');
    }

    /**
     * 获取项目信息
     * @param $projectId
     * @return bool|mixed
     */
    function getObjInfo_d($projectId)
    {
        $projectDao = new model_engineering_project_esmproject();
        $projectRow = $projectDao->get_d($projectId);
        return $projectRow;
    }

    /**
     * 获取活动的下级活动id
     * @param $activityId
     * @param $lft
     * @param $rgt
     * @return null|string
     */
    function getUnderTreeIds_d($activityId, $lft, $rgt)
    {
        $esmactivityDao = new model_engineering_activity_esmactivity();
        return $esmactivityDao->getUnderTreeIds_d($activityId, $lft, $rgt);
    }

    /*************************** 内部方法 *****************************/
    /**
     * 重写add_d
     * @param $object
     * @return bool
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            //新增方法
            $newId = parent::add_d($object, true);

            //更新项目信息
            $this->updateProject_d($object);

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 批量新增
     * @param $object
     * @return bool
     */
    function addBatch_d($object)
    {
        //获取从表预算信息
        $person = $object['person'];
        unset($object['person']);
        try {
            $this->start_d(); //事务开启
            //加载新增信息
            $object = $this->addCreateInfo($object);

            //按引入预算页面数据的数目来新增预算
            $this->createBatch($person, $object);

            //更新项目的现场预算
            $this->updateProject_d($object);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重写edit_d
     * @param $object
     * @return bool
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //新增方法
            $newId = parent::edit_d($object, true);

            //更新项目信息
            $this->updateProject_d($object);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 简单新增
     */
    function addOrg_d($object)
    {
        try {
            return parent::add_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 简单编辑
     */
    function editOrg_d($object)
    {
        $object = $this->addUpdateInfo($object);
        try {
            return $this->updateById($object);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @desription 删除树节点及下属节点
     *
     */
    function deletes_d($id)
    {
        try {
            $this->start_d();

            $idArr = explode(',', $id);
            //需要处理的Id
            $dealId = $idArr[0];

            $obj = $this->find(array('id' => $dealId), null, 'projectId');

            //删除预算项
            parent::deletes($id);

            $this->updateProject_d($obj);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 项目任务新增 － 项目人力预算(新增或者修改删除)
     */
    function dealPerson_d($object, $activityId, $activityName)
    {
        //        print_r($object);
        if (!empty($object)) {

            try {
                $this->start_d();

                //人员信息缓存数组
                $personArr = array();
                //循环处理 -  主要是将同意个人力预算合并
                foreach ($object as $key => $val) {
                    if (empty($val['personLevelId'])) {
                        continue;
                    }
                    $markStr = $val['personLevelId'] . '-' . $val['planBeginDate'] . '-' . $val['planEndDate'];
                    //处理已存在的信息
                    if (isset($personArr[$markStr])) {
                        $personArr[$markStr]['personDays'] = bcadd($personArr[$markStr]['personDays'], $val['personDays']); //天数相加
                        $personArr[$markStr]['personCostDays'] = bcadd($personArr[$markStr]['personCostDays'], $val['personCostDays']); //人力成本
                        $personArr[$markStr]['personCost'] = bcadd($personArr[$markStr]['personCost'], $val['personCost']); //成本金额
                        $personArr[$markStr]['number'] = bcadd($personArr[$markStr]['number'], $val['number']); //人数相加
                    } else {
                        $personArr[$markStr] = $val;
                    }
                }

                if ($personArr) {
                    //循环插入数据
                    foreach ($personArr as $v) {
                        $v['activityId'] = $activityId;
                        $v['activityName'] = $activityName;
                        parent::add_d($v, true);
                    }
                    //更新项目
                    $this->updateProject_d($v);
                }

                $this->commit_d();
                return true;
            } catch (Exception $e) {
                $this->rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 批量修改
     */
    function batchEdit_d($object)
    {
        if (empty($object)) {
            return false;
        }
        try {
            $this->start_d();

            $returnObjs = array();
            foreach ($object as $val) {
                $val = $this->addCreateInfo($val);
                $isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
                if (empty ($val ['id']) && $isDelTag == 1) {

                } else if (empty ($val ['id'])) {
                    $id = $this->add_d($val);
                    $val ['id'] = $id;
                    array_push($returnObjs, $val);
                } else if ($isDelTag == 1) {
                    $this->deletes($val ['id']);
                } else {
                    $this->edit_d($val);
                    array_push($returnObjs, $val);
                }
            }
            //更新项目
            $this->updateProject_d($val);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    /*************************** 逻辑处理方法 内外部均可 *************************/
    /**
     * 判断项目是否有启用人力预算
     */
    function isOpen_d($projectId)
    {
        $rs = $this->find(array('projectId' => $projectId), null, 'id');
        if (empty($rs)) {
            return 0;
        } else {
            return 1;
        }
    }

    //获取项目人力预算
    function getProjectBudget_d($projectId)
    {
        //获取项目当前人力预算
        $this->searchArr = array('projectId' => $projectId);
        $rs = $this->listBySqlId('count_all');
        if ($rs[0]['personDays']) {
            return array('budgetDay' => $rs[0]['personDays'], 'budgetPeople' => $rs[0]['personCostDays'], 'budgetPerson' => $rs[0]['personCost']);
        } else {
            return array('budgetDay' => 0, 'budgetPeople' => 0, 'budgetPerson' => 0);
        }
    }

    /**
     * 更新项目信息 - 统一调用方法
     * 数组需要包含 projectId
     */
    function updateProject_d($object)
    {
        try {
            $this->start_d();

            //获取项目当前人力预算
            $this->searchArr = array('projectId' => $object['projectId']);
            $rs = $this->listBySqlId('count_all');

            //实例化项目
            $esmprojectDao = new model_engineering_project_esmproject();
            $esmproject = array('budgetDay' => $rs[0]['personDays'], 'budgetPeople' => $rs[0]['personCostDays'], 'budgetPerson' => $rs[0]['personCost']);
            //更新项目人力预算
            $esmprojectDao->updateProject_d($object['projectId'], $esmproject);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /************************************* 页面渲染部分 ****************************/
    /**
     * 渲染审批页面
     * @param $activityArr
     * @return null|string
     */
    function initViewPage_d($activityArr)
    {
        if ($activityArr) {
            $str = null;
            //标识位
            $mark = null;
            //级别
            $level = 0;
            //缓存任务数组
            $activityCache = array();
            $projectId = '';
            $projectCount = array();
            foreach ($activityArr as $val) {
                $activityCache[$val['id']] = $val;
                $projectId = $val['projectId'];

                $appendStr = $this->rtAppendStr_v($level);
                if ($val['parentId'] == PARENT_ID) {
                    //重置标志位
                    $mark = $val['id'];
                    //重置级数
                    $level = 0;
                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">$val[activityName]</td>
							<td colspan="12"></td>
						</tr>
EOT;
                } else if ($mark == $val['parentId']) {
                    $level++;
                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">└ $val[activityName]</td>
							<td colspan="12"></td>
						</tr>
EOT;
                } else {
                    $showStr = $appendStr . '└ ' . $val['activityName'];

                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">$showStr</td>
							<td colspan="12"></td>
						</tr>
EOT;
                }
                //费用数组
                $rs = $this->getBudgetAndFee_d($val['id']);
                //任务费用合计数组
                $activityCount = array('days' => 0, 'personDays' => 0, 'personCostDays' => 0, 'personCost' => 0, 'number' => 0);

                if ($rs) {
                    foreach ($rs as $v) {
                        $str .= <<<EOT
							<tr class="tr_even">
								<td></td>
								<td><a href="javascript:void(0)" onclick="showThickboxWin('?model=engineering_person_esmperson&action=toView&id=$v[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900')">$v[personLevel]</a></td>
								<td>$v[number]</td>
								<td>$v[planBeginDate]</td>
								<td>$v[planEndDate]</td>
								<td style="text-align:right;padding:0 5px 0 0;">$v[days]</td>
								<td style="text-align:right;padding:0 5px 0 0;">$v[personDays]</td>
								<td style="text-align:right;padding:0 5px 0 0;">$v[personCostDays]</td>
								<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$v[personCost]</span></td>
								<td style="text-align:right;padding:0 5px 0 0;">$v[actDays]</td>
								<td style="text-align:right;padding:0 5px 0 0;">$v[actCostDays]</td>
								<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$v[actCost]</span></td>
							</tr>
EOT;
                        //计算合计金额
                        $projectCount['days'] = bcadd($projectCount['days'], $v['days'], 0);
                        $projectCount['personDays'] = bcadd($projectCount['personDays'], $v['personDays'], 0);
                        $projectCount['personCost'] = bcadd($projectCount['personCost'], $v['personCost'], 2);
                        $projectCount['personCostDays'] = bcadd($projectCount['personCostDays'], $v['personCostDays'], 0);
                        $projectCount['actDays'] = bcadd($projectCount['actDays'], $v['actDays'], 0);
                        $projectCount['actCostDays'] = bcadd($projectCount['actCostDays'], $v['actCostDays'], 0);
                        $projectCount['actCost'] = bcadd($projectCount['actCost'], $v['actCost'], 2);
                        $projectCount['number'] = bcadd($projectCount['number'], $v['number'], 0);
                        $activityCount['days'] = bcadd($activityCount['days'], $v['days'], 0);
                        $activityCount['personDays'] = bcadd($activityCount['personDays'], $v['personDays'], 0);
                        $activityCount['personCost'] = bcadd($activityCount['personCost'], $v['personCost'], 2);
                        $activityCount['personCostDays'] = bcadd($activityCount['personCostDays'], $v['personCostDays'], 0);
                        $activityCount['actDays'] = bcadd($activityCount['actDays'], $v['actDays'], 0);
                        $activityCount['actCostDays'] = bcadd($activityCount['actCostDays'], $v['actCostDays'], 0);
                        $activityCount['actCost'] = bcadd($activityCount['actCost'], $v['actCost'], 2);
                        $activityCount['number'] = bcadd($activityCount['number'], $v['number'], 0);
                    }
                    $str .= <<<EOT
						<tr class="tr_count">
							<td></td>
							<td>任务合计：</td>
							<td><span>$activityCount[number]</span></td>
							<td></td>
							<td></td>
							<td style="text-align:right;padding:0 5px 0 0;">$activityCount[days]</td>
							<td style="text-align:right;padding:0 5px 0 0;">$activityCount[personDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;">$activityCount[personCostDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$activityCount[personCost]</span></td>
							<td style="text-align:right;padding:0 5px 0 0;">$activityCount[actDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;">$activityCount[actCostDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$activityCount[actCost]</span></td>
						</tr>
EOT;
                }
            }

            //非任务人力决算
            $feeNotActivityArr = $this->getFeeNotActivity_d($projectId);
            if ($feeNotActivityArr) {
                //任务费用合计数组
                $activityCount = array('days' => 0, 'personDays' => 0, 'personCostDays' => 0, 'personCost' => 0, 'number' => 0);
                $str .= <<<EOT
					<tr class="tr_odd">
						<td align="left"><span class="red">非项目任务部分</span></td>
						<td colspan="12"></td>
					</tr>
EOT;
                foreach ($feeNotActivityArr as $k => $v) {
                    $str .= <<<EOT
						<tr class="tr_even">
							<td></td>
							<td>$v[personLevel]</td>
							<td>$v[number]</td>
							<td>$v[planBeginDate]</td>
							<td>$v[planEndDate]</td>
							<td style="text-align:right;padding:0 5px 0 0;">$v[days]</td>
							<td style="text-align:right;padding:0 5px 0 0;">$v[personDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;">$v[personCostDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$v[personCost]</span></td>
							<td style="text-align:right;padding:0 5px 0 0;">$v[actDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;">$v[actCostDays]</td>
							<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$v[actCost]</span></td>
							</tr>
EOT;
                    //计算合计金额
                    $projectCount['days'] = bcadd($projectCount['days'], $v['days'], 0);
                    $projectCount['personDays'] = bcadd($projectCount['personDays'], $v['personDays'], 0);
                    $projectCount['personCost'] = bcadd($projectCount['personCost'], $v['personCost'], 2);
                    $projectCount['personCostDays'] = bcadd($projectCount['personCostDays'], $v['personCostDays'], 0);
                    $projectCount['actDays'] = bcadd($projectCount['actDays'], $v['actDays'], 0);
                    $projectCount['actCostDays'] = bcadd($projectCount['actCostDays'], $v['actCostDays'], 0);
                    $projectCount['actCost'] = bcadd($projectCount['actCost'], $v['actCost'], 2);
                    $projectCount['number'] = bcadd($projectCount['number'], $v['number'], 0);
                    $activityCount['days'] = bcadd($activityCount['days'], $v['days'], 0);
                    $activityCount['personDays'] = bcadd($activityCount['personDays'], $v['personDays'], 0);
                    $activityCount['personCost'] = bcadd($activityCount['personCost'], $v['personCost'], 2);
                    $activityCount['personCostDays'] = bcadd($activityCount['personCostDays'], $v['personCostDays'], 0);
                    $activityCount['actDays'] = bcadd($activityCount['actDays'], $v['actDays'], 0);
                    $activityCount['actCostDays'] = bcadd($activityCount['actCostDays'], $v['actCostDays'], 0);
                    $activityCount['actCost'] = bcadd($activityCount['actCost'], $v['actCost'], 2);
                    $activityCount['number'] = bcadd($activityCount['number'], $v['number'], 0);
                }
                $str .= <<<EOT
					<tr class="tr_count">
						<td></td>
						<td>任务合计：</td>
						<td><span>$activityCount[number]</span></td>
						<td></td>
						<td></td>
						<td style="text-align:right;padding:0 5px 0 0;">$activityCount[days]</td>
						<td style="text-align:right;padding:0 5px 0 0;">$activityCount[personDays]</td>
						<td style="text-align:right;padding:0 5px 0 0;">$activityCount[personCostDays]</td>
						<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$activityCount[personCost]</span></td>
						<td style="text-align:right;padding:0 5px 0 0;">$activityCount[actDays]</td>
						<td style="text-align:right;padding:0 5px 0 0;">$activityCount[actCostDays]</td>
						<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$activityCount[actCost]</span></td>
						</tr>
EOT;
            }


            $str .= <<<EOT
				<tr class="tr_count">
					<td></td>
					<td>项目合计：</td>
					<td><span>$projectCount[number]</span></td>
					<td></td>
					<td></td>
					<td style="text-align:right;padding:0 5px 0 0;">$projectCount[days]</td>
					<td style="text-align:right;padding:0 5px 0 0;">$projectCount[personDays]</td>
					<td style="text-align:right;padding:0 5px 0 0;">$projectCount[personCostDays]</td>
					<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$projectCount[personCost]</span></td>
					<td style="text-align:right;padding:0 5px 0 0;">$projectCount[actDays]</td>
					<td style="text-align:right;padding:0 5px 0 0;">$projectCount[actCostDays]</td>
					<td style="text-align:right;padding:0 5px 0 0;"><span class="formatMoney">$projectCount[actCost]</span></td>
				</tr>
EOT;
            return $str;
        } else {
            return "<tr><td colspan='12'>项目没有对应预算信息</td></tr>";
        }
    }

    /**
     * 返回前置空格
     * @param $level
     * @return string
     */
    function rtAppendStr_v($level)
    {
        if ($level == 0) {
            return "";
        }
        $str = "";
        for ($i = 0; $i < $level; $i++) {
            $str .= "&nbsp;&nbsp;&nbsp;";
        }
        return $str;
    }

    /**
     * 查询任务的人力费用和预算
     * @param $activityId
     * @return mixed
     */
    function getBudgetAndFee_d($activityId)
    {
        $sql = "select
				c.id,c.personLevel,c.number,c.planBeginDate,c.planEndDate,
				sum(if(c.thisType = 1,c.days,0)) as days,sum(if(c.thisType = 1,c.personDays,0)) as personDays,
				sum(if(c.thisType = 1,c.personCostDays,0)) as personCostDays,sum(if(c.thisType = 1,c.personCost,0)) as personCost,
				sum(if(c.thisType = 2,c.actDays,0)) as actDays,sum(if(c.thisType = 2,c.actCostDays,0)) as actCostDays,
				sum(if(c.thisType = 2,c.actCost,0)) as actCost,group_concat(cast(c.thisType as char(50))) as thisType
			from
			(
				select
					personLevel,personLevelId,id,activityName,activityId,number,planBeginDate,planEndDate,days,personDays,personCostDays,personCost,
					0 as actDays,0 as actCostDays,0 as actCost,1 as thisType
				from oa_esm_project_person where activityId = $activityId
				union all
				select personLevel,personLevelId,'',activityName,activityId,count(*),'','','',0,0,0,
					sum(actDays) as actDays,sum(actCostDays) as actCostDays,sum(actCost) as actCost ,2 as thisType
				from oa_esm_project_activitymember where activityId = $activityId group by personLevelId
			) c group by c.personLevelId";
        return $this->_db->getArray($sql);
    }

    /**
     * 查询任务的人力费用和预算
     * @param $projectId
     * @return mixed
     */
    function getFeeNotActivity_d($projectId)
    {
        $sql = "select
				c.personLevel,count(*) as number,'' as planBeginDate,'' as planEndDate,0 as days,0 as personDays,0 as personCostDays,0 as personCost,
				sum(l.workDays) as actDays,
				round(sum(l.workDays * c.coefficient),0) as actCostDays,sum(l.workDays * c.price) as actCost
			from
				oa_esm_project_member c
				inner join
				(select createId,count(*) as workDays from oa_esm_worklog where projectId = $projectId and activityId = '' GROUP BY createId) l
				on c.memberId = l.createId
			where c.projectId = $projectId
			group by c.personLevelId";
        return $this->_db->getArray($sql);
    }

    /*************************** 工资部分接入 *************************/
    /**
     * 更新人员决算
     * @param $thisYear
     * @param $thisMonth
     * @return bool|string
     */
    function updateSalary_d($thisYear, $thisMonth)
    {
        set_time_limit(0);
        try {
            // 获取人员工资
            $gl = new includes_class_global();
            $salary = $gl->get_salary_info($thisYear, $thisMonth);

            //如果查询到了工资,则进入更新部分
            if (!isset($salary['error'])) {
                // 获取人员工作量 - 某年某月某人在某项目上的工作量
                $logDao = new model_engineering_worklog_esmworklog();
                $logData = $logDao->getLogData_d($thisYear, $thisMonth);
                $personInfoMap = $logDao->getPersonnelInfoMap_d();

                if (empty($logData)) {
                    throw new Exception('本月没有日志信息，更新失败');
                }

                //接入价格转换
                $salary = $this->costToPrice_d($thisYear, $thisMonth, $salary, $personInfoMap);

                //同步人员月工作量
                $esmpersonfeeDao = new model_engineering_person_esmpersonfee();
                $projectIdArr = $esmpersonfeeDao->synLogInfo_d($thisYear, $thisMonth, $salary, $logData);

                if (!empty($projectIdArr)) {
                    // 转成字符串给后面的业务使用
                    $projectIds = implode(',', $projectIdArr);

                    //更新项目内人员决算
                    $esmmemberDao = new model_engineering_member_esmmember();
                    $esmmemberDao->updateMemberFee_d($projectIds);

                    //更新项目人力决算
                    $sql = "UPDATE
						oa_esm_project c
							INNER JOIN
						(
							SELECT projectId,SUM(feePerson) AS feePerson FROM oa_esm_project_member
							WHERE projectId IN ($projectIds) GROUP BY projectId
						) p
							ON c.id = p.projectId
					SET c.feePerson = p.feePerson
					WHERE c.id IN($projectIds)";
                    $this->_db->query($sql);

                    //更新项目决算
                    $esmprojectDao = new model_engineering_project_esmproject();
                    $esmprojectDao->calProjectFee_d(null, $projectIds); //更新项目总决算
                    $esmprojectDao->calFeeProcess_d(null, $projectIds); //更新项目决算进度
                    $esmprojectDao->updateContractInfo_d($projectIdArr); //更新项目对应的源单
                }
            } else {
                throw new Exception($salary['error']);
            }

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 数据转换 - 将月工资专为单价
     * @param $thisYear
     * @param $thisMonth
     * @param $salary
     * @param $personInfoMap
     * @return array|mixed
     */
    function costToPrice_d($thisYear, $thisMonth, $salary, $personInfoMap)
    {
        // 月工资数据
        $monthSalary = array();
        if ($salary) {
            $monthSalary = array_pop($salary); //工资数据前面的年月不需要了
            $monthDays = date("t", mktime(0, 0, 0, $thisMonth, 1, $thisYear)); // 本月自然天数
            $monthBeginStamp = strtotime($thisYear . '-' . $thisMonth . '-01'); // 本月开始
            $monthEndStamp = strtotime(date('Y-m-t', $monthBeginStamp)); // 本月结束
            foreach ($monthSalary as $k => &$v) {
                // 如果入职日期大于本月开始日期，则从入职日期开始计算本月天数
                $actDay = $personInfoMap[$k]['entryTime'] > $monthBeginStamp ?
                    ($monthEndStamp - $personInfoMap[$k]['entryTime']) / 86400 + 1 : $monthDays;
                $v['price'] = bcdiv($v['paycost'], $actDay, 2);
            }
        }
        return $monthSalary;
    }
}