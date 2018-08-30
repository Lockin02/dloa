<?php
/**
 * @author Michael
 * @Date 2014年2月10日 星期一 18:40:56
 * @version 1.0
 * @description:租车登记表 Model层
 */
 class model_outsourcing_vehicle_register  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_register";
		$this->sql_map = "outsourcing/vehicle/registerSql.php";
		parent::__construct ();
	}

	//公司权限处理 TODO
	// protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

	/**
	 * 重写add
	 */
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //租车性质
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //合同类型
			$object['carModel'] = $datadictDao->getDataNameByCode($object['carModelCode']); //车型

			//获取归属公司名称
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object);

			if ($id) {
				//附加费用处理
				if (is_array($object['fee'])) {
					$feeDao = new model_outsourcing_vehicle_registerfee();
					foreach ($object['fee'] as $key => $val) {
						$val['registerId'] = $id;
						$feeDao->add_d($val);
					}
				}

				//统计表处理
				if ($object['state'] == '1') {
					$this->dealStatistics_d( $id );
				}
			}

			//添加附件关联关系
			$this->updateObjWithFile ( $id );

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写edit
	 */
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //租车性质
			$object['contractType'] = $datadictDao->getDataNameByCode($object['contractTypeCode']); //合同类型
			$object['carModel'] = $datadictDao->getDataNameByCode($object['carModelCode']); //车型

			$id = parent :: edit_d($object, true);

			if ($id) {
				//附加费用处理
				$feeDao = new model_outsourcing_vehicle_registerfee();
				$feeDao->delete(array('registerId' => $object['id']));
				if (is_array($object['fee'])) {
					foreach ($object['fee'] as $key => $val) {
						$val['registerId'] = $object['id'];
						$feeDao->add_d($val);
					}
				}

				//统计表处理
				if ($object['state'] == '1') {
					$this->dealStatistics_d($object['id']);
				}
			}

			//更新附件关联关系
			$this->updateObjWithFile ($object['id']);

			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 添加评价和扣款信息
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
	 * 添加合同相关信息
	 */
	function addContract_d( $obj ){
		try {
			$this->start_d();

			if(is_array($obj)) {
				$rentcarDao = new model_outsourcing_contract_rentcar();
				foreach($obj as $key => $val) {
					$rentcarObj = $rentcarDao->get_d($val['rentalContractId']);
					$object = $this->get_d( $val['id'] );

					//计算用车天数和租车费
//					$useDays = $this->getDaysOrFee_d($val['id'] ,$val['rentalContractId']);
//					$rentalCarCost = $rentcarObj['rentUnitPrice'] / 30 * $useDays; //租车费用

					//如果为短租的话，租车费用以下的计算方法
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
					} else if ($object['rentalPropertyCode'] == 'ZCXZ-03') { //宏达租车
						$rentalCarCost = $this->getHongdaFee_d($val['id'] ,$val['rentalContractId']);
					}

                    if($val['rentalContractId'] > 0){// 如果有关联租车合同, 租车费默认取合同的费用项金额
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
	 * 变更信息
	 */
	function change_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();

			$logSettringDao = new model_syslog_setting_logsetting ();
			$allregisterDao = new model_outsourcing_vehicle_allregister();
			$object['carModel'] = $datadictDao->getDataNameByCode($object['carModelCode']); //车型
			$oldObj = $this->get_d ($object['id']);
			$allregisterDao->changeStatistics_d( $oldObj ,$object ); //统计表变更处理
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object ); //操作日志

			$id = parent :: edit_d($object, true);

			if ($id) {
				//附加费用处理
				$feeDao = new model_outsourcing_vehicle_registerfee();
				$feeDao->delete(array('registerId' => $object['id']));
				if (is_array($object['fee'])) {
					foreach ($object['fee'] as $key => $val) {
						$val['registerId'] = $object['id'];
						$feeDao->add_d($val);
					}
				}
			}

			//更新附件关联关系
			$this->updateObjWithFile ($object['id']);

			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 提交一个
	 */
	function submit_d($id,$act = "") {
	    $idArr = ($act == "batch")? explode(",",$id) : array();
		try {
			$this->start_d();

            if($act == "batch" && !empty($idArr)){
                foreach ($idArr as $bId){
                    $obj = array('id' => $bId ,'state' => 1);
                    $rs = parent::edit_d($obj ,true);
                    $this->dealStatistics_d( $bId ); //统计表处理
                }
            }else{
                $obj = array('id' => $id ,'state' => 1);
                $rs = parent::edit_d($obj ,true);
                $this->dealStatistics_d( $id ); //统计表处理
            }

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据登记表ID进行统计表的处理
	 */
	function dealStatistics_d( $id ) {
		try {
			$this->start_d();

			$object = $this->get_d( $id );

			$allregisterDao = new model_outsourcing_vehicle_allregister();
			$object['useCarDate'] = substr_replace($object['useCarDate'] ,'00' ,-2 ,2); //日期的日格式化，作为月份统计
			$result = $allregisterDao->find(array('projectId' => $object['projectId'] ,'useCarDate' => $object['useCarDate'] ,'state' => 0)); //未提交的
			if (!$result) {
				$result = $allregisterDao->find(array('projectId' => $object['projectId'] ,'useCarDate' => $object['useCarDate'] ,'state' => 3)); //打回的
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
			$allregisterObj['overtimePay'] = $object['overtimePay']; //加班费
			$allregisterObj['specialGas'] = $object['specialGas']; //特殊油费

			$allregisterObj['actualUseDay'] = 1; //实际用车天数初始化为1
			if (!$result) {
				//获取归属公司名称
				$allregisterObj['formBelong'] = $_SESSION['USER_COM'];
				$allregisterObj['formBelongName'] = $_SESSION['USER_COM_NAME'];
				$allregisterObj['businessBelong'] = $_SESSION['USER_COM'];
				$allregisterObj['businessBelongName'] = $_SESSION['USER_COM_NAME'];

				$allregisterId = $allregisterDao->add_d($allregisterObj); //如果为第一次登记，则添加统计记录
			} else {
				$allregisterId = $allregisterDao->updateStatistics_d($result['id'] ,$allregisterObj); //否则，更新统计记录
			}

			$this->update(array('id' => $id) ,array('allregisterId' => $allregisterId)); //登记表跟统计表关联

			$this->commit_d();
			return true;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 根据租车登记表ID和合同ID求租车费用计算的天数
	 * @param $info为true返回计算天数，为false返回根据合同租车单价计算好租车费
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
		$contractStartDate = strtotime($rentcarObj['contractStartDate']); //合同开始日期
		$contractEndDate = strtotime($rentcarObj['contractEndDate']); //合同结束日期
		$tmp = $this->find($conditions ,"useCarDate ASC" ,"useCarDate");
		$startUseCarDate = strtotime($tmp['useCarDate']); //当月第一次用车日期
		$tmp = $this->find($conditions ,"useCarDate DESC" ,"useCarDate");
		$endUseCarDate = strtotime($tmp['useCarDate']); //当月最后一次用车日期
		$useDays = 0;
		if (date("Y-m-d" ,$startUseCarDate) >= date("Y-m-d" ,$contractStartDate)
				&& date("Y-m-d" ,$endUseCarDate) <= date("Y-m-d" ,$contractEndDate)) { //用车在合同期内

			if (date("Y-m" ,$startUseCarDate) == date("Y-m" ,$contractStartDate)) { //用车在合同开始月
				if (date("Y-m" ,$endUseCarDate) == date("Y-m" ,$contractEndDate)) { //用车在合同结束月
					if ((int)date("j" ,$contractStartDate) == 1
							&& (int)date("j" ,$contractEndDate) == (int)date("t" ,$contractEndDate)) { //月初和月末判断是否满月
						$useDays = 30;
					} else {
						$useDays = (int)date("j" ,$contractEndDate) - (int)date("j" ,$contractStartDate) + 1;
					}
				} else {
					$useDays = 30 - (int)date("j" ,$contractStartDate) + 1;
				}
			} else if (date("Y-m" ,$startUseCarDate) == date("Y-m" ,$contractEndDate)) { //用车在结束月
				if ((int)date("j" ,$contractEndDate) == (int)date("t" ,$contractEndDate)) { //是否在月的最后一天结束
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
	 * 根据租车登记ID和合同ID求租车费用
	 */
	function getHongdaFee_d($registerId ,$rentalContractId) {
		$rentcarDao = new model_outsourcing_contract_rentcar();
		$rentcarObj = $rentcarDao->get_d( $rentalContractId );
		$object = $this->get_d( $registerId );
		$objs = $this->findAll(array('allregisterId' => $object['allregisterId'] ,'carNum' => $object['carNum']));
		$fee = 0.00; //初始化租车费
		$otherFee = 0.00; //初始化合同附加费用
		if (is_array($objs)) {
			$rencarfeeDao = new model_outsourcing_contract_rentcarfee();
			$rencarfeeObjs = $rencarfeeDao->findAll(array('contractId' => $rentalContractId ,'isTemp' => 0));
			if (is_array($rencarfeeObjs)) {
				$feeDao = new model_outsourcing_vehicle_registerfee();
				foreach ($rencarfeeObjs as $key => $val) { //循环合同附加费用
					if (!isset($rencarfeeObjs[$key]['days'])) {
						$rencarfeeObjs[$key]['days'] = 0;
					}
					foreach ($objs as $k => $v) { //循环该车单月的租车登记
						$feeObj = $feeDao->findAll(array('registerId' => $v['id'] ,'yesOrNo' => 1));
						if (is_array($feeObj)) {
							foreach ($feeObj as $ke => $va) { //循环每天的合同附加费用
								if ($va['feeName'] == $val['feeName'] && $va['feeAmount'] == $val['feeAmount']) {
									$rencarfeeObjs[$key]['days']++;
								}
							}
						}
					}
				}
				//计算附加总费用
				foreach ($rencarfeeObjs as $key => $val) {
					if ($val['days'] > 0) {
						$otherFee += ($val['feeAmount'] / 23 * $val['days']);
					}
				}
				$otherFee = ceil($otherFee * 100) / 100; //进1并且保留两位小数
			}
			$fee = $rentcarObj['rentUnitPrice'] / 23 * count($objs);
			$fee = ceil($fee * 100) / 100; //进1并且保留两位小数
		}
		return ($fee + $otherFee);
	}

	/**
	 * 根据租车登记id数组获取当月车辆的所有费用
	 */
	function getAllKindsFeeById_d($ids) {
		$idArr = explode(',' ,$ids);
		//初始化返回值
		$result = array(
			'id' => $ids ,
			'allCost' => 0 ,
			'fee' => array(
				//租车费
				array(
					'costTypeId' => 'YSLX-02',
					'costMoney'  => 0
				),
				//油费
				array(
					'costTypeId' => 'YSLX-03',
					'costMoney'  => 0
				),
				//停车费
				array(
					'costTypeId' => 'YSLX-04',
					'costMoney'  => 0
				),
                //路桥费
				array(
					'costTypeId' => 'YSLX-08',
					'costMoney'  => 0
				),
				//餐饮费
				array(
					'costTypeId' => 'YSLX-05',
					'costMoney'  => 0
				),
				//住宿费
				array(
					'costTypeId' => 'YSLX-06',
					'costMoney'  => 0
				),
				//加班费
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
	 * 根据租车登记id获取当月车辆的所有费用
	 */
	function getAllKindsFeeByOne_d($id) {
		$obj = $this->get_d($id);
		$objs = $this->findAll(array('allregisterId' => $obj['allregisterId'] ,'carNum' => $obj['carNum'] ,'state' => 1));
		$reimbursedFuel    = 0; //实报实销油费
		$gasolineKMCost    = 0; //按公里计价油费
		$parkingCost       = 0; //停车费
        $tollCost          = 0; //路桥费
		$rentalCarCost     = 0; //租车费
		$mealsCost         = 0; //餐饮费
		$accommodationCost = 0; //住宿费
		$overtimePay       = 0; //加班费
		$specialGas        = 0; //特殊油费
		$allCost           = 0; //总费用

		if (is_array($objs)) {
			$vehicleDao = new model_outsourcing_contract_vehicle();
			foreach ($objs as $key => $val) {
				//实报实销油费
				$reimbursedFuel += ($val['reimbursedFuel'] > 0 ? $val['reimbursedFuel'] : 0);

				//按公里计价油费
				$gasolineKMCost += ($val['gasolineKMCost'] > 0 ? $val['gasolineKMCost'] : 0);

				//停车费
				$parkingCost += ($val['parkingCost'] > 0 ? $val['parkingCost'] : 0);

                //路桥费
				$tollCost += ($val['tollCost'] > 0 ? $val['tollCost'] : 0);

				//租车费
				if ($val['rentalPropertyCode'] == 'ZCXZ-02') { //短租
					$rentalCarCost += ($val['shortRent'] > 0 ? $val['shortRent'] : 0);
				} else if ($val['rentalPropertyCode'] == 'ZCXZ-03') { //宏达租车
					$rentalCarCost = 0; //这里的从新初始化是为了防止登记错误
					$rentalCarCost = $this->getHongdaFee_d($val['id'] ,$val['rentalContractId']);
				} else {
					$rentalCarCost = 0; //这里的从新初始化是为了防止登记错误
					$rentalCarCost = $this->getDaysOrFee_d($val['id'] ,$val['rentalContractId'] ,false);
				}

				//餐饮费
				$mealsCost += ($val['mealsCost'] > 0 ? $val['mealsCost'] : 0);

				//住宿费
				$accommodationCost += ($val['accommodationCost'] > 0 ? $val['accommodationCost'] : 0);

				//加班费
				$overtimePay += ($val['overtimePay'] > 0 ? $val['overtimePay'] : 0);

				//特殊油费
				$specialGas += ($val['specialGas'] > 0 ? $val['specialGas'] : 0);

				//合同里选择了油卡抵充的车辆，自动在生成报销单的时候无需计算油费，但特殊油费保留
				if ($val['rentalContractId'] > 0) { //存在合同
					$vehicleObj = $vehicleDao->find(
						array(
							'contractId' => $val['rentalContractId'],
							'carNumber'  => $val['carNum'],
							'isTemp'     => 0,
							'oilCarUse'  => '是'
						)
					);
					if ($vehicleObj) {
						$reimbursedFuel = $gasolineKMCost = 0;
					}
				}
			}
		}

		$oilFee = $reimbursedFuel + $gasolineKMCost + $specialGas; //油费=实报实销油费+公里数计费+特殊油费
		$oilFee = ceil($oilFee * 100) / 100; //进1并且保留两位小数

		$allCost = (double)$oilFee
				 + (double)$parkingCost
                 + (double)$tollCost
				 + (double)$rentalCarCost
				 + (double)$mealsCost
				 + (double)$accommodationCost
				 + (double)$overtimePay;
		$allCost = ceil($allCost * 100) / 100; //进1并且保留两位小数

		$result = array('id' => $id ,'allCost' => $allCost ,'fee' => array()); //初始化返回值
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-02' ,'costMoney' => $rentalCarCost)); //租车费
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-03' ,'costMoney' => $oilFee)); //油费
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-04' ,'costMoney' => $parkingCost)); //停车费
        array_push($result['fee'] ,array('costTypeId' => 'YSLX-08' ,'costMoney' => $tollCost)); //路桥费
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-05' ,'costMoney' => $mealsCost)); //餐饮费
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-06' ,'costMoney' => $accommodationCost)); //住宿费
		array_push($result['fee'] ,array('costTypeId' => 'YSLX-07' ,'costMoney' => $overtimePay)); //加班费

		return $result;
	}

	/**
	 * 付款（付款和报销）后调用接口，更新已付款金额
	 * @param array(array('id'=>'登记表id','money'=>'付款金额','payType'=>0:费用报销;1:付款申请)……)
	 */
	function dealAfterPay_d($payObj) {
		try {
			$this->start_d();

			if (is_array($payObj)) {
				$allDao = new model_outsourcing_vehicle_allregister();
				foreach ($payObj as $key => $val) {
					$obj = $this->get_d($val['id']);
					if ($obj) {
						//批量更新租车登记表付款信息
						$conditions = array(
							'allregisterId' => $obj['allregisterId'],
							'carNum'        => $obj['carNum'],
							'state'         => 1
						);
						$row = array(
							'payType'  => $val['payType'],
							'payMoney' => $val['money']
						);

						//这里这样写是因为费用分摊无法区分不同登记的id，所以只能传一次总的费用，其他登记的费用都是-1
						if ($val['money'] >= 0) {
							$this->update($conditions ,$row);

							//更新汇总表付款信息
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
	 * excel导入
	 */
	function addExecelData_d(){
		set_time_limit(0);
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//结果数组
		$excelData = array ();//excel数据数组
		$tempArr = array();
		$inArr = array();//插入数组
		$datadictDao = new model_system_datadict_datadict();
		//判断导入类型是否为excel表
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name, 1);
            $excelHeaderArr = $excelData[0];
            unset($excelData[0]);

			spl_autoload_register("__autoload");
            $transData = array();
            if(!in_array("停车费",$excelHeaderArr) || !in_array("路桥费",$excelHeaderArr)) {// 根据导入模板的标题判断模板是否为最新
                $tempArr['docCode'] = '导入模板有误';
                $tempArr['result'] = '<font color=red>请下载最新的导入模板后重试!</font>';
                array_push($resultArr ,$tempArr);
            }else if(is_array($excelData)) {
				//先对数组进行转换
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

				$esmDao = new model_engineering_project_esmproject(); //工程项目
				$provinceDao = new model_system_procity_province(); //城市
				$cityDao = new model_system_procity_city(); //省份
				$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp(); //车辆供应商
				//行数组循环
				foreach($transData as $key => $val){
					$actNum = $key + 1;
					if(empty($val['projectCode']) && empty($val['useCarDate']) && empty($val['suppCode'])) {
						continue;
					} else {
						//新增数组
						$inArr = array();

						//项目编号
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
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!项目编号不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!项目编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//省份
						if(!empty($val['province']) && trim($val['province']) != '') {
							$inArr['province'] = trim($val['province']);
							$provinceObj = $provinceDao->find(array('provinceName' => $inArr['province']));
							if ($provinceObj) {
								$inArr['provinceId'] = $provinceObj['id'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!省份不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//城市
						if(!empty($val['city']) && trim($val['city']) != '') {
							$inArr['city'] = trim($val['city']);
							$cityObj = $cityDao->find(array('cityName' => $inArr['city']));
							if ($cityObj) {
								$inArr['cityId'] = $cityObj['id'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!城市不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//用车日期
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
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!用车日期为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//供应商编号
						if(!empty($val['suppCode']) && trim($val['suppCode']) != '') {
							$inArr['suppCode'] = trim($val['suppCode']);
							$suppObj = $suppDao->find(array('suppCode'=>$inArr['suppCode']));
							if ($suppObj) {
								$inArr['suppId'] = $suppObj['id'];
								$inArr['suppName'] = $suppObj['suppName'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!供应商编号不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!供应商编号为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//司机姓名
						if(!empty($val['driverName']) && trim($val['driverName']) != '') {
							$inArr['driverName'] = trim($val['driverName']);
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!司机姓名为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//租车性质
						if(!empty($val['rentalProperty']) && trim($val['rentalProperty']) != '') {
							$inArr['rentalProperty'] = trim($val['rentalProperty']);
							$rentalPropertyCode = $datadictDao->getCodeByName('WBZCXZ' ,$inArr['rentalProperty']);
							if ($rentalPropertyCode) {
								$inArr['rentalPropertyCode'] = $rentalPropertyCode;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!租车性质不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!租车性质为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//合同类型
						if(!empty($val['contractType']) && trim($val['contractType']) != '') {
							$inArr['contractType'] = trim($val['contractType']);
							$contractTypeCode = $datadictDao->getCodeByName('ZCHTLX' ,$inArr['contractType']);
							if ($contractTypeCode) {
								$inArr['contractTypeCode'] = $contractTypeCode;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!合同类型不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!合同类型为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//是否使用油卡支付
						if(!empty($val['isCardPay']) && trim($val['isCardPay']) != '') {
							$isCardPay = trim($val['isCardPay']);
							if ($isCardPay == '是，使用油卡支付') {
								$inArr['isCardPay'] = 1;
							} else {
								$inArr['isCardPay'] = 0;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!是否使用油卡支付为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//车牌
						if(!empty($val['carNum']) && trim($val['carNum']) != '') {
							$inArr['carNum'] = trim($val['carNum']);
							$rs = $this->isCanAdd_d($inArr);
							if (!$rs) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>项目上该车牌已存在用车日期为'.$inArr['useCarDate'].'的记录</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!车牌为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//车型
						if(!empty($val['carModel']) && trim($val['carModel']) != '') {
							$inArr['carModel'] = trim($val['carModel']);
							$carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$inArr['carModel']);
							if ($carModelCode) {
								$inArr['carModelCode'] = $carModelCode;
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!车型不存在</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!车型为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//开始里程
						if(trim($val['startMileage']) != '') {
							$inArr['startMileage'] = trim($val['startMileage']);
							if (!is_numeric($inArr['startMileage'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!开始里程必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!开始里程为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//结束里程
						if(trim($val['endMileage']) != '') {
							$inArr['endMileage'] = trim($val['endMileage']);
							if (!is_numeric($inArr['endMileage'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!结束里程必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}

							if ($inArr['endMileage'] >= $inArr['startMileage']) {
								$inArr['effectMileage'] = $inArr['endMileage'] - $inArr['startMileage'];
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!开始里程大于结束里程</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!结束里程为空</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//餐饮费
						if(!empty($val['mealsCost']) && trim($val['mealsCost']) != '') {
							$inArr['mealsCost'] = trim($val['mealsCost']);
							if (!is_numeric($inArr['mealsCost'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!餐饮费必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['mealsCost'] = 0;
						}

						//住宿费
						if(!empty($val['accommodationCost']) && trim($val['accommodationCost']) != '') {
							$inArr['accommodationCost'] = trim($val['accommodationCost']);
							if (!is_numeric($inArr['accommodationCost'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!住宿费必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['accommodationCost'] = 0;
						}

						//停车费
						if(trim($val['parkingCost']) != '') {
							$inArr['parkingCost'] = trim($val['parkingCost']);
							if (!is_numeric($inArr['parkingCost'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!停车费必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['parkingCost'] = 0;
						}

						//路桥费
						if(trim($val['tollCost']) != '') {
							$inArr['tollCost'] = trim($val['tollCost']);
							if (!is_numeric($inArr['tollCost'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!路桥费必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['tollCost'] = 0;
						}

						//油价
						if(!empty($val['gasolinePrice']) && trim($val['gasolinePrice']) != '') {
							$inArr['gasolinePrice'] = trim($val['gasolinePrice']);
							if (!is_numeric($inArr['gasolinePrice'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!油价必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['gasolinePrice'] = 0;
						}

						//实报实销油费
						if ($inArr['contractTypeCode'] == 'ZCHTLX-03') {
							if(trim($val['reimbursedFuel']) != '') {
								$inArr['reimbursedFuel'] = trim($val['reimbursedFuel']);
								if (!is_numeric($inArr['reimbursedFuel'])) {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!实报实销油费必须为整数或小数</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!实报实销油费为空</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['reimbursedFuel'] = 0;
						}

						//加班费
						if(!empty($val['overtimePay']) && trim($val['overtimePay']) != '') {
							$inArr['overtimePay'] = trim($val['overtimePay']);
							if (!is_numeric($inArr['overtimePay'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!加班费必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['overtimePay'] = 0;
						}

						//特殊油费
						if(!empty($val['specialGas']) && trim($val['specialGas']) != '') {
							$inArr['specialGas'] = trim($val['specialGas']);
							if (!is_numeric($inArr['specialGas'])) {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!特殊油费必须为整数或小数</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['specialGas'] = 0;
						}

						//短租车费
						if ($inArr['rentalPropertyCode'] == 'ZCXZ-02') {
							if(trim($val['shortRent']) != '') {
								$inArr['shortRent'] = trim($val['shortRent']);
								if (!is_numeric($inArr['shortRent'])) {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!短租车费必须为整数或小数</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!短租车费为空</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['shortRent'] = 0;
						}

						//按公里计价油费
						if ($inArr['rentalPropertyCode'] == 'ZCXZ-02' && $inArr['contractTypeCode'] == 'ZCHTLX-02') {
							if(trim($val['gasolineKMPrice']) != '') {
								$inArr['gasolineKMPrice'] = trim($val['gasolineKMPrice']);
								if (!is_numeric($inArr['gasolineKMPrice'])) {
									$tempArr['docCode'] = '第' . $actNum .'行数据';
									$tempArr['result'] = '<font color=red>导入失败!按公里计价油费必须为整数或小数</font>';
									array_push($resultArr ,$tempArr);
									continue;
								}
							} else {
								$tempArr['docCode'] = '第' . $actNum .'行数据';
								$tempArr['result'] = '<font color=red>导入失败!按公里计价油费为空</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$inArr['gasolineKMPrice'] = 0;
						}

						//行车事由
						if(!empty($val['drivingReason']) && trim($val['drivingReason']) != '') {
							$inArr['drivingReason'] = trim($val['drivingReason']);
						}

						//有效LOG时长
						if(!empty($val['effectLogTime']) && trim($val['effectLogTime']) != '') {
							$inArr['effectLogTime'] = trim($val['effectLogTime']);
						}

						//备注
						if(!empty($val['remark']) && trim($val['remark']) != '') {
							$inArr['remark'] = trim($val['remark']);
						}

						$inArr['state'] = 1;
						//获取归属公司名称
						$inArr['formBelong'] = $_SESSION['USER_COM'];
						$inArr['formBelongName'] = $_SESSION['USER_COM_NAME'];
						$inArr['businessBelong'] = $_SESSION['USER_COM'];
						$inArr['businessBelongName'] = $_SESSION['USER_COM_NAME'];

						$newId = parent::add_d($inArr ,true);
						if($newId) {
							$rs = $this->dealStatistics_d( $newId );
							if ($rs) {
								$tempArr['result'] = '导入成功';
							} else {
								$tempArr['result'] = '<font color=red>导入失败</font>';
							}
						} else {
							$tempArr['result'] = '<font color=red>导入失败</font>';
						}
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						array_push($resultArr ,$tempArr);
					}
				}
				return $resultArr;
			}
		}
	}

	/**
	 * 判断是否已经存在记录(能否添加)
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
      * 判断是否已经存在记录(能否添加)
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
             if($hasDuplit){// 同一项目下的同一车牌号不能存在同一天内的多条记录
                 $resultArr['error'] = 1;
                 $resultArr['msg'] = "提交的记录中存在重复的用车记录, 请检查后重试。";
             }else{
                 $resultArr['error'] = ($dispassRegister != "")? 1 : 0;
                 $resultArr['msg'] = ($dispassRegister != "")? "以下车牌: {$dispassRegister} 已存在用车记录, 请检查后重试。" : "";
             }
         }
         return $resultArr;
     }

	/**
	 * 处理导入的excel数据
	 */
	function dealExcelData_d($data) {
		$resultArr = array();//结果数组
		$tempArr = array();
		$datadictDao = new model_system_datadict_datadict();
		$esmDao = new model_engineering_project_esmproject(); //工程项目
		$provinceDao = new model_system_procity_province(); //城市
		$cityDao = new model_system_procity_city(); //省份
		$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp(); //车辆供应商
		$resultArr['result'] = true;
		foreach($data as $key => $val){
			$actNum = $key + 1;
			$inArr = array(); //新数据

			//项目编号
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
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!项目编号不存在</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!项目编号为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//省份
			if(!empty($val['province']) && trim($val['province']) != '') {
				$inArr['province'] = trim($val['province']);
				$provinceObj = $provinceDao->find(array('provinceName' => $inArr['province']));
				if ($provinceObj) {
					$inArr['provinceId'] = $provinceObj['id'];
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!省份不存在</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			}

			//城市
			if(!empty($val['city']) && trim($val['city']) != '') {
				$inArr['city'] = trim($val['city']);
				$cityObj = $cityDao->find(array('cityName' => $inArr['city']));
				if ($cityObj) {
					$inArr['cityId'] = $cityObj['id'];
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!城市不存在</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			}

			//用车日期
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
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!用车日期为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//供应商编号
			if(!empty($val['suppCode']) && trim($val['suppCode']) != '') {
				$inArr['suppCode'] = trim($val['suppCode']);
				$suppObj = $suppDao->find(array('suppCode'=>$inArr['suppCode']));


				if ($suppObj) {
//                    //判断车辆是否已登记 // 租车登记导入去掉供应商重复验证 (PMS2132 by haojin 2016-10-18)
//                    $registerRow=$this->find(array('carNum'=> trim($val['carNum']),'useCarDate'=>$inArr['useCarDate']));
//                    if(is_array($registerRow)&&count($registerRow)>0){
//                        $tempArr['docCode'] = '第' . $actNum .'行数据';
//                        $tempArr['result'] = '<font color=red>导入失败!该供应商'.$inArr['useCarDate'].'已登记</font>';
//                        $resultArr['result'] = false;
//                        array_push($resultArr ,$tempArr);
//                        continue;
//                    }
                    $inArr['suppId'] = $suppObj['id'];
					$inArr['suppName'] = $suppObj['suppName'];
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!供应商编号不存在</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!供应商编号为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//司机姓名
			if(!empty($val['driverName']) && trim($val['driverName']) != '') {
				$inArr['driverName'] = trim($val['driverName']);
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!司机姓名为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//租车性质
			if(!empty($val['rentalProperty']) && trim($val['rentalProperty']) != '') {
				$inArr['rentalProperty'] = trim($val['rentalProperty']);
				$rentalPropertyCode = $datadictDao->getCodeByName('WBZCXZ' ,$inArr['rentalProperty']);
				if ($rentalPropertyCode) {
					$inArr['rentalPropertyCode'] = $rentalPropertyCode;
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!租车性质不存在</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!租车性质为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//合同类型
			if(!empty($val['contractType']) && trim($val['contractType']) != '') {
				$inArr['contractType'] = trim($val['contractType']);
				$contractTypeCode = $datadictDao->getCodeByName('ZCHTLX' ,$inArr['contractType']);
				if ($contractTypeCode) {
					$inArr['contractTypeCode'] = $contractTypeCode;
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!合同类型不存在</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!合同类型为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//是否使用油卡支付
			if(!empty($val['isCardPay']) && trim($val['isCardPay']) != '') {
				$isCardPay = trim($val['isCardPay']);
				if ($isCardPay == '是，使用油卡支付') {
					$inArr['isCardPay'] = 1;
				} else {
					$inArr['isCardPay'] = 0;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!是否使用油卡支付为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//车牌
			if(!empty($val['carNum']) && trim($val['carNum']) != '') {
				$inArr['carNum'] = trim($val['carNum']);
				$rs = $this->isCanAdd_d($inArr);//判断该车辆是否已登记
				if (!$rs) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>项目上该车牌已存在用车日期为'.$inArr['useCarDate'].'的记录</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}else{
                    if(!empty($resultArr)&&count($resultArr)>1){
                        $breakFlag=false;
                        foreach($resultArr as $rKey=>$rVal){
                            if(isset($rVal['data'])){
                                if($rVal['data']['carNum']==$inArr['carNum']&&$rVal['data']['useCarDate']==$inArr['useCarDate']){
                                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                                    $tempArr['result'] = '<font color=red>导入文档中该车牌【'.$inArr['carNum'].'】已存在用车日期为'.$inArr['useCarDate'].'的记录</font>';
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
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!车牌为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

            // 是否为可导入数据
            $projectCode = $val['projectCode'];// 项目编号
            $suppCode = $val['suppCode'];// 供应商编码
            $carNum = trim($val['carNum']);// 车牌号码
            $useCarMonth = substr($inArr['useCarDate'],0,7);// 对应月份
            $chkResult = $this->chkRentCarRecord($projectCode,$suppCode,$carNum,$useCarMonth);
            if($chkResult && count($chkResult) > 0){
                $tempArr['docCode'] = '第' . $actNum .'行数据';
                $tempArr['result'] = '<font color=red>导入失败!此车牌号【'.$carNum.'】已生成了相应的租车登记汇总记录（审批状态为审批中或完成）, 请与项目经理沟通解决。</font>';
                $resultArr['result'] = false;
                array_push($resultArr ,$tempArr);
                continue;
            }

			//车型
			if(!empty($val['carModel']) && trim($val['carModel']) != '') {
				$inArr['carModel'] = trim($val['carModel']);
				$carModelCode = $datadictDao->getCodeByName('WBZCCX' ,$inArr['carModel']);
				if ($carModelCode) {
					$inArr['carModelCode'] = $carModelCode;
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!车型不存在</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!车型为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//开始里程
			if(trim($val['startMileage']) != '') {
				$inArr['startMileage'] = trim($val['startMileage']);
				if (!is_numeric($inArr['startMileage'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!开始里程必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!开始里程为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//结束里程
			if(trim($val['endMileage']) != '') {
				$inArr['endMileage'] = trim($val['endMileage']);
				if (!is_numeric($inArr['endMileage'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!结束里程必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}

				if ($inArr['endMileage'] >= $inArr['startMileage']) {
					$inArr['effectMileage'] = $inArr['endMileage'] - $inArr['startMileage'];
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!开始里程大于结束里程</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$tempArr['docCode'] = '第' . $actNum .'行数据';
				$tempArr['result'] = '<font color=red>导入失败!结束里程为空</font>';
				$resultArr['result'] = false;
				array_push($resultArr ,$tempArr);
				continue;
			}

			//餐饮费
			if(!empty($val['mealsCost']) && trim($val['mealsCost']) != '') {
				$inArr['mealsCost'] = trim($val['mealsCost']);
				if (!is_numeric($inArr['mealsCost'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!餐饮费必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['mealsCost'] = 0;
			}

			//住宿费
			if(!empty($val['accommodationCost']) && trim($val['accommodationCost']) != '') {
				$inArr['accommodationCost'] = trim($val['accommodationCost']);
				if (!is_numeric($inArr['accommodationCost'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!住宿费必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['accommodationCost'] = 0;
			}

			//停车费
			if(trim($val['parkingCost']) != '') {
				$inArr['parkingCost'] = trim($val['parkingCost']);
				if (!is_numeric($inArr['parkingCost'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!停车费必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['parkingCost'] = 0;
			}

            //路桥费
            if(trim($val['tollCost']) != '') {
                $inArr['tollCost'] = trim($val['tollCost']);
                if (!is_numeric($inArr['tollCost'])) {
                    $tempArr['docCode'] = '第' . $actNum .'行数据';
                    $tempArr['result'] = '<font color=red>导入失败!路桥费必须为整数或小数</font>';
                    $resultArr['result'] = false;
                    array_push($resultArr ,$tempArr);
                    continue;
                }
            } else {
                $inArr['tollCost'] = 0;
            }

			//油价
			if(!empty($val['gasolinePrice']) && trim($val['gasolinePrice']) != '') {
				$inArr['gasolinePrice'] = trim($val['gasolinePrice']);
				if (!is_numeric($inArr['gasolinePrice'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!油价必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['gasolinePrice'] = 0;
			}

			//实报实销油费
			if ($inArr['contractTypeCode'] == 'ZCHTLX-03') {
				if(trim($val['reimbursedFuel']) != '') {
					$inArr['reimbursedFuel'] = trim($val['reimbursedFuel']);
					if (!is_numeric($inArr['reimbursedFuel'])) {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!实报实销油费必须为整数或小数</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!实报实销油费为空</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['reimbursedFuel'] = 0;
			}

			//加班费
			if(!empty($val['overtimePay']) && trim($val['overtimePay']) != '') {
				$inArr['overtimePay'] = trim($val['overtimePay']);
				if (!is_numeric($inArr['overtimePay'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!加班费必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['overtimePay'] = 0;
			}

			//特殊油费
			if(!empty($val['specialGas']) && trim($val['specialGas']) != '') {
				$inArr['specialGas'] = trim($val['specialGas']);
				if (!is_numeric($inArr['specialGas'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!特殊油费必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['specialGas'] = 0;
			}


			//短租车费
			if ($inArr['rentalPropertyCode'] == 'ZCXZ-02') {
				if(trim($val['shortRent']) != '') {
					$inArr['shortRent'] = trim($val['shortRent']);
					if (!is_numeric($inArr['shortRent'])) {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!短租车费必须为整数或小数</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!短租车费为空</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['shortRent'] = 0;
			}

			//按公里计价油费
			if ($inArr['rentalPropertyCode'] == 'ZCXZ-02' && $inArr['contractTypeCode'] == 'ZCHTLX-02') {
				if(trim($val['gasolineKMPrice']) != '') {
					$inArr['gasolineKMPrice'] = trim($val['gasolineKMPrice']);
					if (!is_numeric($inArr['gasolineKMPrice'])) {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!按公里计价油费必须为整数或小数</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!按公里计价油费为空</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['gasolineKMPrice'] = 0;
			}

			//行车事由
			if(!empty($val['drivingReason']) && trim($val['drivingReason']) != '') {
				$inArr['drivingReason'] = trim($val['drivingReason']);
			} else {
				$inArr['drivingReason'] = '';
			}

			//有效LOG时长
			if(!empty($val['effectLogTime']) && trim($val['effectLogTime']) != '') {
				$inArr['effectLogTime'] = trim($val['effectLogTime']);
				if (!is_numeric($inArr['effectLogTime'])) {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!有效LOG时长必须为整数或小数</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			} else {
				$inArr['effectLogTime'] = 0;
			}

			//备注
			if(!empty($val['remark']) && trim($val['remark']) != '') {
				$inArr['remark'] = trim($val['remark']);
			} else {
				$inArr['remark'] = '';
			}

			if ($inArr['rentalPropertyCode'] != 'ZCXZ-02') { //非短租需核对相关信息
				$rentcarDao = new model_outsourcing_contract_rentcar();
				$rentcarObj = $rentcarDao->getByCarAndDate_d($inArr['carNum'] ,$inArr['useCarDate']);
				if ($rentcarObj) {
					// if ($rentcarObj['carModelCode'] != $inArr['carModelCode']) {
					//  $tempArr['docCode'] = '第' . $actNum .'行数据';
					//  $tempArr['result'] = '<font color=red>导入失败!登记车型跟合同车型不一致</font>';
					//  $resultArr['result'] = false;
					//  array_push($resultArr ,$tempArr);
					//  continue;
					// }
					if ($rentcarObj['signCompanyId'] != $inArr['suppId']) {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!登记供应商与租车合同信息不一致</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					}
					if ($rentcarObj['contractTypeCode'] != $inArr['contractTypeCode']) {
						$tempArr['docCode'] = '第' . $actNum .'行数据';
						$tempArr['result'] = '<font color=red>导入失败!登记合同类型与租车合同信息不一致</font>';
						$resultArr['result'] = false;
						array_push($resultArr ,$tempArr);
						continue;
					} else { //根据合同性质带出相应信息
						if ($rentcarObj['contractTypeCode'] == 'ZCHTLX-01') { //包油
							$inArr['gasolinePrice'] = $rentcarObj['oilPrice'];
						} else if ($rentcarObj['contractTypeCode'] == 'ZCHTLX-02') { //公里数计费
							$inArr['gasolineKMPrice'] = $rentcarObj['fuelCharge'];
						}
					}

					//处理合同附加费用
					if (!empty($val['fee']) && trim($val['fee']) != '') {
						$rentcarfeeDao = new model_outsourcing_contract_rentcarfee();
						$rentcarfeeObjs = $rentcarfeeDao->findAll(array('contractId' => $rentcarObj['contractId'] ,'isTemp' => 0));
						if (is_array($rentcarfeeObjs)) {
							$feeObj = explode('|' ,$val['fee']);
							if (is_array($feeObj)) {
								$i = 0; //初始化数组下标
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
							$tempArr['docCode'] = '第' . $actNum .'行数据';
							$tempArr['result'] = '<font color=red>导入失败!不存在的合同附加费</font>';
							$resultArr['result'] = false;
							array_push($resultArr ,$tempArr);
							continue;
						}
					}
				} else {
					$tempArr['docCode'] = '第' . $actNum .'行数据';
					$tempArr['result'] = '<font color=red>导入失败!车牌号'.$inArr['carNum'].'未签订合同或合同已过期</font>';
					$resultArr['result'] = false;
					array_push($resultArr ,$tempArr);
					continue;
				}
			}

			$tempArr['result'] = '可以导入';
			$tempArr['docCode'] = '第' . $actNum .'行数据';
			$tempArr['data'] = $inArr;
			array_push($resultArr ,$tempArr);
		}
		return $resultArr;
	}

	/**
	 * excel导入预览确定后添加
	 */
	function excelAdd_d($objs) {
		try {
			$this->start_d();

			if (is_array($objs)) {
				foreach ($objs as $key => $val) {
					$val['state'] = 1;
					//获取归属公司名称
					$val['formBelong'] = $_SESSION['USER_COM'];
					$val['formBelongName'] = $_SESSION['USER_COM_NAME'];
					$val['businessBelong'] = $_SESSION['USER_COM'];
					$val['businessBelongName'] = $_SESSION['USER_COM_NAME'];

					$id = parent::add_d($val ,true);

					if ($id) {
						//附加费用处理
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

						$this->dealStatistics_d( $id ); //统计表处理
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
                         //获取归属公司名称
                         $newData['formBelong'] = $_SESSION['USER_COM'];
                         $newData['formBelongName'] = $_SESSION['USER_COM_NAME'];
                         $newData['businessBelong'] = $_SESSION['USER_COM'];
                         $newData['businessBelongName'] = $_SESSION['USER_COM_NAME'];

                         //合同附加费用
                         $feeArr = isset($newData['fee'])? $newData['fee'] : array();

                         $id = parent::add_d($newData ,true);

                         if ($id) {
                             $insertNum += 1;
                             //附加费用处理
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
      * 计算当前的实时费用值
      * @param $dataArr
      * @param $mainObj
      * @return mixed
      */
    function countRealCostMoney($dataArr,$mainObj, $type = 'cz'){
        if($type == 'dz'){// 短租
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
                // 注意: 此处逻辑与 controller/outsourcing/vehicle/rentalcar.php文件中c_getCostTypeByRentalCarType函数内的是相似的,需要同时修改,或有时间的话再整合到一个函数里吧
//                $this->getParam ( array("allregisterId" => $allregisterId, "useCarDateLimit" => $useCarDate) );
//                $this->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
//                $rows = $this->listBySqlId ( 'select_Month' );
                $rows = $this->getStatisticsJsonData($allregisterId,$useCarDate);
                if($rows){
                    $catchPayTypeDetail = array();
                    foreach ($rows as $key => $row){
                        if ($row['rentalContractId'] > 0) {
                            //计算租车费和合同用车天数
                            $row['rentalCarCost'] = $this->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,false);
                            $row['contractUseDay'] = $this->getDaysOrFee_d($row['id'] ,$row['rentalContractId'] ,true);

                            //宏达租车
                            if ($row['rentalPropertyCode'] == 'ZCXZ-03') {
                                $row['rentalCarCost'] = $this->getHongdaFee_d($row['id'] ,$row['rentalContractId']);
                            }else{
                                $rows[$key]['rentalPropertyCode'] = $val['rentalPropertyCode'] = 'ZCXZ-01';// 含有关联合同号的,默认为长租
                                $rows[$key]['rentalProperty'] = $val['rentalProperty'] = '长租';
                            }
                        } else {
                            $rows[$key]['rentalCarCost'] = '';
                            $rows[$key]['contractUseDay'] = '';
                        }

                        if ($row['rentalPropertyCode'] == 'ZCXZ-02') { //短租的情况
                            $obj = $this->get_d( $row['id'] );
                            $row['rentalCarCost'] = $rows[$key]['shortRent']; //短租的直接显示短租车费的累加
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
        }else{// 长租
            foreach ($dataArr as $k => $v){
                $costMoney = 0;
                $includeFeeTypeCode = $v['includeFeeTypeCode'];
                $includeFeeTypeCodeArr = explode(",",$includeFeeTypeCode);
                $configuratorDao = new model_system_configurator_configurator();

                // 拼接当前的费用项明细
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
      * 查询同一个月内是否已存在满足相应条件的审核中或通过了的租车登记汇总
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
      * 根据租车汇总ID以及车牌号或许对应的用车天数
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
      * 根据相应信息获取长租汇总的租车费
      * @param $cId
      * @param $allregisterId
      * @param $carNum
      * @return bool|string
      */
    function getRentalCarCost($cId,$allregisterId,$carNum){
        // 查询关联的租车合同的基本信息
        $rentalContInfoSql = "select DATE_FORMAT(contractStartDate,'%Y%m%d') as startDate,DATE_FORMAT(contractEndDate,'%Y%m%d') as endDate,rentUnitPrice,rentUnitPriceCalWay from oa_contract_rentcar where id = '{$cId}'";
        $rentalContInfo = $this->_db->get_one($rentalContInfoSql);

        if($rentalContInfo){
            $contractCost = 0;// 合同期内的租车费
            $contractStartDate = isset($rentalContInfo['startDate'])? $rentalContInfo['startDate'] : 0;
            $contractEndDate = isset($rentalContInfo['endDate'])? $rentalContInfo['endDate'] : 0;
            if(isset($rentalContInfo['rentUnitPriceCalWay'])){
                switch($rentalContInfo['rentUnitPriceCalWay']){
                    case "byDay":// 按天算
                        // 查询合同期限内的租车登记信息
                        if($contractStartDate > 0 && $contractEndDate > 0){
                            $countDaysSql = "select count(id) as days from oa_outsourcing_register c where c.carNum = '{$carNum}' and c.allregisterId = '{$allregisterId}' and c.state = '1' AND (DATE_FORMAT(useCarDate,'%Y%m%d') BETWEEN {$contractStartDate} AND {$contractEndDate})";
                            $countUseCarDaysArr = $this->_db->get_one($countDaysSql);
                            if($countUseCarDaysArr){
                                $countUseCarDays = isset($countUseCarDaysArr['days'])? $countUseCarDaysArr['days'] : 0;
                                $contractCost = isset($rentalContInfo['rentUnitPrice'])? round(bcmul($rentalContInfo['rentUnitPrice'],$countUseCarDays,4),2) : 0;
                            }
                        }
                        break;
                    default:// 默认按月算
                        $contractCost = isset($rentalContInfo['rentUnitPrice'])? $rentalContInfo['rentUnitPrice'] : 0;
                        break;
                }
            }

            // 查询不在合同期限内的租车登记信息
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
      * 根据租车登记汇总以及车牌号获取总的租车费用
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
      * 获取车牌所属的合同期
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
        // 获取记录所属的月份
        $useCarMonth = $this->_db->get_one($useCarMonthSql);
        $projectId  = ($useCarMonth)? $useCarMonth['projectId'] : '';
        $useCarMonth = ($useCarMonth)? $useCarMonth['useCarMonth'] : '';

        // 获取相关的合同期间
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
                if(T.shortRentTip = '1','长租',T.rentalProperty) as rentalProperty,
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