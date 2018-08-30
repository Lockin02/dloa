<?php

/**
 * @author zengzx
 * @Date 2011年5月4日 16:06:49
 * @version 1.0
 * @description:发货计划物料清单 Model层
 */
class model_stock_outplan_outplanProduct extends model_base
{

	function __construct() {
		$this->tbl_name = "oa_stock_outplan_product";
		$this->sql_map = "stock/outplan/outplanProductSql.php";
		parent:: __construct();
	}

	/**
	 * 根据mainId取出所有product
	 */
	function getInfoByMainId($mainId) {
		return $this->findAll(array(
			'mainId',
			$mainId
		));
	}

	/**
	 * 出库时从表显示模板 --- 销售出库
	 */
	function showAddList($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
            $productinfoDao = new model_stock_productinfo_productinfo();
			foreach ($rows as $key => $val) {
				$number = $val['number'] - $val['executedNum'];
				if ($number == 0) {
					continue;
				}
				$sNum = $i + 1;
                $proType="";
                $typeRow=$productinfoDao->getParentType($val['productId']);
                if(!empty($typeRow)){
                    $proType=$typeRow['proType'];
                }
				if ($val['BToOTips'] == 1) {
					$str .= <<<EOT
						<tr style="background:#D3FFD3" align="center">
							<td>
							   <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
							</td>
							<td>
								$sNum
							</td>
							<td>
								<input type="text" name="stockout[items][$i][productCode]" readonly id="productCode$i" class="readOnlyTxtShort" value="$val[productNo]"/>
								<input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
							</td>
                        <td>
                            <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                        </td>
							<td>
								<input type="text" name="stockout[items][$i][k3Code]" readonly id="k3Code$i" class="readOnlyTxtShort"  value="$val[k3Code]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][productName]" readonly id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][pattern]" readonly id="pattern$i" class="readOnlyTxtShort"  value="$val[productModel]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][unitName]" readonly id="unitName$i" class="readOnlyTxtMin"/>
							</td>
							<td>
								<input type="text" name="stockout[items][$i][shouldOutNum]" readonly id="shouldOutNum$i" class="readOnlyTxtShort"  value="$number" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][actOutNum]"  id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"  value="$number" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" />
							 </td>
							<td>
								<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
								<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
								<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
								<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
								<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" />
								<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" />
							</td>
							<td>
								 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
								 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="{$val[serialnoId]}"/>
								 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialnoName]" />
							</td>
							<td>
							******
								<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" />
							</td>
							<td>
							******
								<input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney"  />
							</td>
							<td>
							******
								<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney"  onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')"   />
							</td>
							<td>
							******
								<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtShort formatMoney"  />
							</td>
						</tr>
EOT;
				} else {
					$str .= <<<EOT
						<tr align="center">
							<td>
							   <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
							</td>
							<td>
								$sNum
							</td>
							<td>
								<input type="text" name="stockout[items][$i][productCode]" readonly id="productCode$i" class="readOnlyTxtShort" value="$val[productNo]"/>
								<input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
							</td>
                        <td>
                            <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                        </td>
							<td>
								<input type="text" name="stockout[items][$i][k3Code]" readonly id="k3Code$i" class="readOnlyTxtShort"  value="$val[k3Code]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][productName]" readonly id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][pattern]" readonly id="pattern$i" class="readOnlyTxtShort"  value="$val[productModel]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][unitName]" readonly id="unitName$i" class="readOnlyTxtMin"/>
							</td>
							<td>
								<input type="text" name="stockout[items][$i][shouldOutNum]" readonly id="shouldOutNum$i" class="readOnlyTxtShort"  value="$number" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"  value="$number" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" />
							 </td>
							<td>
								<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
								<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
								<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
								<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
								<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" />
								<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" />
							</td>
							<td>
								 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
								 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="{$val[serialnoId]}"/>
								 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialnoName]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney"  />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney"  onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')"   />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtShort formatMoney"  />
							</td>
						</tr>
