<?php
/**
 * @author Michael
 * @Date 2014��1��20�� ����һ 15:32:49
 * @version 1.0
 * @description:�⳵������������ Model��
 */
class model_outsourcing_vehicle_rentalcar  extends model_base {

    public $_rentCarFeeName = array();
	function __construct() {
		$this->tbl_name = "oa_outsourcing_rentalcar";
		$this->sql_map = "outsourcing/vehicle/rentalcarSql.php";
		parent::__construct ();

        // �⳵������Ŀ
        $this->_rentCarFeeName = array(
            "gasolinePrice" => "�ͷ�",
            "reimbursedFuel" => "ʵ��ʵ���ͷ�",
            "gasolineKMCost" => "������Ƽ��ͷ�",
            "parkingCost" => "ͣ����",
            "tollCost" => "·�ŷ�",
            "rentalCarCost" => "�⳵��",
            "mealsCost" => "������",
            "accommodationCost" => "ס�޷�",
            "overtimePay" => "�Ӱ��",
            "specialGas" => "�����ͷ�"
        );
	}

	//��˾Ȩ�޴��� TODO
	protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/**
	 * ��дadd
	 */
	function add_d($object){
		try {
			$this->start_d();
			$object['formCode'] = "ZCSQ".date("Ymd").time(); //�������뵥���
			$datadictDao = new model_system_datadict_datadict();
			$object['testTime'] = $datadictDao->getDataNameByCode($object['testTimeCode']); //����ʱ��
			$object['testPeriod'] = $datadictDao->getDataNameByCode($object['testPeriodCode']); //����ʱ���
			$object['expectUseDay'] = $datadictDao->getDataNameByCode($object['expectUseDayCode']); //Ԥ��ÿ���ó�����
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //�⳵����
			$object['payGasoline'] = $datadictDao->getDataNameByCode($object['payGasolineCode']); //�ͷ�
			$object['payParking'] = $datadictDao->getDataNameByCode($object['payParkingCode']); //·��ͣ����
			$object['isPayDriver'] = $datadictDao->getDataNameByCode($object['isPayDriverCode']); //�Ƿ�֧˾��ʳ��

			//��ȡ������˾����
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object, true);  //����������Ϣ

			$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
			if(is_array($object['supp'])){  //�Ƽ���Ӧ���б�
                $uploadFile = new model_file_uploadfile_management ();
				foreach($object['supp'] as $key => $val) {
					if ($val['isProvideInvoice'] == 0) { //���ѡ���ṩ��Ʊ����Ʊ�����˰������Ϊ��
						$val['invoiceCode'] = '';
						$val['taxPoint'] = '';
					}
					$val['vehicleModel'] = $datadictDao->getDataNameByCode($val['vehicleModelCode']); //�⳵����
					$val['paymentCycle'] = $datadictDao->getDataNameByCode($val['paymentCycleCode']); //��������
					$val['invoice'] = $datadictDao->getDataNameByCode($val['invoiceCode']); //��Ʊ����
					$val['taxationBears'] = $datadictDao->getDataNameByCode($val['taxationBearsCode']); //˰�ѳе���
					$val['parentId'] = $id;
					$equId=$rentalcarequDao->add_d($val, true);
                    if(!empty($val['file'])){
                        //������
                        $uploadFile->updateFileAndObj ( $val['file'], $equId ,'rentalcar_supp' );
                    }
				}
			}
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
			$object['testTime'] = $datadictDao->getDataNameByCode($object['testTimeCode']); //����ʱ��
			$object['testPeriod'] = $datadictDao->getDataNameByCode($object['testPeriodCode']); //����ʱ���
			$object['expectUseDay'] = $datadictDao->getDataNameByCode($object['expectUseDayCode']); //Ԥ��ÿ���ó�����
			$object['rentalProperty'] = $datadictDao->getDataNameByCode($object['rentalPropertyCode']); //�⳵����
			$object['payGasoline'] = $datadictDao->getDataNameByCode($object['payGasolineCode']); //�ͷ�
			$object['payParking'] = $datadictDao->getDataNameByCode($object['payParkingCode']); //·��ͣ����
			$object['isPayDriver'] = $datadictDao->getDataNameByCode($object['isPayDriverCode']); //�Ƿ�֧˾��ʳ��

			$id = parent::edit_d($object, true); //����������Ϣ

			if(is_array($object['supp'])) { //�Ƽ���Ӧ���б�
				$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
                $uploadFile = new model_file_uploadfile_management ();
				$rentalcarequDao->delete(array('parentId' => $object['id']));
				foreach($object['supp'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						if ($val['isProvideInvoice'] == 0) { //���ѡ���ṩ��Ʊ����Ʊ�����˰������Ϊ��
							$val['invoiceCode'] = '';
							$val['taxPoint'] = '';
						}
						$val['vehicleModel'] = $datadictDao->getDataNameByCode($val['vehicleModelCode']); //�⳵����
						$val['paymentCycle'] = $datadictDao->getDataNameByCode($val['paymentCycleCode']); //��������
						$val['invoice'] = $datadictDao->getDataNameByCode($val['invoiceCode']); //��Ʊ����
						$val['taxationBears'] = $datadictDao->getDataNameByCode($val['taxationBearsCode']); //˰�ѳе���
						$val['parentId'] = $object['id'];
                        unset($val['id'] );
                        $equId=$rentalcarequDao->add_d($val, true);
                        if(!empty($val['file'])){
                            //������
                            $uploadFile->updateFileAndObj ( $val['file'], $equId ,'rentalcar_supp' );
                        }
					}
				}
			}

			$this->commit_d();
			return $object['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �⳵�ӿ��˱༭�Ƽ���Ӧ���б�
	 */
	function dealEdit_d($id ,$obj) {
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
			$rentalcarequDao->delete(array ('parentId' =>$id ,'createId' =>$_SESSION['USER_ID']));
			if(is_array($obj)){  //�Ƽ���Ӧ���б�
				foreach($obj as $key => $val){
					if ($val['isDelTag'] != 1) {
						if ($val['isProvideInvoice'] == 0) { //���ѡ���ṩ��Ʊ����Ʊ�����˰������Ϊ��
							$val['invoiceCode'] = '';
							$val['taxPoint'] = '';
						}
						$val['paymentCycle'] = $datadictDao->getDataNameByCode($val['paymentCycleCode']); //��������
						$val['invoice'] = $datadictDao->getDataNameByCode($val['invoiceCode']); //��Ʊ����
						$val['taxationBears'] = $datadictDao->getDataNameByCode($val['taxationBearsCode']); //˰�ѳе���
						$val['parentId'] = $id;
						$rentalcarequDao->add_d($val, true);
					}
				}
			}
			//���¸���������ϵ
			$this->updateObjWithFile ( $id );

			//��������
			if (isset ( $_POST ['fileuploadIds'] ) && is_array ( $_POST ['fileuploadIds'] )) {
				$uploadFile = new model_file_uploadfile_management ();
				$uploadFile->updateFileAndObj ( $_POST ['fileuploadIds'], $id);
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �⳵������ȷ���Ƽ���Ӧ���б�
	 */
	function affirmEdit_d($id ,$obj) {
		try {
			$this->start_d();
			$this->update(array('id'=>$id) ,array('state'=>'6'));
			$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
			if(is_array($obj)){  //�Ƽ���Ӧ���б�
				foreach($obj as $key => $val){
					if (!$val['suppAffirm']) {
						$val['suppAffirm'] = 'no';
					}
					$rentalcarequDao->edit_d($val);
				}
			}
			$this->sendFinishMail_d( $id ); //�����ʼ�֪ͨ
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �⳵�����˴������
	 */
	function back_d($obj) {
		try {
			$this->start_d();
			$obj['state'] = '7';
			parent :: edit_d($obj);
			$this->sendBackMail_d( $obj['id'] ); //�����ʼ�֪ͨ
			$this->commit_d();
			return $obj['id'];
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ������ɷ����ʼ�֪ͨ�⳵�����������Ա(�����������ܼ�)
	 */
	 function sendRentalcarMail_d( $id ) {
	 	$obj = $this->get_d( $id );
	 	$receiverId = $this->getMailReceiver_d( $id );
	 	$emailDao = new model_common_mail();
	 	$mailContent = '���ã����ʼ�Ϊ�⳵��������ͨ��֪ͨ����ϸ��Ϣ���£�<br>'.
		'���ݱ�ţ�<span style="color:blue">'.$obj['formCode'].
		'</span><br>��Ŀ��ţ�<span style="color:blue">'.$obj['projectCode'].
		'</span><br>��Ŀ���ƣ�<span style="color:blue">'. $obj['projectName'].
		'</span><br>�⳵���ʣ�<span style="color:blue">'.$obj['rentalProperty'].
		'</span><br>�ó��ص㣺<span style="color:blue">'.$obj['province'].'-'.$obj['city'].
		'</span><br>�ó�������<span style="color:blue">'.$obj['useCarAmount'].
		'</span><br>�����ˣ�<span style="color:blue">'.$obj['createName'].
		'</span><br>�����˵绰��<span style="color:blue">'.$obj['applicantPhone'].
		'</span><br>����ʱ�䣺<span style="color:blue">'.$obj['createTime'].
		'</span><br>';

	 	$emailDao->mailGeneral("�⳵����������" ,$receiverId ,$mailContent);
	 }

	/**
	 * ������ɷ����ʼ�֪ͨ�⳵�����������Ա
	 */
	 function sendFinishMail_d( $id ) {
	 	$obj = $this->get_d( $id );
	 	$receiverId = $this->getMailReceiver2_d( $id );
	 	$emailDao = new model_common_mail();
	 	$mailContent = '���ã����ʼ�Ϊ�⳵��������ͨ��֪ͨ����ϸ��Ϣ���£�<br>'.
		'���ݱ�ţ�<span style="color:blue">'.$obj['formCode'].
		'</span><br>��Ŀ��ţ�<span style="color:blue">'.$obj['projectCode'].
		'</span><br>��Ŀ���ƣ�<span style="color:blue">'. $obj['projectName'].
		'</span><br>�⳵���ʣ�<span style="color:blue">'.$obj['rentalProperty'].
		'</span><br>�ó��ص㣺<span style="color:blue">'.$obj['province'].'-'.$obj['city'].
		'</span><br>�ó�������<span style="color:blue">'.$obj['useCarAmount'].
		'</span><br>�����ˣ�<span style="color:blue">'.$obj['createName'].
		'</span><br>�����˵绰��<span style="color:blue">'.$obj['applicantPhone'].
		'</span><br>����ʱ�䣺<span style="color:blue">'.$obj['createTime'].
		'</span><br>';

		$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
		$objEqu = $rentalcarequDao->findAll(array('parentId'=>$id));

		if (is_array($objEqu)) {
			$mailContent .= '�Ƽ���Ӧ����Ϣ:<br><table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td width="100px">��Ӧ�̱��</td><td width="100px">��Ӧ������</td><td width="60px">��ϵ������</td><td>��ϵ�˵绰</td><td width="60px">�⳵����</td><td width="110px">�����������</td><td width="90px">�ֳ���ͨ�۸�</td><td width="150px">�ֳ���ͨ�۸�˵��</td><td width="90px">��������</td><td width="60px">���������</td><td width="50px">�Ƿ��ṩ��Ʊ</td><td width="90px">��Ʊ����</td><td width="45px">��Ʊ˰��</td><td width="80px">˰�ѳе���</td><td width="300px">��ע</td></tr>';
			foreach($objEqu as $k => $v) {
				if ($v['powerSupply'] == '1') {
					$v['powerSupply'] = "������Ŀ����";
				}else{
					$v['powerSupply'] = "��������Ŀ����";
				}

				if ($v['isProvideInvoice'] == '1') {
				   $v['isProvideInvoice'] = "��";
				}else{
				   $v['isProvideInvoice'] = "��";
				}

				$mailContent .= '<tr><td>'.$v['suppCode'].'</td><td>'.$v['suppName'].'</td><td>'.$v['linkManName'].'</td><td>'.$v['linkManPhone'].'</td><td>'.$v['vehicleModel'].'</td><td>'.$v['powerSupply'].'</td><td>'.number_format($v['spotPrice'],2).'</td><td style="word-break: break-all;">'.$v['spotPriceExplain'].'</td><td>'.$v['paymentCycle'].'</td><td>'.number_format($v['vehicleMileage'],2).'</td><td>'.$v['isProvideInvoice'].'</td><td>'.$v['invoice'].'</td><td>'.($v['taxPoint']?$v['taxPoint'].'%':'').'</td><td>'.$v['taxationBears'].'</td><td style="text-align:left;word-break: break-all;">'.$v['remark'].'</td></tr>';
			}
			$mailContent .= '</table>';
		}
	 	$emailDao->mailGeneral("�⳵����������" ,$receiverId ,$mailContent);
	 }

	/**
	 * �⳵�����˴���������ʼ�֪ͨ�⳵�����������Ա
	 */
	 function sendBackMail_d( $id ) {
	 	$obj = $this->get_d( $id );
	 	$receiverId = $this->getMailReceiver2_d( $id );
	 	$emailDao = new model_common_mail();
	 	$mailContent = '���ã����ʼ�Ϊ�⳵����������֪ͨ����ϸ��Ϣ���£�<br>'.
		'���ݱ�ţ�<span style="color:blue">'.$obj['formCode'].
		'</span><br>��Ŀ��ţ�<span style="color:blue">'.$obj['projectCode'].
		'</span><br>��Ŀ���ƣ�<span style="color:blue">'. $obj['projectName'].
		'</span><br>�⳵���ʣ�<span style="color:blue">'.$obj['rentalProperty'].
		'</span><br>�ó��ص㣺<span style="color:blue">'.$obj['province'].'-'.$obj['city'].
		'</span><br>�ó�������<span style="color:blue">'.$obj['useCarAmount'].
		'</span><br>�����ˣ�<span style="color:blue">'.$obj['createName'].
		'</span><br>�����˵绰��<span style="color:blue">'.$obj['applicantPhone'].
		'</span><br>����ʱ�䣺<span style="color:blue">'.$obj['createTime'].
		'</span><br>';

		$rentalcarequDao = new model_outsourcing_vehicle_rentalcarequ();
		$objEqu = $rentalcarequDao->findAll(array('parentId'=>$id));

		if (is_array($objEqu)) {
			$mailContent .= '�Ƽ���Ӧ����Ϣ:<br><table border="1px" cellspacing="0px" style="text-align:center"><tr style="background-color:#fff999"><td width="60px">��Ӧ��ȷ��</td><td width="80px">����</td><td width="100px">��Ӧ������</td><td width="60px">��ϵ������</td><td>��ϵ�˵绰</td><td width="60px">�ó�����</td><td width="100px">֤�����</td><td width="110px">�����������</td><td width="90px">��������</td><td width="90px">��Ʊ����</td><td width="45px">��Ʊ˰��</td><td width="200px">�⳵�ѣ�����˾�����ʣ�</td><td>�ͷ�</td><td>������</td><td>ס�޷�</td><td width="300px">��ע</td></tr>';
			foreach($objEqu as $k => $v) {
				if ($v['suppAffirm'] == 'on') {
				   $v['suppAffirm'] = "<span style='color:blue'>��</span>";
				}else{
				   $v['suppAffirm'] = "<span style='color:red'>-</span>";
				}

				if ($v['powerSupply'] == '1') {
					$v['powerSupply'] = "������Ŀ����";
				}else{
					$v['powerSupply'] = "��������Ŀ����";
				}

				$mailContent .= '<tr><td>'.$v['suppAffirm'].'</td><td>'.$v['deptName'].'</td><td>'.$v['suppName'].'</td><td>'.$v['linkManName'].'</td><td>'.$v['linkManPhone'].'</td><td>'.$v['useCarAmount'].'</td><td>'.$v['certificate'].'</td><td>'.$v['powerSupply'].'</td><td>'.$v['paymentCycle'].'</td><td>'.$v['invoice'].'</td><td>'.$v['taxPoint'].'%</td><td>'.$v['rentalFee'].'</td><td>'.$v['gasolineFee'].'</td><td>'.$v['catering'].'</td><td>'.$v['accommodationFee'].'</td><td style="text-align:left">'.$v['remark'].'</td></tr>';
			}
		}

		$mailContent .= '</table><br>���ԭ��<span style="color:blue">'.$obj['backReason'].'</span>';
	 	$emailDao->mailGeneral("�⳵����������" ,$receiverId ,$mailContent);
	 }

	/**
	 * �����⳵����id ��ȡ�⳵�����������Ա(�����������ܼ�)
	 */
	function getMailReceiver_d( $id ) {
		$obj = $this->get_d( $id );
		$receiverId = '';
		$receiverId.=$obj['createId'];//������
		include (WEB_TOR."model/common/mailConfig.php");
		$mailNotify = $mailUser['oa_outsourcing_rentalcar'];
		$receiverId.= ','.$mailNotify['TO_ID']; //�⳵����-����ͨ��֪ͨ��

		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
		$receiverId.=','.$esmprojectObj['areaManagerId'];//������
		return $receiverId;
	}

	/**
	 * �����⳵����id ��ȡ�⳵�����������Ա
	 */
	function getMailReceiver2_d( $id ) {
		$obj = $this->get_d( $id );
		$receiverId = '';
		$receiverId.=$obj['createId'];//������
		include (WEB_TOR."model/common/mailConfig.php");
		$mailNotify = $mailUser['oa_outsourcing_rentalcar'];
		$receiverId.= ','.$mailNotify['TO_ID']; //�⳵����-����ͨ��֪ͨ��

		$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($obj['projectId']);
		$receiverId.=','.$esmprojectObj['areaManagerId'];//������
		$receiverId.=','.$this->get_table_fields('oa_esm_office_baseinfo', "id=".$esmprojectObj['officeId'], 'mainManagerId');//�����ܼ�
		return $receiverId;
	}

	/**
	 * ����ͨ����Ĵ���
	 */
	function dealAfterAuditPass_d( $id ) {
		$this->sendFinishMail_d( $id ); //���ʼ�֪ͨ������

		//�ж��Ƽ���Ӧ���Ƿ���ڹ�Ӧ�̿⣬���û�������
		$equDao = new model_outsourcing_vehicle_rentalcarequ();
		$suppDao = new model_outsourcing_outsourcessupp_vehiclesupp();
		$equObj = $equDao->findAll(array('parentId' => $id));
		if (is_array($equObj)) {
			foreach ($equObj as $v) {
				$suppObj = $suppDao->find(array('suppName' => $v['suppName']));
				if ($suppObj) {
					$equDao->edit_d(array('id' => $v['id'] ,'suppCode' => $suppObj['suppCode'] ,'suppId' => $suppObj['id']));
				} else {
					$newSupp = array(
						'suppName' => $v['suppName']
						,'linkmanName' => $v['linkManName']
						,'linkmanPhone' => $v['linkManPhone']
						,'suppCategory' => 'GYSLX-02' //�ֶ����Ĭ��Ϊ������
					);
					$suppId = $suppDao->add_d($newSupp);
					$newSuppObj = $suppDao->get_d($suppId);
					$equDao->edit_d(array('id' => $v['id'] ,'suppCode' => $newSuppObj['suppCode'] ,'suppId' => $suppId));
				}
			}
		}
	}

	/**
	 * ������Ŀid��ȡ�⳵Ԥ��
	 */
	function getBudgetByProId_d($projectId) {
		$esmbudgetDao = new model_engineering_budget_esmbudget();
		$budgetArr = $esmbudgetDao->getBudgetForCar_d($projectId);
		$budget = 0;
		if (!empty($budgetArr)) {
			foreach ($budget as $key => $val) {
				$budget += $val;
			}
		}
		return $budget;
	}
	/**
	 * �������ص�����
	 */
	function workflowCallBack($spid){
        $otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $spid );
		if($folowInfo['examines'] == "ok") {  //����ͨ��
			$this->dealAfterAuditPass_d( $folowInfo['objId'] );
		}
	}

    /**
     * ͨ��ID��ȡ֧����Ϣ
     *
     * @param $id
     * @return bool|mixed
     */
	function getPayInfoById($id,$idType = ''){
        $rentCarPayInfoDao =  new model_outsourcing_contract_payInfo();
        if($idType == "mainId"){
            $payInfo = $rentCarPayInfoDao->find(array("mainId" => $id));
        }else{
            $payInfo = $rentCarPayInfoDao->get_d($id);
        }

        if(isset($payInfo['payTypeCode']) && $payInfo['payTypeCode'] == 'BXFQR'){// ������������
            $bankInfoSql = "select staffName as realName,oftenBank,oftenCardNum,oftenAccount from oa_hr_personnel where userAccount = '{$_SESSION['USER_ID']}';";
            $bankInfo = $this->_db->getArray($bankInfoSql);

            $payInfo['bankName'] = ($bankInfo && isset($bankInfo[0]['oftenBank']))? $bankInfo[0]['oftenBank'] : '';
            $payInfo['bankAccount'] = ($bankInfo && isset($bankInfo[0]['oftenAccount']))? $bankInfo[0]['oftenAccount'] : '';
            $payInfo['bankReceiver'] = ($bankInfo && isset($bankInfo[0]['realName']))? $bankInfo[0]['realName'] : '';
        }

        return $payInfo;
    }

    /**
     * ���ݳ��ƺ��Լ��⳵�Ǽǻ���ID��ȡ��ص�����
     * @param $allregisterId
     * @param $carNum
     * @param $rentcarContractId
     * @return array
     */
    function getRentalCarBaseInfo($allregisterId,$carNum,$rentcarContractId){
        $backData = array();
        $carNumStr = "'".str_replace(",","','",rtrim($carNum,","))."'";
        $registerBaseInfoSql = "
        SELECT (TO_DAYS(t.endDate) - TO_DAYS(t.startDate) + 1) AS useCarDays,t.* FROM (
            SELECT min(useCarDate) AS startDate,max(useCarDate) AS endDate,driverName,rentalProperty,rentalPropertyCode,carNum FROM oa_outsourcing_register WHERE allregisterId = {$allregisterId} AND carNum in ({$carNumStr}) GROUP BY
	        YEAR (useCarDate),MONTH (useCarDate)
        ) t";
        $registerBaseInfo = $this->_db->getArray($registerBaseInfoSql);
        if($registerBaseInfo){$registerBaseInfo = $registerBaseInfo[0];}
        $backData['startDate'] = isset($registerBaseInfo['startDate'])? $registerBaseInfo['startDate'] : '';
        $backData['endDate'] = isset($registerBaseInfo['endDate'])? $registerBaseInfo['endDate'] : '';
        $backData['useCarDays'] = isset($registerBaseInfo['useCarDays'])? $registerBaseInfo['useCarDays'] : '';
        $backData['carNum'] = isset($registerBaseInfo['carNum'])? $registerBaseInfo['carNum'] : '';
        $backData['driverName'] = isset($registerBaseInfo['driverName'])? $registerBaseInfo['driverName'] : '';
        $backData['rentalProperty'] = isset($registerBaseInfo['rentalProperty'])? $registerBaseInfo['rentalProperty'] : '';
        $backData['rentalPropertyCode'] = isset($registerBaseInfo['rentalPropertyCode'])? $registerBaseInfo['rentalPropertyCode'] : '';

        $rentalContract = array();
        if($rentcarContractId != ''){
            $rentalContractSql = "select c.contractNature,c.contractNatureCode,c.contractType,c.contractTypeCode,c.contractStartDate,c.contractEndDate from oa_contract_rentcar c where c.id = {$rentcarContractId};";
            $rentalContractData = $this->_db->getArray($rentalContractSql);
            if($rentalContractData){$rentalContract = $rentalContractData[0];}
        }

        $backData['contractNature'] = isset($rentalContract['contractNature'])? $rentalContract['contractNature'] : '';
        $backData['contractNatureCode'] = isset($rentalContract['contractNatureCode'])? $rentalContract['contractNatureCode'] : '';
        $backData['contractType'] = isset($rentalContract['contractType'])? $rentalContract['contractType'] : '';
        $backData['contractTypeCode'] = isset($rentalContract['contractTypeCode'])? $rentalContract['contractTypeCode'] : '';
        $backData['contractStartDate'] = isset($rentalContract['contractStartDate'])? $rentalContract['contractStartDate'] : '';
        $backData['contractEndDate'] = isset($rentalContract['contractEndDate'])? $rentalContract['contractEndDate'] : '';

        return $backData;
    }

    /**
     * ͨ����ĿID��ѯ�������⳵�ܷ���
     *
     * @param $projectId
     * @param string $state // ��Ҫ���˵��⳵�Ǽǵ�״̬,Ĭ��Ϊ��δ������,�����ύ��������,��������Ŀ�⳵�Ǽ��б�����״̬Ϊδ������
     * @return array
     */
    function getProjectAuditingCarFee($projectId,$state = "WSP"){
        $backArr = array();
        $chkSql = "";
        switch ($state){
            case 'WSP': // δ����
                $chkSql = <<<EOT
            SELECT
                c.id,c.projectId,c.projectCode,(if(c.rentalCarCost is null,0,c.rentalCarCost)+c.reimbursedFuel+if(c.gasolineKMCost is null,0,c.gasolineKMCost)+c.parkingCost+c.tollCost+c.mealsCost+c.accommodationCost+c.overtimePay+c.specialGas) as allCost
            FROM
                oa_outsourcing_allregister c 
            WHERE ((c.ExaStatus IN ( '��������', '���', '���' ))) AND ( ( c.projectId = '$projectId' ) ) AND c.state = 1 ORDER BY id DESC
EOT;
                break;
        }

        $totalCost = 0;
        $result = empty($chkSql)? array() : $this->_db->getArray($chkSql);
        foreach ($result as $row){
            $totalCost += isset($row['allCost'])? $row['allCost'] : 0;
        }

        $backArr['searchSql'] = $chkSql;
        $backArr['resultRow'] = $result;
        $backArr['totalCost'] = $totalCost;
        return $backArr;
    }

    /**
     * ������Ŀ�ܳɱ�֮���ټ�����Ӧ�������е��⳵�Ǽ�Ԥ����
     * @param null $projectIds
     */
    function updateProjectFieldFeeWithCarFee($projectIds = null){
        $projectIdsArr = explode(",",$projectIds);
        if(!empty($projectIdsArr)){
            foreach ($projectIdsArr as $id){
                $rentalcarCostArr = $this->getProjectAuditingCarFee($id);
                $auditingCarFee = ($rentalcarCostArr)? $rentalcarCostArr['totalCost'] : 0;
                if($auditingCarFee > 0){
                    $updateSql = "update oa_esm_project set feeAll = feeAll + {$auditingCarFee} where id = '{$id}';";
                    $this->_db->query($updateSql);
                }
            }
        }
    }
}