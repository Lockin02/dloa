<?php
/**
 * @author Administrator
 * @Date 2012年3月22日 21:49:56
 * @version 1.0
 * @description:合同/物料审批关联表控制层
 */
class controller_contract_contract_contequlink extends controller_base_action {

	function __construct() {
		$this->objName = "contequlink";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * 跳转到合同/物料审批关联表
	 */
    function c_page() {
      $this->view('list');
    }

	 /**
     * 变更物料确认审批后跳转方法
     */
    function c_confirmChange(){
        if (! empty ( $_GET ['spid'] )){
            $this->service->confirmChange($_GET ['spid'] );
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }


	 /**
     * 新增物料确认审批后跳转方法
     */
    function c_confirmAudit(){
        if (! empty ( $_GET ['spid'] )){
            $this->service->confirmAudit($_GET ['spid'] );
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

 }
?>