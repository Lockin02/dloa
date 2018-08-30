<?php
/**
 * @author huangzf
 * @Date 2012年8月20日 星期一 20:43:04
 * @version 1.0
 * @description:安全库存列表控制层
 */
class controller_stock_safetystock_safetystock extends controller_base_action {

	function __construct() {
		$this->objName = "safetystock";
		$this->objPath = "stock_safetystock";
		parent::__construct ();
	}

	/**
	 * 跳转到安全库存列表列表
	 */
	function c_page() {
		$this->view( 'list' );
	}

	/**
	 * 列表修改
	 */
	function c_pageJsonCount(){
		$service = $this->service;
        //加载部门权限
        $deptLimit = isset($this->service->this_limit['管理部门']) && !empty($this->service->this_limit['管理部门']) ?
            $this->service->this_limit['管理部门'].','.$_SESSION['DEPT_ID'] : $_SESSION['DEPT_ID'];
        if(strpos($deptLimit,';;') === FALSE){
            $_REQUEST['manageDeptIds'] = $deptLimit;
        }
		$service->getParam ( $_REQUEST );
		$sql = $service->getCountSql_d();
		$rows = $service->pageBySql($sql);

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到新增安全库存列表页面
	 */
	function c_toAdd() {
        $this->assign('manageDept',$_SESSION['DEPT_NAME']);
        $this->assign('manageDeptId',$_SESSION['DEPT_ID']);
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑安全库存列表页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$lastPrice = $this->service->getLastPrice ( $val ['productId'] );
		$actNum = $this->service->getProActNum ( $val ['productId'] );
		$this->assign ( "actNum", $actNum );
		$this->assign ( "price", $lastPrice );
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看安全库存列表页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$lastPrice = $this->service->getLastPrice ( $val ['productId'] );
		$actNum = $this->service->getProActNum ( $val ['productId'] );
		$this->assign ( "actNum", $actNum );
		$this->assign ( "price", $lastPrice );
		$this->view ( 'view' );
	}

	/**
	 *
	 * 获取设备附属物料的库存商品仓数量
	 */
	function c_getProActNum() {
		echo $this->service->getProActNum ( $_POST ['productId'] );
	}

	/**
	 *
	 * 获取在途数量
	 */
	function c_getEqusOnway() {
		$orderEquDao = new model_purchase_contract_equipment ();
		echo $orderObj = $orderEquDao->getEqusOnway ( array ('productId' => $_POST ['productId'] ) );
	}

	/**
	 *
	 * 获取最新入库单价
	 */
	function c_getLastPrice() {
		echo $this->service->getLastPrice ( $_POST ['productId'] );
	}

	/**
	 *
	 * 跳转到分析页面
	 */
	function c_toAnalyse() {
		$itemArr = $this->service->findAnalyseItem ();
		$this->assign ( "itemsList", $this->service->showAnlyseItemList ( $itemArr ) );
		$this->view ( "analyse" );
	}

	/**
	 *
	 * 导出EXCEL
	 */
	function c_toExportExcel() {
        //加载部门权限
        $deptLimit = isset($this->service->this_limit['部门权限']) ? $this->service->this_limit['部门权限'] : $_SESSION['DEPT_ID'];
        if(strpos($deptLimit,';;') === FALSE){
            $this->service->searchArr = array('manageDeptIds' => $deptLimit);
        }
		$sql = $this->service->getCountSql_d();
		$dataArr = $this->service->listBySql($sql);
		$dao = new model_stock_productinfo_importProductUtil ();
		return $dao->exportSafeStockExcel ( $dataArr );
	}
}