<?php
/**
 * @author Michael
 * @Date 2014��2��10�� ����һ 18:43:01
 * @version 1.0
 * @description:�⳵�Ǽǻ��ܿ��Ʋ�
 */
class controller_outsourcing_vehicle_allregister extends controller_base_action {
    private $bindId = "";// �����ֲ��BindId
	function __construct() {
		$this->objName = "allregister";
		$this->objPath = "outsourcing_vehicle";
		parent::__construct ();
        $this->bindId = "507cdd55-bba5-4ffb-b589-7c5799b6f365";
	}

	/**
	 * ��ת���⳵�Ǽǻ����б�
	 */
	function c_page() {
		$this->assign("userId" ,$_SESSION['USER_ID']);
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view('list');
	}

	/**
	 * ��ת���⳵�Ǽǻ����б�(���ύ����)
	 */
	function c_toRecordPage() {
        $this->assign('projectId', isset($_GET['projectId'])?$_GET['projectId']:"");
		$this->view('listrecord');
	}

	/**
	 * ��ת���鿴�⳵�Ǽǻ����б�(������Ŀ)
	 */
	function c_toViewProjectPage() {
		$this->assign('projectId' ,$_GET['projectId']);
		$this->view('view-projectList');
	}

	/**
	 * ��ת���༭�⳵�Ǽǻ����б�(������Ŀ)
	 */
	function c_toEditProjectPage() {
		$this->assign('projectId' ,$_GET['projectId']);
		$this->view('edit-projectList');
	}

	/**
	 * ��ת���⳵�Ǽǻ����б�(������)
	 */
	function c_toServicePage() {
		$this->assign("userId" ,$_SESSION['USER_ID']);
		$this->view('listservice');
	}

	/**
	 * ��ת���ó���Ϣ�б�ÿ����Ŀÿ����ÿ������
	 */
	function c_toUseMessagePage() {
        $this->assign('projectId' ,$_GET['projectId']);
		$this->view('listMessage');
	}

	/**
	 * ��ȡ�ó���Ϣ����ת��Json
	 */
	function c_messageJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);

		// $service->asc = false;
		$service->sort = 'c.useCarDate';
		$service->groupBy = 'c.id,r.carNum';

		$rows = $service->page_d('select_message');

        foreach ($rows as $k => $v){
//            $dateLimit = substr($v['useCarDate'],0,7);
//            $cost = $service->getRentalCarCostVal($v['allRegisterId'],$dateLimit);
//            $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
            $registerDao = new model_outsourcing_vehicle_register();
            $rows[$k]['rentalCarCost'] = $registerDao->getRentalCarCostByRegisterId($v['allRegisterId'],$v['carNum']);
        }

