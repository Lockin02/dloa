<?php
/**
 * @author tse
 * @Date 2014年3月14日 10:43:27
 * @version 1.0
 * @description:审批付款时间变更表控制层 
 */
class controller_finance_payablesapply_payablesapplychange extends controller_base_action {

	function __construct() {
		$this->objName = "payablesapplychange";
		$this->objPath = "finance_payablesapply";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到审批付款时间变更表列表
	 */
    function c_page() {
      $this->assign('salesmanId', $_SESSION['USER_ID']);
      $this->view('list');
    }
    
   /**
	 * 跳转到新增审批付款时间变更表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
    * 重写add方法
    */
   function c_add(){
   	$object = $_POST [$this->objName];
	$id = $this->service->add_d($object,true);
		if($id){
			succ_show('controller/finance/payablesapply/ewf_index2.php?actTo=ewfSelect&billId='.$id);
		}else{
			msg("提交失败");
		}
	}
   
   /**
	 * 跳转到编辑审批付款时间变更表页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}  
      $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看审批付款时间变更表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//处理扩展审批流
		$this->assign('exaId',$_GET ['id']);
		$this->assign('exaCode',$this->service->tbl_name);
		
		$skey=$this->md5Row($obj['purOrderId'], 'finance_payablesapply_payablesapply');
		$this->assign('skey', $skey);
		$this->view ( 'view' );
   }
   
   /**
    * 审批查看页面
    */
   function c_toAuditView() {
   	$this->permCheck (); //安全校验
   	$obj = $this->service->get_d ( $_GET ['id'] );
   	foreach ( $obj as $key => $val ) {
   		$this->assign ( $key, $val );
   	}
   	$skey=$this->md5Row($obj['purOrderId'], 'finance_payablesapply_payablesapply');
   	$this->assign('skey', $skey);
   	$this->view ( 'auditview' );
   }
   
   /**
    * 审批处理完成页面
    */
	function c_dealAfterAudit(){
		$this->service->dealAfterAudit_d($_GET ['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
 }
?>