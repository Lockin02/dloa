<?php
header("Content-type: text/html; charset=gb2312");
//����ӿ�
include_once WEB_TOR . 'model/stock/instock/strategy/istockin.php';

/**
 * @description: ��������������
 * @date 2015-11-24 ����10:24:34
 */
class model_stock_instock_strategy_producebackstockin extends model_base implements istockin
{
	/**
	 * @description ���ƺ�ɫ���ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemAddRed($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$seNum = $i + 1;
				$str .= <<<EOT
				<tr align="center">
                    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
                    </td>
                    <td>
                        $seNum
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" readOnly value="{$val['productCode']}" />
                        <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="{$val['productId']}"/>
                        <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"/>
                        <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"/>
                        <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="{$val['k3Code']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readOnly value="{$val['productName']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" readonly="readonly" value="{$val['pattern']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtMin" readonly="readonly" value="{$val['unitName']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort" value="{$val['batchNum']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort" value="{$val['storageNum']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" onfocus="exploreProTipInfo($i)" ondblclick="serialNoDeal(this,$i)"  class="txtshort" value="{$val['actNum']}" onblur="FloatMul('actNum$i','price$i','subPrice$i')" />
                        <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value="{$val['serialnoId']}" />
                        <input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value="{$val['serialnoName']}" />
                    </td>
                    <td>
                         <input type="text"   name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="{$val['inStockName']}" />
                         <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="{$val['inStockId']}" />
                         <input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="{$val['inStockCode']}" />
                         <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"   value="{$val['relDocId']}" />
                         <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  value="{$val['relDocName']}"  />
                         <input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"  value="{$val['relDocCode']}"  />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoney" value="{$val['price']}"  onblur="FloatMul('price$i','actNum$i','subPrice$i')" />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" value="{$val['subPrice']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort" value="{$val['warranty']}" readonly="readonly"/>
                    </td>
                </tr>
EOT;
				$i++;
			}
			return $str;
		}
	}

	/**
	 * �޸���ⵥʱ�������嵥ģ��
	 * @author huangzf
	 */
	function showItemEdit($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$productCodeClass = "txtshort";
				$productNameClass = "txt";
				if ($val ['relDocId'] > 0) {
					$productCodeClass = "readOnlyTxtItem";
					$productNameClass = "readOnlyTxtNormal";
				}
				$seNum = $i + 1;
				$str .= <<<EOT
				<tr align="center" >
                    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
                    </td>
                    <td>
                        $seNum
                        </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="$productCodeClass" value="{$val['productCode']}" readonly="readonly"/>
                        <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                        <input type="hidden" name="stockin[items][$i][id]" id="id$i" value="{$val['id']}"  />
                        <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i" value="{$val['serialSequence']}"  />
                        <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i" value="{$val['serialRemark']}"  />
                        <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i" value="{$val['serialnoId']}"  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="{$val['k3Code']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="$productNameClass" value="{$val['productName']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" readOnly value="{$val['pattern']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtMin" readOnly value="{$val['unitName']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort" value="{$val['batchNum']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort" value="{$val['storageNum']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" onfocus="exploreProTipInfo($i)" ondblclick="serialNoDeal(this,$i)"  class="txtshort" value="{$val['actNum']}" onblur="FloatMul('actNum$i','price$i','subPrice$i')" />
                        <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value="{$val['serialnoId']}" />
                        <input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value="{$val['serialnoName']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="{$val['inStockName']}" />
                        <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="{$val['inStockId']}" />
                        <input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="{$val['inStockCode']}" />
                        <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"   value="{$val['relDocId']}" />
                        <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  value="{$val['relDocName']}"  />
                        <input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"  value="{$val['relDocCode']}"  />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoneySix" value="{$val['price']}"  onblur="FloatMul('price$i','actNum$i','subPrice$i')" />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" value="{$val['subPrice']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort" value="{$val['warranty']}" readonly="readonly"/>
                    </td>
                </tr>
EOT;
				$i++;
			}
			return $str;
		}
	}

