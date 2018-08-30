<?php 
class controller_engineering_project_esmreport extends controller_base_action {
	function __construct() {
		$this->objName = "esmreport";
		$this->objPath = "engineering_project";
		parent::__construct ();
	}
	
	/**
	 * 项目汇总呈现
	 */
	function c_showPage(){
		$this->showDatadicts ( array ('status' => 'GCXMZT' ));
		$this->showDatadicts ( array ('nature' => 'GCXMXZ' ));
		$this->showDatadicts ( array ('productLine' => 'GCSCX' ));
		//日期下拉初始化
		$this->assign('beginDate',  date('Y').'-01-01');
		$this->assign('endDate', date('Y').'-12-31');
		
		$settingDao = new model_system_usersetting_usersetting();
		$settingObj = $settingDao->find(array('user' => $_SESSION['USER_ID'],'businessCode'=>'esmreport'));
		if(!empty($settingObj)){
			$this->assign('memoryused',$settingObj['memoryused']);
			$this->assign('user', $_SESSION['USER_ID']);
		}else{
			$memoryused = array('showProjectCount' => 1,'showContractMoneySum'=>1,
					'showSalesMoney'=> 1,'showCost' => 1,'showProfitMoney' => 1,
					'showProfitRate' => 1,'showBudget' => 1,'showEstimate' =>1
			);
			$settingDao->add_d(array('user' => $_SESSION['USER_ID'],'businessCode'=>'esmreport','memoryused' => util_jsonUtil::encode($memoryused)));
			$this->assign('memoryused',util_jsonUtil::encode($memoryused));
			$this->assign('user', $_SESSION['USER_ID']);
		}
		$this->view("show");
	}
	
