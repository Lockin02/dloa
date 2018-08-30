<?php
/**
 *
 * �ʲ��ɹ�������Ʋ���
 * @author fengxw
 *
 */
class controller_asset_purchase_task_task extends controller_base_action {

	function __construct() {
		$this->objName = "task";
		$this->objPath = "asset_purchase_task";
		parent::__construct ();
	}

	/*
	 * ��ת���ʲ��ɹ�����
	 */
    function c_page() {
      $this->view("list");
    }

    /**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$applyId=isset($_GET['id'])?$_GET['id']:"";
		$this->assign ( 'sendId', $_SESSION ['USER_ID'] );
		$this->assign ( 'sendName', $_SESSION ['USERNAME'] );

		$this->assign('applyId',$applyId);
		$this->assign('sendTime', date("Y-m-d"));
		$this->view ( 'add' );

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
			$this->view ( 'view' );
		} else {
			$this->view ( 'edit' );
		}
	}

	/**
	 * ��ת�����˲ɹ�������Ϣ�б�
	 */
	function c_pageMyList() {
		$this->assign('userId', $_SESSION['USER_ID']);
		$this->view('list-myTs');
	}

	/**
	 * �ı䵥��״̬
 	 */
	function c_submit() {
		try {
			$id = isset ($_GET['id']) ? $_GET['id'] : false;
			$object=array("id"=>$id,"state"=>"�ѽ���","acceptDate"=>date("Y-m-d"));
			$this->service->updateById($object);
			echo 1;
		} catch (Exception $e) {
			throw $e;
			echo 0;
		}
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '�´�����ɹ���';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
	}
}

?>