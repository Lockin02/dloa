<?php
/**
 * �ʲ�����model����
 */
class model_asset_daily_borrow extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_borrow";
		$this->sql_map = "asset/daily/borrowSql.php";
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

	/** @desription ����id��ȡ���뵥���в�Ʒ��Ϣ
	 * @param tags
	 * @date 2011-8-17
	 */
	function get_d($id) {
		$borrowitemDao = new model_asset_daily_borrowitem();
		$borrowitemDao->searchArr['borrowId'] = $id;
		$items = $borrowitemDao->listBySqlId();
		$borrow = parent :: get_d($id);
		$borrow['details'] = $items; //details��c���ȡ
		return $borrow;
	}
	
	/**
	 * @desription ��ӱ��淽��
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
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_borrow", "JY" ,$thisDate,$object['applyCompanyCode'],'�̶��ʲ����õ�',true);
		       	}else{
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_borrow", "JY" ,$thisDate,$object['applyCompanyCode'],'�̶��ʲ����õ�',false);
		       	}
				/*s:1.�������������Ϣ*/
				$object['docStatus'] = 'WGH';
				$id = parent :: add_d($object, true);
				/*e:1.�������������Ϣ*/
				/*s:2.����ӱ��ʲ���Ϣ*/
				$borrowitemDao = new model_asset_daily_borrowitem();
				$itemsObjArr = $object['borrowitem'];
				$itemsArr = $this->setItemMainId("borrowId", $id, $itemsObjArr);
				$itemsObj = $borrowitemDao->saveDelBatch($itemsArr);
                 //�����ʲ���Ƭ״̬Ϊ�ѽ��
				$assetArr = array();
				foreach( $itemsObjArr as $key=>$val ){
					$assetArr[] = $val['assetId'];
				}
				$assetDao->setIdleStatus($assetArr,'1');
                 //���������ѷ�������
				$this->updateRequireExeNum($itemsArr);
				$requireDao->updateOutStatus($object['requireId']);
	            //���������ƺ�Id
			     $this->updateObjWithFile($id);
				//�����ʼ�
			     $this->mailDeal_d('assetBorrow',$object['chargeManId'],array(id => $id));
				/*e:2.����ӱ��ʲ���Ϣ*/
          		//�ı��ʲ�����״̬
          		$requirementId = $object['requireId'];
          		$requireDao->updateRecognize($requirementId);
				$this->commit_d();
				return 1;
			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
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
			$this->start_d();
			if (is_array($object['borrowitem'])) {
				/*s:1.�������������Ϣ*/
				//$codeDao = new model_common_codeRule ();
				$oldObj = $this->get_d($object['id']);
				$id = parent :: edit_d($object, true);
				/*e:1.�������������Ϣ*/
				/*s:2.����ӱ��ʲ���Ϣ*/
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
                 //���������ѷ�������
				$this->updateRequireExeNum($itemsArr);
				/*e:2.����ӱ��ʲ���Ϣ*/
				$this->commit_d();
				return true;
			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch (Exception $e) {
			$this->rollBack();
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
				$dataDao = new model_system_datadict_datadict ();
				$cardDao = new model_asset_assetcard_assetcard();
				//��ȡ��Ӧ�ı䶯����
				foreach ( $details as $key=>$val ){
					$cardObjs = array();
					//�ʲ����롢ʹ�ò��š�ʹ����
					$cardObjs['oldId']=$val['assetId'];
					$cardObjs['assetCode']=$val['assetCode'];
					$cardObjs['useOrgId']=$obj['deptId'];
					$cardObjs['useOrgName']=$obj['deptName'];
					$cardObjs['userId']=$obj['reposeManId'];
					$cardObjs['userName']=$obj['reposeMan'];
					$cardObjs['useStatusCode']='SYZT-SYZ';
					$cardObjs['useStatusName']=$dataDao->getDataNameByCode('SYZT-SYZ');
					//��ȡʹ�ö���������Ϣ
					$rs = $cardDao->getParentDept_d($obj['deptId']);
					$cardObjs['parentUseOrgId'] = $rs[0]['parentId'];
					$cardObjs['parentUseOrgName'] = $rs[0]['parentName'];
					//���뿨Ƭ�࣬��ӱ䶯��¼
					if($cardDao->changeByObj_d($cardObjs,$relInfo)){
						$flag = true;
					}else{
						throw new Exception("������Ϣ����������ȷ��!");
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
	 * ����id���µ���״̬
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
	 * ����id�޸��ֶ�ֵ
	 */
	 function setFields($condition,$field,$value){
	 	$this->updateField($condition,$field,$value);
	 }

	/**
	 * ���������ѷ�������
	 */
	 function updateRequireExeNum($itemObj){
	 	$itemIdArr = array();
	 	foreach( $itemObj as $key => $val ){
	 		$itemIdArr[] = $val['itemId'];
	 	}
	 	$requireItemDao = new model_asset_require_requireitem();
	 	$requireItemDao->setExeNum($itemIdArr);
	 }
	 
	/******************************ǩ��ҵ��******************************/
	/**
	 * ��ȡǩ�ն�Ӧ���ʲ���������id
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
     * ��ȡͬ���ʲ����������½�������״̬Ϊ��δǩ�ա��ļ�¼��
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
    		$dailDao->ctDealRelInfoAtSign($id,'oa_asset_borrow');
    		//�ı��ʲ�����״̬
    		$requirementId = $this->getRequirementId($id); //��ȡǩ�ն�Ӧ���ʲ���������id
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
     * �Զ�ǩ�յķ���
     */
    function autoSign_d($obj){
    	return $this->sign_d($obj['id']);
    }
}