<?php

/**
 * @author Show
 * @Date 2012��6��18�� ����һ 17:35:04
 * @version 1.0
 * @description:��Ŀ����Ԥ��(oa_esm_project_person) Model��
 */
class model_engineering_person_esmperson extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project_person";
        $this->sql_map = "engineering/person/esmpersonSql.php";
        parent:: __construct();
    }

    /****************************��ȡ�ⲿ��Ϣ����***************************/

    /**
     * ��ȡ��Ŀ��Ϣ
     * @param $projectId
     * @return bool|mixed
     */
    function getEsmprojectInfo_d($projectId)
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->find(array('id' => $projectId), null, 'projectCode,projectName');
    }

    /**
     * ��ȡ���Ϣ
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
     * ��ȡ���Ϣ
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
     * ��ȡ��Ŀ��Ϣ
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
     * ��ȡ����¼��id
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

    /*************************** �ڲ����� *****************************/
    /**
     * ��дadd_d
     * @param $object
     * @return bool
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            //��������
            $newId = parent::add_d($object, true);

            //������Ŀ��Ϣ
            $this->updateProject_d($object);

            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��������
     * @param $object
     * @return bool
     */
    function addBatch_d($object)
    {
        //��ȡ�ӱ�Ԥ����Ϣ
        $person = $object['person'];
        unset($object['person']);
        try {
            $this->start_d(); //������
            //����������Ϣ
            $object = $this->addCreateInfo($object);

            //������Ԥ��ҳ�����ݵ���Ŀ������Ԥ��
            $this->createBatch($person, $object);

            //������Ŀ���ֳ�Ԥ��
            $this->updateProject_d($object);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��дedit_d
     * @param $object
     * @return bool
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            //��������
            $newId = parent::edit_d($object, true);

            //������Ŀ��Ϣ
            $this->updateProject_d($object);

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������
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
     * �򵥱༭
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
     * @desription ɾ�����ڵ㼰�����ڵ�
     *
     */
    function deletes_d($id)
    {
        try {
            $this->start_d();

            $idArr = explode(',', $id);
            //��Ҫ�����Id
            $dealId = $idArr[0];

            $obj = $this->find(array('id' => $dealId), null, 'projectId');

            //ɾ��Ԥ����
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
     * ��Ŀ�������� �� ��Ŀ����Ԥ��(���������޸�ɾ��)
     */
    function dealPerson_d($object, $activityId, $activityName)
    {
        //        print_r($object);
        if (!empty($object)) {

            try {
                $this->start_d();

                //��Ա��Ϣ��������
                $personArr = array();
                //ѭ������ -  ��Ҫ�ǽ�ͬ�������Ԥ��ϲ�
                foreach ($object as $key => $val) {
                    if (empty($val['personLevelId'])) {
                        continue;
                    }
                    $markStr = $val['personLevelId'] . '-' . $val['planBeginDate'] . '-' . $val['planEndDate'];
                    //�����Ѵ��ڵ���Ϣ
                    if (isset($personArr[$markStr])) {
                        $personArr[$markStr]['personDays'] = bcadd($personArr[$markStr]['personDays'], $val['personDays']); //�������
                        $personArr[$markStr]['personCostDays'] = bcadd($personArr[$markStr]['personCostDays'], $val['personCostDays']); //�����ɱ�
                        $personArr[$markStr]['personCost'] = bcadd($personArr[$markStr]['personCost'], $val['personCost']); //�ɱ����
                        $personArr[$markStr]['number'] = bcadd($personArr[$markStr]['number'], $val['number']); //�������
                    } else {
                        $personArr[$markStr] = $val;
                    }
                }

                if ($personArr) {
                    //ѭ����������
                    foreach ($personArr as $v) {
                        $v['activityId'] = $activityId;
                        $v['activityName'] = $activityName;
                        parent::add_d($v, true);
                    }
                    //������Ŀ
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
     * �����޸�
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
            //������Ŀ
            $this->updateProject_d($val);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    /*************************** �߼������� ���ⲿ���� *************************/
    /**
     * �ж���Ŀ�Ƿ�����������Ԥ��
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

    //��ȡ��Ŀ����Ԥ��
    function getProjectBudget_d($projectId)
    {
        //��ȡ��Ŀ��ǰ����Ԥ��
        $this->searchArr = array('projectId' => $projectId);
        $rs = $this->listBySqlId('count_all');
        if ($rs[0]['personDays']) {
            return array('budgetDay' => $rs[0]['personDays'], 'budgetPeople' => $rs[0]['personCostDays'], 'budgetPerson' => $rs[0]['personCost']);
        } else {
            return array('budgetDay' => 0, 'budgetPeople' => 0, 'budgetPerson' => 0);
        }
    }

    /**
     * ������Ŀ��Ϣ - ͳһ���÷���
     * ������Ҫ���� projectId
     */
    function updateProject_d($object)
    {
        try {
            $this->start_d();

            //��ȡ��Ŀ��ǰ����Ԥ��
            $this->searchArr = array('projectId' => $object['projectId']);
            $rs = $this->listBySqlId('count_all');

            //ʵ������Ŀ
            $esmprojectDao = new model_engineering_project_esmproject();
            $esmproject = array('budgetDay' => $rs[0]['personDays'], 'budgetPeople' => $rs[0]['personCostDays'], 'budgetPerson' => $rs[0]['personCost']);
            //������Ŀ����Ԥ��
            $esmprojectDao->updateProject_d($object['projectId'], $esmproject);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /************************************* ҳ����Ⱦ���� ****************************/
    /**
     * ��Ⱦ����ҳ��
     * @param $activityArr
     * @return null|string
     */
    function initViewPage_d($activityArr)
    {
        if ($activityArr) {
            $str = null;
            //��ʶλ
            $mark = null;
            //����
            $level = 0;
            //������������
            $activityCache = array();
            $projectId = '';
            $projectCount = array();
            foreach ($activityArr as $val) {
                $activityCache[$val['id']] = $val;
                $projectId = $val['projectId'];

                $appendStr = $this->rtAppendStr_v($level);
                if ($val['parentId'] == PARENT_ID) {
                    //���ñ�־λ
                    $mark = $val['id'];
                    //���ü���
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
							<td align="left">�� $val[activityName]</td>
							<td colspan="12"></td>
						</tr>
EOT;
                } else {
                    $showStr = $appendStr . '�� ' . $val['activityName'];

                    $str .= <<<EOT
						<tr class="tr_odd">
							<td align="left">$showStr</td>
							<td colspan="12"></td>
						</tr>
EOT;
                }
                //��������
                $rs = $this->getBudgetAndFee_d($val['id']);
                //������úϼ�����
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
                        //����ϼƽ��
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
							<td>����ϼƣ�</td>
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

            //��������������
            $feeNotActivityArr = $this->getFeeNotActivity_d($projectId);
            if ($feeNotActivityArr) {
                //������úϼ�����
                $activityCount = array('days' => 0, 'personDays' => 0, 'personCostDays' => 0, 'personCost' => 0, 'number' => 0);
                $str .= <<<EOT
					<tr class="tr_odd">
						<td align="left"><span class="red">����Ŀ���񲿷�</span></td>
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
                    //����ϼƽ��
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
						<td>����ϼƣ�</td>
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
					<td>��Ŀ�ϼƣ�</td>
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
            return "<tr><td colspan='12'>��Ŀû�ж�ӦԤ����Ϣ</td></tr>";
        }
    }

    /**
     * ����ǰ�ÿո�
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
     * ��ѯ������������ú�Ԥ��
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
     * ��ѯ������������ú�Ԥ��
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

    /*************************** ���ʲ��ֽ��� *************************/
    /**
     * ������Ա����
     * @param $thisYear
     * @param $thisMonth
     * @return bool|string
     */
    function updateSalary_d($thisYear, $thisMonth)
    {
        set_time_limit(0);
        try {
            // ��ȡ��Ա����
            $gl = new includes_class_global();
            $salary = $gl->get_salary_info($thisYear, $thisMonth);

            //�����ѯ���˹���,�������²���
            if (!isset($salary['error'])) {
                // ��ȡ��Ա������ - ĳ��ĳ��ĳ����ĳ��Ŀ�ϵĹ�����
                $logDao = new model_engineering_worklog_esmworklog();
                $logData = $logDao->getLogData_d($thisYear, $thisMonth);
                $personInfoMap = $logDao->getPersonnelInfoMap_d();

                if (empty($logData)) {
                    throw new Exception('����û����־��Ϣ������ʧ��');
                }

                //����۸�ת��
                $salary = $this->costToPrice_d($thisYear, $thisMonth, $salary, $personInfoMap);

                //ͬ����Ա�¹�����
                $esmpersonfeeDao = new model_engineering_person_esmpersonfee();
                $projectIdArr = $esmpersonfeeDao->synLogInfo_d($thisYear, $thisMonth, $salary, $logData);

                if (!empty($projectIdArr)) {
                    // ת���ַ����������ҵ��ʹ��
                    $projectIds = implode(',', $projectIdArr);

                    //������Ŀ����Ա����
                    $esmmemberDao = new model_engineering_member_esmmember();
                    $esmmemberDao->updateMemberFee_d($projectIds);

                    //������Ŀ��������
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

                    //������Ŀ����
                    $esmprojectDao = new model_engineering_project_esmproject();
                    $esmprojectDao->calProjectFee_d(null, $projectIds); //������Ŀ�ܾ���
                    $esmprojectDao->calFeeProcess_d(null, $projectIds); //������Ŀ�������
                    $esmprojectDao->updateContractInfo_d($projectIdArr); //������Ŀ��Ӧ��Դ��
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
     * ����ת�� - ���¹���רΪ����
     * @param $thisYear
     * @param $thisMonth
     * @param $salary
     * @param $personInfoMap
     * @return array|mixed
     */
    function costToPrice_d($thisYear, $thisMonth, $salary, $personInfoMap)
    {
        // �¹�������
        $monthSalary = array();
        if ($salary) {
            $monthSalary = array_pop($salary); //��������ǰ������²���Ҫ��
            $monthDays = date("t", mktime(0, 0, 0, $thisMonth, 1, $thisYear)); // ������Ȼ����
            $monthBeginStamp = strtotime($thisYear . '-' . $thisMonth . '-01'); // ���¿�ʼ
            $monthEndStamp = strtotime(date('Y-m-t', $monthBeginStamp)); // ���½���
            foreach ($monthSalary as $k => &$v) {
                // �����ְ���ڴ��ڱ��¿�ʼ���ڣ������ְ���ڿ�ʼ���㱾������
                $actDay = $personInfoMap[$k]['entryTime'] > $monthBeginStamp ?
                    ($monthEndStamp - $personInfoMap[$k]['entryTime']) / 86400 + 1 : $monthDays;
                $v['price'] = bcdiv($v['paycost'], $actDay, 2);
            }
        }
        return $monthSalary;
    }
}