<?php

/**
 * @author Show
 * @Date 2011��12��3�� ������ 14:17:32
 * @version 1.0
 * @description:��Ŀ������뵥(oa_esm_change_baseinfo)���Ʋ�
 */
class controller_engineering_change_esmchange extends controller_base_action
{

    function __construct()
    {
        $this->objName = "esmchange";
        $this->objPath = "engineering_change";
        parent::__construct();
    }

    /**
     * ��ת����Ŀ������뵥
     */
    function c_page()
    {
        $this->view('list');
    }

    /************************** ��Ŀ��� *********************************/
    /**
     * TODO��Ŀ���
     *
     */
    function c_toAdd()
    {
        $projectId = $_GET['projectId'];
        $projectObj = $this->service->getProjectForChange_d($projectId);
        $this->assignFunc($projectObj);

        $this->showDatadicts(array('outsourcing' => 'WBLX'), $projectObj['outsourcing'], true);//�������
        $this->showDatadicts(array('outsourcingType' => 'GCWBLX'), $projectObj['outsourcingType'], true);//�����ʽ

        $this->view('add');
    }

    /**
     * �����������
     */
    function c_add()
    {
        $object = $_POST[$this->objName];
        $id = $this->service->add_d($_POST[$this->objName]);
        if ($id) {
            if ($_GET['act']) {
                //��ȡ��Ӧʡ�ݵķ�Χid
                $rangeId = $this->service->getRangeId_d($object['projectId']);
                succ_show('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&billArea=' . $rangeId);
            } else {
                msg('����ɹ�');
            }
        } else {
            msg('����ʧ��');
        }
    }

    /**
     * �༭ҳ��
     */
    function c_toEdit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getViewInfo_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('edit');
    }

    /**
     * �޸Ķ���
     */
    function c_edit()
    {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            if ($_GET['act']) {
                //��ȡ��Ӧʡ�ݵķ�Χid
                $rangeId = $this->service->getRangeId_d($object['projectId']);
                succ_show('controller/engineering/change/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&billArea=' . $rangeId);
            } else {
                msg('�༭�ɹ�');
            }
        } else {
            msg('�༭ʧ�ܣ�');
        }
    }

    /**
     * ��ʼ������ - ����
     */
    function c_init()
    {
        if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
            $this->permCheck(); //��ȫУ��
            $obj = $this->service->getViewInfo_d($_GET['id']);
            $this->assignFunc($obj);
            $this->view('view');
        } else {
            $this->permCheck(); //��ȫУ��
            $obj = $this->service->getViewInfo_d($_GET['id']);
            $this->assignFunc($obj);
            $this->view('edit');
        }
    }

    /**
     * �鿴ҳ��
     */
    function c_toView()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getViewInfo_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('view');
    }

    /**
     * �����鿴ҳ��
     */
    function c_viewAudit()
    {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->getViewInfo_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('viewaudit');
    }

    /**
     * ��Ŀ����б�
     */
    function c_pageForProject()
    {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('isManager', $this->service->isManager_d($_GET['projectId']));
        $projectInfo = $this->service->getObjInfo_d($_GET['projectId']);
        $this->assign('estimates', $projectInfo['estimates']);
        $this->display('listforproject');
    }

    /**
     * �ж���Ŀ�Ƿ��Ѿ��ж�Ӧ�ı������
     */
    function c_hasChangeInfo()
    {
        echo $this->service->hasChangeInfo_d($_POST['projectId']);
    }

    /**
     * ������ɺ�����
     */
    function c_dealAfterAudit()
    {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }

    /**
     * ajax���õı������
     */
    function c_ajaxChange()
    {
        echo $this->service->setChange_d($_POST['id']) ? 1 : 0;
    }

    /**
     * ��֤������Ŀʵʩ���ڼ�Ԥ���Ƿ񳬳�ԭPK����ʱ������
     */
    function c_isPKOverproof()
    {
        $service = $this->service;

        $object = $service->get_d($_POST['id']);
        //��ȡ��Ӧҵ����Ϣ
        $esmprojectDao = new model_engineering_project_esmproject();
        $sTrialprojectDao = new model_engineering_project_strategy_sTrialproject();
        $rs = $esmprojectDao->find(array('id' => $object['projectId']), null, 'contractId');
        $robj = $sTrialprojectDao->getRawInfo_i($rs['contractId']);
        if (strtotime($object['planBeginDate']) < strtotime($robj['beginDate']) || strtotime($object['planEndDate']) > strtotime($robj['closeDate'])) {
            echo 1;    //ʵʩ���ڲ��Ϸ�
        } elseif ($object['newBudgetAll'] > $robj['affirmMoney']) {
            echo 2; //Ԥ�㲻�Ϸ�
        }
    }
}