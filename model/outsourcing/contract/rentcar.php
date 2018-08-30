<?php
/**
 * @author Michael
 * @Date 2014��3��6�� ������ 10:10:23
 * @version 1.0
 * @description:�⳵��ͬ Model��
 */
 class model_outsourcing_contract_rentcar  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_contract_rentcar";
		$this->sql_map = "outsourcing/contract/rentcarSql.php";
		parent::__construct ();
	}

	//��˾Ȩ�޴��� TODO
	// protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/**
	 * ��дadd
	 */
	function add_d($object){
		//ҵ��ǰǩԼ��λ��Ϣ����
		$signCompanyDao = new model_contract_signcompany_signcompany();
		$signCompanyArr = array(
			'signCompanyName' => $object['signCompany'],
			'proName' => $object['companyProvince'],
			'proCode' => $object['companyProvinceCode'],
			'phone' => $object['phone'],
			'address' => $object['address'],
			'linkman' => $object['linkman']
		);
		$signCompanyDao->saveCompanyInfo_d($signCompanyArr);

		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['orderCode'] = $this->createContractCode_d($object['companyCityCode']); //������ͬ���
			$object['contractNature'] = $datadictDao->getDataNameByCode($object['contractNatureCode']); //��ͬ����
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //��ͬ����
			$object['payType'] = $datadictDao->getDataNameByCode($object['payTypeCode']); //��ͬ���ʽ
			$object['ExaStatus'] = WAITAUDIT; //��ʼ������״̬

			//��ȡ������˾����
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

            // ������Ϣ
            $payInfos = array();
            if(isset($object['payInfo'])){
                $payInfos = $object['payInfo'];
                unset($object['payInfo']);
            }

			$id = parent :: add_d($object, true); //����������Ϣ

            // ������Ϣ���ݴ���
            if(!empty($payInfos)){
                $this->tbl_name = 'oa_contract_rentcar_payinfos';
                foreach ($payInfos as $k => $v){
                    $payInfos[$k]['mainId'] = $id;
                    $payInfos[$k]['projectId'] = $object['projectId'];
                    $payInfo = $payInfos[$k];
                    if(!isset($v['isDel'])){
                        parent :: add_d($payInfo,true);// �����⳵������Ϣ������
                    }
                }
                $this->tbl_name = "oa_contract_rentcar";
            }

			if(is_array($object['vehicle'])) { //��ͬ���޳�����Ϣ
				$vehicleDao = new model_outsourcing_contract_vehicle();
				foreach($object['vehicle'] as $key => $val) {
					$val['contractId'] = $id;
					$val['orderCode'] = $object['orderCode'];
					$val['carNumber'] = trim($val['carNumber']); //ȥ�����ƺ����ߵĿո�
					$val['carModel'] = $datadictDao->getDataNameByCode($val['carModelCode']); //�⳵����
					$vehicleDao->add_d($val ,true);
				}
			}

			if(is_array($object['fee'])) { //��ͬ���ӷ���
				$feeDao = new model_outsourcing_contract_rentcarfee();
				foreach($object['fee'] as $key => $val) {
					$val['contractId'] = $id;
					$val['orderCode'] = $object['orderCode'];
					$feeDao->add_d($val ,true);
				}
			}

			$this->updateObjWithFile($id ,$object['orderCode']); //���¸���������ϵ
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��дedit
	 */
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['contractNature'] = $datadictDao->getDataNameByCode($object['contractNatureCode']); //��ͬ����
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //��ͬ����
			$object['payType'] = $datadictDao->getDataNameByCode($object['payTypeCode']); //��ͬ���ʽ

            // ������Ϣ
            $payInfos = array();
            if(isset($object['payInfo'])){
                $payInfos = $object['payInfo'];
                unset($object['payInfo']);
            }

            // ������Ϣ���ݴ���
            if(!empty($payInfos)){
                $this->tbl_name = 'oa_contract_rentcar_payinfos';
                foreach ($payInfos as $k => $v){
                    $payInfos[$k]['mainId'] = $object['id'];
                    $payInfos[$k]['projectId'] = $object['projectId'];
                    $payInfo = $payInfos[$k];
                    if(!isset($payInfo['id'])){
                        if(!isset($payInfo['isDel'])){
                            parent :: add_d($payInfo,true);
                        }
                    }else if(isset($payInfo['id'])){
                        if(isset($payInfo['isDel']) && $payInfo['isDel'] == 1){
                            parent :: delete(array("id" => $payInfo['id']));
                        }else{
                            parent :: edit_d($payInfo,true);
                        }
                    }
                }
                $this->tbl_name = "oa_contract_rentcar";
            }

			$id = parent :: edit_d($object, true); //�༭������Ϣ

			$vehicleDao = new model_outsourcing_contract_vehicle();
			$vehicleDao->delete(array ('contractId' => $object['id']));
			if(is_array($object['vehicle'])) {  //��ͬ���޳�����Ϣ
				foreach($object['vehicle'] as $key => $val){
					if ($val['isDelTag'] != 1) {
						$val['contractId'] = $object['id'];
						$val['orderCode'] = $object['orderCode'];
						$val['carModel'] = $datadictDao->getDataNameByCode($val['carModelCode']); //�⳵����
						$vehicleDao->add_d($val ,true);
					}
				}
			}

			if(is_array($object['fee'])) { //��ͬ���ӷ���
				$feeDao = new model_outsourcing_contract_rentcarfee();
				foreach ($object['fee'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val['carNumber'] = trim($val['carNumber']); //ȥ�����ƺ����ߵĿո�
						if ($val['id'] > 0) {
							$feeDao->edit_d($val);
						} else {
							$val['contractId'] = $object['id'];
							$val['orderCode'] = $object['orderCode'];
							$feeDao->add_d($val);
						}
					} else {
						$feeDao->deleteByPk($val['id']);
					}
				}
			}

			$this->updateObjWithFile($id ,$object['orderCode']); //���¸���������ϵ
			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ɶ����⳵��ͬ��� ���б���+��� ȷ��Ψһ��
	 * @param $preStr ���б���
	 */
	function createContractCode_d($cityCode) {
		$preStr = "DLZC".$cityCode.date("Y");
		//�������һ�����ϼ�¼�ĺ�ͬ���
		$sqlStr = "SELECT orderCode FROM ".$this->tbl_name." WHERE orderCode LIKE '$preStr%' AND isTemp = 0 ORDER BY id DESC LIMIT 0,1";
		$obj = $this->findSql($sqlStr);
		if($obj) {
			$codeNum = intval(substr($obj[0]['orderCode'] ,-3));
			$newNum = $codeNum + 1;
			switch(strlen($newNum)) {
				case 1 : $codeNum = "00".$newNum;break;
				case 2 : $codeNum = "0".$newNum;break;
				case 3 : $codeNum = $newNum;break;
				default : $codeNum = "001"; //��ˮ�ų���999
			}
			$billCode = $preStr.$codeNum;
		} else {
			$billCode = $preStr."001";
		}
		return $billCode;
	}

	/**
	 * �������������Ϣ
	 */
	function stamp_d($obj){
		try{
			$this->start_d();

			$stampDao = new model_contract_stamp_stamp();
			//��ȡ��Ӧ�����������κ�
			$maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name," contractType = 'HTGZYD-07' and contractId=". $obj['contractId'] ,"max(batchNo)");
			$obj['batchNo'] = $maxBatchNo + 1;

			//����������Ϣ
			$obj['contractType'] = 'HTGZYD-07';
			$stampDao->addStamps_d($obj ,true);

			//���º�ͬ�ֶ���Ϣ
			$this->updateById(array('id'=>$obj['contractId'] ,'isNeedStamp' => 1 ,'stampType'=>$obj['stampType'] ,'isStamp'=>0));

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *��ͬ�����ɹ����ڸ����б������Ϣ
	 */
	function workflowCallBack($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$object = $this->get_d($objId);

		$this->dealSuppAndVehicle_d( $objId ); //������Ӧ�ͳ�����Դ�⴦��

		if($object['isNeedStamp'] == "1" && $object['ExaStatus'] == AUDITED){
			if($userId == $object['createId']){
				$userName = $object['createName'];
			} else{
				$userName = $object['principalName'];
			}
	 		//��������
			$stampObj = array (
				"contractId" => $object['id'],
				"contractCode" => ($object['orderCode'] ? $object['orderCode'] : $object['orderTempCode']),
				"contractType" => 'HTGZYD-07',
				"objCode" => $object['objCode'],
				"contractName" => $object['orderName'],
				"signCompanyName" => $object['signCompany'],
				"signCompanyId" => $object['signCompanyId'],
				"contractMoney" => $object['orderMoney'],
				"applyUserId" => $userId,
				"applyUserName" => $userName,
				"applyDate" => day_date,
				"stampType" => $object['stampType'],
				"status" => 0
			);
			$stampDao = new model_contract_stamp_stamp();
			$stampDao->addStamps_d($stampObj ,true);
			return 1;
		}
	 	return 1;
	}

	/**
	 * �������
	 */
	function change_d($object){
		try{
			$this->start_d();

			$changeLogDao = new model_common_changeLog('rentcar'); //ʵ���������

			$object['uploadFiles'] = $changeLogDao->processUploadFile($object ,$this->tbl_name); //��������

			$datadictDao = new model_system_datadict_datadict();
			$object['payType'] = $datadictDao->getDataNameByCode($object['payTypeCode']); //��ͬ���ʽ

			//����������������ݴ���
			foreach ($object['vehicle'] as $key => $val) {
				$object['vehicle'][$key]['carNumber'] = trim($val['carNumber']); //ȥ�����ƺ����ߵĿո�
				if ($val['id']) {
					$object['vehicle'][$key]['oldId'] = $val['id'];
				} else {
					$object['vehicle'][$key]['orderCode'] = $object['orderCode'];
				}
				$object['vehicle'][$key]['carModel'] = $datadictDao->getDataNameByCode($val['carModelCode']); //�⳵����
			}

			//���ӷ��õ����ݴ���
			foreach ($object['fee'] as $key => $val) {
				if ($val['id']) {
					$object['fee'][$key]['oldId'] = $val['id'];
				} else {
					$object['fee'][$key]['orderCode'] = $object['orderCode'];
				}
			}

            //������Ϣ�����ݴ���
            foreach ($object['payInfo'] as $key => $val) {
                $object = $this->addUpdateInfo($object);
                if ($val['id']) {
                    $object['payInfo'][$key]['oldId'] = $val['id'];
                } else {
                    $object['payInfo'][$key]['orderCode'] = $object['orderCode'];
                }

                if($val['isDel'] == 1){
                    if(!isset($val['id'])){
                        unset($object['payInfo'][$key]);
                    }else{
                        $object['payInfo'][$key]['isDelTag'] = $val['isDel'];
                    }
                }
            }

			$tempObjId = $changeLogDao->addLog($object); //���������Ϣ

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
	
	/**
	 * ������ص�����
	 */
	function workflowCallBack_change($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];
		$this->dealAfterAuditChange_d($objId ,$userId);
	}

	/**
	 * ���������ɺ���º�ͬ״̬
	 */
	function dealAfterAuditChange_d($objId ,$userId){
		$obj = $this->get_d($objId);
		if($obj['ExaStatus'] == AUDITED){
			try{
				$this->start_d();

				$changeLogDao = new model_common_changeLog ( 'rentcar' );
				$changeLogDao->confirmChange_d ( $obj );

				//Դ��״̬����
				if($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1){//��Ҫ���¸���
					//ֱ�����ø���״̬λ�������и��¼�¼�ر�
					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

				}elseif($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0){//���ڸ��µĴ���

					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

					$stampDao = new model_contract_stamp_stamp();
					$newId = $stampDao->closeWaiting_d($obj['originalId'],'HTGZYD-07');
				}else{//�Ǹ��´���
					$this->update(array('id'=>$obj['originalId']),array('status' => 2,'isNeedRestamp' => 0));
				}

				$this->dealSuppAndVehicle_d($objId); //������Ӧ�̺ͳ�����Դ����Ϣ����

                // ������Ϣ����
                $updateSql = "update oa_contract_rentcar_payinfos o1 left join oa_contract_rentcar_payinfos o2 on o1.id=o2.originalId SET o1.includeFeeTypeCode=o2.includeFeeTypeCode,o1.updateId = '{$_SESSION['USER_ID']}',o1.updateName = '{$_SESSION['USERNAME']}',o1.updateTime = Now() where o2.mainId = '{$objId}';";
                $this->_db->query($updateSql);

				$this->commit_d();
				return 1;
			}catch(Exception $e){
				$this->rollBack();
				return 1;
			}
		}else{
			try{
				$this->start_d();

				$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'ExaStatus' => '���'));

                // �����¼����״̬����Ϊ���
                $this->_db->query("update oa_contract_rentcar_changelog set ExaStatus = '���',ExaDT = now() where tempId = '{$objId}';");

				$this->dealSuppAndVehicle_d($objId); //������Ӧ�̺ͳ�����Դ����Ϣ����

				$this->commit_d();
				return 1;
			}catch(Exception $e){
				$this->rollBack();
				return 1;
			}
		}
		return 1;
	}

	/**
	 * ����������֤����
	 */
	function canPayapply_d($id){
		$payablesapplyDao = new model_finance_payablesapply_payablesapply();
		$rs = $payablesapplyDao->isExistence_d($id ,'YFRK-06' ,'back');
		if($rs){
			return 'hasBack';
		}
	}

	/**
	 * �˿�������֤
	 */
	function canPayapplyBack_d($id){
		$obj = $this->find(array('id' => $id) ,null ,'initPayMoney');
		//��ȡ�Ѹ�����(�����˿�)
		$payablesDao = new model_finance_payables_payables();
		$payedMoney = bcadd($payablesDao->getPayedMoneyByPur_d($id ,'YFRK-02') ,$obj['initPayMoney'] ,2);
		if($payedMoney*1 != 0){
			$payablesapplyDao = new model_finance_payablesapply_payablesapply();
			$rs = $payablesapplyDao->isExistence_d($id ,'YFRK-02');
			if($rs){
				return 'hasBack';
			}
			$payedApplyMoney = bcadd($payablesapplyDao->getApplyMoneyByPurAll_d($id ,'YFRK-02') ,$obj['initPayMoney'] ,2);
			if($payedApplyMoney*1 != 0){
				return $payedApplyMoney;
			}else{
				return -1;
			}
		}else{
			return 0;
		}
	}

	/**
	 * ��ͬǩ�� - ǩ�չ���
	 */
	function sign_d($object){
		//ʵ���������
		$changeLogDao = new model_common_changeLog ( 'rentcarSign' );
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
			parent::edit_d($signInfo ,true);

			//���ݴ���
			$object = $this->processDatadict($object);

			//��������
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//����ǩ����Ϣ
			$tempObjId = $changeLogDao->addLog ( $object );

			$changeObj = $object;
			$changeObj['id'] = $tempObjId;
			$changeObj['originalId'] = $changeObj['oldId'];

			//ǩ��ȷ��
			$changeLogDao->confirmChange_d ( $changeObj );

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݺ�ͬID��������Ӧ�̺ͳ�����Դ����Ϣ
	 */
	function dealSuppAndVehicle_d( $id ) {
		$object = $this->get_d($id);
		//������Ӧ����Ϣ����
		$vehicleSuppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$vehicleSuppObj['id'] = $object['signCompanyId'];
		$vehicleSuppObj['province'] = $object['companyProvince'];
		$vehicleSuppObj['provinceId'] = $this->get_table_fields('oa_system_province_info' ,"provinceName='".$vehicleSuppObj['province']."'" ,'id');
		$vehicleSuppObj['city'] = $object['companyCity'];
		$vehicleSuppObj['cityId'] = $this->get_table_fields('oa_system_city_info' ,"cityName='".$vehicleSuppObj['province']."' AND provinceId='".$vehicleSuppObj['provinceId']."'" ,'id');
		$vehicleSuppObj['address'] = $object['address'];
		$vehicleSuppDao->updateById($vehicleSuppObj);

		//��ȡ������Ӧ����Ϣ
		$suppObj = $vehicleSuppDao->get_d($object['signCompanyId']);
		//���޳����ӱ�¼�복����Դ��
		$equDao = new model_outsourcing_contract_vehicle();
		$contractId = $object['originalId'] ? $object['originalId'] : $object['id']; //�ж��Ƿ��һ�����
		$equRow = $equDao->findAll(array('contractId'=>$contractId ,'isTemp'=>0));
		$vehicleDao = new model_outsourcing_outsourcessupp_vehicle();
		foreach ($equRow as $key => $val) {
			$vehicleObj = $vehicleDao->find(array('carNumber'=>$val['carNumber']));
			$vehicleObj['suppId']       = $suppObj['id'];
			$vehicleObj['suppCode']     = $suppObj['suppCode'];
			$vehicleObj['suppName']     = $suppObj['suppName'];

			$vehicleObj['carModel']     = $val['carModel'];
			$vehicleObj['driver']       = $val['driver'];
			$vehicleObj['idNumber']     = $val['idNumber'];
			$vehicleObj['displacement'] = $val['displacement'];

			if ($vehicleObj['id']) {
				$vehicleDao->edit_d($vehicleObj ,true);
			} else {
				$vehicleObj['carNumber'] = $val['carNumber'];
				$vehicleDao->add_d($vehicleObj ,true);
			}
		}
	}

	/**
	 * ���ݳ��ƺ����ڲ��Һ�ͬ��Ϣ
	 */
	function getByCarAndDate_d($carNum ,$date) {
		$sql = "SELECT * FROM $this->tbl_name c LEFT JOIN oa_contract_vehicle v ON c.id=v.contractId "
				." WHERE c.isTemp=0 AND v.isTemp=0 AND c.status=2 "
				." AND contractStartDate<='$date' AND contractEndDate>='$date' "
				." AND v.carNumber='$carNum' ";
		$obj = $this->findSql($sql);
		if ($obj) {
			return array_pop($obj);
		} else {
			return false;
		}
	}

	/**
	 * ��Ŀ�����ύ�⳵��ͬ���ʼ�֪ͨ��ظ�����
	 */
	function mailByProjectSubmit_d( $id ) {
		$obj = $this->get_d( $id );
		$content = <<<EOT
			�⳵���뵥�ţ�<span style="color:blue">$obj[rentalcarCode]</span><br />
			��Ŀ���ƣ�<span style="color:blue">$obj[projectName]</span><br />
			��Ŀ��ţ�<span style="color:blue">$obj[projectCode]</span><br />
			ǩԼ��˾��<span style="color:blue">$obj[signCompany]</span><br />
			��ͬ���ƣ�<span style="color:blue">$obj[orderName]</span>
EOT;
		$this->mailDeal_d('rentcarProjectSubmit' ,null ,array('id' => $id ,'content' => $content));
	}

	/**
	 * ����⳵��ͬ
	 */
	function back_d($obj){
		try{
			$this->start_d();
			$this->updateById(array('id' => $obj['id'] ,'status' => $obj['status']));
			$object = $this->get_d($obj['id']);
			$content = <<<EOT
				�⳵���뵥�ţ�<span style="color:blue">$object[rentalcarCode]</span><br />
				��Ŀ���ƣ�<span style="color:blue">$object[projectName]</span><br />
				��Ŀ��ţ�<span style="color:blue">$object[projectCode]</span><br />
				ǩԼ��˾��<span style="color:blue">$object[signCompany]</span><br />
				��ͬ���ƣ�<span style="color:blue">$object[orderName]</span><br />
				���ԭ��$obj[backReason]
EOT;
			$this->mailDeal_d('rentcarBack' ,$object['projectManagerId'] ,array('id' => $obj['id'] ,'content' => $content));

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * excel����
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
		$inArr = array();//��������
		$linkmanArr = array();//��������
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$codeDao = new model_common_codeRule();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				//�����±�
				$keyArr = array(
					'contractNature', //��ͬ����
					'contractType', //��ͬ����
					'rentalcarCode', //�⳵���뵥��
					'projectCode', //��Ŀ���
					'orderName', //��ͬ����
					'principalName', //��ͬ������
					'deptName', //�����˲���
					'signCompanyCode', //ǩԼ��˾���
					'companyProvince', //��˾ʡ��
					'companyCity', //��˾����
					'orderMoney', //��ͬ���
					'linkman', //��ϵ��
					'phone', //��ϵ�绰
					'isNeedStamp', //�Ƿ���Ҫ����
					'stampType', //��������
					'ownCompany', //������˾
					'rentUnitPrice', //���޷���(Ԫ/��/��)
					'oilPrice', //�ͼ�(Ԫ/��)
					'fuelCharge', //ȼ�ͷ�(Ԫ/����)
					'signDate', //ǩԼ����
					'contractStartDate', //��ͬ��ʼ����
					'contractEndDate', //��ͬ��������
					'isUseOilcard', //�Ƿ�ʹ���Ϳ�
					'oilcardMoney', //�Ϳ����(Ԫ)
//					'payBankName', //��������
//					'payBankNum', //�����˺�
//					'payMan', //������
//					'payConditions', //��������
//					'payType', //���ʽ
//					'payApplyMan', //����������
					'carModel', //����
					'carNumber', //���ƺ�
					'driver', //��ʻԱ
					'idNumber', //��ʻԱ���֤��
					'displacement', //������ʹ�ú�������
					'oilCarUse', //�Ϳ��ֳ�
					'oilCarAmount', //�Ϳ����
					'address', //��ϵ��ַ
					'fundCondition', //��������
					'contractContent' //��ͬ����
				);
				//���������ת��
				$transData = array();
				foreach ($excelData as $key => $val) {
					if (!empty($val[0]) || !empty($val[1])) {
						foreach ($keyArr as $k => $v) {
							$data[$v] = trim($val[$k]);
						}
                        $data['numKey']=$key;
						array_push($transData ,$data);
					}
				}
			}
             $newTransData=array();
            foreach($transData as $key=>$val){
                $newTransData[$val['signCompanyCode']][] = $val;
            }
			if (!empty($newTransData)) {
				$rentalcarDao = new model_outsourcing_vehicle_rentalcar(); //�⳵����
				$projectDao = new model_engineering_project_esmproject(); //������Ŀ
				$userDao = new model_deptuser_user_user(); //�û�
				$deptDao = new model_deptuser_dept_dept(); //����
				$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp(); //������Ӧ��
				$provinceDao = new model_system_procity_province(); //����
				$cityDao = new model_system_procity_city(); //ʡ��
				$branchDao = new model_deptuser_branch_branch(); //��˾
				$equDao = new model_outsourcing_contract_vehicle(); //�ӱ�
				$vehicleDao = new model_outsourcing_outsourcessupp_vehicle(); //������Դ��
				$stampDao = new model_contract_stamp_stamp(); // ����

                foreach($newTransData as $nKey=>$nVal){
                    $newId=0;
                    $orderCode='';
                    foreach($newTransData[$nKey] as $key => $val ){
                        $actNum = $val['numKey'] + 2;
                        //��������
                        $inArr = array();
                        //����������
                        $carArr=array();
                        if($key==0){

                            //��ͬ����
                            if(!empty($val['contractNature'])) {
                                $inArr['contractNature'] = $val['contractNature'];
                                $contractNatureCode = $datadictDao->getCodeByName('ZCHTXZ' ,$inArr['contractNature']);
                                if ($contractNatureCode) {
                                    $inArr['contractNatureCode'] = $contractNatureCode;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��ͬ���ʲ�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ͬ����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ͬ����
                            if(!empty($val['contractType'])) {
                                $inArr['contractType'] = $val['contractType'];
                                $contractTypeCode = $datadictDao->getCodeByName('ZCHTLX' ,$inArr['contractType']);
                                if ($contractTypeCode) {
                                    $inArr['contractTypeCode'] = $contractTypeCode;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��ͬ���Ͳ�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ͬ����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //�⳵���뵥��
                            if(!empty($val['rentalcarCode'])) {
                                $inArr['rentalcarCode'] = $val['rentalcarCode'];
                                $rentalcarObj = $rentalcarDao->find(array('formCode' => $inArr['rentalcarCode']) ,'id');
                                if ($rentalcarObj) {
                                    $inArr['rentalcarId'] = $rentalcarObj['id'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!�⳵���뵥�Ų�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            }

                            //��Ŀ���
                            if(!empty($val['projectCode'])) {
                                $inArr['projectCode'] = $val['projectCode'];
                                $projectObj = $projectDao->find(array('projectCode' => $inArr['projectCode']));
                                if ($projectObj) {
                                    $inArr['projectId'] = $projectObj['id'];
                                    $inArr['projectName'] = $projectObj['projectName'];
                                    $inArr['officeId'] = $projectObj['officeId'];
                                    $inArr['officeName'] = $projectObj['officeName'];
                                    $inArr['projectType'] = $projectObj['natureName'];
                                    $inArr['projectTypeCode'] = $projectObj['nature'];
                                    $inArr['projectManager'] = $projectObj['managerName'];
                                    $inArr['projectManagerId'] = $projectObj['managerId'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��Ŀ��Ų�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��Ŀ���Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ͬ����
                            if(!empty($val['orderName'])) {
                                $inArr['orderName'] = $val['orderName'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ͬ����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ͬ������
                            if(!empty($val['principalName'])) {
                                $inArr['principalName'] = $val['principalName'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ͬ������Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //�����˲���
                            if(!empty($val['deptName'])) {
                                $inArr['deptName'] = $val['deptName'];
                                $deptObj = $deptDao->findAll(array('DEPT_NAME' => $inArr['deptName']) ,null ,'DEPT_ID');
                                if ($deptObj) {
                                    $deptIds = '';
                                    foreach ($deptObj as $dKey => $dval){// ��ֹ������Ӳ������ж�����¼��ʱ�����������
                                        if($dval['DEPT_ID'] != ''){
                                            $deptIds .= ($deptIds == '')? $dval['DEPT_ID'] : ",".$dval['DEPT_ID'];
                                        }
                                    }
                                    $userObj = ($deptIds == "")? false : $userDao->find(" USER_NAME = '{$inArr['principalName']}' AND DEPT_ID IN ({$deptIds})" ,null ,'USER_ID');
                                    if ($userObj) {
                                        $inArr['deptId'] = $userObj['DEPT_ID'];
                                        $inArr['principalId'] = $userObj['USER_ID'];
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum .'������';
                                        $tempArr['result'] = '<font color=red>����ʧ��!��ͬ�����˲�����</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!�����˲��Ų�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!�����˲���Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //ǩԼ��˾���
                            if(!empty($val['signCompanyCode'])) {
                                $signCompanyCode = $val['signCompanyCode'];
                                $suppObj = $suppDao->find(array('suppCode'=>$signCompanyCode));
                                if ($suppObj) {
                                    $inArr['signCompany'] = $suppObj['suppName'];
                                    $inArr['signCompanyId'] = $suppObj['id'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!ǩԼ��˾��Ų�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!ǩԼ��˾���Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��˾ʡ��
                            if(!empty($val['companyProvince'])) {
                                $inArr['companyProvince'] = $val['companyProvince'];
                                $companyProvinceObj = $provinceDao->find(array('provinceName' => $inArr['companyProvince']) ,null ,'provinceCode');
                                if ($companyProvinceObj) {
                                    $inArr['companyProvinceCode'] = $companyProvinceObj['provinceCode'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��˾ʡ�ݲ�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��˾ʡ��Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��˾����
                            if(!empty($val['companyCity'])) {
                                $inArr['companyCity'] = $val['companyCity'];
                                $companyCityObj = $cityDao->find(array('cityName' => $inArr['companyCity'] ,'provinceCode' => $inArr['companyProvinceCode']) ,null ,'cityCode');
                                if ($companyCityObj) {
                                    $inArr['companyCityCode'] = $companyCityObj['cityCode'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��˾���в�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��˾����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ͬ���
                            if($val['orderMoney'] != '') {
                                $inArr['orderMoney'] = $val['orderMoney'];
                                if (!is_numeric($inArr['orderMoney'])) {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��ͬ������Ϊ������С��</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ͬ���Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ϵ��
                            if(!empty($val['linkman'])) {
                                $inArr['linkman'] = $val['linkman'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ϵ��Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ϵ�绰
                            if(!empty($val['phone'])) {
                                $inArr['phone'] = $val['phone'];
                            }

                            //�Ƿ���Ҫ����
                            if(!empty($val['isNeedStamp'])) {
                                $isNeedStamp = $val['isNeedStamp'];
                                if ($isNeedStamp == '��') {
                                    $inArr['isNeedStamp'] = 1;
                                } else {
                                    $inArr['isNeedStamp'] = 0;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!�Ƿ���Ҫ����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��������
                            if ($inArr['isNeedStamp'] == 1) {
                                if(!empty($val['stampType'])) {
                                    $inArr['stampType'] = $val['stampType'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            }

                            //������˾
                            if(!empty($val['ownCompany'])) {
                                $inArr['ownCompany'] = $val['ownCompany'];
                                $branchObj = $branchDao->find(array('NameCN' => $inArr['ownCompany']));
                                if ($branchObj) {
                                    $inArr['ownCompanyId'] = $branchObj['ID'];
                                    $inArr['ownCompanyCode'] = $branchObj['NamePT'];
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!������˾������</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!������˾Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //���޷���
                            if($val['rentUnitPrice'] != '') {
                                $inArr['rentUnitPrice'] = $val['rentUnitPrice'];
                                if (!is_numeric($inArr['rentUnitPrice'])) {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!���޷��ñ���Ϊ������С��</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!���޷���Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //�ͼ�
                            if ($inArr['contractTypeCode'] == 'ZCHTLX-01') {
                                if(trim($val['oilPrice']) != '') {
                                    $inArr['oilPrice'] = $val['oilPrice'];
                                    if (!is_numeric($inArr['oilPrice'])) {
                                        $tempArr['docCode'] = '��' . $actNum .'������';
                                        $tempArr['result'] = '<font color=red>����ʧ��!�ͼ۱���Ϊ������С��</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!�ͼ�Ϊ��</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['oilPrice'] = 0;
                            }

                            //ȼ�ͷ�
                            if ($inArr['contractTypeCode'] == 'ZCHTLX-02') {
                                if(trim($val['fuelCharge']) != '') {
                                    $inArr['fuelCharge'] = $val['fuelCharge'];
                                    if (!is_numeric($inArr['fuelCharge'])) {
                                        $tempArr['docCode'] = '��' . $actNum .'������';
                                        $tempArr['result'] = '<font color=red>����ʧ��!ȼ�ͷѱ���Ϊ������С��</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!ȼ�ͷ�Ϊ��</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['fuelCharge'] = 0;
                            }

                            //ǩԼ����
                            if(!empty($val['signDate']) && $val['signDate'] != '0000-00-00') {
                                $val['signDate'] = $val['signDate'];
                                if(!is_numeric($val['signDate'])){
                                    $inArr['signDate'] = $val['signDate'];
                                } else {
                                    $signDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['signDate'] - 1 ,1900)));
                                    if($signDate == '1970-01-01') {
                                        $tmpDate = date('Y-m-d' ,strtotime($val['signDate']));
                                        $inArr['signDate'] = $tmpDate;
                                    } else {
                                        $inArr['signDate'] = $signDate;
                                    }
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!ǩԼ����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ͬ��ʼ����
                            if(!empty($val['contractStartDate']) && $val['contractStartDate'] != '0000-00-00') {
                                $val['contractStartDate'] = $val['contractStartDate'];
                                if(!is_numeric($val['contractStartDate'])){
                                    $inArr['contractStartDate'] = $val['contractStartDate'];
                                } else {
                                    $contractStartDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['contractStartDate'] - 1 ,1900)));
                                    if($contractStartDate == '1970-01-01') {
                                        $tmpDate = date('Y-m-d' ,strtotime($val['contractStartDate']));
                                        $inArr['contractStartDate'] = $tmpDate;
                                    } else {
                                        $inArr['contractStartDate'] = $contractStartDate;
                                    }
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ͬ��ʼ����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ͬ��������
                            if(!empty($val['contractEndDate']) && $val['contractEndDate'] != '0000-00-00') {
                                $val['contractEndDate'] = $val['contractEndDate'];
                                if(!is_numeric($val['contractEndDate'])){
                                    $inArr['contractEndDate'] = $val['contractEndDate'];
                                } else {
                                    $contractEndDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['contractEndDate'] - 1 ,1900)));
                                    if($contractEndDate == '1970-01-01') {
                                        $tmpDate = date('Y-m-d' ,strtotime($val['contractEndDate']));
                                        $inArr['contractEndDate'] = $tmpDate;
                                    } else {
                                        $inArr['contractEndDate'] = $contractEndDate;
                                    }
                                }

                                //�����ͬ����
                                $days = (strtotime($inArr['contractEndDate']) - strtotime($inArr['contractStartDate'])) / (24 * 60 * 60);
                                if ($days > 0) {
                                    $inArr['contractUseDay'] = $days;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!��ͬ�������ڲ���С�ں�ͬ��ʼ����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ͬ��������Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //�Ƿ�ʹ���Ϳ�
                            if(!empty($val['isUseOilcard'])) {
                                $isUseOilcard = $val['isUseOilcard'];
                                if ($isUseOilcard == '��') {
                                    $inArr['isUseOilcard'] = 1;
                                } else {
                                    $inArr['isUseOilcard'] = 0;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!�Ƿ�ʹ���Ϳ�Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //�Ϳ����
                            if ($inArr['isUseOilcard'] == 1) {
                                if($val['oilcardMoney'] != '') {
                                    $inArr['oilcardMoney'] = $val['oilcardMoney'];
                                    if (!is_numeric($inArr['oilcardMoney'])) {
                                        $tempArr['docCode'] = '��' . $actNum .'������';
                                        $tempArr['result'] = '<font color=red>����ʧ��!�Ϳ�������Ϊ������С��</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!�Ϳ����Ϊ��</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $inArr['oilcardMoney'] = 0;
                            }

//                            //��������
//                            if(!empty($val['payBankName'])) {
//                                $inArr['payBankName'] = $val['payBankName'];
//                            } else {
//                                $tempArr['docCode'] = '��' . $actNum .'������';
//                                $tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //�����ʺ�
//                            if(!empty($val['payBankNum'])) {
//                                $inArr['payBankNum'] = $val['payBankNum'];
//                            } else {
//                                $tempArr['docCode'] = '��' . $actNum .'������';
//                                $tempArr['result'] = '<font color=red>����ʧ��!�����ʺ�Ϊ��</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //������
//                            if(!empty($val['payMan'])) {
//                                $inArr['payMan'] = $val['payMan'];
//                            } else {
//                                $tempArr['docCode'] = '��' . $actNum .'������';
//                                $tempArr['result'] = '<font color=red>����ʧ��!������Ϊ��</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //��������
//                            if(!empty($val['payConditions'])) {
//                                $inArr['payConditions'] = $val['payConditions'];
//                            } else {
//                                $tempArr['docCode'] = '��' . $actNum .'������';
//                                $tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //���ʽ
//                            if(!empty($val['payType'])) {
//                                $inArr['payType'] = $val['payType'];
//                                $payTypeCode = $datadictDao->getCodeByName('ZCHTFK' ,$inArr['payType']);
//                                if ($payTypeCode) {
//                                    $inArr['payTypeCode'] = $payTypeCode;
//                                } else {
//                                    $tempArr['docCode'] = '��' . $actNum .'������';
//                                    $tempArr['result'] = '<font color=red>����ʧ��!���ʽ������</font>';
//                                    array_push($resultArr ,$tempArr);
//                                    continue;
//                                }
//                            } else {
//                                $tempArr['docCode'] = '��' . $actNum .'������';
//                                $tempArr['result'] = '<font color=red>����ʧ��!���ʽΪ��</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }
//
//                            //����������
//                            if(!empty($val['payApplyMan'])) {
//                                if ($val['payApplyMan'] == '��Ŀ����' || $val['payApplyMan'] == '��Ŀ��Ա' || $val['payApplyMan'] == '���޷�Χ') {
//                                    $inArr['payApplyMan'] = $val['payApplyMan'];
//                                } else {
//                                    $tempArr['docCode'] = '��' . $actNum .'������';
//                                    $tempArr['result'] = '<font color=red>����ʧ��!��ͬ���ʲ�����</font>';
//                                    array_push($resultArr ,$tempArr);
//                                    continue;
//                                }
//                            } else {
//                                $tempArr['docCode'] = '��' . $actNum .'������';
//                                $tempArr['result'] = '<font color=red>����ʧ��!����������Ϊ��</font>';
//                                array_push($resultArr ,$tempArr);
//                                continue;
//                            }

                            //����
                            if(!empty($val['carModel'])) {
                                $inArr['equ']['carModel'] = $val['carModel'];
                                $carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$inArr['equ']['carModel']);
                                if ($carModelCode) {
                                    $inArr['equ']['carModelCode'] = $carModelCode;
                                } else {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!���Ͳ�����</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //���ƺ�
                            if(!empty($val['carNumber'])) {
                                $inArr['equ']['carNumber'] = $val['carNumber'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!���ƺ�Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ʻԱ
                            if(!empty($val['driver'])) {
                                $inArr['equ']['driver'] = $val['driver'];
                            }else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ʻԱ����Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ʻԱ���֤��
                            if(!empty($val['idNumber'])) {
                                $inArr['equ']['idNumber'] = $val['idNumber'];
                            } else {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��ʻԱ���֤��Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //������ʹ�ú�������
                            if(!empty($val['displacement'])) {
                                $inArr['equ']['displacement'] = $val['displacement'];
                            }

                            //�Ϳ��ֳ�
                            if(!empty($val['oilCarUse'])) {
                                switch ($val['oilCarUse']) {
                                    case '��':
                                        $oilCarUse = '��';
                                        break;
                                    case '��':
                                        $oilCarUse = '��';
                                        break;
                                    default:
                                        $oilCarUse = '��';
                                        break;
                                }
                                $inArr['equ']['oilCarUse'] = $oilCarUse;
                            } else {
                                $inArr['equ']['oilCarUse'] = '��';
                            }

                            if(!empty($val['oilCarAmount'])) {
                                if (!is_numeric($val['oilCarAmount'])) {
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!�����Ϳ�������Ϊ������С��</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                                $inArr['equ']['oilCarAmount'] = $val['oilCarAmount'];
                            } else {
                                $inArr['equ']['oilCarAmount'] = 0;
                            }

                            //��ϵ��ַ
                            if(!empty($val['address'])) {
                                $inArr['address'] = $val['address'];
                            }

                            //��������
                            if(!empty($val['fundCondition'])) {
                                $inArr['fundCondition'] = $val['fundCondition'];
                            } else if ($inArr['contractNatureCode'] == 'ZCHTXZ-01') {
                                $tempArr['docCode'] = '��' . $actNum .'������';
                                $tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
                                array_push($resultArr ,$tempArr);
                                continue;
                            }

                            //��ͬ����
                            if(!empty($val['contractContent'])) {
                                $inArr['contractContent'] = $val['contractContent'];
                            }

                            $inArr['orderCode'] = $this->createContractCode_d($inArr['companyCityCode']); //������ͬ���
                            $inArr['status'] = 0;
                            $inArr['ExaStatus'] = '���ύ';
                            //��ȡ������˾����
                            $inArr['formBelong'] = $_SESSION['USER_COM'];
                            $inArr['formBelongName'] = $_SESSION['USER_COM_NAME'];
                            $inArr['businessBelong'] = $_SESSION['USER_COM'];
                            $inArr['businessBelongName'] = $_SESSION['USER_COM_NAME'];

					        $newId = parent::add_d($inArr ,true);

                            if($newId) {
                                $inArr['equ']['contractId'] = $newId;
                                $orderCode=$inArr['equ']['orderCode'] = $inArr['orderCode'];
                                $rs = $equDao->add_d( $inArr['equ'] );
                                if ($rs) {
                                    //¼�복����Դ��
                                    $this->dealSuppAndVehicle_d( $newId );

                                    // ���´���
                                    if($inArr['isNeedStamp'] == "1") {
                                        $obj = $this->get_d($newId);
                                        //��������
                                        $stampObj = array (
                                            "contractId"      => $obj['id'],
                                            "contractCode"    => $obj['orderCode'],
                                            "contractType"    => 'HTGZYD-07',
                                            "objCode"         => $obj['objCode'],
                                            "contractName"    => $obj['orderName'],
                                            "signCompanyName" => $obj['signCompany'],
                                            "signCompanyId"   => $obj['signCompanyId'],
                                            "contractMoney"   => $obj['orderMoney'],
                                            "applyUserId"     => $obj['principalId'],
                                            "applyUserName"   => $obj['principalName'],
                                            "applyDate"       => day_date,
                                            "stampType"       => $obj['stampType'],
                                            "status"          => 0
                                        );
                                        $stampDao->addStamps_d($stampObj ,true);
                                    }

                                    $tempArr['result'] = '����ɹ�';
                                } else {
                                    $tempArr['result'] = '<font color=red>����ʧ��</font>';
                                }
                            } else {
                                $tempArr['result'] = '<font color=red>����ʧ��</font>';
                            }
                            $tempArr['docCode'] = '��' . $actNum .'������';
                            array_push($resultArr ,$tempArr);

                        }else{
                                if($newId>0){
                                    //����
                                    if(!empty($val['carModel'])) {
                                        $carArr['carModel'] = $val['carModel'];
                                        $carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$carArr['carModel']);
                                        if ($carModelCode) {
                                            $carArr['carModelCode'] = $carModelCode;
                                        } else {
                                            $tempArr['docCode'] = '��' . $actNum .'������';
                                            $tempArr['result'] = '<font color=red>����ʧ��!���Ͳ�����</font>';
                                            array_push($resultArr ,$tempArr);
                                            continue;
                                        }
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum .'������';
                                        $tempArr['result'] = '<font color=red>����ʧ��!����Ϊ��</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }

                                    //���ƺ�
                                    if(!empty($val['carNumber'])) {
                                        $carArr['carNumber'] = $val['carNumber'];
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum .'������';
                                        $tempArr['result'] = '<font color=red>����ʧ��!���ƺ�Ϊ��</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }

                                    //��ʻԱ
                                    if(!empty($val['driver'])) {
                                        $carArr['driver'] = $val['driver'];
                                    }

                                    //��ʻԱ���֤��
                                    if(!empty($val['idNumber'])) {
                                        $carArr['idNumber'] = $val['idNumber'];
                                    } else {
                                        $tempArr['docCode'] = '��' . $actNum .'������';
                                        $tempArr['result'] = '<font color=red>����ʧ��!��ʻԱ���֤��Ϊ��</font>';
                                        array_push($resultArr ,$tempArr);
                                        continue;
                                    }

                                    //������ʹ�ú�������
                                    if(!empty($val['displacement'])) {
                                        $carArr['displacement'] = $val['displacement'];
                                    }

                                    //�Ϳ��ֳ�
                                    if(!empty($val['oilCarUse'])) {
                                        switch ($val['oilCarUse']) {
                                            case '��':
                                                $oilCarUse = '��';
                                                break;
                                            case '��':
                                                $oilCarUse = '��';
                                                break;
                                            default:
                                                $oilCarUse = '��';
                                                break;
                                        }
                                        $carArr['oilCarUse'] = $oilCarUse;
                                    } else {
                                        $carArr['oilCarUse'] = '��';
                                    }

                                    if(!empty($val['oilCarAmount'])) {
                                        if (!is_numeric($val['oilCarAmount'])) {
                                            $tempArr['docCode'] = '��' . $actNum .'������';
                                            $tempArr['result'] = '<font color=red>����ʧ��!�����Ϳ�������Ϊ������С��</font>';
                                            array_push($resultArr ,$tempArr);
                                            continue;
                                        }
                                        $carArr['oilCarAmount'] = $val['oilCarAmount'];
                                    } else {
                                        $carArr['oilCarAmount'] = 0;
                                    }
                                    $carArr['contractId'] = $newId;
                                   $carArr['orderCode'] =  $orderCode;
                                    $rs = $equDao->add_d( $carArr);
                                    if($rs){
                                        //¼�복����Դ��
                                        $this->dealSuppAndVehicle_d( $newId );
                                        $tempArr['result'] = '����ɹ�';
                                    } else {
                                        $tempArr['result'] = '<font color=red>����ʧ��</font>';
                                    }
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    array_push($resultArr ,$tempArr);
                                }else{
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>����ʧ��!δ���ɺ�ͬ��Ϣ</font>';
                                    array_push($resultArr ,$tempArr);
                                    continue;
                                }
                        }
                    }
                }
			}
			return $resultArr;
		}
	}

	/**
	 * ��ͬ����������Ŀ
	 */
	function excelPro_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {
				$projectDao = new model_engineering_project_esmproject(); //������Ŀ

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 1;
					if(empty($val[0]) && empty($val[1])) {
						continue;
					} else {
						//��������
						$inArr = array();

						//������ͬ���
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['orderCode'] = trim($val[0]);
							$rs = $this->find(array('orderCode' => $inArr['orderCode'] ,'isTemp' => 0 ,'status' => 2));
							if ($rs) {
								$conditions = array('orderCode' => $inArr['orderCode'] ,'isTemp' => 0 ,'status' => 2);
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!������ͬ��Ų����ڻ���ִ����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!������ͬ���Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��Ŀ���
						if(!empty($val[1]) && trim($val[1]) != '') {
							$inArr['projectCode'] = trim($val[1]);
							$projectObj = $projectDao->find(array('projectCode' => $inArr['projectCode']));
							if ($projectObj) {
								$inArr['projectId'] = $projectObj['id'];
								$inArr['projectName'] = $projectObj['projectName'];
								$inArr['officeId'] = $projectObj['officeId'];
								$inArr['officeName'] = $projectObj['officeName'];
								$inArr['projectType'] = $projectObj['natureName'];
								$inArr['projectTypeCode'] = $projectObj['nature'];
								$inArr['projectManager'] = $projectObj['managerName'];
								$inArr['projectManagerId'] = $projectObj['managerId'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��Ŀ��Ų�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ŀ���Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						$rs = $this->update($conditions ,$inArr);

						if($rs) {
							$tempArr['result'] = '����ɹ�';
						} else {
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}
}
?>