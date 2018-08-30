<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/stock/outstock/strategy/istockout.php';

/**
 * @author huangzf
 * @Date 2011��3��12�� 17:03:12
 * @version 1.0
 * @description:���۳����������
 */
class model_stock_outstock_strategy_salesstockout implements istockout
{

    function __construct() {
        $this->relDocTypeArr = array(
            "XSCKFHJH" => array(
                "name" => "�����ƻ�",
                "mainModel" => "model_stock_outplan_outplan",
                "dealMainFun" => "updateAsOut",
                "unAuditFun" => "updateAsAutiAudit",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            ),
            "XSCKFHD" => array(
                "name" => "������",
                "mainModel" => "model_stock_outplan_ship",
                "dealMainFun" => "setDocStatusById_d",
                "unAuditFun" => "unsetDocStatusById_d"
            ),
            "XSCKDLHT" => array(
                "name" => "������ͬ",
                "mainModel" => "model_common_contract_allsource",
                "dealMainFun" => "updateAsOut",
                "unAuditFun" => "updateAsAutiAudit",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            ),
            "XSCKTHTZ" => array(
                "name" => "�˻�֪ͨ ",
                "mainModel" => "model_projectmanagent_return_return",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            ),
            "XSCKHHSQ" => array(
                "name" => "�������� ",
                "mainModel" => "model_projectmanagent_exchange_exchange",
                "dealMainFun" => "updateAsOut",
                "unAuditFun" => "updateAsAutiAudit",
                "dealRedFun" => "updateAsRedOut",
                "unAuditRedFun" => "updateAsRedAutiAudit"
            )
        );
    }

