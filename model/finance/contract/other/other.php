<?php
/**
 * @author Show
 * @Date 2011��12��5�� ����һ 10:19:51
 * @version 1.0
 * @description:������ͬ Model��
 */
class model_contract_other_other extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_other";
		$this->sql_map = "contract/other/otherSql.php";
		parent::__construct ();
    }

    //�����ֵ�
    public $datadictFieldArr = array('projectType','fundType');

	//����������
    private $relatedStrategyArr = array (
		'KXXZA' => 'model_finance_income_strategy_income', //���
		'KXXZB' => 'model_finance_income_strategy_prepayment', //Ԥ�տ�
		'KXXZC' => 'model_finance_income_strategy_refund' //�˿
	);

	private $relatedCode = array (
		'KXXZA' => 'income', //���
		'KXXZB' => 'pay', //Ԥ�տ�
		'KXXZC' => 'none' //�˿
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
	private $statusArr = array("δ�ύ","������","ִ����","�ѹر�","�����");

    /**
	 * ��Ӷ���
	 */
	function add_d($object) {

		//ҵ��ǰǩԼ��λ��Ϣ����
		$signCompanyDao = new model_contract_signcompany_signcompany();
		$signCompanyArr = array(
			'signCompanyName' => $object['signCompanyName'],
			'proName' => $object['proName'],
			'proCode' => $object['proCode'],
			'phone' => $object['phone'],
			'address' => $object['address'],
			'linkman' => $object['linkman']
		);
		$signCompanyDao->saveCompanyInfo_d($signCompanyArr);

//		echo "<pre>";
//		print_r($object);
		//��ȡ����������Ϣ
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);

		try{
			$this->start_d();//��������

			//ҵ�������ɲ���
			$deptDao = new model_deptuser_dept_dept();
			$dept = $deptDao->getDeptByUserId($object['principalId']);

			$orderCodeDao = new model_common_codeRule ();
			$object['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

			if(ORDERCODE_INPUT == 1){
				$object['orderCode'] = $object['objCode'];
			}

			$object['ExaStatus'] = WAITAUDIT;
			$object['status'] = 0;

			if(!empty($object['fundType'])){
				//���������ֵ��ֶ�
				$datadictDao = new model_system_datadict_datadict ();
				$object ['fundTypeName'] =  $datadictDao->getDataNameByCode ( $object['fundType'] );
			}

			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			//���ø���
			$newId = parent :: add_d($object,true);

			if($object['isNeedPayapply']){
				//���븶��������Ϣ
				$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
				$payapplyInfo['contractId'] = $newId;
				$payapplyInfo['contractType'] = $this->tbl_name;
				$payapplyInfoDao->dealInfo_d($payapplyInfo);
			}

			//���¸���������ϵ
			$this->updateObjWithFile($newId,$object['orderCode']);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��д�޸ķ���
	 */
	function edit_d($object){

		if(!empty($object['fundType'])){
			//���������ֵ��ֶ�
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
		//��ȡ����������Ϣ
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);
		try{
			$this->start_d();

			//���״̬�ֶ������ֵ䴦��
			if(isset($object['fundType']) && !empty($object['fundType'])){
				//���������ֵ��ֶ�
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

			$this->commit_d();
			return $object['id'];
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ȡ��ͬ��Ϣ��������Ϣ
	 */
	function getInfo_d($id){
		$obj = parent::get_d($id);

		$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
		$payapplyArr = $payapplyInfoDao->getPayapplyInfo_d($id,$this->tbl_name);

		$obj['otherFeeDeptName'] = $obj['feeDeptName'];
		$obj['otherFeeDeptId'] = $obj['feeDeptId'];

		$obj = array_merge($obj,$payapplyArr);

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
			$maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name," contractType = 'HTGZYD-02' and contractId=". $obj['contractId'],"max(batchNo)");
			$obj['batchNo'] = $maxBatchNo + 1;

			//����������Ϣ
			$obj['contractType'] = 'HTGZYD-02';
			$stampDao->addStamps_d($obj,true);

			//���º�ͬ�ֶ���Ϣ
			$this->edit_d(array('id' => $obj['contractId'],'isNeedStamp' => 1,'stampType' => $obj['stampType']));

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *�����ɹ����ڸ����б������Ϣ
	 */
	function dealAfterAudit_d($objId,$userId){
	 	$object = $this->get_d($objId);
	 	if($object['isNeedStamp'] == "1" &&$object['ExaStatus'] == AUDITED){
	 		if($userId == $object['createId']){
	 			$userName = $object['createName'];
	 		}else{
	 			$userName = $object['principalName'];
	 		}
	 		//��������
			$object = array (
				"contractId" => $object['id'],
				"contractCode" => ($object['orderCode']?$object['orderCode']:$object['orderTempCode']),
				"contractType" => 'HTGZYD-02',
				"objCode" => $object['objCode'],
				"contractName" => $object['orderName'],
				"signCompanyName" => $object['signCompanyName'],
				"signCompanyId" => $object['signCompanyId'],
				"contractMoney" => $object['orderMoney'],
				"applyUserId" => $userId,
				"applyUserName" => $userName,
				"applyDate" => day_date,
				"stampType" => $object['stampType'] ,
				"status" => 0
			);
			$stampDao = new model_contract_stamp_stamp();
			$stampDao->addStamps_d($object,true);
			return 1;
	 	}
	 	return 1;
	}

	/**
	 *������ͬ��������������ɹ�����
	 */
	function dealAfterAuditPayapply_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
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
			 		//��������
					$stampArr = array (
						"contractId" => $object['id'],
						"contractCode" => ($object['orderCode']?$object['orderCode']:$object['orderTempCode']),
						"contractType" => 'HTGZYD-02',
						"objCode" => $object['objCode'],
						"contractName" => $object['orderName'],
						"signCompanyName" => $object['signCompanyName'],
						"signCompanyId" => $object['signCompanyId'],
						"contractMoney" => $object['orderMoney'],
						"applyUserId" => $userId,
						"applyUserName" => $userName,
						"applyDate" => day_date,
						"stampType" => $object['stampType'] ,
						"status" => 0
					);
					$stampDao = new model_contract_stamp_stamp();
					$stampDao->addStamps_d($stampArr,true);
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
					'payDate' => $object['formDate'],
					'formDate' => day_date,
					'feeDeptName' => $object['feeDeptName'],
					'feeDeptId' => $object['feeDeptId'],
					'bank' => $object['bank'],
					'account' => $object['account'],
					'payFor' => $object['payFor'],
					'payType' => $object['payType'],
					'remark' => $object['remark'],
					'payCondition' => $object['payCondition'],
					'sourceType' => 'YFRK-02',
					'ExaStatus' => '���',
					'ExaDT' => day_date,
					'exaId' => $object['id'],
					'exaCode' => $this->tbl_name,
					'ExaUser' => $folowInfo['USER_NAME'],
					'ExaUserId' => $folowInfo['USER_ID'],
					'ExaContent' => $folowInfo['content'],
					'detail' => array(
						0 => array(
							'money' => $object['applyMoney'],
							'objId' => $object['id'],
							'objCode' => $object['orderCode'],
							'objType' => 'YFRK-02',
							'purchaseMoney' => $object['orderMoney'],
							'payDesc' => '������ͬ�������������'
						)
					)
				);
				$payablesapplyArr ['createId'] = $payablesapplyArr ['updateId'] = $userId;
				$payablesapplyArr ['createName'] = $payablesapplyArr ['updateName'] = $userName;
				$payablesapplyArr ['createTime'] = $payablesapplyArr ['updateTime'] =date ( "Y-m-d H:i:s" );

				$payablesapplyDao->addOnly_d($payablesapplyArr);

				$this->commit_d();
			}catch(exception $e){
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
			$newArr = array(
				'payApplyMoney' => 0,'payedMoney'=>0,'invotherMoney'=>0 ,
				'applyInvoice' => 0,'invoiceMoney'=>0,'incomeMoney'=>0 ,
				'orderMoney' => 0
			);
			foreach($object as $key => $val){
				$newArr['payApplyMoney'] = bcadd($newArr['payApplyMoney'],$val['payApplyMoney'],2);
				$newArr['payedMoney'] = bcadd($newArr['payedMoney'],$val['payedMoney'],2);
				$newArr['invotherMoney'] = bcadd($newArr['invotherMoney'],$val['invotherMoney'],2);
				$newArr['applyInvoice'] = bcadd($newArr['applyInvoice'],$val['applyInvoice'],2);
				$newArr['invoiceMoney'] = bcadd($newArr['invoiceMoney'],$val['invoiceMoney'],2);
				$newArr['incomeMoney'] = bcadd($newArr['incomeMoney'],$val['incomeMoney'],2);
				$newArr['orderMoney'] = bcadd($newArr['orderMoney'],$val['orderMoney'],2);
			}
			$newArr['createDate'] = '��ҳС��';
			$newArr['id'] = 'noId';
			$object[] = $newArr;
			return $object;
		}
	}

	/***************** S ���ϵ�� *********************/
	/**
	 * �������
	 */
	function change_d($object){
		try{
			$this->start_d();

			//ʵ���������
			$changeLogDao = new model_common_changeLog ( 'other' );

			//��������
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//���������Ϣ
			$tempObjId = $changeLogDao->addLog ( $object );

			$this->commit_d();
			return $tempObjId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���������ɺ���º�ͬ״̬
	 */
	function dealAfterAuditChange_d($objId,$userId){
		$obj = $this->get_d($objId);
	 	if($obj['ExaStatus'] == AUDITED){
	 		try{
	 			$this->start_d();

		 		$changeLogDao = new model_common_changeLog ( 'other' );
				$changeLogDao->confirmChange_d ( $obj );

				//Դ��״̬����
				if($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1){//��Ҫ���¸���
					//ֱ�����ø���״̬λ�������и��¼�¼�ر�
					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

				}elseif($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0){//���ڸ��µĴ���

					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

					$stampDao = new model_contract_stamp_stamp();
					$newId = $stampDao->closeWaiting_d($obj['originalId'],'HTGZYD-02');
				}else{//�Ǹ��´���
					$this->update(array('id'=>$obj['originalId']),array('status' => 2,'isNeedRestamp' => 0));
				}

	 			$this->commit_d();
	 			return 1;
	 		}catch(exception $e){
	 			$this->rollBack();
	 			return 1;
	 		}
	 	}else{
            try{
                $this->start_d();

                $this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'ExaStatus' => '���'));

                $this->commit_d();
                return 1;
            }catch(exception $e){
                $this->rollBack();
                return 1;
            }
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
		$changeLogDao = new model_common_changeLog ( 'otherSign' );
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
 		}catch(exception $e){
 			$this->rollBack();
 			return false;
 		}
	}

	/***************** E ǩ��ϵ�� *********************/
}
?>