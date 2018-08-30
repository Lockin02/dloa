<?php
/**
 * @author eric
 * @Date 2013-4-15 14:15:00
 * @version 1.0
 * @description: 终端产品管理
 */
class controller_product_terminal_product extends controller_base_action {
    function __construct() {
        $this->objName = "product";
        $this->objPath = "product_terminal";
        parent::__construct();
    }
    //跳转到列表页
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
     * 初始化对象
     */
    function c_init() {
        $this->permCheck(); //安全校验
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
    //获取产品信息到树
    function c_getProduct() {
        $service = $this->service;
        $service->asc = false;
        $rows = $service->list_d('select_product');
        echo util_jsonUtil :: encode($rows);
    }

    /**
     * 获取产品的终端信息
     */
    function c_listJsonDetail(){
    	$this->service->getParam($_REQUEST);
		$rows = $this->service->listDetailByProduct($_GET['productId']);

        echo util_jsonUtil :: encode($rows);
    }

}
?>
