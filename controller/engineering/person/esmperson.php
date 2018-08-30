<?php

/**
 * @author Show
 * @Date 2012��6��18�� ����һ 17:35:04
 * @version 1.0
 * @description:��Ŀ����Ԥ����Ʋ�
 */
class controller_engineering_person_esmperson extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmperson";
        $this->objPath = "engineering_person";
        parent:: __construct();
    }

    /********************* �б��� *********************************/

    /**
     * ��ת����Ŀ����Ԥ��
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
        if (isset($_POST['activityId']) && $_POST['activityId'] == -1) {
            unset($_POST['activityId']);
        } else {
            $_POST['activityIds'] = $this->service->getUnderTreeIds_d($_POST['activityId'], $_POST['lft'], $_POST['rgt']);
            unset($_POST['activityId']);
            unset($_POST['lft']);
            unset($_POST['rgt']);
        }
        $service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ

        $rows = $service->page_d();

        if (is_array($rows)) {
            //���ݼ��밲ȫ��
            $rows = $this->sconfig->md5Rows($rows);

            //��ҳС�� - ����Ԥ�����
            $rsArr = array('id' => 'noId', 'days' => 0, 'personDays' => 0, 'personCost' => 0, 'number' => 0, 'personLevel' => '��ҳС��');
            foreach ($rows as $val) {
                $rsArr['days'] = bcadd($rsArr['days'], $val['days']);
                $rsArr['personDays'] = bcadd($rsArr['personDays'], $val['personDays']);
                $rsArr['personCostDays'] = bcadd($rsArr['personCostDays'], $val['personCostDays']);
                $rsArr['personCost'] = bcadd($rsArr['personCost'], $val['personCost'], 2);
                $rsArr['number'] = bcadd($rsArr['number'], $val['number']);
            }
            $rows[] = $rsArr;

            //������Ŀ�ϼ�
            $service->sort = "";
            $service->searchArr = array('projectId' => $_POST['projectId']);
            $objArr = $service->listBySqlId('count_all');
            if (is_array($objArr)) {
                $rsArr = $objArr[0];
                $rsArr['personLevel'] = '��Ŀ�ϼ�';
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
     * ����Ԥ��༭�б�
     */
    function c_toEditList()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $project = $this->service->getObjInfo_d($_GET['projectId']);
        $this->assign('ExaStatus', $project['ExaStatus']);
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list');
    }

    /**
     * ����Ԥ��鿴�б�
     */
    function c_toViewList()
    {
        $parentId = isset($_POST['id']) ? $_POST['id'] : PARENT_ID;
        $this->assign('parentId', $parentId);
        $this->assign('projectId', $_GET['projectId']);
        $this->view('list-view');
    }

    /**
     * �б�鿴ҳ�� - Ĭ��
     */
    function c_toViewPage()
    {
        //��Ŀid
        $projectId = $_GET['projectId'];
        //��ȡ��Ŀ����
        $activityArr = $this->service->getActivityArr_d($projectId);
        $str = $this->service->initViewPage_d($activityArr);

        $this->assign('list', $str);
        $this->view('view-page');
    }

    /**
     * �����Ա�б�
     */
    function c_taskListJson()
    {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $service->asc = false;
        $rows = $service->list_d('select_person');
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }
    /************************ ��ɾ�Ĳ� **************************/

    /**
     * ��ת��������Ŀ����Ԥ��
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

        $this->view('add');
    }

    /**
     * ��ĿԤ����������
     */
    function c_toAddBatch()
    {
        //��Ŀ������Ⱦ
        $rs = $this->service->getEsmprojectInfo_d($_GET['projectId']);
        $this->assignFunc($rs);
        $this->assign('projectId', $_GET['projectId']);

        //��ȡ������Ⱦ
        $activityRs = $this->service->getActivityInfo_d($_GET['activityId']);
        $this->assignFunc($activityRs);
        $this->assign('activityId', $_GET['activityId']);

        $this->view('addbatch');
    }

    /**
     * ��������
     */
    function c_addBatch()
    {
        if ($this->service->addBatch_d($_POST[$this->objName])) {
            msg($_POST["msg"] ? $_POST["msg"] : '��ӳɹ���');
        }
    }

    /**
     * ��ת���༭��Ŀ����Ԥ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        $this->view('edit');
    }

    /**
     * ��ת���鿴��Ŀ����Ԥ��
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
     * ���Ʒ���Ԥ�� - ҳ����Ⱦ
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
        $service->searchArr ['ids'] = $_GET['ids'];
        $service->asc = false;
        $rows = $service->list_d();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows($rows);
        echo util_jsonUtil::encode($rows);
    }

    /**
     * ����Ԥ��
     */
    function c_copy()
    {
        $rows = $_POST ["esmEditArr"]; //������Ϣ
        $act = $_POST [$this->objName]; //����λ��
        foreach ($rows as $key => $val) {
            $rows[$key]['activityId'] = $act['activityId'];
            $rows[$key]['activityName'] = $act['activityName'];
        }
        $this->service->saveDelBatch($rows);
        $msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
        msg($msg);
    }

    /**
     * ������������
     */
    function c_updateSalary()
    {
        $rs = $this->service->updateSalary_d($_POST['thisYear'], $_POST['thisMonth']);
        if ($rs) {
            exit(util_jsonUtil::iconvGB2UTF($rs));
        } else {
            exit(0);
        }
    }
}