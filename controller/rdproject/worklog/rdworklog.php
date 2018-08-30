<?php
/*
 * Created on 2010-9-15
 * ��־
 */
class controller_rdproject_worklog_rdworklog extends controller_base_action{
	function __construct() {
		$this->objName = "rdworklog";
		$this->objPath = "rdproject_worklog";
		parent::__construct ();
	}
	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ


		//$service->asc = false;
		$rows = $service->page_d ('base_list');
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
	 * ��д����
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) );
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ) );
		$this->showDatadicts ( array ('status' => 'XMRWZT' ) );
		$this->assign('executionDate',date("Y-m-d"));
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}

	/**
	 *  ������־ID��ȡ��־�б�
	 */
	function c_logList(){
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr['weekId'] = $_GET['id'];
		$projectId = isset($_GET['projectId']) ?  $_GET['projectId'] : null;

		if(!empty($projectId)){
			$service->searchArr['projectId'] = $projectId;
		}

		$service->sort = 'executionDate';
		$rows = $service->page_d ();
		$this->pageShowAssign();
		$this->show->assign ( 'weekId', $_GET['weekId'] );
		$this->show->assign ( 'projectId', $projectId );
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}

	/**
	 * �ҵĹ�����־
	 */
	function c_myLogList(){
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
//		$service->searchArr['weekId'] = $_GET['id'];

		if(isset($_GET['projectId'])){
			$service->searchArr['projectId'] = $_GET['projectId'];
		}

		$service->sort = 'executionDate';
		$rows = $service->page_d ();
		$this->pageShowAssign();
		$this->show->assign ( 'weekId', $_GET['weekId'] );
		$this->show->assign ( 'list', $service->showlist ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-mylist' );
	}

	/**
	 * ��д��ʼ��
	 */
	function c_init(){
		$worklogrows = $this->service->get_d($_GET['id']);
		$object = $this->service->getDateInTask($worklogrows['taskId']);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
		foreach ( $worklogrows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) );
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ) );
		$this->showDatadicts ( array ('status' => 'XMRWZT' ) );
		$this->show->assign ( 'id', $_GET['id'] );
		$this->show->display( $this->objPath .'_' .$this->objName .'-edit' );
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object)) {
			msg ( '�༭�ɹ���' );
		}else{
			msg ( '�༭ʧ�ܣ�' );
		}
	}

	/**
	 * ������־��ϸ
	 */
	function c_viewWorkLog(){
		$object = $this->service->getBase ( $_GET ['id'] );

		$taskobject = $object['rdtask'];
		unset($object['rdtask']);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$taskobject['priority'] = $this->getDataNameByCode($taskobject['priority']);
		$taskobject['status'] = $this->getDataNameByCode($taskobject['status']);
		$taskobject['taskType'] = $this->getDataNameByCode($taskobject['taskType']);
		foreach ( $taskobject as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
//		print_r($taskobject);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

	/**
	 * ������־��ϸ
	 */
	function c_viewWorkLogByIdAndDate(){
		$condition = array ('taskId' => $_GET ['taskId'] , 'executionDate' => $_GET['executionDate'] ,'createId'=>$_GET['createId']);
		$rs = $this->service->findCount($condition);
		if($rs != 1){
			$this->c_worklogResultList($condition);
			exit();
		}

		$object = $this->service->getInfoByTaskAndDate ( $_GET ['taskId'],$_GET['executionDate'],$_GET['createId']);

		$taskobject = $object['rdtask'];
		unset($object['rdtask']);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$taskobject['priority'] = $this->getDataNameByCode($taskobject['priority']);
		$taskobject['status'] = $this->getDataNameByCode($taskobject['status']);
		$taskobject['taskType'] = $this->getDataNameByCode($taskobject['taskType']);
		foreach ( $taskobject as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
//		print_r($taskobject);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-view' );
	}

	/**
	 * ��־����б�
	 */
	function c_worklogResultList($object){
		$this->assignFunc($object);
		$this->view('worklogresultlist');
	}

	/**
	 * �ŶӸ����Ǳ���
	 */
	function c_teamDashBoard(){
		$this->assign('member', $_SESSION['USERNAME']);
    	$this->assign('memberId', $_SESSION['USER_ID']);
    	$this->assign('flag' , 'flag');
		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashboard' );
	}


	/**
	 * �����Ǳ���
	 */
	function c_dashBoard(){
		$this->assign('member', $_SESSION['USERNAME']);
    	$this->assign('memberId', $_SESSION['USER_ID']);
		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashboard' );
	}

	/**
	 * �����Ǳ���-���
	 */
	function c_dashBoradResult(){

		$service = $this->service;

		foreach ( $_GET as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$rows = $service->getDashResults ( $_GET );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}

		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashboardresult' );
	}

	/**
	 * �����Ǳ��� - �������ֲ�ͼ
	 */
	function c_showLoadCharts(){
		$rows = $this->service->loadSpread($_GET['beginDate'],$_GET['overDate'],$_GET['memberId']);
		$this->show->assign( 'result',$this->service->showChartsLoadSpead($rows,'��Ŀ�������ֲ�ͼ','����Ŀ����')  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * �����Ǳ��� - ���������ֲ�ͼ
	 */
	function c_showLoadByTask(){
		$rows = $this->service->loadSpreadByTask($_GET['beginDate'],$_GET['overDate'],$_GET['memberId']);
		$this->show->assign( 'result',$this->service->showChartsLoadSpead($rows,'���������ֲ�ͼ')  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * ����ID����ȡ��־�б�
	 */
	function c_dashDetail(){
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->searchArr['ids'] = $_GET['ids'];

		$rows = $service->page_d ();
		$this->pageShowAssign();

		$this->show->assign ( 'list', $service->showlistInWindow ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-dashdetail' );
	}

	/**
	 * ���һ����־�������б���
	 */
	function c_addInTask(){
		$object = $this->service->getDateInTask($_GET['id']);
//		print_r($object);
		foreach ( $object as $key => $val ) {
			$this->show->assign ( "task".$key, $val );
		}
		$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) );
		$this->showDatadicts ( array ('priority' => 'XMRMYXJ' ) );
		$this->showDatadicts ( array ('status' => 'XMRWZT' ) );
		$this->show->display( $this->objPath .'_' .$this->objName .'-addintask' );
	}

	/**
	 * ������ϸ-ִ���ձ�
	 */
	function c_worklogInTask(){
		$service = $this->service;
		$service->getParam ( $_GET ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$rows = $service->worklogInTask ($_GET['id']);
		$this->pageShowAssign();

		$this->show->assign ( 'taskId', $_GET['id'] );
		$this->show->assign ( 'jsUrl', $_GET['jsUrl'] );
		$this->show->assign ( 'list', $service->showLogInTask ( $rows ) );
		$this->show->display ( $this->objPath . '_' . $this->objName . '-worklogInTask' );
	}

	/**
	 * ��ԱͶ�빤����͸��ͼ-ͼ��
	 */
	function c_worklogCharts(){
		$rows = $this->service->getWorklogByUserGPid($_GET['pjId']);
		$this->show->assign( 'result',$this->service->worklogCharts($rows)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * ��Ա������͸��ͼ-���
	 */
	function c_worklogTable(){
		$all = $this->service->getAllWorklogByPjId($_GET['pjId']);
		$rows = $this->service->getWorklogByUserGPid($_GET['pjId']);
		$this->show->assign( 'result',$this->service->worklogTable($rows,$all)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/************��Ŀ���ͳ����ԱͶ�빤����******By LiuB 2011��7��27��9:26:31********begin*********/

	/**
	 * ��ԱͶ�빤����͸��ͼ-ͼ��
	 */
	function c_GroupWorklogCharts(){
		$rdNum = $this->service->getGroup_d ($_GET['gpId']);
       	 $arr = array();
			foreach ( $rdNum as $key => $val){
			$arr[$key] = $val['id'];

			}
	         $arr = implode(",",$arr);
	         if(empty ($arr)){
	            $rows = $arr;
	         }else
		       $rows = $this->service->GroupWorklogByUserGPid($arr);
		$this->show->assign( 'result',$this->service->worklogCharts($rows)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}

	/**
	 * ��Ա������͸��ͼ-���
	 */
	function c_GroupWorklogTable(){
		$rdNum = $this->service->getGroup_d ($_GET['gpId']);
       	 $arr = array();
			foreach ( $rdNum as $key => $val){
			$arr[$key] = $val['id'];

			}
	         $arr = implode(",",$arr);
	         if(empty ($arr)){
	            $rows = $arr;
	         }else{
                $rows = $this->service->GroupWorklogByUserGPid($arr);
                $all = $this->service->GroupAllWorklogByPjId($arr);
                $rows = $this->service->GroupWorklogByUserGPid($arr);
	         }


		$this->show->assign( 'result',$this->service->worklogTable($rows,$all)  );
		$this->show->display( $this->objPath .'_' .$this->objName .'-planChartsView' );
	}
	/************��Ŀ���ͳ����ԱͶ�빤����******By LiuB 2011��7��27��9:26:31*******end**********/

	/**
	 * ��־��ѯ���
	 */
	function c_resultList(){
		$this->assignFunc($_GET);
		$this->view('resultlist');
	}
}
?>
