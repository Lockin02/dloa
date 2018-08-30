<?php
/**
 * @author Administrator
 * @Date 2011年5月4日 21:40:04
 * @version 1.0
 * @description:收料通知单物料清单信息 Model层
 */
header("Content-type: text/html; charset=gb2312");
class model_purchase_arrival_equipment extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_purchase_arrival_equ";
        $this->sql_map = "purchase/arrival/equipmentSql.php";
        parent::__construct();
    }

    /**查看收料单，物料列表
     * @param  $rows 收料物料信息数组
     */
    function showViewList($rows)
    {
        $datadictDao = new model_system_datadict_datadict ();
        $str = "";
        $i = 0;
        if ($rows) {
            foreach ($rows as $key => $val) {
                $val['checkType'] = $datadictDao->getDataNameByCode($val['checkType']);
                //质检信息显示
                if ($val['qualityCode'] == 'ZJSXCG') {
                    $qualityInfo = <<<EOT
						<a href="javascript:void(0)" onclick="showOpenWin('?model=produce_quality_qualityapply&action=searchQuality&relDocItemId=$val[id]');">$val[qualityPassNum]</a>
EOT;
                } else {
                    $qualityInfo = $val['qualityPassNum'];
                }

                $i++;
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[sequence]
						</td>
						<td>
							$val[productName]
						</td>
						<td >$val[pattem]</td>
						<td >$val[batchNum]</td>
						<td>
						  $val[units]
						</td>
						<td>$val[arrivalNum]</td>
						<td>$val[storageNum]</td>
						<td >
						  $val[month]
						</td>
						<td >$val[arrivalDate]</td>
						<td>$qualityInfo</td>
						<td>$val[qualityName]</td>
						<td>$val[checkType]</td>
					</tr>
EOT;
            }
        }
        return $str;
    }
    /**查看收料单，物料列表
     * @param  $rows 收料物料信息数组
     */
    function showCloseViewList($rows)
    {
        $datadictDao = new model_system_datadict_datadict ();
        $str = "";
        $i = 0;
        if ($rows) {
            $ualityereportequitemDao=new model_produce_quality_qualityereportequitem();
            $qualityapplyitemDao=new model_produce_quality_qualityapplyitem();
            foreach ($rows as $key => $val) {
                $val['checkType'] = $datadictDao->getDataNameByCode($val['checkType']);
                $applyItemRow=$qualityapplyitemDao->getApplyItem_d($val['id']);
                if(is_array($applyItemRow)&&$applyItemRow['status']==0){
                    $qualitedRate='100%';
                }else{
                    $rateRow=$ualityereportequitemDao->getQualifiedRate_d($val['id'],'ZJSQYDSL');
                    if(is_array($rateRow)){
                        $qualitedRate=$rateRow[0]['qualitedRate']."%";
                    }else{
                        $qualitedRate='';
                    }
                }
                if($qualitedRate!=''){
                    $qualitedRate = <<<EOT
						<a href="javascript:void(0)" onclick="showOpenWin('?model=produce_quality_qualitytask&action=toTaskReportTab&sourceId=$val[id]');">$qualitedRate</a>
EOT;
                }

                //质检信息显示
                if ($val['qualityCode'] == 'ZJSXCG') {
                    $qualityInfo = <<<EOT
						<a href="javascript:void(0)" onclick="showOpenWin('?model=produce_quality_qualityapply&action=searchQuality&relDocItemId=$val[id]');">$val[qualityPassNum]</a>
EOT;
                } else {
                    $qualityInfo = $val['qualityPassNum'];
                }

                $i++;
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[sequence]
						</td>
						<td>
							$val[productName]
						</td>
						<td >$val[pattem]</td>
						<td >$val[batchNum]</td>
						<td>
						  $val[units]
						</td>
						<td>$val[arrivalNum]</td>
						<td>$val[storageNum]</td>
						<td >
						  $val[month]
						</td>
						<td >$val[arrivalDate]</td>
						<td>$qualityInfo</td>
						<td>$qualitedRate</td>
						<td>$val[qualityName]</td>
						<td>$val[checkType]</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**查看收料单，物料列表
     * @param  $rows 收料物料信息数组
     */
    function showArrivalList($rows)
    {
        $datadictDao = new model_system_datadict_datadict ();
        $str = "";
        $i = 0;
        $arrivalNum = 0;
        $storageNum = 0;
        if ($rows) {
            foreach ($rows as $key => $val) {
                $val['checkType'] = $datadictDao->getDataNameByCode($val['checkType']);
                $i++;
                $arrivalNum = $arrivalNum + $val[arrivalNum];
                $storageNum = $storageNum + $val[storageNum];
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[arrivalCode]
						</td>
						<td >$val[batchNum]</td>
						<td>
						  $val[units]
						</td>
						<td>$val[arrivalNum]</td>
						<td>$val[storageNum]</td>
						<td >
						  $val[month]
						</td>
						<td >$val[arrivalDate]</td>
						<td>$val[checkType]</td>
					</tr>
EOT;
            }
            $str .= <<<EOT
					<tr class="tr_count">
					    <td>合计</td>
						<td colspan="3"></td>
						<td><b>$arrivalNum</b></td>
						<td><b>$storageNum</b></td>
						<td colspan="3"></td>
					</tr>
EOT;
        } else {
            $str = "<tr><td colspan='9'>该物料暂无相关收料信息</td></tr>";
        }
        return $str;
    }

    /**
     * 编辑收料通知单
     * @param  $rows 收料物料信息数组
     */
    function showEditList($rows)
    {
        $datadictDao = new model_system_datadict_datadict ();
        $str = "";
        $i = 0;
        if ($rows) {
            foreach ($rows as $key => $val) {
                $num = $i + 1;
                $checkType = $datadictDao->getDataNameByCode($val['checkType']);
                if ($val['qualityCode'] == 'ZJSXCG' && $val['checkType'] != 'ZJFSMJ') {
                    $arrivalNumStr = '<input type="text"  class="readOnlyTxtItem"  value="' . $val['arrivalNum'] . '" name="arrival[equipment][$i][arrivalNum]" readonly/>';
                } else {
                    $arrivalNumStr = '<input type="text"  class="txtshort"  value="' . $val['arrivalNum'] . '" name="arrival[equipment][$i][arrivalNum]" />';
                }
                $str .= <<<EOT
					<tr>
						<td>$num</td>
						<td>
							<input type="hidden"  class="readOnlyTxtItem" value="$val[id]" readOnly  name="arrival[equipment][$i][id]" />
							<input type="text"  class="readOnlyTxtItem" value="$val[sequence]" readOnly  name="arrival[equipment][$i][sequence]" />
						</td>
						<td>
							<input type="text" value="$val[productName]" readOnly  class="readOnlyTxtItem" name="arrival[equipment][$i][productName]" />
							<input type="hidden" value="$val[productId]" name="arrival[equipment][$i][productId]" />
						</td>
						<td>
							<input type="text"  class="readOnlyTxtItem" value="$val[pattem]"  name="arrival[equipment][$i][pattem]"  readOnly>
						</td>
						<td >
							<input type="text" id=""   class="readOnlyTxtMin" value="$val[units]" name="arrival[equipment][$i][units]"  readOnly/>
						</td>
						<td >
							<input type="text"  class="txtshort" value="$val[batchNum]" name="arrival[equipment][$i][batchNum]" >
						</td>
						<td>
							<input type="hidden" readOnly  value="$val[price]" name="arrival[equipment][$i][price]" />
							<input type="hidden" readOnly  value="$val[contractId]" name="arrival[equipment][$i][contractId]" />
							<input type="hidden"   value="$val[planAssType]" readOnly class="readOnlyTxt" name="arrival[equipment][$i][purchType]" >

							$arrivalNumStr
							<input type="hidden"  name="arrival[equipment][$i][oldArrivalNum]" value="$val[arrivalNum]" />
						</td>
						<td>
							<select id="month$i" class="txtshort" name="arrival[equipment][$i][month]">
								<SCRIPT language=JavaScript>
									for(i=1;i<13;i++){
										jQuery("#month$i").append('<option id="month$i'+i+'">'+i+'</option>');
										if(i==$val[month]){jQuery("#month$i"+i).attr("SELECTED","SELECTED")}
									}
								</SCRIPT>
							</select>
						</td>
						<td>
							<input type="text" class="txtshort"  onfocus="WdatePicker()" value="$val[arrivalDate]"  name="arrival[equipment][$i][arrivalDate]" />
						</td>
						<td >
							<input type="text" id=""   class="readOnlyTxtShort" value="$val[qualityName]" name=""  readOnly/>
						</td>
						<td >
							<input type="text" id=""   class="readOnlyTxtShort" value="$checkType" name=""  readOnly/>
						</td>
						<!--
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td> -->

					</tr>
EOT;

                $i++;
            }
        }
        return $str;

    }

    /**
     * 编辑收料通知单
     * @param  $rows 收料物料信息数组
     */
    function showAssetList($rows)
    {
        $datadictDao = new model_system_datadict_datadict ();
        $str = "";
        $i = 0;
        if ($rows) {
            foreach ($rows as $key => $val) {
                $val['checkType'] = $datadictDao->getDataNameByCode($val['checkType']);
                $storageNum = $val['arrivalNum'] - $val['storageNum'];
                $num = $i + 1;
                $str .= <<<EOT
					<tr>
						<td>$num</td>
						<td>
							<input type="hidden"  class="readOnlyTxtItem" value="$val[id]" readOnly  name="arrival[equipment][$i][id]" />
							<input type="text" value="$val[productName]" readOnly  class="readOnlyTxtItem"  />
						</td>
						<td>
							<input type="text"  class="readOnlyTxtItem" value="$val[pattem]"  readOnly>
						</td>
						<td >
							<input type="text" id=""   class="readOnlyTxtItem" value="$val[units]"   readOnly/>
						<td >
							<input type="text"  class="readOnlyTxtShort" value="$val[batchNum]"  >
						</td>
						<td>
							<input type="hidden" readOnly  value="$val[contractId]" name="arrival[equipment][$i][contractId]" />

							<input type="text"  class="readOnlyTxtShort"  value="$val[arrivalNum]"  />
						</td>
						<td>
							<input type="text"  class="txtshort" id="storageNum$i" value="$storageNum" name="arrival[equipment][$i][storageNum]"  onblur="checkNum(this);"/>
							<input type="hidden"  class="txtshort"  value="$storageNum"  />
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$val[arrivalDate]"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$val[checkType]"/>
						</td>
						<!--
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td> -->

					</tr>
EOT;

                $i++;
            }
        }
        return $str;

    }

    /**
     * 入库时从表显示模板
     * @param  $rows 收料物料信息数组
     *
     */
    function showAddList($rows)
    {
        $str = "";
        $i = 0;
        if ($rows) {
            $productinfoDao = new model_stock_productinfo_productinfo();
            foreach ($rows as $key => $val) {
                if (($val['qualityCode'] != 'ZJSXCG') || ($val['qualityCode'] == 'ZJSXCG' && $val['qualityPassNum'] - $val['storageNum'] > 0)) {
                    $sNum = $i + 1;
                    if ($val['qualityCode'] == 'ZJSXCG') {
                        $storageNum = $val['qualityPassNum'] - $val['storageNum'];
                    } else {
                        $storageNum = $val['arrivalNum'] - $val['storageNum'];
                    }
                    $proType="";
                    $typeRow=$productinfoDao->getParentType($val['productId']);
                    if(!empty($typeRow)){
                        $proType=$typeRow['proType'];
                    }

                    $subCost = $val['price'] * $storageNum;
                    $str .= <<<EOT
					    <tr align="center">
								<td>
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
			                    </td>
                                <td>
                                    $sNum
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[sequence]" readonly="readonly"/>
                                    <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                    <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                                    <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"   />
                                    <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i"  />
                                </td>
                                <td>
                                    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[pattem]" readonly/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort"  value="$val[units]" readonly/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort"  />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$storageNum" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')"  value="$storageNum" />
									<input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"   />
									<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  />
                                </td>
                                <td>
                                    <input type="text" id="qualityPassNum$i" class="readOnlyTxtShort"  value="$val[qualityPassNum]" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"  value="$val[stockName]" />
                                    <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"  value="$val[stockId]" />
                                    <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"  value="$val[stockCode]" />
                                    <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
                                    <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  />
                                    <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"  value="$val[arrivalCode]" />
                                </td>
                                <td>
                                    ******
                                    <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="readOnlyTxtShort formatMoneySix" readonly="readonly" onblur="FloatMul('price$i','actNum$i','subPrice$i')" value="$val[price]"/>
                                </td>
                                <td>
                                    ******
                                    <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" readonly="readonly" value="$subCost" />
                                </td>
                            </tr>
EOT;
                    $i++;

                }
            }
        }
        return $str;
    }

    /**
     * 入库时从表显示模板
     * @param  $rows 收料物料信息数组
     *
     */
    function showAddListJson($rows)
    {
        $str = "";
        $i = 0;
        if ($rows) {
            $productinfoDao = new model_stock_productinfo_productinfo();
            foreach ($rows as $key => $val) {
                if (($val['qualityCode'] != 'ZJSXCG') || ($val['qualityCode'] == 'ZJSXCG' && $val['qualityPassNum'] - $val['storageNum'] > 0)) {
                    $sNum = $i + 1;
                    if ($val['qualityCode'] == 'ZJSXCG') {
                        $storageNum = $val['qualityPassNum'] - $val['storageNum'];
                    } else {
                        $storageNum = $val['arrivalNum'] - $val['storageNum'];
                    }
                    $proType="";
                    $typeRow=$productinfoDao->getParentType($val['productId']);
                    if(!empty($typeRow)){
                        $proType=$typeRow['proType'];
                    }

                    $subCost = $val['price'] * $storageNum;
                    $str .= <<<EOT
					    <tr align="center">
								<td>
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
			                    </td>
                                <td>
                                    $sNum
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[sequence]" readonly="readonly"/>
                                    <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                    <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                                    <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"   />
                                    <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i"  />
                                </td>
                                <td>
                                    <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal"  value="$val[productName]" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem"  value="$val[pattem]" readonly/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtShort"  value="$val[units]" readonly/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort"  />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$storageNum" readonly="readonly"/>
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort"  onblur="FloatMul('actNum$i','price$i','subPrice$i')"  value="$storageNum" />
									<input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"   />
									<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"  value="$val[stockName]" />
                                    <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"  value="$val[stockId]" />
                                    <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"  value="$val[stockCode]" />
                                    <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
                                    <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  />
                                    <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"  value="$val[arrivalCode]" />
                                </td>
                                <td>
                                ******
                                    <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="readOnlyTxtShort formatMoneySix" readonly="readonly" onblur="FloatMul('price$i','actNum$i','subPrice$i')" value="$val[price]"/>
                                </td>
                                <td>
                                ******
                                    <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" readonly="readonly" value="$subCost" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][warranty]" id="warranty$i" class="readOnlyTxtShort" value="{$val['warranty']}"/>
                                </td>
                            </tr>
EOT;
                    $i++;

                }
            }
        }
        return $str;
    }

    /**
     * @author 显示模板
     *
     */
    function showItemModel($listEqu)
    {
        $str = "";
        $i = 0;
        $sumNumb = 0;
        $sumMoney = 0.00;
        if ($listEqu) {
            foreach ($listEqu as $key => $val) {
                $unionNum = $val[arrivalNum] - $val[storageNum];
                $allMoney = $val[arrivalNum] * $val[price];
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
						<td>
	                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
	                    </td>
					    <td>$i</td>
					    <td>$val[sequence]</td>
						<td>
						<input type="hidden" name="stockin[arrivalItems][$i][productId]" value="$val[productId]"/>
						<input type="hidden" name="stockin[arrivalItems][$i][id]" value="$val[id]"/>
						<input type="hidden" name="stockin[arrivalItems][$i][price]" value="$val[price]"/>
							$val[productName]
						</td>
						<td>$val[pattem]</td>
						<td>$val[units]</td>
						<td>
							$val[arrivalNum]
						</td>
						<td>
							$val[storageNum]
						</td>
						<td class="formatMoney">
							$val[price]
						</td>
						<td class="formatMoney">
							$allMoney
						</td>
						<td>
							<input type="text" class="txtshort" name="stockin[arrivalItems][$i][unionNum]" value="$unionNum"/>
						</td>
					</tr>
EOT;
            }
        } else {
            $str = "<tr><td colspan='15'>无相关物料信息</td></tr>";
        }
        return $str;

    }

    /**
     * 根据收料单ID获取物料清单
     * @param  $basicId 收料通知单ID
     *
     */
    function getItemByBasicIdId_d($basicId)
    {
        $conditions = array(
            "arrivalId" => $basicId
        );
        return parent :: findAll($conditions);
    }

    /**
     * 根据收料单ID获取物料清单（未完成入库）
     * @param  $basicId 收料通知单ID
     *
     */
    function getItemByBasicIdUnstock_d($basicId)
    {
        $this->searchArr = array('arrivalId' => $basicId,
            "unstrock" => "unstrock");
        return $this->listBySqlId('select_default');
    }

    /**
     * 根据收料单ID获取物料清单（可入库物料）
     * @param  $basicId 收料通知单ID
     *
     */
    function getItemByBasicIdStock_d($basicId)
    {
        $this->searchArr = array('arrivalId' => $basicId);
        return $this->listBySqlId('select_stock');
    }


    /**
     * 根据收料单ID获取物料ID
     * @param  $basicId 收料通知单ID
     * @param  $productId 物料Id
     *
     */
    function getItemIDByBasicIdId_d($basicId, $productId)
    {
        $conditions = array(
            "arrivalId" => $basicId,
            "productId" => $productId,
        );
        return parent :: findAll($conditions);
    }

    /**
     * 根据采购订单物料ID获取收料物料信息
     * @param  $contractEquId 采购订单物料ID
     *
     */
    function getItemByContractEquId_d($contractEquId)
    {
        $conditions = array(
            "contractId" => $contractEquId
        );
        return parent :: findAll($conditions);
    }

    /**
     * 更新物料的入库数量.
     * @param  $arrivalId 收料通知单ID
     * @param  $productId 物料Id
     * @param  $proNum 入库数量
     */
    function updateNumb_d($arrivalId, $equId, $proNum)
    {
        $sql = "update oa_purchase_arrival_equ c set c.storageNum=(c.storageNum+" . $proNum . ") where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新物料的质检数量.
     * @param  $arrivalId 收料通知单ID
     * @param  $productId 物料Id
     * @param  $proNum 质检数量
     */
    //update chenrf add 质检完成时间
    function editQualityInfo($arrivalId, $equId, $proNum)
    {
        $completionTime = date('Y-m-d H:i:s'); //质检完成时间
        $sql = "update oa_purchase_arrival_equ c set c.qualityPassNum=(c.qualityPassNum+$proNum) ,c.completionTime='{$completionTime}' where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新物料的收料数量 - 用于质检退回
     * @param  $arrivalId 收料通知单ID
     * @param  $productId 物料Id
     * @param  $proNum 质检数量
     */
    //update chenrf add 质检完成时间
    function editQualityBackInfo($arrivalId, $equId, $proNum)
    {
        $completionTime = date('Y-m-d H:i:s'); //质检完成时间
        $sql = "update oa_purchase_arrival_equ c set c.arrivalNum=(c.arrivalNum - " . $proNum . ") ,c.completionTime='{$completionTime}' where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新物料的质检数量. - 用于质检让步接收
     * @param  $arrivalId 收料通知单ID
     * @param  $productId 物料Id
     * @param  $proNum 质检数量
     */
    //update chenrf add 质检完成时间
    function editQualityReceiceInfo($arrivalId, $equId, $proNum, $backNum)
    {
        $completionTime = date('Y-m-d H:i:s'); //质检完成时间
        $sql = "update oa_purchase_arrival_equ c set c.arrivalNum=(c.arrivalNum - " . $backNum . "),c.qualityPassNum=(c.qualityPassNum+" . $proNum . ") ,c.completionTime='{$completionTime}'  where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新是否质检打回
     * @param  $id 收料通知单从表id
     * @param  $flag 是否打回标识：0 否；1 是
     */
    function updateIsQualityBack($id, $flag)
    {
        $this->update(array('id' => $id), array('isQualityBack' => $flag));
    }

    /**
     * 更新物料的质检数量. - 用于质检报告撤销
     * @param  $arrivalId 收料通知单ID
     * @param  $productId 物料Id
     * @param  $proNum 质检数量
     */
    function editQualityUnconfirmInfo($arrivalId, $equId, $proNum)
    {
        $sql = "update oa_purchase_arrival_equ c set c.qualityPassNum=(c.qualityPassNum-" . $proNum . ") where  c.id=$equId";
        $this->query($sql);
    }

    /**删除收料单所有设备，维护采购订单设备下达数量
     * @param  $id 物料Id
     */
    function del_d($id)
    {
        $rows = $this->getItemByBasicIdId_d($id);
        if ($rows) {
            $taskEquDao = new model_purchase_contract_equipment();
            foreach ($rows as $key => $val) {
                if (!isset($val['arrivalNum'])) {
                    $val['arrivalNum'] = 0;
                }
                $taskEquDao->updateAmountIssued($val['contractId'], 0, $val['arrivalNum']);
            }
        }
    }

    /**进行外购入库时，更新申请采购物料的在途数量
     * $arrivalEquId 收料物料ID
     * $number 入库数量
     */
    function updateOnWay_d($arrivalEquId, $number)
    {
        $contractDao = new model_contract_common_allcontract();
        $rows = $this->get_d($arrivalEquId);
        //获取采购类型与申请采购的物料源ID
        $sql = "select planAssType,planAssEquId from oa_purch_objass where applyEquId=" . $rows['applyEquId'];
        $res = $this->query($sql);
        $arr = mysql_fetch_row($res);
        if (is_array($arr)) {
            if ($arr[0] == "oa_sale_service" || $arr[0] == "oa_sale_order" || $arr[0] == "oa_sale_rdproject" || $arr[0] == "oa_sale_lease") { //更新合同的在途数量
                $contractDao->setOnWayNum_d($arr[0], $arr[1], -$number);
            }
        }
    }

    /**
     *根据收料物料id,获取源单信息
     *
     */
    function getApplyInfo($itemRelDocId)
    {
        $arrivalEquRow = $this->get_d($itemRelDocId);
        $contEquDao = new model_purchase_contract_equipment();
        if ($arrivalEquRow['contractId']) {
            $contEquRow = $contEquDao->get_d($arrivalEquRow['contractId']);
        }
        $object = array();
        if (is_array($contEquRow)) {
            $object = array(
                "applyType" => $contEquRow['purchType'], "applyId" => $contEquRow['sourceID']
            );

        }
        return $object;
    }

    /**
     * 判断收料单的物料是否全部入库
     *
     */
    function isEndInstock_d($arrivalId)
    {
        $equs = $this->getItemByBasicIdId_d($arrivalId);
        if (is_array($equs)) {
            $flag = 2;
            foreach ($equs as $key => $val) {
                if ($val['arrivalNum'] != $val['storageNum']) {
                    $flag = 4;
                    break;
                }
            }
            return $flag;
        } else {
            return 0;
        }

    }

    /**
     * 更新质检时间
     *
     */
    function updateComDate($id)
    {
        return $this->update(array('id' => $id), array('completionTime' => date('Y-m-d H:i:s')));
    }

}

?>