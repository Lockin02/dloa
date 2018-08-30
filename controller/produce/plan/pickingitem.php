<?php
/**
 * @author Michael
 * @Date 2014年9月3日 17:25:41
 * @version 1.0
 * @description:生产领料申请单清单控制层
 */
class controller_produce_plan_pickingitem extends controller_base_action {

	function __construct() {
		$this->objName = "pickingitem";
		$this->objPath = "produce_plan";
		parent::__construct ();
	 }

	/**
	 * 跳转到生产领料申请单清单列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增生产领料申请单清单页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑生产领料申请单清单页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit');
	}

	/**
	 * 跳转到查看生产领料申请单清单页面
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
	 * 获取生产计划的生产领料物料数据转成Json
	 */
	function c_pageJsonProduct() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d('select_product');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到查看生产计划的生产领料物料页面
	 */
	function c_toViewProduct() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ($obj as $key => $val) {
			$this->assign($key ,$val);
		}
		$this->assign('applyNum' ,$_GET['applyNum'] > 0 ? $_GET['applyNum'] : '*');
		$this->view('view-product');
	}

	/**
	 * 重写listJson
	 */
	function c_listJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();
		if (is_array($rows)) {
			$pickDao = new model_produce_plan_picking();
			foreach ($rows as $key => $val) {
				$numArr = $pickDao->getProductNum_d($val['productCode']);
				$rows[$key]['JSBC'] = $numArr['JSBC']; //旧设备仓数量
				$rows[$key]['KCSP'] = $numArr['KCSP']; //库存商品仓数量
				$rows[$key]['SCC']  = $numArr['SCC'];  //生产仓数量
			}
		}
		if($_REQUEST['type'] == 'back'){//下推退料申请时处理
			$rows = $service->dataProcessAtBack_d ($rows,$_REQUEST['pickingId']);
		}
		if($_REQUEST['type'] == 'edit'){//编辑领料申请单时处理
			$rows = $service->dataProcessAtEdit_d ($rows,$_REQUEST['taskId'],$_REQUEST['planId']);
		}
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
	
	/**
	 * 领料出库时，获取实际可出库的物料数量
	 */
	function c_getNumAtOutStock(){
		$rs = $this->service->find(array('id' => $_POST['id']),null,'applyNum,realityNum');
		echo $rs['applyNum'] - $rs['realityNum'];
	}
 }