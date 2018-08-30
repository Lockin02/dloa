<?php

/**
 * 资产维保model层类
 *@linzx
 */
class model_asset_daily_keep extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_keep";
		$this->sql_map = "asset/daily/keepSql.php";
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
	function add_d ($keepinfo) {
		try{
			$this->start_d();
			//$codeDao=new model_common_codeRule();
			//$scrapinfo['fillupCode']=$codeDao->stockCode("oa_stock_fillup","FILL");
			if(is_array($keepinfo['item'])){
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_keep";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$keepinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_keep", "WB" ,$thisDate,$keepinfo['applyCompanyCode'],'固定资产维保单',true);
		       	}else{
					$keepinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_keep", "WB" ,$thisDate,$keepinfo['applyCompanyCode'],'固定资产维保单',false);
		       	}
                 $id = parent :: add_d($keepinfo,true);
                 $keepDao = new model_asset_daily_keepitem();
                 //将主表的id和从表id关联起来
                 $itemsArr = $this->setItemMainId ( "keepId", $id,$keepinfo['item']);
                 $itemsObj = $keepDao->saveDelBatch ( $itemsArr );
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
	function edit_d ($keepinfo) {
		try{
			$this->start_d();

			if(is_array($keepinfo['item'])){
				$id=parent :: edit_d($keepinfo,true);
			    $keepDao = new model_asset_daily_keepitem();
                $itemsArr = $this->setItemMainId ( "keepId",$keepinfo['id'],$keepinfo['item']);
                 $itemsObj = $keepDao->saveDelBatch ( $itemsArr );
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
     * 根据维保资产的ID将申请单所有的维保资产拿出来
     * @linzx
	 */
   function get_d($id){
		$keepitemDao = new model_asset_daily_keepitem();
		$keepitemDao->searchArr['keepId']=$id;
		$keepitem = $keepitemDao->listBySqlId();
		$keepiteminfo = parent :: get_d($id);
		$keepiteminfo['details'] = $keepitem;
		return $keepiteminfo;
	}


	/**
	 * 资产维保审批后处理操作
	 * @author zengzx
	 * @since 1.0 - 2011-11-29
	 */
	function dealRelInfoAtAudit($id,$relInfo){
		try{
			$this->start_d();
			$flag = false;
			$obj = $this->get_d($id);
			$details = $obj ['details'];
			if(array($details)){
				$cardDao = new model_asset_assetcard_assetcard();
				//获取对应的变动数据
				foreach ( $details as $key=>$val ){
					if( $val['amount']*1 >= 500 ){
						$cardObjs = array();
						//资产编码、使用部门、使用人
						$cardObjs['oldId']=$val['assetId'];
						$cardObjs['assetCode']=$val['assetCode'];
						$cardObjs['origina']=$val['amount'];
						//进入卡片类，添加变动记录
						if($cardDao->changeByObj_d($cardObjs,$relInfo)){
							$flag = true;
						}else{
							throw new Exception("单据信息不完整，请确认!");
						}
					}else{
						continue;
					}
				}
			}
			$this->commit_d();
			return $flag;
		}catch(Exception $e ){
			$this->rollBack();
			return $flag;
		}
	}

}
?>