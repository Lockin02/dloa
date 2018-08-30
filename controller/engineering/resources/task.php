<?php
/**
 * @author Administrator
 * @Date 2012-11-14 11:05:47
 * @version 1.0
 * @description:��Ŀ�豸���񵥿��Ʋ�
 */
class controller_engineering_resources_task extends controller_base_action {

	function __construct() {
		$this->objName = "task";
		$this->objPath = "engineering_resources";
		parent :: __construct();
	}

	/**
	 * ��ת����Ŀ�豸�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = array();
		//��������Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);

		if(isset($sysLimit['�豸����Ȩ��']) && !empty($sysLimit['�豸����Ȩ��'])){
			if(strpos($sysLimit['�豸����Ȩ��'],';;') === false){
				$_REQUEST['areaIdArr'] = $sysLimit['�豸����Ȩ��'];
			}
			$service->getParam ( $_REQUEST );
			$rows = $service->page_d ();
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows ( $rows );
		}
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
	 * ��ת��������Ŀ�豸����ҳ��
	 */
	function c_toAdd() {
		//�豸���뵥��Ϣ
		$resourceapplyDao = new model_engineering_resources_resourceapply();
		$resourceapplyObj = $resourceapplyDao->get_d($_GET['id']);
		//������Ⱦ
		$this->assignFunc($resourceapplyObj);
		$this->assign("taskManager", $_SESSION['USERNAME']);
		$this->assign("taskManagerId", $_SESSION['USER_ID']);
		$this->showDatadicts(array('applyType' => 'GCSBSQ'));
		//��ȡϵͳĬ�ϲ���Ա����  
		$areaName = $this->service->get_table_fields('area','ID='.$_SESSION['AREA'],'Name');
		$this->assign("areaId", $_SESSION['AREA']);
		$this->assign("areaName", $areaName);
		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		if ($this->service->add_d($_POST[$this->objName])) {
			msgRf('�´�ɹ�');
		}
	}

	/**
	 * ��ת���༭��Ŀ�豸����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		if ($this->service->edit_d($_POST[$this->objName])) {
			msgRf('�༭�ɹ���');
		}
	}

	/**
	 * ��ת���鿴��Ŀ�豸����ҳ��
	 */
	function c_toView() {
		$id = $_GET['id'];
		$obj = $this->service->get_d($id);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}
	/**
	 * ��ӡ
	 */
	function c_toBatchPrintAlone(){
		//id��
		$ids = null;	
		if(isset($_GET['id'])&&!empty($_GET['id'])){
			$ids = $_GET['id'];
			$idArr = explode(',',$ids);
		}else{
			msg("������ѡ��һ�ŵ��ݴ�ӡ");
		}
	
		$this->view('batchprinthead');
	
		foreach($idArr as $val){
			$id = is_array($val) ? $val['id'] : $val;
			$obj = $this->service->get_d($id);
			foreach ($obj as $key => $val) {
				$this->assign($key, $val);
			}
			$this->display('print-expand');
		}
		$this->assign('ids',$ids);
		$this->assign('allNum',count($idArr));
		$this->display('batchprintalone');
	}

    /**
     * ����
     */
    function c_toOutStock(){
        $obj = $this->service->get_d($_GET['id']);
        $this->assignFunc($obj);
        $this->view('outstock');
    }

    /**
     * ѡ������豸
     */
    function c_outStockConfirm(){
        //����ѡ�ϵ��豸��Ϣ
        $obj = $this->service->initInfo_d($_POST[$this->objName]);
        if(!empty($obj)){
        	$this->assignFunc($obj);
        	//�ʼ�Ĭ�Ͻ�����Ϊ������,������,��Ŀ����
        	$this->assign('sendUserId', implode(',',array_unique(explode(',', $obj['applyUserId'].','.$obj['receiverId'].','.$obj['managerId']))));
        	$this->assign('sendUserName', implode(',',array_unique(explode(',', $obj['applyUser'].','.$obj['receiverName'].','.$obj['managerName']))));
        	$this->view('outstockconfirm', true);
        }else{
        	msgRf('û����Ҫ������豸');
        }
    }

    /**
     * ȷ�����ճ��ⵥ
     */
    function c_outStockFinal(){
    	$this->checkSubmit();//��֤�Ƿ��ظ��ύ
        if($this->service->outStockFinal_d($_POST[$this->objName])){
            msgRf('����ɹ�');
        }
    }
    
    /**
     * ��ת���޸ķ��������������
     */
    function c_toEditNumber() {
    	$this->permCheck(); //��ȫУ��
    	$obj = $this->service->get_d($_GET['id']);
    	foreach ($obj as $key => $val) {
    		$this->assign($key, $val);
    	}
    	$this->view('editNumber');
    }
    
    /**
     * �޸ķ��������������
     */
    function c_editNumber() {
    	if ($this->service->editNumber_d($_POST[$this->objName])) {
    		msgRf('�ύ�ɹ���');
    	}else{
    		msgRf('�ύʧ�ܣ�');
    	}
    }
}