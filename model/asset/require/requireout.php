<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:资产转物料申请 Model层
 */
 class model_asset_require_requireout extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requireout";
		$this->sql_map = "asset/require/requireoutSql.php";
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
		       	$sql = "SELECT MAX(applyDate) as applyDate from oa_asset_requireout";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = $applyDateArr['applyDate'];
		       	$thisDate = day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requireout", "ZCRK" ,$thisDate,$object['businessBelong'],'资产入库申请单',true);
		       	}else{
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requireout", "ZCRK" ,$thisDate,$object['businessBelong'],'资产入库申请单',false);
		       	}
		       	$object['inStockStatus']="WRK";//入库状态--未入库
				$id = parent::add_d ( $object, true );
				$requireoutitemDao = new model_asset_require_requireoutitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id , $object ['items'] );
				$requireoutitemDao->saveDelBatch ( $itemsArr );
				//提交时，将资产卡片状态置为待退库
				if($object['ExaStatus'] != "待提交"){
					foreach($object ['items'] as $key => $val ){
						if($val['isDelTag'] != 1){//isDelTag=1 为从表已删除
							$assetcardDao = new model_asset_assetcard_assetcard();
							$assetcardDao->setToStock($val['assetId']);
						}
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
				$requireoutitemDao = new model_asset_require_requireoutitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $object ['id'], $object ['items'] );
				$requireoutitemDao->saveDelBatch ( $itemsArr );
				//提交时，将资产卡片状态置为待退库
				if($object['ExaStatus'] != "待提交"){
					foreach($object ['items'] as $key => $val ){
						if($val['isDelTag'] != 1){//isDelTag=1 为从表已删除
							$assetcardDao = new model_asset_assetcard_assetcard();
							$assetcardDao->setToStock($val['assetId']);
						}
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
		// 如果是数字，那么是旧OA，否则是新OA
		if (is_numeric($id) && strlen($id) < 32) {
			$object = parent::get_d ( $id );
	   		$requireoutitemDao = new model_asset_require_requireoutitem();
			$requireoutitemDao->searchArr ['mainId'] = $id;
			$object ['items'] = $requireoutitemDao->listBySqlId ();
		} else {
			// 接入aws
			// 从aws获取资产转物料申请数据
			$result = util_curlUtil::getDataFromAWS('asset', 'getAssetTransferInfo', array(
				"id" => $id
			));
			$assetTransferInfo = util_jsonUtil::decode($result['data'], true);
			// 主表数据处理
			$object = $assetTransferInfo['data']['assetTransferInfo'];
			//数据对应
			$object['requireCode'] = $object['applyNo'];
			$object['applyDate'] = date("Y-m-d",strtotime($object['applyDate']));
			$object['applyName'] = $object['applyUser'];
			$object['applyId'] = $object['applyUserId'];
			$object['applyDeptName'] = $object['applyDept'];
			// 从表数据处理
			// 资产转物料申请明细
			if (!empty ($assetTransferInfo['data']['details'])) {
				$items = array();
				foreach ($assetTransferInfo['data']['details'] as $k => $v) {
					$v['id'] = $k;
					$v['spec'] = $v['pattern'];
					$v['number'] = $v['applyNum'];
					$v['executedNum'] = $v['inStorageNum'];
					$v['salvage'] = $v['assetRest'];
					array_push($items, $v);
				}
				$object ['items'] = $items;
			}
		}
		return $object;
	}

 	/**
	 * 蓝字出库审核
	 * @param  $id   申请单ID
	 * @param  $equId   物料清单ID
	 * @param  $productId   物料ID
	 * @param  $proNum    入库数量
	 */
	function updateInStock($id,$equId,$productId,$proNum){
		// 如果是数字，那么是旧OA，否则是新OA
		if (is_numeric($equId) && strlen($equId) < 32) {
			$itemDao = new model_asset_require_requireoutitem();
			//更新物料入库数量
			$itemDao->updateInNum($equId,$proNum);
			//获取卡片id
			$rs = $itemDao->find(array('id' => $equId),null,'assetId');
			$assetId = $rs['assetId'];
			//更新卡片状态为已退库
			$assetcardDao = new model_asset_assetcard_assetcard();
			$assetcardDao->setIsStock($assetId);
			//更新需求入库状态
			$this->updateInStatus($id);
		} else {
			// 获取资产验收单的内容
			$result = util_curlUtil::getDataFromAWS('asset', 'updateAssetTransferDetail', array(
				"id" => $equId,
				"inStorageNum" => $proNum
			));

			$errorInfo = util_jsonUtil::decode($result['data']);
			if ($errorInfo['data']['error']) {
				throw new Exception($errorInfo['data']['error']);
			}
		}
	}
	
	/**
	 * 蓝字单反审核
	 * @param  $id   申请单ID
	 * @param  $equId   物料清单ID
	 * @param  $productId   物料ID
	 * @param  $proNum    入库数量
	 */
	function updateInStockCancel($id,$equId,$productId,$proNum){
		// 如果是数字，那么是旧OA，否则是新OA
		if (is_numeric($equId) && strlen($equId) < 32) {
			$itemDao = new model_asset_require_requireoutitem();
			//更新物料入库数量
			$itemDao->updateInNum($equId,-$proNum);
			//获取卡片id
			$rs = $itemDao->find(array('id' => $equId),null,'assetId');
			$assetId = $rs['assetId'];
			//更新卡片状态为待退库
			$assetcardDao = new model_asset_assetcard_assetcard();
			$assetcardDao->setToStock($assetId);
			//更新需求入库状态
			$this->updateInStatus($id);
		} else {
			// 获取资产验收单的内容
			$result = util_curlUtil::getDataFromAWS('asset', 'updateAssetTransferDetail', array(
				"id" => $equId,
				"inStorageNum" => -$proNum
			));

			$errorInfo = util_jsonUtil::decode($result['data']);
			if ($errorInfo['data']['error']) {
				throw new Exception($errorInfo['data']['error']);
			}
		}
	}
	
	/**
	 * 根据id更新需求的入库状态
	 */
	 function updateInStatus($id){
	 	$sql = "select count(0) as countNum,(select sum(o.executedNum) from oa_asset_requireoutitem o where o.mainId=".$id." )
	 			as executeNum from (select e.mainId,(e.number-e.executedNum) as remainNum from oa_asset_requireoutitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
		$remainNum = $this->_db->getArray( $sql );
		$inStockStatus = '';
	 	if( $remainNum[0]['countNum'] <= 0 ){//已入库
	 		$inStockStatus = 'YRK';
	 	}elseif( $remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0 ){//未入库
	 		$inStockStatus = 'WRK';
		} else {//部分出库
	 		$inStockStatus = 'BFRK';
	 	}
	 	$statusInfo = array(
	 		'id' => $id,
	 		'inStockStatus' => $inStockStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }
	 /**
	  * 删除主表和从表信息
	  */
	 function deletes_d($id) {
	 	try {
	 		$this->start_d ();
	 		
			$itemDao = new model_asset_require_requireoutitem();
			$itemDao->delete(array('mainId'=>$id));
			$this->deletes($id);

			$this->commit_d ();
			return true;
		} catch (Exception $e) {
			$this->rollBack ();
			return false;
		}	
	 }
 }