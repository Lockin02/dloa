<?php

/**
 * @author Administrator
 * @Date 2011��12��12�� 17:06:50
 * @version 1.0
 * @description:��Ŀ��Χ(oa_esm_project_activity) Model��
 * status {0:����,1:���,2:��ͣ}
 */
class model_engineering_activity_esmactivity extends model_treeNode
{

    function __construct() {
        $this->tbl_name = "oa_esm_project_activity";
        $this->sql_map = "engineering/activity/esmactivitySql.php";
        parent::__construct();
    }

    // �����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'workloadUnit'
    );

    /************************ �ⲿ��Ϣ��ȡ ***********************/
    /**
     * ��ȡ��Ŀ��Ϣ
     * @param $projectId
     * @return bool|mixed
     */
    function getObjInfo_d($projectId) {
        $projectDao = new model_engineering_project_esmproject();
        return $projectDao->get_d($projectId);
    }

    /************************* ��ɾ�Ĳ� ************************/

    /**
     * ����
     * @param ���뱣������ڵ� $object
     * @return bool|null
     */
    function add_d($object) {
        //��ȡ��Ա����
        $esmperson = $object['esmperson'];
        unset($object['esmperson']);

        try {
            $this->start_d();

            //�жϴ��޸��Ƿ����ڱ��
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                //Ԥ������
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $newId = $esmchangeactDao->add_d($object, $this);
            } else {

                //��������
                $newId = $this->addOrg_d($object);

                //���������Ա
                $esmactmemberDao = new model_engineering_activity_esmactmember();
                $esmactmemberDao->batchDeal_d($esmperson, $newId, $object['activityName']);

                //������Ŀ����Ԥ��
                $esmpersonDao = new model_engineering_person_esmperson();
                $esmpersonDao->dealPerson_d($esmperson, $newId, $object['activityName']);

                //�����ϼ����������Ϣ
                $this->updateAllParent_d($newId, $object);

                //�ж��Ƿ��ǳﱸ״̬����Ŀ
                $esmprojectArr = $esmprojectDao->find(array('id' => $object['projectId']), null, 'status');
                if ($esmprojectArr['status'] == 'GCXMZT01') {
                    //������ĿԤ�ƿ�ʼ�ͽ�������
                    $this->updatePlanDate_d($object['projectId']);
                }
            }

            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������
     * @param $object
     * @return bool|null
     * @throws Exception
     */
    function addOrg_d($object) {
        try {
            //�����ֵ����Ĵ���
            $object = $this->processDatadict($object);
            return parent::add_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        //��ȡ�����Ա
        $esmactmember = $object['esmactmember'];
        unset($object['esmactmember']);
        //��ȡ����Ԥ��
        $esmperson = $object['esmperson'];
        unset($object['esmperson']);
        try {
            $this->start_d();

            if ($object['orgId'] != -1) {
                //Ԥ������
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->editOrg_d($object);
            } else {
                //�жϴ��޸��Ƿ����ڱ��
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                    //Ԥ������
                    $esmchangeactDao = new model_engineering_change_esmchangeact();
                    $esmchangeactDao->edit_d($object, $this);
                } else {
                    //���������߲������ҽڵ�id
                    $this->editOrg_d($object);

                    //���������Ա
                    $esmactmemberDao = new model_engineering_activity_esmactmember();
                    $esmactmemberDao->batchDeal_d($esmactmember);

                    //��������Ԥ��
                    $esmpersonDao = new model_engineering_person_esmperson();
                    $esmpersonDao->batchEdit_d($esmperson);

                    //�����ϼ����������Ϣ
                    $this->updateAllParent_d($object['id'], $object);

                    //�ж��Ƿ��ǳﱸ״̬����Ŀ
                    $esmprojectArr = $esmprojectDao->find(array('id' => $object['projectId']), null, 'status');
                    if ($esmprojectArr['status'] == 'GCXMZT01') {
                        //������ĿԤ�ƿ�ʼ�ͽ�������
                        $this->updatePlanDate_d($object['projectId']);
                    }
                }
            }

            $this->commit_d();
            return $object;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �򵥱༭
     * @param $object
     * @return bool|null|������ӵĽڵ����|�����ƶ�
     * @throws Exception
     */
    function editOrg_d($object) {
        try {
            //�����ֵ����Ĵ���
            $object = $this->processDatadict($object);
            return parent::edit_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * �򵥱༭
     * @param $object
     * @return bool
     */
    function editWorkloadDone_d($object) {
        try {
            //��������������
            $process = bcmul(bcdiv($object['workloadDone'], $object['days'], 6), 100, 4);
            $updateArr = array(
                'workloadDone' => $object['workloadDone'],
                'process' => $process > 100 ? 100 : $process,
                'confirmDate' => day_date, 'confirmId' => $_SESSION['USER_ID'],
                'confirmName' => $_SESSION['USERNAME'],
                'confirmDays' => bcsub($object['workloadDone'], $object['workloadCount'])
            );
            return $this->update(array('id' => $object['id']), $updateArr);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * ɾ�����ڵ㼰�����ڵ�
     * @param $id
     * @param null $changeId
     * @param null $projectId
     * @return bool
     */
    function deletes_d($id, $changeId = null, $projectId = null) {
        try {
            $this->start_d();

            //����Ǳ�������
            if ($changeId) {
                //ת�������ִ���
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->deletes_d($id, $changeId);
            } else {
                //�жϴ��޸��Ƿ����ڱ��
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($projectId)) {
                    //���ɾ��
                    $esmchangeactDao = new model_engineering_change_esmchangeact();
                    $esmchangeactDao->deletes_d($id, null, $projectId);
                } else {
                    //��ȡ�ӽڵ�
                    $node = $this->get_d($id);
                    $childNodes = $this->getChildrenByNode($node);
                    if ($childNodes) {
                        foreach ($childNodes as $val) {
                            $this->deleteNodes($val['id']);
                            parent::deletes($val['id']);
                        }
                    }
                    parent::deletes($id);

                    //ɾ��ʱ���¼�����Ŀ��Ϣ
                    $esmprojectDao = new model_engineering_project_esmproject();
                    $esmprojectDao->updateProjectBudget_d($node['projectId']);

                    //�����ϼ����������Ϣ
                    $this->updateAllParent_d($id, $node);
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ת����Ŀ
     * @param $object
     * @return bool
     */
    function move_d($object) {
        //��ȡ�����Ա
        $esmactmember = $object['esmactmember'];
        unset($object['esmactmember']);
        //��ȡ����Ԥ��
        $esmperson = $object['esmperson'];
        unset($object['esmperson']);

        try {
            $this->start_d();

            //�ж�����Ѿ��Ǳ���������⴦��
            if ($object['changeId']) {
                //Ԥ������
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->move_d($object, $this);
            } else {
                //�жϴ��޸��Ƿ����ڱ��
                $esmprojectDao = new model_engineering_project_esmproject();
                if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                    //ʵ������������
                    $esmchangeactDao = new model_engineering_change_esmchangeact();
                    $esmchangeactDao->move_d($object, $this);
                } else {
                    //���������߲������ҽڵ�id
                    $newId = $this->addOrg_d($object);

                    //���������Ա
                    $esmactmember = util_arrayUtil::setArrayFn(array('activityId' => $newId, 'activityName' => $object['acitivityName']), $esmactmember);
                    $esmactmemberDao = new model_engineering_activity_esmactmember();
                    $esmactmemberDao->batchDeal_d($esmactmember);

                    //��������Ԥ��
                    $esmperson = util_arrayUtil::setArrayFn(array('activityId' => $newId, 'activityName' => $object['acitivityName']), $esmperson);
                    $esmpersonDao = new model_engineering_person_esmperson();
                    $esmpersonDao->batchEdit_d($esmperson);

                    //Ԥ�㲿�ָ���
                    $esmbudgetDao = new model_engineering_budget_esmbudget();
                    $esmbudgetDao->update(array('activityId' => $object['parentId']), array('activityId' => $newId, 'activityName' => $object['acitivityName']));

                    //ԭ�������
                    $this->clearActivity_d($object['parentId']);

                    //�����ϼ����������Ϣ
                    $this->updateAllParent_d($newId, $object);
                }
            }

            $this->commit_d();
            return $object;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ������շ�����Ϊ��ʹ���ݿ���������
     * @param $id
     */
    function clearActivity_d($id) {
        $sql = "update " . $this->tbl_name . " set
				budgetAll = null,memberId = null,memberName = null,workload = null,workloadUnit = null ,workloadUnitName = null,
				workContent = null,remark = null
			where id = " . $id;
        $this->_db->query($sql);
    }

    /************************** ҵ���߼��ӿ� ************************/

    /**
     * ��ȡ��Ŀ�еĿ�������
     * @param $projectId
     * @return mixed
     */
    function getProjectActivity_d($projectId) {
        return $this->findAll(array('projectId' => $projectId), 'lft');
    }

    /**
     * ��Ŀ��Χ - �����ϼ� - �ݹ麯��
     * @param $id
     * @param null $obj
     * @return bool
     */
    function updateAllParent_d($id, $obj = null) {
        if (empty($obj)) {
            $obj = $this->get_d($id);
        }

        // ��ѯ��Χ�����������Լ�����
        $sql = "select
				min(planBeginDate) as planBeginDate,max(planEndDate) as planEndDate,
				round((UNIX_TIMESTAMP( max(planEndDate) ) - UNIX_TIMESTAMP( min(planBeginDate) ) )/(3600 *24)) + 1 as days,
				round(sum(workRate*process/100),2) as process
			from oa_esm_project_activity where parentId = " . $obj['parentId'];
        $rs = $this->_db->getArray($sql);
        $this->update(
            array('id' => $obj['parentId']),
            array('planBeginDate' => $rs[0]['planBeginDate'], 'planEndDate' => $rs[0]['planEndDate'], 'days' => $rs[0]['days'], 'process' => $rs[0]['process'])
        );

        // ����Ѿ��Ǹ��ڵ㣬ֱ�ӷ���
        if ($obj['parentId'] == PARENT_ID) {
            return true;
        } else {
            return $this->updateAllParent_d($obj['parentId']);
        }
    }

    /**
     * ��֤�Ƿ���ڸ��ڵ㣬������������
     */
    function checkParent_d() {
        $this->searchArr['id'] = -1;
        $rs = $this->list_d('select_default');
        if (is_array($rs)) {
            return true;
        } else {
            $this->create(array('id' => -1, 'activityName' => '��Ŀ', 'lft' => 1, 'rgt' => 2));
            return false;
        }
    }

    /**
     * ��ȡid�µ�����Id,�������ڵ�
     * @param $id
     * @param $lft
     * @param $rgt
     * @return null|string
     */
    function getUnderTreeIds_d($id, $lft, $rgt) {
        $this->searchArr = array('biglft' => $lft, 'smallrgt' => $rgt);
        $rs = $this->list_d();
        if (is_array($rs)) {
            $idArr = array();
            foreach ($rs as $val) {
                array_push($idArr, $val['id']);
            }
            return $ids = implode($idArr, ',');
        }
        return null;
    }

    /**
     * ��ȡ�����ڵĹ�����
     * @param $projectId
     * @param int $parentId
     * @param null $changeId
     * @return int
     * @throws Exception
     */
    function getWorkRateByParentId_d($projectId, $parentId = -1, $changeId = null) {
        $this->searchArr = array(
            'projectId' => $projectId,
            'parentId' => $parentId
        );
        //���û�б������id,����һ�β�ѯ
        if (!$changeId) {
            $esmchangeDao = new model_engineering_change_esmchange();
            $changeId = $esmchangeDao->getChangeId_d($projectId, false);
        }

        if ($changeId) {
            $esmchangeactDao = new model_engineering_change_esmchangeact();
            $workRate = $esmchangeactDao->workRateCount($changeId, $parentId);
            $rs = array(array(
                'workRate' => $workRate
            ));
        } else {
            $rs = $this->list_d('sum_list');
        }

        if (is_array($rs)) {
            return $rs[0]['workRate'];
        } else {
            return 0;
        }
    }

    /**
     * ��������Ľ��� - ��־¼��ҳ�������ȵ��÷���
     * @param $id
     * @param $workload
     * @param null $worklogId
     * @return array
     */
    function calTaskProcess_d($id, $workload, $worklogId = null) {
        $obj = $this->get_d($id);

        //��ȡ��־ͳ����Ϣ
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $countInfo = $esmworklogDao->getWorklogCountInfo_d($id, $worklogId);

        //��������к��й����������Թ��������㣬�����Թ��ڼ���
        $newWorkLoad = bcadd($workload, $countInfo['workloadDay'], 2);
        $process = bcmul(bcdiv($newWorkLoad, $obj['workload'], 4), 100, 2);
        if ($process > 100) {
            $process = 100;
        }

        //��������Ӧ����İٷֱ� - ��Ҷ�����ϵݹ�
        $thisActivityProcess = bcmul(bcdiv($workload, $obj['workload'], 4), 100, 2);

        //��������Ӧ��Ŀ�İٷֱ� - ��Ҷ������
        $thisProjectProcess = $this->calThisProjectProcess_d($obj, $thisActivityProcess);

        return array('process' => $process, 'thisActivityProcess' => $thisActivityProcess, 'thisProjectProcess' => $thisProjectProcess);
    }

    /**
     * �ݹ��������ռ��Ŀ����
     * @param $object
     * @param $thisRate
     * @return string
     */
    function calThisProjectProcess_d($object, $thisRate) {
        //�����
        if ($object['parentId'] == PARENT_ID) {
            return bcdiv(bcmul($thisRate, $object['workRate'], 2), 100, 2);
        } else {
            $obj = $this->get_d($object['parentId']);
            $thisRate = bcdiv(bcmul($thisRate, $object['workRate'], 2), 100, 2);
            return $this->calThisProjectProcess_d($obj, $thisRate);
        }
    }

    /**
     * �����Լ������������
     * @param $id
     * @param $countInfo
     * @return mixed
     * @throws Exception
     */
    function updateTaskProcess_d($id, $countInfo) {
        $obj = $this->get_d($id);

        //��������к��й����������Թ��������㣬�����Թ��ڼ���
        $process = bcmul(bcdiv($countInfo['workloadDay'], $obj['workload'], 4), 100, 2);
        if ($process > 100) {
            $process = 100;
        }
        if ($process < 0) {
            $process = 0;
        }
        try {
            //��������
            $conditionArr = array('id' => $id);

            $needDay = bcsub($obj['days'], $countInfo['workDay']);
            //��������
            $updateArr = array(
                'process' => $process,
                'workloadDone' => $countInfo['workloadDay'],
                'actBeginDate' => $countInfo['actBeginDate'],
                'needDays' => $needDay,
                'workedDays' => $countInfo['workDay']
            );

            //��������
            $this->update($conditionArr, $updateArr);

            //�����ϼ�
            $this->updateAllParent_d($id, $obj);

            return $updateArr['process'];
        } catch (exception $e) {
            throw $e;
        }
    }

    /**
     * ��ϸ����ʵ����������Ѿ�ʵ�ʹ���
     * @param $id
     * @param null $actBeginDate
     * @param null $actEndDate
     * @return bool
     * @throws Exception
     */
    function updateEndDate_d($id, $actBeginDate = null, $actEndDate = null) {
        try {
            //����ʵ�ʹ���
            $actDays = bcdiv(strtotime($actEndDate) - strtotime($actBeginDate), 86400) + 1;

            //����ʵ�ʽ�������
            return $this->update(array('id' => $id), array('actEndDate' => $actEndDate, 'actDays' => $actDays));
        } catch (exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ�ϼ����� - �˴�����һ������id,�ݹ�������Ŀ����
     * @param $projectId
     * @return int
     */
    function getProjectProcess_d($projectId) {
        return $this->calProcessForParentId_d(PARENT_ID, $projectId);
    }

    /**
     * ����ͬһ��Ľ���
     * @param $parentId
     * @param $projectId
     * @return int
     */
    function calProcessForParentId_d($parentId, $projectId) {
        $this->searchArr = array(
            'parentId' => $parentId,
            'projectId' => $projectId
        );
        $this->groupBy = 'c.parentId,c.projectId';
        $rs = $this->list_d('parent_process');
        if ($rs[0]['process']) {
            return $rs[0]['process'];
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ��Ŀ������Ϣ
     * @param $projectId
     * @return array|null
     */
    function getListProcess_d($projectId) {
        $esmworklogDao = new model_engineering_worklog_esmworklog();
        $processList = $esmworklogDao->getListProcess_d($projectId);
        if ($processList) {
            $newProcessList = array();
            foreach ($processList as $val) {
                $newProcessList[$val['activityId']] = $val;
            }
            return $newProcessList;
        } else {
            return null;
        }
    }

    /*********************** ������ִ��� *************************/

    /**
     * ��� �༭����ʱ����״̬ add ,edit ,delete
     * @param $id
     * @param string $thisAction
     * @return bool
     */
    function changeInfoSet_d($id, $thisAction = 'add') {
        return $this->update(array('id' => $id), array('changeAction' => $thisAction, 'isChanging' => 1));
    }

    /**
     * ���״̬��ԭ
     * @param $projectId
     * @return bool
     */
    function revertChangeInfo_d($projectId) {
        return $this->update(array('projectId' => $projectId), array('changeAction' => '', 'isChanging' => '0'));
    }

    /**
     * ������Ŀid������Ŀ������ռ���ܺ�
     * @param $projectId
     * @return int
     */
    function workRateCount($projectId) {
        $num = 0;
        $workRateRow = $this->findAll(array('parentId' => -1, 'projectId' => $projectId), null, 'workRate');
        $workRateRow = array_filter($workRateRow);
        if (!is_array($workRateRow) || empty($workRateRow))
            return 0;
        foreach ($workRateRow as $value) {
            $num += $value['workRate'];
        }
        return $num;
    }

    /**
     * ������Ŀid������Ŀ����-�¼�����-����ռ���ܺ�
     * @param $projectId
     * @param $parentId
     * @param $result
     * @return array
     */
    function workRateCountNew($projectId, $parentId, $result) {
        $num = 0;
        if (empty($result)) {
            $result = array('count' => 100, 'parentName' => null);
        }
        if ($parentId == -1) {
            $idRow = $this->findAll(array('parentId' => $parentId, 'projectId' => $projectId), null, 'id');
            $idRow = array_filter($idRow);
            if (!empty($idRow)) {
                foreach ($idRow as $value) {
                    $workRateNext = $this->workRateCountNew($projectId, $value['id'], $result);
                    if ($workRateNext['count'] != 100) {
                        if (empty($workRateNext['parentName'])) {
                            $result['parentName'] = $this->getParentName($value['id']);
                        } else {
                            $result['parentName'] = $workRateNext['parentName'];
                        }
                        $result['count'] = $workRateNext['count'];
                        return $result;
                    }
                }
                return $result;
            } else {
                return $result;
            }
        } else {
            $idWorkRate = $this->getIdWorkRate($projectId, $parentId);
            if (!empty($idWorkRate)) { //�����û���¼�����
                foreach ($idWorkRate as $v) {
                    $num += $v['workRate'];
                }
                if ($num != 100) {
                    if (empty($result['parentName'])) {
                        $result['parentName'] = $this->getParentName($idWorkRate[0]['id']);
                    }
                    $result['count'] = $num;
                    return $result;
                }

                foreach ($idWorkRate as $v) {
                    $workRateNext = $this->workRateCountNew($projectId, $v['id'], $result);
                    if ($workRateNext['count'] != 100) {
                        if (empty($workRateNext['parentName'])) {
                            $result['parentName'] = $this->getParentName($v['id']);
                        } else {
                            $result['parentName'] = $workRateNext['parentName'];
                        }
                        $result['count'] = $workRateNext['count'];
                        return $result;
                    }
                }
            }
            return $result;
        }
    }

    /**
     * ��ȡ�ϼ���������
     * @param $id
     * @return mixed
     */
    function getParentName($id) {
        $parentName = $this->findAll(array('id' => $id), null, 'parentName');
        return $parentName[0]['parentName'];
    }

    /**
     * ��ȡ�����ٷֱȺ�ID
     * @param $projectId
     * @param $parentId
     * @return array
     */
    function getIdWorkRate($projectId, $parentId) {
        $idWorkRateRow = $this->findAll(array('parentId' => $parentId, 'projectId' => $projectId), null, 'id,workRate');
        $idWorkRateRow = array_filter($idWorkRateRow);
        return $idWorkRateRow;
    }

    /**
     * ���ʱ�������ڵ���Ϣ����
     * @param $projectId
     * @return mixed
     */
    function initChangeInfo_d($projectId) {
        $sql = "update " . $this->tbl_name . " set changeLft = lft,changeRgt = rgt where projectId = " . $projectId;
        return $this->_db->query($sql);
    }

    /**
     * �ж��Ƿ��б������
     * @param $projectId
     * @param $isCreateNew
     * @return bool
     * @throws Exception
     */
    function isChanging_d($projectId, $isCreateNew) {
        //�ж��Ƿ��Ѿ����ڱ������
        $esmchangeDao = new model_engineering_change_esmchange();
        return $esmchangeDao->getChangeId_d($projectId, $isCreateNew);
    }

    /**
     * �����Ϣ��ȡ
     * @param $uid
     * @return bool|mixed
     */
    function getChange_d($uid) {
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        $changeObj = $esmchangeactDao->get_d($uid);

        //����һЩ��ǰ��������ֶ�
        $obj = $this->get_d($changeObj['activityId']);
        $changeObj['actBeginDate'] = $obj['actBeginDate'];
        $changeObj['actEndDate'] = $obj['actEndDate'];
        $changeObj['actDays'] = $obj['actDays'];
        $changeObj['workedDays'] = $obj['workedDays'];
        $changeObj['needDays'] = $obj['needDays'];
        $changeObj['process'] = $obj['process'];
        $changeObj['workloadDone'] = $obj['workloadDone'];
        $changeObj['orgId'] = $changeObj['activityId'];
        $changeObj['memberId'] = '';
        $changeObj['memberName'] = '';

        return $changeObj;
    }

    /**
     * ��ȡ����ĸ��ڵ�
     * @param $changeId
     * @return mixed
     */
    function getChangeRoot_d($changeId) {
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        return $esmchangeactDao->getChangeRoot_d($changeId);
    }

    /**
     * ��ȡ�ϼ�����
     * @param $id
     * @param $changeId
     * @return bool|mixed
     */
    function getParentObj_d($id, $changeId) {
        //�����������id�����϶�Ϊ���
        if ($changeId) {
            $esmchangeactDao = new model_engineering_change_esmchangeact();
            $obj = $esmchangeactDao->get_d($id);
            $obj['memberId'] = '';
            $obj['memberName'] = '';
            //��ʼ������
            if (empty($obj['budgetAll'])) {
                $obj['budgetAll'] = 0;
            }
        } else {
            $obj = $this->get_d($id);
        }
        return $obj;
    }

    /**
     * ����ϼƴ���
     * @param $parentId
     * @return mixed
     */
    function countForChange_d($parentId) {
        $sql = "select
            sum(if(c.parentId = $parentId ,c.workRate,0)) as workRate ,
            min(c.planBeginDate) as planBeginDate,max(c.planEndDate) as planEndDate,
            round((UNIX_TIMESTAMP( max(c.planEndDate) ) - UNIX_TIMESTAMP( min(c.planBeginDate) ) )/(3600 *24)) + 1 as days,
            sum(round(if(c.parentId = $parentId,c.process,0)*if(c.parentId = $parentId,c.workRate,0)/100,2)) as process,
            sum(c.workload) as workload
        from
            oa_esm_change_activity c
        where c.changeAction <> 'delete' and c.isRoot = 0";
        return $this->listBySql($sql);
    }

    /**
     * �������ʱ�����¼�������Ľ����Լ���Ŀ�Ľ���
     * @param $projectId
     * @return bool
     * @throws Exception
     */
    function recountProjectProcess_d($projectId) {
        try {
            //��ȡ��Ŀ�е�����
            $sql = "select id,parentId from " . $this->tbl_name . " where projectId = '$projectId' and rgt - lft = 1
                AND isTrial = 0";
            $rows = $this->_db->getArray($sql);

            //�ò�ѯ������Ҷ���������ϵݹ���Ŀ�Ľ���
            if ($rows) {
                foreach ($rows as $key => $val) {
                    //�����������
                    $myProcess = $this->getLeafProcess_d($val['id']);
                    $this->update(array('id' => $val['id']), array('process' => $myProcess));
                    //�����ϼ�����
                    $this->updateAllParent_d($val['id'], $val);
                }
            }

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ��ǰ�������
     * @param $id
     * @return int
     */
    function getLeafProcess_d($id) {
        $this->searchArr = array(
            'id' => $id
        );
        $rs = $this->list_d('count_process');
        if ($rs[0]['process']) {
            if ($rs[0]['process'] > 100)
                return 100;
            else
                return $rs[0]['process'];
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ��ʱ����
     * @param $projectId
     * @param null $beginDate
     * @param $endDate
     * @param bool $isAProject
     * @param bool $trialProcessDeal
     * @return int|string
     */
    function getActCountProcess_d($projectId, $beginDate = null, $endDate, $isAProject = false, $trialProcessDeal = false) {
        // ����
        $process = 0;
        // �����A����Ŀ��ȡԤ�ƽ���
        if ($isAProject) {
            $rs = $this->findAll(array('projectId' => $projectId), 'lft DESC', 'id,lft,rgt,isTrial,process,planBeginDate,planEndDate,days,confirmDays,parentId,workRate');
            foreach ($rs as $k => $v) {
                if ($v['rgt'] - $v['lft'] != 1) continue; // �������������,ֱ������
                if ($v['isTrial'] && !$trialProcessDeal) { // ���������PK������Ĭ��Ϊ100
                    $rs[$k]['process'] = 100;
                    continue;
                }
                if ($endDate < $v['planBeginDate']) { // �����������������ʼ֮ǰ����ô������Ϊ0
                    $rs[$k]['process'] = 0;
                    continue;
                }
                if ($beginDate > $v['planEndDate']) { // �����ʼ�����������������֮����ô����Ϊ100
                    $rs[$k]['process'] = 0;
                    continue;
                }
                $calBeginDate = $beginDate ? max($beginDate, $v['planBeginDate']) : $v['planBeginDate']; // Ԥ�ƿ�ʼ����
                $calEndDate = min($endDate, $v['planEndDate']); // Ԥ�ƽ�������
                $rs[$k]['calDays'] = (strtotime($calEndDate) - strtotime($calBeginDate)) / 86400 + 1 + $v['confirmDays'];
                $rs[$k]['process'] = bcmul(bcdiv($rs[$k]['calDays'], $v['days'], 6), 100, 6);
            }
        } else {
            $timeSql = " and executionDate <= '$endDate'"; // ���ؽ�������
            if ($beginDate) $timeSql .= " and executionDate >= '$beginDate'"; // ���ؿ�ʼ����
            $sql = "select
				c.id,c.parentId,c.lft,c.rgt,c.workRate,c.isTrial,
				if(w.workloadDay > c.workload,1,w.workloadDay/c.workload)*100 as process
			from
				oa_esm_project_activity c
				left join
				(
					select sum(workloadDay) as workloadDay,activityId
					from oa_esm_worklog where projectId = '$projectId' and confirmStatus = 1 $timeSql group by activityId
				) w
				on c.id = w.activityId
			where projectId = '$projectId' order by c.lft desc";
            $rs = $this->_db->getArray($sql);
            foreach ($rs as $k => $v) {
                if ($v['isTrial']) { // ���������PK������Ĭ��Ϊ100
                    if ($trialProcessDeal) {
                        $calBeginDate = $beginDate ? max($beginDate, $v['planBeginDate']) : $v['planBeginDate']; // Ԥ�ƿ�ʼ����
                        $calEndDate = min($endDate, $v['planEndDate']); // Ԥ�ƽ�������
                        $rs[$k]['calDays'] = (strtotime($calEndDate) - strtotime($calBeginDate)) / 86400 + 1;
                        $rs[$k]['process'] = bcmul(bcdiv($rs[$k]['calDays'], $v['days'], 6), 100, 6);
                    } else {
                        $rs[$k]['process'] = 100;
                    }
                }
            }
        }
        if ($rs) {
            $processCache = array(); // ���Ȼ���
            foreach ($rs as $v) {
                $activityProcess = empty($v['process']) ? 0 : $v['process']; # �˴��������Ϊ�յ����
                $activityProcess = $activityProcess > 100 ? 100 : $activityProcess; # �˴������ȴ���100�Ľ�������Ϊ100
                if ($v['parentId'] == -1) {
                    // �������
                    $process = $v['rgt'] - $v['lft'] == 1 ? bcadd($process, bcmul(bcdiv($v['workRate'], 100, 6), $activityProcess, 6), 6) :
                        bcadd($process, bcmul(bcdiv($v['workRate'], 100, 6), $processCache[$v['id']], 6), 6);
                } else {
                    if ($v['rgt'] - $v['lft'] == 1) {
                        // ���ȳ�ʼ��
                        $processCache[$v['parentId']] = isset($processCache[$v['parentId']]) ?
                            bcadd($processCache[$v['parentId']], bcmul(bcdiv($v['workRate'], 100, 6), $activityProcess, 6), 6) :
                            bcmul(bcdiv($v['workRate'], 100, 6), $activityProcess, 6);
                    } else {
                        // ���ȳ�ʼ��
                        $processCache[$v['parentId']] = $processCache[$v['id']];
                    }
                }
            }
        }
        if ($process > 100) $process = 100;
        if ($process < 0) $process = 0;
        return $process;
    }

    /**
     * ��Ŀ���Ȼ�ȡ - ������
     * @param $projectInfo
     * @param null $beginDate
     * @param $endDate
     * @return int
     */
    function getActFinanceProcess_d($projectInfo, $beginDate = null, $endDate) {
        // ��ͬ���
        $contractMoney = $projectInfo['contractMoney'];

        // �ۿ���
        $sql = "SELECT SUM(deductMoney) AS deductMoney FROM oa_contract_deduct WHERE state = 1
            AND contractId = '" . $projectInfo['contractId'] . "' AND TO_DAYS(ExaDT) <= TO_DAYS('" . $endDate . "') ";
        if ($beginDate) {
            $sql .= "  AND TO_DAYS(ExaDT) >= TO_DAYS('" . $beginDate . "') ";
        }
        $deductRow = $this->_db->get_one($sql);
        $deductMoney = $deductRow['deductMoney'] === null ? 0 : $deductRow['deductMoney'];

        // ���˽��
        if ($projectInfo['incomeType'] == 'SRQRFS-02') {
            $sql = "SELECT SUM(IF(isRed = 0,invoiceMoney,-invoiceMoney)) AS inMoney FROM oa_finance_invoice WHERE objType = 'KPRK-12'
            AND objId = '" . $projectInfo['contractId'] . "' AND TO_DAYS(invoiceTime) <= TO_DAYS('" . $endDate . "') ";
            if ($beginDate) {
                $sql .= "  AND TO_DAYS(invoiceTime) >= TO_DAYS('" . $beginDate . "') ";
            }
        } else {
            $sql = "SELECT SUM(IF(formType = 'YFLX-TKD',-a.money,a.money)) AS inMoney
                FROM oa_finance_income c LEFT JOIN oa_finance_income_allot a ON c.id = a.incomeId
                WHERE a.objType = 'KPRK-12' AND a.objId = '" . $projectInfo['contractId'] . "'
                    AND TO_DAYS(a.allotDate) <= TO_DAYS('" . $endDate . "') ";
            if ($beginDate) {
                $sql .= "  AND TO_DAYS(a.allotDate) >= TO_DAYS('" . $beginDate . "') ";
            }
        }
        $inMoneyRow = $this->_db->get_one($sql);
        $inMoney = $inMoneyRow['inMoney'] === null ? 0 : $inMoneyRow['inMoney'];

        // �����ȼ���
        $process = round(bcmul(bcdiv($inMoney, bcsub($contractMoney, $deductMoney, 9), 9), 100, 9), 5);

        if ($process > 100) $process = 100;
        if ($process < 0) $process = 0;
        return $process;
    }

    /**
     * ���ݴ�����������id,��ȡ��������Ŀ�е�ռ��
     * @param $id
     * @return string
     */
    function getWorkRate_d($id) {
        $arr = $this->find(array('id' => $id));
        $workRate = bcdiv($arr['workRate'], 100, 4);
        if ($arr['parentId'] != -1) {
            return bcmul($workRate, $this->getWorkRate_d($arr['parentId']), 4);
        } else {
            return $workRate;
        }
    }

    /**
     * ����Ԥ�ƿ�ʼ�ͽ�������
     * @param $projectId
     * @return bool
     */
    function updatePlanDate_d($projectId) {
        // ȡ��ǰ��С��ʼ���ں�����������
        $this->searchArr = array('projectId' => $projectId);
        $objArr = $this->listBySqlId('count_list');

        // ���µ���Ŀ��
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->update(
            array('id' => $projectId),
            array('planBeginDate' => $objArr[0]['planBeginDate'], 'planEndDate' => $objArr[0]['planEndDate'],
                'expectedDuration' => (strtotime($objArr[0]['planEndDate']) - strtotime($objArr[0]['planBeginDate'])) / 86400 + 1
            )
        );
    }

    /**
     * ���������������Ϣ
     * @param $projectId
     * @return mixed
     */
    function updateTriActivity_d($projectId) {
        //����������Ŀ��Ϣ
        return $this->_db->query("update oa_esm_project_activity a left join oa_esm_project p on a.triProjectId = p.id
                set a.planBeginDate = p.planBeginDate,a.planEndDate = p.planEndDate,a.days = p.expectedDuration
            where a.projectId = " . $projectId ." and a.isTrial = 1");
    }

    /**
     * ����A����Ŀ���� - �˷���������Ŀ����ҳ���Լ���ʱ���������Ŀ����
     * @param $param
     * @return bool
     */
    function updateCategoryAProcess_d($param,$updateActivityOnly = false) {
        // ��ĿId��ʼ��
        $projectId = is_array($param) ? $param['id'] : $param;

        //���������������Ŀ����
        $this->updateTriActivity_d($projectId);

        $esmprojectDao = new model_engineering_project_esmproject();
        if (!$esmprojectDao->isCategoryAProject_d(null, $projectId)) return false; //��A����Ŀֱ�ӷ���
        if (!$esmprojectDao->isDoing_d($projectId)) return true; // ���ڽ���Ŀֱ�ӷ���

        //��ȡ��������
        $activityArray = $this->findAll(
            array('projectId' => $projectId, 'isTrial' => 0), 'lft DESC',
            'id,parentId,workRate,process,lft,rgt,status,planEndDate,planBeginDate,confirmDays,days'
        );
        // ��Ҫ���½��ȵ��ϼ�
        $parentActivity = array();
        foreach ($activityArray as $v) {
            if ($v['rgt'] - $v['lft'] != 1) continue;     // �������������,��������
            if ($v['status'] == 2) continue;              // �������ͣ״̬�����񣬲�������
            if (min($v['planBeginDate'], day_date) == day_date) continue; // ��û�п�ʼ�����񣬲��������
            //��������������
            $workloadDone = (strtotime(min(day_date, $v['planEndDate'])) - strtotime($v['planBeginDate'])) / 86400 + 1 + $v['confirmDays'];
            $process = bcmul(bcdiv($workloadDone, $v['days'], 6), 100, 4);
            $updateArr = array(
                'workloadDone' => $workloadDone,
                'workedDays' => $workloadDone,
                'process' => $process > 100 ? 100 : $process
            );
            $this->update(array('id' => $v['id']), $updateArr);

            if ($v['parentId'] <> -1 && !in_array($v['parentId'], $parentActivity)) array_push($parentActivity, $v['parentId']); // ���ϼ����浽������
        }

        if(!$updateActivityOnly){
            // �����ϼ�����
            if (!empty($parentActivity)) $this->autoCalProcess_d($projectId, $parentActivity);

            // ƥ��������Ŀ���������ֱ�Ӽƻ���Ŀ����
            if ($esmprojectDao->isAllOutsourcingProject_d($projectId)) {
                $esmprojectDao->updateProjectProcess_d($projectId,
                    $this->getActCountProcess_d($projectId, null, day_date, true, true));
            }
        }

        return true;
    }

    /**
     * �Զ��������
     * @param $projectId
     * @param $parentActivity
     * @return bool
     */
    function autoCalProcess_d($projectId, $parentActivity) {
        $parentIds = implode(',', $parentActivity);
        $this->_db->query("UPDATE
            oa_esm_project_activity a
            LEFT JOIN
            (
                SELECT
                    parentId,SUM(workRate*process/100) AS processCount
                FROM
                    oa_esm_project_activity
                WHERE
                    projectId = $projectId AND parentId IN ($parentIds)
                GROUP BY
                    parentId
            ) aa
            ON a.id = aa.parentId
            SET a.process = aa.processCount
            WHERE a.projectId = $projectId AND a.id IN ($parentIds)");
        //�ٲ�ѯ����û���ϼ�
        $rs = $this->_db->getArray("SELECT parentId FROM oa_esm_project_activity WHERE id IN ($parentIds)");
        if ($rs) {
            $parentActivity = array();
            foreach ($rs as $v) {
                if ($v['parentId'] != -1) array_push($parentActivity, $v['parentId']);
            }
            return empty($parentActivity) ? true : $this->autoCalProcess_d($projectId, $parentActivity);
        } else {
            return true;
        }
    }

    /**
     * ��ͣ����
     * @param $id
     * @return bool
     */
    function stop_d($id) {
        return $this->update(array('id' => $id), array('status' => 2, 'stopDate' => day_date));
    }

    /**
     * �ָ�����
     * @param $id
     * @return bool
     */
    function restart_d($id) {
        return $this->update(array('id' => $id), array('status' => 0, 'stopDate' => '0000-00-00'));
    }

    /**
     * ��ȡ����д��־������
     * @param $projectId
     * @param $activityName
     * @return mixed
     */
    function getCanLogTask_d($projectId, $activityName) {
        $rs = $this->findAll(array('projectId' => $projectId, 'isTrial' => 0, 'activityName' => $activityName),
            null,
            'id,workloadUnit,planEndDate,lft,rgt');
        if ($rs) {
            foreach ($rs as $k => $v) {
                if ($v['rgt'] - $v['lft'] != 1) unset($rs[$k]);
            }
        }
        return $rs;
    }
}