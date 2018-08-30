<?php
/**
 * @author Administrator
 * @Date 2012年5月11日 11:41:37
 * @version 1.0
 * @description:资产需求申请 Model层
 */
 class model_asset_require_requirement  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requirement";
		$this->sql_map = "asset/require/requirementSql.php";
		parent::__construct ();
	}

   /*--------------------------------------------业务操作--------------------------------------------*/

   /**
	 * 新增保存
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			$codeDao = new model_common_codeRule ();
			foreach( $object as $key => $val){
				if($val==''){
					unset($object[$key]);
				}
			}
			if (is_array ( $object ['items'] )) {
		       	$sql = "SELECT MAX(applyDate) as applyDate from oa_asset_requirement";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = $applyDateArr['applyDate'];
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requirement", "XQ" ,$thisDate,$object['userCompanyCode'],'固定资产需求申请单',true);
		       	}else{
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requirement", "XQ" ,$thisDate,$object['userCompanyCode'],'固定资产需求申请单',false);
		       	}
//		       	$object['ExaStatus']="待提交";
		       	$object['DeliveryStatus']="WFH";
				$id = parent::add_d ( $object, true );
				$object['id']=$id;
				$requireitemDao = new model_asset_require_requireitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id , $object ['items'] );
				$itemsObj = $requireitemDao->saveDelBatch ( $itemsArr );
				//状态为提交，则发送邮件
				if($object['isSubmit'] == 1 && $object['mailInfo']['issend'] == 'y'){
					$this->mailDeal_d('requirement',$object['mailInfo']['TO_ID'],array(id=>$id));
				}
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * 修改保存
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$editResult = parent::edit_d ( $object, true );
				$requireitemDao = new model_asset_require_requireitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $requireitemDao->saveDelBatch ( $itemsArr );
				//状态为提交，则发送邮件
				if($object['isSubmit'] == 1){
					$this->mailDeal_d('requirement',$object['mailInfo']['TO_ID'],array(id=>$object ['id']));
				}
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
			$this->commit_d ();
			return $object ['id'];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

   /**
	 * 通过id获取详细信息
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
   	$requireitemDao = new model_asset_require_requireitem();
		$requireitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $requireitemDao->listBySqlId ();
		return $object;
	}

	/**
	 * 根据id更新需求的发货状态
	 */
	 function updateOutStatus($id){
	 	$sql = "select count(0) as countNum,(select sum(o.executedNum) from oa_asset_requireitem o where o.mainId=".$id." )
	 			as executeNum from (select e.mainId,(e.number-e.executedNum) as remainNum from oa_asset_requireitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
		$remainNum = $this->_db->getArray( $sql );
		$DeliveryStatus = '';
	 	if( $remainNum[0]['countNum'] <= 0 ){//已发货
	 		$DeliveryStatus='YFH';
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['executeNum']==0 ){//未发货
	 		$DeliveryStatus='WFH';
		} else {//部分发货
	 		$DeliveryStatus='BFFH';
	 	}
	 	$statusInfo = array(
	 		'id' => $id,
	 		'DeliveryStatus' => $DeliveryStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }

	 /**
	  * 审批通过后邮件通知
	  */
	 function dealAfterAudit_d($id){
	 	$mainObj = $this->get_d($id);
	 	if($mainObj['ExaStatus'] == '完成'){
			$this->mailDeal_d('requireAudit',null,array('id' =>$mainObj['id']));
	 	}
	 }

	/**
	 * 打回部分
	 */
	function backDetail_d($object){
		try{
			$this->start_d();
			//如果符合全单撤回，则更新状态
			if($object['id']){
				$this->update(array('id' => $object['id']),array("isRecognize" => 4,'backReason'=>$object['backReason']));
			}
			$backArr = array(
				'requireId'=>$object['id'],
				'backReason'=>$object['backReason']
			);
			$backDao = new model_asset_require_requireback();
			$backArr = $backDao->addCreateInfo ( $backArr );
			$newId = $backDao->create ( $backArr );
			//获取其它相关信息
			$object = $this->get_d($object['id']);
			//发送邮件,收件人为申请人、使用人
			$mailId = $object['applyId'].",".$object['userId'];
			$this->mailDeal_d('requirementBack',$mailId,array(id=>$object['id']));
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * 撤回部分
	 */
	function rollback_d($id){
		try{
			$this->start_d();
			//如果符合全单撤回，则更新状态
			if($id){
				$this->update(array('id' => $id),array("isRecognize" => 2,'isSubmit'=> 0));
			}
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * 更新资产申请状态
	 */
	function updateRecognize($id){
		$recognizeVal = "";
		$applyDao = new model_asset_purchase_apply_apply();
		$receiveItemDao = new model_asset_purchase_receive_receiveItem();
		$borrowDao = new model_asset_daily_borrow();
		$chargeDao = new model_asset_daily_charge();
		$purchAmount = $applyDao->countPurch($id);
		$isCardAmount = $receiveItemDao->countIsCard($id);
		$isSignBorrow = $borrowDao->countIsSign($id);
		$isSignCharge = $chargeDao->countIsSign($id);
		//显示状态规则：采购中>生成资产卡片>申请人签收
		//只有完成所有采购、生成资产卡片和申请人签收操作，才将资产需求申请状态改为【已完成】，值设置为8
		if($purchAmount != 0 || $isCardAmount != 0 || $isSignBorrow != 0 || $isSignCharge != 0){
			if($purchAmount != 0 && $isCardAmount != 0){
				//状态为【采购中】,值设置为5
				$recognizeVal = 5;
			}elseif ($purchAmount != 0 && ($isSignBorrow != 0 || $isSignCharge != 0)){
				//状态为【采购中】,值设置为5
				$recognizeVal = 5;
			}elseif ($isCardAmount != 0 && ($isSignBorrow != 0 || $isSignCharge != 0)){
				//状态为【生成资产卡片】,值设置为6
				$recognizeVal = 6;
			}elseif ($isCardAmount != 0){
				//状态为【生成资产卡片】,值设置为6
				$recognizeVal = 6;
			}elseif ($isSignBorrow != 0 || $isSignCharge != 0){
				//状态为【申请人签收】,值设置为7
				$recognizeVal = 7;
			}else {
				//状态为【采购中】,值设置为5
				$recognizeVal = 5;
			}
		}else {
			//获取发货状态，用以区分是已完成状态还是已确认状态
			$rs = $this->find(array('id' => $id),null,'DeliveryStatus');
			if($rs['DeliveryStatus'] == 'YFH'){
				//状态为【已完成】,值设置为8
				$recognizeVal = 8;
			}else{
				//状态为【已确认】,值设置为1
				$recognizeVal = 1;
			}
		}
		$this->update(array('id' => $id),array('isRecognize' => $recognizeVal));
	}

	/**
	 * workflow callback
	 */
	 function workflowCallBack($spid){
	 	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if($folowInfo ['examines'] == "ok"){
      	 	$this->dealAfterAudit_d($objId);
		}
	 }
 }