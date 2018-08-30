<?php
/**
 * @description: 采购合同
 * @date 2010-12-29 下午09:01:16
 */
header("Content-type: text/html; charset=gb2312");
class model_purchase_contract_equipment extends model_base
{
    /*
     * @desription 构造函数
     * @author qian
     * @date 2010-12-29 下午09:02:22
     */
    function __construct()
    {
        $this->tbl_name = "oa_purch_apply_equ";
        $this->sql_map = "purchase/contract/equipmentSql.php";
        parent :: __construct();
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
                $sNum = $i + 1;
                $proType="";
                $typeRow=$productinfoDao->getParentType($val['productId']);
                if(!empty($typeRow)){
                    $proType=$typeRow['proType'];
                }
                $storageNum = $val['amountAll'] - $val['amountIssued'];
                $str .= <<<EOT
				<tr>
					<td>
                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
                    </td>
                   <td>
                        $sNum
                   </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productNumb]" />
                        <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                        <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i" value=""  />
                        <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i" value=""  />
                        <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i" value=""  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                        <input type="hidden" name="stockin[items][$i][proTypeId]" id="proTypeId$i" value=""  />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="{$val['k3Code']}"/>
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort" value="$val[pattem]" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][unitName]"  id="unitName$i" class="readOnlyTxtMin" value="$val[units]" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort" value="" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort" value="$storageNum" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="serialNoDeal(this,$i)"  class="txtshort" value="" onblur="FloatMul('actNum$i','price$i','subPrice$i')" />
						<input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  value="" />
						<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"  value="" />
                    </td>
                    <td>
                        <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort" value="" />
                        <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i" value="" />
                       	<input type="hidden" name="stockin[items][$i][inStockCode]"id="inStockCode$i" value="" />
                       	<input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i"   value="$val[id]" />
                        <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  value=""  />
                       	<input type="hidden" name="stockin[items][$i][relCodeCode]" id="relCodeCode$i"  value="$val[basicNumb]"  />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoney" value="$val[price]"  onblur="FloatMul('price$i','actNum$i','subPrice$i')" />
                    </td>
                    <td>
                    ******
                        <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney" value="" />
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
     * 采购发票源单选择从表模板渲染
     * @param  $rows
     *
     */
    function showInvpurchaseList($rows, $condition)
    {
        $str = "";
        if ($rows) {
            $invnumber = isset($condition['invnumber']) ? $condition['invnumber'] : 0;
            $pronumber = isset($condition['pronumber']) ? $condition['pronumber'] : 0;
            $objId = isset($condition['objId']) ? $condition['objId'] : 0;
            $objCode = isset($condition['objCode']) ? $condition['objCode'] : 0;
            $objType = isset($condition['objType']) ? $condition['objType'] : null;
            foreach ($rows as $key => $val) {
                $pronumber++;
                $invnumber++;
                $str .= <<<EOT
                    <tr class="$objType"><td>
                            <img src="images/removeline.png" onclick="mydel(this,'invbody')" title="删除行">
                        </td>
                        <td>
                            $pronumber
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][productNo]" id="productNo$invnumber" value="$val[productNumb]" class="txtmiddle"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objId]"id="objId$invnumber" value="$objId" />
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objCode]" id="objCode$invnumber"   value="$objCode" />
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objType]" value="$objType"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][productName]" id="productName$invnumber" value="$val[productName]" class="txt"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][productId]" id="productId$invnumber" value="$val[productId]"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][productModel]" id="productModel$invnumber" value="$val[pattem]" class="readOnlyTxtItem" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][unit]" id="unit$invnumber" value="$val[units]" class="readOnlyTxtShort" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][number]" id="number$invnumber" value="$val[amountAll]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][price]" id="price$invnumber" value="$val[applyPrice]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][taxPrice]" id="taxPrice$invnumber" value="$val[applyPrice]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][amount]" id="amount$invnumber" value="$val[moneyAll]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][assessment]" id="assessment$invnumber" value="0" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][allCount]" id="allCount$invnumber" value="$val[moneyAll]" class="txtshort"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][objCode]" id="objCode$invnumber" value="$objCode" class="readOnlyTxtNormal" readonly="readonly"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objId]"id="objId$invnumber" value="$objId" />
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][objType]" value="$objType"/>
                        </td>
                        <td>
                            <input type="text" name="invpurchase[invpurdetail][$invnumber][contractCode]" id="contractCode$invnumber" value="$objCode" class="readOnlyTxtNormal" readonly="readonly"/>
                            <input type="hidden" name="invpurchase[invpurdetail][$invnumber][contractId]"id="contractId$invnumber" value="$objId" />
                        </td>
                    </tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * @author 收料显示模板
     *
     */
    function showArrivalList($rows)
    {
        $datadictDao = new model_system_datadict_datadict ();
        if ($rows) {
            $arrivalNum = 0;
            $storageNum = 0;
            $i = 0;
            foreach ($rows as $key => $val) {
                $i++;
                $val['checkType'] = $datadictDao->getDataNameByCode($val['checkType']);
                $arrivalNum = $arrivalNum + $val[arrivalNum];
                $storageNum = $storageNum + $val[storageNum];
                $str .= <<<EOT
					<tr>
					    <td width="5%">$i)</td>
						<td width="20%">
							$val[arrivalCode]
						</td>
						<td width="30%">$val[batchNum]</td>
						<td width="10%">$val[arrivalNum]</td>
						<td width="10%">$val[storageNum]</td>
						<td width="5%">
						  $val[month]
						</td>
						<td width="10%">$val[arrivalDate]</td>
						<td width="10%">$val[checkType]</td>
					</tr>
EOT;
            }
            $str .= <<<EOT
					<tr class="tr_count">
					    <td>合计</td>
						<td colspan="2"></td>
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
     * @author 显示模板
     *
     */
    function showItemModel($listEqu)
    {
        $interfObj = new model_common_interface_obj ();
        $str = "";
        $i = 0;
        $sumNumb = 0;
        $sumMoney = 0.00;
        if ($listEqu) {
            foreach ($listEqu as $key => $val) {
                $unionNum = $val[amountAll] - $val[amountIssued];
                $purchType = $interfObj->typeKToC($val['purchType']); //类型名称
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
						<td>
	                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
	                    </td>
					    <td>$i</td>
					    <td>$val[productNumb]</td>
						<td>
						<input type="hidden" name="stockin[orderItems][$i][productId]" value="$val[productId]"/>
						<input type="hidden" name="stockin[orderItems][$i][id]" value="$val[id]"/>
						<input type="hidden" name="stockin[orderItems][$i][price]" value="$val[applyPrice]"/>
							$val[productName]
						</td>
						<td>$val[pattem]</td>
						<td>$val[units]</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$val[amountIssued]
						</td>
						<td class="formatMoney">
							$val[applyPrice]
						</td>
						<td class="formatMoney">
							$val[moneyAll]
						</td>
						<td>
							$purchType
						</td>
						<td>
							$val[applyDeptName]
						</td>
						<td>
							$val[sourceNumb]
						</td>
						<td>
							<input type="text" class="txtshort" name="stockin[orderItems][$i][unionNum]" value="$unionNum"/>
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
     *采购申请信息查看模板
     * @param $planEqu 采购申请信息
     */
    function showPlanEquList($planEqu)
    {
        $planDao = new model_purchase_plan_basic();
        $str = "";
        $i = 0;
        if ($planEqu) {
            foreach ($planEqu as $key => $val) {
                $assets_Codes = '无资产编号信息';
                $deptName = $val['department'];
                $sendName = $val['sendName'];
                if ($val["purchType"] == "produce") {
                    //显示附件信息
                    $fileStr = $planDao->getFilesByObjId($val['basicId'], false, 'oa_purch_plan_basic');
                } else if($val["purchType"] == "assets" || $val["purchType"] == "oa_asset_purchase_apply") {
                    // 显示资产编号信息(因可能含有其他采购类型的物料，所以只能一一对比并获取资产卡片信息)
                    $assetCardStr = $this->searchAssetCardByOrderId($val["basicId"],$val['id'],$val['productNumb']);
                    $assets_Codes = (is_array($assetCardStr))? $assetCardStr['linkStr'] : '';
                    $warehouseName = $this->_db->getArray("select id,warehouseName,wareManagerName from oa_asset_purchase_apply where id = '{$val['applyId']}'");
                    if($warehouseName && isset($warehouseName[0]['warehouseName'])){
                        $deptName = $warehouseName[0]['warehouseName'];
                        $sendName = $warehouseName[0]['wareManagerName'];
                    }
                }else{
                    $fileStr = '无附件信息';
                }
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
					    <td>$val[productNumb]</td>
						<td width="16%">
							$val[productName]
						</td>
						<td>$val[basicNumb]</td>
						<td>$val[purchTypeCN]</td>
						<td>
							$val[amountAll]
						</td>
						<td>
							$deptName
						</td>
						<td >
							$sendName
						</td>
						<td >
							$val[dateHope]
						</td>
						<td >
							$val[address]
						</td>
						<td >
							$val[pattem]
						</td>
						<td >
							$assets_Codes
						</td>
						<td>
							$val[sourceNumb]
						</td>
						<td>
							$val[rObjCode]
						</td>
						<td>
							$val[remark]
						</td>
						<td>
							$fileStr
						</td>
					</tr>
EOT;
            }
        } else {
            $str = "<tr><td colspan='11'>无关联信息</td></tr>";
        }
        return $str;

    }
    /**
     * 通过订单ID查询对应的资产卡片数据
     * @param $oid
     * @param $productID
     * @param $productNo
     * @return array
     * @description ID2209 2016-11-24 haojin
     */
        function searchAssetCardByOrderId($oid,$productID,$productNo){
        $cardsData = array();
        // 获取采购订单编号
        $sql = "SELECT hwapplyNumb FROM oa_purch_apply_basic WHERE id = '{$oid}'";
        $purchase_order = $this->_db->getArray($sql);
        $purchase_orderCode = $purchase_order[0]['hwapplyNumb'];

        // 到新OA获取卡片编号信息(用采购订单号换对应的资产卡片信息)
        $result = util_curlUtil::getDataFromAWS('asset', 'SeckCardASLP', array(
            "comefromNo" => $purchase_orderCode
        ));
        $back_data = util_jsonUtil::decode ( $result ['data'], true );
        $back_str = '';
        if($back_data['result'] == 'ok' && !empty($back_data['data']['detail'])){
            $cardsData['detail'] = array();
            foreach($back_data['data']['detail'] as $k => $v){
                if($v['ACCEPTANCEDETAILID'] == $productID){
                    $urlStr = urlencode("&cmd=com.actionsoft.apps.asset.card_viewTabs&bindId={$v['BINDID']}");
                    $cardsData['detail'][$v['ASSETCODE']]['linkStr'] = "<a href='?model=common_otherdatas&action=toSignInAwsMenu&reUrl=".$urlStr."' target='_blank'>".$v['ASSETCODE']."</a>,";
                    $cardsData['detail'][$v['ASSETCODE']]['bindId'] = $v['BINDID'];
                    $cardsData['detail'][$v['ASSETCODE']]['productID'] = $productID;
                    $cardsData['detail'][$v['ASSETCODE']]['productNo'] = $productNo;
                    $cardsData['detail'][$v['ASSETCODE']]['objId'] = $oid;
                    $cardsData['detail'][$v['ASSETCODE']]['objCode'] = $purchase_orderCode;
                    $back_str .= "<a href='?model=common_otherdatas&action=toSignInAwsMenu&reUrl=".$urlStr."'>".$v['ASSETCODE']."</a>,";
                }
            }

        }

        $back_str = rtrim($back_str,',');
        $cardsData['linkStr'] = $back_str;
//        echo "<pre>";print_r($back_data);exit();
        return $cardsData;
    }

    /**
     *采购申请信息查看模板
     * @param $planEqu 采购申请信息
     */
    function showPlanEquListForOrder($planEqu)
    {
        $str = "";
        $i = 0;
        if ($planEqu) {
            foreach ($planEqu as $key => $val) {
                //根据不同的采购类型，查看不同的源单信息
                switch ($val['purchType']) {
                    case "oa_sale_order":
                        $souceNum = '<a target="_bank" href="index1.php?model=projectmanagent_order_order&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "oa_sale_lease":
                        $souceNum = '<a target="_bank" href="index1.php?model=contract_rental_rentalcontract&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "oa_sale_service":
                        $souceNum = '<a target="_bank" href="index1.php?model=engineering_serviceContract_serviceContract&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "oa_sale_rdproject":
                        $souceNum = '<a target="_bank" href="index1.php?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "stock":
                        $souceNum = '<a target="_bank" href="index1.php?model=stock_fillup_fillup&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看补库信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "oa_borrow_borrow":
                        $souceNum = '<a target="_bank" href="index1.php?model=projectmanagent_borrow_borrow&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看借试用信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "oa_present_present":
                        $souceNum = '<a target="_bank" href="index1.php?model=projectmanagent_present_present&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看赠送信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "HTLX-XSHT":
                        $souceNum = '<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "HTLX-ZLHT":
                        $souceNum = '<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "HTLX-FWHT":
                        $souceNum = '<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
                    case "HTLX-YFHT":
                        $souceNum = '<a target="_bank" href="index1.php?model=contract_contract_contract&action=init&perm=view&id=' . $val[sourceID] . '&skey=' . $val['skey_'] . '" title="查看合同信息">' . $val[sourceNumb] . '</a>';
                        break;
//					case "rdproject":$souceNum='<a target="_bank" href="index1.php?model=rdproject_project_rdproject&action=rpBasicMsg&pjId='.$val[sourceID].'" title="查看项目信息">'.$val[sourceNumb].'</a>';break;
                    default:
                        $souceNum = $val['sourceNumb'];
                        break;
                }
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
					    <td>$val[productNumb]</td>
						<td width="15%">
							$val[productName]
						</td>
						<td>$val[purchTypeCn]</td>
						<td >
							$val[sendName]
						</td>
						<td>
							$souceNum
						</td>
						<td>
							$val[remark]
						</td>
					</tr>
EOT;
            }
        } else {
            $str = "<tr><td colspan='11'>无关联信息</td></tr>";
        }
        return $str;

    }

    /**
     *采购历史信息查看模板
     */
    function showHistoryList($groupByEqus)
    {
        $str = "";
        $i = 0;
        if ($groupByEqus) {
            $orderDateTime = $this->get_table_fields('oa_purch_apply_basic', 'id=' . $groupByEqus['0']['basicId'], 'createTime'); //订单日期
            foreach ($groupByEqus as $key => $val) {
                $rows = $this->getHistoryInfo_d($val['productNumb'], $orderDateTime); //获取最新历史价格
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                if ($rows) {
                    $str .= <<<EOT
						<tr class="$classCss">
						    <td>$i</td>
							<td >
								$rows[productNumb]
							</td>
							<td width="20%">$rows[productName]</td>
						    <td>$rows[orderTime]</td>
							<td>$rows[suppName]</td>
							<td  class="formatMoneySix">
								$rows[price]
							</td>
							<td  class="formatMoneySix">
								$rows[applyPrice]
							</td>
							<td >
								$rows[taxRate]%
							</td>
							<td >
								$rows[amountAll]
							</td>
							<td>
								$rows[paymentConditionName] $rows[payRatio]
							</td>
						</tr>
EOT;
                } else {
                    $str .= <<<EOT
						<tr class="$classCss">
						    <td>$i</td>
							<td >
								$val[productNumb]
							</td>
							<td width="20%">$val[productName]</td>
						    <td colspan="7">无历史价格信息</td>
						</tr>
EOT;

                }
            }
        } else {
            $str = "<tr><td colspan='11'>无相关信息</td></tr>";
        }
        return $str;

    }

    /**
     *采购询价信息查看模板
     * @param $inquiryEqu 采购询价信息
     */
    function showInquiryList($inquiryEqu)
    {
        $str = "";
        $i = 0;
        if ($inquiryEqu) {
            foreach ($inquiryEqu as $key => $val) {
                $j = 0;
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $supp = array();
                foreach ($val['supppro'] as $eKey => $eVal) {
                    $j++;
                    $supp[$j] = $eVal['suppName'] . "<<font color=blue>" . $eVal['price'] . "</font>>";
                }
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
					    <td>$val[productNumb]</td>
						<td width="16%">
							$val[productName]
						</td>
						<td>$val[inquiryCode]</td>
						<td >$supp[1]</td>
						<td>$supp[2]
						</td>
						<td>
							$supp[3]
						</td>
					</tr>
EOT;
            }
        } else {
            $str = "<tr><td colspan='11'>无关联信息</td></tr>";
        }
        return $str;

    }

    /**
     *付款申请信息查看模板
     * @param $payApply 付款申请信息
     */
    function showPayApplyList($payApply)
    {
        $datadictDao = new model_system_datadict_datadict();
        $str = "";
        $i = 0;
        $applyMoneySum = 0;
        if ($payApply) {
            foreach ($payApply as $key => $val) {
                $val['payFor'] = $datadictDao->getDataNameByCode($val['payFor']);
                $applyMoneySum = bcadd($applyMoneySum, $val['money'], 2);
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
					    <td>$val[formNo]</td>
						<td >
							$val[formDate]
						</td>
						<td>$val[payFor]</td>
						<td>$val[supplierName]</td>
						<td>
							$val[payMoney]
						</td>
						<td>
							$val[payedMoney]
						</td>
						<td >
							$val[money]
						</td>
						<td >
							$val[ExaStatus]
						</td>
						<td>
							$val[ExaDT]
						</td>
						<td>
							$val[salesman]
						</td>
					</tr>
EOT;
            }
            $str .= "<tr class='tr_count'><td>合计</td><td colspan='6'></td><td>$applyMoneySum</td><td colspan='3'></td></tr>";
        } else {
            $str = "<tr><td colspan='11'>无关联信息</td></tr>";
        }
        return $str;

    }

    /**
     *付款记录信息查看模板
     * @param $payed 付款记录信息
     */
    function showPayedList($payed)
    {
        $datadictDao = new model_system_datadict_datadict();
        $str = "";
        $i = 0;
        $payMoneySum = 0;
        if ($payed) {
            foreach ($payed as $key => $val) {
                $val['formType'] = $datadictDao->getDataNameByCode($val['formType']);
                $val['payType'] = $datadictDao->getDataNameByCode($val['payType']);
                $payMoneySum = bcadd($payMoneySum, $val['money'], 2);
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
					    <td>$val[formNo]</td>
						<td >
							$val[formDate]
						</td>
						<td>$val[formType]</td>
						<td>$val[supplierName]</td>
						<td>
							$val[amount]
						</td>
						<td>
							$val[money]
						</td>
						<td >
							$val[payType]
						</td>
						<td >
							$val[createName]
						</td>
						<td>
							$val[payApplyNo]
						</td>
					</tr>
EOT;
            }
            $str .= "<tr class='tr_count'><td>合计</td><td colspan='5'></td><td>$payMoneySum</td><td colspan='3'></td></tr>";
        } else {
            $str = "<tr><td colspan='11'>无关联信息</td></tr>";
        }
        return $str;
    }

    /**
     *发票记录信息查看模板
     * @param $invoice 发票记录信息
     */
    function showInvoiceList($invoice)
    {
        $datadictDao = new model_system_datadict_datadict();
        $str = "";
        $i = 0;
        $invoiceMoneySum = 0;
        if ($invoice) {
            foreach ($invoice as $key => $val) {

                $val['invType'] = $datadictDao->getDataNameByCode($val['invType']);
                if ($val['status'] == 1) {
                    $val['status'] = '已钩稽';
                } else {
                    $val['status'] = '未钩稽';
                }
                $invoiceMoneySum = bcadd($invoiceMoneySum, $val['amount'], 2);
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
					    <td>$val[objCode]</td>
						<td >
							$val[objNo]
						</td>
						<td>$val[supplierName]</td>
						<td>$val[invType]</td>
						<td class="formatMoney">
							$val[amount]
						</td>
						<td class="formatMoney">
							$val[formCount]
						</td>
						<td>
							$val[formDate]
						</td>
						<td >
							$val[salesman]
						</td>
						<td >
							$val[status]
						</td>
					</tr>
EOT;
            }
            $str .= "<tr class='tr_count'><td>合计</td><td colspan='4'></td><td>$invoiceMoneySum</td><td colspan='4'></td></tr>";
        } else {
            $str = "<tr><td colspan='11'>无关联信息</td></tr>";
        }
        return $str;
    }


    /**查看收料单，物料列表
     * @param  $equRows 物料信息数组
     */
    function showEquList($equRows)
    {
        //根据采购订单的物料ID，查找所有的收料物料信息
        $equDao = new model_purchase_arrival_equipment();
        $str = "";
        $i = 0;
        if (is_array($equRows)) {
            foreach ($equRows as $conKey => $val) {
                $i++;
                $rows = $equDao->getItemByContractEquId_d($val['id']);
                $arrivalList = $this->showArrivalList($rows);
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[productName]
						</td>
						<td >$val[pattem]</td>
						<td>
						  $val[units]
						</td>
						<td class="td_table"  colspan="9">
							<table class="main_table_nested" >
								$arrivalList
							</table>
						</td>
					</tr>
					<tr heigth="10px"><td colspan='5'></td></tr>
EOT;
            }
        } else {
            $str = "<tr><td colspan='9'>该物料暂无相关收料信息</td></tr>";
        }
        return $str;
    }

    /**根据料采购任务ID,查找所对应的申请部门
     *author can
     *2011-7-21
     */
    function getApplyRows($taskId)
    {
        //获取采购申请单id
        $sql = "select planId from oa_purch_objass where taskEquId=" . $taskId;
        $res = $this->query($sql);
        $arr = mysql_fetch_row($res);
        if ($arr[0]) {
            //获取采购申请单信息
            $planDao = new model_purchase_plan_basic();
            $rows = $planDao->get_d($arr[0]);
            return $rows;

        }
    }


    /**
     * 根据合同id获取合同设备清单
     * $contractId 订单ID
     */
    function getEqusByContractId($contractId)
    {
        $this->searchArr = array("basicId" => $contractId);
        return $this->list_d();
    }

    /**
     * 根据合同id获取合同设备清单
     * $contractId 订单ID，不过滤掉amountAll等于0的数据
     */
    function getEqusByContractIdNew($contractId)
    {
        $this->searchArr = array("basicId" => $contractId);
        return $this->list_d("equipment_listNew");
    }

    /**
     * 根据询价单id获取询价单设备清单,去除重复
     **/
    function getUniqueByParentId($parentId)
    {
        $searchArr = array(
            "basicId" => $parentId
        );
        $this->__SET('sort', "p.id");
        $this->__SET('groupBy', "p.productNumb");
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("equipment_list");
        return $rows;
    }

    /**
     * 根据询价单id获取询价单设备清单,不去除重复
     **/
    function getUniqueByParentIdNew($parentId)
    {
        $searchArr = array(
            "basicId" => $parentId
        );
        $this->__SET('sort', "p.id");
        $this->__SET('groupBy', "p.id");
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("equipment_list");
        return $rows;
    }

    /**
     * 根据合同id获取未开采购发票的设备
     * $contractId 订单ID
     */
    function getEqusForInvpurchase($contractId)
    {
        $sql = "select sp.id,sp.productId,sp.productNumb,sp.productName,sum(sp.amountAll) as amountAll,sp.applyPrice  ,sum(sp.amountAll) *  sp.applyPrice as  moneyAll  from
					(select p.id,p.productId ,p.productNumb ,p.productName,p.pattem ,p.units,p.amountAll,p.applyPrice from oa_purch_apply_equ p where p.basicId = $contractId
					union
					select i.id,i.productId,i.productNo,i.productName,i.productModel,i.unit,if(v.formType = 'blue',-i.number,i.number) as amountAll,0 as applyPrice from oa_finance_invpurchase_detail i left join oa_finance_invpurchase v on i.invPurId = v.id where i.objId = $contractId and i.objType = 'CGFPYD-01' ) sp
				group by sp.productId having amountAll != 0 ";
        return $this->listBySql($sql);
    }

    /**
     * 根据合同id获取未到货合同设备清单
     * $contractId 订单Id
     */
    function getUnArrivalEqusByContractId($contractId)
    {
        $this->searchArr = array("basicId" => $contractId);
        return $this->list_d();
    }

    /**
     * 根据非传参ID设备清单
     * $contractId 订单Id
     * $equIds 订单物料Id字符串
     */
    function getEquNotIn($contractId, $equIds)
    {
        $this->searchArr = array("basicId" => $contractId, "equNotIn" => $equIds);
        return $this->list_d();
    }

    /**
     * 根据任务物料id获取订单清单
     * $taskEquId 订单Id
     */
    function getEqusByTaskEquId($taskEquId)
    {
        $this->searchArr = array("taskEquId" => $taskEquId);
        $rows = $this->listBySqlId("equ_execute");
        return $rows;
    }

    /**
     * 根据任务物料id获取订单清单(采购进度)
     * $taskEquId 订单Id
     */
    function getProgressEqusByTequId($taskEquId)
    {
        $this->searchArr = array("taskEquId" => $taskEquId);
        $rows = $this->listBySqlId("equ_progress");
        return $rows;
    }

    /**
     * 根据询价物料id获取订单清单
     * $taskEquId 订单Id
     */
    function getEqusByInquiryEquId($inquiryEquId)
    {
        if ($inquiryEquId > 0) {
            $this->searchArr = array("inquiryEquId" => $inquiryEquId);
            $rows = $this->listBySqlId("equ_progress");
            return $rows;
        } else {
            return array();
        }
    }

    /**
     * 根据搜索条件，获取在途数量
     * $condition 搜索条件
     */
    function getEqusOnway($condition)
    {
        $this->searchArr = $condition;
        $this->sort = 'onWayAmount';
        $rows = $this->listBySqlId("equ_onway");
        return $rows[0]['onWayAmount'];
    }


    /**
     * 对外接口
     * 如果$lastIssueNum有值的话，把原数据的数量-$lastIssueNum+&issueNum,没有的话把&issueNum跟原数据的数量相加
     */
    function updateAmountIssued($id, $issuedNum, $lastIssueNum = false, $docDate = day_date)
    {
        if (isset($lastIssueNum) && $issuedNum == $lastIssueNum) {
            return true;
        } else {
            if ($lastIssueNum) {
                $sql = "update " . $this->tbl_name .
                    " set amountIssued=amountIssued + $issuedNum - $lastIssueNum, dateIssued = '$docDate' where id='$id'";
            } else {
                $sql = "update " . $this->tbl_name .
                    " set amountIssued=amountIssued + $issuedNum, dateIssued = '$docDate' where id='$id'";
            }
            return parent::query($sql);
        }
    }

    /**，维护采购任务设备合同数量
     *author can
     *2011-1-6
     *$id 订单ID
     */
    function updateContractAmount_d($id)
    {
        $rows = $this->getEqusByContractId($id);
        if ($rows) {
            $taskEquDao = new model_purchase_task_equipment();
            foreach ($rows as $key => $val) {
                if (!isset($val['amountAll'])) {
                    $val['amountAll'] = 0;
                }
                $taskEquDao->updateContractAmount($val['taskEquId'], 0, $val['amountAll']);
            }
        }

    }

    /**
     * @exclude 通过采购申请单Id判断所属设备是否已下达完成
     * @author ouyang
     * @param 采购订单Id
     * @return bool
     * @version 2010-8-10 下午04:37:54
     */
    function endByBasicId_d($id)
    {
        $searchArr = array(
            "basicId" => $id,
            "status" => '0',
            "isTemp" => '0'
        );
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("equipment_list");
        $returnVal = true;
        if ($rows) {
            foreach ($rows as $key => $val) {
                if (!isset($val['amountAll'])) {
                    $val['amountAll'] = 0;
                }
                if (!isset($val['amountIssued'])) {
                    $val['amountIssued'] = 0;
                }
                if ($val['amountAll'] > $val['amountIssued']) {
                    $returnVal = false;
                }
            }
        }
        return $returnVal;
    }

    /**
     * 在执行的订单数组处理
     *
     */
    function dealExeRows_d($rows)
    {
        if (is_array($rows)) {
            $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
            foreach ($rows as $key => $val) {
                $rows[$key]['stockNum'] = $inventoryDao->getExeNums($rows[$key]['productId'], '1');
            }
        }
        return $rows;
    }

    /**
     * 获取采购申请信息
     * @param $id 订单Id
     */
    function getPlanEqu_d($id)
    {
        $conditions = "basicId=" . $id;
        $purchType = $this->get_table_fields('oa_purch_apply_equ', $conditions, 'purchType'); //获取物料的采购类型
        $interfObj = new model_common_interface_obj ();
        $this->searchArr ['orderId'] = $id;
        $this->groupBy = "pe.id";
        if ($purchType == "oa_asset_purchase_apply") { //判断是否为“资产采购”
            $planEqu = $this->listBySqlId("contract_assetequ_list");
        } else {
            $planEqu = $this->listBySqlId("contract_planequ_list");
        }
        if (is_array($planEqu)) {
            $odDao = new model_common_otherdatas();
            $userCache = array();
            foreach ($planEqu as $key => $val) {
                $planEqu[$key]['purchTypeCN'] = $interfObj->typeKToC($val['purchType']); //类型名称

                if ($purchType == "oa_asset_purchase_apply") { //判断是否为“资产采购”
                    if (!isset($userCache[$val['sendId']])) {
                        $userCache[$val['sendId']] = $odDao->getUserDatas($val['sendId']);
                    }
                    $planEqu[$key]['department'] = $userCache[$val['sendId']]['DEPT_NAME'] ?
                        $userCache[$val['sendId']]['DEPT_NAME'] : $planEqu[$key]['department'];
                }
            }
        }
        return $planEqu;

    }

    /**
     * 获取采购申请信息
     * @param $id 订单Id
     */
    function getPlanEquForRead_d($id)
    {
        $conditions = "basicId=" . $id;
        $purchType = $this->get_table_fields('oa_purch_apply_equ', $conditions, 'purchType'); //获取物料的采购类型
        $interfObj = new model_common_interface_obj ();
        $this->searchArr ['orderId'] = $id;
        $this->groupBy = "pe.id";
        if ($purchType == "oa_asset_purchase_apply") { //判断是否为“资产采购”
            $planEqu = $this->listBySqlId("contract_assetequ_list");
        } else {
            $planEqu = $this->listBySqlId("contract_planequ_list");
        }
        if (is_array($planEqu)) {
            foreach ($planEqu as $key => $val) {
                $planEqu[$key]['purchTypeCn'] = $interfObj->typeKToC($val['purchType']); //类型名称
            }
        }
        return $planEqu;

    }

    /**
     * 获取采购询价信息
     * @param $id 订单Id
     */
    function getInquiryEqu_d($id)
    {
        $suppproDao = new model_purchase_inquiry_inquirysupppro();
        $this->searchArr ['orderId'] = $id;
        $this->groupBy = "ie.id";
        $inquiryEqu = $this->listBySqlId("contract_inquiryequ_list"); //获取询价物料信息
//			echo "<pre>";
//			print_r($inquiryEqu);
        if (is_array($inquiryEqu)) {
            foreach ($inquiryEqu as $key => $val) {
                $inquiryEqu[$key]['supppro'] = $suppproDao->getSuppEqu_d($val['ieId']);
            }
        }
        return $inquiryEqu;
    }

    /**获取历史采购信息
     * @author evan
     *
     */
    function getHistoryInfo_d($productName, $orderDateTime)
    {
        $searchArr = array(
            "productNumb" => $productName,
            "orderDate" => $orderDateTime
        );
        $this->groupBy = "p.id";
        $this->pageSize = 1;
        $this->__SET('sort', "c.createTime");
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId("history_price");
//			echo "<pre>";
//			print_r($rows);
        return $rows['0'];
    }

}

?>
