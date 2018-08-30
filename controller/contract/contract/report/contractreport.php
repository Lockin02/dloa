<?php
/**
 * @author LiuB
 * @Date 2012年3月26日9:49:42
 * @version 1.0
 * @description:合同报表
 */
class controller_contract_report_contractreport extends controller_base_action {

	function __construct() {
		$this->objName = "contractreport";
		$this->objPath = "contract_report";
		parent::__construct ();
	}

	 /**
	  * 交付-商机报表
	  */
	 function c_deliverByChanceReport(){
		if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
   	  $this->display('deliverByChanceReport');
	}
	//查询
    function c_deliverbychanceSearch(){
      $this->display('deliverbychanceSearch');
   }


	 /**
	  * 研发-商机报表
	  */
	 function c_rdprojectByChanceReport(){
		if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
   	  $this->display('rdprojectByChanceReport');
	}
	//查询
    function c_rdprojectbychanceSearch(){
      $this->display('rdprojectbychanceSearch');
   }

	/**
	 * 服务线-商机报表
	 */
	function c_serviceByChanceReport(){
		if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
   	  $this->display('servicebychanceReport');
	}
	//查询
    function c_servicebychanceSearch(){
      $this->display('servicebychanceSearch');
   }

   /**
    * 试用项目总览表
    */
   function c_trialprojectReport(){
   	  if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
   	  $this->display('trialprojectReport');
   }
   //查询
    function c_trialprojectSearch(){
      $this->display('trialprojectSearch');
   }
   /**
    * 合同/商机总览表
    */
   function c_saleViewReport(){
   	   if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
      $this->display('saleViewReport');
   }
    //查询
   function c_saleSearch(){
      $this->display('saleSearch');
   }
   /**
    * 商机数据汇总表
    */
   function c_chanceViewReport(){
   	  if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
      //权限
	if (isset ($this->service->this_limit['商机数据汇总权限']) && !empty ($this->service->this_limit['商机数据汇总权限']))
      $limitArr = $this->service->this_limit['商机数据汇总权限'];
//     if(!empty($limitArr) && $limitArr != ';;'){
     	$limitArr = $this->handleAreaName($limitArr);
//     }
      $this->assign("areaName" , $limitArr);
   	  $this->display('chanceViewReport');
   }
   //查询
    function c_chanceViewSearch(){
      $this->display('chanceViewSearch');
   }
    /**
    * 合同数据汇总表
    */
   function c_contractViewReport(){
   	  if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
       //权限
	if (isset ($this->service->this_limit['合同数据汇总权限']) && !empty ($this->service->this_limit['合同数据汇总权限']))
      $limitArr = $this->service->this_limit['合同数据汇总权限'];
//     if(!empty($limitArr) && $limitArr != ';;'){
     	$limitArr = $this->handleAreaName($limitArr);
//     }
      $this->assign("areaName" , $limitArr);
   	  $this->display('contractViewReport');
   }
   //查询
    function c_contractViewSearch(){
      $this->display('contractViewSearch');
   }
   /**
    * 商机与合同合并数据汇总
    */
   function c_saleallViewReport(){
   	   if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
     //权限
	if (isset ($this->service->this_limit['商机合同合并汇总权限']) && !empty ($this->service->this_limit['商机合同合并汇总权限']))
      $limitArr = $this->service->this_limit['商机合同合并汇总权限'];
//     if(!empty($limitArr) && $limitArr != ';;'){
     	$limitArr = $this->handleAreaName($limitArr);
//     }
      $this->assign("areaName" , $limitArr);
   	   $this->display('saleallViewReport');
   }
   //查询
    function c_saleallViewSearch(){
      $this->display('saleallViewSearch');
   }

    /**
     * 销售月报
     */
    function c_salesMonthReport(){
         $this->display('salesMonthReport');
    }
    /**
     * 销售季报
     */
    function c_salesQuarterReport(){
      if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }
   	  if(!empty($_GET['areaCode'])){
   	  	  $areaCode = $_GET['areaCode'];
   	  }else{
   	  	  $areaCode = "all";
   	  }