EOT;
				}
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 出库时从表显示模板 --- 其他出库
	 */
	function showAddOtherList($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
            $productinfoDao = new model_stock_productinfo_productinfo();
			foreach ($rows as $key => $val) {
				//				print_r($val);
				$number = $val['number'] - $val['executedNum'];
				if ($number == 0) {
					continue;
				}
				$sNum = $i + 1;
                $proType="";
                $typeRow=$productinfoDao->getParentType($val['productId']);
                if(!empty($typeRow)){
                    $proType=$typeRow['proType'];
                }
				if ($val['BToOTips'] == 1) {
					$str .= <<<EOT
						<tr style="background:#D3FFD3" align="center">
				            <td>
	                           <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
	                        </td>
	                        <td>
	                            $sNum
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][productCode]" readonly id="productCode$i" class="readOnlyTxtShort" value="$val[productNo]"/>
	                            <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
	                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                        </td>
							<td>
								<input type="text" name="stockout[items][$i][k3Code]" readonly id="k3Code$i" class="readOnlyTxtShort"  value="$val[k3Code]" />
							</td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][productName]" readonly="readonly" id="productName$i" class="readOnlyTxtShort"  value="$val[productName]" />
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][pattern]" readonly="readonly" id="pattern$i" class="readOnlyTxtShort"  value="$val[productModel]" />
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][unitName]" readonly="readonly" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]"/>
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][shouldOutNum]"  id="shouldOutNum$i" class="txtshort"  value="$number"  />
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][actOutNum]"  id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"  value="$number" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" />
	                        </td>
							<td>
								<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
								<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
								<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
								<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
								<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" />
								<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" />
							</td>
							<td>
								 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
								 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="{$val[serialnoId]}"/>
								 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialnoName]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
							</td>
		                     <td>
		                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney" value="$val[subCost]" readonly="readonly"/>
							</td>
		                     <td>
								<input type="text" name="stockout[items][$i][remark]" id="remark$i" class="txtshort"  value="$val[remark]"/>
							</td>
		    				<td>
		    					<input type="text" name="stockout[items][$i][prodDate]" id="prodDate$i" class="txtshort" value="$val[prodDate]"  />
							</td>
							 <td>
		    					<input type="text" name="stockout[items][$i][shelfLife]" id="shelfLife$i" class="txtshort" value="$val[shelfLife]"  />
							</td>
	                    </tr>
EOT;
				} else {
					$str .= <<<EOT
						<tr align="center">
				            <td>
                               <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
                            </td>
                            <td>
                                $sNum
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][productCode]" readonly="readonly" id="productCode$i" class="readOnlyTxtShort" value="$val[productNo]"/>
                                <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
                            </td>
                        <td>
                            <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                        </td>
							<td>
								<input type="text" name="stockout[items][$i][k3Code]" readonly id="k3Code$i" class="readOnlyTxtShort"  value="$val[k3Code]" />
							</td>
                            <td>
                                <input type="text" name="stockout[items][$i][productName]" readonly="readonly" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][pattern]" readonly="readonly" id="pattern$i" class="readOnlyTxtShort"  value="$val[productModel]" />
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][unitName]" readonly="readonly" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]"/>
                            </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][shouldOutNum]"  id="shouldOutNum$i" class="readOnlyTxtShort"  readonly  value="$number"  />
	                        </td>
                            <td>
                                <input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"  value="$number" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" />
                            </td>
							<td>
								<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
								<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
								<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
								<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
								<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" />
								<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" />
							</td>
							<td>
								 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
								 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="{$val['serialnoId']}"/>
								 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialnoName]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
							</td>
		                     <td>
		                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney" value="$val[subCost]" readonly="readonly"/>
							</td>
		                     <td>
								<input type="text" name="stockout[items][$i][remark]" id="remark$i" class="txtshort"  value="$val[remark]"  />
							</td>
		    				<td>
		    					<input type="text" name="stockout[items][$i][prodDate]" id="prodDate$i" class="txtshort" value="$val[prodDate]"  />
							</td>
							 <td>
		    					<input type="text" name="stockout[items][$i][shelfLife]" id="shelfLife$i" class="txtshort" value="$val[shelfLife]"  />
							</td>
                        </tr>
