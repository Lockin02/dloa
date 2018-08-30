<?php
/**
 * @author huangzf
 * @Date 2011��5��9�� 22:10:00
 * @version 1.0
 * @description:��ⵥ������Ϣ Model�� 1.��ⵥ���ͣ�A.�вɹ����  B.��Ʒ���   C.������� D.�������
 * 2.����״̬��  δ��ˡ������
 */
class model_stock_instock_stockin extends model_base
{

	public $stockinStrategyArr = array();
	public $stockinPreArr = array();

	function __construct() {
		$this->tbl_name = "oa_stock_instock";
		$this->sql_map = "stock/instock/stockinSql.php";
		parent::__construct();
		$this->stockinStrategyArr = array(//������
			"RKPURCHASE" => "model_stock_instock_strategy_purchasestockin", //�⹺���
			"RKPRODUCT" => "model_stock_instock_strategy_productstockin", //��Ʒ���
			"RKOTHER" => "model_stock_instock_strategy_otherstockin", //�������
			"RXSTH" => "model_stock_instock_strategy_otherstockin", //�������
			"RKPRODUCEBACK" => "model_stock_instock_strategy_producebackstockin", //�����������
            "RKDLBF" => "model_stock_instock_strategy_idleStockScrapStockin", //�����ϴ������
		); //�������

		$this->stockinPreArr = array(//���ǰ׺
			"RKPURCHASE" => "WIN",
			"RKPRODUCT" => "CIN",
			"RKOTHER" => "QIN",
			"RKPRODUCEBACK" => "SIN",
			"RXSTH" => "HIN",
            "RKDLBF" => "DIN",
		);

		$this->docStatus = array(//����״̬
			"WSH" => "δ���", "YSH" => "�����"
		);
	}

	//��˾Ȩ�޴���
	protected $_isSetCompany = 1;

	/*===================================ҳ��ģ��======================================*/
	/**
	 * ������뵥�б���ʾģ��
	 * @param $rows
	 * @param istockin $istrategy
	 */
	function showList($rows, istockin $istrategy) {
		$istrategy->showList();
	}

	/**
	 * @description �����������������ʱ���嵥��ʾģ��
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemAdd($rows, istockin $istrategy) {
		return $istrategy->showItemAdd($rows);
	}

	/**
	 *
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemAddRed($rows, istockin $istrategy) {
		return $istrategy->showItemAddRed($rows);
	}

	/**
	 * @description �޸��������ʱ���嵥��ʾģ��
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemEdit($rows, istockin $istrategy) {
		return $istrategy->showItemEdit($rows);
	}

	/**
	 * @description �޸��������ʱ���嵥��ʾģ��
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemEditPrice($rows, istockin $istrategy) {
		return $istrategy->showItemEditPrice($rows);
	}

	/**
	 * @description �鿴�������ʱ���嵥��ʾģ��
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemView($rows, istockin $istrategy) {
		return $istrategy->showItemView($rows);
	}

	/**
	 * @description ��ӡ�������ʱ���嵥��ʾģ��
	 * @param $rows
	 * @param $istrategy
	 */
	function showItemPrint($rows, istockin $istrategy) {
		return $istrategy->showItemPrint($rows);
	}
	
	/**
	 * @description ��ӡ�������ʱ���嵥��ʾģ��--��ɫ��
	 * @param $rows
	 * @param $istrategy
	 */
	function showRedItemPrint($rows, istockin $istrategy) {
		return $istrategy->showRedItemPrint($rows);
	}

	/**
	 * @description ��ȡԴ����Ϣ
	 * @param $paramArr
	 * @param $istrategy
	 */
	function getRelInfo($paramArr, istockin $istrategy) {
		return $istrategy->getRelInfo($paramArr);
	}

	/**
	 * @desription �б���ʾ
	 * @param tags
	 * @date 2011-2-24 ����02:18:24
	 * @qiaolong
	 */
	function showViewProdList($taskProdRows) {
		$producetaskDao = new model_produce_task_producetask();
		$list = $producetaskDao->showAppTaskProdList($taskProdRows);
		return $list;
	}

	/**
	 * @description ������뵥�ӱ���Ϣ��ʾģ��
	 * @param $rows
	 * @param $istrategy
	 */
	function showProDetailList($rows, istockin $istrategy) {
		return $istrategy->showProDetailList($rows);
	}

	/**
	 *
	 * ������ɫ��ⵥ���ɺ�ɫ��ⵥ���嵥��ʾģ��
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showRelItem($rows, istockin $istrategy) {
		return $istrategy->showRelItem($rows);
	}

	/**
	 * ������ⵥ���Ƴ��ⵥʱ���嵥��ʾģ��
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showItemAtOutStock($rows, istockin $istrategy) {
		return $istrategy->showItemAtOutStock($rows);
	}

     /***
      *
      */
    function  getMoneyLimit($limitKey){
        $otherdatasDao=new model_common_otherdatas();
        $limit =$otherdatasDao->getUserPriv('stock_instock_stockin',$_SESSION ['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['USER_JOBSID']);
        return $limit[$limitKey];
    }
    /*===================================ҵ����======================================*/
    /**
	 * ������ⵥ
	 * @param $object
	 * @return bool
	 * @throws Exception
	 */
	function add_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				//������ⵥ������Ϣ
				$codeDao = new model_common_codeRule();
				$object['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr[$object['docType']]);
				$object['catchStatus'] = "CGFPZT-WGJ";
				$id = parent::add_d($object, true);

                //���������¼
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, $object,
                    $object['docStatus'] == "YSH" ? '������' : '�������');

                if (isset($object['orgId']) && $object['orgId']) {
                    $logSettingDao->addObjLog($this->tbl_name, $object['orgId'],
                        array('docCode' => $object['docCode']), '���ƺ��ֵ���');
                }

				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				// ����һ������ID
				$productIds = array();

				//������ⵥ�����嵥
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $itemObj) { //���ù�����Ϣ
					$itemObj['unHookNumber'] = $itemObj['actNum'];
					$itemObj['unHookAmount'] = $itemObj['price'] * $itemObj['actNum'];

					if (!in_array($itemObj['productId'], $productIds)) {
						$productIds[] = $itemObj['productId'];
					}

                    //��������һ������
                    if(!isset($itemObj['proType'])||$itemObj['proType']==""){
                        $typeRow=$productinfoDao->getParentType($itemObj['productId']);
                        if(!empty($typeRow)){
                            $itemObj['proType']=$typeRow['proType'];
                        }
                    }
					//��ɫ��ⵥ�������к�ͬʱ���浽�嵥�У���������
					if($object['isRed'] == "0") {
						if(!empty($itemObj['serialSequence'])) {
							$itemObj['serialnoName'] = $itemObj['serialSequence'];
						}
					}
					array_push($itemsObjArr, $itemObj);
				}
				$itemsArr = $this->setItemMainId("mainId", $id, $itemsObjArr);
				$itemsObj = $stockinItemDao->saveDelBatch($itemsArr);

				//���ò��Դ������ҵ��
				$stockinStrategy = $this->stockinStrategyArr[$object['docType']];
				if($stockinStrategy) {
					$paramArr = array(//���ݻ�����Ϣ
						'docId' => $id, 'docCode' => $object['docCode'], 'docType' => $object['docType'],
						'docStatus' => $object['docStatus'], 'relDocId' => $object['relDocId'],
						'relDocCode' => $object['relDocCode'], 'relDocType' => $object['relDocType'],
						'auditDate' => $object['auditDate'], 'isRed' => $object['isRed']
					);

					if ($object['purOrderId'] > 0) {
                        $paramArr['auditDate'] = $this->getEntryDateForPurOrderId_d($object['purOrderId'],
							implode(',', $productIds), false, false);
					}

					$dealRelInfoResult = $this->ctDealRelInfoAtAdd(new $stockinStrategy(), $paramArr, $itemsObj);
					if(!$dealRelInfoResult) {
						throw new Exception("���ҵ����������ȷ�ϣ�");
					}
				} else {
					throw new Exception("�����������δ���ţ�����ϵ������Ա��");
				}
				if($object['relDocId'] && $object['relDocType'] == 'RSLTZD') {
					//add chenrf �������ʱ��
					$sql = ' update oa_produce_quality_ereportequitem c ,(
                        select c.id , b.mainId from oa_purchase_arrival_info c left join oa_purchase_arrival_equ p
                        on c.id=p.arrivalId left join  oa_produce_qualityapply_item m on p.id=m.relDocItemId
                        left join oa_produce_quality_taskitem n ON m.id=n.applyItemId
                        LEFT JOIN oa_produce_quality_ereportequitem b ON n.id=b.relItemId
                        WHERE b.id is not null AND m.status=3 )  p
                        SET c.completionTime=NOW() WHERE c.mainId=p.mainId';
					$sql .= ' and p.id="' . $object['relDocId'] . '"';
					$this->query($sql);
				}

