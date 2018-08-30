<?php
/**
 * @author Administrator
 * @Date 2013��9��24�� ���ڶ� 16:05:37
 * @version 1.0
 * @description:������ȷ�ϵ� Model��
 */
 class model_outsourcing_workverify_workVerify  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_workverify";
		$this->sql_map = "outsourcing/workverify/workVerifySql.php";
		parent::__construct ();
	}

	//������־ID
	function updateLogId($ids){
		$sql='update oa_esm_worklog set isCount=1 where id in('.$ids.')';
		$this->query($sql);
	}

	//������־ID
	function updateLogIdNo($ids){
		$sql='update oa_esm_worklog set isCount=0 where id in('.$ids.')';
		$this->query($sql);
	}

	//��֤�Ƿ������ɸ��¼�¼
	function checkIsAdd_d($object){
		//��ѯ�Ƿ���ڶ�Ӧ�ܱ�
		$this->searchArr = array ('endDateCheck' => date('Y-m',strtotime($object['endDate'])));
		$rows= $this->listBySqlId ( "select_default" );
		if(is_array($rows['0'])){//����Ѵ��ڼ�¼���շ���ID
			$returnArr=array('parentId'=>$rows['0']['id'],'parentCode'=>$rows['0']['formCode']);
		}else{
			$workArr['formCode']='GZLQR'.date ( "YmdHis" );//���ݱ��
			$workArr['formDate']=$object['formDate'];
			$workArr['workMonth']=date('Y-m',strtotime($object['endDate']));
			$workArr['remark']=$object['remark'];
			$workArr['status']=1;
			$id = parent :: add_d($workArr, true);
			$returnArr=array('parentId'=>$id,'parentCode'=>$workArr['formCode']);
		}
		return $returnArr;

	}

	function add_d($object){
		try {
			$this->start_d();

			$object['formCode']='GZLQR'.date ( "YmdHis" );//���ݱ��

			//�޸�������Ϣ
			$id = parent :: add_d($object, true);

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//�����ְ�
				foreach($object['wholeList'] as $key=>$val){
					$val['parentCode']=$object['formCode'];
					$val['parentId']=$id;
					$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
					$verifyDetailDao->add_d($val);
				}

			}
			if(is_array($object['rentList'])){//��Ա����
				foreach($object['rentList'] as $key=>$val){
					$val['parentCode']=$object['formCode'];
					$val['parentId']=$id;
					$val['outsourcing']='HTWBFS-02';
					$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
					$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
					$verifyDetailDao->add_d($val);
					//������־ͳ��״̬
					if(isset($val['ids'])&&!empty($val['ids'])){
						$this->updateLogId($val['ids']);
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

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
//			$verifyDetailDao->delete(array ('parentId' =>$object['id']));
			if(is_array($object['wholeList'])){//�����ְ�
				foreach($object['wholeList'] as $key=>$val){
					if($val['id']==''){
						$val['parentCode']=$object['formCode'];
						$val['parentId']=$object['id'];
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
						$val['parentCode']=$object['formCode'];
						$val['parentId']=$object['id'];;
						$val['outsourcing']='HTWBFS-02';
						$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
						$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
						$verifyDetailDao->add_d($val);
					}else if($val['id']>0&&$val['isDelTag']==1){
						//������־ͳ��״̬
						if(isset($val['ids'])&&!empty($val['ids'])){
							$this->updateLogIdNo($val['ids']);
						}
						$verifyDetailDao->deletes_d($val['id']);
					}else if($val['id']>0){
						$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
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
	function deliverEdit_d($object){
		try {
			$this->start_d();

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			if(is_array($object['wholeList'])){//�����ְ�
				foreach($object['wholeList'] as $key=>$val){
					if(!empty($val['feeTotal'])&&$val['feeTotal']>0){
						$val['purchAuditDate']=date("Y-m-d");
						$val['purchAuditState']='1';
						$verifyDetailDao->edit_d($val);
					}
				}

			}
			if(is_array($object['rentList'])){//��Ա����
				foreach($object['rentList'] as $rkey=>$rval){
					if(!empty($rval['feeTotal'])&&$rval['feeTotal']>0){
						$val['purchAuditDate']=date("Y-m-d");
						$rval['purchAuditState']='1';
						$verifyDetailDao->edit_d($rval);
					}
				}

			}

			//�Ƿ���ȫ��������ɣ��������ȷ�ϵ���״̬Ϊ�������
			if($verifyDetailDao->findCount("purchAuditState<>'1' and parentId=".$object['id']." ")=="0"){
		     	$this->updateById(array("id"=>$object['id'],"status"=>"3"));
			}
			$this->commit_d();
			return true;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * �༭���޸�
	 *
	 */
	function auditEdit_d($object){
		try {
			$this->start_d();

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//�����ְ�
				foreach($object['wholeList'] as $key=>$val){
						$verifyDetailDao->edit_d($val);
				}

			}
			if(is_array($object['rentList'])){//��Ա����
				foreach($object['rentList'] as $rkey=>$rval){
						if($rval['id']==''){
							$rval['parentCode']=$object['formCode'];
							$rval['parentId']=$object['id'];;
							$rval['outsourcing']='HTWBFS-02';
							$rval['outsourcingName'] = $datadictDao->getDataNameByCode($rval['outsourcing']);
							$rval['personnelTypeName'] = $datadictDao->getDataNameByCode($rval['personnelType']);
							$verifyDetailDao->add_d($rval);
						}else if($rval['id']>0&&$rval['isDelTag']==1){
							//������־ͳ��״̬
							if(isset($val['ids'])&&!empty($val['ids'])){
								$this->updateLogIdNo($val['ids']);
							}
							$verifyDetailDao->deletes_d($rval['id']);
						}else if($rval['id']>0){
							$rval['personnelTypeName'] = $datadictDao->getDataNameByCode($rval['personnelType']);
							$verifyDetailDao->edit_d($rval);
						}
				}

			}
			$this->commit_d();
			return true;
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

		/**
	 * ����ɾ������
	 */
	function deletes_d($ids) {
		try {
			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$detailRows=$verifyDetailDao->getListByParentId($ids);
			$this->deletes ( $ids );
			if(is_array($detailRows)){
				foreach($detailRows as $rkey=>$rval){
					//������־ͳ��״̬
					if(isset($rval['ids'])&&!empty($rval['ids'])){
						$this->updateLogIdNo($rval['ids']);
					}
					$verifyDetailDao->deletes_d($rval['id']);
				}
			}
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}
 }
?>