<?php

/**
 * 资产领用model层类
 *@linzx
 */
class model_asset_daily_charge extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_charge";
		$this->sql_map = "asset/daily/chargeSql.php";
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
	function add_d ($chargeinfo) {
		try{
			$this->start_d();
			//$codeDao=new model_common_codeRule();
			//$scrapinfo['fillupCode']=$codeDao->stockCode("oa_stock_fillup","FILL");
			if(is_array($chargeinfo['item'])){
				$codeDao = new model_common_codeRule ();
				$assetDao = new model_asset_assetcard_assetcard();
			    $requireDao = new model_asset_require_requirement();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_charge";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$chargeinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_charge", "LY" ,$thisDate,$chargeinfo['applyCompanyCode'],'固定资产领用单',true);
		       	}else{
					$chargeinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_charge", "LY" ,$thisDate,$chargeinfo['applyCompanyCode'],'固定资产领用单',false);
		       	}
				 $chargeinfo['docStatus'] = 'WGH';
                 $id = parent :: add_d($chargeinfo,true);
                 $chargeDao = new model_asset_daily_chargeitem();
                 //将主表的id和从表id关联起来
                 $itemsArr = $this->setItemMainId ( "allocateID", $id,$chargeinfo['item']);
                 $itemsObj = $chargeDao->saveDelBatch ( $itemsArr );
                 //设置资产卡片状态为已借出
				$assetArr = array();
				foreach( $chargeinfo['item'] as $key=>$val ){
					$assetArr[] = $val['assetId'];
				}
				$assetDao->setIdleStatus($assetArr,'1');
                 //更新需求已发货数量
				$this->updateRequireExeNum($itemsArr);
				$requireDao->updateOutStatus($chargeinfo['requireId']);
	            //处理附件名称和Id
			     $this->updateObjWithFile($id);
          		 //发送邮件
          		 $this->mailDeal_d('assetCharge',$chargeinfo['chargeManId'],array(id => $id));
          		 //改变资产申请状态
          		 $requirementId = $chargeinfo['requireId'];
          		 $requireDao->updateRecognize($requirementId);
			     $this->commit_d();
			     return 1;
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
	function edit_d ($chargeinfo) {
		try{
			$this->start_d();
			if(is_array($chargeinfo['item'])){
				$oldObj = $this->get_d($chargeinfo['id']);
				$id=parent :: edit_d($chargeinfo,true);
				$assetDao = new model_asset_assetcard_assetcard();
			    $chargeDao = new model_asset_daily_chargeitem();
			    $requireDao = new model_asset_require_requirement();
                $itemsArr = $this->setItemMainId ( "allocateID",$chargeinfo['id'],$chargeinfo['item']);
                 $itemsObj = $chargeDao->saveDelBatch ( $itemsArr );
				$unUseArr = array();
				$isUseArr = array();
				foreach( $oldObj['details'] as $key=>$val ){
					$unUseArr[]=$val['assetId'];
				}
				foreach( $chargeinfo['item'] as $key=>$val ){
					if( !isset($val['isDelTag']) )
					$isUseArr[]=$val['assetId'];
				}
				if( count($unUseArr)>0 ){
					$assetDao->setIdleStatus($unUseArr,'0');
				}
				if( count($isUseArr)>0 ){
					$assetDao->setIdleStatus($isUseArr,'1');
				}
                 //更新需求已发货数量
				$this->updateRequireExeNum($itemsArr);
				$requireDao->updateOutStatus($chargeinfo['requireId']);
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
     * 根据资产的ID将申请单所有的资产拿出来
     * @linzx
	 */
   function get_d($id){
		$chargeitemDao = new model_asset_daily_chargeitem();
		$chargeitemDao->searchArr['allocateID']=$id;
		$chargeitem = $chargeitemDao->listBySqlId();
		$chargeiteminfo = parent :: get_d($id);
		$chargeiteminfo['details'] = $chargeitem;
		return $chargeiteminfo;
	}

	/**
	 * 资产领用审批后处理操作
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
				$dataDao = new model_system_datadict_datadict();
				$cardDao = new model_asset_assetcard_assetcard();
				//获取对应的变动数据
				foreach ( $details as $key=>$val ){
					$cardObjs = array();
					//资产编码、使用部门、使用人
					$cardObjs['oldId']=$val['assetId'];
					$cardObjs['assetCode']=$val['assetCode'];
					$cardObjs['useOrgId']=$obj['deptId'];
					$cardObjs['useOrgName']=$obj['deptName'];
					$cardObjs['userId']=$obj['chargeManId'];
					$cardObjs['userName']=$obj['chargeMan'];
					$cardObjs['useStatusCode']='SYZT-SYZ';
					$cardObjs['useStatusName']=$dataDao->getDataNameByCode('SYZT-SYZ');
					//获取使用二级部门信息
					$rs = $cardDao->getParentDept_d($obj['deptId']);
					$cardObjs['parentUseOrgId'] = $rs[0]['parentId'];
					$cardObjs['parentUseOrgName'] = $rs[0]['parentName'];
					//进入卡片类，添加变动记录
					if($cardDao->changeByObj_d($cardObjs,$relInfo)){
						$flag = true;
					}else{
						throw new Exception("单据信息不完整，请确认!");
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
	
	/**
	 * 根据id更新单据状态
	 */
	function setDocStatus($id){
		$chargeItemDao = new model_asset_daily_chargeitem();

		$sql = "select (select count(0) from ".$chargeItemDao->tbl_name." where allocateID=$id) as itemNum ,
				(select count(0) from ".$chargeItemDao->tbl_name." where allocateID=$id and isReturn=0) as remainNum";
		$numStr = $this->_db->getArray( $sql );
		if($numStr[0]['remainNum']==0){
			$status = 'YGH';
		}elseif($numStr[0]['remainNum']>0&&$numStr[0]['remainNum']!=$numStr[0]['itemNum']){
			$status = 'BFGH';
		}else{
			$status = 'WGH';
		}
		$rows = array(
			'docStatus'=>$status,
			'returnDate'=>day_date
		);
		return $this->update(array('id'=>$id),$rows);
	}

	/**
	 * 更新需求已发货数量
	 */
	 function updateRequireExeNum($itemObj){
	 	$itemIdArr = array();
	 	foreach( $itemObj as $key => $val ){
	 		$itemIdArr[] = $val['itemId'];
	 	}
	 	$requireItemDao = new model_asset_require_requireitem();
	 	$requireItemDao->setExeNum($itemIdArr);
	 }

	 /******************************签收业务******************************/
	/**
	 * 获取签收对应的资产需求申请id
	 */
    function getRequirementId($id){
    	$sql = "
			SELECT
				requireId
			FROM ".$this->tbl_name."
			WHERE
				id = '".$id."'";
    	$rs = $this->_db->get_one($sql);
    
    	return $rs['requireId'];
    }
    
    /**
     * 获取同个资产需求申请下借用需求状态为【未签收】的记录数
     */
    function countIsSign($requireId){
    	$sql = "
			SELECT
				COUNT(*) AS isSignAmount
			FROM
				".$this->tbl_name."
			WHERE
				requireId = '".$requireId."'
			AND isSign = 0";
    	$rs = $this->_db->get_one($sql);
    
    	return $rs['isSignAmount'];
    }
    
    /**
     * 签收
     */
    function sign_d($id){
    	try{
    		$this->start_d();
    		
    		$signInfo = array(
    				'id' => $id,
    				'isSign' => '1',
    				'signDate' => day_date
    		);
    		$this->updateById($signInfo);
    		$dailDao = new model_asset_daily_dailyCommon();
    		$dailDao->ctDealRelInfoAtSign($id,'oa_asset_charge');
    		//改变资产申请状态
    		$requirementId = $this->getRequirementId($id);//获取签收对应的资产需求申请id
    		$requirementDao = new model_asset_require_requirement();
    		$requirementDao->updateRecognize($requirementId);
    		
    		$this->commit_d();
    		return 1;
    	}catch(Exception $e ){
    		$this->rollBack();
    		return 0;
    	}
    }
    
    /**
     * 自动签收的方法
     */
    function autoSign_d($obj){
    	return $this->sign_d($obj['id']);
    }
    
    /**
     * 资产管理员签收时确认转入设备
     */
    function signToDevice_d($objName){
    	try{
    		$this->start_d();
    		
    		if(is_array($objName['item'])){
    			$item = $objName['item'];
    			//去掉页面删除的数据
    			foreach ($item as $key => $val){
    				if($val['isDelTag'] == 1){
    					unset($item[$key]);
    				}
    			}
    			if(empty($item)){
    				msg ( '单据信息不完整!' );
    			}
    			$dept_id = $objName['deptId'];
    			$timeStamp = time();
    			$sql = "INSERT INTO device_info (assetCardId,assetCardCode,coding,dept_id,area,dpcoding,list_id,amount,date,rand_key,depreciation,depreciationYear) VALUES ";
    			$dataSql = "";
				foreach ($item as $v){
					if ($dataSql) $dataSql .= ',';
					$dataSql .= "(" . $v['assetId'] . ",'" . $v['assetCode'] . "','" . $v['machineCode'] .  "'," . $dept_id .
					                ",1,'" . $v['dpcoding'] . "'," . $v['resourceId'] . ",1," . $timeStamp . ",'" .
					                md5($timeStamp.rand()) . "','" . $v['depreciation'] . "','" . $v['depreciationYear']. "')";
				}
				//设备管理的库存信息入库
				$this->_db->query($sql.$dataSql);
				//签收处理
				$this->sign_d($objName['id']);
    		}else{
    			msg( "单据信息不完整!" );
    		}
    		
    		$this->commit_d();
    		return true;
    	}catch(Exception $e ){
    		$this->rollBack();
    		return false;
    	}
    }
}