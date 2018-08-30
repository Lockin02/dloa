<?php
/**
 * @author Show
 * @Date 2013��8��2�� ������ 14:41:22
 * @version 1.0
 * @description:����ģ�����ñ���Ʋ� 
 */
class controller_stock_template_protemplate extends controller_base_action {

	function __construct() {
		$this->objName = "protemplate";
		$this->objPath = "stock_template";
		parent::__construct ();
	 }
    
	/**
	 * ��ת������ģ�����ñ��б�
	 */
    function c_page() {
     	$this->view('list');
    }
    
   /**
	 * ��ת����������ģ�����ñ�ҳ��
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
   }
   
   /**
    * �����������
    */
   function c_add($isAddInfo = false) {
	   	$this->checkSubmit();
	   	$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
	   	$msg = $_POST ["msg"] ? $_POST ["msg"] : '��ӳɹ���';
	   	if ($id) {
	   		msg ( $msg );
   		}
   }
    
   /**
	 * ��ת���༭����ģ�����ñ�ҳ��
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
	 * ��ת���鿴����ģ�����ñ�ҳ��
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
    * ��ת������ģ��ҳ��
    */
   function c_toProModel(){
   		$this->permCheck (); //��ȫУ��
   		$this->view('protemplate');
   }
 }
?>