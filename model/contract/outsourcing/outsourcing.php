<?php
/**
 * @author Show
 * @Date 2011��12��3�� ������ 10:29:00
 * @version 1.0
 * @description:�����ͬ Model��
 */
class model_contract_outsourcing_outsourcing extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_outsourcing";
		$this->sql_map = "contract/outsourcing/outsourcingSql.php";
		parent::__construct ();
    }

    public $datadictFieldArr = array('outsourcing','payType','projectType','outsourceType');

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

    //�Ƿ�
    function rtYesOrNo_d($value){
		if($value == 1){
			return '��';
		}else{
			return '��';
		}
    }

    //ǩ��״̬
    function rtIsSign_d($value){
		if($value == 1){
			return '��ǩ��';
		}else{
			return 'δǩ��';
		}
    }

    //��ͬ״̬
    function rtStatusKey_d($value){
    	$rt = null;
		switch($value){
			case 'δ�ύ' : $rt = 0;break;
			case '������' : $rt = 1;break;
			case 'ִ����' : $rt = 2;break;
			case '�ѹر�' : $rt = 3;break;
			case '�����' : $rt = 4;break;
			case '�ر�������' : $rt = 5;break;
			default : $rt = false;
		}
		return $rt;
    }

    /**
     * SQL����
     */
    function initSetting_c($thisVal){
		switch($thisVal){
			case '1' : return 'larger';break;
			case '2' : return 'largerEqu';break;
			case '3' : return 'equ';break;
			case '4' : return 'lessEqu';break;
			case '5' : return 'less';break;
			default : return 'noThisSetting';
		}
    }

	/************************* ��ɾ�Ĳ� ***********************/
	/**
	 * ��д��������
	 */
	function add_d($object){
//		echo "<pre>";
//		print_r($object);
//		$projectrentalDao = new model_contract_outsourcing_projectrental();
//		$projectRental = $projectrentalDao->dataFormat_d($object['projectRental']);//��ʽ������,ת����������
//		print_r($projectRental);
//		die();

// 		//ҵ��ǰǩԼ��λ��Ϣ����
// 		$signCompanyDao = new model_contract_signcompany_signcompany();
// 		$signCompanyArr = array(
// 			'signCompanyName' => $object['signCompanyName'],
// 			'proName' => $object['proName'],
// 			'proCode' => $object['proCode'],
// 			'phone' => $object['phone'],
// 			'address' => $object['address'],
// 			'linkman' => $object['linkman']
// 		);
// 		$signCompanyDao->saveCompanyInfo_d($signCompanyArr);

		//��ȡ����������Ϣ
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);
		//��Ա����ȡ��
		$items = $object['items'];
		unset($object['items']);
		//��Ŀ����ȡ��
		$projectRental = $object['projectRental'];
		unset($object['projectRental']);

		try{
			$this->start_d();//��������

			//ҵ�������ɲ���
			$deptDao = new model_deptuser_dept_dept();
			$dept = $deptDao->getDeptByUserId($object['principalId']);
			$orderCodeDao = new model_common_codeRule ();
			$object['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

			if(ORDERCODE_INPUT == 1) $object['orderCode'] = $object['objCode'];//���ϵͳ���ɱ��,���ͬ�ŵ���ҵ����

			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			$object['ExaStatus'] = WAITAUDIT;
			$object['status'] = 0;

			//���ø���
			$newId = parent :: add_d($object,true);

			if($object['isNeedPayapply']){//���븶��������Ϣ
				$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
				$payapplyInfo['contractId'] = $newId;
				$payapplyInfo['contractType'] = $this->tbl_name;
				$payapplyInfoDao->dealInfo_d($payapplyInfo);
			}
//			if ($items && $object['outsourcing'] == 'HTWBFS-02') {//��Ա����
//				$personrentalDao = new model_contract_personrental_personrental();
//				$items = util_arrayUtil :: setArrayFn(array ('mainId' => $newId), $items ,array('personLevelName'));
//				$personrentalDao->saveDelBatch($items);
//			}else{//����/�ְ�
//				$projectrentalDao = new model_contract_outsourcing_projectrental();
//				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//��ʽ������,ת����������
//				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $newId), $projectRental);
//				$projectrentalDao->saveDelBatch($projectRental);
//			}

			//���¸���������ϵ
			$this->updateObjWithFile($newId,$object['orderCode']);

			$this->commit_d();
			return $newId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д�޸ķ���
	 */
	function edit_d($object){
		//���״̬�ֶ������ֵ䴦��
		if(isset($object['outsourceType']) && !empty($object['outsourceType'])){
			//�����ֵ䴦��
			$object = $this->processDatadict($object);
		}
		return parent::edit_d($object,true);
	}

	/**
	 * �༭����
	 */
	function editInfo_d($object){
//		echo "<pre>";
//		print_r($object);
//		$projectrentalDao = new model_contract_outsourcing_projectrental();
//		$projectRental = $projectrentalDao->dataFormat_d($object['projectRental']);//��ʽ������,ת����������
//		print_r($projectRental);
//		die();

		//��ȡ����������Ϣ
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);
		//��Ա����ȡ��
		$items = $object['items'];
		unset($object['items']);
		//��Ŀ����ȡ��
		$projectRental = $object['projectRental'];
		unset($object['projectRental']);

		try{
			$this->start_d();

			//���״̬�ֶ������ֵ䴦��
			if(isset($object['outsourceType']) && !empty($object['outsourceType'])){
				//�����ֵ䴦��
				$object = $this->processDatadict($object);
			}

			$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
			//������Ϣ����
			if($object['isNeedPayapply'] == 1){
				//���¸���������Ϣ
				$payapplyInfo['contractId'] = $object['id'];
				$payapplyInfo['contractType'] = $this->tbl_name;
				$payapplyInfoDao->dealInfo_d($payapplyInfo);
			}else{
				$object['isNeedPayapply'] = 0;
				//ɾ������������Ϣ
				$payapplyInfoDao->delete(array('contractId' =>$object['id'],'contractType'=> $this->tbl_name));
			}

			parent::edit_d($object,true);

//			$personrentalDao = new model_contract_personrental_personrental();//��Ա����ʵ��
//			$projectrentalDao = new model_contract_outsourcing_projectrental();//����ʵ��
//			if($object['outsourcing'] == "HTWBFS-02"){//��Ա����
//				$items = util_arrayUtil :: setArrayFn(array ('mainId' => $object['id']), $items ,array('personLevelName'));
//				$personrentalDao->saveDelBatch($items);
//
//				$projectrentalDao->delItemInfo_d($object['id']);//ɾ��������Ϣ
//			}else{//����/�ְ�
//				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//��ʽ������,ת����������
//				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $object['id']), $projectRental);
//				$projectrentalDao->saveDelBatch($projectRental);
//
//				$personrentalDao->delItemInfo_d($object['id']);//ɾ����Ա������Ϣ
//			}
			$this->commit_d();
			return $object['id'];
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��дɾ������
	 */
	function delete_d($id){
		try{
			$this->start_d();

			//�޸��������״̬
			$arr = $this->find(array('id' => $id ));
			if(!empty($arr['approvalId'])){
				if($arr['outsourcing'] == 'HTWBFS-01'){
					$this->changeIsAddContract_d(0, $arr['approvalId'], 0);
				}else{
					$this->changeIsAddContract_d(1, $arr['personrentalId'], 0);
				}
			}

			//ɾ������
			$this->delete(array('id' => $id));

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ��ȡ��ͬ��Ϣ��������Ϣ
	 */
	function getInfo_d($id){
		$obj = parent::get_d($id);

		$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
		$payapplyArr = $payapplyInfoDao->getPayapplyInfo_d($id,$this->tbl_name);

		$obj['outPayType'] = $obj['payType'];
		$obj['outRemark'] = $obj['remark'];

		$obj = array_merge($obj,$payapplyArr);
		return $obj;
	}

	/**
	 *��ȡ�����ͬ����Ϣ
	 */
	function getInfoProject_d ($projectId){
		$projectDao = new model_engineering_project_esmproject();
		$obj = $projectDao->get_d($projectId);
		return $obj;
	}

	/**
	 * �������������Ϣ
	 */
	function stamp_d($obj){
		$stampDao = new model_contract_stamp_stamp();
		try{
			$this->start_d();

			//��ȡ��Ӧ�����������κ�
			$maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name," contractType = 'HTGZYD-01' and contractId=". $obj['contractId'],"max(batchNo)");
			$obj['batchNo'] = $maxBatchNo + 1;

			//����������Ϣ
			$obj['contractType'] = 'HTGZYD-01';
			$stampDao->addStamps_d($obj,true);

			//���º�ͬ�ֶ���Ϣ
			$this->edit_d(array('id' => $obj['contractId'],'isNeedStamp' => 1,'stampType' => $obj['stampType'],'isStamp' => 0));

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *�����ɹ����ڸ����б������Ϣ
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$object = $this->get_d($objId);
	 	if($object['isNeedStamp'] == "1" &&$object['ExaStatus'] == AUDITED){
	 		if($userId == $object['createId']){
	 			$userName = $object['createName'];
	 		}else{
	 			$userName = $object['principalName'];
	 		}
			//��ʼ��������
			$stampDao = new model_contract_stamp_stamp();
			$stampArr = $stampDao->find(array('contractId' => $object['id'],'contractType' => 'HTGZYD-01' ),null,'id');
			if(empty($stampArr)){
		 		//�������� - ģ���������
				$object = array ("contractId" => $object['id'],
					"contractCode" => $object['orderCode'],
					"contractType" =>  'HTGZYD-01',
					"contractName" => $object['orderName'],
					"signCompanyName" => $object['signCompanyName'],
					"signCompanyId" => $object['signCompanyId'],
					"objCode" => $object['objCode'],
					"contractMoney" => $object['orderMoney'],
					"applyUserId" => $userId,
					"applyUserName" => $userName,
					"applyDate" => day_date,
					"stampType" => $object['stampType'],
					"status" => 0
				);
				$stampDao->addStamps_d($object);
			}
			return 1;
	 	}
	 	return 1;
	}

	/**
	 *�����ͬ��������������ɹ�����
	 */
	function dealAfterAuditPayapply_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$object = $this->getInfo_d($objId);
	 	if($object['ExaStatus'] == AUDITED){

			try{
				$this->start_d();

				//��Ҫ�������趨
		 		if($userId == $object['createId']){
		 			$userName = $object['createName'];
		 		}else{
		 			$userName = $object['principalName'];
		 		}

				//�����Ҫ����
		 		if($object['isNeedStamp'] == "1"){
					$stampDao = new model_contract_stamp_stamp();
					$stampArr = $stampDao->find(array('contractId' => $object['id'],'contractType' => 'HTGZYD-01' ),null,'id');
					if(empty($stampArr)){
				 		//��������
						$stampArr = array ("contractId" => $object['id'],
							"contractCode" => $object['orderCode'] ,
							"contractType" =>  'HTGZYD-01',
							"contractName" => $object['orderName'],
							"signCompanyName" => $object['signCompanyName'],
							"signCompanyId" => $object['signCompanyId'],
							"objCode" => $object['objCode'],
							"contractMoney" => $object['orderMoney'],
							"applyUserId" => $userId,
							"applyUserName" => $userName,
							"applyDate" => day_date,
							"stampType" => $object['stampType'] ,
							"status" => 0
						);
						$stampDao->addStamps_d($stampArr);
					}
		 		}

				//�������봦��
				$payablesapplyDao = new model_finance_payablesapply_payablesapply();
				//����������������
				$payablesapplyArr = array(
					'deptName' => $object['deptName'],
					'deptId' => $object['deptId'],
					'salesman' => $userName,
					'salesmanId' => $userId,
					'supplierName' => $object['signCompanyName'],
					'payMoney' => $object['applyMoney'],
					'payMoneyCur' => $object['applyMoney'] * $object['rate'],
					'payDate' => $object['formDate'],
					'formDate' => day_date,
					'feeDeptName' => $object['feeDeptName'],
					'feeDeptId' => $object['feeDeptId'],
					'sourceCode' => $object['orderCode'],
					'bank' => $object['bank'],
					'account' => $object['account'],
					'payFor' => $object['payFor'],
					'payType' => $object['payType'],
					'remark' => $object['remark'],
					'payCondition' => $object['payCondition'],
					'sourceType' => 'YFRK-03',
					'ExaStatus' => '���',
					'ExaDT' => day_date,
					'exaId' => $object['id'],
					'exaCode' => $this->tbl_name,
					'ExaUser' => $folowInfo['USER_NAME'],
					'ExaUserId' => $folowInfo['USER_ID'],
					'ExaContent' => $folowInfo['content'],
					'payDesc' => $object['payDesc'],
					'isEntrust' => $object['isEntrust'],
					'currency' => $object['currency'],
					'currencyCode' => $object['currencyCode'],
					'place' => $object['place'],
					'rate' => $object['rate'],
					'businessBelong' => $object['businessBelong'],
					'businessBelongName' => $object['businessBelongName'],
					'formBelong' => $object['formBelong'],
					'formBelongName' => $object['formBelongName'],
					'detail' => array(
						0 => array(
							'money' => $object['applyMoney'],
							'objId' => $object['id'],
							'objCode' => $object['orderCode'],
							'objType' => 'YFRK-03',
							'purchaseMoney' => $object['orderMoney'],
							'payDesc' => '�����Ŀ�������������',
							'expand1' =>  $object['outsourceTypeName'],
							'expand2' =>  $object['projectCode'],
							'expand3' =>  $object['projectId'],
							'orgFormType' =>  $object['projectName']
						)
					)
				);
				$payablesapplyArr ['createId'] = $payablesapplyArr ['updateId'] = $userId;
				$payablesapplyArr ['createName'] = $payablesapplyArr ['updateName'] = $userName;
				$payablesapplyArr ['createTime'] = $payablesapplyArr ['updateTime'] =date ( "Y-m-d H:i:s" );

				$payablesapplyDao->addOnly_d($payablesapplyArr);

				$this->commit_d();
			}catch(Exception $e){
				$this->rollBack();
			}

			return 1;
	 	}
	 	return 1;
	}

	/**
	 * ��ҳС�ƴ���
	 */
	function pageCount_d($object){
		if(is_array($object)){
			//С�Ƴ�ʼ���
			$newArr = array(
				'allCount' => 0,'applyedMoney'=>0,'payedMoney'=>0 ,'orderMoney' => 0,
				'initPayMoney' => 0,'initInvoiceMoney' => 0,'orgApplyedMoney' => 0,'orgPayedMoney' => 0,'orgAllCount' => 0
			);

			foreach($object as $key => $val){
				$newArr['allCount'] = bcadd($newArr['allCount'],$val['allCount'],2);
				$newArr['applyedMoney'] = bcadd($newArr['applyedMoney'],$val['applyedMoney'],2);
				$newArr['payedMoney'] = bcadd($newArr['payedMoney'],$val['payedMoney'],2);
				$newArr['orderMoney'] = bcadd($newArr['orderMoney'],$val['orderMoney'],2);
				$newArr['initPayMoney'] = bcadd($newArr['initPayMoney'],$val['initPayMoney'],2);
				$newArr['initInvoiceMoney'] = bcadd($newArr['initInvoiceMoney'],$val['initInvoiceMoney'],2);
				$newArr['orgApplyedMoney'] = bcadd($newArr['orgApplyedMoney'],$val['orgApplyedMoney'],2);
				$newArr['orgPayedMoney'] = bcadd($newArr['orgPayedMoney'],$val['orgPayedMoney'],2);
				$newArr['orgAllCount'] = bcadd($newArr['orgAllCount'],$val['orgAllCount'],2);
			}
			$newArr['createDate'] = '��ҳС��';
			$newArr['id'] = 'noId';
			$object[] = $newArr;
			return $object;
		}
	}

	/**************** S ���뵼��Щ�� ************************/

	/**
	 * ���������Ŀ����
	 */
	function addExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$contractArr = array();//��ͬ��������
		$otherDataDao = new model_common_otherdatas();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$provinceArr = array();//ʡ������
		$provinceDao = new model_system_procity_province();
		$esmprojectArr = array();//������Ŀ����
		$esmprojectDao = new model_engineering_project_esmproject();
		$rdprojectArr = array();//�з���Ŀ����
		$rdprojectDao = new model_rdproject_project_rdproject();
		$deptDao = new model_deptuser_dept_dept();
		$orderCodeDao = new model_common_codeRule ();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream" || $fileType == "application/kset") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
//			print_r($excelData);
			if(is_array($excelData)){
				//������ѭ��
				foreach($excelData as $key => $val){
					$val[0] = str_replace( ' ','',$val[0]);
					$actNum = $key + 2;
					$updateArr = array();

					//���������Ǹ�������
					//1Ϊ����
					//0Ϊ����
					$addOrUpdate = 1;

					//�������
					if(!empty($val[0])){
						$val[0] = trim($val[0]);
						if(!isset($datadictArr[$val[0]])){
							$rs = $datadictDao->getCodeByName('HTWB',$val[0]);
							if(!empty($rs)){
								$outsourceType = $datadictArr[$val[0]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�����ڵ��������';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$outsourceType = $datadictArr[$val[0]]['code'];
						}
						$updateArr['outsourceType'] = $outsourceType;
						$updateArr['outsourceTypeName'] = $val[0];
					}

					//��ͬǩԼ����
					if(!empty($val[1])&& $val[1] != '0000-00-00'){
						$val[1] = trim($val[1]);

						if(!is_numeric($val[1])){
							$updateArr['signDate'] = $val[1];
						}else{
							$actEndDate = date('Y-m-d',(mktime(0,0,0,1, $val[1] - 1 , 1900)));
							$updateArr['signDate'] = $actEndDate;
						}
					}else{
						$updateArr['signDate'] = day_date;
					}

					//��Ŀ����
					if(!empty($val[2])){
						$updateArr['projectName'] = $val[2];
					}

					//��Ŀ���
					if($outsourceType != 'HTWB01'){
						if(!empty($val[3])){
							$updateArr['projectCode'] = $projectCode = $val[3];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û����д��Ŀ���';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//�з�
						if($outsourceType == 'HTWB02'){
							if(!isset($rdprojectArr[$val[3]])){
								$rs = $rdprojectDao->getProjectInfo_d($val[3]);
								if(empty($rs)){
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!�����ڵ��з���Ŀ['.$val[3].']������������Ŀ��Ϣ';
									array_push( $resultArr,$tempArr );
									continue;
								}else{
									$rdprojectArr[$val[3]] = $rs;
								}
							}
							$updateArr['projectId'] = $rdprojectArr[$val[3]]['id'];
						}else if($outsourceType == 'HTWB03'){//������Ŀ
							if(!isset($esmprojectArr[$val[3]])){
								$rs = $esmprojectDao->getProjectInfo_d($val[3]);
								if(empty($rs)){
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '����ʧ��!�����ڵĹ�����Ŀ['.$val[3].']������������Ŀ��Ϣ';
									array_push( $resultArr,$tempArr );
									continue;
								}else{
									$esmprojectArr[$val[3]] = $rs;
								}
							}
							$updateArr['projectId'] = $esmprojectArr[$val[3]]['id'];
							$updateArr['projectType'] = $esmprojectArr[$val[3]]['category'];

							//������Ŀ����
							if(!empty($updateArr['projectType'])){
								$updateArr['projectType'] = trim($updateArr['projectType']);
								if(!isset($datadictArr[$updateArr['projectType']])){
									$rs = $datadictDao->getCodeByName('XMLB',$updateArr['projectType']);
									if(!empty($rs)){
										$outsourcing = $datadictArr[$updateArr['projectType']]['code'] = $rs;
									}else{
										$tempArr['docCode'] = '��' . $actNum .'������';
										$tempArr['result'] = '����ʧ��!�����ڵ���Ŀ���';
										array_push( $resultArr,$tempArr );
										continue;
									}
								}else{
									$outsourcing = $datadictArr[$updateArr['projectType']]['code'];
								}
								$updateArr['projectTypeName'] = $updateArr['projectType'];
							}
						}
					}

					//������ͬ��
					if(!empty($val[4])){
						$updateArr['orderCode'] = $val[4];
						if(!isset($contractArr[$updateArr['orderCode']])){
							$rs = $this->find(array('orderCode' => $updateArr['orderCode']),null,'id');
							if(is_array($rs)){
								$addOrUpdate = 0;
							}
						}else{
							$addOrUpdate = 0;
						}
					}

					//��ͬ����
					if(!empty($val[5])){
						$updateArr['orderName'] = $val[5];
					}

					//�����˾����
					if(!empty($val[6])){
						$updateArr['signCompanyName'] = $val[6];
						$basicinfoDao = new model_outsourcing_supplier_basicinfo();
						$basicinfoArr = $basicinfoDao->find(array('suppName' => $val[6]),null,'id');
						if(!empty($basicinfoArr['id'])){
							$updateArr['signCompanyId'] = $basicinfoArr['id'];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!û�ж�Ӧ�Ĺ�Ӧ��';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}

					//ʡ��
					if(!empty($val[7])){
						if(!isset($provinceArr[$val[7]])){
							$provinceCode = $provinceDao->getCodeByName($val[7]);
							if(!empty($provinceCode)){
								$provinceArr[$val[7]]['provinceCode'] = $provinceCode;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!û�ж�Ӧ��ʡ��';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						$updateArr['proCode'] = $provinceArr[$val[7]]['provinceCode'];
						$updateArr['proName'] = $val[7];
					}

					//�����ͬ��
					if(!empty($val[8])){
						$updateArr['outContractCode'] = $val[8];
					}

					//�����ʽ
					if(!empty($val[9])){
						$val[9] = trim($val[9]);
						if(!isset($datadictArr[$val[9]])){
							$rs = $datadictDao->getCodeByName('HTWBFS',$val[9]);
							if(!empty($rs)){
								$outsourcing = $datadictArr[$val[9]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�����ڵ��������';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$outsourcing = $datadictArr[$val[9]]['code'];
						}
						$updateArr['outsourcing'] = $outsourcing;
						$updateArr['outsourcingName'] = $val[9];
					}

					//��ͬ������
					if(!empty($val[10])){
						$val[10] = trim($val[10]);
						if(!isset($userArr[$val[10]])){
							$rs = $otherDataDao->getUserInfo($val[10]);
							if(!empty($rs)){
								$userArr[$val[10]] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ������';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}
						$updateArr['principalId'] = $userArr[$val[10]]['USER_ID'];
						$updateArr['deptId'] = $userArr[$val[10]]['DEPT_ID'];
						$updateArr['deptName'] = $userArr[$val[10]]['DEPT_NAME'];
						$updateArr['principalName'] = $val[10];

						$deptCode = $userArr[$val[10]]['DEPT_CODE'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û��¼���ͬ������';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//���ʽ
					if(!empty($val[11])){
						$val[11] = trim($val[11]);
						if(!isset($datadictArr[$val[11]])){
							$rs = $datadictDao->getCodeByName('HTFKFS',$val[11]);
							if(!empty($rs)){
								$payType = $datadictArr[$val[11]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '����ʧ��!�����ڵĸ��ʽ';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$payType = $datadictArr[$val[11]]['code'];
						}
						$updateArr['payType'] = $payType;
						$updateArr['payTypeName'] = $val[11];
					}

					//��ʼ����
					if(!empty($val[13])&& $val[13] != '0000-00-00'){
						$val[13] = trim($val[13]);

						if(!is_numeric($val[13])){
							$updateArr['beginDate'] = $val[13];
						}else{
							$actEndDate = date('Y-m-d',(mktime(0,0,0,1, $val[13] - 1 , 1900)));
							$updateArr['beginDate'] = $actEndDate;
						}
					}

					//��������
					if(!empty($val[14])&& $val[14] != '0000-00-00'){
						$val[14] = trim($val[14]);

						if(!is_numeric($val[14])){
							$updateArr['endDate'] = $val[14];
						}else{
							$actEndDate = date('Y-m-d',(mktime(0,0,0,1, $val[14] - 1 , 1900)));
							$updateArr['endDate'] = $actEndDate;
						}
					}

					//��ʼ����ϼƺͿ�Ʊ���
					$updateArr['orderMoney'] = empty($val[12]) ? 0 : sprintf("%f",abs(trim($val[12])));
					$updateArr['initPayMoney'] = empty($val[15]) ? 0 : sprintf("%f",abs(trim($val[15])));
					$updateArr['initInvoiceMoney'] = empty($val[16]) ? 0 : sprintf("%f",abs(trim($val[16])));

					//��Ŀ״̬
					if($val[17]){
						$rt = $this->rtStatusKey_d($val[17]);
						if($rt){
							$updateArr['status'] = $rt;
							if($val[17] == 'δ�ύ'){
								$updateArr['ExaStatus'] = '���ύ';
							}else{
								$updateArr['ExaStatus'] = '���';
							}
							$updateArr['ExaDT'] = day_date;
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�����ڵĺ�ͬ״̬';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$updateArr['status'] = 0;
					}

					//��ϵ��
					if(!empty($val[18])){
						$updateArr['linkman'] = $val[18];
					}

					//��ϵ�绰
					if(!empty($val[19])){
						$updateArr['phone'] = $val[19];
					}

					//��ϵ��ַ
					if(!empty($val[20])){
						$updateArr['address'] = $val[20];
					}

					//������˾
					if(!empty($val[21])){
						$branchDao = new model_deptuser_branch_branch();
						$branchObj = $branchDao->find(array('NameCN' => $val[21]));
						if(!empty($branchObj)){
							$updateArr['businessBelongName'] = $val[21];
							$updateArr['businessBelong'] = $branchObj['NamePT'];
							$updateArr['formBelong'] = $branchObj['NamePT'];
							$updateArr['formBelongName'] = $branchObj['NameCN'];
						}
						else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '����ʧ��!�����ڵĹ�����˾';
							array_push( $resultArr,$tempArr );
							continue;
						}
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '����ʧ��!û��¼�������˾';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//���ݴ�����
					if($addOrUpdate == 1){
						//ҵ���Ŵ���
						$updateArr['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$deptCode);
						//������Ŀ����
						$newId = parent::add_d($updateArr,true);
						if($newId){
							$tempArr['result'] = '����ɹ�';
							$tempArr['docCode'] = '��' . $actNum .'������';
						}else{
							$tempArr['result'] = '����ʧ��';
							$tempArr['docCode'] = '��' . $actNum .'������';
						}
					}else{
						//������Ŀ����
						$this->update(array('orderCode' => $updateArr['orderCode']),$updateArr);
						$tempArr['result'] = '�������ݳɹ�';
						$tempArr['docCode'] = '��' . $actNum .'������';
					}

//					echo "<pre>";
//					print_r($updateArr);

					array_push( $resultArr,$tempArr );
				}
				return $resultArr;
			} else {
				msg( "�ļ������ڿ�ʶ������!");
			}
		} else {
			msg( "�ϴ��ļ����Ͳ���EXCEL!");
		}

	}

	/**************** E ���뵼��ϵ�� ************************/

	/***************** S ���ϵ�� *********************/
	/**
	 * �������
	 */
	function change_d($object){
		try{
			$this->start_d();

			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			//ʵ���������
			$changeLogDao = new model_common_changeLog ( 'outsourcing' );

//			echo "<pre>";
//			print_r($object);

			//��������
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//���������Ϣ
			$tempObjId = $changeLogDao->addLog ( $object );

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���������ɺ���º�ͬ״̬
	 */
	function dealAfterAuditChange_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid );
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$obj = $this->get_d($objId);
 		try{
 			$this->start_d();

			//�����Ϣ����
	 		$changeLogDao = new model_common_changeLog ( 'outsourcing' );
			$changeLogDao->confirmChange_d ( $obj );

 			if($obj['ExaStatus'] == AUDITED){
				//Դ��״̬����
				if($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1){//��Ҫ���¸���
					//ֱ�����ø���״̬λ�������и��¼�¼�ر�
					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

				}elseif($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0){//���ڸ��µĴ���

					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

					$stampDao = new model_contract_stamp_stamp();
					$newId = $stampDao->closeWaiting_d($obj['originalId'],'HTGZYD-01');
				}else{//�Ǹ��´���
					$this->update(array('id'=>$obj['originalId']),array('status' => 2,'isNeedRestamp' => 0));
				}
 			}else{
            	$this->update(array('id'=>$obj['originalId']),array('status' => 2));
 			}

 			$this->commit_d();
 		}catch(Exception $e){
 			$this->rollBack();
 		}
	 	return 1;
	}
	/***************** E ���ϵ�� *********************/

	/***************** S ǩ��ϵ�� *********************/
	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function sign_d($object){
		//ʵ���������
		$changeLogDao = new model_common_changeLog ( 'outsourcingSign' );
 		try{
 			$this->start_d();

 			//ԭ��ǩ��״̬����
 			$signInfo = array(
				'signedDate' => day_date,
				'signedStatus' => 1,
				'signedMan' => $_SESSION['USERNAME'],
				'signedManId' => $_SESSION['USER_ID'],
				'id' => $object['oldId']
 			);
 			parent::edit_d($signInfo,true);

			//���ݴ���
			$object = $this->processDatadict($object);

			//��������
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//���������Ϣ
			$tempObjId = $changeLogDao->addLog ( $object );

			$changeObj = $object;
			$changeObj['id'] = $tempObjId;
			$changeObj['originalId'] = $changeObj['oldId'];

			//���ȷ��
			$changeLogDao->confirmChange_d ( $changeObj );

 			$this->commit_d();
 			return $tempObjId;
 		}catch(Exception $e){
 			$this->rollBack();
 			return false;
 		}
	}

	/***************** E ǩ��ϵ�� *********************/

	/**
	 * �޸������������ɺ�ͬ��״̬
	 * @param unknown $type
	 * @param unknown $changeId
	 * @param unknown $status
	 */
	function changeIsAddContract_d($type,$changeId,$status){
		$approvalDao = new model_outsourcing_approval_basic();
		$personrentalDao = new model_outsourcing_approval_persronRental();
		if($type == 0){
			$approvalDao->update(array('id'=> $changeId),array('isAllAddContract' => $status));
		}else{
			$changeId = explode(',', $changeId);
			foreach ($changeId as $key =>$val){
				$personrentalDao->update(array('id'=> $val),array('isAddContract' => $status));
			}
			$arr=$personrentalDao->find(array('id' => $changeId[0]),null,mainId);
			$count=$personrentalDao->findCount(array('mainId' => $arr['mainId'],'isAddContract' => 0));
			if($count == 0){
				$approvalDao->update(array('id'=> $arr['mainId']),array('isAllAddContract' => 1));
			}else{
				$approvalDao->update(array('id'=> $arr['mainId']),array('isAllAddContract' => 0));
			}
		}
	}

	/**
	 * ��ȡ��ͬ��Ϣ - �����ֹ�˾
	 */
	function getList_d($ids){
		$this->setCompany(0);
		$this->searchArr = array('ids' => $ids);
		return $this -> list_d();
	}
}