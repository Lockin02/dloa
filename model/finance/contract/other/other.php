<?php
/**
 * @author Show
 * @Date 2011年12月5日 星期一 10:19:51
 * @version 1.0
 * @description:其他合同 Model层
 */
class model_contract_other_other extends model_base {

	function __construct() {
		$this->tbl_name = "oa_sale_other";
		$this->sql_map = "contract/other/otherSql.php";
		parent::__construct ();
    }

    //数据字典
    public $datadictFieldArr = array('projectType','fundType');

	//策略类配置
    private $relatedStrategyArr = array (
		'KXXZA' => 'model_finance_income_strategy_income', //到款单
		'KXXZB' => 'model_finance_income_strategy_prepayment', //预收款
		'KXXZC' => 'model_finance_income_strategy_refund' //退款单
	);

	private $relatedCode = array (
		'KXXZA' => 'income', //到款单
		'KXXZB' => 'pay', //预收款
		'KXXZC' => 'none' //退款单
	);

	/**
	 * 根据类型返回业务名称
	 */
	public function getBusinessCode($objType){
		return $this->relatedCode[$objType];
	}

	/**
	 * 根据数据类型返回类
	 */
	public function getClass($objType){
		return $this->relatedStrategyArr[$objType];
	}

    //是否
    function rtYesOrNo_d($value){
		if($value == 1){
			return '是';
		}else{
			return '否';
		}
    }

    //签收状态
    function rtIsSign_d($value){
		if($value == 1){
			return '已签收';
		}else{
			return '未签收';
		}
    }

	//合同状态
	private $statusArr = array("未提交","审批中","执行中","已关闭","变更中");

    /**
	 * 添加对象
	 */
	function add_d($object) {

		//业务前签约单位信息处理
		$signCompanyDao = new model_contract_signcompany_signcompany();
		$signCompanyArr = array(
			'signCompanyName' => $object['signCompanyName'],
			'proName' => $object['proName'],
			'proCode' => $object['proCode'],
			'phone' => $object['phone'],
			'address' => $object['address'],
			'linkman' => $object['linkman']
		);
		$signCompanyDao->saveCompanyInfo_d($signCompanyArr);

//		echo "<pre>";
//		print_r($object);
		//获取付款申请信息
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);

