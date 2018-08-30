<?php
/**
 * @author Administrator
 * @Date 2013��11��19�� ���ڶ� 23:55:18
 * @version 1.0
 * @description:������� Model�� ��ͬ״̬
                            0.δ�ύ
                            1.������
                            2.ִ����
                            3.�ѹر�
                            4.�����
 */
 class model_outsourcing_approval_basic  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_approval";
		$this->sql_map = "outsourcing/approval/basicSql.php";
		parent::__construct ();
	}

	//��˾Ȩ�޴��� TODO
	protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������

	/**����*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['formCode']="WBL".date("Ymd").time();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);
			//��Ŀ����ȡ��
			$projectRental = $object['projectRental'];

			//��ȡ������˾����
			$object['formBelong'] = $_SESSION['USER_COM'];
			$object['formBelongName'] = $_SESSION['USER_COM_NAME'];
			$object['businessBelong'] = $_SESSION['USER_COM'];
			$object['businessBelongName'] = $_SESSION['USER_COM_NAME'];

			//����������Ϣ
			$id = parent :: add_d($object, true);

			$persronRentalDao=new model_outsourcing_approval_persronRental();
			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//��Ա����
				foreach($object['personList'] as $key=>$val){
					$val['mainId']=$id;
					$persronRentalDao->add_d($val);
				}
			}
			if(is_array($object['projectRental'])&&($object['outsourcing']=='HTWBFS-03'||$object['outsourcing']=='HTWBFS-01')){//����/�ְ�
				$projectrentalDao = new model_outsourcing_approval_projectRental();
				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//��ʽ������,ת����������
				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $id), $projectRental);
				$projectrentalDao->saveDelBatch($projectRental);
			}

			//����������뵥״̬
			if($object['applyId']){
				$applyDao=new model_outsourcing_outsourcing_apply();
				$applyDao->updateField('id='.$object['applyId'],'state','1');
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**�༭*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);

			//��Ŀ����ȡ��
			$projectRental = $object['projectRental'];
			//����������Ϣ
			$id = parent :: edit_d($object, true);

			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//��Ա����
				$persronRentalDao=new model_outsourcing_approval_persronRental();
				$persronRentalDao->delete(array ('mainId' =>$object['id']));
				foreach($object['personList'] as $key=>$val){
					$val['mainId']=$object['id'];
					$persronRentalDao->add_d($val);
				}
			}
			if(is_array($projectRental)&&($object['outsourcing']=='HTWBFS-03'||$object['outsourcing']=='HTWBFS-01')){//����/�ְ�
				$projectrentalDao = new model_outsourcing_approval_projectRental();
				$projectRental = $projectrentalDao->dataFormat_d($projectRental);//��ʽ������,ת����������
				$projectRental = util_arrayUtil :: setArrayFn(array('mainId' => $object['id']), $projectRental);
				$projectrentalDao->saveDelBatch($projectRental);
			}

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$row=$this->get_d($ids);
			//����������뵥״̬
			if($row['applyId']){
				$applyDao=new model_outsourcing_outsourcing_apply();
				$applyDao->updateField('id='.$row['applyId'],'state','0');
			}
			$this->deletes ( $ids );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 *��������ҵ��
	 */
	function dealAduit_d($id) {
		try {
			$row=$this->get_d($id);
			$personnelDao=new model_outsourcing_supplier_personnel();
			if($row['outsourcing']=='HTWBFS-02'){//��Ա����
				$persronRentalDao=new model_outsourcing_approval_persronRental();
				$$persronRows=$persronRentalDao->findAll(array ('mainId' =>$id));
				if(is_array($$persronRows)){
					foreach($$persronRows as $key=>$val){
						$suppCode=$this->get_table_fields('oa_outsourcesupp_supplib', "id='".$val['suppId']."'", 'suppCode');
						$userId=$this->get_table_fields('user', "USER_ID='".$suppCode."'", 'USER_ID');
						if(!$userId){
							//���oa�˺�
							$userArr=array(
					 					   'userName' =>$val['suppName'],
					                       'userId' =>$suppCode,
					                       'sex' =>'��',
					                       'email' =>''
				                       );
						    $userId=$personnelDao->createUser_d($userArr);
						}
					}

				}
			}
			if(($row['outsourcing']=='HTWBFS-03'||$row['outsourcing']=='HTWBFS-01')){//����/�ְ�
				if($row['suppId']){
					$userId=$this->get_table_fields('user', "USER_ID='".$row['suppCode']."'", 'USER_ID');
					if(!$userId){
						//���oa�˺�
						$userArr=array(
				 					   'userName' =>$row['suppName'],
				                       'userId' =>$row['suppCode'],
				                       'sex' =>'��',
				                       'email' =>''
			                       );
					    $userId=$personnelDao->createUser_d($userArr);
					}
				}
			}
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

		/***************** S ���ϵ�� *********************/
	/**
	 * �������
	 */
	function change_d($object){

		try{
			$this->start_d();
			$projectrentalDao = new model_outsourcing_approval_projectRental();

			//�����ֵ䴦��
			$datadictDao = new model_system_datadict_datadict();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);

			//ʵ���������
			$changeLogDao = new model_common_changeLog ( 'outsourcingapproval' );

			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//��Ա����
				foreach($object['personList'] as $key=>$val){
					if(isset($val['id'])){
						$object['personList'][$key]['oldId']=$val['id'];
						unset($object['personList'][$key]['id']);
					}
				}
			}
			if(is_array($object['projectRental'])&&($object['outsourcing']=='HTWBFS-03'||$object['outsourcing']=='HTWBFS-01')){//����/�ְ�
				$object['projectRental'] = $projectrentalDao->dataFormat_d($object['projectRental']);//��ʽ������,ת����������
				foreach($object['projectRental'] as $key=>$val){
					if(isset($val['id'])){
						$object['projectRental'][$key]['oldId']=$val['id'];
						unset($object['projectRental'][$key]['id']);
					}
				}
			}

			//��������
//			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//���������Ϣ
			$tempObjId = $changeLogDao->addLog ( $object );

			$this->commit_d();
			return $tempObjId;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}


	/**
	 * ���������ɺ����״̬
	 */
	function dealAfterAuditChange_d($objId,$userId){
		$obj = $this->get_d($objId);
	 	if($obj['ExaStatus'] == AUDITED){
	 		try{
	 			$this->start_d();

				//�����Ϣ����
		 		$changeLogDao = new model_common_changeLog ( 'outsourcingapproval' );
				$changeLogDao->confirmChange_d ( $obj );

                $this->update(array('id'=>$obj['originalId']),array('ExaStatus' => '���'));
				if($obj['applyId']){
					$applyDao=new model_outsourcing_outsourcing_apply();
					$applyDao->updateField('id='.$obj['applyId'],'state','4');
				}

	 			$this->commit_d();
	 			return 1;
	 		}catch(Exception $e){
	 			$this->rollBack();
	 			return 1;
	 		}
        }else{
            try{
                $this->start_d();

                $this->update(array('id'=>$obj['originalId']),array('ExaStatus' => '���'));//����������뵥״̬
				if($obj['applyId']){
					$applyDao=new model_outsourcing_outsourcing_apply();
					$applyDao->updateField('id='.$obj['applyId'],'state','4');
				}

                $this->commit_d();
                return 1;
            }catch(Exception $e){
                $this->rollBack();
                return 1;
            }
        }
	 	return 1;
	}

	/*
	 * �����ʼ�֪ͨ��������������Ա
	 */
	 function sendApplyMail_d( $obj ) {
	 	$applyDao = new model_outsourcing_outsourcing_apply();
	 	$receiverId = $applyDao->getMailReceiver_d( $obj['applyId'] );
	 	$applyObj = $applyDao->get_d( $obj['applyId'] );
	 	$esmprojectDao = new model_engineering_project_esmproject();
		$esmprojectObj = $esmprojectDao->get_d($applyObj['projectId']);
	 	$emailDao = new model_common_mail();
	 	$mailContent = '���ã����ʼ�Ϊ�����������ͨ��֪ͨ����ϸ��Ϣ���£�<br>'.
		'���ݱ�ţ�<span style="color:blue">'.$applyObj['applyCode'].
		'</span><br>��������<span style="color:blue">'.$esmprojectObj['officeName'].
		'</span><br>��Ŀʡ�ݣ�<span style="color:blue">'.$esmprojectObj['province'].
		'</span><br>��Ŀ���ƣ�<span style="color:blue">'.$applyObj['projecttName'].
		'</span><br>��Ŀ��ţ�<span style="color:blue">'.$applyObj['projectCode'].
		'</span><br>�����ˣ�<span style="color:blue">'.$applyObj['createName'].
		'</span><br>����ʱ�䣺<span style="color:blue">'.$applyObj['createTime'].
		'</span><br>';
	 	if($obj['outsourcing']=='HTWBFS-03' || $obj['outsourcing']=='HTWBFS-01') {
	 		$mailContent .= '�����Ӧ�̣�<span style="color:blue">'.$obj['suppName'].
		'</span><br>��ʼ���ڣ�<span style="color:blue">'.$obj['beginDate'].
		'</span><br>�������ڣ�<span style="color:blue">'.$obj['endDate'].
		'</span>';
	 	}else {
			$mailContent .= '��Ա������Ϣ:<br><table border="1px" cellspacing="0px" style="text-align:center"><tr rowspan="2" style="background-color:#fff999"><td rowspan="2" width="50px">����</td><td rowspan="2" width="50px">����</td><td rowspan="2">���������Ӧ��</td><td colspan="3">��������</td><td colspan="4">�۸�Ա�</td><td rowspan="2" width="80px">��������Ҫ��</td><td rowspan="2" width="100px">��ע</td></tr><tr style="background-color:#fff999"><td>���޿�ʼ����</td><td>���޽�������</td><td>����</td><td>���������ɱ�����(Ԫ/��)</td><td>���������ɱ�</td><td>�������(Ԫ/��)</td><td>����۸�</td></tr>';
			$persronRentalDao = new model_outsourcing_approval_persronRental();
			$persronRentalRows = $persronRentalDao->findAll(array('mainId' => $obj['id']));
			if (is_array ($persronRentalRows)) {
				foreach($persronRentalRows as $k => $v) {
					$mailContent .= '<tr><td>'.$v['personLevelName'].'</td><td>'.$v['pesonName'].'</td><td>'.$v['suppName'].'</td><td>'.$v['beginDate'].'</td><td>'.$v['endDate'].'</td><td>'.$v['totalDay'].'</td><td>'.number_format($v['inBudgetPrice'],2).'</td><td>'.number_format($v['selfPrice'],2).'</td><td>'.number_format($v['outBudgetPrice'],2).'</td><td>'.number_format($v['rentalPrice'],2).'</td><td style="text-align:left">'.$v['skillsRequired'].'</td><td style="text-align:left">'.$v['remark'].'</td></tr>';
				}
			}
			$mailContent .= '</table>';
	 	}
	 	$emailDao->mailClear("�������������" ,$receiverId ,$mailContent);
	 }

	 /**
	  * workflow callback
	  */
	  function workflowCallBack($spid){
	  	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		if($folowInfo['examines']=="ok"){  //����ͨ��
			$obj = $this->get_d ( $folowInfo['objId'] );
		 	$this->dealAduit_d ( $folowInfo['objId'] );
	//				$this->service->sendApplyMail_d($obj);
		}
	  }

 }
?>