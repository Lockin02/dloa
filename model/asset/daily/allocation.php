<?php

/**
 * 资产调拨model层类
 */
class model_asset_daily_allocation extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_allocation";
		$this->sql_map = "asset/daily/allocationSql.php";
		parent::__construct ();

	}



	/*===================================业务处理======================================*/

		/**
	 * 设置关联从表的申请单id信息
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}

	/* @desription 根据id获取申请单所有产品信息
	 * @param tags
	 * @date 2011-8-17
	 */
	function get_d($id) {
		$allocationitemDao = new model_asset_daily_allocationitem ();
		$allocationitemDao->searchArr ['allocateID'] = $id;
		$items = $allocationitemDao->listBySqlId ();
		$allocation = parent::get_d ( $id );
		$allocation ['details'] = $items; //details被c层获取
		return $allocation;
	}
	/**
	 * @desription 添加保存方法
	 * @date 2011-11-21
	 * @chenzb
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['allocationitem'] )) {
				$codeDao = new model_common_codeRule ();
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_allocation";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_allocation", "DB" ,$thisDate,$object['applyCompanyCode'],'固定资产调拨单',true);
		       	}else{
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_allocation", "DB" ,$thisDate,$object['applyCompanyCode'],'固定资产调拨单',false);
		       	}
				/*s:1.保存主表基本信息*/
				$id = parent::add_d ( $object, true );
				/*e:1.保存主表基本信息*/
				/*s:2.保存从表资产信息*/
				$allocationitemDao = new model_asset_daily_allocationitem ();
				$itemsObjArr = $object ['allocationitem'];
				$itemsArr = $this->setItemMainId ( "allocateID", $id, $itemsObjArr );
				$itemsObj = $allocationitemDao->saveDelBatch ( $itemsArr );
				/*e:2.保存从表资产信息*/

				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
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
			$this->start_d ();

			if (is_array ( $object ['allocationitem'] )) {
				/*s:1.保存主表基本信息*/
				//$codeDao = new model_common_codeRule ();
				$id = parent::edit_d ( $object, true );
				/*e:1.保存主表基本信息*/
				/*s:2.保存从表资产信息*/
				$allocationitemDao = new model_asset_daily_allocationitem ();
				$itemsObjArr = $object ['allocationitem'];
				$itemsArr = $this->setItemMainId ( "allocateID",  $object ['id'] , $itemsObjArr );

				$itemsObj = $allocationitemDao->saveDelBatch ( $itemsArr );
				/*e:2.保存从表资产信息*/
				$this->commit_d ();
				return true;
        } else {
				throw new Exception ( "单据信息不完整，请确认！" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
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
				$dataDao = new model_system_datadict_datadict();
				$dailyDao = new model_asset_daily_dailyCommon();
				$cardDao = new model_asset_assetcard_assetcard();
				//获取对应的变动数据
				foreach ( $details as $key => $val ){
					$cardObjs = array();
					$cardObjs['oldId'] = $val['assetId'];
					$cardObjs['assetCode'] = $val['assetCode'];
					if( $obj['inDeptId'] != '' && $obj['inDeptId'] != 0){
						$cardObjs['orgId'] = $obj['inDeptId'];
						$cardObjs['orgName'] = $obj['inDeptName'];
					}
					if($obj['inAgencyCode'] != ''){
						$cardObjs['agencyCode'] = $obj['inAgencyCode'];
						$cardObjs['agencyName'] = $obj['inAgencyName'];
					}
					//部门-部门调拨
					if(isset($cardObjs['orgId'])){
						//所属二级部门
						$rs = $cardDao->getParentDept_d($cardObjs['orgId']);
						$cardObjs['parentOrgId'] = $rs[0]['parentId'];
						$cardObjs['parentOrgName'] = $rs[0]['parentName'];
					}
					//区域-区域调拨
					if(isset($cardObjs['agencyCode'])){
						//获取行政区域负责人信息
						$agencyDao = new model_asset_basic_agency();//行政区域
						$rs = $agencyDao->find(array('agencyCode' => $cardObjs['agencyCode']),null,'chargeId,chargeName');
						$userId = $rs['chargeId'];
						$userName = $rs['chargeName'];
						//获取部门信息
						$deptInfo = $cardDao->getDeptInfo_d($userId);
						$deptId = $deptInfo[0]['deptId']; //部门id
						$deptName = $deptInfo[0]['deptName']; //部门名称
						$parentDeptId = $deptInfo[0]['parentId']; //二级部门id
						$parentDeptName = $deptInfo[0]['parentName']; //二级部门名称
						//使用人，使用部门，所属人，所属部门信息
						$cardObjs['userId'] = $userId;
						$cardObjs['userName'] = $userName;
						$cardObjs['useOrgId'] = $deptId;
						$cardObjs['useOrgName'] = $deptName;
						$cardObjs['parentUseOrgId'] = $parentDeptId;
						$cardObjs['parentUseOrgName'] = $parentDeptName;
						$cardObjs['belongManId'] = $userId;
						$cardObjs['belongMan'] = $userName;
						$cardObjs['orgId'] = $deptId;
						$cardObjs['orgName'] = $deptName;
						$cardObjs['parentOrgId'] = $parentDeptId;
						$cardObjs['parentOrgName'] = $parentDeptName;
					}
					//进入卡片类，添加变动记录
					if($cardDao->changeByObj_d($cardObjs,$relInfo)){
						$dailyDao->setRelEquAllocateStatus($val['assetId']);
						$flag = true;
					}
				}
				$this->toMail_d($obj['proposerId'],$obj);
			}else{
				throw new Exception("单据信息不完整，请确认!");
			}

			$this->commit_d();
			return $flag;
		}catch(Exception $e ){
			$this->rollBack();
			return $flag;
		}
	}

    /**
//     * 邮件发送
//     * 2013年1月28日 07:50:20
//     * zengzx
//     */
//    function toMail_d($emailArr,$object){
//        $addMsg = '您申请的调拨单已审批通过。（调拨单号为：'.$object['billNo']
//        		.')。请尽快确认。';
//        $emailDao = new model_common_mail();
//        $emailInfo = $emailDao->mailClear('资产调拨单审批信息',$emailArr,$addMsg);
//    }

    	/**
	 * 发货计划下达后邮寄
	 * TODO:@param mailman string 额外邮寄人（待拓展）
	 */
	function toMail_d($emailArr,$object) {
		include (WEB_TOR . "model/common/mailConfig.php");
		$this->mailArr = $mailUser[$this->tbl_name];
		$addMsg = $this->getAddMes_d($object);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '审批通过', $object['billNo'], $emailArr, $addMsg, '1');
	}
	/**
	 * 邮件中附加物料信息
	 */
	function getAddMes_d($object) {
		if (is_array($object['details'])) {
			$j = 0;
			$addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>序号</td><td>资产编号</td><td>资产名称</td><td>购置日期</td><td>规格型号</td><td>残值</td><td>备注</td></tr>";
			foreach ($object['details'] as $key => $equ) {
				$j++;
				$assetCode = $equ['assetCode'];
				$assetName = $equ['assetName'];
				$buyDate = $equ['buyDate'];
				$spec = $equ['spec'];
				$salvage = $equ['salvage'];
				$remark = $equ['remark'];
				$addmsg .=<<<EOT
						<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$assetCode</td><td>$assetName</td><td>$buyDate</td><td>$spec</td><td>$salvage</td><td>$remark</td></tr>
EOT;
			}
			//					$addmsg.="</table>" .
			//							"<br><span color='red'>以上列表若有背景色为绿色的物料，说明该物料是借试用转销售的。</span></br>";
		}
		return $addmsg;
	}

	function getBillDept($object){
		$userDao = new model_deptuser_user_user();
		$proposerObj = $userDao->getUserById($object['proposerId']);
		$recipientObj = $userDao->getUserById($object['recipientId']);
		//调出部门
		$outDeptId = $proposerObj['DEPT_ID'];
		//调入部门
		$inDeptId = $recipientObj['DEPT_ID'];
		$deptId = $outDeptId.','.$inDeptId;
		return $deptId;
	}
}
?>