<?php
/**
 * @author Show
 * @Date 2013��12��10�� ���ڶ� 17:12:46
 * @version 1.0
 * @description:����Э�����Ϣ���Ʋ�
 */
class controller_purchase_material_material extends controller_base_action {

	function __construct() {
		$this->objName = "material";
		$this->objPath = "purchase_material";
		parent::__construct ();
	 }

	/**
	 * ��ת������Э�����Ϣ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת����������Э�����Ϣҳ��
	 */
	function c_toAdd() {
		$this->showDatadicts ( array ('protocolTypeCode' => 'XYLL' )); //Э������
		$this->assign("createName",$_SESSION['USERNAME']);
		$this->assign("createId",$_SESSION['USER_ID']);
		$this->assign("createTime",date("Y-m-d H:i:s"));
		$this->view ( 'add' );
   }

   /**
	 * ��ת���༭����Э�����Ϣҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('protocolTypeCode' => 'XYLL' ) ,$obj['protocolTypeCode']); //Э������
		$this->view ( 'edit');
	}

   /**
	 * ��ת���鿴����Э�����Ϣҳ��
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
    * ��дadd
    */
	function c_add(){
		$this->permCheck (); //��ȫУ��
		$materialId = $this->service->add_d($_POST['material']);
		if($materialId){
			msg ( '����ɹ���' );
		}else{
			msg ( '����ʧ�ܣ�' );
		}
	}

	/*
	 * ɾ��
	 */
	function c_ajaxdeletes() {
		try {
			$flag = $this->service->deletes_d ( $_POST ['id'] );
			echo $flag;
		} catch ( Exception $e ) {
			echo 0;
		}
	}

 }
?>