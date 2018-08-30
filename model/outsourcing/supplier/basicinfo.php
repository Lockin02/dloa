<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 16:32:24
 * @version 1.0
 * @description:�����Ӧ�̿� Model��
 */
 class model_outsourcing_supplier_basicinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_supplib";
		$this->sql_map = "outsourcing/supplier/basicinfoSql.php";
		parent::__construct ();
	}

	/*****************************************************��ʾ�ָ���**********************************************/

	/*
	 * ͨ��value����״̬
	 */
	function stateToVal($stateVal) {
		$returnVal = false;
		try {
			foreach ( $this->state as $key => $val ) {
				if ($val ['stateVal'] == $stateVal) {
					$returnVal = $val ['stateCName'];
				}
			}
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
		return $returnVal;
	}

	/*
	 * ͨ��״̬����value
	 */
	function stateToSta($stateSta) {
		$returnVal = false;
		foreach ( $this->state as $key => $val ) {
			if ($val ['stateEName'] == $stateSta) {
				$returnVal = $val ['stateVal'];
			}
		}
		//TODO:����쳣����
		return $returnVal;
	}

	/*****************************************************��ʾ�ָ���**********************************************/
	/**������Ӧ��*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$codeDao=new model_common_codeRule();
			$object['suppCode']=$codeDao->outsourcSupplierCode($this->tbl_name);//��Ӧ�̱��
			$object['suppTypeName'] = $datadictDao->getDataNameByCode($object['suppTypeCode']);

			//����������Ϣ
			$id = parent :: add_d($object, true);

			$linkmanDao=new model_outsourcing_supplier_linkman();
			if(is_array($object['linkman'])){//��ϵ��
				foreach($object['linkman'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$linkmanDao->add_d($val);
				}
			}
			$bankinfoDao=new model_outsourcing_supplier_bankinfo();
			if(is_array($object['bankinfo'])){//�����˺�
				foreach($object['bankinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$val['suppName']=$object['suppName'];
					$bankinfoDao->add_d($val);
				}
			}
			$hrInfoDao=new model_outsourcing_supplier_hrInfo();
			if(is_array($object['hrinfo'])){//������Դ��Ϣ
				foreach($object['hrinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$val['suppName']=$object['suppName'];
					$hrInfoDao->add_d($val);
				}
			}
			$workInfoDao=new model_outsourcing_supplier_workInfo();
			if(is_array($object['workinfo'])){//������Դ����������Ϣ
				foreach($object['workinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$id;
					$val['suppName']=$object['suppName'];
					$workInfoDao->add_d($val);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

		/**�༭��Ӧ��*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['suppTypeName'] = $datadictDao->getDataNameByCode($object['suppTypeCode']);

			//����������Ϣ
			$oldObj = $this->get_d ( $object ['id'] );
			$logSettringDao = new model_syslog_setting_logsetting ();
			$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $object );
			$id = parent :: edit_d($object, true);

			$linkmanDao=new model_outsourcing_supplier_linkman();
			$linkmanDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['linkman'])){//��ϵ��
				foreach($object['linkman'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$linkmanDao->add_d($val);
				}
			}
			$bankinfoDao=new model_outsourcing_supplier_bankinfo();
			$bankinfoDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['bankinfo'])){//�����˺�
				foreach($object['bankinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$val['suppName']=$object['suppName'];
					$bankinfoDao->add_d($val);
				}
			}
			$hrInfoDao=new model_outsourcing_supplier_hrInfo();
			$hrInfoDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['hrinfo'])){//������Դ��Ϣ
				foreach($object['hrinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$val['suppName']=$object['suppName'];
					$hrInfoDao->add_d($val);
				}
			}

			$workInfoDao=new model_outsourcing_supplier_workInfo();
			$workInfoDao->delete(array ('suppId' =>$object['id']));
			if(is_array($object['workinfo'])){//������Դ����������Ϣ
				foreach($object['workinfo'] as $key=>$val){
					$val['suppCode']=$object['suppCode'];
					$val['suppId']=$object['id'];
					$val['suppName']=$object['suppName'];
					$workInfoDao->add_d($val);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**��֤�ȼ����*/
	function changeGrade_d($object){
		try {
			$this->start_d();
			if($object['gradeChange']==4){//������
				$object['blackListReason']=$object['changeReason'];
			}
			$object['blackReason']=$object['changeReason'];
			//����������Ϣ
			$id = parent :: edit_d($object, true);

			$changeInfoDao=new model_outsourcing_supplier_changeInfo();
			$val['suppCode']=$object['suppCode'];
			$val['suppId']=$object['id'];
			$val['suppGradeOld']=$object['suppGrade'];
			$val['suppGrade']=$object['gradeChange'];
			$val['remark']=$object['changeReason'];
			$changeInfoDao->add_d($val,true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

		/**��֤�ȼ��������*/
	function dealChange_d($id){
		try {
			$this->start_d();
			$object=$this->get_d($id);
			$obj['id']=$id;
			$obj['suppGrade']=$object['gradeChange'];
			//����������Ϣ
			$id = parent :: edit_d($obj, true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

			/**��֤�ȼ��������*/
	function ajaxChange_d($id){
		try {
			$this->start_d();
			$obj['id']=$id;
			$obj['ExaStatus']='���';
			//����������Ϣ
			$id = parent :: edit_d($obj, true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

/**��ɾ����Ӧ��*/
	function deleteSupp_d($id,$isDel){
		try {
			$this->start_d();
			$obj['id']=$id;
			$obj['isDel']=$isDel;
			//����������Ϣ
			$id = parent :: edit_d($obj, true);
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

			/******************* S ���뵼��ϵ�� ************************/
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
		$linkmanDao=new model_outsourcing_supplier_linkman();
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if( empty($val[1])){
						continue;
					}else{
						//��������
						$inArr = array();

						//��Ӧ������
						if(!empty($val[0])&&trim($val[0])!=''){
							$id=$this->get_table_fields('oa_outsourcesupp_supplib', "suppName='".$val[0]."'", 'id');
							if($id>0){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�������Ӧ����Ϣ�Ѵ���</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$inArr['suppName'] = trim($val[0]);
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[1])&&trim($val[1])!=''){
							$officeId=$this->get_table_fields('oa_esm_office_baseinfo', "officeName='".$val[1]."'", 'id');
							if($officeId>0){
								$inArr['officeId'] = $officeId;
								$inArr['officeName'] = trim($val[1]);
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!������ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//ʡ��
						if(!empty($val[2])&&trim($val[2])!=''){
							$provinceId=$this->get_table_fields('oa_system_province_info', "provinceName='".$val[2]."'", 'id');
							if($provinceId>0){
								$inArr['provinceId'] = $provinceId;
								$inArr['province'] = trim($val[2]);
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!ʡ�ݲ���ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//����ʱ��
						if(!empty($val[3])&& $val[3] != '0000-00-00'&&trim($val[3])!=''){
							$val[3] = trim($val[3]);

							if(!is_numeric($val[3])){
								$inArr['registeredDate'] = $val[3];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[3] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$entryDate = date('Y-m-d',strtotime ($val[3]));
									$inArr['registeredDate'] = $entryDate;
								}else{
									$inArr['registeredDate'] = $recorderDate;
								}
							}
						}

						//ע���ʽ���Ԫ��
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['registeredFunds'] = trim($val[4]);
						}

						//���˴���
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['legalRepre'] = trim($val[5]);
						}

						//��Ȩ�ṹ
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['equityStructure'] = trim($val[6]);
						}

						//��Ӫҵ��
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['mainBusiness'] = trim($val[7]);
						}

						//�ó���������
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['adeptNetType'] = trim($val[8]);
						}

						//�ó������豸
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['adeptDevice'] = trim($val[9]);
						}

						//ҵ��ֲ�
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['businessDistribute'] = trim($val[10]);
						}

						//˰��
						if(!empty($val[11])&&trim($val[11])!=''){
							$val[11] = trim($val[11]);
							if(!isset($datadictArr[$val[11]])){
								$rs = $datadictDao->getCodeByName('WBZZSD',$val[11]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[11]]['code'] = $rs;
									$inArr['taxPoint'] = $incentiveType;
								}
							}else{
								$incentiveType = $datadictArr[$val[11]]['code'];
							}
						}

						//����
						if(!empty($val[12])&&trim($val[12])!=''){
							switch(trim($val[12])){
								case '��':$inArr['suppGrade'] = 1;break;
								case '��':$inArr['suppGrade'] = 2;break;
								case 'ͭ':$inArr['suppGrade'] = 3;break;
								case '������':$inArr['suppGrade'] = 4;break;
								default:$inArr['suppGrade'] = '0';break;
							}
						}else{
							$inArr['suppGrade'] = '0';
						}

						//��֤����
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['certifyNumber'] = trim($val[13]);
						}

						//�ʱ�
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['zipCode'] = trim($val[14]);
						}

						//��ַ
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['address'] = trim($val[15]);
						}

						//��˾���
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['suppIntro'] = trim($val[16]);
						}

						$inArr['suppCode']=$codeDao->outsourcSupplierCode($this->tbl_name);//��Ӧ�̱��
						$inArr['suppTypeCode'] ='GYSLX-01';
						$inArr['suppTypeName'] ='�����˾';
//						print_r($inArr);
						$newId = parent::add_d($inArr,true);
						if($newId){
							//����
							if(!empty($val[17])&&trim($val[17])!=''){
								$linkmanArr['name'] = trim($val[17]);
							}
							//ְ��
							if(!empty($val[18])&&trim($val[18])!=''){
								$linkmanArr['jobName'] = trim($val[18]);
							}
							//�绰
							if(!empty($val[19])&&trim($val[19])!=''){
								$linkmanArr['mobile'] = trim($val[19]);
							}
							//����
							if(!empty($val[20])&&trim($val[20])!=''){
								$linkmanArr['email'] = trim($val[20]);
							}
							if(trim($val[17])!=''){
								$linkmanArr['suppCode']=$inArr['suppCode'];
								$linkmanArr['suppId']=$newId;
								$linkmanArr['defaultContact']='on';
								$linkmanDao->add_d($linkmanArr,true);
							}
							$tempArr['result'] = '����ɹ�';
						}else{
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}

	function updateExecelData_d(){
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
		$linkmanDao=new model_outsourcing_supplier_linkman();
		$logSettringDao = new model_syslog_setting_logsetting ();
		$codeDao=new model_common_codeRule();
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if( empty($val[1])){
						continue;
					}else{
						//��������
						$inArr = array();

						//��Ӧ������
						if(!empty($val[0])&&trim($val[0])!=''){
							$id=$this->get_table_fields('oa_outsourcesupp_supplib', "suppName='".$val[0]."'", 'id');
							if($id>0){
								$inArr['id'] =$id;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�������Ӧ����Ϣ������</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��Ӧ������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[1])&&trim($val[1])!=''){
							$officeId=$this->get_table_fields('oa_esm_office_baseinfo', "officeName='".$val[1]."'", 'id');
							if($officeId>0){
								$inArr['officeId'] = $officeId;
								$inArr['officeName'] = trim($val[1]);
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!������ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//ʡ��
						if(!empty($val[2])&&trim($val[2])!=''){
							$provinceId=$this->get_table_fields('oa_system_province_info', "provinceName='".$val[2]."'", 'id');
							if($provinceId>0){
								$inArr['provinceId'] = $provinceId;
								$inArr['province'] = trim($val[2]);
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!ʡ�ݲ���ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//����ʱ��
						if(!empty($val[3])&& $val[3] != '0000-00-00'&&trim($val[3])!=''){
							$val[3] = trim($val[3]);

							if(!is_numeric($val[3])){
								$inArr['registeredDate'] = $val[3];
							}else{
								$recorderDate = date('Y-m-d',(mktime(0,0,0,1, $val[3] - 1 , 1900)));
								if($recorderDate=='1970-01-01'){
									$entryDate = date('Y-m-d',strtotime ($val[3]));
									$inArr['registeredDate'] = $entryDate;
								}else{
									$inArr['registeredDate'] = $recorderDate;
								}
							}
						}

						//ע���ʽ���Ԫ��
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['registeredFunds'] = trim($val[4]);
						}

						//���˴���
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['legalRepre'] = trim($val[5]);
						}

						//��Ȩ�ṹ
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['equityStructure'] = trim($val[6]);
						}

						//��Ӫҵ��
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['mainBusiness'] = trim($val[7]);
						}

						//�ó���������
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['adeptNetType'] = trim($val[8]);
						}

						//�ó������豸
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['adeptDevice'] = trim($val[9]);
						}

						//ҵ��ֲ�
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['businessDistribute'] = trim($val[10]);
						}

						//˰��
						if(!empty($val[11])&&trim($val[11])!=''){
							$val[11] = trim($val[11]);
							if(!isset($datadictArr[$val[11]])){
								$rs = $datadictDao->getCodeByName('WBZZSD',$val[11]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[11]]['code'] = $rs;
									$inArr['taxPoint'] = $incentiveType;
								}
							}else{
								$incentiveType = $datadictArr[$val[11]]['code'];
							}
						}

						//����
						if(!empty($val[12])&&trim($val[12])!=''){
							switch(trim($val[12])){
								case '��':$inArr['suppGrade'] = 1;break;
								case '��':$inArr['suppGrade'] = 2;break;
								case 'ͭ':$inArr['suppGrade'] = 3;break;
								case '������':$inArr['suppGrade'] = 4;break;
								default:$inArr['suppGrade'] = '0';break;
							}
						}

						//��֤����
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['certifyNumber'] = trim($val[13]);
						}

						//�ʱ�
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['zipCode'] = trim($val[14]);
						}

						//��ַ
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['address'] = trim($val[15]);
						}

						//��˾���
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['suppIntro'] = trim($val[16]);
						}
