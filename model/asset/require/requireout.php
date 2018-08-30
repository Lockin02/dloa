<?php
/**
 * @author show
 * @Date 2014��09��01��
 * @version 1.0
 * @description:�ʲ�ת�������� Model��
 */
 class model_asset_require_requireout extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_requireout";
		$this->sql_map = "asset/require/requireoutSql.php";
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
		       	$sql = "SELECT MAX(applyDate) as applyDate from oa_asset_requireout";
		       	$applyDateArr = $this->_db->get_one($sql);
		       	$applyDate = $applyDateArr['applyDate'];
		       	$thisDate = day_date;
		       	if( $applyDate!= $thisDate ){
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requireout", "ZCRK" ,$thisDate,$object['businessBelong'],'�ʲ�������뵥',true);
		       	}else{
					$object ['requireCode']=$codeDao->assetRequireCode ( "oa_asset_requireout", "ZCRK" ,$thisDate,$object['businessBelong'],'�ʲ�������뵥',false);
		       	}
		       	$object['inStockStatus']="WRK";//���״̬--δ���
				$id = parent::add_d ( $object, true );
				$requireoutitemDao = new model_asset_require_requireoutitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $id , $object ['items'] );
				$requireoutitemDao->saveDelBatch ( $itemsArr );
				//�ύʱ�����ʲ���Ƭ״̬��Ϊ���˿�
				if($object['ExaStatus'] != "���ύ"){
					foreach($object ['items'] as $key => $val ){
						if($val['isDelTag'] != 1){//isDelTag=1 Ϊ�ӱ���ɾ��
							$assetcardDao = new model_asset_assetcard_assetcard();
							$assetcardDao->setToStock($val['assetId']);
						}
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
				$requireoutitemDao = new model_asset_require_requireoutitem();
				$itemsArr = util_arrayUtil::setItemMainId( "mainId", $object ['id'], $object ['items'] );
				$requireoutitemDao->saveDelBatch ( $itemsArr );
				//�ύʱ�����ʲ���Ƭ״̬��Ϊ���˿�
				if($object['ExaStatus'] != "���ύ"){
					foreach($object ['items'] as $key => $val ){
						if($val['isDelTag'] != 1){//isDelTag=1 Ϊ�ӱ���ɾ��
							$assetcardDao = new model_asset_assetcard_assetcard();
							$assetcardDao->setToStock($val['assetId']);
						}
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
		// ��������֣���ô�Ǿ�OA����������OA
		if (is_numeric($id) && strlen($id) < 32) {
			$object = parent::get_d ( $id );
	   		$requireoutitemDao = new model_asset_require_requireoutitem();
			$requireoutitemDao->searchArr ['mainId'] = $id;
			$object ['items'] = $requireoutitemDao->listBySqlId ();
		} else {
			// ����aws
			// ��aws��ȡ�ʲ�ת������������
			$result = util_curlUtil::getDataFromAWS('asset', 'getAssetTransferInfo', array(
				"id" => $id
			));
			$assetTransferInfo = util_jsonUtil::decode($result['data'], true);
			// �������ݴ���
			$object = $assetTransferInfo['data']['assetTransferInfo'];
			//���ݶ�Ӧ
			$object['requireCode'] = $object['applyNo'];
			$object['applyDate'] = date("Y-m-d",strtotime($object['applyDate']));
			$object['applyName'] = $object['applyUser'];
			$object['applyId'] = $object['applyUserId'];
			$object['applyDeptName'] = $object['applyDept'];
			// �ӱ����ݴ���
			// �ʲ�ת����������ϸ
			if (!empty ($assetTransferInfo['data']['details'])) {
				$items = array();
				foreach ($assetTransferInfo['data']['details'] as $k => $v) {
					$v['id'] = $k;
					$v['spec'] = $v['pattern'];
					$v['number'] = $v['applyNum'];
					$v['executedNum'] = $v['inStorageNum'];
					$v['salvage'] = $v['assetRest'];
					array_push($items, $v);
				}
				$object ['items'] = $items;
			}
		}
		return $object;
	}

 	/**
	 * ���ֳ������
	 * @param  $id   ���뵥ID
	 * @param  $equId   �����嵥ID
	 * @param  $productId   ����ID
	 * @param  $proNum    �������
	 */
	function updateInStock($id,$equId,$productId,$proNum){
		// ��������֣���ô�Ǿ�OA����������OA
		if (is_numeric($equId) && strlen($equId) < 32) {
			$itemDao = new model_asset_require_requireoutitem();
			//���������������
			$itemDao->updateInNum($equId,$proNum);
			//��ȡ��Ƭid
			$rs = $itemDao->find(array('id' => $equId),null,'assetId');
			$assetId = $rs['assetId'];
			//���¿�Ƭ״̬Ϊ���˿�
			$assetcardDao = new model_asset_assetcard_assetcard();
			$assetcardDao->setIsStock($assetId);
			//�����������״̬
			$this->updateInStatus($id);
		} else {
			// ��ȡ�ʲ����յ�������
			$result = util_curlUtil::getDataFromAWS('asset', 'updateAssetTransferDetail', array(
				"id" => $equId,
				"inStorageNum" => $proNum
			));

			$errorInfo = util_jsonUtil::decode($result['data']);
			if ($errorInfo['data']['error']) {
				throw new Exception($errorInfo['data']['error']);
			}
		}
	}
	
	/**
	 * ���ֵ������
	 * @param  $id   ���뵥ID
	 * @param  $equId   �����嵥ID
	 * @param  $productId   ����ID
	 * @param  $proNum    �������
	 */
	function updateInStockCancel($id,$equId,$productId,$proNum){
		// ��������֣���ô�Ǿ�OA����������OA
		if (is_numeric($equId) && strlen($equId) < 32) {
			$itemDao = new model_asset_require_requireoutitem();
			//���������������
			$itemDao->updateInNum($equId,-$proNum);
			//��ȡ��Ƭid
			$rs = $itemDao->find(array('id' => $equId),null,'assetId');
			$assetId = $rs['assetId'];
			//���¿�Ƭ״̬Ϊ���˿�
			$assetcardDao = new model_asset_assetcard_assetcard();
			$assetcardDao->setToStock($assetId);
			//�����������״̬
			$this->updateInStatus($id);
		} else {
			// ��ȡ�ʲ����յ�������
			$result = util_curlUtil::getDataFromAWS('asset', 'updateAssetTransferDetail', array(
				"id" => $equId,
				"inStorageNum" => -$proNum
			));

			$errorInfo = util_jsonUtil::decode($result['data']);
			if ($errorInfo['data']['error']) {
				throw new Exception($errorInfo['data']['error']);
			}
		}
	}
	
	/**
	 * ����id������������״̬
	 */
	 function updateInStatus($id){
	 	$sql = "select count(0) as countNum,(select sum(o.executedNum) from oa_asset_requireoutitem o where o.mainId=".$id." )
	 			as executeNum from (select e.mainId,(e.number-e.executedNum) as remainNum from oa_asset_requireoutitem e
				where e.mainId=".$id." ) c where c.remainNum>0";
		$remainNum = $this->_db->getArray( $sql );
		$inStockStatus = '';
	 	if( $remainNum[0]['countNum'] <= 0 ){//�����
	 		$inStockStatus = 'YRK';
	 	}elseif( $remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0 ){//δ���
	 		$inStockStatus = 'WRK';
		} else {//���ֳ���
	 		$inStockStatus = 'BFRK';
	 	}
	 	$statusInfo = array(
	 		'id' => $id,
	 		'inStockStatus' => $inStockStatus
	 	);
	 	$this->updateById( $statusInfo );
	 }
	 /**
	  * ɾ������ʹӱ���Ϣ
	  */
	 function deletes_d($id) {
	 	try {
	 		$this->start_d ();
	 		
			$itemDao = new model_asset_require_requireoutitem();
			$itemDao->delete(array('mainId'=>$id));
			$this->deletes($id);

			$this->commit_d ();
			return true;
		} catch (Exception $e) {
			$this->rollBack ();
			return false;
		}	
	 }
 }