<?php
/**
 * @author Administrator
 * @Date 2012年11月20日 10:22:14
 * @version 1.0
 * @description:入库通知单清单 Model层
 */
class model_stock_withdraw_noticeequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_innotice_equ";
		$this->sql_map = "stock/withdraw/noticeequSql.php";
		parent :: __construct();
	}

	/**
	 * 获取可入库内容
	 */
	function getItemByBasicIdUnstock_d($mainId){
		$this->searchArr = array('mainId' => $mainId,'notExecuted' => 1);
		return $this->list_d();
	}

	/**
	 * 入库时从表显示模板
	 * @param  $rows   收料物料信息数组
	 *
	 */
	function showAddList($rows){
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
				$storageNum=$val['number']-$val['executedNum'];
				$str.=<<<EOT
				    <tr align="center">
							<td>
		                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
		                    </td>
                            <td>
                                $sNum
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]" readonly="readonly"/>
                                <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                                <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"   />
                                <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i"  />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                                <input type="hidden" name="stockin[items][$i][proTypeId]" id="proTypeId$i" value="{$val['proTypeId']}"  />
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="{$val['k3Code']}" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[productModel]" readonly/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort"  value="$val[unitName]" readonly/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][batchNo]"  id="batchNo$i" class="txtshort"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$storageNum" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')"  value="$storageNum" />
								<input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"/>
								<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"  value="$val[stockName]" />
                                <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"  value="$val[stockId]" />
                                <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"  value="$val[stockCode]" />
                                <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$val[id]"/>
                                <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  />
                                <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"  value="$val[arrivalCode]" />
                            </td>
                            <td style="display:none;">
                            ******
                                <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="readOnlyTxtShort formatMoneySix" readonly="readonly" onblur="FloatMul('price$i','actNum$i','subPrice$i')" value="0"/>
                            </td>
                            <td style="display:none;">
                            ******
                                <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" readonly="readonly" value="0" />
                            </td>
                        </tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 更新物料的入库数量.
	 * @param  $arrivalId   收料通知单ID
	 * @param  $productId   物料Id
	 * @param  $proNum   入库数量
	 */
	function updateNumb_d($mainId,$id,$proNum) {
		$sql = "update $this->tbl_name c set c.executedNum=(c.executedNum+$proNum) where  c.id=$id";
		$this->query($sql);

        // 如果入库通知单原单类型为 “oa_contract_exchange” 既换货申请的,更新原单退货物料的执行数量
        $relObjUpdateSql = "update oa_contract_exchange_backequ be
            left join oa_stock_withdraw_equ we on we.contEquId = be.id
            left join oa_stock_innotice_equ ie on ie.planEquId = we.id
            left join oa_stock_innotice si on si.id = ie.mainId
            set be.executedNum = (be.executedNum + {$proNum})
            where 
            si.docType = 'oa_contract_exchange'
            and ie.id = '{$id}'";
        $this->query($relObjUpdateSql);
	}
	
	/**
	 * 更新物料的入库数量.
	 * @param  $mainId   收料通知单ID
	 * @param  $productId   物料Id
	 * @param  $proNum   入库数量
	 */
	function updateNum_d($mainId,$productId,$proNum) {
		$sql = "update $this->tbl_name c set c.executedNum=(c.executedNum+$proNum) where  c.mainId=$mainId and c.productId=$productId";
		$this->query($sql);
	}
}