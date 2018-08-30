<?php
/**
 * @author Admin
 * @Date 2014��1��16�� 13:36:19
 * @version 1.0
 * @description:�����Ӧ�̹�����ȷ�ϵ����Ʋ�
 */
class controller_outsourcing_workverify_suppVerify extends controller_base_action {

	function __construct() {
		$this->objName = "suppVerify";
		$this->objPath = "outsourcing_workverify";
		parent::__construct ();
	 }

	/**
	 * ��ת�������Ӧ�̹�����ȷ�ϵ��б�
	 */
    function c_page() {
      $this->view('list');
    }

    	/**
	 * ��ת��������ȷ�ϵ��б�
	 */
    function c_toSuppList() {
		$this->assign('createId', $_SESSION['USER_ID']);
      $this->view('supp-list');
    }

   /**
	 * ��ת�����������Ӧ�̹�����ȷ�ϵ�ҳ��
	 */
	function c_toAdd() {
		$this->assign('formDate',date("Y-m-d"));
		$this->assign('createId', $_SESSION['USER_ID']);
		$this->assign('createName', $_SESSION['USERNAME']);
	     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�����Ӧ�̹�����ȷ�ϵ�ҳ��
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
	 * ��ת���鿴�����Ӧ�̹�����ȷ�ϵ�ҳ��
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
	 * ��ȡ�����ڵĹ�����json
	 */
	function c_worklogListJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$esmworklogDao=new model_engineering_worklog_esmworklog();
		$rows=$esmworklogDao->getTimeLogForSupp_d($_POST['beginDate'],$_POST['endDate']);
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

   	 /**
	 *����
	 *
	 */
	 function c_add(){
		$addType = isset ($_GET['addType']) ? $_GET['addType'] : null;
		if($addType=='submit'){
			$_POST[$this->objName]['status']=1;
		}
		$id=$this->service->add_d($_POST[$this->objName]);
		if($id){
			if($addType!=''){
				msg ( '�ύ�ɹ���' );
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			if($addType!=''){
				msg ( '�ύʧ�ܣ�' );
			}else{
				msg ( '����ʧ�ܣ�' );
			}
		}
	 }

	 /**
	 *����
	 *
	 */
	 function c_edit(){
		$addType = isset ($_GET['addType']) ? $_GET['addType'] : null;
		if($addType=='submit'){
			$_POST[$this->objName]['status']=1;
		}
//		echo "<pre>";
//		print_r($_POST[$this->objName]);
		$id=$this->service->edit_d($_POST[$this->objName]);
		if($id){
			if($addType!=''){
				msg ( '�ύ�ɹ���' );
			}else{
				msg ( '����ɹ���' );
			}
		}else{
			if($addType!=''){
				msg ( '�ύʧ�ܣ�' );
			}else{
				msg ( '����ʧ�ܣ�' );
			}
		}
	 }
	/**
	 * �ı�״̬(ajax)
	 *
	 */
	 function c_changeState(){
	 	$flag=$this->service->changeState_d($_POST);
	 	if($flag){
	 		echo 1;
	 	}else{
	 		echo 0;
	 	}
	 }

   /**
    * ֱ���ύ
    */
	function c_changeStatus() {
		$arr = $this->service->update(array('id'=>$_POST['id']) ,array('status'=>'1'));
		echo $arr;
	}
 }
?>