<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 16:45:47
 * @version 1.0
 * @description:检验报告清单控制层
 */
class controller_produce_quality_qualityereportitem extends controller_base_action {

	function __construct() {
		$this->objName = "qualityereportitem";
		$this->objPath = "produce_quality";
		parent::__construct ();
	}

	/**
	 * 跳转到检验报告清单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增检验报告清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑检验报告清单页面
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
	 * 跳转到查看检验报告清单页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

    /**
     * 获取所有数据返回json
     */
    function c_listJson() {
        $service = $this->service;
        $service->getParam ( $_REQUEST );
        $service->asc = false;
        $rows = $service->list_d ();
        //数据加入安全码
        $rows = $this->sconfig->md5Rows ( $rows );
        echo util_jsonUtil::encode ( $rows );
    }

    /**
	 *
	 * 获取检验报告清单subgrid数据
	 */
	function c_pageItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

    /**
	 *
	 * 获取检验报告清单editgrid数据
	 */
	function c_editItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		echo util_jsonUtil::encode ( $rows );
	}
}