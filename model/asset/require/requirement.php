<?php
/**
 * @author Administrator
 * @Date 2012��5��11�� 11:41:37
 * @version 1.0
 * @description:�ʲ��������� Model��
 */
 class model_asset_require_requirement  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requirement";
		$this->sql_map = "asset/require/requirementSql.php";
		parent::__construct ();
	}

   /*--------------------------------------------ҵ�����--------------------------------------------*/

   /**
	 * ��������
	 * @see model_base::add_d()
	 */
	function add_d($object) {
		try {
			$this->start_d ();
			$codeDao = new model_common_codeRule ();
			foreach( $object as $key => $val){
				if($val==''){
					unset($object[$key]);
				}
			}
			if (is_array ( $object ['items'] )) {
		       	$sql = "SELECT MAX(applyDate) as applyDate from oa_asset_requirement";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = $applyDateArr['applyDate'];
		       	$thisDate =  day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requirement", "XQ" ,$thisDate,$object['userCompanyCode'],'�̶��ʲ��������뵥',true);
		       	}else{
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requirement", "XQ" ,$thisDate,$object['userCompanyCode'],'�̶��ʲ��������뵥',false);
		       	}
//		       	$object['ExaStatus']="���ύ";
		       	$object['DeliveryStatus']="WFH";
				$id = parent::add_d ( $object, true );
				$object['id']=$id;
				$requireitemDao = new model_asset_require_requireitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id , $object ['items'] );
				$itemsObj = $requireitemDao->saveDelBatch ( $itemsArr );
				//״̬Ϊ�ύ�������ʼ�
				if($object['isSubmit'] == 1 && $object['mailInfo']['issend'] == 'y'){
					$this->mailDeal_d('requirement',$object['mailInfo']['TO_ID'],array(id=>$id));
				}
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
			$this->commit_d ();
			return $id;
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['items'] )) {
				$editResult = parent::edit_d ( $object, true );
				$requireitemDao = new model_asset_require_requireitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $object ['id'], $object ['items'] );
				$itemsObj = $requireitemDao->saveDelBatch ( $itemsArr );
				//״̬Ϊ�ύ�������ʼ�
				if($object['isSubmit'] == 1){
					$this->mailDeal_d('requirement',$object['mailInfo']['TO_ID'],array(id=>$object ['id']));
				}
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
			$this->commit_d ();
			return $object ['id'];
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

   /**
	 * ͨ��id��ȡ��ϸ��Ϣ
	 * @see model_base::get_d()
	 */
	function get_d($id) {
		$object = parent::get_d ( $id );
   	$requireitemDao = new model_asset_require_requireitem();
		$requireitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $requireitemDao->listBySqlId ();
		return $object;
	}

	/**
	 * ����id��������ķ���״̬
	 */
	 function updateOutStatus($id){
	 	$sql = "select count(0) as countNum,(select sum(o.executedNum) from oa_asset_requireitem o where o.mainId=".$id." )
	 			as executeNum from (select e.mainId,(e.number-e.executedNum) as remainNum from oa_asset_requireitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
		$remainNum = $this->_db->getArray( $sql );
		$DeliveryStatus = '';
	 	if( $remainNum[0]['countNum'] <= 0 ){//�ѷ���
	 		$DeliveryStatus='YFH';
	 	}elseif( $remainNum[0]['countNum']>0 && $remainNum[0]['executeNum']==0 ){//δ����
	 		$DeliveryStatus='WFH';
		} else {//���ַ���
	 		$DeliveryStatus='BFFH';
	 	}
	 	$statusInfo = array(
	 		'id' => $id,
	 		'DeliveryStatus' => $DeliveryStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }

	 /**
	  * ����ͨ�����ʼ�֪ͨ
	  */
	 function dealAfterAudit_d($id){
	 	$mainObj = $this->get_d($id);
	 	if($mainObj['ExaStatus'] == '���'){
			$this->mailDeal_d('requireAudit',null,array('id' =>$mainObj['id']));
	 	}
	 }

	/**
	 * ��ز���
	 */
	function backDetail_d($object){
		try{
			$this->start_d();
			//�������ȫ�����أ������״̬
			if($object['id']){
				$this->update(array('id' => $object['id']),array("isRecognize" => 4,'backReason'=>$object['backReason']));
			}
			$backArr = array(
				'requireId'=>$object['id'],
				'backReason'=>$object['backReason']
			);
			$backDao = new model_asset_require_requireback();
			$backArr = $backDao->addCreateInfo ( $backArr );
			$newId = $backDao->create ( $backArr );
			//��ȡ���������Ϣ
			$object = $this->get_d($object['id']);
			//�����ʼ�,�ռ���Ϊ�����ˡ�ʹ����
			$mailId = $object['applyId'].",".$object['userId'];
			$this->mailDeal_d('requirementBack',$mailId,array(id=>$object['id']));
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * ���ز���
	 */
	function rollback_d($id){
		try{
			$this->start_d();
			//�������ȫ�����أ������״̬
			if($id){
				$this->update(array('id' => $id),array("isRecognize" => 2,'isSubmit'=> 0));
			}
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
	}

	/**
	 * �����ʲ�����״̬
	 */
	function updateRecognize($id){
		$recognizeVal = "";
		$applyDao = new model_asset_purchase_apply_apply();
		$receiveItemDao = new model_asset_purchase_receive_receiveItem();
		$borrowDao = new model_asset_daily_borrow();
		$chargeDao = new model_asset_daily_charge();
		$purchAmount = $applyDao->countPurch($id);
		$isCardAmount = $receiveItemDao->countIsCard($id);
		$isSignBorrow = $borrowDao->countIsSign($id);
		$isSignCharge = $chargeDao->countIsSign($id);
		//��ʾ״̬���򣺲ɹ���>�����ʲ���Ƭ>������ǩ��
		//ֻ��������вɹ��������ʲ���Ƭ��������ǩ�ղ������Ž��ʲ���������״̬��Ϊ������ɡ���ֵ����Ϊ8
		if($purchAmount != 0 || $isCardAmount != 0 || $isSignBorrow != 0 || $isSignCharge != 0){
			if($purchAmount != 0 && $isCardAmount != 0){
				//״̬Ϊ���ɹ��С�,ֵ����Ϊ5
				$recognizeVal = 5;
			}elseif ($purchAmount != 0 && ($isSignBorrow != 0 || $isSignCharge != 0)){
				//״̬Ϊ���ɹ��С�,ֵ����Ϊ5
				$recognizeVal = 5;
			}elseif ($isCardAmount != 0 && ($isSignBorrow != 0 || $isSignCharge != 0)){
				//״̬Ϊ�������ʲ���Ƭ��,ֵ����Ϊ6
				$recognizeVal = 6;
			}elseif ($isCardAmount != 0){
				//״̬Ϊ�������ʲ���Ƭ��,ֵ����Ϊ6
				$recognizeVal = 6;
			}elseif ($isSignBorrow != 0 || $isSignCharge != 0){
				//״̬Ϊ��������ǩ�ա�,ֵ����Ϊ7
				$recognizeVal = 7;
			}else {
				//״̬Ϊ���ɹ��С�,ֵ����Ϊ5
				$recognizeVal = 5;
			}
		}else {
			//��ȡ����״̬�����������������״̬������ȷ��״̬
			$rs = $this->find(array('id' => $id),null,'DeliveryStatus');
			if($rs['DeliveryStatus'] == 'YFH'){
				//״̬Ϊ������ɡ�,ֵ����Ϊ8
				$recognizeVal = 8;
			}else{
				//״̬Ϊ����ȷ�ϡ�,ֵ����Ϊ1
				$recognizeVal = 1;
			}
		}
		$this->update(array('id' => $id),array('isRecognize' => $recognizeVal));
	}

	/**
	 * workflow callback
	 */
	 function workflowCallBack($spid){
	 	$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ($spid);
		$objId = $folowInfo ['objId'];
		if($folowInfo ['examines'] == "ok"){
      	 	$this->dealAfterAudit_d($objId);
		}
	 }
 }