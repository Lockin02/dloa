<?php

/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:资产转物料申请明细 Model层
 */
class model_asset_require_requireoutitem extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_asset_requireoutitem";
		$this->sql_map = "asset/require/requireoutitemSql.php";
		parent::__construct();
	}

	/**
	 * 更新物料入库数量
	 */
	function updateInNum($id, $inNum) {
		$sql = "update " . $this->tbl_name . " set executedNum = executedNum + " . $inNum . " where id = " . $id;
		$this->_db->query($sql);
	}

	/*******************************************页面显示层*****************************************/
	/**
	 * 根据申请单id获取产品列表
	 */
	function getDetail_d($requireId) {
		$this->searchArr['mainId'] = $requireId;
		$this->searchArr['shouldOutNum'] = 0;//只显示【应发数量】大于0的产品数据
		return $this->list_d();
	}

	/**
	 * 资产入库-产品列表
	 */
	function showProAtEdit($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$sNum = $i + 1;
				$storageNum = $val['number'] - $val['executedNum'];
				$str .= <<<EOT
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
                                <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" readonly="readonly"/>
                            </td>
                            <td>
                                <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[spec]" readonly/>
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
                                <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="readOnlyTxtShort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')" value="$storageNum" readonly="readonly"/>
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
                            <td>
                            ******
                                <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="readOnlyTxtShort formatMoneySix" readonly="readonly" onblur="FloatMul('price$i','actNum$i','subPrice$i')" value="0"/>
                            </td>
                            <td>
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
	 *
	 */
	function showProAtStockInNew_d($rows) {

		$str = "";
		if ($rows) {
			$i = 0;
			foreach ($rows as $key => $val) {
				$sNum = $i + 1;
				$storageNum = $val['applyNum'] - $val['inStorageNum'];
				if ($storageNum == 0)
					continue;
				$str .= <<<EOT
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
							<input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[pattern]" readonly/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort"  value="$val[unit]" readonly/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][batchNo]"  id="batchNo$i" class="txtshort"/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$storageNum" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="readOnlyTxtShort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')" value="$storageNum" readonly="readonly"/>
							<input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"/>
							<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"  value="$val[stockName]" />
							<input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"  value="$val[stockId]" />
							<input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"  value="$val[stockCode]" />
							<input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$key"/>
							<input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"/>
							<input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][price]" id="price$i" class="readOnlyTxtShort formatMoneySix" readonly="readonly" onblur="FloatMul('price$i','actNum$i','subPrice$i')" value="$val[price]"/>
						</td>
						<td>
							<input type="text" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" readonly="readonly" value="$val[subPrice]"/>
						</td>
					</tr>
EOT;
				$i++;
			}
		}
		return $str;
	}
}