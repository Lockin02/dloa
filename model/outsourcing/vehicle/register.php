<?php
/**
 * @author Michael
 * @Date 2014��2��10�� ����һ 18:40:56
 * @version 1.0
 * @description:�⳵�ǼǱ� Model��
 */
 class model_outsourcing_vehicle_register  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_register";
		$this->sql_map = "outsourcing/vehicle/registerSql.php";
		parent::__construct ();
	}

	//��˾Ȩ�޴��� TODO
	// protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/**
	 * ��дadd
	 */
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //�⳵����
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //��ͬ����
			$object['carModel'] = $datadictDao->getDataNameByCode($object['carModelCode']); //����

			//��ȡ������˾����
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object);

			if ($id) {
				//���ӷ��ô���
				if (is_array($object['fee'])) {
					$feeDao = new model_outsourcing_vehicle_registerfee();
					foreach ($object['fee'] as $key => $val) {
						$val['registerId'] = $id;
						$feeDao->add_d($val);
					}
				}

				//ͳ�Ʊ���
				if ($object['state'] == '1') {
					$this->dealStatistics_d( $id );
				}
			}

			//��Ӹ���������ϵ
			$this->updateObjWithFile ( $id );

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
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //�⳵����
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //��ͬ����
			$object['carModel'] = $datadictDao->getDataNameByCode($object['carModelCode']); //����

			$id = parent :: edit_d($object, true);

			if ($id) {
				//���ӷ��ô���
				$feeDao = new model_outsourcing_vehicle_registerfee();
				$feeDao->delete(array('registerId' => $object['id']));
				if (is_array($object['fee'])) {
					foreach ($object['fee'] as $key => $val) {
						$val['registerId'] = $object['id'];
						$feeDao->add_d($val);
					}
				}

				//ͳ�Ʊ���
				if ($object['state'] == '1') {
					$this->dealStatistics_d($object['id']);
				}
			}

			//���¸���������ϵ
			$this->updateObjWithFile ($object['id']);

			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������ۺͿۿ���Ϣ
	 */
	function addEstimate_d( $obj ){
		try {
			$this->start_d();

			if(is_array($obj)) {
				foreach($obj as $key => $val) {
					$object = $this->get_d( $val['id'] );
					$sqlArr = "UPDATE oa_outsourcing_register SET "
							." estimate = '".$val['estimate']."'"
							.",estimateTime = '".date( "Y-m-d H:i:s" )."'"
							.",deductInformation = '".$val['deductInformation']."'"
							." WHERE "
							." state = 1"
							." and projectId = '".$object['projectId']."'"
							." and carNum = '".$object['carNum']."'"
							." and allregisterId = '".$object['allregisterId']."'";
					$this->query( $sqlArr );
				}
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��Ӻ�ͬ�����Ϣ
	 */
	function addContract_d( $obj ){
		try {
			$this->start_d();

			if(is_array($obj)) {
				$rentcarDao = new model_outsourcing_contract_rentcar();
				foreach($obj as $key => $val) {
					$rentcarObj = $rentcarDao->get_d($val['rentalContractId']);
					$object = $this->get_d( $val['id'] );

					//�����ó��������⳵��
//					$useDays = $this->getDaysOrFee_d($val['id'] ,$val['rentalContractId']);
//					$rentalCarCost = $rentcarObj['rentUnitPrice'] / 30 * $useDays; //�⳵����

					//���Ϊ����Ļ����⳵�������µļ��㷽��
					if ($object['rentalPropertyCode'] == 'ZCXZ-02') {
						$shortRentObj = $this->findAll(
							array(
								'allregisterId' => $object['allregisterId']
								,'carNum' => $object['carNum']
								,'state' => 1
							)
						);

						if (is_array($shortRentObj)) {
							$rentalCarCost = 0;
							foreach ($shortRentObj as $key => $val) {
								$rentalCarCost += $val['shortRent'];
							}
						}
						$rentcarObj['id'] = 0;
						$rentcarObj['orderCode'] = 0;
						$rentcarObj['oilPrice'] = $object['gasolinePrice'];
						$rentcarObj['fuelCharge'] = $object['gasolineKMPrice'];
					} else if ($object['rentalPropertyCode'] == 'ZCXZ-03') { //����⳵
						$rentalCarCost = $this->getHongdaFee_d($val['id'] ,$val['rentalContractId']);
					}

                    if($val['rentalContractId'] > 0){// ����й����⳵��ͬ, �⳵��Ĭ��ȡ��ͬ�ķ�������
                        $rentalCarCost = $rentcarObj['rentUnitPrice'];
                    }

					$sqlArr = "UPDATE oa_outsourcing_register SET "
							." rentalContractId = '".$rentcarObj['id']."'"
							.",rentalContractCode = '".$rentcarObj['orderCode']."'"
							.",rentalCarCost = '".$rentalCarCost."'"
							.",gasolinePrice = '".$rentcarObj['oilPrice']."'"
							.",gasolineKMPrice = '".$rentcarObj['fuelCharge']."'"
							.",gasolineKMCost = effectMileage * '".$rentcarObj['fuelCharge']."'"
							." WHERE "
							." state = 1 "
							." AND projectId = '".$object['projectId']."'"
							." AND carNum = '".$object['carNum']."'"
							." AND allregisterId = '".$object['allregisterId']."'";
					$this->query( $sqlArr );
				}
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �����Ϣ
	 */
	function change_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();

			$logSettringDao = new model_syslog_setting_logsetting ();
			$allregisterDao = new model_outsourcing_vehicle_allregister();
			$object['carModel'] = $datadictDao->getDataNameByCode($object['carModelCode']); //����
			$oldObj = $this->get_d ($object['id']);
			$allregisterDao->changeStatistics_d( $oldObj ,$object ); //ͳ�Ʊ�������
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object ); //������־

			$id = parent :: edit_d($object, true);

			if ($id) {
				//���ӷ��ô���
				$feeDao = new model_outsourcing_vehicle_registerfee();
				$feeDao->delete(array('registerId' => $object['id']));
				if (is_array($object['fee'])) {
					foreach ($object['fee'] as $key => $val) {
						$val['registerId'] = $object['id'];
						$feeDao->add_d($val);
					}
				}
			}

			//���¸���������ϵ
			$this->updateObjWithFile ($object['id']);

			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �ύһ��
	 */
	function submit_d($id,$act = "") {
	    $idArr = ($act == "batch")? explode(",",$id) : array();
		try {
			$this->start_d();

            if($act == "batch" && !empty($idArr)){
                foreach ($idArr as $bId){
                    $obj = array('id' => $bId ,'state' => 1);
                    $rs = parent::edit_d($obj ,true);
                    $this->dealStatistics_d( $bId ); //ͳ�Ʊ���
                }
            }else{
                $obj = array('id' => $id ,'state' => 1);
                $rs = parent::edit_d($obj ,true);
                $this->dealStatistics_d( $id ); //ͳ�Ʊ���
            }

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݵǼǱ�ID����ͳ�Ʊ�Ĵ���
	 */
	function dealStatistics_d( $id ) {
		try {
			$this->start_d();

			$object = $this->get_d( $id );

			$allregisterDao = new model_outsourcing_vehicle_allregister();
			$object['useCarDate'] = substr_replace($object['useCarDate'] ,'00' ,-2 ,2); //���ڵ��ո�ʽ������Ϊ�·�ͳ��
			$result = $allregisterDao->find(array('projectId' => $object['projectId'] ,'useCarDate' => $object['useCarDate'] ,'state' => 0)); //δ�ύ��
			if (!$result) {
				$result = $allregisterDao->find(array('projectId' => $object['projectId'] ,'useCarDate' => $object['useCarDate'] ,'state' => 3)); //��ص�
			}

			$allregisterObj = array();
			$allregisterObj['projectId'] = $object['projectId'];
			$allregisterObj['projectCode'] = $object['projectCode'];
			$allregisterObj['projectName'] = $object['projectName'];
			$allregisterObj['projectType'] = $object['projectType'];
			$allregisterObj['projectTypeCode'] = $object['projectTypeCode'];
			$allregisterObj['projectManager'] = $object['projectManager'];
			$allregisterObj['projectManagerId'] = $object['projectManagerId'];
			$allregisterObj['officeName'] = $object['officeName'];
			$allregisterObj['officeId'] = $object['officeId'];
			$allregisterObj['province'] = $object['province'];
			$allregisterObj['provinceId'] = $object['provinceId'];
			$allregisterObj['city'] = $object['city'];
			$allregisterObj['cityId'] = $object['cityId'];
			$allregisterObj['useCarDate'] = $object['useCarDate'];
			$allregisterObj['effectMileage'] = $object['effectMileage'];
			$allregisterObj['gasolinePrice'] = $object['gasolinePrice'];
			$allregisterObj['reimbursedFuel'] = $object['reimbursedFuel'];
			$allregisterObj['parkingCost'] = $object['parkingCost'];
            $allregisterObj['tollCost'] = $object['tollCost'];
			$allregisterObj['mealsCost'] = $object['mealsCost'];
			$allregisterObj['accommodationCost'] = $object['accommodationCost'];
			$allregisterObj['effectLogTime'] = $object['effectLogTime'];
			$allregisterObj['overtimePay'] = $object['overtimePay']; //�Ӱ��
			$allregisterObj['specialGas'] = $object['specialGas']; //�����ͷ�

			$allregisterObj['actualUseDay'] = 1; //ʵ���ó�������ʼ��Ϊ1
			if (!$result) {
				//��ȡ������˾����
				$allregisterObj['formBelong'] = $_SESSION['USER_COM'];
				$allregisterObj['formBelongName'] = $_SESSION['USER_COM_NAME'];
				$allregisterObj['businessBelong'] = $_SESSION['USER_COM'];
				$allregisterObj['businessBelongName'] = $_SESSION['USER_COM_NAME'];

				$allregisterId = $allregisterDao->add_d($allregisterObj); //���Ϊ��һ�εǼǣ������ͳ�Ƽ�¼
			} else {
				$allregisterId = $allregisterDao->updateStatistics_d($result['id'] ,$allregisterObj); //���򣬸���ͳ�Ƽ�¼
			}

			$this->update(array('id' => $id) ,array('allregisterId' => $allregisterId)); //�ǼǱ��ͳ�Ʊ����

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �����⳵�ǼǱ�ID�ͺ�ͬID���⳵���ü��������
	 * @param $infoΪtrue���ؼ���������Ϊfalse���ظ��ݺ�ͬ�⳵���ۼ�����⳵��
	 */
	function getDaysOrFee_d($registerId ,$rentalContractId ,$info = true) {
		$rentcarDao = new model_outsourcing_contract_rentcar();
		$rentcarObj = $rentcarDao->get_d( $rentalContractId );
		$object = $this->get_d( $registerId );
		$conditions = array(
			"state"          => 1
			,"projectId"     => $object['projectId']
			,"createId"      => $object['createId']
			,"carNum"        => $object['carNum']
			,"allregisterId" => $object['allregisterId']
		);
		$contractStartDate = strtotime($rentcarObj['contractStartDate']); //��ͬ��ʼ����
		$contractEndDate = strtotime($rentcarObj['contractEndDate']); //��ͬ��������
		$tmp = $this->find($conditions ,"useCarDate ASC" ,"useCarDate");
		$startUseCarDate = strtotime($tmp['useCarDate']); //���µ�һ���ó�����
		$tmp = $this->find($conditions ,"useCarDate DESC" ,"useCarDate");
		$endUseCarDate = strtotime($tmp['useCarDate']); //�������һ���ó�����
		$useDays = 0;
		if (date("Y-m-d" ,$startUseCarDate) >= date("Y-m-d" ,$contractStartDate)
				&& date("Y-m-d" ,$endUseCarDate) <= date("Y-m-d" ,$contractEndDate)) { //�ó��ں�ͬ����

			if (date("Y-m" ,$startUseCarDate) == date("Y-m" ,$contractStartDate)) { //�ó��ں�ͬ��ʼ��
				if (date("Y-m" ,$endUseCarDate) == date("Y-m" ,$contractEndDate)) { //�ó��ں�ͬ������
					if ((int)date("j" ,$contractStartDate) == 1
							&& (int)date("j" ,$contractEndDate) == (int)date("t" ,$contractEndDate)) { //�³�����ĩ�ж��Ƿ�����
						$useDays = 30;
					} else {
						$useDays = (int)date("j" ,$contractEndDate) - (int)date("j" ,$contractStartDate) + 1;
					}
				} else {
					$useDays = 30 - (int)date("j" ,$contractStartDate) + 1;
				}
			} else if (date("Y-m" ,$startUseCarDate) == date("Y-m" ,$contractEndDate)) { //�ó��ڽ�����
				if ((int)date("j" ,$contractEndDate) == (int)date("t" ,$contractEndDate)) { //�Ƿ����µ����һ�����
					$useDays = 30;
				} else {
					$useDays = (int)date("j" ,$contractEndDate);
				}
			} else {
				$useDays = 30;
			}
		}

		if ($info) {
			return $useDays;
		} else {
            return $rentcarObj['rentUnitPrice'];
			// return $rentcarObj['rentUnitPrice'] / 30 * $useDays;
		}
	}

	/**
	 * �����⳵�Ǽ�ID�ͺ�ͬID���⳵����
	 */
	function getHongdaFee_d($registerId ,$rentalContractId) {
		$rentcarDao = new model_outsourcing_contract_rentcar();
		$rentcarObj = $rentcarDao->get_d( $rentalContractId );
		$object = $this->get_d( $registerId );
		$objs = $this->findAll(array('allregisterId' => $object['allregisterId'] ,'carNum' => $object['carNum']));
		$fee = 0.00; //��ʼ���⳵��
		$otherFee = 0.00; //��ʼ����ͬ���ӷ���
		if (is_array($objs)) {
			$rencarfeeDao = new model_outsourcing_contract_rentcarfee();
			$rencarfeeObjs = $rencarfeeDao->findAll(array('contractId' => $rentalContractId ,'isTemp' => 0));
			if (is_array($rencarfeeObjs)) {
				$feeDao = new model_outsourcing_vehicle_registerfee();
				foreach ($rencarfeeObjs as $key => $val) { //ѭ����ͬ���ӷ���
					if (!isset($rencarfeeObjs[$key]['days'])) {
						$rencarfeeObjs[$key]['days'] = 0;
					}
					foreach ($objs as $k => $v) { //ѭ���ó����µ��⳵�Ǽ�
						$feeObj = $feeDao->findAll(array('registerId' => $v['id'] ,'yesOrNo' => 1));
						if (is_array($feeObj)) {
							foreach ($feeObj as $ke => $va) { //ѭ��ÿ��ĺ�ͬ���ӷ���
								if ($va['feeName'] == $val['feeName'] && $va['feeAmount'] == $val['feeAmount']) {
									$rencarfeeObjs[$key]['days']++;
								}
							}
						}
					}
				}
				//���㸽���ܷ���
				foreach ($rencarfeeObjs as $key => $val) {
					if ($val['days'] > 0) {
						$otherFee += ($val['feeAmount'] / 23 * $val['days']);
					}
				}
				$otherFee = ceil($otherFee * 100) / 100; //��1���ұ�����λС��
			}
			$fee = $rentcarObj['rentUnitPrice'] / 23 * count($objs);
			$fee = ceil($fee * 100) / 100; //��1���ұ�����λС��
		}
		return ($fee + $otherFee);
	}

	/**
	 * �����⳵�Ǽ�id�����ȡ���³��������з���
	 */
	function getAllKindsFeeById_d($ids) {
		$idArr = explode(',' ,$ids);
		//��ʼ������ֵ
		$result = array(
			'id' => $ids ,
			'allCost' => 0 ,
			'fee' => array(
				//�⳵��
				array(
					'costTypeId' => 'YSLX-02',
					'costMoney'  => 0
				),
				//�ͷ�
				array(
					'costTypeId' => 'YSLX-03',
					'costMoney'  => 0
				),
				//ͣ����
				array(
					'costTypeId' => 'YSLX-04',
					'costMoney'  => 0
				),
                //·�ŷ�
				array(
					'costTypeId' => 'YSLX-08',
					'costMoney'  => 0
				),
				//������
				array(
					'costTypeId' => 'YSLX-05',
					'costMoney'  => 0
				),
				//ס�޷�
				array(
					'costTypeId' => 'YSLX-06',
					'costMoney'  => 0
				),
				//�Ӱ��
				array(
					'costTypeId' => 'YSLX-07',
					'costMoney'  => 0
				)
			)
		);
		if (is_array($idArr)) {
			foreach ($idArr as $key => $val) {
				$tmp = array();
				$tmp = $this->getAllKindsFeeByOne_d($val);
				foreach ($result['fee'] as $ke => $va) {
					foreach ($tmp['fee'] as $k => $v) {
						if ($va['costTypeId'] == $v['costTypeId']) {
							$result['fee'][$ke]['costMoney'] += $v['costMoney'];
							break;
						}
					}
				}
				$result['allCost'] += $tmp['allCost'];
			}
		}

		return $result;
	}

	/**
	 * �����⳵�Ǽ�id��ȡ���³��������з���
	 */
	function getAllKindsFeeByOne_d($id) {
		$obj = $this->get_d($id);
		$objs = $this->findAll(array('allregisterId' => $obj['allregisterId'] ,'carNum' => $obj['carNum'] ,'state' => 1));
		$reimbursedFuel    = 0; //ʵ��ʵ���ͷ�
		$gasolineKMCost    = 0; //������Ƽ��ͷ�
		$parkingCost       = 0; //ͣ����
        $tollCost          = 0; //·�ŷ�
		$rentalCarCost     = 0; //�⳵��
		$mealsCost         = 0; //������
		$accommodationCost = 0; //ס�޷�
		$overtimePay       = 0; //�Ӱ��
		$specialGas        = 0; //�����ͷ�
		$allCost           = 0; //�ܷ���

		if (is_array($objs)) {
			$vehicleDao = new model_outsourcing_contract_vehicle();
			foreach ($objs as $key => $val) {
				//ʵ��ʵ���ͷ�
				$reimbursedFuel += ($val['reimbursedFuel'] > 0 ? $val['reimbursedFuel'] : 0);

				//������Ƽ��ͷ�
				$gasolineKMCost += ($val['gasolineKMCost'] > 0 ? $val['gasolineKMCost'] : 0);

				//ͣ����
				$parkingCost += ($val['parkingCost'] > 0 ? $val['parkingCost'] : 0);

                //·�ŷ�
				$tollCost += ($val['tollCost'] > 0 ? $val['tollCost'] : 0);

				//�⳵��
				if ($val['rentalPropertyCode'] == 'ZCXZ-02') { //����
					$rentalCarCost += ($val['shortRent'] > 0 ? $val['shortRent'] : 0);
				} else if ($val['rentalPropertyCode'] == 'ZCXZ-03') { //����⳵
					$rentalCarCost = 0; //����Ĵ��³�ʼ����Ϊ�˷�ֹ�ǼǴ���
					$rentalCarCost = $this->getHongdaFee_d($val['id'] ,$val['rentalContractId']);
				} else {
					$rentalCarCost = 0; //����Ĵ��³�ʼ����Ϊ�˷�ֹ�ǼǴ���
					$rentalCarCost = $this->getDaysOrFee_d($val['id'] ,$val['rentalContractId'] ,false);
				}

				//������
				$mealsCost += ($val['mealsCost'] > 0 ? $val['mealsCost'] : 0);

				//ס�޷�
				$accommodationCost += ($val['accommodationCost'] > 0 ? $val['accommodationCost'] : 0);

				//�Ӱ��
				$overtimePay += ($val['overtimePay'] > 0 ? $val['overtimePay'] : 0);

				//�����ͷ�
				$specialGas += ($val['specialGas'] > 0 ? $val['specialGas'] : 0);

				//��ͬ��ѡ�����Ϳ��ֳ�ĳ������Զ������ɱ�������ʱ����������ͷѣ��������ͷѱ���
				if ($val['rentalContractId'] > 0) { //���ں�ͬ
					$vehicleObj = $vehicleDao->find(
						array(
							'contractId' => $val['rentalContractId'],
							'carNumber'  => $val['carNum'],
							'isTemp'     => 0,
							'oilCarUse'  => '��'
						)
					);
					if ($vehicleObj) {
						$reimbursedFuel = $gasolineKMCost = 0;
					}
				}
			}
		}

		$oilFee = $reimbursedFuel + $gasolineKMCost + $specialGas; //�ͷ�=ʵ��ʵ���ͷ�+�������Ʒ�+�����ͷ�
		$oilFee = ceil($oilFee * 100) / 100; //��1���ұ�����λС��

		$allCost = (double)$oilFee
				 + (double)$parkingCost
                 + (double)$tollCost
				 + (double)$rentalCarCost
				 + (double)$mealsCost
				 + (double)$accommodationCost
				 + (double)$overtimePay;
		$allCost = ceil($allCost * 100) / 100; //��1���ұ�����λС��

		$result = array('id' => $id ,'allCost' => $allCost ,'fee' => array()); //��ʼ������ֵ
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-02' ,'costMoney' => $rentalCarCost)); //�⳵��
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-03' ,'costMoney' => $oilFee)); //�ͷ�
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-04' ,'costMoney' => $parkingCost)); //ͣ����
        array_push($result['fee'] ,array('costTypeId' => 'YSLX-08' ,'costMoney' => $tollCost)); //·�ŷ�
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-05' ,'costMoney' => $mealsCost)); //������
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-06' ,'costMoney' => $accommodationCost)); //ס�޷�
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-07' ,'costMoney' => $overtimePay)); //�Ӱ��

		return $result;
	}

	/**
	 * �������ͱ���������ýӿڣ������Ѹ�����
	 * @param array(array('id'=>'�ǼǱ�id','money'=>'������','payType'=>0:���ñ���;1:��������)����)
	 */
	function dealAfterPay_d($payObj) {
		try {
			$this->start_d();

			if (is_array($payObj)) {
				$allDao = new model_outsourcing_vehicle_allregister();
				foreach ($payObj as $key => $val) {
					$obj = $this->get_d($val['id']);
					if ($obj) {
						//���������⳵�ǼǱ�����Ϣ
						$conditions = array(
							'allregisterId' => $obj['allregisterId'],
							'carNum'        => $obj['carNum'],
							'state'         => 1
						);
						$row = array(
							'payType'  => $val['payType'],
							'payMoney' => $val['money']
						);

						//��������д����Ϊ���÷�̯�޷����ֲ�ͬ�Ǽǵ�id������ֻ�ܴ�һ���ܵķ��ã������Ǽǵķ��ö���-1
						if ($val['money'] >= 0) {
							$this->update($conditions ,$row);

							//���»��ܱ�����Ϣ
							$allDao->updateMoney_d($obj['allregisterId'] ,$val['money'] ,$val['type']);
						}
					}
				}
			}

			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * excel����
	 */
	function addExecelData_d(){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name, 1);
            $excelHeaderArr = $excelData[0];
            unset($excelData[0]);

			spl_autoload_register("__autoload");
            $transData = array();
            if(!in_array("ͣ����",$excelHeaderArr) || !in_array("·�ŷ�",$excelHeaderArr)) {// ���ݵ���ģ��ı����ж�ģ���Ƿ�Ϊ����
                $tempArr['docCode'] = '����ģ������';
                $tempArr['result'] = '<font color=red>���������µĵ���ģ�������!</font>';
                array_push($resultArr ,$tempArr);
            }else if(is_array($excelData)) {
				//�ȶ��������ת��
				foreach ($excelData as $key => $val) {
					$transData[$key]['projectCode']       = $val[0];
					$transData[$key]['province']          = $val[1];
					$transData[$key]['city']              = $val[2];
					$transData[$key]['useCarDate']        = $val[3];
					$transData[$key]['suppCode']          = $val[4];
					$transData[$key]['driverName']        = $val[5];
					$transData[$key]['rentalProperty']    = $val[6];
					$transData[$key]['contractType']      = $val[7];
					$transData[$key]['isCardPay']         = $val[8];
					$transData[$key]['carNum']            = $val[9];
					$transData[$key]['carModel']          = $val[10];
					$transData[$key]['startMileage']      = $val[11];
					$transData[$key]['endMileage']        = $val[12];
					$transData[$key]['mealsCost']         = $val[13];
					$transData[$key]['accommodationCost'] = $val[14];
					$transData[$key]['parkingCost']       = $val[15];
                    $transData[$key]['tollCost']          = $val[16];
					$transData[$key]['gasolinePrice']     = $val[17];
					$transData[$key]['reimbursedFuel']    = $val[18];
					$transData[$key]['overtimePay']       = $val[19];
					$transData[$key]['specialGas']        = $val[20];
					$transData[$key]['shortRent']         = $val[21];
					$transData[$key]['gasolineKMPrice']   = $val[22];
					$transData[$key]['drivingReason']     = $val[23];
					$transData[$key]['effectLogTime']     = $val[24];
					$transData[$key]['remark']            = $val[25];
				}

				$esmDao = new model_engineering_project_esmproject(); //������Ŀ
				$provinceDao = new model_system_procity_province(); //����
				$cityDao = new model_system_procity_city(); //ʡ��
				$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp(); //������Ӧ��
				//������ѭ��
				foreach($transData as $key => $val){
					$actNum = $key + 1;
					if(empty($val['projectCode']) && empty($val['useCarDate']) && empty($val['suppCode'])) {
						continue;
					} else {
						//��������
						$inArr = array();

						//��Ŀ���
						if(!empty($val['projectCode']) && trim($val['projectCode']) != '') {
							$inArr['projectCode'] = trim($val['projectCode']);
							$esmObj = $esmDao->find(array('projectCode'=>$val['projectCode']));
							if ($esmObj) {
								$inArr['projectId'] = $esmObj['id'];
								$inArr['projectName'] = $esmObj['projectName'];
								$inArr['officeId'] = $esmObj['officeId'];
								$inArr['officeName'] = $esmObj['officeName'];
								$inArr['projectType'] = $esmObj['natureName'];
								$inArr['projectTypeCode'] = $esmObj['nature'];
								$inArr['projectManagerId'] = $esmObj['managerId'];
								$inArr['projectManager'] = $esmObj['managerName'];
								$inArr['provinceId'] = $esmObj['provinceId'];
								$inArr['province'] = $esmObj['province'];
								$inArr['cityId'] = $esmObj['cityId'];
								$inArr['city'] = $esmObj['city'];
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

						//ʡ��
						if(!empty($val['province']) && trim($val['province']) != '') {
							$inArr['province'] = trim($val['province']);
							$provinceObj = $provinceDao->find(array('provinceName' => $inArr['province']));
							if ($provinceObj) {
								$inArr['provinceId'] = $provinceObj['id'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!ʡ�ݲ�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//����
						if(!empty($val['city']) && trim($val['city']) != '') {
							$inArr['city'] = trim($val['city']);
							$cityObj = $cityDao->find(array('cityName' => $inArr['city']));
							if ($cityObj) {
								$inArr['cityId'] = $cityObj['id'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!���в�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//�ó�����
						if(!empty($val['useCarDate']) && $val['useCarDate'] != '0000-00-00' && trim($val['useCarDate']) != '') {
							$val['useCarDate'] = trim($val['useCarDate']);
							if(!is_numeric($val['useCarDate'])){
								$inArr['useCarDate'] = $val['useCarDate'];
							} else {
								$useCarDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['useCarDate'] - 1 ,1900)));
								if($useCarDate == '1970-01-01') {
									$tmpDate = date('Y-m-d' ,strtotime($val['useCarDate']));
									$inArr['useCarDate'] = $tmpDate;
								}else{
									$inArr['useCarDate'] = $useCarDate;
								}
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�ó�����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��Ӧ�̱��
						if(!empty($val['suppCode']) && trim($val['suppCode']) != '') {
							$inArr['suppCode'] = trim($val['suppCode']);
							$suppObj = $suppDao->find(array('suppCode'=>$inArr['suppCode']));
							if ($suppObj) {
								$inArr['suppId'] = $suppObj['id'];
								$inArr['suppName'] = $suppObj['suppName'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱�Ų�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱��Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//˾������
						if(!empty($val['driverName']) && trim($val['driverName']) != '') {
							$inArr['driverName'] = trim($val['driverName']);
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!˾������Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//�⳵����
						if(!empty($val['rentalProperty']) && trim($val['rentalProperty']) != '') {
							$inArr['rentalProperty'] = trim($val['rentalProperty']);
							$rentalPropertyCode = $datadictDao->getCodeByName('WBZCXZ' ,$inArr['rentalProperty']);
							if ($rentalPropertyCode) {
								$inArr['rentalPropertyCode'] = $rentalPropertyCode;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�⳵���ʲ�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�⳵����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��ͬ����
						if(!empty($val['contractType']) && trim($val['contractType']) != '') {
							$inArr['contractType'] = trim($val['contractType']);
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

						//�Ƿ�ʹ���Ϳ�֧��
						if(!empty($val['isCardPay']) && trim($val['isCardPay']) != '') {
							$isCardPay = trim($val['isCardPay']);
							if ($isCardPay == '�ǣ�ʹ���Ϳ�֧��') {
								$inArr['isCardPay'] = 1;
							} else {
								$inArr['isCardPay'] = 0;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�Ƿ�ʹ���Ϳ�֧��Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//����
						if(!empty($val['carNum']) && trim($val['carNum']) != '') {
							$inArr['carNum'] = trim($val['carNum']);
							$rs = $this->isCanAdd_d($inArr);
							if (!$rs) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>��Ŀ�ϸó����Ѵ����ó�����Ϊ'.$inArr['useCarDate'].'�ļ�¼</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//����
						if(!empty($val['carModel']) && trim($val['carModel']) != '') {
							$inArr['carModel'] = trim($val['carModel']);
							$carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$inArr['carModel']);
							if ($carModelCode) {
								$inArr['carModelCode'] = $carModelCode;
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

						//��ʼ���
						if(trim($val['startMileage']) != '') {
							$inArr['startMileage'] = trim($val['startMileage']);
							if (!is_numeric($inArr['startMileage'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��ʼ��̱���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ʼ���Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//�������
						if(trim($val['endMileage']) != '') {
							$inArr['endMileage'] = trim($val['endMileage']);
							if (!is_numeric($inArr['endMileage'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!������̱���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}

							if ($inArr['endMileage'] >= $inArr['startMileage']) {
								$inArr['effectMileage'] = $inArr['endMileage'] - $inArr['startMileage'];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��ʼ��̴��ڽ������</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�������Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//������
						if(!empty($val['mealsCost']) && trim($val['mealsCost']) != '') {
							$inArr['mealsCost'] = trim($val['mealsCost']);
							if (!is_numeric($inArr['mealsCost'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ѱ���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['mealsCost'] = 0;
						}

						//ס�޷�
						if(!empty($val['accommodationCost']) && trim($val['accommodationCost']) != '') {
							$inArr['accommodationCost'] = trim($val['accommodationCost']);
							if (!is_numeric($inArr['accommodationCost'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!ס�޷ѱ���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['accommodationCost'] = 0;
						}

						//ͣ����
						if(trim($val['parkingCost']) != '') {
							$inArr['parkingCost'] = trim($val['parkingCost']);
							if (!is_numeric($inArr['parkingCost'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!ͣ���ѱ���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['parkingCost'] = 0;
						}

						//·�ŷ�
						if(trim($val['tollCost']) != '') {
							$inArr['tollCost'] = trim($val['tollCost']);
							if (!is_numeric($inArr['tollCost'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!·�ŷѱ���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['tollCost'] = 0;
						}

						//�ͼ�
						if(!empty($val['gasolinePrice']) && trim($val['gasolinePrice']) != '') {
							$inArr['gasolinePrice'] = trim($val['gasolinePrice']);
							if (!is_numeric($inArr['gasolinePrice'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�ͼ۱���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['gasolinePrice'] = 0;
						}

						//ʵ��ʵ���ͷ�
						if ($inArr['contractTypeCode'] == 'ZCHTLX-03') {
							if(trim($val['reimbursedFuel']) != '') {
								$inArr['reimbursedFuel'] = trim($val['reimbursedFuel']);
								if (!is_numeric($inArr['reimbursedFuel'])) {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!ʵ��ʵ���ͷѱ���Ϊ������С��</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!ʵ��ʵ���ͷ�Ϊ��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['reimbursedFuel'] = 0;
						}

						//�Ӱ��
						if(!empty($val['overtimePay']) && trim($val['overtimePay']) != '') {
							$inArr['overtimePay'] = trim($val['overtimePay']);
							if (!is_numeric($inArr['overtimePay'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�Ӱ�ѱ���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['overtimePay'] = 0;
						}

						//�����ͷ�
						if(!empty($val['specialGas']) && trim($val['specialGas']) != '') {
							$inArr['specialGas'] = trim($val['specialGas']);
							if (!is_numeric($inArr['specialGas'])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����ͷѱ���Ϊ������С��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['specialGas'] = 0;
						}

						//���⳵��
						if ($inArr['rentalPropertyCode'] == 'ZCXZ-02') {
							if(trim($val['shortRent']) != '') {
								$inArr['shortRent'] = trim($val['shortRent']);
								if (!is_numeric($inArr['shortRent'])) {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!���⳵�ѱ���Ϊ������С��</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!���⳵��Ϊ��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['shortRent'] = 0;
						}

						//������Ƽ��ͷ�
						if ($inArr['rentalPropertyCode'] == 'ZCXZ-02' && $inArr['contractTypeCode'] == 'ZCHTLX-02') {
							if(trim($val['gasolineKMPrice']) != '') {
								$inArr['gasolineKMPrice'] = trim($val['gasolineKMPrice']);
								if (!is_numeric($inArr['gasolineKMPrice'])) {
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!������Ƽ��ͷѱ���Ϊ������С��</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!������Ƽ��ͷ�Ϊ��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['gasolineKMPrice'] = 0;
						}

						//�г�����
						if(!empty($val['drivingReason']) && trim($val['drivingReason']) != '') {
							$inArr['drivingReason'] = trim($val['drivingReason']);
						}

						//��ЧLOGʱ��
						if(!empty($val['effectLogTime']) && trim($val['effectLogTime']) != '') {
							$inArr['effectLogTime'] = trim($val['effectLogTime']);
						}

						//��ע
						if(!empty($val['remark']) && trim($val['remark']) != '') {
							$inArr['remark'] = trim($val['remark']);
						}

						$inArr['state'] = 1;
						//��ȡ������˾����
						$inArr['formBelong'] = $_SESSION['USER_COM'];
						$inArr['formBelongName'] = $_SESSION['USER_COM_NAME'];
						$inArr['businessBelong'] = $_SESSION['USER_COM'];
						$inArr['businessBelongName'] = $_SESSION['USER_COM_NAME'];

						$newId = parent::add_d($inArr ,true);
						if($newId) {
							$rs = $this->dealStatistics_d( $newId );
							if ($rs) {
								$tempArr['result'] = '����ɹ�';
							} else {
								$tempArr['result'] = '<font color=red>����ʧ��</font>';
							}
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

	/**
	 * �ж��Ƿ��Ѿ����ڼ�¼(�ܷ����)
	 */
	function isCanAdd_d( $obj ) {
		$rs = $this->find(
					array(
						'projectId' => $obj['projectId']
						,'useCarDate' => $obj['useCarDate']
						,'carNum' => $obj['carNum']
						,'state' => 1
					)
				);
		if (!$rs) {
			return true;
		} else {
			return false;
		}
	}

     /**
      * �ж��Ƿ��Ѿ����ڼ�¼(�ܷ����)
      */
     function isCanBatchAdd_d( $ids = '' ) {
         $resultArr = array(
             "error" => 0,
             "msg" => ""
         );
         if( $ids != '' ){
             $dispassRegister = "";
             $registerData = $this->findAll(" id in ($ids)");
             // $resultArr['data'] = $registerData;
             $importData = array();
             if(is_array($registerData)){
                 foreach ($registerData as $row){
                     $importData["{$row['projectId']}_{$row['carNum']}_{$row['useCarDate']}"] =
                         isset($importData["{$row['projectId']}_{$row['carNum']}_{$row['useCarDate']}"])?
                             $importData["{$row['projectId']}_{$row['carNum']}_{$row['useCarDate']}"] + 1 : 1;
                     $rs = $this->find(
                         array(
                             'projectId' => $row['projectId']
                             ,'useCarDate' => $row['useCarDate']
                             ,'carNum' => $row['carNum']
                             ,'state' => 1
                         )
                     );
                     $dispassRegister .= ($rs)? (($dispassRegister == "")? " {$row['carNum']}({$row['useCarDate']})" : ",{$row['carNum']}({$row['useCarDate']})") : "";
                 }
             }
             $hasDuplit = false;
             foreach ($importData as $item){
                 if($item > 1){
                     $hasDuplit = true;
                 }
             }
             if($hasDuplit){// ͬһ��Ŀ�µ�ͬһ���ƺŲ��ܴ���ͬһ���ڵĶ�����¼
                 $resultArr['error'] = 1;
                 $resultArr['msg'] = "�ύ�ļ�¼�д����ظ����ó���¼, ��������ԡ�";
             }else{
                 $resultArr['error'] = ($dispassRegister != "")? 1 : 0;
                 $resultArr['msg'] = ($dispassRegister != "")? "���³���: {$dispassRegister} �Ѵ����ó���¼, ��������ԡ�" : "";
             }
         }
         return $resultArr;
     }

	/**
	 * �������excel����
	 */
	function dealExcelData_d($data) {
		$resultArr = array();//�������
		$tempArr = array();
		$datadictDao = new model_system_datadict_datadict();
		$esmDao = new model_engineering_project_esmproject(); //������Ŀ
		$provinceDao = new model_system_procity_province(); //����
		$cityDao = new model_system_procity_city(); //ʡ��
		$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp(); //������Ӧ��
		$resultArr['result'] = true;
		foreach($data as $key => $val){
			$actNum = $key + 1;
			$inArr = array(); //������

			//��Ŀ���
			if(!empty($val['projectCode']) && trim($val['projectCode']) != '') {
				$inArr['projectCode'] = trim($val['projectCode']);
				$esmObj = $esmDao->find(array('projectCode'=>$val['projectCode']));
				if ($esmObj) {
					$inArr['projectId'] = $esmObj['id'];
					$inArr['projectName'] = $esmObj['projectName'];
					$inArr['officeId'] = $esmObj['officeId'];
					$inArr['officeName'] = $esmObj['officeName'];
					$inArr['projectType'] = $esmObj['natureName'];
					$inArr['projectTypeCode'] = $esmObj['nature'];
					$inArr['projectManagerId'] = $esmObj['managerId'];
					$inArr['projectManager'] = $esmObj['managerName'];
					$inArr['provinceId'] = $esmObj['provinceId'];
					$inArr['province'] = $esmObj['province'];
					$inArr['cityId'] = $esmObj['cityId'];
					$inArr['city'] = $esmObj['city'];
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!��Ŀ��Ų�����</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!��Ŀ���Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//ʡ��
			if(!empty($val['province']) && trim($val['province']) != '') {
				$inArr['province'] = trim($val['province']);
				$provinceObj = $provinceDao->find(array('provinceName' => $inArr['province']));
				if ($provinceObj) {
					$inArr['provinceId'] = $provinceObj['id'];
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!ʡ�ݲ�����</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			}

			//����
			if(!empty($val['city']) && trim($val['city']) != '') {
				$inArr['city'] = trim($val['city']);
				$cityObj = $cityDao->find(array('cityName' => $inArr['city']));
				if ($cityObj) {
					$inArr['cityId'] = $cityObj['id'];
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!���в�����</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			}

			//�ó�����
			if(!empty($val['useCarDate']) && $val['useCarDate'] != '0000-00-00' && trim($val['useCarDate']) != '') {
				$val['useCarDate'] = trim($val['useCarDate']);
				if(!is_numeric($val['useCarDate'])){
					$inArr['useCarDate'] = $val['useCarDate'];
				} else {
					$useCarDate = date('Y-m-d' ,(mktime(0 ,0 ,0 ,1 ,$val['useCarDate'] - 1 ,1900)));
					if($useCarDate == '1970-01-01') {
						$tmpDate = date('Y-m-d' ,strtotime($val['useCarDate']));
						$inArr['useCarDate'] = $tmpDate;
					}else{
						$inArr['useCarDate'] = $useCarDate;
					}
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!�ó�����Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//��Ӧ�̱��
			if(!empty($val['suppCode']) && trim($val['suppCode']) != '') {
				$inArr['suppCode'] = trim($val['suppCode']);
				$suppObj = $suppDao->find(array('suppCode'=>$inArr['suppCode']));


				if ($suppObj) {
//                    //�жϳ����Ƿ��ѵǼ� // �⳵�Ǽǵ���ȥ����Ӧ���ظ���֤ (PMS2132 by haojin 2016-10-18)
//                    $registerRow=$this->find(array('carNum'=> trim($val['carNum']),'useCarDate'=>$inArr['useCarDate']));
//                    if(is_array($registerRow)&&count($registerRow)>0){
//                        $tempArr['docCode'] = '��' . $actNum .'������';
//                        $tempArr['result'] = '<font color=red>����ʧ��!�ù�Ӧ��'.$inArr['useCarDate'].'�ѵǼ�</font>';
//                        $resultArr['result'] = false;
//                        array_push($resultArr ,$tempArr);
//                        continue;
//                    }
                    $inArr['suppId'] = $suppObj['id'];
					$inArr['suppName'] = $suppObj['suppName'];
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱�Ų�����</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱��Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//˾������
			if(!empty($val['driverName']) && trim($val['driverName']) != '') {
				$inArr['driverName'] = trim($val['driverName']);
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!˾������Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//�⳵����
			if(!empty($val['rentalProperty']) && trim($val['rentalProperty']) != '') {
				$inArr['rentalProperty'] = trim($val['rentalProperty']);
				$rentalPropertyCode = $datadictDao->getCodeByName('WBZCXZ' ,$inArr['rentalProperty']);
				if ($rentalPropertyCode) {
					$inArr['rentalPropertyCode'] = $rentalPropertyCode;
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!�⳵���ʲ�����</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!�⳵����Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//��ͬ����
			if(!empty($val['contractType']) && trim($val['contractType']) != '') {
				$inArr['contractType'] = trim($val['contractType']);
				$contractTypeCode = $datadictDao->getCodeByName('ZCHTLX' ,$inArr['contractType']);
				if ($contractTypeCode) {
					$inArr['contractTypeCode'] = $contractTypeCode;
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!��ͬ���Ͳ�����</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!��ͬ����Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//�Ƿ�ʹ���Ϳ�֧��
			if(!empty($val['isCardPay']) && trim($val['isCardPay']) != '') {
				$isCardPay = trim($val['isCardPay']);
				if ($isCardPay == '�ǣ�ʹ���Ϳ�֧��') {
					$inArr['isCardPay'] = 1;
				} else {
					$inArr['isCardPay'] = 0;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!�Ƿ�ʹ���Ϳ�֧��Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//����
			if(!empty($val['carNum']) && trim($val['carNum']) != '') {
				$inArr['carNum'] = trim($val['carNum']);
				$rs = $this->isCanAdd_d($inArr);//�жϸó����Ƿ��ѵǼ�
				if (!$rs) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>��Ŀ�ϸó����Ѵ����ó�����Ϊ'.$inArr['useCarDate'].'�ļ�¼</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}else{
                    if(!empty($resultArr)&&count($resultArr)>1){
                        $breakFlag=false;
                        foreach($resultArr as $rKey=>$rVal){
                            if(isset($rVal['data'])){
                                if($rVal['data']['carNum']==$inArr['carNum']&&$rVal['data']['useCarDate']==$inArr['useCarDate']){
                                    $tempArr['docCode'] = '��' . $actNum .'������';
                                    $tempArr['result'] = '<font color=red>�����ĵ��иó��ơ�'.$inArr['carNum'].'���Ѵ����ó�����Ϊ'.$inArr['useCarDate'].'�ļ�¼</font>';
                                    $resultArr['result'] = false;
                                    array_push($resultArr ,$tempArr);
                                    $breakFlag=true;
                                    break;;
                                }
                            }else{
                                continue;
                            }
                        }
                        if($breakFlag){
                            continue;
                        }
                    }
                }
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!����Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

            // �Ƿ�Ϊ�ɵ�������
            $projectCode = $val['projectCode'];// ��Ŀ���
            $suppCode = $val['suppCode'];// ��Ӧ�̱���
            $carNum = trim($val['carNum']);// ���ƺ���
            $useCarMonth = substr($inArr['useCarDate'],0,7);// ��Ӧ�·�
            $chkResult = $this->chkRentCarRecord($projectCode,$suppCode,$carNum,$useCarMonth);
            if($chkResult && count($chkResult) > 0){
                $tempArr['docCode'] = '��' . $actNum .'������';
                $tempArr['result'] = '<font color=red>����ʧ��!�˳��ƺš�'.$carNum.'������������Ӧ���⳵�Ǽǻ��ܼ�¼������״̬Ϊ�����л���ɣ�, ������Ŀ����ͨ�����</font>';
                $resultArr['result'] = false;
                array_push($resultArr ,$tempArr);
                continue;
            }

			//����
			if(!empty($val['carModel']) && trim($val['carModel']) != '') {
				$inArr['carModel'] = trim($val['carModel']);
				$carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$inArr['carModel']);
				if ($carModelCode) {
					$inArr['carModelCode'] = $carModelCode;
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!���Ͳ�����</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!����Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//��ʼ���
			if(trim($val['startMileage']) != '') {
				$inArr['startMileage'] = trim($val['startMileage']);
				if (!is_numeric($inArr['startMileage'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!��ʼ��̱���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!��ʼ���Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//�������
			if(trim($val['endMileage']) != '') {
				$inArr['endMileage'] = trim($val['endMileage']);
				if (!is_numeric($inArr['endMileage'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!������̱���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}

				if ($inArr['endMileage'] >= $inArr['startMileage']) {
					$inArr['effectMileage'] = $inArr['endMileage'] - $inArr['startMileage'];
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!��ʼ��̴��ڽ������</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '��' . $actNum .'������';
				$tempArr['result'] = '<font color=red>����ʧ��!�������Ϊ��</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//������
			if(!empty($val['mealsCost']) && trim($val['mealsCost']) != '') {
				$inArr['mealsCost'] = trim($val['mealsCost']);
				if (!is_numeric($inArr['mealsCost'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!�����ѱ���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['mealsCost'] = 0;
			}

			//ס�޷�
			if(!empty($val['accommodationCost']) && trim($val['accommodationCost']) != '') {
				$inArr['accommodationCost'] = trim($val['accommodationCost']);
				if (!is_numeric($inArr['accommodationCost'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!ס�޷ѱ���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['accommodationCost'] = 0;
			}

			//ͣ����
			if(trim($val['parkingCost']) != '') {
				$inArr['parkingCost'] = trim($val['parkingCost']);
				if (!is_numeric($inArr['parkingCost'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!ͣ���ѱ���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['parkingCost'] = 0;
			}

            //·�ŷ�
            if(trim($val['tollCost']) != '') {
                $inArr['tollCost'] = trim($val['tollCost']);
                if (!is_numeric($inArr['tollCost'])) {
                    $tempArr['docCode'] = '��' . $actNum .'������';
                    $tempArr['result'] = '<font color=red>����ʧ��!·�ŷѱ���Ϊ������С��</font>';
                    $resultArr['result'] = false;
                    array_push($resultArr ,$tempArr);
                    continue;
                }
            } else {
                $inArr['tollCost'] = 0;
            }

			//�ͼ�
			if(!empty($val['gasolinePrice']) && trim($val['gasolinePrice']) != '') {
				$inArr['gasolinePrice'] = trim($val['gasolinePrice']);
				if (!is_numeric($inArr['gasolinePrice'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!�ͼ۱���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['gasolinePrice'] = 0;
			}

			//ʵ��ʵ���ͷ�
			if ($inArr['contractTypeCode'] == 'ZCHTLX-03') {
				if(trim($val['reimbursedFuel']) != '') {
					$inArr['reimbursedFuel'] = trim($val['reimbursedFuel']);
					if (!is_numeric($inArr['reimbursedFuel'])) {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!ʵ��ʵ���ͷѱ���Ϊ������С��</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!ʵ��ʵ���ͷ�Ϊ��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['reimbursedFuel'] = 0;
			}

			//�Ӱ��
			if(!empty($val['overtimePay']) && trim($val['overtimePay']) != '') {
				$inArr['overtimePay'] = trim($val['overtimePay']);
				if (!is_numeric($inArr['overtimePay'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!�Ӱ�ѱ���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['overtimePay'] = 0;
			}

			//�����ͷ�
			if(!empty($val['specialGas']) && trim($val['specialGas']) != '') {
				$inArr['specialGas'] = trim($val['specialGas']);
				if (!is_numeric($inArr['specialGas'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!�����ͷѱ���Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['specialGas'] = 0;
			}


			//���⳵��
			if ($inArr['rentalPropertyCode'] == 'ZCXZ-02') {
				if(trim($val['shortRent']) != '') {
					$inArr['shortRent'] = trim($val['shortRent']);
					if (!is_numeric($inArr['shortRent'])) {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!���⳵�ѱ���Ϊ������С��</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!���⳵��Ϊ��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['shortRent'] = 0;
			}

			//������Ƽ��ͷ�
			if ($inArr['rentalPropertyCode'] == 'ZCXZ-02' && $inArr['contractTypeCode'] == 'ZCHTLX-02') {
				if(trim($val['gasolineKMPrice']) != '') {
					$inArr['gasolineKMPrice'] = trim($val['gasolineKMPrice']);
					if (!is_numeric($inArr['gasolineKMPrice'])) {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!������Ƽ��ͷѱ���Ϊ������С��</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!������Ƽ��ͷ�Ϊ��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['gasolineKMPrice'] = 0;
			}

			//�г�����
			if(!empty($val['drivingReason']) && trim($val['drivingReason']) != '') {
				$inArr['drivingReason'] = trim($val['drivingReason']);
			} else {
				$inArr['drivingReason'] = '';
			}

			//��ЧLOGʱ��
			if(!empty($val['effectLogTime']) && trim($val['effectLogTime']) != '') {
				$inArr['effectLogTime'] = trim($val['effectLogTime']);
				if (!is_numeric($inArr['effectLogTime'])) {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!��ЧLOGʱ������Ϊ������С��</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['effectLogTime'] = 0;
			}

			//��ע
			if(!empty($val['remark']) && trim($val['remark']) != '') {
				$inArr['remark'] = trim($val['remark']);
			} else {
				$inArr['remark'] = '';
			}

			if ($inArr['rentalPropertyCode'] != 'ZCXZ-02') { //�Ƕ�����˶������Ϣ
				$rentcarDao = new model_outsourcing_contract_rentcar();
				$rentcarObj = $rentcarDao->getByCarAndDate_d($inArr['carNum'] ,$inArr['useCarDate']);
				if ($rentcarObj) {
					// if ($rentcarObj['carModelCode'] != $inArr['carModelCode']) {
					//  $tempArr['docCode'] = '��' . $actNum .'������';
					//  $tempArr['result'] = '<font color=red>����ʧ��!�Ǽǳ��͸���ͬ���Ͳ�һ��</font>';
					//  $resultArr['result'] = false;
					//  array_push($resultArr ,$tempArr);
					//  continue;
					// }
					if ($rentcarObj['signCompanyId'] != $inArr['suppId']) {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!�Ǽǹ�Ӧ�����⳵��ͬ��Ϣ��һ��</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
					if ($rentcarObj['contractTypeCode'] != $inArr['contractTypeCode']) {
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<font color=red>����ʧ��!�ǼǺ�ͬ�������⳵��ͬ��Ϣ��һ��</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					} else { //���ݺ�ͬ���ʴ�����Ӧ��Ϣ
						if ($rentcarObj['contractTypeCode'] == 'ZCHTLX-01') { //����
							$inArr['gasolinePrice'] = $rentcarObj['oilPrice'];
						} else if ($rentcarObj['contractTypeCode'] == 'ZCHTLX-02') { //�������Ʒ�
							$inArr['gasolineKMPrice'] = $rentcarObj['fuelCharge'];
						}
					}

					//�����ͬ���ӷ���
					if (!empty($val['fee']) && trim($val['fee']) != '') {
						$rentcarfeeDao = new model_outsourcing_contract_rentcarfee();
						$rentcarfeeObjs = $rentcarfeeDao->findAll(array('contractId' => $rentcarObj['contractId'] ,'isTemp' => 0));
						if (is_array($rentcarfeeObjs)) {
							$feeObj = explode('|' ,$val['fee']);
							if (is_array($feeObj)) {
								$i = 0; //��ʼ�������±�
								foreach ($feeObj as $ke => $va) {
									foreach ($rentcarfeeObjs as $k => $v) {
										if ($va == $v['feeName']) {
											$inArr['fee'][$i]['feeName'] = $v['feeName'];
											$inArr['fee'][$i]['contractId'] = $v['contractId'];
											$i++;
										}
									}
								}
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!�����ڵĺ�ͬ���ӷ�</font>';
							$resultArr['result'] = false;
							array_push($resultArr ,$tempArr);
							continue;
						}
					}
				} else {
					$tempArr['docCode'] = '��' . $actNum .'������';
					$tempArr['result'] = '<font color=red>����ʧ��!���ƺ�'.$inArr['carNum'].'δǩ����ͬ���ͬ�ѹ���</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			}

			$tempArr['result'] = '���Ե���';
			$tempArr['docCode'] = '��' . $actNum .'������';
			$tempArr['data'] = $inArr;
			array_push($resultArr ,$tempArr);
		}
		return $resultArr;
	}

	/**
	 * excel����Ԥ��ȷ�������
	 */
	function excelAdd_d($objs) {
		try {
			$this->start_d();

			if (is_array($objs)) {
				foreach ($objs as $key => $val) {
					$val['state'] = 1;
					//��ȡ������˾����
					$val['formBelong'] = $_SESSION['USER_COM'];
					$val['formBelongName'] = $_SESSION['USER_COM_NAME'];
					$val['businessBelong'] = $_SESSION['USER_COM'];
					$val['businessBelongName'] = $_SESSION['USER_COM_NAME'];

					$id = parent::add_d($val ,true);

					if ($id) {
						//���ӷ��ô���
						if (is_array($val['fee'])) {
							$feeDao = new model_outsourcing_vehicle_registerfee();
							$rentcarfeeDao = new model_outsourcing_contract_rentcarfee();
							$rentcarfeeObjs = $rentcarfeeDao->findAll(array('contractId' => $val['fee'][0]['contractId'] ,'isTemp' => 0));
							foreach ($rentcarfeeObjs as $ke => $va) {
								foreach ($val['fee'] as $k => $v) {
									if ($v['feeName'] == $va['feeName']) {
										$va['yesOrNo'] = 1;
										break;
									}
								}
								unset($va['id']);
								unset($va['isTemp']);
								unset($va['originalId']);
								unset($va['isDel']);
								$va['registerId'] = $id;
								$feeDao->add_d($va);
							}
						}

						$this->dealStatistics_d( $id ); //ͳ�Ʊ���
					}
				}
			}

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

     function excelAddNew_d($objs) {
         try {
             $this->start_d();

             $insertNum = 0;
             if (is_array($objs)) {
                 foreach ($objs as $key => $val) {
                     $newData = $val['data'];
                     if(is_array($newData)){
                         $newData['state'] = 0;
                         //��ȡ������˾����
                         $newData['formBelong'] = $_SESSION['USER_COM'];
                         $newData['formBelongName'] = $_SESSION['USER_COM_NAME'];
                         $newData['businessBelong'] = $_SESSION['USER_COM'];
                         $newData['businessBelongName'] = $_SESSION['USER_COM_NAME'];

                         //��ͬ���ӷ���
                         $feeArr = isset($newData['fee'])? $newData['fee'] : array();

                         $id = parent::add_d($newData ,true);

                         if ($id) {
                             $insertNum += 1;
                             //���ӷ��ô���
                             if (is_array($feeArr)) {
                                 $feeDao = new model_outsourcing_vehicle_registerfee();
                                 $rentcarfeeDao = new model_outsourcing_contract_rentcarfee();
                                 $rentcarfeeObjs = $rentcarfeeDao->findAll(array('contractId' => $feeArr[0]['contractId'] ,'isTemp' => 0));
                                 foreach ($rentcarfeeObjs as $ke => $va) {
                                     foreach ($feeArr as $k => $v) {
                                         if ($v['feeName'] == $va['feeName']) {
                                             $va['yesOrNo'] = 1;
                                             break;
                                         }
                                     }
                                     unset($va['id']);
                                     unset($va['isTemp']);
                                     unset($va['originalId']);
                                     unset($va['isDel']);
                                     $va['registerId'] = $id;
                                     $feeDao->add_d($va);
                                 }
                             }
                         }
                     }
                 }
             }

             $this->commit_d();
             return $insertNum;
         } catch (exception $e) {
             $this->rollBack();
             return false;
         }
     }

     /**
      * ���㵱ǰ��ʵʱ����ֵ
      * @param $dataArr
      * @param $mainObj
      * @return mixed
      */
    function countRealCostMoney($dataArr,$mainObj, $type = 'cz'){
        if($type == 'dz'){// ����
            $dataArr['realNeedPayCost1'] = 0;
            if(isset($mainObj['useCarDate']) && !empty($mainObj['useCarDate'])){
                $useCarDate = substr($mainObj['useCarDate'],0,7);
                $expenseTmpId = isset($dataArr['expenseTmpId1'])? $dataArr['expenseTmpId1'] : '';
                $allregisterId = isset($mainObj['allregisterId'])? $mainObj['allregisterId'] : '';

                $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
                $expensetmpData = $expensetmpDao->findExpenseTmpRecord($expenseTmpId,'','','',1);
                $carNumberStr = isset($expensetmpData['carNumber'])? $expensetmpData['carNumber'] : '';
                $carNumberArr = explode(",",$carNumberStr);

                $configuratorDao = new model_system_configurator_configurator();
                // ע��: �˴��߼��� controller/outsourcing/vehicle/rentalcar.php�ļ���c_getCostTypeByRentalCarType�����ڵ������Ƶ�,��Ҫͬʱ�޸�,����ʱ��Ļ������ϵ�һ���������
//                $this->getParam ( array("allregisterId" => $allregisterId, "useCarDateLimit" => $useCarDate) );
//                $this->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
//                $rows = $this->listBySqlId ( 'select_Month' );
                $rows = $this->getStatisticsJsonData($allregisterId,$useCarDate);
                if($rows){
                    $catchPayTypeDetail = array();
                    foreach ($rows as $key => $row){
                        if ($row['rentalContractId'] > 0) {
                            //�����⳵�Ѻͺ�ͬ�ó�����
                            $row['rentalCarCost'] = $this->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,false);
                            $row['contractUseDay'] = $this->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,true);

                            //����⳵
                            if ($row['rentalPropertyCode'] == 'ZCXZ-03') {
                                $row['rentalCarCost'] = $this->getHongdaFee_d($row['id'] ,$row['rentalContractId']);
                            }else{
                                $rows[$key]['rentalPropertyCode'] = $val['rentalPropertyCode'] = 'ZCXZ-01';// ���й�����ͬ�ŵ�,Ĭ��Ϊ����
                                $rows[$key]['rentalProperty'] = $val['rentalProperty'] = '����';
                            }
                        } else {
                            $rows[$key]['rentalCarCost'] = '';
                            $rows[$key]['contractUseDay'] = '';
                        }

                        if ($row['rentalPropertyCode'] == 'ZCXZ-02') { //��������
                            $obj = $this->get_d( $row['id'] );
                            $row['rentalCarCost'] = $rows[$key]['shortRent']; //�����ֱ����ʾ���⳵�ѵ��ۼ�
                            $row['gasolineKMPrice'] = $obj['gasolineKMPrice'];
                            $row['gasolineKMCost'] = $obj['gasolineKMPrice'] * $rows[$key]['effectMileage'];
                        }
                        if(in_array($row['carNum'],$carNumberArr) && $row['rentalPropertyCode'] != 'ZCXZ-01'){
                            $catchPayTypeDetail['gasolinePrice'] = isset($catchPayTypeDetail['gasolinePrice'])? $catchPayTypeDetail['gasolinePrice'] + $row['gasolinePrice'] : $row['gasolinePrice'];
                            $catchPayTypeDetail['gasolineKMCost'] = isset($catchPayTypeDetail['gasolineKMCost'])? $catchPayTypeDetail['gasolineKMCost'] + $row['gasolineKMCost'] : $row['gasolineKMCost'];
                            $catchPayTypeDetail['reimbursedFuel'] = isset($catchPayTypeDetail['reimbursedFuel'])? $catchPayTypeDetail['reimbursedFuel'] + $row['reimbursedFuel'] : $row['reimbursedFuel'];
                            $catchPayTypeDetail['parkingCost'] = isset($catchPayTypeDetail['parkingCost'])? $catchPayTypeDetail['parkingCost'] + $row['parkingCost'] : $row['parkingCost'];
                            $catchPayTypeDetail['tollCost'] = isset($catchPayTypeDetail['tollCost'])? $catchPayTypeDetail['tollCost'] + $row['tollCost'] : $row['tollCost'];
                            $catchPayTypeDetail['rentalCarCost'] = isset($catchPayTypeDetail['rentalCarCost'])? $catchPayTypeDetail['rentalCarCost'] + $row['rentalCarCost'] : $row['rentalCarCost'];
                            $catchPayTypeDetail['mealsCost'] = isset($catchPayTypeDetail['mealsCost'])? $catchPayTypeDetail['mealsCost'] + $row['mealsCost'] : $row['mealsCost'];
                            $catchPayTypeDetail['accommodationCost'] = isset($catchPayTypeDetail['accommodationCost'])? $catchPayTypeDetail['accommodationCost'] + $row['accommodationCost'] : $row['accommodationCost'];
                            $catchPayTypeDetail['overtimePay'] = isset($catchPayTypeDetail['overtimePay'])? $catchPayTypeDetail['overtimePay'] + $row['overtimePay'] : $row['overtimePay'];
                            $catchPayTypeDetail['specialGas'] = isset($catchPayTypeDetail['specialGas'])? $catchPayTypeDetail['specialGas'] + $row['specialGas'] : $row['specialGas'];
                        }
                    }
                    $countMoney = 0;
                    foreach ($catchPayTypeDetail as $ckey => $cval){
                        $matchConfigItem = $configuratorDao->getConfigItems('ZCFYMM','config_itemSub1',$ckey,array('config_itemSub3' => "1"));
                        if($matchConfigItem && count($matchConfigItem) > 0){
                            if(!empty($matchConfigItem[0]['config_itemSub2'])){
                                $countMoney += bcmul($cval,1,2);
                            }
                        }
                    }
                    $dataArr['realNeedPayCost1'] = $countMoney;
                }
            }
        }else{// ����
            foreach ($dataArr as $k => $v){
                $costMoney = 0;
                $includeFeeTypeCode = $v['includeFeeTypeCode'];
                $includeFeeTypeCodeArr = explode(",",$includeFeeTypeCode);
                $configuratorDao = new model_system_configurator_configurator();

                // ƴ�ӵ�ǰ�ķ�������ϸ
                foreach ($includeFeeTypeCodeArr as $key => $type){
                    $matchConfigItem = $configuratorDao->getConfigItems('ZCFYMM','config_itemSub1',$type,array('config_itemSub3' => "1"));
                    if($matchConfigItem && count($matchConfigItem) > 0){
                        if(!empty($matchConfigItem[0]['config_itemSub2']) && isset($mainObj[$type])){
                            $costMoney += $mainObj[$type];
                        }
                    }
                }
                $dataArr[$k]['realCostMoney'] = $costMoney;
            }
        }
        return $dataArr;
    }

     /**
      * ��ѯͬһ�������Ƿ��Ѵ���������Ӧ����������л�ͨ���˵��⳵�Ǽǻ���
      *
      * @param string $projectCode
      * @param string $suppCode
      * @param string $carNum
      * @param string $useCarMonth
      * @return mixed
      */
    function chkRentCarRecord($projectCode = '',$suppCode = '',$carNum = '',$useCarMonth = ''){
        $chkSql = <<<EOT
            select t.*,r.useCarDate,r.ExaStatus,r.state from(
                SELECT
                    c.id ,c.allregisterId ,c.suppId ,c.suppCode ,c.suppName ,c.projectId ,c.projectCode ,c.projectName ,c.useCarDate ,c.useCarNum,c.carNum 
                FROM
                    oa_outsourcing_register c
                    LEFT JOIN (
                    SELECT
                        * 
                    FROM
                        (
                        SELECT
                            d.id ,d.contractStartDate ,d.contractEndDate, d.contractNature ,d.status , d.isTemp ,d.contractType ,d.contractTypeCode ,d.orderCode, v.carNumber,
                            r.projectId,d.projectCode,	p.contractType as projectType,p.id as Pid,if(p.contractType='GCXMYD-04',m.projectId,p.id) as esmprojectId
                        FROM
                            oa_contract_rentcar d
                            LEFT JOIN oa_contract_vehicle v ON v.contractId = d.id
                            LEFT JOIN oa_outsourcing_rentalcar r ON r.id = d.rentalcarId
                            LEFT JOIN oa_esm_project p ON p.id = d.projectId
                            LEFT JOIN oa_esm_project_mapping m ON p.id = m.pkProjectId 
                        WHERE
                            d.isTemp = 0 
                            AND d.STATUS = 2 
                            AND v.isTemp = 0 
                        ORDER BY
                            d.id DESC 
                        ) innert 
                    WHERE
                        innert.orderCode <> '' 
                    GROUP BY
                        innert.orderCode, innert.carNumber 
                    ) t ON t.carNumber = c.carNum 
                    AND t.contractStartDate <= c.useCarDate AND t.contractEndDate >= c.useCarDate 
                AND
                IF
                    (
                        t.projectType = 'GCXMYD-04',
                        (
                            t.Pid = c.projectId 
                            OR t.esmprojectId = c.projectId 
                        ),
                        t.projectCode = c.projectCode 
                    ) 
                WHERE
                    c.state = 1 
                    AND (
                        (
                            DATE_FORMAT( c.useCarDate, "%Y-%m" ) = '$useCarMonth' 
                        ) 
                    ) 
                GROUP BY
                    YEAR ( c.useCarDate ),
                    MONTH ( c.useCarDate ),
                    c.allregisterId,
                    c.carNum
            )t 
            left join oa_outsourcing_allregister r on r.id = t.allregisterId
            where t.allregisterId <> ''
            and r.state in (1,2)
            and t.projectCode = '$projectCode'
            and t.suppCode = '$suppCode'
            and t.carNum = '$carNum';
EOT;
        $rows = $this->_db->getArray($chkSql);
        return $rows;
    }

     /**
      * �����⳵����ID�Լ����ƺŻ����Ӧ���ó�����
      *
      * @param string $carNum
      * @param string $allregisterId
      * @return int
      */
    function countUseCarDays($carNum = '',$allregisterId = ''){
        $sql = "select count(t.id) as num from(
                        SELECT
                        c.id
                    FROM
                        oa_outsourcing_register c 
                    WHERE
                        1 = 1
                        AND ( ( c.carNum = '{$carNum}' ) )
                        AND ( ( c.allregisterId = '{$allregisterId}' ) )
                        AND ( ( c.state = '1' ) ) 
                    GROUP BY c.useCarDate)t";
        $result = $this->_db->get_one($sql);
        $num = 0;
        if($result){
            $num = $result['num'];
        }
        return $num;
    }

     /**
      * ������Ӧ��Ϣ��ȡ������ܵ��⳵��
      * @param $cId
      * @param $allregisterId
      * @param $carNum
      * @return bool|string
      */
    function getRentalCarCost($cId,$allregisterId,$carNum){
        // ��ѯ�������⳵��ͬ�Ļ�����Ϣ
        $rentalContInfoSql = "select DATE_FORMAT(contractStartDate,'%Y%m%d') as startDate,DATE_FORMAT(contractEndDate,'%Y%m%d') as endDate,rentUnitPrice,rentUnitPriceCalWay from oa_contract_rentcar where id = '{$cId}'";
        $rentalContInfo = $this->_db->get_one($rentalContInfoSql);

        if($rentalContInfo){
            $contractCost = 0;// ��ͬ���ڵ��⳵��
            $contractStartDate = isset($rentalContInfo['startDate'])? $rentalContInfo['startDate'] : 0;
            $contractEndDate = isset($rentalContInfo['endDate'])? $rentalContInfo['endDate'] : 0;
            if(isset($rentalContInfo['rentUnitPriceCalWay'])){
                switch($rentalContInfo['rentUnitPriceCalWay']){
                    case "byDay":// ������
                        // ��ѯ��ͬ�����ڵ��⳵�Ǽ���Ϣ
                        if($contractStartDate > 0 && $contractEndDate > 0){
                            $countDaysSql = "select count(id) as days from oa_outsourcing_register c where c.carNum = '{$carNum}' and c.allregisterId = '{$allregisterId}' and c.state = '1' AND (DATE_FORMAT(useCarDate,'%Y%m%d') BETWEEN {$contractStartDate} AND {$contractEndDate})";
                            $countUseCarDaysArr = $this->_db->get_one($countDaysSql);
                            if($countUseCarDaysArr){
                                $countUseCarDays = isset($countUseCarDaysArr['days'])? $countUseCarDaysArr['days'] : 0;
                                $contractCost = isset($rentalContInfo['rentUnitPrice'])? round(bcmul($rentalContInfo['rentUnitPrice'],$countUseCarDays,4),2) : 0;
                            }
                        }
                        break;
                    default:// Ĭ�ϰ�����
                        $contractCost = isset($rentalContInfo['rentUnitPrice'])? $rentalContInfo['rentUnitPrice'] : 0;
                        break;
                }
            }

            // ��ѯ���ں�ͬ�����ڵ��⳵�Ǽ���Ϣ
            $registerCost = 0;
//            if($contractStartDate > 0 && $contractEndDate > 0){
//                $registerCostSumSql = "select sum(shortRent) as shortRent from oa_outsourcing_register c where c.carNum = '{$carNum}' and c.allregisterId = '{$allregisterId}' and c.state = '1' AND (DATE_FORMAT(useCarDate,'%Y%m%d') < {$contractStartDate} or DATE_FORMAT(useCarDate,'%Y%m%d') > {$contractEndDate})";
//                $registerCostSum = $this->_db->get_one($registerCostSumSql);
//                $registerCost = ($registerCostSum)? sprintf("%.2f", $registerCostSum['shortRent']) : 0;
//            }

            $contractCost = sprintf("%.2f", $contractCost);
            $registerCost = sprintf("%.2f", $registerCost);
            $totalRentalCarCost =  bcadd($contractCost,$registerCost,2);
            return $totalRentalCarCost;
        }else{
            return false;
        }
    }

     /**
      * �����⳵�Ǽǻ����Լ����ƺŻ�ȡ�ܵ��⳵����
      * @param $allregisterId
      * @param $carNum
      * @return string
      */
    function getRentalCarCostByRegisterId($allregisterId,$carNum){
        $param['dir'] = "ASC";
        $param['sort'] = "useCarDate";
        $param['allregisterId'] = $allregisterId;
        $param['carNum'] = $carNum;

        $this->getParam ( $param );
        $rows = $this->listBySqlId('select_default_forRecords');

        $rentalContractCost = array(
            "shortRentCost" => 0,
            "byMonth" => 0,
            "byDay" => 0,
        );
        if(is_array($rows)){
            foreach ($rows as $row){
                if(empty($row["rentUnitPriceCalWay"]) || $row["rentUnitPriceCalWay"] == 'null'){
                    $rentalContractCost["shortRentCost"] = round(bcadd($rentalContractCost["shortRentCost"],$row['shortRent'],3),2);
                }else if($row["rentUnitPriceCalWay"] == "byMonth"){
                    $rentalContractCost["byMonth"] = ($rentalContractCost["byMonth"] > 0)? $rentalContractCost["byMonth"] : $row['rentUnitPrice'];
                }else if($row["rentUnitPriceCalWay"] == "byDay"){
                    $rentalContractCost["byDay"] = round(bcadd($rentalContractCost["byDay"],$row['rentUnitPrice'],3),2);
                }
            }
        }

        $rentalCarCost = bcadd($rentalContractCost['shortRentCost'],bcadd($rentalContractCost['byMonth'],$rentalContractCost['byDay'],6),6);
        return $rentalCarCost;
    }

     /**
      * ��ȡ���������ĺ�ͬ��
      * @param $carNum
      * @param $allregisterId
      * @return array
      */
    function getCarsBelongContractDate($carNum,$allregisterId){
        $backArr = array(
            "contractStartDate" => "",
            "contractEndDate" => ""
        );
        $useCarMonthSql = "select DATE_FORMAT(useCarDate,'%Y%m') as useCarMonth,projectId from oa_outsourcing_allregister where Id = '{$allregisterId}'";
        // ��ȡ��¼�������·�
        $useCarMonth = $this->_db->get_one($useCarMonthSql);
        $projectId  = ($useCarMonth)? $useCarMonth['projectId'] : '';
        $useCarMonth = ($useCarMonth)? $useCarMonth['useCarMonth'] : '';

        // ��ȡ��صĺ�ͬ�ڼ�
        $rentalConDateChkSql = "select c.contractStartDate,c.contractEndDate  from oa_contract_vehicle v left join oa_contract_rentcar c on c.id = v.contractId where c.isTemp = 0 and ".
            "'{$useCarMonth}' >= DATE_FORMAT(c.contractStartDate,\"%Y%m\") AND '{$useCarMonth}' <= DATE_FORMAT(c.contractEndDate,\"%Y%m\") AND c.projectId = '{$projectId}' AND v.carNumber = '{$carNum}' and c.isTemp = 0";
        $rentalConDateChk = $this->_db->get_one($rentalConDateChkSql);
        if($rentalConDateChk){
            $backArr['contractStartDate'] = $rentalConDateChk['contractStartDate'];
            $backArr['contractEndDate'] = $rentalConDateChk['contractEndDate'];
        }

        return $backArr;
    }

    function getStatisticsJsonData($allregisterId = '',$useCarDateLimit = ''){
        $allregisterId = isset($_REQUEST['allregisterId'])? $_REQUEST['allregisterId'] : $allregisterId;
        $useCarDateLimit = isset($_REQUEST['useCarDateLimit'])? $_REQUEST['useCarDateLimit'] : $useCarDateLimit;
        unset($_REQUEST['allregisterId']);
        unset($_REQUEST['useCarDateLimit']);
        $this->getParam ( $_REQUEST );
        $sql = <<<EOT
        select * from (
            select 
                T.id,T.allregisterId,T.suppId,T.suppCode,T.suppName,T.projectId,T.projectCode,T.projectName,T.projectType,T.projectTypeCode,T.projectManager,T.officeName,T.officeId,T.province,T.provinceId,T.city,T.cityId,T.useCarDate,T.useCarNum,T.driverName,
                if(T.shortRentTip = '1','����',T.rentalProperty) as rentalProperty,
                if(T.shortRentTip = '1','ZCXZ-01',T.rentalPropertyCode) as rentalPropertyCode,
                T.carNum,T.drivingReason,T.remark,T.estimate,T.estimateTime,T.state,T.deductInformation,T.changeReason,T.createName,T.createId,T.createTime,T.updateName,T.updateId,T.updateTime,T.isCardPay,T.carModel,T.carModelCode,T.rentalContractId,T.rentUnitPriceCalWay,T.rentalContractNature,T.rentalContractCode,T.contractUseDay,
                SUM( T.shortRent ) AS shortRent,
                (
                    SUM( T.effectMileage ) *
                IF
                    (
                        T.rentalPropertyCode = 'ZCXZ-01',
                        T.fuelCharge,
                        T.gasolineKMPrice 
                    ) 
                ) AS gasolineKMCost,
                SUM( T.effectMileage ) AS effectMileage,
                SUM( T.reimbursedFuel ) AS reimbursedFuel,
                SUM( T.parkingCost ) AS parkingCost,
                SUM( T.tollCost ) AS tollCost,
                SUM( T.mealsCost ) AS mealsCost,
                SUM( T.overtimePay ) AS overtimePay,
                SUM( T.specialGas ) AS specialGas,
                SUM( T.accommodationCost ) AS accommodationCost,
                SUM( T.effectLogTime ) AS effectLogTime,
                (
                    SUM( T.effectMileage ) * T.fuelCharge + SUM( T.reimbursedFuel ) + SUM( T.parkingCost ) + SUM( T.tollCost ) + SUM( T.mealsCost ) + SUM( T.accommodationCost ) + SUM( T.overtimePay ) + SUM( T.specialGas ) + T.rentalCarCost 
                ) AS allCost,
                T.rentalCarCost,T.oilPrice AS gasolinePrice,
                IF
                (
                    T.rentalPropertyCode = 'ZCXZ-01',
                    T.fuelCharge,
                    T.gasolineKMPrice 
                ) AS gasolineKMPrice,
                COUNT( T.id ) AS registerNum,GROUP_CONCAT( T.id ) AS registerIds
            from (
            select 
                c.id,c.allregisterId,c.suppId,c.suppCode,c.suppName,c.projectId,c.projectCode,c.projectName,c.projectType,c.projectTypeCode,c.projectManager,c.officeName,c.officeId,c.province,c.provinceId,c.city,c.cityId,c.useCarDate,c.useCarNum,c.driverName,c.rentalProperty,c.rentalPropertyCode,c.carNum,c.drivingReason,c.remark,c.estimate,c.estimateTime,c.state,c.deductInformation,c.changeReason,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime,c.isCardPay,c.carModel,c.carModelCode,t.shortRentTip,c.shortRent,t.fuelCharge,c.gasolineKMPrice,c.reimbursedFuel,c.parkingCost,c.tollCost,c.mealsCost,c.overtimePay,c.specialGas,c.effectMileage,c.accommodationCost,c.effectLogTime,t.oilPrice,c.rentalCarCost,t.id AS rentalContractId,t.rentUnitPriceCalWay,t.contractNature AS rentalContractNature,t.orderCode AS rentalContractCode,t.contractUseDay
            FROM
                oa_outsourcing_register c
                LEFT JOIN (
                SELECT
                    * 
                FROM
                    (
                    SELECT
                        d.id,d.contractNature,d.contractNatureCode,d.contractType,d.contractTypeCode,d.orderCode,d.orderTempCode,d.orderName,d.principalName,d.principalId,d.deptName,d.deptId,d.signCompany,d.signCompanyId,d.companyProvince,d.companyProvinceCode,d.companyCity,d.companyCityCode,d.orderMoney,d.signDate,d.contractStartDate,if(d.contractStartDate <> '',"1","") AS shortRentTip,d.contractEndDate,d.contractUseDay,d.linkman,d.phone,d.address,d.isNeedStamp,d.stampType,d.ownCompany,d.ownCompanyId,d.ownCompanyCode,d.fundCondition,d.contractContent,d.remark,d.isStamp,d.STATUS,d.isTemp,d.originalId,d.changeTip,d.changeReason,d.isNeedRestamp,d.isNeedPayables,d.feeDeptName,d.feeDeptId,d.returnMoney,d.rentalcarId,d.rentalcarCode,d.rentUnitPriceCalWay,d.rentUnitPrice,d.oilPrice,d.fuelCharge,d.objCode,d.signedStatus,d.signedDate,d.signedMan,d.signedManId,d.ExaStatus,d.ExaDT,d.createId,d.createName,d.createTime,d.updateId,d.updateName,d.updateTime,date_format( d.createTime, '%Y-%m-%d' ) AS createDate,v.carNumber,r.projectId,d.projectCode,p.contractType AS projectType,p.id AS Pid,
                    IF
                        (
                            p.contractType = 'GCXMYD-04',
                            m.projectId,
                            p.id 
                        ) AS esmprojectId 
                    FROM
                        oa_contract_rentcar d
                        LEFT JOIN oa_contract_vehicle v ON v.contractId = d.id
                        LEFT JOIN oa_outsourcing_rentalcar r ON r.id = d.rentalcarId
                        LEFT JOIN oa_esm_project p ON p.id = d.projectId
                        LEFT JOIN (select t.pkProjectId,CONCAT(",",t.projectId,",") AS projectId from(select GROUP_CONCAT(projectId) as projectId,pkProjectId from oa_esm_project_mapping GROUP BY pkProjectId)t) m ON p.id = m.pkProjectId  
                    WHERE
                        d.isTemp = 0 
                        AND d.STATUS = 2 
                        AND v.isTemp = 0 
                    ORDER BY
                        d.id DESC 
                    ) innert 
                WHERE
                    innert.orderCode <> '' 
                GROUP BY
                    innert.orderCode,
                    innert.carNumber 
                ) t ON t.carNumber = c.carNum 
                AND t.contractStartDate <= c.useCarDate AND t.contractEndDate >= c.useCarDate 
            AND
            IF
                (
                    t.projectType = 'GCXMYD-04',
                    (
                        t.Pid = c.projectId 
                        OR t.esmprojectId like CONCAT("%,",c.projectId,",%") 
                    ),
                    t.projectCode = c.projectCode 
                ) 
            WHERE
                c.state = 1 
                AND ( ( c.allregisterId = '$allregisterId' ) ) 
                AND (
                    (
                        DATE_FORMAT( c.useCarDate, "%Y-%m" ) = '$useCarDateLimit' 
                    ) 
                ) 
            )T 
            GROUP BY
                T.shortRentTip,
                YEAR ( T.useCarDate ),
                MONTH ( T.useCarDate ),
                T.carNum 
            ORDER BY
                T.id ASC
        )c
EOT;
        $this->asc = "DESC";
        $this->sort = 'c.rentalPropertyCode';
        $rows = $this->listBySql ( $sql );
        return $rows;
    }
}
?>