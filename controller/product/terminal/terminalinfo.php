<?php

/**
 * @author eric
 * @Date 2013-4-16 13:38:04
 * @version 1.0
 * @description: �ն���Ϣ
 */
class controller_product_terminal_terminalinfo extends controller_base_action {
    function __construct() {
        $this->objName = "terminalinfo";
        $this->objPath = "product_terminal";
        parent::__construct();
    }
    //��ת���б�ҳ
    function c_page() {
        $this->view('list');
    }
    function c_toAdd() {
        $productIdVal = isset($_GET['productId']) ? $_GET['productId'] : '';
        $productNameVal = isset($_GET['productName']) ? $_GET['productName'] : '';
        $this->assign('productId', $productIdVal);
        $this->assign('productName', $productNameVal);
        $this->view('add');
    }


    function c_toEdit() {
        $this->view('edit');
    }
    /**
     * ��ʼ������
     */
    function c_init() {
        $this->permCheck(); //��ȫУ��
        $obj = $this->service->get_d($_GET ['id']);
        foreach ($obj as $key => $val) {
            $this->assign($key, $val);
        }
        if (isset($_GET ['perm']) && $_GET ['perm'] == 'view') {
            $this->view('view');
        } else {
            $this->view('edit');
        }
    }

    /**
     * ��ת������ά������
     */
    function c_toDetail() {
        if ($this->service->this_limit ['�鿴����']) {
            $this->assign("isView", 1);
        } else {
            $this->assign("isView", 0);
        }
        if ($this->service->this_limit ['�༭����']) {
            $this->assign("isEdit", "1");
        } else {
            $this->assign("isEdit", "0");
        }
        $this->view('detail');
    }
    //�鿴�ն˲�Ʒ��ע
    function c_toViewRemark() {
        $terminalinfoModel = new model_product_terminal_terminalinfo();
        $remark = $terminalinfoModel->getRemark($_GET['terminalId']);
        $this->assign('remark', $remark);
        $this->view('viewRemark');
    }
    //�鿴����
    function c_reportInfo(){
    	if ($this->service->this_limit ['�ն˽�����Ϣ��']) {
    		$this->assign('terminalName' ,$_GET['terminalinfo']['terminalName']);
	    	$this->assign('proType' ,$_GET['terminalinfo']['proType']);
	    	$this->assign('deviceType' ,$_GET['terminalinfo']['deviceType']);
	    	$this->assign('supportNetwork' ,$_GET['terminalinfo']['supportNetwork']);
	    	$this->assign('situation' ,$_GET['terminalinfo']['situation']);
	    	$this->assign('versionStatus' ,$_GET['terminalinfo']['versionStatus']);
	    	$this->assign('proTypeName' ,$_GET['terminalinfo']['proTypeName']);
    		$this->view('report');
    	}else{
    		msg('û��Ȩ��');
    	}

    }
	function c_toReportSearch(){
		$this->view('reportSearch');
	}


    //add chenrf
    /**
     * �ն�ά������excel
     */
    function c_detailExcelOut(){
    	if($this->service->this_limit ['�������']){
    		$terminalfunction=new model_product_terminal_terminalfunction();
    		if(!$terminalfunction->detailExcelOut($_POST[$this->objName]))
    			msg("�޼�¼��");
    	}else
    		msg("������Ȩ������");

    }

}
?>
