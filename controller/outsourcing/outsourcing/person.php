<?php
/**
 * @author Administrator
 * @Date 2013��9��14�� 15:51:51
 * @version 1.0
 * @description:��Ա�����ϸ���Ʋ�
 */
class controller_outsourcing_outsourcing_person extends controller_base_action {

	function __construct() {
		$this->objName = "person";
		$this->objPath = "outsourcing_outsourcing";
		parent::__construct ();
	 }

	/**
	 * ��ת����Ա�����ϸ�б�
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * ��ת��������Ա�����ϸҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * ��ת���༭��Ա�����ϸҳ��
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
	 * ��ת���鿴��Ա�����ϸҳ��
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
	 * ��ת���鿴��Ա�����ϸҳ��
	 */
   function c_selectPersonnel(){
        $applyId=$_POST['applyId'];//����ID
        $riskCode= $_POST['riskCode'];//��Ա״̬
        //���ݻ�ȡ
        $rows = $this->service->selectPersonnel_d(array('applyId'=>$applyId,'riskCode'=>$riskCode));
        if(is_array($rows['0'] )){
        	echo util_jsonUtil::encode ( $rows['0'] );
        }else{
        	echo 0;
        }
    }


 }
?>