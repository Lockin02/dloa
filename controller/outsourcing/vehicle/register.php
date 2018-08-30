<?php
/**
 * @author Michael
 * @Date 2014��2��10�� ����һ 18:40:56
 * @version 1.0
 * @description:�⳵�ǼǱ���Ʋ�
 */
class controller_outsourcing_vehicle_register extends controller_base_action {

	function __construct() {
		$this->objName = "register";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
	 }

	/**
	 * ��ת���⳵�Ǽ��б�
	 */
	function c_page() {
		$this->assign ('userId' ,$_SESSION['USER_ID']);
		$this->service->setCompany(0); # �����б�,����Ҫ���й�˾����
		$this->view('list');
	}

    /**
     * ��ȡ��ҳ����ת��Json ����д��
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        // ��Ҫ���ݺ�ͬ���޹�������
        if(isset($_REQUEST['needConDateFielt'])){
            $carNum = isset($_REQUEST['carNum'])? $_REQUEST['carNum'] : '';
            $carNum = util_jsonUtil::iconvUTF2GB($carNum);
            $allregisterId = isset($_REQUEST['allregisterId'])? $_REQUEST['allregisterId'] : '';

            $chkResult = $this->service->getCarsBelongContractDate($carNum,$allregisterId);
            $contractStartDate = $chkResult['contractStartDate'];
            $contractEndDate = $chkResult['contractEndDate'];

            if(!empty($contractStartDate) && !empty($contractEndDate)){
                switch ($_REQUEST['needConDateFielt']){
                    // ��ͬ���ڵĵǼ�
                    case '1':
                        $service->searchArr['useCarDateSta'] = $contractStartDate;
                        $service->searchArr['useCarDateEnd'] = $contractEndDate;
                        break;
                    // ��ͬ����ĵǼ�
                    default:
                        $service->searchArr['useCarDateOutRangeSql'] = "sql: and (c.useCarDate < '{$contractStartDate}' or c.useCarDate > '{$contractEndDate}') ";
                        break;
                }
            }
        }

        //$service->asc = false;
        $rows = $service->page_d ();
        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $rows );
        $arr = array ();
        $arr ['collection'] = $rows;
        //count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
        $arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
        $arr ['page'] = $service->page;
        $arr ['advSql'] = $service->advSql;
        $arr ['listSql'] = $service->listSql;
        echo util_jsonUtil::encode ( $arr );
    }

	/**
	 * ��ת���⳵�Ǽ���ϸ�б�ҳ��
	 */
	function c_toDetail() {
		$this->view('detail');
	}

	/**
	 * ����Ŀ����ʦ�鿴�⳵�Ǽ��б�
	 */
	function c_pageView() {
		$this->permCheck (); //��ȫУ��
        $this->assign('needConDateFielt' ,isset($_GET['needConDateFielt'])? $_GET['needConDateFielt'] : '');
		$this->assign('carNum' ,$_GET['carNum']);
		$this->assign('allregisterId' ,$_GET['allregisterId']);
		$this->view('listView');
	}

	/**
	 * ��ת�������⳵�ǼǱ�ҳ��
	 */
	function c_toAdd() {
		$this->assign('createId' ,$_SESSION ['USER_ID']);
		$this->assign('createName' ,$_SESSION ['USERNAME']);
		$this->assign('createTime' ,date ( "Y-m-d H:i:s" ));
		$this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ'));  //�⳵����
		$this->showDatadicts(array('contractTypeCode' => 'ZCHTLX')); //��ͬ����
		$this->showDatadicts(array('carModelCode' => 'WBZCCX')); //����
		$this->view ('add' ,true);
	}

