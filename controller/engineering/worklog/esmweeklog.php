<?php
/**
 * @author Show
 * @Date 2011��12��14�� ������ 10:00:57
 * @version 1.0
 * @description:�ܱ�(oa_esm_weeklog)���Ʋ�
 */
class controller_engineering_worklog_esmweeklog extends controller_base_action {

	function __construct() {
		$this->objName = "esmweeklog";
		$this->objPath = "engineering_worklog";
		parent::__construct ();
	}

	/*
	 * ��ת���ܱ�Tab
	 */
	function c_page() {
		$this->view ( 'listTab' );
	}

	/*
	 * ��ת���ܱ�(oa_esm_weeklog)
	*/
	function c_pageTab() {

		//����Ȩ��
		$otherDataDao = new model_common_otherdatas();
		$sysLimit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$deptLimit = $sysLimit['����Ȩ��'];
		$deptIds = null;
		if(!strstr($deptLimit,';;')){
			if(empty($deptLimit)){
				$deptIds = $_SESSION['DEPT_ID'];
			}else{
				$deptIds = $deptLimit.','.$_SESSION['DEPT_ID'];
			}
		}
		$this->assign('deptIds',$deptIds);
		$this->view ( 'list' );
	}

	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->assign ( 'subStatus', $this->service->rtSubStatus ( $obj ['subStatus'] ) );
			$this->show->assign ( "exaResults", $this->getDataNameByCode ( $obj ['exaResults'] ) );

	        //����ָ�겿��
	        $indexInfo = $this->service->initAuditIndexView_d($obj);
	        $this->assign('indexTbl',$indexInfo);

			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * ���ݴ������Ϣ��ȡ������־
	 */
	function c_myWeeklog() {
		$this->view ( 'mylist' );
	}

	/**
	 *
	 * ���˹�����־tabҳ
	 */
	function c_myWeeklogTab() {
		$obj = $this->service->get_d ( $_GET ['id'] );

		$this->view ( 'mylist-tab' );
	}

	/**
	 * �����ܱ�����
	 */
	function c_myPageJson() {
		$service = $this->service;

		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr ['createId'] = $_SESSION ['USER_ID'];

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 *
	 * ��ת���ύ�ܱ�ҳ��
	 */
	function c_toSubmitLog() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( "submit" );
	}

	/**
	 *
	 * �ύ�ܱ�
	 */
	function c_submitLog() {
		$service = $this->service;
		$object = $_POST [$this->objName];
		if ($service->submitLog_d ( $object)) {
			echo "<script>alert('�ύ�ɹ�!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('�ύʧ��!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 *
	 * ȷ�������־
	 */
	function c_toAuditLog() {
		$this->assign ( "loginId", $_SESSION ['USER_ID'] );
		$this->view ( "audit-list" );
	}

	/**
	 * ȷ������ܱ�ҳ��
	 */
	function c_toAudit() {
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('thisDate',day_date);
		$this->showDatadicts ( array ('exaResults' => 'ZBKHJZ' ) );

        //����ָ�겿��
        $indexInfo = $this->service->initAuditIndex_d($obj);
        $this->assign('indexTbl',$indexInfo);

		$this->view ( "audit" );
	}

	/**
	 *
	 * ȷ�������־
	 */
	function c_auditLog() {
		$service = $this->service;
		$object = $_POST [$this->objName];
		if ($service->auditLog_d ( $object,true )) {
			echo "<script>alert('���˳ɹ�!');window.opener.window.show_page();window.close();</script>";
		} else {
			echo "<script>alert('����ʧ��!');window.opener.window.show_page();window.close();</script>";
		}
	}

	/**
	 * ֱ�Ӵ���ܱ�
	 */
	function c_backLog(){
		$service = $this->service;
		$id = $_POST['id'];
		if ($service->backLog_d ( $id )) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * �ж��ܱ��Ƿ�ɼ���¼����־
	 */
	function c_checkLogCanWrite(){
        $executionDate = $_POST ['executionDate'];
        $this->service->searchArr = array( 'worklogDate' =>  $executionDate ,'createId' => $_SESSION['USER_ID'] , 'subStatus' => 'YQR');
        $rs = $this->service->list_d();
        if ($rs)
            echo 0; //������ȷ���ܱ� ����¼
        else
            echo 1; //��¼
        exit();
	}

	/**
	 * ��ת��excel����ҳ��
	 */
	function c_toExportExcel(){
		$this->assignFunc($_GET);
		$this->view('outexcel');
	}
	/**
	 * excel����
	 */
	function c_exportExcel(){
		set_time_limit(0);
		$rows = $this->service->getExcelInfo_d($_POST[$this->objName]);
		model_engineering_util_esmexcelutil::exportWeeklogInfo($rows);
	}

	/******************** �������
	/**
	 * �����Ա�ܱ� - tab
	 */
	function c_toSuppUserLogTab(){
		$this->view ( 'suppuserlog-tab' );
	}

	/**
	 * �����Ա�ܱ�
	 */
	function c_toSuppUserLog(){
		$this->view ( 'suppuserlog-list' );
	}

	/**
	 * �����Ա�ܱ�
	 */
	function c_suppUserLogJson(){
		$service = $this->service;

		$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$personnelDao = new model_outsourcing_supplier_personnel();
		$userStr = $personnelDao->getPersonIdList($_SESSION ['USER_ID']);
		$service->searchArr ['createIdArr'] = $userStr;

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
}