<?php
/**
 * @author Administrator
 * @Date 2012年4月6日 16:39:39
 * @version 1.0
 * @description:赠送/物料审批关联表控制层
 */
class controller_projectmanagent_present_presentequlink extends controller_base_action {

	function __construct() {
		$this->objName = "presentequlink";
		$this->objPath = "projectmanagent_present";
		parent::__construct ();
	 }

	/*
	 * 跳转到赠送/物料审批关联表
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