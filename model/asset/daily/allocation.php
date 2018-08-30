<?php

/**
 * �ʲ�����model����
 */
class model_asset_daily_allocation extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_allocation";
		$this->sql_map = "asset/daily/allocationSql.php";
		parent::__construct ();

	}



	/*===================================ҵ����======================================*/

		/**
	 * ���ù����ӱ�����뵥id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}

	/* @desription ����id��ȡ���뵥���в�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-8-17
	 */
	function get_d($id) {
		$allocationitemDao = new model_asset_daily_allocationitem ();
		$allocationitemDao->searchArr ['allocateID'] = $id;
		$items = $allocationitemDao->listBySqlId ();
		$allocation = parent::get_d ( $id );
		$allocation ['details'] = $items; //details��c���ȡ
		return $allocation;
	}
	/**
	 * @desription ��ӱ��淽��
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
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_allocation", "DB" ,$thisDate,$object['applyCompanyCode'],'�̶��ʲ�������',true);
		       	}else{
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_allocation", "DB" ,$thisDate,$object['applyCompanyCode'],'�̶��ʲ�������',false);
		       	}
				/*s:1.�������������Ϣ*/
				$id = parent::add_d ( $object, true );
				/*e:1.�������������Ϣ*/
				/*s:2.����ӱ��ʲ���Ϣ*/
				$allocationitemDao = new model_asset_daily_allocationitem ();
				$itemsObjArr = $object ['allocationitem'];
				$itemsArr = $this->setItemMainId ( "allocateID", $id, $itemsObjArr );
				$itemsObj = $allocationitemDao->saveDelBatch ( $itemsArr );
				/*e:2.����ӱ��ʲ���Ϣ*/

				$this->commit_d ();
				return $id;
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}
	/**
	 * �޸ı���
	* @desription ��ӱ��淽��
	 * @date 2011-11-21
	 * @chenzb
	 */
	function edit_d($object) {
		try {
			$this->start_d ();

			if (is_array ( $object ['allocationitem'] )) {
				/*s:1.�������������Ϣ*/
				//$codeDao = new model_common_codeRule ();
				$id = parent::edit_d ( $object, true );
				/*e:1.�������������Ϣ*/
				/*s:2.����ӱ��ʲ���Ϣ*/
				$allocationitemDao = new model_asset_daily_allocationitem ();
				$itemsObjArr = $object ['allocationitem'];
				$itemsArr = $this->setItemMainId ( "allocateID",  $object ['id'] , $itemsObjArr );

				$itemsObj = $allocationitemDao->saveDelBatch ( $itemsArr );
				/*e:2.����ӱ��ʲ���Ϣ*/
				$this->commit_d ();
				return true;
        } else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * �ʲ����������������
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
				//��ȡ��Ӧ�ı䶯����
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
					//����-���ŵ���
					if(isset($cardObjs['orgId'])){
						//������������
						$rs = $cardDao->getParentDept_d($cardObjs['orgId']);
						$cardObjs['parentOrgId'] = $rs[0]['parentId'];
						$cardObjs['parentOrgName'] = $rs[0]['parentName'];
					}
					//����-�������
					if(isset($cardObjs['agencyCode'])){
						//��ȡ��������������Ϣ
						$agencyDao = new model_asset_basic_agency();//��������
						$rs = $agencyDao->find(array('agencyCode' => $cardObjs['agencyCode']),null,'chargeId,chargeName');
						$userId = $rs['chargeId'];
						$userName = $rs['chargeName'];
						//��ȡ������Ϣ
						$deptInfo = $cardDao->getDeptInfo_d($userId);
						$deptId = $deptInfo[0]['deptId']; //����id
						$deptName = $deptInfo[0]['deptName']; //��������
						$parentDeptId = $deptInfo[0]['parentId']; //��������id
						$parentDeptName = $deptInfo[0]['parentName']; //������������
						//ʹ���ˣ�ʹ�ò��ţ������ˣ�����������Ϣ
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
					//���뿨Ƭ�࣬��ӱ䶯��¼
					if($cardDao->changeByObj_d($cardObjs,$relInfo)){
						$dailyDao->setRelEquAllocateStatus($val['assetId']);
						$flag = true;
					}
				}
				$this->toMail_d($obj['proposerId'],$obj);
			}else{
				throw new Exception("������Ϣ����������ȷ��!");
			}

			$this->commit_d();
			return $flag;
		}catch(Exception $e ){
			$this->rollBack();
			return $flag;
		}
	}

    /**
//     * �ʼ�����
//     * 2013��1��28�� 07:50:20
//     * zengzx
//     */
//    function toMail_d($emailArr,$object){
//        $addMsg = '������ĵ�����������ͨ��������������Ϊ��'.$object['billNo']
//        		.')���뾡��ȷ�ϡ�';
//        $emailDao = new model_common_mail();
//        $emailInfo = $emailDao->mailClear('�ʲ�������������Ϣ',$emailArr,$addMsg);
//    }

    	/**
	 * �����ƻ��´���ʼ�
	 * TODO:@param mailman string �����ʼ��ˣ�����չ��
	 */
	function toMail_d($emailArr,$object) {
		include (WEB_TOR . "model/common/mailConfig.php");
		$this->mailArr = $mailUser[$this->tbl_name];
		$addMsg = $this->getAddMes_d($object);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '����ͨ��', $object['billNo'], $emailArr, $addMsg, '1');
	}
	/**
	 * �ʼ��и���������Ϣ
	 */
	function getAddMes_d($object) {
		if (is_array($object['details'])) {
			$j = 0;
			$addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>�ʲ����</td><td>�ʲ�����</td><td>��������</td><td>����ͺ�</td><td>��ֵ</td><td>��ע</td></tr>";
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
			//							"<br><span color='red'>�����б����б���ɫΪ��ɫ�����ϣ�˵���������ǽ�����ת���۵ġ�</span></br>";
		}
		return $addmsg;
	}

	function getBillDept($object){
		$userDao = new model_deptuser_user_user();
		$proposerObj = $userDao->getUserById($object['proposerId']);
		$recipientObj = $userDao->getUserById($object['recipientId']);
		//��������
		$outDeptId = $proposerObj['DEPT_ID'];
		//���벿��
		$inDeptId = $recipientObj['DEPT_ID'];
		$deptId = $outDeptId.','.$inDeptId;
		return $deptId;
	}
}
?>