<?php
/**
 * 应付账款控制层类
 */
class controller_finance_payable_payable extends controller_base_action {

	function __construct() {
		$this->objName = "payable";
		$this->objPath = "finance_payable";
		parent::__construct ();
	}

	/**
	 * 应付账款查询过滤
	 */
	function c_toDetailPage(){
		$this->assign('year' , date('Y') );
		$this->display( 'todetailpage' );
	}

	/**
	 * 应付账款明细表
	 */
	function c_detailPage(){
		$this->assignFunc( $_GET[$this->objName]);
		$this->display( 'detailpage' );
	}

	/**
	 * 应付账款PageJson
	 */
	function c_detailPageJson(){
		$service = $this->service;
		$rows = $service->getPayDetail_d($_POST);
        $rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 应付账款汇总表
	 */
	function c_toCountPage(){
		$this->assign('year' , date('Y') );
		$this->display('tocountpage');
	}

	/**
	 * 应付款汇总表
	 */
	function c_countPage(){
		$this->assignFunc($_GET[$this->objName]);
		$this->display('countpage');
	}

	/**
	 * 应付款汇总表PageJson
	 */
	function c_countPageJson(){
		$service = $this->service;
//		$service->getParam ( $_POST ); //设置前台获取的参数信息
		$rows = $service->getCount_d($_POST);
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
	}


	/***************************** S 付款明细表 ******************************/
	/**
	 * 付款明细表
	 */
	function c_payablesDetail(){

		if(isset($_GET['beginYear'])){
			$initArr = $_GET;
		}else{
			//初始化数组
			$initArr = array(
				'beginYear' => date("Y"),
				'beginMonth' => 1,
				'endYear' => date("Y"),
				'endMonth' => date("m"),

				'supplierName' => null,
				'objType' => null,
				'objCode' => null,

				'payContent' => null,
				'amount' => null,

				'salesman' => null,
				'salesmanId' => null,
				'deptId' => null,
				'deptName' => null
			);
		}

		$this->assignFunc($initArr);
    	$this->display('payablesdetail');
	}

	/**
     *应收账款明细 --- 高级查询
     */
    function c_payablesDetailSearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "年</option>";
		}
		$this->assign('yearStr',$yearStr);
		$this->assignFunc( $_GET );
        $this->view('payablesdetailsearch');
    }


	/****************** S 应付账款报表 *********************/
	/**
	 * 应付账款报表-列表显示
	 * create by kuangzw
	 */
	function c_toSupplierPayables() {
		if (isset ( $_GET ['supplierName'] )) {
			$initArr = $_GET;
		} else {
			$thisYear = date ( 'Y' );
			$thisMonth = date ( 'm' ) * 1;

			//初始化数组
			$initArr = array ("beginYear" => $thisYear, "beginMonth" => $thisMonth, "endYear" => $thisYear, "endMonth" => $thisMonth,

			"supplierId" => '', "supplierName" => '');
		}

		$this->assignFunc ( $initArr );
		$this->view ( 'supplierpayables' );
	}

	/**
	 *
	 * 应付账款报表-查询页面
	 * create by kuangzw
	 */
	function c_toSupplierPayablesSearch() {
		$year = date ( "Y" );
		$yearStr = "";
		for($i = $year; $i >= 2005; $i --) {
			$yearStr .= "<option value=$i>" . $i . "年</option>";
		}
		$this->assign ( 'yearStr', $yearStr );
		$this->assignFunc ( $_GET );
		$this->view ( "supplierpayables-search" );
	}

	/****************** E 应付账款报表 *********************/
}
?>