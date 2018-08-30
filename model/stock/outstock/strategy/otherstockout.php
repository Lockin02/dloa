<?php
header("Content-type: text/html; charset=gb2312");

//引入接口
include_once WEB_TOR . 'model/stock/outstock/strategy/istockout.php';

/**
 * @author huangzf
 * @Date 2011年3月12日 17:03:12
 * @version 1.0
 * @description:其他出库策略
 */
class model_stock_outstock_strategy_otherstockout implements istockout
{

    function __construct() {
        $this->relDocTypeArr = array(
            "QTCKFHJH" => array(
                "name" => "发货计划",
                "mainModel" => "model_stock_outplan_outplan",
                "dealMainFun" => "updateAsOut",
                "unAuditFun" => "updateAsAutiAudit",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            ),
            "QTCKFHD" => array(
                "name" => "发货单",
                "mainModel" => "model_stock_outplan_ship",
                "dealMainFun" => "setDocStatusById_d",
                "unAuditFun" => "unsetDocStatusById_d"
            ),
            "XSCKDLHT" => array(
                "name" => "鼎利合同",
                "mainModel" => "model_common_contract_allsource",
                "dealMainFun" => "updateAsOut",
                "unAuditFun" => "updateAsAutiAudit",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            ),
            "XSCKTHTZ" => array(
                "name" => "退货通知 ",
                "mainModel" => "model_projectmanagent_return_return",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            ),
            "XSCKHHSQ" => array(
                "name" => "换货申请",
                "mainModel" => "model_projectmanagent_exchange_exchange",
                "dealMainFun" => "updateAsOut",
                "unAuditFun" => "updateAsAutiAudit",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            ),
            "DBDYDLXGH" => array(
                "name" => "借试用归还确认单",
                "mainModel" => "model_projectmanagent_borrowreturn_borrowreturnDis",
                "dealMainFun" => "updateAsOutStock",
                "unAuditFun" => "updateAsAutiAuditStock",
                "dealRedFun" => "updateAsRedOutStock",
                "unAuditRedFun" => "updateAsRedAutiAuditStock"
            ),
            "QTCKZCCK" => array(
                "name" => "资产出库",
                "mainModel" => "model_asset_require_requirein",
                "dealMainFun" => "updateAsOut",
                "unAuditFun" => "updateAsAutiAudit",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            )
        );
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
                        <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" value="$val[productCode]"  />
					    <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                    </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$val[proType]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtItem" value="$val[productName]"  />
					</td>
    				<td>
    				    <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattern]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i"
						class="txtshort" onfocus="exploreProTipInfo($i)" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" ondblclick="chooseSerialNo($i)"  value="$val[actOutNum]"  />
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
                $i++;
            }
        }
        return $str;
    }

    /**
     * 修改其他出库单时 物料信息显示模板
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
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i"
						class="txtshort" onfocus="exploreProTipInfo($i)" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')" ondblclick="chooseSerialNo($i)"  value="$val[actOutNum]"  />
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
                $i++;
            }
        }
        return $str;
    }

    /**
     * 修改其他出库单时 物料信息显示模板
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
					<td class="tabledata">
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
						$val[remark]
					</td>
    				<td>
    					$val[prodDate]
					</td>
                    <td>
    					$val[shelfLife]
					</td>
					<td>
						$val[stockName]
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
				    <td class="tabledata">
					    $val[proType]
					</td>
					<td class="tabledata">
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
						{$val['actOutNum']}
					</td>
					<td class="tabledata">
						{$val['batchNum']}
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
						$val[remark]
					</td>
					<td class="tabledata">
						$val[prodDate]
					</td>
					<td class="tabledata">
						$val[shelfLife]
					</td>
					<td class="tabledata">
						$val[stockName]
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
<!--					<td class="tabledata">
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
<!--					<td class="tabledata">
						$val[k3Code]
					</td>-->
					<td class="tabledata">
						$productNameStr
					</td>
<!--					<td class="tabledata">
						$val[pattern]
					</td>
					<td class="tabledata">
						$val[unitName]
					</td>-->
					<td class="tabledata">
						{$val['actOutNum']}
					</td>
<!--					<td class="tabledata">
						{$val['batchNum']}
					</td>
					<td class="tabledata">
						<a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号" /></a>
					</td>-->
					<td class="tabledata formatMoneySix">
						$val[cost]
					</td>
					<td class="tabledata formatMoney">
						$val[subCost]
					</td>
<!--					<td class="tabledata">
						$val[remark]
					</td>
					<td class="tabledata">
						$val[prodDate]
					</td>
					<td class="tabledata">
						$val[shelfLife]
					</td>
					<td class="tabledata">
						$val[stockName]
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
<!--					<td class="tabledata">
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
<!--					<td class="tabledata">
						$val[k3Code]
					</td>-->
					<td class="tabledata">
						$productNameStr
					</td>
<!--					<td class="tabledata">
						$val[pattern]
					</td>
					<td class="tabledata">
						$val[unitName]
					</td>-->
					<td class="tabledata">
						-{$val['actOutNum']}
					</td>
<!--					<td class="tabledata">
						{$val['batchNum']}
					</td>
					<td class="tabledata">
						<a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号" /></a>
					</td>-->
					<td class="tabledata formatMoneySix">
						$val[cost]
					</td>
					<td class="tabledata formatMoney">
						$val[subCost]
					</td>
<!--					<td class="tabledata">
						$val[remark]
					</td>
					<td class="tabledata">
						$val[prodDate]
					</td>
					<td class="tabledata">
						$val[shelfLife]
					</td>
					<td class="tabledata">
						$val[stockName]
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
     * 根据蓝色其他出库单生成红色其他出库单，清单显示模板
     * @param  $rows
     * @param  $istrategy
     */
    function showRelItem($rows) {
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
    				    <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="$val[pattern]"  />
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[mainId]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i"  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i"   />
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
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="$val[subCost]"  />
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
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach ($rows as $key => $val) {
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
                            <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="{$val['productCode']}" />
                            <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="{$val['productId']}"  />
                            <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i" />
                            <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"   />
                             <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i" value=""  />
                        </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$val[proType]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
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
                            <input type="text" name="stockin[items][$i][batchNo]"  id="batchNo$i" class="txtshort" />
                        </td>
                        <td>
                            <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtItem" value="{$val['actOutNum']}" />
                        </td>
                        <td>
                            <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)"  class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')" />
                            <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"   />
							<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  />
                        </td>
                        <td>
                            <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="{$val['stockName']}" />
                            <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="{$val['stockId']}" />
                            <input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="{$val['stockCode']}" />
                            <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"   value="{$val['mainId']}" />
                            <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"   />
                           	<input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"   />
                        </td>
                        <td>
                        ******
                            <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoneySix" value="{$val['cost']}"  onblur="FloatMul('price$i','actNum$i','subPrice$i')" />
                        </td>
                        <td>
                        ******
                            <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtItem formatMoney"  />
                        </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort"  />
                    </td>
                	</tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 新增出库单时处理相关业务TODO
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAdd($paramArr = false, $relItemArr = array()) {
        //已审核的出库单,进入仓库
        if ($paramArr ['docStatus'] == "YSH") {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
            //$relDocProArr=array();//关联单据物料清单
            foreach ($relItemArr as $key => $value) {
                //更新库存
                $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                if ($paramArr ['isRed'] == "0") { //蓝色
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                } else { //红色
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                }

                //修改其他入库单业务用于 赠送、退换货、借试用
                if (!empty ($value ['relDocId'])) {
                    /*start:处理关联单据,处理锁定解锁*/
                    $lockDao = new model_stock_lock_lock ();
                    $relDocModelInfo = $this->relDocTypeArr [$paramArr ['relDocType']];
                    if ($relDocModelInfo && $value ['relDocId']) {
                        $relDocDao = new $relDocModelInfo ['mainModel'] ();
						$relDocItemArr = array(
							"id" => $value['id'],"relDocId" => $paramArr['relDocId'],
							"relDocItemId" => $value['relDocId'], "productId" => $value['productId'],
							"outNum" => $value['actOutNum'], "outPrice" => $value['cost'],
							"contractType" => $paramArr['contractType']
						);

                        if ($paramArr ['isRed'] == "0") { //蓝色业务处理
                            $relDocDaoFun = $relDocModelInfo ['dealMainFun'];
                            $relDocDao->$relDocDaoFun ($relDocItemArr);

                            if ($paramArr ['relDocType'] == "QTCKFHJH") { //源单据为发货计划的需要处理锁定信息
                                $lockNum = $relDocDao->findLockNum($paramArr ['relDocId'], $value ['stockId'], $value ['productId']);
                                if (!empty ($lockNum) && $lockNum > 0) {
                                    if ($value ['actOutNum'] >= $lockNum) { //出库数量大于锁数量
                                        $releaseObj = array("outDocId" => $paramArr ['docId'], "planId" => $paramArr ['relDocId'], "relDocItemId" => $value ['relDocId'], "stockId" => $value ['stockId'], "productId" => $value ['productId'], "lockNum" => $lockNum);

                                    } else { //出库数量小于锁数量
                                        $releaseObj = array("outDocId" => $paramArr ['docId'], "planId" => $paramArr ['relDocId'], "relDocItemId" => $value ['relDocId'], "stockId" => $value ['stockId'], "productId" => $value ['productId'], "lockNum" => $value ['actOutNum']);
                                    }
                                    $lockDao->releaseLockByOutPlan($releaseObj);
                                }
                            }
                        } else {
                            $relDocDaoRedFun = $relDocModelInfo ['dealRedFun'];
                            if ($relDocDaoRedFun) {
                                $relDocDao->$relDocDaoRedFun ($relDocItemArr);
                            }
                        }
                    }
                    /*end:处理关联单据,处理锁定解锁*/
                }
            }
            // 资产出库，对接aws
            if(strlen($paramArr ['relDocId']) >= 32 && $paramArr ['relDocType'] == 'QTCKZCCK'){
            	$obj = array();//用来存放推送的数据
            	$detail = array();//用来存放明细
            	if($paramArr ['isRed'] == "0"){// 蓝色单,推送验收单据到aws
            		$amount = 0;// 用来计算总金额
            		foreach ($relItemArr as $key => $value) {
            			array_push($detail, array(
	            			'comeFromItemId' => $value['id'],
	            			'productId' => $value['productId'],
	            			'productCode' => $value['productCode'],
	            			'productName' => $value['productName'],
	            			'pattern' => $value['pattern'],
	            			'unit' => $value['unitName'],
	            			'num' => $value['actOutNum'],
	            			'price' => $value['cost'],
	            			'amount' => $value['subCost'],
							'serialNo' => $value['serialnoName']
            			));
            			$amount += $value['subCost'];
            		}
            		$obj['comeFrom'] = 'YSLY-02';// 验收类型为【物料转资产】
            		$obj['comeFromId'] = $paramArr['docId'];// 来源id
            		$obj['comeFromNo'] = '';// 来源单号,此处为空,因为出库单存的是需求申请单号,需要在aws获取
            		// 主表信息
            		$obj['acceptance']['userId'] = 'admin';
            		$obj['acceptance']['userName'] = '管理员';
            		$obj['acceptance']['result'] = '系统生成';
            		$obj['acceptance']['amount'] = $amount;
            		$obj['acceptance']['id'] = $paramArr['relDocId'];// 物料转资产id,用于获取物料转资产单号
            		// 明细信息
            		$obj['acceptance']['detail'] = $detail;
            		// 推送验收单数据给aws
            		$obj['requiredNo'] = '';//采购才需要传资产需求申请编号，这边传空值
            		util_curlUtil::getDataFromAWS('asset', 'createAcceptance', $obj);
            	}else{// 红色单,删除aws验收单数据
            		foreach ($relItemArr as $key => $value) {
            			array_push($detail, array(
            				'comeFromItemId' => $value['id']
            			));
            		}
            		$obj['comeFromId'] = $paramArr['docId'];// 来源id
					$obj['comeFrom'] = 'YSLY-02';// 来源类型
            		// 明细信息
            		$obj['acceptance']['detail'] = $detail;
            		// 推送验收单数据给aws
            		util_curlUtil::getDataFromAWS('asset', 'deleteAcceptance', $obj);
            	}
            }
        }
    }

    /**
     * 修改出库单时处理相关业务
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = array()) {
        //已审核的出库单,进入仓库
        if ($paramArr ['docStatus'] == "YSH") {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //库存DAO
            foreach ($relItemArr as $key => $value) {
                //更新库存
                $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                if ($paramArr ['isRed'] == "0") { //蓝色
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                } else { //红色
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                }

                //修改其他入库单业务用于 赠送、退换货、借试用
                if (!empty ($value ['relDocId'])) {
                    /*start:处理关联单据,处理锁定解锁*/
                    $lockDao = new model_stock_lock_lock ();
                    $relDocModelInfo = $this->relDocTypeArr [$paramArr ['relDocType']];
                    if ($relDocModelInfo && $value ['relDocId']) {
                        $relDocDao = new $relDocModelInfo ['mainModel'] ();
                        $relDocItemArr = array(
							"id" => $value['id'],"relDocId" => $paramArr['relDocId'],
							"relDocItemId" => $value['relDocId'], "productId" => $value['productId'],
							"outNum" => $value['actOutNum'], "outPrice" => $value['cost'],
							"contractType" => $paramArr['contractType']
						);

                        if ($paramArr ['isRed'] == "0") { //蓝色业务处理
                            $relDocDaoFun = $relDocModelInfo ['dealMainFun'];
                            $relDocDao->$relDocDaoFun ($relDocItemArr);

                            if ($paramArr ['relDocType'] == "QTCKFHJH") { //源单据为发货计划的需要处理锁定信息
                                $lockNum = $relDocDao->findLockNum($paramArr ['relDocId'], $value ['stockId'], $value ['productId']);
                                if (!empty ($lockNum) && $lockNum > 0) {
                                    if ($value ['actOutNum'] >= $lockNum) { //出库数量大于锁数量
                                        $releaseObj = array("outDocId" => $paramArr ['docId'], "planId" => $paramArr ['relDocId'], "relDocItemId" => $value ['relDocId'], "stockId" => $value ['stockId'], "productId" => $value ['productId'], "lockNum" => $lockNum);

                                    } else { //出库数量小于锁数量
                                        $releaseObj = array("outDocId" => $paramArr ['docId'], "planId" => $paramArr ['relDocId'], "relDocItemId" => $value ['relDocId'], "stockId" => $value ['stockId'], "productId" => $value ['productId'], "lockNum" => $value ['actOutNum']);
                                    }
                                    $lockDao->releaseLockByOutPlan($releaseObj);
                                }
                            }
                        } else {
                            $relDocDaoRedFun = $relDocModelInfo ['dealRedFun'];
                            if ($relDocDaoRedFun) {
                                $relDocDao->$relDocDaoRedFun ($relDocItemArr);
                            }
                        }
                    }
                    /*end:处理关联单据,处理锁定解锁*/
                }
            }
            // 资产出库，对接aws
            if(strlen($paramArr ['relDocId']) >= 32 && $paramArr ['relDocType'] == 'QTCKZCCK'){
            	$obj = array();//用来存放推送的数据
            	$detail = array();//用来存放明细
            	if($paramArr ['isRed'] == "0"){// 蓝色单,推送验收单据到aws
            		$amount = 0;// 用来计算总金额
            		foreach ($relItemArr as $key => $value) {
            			array_push($detail, array(
							'comeFromItemId' => $value['id'],
							'productId' => $value['productId'],
							'productCode' => $value['productCode'],
							'productName' => $value['productName'],
							'pattern' => $value['pattern'],
							'unit' => $value['unitName'],
							'num' => $value['actOutNum'],
							'price' => $value['cost'],
							'amount' => $value['subCost'],
							'serialNo' => $value['serialnoName']
            			));
            			$amount += $value['subCost'];
            		}
            		$obj['comeFrom'] = 'YSLY-02';// 验收类型为【物料转资产】
            		$obj['comeFromId'] = $paramArr['docId'];// 来源id
            		$obj['comeFromNo'] = '';// 来源单号,此处为空,因为出库单存的是需求申请单号,需要在aws获取
            		// 主表信息
            		$obj['acceptance']['userId'] = 'admin';
            		$obj['acceptance']['userName'] = '管理员';
            		$obj['acceptance']['result'] = '系统生成';
            		$obj['acceptance']['amount'] = $amount;
            		$obj['acceptance']['id'] = $paramArr['relDocId'];// 物料转资产id,用于获取物料转资产单号
            		// 明细信息
            		$obj['acceptance']['detail'] = $detail;
            		// 推送验收单数据给aws
            		$obj['requiredNo'] = '';//采购才需要传资产需求申请编号，这边传空值
            		util_curlUtil::getDataFromAWS('asset', 'createAcceptance', $obj);
            	}else{// 红色单,删除aws验收单数据
            		foreach ($relItemArr as $key => $value) {
            			array_push($detail, array(
            			'comeFromItemId' => $value['id']
            			));
            		}
            		$obj['comeFromId'] = $paramArr['docId'];// 来源id
					$obj['comeFrom'] = 'YSLY-02';// 来源类型
            		// 明细信息
            		$obj['acceptance']['detail'] = $detail;
            		// 推送验收单数据给aws
            		util_curlUtil::getDataFromAWS('asset', 'deleteAcceptance', $obj);
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
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    $relDocModelInfo = $this->relDocTypeArr [$stockoutObj ['relDocType']];
                    if ($relDocModelInfo) {
                        $relDocDao = new $relDocModelInfo ['mainModel'] ();
                    }
                    $relDocItemArr = array(
						"id" => $value['id'],"relDocId" => $stockoutObj['relDocId'],
						"relDocItemId" => $value['relDocId'], "productId" => $value['productId'],
						"outNum" => $value['actOutNum'], "outPrice" => $value['cost'],
						"contractType" => $stockoutObj['contractType']
					);
                    if ($stockoutObj ['isRed'] == "0") { //蓝色
                        //还原库存
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                        //还原关联业务
                        if ($relDocModelInfo && $value ['relDocId']) {
                            $relDocDaoFun = $relDocModelInfo ['unAuditFun'];
                            $relDocDao->$relDocDaoFun ($relDocItemArr);
                        }
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                        if ($relDocModelInfo && $value ['relDocId']) {
                            $relDocDaoRedFun = $relDocModelInfo ['unAuditRedFun'];
                            if ($relDocDaoRedFun) {
                                $relDocDao->$relDocDaoRedFun ($relDocItemArr);
                            }
                        }
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

                if ($stockoutObj ['isRed'] == "0") {
                    if ($stockoutObj ['relDocType'] == "QTCKFHJH") { //源单据为发货计划的需要处理锁定信息
                        /*start:删除对应出库释放锁定记录及增加即时库存的锁定数量*/
                        $lockDao = new model_stock_lock_lock ();
                        $salePlanDao = new model_stock_outplan_outplan ();
                        $salePlanObj = $salePlanDao->get_d($stockoutObj ['relDocId']);

                        //根据出库单id及发货计划关联的源单类型查找出库的负数量锁定记录
                        $lockSearchArr = array("outStockDocId" => $stockoutObj ['id'], "objType" => $salePlanObj ['docType']);
                        $lockDao->searchArr = $lockSearchArr;
                        $relLockArr = $lockDao->list_d();
                        if (is_array($relLockArr)) {
                            foreach ($relLockArr as $key => $relLockObj) {
                                $invSearchArr = array("stockId" => $relLockObj ['stockId'], "productId" => $relLockObj ['productId']);
                                $inventoryDao->searchArr = $invSearchArr;
                                $inventoryArr = $inventoryDao->list_d();
                                if (is_array($inventoryArr)) {
                                    $inventoryObj = $inventoryArr [0];
                                    $inventoryObj ['lockedNum'] = $inventoryObj ['lockedNum'] - $relLockObj ['lockNum'];
                                    $inventoryObj ['exeNum'] = $inventoryObj ['exeNum'] + $relLockObj ['lockNum'];
                                    $inventoryDao->updateById($inventoryObj);
                                }

                            }
                            $lockDao->delete($lockSearchArr);
                        }
                        /*end:删除对应出库释放锁定记录及增加即时库存的锁定数量*/
                    }
                }
                // 资产出库，对接aws
                if(strlen($stockoutObj ['relDocId']) >= 32 && $stockoutObj ['relDocType'] == 'QTCKZCCK'){
                	$obj = array();//用来存放推送的数据
                	$detail = array();//用来存放明细
                	if($stockoutObj ['isRed'] == "1"){// 红色单,推送验收单据到aws
                		$amount = 0;// 用来计算总金额
                		foreach ($stockoutObj ['items'] as $key => $value) {
                			array_push($detail, array(
	                			'comeFromItemId' => $value['id'],
	                			'productId' => $value['productId'],
	                			'productCode' => $value['productCode'],
	                			'productName' => $value['productName'],
	                			'pattern' => $value['pattern'],
	                			'unit' => $value['unitName'],
	                			'num' => $value['actOutNum'],
	                			'price' => $value['cost'],
	                			'amount' => $value['subCost'],
								'serialNo' => $value['serialnoName']
                			));
                			$amount += $value['subCost'];
                		}
                		$obj['comeFrom'] = 'YSLY-02';// 验收类型为【物料转资产】
                		$obj['comeFromId'] = $stockoutObj['docId'];// 来源id
                		$obj['comeFromNo'] = '';// 来源单号,此处为空,因为出库单存的是需求申请单号,需要在aws获取
                		// 主表信息
                		$obj['acceptance']['userId'] = 'admin';
                		$obj['acceptance']['userName'] = '管理员';
                		$obj['acceptance']['result'] = '系统生成';
                		$obj['acceptance']['amount'] = $amount;
                		$obj['acceptance']['id'] = $stockoutObj['relDocId'];// 物料转资产id,用于获取物料转资产单号
                		// 明细信息
                		$obj['acceptance']['detail'] = $detail;
                		// 推送验收单数据给aws
                		$obj['requiredNo'] = '';//采购才需要传资产需求申请编号，这边传空值
                		util_curlUtil::getDataFromAWS('asset', 'createAcceptance', $obj);
                	}else{// 蓝色单,删除aws验收单数据
                		foreach ($stockoutObj ['items'] as $key => $value) {
                			array_push($detail, array(
                				'comeFromItemId' => $value['id']
                			));
                		}
                		$obj['comeFromId'] = $stockoutObj['id'];// 来源id
						$obj['comeFrom'] = 'YSLY-02';// 来源类型
                		// 明细信息
                		$obj['acceptance']['detail'] = $detail;
                		// 推送验收单数据给aws
                		util_curlUtil::getDataFromAWS('asset', 'deleteAcceptance', $obj);
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