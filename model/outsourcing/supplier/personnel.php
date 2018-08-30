<?php
/**
 * @author Administrator
 * @Date 2013��10��22�� ���ڶ� 16:08:18
 * @version 1.0
 * @description:��Ӧ����Ա��Ϣ Model��
 */
 class model_outsourcing_supplier_personnel  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcesupp_personnel";
		$this->sql_map = "outsourcing/supplier/personnelSql.php";
		parent::__construct ();
	}

	/**
	 * @author Admin
	 *���ݹ�Ӧ�̱�Ż�ȡ��Ӧ��������Ա��Ϣ
	 */
	 function getPersonIdList($suppCode){
		$this->searchArr = array ('suppCode' => $suppCode );
		$personnelRow= $this->listBySqlId ( "select_default" );
		$userIdStr='';
		if(is_array($personnelRow)){
			$userIdArr=array();
			foreach($personnelRow as $key=>$val){
				if($val['userAccount']!=''){
					array_push($userIdArr,$val['userAccount']);
				}
			}
			if(!empty($userIdArr)){
				$userIdStr=implode(',',$userIdArr);
			}

		}

		return $userIdStr;
	 }

	/**
	 * @author Admin
	 *���OA�˺�
	 */
	 function createUser_d($object){
	 	if(is_array($object)){
			$userInfo=array();
		 	$find='';
		 	$values='';
			$userId=$this->get_table_fields('user', "USER_ID='".$object['userId']."'", 'USER_ID');
		 	if(!$userId){
	 			$tmpTplConcent=array( 'USER_ID' =>$object[ 'userId' ],
		 					   'USER_NAME' =>$object['userName'],
		                       'PASSWORD' =>md5($object['userId']),
		                       'LogName' =>$object[ 'userId' ],
//		                       'USER_PRIV' =>'87',
		                       'DEPT_ID' =>120,
		                       'SEX' =>$object['sex']=='��'?0:1,
		                       'Company' =>'dl',
		                       'Creator' =>$_SESSION['USER_ID'],
		                       'CreatDT' =>date('Y-m-d H:i:s'),
//		                       'jobs_id' =>'87',
		                       'userType' =>'2',
		                       'EMAIL' =>$object['email']
		                       );
		 		foreach ($tmpTplConcent as $key => $val) {
						$find .= $key . ',';
						$values .= "'$val',";
				}
				$find = trim($find, ',');
				$values = trim($values, ',');
				if($find&&$values){
					$user_sql = "INSERT INTO user($find)VALUES($values)";
					$this->query($user_sql);
				}
				return $object[ 'userId' ];
		 	}else{
		 		return $userId;
		 	}

	 	}
	 }

	/**������Ӧ����Ա��Ϣ*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['highEducationName'] = $datadictDao->getDataNameByCode($object['highEducation']);

			//����������Ϣ
			$id = parent :: add_d($object, true);

			$personLevelDao=new model_outsourcing_supplier_personLevel();
			if(is_array($object['areaSkill'])){//��������
				foreach($object['areaSkill'] as $key=>$val){
					$val['parentId']=$id;
					$val['levelName'] = $datadictDao->getDataNameByCode($val['levelCode']);
					$val['skillTypeName'] = $datadictDao->getDataNameByCode($val['skillTypeCode']);
					$personLevelDao->add_d($val);
				}
			}
			$userAccount = $this->get_table_fields('oa_hr_personnel', "identityCard='".$object['identityCard']."'", 'userAccount');
			if(empty($userAccount)){
  			//���oa�˺�
  			$userArr=array(
	 					   'userName' =>$object['userName'],
	                       'userId' =>substr($object['identityCard'],-8),
	                       'sex' =>'��',
	                       'email' =>$object['email']
                       );
		    $userId=$this->createUser_d($userArr);
		    $this->updateField('id='.$id,'userAccount',$userId);
      }else{
		    $this->updateField('id='.$id,'userAccount',$userAccount);
      }
			

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**�༭��Ӧ����Ա��Ϣ*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['highEducationName'] = $datadictDao->getDataNameByCode($object['highEducation']);
			$object['levelName'] = $datadictDao->getDataNameByCode($object['levelCode']);
			$object['skillTypeName'] = $datadictDao->getDataNameByCode($object['skillTypeCode']);

			//����������Ϣ
			$id = parent :: edit_d($object, false);

			$personLevelDao=new model_outsourcing_supplier_personLevel();
			$personLevelDao->delete(array ('parentId' =>$object['id']));
			if(is_array($object['areaSkill'])){//��������
				foreach($object['areaSkill'] as $key=>$val){
					$val['parentId']=$object['id'];
					$val['levelName'] = $datadictDao->getDataNameByCode($val['levelCode']);
					$val['skillTypeName'] = $datadictDao->getDataNameByCode($val['skillTypeCode']);
					$personLevelDao->add_d($val);
				}
			}

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
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$personLevelDao=new model_outsourcing_supplier_personLevel();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if( empty($val[1]) && empty($val[2])){
						continue;
					}else{
						//��������
						$inArr = array();

						//���
						if(!empty($val[0])&&trim($val[0])!=''){
						}

						//����
						if(!empty($val[1])&&trim($val[1])!=''){
							$inArr['userName'] = trim($val[1]);
						}

						//���֤����
						if(!empty($val[2])&&trim($val[2])!=''){
							$id=$this->get_table_fields('oa_outsourcesupp_personnel', "identityCard='".$val[2]."'", 'id');
							if($id>0){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!����Ա��Ϣ�Ѵ���</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$inArr['identityCard'] = trim($val[2]);
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!���֤����Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//�����Ӧ��
						if(!empty($val[3])&&trim($val[3])!=''){
							$val[3] = trim($val[3]);
							$suppId=$this->get_table_fields('oa_outsourcesupp_supplib', "suppName='".$val[3]."'", 'id');
							if($suppId){
								$suppCode=$this->get_table_fields('oa_outsourcesupp_supplib', "suppName='".$val[3]."'", 'suppCode');
								$inArr['suppId'] =$suppId;
								$inArr['suppCode'] =$suppCode;
							}else{
								$inArr['suppId'] ='';
								$inArr['suppCode'] ='';
							}
							$inArr['suppName'] =$val[3];
						}

						//����
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['age'] = trim($val[4]);
						}

						//��ϵ�绰
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['mobile'] = trim($val[5]);
						}

						//����
						if(!empty($val[6])&&trim($val[6])!=''){
							$inArr['email'] = trim($val[6]);
						}

						//ѧ��
						if(!empty($val[7])&&trim($val[7])!=''){
							$val[7] = trim($val[7]);
							if(!isset($datadictArr[$val[7]])){
								$rs = $datadictDao->getCodeByName('HRJYXL',$val[7]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[7]]['code'] = $rs;
								}else{
									$incentiveType="";
									$val[7]="";
								}
							}else{
								$incentiveType = $datadictArr[$val[7]]['code'];
							}
							$inArr['highEducation'] = $incentiveType;
							$inArr['highEducationName'] = $val[7];
						}

						//��ҵԺУ
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['highSchool'] = trim($val[8]);
						}

						//רҵ
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['professionalName'] = trim($val[9]);
						}

						//��ʼ����ʱ��
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['workBeginDate'] = trim($val[10]);
						}

						//�������Ź�������
						if(!empty($val[11])&&trim($val[11])!=''){
							$inArr['workYears'] = trim($val[11]);
						}



						//���̾����о�
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['tradeList'] = trim($val[14]);
						}

						//��������
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['certifyList'] = trim($val[15]);
						}

						//�����������
						if(!empty($val[16])&&trim($val[16])!=''){
							$inArr['remark'] = trim($val[16]);
						}

						//�Ƿ������
						if(!empty($val[17])&&trim($val[17])!=''){
							if($val[17]=='��'){
								$inArr['isBlack'] = 1;
							}
						}

						//˵��
						if(!empty($val[18])&&trim($val[18])!=''){
							$inArr['blackReason'] = trim($val[18]);
						}


//						print_r($inArr);
						$newId = parent::add_d($inArr,true);
						if($newId){
							//��������
							if(!empty($val[12])&&trim($val[12])!=''){
								$val[12] = trim($val[12]);
								if(!isset($datadictArr[$val[12]])){
									$rs = $datadictDao->getCodeByName('WBJNLX',$val[12]);
									if(!empty($rs)){
										$incentiveType = $datadictArr[$val[12]]['code'] = $rs;
									}else{
										$incentiveType="";
//										$val[12]="";
									}
								}else{
									$incentiveType = $datadictArr[$val[12]]['code'];
								}
								$detail['skillTypeCode'] = $incentiveType;
								$detail['skillTypeName'] = $val[12];
							}

							//����
							if(!empty($val[13])&&trim($val[13])!=''){
								$val[13] = trim($val[13]);
								if(!isset($datadictArr[$val[13]])){
									$rs = $datadictDao->getCodeByName('WBRYJB',$val[13]);
									if(!empty($rs)){
										$incentiveType = $datadictArr[$val[13]]['code'] = $rs;
									}else{
										$incentiveType="";
//										$val[13]="";
									}
								}else{
									$incentiveType = $datadictArr[$val[13]]['code'];
								}
								$detail['levelCode'] = $incentiveType;
								$detail['levelName'] = $val[13];
							}
							if(!empty($val[12])&&trim($val[12])!=''){
								$detail['parentId']=$newId;
								$personLevelDao->add_d($detail);
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
	/*
	 * �������Ա������
	 */
	function excelImport_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		$personLevelDao=new model_outsourcing_supplier_personLevel();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
					$actNum = $key + 2;
					if( empty($val[0]) && empty($val[1])){
						continue;
					}else{
						//��������
						$inArr = array();

						//����
						if(!empty($val[0])&&trim($val[0])!=''){
							$inArr['userName'] = trim($val[0]);
						}

						//���֤����
						if(!empty($val[1])&&trim($val[1])!=''){
							$id=$this->get_table_fields('oa_outsourcesupp_personnel', "identityCard='".$val[1]."'", 'id');
							if($id>0){
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!����Ա��Ϣ�Ѵ���</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}else{
								$judge=$this->validation_filter_id_card($val[1]);
								if($judge){
								$inArr['identityCard'] = trim($val[1]);
								}
								else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<font color=red>����ʧ��!���֤������Ч</font>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!���֤����Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//����
						if(!empty($val[2])&&trim($val[2])!=''){
							$inArr['age'] = trim($val[2]);
						}

						//��ϵ�绰
						if(!empty($val[4])&&trim($val[4])!=''){
							$inArr['mobile'] = trim($val[4]);
						}

						//����
						if(!empty($val[5])&&trim($val[5])!=''){
							$inArr['email'] = trim($val[5]);
						}

						//ѧ��
						if(!empty($val[6])&&trim($val[6])!=''){
							$val[6] = trim($val[6]);
							if(!isset($datadictArr[$val[6]])){
								$rs = $datadictDao->getCodeByName('HRJYXL',$val[6]);
								if(!empty($rs)){
									$incentiveType = $datadictArr[$val[6]]['code'] = $rs;
								}else{
									$incentiveType="";
									$val[6]="";
								}
							}else{
								$incentiveType = $datadictArr[$val[6]]['code'];
							}
							$inArr['highEducation'] = $incentiveType;
							$inArr['highEducationName'] = $val[6];
						}

						//��ҵԺУ
						if(!empty($val[7])&&trim($val[7])!=''){
							$inArr['highSchool'] = trim($val[7]);
						}

						//רҵ
						if(!empty($val[8])&&trim($val[8])!=''){
							$inArr['professionalName'] = trim($val[8]);
						}

						//��ʼ����ʱ��
						if(!empty($val[9])&&trim($val[9])!=''){
							$inArr['workBeginDate'] = trim($val[9]);
						}

						//�������Ź�������
						if(!empty($val[10])&&trim($val[10])!=''){
							$inArr['workYears'] = trim($val[10]);
						}

						//���̾����о�
						if(!empty($val[13])&&trim($val[13])!=''){
							$inArr['tradeList'] = trim($val[13]);
						}

						//��������
						if(!empty($val[14])&&trim($val[14])!=''){
							$inArr['certifyList'] = trim($val[14]);
						}

						//�����������
						if(!empty($val[15])&&trim($val[15])!=''){
							$inArr['remark'] = trim($val[15]);
						}

						//�Զ��������Ӧ�����Ӧ��
						$suppCode = $_SESSION['USER_ID'];
						$suppId=$this->get_table_fields('oa_outsourcesupp_supplib', "suppCode='".$suppCode."'", 'id');
						$suppName=$this->get_table_fields('oa_outsourcesupp_supplib', "suppCode='".$suppCode."'", 'suppName');
						$inArr['suppId'] = $suppId;
						$inArr['suppCode'] =$suppCode;
						$inArr['suppName'] =$suppName;

						//�������֤�����ȡ�������û��˻�
						$userAccount = $this->get_table_fields('oa_hr_personnel', "identityCard='".$inArr['identityCard']."'", 'userAccount');
						if(empty($userAccount)){
							//û���˻�������Ӷ�ӦOA�ʺ�
							 $userArr=array(
		 					   'userName' =>$inArr['userName'],
		                       'userId' =>substr($inArr['identityCard'],-8),
		                       'sex' =>$val[3]=='Ů'?'Ů':'��',
		                       'email' =>$inArr['email']
	                        );
						    $userId=$this->createUser_d($userArr);
							$inArr['userAccount'] = $userArr['userId'];
						}
						else{
							$inArr['userAccount'] =$userAccount;
						}
//						print_r($inArr);
						$newId = parent::add_d($inArr,true);
						if($newId){
							//��������
							if(!empty($val[11])&&trim($val[11])!=''){
								$val[11] = trim($val[11]);
								if(!isset($datadictArr[$val[11]])){
									$rs = $datadictDao->getCodeByName('WBJNLX',$val[11]);
									if(!empty($rs)){
										$incentiveType = $datadictArr[$val[11]]['code'] = $rs;
									}else{
										$incentiveType="";
									}
								}else{
									$incentiveType = $datadictArr[$val[11]]['code'];
								}
								$detail['skillTypeCode'] = $incentiveType;
								$detail['skillTypeName'] = $val[11];
							}

							//����
							if(!empty($val[12])&&trim($val[12])!=''){
								$val[12] = trim($val[12]);
								if(!isset($datadictArr[$val[12]])){
									$rs = $datadictDao->getCodeByName('WBRYJB',$val[12]);
									if(!empty($rs)){
										$incentiveType = $datadictArr[$val[12]]['code'] = $rs;
									}else{
										$incentiveType="";
									}
								}else{
									$incentiveType = $datadictArr[$val[12]]['code'];
								}
								$detail['levelCode'] = $incentiveType;
								$detail['levelName'] = $val[12];
							}
							if(!empty($val[11])&&trim($val[11])!=''){
								$detail['parentId']=$newId;
								$personLevelDao->add_d($detail);
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
	/**********************************���֤������֤***********************************************/
	function validation_filter_id_card($id_card)
	{
		if(strlen($id_card) == 18)
		{
			return $this->idcard_checksum18($id_card);
		}
		else if((strlen($id_card) == 15))
		{
			$id_card = $this->idcard_15to18($id_card);
			return $this->idcard_checksum18($id_card);
		}
		else
		{
			return false;
		}
	}
	// �������֤У���룬���ݹ��ұ�׼GB 11643-1999
	function idcard_verify_number($idcard_base)
	{
		if(strlen($idcard_base) != 17)
		{
			return false;
		}
		//��Ȩ����
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//У�����Ӧֵ
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++)
		{
			$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}
	// ��15λ���֤������18λ
	function idcard_15to18($idcard){
		if (strlen($idcard) != 15){
			return false;
		}
		else{
			// ������֤˳������996 997 998 999����Щ��Ϊ�����������˵��������
			if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
				$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
			}
			else{
				$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
			}
		}
		$idcard = $this->idcard_verify_number($idcard);
		return $idcard;
	}
	// 18λ���֤У������Ч�Լ��
	function idcard_checksum18($idcard){
		if (strlen($idcard) != 18){ return false; }
		$idcard_base = substr($idcard, 0, 17);
		if ($this->idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
			return false;
		}
		else{
			return true;
		}
	}
 }
?>