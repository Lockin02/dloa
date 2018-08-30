<?php
/**
 * @author Show
 * @Date 2010��12��21�� ���ڶ� 15:54:46
 * @version 1.0
 * @description:�ɹ���Ʊ��Ŀ Model��
 */
 class model_finance_invpurchase_invpurdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_invpurchase_detail";
		$this->sql_map = "finance/invpurchase/invpurdetailSql.php";
		parent::__construct ();
	}

	/**
	 * ��ʾ�� - �鿴
	 */
	function showDetailView($rows,$formType ='blue'){
		if($rows){
			$str = null;
			$i = 0;
            $title = null;
            if($formType =='blue'){
				foreach($rows as $key => $val){
					$i++;
					$str .=<<<EOT
						<tr>
							<td>
								$i
							</td>
							<td>
								<input type="hidden" id="number$i" value="$val[number]"/>
								<input type="hidden" id="price$i" value="$val[price]"/>
								<input type="hidden" id="amount$i" value="$val[amount]"/>
								<input type="hidden" id="assessment$i" value="$val[assessment]"/>
								<input type="hidden" id="allCount$i" value="$val[allCount]"/>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unit]
							</td>
							<td>
								$val[number]
							</td>
							<td class="formatMoneySix">
								$val[price]
							</td>
							<td class="formatMoneySix">
								$val[taxPrice]
							</td>
							<td class="formatMoney">
								$val[amount]
							</td>
							<td class="formatMoney">
								$val[assessment]
							</td>
							<td class="formatMoney">
								$val[allCount]
							</td>
							<td>
								<a href="#" onclick="toSource('$val[objId]','$val[objType]')" title="����鿴����">$val[objCode]</a>
							</td>
							<td>
								<a href="#" onclick="toPur($val[contractId])" title="����鿴����">$val[contractCode]</a>
							</td>
						</tr>
EOT;
				}
            }else{
            	foreach($rows as $key => $val){
					$i++;
					$str .=<<<EOT
						<tr style="color:red">
							<td>
								$i
							</td>
							<td>
								<input type="hidden" id="number$i" value="$val[number]"/>
								<input type="hidden" id="price$i" value="$val[price]"/>
								<input type="hidden" id="amount$i" value="$val[amount]"/>
								<input type="hidden" id="assessment$i" value="$val[assessment]"/>
								<input type="hidden" id="allCount$i" value="$val[allCount]"/>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unit]
							</td>
							<td>
								$val[number]
							</td>
							<td class="formatMoneySix">
								$val[price]
							</td>
							<td class="formatMoneySix">
								$val[taxPrice]
							</td>
							<td class="formatMoney">
								$val[amount]
							</td>
							<td class="formatMoney">
								$val[assessment]
							</td>
							<td class="formatMoney">
								$val[allCount]
							</td>
							<td>
								<a href="#" onclick="toSource('$val[objId]','$val[objType]')" title="����鿴����">$val[objCode]</a>
							</td>
							<td>
								$val[contractCode]
							</td>
						</tr>
EOT;
				}

            }
		}else{
			$str = '<tr><td colspan="7">�����������</td></tr>';
		}
		return array($str,$i);
	}

	/**
	 * ��ʾ�� - �༭
	 */
	function showDetailEdit($rows,$formType ='blue'){
		$str = null;
		$i = 0;
		if($rows){
			foreach($rows as $key => $val){
			$i++;
			$str .=<<<EOT
				<tr>
					<td>
						<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
					</td>
					<td>
						$i
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][productNo]" id="productNo$i" value="$val[productNo]" class="txtmiddle"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][productName]" id="productName$i" value="$val[productName]" class="txt"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][productId]" id="productId$i" value="$val[productId]"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][productModel]" id="productModel$i" value="$val[productModel]" class="readOnlyTxtItem" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][unit]" id="unit$i" value="$val[unit]" class="readOnlyTxtShort" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][number]" id="number$i" value="$val[number]" onblur="FloatMul('price$i','number$i','amount$i');countAll();"  class="txtshort"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][price]" id="price$i" value="$val[price]" onblur="FloatMul('price$i','number$i','amount$i');countAll('price');" class="txtshort formatMoneySix"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][taxPrice]" id="taxPrice$i" value="$val[taxPrice]" onblur="FloatMul('taxPrice$i','number$i','allCount$i');countAll('taxPrice');" class="txtshort formatMoneySix"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][amount]" id="amount$i" value="$val[amount]" onblur="countAll('countForm');" class="txtshort formatMoney"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][assessment]" id="assessment$i" value="$val[assessment]" onblur="countAll('countForm');" class="txtshort formatMoney"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][allCount]" id="allCount$i" value="$val[allCount]" onblur="countAll('countForm');" class="txtshort formatMoney"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][objCode]" id="objCode$i" value="$val[objCode]" class="readOnlyTxtNormal" readonly="readonly"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][objId]" id="objId$i" value="$val[objId]"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][objType]" id="objType$i" value="$val[objType]"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][expand1]" id="expand1$i" value="$val[expand1]"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][expand2]" id="expand2$i" value="$val[expand2]"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][expand3]" id="expand3$i" value="$val[expand3]"/>
					</td>
					<td>
						<input type="text" name="invpurchase[invpurdetail][$i][contractCode]" id="contractCode$i" value="$val[contractCode]" class="readOnlyTxtNormal"  readonly="readonly"/>
						<input type="hidden" name="invpurchase[invpurdetail][$i][contractId]" id="contractId$i" value="$val[contractId]"/>
					</td>
				</tr>