	/**
	 * ��дadd
	 */
	function c_add(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$isSub = isset ($_GET['isSub']) ? $_GET['isSub'] : null;
		$obj = $_POST[$this->objName];
		if ($isSub) {
			$obj['state'] = 1;
		}
		$id = $this->service->add_d( $obj );
		if($id) {
			if ($isSub) {
				msg( '�ύ�ɹ���' );
			} else {
				msg( '����ɹ���' );
			}
		} else {
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * �Ҽ�ajax�ύ
	 */
	function c_ajaxSubmit() {
		$rs = $this->service->submit_d($_POST['id']);
		if($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ajax�����ύ
	 */
	function c_ajaxBatchSubmit() {
		$rs = $this->service->submit_d($_POST['ids'],"batch");
		if($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * ��ת���༭�⳵�ǼǱ�ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('rentalPropertyCode' => 'WBZCXZ') ,$obj['rentalPropertyCode']);  //�⳵����
		$this->showDatadicts(array('contractTypeCode' => 'ZCHTLX') ,$obj['contractTypeCode']);  //��ͬ����
		$this->showDatadicts(array('carModelCode' => 'WBZCCX') ,$obj['carModelCode']); //����
		$this->assign("file",$this->service->getFilesByObjId($_GET ['id'] ,true)); //��ʾ������Ϣ
		$this->view ('edit' ,true);
	}

	/**
	 * ��дedit
	 */
	function c_edit(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$isSub = isset ($_GET['isSub']) ? $_GET['isSub'] : null;
		$obj = $_POST[$this->objName];
		if ($isSub) {
			$obj['state'] = 1;
		}
		$id = $this->service->edit_d( $obj );
		if($id) {
			if ($isSub) {
				msg( '�ύ�ɹ���' );
			} else {
				msg( '����ɹ���' );
			}
		} else {
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���鿴�⳵�ǼǱ�ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign("isCardPay" ,$obj['isCardPay'] == 1 ? '�ǣ�ʹ���Ϳ�֧��' : '�񣬱���֧��');
		//����ǧ��λ��ʾ
		$this->assign("startMileage" ,number_format($obj['startMileage'] ,2)); //��ʼ���
		$this->assign("endMileage" ,number_format($obj['endMileage'] ,2)); //�������
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //��Ч���

		$this->assign("gasolinePrice" ,number_format($obj['gasolinePrice'] ,2)); //�ͼ�
		$this->assign("reimbursedFuel" ,number_format($obj['reimbursedFuel'] ,2)); //ʵ��ʵ���ͷ�
		$this->assign("gasolineKMPrice" ,number_format($obj['gasolineKMPrice'] ,2)); //������Ƽ��ͷѵ���
		$this->assign("shortRent" ,number_format($obj['shortRent'] ,2)); //���⳵��

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //ͣ����
        $this->assign("tollCost" ,number_format($obj['tollCost'] ,2)); //·�ŷ�
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //������
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //ס�޷�

		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //��ʾ������Ϣ
		$this->view ( 'view' );
	}

	/**
	 * ���»�ȡ�������ݷ���json
	 */
	function c_statisticsJson() {
		$service = $this->service;
        $enCodeLimitRelativeCarNum = isset($_REQUEST['limitRelativeCarNum'])? $_REQUEST['limitRelativeCarNum'] : "";
        $deCodeLimitRelativeCarNum = ($enCodeLimitRelativeCarNum != '')? base64_decode($enCodeLimitRelativeCarNum) : "";
        $limitRentalProperty = isset($_REQUEST['limitRentalProperty'])? $_REQUEST['limitRentalProperty'] : "";

//		$service->getParam ( $_REQUEST );
//		$service->groupBy = 'YEAR(c.useCarDate) ,MONTH(c.useCarDate) ,c.carNum';
//		$rows = $service->listBySqlId ( 'select_Month' );
        $rows = $service->getStatisticsJsonData();
        $rentCarPayInfoDao =  new model_outsourcing_contract_payInfo();
        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();

        $relativeCarsArrForCz = array();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				if ($val['rentalContractId'] > 0) {
					//�����⳵�Ѻͺ�ͬ�ó�����
					$rows[$key]['rentalCarCost'] = $service->getDaysOrFee_d($val['id'] ,$val['rentalContractId'] ,false);
					$rows[$key]['contractUseDay'] = $service->getDaysOrFee_d($val['id'] ,$val['rentalContractId'] ,true);

					//����⳵
					if ($val['rentalPropertyCode'] == 'ZCXZ-03') {
						$rows[$key]['rentalCarCost'] = $service->getHongdaFee_d($val['id'] ,$val['rentalContractId']);
					}else{
                        $rows[$key]['rentalPropertyCode'] = $val['rentalPropertyCode'] = 'ZCXZ-01';// ���й�����ͬ�ŵ�,Ĭ��Ϊ����
                        $rows[$key]['rentalProperty'] = $val['rentalProperty'] = '����';
                    }
				} else {
					$rows[$key]['rentalCarCost'] = '';
					$rows[$key]['contractUseDay'] = '';
				}

				if ($val['rentalPropertyCode'] == 'ZCXZ-02') { //��������
					$obj = $service->get_d( $val['id'] );
					$rows[$key]['rentalCarCost'] = $rows[$key]['shortRent']; //�����ֱ����ʾ���⳵�ѵ��ۼ�
					$rows[$key]['gasolineKMPrice'] = $obj['gasolineKMPrice'];
					$rows[$key]['gasolineKMCost'] = $obj['gasolineKMPrice'] * $rows[$key]['effectMileage'];
				}

				// ���Ʒѷ�ʽ������Ӧ���⳵����
				if(!empty($val['rentalContractId']) && $val['rentalContractId'] > 0){// ���й�����ͬ�ŵ�,����������ȡ��Ӧ���⳵��
                    $rentalConDao = new model_outsourcing_contract_rentcar();
                    $rentalContract = $rentalConDao->get_d($val['rentalContractId']);
                    if($rentalContract){
                        $rows[$key]['rentalContractStartDate'] = $rentalContract['contractStartDate'];
                        $rows[$key]['rentalContractEndDate'] = $rentalContract['contractEndDate'];
                    }
                    $rentalCarCost = $service->getRentalCarCost($val['rentalContractId'],$val['allregisterId'],$val['carNum']);
                    $rows[$key]['rentalCarCost'] = $rentalCarCost;
                }

				// ��ȡ�⳵��ͬ�����ĸ��ʽ
                $payInfos = $rentCarPayInfoDao->findAll(" mainId = '{$val['rentalContractId']}' and (isDel is null or isDel <> 1)");
                $payInfosArr = array(
                    "payInfoId1" => '',
                    "payInfoMoney1" => '',
                    "payInfoId2" => '',
                    "payInfoMoney2" => ''
                );

                $isFirstCar = 1;$isFirstCzCar = 0;
                if($payInfos && count($payInfos) > 0){
                    // ͳ�Ƶ�ǰ��ʵʱ�����ܶ�
                    $payInfos = $service->countRealCostMoney($payInfos,$rows[$key]);

                    // $carNum = base64_encode($val['carNum']);
                    $carNum = $val['carNum'];
                    // ������Ӧ�ĳ��Ƶ�֧��������Ϣ
                    $payInfosRecord = ($payInfos[0]['id'] != '')? $expensetmpDao->findExpenseTmpRecord("",$val['allregisterId'],$carNum,$payInfos[0]['id'],1) : array();
                    $payInfosArr['expenseTmpId1'] = '-';
                    $payInfosArr['payInfoId1'] = isset($payInfos[0])? $payInfos[0]['id'] : '';
                    if($payInfosRecord && isset($payInfosRecord['id'])){
                        $payInfosArr['expenseTmpId1'] = $payInfosRecord['id'];
                    }
                    $payInfosArr['pay1Cost'] = ($payInfosRecord && isset($payInfosRecord['payMoney']))? $payInfosRecord['payMoney'] : (($payInfosArr['payInfoId1'] != "")? 'δ����' : '-');
                    $payInfosArr['realNeedPayCost1'] = isset($payInfos[0]['realCostMoney'])? $payInfos[0]['realCostMoney'] : 0;
                    $payInfosArr['pay1Cost'] = ($payInfos[0]['payTypeCode'] == "HETFK")? $payInfosArr['pay1Cost'] : $payInfosArr['pay1Cost'];
                    $payInfosArr['pay1payTypeCode'] = $payInfos[0]['payTypeCode'];
                    // ��������ķ��ü�¼, ���ϲ��Ļ��ܼ�¼�鵽һ��,���ں���ͳ��ʵ��Ӧ����
                    if($payInfosRecord){
                        $payInfosRecordIncludeCars = explode(",",$payInfosRecord['carNumber']);
                        $isFirstCzCar = ($val['carNum'] == $payInfosRecordIncludeCars[0])? 1 : 0;
                        if(count($payInfosRecordIncludeCars) > 1){// ������������1�Ĳ����Ǻϲ����, ���ⵥ������, ֻ���֧�����1ȴ��֧�����2Ҳ��ʾ�����ˣ���Ϊ�ϲ����ͬʱ�֧�����1��2��,��������Ǻϲ���ļ�¼�Ļ�ϵͳ��Ĭ�ϰ�֧�����1��2����Ϊ�����
                            if(isset($relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                if(!in_array($key,$relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                    $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                                }
                            }else{
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']] = array();
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                            }
                        }
                    }

                    $payInfosRecord = ($payInfos[1]['id'] != '')? $expensetmpDao->findExpenseTmpRecord("",$val['allregisterId'],$carNum,$payInfos[1]['id'],1) : array();
                    $payInfosArr['expenseTmpId2'] = '-';
                    $payInfosArr['payInfoId2'] = isset($payInfos[1])? $payInfos[1]['id'] : '';
                    if($payInfosRecord && isset($payInfosRecord['id'])){
                        $payInfosArr['expenseTmpId2'] = $payInfosRecord['id'];
                    }
                    $payInfosArr['pay2Cost'] = ($payInfosRecord && isset($payInfosRecord['payMoney']))? $payInfosRecord['payMoney'] : (($payInfosArr['payInfoId2'] != '')? 'δ����' : '-');
                    $payInfosArr['realNeedPayCost2'] = isset($payInfos[1]['realCostMoney'])? $payInfos[1]['realCostMoney'] : 0;
                    $payInfosArr['pay2Cost'] = ($payInfos[1]['payTypeCode'] == "HETFK")? $payInfosArr['pay2Cost'] : $payInfosArr['pay2Cost'];
                    $payInfosArr['pay2payTypeCode'] = $payInfos[1]['payTypeCode'];
                    // ��������ķ��ü�¼, ���ϲ��Ļ��ܼ�¼�鵽һ��,���ں���ͳ��ʵ��Ӧ����
                    if($payInfosRecord){
                        $payInfosRecordIncludeCars = explode(",",$payInfosRecord['carNumber']);
                        $isFirstCzCar = ($val['carNum'] == $payInfosRecordIncludeCars[0])? 1 : 0;
                        if(count($payInfosRecordIncludeCars) > 1){
                            if(isset($relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                if(!in_array($key,$relativeCarsArrForCz[$payInfosRecord['carNumber']])){
                                    $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                                }
                            }else{
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']] = array();
                                $relativeCarsArrForCz[$payInfosRecord['carNumber']][] = $key;
                            }
                        }
                    }
                }else{// ����û�����֧����Ϣ�����
                    $carNum = $val['carNum'];
                    // ������Ӧ�ĳ��Ƶ�֧��������Ϣ
                    $payInfosRecord = $expensetmpDao->findExpenseTmpRecord("",$val['allregisterId'],$carNum,$payInfosArr['payInfoId1'],1,0,"����");
                    $payInfosRecordIncludeCars = explode(",",$payInfosRecord['carNumber']);
                    $isFirstCar = ($payInfosRecordIncludeCars[0] == $val['carNum'])? 1 : 0;
                    $payInfosArr['expenseTmpId1'] = ($payInfosRecord && isset($payInfosRecord['id']))? $payInfosRecord['id'] : '';
                    $payInfosArr['payInfoId1'] = '';
                    $payInfosArr['pay1Cost'] = ($payInfosRecord && isset($payInfosRecord['payMoney']))? $payInfosRecord['payMoney'] : 'δ����';
                    $isFirstCar = ($payInfosArr['pay1Cost'] == "δ����")? 1 : $isFirstCar;

                    // ͳ�Ƶ�ǰ��ʵʱ�����ܶ�
                    $payInfosArr = $service->countRealCostMoney($payInfosArr,$rows[$key],'dz');
                }

                $rows[$key]['isFirstCar'] = $isFirstCar;// �Ƿ�Ϊ��¼����ĵ�һ����¼���������Ͳ��õ��ֶΣ�
                $rows[$key]['isFirstCzCar'] = $isFirstCzCar;
                $rows[$key]['expenseTmpId1'] = $payInfosArr['expenseTmpId1'];
                $rows[$key]['payInfoId1'] = $payInfosArr['payInfoId1'];
                $rows[$key]['realNeedPayCost1'] = isset($payInfosArr['realNeedPayCost1'])? $payInfosArr['realNeedPayCost1'] : (($payInfosArr['pay1Cost'] == "δ����")? '' : 0);
                $rows[$key]['payInfoMoney1'] = ($rows[$key]['realNeedPayCost1'] > 0)? $payInfosArr['pay1Cost'] : (($payInfosArr['pay1Cost'] == "δ����")? 'δ����' : ($payInfosArr['pay1Cost']>0)? $payInfosArr['pay1Cost'] : 0);
                $rows[$key]['pay1payTypeCode'] = $payInfosArr['pay1payTypeCode'];
                $rows[$key]['expenseTmpId2'] = $payInfosArr['expenseTmpId2'];
                $rows[$key]['payInfoId2'] = $payInfosArr['payInfoId2'];
                $rows[$key]['pay2payTypeCode'] = $payInfosArr['pay2payTypeCode'];
                $rows[$key]['realNeedPayCost2'] = isset($payInfosArr['realNeedPayCost2'])? $payInfosArr['realNeedPayCost2'] : (($payInfosArr['pay2Cost'] == "δ����")? '' : 0);
                $rows[$key]['payInfoMoney2'] = ($rows[$key]['realNeedPayCost2'] > 0)? $payInfosArr['pay2Cost'] : (($payInfosArr['pay2Cost'] == "δ����")? 'δ����' : ($payInfosArr['pay2Cost']>0)? $payInfosArr['pay2Cost'] : 0);
                $rows[$key]['rentalContractNature'] = ($val['rentalPropertyCode'] == "ZCXZ-02")? "��" : $val['rentalContractNature'];// ����ĺ�ͬ������ʾ��

				//���¼����ܷ���
				$rows[$key]['allCost'] = (double)$rows[$key]['gasolineKMCost']
										+ (double)$rows[$key]['reimbursedFuel']
										+ (double)$rows[$key]['parkingCost']
                                        + (double)$rows[$key]['tollCost']
										+ (double)$rows[$key]['mealsCost']
										+ (double)$rows[$key]['accommodationCost']
										+ (double)$rows[$key]['overtimePay']
										+ (double)$rows[$key]['specialGas']
										+ $rows[$key]['rentalCarCost'];
			}

            if($deCodeLimitRelativeCarNum != ""){
                $backArr = array();
                $limitRelativeCarNum = explode(",",$deCodeLimitRelativeCarNum);
                foreach ($rows as $k => $v){
                    if(in_array($v['carNum'],$limitRelativeCarNum) && $v['rentalProperty'] == util_jsonUtil::iconvUTF2GB($limitRentalProperty)){
                        $backArr[] = $v;
                    }
                }
                $rows = $backArr;
            }
		}
		// ����ۿ���Ϣ
        $rows = $this->addDeductInfo($rows);

        // �����ϲ������ʵ��Ӧ����
        if(!empty($relativeCarsArrForCz)){
            $groupNum = 1;
            foreach ($relativeCarsArrForCz as $rowsKey){
                $realCostNeed1 = $realCostNeed2 = 0;
                // ͳ��
                foreach ($rowsKey as $k){
                    $realCostNeed1 = bcadd($realCostNeed1,$rows[$k]['realNeedPayCost1'],2);
                    $realCostNeed2 = bcadd($realCostNeed2,$rows[$k]['realNeedPayCost2'],2);
                }

                // ��ֵ
                foreach ($rowsKey as $k){
                    if(!empty($rows[$k])){
                        $rows[$k]['realNeedPayCost1'] = $realCostNeed1;
                        $rows[$k]['realNeedPayCost2'] = $realCostNeed2;
                        // ��Ϊ�ϲ����ͬʱ�֧�����1��2��,��������Ǻϲ���ļ�¼�Ļ�ϵͳ��Ĭ�ϰ�֧�����1��2����Ϊ���
                        // if($realCostNeed2 > 0 && $rows[$k]['payInfoMoney2'] == 0){
                           // $rows[$k]['payInfoMoney2'] = $realCostNeed2;
                        // }(���ֻ��зǺ�ƴ��¼���뵽��ѭ��,����֧����������ʾ,Ӱ���˺��ڵı���������,��ʱ���δ˶δ���)
                        $rows[$k]['belongGroup'] = $groupNum;
                    }
                }
                $groupNum += 1;
            }
        }

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

    /**
     * ����ۿ���Ϣ
     * @param array $rows
     * @return array
     */
	function addDeductInfo($rows = array()){
        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
	    if(!empty($rows) && is_array($rows)){
	        foreach ($rows as $key => $row){
	            // ÿ���⳵�Ǽǻ��������ÿ������ֻ����һ���ۿ���Ϣ
                $deductInfoArr = $deductinfoDao->find(array("allregisterId" => $row['allregisterId'],"carNum" => $row['carNum']));
                if($deductInfoArr){
                    // ������ڶ�Ӧ�Ŀۿ���Ϣ,��Ӧ֧����ʽ��ʵ��Ӧ����Ӧ�ü�ȥ�ÿۿ���
                    if($deductInfoArr['payinfoId'] == $row['payInfoId1'] || $deductInfoArr['expensetmpId'] == $row['expenseTmpId1']){
                        $rows[$key]['realNeedPayCost1'] = bcsub($row['realNeedPayCost1'],$deductInfoArr['deductMoney'],2);
                    }else if($deductInfoArr['payinfoId'] == $row['payInfoId2'] || $deductInfoArr['expensetmpId'] == $row['expenseTmpId2']){
                        $rows[$key]['realNeedPayCost2'] = bcsub($row['realNeedPayCost2'],$deductInfoArr['deductMoney'],2);
                    }
                    $rows[$key]['deductInfoId'] = $deductInfoArr['id'];
                    $rows[$key]['deductMoney'] = $deductInfoArr['deductMoney'];
                    $rows[$key]['deductReason'] = $deductInfoArr['deductReason'];
                }
            }
        }
	    return $rows;
    }

    /**
     * ��ѯ�⳵�ǼǼ�¼�Ƿ������ɹ����ı�������Ϣ��ʱ��¼
     *
     * @param string $carNum
     * @param string $allregisterId
     * @param string $retalcarContId
     * @return array
     */
	function c_chkExpenseTmp($carNum = '',$allregisterId = '',$retalcarContId = ''){
	    $chkFrom = isset($_REQUEST['chkType'])? $_REQUEST['chkType'] : '';
	    $carNum = isset($_REQUEST['carNum'])? $_REQUEST['carNum'] : $carNum;
        $allregisterId = isset($_REQUEST['allregisterId'])? $_REQUEST['allregisterId'] : $allregisterId;
        $retalcarContId = isset($_REQUEST['retalcarContId'])? $_REQUEST['retalcarContId'] : $retalcarContId;

        $rentalcarExpensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $data = $rentalcarExpensetmpDao->list_d();
        var_dump($data);
        $backData = array();

        if($chkFrom == 'json'){
            echo util_jsonUtil::encode($backData);
        }else{
            return $backData;
        }
    }

	/**
	 * ���ݵǼǻ��ܱ�ID�ж��Ƿ���Ա��
	 */
	function c_isChange() {
		$arr = $this->service->get_table_fields('oa_outsourcing_allregister' ,'id='.$_POST['allregisterId'] ,'state');
		if ($arr == '0') {
			echo 1;
		}
	}

	/**
	 * ��ת���⳵�ǼǱ��ҳ��
	 */
	function c_toChange() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->showDatadicts(array('carModelCode' => 'WBZCCX') ,$obj['carModelCode']); //����
		$this->assign("file" ,$this->service->getFilesByObjId($_GET ['id'] ,false)); //��ʾ������Ϣ
		$this->view ('change' ,true);
	}

	/**
	 * �����Ϣ
	 */
	function c_change(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		$id = $this->service->change_d( $obj );
		if($id) {
			msg( '����ɹ���' );
		} else {
			msg( '���ʧ�ܣ�' );
		}
	}

	/**
	 * ��ת���鿴�⳵�ǼǱ��ԭ��ҳ��
	 */
	function c_toChangeReason() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign("file",$this->service->getFilesByObjId($_GET ['id'] ,false)); //��ʾ������Ϣ
		$this->view ( 'changeReason' );
	}

	/**
	 * ��ת��excel����ҳ��
	 */
	function c_toExcelOut() {
		$this->permCheck (); //��ȫУ��
		//�жϴ������ĵ���
		$createId = isset ($_GET['createId']) ? $_GET['createId'] : null;
		$this->assign('createId' ,$createId); //�����б���

		$this->view ( 'excelout' );
	}

	/**
	 * ����excel
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //��Ŀ����
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];

		if(!empty($formData['useCarDateSta'])) //�ó�������
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //�ó�������
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		if(!empty($formData['provinceId'])) //ʡ��
			$this->service->searchArr['provinceId'] = $formData['provinceId'];
		if(!empty($formData['cityId'])) //����
			$this->service->searchArr['cityId'] = $formData['cityId'];

		if(!empty($formData['driverName'])) //˾������
			$this->service->searchArr['driverNameSea'] = $formData['driverName'];

		if(!empty($formData['carNum'])) //���ƺ�
			$this->service->searchArr['carNum'] = $formData['carNum'];

		if(!empty($formData['createDateSta'])) //¼��ʱ����
			$this->service->searchArr['createDateSta'] = $formData['createDateSta'];
		if(!empty($formData['createDateEnd'])) //¼��ʱ����
			$this->service->searchArr['createDateEnd'] = $formData['createDateEnd'];

		if(!empty($formData['createId'])) //¼����
			$this->service->searchArr['createId'] = $formData['createId'];

		$this->service->searchArr['state'] = 1;
		$rows = $this->service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['driverName'] = $v['driverName'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['createTime'] = $v['createTime'];
			$rowData[$k]['useCarDate'] = $v['useCarDate'];
			$rowData[$k]['projectName'] = $v['projectName'];
			$rowData[$k]['province'] = $v['province'];
			$rowData[$k]['city'] = $v['city'];
			$rowData[$k]['carNum'] = $v['carNum'];
			$rowData[$k]['carModel'] = $v['carModel'];
			$rowData[$k]['startMileage'] = $v['startMileage'];
			$rowData[$k]['endMileage'] = $v['endMileage'];
			$rowData[$k]['effectMileage'] = $v['effectMileage'];
			$rowData[$k]['gasolinePrice'] = $v['gasolinePrice'];
			$rowData[$k]['gasolineKMPrice'] = $v['gasolineKMPrice'];
			$rowData[$k]['reimbursedFuel'] = $v['reimbursedFuel'];
			$rowData[$k]['gasolineKMCost'] = $v['gasolineKMCost'];
			$rowData[$k]['parkingCost'] = $v['parkingCost'];
            $rowData[$k]['tollCost'] = $v['tollCost'];
			if ($v['rentalPropertyCode'] == 'ZCXZ-02') { //����
				$rowData[$k]['rentalCarCost'] = $v['shortRent'];
			} else {
				$rowData[$k]['rentalCarCost'] = '';
			}
			$rowData[$k]['mealsCost'] = $v['mealsCost'];
			$rowData[$k]['accommodationCost'] = $v['accommodationCost'];
			$rowData[$k]['overtimePay'] = $v['overtimePay'];
			$rowData[$k]['specialGas'] = $v['specialGas'];
			$rowData[$k]['effectLogTime'] = $v['effectLogTime'];
		}

		$colArr  = array();
		$modelName = '���-�⳵�Ǽ���Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * ������Ӧ�̣���ת����Ŀ��Ϣ
	 */
	function c_toProject(){
		$this->assign ( 'suppId' ,$_GET['suppId'] );
		$this->view ( 'projectView' );
	}

	/**
	 * ������Ӧ�̣���Ŀ��Ϣ�б�
	 */
	function c_projectList(){
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ('select_detail');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת����Ŀ����ҳ��
	 */
	function c_toExcelOutProject(){
		$suppName = '';
		if (isset ($_GET['suppId'])) {
			$suppName = $this->service->get_table_fields("oa_outsourcessupp_vehiclesupp" ,"id=".$_GET['suppId'] ,"suppName");
		}
		$this->assign ( 'suppName' ,$suppName);
		$this->view ( 'exceloutproject' );
	}

	/**
	 * ����excel
	 */
	function c_projectOut() {
		set_time_limit(0);
		$service = $this->service;
		$formData = $_POST[$this->objName];

		if(trim($formData['suppName'])) //������Ӧ��
			$service->searchArr['suppName'] = $formData['suppName'];

		if(trim($formData['projectCode'])) //��Ŀ���
			$service->searchArr['projectCodeE'] = $formData['projectCode'];

		if(trim($formData['projectName'])) //��Ŀ����
			$service->searchArr['projectNameSea'] = $formData['projectName'];

		if(trim($formData['carNum'])) //���ƺ�
			$service->searchArr['carNumber'] = $formData['carNum'];

		if(trim($formData['rentalContractCode'])) //�⳵��ͬ���
			$service->searchArr['rentalContractCode'] = $formData['rentalContractCode'];

		if(trim($formData['rentalCarCost'])) //�⳵����
			$service->searchArr['rentalCarCost'] = $formData['rentalCarCost'];

		if(trim($formData['useCarDateSta'])) //�⳵��ʼ��ѯʱ��
			$service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(trim($formData['useCarDateEnd'])) //�⳵������ѯʱ��
			$service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		$rows = $service->list_d('selcet_projectOut');
		if($rows){
			foreach($rows as $key=>$val){
				$rows[$key]['useCarDate'] =substr($val['useCarDate'] ,0 ,7);
			}
			for ($i = 0; $i < count($rows); $i++) {
				unset($rows[$i]['id']);
			}
			$colArr  = array();
			$modelName = '������Ӧ����Ŀ��Ϣ';
			return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
		}else {
			msg("�鲻�����ݣ�");
		}
	}

	/**
	 * �⳵�Ǽ��б���
	 */
	function c_pageViewOut(){
		set_time_limit(0);
		$service = $this->service;
		$service->searchArr['carNum'] = $_GET['carNum'];
		$service->searchArr['allregisterId'] = $_GET['allregisterId'];
		$service->searchArr['state'] = 1;
		$rows = $service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['driverName'] = $v['driverName'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['createTime'] = $v['createTime'];
			$rowData[$k]['useCarDate'] = $v['useCarDate'];
			$rowData[$k]['projectName'] = $v['projectName'];
			$rowData[$k]['province'] = $v['province'];
			$rowData[$k]['city'] = $v['city'];
			$rowData[$k]['carNum'] = $v['carNum'];
			$rowData[$k]['carModel'] = $v['carModel'];
			$rowData[$k]['startMileage'] = $v['startMileage'];
			$rowData[$k]['endMileage'] = $v['endMileage'];
			$rowData[$k]['effectMileage'] = $v['effectMileage'];
			$rowData[$k]['gasolinePrice'] = $v['gasolinePrice'];
			$rowData[$k]['gasolineKMPrice'] = $v['gasolineKMPrice'];
			$rowData[$k]['reimbursedFuel'] = $v['reimbursedFuel'];
			$rowData[$k]['gasolineKMCost'] = $v['gasolineKMCost'];
			$rowData[$k]['parkingCost'] = $v['parkingCost'];
            $rowData[$k]['tollCost'] = $v['tollCost'];
			if ($v['rentalPropertyCode'] == 'ZCXZ-02') { //����
				$rowData[$k]['rentalCarCost'] = $v['shortRent'];
			} else {
				$rowData[$k]['rentalCarCost'] = $v['rentalCarCost'];
			}
			$rowData[$k]['mealsCost'] = $v['mealsCost'];
			$rowData[$k]['accommodationCost'] = $v['accommodationCost'];
			$rowData[$k]['overtimePay'] = $v['overtimePay'];
			$rowData[$k]['specialGas'] = $v['specialGas'];
			$rowData[$k]['effectLogTime'] = $v['effectLogTime'];
		}

		$colArr  = array();
		$modelName = '���-�⳵�Ǽ���Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * ��Ŀ��Ϣ�б����
	 */
	function c_toAllProject(){
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view ( 'allproject' );
	}

	/**
	 * �⳵�Ǽ���ϸ�����б�
	 */
	function c_detailPage(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		//$service->asc = false;
		$rows = $service->page_d ('select_detail');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �⳵�Ǽ���ϸ���ܱ��ѯ����
	 */
	function c_toExcelDetail(){
		$this->view('excelDetail');
	}

	function c_excelDetail(){
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //��Ŀ����
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];

		if(!empty($formData['useCarDateSta'])) //�ó�������
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //�ó�������
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		if(!empty($formData['provinceId'])) //ʡ��
			$this->service->searchArr['provinceId'] = $formData['provinceId'];
		if(!empty($formData['cityId'])) //����
			$this->service->searchArr['cityId'] = $formData['cityId'];

		if(!empty($formData['driverName'])) //˾������
			$this->service->searchArr['driverNameSea'] = $formData['driverName'];

		if(!empty($formData['carNum'])) //���ƺ�
			$this->service->searchArr['carNum'] = $formData['carNum'];

		if(!empty($formData['createDateSta'])) //¼��ʱ����
			$this->service->searchArr['createDateSta'] = $formData['createDateSta'];
		if(!empty($formData['createDateEnd'])) //¼��ʱ����
			$this->service->searchArr['createDateEnd'] = $formData['createDateEnd'];

		$rows = $this->service->listBySqlId('select_detail');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $k => $v) {
			$rowData[$k]['driverName'] = $v['driverName'];
			$rowData[$k]['createName'] = $v['createName'];
			$rowData[$k]['createTime'] = $v['createTime'];
			$rowData[$k]['useCarDate'] = $v['useCarDate'];
			$rowData[$k]['projectName'] = $v['projectName'];
			$rowData[$k]['province'] = $v['province'];
			$rowData[$k]['city'] = $v['city'];
			$rowData[$k]['carNum'] = $v['carNum'];
			$rowData[$k]['carModel'] = $v['carModel'];
			$rowData[$k]['startMileage'] = $v['startMileage'];
			$rowData[$k]['endMileage'] = $v['endMileage'];
			$rowData[$k]['effectMileage'] = $v['effectMileage'];
			$rowData[$k]['gasolinePrice'] = $v['gasolinePrice'];
			$rowData[$k]['gasolineKMPrice'] = $v['gasolineKMPrice'];
			$rowData[$k]['reimbursedFuel'] = $v['reimbursedFuel'];
			$rowData[$k]['gasolineKMCost'] = $v['gasolineKMCost'];
			$rowData[$k]['parkingCost'] = $v['parkingCost'];
            $rowData[$k]['tollCost'] = $v['tollCost'];
			$rowData[$k]['rentalCarCost'] = $v['rentalCarCost'];
			$rowData[$k]['mealsCost'] = $v['mealsCost'];
			$rowData[$k]['accommodationCost'] = $v['accommodationCost'];
			$rowData[$k]['overtimePay'] = $v['overtimePay'];
			$rowData[$k]['specialGas'] = $v['specialGas'];
			$rowData[$k]['effectLogTime'] = $v['effectLogTime'];
		}

		$colArr  = array();
		$modelName = '���-�⳵�Ǽ���Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}

	/**
	 * ���ºͳ��ƺŻ�ȡ�������ݷ���json
	 */
	function c_recordJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->listBySqlId('select_default_forRecords');

		if(is_array($rows)){
			//���غϼ�
			$objArr = $service->listBySqlId('select_sum');
			if(is_array($objArr)){
				$rsArr = $objArr[0];
				$rsArr['useCarDate'] = '��ͳ��';
				$rsArr['id'] = 'noId';
			}

			// ���¼����⳵����
            $rentalContractCost = array(
                "shortRentCost" => 0,
                "byMonth" => 0,
                "byDay" => 0,
            );
			foreach ($rows as $row){
			    if(empty($row["rentUnitPriceCalWay"]) || $row["rentUnitPriceCalWay"] == 'null'){
                    $rentalContractCost["shortRentCost"] = round(bcadd($rentalContractCost["shortRentCost"],$row['shortRent'],3),2);
                }else if($row["rentUnitPriceCalWay"] == "byMonth"){
                    $rentalContractCost["byMonth"] = ($rentalContractCost["byMonth"] > 0)? $rentalContractCost["byMonth"] : $row['rentUnitPrice'];
                }else if($row["rentUnitPriceCalWay"] == "byDay"){
                    $rentalContractCost["byDay"] = round(bcadd($rentalContractCost["byDay"],$row['rentUnitPrice'],3),2);
                }
            }

            $rsArr['rentalCarCost'] = bcadd($rentalContractCost['shortRentCost'],bcadd($rentalContractCost['byMonth'],$rentalContractCost['byDay'],6),6);
			$rows[] = $rsArr;
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * ��ת���⳵�Ǽǵ���ҳ��
	 */
	function c_toExcelIn() {
		$this->view('excelIn');
	}

	/**
	 * ����
	 */
	function c_excelIn() {
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '�⳵�Ǽǵ������б�';
		$thead = array('������Ϣ' ,'������');
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * �ж��Ƿ��Ѿ����ڼ�¼(�ܷ����)
	 */
	function c_isCanAdd() {
		$obj = array('projectId' => $_POST['projectId']
				,'useCarDate' => util_jsonUtil::iconvUTF2GB($_POST['useCarDate'])
				,'carNum' => util_jsonUtil::iconvUTF2GB($_POST['carNum'])
			);
		$rs = $this->service->isCanAdd_d( $obj );
		if ($rs) {
			echo 1;
		} else {
			echo 0;
		}
	}

    /**
     * ��������ύ�ļ�¼�Ƿ������ύ��׼
     */
	function c_isCanBatchAdd(){
	    $ids = $_REQUEST['ids'];
        $rs = $this->service->isCanBatchAdd_d( $ids );
        echo util_jsonUtil::encode ( $rs );
    }

	/**
	 * �ж��Ƿ��Ѿ����ڼ�¼(�ܷ���)
	 */
	function c_isCanAddByChange() {
		$obj = array('projectId' => $_POST['projectId']
				,'useCarDate' => util_jsonUtil::iconvUTF2GB($_POST['useCarDate'])
				,'carNum' => util_jsonUtil::iconvUTF2GB($_POST['carNum'])
			);
		$oldObj = $this->service->get_d( $_POST['id'] );

		if ($obj['projectId'] == $oldObj['projectId']
				&& $obj['useCarDate'] == $oldObj['useCarDate']
				&& $obj['carNum'] == $oldObj['carNum']) {
			echo 1;
		} else {
			$rs = $this->service->isCanAdd_d( $obj );
			if ($rs) {
				echo 1;
			} else {
				echo 0;
			}
		}
	}

	/**
	 * �����⳵�Ǽǵ�ҳ��Ԥ��
	 */
	function c_importRegister() {
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES["inputExcel"]["name"];
		$temp_name = $_FILES["inputExcel"]["tmp_name"];
		$fileType = $_FILES["inputExcel"]["type"];

		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = model_outsourcing_outsourcessupp_importVehiclesuppUtil::readExcelData2($filename ,$temp_name,1);
            $excelHeaderArr = $excelData[0];
            unset($excelData[0]);

			if (!is_array($excelData)) {
				echo "<script>alert('�ϴ��ļ�û������,�������ϴ�!');history.go(-1);</script>";
				exit();
			}else if(!in_array("ͣ����",$excelHeaderArr) || !in_array("·�ŷ�",$excelHeaderArr)){// ���ݵ���ģ��ı����ж�ģ���Ƿ�Ϊ����
                echo "<script>alert('�ϴ��ļ�ģ������,���������µĵ���ģ�������!');history.go(-1);</script>";
                exit();
            }

			$registerData = array();
			foreach ($excelData as $key => $val) {
				$registerData[$key]['projectCode'] = $val[0];
				$registerData[$key]['province'] = $val[1];
				$registerData[$key]['city'] = $val[2];
				$registerData[$key]['useCarDate'] = $val[3];
				$registerData[$key]['suppCode'] = $val[4];
				$registerData[$key]['driverName'] = $val[5];
				$registerData[$key]['rentalProperty'] = $val[6];
				$registerData[$key]['contractType'] = $val[7];
				$registerData[$key]['isCardPay'] = $val[8];
				$registerData[$key]['carNum'] = $val[9];
				$registerData[$key]['carModel'] = $val[10];
				$registerData[$key]['startMileage'] = $val[11];
				$registerData[$key]['endMileage'] = $val[12];
				$registerData[$key]['mealsCost'] = $val[13];
				$registerData[$key]['accommodationCost'] = $val[14];
				$registerData[$key]['parkingCost'] = $val[15];
                $registerData[$key]['tollCost'] = $val[16];
				$registerData[$key]['gasolinePrice'] = $val[17];
				$registerData[$key]['reimbursedFuel'] = $val[18];
				$registerData[$key]['overtimePay'] = $val[19];
				$registerData[$key]['specialGas'] = $val[20];
				$registerData[$key]['shortRent'] = $val[21];
				$registerData[$key]['gasolineKMPrice'] = $val[22];
				$registerData[$key]['drivingReason'] = $val[23];
				$registerData[$key]['effectLogTime'] = $val[24];
				$registerData[$key]['remark'] = $val[25];
				$registerData[$key]['fee'] = $val[26];
			}
			spl_autoload_register ( '__autoload' );
			$resultArr = $service->dealExcelData_d($registerData);
			$rs = $resultArr['result'];
			unset($resultArr['result']);

			if (!$rs) {
				$title = '�⳵�Ǽǵ������б�';
				$thead = array('������Ϣ' ,'������');
				echo util_excelUtil::showResult($resultArr ,$title ,$thead);
			} else {

                ini_set("memory_limit", "1024M");
                $result = $this->service->excelAddNew_d($resultArr);
                $title = '�⳵�Ǽǵ������б�';
                $thead = array('������Ϣ' ,'������');
                if($result && $result > 0){
                    $dataNum = $result;
                    $tempArr = array(
                        array(
                            "result" => "����ɹ�!",
                            "docCode" => "����{$dataNum}������"
                        )
                    );
                }else{
                    $tempArr = array(
                        array(
                            "result" => "����ʧ��!",
                            "docCode" => "����0������"
                        )
                    );
                }
                echo util_excelUtil::showResult($tempArr ,$title ,$thead);
//				$this->view('excel-head' ,true);
//				foreach ($resultArr as $key => $val) {
//					$this->assignFunc($val['data']);
//					$this->assign("isCardPayVal" ,$val['data']['isCardPay'] == 1 ? '�ǣ�ʹ���Ϳ�֧��' : '�񣬱���֧��');
//					$this->assign('i' ,$key);
//					//��ͬ���ӷ���
//					$fee = '';
//					if (is_array($val['data']['fee'])) {
//						foreach ($val['data']['fee'] as $k => $v) {
//							$num = $k + 1;
//							$fee .= "$num ��$v[feeName]"
//								."<input type='hidden' name='register[$key][fee][$k][feeName]' value='$v[feeName]'/>"
//								."<input type='hidden' name='register[$key][fee][$k][contractId]' value='$v[contractId]'/>"
//								."&nbsp;&nbsp;&nbsp;&nbsp;";
//						}
//					}
//					$this->assign('fee' ,$fee);
//					$this->view('excel-body');
//				}
//				$this->view('excel-foot');
			}
		} else {
			echo "<script>alert('�ϴ��ļ������д�,�������ϴ�!');history.go(-1);</script>";
		}
	}

	/**
	 * excel����Ԥ����ȷ�����
	 */
	function c_excelAdd() {
        set_time_limit(0);
        ini_set("memory_limit", "1024M");
        // ini_set("display_errors",1);
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		if ($this->service->excelAdd_d($_POST[$this->objName])) {
			msg('��ӳɹ���');
		} else {
			msg('���ʧ�ܣ�');
		}
	}

    /**
     * ��ѯͬһ�������Ƿ��Ѵ���������Ӧ����������л�ͨ���˵��⳵�Ǽǻ���
     */
	function c_ajaxChkRentCarRecord(){
        $projectCode = isset($_POST['projectCode'])? util_jsonUtil::iconvUTF2GB($_POST['projectCode']) : '';// ��Ŀ���
        $suppCode = isset($_POST['suppCode'])? util_jsonUtil::iconvUTF2GB($_POST['suppCode']) : '';// ��Ӧ�̱���
        $carNum = isset($_POST['carNum'])? util_jsonUtil::iconvUTF2GB($_POST['carNum']) : '';// ���ƺ���
        $useCarMonth = isset($_POST['useCarMonth'])? $_POST['useCarMonth'] : '';// ��Ӧ�·�

        $service = $this->service;
        $chkResult = $service->chkRentCarRecord($projectCode,$suppCode,$carNum,$useCarMonth);

        //���ݼ��밲ȫ��
        $rows = $this->sconfig->md5Rows ( $chkResult );
        echo util_jsonUtil::encode ( $rows );
    }

}
?>