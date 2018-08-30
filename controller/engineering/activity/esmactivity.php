<?php

/**
 * @author Administrator
 * @Date 2011��12��12�� 17:06:50
 * @version 1.0
 * @description:��Ŀ��Χ(oa_esm_project_activity)���Ʋ�
 */
class controller_engineering_activity_esmactivity extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmactivity";
        $this->objPath = "engineering_activity";
        parent::__construct();
    }

    /****************************** �б��� ******************************/

    /**
     * ��ת����Ŀ��Χ�б�
     */
    function c_page()
    {
        $this->view('list');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJson()
    {
        $service = $this->service;

        if (isset($_POST['parentId'])) {
            $parentId = $_POST['parentId'];
            unset($_POST['parentId']);
            $obj = $service->find(array('id' => $parentId), null, 'lft,rgt');

            $_POST['biglftNoEqu'] = $obj['lft'];
            $_POST['smallrgtNoEqu'] = $obj['rgt'];
        }

        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $rows = $service->page_d();
        if (!empty($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //������Ŀ�ϼ�
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId'], 'parentId' => PARENT_ID);
            $objArr = $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['activityName'] = '��Ŀ�ϼ�';
                $rsArr['id'] = 'noId';
            }
            $rows[] = $rsArr;

        }
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��������ȡ������Ϣ
     */
    function c_pageJsonOrg()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��־ѡ������
     */
    function c_selectActForLog()
    {
        $service = $this->service;

        $service->getParam($_REQUEST);
        $rows = $service->page_d();

        foreach ($rows as $key => $item) {
            if ($rows[$key]['parentId'] != -1) {
                $rows[$key]['activityNameView'] = $rows[$key]['parentName'] . "/" . $rows[$key]['activityName'];
            } else {
                $rows[$key]['activityNameView'] = $rows[$key]['activityName'];
            }
        }
        $arr = array();
        $arr ['collection'] = $rows;

        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr ['page'] = $service->page;

        echo util_jsonUtil::encode($arr);
    }

    /*
     * ��ת��Tab��Ŀ��Χ
     */
    function c_tabEsmactivity()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * ��ת���༭�б�
     */
    function c_toEditList()
    {
        $this->service->checkParent_d();
        $this->assign('parentId', PARENT_ID);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list-edit');
    }

    /**
     * ��ת���鿴Tab��Ŀ��Χ
     */
    function c_toViewList()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('viewlist');
    }

    /**
     * ����
     */
    function c_toTreeList()
    {
        $esmprojectDao = new model_engineering_project_esmproject();
        $outsourcingName = $esmprojectDao->find(array("id"=>$_GET['projectId']),null,"outsourcingName");

        //��������·�����A����Ŀ����
        $isACatWithFallOutsourcing = false;
        if($outsourcingName['outsourcingName'] != "����"){
            $this->assign('isCategyAProject',$this->service->updateCategoryAProcess_d($_GET['projectId']) ? 1 : 0);
        }else{
            $isACatWithFallOutsourcing = $esmprojectDao->isCategoryAProject_d(null, $_GET['projectId']);// A����Ŀ
            $this->assign('isCategyAProject', $isACatWithFallOutsourcing ? 1 : 0);
            if($isACatWithFallOutsourcing){
                $this->service->updateCategoryAProcess_d($_GET['projectId'],true);
            }
        }
//        $this->assign('isACatWithFallOutsourcing', ($isACatWithFallOutsourcing? "1" : ""));

        //��Ŀid
        $this->assign('parentId', PARENT_ID);
        $this->assign('projectId', $_GET['projectId']);
        $this->display('treelist');
    }

    /**
     * ��������
     */
    function c_manageTreeJson()
    {
        $service = $this->service;
        //��ʼ�����ڵ�
        $parentId = PARENT_ID;

        $projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
        if ($projectId) {
            $service->searchArr['projectId'] = $projectId;
        }
        $service->asc = false;

        //�ж��Ƿ���ڱ���ļ�¼
        $isChanging = $service->isChanging_d($projectId, false);
        $service->sort = 'c.lft';
        if ($isChanging) {
            //��ȡ�����¼�ĸ��ڵ�
            $parentId = $service->getChangeRoot_d($isChanging);
            $service->searchArr['changeId'] = $isChanging;
            $arrs = $service->listBySqlId('select_change');
        } else {
            $arrs = $service->listBySqlId('treelist');
        }

        //�����б�����
        $activityProcessArr = $service->getListProcess_d($projectId);

        // ������Ŀ������ȷ�Ϸ�ʽȷ����Ŀ���ȼ��ط�ʽ
        $projectDao = new model_engineering_project_esmproject();
        $projectInfo = $projectDao->get_d($projectId);

        switch ($projectInfo['incomeType']) {
            case 'SRQRFS-02' : // ��Ʊ
                $projectInfo = $projectDao->contractDeal($projectInfo);
                $projectProcess = $projectInfo['invoiceProcess'];
                break;
            case 'SRQRFS-03' : // ����
                $projectInfo = $projectDao->contractDeal($projectInfo);
                $projectProcess = $projectInfo['incomeProcess'];
                break;
            case 'SRQRFS-01' : // ����
            default :
                //�����б�����
                $activityProcessArr = $service->getListProcess_d($projectId);
        }

        if (!empty($arrs)) {
            //��ȥ_parentId
            foreach ($arrs as $key => $val) {
                // ���ݹ�ʽ���������
                $workloadCount = (strtotime(min(day_date, $val['planEndDate'])) - strtotime($val['planBeginDate'])) / 86400 + 1;
                $arrs[$key]['workloadCount'] = $workloadCount;

                if ($val['_parentId'] == $parentId) {
                    unset($arrs[$key]['_parentId']);
                }
                //����ƻ������Ѿ��ﵽ����100����100��
                $arrs[$key]['planProcess'] = $arrs[$key]['planProcess'] > 100 ? '100.00' : $arrs[$key]['planProcess'];

                //������ڱ�����뵥id,ʹ��activityId��ΪId
                $id = $isChanging ? $val['activityId'] : $val['id'];

                if(in_array($projectInfo['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) && isset($projectProcess)) {
                    $arrs[$key]['process'] = $arrs[$key]['countProcess'] = $projectProcess;
                    $arrs[$key]['waitConfirmProcess'] = '0.00';
                    $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $projectProcess, 2);
                } else {
                    //���ش�ȷ�Ͻ��ȡ��ۼƽ��ȡ��ƻ�����
                    if ($val['rgt'] - $val['lft'] == 1) {
                        $arrs[$key]['waitConfirmProcess'] = isset($activityProcessArr[$id]) ? $activityProcessArr[$id]['thisActivityProcess'] : '0.00';
                        $arrs[$key]['countProcess'] = isset($activityProcessArr[$id]) ? bcadd($activityProcessArr[$id]['thisActivityProcess'], $val['process'], 2) : $val['process'];
                        $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $arrs[$key]['countProcess'], 2);
                    }
                }
            }
            //������Ŀ�ϼ�
            $service->sort = '';
            //����ϼƴ���
            $objArr = $isChanging ? $service->countForChange_d($parentId) : $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $objArr[0]['id'] = 'noId';
                $objArr[0]['activityName'] = '��Ŀ�ϼ�';
            }
            $rows['footer'] = $objArr;
        }
        //������ֵ
        $rows['rows'] = $arrs;
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ��������
     */
    function c_treeJson()
    {
        $service = $this->service;
        $projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
        if ($projectId) {
            $service->searchArr['projectId'] = $projectId;
        }
        $service->sort = 'c.lft';
        $service->asc = false;
        $arrs = $service->listBySqlId('treelist');

        if (!empty($arrs)) {
            // ������Ŀ������ȷ�Ϸ�ʽȷ����Ŀ���ȼ��ط�ʽ
            $projectDao = new model_engineering_project_esmproject();
            $projectInfo = $projectDao->get_d($projectId);

            switch ($projectInfo['incomeType']) {
                case 'SRQRFS-02' : // ��Ʊ
                    $projectInfo = $projectDao->contractDeal($projectInfo);
                    $projectProcess = $projectInfo['ivnoiceProcess'];
                    break;
                case 'SRQRFS-03' : // ����
                    $projectInfo = $projectDao->contractDeal($projectInfo);
                    $projectProcess = $projectInfo['incomeProcess'];
                    break;
                case 'SRQRFS-01' : // ����
                default :
                    //�����б�����
                    $activityProcessArr = $service->getListProcess_d($projectId);
            }

            //��ȥ_parentId
            foreach ($arrs as $key => $val) {
                // ���ݹ�ʽ���������
                $workloadCount = (strtotime(min(day_date, $val['planEndDate'])) - strtotime($val['planBeginDate'])) / 86400 + 1;
                $arrs[$key]['workloadCount'] = $workloadCount;

                if ($val['_parentId'] == -1) {
                    unset($arrs[$key]['_parentId']);
                }

                //����ƻ������Ѿ��ﵽ����100����100��
                if ($val['planProcess'] > 100) {
                    $arrs[$key]['planProcess'] = $val['planProcess'] = 100;
                }

                if(in_array($projectInfo['incomeType'], array('SRQRFS-02', 'SRQRFS-03')) && isset($projectProcess)) {
                    $arrs[$key]['process'] = $arrs[$key]['countProcess'] = $projectProcess;
                    $arrs[$key]['waitConfirmProcess'] = '0.00';
                    $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $projectProcess, 2);
                } else {
                    //���ش�ȷ�Ͻ��ȡ��ۼƽ��ȡ��ƻ�����
                    if ($val['rgt'] - $val['lft'] == 1) {
                        $arrs[$key]['waitConfirmProcess'] = isset($activityProcessArr[$val['id']]) ? $activityProcessArr[$val['id']]['thisActivityProcess'] : '0.00';
                        $arrs[$key]['countProcess'] = isset($activityProcessArr[$val['id']]) ? bcadd($activityProcessArr[$val['id']]['thisActivityProcess'], $val['process'], 2) : $val['process'];
                        $arrs[$key]['diffProcess'] = bcsub($arrs[$key]['planProcess'], $arrs[$key]['countProcess'], 2);
                    }
                }
            }

            //������Ŀ�ϼ�
            $service->sort = "";
            $service->searchArr = array('projectId' => $projectId);
            $objArr = $service->listBySqlId('count_list');
            if (is_array($objArr)) {
                $objArr[0]['id'] = 'noId';
                $objArr[0]['activityName'] = '��Ŀ�ϼ�';
            }
            $rows['footer'] = $objArr;
        }
        //������ֵ
        $rows['rows'] = $arrs;
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ����
     */
    function c_toTreeViewList()
    {
        $esmProDao = new model_engineering_project_esmproject();
        $outsourcingName = $esmProDao->find(array("id"=>$_GET['projectId']),null,"outsourcingName");

        $isACatWithFallOutsourcing = false;
        $esmprojectDao = new model_engineering_project_esmproject();
        //��������·�����A����Ŀ����
        if($outsourcingName['outsourcingName'] != "����"){
            $this->service->updateCategoryAProcess_d($_GET['projectId']);
        }else{
            $isACatWithFallOutsourcing = $esmprojectDao->isCategoryAProject_d(null, $_GET['projectId']);// A����Ŀ
            if($isACatWithFallOutsourcing){// A��������Ŀ
                $this->service->updateCategoryAProcess_d($_GET['projectId'],true);
            }
        }

        //��Ŀid
        $this->assign('parentId', PARENT_ID);
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('isACatWithFallOutsourcing', ($isACatWithFallOutsourcing? "1" : ""));
        $this->display('treeviewlist');
    }

    /******************************* ��ɾ�Ĳ� *******************************/

    /**
     * ��ת��������Ŀ��Χ(oa_esm_project_activity)ҳ��
     */
    function c_toAdd()
    {
        //������ĿId����ȡ��Ŀ��Ϣ
        $esmproject = $this->service->getObjInfo_d($_GET['projectId']);
        $this->assignFunc($esmproject);

        //ͨ�������changeId�ж��Ƿ��Ǳ��
        $changeId = isset($_GET['changeId']) ? $_GET['changeId'] : '';
        $parentId = $_GET['parentId'];
        $obj = $this->service->getParentObj_d($parentId, $changeId);

        //��ȡ��ǰ�ȼ���ʣ�๤��ռ��
        $thisWorkRate = $this->service->getWorkRateByParentId_d($esmproject['id'], $parentId, $changeId);
        $lastWorkRate = bcsub(100, $thisWorkRate, 2);
        $this->assign('workRate', $lastWorkRate);

        //��������λ��ʼ��
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'));

        if ($parentId == PARENT_ID) {
            $this->assign('parentId', $parentId);
            $this->assign('activityName', "��Ŀ");
            //���ڻ�ȡ
            $this->assign('planBeginDate', $esmproject['planBeginDate']);
            $this->assign('planEndDate', $esmproject['planEndDate']);
            $this->assign('days', $esmproject['expectedDuration']);
        } else {
            $this->assign('parentId', $obj['id']);
            $this->assign('activityName', $obj['activityName']);
            //���ڻ�ȡ
            $this->assign('planBeginDate', $obj['planBeginDate']);
            $this->assign('planEndDate', $obj['planEndDate']);
            $this->assign('days', $obj['days']);
        }
        //changId��Ⱦ
        $this->assign('changeId', $changeId);

        $this->view('add');
    }

    /**
     * �����������
     */
    function c_add()
    {
        $object = $_POST[$this->objName];
        if ($this->service->add_d($object)) {
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
     * ����ת��
     */
    function c_toMove()
    {
        //ͨ�������changeId�ж��Ƿ��Ǳ��
        $changeId = isset($_GET['changeId']) ? $_GET['changeId'] : '';
        $parentId = $_GET['parentId'];
        $obj = $this->service->getParentObj_d($parentId, $changeId);
        $this->assignFunc($obj);

        //��ȡ��ǰ�ȼ���ʣ�๤��ռ��
        $thisWorkRate = $this->service->getWorkRateByParentId_d($obj['projectId'], $parentId, $changeId);
        $lastWorkRate = bcsub(100, $thisWorkRate, 2);
        $this->assign('workRate', $lastWorkRate);

        //��������λ��ʼ��
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $obj['workloadUnit']);

        //���ڵ���Ⱦ
        $this->assign('parentId', $parentId);

        //changId��Ⱦ
        $this->assign('changeId', $changeId);

        $this->view('move');
    }

    /**
     * ����ת��
     */
    function c_move()
    {
        if ($this->service->move_d($_POST[$this->objName])) {
            msgRf('ת�Ƴɹ�');
        } else {
            msgRf('ת��ʧ��');
        }
    }

    /**
     * ��ʼ������
     */
    function c_init()
    {
        $this->service->getUnderTreeIds_d($_GET['id']);
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset($_GET['perm']) && $_GET['perm'] == 'view') {
            $this->view('view');
        } else {
            $this->view('edit');
        }
    }

    /**
     * ��ת���༭��Ŀ��Χҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }

        //��������λ��ʼ��workloadUnit
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $obj['workloadUnit'], true);
        //���û��orgId,������-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('edit');
    }

    /**
     * �޸�������Ŀ�����ҳ��
     */
    function c_toEditTrial()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //���û��orgId,������-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('edittrial');
    }

    /**
     * �޸�������Ŀ�����ҳ��
     */
    function c_toEditTrialChange()
    {
        $this->permCheck(); //��ȫУ��
        $this->service->getParam(array('id' => $_GET['id']));
        $obj = $this->service->list_d('select_change');
        foreach ($obj[0] as $key => $val) {
            $this->assign($key, $val);
        }
        //���û��orgId,������-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('edittrial');
    }

    /**
     * �޸Ķ���
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
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
     * �����������
     */
    function c_toEditWorkloadDone()
    {
        $this->permCheck();//��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        //��������������
        $workloadCount = (strtotime(min(day_date, $obj['planEndDate'])) - strtotime($obj['planBeginDate'])) / 86400 + 1;
        $this->assign('workloadCount', $workloadCount);
        $this->view('editworkloaddone');
    }

    /**
     * �����������
     */
    function c_editWorkloadDone()
    {
        if ($this->service->editWorkloadDone_d($_POST[$this->objName])) {
            msgRf('�༭�ɹ���');
        } else {
            msgRf('�༭ʧ��');
        }
    }

    /**
     * ��ת���鿴��Ŀ��Χҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * �༭�ڵ� (���¼�����)
     */
    function c_toEditNode()
    {
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);

        //��ȡ��ǰ�ȼ���ʣ�๤��ռ��
        $thisWorkRate = $this->service->getWorkRateByParentId_d($obj['projectId'], $obj['parentId']);
        $lastWorkRate = bcadd(bcsub(100, $thisWorkRate, 2), $obj['workRate'], 2);
        $this->assign('canUseWorkRate', $lastWorkRate);

        //���û��orgId,������-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', -1);
        }
        $this->view('node');
    }

    /**
     * ��ת���鿴��Ŀ��Χҳ��
     */
    function c_toViewNode()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('node-view');
    }

    /************************ �������� *******************************/

    /**
     * ��дΨһ��֤
     */
    function c_checkRepeat()
    {
        $checkId = "";
        $service = $this->service;
        if (isset ($_REQUEST ['id'])) {
            $checkId = $_REQUEST ['id'];
            unset ($_REQUEST ['id']);
        }
        if (!isset($_POST['validateError'])) {
            $service->getParam($_REQUEST);
            $isRepeat = $service->isRepeat($service->searchArr, $checkId);
            echo $isRepeat;
        } else {
            //����֤���
            $validateId = $_POST['validateId'];
            $validateValue = $_POST['validateValue'];
            $service->searchArr = array(
                $validateId . "Eq" => $validateValue
            );
            $service->searchArr['projectId'] = $_GET['projectId'];

            $isRepeat = $service->isRepeat($service->searchArr, $checkId);
            $result = array(
                'jsonValidateReturn' => array($_POST['validateId'], $_POST['validateError'])
            );
            if ($isRepeat) {
                $result['jsonValidateReturn'][2] = "false";
            } else {
                $result['jsonValidateReturn'][2] = "true";
            }
            echo util_jsonUtil::encode($result);
        }
    }

    /**
     * ����Ƿ���ڸ��ڵ㣬�����������һ��
     */
    function c_checkParent()
    {
        echo $this->service->checkParent_d() ? 1 : 0;
    }

    /**
     * ������
     */
    function c_getChildren()
    {
        $service = $this->service;

        $sqlKey = isset($_POST['rtParentType']) ? 'select_treelistRtBoolean' : 'select_treelist';

        if (empty($_POST['id'])) {
            $rows = array(array('id' => PARENT_ID, 'code' => 'root', 'name' => '��Ŀ', 'isParent' => 'true'));
        } else {
            $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
            $projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
            $service->searchArr['parentId'] = $parentId;
            if ($projectId) {
                $service->searchArr['projectId'] = $projectId;
            }
            $service->asc = false;
            $rows = $service->listBySqlId($sqlKey);
        }
        echo util_jsonUtil::encode($rows);
    }


    /**
     * ʵʱ�����������
     */
    function c_calTaskProcess()
    {
        //����
        $rs = $this->service->calTaskProcess_d($_POST['id'], $_POST['workload'], $_POST['worklogId']);
        echo $rs ? util_jsonUtil::encode($rs) : 0;
    }

    //add chenrf 20130604
    /**
     * ���㹤��ռ���ܺ�
     */
    function c_workRateCount()
    {
        echo $this->service->workRateCount($_GET['projectId']);
    }

    /**
     * ���㹤��ռ���ܺ� - ����
     */
    function c_workRateCountNew()
    {
        echo util_jsonUtil::encode($this->service->workRateCountNew($_GET['projectId'], $_GET['parentId'], null));
    }
    /********************* ������� ************************/
    /**
     *  ����鿴
     */
    function c_toViewChange()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getChange_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('view');
    }

    /**
     * ����༭
     */
    function c_toEditChange()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getChange_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        //��������λ��ʼ��workloadUnit
        $this->showDatadicts(array('workloadUnit' => 'GCGZLDW'), $obj['workloadUnit'], true);

        //���û��orgId,������-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', 0);
        }
        $this->view('edit');
    }

    /**
     * �༭�ڵ� (���¼�����)
     */
    function c_toEditNodeChange()
    {
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);

        //��ȡ��ǰ�ȼ���ʣ�๤��ռ��
        $thisWorkRate = $this->service->getWorkRateByParentId_d($obj['projectId'], $obj['parentId']);
        $lastWorkRate = bcadd(bcsub(100, $thisWorkRate, 2), $obj['workRate'], 2);
        $this->assign('canUseWorkRate', $lastWorkRate);

        //���û��orgId,������-1
        if (empty($obj['orgId'])) {
            $this->assign('orgId', 0);
        }
        $this->view('node');
    }

    /**
     * ��ת���鿴��Ŀ��Χҳ��
     */
    function c_toViewNodeChange()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getChange_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('node-view');
    }

    /**
     * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
     */
    function c_ajaxdeletes()
    {
        echo $this->service->deletes_d($_POST ['id'], $_POST ['changeId'], $_POST ['projectId']) ? 1 : 0;
    }

    /**
     * ��ͣ����
     */
    function c_stop()
    {
        echo $this->service->stop_d($_POST ['id']) ? 1 : 0;
    }

    /**
     * �ָ�����
     */
    function c_restart()
    {
        echo $this->service->restart_d($_POST ['id']) ? 1 : 0;
    }
}