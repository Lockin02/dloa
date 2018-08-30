<?php
/**
 * @author Michael
 * @Date 2014��1��20�� ����һ 15:32:49
 * @version 1.0
 * @description:�⳵������������ӱ���Ʋ�
 */
class controller_outsourcing_vehicle_rentalcarequ extends controller_base_action {

	function __construct() {
		$this->objName = "rentalcarequ";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
	 }

	/**
	 * ��ת���⳵������������ӱ��б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת�������⳵������������ӱ�ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭�⳵������������ӱ�ҳ��
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
	 * ��ת���鿴�⳵������������ӱ�ҳ��
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
    * ��������ID��ѯ��Ӧ�̵�ID�����ַ�����ʽ����
    */
	function c_getSuppByParent() {
		$obj = $this->service->findAll(array('parentId'=>$_POST['parentId']) ,'' ,'suppId');
		foreach ( $obj as $key => $val ) {
			$idArr[$key] = $val['suppId'];
		}
		$ids = implode(',' ,$idArr);
		echo $ids;
	}
 }
?>