EOT;
			}
		}
		return array($str,$i);
	}

	/**
	 * ��ʾ�� - ���
	 */
	function showDetailBreak($rows,$formType ='blue'){
		$str = null;
		$i = 0;
		if($rows){
			foreach($rows as $key => $val){
				$i++;
				$str .=<<<EOT
					<tr>
						<td>
							$i
						</td>
						<td>
							$val[productNo]
							<input type="hidden" name="invpurchase[invpurdetail][$i][productNo]" value="$val[productNo]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][id]" value="$val[id]"/>
						</td>
						<td>
							$val[productName]
							<input type="hidden" name="invpurchase[invpurdetail][$i][productName]" value="$val[productName]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][productId]" value="$val[productId]"/>
						</td>
						<td>
							$val[productModel]
							<input type="hidden" name="invpurchase[invpurdetail][$i][productModel]" value="$val[productModel]"/>
						</td>
						<td>
							$val[unit]
							<input type="hidden" name="invpurchase[invpurdetail][$i][unit]" value="$val[unit]"/>
						</td>
						<td>
							$val[number]
							<input type="hidden" name="invpurchase[invpurdetail][$i][number]" id="number$i" value="$val[number]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][price]" id="price$i" value="$val[price]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][taxPrice]" id="taxPrice$i" value="$val[taxPrice]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][amount]" value="$val[amount]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][assessment]" value="$val[assessment]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][allCount]" value="$val[allCount]"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$i][objId]" value="$val[objId]"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$i][objCode]" value="$val[objCode]"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$i][objType]" value="$val[objType]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][contractCode]" id="contractCode$i" value="$val[contractCode]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][contractId]" id="contractId$i" value="$val[contractId]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][objType]" id="objType$i" value="$val[objType]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][expand1]" id="expand1$i" value="$val[expand1]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][expand2]" id="expand2$i" value="$val[expand2]"/>
							<input type="hidden" name="invpurchase[invpurdetail][$i][expand3]" id="expand3$i" value="$val[expand3]"/>
						</td>
						<td class="formatMoneySix">
							$val[price]
						</td>
						<td class="formatMoneySix">
							$val[taxPrice]
						</td>
						<td class="formatMoney">
							$val[amount]
						</td>
						<td class="formatMoney">
							$val[assessment]
						</td>
						<td class="formatMoney">
							$val[allCount]
						</td>
						<td>
							<input type="text" name="invpurchase[invpurdetail][$i][breakNumber]" id="breakNumber$i" value="0" onblur="checkThis('breakNumber$i','number$i');countAll();" class="txtshort"/>
						</td>
					</tr>
