<?php
/**
 * @author show
 * @Date 2014��09��01��
 * @version 1.0
 * @description:����ת�ʲ����� Model��
 */
 class model_asset_require_requirein extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requirein";
		$this->sql_map = "asset/require/requireinSql.php";
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
			if (is_array ( $object ['items'] )) {
		       	$sql = "SELECT MAX(applyDate) as applyDate from oa_asset_requirein";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = $applyDateArr['applyDate'];
		       	$thisDate = day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_requirein", "ZCCK" ,$thisDate,$object['businessBelong'],'�ʲ��������뵥',true);
		       	}else{
					$object ['billNo']=$codeDao->assetRequireCode ( "oa_asset_requirein", "ZCCK" ,$thisDate,$object['businessBelong'],'�ʲ��������뵥',false);
		       	}
		       	$object['outStockStatus']="WCK";//����״̬--δ����

				$id = parent::add_d ( $object, true );
				$requireinitemDao = new model_asset_require_requireinitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id , $object ['items'] );
				$requireinitemDao->saveDelBatch ( $itemsArr );
				if($object['status'] == "��ȷ��"){//�ύʱִ��
					// ����aws
					// �ı��������뵥״̬,��Ϊ������ת�ʲ��С�
					$result = util_curlUtil::getDataFromAWS ( 'asset', 'updateApplyStatus', array (
						'requireId' => $object ['requireId'],
						'applyStatus' => '1042' 
					));
					if($result){
						//�ʼ�֪ͨ�����Ա���г���ȷ��
						$this->mailDeal_d('assetRequireinConfirm',null,array('id' => $id));
						//�ʼ�֪ͨ�����˵��ݽ���
						$this->mailDeal_d('assetRequireinStep1',$object ['applyId'],array('id' => $id));
					}
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
				parent::edit_d ( $object, true );
				$id = $object ['id'];
				$requireinitemDao = new model_asset_require_requireinitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id, $object ['items'] );
				$requireinitemDao->saveDelBatch ( $itemsArr );
				if($object['status'] == "��ȷ��"){//�ύʱִ��
					// ����aws
					// �ı��������뵥״̬,��Ϊ������ת�ʲ��С�
					$result = util_curlUtil::getDataFromAWS ( 'asset', 'updateApplyStatus', array (
							'requireId' => $object ['requireId'],
							'applyStatus' => '1042'
					));
					if($result){
						//�ʼ�֪ͨ�����Ա���г���ȷ��
						$this->mailDeal_d('assetRequireinConfirm',null,array('id' => $id));
						//�ʼ�֪ͨ�����˵��ݽ���
						$this->mailDeal_d('assetRequireinStep1',$object ['applyId'],array('id' => $id));
					}
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
   		$requireinitemDao = new model_asset_require_requireinitem();
		$requireinitemDao->searchArr ['mainId'] = $id;
		$object ['items'] = $requireinitemDao->listBySqlId ();
		return $object;
	}
	
	/**
	 * ���ֳ������
	 */
	function updateAsOut($rows) {
		$itemDao = new model_asset_require_requireinitem();
		//�������ϳ�������
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//���·���״̬
		$this->updateOutStatus($id);
		//����������������ת�ʲ�״̬
// 		$this->updateRequireInStatus($id);
	}

	/**
	 * ���ֳ��ⵥ�����
	 */
	function updateAsAutiAudit($rows) {
		$itemDao = new model_asset_require_requireinitem();
		$rows['outNum'] = $rows['outNum']*(-1);
		//�������ϳ�������
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//���·���״̬
		$this->updateOutStatus($id);
		//����������������ת�ʲ�״̬
// 		$this->updateRequireInStatus($id);
	}
	
	/**
	 * ���ֳ������
	 */
	function updateAsRedOut($rows) {
		$itemDao = new model_asset_require_requireinitem();
		$rows['outNum'] = $rows['outNum']*(-1);
		//�������ϳ�������
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//���·���״̬
		$this->updateOutStatus($id);
		//����������������ת�ʲ�״̬
// 		$this->updateRequireInStatus($id);
	}
	
	/**
	 * ���ֳ��ⷴ���
	 */
	function updateAsRedAutiAudit($rows) {
		$itemDao = new model_asset_require_requireinitem();
		//�������ϳ�������
		$itemDao->updateOutNum($rows['relDocItemId'],$rows['outNum']);
		$id = $rows['relDocId'];
		//���·���״̬
		$this->updateOutStatus($id);
		//����������������ת�ʲ�״̬
// 		$this->updateRequireInStatus($id);
	}
	
	/**
	 * ����id��������ĳ���״̬
	 */
	 function updateOutStatus($id){
	 	$sql = "select count(*) as countNum,(select sum(o.executedNum) from oa_asset_requireinitem o where o.mainId=".$id." )
	 			as executeNum from (select (e.number-e.executedNum) as remainNum from oa_asset_requireinitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
		$remainNum = $this->_db->getArray( $sql );
		$outStockStatus = '';
	 	if( $remainNum[0]['countNum'] <= 0 ){//�ѳ���
	 		$outStockStatus = 'YCK';
	 	}elseif( $remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0 ){//δ����
	 		$outStockStatus = 'WCK';
		} else {//���ֳ���
	 		$outStockStatus = 'BFCK';
	 	}
	 	$statusInfo = array(
	 		'id' => $id,
	 		'outStockStatus' => $outStockStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }
	 
	 /**
	  * ����id�������������״̬
	  */
	 function updateReceiveStatus($id){
	 	$sql = "select count(*) as countNum,(select sum(o.receiveNum) from oa_asset_requireinitem o where o.mainId=".$id." )
	 			as receiveNum from (select (e.number-e.receiveNum) as remainNum from oa_asset_requireinitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
	 	$remainNum = $this->_db->getArray( $sql );
	 	$receiveStatus = '0';//δ����
	 	if( $remainNum[0]['countNum'] <= 0 ){//������
	 		$receiveStatus = '2';
	 	}elseif( $remainNum[0]['countNum'] > 0 && $remainNum[0]['receiveNum'] > 0 ){//��������
	 		$receiveStatus = '1';
	 	}
	 	$statusInfo = array(
 			'id' => $id,
 			'receiveStatus' => $receiveStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }
	 
	 /**
	  * ����id��������ĵ���״̬
	  */
	 function updateStatus($id){
	 	$sql = "select sum(cardNum) as cardNum,sum(number)-sum(cardNum) as num from oa_asset_requireinitem where mainId = ".$id;
	 	$rs = $this->_db->getArray( $sql );
	 	if($rs[0]['num'] == 0){//�����
	 		$status = '�����';
	 	}elseif ($rs[0]['cardNum'] > 0){
	 		$status = '�������';
	 	}
	 	$statusInfo = array(
 			'id' => $id,
 			'status' => $status
	 	);
	 	$this->updateById( $statusInfo );
	 }

	 /**
	  * �����������������ת�ʲ�״̬
	  */
	 function updateRequireInStatus($id,$status = null){
	 	$rs = $this->find(array('id'=>$id),null,'requireId');
	 	$requireId = $rs['requireId'];//��������id
	 	if(empty($status)){
	 		//��ȡ������ϸ
	 		$sql = "select sum(number) as number from oa_asset_requireitem where mainId = ".$requireId;
	 		$rs = $this->_db->getArray( $sql );
	 		$requireitemNum = $rs[0]['number'];
	 		//��ȡ����ת�ʲ���ϸ
	 		$sql = "
	 				SELECT
						SUM(m.number) AS number,
						SUM(m.executedNum) AS executedNum,
	 					SUM(m.receiveNum) AS receiveNum,
						SUM(m.cardNum) AS cardNum
					FROM
						oa_asset_requireinitem m
					WHERE
						mainId IN (
							SELECT
								n.id
							FROM
								oa_asset_requirein n
							WHERE
								n.requireId = ".$requireId.")";
	 		$rs = $this->_db->getArray( $sql );
	 		$inNumber = $rs[0]['number'];
	 		$inExecutedNum = $rs[0]['executedNum'];
	 		$inReceiveNum = $rs[0]['receiveNum'];
	 		$inCardNum = $rs[0]['cardNum'];
	 		//״̬��ʾ˳�򣺴�����>�������ʲ���Ƭ>������
	 		if($requireitemNum == $inCardNum){//�����
	 			$status = '4';
	 		}elseif($inExecutedNum > $inReceiveNum){//������
	 			$status = '2';
	 		}elseif($inReceiveNum > $inCardNum){//�������ʲ���Ƭ
	 			$status = '3';
	 		}elseif($inNumber > $inExecutedNum){//������
	 			$status = '1';
	 		}else{//Ĭ��״̬
	 			$status = '0';
	 		}
	 	}
	 	$requirementDao = new model_asset_require_requirement();
	 	$statusInfo = array(
 			'id' => $requireId,
 			'requireInStatus' => $status
	 	);
	 	$requirementDao->updateById( $statusInfo );
	 }
	 
	 /**
	  * ɾ������ʹӱ���Ϣ
	  */
	 function deletes_d($id) {
	 	try {
	 		$this->start_d ();
	 
	 		$itemDao = new model_asset_require_requireinitem();
	 		$itemDao->delete(array('mainId'=>$id));
	 		$this->deletes($id);
	 
	 		$this->commit_d ();
	 		return true;
	 	} catch (Exception $e) {
	 		$this->rollBack ();
	 		return false;
	 	}
	 }
	 
	 /**
	  * ȷ���ɲֹ��ʲ�����
	  */
	 function confirm_d($id){
	 	try {
	 		$this->start_d ();
	 		
	 		$object = $this->get_d($id);
	 		// ����״̬�޸�Ϊ��ȷ��
			$this->updateById(array('id' => $id, 'status' => '��ȷ��', 'confirmId' => $_SESSION['USER_ID'],
				 			'confirmName' => $_SESSION['USERNAME'], 'confirmTime' => date('Y-m-d H:i:s')));
	 	
	 		// �ʼ�֪ͨ�����Ա���г���
	 		$this->mailDeal_d('assetRequirein',null,array('id' => $id));
	 		//�ʼ�֪ͨ�����˵��ݽ���
	 		$this->mailDeal_d('assetRequireinStep2',$object ['applyId'],array('id' => $id));
	 		
	 		$this->commit_d ();
	 		return true;
	 	} catch (Exception $e) {
	 		$this->rollBack();
	 		return false;
	 	}
	 }
	 
	 /**
	  *��ص���
	  */
	 function back_d($object) {
	 	try {
	 		$this->start_d ();
	 		
	 		// ����״̬�޸�Ϊ���
	 		$object['status'] = '���';
	 		parent :: edit_d($object, true);
	 
	 		// �ʼ�֪ͨ
	 		if($object['mailInfo']['issend'] == 'y'){
	 			$id = $object['id'];
	 			$this->mailDeal_d('assetRequireinBack',$object['mailInfo']['TO_ID'],array('id' => $id));
	 			//�ʼ�֪ͨ�����˵��ݽ���
	 			$this->mailDeal_d('assetRequireinStep3',$object ['applyId'],array('id' => $id));
	 		}
	 			
	 		$this->commit_d ();
	 		return true;
	 	} catch (Exception $e) {
	 		$this->rollBack();
	 		return false;
	 	}
	 }
 }