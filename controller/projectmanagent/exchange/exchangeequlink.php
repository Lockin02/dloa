<?php
/**
 * @author Administrator
 * @Date 2012��4��12�� 7:34:29
 * @version 1.0
 * @description:����/����������������Ʋ�
 */
class controller_projectmanagent_exchange_exchangeequlink extends controller_base_action {

	function __construct() {
		$this->objName = "exchangeEqulink";
		$this->objPath = "projectmanagent_exchange";
		parent::__construct ();
	 }

	/*
	 * ��ת������/��������������
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