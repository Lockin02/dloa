<?php
/**
 * @author suxc
 * @Date 2011年5月6日 10:27:57
 * @version 1.0
 * @description:退料通知单物料清单信息 Model层
 */
header("Content-type: text/html; charset=gb2312");
class model_purchase_delivered_equipment extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_purchase_delivered_equ";
        $this->sql_map = "purchase/delivered/equipmentSql.php";
        parent::__construct();
    }

    /**查看退料单，物料列表
     * @param  $rows 退料物料数组
     */
    function showViewList($rows)
    {
        $str = "";
        $i = 0;
        if ($rows) {
            foreach ($rows as $key => $val) {
                $i++;
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
						<td width="15%">
							$val[productNumb]
						</td>
						<td width="15%">
							$val[productName]
						</td>
						<td width="10%">$val[pattem]</td>
						<td width="10%">
						  $val[units]
						</td>
						<td width="10%">$val[batchNum]</td>
						<td width="15%">$val[deliveredNum]</td>
						<td width="15%">$val[factNum]</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 入库时从表显示模板
     * @param  $rows 退料物料数组
     *
     */
    function showAddList($rows)
    {
        $str = "";
        $i = 0;
        if ($rows) {
            $productinfoDao = new model_stock_productinfo_productinfo();
            foreach ($rows as $key => $val) {
                $sumPrice = $val[deliveredNum] * $val[price];
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
                                    <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productNumb]"/>
                                    <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                    <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i" value=""  />
                                    <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i" value=""  />
                                    <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i" value=""  />
                                </td>
					<td>
					    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
					</td>
					<td>
					    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
					</td>
                                <td>
                                    <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort"  value="$val[pattem]" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin"  value="$val[units]" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort"  />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$val[deliveredNum]" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')"  value="$val[deliveredNum]" />
                                    <input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value="" />
									<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value="" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"   value="$val[stockName]"/>
                                    <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"   value="$val[stockId]"/>
                                    <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"  />
                                    <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" class="txtshort" value="$val[id]"  />
                                    <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  />
                                    <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"   />
                                </td>
                                <td>
                                ******
                                    <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="readOnlyTxtItem formatMoneySix" onblur="FloatMul('price$i','actNum$i','subPrice$i')"   value="$val[price]"/>
                                </td>
                                <td>
                                ******
                                    <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" value="$sumPrice"/>
                                </td>
                    <td>
                        <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort" value="{$val['warranty']}"/>
                    </td>

                            </tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /**
     * 对修改退料单时，产品清单模板
     * @param  $rows 退料物料数组
     */
    function showEditList($rows)
    {
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串


            foreach ($rows as $key => $val) {
                ++$i;
                $str .= <<<EOT
					<tr align="center" class="TableHeader">
						<td>$i</td>
						<td >
							<input type="text" value="$val[productNumb]" readOnly class="readOnlyTxtItem" name="delivered[equipment][$i][productNumb]" >
						</td>
						<td >
							<input type="text" s value="$val[productName]" readOnly class="readOnlyTxtItem" name="delivered[equipment][$i][productName]" />
							<input type="hidden" value="$val[productId]" name="delivered[equipment][$i][productId]" />
						</td>
						<td >
							<input type="text"  class="readOnlyTxtItem" value="$val[pattem]"  name="delivered[equipment][$i][pattem]"  readOnly >
						</td>
						<td  >
							<input type="text"  class="readOnlyTxtItem" value="$val[units]" name="delivered[equipment][$i][units]"   readOnly />
						</td>
						<td >
							<input type="text"  class="txtshort"  value="$val[batchNum]" name="delivered[equipment][$i][batchNum]"  />
						</td>
						<td>
							<input type="hidden" readOnly  value="$val[price]" name="delivered[equipment][$i][price]" />
							<input type="hidden" readOnly  value="$val[businessId]" name="delivered[equipment][$i][businessId]" />

							<input type="text" class="txtshort" value="$val[deliveredNum]" name="delivered[equipment][$i][deliveredNum]" onblur="checkNum(this);"/>
							<input type="hidden"   value="$val[arrivalNum]" />
						</td>
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td>

					</tr>
EOT;
            }
            return $str;

        }
    }

    /**
     * 根据退料单ID获取物料清单
     * @param  $basicId 退料通知单ID
     *
     */
    function getItemByBasicIdId_d($basicId)
    {
        $conditions = array(
            "basicId" => $basicId
        );
        return parent :: findAll($conditions);
    }

    /**
     * 更新物料的实际退料数量.
     * @param  $basicId 退料通知单ID
     * @param  $productId 物料ID
     * @param  $num 红色入库数量
     */
    function updateNumb_d($basicId, $equId, $num)
    {
        $sql = "update oa_purchase_delivered_equ c set c.factNum=c.factNum+" . $num . " where c.basicId=$basicId and c.id=$equId";
        $this->query($sql);
    }
}

?>