	/**
	 * �鿴��ⵥʱ�������嵥��ʾģ��
	 * @author huangzf
	 */
	function showItemView($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
				$productNameStr = implode("<br />", $productNameArr);
				$sNum = $i + 1;
				$str .= <<<EOT
				<tr align="center" class="tr_odd" >
                    <td>
                       $sNum
                    </td>
                    <td>
                       {$val['productCode']}
                    </td>
                    <td>
                       {$val['k3Code']}
                    </td>
                    <td>
                        $productNameStr
                    </td>
                    <td>
                        {$val['pattern']}
                    </td>
                    <td>
                        {$val['unitName']}
                    </td>
                    <td>
                        {$val['storageNum']}
                    </td>
                    <td>
                        {$val['actNum']}
                    </td>
                    <td>
                        {$val['batchNum']}
                    </td>
                    <td>
                         <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialSequence']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="�鿴���к���Ϣ" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="�鿴���к�" /></a>
                    </td>
                    <td class="formatMoneySix">
                        {$val['price']}
                    </td>
                    <td class="formatMoney">
                        {$val['subPrice']}
                    </td>
                    <td>
                        {$val['warranty']}
                    </td>
                    <td>
                        {$val['inStockName']}
                    </td>
                </tr>
EOT;
				$i++;
			}
			return $str;
		}
	}

	/**
	 * @description ��ӡ�������ʱ���嵥��ʾģ��
	 * @param $rows
	 */
	function showItemPrint($rows){
		
	}
	
	/**
	 *
	 * ������ɫ��ⵥ���ɺ�ɫ��ⵥ���嵥��ʾģ��
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showRelItem($rows) {
		if ($rows) {
			$i = 0; //�б��¼���
			$str = ""; //���ص�ģ���ַ���
			foreach ($rows as $key => $val) {
				$seNum = $i + 1;
				$str .= <<<EOT
				<tr align="center" >
                    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
                    </td>
                    <td>
                        $seNum
                       </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="{$val['productCode']}" />
                        <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                        <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                        <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"  />
                        <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i"  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="{$val['productName']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['pattern']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" value="{$val['unitName']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtItem" value="{$val['actNum']}" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)"  class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')" />
                        <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"   />
                        <input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="{$val['inStockName']}" />
                         <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="{$val['inStockId']}" />
                         <input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="{$val['inStockCode']}" />
                         <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"   value="{$val['mainId']}" />
                        <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"   />
                        <input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"   />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoneySix" value="{$val['price']}"  onblur="FloatMul('price$i','actNum$i','subPrice$i')" />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtItem formatMoney"  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtItem"  />
                    </td>
                </tr>
EOT;
				$i++;
			}
			return $str;
		}
	}

	/**
	 * ������ⵥ���Ƴ��ⵥʱ���嵥��ʾģ��
	 * @param  $rows
	 * @param  $istrategy
	 */
	function showItemAtOutStock($rows) {

	}

	/**
	 * ������ⵥʱ�������ҵ����Ϣ
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		try {
			/*start:-----��ɫ�����������кš����κ�̨��---------*/
			if ("0" == $paramArr ['isRed']) {
				$serialnoDao = new model_stock_serialno_serialno ();
				$batchnoDao = new model_stock_batchno_batchno ();
				foreach ($relItemArr as $key => $itemObj) {
					if (!empty ($itemObj ['batchNum'])) {
						$batchnoObj = array("inDocId" => $paramArr ['docId'], "inDocCode" => $paramArr ['docCode'], "inDocItemId" => $itemObj ['id'], "productId" => $itemObj ['productId'], "productName" => $itemObj ['productName'], "productCode" => $itemObj ['productCode'], "stockId" => $itemObj ['inStockId'], "stockName" => $itemObj ['inStockName'], "stockCode" => $itemObj ['inStockCode'], "batchNo" => $itemObj ['batchNum'], "stockNum" => $itemObj ['actNum']);
						$batchnoDao->add_d($batchnoObj);
					}

					if (!empty ($itemObj ['serialSequence'])) {
						$serialObj = array("inDocId" => $paramArr ['docId'], "inDocCode" => $paramArr ['docCode'],
							"inDocItemId" => $itemObj ['id'], "productId" => $itemObj ['productId'],
							"productName" => $itemObj ['productName'], "productCode" => $itemObj ['productCode'],
							"pattern" => $itemObj ['pattern'], "stockId" => $itemObj ['inStockId'],
							"stockName" => $itemObj ['inStockName'], "stockCode" => $itemObj ['inStockCode'],
							"seqStatus" => "0", "batchNo" => $itemObj ['batchNum'], "relDocCode" => $paramArr['relDocCode'],
							"relDocType" => $paramArr['relDocType']);

						$serialnoDao->autoDeal_d($serialObj, $itemObj['serialSequence'], $itemObj['serialRemark'], 1);
					}

				}
			}
			/*end:-----��ɫ�����������кš����κ�̨��---------*/

			//����˵���ⵥ,����ֿ�
			if ($paramArr ['docStatus'] == "YSH") {
				$inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
				foreach ($relItemArr as $key => $value) {
					//���¿��
					$stockParamArr = array("stockId" => $value ['inStockId'], "productId" => $value ['productId']);
					if ($paramArr ['isRed'] == "0") { //��ɫ
						$inventoryDao->updateInTimeInfo($stockParamArr, $value ['actNum'], "instock");
					} else { //��ɫ
						$inventoryDao->updateInTimeInfo($stockParamArr, $value ['actNum'], "outstock");
						//���������϶�Ӧ���к�
						$serialnoDao = new model_stock_serialno_serialno ();
						if (!empty ($value ['serialnoId'])) {
							$seStr = $value ['serialnoId'];
							$serialnoDao->query("update oa_stock_product_serialno set seqStatus=1 where id in($seStr)");
						}
					}
				}
				//�����������
				$backDao = new model_produce_plan_back();
				if ($paramArr ['isRed'] == "0") {
					$backDao->finishStockIn($paramArr ,$relItemArr);
				} else {
					$backDao->cancelStockIn($paramArr ,$relItemArr);
				}
			}
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * �޸���ⵥʱ�������ҵ����Ϣ
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
		try {
			/*start:-----��ɫ�����������кš����κ�̨��---------*/
			if ("0" == $paramArr ['isRed']) {
				$serialnoDao = new model_stock_serialno_serialno ();
				$batchnoDao = new model_stock_batchno_batchno ();
				foreach ($relItemArr as $key => $itemObj) {
					$deleteCon = array("inDocItemId" => $itemObj ['id']);
					if (!empty ($itemObj ['batchNo'])) {
						$batchnoDao->delete($deleteCon);
						$batchnoObj = array("inDocId" => $paramArr ['docId'], "inDocCode" => $paramArr ['docCode'], "inDocItemId" => $itemObj ['id'], "productId" => $itemObj ['productId'], "productName" => $itemObj ['productName'], "productCode" => $itemObj ['productCode'], "stockId" => $itemObj ['inStockId'], "stockName" => $itemObj ['inStockName'], "stockCode" => $itemObj ['inStockCode'], "batchNo" => $itemObj ['batchNo'], "stockNum" => $itemObj ['actNum']);
						$batchnoDao->add_d($batchnoObj);
					}

					if (!empty ($itemObj ['serialSequence'])) {
						$serialObj = array("inDocId" => $paramArr ['docId'], "inDocCode" => $paramArr ['docCode'],
							"inDocItemId" => $itemObj ['id'], "productId" => $itemObj ['productId'],
							"productName" => $itemObj ['productName'], "productCode" => $itemObj ['productCode'],
							"pattern" => $itemObj ['pattern'], "stockId" => $itemObj ['inStockId'],
							"stockName" => $itemObj ['inStockName'], "stockCode" => $itemObj ['inStockCode'],
							"seqStatus" => "0", "batchNo" => $itemObj ['batchNum'], "relDocCode" => $paramArr['relDocCode'],
							"relDocType" => $paramArr['relDocType']);

						$serialnoDao->autoDeal_d($serialObj, $itemObj['serialSequence'], $itemObj['serialRemark'], 1);
					}
				}
			}
			/*end:-----��ɫ�����������кš����κ�̨��---------*/

			//����˵���ⵥ,����ֿ�
			if ($paramArr ['docStatus'] == "YSH") {
				$inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
				foreach ($relItemArr as $key => $value) {
					//���¿��
					$stockParamArr = array("stockId" => $value ['inStockId'], "productId" => $value ['productId']);
					if ($paramArr ['isRed'] == "0") { //��ɫ
						$inventoryDao->updateInTimeInfo($stockParamArr, $value ['actNum'], "instock");
					} else { //��ɫ
						$inventoryDao->updateInTimeInfo($stockParamArr, $value ['actNum'], "outstock");
						//���������϶�Ӧ���к�
						$serialnoDao = new model_stock_serialno_serialno ();
						if (!empty ($value ['serialnoId'])) {
							$seStr = $value ['serialnoId'];
							$serialnoDao->query("update oa_stock_product_serialno set seqStatus=1 where id in($seStr)");
						}
					}
				}
				//�����������
				$backDao = new model_produce_plan_back();
				if ($paramArr ['isRed'] == "0") {
					$backDao->finishStockIn($paramArr ,$relItemArr);
				} else {
					$backDao->cancelStockIn($paramArr ,$relItemArr);
				}
			}
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * �������ⵥ
	 * @param $id
	 */
	function cancelAudit($stockinObj) {
		try {
			if ($stockinObj ['docStatus'] == "YSH") { //ȷ�ϵ���ʱ��������
				$inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
				foreach ($stockinObj ['items'] as $key => $value) {
					//��ԭ���
					$stockParamArr = array("stockId" => $value ['inStockId'], "productId" => $value ['productId']);
					if ($stockinObj ['isRed'] == "0") { //��ɫ
						$inventoryDao->updateInTimeInfo($stockParamArr, $value ['actNum'], "outstock");
					} else { //��ɫ
						$inventoryDao->updateInTimeInfo($stockParamArr, $value ['actNum'], "instock");
					}
				}
				//�����������
				$backDao = new model_produce_plan_back();
				$paramArr['relDocId'] = $stockinObj ['relDocId'];
				$relItemArr = $stockinObj['items'];
				if ($stockinObj ['isRed'] == "0") {
					$backDao->cancelStockIn($paramArr ,$relItemArr);
				} else {
					$backDao->finishStockIn($paramArr ,$relItemArr);
				}
			}
			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ���ݻ�����ϢID��ȡ�����嵥��Ϣ(�������кż����κ� )
	 */
	function getItem($id) {
		$stockinitemDao = new model_stock_instock_stockinitem ();
		$serialnoDao = new model_stock_serialno_serialno ();
		$batchnoDao = new model_stock_batchno_batchno ();
		$stockinItemArr = $stockinitemDao->getItemByMainId($id);
		$itemResult = array();
		if (is_array($stockinItemArr)) {
			foreach ($stockinItemArr as $key => $itemObj) {
				$batchnoObj = $batchnoDao->findByInItemId($itemObj ['id']);
				$serialnoObj = $serialnoDao->findByInItemId($itemObj ['id']);
				if (is_array($batchnoObj)) {
					$itemObj ['batchNo'] = $batchnoObj [0] ['batchNo'];

				} else {
					$itemObj ['batchNo'] = "";
				}
				if (is_array($serialnoObj)) {
					$sequenceStr = "";
					$remarkStr = "";
					$serialnoIdStr = "";
					for ($i = 0; $i < count($serialnoObj); $i++) {
						$sequenceStr .= $serialnoObj [$i] ['sequence'];
						$remarkStr .= $serialnoObj [$i] ['remark'];
						$serialnoIdStr .= $serialnoObj [$i] ['id'];
						if ($i + 1 < count($serialnoObj)) {
							$sequenceStr .= ",";
							$remarkStr .= ",";
							$serialnoIdStr .= ",";
						}
					}
					$itemObj ['serialSequence'] = $sequenceStr;
					$itemObj ['serialRemark'] = $remarkStr;
					$itemObj ['serialnoId'] = $serialnoIdStr;
					$itemObj ['serialnoName'] = $sequenceStr;
				} else {
					$itemObj ['serialSequence'] = "";
					$itemObj ['serialRemark'] = "";
				}
				array_push($itemResult, $itemObj);

			}
		}
		return $itemResult;
	}
}