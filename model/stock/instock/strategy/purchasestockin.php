<?php
header("Content-type: text/html; charset=gb2312");
//引入接口
include_once WEB_TOR . 'model/stock/instock/strategy/istockin.php';

/**
 * @description: 外购入库策略
 */
class model_stock_instock_strategy_purchasestockin extends model_base implements istockin
{

    function __construct()
    {
        $this->relDocTypeArr = array(//审核调用方法
            "RSLTZD" => array(
                "name" => "收料通知单",
                "mainModel" => "model_purchase_arrival_arrival",
                "dealMainFun" => "updateInStock",
                "getApplyFun" => "getApplyInfo"
            ),
            "RTLTZD" => array(
                "name" => "退料通知单",
                "mainModel" => "model_purchase_delivered_delivered",
                "dealMainFun" => "updateInStock"
            ),
            "RCGDD" => array(
                "name" => "采购订单",
                "mainModel" => "model_purchase_contract_purchasecontract",
                "dealMainFun" => "updateInStock"
            )
        );
        $this->cancelDocTypeArr = array(//反审核调用方法
            "RSLTZD" => array(
                "name" => "收料通知单",
                "mainModel" => "model_purchase_arrival_arrival",
                "dealMainFun" => "updateInStockCancel",
                "getApplyFun" => "getApplyInfo"
            ),
            "RTLTZD" => array(
                "name" => "退料通知单",
                "mainModel" => "model_purchase_delivered_delivered",
                "dealMainFun" => "updateInStockCancel"
            ),
            "RCGDD" => array(
                "name" => "采购订单",
                "mainModel" => "model_purchase_contract_purchasecontract",
                "dealMainFun" => "updateInStockCancel"
            )
        );
    }

    /**
     * @description 入库单列表显示模板
     * @param $rows
     */
    function showList($rows)
    {
    }

    /**
     * @description 根据关联单据新增入库申请时，清单显示模板
     * @param $rows
     */
    function showItemAdd($rows)
    {
    }

