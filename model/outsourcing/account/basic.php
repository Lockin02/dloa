<?php
/**
 * @author Administrator
 * @Date 2013年12月15日 星期日 22:23:01
 * @version 1.0
 * @description:外包结算 Model层 合同状态
                            0.未提交
                            1.审批中
                            2.执行中
                            3.已关闭
                            4.变更中
 */
 class model_outsourcing_account_basic  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_account";
		$this->sql_map = "outsourcing/account/basicSql.php";
		parent::__construct ();
	}

		/**新增*/
	function add_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['formCode']="WBJ".date("Ymd").time();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);

			//新增主表信息
			$id = parent :: add_d($object, true);

			$persronRentalDao=new model_outsourcing_account_persron();
			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//人员租赁
				foreach($object['personList'] as $key=>$val){
					$val['mainId']=$id;
					$persronRentalDao->add_d($val);
				}
			}

			//如果是通过外包供应商工作量添加则更新工作量从表的结算状态
			if ($object['suppVerifyId']) {
				$verifyDetailDao = new model_outsourcing_workverify_verifyDetail();
				$verifyDetailId = $verifyDetailDao->get_table_fields('oa_outsourcing_workverify_detail' ,'suppVerifyId='.$object['suppVerifyId'].' AND projectId='.$object['projectId'] ,'id');
				$verifyDetailDao->update(array('id'=>$verifyDetailId) ,array('settlementState'=>1));
			}

			//如果提交确认的话则发邮件通知确认人
			if ($object['state'] == 1) {
				$this->sendMailAffirm_d( $id );
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			return false;
		}
	}

		/**编辑*/
	function edit_d($object){
		try {
			$this->start_d();
			$datadictDao = new model_system_datadict_datadict();
			$object['outsourcingName'] = $datadictDao->getDataNameByCode($object['outsourcing']);
			$object['taxPoint'] = $datadictDao->getDataNameByCode($object['taxPointCode']);
			$object['payTypeName'] = $datadictDao->getDataNameByCode($object['payType']);
			//新增主表信息
			$id = parent :: edit_d($object, true);

			if(is_array($object['personList'])&&$object['outsourcing']=='HTWBFS-02'){//人员租赁
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
	 * 删除对象
	 */
	function deletes_d($id) {
		try {
			$obj = $this->get_d($id);
			//修改工作量明细表的结算状态
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
	 * 发邮件通知确认人确认
	 */
	function sendMailAffirm_d ( $id ) {
		$obj = $this->get_d( $id );
		include (WEB_TOR."model/common/mailConfig.php");
		$emailDao = new model_common_mail();
		$receiverId = $mailUser['oa_outsourcing_account']['TO_ID'];
		$mailContent = '<span style="color:blue">'.$obj['createName']
						.'</span>提交了单据编号为<span style="color:blue">'.$obj['formCode']
						.'</span>外包结算，请登录系统进行确认！';
		$emailDao->mailGeneral("外包结算确认" ,$receiverId ,$mailContent);
	}
 }
?>