<?php

/**
 * @author Michael
 * @Date 2014��1��20�� ����һ 15:32:49
 * @version 1.0
 * @description:�⳵��������������Ʋ�
 */
class controller_outsourcing_vehicle_rentalcar extends controller_base_action
{

    function __construct()
    {
        $this->objName = "rentalcar";
        $this->objPath = "outsourcing_vehicle";
        parent::__construct();
    }

    /**
     * ��ת���⳵�������������б�
     */
    function c_page()
    {
        $this->assign('userId', $_SESSION['USER_ID']);
        $this->service->setCompany(0); # �����б�,����Ҫ���й�˾����
        $this->view('list');
    }

    /**
     * ������ͷ����ܼ�
     * ��ת���⳵����������������б�
     */
    function c_toAllList()
    {
        $this->assign('projectId', isset($_GET['projectId']) ? $_GET['projectId'] : "");
        $this->view('allList');
    }

    /**
     * ��ȡ��ҳ����ת��Json(ȥ����˾Ȩ��)
     */
    function c_pageJson2()
    {
        $service = $this->service;
        $service->setCompany(0);

        $service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ȡ��ҳ����ת��Json(����Ŀ��Ϣ)
     */
    function c_pageJsonProject()
    {
        $service = $this->service;
        $service->getParam($_REQUEST); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $service->page_d();
        if (is_array($rows)) {
            $projectdao = new model_engineering_project_esmproject();
            $projectArr = array();
            foreach ($rows as $key => $val) {
                if (is_array($projectArr[$val['projectId']])) {
                    $projectObj = $projectArr[$val['projectId']];
                } else {
                    $projectObj = $projectdao->get_d($val['projectId']);
                    $projectArr[$val['projectId']] = $projectObj;
                }
                $rows[$key]['officeId'] = $projectObj['officeId'];
                $rows[$key]['officeName'] = $projectObj['officeName'];
                $rows[$key]['projectType'] = $projectObj['natureName'];
                $rows[$key]['projectTypeCode'] = $projectObj['nature'];
                $rows[$key]['projectManager'] = $projectObj['managerName'];
                $rows[$key]['projectManagerId'] = $projectObj['managerId'];
            }
        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * �⳵�ӿ���
     * ��ת���⳵�����������������б�
     */
    function c_toDealList()
    {
        $this->view('dealList');
    }

    /**
     * �⳵������
     * ��ת���⳵������������ȷ���б�
     */
    function c_toAffirmList()
    {
        $this->view('affirmList');
    }

    /**
     * ��Ŀ����
     * ��ת���鿴�⳵������������ȷ���б�
     */
    function c_toViewProjectList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('view-projectList');
    }

    /**
     * ��Ŀ����
     * ��ת���鿴�⳵������������ȷ���б�
     */
    function c_toEditProjectList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('edit-projectList');
    }

    /**
     * ��ת�������⳵������������ҳ��
     */
    function c_toAdd()
    {
        $this->assign('createId', $_SESSION ['USER_ID']);
        $this->assign('createName', $_SESSION ['USERNAME']);
        $this->assign('createTime', date("Y-m-d H:i:s"));
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC')); //����ʱ��
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD')); //����ʱ���
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS')); //Ԥ��ÿ���ó�����
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ')); //�⳵����
        $this->showDatadicts(array('payGasolineCode' => 'WBYF')); //�ͷ�
        $this->showDatadicts(array('payParkingCode' => 'WBLQT')); //·��ͣ����
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS')); //�Ƿ�֧˾��ʳ��

        //��ȡ����
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->view('add', true);
    }

    /**
     * ��ת�������⳵������������ҳ��
     */
    function c_toAddByProject()
    {
        $this->assign('createId', $_SESSION ['USER_ID']);
        $this->assign('createName', $_SESSION ['USERNAME']);
        $this->assign('createTime', date("Y-m-d H:i:s"));
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC')); //����ʱ��
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD')); //����ʱ���
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS')); //Ԥ��ÿ���ó�����
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ')); //�⳵����
        $this->showDatadicts(array('payGasolineCode' => 'WBYF')); //�ͷ�
        $this->showDatadicts(array('payParkingCode' => 'WBLQT')); //·��ͣ����
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS')); //�Ƿ�֧˾��ʳ��

        //��ȡ����
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        //��ȡ��Ŀ��Ϣ
        $projectDao = new model_engineering_project_esmproject();
        $projectObj = $projectDao->get_d($_GET['projectId']);
        $this->assign('projectId', $projectObj['id']);
        $this->assign('projectName', $projectObj['projectName']);
        $this->assign('projectCode', $projectObj['projectCode']);
        $this->assign('projectType', $projectObj['natureName']);
        $this->assign('projectTypeCode', $projectObj['nature']);
        $this->assign('projectManager', $projectObj['managerName']);
        $this->assign('provinceId', $projectObj['provinceId']);
        $this->assign('cityId', $projectObj['cityId']);

        $this->assign('projectBudget', $this->service->getBudgetByProId_d($_GET['projectId'])); //��ĿԤ����

        $this->view('add-project', true);
    }

    /**
     * ��������¼�����ת���б�ҳ
     */
    function c_add()
    {
        $this->checkSubmit(); //��֤�Ƿ��ظ��ύ
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $rentalcarId = $this->service->add_d($_POST[$this->objName]);
        if ($rentalcarId) {
            if ($actType) {
                $esmDao = new model_engineering_project_esmproject();
                $areaId = $esmDao->getRangeId_d($_POST[$this->objName]['projectId']);
                if ($areaId > 0) {
                    $billArea = $areaId;
                } else {
                    $billArea = '';
                }
                succ_show('controller/outsourcing/vehicle/ewf_index.php?actTo=ewfSelect&billId=' . $rentalcarId .
                    '&billArea=' . $billArea . '&flowMoney=' . $_POST[$this->objName]['estimateAmonut']);
            } else {
                msg('����ɹ���');
            }
        } else {
            msg('����ʧ�ܣ�');
        }
    }

    /**
     * ��ת���༭�⳵������������ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->assign('projectBudget', $this->service->getBudgetByProId_d($_GET['projectId'])); //��ĿԤ����
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC'), $obj['testTimeCode']); //����ʱ��
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD'), $obj['testPeriodCode']); //����ʱ���
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS'), $obj['expectUseDayCode']); //Ԥ��ÿ���ó�����
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ'), $obj['rentalPropertyCode']); //�⳵����
        $this->showDatadicts(array('payGasolineCode' => 'WBYF'), $obj['payGasolineCode']); //�ͷ�
        $this->showDatadicts(array('payParkingCode' => 'WBLQT'), $obj['payParkingCode']); //·��ͣ����
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS'), $obj['isPayDriverCode']); //�Ƿ�֧˾��ʳ��

        //��ȡ����
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        $this->view('edit', true);
    }

    /**
     * ��ת���༭�⳵������������ҳ��
     */
    function c_toEditByProject()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->assign('projectBudget', $this->service->getBudgetByProId_d($_GET['projectId'])); //��ĿԤ����
        $this->showDatadicts(array('testTimeCode' => 'WBCSSC'), $obj['testTimeCode']); //����ʱ��
        $this->showDatadicts(array('testPeriodCode' => 'WBCSSJD'), $obj['testPeriodCode']); //����ʱ���
        $this->showDatadicts(array('expectUseDayCode' => 'WBYCTS'), $obj['expectUseDayCode']); //Ԥ��ÿ���ó�����
        $this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ'), $obj['rentalPropertyCode']); //�⳵����
        $this->showDatadicts(array('payGasolineCode' => 'WBYF'), $obj['payGasolineCode']); //�ͷ�
        $this->showDatadicts(array('payParkingCode' => 'WBLQT'), $obj['payParkingCode']); //·��ͣ����
        $this->showDatadicts(array('isPayDriverCode' => 'WBZFSS'), $obj['isPayDriverCode']); //�Ƿ�֧˾��ʳ��

        $this->view('edit-project', true);
    }

