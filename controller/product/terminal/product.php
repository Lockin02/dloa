<?php
/**
 * @author eric
 * @Date 2013-4-15 14:15:00
 * @version 1.0
 * @description: �ն˲�Ʒ����
 */
class controller_product_terminal_product extends controller_base_action {
    function __construct() {
        $this->objName = "product";
        $this->objPath = "product_terminal";
        parent::__construct();
    }
    //��ת���б�ҳ
    function c_page() {
        $this->view('list');
    }
    function c_toAdd() {
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
    //��ȡ��Ʒ��Ϣ����
    function c_getProduct() {
        $service = $this->service;
        $service->asc = false;
        $rows = $service->list_d('select_product');
        echo util_jsonUtil :: encode($rows);
    }

    /**
     * ��ȡ��Ʒ���ն���Ϣ
     */
    function c_listJsonDetail(){
    	$this->service->getParam($_REQUEST);
		$rows = $this->service->listDetailByProduct($_GET['productId']);

        echo util_jsonUtil :: encode($rows);
    }

}
?>
