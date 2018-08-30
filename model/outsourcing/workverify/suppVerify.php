<?php
/**
 * @author Admin
 * @Date 2014��1��16�� 13:36:19
 * @version 1.0
 * @description:�����Ӧ�̹�����ȷ�ϵ� Model��
 */
 class model_outsourcing_workverify_suppVerify  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_suppverify";
		$this->sql_map = "outsourcing/workverify/suppVerifySql.php";
		parent::__construct ();
	}


	/**
	 * @author Admin
	 *
	 */
	function add_d($object){
		try {
			$this->start_d();

			//�ж��Ƿ������ɹ�����ȷ�ϵ�����
			$workVerifyDao=new model_outsourcing_workverify_workVerify();
			$workVerifyRow=$workVerifyDao->checkIsAdd_d($object);
			//��ȡ�����Ӧ����Ϣ
			$basicinfoDao=new model_outsourcing_supplier_basicinfo();
			$basicinfoRow=$basicinfoDao->find(array("suppCode" => $_SESSION ['USER_ID']));


			$object['formCode']='WBGZL'.date ( "YmdHis" );//���ݱ��
			$object['parentCode']=$workVerifyRow['parentCode'];
			$object['parentId']=$workVerifyRow['parentId'];
			$object['workMonth']=date('Y-m',strtotime($object['endDate']));
			$object['outsourceSuppId']=$basicinfoRow['id'];
			$object['outsourceSuppCode']=$basicinfoRow['suppCode'];
			$object['outsourceSupp']=$basicinfoRow['suppName'];





			//�޸�������Ϣ
			$id = parent :: add_d($object, true);

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//�����ְ�
				foreach($object['wholeList'] as $key=>$val){
					$val['parentCode']=$workVerifyRow['parentCode'];
					$val['parentId']=$workVerifyRow['parentId'];
					$val['suppVerifyCode']=$object['formCode'];
					$val['suppVerifyId']=$id;
					$val['outsourceSuppId']=$basicinfoRow['id'];
					$val['outsourceSuppCode']=$basicinfoRow['suppCode'];
					$val['outsourceSupp']=$basicinfoRow['suppName'];
					$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
					$verifyDetailDao->add_d($val);
				}

			}
			if(is_array($object['rentList'])){//��Ա����
				foreach($object['rentList'] as $key=>$val){
					$val['parentCode']=$workVerifyRow['parentCode'];
					$val['parentId']=$workVerifyRow['parentId'];
					$val['suppVerifyCode']=$object['formCode'];
					$val['suppVerifyId']=$id;
					$val['outsourceSuppId']=$basicinfoRow['id'];
					$val['outsourceSuppCode']=$basicinfoRow['suppCode'];
					$val['outsourceSupp']=$basicinfoRow['suppName'];
					$val['outsourcing']='HTWBFS-02';
					$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
					$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
					$verifyDetailDao->add_d($val);
					//������־ͳ��״̬
					if(isset($val['ids'])&&!empty($val['ids'])){
						$workVerifyDao->updateLogId($val['ids']);
					}
				}

			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	function edit_d($object){
		try {
			$this->start_d();

			//�޸�������Ϣ
			$id = parent :: edit_d($object, true);
			//��ȡ�����Ӧ����Ϣ
			$basicinfoDao=new model_outsourcing_supplier_basicinfo();
			$basicinfoRow=$basicinfoDao->find(array("id" => $object ['outsourceSuppId']));
			print_r($basicinfoRow);

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//�����ְ�
				foreach($object['wholeList'] as $key=>$val){
					if($val['id']==''){
						$val['parentCode']=$object['formCode'];
						$val['parentId']=$object['id'];
						$val['outsourceSuppId']=$basicinfoRow['id'];
						$val['outsourceSuppCode']=$basicinfoRow['suppCode'];
						$val['outsourceSupp']=$basicinfoRow['suppName'];
						$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
						$verifyDetailDao->add_d($val);
					}else if($val['id']>0&&$val['isDelTag']==1){
						$verifyDetailDao->deletes_d($val['id']);
					}else if($val['id']>0){
						$verifyDetailDao->edit_d($val);
					}
				}

			}
			if(is_array($object['rentList'])){//��Ա����
				foreach($object['rentList'] as $key=>$val){
					if($val['id']==''){
						$val['parentCode']=$object['parentCode'];
						$val['parentId']=$object['parentId'];;
						$val['suppVerifyCode']=$object['formCode'];
						$val['suppVerifyId']=$object['id'];
						$val['outsourceSuppId']=$basicinfoRow['id'];
						$val['outsourceSuppCode']=$basicinfoRow['suppCode'];
						$val['outsourceSupp']=$basicinfoRow['suppName'];
						$val['outsourcing']='HTWBFS-02';
						$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
//						$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
						$verifyDetailDao->add_d($val);
					}else if($val['id']>0&&$val['isDelTag']==1){
						//������־ͳ��״̬
						if(isset($val['ids'])&&!empty($val['ids'])){
							$this->updateLogIdNo($val['ids']);
						}
						$verifyDetailDao->deletes_d($val['id']);
					}else if($val['id']>0){
//						$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
						$verifyDetailDao->edit_d($val);
					}
				}

			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * �ı�״̬
	 *
	 */
	function changeState_d($object){
		try{
			$this->start_d();
			$id=parent::edit_d($object,true);
			$this->commit_d();
			return $id;
		}catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}
 }
?>