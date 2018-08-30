<?php

/**
 * @author Show
 * @Date 2011��12��14�� ������ 10:01:27
 * @version 1.0
 * @description:������־(oa_esm_worklog) Model�� ÿ�ս�չ
 */
class model_engineering_worklog_esmworklog extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_worklog";
        $this->sql_map = "engineering/worklog/esmworklogSql.php";
        parent::__construct();
    }

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'workloadUnit'
    );

    //Ĭ�Ϲ�������λ
    public $workloadUnitDefault = 'GCGZLDW-00';

    //��˽��ֵ����
    public $assessResultArr = array(
        '0' => '',
        '1' => '��',
        '2' => '��',
        '3' => '��',
        '4' => '��',
        '5' => '���'
    );

    //��һ�����󻺴� -- ��Ϊ�����ʱ��ķѵ�����̫�࣬����Ҫ�Ż�
    static $initObjCache = array();

    //��ȡ���󻺴�ķ���
    static function getObjCache($className)
    {
        if (empty(self::$initObjCache[$className])) {
            self::$initObjCache[$className] = new $className();
        }
        return self::$initObjCache[$className];
    }

    /******************** ��ɾ�Ĳ�*****************************/

    /**
     * ��������
     * @param $object
     * @return bool|void
     * @throws $e
     */
    function add_d($object)
    {
        # �������Ĺ���վ�ȴ���100,����ʧ��
        if ($object['inWorkRate'] > 100) return false;
        # ������ڸ�ʽ���󣬷���
        if ($object['executionDate'] == '0000-00-00') return false;

        # �����������,����ʧ��
        $condition = array('executionDate' => $object['executionDate'], 'createId' => $_SESSION['USER_ID'],
            'activityId' => $object['activityId']);
        if ($this->find($condition, null, 'id')) return false;

        //ȡ������Ϣ������
        if (isset($object['esmcostdetail'])) {
            $esmcostdetail = $object['esmcostdetail'];
            unset($object['esmcostdetail']);
        }

        try {
            $this->start_d();
            //�ж�ʱ������Ƿ�����ܱ������򷵻��ܱ�id,���������ܱ�Ȼ�󷵻�id
            $weeklogDao = self::getObjCache('model_engineering_worklog_esmweeklog');
            $weeklogId = $weeklogDao->checkIsWeeklog($object);

            //��ѯ��ִ������֮���Ƿ������־��������ʾ -- ��������ʾ��������
            if (!empty($object['activityId']) && $this->hasBelowLog_d($object['executionDate'], $object['activityId'])) {
                $object['workProcess'] = -1;
            }

            //������־����
            $object ['weekId'] = $weeklogId;
            $object ['status'] = 'WTJ';
            //���Ӳ���
            $object['deptName'] = $_SESSION['DEPT_NAME'];
            $object['deptId'] = $_SESSION['DEPT_ID'];
            $object['executionTimes'] = strtotime($object['executionDate']);

            $object = $this->processDatadict($object);
            $workLogId = parent::add_d($object, true);

            //������з���
            if (isset($esmcostdetail)) {
                $esmcostdetail['worklog'] = array(
                    'worklogId' => $workLogId,
                    'activityId' => $object['activityId'],
                    'activityName' => $object['activityName'],
                    'projectId' => $object['projectId'],
                    'projectCode' => $object['projectCode'],
                    'projectName' => $object['projectName'],
                    'executionDate' => $object['executionDate']
                );
                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                $esmcostdetailDao->addBatch_d($esmcostdetail);
            }

            //���¹�����Ϣ
            $this->updateSourceInfo_d($object);

            $this->commit_d();
            return $workLogId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ��дedit_d����
     * @param $object
     * @param null $invoiceStatus
     * @return bool
     */
    function edit_d($object, $invoiceStatus = null)
    {
        # �������������100������
        if ($object['inWorkRate'] > 100) return false;
        # ������ڸ�ʽ���󣬷���
        if ($object['executionDate'] == '0000-00-00') return false;

        //ȡ������Ϣ������
        if (isset($object['esmcostdetail'])) {
            $esmcostdetail = $object['esmcostdetail'];
            unset($object['esmcostdetail']);
        }
        //�ж��Ƿ�Ϊexcel���������־
        if (isset($object['isExcel'])) {
            $isExcel = $object['isExcel'];
            unset($object['isExcel']);
        }

        try {
            $this->start_d();
            //�༭
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //������з���
            if (isset($esmcostdetail)) {
                $esmcostdetail['worklog'] = array(
                    'worklogId' => $object['id'],
                    'activityId' => $object['activityId'],
                    'activityName' => $object['activityName'],
                    'projectId' => $object['projectId'],
                    'projectCode' => $object['projectCode'],
                    'projectName' => $object['projectName'],
                    'executionDate' => $object['executionDate']
                );
                if (isset($isExcel) && $isExcel == 1) {
                    $esmcostdetail['worklog']['isExcel'] = 1;
                    $esmcostdetail['worklog']['costMoney'] = $object['costMoney'];
                    unset($object['costMoney']);
                }
                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                $idsArr = $esmcostdetailDao->editBatch_d($esmcostdetail, $invoiceStatus);
            }

            //���¹�����Ϣ
            $this->updateSourceInfo_d($object);

            $this->commit_d();
            if (isset($idsArr) && $idsArr) {
                return $idsArr;
            }
            return $object['id'];
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ҵ��edit_d
     * @param $object
     * @return bool
     * @throws Exception
     */
    function editOrg_d($object)
    {
        try {
            //�༭
            $object = $this->processDatadict($object);
            return parent::edit_d($object, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ɾ������
     * @param $id
     * @return bool
     */
    function deletes_d($id)
    {
        try {
            $this->start_d();

            $object = $this->get_d($id);

            $this->deletes($id);

            //����ҵ����Ϣ
            $this->updateSourceInfo_d($object);

            //������Ա����Ŀ����
            if ($object['projectId']) {
                //��ȡ��ǰ��Ŀ�ķ���
                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId']);

                //������Ա������Ϣ
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $object['projectId'], 'memberId' => $_SESSION['USER_ID']),
                    $projectCountArr
                );
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��������
     * @param $object
     * @return bool
     */
    function batchAdd_d($object)
    {
        # ����У��
        if (strtotime($object['executionDate']) > strtotime(date('Y-m-d'))) {
            return '��־��δ����дʱ�䣬���Ժ����ԣ�';
        }

        try {
            $this->start_d();

            $executionTimes = strtotime($object['executionDate']);

            //�����ֶΣ�Ȼ������
            $object['detail'] = util_arrayUtil::setArrayFn(array(
                'executionDate' => $object['executionDate'],
                'executionTimes' => $executionTimes,
                'workStatus' => $object['workStatus']
            ), $object['detail']);

            //����
            $this->saveDelBatch($object['detail']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * ������־�ķ�����Ϣ
     * @param $id
     * @param $costMoney
     * @return bool
     * @throws Exception
     */
    function updateCostMoney_d($id, $costMoney)
    {
        //�����ж�
        if (!is_array($costMoney)) {
            $costMoney = array('costMoney' => $costMoney);
        }

        try {
            $this->update(array('id' => $id), $costMoney);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ������־״̬
     * @param $weekId
     * @param string $status
     * @param null $confirmStatus
     * @return bool
     * @throws Exception
     */
    function updateStatus_d($weekId, $status = 'YTJ', $confirmStatus = null)
    {
        try {
            //�ύ����
            $conditionArr = array('weekId' => $weekId);

            $updateArr = array('status' => $status);
            $updateArr = $this->addUpdateInfo($updateArr);

            if ($confirmStatus !== null) {
                $updateArr['confirmStatus'] = $confirmStatus;
            }

            $this->update($conditionArr, $updateArr);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /********************** 2013-7-3���������� ***********************/
    /**
     * ��־���
     * @param $id
     * @param $assessResult
     * @param $feedBack
     * @return bool
     */
    function auditLog_d($id, $assessResult, $feedBack)
    {
        //��ȡ��ǰ��־
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            //���ؿ��˽����������ϵ��
            $assessResultDao = self::getObjCache('model_engineering_assess_esmassresult');
            $assessResultScore = $assessResultDao->getValScore_d($assessResult);

            //��־����
            $object = array(
                'id' => $id,
                'assessResult' => $assessResult,
                'assessResultName' => $this->assessResultArr[$assessResult],
                'feedBack' => $feedBack,
                'confirmDate' => date('Y-m-d H:i:s'),
                'confirmMoney' => $obj['costMoney'],
                'confirmName' => $_SESSION['USERNAME'],
                'confirmId' => $_SESSION['USER_ID'],
                'confirmStatus' => 1,
                'processCoefficient' => bcmul($assessResultScore, $obj['thisProjectProcess'], 2)
            );

            //���������Ŀ����,ֱ�Ӽ��㹤��ϵ��,��������Ŀ�ܱ��������ټ���
            $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
            if (!$esmmemberDao->isManager_d($obj['projectId'], $obj['createId'])) {
                $object['workCoefficient'] = bcdiv(bcmul($assessResultScore, $obj['inWorkRate'], 4), 100, 2);
            } else {
                //�����Ӧ����Ŀ�ܱ��Ѿ�������,����µ�ǰ��־�Ĺ���ϵ��
                if ($obj['projectId']) {
                    $statusreportDao = self::getObjCache('model_engineering_project_statusreport');
                    $score = $statusreportDao->checkLogScored_d($obj['projectId'], $obj['executionDate']);
                    if ($score !== false) {
                        $object['workCoefficient'] = bcdiv(bcmul($score, $obj['inWorkRate'], 4), 1000, 2);
                    }
                }
            }

            $object = $this->addUpdateInfo($object);
            $this->updateById($object);

            //������־�µķ���״̬
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
            $esmcostdetailDao->auditFee_d($id);

            //ȷ���������
            if ($obj['activityId']) {
                //������Ŀ�����жϣ������A�࣬�򲻸����������
                $esmporjectDao = self::getObjCache('model_engineering_project_esmproject');
                if (!$esmporjectDao->isCategoryAProject_d(null, $obj['projectId'])) {
                    //��Ŀ��Χ����
                    $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

                    //��ȡ�������־ͳ����Ϣ
                    $countInfo = $this->getWorklogCountInfo_d($obj['activityId'], null, true);

                    //����������־��Ϣ edit by kuangzw on 2013-07-04 ��ͣʹ�ã�����־��˹�����˳�ͻ
                    $process = $activityDao->updateTaskProcess_d($obj['activityId'], $countInfo);

                    if ($process == 100) {
                        //��ȡ��������
                        $activityArr = $activityDao->find(array('id' => $obj['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                        //��������ʵ�ʽ�������
                        $activityDao->updateEndDate_d($obj['activityId'], $activityArr['actBeginDate'], $obj['executionDate']);
                    }
                }
            }

            //������Ա����Ŀ����
            if ($obj['projectId']) {
                //��ȡ��ǰ��Ŀ�ķ���
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($obj['projectId'], $obj['createId']);

                //������Ա������Ϣ
                $esmmemberDao->update(
                    array('projectId' => $obj['projectId'], 'memberId' => $obj['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �����־
     * @param $id
     * @param $assessResult
     * @param $feedBack
     * @return bool
     */
    function backLog_d($id, $assessResult, $feedBack)
    {
        //��ȡ��ǰ��־
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            //��־����
            $object = array(
                'id' => $id,
                'assessResult' => $assessResult,
                'assessResultName' => $this->assessResultArr[$assessResult],
                'feedBack' => $feedBack,
                'confirmDate' => date('Y-m-d H:i:s'),
                'backMoney' => $obj['costMoney'],
                'confirmName' => $_SESSION['USERNAME'],
                'confirmId' => $_SESSION['USER_ID'],
                'confirmStatus' => 0
            );
            $object = $this->addUpdateInfo($object);
            $this->updateById($object);

            //������־�µķ���״̬
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
            //����Ǵ�أ������ز���
            $esmcostdetailDao->auditFee_d($id, 1);

            //������Ա����Ŀ����
            if ($obj['projectId']) {
                //��ȡ��ǰ��Ŀ�ķ���
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($obj['projectId'], $obj['createId']);

                //������Ա������Ϣ
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $obj['projectId'], 'memberId' => $obj['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �������־
     * @param $id
     * @return bool|string
     */
    function unauditLog_d($id)
    {
        //��ȡ��ǰ��־
        $obj = $this->get_d($id);
        try {
            $this->start_d();

            //��־����
            $object = array(
                'id' => $id,
                'assessResult' => 0,
                'assessResultName' => '',
                'feedBack' => '',
                'confirmDate' => '0000-00-00 00:00:00',
                'confirmMoney' => 0,
                'confirmName' => '',
                'confirmId' => '',
                'confirmStatus' => 0,
                'processCoefficient' => 0,
                'workCoefficient' => 0
            );
            $object = $this->addUpdateInfo($object);
            $this->updateById($object);

            //������־�µķ���״̬
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
            $esmcostdetailDao->unauditFee_d($id);

            //���¼����������
            if ($obj['activityId']) {
                //������Ŀ�����жϣ������A�࣬�򲻸����������
                $esmporjectDao = self::getObjCache('model_engineering_project_esmproject');
                if (!$esmporjectDao->isCategoryAProject_d(null, $obj['projectId'])) {
                    //��Ŀ��Χ����
                    $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

                    //��ȡ�������־ͳ����Ϣ
                    $countInfo = $this->getWorklogCountInfo_d($obj['activityId'], null, true);

                    //����������־��Ϣ edit by kuangzw on 2013-07-04 ��ͣʹ�ã�����־��˹�����˳�ͻ
                    $process = $activityDao->updateTaskProcess_d($obj['activityId'], $countInfo);

                    if ($process == 100) {
                        //��ȡ��������
                        $activityArr = $activityDao->find(array('id' => $obj['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                        //��������ʵ�ʽ�������
                        $activityDao->updateEndDate_d($obj['activityId'], $activityArr['actBeginDate'], $obj['executionDate']);
                    }
                }
            }

            //���¼�����Ŀ����
            if ($obj['projectId']) {
                //��ȡ��ǰ��Ŀ�ķ���
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($obj['projectId'], $obj['createId']);

                //������Ա������Ϣ
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $obj['projectId'], 'memberId' => $obj['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return $e->getMessage();
        }
    }

    /**
     * ��־��� - ����
     * @param $object
     * @return bool
     */
    function auditLogForPerson_d($object)
    {
        //ʵ������Ա
        $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
        try {
            $this->start_d();

            //���ؿ��˽����������ϵ��
            $assessResultDao = self::getObjCache('model_engineering_assess_esmassresult');
            $assessResultScore = $assessResultDao->getValScore_d($object['assessResults']);

            //��ѯҪ��˵���־
            $objArr = $this->findAll(array(
                'projectId' => $object['projectId'],
                'activityId' => $object['activityId'],
                'createId' => $object['createId'],
                'confirmStatus' => $object['confirmStatus']
            ), null, 'id,costMoney,thisProjectProcess,inWorkRate,executionDate');

            //������־�µķ���״̬
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');

            //����в鵽��־����ѭ���������
            if ($objArr) {
                //���������Ŀ����,ֱ�Ӽ��㹤��ϵ��,��������Ŀ�ܱ��������ټ���
                $isManager = $esmmemberDao->isManager_d($object['projectId'], $object['createId']) ? true : false;

                # ��¼���ʱ��
                $thisTime = date('Y-m-d H:i:s');
                $maxDate = '';

                $statusreportDao = self::getObjCache('model_engineering_project_statusreport');

                foreach ($objArr as $val) {
                    //��־����
                    $newObj = array(
                        'id' => $val['id'],
                        'assessResult' => $object['assessResults'],
                        'assessResultName' => $this->assessResultArr[$object['assessResults']],
                        'confirmDate' => $thisTime,
                        'confirmMoney' => $val['costMoney'],
                        'confirmName' => $_SESSION['USERNAME'],
                        'confirmId' => $_SESSION['USER_ID'],
                        'confirmStatus' => 1,
                        'processCoefficient' => bcmul($assessResultScore, $val['thisProjectProcess'], 2)
                    );

                    //���������Ŀ����,��ʱ����
                    if (!$isManager) {
                        $newObj['workCoefficient'] = bcdiv(bcmul($assessResultScore, $val['inWorkRate'], 4), 100, 2);
                    } else {
                        if ($object['projectId']) {
                            //�����Ӧ����Ŀ�ܱ��Ѿ�������,����µ�ǰ��־�Ĺ���ϵ��
                            $score = $statusreportDao->checkLogScored_d($object['projectId'], $val['executionDate']);
                            if ($score !== false) {
                                $newObj['workCoefficient'] = bcdiv(bcmul($score, $val['inWorkRate'], 4), 1000, 2);
                            }
                        }
                    }

                    $newObj = $this->addUpdateInfo($newObj);
                    $this->updateById($newObj);

                    $esmcostdetailDao->auditFee_d($val['id']);

                    $maxDate = max($maxDate, $val['executionDate']);
                }
            }

            //ȷ���������
            if ($object['activityId'] && $objArr) {
                //������Ŀ�����жϣ������A�࣬�򲻸����������
                $esmporjectDao = self::getObjCache('model_engineering_project_esmproject');
                if (!$esmporjectDao->isCategoryAProject_d(null, $object['projectId'])) {
                    //��Ŀ��Χ����
                    $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

                    //��ȡ�������־ͳ����Ϣ
                    $countInfo = $this->getWorklogCountInfo_d($object['activityId'], null, true);

                    //����������־��Ϣ edit by kuangzw on 2013-07-04
                    $process = $activityDao->updateTaskProcess_d($object['activityId'], $countInfo);

                    if ($process == 100) {
                        //��ȡ��������
                        $activityArr = $activityDao->find(array('id' => $object['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                        //��������ʵ�ʽ�������
                        $activityDao->updateEndDate_d($object['activityId'], $activityArr['actBeginDate'], $maxDate);
                    }
                }
            }

            //������Ա����Ŀ����
            if ($object['projectId'] && $objArr) {
                //��ȡ��ǰ��Ŀ�ķ���
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['createId']);

                //������Ա������Ϣ
                $esmmemberDao->update(
                    array('projectId' => $object['projectId'], 'memberId' => $object['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���������־
     * @param $object
     * @return bool
     */
    function backLogForPerson_d($object)
    {
        try {
            $this->start_d();

            //��ѯҪ��˵���־
            $objArr = $this->findAll(array(
                'projectId' => $object['projectId'],
                'activityId' => $object['activityId'],
                'createId' => $object['createId'],
                'confirmStatus' => $object['confirmStatus']
            ), null, 'id,costMoney,thisProjectProcess,inWorkRate,executionDate');

            //������־�µķ���״̬
            $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');

            //����в鵽��־����ѭ���������
            if ($objArr) {
                foreach ($objArr as $val) {
                    //��־����
                    $newObj = array(
                        'id' => $val['id'],
                        'assessResult' => $object['assessResults'],
                        'assessResultName' => $this->assessResultArr[$object['assessResults']],
                        'confirmDate' => date('Y-m-d H:i:s'),
                        'confirmName' => $_SESSION['USERNAME'],
                        'confirmId' => $_SESSION['USER_ID'],
                        'backMoney' => $val['costMoney']
                    );

                    $newObj = $this->addUpdateInfo($newObj);
                    $this->updateById($newObj);
                    $esmcostdetailDao->auditFee_d($val['id'], 1);
                }
            }

            //������Ա����Ŀ����
            if ($object['projectId'] && $objArr) {
                //��ȡ��ǰ��Ŀ�ķ���
                $projectCountArr = $esmcostdetailDao->getCostFormMember_d($object['projectId'], $object['createId']);

                //������Ա������Ϣ
                $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');
                $esmmemberDao->update(
                    array('projectId' => $object['projectId'], 'memberId' => $object['createId']),
                    $projectCountArr
                );
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /************************ ҵ���߼����� *************************/
    /**
     * ���� ��־ �ϼ�ҵ����Ϣ
     * @param $object
     * @throws Exception
     * @return true
     */
    function updateSourceInfo_d($object)
    {
        //��Ŀ��Χ����
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');

        //ʵ������Ŀ����
        $esmprojectDao = self::getObjCache('model_engineering_project_esmproject');

        //ʵ������Ŀ��Ա
        $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');

        try {
            $this->start_d();

            /*************** ���񲿷�ҵ����� ********************/
            if ($object['activityId']) {
                //��ȡ��������
                $activityArr = $activityDao->find(array('id' => $object['activityId']), null, 'id,activityName,actBeginDate,planEndDate');

                //------------��������ʼ����
                if ($activityArr['actBeginDate'] == '0000-00-00' || empty($activityArr['actBeginDate']) || strtotime($activityArr['actBeginDate']) > strtotime($object['executionDate'])) {
                    $activityDao->update(array('id' => $object['activityId']), array('actBeginDate' => $object['executionDate']));
                }
            }

            /***************** ��Ŀ�������ݸ��� ******************/
            if ($object['projectId']) {
                //��ȡ����-������־��ͳ����Ϣ
                $thisCount = $this->getProjectMemberCountInfo_d($object['projectId'], $_SESSION['USER_ID']);
                //���������Ա������
                $esmmemberDao->updateDayInfo_d($object['projectId'], $_SESSION['USER_ID'], $thisCount['inWorkDay']);

                /*------------ ��Ŀ��Ա���²��� -----------------*/
                $esmmemberArr = $esmmemberDao->find(array('projectId' => $object['projectId'], 'memberId' => $_SESSION['USER_ID']), null, 'beginDate');
                if ($esmmemberArr['beginDate'] == '0000-00-00' || empty($esmmemberArr['beginDate']) || strtotime($esmmemberArr['beginDate']) > strtotime($object['executionDate'])) {
                    $esmmemberDao->update(
                        array('projectId' => $object['projectId'], 'memberId' => $_SESSION['USER_ID']),
                        array('beginDate' => $object['executionDate'])
                    );
                }

                //��ȡ��Ŀ����
                $projectArr = $esmprojectDao->find(array('id' => $object['projectId']), null, 'id,projectName,projectCode,planEndDate,actBeginDate');

                //------------������Ŀ��ʼ����
                if ($projectArr['actBeginDate'] == '0000-00-00' || empty($projectArr['actBeginDate']) || strtotime($projectArr['actBeginDate']) > strtotime($object['executionDate'])) {
                    $esmprojectDao->update(array('id' => $object['projectId']), array('actBeginDate' => $object['executionDate']));
                }

                //��ȡ��Ŀ���ܷ���
                $projectPersonFee = $esmmemberDao->getFeePerson_d($object['projectId']);
                $esmprojectDao->updateFeePerson_d($object['projectId'], $projectPersonFee);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ���� ��־ �ϼ�ҵ����Ϣ
     * @param $esmprojectDao
     * @param $activityDao
     * @param $esmmemberDao
     * @param $projectId
     * @param $activityId
     * @param $createId
     * @param $executionDate
     * @return bool
     * @throws Exception
     */
    function updateLogSource_d($esmprojectDao, $activityDao, $esmmemberDao, $projectId,
                               $activityId, $createId, $executionDate)
    {

        try {
            $this->start_d();

            /*************** ���񲿷�ҵ����� ********************/
            if ($activityId) {
                //��ȡ��������
                $activityArr = $activityDao->find(array('id' => $activityId), null, 'id,activityName,actBeginDate,planEndDate');

                //------------��������ʼ����
                if ($activityArr['actBeginDate'] == '0000-00-00' || empty($activityArr['actBeginDate']) || strtotime($activityArr['actBeginDate']) > strtotime($executionDate)) {
                    $activityDao->update(array('id' => $activityId), array('actBeginDate' => $executionDate));
                }
            }

            /***************** ��Ŀ�������ݸ��� ******************/
            if ($projectId) {
                //��ȡ����-������־��ͳ����Ϣ
                $thisCount = $this->getProjectMemberCountInfo_d($projectId, $createId);
                //���������Ա������
                $esmmemberDao->updateDayInfo_d($projectId, $_SESSION['USER_ID'], $thisCount['inWorkDay']);

                /*------------ ��Ŀ��Ա���²��� -----------------*/
                $esmmemberArr = $esmmemberDao->find(array('projectId' => $projectId, 'memberId' => $createId), null, 'beginDate');
                if ($esmmemberArr['beginDate'] == '0000-00-00' || empty($esmmemberArr['beginDate']) || strtotime($esmmemberArr['beginDate']) > strtotime($executionDate)) {
                    $esmmemberDao->update(
                        array('projectId' => $projectId, 'memberId' => $_SESSION['USER_ID']),
                        array('beginDate' => $executionDate)
                    );
                }

                //��ȡ��Ŀ����
                $projectArr = $esmprojectDao->find(array('id' => $projectId), null, 'id,projectName,projectCode,planEndDate,actBeginDate');

                //------------������Ŀ��ʼ����
                if ($projectArr['actBeginDate'] == '0000-00-00' || empty($projectArr['actBeginDate']) || strtotime($projectArr['actBeginDate']) > strtotime($executionDate)) {
                    $esmprojectDao->update(array('id' => $projectId), array('actBeginDate' => $executionDate));
                }

                //��ȡ��Ŀ���ܷ���
                $projectPersonFee = $esmmemberDao->getFeePerson_d($projectId);
                $esmprojectDao->updateFeePerson_d($projectId, $projectPersonFee);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ��ȡ��һ�δ�����־����Ϣ
     * @return array|bool|mixed
     */
    function getLastInfo_d()
    {
        $sort = 'createTime DESC';
        $serviceesmworklog = $this->find(array('createId' => $_SESSION ['USER_ID']), $sort);
        if (is_array($serviceesmworklog)) {
            return $serviceesmworklog;
        } else {
            return array(
                'countryId' => 1,
                'provinceId' => null,
                'cityId' => null
            );
        }
    }

    /**
     * �ж�ĳ������ĳ��Ŀ���Ƿ���д����־
     * @param $userId
     * @param $projectId
     * @return bool
     */
    function checkExistLogPro($userId, $projectId)
    {
        return $this->find(array('projectId' => $projectId, 'createId' => $userId), null) ? true : false;
    }

    /**
     * �ж���Ա�Ƿ�����־
     * @param $userId
     * @param $projectId
     * @return int|string
     */
    function checkExistLogUsers_d($userId, $projectId)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'createIdArr' => $userId
        );
        $this->groupBy = 'c.createId';
        $rs = $this->list_d();
        if ($rs) {
            $createNameArr = array();
            foreach ($rs as $val) {
                array_push($createNameArr, $val['createName']);
            }
            return implode(',', $createNameArr);
        } else {
            return 0;
        }
    }

    /**
     * ��������id��ȡ��־ͳ����Ϣ
     * ����idʱ��ȡϵͳԭͳ��ֵʱ�����㱾��־����Ϣ
     * @param $activityId
     * @param null $id
     * @param bool $done
     * @return array
     */
    function getWorklogCountInfo_d($activityId, $id = null, $done = false)
    {
        $this->searchArr = array(
            'activityId' => $activityId
        );
        if ($id) {
            $this->searchArr['noId'] = $id;
        }
        if ($done) {
            $this->searchArr['confirmStatus'] = 1;
        }
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'costMoney' => 0
            );
        }
    }

    /**
     * ��������id��ȡ��־ͳ����Ϣ
     * ����idʱ��ȡϵͳԭͳ��ֵʱ�����㱾��־����Ϣ
     * @param $activityId
     * @param $memberId
     * @return array
     */
    function getActMemberCountInfo_d($activityId, $memberId)
    {
        $this->searchArr = array(
            'activityId' => $activityId,
            'createId' => $memberId
        );
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'costMoney' => 0
            );
        }
    }

    /**
     * ������Ŀid��ȡ��־ͳ����Ϣ
     * ����idʱ��ȡϵͳԭͳ��ֵʱ�����㱾��־����Ϣ
     * @param $projectId
     * @param $memberId
     * @return array
     */
    function getProjectMemberCountInfo_d($projectId, $memberId)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'createId' => $memberId
        );
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'inWorkDay' => 0,
                'costMoney' => 0
            );
        }
    }

    /**
     * ��ȡ�������
     * @param $thisDate
     * @param null $userId
     * @return array
     */
    function getDayUserCountInfo_d($thisDate, $userId = null)
    {
        //���û��������id����ȥ��¼��
        if (empty($userId)) {
            $userId = $_SESSION['USER_ID'];
        }
        $this->searchArr = array(
            'executionDate' => $thisDate,
            'createId' => $userId
        );
        $rs = $this->list_d('count_list');
        if (!empty($rs[0]['workloadDay'])) {
            return $rs[0];
        } else {
            return array(
                'workloadDay' => 0,
                'workDay' => 0,
                'costMoney' => 0,
                'inWorkRate' => 0
            );
        }
    }

    /**
     * ��ȡ��Ŀʱ�䷶Χ�ڵ���־
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function getSearchData_d($projectId, $beginDate, $endDate)
    {
        $rows = $this->getAssessData_d(array(
            'projectId' => $projectId, 'beginDate' => $beginDate, 'endDate' => $endDate
        ));

        // ʱ���ת��
        $begin = strtotime($beginDate);
        $end = strtotime($endDate);

        // ���ݼٴ���
        foreach ($rows as $k => $v) {
            if (isset($this->lastHols[$v['createId']])) {
                $actHolsDays = 0;
                foreach ($this->lastHols[$v['createId']] as $ki => $vi) {
                    $holsTime = strtotime($ki);
                    if ($holsTime <= $end && $holsTime >= $begin) {
                        $actHolsDays += $vi;
                    }
                }
                $rows[$k]['actHolsDays'] = $actHolsDays;
            }
        }

        return $rows;
    }

    /**
     * ��ȡȱ����־����
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @param $needLogs
     * @return mixed
     */
    function getWarningLogDays_d($projectId, $beginDate, $endDate, $needLogs)
    {
        // ��ϸ���ݻ�ȡ
        $rows = $this->getAssessment_d(array(
            'projectId' => $projectId, 'beginDate' => $beginDate, 'endDate' => $endDate
        ), false, true);

        // ʱ���ת��
        $begin = strtotime($beginDate);
        $end = strtotime($endDate);

        // ���ݼٴ���
        $logNum = 0;
        foreach ($rows as $k => $v) {
            // �������˵�����Ҫ��д������û�����ݼ٣����ж�Ϊ
            if (in_array($v['executionDate'], $needLogs[$v['createId']])) {
                $logDay = bcdiv($v['inWorkRate'], 100, 2);
                $logNum = bcadd($logNum, $logDay, 2);
            }
        }
        // ��Ҫ��д�����ڣ������Ӧ���Ƿ�������ݼ٣�������ڣ����뵽����
        foreach ($needLogs as $k => $v) {
            foreach ($v as $vi) {
                $holsTime = strtotime($vi);
                if ($holsTime <= $end && $holsTime >= $begin && $this->lastHols[$k][$vi]) {
                    $logNum += $this->lastHols[$k][$vi];
                }
            }
        }
        return $logNum;
    }

    /**
     * ��ȡ��־�����б�
     * @param $projectId
     * @return mixed
     */
    function getListProcess_d($projectId)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'confirmStatus' => 0,
            'assessResultsNo' => '5'
        );
        $this->groupBy = 'c.activityId';
        return $this->list_d('getListProcess');
    }

    /**
     * ��ȡʱ�������Ŀ��ͳ����Ϣ
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return bool
     */
    function getProjectCountInfo_d($projectId, $beginDate, $endDate)
    {
        $this->searchArr = array(
            'projectId' => $projectId,
            'confirmStatus' => 1,
            'beginDateThan' => $beginDate,
            'endDateThan' => $endDate
        );
        $this->groupBy = 'c.createId';
        $rs = $this->list_d('search_json');
        if ($rs[0]['createId']) {
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * ��ȡ��־���� - ���뿪ʼ����������
     * ����������
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function getTimeLog_d($beginDate, $endDate)
    {
        //��ȡԤ������������id
        include(WEB_TOR . "includes/config.php");
        $deptIds = isset($defaultOutsourcingDept) ? implode(',', array_keys($defaultOutsourcingDept)) : '';

        $sql = "select
				c.projectId ,p.projectCode,p.projectName,p.officeId,p.officeName,p.provinceId,p.province,p.managerName,
				p.managerId,c.createName as userName,c.createId as userId,count(*) as totalDay,min(c.executionDate) as beginDate,
				max(c.executionDate) as endDate,round(sum(if(c.inWorkRate > 1,1,c.inWorkRate)),2) as feeDay,group_concat(c.ids) as ids
			from
				(
					select
						createId,createName,executionDate,projectId,sum(inWorkRate)/100 as inWorkRate,group_concat(CAST(id AS char)) as ids
					from
						oa_esm_worklog
					where projectId <> 0 and confirmStatus = 1 and executionDate >= '$beginDate' && executionDate <= '$endDate' and isCount = 0
						 and deptId in ($deptIds)
					group by createId,executionDate,projectId
				) c
					left join
				oa_esm_project p on c.projectId = p.id
					left join
				user u on c.createId = u.USER_ID
			group by c.createId,projectId
			order by c.projectId";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ȡ��־���� - ���뿪ʼ����������
     * ���������� - �� create on 2014-01-14
     * @param $beginDate
     * @param $endDate
     * @param null $userId
     * @return null
     */
    function getTimeLogForSupp_d($beginDate, $endDate, $userId = null)
    {
        $user = null ? $userId : $_SESSION['USER_ID'];
        //���������Ӧ�̽ӿ�ȡ��
        $personnelDao = self::getObjCache('model_outsourcing_supplier_personnel');
        $userStr = $personnelDao->getPersonIdList($user);
        //�յ�ʱ��ֱ�ӷ���
        if (empty($userStr)) return null;
        $userStr = util_jsonUtil::strBuild($userStr);
        $sql = "select
                c.projectId ,p.projectCode,p.projectName,p.officeId,p.officeName,p.provinceId,p.province,p.managerName,
                p.managerId,c.createName as userName,c.createId as userId,count(*) as totalDay,min(c.executionDate) as beginDate,
                max(c.executionDate) as endDate,round(sum(if(c.inWorkRate > 1,1,c.inWorkRate)),2) as feeDay,group_concat(c.ids) as ids
            from
                (
                    select
                        createId,createName,executionDate,projectId,sum(inWorkRate)/100 as inWorkRate,group_concat(CAST(id AS char)) as ids
                    from
                        oa_esm_worklog
                    where projectId <> 0 and confirmStatus = 1 and executionDate >= '$beginDate' && executionDate <= '$endDate' and isCount = 0
                         and createId in ($userStr)
                    group by createId,executionDate,projectId
                ) c
                    left join
                oa_esm_project p on c.projectId = p.id
                    left join
                user u on c.createId = u.USER_ID
            group by c.createId,projectId
            order by c.projectId";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ȡ�����ڵ�ͳ����Ϣ
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    function getCountByDates_d($projectId, $beginDate, $endDate)
    {
        $sql = "select
				activityId,sum(workloadDay) as workloadDay,sum(thisActivityProcess) as thisActivityProcess
			from
				oa_esm_worklog where projectId = '$projectId' and confirmStatus = 1 and executionDate >= '$beginDate' && executionDate <= '$endDate' GROUP BY activityId";
        $arr = $this->_db->getArray($sql);
        $newArr = array();
        if ($arr) {//ר�̹�ϣ����
            foreach ($arr as $val) {
                $newArr[$val['activityId']] = $val;
            }
        }
        return $newArr;
    }

    /**
     * �������»�ȡ��־ͳ������
     * @param $thisYear
     * @param $thisMonth
     * @return array|bool
     */
    function getLogData_d($thisYear, $thisMonth)
    {
        $sql = "SELECT
                projectId,projectCode,projectName,createId,createName,SUM(inWorkRate)/100 AS inWorkRate,
                $thisYear AS thisYear,$thisMonth AS thisMonth
            FROM
                oa_esm_worklog
            WHERE
                YEAR(executionDate) = $thisYear AND MONTH(executionDate) = $thisMonth AND confirmStatus = 1
            GROUP BY projectId,createId";
        return $this->_db->getArray($sql);
    }

    /**
     * ��֤��Ŀ��־��дδ����ֹ����
     * @param $projectIds
     * @param $executionDate
     * @return bool
     */
    function checkProjectWithoutDeadline_d($projectIds, $executionDate)
    {
        // ��ȡ
        $esmDeadlineDao = new model_engineering_baseinfo_esmdeadline();
        $deadlineInfo = $esmDeadlineDao->getDeadlineInfo_d();

        // ���û�����ƣ���ֱ��ͨ����֤
        if (empty($deadlineInfo) || empty($deadlineInfo['useRangeId'])) {
            return true;
        }

        $projectDao = new model_engineering_project_esmproject();
        $projectList = $projectDao->getListByIdsAndAttribute_d($projectIds,
            $deadlineInfo['useRangeId']);

        if (!empty($projectList) && strtotime($executionDate) < strtotime($deadlineInfo['date'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * �������ǰ�Ƿ�����д��־
     * @param $userId
     * @param $activityId
     * @param $executionDate
     * @param null $searchType
     * @return bool
     */
    function checkActivityLog_d($userId, $activityId, $executionDate, $searchType = null)
    {
        //��֤��ǰ���ڶ�Ӧ���ܱ��Ƿ��д
        $esmweeklogDao = self::getObjCache('model_engineering_worklog_esmweeklog');
        $weekLogRs = $esmweeklogDao->isSetWeekLog_d($userId, $executionDate);
        if ($weekLogRs == 1) {
            return '��Ӧ�ܱ�������ˣ������Ҫ��д��־��������ϵ����˴���ܱ���';
        } elseif ($weekLogRs == 2) {
            return '��Ӧ�ܱ��Ѿ����ȷ�ϣ����ܼ�����д��';
        }

        //����ǻ�ϲ�ѯ���򷵻����Ľ��
        if ($searchType == 'mul') {
            $this->searchArr = array(
                'activityIds' => $activityId,
                'executionDate' => $executionDate,
                'createId' => $userId
            );
            $rs = $this->list_d();

            if ($rs) {
                //����һ����־������
                $activityNameArr = array();
                //�����������
                foreach ($rs as $val) {
                    array_push($activityNameArr, $val['activityName']);
                }
                return '����' . implode(',', $activityNameArr) . '����־�Ѿ���д��';
            } else {
                return false;
            }

        } else {
            $rs = $this->find(array('createId' => $userId, 'activityId' => $activityId, 'executionDate' => $executionDate), null, 'id');
            if (is_array($rs)) {
                return $rs['id'];
            } else {
                return false;
            }
        }
    }

    /**
     * �����ִ������֮���Ӧ������ʱ�������־
     * @param $executionDate
     * @param $activity
     * @return bool
     */
    function hasBelowLog_d($executionDate, $activity)
    {
        $this->searchArr = array(
            'activityId' => $activity,
            'biggerDate' => $executionDate,
            'createId' => $_SESSION['USER_ID']
        );
        return $this->list_d() ? true : false;
    }

    /**
     * �ж��ܱ�����δ��˵ķ���
     * @param $weekId
     * @return bool
     */
    function checkCostAllAudit_d($weekId)
    {
        $this->searchArr = array(
            'weekId' => $weekId,
            'confirmStatus' => '0',
            'costMoneyNotEqu' => '0'
        );
        return $this->list_d() ? false : true;
    }

    /**
     * ��ѯ��Ա ������ ����־��Ϣ
     * @param $userIds
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function getWorklogInPeriod_d($userIds, $projectId, $beginDate, $endDate)
    {
        $this->searchArr = array(
            'userIds' => $userIds,
            'beginDateThan' => $beginDate,
            'endDateThan' => $endDate,
            'projectId' => $projectId
        );
        return $this->list_d();
    }

    /**
     * ��־����ƴװ
     * @param $worklogArr
     * @return array
     */
    function logArrDeal_d($worklogArr)
    {
        $newLogArr = array();
        if ($worklogArr) {
            foreach ($worklogArr as $val) {
                //��ʾ��¼�ϼƽ���
                $thisCostMoney = $val['confirmStatus'] == 1 ? $val['confirmMoney'] : $val['costMoney'];
                if (!isset($newLogArr[$val['createId']]['costMoney'])) {
                    $newLogArr[$val['createId']]['costMoney'] = $thisCostMoney;
                } else {
                    $newLogArr[$val['createId']]['costMoney'] = bcadd($newLogArr[$val['createId']]['costMoney'], $thisCostMoney, 2);
                }

                $newLogArr[$val['createId']]['logStatus'] = $val['status'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['worklogId'] = $val['id'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['costMoney'] = $val['costMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['confirmMoney'] = $val['confirmMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['backMoney'] = $val['backMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['unconfirmMoney'] = $val['unconfirmMoney'];
                $newLogArr[$val['createId']]['dateInfo'][$val['executionDate']]['confirmStatus'] = $val['confirmStatus'];
            }
        }
        return $newLogArr;
    }

    /**
     * �����ܱ�id��ȡָ����Ŀid
     * @param $weekId
     * @return bool
     */
    function getAuditProjectId_d($weekId)
    {
        $this->searchArr = array(
            'weekId' => $weekId
        );
        $this->groupBy = 'c.createId,c.projectId';
        $this->sort = 'worklogNum';
        $rs = $this->list_d('index_project');
        return $rs[0]['projectId'] ? $rs[0]['projectId'] : false;
    }

    /**
     * ������Ϣ - �༭ҳ��
     * @param $id
     * @return null|string
     */
    function initCost_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempEdit_d($id);
    }

    /**
     * ������Ϣ - �鿴ҳ��
     * @param $id
     * @return null|string
     */
    function initCostView_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempView_d($id);
    }

    /**
     * ������Ϣ - ȷ��ҳ��
     * @param $id
     * @return null|string
     */
    function initCostConfirm_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempConfirm_d($id);
    }

    /**
     * ������Ϣ - ���±༭
     * @param $id
     * @return null|string
     */
    function initCostReedit_d($id)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initTempReedit_d($id);
    }

    /**
     * ������Ϣ - Ʊ������༭
     * @param $id
     * @param $costdetailId
     * @return null|string
     */
    function initCheckEdit_d($id, $costdetailId)
    {
        $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
        return $esmcostdetailDao->initCheckEdit_d($id, $costdetailId);
    }

    /**
     * �����б���õ���
     * �˷����൱���ӣ����Ҷ�
     * @param $object
     * @return bool
     */
    function editCheck_d($object)
    {
        $expenseId = $object['expenseId'];
        unset($object['expenseId']);
        try {
            $this->start_d();

            //�ȸ�����־����
            $idsArr = $this->edit_d($object, 3);

            //���»�ȡ��������ϸ��Ȼ����и���
            $expenseDao = new model_finance_expense_expense();
            $expenseObj = $expenseDao->find(array('id' => $expenseId));
            $ecostdetailIdArr = explode(',', $expenseObj['esmCostdetailId']);

            //ʵ������������ϸ��
            $expensedetailDao = new model_finance_expense_expensedetail();
            $expenseinvDao = new model_finance_expense_expenseinv();

            //ȥ����Ч��ֵ
            if ($idsArr['delIds']) {
                foreach ($ecostdetailIdArr as $key => $val) {
                    if (in_array($val, $idsArr['delIds'])) {
                        unset($ecostdetailIdArr[$key]);
                    }
                }
            }
            $newArr = array_merge($ecostdetailIdArr, $idsArr['addIds']);

            //���µķ�����Ŀȥ���±�����
            $esmcostdetailDao = new model_engineering_cost_esmcostdetail();
            //��ȡ�µķ�Ʊ��ϸ
            $esminvoicedetailDao = new model_engineering_cost_esminvoicedetail();

            //ɾ�����ݴ���
            $this->delCostDetail_d($object, $idsArr['delIds'], $expenseObj, $expensedetailDao, $expenseinvDao, $esminvoicedetailDao);

            //��ȡ������������
            $newIds = implode(',', $newArr);
            $newCostdetailArr = $esmcostdetailDao->getCostByIds_d($newIds);
            //ѭ��������Ʊ��Ŀ�Լ����·�������
            foreach ($newCostdetailArr as $key => $val) {
                //�ñ������Ͳ�ѯ��������ϸ
                $rs = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo'], 'costTypeId' => $val['costTypeId']), null, 'id,AssID');
                //��ȡassId
                if (!isset($assId)) {
                    if (empty($assId)) {
                        $assArr = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo']), null, 'AssID');
                        $assId = $assArr['AssID'];
                    } else {
                        $assId = $rs['AssID'];
                    }
                }
                //���¼����޸ĵ���
                if ($rs) {//����Ѵ������ͣ���������
                    $expensedetailDao->update(array('id' => $rs['id']), array('CostMoney' => $val['costMoney'], 'esmCostdetailId' => $val['costIds']));
                } else {
                    $newId = $expensedetailDao->create(
                        array(
                            'CostMoney' => $val['costMoney'],
                            'RNo' => 1,
                            'days' => 1,
                            'esmCostdetailId' => $val['costIds'],
                            'CostTypeID' => $val['costTypeId'],
                            'HeadID' => $expenseId,
                            'BillNo' => $expenseObj['BillNo'],
                            'AssID' => $assId,
                            'MainType' => $val['parentCostType'],
                            'MainTypeId' => $val['parentCostTypeId'],
                            'Remark' => $val['remark']
                        )
                    );
                    $rs = array(
                        'id' => $newId
                    );
                }

                //ѭ�����·�Ʊ��Ϣ
                $newCostdetailArr[$key]['expenseinv'] = $esminvoicedetailDao->getInvoice_d($val['costIds'], 3);

                foreach ($newCostdetailArr[$key]['expenseinv'] as $v) {
                    //��ѯ��Ӧ��Ʊ
                    $irs = $expenseinvDao->find(array('BillDetailID' => $rs['id'], 'BillTypeID' => $v['invoiceTypeId']), null, 'id');
                    //���¼��㷢Ʊ������
                    if ($irs) {
                        $expenseinvDao->update(array('id' => $irs['id']), array('Amount' => $v['invoiceMoney'], 'invoiceNumber' => $v['invoiceNumber']));
                    } elseif (empty($irs)) {
                        $expenseinvDao->create(
                            array(
                                'Amount' => $v['invoiceMoney'],
                                'invoiceNumber' => $v['invoiceNumber'],
                                'BillTypeID' => $v['invoiceTypeId'],
                                'BillDetailID' => $rs['id'],
                                'BillNo' => $expenseObj['BillNo'],
                                'BillAssID' => $assId
                            )
                        );
                    }
                }
            }

            //���¼��㱨�������ܽ��
            $expenseDao->recountExpense_d($expenseId, $expensedetailDao, $expenseinvDao);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ɾ�����ݴ���
     * @param $object
     * @param $delArr
     * @param $expenseObj
     * @param null $expensedetailDao
     * @param null $expenseinvDao
     * @param null $esminvoicedetailDao
     * @throws Exception
     */
    function delCostDetail_d($object, $delArr, $expenseObj, $expensedetailDao = null, $expenseinvDao = null, $esminvoicedetailDao = null)
    {
        //�ж��Ƿ�����ʵ����������ϸ
        if (!$expensedetailDao) {
            $expensedetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
        }
        //�ж��Ƿ�����ʵ����������ϸ
        if (!$expenseinvDao) {
            $expenseinvDao = new model_finance_expense_expenseinv();
        }
        //�ж��Ƿ�����ʵ����������ϸ
        if (!$esminvoicedetailDao) {
            $esminvoicedetailDao = new model_engineering_cost_esminvoicedetail();
        }

        //��Ӧ���������л���
        try {
            $this->start_d();

            foreach ($object['esmcostdetail'] as $key => $val) {
                //�������Ҫɾ���������д���
                if (isset($val['isDelTag']) && $val['isDelTag'] == 1 && isset($val['id'])) {
                    $rs = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo'], 'costTypeId' => $val['costTypeId']), null, 'id,esmCostDetailId');
                    if (is_numeric($rs['esmCostDetailId']) && in_array($rs['esmCostDetailId'], $delArr)) {
                        //ɾ����Ӧ�ķ�����Ϣ
                        $expensedetailDao->delete(array('id' => $rs['id']));
                        //ɾ����Ӧ�ķ�Ʊ��Ϣ
                        $expenseinvDao->delete(array('BillDetailID' => $rs['id']));
                    }
                } elseif (isset($val['id'])) {
                    $rs2 = null;
                    //ѭ����Ʊ��Ϣ����ȡ��Ҫɾ���ķ�Ʊ
                    foreach ($val['invoiceDetail'] as $k => $v) {
                        //�����ѯ��ɾ�������ݣ���ʵ������Ӧ����Ϣ
                        if (isset($v['isDelTag']) && $v['isDelTag'] == 1 && isset($v['id'])) {
                            //�жϵ�ǰ��Ʊ�����
                            //���һ����ط�Ʊֻ���ڱ���¼��
                            //�������һ��־һ�����к��ж�����ͬ��Ʊ
                            //�������һ��֮��ͬ�����к���ͬ��Ʊ
                            if (empty($rs2)) {
                                $rs2 = $expensedetailDao->find(array('BillNo' => $expenseObj['BillNo'], 'costTypeId' => $val['costTypeId']), null, 'id,esmCostDetailId');
                            }
                            $irs = $esminvoicedetailDao->checkInvoiceExist_d($v['id'], $v['invoiceTypeId'], $rs2['esmCostDetailId']);
                            if (!$irs) {
                                //ɾ����Ӧ�ķ�Ʊ��Ϣ
                                $expenseinvDao->delete(array('BillDetailID' => $rs2['id'], 'BillNo' => $expenseObj['BillNo'], 'BillTypeID' => $v['invoiceTypeId']));
                            }
                        }
                    }
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * �ж����� �Լ��������� �Ƿ������־
     * @param $activityId
     * @return mixed
     */
    function checkActAndParentLog_d($activityId)
    {
        //�Ȳ�ѯ�������ӽڵ�
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');
        $activityObj = $activityDao->find(array('id' => $activityId), null, 'lft,rgt');
        $activityIds = $activityDao->getUnderTreeIds_d($activityId, $activityObj['lft'], $activityObj['rgt']);

        //��ѯ��־
        $this->searchArr = array('activityIds' => $activityIds);
        return $this->list_d();
    }

    /**
     * ������Ա������ְ��Ϣ
     * @param $data
     * @param $userId
     * @param $info
     * @return mixed
     */
    function dealEntryQuit_d($data, $userId = '', $info = array())
    {
        // ��ȡ��Ա����ְ����ְ��Ϣ
        $userInfo = $userId ? $this->getPersonnelInfo_d($userId) : $info;

        // ��������ְ����
        foreach ($data as $k => $v) {
            if ($userInfo['entryTime'] && $userInfo['entryTime'] > $v['executionTimes']) {
                $data[$k]['notEntry'] = 1;
                $data[$k]['noNeed'] = 1;
                continue;
            }
            if ($userInfo['quitTime'] && $userInfo['quitTime'] < $v['executionTimes']) {
                $data[$k]['isLeave'] = 1;
                $data[$k]['noNeed'] = 1;
                continue;
            }
        }

        return $data;
    }

    /**
     * ������Ա���ݼ�
     * @param $data
     * @param $beginDate
     * @param $endDate
     * @param $userId
     * @param $info
     * @return mixed
     */
    function dealHolds_d($data, $beginDate = '', $endDate = '', $userId = '', $info = array())
    {
        // ��ȡ���ݼ�
        $hols = $beginDate ? $this->getHolsHash_d(strtotime($beginDate), strtotime($endDate), $userId) : $info;

        foreach ($data as $k => $v) {
            if ($hols[$userId][$v['executionDate']]) {
                $data[$k]['hols'] = $hols[$userId][$v['executionDate']];
                if ($hols[$userId][$v['executionDate']] == 1) {
                    $data[$k]['noNeed'] = 1;
                } else {
                    $data[$k]['noNeed'] = 1;
                    $data[$k]['need'] = bcsub(1, $hols[$userId][$v['executionDate']], 2);
                }
            }
        }
        return $data;
    }

    /**
     * ��ʼ������־
     * @param $data
     * @param $projectId
     * @param $logs
     * @return mixed
     */
    function dealLog_d($data, $projectId, $logs)
    {
        $logMapping = array();

        foreach ($logs as $v) {
            if (!isset($logMapping[$v['executionTimes']])) {
                $logMapping[$v['executionTimes']] = array();
            }
            $logMapping[$v['executionTimes']][] = $v;
        }

        $rst = array();
        foreach ($data as $k => $v) {
            if (isset($logMapping[$v['executionTimes']])) {
                foreach ($logMapping[$v['executionTimes']] as $vi) {
                    if ($projectId == $vi['projectId']) {
                        $vi['monthScore'] = $vi['accessScore'];
                        $vi['inWorkRate'] = round(bcdiv($vi['inWorkRate'], 100, 4), 2);
//                        $vi['noNeed'] = 1;
                        $rst[] = $vi;
                    } else {
                        $v['noNeed'] = 1;
                        $v['otherProject'] = 1;
                        $v['projectCode'] = $vi['projectCode'];
                        $rst[] = $v;
                    }
                }
            } else {
                $rst[] = $v;
            }
        }

        return $rst;
    }

    /**
     * ����δ��д���ܱ�
     * @param $row
     * @param $beginDate
     * @param $endDate
     * @param $projectId
     * @return array
     */
    function dealUnLog_d($row, $beginDate, $endDate, $projectId)
    {
        //��������
        $days = (strtotime($endDate) - strtotime($beginDate)) / 86400 + 1;
        //������
        $newRows = array();
        //ͷ����Ϣ��Ⱦ
        $thisDate = $endDate;
        for ($i = $days; $i > 0; $i--) {
            $hasLog = false;
            if ($i != $days) {
                $thisDate = date('Y-m-d', strtotime($thisDate) - 86400);
            }

            foreach ($row as $val) {
                if ($thisDate == $val['executionDate']) {
                    if ($projectId == $val['projectId']) {
                        $newRows[] = $val;
                    } else {
                        $newRows[] = array(
                            'id' => $thisDate,
                            'executionDate' => $thisDate,
                            'confirmStatus' => 3
                        );
                    }
                    $hasLog = true;
                }
            }

            if ($hasLog == false) {
                $newRows[] = array(
                    'id' => $thisDate,
                    'executionDate' => $thisDate
                );
            }
        }
        return array_reverse($newRows);
    }

    /**
     * δ��д��־
     * @param $obj
     * @return array
     */
    function warnView_d($obj)
    {
        $condition = '';
        $deptCondition = '';
        $objArr = array();//ûд��־
        $otherDataDao = new model_common_otherdatas();

        if (isset($obj['k'])) {
            // ������ڴ����ˣ������ӹ���
            if ($obj['createId']) {
                $condition .= " AND c.createId = '" . $obj['createId'] . "'";
                $deptCondition .= " AND c.userAccount = '" . $obj['createId'] . "'";
            }
            $beginDateThan = strtotime($obj['year'] . '-' . $obj['month'] . '-01');
            $endDateThan = strtotime(date('Y-m-t', $beginDateThan));
            $condition .= " AND c.executionTimes >= '" . $beginDateThan . "'";
            $condition .= " AND c.executionTimes <= '" . $endDateThan . "'";
            $deptCondition .= " AND c.belongDeptId in (" . $obj['deptId'] . ")";
        } else {
            $beginDateThan = strtotime($obj['beginDateThan']);
            $endDateThan = strtotime($obj['endDateThan']);
            if ($beginDateThan) $condition .= " AND c.executionTimes >= '" . $beginDateThan . "'";
            if ($endDateThan) $condition .= " AND c.executionTimes <= '" . $endDateThan . "'";
            if ($obj['deptId']) $deptCondition = " AND c.belongDeptId in (" . $obj['deptId'] . ")";

            //����Ȩ��
            $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            $deptLimit = $sysLimit['����Ȩ��'];
            if (strpos($deptLimit, ';;') === false) {
                $deptCondition .= empty($deptLimit) ?
                    " AND c.belongDeptId in (" . $_SESSION['DEPT_ID'] . ")" :
                    " AND c.belongDeptId in (" . $deptLimit . ',' . $_SESSION['DEPT_ID'] . ")";
            }
        }

        // ������ڹ��˵�ְλ����ƴ����ش���
        $filterJobNameStr = "";
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('RZGJJOBFILTER');
        if(count($matchConfigItem) > 0){
            foreach ($matchConfigItem as $configItem){
                if($configItem['config_item1'] != ""){
                    $filterJobNameStr .= ($filterJobNameStr == "")?
                        trim($configItem['config_item1'],",") :
                        ",".trim($configItem['config_item1'],",");
                }
            }
            $deptCondition .= " AND c.jobName NOT IN(" . util_jsonUtil::strBuild($filterJobNameStr) . ")";
        }
//        $filterJobName = $otherDataDao->getConfig('engineering_worklog_warning_filter_jobName');
//        if ($filterJobName) {
//            $deptCondition .= " AND c.jobName NOT IN(" . util_jsonUtil::strBuild($filterJobName) . ")";
//        }

        $sql = <<<MARK
    		SELECT c.userAccount AS createId,c.userName AS createName,c.belongDeptName,c.jobName,w.writeLog,
    		    if(c.entryDate = '0000-00-00', '', c.entryDate) AS entryDate,
    		    if(c.quitDate = '0000-00-00', '', c.quitDate) AS quitDate,
    		    w.confirmStatus
            FROM
                oa_hr_personnel c
                LEFT JOIN
                (
                    SELECT GROUP_CONCAT(CAST(c.executionDate AS char)) AS writeLog,c.createId,
                        GROUP_CONCAT(CAST(c.confirmStatus AS char)) AS confirmStatus FROM
                        (
                            SELECT
                                c.executionDate,c.createId,c.confirmStatus
                            FROM oa_esm_worklog c WHERE 1 $condition
                            GROUP BY c.executionDate,c.createId
                        ) c GROUP BY c.createId
                ) w ON c.userAccount = w.createId
            WHERE c.employeesState = 'YGZTZZ' $deptCondition
MARK;
        $rows = $this->findSql($sql);

        // ��������
        $num = ($endDateThan - $beginDateThan) / 86400; // ����
        $nextStamp = $beginDateThan;
        $speDate = array(date('Y-m-d', $nextStamp));//��ѯ��ʱ���
        for ($i = 1; $i <= $num; $i++) { // ��ѯ���ڼ����������
            $nextStamp = $nextStamp + 86400;
            array_push($speDate, date('Y-m-d', $nextStamp));
        }

        //����δд��־ʱ��
        if (!empty($rows) && !empty($speDate)) {

            // ���ݼ�
            $holsInfo = $this->getHolsHash_d($beginDateThan, $endDateThan);

            foreach ($rows as $v) {
                // ���� ���� =�� ȷ��״̬ ������
                $logArr = array_combine(explode(',', $v['writeLog']), explode(',', $v['confirmStatus']));

                // ��ʼƥ���ѯʱ��
                foreach ($speDate as $time) {
                    $thisTime = strtotime($time);
                    // �������£���־δ�ύ / ���������ݼ� / ���ڵ�����ְ���� / С�ڵ�����ְ����
                    if (!isset($logArr[$time]) && !isset($holsInfo[$v['createId']][$time])
                        && (empty($v['entryDate']) || $thisTime >= strtotime($v['entryDate']))
                        && (empty($v['quitDate']) || $thisTime <= strtotime($v['quitDate']))
                    ) {
                        $msg = 'δ�';
                    } else if ($logArr[$time] == '0') {
                        $msg = 'δ���';
                    } else {
                        continue;
                    }
                    array_push($objArr, array(
                        'executionDate' => $time,
                        'createId' => $v['createId'],
                        'createName' => $v['createName'],
                        'belongDeptName' => $v['belongDeptName'],
                        'jobName' => $v['jobName'],
                        'msg' => $msg
                    ));
                }
            }
        }
        return $objArr;
    }

    /**
     * δ��д��־����
     * @param $obj
     * @return array
     */
    function warnSummary_d($obj)
    {
        // ��ȡ�澯����
        $warningData = $this->warnView_d($obj);

        // ��������
        $warningSummary = array();
        if (!empty($warningData)) {
            foreach ($warningData as $v) {
                if (!isset($warningSummary[$v['createId']])) {
                    $warningSummary[$v['createId']] = $v;
                    $warningSummary[$v['createId']]['warningNum'] = 1;
                } else {
                    $warningSummary[$v['createId']]['warningNum']++;
                }
            }
            $warningSummary = array_values($warningSummary);
        }

        return $warningSummary;
    }
    /******************************** ��־���벿�� ***********************/
    /**
     * ��־����
     */
    function excelIn_d()
    {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//�������
        $tempArr = array();
        $datadictArr = array();//�����ֵ�����
        $datadictDao = self::getObjCache('model_system_datadict_datadict');

        //��Ŀ��������
        $esmprojectArr = array();
        $esmprojectDao = self::getObjCache('model_engineering_project_esmproject');
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');
        $esmmemberDao = self::getObjCache('model_engineering_member_esmmember');

        //����ʡ�ݻ���
        $cityArr = array();
        $cityDao = self::getObjCache('model_system_procity_city');
        $provinceArr = array();
        $provinceDao = self::getObjCache('model_system_procity_province');

        //������־����
        $esmworklogArr = array();

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //�ж���־�����Ƿ�������ݼټ�¼
                $holsStr = $this->isInHols_d($excelData);
                if (!empty($holsStr)) {
                    $tempArr['docCode'] = '��' . $holsStr . '���������ݼټ�¼������״̬����Ϊ���������';
                    $tempArr['result'] = '����ʧ��';
                    array_push($resultArr, $tempArr);
                    return $resultArr;
                }

                // ��ȡ��ְ����
                $personnelInfo = $this->getPersonnelInfo_d();
                $entryTime = strtotime($personnelInfo['entryDate']);

                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $val[2] = strtoupper(trim($val[2]));
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $inArr = array();
                        //ִ������
                        if (!empty($val[0]) && $val[0] != '0000-00-00' && trim($val[0]) != '') {
                            $val[0] = trim($val[0]);
                            // ��ȡ����
                            $inArr['executionDate'] = !is_numeric($val[0]) ? $val[0] : util_excelUtil::exceltimtetophp($val[0]);

                            //������ǰ����־
                            $executionTime = strtotime($inArr['executionDate']);
                            if (strtotime(day_date) < $executionTime) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!������ǰ����־';
                                array_push($resultArr, $tempArr);
                                continue;
                            } else if ($executionTime < $entryTime) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!������д��ְ����֮ǰ����־';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����дִ������';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //����״̬
                        if (!empty($val[1])) {
                            $val[1] = trim($val[1]);
                            if (!isset($datadictArr[$val[1]])) {
                                $rs = $datadictDao->getCodeByName('GXRYZT', $val[1]);
                                if (!empty($rs)) {
                                    $inArr['workStatus'] = $datadictArr[$val[1]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵĹ���״̬';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['workStatus'] = $datadictArr[$val[1]]['code'];
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����״̬';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��Ŀ���
                        if (!empty($val[2]) && !empty($val[3])) {
                            //��Ŀ���洦��
                            if (!isset($esmprojectArr[$val[2]])) {
                                $esmprojectObj = $esmprojectDao->find(array('projectCode' => $val[2]), null, 'id,projectName,maxLogDay,planEndDate,status,attribute');
                                if ($esmprojectObj) {
                                    $esmprojectArr[$val[2]] = $esmprojectObj;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!û�в�ѯ����ص���Ŀ';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }

                            // �Ǻ�̨��Ŀ��Ҫ��֤��Ŀ��Ա
                            if ($esmprojectArr[$val[2]]['attribute'] != "GCXMSS-04") {
                                // ��Ŀ��Ա����
                                if (!isset($esmprojectArr[$val[2]]['isMember'])) {
                                    $esmmemberObj = $esmmemberDao->find(
                                        array('projectCode' => $val[2], 'memberId' => $_SESSION['USER_ID'], 'status' => 0),
                                        null, 'id');
                                    $esmprojectArr[$val[2]]['isMember'] = $esmmemberObj ? true : false;
                                }

                                // ��Ŀ��Ա��֤
                                if (!$esmprojectArr[$val[2]]['isMember']) {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!����Ŀ��Ա�����Ѿ��뿪����Ա���ܵ�����־';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }

                            //��Ŀ״̬�ж�
                            if ($esmprojectArr[$val[2]]['status'] != 'GCXMZT02') {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!��Ŀ�����ڽ�״̬';
                                array_push($resultArr, $tempArr);
                                continue;
                            }

                            //�����ж�
                            if ($esmprojectArr[$val[2]]['maxLogDay'] != 0 &&
                                round((strtotime(day_date) - strtotime($inArr['executionDate'])) / 86400) > $esmprojectArr[$val[2]]['maxLogDay']
                            ) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!��־�ѳ��������';
                                array_push($resultArr, $tempArr);
                                continue;
                            }

                            //���񻺴洦��
                            if (!isset($esmprojectArr[$val[2]][$val[3]])) {
                                $esmactivityObjs = $activityDao->getCanLogTask_d($esmprojectArr[$val[2]]['id'], $val[3]);
                                if ($esmactivityObjs && count($esmactivityObjs) == 1) {
                                    //��������
                                    $esmprojectArr[$val[2]][$val[3]] = array_pop($esmactivityObjs);
                                } elseif ($esmactivityObjs && count($esmactivityObjs) > 1) {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!��Ŀ�´��ڶ��ͬ�����񣬲��ܵ�����־';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!��Ŀ��û�в�ѯ����ص�����';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }

                            //��������
                            $inArr['projectId'] = $esmprojectArr[$val[2]]['id'];
                            $inArr['projectCode'] = $val[2];
                            $inArr['projectName'] = $esmprojectArr[$val[2]]['projectName'];
                            $inArr['projectEndDate'] = $esmprojectArr[$val[2]]['planEndDate'];
                            $inArr['activityId'] = $esmprojectArr[$val[2]][$val[3]]['id'];
                            $inArr['activityName'] = $val[3];
                            $inArr['activityEndDate'] = $esmprojectArr[$val[2]][$val[3]]['planEndDate'];
                            $inArr['workloadUnit'] = $esmprojectArr[$val[2]][$val[3]]['workloadUnit'];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!��Ŀ�����������д';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //ʡ��
                        if (!empty($val[4])) {
                            $val[4] = trim($val[4]);
                            if (!isset($provinceArr[$val[4]])) {
                                $rs = $provinceDao->find(array('provinceName' => $val[4]), null, 'id');
                                if (!empty($rs)) {
                                    $inArr['provinceId'] = $provinceArr[$val[4]]['id'] = $rs['id'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ�ʡ��';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['provinceId'] = $provinceArr[$val[4]]['id'];
                            }
                            $inArr['province'] = $val[4];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����дʡ��';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //����
                        if (!empty($val[5])) {
                            $val[5] = trim($val[5]);
                            if (!isset($cityArr[$val[5]])) {
                                $rs = $cityDao->find(array('cityName' => $val[5]), null, 'id');
                                if (!empty($rs)) {
                                    $inArr['cityId'] = $cityArr[$val[5]]['id'] = $rs['id'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵĳ���';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['cityId'] = $cityArr[$val[5]]['id'];
                            }
                            $inArr['city'] = $val[5];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //�����
                        $workloadDay = $val[6] === '' ? 'NONE' : sprintf("%f", trim($val[6]));
                        if ($workloadDay != 'NONE') {
                            $inArr['workloadDay'] = $workloadDay;
                        }

                        //Ͷ�빤��������
                        $inWorkRate = $val[7] === '' ? 'NONE' : sprintf("%f", abs(trim($val[7])));
                        if ($inWorkRate != 'NONE') {
                            if ($inWorkRate < 0) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!Ͷ�빤����������С��0';
                                array_push($resultArr, $tempArr);
                                continue;
                            } else {
                                $inArr['inWorkRate'] = $inWorkRate * 100;
                            }
                        }

                        //��������
                        if (!empty($val[8])) {
                            $inArr['description'] = $val[8];
                        }

                        //��ע
                        if (!empty($val[9])) {
                            $inArr['problem'] = $val[9];
                        }

                        //Ĭ�����������Ϣ
                        $inArr['country'] = '�й�';
                        $inArr['countryId'] = '1';

                        //�ж���־�Ƿ����
                        if (isset($esmworklogArr[$inArr['executionDate']][$inArr['activityId']])) {
                            $inArr['id'] = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['id'];
                            //��ʱ������Ͷ�빤��ռ�ȣ��粻���ϣ���ԭ
                            $tempInWorkRate = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'];
                            $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'] = $inArr['inWorkRate'];
                        } else {
                            //��ȥ��������
                            $esmworklogObjs = $this->findAll(array('executionDate' => $inArr['executionDate'], 'createId' => $_SESSION['USER_ID']));
                            if ($esmworklogObjs) {
                                foreach ($esmworklogObjs as $v) {
                                    $esmworklogArr[$inArr['executionDate']][$v['activityId']] = $v;
                                }
                            }
                            //����Ѿ����ڣ���ֵ
                            if ($esmworklogArr[$inArr['executionDate']][$inArr['activityId']]) {
                                $inArr['id'] = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['id'];
                                //��ʱ������Ͷ�빤��ռ�ȣ��粻���ϣ���ԭ
                                $tempInWorkRate = $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'];
                                $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'] = $inArr['inWorkRate'];
                            } else {
                                $tempInWorkRate = 0;
                                $esmworklogArr[$inArr['executionDate']][$inArr['activityId']] = $inArr;
                            }
                        }

                        //�ж���־�Ƿ�����
                        if ($inArr['id'] && $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['confirmStatus'] == 1) {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!��־����ˣ����ܽ��и���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        // ��������������Ҵ�����Ŀid, ��ʼ��֤��־�����
                        if (!isset($inArr['id']) && $inArr['projectId']) {
                            if (!$this->checkProjectWithoutDeadline_d($inArr['projectId'], $inArr['executionDate'])) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!��Ŀ������ֹ�����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        //�ж�Ͷ�빤��ռ���Ƿ�����
                        if ($this->calInWorkRateExcel_d($esmworklogArr[$inArr['executionDate']])) {
                            $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['inWorkRate'] = $tempInWorkRate;
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!Ͷ�빤�������ѳ�����Χ';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��ȡ����
                        if (isset($inArr['id'])) {
                            $processInfo = $activityDao->calTaskProcess_d($inArr['activityId'], $inArr['workloadDay'], $inArr['id']);
                        } else {
                            $processInfo = $activityDao->calTaskProcess_d($inArr['activityId'], $inArr['workloadDay'], null);
                        }
                        //�������
                        $inArr['workProcess'] = $processInfo['process'];
                        $inArr['thisActivityProcess'] = $processInfo['thisActivityProcess'];
                        $inArr['thisProjectProcess'] = $processInfo['thisProjectProcess'];

                        //����id �ж����������������������
                        if (isset($inArr['id'])) {
                            // �����޸�����־���ݺ�,��ʼ��ȷ������
                            $inArr['confirmDate'] = null;
                            $inArr['confirmId'] = '';
                            $inArr['confirmName'] = '';
                            $inArr['confirmStatus'] = 0;
                            $inArr['assessResult'] = 0;
                            $inArr['assessResultName'] = '';
                            $inArr['feedBack'] = '';

                            $this->edit_d($inArr);
                            $tempArr['result'] = '���³ɹ�';
                        } else {
                            $newId = $this->add_d($inArr);
                            $esmworklogArr[$inArr['executionDate']][$inArr['activityId']]['id'] = $newId;
                            $tempArr['result'] = '�����ɹ�';
                        }

                        $tempArr['docCode'] = '��' . $actNum . '������';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ���������ڵĹ���վ���Ƿ񳬱� - excel������
     * @param $esmworklogArr
     * @return bool
     */
    function calInWorkRateExcel_d($esmworklogArr)
    {
        $inWorkRate = 0;
        foreach ($esmworklogArr as $val) {
            $inWorkRate = bcadd($inWorkRate, $val['inWorkRate'], 2);
        }
        return $inWorkRate > 100 || $inWorkRate < 0 ? true : false;
    }

    /**
     * ��ȡ��ѯ�б������
     * @param $object
     * @return string
     */
    function getSearchList($object)
    {
        $condition = '';
        $workStatusSql = '';
        $projectIdSql = '';
        if (!empty($object['workStatusArr'])) {
            $workStatus = util_jsonUtil::strBuild($object['workStatusArr']);
            $workStatusSql .= " c.workStatus in($workStatus)";
        }
        if (!empty($object['projectId'])) {
            $projectIdSql .= " AND c.projectId = " . $object['projectId'];
        }
        $work = " c.workStatus = 'GXRYZT-01' ";
        $sql = "
            select
                c.createId,c.createName,c.projectId,c.projectCode,c.projectName,
                round(sum(if($workStatusSql,c.inWorkRate/100,0)),2) as inWorkRate,
                round(sum(if($workStatusSql,c.workCoefficient/c.inWorkRate * 100,0)),2) as auditScore,
                round(sum(if($workStatusSql,c.workCoefficient,0))/sum(if($workStatusSql,c.inWorkRate/100,0)),2) as perAuditScore,
                m.monthAuditScore,
                sum(c.costMoney) as costMoney,
                sum(c.thisProjectProcess) as thisProjectProcess,
                sum(c.processCoefficient) as processCoefficient,
                round(sum(if($work,c.workCoefficient/c.inWorkRate * 100,0)),2) as workCoefficient
            from
                oa_esm_worklog c
                LEFT JOIN
                (
                SELECT createId,round(sum(if($workStatusSql,c.workCoefficient,0))/sum(if($workStatusSql,c.inWorkRate/100,0)),2) as monthAuditScore
                FROM oa_esm_worklog c
                WHERE
                c.confirmStatus ='{$object['confirmStatus']}' and c.executionDate >= '{$object['beginDateThan']}'
                and c.executionDate <= '{$object['endDateThan']}'
                GROUP BY createId
                ) m ON c.createId = m.createId
            where c.confirmStatus ='{$object['confirmStatus']}' and c.executionDate >= '{$object['beginDateThan']}' and c.executionDate <= '{$object['endDateThan']}' $projectIdSql
            $condition
            group by c.projectId,c.createId";
        return $this->_db->getArray($sql);
    }

    /**
     * ������ͳ��
     * @param $object
     * @param bool $getSql
     * @return string
     */
    function getSearchDeptList_d($object, $getSql = false)
    {
        $condition = '';
        $projectIdSql = '';
        $workStatusSql = " c.workStatus in('GXRYZT-01','GXRYZT-02','GXRYZT-04')";
        if (!empty($object['projectId'])) {
            $projectIdSql .= " AND c.projectId = " . $object['projectId'];
        }
        $work = " c.workStatus = 'GXRYZT-01' ";
        $sql = "
            SELECT
                c.createId,c.createName,u.belongDeptName,c.projectId,c.projectCode,c.projectName,
                round(SUM(if($workStatusSql,c.inWorkRate/100,0)),2) AS inWorkRate,
                round(SUM(if($workStatusSql,c.workCoefficient/c.inWorkRate * 100,0)),2) AS auditScore,
                round(SUM(if($workStatusSql,c.workCoefficient,0))/SUM(if($workStatusSql,c.inWorkRate/100,0)),2) AS perAuditScore,
                m.monthAuditScore,
                SUM(c.costMoney) AS costMoney,
                SUM(c.thisProjectProcess) AS thisProjectProcess,
                SUM(c.processCoefficient) AS processCoefficient,
                round(SUM(if($work,c.workCoefficient/c.inWorkRate * 100,0)),2) AS workCoefficient
            FROM
                oa_esm_worklog c
                LEFT JOIN
                (
                SELECT createId,round(SUM(if($workStatusSql,c.workCoefficient,0))/SUM(if($workStatusSql,c.inWorkRate/100,0)),2) AS monthAuditScore
                FROM oa_esm_worklog c
                WHERE
                c.confirmStatus = 1 AND c.executionDate >= '{$object['beginDate']}'
                and c.executionDate <= '{$object['endDate']}'
                GROUP BY createId
                ) m ON c.createId = m.createId
				LEFT JOIN
					oa_hr_personnel u ON u.userAccount = c.createId
            WHERE c.confirmStatus = 1 AND c.executionDate >= '{$object['beginDate']}'
            	AND c.executionDate <= '{$object['endDate']}' $projectIdSql
            $condition
            group by c.projectId,c.createId";
        return $getSql ? $sql : $this->_db->getArray($sql);
    }

    /**
     * �����û��ȼ�
     * @param $rows
     * @param $object
     * @return bool
     */
    function appendLogInfo_d($rows, $object)
    {
        $deptId = is_array($object) ? $object['deptId'] : $object;
        if ($rows) {
            $deptIdArr = array();
            // ���벿�����ݹ���
            if ($deptId) {
                $deptIdArr = explode(',', $deptId);
            }

            // ��ȡ�û���Ϣ
            $personArr = $this->_db->getArray('SELECT userAccount,personLevel,userNo,belongDeptId,belongDeptName FROM oa_hr_personnel');
            $personLevelInfo = array(); // ������Ա�ȼ�����
            foreach ($personArr as $v) {
                $personLevelInfo[$v['userAccount']] = $v;
            }
            unset($personArr); // ������unset��

            // ��ʼ���˲�����Ϣ
            foreach ($rows as $k => $v) {
                if (!empty($deptIdArr) && !in_array($personLevelInfo[$v['createId']]['belongDeptId'], $deptIdArr)) {
                    unset($rows[$k]);
                    continue;
                }
                $rows[$k]['personLevel'] = isset($personLevelInfo[$v['createId']]) ? $personLevelInfo[$v['createId']]['personLevel'] : '';
                $rows[$k]['userNo'] = isset($personLevelInfo[$v['createId']]) ? $personLevelInfo[$v['createId']]['userNo'] : '';
                $rows[$k]['deptName'] = isset($personLevelInfo[$v['createId']]) ? $personLevelInfo[$v['createId']]['belongDeptName'] : '';
            }
            unset($personLevelInfo); // ������unset��
            return $rows;
        }
        return false;
    }

    /**
     * ��ѯ��Ŀ��Ա
     * @param $projectId
     * @return array
     */
    function findMember_d($projectId)
    {
        $memberDao = new model_engineering_member_esmmember();
        $data = $memberDao->getMemberInProject_d($projectId);
        foreach ($data as $k => $v) {
            $data[$k]['createId'] = $v['memberId'];
            $data[$k]['createName'] = $v['memberName'];
        }
        return $data;
    }

    /**
     * ������ͳ�� - �ϼƲ���
     * @param $rows
     * @return array
     */
    function getSearchDeptCount_d($rows)
    {
        $rtOjb = array(
            'inWorkRate' => 0,
            'costMoney' => 0,
            'thisProjectProcess' => 0,
            'processCoefficient' => 0,
            'workCoefficient' => 0
        );
        foreach ($rows as $v) {
            $rtOjb['inWorkRate'] = bcadd($rtOjb['inWorkRate'], $v['inWorkRate'], 2);
            $rtOjb['costMoney'] = bcadd($rtOjb['costMoney'], $v['costMoney'], 2);
            $rtOjb['thisProjectProcess'] = bcadd($rtOjb['thisProjectProcess'], $v['thisProjectProcess'], 2);
            $rtOjb['processCoefficient'] = bcadd($rtOjb['processCoefficient'], $v['processCoefficient'], 2);
            $rtOjb['auditScore'] = bcadd($rtOjb['auditScore'], $v['auditScore'], 2);
            $rtOjb['workCoefficient'] = bcadd($rtOjb['workCoefficient'], $v['workCoefficient'], 2);
        }
        return $rtOjb;
    }

    /**
     * ������ͳ�� - �ϼƲ���
     * @param $rows
     * @return array
     */
    function getNewSearchDeptCount_d($rows)
    {
        $rtOjb = array(
            'inWorkRate' => 0,
            'monthScore' => 0,
            'workloadDay' => 0,
            'actHolsDays' => 0,
            'countDay' => 0
        );
        foreach ($rows as $v) {
            $rtOjb['inWorkRate'] = bcadd($rtOjb['inWorkRate'], $v['inWorkRate'], 2);
            $rtOjb['monthScore'] = bcadd($rtOjb['monthScore'], $v['monthScore'], 2);
            $rtOjb['workloadDay'] = bcadd($rtOjb['workloadDay'], $v['workloadDay'], 2);
            $rtOjb['actHolsDays'] = bcadd($rtOjb['actHolsDays'], $v['actHolsDays'], 2);
            $rtOjb['countDay'] = bcadd($rtOjb['countDay'], $v['countDay'], 2);
        }
        return $rtOjb;
    }

    /**
     * ���html
     * @param $rows
     * @return string
     */
    function searchDeptHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>���</th><th>����</th><th>��������</th><th>��Ա�ȼ�</th>' .
                '<th>Ա�����</th><th>��Ŀ����</th><th>��������</th><th>����ϵ��</th><th>ƽ������ϵ��</th><th>�¿���ϵ��</th><th>����ϵ��</th><th>��Ŀ�����</th>' .
                '<th>��չϵ��</th><th>����</th></tr></thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                $i++;
                $html .= "<tr class='tr_even'><td>$i</td>";
                $html .= $v['createId'] == 'noId' ? "<td>$v[createName]</td>" :
                    "<td><a href='javascript:void(0);' onclick='searchDetail(\"$v[createId]\",\"$v[createName]\",\"$v[projectId]\")'>$v[createName]</a></td>";
                $html .= "<td>$v[deptName]</td><td>$v[personLevel]</td><td>$v[userNo]</td>" .
                    "<td>$v[projectName]</td><td>$v[inWorkRate]</td><td>$v[auditScore]</td><td>$v[perAuditScore]</td><td>$v[monthAuditScore]</td>" .
                    "<td>$v[workCoefficient]</td><td>$v[thisProjectProcess] %</td><td>$v[processCoefficient]</td><td>$v[costMoney]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return 'û�в�ѯ������';
        }
    }

    /**
     * ���html
     * @param $rows
     * @return string
     */
    function newSearchDeptHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>���</th>' .
                '<th>����</th><th>Ա�����</th><th>��������</th><th>��Ŀ���</th><th>����Ͷ��</th>' .
                '<th>���˵÷�</th><th>ͳ������</th><th>����ϵ��</th><th>����ϵ��</th><th>���ݼ�����</th></tr></thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                $i++;
                $html .= "<tr class='tr_even'><td>$i</td>";
                $html .= $v['createId'] == 'noId' ? "<td>$v[createName]</td>" :
                    "<td><a href='javascript:void(0);' onclick='searchDetail(\"$v[createId]\",\"$v[createName]\",\"$v[projectId]\")'>$v[createName]</a></td>";
                $html .= "<td>$v[userNo]</td><td>$v[deptName]</td><td>$v[projectCode]</td>" .
                    "<td>$v[inWorkRate]</td><td>$v[monthScore]</td><td>$v[countDay]</td>" .
                    "<td>$v[attendance]</td><td>$v[assess]</td><td>$v[hols]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return 'û�в�ѯ������';
        }
    }

    /**
     * ��ȡ��������Ϣ
     * @param $projectId
     * @return mixed
     */
    function getWeekStatus_d($projectId)
    {
        $this->searchArr = array('projectId' => $projectId);
        $this->groupBy = "date_format(executionDate,'%Y%w'),activityId";
        $this->sort = "date_format(executionDate,'%Y%w') desc,activityId";
        return $this->list_d('select_weekstatus');
    }

    /**
     * ��ȡ��Ŀ��־�Ŀ�ʼ����������
     * @param $projectId
     * @return array
     */
    function getDates_d($projectId)
    {
        if ($projectId) {
            $beginDate = $this->get_table_fields($this->tbl_name, "projectId = $projectId AND executionDate <> '0000-00-00'", "min(executionDate)");
            $endDate = $this->get_table_fields($this->tbl_name, "projectId = $projectId AND executionDate <> '0000-00-00'", "max(executionDate)");
            return array(
                'beginDate' => $beginDate,
                'endDate' => $endDate
            );
        } else {
            return array(
                'beginDate' => '',
                'endDate' => ''
            );
        }
    }

    /**
     * ������Ŀ����Ĺ���ϵ��
     * @param $projectId
     * @param $weekNo
     * @param $score
     * @return mixed
     */
    function updateWorkCoefficient_d($projectId, $weekNo, $score)
    {
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $dateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);
        // ���ֹ��ش���
        $score = $score > 10 ? 10 : $score;
        // ��ȡ��Ŀ����
        $esmmemberDao = new model_engineering_member_esmmember();
        $esmmemberArr = $esmmemberDao->getManagerInProject_d($projectId);
        if ($esmmemberArr) {
            $memberIdArr = array();
            foreach ($esmmemberArr as $v) {
                array_push($memberIdArr, $v['memberId']);
            }
            $memberIds = "'" . implode("','", $memberIdArr) . "'";
            $sql = "update oa_esm_worklog
						set workCoefficient = round(inWorkRate/100 * $score / 10,2)
					where
						createId in ($memberIds) and projectId = '$projectId' and executionDate >= '{$dateInfo['beginDate']}' and executionDate <= '{$dateInfo['endDate']}'";
            return $this->_db->query($sql);
        }
    }

    /**
     * ���¼�����Ŀ����
     * @param $projectId
     */
    function recountProjectProcess_d($projectId)
    {
        $activityDao = self::getObjCache('model_engineering_activity_esmactivity');
        $activityArr = $activityDao->findAll(array('projectId' => $projectId));

        foreach ($activityArr as $val) {
            if ($val['workload']) {
                $workRate = $activityDao->getWorkRate_d($val['id']);
                $sql = "update oa_esm_worklog c set c.thisActivityProcess = round((c.workloadDay/" . $val['workload'] . ")*100,2) , c.thisProjectProcess = "
                    . "round((c.workloadDay/" . $val['workload'] . ")*" . $workRate . "*100,2) where activityId = " . $val['id'];
                $this->_db->query($sql);
            }
        }
    }

    /******************************** ������־���벿�� ***********************/
    /**
     * ������־����
     */
    function costExcelIn_d()
    {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//�������


        $tempArr = array();

        $costTypeDao = new model_finance_expense_costtype();

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);//ִ������
                    $val[1] = trim($val[1]);//���Ա
                    $val[2] = trim($val[2]);//��������
                    $val[3] = trim($val[3]);//���ô���
                    $val[4] = trim($val[4]);//����С��
                    $val[5] = trim($val[5]);//��Ʊ����
                    $val[6] = trim($val[6]);//��Ʊ���
                    $val[7] = trim($val[7]);//��Ʊ����
                    $val[8] = trim($val[8]);//��ע
                    $actNum = $key + 2;
                    $inArr = array();
                    $esmcostdetailArr = array();
                    $invoiceDetailArr = array();
                    //ִ������
                    if (!empty($val[0]) && $val[0] != '0000-00-00' && $val[0] != '') {
                        if (!is_numeric($val[0])) {
                            $inArr['executionDate'] = $val[0];
                        } else {
                            $inArr['executionDate'] = util_excelUtil::exceltimtetophp($val[0]);
                        }
                    } else {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!û����дִ������';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //���Ա
                    if (empty($val[1]) && $val[1] == '') {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!û����д���Ա';
                        array_push($resultArr, $tempArr);
                        continue;
                    }
                    //��������
                    if (!empty($val[2]) && $val[2] != '') {
                        $inArr['activityName'] = $val[2];
                    } else {
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        $tempArr['result'] = '����ʧ��!û����д��������';
                        array_push($resultArr, $tempArr);
                        continue;
                    }

                    //����ִ�����ڣ����Ա�����������жϸù�����־�Ƿ���ڣ������򷵻���������ֵ�������ڲ�����÷�����־
                    if (!empty($val[0]) && !empty($val[1]) && !empty($val[2])) {
                        //��ȡ������־��Ϣ
                        $workLogObj = $this->findAll(array('executionDate' => $inArr['executionDate'], 'createName' => $val[1], 'activityName' => $val[2]), null, 'id,projectId,projectCode,projectName,activityId,confirmStatus,costMoney,feeRegular,invoiceMoney,invoiceNumber');
                        if (is_array($workLogObj)) {
                            //���鵽��ֻһ����־��¼���򱨴�
                            if (count($workLogObj) > 1) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!��ѯ���ظ��Ĺ�����־';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                            $inArr['id'] = $workLogObj['0']['id'];
                            $inArr['projectId'] = $workLogObj['0']['projectId'];
                            $inArr['projectCode'] = $workLogObj['0']['projectCode'];
                            $inArr['projectName'] = $workLogObj['0']['projectName'];
                            $inArr['activityId'] = $workLogObj['0']['activityId'];
                            $inArr['costMoney'] = $workLogObj['0']['costMoney'];
                            $inArr['feeRegular'] = $workLogObj['0']['feeRegular'];
                            $inArr['invoiceMoney'] = $workLogObj['0']['invoiceMoney'];
                            $inArr['invoiceNumber'] = $workLogObj['0']['invoiceNumber'];
                            $inArr['isExcel'] = 1;
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û�в�ѯ����صĹ�����־';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //���ô���
                        if (!empty($val[3]) && $val[3] != '') {
                            //���ݷ����������ƻ�ȡ��Ӧ��id
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[3]);
                            if (!empty($costTypeInfo)) {
                                $esmcostdetailArr['parentCostType'] = $val[3];
                                $esmcostdetailArr['parentCostTypeId'] = $costTypeInfo['CostTypeID'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�÷��ô��಻����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д���ô���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //��Ʊ����id
                        if (!empty($val[5]) && $val[5] != '') {
                            //���ݷ�Ʊ�������ƻ�ȡ��Ӧ��id
                            $sql = "select ID from bill_type where Name='" . $val[5] . "'";
                            $rs = $this->_db->get_one($sql);
                            $invoiceTypeId = $rs['ID'];
                            if (!empty($invoiceTypeId)) {
                                $invoiceDetailArr['0']['invoiceTypeId'] = $invoiceTypeId;
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�÷�Ʊ���Ͳ�����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ʊ����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //��Ʊ���
                        if (!empty($val[6]) && $val[6] != '') {
                            $invoiceDetailArr['0']['invoiceMoney'] = $val[6];
                            //���ý����ֵ�뷢Ʊ���һ��
                            $esmcostdetailArr['costMoney'] = $val[6];
                            //���¹�����־�ĺϼƽ�������ã���Ʊ���
                            $inArr['costMoney'] += $val[6];
                            $inArr['feeRegular'] += $val[6];
                            $inArr['invoiceMoney'] += $val[6];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ʊ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //��Ʊ����
                        if (!empty($val[7]) && $val[7] != '') {
                            $invoiceDetailArr['0']['invoiceNumber'] = $val[7];
                            $inArr['invoiceNumber'] += $val[7];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ʊ����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //��ע
                        if (!empty($val[8]) && $val[8] != '') {
                            $esmcostdetailArr['remark'] = $val[8];
                        }
                        //����С��
                        if (!empty($val[4]) && $val[4] != '') {
                            //���ݷ����������ƻ�ȡ��Ӧ��id
                            $costTypeInfo = $costTypeDao->getIdAndParentIdByName($val[4]);
                            if (!empty($costTypeInfo)) {
                                if ($costTypeInfo['ParentCostTypeID'] != $esmcostdetailArr['parentCostTypeId']) {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!���ô��������С�಻ƥ��';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                                //�÷��������ڣ���ȡ��id,�����·��ý��
                                $esmcostdetailDao = self::getObjCache('model_engineering_cost_esmcostdetail');
                                $esmcostdetailInfo = $esmcostdetailDao->findAll(array('projectId' => $inArr['projectId'], 'worklogId' => $inArr['id'], 'executionDate' => $inArr['executionDate'], 'createName' => $val[1], 'activityName' => $val[2], 'parentCostType' => $val[3], 'costType' => $val[4]), null, 'id,costMoney');
                                if (!empty($esmcostdetailInfo)) {
                                    if (count($esmcostdetailInfo) > 1) {
                                        $tempArr['docCode'] = '��' . $actNum . '������';
                                        $tempArr['result'] = '����ʧ��!��ѯ���ظ��Ĺ�����־';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
                                    $esmcostdetailArr['id'] = $esmcostdetailInfo['0']['id'];
                                    $esmcostdetailArr['costMoney'] = $esmcostdetailInfo['0']['costMoney'] + $val[6];
                                }
                                $esmcostdetailArr['costType'] = $val[4];
                                $esmcostdetailArr['costTypeId'] = $costTypeInfo['CostTypeID'];
                                $esmcostdetailArr['invoiceDetail'] = $invoiceDetailArr;
                                $inArr['esmcostdetail'][$costTypeInfo['CostTypeID']] = $esmcostdetailArr;
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�÷���С�಻����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����С��';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //�ж���־�Ƿ�����
                        if ($workLogObj['0']['confirmStatus'] == 1) {
                            $tempArr['result'] = '����ʧ��!������־����ˣ����ܽ��и���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //���뿪ʼִ��
                        try {
                            $this->start_d();

                            $this->edit_d($inArr);
                            if (!empty($esmcostdetailArr['id'])) {
                                $tempArr['result'] = '���³ɹ�';
                            } else {
                                $tempArr['result'] = '�����ɹ�';
                            }

                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '����ʧ��';
                        }
                        $tempArr['docCode'] = '��' . $actNum . '������';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ��־��¼��ѯ - ����
     * @param $beginDate
     * @param $endDate
     * @return string
     */
    function searchLog_d($beginDate, $endDate)
    {
        $condition = 'confirmStatus = 0 and createId = "' . $_SESSION['USER_ID'] . '" and executionDate between "' . $beginDate . '" and "' . $endDate . '"';
        return $this->get_table_fields($this->tbl_name, $condition, 'COUNT(*)');
    }

    /**
     * ɾ����־
     * @param $beginDate
     * @param $endDate
     * @return bool
     */
    function deleteLog_d($beginDate, $endDate)
    {
        //��ѯ������־
        $this->searchArr = array('createId' => $_SESSION['USER_ID'], 'confirmStatus' => 0, 'beginDateThan' => $beginDate, 'endDateThan' > $endDate);
        $logArr = $this->list_d();
        if (empty($logArr)) return true; // ���û�в�ѯ����־����ֱ�ӷ���

        try {
            $this->start_d();

            //ѭ���鴦�����Ϣ
            $idArr = array();# id
            $projectIdArr = array();# ��Ŀid
            foreach ($logArr as $v) {
                array_push($idArr, $v['id']);
                if ($v['projectId'] && !in_array($v['projectId'], $projectIdArr)) array_push($projectIdArr, $v['projectId']);
            }

            //ɾ����־
            parent::deletes(implode(',', $idArr));

            //������Ŀ��Ϣ
            if (!empty($projectIdArr)) {
                $esmprojectDao = new model_engineering_project_esmproject();//ʵ������Ŀ
                $esmmemberDao = new model_engineering_member_esmmember();//ʵ������Ա
                $esmcostdetailDao = new model_engineering_cost_esmcostdetail();//ʵ����������Ϣ

                //����������Ŀ����
                foreach ($projectIdArr as $v) {
                    //��ȡ����-������־��ͳ����Ϣ
                    $thisCount = $this->getProjectMemberCountInfo_d($v, $_SESSION['USER_ID']);

                    //���������Ա������
                    $esmmemberDao->updateDayInfo_d($v, $_SESSION['USER_ID'], $thisCount['inWorkDay']);

                    //��ȡ��Ŀ���ܷ���
                    $projectPersonFee = $esmmemberDao->getFeePerson_d($v);
                    $esmprojectDao->updateFeePerson_d($v, $projectPersonFee);

                    //��ȡ��ǰ��Ŀ�ķ���
                    $projectCountArr = $esmcostdetailDao->getCostFormMember_d($v);

                    //������Ա������Ϣ
                    $esmmemberDao->update(
                        array('projectId' => $v, 'memberId' => $_SESSION['USER_ID']),
                        $projectCountArr
                    );
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    /********************************����ϵ����ѯ���� ***********************/
    /**
     * ����ϵ����ѯ-��ȡ�б�����
     * @param $object
     * @param bool $getSql
     * @return string
     */
    function getSearchCoefficienList_d($object, $getSql = false)
    {
        $condition = '';
        $workStatusSql = '';
        if (!empty($object['userName'])) {
            $condition = " and c.createName = '" . $object['userName'] . "'";
        }
        if (!empty($object['workStatusArr'])) {
            $workStatus = util_jsonUtil::strBuild($object['workStatusArr']);
            $workStatusSql = " and c.workStatus in($workStatus)";
        }
        $sql = "
	    	SELECT
	    		c.createId,c.createName,c.deptName,p.userNo,c.projectId,c.projectCode,c.projectName,
				round(sum(c.inWorkRate / 100), 2) AS inWorkRate,
				sum(c.workCoefficient) AS workCoefficient,
				round(sum(c.workCoefficient) / sum(c.inWorkRate / 100),2) AS workCoefficientAvg
			FROM
				oa_esm_worklog c LEFT JOIN oa_hr_personnel p ON c.createId = p.userAccount
			WHERE
				c.confirmStatus = '{$object['confirmStatus']}'
				AND
				c.executionDate >= '{$object['beginDateThan']}' and c.executionDate <= '{$object['endDateThan']}'
				$condition
				$workStatusSql
			GROUP BY
    			c.projectId,c.createId";
        return $getSql ? $sql : $this->_db->getArray($sql);
    }

    /**
     * ����ϵ����ѯ-�ϼƲ���
     * @param $rows
     * @return array
     */
    function getSearchCoefficienCount_d($rows)
    {
        $rtOjb = array(
            'inWorkRate' => 0,
            'workCoefficient' => 0,
            'workCoefficientAvg' => 0
        );
        foreach ($rows as $v) {
            $rtOjb['inWorkRate'] = bcadd($rtOjb['inWorkRate'], $v['inWorkRate'], 2);
            $rtOjb['workCoefficient'] = bcadd($rtOjb['workCoefficient'], $v['workCoefficient'], 2);
        }
        $rtOjb['workCoefficientAvg'] = bcdiv($rtOjb['workCoefficient'], $rtOjb['inWorkRate'], 2);
        return $rtOjb;
    }

    /**
     * ����ϵ����ѯ-���html
     * @param $rows
     * @return string
     */
    function searchCoefficientHtml_d($rows)
    {
        if ($rows) {
            $html = '<table class="main_table"><thead><tr class="main_tr_header"><th>���</th><th>����</th><th>��������</th><th>Ա�����</th>' .
                '<th>��Ŀ���</th><th>��Ŀ����</th><th>��������</th><th>����ϵ��</th><th>ƽ������ϵ��</th></tr></thead><tbody>';
            $i = 0;
            foreach ($rows as $v) {
                $i++;
                $html .= "<tr class='tr_even'><td>$i</td><td>$v[createName]</td><td>$v[deptName]</td><td>$v[userNo]</td>";
                $html .= $v['createId'] == 'noId' ? "<td>$v[projectCode]</td>" :
                    "<td><a href='javascript:void(0);' onclick='searchDetail(\"$v[createId]\",\"$v[createName]\",\"$v[projectId]\")'>$v[projectCode]</a></td>";
                $html .= "<td>$v[projectName]</td><td>$v[inWorkRate]</td><td>$v[workCoefficient]</td>" .
                    "<td>$v[workCoefficientAvg]</td></tr>";
            }
            return $html . '</tbody></table>';
        } else {
            return 'û�в�ѯ������';
        }
    }

    /**
     * �ж���־�����Ƿ�������ݼټ�¼
     * @param $rows ,�����ǵ���һ������,Ҳ��������������
     * @return string
     */
    function isInHols_d($rows)
    {
        $sql = "SELECT BeginDT,EndDT FROM hols WHERE UserId = '" . $_SESSION['USER_ID'] . "'";
        $rs = $this->_db->getArray($sql);
        if (empty($rs)) {
            return '';
        } else {
            $str = '';
            if (is_array($rows)) {//��������
                $arr = array();
                foreach ($rows as $val) {
                    if ($val[1] == '����' || $val[1] == '����') {
                        $executionDate = trim($val[0]);
                        // ��ȡ����
                        $executionDate = !is_numeric($executionDate) ? $executionDate : util_excelUtil::exceltimtetophp($executionDate);
                        foreach ($rs as $v) {
                            if ($executionDate >= $v['BeginDT'] && $executionDate <= $v['EndDT']) {
                                array_push($arr, $executionDate);
                            }
                        }
                    }
                }
                if (!empty($arr)) {
                    $str = implode(',', array_unique($arr));
                }
            } else {//��������
                foreach ($rs as $v) {
                    if ($rows >= $v['BeginDT'] && $rows <= $v['EndDT']) {
                        $str = $rows;
                    }
                }
            }
        }
        return $str;
    }

    // ʣ�������Ϣ
    public $lastHols = array();

    /**
     * �������»�ȡ��Ա�����ݼ���Ϣ
     * @param $begin
     * @param $end
     * @param $userIds
     * @return array
     */
    function getHolsHash_d($begin, $end, $userIds = '')
    {
        // ��󷵻صĽ��
        $hash = array();

        // ��ѯ�ű�
        $sql = "SELECT UserId, UNIX_TIMESTAMP(begindt) AS bt, UNIX_TIMESTAMP(enddt) AS et, DTA, beginHalf
            FROM hols WHERE ExaStatus IN('���','��������')
            AND UNIX_TIMESTAMP(enddt) >= " . $begin . " and UNIX_TIMESTAMP(begindt) <= " . $end;

        $sql .= $userIds ? " AND UserId IN(" . util_jsonUtil::strBuild($userIds) . ")" : "";

        $data = $this->_db->getArray($sql);

        if (!empty($data)) {
            foreach ($data as $v) {
                // �����ֻ������״̬�����������뻺��
                if ($v['DTA'] < 1) {
                    $d = date('Y-m-d', $v['bt']);
                    $hash[$v['UserId']][$d] = $v['DTA'];
                } else {
                    // �Ƿ��ʼ״̬
                    $isInitState = true;

                    // ��������
                    $dt = ($v['et'] - $v['bt']) / 86400 + 1;

                    // ����ǰ��쿪ʼ�ģ�����0.5
                    if ($v['beginHalf']) {
                        $dt = $dt - 0.5;
                    }

                    for ($bt = $v['bt']; $bt <= $v['et']; $bt = $bt + 86400) {
                        $d = date('Y-m-d', $bt);

                        // ����뵱���Ѿ������ݼ��ˣ��򲻴���
                        if (isset($hash[$v['UserId']][$d])) {
                            continue;
                        } else {
                            if ($dt >= 1) {
                                if ($isInitState && $v['beginHalf']) {
                                    // ������ݼٴ����翪ʼ�����һ��ȡĦ
                                    $hash[$v['UserId']][$d] = fmod($dt, 1);
                                    $isInitState = false;
                                } else {
                                    // ������ݼٴ����翪ʼ�����һ��ȡĦ
                                    $hash[$v['UserId']][$d] = 1;
                                }

                            } else {
                                $hash[$v['UserId']][$d] = $dt > 0 ? $dt : 0;
                            }
                            // ����ʣ���������
                            $dt = $dt - $hash[$v['UserId']][$d];
                        }
                    }
                }
            }
        }
        $this->lastHols = $hash;

        return $hash;
    }

    /**
     * ��ȡ���˽�� - ������Ա����
     * @param $year
     * @param $month
     * @return array
     */
    function getAssessInfo_d($year, $month)
    {
        $begin = $year . '-' . $month . '-01';
        $end = date('Y-m-t', strtotime($begin));

        return $this->getAssessData_d(array(
            'beginDate' => $begin,
            'endDate' => $end
        ), false, true);
    }

    /**
     * ��ȡ���˽�� - ������Ա����
     * @param $begin string ��ʼ����(YYYY-mm-dd)
     * @param $end string ��������(YYYY-mm-dd)
     * @param $userId string �û�ID
     * @return array
     */
    function getAssessInfoByCond_d($begin, $end, $userId) {
        return $this->getAssessData_d(array(
            'beginDate' => $begin,
            'endDate' => $end,
            'userId' => $userId
        ), false, true);
    }

    /**
     * ��ȡ��Ŀ��������
     * @param $condition
     * @param bool $noHols
     * @param bool $allDept
     * @return array
     */
    function getAssessData_d($condition, $noHols = true, $allDept = false)
    {
        // ��ϸ���ݻ�ȡ
        $assessData = $this->getAssessment_d($condition, $noHols, $allDept);

        // ���ص�����
        $returnData = array();

        if (!empty($assessData)) {
            // �м�����
            $tempData = array();

            // ��������
            $countDay = (strtotime($condition['endDate']) - strtotime($condition['beginDate'])) / 86400 + 1;

            foreach ($assessData as $v) {
                // �������ְ��־����ȡֵ
                if (isset($v['isLeave']) && $v['isLeave']) {
                    continue;
                }
                // �����δ��ְ��־����ȡֵ
                if (isset($v['unEntry']) && $v['unEntry']) {
                    continue;
                }

                // �����־����ػ���δ��ˣ���ͳ��
                if ($v['confirmStatus'] != 1) {
                    continue;
                }

                if (isset($tempData[$v['createId']][$v['projectCode']])) {
                    $tempData[$v['createId']][$v['projectCode']]['inWorkRate'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['inWorkRate'], $v['inWorkRate']);
                    $tempData[$v['createId']][$v['projectCode']]['accessScore'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['accessScore'], $v['accessScore'], 2);
                    $tempData[$v['createId']][$v['projectCode']]['workloadDay'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['workloadDay'], $v['workloadDay'], 2);
                    $tempData[$v['createId']][$v['projectCode']]['hols'] =
                        bcadd($tempData[$v['createId']][$v['projectCode']]['hols'], $v['hols'], 1);
                } else {
                    $tempData[$v['createId']][$v['projectCode']] = array(
                        'projectId' => $v['projectId'],
                        'createName' => $v['createName'],
                        'inWorkRate' => $v['inWorkRate'],
                        'accessScore' => $v['accessScore'],
                        'workloadDay' => $v['workloadDay'],
                        'hols' => $v['hols']
                    );
                }
            }

            // ת��Ϊ��ʽ���������
            foreach ($tempData as $k => $v) {
                foreach ($v as $ki => $vi) {
                    $vi['createId'] = $k;
                    $vi['projectCode'] = $ki;
                    $vi['monthScore'] = $vi['accessScore'];
                    $vi['inWorkRate'] = bcdiv($vi['inWorkRate'], 100, 2);
                    $vi['countDay'] = $countDay;
                    $vi['attendance'] = round(bcdiv($vi['inWorkRate'], $countDay, 3), 2);
                    $vi['assess'] = round(bcdiv($vi['monthScore'], $vi['inWorkRate'], 3), 2);
                    $vi['hols'] = isset($vi['hols']) ? $vi['hols'] : '0.0';
                    $returnData[] = $vi;
                }
            }
        }

        return $returnData;
    }

    /**
     * ��ȡ������ͳ������
     * @param $condition
     * @param bool $noHols �����Ƿ���Ҫ�������ݼ٣�true��ʱ�򲻴���
     * @param bool $allDept �����Ƿ���������Ȩ��
     * @return mixed
     */
    function getAssessment_d($condition, $noHols = true, $allDept = false)
    {
        // ��ȡ������ͳ��
        $data = $this->getAssessmentLog_d($condition, $noHols, $allDept);

        // ���ݼٴ���
        if ($data) {
            $begin = strtotime($condition['beginDate']);
            $end = strtotime($condition['endDate']);

            // ���ݼ�
            $holsInfo = $this->getHolsHash_d($begin, $end);

            // ���˽��
            $assResultDao = new model_engineering_assess_esmassresult();
            $assResultMap = $assResultDao->getResultMap_d();

            // ������ϸ����
            $data = $this->assessmentDetail_d($data, $holsInfo, $assResultMap);
        }

        return $data;
    }

    /**
     * ������־ - key => logs
     * @param $beginDate
     * @param $endDate
     * @param $userIds
     * @return array
     */
    function getAssessmentLogMap_d($beginDate, $endDate, $userIds) {
        // ��ȡ��־
        $logs = $this->getAssessmentLog_d(array('beginDate' => $beginDate, 'endDate' => $endDate, 'userIds' => $userIds),
            false, true, false);

        // ���˽��
        $assResultDao = new model_engineering_assess_esmassresult();
        $assResultMap = $assResultDao->getResultMap_d();

        // ������ϸ����
        $logs = $this->assessmentDetail_d($logs, array(), $assResultMap);

        $data = array();

        foreach ($logs as $v) {
            // ���˵�ִ����־Ϊ 0000-00-00������
            if ($v['executionDate'] == '0000-00-00') {
                continue;
            }
            if (!$data[$v['createId']]) {
                $data[$v['createId']] = array();
            }
            $data[$v['createId']][] = $v;
        }

        return $data;
    }

    /**
     * ��ȡ������
     * @param $condition
     * @param bool|true $noHols
     * @param bool|false $allDept
     * @param bool|true $onlyNormalProject
     * @return array|bool
     */
    function getAssessmentLog_d($condition, $noHols = true, $allDept = false, $onlyNormalProject = true)
    {
        $begin = strtotime($condition['beginDate']);
        $end = strtotime($condition['endDate']);

        $userSql = "";
        if (isset($condition['userName'])) {
            $userName = util_jsonUtil::is_utf8($condition['userName']) ? util_jsonUtil::iconvUTF2GB($condition['userName'])
                : $condition['userName'];
            $userSql = " AND c.createName = '" . $userName . "'";
        }
        if (isset($condition['userId'])) {
            $userSql = " AND c.createId = '" . $condition['userId'] . "'";
        }
        if (isset($condition['userIds'])) {
            $userSql = " AND c.createId IN(" . util_jsonUtil::strBuild($condition['userIds']) . ")";
        }

        // ����д�����Ŀid��������ĿIDΪ׼��������Ժ�ͬ��������Ŀ����ѯ
        if (isset($condition['projectId'])) {
            $projectIdSql = " AND c.projectId = " . $condition['projectId'];
        } else if ($onlyNormalProject) {
            // ��ȡһ����Ŀ��id,���ڹ���
            $projectDao = new model_engineering_project_esmproject();
            $projectIds = $projectDao->getNormalProjectIds_d();
            $projectIdSql = "";
            if ($projectIds) {
                $projectIdSql = "AND c.projectId IN(" . $projectIds . ") ";
            }
        }

        // ����Ȩ�޴���
        $userTbSql = "";
        $deptLimitSql = "";
        $deptLimit = $this->getDeptLimit_d();
        if (!$allDept && $noHols && $deptLimit != 'all' && $deptLimit != '') {
            $userTbSql = "LEFT JOIN user u ON c.createId = u.USER_ID";
            $deptLimitSql = " AND u.DEPT_ID IN(" . $deptLimit . ")";
        }

        $sql = "SELECT
                c.id, c.createId, c.createName, c.executionDate, c.executionTimes, c.projectId, c.projectCode,
                c.province, c.city, c.inWorkRate, c.assessResultName, c.assessResult,
                c.workStatus, c.workloadDay, c.workloadUnitName, c.createTime, c.confirmDate,
                ROUND(s.score) AS score, c.confirmStatus
            FROM
                oa_esm_worklog c
                $userTbSql
                LEFT JOIN
                (
                    SELECT projectId, beginTimes, endTimes, score
                    FROM oa_esm_project_statusreport c
                    WHERE
                        ExaStatus = '���' $projectIdSql AND endTimes >= " . $begin . "
                        AND beginTimes <= " . $end . "
                ) s
                ON c.projectId = s.projectId AND
                    c.executionTimes >= s.beginTimes AND
                    c.executionTimes <= s.endTimes
            WHERE
                1 $userSql $deptLimitSql $projectIdSql AND
                c.executionTimes BETWEEN " . $begin . " AND " . $end . " AND c.executionDate <> '0000-00-00'
            ORDER BY c.executionTimes";
        return $this->_db->getArray($sql);
    }

    /**
     * ������ϸ����
     * @param $data
     * @param $holsInfo
     * @param $assResultMap
     * @return mixed
     */
    function assessmentDetail_d($data, $holsInfo, $assResultMap)
    {
        // ��ȡ��ְ����
        $personnelInfo = $this->getPersonnelInfoMap_d();

        foreach ($data as $k => $v) {
            // ������־�޳�
            if ($v['executionDate'] == '0000-00-00') {
                unset($data[$k]);
                continue;
            }
            // ��ְ����
            if (isset($personnelInfo[$v['createId']]) && $personnelInfo[$v['createId']]['quitTime']
                && $v['executionTimes'] > $personnelInfo[$v['createId']]['quitTime']
            ) {
                $data[$k]['isLeave'] = '1';
            }
            // ��ְ����
            if (isset($personnelInfo[$v['createId']]) && $personnelInfo[$v['createId']]['entryTime']
                && $v['executionTimes'] < $personnelInfo[$v['createId']]['entryTime']
            ) {
                $data[$k]['unEntry'] = '1';
            }

            // ��ʼ����Ͷ��
            $inWorkRate = bcdiv($v['inWorkRate'], 100, 2);

            // ����������ݼ٣���۳��������
            if (isset($holsInfo[$v['createId']]) && isset($holsInfo[$v['createId']][$v['executionDate']])) {
                // ����������1����ǰȫ��
                if ($holsInfo[$v['createId']][$v['executionDate']] == 1) {
                    $inWorkRate = 0;
                    // ����������100%���������˰���ٵ�ʱ�򣬿۳���ٹ�����
                } else if ($inWorkRate == 1 && $holsInfo[$v['createId']][$v['executionDate']] < 1) {
                    $inWorkRate = $inWorkRate - $holsInfo[$v['createId']][$v['executionDate']];
                }
                // ���˵÷ּ���
                $data[$k]['hols'] = $holsInfo[$v['createId']][$v['executionDate']];

                // �����־���������٣����޳���һ��������
                $this->lastHols[$v['createId']][$v['executionDate']] = $this->lastHols[$v['createId']][$v['executionDate']] - $inWorkRate;
                if ($this->lastHols[$v['createId']][$v['executionDate']] == 0) {
                    unset($this->lastHols[$v['createId']][$v['executionDate']]);
                }
            }

            // Ͷ�������ֵ
            $data[$k]['inWorkRate'] = $inWorkRate * 100;

            // û���ύ�ܱ���0��
            $v['score'] = $v['score'] === null ? 0 : $v['score'];

            // �÷��������
            $scoreIndex = 'score_' . $v['score'];

            // ���˵÷ּ���
            $data[$k]['accessScore'] = $assResultMap[$v['assessResult']][$scoreIndex];

            // ���˷���
            $data[$k]['accessScore'] = bcmul($data[$k]['accessScore'], $inWorkRate, 2);
        }

        return $data;
    }

    /**
     * ����Ȩ���ڵĲ��ţ�all���У�ids���ֲ��ţ����ַ�������Ȩ��
     * @return array
     */
    function getDeptLimit_d()
    {
        //����Ȩ��
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);

        if (isset($sysLimit['����Ȩ��'])) {
            if (empty($sysLimit['����Ȩ��'])) {
                return '';
            } else if (strpos($sysLimit['����Ȩ��'], ';;') !== false) {
                return 'all';
            } else {
                return $sysLimit['����Ȩ��'];
            }
        } else {
            return '';
        }
    }

    /**
     * ��ȡ�û���Ϣ
     * @param string $userId
     * @return array
     */
    function getPersonnelInfo_d($userId = "")
    {
        $userId = $userId ? $userId : $_SESSION['USER_ID'];
        // ��ȡ��ְ����
        $personInfo = $this->_db->get_one("SELECT entryDate, quitDate FROM oa_hr_personnel WHERE userAccount = '" .
            $userId . "'");
        if ($personInfo['entryDate'] && $personInfo['entryDate'] != "" && $personInfo['entryDate'] != "0000-00-00") {
            $personInfo['entryTime'] = strtotime($personInfo['entryDate']);
        }
        if ($personInfo['quitDate'] && $personInfo['quitDate'] != "" && $personInfo['quitDate'] != "0000-00-00") {
            $personInfo['quitTime'] = strtotime($personInfo['quitDate']);
        }
        return $personInfo;
    }

    /**
     * ��ȡ�û���Ϣ - map
     * @param string $userIds
     * @return array
     */
    function getPersonnelInfoMap_d($userIds = '')
    {
        $sql = "SELECT userAccount, entryDate, quitDate FROM oa_hr_personnel";
        if ($userIds) {
            $sql .= " WHERE userAccount IN(" . util_jsonUtil::strBuild($userIds) . ")";
        }
        // ��ȡ��ְ����
        $data = $this->_db->getArray($sql);

        $result = array();
        if ($data) {
            foreach ($data as $v) {
                $result[$v['userAccount']] = array(
                    'entryTime' => $v['entryDate'] && $v['entryDate'] != '0000-00-00' ? strtotime($v['entryDate']) : 0,
                    'quitTime' => $v['quitDate'] && $v['quitDate'] != '0000-00-00' ? strtotime($v['quitDate']) : 0
                );
            }
        }
        return $result;
    }
}