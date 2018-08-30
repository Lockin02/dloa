<?php
/**
 * @author Administrator
 * @Date 2012年11月20日 10:10:05
 * @version 1.0
 * @description:入库通知单控制层
 */
class controller_stock_withdraw_innotice extends controller_base_action {

	function __construct() {
		$this->objName = "innotice";
		$this->objPath = "stock_withdraw";
		parent :: __construct();
	}

	/**
	 * 跳转到入库通知单列表
	 */
	function c_page() {
		$this->view('list');
	}
	
	/**
	 * 跳转到入库通知单列表-生产
	 */
	function c_pageByProduce() {
		$this->view('produce-list');
	}

	/**
	 * 跳转到新增入库通知单页面
	 */
	function c_toAdd() {
		$docDao = new model_stock_withdraw_withdraw();
		$docObj = $docDao->get_d($_GET['id']);
		foreach ($docObj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('consigneeId',$_SESSION['USER_ID']);
		$this->assign('consignee',$_SESSION['USERNAME']);
		$this->assign('thisDate',day_date);
        $this->assign('drawCode',$docObj['planCode']);
        $this->assign('drawId',$_GET['id']);
		$this->view('add');
	}

	/**
	 * 跳转到新增入库通知单页面-生产
	 */
	function c_toAddByProduce() {
		$docDao = new model_produce_plan_produceplan();
		$docObj = $docDao->get_d($_GET['relDocId']);
		foreach ($docObj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('consigneeId',$_SESSION['USER_ID']);
		$this->assign('consignee',$_SESSION['USERNAME']);
		$this->assign('drawCode',$docObj['planCode']);
		$this->assign('drawId',$_GET['id']);
		$this->assign('thisDate',day_date);
		$this->view('produce-add');
	}
	
	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 跳转到编辑入库通知单页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("itemsList", $this->service->showItemAtEdit($obj['items']));
		$this->assign("itemscount", count($obj['items']));
		$this->view('edit');
	}

	/**
	 * 跳转到查看入库通知单页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		if( $obj['docType']=='oa_contract_exchange' ){
			$obj['docType']='换货需求';
		}else if ($obj['docType']=='oa_produce_plan') {
			$obj['docType']='生产计划';
		}
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
//		$this->assign("itemsList", $this->service->showItemAtView($obj['items']));
//		$this->assign("itemscount", count($obj['items']));
		$this->view('view');
	}

	/**
	 * 获取源单对象
	 */
	 function c_getDocEqu(){
	 	$id=$_POST['id'];
	 	$type=$_POST['docType'];
	 	$row = $this->service->getDocEqu_d($id,$type);
		echo util_jsonUtil::encode ( $row );
	 }

	/**
	 * 发货计划列表从表
	 */
	 function c_equJson(){
	 	$outplanEqu = new model_stock_withdraw_equ();
		$outplanEqu->searchArr['mainId'] = $_POST['mainId'];
		$outplanEqu->searchArr['isDel'] = 0;
		$rows = $outplanEqu->list_d ();
//		echo "<pre>";
//		print_R($rows);
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $outplanEqu->count ? $outplanEqu->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $outplanEqu->page;
		echo util_jsonUtil::encode ( $arr );
	 }
}
?>