<?php

/**
 * @author Show
 * @Date 2011��12��22�� ������ 9:49:51
 * @version 1.0
 * @description:��Ŀ����Ԥ��(oa_esm_project_budget)���Ʋ�
 */
class controller_engineering_budget_esmbudget extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmbudget";
        $this->objPath = "engineering_budget";
        parent::__construct();
    }

    /************************* �б��� ****************************/
    /**
     * ��ת����Ŀ����Ԥ��
     */
    function c_page()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->sort = "c.budgetType,c.budgetName,c.isImport";

        //�ж��Ƿ���ڱ���ļ�¼
        $changeId = $service->isChanging_d($_POST['projectId'], false);
        if ($changeId) {
            $service->searchArr['changeId'] = $changeId;
            $rows = $service->page_d('select_change');
        } else {
            $rows = $service->page_d();
        }

        // Ȩ���Լ��ϼƴ���
        if (!empty($rows)) {
            //����Ȩ��
            $rows = $this->gridDateFilter($rows);
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //������Ŀ�ϼ�
            $objArr = $changeId ? $service->listBySqlId('count_change') : $objArr = $service->listBySqlId('count_all');
            $rsArr = $objArr[0];
            $rsArr['parentName'] = '�� Ŀ �� ��';
            $rsArr['id'] = 'noId';

            // ��ѯ��ĿԤ����
            $esmProjectDao = new model_engineering_project_esmproject();
            $project = $esmProjectDao->get_d($_REQUEST['projectId']);
            $project = $esmProjectDao->feeDeal($project);

            // ����豸������0�������
            if ($project['budgetEqu'] > 0 && !$_POST['budgetType']) {
                $rsArr['amount'] = bcadd($rsArr['amount'], $project['budgetEqu'], 2);
                $rows[] = array('id' => 'noId', 'parentName' => '�豸Ԥ��',  'amount' => $project['budgetEqu']);
            }

            // ���PKԤ�����0�������
            if ($project['budgetPK'] > 0 && !$_POST['budgetType']) {
                $rsArr['amount'] = bcadd($rsArr['amount'], $project['budgetPK'], 2);
                $rows[] = array('id' => 'noId', 'parentName' => 'PKԤ��',  'amount' => $project['budgetPK']);
            }

            // �ϼƴ���
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת���鿴Tab��Ŀ����Ԥ��
     */
    function c_toViewList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('act', isset($_GET['act']) ? 'searchJson2' : 'searchJson');

        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($_GET['projectId']);
        $this->assign('projectCode', $esmprojectObj['projectCode']);

        $this->view('viewlist');
    }

    /**
     * PK Ԥ�������
     * @param $rows
     * @param $rsArr
     * @param $budgetType
     * @return array
     */
    function setOtherFee($rows, $rsArr, $budgetType)
    {
        // PK������Ϣ��ȡ
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectObj = $esmprojectDao->get_d($_POST['projectId']);

        // ���ػ�Ʊ����
        if ($esmprojectObj['feeFlights'] > 0 && ($budgetType == 'budgetFlights' || $budgetType == '')) {
            if (!is_array($rows)) $rows = array(); // ��������row�������飬����תΪ����
            !empty($rsArr) ? array_pop($rows) : $rsArr = array('id' => 'noId', 'parentName' => '��Ŀ�ϼ�'); // ����кϲ����򵯳�row���һ��,�����ʼ��rsArr
            // ����PK��Ŀ��Ϣ
            array_push($rows, array(
                'id' => 'budgetFlights',
                'parentName' => '��Ʊ����', 'budgetType' => 'budgetFlights',
                'budgetName' => '��Ʊ����',
                'remark' => '�˷���ȡ�Ի�Ʊϵͳ',
                'amount' => '0.00',
                'actFee' => $esmprojectObj['feeFlights'],
                'feeProcess' => '0.00'
            ));
            // PKԤ�������ϼƲ���
            $rsArr['actFee'] = bcadd($esmprojectObj['feeFlights'], $rsArr['actFee'], 2);
            $rows[] = $rsArr;
        }

        // ����в�ѯ����������ߴ���PK����
        $pkProjectRows = $budgetType == 'budgetTrial' || $budgetType == '' ? $esmprojectDao->getPKInfo_d(null, $esmprojectObj) : array();
        if (!empty($pkProjectRows)) {
            if (!is_array($rows)) $rows = array(); // ��������row�������飬����תΪ����
            !empty($rsArr) ? array_pop($rows) : $rsArr = array('id' => 'noId', 'parentName' => '��Ŀ�ϼ�'); // ����кϲ����򵯳�row���һ��,�����ʼ��rsArr
            foreach ($pkProjectRows as $v) {
                // ����PK��Ŀ��Ϣ
                array_push($rows, array(
                    'id' => 'budgetTrial',
                    'parentName' => '������Ŀ', 'budgetType' => 'budgetTrial',
                    'budgetName' => $v['projectCode'], 'projectId' => $v['id'],
                    'remark' => $v['projectName'],
                    'amount' => $v['budgetAll'], 'actFee' => $v['feeAllCount'],
                    'feeProcess' => bcmul(bcdiv($v['budgetAll'], $v['feeAllCount'], 4), 100, 2),
                ));
                // PKԤ�������ϼƲ���
                $rsArr['amount'] = bcadd($v['budgetAll'], $rsArr['amount'], 2);
                $rsArr['actFee'] = bcadd($v['feeAllCount'], $rsArr['actFee'], 2);
            }
            $rsArr['feeProcess'] = bcmul(bcdiv($rsArr['actFee'], $rsArr['amount'], 4), 100, 2);
            $rows[] = $rsArr;
        }
        return $rows;
    }

    /**
     * ��ȡ�������ݷ���json
     */
    function c_listJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->list_d();
        // ���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * �б�����Ȩ�޹���
     * @param $rows
     * @return mixed
     */
    function gridDateFilter($rows)
    {
        // ����Ԥ�㵥��Ȩ�޵� 2013-07-08
        $otherDataDao = new model_common_otherdatas();
        $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        if (!$esmLimitArr['����Ԥ�㵥��']) {
            foreach ($rows as $key => $val) {
                if ($val['budgetType'] == 'budgetPerson' && empty($val['customPrice'])) {
                    $rows[$key]['price'] = '******';
                }
            }
        }
        return $rows;
    }

    /**
     * ����tabҳ
     */
    function c_manageTab()
    {
        $this->assignFunc($_GET);
        $this->view('manageTab');
    }

    /**
     * �鿴tabҳ
     */
    function c_viewTab()
    {
        $this->assignFunc($_GET);
        $this->view('viewTab');
    }

    /************************* ҵ���ܴ��� *************************/

    /**
     * ��ת��������Ŀ����Ԥ��ҳ��
     */
    function c_toAdd()
    {
        // ��Ŀ������Ⱦ
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('add', true);
    }

    /**
     * ��ĿԤ����������
     */
    function c_toBatchAdd()
    {
        // ��Ŀ������Ⱦ
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        // ģ����Ⱦ
        $customtemplateDao = new model_finance_expense_customtemplate();
        $customtemplateArr = $customtemplateDao->getTemplate_d();
        $this->assignFunc($customtemplateArr);

        $this->view('addbatch', true);
    }

    /**
     * ��������
     */
    function c_addBatch()
    {
        $this->checkSubmit(); // �ظ��ύ
        $object = $_POST[$this->objName];
        if ($this->service->addBatch_d($object)) {
            //�жϴ��޸��Ƿ����ڱ��
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                msgRf('����ɹ������ڡ������¼����Ŀ�ύ����');
            } else {
                msgRf('����ɹ�');
            }
        } else {
            msgRf('����ʧ��');
        }
    }

    /**
     * �༭
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->assign('orgId', '-1');
        if ($obj['budgetType'] == 'budgetPerson') {
            //����Ԥ�㵥��Ȩ�޵� 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['����Ԥ�㵥��']) {
                $this->assign('priceView', '******');
            } else {
                $this->assign('priceView', $obj['price']);
            }
            $this->view('edit-person');
        } else {
            $this->view('edit');
        }
    }

    /**
     * �޸Ķ���
     * @param bool $isEditInfo
     * @throws Exception
     */
    function c_edit($isEditInfo = false)
    {
        $this->checkSubmit(); //�ظ��ύ
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object, $isEditInfo)) {
            //�жϴ��޸��Ƿ����ڱ��
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($object['projectId'])) {
                msg('����ɹ������ڡ������¼����Ŀ�ύ����');
            } else {
                msg('�༭�ɹ���');
            }
        }
    }

    /**
     * ��ת���鿴
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        if ($obj['budgetType'] == 'budgetPerson' && empty($obj['customPrice'])) {
            //����Ԥ�㵥��Ȩ�޵� 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['����Ԥ�㵥��']) {
                $this->assign('price', '******');
            }

            $this->view('view-person');
        } else {
            $this->view('view');
        }
    }

    /**
     * ���������ֳ�Ԥ��
     */
    function c_toAddField()
    {
        //ģ����Ⱦ
        $customtemplateDao = new model_finance_expense_customtemplate();
        $customtemplateArr = $customtemplateDao->getTemplate_d();
        if ($customtemplateArr) {
            $this->assignFunc($customtemplateArr);

            //��Ŀ������Ⱦ
            $rs = $this->service->getProjectInfo_d($_GET['projectId']);
            $this->assignFunc($rs);
            $this->assign('projectId', $_GET['projectId']);

            $this->view('addfield');
        } else {
            $this->assign('userName', $_SESSION['USERNAME']);
            $this->assign('userId', $_SESSION['USER_ID']);
            $this->view('createtemplate');
        }
    }

    /**
     * ��������-����Ԥ��
     */
    function c_toAddPerson()
    {
        //��Ŀ������Ⱦ
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('addperson');
    }

    /**
     * ��������-���Ԥ��
     */
    function c_toAddOutsourcing()
    {
        //��Ŀ������Ⱦ
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('addoutsourcing');
    }

    /**
     * ��������-����Ԥ��
     */
    function c_toAddOther()
    {
        //��Ŀ������Ⱦ
        $rs = $this->service->getProjectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        $this->view('addother');
    }

    /********************* ������� ************************/
    /**
     *  ����鿴
     */
    function c_toViewChange()
    {
        $this->permCheck(); // ��ȫУ��
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);

        if ($obj['budgetType'] == 'budgetPerson' && empty($obj['customPrice'])) {
            //����Ԥ�㵥��Ȩ�޵� 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['����Ԥ�㵥��']) {
                $this->assign('price', '******');
            }
            $this->view('view-person');
        } else {
            $this->view('view');
        }
    }

    /**
     * ����༭
     */
    function c_toEditChange()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);
        if ($obj['budgetType'] == 'budgetPerson') {
            //����Ԥ�㵥��Ȩ�޵� 2013-07-08
            $otherDataDao = new model_common_otherdatas();
            $esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
            if (!$esmLimitArr['����Ԥ�㵥��']) {
                $this->assign('priceView', '******');
            } else {
                $this->assign('priceView', $obj['price']);
            }
            $this->view('edit-person');
        } else {
            $this->view('edit');
        }
    }

    /**
     * ajax��ʽ����ɾ��
     */
    function c_ajaxdeletes()
    {
        try {
            $this->service->deletes_d($_POST['id'], $_POST['projectId'], $_POST['changeId']);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ��ѯ������ϸ
     */
    function c_toFeeDetail()
    {
        $this->assignFunc($_GET);

        // ��Ŀidת��
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($_GET['projectId']);
        $this->assign('projectCode', $project['projectCode']);
        $this->assign('contractCode', $project['contractType'] == 'GCXMYD-01' ? $project['contractCode'] : '');

        $this->view('feeDetail');
    }

    /**
     * ��ѯ������ϸ
     */
    function c_feeDetail()
    {
        echo util_jsonUtil::encode($this->service->feeDetail_d($_POST['projectId'],
            util_jsonUtil::iconvUTF2GB($_POST['budgetName']), $_POST['budgetType']));
    }

    /**
     * ��ĿԤ���ȡ - �����������͵�Ԥ��(ͨ��������Ŀid)
     */
    function c_getAllBudgetDetail()
    {
        echo util_jsonUtil::encode($this->service->getAllBudgetDetail_d($_POST['projectId']));
    }

    /**
     * ��Ŀ����Ԥ���б�
     */
    function c_searchJson()
    {
        // ���ݻ��� - �����ѯ�����ľ�������
        $dataCache = array();

        // ������������
        $data = array();

        // ����ȡ����Ŀ - ��Ҫ�ǻ�ȡĳЩ�̻�����Ŀ���еĳɱ�����
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($_POST['projectId']);

        // ��ȡԤ��
        $budgetData = $this->service->getBudgetData_d($_POST);

        // Ԥ���з�
        $budgetCache = $this->service->budgetSplit_d($budgetData);

        // ��ȡ����
        $feeData = $this->service->getFee_d($_POST['projectId']);

        // �����з�
        $feeCache = $this->service->feeSplit_d($feeData);

        // �����ɱ�
        $dataCache[0] = $this->service->getPersonFee_d($budgetCache[0], $feeCache[0], $project);

        // ����֧���ɱ�
        $dataCache[1] = $this->service->getFieldFee_d($budgetCache[1], $feeCache[1], $project);
//        echo "<pre>";print_r($dataCache);exit();

        // �豸�ɱ�
        $dataCache[2] = $this->service->getEquFee_d($budgetCache[2], $feeCache[2], $project);

        // ����ɱ�
        $dataCache[3] = $this->service->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $project);

        // �����ɱ�
        $dataCache[4] = $this->service->getOtherFee_d($budgetCache[4], $feeCache[4], $project);

        // �����ѯ�����ݣ�����������������
        if (!empty($dataCache)) {
            $budgetAll = 0;
            $feeAll = 0;
            foreach ($dataCache as $v) {
                foreach ($v as $vi) {
                    $data[] = $vi;
                    if ($vi['id'] == 'noId') {
                        $budgetAll = bcadd($budgetAll, $vi['amount'], 2);
                        $feeAll = bcadd($feeAll, $vi['actFee'], 2);
                    }
                }
            }
            // ��������Ϣ����
            array_push($data, $this->service->getCountRow_d('noId', '��Ŀ�ϼ�', $budgetAll, $feeAll));
        }

        echo util_jsonUtil::encode($data);
    }

    /**
     * ��Ŀ����Ԥ���б�
     */
    function c_searchJson2()
    {
        //��ʼ����Ŀ�ϼƵĴ����Ԥ��/����
        $amountWait = 0;
        $actFeeWait = 0;

        $mulRows = array(); // �ֳ������������������������
        $personRows = array(); // ��������
        $personBudgetCount = 0; // ����Ԥ�������� - ������������

        // �����еĲ�������
        $otherDatasDao = new model_common_otherdatas();
        $subsidyArr = $otherDatasDao->getConfig('engineering_budget_subsidy', null, 'arr');

        //����ֵ�����豸ʱ,��ȡ��ҵ����Ԥ����Ϣ
        if ($_POST['budgetType'] != 'budgetDevice') {
            $rows = $this->service->getBudgetData_d($_POST);
            foreach ($rows as $val) {
                //�ۼӵ����Ԥ������Ϣ
                if ($val['isImport'] == 1) {
                    $amountWait = bcadd($val['amountWait'], $amountWait, 2);
                    $actFeeWait = bcadd($val['actFeeWait'], $actFeeWait, 2);
                }

                // ������룬����������ִ���
                if ($val['budgetType'] == 'budgetPerson') {
                    if (!in_array($val['budgetName'], $subsidyArr)) {
                        $personBudgetCount++;
                    } else {
                        $personRows[] = $val;
                    }
                } else {
                    $mulRows[] = $val;
                }
            }
        }
        $rowArr = array(); //�洢ɸѡ�����������
        $rowSum = array('parentName' => '�� Ŀ �� ��', 'id' => 'noId', 'actFee' => 0, 'amount' => 0,
            'amountWait' => $amountWait, 'actFeeWait' => $actFeeWait, 'feeProcess' => 0,
            'budgetType' => '');
        //�洢��Ŀ�ϼƵ�����
        //�����ֳ�����
        $feeArr = $this->service->getFee_d($_POST['projectId']);

        //�����ֳ�Ԥ�㣬���Ԥ�㣬����Ԥ�㣬��������ʱ��Ŀ�ܼƺͻ�ȡ����Ԥ�����
        if (isset($mulRows) || isset($feeArr)) {
            $result = $this->service->getNormalData_d($rowArr, $mulRows, $rowSum, $feeArr, $_POST);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }

        //��������Ԥ��
        if ($_POST['budgetType'] == 'budgetPerson' || $_POST['budgetType'] == '') {
            $result = $this->service->getBudgetPerson_d($rowArr, $rowSum, $_POST, $personBudgetCount, $feeArr, $personRows);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }

        //�����豸Ԥ��
        if ($_POST['budgetType'] == 'budgetDevice' || $_POST['budgetType'] == '') {
            $result = $this->service->getBudgetDevice_d($rowArr, $rowSum, $_POST);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }

        // ��Ʊ����
        $esmprojectObj = array();
        if ($_POST['budgetType'] == 'budgetFlights' || $_POST['budgetType'] == '') {
            // ����ȡ����Ŀ
            $esmprojectDao = new model_engineering_project_esmproject();
            $esmprojectObj = $esmprojectDao->get_d($_POST['projectId']);
            if ($esmprojectObj['feeFlights'] > 0 || $esmprojectObj['feeFlightsShare'] > 0) {
                $feeFlightsAll = bcadd($esmprojectObj['feeFlights'], $esmprojectObj['feeFlightsShare'], 2);
                // ��������Ϣ����
                array_push($rowArr, array(
                    'id' => 'budgetFlights', 'budgetType' => 'budgetFlights', 'parentName' => '��Ʊ����', 'detCount' => '',
                    'budgetName' => '��Ʊ����',
                    'remark' => '��Ʊϵͳ��' . $esmprojectObj['feeFlights'] .
                        '������̯ϵͳ��' . $esmprojectObj['feeFlightsShare'] . '��',
                    'amount' => '0.00', 'actFee' => $feeFlightsAll,
                    'feeProcess' => '0.00'
                ));
                // �ѽ����ص��б�ϼ���
                $rowSum['actFee'] = bcadd($rowSum['actFee'], $feeFlightsAll, 2);
            }
        }

        // ����������ĿԤ��
        if ($_POST['budgetType'] == 'budgetTrial' || $_POST['budgetType'] == '') {
            $result = $this->service->getBudgetTrial_d($rowArr, $rowSum, $_POST, $esmprojectObj);
            $rowArr = $result['rowArr'];
            $rowSum = $result['rowSum'];
        }
        // ��Ŀ�ϼ�
        if ($rowArr) {
            $rowSum['feeProcess'] = bcmul(bcdiv($rowSum['actFee'], $rowSum['amount'], 4), 100, 2);
            array_push($rowArr, $rowSum);
            echo util_jsonUtil::encode($rowArr);
        }
    }

    /**
     * ������Ŀ����Ԥ���б�
     */
    function c_exportExcel()
    {
        // ���ݻ��� - �����ѯ�����ľ�������
        $dataCache = array();

        // ������������
        $data = array();

        // ����ȡ����Ŀ - ��Ҫ�ǻ�ȡĳЩ�̻�����Ŀ���еĳɱ�����
        $projectDao = new model_engineering_project_esmproject();
        $project = $projectDao->get_d($_GET['projectId']);

        // ��ȡԤ��
        $budgetData = $this->service->getBudgetData_d($_GET);

        // Ԥ���з�
        $budgetCache = $this->service->budgetSplit_d($budgetData);

        // ��ȡ����
        $feeData = $this->service->getFee_d($_GET['projectId']);

        // �����з�
        $feeCache = $this->service->feeSplit_d($feeData);

        // �����ɱ�
        $dataCache[0] = $this->service->getPersonFee_d($budgetCache[0], $feeCache[0], $project);

        // ����֧���ɱ�
        $dataCache[1] = $this->service->getFieldFee_d($budgetCache[1], $feeCache[1], $project);

        // �豸�ɱ�
        $dataCache[2] = $this->service->getEquFee_d($budgetCache[2], $feeCache[2], $project);

        // ����ɱ�
        $dataCache[3] = $this->service->getOutsourcingFee_d($budgetCache[3], $feeCache[3], $project);

        // �����ɱ�
        $dataCache[4] = $this->service->getOtherFee_d($budgetCache[4], $feeCache[4], $project);

        // �����ѯ�����ݣ�����������������
        if (!empty($dataCache)) {
            foreach ($dataCache as $v) {
                foreach ($v as $vi) {
                    if ($vi['id'] != 'noId') {
                        $data[] = $vi;
                    }
                }
            }

            return model_engineering_util_esmexcelutil::exportBudget($data);
        }
    }

    /**
     * ������Ŀʵʱ�б�������Ŀ����Ԥ���б�
     */
    function c_exportAllExcel()
    {
        if (isset($_SESSION['engineering_project_esmproject_listSql'])) {
            $sql = base64_decode($_SESSION['engineering_project_esmproject_listSql']);
            unset($_SESSION['engineering_project_esmproject_listSql']);
            $datas = $this->service->getExcelData_d($sql);
            if (!empty($datas)) {
                return model_engineering_util_esmexcelutil::exportBudgetAll($datas);
            } else {
                msg("��������");
            }
        } else {
            msg("��ˢ���б�����");
        }
    }

    /**
     * ������Ŀʵʱ�б�������Ŀ����Ԥ���б�(CSV)
     */
    function c_exportAllExcelCSV()
    {
        if (isset($_SESSION['engineering_project_esmproject_listSql'])) {
            $sql = base64_decode($_SESSION['engineering_project_esmproject_listSql']);
            unset($_SESSION['engineering_project_esmproject_listSql']);
            $datas = $this->service->getExcelData_d($sql);
            if (!empty($datas)) {
                $colArr = array(
                    'projectCode' => '��Ŀ���',
                    'statusName' => '״̬',
                    'parentName' => '���ô���',
                    'budgetName' => '����С��',
                    'amount' => 'Ԥ��',
                    'actFee' => '����',
                    'amountWait' => '�����Ԥ��',
                    'actFeeWait' => '����˾���',
                    'feeProcess' => '���ý���',
                    'remark' => '��ע˵��'
                );
                // ���ݴ���
                foreach ($datas as $k => $v) {
                    $datas[$k]['parentName'] = $v['projectCode'];
                    $datas[$k]['statusName'] = $v['statusName'];
                    $datas[$k]['parentName'] = $v['parentName'];
                    $datas[$k]['budgetName'] = $v['budgetName'];
                    $datas[$k]['amount'] = empty($v['amount']) && $v['amount'] != '0' ? '' : number_format($v['amount'], 2);
                    $datas[$k]['actFee'] = empty($v['actFee']) && $v['actFee'] != '0' ? '' : number_format($v['actFee'], 2);
                    $datas[$k]['amountWait'] = empty($v['amountWait']) && $v['amountWait'] != '0' ? '' : number_format($v['amountWait'], 2);
                    $datas[$k]['actFeeWait'] = empty($v['actFeeWait']) && $v['actFeeWait'] != '0' ? '' : number_format($v['actFeeWait'], 2);
                    $datas[$k]['feeProcess'] = empty($v['feeProcess']) && $v['feeProcess'] != '0' ? '' : $v['feeProcess'] . '%';
                    if ($v['isImport'] == 1) {
                        if ($v['status'] == 0) {
                            $datas[$k]['remark'] = '��̨�������ݣ�δ���';
                        } else {
                            $datas[$k]['remark'] = '��̨�������ݣ������';
                        }
                    }
                }
                return model_engineering_util_esmexcelutil::exportCSV($colArr, $datas, '��ĿԤ���㵼��');
            } else {
                msg("��������");
            }
        } else {
            msg("��ˢ���б�����");
        }
    }

    /**
     * ��ĿԤ����ϸ
     */
    function c_toSearchDetailList()
    {
        $this->assignFunc($_GET);
        $this->view("detail-list");
    }

    /**
     * ��ĿԤ����ϸ��Ϣ
     */
    function c_searhDetailJson()
    {
        $condition = array();
        $condition['isImport'] = 0; //ֻ��ȡ���ǵ��������
        //��ȡ����
        if ($_POST['budgetType'] == 'budgetPerson') {
            $condition['projectId'] = $_POST['projectId'];
            $condition['parentName'] = $_POST['parentName'];
            $condition['budgetType'] = $_POST['budgetType'];
        } else {
            $condition['projectId'] = $_POST['projectId'];
            $condition['parentName'] = $_POST['parentName'];
            $condition['budgetName'] = $_POST['budgetName'];
        }

        $service = $this->service;
        $service->getParam($condition);
        $rows = $service->list_d('select_default');

        //����Ȩ��
        $rows = $this->gridDateFilter($rows);

        if (!empty($rows)) {
            //������Ŀ�ϼ�
            $service->sort = "";
            $service->groupBy = 'c.projectId';
            $objArr = $service->listBySqlId('count_all');
            $rsArr = array();
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['parentName'] = '�� ��';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;
        }

        echo util_jsonUtil::encode($rows);
    }
}