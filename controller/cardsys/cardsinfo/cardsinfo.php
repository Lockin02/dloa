<?php
/**
 * @author Show
 * @Date 2012��1��5�� ������ 10:00:48
 * @version 1.0
 * @description:���Կ���Ϣ(oa_cardsys_cardsinfo)���Ʋ�
 */
class controller_cardsys_cardsinfo_cardsinfo extends controller_base_action {

	function __construct() {
		$this->objName = "cardsinfo";
		$this->objPath = "cardsys_cardsinfo";
		parent::__construct ();
	 }

	/*
	 * ��ת�����Կ���Ϣ(oa_cardsys_cardsinfo)�б�
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * ���˲��Կ��б�
     */
	function c_myList(){
		$this->view('mylist');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_myPageJson() {
		$service = $this->service;

		$_POST['createId'] = $_SESSION['USER_ID'];
		$service->getParam ( $_POST );

		//$service->asc = false;
		$rows = $service->page_d ();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		echo util_jsonUtil::encode ( $arr );
	}

    /*
	 * ��ת�����Կ�ʹ�ü�¼�б�
	 */
    function c_pageForProject() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
    }

    /*
	 * ��ת�����Կ�ʹ�ü�¼�б�
	 */
    function c_pageForProjectView() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforprojectview');
    }

    /**
	 * ��ת���������Կ���Ϣ(oa_cardsys_cardsinfo)ҳ��
	 */
	function c_toAdd() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('userName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);
		$this->showDatadicts ( array ('cardType' => 'GCXMCSK' ));
     	$this->view ( 'add' );
	}

	/**
	 * ��ת���༭���Կ���Ϣ(oa_cardsys_cardsinfo)ҳ��
	 */
	function c_toEdit() {
   		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('cardType' => 'GCXMCSK' ),$obj['cardType']);
		$this->view ( 'edit');
	}

   	/**
	 * ��ת���鿴���Կ���Ϣ(oa_cardsys_cardsinfo)ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

    /**
     * ��ת�ͷŲ��Կ�
     */
    function c_toReleaseCar(){
    	$this->assign ( 'ownerId', $_SESSION['USER_ID'] );
		$this->view ( 'releasecar' );
    }

	 /**
	  * �ͷŲ��Կ�����
	  */
     function c_releaseCar(){
    	$objs = $_POST [$this->objName];
    	$obj = $objs['release'];
		if ($this->service->releaseCar_d ($obj)) {
			msg ( '�༭�ɹ���' );
		}

	}
}
?>