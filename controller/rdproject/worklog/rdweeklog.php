<?php
/*
 * Created on 2010-9-16
 * ��־
 */
class controller_rdproject_worklog_rdweeklog extends controller_base_action{
	function __construct() {
		$this->objName = "rdweeklog";
		$this->objPath = "rdproject_worklog";
		parent::__construct ();
	}

	/**
	 * �������־-������־
	 */
	function c_page() {
		global $func_limit; //��ȡ��ǰ��½�û�ӵ�к���������
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * �ҵ���־
	 */
	function c_myWeeklog(){
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->searchArr['user_id'] = $_SESSION['USER_ID'];

		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showmylist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-myweeklog' );
	}

	/**
	 * �鿴�ܱ�-��д
	 */
	function c_read(){
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-read' );
	}

	/**
	 * �鿴�ܱ�-ֻ��
	 */
	function c_view(){
		$rows = $this->service->get_d ( $_GET ['id'] ,'view');
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}


	/**
	 * �鿴�����ձ�����ӱ�ע
	 */
	function c_addremark(){
		$rows = $this->service->getBase ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName .'-addremark');
	}

	/**
	 * ������־
	 */
	function c_editLog(){
		$object = $_POST [$this->objName];
		if ($this->service->editLog ( $object )) {
			showmsg ( '����ɹ�' );
		} else {
			showmsg ( '����ʧ��' );
		}
	}

	/**
	 * ��ѯ��־
	 */
	function c_searchLog(){
		$this->show->display( $this->objPath . '_' .$this->objName . '-searchlog');
	}

	/**
	 * ��ѯ��־�����ʾҳ
	 */
	function c_searchlogresult(){
		$service = $this->service;
		$object = array();
		$_GET = array_filter($_GET);
		//���б������ֵ
		if(isset($_GET['w_projectId'])){
			$object['w_projectId'] = $_GET['w_projectId'];
		}
		//���б������ֵ
		if(isset($_GET['departmentIds'])){
			if($_GET['departmentIds'] == 'ALL_DEPT'){
				unset($_GET['departmentIds']);
			}
		}
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$rows = $service->getSearchList ();
//		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showResultList ( $rows ,$object) );
		$this->show->display( $this->objPath . '_' .$this->objName . '-resultlist');
	}

	/**
	 * ��ѯ��־�����ʾҳ
	 */
	function c_searchworklogresult(){
		$this->assignFunc($_GET);
		$this->view('resultlist-worklog');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonWorklogResult() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ('select_inner');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/************************** ��ѯ��־  ��Ŀ���� ���� **************************/
	/**
	 * ��ѯ��־
	 */
	function c_searchLogForManeger(){
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->show->display( $this->objPath . '_' .$this->objName . '-searchlogformanager');
	}

	/**
	 * ����SQL����Ҫʹ�� in �� ֵ���д���
	 */
	function dealStr($str){
		$str = substr($str,0,-1);
		$arr = explode(',',$str);
		$returnstr = null;
		foreach($arr as $val){
			$returnstr .= '"'.$val .'",';
		}
		$returnstr = substr($returnstr,0,-1);
		return $returnstr;
	}

	/**
	 * ��־��������
	 */
	function c_logReport(){
		$this->show->display( $this->objPath .'_' . $this->objName .'-logreport');
	}

	/**
	 * ��־�����µĲ�ѯ��־
	 */
	function c_searchalong(){
		$this->show->display( $this->objPath . '_' .$this->objName .'-searchalong');
	}

	/**
	 * ������־
	 */
	function c_subordinateLog(){
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->getSubordinateLog();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display( $this->objPath .'_'.$this->objName .'-subordinatelog');
	}

	/**
	 * �ҵ���Ŀ-����Ŀ����ʾ�ܱ�
	 */
	function c_listInProject() {
		$pjId = isset ( $_GET ['pjId'] ) ? $_GET ['pjId'] : exit ();
		$proCenter = isset ( $_GET ['proCenter'] ) ? $_GET ['proCenter'] : 0;
		$service = $this->service;
		$service->searchArr ['projectId'] = $_GET ['pjId'];
		if( isset($_GET ['createName']) ){
			$service->searchArr ['createName1'] = $_GET ['createName'];
		}
		if( isset($_GET['depName']) ){
			$service->searchArr ['depName1'] = $_GET['depName'];
		}
		$service->groupBy = 'w.id';
		$rows = $service->pageBySqlId('view_inproject');
		$this->pageShowAssign();

		if($proCenter){
			$pjId .= "&proCenter=1";
		}
		$this->show->assign ( 'projectId',$pjId );
		$this->show->assign ( 'jsUrl',$_GET ['jsUrl'] );
		$this->show->assign ( 'list', $service->showResultList ( $rows,array('w_projectId' => $_GET ['pjId']) ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-listinproject' );
	}
}
?>
