<?php
/**
 * @author Admin
 * @Date 2014年1月16日 13:36:19
 * @version 1.0
 * @description:外包供应商工作量确认单 Model层
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

			//判断是否已生成工作量确认单主单
			$workVerifyDao=new model_outsourcing_workverify_workVerify();
			$workVerifyRow=$workVerifyDao->checkIsAdd_d($object);
			//获取外包供应商信息
			$basicinfoDao=new model_outsourcing_supplier_basicinfo();
			$basicinfoRow=$basicinfoDao->find(array("suppCode" => $_SESSION ['USER_ID']));


			$object['formCode']='WBGZL'.date ( "YmdHis" );//单据编号
			$object['parentCode']=$workVerifyRow['parentCode'];
			$object['parentId']=$workVerifyRow['parentId'];
			$object['workMonth']=date('Y-m',strtotime($object['endDate']));
			$object['outsourceSuppId']=$basicinfoRow['id'];
			$object['outsourceSuppCode']=$basicinfoRow['suppCode'];
			$object['outsourceSupp']=$basicinfoRow['suppName'];





			//修改主表信息
			$id = parent :: add_d($object, true);

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//整包分包
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
			if(is_array($object['rentList'])){//人员租赁
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
					//更新日志统计状态
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

			//修改主表信息
			$id = parent :: edit_d($object, true);
			//获取外包供应商信息
			$basicinfoDao=new model_outsourcing_supplier_basicinfo();
			$basicinfoRow=$basicinfoDao->find(array("id" => $object ['outsourceSuppId']));
			print_r($basicinfoRow);

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//整包分包
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
			if(is_array($object['rentList'])){//人员租赁
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
						//更新日志统计状态
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
	 * 改变状态
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