//						print_r($inArr);
						$oldObj = $this->get_d ( $inArr ['id'] );
						$logSettringDao->compareModelObj ( $this->tbl_name, $oldObj, $inArr );
						$newId = parent::edit_d($inArr,true);
						if($newId){
							$tempArr['result'] = '���³ɹ�';
						}else{
							$tempArr['result'] = '<font color=red>����ʧ��</font>';
						}
						$tempArr['docCode'] = '��' . $actNum .'������';
						array_push( $resultArr,$tempArr );
					}
				}
				return $resultArr;
			}
		}
	}
	/**
	 * ��ȡʡ�ݱ��룬��ϵ�ˣ��绰,�����˻�������
	 * @param unknown $object
	 */
	function getInfo_d($object){
		$provinceDao = new model_system_procity_province();
		$provinceArr = $provinceDao->find(array('id' => $object['provinceId']));
		$linkmanDao = new model_outsourcing_supplier_linkman();
		$linkmanArr = $linkmanDao->find(array('suppId' => $object['id']));
		$bankinfoDao = new model_outsourcing_supplier_bankinfo();
		$bankinfoArr = $bankinfoDao->find(array('suppId' => $object['id']));
		$result = array('0'=>array('provinceCode' => $provinceArr['provinceCode'],'linkman' => $linkmanArr['name'],
				'phone' => $linkmanArr['mobile'],'bank'=>$bankinfoArr['bankName'],'account'=>$bankinfoArr['accountNum']));
		return $result;
	}
 }