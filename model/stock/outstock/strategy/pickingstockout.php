<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/stock/outstock/strategy/istockout.php';

/**
 * @author huangzf
 * @Date 2011年3月12日 17:03:12
 * @version 1.0
 * @description:领料出库策略
 */
class model_stock_outstock_strategy_pickingstockout implements istockout
{

    function __construct() {

    }

    /**
     * 新增红色销售出库单时 物料信息显示模板
     * @param  $rows
     */
    function showProAddRed($rows) {
        $str = "";
        if ($rows) {
            $i = 0;
            foreach ($rows as $key => $val) {
                $sNum = $i + 1;
                $str .= <<<EOT
				<tr align="center">
					<td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
                    </td>
                    <td>
                        $sNum
                    </td>
                    <td>
                        <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="txtshort" value="$val[productCode]"/>
					    <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                    </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$val[proType]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="txt" value="$val[productName]"/>
					</td>
    				<td>
    				    <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattern]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][batchNum]" id="batchNum$i" class="txtshort" value="$val[batchNum]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i"   />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i"   />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i"   />
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtShort" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" onfocus="exploreProTipInfo($i)" ondblclick="chooseSerialNo($i)" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" value="$val[actOutNum]"  />
					</td>
					<td>
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
						 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="" />
					</t>
					<td>
					******
						<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
				    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney" value="$val[subCost]" readonly="readonly"/>
					</td>
                    <td>
						<input type="text" name="stockout[items][$i][prodDate]" id="prodDate$i" onfocus="WdatePicker()" class="txtshort"  value="$val[prodDate]"  />
					</td>
                    <td>
						<input type="text" name="stockout[items][$i][shelfLife]" id="shelfLife$i" class="txtshort" value="$val[shelfLife]"  />
					</td>
    				<td>
    					<input type="text" name="stockout[items][$i][validDate]" id="validDate$i" onfocus="WdatePicker()" class="txtshort" value="$val[validDate]"  />
					</td>
			</tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * 修改销售出库单时 物料信息显示模板
     * @param  $rows
     */
    function showProAtEdit($rows) {
        $str = "";
        if ($rows) {
            $i = 0;
            foreach ($rows as $key => $val) {
                $sNum = $i + 1;
                $str .= <<<EOT
				<tr align="center">
					<td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
                    </td>
	                <td>
	                   $sNum
	                </td>
	                <td>
                        <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="txtshort" value="$val[productCode]"  />
					    <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                        <input type="hidden" name="stockout[items][$i][id]" id="id$i " value="$val[id]"  />
                    </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$val[proType]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="txt" value="$val[productName]"  />
					</td>
    				<td>
    				    <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattern]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][batchNum]" id="batchNum$i" class="txtshort" value="$val[batchNum]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[relDocId]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" value="$val[relDocName]"  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" value="$val[relDocCode]"  />
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtShort" value="$val[shouldOutNum]" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" onfocus="exploreProTipInfo($i)" ondblclick="chooseSerialNo($i)" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" value="$val[actOutNum]"  />
					</td>
					<td>
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="{$val[serialnoId]}"/>
						 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialnoName]" />
					</td>
					<td>
					******
						<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
					</td>
                    <td>
                    ******
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney" value="$val[subCost]" readonly="readonly"/>
					</td>
                    <td>
						<input type="text" name="stockout[items][$i][prodDate]" id="prodDate$i" onfocus="WdatePicker()" class="txtshort"  value="$val[prodDate]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][shelfLife]" id="shelfLife$i" class="txtshort" value="$val[shelfLife]"  />
					</td>
    				<td>
    					<input type="text" name="stockout[items][$i][validDate]" id="validDate$i" onfocus="WdatePicker()" class="txtshort" value="$val[validDate]"  />
					</td>
			</tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * 修改销售出库单时 物料信息显示模板
     * @param  $rows
     */
    function showProAtEditCal($rows) {
        $str = "";
        if ($rows) {
            $i = 0;
            foreach ($rows as $key => $val) {
                $sNum = $i + 1;
                $str .= <<<EOT
				<tr align="center">
                    <td>
                        $sNum
                    </td>
                    <td>
                        $val[productCode]
                    </td>
				    <td>
					    $val[proType]
					</td>
                    <td>
                        $val[k3Code]
                    </td>
					<td>
					    $val[productName]
					</td>
    				<td>
    				    $val[pattern]
					</td>
					<td>
                        $val[unitName]
					</td>
					<td>
					    $val[shouldOutNum]
					</td>
					<td>
						$val[actOutNum]
						<input type="hidden" name="stockout[items][$i][actOutNum]" id="actOutNum$i" value="$val[actOutNum]"  />
						<input type="hidden" name="stockout[items][$i][id]" id="id$i " value="$val[id]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
					</td>
                     <td>
                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="$val[subCost]"  />
					</td>
					<td>
						$val[stockName]
					</td>
					<td>
						<select onchange="changePrice($i,'$val[productCode]',this.value)">
							<option value=""></option>
							<option value="stockBalancePrice">期初价格</option>
							<option value="stockInPrice">最新入库价</option>
							<option value="stockOutPrice">最新出库价</option>
							<option value="purchasePrice">最新采购价</option>
						</select>
					</td>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * 查看出库单物料清单显示模板
     * @param
     */
    function showProAtView($rows) {
        $str = ""; //返回的模板字符串
        if ($rows) {
            $i = 0; //列表记录序号
            foreach ($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
                $productNameStr = implode("<br />", $productNameArr);
                $sNum = $i + 1;
                $str .= <<<EOT
					<tr align="center" >
                        <td class="tabledata">
                            $sNum
                        </td>
                        <td class="tabledata">
                            $val[productCode]
                        </td>
                        <td>
                            $val[proType]
                        </td>
                        <td>
                            $val[k3Code]
                        </td>
                        <td class="tabledata">
                            $productNameStr
                        </td>
                        <td class="tabledata">
                            $val[pattern]
                        </td>
                        <td class="tabledata">
                            $val[unitName]
                        </td>
                        <td class="tabledata">
                            {$val['batchNum']}
                        </td>
                        <td class="tabledata">
                            $val[stockName]
                        </td>
                        <td class="tabledata">
                            $val[shouldOutNum]
                        </td>
                        <td class="tabledata">
                            {$val['actOutNum']}
                        </td>
                        <td class="tabledata">
                            <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号" /></a>
                        </td>
                        <td class="tabledata formatMoneySix">
                            $val[cost]
                        </td>
                        <td class="tabledata formatMoney">
                            $val[subCost]
                        </td>
                        <td class="tabledata">
                            $val[prodDate]
                        </td>
                        <td class="tabledata">
                            $val[shelfLife]
                        </td>
                        <td class="tabledata">
                            $val[validDate]
                        </td>
					</tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * 查看出库单物料清单显示模板
     * @param
     */
    function showProAtPrint($rows) {
        $str = ""; //返回的模板字符串
        if ($rows) {
            $i = 0; //列表记录序号
            foreach ($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
                $productNameStr = implode("<br />", $productNameArr);
                $sNum = $i + 1;
                $str .= <<<EOT
					<tr align="center" >
<!--                        <td class="tabledata">
                            $sNum
                        </td>-->
                        <td class="tabledata">
                            $val[productCode]
                       <input type="hidden" id="productCode$i" value="{$val['productCode']}">
                       <input type="hidden" id="productName$i" value="{$val['productName']}">
                       <input type="hidden" id="unitName$i" value="{$val['unitName']}">
                       <input type="hidden" id="pattern$i" value="{$val['pattern']}">
                       <input type="hidden" id="actOutNum$i" value="{$val['actOutNum']}">
                        </td>
<!--                        <td>
                            $val[k3Code]
                        </td>-->
                        <td class="tabledata">
                            $productNameStr
                        </td>
<!--                        <td class="tabledata">
                            $val[pattern]
                        </td>
                        <td class="tabledata">
                            $val[unitName]
                        </td>
                        <td class="tabledata">
                            {$val['batchNum']}
                        </td>
                        <td class="tabledata">
                            $val[stockName]
                        </td>
                        <td class="tabledata">
                            $val[shouldOutNum]
                        </td>-->
                        <td class="tabledata">
                            {$val['actOutNum']}
                        </td>
<!--                        <td class="tabledata">
                            <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号" /></a>
                        </td>-->
                        <td class="tabledata formatMoneySix">
                            $val[cost]
                        </td>
                        <td class="tabledata formatMoney">
                            $val[subCost]
<!--                        </td>
                        <td class="tabledata">
                            $val[prodDate]
                        </td>
                        <td class="tabledata">
                            $val[shelfLife]
                        </td>
                        <td class="tabledata">
                            $val[validDate]
                        </td>-->
					</tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * 查看出库单物料清单显示模板 --红色单
     * @param
     */
    function showRedProAtPrint($rows) {
    	$str = ""; //返回的模板字符串
    	if ($rows) {
    		$i = 0; //列表记录序号
    		foreach ($rows as $key => $val) {
    			$productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
    			$productNameStr = implode("<br />", $productNameArr);
    			$sNum = $i + 1;
    			$str .= <<<EOT
					<tr align="center" >
<!--                        <td class="tabledata">
                            $sNum
                        </td>-->
                        <td class="tabledata">
                            $val[productCode]
                       <input type="hidden" id="productCode$i" value="{$val['productCode']}">
                       <input type="hidden" id="productName$i" value="{$val['productName']}">
                       <input type="hidden" id="unitName$i" value="{$val['unitName']}">
                       <input type="hidden" id="pattern$i" value="{$val['pattern']}">
                       <input type="hidden" id="actOutNum$i" value="-{$val['actOutNum']}">
                        </td>
<!--                        <td>
                            $val[k3Code]
                        </td>-->
                        <td class="tabledata">
                            $productNameStr
                        </td>
<!--                        <td class="tabledata">
                            $val[pattern]
                        </td>
                        <td class="tabledata">
                            $val[unitName]
                        </td>
                        <td class="tabledata">
                            {$val['batchNum']}
                        </td>
                        <td class="tabledata">
                            $val[stockName]
                        </td>
                        <td class="tabledata">
                            $val[shouldOutNum]
                        </td>-->
                        <td class="tabledata">
                            -{$val['actOutNum']}
                        </td>
<!--                        <td class="tabledata">
                            <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号" /></a>
                        </td>-->
                        <td class="tabledata formatMoneySix">
                            $val[cost]
                        </td>
                        <td class="tabledata formatMoney">
                            $val[subCost]
<!--                        </td>
                        <td class="tabledata">
                            $val[prodDate]
                        </td>
                        <td class="tabledata">
                            $val[shelfLife]
                        </td>
                        <td class="tabledata">
                            $val[validDate]
                        </td>-->
					</tr>
EOT;
            	$i++;
    		}
    	}
    	return $str;
    }

    /**
     *
     * 根据生产领料单生成红色领料出库单，清单显示模板
     * @param  $rows
     * @param  $istrategy
     */
    function showRelItem($rows) {
        $str = "";
        if ($rows) {
            $productinfoDao = new model_stock_productinfo_productinfo();
            $i = 0;
            foreach ($rows as $key => $val) {
                $sNum = $i + 1;
                $proType="";
                $typeRow=$productinfoDao->getParentType($val['productId']);
                if(!empty($typeRow)){
                    $proType=$typeRow['proType'];
                }
                $str .= <<<EOT
                    <tr align="center">
                        <td>
                            <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
                        </td>
                        <td>
                            $sNum
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="txtshort" value="$val[productCode]"  />
                            <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                        </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
					</td>
                        <td>
                            <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
                        </td>
                        <td>
                          <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="txt" value="$val[productName]"  />
                        </td>
                        <td>
                           <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattern]"  />
                        </td>
                        <td>
                           <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]"  />
                        </td>
                                            <td>
                           <input type="text" name="stockout[items][$i][batchNum]" id="batchNum$i" class="txtshort" value="$val[batchNum]"  />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
                            <input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
                            <input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
                            <input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[mainId]"  />
                            <input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" />
                            <input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i"   />
                        </td>
                        <td>
                           <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$val[actOutNum]"  />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')"  />
                        </td>
                        <td>
                             <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
                             <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
                             <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="" />
                        </td>
                        <td>
                        ******
                            <input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
                        </td>
                         <td>
                         ******
                            <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney"  />
                        </td>
                         <td>
                            <input type="text" name="stockout[items][$i][prodDate]" id="prodDate$i" onfocus="WdatePicker()" class="txtshort"  value="$val[prodDate]"  />
                        </td>
                         <td>
                            <input type="text" name="stockout[items][$i][shelfLife]" id="shelfLife$i" class="txtshort" value="$val[shelfLife]"  />
                        </td>
                        <td>
                            <input type="text" name="stockout[items][$i][validDate]" id="validDate$i" onfocus="WdatePicker()" class="txtshort" value="$val[validDate]"  />
                        </td>
                        <td>
                            <img src="images/closeDiv.gif" onclick="delItem(this);" title="删除行" />
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
     * 出库单下推入库单时，清单显示模板
     * @param
     */
    function showItemAtInStock($rows) {

    }

    /**
     * 新增出库单时处理相关业务
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
        //已审核的出库单,进入仓库
        if ($paramArr ['docStatus'] == "YSH") {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
            foreach ($relItemArr as $key => $value) {

                if (empty ($value ['relDocId'])) {
                    //更新库存
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                } else {
                    //更新库存
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                }
            }

			//处理关联单据
			if ($paramArr['relDocType'] == 'LLCKSCRWD' && $paramArr['relDocId']) { //生产任务单(生产领料)
				$pickingDao = new model_produce_plan_picking();
				if ($paramArr ['isRed'] == "0") {
					$pickingDao->finishStockOut($paramArr ,$relItemArr);
				} else {
					$pickingDao->cancelStockOut($paramArr ,$relItemArr);
				}
			}
        }
    }

    /**
     * 修改出库单时处理相关业务
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
        //已审核的出库单,进入仓库
        if ($paramArr ['docStatus'] == "YSH") {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
            foreach ($relItemArr as $key => $value) {

                if (empty ($value ['relDocId'])) {
                    //更新库存
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                } else {
                    //更新库存
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                }
            }

			//处理关联单据
			if ($paramArr['relDocType'] == 'LLCKSCRWD' && $paramArr['relDocId']) { //生产任务单(生产领料)
				$pickingDao = new model_produce_plan_picking();
				if ($paramArr ['isRed'] == "0") {
					$pickingDao->finishStockOut($paramArr ,$relItemArr);
				} else {
					$pickingDao->cancelStockOut($paramArr ,$relItemArr);
				}
			}
        }
    }

    /**
     * 反审核出库单
     * @param $stockoutObj
     */
    function cancelAudit($stockoutObj) {
        try {
            if ($stockoutObj ['docStatus'] == "YSH") { //确认单据是审核状态
                $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
                $serialnoDao = new model_stock_serialno_serialno (); //序列号DAO
                foreach ($stockoutObj ['items'] as $key => $value) {
                    //还原库存
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($stockoutObj ['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    }

                    //出库单物料序列号从已出库变为库存中
                    if (!empty ($value ['serialnoId'])) {
                        $sequenceId = $value ['serialnoId'];
                        $seqStatusVal = "0";
                        if ($stockoutObj ['isRed'] == "1") {
                            $seqStatusVal = "1";
                        }

                        $sequencObj = array("seqStatus" => $seqStatusVal);
                        $serialnoDao->update("id in($sequenceId)", $sequencObj);
                    }
                }
                
                //处理关联单据
                if ($stockoutObj['relDocType'] == 'LLCKSCRWD' && $stockoutObj['relDocId']) { //生产任务单(生产领料)
                	$pickingDao = new model_produce_plan_picking();
                	if ($stockoutObj ['isRed'] == "0") {
                		$pickingDao->cancelStockOut(array('relDocId' => $stockoutObj['relDocId']) ,$stockoutObj['items']);
                	} else {
                		$pickingDao->finishStockOut(array('relDocId' => $stockoutObj['relDocId']) ,$stockoutObj['items']);
                	}
                }
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取出库单物料清单详细信息
     * @author huangzf
     */
    function getItem($mainId) {
        $stockoutDao = new model_stock_outstock_stockoutitem ();
        $stockoutDao->searchArr ['mainId'] = $mainId;
        $itemsObj = $stockoutDao->listBySqlId();
        return $itemsObj;
    }
}