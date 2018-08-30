<?php
/**
 * @author Administrator
 * @Date 2010年12月21日 20:57:46
 * @version 1.0
 * @description:盘点入库控制层
 */
class controller_stock_checkinfo_stockcheckinstock extends controller_base_action {

	function __construct() {
		$this->objName = "stockcheckinstock";
		$this->objPath = "stock_checkinfo";
		parent::__construct ();
	 }

	/*
	 * 跳转到盘点入库
	 */
    function c_page() {
    	$this->show->display($this->objPath . '_' . $this->objName . '-list');
    }
	/*
	 * 跳转到盘点入库(demo)
	 */
    function c_page1() {
    	$this->show->display($this->objPath . '_' . $this->objName . '-list1');
    }
    /**
	 * @desription 跳转到添加新的盘点入库信息页面
	 * @param tags
	 * @date 2010-12-21 下午09:47:00
	 * @qiaolong
	 */
	function c_toAdd () {
		$this->showDatadicts ( array ('checkType' => 'PDLX' ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-add');
	}
	/**
	 * @desription 修改盘点入库信息
	 * @param tags
	 * @date 2010-12-22 上午11:09:47
	 * @qiaolong
	 */
	function c_toEdit () {
		//显示盘点信息
		$rows = $this->service->get_d ( $_GET ['id'] );
		$rows['file']=$this->service->getFilesByObjId($_GET ['id'],'oa_stock_check_instock');
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->showDatadicts ( array ('checkType' => 'PDLX' ), $rows ['checkType'] );

		//显示盘点产品信息
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$rows = $service->getCheckProductInfo_d ($checkId);
		$this->show->assign ( 'list', $service->showProductInfoEdit ( $rows ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-edit');
	}
	/**
	 * @desription 查看盘点入库信息
	 * @param tags
	 * @date 2010-12-22 上午11:21:43
	 * @qiaolong
	 */
	function c_toRead () {
		//显示盘点信息
		$rows = $this->service->get_d ( $_GET ['id'] );
		$rows['file'] = $this->service->getFilesByObjId($_GET ['id'], false);
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$checkType = $this->getDataNameByCode($rows['checkType']);
		$this->show->assign('checkType',$checkType);


		//显示盘点产品信息
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$arr = $service->getCheckProductInfo_d ($checkId);
		$actType=isset($_GET['actType'])?$_GET['actType']:null;
		$this->show->assign("actType",$actType);//操作页面(一般的查看页面、内嵌在审批表单中)

		$this->show->assign ( 'list', $service->showProductInfoList ( $arr ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-view');


	}
	/**
	 * @desription 我的审核任务 盘点信息审核
	 * @param tags
	 * @date 2010-12-22 下午04:10:25
	 * @qiaolong
	 */
	function c_toMytaskCheckinfo () {
		$this->show->display($this->objPath . '_' . $this->objName . '-mychecktask-list');
	}
	/**
	 * @desription 我的审核任务 盘点信息审核 列表获取数据方法
	 * @param tags
	 * @date 2010-12-22 下午04:28:15
	 * @qiaolong
	 */
	function c_mytaskPJ () {
		$service = $this->service;
		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$service->asc = false;
//		$auditNameId = isset ($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : null;
		$service->searchArr['findInName'] = $_SESSION['USER_ID'];
		$service->searchArr['Flag'] = 0;
		$service->searchArr['workFlowCode'] = $service->tbl_name;
//		$rows = $service->mychecktaskinfo_d ();
		$rows = $service->pageBySqlId ('sql_examine');
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}
	/**
	 * @desription 盘点信息修改保存方法
	 * @param tags
	 * @date 2010-12-22 下午04:28:15
	 * @qiaolong
	 */
	function c_checkInfoEdit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}
	/**
	 * @desription 跳转到提交审核页面方法
	 * @param tags
	 * @date 2010-12-23 下午02:20:35
	 * @qiaolong
	 */
	function c_submitAudit () {
		//显示盘点信息
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$checkType = $this->getDataNameByCode($rows['checkType']);
		$this->show->assign('checkType',$checkType);


		//显示盘点产品信息
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$rows = $service->getCheckProductInfo_d ($checkId);
		$this->show->assign ( 'list', $service->showProductInfoList ( $rows ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-submitaudit-page');
	}
	/**
	 * @desription 提交审批方法
	 * @param tags
	 * @date 2010-12-22 下午04:28:15
	 * @qiaolong
	 */
	function c_submitEdit($isEditInfo = false) {
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '提交审批成功！' );
		}
	}

	/**
	 * @desription 跳转到盘点入库页面方法
	 * @param tags
	 * @date 2010-12-24 上午10:40:15
	 * @qiaolong
	 */
	function c_intostock () {
		//显示盘点信息
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$checkType = $this->getDataNameByCode($rows['checkType']);
		$this->assign('checkT',$checkType);

		//显示盘点产品信息
		$service = $this->service;
		$searchArr = $service->getParam ( $_GET );
		$checkId = $_GET['id'];
		$rows = $service->getCheckProductInfo_d ($checkId);
		$this->show->assign ( 'list', $service->showProductInfoList ( $rows ) );
		$this->show->display($this->objPath . '_' . $this->objName . '-intostock-page');
	}
	/**
	 * @desription 盘点入库方法
	 * @param tags
	 * @date 2010-12-24 上午10:44:24
	 * @qiaolong
	 */
//	function c_intoShockInfo () {
//		$id = $_GET['id'];
//		$service = $this->service;
//		$service->searchArr['id']=$id;
//		$rows = $service->listBySqlId('select_default');
//		$stockCode = $rows[0]['stockCode'];
//		$checktype = $rows[0]['checkType'];
//		$arr = $service->getProductInfo($id);
//		if(!$arr){
//			msg('没有产品不能入库');
//		}else{
//			$updatesql ="update oa_stock_check_instock set ExaStatus='已入库' where id='".$id."'";
//			$service->query($updatesql);
//			foreach($arr as $key=>$val){
//				if($checktype == 'PDPK'){
//					 $adjust = - $arr[$key]['adjust'];
//				}else{
//					 $adjust = $arr[$key]['adjust'];
//				}
//				$productId = $arr[$key]['productId'];
//				$sql = "update oa_stock_inventory_info  c  set c.exeNum=c.exeNum+(".$adjust."),c.actNum=c.actNum+(".$adjust.") where c.stockCode='".$stockCode."' and c.productId='".$productId."'";
//				$service->query($sql);
//			}
//			if($service->query($sql)){
//
//				msg('盘点入库成功');
//			}
//		}
//	}

	/**盘点入库
	*author can
	*2011-1-20
	*/
	function c_intoShockInfo(){
//		echo "<pre>";
//		print_r($_POST);
		$flag=$this->service->intoShockInfo_d($_POST[$this->objName]);
		if($flag){
			msg("盘点入库成功！");
		}else{
			msg('盘点入库不成功');
		}

	}


 }
?>