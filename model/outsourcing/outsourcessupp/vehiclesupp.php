<?php
/**
 * @author Michael
 * @Date 2014��1��7�� ���ڶ� 10:22:36
 * @version 1.0
 * @description:������Ӧ��-������Ϣ Model��
 */
 class model_outsourcing_outsourcessupp_vehiclesupp  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcessupp_vehiclesupp";
		$this->sql_map = "outsourcing/outsourcessupp/vehiclesuppSql.php";
		parent::__construct ();
	}

	//��˾Ȩ�޴��� TODO
	// protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/*
	 * ��дadd
	 */
	function add_d($object) {
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$codeDao = new model_common_codeRule();
			$object['suppCode'] = $codeDao->outsourcSupplierCode($this->tbl_name ,'WBCL'); //��Ӧ�̱��
			$object['suppCategoryName'] = $datadictDao->getDataNameByCode($object['suppCategory']); //��Ӧ������
			$object['invoice'] = $datadictDao->getDataNameByCode($object['invoiceCode']); //��Ʊ����

			//��ȡ������˾����
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			$id = parent :: add_d($object, true);  //����������Ϣ

			$vehiclesuppequDao = new model_outsourcing_outsourcessupp_vehiclesuppequ();
			if(is_array($object['vehicle'])) {  //������Դ��Ϣ
				foreach($object['vehicle'] as $key => $val) {
					$val['parentId'] = $id;
					$vehiclesuppequDao->add_d($val);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/*
	 * ��дedit
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['suppCategoryName'] = $datadictDao->getDataNameByCode($object['suppCategory']); //��Ӧ������
			$object['invoice'] = $datadictDao->getDataNameByCode($object['invoiceCode']); //��Ʊ����

			//������־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$oldObj = $this->get_d ( $object ['id'] );
			if ($oldObj['isEquipDriver'] == '1') { //�ܷ��䱸˾��
				$oldObj['isEquipDriver'] = '��';
			} else {
				$oldObj['isEquipDriver'] = '����';
			}
			if ($oldObj['isDriveTest'] == '1') { //����·�⾭��
				$oldObj['isDriveTest'] = '��';
			} else {
				$oldObj['isDriveTest'] = 'û��';
			}
			$newObj = $object;
			if ($newObj['isEquipDriver'] == '1') { //�ܷ��䱸˾��
				$newObj['isEquipDriver'] = '��';
			} else {
				$newObj['isEquipDriver'] = '����';
			}
			if ($newObj['isDriveTest'] == '1') { //����·�⾭��
				$newObj['isDriveTest'] = '��';
			} else {
				$newObj['isDriveTest'] = 'û��';
			}
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $newObj );

			$id = parent :: edit_d($object, true); //����������Ϣ

			$vehiclesuppequDao = new model_outsourcing_outsourcessupp_vehiclesuppequ();
			$vehiclesuppequDao->delete(array ('parentId' =>$object['id']));
			if(is_array($object['vehicle'])) {  //������Դ��Ϣ
				foreach($object['vehicle'] as $key => $val) {
					if ($val['isDelTag'] != 1) {
						$val['parentId'] = $object['id'];
						$vehiclesuppequDao->add_d($val);
					}
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/*
	 * ���������
	 */
	function addBlacklist_d($object) {
		try {
			$this->start_d();

			//������־
			$logSettringDao = new model_syslog_setting_logsetting ();
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );

			$id = parent :: edit_d($object, true); //����������Ϣ

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * excel����
	 */
	function addExecelData_d() {
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
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {

				//������ѭ��
				foreach($excelData as $key => $val) {
					$actNum = $key;
					if( $key == 0 || empty($val[1])) {
						continue;
					} else {
						//��������
						$inArr = array();

						//��Ӧ������
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['suppCategoryName'] = trim($val[0]);
							$inArr['suppCategory'] = $datadictDao->getCodeByName('WBGYSLX' ,$inArr['suppCategoryName']);
							if (!$inArr['suppCategory']) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�����Ͳ�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ������Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��Ӧ������
						if(!empty($val[1]) && trim($val[1]) != '') {
							$inArr['suppName'] = trim($val[1]);
							$tmp = $this->findCount(array('suppName' => $inArr['suppName'])); //���ҹ�Ӧ�������Ƿ��Ѿ�����
							if ($tmp  > 0) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�������ѱ�ע��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							} else {
								$inArr['suppCode'] = $codeDao->outsourcSupplierCode($this->tbl_name ,'WBCL');
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ������Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//ʡ��
						if(!empty($val[2]) && trim($val[2]) != '') {
							$inArr['province'] = trim($val[2]);
							$provinceId = $this->get_table_fields('oa_system_province_info', "provinceName='".$inArr['province']."'", 'id');
							if($provinceId > 0) {
								$inArr['provinceId'] = $provinceId;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!ʡ�ݲ���ȷ</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!ʡ��Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//����
						if(!empty($val[3]) && trim($val[3]) != '') {
							$inArr['city'] = trim($val[3]);
							$cityId = $this->get_table_fields('oa_system_city_info', "cityName='".$inArr['city']."'", 'id');
							if($cityId > 0) {
								$inArr['cityId'] = $cityId;
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!���в���ȷ</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!����Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//����ʱ��
						if(!empty($val[4]) &&  $val[4] != '0000-00-00' && trim($val[4]) != '') {
							$val[4] = trim($val[4]);
							if(!is_numeric($val[4])) {
								$inArr['registeredDate'] = $val[4];
							} else {
								$recorderDate = date('Y-m-d' ,(mktime(0,0,0,1, $val[4] - 1 , 1900)));
								if($recorderDate=='1970-01-01') {
									$entryDate = date('Y-m-d',strtotime ($val[4]));
									$inArr['registeredDate'] = $entryDate;
								} else {
									$inArr['registeredDate'] = $recorderDate;
								}
							}
						}

						//ע���ʽ�
						if(!empty($val[5]) && trim($val[5]) != '') {
							$inArr['registeredFunds'] = trim($val[5]);
						}

						//���˴���
						if(!empty($val[6]) && trim($val[6]) != '') {
							$inArr['legalRepre'] = trim($val[6]);
						}

						//��������
						if(!empty($val[7]) && trim($val[7]) != '') {
							$inArr['carAmount'] = trim($val[7]);
						}

						//˾������
						if(!empty($val[8]) && trim($val[8]) != '') {
							$inArr['driverAmount'] = trim($val[8]);
						}

						//��Ʊ����
						if(!empty($val[9]) && trim($val[9]) != '') {
							$inArr['invoice'] = trim($val[9]);
							$inArr['invoiceCode'] = $datadictDao->getCodeByName('WBFPZL' ,$inArr['invoice']);
							if (!$inArr['invoiceCode']) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��Ʊ���Բ�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						}

						//��Ʊ˰��
						if(!empty($val[10]) && trim($val[10]) != '') {
							$inArr['taxPoint'] = trim($val[10]);
						}

						//�ܷ��䱸˾��
						if(!empty($val[11]) && trim($val[11]) != '') {
							$isEquipDriver = trim($val[11]);
							if ($isEquipDriver == '��') {
								$inArr['isEquipDriver'] = 1;
							}else {
								$inArr['isEquipDriver'] = 0;
							}
						}else {
							$inArr['isEquipDriver'] = 0;
						}

						//����·�⾭��
						if(!empty($val[12]) && trim($val[12]) != '') {
							$isDriveTest = trim($val[12]);
							if ($isDriveTest == '��') {
								$inArr['isDriveTest'] = 1;
							}else {
								$inArr['isDriveTest'] = 0;
							}
						}else {
							$inArr['isDriveTest'] = 0;
						}

						//ҵ��ֲ�
						if(!empty($val[13]) && trim($val[13]) != '') {
							$businessDistribute = str_replace('��' ,',' ,trim($val[13]));
							$businessDistributeList = explode(',' ,$businessDistribute);
							$tmp = '';
							foreach ($businessDistributeList as $k => $v) {
								$businessDistributeId = $this->get_table_fields('oa_system_province_info', "provinceName='".$v."'", 'id');
								if($businessDistributeId > 0) {
									continue;
								}
								$tmp = $tmp.$v.'��';
							}
							if ($tmp) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�����С�'.$tmp.'��������</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['businessDistribute'] = $businessDistribute;
						}

						//������̸����
						if(!empty($val[14]) && trim($val[14]) != '') {
							$inArr['tentativeTalk'] = trim($val[14]);
						}

						//��˾���
						if(!empty($val[15]) && trim($val[15]) != '') {
							$inArr['companyProfile'] = trim($val[15]);
						}

						//����
						if(!empty($val[16]) && trim($val[16]) != '') {
							$inArr['linkmanName'] = trim($val[16]);
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ϵ������Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//ְ��
						if(!empty($val[17]) && trim($val[17]) != '') {
							$inArr['linkmanJob'] = trim($val[17]);
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ϵ��ְ��Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//�绰
						if(!empty($val[18]) && trim($val[18]) != '') {
							$inArr['linkmanPhone'] = trim($val[18]);
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ϵ�˵绰Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//����
						if(!empty($val[19]) && trim($val[19]) != '') {
							$inArr['linkmanMail'] = trim($val[19]);
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ϵ������Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//�ʱ�
						if(!empty($val[20]) && trim($val[20]) != '') {
							$inArr['postcode'] = trim($val[20]);
						}

						//��ַ
						if(!empty($val[21]) && trim($val[21]) != '') {
							$inArr['address'] = trim($val[21]);
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��ϵ�˵�ַΪ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//������
						if(!empty($val[22]) && trim($val[22]) != '') {
							$inArr['bankName'] = trim($val[22]);
						}

						//�����˺�
						if(!empty($val[23]) && trim($val[23]) != '') {
							$inArr['bankAccount'] = trim($val[23]);
						}

						$newId = parent::add_d($inArr ,true);
						if($newId) {
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

	/**
	 * excel���������
	 */
	function blackExecelData_d() {
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
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)) {

				//������ѭ��
				foreach($excelData as $key => $val) {
					$actNum = $key + 1;
					if( empty($val[1]) ) {
						continue;
					} else {
						//��������
						$inArr = array();

						//��Ӧ�̱��
						if(!empty($val[0]) && trim($val[0]) != '') {
							$inArr['suppCode'] = trim($val[0]);
							$tmp = $this->find(array('suppCode' => $inArr['suppCode']));
							if (!$tmp) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱�Ų�����</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
							$inArr['id'] = $tmp['id'];
							$inArr['suppName'] = $tmp['suppName'];
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱��Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//��Ӧ������
						if(!empty($val[1]) && trim($val[1]) != '') {
							if ($inArr['suppName'] != trim($val[1])) {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ�̱�������Ʋ�ƥ��</font>';
								array_push($resultArr ,$tempArr);
								continue;
							}
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ������Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						//���������ԭ��
						if(!empty($val[2]) && trim($val[2]) != '') {
							$inArr['blackReason'] = trim($val[2]);
						} else {
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!���������ԭ��Ϊ��</font>';
							array_push($resultArr ,$tempArr);
							continue;
						}

						$inArr['suppLevel'] = 0;
						$newId = $this->addBlacklist_d($inArr);

						if($newId) {
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