	//TODO项目汇总呈现
	function c_showProjectList(){
		$esmprojectDao = new model_engineering_project_esmproject();
		$service = $esmprojectDao;
		//获取统计方式
		if($_POST!=null){
			$statistical = $_POST['statistical'];
			unset($_POST['statistical']);
			if($_POST['contractType']!='GCXMYD-04'){
				$_POST['contractTypeK'] = $_POST['contractType'];
				unset($_POST['contractType']);
			}
			if(isset($_POST['type'])){
				$type = $_POST['type'];
			}else{
				$type = 'productLine';
			}
		}
		else{
			$statistical=1;
			$_POST['beginDateSearch'] = date('Y').'-01-01';
			$_POST['endDateSearch'] = date('Y').'-12-31';
			$type = 'productLine';
		}
		$rows = null;
		$service->setCompany(1);# 设置此列表启用公司
		# 默认指向表的别称是
		$service->setComLocal(array(
		"c" => $service->tbl_name
		));
		//办事处权限部分
		$officeArr = array();
		$otherDataDao = new model_common_otherdatas();
		$limit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$sysLimit = $limit['办事处'];

		//省份权限处理
		//$proArr = array();
	
		//服务经理权限
		$manArr = array();
	
		//省份权限
		$proLimit = $limit['省份权限'];
	
		//办事处 － 全部 处理
		if(strstr($sysLimit,';;')|| strstr($proLimit,';;')){
			$service->getParam($_POST); //设置前台获取的参数信息
			$service->sort='c.productLine,c.officeName,c.province';
			//判断产品线，省份，区域分组
			if($type == 'productLine'){
				$service->groupBy = 'c.productLine';
			}
			elseif($type == 'officeName'){
				$service->groupBy = 'c.officeName,c.productLine';
			}else if($type == 'province'){
				$service->groupBy ='c.productLine,c.officeName,c.province';
			}
			$rows = $service->list_d('projectShow');
		}else{//如果没有选择全部，则进行权限查询并赋值
			if(!empty($sysLimit)) array_push($officeArr,$sysLimit);
			//办事处经理权限
			$officeIds = $service->getOfficeIds_d();
			if(!empty($officeIds)){
				array_push($officeArr,$officeIds);
			}
	
			// 			if(!empty($proLimit)) array_push($proArr,$proLimit);
	
			//服务经理权限
			$manager = $service->getProvincesAndLines_d();
			if(!empty($manager)){
				$manArr=$manager;
			}
	
			if(!empty($officeArr) || !empty($manArr)){
				$service->getParam($_POST); //设置前台获取的参数信息
	
				$sqlStr = "sql: and (";
				//办事处脚本构建
				if($officeArr){
					$sqlStr .=" c.officeId in (".implode(array_unique(explode(",",implode($officeArr,','))),',') .") ";
				}
	
				//省份脚本构建(经理)
				if($manArr){
					if($officeArr) $sqlStr .= " or ";
					if(!empty($proLimit)){//判断是否有省份权限
						$proArr = explode(",", $proLimit);
						$proStr = "";
						foreach ($proArr as $key =>$val){
							$proStr .= "'".$val."',";
						}
						$proStr=substr($proStr,0,strlen($proStr)-1);
						foreach ($manArr as $key =>$val){
							if(!in_array($val['province'],$proArr)){
								$sqlStr.=" (c.province = '".$val['province']."' and c.productLine = '".$val['productLine']."') or ";
							}
						}
						$sqlStr.="(c.province in (".$proStr."))";
					}
					else{
						foreach ($manArr as $key =>$val){
							$sqlStr.=" (c.province = '".$val['province']."' and c.productLine = '".$val['productLine']."') or ";
						}
						$sqlStr=substr($sqlStr,0,strlen($sqlStr)-3);
					}
					$sqlStr.=" ";
				}
	
				$sqlStr.= " )";
				$service->searchArr['mySearchCondition'] = $sqlStr;
				$service->sort='c.productLine,c.officeName,c.province';
				
				//判断产品线，省份，区域分组
				if($type == 'productLine'){
					$service->groupBy = 'c.productLine';
				}
				elseif($type == 'officeName'){
					$service->groupBy = 'c.officeName,c.productLine';
				}else if($type == 'province'){
					$service->groupBy ='c.productLine,c.officeName,c.province';
				}
				
				$rows=$service->list_d('projectShow');
			}
			else if(!empty($proLimit)){
				$service->getParam($_POST); //设置前台获取的参数信息
				$service->sort='c.productLine,c.officeName,c.province';
				//判断产品线，省份，区域分组
				if($type == 'productLine'){
					$service->groupBy = 'c.productLine';
				}
				elseif($type == 'officeName'){
					$service->groupBy = 'c.officeName,c.productLine';
				}else if($type == 'province'){
					$service->groupBy ='c.productLine,c.officeName,c.province';
				}
				$sqlStr = "sql: and (";
				$proArr = explode(",", $proLimit);
				$proStr = "";
				foreach ($proArr as $key =>$val){
					$proStr .= "'".$val."',";
				}
				$proStr=substr($proStr,0,strlen($proStr)-1);
				$sqlStr.=" c.province in (".$proStr.") ";
				$sqlStr.= " )";
				$service->searchArr['mySearchCondition'] = $sqlStr;
				$rows = $service->list_d('projectShow');
			}
		}
		
		if($rows){
			
			//如果是产品线，隐藏区域和省份
			if($type == 'productLine'){
				foreach ($rows as $k =>$v){
					unset($rows[$k]['officeName']);
					unset($rows[$k]['province']);
				}
			}
			//汇总信息数组
			$rowSum = array(officeName =>'汇总','projectCount'=>0,'contractMoneySum'=>0,'salesMoney'=>0,
					'profitMoney'=>0,'budget'=>0,'cost'=>0
			);
	
			foreach($rows as $key =>$val){
				$rowSum['projectCount'] += $rows[$key]['projectCount'];
				$rowSum['contractMoneySum'] = bcadd($rowSum['contractMoneySum'], $rows[$key]['contractMoneySum'],2);
				$rowSum['budget'] = bcadd($rowSum['budget'], $rows[$key]['budget'],2);
				$rowSum['cost'] = bcadd($rowSum['cost'], $rows[$key]['cost'],2);
				//营收
				if($statistical == '2'){
					$rows[$key]['salesMoney'] = $rows[$key]['salesMoney2'];
				}else{
					$rows[$key]['salesMoney'] = $rows[$key]['salesMoney1'];
				}
				$rowSum['salesMoney']= bcadd($rowSum['salesMoney'], $rows[$key]['salesMoney'],2);
				unset($rows[$key]['salesMoney1']);
				unset($rows[$key]['salesMoney2']);
				//毛利
				$rows[$key]['profitMoney']  = bcsub($rows[$key]['salesMoney'],$rows[$key]['feeAllCount'],2);
				$rowSum['profitMoney']= bcadd($rowSum['profitMoney'], $rows[$key]['profitMoney'],2);
				unset($rows[$key]['feeAllCount']);
				//毛利率
				$rows[$key]['profitRate']  = bcmul(bcdiv($rows[$key]['profitMoney'],$rows[$key]['salesMoney'],4),100,2);
			}
			$rowSum['profitRate']= bcmul(bcdiv($rowSum['profitMoney'],$rowSum['salesMoney'],4),100,2);
			array_push($rows, $rowSum);
		}
		exit(util_jsonUtil::encode($rows));
	}
}
?>