EOT;
			}
		}
		return array($str,$i);
	}



	/**
	 * ���ʱ�ӱ���ʾģ��
	 *
	 */
	function showAddList($rows){
		$str="";
		$i=0;
		if($rows){
			foreach($rows as $key=>$val){
				$sNum=$i+1;
				$str.=<<<EOT
				    <tr align="center">
							<td>
		                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
		                    </td>
                            <td>
                                $sNum
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productNo]"/>
                                <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                                <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"   />
                                <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i" value=""  />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readonly="readonly" value="$val[productName]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[productModel]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem"  value="$val[unit]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][batchNo]"  id="batchNo$i" class="txtshort"  />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtItem"  value="$val[number]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')" />
                                <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value="" />
								<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value="" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"/>
                                <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"/>
                                <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"/>
                                <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$val[invPurId]"  />
                                <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  />
                                <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"  value="$val[invPurCode]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('price$i','actNum$i','subPrice$i')" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtItem formatMoney"  />
                            </td>

                        </tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

     /**
      * ���ʱ�ӱ���ʾģ��
      *
      */
     function showAddListJson($rows){
         $str="";
         $i=0;
         if($rows){
             $productinfoDao = new model_stock_productinfo_productinfo();
             foreach($rows as $key=>$val){
                 $sNum=$i+1;
                 $proType="";
                 $typeRow=$productinfoDao->getParentType($val['productId']);
                 if(!empty($typeRow)){
                     $proType=$typeRow['proType'];
                 }
                 $str.=<<<EOT
				    <tr align="center">
							<td>
		                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
		                    </td>
                            <td>
                                $sNum
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productNo]"/>
                                <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                                <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"   />
                                <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i" value=""  />
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" readonly="readonly" value="$val[productName]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort"  value="$val[productModel]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin"  value="$val[unit]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort"  />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$val[number]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')" />
                                <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value="" />
								<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value="" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"/>
                                <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"/>
                                <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"/>
                                <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$val[invPurId]"  />
                                <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  />
                                <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"  value="$val[invPurCode]" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoney" onblur="FloatMul('price$i','actNum$i','subPrice$i')" />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney"  />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort" value="{$val['warranty']}"/>
                            </td>

                        </tr>
EOT;
                 $i++;
             }
         }
         return $str;
     }

	/***************************************ҳ����ʾ**********************************************/

	/**
	 * ��������
	 */
	function batchAdd_d($invId,$object,$formType = "blue"){
		try{
			$purchasecontDao = new model_purchase_contract_purchasecontract();
//			$productInfoDao = new model_stock_productinfo_productinfo();
			foreach($object as $val){
				if(!empty($val['productName'])){
					$val['invPurId'] = $invId;
					$val['unHookAmount'] = $val['amount'];
					$val['unHookNumber'] = $val['number'];
					$this->add_d($val);

					//�����������¼۸�
//					$productInfoDao->update(array('id' => $val['productId']),array('priCost' => $val['taxPrice']));

					if(!empty($val['contractId']) && $val['contractId'] != 0){
						$purchasecontDao->end_d($val['contractId']);
					}
				}
			}
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * ���ݲɹ���Ʊid��ȡ��Ŀ
	 */
	function getDetail_d($invId){
		$condition = array( 'invPurId' => $invId);
		return $this->findAll($condition);
	}

	/**
	 * ���ݲɹ���Ʊ��id����ȡ��Ŀ
	 */
	function getDetailByIds_d($invIds,$method = 1){
		$this->searchArr['invIds'] = $invIds;
		$this->asc = false;
		if($method == 1){
			return $this->listBySqlId('easy_list');
		}else{
			return $this->listBySqlId('select_left');
		}
	}

	/**
	 * ���ݷ�Ʊidɾ����Ŀ
	 */
	function delDetail_d($invId){
		return $this->delete( array('invPurId' => $invId));
	}

	/**
	 * ��ִ���
	 */
	function batchBreak_d($oldId,$newId,$object,$formType = 'blue'){
		try{
			foreach($object as $val){
				if($val['breakNumber'] != 0){
					if($val['number'] != $val['breakNumber']){
						//���ϲ���˰���
						$allMoney = bcmul($val['breakNumber'] ,$val['price'],2);

						$val['allCount'] = bcmul($val['breakNumber'] ,$val['taxPrice'],2);

						$val['assessment'] = bcsub( $val['allCount'] ,$allMoney ,2 );

						$newVal = $val;
						$newVal['invPurId'] = $newId;
						$newVal['belongId'] = $val['id'];
						$newVal['number'] = $newVal['unHookNumber'] = $newVal['breakNumber'];
						$newVal['amount'] = $newVal['unHookAmount'] = $allMoney;

						unset($newVal['id']);
						$this->add_d($newVal);
//						print_r($newVal);

						$this->breakDeal_d($val,$allMoney);
					}elseif($val['number'] == $val['breakNumber']){
						$newVal = $val;
						$newVal['invPurId'] = $newId;
						$newVal['belongId'] = $val['id'];
						$newVal['number'] = $newVal['unHookNumber'] =$newVal['breakNumber'];
						$newVal['unHookAmount'] = $newVal['amount'];
						$newVal['assessment'] = $val['assessment'];
						$newVal['allCount'] = $val['allCount'];
						unset($newVal['id']);
						$this->add_d($newVal);

						$this->deletes_d($val['id']);
					}
				}
			}
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * ���ֲ�����
	 */
	function breakDeal_d($object,$allMoney,$formType = 'blue'){
		if($formType == 'blue'){
			$sql = " update ". $this->tbl_name . " set number = number - " .$object['breakNumber'] . ", amount = amount - " . $allMoney ." , unHookNumber = unHookNumber - " .$object['breakNumber'] . ", unHookAmount = unHookAmount - " . $allMoney .", assessment = assessment - " . $object['assessment'] .", allCount = allCount - " . $object['allCount'] ." where id = " . $object['id'];
		}else{
			$sql = " update ". $this->tbl_name . " set number = number + " .$object['breakNumber'] . ", amount = amount + " . $allMoney ." , unHookNumber = unHookNumber + " .$object['breakNumber'] . ", unHookAmount = unHookAmount + " . $allMoney .", assessment = assessment + " . $object['assessment'] .", allCount = allCount + " . $object['allCount'] ." where id = " . $object['id'];
		}
		return $this->query($sql);
	}

	/**
	 * ���ַ�Ʊ�ϲ�
	 */
	function mergeDeal_d($object,$formType = 'blue'){
		if($formType == 'blue'){
			$sql = " update ". $this->tbl_name . " set number = number + " .$object['number'] . ", amount = amount + " . $object['amount'] .", unHookNumber = unHookNumber + " .$object['number'] . ", unHookAmount = unHookAmount + " . $object['amount'] .", assessment = assessment + " . $object['assessment'] .", allCount = allCount + " . $object['allCount'] ." where id = " . $object['id'];
		}else{
			$sql = " update ". $this->tbl_name . " set number = number - " .$object['number'] . ", amount = amount - " . $object['amount'] .", unHookNumber = unHookNumber - " .$object['number'] . ", unHookAmount = unHookAmount - " . $object['amount']  .", assessment = assessment - " . $object['assessment'] .", allCount = allCount - " . $object['allCount'] ." where id = " . $object['id'];
		}
		return $this->query($sql);
	}

	/**
	 * �ϲ�����
	 * ����1Ϊ��ǰ�ɹ���ƱId
	 * ����2Ϊԭ�ɹ���ƱId
	 */
	function mergeDetail_d($invId,$belongId){
		$rows = $this->getDetailByIds_d($invId);
		$tempId = null;
		try{
			foreach($rows as $val){
				if($this->isExist_d($val['belongId'])){
					//������ĿID
					$tempId = $val['id'];
					//��ԭ��Ŀidȡ����ǰ��Ŀid
					$val['id'] = $val['belongId'];
					//ɾ������id
//					unset($val['belongId']);
					//���ֵ
					$this->mergeDeal_d($val);
					//ɾ����֧��Ŀ
					$this->deletes_d($tempId);
				}else{
					//������Ŀ������Ʊ
					$val['invPurId'] = $belongId;
					$val['id'] = $val['belongId'];
					$val['unHookNumber'] = $val['number'];
					$val['unHookAmount'] = $val['amount'];
					unset($val['belongId']);
					$this->add_d($val);
				}
			}
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * �жϱ���ֵ���Ŀ�Ƿ񻹴���
	 */
	function isExist_d($detailId){
		return $this->find( array ('id' => $detailId),null,'id');
	}

	/**
	 * ��������
	 */
	function hookDeal_d($object){
		$sql = " update ". $this->tbl_name . " set unHookNumber = unHookNumber - " .$object['number'] . ", unHookAmount = unHookAmount - " . $object['amount'] .", hookNumber = hookNumber + " . $object['number'] .", hookAmount = hookAmount + " . $object['amount'] ." where id = " . $object['hookId'];
		return $this->query($sql);
	}

	/**
	 * ����������
	 */
	function unhookDeal_d($object){
//		print_r($object);
		$sql = " update ". $this->tbl_name . " set unHookNumber = unHookNumber + " .$object['number'] . ", unHookAmount = unHookAmount + " . $object['amount'] .", hookNumber = hookNumber - " . $object['number'] .", hookAmount = hookAmount - " . $object['amount'] ." where id = " . $object['hookId'];
		return $this->query($sql);
	}

	/**
	 * ���ط�Ʊδ���������жϷ�Ʊ�ǲ��ֹ�������ȫ������
	 */
	function isAllHook_d($invPurId){
		$this->searchArr['invPurId'] = $invPurId;
		$rows = $this->listBySqlId('isAllHook');
		return $rows[0]['allunHookNumber'];
	}


	/**
	 * ��ȡ�����ѿ���Ʊ����
	 */
	function findAllItemByObjIdAndType_d($objIds,$objType = 'CGFPYD-02'){
		$this->searchArr['objIds'] = $objIds;
		$this->searchArr['objType'] = $objType;
		$this->groupBy = 'c.objId,c.objType,c.productId,c.expand1';
		return $this->listBySqlId('select_sum');
	}

	/**
	 * ���ݲɹ�����id��ȡ�ɹ���Ʊ��ϸ
	 */
	function getDetailByContractIds_d($contractId){
		$this->searchArr = array('contractIds' => $contractId);
		return $this->list_d('select_left');
	}
}
?>