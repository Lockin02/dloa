<?php
/**
 * @author show
 * @Date 2013��12��9�� 19:17:24
 * @version 1.0
 * @description:�豸ת��������Ʋ�
 */
class controller_engineering_resources_elent extends controller_base_action
{

    function __construct() {
        $this->objName = "elent";
        $this->objPath = "engineering_resources";
        parent::__construct();
    }

    /**
     * �豸ת���������
     */
    function c_page() {
        $this->view('list');
    }

    /**
     * �豸�黹���б�
     */
    function c_pageJson() {
        $service = $this->service;
        $service->getParam($_REQUEST);
        // ����Ȩ�޴���
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['����Ȩ��'];
        if (strpos($deptLimit, ';;') === false) {
            if (empty($deptLimit)) {
                $service->searchArr['deviceDeptIds'] = $_SESSION['DEPT_ID'];
            } else {
                $service->searchArr['deviceDeptIds'] = $deptLimit . ',' . $_SESSION['DEPT_ID'];
            }
        }

        $rows = $service->page_d();
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
     * �豸ת������
     */
    function c_mylist() {
    	$this->assign('receiverId', $_SESSION['USER_ID']);
        $this->view('mylist');
    }

    /**
     * ��ת���豸ת�����뵥
     */
    function c_prolist() {
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->view('prolist');
    }

    /**
     * �豸�黹���б�
     */
    function c_proPageJson(){
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $rows = $service->page_d();
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

    /**
     * �ҵ��豸ת�赥�б�
     */
    function c_mylistJson() {
        $_REQUEST['charger'] = $_SESSION['USER_ID'];
        $service = $this->service;
        $service->getParam($_REQUEST);
        $rows = $service->page_d();

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
     * �豸ת���������
     */
    function c_toAdd() {
        //���������Ŀ��Ϣ
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->assign('projectName', $_GET['projectName']);
        $this->assign('managerId', $_GET['managerId']);
        $this->assign('managerName', $_GET['managerName']);

        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_GET['deviceDeptId']);
        $this->assign('deviceDeptName', $deptInfo['DEPT_NAME']);
        $this->assign('deviceDeptId', $_GET['deviceDeptId']);

        $this->assign('rowsId', $_GET['rowsId']);
        $this->assign('applyUser', $_SESSION['USERNAME']);
        $this->assign('applyUserId', $_SESSION['USER_ID']);
        $this->assign('deptId', $_SESSION['DEPT_ID']);
        $this->assign('deptName', $_SESSION['DEPT_NAME']);
        $this->assign('applyDate', day_date);

        $this->view('add', true);
    }

    /**
     * �����������
     */
    function c_add() {
        $this->checkSubmit();//��֤�Ƿ��ظ��ύ
        $object = $_POST[$this->objName];
        $id = $this->service->add_d($object);
        if ($id) {
            if ($object['status'] == "1") {
                msgRf('�ύ�ɹ�');
                //				succ_show('controller/engineering/resources/ewf_index.php?actTo=ewfSelect&billId=' .$id);
            } else {
                msgRf('����ɹ�');
            }
        }
    }

    /**
     * �豸ת���������
     */
    function c_toEdit() {
        $this->permCheck(); //��ȫУ��
        $service = $this->service;
        $obj = $service->get_d($_GET['id']);
        $this->assignFunc($obj);

        $this->view('edit');
    }

    /**
     * �޸Ķ���
     */
    function c_edit() {
        $object = $_POST[$this->objName];
        $rs = $this->service->edit_d($object);
        if ($rs) {
            if ($object['status'] == "1") {
                msgRf('�ύ�ɹ�');
            } else {
                msgRf('����ɹ�');
            }
        }
    }

    /**
     * �豸ת������鿴
     */
    function c_toView() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('view');
    }

    /**
     * �޸ĵ���״̬
     */
    function c_confirmStatus() {
        echo $this->service->status_d($_POST['id'], $_POST['status']) ? 1 : 0;
    }

    /**
     * ��ת������Աȷ��ת��ҳ��
     */
    function c_toConfirm() {
    	$this->permCheck(); //��ȫУ��
    	$service = $this->service;
    	$obj = $service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    
    	$this->view('confirm');
    }
    
    /**
     * ����Աȷ��ת��
     */
    function c_confirm() {
    	if ($this->service->confirm_d($_POST[$this->objName])) {
    		msgRf('ȷ�ϳɹ�');
    	} else {
    		msgRf('ȷ��ʧ��');
    	}
    }
    
    /**
     * ��ת������������ȷ��ת��ҳ��
     */
    function c_toFinalConfirm() {
    	$this->permCheck(); //��ȫУ��
    	$service = $this->service;
    	$obj = $service->get_d($_GET['id']);
    	$this->assignFunc($obj); 	
    	$this->assign('today', day_date);
    	
    	$this->view('finalconfirm');
    }
    
    /**
     * ����������ȷ��ת��
     */
    function c_finalConfirm() {
    	$object = $_POST[$this->objName];
    	if ($this->service->finalConfirm_d($object)) {
    		msgRf('ȷ�ϳɹ�');
    	} else {
    		msgRf('ȷ��ʧ��');
    	}
    }
}