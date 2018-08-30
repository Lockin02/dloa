<?php

/**
 *
 * �ʲ�����model
 * @author fengxw
 *
 */
class model_asset_purchase_receive_receive extends model_base {

	function __construct() {
		$this->tbl_name = "oa_asset_receive";
		$this->sql_map = "asset/purchase/receive/receiveSql.php";
		parent::__construct ();
	}

	/**
	 * �½������ʲ����ռ���ϸ��
	 */
	function add_d($object){
		try{
			$this->start_d();
			if(!is_array($object['receiveItem'])){
				msg ( '����д���ʲ�������ϸ������Ϣ��' );
				throw new Exception('�ʲ�������Ϣ������������ʧ�ܣ�');
			}
// 			$codeDao = new model_common_codeRule ();
// 	       	$sql = "SELECT MAX(createTime) as createTime from oa_asset_receive";
// 	       	$applyDateArr = $this->_db->get_one($sql);
// 	       	$applyDate = substr($applyDateArr['createTime'], 0, 10);
// 	       	$thisDate =  day_date;
// 	       	if( $applyDate!= $thisDate ){
// 				$object ['name']=$codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],true);
// 	       	}else{
// 				$object ['name']=$codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],false);
// 	       	}
// 			$id=parent::add_d($object,true);

			$sendContent="<table><tr><td>������:</td><td>" . $object ['salvage'] . "</td><td>�ɹ������:</td><td>" . $object ['purchaseContractCode'] . "</td></tr>"
											."<tr><td>��������:</td><td>" . $object ['limitYears'] . "</td><td>���ս��:</td><td>" . $object ['amount'] . "</td></tr>"
											."<tr><td>���ս��:</td><td colspan='3'>" . $object ['result'] . "</td></tr></table>";
			$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>�ʲ�����</b></td><td><b>���</b></td><td><b>����</b></td><td><b>����</b></td><td><b>���</b></td><td><b>����</b></td><td><b>��ע</b></td></tr>";
			//������ϸ��
// 			$receiveItemDao=new model_asset_purchase_receive_receiveItem();
			$applyItemDao=new model_asset_purchase_apply_applyItem();
			$arrivalDao=new model_purchase_arrival_arrival();
			$applyDao = new model_asset_purchase_apply_apply();
// 			$requirementDao = new model_asset_require_requirement();
			$applyArr = array();
			//��ʼ�����͸�aws������
			$obj = array();//����������͵�����
			$detail = array();//���������ϸ
			//�Ƿ�Ϊ�������ɹ�,������Ϊ�������ɹ�
			$isDelivery = empty($object['purchaseContractId']) ? false : true;
			
			if($isDelivery){//�ɽ�����ִ�вɹ�
				//��ȡ�ɹ�����id
				$equDao=new model_purchase_contract_equipment();
				$planEquRows=$equDao->getPlanEqu_d($object['purchaseContractId']);
				foreach ($planEquRows as $val){
					$applyArr[$val['id']] = $val['applyId'];
				}
				//��Դ���ż�id
				$obj['comeFromNo'] = $object['purchaseContractCode'];
				$obj['comeFromId'] = $object['purchaseContractId'];
			}else{//��������ִ�вɹ�
				//���²ɹ�״̬Ϊ����ɡ���ֵ����Ϊ3
				$applyDao->updatePurchState($object['applyId'], 3);
				//��Դ���ż�id
				$obj['comeFromNo'] = $object['code'];
				$obj['comeFromId'] = $object['applyId'];
			}
			$seNum=1;
			foreach($object['receiveItem'] as &$val){
				if($val['isDelTag']!="1"){
// 					$val['receiveId']=$id;
// 					$val['isCard']="0";
					if($isDelivery){//�ɽ�����ִ�вɹ�
						$val['applyId'] = $applyArr[$val['contractId']];
					}else{//��������ִ�вɹ�
						$val['applyId'] = $object['applyId'];
					}
// 					$receiveItemDao->add_d($val);
					//���²ɹ�������ϸ����������
					if(!empty($val['applyEquId'])){//����ж� by chengl ��Ϊ�ɹ������������������˲���
						$applyItemDao->updateAmountCheck($val['applyEquId'],$val['checkAmount']);
						array_push($detail, array(
							'comeFromItemId' => $val['applyEquId'],
							'productId' => '',
							'productCode' => '',
							'productName' => $val['assetName'],
							'pattern' => $val['spec'],
							'unit' => '',
							'num' => $val['checkAmount'],
							'price' => $val['price'],
							'amount' => $val['amount']
						));
					}
	
					if(!empty($val['arrivalEquId'])){//add by huangzf ����Դ������֪ͨ����Ϣ
						$arrivalDao->updateInStock($object['arrivalId'], $val['arrivalEquId'], $val['assetId'], $val['checkAmount']);
						array_push($detail, array(
							'comeFromItemId' => $val['contractId'],
							'productId' => $val['productId'],
							'productCode' => $val['sequence'],
							'productName' => $val['assetName'],
							'pattern' => $val['spec'],
							'unit' => $val['units'],
							'num' => $val['checkAmount'],
							'price' => $val['price'],
							'amount' => $val['amount']
						));
					}
					$sendContent .= <<<EOT
													<tr align="center" >
													<td>$seNum</td>
													<td>$val[assetName]</td>
													<td>$val[spec]</td>
													<td>$val[checkAmount]</td>
													<td>$val[price]</td>
													<td>$val[amount]</td>
													<td>$val[deploy]</td>
													<td>$val[remark]</td>
												</tr>
EOT;
					$seNum++;
				}
			}
			//������Ϣ
			$obj['acceptance']['userId'] = $object['salvageId'];
			$obj['acceptance']['userName'] = $object['salvage'];
			$obj['acceptance']['result'] = $object['result'];
			$obj['acceptance']['amount'] = $object['amount'];
			//��ϸ��Ϣ
			$obj['acceptance']['detail'] = $detail;
			// ����aws
			// 1.�������յ����ݸ�aws
			// ��������Ϊ���ɹ��ջ�֪ͨ��
			$obj['comeFrom'] = 'YSLY-01';
			$result = util_curlUtil::getDataFromAWS('asset', 'createAcceptanceByPurchase', $obj);
			// 2.�ı��������뵥״̬,��Ϊ�������ա�
			if($result){
				if($isDelivery){//�ɽ�����ִ�вɹ�
					foreach($object['receiveItem'] as $val){
						if($val['isDelTag']!="1"){
							$val['applyId'] = $applyArr[$val['contractId']];
							$requirementId = $applyDao->getRequirementId($val['applyId']);//��ȡ�ɹ������Ӧ���ʲ���������id
							$result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
									'requireId' => $requirementId, 'applyStatus' => '1041')
							);
// 							$requirementDao->updateRecognize($requirementId);
						}
					}
				}else{//��������ִ�вɹ�
					$requirementId = $applyDao->getRequirementId($object['applyId']);//��ȡ�ɹ������Ӧ���ʲ���������id
					$result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
							'requireId' => $requirementId, 'applyStatus' => '1041')
					);
// 					$requirementDao->updateRecognize($requirementId);
				}
					
				//�����ʼ����ɹ�Ա
				if($result && !empty($object['purchaseContractId']) && $seNum>1){
					$orderDao=new model_purchase_contract_purchasecontract();
					$orderObj=$orderDao->get_d($object['purchaseContractId']);
					$emailDao = new model_common_mail();
					$emailDao->mailClear ( "�ʲ�����֪ͨ", $orderObj['sendUserId'], $sendContent );
				}
			}
			
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �޸ı���
	 * @see model_base::edit_d()
	 */
	function edit_d($object) {
		try {
			$this->start_d ();
			if (is_array ( $object ['receiveItem'] )) {
				$editresult = parent::edit_d ( $object, true );
				$receiveItemDao=new model_asset_purchase_receive_receiveItem();
				$itemsArr = $this->setItemMainId ( "receiveId", $object ['id'], $object ['receiveItem'] );
				$itemsObj = $receiveItemDao->saveDelBatch ( $itemsArr );
				$this->commit_d ();
				return $object ['id'];
			} else {
				throw new Exception ( "������Ϣ����������ȷ�ϣ�" );
			}
		} catch ( Exception $e ) {
			$this->rollBack ();
			return null;
		}
	}

	/**
	 * ���ù����嵥�Ĵӱ������id��Ϣ
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array ();
		foreach ( $iteminfoArr as $key => $value ) {
			$value [$mainIdName] = $mainIdValue;
			array_push ( $resultArr, $value );
		}
		return $resultArr;
	}

	/**
	 * ���ݳ��� - create by kuangzw
	 */
	function ajaxRevocation_d($id){
		try{
			$this->start_d();

			//��ѯ����ҵ����Ϣ
			$obj = $this->find(array('id' => $id));

			$receiveItemDao=new model_asset_purchase_receive_receiveItem();
			//����Ǵ����ϵ�������,����Ҫ����������ϵ�����
			if($obj['arrivalId']){
				//������ϸ��
//				$receiveItemDao=new model_asset_purchase_receive_receiveItem();
				$receiveItemArr = $receiveItemDao->findAll(array('receiveId' => $id));
//				print_r($receiveItemArr);

				$arrivalDao=new model_purchase_arrival_arrival();
				foreach($receiveItemArr as $key => $val){
					if(!empty($val['arrivalEquId'])){//add by huangzf ����Դ������֪ͨ����Ϣ
						$arrivalDao->updateInStockCancel($obj['arrivalId'], $val['arrivalEquId'], $val['assetId'], $val['checkAmount']);
					}
				}
			}

			//ɾ������
			$this->revocateSendMail_d($id);
			$receiveItemDao->deleteByFk($id);
			$this->deletes($id);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ���ݳ����ʼ�֪ͨ - create by zengzx
	 */
	function revocateSendMail_d($id){
		try{
			$this->start_d();

			//��ѯ����ҵ����Ϣ
			$object = $this->get_d($id);
			$receiveItemDao=new model_asset_purchase_receive_receiveItem();
			$receiveItemDao->searchArr['receiveId']=$id;
			$itemObj = $receiveItemDao->list_d();
			$object['receiveItem']=$itemObj;

			$sendContent="<table><tr><td>������:</td><td>" . $object ['salvage'] . "</td><td>�ɹ������:</td><td>" . $object ['purchaseContractCode'] . "</td></tr>"
											."<tr><td>��������:</td><td>" . $object ['limitYears'] . "</td><td>���ս��:</td><td>" . $object ['amount'] . "</td></tr>"
											."<tr><td>���ս��:</td><td colspan='3'>" . $object ['result'] . "</td></tr></table>";
			$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>�ʲ�����</b></td><td><b>���</b></td><td><b>����</b></td><td><b>����</b></td><td><b>���</b></td><td><b>��ע</b></td></tr>";

			$seNum=1;
			foreach($object['receiveItem'] as $val){
				//���²ɹ�������ϸ����������
				$sendContent .= <<<EOT
												<tr align="center" >
												<td>$seNum</td>
												<td>$val[assetName]</td>
												<td>$val[spec]</td>
												<td>$val[checkAmount]</td>
												<td>$val[price]</td>
												<td>$val[amount]</td>
												<td>$val[remark]</td>
											</tr>
EOT;
				$seNum++;
			}
//			echo $sendContent;
			$emailDao = new model_common_mail();
			include (WEB_TOR . "model/common/mailConfig.php");
			$this->mailArr = $mailUser[$this->tbl_name];
			$emailDao->mailClear ( "�ʲ����ճ���֪ͨ", $this->mailArr['sendUserId'], $sendContent );
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}
	
	/**
	 * �½������ʲ����ռ���ϸ��
	 */
	function addByRequirein_d($object){
		try{
			$this->start_d();
			
			//ȥ��ҳ��ɾ��������
			foreach($object['receiveItem'] as $key => $val){
				if($val['isDelTag'] == '1'){
					unset($object['receiveItem'][$key]);
				}
			}
			//ͳһʵ����
// 			$codeDao = new model_common_codeRule ();
// 			$receiveItemDao = new model_asset_purchase_receive_receiveItem();
			$requireinDao = new model_asset_require_requirein();
			$requireinitemDao = new model_asset_require_requireinitem();
// 			$sql = "SELECT MAX(createTime) as createTime from oa_asset_receive";
// 			$applyDateArr = $this->_db->get_one($sql);
// 			$applyDate = substr($applyDateArr['createTime'], 0, 10);
// 			$thisDate = day_date;
// 			if( $applyDate != $thisDate ){
// 				$object['name'] = $codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],true);
// 			}else{
// 				$object['name'] = $codeDao->assetReceiveCode ( "oa_asset_receive", "YS" ,$thisDate,$object['company'],false);
// 			}
// 			$id = parent::add_d($object,true);
			//��ʼ�����͸�aws������
			$obj = array();//����������͵�����
			$detail = array();//���������ϸ
			$amount = 0;//�������������ܶ�
			$productDao = new model_stock_productinfo_productinfo();
			foreach($object['receiveItem'] as $val){
// 				$val['receiveId'] = $id;
// 				$val['isCard'] = '0';
// 				$receiveItemDao->add_d($val);//����������ϸ��
				$requireinitemDao->updateReceiveNum($val['requireinItemId'],$val['checkAmount']);//��������ת�ʲ���ϸ��������
				$detailAmount = $val['checkAmount'] * $val['productPrice'];//����������ϸ�ܶ�
				//��ȡ���ϵ�λ
				$rs = $productDao->find(array('id' => $val['assetId']),null,'unitName');
				array_push($detail, array(
					'comeFromItemId' => $val['requireinItemId'],
					'productId' => $val['assetId'],
					'productCode' => $val['assetCode'],
					'productName' => $val['assetName'],
					'pattern' => $val['spec'],
					'unit' => $rs['unitName'],
					'num' => $val['checkAmount'],
					'price' => $val['productPrice'],
					'amount' => $detailAmount
				));
				$amount += $detailAmount;
			}

			$requireinId = $object['requireinId'];
			$requireinDao->updateReceiveStatus($requireinId);//��������ת�ʲ���������״̬
// 			$requireinDao->updateRequireInStatus($requireinId);//����������������ת�ʲ�״̬			
			// ��������Ϊ������ת�ʲ���
			$obj['comeFrom'] = 'YSLY-02';
			//��Դ���ż�id
			$obj['comeFromNo'] = $object['requireinCode'];
			$obj['comeFromId'] = $object['requireinId'];
			//������Ϣ
			$obj['acceptance']['userId'] = $object['salvageId'];
			$obj['acceptance']['userName'] = $object['salvage'];
			$obj['acceptance']['result'] = $object['result'];
			$obj['acceptance']['amount'] = $amount;
			//��ϸ��Ϣ
			$obj['acceptance']['detail'] = $detail;
			// ����aws
			// 1.�������յ����ݸ�aws
			$result = util_curlUtil::getDataFromAWS('asset', 'createAcceptanceByPurchase', $object);
			// 2.�ı��������뵥״̬,��Ϊ�������ա�
			if($result){
				$result = util_curlUtil::getDataFromAWS('asset', 'updateApplyStatus', array(
					'requireId' => $requireinId, 'applyStatus' => '1041')
				);
			}
			
			$this->commit_d();
			return $result;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
}
