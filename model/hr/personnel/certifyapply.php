<?php

/**
 * @author Show
 * @Date 2012��5��31�� ������ 17:39:42
 * @version 1.0
 * @description:��ְ�ʸ���Ϣ Model��
 */
include_once WEB_TOR . "module/phpExcel/classes/PHPExcel.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/IOFactory.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Cell.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel5.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/Excel2007.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/ReferenceHelper.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Reader/DefaultReadFilter.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/Shared/OLERead.php";
include_once WEB_TOR . "module/phpExcel/Classes/PHPExcel/CachedObjectStorageFactory.php";

class model_hr_personnel_certifyapply extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_personnel_certifyapply";
		$this->sql_map = "hr/personnel/certifyapplySql.php";
		parent :: __construct();
	}

	//��Ҫ�����ֵ䴦����ֶ�
    public $datadictFieldArr = array(
		'careerDirection','baseLevel',
		'baseGrade','finalCareer',
		'finalLevel','finalTitle',
		'finalGrade',
        'nowLevel','nowGrade',
        'nowDirection',
        'certifyDirection'
	);

	//״̬
	function rtStatus_c($status){
		switch($status){
			case 0 : return 'δ�ύ';break;
			case 1 : return '������';break;
			case 2 : return '��֤�������';break;
			case 3 : return '��֤׼����';break;
			case 4 : return '��֤������';break;
			case 5 : return '��֤������';break;
			case 6 : return '��֤�����';break;
			case 7 : return '��֤��������';break;
			case 8 : return '��֤ʧ��';break;
			case 10 : return '��֤�����';break;
			case 11 : return '���';break;
			case 12 : return '���';break;
			default : return $status;
		}
	}

	//�����Ƿ�ͨ��
	function rtIsPass_d($val){
		if($val == 1){
			return 'ͨ��';
		}else if($val === '0'){
			return '��ͨ��';
		}else{
			return '';
		}
	}

	//��ȡ������Ϣ
	function getPersonnelInfo_d($userId){
		$personnelDao = new model_hr_personnel_personnel();
		$personnelInfo = $personnelDao->find(array('userAccount' => $userId));
		if(!$personnelInfo){
			return false;
		}
		if($personnelInfo['entryDate'] != '0000-00-00' && !empty($personnelInfo['entryDate'])){
			$personnelInfo['workExperience'] = (substr(day_date,0,4) - substr($personnelInfo['entryDate'],0,4));
		}else{
			$personnelInfo['workExperience'] = '';
		}
		return $personnelInfo;
	}

	//��ȡ��֤���۱�
	function getAssess_d($id){
		$cassessDao = new model_hr_certifyapply_cassess();
		return $cassessDao->find(array('applyId' => $id),null,'id');
	}

	//��ȡ��֤���۱�
	function getScore_d($cassessId){
		$scoreDao = new model_hr_certifyapply_score();
		return $scoreDao->find(array('cassessId' => $cassessId),null,'id');
	}

	/***************** ��ɾ�Ĳ� ******************************/

	//��дadd_d
	function add_d($object){
		$object = $this->processDatadict($object);
		$object['status']=11;
		$object['ExaStatus'] = "���";

		return parent::add_d($object,true);
	}

	//��дedit
	function edit_d($object){
		$object = $this->processDatadict($object);

		return parent::edit_d($object,true);
	}

    //��˱༭�������������ֵ䴦��
    function editConfirm_d($object){
        try{
            return parent::edit_d($object,true);

        }catch(exception $e){
            throw $e;
            return false;
        }
    }

	//��дaddApply_d
	function addApply_d($object){
        //��ȡ��ΪҪ��
        $certifyapplyexp = $object['certifyapplyexp'];
        unset($object['certifyapplyexp']);

		try{
			$this->start_d();

			//�����ֵ䴦��
			$object = $this->processDatadict($object);
			if($object['status']==1){
				$object['ExaStatus'] = AUDITING;
			}else{
				$object['ExaStatus'] = WAITAUDIT;

			}

			$newId =  parent::add_d($object,true);

            //���������Ա
            $certifyapplyexpDao = new model_hr_personnel_certifyapplyexp();
            $certifyapplyexpDao->createBatch($certifyapplyexp,array('applyId' => $newId));

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	//editapply
	function editApply_d($object){
//		echo "<pre>";print_r($object);die();
        //��ȡ��ΪҪ��
        $certifyapplyexp = $object['certifyapplyexp'];
        unset($object['certifyapplyexp']);

		try{
			$this->start_d();

			//�����ֵ䴦��
			$object = $this->processDatadict($object);

			parent::edit_d($object,true);

            //���������Ա
            $certifyapplyexpDao = new model_hr_personnel_certifyapplyexp();
            if(is_array($certifyapplyexp)){
            	$certifyapplyexpDao->saveDelBatch($certifyapplyexp);
            }

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
		//editapply
	function backApply_d($object){
		try{
			$this->start_d();
			$object['status']='12';
			$object['ExaStatus'] = AUDITING;
			parent::edit_d($object,true);

			$row=$this->get_d($object['id']);
			//�����ʼ�
			$emailArr=array();
			$emailArr['issend'] = 'y';
			$emailArr['TO_ID']=$row['userAccount'];
			$emailArr['TO_NAME']=$row['userName'];
			if(is_array($row )){
				$j=0;
				$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>Ա������</b></td><td><b>��������</b></td><td><b>����ͨ��</b></td><td><b>���뼶��</b></td></tr>";

				$userName=$row['userName'];
				$applyDate=$row['applyDate'];
				$careerDirectionName=$row['careerDirectionName'];
				$baseLevelName=$row['baseLevelName'];
				$baseGradeName=$row['baseGradeName'];
					$addmsg .=<<<EOT
					<tr align="center" >
								<td>$userName</td>
								<td>$applyDate</td>
								<td>$careerDirectionName</td>
								<td>$baseLevelName</td>
						</tr>
EOT;
					$addmsg.="</table><br/>";
					$addmsg.="</table><br><font color=red>���ԭ��</font><br>".$object['backReason'];
		}
			$emailDao = new model_common_mail();
			$emailInfo = $emailDao->emailInquiry($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'certifyapplyBack','������ְ�ʸ����뵥�Ѵ��','',$emailArr['TO_ID'],$addmsg,1);


			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �������뵥״̬
	 */
	function updateStatus_d($id,$status){
		try{
			$updateArr = array('status' => $status);
			$updateArr = $this->addUpdateInfo($updateArr);

			$this->update(array('id' => $id),$updateArr);
			return true;
		}catch(exception $e){
			throw $e;
			return false;
		}
	}

	/**
	 *�ύ��֤����
	 */
	function submitApply_d($id){
			$ExaStatus= '��������';
			$sql="update oa_hr_personnel_certifyapply set status=1,ExaStatus='".$ExaStatus."' where id=".$id."";
			$flag=$this->query($sql);
			if($flag){
				return true;
			}else{
				return false;
			}
	}

	 		/**
	 *����Ա��������Ϣ
	 *
	 */
	 function aduitPass_d($ids){
	 	try{
			$this->start_d();
			if(is_array($ids)){
				$idArr=$ids;
			}else{
				$idArr=explode(',',$ids);
			}
			$flag=true;
			if(!empty($idArr)){
				foreach($idArr as $key=>$val){
					$info = $this->get_d($val);
					$object['id']=$val;
					$object['status']=2;
					$object['ExaStatus']='���';
					$id=parent::edit_d($object,true);

					$row=$this->get_d($object['id']);
					//�����ʼ�
					$emailArr=array();
					$emailArr['issend'] = 'y';
					$emailArr['TO_ID']=$row['userAccount'];
					$emailArr['TO_NAME']=$row['userName'];
					if(is_array($row )){
						$addmsg="";
						$j=0;
						$addmsg.="<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>Ա������</b></td><td><b>��������</b></td><td><b>����ͨ��</b></td><td><b>���뼶��</b></td></tr>";

						$userName=$row['userName'];
						$applyDate=$row['applyDate'];
						$careerDirectionName=$row['careerDirectionName'];
						$baseLevelName=$row['baseLevelName'];
						$baseGradeName=$row['baseGradeName'];
							$addmsg .=<<<EOT
							<tr align="center" >
										<td>$userName</td>
										<td>$applyDate</td>
										<td>$careerDirectionName</td>
										<td>$baseLevelName</td>
								</tr>
EOT;
					$addmsg.="</table><br/>";
					$emailDao = new model_common_mail();
					$emailInfo = $emailDao->certifyapply($emailArr['issend'],$_SESSION['USERNAME'],$_SESSION['EMAIL'],'certifyapplyBack','������ְ�ʸ����뵥����ˣ���׼��������֤������','',$emailArr['TO_ID'],$addmsg,1);
				}
			}
			}
			$this->commit_d();
			return $flag;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	 }

	/****************** ҵ���߼� ******************************/
	/**
	 *�����ɹ����ڸ����б������Ϣ
	 */
	function dealAfterAudit_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];

	 	$object = $this->get_d($objId);
	 	if($object['ExaStatus'] == AUDITED){
			return 1;
	 	}
	 	return 1;
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

		$userConutArr = array();//�û�����
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();

		//�����ֶα�ͷ����
		$objNameArr = array (
			0 => 'userNo', //�û����
			1 => 'userName', //�û�����
			2 => 'applyDate', //��������
			3 => 'careerDirectionName', //ְҵ��չͨ��
		    4 => 'baseLevelName', //����
			5 => 'baseGradeName', //���뼶��
		    7 => 'ExaStatus', //�������
		    8 => 'baseScore',//���Ե÷�
		    9 => 'baseResult',//���Խ��
		    10 => 'finalResult', //������֤���
		    11 => 'finalScore', //��֤���۵÷�
		    12 => 'finalCareerName',//ְҵ��չͨ��
		    13 => 'finalLevelName',//����
		    14 => 'finalTitleName',//�����Ӧ�ĳ�ν
		    15 => 'finalGradeName',//����
		    16 => 'finalDate'//��֤�����Чʱ��
		);

		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
			if(is_array($excelData)){
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					if(empty($row[0]) && empty($row[1]) && empty($row[2]) && empty($row[3]) && empty($row[4])&& empty($row[5]))
						continue;
					else{
						foreach ( $objNameArr as $index => $fieldName ) {
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
				}

//				print_r($objectArr);

				//������ѭ��
				foreach($objectArr as $key => $val){
//					echo "<pre>";
//					print_r($val);
					if($key === 0){
						continue ;
					}
					$inArr = array();
					$actNum = $key + 2;

					//��ʦԱ�����
					if(!empty($val['userNo'])){
						if(!isset($userArr[$val['userNo']])){
							$rs = $otherDataDao->getUserInfoByUserNo($val['userNo']);
							if(!empty($rs)){
								$userConutArr[$val['userNo']] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�Ա�����:'.$val['userNo'] .'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						$inArr['userAccount'] = $userConutArr[$val['userNo']]['USER_ID'];
						$inArr['deptId'] = $userConutArr[$val['userNo']]['DEPT_ID'];
						$inArr['deptName'] = $userConutArr[$val['userNo']]['DEPT_NAME'];
						$inArr['userNo'] = $val['userNo'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!û��Ա�����</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//Ա������
					if(!empty($val['userName'])){
						$inArr['userName'] = $val['userName'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!û��Ա������</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

					//��������
					if(!empty($val['applyDate'])&& $val['applyDate'] != '0000-00-00'){
						$val['applyDate'] = trim($val['applyDate']);

						if(!is_numeric($val['applyDate'])){
							$inArr['applyDate'] = $val['applyDate'];
						}else{
							$applyDate = date('Y-m-d',(mktime(0,0,0,1, $val['applyDate'] - 1 , 1900)));
							$inArr['applyDate'] = $applyDate;
						}
					}

					//ְҵ��չͨ��
					if(!empty($val['careerDirectionName'])){
						$val['careerDirectionName'] = trim($val['careerDirectionName']);
						if(!isset($datadictArr[$val['careerDirectionName']])){
							$rs = $datadictDao->getCodeByName('HRZYFZ',$val['careerDirectionName']);
							if(!empty($rs)){
								$careerDirection = $datadictArr[$val['careerDirectionName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ�ְҵ��չͨ��:'.$val['careerDirectionName'] .'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$careerDirection = $datadictArr[$val['careerDirectionName']]['code'];
						}
						$inArr['careerDirection'] = $careerDirection;
						$inArr['careerDirectionName'] = $val['careerDirectionName'];
					}

					//���뼶��
					if(!empty($val['baseLevelName'])){
						$val['baseLevelName'] = trim($val['baseLevelName']);
						if(!isset($datadictArr[$val['baseLevelName']])){
							$rs = $datadictDao->getCodeByName('HRRZJB',$val['baseLevelName']);
							if(!empty($rs)){
								$baseLevel = $datadictArr[$val['baseLevelName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ����뼶��:' . $val['baseLevelName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$baseLevel = $datadictArr[$val['baseLevelName']]['code'];
						}
						$inArr['baseLevel'] = $baseLevel;
						$inArr['baseLevelName'] = $val['baseLevelName'];
					}

					//���뼶��
					if(!empty($val['baseGradeName'])){
						$val['baseGradeName'] = trim($val['baseGradeName']);
						if(!isset($datadictArr[$val['baseGradeName']])){
							$rs = $datadictDao->getCodeByName('HRRZZD',$val['baseGradeName']);
							if(!empty($rs)){
								$baseGrade = $datadictArr[$val['baseGradeName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ����뼶��:' . $val['baseGradeName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$baseGrade = $datadictArr[$val['baseGradeName']]['code'];
						}
						$inArr['baseGrade'] = $baseGrade;
						$inArr['baseGradeName'] = $val['baseGradeName'];
					}

					//�������
					if(!empty($val['ExaStatus'])){
						$inArr['ExaStatus'] = $val['ExaStatus'];
					}

					//���Ե÷�
					if(!empty($val['baseScore'])){
						$inArr['baseScore'] = $val['baseScore'];
					}

					//���Խ��
					if(!empty($val['baseResult'])){
						if($val['baseResult'] == 'ͨ��'){
							$inArr['baseResult'] = 1;
						}else if($val['baseResult'] == '��ͨ��'){
							$inArr['baseResult'] = 0;
						}
					}

					//������֤���
					if(!empty($val['finalResult'])){
						if($val['finalResult'] == 'ͨ��'){
							$inArr['finalResult'] = 1;
						}else if($val['finalResult'] == '��ͨ��'){
							$inArr['finalResult'] = 0;
						}
					}

					//��֤�÷�
					if(!empty($val['finalScore'])){
						$inArr['finalScore'] = $val['finalScore'];
					}

					//ְҵ��չͨ��
					if(!empty($val['finalCareerName'])){
						$val['finalCareerName'] = trim($val['finalCareerName']);
						if(!isset($datadictArr[$val['finalCareerName']])){
							$rs = $datadictDao->getCodeByName('HRZYFZ',$val['finalCareerName']);
							if(!empty($rs)){
								$finalCareer = $datadictArr[$val['finalCareerName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���ְ֤ҵ��չͨ��:' . $val['finalCareerName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalCareer = $datadictArr[$val['finalCareerName']]['code'];
						}
						$inArr['finalCareer'] = $finalCareer;
						$inArr['finalCareerName'] = $val['finalCareerName'];
					}

					//����
					if(!empty($val['finalLevelName'])){
						$val['finalLevelName'] = trim($val['finalLevelName']);
						if(!isset($datadictArr[$val['finalLevelName']])){
							$rs = $datadictDao->getCodeByName('HRRZJB',$val['finalLevelName']);
							if(!empty($rs)){
								$finalLevel = $datadictArr[$val['finalLevelName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���֤����:' . $val['finalLevelName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalLevel = $datadictArr[$val['finalLevelName']]['code'];
						}
						$inArr['finalLevel'] = $finalLevel;
						$inArr['finalLevelName'] = $val['finalLevelName'];
					}

					//�����Ӧ�ĳ�ν
					if(!empty($val['finalTitleName'])){
						$val['finalTitleName'] = trim($val['finalTitleName']);
						if(!isset($datadictArr[$val['finalTitleName']])){
							$rs = $datadictDao->getCodeByName('HRRZCW',$val['finalTitleName']);
							if(!empty($rs)){
								$finalTitle = $datadictArr[$val['finalTitleName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���֤��ν:' . $val['finalTitleName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalTitle = $datadictArr[$val['finalTitleName']]['code'];
						}
						$inArr['finalTitle'] = $finalTitle;
						$inArr['finalTitleName'] = $val['finalTitleName'];
					}

					//����
					if(!empty($val['finalGradeName'])){
						$val['finalGradeName'] = trim($val['finalGradeName']);
						if(!isset($datadictArr[$val['finalGradeName']])){
							$rs = $datadictDao->getCodeByName('HRRZZD',$val['finalGradeName']);
							if(!empty($rs)){
								$finalGrade = $datadictArr[$val['finalGradeName']]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���֤����:' . $val['finalGradeName'].'</span>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}else{
							$finalGrade = $datadictArr[$val['finalGradeName']]['code'];
						}
						$inArr['finalGrade'] = $finalGrade;
						$inArr['finalGradeName'] = $val['finalGradeName'];
					}

					//����
					if(!empty($val['finalDate'])&& $val['finalDate'] != '0000-00-00'){
						$val['finalDate'] = trim($val['finalDate']);

						if(!is_numeric($val['finalDate'])){
							$inArr['finalDate'] = $val['finalDate'];
						}else{
							$finalDate = date('Y-m-d',(mktime(0,0,0,1, $val['finalDate'] - 1 , 1900)));
							$inArr['finalDate'] = $finalDate;
						}
					}
					$inArr['status'] = '11';
//					print_r($inArr);
					$newId = $this->add_d($inArr,true);
					if($newId){
						$tempArr['result'] = '����ɹ�';
					}else{
						$tempArr['result'] = '<span class="red">����ʧ��</span>';
					}
					$tempArr['docCode'] = '��' . $actNum .'������';
					array_push( $resultArr,$tempArr );
				}

				return $resultArr;
			}
		}
	}

	function updataExecelData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$updataArr = array();//��������

		$userConutArr = array();//�û�����
		$userArr = array();//�û�����
		$deptArr = array();//��������
		$jobsArr = array();//ְλ����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();

		//�����ֶα�ͷ����
		$objNameArr = array (
			0 => 'userNo', //�û����
			1 => 'userName', //�û�����
			2 => 'applyDate', //��������
			3 => 'careerDirectionName', //ְҵ��չͨ��
		    4 => 'baseLevelName', //����
			5 => 'baseGradeName', //���뼶��
		    7 => 'ExaStatus', //�������
		    8 => 'baseScore',//���Ե÷�
		    9 => 'baseResult',//���Խ��
		    10 => 'finalResult', //������֤���
		    11 => 'finalScore', //��֤���۵÷�
		    12 => 'finalCareerName',//ְҵ��չͨ��
		    13 => 'finalLevelName',//����
		    14 => 'finalTitleName',//�����Ӧ�ĳ�ν
		    15 => 'finalGradeName',//����
		    16 => 'finalDate'//��֤�����Чʱ��
		);

		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
//			echo "<pre>";
			if(is_array($excelData)){
				$objectArr = array ();
				foreach ( $excelData as $rNum => $row ) {
					if(empty($row[0]) && empty($row[1]) && empty($row[2]) && empty($row[3]) && empty($row[4])&& empty($row[5]))
						continue;
					else{
						foreach ( $objNameArr as $index => $fieldName ) {
							$objectArr [$rNum] [$fieldName] = $row [$index];
						}
					}
				}
			}
			//print_r($objectArr);
			foreach($objectArr as $key => $val){
				$actNum = $key + 2;
				if($key === 0){
						continue ;
					}
					//��ʦԱ�����
					if(!empty($val['userNo'])){
						$updataArr['userNo'] = $val['userNo'];
					}else{
						$tempArr['docCode'] = '��' . $actNum .'������';
						$tempArr['result'] = '<span class="red">����ʧ��!û��Ա�����</span>';
						array_push( $resultArr,$tempArr );
						continue;
					}

				//���Ե÷�
				if(!empty($val['baseScore'])){
					$updataArr['baseScore'] = $val['baseScore'];
				}

				//���Խ��
				if(!empty($val['baseResult'])){
					if($val['baseResult']=="ͨ��"){
						$updataArr['baseResult'] = 1;
					}else{
						$updataArr['baseResult'] = 0;
					}
				}
				$id=$this->update(array("userNo"=>$updataArr['userNo']),array("baseScore"=>$updataArr['baseScore'],"baseResult"=>$updataArr['baseResult']));
				if($id){
					$tempArr['result'] = '����ɹ�';
				}else{
					$tempArr['result'] = '<span class="red">����ʧ��</span>';
				}
				$tempArr['docCode'] = '��' . $actNum .'������';
				array_push( $resultArr,$tempArr );
			}
			return $resultArr;
		}
	}

		/**
	 * ���������Ϣ
	 *
	 */
	 function updataCertifyapplyData_d(){
		set_time_limit(0);
		$service = $this->service;
		$filename = $_FILES ["inputExcel"] ["name"];
		$temp_name = $_FILES ["inputExcel"] ["tmp_name"];
		$fileType = $_FILES ["inputExcel"] ["type"];
		$resultArr = array();//�������
		$excelData = array ();//excel��������
		$tempArr = array();
		$inArr = array();//��������
		$userArr = array();//�û�����
		$otherDataDao = new model_common_otherdatas();//������Ϣ��ѯ
		$datadictArr = array();//�����ֵ�����
		$datadictDao = new model_system_datadict_datadict();
		//�жϵ��������Ƿ�Ϊexcel��
		if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
			$excelData = util_excelUtil::upReadExcelDataClear ( $filename, $temp_name );
			spl_autoload_register("__autoload");
			if(is_array($excelData)){

				//������ѭ��
				foreach($excelData as $key => $val){
//					if($key === 0){
//						continue ;
//					}
					$actNum = $key + 2;
					if(0){
						continue;
					}else{
						//��������
						$inArr = array();

						//Ա�����
						if(!empty($val[0])&&trim($val[0])!=''){
							$val[0]=trim($val[0]);
							$inArr['userNo'] = $val[0];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!Ա�����Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//��������
						if(!empty($val[4])&&trim($val[4])!=''){
							$val[4]=trim($val[4]);
							$rs = $datadictDao->getCodeByName('HRRZJB',$val[4]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[4]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!����������ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['nowLevel'] = $incentiveType;
							$inArr['nowLevelName'] = $val[4];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}


						//��������
						if(!empty($val[5])&&trim($val[5])!=''){
							$val[5]=trim($val[5]);
							$rs = $datadictDao->getCodeByName('HRRZZD',$val[5]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[5]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!�������Ȳ���ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['nowGrade'] = $incentiveType;
							$inArr['nowGradeName'] = $val[5];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}
						//��������
						if(!empty($val[8])&& $val[8] != '0000-00-00'&&trim($val[8])!=''){
							$val[8] = trim($val[8]);
							if(!is_numeric($val[8])){
								$inArr['applyDate'] = $val[8];
							}else{
								$applyDate = date('Y-m-d',(mktime(0,0,0,1, $val[8] - 1 , 1900)));
								if($applyDate=='1970-01-01'){
									$quitDate = date('Y-m-d',strtotime ($val[8]));
									$inArr['applyDate'] = $quitDate;
								}else{
									$inArr['applyDate'] = $applyDate;
								}
							}
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!��������Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}




						//����ͨ��
						if(!empty($val[9])&&trim($val[9])!=''){
							$val[9]=trim($val[9]);
							$rs = $datadictDao->getCodeByName('HRZYFZ',$val[9]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[9]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!����ͨ������ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['careerDirection'] = $incentiveType;
							$inArr['careerDirectionName'] = $val[9];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!����ͨ��Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//���뼶��
						if(!empty($val[10])&&trim($val[10])!=''){
							$val[10]=trim($val[10]);
							$rs = $datadictDao->getCodeByName('HRRZJB',$val[10]);
							if(!empty($rs)){
								$incentiveType = $datadictArr[$val[10]]['code'] = $rs;
							}else{
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!���뼶����ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
							$inArr['baseLevel'] = $incentiveType;
							$inArr['baseLevelName'] = $val[10];
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!���뼶��Ϊ��</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						//���Ե÷�
						if(!empty($val[13])&&trim($val[13])!=''){
							$val[13] = trim($val[13]);
							if ($val[13] >= 0 && $val[13] <= 100) {
								$inArr['baseScore'] = $val[13];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!���Ե÷ֲ���0~100֮��</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//���Խ��
						if(!empty($val[14])&&trim($val[14])!=''){
							$val[14] = trim($val[14]);
							if($val[14] == 'ͨ��'){
								$inArr['baseResult'] = 1;
							}else if($val[14] == '��ͨ��'){
								$inArr['baseResult'] = 0;
							}else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!���Խ������ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//��֤���
						if(!empty($val[15])&&trim($val[15])!=''){
							$val[15] = trim($val[15]);
							if($val[15] == 'ͨ��'){
								$inArr['finalResult'] = 1;
							}else if($val[15] == '��ͨ��'){
								$inArr['finalResult'] = 0;
							}else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��֤�������ȷ</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//��֤�÷�
						if(!empty($val[16])&&trim($val[16])!=''){
							$val[16] = trim($val[16]);
							if ($val[16] >= 0 && $val[16] <= 100) {
								$inArr['finalScore'] = $val[16];
							} else {
								$tempArr['docCode'] = '��' . $actNum .'������';
								$tempArr['result'] = '<font color=red>����ʧ��!��֤�÷ֲ���0~100֮��</font>';
								array_push( $resultArr,$tempArr );
								continue;
							}
						}

						//��֤ͨ��
						if(!empty($val[17])&&trim($val[17])!=''){
							$val[17] = trim($val[17]);
							if(!isset($datadictArr[$val[17]])){
								$rs = $datadictDao->getCodeByName('HRZYFZ',$val[17]);
								if(!empty($rs)){
									$finalCareer = $datadictArr[$val[17]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���֤ͨ��:' . $val[17].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalCareer = $datadictArr[$val[17]]['code'];
							}
							$inArr['finalCareer'] = $finalCareer;
							$inArr['finalCareerName'] = $val[17];
						}

						//��֤����
						if(!empty($val[18])&&trim($val[18])!=''){
							$val[18] = trim($val[18]);
							if(!isset($datadictArr[$val[18]])){
								$rs = $datadictDao->getCodeByName('HRRZJB',$val[18]);
								if(!empty($rs)){
									$finalLevel = $datadictArr[$val[18]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���֤����:' . $val[18].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalLevel = $datadictArr[$val[18]]['code'];
							}
							$inArr['finalLevel'] = $finalLevel;
							$inArr['finalLevelName'] = $val[18];
						}

						//��֤����
						if(!empty($val[19])&&trim($val[19])!=''){
							$val[19] = trim($val[19]);
							if(!isset($datadictArr[$val[19]])){
								$rs = $datadictDao->getCodeByName('HRRZZD',$val[19]);
								if(!empty($rs)){
									$finalGrade = $datadictArr[$val[19]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���֤����:' . $val[19].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalGrade = $datadictArr[$val[19]]['code'];
							}
							$inArr['finalGrade'] = $finalGrade;
							$inArr['finalGradeName'] = $val[19];
						}

						//��֤��ν
						if(!empty($val[20])&&trim($val[20])!=''){
							$val[20] = trim($val[20]);
							if(!isset($datadictArr[$val[20]])){
								$rs = $datadictDao->getCodeByName('HRRZCW',$val[20]);
								if(!empty($rs)){
									$finalTitle = $datadictArr[$val[20]]['code'] = $rs;
								}else{
									$tempArr['docCode'] = '��' . $actNum .'������';
									$tempArr['result'] = '<span class="red">����ʧ��!�����ڵ���֤��ν:' . $val[20].'</span>';
									array_push( $resultArr,$tempArr );
									continue;
								}
							}else{
								$finalTitle = $datadictArr[$val[20]]['code'];
							}
							$inArr['finalTitle'] = $finalTitle;
							$inArr['finalTitleName'] = $val[20];
						}

						//��֤�����Ч����
						if(!empty($val[21])&& $val[21] != '0000-00-00'){
							$val[21] = trim($val[21]);
							if(!is_numeric($val[21])){
								$inArr['finalDate'] = $val[21];
							}else{
								$finalDate = date('Y-m-d',(mktime(0,0,0,1, $val[21] - 1 , 1900)));
								$inArr['finalDate'] = $finalDate;
							}
						}

						$id=$this->get_table_fields('oa_hr_personnel_certifyapply', "userNo='".$inArr['userNo']."' and applyDate='".$inArr['applyDate']."' and careerDirection='".$inArr['careerDirection']."' and baseLevel='".$inArr['baseLevel']."'", 'id');
						if($id){
							/*
							$newId = $this->update(
							array("id" => $id) ,
							array("nowLevel"=>$inArr['nowLevel'] ,"nowLevelName"=>$inArr['nowLevelName'] ,"nowGrade"=>$inArr['nowGrade'] ,"nowGradeName"=>$inArr['nowGradeName'] ,"baseScore"=>$inArr['baseScore'] ,"baseResult"=>$inArr['baseResult'] ,"finalResult"=>$inArr['finalResult'] ,"finalScore"=>$inArr['finalScore'] ,"finalCareerName"=>$inArr['finalCareerName'] ,"finalLevelName"=>$inArr['finalLevelName'] ,"finalGradeName"=>$inArr['finalGradeName'] ,"finalTitleName"=>$inArr['finalTitleName'] ,"finalDate"=>$inArr['finalDate'] ,"finalCareer"=>$inArr['finalCareer'] ,"finalLevel"=>$inArr['finalLevel'] ,"finalGrade"=>$inArr['finalGrade'] ,"finalTitle"=>$inArr['finalTitle'])
							);
							*/
							$inArr['id'] = $id;
							$newId = parent::edit_d($inArr,true);
						}else{
							$tempArr['docCode'] = '��' . $actNum .'������';
							$tempArr['result'] = '<font color=red>����ʧ��!����ְ�ʸ������¼������</font>';
							array_push( $resultArr,$tempArr );
							continue;
						}

						if($newId){
							$tempArr['result'] = '���³ɹ�';
						}else{
							$tempArr['result'] = '����ʧ��';
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
		 * ����excel
		 */
		function excelOut($rowdatas){
			PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
			//		//����һ��Excel������
			//		$objPhpExcelFile = new PHPExcel();


			$objReader = PHPExcel_IOFactory::createReader ( 'Excel5' ); //use excel2007 for 2007 format
			$objPhpExcelFile = $objReader->load ( "upfile/����-��ְ�ʸ񵼳�ģ��.xls" ); //��ȡģ��
			//Excel2003����ǰ�ĸ�ʽ
			$objWriter = new PHPExcel_Writer_Excel5 ( $objPhpExcelFile );

			//���ñ�����ļ����Ƽ�·��
			$fileName = "YXEXCEL_" . date ( 'H_i_s' ) . rand ( 0, 10 ) . ".xls";
			$path = "D:/EXCELDEMO";
			$fileSave = $path . "/" . $fileName;
			//�����ǶԹ������������������
			//���õ�ǰ�����������
			$objPhpExcelFile->getActiveSheet ()->setTitle ( iconv ( "gb2312", "utf-8", '��Ϣ�б�' ) );
			//���ñ�ͷ����ʽ ����
			$i = 2;
			if (! count ( array_filter ( $rowdatas ) ) == 0) {
				$row = $i;
				for($n = 0; $n < count ( $rowdatas ); $n ++) {
					$m = 0;
					foreach ( $rowdatas [$n] as $field => $value ) {
						$objPhpExcelFile->getActiveSheet ()->setCellValueExplicitByColumnAndRow ( $m, $row + $n, iconv ( "gb2312", "utf-8", $value ) );
						$m ++;
					}
					$i ++;
				}
			} else {
				$objPhpExcelFile->getActiveSheet ()->mergeCells ( 'A' . $i . ':' . 'AB' . $i );
				for($m = 0; $m < 10; $m ++) {
					$objPhpExcelFile->getActiveSheet ()->getStyleByColumnAndRow ( $m, $i )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
					$objPhpExcelFile->getActiveSheet ()->setCellValueByColumnAndRow ( $m, $i, iconv ( "gb2312", "utf-8", '���������Ϣ' ) );
				}
			}
			//�������
			ob_end_clean (); //��������������������������
			header ( "Content-Type: application/force-download" );
			header ( "Content-Type: application/octet-stream" );
			header ( "Content-Type: application/download" );
			header ( 'Content-Disposition:inline;filename="' . "����-��ְ�ʸ񵼳�.xls" . '"' );
			header ( "Content-Transfer-Encoding: binary" );
			header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
			header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
			header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
			header ( "Pragma: no-cache" );
			$objWriter->save ( 'php://output' );
		}
	/******************* E ���뵼��ϵ�� ************************/
}
?>