EOT;
				}
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 出库时从表显示模板 --- 调拨出库
	 *
	 */
	function showAddList_borrow($rows) {
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$number = $val['number'] - $val['executedNum'];
				if ($number == 0) {
					continue;
				}
				$sNum = $i + 1;
				$str .= <<<EOT
					<tr align="center">
						<td>
							<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
						</td>
						<td>
							$sNum
						</td>
						<td>
							<input type="text" name="allocation[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="{$val['productNo']}" readonly="readonly"/>
							<input type="hidden" name="allocation[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
						</td>
						<td>
							<input type="text" name="allocation[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtItem" value="{$val['k3Code']}" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="{$val['productName']}" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['productModel']}" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" value="{$val['unitName']}" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][allocatNum]"  id="allocatNum$i" class="txtshort" ondblclick="chooseSerialNo($i)" onblur="FloatMul('allocatNum$i','cost$i','subCost$i')" value="$number" />
						 </td>
						<td>
						******
							<input type="hidden" name="allocation[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','allocatNum$i','subCost$i')" />
						</td>
						<td>
						******
							<input type="hidden" name="allocation[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][exportStockName]" id="exportStockName$i" class="txtshort"/>
							<input type="hidden" name="allocation[items][$i][exportStockId]" id="exportStockId$i" />
							<input type="hidden" name="allocation[items][$i][exportStockCode]"id="exportStockCode$i" />
							<input type="hidden" name="allocation[items][$i][relDocId]" id="relDocId$i" value="{$val['id']}"/>
							<input type="hidden" name="allocation[items][$i][relDocName]" id="relDocName$i"/>
							<input type="hidden" name="allocation[items][$i][relCodeCode]" id="relCodeCode$i"/>

						</td>
						<td>
							<input type="text" name="allocation[items][$i][importStockName]" id="importStockName$i" class="txtshort"/>
							<input type="hidden" name="allocation[items][$i][importStockId]" id="importStockId$i"/>
							<input type="hidden" name="allocation[items][$i][importStockCode]"id="importStockCode$i"/>
                       		<input type="hidden" name="allocation[items][$i][borrowItemId]" id="borrowItemId$i" value="{$val['borrowItemId']}" />
						</td>
						<td>
							<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo($i);" title="选择序列号">
							<input type="hidden" name="allocation[items][$i][serialnoId]" id="serialnoId$i"/>
							<input type="text" name="allocation[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"   />
						</td>
						<td>
							<input type="text" name="allocation[items][$i][remark]" id="remark$i" class="txtshort"/>
						</td>
						<td>
							<input type="text" name="allocation[items][$i][validDate]" id="subPrice$i" onfocus="WdatePicker()" class="txtshort" />
						</td>
					</tr>
