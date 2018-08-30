<?php


/**
 * �ʲ���ʧmodel����
 *@linzx
 */
class model_asset_daily_return extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_return";
		$this->sql_map = "asset/daily/returnSql.php";
		parent :: __construct();

	}

	/*===================================ҵ����======================================*/
	/**
	 * ���ù����ӱ�����뵥id��Ϣ
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
	* @desription ��ӱ��淽��
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
				$codeDao = new model_common_codeRule ();//���ݱ��������				
		       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_return";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$returninfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_return", "GH" ,$thisDate,$returninfo['applyCompanyCode'],'�̶��ʲ��黹��',true);
		       	}else{
					$returninfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_return", "GH" ,$thisDate,$returninfo['applyCompanyCode'],'�̶��ʲ��黹��',false);
		       	}

				$id = parent :: add_d($returninfo, true);
				$returnDao = new model_asset_daily_returnitem();
				//�������id�ʹӱ�id��������
				$itemsArr = $this->setItemMainId("allocateID", $id, $returninfo['item']);
				$itemsObj = $returnDao->saveDelBatch($itemsArr);
			
				$this->commit_d();
				return $id;
			} else {
				throw new Exception("������Ϣ������!");
			}
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}

	/**
		 * @desription �޸ı��淽��
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
				throw new Exception("������Ϣ������!");
			}
			$this->commit_d();
			return true;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	* ��дget_d����
	* �����ʲ���ID�����뵥���е��ʲ��ó���
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
	 * �ʲ��黹�����������
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
				//ͳһʵ����
				$changeDao = new model_asset_change_assetchange();//�䶯��¼
				$dataDao = new model_system_datadict_datadict();//�����ֵ�
				$cardDao = new model_asset_assetcard_assetcard();//�ʲ���Ƭ
				$dailyDao = new model_asset_daily_dailyCommon();//�̶��ʲ�������
				$agencyDao = new model_asset_basic_agency();//��������
				$userDao = new model_deptuser_user_user();//��Ա��Ϣ
				//��ȡ��������������Ϣ
				$agency = $agencyDao->find(array('agencyCode' => $obj['agencyCode']),null,'chargeId');
				$user = $userDao->getUserById($agency['chargeId']);
				//��ȡ��Ӧ�ı䶯����
				foreach ($details as $key => $val) {
					$cardObjs = array ();
					//�黹��ʹ����/��������Ϣ��Ϊ��������������Ϣ
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
					//��ȡ�����������˶���������Ϣ
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

					//���뿨Ƭ�࣬��ӱ䶯��¼
					if ($cardDao->changeByObj_d($cardObjs, $relInfo)) {
						if ($obj['returnType'] != 'other' && $obj['returnType'] != 'oa_asset_') {
							$dailyDao->setRelEquReturnStatus($obj['borrowId'], $obj['returnType'], $val['assetId']);
						}
					} else {
						$flag = false;
						throw new Exception("������Ϣ����������ȷ��!");
					}
				}
				if ($obj['returnType'] != 'other' && $obj['returnType'] != 'oa_asset_') {
					if ($dailyDao->setRelReturnStatus($obj['borrowId'], $obj['returnType'])) {
						;
					} else {
						$flag = false;
						throw new Exception("������Ϣ����������ȷ��!");
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
	 * ǩ��
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
		 	//���黹��Ϊ�麣�з����ʲ�����Ա�����ִ�������ʲ�����Ա��ʱ�򣬼�ȥ�豸������Ӧ�Ŀ��
		 	$returnManId = $obj['returnManId'];
		 	if($returnManId == 'ZHYFZCGLY' || $returnManId == 'FWZXZXZCGLY'){
		 		$cardIdArr = array();
		 		foreach ($obj['details'] as $val){
		 			array_push($cardIdArr, $val['assetId']);
		 		}
		 		$cardIds = implode(',',$cardIdArr);
		 		if(!empty($cardIds)){
		 			// ɾ����Ӧ�豸���
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