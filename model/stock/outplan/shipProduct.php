<?php
/**
 * @author zengzx
 * @Date 2011年5月4日 16:08:07
 * @version 1.0
 * @description:发货单详细清单 Model层
 */
 class model_stock_outplan_shipProduct  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_ship_product";
		$this->sql_map = "stock/outplan/shipProductSql.php";
		parent::__construct ();
	}

	/**
	 * 入库时从表显示模板
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
				$str.=<<<EOT
				    <tr align="center">
    				    <td>
              				  <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
         			    </td>
                        <td>
                            $sNum
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productNo]"/>
                            <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
                        </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
					</td>
                        <td>
                            <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort"  value="$val[productModel]" />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin"  />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtShort"  value="$val[number]" />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"  value="$val[number]" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')"/>
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
                            <input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')"/>
                        </td>
                        <td>
                        ******
                            <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney"  />
                        </td>
                        <td>
                        ******
                            <input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort  formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')"/>
                        </td>
                        <td>
                        ******
                            <input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtShort formatMoney"  />
                        </td>
                    </tr>
EOT;
			$i++;
			}
		}
		return $str;
	}

	/**
	 * 入库时从表显示模板 -- 其他出库使用
	 *
	 */
	function showAddOtherList($rows){
		$str="";
		$i=0;
		if($rows){
			foreach($rows as $key=>$val){
				$sNum=$i+1;
				$str.=<<<EOT
				    <tr align="center">
    				    <td>
              				  <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
         			    </td>
                        <td>
                            $sNum
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productNo]"/>
                            <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[productModel]" />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"/>
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"  value="$val[number]" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')"/>
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
                            <input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')"/>
                        </td>
                        <td>
                        ******
                            <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney"  />
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
			$i++;
			}
		}
		return $str;
	}

	/**
	 * 根据退料单ID获取物料清单
	 *
	 */
	function getItemByshipId_d($shipId){
		$products = array (
					"mainId" => $shipId
			);
		$rows = parent :: findAll($products);
	   return $rows;
	}



 }
?>