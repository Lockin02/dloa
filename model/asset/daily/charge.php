<?php

/**
 * �ʲ�����model����
 *@linzx
 */
class model_asset_daily_charge extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_charge";
		$this->sql_map = "asset/daily/chargeSql.php";
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
					$chargeinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_charge", "LY" ,$thisDate,$chargeinfo['applyCompanyCode'],'�̶��ʲ����õ�',true);
		       	}else{
					$chargeinfo ['billNo']=$codeDao->assetRequireCode ( "oa_asset_charge", "LY" ,$thisDate,$chargeinfo['applyCompanyCode'],'�̶��ʲ����õ�',false);
		       	}
				 $chargeinfo['docStatus'] = 'WGH';
                 $id = parent :: add_d($chargeinfo,true);
                 $chargeDao = new model_asset_daily_chargeitem();
                 //�������id�ʹӱ�id��������
                 $itemsArr = $this->setItemMainId ( "allocateID", $id,$chargeinfo['item']);
                 $itemsObj = $chargeDao->saveDelBatch ( $itemsArr );
                 //�����ʲ���Ƭ״̬Ϊ�ѽ��
				$assetArr = array();
				foreach( $chargeinfo['item'] as $key=>$val ){
					$assetArr[] = $val['assetId'];
				}
				$assetDao->setIdleStatus($assetArr,'1');
                 //���������ѷ�������
				$this->updateRequireExeNum($itemsArr);
				$requireDao->updateOutStatus($chargeinfo['requireId']);
	            //���������ƺ�Id
			     $this->updateObjWithFile($id);
          		 //�����ʼ�
          		 $this->mailDeal_d('assetCharge',$chargeinfo['chargeManId'],array(id => $id));
          		 //�ı��ʲ�����״̬
          		 $requirementId = $chargeinfo['requireId'];
          		 $requireDao->updateRecognize($requirementId);
			     $this->commit_d();
			     return 1;
			}else {
				throw new Exception ( "������Ϣ������!" );
				}

		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}

	}

    /**
	 * @desription �޸ı��淽��
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
                 //���������ѷ�������
				$this->updateRequireExeNum($itemsArr);
				$requireDao->updateOutStatus($chargeinfo['requireId']);
			}else {
				throw new Exception ( "������Ϣ������!" );
				}
			$this->commit_d();
			return true;
		}catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

   	/**
	 * ��дget_d����
     * �����ʲ���ID�����뵥���е��ʲ��ó���
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
				$cardDao = new model_asset_assetcard_assetcard();
				//��ȡ��Ӧ�ı䶯����
				foreach ( $details as $key=>$val ){
					$cardObjs = array();
					//�ʲ����롢ʹ�ò��š�ʹ����
					$cardObjs['oldId']=$val['assetId'];
					$cardObjs['assetCode']=$val['assetCode'];
					$cardObjs['useOrgId']=$obj['deptId'];
					$cardObjs['useOrgName']=$obj['deptName'];
					$cardObjs['userId']=$obj['chargeManId'];
					$cardObjs['userName']=$obj['chargeMan'];
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
    		$dailDao->ctDealRelInfoAtSign($id,'oa_asset_charge');
    		//�ı��ʲ�����״̬
    		$requirementId = $this->getRequirementId($id);//��ȡǩ�ն�Ӧ���ʲ���������id
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
    
    /**
     * �ʲ�����Աǩ��ʱȷ��ת���豸
     */
    function signToDevice_d($objName){
    	try{
    		$this->start_d();
    		
    		if(is_array($objName['item'])){
    			$item = $objName['item'];
    			//ȥ��ҳ��ɾ��������
    			foreach ($item as $key => $val){
    				if($val['isDelTag'] == 1){
    					unset($item[$key]);
    				}
    			}
    			if(empty($item)){
    				msg ( '������Ϣ������!' );
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
				//�豸����Ŀ����Ϣ���
				$this->_db->query($sql.$dataSql);
				//ǩ�մ���
				$this->sign_d($objName['id']);
    		}else{
    			msg( "������Ϣ������!" );
    		}
    		
    		$this->commit_d();
    		return true;
    	}catch(Exception $e ){
    		$this->rollBack();
    		return false;
    	}
    }
}