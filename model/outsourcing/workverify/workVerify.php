<?php
/**
 * @author Administrator
 * @Date 2013年9月24日 星期二 16:05:37
 * @version 1.0
 * @description:工作量确认单 Model层
 */
 class model_outsourcing_workverify_workVerify  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_outsourcing_workverify";
		$this->sql_map = "outsourcing/workverify/workVerifySql.php";
		parent::__construct ();
	}

	//更新日志ID
	function updateLogId($ids){
		$sql='update oa_esm_worklog set isCount=1 where id in('.$ids.')';
		$this->query($sql);
	}

	//更新日志ID
	function updateLogIdNo($ids){
		$sql='update oa_esm_worklog set isCount=0 where id in('.$ids.')';
		$this->query($sql);
	}

	//验证是否已生成该月记录
	function checkIsAdd_d($object){
		//查询是否存在对应周报
		$this->searchArr = array ('endDateCheck' => date('Y-m',strtotime($object['endDate'])));
		$rows= $this->listBySqlId ( "select_default" );
		if(is_array($rows['0'])){//如果已存在记录，刚返回ID
			$returnArr=array('parentId'=>$rows['0']['id'],'parentCode'=>$rows['0']['formCode']);
		}else{
			$workArr['formCode']='GZLQR'.date ( "YmdHis" );//单据编号
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

			$object['formCode']='GZLQR'.date ( "YmdHis" );//单据编号

			//修改主表信息
			$id = parent :: add_d($object, true);

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//整包分包
				foreach($object['wholeList'] as $key=>$val){
					$val['parentCode']=$object['formCode'];
					$val['parentId']=$id;
					$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
					$verifyDetailDao->add_d($val);
				}

			}
			if(is_array($object['rentList'])){//人员租赁
				foreach($object['rentList'] as $key=>$val){
					$val['parentCode']=$object['formCode'];
					$val['parentId']=$id;
					$val['outsourcing']='HTWBFS-02';
					$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
					$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
					$verifyDetailDao->add_d($val);
					//更新日志统计状态
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

			//修改主表信息
			$id = parent :: edit_d($object, true);

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
//			$verifyDetailDao->delete(array ('parentId' =>$object['id']));
			if(is_array($object['wholeList'])){//整包分包
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
			if(is_array($object['rentList'])){//人员租赁
				foreach($object['rentList'] as $key=>$val){
					if($val['id']==''){
						$val['parentCode']=$object['formCode'];
						$val['parentId']=$object['id'];;
						$val['outsourcing']='HTWBFS-02';
						$val['outsourcingName'] = $datadictDao->getDataNameByCode($val['outsourcing']);
						$val['personnelTypeName'] = $datadictDao->getDataNameByCode($val['personnelType']);
						$verifyDetailDao->add_d($val);
					}else if($val['id']>0&&$val['isDelTag']==1){
						//更新日志统计状态
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
	 * 改变状态
	 *
	 */
	function deliverEdit_d($object){
		try {
			$this->start_d();

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			if(is_array($object['wholeList'])){//整包分包
				foreach($object['wholeList'] as $key=>$val){
					if(!empty($val['feeTotal'])&&$val['feeTotal']>0){
						$val['purchAuditDate']=date("Y-m-d");
						$val['purchAuditState']='1';
						$verifyDetailDao->edit_d($val);
					}
				}

			}
			if(is_array($object['rentList'])){//人员租赁
				foreach($object['rentList'] as $rkey=>$rval){
					if(!empty($rval['feeTotal'])&&$rval['feeTotal']>0){
						$val['purchAuditDate']=date("Y-m-d");
						$rval['purchAuditState']='1';
						$verifyDetailDao->edit_d($rval);
					}
				}

			}

			//是否已全部审批完成，是则更新确认单的状态为审批完成
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
	 * 编辑后修改
	 *
	 */
	function auditEdit_d($object){
		try {
			$this->start_d();

			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$datadictDao = new model_system_datadict_datadict();
			if(is_array($object['wholeList'])){//整包分包
				foreach($object['wholeList'] as $key=>$val){
						$verifyDetailDao->edit_d($val);
				}

			}
			if(is_array($object['rentList'])){//人员租赁
				foreach($object['rentList'] as $rkey=>$rval){
						if($rval['id']==''){
							$rval['parentCode']=$object['formCode'];
							$rval['parentId']=$object['id'];;
							$rval['outsourcing']='HTWBFS-02';
							$rval['outsourcingName'] = $datadictDao->getDataNameByCode($rval['outsourcing']);
							$rval['personnelTypeName'] = $datadictDao->getDataNameByCode($rval['personnelType']);
							$verifyDetailDao->add_d($rval);
						}else if($rval['id']>0&&$rval['isDelTag']==1){
							//更新日志统计状态
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

		/**
	 * 批量删除对象
	 */
	function deletes_d($ids) {
		try {
			$verifyDetailDao=new model_outsourcing_workverify_verifyDetail();
			$detailRows=$verifyDetailDao->getListByParentId($ids);
			$this->deletes ( $ids );
			if(is_array($detailRows)){
				foreach($detailRows as $rkey=>$rval){
					//更新日志统计状态
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