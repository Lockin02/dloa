<?php
/**
 * @author Administrator
 * @Date 2012-09-25 09:54:07
 * @version 1.0
 * @description:���۱������Ʋ�
 */
class controller_projectmanagent_stockup_stockup extends controller_base_action {

	function __construct() {
		$this->objName = "stockup";
		$this->objPath = "projectmanagent_stockup";
		parent::__construct ();
	 }

	/*
	 * ��ת�����۱����б�
	 */
    function c_page() {
      $this->view('list');
    }

    /**
     * �ҵ����۱����б�
     */
    function c_mystockupList(){
    	$this->assign("userId" , $_SESSION['USER_ID']);
        $this->view("mystockupList");
    }

   /**
	 * ��ת���������۱���ҳ��
	 */
	function c_toAdd() {
	 //��ȡԴ�����ͣ�id
	 $sourceType = isset($_GET['sourceType'])?$_GET['sourceType']:null;
	 $sourceId = isset($_GET['sourceId'])?$_GET['sourceId']:null;
	 $this->assign("sourceType",$sourceType);
	 $this->assign("sourceId",$sourceId);
	 $this->assign("type","XSBH");
     $this->view ( 'add' );
   }

   /**
	 * �����������
	 */
	function c_add() {
		$rows = $_POST [$this->objName];
		$id = $this->service->add_d ($rows);
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
        //�ж��Ƿ�ֱ���ύ����
		if ($id && $_GET ['act'] == "app") {
			succ_show ( 'controller/projectmanagent/stockup/ewf_stockup.php?actTo=ewfSelect&billId=' . $id );
		}else{
			msgRF ( $msg );
		}
		//$this->listDataDict();
	}

   /**
	 * ��ת���༭���۱���ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴���۱���ҳ��
	 */
	function c_toView() {
        $this->permCheck (); //��ȫУ��
        $viewType = isset ( $_GET ['viewType'] ) ? $_GET ['viewType'] : "";
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('viewType',$viewType);
		$this->view ( 'view');
   }
 }
?>