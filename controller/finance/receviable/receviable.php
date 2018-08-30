<?php
header("Content-type: text/html; charset=gb2312");
/**
 * 应收账款控制层类
 */
class controller_finance_receviable_receviable extends controller_base_action {

	function __construct() {
		$this->objName = "receviable";
		$this->objPath = "finance_receviable";
		parent::__construct ();
	}


	/********----S-----*******应收账款明细表************************/

	/**
	 * 应收账款明细 - 条件选择页面
	 */
	function c_toDetailPage(){
		if(isset($_GET['customerName'])){
			$initArr = $_GET;
		}else{
			//初始化数组
			$initArr = array(
				'beginYear' => date("Y"),
				'beginMonth' => 1,
				'endYear' => date("Y"),
				'endMonth' => date("m"),

				'customerId' => null,
				'customerName' => null,
				'customerProvince' => null,
				'customerType' => null,

				'orderCode' => null,

				'areaId' => null,
				'areaName' => null,

				'prinvipalId' => null,
				'prinvipalName' => null,

				'areaPrincipalId' => null,
				'areaPrincipal' => null
			);
		}

		$this->assignFunc($initArr);
        $this->display('incomedetail');
	}

	/**
     *应收账款明细 --- 高级查询
     */
    function c_incomeDetailSearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "年</option>";
		}
		$this->assign('yearStr',$yearStr);
		$this->assignFunc( $_GET );
        $this->view('incomedetailsearch');
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
	 * 应收账款明细表
	 */
	function c_incomeDetail($obj = null){
		if(empty($obj)){
			$obj = $_GET;
		}
		$this->assignFunc( $obj );
        $this->display('incomedetail');
	}

	/********----E-----*******应收账款明细表************************/


	/********----S-----******收入汇总表******************************/


    /**
     * 收入汇总表
     */
    function c_incomeSummary(){
		if(isset($_GET['customerName'])){
			$initArr = $_GET;
		}else{
			//初始化数组
			$initArr = array(
				'beginYear' => date("Y"),
				'beginMonth' => 1,
				'endYear' => date("Y"),
				'endMonth' => date("m")*1,

				'customerId' => null,
				'customerName' => null,
				'customerProvince' => null,
				'customerType' => null,

				'orderId' => null,
				'orderCode' => null,

				'areaId' => null,
				'areaName' => null,

				'prinvipalId' => null,
				'prinvipalName' => null,

				'areaPrincipalId' => null,
				'areaPrincipal' => null,

				'incomeMoney' => null,
				'invoiceMoney' => null
			);
		}

		$this->assignFunc($initArr);
        $this->display('incomesummary');
    }
    /**
     * 收入汇总表 --- 高级查询
     */
    function c_summarySearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "年</option>";
		}
		$this->assign('yearStr',$yearStr);
		$this->assignFunc( $_GET );
        $this->view('incomesummarysearch');
    }


	/********----E-----******收入汇总表******************************/

	/********----S-----********到款明细表************************************/

	/**
	 * 到款分析查询
	 */
    function c_incomeAnalysisSearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "年</option>";
		}
		$this->assign('yearStr',$yearStr);
		$this->assignFunc( $_GET );
        $this->view('incomeanalysissearch');
    }
    /**
     * 到款分析表条件查询页面
     */
    function c_toIncomeAnalysis(){
		$this->c_incomeAnalysis();
    }

    /**
     * 到款分析表
     */
    function c_incomeAnalysis(){
		if(isset($_GET['customerName'])){
			$initArr = $_GET;
		}else{
			//初始化数组
			$initArr = array(
				'beginYear' => date("Y"),
				'beginMonth' => 1,
				'endYear' => date("Y"),
				'endMonth' => date("m"),

				'customerId' => null,
				'customerName' => null,
				'customerProvince' => null,
				'customerType' => null,

				'orderId' => null,
				'orderCode' => null,

				'areaId' => null,
				'areaName' => null,

				'prinvipalId' => null,
				'prinvipalName' => null,

				'areaPrincipalId' => null,
				'areaPrincipal' => null
			);
		}

		$this->assignFunc($initArr);

		$this->service->initIncomeAnalysis_d($initArr);

    	$this->display('incomeAnalysis');
    }

    /**
     * 到款明细表pagejson
     */
    function c_incomeAnalysisPj(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
        $rows = $service->list_d('incomeAnalysis2');
        $rows = $service->filterArr_d($rows);
        $newRows=array();
        if(is_array($rows)){
       		foreach($rows as $key=>$val){
       			switch($val['objType']){
       				case 'oa_sale_order':$val['type']='order';break;
       				case 'oa_sale_service':$val['type']='serviceContract';break;
       				case 'oa_sale_lease':$val['type']='rentalcontract';break;
       				case 'oa_sale_rdproject':$val['type']='rdproject';break;
       			}
       		array_push($newRows,$val);
       		}
        }
       $rows = $this->sconfig->md5Rows ( $newRows,'orgid','type' );
		$arr = array ();
		$arr ['collection'] = $rows;
		echo util_jsonUtil::encode ( $arr );
    }

    /**
     * 合同明细查询条件过滤
     */
    function c_toSearch(){
    	$this->assign('year',date('Y'));
        $this->assign('month',date('m'));
        $this->showDatadicts(array('objType' => 'KPRK'));
        $this->showDatadicts(array('formType' => 'YFLX'));
        $this->showDatadicts(array('status' => 'DKZT'));
        $this->showDatadicts ( array ('customerType' => 'KHLX' ));
		$this->display('tosearch');
    }

	/***-----S------*******************************OA进度表*****************************/
    /**
     * OA进度表A
     */
    function c_contractInOut(){
    	if(isset($_GET['periodYear'])){
    		$this->assignFunc($_GET);
    	}else{
			$periodArr = $this->service->rtThisPeriod_d();
			$object = array(
				'beginYear' => date("Y"),
				'beginMonth' => 1,
				'endYear' => date("Y"),
				'endMonth' => 12,
				'customerId' => null,
				'orderId' => null,
				'periodYear' => $periodArr['thisYear'],
				'periodMonth' => $periodArr['thisMonth']
			);
			$this->assignFunc($object);
    	}
    	$this->display('contractinout');
    }


    /**
     * 成本结转预览表 － 查询
     */
    function c_contractInOutSearch(){
		$year = date("Y");
		$yearStr = "";
		for($i=$year;$i>=2005;$i--){
			$yearStr .="<option value=$i>" . $i . "年</option>";
		}
		$this->assign('yearStr',$yearStr);
		$this->assignFunc( $_GET );

		$periodArr = $this->service->rtThisPeriod_d();
		$this->assignFunc($periodArr);
        $this->view('contractinoutsearch');
    }


	/***-----E------*******************************OA进度表*****************************/

}
?>