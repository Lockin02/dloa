<?php
/**
 * 资产借用model层类
 */
class model_asset_daily_borrow extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_borrow";
		$this->sql_map = "asset/daily/borrowSql.php";
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

	/** @desription 根据id获取申请单所有产品信息
	 * @param tags
	 * @date 2011-8-17
	 */
	function get_d($id) {
		$borrowitemDao = new model_asset_daily_borrowitem();
		$borrowitemDao->searchArr['borrowId'] = $id;
		$items = $borrowitemDao->listBySqlId();
		$borrow = parent :: get_d($id);
		$borrow['details'] = $items; //details被c层获取
		return $borrow;
	}
	
	/**
	 * @desription 添加保存方法
	 * @date 2011-11-21
	 * @chenzb
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if (is_array($object['borrowitem'])) {
				$codeDao = new model_common_codeRule ();
				$assetDao = new model_asset_assetcard_assetcard();
			    $requireDao = new model_asset_require_requirement();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_borrow";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_borrow", "JY" ,$thisDate,$object['applyCompanyCode'],'固定资产借用单',true);
		       	}else{
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_borrow", "JY" ,$thisDate,$object['applyCompanyCode'],'固定资产借用单',false);
		       	}
				/*s:1.保存主表基本信息*/
				$object['docStatus'] = 'WGH';
				$id = parent :: add_d($object, true);
				/*e:1.保存主表基本信息*/
				/*s:2.保存从表资产信息*/
				$borrowitemDao = new model_asset_daily_borrowitem();
				$itemsObjArr = $object['borrowitem'];
				$itemsArr = $this->setItemMainId("borrowId", $id, $itemsObjArr);
				$itemsObj = $borrowitemDao->saveDelBatch($itemsArr);
                 //设置资产卡片状态为已借出
				$assetArr = array();
				foreach( $itemsObjArr as $key=>$val ){
					$assetArr[] = $val['assetId'];
				}
				$assetDao->setIdleStatus($assetArr,'1');
                 //更新需求已发货数量
				$this->updateRequireExeNum($itemsArr);
				$requireDao->updateOutStatus($object['requireId']);
	            //处理附件名称和Id
			     $this->updateObjWithFile($id);
				//发送邮件
			     $this->mailDeal_d('assetBorrow',$object['chargeManId'],array(id => $id));
				/*e:2.保存从表资产信息*/
          		//改变资产申请状态
          		$requirementId = $object['requireId'];
          		$requireDao->updateRecognize($requirementId);
				$this->commit_d();
				return 1;
			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}
	
	/**
	 * 修改保存
	 * @desription 添加保存方法
	 * @date 2011-11-21
	 * @chenzb
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			if (is_array($object['borrowitem'])) {
				/*s:1.保存主表基本信息*/
				//$codeDao = new model_common_codeRule ();
				$oldObj = $this->get_d($object['id']);
				$id = parent :: edit_d($object, true);
				/*e:1.保存主表基本信息*/
				/*s:2.保存从表资产信息*/
				$borrowitemDao = new model_asset_daily_borrowitem();
				$assetDao = new model_asset_assetcard_assetcard();
				$itemsObjArr = $object['borrowitem'];
				$itemsArr = $this->setItemMainId("borrowId", $object['id'], $itemsObjArr);
				$itemsObj = $borrowitemDao->saveDelBatch($itemsArr);
				$unUseArr = array();
				$isUseArr = array();
				foreach( $oldObj['details'] as $key=>$val ){
					$unUseArr[]=$val['assetId'];
				}
				foreach( $itemsObjArr as $key=>$val ){
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
				/*e:2.保存从表资产信息*/
				$this->commit_d();
				return true;
			} else {
				throw new Exception("单据信息不完整，请确认！");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * 资产调拨审批后处理操作
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
				$dataDao = new model_system_datadict_datadict ();
				$cardDao = new model_asset_assetcard_assetcard();
				//获取对应的变动数据
				foreach ( $details as $key=>$val ){
					$cardObjs = array();
					//资产编码、使用部门、使用人
					$cardObjs['oldId']=$val['assetId'];
					$cardObjs['assetCode']=$val['assetCode'];
					$cardObjs['useOrgId']=$obj['deptId'];
					$cardObjs['useOrgName']=$obj['deptName'];
					$cardObjs['userId']=$obj['reposeManId'];
					$cardObjs['userName']=$obj['reposeMan'];
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
		$borrowItemDao = new model_asset_daily_borrowitem();
		$sql = "select (select count(0) from ".$borrowItemDao->tbl_name." where borrowId=$id) as itemNum ,
				(select count(0) from ".$borrowItemDao->tbl_name." where borrowId=$id and isReturn=0) as remainNum";
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
	 * 根据id修改字段值
	 */
	 function setFields($condition,$field,$value){
	 	$this->updateField($condition,$field,$value);
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
    		$dailDao->ctDealRelInfoAtSign($id,'oa_asset_borrow');
    		//改变资产申请状态
    		$requirementId = $this->getRequirementId($id); //获取签收对应的资产需求申请id
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
}