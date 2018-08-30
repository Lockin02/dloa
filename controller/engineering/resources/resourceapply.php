<?php

/**
 * @author Show
 * @Date 2012��11��7�� ������ 19:23:17
 * @version 1.0
 * @description:��Ŀ�豸�������Ʋ�
 */
class controller_engineering_resources_resourceapply extends controller_base_action
{

    function __construct() {
        $this->objName = "resourceapply";
        $this->objPath = "engineering_resources";
        parent:: __construct();
    }

    /**
     * ��ת����Ŀ�豸������б�
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * ��ȷ���豸����
     */
    function c_waitConfirm() {
        $this->view('listwaitconfirm');
    }

    /**
     * �豸����tabҳ
     */
    function c_toPageTab() {
        $this->view('tab');
    }

    /**
     * ��ת����Ŀ�豸������б�(������Ŀid����)
     */
    function c_prolist() {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('prolist');
    }

    /**
     * �豸�黹���б�
     */
    function c_proPageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();
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

    /**
     * �����б�(������Ŀ����)
     */
    function c_mylist() {
        $this->view('mylist');
    }

    /**
     * ��ȡ��ҳ����ת��Json
     */
    function c_myJson() {
        $service = $this->service;

        $_REQUEST['charger'] = $_SESSION['USER_ID'];
        $service->getParam($_REQUEST);

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
     * ��ת��������Ŀ�豸�����ҳ��
     */
    function c_toAdd() {
        $this->assign('applyUser', $_SESSION['USERNAME']);
        $this->assign('applyUserId', $_SESSION['USER_ID']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('applyDate', day_date);
        $this->showDatadicts(array('applyType' => 'GCSBSQ'));
        $this->showDatadicts(array('getType' => 'GCSBLY'));
        //��ȡ���������ڽ���豸����
        $rs = $this->service->getBorrowDeviceNum($_SESSION['USER_ID']);
        $this->assign('borrowDeviceNum', $rs[0]['borrowDeviceNum']);

        $this->view('add',true);
    }

    /**
     * �����������
     */
    function c_add() {
    	$this->checkSubmit(); //�����Ƿ��ظ��ύ
        $object = $_POST[$this->objName];
        if ($this->service->add_d($object)) {
            if ($object['audit'] == "1") {
                msgRf('�ύ�ɹ�');
            } else {
                msgRf('����ɹ�');
            }
        }else{
        	if ($object['audit'] == "1") {
        		msgRf('�ύʧ��');
        	} else {
        		msgRf('����ʧ��');
        	}
        }
    }

    /**
     * ��ת���༭��Ŀ�豸�����ҳ��
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->service->asc = false;
        $this->showDatadicts(array('applyType' => 'GCSBSQ'), $obj['applyType']);
        $this->showDatadicts(array('getType' => 'GCSBLY'), $obj['getType']);
        $this->assign('proCode', $obj['proCode']);
        //��ȡ���������ڽ���豸����
        $rs = $this->service->getBorrowDeviceNum($obj['applyUserId']);
        $this->assign('borrowDeviceNum', $rs[0]['borrowDeviceNum']);
        
        $this->view('edit');
    }

    /**
     * �޸Ķ���
     */
    function c_edit() {
        $object = $_POST[$this->objName];
        if ($this->service->edit_d($object)) {
            if ($object['audit'] == "1") {
                msgRf('�ύ�ɹ�');
            } else {
                msgRf('����ɹ�');
            }
        }else{
        	if ($object['audit'] == "1") {     	
        		msgRf('�ύʧ��');
        	} else {
        		msgRf('����ʧ��');
        	}
        }
    }

    /**
     * �豸����ȷ��
     */
    function c_editConfirm() {
        $object = $_POST[$this->objName];
        if ($this->service->editConfirm_d($object)) {
            if ($object['audit'] == "1") {
                msgRf('ȷ�ϳɹ�');
            } else {
                msgRf('����ɹ�');
            }
        }else{
        	if ($object['audit'] == "1") {     	
        		msgRf('ȷ��ʧ��');
        	} else {
        		msgRf('����ʧ��');
        	}
        }
    }

    /**
     * ��ת���鿴��Ŀ�豸�����ҳ��
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('view');
    }

    /**
     * ��ת���鿴��Ŀ�豸�����ҳ��
     */
    function c_toAudit() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('viewaudit');
    }

    /**
     * ȷ�Ͻ���
     */
    function c_toEditCheck() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->showDatadicts(array('applyType' => 'GCSBSQ'), $obj['applyType']);
        $this->showDatadicts(array('getType' => 'GCSBLY'), $obj['getType']);

        $this->view('editcheck');
    }

