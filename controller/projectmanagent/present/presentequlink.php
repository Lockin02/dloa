<?php
/**
 * @author Administrator
 * @Date 2012��4��6�� 16:39:39
 * @version 1.0
 * @description:����/����������������Ʋ�
 */
class controller_projectmanagent_present_presentequlink extends controller_base_action {

	function __construct() {
		$this->objName = "presentequlink";
		$this->objPath = "projectmanagent_present";
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