    /**
     * @description 下推红色入库时，清单显示模板
     * @param $rows
     */
    function showItemAddRed($rows)
    {
        if($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach($rows as $key => $val) {
                $seNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" >
                    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行"/>
                    </td>
                    <td>
                        $seNum
                       </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtShort" value="{$val['productCode']}"/>
                        <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="{$val['productId']}"/>
                        <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"/>
                        <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="{$val['proType']}" readonly="readonly"/>
                        <input type="hidden" name="stockin[items][$i][proTypeId]" id="proTypeId$i" value="{$val['proTypeId']}"  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="{$val['k3Code']}" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="{$val['productName']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['pattern']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtMin" value="{$val['unitName']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort" value="{$val['batchNum']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort" value="{$val['storageNum']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" onfocus="exploreProTipInfo($i)" ondblclick="serialNoDeal(this,$i)"  class="txtshort" value="{$val['actNum']}" onblur="FloatMul('actNum$i','price$i','subPrice$i')"/>
                        <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
                        <input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value=""/>
                    </td>
                    <td>
                         <input type="text"   name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="{$val['inStockName']}"/>
                         <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="{$val['inStockId']}"/>
                         <input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="{$val['inStockCode']}"/>
                         <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"   value="{$val['relDocId']}"/>
                         <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  value="{$val['relDocName']}"/>
                         <input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"  value="{$val['relDocCode']}"/>
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoneySix" value="{$val['price']}"  onblur="FloatMul('price$i','actNum$i','subPrice$i')"/>
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" value="{$val['subPrice']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort" value="{$val['warranty']}"/>
                    </td>
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 修改入库单时，物料清单模板
     * @author huangzf
     */
    function showItemEdit($rows)
    {
        if($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach($rows as $key => $val) {
                $productCodeClass = "txtshort";
                $productNameClass = "txt";
                if($val['relDocId'] > 0) {
                    $productCodeClass = "readOnlyTxtShort";
                    $productNameClass = "readOnlyTxtNormal";
                }
                $seNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" >
                    <td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行"/>
                    </td>
                    <td>
                        $seNum
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="$productCodeClass" value="{$val['productCode']}"/>
                        <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="{$val['productId']}"/>
                        <input type="hidden" name="stockin[items][$i][id]" id="id$i" value="{$val['id']}"/>
                        <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i" value="{$val['serialSequence']}"/>
                        <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i" value="{$val['serialRemark']}"/>
                        <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i" value="{$val['serialnoId']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="{$val['proType']}" readonly="readonly"/>
                        <input type="hidden" name="stockin[items][$i][proTypeId]" id="proTypeId$i" value="{$val['proTypeId']}"  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="{$val['k3Code']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="$productNameClass" value="{$val['productName']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="{$val['pattern']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtMin" value="{$val['unitName']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort" value="{$val['batchNum']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort" value="{$val['storageNum']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" onfocus="exploreProTipInfo($i)" ondblclick="serialNoDeal(this,$i)"  class="txtshort" value="{$val['actNum']}" onblur="FloatMul('actNum$i','price$i','subPrice$i')"/>
                        <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value="{$val['serialnoId']}"/>
                        <input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value="{$val['serialnoName']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="{$val['inStockName']}"/>
                        <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="{$val['inStockId']}"/>
                        <input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="{$val['inStockCode']}"/>
                        <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"   value="{$val['relDocId']}"/>
                        <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  value="{$val['relDocName']}"/>
                        <input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"  value="{$val['relDocCode']}"/>
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoneySix" value="{$val['price']}"  onblur="FloatMul('price$i','actNum$i','subPrice$i')"/>
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" value="{$val['subPrice']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort" value="{$val['warranty']}"/>
                    </td>
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 修改入库单时，物料清单模板
     */
    function showItemEditPrice($rows)
    {
        if($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach($rows as $key => $val) {
                $seNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" >
                    <td>
                        $seNum
                       </td>
                    <td>
                        {$val['productCode']}
                        <input type="hidden" name="stockin[items][$i][id]" id="id$i" value="{$val['id']}"/>

                    </td>
                    <td>
                       {$val['proType']}
                    </td>
                    <td>
                        {$val['k3Code']}
                    </td>
                    <td>
                        {$val['productName']}
                    </td>
                    <td>
                        {$val['pattern']}
                    </td>
                    <td>
                        {$val['unitName']}
                    </td>
                    <td>
                        {$val['batchNum']}
                    </td>
                    <td>
                        {$val['storageNum']}
                    </td>
                    <td>
                        {$val['actNum']}
                        <input type="hidden" name="stockin[items][$i][actNum]" id="actNum$i" value="{$val['actNum']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][price]" id="price$i" class="txtmiddle formatMoneySix" value="{$val['price']}"  onblur="FloatMul('price$i','actNum$i','subPrice$i')"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" value="{$val['subPrice']}"/>
                    </td>
                     <td>
                        {$val['warranty']}
                    </td>
                    <td>
                        {$val['inStockName']}
                    </td>
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 查看入库单时，物料清单显示模板
     * @author huangzf
     */
    function showItemView($rows)
    {
        if($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val['productName'], 20);
                $productNameStr = implode("<br/>", $productNameArr);
                $sNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" class="tr_odd" >
                    <td>
                       $sNum
                    </td>
                    <td>
                       {$val['productCode']}
                    </td>
                    <td>
                       {$val['proType']}
                    </td>
                    <td>
                        {$val['k3Code']}
                    </td>
                    <td>
                        $productNameStr
                    </td>
                    <td>
                        {$val['pattern']}
                    </td>
                    <td>
                        {$val['unitName']}
                    </td>
                    <td>
                       {$val['storageNum']}
                    </td>
                    <td>
                       {$val['actNum']}
                    </td>
                    <td>
                       {$val['batchNum']}
                    </td>
                     <td>
                       <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialSequence']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号"/></a>
                    </td>
                    <td class="formatMoneySix">
                        {$val['price']}
                    </td>
                    <td class="formatMoney">
                        {$val['subPrice']}
                    </td>
                    <td>
                       {$val['warranty']}
                    </td>
                    <td>
                        {$val['inStockName']}

                    </td>
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 查看入库单时，物料清单显示模板
     * @author huangzf
     */
    function showItemPrint($rows)
    {
        if($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val['productName'], 20);
                $productNameStr = implode("<br/>", $productNameArr);
                $sNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" class="tr_odd" >
<!--                    <td>
                       $sNum
                    </td>-->
                    <td>
                       {$val['productCode']}
                       <input type="hidden" id="productCode$i" value="{$val['productCode']}">
                    </td>
<!--                    <td>
                        {$val['k3Code']}
                    </td>-->
                    <td>
                        $productNameStr
                       <input type="hidden" id="productName$i" value="{$val['productName']}">
                       <input type="hidden" id="unitName$i" value="{$val['unitName']}">
                       <input type="hidden" id="pattern$i" value="{$val['pattern']}">
                    </td>
<!--                    <td>
                        {$val['pattern']}
                    </td>
                    <td>
                        {$val['unitName']}
                    </td>
                    <td>
                       {$val['storageNum']}
                    </td>-->
                    <td>
                       {$val['actNum']}
                       <input type="hidden" id="actNum$i" value="{$val['actNum']}">
                    </td>
<!--                    <td>
                       {$val['batchNum']}
                    </td>
                     <td>
                       <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialSequence']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号"/></a>
                    </td>-->
                    <td class="formatMoneySix">
                        {$val['price']}
                    </td>
                    <td class="formatMoney">
                        {$val['subPrice']}
                    </td>
<!--                    <td>
                       {$val['warranty']}
                    </td>
                    <td>
                        {$val['inStockName']}

                    </td>-->
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 查看入库单时，物料清单显示模板 --红色单
     * @author huangzf
     */
    function showRedItemPrint($rows)
    {
        if($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach($rows as $key => $val) {
                $productNameArr = model_common_util::subWordInArray($val['productName'], 20);
                $productNameStr = implode("<br/>", $productNameArr);
                $sNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" class="tr_odd" >
<!--                    <td>
                       $sNum
                    </td>-->
                    <td>
                       {$val['productCode']}
                       <input type="hidden" id="productCode$i" value="{$val['productCode']}">
                       <input type="hidden" id="productName$i" value="{$val['productName']}">
                       <input type="hidden" id="unitName$i" value="{$val['unitName']}">
                       <input type="hidden" id="pattern$i" value="{$val['pattern']}">
                       <input type="hidden" id="actNum$i" value="-{$val['actNum']}">
                    </td>
<!--                    <td>
                        {$val['k3Code']}
                    </td>-->
                    <td>
                        $productNameStr
                    </td>
<!--                    <td>
                        {$val['pattern']}
                    </td>
                    <td>
                        {$val['unitName']}
                    </td>
                    <td>
                       {$val['storageNum']}
                    </td>-->
                    <td>
                       -{$val['actNum']}
                    </td>
<!--                    <td>
                       {$val['batchNum']}
                    </td>
                     <td>
                       <a href="index1.php?model=stock_serialno_serialno&action=toView&serialSequence={$val['serialSequence']}&serialRemark={$val['serialRemark']}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=500" title="查看序列号信息" class="thickbox"><img src="images/add_snum.png" align="absmiddle"  title="查看序列号"/></a>
                    </td>-->
                    <td class="formatMoneySix">
                        {$val['price']}
                    </td>
                    <td class="formatMoney">
                        {$val['subPrice']}
                    </td>
<!--                    <td>
                       {$val['warranty']}
                    </td>
                    <td>
                        {$val['inStockName']}
	
                    </td>-->
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 查看相关业务信息
     * @param $paramArr
     */
    function viewRelInfo($paramArr = false)
    {

    }

    /**
     *
     * 根据蓝色入库单生成红色入库单，清单显示模板
     * @param  $rows
     * @param  $istrategy
     */
    function showRelItem($rows)
    {
        if($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach($rows as $key => $val) {
                $seNum = $i + 1;
                $str .= <<<EOT
				<tr align="center" >
                    <td>
                        $seNum
                       </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="txtshort" value="{$val['productCode']}"/>
                        <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="{$val['productId']}"/>
                        <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"/>
                        <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="txt" value="{$val['productName']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="{$val['pattern']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtMin" value="{$val['unitName']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort" value="{$val['actNum']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)"  class="txtshort"  onblur="FloatMul('price$i','actNum$i','subPrice$i')"/>
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoney" value="{$val['price']}"  onblur="FloatMul('actNum$i','price$i','subPrice$i')"/>
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="{$val['inStockName']}"/>
                         <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="{$val['inStockId']}"/>
                         <input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="{$val['inStockCode']}"/>
                         <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"/>
                        <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"/>
                        <input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"/>
                    </td>
                    <td>
                        <img src="images/closeDiv.gif" onclick="delItem(this)" title="删除行"/>
                    </td>
                </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 根据入库单下推出库单时，清单显示模板 (生产领料)
     * @param  $rows
     * @param  $istrategy
     */
    function showItemAtOutStock($rows)
    {
        $str = "";
        if($rows) {
            $i = 0;
            foreach($rows as $key => $val) {
                $sNum = $i + 1;
                $str .= <<<EOT
					<tr align="center">
						<td>
							<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行"/>
						</td>
					  	<td>
					   		$sNum
					  	</td>
					   	<td>
						 	<input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]"/>
						  	<input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"/>
						</td>
                    <td>
                        <input type="text" name="stockin[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="{$val['proType']}" readonly="readonly"/>
                        <input type="hidden" name="stockin[items][$i][proTypeId]" id="proTypeId$i" value="{$val['proTypeId']}"  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="{$val['k3Code']}"/>
                    </td>
						<td>
						  	<input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]"/>
						</td>
						<td>
						   	<input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattern]"/>
						</td>
						<td>
						   	<input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin" value="$val[unitName]"/>
						</td>
						<td>
						   	<input type="text" name="stockout[items][$i][batchNum]" id="batchNum$i" class="txtshort" value="$val[batchNum]"/>
						</td>
						<td>
							<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value="$val[inStockName]"/>
							<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value="$val[inStockId]"/>
							<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value="$val[inStockCode]"/>
							<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[mainId]"/>
							<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i"/>
							<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i"/>
						</td>
						<td>
						   	<input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$val[actNum]"/>
						</td>
						<td>
							<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)" onblur="FloatMul('actOutNum$i','cost$i','subCost$i')"/>
						</td>
						<td>
							<img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
							<input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
							<input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value=""/>
						</t>
						<td>
						******
							<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoney" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="$val[price]"/>
						</td>
						<td>
						******
							<input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtShort formatMoney"/>
						</td>
						<td>
							<input type="text" name="stockout[items][$i][prodDate]" id="prodDate$i" onfocus="WdatePicker()" class="txtshort"/>
						</td>
						<td>
							<input type="text" name="stockout[items][$i][shelfLife]" id="shelfLife$i" class="txtshort"/>
						</td>
						<td>
							<input type="text" name="stockout[items][$i][validDate]" id="validDate$i" onfocus="WdatePicker()" class="txtshort"/>
						</td>
					</tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * 新增入库单时处理相关业务信息
     * @param $paramArr
     * @param $relItemArr
     */
    function dealRelInfoAtAdd($paramArr = false, $relItemArr = false)
    {
        try {
            $applyPurchArr = array();

            /*start:-----蓝色单据增加序列号、批次号台账---------*/
            if("0" == $paramArr['isRed']) {
                $serialnoDao = new model_stock_serialno_serialno();
                $batchnoDao = new model_stock_batchno_batchno();
                foreach($relItemArr as $key => $itemObj) {
                    if(!empty($itemObj['batchNum'])) {
                        $batchnoObj = array("inDocId" => $paramArr['docId'], "inDocCode" => $paramArr['docCode'], "inDocItemId" => $itemObj['id'], "productId" => $itemObj['productId'], "productName" => $itemObj['productName'], "productCode" => $itemObj['productCode'], "stockId" => $itemObj['inStockId'], "stockName" => $itemObj['inStockName'], "stockCode" => $itemObj['inStockCode'], "batchNo" => $itemObj['batchNum'], "stockNum" => $itemObj['actNum']);
                        $batchnoDao->add_d($batchnoObj);
                    }

                    //采购源单需求信息
                    $applyPurchObj = array();
                    if(!empty($itemObj['relDocId'])) {
                        $relDocModelInfo = $this->relDocTypeArr[$paramArr['relDocType']];
                        if($relDocModelInfo) {
                            $relDocDao = new $relDocModelInfo['mainModel']();
                            $relGetApplyFun = $relDocModelInfo['getApplyFun'];
                            $applyPurchObj = $relDocDao->$relGetApplyFun($itemObj['relDocId']);
                            if(isset($applyPurchObj['applyType']) && isset($applyPurchObj['applyId'])) {
                                $applyPurchArr[$itemObj['relDocId']] = $applyPurchObj;
                            }
                        }
                    }

                    if(!empty($itemObj['serialSequence'])) {
                        $serialObj = array("inDocId" => $paramArr['docId'], "inDocCode" => $paramArr['docCode'],
                            "inDocItemId" => $itemObj['id'], "productId" => $itemObj['productId'],
                            "productName" => $itemObj['productName'], "productCode" => $itemObj['productCode'],
                            "pattern" => $itemObj['pattern'], "stockId" => $itemObj['inStockId'],
                            "stockName" => $itemObj['inStockName'], "stockCode" => $itemObj['inStockCode'],
                            "seqStatus" => "0", "batchNo" => $itemObj['batchNum'], "relDocCode" => $paramArr['relDocCode'],
                            "relDocType" => $paramArr['relDocType']);

                        if(isset($applyPurchObj['applyType']) && isset($applyPurchObj['applyId'])) {
                            $serialObj['relDocType'] = $applyPurchObj['applyType'];
                            $serialObj['relDocId'] = $applyPurchObj['applyId'];
                            $serialObj['relDocCode'] = $applyPurchObj['applyCode'];
                        }

                        $serialnoDao->autoDeal_d($serialObj, $itemObj['serialSequence'], $itemObj['serialRemark'], 1);
                    }
                }
            }
            /*end:-----蓝色单据增加序列号、批次号台账---------*/

            //已审核的入库单,进入仓库
            if($paramArr['docStatus'] == "YSH") {
                $inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
                $lockDao = new model_stock_lock_lock(); //库存锁DAO
                foreach($relItemArr as $key => $value) {
                    //更新库存
                    $stockParamArr = array("stockId" => $value['inStockId'], "productId" => $value['productId']);
                    if($paramArr['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value['actNum'], "instock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value['actNum'], "outstock");
                        //清理库存物料对应序列号
                        $serialnoDao = new model_stock_serialno_serialno();
                        if(!empty($value['serialnoId'])) {
                            $seStr = $value['serialnoId'];
                            $serialnoDao->query("update oa_stock_product_serialno set seqStatus=1 where id in($seStr)");
                        }
                    }
                    //处理关联单据
                    $relDocModelInfo = $this->relDocTypeArr[$paramArr['relDocType']];
                    if($relDocModelInfo && $value['relDocId']) {
                        $relDocDao = new $relDocModelInfo['mainModel']();
                        $relDocDaoFun = $relDocModelInfo['dealMainFun'];
                        if($paramArr['isRed'] == "0") { //蓝色
                            $relDocDao->$relDocDaoFun($paramArr['relDocId'], $value['relDocId'], $value['productId'], $value['actNum'], $paramArr['auditDate']);

                            if(isset($applyPurchArr[$value['relDocId']])) { //存在关联采购需求,进行入库锁定
                                $applyObj = $applyPurchArr[$value['relDocId']];
                                $applyObj['relDocId'] = $value['relDocId'];
                                $productObj = array("productId" => $value['productId'], "productCode" => $value['productCode'], "productName" => $value['productName'], "lockNum" => $value['actNum'], "inStockDocId" => $paramArr['docId']);
                                $stockObj = array("stockId" => $value['inStockId'], "stockCode" => $value['inStockCode'], "stockName" => $value['inStockName']);
                                $lockDao->stockinLock_d($applyObj, $productObj, $stockObj);
                            }

                        } else {
                            $relDocDao->$relDocDaoFun($paramArr['relDocId'], $value['relDocId'], $value['productId'], -$value['actNum'], $paramArr['auditDate']);
                        }
                    }
                }
            }
            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * 修改入库单时处理相关业务信息
     * @param  $paramArr
     * @param  $relItemArr
     */
    function dealRelInfoAtEdit($paramArr = false, $relItemArr = false)
    {
        try {
            $applyPurchArr = array();
            /*start:-----蓝色单据增加序列号、批次号台账---------*/
            if("0" == $paramArr['isRed']) {
                $serialnoDao = new model_stock_serialno_serialno();
                $batchnoDao = new model_stock_batchno_batchno();
                foreach($relItemArr as $key => $itemObj) {
                    $deleteCon = array("inDocItemId" => $itemObj['id']);
                    if(!empty($itemObj['batchNum'])) {
                        $batchnoDao->delete($deleteCon);
                        $batchnoObj = array("inDocId" => $paramArr['docId'], "inDocCode" => $paramArr['docCode'], "inDocItemId" => $itemObj['id'], "productId" => $itemObj['productId'], "productName" => $itemObj['productName'], "productCode" => $itemObj['productCode'], "stockId" => $itemObj['inStockId'], "stockName" => $itemObj['inStockName'], "stockCode" => $itemObj['inStockCode'], "batchNo" => $itemObj['batchNum'], "stockNum" => $itemObj['actNum']);
                        $batchnoDao->add_d($batchnoObj);
                    }

                    //采购源单需求信息
                    $applyPurchObj = array();
                    if(!empty($itemObj['relDocId'])) {
                        $relDocModelInfo = $this->relDocTypeArr[$paramArr['relDocType']];
                        if($relDocModelInfo) {
                            $relDocDao = new $relDocModelInfo['mainModel']();
                            $relGetApplyFun = $relDocModelInfo['getApplyFun'];
                            $applyPurchObj = $relDocDao->$relGetApplyFun($itemObj['relDocId']);
                            if(isset($applyPurchObj['applyType']) && isset($applyPurchObj['applyId'])) {
                                $applyPurchArr[$itemObj['relDocId']] = $applyPurchObj;
                            }
                        }
                    }

                    if(!empty($itemObj['serialSequence'])) {
                        $serialObj = array("inDocId" => $paramArr['docId'], "inDocCode" => $paramArr['docCode'],
                            "inDocItemId" => $itemObj['id'], "productId" => $itemObj['productId'],
                            "productName" => $itemObj['productName'], "productCode" => $itemObj['productCode'],
                            "pattern" => $itemObj['pattern'], "stockId" => $itemObj['inStockId'],
                            "stockName" => $itemObj['inStockName'], "stockCode" => $itemObj['inStockCode'],
                            "seqStatus" => "0", "batchNo" => $itemObj['batchNum'], "relDocCode" => $paramArr['relDocCode'],
                            "relDocType" => $paramArr['relDocType']);

                        if(isset($applyPurchObj['applyType']) && isset($applyPurchObj['applyId'])) {
                            $serialObj['relDocType'] = $applyPurchObj['applyType'];
                            $serialObj['relDocId'] = $applyPurchObj['applyId'];
                            $serialObj['relDocCode'] = $applyPurchObj['applyCode'];
                        }

                        $serialnoDao->autoDeal_d($serialObj, $itemObj['serialSequence'], $itemObj['serialRemark'], 1);
                    }
                }
            }
            /*end:-----蓝色单据增加序列号、批次号台账---------*/

            //已审核的入库单,进入仓库
            if($paramArr['docStatus'] == "YSH") {
                $inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
                $lockDao = new model_stock_lock_lock(); //库存锁DAO
                foreach($relItemArr as $key => $value) {
                    //更新库存
                    $stockParamArr = array("stockId" => $value['inStockId'], "productId" => $value['productId']);
                    if($paramArr['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value['actNum'], "instock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value['actNum'], "outstock");
                        //清理库存物料对应序列号
                        $serialnoDao = new model_stock_serialno_serialno();
                        if(!empty($value['serialnoId'])) {
                            $seStr = $value['serialnoId'];
                            $serialnoDao->query("update oa_stock_product_serialno set seqStatus=1 where id in($seStr)");
                        }
                    }

                    //处理关联单据
                    $relDocModelInfo = $this->relDocTypeArr[$paramArr['relDocType']];
                    if($relDocModelInfo && $value['relDocId']) {
                        $relDocDao = new $relDocModelInfo['mainModel']();
                        $relDocDaoFun = $relDocModelInfo['dealMainFun'];
                        if($paramArr['isRed'] == "0") { //蓝色
                            $relDocDao->$relDocDaoFun($paramArr['relDocId'], $value['relDocId'], $value['productId'], $value['actNum'], $paramArr['auditDate']);

                            if(isset($applyPurchArr[$value['relDocId']])) { //存在关联采购需求,进行入库锁定
                                $applyObj = $applyPurchArr[$value['relDocId']];
                                $applyObj['relDocId'] = $value['relDocId'];
                                $productObj = array("productId" => $value['productId'], "productCode" => $value['productCode'], "productName" => $value['productName'], "lockNum" => $value['actNum'], "inStockDocId" => $paramArr['docId']);
                                $stockObj = array("stockId" => $value['inStockId'], "stockCode" => $value['inStockCode'], "stockName" => $value['inStockName']);
                                $lockDao->stockinLock_d($applyObj, $productObj, $stockObj);
                            }
                        } else {
                            $relDocDao->$relDocDaoFun($paramArr['relDocId'], $value['relDocId'], $value['productId'], -$value['actNum'], $paramArr['auditDate']);
                        }
                    }
                }
            }
            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * 反审核入库单
     * @param $id
     */
    function cancelAudit($stockinObj)
    {
        try {
            if($stockinObj['docStatus'] == "YSH") { //确认单据时已审核入库
                $inventoryDao = new model_stock_inventoryinfo_inventoryinfo(); //库存DAO
                $lockDao = new model_stock_lock_lock(); //库存锁DAO
                $serialnoDao = new model_stock_serialno_serialno(); //序列号DAO

                foreach($stockinObj['items'] as $key => $value) {
                    //还原库存
                    $stockParamArr = array("stockId" => $value['inStockId'], "productId" => $value['productId']);
                    if($stockinObj['isRed'] == "0") { //蓝色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value['actNum'], "outstock");
                    } else { //红色
                        $inventoryDao->updateInTimeInfo($stockParamArr, $value['actNum'], "instock");
                    }

                    //蓝色入库单物料序列号状态不变,关联序列号源单信息变为空;红色入库单序列号从已出库变为库存中
                    if(!empty($value['serialnoId'])) {
                        $sequenceId = $value['serialnoId'];
                        $sequencObj = array("seqStatus" => "0");
                        if($stockinObj['isRed'] == "1") {
                            $seqStatusVal = "0";
                        } else {
                            $sequencObj['relDocType'] = "";
                            $sequencObj['relDocId'] = "";
                            $sequencObj['relDocCode'] = "";
                        }

                        $serialnoDao->update("id in($sequenceId)", $sequencObj);
                    }
                    //处理并还原关联单据
                    if(!empty($stockinObj['relDocType'])) {
                        $relDocModelInfo = $this->cancelDocTypeArr[$stockinObj['relDocType']];
                        if($relDocModelInfo && $value['relDocId']) {
                            $relDocDao = new $relDocModelInfo['mainModel']();
                            $relDocDealMainFun = $relDocModelInfo['dealMainFun'];

                            if($stockinObj['isRed'] == "0") { //蓝色
                                $relDocDao->$relDocDealMainFun($stockinObj['relDocId'], $value['relDocId'], $value['productId'], $value['actNum'], $stockinObj['auditDate']);
                                $relGetApplyFun = $relDocModelInfo['getApplyFun'];
                                $applyPurchObj = $relDocDao->$relGetApplyFun($value['relDocId']);

                                if(isset($applyPurchObj['applyType']) && isset($applyPurchObj['applyId'])) {
                                    $productObj = array("productId" => $value['productId'], "productCode" => $value['productCode'], "productName" => $value['productName'], "lockNum" => $value['actNum'], "inStockDocId" => $stockinObj['id']);
                                    $stockObj = array("stockId" => $value['inStockId'], "stockCode" => $value['inStockCode'], "stockName" => $value['inStockName']);
                                    $lockDao->releaseLockByCancelAudit($applyPurchObj, $productObj, $stockObj);
                                }
                            } else {
                                $relDocDao->$relDocDealMainFun($stockinObj['relDocId'], $value['relDocId'], $value['productId'], -$value['actNum'], $stockinObj['auditDate']);
                            }
                        }
                    }
                }
            }
            return true;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * 根据基本信息ID获取物料清单信息(包括序列号及批次号 )
     */
    function getItem($id)
    {
        $stockinitemDao = new model_stock_instock_stockinitem();
        $serialnoDao = new model_stock_serialno_serialno();
        $batchnoDao = new model_stock_batchno_batchno();
        $stockinItemArr = $stockinitemDao->getItemByMainId($id);
        $itemResult = array();
        if(is_array($stockinItemArr)) {
            foreach($stockinItemArr as $key => $itemObj) {
                $batchnoObj = $batchnoDao->findByInItemId($itemObj['id']);
                $serialnoObj = $serialnoDao->findByInItemId($itemObj['id']);
                //批次号
                if(is_array($batchnoObj)) {
                    $itemObj['batchNo'] = $batchnoObj[0]['batchNo'];

                } else {
                    $itemObj['batchNo'] = "";
                }
                //序列号
                if(is_array($serialnoObj)) {
                    $sequenceStr = "";
                    $remarkStr = "";
                    $serialnoIdStr = "";
                    for($i = 0; $i < count($serialnoObj); $i++) {
                        $sequenceStr .= $serialnoObj[$i]['sequence'];
                        $remarkStr .= $serialnoObj[$i]['remark'];
                        $serialnoIdStr .= $serialnoObj[$i]['id'];
                        if($i + 1 < count($serialnoObj)) {
                            $sequenceStr .= ",";
                            $remarkStr .= ",";
                            $serialnoIdStr .= ",";
                        }
                    }
                    $itemObj['serialSequence'] = $sequenceStr;
                    $itemObj['serialRemark'] = $remarkStr;
                    $itemObj['serialnoId'] = $serialnoIdStr;
                    $itemObj['serialnoName'] = $sequenceStr;
                } else {
                    $itemObj['serialSequence'] = "";
                    $itemObj['serialRemark'] = "";
                }
                array_push($itemResult, $itemObj);
            }
        }
        return $itemResult;
    }
}