    /**
     * �⳵�ӿ���
     * ��ת���༭�⳵������������ҳ��
     */
    function c_toDealEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '��';
        } else {
            $obj['isTestEngineer'] = '��';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], true)); //��ʾ������Ϣ

        //��ȡ����
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);

        $this->view('dealEdit');
    }

    /**
     * �༭�����¼�
     */
    function c_edit($isEditInfo = false)
    {
        $actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
        $rentalcarId = $this->service->edit_d($_POST[$this->objName]);
        if ($rentalcarId) {
            if ($actType) {
                $esmDao = new model_engineering_project_esmproject();
                $areaId = $esmDao->getRangeId_d($_POST[$this->objName]['projectId']);
                if ($areaId > 0) {
                    $billArea = $areaId;
                } else {
                    $billArea = '';
                }
                succ_show('controller/outsourcing/vehicle/ewf_index.php?actTo=ewfSelect&billId=' .
                    $rentalcarId . '&billArea=' . $billArea  . '&flowMoney=' . $_POST[$this->objName]['estimateAmonut']);
            } else {
                msg('����ɹ���');
            }
        } else {
            msg('����ʧ�ܣ�');
        }
    }

    /**
     * �⳵������
     * ��ת��ȷ���⳵������������ҳ��
     */
    function c_toAffirmEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '��';
        } else {
            $obj['isTestEngineer'] = '��';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], false)); //��ʾ������Ϣ
        $this->view('affirmEdit');
    }

    /**
     * �⳵�ӿ��˱༭�����¼�
     */
    function c_dealEdit()
    {
        $isSubmit = isset ($_GET['isSubmit']) ? $_GET['isSubmit'] : null;
        $id = $_POST[$this->objName]['id'];
        $obj = $_POST[$this->objName]['supp']; //�ӱ�����
        $rentalcarId = $this->service->dealEdit_d($id, $obj);
        if ($rentalcarId) {
            if ($isSubmit) {
                $this->service->update(array('id' => $id), array('state' => '5'));
            } else {
                $this->service->update(array('id' => $id), array('state' => '4'));
            }
            msg('����ɹ���');
        } else {
            msg('����ʧ�ܣ�');
        }
    }

    /**
     * �⳵�ӿ���ֱ���ύ
     */
    function c_dealSubmit()
    {
        echo $this->service->update(array('id' => $_POST['id']), array('state' => '5'));
    }

    /**
     * �⳵������ȷ�Ϲ�Ӧ��
     */
    function c_affirmEdit()
    {
        $id = $_POST[$this->objName]['id'];
        $obj = $_POST[$this->objName]['supp']; //�ӱ�����
        $rentalcarId = $this->service->affirmEdit_d($id, $obj); //��д����
        if ($rentalcarId) {
            msg('����ɹ���');
        } else {
            msg('����ʧ�ܣ�');
        }
    }

    /**
     * ��ת���鿴�⳵������������ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '��';
        } else {
            $obj['isTestEngineer'] = '��';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        if ($obj['isApplyOilCard'] == 1) {
            $obj['isApplyOilCard'] = '��';
        } else {
            $obj['isApplyOilCard'] = '��';
        }
        $this->assign('isApplyOilCard', $obj['isApplyOilCard']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], false)); //��ʾ������Ϣ
        $this->view('view');
    }

    /**
     * ��ת���鿴�⳵������������ҳ��(�ɷ����ͬ)
     */
    function c_toViewContract()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '��';
        } else {
            $obj['isTestEngineer'] = '��';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);

        if ($obj['isApplyOilCard'] == 1) {
            $obj['isApplyOilCard'] = '��';
        } else {
            $obj['isApplyOilCard'] = '��';
        }
        $this->assign('isApplyOilCard', $obj['isApplyOilCard']);

        $this->show->assign("file", $this->service->getFilesByObjId($_GET ['id'], false)); //��ʾ������Ϣ
        $this->view('view-contract');
    }

    /**
     * ��ת���⳵�����˴��ҳ��
     */
    function c_toBack()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('back');
    }

    /**
     * �⳵�����˴��
     */
    function c_back()
    {
        $arr = $this->service->back_d($_POST[$this->objName]);
        if ($arr) {
            msg('��سɹ���');
        } else {
            msg('���ʧ�ܣ�');
        }
    }

    /**
     * ��ת���鿴�⳵�����˴��ԭ��ҳ��
     */
    function c_toBackReason()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('backreason');
    }

    /**
     * ����ʱ��ʾ�鿴�⳵������������ҳ��
     */
    function c_toAudit()
    {
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        $this->assign('projectBudget', '99999.01'); //��ĿԤ����

        if ($obj['isTestEngineer'] == 1) {
            $obj['isTestEngineer'] = '��';
        } else {
            $obj['isTestEngineer'] = '��';
        }
        $this->assign('isTestEngineer', $obj['isTestEngineer']);


        if ($obj['isApplyOilCard'] == 1) {
            $obj['isApplyOilCard'] = '��';
        } else {
            $obj['isApplyOilCard'] = '��';
        }
        $this->assign('isApplyOilCard', $obj['isApplyOilCard']);
        $this->view('audit');
    }

    /**
     * ����ʡ��id���ذ��´���Ϣ
     */
    function c_getOfficeInfoForId()
    {
        $esmDao = new model_engineering_project_esmproject();
        $areaId = $esmDao->getRangeId_d($_POST['projectId']);
        if ($areaId > 0) {
            $billArea = $areaId;
        } else {
            $billArea = '';
        }
        echo $billArea;
    }

    /**
     * �⳵��������ͨ������
     */
    function c_dealAfterAuditPass()
    {
        if (!empty ($_GET ['spid'])) {
            //�������ص�����
            $this->service->workflowCallBack($_GET['spid']);
        }
        echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
    }

    /**
     * ����excel
     */
    function c_excelOut()
    {
        set_time_limit(0);
        $rows = $this->service->listBySqlId('select_excelOut');
        for ($i = 0; $i < count($rows); $i++) {
            unset($rows[$i]['id']);
        }
        $colArr = array();
        $modelName = '���-�⳵������Ϣ';
        $startRowNum = 2;
        return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rows, $modelName, $startRowNum);
    }

    /**
     * ��ת���Զ��嵼��excelҳ��
     */
    function c_toExcelOutCustom()
    {
        //�жϴ������ĵ���
        $createId = isset ($_GET['createId']) ? $_GET['createId'] : null;
        $this->assign('createId', $createId); //�����б���
        $isSetCompany = isset ($_GET['isSetCompany']) ? $_GET['isSetCompany'] : null;
        $this->assign('isSetCompany', $isSetCompany); //�����б���

        $ExaStatus = isset ($_GET['ExaStatus']) ? "��������','���','���" : null;
        $this->assign('ExaStatus', $ExaStatus); //�����б��������ύ������
        $this->view('excelOutCustom');
    }

    /**
     * �Զ��嵼��excel
     */
    function c_excelOutCustom()
    {
        set_time_limit(0);
        $formData = $_POST[$this->objName];

        if (!empty($formData['formCode'])) //���ݱ��
            $this->service->searchArr['formCode'] = $formData['formCode'];

        if (!empty($formData['projectCode'])) //��Ŀ���
            $this->service->searchArr['projectCode'] = $formData['projectCode'];

        if (!empty($formData['projectName'])) //��Ŀ����
            $this->service->searchArr['projectName'] = $formData['projectName'];

        if (!empty($formData['createName'])) //������
            $this->service->searchArr['createNameSea'] = $formData['createName'];

        if (!empty($formData['createTimeSta'])) //����������
            $this->service->searchArr['createTimeSta'] = $formData['createTimeSta'];
        if (!empty($formData['createTimeEnd'])) //����������
            $this->service->searchArr['createTimeEnd'] = $formData['createTimeEnd'];

        if (!empty($formData['province'])) //�ó�ʡ��
            $this->service->searchArr['province'] = $formData['province'];

        if (!empty($formData['city'])) //�ó�����
            $this->service->searchArr['city'] = $formData['city'];

        if (!empty($formData['createId'])) //������ID
            $this->service->searchArr['createId'] = $formData['createId'];

        if (!empty($formData['ExaStatus'])) //����״̬
            $this->service->searchArr['ExaStatusArr'] = str_replace('\\', '', $formData['ExaStatus']);

        if (!empty($formData['isSetCompany'])) //���ܱ��������ֹ�����˾
            $this->service->setCompany(0);

        $rows = $this->service->listBySqlId('select_default');
        if (!$rows) {
            echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
                . "<script type='text/javascript'>"
                . "alert('û�м�¼!');self.parent.tb_remove();"
                . "</script>";
        }

        $rowData = array();
        foreach ($rows as $key => $val) {
            $rowData[$key]['formCode'] = $val['formCode'];
            $rowData[$key]['projectCode'] = $val['projectCode'];
            $rowData[$key]['projectName'] = $val['projectName'];
            $rowData[$key]['projectType'] = $val['projectType'];
            $rowData[$key]['rentalProperty'] = $val['rentalProperty'];
            $rowData[$key]['createName'] = $val['createName'];
            $rowData[$key]['createTime'] = $val['createTime'];
            $rowData[$key]['applicantPhone'] = $val['applicantPhone'];
            if ($val['provinceId'] == 43) { //CDMA�Ŷ�
                $rowData[$key]['useCarPlace'] = $val['usePlace'];
            } else {
                $rowData[$key]['useCarPlace'] = $val['province'] . '-' . $val['city'];
            }
            $rowData[$key]['useCarAmount'] = $val['useCarAmount'];
            $rowData[$key]['expectStartDate'] = $val['expectStartDate'];
            $rowData[$key]['useCycle'] = $val['useCycle'];
        }

        $colArr = array();
        $modelName = '���-�⳵������Ϣ';
        $startRowNum = 2;
        return model_outsourcing_outsourcessupp_importVehiclesuppUtil::export2ExcelUtil($colArr, $rowData, $modelName, $startRowNum);
    }

    /**
     * ��ȡȨ��
     */
    function c_getLimits()
    {
        $limitName = util_jsonUtil::iconvUTF2GB($_POST['limitName']);
        echo $this->service->this_limit[$limitName];
    }

    /**
     * ��ת�����ɺ�ͬҳ��
     */
    function c_toAddContract()
    {
        $obj = $this->service->get_d($_GET['rentalcarId']);

        $this->showDatadicts(array('contractNatureCode' => 'ZCHTXZ')); //��ͬ����
        $this->showDatadicts(array('contractTypeCode' => 'ZCHTLX')); //��ͬ����
        $this->showDatadicts(array('payTypeCode' => 'ZCHTFK')); //��ͬ���ʽ

        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('principalId', $_SESSION['USER_ID']);
        $this->assign('principalName', $_SESSION['USERNAME']);
        $this->assign('signDate', day_date);
        $this->assign('createTime', date("Y-m-d H:i:s"));

        $this->assign('rentalcarId', $_GET['rentalcarId']); //���뵥ID
        $this->assign('rentalcarCode', $_GET['rentalcarCode']); //���뵥��

        $projectdao = new model_engineering_project_esmproject();
        $projectObj = $projectdao->get_d($obj['projectId']);
        $this->assign('projectId', $obj['projectId']); //��ĿID
        $this->assign('projectCode', $obj['projectCode']); //��Ŀ���
        $this->assign('projectName', $obj['projectName']); //��Ŀ����
        $this->assign('projectType', $projectObj['natureName']); //��Ŀ����
        $this->assign('projectTypeCode', $projectObj['nature']); //��Ŀ����Code
        $this->assign('projectManagerId', $projectObj['managerId']); //��Ŀ����ID
        $this->assign('projectManager', $projectObj['managerName']); //��Ŀ����
        $this->assign('officeId', $projectObj['officeId']); //����ID
        $this->assign('officeName', $projectObj['officeName']); //����

        $equDao = new model_outsourcing_vehicle_rentalcarequ();
        $equObj = $equDao->findAll(array('parentId' => $_GET['rentalcarId']), '', 'suppId');
        foreach ($equObj as $key => $val) {
            $idArr[$key] = $val['suppId'];
        }
        $ids = implode(',', $idArr);
        $this->assign('suppIds', $ids); //��Ӧ��Ids

        $this->assign('isApplyOilCard', $obj['isApplyOilCard']); //�Ƿ������Ϳ�
        $this->view('add-contract', true);
    }

    /**
     * �⳵���������ȡ��ҳ����ת��Json
     */
    function c_pageJsonDeal()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $service->groupBy = 'c.id';
        //$service->asc = false;
        $rows = $service->page_d('select_deallist');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ������Ŀ��ת���鿴�⳵��Ϣtabҳ��
     */
    function c_viewVehicleTab()
    {
        $this->assign('projectId', $_GET['projectId']);

        //�⳵��ͬid�����鿴�����������
        $rentcarDao = new model_outsourcing_contract_rentcar();
        $rentcarObjs = $rentcarDao->findAll(array('projectId' => $_GET['projectId']));
        $rentcarArr = array();
        if (is_array($rentcarObjs)) {
            foreach ($rentcarObjs as $key => $val) {
                array_push($rentcarArr, $val['id']);
            }
        }
        $this->assign('rentcarIds', implode(',', $rentcarArr));

        $this->view('view-vehicle-tab');
    }

    /**
     * ������Ŀ��ת���༭�⳵��Ϣtabҳ��
     */
    function c_editVehicleTab()
    {
        $this->assign('projectId', $_GET['projectId']);

        //�⳵��ͬid�����鿴�����������
        $rentcarDao = new model_outsourcing_contract_rentcar();
        $rentcarObjs = $rentcarDao->findAll(array('projectId' => $_GET['projectId']));
        $rentcarArr = array();
        if (is_array($rentcarObjs)) {
            foreach ($rentcarObjs as $key => $val) {
                array_push($rentcarArr, $val['id']);
            }
        }
        $this->assign('rentcarIds', implode(',', $rentcarArr));

        $this->view('edit-vehicle-tab');
    }

    /**
     * ������Ŀid��ȡ��ĿԤ��
     */
    function c_getBudgetByProId()
    {
        echo $this->service->getBudgetByProId_d($_POST['projectId']);
    }

    /**
     * ��ת�⳵ת����������Ϣ�鿴ҳ��
     */
    function c_toSeeCostExpense(){
        $payType = $payMainId = $bankName = $bankAccount = $bankReceiver = $includeFeeType = $includeCurNum = $extFormStr = '';
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();

        $fromPage = isset($_REQUEST['fromPage'])? $_REQUEST['fromPage'] : '';
        $_REQUEST = $_REQUEST['addCostApply'];
        $includeCurNum = isset($_REQUEST['carNum'])? util_jsonUtil::iconvUTF2GB($_REQUEST['carNum']) : "";
        $payinfoId = (isset($_REQUEST['payInfoId']) && $_REQUEST['payInfoId']!= 'undefined')? $_REQUEST['payInfoId'] : '';
        $expenseTmpId = (isset($_REQUEST['expenseTmpId']) && $_REQUEST['expenseTmpId']!= 'undefined')? $_REQUEST['expenseTmpId'] : '';
        $deductInfoId = (isset($_REQUEST['deductInfoId']) && $_REQUEST['deductInfoId']!= 'undefined')? $_REQUEST['deductInfoId'] : '';
        $passArr = $_REQUEST;
        $passArr['mainId'] = '';

        if($expenseTmpId != '' && $expenseTmpId != "-"){
            $expenseTmpObj = $expensetmpDao->findExpenseTmpRecord($expenseTmpId);
            $includeCurNum = ($expenseTmpObj != '')? $expenseTmpObj['carNumber'] : $includeCurNum;
        }

        $rentalCarDao = new model_outsourcing_vehicle_rentalcar();

        $formType = "bx";
        if($payinfoId != ''){// ����֧����ʽID��ȡ֧����Ϣ
            $payInfo = $this->service->getPayInfoById($payinfoId);
            if($payInfo){
                if($payInfo['payTypeCode'] == "HETFK"){
                    $formType = "zf";
                }
                $payType = $payInfo['payType'];
                $bankName = $payInfo['bankName'];
                $bankAccount = $payInfo['bankAccount'];
                $bankReceiver = $payInfo['bankReceiver'];
                $includeFeeType = $payInfo['includeFeeType'];
                $passArr['mainId'] = $payMainId = $payInfo['mainId'];
                $passArr['payDetail'] = array();
                $includeFeeTypeCode = $payInfo['includeFeeTypeCode'];
                $includeFeeTypeCode = explode(",",$includeFeeTypeCode);
                foreach ($includeFeeTypeCode as $type){
                    if(isset($rentalCarDao->_rentCarFeeName[$type]) && isset($_REQUEST[$type])){
                        $passArr['payDetail'][$type] = $_REQUEST[$type];
                    }
                }

            }
        }else{// ��֧��ID��Ĭ�϶������������Լ����еķ�������
            $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$_SESSION['USER_ID']}';";
            $bankInfo = $this->service->_db->getArray($bankInfoSql);
            $payType = "������������";
            $bankName = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
            $bankAccount = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
            // $bankReceiver = ($bankInfo && isset($bankInfo[0]['oftenCardNum']))? $bankInfo[0]['oftenCardNum'] : '';
            $bankReceiver = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
            $includeFeeType = "";
            $passArr['payDetail'] = array();
            foreach ($_REQUEST as $k => $v){
                if(isset($rentalCarDao->_rentCarFeeName[$k])){
                    $includeFeeType .= ($includeFeeType == "")? $rentalCarDao->_rentCarFeeName[$k] : ",".$rentalCarDao->_rentCarFeeName[$k];
                    $passArr['payDetail'][$k] = $v;
                }
            }
        }

        // ������������
        foreach ($_REQUEST as $k => $v){
            if(isset($rentalCarDao->_rentCarFeeName[$k])){
                unset($passArr[$k]);
            }
        }
        $passArr['carNum'] = base64_encode($passArr['carNum']);

        // ֻ�б༭ҳ����ת�����Ĳ���ʾ���뱨���İ�ť
        if($fromPage == "Edit"){
            if($formType == "bx"){// ���ʽΪ����
                $extFormStr = "<input type=\"button\" id=\"toAddCost\" class=\"txt_btn_a\"value=\"����ñ�\" />";
            }else if($formType == "zf"){// ���ʽΪ֧��
                $extFormStr = "<input type=\"button\" id=\"toPayCost\" class=\"txt_btn_a\"value=\"����\" />";
            }
        }else{// ������ʾ��صı����������Ϣ

        }

        $costInfoArr = util_jsonUtil::encode($passArr);
        $resultStr = "<table width=\"100%\" style=\"margin-left: 4px\" class=\"form_main_table\">
                    <tr>
                        <td class=\"form_text_left_three_new\" colspan=\"6\" style=\"text-align: center;\">֧����Ϣ</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">֧����ʽ</td><td class=\"form_text_right_three\" colspan=\"5\">{$payType}</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">�տ�����</td><td class=\"form_text_right_three\">{$bankName}</td>
                        <td class=\"form_text_left_three_new\">�տ��˺�</td><td class=\"form_text_right_three\">{$bankAccount}</td>
                        <td class=\"form_text_left_three_new\">�տ���</td><td class=\"form_text_right_three\">{$bankReceiver}</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">����������</td><td class=\"form_text_right_three\" colspan=\"5\">{$includeFeeType}</td>
                    </tr>
                    <tr>
                        <td class=\"form_text_left_three_new\">�������ƺ�</td><td class=\"form_text_right_three\" colspan=\"5\">{$includeCurNum}</td>
                    </tr>
                    <tr>
                        <td class=\"\" align=\"center\" colspan=\"6\">
                            <input  type=\"hidden\" id=\"payinfoId\"  value=\"{$payinfoId}\" />
                            <input  type=\"hidden\" id=\"expenseTmpId\"  value=\"{$expenseTmpId}\" />
                            <input  type=\"hidden\" id=\"payMainId\"  value=\"{$payMainId}\" />
                            <input  type=\"hidden\" id=\"deductInfoId\"  value='{$deductInfoId}' />
                            <input  type=\"hidden\" id=\"costInfoArr\"  value='{$costInfoArr}' />
                            {$extFormStr}
                        </td>
                    </tr>
                </table>";
        echo util_jsonUtil::iconvGB2UTF($resultStr);
    }

    /**
     * ��ת�⳵ת����ҳ��
     */
    function c_toAddCostExpense(){
        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
        $_GET['costInfo'] = str_replace("\\","",$_GET['costInfo']);
        $costInfo = util_jsonUtil::decode($_GET['costInfo']);
        $expenseTmpId = isset($_GET['expenseTmpId'])? $_GET['expenseTmpId'] : '';
        $useCarDate = isset($_GET['useCarDate'])? $_GET['useCarDate'] : '';
        $deductInfoId = isset($costInfo['deductInfoId'])? $costInfo['deductInfoId'] : '';
        $deductInfo = false;
        if(!empty($deductInfoId) && $deductInfoId != 'undefined'){
            $deductInfo = $deductinfoDao->findAll(" id in ({$deductInfoId})");
        }

        if(isset($costInfo['payDetail'])){
            $payDetail = $costInfo['payDetail'];
            unset($costInfo['payDetail']);
        }

        if(!isset($costInfo['payInfoId'])){
            $costInfo['payInfoId'] = '';
        }
        foreach ($costInfo as $key => $val) {
            $this->assign($key, $val);
        }

        // ���������ʱ���Ѿ����Ը��ݷ�����ϸ�����Ƿ����⳵�����жϿۿ���
        if($deductInfo && isset($payDetail['rentalCarCost'])){
            $deductMoney = 0;
            foreach ($deductInfo as $k => $v){
                $deductMoney = bcadd($deductMoney,$v['deductMoney'],2);
            }
            $this->assign("deductInfoId", $deductInfoId);
            $payDetail['rentalCarCost'] = bcsub($payDetail['rentalCarCost'],$deductMoney,2);
        }else{
            $this->assign("deductInfoId", '');
        }

        $this->assign("registerIds", $deductInfo['registerIds']);
        $this->assign("payDetailJson", util_jsonUtil::encode($payDetail));
        $this->assign("expenseTmpId", $expenseTmpId);
        $this->assign("useCarDateStr", $useCarDate);

        $this->view('addCostExpense');
    }

    /**
     * ��ת�⳵ת����ҳ�棨ͬʱ����֧����ʽ1��2��
     */
    function c_toBatchAddCostExpense(){
        $this->view('batchAddCostExpense');
    }

    /**
     * ͨ���⳵ϵͳ�ķ������ͻ�ȡ��Ӧ�ķ���ϵͳ���ó��ķ�������
     */
    function c_getCostTypeByRentalCarType(){
        $payTypeDetail = isset($_POST['payDetail'])? $_POST['payDetail'] : '';
        $expenseTmpId = isset($_POST['expenseTmpId'])? $_POST['expenseTmpId'] : '';
        $useCarDateStr = isset($_POST['useCarDateStr'])? $_POST['useCarDateStr'] : '';
        $configuratorDao = new model_system_configurator_configurator();
        $costTypeIds = "";
        $carNumberArr = $costMoneyArr = $costTypeArr = array();
        $rentalProperty = $allregisterId = '';

        $existCostTypeArr = array();
        if($expenseTmpId != "" && $expenseTmpId != "-"){// ������һ����д������
            $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
            $expensetmpData = $expensetmpDao->findExpenseTmpRecord($expenseTmpId,'','','',1);
            $rentalProperty = isset($expensetmpData['rentalProperty'])? $expensetmpData['rentalProperty'] : '';
            $allregisterId = isset($expensetmpData['allregisterId'])? $expensetmpData['allregisterId'] : '';
            $carNumberStr = isset($expensetmpData['carNumber'])? $expensetmpData['carNumber'] : '';
            $carNumberArr = explode(",",$carNumberStr);
            if(isset($expensetmpData['detail']) && is_array($expensetmpData['detail'])){
                foreach ($expensetmpData['detail'] as $k => $v){
                    $arr['CostTypeID'] = $v['costTypeCode'];
                    $arr['CostTypeName'] = $v['costType'];
                    $arr['showDays'] = 0;
                    $arr['isSubsidy'] = 0;
                    $arr['ParentCostTypeID'] = $v['costBigTypeCode'];
                    $arr['ParentCostType'] = $v['costBigType'];
                    $arr['costMoney'] = $v['costMoney'];
                    $arr['invoiceData'] = util_jsonUtil::decode($v['invoiceDataJson']);
                    $existCostTypeArr[] = $arr;
                }
            }
        }

        // �����Ҵ������¼��ʱ��
        if(!empty($rentalProperty) && $rentalProperty == '����' && !empty($allregisterId)){
            $registerDao = new model_outsourcing_vehicle_register();
//            $registerDao->getParam ( array("allregisterId" => $allregisterId, "useCarDateLimit" => $useCarDateStr) );
//            $registerDao->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
//            $rows = $registerDao->listBySqlId ( 'select_Month' );
            $rows = $registerDao->getStatisticsJsonData($allregisterId,$useCarDateStr);
            if($payTypeDetail != '' && $rows){
                $catchPayTypeDetail = array();
                foreach ($rows as $key => $row){
                    if ($row['rentalContractId'] > 0) {
                        //�����⳵�Ѻͺ�ͬ�ó�����
                        $row['rentalCarCost'] = $registerDao->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,false);
                        $row['contractUseDay'] = $registerDao->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,true);

                        //����⳵
                        if ($row['rentalPropertyCode'] == 'ZCXZ-03') {
                            $row['rentalCarCost'] = $registerDao->getHongdaFee_d($row['id'] ,$row['rentalContractId']);
                        }else{
                            $rows[$key]['rentalPropertyCode'] = $val['rentalPropertyCode'] = 'ZCXZ-01';// ���й�����ͬ�ŵ�,Ĭ��Ϊ����
                            $rows[$key]['rentalProperty'] = $val['rentalProperty'] = '����';
                        }
                    } else {
                        $rows[$key]['rentalCarCost'] = '';
                        $rows[$key]['contractUseDay'] = '';
                    }

                    if ($row['rentalPropertyCode'] == 'ZCXZ-02') { //��������
                        $obj = $registerDao->get_d( $row['id'] );
                        $row['rentalCarCost'] = $rows[$key]['shortRent']; //�����ֱ����ʾ���⳵�ѵ��ۼ�
                        $row['gasolineKMPrice'] = $obj['gasolineKMPrice'];
                        $row['gasolineKMCost'] = $obj['gasolineKMPrice'] * $rows[$key]['effectMileage'];
                    }

                    if(in_array($row['carNum'],$carNumberArr) && $row['rentalPropertyCode'] != 'ZCXZ-01'){
                        foreach ($payTypeDetail as $payDKey => $payDVal){
                            $catchPayTypeDetail[$payDKey] = isset($catchPayTypeDetail[$payDKey])? $catchPayTypeDetail[$payDKey] + $row[$payDKey] : $row[$payDKey];
                        }
                    }
                }
                $payTypeDetail = $catchPayTypeDetail;
            }
        }

        // if($expenseTmpId == "" || $expenseTmpId == "-"){// ������¼
            if($payTypeDetail != ''){
                foreach ($payTypeDetail as $type => $cost){
                    $matchConfigItem = $configuratorDao->getConfigItems('ZCFYMM','config_itemSub1',$type,array('config_itemSub3' => "1"));
                    if($matchConfigItem && count($matchConfigItem) > 0){
                        if(!empty($matchConfigItem[0]['config_itemSub2'])){
                            // ����ͬһ��Ŀ�ķ��ý��
                            $costMoneyArr[$matchConfigItem[0]['config_itemSub2']] += $cost;
                        }

                        $costTypeIds .= (!empty($matchConfigItem[0]['config_itemSub2']))? (($costTypeIds == "")? $matchConfigItem[0]['config_itemSub2'] : ",".$matchConfigItem[0]['config_itemSub2']) : '';
                    }
                }
            }

            if($costTypeIds != ""){
                $chkCostTypeSql = "select t2.CostTypeID,t2.CostTypeName,t2.showDays,t2.isReplace,t2.isEqu,t2.invoiceType,t2.invoiceTypeName,t2.isSubsidy,t2.ParentCostTypeID,t2.ParentCostType from cost_type t2 left join cost_type t1 on t2.ParentCostTypeID = t1.CostTypeID where t1.CostTypeID = 339 and t1.CostTypeName = '��������֧����' and t2.CostTypeID in ({$costTypeIds})";
                $costTypeArr = $this->service->_db->getArray($chkCostTypeSql);
                $costTypeArr = ($costTypeArr)? $costTypeArr : array();
                if(!empty($costTypeArr)){
                    foreach ($costTypeArr as $k => $v){
                        $costTypeArr[$k]['costMoney'] = isset($costMoneyArr[$v['CostTypeID']])? $costMoneyArr[$v['CostTypeID']] : 0;
                    }
                }
            }
        // }else{// ���м�¼

        // �����֮ǰ�ļ�¼,����֮ǰ�Ŀ�Ʊ��Ϣ
        if(!empty($existCostTypeArr)){
            foreach ($costTypeArr as $orgKey => $orgVal){
                foreach ($existCostTypeArr as $existKey => $existVal){
                    if($orgVal['CostTypeID'] == $existVal['CostTypeID']){
                        $costTypeArr[$orgKey]['invoiceData'] = $existVal['invoiceData'];
                    }
                }
            }
        }
        echo util_jsonUtil::encode($costTypeArr);
    }

    /**
     * ���������ʱ��,��ȡ��صķ�������Ϣ
     */
    function c_getCostExpenseInfo(){
        $backArr = array();
        $payInfoId = isset($_POST['payInfoId'])? $_POST['payInfoId'] : '';
        $catchCostArr = isset($_POST['catchArr'])? $_POST['catchArr'] : '';
        $deductInfoId = isset($catchCostArr['deductInfoId'])? $catchCostArr['deductInfoId'] : '';
        $payInfoArr = $this->service->getPayInfoById($payInfoId);

        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
        $deductInfo = array();
        if($deductInfoId != ''){
            $deductInfoSql = "select id,sum(deductMoney) as deductMoney from oa_outsourcing_allregister_deductinfo where id in ({$deductInfoId});";
            $deductInfo = $deductinfoDao->get_one($deductInfoSql);
        }

        $catchCostArr['rentalCarCost'] = isset($catchCostArr['rentalCarCost'])? $catchCostArr['rentalCarCost'] : 0;
        $catchCostArr['rentalCarCost'] = ($deductInfo && $deductInfo['deductMoney'] > 0)? bcsub($catchCostArr['rentalCarCost'],$deductInfo['deductMoney'],2) : $catchCostArr['rentalCarCost'];

        $includeFeeTypeCodeArr  = explode(",",$payInfoArr['includeFeeTypeCode']);
        foreach ($catchCostArr as $key => $val){
           if(!in_array($key,$includeFeeTypeCodeArr)){
               unset($catchCostArr[$key]);
           }else if($key == 'rentalCarCost'){
               $payInfoArr['deductInfoId'] = $deductInfoId;
           }
        }

        $costMoneyArr = $costTypeArr = array();$costTypeIds = "";
        if(!empty($catchCostArr)){
            $configuratorDao = new model_system_configurator_configurator();
            foreach ($catchCostArr as $type => $cost){
                $matchConfigItem = $configuratorDao->getConfigItems('ZCFYMM','config_itemSub1',$type,array('config_itemSub3' => "1"));
                if($matchConfigItem && count($matchConfigItem) > 0){
                    if(!empty($matchConfigItem[0]['config_itemSub2'])){
                        // ����ͬһ��Ŀ�ķ��ý��
                        $costMoneyArr[$matchConfigItem[0]['config_itemSub2']] += $cost;
                    }

                    $costTypeIds .= (!empty($matchConfigItem[0]['config_itemSub2']))? (($costTypeIds == "")? $matchConfigItem[0]['config_itemSub2'] : ",".$matchConfigItem[0]['config_itemSub2']) : '';
                }
            }

            if($costTypeIds != ""){
                $chkCostTypeSql = "select t2.CostTypeID,t2.CostTypeName,t2.showDays,t2.isReplace,t2.isEqu,t2.invoiceType,t2.invoiceTypeName,t2.isSubsidy,t2.ParentCostTypeID,t2.ParentCostType from cost_type t2 left join cost_type t1 on t2.ParentCostTypeID = t1.CostTypeID where t1.CostTypeID = 339 and t1.CostTypeName = '��������֧����' and t2.CostTypeID in ({$costTypeIds})";
                $costTypeArr = $this->service->_db->getArray($chkCostTypeSql);
                $costTypeArr = ($costTypeArr)? $costTypeArr : array();
                if(!empty($costTypeArr)){
                    foreach ($costTypeArr as $k => $v){
                        $costTypeArr[$k]['costMoney'] = isset($costMoneyArr[$v['CostTypeID']])? $costMoneyArr[$v['CostTypeID']] : 0;
                    }
                }
            }
        }

        $backArr['costInfo'] = util_jsonUtil::iconvGB2UTFArr($costTypeArr);
        $backArr['payInfo'] = util_jsonUtil::iconvGB2UTFArr($payInfoArr);

        echo json_encode($backArr);
    }

    /**
     * ����⳵����������ʱ��Ϣ������ͨ�����ת����Ч��Ϣ,����ɼ����༭��
     */
    function c_addCostExpenseTmp(){
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $expenseTmp = isset($_POST['expenseTmp'])? $_POST['expenseTmp'] : array();
        $deductInfoId = isset($expenseTmp['deductInfoId'])? $expenseTmp['deductInfoId'] : '';

        // ��ȡ��������֧����ʽ�ķ�������json����
        $expenseTmp['payDetailJson'] = str_replace("\\","",$expenseTmp['payDetailJson']);
        $payDetailJson = util_jsonUtil::decode($expenseTmp['payDetailJson']);
        $payMoney = 0;
        foreach ($payDetailJson as $v){
            $payMoney = bcadd($payMoney,$v,2);
        }
        // ���½������ƺ�
        $carNumber = base64_decode($expenseTmp['carNum']);
        $carNumber = util_jsonUtil::iconvUTF2GB($carNumber);

        // ��ȡ��ص��⳵�Ǽ���Ϣ�Լ��⳵��ͬ��Ϣ
        $rentalCarBaseInfo = $this->service->getRentalCarBaseInfo($expenseTmp['allregisterId'],$carNumber,$expenseTmp['rentalContractId']);

        $rentalProperty = $rentalPropertyCode = "";
        if($expenseTmp['rentalContractId'] && $expenseTmp['rentalContractId'] > 0){
            $rentalProperty = '����';
            $rentalPropertyCode = 'ZCXZ-01';
        }else{
            $rentalProperty = (isset($expenseTmp['rentalPropertyCode']) && $expenseTmp['rentalPropertyCode'] == "ZCXZ-02")? "����" : $rentalCarBaseInfo['rentalProperty'];
            $rentalPropertyCode = (isset($expenseTmp['rentalPropertyCode']) && $expenseTmp['rentalPropertyCode'] == "ZCXZ-02")? "ZCXZ-02" : $rentalCarBaseInfo['rentalPropertyCode'];
        }

        $mainArr = array(
            "id" => $expenseTmp['id'],
            "allregisterId" => $expenseTmp['allregisterId'],
            "carNumber" => $carNumber,
            "carNumBase64" => base64_encode($carNumber),
            "rentalProperty" => $rentalProperty,
            "rentalPropertyCode" => $rentalPropertyCode,
            "driverName" => $rentalCarBaseInfo['driverName'],
            "rentalContractId" => $expenseTmp['rentalContractId'],
            "contractStartDate" => $rentalCarBaseInfo['contractStartDate'],
            "contractEndDate" => $rentalCarBaseInfo['contractEndDate'],
            "useCarDate" => $expenseTmp['useCarDate'],
            "useCarStartDate" => $rentalCarBaseInfo['startDate'],
            "useCarEndDate" => $rentalCarBaseInfo['endDate'],
            "useCarDays" => $rentalCarBaseInfo['useCarDays'],
            "payInfoId" => $expenseTmp['payInfoId'],
            "registerIds" => $expenseTmp['registerIds'],
            "payMoney" => $payMoney,
            "isConfirm" => 0,
            "ExaStatus" => "δ����"
        );

        $payDetail = array();
        // ����֧��������ʱ��¼�ӱ�����
        if(!empty($expenseTmp['expensedetail'])){
            $rentalPropertype = $mainArr['rentalProperty'];
            $rentalPropertypeCode = $mainArr['rentalPropertyCode'];
            $costMoney = 0;
            foreach ($expenseTmp['expensedetail'] as $detail){
                $arr['parentId'] = '';
                $arr['rentcarType'] = $rentalPropertype;
                $arr['rentcarTypeCode'] = $rentalPropertypeCode;
                $arr['costBigType'] = isset($detail['MainType'])? $detail['MainType'] : '';
                $arr['costBigTypeCode'] = isset($detail['MainTypeId'])? $detail['MainTypeId'] : '';
                $arr['costType'] = isset($detail['costType'])? $detail['costType'] : '';
                $arr['costTypeCode'] = isset($detail['costTypeId'])? $detail['costTypeId'] : '';
                $arr['costMoney'] = isset($detail['costMoney'])? str_replace(",","",$detail['costMoney']) : 0;
                $costMoney = bcadd($costMoney,$arr['costMoney'],3);
                $invoiceArr = array();
                if(isset($detail['expenseinv']) && !empty($detail['expenseinv'])){
                    foreach ($detail['expenseinv'] as $invoiceItem){
                        if($invoiceItem['isDelTag'] != 1){
                            $invoiceItem['Amount'] = str_replace(",","",$invoiceItem['Amount']);
                            $invoiceArr[] = $invoiceItem;
                        }
                    }
                }
                $arr['invoiceDataJson'] = json_encode($invoiceArr);
                $arr['costBigType'] = util_jsonUtil::iconvUTF2GB($arr['costBigType']);
                $arr['costType'] = util_jsonUtil::iconvUTF2GB($arr['costType']);
                $payDetail[] = $arr;
            }

            // ���ݷ�����ϸ��ͳ����ķ��ý��
            $mainArr['payMoney'] = $costMoney;
        }

        $tmpRecordId = $expensetmpDao->addRecord($mainArr,$payDetail);
        if($tmpRecordId && $deductInfoId != ''){
            $updateSql = "update oa_outsourcing_allregister_deductinfo set payinfoId = '{$expenseTmp['payInfoId']}', expensetmpId = '{$tmpRecordId}' where id = '{$deductInfoId}';";
            $this->service->_db->query($updateSql);
        }

        // echo"<pre>"; print_r($result);
        // $data = $expensetmpDao->findExpenseTmpRecord("",$mainArr['allregisterId'],$mainArr['carNumBase64'],$mainArr['payInfoId'],1);
        // print_r($data); print_r($mainArr); print_r($payDetail);
        echo ($tmpRecordId)? "ok" : "fail";
    }

    /**
     * ���������������Ϣ
     */
    function c_batchAddCZCostExpenseTmp(){
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $expenseTmp = isset($_POST['expenseTmp'])? $_POST['expenseTmp'] : array();

        // ���½������ƺ�
        $carNumber = util_jsonUtil::iconvUTF2GB($expenseTmp['carNum']);
        // ��ȡ��ص��⳵�Ǽ���Ϣ�Լ��⳵��ͬ��Ϣ
        $rentalCarBaseInfo = $this->service->getRentalCarBaseInfo($expenseTmp['allregisterId'],$carNumber,$expenseTmp['rentalContractId']);

        $payInfoNum = 0;
        if(isset($expenseTmp[1]) && isset($expenseTmp[1]['expensedetail'])){
            $payInfoNum = 1;
        }
        if(isset($expenseTmp[2]) && isset($expenseTmp[2]['expensedetail'])){
            $payInfoNum = 2;
        }

        $expenseTmp = util_jsonUtil::iconvUTF2GBArr($expenseTmp);

        $tmpRecordAddResult = true;
        if($payInfoNum > 0){
            for ($i = 1;$i <= $payInfoNum;$i++){
                $payMoney = 0;
                $rentalProperty = "����";
                $rentalPropertyCode = "ZCXZ-01";
                $dataArr = $expenseTmp[$i];
                $deductInfoId = isset($dataArr['deductInfoId'])? $dataArr['deductInfoId'] : '';

                $mainArr = array(
                    "id" => $expenseTmp['id'],
                    "allregisterId" => $expenseTmp['allregisterId'],
                    "carNumber" => $carNumber,
                    "carNumBase64" => base64_encode($carNumber),
                    "rentalProperty" => $rentalProperty,
                    "rentalPropertyCode" => $rentalPropertyCode,
                    "driverName" => $rentalCarBaseInfo['driverName'],
                    "rentalContractId" => $expenseTmp['rentalContractId'],
                    "contractStartDate" => $rentalCarBaseInfo['contractStartDate'],
                    "contractEndDate" => $rentalCarBaseInfo['contractEndDate'],
                    "useCarDate" => $expenseTmp['useCarDate'],
                    "useCarStartDate" => $rentalCarBaseInfo['startDate'],
                    "useCarEndDate" => $rentalCarBaseInfo['endDate'],
                    "useCarDays" => $rentalCarBaseInfo['useCarDays'],
                    "payInfoId" => $dataArr['payInfoId'],
                    "registerIds" => $expenseTmp['registerIds'],
                    "payMoney" => $payMoney,
                    "isConfirm" => 0,
                    "ExaStatus" => "δ����"
                );

                $payDetail = array();
                // ����֧��������ʱ��¼�ӱ�����
                if(!empty($dataArr['expensedetail'])){
                    $rentalPropertype = $mainArr['rentalProperty'];
                    $rentalPropertypeCode = $mainArr['rentalPropertyCode'];
                    $costMoney = 0;
                    foreach ($dataArr['expensedetail'] as $detail){
                        $arr['parentId'] = '';
                        $arr['rentcarType'] = $rentalPropertype;
                        $arr['rentcarTypeCode'] = $rentalPropertypeCode;
                        $arr['costBigType'] = isset($detail['MainType'])? $detail['MainType'] : '';
                        $arr['costBigTypeCode'] = isset($detail['MainTypeId'])? $detail['MainTypeId'] : '';
                        $arr['costType'] = isset($detail['costType'])? $detail['costType'] : '';
                        $arr['costTypeCode'] = isset($detail['costTypeId'])? $detail['costTypeId'] : '';
                        $arr['costMoney'] = isset($detail['costMoney'])? str_replace(",","",$detail['costMoney']) : 0;
                        $costMoney = bcadd($costMoney,$arr['costMoney'],3);
                        $invoiceArr = array();
                        if(isset($detail['expenseinv']) && !empty($detail['expenseinv'])){
                            foreach ($detail['expenseinv'] as $invoiceItem){
                                if($invoiceItem['isDelTag'] != 1){
                                    $invoiceItem['Amount'] = str_replace(",","",$invoiceItem['Amount']);
                                    $invoiceArr[] = $invoiceItem;
                                }
                            }
                        }
                        $arr['invoiceDataJson'] = json_encode($invoiceArr);
                        $payDetail[] = $arr;
                    }

                    // ���ݷ�����ϸ��ͳ����ķ��ý��
                    $mainArr['payMoney'] = $costMoney;
                }
                $tmpRecordId = $expensetmpDao->addRecord($mainArr,$payDetail);
                if($tmpRecordId && $deductInfoId != ''){
                    $updateSql = "update oa_outsourcing_allregister_deductinfo set payinfoId = '{$dataArr['payInfoId']}', expensetmpId = '{$tmpRecordId}' where id = '{$deductInfoId}';";
                    $this->service->_db->query($updateSql);
                }
                $tmpRecordAddResult = (!$tmpRecordId)? false : $tmpRecordAddResult;
            }
        }else{
            $tmpRecordAddResult = false;
        }

        echo ($tmpRecordAddResult)? "ok" : "fail";
    }

    function c_viewCostExpenseTmp(){
        $otherDataDao = new model_common_otherdatas();
        $payInfoId = isset($_GET['payInfoId'])? $_GET['payInfoId'] : '';
        $expenseTmpId = (isset($_GET['expenseTmpId']) && $_GET['expenseTmpId'] != '-')? $_GET['expenseTmpId'] : '';
        $payInfoDao = new model_outsourcing_contract_payInfo();
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $expenseTmp = $expensetmpDao->findExpenseTmpRecord($expenseTmpId,"","","",1);
        $payInfo = $payInfoDao->get_d($payInfoId);
        $billType = $otherDataDao->getBillType();
        $billTypeArr = array();
        if($billType && is_array($billType)){
            foreach ($billType as $k => $v){
                $billTypeArr[$v['id']] = $v['name'];
            }
        }

        // ������Ϣ
        $payType = $bankName = $bankAccount = $bankReceiver = $includeFeeType = "";
        if($payInfoId == ""){
            $rentalCarDao = new model_outsourcing_vehicle_rentalcar();
            $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$expenseTmp['createId']}';";
            $bankInfo = $this->service->_db->getArray($bankInfoSql);
            $payType = "������������";
            $bankName = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
            $bankAccount = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
            $bankReceiver = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
            $includeFeeType = "";
            if(is_array($rentalCarDao->_rentCarFeeName)){
                foreach ($rentalCarDao->_rentCarFeeName as $feeName){
                    $includeFeeType .= ($includeFeeType == "")? $feeName : ",".$feeName;
                }
            }
        }else if($payInfo){
            $payType = $payInfo['payType'];
            if($payType == "������������"){
                $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$expenseTmp['createId']}';";
                $bankInfo = $this->service->_db->getArray($bankInfoSql);
                $bankName = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
                $bankAccount = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
                $bankReceiver = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
            }else{
                $bankName = $payInfo['bankName'];
                $bankAccount = $payInfo['bankAccount'];
                $bankReceiver = $payInfo['bankReceiver'];
            }
            $includeFeeType = $payInfo['includeFeeType'];
        }
        $this->assign("payType",$payType);
        $this->assign("bankName",$bankName);
        $this->assign("bankAccount",$bankAccount);
        $this->assign("bankReceiver",$bankReceiver);
        $this->assign("includeFeeType",$includeFeeType);
        $this->assign("includeCurNum",$expenseTmp['carNumber']);

        // ������Ϣ
        $costDetails = "";
        $costDetail = isset($expenseTmp['detail'])? $expenseTmp['detail'] : '';
        if($costDetail != '' && $expenseTmpId != '' && is_array($expenseTmp['detail'])){
            $totalAmount = $totalInvVal = $totalInvNum = 0;
            foreach ($expenseTmp['detail'] as $k => $item){
                $invoiceData = util_jsonUtil::decode($item['invoiceDataJson']);

                $tr_class = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
                $costDetails .= "<tr class='{$tr_class}'>
                    <td valign='top' class='form_text_right'>{$item['costBigType']}</td>
                    <td valign='top' class='form_text_right'>{$item['costType']}</td>
                    <td valign='top' class='form_text_center formatMoney' align='center'>{$item['costMoney']}</td>
                ";
                $totalAmount += $item['costMoney'];
                $invStr = "<td valign='top' colspan='4' class='innerTd'>";
                if(is_array($invoiceData)){
                    $invStr .= "<table class='form_in_table'>";
                    foreach ($invoiceData as $invItem){
                        $totalInvVal += $invItem['Amount'];
                        $totalInvNum += $invItem['invoiceNumber'];
                        $billType = isset($billTypeArr[$invItem['BillTypeID']])? $billTypeArr[$invItem['BillTypeID']] : '';
                        $invStr .= "
                            <tr>
                                <td width='29%'>{$billType}</td>
                                <td width='24%'>{$invItem['Amount']}</td>
                                <td width='24%'>{$invItem['invoiceNumber']}</td>
                            </tr>
                        ";
                    }
                    $invStr .= "</table>";
                }
                $invStr .= "</td>";
                $costDetails .= $invStr ."</tr>";
            }
        }

        $this->assign("costDetails",$costDetails);
        $this->assign("totalAmount",$totalAmount);
        $this->assign("totalInvVal",$totalInvVal);
        $this->assign("totalInvNum",$totalInvNum);
        // echo "<pre>";print_r($bankInfo);
        $this->view("viewCostExpenseTmp");
    }
}