    /**
     * ������ɫ���۳��ⵥʱ ������Ϣ��ʾģ��
     * @param  $rows
     */
    function showProAddRed($rows) {
        $str = "";
        if ($rows) {
            $i = 0;
            foreach ($rows as $key => $val) {
                $productCodeClass = "txtshort";
                $productNameClass = "txt";
                if ($val ['relDocId'] > 0) {
                    $productCodeClass = "readOnlyTxtShort";
                    $productNameClass = "readOnlyTxtNormal";
                    $readonly = "readonly='readonly'";
                }
                $sNum = $i + 1;
                $str .= <<<EOT
				<tr align="center">
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
					</td>
                    <td>
                        $sNum
                    </td>
                    <td>
                        <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="$productCodeClass" value="$val[productCode]" $readonly/>
					    <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                    </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$val[proType]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="$productNameClass" value="$val[productName]" $readonly/>
					</td>
    				<td>
    				    <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattern]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtShort" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" onfocus="exploreProTipInfo($i)" ondblclick="chooseSerialNo($i)"
							 onblur="javascript:FloatMul('actOutNum$i','salecost$i','saleSubCost$i');FloatMul('actOutNum$i','cost$i','subCost$i');" value="$val[actOutNum]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i"  value="$val[relDocId]" />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" />
					</td>
					<td>
						<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
						<input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
						<input type="text" name="stockout[items][$i][serialnoName]" style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i" readonly="readonly"/>
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
					    ******
						<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')" value="$val[salecost]"  />
					</td>
    				<td>
					    ******
    					<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtShort formatMoney" value="$val[saleSubCost]" readonly="readonly"/>
					</td>
			</tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * �޸����۳��ⵥʱ ������Ϣ��ʾģ��
     * @param  $rows
     */
    function showProAtEdit($rows) {
        $str = "";
        if ($rows) {
            $i = 0;
            foreach ($rows as $key => $val) {
                $sNum = $i + 1;
                $productCodeClass = "txtshort";
                $productNameClass = "txt";
                if ($val ['relDocId'] > 0) {
                    $productCodeClass = "readOnlyTxtShort";
                    $productNameClass = "readOnlyTxtNormal";
                    $readonly = "readonly='readonly'";
                }
                $str .= <<<EOT
				<tr align="center">
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
					</td>
                    <td>
                   		$sNum
                    </td>
                    <td>
                        <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="$productCodeClass" value="$val[productCode]" $readonly/>
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
					    <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="$productNameClass" value="$val[productName]" $readonly/>
					</td>
    				<td>
    				    <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattern]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtShort" value="$val[shouldOutNum]" readonly="readonly"/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" onfocus="exploreProTipInfo($i)" ondblclick="chooseSerialNo($i)"
							onblur="javascript:FloatMul('actOutNum$i','salecost$i','saleSubCost$i');FloatMul('actOutNum$i','cost$i','subCost$i');"  value="$val[actOutNum]"  />
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
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value="$val[serialnoId]"/>
						 <input type="text" name="stockout[items][$i][serialnoName]" style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="$val[serialnoName]" readonly="readonly"/>
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
					******
						<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')" value="$val[salecost]"  />
					</td>
    				<td>
					******
    					<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtShort formatMoney" value="$val[saleSubCost]" readonly="readonly"/>
					</td>
			</tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * �޸����۳��ⵥʱ ������Ϣ��ʾģ��
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
                        <input type="hidden" name="stockout[items][$i][id]" id="id$i " value="$val[id]"  />
					    <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
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
						<input type="hidden" name="stockout[items][$i][actOutNum]" id="actOutNum$i" value="$val[actOutNum]"/>
					</td>
					<td>
						<input type="text" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
					</td>
                    <td>
                        <input type="text" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="$val[subCost]"  />
					</td>
                    <td>
						$val[salecost]
					</td>
    				<td>
    					$val[saleSubCost]
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
     * �鿴���ⵥ�����嵥��ʾģ��
     * @param
     */
    function showProAtView($rows) {
        $str = ""; //���ص�ģ���ַ���
        if ($rows) {
            $i = 0; //�б��¼���
            foreach ($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
                $productNameStr = implode("<br />", $productNameArr);

                $sNum = $i + 1;
                $str .= <<<EOT
					<tr align="center" >
					<td class="tabledata">
						$sNum
					</td>
					<td class="tabledata" >
						$val[productCode]
					</td>
					<td class="tabledata" >
						$val[proType]
					</td>
					<td class="tabledata" >
						$val[k3Code]
					</td>
					<td class="tabledata" >
						$productNameStr
					</td>
					<td class="tabledata">
						$val[pattern]
					</td>
					<td class="tabledata">
						$val[unitName]
					</td>
					<td class="tabledata">
						$val[shouldOutNum]
					</td>
					<td class="tabledata">
						{$val['actOutNum']}
					</td>
					<td class="tabledata">
						{$val['batchNum']}
					</td>
					<td class="tabledata">
						<a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="�鿴���к���Ϣ" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="�鿴���к�" /></a>
					</td>
					<td class="tabledata formatMoneySix">
						$val[cost]
					</td>
					<td class="tabledata formatMoney">
						$val[subCost]
					</td>
					<td class="tabledata formatMoneySix">
						$val[salecost]
					</td>
					<td class="tabledata formatMoney">
						$val[saleSubCost]
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
     * �鿴���ⵥ�����嵥��ʾģ��
     * @param
     */
    function showProAtPrint($rows) {
        $str = ""; //���ص�ģ���ַ���
        if ($rows) {
            $i = 0; //�б��¼���
            foreach ($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
                $productNameStr = implode("<br />", $productNameArr);

                $sNum = $i + 1;
                $str .= <<<EOT
					<tr align="center" >
<!--					<td class="tabledata">
						$sNum
					</td>-->
					<td class="tabledata" >
						$val[productCode]
                       <input type="hidden" id="productCode$i" value="{$val['productCode']}">
                       <input type="hidden" id="productName$i" value="{$val['productName']}">
                       <input type="hidden" id="unitName$i" value="{$val['unitName']}">
                       <input type="hidden" id="pattern$i" value="{$val['pattern']}">
                       <input type="hidden" id="actOutNum$i" value="{$val['actOutNum']}">
<!--					</td>
					<td class="tabledata" >
						$val[k3Code]
					</td>-->
					<td class="tabledata" >
						$productNameStr
<!--					</td>
					<td class="tabledata">
						$val[pattern]
					</td>
					<td class="tabledata">
						$val[unitName]
					</td>
					<td class="tabledata">
						$val[shouldOutNum]
					</td>-->
					<td class="tabledata">
						{$val['actOutNum']}
					</td>
<!--					<td class="tabledata">
						{$val['batchNum']}
					</td>
					<td class="tabledata">
						<a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="�鿴���к���Ϣ" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="�鿴���к�" /></a>
					</td>
					<td class="tabledata formatMoneySix">
						$val[cost]
					</td>
					<td class="tabledata formatMoney">
						$val[subCost]
					</td>-->
					<td class="tabledata formatMoneySix">
						$val[salecost]
					</td>
					<td class="tabledata formatMoney">
						$val[saleSubCost]
					</td>
<!--					<td class="tabledata">
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
     * �鿴���ⵥ�����嵥��ʾģ�� --��ɫ��
     * @param
     */
    function showRedProAtPrint($rows) {
    	$str = ""; //���ص�ģ���ַ���
    	if ($rows) {
    		$i = 0; //�б��¼���
    		foreach ($rows as $key => $val) {
    			$productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
    			$productNameStr = implode("<br />", $productNameArr);
    
    			$sNum = $i + 1;
    			$str .= <<<EOT
					<tr align="center" >
<!--					<td class="tabledata">
						$sNum
					</td>-->
					<td class="tabledata" >
						$val[productCode]
                       <input type="hidden" id="productCode$i" value="{$val['productCode']}">
                       <input type="hidden" id="productName$i" value="{$val['productName']}">
                       <input type="hidden" id="unitName$i" value="{$val['unitName']}">
                       <input type="hidden" id="pattern$i" value="{$val['pattern']}">
                       <input type="hidden" id="actOutNum$i" value="-{$val['actOutNum']}">
<!--					</td>
					<td class="tabledata" >
						$val[k3Code]
					</td>-->
					<td class="tabledata" >
						$productNameStr
<!--					</td>
					<td class="tabledata">
						$val[pattern]
					</td>
					<td class="tabledata">
						$val[unitName]
					</td>
					<td class="tabledata">
						$val[shouldOutNum]
					</td>-->
					<td class="tabledata">
						-{$val['actOutNum']}
					</td>
<!--					<td class="tabledata">
						{$val['batchNum']}
					</td>
					<td class="tabledata">
						<a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="�鿴���к���Ϣ" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="�鿴���к�" /></a>
					</td>
					<td class="tabledata formatMoneySix">
						$val[cost]
					</td>
					<td class="tabledata formatMoney">
						$val[subCost]
					</td>-->
					<td class="tabledata formatMoneySix">
						$val[salecost]
					</td>
					<td class="tabledata formatMoney">
						$val[saleSubCost]
					</td>
<!--					<td class="tabledata">
						$val[stockName]
					</td>-->
					</tr>
EOT;
    						$i++;
    		}
    	}
    	return $str;
    }
    
    //��תҳ���ӡ
    function showPrintForCarry($rows) {
        $str = ""; //���ص�ģ���ַ���
        if ($rows) {
            $i = 0; //�б��¼���
            foreach ($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val ['productName'], 20);
                $productNameStr = implode("<br />", $productNameArr);
                $str .= <<<EOT
					<tr align="center" id="tr$val[id]">
						<td class="hiddenAction">
							<input type="checkbox" name="hiddenAction" value="$val[id]"/>
						</td>
						<td class="tabledata" >
							$val[productCode]
                       <input type="hidden" id="productCode$i" value="{$val['productCode']}">
                       <input type="hidden" id="productName$i" value="{$val['productName']}">
                       <input type="hidden" id="unitName$i" value="{$val['unitName']}">
                       <input type="hidden" id="pattern$i" value="{$val['pattern']}">
                       <input type="hidden" id="actOutNum$i" value="{$val['actOutNum']}">
						</td>
						<td class="tabledata" >
							$productNameStr
						</td>
						<td class="tabledata">
							$val[unitName]
						</td>
						<td class="tabledata">
							{$val['actOutNum']}
						</td>
						<td class="tabledata formatMoneySix">
							$val[cost]
						</td>
						<td class="tabledata formatMoney">
							$val[subCost]
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
     * ������ɫ���ⵥ���ɺ�ɫ���ⵥ���嵥��ʾģ��
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
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
					    <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$val[actOutNum]"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" onfocus="exploreProTipInfo($i)" ondblclick="chooseSerialNo($i)"  onblur="FloatMul('actOutNum$i','cost$i','subCost$i')"   />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[stockName]"  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[stockId]"  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[stockCode]"  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[mainId]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" value=""  />
					</td>
					<td>
					    <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
                        <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
                        <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="" />
					</td>
					<td>
					******
						<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[cost]"  />
					</td>
                    <td>
					******
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney"   />
					</td>
                    <td>
					******
						<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')" />
					</td>
    				<td>
					******
    					<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtItem formatMoney"  />
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
     * ���ⵥ������ⵥʱ���嵥��ʾģ��
     * @param
     */
    function showItemAtInStock($rows) {

    }

    /**
     * �������ⵥʱ�������ҵ��
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
        //����˵ĳ��ⵥ,����ֿ�
        if ($paramArr ['docStatus'] == "YSH") {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
            foreach ($relItemArr as $key => $value) {
                //���¿��
                $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                if ($paramArr ['isRed'] == "0") { //��ɫ
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                } else { //��ɫ
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                }
                if (!empty ($value ['relDocId'])) {
                    /*start:�����������,������������*/
                    $lockDao = new model_stock_lock_lock ();
                    $relDocModelInfo = $this->relDocTypeArr [$paramArr ['relDocType']];
                    if ($relDocModelInfo && $value ['relDocId']) {
                        $relDocDao = new $relDocModelInfo ['mainModel'] ();
                        $relDocItemArr = array(
							"relDocId" => $paramArr ['relDocId'],  "relDocItemId" => $value ['relDocId'],
							"productId" => $value ['productId'],  "outNum" => $value ['actOutNum'],
							"contractId" => $paramArr ['contractId'], "contractType" => $paramArr ['contractType']);

                        if ($paramArr ['isRed'] == "0") { //��ɫҵ����
                            $relDocDaoFun = $relDocModelInfo ['dealMainFun'];
                            $relDocDao->$relDocDaoFun ($relDocItemArr);

                            if ($paramArr ['relDocType'] == "XSCKFHJH") { //Դ����Ϊ�����ƻ�����Ҫ����������Ϣ
                                $lockNum = $relDocDao->findLockNum($paramArr ['relDocId'], $value ['stockId'], $value ['productId']);
                                if (!empty ($lockNum) && $lockNum > 0) {
                                    if ($value ['actOutNum'] >= $lockNum) { //������������������
                                        $releaseObj = array("outDocId" => $paramArr ['docId'], "planId" => $paramArr ['relDocId'], "relDocItemId" => $value ['relDocId'], "stockId" => $value ['stockId'], "productId" => $value ['productId'], "lockNum" => $lockNum);

                                    } else { //��������С��������
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
                    /*end:�����������,������������*/
                }
            }
        }
    }

    /**
     * �޸ĳ��ⵥʱ�������ҵ��
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
        //����˵ĳ��ⵥ,����ֿ�
        if ($paramArr ['docStatus'] == "YSH") {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
            foreach ($relItemArr as $key => $value) {
                //���¿��
                $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                if ($paramArr ['isRed'] == "0") { //��ɫ
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                } else { //��ɫ
                    $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                }

                if (!empty ($value ['relDocId'])) {
                    /*start:�����������,������������*/
                    $lockDao = new model_stock_lock_lock ();
                    $relDocModelInfo = $this->relDocTypeArr [$paramArr ['relDocType']];
                    if ($relDocModelInfo && $value ['relDocId']) {
                        $relDocDao = new $relDocModelInfo ['mainModel'] ();
						$relDocItemArr = array(
							"relDocId" => $paramArr ['relDocId'],  "relDocItemId" => $value ['relDocId'],
							"productId" => $value ['productId'],  "outNum" => $value ['actOutNum'],
							"contractId" => $paramArr ['contractId'], "contractType" => $paramArr ['contractType']);


						if ($paramArr ['isRed'] == "0") {
                            $relDocDaoFun = $relDocModelInfo ['dealMainFun'];
                            $relDocDao->$relDocDaoFun ($relDocItemArr);
                            if ($paramArr ['relDocType'] == "XSCKFHJH") { //Դ����Ϊ�����ƻ�����Ҫ����������Ϣ
                                $lockNum = $relDocDao->findLockNum($paramArr ['relDocId'], $value ['stockId'], $value ['productId']);
                                if (!empty ($lockNum) && $lockNum > 0) {
                                    if ($value ['actOutNum'] >= $lockNum) { //������������������
                                        $releaseObj = array("outDocId" => $paramArr ['docId'], "planId" => $paramArr ['relDocId'], "relDocItemId" => $value ['relDocId'], "stockId" => $value ['stockId'], "productId" => $value ['productId'], "lockNum" => $lockNum);
                                    } else { //��������С��������
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
                    /*end:�����������,������������*/
                }
            }
        }
    }

    /**
     * ����˳��ⵥ
     * @param $stockoutObj
     */
    function cancelAudit($stockoutObj) {
        try {
            if ($stockoutObj ['docStatus'] == "YSH") { //ȷ�ϵ��������״̬
                $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
                $serialnoDao = new model_stock_serialno_serialno (); //���к�DAO
                foreach ($stockoutObj ['items'] as $key => $value) {
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    $relDocModelInfo = $this->relDocTypeArr [$stockoutObj ['relDocType']];
                    if ($relDocModelInfo) {
                        $relDocDao = new $relDocModelInfo ['mainModel'] ();
                    }
                    $relDocItemArr = array(
						"relDocId" => $stockoutObj ['relDocId'], "relDocItemId" => $value ['relDocId'],
						"productId" => $value ['productId'], "outNum" => $value ['actOutNum'],
						"contractId" => $stockoutObj ['contractId'], "contractType" => $stockoutObj ['contractType']);

                    if ($stockoutObj ['isRed'] == "0") { //��ɫ
                        //��ԭ���
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                        //��ԭ����ҵ��
                        if ($relDocModelInfo && $value ['relDocId']) {
                            $relDocDaoFun = $relDocModelInfo ['unAuditFun'];
                            $relDocDao->$relDocDaoFun ($relDocItemArr);
                        }
                    } else { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                        if ($relDocModelInfo && $value ['relDocId']) {
                            $relDocDaoRedFun = $relDocModelInfo ['unAuditRedFun'];
                            if ($relDocDaoRedFun) {
                                $relDocDao->$relDocDaoRedFun ($relDocItemArr);
                            }
                        }
                    }

                    //���ⵥ�������кŴ��ѳ����Ϊ�����
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

                //��ԭ��װ������Ϣ
                if (is_array($stockoutObj ['packitems'])) {
                    $stockSystemDao = new model_stock_stockinfo_systeminfo ();
                    $stockSysObj = $stockSystemDao->get_d(1);
                    $packInOut = "instock";
                    if ($stockoutObj ['isRed'] == "1") {
                        $packInOut = "outstock";
                    }

                    foreach ($stockoutObj ['packitems'] as $key => $value) {
                        //��ԭ���
                        $packStockParamArr = array("stockId" => $stockSysObj ['packingStockId'], "productId" => $value ['productId']);
                        $inventoryDao->updateInTimeInfo($packStockParamArr, $value ['outstockNum'], $packInOut);
                    }
                }

                if ($stockoutObj ['isRed'] == "0") {
                    if ($stockoutObj ['relDocType'] == "XSCKFHJH") { //Դ����Ϊ�����ƻ�����Ҫ����������Ϣ
                        /*start:ɾ����Ӧ�����ͷ�������¼�����Ӽ�ʱ������������*/
                        $lockDao = new model_stock_lock_lock ();
                        $salePlanDao = new model_stock_outplan_outplan ();
                        $salePlanObj = $salePlanDao->get_d($stockoutObj ['relDocId']);

                        //���ݳ��ⵥid�������ƻ�������Դ�����Ͳ��ҳ���ĸ�����������¼
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
                        /*end:ɾ����Ӧ�����ͷ�������¼�����Ӽ�ʱ������������*/
                    }
                }
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ��ȡ���ⵥ�����嵥��ϸ��Ϣ
     * @author huangzf
     */
    function getItem($mainId) {
        $stockoutDao = new model_stock_outstock_stockoutitem ();
        $stockoutDao->searchArr ['mainId'] = $mainId;
        $itemsObj = $stockoutDao->listBySqlId();
        return $itemsObj;
    }

    /**
     * ����ʱ��ȡ���������嵥��Ϣ  -- ���ҳ��
     */
    function getObjInfo($id) {
        $orderDao = new model_projectmanagent_order_order ();
        $orders = $orderDao->get_d($id, array('orderequ'));
        $shipapply = array('objCode' => $orders ['orderCode'], 'customerId' => $orders ['customerId'], 'customerName' => $orders ['customerName'], 'relDocName' => $orders ['orderName'], 'reachDate' => $orders ['deliveryDate'], 'address' => $orders ['address'], 'mailAddress' => $orders ['address']);
        $shipapply ['shipproducts'] = $this->showItemAdd($orders ['orderequ']);
        return $shipapply;
    }
}