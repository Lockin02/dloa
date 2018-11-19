<?php

/**
 * @author Show
 * @Date 2011��11��24�� ������ 17:20:15
 * @version 1.0
 * @description:������Ŀ(oa_esm_project) Model��
 *
 * ���ں�ͬ�����Ϣ˵��
 * contractId (Դ��id)
 * contractCode (Դ�����)
 * contractType (Դ������)
 * rObjCode (ҵ����)
 *
 */
class model_engineering_project_esmproject extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_esm_project";
        $this->sql_map = "engineering/project/esmprojectSql.php";
        parent::__construct();
    }

    //��һ�����󻺴� -- ��Ϊ�����ʱ��ķѵ�����̫�࣬����Ҫ�Ż�
    public static $initObjCache = array();

    //��ȡ���󻺴�ķ���
    public static function getObjCache($className)
    {
        if (empty(self::$initObjCache[$className])) {
            self::$initObjCache[$className] = new $className();
        }
        return self::$initObjCache[$className];
    }

    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
        'attribute', 'contractType', 'outsourcingType',
        'nature2', 'nature', 'cycle', 'category', 'platform', 'status',
        'net', 'createType', 'outsourcing', 'signType', 'customerType',
        'newProLine', 'productLine'
    );

    /********************** ���Բ��� **********************/

    // ��ʵ������
    public $initContractType = array(
        'GCXMYD-01', 'GCXMYD-04'
    );

    //��ͬ����������������,������Ҫ���������׷��
    private $relatedStrategyArr = array(
        'GCXMYD-01' => 'model_engineering_project_strategy_sContract', //������ͬ
        'GCXMYD-04' => 'model_engineering_project_strategy_sTrialproject' //������Ŀ����
    );

    /**
     * �����������ͷ�����
     * @param $objType
     * @return string
     */
    public function getClass($objType)
    {
        return $this->relatedStrategyArr[$objType];
    }

    //��Ӧҵ�����
    private $relatedCode = array(
        'GCXMYD-01' => 'contract', //������ͬ
        'GCXMYD-04' => 'trialproject' //������Ŀ
    );

    /**
     * �������ͷ���ҵ������
     * @param $objType
     * @return string
     */
    public function getBusinessCode($objType)
    {
        return $this->relatedCode[$objType];
    }

    /**
     * Դ����Ӧ��ͬ��������
     */
    private $relatedSourceAttribute = array(
        'GCXMYD-01' => 'GCXMSS-02', //������ͬ
        'GCXMYD-04' => 'GCXMSS-01' //������Ŀ
    );

    /**
     * ����Դ��������Ŀ����
     * @param $objType
     * @return string
     */
    public function getAttributeCode($objType)
    {
        return $this->relatedSourceAttribute[$objType];
    }

    /**
     * ��ȡ������Ϣ
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function getObjInfo_d($obj, iesmproject $strategy)
    {
        return $strategy->getObjInfo_i($obj);
    }

    /**
     * ��ȡ���߽�� - ��ʱֻ�����ں�ͬ
     * @param $contractId
     * @param iesmproject $strategy
     * @return mixed
     */
    public function getContractMoney_d($contractId, iesmproject $strategy)
    {
        return $strategy->getContractMoney_i($contractId);
    }

    /**
     * ��ȡԭʼ������Ϣ
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function getRawObjInfo_d($obj, iesmproject $strategy)
    {
        return $strategy->getRawInfo_i($obj['contractId']);
    }

    /**
     * ��������ҵ����
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessAdd_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessAdd_i($obj, $this) : true;
    }

    /**
     * ����ȷ��ҵ������
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessConfirm_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessConfirm_i($obj, $this) : true;
    }

    /**
     * ɾ������ҵ����
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessDelete_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessDelete_i($obj, $this) : true;
    }

    /**
     * �رշ���ҵ����
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessClose_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessClose_i($obj, $this) : true;
    }

    /**
     * ��ȡ������Ŀ�³̵ĺ�ͬ��Ϣ
     * @param $obj
     * @param iesmproject $strategy
     * @return mixed
     */
    public function businessForCharter_d($obj, iesmproject $strategy)
    {
        return $obj['contractId'] && $obj['contractType'] ? $strategy->businessForCharter_i($obj, $this) : true;
    }

    /**
     * ��ͬҵ����
     * ������Ŀ��Ϣ
     * @param $object
     * @return mixed
     */
    function businessDeal_d($object)
    {
        try {
            $this->start_d();

            //��ȡ���µ�һ����Ŀ��״̬
            $rs = $this->find(array('id' => $object['id']), 'createTime', 'status');
            if (!is_array($rs)) {
                $rs = array(
                    'status' => 'GCXMZT00'
                );
            }

            $contractDao = new model_contract_contract_contract();
            $contractDao->update(array('objCode' => $object['rObjCode']), array('projectStatus' => $rs['status']));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }
    /********************** ���Բ��� **********************/

    /****************************ҵ�񷽷�***************************/

    /**
     * ��д��������
     * @param $object
     * @return mixed
     */
    function add_d($object)
    {
        //�����ֵ�
        $object = $this->processDatadict($object);
        return parent::add_d($object, true);
    }

    /**
     * ��Ŀ�������� - ����ҵ����
     * @param $object
     * @param boolean $isTransaction
     * @return mixed
     * @throws $e
     */
    function addProject_d($object, $isTransaction = false)
    {
        //������Ŀ�����ɫ����
        $roleDao = new model_engineering_role_esmrole();
        try {
            if ($isTransaction == true) {
                $this->start_d();
            }
            //�趨��ʼ��Ϣ
            $object['ExaStatus'] = WAITAUDIT;
            $object['status'] = 'GCXMZT01';

            //�����ֵ�
            $object = $this->processDatadict($object);

            //����Դ��ʡ���Զ���ѯ������
            if (empty($object['areaManagerId']) && !empty($object['province'])) {
                $managerDao = new model_engineering_officeinfo_manager();
                $provinceObj = $managerDao->getManager_d($object['province'], $object['businessBelong']);
                $object['areaManagerId'] = $provinceObj['areaManagerId'];
                $object['areaManager'] = $provinceObj['areaManager'];
            }

            //������Ŀ
            $newId = $this->add_d($object);
            $object['id'] = $newId;
            $managerRole = array(
                'projectCode' => $object['projectCode'],
                'projectName' => $object['projectName'],
                'projectId' => $newId,
                'memberId' => $object['managerId'],
                'memberName' => $object['managerName'],
                'roleName' => '��Ŀ����',
                'isManager' => '1',
                'parentId' => -1
            );
            $roleDao->addRoleAndMember_d($managerRole);

            //�����ͬ�й�����������Ŀ������һ����Ŀ����
            if ($object['contractType'] == 'GCXMYD-01') {
                $trialproject = new model_projectmanagent_trialproject_trialproject();
                $trialStr = $trialproject->getTrialIdByconId($object['contractId']);

                if ($trialStr != null) {
                    $this->getParam(array('trialStr' => $trialStr, 'contractType' => 'GCXMYD-04', 'newProLine' => $object['newProLine']));
                    $trialInfo = $this->list_d();
                    //�������������Ŀ��������
                    if ($trialInfo) {
                        $esmactivityDao = new model_engineering_activity_esmactivity(); // ʵ����������Ŀ����
                        $esmmappingDao = new model_engineering_project_esmmapping(); // ʵ����PK��Ŀӳ�����
                        foreach ($trialInfo as $v) {
                            // ������Ŀ����
                            $esmactivityDao->addOrg_d(array('projectId' => $newId, 'projectCode' => $object['projectCode'],
                                'projectName' => $object['projectName'], 'activityName' => $v['projectName'],
                                'workRate' => 0.00, 'parentId' => -1, 'isTrial' => 1, 'triProjectId' => $v['id'],
                                'workContent' => '��ͬ����PK��Ŀ��' . $v['projectCode'] . '��', 'planBeginDate' => $v['planBeginDate'],
                                'planEndDate' => $v['planEndDate'], 'process' => 100, 'days' => $v['expectedDuration'],
                                'workLoadUnit' => 'GCGZLDW-00', 'workloadUnitName' => '��'
                            ));

                            // ����ӳ���ϵ
                            $esmmappingDao->add_d(array('projectId' => $newId, 'pkProjectId' => $v['id']));
                        }
                    }
                }
            }

            //������ҵ����
            //���ò���
            if (in_array($object['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessAdd_d($object, $initObj);
                }
            }

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($newId, '�½�');

            //ͬ��project_info����Ŀ
            $this->updateProjectInfo_p($object);
            if ($isTransaction == true) {
                $this->commit_d();
            }
            return $newId;
        } catch (Exception $e) {
            if ($isTransaction == true) {
                $this->rollBack();
            }
            throw $e;
        }
    }

    /**
     * ��д�޸ķ���
     * @param $object
     * @return mixed
     */
    function edit_d($object)
    {
        $object = $this->processDatadict($object);//�����ֵ�
        return parent::edit_d($object, true);
    }

    /**
     * ����༭����
     * @param $object
     * @return mixed
     */
    function editRight_d($object)
    {
        //�����ֵ�
        $object = $this->processDatadict($object);

        // ��������ֶ�
        $keyArr = array('projectName' => '��Ŀ����', 'managerName' => '��Ŀ����', 'productLineName' => 'ִ������',
            'outsourcingName' => '�������', 'officeName' => '��������', 'deptName' => '��������',
            'categoryName' => '��Ŀ���', 'customerLinkMan' => '�ͻ�����', 'country' => '����',
            'province' => 'ʡ��', 'city' => '����', 'projectObjectives' => '��ĿĿ��', 'description' => '��ĿʵʩҪ��',
            'workDescription' => '��Ŀ����������', 'statusName' => '��Ŀ״̬');

        // ��Ŀԭ��
        $orgObject = $this->find(array('id' => $object['id']), null,
            implode(',', array_keys($keyArr)));

        try {
            $this->start_d();

            //��Ŀ�����ִ��� ����޸�����Ŀ���������Ŀ��Ա�б��е���Ŀ��������޸�
            if (isset($object['orgManagerId']) && $object['orgManagerId'] != $object['managerId']) {
                //�����Ŀ����
                $esmroleDao = new model_engineering_role_esmrole();
                $esmroleDao->changeManager_d($object);

                //���ñ����Ŀ����ķ���
                $this->updateProjectInfo_p($object);
            }

            unset($object['orgManagerId']);
            unset($object['orgManagerName']);

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '������Ա�޸�', $esmlogDao->showDiff_d($keyArr, $orgObject, $object));

            //�༭
            parent::edit_d($object, true);

            //���Ѿ����ó�ʼ��������ò���
            if (!empty($object['contractType']) && in_array($object['contractType'], $this->initContractType)) {
                //���ò���
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessConfirm_d($object, $initObj);
                }
            }

            // ���¸��¹���ͬһԴ������Ŀ�ĸ���
            if(isset($object['contractId']) && isset($object['contractType']) && $object['contractType'] == 'GCXMYD-01'){
                $this->updateContractMoney_d($object['contractId']);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ����ɾ������
     * @param $id
     * @return bool
     */
    function deletes_d($id)
    {
        try {
            $this->start_d();

            $obj = $this->find(array('id' => $id), null, 'contractId,contractType,rObjCode,projectCode');

            $this->deletes($id);

            //���Ѿ����ó�ʼ��������ò���
            if (in_array($obj['contractType'], $this->initContractType)) {
                //���ò��� - ɾ�����������
                $newClass = $this->getClass($obj['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $obj = $this->businessDelete_d($obj, $initObj);
                }
            }
            //ɾ����Ŀ�³�
            $esmcharterDao = new model_engineering_charter_esmcharter();
            $esmcharterDao->delete(array('projectCode' => $obj['projectCode']));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ͣ��Ŀ
     * @param $object
     * @return mixed
     */
    function stop_d($object)
    {
        $mailInfo = $object;
        try {
            $this->start_d();
            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            //�༭��Ŀ
            unset($object['description']);
            parent::edit_d($object, true);

            //ͬ����Ŀ
            $this->updateProjectInfo_p($object['projectCode']);

            //���Ѿ����ó�ʼ��������ò���
            if (!empty($object['contractType']) && in_array($object['contractType'], $this->initContractType)) {
                //���ò���
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessConfirm_d($object, $initObj);
                }
            }

            //�ʼ�����
            if ($object['email']['issend'] == 'y') {
                $this->mailDeal_d('esmprojectStop', $object['mail']['TO_ID'], $mailInfo);
            }

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '��ͣ', $mailInfo['description']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ȡ����ͣ
     * @param $object
     * @return mixed
     */
    function cancelStop_d($object)
    {
        $mailInfo = $object;
        try {
            $this->start_d();
            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            //�༭��Ŀ
            unset($object['description']);
            parent::edit_d($object, true);

            //ͬ����Ŀ
            $this->updateProjectInfo_p($object['projectCode']);

            //���Ѿ����ó�ʼ��������ò���
            if (!empty($object['contractType']) && in_array($object['contractType'], $this->initContractType)) {
                //���ò���
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessConfirm_d($object, $initObj);
                }
            }

            //�ʼ�����
            if ($object['email']['issend'] == 'y') {
                $this->mailDeal_d('esmprojectCancelStop', $object['email']['TO_ID'], $mailInfo);
            }

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], 'ȡ����ͣ', $mailInfo['description']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �ر���Ŀ����
     * @param $object
     * @return mixed
     */
    function close_d($object)
    {
        try {
            $this->start_d();

            //�����ֵ䴦��
            $object = $this->processDatadict($object);

            //�ر���Ŀʱ���Զ�����Ŀ���ȸ���Ϊ100
            $object['projectProcess'] = 100;

            //�༭��Ŀ
            parent::edit_d($object, true);

            //ͬ����Ŀ
            $this->updateProjectInfo_p($object['projectCode']);

            /**** ------------ �رպ�ҵ���� ------------- ****/

            // ���ò���
            if (in_array($object['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessClose_d($object, $initObj);
                }
            }

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '�ر�', $object['closeDesc']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ���¼�����Ŀ�ľ�����Ϣ
     * @param null $projectCode
     * @param null $projectIds
     * @return mixed
     * @throws Exception
     */
    function calProjectFee_d($projectCode = null, $projectIds = null)
    {
        $sql = "UPDATE " . $this->tbl_name . " c LEFT JOIN (
                SELECT projectId,SUM(fee) AS feeEqu FROM oa_esm_resource_fee GROUP BY projectId) e ON c.id = e.projectId
                SET c.feeAll = c.feeField + c.feeFieldImport + c.feePerson + c.feeSubsidy + c.feeSubsidyImport +
                    if(e.feeEqu IS NULL, 0, e.feeEqu) + c.feeEqu + c.feeEquImport + c.feeOutsourcing +
                    c.feeOther + c.feePK + c.feeFlights + c.feePayables + c.feeCar";

        if ($projectCode) $sql .= " WHERE projectCode = '$projectCode'";
        if ($projectIds) $sql .= " WHERE id IN($projectIds)";

        try {
            return $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ���¼�����Ŀ���ý���
     * @param null $id
     * @param null $projectIds
     * @return mixed
     * @throws $e
     */
    function calFeeProcess_d($id = null, $projectIds = null)
    {
        try {
            $sql = "UPDATE " . $this->tbl_name . " SET
                feeAllProcess = IF(budgetAll = 0,0,ROUND(feeAll/budgetAll*100,2)) ,
                feeFieldProcess = IF(budgetField = 0,0,ROUND(feeField/budgetField*100,2))";

            if ($id) $sql .= " WHERE id = " . $id;
            if ($projectIds) $sql .= " WHERE id IN($projectIds)";

            return $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ���¼�����Ŀ���ý���
     * @param $projectCode
     * @param $type
     * @return mixed
     * @throws $e
     */
    function calFeeProcessByCode_d($projectCode, $type = 1)
    {
        if ($type == 1) {
            $sql = "update " . $this->tbl_name . " set feeAllProcess = if(budgetAll = 0,0,round(feeAll/budgetAll*100,2)) ,
			feeFieldProcess = if(budgetField = 0,0,round(feeField/budgetField*100,2)) where projectCode = '" . $projectCode . "'";
        } else {
            $sql = "update " . $this->tbl_name . " set feeAllProcess = if(budgetAll = 0,0,round(feeAll/budgetAll*100,2)) ,
			feeFieldProcess = if(budgetField = 0,0,round(feeField/budgetField*100,2)),
			budgetAll = budgetOther + budgetField + budgetOutsourcing + budgetEqu + budgetPerson where projectCode = '" . $projectCode . "'";
        }
        try {
            return $this->_db->query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ���˰��´�id
     * @return string
     */
    function getOfficeIds_d()
    {
        $officeInfoDao = new model_engineering_officeinfo_officeinfo();
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
     * ��ȡ��������ʡ�����Ʒ��
     * @return string
     */
    function getProvincesAndLines_d()
    {
        $managerDao = new model_engineering_officeinfo_manager();
        return $managerDao->getProvincesAndLines_d();
    }

    /**
     * ��ȡ�����˻�����ฺܼ���ʡ�����Ʒ��
     * @return string
     */
    function getOfficeNameAndLines_d()
    {
        $officeInfoDao = new model_engineering_officeinfo_officeinfo();
        return $officeInfoDao->getOfficeNameAndLines_d();
    }

    /**
     * ��ȡ��Ŀ�Ķ�Ӧ��Χid
     * @param $id
     * @return mixed
     */
    function getRangeId_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'provinceId,productLine,officeId');
        $esmrangeDao = new model_engineering_officeinfo_range();
        return $esmrangeDao->getRangeId_d($obj['provinceId'], $obj['productLine'], $obj['officeId']);
    }

    /**
     * ��ȡ��Ŀ�Ķ�Ӧ��Χ��Ϣ
     * @param $id
     * @return mixed
     */
    function getRangeInfo_d($id)
    {
        $obj = $this->find(array('id' => $id), null, 'provinceId,productLine,officeId');
        $esmrangeDao = new model_engineering_officeinfo_range();
        return $esmrangeDao->getRangeInfo_d($obj['provinceId'], $obj['productLine'], $obj['officeId']);
    }

    /**
     * ��ȡ��Ŀ�ֳ�����
     * @param $projectCode
     * @return int
     */
    function getFeeNow_d($projectCode)
    {
        $expenseDao = self::getObjCache('model_finance_expense_expense');
        return $expenseDao->getFeeSum_d($projectCode);
    }

    /**
     * ��ȡ��Ŀ��ĳЩ�ֳ�����
     * @param $projectCode
     * @param $costTypeIds
     * @return mixed
     */
    function getSomeFeeSum_d($projectCode, $costTypeIds)
    {
        $expenseDao = self::getObjCache('model_finance_expense_expense');
        return $expenseDao->getSomeFeeSum_d($projectCode, $costTypeIds);
    }

    /**
     * ��ȡ����������Ϣ
     * @param $id
     * @return int
     */
    function getFeePerson_d($id)
    {
        $esmmemberDao = new model_engineering_member_esmmember();
        return $esmmemberDao->getFeePerson_d($id);
    }

    /**
     * ������Ŀ��Ż�ȡ��Ŀ��Ϣ
     * @param $projectCode
     * @return bool|mixed
     */
    function getProjectInfo_d($projectCode)
    {
        return $this->find(array('projectCode' => $projectCode));
    }

    /**
     * ��Ŀ�³̻�ȡ��Ŀ��Ϣ
     * @param $object
     * @return bool|mixed
     */
    function getProjectForCharter_d($object)
    {
        if (!isset($object['contractType'])) {
            $object['contractType'] = 'GCXMYD-01';
        }
        //���ò��� - ɾ�����������
        $newClass = $this->getClass($object['contractType']);
        if ($newClass) {
            $initObj = new $newClass();
            return $this->businessForCharter_d($object, $initObj);
        } else {
            return false;
        }
    }

    /**
     * ������Ŀ����
     * create on 2012-12-3
     * create by show
     * @param $id
     * @param $projectProcess
     * @throws $e
     */
    function updateProjectProcess_d($id, $projectProcess)
    {
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        try {
            $this->start_d();

            //���½���
            //$this->update(array('id' => $id), array('projectProcess' => $projectProcess));
            $this->update(" id = '{$id}' and incomeType not in ('SRQRFS-02','SRQRFS-03')", array('projectProcess' => $projectProcess));//������Ŀ���� PMS 521 Ҫ��ȡ����Ŀ�ܱ��Ľ��ȸ���

            //���½������ҵ��
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessConfirm_d($obj, $initObj);
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ������Ŀ��������
     * @param $id
     * @param $feePerson
     * @throws $e
     */
    function updateFeePerson_d($id, $feePerson)
    {
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        $feePerson['id'] = $id;
        try {
            $this->start_d();

            $this->edit_d($feePerson);

            //���½������ҵ��
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessConfirm_d($obj, $initObj);
            }

            //������Ŀ����
            $this->calFeeProcess_d($id);

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ���ݺ�ͬ��Ÿ�����Ŀ�����̯����
     * @param $projectCode
     * @param $feePayables
     * @return mixed
     * @throws Exception
     */
    function updateFeePayables_d($projectCode, $feePayables)
    {
        try {
            return $this->update(array('projectCode' => $projectCode), array('feePayables' => $feePayables));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ���ݺ�ͬ��Ÿ�����Ŀ�����̯����
     * @param $projectId
     * @param $feeFlights
     * @return mixed
     * @throws Exception
     */
    function updateFeeFlights_d($projectId, $feeFlights)
    {
        try {
            return $this->update(array('id' => $projectId), array('feeFlights' => $feeFlights));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /******************      �����ͬר�ô���      ******************/

    /**
     * ��ȡ��ͬ�ܹ���ռ�� - ���ݺ�ͬ���ͺ�id
     * @param $contractId
     * @param $contractType
     * @param $newProLine
     * @return int
     */
    function getAllWorkRateByType_d($contractId, $contractType, $newProLine)
    {
        $this->searchArr = array('contractId' => $contractId, 'contractType' => $contractType, 'newProLine' => $newProLine);
        $this->groupBy = 'c.contractId,c.contractType,newProLine';
        $rs = $this->list_d('sumWorkRate');

        if (is_array($rs)) {
            return $rs[0]['workRate'];
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ��Ŀ����
     * @param $contractId
     * @param $contractType
     * @return int
     */
    function getProjectNum_d($contractId, $contractType)
    {
        $projectInfo = $this->findAll(
            array('contractId' => $contractId, 'contractType' => $contractType),
            null, 'id');
        return $projectInfo ? count($projectInfo) : 0;
    }

    /**
     * ������Ŀ���Ȼ�ȡ��ͬ���� - ���ݺ�ͬ���ͺ�id
     * @param $contractId
     * @param $contractType
     * @return int
     */
    function getAllProcessByType_d($contractId, $contractType)
    {
        $this->searchArr = array('contractId' => $contractId, 'contractType' => $contractType);
        $this->groupBy = 'c.contractId,c.contractType';
        $rs = $this->list_d('sumProcess');

        if (is_array($rs)) {
            return $rs[0]['allProcess'];
        } else {
            return 0;
        }
    }

    /**
     * ������Ŀ�����ͻ�ȡ��Ŀ�б�
     * @param $projectIds
     * @param $attributes
     * @return mixed
     */
    function getListByIdsAndAttribute_d($projectIds, $attributes)
    {
        $this->searchArr = array('idArr' => $projectIds, 'attributes' => $attributes);
        return $this->list_d();
    }

    /**
     * ������Ŀid��ȡ��Ŀ��ϸӳ���
     * @param $projectIds
     * @return array
     */
    function getMapByIds_d($projectIds)
    {
        $this->searchArr = array('ids' => $projectIds);
        $data = $this->list_d();

        $rst = array();
        if (!empty($data)) {
            foreach ($data as $v) {
                $v = $this->feeDeal($v);
                $v = $this->contractDeal($v);
                $rst[$v['id']] = $v;
            }
        }

        return $rst;
    }

    /**********************���뵼������********************************/
    /**
     * ƥ��excel�ֶ�
     * @param $datas
     * @param $titleRow
     * @return mixed
     */
    function formatArray_d($datas, $titleRow)
    {
        // �Ѷ������
        $definedTitle = array(
            '��Ʒ��' => 'newProLineName', '����' => 'officeName', '��Ŀ����' => 'projectName', '��Ŀ���' => 'projectCode',
            '״̬' => 'statusName', '��Ԥ��' => 'budgetAll', '�ֳ�Ԥ��' => 'budgetField', '����Ԥ��' => 'budgetPerson',
            '�豸Ԥ��' => 'budgetEqu', '���Ԥ��' => 'budgetOutsourcing', '����Ԥ��' => 'budgetOther',
            '��������' => 'feePerson', '�������' => 'feeOutsourcing', '֧��������' => 'feeOther', '��Ʊ����' => 'feeFlights',
            '���̽���' => 'projectProcess', '����ʡ��' => 'province', 'Ԥ����������' => 'planBeginDate',
            'Ԥ�ƽ�������' => 'planEndDate', 'ʵ�ʿ�ʼ����' => 'actBeginDate', 'ʵ���������' => 'actEndDate',
            '��Ŀ����' => 'managerName', '����' => 'peopleNumber', '�������' => 'outsourcingName',
            '��Ŀ���' => 'categoryName', '����' => 'netName', '����ռ��' => 'workRate', '��Ŀ����' => 'estimates'
        );
        // ������֤�ı���
        $dateTitle = array(
            'Ԥ����������' => 'planBeginDate', 'Ԥ�ƽ�������' => 'planEndDate', 'ʵ�ʿ�ʼ����' => 'actBeginDate',
            'ʵ���������' => 'actEndDate'
        );
        // ���¼�¼����
        $logArr = array();

        // �����µ�����
        foreach ($titleRow as $k => $v) {
            // �������Ϊ�գ���ɾ��
            if (trim($datas[$k]) === '') {
                unset($datas[$k]);
                continue;
            }
            // ��������Ѷ������ݣ�����м�ֵ�滻
            if (isset($definedTitle[$v])) {
                // ʱ�����ݴ���
                if (isset($dateTitle[$v]) && is_numeric(trim($datas[$k]))) {
                    $datas[$k] = date('Y-m-d', (mktime(0, 0, 0, 1, $datas[$k] - 1, 1900)));
                }

                // ��ʽ����������
                $datas[$definedTitle[$v]] = trim($datas[$k]);
                // ������־����
                $logArr[$v] = trim($datas[$k]);
            }
            // ������ɺ�ɾ������
            unset($datas[$k]);
        }
        // �и�������ʱ����������־һ������
        if (!empty($datas)) {
            $datas['logs'] = $logArr;
        }
        return $datas;
    }

    /**
     * ��Ŀ���빦��
     */
    function addExecelData_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        $contArr = array();//��ͬ��Ϣ����
        $contDao = new model_contract_contract_contract();
        $otherDataDao = new model_common_otherdatas();
        $datadictArr = array();//�����ֵ�����
        $datadictDao = new model_system_datadict_datadict();
        $officeDao = new model_engineering_officeinfo_officeinfo();
        $projectArr = array();//��Ŀ��������
        $strategyArr = array();//�����໺��
        //������Ŀ�����ɫ����
        $memberDao = new model_engineering_member_esmmember();
        $esmroleDao = new model_engineering_role_esmrole();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4]) && empty($val[5]) && empty($val[6])) {
                        continue;
                    } else {
                        //���������Ǹ�������
                        //1Ϊ����
                        //0Ϊ����
                        $addOrUpdate = 1;
                        $newId = 0; // ��ʼ��������¼id

                        //��ͬ����
                        if (!empty($val[0])) {
                            $contractCode = $val[0];

                            //������ͬ��������
                            $contArr[$contractCode] = isset($contArr[$contractCode]) ? $contArr[$contractCode] : $contDao->getContractInfoByCode($contractCode, 'main');
                            if (is_array($contArr[$contractCode])) {
                                $contractId = $contArr[$contractCode]['id'];
                                $rObjCode = $contArr[$contractCode]['objCode'];
                                $customerId = $contArr[$contractCode]['customerId'];
                                $customerName = $contArr[$contractCode]['customerName'];
                                $contractCountry = $contArr[$contractCode]['contractCountry'];
                                $contractCountryId = $contArr[$contractCode]['contractCountryId'];
                                $contractProvince = $contArr[$contractCode]['contractProvince'];
                                $contractProvinceId = $contArr[$contractCode]['contractProvinceId'];
                                $contractCity = $contArr[$contractCode]['contractCity'];
                                $contractCityId = $contArr[$contractCode]['contractCityId'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ��';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $contractId = 0;
                            $rObjCode = "";
                            $customerId = "";
                            $customerName = "";
                            $contractCode = "";
                            $contractCountry = '';
                            $contractCountryId = '';
                            $contractProvince = '';
                            $contractProvinceId = '';
                            $contractCity = '';
                            $contractCityId = '';
                        }

                        //��Ŀ����
                        $projectName = trim($val[1]);
                        if (empty($projectName)) {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����Ŀ����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��Ŀ���
                        $projectCode = trim($val[2]);
                        if (!empty($projectCode)) {
                            if (isset($projectArr[$projectCode])) {
                                $addOrUpdate = 0;
                            } else {
                                //�ж�ʱ���Ѿ�������Ŀ
                                $rs = $this->find(array('projectCode' => $projectCode), null, 'id,contractId,managerId,managerName');
                                if (is_array($rs)) {
                                    $projectArr[$projectCode] = $rs;
                                    $addOrUpdate = 0;
                                }
                            }

                            if (!$contractCode) {
                                if ($contractId != $projectArr[$projectCode]['contractId']) {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!��Ŀԭ������ͬ�뵱ǰ������ͬ����ͬ,��ɾ������Ŀ���ٽ��е���';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }

                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����Ŀ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��Ŀ״̬
                        if (!empty($val[3])) {
                            $val[3] = trim($val[3]);
                            if (!isset($datadictArr[$val[3]])) {
                                $rs = $datadictDao->getCodeByName('GCXMZT', $val[3]);
                                if (!empty($rs)) {
                                    $status = $datadictArr[$val[3]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ���Ŀ״̬';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $status = $datadictArr[$val[3]]['code'];
                            }
                            if ($val[3] == '�ﱸ') {
                                $ExaStatus = WAITAUDIT;
                            } else {
                                $ExaStatus = AUDITED;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����Ŀ״̬';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��Ŀ����
                        if (empty($val[4])) {
                            $projectProcess = 0;
                        } else {
                            $projectProcess = $val[4];
                        }

                        //��Ŀ����
                        if (!empty($val[5])) {
                            $val[5] = trim($val[5]);
                            if (!isset($userArr[$val[5]])) {
                                $rs = $otherDataDao->getUserInfo($val[5]);
                                if (!empty($rs)) {
                                    $userArr[$val[5]] = $rs;
                                    $managerId = $userArr[$val[5]]['USER_ID'];
                                    $deptId = $userArr[$val[5]]['DEPT_ID'];
                                    $deptName = $userArr[$val[5]]['DEPT_NAME'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ���Ŀ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $managerId = $userArr[$val[5]]['USER_ID'];
                                $deptId = $userArr[$val[5]]['DEPT_ID'];
                                $deptName = $userArr[$val[5]]['DEPT_NAME'];
                            }
                            $managerName = $val[5];
                        } else {
                            $managerId = "";
                            $managerName = "";
                            $deptId = "";
                            $deptName = "";
                        }

                        // ��˾�������û�д��룬���Ե�ǰ��¼�˵�������˾Ϊ��׼
//                        if (!empty($val[8])) {
//                            $businessBelongName = trim($val[8]);
//                            $branchObj = $branchDao->find(array('NameCN' => $businessBelongName));
//                            $businessBelong = $branchObj['NamePT'];
//                            $formBelong = $branchObj['NamePT'];
//                            $formBelongName = $branchObj['NameCN'];
//                        } else {
//                            $businessBelongName = $formBelongName = $_SESSION['USER_COM_NAME'];
//                            $businessBelong = $formBelong = $_SESSION['USER_COM'];
//                        }

                        //���´�
//						if (!empty($val[6]) && !empty($val[7]) && !empty($val[8])) {
//							$productLineName = trim($val[7]);
//							if (!isset($datadictArr['GC'.$productLineName])) {
//								$rs = $datadictDao->getCodeByName('GCSCX', $productLineName);
//								if (!empty($rs)) {
//									$productLine = $datadictArr['GC'.$productLineName]['code'] = $rs;
//								} else {
//									$tempArr['docCode'] = '��' . $actNum . '������';
//									$tempArr['result'] = '����ʧ��!�����ڵĹ��̲�Ʒ��';
//									array_push($resultArr, $tempArr);
//									continue;
//								}
//							} else {
//								$productLine = $datadictArr['GC'.$productLineName]['code'];
//							}
//							$officeName = trim($val[6]);
//							if (!isset($officeArr[$officeName])) {
//								$officeId = $officeDao->getIdByName($officeName, $productLineName, $businessBelongName);
//								if (!empty($officeId)) {
//									$officeId = $officeArr[$officeName]['id'] = $officeId;
//								} else {
//									$tempArr['docCode'] = '��' . $actNum . '������';
//									$tempArr['result'] = '����ʧ��!û��ƥ�䵽��' . $businessBelongName .
//                                        '����˾�����ġ�' . $productLineName . '����Ʒ�ߣ����Ķ�Ӧ��������';
//									array_push($resultArr, $tempArr);
//									continue;
//								}
//							} else {
//								$officeId = $officeArr[$officeName]['id'];
//							}
//							$setOffice = true;
//						} else {
//							$setOffice = false;
//							$officeName = $officeId = $productLine = $productLineName = '';
//						}

                        // ��Ŀ����
                        if (!empty($val[6])) {
                            $officeName = trim($val[6]);
                            $officeArr = $officeDao->getIdByOfficeName($officeName);
                            if (!empty($officeArr)) {
                                $businessBelongName = $officeArr['businessBelongName'];
                                $businessBelong = $officeArr['businessBelong'];
                                $formBelong = $officeArr['businessBelong'];
                                $formBelongName = $officeArr['businessBelongName'];
                                $productLine = $officeArr['productLine'];
                                $productLineName = $officeArr['productLineName'];
                                $officeId = $officeArr['id'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�����ڵ���Ŀ����' . $officeName . '��';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                            $setOffice = true;
                        } else {
                            $setOffice = false;
                        }

                        // �²���
                        if (!empty($val[7])) {
                            $newProLineName = trim($val[7]);
                            if (!isset($datadictArr['HT' . $newProLineName])) {
                                $rs = $datadictDao->getCodeByName('HTCPX', $newProLineName);
                                if (!empty($rs)) {
                                    $newProLine = $datadictArr['HT' . $newProLineName]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ��Ʒ��';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $newProLine = $datadictArr['HT' . $newProLineName]['code'];
                            }
                        } else {
                            $newProLine = "";
                            $newProLineName = "";
                        }

                        // �������
                        $moduleCode = $moduleName = "";
                        if (!empty($val[9])) {
                            $moduleArr = $this->_db->get_one("select dataName,dataCode from oa_system_datadict where parentCode = 'HTBK' and dataName = '{$val[9]}';");
                            if($moduleArr && isset($moduleArr['dataName'])){
                                $moduleCode = $moduleArr['dataCode'];
                                $moduleName = $moduleArr['dataName'];
                            }else{
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�����ڵİ�顾' . $val[9] . '��';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        // ������˾
                        if (!empty($val[10])) {
                            $companyArr = $this->_db->get_one("select NameCN,NamePT from branch_info where NameCN = '{$val[10]}';");
                            if($companyArr && isset($companyArr['NameCN'])){
                                $businessBelongName = $companyArr['NameCN'];
                                $businessBelong = $companyArr['NamePT'];
                                $formBelong = $companyArr['NamePT'];
                                $formBelongName = $companyArr['NameCN'];
                            }else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�����ڵĹ�����˾��' . $val[10] . '��';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        //�������ƴװ
                        $inArr = array(
                            'contractId' => $contractId,
                            'contractCode' => $contractCode,
                            'rObjCode' => $rObjCode,
                            'customerId' => $customerId,
                            'customerName' => $customerName,
                            'country' => $contractCountry,
                            'countryId' => $contractCountryId,
                            'contractCountry' => $contractCountry,
                            'province' => $contractProvince,
                            'provinceId' => $contractProvinceId,
                            'contractProvince' => $contractProvince,
                            'city' => $contractCity,
                            'cityId' => $contractCityId,
                            'contractCity' => $contractCity,
                            'projectName' => $projectName,
                            'projectCode' => $projectCode,
                            'projectProcess' => $projectProcess,
                            'managerName' => $managerName,
                            'managerId' => $managerId,
                            'deptId' => $deptId,
                            'deptName' => $deptName,
                            'status' => $status,
                            'ExaStatus' => $ExaStatus,
                            'ExaDT' => day_date,
                            'remark' => 'ϵͳ��������',
                            'newProLine' => $newProLine,
                            'newProLineName' => $newProLineName,
                            'estimates' => $val[8],
                            'module' => $moduleCode,
                            'moduleName' => $moduleName
                        );

                        if ($setOffice) {
                            $inArr['officeName'] = $officeName;
                            $inArr['officeId'] = $officeId;
                            $inArr['productLine'] = $productLine;
                            $inArr['productLineName'] = $productLineName;
                            $inArr['businessBelongName'] = $businessBelongName;
                            $inArr['businessBelong'] = $businessBelong;
                            $inArr['formBelongName'] = $formBelongName;
                            $inArr['formBelong'] = $formBelong;
                        }

                        if ($contractCode) {
                            //��Ŀ�������⴦��
                            $inArr['attribute'] = 'GCXMSS-02';
                            $inArr['contractType'] = 'GCXMYD-01';
                        }
                        //��Ŀռ��
                        if ($addOrUpdate == 1) {
                            $inArr['workRate'] = 100;
                        }
                        try {
                            $this->start_d();
                            if ($addOrUpdate == 1) {
                                //������Ŀ����
                                $newId = $this->add_d($inArr);

                                //��Ŀ�������ݴ���
                                $managerRole = array(
                                    'projectCode' => $projectCode,
                                    'projectName' => $projectName,
                                    'projectId' => $newId,
                                    'memberId' => $managerId,
                                    'memberName' => $managerName,
                                    'roleName' => '��Ŀ����',
                                    'isManager' => '1',
                                    'parentId' => -1
                                );
                                $esmroleDao->addRoleAndMember_d($managerRole);
                            } else {
                                //������Ŀ����
                                $this->update(array('projectCode' => $projectCode), $inArr);
                                //��Ŀ�����ִ��� ����޸�����Ŀ���������Ŀ��Ա�б��е���Ŀ��������޸�
                                if ($projectArr[$projectCode]['managerId'] != $managerId) {
                                    $conditionArr = array('projectCode' => $projectCode, 'memberId' => $projectArr[$projectCode]['managerId']);
                                    $memberArr = array('memberId' => $managerId, 'memberName' => $managerName);
                                    $memberDao->update($conditionArr, $memberArr);

                                    //��Ŀ��ɫ�б����
                                    $roleCon = array('projectCode' => $projectCode, 'isManager' => 1);
                                    $roleArr = array('memberId' => $managerId, 'memberName' => $managerName);
                                    $esmroleDao->update($roleCon, $roleArr);
                                }
                            }
                            //���ò���
                            if (isset($inArr['contractType']) && in_array($inArr['contractType'], $this->initContractType)) {
                                $projectInfo = array('contractType' => $inArr['contractType'], 'contractId' => $contractId);
                                //���ò��� - ȷ�Ϻ��������
                                if (!isset($strategyArr[$inArr['contractType']])) {
                                    $newClass = $this->getClass($inArr['contractType']);
                                    if ($newClass) {
                                        $strategyArr[$inArr['contractType']] = new $newClass();
                                    }
                                }
                                //���������
                                if ($addOrUpdate == 1) {
                                    //����ʱ����
                                    $this->businessAdd_d($projectInfo, $strategyArr[$inArr['contractType']]);
                                }

                                //���������Ŀ����� ����id
                                if(!$addOrUpdate){
                                    $projectInfo['id'] = $projectArr[$projectCode]['id'];
                                }
                                //����ȷ��
                                $this->businessConfirm_d($projectInfo, $strategyArr[$inArr['contractType']]);
                                //����ر�
                                $this->businessClose_d($projectInfo, $strategyArr[$inArr['contractType']]);
                            }
                            //��Ŀ��Ϣͬ��
                            $this->updateProjectInfo_p($inArr);

                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                        }
                        if ($addOrUpdate == 1) {
                            if ($newId) {
                                $tempArr['result'] = '����ɹ�';
                                $tempArr['docCode'] = '��' . $actNum . '������';
                            } else {
                                $tempArr['result'] = '����ʧ��';
                                $tempArr['docCode'] = '��' . $actNum . '������';
                            }
                        } else {
                            $tempArr['result'] = '�������ݳɹ�';
                            $tempArr['docCode'] = '��' . $actNum . '������';
                        }

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
     * ���񲿷ָ���
     */
    function updateProjectFee_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        //��Ŀ���
                        if (!empty($val[0])) {
                            $projectCode = $val[0];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ŀ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (empty($val[1])) {
                            $feeAll = 0;
                        } else {
                            $feeAll = $val[1];
                        }

                        $conditionArr = array(
                            'projectCode' => $projectCode
                        );

                        $updateRows = array(
                            'projectCode' => $projectCode,
                            'feeAll' => $feeAll,
                            'updateId' => $_SESSION['USER_ID'],
                            'updateName' => $_SESSION['USERNAME'],
                            'updateTime' => date("Y-m-d H:i:s")
                        );

                        try {
                            $this->start_d();
                            //���·���
                            $this->update($conditionArr, $updateRows);

                            if ($this->_db->affected_rows() == 0) {
                                $tempArr['result'] = '���³ɹ�';
                            } else {
                                $tempArr['result'] = '���³ɹ�';
                                //���¼�����Ŀ���ý���
                                $this->calFeeProcessByCode_d($projectCode);
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
     * ���������Ŀ����
     */
    function updateProjectInfo_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        $otherDataDao = new model_common_otherdatas();
        $datadictArr = array();//�����ֵ�����
        $datadictDao = new model_system_datadict_datadict();
        $provinceArr = array();//ʡ������
        $provinceDao = new model_system_procity_province();
        $officeArr = array();//���´�id
        $officeDao = new model_engineering_officeinfo_officeinfo();
        // ��Ŀ������־����
        $logArr = array();
        $esmlogDao = new model_engineering_baseinfo_esmlog();
        $changeWorkRateProjectCodeArray = array(); // �Ƿ���Ҫ������Ŀ���
        // �������
        $strategyArr = array();
        // �жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name, 1);
            spl_autoload_register("__autoload");

            $titleRow = $excelData[0];
            unset($excelData[0]);

            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;

                    if (empty($val[3])) {
                        continue;
                    } else {
                        // ��ʽ������
                        $val = $this->formatArray_d($val, $titleRow);

                        //��Ŀ���
                        if (isset($val['projectCode'])) {
                            $conditionArr = array('projectCode' => $val['projectCode']);
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ŀ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //���´�
                        if (isset($val['officeName'])) {
                            if (!isset($officeArr[$val['officeName']])) {
                                $officeId = $officeDao->getIdByOfficeName($val['officeName']);
                                if (!empty($officeId)) {
                                    $deptName = explode(",", $officeId['feeDeptName']);
                                    $deptName = $deptName[0];
                                    $deptId = explode(",", $officeId['feeDeptId']);
                                    $deptId = $deptId[0];
                                    $officeId = $officeArr[$val['officeName']]['id'] = $officeId['id'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!û�ж�Ӧ�İ��´�';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $officeId = $officeArr[$val['officeName']]['id'];
                                $deptName = $officeArr[$val['officeName']]['feeDeptName'];
                                $deptId = $officeArr[$val['officeName']]['feeDeptId'];
                            }
                            $val['officeId'] = $officeId;
                            $val['deptName'] = $deptName;
                            $val['deptId'] = $deptId;
                        }

                        //��Ŀ״̬
                        if (isset($val['statusName'])) {
                            if (!isset($datadictArr[$val['statusName']])) {
                                $rs = $datadictDao->getCodeByName('GCXMZT', $val['statusName']);
                                if (!empty($rs)) {
                                    $status = $datadictArr[$val['statusName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ���Ŀ״̬';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $status = $datadictArr[$val['statusName']]['code'];
                            }
                            $val['status'] = $status;
                            $val['ExaStatus'] = $val['statusName'] == '�ﱸ' ? WAITAUDIT : AUDITED;
                        }

                        //���̽���
                        if (isset($val['projectProcess'])) {
                            $val['projectProcess'] = $val['projectProcess'] * 100;
                        }

                        //����վ��
                        if (isset($val['workRate'])) {
                            $val['workRate'] = $val['workRate'] * 100;
                        }

                        //ʡ��
                        if (isset($val['province'])) {
                            if (!isset($provinceArr[$val['province']])) {
                                $provinceCode = $provinceDao->getCodeByName($val['province']);
                                if (!empty($provinceCode)) {
                                    $provinceCode = $provinceArr[$val['province']]['provinceCode'] = $provinceCode;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!û�ж�Ӧ��ʡ��';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $provinceCode = $provinceArr[$val['province']]['provinceCode'];
                            }
                            $val['proCode'] = $provinceCode;
                        }

                        //��Ŀ����
                        if (!empty($val['managerName'])) {
                            if (!isset($userArr[$val['managerName']])) {
                                $rs = $otherDataDao->getUserInfo($val['managerName']);
                                if (!empty($rs)) {
                                    $userArr[$val['managerName']] = $rs;
                                    $managerId = $userArr[$val['managerName']]['USER_ID'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ���Ŀ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $managerId = $userArr[$val['managerName']]['USER_ID'];
                            }
                            $val['managerId'] = $managerId;
                        }

                        //��Ŀ���
                        if (!empty($val['outsourcingName'])) {
                            $val['outsourcingName'] = trim($val['outsourcingName']);
                            if (!isset($datadictArr[$val['outsourcingName']])) {
                                $rs = $datadictDao->getCodeByName('WBLX', $val['outsourcingName']);
                                if (!empty($rs)) {
                                    $outsourcing = $datadictArr[$val['outsourcingName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ��������';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $outsourcing = $datadictArr[$val['outsourcingName']]['code'];
                            }
                            $val['outsourcing'] = $outsourcing;
                        }

                        //��Ŀ���
                        if (!empty($val['categoryName'])) {
                            $val['categoryName'] = trim($val['categoryName']);
                            if (!isset($datadictArr[$val['categoryName']])) {
                                $rs = $datadictDao->getCodeByName('XMLB', $val['categoryName']);
                                if (!empty($rs)) {
                                    $category = $datadictArr[$val['categoryName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!��Ŀ������ò���ȷ';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $category = $datadictArr[$val['categoryName']]['code'];
                            }
                            $val['category'] = $category;
                        }

                        //����
                        if (!empty($val['netName'])) {
                            $val['netName'] = trim($val['netName']);
                            if (!isset($datadictArr[$val['netName']])) {
                                $rs = $datadictDao->getCodeByName('WLLX', $val['netName']);
                                if (!empty($rs)) {
                                    $net = $datadictArr[$val['netName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�������ò���ȷ';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $net = $datadictArr[$val['netName']]['code'];
                            }
                            $val['net'] = $net;
                        }

                        //��Ʒ��
                        if (!empty($val['newProLineName'])) {
                            $val['newProLineName'] = trim($val['newProLineName']);
                            if (!isset($datadictArr[$val['newProLineName']])) {
                                $rs = $datadictDao->getCodeByName('HTCPX', $val['newProLineName']);
                                if (!empty($rs)) {
                                    $newProLine = $datadictArr[$val['newProLineName']]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!��Ʒ�����ò���ȷ';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $newProLine = $datadictArr[$val['newProLineName']]['code'];
                            }
                            $val['newProLine'] = $newProLine;
                        }

                        //�������⴦�� - ��ɺ�ͬ��Ŀ
                        $val = $this->processDatadict($val);

                        $val['updateTime'] = date('Y-m-d h:i:s');
                        $val['updateId'] = $_SESSION['USER_ID'];
                        $val['updateName'] = $_SESSION['USERNAME'];

//						echo "<pre>";print_r($val);die();

                        try {
                            $this->start_d();
                            // ��ѯ��Ŀ��Ϣ
                            $projectInfo = $this->find(array('projectCode' => $val['projectCode']), null, 'id,contractId,contractType,projectProcess,status');
                            // ������־����
                            $val['logs']['projectId'] = $projectInfo['id'];
                            array_push($logArr, $val['logs']);
                            unset($val['logs']);

                            //���·���
                            $this->update($conditionArr, $val);

                            //�ж��Ƿ��и��½���
                            if (isset($val['projectProcess']) || isset($val['status'])) {
                                //���Ѿ����ó�ʼ��������ò���
                                if (in_array($projectInfo['contractType'], $this->initContractType)) {
                                    //���ò��� - ȷ�Ϻ��������
                                    if (!isset($strategyArr[$projectInfo['contractType']])) {
                                        $newClass = $this->getClass($projectInfo['contractType']);
                                        if ($newClass) {
                                            $strategyArr[$projectInfo['contractType']] = new $newClass();
                                        }
                                    }
                                    // ����н��ȣ���ʹ�ñ��θ��µĽ���
                                    $projectInfo['projectProcess'] = isset($val['projectProcess']) ? $val['projectProcess'] : $projectInfo['projectProcess'];
                                    $projectInfo['status'] = isset($val['status']) ? $val['status'] : $projectInfo['status'];
                                    //����ȷ��
                                    $this->businessConfirm_d($projectInfo, $strategyArr[$projectInfo['contractType']]);
                                    //����ر�
                                    $projectInfo['contractType'] == 'GCXMYD-01' && $this->businessClose_d($projectInfo, $strategyArr[$projectInfo['contractType']]);
                                }
                            }

                            // ����и��¹���վ�ȣ������¼�����Ŀ���
                            if (isset($val['workRate'])) array_push($changeWorkRateProjectCodeArray, $val['projectCode']);

                            //ͬ����Ŀ
                            $this->updateProjectInfo_p($val['projectCode']);

                            $tempArr['result'] = '���³ɹ�';
                            //���¼�����Ŀ���ý���
                            $this->calFeeProcessByCode_d($val['projectCode'], 2);
                            $this->commit_d();
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '����ʧ��';
                        }

                        $tempArr['docCode'] = '��' . $actNum . '������';
                        array_push($resultArr, $tempArr);
                    }
                }

                // ������ڱ��ռ�ȵ���Ŀ�������¼����ͬ���
                if ($changeWorkRateProjectCodeArray) $this->updateContractMoneyByProjectCode_d($changeWorkRateProjectCodeArray);

                // ��������־����
                $esmlogDao->addLogBatch_d($logArr, '�������', 'projectId');

                return $resultArr;
            } else {
                msg("�ļ������ڿ�ʶ������!");
            }
        } else {
            msg("�ϴ��ļ����Ͳ���EXCEL!");
        }
    }

    /**
     * ���º�ͬ������
     * @param $projectCodeArr
     */
    function updateContractProcess_d($projectCodeArr)
    {
        //������Ŀ����
        $projectCache = array();
        //�������
        $strategyArr = array();

        foreach ($projectCodeArr as $val) {
            if (!isset($projectCache[$val])) {
                //��ȡ��Ŀ����
                $projectCache[$val] = $this->find(array('projectCode' => $val), null, 'contractId,contractType');//���Ѿ����ó�ʼ��������ò���
                //�ж϶����Ƿ���Ҫ���ò���
                if (in_array($projectCache[$val]['contractType'], $this->initContractType)) {
                    //���ò��� - ȷ�Ϻ��������
                    if (!isset($strategyArr[$projectCache[$val]['contractType']])) {
                        $newClass = $this->getClass($projectCache[$val]['contractType']);
                        $strategyArr[$projectCache[$val]['contractType']] = new $newClass();
                    }

                    $this->businessConfirm_d($projectCache[$val], $strategyArr[$projectCache[$val]['contractType']]);
                }
            }
        }
    }

    /**
     * ������Ŀ�������
     */
    function updateProjectOtherFee_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        //��Ŀ���
                        if (!empty($val[0])) {
                            $projectCode = $val[0];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ŀ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $conditionArr = array(
                            'projectCode' => $projectCode
                        );

                        $updateRows = array(
                            'projectCode' => $projectCode,
                            'updateId' => $_SESSION['USER_ID'],
                            'updateName' => $_SESSION['USERNAME'],
                            'updateTime' => date("Y-m-d H:i:s")
                        );

                        //����������ø���
                        $feePerson = $val[1] === '' ? 'NONE' : sprintf("%f", abs(trim($val[1])));
                        if ($feePerson != 'NONE') {
                            $updateRows['feePerson'] = $feePerson;
                        }

                        //����������ø���
                        $feePeople = $val[2] === '' ? 'NONE' : sprintf("%f", abs(trim($val[2])));
                        if ($feePeople != 'NONE') {
                            $updateRows['feePeople'] = $feePeople;
                        }

                        //����������ø���
                        $feeDay = $val[3] === '' ? 'NONE' : sprintf("%f", abs(trim($val[3])));
                        if ($feeDay != 'NONE') {
                            $updateRows['feeDay'] = $feeDay;
                        }

                        //�豸������ø���
                        $feeEqu = $val[4] === '' ? 'NONE' : sprintf("%f", abs(trim($val[4])));
                        if ($feeEqu != 'NONE') {
                            $updateRows['feeEqu'] = $feeEqu;
                        }

                        //���������ø���
                        $feeOutsourcing = $val[5] === '' ? 'NONE' : sprintf("%f", abs(trim($val[5])));
                        if ($feeOutsourcing != 'NONE') {
                            $updateRows['feeOutsourcing'] = $feeOutsourcing;
                        }

                        //����������ø���
                        $feeOther = $val[6] === '' ? 'NONE' : sprintf("%f", abs(trim($val[6])));
                        if ($feeOther != 'NONE') {
                            $updateRows['feeOther'] = $feeOther;
                        }

                        try {
                            $this->start_d();
                            //���·���
                            $this->update($conditionArr, $updateRows);

                            if ($this->_db->affected_rows() == 0) {
                                $tempArr['result'] = '���³ɹ�';
                            } else {
                                $tempArr['result'] = '���³ɹ�';
                                //���¼�����Ŀ���ý���
                                $this->calFeeProcessByCode_d($projectCode);
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
     * ������ĿԤ��(������ͬ�������ͬ)
     * @param string $budgetType
     * @return array
     */
    function updateProjectOtherBudget_d($budgetType = 'budgetOther')
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        //ʵ����Ԥ�㲿��
        $esmbudgetDao = new model_engineering_budget_esmbudget();

        //��Ŀ��������
        $projectCache = array();

        $budgetTypeArr = array(
            'budgetField' => '�ֳ�Ԥ��',
            'budgetPerson' => '����Ԥ��',
            'budgetOutsourcing' => '���Ԥ��',
            'budgetOther' => '����Ԥ��'
        );
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        //��Ŀ���
                        if (!empty($val[0])) {
                            $projectCode = $val[0];
                            if (!isset($projectCache[$projectCode])) {
                                $projectObj = $this->find(array('projectCode' => $projectCode), null, 'id,projectName');
                                if ($projectObj) {
                                    $projectCache[$projectCode] = $projectObj;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum . '������';
                                    $tempArr['result'] = '����ʧ��!�����ڵ���Ŀ���';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д��Ŀ���';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //Ԥ������
                        if (!empty($val[1])) {
                            $budgetName = $val[1];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����дԤ������';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //Ԥ���ѯ����
                        $budgetCondition = array(
                            'projectCode' => $projectCode,
                            'budgetName' => $budgetName,
                            'budgetType' => $budgetType
                        );

                        //����
                        $price = $val[2] === '' ? 'NONE' : sprintf("%f", trim($val[2]));
                        if ($price != 'NONE') {
                            $updateRows['price'] = $price;
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //����1
                        $numberOne = $val[3] === '' ? 'NONE' : sprintf("%f", abs(trim($val[3])));
                        if ($numberOne != 'NONE') {
                            $updateRows['numberOne'] = $numberOne;
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����1';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //����2
                        $numberTwo = $val[4] === '' ? 'NONE' : sprintf("%f", abs(trim($val[4])));
                        if ($numberTwo != 'NONE') {
                            $updateRows['numberTwo'] = $numberTwo;
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!û����д����2';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //����Ԥ��
                        $updateRows['amount'] = bcmul($numberTwo, bcmul($numberOne, $price, 2), 2);
                        //����,����¼�븺ֵ
                        $actual = $val[6] === '' ? 'NONE' : sprintf("%f", trim($val[6]));
                        if ($actual != 'NONE') {
                            if (is_numeric($actual)) {
                                $updateRows['actFee'] = $actual;
                            } else {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!<����>����д����';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        }

                        //��ע
                        $updateRows['remark'] = $val[7] ? $val[7] : '';

                        try {
                            $this->start_d();

                            //�ж�Ԥ���Ƿ����
                            $esmbudgetObj = $esmbudgetDao->find($budgetCondition, null, 'id');
                            if ($esmbudgetObj) {
                                if (!empty($actual)) {  //�ж��Ƿ���ھ���  add chenrf
                                    $esmbudgetDao->updateValById($esmbudgetObj['id'], 'actFee', $actual);
                                    unset($updateRows['actFee']);
                                }
                                $esmbudgetDao->update(array('id' => $esmbudgetObj['id']), $updateRows);
                            } else {
                                //����һЩ��Ϣ
                                $updateRows['budgetId'] = '';
                                $updateRows['budgetName'] = $budgetName;
                                $updateRows['budgetType'] = $budgetType;
                                $updateRows['parentId'] = '-1';
                                $updateRows['parentName'] = $budgetTypeArr[$budgetType];
                                $updateRows['projectCode'] = $projectCode;
                                $updateRows['projectId'] = $projectCache[$projectCode]['id'];
                                $updateRows['projectName'] = $projectCache[$projectCode]['projectName'];
                                $esmbudgetDao->addOrg_d($updateRows);
                            }

                            $tempArr['result'] = '���³ɹ�';
                            //������Ŀ����ĿԤ��,����
                            $projectInfo = array(
                                'projectId' => $projectCache[$projectCode]['id']
                            );
                            $esmbudgetDao->updateProject_d($projectInfo, true);

                            // ������Ŀ����
                            $this->calProjectFee_d($projectCode);

                            //���¼�����Ŀ���ý���
                            $this->calFeeProcessByCode_d($projectCode, 2);

                            unset($updateRows);
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
     * ������������
     */
    function updateProjectPersonBudget_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        //ʵ����Ԥ�㲿��
        $esmmemberDao = new model_engineering_member_esmmember();
        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $projectCode = $val[0];
                        //��Ŀ���
                        if (!empty($projectCode)) {
                            $proCodeInfo = $this->find(array('projectCode' => $projectCode), null, 'projectCode,id,projectName');
                            if (empty($proCodeInfo)) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�޴�<��Ŀ���>';
                                array_push($resultArr, $tempArr);
                                continue;
                            } else {
                                $projectId = $proCodeInfo['id'];
                                $memberId = 'SYSTEM';
                                $memberName = 'ϵͳ��¼';
                                $projectName = $proCodeInfo['projectName'];
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!<��Ŀ���>����Ϊ��';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        //�����ɱ�
                        $feePerson = $val[1] === '' ? 'NONE' : sprintf("%f", trim($val[1]));
                        if ($feePerson != 'NONE') {
                            $updateRows['feePerson'] = $feePerson;
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!<����>����д����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��ѯ����
                        $budgetCondition = array(
                            'projectCode' => $projectCode,
                            'memberId' => $memberId,
                            'memberName' => $memberName
                        );
                        try {
                            $this->start_d();
                            //�ж��Ƿ����
                            $esmmemberObj = $esmmemberDao->find($budgetCondition, null, 'id');
                            if ($esmmemberObj) {
                                if ($feePerson) {  //�ж��Ƿ���ھ���  add chenrf
                                    $esmmemberDao->updateValById($esmmemberObj['id'], 'feePerson', $feePerson);
                                    unset($updateRows['feePerson']);
                                }
                                $esmmemberDao->update(array('id' => $esmmemberObj['id']), $updateRows);
                            } else {
                                //����һЩ��Ϣ
                                $updateRows['projectCode'] = $projectCode;
                                $updateRows['projectId'] = $projectId;
                                $updateRows['projectName'] = $projectName;
                                $updateRows['feePerson'] = $feePerson;
                                $updateRows['memberId'] = $memberId;
                                $updateRows['memberName'] = $memberName;
                                $esmmemberDao->addOrg_d($updateRows);
                            }
                            $tempArr['result'] = '���³ɹ�';

                            //��ȡ��Ŀ���ܷ���
                            $projectPersonFee = $esmmemberDao->getFeePerson_d($projectId);
                            $this->updateFeePerson_d($projectId, $projectPersonFee);

                            //���¼�����Ŀ���ý���
                            $this->calFeeProcessByCode_d($projectId, 2);

                            unset($updateRows);
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
     * �����豸����
     */
    function updateProjectFeeEqu_d()
    {
        set_time_limit(0);
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $projectCode = $val[0];
                        //��Ŀ���
                        if (!empty($projectCode)) {
                            $proCodeInfo = $this->find(array('projectCode' => $projectCode), null, 'projectCode,id,projectName');
                            if (empty($proCodeInfo)) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�޴�<��Ŀ���>';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!<��Ŀ���>����Ϊ��';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        $feeEqu = $val[1] === '' ? 'NONE' : sprintf("%f", trim($val[1]));
                        if ($feeEqu != 'NONE') {
                            $updateRows['feeEqu'] = $val[1];
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!<����>����д����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //��ѯ����
                        $budgetCondition = array(
                            'projectCode' => $projectCode
                        );
                        try {
                            $this->start_d();
                            $this->update($budgetCondition, $updateRows);
                            $tempArr['result'] = '���³ɹ�';

                            unset($updateRows);
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
     * �����ɱ�
     * @param $type 0-�����ɱ� 1-�����ɱ�
     * @param bool $isAddBefore true-�����ۼ� false-���Ǹ���
     * @return array
     */
    function updateProjectShipCost_d($type,$isAddBefore = false)
    {
        set_time_limit(0);
        ini_set("memory_limit", "1000M");
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $fileType = $_FILES["inputExcel"]["type"];
        $resultArr = array();//�������
        $tempArr = array();
        if ($type == 0) {
            $tableName = "oa_esm_project_shipcost";
        }
        if ($type == 1) {
            $tableName = "oa_esm_project_othercost";
        }

        //�жϵ��������Ƿ�Ϊexcel��
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //������ѭ��
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        $projectCode = $val[0];
                        //��Ŀ���
                        if (!empty($projectCode)) {
                            $proDao = new model_contract_conproject_conproject();
                            $proCodeInfo = $proDao->find(array('projectCode' => $projectCode), null, 'projectCode,id,projectName');
                            if (empty($proCodeInfo)) {
                                $tempArr['docCode'] = '��' . $actNum . '������';
                                $tempArr['result'] = '����ʧ��!�޴�<��Ŀ���>';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!<��Ŀ���>����Ϊ��';
                            array_push($resultArr, $tempArr);
                            continue;
                        }
                        $feeEqu = $val[1] === '' ? 'NONE' : sprintf("%f", trim($val[1]));
                        if ($feeEqu != 'NONE') {
                            $isSet = $this->get_table_fields($tableName, "projectCode='$projectCode'", "id");
                            if (!empty($isSet)) {
                                if($type == 0 && $isAddBefore){// ���뷢���ɱ�(�����ۼ�)
                                    $dataInfo = $this->_db->get_one("select id,cost from {$tableName} where projectCode = '" . $projectCode . "';");
                                    $dataInfoCost = (isset($dataInfo['cost']) && $dataInfo['cost'] > 0)? $dataInfo['cost'] : 0;
                                    $feeEqu = round(bcadd($feeEqu,$dataInfoCost,4),2);
                                }
                                //update
                                $sql = "update $tableName set cost='$feeEqu' where projectCode='$projectCode'";
                            } else {
                                //insert
                                $sql = "insert into $tableName(projectCode,cost) values ('$projectCode','$feeEqu')";
                            }
                        } else {
                            $tempArr['docCode'] = '��' . $actNum . '������';
                            $tempArr['result'] = '����ʧ��!<�ɱ�>����д����';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        try {
                            $this->start_d();
                            $this->_db->query($sql);
                            $tempArr['result'] = '���³ɹ�';

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
     *  ��ͬ��Ŀ����
     */
    function conprojectExcel()
    {

        set_time_limit(0);
        ini_set('memory_limit', '128M');
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $dao = new model_contract_conproject_importConprojectUtil ();
            $excelData = $dao->readExcelData($filename, $temp_name);
            spl_autoload_register('__autoload');
            $conProDao = new model_contract_conproject_conproject();
            $resultArr = $conProDao->importProInfo_d($excelData);
            if ($resultArr)
                echo util_excelUtil:: finalceResult($resultArr, "������", array(
                    "��ͬ���",
                    "���"
                ));
            else
                echo "<script>alert('����ʧ��!');self.parent.show_page();self.parent.tb_remove();</script>";
        } else {
            echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');self.parent.show_page();self.parent.tb_remove();</script>";
        }

    }

    /***************************���뵼������*****************************/

    /**
     * ������ĿԤ��
     * edit on 2013-05-31 by kuangzw ������Ԥ�������Ԥ�㶼������ĿԤ���д���
     * @param $id
     * @return bool
     * @throws Exception
     */
    function updateProjectBudget_d($id)
    {
        $object['id'] = $id;
        try {
            $this->start_d();

            //��ȡ��Ŀ�ֳ�Ԥ��
            $esmbudgetDao = new model_engineering_budget_esmbudget();
            $budgetArr = $esmbudgetDao->getProjectBudgetOnce_d($id);
            $object = array_merge($object, $budgetArr);

            //��ȡ��Ŀ�豸Ԥ��
            $esmresourcesDao = new model_engineering_resources_esmresources();
            $object['budgetEqu'] = $esmresourcesDao->getProjectBudget_d($id);

            //������Ŀ
            $this->edit_d($object);

            $this->updateBudgetAll_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ������Ŀ��Ϣ
     * @param $id
     * @param $updateArr
     * @return bool
     * @throws Exception
     */
    function updateProject_d($id, $updateArr)
    {
        $object = $updateArr;
        $object['id'] = $id;
        try {
            $this->start_d();

            //������Ŀ
            $this->edit_d($object);

            $this->updateBudgetAll_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * �ؼ�����Ŀ��Ԥ��
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function updateBudgetAll_d($id)
    {
        try {
            return $this->_db->query("update $this->tbl_name set budgetAll = budgetField + budgetOutsourcing +
				budgetEqu + budgetPerson + budgetOther where id in ($id)");
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ������Ŀ����
     * @param $id
     * @param $updateArr
     * @return bool
     * @throws Exception
     */
    function updateProjectPeople_d($id, $updateArr)
    {
        $object = $updateArr;
        $object['id'] = $id;
        try {
            $this->start_d();

            //������Ŀ
            $this->edit_d($object);

            $this->updatePeople_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * ������Ŀ����
     * @param $id
     * @return mixed
     * @throws Exception
     */
    function updatePeople_d($id)
    {
        try {
            return $this->_db->query("update " . $this->tbl_name . " set peopleNumber = dlPeople + outsourcingPeople where id = " . $id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /************************ ������ɺ��� *******************/
    /**
     * ������ɺ���
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterAudit_d($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);

        $id = $folowInfo['objId'];
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        //���Ѿ����ó�ʼ��������ò���
        if (!empty($obj['contractType']) && in_array($obj['contractType'], $this->initContractType)) {
            //���ò���
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessConfirm_d($obj, $initObj);
            }
        }

        //��Ŀִ�й켣-�ڽ���¼,��¼������־
        $esmlogDao = new model_engineering_baseinfo_esmlog();
        $esmlogDao->addLog_d($id, '�ڽ�', null);
        return true;
    }

    /**
     * �깤������ɺ���
     * @param $spid
     * @return bool
     * @throws Exception
     */
    function dealAfterCompleteAudit_d($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);

        $id = $folowInfo['objId'];
        $obj = $this->get_d($id);
        $obj = $this->feeDeal($obj);
        //���Ѿ����ó�ʼ��������ò���
        if (!empty($obj['contractType']) && in_array($obj['contractType'], $this->initContractType)) {
            //���ò���
            $newClass = $this->getClass($obj['contractType']);
            if ($newClass) {
                $initObj = new $newClass();
                $this->businessClose_d($obj, $initObj);
            }
        }

        //��Ŀִ�й켣-�ڽ���¼,��¼������־
        $esmlogDao = new model_engineering_baseinfo_esmlog();
        $esmlogDao->addLog_d($id, '�깤', null);
        return true;
    }

    /**
     * ���·��� - ����Դ�����������ʱ����Ŀ�е�Դ�����Ҳ���б��
     * @param $contractId
     * @param string $contractType
     * @return mixed
     * @throws $e
     */
    function updateContractMoney_d($contractId, $contractType = 'GCXMYD-01')
    {
        if (in_array($contractType, $this->initContractType)) {
            $newClass = $this->getClass($contractType);
            if ($newClass) {
                $initObj = new $newClass();
                $contractMoney = $this->getContractMoney_d($contractId, $initObj);

                try {
                    foreach ($contractMoney as $k => $v) {
                        $updateSql = ($contractType == 'GCXMYD-01')? "UPDATE oa_esm_project SET contractMoney = estimatesRate * " . $v['contractMoney'] . "/100 ," .
                            " estimates = estimatesRate * " . $v['estimates'] . "/100  WHERE contractId = " . $contractId .
                            " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'" :
                            "UPDATE oa_esm_project SET contractMoney = workRate * " . $v['contractMoney'] . "/100 ," .
                            " estimates = workRate * " . $v['estimates'] . "/100  WHERE contractId = " . $contractId .
                            " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'";
                        $this->_db->query($updateSql);
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }

        return true;
    }

    /**
     * ������Ŀ���ͳ�� - ���ݵ��ݺ�
     * @param $projectCodeArr
     * @param string $contractType
     * @return bool
     * @throws Exception
     */
    function updateContractMoneyByProjectCode_d($projectCodeArr, $contractType = 'GCXMYD-01')
    {
        if (in_array($contractType, $this->initContractType)) {
            $newClass = $this->getClass($contractType);
            if ($newClass) {
                $initObj = new $newClass();
                try {
                    foreach ($projectCodeArr as $vi) {
                        $obj = $this->find(array('projectCode' => $vi), null, 'contractId');
                        $contractMoney = $this->getContractMoney_d($obj['contractId'], $initObj);

                        foreach ($contractMoney as $k => $v) {
                            $updateSql = ($contractType == 'GCXMYD-01')? "UPDATE oa_esm_project SET contractMoney = estimatesRate * " . $v['contractMoney'] . "/100 ," .
                                " estimates = estimatesRate * " . $v['estimates'] . "/100  WHERE contractId = " . $obj['contractId'] .
                                " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'" :
                                "UPDATE oa_esm_project SET contractMoney = workRate * " . $v['contractMoney'] . "/100 ," .
                                " estimates = workRate * " . $v['estimates'] . "/100  WHERE contractId = " . $obj['contractId'] .
                                " AND contractType = '" . $contractType . "' AND newProLine = '" . $k . "'";
                            $this->_db->query($updateSql);
                        }
                    }
                } catch (Exception $e) {
                    throw $e;
                }
            }
        }
        return true;
    }

    /**
     * �ж���Ŀ��ǰ�����Ƿ��Ǳ��
     * @param $projectId
     * @return bool
     */
    function actionIsChange_d($projectId)
    {
        if (!$projectId) {
            return false;
        }
        $obj = $this->get_d($projectId);
        if ($obj['ExaStatus'] == '���ύ' || $obj['ExaStatus'] == '���') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * ��ȡ��ͬ��Ŀ����
     * @param $contractId
     * @return int|string
     */
    function getContractProjectProcess_d($contractId)
    {
        // ��ѯ��ǰ������Ŀ
        $data = $this->findAll(array('contractId' => $contractId, 'contractType' => 'GCXMYD-01'));

        if (empty($data)) {
            return 0;
        } else {
            // ��Ʒ��ȡ
            $productDao = self::getObjCache('model_contract_contract_product');
            $productLines = $productDao->getProductLineDetails_d($contractId);

            // ���ݲ��
            $dataSplit = $this->splitProjectByProductLine_d($data);

            $esmRate = 0; // ����ռ��
            $process = 0; // ��������ܽ���
            foreach ($productLines as $k => $v) {
                if ($k == 17) {
                    foreach ($v as $ki => $vi) {
                        // ��ǰ���߽��
                        $proLineProcess = 0;

                        // ���߽��Ȼ�ȡ
                        foreach ($dataSplit[$vi['productLine']] as $vii) {
                            $proLineProcess = round(bcadd(bcmul($vii['projectProcess'], bcdiv($vii['workRate'], 100, 4), 4), $proLineProcess, 4), 2);
                        }

                        // ����ռ�ȵ���
                        $esmRate = bcadd($esmRate, $vi['productLineRate'], 2);

                        // ���ȵ���
                        $process = bcadd($process, round(bcmul($proLineProcess, bcdiv($vi['productLineRate'], 100, 4), 4), 2), 2);
                    }
                }
            }
            return round(bcmul(bcdiv($process, $esmRate, 4), 100, 4), 4);
        }
    }

    /**
     * ���ݲ��߲����Ŀ��Ϣ
     * @param $data
     * @return array
     */
    function splitProjectByProductLine_d($data)
    {
        $rst = array();
        foreach ($data as $v) {
            if (!isset($rst[$v['newProLine']])) {
                $rst[$v['newProLine']] = array();
            }
            $rst[$v['newProLine']][] = $v;
        }
        return $rst;
    }

    /**
     * �鿴��������Ϣ����
     * @param $obj
     * @return mixed
     */
    function feeDeal($obj)
    {
        // ���������еĲ���ID
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy_id');
        $filterArr = $otherDatasDao->getConfig('engineering_budget_expense_id');
        // ����ϵͳ���û�ȡ
        $feeNow = $this->getFeeNow_d($obj['projectCode']);

        // ����ϵͳ�н��ñ�����
        $feeFilterNow = $this->getSomeFeeSum_d($obj['projectCode'], $filterArr);

        // ����ϵͳ�еĲ������û�ȡ
        $feeSubsidyNow = $this->getSomeFeeSum_d($obj['projectCode'], $subsidyArr);

        // �ֳ�������� = �������� - �������� - ���ñ�����
        $feeField = bcsub($feeNow, $feeSubsidyNow, 2);
        $feeField = bcsub($feeField, $feeFilterNow, 2);

//        if ($obj['feePayables'] == 0) {// ���֧���ľ���Ϊ0��ʵʱ��ȡ��ǰ��֧������
            $feePayables = 0;
            $fieldRecordDao = self::getObjCache('model_engineering_records_esmfieldrecord');
            $fieldRecordArr = $fieldRecordDao->feeDetail_d('payables', $obj['id']);

            foreach ($fieldRecordArr as $costVal) {
                $feePayables = bcadd($feePayables, $costVal, 3);
            }
            $obj['feePayables'] = $feePayables;
//        }
        // �����⳵�Ǽǵ�Ԥ���� PMS 715
        $rentalcarDao = new model_outsourcing_vehicle_rentalcar();
        $rentalcarCostArr = $rentalcarDao->getProjectAuditingCarFee($obj['id']);
        $auditingCarFee = ($rentalcarCostArr)? $rentalcarCostArr['totalCost'] : 0;

        //ʵʱ�������ã��ֳ����� = ��������+���÷�̯����+����ά��+��Ʊ����
        $obj['feeFieldClean'] = bcadd($feeField, $auditingCarFee, 2);  // ��������
        $feeField = bcadd($feeField, $obj['feePayables'], 2);  //���÷�̯����
        $feeField = bcadd($feeField, $obj['feeFieldImport'], 2);  //����ά��
        $feeField = bcadd($feeField, $obj['feeCar'], 2);  //�⳵
        $feeField = bcadd($feeField, $auditingCarFee, 2);  //�⳵�Ǽ�Ԥ��
        $feeField = bcadd($feeField, $obj['feeFlights'], 2);
        $obj['feeField'] = $feeField;  //��������
        $obj['feeFieldProcess'] = $this->countProcess_d($obj['budgetField'], $feeField); // �ֳ����ý���

        // PK������������
        $projectList = $this->PKFeeDeal_d(array($obj));
        $obj['budgetPK'] = $projectList[0]['budgetPK']; // PKԤ��
        $obj['feePK'] = $projectList[0]['feePK']; // PK����
        $obj['budgetOther'] = bcadd($obj['budgetOther'], $obj['budgetPK'], 2);
        $obj['feeOther'] = bcadd($obj['feeOther'], $obj['feePK'], 2);
        $obj['feeOtherProcess'] = $this->countProcess_d($obj['budgetOther'], $obj['feeOther']); // ����

        // Ԥ������
        $obj['budgetAllClean'] = $obj['budgetAll'];
        $obj['budgetAll'] = bcadd($obj['budgetAll'], $projectList[0]['budgetPK'], 2);

        // �������� = �������㣨�ӹ���ϵͳȡ�� + ��������
        $obj['feePersonClean'] = $obj['feePerson'];
        $obj['feeSubsidy'] = $feeSubsidyNow;
        $obj['feePerson'] = bcadd($obj['feePerson'], $feeSubsidyNow, 2);
        $obj['feePerson'] = bcadd($obj['feePerson'], $obj['feeSubsidyImport'], 2); //�����ɱ��ϼ�
        $obj['feePersonProcess'] = $this->countProcess_d($obj['budgetPerson'], $obj['feePerson']); // �豸����

        // ��ȡ�豸����
        $esmDeviceFeeDao = new model_engineering_resources_esmdevicefee();
        $obj['feeEquClean'] = $obj['feeEqu'];
        $obj['feeEquDepr'] = bcadd($esmDeviceFeeDao->getDeviceFee_d($obj['id'], '2'),
            $esmDeviceFeeDao->getDeviceFee_d($obj['id'], '1'), 2); // ���豸ϵͳ��ȡ�ľ���
        $obj['feeEqu'] = bcadd($obj['feeEquClean'], $obj['feeEquDepr'], 2);
        $obj['feeEqu'] = bcadd($obj['feeEqu'], $obj['feeEquImport'], 2);  //�豸�ɱ��ϼ�
        $obj['feeEquProcess'] = $this->countProcess_d($obj['budgetEqu'], $obj['feeEqu']); // �豸����

        $obj['feeOutsourcingProcess'] = $this->countProcess_d($obj['budgetOutsourcing'], $obj['feeOutsourcing']); // �������

        // �ܷ�������
        $feeAll = bcadd($obj['feePerson'], $obj['feeEqu'], 2);
        $feeAll = bcadd($feeAll, $obj['feeOther'], 2);
        $feeAll = bcadd($feeAll, $obj['feeOutsourcing'], 2);
        $feeAll = bcadd($feeAll, $obj['feeField'], 2);

        $obj['feeAll'] = $feeAll;  //�ܾ���

        $obj['feeAllProcess'] = $this->countProcess_d($obj['budgetAll'], $feeAll);  //�ܷ��ý���

        $loanDao = new model_loan_loan_loan();
        $obj['inLoanAmount'] = $loanDao->getInLoanAmountByProjId($obj['id']);  //��Ŀ���
        $obj['noPayLoanAmount'] = $loanDao->getNoPayLoanAmountByProjId($obj['id']);  //������

        return $obj;
    }

    /**
     * ��ͬ��Ϣ����
     * @param $obj
     * @return mixed
     */
    function contractDeal($obj)
    {
        /****************** ͨ�ô��� - ���� ***********************/
        //��ʵʩ����,��ǰ����-Ԥ�ƿ�ʼ����,�����δ��ʼ,��Ϊ0,��ʵ�ʹ��ڲ�Ϊ0,��Ϊʵ�ʹ���
        $obj['workedDays'] = $obj['actBeginDate'] && $obj['actBeginDate'] != '0000-00-00' && $obj['actEndDate'] && $obj['actEndDate'] != '0000-00-00' ?
            round((strtotime($obj['actEndDate']) - strtotime($obj['actBeginDate'])) / 86400) + 1 : "";

        $obj['remainingDuration'] = time() < strtotime($obj['planEndDate']) ?
            round((strtotime($obj['planEndDate']) - time()) / 3600 / 24) + 1 : 0;
        // CPI
        $obj['CPI'] = round(bcdiv(bcmul(bcdiv($obj['projectProcess'], 100, 4), $obj['budgetAll'], 6), $obj['feeAll'], 6), 2);
        // SPI
        $thisTime = strtotime($obj['planEndDate']) < strtotime(day_date) ? strtotime($obj['planEndDate']) : strtotime(day_date);
        $obj['planProcess'] = round(bcmul(bcdiv(($thisTime - strtotime($obj['planBeginDate'])) / 86400 + 1, $obj['expectedDuration'], 6), 100, 4), 2);
        if ($obj['planProcess'] > 100) {
            $obj['planProcess'] = 100;
        }
        $obj['SPI'] = round(bcdiv($obj['projectProcess'], $obj['planProcess'], 6), 2);

        // ��ȡԤ������
        $statusreportDao = self::getObjCache('model_engineering_project_statusreport');
        $obj['warningNum'] = $statusreportDao->getNewestWarningNum_d($obj['id']);

        /****************** ͨ�ô��� - ���� ***********************/

        // ��ͬ����Ŀ
        if ($obj['contractType'] == 'GCXMYD-01') {
            //��ͬ˰��(ȡ�ۺ�˰��)
            $conDao = self::getObjCache('model_contract_contract_contract');
            $conArr = $conDao->get_d($obj['contractId']);
            $conprojectDao = self::getObjCache('model_contract_conproject_conproject');

            // ��ҵ��/���
            $obj['moduleName'] = $conArr['moduleName'];
            $obj['module'] = $conArr['module'];
            $obj['areaCode'] = $conArr['areaCode'];
            $obj['areaName'] = $conArr['areaName'];
            $obj['contractStatus'] = $conArr['state'];
            // ��ͬ�ۺ�˰��
            $contractRate = $conprojectDao->getTxaRate($conArr);
            $obj['contractRate'] = $contractRate > 0 ? round(bcmul($contractRate, 100, 3), 2) : 0;
            // ��Ŀռ��
            $newProLineMoney = $conprojectDao->getAccBycid($obj['contractId'], $obj['newProLine'], 17, 9, 1);
            $proportion = $conprojectDao->getAccBycid($obj['contractId'], $obj['newProLine'], 17, 9);
            $obj['lineRate'] = $proportion;
            $obj['projectRate'] = bcmul($proportion, bcdiv($obj['workRate'], 100, 9), 9);

            // ��ͬ�ۿ�ӻ���
            $deductAndBad = $conArr['deductMoney'];//bcadd($conArr['deductMoney'], $conArr['badMoney'], 2);
            // ��Ŀ�ۿ���
            $obj['deductMoney'] = bcmul($deductAndBad, bcdiv($obj['projectRate'], 100, 9), 9);
            $obj['contractDeduct'] = $deductAndBad; // ��ͬ�ۿ���
            $obj['contractMoney'] = $conArr['contractMoney']; // ��ͬ���
            $obj['uninvoiceMoney'] = $conArr['uninvoiceMoney']; // ����Ʊ���
            $obj['contractMoneyDeduct'] = bcsub($conArr['contractMoney'],
                bcadd($deductAndBad, $conArr['uninvoiceMoney'], 9), 9); // ��ͬ���Ѿ���ȥ�ۿ�ͬ����Ʊ��

            $obj['projectMoneyWithTax'] = bcmul($newProLineMoney, bcdiv($obj['workRate'], 100, 9), 9); // ˰ǰ - ��˰
            $obj['projectMoney'] = bcdiv($obj['projectMoneyWithTax'], bcadd(1, $contractRate, 9), 9); // ˰�� - ����˰
            // ��Ʊ
            $invoiceDao = self::getObjCache('model_finance_invoice_invoice');
            $invoiceInfo = $invoiceDao->getInvoiceAllMoney_d(array(
                'objId' => $obj['contractId'],
                'objType' => 'KPRK-12'
            ));
            $obj['invoiceMoney'] = $invoiceInfo['invoiceMoney'] ? $invoiceInfo['invoiceMoney'] : '0.00';
            $obj['invoiceProcess'] = bcmul(bcdiv($obj['invoiceMoney'], $obj['contractMoneyDeduct'], 9), 100, 9);
            // ����
            $incomeAllotDao = self::getObjCache('model_finance_income_incomeAllot');
            $incomeMoney = $incomeAllotDao->getIncomeMoney_d(array(
                'objId' => $obj['contractId'],
                'objType' => 'KPRK-12'
            ));
            $obj['incomeMoney'] = $incomeMoney ? $incomeMoney : '0.00';
            $obj['incomeProcess'] = bcmul(bcdiv($obj['incomeMoney'], $obj['contractMoneyDeduct'], 9), 100, 9);
            // Ӫ��Ԥ��
            if ($obj['projectProcess'] <= 98 || $obj['invoiceProcess'] >= 100) {
                $reserveRate = 0;
            } else {
                $reserveRate = $obj['invoiceProcess'] < 98 ?
                    0.02 : bcsub(1, bcdiv(min($obj['projectProcess'], $obj['invoiceProcess']), 100, 9), 9);
            }
            $obj['reserveEarnings'] = $reserveRate > 0 ?
                round(
                    bcmul(bcsub($obj['projectMoney'], bcdiv($obj['deductMoney'], bcadd(1, $contractRate, 9), 9), 9), $reserveRate, 9),
                    2)
                : '0.00';
            // ����ë����
            $obj['estimatesExgross'] = bcmul(bcsub(1, bcdiv($obj['estimates'], $obj['projectMoney'], 9), 9), 100, 9);
            // Ԥ��ë����Ԥ��ë����
            $obj['budgetProfit'] = round(bcsub($obj['projectMoney'], $obj['budgetAll'], 9), 2);
            $obj['budgetExgross'] = round(bcmul(bcsub(1, bcdiv($obj['budgetAll'], $obj['projectMoney'], 9), 9), 100, 9), 2);

            // ��ĿӪ��
            switch ($obj['incomeType']) {
                case 'SRQRFS-01' : // ����
                    // ������ȷ�� = ˰���ͬ�� * ��Ŀ���� - ��Ŀ�ۿ�/��1 + ˰�㣩 - Ӫ��Ԥ��
                    $obj['curIncome'] = bcmul($obj['projectMoney'], bcdiv($obj['projectProcess'], 100, 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], round(bcdiv($obj['deductMoney'], 1 + $contractRate, 9), 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], $obj['reserveEarnings'], 9);
                    break;
                case 'SRQRFS-02' : // ��Ʊ
                    // ����Ʊȷ�� = ��ƪ��� * ��ͬ��
                    $obj['curIncome'] = bcmul($obj['projectMoney'], bcdiv($obj['invoiceProcess'], 100, 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], round(bcdiv($obj['deductMoney'], 1 + $contractRate, 9), 9), 9);
                    $obj['reserveEarnings'] = 0;
                    break;
                case 'SRQRFS-03' : // ����
                    // ������ȷ�� = ������ * ��ͬ��
                    $obj['curIncome'] = bcmul($obj['projectMoney'], bcdiv($obj['incomeProcess'], 100, 9), 9);
                    $obj['curIncome'] = bcsub($obj['curIncome'], round(bcdiv($obj['deductMoney'], 1 + $contractRate, 9), 9), 9);
                    $obj['reserveEarnings'] = 0;
                    break;
                default :
                    $obj['curIncome'] = 0;
            }

            // ��Ŀ���봦�� - �����ͬ״̬���쳣�رգ���ô��ĿӪ��Ϊ0
            if ($obj['contractStatus'] == '7') {
                $obj['curIncome'] = 0;
            }

            //�����Ʒ:��ȡ��ͬϵͳ-��ͬ��Ʒ�嵥-��Ʒ���ƣ����ж�����/����
            $productDao = self::getObjCache('model_contract_contract_product');
            $rs = $productDao->findAll(array('contractId' => $obj['contractId'], 'newProLineCode' => $obj['newProLine']), null, 'conProductName');
            if (!empty($rs)) {
                $arr = array();
                foreach ($rs as $v) {
                    array_push($arr, $v['conProductName']);
                }
                $obj['conProductName'] = implode('/', $arr);
            } else {
                $obj['conProductName'] = "";
            }

            // С��λ��ʽ��
            $obj['projectMoneyWithTax'] = round($obj['projectMoneyWithTax'], 2);
            $obj['projectMoney'] = round($obj['projectMoney'], 2);
            $obj['curIncome'] = round($obj['curIncome'], 2);
            $obj['deductMoney'] = round($obj['deductMoney'], 2);
            $obj['invoiceProcess'] = round($obj['invoiceProcess'], 2);
            $obj['incomeProcess'] = round($obj['incomeProcess'], 2);
            $obj['estimatesExgross'] = round($obj['estimatesExgross'], 2); // ����ë����
        } else { // �ֶβ���
            $obj['projectMoneyWithTax'] = $obj['contractRate'] = $obj['projectMoney'];
            $obj['estimatesExgross'] = $obj['budgetExgross'] = 0;
            $obj['projectRate'] = $obj['workRate'];
            $obj['curIncome'] = $obj['uninvoiceMoney'] = 0;
            $obj['invoiceMoney'] = $obj['invoiceProcess'] = '0';
            $obj['incomeMoney'] = $obj['incomeProcess'] = '0';
            $obj['reserveEarnings'] = $obj['deductMoney'] = '0';
            $obj['conProductName'] = $obj['projectObjectives'] = '';

            // �����PK��Ŀ����ȡ��Ӧ�ĸ����Լ����򡢰�����Ϣ
            if ($obj['contractType'] == 'GCXMYD-04') {
                $trialprojectDao = self::getObjCache('model_projectmanagent_trialproject_trialproject');
                $trialprojectObj = $trialprojectDao->get_d($obj['contractId']);
                $obj['estimates'] = $trialprojectObj['affirmMoney'];

                // ��ҵ��/���
                $obj['moduleName'] = $trialprojectObj['moduleName'];
                $obj['module'] = $trialprojectObj['module'];
                $obj['areaCode'] = $trialprojectObj['areaCode'];
                $obj['areaName'] = $trialprojectObj['areaName'];
            }
        }

        // ��Ŀë��
        $obj['curIncome'] = sprintf("%.2f", $obj['curIncome']);
        $obj['grossProfit'] = bcsub($obj['curIncome'], $obj['feeAll'], 2);
        // ��ǰë����
        $obj['exgross'] = ($obj['curIncome'] == 0) ? '-' : round(bcmul(bcsub(1, bcdiv($obj['feeAll'], $obj['curIncome'], 9), 9), 100, 9), 2);

        $obj['projectRate'] = round($obj['projectRate'], 2);
        $obj['workRate'] = round($obj['workRate'], 2);

        return $obj;
    }

    /**
     * ��ȡ��Ŀռ��
     * @param $id
     * @return string
     */
    function getProjectRate_d($id)
    {
        // ��Ŀ��Ϣ��ȡ
        $obj = $this->find(array('id' => $id), null, 'contractId,newProLine,workRate');

        // ��ʼ������Ŀռ��
        $conprojectDao = self::getObjCache('model_contract_conproject_conproject');
        $proportion = $conprojectDao->getAccBycid($obj['contractId'], $obj['newProLine'], 17, 9);
        $proportion = $proportion ? $proportion : 0;
        return bcmul($proportion, bcdiv($obj['workRate'], 100, 9), 9);
    }

    /**
     * ���ȼ���
     * @param $budget
     * @param $fee
     * @return string
     */
    function countProcess_d($budget, $fee)
    {
        if ($budget && $budget != 0.00 && $fee && $fee != 0.00) {
            return round(bcmul(bcdiv($fee, $budget, 6), 100, 4), 2);
        } else {
            return "0.00";
        }
    }

    /**
     * ���º�ͬ
     * @param $keyArr ��ѯ��Ϣ����
     * @param string $keyType ��ѯ��Ŀ�õ���Ϣ
     * @return bool
     * @throws Exception
     */
    function updateContractInfo_d($keyArr, $keyType = 'id')
    {
        try {
            $this->start_d();

            foreach ($keyArr as $val) {
                $projectInfo = $this->find(array($keyType => $val), null, 'id,contractId,contractType');
                if (in_array($projectInfo['contractType'], $this->initContractType)) {
                    //���ò��� - ȷ�Ϻ��������
                    if (!isset($strategyArr[$projectInfo['contractType']])) {
                        $newClass = $this->getClass($projectInfo['contractType']);
                        if ($newClass) {
                            $strategyArr[$projectInfo['contractType']] = new $newClass();
                        }
                    }
                    //����ȷ��
                    $this->businessConfirm_d($projectInfo, $strategyArr[$projectInfo['contractType']]);
                }
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /******************** ͬ��project_info,xm_lx��Ĳ���***************/
    /**
     * ͬ����Ŀ
     * ͬ��project_info���е���Ϣ
     * project_info �ֶ� deptId,name,number,manager,status,rand_key,flag
     * project_info�� flag 1 Ϊ�ڽ�/�ﱸ ,2 Ϊ��Ŀ�ر�
     * ͬ��xm_lx���е���Ϣ
     * xm_lx �ֶ� SID,Name,ProjectNo,DeptNo,Manager,flag,FlagClose
     * xm_lx�� FlagClose 0 Ϊ�ڽ�/�ﱸ ,1 Ϊ��Ŀ�ر�
     * @param $object ��Ҫ���� ��Ŀ���� �� ��Ŀ��� �� ��Ŀ״̬
     * @return bool
     */
    function updateProjectInfo_p($object)
    {
        if (!is_array($object)) {
            $object = $this->find(array('projectCode' => $object), null, 'id,projectName,projectCode,deptId,managerId,status');
        }
        if (!$object) return true;
        if (!isset($object['id'])) {
            $temp = $this->find(array('projectCode' => $object['projectCode']), null, 'id');
            $object['id'] = $temp['id'];
        }
        //��Ŀ��״̬ -- Ĭ�Ͽ���
        if (isset($object['status']) && $object['status'] == 'GCXMZT03') {
            $flag = 2;
            $flagClose = 1;
        } else {
            $flag = 1;
            $flagClose = 0;
        }
        //�ж���Ŀ��������,ȡ��һ��
        $managerIdArr = explode(',', $object['managerId']);
        if (count($managerIdArr) > 1) {
            $managerId = array_pop($managerIdArr);
        } else {
            $managerId = $object['managerId'];
        }

        //������Ŀ�����ݽӿ�
        //ͬ��project_info
        $projectInfo = $this->_db->getArray("select id from project_info where number = '" . $object['projectCode'] . "'");
        if (!$projectInfo) {
            $inSql = "insert into project_info (dept_id,name,number,manager,status,flag,rand_key,projectId) " .
                "value " . "('" . $object['deptId'] . "','" . $object['projectName'] . "','" . $object['projectCode'] .
                "','" . $managerId . "',1,$flag,MD5('" . $object['projectCode'] . "')," . $object['id'] . ")";
        } else {
            $inSql = "UPDATE project_info SET name = '" . $object['projectName'] .
                "',manager = '$managerId',flag = '$flag',projectId = " . $object['id'] .
                " WHERE number = '" . $object['projectCode'] . "'";
        }
        $this->_db->query($inSql);
        //ͬ��xm_lx
        $projectInfo = $this->_db->getArray("select ID from xm_lx where ProjectNo = '" . $object['projectCode'] . "'");
        if (!$projectInfo) {
            $inSql = "insert into xm_lx (SID,Name,ProjectNo,DeptNo,Manager,flag,FlagClose) " .
                "value " . "(1,'" . $object['projectName'] . "','" . $object['projectCode'] . "','" . $object['deptId'] .
                "','" . $managerId . "','1','" . $flagClose . "')";
        } else {
            $inSql = "UPDATE xm_lx SET Name = '" . $object['projectName'] .
                "',Manager = '$managerId',FlagClose = '$flagClose',DeptNo = '" . $object['deptId'] .
                "' WHERE ProjectNo = '" . $object['projectCode'] . "'";
        }
        $this->_db->query($inSql);

        // ������Ŀ����Ŀ����
        $this->updateContractMoneyByProjectCode_d(array($object['projectCode']));
    }

    /**
     * ��������Ŀid��ȡ����Ŀid
     * @param $projectId
     * @return string
     */
    function getOldProjectId_d($projectId)
    {
        $projectInfo = $this->find(array('id' => $projectId), null, 'projectCode');
        //��ȡ����һ�ű��id
        return $this->get_table_fields('project_info', ' number = "' . $projectInfo['projectCode'] . '"', 'id');
    }

    /**
     * ����ë����
     * @param $object
     * @return bool
     */
    function updateExgross_d($object)
    {
        set_time_limit(0); // ����ʱ
        try {
            //��ȡ��Ŀ��Ϣ
            $this->searchArr = array('contractType' => 'GCXMYD-01', 'ExaStatus' => AUDITED);
            if ($object['projectCode']) $this->searchArr['projectCodeArr'] = $object['projectCode'];
            if ($object['status']) $this->searchArr['statusArr'] = $object['status'];
            $rows = $this->list_d();

            $conDao = new model_contract_contract_contract(); // ��ͬ
            $conprojectDao = new model_contract_conproject_conproject(); // ��ͬ��Ŀ

            foreach ($rows as $v) {
                $conArr = $conDao->get_d($v['contractId']);
                $contractRate = $conprojectDao->getTxaRate($conArr);
                $contractRate = bcmul($contractRate, 100);
                // ˰����Ŀ���
                $v['projectMoney'] = bcdiv($v['contractMoney'], bcadd(1, $contractRate, 2), 2);
                // ��ǰ����
                $v['curIncome'] = bcmul($v['projectMoney'], bcdiv($v['projectProcess'], 100, 4), 2);
                // ��ǰë����
                $v['exgross'] = round(bcmul(bcsub(1, bcdiv($v['feeAll'], $v['curIncome'], 6), 6), 100, 4), 2);

                // �������µ����ݣ����Ҹ���������Ϣ
                $updateProject = array('id' => $v['id'], 'exgross' => $v['exgross']);
                $this->addUpdateInfo($updateProject);

                // ִ�и���
                $this->updateById($updateProject);
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * ���ݺ�ͬid�ͺ�ͬ���ͻ�ȡ��Ŀ��Ϣ
     * @param array $contractIdArr
     * @param string $contractType
     * @return bool|array
     */
    function getProjectList_d(array $contractIdArr, $contractType = 'GCXMYD-01')
    {
        if (!is_array($contractIdArr) || empty($contractIdArr)) return false;

        // �б����ݻ�ȡ
        $this->searchArr = array('contractIdArr' => implode(',', $contractIdArr), 'contractType' => $contractType);
        $rows = $this->list_d('select_defaultAndFee');

        // ����Ԥ�㣬���þ���
        $rows = $this->PKFeeDeal_d($rows);

        // ת���hash����
        $hashRows = array();
        foreach ($rows as $v) {
            // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
            $pType = isset($v['pType']) ? $v['pType'] : 'esm';
            // ֻ�з�����Ŀ�ż�����Щ����
            if ($pType == 'esm') {
                $v = $this->contractDeal($v);
            }
            $hashRows[$v['id']] = $v;
        }

        return $hashRows;
    }

    /**
     * ���ݺ�ͬ��Ż�ȡ��Ŀ��Ϣ��Ŀǰ����ֻ�õ���Ŀ��������������û��
     * @param $contractCode
     * @return array|bool
     */
    function getProjectListByContractCode_d($contractCode)
    {
        if (empty($contractCode)) return false;

        // �б����ݻ�ȡ
        $this->searchArr = array('contractCode' => $contractCode);
        $rows = $this->list_d('select_defaultAndFee');

        // ��ͬ��Ŀ�����
        $conProjectDao = new model_contract_conproject_conproject();
        foreach ($rows as $k => $v) {
            // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
            $pType = isset($v['pType']) ? $v['pType'] : 'esm';
            // ֻ�з�����Ŀ�ż�����Щ����
            if ($pType == 'esm') {
                $rows[$k] = $this->contractDeal($v);
            } else {
                $rows[$k]['projectMoneyWithTax'] = $conProjectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //��Ŀ��ͬ��
            }
        }

        return $rows;
    }

    /**
     * ��ȡһ����Ŀ��id����ָ��ͬ��������ࣩ
     * @return string
     */
    function getNormalProjectIds_d()
    {
        $this->searchArr = array(
            'contractTypes' => 'GCXMYD-01,GCXMYD-04'
        );
        $rows = $this->list_d();

        if (!empty($rows)) {
            $idArr = array();
            foreach ($rows as $v) {
                $idArr[] = $v['id'];
            }
            return implode(",", $idArr);
        } else {
            return "";
        }
    }

    /**
     * ��ȡ�������������������ʡ����ͻ�����
     */
    function getProvincesAndCustomerType_d()
    {
        $salepersonDao = new model_system_saleperson_saleperson();
        $rs = $salepersonDao->getSaleArea($_SESSION['USER_ID']);
        if (is_array($rs)) {
            $pcArr = array();
            foreach ($rs as $val) {
                if ($val['isUse'] == 0) {
                    array_push($pcArr, array('province' => $val['province'], 'customerType' => $val['customerType']));
                }
            }
            return $pcArr;
        } else {
            return '';
        }
    }

    /**
     * �����Ŀ
     * @param $object
     * @return bool
     */
    function finish_d($object)
    {
        $email = $object['email'];
        unset($object['email']);

        try {
            $this->start_d();

            // ������ɵ�״̬
            $object['status'] = "GCXMZT04";
            $this->edit_d($object);

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '���', null);

            if ($email['issend'] == 'y') {
                // �ʼ�����
                $this->mailDeal_d('esmProjectFinish', $email['TO_ID'], array('id' => $object['id']));
            }

            //���º�ͬ״̬
            $row = $this->get_d($object['id']);
            if (in_array($row['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($row['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessClose_d($row, $initObj);
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
     * �����Ŀ - �Զ�
     * @param $object
     * @return bool
     */
    function autoFinish_d($object)
    {

        try {
            $this->start_d();

            $object['status'] = 'GCXMZT04';
            $object['actEndDate'] = day_date;
            $this->edit_d($object);

            //���º�ͬ״̬
            $row = $this->get_d($object['id']);
            if (in_array($row['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($row['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();
                    $this->businessClose_d($row, $initObj);
                }
            }

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '���', null);

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }

        // �ʼ�֪ͨ����
        $rangeInfo = $this->getRangeInfo_d($object['id']);

        // �ϲ���Ա����
        $userIdArray = array();
        if ($object['managerId']) $userIdArray[] = $object['managerId'];
        if ($rangeInfo['mainManagerId']) $userIdArray[] = $rangeInfo['mainManagerId'];
        if ($rangeInfo['managerId']) $userIdArray[] = $rangeInfo['managerId'];
        if ($rangeInfo['headId']) $userIdArray[] = $rangeInfo['headId'];
        if ($rangeInfo['assistantId']) $userIdArray[] = $rangeInfo['assistantId'];

        $userIdArray = array_unique(explode(',', implode(',', $userIdArray)));
        $userIds = implode(',', $userIdArray);

        // �ʼ�����
        $this->mailDeal_d('esmProjectFinish', $userIds, array('id' => $object['id']));

        return true;
    }

    /**
     * @param $object
     * @return bool
     */
    function openClose_d($object)
    {
        try {
            $this->start_d();

            // �����ֵ䴦��
            $object = $this->processDatadict($object);

            // ��Ŀ����
            $this->edit_d($object);

            // ����״̬
            $actionType = $object['status'] == "GCXMZT02" ? "��" : "��";

            //��¼������־
            $esmlogDao = new model_engineering_baseinfo_esmlog();
            $esmlogDao->addLog_d($object['id'], '������Ŀ-' . $actionType, $object['remark']);

            //���º�ͬ״̬
            if (in_array($object['contractType'], $this->initContractType)) {
                $newClass = $this->getClass($object['contractType']);
                if ($newClass) {
                    $initObj = new $newClass();

                    // ��ͬ״ִ̬�в�ͬ����
                    if ($object['status'] == "GCXMZT02") {
                        $this->businessConfirm_d($object, $initObj);
                    } else {
                        $this->businessClose_d($object, $initObj);
                    }
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
     * �ж���Ŀ�Ƿ�A��
     * @param null $object
     * @param null $id
     * @return bool
     */
    function isCategoryAProject_d($object = null, $id = null)
    {
        //���������Ŀ��Ϣ,ֱ���ж�
        if (isset($object['category']) && $object['category'] == 'XMLBA') {
            return true;
        }
        //���ֻ��id������Ҫ��һ�β�ѯ
        if ($id) {
            return $this->find(array('id' => $id, 'category' => 'XMLBA'), null, 'id') ? true : false;
        }
        return false;
    }

    /**
     * �ж���Ŀ�Ƿ�ȫ���
     * @param $id
     * @return bool
     */
    function isAllOutsourcingProject_d($id)
    {
        //���ֻ��id������Ҫ��һ�β�ѯ
        if ($id) {
            return $this->find(array('id' => $id, 'outsourcing' => 'WBLXB'), null, 'id') ? true : false;
        }
    }

    /**
     * �ж���Ŀ�Ƿ��ڽ�
     * @param $id
     * @return bool|mixed
     */
    function isDoing_d($id)
    {
        return $this->find(array('id' => $id, 'status' => 'GCXMZT02'), NULL, 'id');
    }

    /**
     * �ж���Ŀʱ����ҵ��δ���
     * @param $id
     * @return bool
     */
    function isNotDone_d($id)
    {
        $statusreportDao = new model_engineering_project_statusreport();
        if ($statusreportDao->hasSubmitedReport_d($id)) {
            return true;
        }

        $esmchangeDao = new model_engineering_change_esmchange();
        if ($esmchangeDao->hasChangeProject_d($id)) {
            return true;
        }
        return false;
    }

    /**
     * �ж���Ŀ�Ƿ��ѹر�
     * @param $id
     * @return mixed
     */
    function isClose_d($id)
    {
        return $this->find(array('id' => $id, 'status' => 'GCXMZT03'));
    }

    /**
     * �жϺ�ͬ�����Ѵ������ - ���ݺ�ͬ���ͺ�id
     * @param $contractId
     * @param $contractType
     * @param $productLine
     * @return bool
     */
    function isAllDealByType_d($contractId, $contractType, $productLine)
    {
        //���û�ȡ�����ȷ���
        $allWorkRate = $this->getAllWorkRateByType_d($contractId, $contractType, $productLine);
        if ($allWorkRate >= 100) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ����ҵ���Ż�ȡ��Ŀ״̬
     * @param $rObjCode
     * @param bool $checkClose
     * @return int 0:ִ����|1�����|2û����Ŀ|3�ѹر�
     */
    function checkIsCloseByRobjcode_d($rObjCode, $checkClose = false)
    {
        $this->searchArr = array(
            'rObjCode' => $rObjCode
        );
        $rs = $this->list_d();
        if (is_array($rs)) {
            // ����ƥ��
            $proLineArr = array();
            // ״̬����
            $statusArr = array();

            // ��ѯ����
            foreach ($rs as $v) {
                // �����Ŀ״̬�ǹرջ�����ɻ�����Ŀ���Ȳ�Ϊ100���򷵻���Ŀִ����
//                if ($v['projectProcess'] < 100 || !in_array($v['status'], array('GCXMZT03', 'GCXMZT04', 'GCXMZT00'))) {
                // PMS 2859 ����ͬ����״̬Ϊ���ʱ,��������Ŀ�Ľ���, ��Ŀ�깤ʱ�����Ȳ�һ��Ҫ�ﵽ100%���������Ŀ״̬�ǹرջ�����ɣ��򷵻���Ŀִ����,
                if (!in_array($v['status'], array('GCXMZT03', 'GCXMZT04', 'GCXMZT00'))) {
                    return 0;
                }
                $proLineArr[$v['productLine']] = isset($proLineArr[$v['productLine']]) ?
                    $proLineArr[$v['productLine']] + $v['workRate'] : $v['workRate'];

                // ״̬����
                if (!in_array($v['status'], $statusArr)) {
                    $statusArr[] = $v['status'];
                }
            }

            foreach ($proLineArr as $v) {
                if ($v < 100) {
                    return 0;
                }
            }

            // ���������Ŀ�رգ���ִ�������ж�
            // �����Ŀ״̬���ҽ�Ϊ�ѹرգ�����Ŀ�ж�Ϊȫ���ر�
            if ($checkClose && (count($statusArr) == 1 && $statusArr[0] == 'GCXMZT03')) {
                return 3;
            }

            // ������Ŀ�����
            return 1;
        } else {
            $chkEsmProductSql = "select p.id from oa_contract_product p left join oa_contract_contract c on c.id = p.contractId where c.objCode = '{$rObjCode}' and c.isTemp = 0 and p.proTypeId in (17,18);";
            $chkEsmProduct = $this->_db->getArray($chkEsmProductSql);
            
            //����2����û����Ŀ
            return ($chkEsmProduct)? 0 : 2;
        }
    }

    /**
     * �ύ��֤
     * @param $id
     * @return array
     */
    function submitCheck_d($id)
    {
        // ��Ŀ��Ϣ
        $obj = $this->get_d($id);
        //�������
        $obj = $this->feeDeal($obj);

        // PK ��Ŀ��֤������Ŀ���ںϷ���
        if ($obj['contractType'] == 'GCXMYD-04') {
            //���ò���
            $newClass = $this->getClass($obj['contractType']);
            $initObj = new $newClass();
            //��ȡ��Ӧҵ����Ϣ
            $businessObj = $this->getRawObjInfo_d($obj, $initObj);
            if (strtotime($obj['planBeginDate']) < strtotime($businessObj['beginDate']) ||
                strtotime($obj['planEndDate']) > strtotime($businessObj['closeDate'])
            ) {

                return array('pass' => 0, 'msg' => 'PK��Ŀʵʩ���ڳ�����������');
            } elseif ($obj['budgetAll'] > $businessObj['affirmMoney']) {

                return array('pass' => 0, 'msg' => 'PK��ĿԤ�㳬������ȷ�϶��');
            }
        } else if ($obj['contractType'] == 'GCXMYD-01') {
            // ��ͬ��Ŀ��֤����ֵ
            if ($obj['budgetAll'] > $obj['estimates']) {
                return array('pass' => 0, 'msg' => '��ĿԤ�㲻�ܴ�����Ŀ���㣬���޸ĺ����ύ����!');
            }

            // ��֤�Ƿ���PK��Ŀ��ִ��
            if ($this->isPKProcessing_d($id)) {
                return array('pass' => 0, 'msg' => '����Ŀ����δ�رջ�δ��ɵĹ���PK��Ŀ���޷��ύ����!');
            }
        }

        // ��Ŀ�ƻ���֤
        $esmActivityDao = new model_engineering_activity_esmactivity();

        // ��Ŀ��������֤
        $workRate = $esmActivityDao->workRateCount($id);
        if ($workRate != 100) {
            return array('pass' => 0, 'msg' => '����!\n��Ŀ����-����ռ���ܺ�' . $workRate . '%�����޸�Ϊ100%�����ύ����');
        }

        // �¼�������֤
        $activityCheckInfo = $esmActivityDao->workRateCountNew($id, -1, null);
        if ($activityCheckInfo['count'] != 100) {
            return array('pass' => 0, 'msg' => '����!\n��Ŀ����-�¼������� ' . $activityCheckInfo['parentName'] .
                ' ����ռ���ܺ�' . $activityCheckInfo['count'] . '%,����100%');
        }

        // ��Ŀ�ر�����
        $esmcloseDao = new model_engineering_close_esmclose();
        if ($esmcloseDao->hasProcessingApply_d($id)) {
            return array('pass' => 0, 'msg' => '����!��Ŀ���������еĹر�����');
        }

        return $errorMsg = array('pass' => 1, 'msg' => '', 'rangeId' => $this->getRangeId_d($id));
    }

    /****************************** PK��Ŀ��ط��� ******************************/
    /**
     * ����ʹ����Ŀid �ر� ������Ŀ
     * @param $contractId
     * @param string $contractType
     * @return bool|mixed
     */
    function closeProjectByContractId_d($contractId, $contractType = 'GCXMYD-04')
    {
        try {
            $this->start_d();

            //������ĿΪ�ر�
            $updateArr = array(
                'status' => 'GCXMZT03', 'closeDate' => day_date,
                'closeDesc' => '������Ŀת��ͬ��ϵͳ�Զ��ر�������Ŀ���µĹ�����Ŀ'
            );
            $updateArr = $this->addUpdateInfo($updateArr);
            $this->update(
                array('contractId' => $contractId, 'contractType' => $contractType),
                $updateArr
            );

            //������Ŀ��Ϣ
            $obj = $this->find(array('contractId' => $contractId, 'contractType' => $contractType));

            $this->commit_d();
            return $obj;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * ��ȡPK��Ŀ��Ϣ
     * @param null $projectId
     * @param null $esmprojectObj
     * @return mixed
     */
    function getPKInfo_d($projectId = null, $esmprojectObj = null,$noRate = false)
    {
        $trialInfo = array();
        //��ȡ����Ŀ
        $esmprojectObj = empty($esmprojectObj) ? $this->find(array('id' => $projectId), null, 'id,contractId,contractType,workRate,productLine') : $esmprojectObj;
        if ($esmprojectObj['contractType'] == 'GCXMYD-01') {
            //���������Ŀռ�Ȳ���100(����Ϊ��ͬ����,������������Ŀ)����ô��ѯ��غ�ͬ�µ�������Ŀ��������ռ��
//            if ($esmprojectObj['workRate'] != 100) {
//                $esmprojectArr = $this->findAll(
//                    array('contractId' => $esmprojectObj['contractId'], 'contractType' => $esmprojectObj['contractType'], 'productLine' => $esmprojectObj['productLine']),
//                    null,
//                    'workRate'
//                );
//                //��ȡ�Ĳ�Ʒ�߷����ȫ��ռ��
//                $allThisProduceLintRate = 0;
//                foreach ($esmprojectArr as $v) {
//                    $allThisProduceLintRate = bcadd($allThisProduceLintRate, $v['workRate'], 4);
//                }
//            } else {
//                $allThisProduceLintRate = 100;
//            }

            //��ȡ����������Ŀ
            $esmmappingDao = self::getObjCache('model_engineering_project_esmmapping'); // ʵ������Ŀӳ���
            $ids = $esmmappingDao->getProjectString_d($esmprojectObj['id']);
            if ($ids != null) {
                $this->getParam(array('idArr' => $ids, 'contractType' => 'GCXMYD-04', 'productLine' => $esmprojectObj['productLine']));
                $trialInfo = $this->list_d('select_defaultAndFee');
                if (!empty($trialInfo)) {
                    $pkEstimatesRate = 1;
                    if(isset($esmprojectObj['pkEstimatesRate'])){
                        $pkEstimatesRate = bcdiv($esmprojectObj['pkEstimatesRate'],100,4);
                    }else{
                        $esmObj = $this->find(array('id' => $projectId), null, 'id,contractId,contractType,pkEstimatesRate');
                        $pkEstimatesRate = (isset($esmObj['pkEstimatesRate']))? bcdiv($esmObj['pkEstimatesRate'],100,4) : $pkEstimatesRate;
                    }
                    $pkEstimatesRate = ($noRate)? 1 : $pkEstimatesRate;
                    foreach ($trialInfo as $key => $val) {
                        //����ͬ��Ʒ��ռ��
//                        $trialProjectWorkRate = bcdiv($esmprojectObj['workRate'], $allThisProduceLintRate, 4);
//                        $trialInfo[$key]['budgetAll'] = $trialInfo[$key]['feeAll'] = $val['feeAll'] == 0 ? 0 : round(bcmul($val['feeAll'], $trialProjectWorkRate, 4), 2);

                        // ����PK�ɱ�ռ��, ��ȡ��Ӧ�ĳɱ�����
                        $trialInfo[$key]['budgetAll'] = ($noRate)? $trialInfo[$key]['feeAll'] : round(bcmul($trialInfo[$key]['feeAll'],$pkEstimatesRate,3),2);
                        $trialInfo[$key]['feeAll'] = ($noRate)? $trialInfo[$key]['feeAll'] : round(bcmul($trialInfo[$key]['feeAll'],$pkEstimatesRate,3),2);
                    }
                }
            }
        }
        return $trialInfo;
    }

    /**
     * ƥ����ʷ��������
     * @param $object
     * @param $feeBeginDate
     * @param $feeEndDate
     * @return mixed
     */
    function historyFeeDeal_d($object, $feeBeginDate, $feeEndDate)
    {
        // ������ĿID
        $projectIds = array();

        foreach ($object as $v) {
            $projectIds[] = $v['id'];
        }

        // ��ȡ�汾��ϸ
        $esmfielddetailDao = new model_engineering_records_esmfielddetail();
        $esmfielddetailArr = $esmfielddetailDao->getHistory_d($feeBeginDate, $feeEndDate, implode(',', $projectIds));

        // �վ�������
        $emptyArr = array(
            'feeAll' => 0, 'feePayables' => 0, 'feeFlights' => 0, 'feeCar' => 0,
            'feeCostMaintain' => 0, 'feeEqu' => 0, 'feeEquImport' => 0, 'feeExpense' => 0,
            'feeOther' => 0, 'feeOutsourcing' => 0, 'feePerson' => 0, 'feePK' => 0,
            'feeSubsidy' => 0, 'feeSubsidyImport' => 0
        );

        // ���㴦��
        foreach ($object as $k => $v) {
            if (isset($esmfielddetailArr[$v['id']])) {
                $object[$k] = array_merge($object[$k], $esmfielddetailArr[$v['id']]);
            } else {
                $object[$k] = array_merge($object[$k], $emptyArr);
            }
        }

        return $object;
    }

    /**
     * ƥ����ʷ����
     * @param $object
     * @param $beginDate
     * @param $endDate
     * @return mixed
     */
    function historyIncomeDeal_d($object, $beginDate, $endDate)
    {
        // ������ĿID
        $projectIds = array();

        foreach ($object as $v) {
            $projectIds[] = $v['id'];
        }

        // ��ȡ�汾��ϸ
        $esmincomeDao = new model_engineering_records_esmincome();
        $esmincomeArr = $esmincomeDao->getHistory_d($beginDate, $endDate, implode(',', $projectIds));

        // ���㴦��
        foreach ($object as $k => $v) {
            if (isset($esmincomeArr[$v['id']])) {
                $object[$k]['curIncome'] = $esmincomeArr[$v['id']]['curIncome'];
            } else {
                $object[$k]['curIncome'] = 0;
            }
            //��ǰë����
            $object[$k]['exgross'] = round(bcmul(bcsub(1, bcdiv($v['feeAll'], $object[$k]['curIncome'], 6), 6), 100, 4), 2);
        }

        return $object;
    }

    /**
     * ����PK���ô���
     * @param $object
     * @return mixed
     */
    function PKFeeDeal_d($object,$needPkRate = false)
    {
        foreach ($object as $k => $v) {

            // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
            if ($v['contractType'] == 'GCXMYD-01' && (!isset($v['pType']) || $v['pType'] == 'esm')) {
                $pkEstimatesRate = 1;
                $esmObj = $this->find(array('id' => $v['id']), null, 'id,pkEstimatesRate');
                $pkEstimatesRate = (isset($esmObj['pkEstimatesRate']))? bcdiv($esmObj['pkEstimatesRate'],100,4) : $pkEstimatesRate;
                //��ȡ��ǰ�е�������Ŀ
                $fee = 0;
                $thisRowPKProject = $this->getPKInfo_d(null, $v);
                if ($thisRowPKProject) {
                    foreach ($thisRowPKProject as $val) {
                        $pkFeeAll = ($needPkRate)? round(bcmul($val['feeAll'],$pkEstimatesRate,4),2) : $val['feeAll'];
                        $fee = bcadd($fee, $pkFeeAll, 2);
                    }
                    $object[$k]['budgetAll'] = bcadd(bcsub($v['budgetAll'], $v['budgetPK'], 2), $fee, 2); // ��Ԥ��
                    $object[$k]['feeAll'] = bcadd(bcsub($v['feeAll'], $v['feePK'], 2), $fee, 2); //�� �� ��(ʵʱ)
                }
                $object[$k]['budgetPK'] = $fee;    //PK��ĿԤ��
                $object[$k]['feePK'] = $fee;    //PK��Ŀ����
            }
        }
        return $object;
    }

    /**
     * ��֤�Ƿ���δ��ɻ�δ�رյĹ���PK��Ŀ
     * @param $projectId
     * @return bool
     */
    function isPKProcessing_d($projectId)
    {
        $trialInfo = $this->getPKInfo_d($projectId);
        if ($trialInfo) {
            foreach ($trialInfo as $v) {
                //GCXMZT03״̬Ϊ�رգ�GCXMZT04״̬Ϊ���, GCXMZT00Ϊ����δ�ر�
                if (!in_array($v['status'], array('GCXMZT03', 'GCXMZT04', 'GCXMZT00'))) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * ��֤��Ŀ�Ƿ�ΪPK��Ŀ
     * @param $projectId
     * @return bool
     */
    function isPK_d($projectId)
    {
        $rs = $this->find(array('id' => $projectId), null, 'contractType');
        if ($rs['contractType'] == 'GCXMYD-04') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * ������Ŀ�Ƿ������¼��־
     * @param $id
     * @return string
     */
    function checkCanWithoutLog_d($id)
    {
        // ��Ŀƥ��
        $object = $this->get_d($id);
        if ($object['ExaStatus'] != AUDITED) {
            return '��Ŀû��������ɣ�����������Ϊ��¼��־';
        }

        // ״̬Ҫ���ڽ�
        if ($object['status'] != 'GCXMZT02') {
            return '��Ŀ״̬�����ڽ�״̬������������Ϊ��¼��־';
        }

        // ��Ŀ�ƻ���֤
        $esmactivityDao = new model_engineering_activity_esmactivity();
        $projectActivities = $esmactivityDao->getProjectActivity_d($id);

        // ���񳤶�
        $activitiesLength = count($projectActivities);

        // Ҫ�����񳤶�����Ψһ
        if ($activitiesLength == 0) {
            return '��Ҫû����Ŀ�ƻ�������������Ϊ��¼��־';
        }
        if ($activitiesLength > 1) {
            return '��¼����ֻ֧��һ����Ŀ�ƻ�����Ŀ';
        }

        // ��Ŀ��Ա
        $esmmemberDao = new model_engineering_member_esmmember();
        $projectMembers = $esmmemberDao->checkMemberAllLeave_d($id);

        // ��Ա����
        $membersLength = count($projectMembers);

        // ��Ա��������Ϊ��
        if ($membersLength == 0) {
            return 'û�л�����Ŀ�еĳ�Ա������������Ϊ��¼��־';
        }

        return 'ok';
    }

    /**
     * �Զ���־¼�� - ��¼��־
     * @param $project
     * @return bool
     */
    function autoFillLog_d($project)
    {

        // ����в鵽��Ŀ����ʼ����
        if (!empty($project)) {
            // ����״̬
            $work = 'GXRYZT-01';
            $rest = 'GXRYZT-04';

            // ʱ��
            $now = date('Y-m-d H:i:s');

            // ��ȡ�Ѿ���д�˵���־ - ��Ա����Ŀ
            $sql = "SELECT projectId, executionDate FROM oa_esm_worklog c WHERE c.projectId IN(" .
                $project['projectId'] . ") AND c.createId = '" . $project['memberId'] . "'";
            $logDone = $this->_db->getArray($sql);

            // ��¼��־����
            $logDoneMap = array();

            // ����������¼����ת��Ϊӳ���
            if ($logDone) {
                foreach ($logDone as $v) {
                    if (!in_array($v['executionDate'], $logDoneMap)) {
                        $logDoneMap[] = $v['executionDate'];
                    }
                }
            }

            // ��ȡԤ�ƿ�����д����־
            $sql = "SELECT
					c.id AS projectId, c.projectCode, c.projectName, c.country, c.countryId, c.province, c.provinceId,
					c.provinceId, c.city, c.cityId, c.planEndDate AS projectEndDate,
					a.id AS activityId, a.activityName, a.workloadUnit, a.workloadUnitName,
					a.planEndDate AS activityEndDate, a.planBeginDate, a.planEndDate,
					m.memberId AS createId, m.memberName AS createName,'" . $now . "' AS createTime,
					-1 AS workProcess,'WTJ' AS status, 1 AS assessResult, '��' AS assessResultName,
					'��¼��־-ϵͳ�Զ�����' AS description, 1 AS workCoefficient,
					1 AS confirmStatus, '" . $now . "' AS confirmTime, 'SYSTEM' AS confirmId, 'ϵͳ' AS confirmName
				FROM
					oa_esm_project c
					LEFT JOIN
					oa_esm_project_activity a ON c.id = a.projectId
					LEFT JOIN
					oa_esm_project_member m ON c.id = m.projectId
				WHERE c.id IN(" . $project['projectId'] . ") AND m.memberId = '" . $project['memberId'] . "'";
            $logBaseData = $this->_db->getArray($sql);

            // ��ȡ�û�������Ϣ
            $sql = "SELECT d.DEPT_NAME,d.DEPT_ID FROM department d LEFT JOIN user u ON d.DEPT_ID = u.DEPT_ID
                WHERE u.USER_ID = '" . $project['memberId'] . "'";
            $deptInfo = $this->_db->get_one($sql);

            // ��־����
            $logCache = array();

            foreach ($logBaseData as $k => $v) {
                // ��ʼ�����Լ���������
                $begin = $v['planBeginDate'];
                $end = strtotime($v['planEndDate']) > strtotime(day_date) ?
                    day_date : $v['planEndDate'];

                // ��������
                $weekDao = new model_engineering_baseinfo_week();
                $projectPeriod = $weekDao->getAllDays($begin, $end);

                foreach ($projectPeriod as $vi) {
                    $logCache[$vi][] = $k;
                }
            }

            // Ԥ��Ҫ���������
            $insertData = array();

            // ��־����
            foreach ($logCache as $k => $v) {

                // ��������Ѿ��б����־���������������
                if (in_array($k, $logDoneMap)) {
                    continue;
                }

                // ����״̬
                $workStatus = in_array(date('w', strtotime($k)), array(0, 6)) ? $rest : $work;
                $workloadDay = round(1 / count($v), 2);
                $inWorkRate = round(100 / count($v), 2);

                // �������ڹ�����������
                foreach ($v as $vi) {
                    $log = $logBaseData[$vi];
                    $log['executionDate'] = $k;
                    $log['executionTimes'] = strtotime($k);
                    $log['workStatus'] = $workStatus;
                    $log['workloadDay'] = $workloadDay;
                    $log['inWorkRate'] = $inWorkRate;
                    $log['deptId'] = $deptInfo['DEPT_ID'];
                    $log['deptName'] = $deptInfo['DEPT_NAME'];
                    $insertData[] = $log;
                }
            }

            // �������Ҫ�������־������д���
            if (!empty($insertData)) {
                // ʵ����һЩdao
                $esmactivityDao = new model_engineering_activity_esmactivity();
                $esmmemberDao = new model_engineering_member_esmmember();
                $esmworklogDao = new model_engineering_worklog_esmworklog();

                try {
                    $this->start_d();

                    // ��־����
                    $esmworklogDao->addBatch_d($insertData);

                    foreach ($logBaseData as $v) {
                        // �����ϼ�����
                        $esmworklogDao->updateLogSource_d($this, $esmactivityDao, $esmmemberDao,
                            $v['projectId'], $v['activityId'], $v['memberId'], $v['planBeginDate']);
                    }

                    $this->commit_d();
                } catch (Exception $e) {
                    $this->rollBack();
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * ����Ŀ״̬����Ϊ����δ�ر�
     * @param $project
     * @return bool
     */
    function autoCloseOverdue_d($project)
    {
        if (!empty($project) && $project['id']) {
            $object = array(
                'status' => 'GCXMZT00'
            );
            $object = $this->processDatadict($object);
            $object = $this->addUpdateInfo($object);

            // ִ�и���
            $this->update(array('id' => $project['id']), $object);
        }
        return true;
    }

    /**
     * ��ȡĬ�ϲ���
     * @param bool $onlyShow
     * @return array
     */
    function getDefaultDept_d($onlyShow = false)
    {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'],
            $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['����Ȩ��'];
        if (!$deptLimit) {
            return array('deptId' => '', 'deptName' => '');
        }
        if ($onlyShow && strpos($deptLimit, ';;') !== false) {
            return array('deptId' => 'all', 'deptName' => 'ȫ��');
        }
        $deptLimitArr = explode(',', $deptLimit);
        $deptIdArr = array();
        foreach ($deptLimitArr as $v) {
            if ($v != ';;' && $v) {
                $deptIdArr[] = $v;
            }
        }
        $deptNameArr = array();
        if (!empty($deptIdArr)) {
            $deptDao = new model_deptuser_dept_dept();
            $deptArr = $deptDao->getDeptByIds_d(implode(',', $deptIdArr));
            foreach ($deptArr as $key => $val) {
                if ($val['deptName']) {
                    $deptNameArr[$key] = $val['deptName'];
                }
            }
        }
        return array(
            'deptId' => implode(',', $deptIdArr),
            'deptName' => implode(',', $deptNameArr)
        );
    }

    /**
     * ����Դ�����¸���
     * @param $contractId
     * @param $contractType
     * @param $estimates
     * @throws Exception
     */
    function updateTriEstimates_d($contractId, $contractType, $estimates)
    {
        $object = array(
            'estimates' => $estimates
        );
        $object = $this->addUpdateInfo($object);
        try {
            $this->update(array(
                'contractId' => $contractId,
                'contractType' => $contractType
            ), $object);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ������Ŀ�ĺ�ͬ����
     * @param $param
     * @param $month
     * @return bool
     */
    function updateProjectFields_d($param, $month = '')
    {
        set_time_limit(0);
        // ��ѯ�Ѿ���˹��ĺ�ͬ����Ŀ������
        $condition = null;
        if (!is_array($param) && $param) $condition = array('projectCode' => $param);
        $rows = $this->findAll($condition);

        if ($rows) {
            $projectIds = array();
            foreach ($rows as $v) {
                $module = $v['module'];
                $moduleName = $v['moduleName'];
                $areaCode = $v['areaCode'];
                $areaName = $v['areaName'];

                $v = $this->feeDeal($v);
                $v = $this->contractDeal($v);
                if($v['contractId'] > 0){
                    $module = $v['module'];
                    $moduleName = $v['moduleName'];
                    $areaCode = $v['areaCode'];
                    $areaName = $v['areaName'];
                }

                // ������Ҫ���µ���ĿID
                $projectIds[] = $v['id'];

                // ������Ŀ��Ϣ
                //pms 3055 'feeField' => $v['feeFieldClean'],�ĳ�'feeField' => $v['feeField']
                //��Ϊȱ�����⳵Ԥ�� feefield�ֶ��Ǳ����ܺ� �ǰ����⳵Ԥ��� clean�ǲ�����
                $this->edit_d(array(
                    'id' => $v['id'], 'moduleName' => $moduleName, 'module' => $module,
                    'areaCode' => $areaCode, 'areaName' => $areaName, 'contractRate' => $v['contractRate'],
                    'deductMoney' => $v['deductMoney'], 'curIncome' => $v['curIncome'], 'exgross' => $v['exgross'],
                    'reserveEarnings' => $v['reserveEarnings'], 'contractMoney' => $v['contractMoney'],
                    'estimates' => $v['estimates'], 'budgetAll' => $v['budgetAllClean'], 'feeAll' => $v['feeAll'],
                    'budgetPK' => $v['budgetPK'], 'feePK' => $v['feePK'], 'feeField' => $v['feeFieldClean'],
                    'feeSubsidy' => $v['feeSubsidy'], 'feeEquDepr' => $v['feeEquDepr'], 'feePayables' => $v['feePayables'],
                    'projectMoney' => $v['projectMoney'], 'projectMoneyWithTax' => $v['projectMoneyWithTax'],
                    'invoiceMoney' => $v['invoiceMoney'], 'incomeMoney' => $v['incomeMoney'],
                    'uninvoiceMoney' => $v['uninvoiceMoney'], 'contractStatus' => $v['contractStatus'],
                    'contractDeduct' => $v['contractDeduct']
                ), true);
            }

            // ������ĿԤ����
            $this->updateBudgetAll_d(implode(',', $projectIds));
        }

        // ��־д��
        $logDao = new model_engineering_baseinfo_esmlog();
        $month = $month ? $month : date('n');
        $logDao->addLog_d(-1, '������Ŀ����', count($rows) . '|' . $month);

        return true;
    }

    /**
     * ���ݺ�ͬid��ȡ��Ŀ״̬
     * @param $contractId
     * @param string $contractType
     * @return bool
     */
    function getStatusNameByContractId_d($contractId, $contractType = 'GCXMYD-01')
    {
        $object = $this->findAll(array(
            'contractId' => $contractId,
            'contractType' => $contractType
        ), null, 'statusName');

        if (!empty($object)) {
            $statusNames = array();
            foreach ($object as $v) {
                $statusNames[] = $v['statusName'];
            }
            return implode(';', $statusNames);
        } else {
            return false;
        }
    }

    /**
     * ���ܱ��ȡ����ֵ
     */
    function getCurIncomeByPro($pro)
    {
        $conprojectDao = new model_contract_conproject_conproject();
        return $conprojectDao->getCurIncomeByPro($pro);
    }

    /**
     * ���ܱ��ȡ�ɱ�ֵ
     */
    function getFeeAllByPro($pro)
    {
        $conprojectDao = new model_contract_conproject_conproject();
        return $conprojectDao->getFeeAllByPro($pro);
    }

    /**
     *
     * ��ȡ��ǰ��½��������Ŀ�Ĵ������־
     */
    function getWaitAuditLog_d()
    {
        $sql = "SELECT COUNT(id) AS waitAuditLog FROM oa_esm_worklog
			WHERE confirmStatus <> 1 AND projectId IN(
				SELECT id FROM oa_esm_project
				WHERE FIND_IN_SET('" . $_SESSION['USER_ID'] . "', managerId
			) AND ExaStatus = '���')";
        $data = $this->_db->get_one($sql);

        if ($data) {
            return $data['waitAuditLog'];
        } else {
            return 0;
        }
    }

    /**
     * ��ȡ��ǰ��½��δ�ύ�ܱ�
     * @return int
     */
    function getWaitSubReport_d()
    {
        $sql = "SELECT id FROM oa_esm_project
				WHERE FIND_IN_SET('" . $_SESSION['USER_ID'] . "', managerId) AND status = 'GCXMZT02'";
        $data = $this->_db->getArray($sql);

        // ��Ŀ����
        $idArr = array();

        foreach ($data as $v) {
            $idArr[] = $v['id'];
        }

        if (empty($idArr)) {
            return 0;
        } else {
            $statusreportDao = new model_engineering_project_statusreport();
            $warnData = $statusreportDao->warnView_d(
                array('beginDateThan' => day_date, 'endDateThan' => day_date,
                    'projectIds' => implode(',', $idArr), 'all' => 1
                )
            );

            $num = 0;
            foreach ($warnData as $v) {
                if ($v['msg'] == 'δ�') {
                    $num++;
                }
            }
            return $num;
        }
    }

    /**
     * ��ȡ���汾
     */
    function getVersionInfo_d()
    {
        $versionInfo = array();

        // ��ѯ��ǰ��߰汾
        $this->tbl_name = "oa_esm_records_project";
        $maxVersion = $this->find(null, 'version DESC', 'version');

        if ($maxVersion) {
            $today = date('Ymd');
            $versionDay = substr($maxVersion['version'], 0, 8);
            if ($today != $versionDay) {
                $versionInfo['version'] = $today . '01';
            } else {
                $versionInfo['version'] = intval($maxVersion['version']) + 1;
            }
            $versionInfo['maxVersion'] = $maxVersion['version'];
        } else {
            $versionInfo['version'] = date('Ymd') . '01';
            $versionInfo['maxVersion'] = 0;
        }
        // �ꡢ�·�
        $versionInfo['storeYear'] = date('Y');
        $versionInfo['storeMonth'] = date('m');

        $this->tbl_name = "oa_esm_project";
        return $versionInfo;
    }

    /**
     * ���ݺ�ͬid��ȡ��Ŀ����[��ͬ���ݸ���ʱ��]
     * @param $cid
     * @param array $conArr
     * @return mixed|null
     */
    function getProByCidForUpload($cid,$conArr = array())
    {
        $backArr['cid'] = $cid;
        $backArr['hasProject'] = false;
        $backArr['contractCode'] = $conArr['contractCode'];
        $backArr['contractMoney'] = $conArr['contractMoney'];
        $backArr['allProjMoneyWithSchl'] = 0;//��Ŀ��ͬ��
        $backArr['budgetAll'] = 0;// ��Ԥ��
        $backArr['curIncome'] = 0;//��ĿӪ��
        $backArr['feeAll'] = 0;//�� �� ��(ʵʱ)
        $this->searchArr = "";
        $this->searchArr['contractId'] = $cid;
        //������Ŀ�ų�
        $rows = $this->pageBySqlId('select_defaultAndFeeForUpload');
        if($rows){
            $backArr['hasProject'] = true;
            // ��Ŀ��Ϣ����
            foreach ($rows as $k => $v) {
                $budgetAll = $feeAll = 0;
                // Ԥ��Ԥ����
                $feeAll = $v['feeAll'];
                if ($v['contractType'] == 'GCXMYD-01' && (!isset($v['pType']) || $v['pType'] == 'esm')) {
                    // ��ȡ��ǰ�е�������Ŀ
                    $fee = 0;
                    $thisRowPKProject = $this->getPKInfo_d(null, $v);
                    if ($thisRowPKProject) {
                        foreach ($thisRowPKProject as $val) {
                            $fee = bcadd($fee, $val['feeAll'], 2);
                        }
                        $budgetAll = bcadd(bcsub($v['budgetAll'], $v['budgetPK'], 2), $fee, 2); // ��Ԥ��
                    }else{
                        $budgetAll = $v['budgetAll'];
                    }
                }else{
                    $budgetAll = $v['budgetAll'];
                }
                $projectMoneyWithTax = sprintf("%.3f", $v['projectMoney']);
                $projectMoneyWithTax = bcmul($projectMoneyWithTax,1,10);
                $backArr['allProjMoneyWithSchl'] += bcmul($projectMoneyWithTax,bcdiv($v['projectProcess'],100,11),10);//��Ŀ��ͬ��
                $backArr['budgetAll'] += sprintf("%.3f", $budgetAll);// ��Ԥ��
                $backArr['curIncome'] += sprintf("%.3f", $v['curIncome']);//��ĿӪ��
                $backArr['feeAll'] += sprintf("%.3f", $feeAll);//�� �� ��(ʵʱ)
            }
            return $backArr;
        }else{
            return $backArr;
        }
    }

    /**
     *  ���ݺ�ͬid��ȡ��Ŀ����
     */
    function getProByCid($cid)
    {

        $rows = null;
        # Ĭ��ָ���ı����
        $this->setComLocal(array(
            "c" => $this->tbl_name
        ));
        $this->searchArr = "";
        $this->searchArr['contractId'] = $cid;
        $rows = $this->pageBySqlId('select_defaultAndFee');
        //��չ���ݴ���
        if ($rows) {
            $productDao = new model_contract_contract_product();
            $conProjectDao = new model_contract_conproject_conproject();
            $conDao = new model_contract_contract_contract();
            //����Ԥ�㣬���þ���
            $rows = $this->PKFeeDeal_d($rows);
            // ������Ϣ����
            foreach ($rows as $k => $v) {
                // ������Ŀ���ͣ������ֵû�д��룬��Ĭ�Ϲ�����Ŀ
                $pType = isset($v['pType']) ? $v['pType'] : 'esm';
                // ֻ�к�ͬ��Ŀ�ż�����Щ����
                if ($pType == 'esm') {
                    $rows[$k] = $this->contractDeal($v);
                } else if ($v['pType'] == "pro") {
                    //ִ������
                    $rs = $productDao->find(array('contractId' => $v['contractId'], 'newProLineCode' => $v['newProLine'],
                        'proTypeId' => '11', 'isDel' => '0'), null, 'exeDeptId,exeDeptName');
                    $rows[$k]['productLineName'] = empty($rs['exeDeptName']) ? '' : $rs['exeDeptName'];
                    //�ܳɱ�
                    $conArr = $conDao->get_d($v['contractId']);
                    $revenue = $conProjectDao->getSchedule($v['contractId'], $conArr, $v, 1); //��ĿӪ��;
                    $earningsType = $v['incomeTypeName']; //����ȷ�Ϸ�ʽ
                    $estimates = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //��Ŀ����
                    $DeliverySchedule = $conProjectDao->getFHJD($v);//��������
                    $schedule = $conProjectDao->getSchedule($v['contractId'], $conArr, $v); //��Ŀ����
                    $shipCostT = $conProjectDao->getFinalCost($v['projectCode'], $revenue, $earningsType, $conArr, $DeliverySchedule, $estimates, 2);//�����ɱ�;

                    //��Ŀʵʱ״��
                    if ($conArr['contractMoney'] === $conArr['uninvoiceMoney']) {
                        $invoiceExe = 100;
                    } else {
                        $deductAndBad = bcadd($conArr['deductMoney'], $conArr['badMoney'], 2);
                        $invoiceExe = round(bcdiv($conArr['invoiceMoney'], bcsub($conArr['contractMoney'], bcadd($deductAndBad, $conArr['uninvoiceMoney'], 9), 9), 9), 9) * 100;//��Ʊ����-����
                    }

                    // ���޺�ͬ����
                    $date1 = strtotime($conArr['beginDate']);
                    $date2 = strtotime($conArr['endDate']);
                    $date3 = strtotime(date("Y-m-d"));
                    $allDays = ($date2 - $date1) / 86400 + 1;
                    $finishDays = ($date3 - $date1) / 86400 + 1;
                    $rentPerc = ($finishDays > $allDays)? 100 : round(bcmul(bcdiv($finishDays,$allDays,5),100,5),2);

                    $otherCost = $conProjectDao->getPotherCost($v['projectCode']);
                    $proportion = $conProjectDao->getAccBycid($v['contractId'], $v['newProLine'], 11);
                    $workRate = round($proportion, 2);
                    $feeCostbx = $conProjectDao->getFeeCostBx($conArr, $workRate);//����֧���ɱ�
                    $shipCost = $conProjectDao->getShipCost($schedule, $invoiceExe, $DeliverySchedule, $shipCostT, $estimates, $earningsType, null, $conArr); //���ᷢ���ɱ�;
                    $finalCost = $otherCost + $feeCostbx + $shipCost;//��Ŀ����

                    $rows[$k]['feeAll'] = $finalCost;//�ܳɱ�
                    $rows[$k]['curIncome'] = $revenue;
                    $rows[$k]['estimates'] = $estimates;
                    $rows[$k]['projectProcess'] = $projectProcess = $conProjectDao->getSchedule($v['contractId'], $conArr, $v); //��Ŀ����;
                    $rows[$k]['shipCostT'] = $conProjectDao->getFinalCost($v['projectCode'], $revenue, $earningsType, $conArr, $DeliverySchedule, $estimates, 2);//�����ɱ�;
                    $rows[$k]['shipCost'] = $shipCost;
                    $rows[$k]['feeProcess'] = round($finalCost / $estimates, 2) * 100; //���ý���;
                    $rows[$k]['equCost'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 0); //�������
                    $rows[$k]['DeliverySchedule'] = $DeliverySchedule; //��������
                    $rows[$k]['projectMoneyWithTax'] = $conProjectDao->getAccMoneyBycid($v['contractId'], $v['newProLine'], 11); //��Ŀ��ͬ��
//                        $rows[$k]['grossProfit'] = $conProjectDao->getSchedule($v['contractId'], $conArr, $v, 2) - $otherCost - $feeCostbx - $shipCost; //��Ŀë��
                    $rows[$k]['curIncome'] = sprintf("%.2f", $rows[$k]['curIncome']);
                    $rows[$k]['grossProfit'] = $rows[$k]['curIncome'] - $otherCost - $feeCostbx - $shipCost; //��Ŀë��
                    $rows[$k]['feeAllProcess'] = round($finalCost / $estimates, 2) * 100;
                    $rows[$k]['projectRate'] = $workRate;
                    $rows[$k]['projectMoney'] = $conProjectDao->getCost($v['contractId'], $v['newProLine'], $conArr, 3); //˰����Ŀ���
                    $rows[$k]['statusName'] = $conProjectDao->getProStatusEx($DeliverySchedule, $invoiceExe, $earningsType,$rentPerc); //״̬
                }
            }
        }
        return $rows;
    }

    /**
     * ͨ��Ԥ���Զ����µ�ǰ��Ŀ�Ľ���ֵ ��ֻ����Ŀǰ����Ʊ�򰴵���ȷ������ķ�����Ŀ��
     */
    function autoUpdateProjectsProcess(){
        $weekNo = model_engineering_util_esmtoolutil::getEsmWeekNo();
        //��ȡ������Ϣ
        $esmactivityDao = new model_engineering_activity_esmactivity();

        //��ȡʵ���ܴ�
        $weekDao = new model_engineering_baseinfo_week();
        $weekInfo = $weekDao->findWeekDate($weekNo);
        $weekDateInfo = $weekDao->getWeekRange($weekInfo['week'], $weekInfo['year']);

        $sql = "select * from oa_esm_project where incomeType in ('SRQRFS-02','SRQRFS-03');";
        $esmProjectObj = $this->_db->getArray($sql);

        if($esmProjectObj){
            foreach ($esmProjectObj as $object){
                $isAProject = $this->isCategoryAProject_d($object); // �Ƿ���A����Ŀ

                //��ȡ��ʱ��Ŀ����
                $projectProcess = in_array($object['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) ?
                    $esmactivityDao->getActFinanceProcess_d($object, null, $weekDateInfo['endDate'])
                    : $esmactivityDao->getActCountProcess_d($object['id'], null, $weekDateInfo['endDate'], $isAProject);

                // ��Ŀ��ǰ����
                $projectProcess = $projectProcess > 100 ? 100 : round($projectProcess, 4);

                // ������Ŀ�ܽ�չ
                $this->updateById(array("id" => $object['id'],"projectProcess" => $projectProcess));
//                $projectAllProcess = array(
//                    'id' => $object['id'],
//                    'processAct' => $projectProcess > 100 ? 100 : round($projectProcess, 4), // ʵ�ʽ���
//                    'realProcess' => $object['projectProcess']
//                ); if($projectAllProcess['processAct'] != $projectAllProcess['realProcess']){echo"<pre>";print_r($projectAllProcess);}
            }
        }
    }
}