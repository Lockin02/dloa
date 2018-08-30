<?php
/**
 * @author Administrator
 * @Date 2011��5��4�� 21:40:04
 * @version 1.0
 * @description:����֪ͨ�������嵥��Ϣ Model��
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

    /**�鿴���ϵ��������б�
     * @param  $rows ����������Ϣ����
     */
    function showViewList($rows)
    {
        $datadictDao = new model_system_datadict_datadict ();
        $str = "";
        $i = 0;
        if ($rows) {
            foreach ($rows as $key => $val) {
                $val['checkType'] = $datadictDao->getDataNameByCode($val['checkType']);
                //�ʼ���Ϣ��ʾ
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
    /**�鿴���ϵ��������б�
     * @param  $rows ����������Ϣ����
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

                //�ʼ���Ϣ��ʾ
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

    /**�鿴���ϵ��������б�
     * @param  $rows ����������Ϣ����
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
					    <td>�ϼ�</td>
						<td colspan="3"></td>
						<td><b>$arrivalNum</b></td>
						<td><b>$storageNum</b></td>
						<td colspan="3"></td>
					</tr>
EOT;
        } else {
            $str = "<tr><td colspan='9'>�������������������Ϣ</td></tr>";
        }
        return $str;
    }

    /**
     * �༭����֪ͨ��
     * @param  $rows ����������Ϣ����
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
							<img title="ɾ����" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td> -->

					</tr>
EOT;

                $i++;
            }
        }
        return $str;

    }

    /**
     * �༭����֪ͨ��
     * @param  $rows ����������Ϣ����
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
							<img title="ɾ����" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td> -->

					</tr>
EOT;

                $i++;
            }
        }
        return $str;

    }

    /**
     * ���ʱ�ӱ���ʾģ��
     * @param  $rows ����������Ϣ����
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
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
     * ���ʱ�ӱ���ʾģ��
     * @param  $rows ����������Ϣ����
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
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
     * @author ��ʾģ��
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
	                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
            $str = "<tr><td colspan='15'>�����������Ϣ</td></tr>";
        }
        return $str;

    }

    /**
     * �������ϵ�ID��ȡ�����嵥
     * @param  $basicId ����֪ͨ��ID
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
     * �������ϵ�ID��ȡ�����嵥��δ�����⣩
     * @param  $basicId ����֪ͨ��ID
     *
     */
    function getItemByBasicIdUnstock_d($basicId)
    {
        $this->searchArr = array('arrivalId' => $basicId,
            "unstrock" => "unstrock");
        return $this->listBySqlId('select_default');
    }

    /**
     * �������ϵ�ID��ȡ�����嵥����������ϣ�
     * @param  $basicId ����֪ͨ��ID
     *
     */
    function getItemByBasicIdStock_d($basicId)
    {
        $this->searchArr = array('arrivalId' => $basicId);
        return $this->listBySqlId('select_stock');
    }


    /**
     * �������ϵ�ID��ȡ����ID
     * @param  $basicId ����֪ͨ��ID
     * @param  $productId ����Id
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
     * ���ݲɹ���������ID��ȡ����������Ϣ
     * @param  $contractEquId �ɹ���������ID
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
     * �������ϵ��������.
     * @param  $arrivalId ����֪ͨ��ID
     * @param  $productId ����Id
     * @param  $proNum �������
     */
    function updateNumb_d($arrivalId, $equId, $proNum)
    {
        $sql = "update oa_purchase_arrival_equ c set c.storageNum=(c.storageNum+" . $proNum . ") where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * �������ϵ��ʼ�����.
     * @param  $arrivalId ����֪ͨ��ID
     * @param  $productId ����Id
     * @param  $proNum �ʼ�����
     */
    //update chenrf add �ʼ����ʱ��
    function editQualityInfo($arrivalId, $equId, $proNum)
    {
        $completionTime = date('Y-m-d H:i:s'); //�ʼ����ʱ��
        $sql = "update oa_purchase_arrival_equ c set c.qualityPassNum=(c.qualityPassNum+$proNum) ,c.completionTime='{$completionTime}' where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * �������ϵ��������� - �����ʼ��˻�
     * @param  $arrivalId ����֪ͨ��ID
     * @param  $productId ����Id
     * @param  $proNum �ʼ�����
     */
    //update chenrf add �ʼ����ʱ��
    function editQualityBackInfo($arrivalId, $equId, $proNum)
    {
        $completionTime = date('Y-m-d H:i:s'); //�ʼ����ʱ��
        $sql = "update oa_purchase_arrival_equ c set c.arrivalNum=(c.arrivalNum - " . $proNum . ") ,c.completionTime='{$completionTime}' where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * �������ϵ��ʼ�����. - �����ʼ��ò�����
     * @param  $arrivalId ����֪ͨ��ID
     * @param  $productId ����Id
     * @param  $proNum �ʼ�����
     */
    //update chenrf add �ʼ����ʱ��
    function editQualityReceiceInfo($arrivalId, $equId, $proNum, $backNum)
    {
        $completionTime = date('Y-m-d H:i:s'); //�ʼ����ʱ��
        $sql = "update oa_purchase_arrival_equ c set c.arrivalNum=(c.arrivalNum - " . $backNum . "),c.qualityPassNum=(c.qualityPassNum+" . $proNum . ") ,c.completionTime='{$completionTime}'  where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * �����Ƿ��ʼ���
     * @param  $id ����֪ͨ���ӱ�id
     * @param  $flag �Ƿ��ر�ʶ��0 ��1 ��
     */
    function updateIsQualityBack($id, $flag)
    {
        $this->update(array('id' => $id), array('isQualityBack' => $flag));
    }

    /**
     * �������ϵ��ʼ�����. - �����ʼ챨�泷��
     * @param  $arrivalId ����֪ͨ��ID
     * @param  $productId ����Id
     * @param  $proNum �ʼ�����
     */
    function editQualityUnconfirmInfo($arrivalId, $equId, $proNum)
    {
        $sql = "update oa_purchase_arrival_equ c set c.qualityPassNum=(c.qualityPassNum-" . $proNum . ") where  c.id=$equId";
        $this->query($sql);
    }

    /**ɾ�����ϵ������豸��ά���ɹ������豸�´�����
     * @param  $id ����Id
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

    /**�����⹺���ʱ����������ɹ����ϵ���;����
     * $arrivalEquId ��������ID
     * $number �������
     */
    function updateOnWay_d($arrivalEquId, $number)
    {
        $contractDao = new model_contract_common_allcontract();
        $rows = $this->get_d($arrivalEquId);
        //��ȡ�ɹ�����������ɹ�������ԴID
        $sql = "select planAssType,planAssEquId from oa_purch_objass where applyEquId=" . $rows['applyEquId'];
        $res = $this->query($sql);
        $arr = mysql_fetch_row($res);
        if (is_array($arr)) {
            if ($arr[0] == "oa_sale_service" || $arr[0] == "oa_sale_order" || $arr[0] == "oa_sale_rdproject" || $arr[0] == "oa_sale_lease") { //���º�ͬ����;����
                $contractDao->setOnWayNum_d($arr[0], $arr[1], -$number);
            }
        }
    }

    /**
     *������������id,��ȡԴ����Ϣ
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
     * �ж����ϵ��������Ƿ�ȫ�����
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
     * �����ʼ�ʱ��
     *
     */
    function updateComDate($id)
    {
        return $this->update(array('id' => $id), array('completionTime' => date('Y-m-d H:i:s')));
    }

}

?>