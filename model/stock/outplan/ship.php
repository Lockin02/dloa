<?php

/**
 * @author zengzx
 * @Date 2011��5��4�� 15:55:01
 * @version 1.0
 * @description:������ Model��
 */
class model_stock_outplan_ship extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_ship";
		$this->sql_map = "stock/outplan/shipSql.php";
		parent :: __construct();
			$this->relatedStrategyArr = array (//��ͬ���ͷ���������,������Ҫ���������׷��
		"oa_contract_contract" => "model_stock_outplan_strategy_contractship", //���۷���
		"oa_borrow_borrow" => "model_stock_outplan_strategy_borrowship", //���÷���
		"oa_present_present" => "model_stock_outplan_strategy_presentship", //���ͷ���
		"oa_contract_exchangeapply" => "model_stock_outplan_strategy_exchangeship", //���ͷ���
		"oa_service_accessorder" => "model_stock_outplan_strategy_accessordership", //������������������
		"oa_service_repair_apply" => "model_stock_outplan_strategy_repairapplyship", //�������ά�����뵥����
		"independent" => "model_stock_outplan_strategy_indeptship", //��������

		);
	}
	/*===================================ҳ��ģ��======================================*/
	/**
	 * @description �������б���ʾģ��
	 * @param $rows
	 */
	function showList($rows, shipStrategy $istrategy) {
		$istrategy->showList($rows);
	}

	/**
	 * @description ���ݷ���������������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAddByPlan($rows, shipStrategy $istrategy) {
		return $istrategy->showItemAddByPlan($rows);
	}
	/**
	 * @description ����������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAdd($rows, shipStrategy $istrategy) {
		return $istrategy->showItemAdd($rows);
	}

	/**
	 * @description �޸ķ�����ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemEdit($rows, shipStrategy $istrategy) {
		return $istrategy->showItemEdit($rows);
	}

	/**
	 * @description �鿴������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemView($rows, shipStrategy $istrategy) {
		return $istrategy->showItemView($rows);
	}

	/**
	 * @description ��ӡ������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemPrint($rows, shipStrategy $istrategy) {
		return $istrategy->showItemPrint($rows);
	}

	/**
	 * �鿴���ҵ����Ϣ
	 * @param $paramArr
	 */
	function viewRelInfo($paramArr = false, shipStrategy $istrategy) {

	}

	/**
	 * ���ƻ�ȡԴ�����ݷ���
	 */
	function getDocInfo($id, shipStrategy $strategy) {
		$rows = $strategy->getDocInfo($id);
		return $rows;
	}

	/**
	 * ����������ʱԴ����ҵ����
	 * @param $istorageapply ���Խӿ�
	 * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr �ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtAdd(shipStrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}
	/**
	 * �޸ķ�����ʱԴ����ҵ����
	 * @param $istorageapply ���Խӿ�
	 * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr �ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtEdit(shipStrategy $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

	/**
	 * ɾ��������ʱԴ����ҵ����
	 * @param $istorageapply ���Խӿ�
	 * @param  $paramArr ����������� :$relDocId->��������id;$relDocCode->�������ݱ���;
	 * @param  $relItemArr �ӱ��嵥��Ϣ
	 */
	function ctDealRelInfoAtDel(shipStrategy $istrategy, $paramArr = false) {
		return $istrategy->dealRelInfoAtDel($paramArr);
	}

	/**
	 * ��Ӷ���
	 */
	function add_d($object) {
		try {
			$this->start_d();
			$codeDao = new model_common_codeRule();
			$object['shipCode'] = $codeDao->sendCode($this->tbl_name);
			$id = parent :: add_d($object, true);
			$docType = isset ($object['docType']) ? $object['docType'] : null;
			if ($docType) { //���ڷ���������
				$outStrategy = $this->relatedStrategyArr[$docType];
				if ($outStrategy) {
						$paramArr = array (//�����������
						'mainId' => $id,
						'docId' => $object['docId'],
						'docCode' => $object['docCode'],
						'docType' => $object['docType'],
						//'planId' => $object['planId']

					); //...���Լ���׷��
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //�����嵥��Ϣ
						$prod = new model_stock_outplan_shipProduct();
						$mainIdArr = $paramArr;
						$prod->createBatch($relItemArr, $mainIdArr);
					} else {
						throw new Exception("������Ϣ����������ȷ��!");
					}
					$paramArr['planId'] = $object['planId'];
					//ͳһѡ����ԣ�������Ե�ҵ����
					if ($paramArr['docId']) {
						$storageproId = $this->ctDealRelInfoAtAdd(new $outStrategy (), $paramArr, $relItemArr);
						$this->mailTo_d($object);
					} else {
						throw new Exception("�޹���ҵ��Id����ȷ�ϣ�");
					}
				} else {
					throw new Exception("�����ͳ���������δ���ţ�����ϵ������Ա!");
				}
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
			/*end:�����������ҵ����,ֻ����ѱ�Ҫ�������չ��򴫵����԰�װ����,�Ժ�����Թ̶��Ĵ���*/
			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ��������������
	 */
	function addWithoutPlan_d($object) {
		try {
			$this->start_d();
			$codeDao = new model_common_codeRule();
			$object['shipCode'] = $codeDao->sendCode($this->tbl_name);
			$productsDetailDao = new model_stock_outplan_shipProduct();
			$id = parent :: add_d($object, true);
			if (!empty ($object['productsdetail'])) {
				foreach ($object['productsdetail'] as $key => $value) {
					$value['mainId'] = $id;
					$productsDetailDao->add_d($value);
				}
			}
			$this->commit_d();
			return $id;
		} catch (exception $e) {
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �༭����
	 */
	function edit_d($object) {
		try {
			$this->start_d();
			$id = parent :: edit_d($object, true);
			$docType = isset ($object['docType']) ? $object['docType'] : null;
			if ($docType) { //���ڷ���������
				$outStrategy = $this->relatedStrategyArr[$docType];
				if ($outStrategy) {
						$paramArr = array (//�����������
	'mainId' => $object['id'],
						'docId' => $object['docId'],
						'docCode' => $object['docCode'],
							//							'planId' => $object['planId']

					); //...���Լ���׷��
					if (is_array($object['productsdetail'])) {
						$relItemArr = $object['productsdetail']; //�����嵥��Ϣ
						$prod = new model_stock_outplan_shipProduct();
						$mainIdArr = array (
							'mainId' => $object['id']
						);
						$prod->delete($mainIdArr);
						$prod->createBatch($relItemArr, $paramArr);
					} else {
						throw new Exception("������Ϣ����������ȷ��!");
					}
					$paramArr['planId'] = $object['planId'];
					//ͳһѡ����ԣ�������Ե�ҵ����
					$storageproId = $this->ctDealRelInfoAtEdit(new $outStrategy (), $paramArr, $relItemArr);
				} else {
					throw new Exception("�����ͳ���������δ���ţ�����ϵ������Ա!");
				}
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
			/*end:�����������ҵ����,ֻ����ѱ�Ҫ�������չ��򴫵����԰�װ����,�Ժ�����Թ̶��Ĵ���*/

			$this->commit_d();
			return $id;
		} catch (Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ����id��������뵥������Ϣ
	 */
	function get_d($id) {
		$shipInfo = parent :: get_d($id);
		$itemDao = new model_stock_outplan_shipProduct();
		$searchArr = array (
			'mainId' => $id,

		);
		$shipInfo['details'] = $itemDao->findAll($searchArr);
		if (is_array($shipInfo['details'])) {
			foreach ($shipInfo['details'] as $key => $val) {
				$shipProDao = new model_stock_outplan_outplanProduct();
				$shipPro = $shipProDao->get_d($shipInfo['details'][$key]['planEquId']);
				$shipInfo['details'][$key]['contEquId'] = $shipPro['contEquId'];
			}
		}
		return $shipInfo;
	}

	/**
	 * �༭����
	 */
	function sign_d($object) {
		$id = parent :: edit_d($object, true);
		$docType = isset ($object['docType']) ? $object['docType'] : null;
		return $id;
	}

	/**
	 * ��д���ⵥʱ�����ݳ��ⵥID��ȡ������ʾģ��
	 */
	function getEquList_d($shipId) {
		$shipEquDao = new model_stock_outplan_shipProduct();
		$rows = $shipEquDao->getItemByshipId_d($shipId);// k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows= $productinfoDao->k3CodeFormatter_d($rows);
		$list = $shipEquDao->showAddList($rows);
		return $list;
	}

	/**
	 * ��д���ⵥʱ�����ݳ��ⵥID��ȡ������ʾģ�� -- ��������ʹ��
	 */
	function getEquListOther_d($shipId) {
		$shipEquDao = new model_stock_outplan_shipProduct();
		$rows = $shipEquDao->getItemByshipId_d($shipId);
		$list = $shipEquDao->showAddOtherList($rows);
		return $list;
	}

	/**
	 * ��д���ⵥʱ�����ݳ��ⵥID������״̬��Ϊ�ѷ���
	 *
	 */
	function setDocStatusById_d($relDocItemArr) {
		$docStatus = array (
			'id' => $relDocItemArr['relDocId'],
			'docStatus' => '1',
			'shipStatus' => '2'
		);
		$this->updateById($docStatus);
	}

	/**
	 * ������ⵥʱ�����ݳ��ⵥID������״̬��Ϊδ����
	 *
	 */
	function unsetDocStatusById_d($relDocItemArr) {
		$docStatus = array (
			'id' => $relDocItemArr['relDocId'],
			'docStatus' => '0',
			'shipStatus' => '1'
		);
		$this->updateById($docStatus);
	}

	/**
	 * ɾ����������������Ϣ��ɾ���ʼ���Ϣ��¼���ع����������ƻ���ִ��״̬
	 */
	function deleteObj($shipObj) {
		try {
			$this->start_d();
			$docType = $shipObj['docType'];
			//����ɾ�������ʼ���Ϣ�ļ�¼
			$mailDao = new model_mail_mailinfo();
			$shipEquDao = new model_stock_outplan_shipProduct();
			if (!$mailDao->deleteByDoc($shipObj['id'], 'YJSQDLX-FHYJ')) {
				throw new Exception("������Ϣ����������ȷ��!");
				return null;
			}
			$condition = array (
				'mainId' => $shipObj['id']
			);
			$shipEquDao->delete($condition);
			$flag = $this->deleteByPk($shipObj['id']);
			$outStrategy = $this->relatedStrategyArr[$docType];
			$this->ctDealRelInfoAtDel(new $outStrategy (), $shipObj);
			$this->commit_d();
			return $flag;
		} catch (Exception $e) {
			$this->rollBack();
			return 0;
		}
	}

	/**
	 * ��ȡ��ͬ�����˷���
	 */
	function getSaleman($shiDocId, $docType, shipStrategy $strategy) {
		$salemanArr = $strategy->getSaleman($shiDocId, $docType);
		return $salemanArr;
	}

	/**
	 *
	 * ����Դ�����ͼ�Դ��id ���ҷ�������Ϣ
	 * @param  $docType
	 * @param  $docId
	 */
	function findIdArrByDocInfo($docId, $docType) {
		$this->searchArr = array (
			"docType" => $docType,
			"docId" => $docId
		);
		$shipArr = $this->listBySqlId();
		$idArr = array ();
		foreach ($shipArr as $key => $val) {
			array_push($idArr, $val['id']);
		}
		return $idArr;
	}

	/**
	 * ����Դ���ţ�Դ�����ͣ���ȡ������id
	 * @author zengzx
	 * @param bigint $orderId Դ��ID
	 * @param string $type Դ������
	 * 2012��6��14�� 09:54:29
	 */
	function getShipId_d($orderId, $type) {
		$conditions = array (
			'docId' => $orderId,
			'docType' => $type
		);
		$ids = $this->find($conditions, $sort = null, 'id');
		return $ids;
	}

	/**
	 * �����ƻ��´���ʼ�
	 * TODO:@param mailman string �����ʼ��ˣ�����չ��
	 */
	function mailTo_d($object) {
		include (WEB_TOR . "model/common/mailConfig.php");
		$this->mailArr = $mailUser[$this->tbl_name];
		$addMsg = $this->getAddMes_d($object);
		$emailDao = new model_common_mail();
		$emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '¼��', $object['shipCode'], $this->mailArr['sendUserId'], $addMsg, '1');
	}
	/**
	 * �ʼ��и���������Ϣ
	 */
	function getAddMes_d($object) {
		if (is_array($object['productsdetail'])) {
			$j = 0;
			$addmsg = '';
			if($object['docType'] == 'oa_borrow_borrow'){
				if($object['customerName']==''){
					$addmsg.="Դ��ΪԱ�������ã������õ���Ϊ����<font color='red'>".$object['docCode']."</font>��</br>";
				}else{
					$addmsg.="Դ��Ϊ�ͻ������ã������õ���Ϊ��".$object['docCode'].">,�ͻ�����Ϊ��".$object['customerName']."</br>";
				}
			}else{
				$addmsg.="Դ��Ϊ��ͬ����ͬ��Ϊ��".$object['docCode'].">,�ͻ�����Ϊ��".$object['customerName']."</br>";
			}
			$addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor='#D5D5D5' align=center ><td>���</td><td>���ϱ��</td><td>��������</td><td>����ͺ�</td><td>����</td><td>��ע</td></tr>";
			foreach ($object['productsdetail'] as $key => $equ) {
				$j++;
				$productCode = $equ['productNo'];
				$productName = $equ['productName'];
				$productModel = $equ['productModel'];
				$number = $equ['number'];
				$remark = $equ['remark'];
				$addmsg .=<<<EOT
						<tr bgcolor='#7AD730' align="center" ><td>$j</td><td>$productCode</td><td>$productName</td><td>$productModel</td><td>$number</td><td>$remark</td></tr>
EOT;
			}
			//					$addmsg.="</table>" .
			//							"<br><span color='red'>�����б����б���ɫΪ��ɫ�����ϣ�˵���������ǽ�����ת���۵ġ�</span></br>";
		}
		return $addmsg;
	}
	/**
	 *��ӷ��������ʼ�֪ͨ
	 */
	 function sendMail_d($object){
		$this->mailDeal_d('shipFHDTZ',$object['receiverId'],array(id => $object['id']));
	 }
}
?>