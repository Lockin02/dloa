<?php

/**
 * @author Show
 * @Date 2011��12��10�� ������ 15:03:50
 * @version 1.0
 * @description:��Ŀ��Դ�ƻ�(oa_esm_project_resources)���Ʋ�
 */
class controller_engineering_resources_esmresources extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmresources";
        $this->objPath = "engineering_resources";
        parent::__construct();
    }

    /*
     * ��ת����Ŀ��Դ�ƻ�(oa_esm_project_resources)
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
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d();
        if (is_array($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //���ر�ҳС��
            $pageAmount = 0;
            //��ҳС�� - ����Ԥ�����
            $useDays = $number = 0;
            foreach ($rows as $val) {
                $pageAmount = bcadd($pageAmount, $val['amount'], 2);
                $useDays = bcadd($useDays, $val['useDays']);
                $number = bcadd($number, $val['number']);
            }
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['amount'] = $pageAmount;
            $rsArr['useDays'] = $useDays;
            $rsArr['number'] = $number;
            $rsArr['resourceTypeName'] = '��ҳС��';
            $rows[] = $rsArr;

            //������Ŀ�ϼ�
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            $objArr = $service->listBySqlId('count_all');
            $rsArr = $objArr[0];
            $rsArr['resourceTypeName'] = '��Ŀ�ϼ�';
            $rsArr['id'] = 'noId';
            $rows[] = $rsArr;
        }

        $arr = array();
        $arr['collection'] = $rows;
        //count()+��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_managePageJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);

        //�ж��Ƿ���ڱ���ļ�¼
        $isChanging = $service->isChanging_d($_POST['projectId'], false);
        if ($isChanging) {
            $rows = $service->page_d('select_change');
        } else {
            $rows = $service->page_d();
        }

        if (is_array($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //���ر�ҳС��
            $pageAmount = 0;
            //��ҳС�� - ����Ԥ�����
            $useDays = $number = 0;
            foreach ($rows as $val) {
                $pageAmount = bcadd($pageAmount, $val['amount'], 2);
                $useDays = bcadd($useDays, $val['useDays']);
                $number = bcadd($number, $val['number']);
            }
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['amount'] = $pageAmount;
            $rsArr['useDays'] = $useDays;
            $rsArr['number'] = $number;
            $rsArr['resourceTypeName'] = '��ҳС��';
            $rows[] = $rsArr;

            //������Ŀ�ϼ�
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            if ($isChanging) {
                $objArr = $service->listBySqlId('count_change');
            } else {
                $objArr = $service->listBySqlId('count_all');
            }
            $rsArr = $objArr[0];
            $rsArr['resourceTypeName'] = '��Ŀ�ϼ�';
            $rsArr['id'] = 'noId';
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
     * ��ȡ��ҳ����ת��Json
     */
    function c_pageJsonOrg()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��ת���鿴Tab��Ŀ��Դ�ƻ�
     */
    function c_toViewList()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('viewlist');
    }

    /*��ת����Ŀ��ԴTab*/
    function c_toProResourceTab()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('proresource-tab');
    }

    /**
     * ��Ŀ�豸����
     */
    function c_projectTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('proTab');
    }

    /**
     * ��Ŀ�豸���루�鿴��
     */
    function c_proViewTab()
    {
        $this->assign('id', $_GET['id']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('proViewTab');
    }

    /**
     * ��ת���鿴��Ŀ�豸Ԥ��(oa_esm_project_resources)
     */
    function c_viewlist()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('view-list');
    }

    /**
     * �б�鿴ҳ�� - Ĭ��
     */
    function c_toViewPage()
    {
        //��ȡ��Ŀ����
        $activityArr = $this->service->getActivityArr_d($_GET['projectId']);
        $str = $this->service->initViewPage_d($activityArr);

        $this->assign('list', $str);
        $this->view('view-page');
    }

    /*******************���뵼������**********************************/

    /**
     * ����excel
     */
    function c_toEportExcelIn()
    {
        //��Ŀ������Ⱦ
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //��ȡ������Ⱦ
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
        $this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);

        $this->display('excelin');
    }

    /**
     * ����excel
     */
    function c_eportExcelIn()
    {
        $projectId = $_POST['projectId'];
        $activityId = $_POST['activityId'];

        $resultArr = $this->service->addExecelData_d($projectId, $activityId);

        $title = '��Ŀ�������б�';
        $thead = array('������Ϣ', '������');
        echo util_excelUtil::showResult($resultArr, $title, $thead);
    }
    /*******************���뵼������**********************************/


    /******************************* ���ܲ��� *********************************/

    /**
     * ��ת��Tab����ҳ��
     */
    function c_toAdd()
    {
        //��Ŀ������Ⱦ
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //��ȡ������Ⱦ
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
        $this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);

        $this->showDatadicts(array('resourceTypeCode' => 'ZYLX'));
        $this->view('add');
    }

    //��ĿԤ����������
    function c_toBatchAdd()
    {
        //��Ŀ������Ⱦ
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //��ȡ������Ⱦ
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
//		$this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);
        $this->assign('activityName', $activityRs['activityName']);

        $this->view('addbatch');
    }

    /**
     * ������Դ�ƻ� ������������
     */
    function c_addBatch()
    {
        $rs = $this->service->batchAdd_d($_POST[$this->objName]);
        if ($rs) {
            //�жϴ��޸��Ƿ����ڱ��
            $esmprojectDao = new model_engineering_project_esmproject();
            if ($esmprojectDao->actionIsChange_d($rs[0]['projectId'])) {
                msg('����ɹ������ڡ������¼����Ŀ�ύ����');
            } else {
                msg('����ɹ�');
            }
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * ��ʼ������
     */
    function c_init()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);

        if ($obj['planBeginDate'] == '0000-00-00') $obj['planBeginDate'] = null;
        if ($obj['planEndDate'] == '0000-00-00') $obj['planEndDate'] = null;
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            $TypeOne = $this->getDataNameByCode($obj['resourceTypeCode']);
            $this->assign('resourceTypeCode', $TypeOne);
            $this->assign('resourceNature', $this->getDataNameByCode($obj['resourceNature']));
            $this->view('view');
        } else {
            $this->showDatadicts(array('resourceTypeCode' => 'ZYLX'), $obj['resourceTypeCode']);
            $this->showDatadicts(array('resourceNature' => 'GCXMZYXZ'), $obj['resourceNature']);
            $this->view('edit');
        }
    }

    /**
     * ��ʼ������
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->showDatadicts(array('resourceTypeCode' => 'ZYLX'), $obj['resourceTypeCode']);
        $this->showDatadicts(array('resourceNature' => 'GCXMZYXZ'), $obj['resourceNature']);
        $this->assign('orgId', '-1');
        $this->view('edit');
    }

    /**
     * �޸Ķ���
     * @throws Exception
     */
    function c_edit()
    {
        $this->checkSubmit(); //�ظ��ύ
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
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
     * ��ʼ������
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $TypeOne = $this->getDataNameByCode($obj['resourceTypeCode']);
        $this->assign('resourceTypeCode', $TypeOne);
        $this->assign('resourceNature', $this->getDataNameByCode($obj['resourceNature']));
        $this->view('view');
    }

    /**
     * ������Դ�ƻ�
     */
    function c_toCopy()
    {
        $this->assign('ids', $_GET['ids']);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('copy');
    }

    /**
     * ������Դ�ƻ� ����ҳ������
     */
    function c_toCopylistJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->searchArr['ids'] = $_GET['ids'];
        $service->asc = false;
        $rows = $service->list_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ��֤���ʱ�����豸����
     */
    function c_checkHasResourceInAct()
    {
        $rs = $this->service->find(array('activityId' => $_POST['activityId']), null, 'id');
        if (is_array($rs)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_viewListJson()
    {
        $service = $this->service;
        if (isset($_POST['activityId']) && $_POST['activityId'] == -1) {
            unset($_POST['activityId']);
        } else {
            $_POST['activityIds'] = $this->service->getUnderTreeIds_d($_POST['activityId'], $_POST['lft'], $_POST['rgt']);
            unset($_POST['activityId']);
            unset($_POST['lft']);
            unset($_POST['rgt']);
        }
        $service->getParam($_REQUEST);
        $rows = $service->pageBySqlId("select_viewlist");
        if (is_array($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //���ر�ҳС��
            $pageAmount = 0;
            //��ҳС�� - ����Ԥ�����
            $useDays = $number = 0;
            foreach ($rows as $val) {
                $pageAmount = bcadd($pageAmount, $val['amount'], 2);
                $useDays = bcadd($useDays, $val['useDays']);
                $number = bcadd($number, $val['number']);
            }
            $rsArr = array();
            $rsArr['id'] = 'noId';
            $rsArr['amount'] = $pageAmount;
            $rsArr['useDays'] = $useDays;
            $rsArr['number'] = $number;
            $rsArr['resourceTypeName'] = '��ҳС��';
            $rows[] = $rsArr;

            //������Ŀ�ϼ�
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            $objArr = $service->listBySqlId('count_all');
            $rsArr = $objArr[0];
            $rsArr['resourceTypeName'] = '��Ŀ�ϼ�';
            $rsArr['id'] = 'noId';
            $rows[] = $rsArr;
        }
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        $arr['advSql'] = $service->advSql;
        $arr['listSql'] = $service->listSql;
        echo util_jsonUtil::encode($arr);
    }

    /********************* begin ��Դ�ƻ����� ***********************/

    /**
     * �б� - ������Դ����
     */
    function c_pageJsonEquDeal()
    {
        $service = $this->service;
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $service->searchArr['resourceNature'] = 'GCXMZYXZ-02';
        $rows = $service->page_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        $arr = array();
        $arr['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
        $arr['page'] = $service->page;
        echo util_jsonUtil::encode($arr);
    }

    /**
     * ��Ŀ�豸��Դ���� - ��ϸ����ҳ��
     */
    function c_dealEquResources()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->view('dealequresource');
    }

    /**
     * ��Ŀ����ҳ��
     */
    function c_resourcesTree()
    {
        if (isset($_POST['id'])) {
            $this->service->getParam($_REQUEST);
            $this->service->groupBy = 'c.resourceCode';
            $this->service->sort = 'c.resourceCode';
            $this->service->asc = false;
            $rs = $this->service->list_d('select_tree');
        } else {
            $rs = array(array('id' => PARENT_ID, 'code' => 'root', 'name' => '��Ŀ', 'isParent' => 'true'));
        }
        echo util_jsonUtil :: encode($rs);
    }

    /**
     * ��Ŀ��ϸ����ҳ��
     */
    function c_toDeal()
    {
        $this->assign('ids', $_GET['ids']);
        $this->view('deal');
    }

    /**
     * ����������
     */
    function c_dealBatch()
    {
        if ($this->service->dealBatch_d($_POST[$this->objName])) {
            msg('����ɹ�');
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * ����������� - ������Ŀ
     */
    function c_dealComplated()
    {
        if ($this->service->dealComplated_d($_POST['projectId'])) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /************************** end ��Դ�ƻ����� *************************/

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
        $this->view('edit');
    }

    /*
     * ajax��ʽ����ɾ������Ӧ�ðѳɹ���־����Ϣ���أ�
     */
    function c_ajaxdeletes()
    {
        //$this->permDelCheck ();
        $id = $_POST['id'];
        $changeId = $_POST['changeId'];
        $projectId = $_POST['projectId'];
        try {
            $this->service->deletes_d($id, $changeId, $projectId);
            echo 1;
        } catch (Exception $e) {
            echo 0;
        }
    }

    /**
     * ������Ŀ�ķ���
     */
    function c_updateOldData()
    {
        $this->service->updateOldData_d();
        echo 'update complated';
    }

    /**
     * ��ȡ��Ŀ�ķ�Χid
     */
    function c_getRangeId()
    {
        echo $this->service->getRangeId_d($_POST['projectId']);
    }
}