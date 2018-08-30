<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:22:42
 * @version 1.0
 * @description:��Ʒ������ϸ����Ʋ� 
 */
class controller_stockup_application_applicationProducts extends controller_base_action {

	function __construct() {
		$this->objName = "applicationProducts";
		$this->objPath = "stockup_application";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����Ʒ������ϸ���б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת��������Ʒ������ϸ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭��Ʒ������ϸ��ҳ��
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
	 * ��ת���鿴��Ʒ������ϸ��ҳ��
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
	 * ��񷽷�
	 */
	function c_pageItemJson(){
    	$service = $this->service;
		$rows = null;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('pageItem');
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
    }
 }
?>