		try{
			$this->start_d();//开启事务

			//业务编号生成部分
			$deptDao = new model_deptuser_dept_dept();
			$dept = $deptDao->getDeptByUserId($object['principalId']);

			$orderCodeDao = new model_common_codeRule ();
			$object['objCode']=$orderCodeDao->getObjCode($this->tbl_name."_objCode",$dept['Code']);

			if(ORDERCODE_INPUT == 1){
				$object['orderCode'] = $object['objCode'];
			}

			$object['ExaStatus'] = WAITAUDIT;
			$object['status'] = 0;

			if(!empty($object['fundType'])){
				//处理数据字典字段
				$datadictDao = new model_system_datadict_datadict ();
				$object ['fundTypeName'] =  $datadictDao->getDataNameByCode ( $object['fundType'] );
			}

			//数据字典处理
			$object = $this->processDatadict($object);

			//调用父类
			$newId = parent :: add_d($object,true);

			if($object['isNeedPayapply']){
				//插入付款申请信息
				$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
				$payapplyInfo['contractId'] = $newId;
				$payapplyInfo['contractType'] = $this->tbl_name;
				$payapplyInfoDao->dealInfo_d($payapplyInfo);
			}

			//更新附件关联关系
			$this->updateObjWithFile($newId,$object['orderCode']);

			$this->commit_d();
			return $newId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 重写修改方法
	 */
	function edit_d($object){

		if(!empty($object['fundType'])){
			//处理数据字典字段
			$object = $this->processDatadict($object);
		}
		return parent::edit_d($object,true);
	}

	/**
	 * 编辑方法
	 */
	function editInfo_d($object){
//		echo "<pre>";
//		print_r($object);
		//获取付款申请信息
		$payapplyInfo = $object['payapply'];
		unset($object['payapply']);
		try{
			$this->start_d();

			//外包状态字段数据字典处理
			if(isset($object['fundType']) && !empty($object['fundType'])){
				//处理数据字典字段
				$object = $this->processDatadict($object);
			}

			$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
			//付款信息处理
			if($object['isNeedPayapply'] == 1){
				//更新付款申请信息
				$payapplyInfo['contractId'] = $object['id'];
				$payapplyInfo['contractType'] = $this->tbl_name;
				$payapplyInfoDao->dealInfo_d($payapplyInfo);
			}else{
				$object['isNeedPayapply'] = 0;
				//删除付款申请信息
				$payapplyInfoDao->delete(array('contractId' =>$object['id'],'contractType'=> $this->tbl_name));
			}

			parent::edit_d($object,true);

			$this->commit_d();
			return $object['id'];
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 获取合同信息及付款信息
	 */
	function getInfo_d($id){
		$obj = parent::get_d($id);

		$payapplyInfoDao = new model_contract_otherpayapply_otherpayapply();
		$payapplyArr = $payapplyInfoDao->getPayapplyInfo_d($id,$this->tbl_name);

		$obj['otherFeeDeptName'] = $obj['feeDeptName'];
		$obj['otherFeeDeptId'] = $obj['feeDeptId'];

		$obj = array_merge($obj,$payapplyArr);

		return $obj;
	}

	/**
	  * 新增申请盖章信息
	  */
	function stamp_d($obj){
		$stampDao = new model_contract_stamp_stamp();
		try{
			$this->start_d();

			//获取对应对象的最大批次号
			$maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name," contractType = 'HTGZYD-02' and contractId=". $obj['contractId'],"max(batchNo)");
			$obj['batchNo'] = $maxBatchNo + 1;

			//新增盖章信息
			$obj['contractType'] = 'HTGZYD-02';
			$stampDao->addStamps_d($obj,true);

			//更新合同字段信息
			$this->edit_d(array('id' => $obj['contractId'],'isNeedStamp' => 1,'stampType' => $obj['stampType']));

			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 *审批成功后在盖章列表添加信息
	 */
	function dealAfterAudit_d($objId,$userId){
	 	$object = $this->get_d($objId);
	 	if($object['isNeedStamp'] == "1" &&$object['ExaStatus'] == AUDITED){
	 		if($userId == $object['createId']){
	 			$userName = $object['createName'];
	 		}else{
	 			$userName = $object['principalName'];
	 		}
	 		//创建数组
			$object = array (
				"contractId" => $object['id'],
				"contractCode" => ($object['orderCode']?$object['orderCode']:$object['orderTempCode']),
				"contractType" => 'HTGZYD-02',
				"objCode" => $object['objCode'],
				"contractName" => $object['orderName'],
				"signCompanyName" => $object['signCompanyName'],
				"signCompanyId" => $object['signCompanyId'],
				"contractMoney" => $object['orderMoney'],
				"applyUserId" => $userId,
				"applyUserName" => $userName,
				"applyDate" => day_date,
				"stampType" => $object['stampType'] ,
				"status" => 0
			);
			$stampDao = new model_contract_stamp_stamp();
			$stampDao->addStamps_d($object,true);
			return 1;
	 	}
	 	return 1;
	}

	/**
	 *其他合同立项付款申请审批成功后处理
	 */
	function dealAfterAuditPayapply_d($spid){
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getStepInfo ($spid);
		$objId = $folowInfo ['objId'];
		$userId = $folowInfo['Enter_user'];

		$object = $this->getInfo_d($objId);
	 	if($object['ExaStatus'] == AUDITED){

			try{
				$this->start_d();

				//主要责任人设定
		 		if($userId == $object['createId']){
		 			$userName = $object['createName'];
		 		}else{
		 			$userName = $object['principalName'];
		 		}

				//如果需要盖章
		 		if($object['isNeedStamp'] == "1"){
			 		//创建数组
					$stampArr = array (
						"contractId" => $object['id'],
						"contractCode" => ($object['orderCode']?$object['orderCode']:$object['orderTempCode']),
						"contractType" => 'HTGZYD-02',
						"objCode" => $object['objCode'],
						"contractName" => $object['orderName'],
						"signCompanyName" => $object['signCompanyName'],
						"signCompanyId" => $object['signCompanyId'],
						"contractMoney" => $object['orderMoney'],
						"applyUserId" => $userId,
						"applyUserName" => $userName,
						"applyDate" => day_date,
						"stampType" => $object['stampType'] ,
						"status" => 0
					);
					$stampDao = new model_contract_stamp_stamp();
					$stampDao->addStamps_d($stampArr,true);
		 		}

				//付款申请处理
				$payablesapplyDao = new model_finance_payablesapply_payablesapply();
				//构建付款申请数组
				$payablesapplyArr = array(
					'deptName' => $object['deptName'],
					'deptId' => $object['deptId'],
					'salesman' => $userName,
					'salesmanId' => $userId,
					'supplierName' => $object['signCompanyName'],
					'payMoney' => $object['applyMoney'],
					'payDate' => $object['formDate'],
					'formDate' => day_date,
					'feeDeptName' => $object['feeDeptName'],
					'feeDeptId' => $object['feeDeptId'],
					'bank' => $object['bank'],
					'account' => $object['account'],
					'payFor' => $object['payFor'],
					'payType' => $object['payType'],
					'remark' => $object['remark'],
					'payCondition' => $object['payCondition'],
					'sourceType' => 'YFRK-02',
					'ExaStatus' => '完成',
					'ExaDT' => day_date,
					'exaId' => $object['id'],
					'exaCode' => $this->tbl_name,
					'ExaUser' => $folowInfo['USER_NAME'],
					'ExaUserId' => $folowInfo['USER_ID'],
					'ExaContent' => $folowInfo['content'],
					'detail' => array(
						0 => array(
							'money' => $object['applyMoney'],
							'objId' => $object['id'],
							'objCode' => $object['orderCode'],
							'objType' => 'YFRK-02',
							'purchaseMoney' => $object['orderMoney'],
							'payDesc' => '其他合同立项付款审批单据'
						)
					)
				);
				$payablesapplyArr ['createId'] = $payablesapplyArr ['updateId'] = $userId;
				$payablesapplyArr ['createName'] = $payablesapplyArr ['updateName'] = $userName;
				$payablesapplyArr ['createTime'] = $payablesapplyArr ['updateTime'] =date ( "Y-m-d H:i:s" );

				$payablesapplyDao->addOnly_d($payablesapplyArr);

				$this->commit_d();
			}catch(exception $e){
				$this->rollBack();
			}

			return 1;
	 	}
	 	return 1;
	}

	/**
	 * 单页小计处理
	 */
	function pageCount_d($object){
		if(is_array($object)){
			$newArr = array(
				'payApplyMoney' => 0,'payedMoney'=>0,'invotherMoney'=>0 ,
				'applyInvoice' => 0,'invoiceMoney'=>0,'incomeMoney'=>0 ,
				'orderMoney' => 0
			);
			foreach($object as $key => $val){
				$newArr['payApplyMoney'] = bcadd($newArr['payApplyMoney'],$val['payApplyMoney'],2);
				$newArr['payedMoney'] = bcadd($newArr['payedMoney'],$val['payedMoney'],2);
				$newArr['invotherMoney'] = bcadd($newArr['invotherMoney'],$val['invotherMoney'],2);
				$newArr['applyInvoice'] = bcadd($newArr['applyInvoice'],$val['applyInvoice'],2);
				$newArr['invoiceMoney'] = bcadd($newArr['invoiceMoney'],$val['invoiceMoney'],2);
				$newArr['incomeMoney'] = bcadd($newArr['incomeMoney'],$val['incomeMoney'],2);
				$newArr['orderMoney'] = bcadd($newArr['orderMoney'],$val['orderMoney'],2);
			}
			$newArr['createDate'] = '本页小计';
			$newArr['id'] = 'noId';
			$object[] = $newArr;
			return $object;
		}
	}

	/***************** S 变更系列 *********************/
	/**
	 * 变更操作
	 */
	function change_d($object){
		try{
			$this->start_d();

			//实例化变更类
			$changeLogDao = new model_common_changeLog ( 'other' );

			//附件处理
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//建立变更信息
			$tempObjId = $changeLogDao->addLog ( $object );

			$this->commit_d();
			return $tempObjId;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 变更审批完成后更新合同状态
	 */
	function dealAfterAuditChange_d($objId,$userId){
		$obj = $this->get_d($objId);
	 	if($obj['ExaStatus'] == AUDITED){
	 		try{
	 			$this->start_d();

		 		$changeLogDao = new model_common_changeLog ( 'other' );
				$changeLogDao->confirmChange_d ( $obj );

				//源单状态处理
				if($obj['isNeedRestamp'] == 1 && $obj['isStamp'] == 1){//需要重新盖章
					//直接重置盖章状态位，将现有盖章记录关闭
					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

				}elseif($obj['isNeedStamp'] == 1 && $obj['isStamp'] == 0){//正在盖章的处理

					$this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'isStamp' => 0,'isNeedRestamp' => 0,'isNeedStamp' =>0,'stampType' =>''));

					$stampDao = new model_contract_stamp_stamp();
					$newId = $stampDao->closeWaiting_d($obj['originalId'],'HTGZYD-02');
				}else{//非盖章处理
					$this->update(array('id'=>$obj['originalId']),array('status' => 2,'isNeedRestamp' => 0));
				}

	 			$this->commit_d();
	 			return 1;
	 		}catch(exception $e){
	 			$this->rollBack();
	 			return 1;
	 		}
	 	}else{
            try{
                $this->start_d();

                $this->update(array('id'=>$obj['originalId']),array('status' => 2 ,'ExaStatus' => '完成'));

                $this->commit_d();
                return 1;
            }catch(exception $e){
                $this->rollBack();
                return 1;
            }
	 	}
	 	return 1;
	}
	/***************** E 变更系列 *********************/

	/***************** S 签收系列 *********************/
	/**
	 * 合同签收 - 签收功能
	 */
	function sign_d($object){
		//实例化变更类
		$changeLogDao = new model_common_changeLog ( 'otherSign' );
 		try{
 			$this->start_d();

 			//原来签收状态处理
 			$signInfo = array(
				'signedDate' => day_date,
				'signedStatus' => 1,
				'signedMan' => $_SESSION['USERNAME'],
				'signedManId' => $_SESSION['USER_ID'],
				'id' => $object['oldId']
 			);
 			parent::edit_d($signInfo,true);

			//数据处理
			$object = $this->processDatadict($object);

			//附件处理
			$object['uploadFiles'] = $changeLogDao->processUploadFile ( $object, $this->tbl_name );

			//建立变更信息
			$tempObjId = $changeLogDao->addLog ( $object );

			$changeObj = $object;
			$changeObj['id'] = $tempObjId;
			$changeObj['originalId'] = $changeObj['oldId'];

			//变更确认
			$changeLogDao->confirmChange_d ( $changeObj );

 			$this->commit_d();
 			return $tempObjId;
 		}catch(exception $e){
 			$this->rollBack();
 			return false;
 		}
	}

	/***************** E 签收系列 *********************/
}
?>