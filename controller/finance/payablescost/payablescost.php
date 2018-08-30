<?php

/**
 * @author Show
 * @Date 2012年6月25日 星期一 19:10:38
 * @version 1.0
 * @description:付款申请费用分摊明细表控制层
 */
class controller_finance_payablescost_payablescost extends controller_base_action {

	function __construct() {
		$this->objName = "payablescost";
		$this->objPath = "finance_payablescost";
		parent :: __construct();
	}

	/*
	 * 跳转到付款申请费用分摊明细表列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		//$service->asc = false;
		$rows = $service->page_d ();

		if(!empty($rows)){

			//数据加入安全码
			$rows = $this->sconfig->md5Rows ( $rows );

			//审批情况加载
//			$rows = $service->initExaInfo_d($rows);

			//总计栏加载
			$objArr = $service->listBySqlId('count_all');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['shareTypeName'] = '合计';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 查看付款分摊信息
	 */
	function c_listViewCost(){
		$otherId = $_POST['otherId'];//其他合同id

		$service = $this->service;
		$rows = $service->getListViewCost_d($otherId);
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 跳转到新增付款申请费用分摊明细表页面
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * 跳转到编辑付款申请费用分摊明细表页面
	 */
	function c_toEdit() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * 跳转到查看付款申请费用分摊明细表页面
	 */
	function c_toView() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign('status',$this->service->rtStatus($obj['status']));
		$this->view('view');
	}

	/**
	 * 付款费用分摊录入
	 */
	function c_toShare(){
		$payapplyId = $_GET['payapplyId'];

		$rs = $this->service->find(array('payapplyId' => $payapplyId),null,'id');

		$this->assignFunc($_GET);

		if(is_array($rs)){
			//处理原有费用分摊数据
			$this->service->searchArr = array('payapplyId' => $payapplyId );
			$this->service->asc = false;
			$rows = $this->service->list_d();

			$dataStr = $this->service->initShareEdit_v($rows);
			$this->assign('detail',$dataStr);
			$this->assign('detailNo',count($rows));
//			echo "<pre>";
//			print_r($rows);

			$this->view('share-edit');
		}else{
			$this->view('share-add');
		}
	}

	/**
	 * 付款费用分摊
	 */
	function c_share(){
		$object = $_POST[$this->objName];
		$rs = $this->service->share_d($object);
		if($rs){
			msg('分摊成功');
		}else{
			msg('分摊失败');
		}
	}

	/*************************** 导入导出部分 *************************/
	/**
	 * 费用分摊导入
	 */
	function c_toExcelIn(){
		$this->display('toexcel');
	}

	/**
	 * 费用分摊导入
	 */
	function c_excelIn(){
		$resultArr = $this->service->excelIn_d ($_POST['checkType']);
		$title = '费用分摊导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * 费用分摊-费用类型
	 */
	function c_expense(){
		$this->assign('costTypeId',$_GET['costTypeId']);
		$this->view('expense');
	}
	/**
	 *
	 *查询分摊费用明细（包括临时导入信息）
	 */
   function c_listCost(){
		$rows=$this->service->listCost($_REQUEST['payapplyId'],$_SESSION['USER_ID']);
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>