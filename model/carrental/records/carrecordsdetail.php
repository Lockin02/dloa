<?php
/**
 * @author Show
 * @Date 2011年12月27日 星期二 19:08:21
 * @version 1.0
 * @description:用车明细(oa_carrental_records_detail) Model层
 */
class model_carrental_records_carrecordsdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_records_detail";
		$this->sql_map = "carrental/records/carrecordsdetailSql.php";
		parent::__construct ();
	}

	/********************** 数据设置 ******************/
    //数据字典字段处理
    public $datadictFieldArr = array(
    	'rentalType'
    );

    /*********************** 外部信息获取 *************************/
    /**
     * 获取日志信息
     */
    function getWorklog_d($worklogId){
        $worklogDao = new model_engineering_worklog_esmworklog();
        return $worklogDao->find(array('id' => $worklogId));
    }
    /***************** 增删改查 ***************************/


    //批量新增
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);

		//实例化车辆信息
		$carinfoDao = new model_carrental_carinfo_carinfo();

        try{
            $this->start_d();

            //数据字典处理
            foreach($object as $key => $val){
				$object[$key] = $this->processDatadict($val);
            }

            //新增方法
            $obj = $this->saveDelBatch($object);

            //本次录入金额
            $travelFee = $fuelFee = $roadFee = 0;
            //更新所有的累计金额
            foreach($object as $key => $val){

            	//更新车辆使用天数
            	$useDays = $this->getUseDays_d($val['carId']);

            	$carinfoDao->updateUseDays_d($val['carId'],$useDays);

            	if(isset($val['isDelTag'])){
					continue;
            	}
                //计算本次录入总金额
                $travelFee = bcadd($travelFee,$val['travelFee'],2);
                $fuelFee = bcadd($fuelFee,$val['fuelFee'],2);
                $roadFee = bcadd($roadFee,$val['roadFee'],2);
                $parkingFee = bcadd($parkingFee,$val['parkingFee'],2);
            }

            //引入配置信息
            include (WEB_TOR."model/common/commonConfig.php");
            //租车费对应的费用类型
            $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE['id'] : null;
            //油费对应的费用类型
            $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE['id'] : null;
            //路桥对应的费用类型
            $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE['id'] : null;
            //停车费的费用类型
            $CARPARKINGFEECOSTTYPE = isset($CARPARKINGFEECOSTTYPE) ? $CARPARKINGFEECOSTTYPE['id'] : null;

            $this->commit_d();
            return array(
                $CARTRAVELFEECOSTTYPE => $travelFee ,
                $CARFUELFEECOSTTYPE => $fuelFee,
                $CARROADFEECOSTTYPE => $roadFee,
                $CARPARKINGFEECOSTTYPE => $parkingFee
            );
        }catch(exception $e){
            $this->rollBack();
            return false;
        }
    }

	/**
	 *
	 * 新增用车记录
	 * @param  $carrentalArr
	 * @param  $newId
	 */
	function addCarRecords($carrentalArr, $newId) {
		//实例化用车记录model类
		$carrecordsDao = new model_carrental_records_carrecords ();
//		$itemsArr = $this->setItemMainId ( "worklogId", $newId, $carrentalArr );
		//		echo "<pre>";
		//		print_r($itemsArr);
		try{
			$this->start_d();

			foreach ( $carrentalArr as $key => $val ) {

				//拼装用车明细信息
				$objdetail ['useDate'] = $val ['useDate'];
				$objdetail ['beginNum'] = $val ['beginNum'];
				$objdetail ['endNum'] = $val ['endNum'];
				$objdetail ['mileage'] = $val ['mileage'];
				$objdetail ['useHours'] = $val ['useHours'];
				$objdetail ['useReson'] = $val ['useReson'];
				$objdetail ['travelFee'] = $val ['travelFee'];
				$objdetail ['fuelFee'] = $val ['fuelFee'];
				$objdetail ['roadFee'] = $val ['roadFee'];
				$objdetail ['effectiveLog'] = $val ['effectiveLog'];
				$objdetail ['worklogId'] = $newId;

				//拼装用车记录信息
				$obj ['projectId'] = $val ['projectId'];
				$obj ['projectName'] = $val ['projectName'];
				$obj ['projectCode'] = $val ['projectCode'];
				$obj ['carNo'] = $val ['carNo'];
				$obj ['carId'] = $val ['carId'];
				$obj ['driver'] = $val ['driver'];
				$obj ['linkPhone'] = $val ['linkPhone'];
				$obj ['carType'] = $val ['carType'];

				//对应项目及车牌号为空，数据作废不保存
				if ($obj ['projectCode'] == "" && $obj ['carNo'] == "") {
					continue;
				} else {
					$carrecordInfo = $carrecordsDao->find(array ("projectCode" => $obj ['projectCode'], "carNo" => $obj ['carNo'] ),null,'id');

					if (! is_array ( $carrecordInfo )) { //对应项目及车牌号还没有记录
						//设置用车记录的开始结束日期，然后添加用车主表
						$obj ['beginDate'] = $val ['useDate'];
						$obj ['end'] = $val ['useDate'];
						$carrecordsId = $carrecordsDao->addSelf_d ( $obj, true );
						//新增用车详细
						$objdetail ['recordsId'] = $carrecordsId;
						$carrecordsdetailId = $this->add_d ( $objdetail, true );
					} else {
						//直接新增用车项目
						$objdetail ['recordsId'] = $carrecordInfo ['id'];
						$carrecordsdetailId = $this->add_d ( $objdetail, true );
						//更新用车记录表的开始结束日期
						$maxDate = $this->get_table_fields($this->tbl_name," recordsId = ".$carrecordInfo['id'],'max(useDate)' );
						$minDate = $this->get_table_fields($this->tbl_name," recordsId = ".$carrecordInfo['id'],'min(useDate)' );

						$carrecordsDao->update(array('id' => $carrecordInfo ['id']),array('beginDate' =>$minDate ,'endDate' =>$maxDate ));
					}
				}
			}

			$this->commit_d();
		}catch(exception $e){
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 *
	 * 编辑用车记录
	 * @param  $carrentalArr
	 * @param  $id
	 */
	function editCarRecords($carrentalArr, $id) {
		try {
			$this->start_d ();
			//实例化用车记录model类
			$carrecordsDao = new model_carrental_records_carrecords ();
			$itemsArr = $this->setItemMainId ( "worklogId", $id, $carrentalArr );

			foreach ( $itemsArr as $key => $carrentalObj ) {
				$isDelTag = isset ( $carrentalObj ['isDelTag'] ) ? $carrentalObj ['isDelTag'] : NULL;
				if ($isDelTag) { //删除掉的
					$this->deleteByPk ( $carrentalObj ['id'] );
				} else {
					if (! empty ( $carrentalObj ['id'] )) { //带有id的进行更新
						$this->edit_d ( $carrentalObj, true );
					} else {
						$rarrecordObj = $carrecordsDao->find ( array ("projectId" => $carrentalObj ['projectId'], "carId" => $carrentalObj ['carId'] ) );
						$carrecordetail = array ("worklogId" => $carrentalObj ['worklogId'], "recordsId" => $rarrecordObj ['id'], "useDate" => $carrentalObj ['useDate'], "beginNum" => $carrentalObj ['beginNum'], "endNum" => $carrentalObj ['endNum'], "mileage" => $carrentalObj ['mileage'], "useHours" => $carrentalObj ['useHours'], "useReson" => $carrentalObj ['useReson'], "travelFee" => $carrentalObj ['travelFee'], "fuelFee" => $carrentalObj ['fuelFee'], "roadFee" => $carrentalObj ['roadFee'], "effectiveLog" => $carrentalObj ['effectiveLog'] );
						if (is_array ( $rarrecordObj )) { //存在用车基本信息,关联后新增.
							$this->add_d ( $carrecordetail );
						} else { //不存在用车基本信息,新增后关联后再新增.
							$carrecorBase = array ("projectId" => $carrentalObj ['projectId'], "projectName" => $carrentalObj ['projectName'], "projectCode" => $carrentalObj ['projectCode'], "carId" => $carrentalObj ['carId'], "carNo" => $carrentalObj ['carNo'], "carType" => $carrentalObj ['carType'], "driver" => $carrentalObj ['driver'], "linkPhone" => $carrentalObj ['linkPhone'] );

							$cardBaseId = $carrecordsDao->add_d ( $carrecorBase );
							$carrecordetail = array ("worklogId" => $carrentalObj ['worklogId'], "recordsId" => $cardBaseId, "useDate" => $carrentalObj ['useDate'], "beginNum" => $carrentalObj ['beginNum'], "endNum" => $carrentalObj ['endNum'], "mileage" => $carrentalObj ['mileage'], "useHours" => $carrentalObj ['useHours'], "useReson" => $carrentalObj ['useReson'], "travelFee" => $carrentalObj ['travelFee'], "fuelFee" => $carrentalObj ['fuelFee'], "roadFee" => $carrentalObj ['roadFee'], "effectiveLog" => $carrentalObj ['effectiveLog'] );
							$this->add_d ( $carrecordetail );
						}
					}
				}
			}

			$this->commit_d ();
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}


	/************************** 其他业务处理 ***********************/

	/**
	 * 设置关联从表的id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}

	/**
	 * 获取当前卡号累计的金额
	 */
	function getUseDays_d($carId){
		$this->searchArr = array('carId' => $carId);
		$rs = $this->list_d('select_count');
		if(is_array($rs)){
			return $rs[0]['useDays'];
		}else{
			return 0;
		}
	}
}
?>