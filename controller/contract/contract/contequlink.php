<?php
/**
 * @author Administrator
 * @Date 2012��3��22�� 21:49:56
 * @version 1.0
 * @description:��ͬ/����������������Ʋ�
 */
class controller_contract_contract_contequlink extends controller_base_action {

	function __construct() {
		$this->objName = "contequlink";
		$this->objPath = "contract_contract";
		parent::__construct ();
	 }

	/*
	 * ��ת����ͬ/��������������
	 */
    function c_page() {
      $this->view('list');
    }

	 /**
     * �������ȷ����������ת����
     */
    function c_confirmChange(){
        if (! empty ( $_GET ['spid'] )){
            $this->service->confirmChange($_GET ['spid'] );
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }


	 /**
     * ��������ȷ����������ת����
     */
    function c_confirmAudit(){
        if (! empty ( $_GET ['spid'] )){
            $this->service->confirmAudit($_GET ['spid'] );
        }
        echo "<script>this.location='?model=common_workflow_workflow&action=auditingList'</script>";
    }

 }
?>