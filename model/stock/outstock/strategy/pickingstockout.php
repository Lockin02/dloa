<?php
header("Content-type: text/html; charset=gb2312");

//����ӿ�
include_once WEB_TOR . 'model/stock/outstock/strategy/istockout.php';

/**
 * @author huangzf
 * @Date 2011��3��12�� 17:03:12
 * @version 1.0
 * @description:���ϳ������
 */
class model_stock_outstock_strategy_pickingstockout implements istockout
{

    function __construct() {

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
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
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
     * �޸����۳��ⵥʱ ������Ϣ��ʾģ��
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
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
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
							<option value="stockBalancePrice">�ڳ��۸�</option>
							<option value="stockInPrice">��������</option>
							<option value="stockOutPrice">���³����</option>
							<option value="purchasePrice">���²ɹ���</option>
						</select>
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
                            <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="�鿴���к���Ϣ" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="�鿴���к�" /></a>
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
                            <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="�鿴���к���Ϣ" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="�鿴���к�" /></a>
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
                            <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialnoName']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="�鿴���к���Ϣ" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="�鿴���к�" /></a>
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
     * �����������ϵ����ɺ�ɫ���ϳ��ⵥ���嵥��ʾģ��
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
                            <img src="images/closeDiv.gif" onclick="delItem(this);" title="ɾ����" />
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

                if (empty ($value ['relDocId'])) {
                    //���¿��
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                } else {
                    //���¿��
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                }
            }

			//�����������
			if ($paramArr['relDocType'] == 'LLCKSCRWD' && $paramArr['relDocId']) { //��������(��������)
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
     * �޸ĳ��ⵥʱ�������ҵ��
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false) {
        //����˵ĳ��ⵥ,����ֿ�
        if ($paramArr ['docStatus'] == "YSH") {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
            foreach ($relItemArr as $key => $value) {

                if (empty ($value ['relDocId'])) {
                    //���¿��
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                } else {
                    //���¿��
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($paramArr ['isRed'] == "0") { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
                    } else { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    }
                }
            }

			//�����������
			if ($paramArr['relDocType'] == 'LLCKSCRWD' && $paramArr['relDocId']) { //��������(��������)
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
     * ����˳��ⵥ
     * @param $stockoutObj
     */
    function cancelAudit($stockoutObj) {
        try {
            if ($stockoutObj ['docStatus'] == "YSH") { //ȷ�ϵ��������״̬
                $inventoryDao = new model_stock_inventoryinfo_inventoryinfo (); //���DAO
                $serialnoDao = new model_stock_serialno_serialno (); //���к�DAO
                foreach ($stockoutObj ['items'] as $key => $value) {
                    //��ԭ���
                    $stockParamArr = array("stockId" => $value ['stockId'], "productId" => $value ['productId']);
                    if ($stockoutObj ['isRed'] == "0") { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "instock");
                    } else { //��ɫ
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value ['actOutNum'], "outstock");
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
                
                //�����������
                if ($stockoutObj['relDocType'] == 'LLCKSCRWD' && $stockoutObj['relDocId']) { //��������(��������)
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
     * ��ȡ���ⵥ�����嵥��ϸ��Ϣ
     * @author huangzf
     */
    function getItem($mainId) {
        $stockoutDao = new model_stock_outstock_stockoutitem ();
        $stockoutDao->searchArr ['mainId'] = $mainId;
        $itemsObj = $stockoutDao->listBySqlId();
        return $itemsObj;
    }
}