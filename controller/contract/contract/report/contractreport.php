<?php
/**
 * @author LiuB
 * @Date 2012��3��26��9:49:42
 * @version 1.0
 * @description:��ͬ����
 */
class controller_contract_report_contractreport extends controller_base_action {

	function __construct() {
		$this->objName = "contractreport";
		$this->objPath = "contract_report";
		parent::__construct ();
	}

	 /**
	  * ����-�̻�����
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
	//��ѯ
    function c_deliverbychanceSearch(){
      $this->display('deliverbychanceSearch');
   }


	 /**
	  * �з�-�̻�����
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
	//��ѯ
    function c_rdprojectbychanceSearch(){
      $this->display('rdprojectbychanceSearch');
   }

	/**
	 * ������-�̻�����
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
	//��ѯ
    function c_servicebychanceSearch(){
      $this->display('servicebychanceSearch');
   }

   /**
    * ������Ŀ������
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
   //��ѯ
    function c_trialprojectSearch(){
      $this->display('trialprojectSearch');
   }
   /**
    * ��ͬ/�̻�������
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
    //��ѯ
   function c_saleSearch(){
      $this->display('saleSearch');
   }
   /**
    * �̻����ݻ��ܱ�
    */
   function c_chanceViewReport(){
   	  if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
      //Ȩ��
	if (isset ($this->service->this_limit['�̻����ݻ���Ȩ��']) && !empty ($this->service->this_limit['�̻����ݻ���Ȩ��']))
      $limitArr = $this->service->this_limit['�̻����ݻ���Ȩ��'];
//     if(!empty($limitArr) && $limitArr != ';;'){
     	$limitArr = $this->handleAreaName($limitArr);
//     }
      $this->assign("areaName" , $limitArr);
   	  $this->display('chanceViewReport');
   }
   //��ѯ
    function c_chanceViewSearch(){
      $this->display('chanceViewSearch');
   }
    /**
    * ��ͬ���ݻ��ܱ�
    */
   function c_contractViewReport(){
   	  if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
       //Ȩ��
	if (isset ($this->service->this_limit['��ͬ���ݻ���Ȩ��']) && !empty ($this->service->this_limit['��ͬ���ݻ���Ȩ��']))
      $limitArr = $this->service->this_limit['��ͬ���ݻ���Ȩ��'];
//     if(!empty($limitArr) && $limitArr != ';;'){
     	$limitArr = $this->handleAreaName($limitArr);
//     }
      $this->assign("areaName" , $limitArr);
   	  $this->display('contractViewReport');
   }
   //��ѯ
    function c_contractViewSearch(){
      $this->display('contractViewSearch');
   }
   /**
    * �̻����ͬ�ϲ����ݻ���
    */
   function c_saleallViewReport(){
   	   if(!empty($_GET["year"])){
   	   	  $year = $_GET["year"];
   	   }else{
   	   	  $year = date ( "Y" );
   	   }

      $this->assign("year" , $year);
     //Ȩ��
	if (isset ($this->service->this_limit['�̻���ͬ�ϲ�����Ȩ��']) && !empty ($this->service->this_limit['�̻���ͬ�ϲ�����Ȩ��']))
      $limitArr = $this->service->this_limit['�̻���ͬ�ϲ�����Ȩ��'];
//     if(!empty($limitArr) && $limitArr != ';;'){
     	$limitArr = $this->handleAreaName($limitArr);
//     }
      $this->assign("areaName" , $limitArr);
   	   $this->display('saleallViewReport');
   }
   //��ѯ
    function c_saleallViewSearch(){
      $this->display('saleallViewSearch');
   }

