<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 14:51:20
 * @version 1.0
 * @description:��Ӧ������֤����Ʋ�
 */
class controller_outsourcing_supplier_certify extends controller_base_action {

	function __construct() {
		$this->objName = "certify";
		$this->objPath = "outsourcing_supplier";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ӧ������֤���б�
	 */
    function c_page() {
      $this->view('list');
    }

	/**
	 * ��ת����Ӧ������֤����Ϣ�б�
	 */
    function c_toEditList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('edit-list');
    }

    	/**
	 * ��ת����Ӧ������֤����Ϣ�б�
	 */
    function c_toViewList() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 $this->assign('suppId',$suppId);
      $this->view('view-list');
    }

   /**
	 * ��ת��������Ӧ������֤��ҳ��
	 */
	function c_toAdd() {
		 $suppId=isset($_GET ['suppId'])?$_GET ['suppId']:'';
		 //��ȡ��Ӧ����Ϣ
		 $basicinfoDao=new model_outsourcing_supplier_basicinfo();
		 $suppInfo=$basicinfoDao->get_d($suppId);
		 $this->assign('suppId',$suppId);
		 $this->assign('suppCode',$suppInfo['suppCode']);
		 $this->assign('suppName',$suppInfo['suppName']);
		$this->showDatadicts ( array ('typeCode' => 'WBZZZSLX' ));
    	 $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��Ӧ������֤��ҳ��
	 */
	function c_toEdit() {
   	$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('typeCode' => 'WBZZZSLX' ),$obj['typeCode']);
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],true ));
      $this->view ( 'edit');
   }

   /**
	 * ��ת���鿴��Ӧ������֤��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if($obj['certifyLevel']=="V"){
			$this->assign ( 'certifyLevel', '��' );
		}else if($obj['certifyLevel']=="X"){
			$this->assign ( 'certifyLevel', '��' );
		}else{
			$this->assign ( 'certifyLevel', '' );
		}
		$this->assign ( "file", $this->service->getFilesByObjId ( $_GET ['id'],false ));
      $this->view ( 'view' );
   }
 }
?>