EOT;
				$i++;
			}
		}
		return $str;
	}

	/**
	 * 借用转销售发货计划从表渲染
	 */
	function shwoBToOEqu($rows, $rowNum) {
		$j = 0;
		$str = ""; //返回的模板字符串
		if (is_array($rows)) {
			$i = $rowNum; //列表记录序号
			foreach ($rows as $key => $val) {
				$j = $i + 1;
				$line = $j - $rowNum;
				$str .= <<<EOT
					<tr><td>$line</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
							<input type="hidden" id="BToOTips$j" name="outplan[productsdetail][$j][BToOTips]" value='1' class="txtmiddle"/>
						</td>
						<td>$val[productName]
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[productsdetail][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"borrowbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}

		}
		return $str . '<input type="hidden" id="borrowNum" value="' . $j . '"/>';
	}

	/**
	 * 借用转销售发货计划从表渲染
	 */
	function shwoBToOEquChange($rows, $rowNum) {
		$j = 0;
		if (is_array($rows)) {
			$i = $rowNum; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$j = $i + 1;
				$line = $j - $rowNum;
				$oldIdStr = "";
				if (empty ($val['originalId'])) {
					$oldIdStr .= '<input type="hidden" name="outplan[details][' . $j . '][oldId]" value="' . $val['id'] . '" />';
				} else {
					$oldIdStr .= '<input type="hidden" name="outplan[details][' . $j . '][oldId]" value="' . $val['originalId'] . '" />';
				}
				$str .= <<<EOT
					<tr><td>$line</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo$j" readonly="true" name="outplan[details][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
							<input type="hidden" id="BToOTips$j" name="outplan[details][$j][BToOTips]" value='1' class="txtmiddle"/>
							$oldIdStr
							</td>
						<td>$val[productName]
							<input type="hidden" id="contEquId$j" name="outplan[details][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[details][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName$j" name="outplan[details][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel$j" name="outplan[details][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[details][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[details][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
						<td>
							<img src='images/closeDiv.gif' onclick='mydel(this,"borrowbody")' title='删除行'>
						</td>
					</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}

		}
		$str .= '<input type="hidden" id="borrowNum" value="' . $j . '"/>';
		return $str;
	}

	/**
	 * 借用转销售发货计划从表渲染
	 */
	function shwoBToOEquView($rows, $rowNum) {
		$j = 0;
		if (is_array($rows)) {
			$i = $rowNum; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ($rows as $key => $val) {
				$j = $i + 1;
				$line = $j - $rowNum;
				$str .= <<<EOT
					<tr><td>$line</td>
						<td>$val[productNo]
							<input type="hidden" id="productNo$j" readonly="true" name="outplan[productsdetail][$j][productNo]" value="$val[productNo]" class='readOnlyTxtItem' readonly/></td>
							<input type="hidden" id="BToOTips$j" name="outplan[productsdetail][$j][BToOTips]" value='1' class="txtmiddle"/></td>
						<td>$val[productName]
							<input type="hidden" id="contEquId$j" name="outplan[productsdetail][$j][contEquId]" value="$val[contEquId]"/>
							<input type="hidden" id="productId$j" name="outplan[productsdetail][$j][productId]" value="$val[productId]"/>
							<input type="hidden" id="productName$j" name="outplan[productsdetail][$j][productName]" value="$val[productName]" class='readOnlyTxtMiddle' readonly/></td>
						<td>$val[productModel]
							<input type="hidden" id="productModel$j" name="outplan[productsdetail][$j][productModel]" value="$val[productModel]" class='readOnlyTxtItem' readonly/></td>
						<td>$val[unitName]
							<input type="hidden" name="outplan[productsdetail][$j][unitName]" id="unitName$j" value="$val[unitName]" class='txtshort'/>
						</td>
						<td>$val[number]
							<input type="hidden" name="outplan[productsdetail][$j][number]" id="number$j" value="$val[number]" class='txtshort' onblur="checkThis($j)"/>
						</td>
					</tr>
EOT;
				$i++;
			}
			if ($i == 0) {
				$str = '<tr><td colspan="11"><input type="hidden" id="noRows" value="1"/>暂无相关信息</td></tr>';
			}

		}
		$str .= '<input type="hidden" id="borrowNum" value="' . $j . '"/>';
		return $str;
	}

	/**
	 * 根据发货计划ID获取物料清单
	 *
	 */
	function getItemByshipId_d($planId) {
		$products = array(
			"mainId" => $planId,
			"isDelete" => 0
		);
		$rows = parent:: findAll($products);
		return $rows;
	}

	/**
	 * 关闭发货计划后，更新合同下达数量及状态。
	 */
	function unsetIssuedInfo($planInfo, $docType) {
		$equArr = $this->getItemByshipId_d($planInfo['id']);
		if (is_array($equArr) && count($equArr) > 0) {
			$newEquArr = array();
			foreach ($equArr as $index => $row) {
				$remainNum = $row['number'] - $row['executedNum'];
				if ($remainNum > 0) {
					$newEquArr[$index]['contEquId'] = $row['contEquId'];
					$newEquArr[$index]['number'] = $remainNum * (-1);
				}
			}
			if (is_array($newEquArr) && count($newEquArr) > 0) {
				$docInfo = array(
					'docId' => $planInfo['docId'],
					'docType' => $docType
				);
				$sourceDao = new model_common_contract_allsource();
				$sourceDao->updateIssuedInfo($docInfo, $newEquArr);
				$sourceDao->updateIssuedStatus($docInfo);
			}
		}
	}

}

?>