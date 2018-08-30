<?php

/**
 * 资产出售model层类
 *@linzx
 */
class model_asset_disposal_sell extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_sell";
		$this->sql_map = "asset/disposal/sellSql.php";
		parent :: __construct();


	}


	/*===================================业务处理======================================*/
	/**
	 * 设置关联从表的申请单id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ($iteminfoArr as $key => $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}


  	/**
	 * @desription 添加保存方法
	 * @linzx
	 */
	function add_d ($sellinfo) {
		try{
			$this->start_d();
			if(is_array($sellinfo['item'])){
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_sell";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$sellinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_sell", "CS" ,$thisDate,$sellinfo['applyCompanyCode'],'固定资产出售单',true);
		       	}else{
					$sellinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_sell", "CS" ,$thisDate,$sellinfo['applyCompanyCode'],'固定资产出售单',false);
		       	}
                 $id = parent :: add_d($sellinfo,true);
                 $sellDao = new model_asset_disposal_sellitem();
                 //将主表的id和从表id关联起来
                 $itemsArr = $this->setItemMainId ( "sellID", $id,$sellinfo['item']);
                 $itemsObj = $sellDao->saveDelBatch ( $itemsArr );

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
	function edit_d ($sellinfo) {
		try{
			$this->start_d();

			if(is_array($sellinfo['item'])){
				$id=parent :: edit_d($sellinfo,true);
			    $sellDao = new model_asset_disposal_sellitem();
                $itemsArr = $this->setItemMainId ( "sellID",$sellinfo['id'],$sellinfo['item']);
                 $itemsObj = $sellDao->saveDelBatch ( $itemsArr );
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
		$sellitemDao = new model_asset_disposal_sellitem();
		$sellitemDao->searchArr['sellID']=$id;
		$sellitem = $sellitemDao->listBySqlId();
		$selliteminfo = parent :: get_d($id);
		$selliteminfo['details'] = $sellitem;
		return $selliteminfo;
	}

   	/**
	 * 根据Id 拿到该数据的报废单明细表资产id
     * @linzx
	 */
	function getCardIdById_d($id)	{
	 	$dirObj = $this->get_d($id);
	 	foreach($dirObj['details'] as $key=>$val){
	 		$assetId=$val['assetId'];
	 	$this->setRelEquSellStatus($assetId);
	 	}
	 }
	/**
	 * 出售资产后修改关联单据资产清单的状态位。
	 * @linzx
	 */
	function setRelEquSellStatus($id){
		//$scrapDao = new model_asset_disposal_scrapitem();
		$sellDao = new model_asset_assetcard_assetcard();
		return $sellDao->setIsSell($id);
	}

}
?>