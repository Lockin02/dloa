<?php

/**
 * @author Show
 * @Date 2011��12��3�� ������ 14:17:32
 * @version 1.0
 * @description:��Ŀ������뵥Model��
 */
class model_engineering_change_esmchange extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_esm_change_baseinfo";
        $this->sql_map = "engineering/change/esmchangeSql.php";
        parent::__construct();
    }

    /**************************** ��ɾ�Ĳ� *****************************/
    /**
     * @param $object
     * @return bool
     */
    function add_d($object) {
        //�ֶ����
        $object['ExaStatus'] = WAITAUDIT;
        $object['applyId'] = $_SESSION['USER_ID'];
        $object['applyName'] = $_SESSION['USERNAME'];
        $object['applyDate'] = day_date;

        try {
            //����
            $newId = parent::add_d($object, true);
            return $newId;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * ���������޸Ķ���
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        try {
            //�޸�
            parent::edit_d($object, true);
            return $object['id'];
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * ��ȡ���id
     * @param $projectId
     * @param bool $isCreateNew
     * @return bool
     * @throws Exception
     */
    function getChangeId_d($projectId, $isCreateNew = true) {
        $changeId = $this->hasChangeProject_d($projectId);
        if ($changeId) {
            return $changeId;
        } else if ($isCreateNew) {
            //���´���һ�������
            try {
                $this->start_d();

                //��Ŀ������Ϣ����
                $esmprojectDao = new model_engineering_project_esmproject();
                $esmprojectObj = $esmprojectDao->get_d($projectId);
                //���������������
                $esmchangeObj = array(
                    'projectId' => $esmprojectObj['id'], 'projectCode' => $esmprojectObj['projectCode'], 'projectName' => $esmprojectObj['projectName'],
                    'orgBudgetAll' => $esmprojectObj['budgetAll'], 'orgBudgetField' => $esmprojectObj['budgetField'], 'orgBudgetOutsourcing' => $esmprojectObj['budgetOutsourcing'],
                    'orgBudgetOther' => $esmprojectObj['budgetOther'], 'orgBudgetPerson' => $esmprojectObj['budgetPerson'], 'orgBudgetPeople' => $esmprojectObj['budgetPeople'],
                    'orgBudgetDay' => $esmprojectObj['budgetDay'], 'orgBudgetEqu' => $esmprojectObj['budgetEqu'], 'newBudgetAll' => $esmprojectObj['budgetAll'],
                    'newBudgetField' => $esmprojectObj['budgetField'], 'newBudgetOutsourcing' => $esmprojectObj['budgetOutsourcing'], 'newBudgetOther' => $esmprojectObj['budgetOther'],
                    'newBudgetPerson' => $esmprojectObj['budgetPerson'], 'newBudgetPeople' => $esmprojectObj['budgetPeople'], 'newBudgetDay' => $esmprojectObj['budgetDay'],
                    'newBudgetEqu' => $esmprojectObj['budgetEqu'], 'planBeginDate' => $esmprojectObj['planBeginDate'], 'planEndDate' => $esmprojectObj['planEndDate'],
                    'expectedDuration' => $esmprojectObj['expectedDuration'], 'actBeginDate' => $esmprojectObj['actBeginDate'],
                    'actEndDate' => $esmprojectObj['actEndDate'],'orgPlanEndDate' => $esmprojectObj['planEndDate'],'newPlanEndDate' => $esmprojectObj['planEndDate'],
                    'actDuration' => $esmprojectObj['actDuration'], 'officeId' => $esmprojectObj['officeId'], 'officeName' => $esmprojectObj['officeName'],
                    'provinceId' => $esmprojectObj['provinceId'], 'city' => $esmprojectObj['city'], 'cityId' => $esmprojectObj['cityId'],
                    'country' => $esmprojectObj['country'], 'countryId' => $esmprojectObj['countryId'], 'province' => $esmprojectObj['province'],
                    'place' => $esmprojectObj['place'], 'applyDate' => day_date,
                    'applyId' => $_SESSION['USER_ID'], 'applyName' => $_SESSION['USERNAME'],
                    'ExaStatus' => '���ύ'
                );
                //����������뵥
                $newId = parent::add_d($esmchangeObj, true);

                //��Ŀ���񲿷�
                $esmactivityDao = new model_engineering_activity_esmactivity();
                //��������ڵ���Ϣ
                $esmactivityDao->initChangeInfo_d($projectId);
                $esmactivityArr = $esmactivityDao->getProjectActivity_d($projectId);
                $esmchangeactDao = new model_engineering_change_esmchangeact();
                $esmchangeactDao->createActivity_d($esmactivityArr, $newId, $esmprojectObj);

                //��ĿԤ�㲿��
                $esmbudgetDao = new model_engineering_budget_esmbudget();
                $esmbudgetArr = $esmbudgetDao->getProjectBudgetInfo_d($projectId);
                if ($esmbudgetArr) {
                    $esmchangebudDao = new model_engineering_change_esmchangebud();
                    $esmchangebudDao->createBudget_d($esmbudgetArr, $newId);
                }

                //�豸Ԥ�㲿��
                $esmresourcesDao = new model_engineering_resources_esmresources();
                $esmresourcesArr = $esmresourcesDao->getProjectResources_d($projectId);
                if ($esmresourcesArr) {
                    $esmchangeresDao = new model_engineering_change_esmchangeres();
                    $esmchangeresDao->createResources_d($esmresourcesArr, $newId);
                }

                $this->commit_d();
                return $newId;
            } catch (Exception $e) {
                $this->rollBack();
                throw $e;
            }
        } else {
            return false;
        }
    }

    /**
     * ��дɾ������
     * @param $id
     * @return bool|void
     * @throws Exception
     */
    function deletes_d($id) {
        try {
            $this->start_d();

            //��ȡ��ǰ����
            $obj = $this->get_d($id);

            //ɾ������
            $this->deletes($id);

            //��ԭ������״̬
            $esmactivityDao = new model_engineering_activity_esmactivity();
            $esmactivityDao->revertChangeInfo_d($obj['projectId']);

            //��ԭ�豸Ԥ����״̬
            $esmresourcesDao = new model_engineering_resources_esmresources();
            $esmresourcesDao->revertChangeInfo_d($obj['projectId']);

            //��ԭ��ĿԤ����״̬
            $esmbudgetDao = new model_engineering_budget_esmbudget();
            $esmbudgetDao->revertChangeInfo_d($obj['projectId']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /*********************************ҵ����÷���**********************************/

    /**
     * �ж��Ƿ��Ѵ�����Ŀ�������
     * @param $projectId
     * @return bool
     */
    function hasChangeProject_d($projectId) {
        $this->searchArr = array('ExaStatusIn' => '���ύ,��������,���', 'projectId' => $projectId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            return $rs[0]['id'];
        } else {
            return false;
        }
    }

    /**
     * ��ȡ��Ŀ�������������Ϣ
     * @param $id
     * @return bool|mixed
     */
    function getObjInfo_d($id) {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->get_d($id);
    }

    /**
     * ��ȡ�����¼˵��
     * @param $projectId
     * @return null|string
     */
    function getChangDescription_d($projectId) {
        $this->searchArr = array('ExaStatusNo' => '���ύ', 'projectId' => $projectId);
        $rs = $this->list_d();
        if (is_array($rs)) {
            $str = null;
            foreach ($rs as $val) {
                $str .= '�����ˣ�' . $val['applyName'] . ' ,�������� ��' . $val['applyDate'] . ' ,����״�� ��' . $val['ExaStatus'] . " \n"
                    . $val['changeDescription'] . " \n\n";;
            }
            return $str;
        } else {
            return null;
        }
    }

    /**
     * ��Ŀ��������
     * @param $spid
     * @throws Exception
     */
    function dealAfterAudit_d($spid) {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $id = $folowInfo ['objId'];
        //��ȡ������뵥
        $object = $this->get_d($id);
        if ($object['ExaStatus'] == AUDITED) {
            $this->setChange_d($id, $object);
        }
    }

    /**
     * ��Ŀ�������
     * @param $id
     * @param null $object
     * @return bool
     */
    function setChange_d($id, $object = null) {
        try {
            $this->start_d();

            //����д�����Ŀ��Ϣ��˵���Ǳ����������
            if (!$object) {
                //��ʼ��������뵥��Ϣ
                $object = $this->get_d($id);
                //���µ�ǰ������뵥״̬
                $this->update(array('id' => $id), array('projectStatus' => 1, 'ExaStatus' => AUDITED));
            }

            //���������ݸ��µ���Ŀ��
            $esmprojectDao = new model_engineering_project_esmproject();

            //���µ���Ԥ��
            $updateArr = array(
                'id' => $object['projectId'], 'budgetAll' => $object['newBudgetAll'], 'budgetField' => $object['newBudgetField'],
                'budgetOther' => $object['newBudgetOther'], 'budgetOutsourcing' => $object['newBudgetOutsourcing'],
                'budgetPerson' => $object['newBudgetPerson'], 'budgetPeople' => $object['newBudgetPeople'],
                'budgetDay' => $object['newBudgetDay'], 'budgetEqu' => $object['newBudgetEqu'],
                'planBeginDate' => $object['planBeginDate'], 'planEndDate' => $object['planEndDate'],
                'expectedDuration' => $object['expectedDuration'], 'actDuration' => $object['actDuration'],
                'country' => $object['country'], 'countryId' => $object['countryId'],
                'province' => $object['province'], 'provinceId' => $object['provinceId'],
                'city' => $object['city'], 'cityId' => $object['cityId'], 'place' => $object['place'],
                'status' => 'GCXMZT02'
            );

            //����ʼʱ�䲻Ϊ��ʱ,������Ŀʵ�ʿ�ʼʱ��
            if ($object['actBeginDate'] != '0000-00-00' && !empty($object['actBeginDate'])) {
                $updateArr['actBeginDate'] = $object['actBeginDate'];
            }

            //������ʱ�䲻Ϊ��ʱ,������Ŀʵ�ʽ���ʱ��
            if ($object['actEndDate'] != '0000-00-00' && !empty($object['actEndDate'])) {
                $updateArr['actEndDate'] = $object['actEndDate'];
            }

            $esmprojectDao->edit_d($updateArr);

            //���¼�����Ŀ���ý���
            $esmprojectDao->calFeeProcess_d($object['projectId']);

            /***************** ��Ŀ������� *********************/
            $esmchangeactDao = new model_engineering_change_esmchangeact();
            $allChangeActArr = $esmchangeactDao->getChangeArr_d($id);//��ȡȫ������
            if ($allChangeActArr) {
                $parentId = $esmchangeactDao->getChangeRoot_d($id);
                $esmchangeactArr = array();//�����˱��������
                $cacheArr = array($parentId => -1);//��������id�ı� ���id => ��ʽid
                foreach ($allChangeActArr as $val) {
                    if ($val['activityId']) {//�����ϼ�ID����
                        $cacheArr[$val['id']] = $val['activityId'];
                    }
                    if ($val['isChanging'] == 1) {//���÷������������
                        array_push($esmchangeactArr, $val);
                    }
                }

                if ($esmchangeactArr) {//����б�������ݣ������������
                    $esmactivityDao = new model_engineering_activity_esmactivity();
                    $esmworklogDao = new model_engineering_worklog_esmworklog();
                    $deleteArr = array();//��ɾ������������
                    foreach ($esmchangeactArr as $val) {
                        //���������
                        if ($val['changeAction'] == 'add') {

                            //�л����ڵ�
                            $val['parentId'] = $cacheArr[$val['parentId']];

                            $addArr = $val;
                            unset($addArr['id']);
                            unset($addArr['lft']);
                            unset($addArr['rgt']);
                            $addArr['isChanging'] = 0;
                            $addArr['changeAction'] = '';
                            $activityId = $esmactivityDao->addOrg_d($addArr);

                            //������ʽid
                            $cacheArr[$val['id']] = $activityId;

                        } elseif ($val['changeAction'] == 'edit') {
                            //�л����ڵ�
                            $val['parentId'] = $cacheArr[$val['parentId']];
                            $updateArr = array(
                                'id' => $val['activityId'], 'activityName' => $val['activityName'], 'parentId' => $val['parentId'], 'lft' => $val['lft'], 'rgt' => $val['rgt'],
                                'workRate' => $val['workRate'], 'workload' => $val['workload'], 'workloadUnit' => $val['workloadUnit'], 'workloadUnitName' => $val['workloadUnitName'],
                                'planBeginDate' => $val['planBeginDate'], 'planEndDate' => $val['planEndDate'], 'days' => $val['days'],
                                'workContent' => $val['workContent'], 'remark' => $val['remark'],
                                'isChanging' => 0, 'changeAction' => ''
                            );
                            $esmactivityDao->editOrg_d($updateArr);
                        } elseif ($val['changeAction'] == 'delete') {
                            array_push($deleteArr, $val['activityId']);
                        }
                    }
                    //ɾ������
                    if (!empty($deleteArr)) {
                        $esmactivityDao->deletes_d(implode(',', $deleteArr));
                    }
                    //��ԭ����
                    $esmchangeactDao->rollBackChangeInfo_d($id);

                    //����һ�����½��ȵķ���
                    $esmactivityDao->recountProjectProcess_d($object['projectId']);

                    //������־����Ŀ����
                    $esmworklogDao->recountProjectProcess_d($object['projectId']);
                }
            }

            /***************** Ԥ����ϸ���� *********************/
            //ʵ��������Ԥ��
            $esmchangebudDao = new model_engineering_change_esmchangebud();
            $esmchangebudArr = $esmchangebudDao->getChangeBudget_d($id, '1');
            if ($esmchangebudArr) {
                $esmbudgetDao = new model_engineering_budget_esmbudget();
                $deleteArr = array();
                foreach ($esmchangebudArr as $val) {
                    //���������
                    if ($val['changeAction'] == 'add') {
                        $addArr = $val;
                        unset($addArr['id']);
                        unset($addArr['activityId']);
                        unset($addArr['activityName']);
                        $addArr['isChanging'] = 0;
                        $addArr['changeAction'] = '';
                        $esmbudgetDao->addOrg_d($addArr);
                    } elseif ($val['changeAction'] == 'edit') {
                        $updateArr = array(
                            'id' => $val['orgId'], 'price' => $val['price'], 'numberOne' => $val['numberOne'], 'numberTwo' => $val['numberTwo'],
                            'amount' => $val['amount'], 'remark' => $val['remark'], 'coefficient' => $val['coefficient'], 'budgetDay' => $val['budgetDay'],
                            'budgetPeople' => $val['budgetPeople'], 'planBeginDate' => $val['planBeginDate'], 'planEndDate' => $val['planEndDate'],
                            'isChanging' => 0, 'changeAction' => ''
                        );
                        $esmbudgetDao->editOrg_d($updateArr);
                    } elseif ($val['changeAction'] == 'delete') {
                        array_push($deleteArr, $val['orgId']);
                    }
                }
                //ɾ������
                if (!empty($deleteArr)) {
                    $esmbudgetDao->deletes(implode(',', $deleteArr));
                }
                //��ԭ����
                $esmchangebudDao->rollBackChangeInfo_d($id);
            }

            /**************** �豸Ԥ����� ********************/
            //�豸Ԥ�����
            $esmchangeresDao = new model_engineering_change_esmchangeres();
            $esmchangeresArr = $esmchangeresDao->getChangeArr_d($id, '1');
            if ($esmchangeresArr) {
                $esmresourcesDao = new model_engineering_resources_esmresources();
                $deleteArr = array();
                foreach ($esmchangeresArr as $val) {
                    //���������
                    if ($val['changeAction'] == 'add') {
                        $addArr = $val;
                        unset($addArr['id']);
                        unset($addArr['activityId']);
                        unset($addArr['activityName']);
                        $addArr['isChanging'] = 0;
                        $addArr['changeAction'] = '';
                        $esmresourcesDao->addOrg_d($addArr);
                    } elseif ($val['changeAction'] == 'edit') {
                        $updateArr = array(
                            'id' => $val['orgId'], 'price' => $val['price'], 'number' => $val['number'],
                            'unit' => $val['unit'], 'amount' => $val['amount'], 'remark' => $val['remark'],
                            'planBeginDate' => $val['planBeginDate'], 'planEndDate' => $val['planEndDate'],
                            'useDays' => $val['useDays'], 'isChanging' => 0, 'changeAction' => ''
                        );
                        $esmresourcesDao->editOrg_d($updateArr);
                    } elseif ($val['changeAction'] == 'delete') {
                        array_push($deleteArr, $val['orgId']);
                    }
                }
                //ɾ������
                if (!empty($deleteArr)) {
                    $esmresourcesDao->deletes(implode(',', $deleteArr));
                }
                //��ԭ����
                $esmchangeresDao->rollBackChangeInfo_d($id);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ȡԭ����
     * @param $projectId
     * @return bool|mixed
     */
    function getProjectForChange_d($projectId) {
        //��ȡ��Ŀ��Ϣ
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->get_d($projectId);
    }

    /**
     * ��ȡ�鿴��Ϣ
     * @param $id
     * @return bool|mixed
     */
    function getViewInfo_d($id) {
        $obj = parent::get_d($id);

        //��ȡ�����Χ
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        $obj['esmactivity'] = $esmchangeactDao->initView_d($id);

        return $obj;
    }

    /**
     * ��ȡ�鿴��Ϣ
     * @param $id
     * @return bool|mixed
     */
    function getEditInfo_d($id) {
        $obj = parent::get_d($id);

        //��ȡ�����Χ
        $esmchangeactDao = new model_engineering_change_esmchangeact();
        $obj['esmactivity'] = $esmchangeactDao->initEdit_d($id);

        return $obj;
    }

    /**
     * �жϵ�ǰ��¼���Ƿ���Ŀ����
     * @param $projectId
     * @return int
     */
    function isManager_d($projectId) {
        $esmproject = $this->getObjInfo_d($projectId);
        $esmManagerIdArr = explode(',', $esmproject['managerId']);
        if (in_array($_SESSION['USER_ID'], $esmManagerIdArr)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * �жϱ��״̬
     * @param $projectId
     * @return bool|int
     */
    function hasChangeInfo_d($projectId) {
        $this->searchArr = array('ExaStatusNo' => '���', 'projectId' => $projectId);
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
     * ��ȡ��Ŀ�Ķ�Ӧ��Χid
     * @param $projectId
     * @return mixed
     */
    function getRangeId_d($projectId) {
        $esmprojectDao = new model_engineering_project_esmproject();
        return $esmprojectDao->getRangeId_d($projectId);
    }

    /**
     * ���±������Ľ��
     * @param $updateArr
     * @return bool
     */
    function updateChangeBudget_d($updateArr) {
        try {
            $this->start_d();

            //�������id
            $changeId = $updateArr['id'];
            unset($updateArr['id']);

            //���µ���
            $this->update(array('id' => $changeId), $updateArr);

            //������Ԥ��
            $this->calFeeProcess_d($changeId);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���¼�����Ŀ���ý���
     * @param $id
     * @throws Exception
     */
    function calFeeProcess_d($id) {
        try {
            return $this->_db->query("UPDATE " . $this->tbl_name . " SET newBudgetAll = newBudgetEqu + newBudgetField +
                newBudgetPerson + newBudgetOther + newBudgetOutsourcing,
                orgBudgetAll = orgBudgetEqu + orgBudgetField + orgBudgetPerson + orgBudgetOther + orgBudgetOutsourcing 
                WHERE id = " . $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ��Ŀ���״̬
     * @param $projectId
     * @return bool|mixed
     */
    function getState_d($projectId) {
        return $this->find(array('ExaStatus' => '���ύ', 'projectId' => $projectId));
    }
}