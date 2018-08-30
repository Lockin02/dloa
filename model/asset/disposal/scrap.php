<?php
/**
 * 资产报废model层类
 *@linzx
 */
class model_asset_disposal_scrap extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_scrap";
		$this->sql_map = "asset/disposal/scrapSql.php";
		parent :: __construct();


	}


	/*===================================业务处理======================================*/
	/**
	 * 设置关联从表的申请单id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			//unset($value['id']);
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}


  	/**
	 * @desription 添加保存方法
	 * @linzx
	 */
	function add_d ($scrapinfo) {
		try{
			$this->start_d();
			if(is_array($scrapinfo['item'])){
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_scrap";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$scrapinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_scrap", "BF" ,$thisDate,$scrapinfo['applyCompanyCode'],'固定资产报废单',true);
		       	}else{
					$scrapinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_scrap", "BF" ,$thisDate,$scrapinfo['applyCompanyCode'],'固定资产报废单',false);
		       	}
                 $id = parent :: add_d($scrapinfo,true);

                 $scrapDao = new model_asset_disposal_scrapitem();
                 //将主表的id和从表id关联起来
                 $itemsArr = $this->setItemMainId ( "allocateID", $id,$scrapinfo['item']);
                 $itemsObj = $scrapDao->saveDelBatch ( $itemsArr );
                 //isDelTag=1 为从表已删除
                 foreach($scrapinfo['item'] as $key=>$val ){
                 	if($val['isDelTag']!=1){
	                 	$assetId=$val['assetId'];
	                 	//$loseBillNo=$val['loseBillNo'];
	                 	//将从遗失单过来的资产，将其报废状态改成已报废
	                 	$loseId=$val['loseId'];
	                   	$changeStatus= new model_asset_daily_loseitem();
	                   	$changeStatus->setScrapStatus($loseId,$assetId);
	                   	//提交财务确认，将资产卡片状态置为【待报废】
	                   	if($scrapinfo['financeStatus'] == "财务确认"){
	                   		$assetcardDao = new model_asset_assetcard_assetcard();
	                   		$assetcardDao->setToScrap($val['assetId']);
	                   	}
                 	}
                 }
	            //处理附件名称和Id
			     $this->updateObjWithFile($id);
			     //提交财务确认，发送固定资产报废申请邮件
			     if($scrapinfo['financeStatus'] == "财务确认" && $scrapinfo['mailInfo']['issend'] == 'y'){
			     	$this->mailDeal_d('scrap',$scrapinfo['mailInfo']['TO_ID'],array(id => $id));
			     }
				/*e:2.保存从表资产信息*/
			     $this->commit_d();
			     return $id;
				 }else {
				   throw new Exception ( "单据信息不完整!" );
				}

		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}

						}

 	/**
	 * @desription 修改保存方法
	 * @linzx
	 */
	function edit_d ($scrapinfo) {
		try{
			$this->start_d();

			if(is_array($scrapinfo['item'])){
				$id=parent :: edit_d($scrapinfo,true);
			    $scrapDao = new model_asset_disposal_scrapitem();
                $itemsArr = $this->setItemMainId ( "allocateID",$scrapinfo['id'],$scrapinfo['item']);
                $itemsObj = $scrapDao->saveDelBatch ( $itemsArr );
				//将从遗失单过来的资产，在报废单从表里如果被删除了或者财务打回或者撤回就将其报废状态改成未报废
               foreach($scrapinfo['item'] as $key=>$val ){
                 	if($val['isDelTag'] == 1 || $scrapinfo['financeStatus'] == "打回" || $scrapinfo['recallFlag'] == "y"){
	                 	$assetId=$val['assetId'];
	                 	$loseId=$val['loseId'];
	                   	$changeStatus= new model_asset_daily_loseitem();
	                   	$changeStatus->setNoScrapStatus($loseId,$assetId);
                 	}
                 	if($scrapinfo['financeStatus'] == "财务确认"){
                 		//提交财务确认，将资产卡片状态置为【待报废】
                 		$assetcardDao = new model_asset_assetcard_assetcard();
                 		$assetcardDao->setToScrap($val['assetId']);
                 	}elseif($scrapinfo['financeStatus'] == "已确认"){
                 		//财务核对报废申请，更新资产卡片残值、净值信息
                 		$assetcardDao = new model_asset_assetcard_assetcard();
                 		$assetcardDao->updateScrapcard($val);
                 	}elseif($scrapinfo['financeStatus'] == "打回" || $scrapinfo['recallFlag'] == "y"){
                 		//财务打回或者撤回，将资产卡片状态置为【闲置】
                 		$assetcardDao = new model_asset_assetcard_assetcard();
                 		$assetcardDao->setNoScrap($val['assetId']);
                 	} 
                 }
                 if($scrapinfo['financeStatus'] == "财务确认" && $scrapinfo['mailInfo']['issend'] == 'y'){
                 	//提交财务确认，发送固定资产报废申请邮件
                 	$this->mailDeal_d('scrap',$scrapinfo['mailInfo']['TO_ID'],array(id => $scrapinfo['id']));
                 }elseif($scrapinfo['financeStatus'] == "已确认" && $scrapinfo['mailInfo']['issend'] == 'y'){
                 	//财务核对，发送固定资产报废申请确认通知邮件
                 	$this->mailDeal_d('scrapConfrim',$scrapinfo['mailInfo']['TO_ID'],array(id => $scrapinfo['id']));
                 }elseif($scrapinfo['financeStatus'] == "打回" && $scrapinfo['mailInfo']['issend'] == 'y'){
                 	//财务打回，发送固定资产报废申请打回通知邮件
                 	$this->mailDeal_d('scrapBack',$scrapinfo['mailInfo']['TO_ID'],array(id => $scrapinfo['id']));
                 }elseif($scrapinfo['recallFlag'] == "y"){
                 	//撤回，发送固定资产报废申请撤回通知邮件，收件人为缴款人
                 	$this->mailDeal_d('scrapRecall',$scrapinfo['payerId'],array(id => $scrapinfo['id']));
                 }
			}else {
				throw new Exception ( "单据信息不完整!" );
				}
			$this->commit_d();
			return true;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}
   	/**
	 * 重写get_d方法
     * 根据报废资产的ID将申请单所有的报废资产拿出来
     * @linzx
	 */
   function get_d($id){
		$scrapitemDao = new model_asset_disposal_scrapitem();
		$scrapitemDao->searchArr['allocateID']=$id;
		$scrapitem = $scrapitemDao->listBySqlId();
		$scrapiteminfo = parent :: get_d($id);
		$scrapiteminfo['details'] = $scrapitem;
		return $scrapiteminfo;
	}

	   	/**
	 * 根据Id 拿到该数据的报废单明细表资产id
     * @linzx
	 */
	function getAssetIdById_d($id)	{
	 	$dirObj = $this->get_d($id);
	 	foreach($dirObj['details'] as $key=>$val){
	 		$assetId=$val['assetId'];
	 		$this->setRelEquScrapStatus($assetId);
	 	}
	 }
	/**
	 * 出售资产后修改关联单据资产清单的状态位。
	 * @linzx
	 */
	function setRelEquScrapStatus($id){
		$scrapDao = new model_asset_assetcard_assetcard();
		return $scrapDao->setIsScrap($id);
	}
}