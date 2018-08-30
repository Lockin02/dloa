<?php
/**
 * @author Administrator
 * @Date 2013��12��15�� ������ 22:23:01
 * @version 1.0
 * @description:������� Model�� ��ͬ״̬
                            0.δ�ύ
                            1.������
                            2.ִ����
                            3.�ѹر�
                            4.�����
 */
 class model_outsourcing_account_basic  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_account";
		$this->sql_map = "outsourcing/account/basicSql.php";
		parent::__construct ();
	}

		/**����*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['formCode']="WBJ".date("Ymd").time();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);

			//����������Ϣ
			$id = parent :: add_d($object, true);

			$persronRentalDao=new model_outsourcing_account_persron();
			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//��Ա����
				foreach($object['personList'] as $key=>$val){
					$val['mainId']=$id;
					$persronRentalDao->add_d($val);
				}
			}

			//�����ͨ�������Ӧ�̹������������¹������ӱ�Ľ���״̬
			if ($object['suppVerifyId']) {
				$verifyDetailDao = new model_outsourcing_workverify_verifyDetail();
				$verifyDetailId = $verifyDetailDao->get_table_fields('oa_outsourcing_workverify_detail' ,'suppVerifyId='.$object['suppVerifyId'].' AND projectId='.$object['projectId'] ,'id');
				$verifyDetailDao->update(array('id'=>$verifyDetailId) ,array('settlementState'=>1));
			}

			//����ύȷ�ϵĻ����ʼ�֪ͨȷ����
			if ($object['state'] == 1) {
				$this->sendMailAffirm_d( $id );
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
			//����������Ϣ
			$id = parent :: edit_d($object, true);

			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//��Ա����
				$persronRentalDao=new model_outsourcing_account_persron();
				$persronRentalDao->delete(array ('mainId' =>$object['id']));
				foreach($object['personList'] as $key=>$val){
					$val['mainId']=$object['id'];
					$persronRentalDao->add_d($val);
				}
			}

			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

	/**
	 * ɾ������
	 */
	function deletes_d($id) {
		try {
			$obj = $this->get_d($id);
			//�޸Ĺ�������ϸ��Ľ���״̬
			$verifyDetailDao = new model_outsourcing_workverify_verifyDetail();
			$verifyDetailDao->update(array('suppVerifyId'=>$obj['suppVerifyId'] ,'projectId'=>$obj['projectId']) ,array('settlementState'=>0));
			$this->deletes ( $id );
			return true;
		} catch ( Exception $e ) {
			throw $e;
			return false;
		}
	}

	/**
	 * ���ʼ�֪ͨȷ����ȷ��
	 */
	function sendMailAffirm_d ( $id ) {
		$obj = $this->get_d( $id );
		include (WEB_TOR."model/common/mailConfig.php");
		$emailDao = new model_common_mail();
		$receiverId = $mailUser['oa_outsourcing_account']['TO_ID'];
		$mailContent = '<span style="color:blue">'.$obj['createName']
						.'</span>�ύ�˵��ݱ��Ϊ<span style="color:blue">'.$obj['formCode']
						.'</span>������㣬���¼ϵͳ����ȷ�ϣ�';
		$emailDao->mailGeneral("�������ȷ��" ,$receiverId ,$mailContent);
	}
 }
?>