				/*start:�����ʼ� ,������Ϊ�ύʱ�ŷ���*/
				if($object['docStatus'] == "YSH") {
					if($object['relDocType'] != "RZCRK") {
						if(isset($object['email'])){
							$emailArr = $object['email'];
							if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
								$datadictDao = new model_system_datadict_datadict();
							
								$sendContent = "��λ��:<br/>�����������Ϣ�����,��֪Ϥ.<br/>";
								$sendContent .= "<table><tr><td>���ݱ��:</td><td>" . $object['docCode'] . "</td><td>������λ:</td><td>" . $object['purchaserName'] . "</td></tr>"
										. "<tr><td>Դ������:</td><td>" . $datadictDao->getDataNameByCode($object['relDocType']) . "</td><td>Դ�����:</td><td>" . $object['relDocCode'] . "</td></tr>"
												. "<tr><td>�ͻ�����:</td><td>" . $object['clientName'] . "</td><td></td><td></td></tr>"
														. "<tr><td>��ע:</td><td colspan='3'>" . $object['remark'] . "</td></tr></table>";
							
								$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>���ϱ��</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>����</b></td><td><b>���κ�</b></td><td><b>���к�</b></td></tr>";
								$seNum = 1;
								foreach($itemsObj as $kValue) {
									$sendContent .= <<<EOT
                                <tr align="center" >
                                    <td>$seNum</td>
                                    <td>$kValue[productCode]</td>
                                    <td>$kValue[productName]</td>
                                    <td>$kValue[pattern]</td>
                                    <td>$kValue[actNum]</td>
                                    <td>$kValue[batchNum]</td>
                                    <td>$kValue[serialnoName]</td>
                                </tr>
EOT;
									$seNum++;
								}
								$sendContent .= "</table>";
								$emailDao = new model_common_mail();
								$emailDao->mailClear("��Ʒ���֪ͨ", $emailArr['TO_ID'], $sendContent);
							} else {
								if($object['isRed'] == "1" && $object['docType'] == "RKPURCHASE") {//�⹺������Ϸ���֪ͨ
									$sendContent = "��λ��:<br/>�����������Ϣ���˿�,��֪Ϥ.<br/>";
									$sendContent .= "<table><tr><td>��Ӧ������:</td><td>" . $object['supplierName'] . "</td><td>��������:</td><td>" . $object['payDate'] . "</td></tr>"
											. "<tr><td>���ݱ��:</td><td>" . $object['docCode'] . "</td><td>��������:</td><td>" . $object['auditDate'] . "</td></tr>"
													. "<tr><td>�ͻ�����:</td><td>" . $object['purOrderCode'] . "</td><td>�ɹ�Ա����:</td><td>" . $object['purchaserName'] . "</td></tr>"
															. "<tr><td>��ע:</td><td colspan='3'>" . $object['remark'] . "</td></tr></table>";
							
									$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>���ϱ��</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>����</b></td><td><b>���κ�</b></td><td><b>���к�</b></td></tr>";
									$seNum = 1;
									foreach($itemsObj as $kValue) {
										$sendContent .= <<<EOT
                                    <tr align="center" >
                                        <td>$seNum</td>
                                        <td>$kValue[productCode]</td>
                                        <td>$kValue[productName]</td>
                                        <td>$kValue[pattern]</td>
                                        <td>$kValue[actNum]</td>
                                        <td>$kValue[batchNum]</td>
                                        <td>$kValue[serialnoName]</td>
                                    </tr>
EOT;
										$seNum++;
									}
									$sendContent .= "</table>";
									$emailDao = new model_common_mail();
									include(WEB_TOR . "model/common/mailConfig.php");
									$emailArr = isset($mailUser["purchinstock"]) ? $mailUser["purchinstock"] : '';
									$emailDao->mailClear("�⹺�˿�֪ͨ", $object['purchaserCode'] . "," . $emailArr['TO_ID'], $sendContent);
								}
							}
						}
					} else {//�ʲ����-�ύʱ�ʼ�֪ͨ
						$requireoutDao = new model_asset_require_requireout();
						$rs = $requireoutDao->find(array('id' => $object['relDocId']), null, 'applyId');
						$this->mailDeal_d('assetInStock', $rs['applyId'], array('id' => $id));
					}
				}
				/*end:�����ʼ� ,������Ϊ�ύʱ�ŷ���*/

				$this->commit_d();
				return $id;
			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * ����9�·�ǰ��ⵥ
	 * @param $object
	 * @return bool
	 * @throws Exception
	 */
	function addPre_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				//������ⵥ������Ϣ
				$codeDao = new model_common_codeRule();
				$object['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr[$object['docType']]);
				$object['catchStatus'] = "CGFPZT-WGJ";
				$id = parent::add_d($object, true);

				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				//������ⵥ�����嵥
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $itemObj) { //���ù�����Ϣ
					$itemObj['unHookNumber'] = $itemObj['actNum'];
					$itemObj['unHookAmount'] = $itemObj['price'] * $itemObj['actNum'];
					//��ɫ��ⵥ�������к�ͬʱ���浽�嵥�У���������
					if($object['isRed'] == "0") {
						if(!empty($itemObj['serialSequence'])) {
							$itemObj['serialnoName'] = $itemObj['serialSequence'];
						}
					}
					array_push($itemsObjArr, $itemObj);
				}
				$itemsArr = $this->setItemMainId("mainId", $id, $itemsObjArr);
				$itemsObj = $stockinItemDao->saveDelBatch($itemsArr);

				$arrivalDao = new model_purchase_arrival_arrival();
				foreach($itemsObj as $key => $value) {
					$arrivalDao->updateInStock($object['relDocId'], $value['relDocId'], $value['productId'], $value['actNum']);
				}

				$this->commit_d();
				return $id;
			} else {
				throw new Exception("������Ϣ����������ȷ�ϣ�");
			}
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * �޸���ⵥ
	 * @param $object
	 * @return mixed
	 * @throws Exception
	 */
	function edit_d($object) {
        // ��ȡ�ɵ���ⵥ��Ϣ
        $org = parent::get_d($object['id']);

		try {
			$this->start_d();
			if(is_array($object['items'])) {
				$editresult = parent::edit_d($object, true); //�޸���ⵥ��Ϣ

                //���������¼
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->compareModelObj($this->tbl_name, $org, $object, $object['docStatus'] == "YSH" ? '������' : '�޸����');

				// k3������ش���
				$productinfoDao = new model_stock_productinfo_productinfo();
				$object['items'] = $productinfoDao->k3CodeFormatter_d($object['items']);

				// ����һ������ID
				$productIds = array();

				//������ⵥ�����嵥
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $itemObj) { //���ù�����Ϣ
					$itemObj['unHookNumber'] = $itemObj['actNum'];
					$itemObj['unHookAmount'] = $itemObj['price'] * $itemObj['actNum'];

					if (!in_array($itemObj['productId'], $productIds)) {
						$productIds[] = $itemObj['productId'];
					}

					//��ɫ��ⵥ�������к�ͬʱ���浽�嵥�У���������
					if($object['isRed'] == "0") {
						if(!empty($itemObj['serialSequence'])) {
							$itemObj['serialnoName'] = $itemObj['serialSequence'];
						}
					}
					array_push($itemsObjArr, $itemObj);

				}
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $itemsObjArr);
				$itemsObj = $stockinItemDao->saveDelBatch($itemsArr);

				//���ò��Դ������ҵ��
				$stockinStrategy = $this->stockinStrategyArr[$object['docType']];
				if($stockinStrategy) {
					$paramArr = array(//���ݻ�����Ϣ����
						'docId' => $object['id'], 'docCode' => $object['docCode'],
						'docType' => $object['docType'], 'docStatus' => $object['docStatus'],
						'relDocId' => $object['relDocId'], 'relDocCode' => $object['relDocCode'],
						'relDocType' => $object['relDocType'],
                        'auditDate' => $object['auditDate'], 'isRed' => $object['isRed']
					);

					if ($object['purOrderId'] > 0) {
						$paramArr['auditDate'] = $this->getEntryDateForPurOrderId_d($object['purOrderId'],
							implode(',', $productIds), false, false);
					}

					$dealRelInfoResult = $this->ctDealRelInfoAtEdit(new $stockinStrategy(), $paramArr, $itemsObj);
					if(!$dealRelInfoResult) {
						throw new Exception("���ҵ����������ȷ�ϣ�");
					}
				} else {
					throw new Exception("�����������δ���ţ�����ϵ������Ա!");
				}

				/*start:�����ʼ� ,������Ϊ�ύʱ�ŷ���*/
				if($object['docStatus'] == "YSH") {
					if($object['relDocType'] != "RZCRK") {
						if(isset($object['email'])){
							$emailArr = $object['email'];
							if($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
								$datadictDao = new model_system_datadict_datadict();
								$sendContent = "��λ��:<br/>�����������Ϣ�����,��֪Ϥ.<br/>";
								$sendContent .= "<table><tr><td>���ݱ��:</td><td>" . $object['docCode'] . "</td><td>������λ:</td><td>" . $object['purchaserName'] . "</td></tr>"
										. "<tr><td>Դ������:</td><td>" . $datadictDao->getDataNameByCode($object['relDocType']) . "</td><td>Դ�����:</td><td>" . $object['relDocCode'] . "</td></tr>"
												. "<tr><td>�ͻ�����:</td><td>" . $object['clientName'] . "</td><td></td><td></td></tr>"
														. "<tr><td>��ע:</td><td colspan='3'>" . $object['remark'] . "</td></tr></table>";
							
								$sendContent .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>���ϱ��</b></td><td><b>��������</b></td><td><b>����ͺ�</b></td><td><b>����</b></td><td><b>���κ�</b></td><td><b>���к�</b></td></tr>";
								$seNum = 1;
								foreach($itemsObj as $kValue) {
									$sendContent .= <<<EOT
									<tr align="center">
										<td>$seNum</td>
										<td>$kValue[productCode]</td>
										<td>$kValue[productName]</td>
										<td>$kValue[pattern]</td>
										<td>$kValue[actNum]</td>
										<td>$kValue[batchNum]</td>
										<td>$kValue[serialnoName]</td>
									</tr>
EOT;
									$seNum++;
								}
								$sendContent .= "</table>";
								$emailDao = new model_common_mail();
								$emailDao->mailClear("��Ʒ���֪ͨ", $emailArr['TO_ID'], $sendContent);
							}
						}
					} else {//�ʲ����-�ύʱ�ʼ�֪ͨ
						$requireoutDao = new model_asset_require_requireout();
						$rs = $requireoutDao->find(array('id' => $object['relDocId']), null, 'applyId');
						$this->mailDeal_d('assetInStock', $rs['applyId'], array('id' => $object['id']));
					}
				}
				/*end:�����ʼ� ,������Ϊ�ύʱ�ŷ���*/

				$this->commit_d();
				return $editresult;
			} else {
				throw new Exception("������Ϣ����������ȷ��!");
			}
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

    /**
     * ����ɾ������
     */
    function deletes_d($id)
    {
        // ��ȡ�ɵ���ⵥ��Ϣ
        $org = parent::get_d($id);

        try {
            $this->deletes($id);

            //���������¼
            $logSettingDao = new model_syslog_setting_logsetting();
            $logSettingDao->deleteObjLog($this->tbl_name, $org);

            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

	/**
	 * ��������id��ȡ��ⵥ������Ϣ�������嵥��Ϣ
	 * @param $id
	 * @param istockin $istrategy
	 * @return bool|mixed
	 */
	function get_d($id, istockin $istrategy) {
		$stockinObj = parent::get_d($id);
		$stockinObj['items'] = $istrategy->getItem($id);
		return $stockinObj;
	}

	/**
	 * ���ݵ��ݱ�Ų鿴��ⵥ��Ϣ
	 * @param $docCode
	 * @param istockin $istrategy
	 * @return bool|mixed
	 */
	function findByDocCode($docCode, istockin $istrategy) {
		$this->searchArr = array("nDocCode" => $docCode);
		$inStockArr = $this->list_d();
		$stockinObj = parent::get_d($inStockArr[0]['id']);
		$stockinObj['items'] = $istrategy->getItem($inStockArr[0]['id']);
		return $stockinObj;
	}

	/**
	 * �鿴�������Դ����ҵ����Ϣ
	 * @param istockin $istrategy
	 * @param bool $paramArr
	 * @return mixed
	 */
	function ctViewRelInfo(istockin $istrategy, $paramArr = false) {
		return $istrategy->viewRelInfo($paramArr);
	}

	/**
	 * ������ⵥʱԴ����ҵ����
	 * @param istockin $istrategy
	 * @param bool $paramArr
	 * @param bool $relItemArr
	 * @return mixed
	 */
	function ctDealRelInfoAtAdd(istockin $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtAdd($paramArr, $relItemArr);
	}

	/**
	 * �޸��������ʱԴ����ҵ����
	 * @param istockin $istrategy
	 * @param bool $paramArr
	 * @param bool $relItemArr
	 * @return mixed
	 */
	function ctDealRelInfoAtEdit(istockin $istrategy, $paramArr = false, $relItemArr = false) {
		return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
	}

    /**
     * ��ѯ���кŵ�״̬ PMS2241 2017-01-06
     * @param $SerialnoNames
     * @return array|bool
     */
	function chkSerialnoState($SerialnoNames = '',$SerialnoIds = ''){
        $sql = '';
        if($SerialnoNames != ''){
            $sql = "select seqStatus from oa_stock_product_serialno where FIND_IN_SET(sequence,'{$SerialnoNames}') > 0;";
        }else if($SerialnoIds != ''){
            $sql = "select seqStatus from oa_stock_product_serialno where id in ({$SerialnoIds});";
        }

        $seqStatusArr = ($sql != '')? $this->_db->getArray($sql) : array();
        return $seqStatusArr;
    }

	/**
	 * �������ⵥ
	 * @param $id
	 * @param istockin $istrategy
	 * @return bool
	 * @throws Exception
	 */
	function ctCancelAudit($id, istockin $istrategy) {
		try {
			$this->start_d();
			$stockinObj = $this->get_d($id, $istrategy);

			// ����һ������ID
			$productIds = array();

            // ��ѯ���к�״̬ PMS2241 2017-01-06
            $noInStockSerialno = false;// �Ƿ���ڷǿ�������кű�ʾ,Ĭ��false,��ʾ���ǿ����
            foreach($stockinObj['items'] as $val) {
				if (!in_array($val['productId'], $productIds)) {
					$productIds[] = $val['productId'];
				}
                if($val['serialnoName'] != '' || $val['serialnoId'] != ''){
                    $data = $this->chkSerialnoState('',$val['serialnoId']);
                    foreach($data as $key => $val){
                        //ֻҪ��һ�����кŵ�״̬��Ϊ����е�,�õ��ݲ��÷����
                        if($val['seqStatus'] != 0){
                            $noInStockSerialno = true;
                        }
                    }
                }
            }
            if($noInStockSerialno){
                throw new Exception("�����д������к�״̬��Ϊ����е�,��ֹ�����!");
            }else if("YSH" == $stockinObj['docStatus']) {
				//����ˣ����¼�ʱ���
				if($stockinObj['items']) {
					if($stockinObj['isRed'] == 1) {
						$state = false;
					} else {
						$state = true;
					}
					if($stockinObj['relDocType'] == 'RSLTZD' && $stockinObj['isRed'] == 1) {
						$conditions['arrivalId'] = $stockinObj['relDocId'];
						foreach($stockinObj['items'] as $val) {
							$conditions['productId'] = $val['productId'];
							$this->updateArrivalNum_d($conditions, $val, $state);
						}
					}
					if($stockinObj['relDocType'] == 'RTLTZD') {
						$arrivalId = $this->get_table_fields('oa_purchase_delivered', "id='" . $stockinObj['relDocId'] . "'", 'sourceId');
						$conditions['arrivalId'] = $arrivalId;
						foreach($stockinObj['items'] as $val) {
							$conditions['productId'] = $val['productId'];
							$this->updateArrivalNum_d($conditions, $val, $state);
						}
					}
				}
				$obj = array("id" => $id, "docStatus" => "WSH");
				$this->updateById($obj);

				if ($stockinObj['purOrderId'] > 0) {
					$stockinObj['auditDate'] = $this->getEntryDateForPurOrderId_d($stockinObj['purOrderId'],
						implode(',', $productIds), false, false);
				}

				if(!$istrategy->cancelAudit($stockinObj)) {
					throw new Exception("���ݷ����ʧ��");
				}

                //���������¼
                $logSettingDao = new model_syslog_setting_logsetting();
                $logSettingDao->addObjLog($this->tbl_name, $id, array(), 'ȡ�����');
			} else {
				throw new Exception("����״̬�ѷ����!");
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * �޸�����
	 * @param $object
	 * @return mixed
	 */
	function parentEdit($object) {
		return parent::edit_d($object);
	}

	/**
	 * ������� - ͬ���ı����ݲ�����ʾ
	 * @param $object
	 * @return mixed
	 */
	function filterRows_d($object) {
		if($object) {
			$markId = null;
			foreach($object as $key => $val) {
				if($markId == $val['mainId']) {
					unset($object[$key]['docCode']);
					unset($object[$key]['docStatus']);
					unset($object[$key]['supplierName']);
					unset($object[$key]['auditDate']);
					unset($object[$key]['catchStatus']);
					$object[$key]['isRed'] = 2;
				} else {
					$markId = $val['mainId'];
				}
			}
		}
		return $object;
	}

	/**
	 * ��ȡ��ǰ��������
	 */
	function rtThisPeriod_d() {
		$periodDao = new model_finance_period_period();
		return $periodDao->rtThisPeriod_d();
	}

	/**
	 * �޸���ⵥ - �������޸ļ۸�
	 * @param $object
	 * @return null
	 */
	function editPrice_d($object) {
		try {
			$this->start_d();
			if(is_array($object['items'])) {
				$editresult = parent::edit_d($object, true); //�޸���ⵥ��Ϣ
				//������ⵥ�����嵥
				$stockinItemDao = new model_stock_instock_stockinitem();
				$itemsObjArr = array();
				foreach($object['items'] as $key => $itemObj) { //���ù�����Ϣ
					array_push($itemsObjArr, $itemObj);
				}
				$itemsArr = $this->setItemMainId("mainId", $object['id'], $itemsObjArr);
				$stockinItemDao->saveDelBatch($itemsArr);

				$this->commit_d();
				return $editresult;
			} else {
				throw new Exception("������Ϣ������,����ȷ��!");
			}
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/******************************st:storageapply�еķ���*********************************************************/

	/**
	 * ��������Ϊ��һԪ��
	 * @param $array
	 * @return array
	 */
	function a_array_unique($array) {
		$out = array();
		foreach($array as $key => $value) {
			if(!in_array($value, $out)) {
				$out[$key] = $value;
			}
		}
		return $out;
	}

	/**
	 * ���ù����嵥�Ĵӱ�����뵥id��Ϣ
	 * @param $mainIdName
	 * @param $mainIdValue
	 * @param $iteminfoArr
	 * @return array
	 */
	function setItemMainId($mainIdName, $mainIdValue, $iteminfoArr) {
		$resultArr = array();
		foreach($iteminfoArr as $value) {
			$value[$mainIdName] = $mainIdValue;
			array_push($resultArr, $value);
		}
		return $resultArr;
	}

	/**
	 * ����id ��ȡ�⹺��ⵥ
	 * @param  $ids
	 * @param  $docType
	 */
	function findAllItemByIds($ids, $docType = 'RKPURCHASE') {
		$searchArr = array("ids" => $ids, "docType" => $docType, "docStatus" => "YSH");
		$this->searchArr = $searchArr;
		$this->sort = "c.id";
		return $this->listBySqlId("select_item");
	}

	/**
	 * �⹺��ⵥ���������߼�����
	 * @param $stockinOrder
	 * @return bool|null
	 */
	function unionOrder($stockinOrder) {
		try {
			$this->start_d();
			$stockinItemDao = new model_stock_instock_stockinitem();
			$stockinObj = $this->get_d($stockinOrder['id'], new model_stock_instock_strategy_purchasestockin());

			//����������Ϣ
			$tempStockInObj = array("id" => $stockinOrder['id'], "relDocType" => "RCGDD", "relDocId" => $stockinOrder['purOrderId'], "relDocCode" => $stockinOrder['purOrderCode'], "purOrderCode" => $stockinOrder['purOrderCode'], "purOrderId" => $stockinOrder['purOrderId']);
			$this->updateById($tempStockInObj);

			//���´ӱ���Ϣ ,����productIdƥ��
			$stockinItemArr = $stockinObj['items'];
			foreach($stockinItemArr as $key => $value) { //����嵥
				$tempStockinItemObj = array("id" => $value['id']);
				foreach($stockinOrder['orderItems'] as $oKey => $oValue) { //�����嵥
					if($value['productId'] == $oValue['productId']) {
						$tempStockinItemObj['relDocId'] = $oValue['id'];
						$tempStockinItemObj['price'] = $oValue['price'];
						$tempStockinItemObj['subPrice'] = $value['actNum'] * $oValue['price'];
						$tempStockinItemObj['unHookAmount'] = $value['actNum'] * $oValue['price'];
						$stockinItemDao->updateById($tempStockinItemObj);
						break;
					}
				}
			}

			//���¹���������Ϣ
			$purchasecontractDao = new model_purchase_contract_purchasecontract();
			foreach($stockinOrder['orderItems'] as $oKey => $oValue) { //�����嵥
				$purchasecontractDao->updateInStock($stockinOrder['purOrderId'], $oValue['id'], $oValue['productId'], $oValue['unionNum']);
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}

	}

	/**
	 * �⹺��ⵥ����֪ͨ���߼�����
	 * @param $stockinOrder
	 * @return bool|null
	 */
	function unionArrival($stockinOrder) {
		try {
			$this->start_d();
			$stockinItemDao = new model_stock_instock_stockinitem();
			$stockinObj = $this->get_d($stockinOrder['id'], new model_stock_instock_strategy_purchasestockin());

			//����������Ϣ
			$tempStockInObj = array("id" => $stockinOrder['id'], "relDocType" => "RSLTZD", "relDocId" => $stockinOrder['arrivalId'], "relDocCode" => $stockinOrder['arrivalCode'], "purOrderCode" => $stockinOrder['purOrderCode'], "purOrderId" => $stockinOrder['purOrderId'], "purchaserCode" => $stockinOrder['purchaserCode'], "purchaserName" => $stockinOrder['purchaserName']);
			$this->updateById($tempStockInObj);

			//���´ӱ���Ϣ ,����productIdƥ��
			$stockinItemArr = $stockinObj['items'];
			foreach($stockinItemArr as $key => $value) { //����嵥
				$tempStockinItemObj = array("id" => $value['id']);
				foreach($stockinOrder['arrivalItems'] as $oKey => $oValue) { //�����嵥
					if($value['productId'] == $oValue['productId']) {
						$tempStockinItemObj['relDocId'] = $oValue['id'];
						$tempStockinItemObj['price'] = $oValue['price'];
						$tempStockinItemObj['subPrice'] = $value['actNum'] * $oValue['price'];
						$tempStockinItemObj['unHookAmount'] = $value['actNum'] * $oValue['price'];
						$stockinItemDao->updateById($tempStockinItemObj);
						break;
					}
				}
			}

			//���¹���������Ϣ
			$purchaseArrivalDao = new model_purchase_arrival_arrival();
			foreach($stockinOrder['arrivalItems'] as $oKey => $oValue) { //�����嵥
				$purchaseArrivalDao->updateInStock($stockinOrder['arrivalId'], $oValue['id'], $oValue['productId'], $oValue['unionNum']);
			}
			$this->commit_d();
			return true;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * �����⹺��ⵥ
	 * @param $excelData
	 * @return array|null
	 */
	function importPurchaseStockin($excelData) {
		try {
			$this->start_d();
			$codeDao = new model_common_codeRule();
			$purchasecontractDao = new model_purchase_contract_purchasecontract(); //�ɹ�����Dao
			$purEquDao = new model_purchase_contract_equipment();
			$dataDictDao = new model_system_datadict_datadict(); //�����ֵ�
			$inStockItemDao = new model_stock_instock_stockinitem(); //����嵥Dao
			$productinfoDao = new model_stock_productinfo_productinfo(); //��ƷDao
			$supplierDao = new model_supplierManage_formal_flibrary(); //��Ӧ��Dao
			$userDao = new model_deptuser_user_user(); //�û�Dao
			$stockDao = new model_stock_stockinfo_systeminfo(); //�ֿ�����Dao

			$cgfsArr = $dataDictDao->getDatadictsByParentCodes("cgfs");
			$stockSysObj = $stockDao->get_d("1");

			$resultArr = array();
			foreach($excelData as $key => $value) {
				$stockinObject = array();
				if(!empty($value[0])) {
					if(date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900))) < '2011-09-01') { //С��20110901
						$productinfoArr = $productinfoDao->getProByCode($value[6]);
						$supplierArr = $supplierDao->findBy("suppName", $value[2]);
						$purchUser = $userDao->getUserByName($value[4]);
						if(!empty($value[3])) { //�ж�����ŵ�
							$purchasecontractArr = $purchasecontractDao->findBy("hwapplyNumb", $value[3]);

							if(is_array($purchasecontractArr)) { //�����Ѳ�¼
								if(is_array($productinfoArr)) { //��Ʒ��Ϣ����
									if(is_array($purchUser)) {
										if(is_array($supplierArr)) {
											$purchMethod = "XG"; //�ɹ���ʽ
											$stockinObject['auditDate'] = date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900)));
											$stockinObject['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr["RKPURCHASE"]);
											$stockinObject['isRed'] = "0";
											$stockinObject['relDocType'] = "RCGDD";
											//									print_r($purchasecontractArr);
											$stockinObject['relDocCode'] = $purchasecontractArr['hwapplyNumb'];
											$stockinObject['relDocId'] = $purchasecontractArr['id'];

											foreach($cgfsArr as $cKey => $dataObj) {
												if($dataObj['dataName'] == $value[1]) {
													$purchMethod = $dataObj['dataCode'];
													break;
												}
											}

											$stockinObject['purchMethod'] = $purchMethod;
											$stockinObject['accountingCode'] = 'KJKM1';
											$stockinObject['supplierName'] = $supplierArr['suppName'];
											$stockinObject['supplierId'] = $supplierArr['id'];
											$stockinObject['purOrderCode'] = $purchasecontractArr['hwapplyNumb'];
											$stockinObject['purOrderId'] = $purchasecontractArr['id'];
											$stockinObject['purchaserName'] = $purchUser['USER_NAME'];
											$stockinObject['purchaserCode'] = $purchUser['USER_ID'];
											$stockinObject['docType'] = 'RKPURCHASE';
											$stockinObject['docStatus'] = 'YSH';
											$stockinObject['auditerName'] = $_SESSION['USERNAME'];
											$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
											$stockinObject['catchStatus'] = "CGFPZT-WGJ";
											$stockinObject['remark'] = $value[4];
											$id = parent::add_d($stockinObject, true);

											$stockInItemObj = array("mainId" => $id, "productId" => $productinfoArr['id'], "productName" => $productinfoArr['productName'], "productCode" => $productinfoArr['productCode'], "pattern" => $productinfoArr['pattern'], "unitName" => $productinfoArr['unitName'], "batchNum" => $value['10'], "actNum" => $value['11'], "price" => $value['13'], "subPrice" => $value['14'], "inStockId" => $stockSysObj['salesStockId'], "inStockCode" => $stockSysObj['salesStockCode'], "inStockName" => $stockSysObj['salesStockName'], "unHookNumber" => $value['11'], "unHookAmount" => $value['14']);
											//�����������������
											$purEquObj = $purEquDao->find(array("productId" => $productinfoArr['id'], "basicId" => $purchasecontractArr['id']));
											if(is_array($purEquObj)) {
												$purchasecontractDao->updateInStock($purchasecontractArr['id'], $purEquObj['id'], $productinfoArr['id'], $value['11']);
												$stockInItemObj['relDocId'] = $purEquObj['id'];
											}
											$inStockItemDao->add_d($stockInItemObj);

											array_push($resultArr, array("docCode" => "����:" . $stockinObject['auditDate'] . ",��Ӧ��:" . $value[2] . "���ϱ��:" . $value[6], "result" => "����ɹ�!"));
										} else {
											array_push($resultArr, array("docCode" => $value[2], "result" => "����ʧ��,��Ӧ��Ϊ" . $value[2] . "����Ϣϵͳ�в�����!"));
										}

									} else {
										array_push($resultArr, array("docCode" => $value[6], "result" => "����ʧ��,�ɹ�ԱΪ" . $value[4] . "����Ϣϵͳ�в�����!"));
									}

								} else {
									array_push($resultArr, array("docCode" => $value[6], "result" => "����ʧ��,���ϱ���Ϊ" . $value[6] . "������!"));
								}
							} else {
								array_push($resultArr, array("docCode" => $value[2], "result" => "����ʧ��,�������Ϊ" . $value[3] . "������!"));
							}
						} else { //û�ж�����ŵ�
							if(is_array($productinfoArr)) { //��Ʒ��Ϣ����
								if(is_array($purchUser)) {
									if(is_array($supplierArr)) {
										$purchMethod = "XG"; //�ɹ���ʽ
										$stockinObject['auditDate'] = date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900)));
										$stockinObject['docCode'] = $codeDao->stockCode("oa_stock_instock", $this->stockinPreArr["RKPURCHASE"]);
										$stockinObject['isRed'] = "0";

										foreach($cgfsArr as $cKey => $dataObj) {
											if($dataObj['dataName'] == $value[1]) {
												$purchMethod = $dataObj['dataCode'];
												break;
											}
										}

										$stockinObject['purchMethod'] = $purchMethod;
										$stockinObject['accountingCode'] = 'KJKM1';
										$stockinObject['supplierName'] = $supplierArr['suppName'];
										$stockinObject['supplierId'] = $supplierArr['id'];
										$stockinObject['purchaserName'] = $purchUser['USER_NAME'];
										$stockinObject['purchaserCode'] = $purchUser['USER_ID'];
										$stockinObject['docType'] = 'RKPURCHASE';
										$stockinObject['docStatus'] = 'YSH';
										$stockinObject['auditerName'] = $_SESSION['USERNAME'];
										$stockinObject['auditerCode'] = $_SESSION['USER_ID'];
										$stockinObject['catchStatus'] = "CGFPZT-WGJ";
										$stockinObject['remark'] = $value[4];
										$id = parent::add_d($stockinObject, true);

										$stockInItemObj = array("mainId" => $id, "productId" => $productinfoArr['id'], "productName" => $productinfoArr['productName'], "productCode" => $productinfoArr['productCode'], "pattern" => $productinfoArr['pattern'], "unitName" => $productinfoArr['unitName'], "batchNum" => $value['10'], "actNum" => $value['11'], "price" => $value['13'], "subPrice" => $value['14'], "inStockId" => $stockSysObj['salesStockId'], "inStockCode" => $stockSysObj['salesStockCode'], "inStockName" => $stockSysObj['salesStockName'], "unHookNumber" => $value['11'], "unHookAmount" => $value['14']);
										$inStockItemDao->add_d($stockInItemObj);

										array_push($resultArr, array("docCode" => "����:" . $stockinObject['auditDate'] . ",��Ӧ��:" . $value[2] . "���ϱ��:" . $value[6], "result" => "����ɹ�!"));
									} else {
										array_push($resultArr, array("docCode" => $value[2], "result" => "����ʧ��,��Ӧ��Ϊ" . $value[2] . "����Ϣϵͳ�в�����!"));
									}
								} else {
									array_push($resultArr, array("docCode" => $value[6], "result" => "����ʧ��,�ɹ�ԱΪ" . $value[4] . "����Ϣϵͳ�в�����!"));
								}
							} else {
								array_push($resultArr, array("docCode" => $value[6], "result" => "����ʧ��,���ϱ���Ϊ" . $value[6] . "������!"));
							}
						}
					} else {
						array_push($resultArr, array("docCode" => $value[2], "result" => "����ʧ��,�������ڲ��ܴ���20110901=>" . date('Y-m-d',(mktime(0, 0, 0, 1, $value[0] - 1, 1900)))));
					}
				}
			}
			$this->commit_d();
			return $resultArr;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/**
	 * ����δ������ⵥ
	 * @param $excelData
	 * @return array|null
	 */
	function importWgjDoc($excelData) {
		try {
			$this->start_d();
			$resultArr = array();
			$supplierDao = new model_supplierManage_formal_flibrary(); //��Ӧ��Dao
			$userDao = new model_deptuser_user_user(); //�û�Dao
			$productDao = new model_stock_productinfo_productinfo();
			$instockItemDao = new model_stock_instock_stockinitem();
			$stockDao = new model_stock_stockinfo_stockinfo();
			$lastDocCode = "";
			$lastMainId = "";
			foreach($excelData as $key => $obj) {
				if(!empty($obj['0']) && !empty($obj[4])) {
					$productDao->searchArr['ext2'] = $obj[4];
					$productArr = $productDao->listBySqlId();
					if(is_array($productArr)) {
						if($obj['1'] != $lastDocCode) {

							$supplierArr = $supplierDao->findBy("suppName", $obj[2]);
							$purchUser = $userDao->getUserByName($obj[3]);
							$isRed = $obj[7] > 0 ? "0" : "1";
							$proNum = $obj[7] > 0 ? $obj[7] : -$obj[7];
							$proAmount = $obj[8] > 0 ? $obj[8] : -$obj[8];
							$stockObj = array('auditDate' => $obj[0],
								'isRed' => $isRed,
								'docCode' => $obj['1'] . "K3",
								'supplierId' => $supplierArr['id'],
								'supplierName' => $supplierArr['suppName'],
								'purchaserName' => $purchUser['USER_NAME'],
								'purchaserCode' => $purchUser['USER_ID'],
								'docType' => 'RKPURCHASE',
								'docStatus' => 'YSH',
								'auditerName' => $_SESSION['USERNAME'],
								'auditerCode' => $_SESSION['USER_ID'],
								'catchStatus' => "CGFPZT-WGJ",
								'remark' => $obj[4]
							);
							$lastMainId = parent::add_d($stockObj, true);
							$lastDocCode = $obj['1'];

						}

						$stockObj = $stockDao->findBy("stockName", $obj[6]);
						$instockItem = array("mainId" => $lastMainId, "productId" => $productArr['0']['id'], "productCode" => $productArr['0']['productCode'], "productName" => $productArr['0']['productName'], "pattern" => $productArr['0']['pattern'], "unitName" => $productArr['0']['unitName'], "storageNum" => $proNum, "actNum" => $proNum, "price" => round($proAmount / $proNum, 6), "subPrice" => $proAmount, "unHoodNum" => $proNum, "unHoodAmount" => $proAmount, "inStockName" => $stockObj['stockName'], "inStockId" => $stockObj['id'], "inStockCode" => $stockObj['stockCode']);
						$instockItemDao->add_d($instockItem);
						array_push($resultArr, array("docCode" => "k3����:" . $obj[4] . ",���ݱ��:" . $obj[1], "result" => "����ɹ�!"));

					} else {
						array_push($resultArr, array("docCode" => "k3����:" . $obj[4] . ",���ݱ��:" . $obj[1], "result" => "k3���벻���ڣ�����ɹ�!"));
					}
				}
			}

			$this->commit_d();
			return $resultArr;
		} catch(Exception $e) {
			$this->rollBack();
			return null;
		}
	}

	/*****************************�ϲ��²�**************************/

	/**
	 * �ж�����Դ����Ϣ�Ƿ������Ƶ��⹺��ⵥ
	 */
	function hasSource($objId, $objType) {
		$this->searchArr = array('relDocId' => $objId, 'relDocType' => $objType);
		if(is_array($this->listBySqlId('select_default'))) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * �ɹ���Ʊ����Ĭ�ϻ�ȡ��ⵥ
	 */
	function getPurchaseOutstock_d($supplierId) {
		$this->searchArr = array('supplierId' => $supplierId, 'docType' => 'RKPURCHASE', 'docStatus' => 'YSH', 'catchStatusNo' => 'CGFPZT-YGJ');
		$this->sort = "c.auditDate";
		$rs = $this->listBySqlId('select_item');
		return $this->initHook_d($rs);
	}

	/**
	 * �����ⵥ��ʽ��Ⱦ
	 */
	function initHook_d($object) {
		$str = null;
		if(is_array($object)) {
			$i = 0;
			$dataArr = null;
			$markId = null;
			foreach($object as $key => $val) {
				$i++;
				$tr_class = $i % 2 == 0 ? 'tr_even' : 'tr_odd';

				if(empty($markId) || $markId != $val['mainId']) {

					//�ж�״̬�ֶ��Ƿ��������У�Ȼ��������Ӧ�Ĵ���
					if(!isset($dataArr[$val['catchStatus']])) {
						$dataArr = $this->datadictArrSearch_d($dataArr, $val['catchStatus']);
					}
					$hookType = $dataArr[$val['catchStatus']];

					if($val['isRed'] == 1) {
						$codeStr = "<span class='red'>$val[docCode]</span>";
					} else {
						$codeStr = "<span>$val[docCode]</span>";
					}

					$str .= <<<EOT
						<tr class="storageList_$val[mainId] $tr_class">
							<td>
								<input type="text" name="storage[$i][number]" id="stonumber$i" value="$val[actNum]" class="txtshort" onblur="checkInput('stonumber$i','oldpronumber$i');">
								<input type="hidden" name="storage[$i][hookMainId]" id="stoHookManId$val[mainId]" value="$val[mainId]">
								<input type="hidden" name="storage[$i][hookId]" value="$val[id]">
								<input type="hidden" id="oldpronumber$i" value="$val[actNum]">
								<input type="hidden" name="storage[$i][hookObjCode]" value="$val[docCode]">
								<input type="hidden" name="storage[$i][formDate]" id="stoFormDate$i" value="$val[auditDate]">
								<input type="hidden" id="storageId_$i" value="$val[inStockId]">
								<input type="hidden" id="isRed$i" value="$val[isRed]">
		                    </td>
		                    <td>
		                        $val[auditDate]
		                    </td>
		                    <td>
		                    	$codeStr<img src="images/closeDiv.gif" onclick="delStorage($val[mainId])" title="ɾ����">
		                    </td>
		                    <td>
		                        $val[inStockName]
		                    </td>
		                    <td>
		                        $hookType
		                    </td>
		                    <td>
		                        $val[productCode]
		                        <input type="hidden" name="storage[$i][unHookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][hookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][productName]" value="$val[productName]">
		                        <input type="hidden" name="storage[$i][productNo]" value="$val[productNo]">
		                        <input type="hidden" name="storage[$i][productId]" id="storagePN$i" value="$val[productId]">
		                        <input type="hidden" name="storage[$i][cost]" value="$val[cost]">
		                        <input type="hidden" name="storage[$i][stockId]" value="$val[inStockId]">
		                        <input type="hidden" name="storage[$i][stockName]" value="$val[inStockName]">
		                    </td>
		                    <td>
		                        $val[productName]
		                    </td>
		                    <td>
		                        $val[actNum]
		                    </td>
		                    <td>
		                        $val[hookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[hookAmount]</span>
		                    </td>
		                    <td>
		                        $val[unHookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[unHookAmount]</span>
		                    </td>
		                </tr>
EOT;
				} else {
					$str .= <<<EOT
						<tr class="storageList_$val[mainId] $tr_class">
							<td>
								<input type="text" name="storage[$i][number]" id="stonumber$i" value="$val[actNum]" class="txtshort" onblur="checkInput('stonumber$i','oldpronumber$i');">
								<input type="hidden" name="storage[$i][hookMainId]" id="stoHookManId$val[mainId]" value="$val[mainId]">
								<input type="hidden" name="storage[$i][hookId]" value="$val[id]">
								<input type="hidden" id="oldpronumber$i" value="$val[actNum]">
								<input type="hidden" name="storage[$i][hookObjCode]" value="$val[docCode]">
								<input type="hidden" name="storage[$i][formDate]" id="stoFormDate$i" value="$val[auditDate]">
								<input type="hidden" id="storageId_$i" value="$val[inStockId]">
								<input type="hidden" id="isRed$i" value="$val[isRed]">
		                    </td>
		                    <td colspan="4">
		                    </td>
		                    <td>
		                        $val[productCode]
		                        <input type="hidden" name="storage[$i][unHookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][hookNumber]" value="$val[hookNumber]">
		                        <input type="hidden" name="storage[$i][productName]" value="$val[productName]">
		                        <input type="hidden" name="storage[$i][productNo]" value="$val[productNo]">
		                        <input type="hidden" name="storage[$i][productId]" id="storagePN$i" value="$val[productId]">
		                        <input type="hidden" name="storage[$i][cost]" value="$val[cost]">
		                        <input type="hidden" name="storage[$i][stockId]" value="$val[inStockId]">
		                        <input type="hidden" name="storage[$i][stockName]" value="$val[inStockName]">
		                    </td>
		                    <td>
		                        $val[productName]
		                    </td>
		                    <td>
		                        $val[actNum]
		                    </td>
		                    <td>
		                        $val[hookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[hookAmount]</span>
		                    </td>
		                    <td>
		                        $val[unHookNumber]
		                    </td>
		                    <td>
		                        <span class="formatMoney">$val[unHookAmount]</span>
		                    </td>
		                </tr>
EOT;
				}
				$markId = $val['mainId'];
			}
		}
		return $str;
	}

	/**
	 * �����ֵ��ѯ����
	 * @param $dataArr
	 * @param $dataCode
	 * @return mixed
	 */
	function datadictArrSearch_d($dataArr, $dataCode) {
		$datadictDao = new model_system_datadict_datadict();
		$rtName = $datadictDao->getDataNameByCode($dataCode);
		$dataArr[$dataCode] = $rtName;
		return $dataArr;
	}

	/**
	 * ��ȡ�⹺��ⵥ�����������������ʱ��
	 * @param $purOrderId
	 * @return mixed
	 */
	function getRecentlyDate($purOrderId) {
		$sql = "select max(auditDate) as auditDate,purOrderId from oa_stock_instock
					where isRed='0' and purOrderId='$purOrderId' and docType='RKPURCHASE'
				  	group by purOrderId";
		return $this->findSql($sql);
	}

	/**
	 *
	 * ��ȡ�������ϵ�������ʱ��
	 * @param  $purOrderId
	 * @param  $productId
	 * @return mixed
	 */
	function getOrderProLastDate($purOrderId, $productId) {
		$sql = "select max(auditDate) as docDate from oa_stock_instock i INNER JOIN oa_stock_instock_item ii on(ii.mainId=i.id)
 					where isRed='0' and purOrderId='$purOrderId' and docType='RKPURCHASE' and docStatus='YSH' and ii.productId='$productId';";
		if($record = $this->findSql($sql)) {
			return $record[0]['docDate'];
		} else {
			return "";
		}
	}

	/**
	 * �������������ϵ������Ƿ������д����
	 * @param $object
	 * @return int
	 */
	function checkAudit_d($object) {
		$equItems = $object['items'];
		if($object['relDocType'] == 'RZCRK') {//Դ������Ϊ�ʲ����
			$requireoutitemDao = new model_asset_require_requireoutitem();
			foreach($equItems as $key => $val) {
				if(is_numeric($object['relDocId']) && strlen($object['relDocId']) < 32) {
					$rs = $requireoutitemDao->find(array('id' => $val['relDocId']), null, 'number,executedNum');
					if($rs['executedNum'] + $val['actNum'] > $rs['number']) {
						return 4;//�������������������
					}
				}
			}
		}else if($object['relDocType'] == 'RSCJHD') {//Դ������Ϊ�����ƻ���
				$noticeequDao = new model_stock_withdraw_noticeequ();
				foreach($equItems as $key => $val) {
					$rs = $noticeequDao->find(array('mainId' => $val['relDocId'],'productId' => $val['productId']), null, 'number,executedNum');
					if($rs['executedNum'] + $val['actNum'] > $rs['number']) {
						return 4;//�������������������
					}
				}
		}else {
			$equipmentDao = new model_purchase_arrival_equipment();
			foreach($equItems as $key => $val) {
				$equipmentArr = $equipmentDao->find(array('id' => $val['relDocId']), null, 'qualityPassNum,contractId');
				if($val['actNum'] > $equipmentArr['qualityPassNum']) {
					return 2;//����������ʼ�ϸ���
				}
                if($equipmentArr['contractId']) {
                    $sql = "select sum(c.actNum) as actNumCount from oa_stock_instock_item c left join oa_purchase_arrival_equ d on c.relDocId = d.id where"
                        . " c.relDocId in(select id from oa_purchase_arrival_equ where contractId = {$equipmentArr['contractId']}) group by d.contractId";
                    $arr = $this->_db->getArray($sql);
                    $applyequipmentDao = new model_purchase_apply_applyequipment();
                    $applyequipmentArr = $applyequipmentDao->find(array('id' => $equipmentArr['contractId']), null, 'amountAll');
                    if(($arr[0]['actNumCount'] + $val['actNum']) > $applyequipmentArr['amountAll']) {
                        return 3;//�ջ��������ڶ�������
                    }
                }
			}
		}
		return 1;//����
	}

	/**
	 * ��ˣ����¼�ʱ���
	 * @param $conditions
	 * @param $obj
	 * @param bool $state
	 * @return mixed
	 */
	function updateArrivalNum_d($conditions, $obj, $state = true) {
		$equDao = new model_purchase_arrival_equipment();
		$equipmentRow = $equDao->findAll($conditions);
		if($equipmentRow && $state == true) {
			$equipmentRow[0]['arrivalNum'] = $equipmentRow[0]['arrivalNum'] - $obj['actNum'];
			$result = $equDao->edit_d($equipmentRow[0]);
		} else if($equipmentRow && $state == false) {
			$equipmentRow[0]['arrivalNum'] = $equipmentRow[0]['arrivalNum'] + $obj['actNum'];
			$result = $equDao->edit_d($equipmentRow[0]);
		}
		return $result;
	}

	/**
	 * ��ȡ��������
	 * @param $productIdArr
	 * @return int
	 */
	function getLastInPrice_d($productIdArr) {

		// ���ؽ��
		$results = array();

		if(empty($productIdArr)) {
			return $results;
		}

		// ��ѯ���Ϸ�Χ
		$productIds = implode(",", $productIdArr);

		// ��ѯ���ϵ�������ⵥ��
		$sql = "SELECT c.auditDate, i.productId, i.productCode, i.productName, i.price FROM
				oa_stock_instock c INNER JOIN oa_stock_instock_item i ON c.id = i.mainId
			WHERE
				c.isRed = 0 AND c.docStatus = 'YSH' AND i.productId IN($productIds)
				AND i.price <> 0
			ORDER BY i.productCode, c.auditDate DESC";
		$rows = $this->_db->getArray($sql);

		if($rows) {
			foreach($rows as $v) {
				if(!isset($results[$v['productId']])) {
					$results[$v['productId']] = $v['price'];
				}
			}
		}

		return $results;
	}

	/**
	 * ���ݸ������͸��º��ֳ��ⵥ�ĵ���
	 * @param $params
	 * @return int
	 */
	function updateProductPrice_d($params) {

		// ��������
		$num = 0;

		// ��ȡ��������
		$updateType = $params['updateType'];
		unset($params['updateType']);

        if($updateType == 1){
            if(isset($params['thisYear'])){
                unset($params['thisYear']);
            }

            if(isset($params['thisMonth'])){
                unset($params['thisMonth']);
            }
        }

		// �б����ݻ�ȡ
		$this->getParam($params);
		$items = $this->listBySqlId('select_callist');

		// �ǿյ�ʱ��
		if(!empty($items)) {

			// �ȴ����������
			$waitItems = array();

			// ������Ҫ��ѯ������id
			$productIdArr = array();

			// ѭ����������
			foreach($items as $k => $v) {
				// �۸�Ϊ0ʱ��ȥ����
				if($v['price'] == 0) {

					// ����id����
					if (!in_array($v['productId'], $productIdArr)) {
						$productIdArr[] = $v['productId'];
					}

					// ���������ݻ���
					$waitItems[] = $v;
				}
			}

			// �������Ҫ��������ݣ���ô����е��ۻ�ȡ
			if(!empty($productIdArr)) {

				// ���ݸ������»�ȡ��Ӧ�ĵ��ݽ��
				switch($updateType) {
					case 0: // �������Ȩƽ����
						$stockbalanceDao = new model_finance_stockbalance_stockbalance();
						$priceArr = $stockbalanceDao->getBeginPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 1: // ���³����
						$stockoutDao = new model_stock_outstock_stockout();
						$priceArr = $stockoutDao->getLastOutPrice_d($params['thisYear'],
							$params['thisMonth'], $productIdArr);
						break;
					case 2: // ��������
						$priceArr = $this->getLastInPrice_d($productIdArr);
						break;
					default:
				}

				// �۸���
				if(!empty($priceArr)) {

					// �����ӱ��ʼ��
					$stockinitemDao = new model_stock_instock_stockinitem();

					// ����Ҫ����
					foreach($waitItems as $v) {
						$price = $priceArr[$v['productId']];
						if($price && $price != 0) {
							$stockinitemDao->update(
								array('id' => $v['id']),
								array(
									'price' => $price,
									'subPrice' => round(bcmul($price, $v['actNum'], 6), 2)
								)
							);

							$num++;
						}
					}
				}
			}
		}

		return $num;
	}

    /**
     * ͳ��ĳԭ�����������г���ⵥ���������������
     * @param $relDocId
     * @param string $docStatus // ���ݵ����״̬,�� "'YSH','SPZ'"
     * @return array
     */
	function getRelativeItemsCount($relDocId,$docStatus = ''){
        $ItemsArr = array();
        $extSql = ($docStatus != '')? " and o.docStatus in ({$docStatus})" : "";
        if($relDocId != '') {
            $getIdsSql = "select GROUP_CONCAT(id) as ids from oa_stock_instock where relDocId = {$relDocId}";
            $ids = $this->_db->getArray($getIdsSql);
            $ids = ($ids) ? $ids[0]['ids'] : '';
            $chkSql = "SELECT
					c.productId,
					c.productCode,
					o.isRed,
					c.actNum,
					o.docStatus,
					'instock' AS objType
				FROM
					oa_stock_instock_item c
				LEFT JOIN oa_stock_instock o ON c.mainId = o.id
				WHERE
					o.id in ({$ids}) {$extSql}
				UNION ALL
				SELECT
					c.productId,
					c.productCode,
					o.isRed,
					c.actOutNum AS actNum,
					o.docStatus,
					'outstock' AS objType
				FROM
					oa_stock_outstock_item c
				LEFT JOIN oa_stock_outstock o ON c.mainId = o.id
				WHERE
					o.relDocId in ({$ids}) {$extSql};";
            $resultArr = $this->_db->getArray($chkSql);
            if ($resultArr) {
                foreach ($resultArr as $k => $v) {
                    if (!isset($ItemsArr[$v['productId']])) {
                        $ItemsArr[$v['productId']]['Code'] = $v['productCode'];
                        $ItemsArr[$v['productId']]['Num'] = 0;
                    }

                    if ($v['objType'] == 'instock' && $v['isRed'] == 0) {//�������
                        $ItemsArr[$v['productId']]['Num'] += $v['actNum'];
                    } else if ($v['objType'] == 'instock' && $v['isRed'] == 1) {//���쵥
                        $ItemsArr[$v['productId']]['Num'] -= $v['actNum'];
                    } else if ($v['objType'] == 'outstock' && $v['isRed'] == 0) {//��������
                        $ItemsArr[$v['productId']]['Num'] -= $v['actNum'];
                    } else if ($v['objType'] == 'outstock' && $v['isRed'] == 1) {//����쵥
                        $ItemsArr[$v['productId']]['Num'] += $v['actNum'];
                    }
                }
            }
        }
        return $ItemsArr;
    }

    /**
     * �رմ����ϴ�����ⵥ
     *
     * @param $id
     */
    function closeIdleScrapInStock($id){
        // ������ⵥΪ�ѹر�״̬
        $this->update(array('id'=>$id),array('docStatus'=>'YGB'));
        $stockoutStrategy = $this->stockinStrategyArr['RKDLBF'];
        $objArr = $this->get_d($id,new $stockoutStrategy());
        $serialnoDao = new model_stock_serialno_serialno();

        if($objArr){
            // �ڴ����ϴ��ϲ��м�ȥ��Ӧ����������
            $items = $objArr['items'];
            foreach ($items as $item){
                $stockId = $item['inStockId'];
                $productCode = $item['productCode'];
                $outNum = $item['actNum'];
                $updateSql = "UPDATE oa_stock_inventory_info set actNum = actNum-{$outNum},exeNum = exeNum-{$outNum} where stockId = {$stockId} and productCode = '{$productCode}';";
                $this->_db->query( $updateSql );

                // �������к�״̬����Ϊ�����
                if(!empty($item['serialnoId'])) {
                    $sequenceId = $item['serialnoId'];

                    $sequencObj['seqStatus'] = "0";
                    $serialnoDao->update("id in($sequenceId)", $sequencObj);
                }
            }
        }
    }

	/**
	 * ���ݲɹ�����ID��ȡ��ⵥ������ڣ����û��ƥ�䵽��������δ�����ʱ��
	 * @param $purOrderIds
	 * @param string| $productIds
	 * @param bool|true $emptyReturnMsg
	 * @param bool|true $checkAsset
	 * @return string
	 */
	function getEntryDateForPurOrderId_d($purOrderIds, $productIds = "", $emptyReturnMsg = true, $checkAsset = true) {
		if (!$purOrderIds) {
			return $emptyReturnMsg ? "�����������" : "";
		}

		if ($productIds) {
			$data = $this->_db->get_one("select max(c.auditDate) as entryDate
				from oa_stock_instock c LEFT JOIN oa_stock_instock_item i ON c.id = i.mainId
				where c.docStatus = 'YSH' AND purOrderId IN($purOrderIds) AND i.productId IN($productIds)");
		} else {
			$data = $this->_db->get_one("select max(auditDate) as entryDate from oa_stock_instock
				where docStatus = 'YSH' AND purOrderId IN($purOrderIds)");
		}

		if ($data['entryDate']) {
			return $data['entryDate'];
		} else if ($checkAsset) {
			// ��ѯ��OA
			$result = util_curlUtil::getDataFromAWS('asset', 'GetLastAcceptanceSubmitDate', array(
					'comeFromId' => $purOrderIds)
			);
			try {
				$result = json_decode($result['data'], true);

				return !empty($result['data']['lastSubmitDate']) ?
					$result['data']['lastSubmitDate'] : ($emptyReturnMsg ? "��δ�����ʱ��" : "");
			} catch (Exception $e) {

			}
		}
		return $emptyReturnMsg ? "��δ�����ʱ��" : "";
	}
}