    /**
     * ����ȷ��
     */
    function c_toConfirmDetail() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('confirmdetail');
    }

    /**
     * ����ȷ��
     */
    function c_confirmDetail() {
        if ($this->service->confirmDetail_d($_POST[$this->objName])) {
            msgRf('ȷ�ϳɹ�');
        }
    }

    /**
     * �ύ����ȷ��
     */
    function c_ajaxConfirmStatus() {
        $id = $_POST['id'];
        $confirmStatus = $_POST['confirmStatus'];
        if ($this->service->confirmStatus_d($id, $confirmStatus)) {
            $this->service->sendDefaultEmail_d($id);//����Ĭ�Ϸ������ʼ�
            //��¼������־
            $logDao = new model_engineering_baseinfo_resourceapplylog();
            $logDao->addLog_d($id, '�ύ����');

            echo 1;
        }
    }

    /**
     * ��֤�������Ƿ���
     */
    function c_checkIsEsmDept() {
        $deptId = $_POST['deptId'];
        include(WEB_TOR . 'includes/config.php');
        $defaultEsmDept = isset($defaultEsmDept) ? array_keys($defaultEsmDept) : array();
        if (!in_array($deptId, $defaultEsmDept)) {
            $defaultEsmResourceDept = isset($defaultEsmResourceDept) ? array_keys($defaultEsmResourceDept) : array();
            echo array_pop($defaultEsmResourceDept);
        }
        exit();
    }

    /**
     * ���ؼ��(����)
     */
    function c_checkBack() {
        if ($this->service->update(array('id' => $_POST['id']), array('confirmStatus' => 0))) {
            //��¼������־
            $logDao = new model_engineering_baseinfo_resourceapplylog();
            $logDao->addLog_d($_POST['id'], '���ؼ��');
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * ���
     */
    function c_applyBack() {
        if ($this->service->applyBack_d($_POST['id'])) {
            echo 1;
        } else {
            echo 0;
        }
    }
    
    /**
     * ��ת��ȷ�Ϸ��������������
     */
    function c_toConfirmTaskNum() {
    	$this->permCheck(); //��ȫУ��
    	$obj = $this->service->get_d($_GET['id']);
    	foreach ($obj as $key => $val) {
    		$this->assign($key, $val);
    	}
    	$this->view('confirmTaskNum');
    }
    
    /**
     * ȷ�Ϸ��������������
     */
    function c_confirmTaskNum() {
    	if ($this->service->confirmTaskNum_d($_POST[$this->objName])) {
    		msgRf('ȷ�ϳɹ���');
    	} else {
    		msgRf('ȷ��ʧ�ܣ�');
    	}
    }
    
    /**
     * ת������ȷ��ҳ��
     */
    function c_toConfirmBack() {
    	$this->permCheck(); //��ȫУ��
    	$obj = $this->service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    	$this->view('confirmback');
    }
    
    /**
     * ����ȷ��
     */
    function c_confirmBack() {
    	if ($this->service->confirmBack_d($_POST[$this->objName])) {
    		msgRf('ȷ�ϳɹ�');
    	}
    }
    /*************************  ������ɺ���ת���� *************************/

    /**
     * ������ɺ���ת����
     */
    function c_dealAfterAudit() {
        $this->service->dealAfterAudit_d($_GET['spid']);
        succ_show('?model=common_workflow_workflow&action=auditingList');
    }
}