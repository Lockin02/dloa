<?php 
class controller_engineering_project_esmreport extends controller_base_action {
	function __construct() {
		$this->objName = "esmreport";
		$this->objPath = "engineering_project";
		parent::__construct ();
	}
	
	/**
	 * ��Ŀ���ܳ���
	 */
	function c_showPage(){
		$this->showDatadicts ( array ('status' => 'GCXMZT' ));
		$this->showDatadicts ( array ('nature' => 'GCXMXZ' ));
		$this->showDatadicts ( array ('productLine' => 'GCSCX' ));
		//����������ʼ��
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
	
	//TODO��Ŀ���ܳ���
	function c_showProjectList(){
		$esmprojectDao = new model_engineering_project_esmproject();
		$service = $esmprojectDao;
		//��ȡͳ�Ʒ�ʽ
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
		$service->setCompany(1);# ���ô��б����ù�˾
		# Ĭ��ָ���ı����
		$service->setComLocal(array(
		"c" => $service->tbl_name
		));
		//���´�Ȩ�޲���
		$officeArr = array();
		$otherDataDao = new model_common_otherdatas();
		$limit = $otherDataDao->getUserPriv('engineering_project_esmproject',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
		$sysLimit = $limit['���´�'];

		//ʡ��Ȩ�޴���
		//$proArr = array();
	
		//������Ȩ��
		$manArr = array();
	
		//ʡ��Ȩ��
		$proLimit = $limit['ʡ��Ȩ��'];
	
		//���´� �� ȫ�� ����
		if(strstr($sysLimit,';;')|| strstr($proLimit,';;')){
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$service->sort='c.productLine,c.officeName,c.province';
			//�жϲ�Ʒ�ߣ�ʡ�ݣ��������
			if($type == 'productLine'){
				$service->groupBy = 'c.productLine';
			}
			elseif($type == 'officeName'){
				$service->groupBy = 'c.officeName,c.productLine';
			}else if($type == 'province'){
				$service->groupBy ='c.productLine,c.officeName,c.province';
			}
			$rows = $service->list_d('projectShow');
		}else{//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
			if(!empty($sysLimit)) array_push($officeArr,$sysLimit);
			//���´�����Ȩ��
			$officeIds = $service->getOfficeIds_d();
			if(!empty($officeIds)){
				array_push($officeArr,$officeIds);
			}
	
			// 			if(!empty($proLimit)) array_push($proArr,$proLimit);
	
			//������Ȩ��
			$manager = $service->getProvincesAndLines_d();
			if(!empty($manager)){
				$manArr=$manager;
			}
	
			if(!empty($officeArr) || !empty($manArr)){
				$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
	
				$sqlStr = "sql: and (";
				//���´��ű�����
				if($officeArr){
					$sqlStr .=" c.officeId in (".implode(array_unique(explode(",",implode($officeArr,','))),',') .") ";
				}
	
				//ʡ�ݽű�����(����)
				if($manArr){
					if($officeArr) $sqlStr .= " or ";
					if(!empty($proLimit)){//�ж��Ƿ���ʡ��Ȩ��
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
				
				//�жϲ�Ʒ�ߣ�ʡ�ݣ��������
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
				$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
				$service->sort='c.productLine,c.officeName,c.province';
				//�жϲ�Ʒ�ߣ�ʡ�ݣ��������
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
			
			//����ǲ�Ʒ�ߣ����������ʡ��
			if($type == 'productLine'){
				foreach ($rows as $k =>$v){
					unset($rows[$k]['officeName']);
					unset($rows[$k]['province']);
				}
			}
			//������Ϣ����
			$rowSum = array(officeName =>'����','projectCount'=>0,'contractMoneySum'=>0,'salesMoney'=>0,
					'profitMoney'=>0,'budget'=>0,'cost'=>0
			);
	
			foreach($rows as $key =>$val){
				$rowSum['projectCount'] += $rows[$key]['projectCount'];
				$rowSum['contractMoneySum'] = bcadd($rowSum['contractMoneySum'], $rows[$key]['contractMoneySum'],2);
				$rowSum['budget'] = bcadd($rowSum['budget'], $rows[$key]['budget'],2);
				$rowSum['cost'] = bcadd($rowSum['cost'], $rows[$key]['cost'],2);
				//Ӫ��
				if($statistical == '2'){
					$rows[$key]['salesMoney'] = $rows[$key]['salesMoney2'];
				}else{
					$rows[$key]['salesMoney'] = $rows[$key]['salesMoney1'];
				}
				$rowSum['salesMoney']= bcadd($rowSum['salesMoney'], $rows[$key]['salesMoney'],2);
				unset($rows[$key]['salesMoney1']);
				unset($rows[$key]['salesMoney2']);
				//ë��
				$rows[$key]['profitMoney']  = bcsub($rows[$key]['salesMoney'],$rows[$key]['feeAllCount'],2);
				$rowSum['profitMoney']= bcadd($rowSum['profitMoney'], $rows[$key]['profitMoney'],2);
				unset($rows[$key]['feeAllCount']);
				//ë����
				$rows[$key]['profitRate']  = bcmul(bcdiv($rows[$key]['profitMoney'],$rows[$key]['salesMoney'],4),100,2);
			}
			$rowSum['profitRate']= bcmul(bcdiv($rowSum['profitMoney'],$rowSum['salesMoney'],4),100,2);
			array_push($rows, $rowSum);
		}
		exit(util_jsonUtil::encode($rows));
	}
}
?>