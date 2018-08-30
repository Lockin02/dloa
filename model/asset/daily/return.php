<?php


/**
 * 资产遗失model层类
 *@linzx
 */
class model_asset_daily_return extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_return";
		$this->sql_map = "asset/daily/returnSql.php";
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
	function add_d($returninfo) {
		try {
			$this->start_d();
			
			$iteminfoArr = $returninfo['item'];
			foreach( $iteminfoArr as $key => $val ){
				if($val['isDelTag']==1){
					unset($iteminfoArr[$key]);
				}
			}
			if (is_array($iteminfoArr)&&count($iteminfoArr)>0) {
				$codeDao = new model_common_codeRule ();//单据编号生成类				
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_return";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$returninfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_return", "GH" ,$thisDate,$returninfo['applyCompanyCode'],'固定资产归还单',true);
		       	}else{
					$returninfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_return", "GH" ,$thisDate,$returninfo['applyCompanyCode'],'固定资产归还单',false);
		       	}

				$id = parent :: add_d($returninfo, true);
				$returnDao = new model_asset_daily_returnitem();
				//将主表的id和从表id关联起来
				$itemsArr = $this->setItemMainId("allocateID", $id, $returninfo['item']);
				$itemsObj = $returnDao->saveDelBatch($itemsArr);
			
				$this->commit_d();
				return $id;
			} else {
				throw new Exception("单据信息不完整!");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}

	/**
		 * @desription 修改保存方法
		 * @linzx
		 */
	function edit_d($returninfo) {
		try {
			$this->start_d();

			if (is_array($returninfo['item'])) {
				$id = parent :: edit_d($returninfo, true);
				$returnDao = new model_asset_daily_returnitem();
				$itemsArr = $this->setItemMainId("allocateID", $returninfo['id'], $returninfo['item']);
				$itemsObj = $returnDao->saveDelBatch($itemsArr);
			} else {
				throw new Exception("单据信息不完整!");
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	* 重写get_d方法
	* 根据资产的ID将申请单所有的资产拿出来
	* @linzx
	*/
	function get_d($id) {
		$returnitemDao = new model_asset_daily_returnitem();
		$returnitemDao->searchArr['allocateID'] = $id;
		$returnitem = $returnitemDao->listBySqlId();
		$returniteminfo = parent :: get_d($id);
		$returniteminfo['details'] = $returnitem;
		return $returniteminfo;
	}

	/**
	 * 资产归还审批后处理操作
	 * @author zengzx
	 * @since 1.0 - 2011-11-29
	 */
	function dealRelInfoAtAudit($id, $relInfo) {
		try {
			$this->start_d();
			$flag = true;
			$obj = $this->get_d($id);
			$details = $obj['details'];
			if ( array( $details ) ) {
				//统一实例化
				$changeDao = new model_asset_change_assetchange();//变动记录
				$dataDao = new model_system_datadict_datadict();//数据字典
				$cardDao = new model_asset_assetcard_assetcard();//资产卡片
				$dailyDao = new model_asset_daily_dailyCommon();//固定资产公用类
				$agencyDao = new model_asset_basic_agency();//行政区域
				$userDao = new model_deptuser_user_user();//人员信息
				//获取行政区域负责人信息
				$agency = $agencyDao->find(array('agencyCode' => $obj['agencyCode']),null,'chargeId');
				$user = $userDao->getUserById($agency['chargeId']);
				//获取对应的变动数据
				foreach ($details as $key => $val) {
					$cardObjs = array ();
					//归还后，使用人/所属人信息置为行政区域负责人信息
					$cardObjs['oldId'] = $val['assetId'];
					$cardObjs['assetCode'] = $val['assetCode'];
					$cardObjs['userId'] = $user['USER_ID'];
					$cardObjs['userName'] = $user['USER_NAME'];
					$cardObjs['useOrgId'] = $user['DEPT_ID'];
					$cardObjs['useOrgName'] = $user['DEPT_NAME'];
					$cardObjs['belongManId'] = $user['USER_ID'];
					$cardObjs['belongMan'] = $user['USER_NAME'];
					$cardObjs['orgId'] = $user['DEPT_ID'];
					$cardObjs['orgName'] = $user['DEPT_NAME'];
					$cardObjs['useProId'] = '';
					$cardObjs['useProCode'] = '';
					$cardObjs['useProName'] = '';
					$cardObjs['useStatusCode'] = 'SYZT-XZ';
					$cardObjs['idle'] = '0';
					$cardObjs['useStatusName'] = $dataDao->getDataNameByCode('SYZT-XZ');
					$cardObjs['agencyCode'] = $obj['agencyCode'];
					$cardObjs['agencyName'] = $obj['agencyName'];
					//获取行政区域负责人二级部门信息
					$rs = $cardDao->getParentDept_d($user['DEPT_ID']);
					$parentDeptId = $rs[0]['parentId'];
					$parentDeptName = $rs[0]['parentName'];
					$cardObjs['parentUseOrgId'] = $parentDeptId;
					$cardObjs['parentUseOrgName'] = $parentDeptName;
					$cardObjs['parentOrgId'] = $parentDeptId;
					$cardObjs['parentOrgName'] = $parentDeptName;

					$condition = array(
						'assetId'=>$val['assetId']
					);
					$rows = $changeDao ->find($condition,'id desc');
					if(is_array($rows)&&count($rows)>0){
						$rows['businessType']='oa_asset_'.$rows['businessType'];
						if( $obj['returnType'] !='other' ){
							$obj['returnType']=$rows['businessType'];
							if( !isset($obj['borrowId'])||$obj['borrowId']=='' ){
								$obj['borrowId']=$rows['businessId'];
							}
						}
					}

					//进入卡片类，添加变动记录
					if ($cardDao->changeByObj_d($cardObjs, $relInfo)) {
						if ($obj['returnType'] != 'other' && $obj['returnType'] != 'oa_asset_') {
							$dailyDao->setRelEquReturnStatus($obj['borrowId'], $obj['returnType'], $val['assetId']);
						}
					} else {
						$flag = false;
						throw new Exception("单据信息不完整，请确认!");
					}
				}
				if ($obj['returnType'] != 'other' && $obj['returnType'] != 'oa_asset_') {
					if ($dailyDao->setRelReturnStatus($obj['borrowId'], $obj['returnType'])) {
						;
					} else {
						$flag = false;
						throw new Exception("单据信息不完整，请确认!");
					}
				}
			}
			$this->commit_d();
			return $flag;
		} catch (Exception $e) {
			$this->rollBack();
			return $flag;
		}
	}

	function getAssetIdByDocId($docId,$docType){
		$sql = "select ri.assetId from oa_asset_returnitem ri RIGHT JOIN oa_asset_return r
				ON (r.id = ri.allocateID) WHERE r.borrowId=".$docId." AND returnType='".$docType."'";
		$idArr = $this->_db->getArray( $sql );
		if(is_array($idArr)&&count($idArr)>0){
			$assetId = array();
			foreach($idArr as $key=>$val){
				$assetId[]=$val['assetId'];
			}
		}
		$assetIds = implode(',',$assetId);
		return $assetIds;
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
		 	$dailDao->ctDealRelInfoAtSign($id,'oa_asset_return');
		 	$obj = $this->get_d($id);
		 	//当归还人为珠海研发部资产管理员或服务执行中心资产管理员的时候，减去设备管理相应的库存
		 	$returnManId = $obj['returnManId'];
		 	if($returnManId == 'ZHYFZCGLY' || $returnManId == 'FWZXZXZCGLY'){
		 		$cardIdArr = array();
		 		foreach ($obj['details'] as $val){
		 			array_push($cardIdArr, $val['assetId']);
		 		}
		 		$cardIds = implode(',',$cardIdArr);
		 		if(!empty($cardIds)){
		 			// 删除相应设备库存
		 			$sql = "DELETE FROM device_info WHERE assetCardId IN($cardIds)";
		 			$this->_db->query($sql);
		 		}
		 	}
		 	
			$this->commit_d();
			return 1;
		}catch(Exception $e ){
			$this->rollBack();
			return 0;
		}
	 }


}