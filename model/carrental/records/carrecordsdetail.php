<?php
/**
 * @author Show
 * @Date 2011��12��27�� ���ڶ� 19:08:21
 * @version 1.0
 * @description:�ó���ϸ(oa_carrental_records_detail) Model��
 */
class model_carrental_records_carrecordsdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_carrental_records_detail";
		$this->sql_map = "carrental/records/carrecordsdetailSql.php";
		parent::__construct ();
	}

	/********************** �������� ******************/
    //�����ֵ��ֶδ���
    public $datadictFieldArr = array(
    	'rentalType'
    );

    /*********************** �ⲿ��Ϣ��ȡ *************************/
    /**
     * ��ȡ��־��Ϣ
     */
    function getWorklog_d($worklogId){
        $worklogDao = new model_engineering_worklog_esmworklog();
        return $worklogDao->find(array('id' => $worklogId));
    }
    /***************** ��ɾ�Ĳ� ***************************/


    //��������
    function addBatch_d($object){
//        echo "<pre>";
//        print_r($object);

		//ʵ����������Ϣ
		$carinfoDao = new model_carrental_carinfo_carinfo();

        try{
            $this->start_d();

            //�����ֵ䴦��
            foreach($object as $key => $val){
				$object[$key] = $this->processDatadict($val);
            }

            //��������
            $obj = $this->saveDelBatch($object);

            //����¼����
            $travelFee = $fuelFee = $roadFee = 0;
            //�������е��ۼƽ��
            foreach($object as $key => $val){

            	//���³���ʹ������
            	$useDays = $this->getUseDays_d($val['carId']);

            	$carinfoDao->updateUseDays_d($val['carId'],$useDays);

            	if(isset($val['isDelTag'])){
					continue;
            	}
                //���㱾��¼���ܽ��
                $travelFee = bcadd($travelFee,$val['travelFee'],2);
                $fuelFee = bcadd($fuelFee,$val['fuelFee'],2);
                $roadFee = bcadd($roadFee,$val['roadFee'],2);
                $parkingFee = bcadd($parkingFee,$val['parkingFee'],2);
            }

            //����������Ϣ
            include (WEB_TOR."model/common/commonConfig.php");
            //�⳵�Ѷ�Ӧ�ķ�������
            $CARTRAVELFEECOSTTYPE = isset($CARTRAVELFEECOSTTYPE) ? $CARTRAVELFEECOSTTYPE['id'] : null;
            //�ͷѶ�Ӧ�ķ�������
            $CARFUELFEECOSTTYPE = isset($CARFUELFEECOSTTYPE) ? $CARFUELFEECOSTTYPE['id'] : null;
            //·�Ŷ�Ӧ�ķ�������
            $CARROADFEECOSTTYPE = isset($CARROADFEECOSTTYPE) ? $CARROADFEECOSTTYPE['id'] : null;
            //ͣ���ѵķ�������
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
	 * �����ó���¼
	 * @param  $carrentalArr
	 * @param  $newId
	 */
	function addCarRecords($carrentalArr, $newId) {
		//ʵ�����ó���¼model��
		$carrecordsDao = new model_carrental_records_carrecords ();
//		$itemsArr = $this->setItemMainId ( "worklogId", $newId, $carrentalArr );
		//		echo "<pre>";
		//		print_r($itemsArr);
		try{
			$this->start_d();

			foreach ( $carrentalArr as $key => $val ) {

				//ƴװ�ó���ϸ��Ϣ
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

				//ƴװ�ó���¼��Ϣ
				$obj ['projectId'] = $val ['projectId'];
				$obj ['projectName'] = $val ['projectName'];
				$obj ['projectCode'] = $val ['projectCode'];
				$obj ['carNo'] = $val ['carNo'];
				$obj ['carId'] = $val ['carId'];
				$obj ['driver'] = $val ['driver'];
				$obj ['linkPhone'] = $val ['linkPhone'];
				$obj ['carType'] = $val ['carType'];

				//��Ӧ��Ŀ�����ƺ�Ϊ�գ��������ϲ�����
				if ($obj ['projectCode'] == "" && $obj ['carNo'] == "") {
					continue;
				} else {
					$carrecordInfo = $carrecordsDao->find(array ("projectCode" => $obj ['projectCode'], "carNo" => $obj ['carNo'] ),null,'id');

					if (! is_array ( $carrecordInfo )) { //��Ӧ��Ŀ�����ƺŻ�û�м�¼
						//�����ó���¼�Ŀ�ʼ�������ڣ�Ȼ������ó�����
						$obj ['beginDate'] = $val ['useDate'];
						$obj ['end'] = $val ['useDate'];
						$carrecordsId = $carrecordsDao->addSelf_d ( $obj, true );
						//�����ó���ϸ
						$objdetail ['recordsId'] = $carrecordsId;
						$carrecordsdetailId = $this->add_d ( $objdetail, true );
					} else {
						//ֱ�������ó���Ŀ
						$objdetail ['recordsId'] = $carrecordInfo ['id'];
						$carrecordsdetailId = $this->add_d ( $objdetail, true );
						//�����ó���¼��Ŀ�ʼ��������
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
	 * �༭�ó���¼
	 * @param  $carrentalArr
	 * @param  $id
	 */
	function editCarRecords($carrentalArr, $id) {
		try {
			$this->start_d ();
			//ʵ�����ó���¼model��
			$carrecordsDao = new model_carrental_records_carrecords ();
			$itemsArr = $this->setItemMainId ( "worklogId", $id, $carrentalArr );

			foreach ( $itemsArr as $key => $carrentalObj ) {
				$isDelTag = isset ( $carrentalObj ['isDelTag'] ) ? $carrentalObj ['isDelTag'] : NULL;
				if ($isDelTag) { //ɾ������
					$this->deleteByPk ( $carrentalObj ['id'] );
				} else {
					if (! empty ( $carrentalObj ['id'] )) { //����id�Ľ��и���
						$this->edit_d ( $carrentalObj, true );
					} else {
						$rarrecordObj = $carrecordsDao->find ( array ("projectId" => $carrentalObj ['projectId'], "carId" => $carrentalObj ['carId'] ) );
						$carrecordetail = array ("worklogId" => $carrentalObj ['worklogId'], "recordsId" => $rarrecordObj ['id'], "useDate" => $carrentalObj ['useDate'], "beginNum" => $carrentalObj ['beginNum'], "endNum" => $carrentalObj ['endNum'], "mileage" => $carrentalObj ['mileage'], "useHours" => $carrentalObj ['useHours'], "useReson" => $carrentalObj ['useReson'], "travelFee" => $carrentalObj ['travelFee'], "fuelFee" => $carrentalObj ['fuelFee'], "roadFee" => $carrentalObj ['roadFee'], "effectiveLog" => $carrentalObj ['effectiveLog'] );
						if (is_array ( $rarrecordObj )) { //�����ó�������Ϣ,����������.
							$this->add_d ( $carrecordetail );
						} else { //�������ó�������Ϣ,�����������������.
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


	/************************** ����ҵ���� ***********************/

	/**
	 * ���ù����ӱ��id��Ϣ
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
	 * ��ȡ��ǰ�����ۼƵĽ��
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