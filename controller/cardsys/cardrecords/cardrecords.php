<?php
/**
 * @author Show
 * @Date 2012��1��5�� ������ 16:06:21
 * @version 1.0
 * @description:���Կ�ʹ�ü�¼���Ʋ�
 */
class controller_cardsys_cardrecords_cardrecords extends controller_base_action {

	function __construct() {
		$this->objName = "cardrecords";
		$this->objPath = "cardsys_cardrecords";
		parent::__construct ();
	}

	/*
	 * ��ת�����Կ�ʹ�ü�¼�б�
	 */
    function c_page() {
		$this->view('list');
    }

    /*
	 * ��ת�����Կ�ʹ�ü�¼�б�
	 */
    function c_pageForProject() {
    	$this->assign('projectId',$_GET['projectId']);
		$this->view('listforproject');
    }

   /**
	 * ��ת���������Կ�ʹ�ü�¼ҳ��
	 */
	function c_toAdd() {
		$this->assign('userId',$_SESSION['USER_ID']);
		$this->assign('userName',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);
		$this->view ( 'add' );
	}

    /**
     * ��־�������Կ���Ϣ
     */
    function c_toAddInWorklog(){
        $worklogId = $_GET['worklogId'];
        //��ȡ��־�е�������Ϣ
        $worklogObj = $this->service->getWorklog_d($worklogId);
        $this->assignFunc($worklogObj);
        $this->assign('worklogId',$worklogId);

        $this->view('addinworklog');
    }

    /**
     * ��־�������Կ���Ϣ
     */
    function c_addInWorklog(){
        $object = $_POST[$this->objName];
        $countMoney = $this->service->addBatch_d($object);
        if($countMoney){
            echo "<script>alert('����ɹ�');if(window.opener){window.opener.returnValue = $countMoney;}window.returnValue = $countMoney;window.close();</script>";
        }else{
            echo "<script>alert('����ʧ��');window.close();</script>";
        }
        exit();
    }

   /**
	 * ��ת���༭���Կ�ʹ�ü�¼ҳ��
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
	 * ��ת���鿴���Կ�ʹ�ü�¼ҳ��
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
	 * ��ȡ���Կ�ʹ�ü�¼���ݷ���json
	 */
	function c_listJsonForWeeklog() {
		$service = $this->service;
		$service->getParam ( $_POST );
		$service->sort = 'c.useDate';
		$service->asc = false;

		$rows = $this->service->list_d('select_forweeklog');
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��֤��Ӧ���Կ��Ƿ��Ѵ���ʹ�ü�¼
	 */
	function c_hasRecords(){
		$cardId = $_POST['cardId'];
		$rs = $this->service->find(array('cardId' => $cardId),null,'id');
		if(is_array($rs)){
			echo 1;
		}else{
			echo 0;
		}
	}
}
?>