<?php


/**
 * ����model����
 */
class model_finance_income_income extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_income";
		$this->sql_map = "finance/income/incomeSql.php";
		parent :: __construct();
	}

	/********************�²��Բ���ʹ��************************/

	private $relatedStrategyArr = array (//��ͬ����������������,������Ҫ���������׷��
		'YFLX-DKD' => 'model_finance_income_strategy_income', //���
		'YFLX-YFK' => 'model_finance_income_strategy_prepayment', //Ԥ�տ�
		'YFLX-TKD' => 'model_finance_income_strategy_refund' //�˿
	);

	private $relatedCode = array (
		'YFLX-DKD' => 'income',
		'YFLX-YFK' => 'prepayment',
		'YFLX-TKD' => 'refund'
	);

	/**
	 * �������ͷ���ҵ������
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	/**
	 * �����������ͷ�����
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

	/***************************************************************************************************
	 * ------------------------------����Ϊ���ýӿڷ���,����Ϊ����ģ��������------------------------------
	 *************************************************************************************************/

	/**
	 * ��ӵ���������
	 */
	function add_d($income ,iincome $strategy) {
		$codeRuleDao = new model_common_codeRule();
		$emailArr = null;
		try {
			$this->start_d();

			$incomeAllot = $income['incomeAllots'];
		 	unset($income['incomeAllots']);

			//�Զ����������
			if($income['formType'] == 'YFLX-TKD'){
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t','DL','ST');
			}else{
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name,'DL','SK');
			}

			if( isset($income['sectionType']) && $income['sectionType'] == 'DKLX-FHK' ){
				$income['status'] = 'DKZT-FHK';
			}else{
				$income['status'] = $this->changeStatus( $income , 'val');
			}

			if(isset($income['email'])){
				$emailArr = $income['email'];
				unset($income['email']);
			}

			if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'  ])){
				$income['isSended'] = 1;
			}

			$incomeId = parent :: add_d($income, true);

			if(is_array($incomeAllot)){
				$incomeAllotDao = new model_finance_income_incomeAllot();
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $incomeId));
			}

			$income['id'] = $incomeId;

			$this->commit_d();

			if($income['formType'] == 'YFLX-TKD'){
				//���������¼
				$logSettringDao = new model_syslog_setting_logsetting ();
				$logSettringDao->addObjLog ( $this->tbl_name, $incomeId, $income,'¼���˿�' );
			}else{
				//���������¼
				$logSettringDao = new model_syslog_setting_logsetting ();
				$logSettringDao->addObjLog ( $this->tbl_name, $incomeId, $income,'¼�뵽��' );
			}

			//�����ʼ� ,������Ϊ�ύʱ�ŷ���
			if(isset($emailArr)){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$income);
				}
			}

			return $incomeId;
		} catch (exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ������������
	 */
	function addOther_d($income){
		$codeRuleDao = new model_common_codeRule();
		try {
			$this->start_d();

			$incomeAllot = $income['incomeAllots'];
		 	unset($income['incomeAllots']);

			$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name,'DL','SK');
			$income['status'] = 'DKZT-YFP';
			$income['formType'] = 'YFLX-DKD';
			$income['sectionType'] = 'DKLX-HK';

			$income['allotAble'] = 0;
			$incomeAllot[1]['money'] =  $income['incomeMoney'];
			$incomeAllot[1]['allotDate'] =  day_date;

			$incomeId = parent :: add_d($income, true);

			if(is_array($incomeAllot)){
				$incomeAllotDao = new model_finance_income_incomeAllot();
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $incomeId));
			}

			$this->commit_d();
			return $incomeId;
		} catch (exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ���������޸Ķ���
	 */
	function edit_d($object) {
		if( $object['sectionType'] == 'DKLX-FHK' ){
			$object['status'] = 'DKZT-FHK';
		}else{
			$object['status'] = 'DKZT-WFP';
		}

		$emailArr = $object['email'];
		unset($object['email']);

		if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'  ])){
			$object['isSended'] = 1;
		}

		try{
			$this->start_d();

			$oldObj = parent::get_d($object['id']);

			parent::edit_d($object,true);

			$this->commit_d();

			//���²�����־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object,'�༭����' );

			//�����ʼ� ,������Ϊ�ύʱ�ŷ���
			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
				$this->thisMail_d($emailArr,$object,'�޸�');
			}

			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		};
	}

	/**
	 * ����������ĵ���״̬
	 */
	 function changeStatus( $income , $rsType = 'act'){
		if($rsType == 'act'){
			if( $income['allotAble'] == 0 ){
				$income['status'] = 'DKZT-YFP' ;
				//�رյ����ʼ�
				$mailRecordDao = new model_finance_income_mailrecord();
				$mailRecordDao->closeMailrecordByIncomeId_d($income['id']);
		 	}else if( $income['allotAble'] == $income['incomeMoney']){
				$income['status'] = 'DKZT-WFP' ;
				//���������ʼ�
				$mailRecordDao = new model_finance_income_mailrecord();
				$mailRecordDao->closeMailrecordByIncomeId_d($income['id'],0);
		 	}else{
				$income['status'] = 'DKZT-BFFP' ;
				//�رյ����ʼ�
				$mailRecordDao = new model_finance_income_mailrecord();
				$mailRecordDao->closeMailrecordByIncomeId_d($income['id']);
		 	}
			parent::edit_d($income,true);
		}else{
			if( $income['allotAble'] == 0 ){
				return 'DKZT-YFP' ;
		 	}else if( $income['allotAble'] == $income['incomeMoney']){
				return 'DKZT-WFP' ;
		 	}else{
				return 'DKZT-BFFP' ;
		 	}
		}
	 }

	 /**
	  * ��ȡ����Ϣ�ʹӱ���Ϣ
	  */
	 function getInfoAndDetail_d($id){
		$rs = $this->get_d($id);
		$incomeAllotDao = new model_finance_income_incomeAllot();
		$rs['incomeAllot'] = $incomeAllotDao->getAllotsByIncomeId($id);

		return $rs;
	 }

	 /**
	  * ���÷�����ϸ
	  */
	 function initAllot_d($object,$perm = null ){
		if( empty($object) && $perm == 'view'){
			//û���򷵻��ַ���
			return '<tr align="center"><td colspan="6">û����ϸ��Ϣ</td></tr>';
		}else if( $perm == 'view' ){
			//�鿴����·��شӱ�ҳ��
			$incomeAllotDao = new model_finance_income_incomeAllot();
			return $incomeAllotDao->showIncomeAllotDetailView($object);
		}else if($perm == 'push'){
            //�༭״���·��شӱ�ҳ��
            $incomeAllotDao = new model_finance_income_incomeAllot();
            return $incomeAllotDao->showIncomeAllotDetailPush($object);
        }else{
			//�༭״���·��شӱ�ҳ��
			$incomeAllotDao = new model_finance_income_incomeAllot();
			return $incomeAllotDao->showIncomeAllotDetail($object);
		}
	 }

	 /**
	  * �������
	  */
	 function allot_d($object){
		try{
			$this->start_d();

			$oldObj = parent::get_d($object['id']);

		 	$incomeAllot = $object['incomeAllots'];
		 	unset($object['incomeAllots']);

			//����ӱ�
			$incomeAllotDao = new model_finance_income_incomeAllot();
			$incomeAllotDao->deleteAllotsByIncomeId($object['id']);
			if(!empty($incomeAllot)){
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $object['id'],'allotDate' => day_date));
			};
			//�ı�Դ��״̬
			$this->changeStatus($object);

			$this->commit_d();

			//���²�����־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object,'�������' );

			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	 }

	/**
	 * �ʼ�����
	 */
	function thisMail_d($emailArr,$object,$thisAct = '����'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = '���λ��' . $object['incomeUnitName'] . ',����� '.$object['incomeMoney'] . ' ��';
		$addMsg .= "\n��[�ռ���] ".$nameStr.' �ṩ�ñʵ���Ķ�Ӧ��ͬ�ż��ͻ����Ƹ����񣬲��ʼ��ظ��� '.$_SESSION['USERNAME'].'['.$_SESSION['EMAIL'].']���з���' ;

		$emailDao = new model_common_mail();
		$emailDao->batchEmail($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],$this->tbl_name,$thisAct,$object['incomeNo'],$emailArr['TO_ID'],$addMsg,'1',$emailArr['ADDIDS']);

		$mailRecordDao = new model_finance_income_mailrecord();
		$mailInfo = array(
			'incomeId' => $object['id'],
			'incomeCode' => $object['incomeNo'],
			'sendIds' => $emailArr['TO_ID'],
			'sendNames' => $emailArr['TO_NAME'],
			'copyIds' => $emailArr['ADDIDS'],
			'copyNames' => $emailArr['ADDNAMES'],
			'title' => '���',
			'content' => $addMsg,
			'times' => 1,
			'createOn' => date('Y-m-d h:i:s'),
			'lastMailTime' => date('Y-m-d h:i:s'),
		);
		$mailRecordDao->add_d($mailInfo);
	}

	/**
	 *  ��ȡĬ���ʼ�������
	 */
	function getSendMen_d(){
		include (WEB_TOR."model/common/mailConfig.php");
		$mailArr = isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : array('sendUserId'=>'',
				'sendName'=>'');
		return $mailArr;
	}

	/**
	 * ��ҳС�Ƽ���
	 * create by kuangzw
	 * create on 2012-5-22
	 */
	function pageCount_d($object){
		if(is_array($object)){
			$newArr = array(
				'incomeMoney'=>0
			);
			foreach($object as $key => $val){
				$newArr['incomeMoney'] = bcadd($newArr['incomeMoney'],$val['incomeMoney'],2);
			}
			$newArr['incomeNo'] = '��ҳС��';
			$newArr['id'] = 'noId';
			$object[] = $newArr;
			return $object;
		}
	}

	/**
	 *ɾ������
	 */
	function deletes_d($ids) {
		try {
			$this->start_d ();
			$idArr = explode ( ",", $ids );
			$customerTable = $this->tbl_name;
			$logSettringDao = new model_syslog_setting_logsetting ();
			foreach ( $idArr as $id ) {
				//ɾ�����������־
				$logSettringDao->deleteObjLog ( $this->tbl_name, parent::get_d ( $id ) );

				$this->deleteByPk ( $id );
			}
			$this->commit_d ();
			return true;
		} catch ( Exception $e ) {
			$this->rollBack ();
			//echo $e->message;
			throw $e;
		}
	}

	/**************************************����벿��*********************************/

	/**
	 * ����빦��
	 */
	function addExecelData_d($isCheck = 1){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$contArr = array();//��ͬ��Ϣ����
		$contDao = new model_projectmanagent_order_order();
		$customerArr = array();//�ͻ���Ϣ����
		$customerDao = new model_customer_customer_customer();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){
				if($isCheck == 1) $status = 'DKZT-YFP';
				else $status = 'DKZT-WFP';
				//������ѭ��
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$val[1] = trim($val[1]);
					$val[2] = str_replace( ' ','',$val[2]);
					$val[3] = str_replace( ' ','',$val[3]);
					$actNum = $key + 2;
					if(empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3])){
						continue;
					}else{
						if(!empty($val[0])){
							//�жϵ�������
							$incomeDate = date('Y-m-d',(mktime(0,0,0,1, $val[0] - 1 , 1900)));
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û���տ�����';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if($isCheck == 1){
							if(!empty($val[2])){
								//������ͬ��������
								$contArr[$val[2]] = isset($contArr[$val[2]]) ? $contArr[$val[2]] : $contDao->allOrderInfo($val[2]);
								if(is_array($contArr[$val[2]])){
									$orderCode = $val[2];
									$orderId = $contArr[$val[2]]['orgid'];
									if($val[2] == $contArr[$val[2]]['orderCode']){
										$orderType = $this->changeContType_d($contArr[$val[2]]['tablename'],1);
									}else{
										$orderType = $this->changeContType_d($contArr[$val[2]]['tablename'],2);
									}
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ��';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!û�к�ͬ��';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$orderCode = $val[2];
							$orderId = $orderType = "";
						}

						if(!empty($val[1])){
							//�ͻ�����
							if(!isset($customerArr[$val[1]])){
								$rs = $customerDao->findCus($val[1]);
								if(is_array($rs)){
									$customerId = $customerArr[$val[1]]['id'] = $rs[0]['id'];
									$prov = $customerArr[$val[1]]['prov'] = $rs[0]['Prov'];
									$customerName = $val[1];
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!�ͻ�ϵͳ�в����ڴ˿ͻ�';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$customerId = $customerArr[$val[1]]['id'];
								$prov = $customerArr[$val[1]]['prov'];
								$customerName = $val[1];
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û�пͻ�����';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if(!empty($val[3])&& $val[3]*1 == $val[3]&&$val[3]!=0){
							//�жϵ�����
							$incomeMoney = $val[3];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û�е�������ߵ�����Ϊ0';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if($isCheck == 1){
							$inArr = array(
								'incomeDate' => $incomeDate,
								'incomeUnitName' => $customerName,
								'incomeMoney' => $incomeMoney,
								'allotAble' => 0,
								'incomeUnitId' => $customerId,
								'contractName' => $customerName,
								'contractId' => $customerId,
								'province' => $prov,
								'remark' => 'ϵͳ��������',
								'formType' => 'YFLX-DKD',
								'status' => $status,
								'sectionType' => 'DKLX-HK',
								'incomeAllots' => array(
									array(
										'objId' => $orderId,
										'objCode' => $orderCode,
										'objType' => $orderType,
										'money' => $incomeMoney,
										'allotDate' => day_date
									)
								)
							);
						}else{
							if(empty($orderCode)){
								$inArr = array(
									'incomeDate' => $incomeDate,
									'incomeUnitName' => $customerName,
									'incomeMoney' => $incomeMoney,
									'allotAble' => 0,
									'incomeUnitId' => $customerId,
									'contractName' => $customerName,
									'contractId' => $customerId,
									'province' => $prov,
									'remark' => 'ϵͳ��������',
									'formType' => 'YFLX-DKD',
									'status' => $status,
									'sectionType' => 'DKLX-HK'
								);

							}else{
								$inArr = array(
									'incomeDate' => $incomeDate,
									'incomeUnitName' => $customerName,
									'incomeMoney' => $incomeMoney,
									'allotAble' => 0,
									'incomeUnitId' => $customerId,
									'contractName' => $customerName,
									'contractId' => $customerId,
									'province' => $prov,
									'remark' => 'ϵͳ��������',
									'formType' => 'YFLX-DKD',
									'status' => 'DKZT-YFP',
									'sectionType' => 'DKLX-HK',
									'incomeAllots' => array(
										array(
											'objId' => $orderId,
											'objCode' => $orderCode,
											'objType' => $orderType,
											'money' => $incomeMoney,
											'allotDate' => day_date
										)
									)
								);
							}
						}
						if($this->adForExcel_d($inArr)){
							$tempArr['result'] = '����ɹ�';
							$tempArr['docCode'] = '��' . $actNum .'������';
						}else{
							$tempArr['result'] = '����ʧ��';
							$tempArr['docCode'] = '��' . $actNum .'������';
						}
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}
	}

	/**
	 * ��ͬ����ת��
	 */
	function changeContType_d($val,$thisType){
		if($thisType == 1){
			switch($val){
				case 'oa_sale_order': return 'KPRK-01';break;
				case 'oa_sale_service': return 'KPRK-03';break;
				case 'oa_sale_lease': return 'KPRK-05';break;
				case 'oa_sale_rdproject': return 'KPRK-07';break;
			}
		}else{
			switch($val){
				case 'oa_sale_order': return 'KPRK-02';break;
				case 'oa_sale_service': return 'KPRK-04';break;
				case 'oa_sale_lease': return 'KPRK-06';break;
				case 'oa_sale_rdproject': return 'KPRK-08';break;
			}
		}
	}

	/**
	 * ��ӵ���������
	 */
	function adForExcel_d($income) {
		$codeRuleDao = new model_common_codeRule();
		$emailArr = null;
		try {
			$this->start_d();

			$incomeAllot = $income['incomeAllots'];
		 	unset($income['incomeAllots']);

			//�Զ����������
			if($income['formType'] == 'YFLX-TKD'){
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name . '_t','DL','ST');
			}else{
				$income['incomeNo'] = $codeRuleDao->financeCode($this->tbl_name,'DL','SK');
			}

			$incomeId = parent :: add_d($income, true);

			if(is_array($incomeAllot)){
				$incomeAllotDao = new model_finance_income_incomeAllot();
				$incomeAllotDao->createBatch($incomeAllot ,array('incomeId' => $incomeId));
			}

			$this->commit_d();
			return $incomeId;
		} catch (exception $e) {
			$this->rollBack();
			return null;
		}
	}
}
?>