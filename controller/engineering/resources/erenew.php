<?php
/**
 * @author show
 * @Date 2013��12��9�� 19:17:43
 * @version 1.0
 * @description:�������뵥���Ʋ� 
 */
class controller_engineering_resources_erenew extends controller_base_action {
	function __construct() {
		$this->objName = "erenew";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	}
	
	/**
	 * ��ת���������뵥
	 */
	function c_page() {
		$this->view('list');
	}

    /**
     * �豸�黹���б�
     */
    function c_pageJson(){
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        // ����Ȩ�޴���
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        $deptLimit = $sysLimit['����Ȩ��'];
        if(strpos($deptLimit,';;') === false){
            if(empty($deptLimit)){
                $service->searchArr['deviceDeptIds'] = $_SESSION['DEPT_ID'];
            }else{
                $service->searchArr['deviceDeptIds'] = $deptLimit.','.$_SESSION['DEPT_ID'];
            }
        }

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
	 * �ҵ��豸�黹�б�
	 */
	function c_tomylist(){
		$this->view('mylist');
	}
	
	/**
	 * ��Ŀ�豸�����б�������Ŀid���ˣ�
	 */
	function c_prolist(){
		$this->assign('projectId',$_GET['projectId']);
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
	 * �ҵ��豸�黹�б�
	 */
	function c_mylistJson(){
		$_REQUEST['charger'] = $_SESSION['USER_ID'];
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
	 * ������赥
	 */
	function c_toAdd(){
		//flagΪ��ʶ,1Ϊ�ڽ�/�ﱸ״̬,2Ϊ�ر�״̬
		$flag = $_GET['flag'];
		//�ڽ�״̬����Ŀ����֤���Ƿ�Ϊ������Ŀ
		if($flag == '1'){
			$esmprojectDao = new model_engineering_project_esmproject();
			if($esmprojectDao->isPK_d($_GET['projectId'])){
				$flag = '3';//��ʶΪ������Ŀ
			}
		}
		$this->assign('flag', $flag);
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
	 * ��дadd����
	 */
	function c_add(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		if($this->service->add_d($object)) {
			if($object['status'] == "1"){
				msg('�ύ�ɹ�');
			}
			else
				msg('����ɹ�');
		}
		else
			msg('����ʧ��');
	}

	/**
	 * �༭���赥ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$service = $this->service;
		$obj = $service->get_d($_GET['id']);
		$flag = '';//flagΪ��ʶ,1Ϊ�ڽ�/�ﱸ״̬,2Ϊ�ر�״̬
		if(!empty($obj['projectId'])){
			$flag = $service->get_table_fields('project_info','projectId='.$obj['projectId'],'flag');
			//�ڽ�/�ﱸ״̬����Ŀ����֤���Ƿ�Ϊ������Ŀ
			if($flag == '1'){
				$esmprojectDao = new model_engineering_project_esmproject();
				if($esmprojectDao->isPK_d($obj['projectId'])){
					$flag = '3';//��ʶΪ������Ŀ
				}
			}
		}
		$this->assign('flag', $flag);
		$this->assignFunc($obj);
		$this->view('edit', true);
	}

	/**
	 * �༭���赥
	 */
	function c_edit(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		$rs = $this->service->edit_d($object,true);	
		if ($rs) {
			if($object['status'] == "1"){
				msg('�ύ�ɹ�');
			}else{
				msg('����ɹ�');
			}
		}
	}
	
	/**
	 * �鿴ҳ��
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
	function c_confirmStatus(){
        echo $this->service->status_d($_POST['id'],$_POST['status']) ? 1 : 0;
	}
    
    /**
     * ��ת������Աȷ������ҳ��
     */
    function c_toConfirm() {
    	$this->permCheck(); //��ȫУ��
    	$service = $this->service;
    	$obj = $service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    
    	$this->view('confirm');
    }
    
    /**
     * ����Աȷ������
     */
    function c_confirm() {
    	if ($this->service->confirm_d($_POST[$this->objName])) {
    		msgRf('ȷ�ϳɹ�');
    	} else {
    		msgRf('ȷ��ʧ��');
    	}
    }
    
    /**
     * ��ת������������ȷ������ҳ��
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
     * ����������ȷ������
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