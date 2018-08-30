<?php
/**
 * @author Show
 * @Date 2013��12��10�� ���ڶ� 17:12:50
 * @version 1.0
 * @description:����Э�����ϸ����Ʋ�
 */
class controller_purchase_material_materialequ extends controller_base_action {

	function __construct() {
		$this->objName = "materialequ";
		$this->objPath = "purchase_material";
		parent::__construct ();
	 }

	/**
	 * ��ת������Э�����ϸ���б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת����������Э�����ϸ��ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭����Э�����ϸ��ҳ��
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
	 * ��ת���鿴����Э�����ϸ��ҳ��
	 */
	function c_toView() {
      $this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );

		if ($obj['isEffective'] == 'on') {
			$obj['isEffective'] ='��';
		}else {
			$obj['isEffective'] = '��';
		}

		if ($obj['lowerNum'] == 0) {
			$obj['lowerNum'] = "<span style='color:red'>-</span>";
		}
		if ($obj['ceilingNum'] == 0) {
			$obj['ceilingNum'] = "<span style='color:red'>-</span>";
		}
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 }
?>