<?php
/**
 * @author tse
 * @Date 2014��3��14�� 10:43:27
 * @version 1.0
 * @description:��������ʱ��������Ʋ� 
 */
class controller_finance_payablesapply_payablesapplychange extends controller_base_action {

	function __construct() {
		$this->objName = "payablesapplychange";
		$this->objPath = "finance_payablesapply";
		parent::__construct ();
	 }
    
	/**
	 * ��ת����������ʱ�������б�
	 */
    function c_page() {
      $this->assign('salesmanId', $_SESSION['USER_ID']);
      $this->view('list');
    }
    
   /**
	 * ��ת��������������ʱ������ҳ��
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
    * ��дadd����
    */
   function c_add(){
   	$object = $_POST [$this->objName];
	$id = $this->service->add_d($object,true);
		if($id){
			succ_show('controller/finance/payablesapply/ewf_index2.php?actTo=ewfSelect&billId='.$id);
		}else{
			msg("�ύʧ��");
		}
	}
   
   /**
	 * ��ת���༭��������ʱ������ҳ��
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
	 * ��ת���鿴��������ʱ������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//������չ������
		$this->assign('exaId',$_GET ['id']);
		$this->assign('exaCode',$this->service->tbl_name);
		
		$skey=$this->md5Row($obj['purOrderId'], 'finance_payablesapply_payablesapply');
		$this->assign('skey', $skey);
		$this->view ( 'view' );
   }
   
   /**
    * �����鿴ҳ��
    */
   function c_toAuditView() {
   	$this->permCheck (); //��ȫУ��
   	$obj = $this->service->get_d ( $_GET ['id'] );
   	foreach ( $obj as $key => $val ) {
   		$this->assign ( $key, $val );
   	}
   	$skey=$this->md5Row($obj['purOrderId'], 'finance_payablesapply_payablesapply');
   	$this->assign('skey', $skey);
   	$this->view ( 'auditview' );
   }
   
   /**
    * �����������ҳ��
    */
	function c_dealAfterAudit(){
		$this->service->dealAfterAudit_d($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
 }
?>