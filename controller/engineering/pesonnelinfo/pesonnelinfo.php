<?php
/**
 * @author Show
 * @Date 2012��8��1�� 16:20:04
 * @version 1.0
 * @description:Ա��״��(oa_esm_pesonnelinfo)���Ʋ� 
 */
class controller_engineering_pesonnelinfo_pesonnelinfo extends controller_base_action {

	function __construct() {
		$this->objName = "pesonnelinfo";
		$this->objPath = "engineering_pesonnelinfo";
		parent::__construct ();
	 }
    
	/**
	 * ��ת��Ա��״��(oa_esm_pesonnelinfo)�б�
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * ��ת������Ա��״��(oa_esm_pesonnelinfo)ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * ��ת���༭Ա��״��(oa_esm_pesonnelinfo)ҳ��
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
	 * ��ת���鿴Ա��״��(oa_esm_pesonnelinfo)ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 }
?>