<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:物料转资产申请 Model层
 */
 class model_asset_require_requirein extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requirein";
		$this->sql_map = "asset/require/requireinSql.php";
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
			if (is_array ( $object ['items'] )) {
		       	$sql = "SELECT MAX(applyDate) as applyDate from oa_asset_requirein";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = $applyDateArr['applyDate'];
		       	$thisDate = day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_requirein", "ZCCK" ,$thisDate,$object['businessBelong'],'资产出库申请单',true);
		       	}else{
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_requirein", "ZCCK" ,$thisDate,$object['businessBelong'],'资产出库申请单',false);
		       	}
		       	$object['outStockStatus']="WCK";//出库状态--未出库

				$id = parent::add_d ( $object, true );
				$requireinitemDao = new model_asset_require_requireinitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id , $object ['items'] );
				$requireinitemDao->saveDelBatch ( $itemsArr );
				if($object['status'] == "待确认"){//提交时执行
					// 接入aws
					// 改变需求申请单状态,置为【物料转资产中】
					$result = util_curlUtil::getDataFromAWS ( 'asset', 'updateApplyStatus', array (
						'requireId' => $object ['requireId'],
						'applyStatus' => '1042' 
					));
					if($result){
						//邮件通知相关人员进行出库确认
						$this->mailDeal_d('assetRequireinConfirm',null,array('id' => $id));
						//邮件通知申请人单据进度
						$this->mailDeal_d('assetRequireinStep1',$object ['applyId'],array('id' => $id));
					}
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
				parent::edit_d ( $object, true );
				$id = $object ['id'];
				$requireinitemDao = new model_asset_require_requireinitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id, $object ['items'] );
				$requireinitemDao->saveDelBatch ( $itemsArr );
				if($object['status'] == "待确认"){//提交时执行
					// 接入aws
					// 改变需求申请单状态,置为【物料转资产中】
					$result = util_curlUtil::getDataFromAWS ( 'asset', 'updateApplyStatus', array (
							'requireId' => $object ['requireId'],
							'applyStatus' => '1042'
					));
					if($result){
						//邮件通知相关人员进行出库确认
						$this->mailDeal_d('assetRequireinConfirm',null,array('id' => $id));
						//邮件通知申请人单据进度
						$this->mailDeal_d('assetRequireinStep1',$object ['applyId'],array('id' => $id));
					}
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
   		$requireinitemDao = new model_asset_require_requireinitem();
		$requireinitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $requireinitemDao->listBySqlId ();
		return $object;
	}
	
	/**
	 * 蓝字出库审核
	 */
	function updateAsOut($rows) {
		$itemDao = new model_asset_require_requireinitem();
		//更新物料出库数量
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//更新发货状态
		$this->updateOutStatus($id);
		//更新需求申请物料转资产状态
// 		$this->updateRequireInStatus($id);
	}

	/**
	 * 蓝字出库单反审核
	 */
	function updateAsAutiAudit($rows) {
		$itemDao = new model_asset_require_requireinitem();
		$rows['outNum'] = $rows['outNum']*(-1);
		//更新物料出库数量
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//更新发货状态
		$this->updateOutStatus($id);
		//更新需求申请物料转资产状态
// 		$this->updateRequireInStatus($id);
	}
	
	/**
	 * 红字出库审核
	 */
	function updateAsRedOut($rows) {
		$itemDao = new model_asset_require_requireinitem();
		$rows['outNum'] = $rows['outNum']*(-1);
		//更新物料出库数量
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//更新发货状态
		$this->updateOutStatus($id);
		//更新需求申请物料转资产状态
// 		$this->updateRequireInStatus($id);
	}
	
	/**
	 * 红字出库反审核
	 */
	function updateAsRedAutiAudit($rows) {
		$itemDao = new model_asset_require_requireinitem();
		//更新物料出库数量
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//更新发货状态
		$this->updateOutStatus($id);
		//更新需求申请物料转资产状态
// 		$this->updateRequireInStatus($id);
	}
	
	/**
	 * 根据id更新需求的出库状态
	 */
	 function updateOutStatus($id){
	 	$sql = "select count(*) as countNum,(select sum(o.executedNum) from oa_asset_requireinitem o where o.mainId=".$id." )
	 			as executeNum from (select (e.number-e.executedNum) as remainNum from oa_asset_requireinitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
		$remainNum = $this->_db->getArray( $sql );
		$outStockStatus = '';
	 	if( $remainNum[0]['countNum'] <= 0 ){//已出库
	 		$outStockStatus = 'YCK';
	 	}elseif( $remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0 ){//未出库
	 		$outStockStatus = 'WCK';
		} else {//部分出库
	 		$outStockStatus = 'BFCK';
	 	}
	 	$statusInfo = array(
	 		'id' => $id,
	 		'outStockStatus' => $outStockStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }
	 
	 /**
	  * 根据id更新需求的验收状态
	  */
	 function updateReceiveStatus($id){
	 	$sql = "select count(*) as countNum,(select sum(o.receiveNum) from oa_asset_requireinitem o where o.mainId=".$id." )
	 			as receiveNum from (select (e.number-e.receiveNum) as remainNum from oa_asset_requireinitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $sql );
	 	$receiveStatus = '0';//未验收
	 	if( $remainNum[0]['countNum'] <= 0 ){//已验收
	 		$receiveStatus = '2';
	 	}elseif( $remainNum[0]['countNum'] > 0 && $remainNum[0]['receiveNum'] > 0 ){//部分验收
	 		$receiveStatus = '1';
	 	}
	 	$statusInfo = array(
 			'id' => $id,
 			'receiveStatus' => $receiveStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }
	 
	 /**
	  * 根据id更新需求的单据状态
	  */
	 function updateStatus($id){
	 	$sql = "select sum(cardNum) as cardNum,sum(number)-sum(cardNum) as num from oa_asset_requireinitem where mainId = ".$id;
	 	$rs = $this->_db->getArray( $sql );
	 	if($rs[0]['num'] == 0){//已完成
	 		$status = '已完成';
	 	}elseif ($rs[0]['cardNum'] > 0){
	 		$status = '部分完成';
	 	}
	 	$statusInfo = array(
 			'id' => $id,
 			'status' => $status
	 	);
	 	$this->updateById( $statusInfo );
	 }

	 /**
	  * 更新需求申请的物料转资产状态
	  */
	 function updateRequireInStatus($id,$status = null){
	 	$rs = $this->find(array('id'=>$id),null,'requireId');
	 	$requireId = $rs['requireId'];//需求申请id
	 	if(empty($status)){
	 		//获取需求明细
	 		$sql = "select sum(number) as number from oa_asset_requireitem where mainId = ".$requireId;
	 		$rs = $this->_db->getArray( $sql );
	 		$requireitemNum = $rs[0]['number'];
	 		//获取物料转资产明细
	 		$sql = "
	 				SELECT
						SUM(m.number) AS number,
						SUM(m.executedNum) AS executedNum,
	 					SUM(m.receiveNum) AS receiveNum,
						SUM(m.cardNum) AS cardNum
					FROM
						oa_asset_requireinitem m
					WHERE
						mainId IN (
							SELECT
								n.id
							FROM
								oa_asset_requirein n
							WHERE
								n.requireId = ".$requireId.")";
	 		$rs = $this->_db->getArray( $sql );
	 		$inNumber = $rs[0]['number'];
	 		$inExecutedNum = $rs[0]['executedNum'];
	 		$inReceiveNum = $rs[0]['receiveNum'];
	 		$inCardNum = $rs[0]['cardNum'];
	 		//状态显示顺序：待验收>待生成资产卡片>待出库
	 		if($requireitemNum == $inCardNum){//已完成
	 			$status = '4';
	 		}elseif($inExecutedNum > $inReceiveNum){//待验收
	 			$status = '2';
	 		}elseif($inReceiveNum > $inCardNum){//待生成资产卡片
	 			$status = '3';
	 		}elseif($inNumber > $inExecutedNum){//待出库
	 			$status = '1';
	 		}else{//默认状态
	 			$status = '0';
	 		}
	 	}
	 	$requirementDao = new model_asset_require_requirement();
	 	$statusInfo = array(
 			'id' => $requireId,
 			'requireInStatus' => $status
	 	);
	 	$requirementDao->updateById( $statusInfo );
	 }
	 
	 /**
	  * 删除主表和从表信息
	  */
	 function deletes_d($id) {
	 	try {
	 		$this->start_d ();
	 
	 		$itemDao = new model_asset_require_requireinitem();
	 		$itemDao->delete(array('mainId'=>$id));
	 		$this->deletes($id);
	 
	 		$this->commit_d ();
	 		return true;
	 	} catch (Exception $e) {
	 		$this->rollBack ();
	 		return false;
	 	}
	 }
	 
	 /**
	  * 确认由仓管资产出库
	  */
	 function confirm_d($id){
	 	try {
	 		$this->start_d ();
	 		
	 		$object = $this->get_d($id);
	 		// 单据状态修改为已确认
			$this->updateById(array('id' => $id, 'status' => '已确认', 'confirmId' => $_SESSION['USER_ID'],
				 			'confirmName' => $_SESSION['USERNAME'], 'confirmTime' => date('Y-m-d H:i:s')));
	 	
	 		// 邮件通知相关人员进行出库
	 		$this->mailDeal_d('assetRequirein',null,array('id' => $id));
	 		//邮件通知申请人单据进度
	 		$this->mailDeal_d('assetRequireinStep2',$object ['applyId'],array('id' => $id));
	 		
	 		$this->commit_d ();
	 		return true;
	 	} catch (Exception $e) {
	 		$this->rollBack();
	 		return false;
	 	}
	 }
	 
	 /**
	  *打回单据
	  */
	 function back_d($object) {
	 	try {
	 		$this->start_d ();
	 		
	 		// 单据状态修改为打回
	 		$object['status'] = '打回';
	 		parent :: edit_d($object, true);
	 
	 		// 邮件通知
	 		if($object['mailInfo']['issend'] == 'y'){
	 			$id = $object['id'];
	 			$this->mailDeal_d('assetRequireinBack',$object['mailInfo']['TO_ID'],array('id' => $id));
	 			//邮件通知申请人单据进度
	 			$this->mailDeal_d('assetRequireinStep3',$object ['applyId'],array('id' => $id));
	 		}
	 			
	 		$this->commit_d ();
	 		return true;
	 	} catch (Exception $e) {
	 		$this->rollBack();
	 		return false;
	 	}
	 }
 }