   	  $principalId = $_GET['principalId'];
   	  $this->assign("year" , $year);
   	  $this->assign("areaCode" , $areaCode);
   	  $this->assign("area" , $_GET['area']);
   	  $this->assign("principalId" , $principalId);
   	  $this->assign("principal" , $_GET['principal']);

    	$this->display('salesquartereport');
    }
   /**
    * 销售季报 查询
    */
   function c_quarterSearch(){
      $this->display('quartersearch');
   }
    /**
     *合同明细表
     */
    function c_contractDetailReport(){


   	   if(isset($_GET['beginDT'])){
			$initArr = $_GET;
		}else{
			   $beginDT = isset($_GET['beginDT'])?$_GET['beginDT']:date("Y") . "-" . date("m") . "-01";
			   $endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, date("m"), date("y") ); //这个月有多少天
		   	   $endDT = isset($_GET['endDT'])?$_GET['endDT']:date("Y") . "-" . date("m") . "-" . $endYearMonthNum;//月结束日期
		   	   $contractTypeName = isset($_GET['contractType'])?$_GET['contractType']:null;
		   	   $area = isset($_GET['area'])?$_GET['area']:null;
		   	   $principal = isset($_GET['principal'])?$_GET['principal']:null;
		   	   $customerName = isset($_GET['customerName'])?$_GET['customerName']:null;
		   	   $customerType = isset($_GET['customerType'])?$_GET['customerType']:null;
		   	   $complete = isset($_GET['complete'])?$_GET['complete']:null;

			//初始化数组
			$initArr = array(
				"beginDT" => $beginDT,
				"endDT" => $endDT,
				"contractType" => $contractTypeName,
				"area" => $area,
				"principal" => $principal,
				"customerName" => $customerName,
				"customerType" => $customerType,
				"complete" => $complete
			);
		}
		$this->assignFunc($initArr);
    	$this->display('contractdetailreport');
    }
    /**
     * 合同明细表  查询
     */
    function c_detailSearch(){
      $this->display('detailSearch');
    }



	/**
	 * 合同图形报表
	 */
	function c_contractReport(){
		$service=$this->service;
		$reportTypeCn="合同数量";
		$countField="count(o.id)";
		if($_GET['reportType']=="money"){
			$reportTypeCn="合同金额";
			$countField="sum(o.contractMoney)";
			$this->assign("reportType",$_GET['reportType']);
		}else{
			$this->assign("reportType","");
		}

		//时间处理
		$sqlPlus="";
		if(!empty($_GET['startTime'])){
			$sqlPlus.=" and o.createTime>='".$_GET['startTime']." 00:00:00.0'";
			$this->assign("startTime",$_GET['startTime']);
		}else{
			$this->assign("startTime","");
		}
		if(!empty($_GET['endTime'])){
			$sqlPlus.=" and o.createTime<='".$_GET['endTime']." 00:00:00.0'";
			$this->assign("endTime",$_GET['endTime']);
		}else{
			$this->assign("endTime","");
		}

		//区域默认设置
		$areaChartConf = array(
			'exportFileName'=>"按区域经理统计$reportTypeCn",
			'caption'=>"按区域经理统计$reportTypeCn",
			'formatNumberScale' =>0
		);
		$charts=new model_common_fusionCharts();
		$rows1=$service->getContractByArea($countField,$sqlPlus);
		$result1=$charts->showCharts($rows1,'',$areaChartConf,960);
		$this->assign( 'resultArea',$result1);


		//类型默认设置
		$typeChartConf = array(
			'exportFileName'=>"按合同类型统计$reportTypeCn",
			'caption'=>"按合同类型统计$reportTypeCn"
		);

		$rows2 = $service->getContractByType($countField,$sqlPlus);
		$result2=$charts->showCharts($rows2,'',$typeChartConf);
		$this->assign( 'resultType',$result2);


		//合同状态默认设置
		$statusChartConf = array(
			'exportFileName'=>"按合同状态统计$reportTypeCn",
			'caption'=>"按合同状态统计$reportTypeCn"
		);
		//$service->groupBy="tablename";
		$rows3=$service->getContractByStatus($countField,$sqlPlus);

		$result3=$charts->showCharts($rows3,'',$typeChartConf);
		$this->assign( 'resultStatus',$result3);

		$this->view('charts' );
	}

   /**
    * 处理区域名称
    */
   function handleAreaName($arr){
       $arr = explode(",",$arr);
       foreach($arr as $key => $val){
          $arrStr .= "'$val',";
       }
      //获取当前登录人所负责的 区域省份
      $userId = $_SESSION['USER_ID'];
      $salePersonDao = new model_system_saleperson_saleperson();
      $saleProArr = $salePersonDao->getProvinceByUserId($userId);
      if(!empty($saleProArr)){
      	 foreach($saleProArr as $key => $val){
       	  $pro = $val['province'];
       	  $proName = $_SESSION['USERNAME'] . "  " . $pro;
          $arrStr .= "'$proName',";
       }
      }
      $arrStr =  rtrim($arrStr,',');
      return $arrStr;
   }
	/**
	 * 区域权限控制
	 */
  function initLimit() {
  		if (isset ($this->service->this_limit['销售区域']) && !empty ($this->service->this_limit['销售区域']))
			$limitArr['areaLimit'] = $this->service->this_limit['销售区域'];
  }


  /**
   * 查看定时保存的报表信息
   */
  function c_timingViewChoose(){
     $this->view('timingViewChoose');
  }
    /**
     * 根据报表类型查询并返回日期下拉
     */
   function c_reportTypechoose(){
   	  $reportType = $_POST['reportType'];
   	  $tableName = $reportType."_timing";
   	  $sql = "select timingDate from $tableName group by timingDate order by timingDate desc";
   	  $dateArr = $this->service->_db->getArray($sql);
   	  foreach($dateArr as $key => $val){
   	  	 $yearStr .= "<option value='".$val['timingDate']."'>" . $val['timingDate'] . "</option>";
   	  }
      echo $yearStr;
   }
   /**
    * 跳转选择的报表
    */
   function c_reportView(){
   	   $reportType = $_GET['reportType'];
   	   $timingDate = $_GET['timingDate'];
   	   switch ($reportType) {
   	   	  case "oa_chance_view" :
              $grfType = "chanceView";
              $reportViewSql = "reportViewSqlChance";
   	   	   break;
   	   	  case "oa_contract_view" :
   	   	      $grfType = "contractView";
              $reportViewSql = "reportViewSqlContract";
   	   	   break;
   	   	  case "oa_saleall_view" :
   	   	      $grfType = "saleallView";
              $reportViewSql = "reportViewSqlSaleall";
   	   	   break;
   	   }
   	   $this->assign("grfType",$grfType);
   	   $this->assign("reportViewSql",$reportViewSql);
   	   $this->assign("timingDate",$timingDate);

   	    //权限
		if (isset ($this->service->this_limit['商机数据汇总权限']) && !empty ($this->service->this_limit['商机数据汇总权限']))
	      $limitArr = $this->service->this_limit['商机数据汇总权限'];
//	     if(!empty($limitArr) && $limitArr != ';;'){
	     	$limitArr = $this->handleAreaName($limitArr);
//	     }
	   $this->assign("areaName" , $limitArr);
	   //报表数据年份，暂取今年
	   $year = date ( "Y" );
	   $this->assign('year',$year);
   	   $this->view('reportView');
   }


	/**
	 * 其他合同报表
	 */
	function c_otherPay(){
		if(isset($_GET['thisYear'])){
			$initArr = $_GET;
		}else{
			$initArr = array(
				'thisYear' => date('Y'),
				'beginMonth' => date('m'),
				'endMonth' => date('m'),
                'createName' => '',
                'payedMoney' => '',
                'signCompanyName' => '',
                'orderCode' => ''
			);
		}
		$this->assignFunc($initArr);
		$this->view('otherpay');
	}
}
?>