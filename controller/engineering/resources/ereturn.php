<?php
/**
 * @author xyt
 * @Date 2013��12��14�� ������ 14:23:17
 * @version 1.0
 * @description:��Ŀ�豸�黹����Ʋ�
 */
class controller_engineering_resources_ereturn extends controller_base_action {

	function __construct() {
		$this->objName = "ereturn";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}
	
	/**
	 * �豸�黹���б�
	 */
	function c_page(){
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
	 * ��Ŀ�豸�黹�б�������Ŀid���ˣ�
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
	 * ��ӹ黹��
	 */
	function c_toAdd(){
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select());//��������
		$this->assign('rowsId', $_GET['rowsId']);
		$this->assign('applyUser', $_SESSION['USERNAME']);
		$this->assign('applyUserId', $_SESSION['USER_ID']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('applyDate', day_date);

        $deptDao = new model_deptuser_dept_dept();
        $deptInfo = $deptDao->getDeptName_d($_GET['deviceDeptId']);
        $this->assign('deviceDeptName', $deptInfo['DEPT_NAME']);
        $this->assign('deviceDeptId', $_GET['deviceDeptId']);
        $this->assign('projectId', $_GET['projectId']);
        $this->assign('projectCode', $_GET['projectCode']);
        $this->assign('projectName', $_GET['projectName']);
        $this->assign('managerId', $_GET['managerId']);
        $this->assign('managerName', $_GET['managerName']);

        $this->view('add', true);
	}
	
	/**
	 * ��дadd����
	 */
	function c_add(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		if ($this->service->add_d($_POST)) {
			if($object['status'] == "1"){
				msgRf('�ύ�ɹ�');
			}else{
				msgRf('����ɹ�');
			}
		}
	}
	
	/**
	 * �༭�黹��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		$area = new includes_class_global();
		$this->show->assign('area_select',$area->area_select($obj['areaId']));//��������
		$this->assignFunc($obj);
		$this->view('edit', true);
	}
	
	/**
	 * �༭�黹��
	 */
	function c_edit(){
        $this->checkSubmit();
		$object = $_POST[$this->objName];
		if (!empty($object['areaId'])) {
			$object['areaName'] = $this->service->findArea($object['areaId']);
		}	
		if ($this->service->edit_d($object,true)) {
			if($object['status'] == "1"){
				msgRf('�ύ�ɹ�');
			}else{
				msgRf('����ɹ�');
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
	 * ȷ��״̬
	 */
	function c_confirmStatus(){
        echo $this->service->status_d($_POST['id'],$_POST['status']) ? 1 : 0;
	}
    
    /**
     * ��ת������Աȷ�Ϲ黹ҳ��
     */
    function c_toConfirm() {
    	$this->permCheck(); //��ȫУ��
    	$service = $this->service;
    	$obj = $service->get_d($_GET['id']);
    	$this->assignFunc($obj);
    
    	$this->view('confirm');
    }
    
    /**
     * ����Աȷ�Ϲ黹
     */
    function c_confirm() {
    	if ($this->service->confirm_d($_POST[$this->objName])) {
    		msgRf('ȷ�ϳɹ�');
    	} else {
    		msgRf('ȷ��ʧ��');
    	}
    }
}