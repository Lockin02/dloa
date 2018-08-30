<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 15:09:09
 * @version 1.0
 * @description:�������񵥿��Ʋ�
 */
class controller_produce_quality_qualitytask extends controller_base_action {

	function __construct() {
		$this->objName = "qualitytask";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * ��ת���ʼ�����Tab
	 */
	function c_toPageTab() {
		$this->view ( 'list-tab' );
	}


    /**
     * ��ת���ʼ�����Tab
     */
    function c_toTaskReportTab() {
        $this->assign('sourceId',isset($_GET['sourceId'])?$_GET['sourceId']:"");
        $this->view ( 'taskreport-tab' );
    }


    /**
	 * ��ת�����������б�
	 */
	function c_page() {
		$this->assign('acceptStatusArr',$_GET['acceptStatusArr']);
		$this->view('list');
	}

    /**
     * ��ת���ʼ������б����ϲ鿴��
     */
    function c_arrivalPage() {
        $this->assign('sourceId',$_GET['sourceId']);
        $this->view('arrival-list');
    }

	/**
	 * ��ת���ʼ�����Tab
	 */
	function c_toMyTab() {
		$this->assign("userId",$_SESSION['USER_ID']);
		$this->assign("relDocType",isset($_GET['relDocType']) ? $_GET['relDocType'] : '');
		$this->view ( 'mylist-tab' );
	}

	/**
	 * ��ת��������������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit();
		if ($this->service->add_d( $_POST[$this->objName] )) {
			msg ('�´�ɹ�');
		}
	}

	/**
	 * ��ת���༭��������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴��������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//״̬��Ⱦ
		$this->assign("acceptStatusName",$this->service->rtStatus($obj['acceptStatus']));
		$this->view ( 'view' );
	}

	/**
	 * �鿴ҳ�� - �ʼ촦��
	 */
	function c_toTaskDeal(){
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//״̬��Ⱦ
		$this->assign("acceptStatusName",$this->service->rtStatus($obj['acceptStatus']));
		$this->view ( 'taskdeal' );
	}

	/**
	 * ��ת���´�����ҳ��
	 */
	function c_toIssued(){
		$this->assign('applyId',$_GET['applyId']);
		$this->assign('itemId',$_GET['itemId']);
		$this->view ( 'issued' );
	}

	/**
	 * ��������
	 */
	function c_acceptTask(){
        echo $this->service->acceptTask_d( $_GET['id'] ) ? 1 : 0;
	}

	/**
	 * ajax�´�����
	 */
	function c_ajaxTask(){
		if(empty($_GET['itemId'])){
			echo 0;
		}else{
            $docType = isset($_GET['docType'])? $_GET['docType'] : '';
            if($docType == 'ZJSQDLBF'){
                echo util_jsonUtil::encode ( $this->service->ajaxTaskForDLBF( $_GET['itemId'] ) );
            }else{
                echo $this->service->ajaxTask( $_GET['itemId'] ) ? 1 : 0;
            }
        }
	}
	
	/**
	 * �ʼ챨����ʾ�б�
	 */
	function c_pageJsonDetail(){
		$service = $this->service;
		$service->getParam( $_REQUEST );
		$rows = $service->page_d( 'select_detail' );
		$rows = $this->sconfig->md5Rows( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת���ʼ�����
	 */
	function c_taskList(){
		$this->view ( 'listTask' );
	}
}