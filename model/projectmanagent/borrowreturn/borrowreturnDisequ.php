<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:32
 * @version 1.0
 * @description:借试用归还物料从表 Model层
 */
 class model_projectmanagent_borrowreturn_borrowreturnDisequ  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_return_dispose_equ";
		$this->sql_map = "projectmanagent/borrowreturn/borrowreturnDisequSql.php";
		parent::__construct ();
	}


    /**
     * 用于员工借试用 调拨单下推
     */
     function showItemAtEdit($rows) {
		if ($rows) {
			$i = 0; //列表记录序号
			$str = ""; //返回的模板字符串
			foreach ( $rows as $key => $val ) {
				$productCodeClass = "txtshort";
				$productNameClass = "txt";
//				if ($val ['relDocId'] > 0) {
					$productCodeClass = "readOnlyTxtItem";
					$productNameClass = "readOnlyTxtNormal";
//				}
				$deexecutedNum = $val['disposeNum'] - $val['backNum'];
				if($deexecutedNum != 0){
					$seNum = $i + 1;
					 $str .= <<<EOT
				<tr align="center" >
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
					</td>
                    <td>
                        $seNum
                       </td>
                    <td>
                        <input type="text" name="allocation[items][$i][productCode]" id="productCode$i" class="$productCodeClass" value="{$val['productNo']}" readonly="readonly"/>
                        <input type="hidden" name="allocation[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />

                    </td>
                    <td>
						<input type="text" name="allocation[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtItem" value="{$val['k3Code']}" readonly="readonly"/>
					</td>
                    <td>
                        <input type="text" name="allocation[items][$i][productName]" id="productName$i" class="$productNameClass" value="{$val['productName']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['productModel']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtItem" value="{$val['unitName']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][allocatNum]"  id="allocatNum$i" class="txtshort" ondblclick="chooseSerialNo($i)" onblur="FloatMul('allocatNum$i','cost$i','subCost$i')" value="{$deexecutedNum}" />
	                 </td>
                    <td>
                        <input type="text" name="allocation[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','allocatNum$i','subCost$i')" value="{$val['price']}" />
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="{$val['money']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][exportStockName]" id="exportStockName$i" class="txtshort" value="" />
                        <input type="hidden" name="allocation[items][$i][exportStockId]" id="exportStockId$i" value="" />
                       	<input type="hidden" name="allocation[items][$i][exportStockCode]"id="exportStockCode$i" value="" />
                       	<input type="hidden" name="allocation[items][$i][relDocId]" id="relDocId$i"   value="{$val['id']}" />
                        <input type="hidden" name="allocation[items][$i][relDocName]" id="relDocName$i"  value=""  />
                       	<input type="hidden" name="allocation[items][$i][relCodeCode]" id="relCodeCode$i"  value=""  />
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][importStockName]" id="importStockName$i" class="txtshort" value="" />
                        <input type="hidden" name="allocation[items][$i][importStockId]" id="importStockId$i" value="" />
                       	<input type="hidden" name="allocation[items][$i][importStockCode]"id="importStockCode$i" value="" />
                    </td>
                    <td>
                     	<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo($i);" title="选择序列号">
                     	<input type="hidden" name="allocation[items][$i][serialnoId]" id="serialnoId$i" value="{$val['serialId']}" />
						<input type="text" name="allocation[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i" value="{$val['serialName']}"/>
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][remark]" id="remark$i" class="txtshort" value="{$val['remark']}"  />
                    </td>
                    <td>
                        <input type="text" name="allocation[items][$i][validDate]" id="subPrice$i" onfocus="WdatePicker()" class="txtshort" value="{$val['warrantyPeriod']}" />
                    </td>
               		 </tr>
EOT;
                    $i ++;
				}

			}
			return $str;
		}
	}

	/**
	 * 根据发货计划ID获取物料清单
	 *
	 */
	function getItemByshipId_d($mainId) {
		$products = array (
			"disposeId" => $mainId
		);
		$rows = parent :: findAll($products);
		return $rows;
	}

	/**
	 * 列表显示
	 */
	function showAddOtherList($rows){
		$str = "";
		$i = 0;
		if ($rows) {
			foreach ($rows as $key => $val) {
				$number = $val['outNum'] - $val['executedNum'];
				if($number==0){
					continue;
				}
				$sNum = $i +1;
				if ($val['BToOTips'] == 1) {
					$str .=<<<EOT
						<tr style="background:#D3FFD3" align="center">
				            <td>
	                           <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
	                        </td>
	                        <td>
	                            $sNum
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][productCode]" readonly id="productCode$i" class="readOnlyTxtItem" value="$val[productNo]"/>
	                            <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][k3Code]" readonly id="k3Code$i" class="readOnlyTxtItem" value="$val[k3Code]"/>
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][productName]" readonly="readonly" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][pattern]" readonly="readonly" id="pattern$i" class="readOnlyTxtItem"  value="$val[productModel]" />
	                        </td>
	                        <td>
	                            <input type="text" name="stockout[items][$i][unitName]" readonly="readonly" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"/>
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
								 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="$val[serialOutId]"/>
								 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialOutName]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
							</td>
		                     <td>
		                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="$val[subCost]" readonly="readonly"/>
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
					$str .=<<<EOT
						<tr align="center">
				            <td>
                               <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行">
                            </td>
                            <td>
                                $sNum
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][productCode]" readonly="readonly" id="productCode$i" class="readOnlyTxtItem" value="$val[productNo]"/>
                                <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]" />
                            </td>
                            <td>
	                            <input type="text" name="stockout[items][$i][k3Code]" readonly id="k3Code$i" class="readOnlyTxtItem" value="$val[k3Code]"/>
	                        </td>
                            <td>
                                <input type="text" name="stockout[items][$i][productName]" readonly="readonly" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][pattern]" readonly="readonly" id="pattern$i" class="readOnlyTxtItem"  value="$val[productModel]" />
                            </td>
                            <td>
                                <input type="text" name="stockout[items][$i][unitName]" readonly="readonly" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"/>
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
								 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="$val[serialOutId]"/>
								 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialOutName]" />
							</td>
							<td>
								<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
							</td>
		                     <td>
		                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="$val[subCost]" readonly="readonly"/>
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
	 * 获取下推的数量
	 */
	function getOutNum_d($returnequId){
		$sql = "select sum(outNum) as outNum from $this->tbl_name where returnequId = $returnequId";
		$rs = $this->_db->getArray($sql);
		if($rs[0]['outNum']){
			return $rs[0]['outNum'];
		}else{
			return 0;
		}
	}
}