    /**
     * �����±�
     */
    function c_salesMonthReport(){
         $this->display('salesMonthReport');
    }
    /**
     * ���ۼ���
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
    * ���ۼ��� ��ѯ
    */
   function c_quarterSearch(){
      $this->display('quartersearch');
   }
    /**
     *��ͬ��ϸ��
     */
    function c_contractDetailReport(){


   	   if(isset($_GET['beginDT'])){
			$initArr = $_GET;
		}else{
			   $beginDT = isset($_GET['beginDT'])?$_GET['beginDT']:date("Y") . "-" . date("m") . "-01";
			   $endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, date("m"), date("y") ); //������ж�����
		   	   $endDT = isset($_GET['endDT'])?$_GET['endDT']:date("Y") . "-" . date("m") . "-" . $endYearMonthNum;//�½�������
		   	   $contractTypeName = isset($_GET['contractType'])?$_GET['contractType']:null;
		   	   $area = isset($_GET['area'])?$_GET['area']:null;
		   	   $principal = isset($_GET['principal'])?$_GET['principal']:null;
		   	   $customerName = isset($_GET['customerName'])?$_GET['customerName']:null;
		   	   $customerType = isset($_GET['customerType'])?$_GET['customerType']:null;
		   	   $complete = isset($_GET['complete'])?$_GET['complete']:null;

			//��ʼ������
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
     * ��ͬ��ϸ��  ��ѯ
     */
    function c_detailSearch(){
      $this->display('detailSearch');
    }



	/**
	 * ��ͬͼ�α���
	 */
	function c_contractReport(){
		$service=$this->service;
		$reportTypeCn="��ͬ����";
		$countField="count(o.id)";
		if($_GET['reportType']=="money"){
			$reportTypeCn="��ͬ���";
			$countField="sum(o.contractMoney)";
			$this->assign("reportType",$_GET['reportType']);
		}else{
			$this->assign("reportType","");
		}

		//ʱ�䴦��
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

		//����Ĭ������
		$areaChartConf = array(
			'exportFileName'=>"��������ͳ��$reportTypeCn",
			'caption'=>"��������ͳ��$reportTypeCn",
			'formatNumberScale' =>0
		);
		$charts=new model_common_fusionCharts();
		$rows1=$service->getContractByArea($countField,$sqlPlus);
		$result1=$charts->showCharts($rows1,'',$areaChartConf,960);
		$this->assign( 'resultArea',$result1);


		//����Ĭ������
		$typeChartConf = array(
			'exportFileName'=>"����ͬ����ͳ��$reportTypeCn",
			'caption'=>"����ͬ����ͳ��$reportTypeCn"
		);

		$rows2 = $service->getContractByType($countField,$sqlPlus);
		$result2=$charts->showCharts($rows2,'',$typeChartConf);
		$this->assign( 'resultType',$result2);


		//��ͬ״̬Ĭ������
		$statusChartConf = array(
			'exportFileName'=>"����ͬ״̬ͳ��$reportTypeCn",
			'caption'=>"����ͬ״̬ͳ��$reportTypeCn"
		);
		//$service->groupBy="tablename";
		$rows3=$service->getContractByStatus($countField,$sqlPlus);

		$result3=$charts->showCharts($rows3,'',$typeChartConf);
		$this->assign( 'resultStatus',$result3);

		$this->view('charts' );
	}

   /**
    * ������������
    */
   function handleAreaName($arr){
       $arr = explode(",",$arr);
       foreach($arr as $key => $val){
          $arrStr .= "'$val',";
       }
      //��ȡ��ǰ��¼��������� ����ʡ��
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
	 * ����Ȩ�޿���
	 */
  function initLimit() {
  		if (isset ($this->service->this_limit['��������']) && !empty ($this->service->this_limit['��������']))
			$limitArr['areaLimit'] = $this->service->this_limit['��������'];
  }


  /**
   * �鿴��ʱ����ı�����Ϣ
   */
  function c_timingViewChoose(){
     $this->view('timingViewChoose');
  }
    /**
     * ���ݱ������Ͳ�ѯ��������������
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
    * ��תѡ��ı���
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

   	    //Ȩ��
		if (isset ($this->service->this_limit['�̻����ݻ���Ȩ��']) && !empty ($this->service->this_limit['�̻����ݻ���Ȩ��']))
	      $limitArr = $this->service->this_limit['�̻����ݻ���Ȩ��'];
//	     if(!empty($limitArr) && $limitArr != ';;'){
	     	$limitArr = $this->handleAreaName($limitArr);
//	     }
	   $this->assign("areaName" , $limitArr);
	   //����������ݣ���ȡ����
	   $year = date ( "Y" );
	   $this->assign('year',$year);
   	   $this->view('reportView');
   }


	/**
	 * ������ͬ����
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