//		if (is_array($rows)) {
//			foreach ($rows as $key => $val) {
//				$registerDao = new model_outsourcing_vehicle_register();
//				if ($val['rentalPropertyCode'] != 'ZCXZ-02') {
//					//�����ͬ�ó�����
//					$rows[$key]['contractUseDay'] = $registerDao->getDaysOrFee_d($val['registerId'] ,$val['rentalContractId']);
//				}
//			}
//		}
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * �������������ת��Json
	 */
	function c_serviceJson() {
		$service = $this->service;

		$service->getParam($_REQUEST);
		$rows = $service->page_d('select_service');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$arr = array ();
		$arr ['collection'] = $rows;
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת�������⳵�Ǽǻ���ҳ��
	 */
	function c_toAdd() {
		$this->view ('add');
	}

	/**
	 * ��ת���༭�⳵�Ǽǻ���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//����ǧ��λ��ʾ
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //��Ч���

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //ͣ����
        $this->assign("tollCost" ,number_format($obj['tollCost'] ,2)); //·�ŷ�

        $this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //������
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //ס�޷�

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //�Ӱ��
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //�����ͷ�

        // �����ֲ�����
        $otherDataDao = new model_common_otherdatas();
        $docUrl = $otherDataDao->getDocUrl($this->bindId);
        $this->assign('docUrl',$docUrl);

		$this->view ('edit' ,true);
	}

	/**
	 * ��д�༭
	 */
	function c_edit(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$obj = $_POST[$this->objName];
        if(!empty($obj['register']) && is_array($obj['register'])){
            foreach ($obj['register'] as $k => $v){
                unset($obj['register'][$k]['deductMoney']);
                unset($obj['register'][$k]['deductReason']);
            }
        }

        $useCarDate = isset($_POST['useCarDate'])? $_POST['useCarDate'] : '';
		$registerDao = new model_outsourcing_vehicle_register();
		$rs = $registerDao->addEstimate_d( $obj['register'] ); //������ۺͿۿ���Ϣ
		if($rs) {
			if($actType) {
				$tmp = 0; //�ӱ�������־
				foreach ($obj['register'] as $key => $val) {
					if ($val['rentalContractId'] > 0 || $val['rentalPropertyCode'] == 'ZCXZ-02') { //�к�ͬ���߶���
						$tmp++;
					}
				}
				if (count($obj['register']) == $tmp) { //�ж����еǼ��Ƿ��ж�Ӧ�ĺ�ͬ��Ϣ
					$result = $this->service->addContract_d( $obj , $useCarDate); //¼���ͬ��Ϣ
					if ($result) {
//						if ($this->service->checkBudgetById_d($obj['id'])) {
//							msg('���ó�����ĿԤ�㣡');
//						} else {
							$esmDao = new model_engineering_project_esmproject();
							$areaId = $esmDao->getRangeId_d($obj['projectId']);
							if($areaId > 0) {
								$billArea = $areaId;
							} else {
								$billArea = '';
							}
							succ_show('controller/outsourcing/vehicle/ewf_register.php?actTo=ewfSelect&billId='.$obj['id'].'&billArea='.$billArea);
//						}
					} else {
						msg( 'ϵͳ¼���ͬ��Ϣʧ�ܣ�' );
					}
				} else {
					msg( '���ֵǼ�û�й�����ͬ��' );
				}
			} else {
                // �����⳵���ܵ��⳵��
                $dateLimit = substr($useCarDate,0,7);
                $cost = $this->service->getRentalCarCostVal($obj['id'],$dateLimit);
                if($cost > 0){
                    $this->service->updateById(array("id" => $obj['id'],"rentalCarCost" => $cost));
                }
				msg( '����ɹ���' );
			}
		} else {
			msg( '����ʧ�ܣ�' );
		}
	}

	/**
	 * �ύ������鿴��ʾ�⳵�Ǽǻ���ҳ��
	 */
	function c_toAudit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);

        // ʵʱ�����⳵��
        $dateLimit = substr($obj['useCarDate'],0,7);
        $cost = $this->service->getRentalCarCostVal($obj['id'],$dateLimit);
        $obj['rentalCarCost'] = ($cost > 0)? $cost : $obj['rentalCarCost'];

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		//����ǧ��λ��ʾ
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //��Ч���
		$this->assign("rentalCarCost" ,number_format($obj['rentalCarCost'] ,2)); //�⳵��
		$this->assign("reimbursedFuel" ,number_format($obj['reimbursedFuel'] ,2)); //ʵ��ʵ���ͷ�
		$this->assign("gasolineKMCost" ,number_format($obj['gasolineKMCost'] ,2)); //������Ƽ��ͷ�

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //·��\ͣ����
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //������
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //ס�޷�

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //�Ӱ��
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //�����ͷ�

		$this->assign('hideBtn' ,$_GET['hideBtn'] ? 'hidden' : 'button');
		$this->view ( 'audit' );
	}

	/**
	 * ��ת���鿴�⳵�Ǽǻ���ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
        $id = $_GET ['id'];
        $relativeCarNum = "";
        if(isset($_GET ['type']) && $_GET ['type'] == 'viewByExpenseTmpId' && isset($_GET ['tmpId'])){
            $checkRelativeToCarRentalSql = "select * from oa_contract_rentcar_expensetmp where id = '{$_GET ['tmpId']}'";
            $carRentalRelativeTmpObj = $this->service->_db->get_one($checkRelativeToCarRentalSql);
            if($carRentalRelativeTmpObj){
                $id = $carRentalRelativeTmpObj['allregisterId'];
                $relativeCarNum = $carRentalRelativeTmpObj['carNumBase64'];// ���ﴫ����Ǽ��ܺ�ĳ��ƺ���
                $rentalProperty = $carRentalRelativeTmpObj['rentalProperty'];
            }
             // echo "<pre>";print_r($carRentalRelativeTmpObj);exit();
        }
        $this->assign("relativeCarNum",$relativeCarNum);
        $this->assign("rentalProperty",$rentalProperty);

		$obj = $this->service->get_d ( $id );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//����ǧ��λ��ʾ
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //��Ч���

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //·��\ͣ����
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //������
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //ס�޷�

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //�Ӱ��
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //�����ͷ�
		$this->view ( 'view' );
	}

	/**
	 * �⳵�Ǽ�����ͨ������
	 */
	function c_dealAfterAuditPass() {
	 	if (! empty ( $_GET ['spid'] )) {
	 		//�������ص�����
            $this->service->workflowCallBack($_GET['spid']);
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * ��ת��excel����ҳ��
	 */
	function c_toExcelOut() {
		$this->permCheck (); //��ȫУ��
		if ($_GET['userId']) {
			$this->assign('userId' ,$_GET['userId']);
		} else {
			$this->assign('userId' ,"");
		}
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
		if(!empty($formData['projectCode'])) //��Ŀ���
			$this->service->searchArr['projectCodeSea'] = $formData['projectCode'];

		if(!empty($formData['useCarDateSta'])) //�ó�ʱ����
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //�ó�ʱ����
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		if(!empty($formData['userId'])) //��½��
			$this->service->searchArr['projectManagerIdSea'] = $formData['userId'];

		$rows = $this->service->listBySqlId('select_excelOut');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

        foreach ($rows as $k => $v){
            $cost = $this->service->getRentalCarCostVal($v['id'],$v['substring(c.useCarDate , 1 ,7)']);
            $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
        }

		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '���-�⳵�Ǽǻ�����Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
	 }

	/**
	 * ��ת���ύ�ύ������excel����ҳ��
	*/
	function c_toExcelOutFinish() {
		$this->permCheck (); //��ȫУ��
		$this->view ( 'exceloutfinish' );
	}

	/**
	 * �����ύ����excel
	 */
	function c_excelOutFinish() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //��Ŀ����
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];
		if(!empty($formData['projectCode'])) //��Ŀ���
			$this->service->searchArr['projectCodeSea'] = $formData['projectCode'];

		if(!empty($formData['useCarDateSta'])) //�ó�ʱ����
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //�ó�ʱ����
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];

		$rows = $this->service->listBySqlId('select_excelOutFinish');
        if(!empty($rows)){
            foreach ($rows as $k => $v){
                $dateLimit = substr($v['useCarDate'],0,7);
                $cost = $this->service->getRentalCarCostVal($v['id'],$dateLimit);
                $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
                $rows[$k]['allCost'] = number_format(
                    $v['gasolineKMCost']
                    + $v['reimbursedFuel']
                    + $v['rentalCarCost']
                    + $v['parkingCost']
                    + $v['tollCost']
                    + $v['mealsCost']
                    + $v['accommodationCost']
                    + $v['overtimePay']
                    + $v['specialGas'] ,2
                );
            }
        }
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		for ($i = 0; $i < count($rows); $i++) {
			unset($rows[$i]['id']);
		}
		$colArr  = array();
		$modelName = '���-�⳵�Ǽǻ�����Ϣ';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr, $rows, $modelName);
	 }

	/**
	 * �ж��Ƿ�����ύ����(���ж�ʱ��)��¼���ͬ��Ϣ
	 */
	function c_isCanSubmit() {
		$id = $_POST['id'];
        $limitUseCarDate = isset($_POST['limitUseCarDate'])? $_POST['limitUseCarDate'] : '';
		$rs = $this->service->isCanSubmit_d( $id , $limitUseCarDate);
		if ($rs) {
//			if ($this->service->checkBudgetById_d($id)) { //�ж��Ƿ񳬳�Ԥ��
//				echo "budget";
//			} else {
                if($this->service->chkPayInfoForNofeeCont($id)){
                    echo 'hasNoDone';
                }else{
                    echo 'true';
                }
//			}
		} else {
			echo 'false';
		}
	}

	/**
	 * ��ת���鿴�ó���¼��ϸҳ��
	 */
	function c_toRecord() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		$this->assignFunc($obj);

		$registerDao = new model_outsourcing_vehicle_register();
		$registerObj = $registerDao->get_d( $_GET['registerId'] );
		$this->assign("carNum" ,$registerObj['carNum']); //����
		$this->assign("rentalProperty" ,$registerObj['rentalProperty']); //�⳵����
		$this->assign("driverName" ,$registerObj['driverName']); //˾������

		$vehicleDao = new model_outsourcing_outsourcessupp_vehicle();
		$vehicleObj = $vehicleDao->find(array("suppId"=>$registerObj['suppId'] ,"carNumber"=>$registerObj['carNum']));
		$this->assign("carModel" ,$vehicleObj['carModel']); //����
		$this->assign("phoneNum" ,$vehicleObj['phoneNum']); //��ϵ�绰

		$this->view ( 'record' );
	}

	/**
	 * ��ת���ó���Ϣ���ܵ���ҳ��
	 */
	function c_toExcelOutMessage() {
		$this->view ( 'excelOutMessage' );
	}

	/**
	 * �ó���Ϣ���ܵ���
	 */
	function c_excelOutMessage() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['projectName'])) //��Ŀ����
			$this->service->searchArr['projectNameSea'] = $formData['projectName'];
		if(!empty($formData['projectCode'])) //��Ŀ���
			$this->service->searchArr['projectCodeSea'] = $formData['projectCode'];

		if(!empty($formData['useCarDateSta'])) //�ó�ʱ����
			$this->service->searchArr['useCarDateSta'] = $formData['useCarDateSta'];
		if(!empty($formData['useCarDateEnd'])) //�ó�ʱ����
			$this->service->searchArr['useCarDateEnd'] = $formData['useCarDateEnd'];
		if(!empty($formData['suppName'])) //��Ӧ������
			$this->service->searchArr['suppNameSea'] = $formData['suppName'];

		if(!empty($formData['rentalContractCode'])) //�⳵��ͬ���
			$this->service->searchArr['rentalContractCodeSea'] = $formData['rentalContractCode'];
		if(!empty($formData['carNum'])) //���ƺ�
			$this->service->searchArr['carNumSea'] = $formData['carNum'];

		$this->service->searchArr['ExaStatusArr'] = "��������,���,���";
		$this->service->sort = 'c.useCarDate';
		$this->service->groupBy = 'c.id,r.carNum';
		$rows = $this->service->listBySqlId('select_message');

		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$data = array();
		$registerDao = new model_outsourcing_vehicle_register();
		foreach ($rows as $key => $val) {
			$data[$key]['useCarDate'] = substr($val['useCarDate'] , 0 ,-3);
			$data[$key]['projectName'] = $val['projectName'];
			$data[$key]['projectCode'] = $val['projectCode'];
			switch ($val['state']) {
				case '0' : $data[$key]['state'] = 'δ�ύ';break;
				case '1' : $data[$key]['state'] = 'δ����';break;
				case '2' : $data[$key]['state'] = '�������';break;
				case '3' : $data[$key]['state'] = '���';break;
				default : $data[$key]['state'] = '';
			}
			$data[$key]['projectType'] = $val['projectType'];
			$data[$key]['officeName'] = $val['officeName'];
			$data[$key]['province'] = $val['province'];
			$data[$key]['city'] = $val['city'];
			$data[$key]['suppName'] = $val['suppName'];
			$data[$key]['rentalContractCode'] = $val['rentalContractCode'];
			$data[$key]['carNum'] = $val['carNum'];
			if ($val['rentalPropertyCode'] != 'ZCXZ-02') { //��ͬ�ó�����
				$data[$key]['contractUseDay'] = $registerDao->getDaysOrFee_d($val['id'] ,$val['rentalContractId']);
			} else {
				$data[$key]['contractUseDay'] = $val['registerNum'];
			}

            $registerDao = new model_outsourcing_vehicle_register();
            $val['rentalCarCost'] = $registerDao->getRentalCarCostByRegisterId($val['allRegisterId'],$val['carNum']);

			$data[$key]['registerNum'] = $val['registerNum'];
			$data[$key]['startMileage'] = number_format($val['startMileage'] ,2);
			$data[$key]['endMileage'] = number_format($val['endMileage'] ,2);
			$data[$key]['effectMileage'] = number_format($val['effectMileage'] ,2);
			$data[$key]['gasolineKMPrice'] = number_format($val['gasolineKMPrice'] ,2);
			$data[$key]['gasolineKMCost'] = number_format($val['gasolineKMCost'] ,2);
			$data[$key]['reimbursedFuel'] = number_format($val['reimbursedFuel'] ,2);
			$data[$key]['rentalCarCost'] = number_format($val['rentalCarCost'] ,2);
			$data[$key]['parkingCost'] = number_format($val['parkingCost'] ,2);
            $data[$key]['tollCost'] = number_format($val['tollCost'] ,2);
			$data[$key]['mealsCost'] = number_format($val['mealsCost'] ,2);
			$data[$key]['accommodationCost'] = number_format($val['accommodationCost'] ,2);
			$data[$key]['overtimePay'] = number_format($val['overtimePay'] ,2);
			$data[$key]['specialGas'] = number_format($val['specialGas'] ,2);
			$data[$key]['allCost'] = number_format(
				$val['gasolineKMCost']
				+ $val['reimbursedFuel']
				+ $val['rentalCarCost']
				+ $val['parkingCost']
                + $val['tollCost']
				+ $val['mealsCost']
				+ $val['accommodationCost']
				+ $val['overtimePay']
				+ $val['specialGas'] ,2
			);
			$data[$key]['effectLogTime'] = $val['effectLogTime'];
			$data[$key]['estimate'] = $val['estimate'];
			$data[$key]['remark'] = $val['remark'];
		}
		$colArr  = array();
		$modelName = '���-�ó���Ϣ����';
		return model_outsourcing_outsourcessupp_importVehiclesuppUtil::exportExcelUtil($colArr ,$data ,$modelName);
	}

	/**
	 * ��ת�����ҳ��
	 */
	function c_toBack() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//����ǧ��λ��ʾ
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //��Ч���

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //·��\ͣ����
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //������
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //ס�޷�

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //�Ӱ��
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //�����
		$this->view ('back' ,true);
	}

	function msgRF2($title,$url){
        echo "<script>alert('" . $title . "');history.go(-1);</script>";
    }
	/**
	 * ���
	 */
	function c_back() {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];

        $expensetmpDao = new model_outsourcing_vehicle_rentalcar_expensetmp();
        $deductinfoDao = new model_outsourcing_vehicle_deductinfo();
        $errorsMsg = "";
        foreach ($obj['register'] as $key => $val) {
            if ($val['back'] == 1) {
                // ���ʱ�������Ӧ�Ŀۿ��¼
                $deductinfo = $deductinfoDao->find(" allregisterId = '{$obj['id']}' and carNum like '%{$val['carNum']}%' and FIND_IN_SET('{$val['id']}',registerIds) > 0");
                if($deductinfo){
                    $deductinfoDao->delete(array("id"=>$deductinfo['id']));
                }

                $expenseTmpdata = $expensetmpDao->findAll(" allregisterId = '{$obj['id']}' and FIND_IN_SET('{$val['carNum']}',carNumber) > 0 and FIND_IN_SET('{$val['id']}',registerIds) > 0");
                $ids = '';
                foreach ($expenseTmpdata as $k => $v){
                    $ids .= ($ids == '')? $v['id'] : ','.$v['id'];
                }
                if($ids != ''){
                    $deleteResult = $expensetmpDao->deleteRecordById($ids);
                    if($deleteResult['result'] == "fail"){
                        $errorsMsg = $deleteResult['msg'];
                        break;
                    }
                }
            }
        }

        if($errorsMsg == ""){
            $rs = $this->service->back_d( $obj );
            if($rs) {
                msg( '��سɹ���' );
            } else {
                msg( '���ʧ�ܣ�' );
            }
        }else{
            $this->msgRF2($errorsMsg,"index1.php?model=outsourcing_vehicle_allregister&action=toBack&id=".$obj['id']);
        }
	}

	/**
	 * ��ת�����븶��ҳ��
	 */
	function c_toPayment() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		$obj['useCarDate'] = substr($obj['useCarDate'] , 0 ,-3);
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		//����ǧ��λ��ʾ
		$this->assign("effectMileage" ,number_format($obj['effectMileage'] ,2)); //��Ч���
		$this->assign("rentalCarCost" ,number_format($obj['rentalCarCost'] ,2)); //�⳵��
		$this->assign("reimbursedFuel" ,number_format($obj['reimbursedFuel'] ,2)); //ʵ��ʵ���ͷ�
		$this->assign("gasolineKMCost" ,number_format($obj['gasolineKMCost'] ,2)); //������Ƽ��ͷ�

		$this->assign("parkingCost" ,number_format($obj['parkingCost'] ,2)); //·��\ͣ����
		$this->assign("mealsCost" ,number_format($obj['mealsCost'] ,2)); //������
		$this->assign("accommodationCost" ,number_format($obj['accommodationCost'] ,2)); //ס�޷�

		$this->assign("overtimePay" ,number_format($obj['overtimePay'] ,2)); //�Ӱ��
		$this->assign("specialGas" ,number_format($obj['specialGas'] ,2)); //�����ͷ�

		$this->view ('payment' ,true);
	}

    /**
     * ��ȡ��ҳ����ת��Json����д��
     */
    function c_pageJson() {
        $service = $this->service;

        $service->getParam ( $_REQUEST );
        //$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

        //$service->asc = false;
        $rows = $service->page_d ();
//        foreach ($rows as $k => $v){
//            $dateLimit = substr($v['useCarDate'],0,7);
//            $cost = $service->getRentalCarCostVal($v['id'],$dateLimit);
//            $rows[$k]['rentalCarCost'] = ($cost > 0)? $cost : $v['rentalCarCost'];
//        }

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
}
?>