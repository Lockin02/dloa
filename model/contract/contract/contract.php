<?php

//2012-12-27备份

/**
 * @author Liub
 * @Date 2012年3月8日 10:30:28
 * @version 1.0
 * @description:合同主表 Model层
 */

class model_contract_contract_contract extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_contract";
        $this->sql_map = "contract/contract/contractSql.php";
        parent :: __construct();
    }

    //根据不同的产线,配置相应的邮件接收人
    public $productLineToMailArr = array(
        'HTCPX-YQYB' => 'quanzhou.luo',//仪器仪表事务部：罗权洲(只限含研发类产品)
        'HTCPX-CTFW' => 'dongsheng.wang',//传统服务：王东生
        'HTCPX-DSJ' => 'jianping.luo',//大数据部：罗建平
        'HTCPX-XYWSYB' => '',//新业务事业部
        'HTCPX-CWJS' => '',//财务结算
        'HTCPX-ZXJS' => ''//智翔结算
    );

    /**-----------------------------页面模板显示方法-------------------------------------**/
    /**
     * @authorhuangzf
     * 产品入库时请清单显示模板
     * @param  $rows
     */
    function showProItemAtRkProduct($rows)
    {
        $str = "";
        $i = 0;
        if (is_array($rows['equ'])) {
            $productinfoDao = new model_stock_productinfo_productinfo();
            //			print_r($rows['equ']);
            foreach ($rows['equ'] as $key => $val) {
                $sNum = $i + 1;
                $proType = "";
                $typeRow = $productinfoDao->getParentType($val['productId']);
                if (!empty($typeRow)) {
                    $proType = $typeRow['proType'];
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
                                    <input type="text" name="stockin[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]"/>
                                    <input type="hidden" name="stockin[items][$i][productId]" id="productId$i" value="$val[productId]" />
                                    <input type="hidden" name="stockin[items][$i][serialSequence]" id="serialSequence$i"   />
                                    <input type="hidden" name="stockin[items][$i][serialRemark]" id="serialRemark$i"  />
                                    <input type="hidden" name="stockin[items][$i][serialId]" id="serialId$i"  />
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
                                    <input type="text" name="stockin[items][$i][pattern]" id="pattern$i" class="readOnlyTxtShort"  value="$val[productModel]" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][unitName]" id="unitName$i" class="readOnlyTxtMin"  value="$val[unitName]" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][batchNum]"  id="batchNum$i" class="txtshort"  />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][storageNum]" id="storageNum$i" class="readOnlyTxtShort"  value="$val[number]" />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][actNum]" id="actNum$i" ondblclick="toAddSerialNo(this,$i)" class="txtshort" onblur="FloatMul('actNum$i','price$i','subPrice$i')"/>
									<input type="hidden" name="stockin[items][$i][serialnoId]" id="serialnoId$i"  />
									<input type="hidden" name="stockin[items][$i][serialnoName]" id="serialnoName$i"   />
                                </td>
                                <td>
                                    <input type="text" name="stockin[items][$i][inStockName]" id="inStockName$i" class="txtshort"  value="" />
                                    <input type="hidden" name="stockin[items][$i][inStockId]" id="inStockId$i"  value="" />
                                    <input type="hidden" name="stockin[items][$i][inStockCode]" id="inStockCode$i"  value="" />
                                    <input type="hidden" name="stockin[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
                                    <input type="hidden" name="stockin[items][$i][relDocName]" id="relDocName$i"  value=""/>
                                    <input type="hidden" name="stockin[items][$i][relDocCode]" id="relCodeCode$i"  value="" />
                                </td>
                                <td>
                                ******
                                    <input type="hidden" name="stockin[items][$i][price]" id="price$i" class="txtshort formatMoneySix" onblur="FloatMul('price$i','actNum$i','subPrice$i')" value="$val[price]" />
                                </td>
                                <td>
                                ******
                                    <input type="hidden" name="stockin[items][$i][subPrice]" id="subPrice$i" class="readOnlyTxtShort formatMoney"  value="$val[money]"/>
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
     * @authorhuangzf
     * 蓝色销售出库单带出物料信息
     * @param  $rows
     */
    function showProItemAtCkSales($rows)
    {
        if ($rows['equ']) {
            $productinfoDao = new model_stock_productinfo_productinfo();
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach ($rows['equ'] as $key => $val) {
                if (($val['number'] + $val['backNum']) - $val['executedNum'] > 0) {
                    $seNum = $i + 1;
                    $proType = "";
                    $typeRow = $productinfoDao->getParentType($val['productId']);
                    if (!empty($typeRow)) {
                        $proType = $typeRow['proType'];
                    }
                    $deexecutedNum = ($val['number'] + $val['backNum']) - $val['executedNum'];
                    $str .= <<<EOT
				<tr align="center">
					<td>
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="删除行" />
					</td>
                   <td>
                   		$seNum
                   </td>
                   <td>
                      <input type="text" name="stockout[items][$i][productCode]" id="productCode$i" class="readOnlyTxtItem" value="$val[productCode]"  />
					  <input type="hidden" name="stockout[items][$i][productId]" id="productId$i" value="$val[productId]"  />
                    </td>
                    <td>
                        <input type="text" name="stockout[items][$i][proType]" id="proType$i" class="readOnlyTxtShort" value="$proType" readonly="readonly"/>
                    </td>
                    <td>
                        <input type="text" name="stockout[items][$i][k3Code]" id="k3Code$i" class="readOnlyTxtShort" value="$val[k3Code]" readonly="readonly"/>
                    </td>
					<td>
					  <input type="text" name="stockout[items][$i][productName]" id="productName$i" class="readOnlyTxtNormal" value="$val[productName]"  />
					</td>
    				<td>
    				   <input type="text" name="stockout[items][$i][pattern]" id="pattern$i" class="readOnlyTxtItem" value="$val[productModel]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][unitName]" id="unitName$i" class="readOnlyTxtItem" value="$val[unitName]"  />
					</td>
					<td>
					   <input type="text" name="stockout[items][$i][shouldOutNum]" id="shouldOutNum$i" class="readOnlyTxtItem" value="$deexecutedNum"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][actOutNum]" id="actOutNum$i" class="txtshort" ondblclick="chooseSerialNo($i)"
							onblur="javascript:FloatMul('actOutNum$i','salecost$i','saleSubCost$i');FloatMul('actOutNum$i','cost$i','subCost$i');"  value="$deexecutedNum"  />
					</td>
					<td>
						<input type="text" name="stockout[items][$i][stockName]" id="stockName$i" class="txtshort" value=""  />
						<input type="hidden" name="stockout[items][$i][stockId]" id="stockId$i" value=""  />
						<input type="hidden" name="stockout[items][$i][stockCode]" id="stockCode$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocId]" id="relDocId$i" value="$val[id]"  />
						<input type="hidden" name="stockout[items][$i][relDocName]" id="relDocName$i" value=""  />
						<input type="hidden" name="stockout[items][$i][relDocCode]" id="relDocCode$i" value=""  />
					</td>
					<td>
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="选择序列号">
						 <input type="hidden" name="stockout[items][$i][serialnoId]" id="serialnoId$i"  value=""/>
						 <input type="text" name="stockout[items][$i][serialnoName]" readOnly style="background:#EEEEEE;" class="txtbiglong" id="serialnoName$i"  value="" />
					</td>
					<td>
					******
						<input type="hidden" name="stockout[items][$i][cost]" id="cost$i" class="txtshort formatMoneySix" onblur="FloatMul('cost$i','actOutNum$i','subCost$i')" value="0"  />
					</td>
                     <td>
					******
                        <input type="hidden" name="stockout[items][$i][subCost]" id="subCost$i" class="readOnlyTxtItem formatMoney" value="0"  />
					</td>
                     <td>
					******
						<input type="hidden" name="stockout[items][$i][salecost]" id="salecost$i" class="txtshort formatMoney" onblur="FloatMul('salecost$i','actOutNum$i','saleSubCost$i')" value="$val[price]"  />
					</td>
    				<td>
					******
    					<input type="hidden" name="stockout[items][$i][saleSubCost]" id="saleSubCost$i" class="readOnlyTxtItem formatMoney" value="$val[money]"  />
					</td>
			</tr>
EOT;
                    $i++;
                }
            }
            return $str;
        }
    }

    /**
     * 获取产品里的审批部门
     */
    function getDeptIds($object)
    {
        //查找处理 所选产品的 最高类型
        $goodsTypeIds = "";
        foreach ($object['product'] as $k => $v) {
            if ($v['isDel'] != "1") {
                $goodsTypeIds .= $v['conProductId'] . ",";
            }
        }
        $goodsIds = rtrim($goodsTypeIds, ',');
        if (!empty ($goodsIds)) {
            $sql = "select auditDeptCode from oa_goods_base_info where id in ($goodsIds)";
            $goodsA = $this->_db->getArray($sql);
            if (!empty ($goodsA)) {
                $deptIds = "";
                foreach ($goodsA as $k => $v) {
                    if (!empty ($v['auditDeptCode']))
                        $deptIds .= $v['auditDeptCode'] . ",";
                }
                $deptIds = rtrim($deptIds, ',');
                return $deptIds;
            } else
                return "";
        } else {
            return "";
        }
    }

    /**
     * 获取产品里的审批部门（变更用）
     */
    function getDeptIdsByChange($result)
    {
        //查找处理 所选产品的 最高类型
        $goodsTypeIds = "";
        foreach ($result as $k => $v) {
            $goodsTypeIds .= $v[2] . ",";
        }
        $goodsIds = rtrim($goodsTypeIds, ',');
        if (!empty ($goodsIds)) {
            $sql = "select auditDeptCode from oa_goods_base_info where id in ($goodsIds)";
            $goodsA = $this->_db->getArray($sql);
            if (!empty ($goodsA)) {
                $deptIds = "";
                foreach ($goodsA as $k => $v) {
                    if (!empty ($v['auditDeptCode']))
                        $deptIds .= $v['auditDeptCode'] . ",";
                }
                $deptIds = rtrim($deptIds, ',');
                return $deptIds;
            } else
                return "";
        } else {
            return "";
        }
    }

    /**
     * 合同金额验证方法
     * @param $object
     * @return string
     */
    function checkContractMoney_d($object) {
        if (!isset($object['contractMoney']) || !isset($object['product'])) {
            return "参数传入错误";
        }
        $countMoney = 0;
        foreach ($object['product'] as $v) {
            if(isset($v['money'])){
                if(isset($v['isDelTag']) && $v['isDelTag'] != 1){
                    $countMoney = bcadd($v['money'], $countMoney, 2);
                }else if(isset($v['isDel']) && $v['isDel'] != 1){
                    $countMoney = bcadd($v['money'], $countMoney, 2);
                }else if(!isset($v['isDelTag']) && !isset($v['isDel'])){
                    $countMoney = bcadd($v['money'], $countMoney, 2);
                }
            }else{
                $countMoney = "noChk";
            }
        }
        if ($countMoney == $object['contractMoney'] || $countMoney == "noChk") {
            return "";
        } else {
            return "合同金额与明细不匹配，请修正后再提交。";
        }
    }

    /**
     * 重写add_d方法
     */
    function add_d($object, $confirm, $invoiceJsonData = '')
    {
        $object['invoiceCode'] = isset($object['invoiceCode']) ? implode(",", $object['invoiceCode']) : '';
        $object['invoiceValue'] = isset($object['invoiceValue']) ? implode(",", $object['invoiceValue']) : '';
        try {
            $this->start_d();

            //获取区域扩展字段值
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($object['areaCode']);

            //处理付款条件
            $object = $this->handlePaymentData($object);

            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict();
            if (isset($object['contractNature'])) {
                $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            }
            if (isset($object['contractType'])) {
                $object['contractTypeName'] = $datadictDao->getDataNameByCode($object['contractType']);
            }
            if (isset($object['invoiceType'])) {
                $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            }
            if (isset($object['customerType'])) {
                $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
            }
            if (isset($object['module'])) {
                $object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);
            }
            if (ORDER_INPUT == '1') {
                //合同编号
                $codeRule = new model_common_codeRule();
                $object['contractCode'] = $codeRule->newContractCode($object);
            }
            //业务编号
            if (!empty ($object['prinvipalId']) && !empty($object['contractType'])) {
                $prinvipalId = $object['prinvipalId'];
                $orderCodeDao = new model_common_codeRule();
                $deptDao = new model_deptuser_dept_dept();
                $dept = $deptDao->getDeptByUserId($prinvipalId);
                $object['objCode'] = $orderCodeDao->getObjCode($object['contractType'], $dept['Code']);
            }
            //查找处理 所选产品的 最高类型
            $goodsTypeIds = "";
            $exeDeptNameStr = "";
            $newProLineStr = "";
            $newExeDeptStr = "";
            $xfProLineStr = ""; // 用来区分同一产线不同产品类型的产品冗余，即某某产线销售类(x)/服务类(f)产品
            if (isset($object['product'])) {
                foreach ($object['product'] as $k => $v) {
                    if ($v['isDelTag'] != "1") {
                        $goodsTypeIds .= $v['conProductId'] . ",";
                        $exeDeptNameStr .= $v['exeDeptId'] . ",";
                        $newProLineStr .= $v['newProLineCode'] . ",";
                        if ($v['newProLineCode'] == 'HTCPX-CTFW') { // 产品线为传统服务的产品，对其执行区域进行冗余
                            $newExeDeptStr .= $v['exeDeptId'] . ",";
                        }
                        if ($v['proTypeId'] == '11') { // 销售类产品
                            $xfProLineStr .= $v['newProLineCode'] . "x,";
                        } else { // 服务类产品
                            $xfProLineStr .= $v['newProLineCode'] . "f,";
                        }
                    } else if ($v['isDelTag'] == '1') {
                        unset ($object['product'][$k]);
                    }
                }
            }
            $goodsTypeIds = rtrim($goodsTypeIds, ',');
            $goodsTypeStr = "";
            $goodsTypeStrTemp = "";
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //第一次查找，过滤出本身已经是最高类别的类型
                $goodsTypeA = $this->_db->getArray($sqlA);
                foreach ($goodsTypeA as $k => $v) {
                    if ($v['parentId'] == "-1") {
                        $goodsTypeStr .= $v['id'] . ",";
                    } else {
                        $goodsTypeStrTemp .= $v['id'] . ",";
                    }
                }
                $goodsTypeStrTemp = rtrim($goodsTypeStrTemp, ',');
            }
            if (!empty ($goodsTypeStrTemp)) {
                //第二次查找，找到剩余产品的最高类别
                $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $object['goodsTypeStr'] = $goodsTypeStr;
            //产品线
            $proLineStr = "";
            if (!empty($goodsTypeIds)) {
                $sqlf = "select exeDeptCode from oa_goods_base_info where id in ($goodsTypeIds)";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                foreach ($exeDeptNameArr as $k => $v) {
                    $proLineStr .= $v['exeDeptCode'] . ",";
                }
            }
            $object['productLineStr'] = rtrim($proLineStr, ',');
            $object['exeDeptStr'] = rtrim($exeDeptNameStr, ',');
            $object['newProLineStr'] = rtrim($newProLineStr, ',');
            $object['newExeDeptStr'] = rtrim($newExeDeptStr, ',');
            $object['xfProLineStr'] = rtrim($xfProLineStr, ',');
            //判断是否需要走到执行部，并处理显示字段
            $isSellArr = explode(",", $goodsTypeStr);
            //销售
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
                $object['isSaleConfirm'] = "1";
            } else {
                $object['isSell'] = "0";
            }
            //服务
            $fStr = ESMPRODUCTTYPE;
            $serTempArr = explode(",", $fStr);
            $serF = "0";
            foreach ($serTempArr as $k => $v) {
                if (in_array($v, $isSellArr)) {
                    $serF = "1";
                }
            }
            if (($serF == "1" || $object['contractType'] == 'HTLX-FWHT') && $expand != '1') {
                $object['isEngConfirm'] = "1";
            }
            //研发
            if (in_array(RDPRODUCTTYPE, $isSellArr) && $expand != '1') {
                $object['isRdproConfirm'] = "1";
            }
            //判断是否提交
            if ($confirm == "app" && $_SESSION['DEPT_ID'] != hwDeptId) { // 海外合同的提交状态在审批流文件里处理
                $object['isSubApp'] = "1";
            }
            if (!empty ($object['contractCode'])) {
                //插入主表信息
                $newId = parent :: add_d($object, true);

                if($newId && $invoiceJsonData != ''){
                    $this->invoiceTypeRecord($newId,$invoiceJsonData);
                }

                if (!empty ($object['chanceId'])) {
                    //将商机的沟通版信息带入合同内
                    $sql = "select createName,createId,createTime,content from oa_chance_remark where chanceId = '" . $object['chanceId'] . "'";
                    $arr = $this->_db->getArray($sql);
                    if (!empty($arr)) {
                        $remarkDao = new model_contract_contract_remark();
                        $remarkDao->createBatch($arr, array(
                            'contractId' => $newId
                        ));
                    }
                }

                //插入从表信息
                //客户联系人
                if (!empty ($object['linkman'])) {
                    $linkmanDao = new model_contract_contract_linkman();
                    $linkmanDao->createBatch($object['linkman'], array(
                        'contractId' => $newId
                    ), 'linkmanName');
                }
                //合同收开计划
                if (!empty ($object['financialplan'])) {
                    $financialplanDao = new model_contract_contract_financialplan();
                    $financialplanDao->createBatch($object['financialplan'], array(
                        'contractId' => $newId
                    ), 'planDate');
                }
                //设备
                if (!empty ($object['product'])) {
                    $product = $object['product'];
                    $orderequDao = new model_contract_contract_product();
                    $orderequDao->createBatch($object['product'], array(
                        'contractId' => $newId,
                        'contractCode' => $object['contractCode']
                    ), 'conProductName');
                }
                //物料
                $equDao = new model_contract_contract_equ();
                $equTaxRate = $equDao->_defaultTaxRate;
                if (!empty($object['equ'])) {
                    foreach ($object['equ'] as &$val) { // 按17%税率，计算税后金额
                        $priceTax = bcmul($val['price'], bcadd($equTaxRate,1,2), 2);
                        $val['priceTax'] = $priceTax;
                        $val['moneyTax'] = bcmul($priceTax, $val['number'], 2);
                    }
                    $equDao->createBatch($object['equ'], array(
                        'contractId' => $newId,
                        'isBorrowToorder' => '1'
                    ));
                    if (!empty($object['product'])) {
                        foreach ($object['product'] as $k => $v) {
                            $this->updateConProductIdBybow_d($v['onlyProductId']);
                        }
                    }
                }


                //物料确认插入
                $linkDao = new model_contract_contract_contequlink();
                if (!empty($object['material'])) {
                    foreach ($object['material'] as &$val) { // 按17%税率，计算税后金额
                        $priceTax = bcmul($val['price'], bcadd($equTaxRate,1,2), 2);
                        $val['priceTax'] = $priceTax;
                        $val['moneyTax'] = bcmul($priceTax, $val['number'], 2);
                    }
                    $equDao->createBatch($object['material'], array(
                        'contractId' => $newId
                    ));
                    foreach ($object['product'] as $k => $v) {
                        $this->updateConProductId_d($v['onlyProductId']);
                    }
                    //加入关联表
                    $linkArr = array(
                        "contractId" => $newId,
                        "contractCode" => $object['contractCode'],
                        "contractName" => $object['contractName'],
                        "contractType" => 'oa_contract_contract',
                        "contractTypeName" => $object['contractTypeName'],
                        "ExaStatus" => '未提交'
                    );
                    $linkId = $linkDao->add_d($linkArr, true);
                }
                //收款计划
                $orderReceiptplanDao = new model_contract_contract_receiptplan();
                if (!empty ($object['payment'])) {
                    $orderReceiptplanDao->createBatch($object['payment'], array(
                        'contractId' => $newId
                    ), 'paymentterm');
                }
                //处理附件名称和Id
                $this->updateObjWithFile($newId);
            }
            $this->commit_d();
            $handleDao = new model_contract_contract_handle();
            //									$this->rollBack();
            //判断是否需要到工程部确认 成本概算 in_array(ESMPRODUCTTYPE, $isSellArr) &&
            if ($confirm == "app") {
                $handleDao->handleAdd_d(array(
                    "cid" => $newId,
                    "stepName" => "合同录入",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                //判断是否为海外部门提交的合同
                if ($_SESSION['DEPT_ID'] == hwDeptId) {
                    return $newId;
                }
                $handleDao->handleAdd_d(array(
                    "cid" => $newId,
                    "stepName" => "提交成本确认",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                if ($expand == '1') {
                    if ($object['isSaleConfirm'] == "1") {
                        return "confirm";
                    } else {
                        return $newId;
                    }
                }
                $tomail = $this->costConUserIdBycid($object['newProLineStr']);
                $conArr = $this->get_d($newId);
                $content = array(
                    "contractCode" => $conArr['contractCode'],
                    "contractName" => $object['contractName'],
                    "customerName" => $object['customerName']
                );
                $this->mailDeal_d("contractCost_Confirm", $tomail, $content);
                return "confirm";
            } else {
                $handleDao->handleAdd_d(array(
                    "cid" => $newId,
                    "stepName" => "合同录入",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                return $newId;
            }

        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据产品线获取 产品线成本确认人
     */
    function  costConUserIdBycid($productLineStr)
    {
        $productLineArr = explode(",", $productLineStr);
        if (is_array($productLineArr)) {
            $allArr = array();
            foreach ($productLineArr as $k => $v) {
                $sql = "select userid from purview_info where typeid = '298' and FIND_IN_SET('" . $v . "',content)";
                $arr = $this->_db->getArray($sql);
                if (!empty($arr))
                    $allArr = array_merge($allArr, $arr);
            }
        }
        if (!empty($allArr)) {
            $tomailStr = "";
            foreach ($allArr as $k => $v) {
                $tomailStr .= $v['userid'] . ",";
            }
        }
        return $tomailStr;
    }

    /**
     * 重写编辑方法
     */
    function edit_d($object, $confirm)
    {
        $object['invoiceCode'] = isset($object['invoiceCode']) ? implode(",", $object['invoiceCode']) : '';
        $object['invoiceValue'] = isset($object['invoiceValue']) ? implode(",", $object['invoiceValue']) : '';
        try {
            $this->start_d();

            $conId = $object['id'];// 合同ID

            //获取区域扩展字段值
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($object['areaCode']);

            //处理付款条件
            $object = $this->handlePaymentData($object);
            $oldArr = $this->get_d($object['id']);

            //更新dealStatus为0，以防止dealStatus不为0的情况，如撤销审批后
            if ($oldArr['dealStatus'] != '0') {
                $object['dealStatus'] = '0';
            }

            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict();
            if (isset($object['contractNature'])) {
                $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            }
            if (isset($object['contractType'])) {
                $object['contractTypeName'] = $datadictDao->getDataNameByCode($object['contractType']);
            }
            if (isset($object['invoiceType'])) {
                $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            }
            if (isset($object['customerType'])) {
                $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
            }
            if (isset($object['module'])) {
                $object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);
            }
            //2017-2-4 PMS 2424  一旦合同编号生成，无论合同是否生效，再次编辑/变更影响编号的内容，合同编号都不变
//            if (ORDER_INPUT == '1' && $object['customerId'] != '1058') { //排除掉海外的合同，海外的合同修改不产生新的合同编号
//                //合同编号
//                $codeRule = new model_common_codeRule();
//                $object['contractCode'] = $codeRule->newContractCode($object);
//            }
            //业务编号
            if (empty($oldArr['objCode']) && !empty ($object['prinvipalId']) && !empty($object['contractType'])) {
                $prinvipalId = $object['prinvipalId'];
                $orderCodeDao = new model_common_codeRule();
                $deptDao = new model_deptuser_dept_dept();
                $dept = $deptDao->getDeptByUserId($prinvipalId);
                $object['objCode'] = $orderCodeDao->getObjCode($object['contractType'], $dept['Code']);
            }
            //查找处理 所选产品的 最高类型
            $goodsTypeIds = "";
            $exeDeptNameStr = "";
            $newProLineStr = "";
            $newExeDeptStr = "";
            $xfProLineStr = ""; // 用来区分同一产线不同产品类型的产品冗余，即某某产线销售类(x)/服务类(f)产品
            if (isset($object['product'])) {
                foreach ($object['product'] as $k => $v) {
                    if ($v['isDelTag'] != "1") {
                        $goodsTypeIds .= $v['conProductId'] . ",";
                        $goodsIdArr[] = $v['id'];
                        $exeDeptNameStr .= $v['exeDeptId'] . ",";
                        $newProLineStr .= $v['newProLineCode'] . ",";
                        if ($v['newProLineCode'] == 'HTCPX-CTFW') { // 产品线为传统服务的产品，对其执行区域进行冗余
                            $newExeDeptStr .= $v['exeDeptId'] . ",";
                        }
                        if ($v['proTypeId'] == '11') { // 销售类产品
                            $xfProLineStr .= $v['newProLineCode'] . "x,";
                        } else { // 服务类产品
                            $xfProLineStr .= $v['newProLineCode'] . "f,";
                        }
                    }
                }
            }
            $goodsTypeIds = rtrim($goodsTypeIds, ',');
            $goodsTypeStr = "";
            $goodsTypeStrTemp = "";
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //第一次查找，过滤出本身已经是最高类别的类型
                $goodsTypeA = $this->_db->getArray($sqlA);
                foreach ($goodsTypeA as $k => $v) {
                    if ($v['parentId'] == "-1") {
                        $goodsTypeStr .= $v['id'] . ",";
                    } else {
                        $goodsTypeStrTemp .= $v['id'] . ",";
                    }
                }
                $goodsTypeStrTemp = rtrim($goodsTypeStrTemp, ',');
            }
            if (!empty ($goodsTypeStrTemp)) {
                //第二次查找，找到剩余产品的最高类别
                $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $object['goodsTypeStr'] = $goodsTypeStr;
            //产品线
            $proLineStr = "";
            if (!empty($goodsTypeIds)) {
                $sqlf = "select exeDeptCode from oa_goods_base_info where id in ($goodsTypeIds)";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                foreach ($exeDeptNameArr as $k => $v) {
                    $proLineStr .= $v['exeDeptCode'] . ",";
                }
            }
            $object['productLineStr'] = rtrim($proLineStr, ',');
            $object['exeDeptStr'] = rtrim($exeDeptNameStr, ',');
            $object['newProLineStr'] = rtrim($newProLineStr, ',');
            $object['newExeDeptStr'] = rtrim($newExeDeptStr, ',');
            $object['xfProLineStr'] = rtrim($xfProLineStr, ',');
            //判断是否需要走到执行部，并处理显示字段
            $isSellArr = explode(",", $goodsTypeStr);
            //销售
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
                $object['isSaleConfirm'] = "1";
            } else {
                $object['isSell'] = "0";
                $object['isSaleConfirm'] = "0";
            }
            //服务
            $fStr = ESMPRODUCTTYPE;
            $serTempArr = explode(",", $fStr);
            $serF = "0";
            foreach ($serTempArr as $k => $v) {
                if (in_array($v, $isSellArr)) {
                    $serF = "1";
                }
            }
            if (($serF == "1" || $object['contractType'] == 'HTLX-FWHT') && $expand != '1') {
                $object['isEngConfirm'] = "1";
            } else {
                $object['isEngConfirm'] = "0";
            }
            //研发
            if (in_array(RDPRODUCTTYPE, $isSellArr)) {
                $object['isRdproConfirm'] = "1";
            } else {
                $object['isRdproConfirm'] = "0";
            }
            //判断是否提交
            if ($confirm == "app" && $_SESSION['DEPT_ID'] != hwDeptId) { // 海外合同的提交状态在审批流文件里处理
                $object['isSubApp'] = "1";
            }

            //修改主表信息
            parent :: edit_d($object, true);
            $orderId = $object['id'];

            //插入从表信息
            //客户联系人
            if (!empty ($object['linkman'])) {
                $linkmanDao = new model_contract_contract_linkman();
                $linkmanDao->delete(array(
                    'contractId' => $orderId
                ));
                foreach ($object['linkman'] as $k => $v) {
                    if ($v['isDelTag'] == '1') {
                        unset ($object['linkman'][$k]);
                    }
                }
                $linkmanDao->createBatch($object['linkman'], array(
                    'contractId' => $orderId
                ), 'linkmanName');
            }
            //设备
            if (!empty ($object['product'])) {
                $productDao = new model_contract_contract_product();
                $itemsArr = util_arrayUtil :: setItemMainId("contractId", $orderId, $object['product']);
                $itemsObj = $productDao->ProSaveDelBatch($itemsArr);
            }
            //			$orderequDao->delete(array (
            //				'contractId' => $orderId
            //			));
            //			foreach ($object['product'] as $k => $v) {
            //				if ($v['isDelTag'] == '1') {
            //					unset ($object['product'][$k]);
            //				}
            //			}
            //			$orderequDao->createBatch($object['product'], array (
            //				'contractId' => $orderId
            //			), 'conProductName');
            //收开计划
            if (!empty($object['financialplan'])) {
                $orderequDao = new model_contract_contract_financialplan();
                $orderequDao->delete(array(
                    'contractId' => $orderId
                ));
                foreach ($object['financialplan'] as $k => $v) {
                    if ($v['isDelTag'] == '1') {
                        unset ($object['financialplan'][$k]);
                    }
                }
                $orderequDao->createBatch($object['financialplan'], array(
                    'contractId' => $orderId
                ), 'planDate');
            }
            //针对海外合同编辑处理，海外的合同编辑不处理物料信息（暂时处理）
            $conArr = $this->get_d($object['id']);
            $equDao = new model_contract_contract_equ();
            $equTaxRate = $equDao->_defaultTaxRate;
            if (empty($conArr['parentName'])) {
                if (!empty ($object['equ']) || !empty ($object['material'])) {
                    $equDao->delete(array(
                        'contractId' => $orderId
                    ));
                }
                //借试用转销售物料
                if (!empty ($object['equ'])) {
                    foreach ($object['equ'] as $k => $v) {
                        if ($v['isDelTag'] == '1') {
                            unset ($object['equ'][$k]);
                        } else { // 按17%税率，计算税后金额
                            $priceTax = bcmul($v['price'], bcadd($equTaxRate,1,2), 2);
                            $object['equ'][$k]['priceTax'] = $priceTax;
                            $object['equ'][$k]['moneyTax'] = bcmul($priceTax, $v['number'], 2);
                        }
                    }
                    $equDao->createBatch($object['equ'], array(
                        'contractId' => $orderId
                    ));
                    foreach ($object['product'] as $k => $v) {
                        $this->updateConProductIdBybow_d($v['onlyProductId']);
                    }
                }
                //物料确认
                //物料关联处理，如果存在关联记录，则要删掉，不然撤销审批后再提交，交付无法进行确认物料操作
                $linkDao = new model_contract_contract_contequlink();
                $linkDao->delete(array(
                    'contractId' => $orderId
                ));
                if (!empty ($object['material'])) {
                    foreach ($object['material'] as &$val) { // 按17%税率，计算税后金额
                        $priceTax = bcmul($val['price'], bcadd($equTaxRate,1,2), 2);
                        $val['priceTax'] = $priceTax;
                        $val['moneyTax'] = bcmul($priceTax, $val['number'], 2);
                    }
                    $equDao->createBatch($object['material'], array(
                        'contractId' => $orderId
                    ));
                    foreach ($object['product'] as $k => $v) {
                        $this->updateConProductId_d($v['onlyProductId']);
                    }
                    //加入关联表
                    $linkArr = array(
                        "contractId" => $orderId,
                        "contractCode" => $object['contractCode'],
                        "contractName" => $object['contractName'],
                        "contractType" => 'oa_contract_contract',
                        "contractTypeName" => $object['contractTypeName'],
                        "ExaStatus" => '未提交'
                    );
                    $linkId = $linkDao->add_d($linkArr, true);
                }
            }
            if (!empty($object['payment'])) {
                //收款计划
                $orderReceiptplanDao = new model_contract_contract_receiptplan();
                $orderReceiptplanDao->delete(array(
                    'contractId' => $orderId
                ));
                foreach ($object['payment'] as $k => $v) {
                    if ($v['isDelTag'] == '1') {
                        unset ($object['payment'][$k]);
                    }
                }
                $orderReceiptplanDao->createBatch($object['payment'], array(
                    'contractId' => $orderId
                ), 'paymentterm');
            }
            //处理删除产品后遗留的关联物料
            $productDao = new model_contract_contract_product();
            $pArr = $productDao->getDetail_d($orderId);
            $eArr = $equDao->getDetail_d($orderId);

            foreach ($pArr as $v) {
                $pidArr[] = $v['id'];
            }
            foreach ($eArr as $v) {
                if (!in_array($v['conProductId'], $pidArr) && !in_array($v['proId'], $pidArr) && $v['conProductId']>0) {
                    $equDao->delete(array('id' => $v['id']));
                }
            }
            $deleteSql = "delete from oa_contract_equ where contractId  = {$conId} and isDel = 1 and onlyProductId = '';";
            $this->_db->query( $deleteSql );

            $this->commit_d();
            $handleDao = new model_contract_contract_handle();
            //判断是否需要到工程部确认 成本概算 in_array(ESMPRODUCTTYPE, $isSellArr) &&
            if ($confirm == "app") {
                //删除成本记录
                $constSql = "delete from oa_contract_cost where contractId = '" . $orderId . "'";
                $this->_db->query($constSql);
                $handleDao->handleAdd_d(array(
                    "cid" => $orderId,
                    "stepName" => "合同编辑",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                //判断是否为海外部门提交的合同
                if ($_SESSION['DEPT_ID'] == hwDeptId) {
                    return $orderId;
                }
                $handleDao->handleAdd_d(array(
                    "cid" => $orderId,
                    "stepName" => "提交成本确认",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                if ($expand == '1') {
                    if ($object['isSaleConfirm'] == "1") {
                        return "confirm";
                    } else {
                        return $orderId;
                    }
                }
                $tomail = $this->costConUserIdBycid($object['newProLineStr']);
                $content = array(
                    "contractCode" => $conArr['contractCode'],
                    "contractName" => $object['contractName'],
                    "customerName" => $object['customerName']
                );
                $this->mailDeal_d("contractCost_Confirm", $tomail, $content);
                return "confirm";
            } else {
                $handleDao->handleAdd_d(array(
                    "cid" => $orderId,
                    "stepName" => "合同编辑",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                return $orderId;
            }

        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重写编辑方法
     * 仅提供海外使用
     */
    function hwedit_d($object)
    {
        $object['invoiceCode'] = implode(",", $object['invoiceCode']);
        $object['invoiceValue'] = implode(",", $object['invoiceValue']);
        try {
            $this->start_d();
            //处理付款条件
            $object = $this->handlePaymentData($object);
            $oldArr = $this->get_d($object['id']);
            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict();
            $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            $object['contractTypeName'] = $datadictDao->getDataNameByCode($object['contractType']);
            $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);

            if (ORDER_INPUT == '1' && $object['businessBelong'] != $oldArr['businessBelong']
                && $object['customerId'] != '1058'
            ) { //排除掉海外的合同，海外的合同修改不产生新的合同编号
                //合同编号
                $codeRule = new model_common_codeRule();
                $object['contractCode'] = $codeRule->newContractCode($object);
            }
            //查找处理 所选产品的 最高类型
            $goodsTypeIds = "";
            $exeDeptNameStr = "";
            foreach ($object['product'] as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    $goodsTypeIds .= $v['conProductId'] . ",";
                    $goodsIdArr[] = $v['id'];
                    $exeDeptNameStr .= $v['exeDeptId'] . ",";
                }
            }
            $goodsTypeIds = rtrim($goodsTypeIds, ',');
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //第一次查找，过滤出本身已经是最高类别的类型
                $goodsTypeA = $this->_db->getArray($sqlA);
                $goodsTypeStr = "";
                $goodsTypeStrTemp = "";
                foreach ($goodsTypeA as $k => $v) {
                    if ($v['parentId'] == "-1") {
                        $goodsTypeStr .= $v['id'] . ",";
                    } else {
                        $goodsTypeStrTemp .= $v['id'] . ",";
                    }
                }
                $goodsTypeStrTemp = rtrim($goodsTypeStrTemp, ',');
            }
            if (!empty ($goodsTypeStrTemp)) {
                //第二次查找，找到剩余产品的最高类别
                $sqlB = "
					               select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $object['goodsTypeStr'] = $goodsTypeStr;
            //产品线
            $sqlf = "select exeDeptCode from oa_goods_base_info where id in ($goodsTypeIds)";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $proLineStr = "";
            foreach ($exeDeptNameArr as $k => $v) {
                $proLineStr .= $v['exeDeptCode'] . ",";
            }
            $object['productLineStr'] = $proLineStr;
            $object['exeDeptStr'] = $exeDeptNameStr;
            //判断是否需要走到执行部，并处理显示字段
            $isSellArr = explode(",", $goodsTypeStr);
            //销售
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
                $object['isSaleConfirm'] = "1";
            } else {
                $object['isSell'] = "0";
                $object['isSaleConfirm'] = "0";
            }
            //服务
            $fStr = ESMPRODUCTTYPE;
            $serTempArr = explode(",", $fStr);
            $serF = "0";
            foreach ($serTempArr as $k => $v) {
                if (in_array($v, $isSellArr)) {
                    $serF = "1";
                }
            }
            //获取区域扩展字段值
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($object['areaCode']);
            if (($serF == "1" || $object['contractType'] == 'HTLX-FWHT') && $expand != '1') {
                $object['isEngConfirm'] = "1";
            } else {
                $object['isEngConfirm'] = "0";
            }
            //研发
            if (in_array(RDPRODUCTTYPE, $isSellArr)) {
                $object['isRdproConfirm'] = "1";
            } else {
                $object['isRdproConfirm'] = "0";
            }
            //修改主表信息
            parent :: edit_d($object, true);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 关闭合同方法
     */
    function close_d($object, $isEditInfo = false)
    {
        if ($isEditInfo) {
            $object = $this->addUpdateInfo($object);
        }
        //加入数据字典处理 add by chengl 2011-05-15
        $this->processDatadict($object);

        $feeType='<br/>本合同执行异常关闭后，所产生的成本处理 ：';
        if($object['fee'] == '0'){
            $feeType .= "转入销售费用";
        }else if($object['fee'] == '1'){
            $feeType .= "合并于新合同  ".$object['contractCode'];
        }
        unset($object['fee']);
        unset($object['contractCode']);
        $object['closeRegard'] .= $feeType;

        return $this->updateById($object);
    }

    /**
     * 不需要审批变更处理（无产品.金额变更）
     */
    function changeNotApp_d($object, $invoiceJsonData = '')
    {
        $object['invoiceCode'] = implode(",", $object['invoiceCode']);
        $object['invoiceValue'] = implode(",", $object['invoiceValue']);
//            	echo "<pre>";
//        		print_r($invoiceArrs);
//        		die();
        try {

            //处理付款条件
            $object = $this->handlePaymentData($object);
            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict();
            $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            $object['contractTypeName'] = $datadictDao->getDataNameByCode($object['contractType']);
            $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
            $object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);
            unset($object['product']);
            unset($object['equ']);

            //变更处理
            $changeLogDao = new model_common_changeLog('contract', false);

            $changeLogDao->addLog($object);
            //修改主表信息
            parent :: edit_d($object, true);
            $orderId = $object['id'];

            // 记录临时记录的开票信息jsonData
            if($object['id'] && $invoiceJsonData != ''){
                $this->invoiceTypeRecord($object['id'],$invoiceJsonData);
            }

            //客户联系人
            $linkmanDao = new model_contract_contract_linkman();
            $linkmanDao->delete(array('contractId' => $orderId));
            foreach ($object['linkman'] as $k => $v) {
                if ($v['isDelTag'] == '1') {
                    unset ($object['linkman'][$k]);
                }
            }
            $linkmanDao->createBatch($object['linkman'], array('contractId' => $orderId), 'linkmanName');

            //收开计划
            $orderequDao = new model_contract_contract_financialplan();
            $orderequDao->delete(array('contractId' => $orderId));
            foreach ($object['financialplan'] as $k => $v) {
                if ($v['isDelTag'] == '1') {
                    unset ($object['financialplan'][$k]);
                }
            }
            $orderequDao->createBatch($object['financialplan'], array('contractId' => $orderId), 'planDate');

            //付款条件 add by chenrf
            $receiptplan = new model_contract_contract_receiptplan();
            $payment = $object['payment'];
            foreach ($payment as $k => $v) {

                if ($v['isDelTag'] == '1') {
                    $receiptplan->updateField(array('id' => $v['id']), 'isDel', 1);
                    unset ($object['payment'][$k]);
                }
                if ($v['id']) {
                    unset ($object['payment'][$k]);
                }
            }
            if (!empty($object['payment'])) ;
            $receiptplan->createBatch($object['payment'], array('contractId' => $orderId));

            //删除本次加载的临时变更记录(若有)
            if (!empty($object['tempId'])) {
                $sql = "select id,ExaStatus from oa_contract_changlog where objType = 'contract' and tempId=" . $object['tempId'];
                $rs = $this->_db->getArray($sql);
                if (!empty($rs)) {
                    //取消加载变更记录，物料确认打回/变更审批打回的变更记录不删除
                    if (($rs[0]['ExaStatus'] != '物料确认打回' && $rs[0]['ExaStatus'] != '打回')
                        || (($rs[0]['ExaStatus'] == '物料确认打回' || $rs[0]['ExaStatus'] == '打回') && $object['id'] != $object['contractId'])
                    ) {
                        $delSql = "delete from oa_contract_changedetail where parentId=" . $rs[0]['id'];
                        $this->_db->query($delSql);
                        $delSql = "delete from oa_contract_changlog where objType = 'contract' and tempId=" . $object['tempId'];
                        $this->_db->query($delSql);
                    }
                }
            }
            //更新合同变更次数
            $becomeNumSql = "UPDATE oa_contract_contract c
				            LEFT JOIN (
					            SELECT
					            	COUNT(*) AS num,
					            	objId
					            FROM
					            	oa_contract_changlog
					            WHERE
					            	objId = {$object['id']}
				            	GROUP BY
									objId
					            ) g ON c.id = g.objId
				            SET c.becomeNum = g.num
				            WHERE c.id = {$object['id']}";
            $this->query($becomeNumSql);
            // 借试用转销售关联商机处理
            $obj = $this->get_d($object['id']);
            if (!empty($object['turnChanceIds'])) {
                $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    	`status` = '4',rObjCode = '" . $obj['objCode'] . "',
                    	contractCode = '" . $obj['contractCode'] . "'
                    	WHERE id IN(" . $obj['turnChanceIds'] . ")";
                $this->_db->query($sql);
            }

            // 借试用转销售关联物料处理 create by huanghaojin (借试用转销售必须走审批，所以不会来到这里)
//            $equDao = new model_contract_contract_equ();
//            $productDao = new model_contract_contract_product();
//            if(!empty($object['material'])){
//                foreach ($object['material'] as $k => $v) {
//                    if($object['material'][$k]['id']!=''){//删除原物料
//                        unset($object['material'][$k]);
//                    }else{//处理新物料
//                        // 按17%税率，计算税后金额
//                        $priceTax = bcmul($object['material'][$k]['price'], '1.17', 2);
//                        $object['material'][$k]['priceTax'] = $priceTax;
//                        $object['material'][$k]['moneyTax'] = bcmul($priceTax, $object['material'][$k]['number'], 2);
//
//                        // 补上相关的合同信息
//                        $object['material'][$k]['contractTypeName'] = $object['contractTypeName'];
//                        $object['material'][$k]['contractType'] = $object['contractType'];
//                        $object['material'][$k]['contractName'] = $object['contractName'];
//
//                        // 补上相关的产品信息
//                        $con = array(
//                            'onlyProductId' => $object['material'][$k]['onlyProductId'],
//                            'contractId' => $object['contractId']
//                        );
//                        $query_data = $productDao->find($con,'',"id,version");
//                        $object['material'][$k]['conProductId'] = '0';//$query_data['id'];
//                        $object['material'][$k]['version'] = $query_data['version'];
//                    }
//                }
//
//                //批量添加
//                $equDao->createBatch($object['material'], array (
//                    'contractId' => $object['contractId']
//                ));
//                foreach($object['material'] as $k=>$v){
//                    $this->updateConProductIdBybow_d($v['onlyProductId']);
//                }
//            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 变更销售合同
     */
    function change_d($obj, $isMoney, $isbo, $invoiceJsonData = '')
    {
        $obj['contractMoney'] = sprintf("%.2f", $obj['contractMoney']);
        if (!empty($obj['material'])) { //用于销售删除借试用转销售物料后配件的处理
            $equDao = new model_contract_contract_equ();
            $equs = $obj['material'];
            foreach ($equs as $val) {
                if (!empty($val['id']) && isset($val['isDelTag']) && $val['isDelTag'] == '1') {
                    $rs = $equDao->findAll(array('parentEquId' => $val['id'], 'isBorrowToorder' => '1', 'isTemp' => '0', 'isDel' => '0'));
                    if (!empty($rs)) {
                        foreach ($rs as $v) {
                            unset($v['isDel']);
                            $v['isDelTag'] = '1';
                            array_push($obj['material'], $v);
                        }
                    }
                }
            }
        }
        //合并物料和借用转销售数组
        if (!empty($obj['equ']) && !empty($obj['material'])) {
            $obj['equ'] = array_merge($obj['equ'], $obj['material']);
            unset($obj['material']);
        } else if (empty($obj['equ']) && !empty($obj['material'])) {
            $obj['equ'] = $obj['material'];
            unset($obj['material']);
        }

        $obj['invoiceCode'] = implode(",", $obj['invoiceCode']);
        $obj['invoiceValue'] = implode(",", $obj['invoiceValue']);
        try {
            $this->start_d();
            //处理付款条件
//          $obj = $this->handlePaymentData($obj);

            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict();
            if (!empty($obj['contractNature'])) {
                $obj['contractNatureName'] = $datadictDao->getDataNameByCode($obj['contractNature']);
            }
            if (!empty($obj['contractType'])) {
                $obj['contractTypeName'] = $datadictDao->getDataNameByCode($obj['contractType']);
            }
            if (!empty($obj['invoiceType'])) {
                $obj['invoiceTypeName'] = $datadictDao->getDataNameByCode($obj['invoiceType']);
            }
            if (!empty($obj['customerType'])) {
                $obj['customerTypeName'] = $datadictDao->getDataNameByCode($obj['customerType']);
            }
            if (!empty($obj['module'])) {
                $obj['moduleName'] = $datadictDao->getDataNameByCode($obj['module']);
            }

            //查找处理 所选产品的 最高类型
            $goodsTypeIds = "";
            $exeDeptNameStr = "";
            $newProLineStr = "";
            $newExeDeptStr = "";
            $xfProLineStr = ""; // 用来区分同一产线不同产品类型的产品冗余，即某某产线销售类(x)/服务类(f)产品
            foreach ($obj['product'] as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    $goodsTypeIds .= $v['conProductId'] . ",";
                    $exeDeptNameStr .= $v['exeDeptId'] . ",";
                    $newProLineStr .= $v['newProLineCode'] . ",";

//                    if(isset($v['exeDeptId']) && $v['exeDeptId'] != '' && (!isset($v['exeDeptName']) || (isset($v['exeDeptName']) && $v['exeDeptName'] == ''))){
//                        $exeDeptName = $datadictDao->getDataNameByCode($v['exeDeptId']);// 根据选中的执行区域ID重新匹配中文名 PMS2741
//                        if($exeDeptName && !empty($exeDeptName)){
//                            $obj['product'][$k]['exeDeptName'] = $exeDeptName;
//                        }else{// 如果缓存数组中没能读到相关的执行区域名称,则去销售负责人管理表内查
//                            $salepersonDao = new model_system_saleperson_saleperson();
//                            $exeDeptArr = $salepersonDao->find(array("exeDeptCode" => $v['exeDeptId']));
//                            if($exeDeptArr && !empty($exeDeptArr)){
//                                $obj['product'][$k]['exeDeptName'] = $exeDeptArr['exeDeptName'];
//                            }
//                        }
//                    }

                    // 之前做了相关验证后才更新字段值,现在只要查到匹配的直接替换 PMS 2848
                    if(isset($v['exeDeptId']) && $v['exeDeptId'] != ''){
                        $salepersonDao = new model_system_saleperson_saleperson();
                        $exeDeptSql = "select c.dataName,c.dataCode from oa_system_datadict c where 1=1 and(( c.parentCode = 'GCSCX')) and c.dataCode = '{$v['exeDeptId']}';";
                        $exeDeptArr = $salepersonDao->_db->get_one($exeDeptSql);
                        if($exeDeptArr && !empty($exeDeptArr)){
                            $obj['product'][$k]['exeDeptName'] = $exeDeptArr['dataName'];
                        }
                    }

                    if ($v['newProLineCode'] == 'HTCPX-CTFW') { // 产品线为传统服务的产品，对其执行区域进行冗余
                        $newExeDeptStr .= $v['exeDeptId'] . ",";
                    }
                    if ($v['proTypeId'] == '11') { // 销售类产品
                        $xfProLineStr .= $v['newProLineCode'] . "x,";
                    } else { // 服务类产品
                        $xfProLineStr .= $v['newProLineCode'] . "f,";
                    }
                }
                $obj['product'][$k]['money'] = sprintf("%.2f", $v['money']);
            }
            $goodsTypeIds = rtrim($goodsTypeIds, ',');
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //第一次查找，过滤出本身已经是最高类别的类型
                $goodsTypeA = $this->_db->getArray($sqlA);
                $goodsTypeStr = "";
                $goodsTypeStrTemp = "";
                foreach ($goodsTypeA as $k => $v) {
                    if ($v['parentId'] == "-1") {
                        $goodsTypeStr .= $v['id'] . ",";
                    } else {
                        $goodsTypeStrTemp .= $v['id'] . ",";
                    }
                }
                $goodsTypeStrTemp = rtrim($goodsTypeStrTemp, ',');
            }
            if (!empty ($goodsTypeStrTemp)) {
                //第二次查找，找到剩余产品的最高类别
                $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $obj['goodsTypeStr'] = $goodsTypeStr;
            //产品线
            $sqlf = "select exeDeptCode from oa_goods_base_info where id in ($goodsTypeIds)";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $proLineStr = "";
            foreach ($exeDeptNameArr as $k => $v) {
                $proLineStr .= $v['exeDeptCode'] . ",";
            }
            $obj['productLineStr'] = rtrim($proLineStr, ',');
            $obj['exeDeptStr'] = rtrim($exeDeptNameStr, ',');
            $obj['newProLineStr'] = rtrim($newProLineStr, ',');
            $obj['newExeDeptStr'] = rtrim($newExeDeptStr, ',');
            $obj['xfProLineStr'] = rtrim($xfProLineStr, ',');
            //判断是否需要走到执行部，并处理显示字段
            $isSellArr = explode(",", $goodsTypeStr);
            if (in_array(isSell, $isSellArr) || (empty ($obj['product']) && !empty ($obj['equ']))) {
                $obj['isSell'] = "1";
                $obj['isSellchange'] = "1";
            } else {
                $obj['isSell'] = "0";
                $obj['isSellchange'] = "0";
            }
            //获取区域扩展字段值
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($obj['areaCode']);
            if ($isMoney == '0' && $obj['isSub'] == '1') { //提交时执行
                if ($expand != '1') {
                    $this->update(array("id" => $obj['id']), array("isSubAppChange" => "1"));
                    $this->update(array("id" => $obj['id']), array("isSubApp" => "1"));
                }
                //海外部门提交的合同变更无须处理成本确认信息
                if ($_SESSION['DEPT_ID'] != hwDeptId && $obj['isSub'] == '1') {
                    //销售
                    if (in_array(isSell, $isSellArr)) {
                        if ($expand == '1') {
                            $this->update(array("id" => $obj['id']), array("dealStatus" => "2"));
                        } else {
                            if ($isbo != 'tobo') {
                                $this->update(array("id" => $obj['id']), array("dealStatus" => "2"));
                            }
                            $this->update(array("id" => $obj['id']), array("isSaleConfirm" => "1"));
                            $this->update(array("id" => $obj['id']), array("saleConfirm" => "0"));
                            $this->update(array("id" => $obj['id']), array("isSell" => "1"));
                            $this->update(array("id" => $obj['id']), array("isSellchange" => "1"));
                        }
                    } else {
                        $this->update(array("id" => $obj['id']), array("isSaleConfirm" => "0"));
                        $this->update(array("id" => $obj['id']), array("saleConfirm" => "0"));
                        $this->update(array("id" => $obj['id']), array("isSell" => "0"));
                        $this->update(array("id" => $obj['id']), array("isSellchange" => "0"));
                    }
                    //服务
                    if ((in_array(ESMPRODUCTTYPE, $isSellArr) || $obj['contractType'] == 'HTLX-FWHT') && $expand != '1') {
                        $this->update(array("id" => $obj['id']), array("isEngConfirm" => "1"));
                        $this->update(array("id" => $obj['id']), array("engConfirm" => "0"));
                    } else {
                        $this->update(array("id" => $obj['id']), array("isEngConfirm" => "0"));
                        $this->update(array("id" => $obj['id']), array("engConfirm" => "0"));
                    }
                    //研发
                    if (in_array(RDPRODUCTTYPE, $isSellArr)) {
                        $this->update(array("id" => $obj['id']), array("isRdproConfirm" => "1"));
                        $this->update(array("id" => $obj['id']), array("rdproConfirm" => "0"));
                    } else {
                        $this->update(array("id" => $obj['id']), array("isRdproConfirm" => "0"));
                        $this->update(array("id" => $obj['id']), array("rdproConfirm" => "0"));
                    }
                }
            }

            //1.海外部门提交的合同变更无须处理成本确认信息
            //2.保存的合同变更无须处理成本确认信息
            if ($_SESSION['DEPT_ID'] != hwDeptId && $obj['isSub'] == '1') {
                //变更重置产品线成本确认状态
                $costDao = new model_contract_contract_cost();
                $costDao->resetStateByCid($obj);
            }
            //更新原合同内变更后的产品线冗余值， 当删除产品且审批打回后，会有bug，暂时这么处理
            if ($obj['isSub'] == '1') { //提交时执行
                $this->update(array("id" => $obj['id']), array("goodsTypeStr" => $obj['goodsTypeStr'], "exeDeptStr" => $obj['exeDeptStr'], "newProLineStr" => $obj['newProLineStr']));
            }
            //变更附件处理，暂时只处理加密区文件
            $changeLogDao = new model_common_changeLog('contract');
            //             $obj ['uploadFiles'] = $changeLogDao->processUploadFile ( $obj, $this->tbl_name );
            if ($obj['id'] != $obj['contractId']) { //兼容临时变更合同
                $obj['oldId'] = $obj['contractId'];
            }
            $obj['uploadFiles'] = $changeLogDao->processUploadFile($obj, 'oa_contract_contract2');
            if ($obj['id'] != $obj['contractId']) { //临时变更合同附件处理
                foreach ($obj['uploadFiles'] as $k => $v) {
                    if ($v['originalId'] == '0') { //相对源单来说是新增的附件
                        unset($obj['uploadFiles'][$k]['oldId']);
                    } else {
                        $obj['uploadFiles'][$k]['oldId'] = $obj['uploadFiles'][$k]['originalId'];
                    }
                }
            }
            //变更记录,拿到变更的临时主对象id
            $obj['oldId'] = $obj['id'];
            $forArr = array(
                "linkman",
                "product",
                "equ",
                "invoice",
                "income",
                "train",
                "payment",
                "invoiceType"
            );
            if (!empty($obj['equ'])) {
                foreach ($obj['equ'] as $key => $val) {
                    if (empty($val['productCode']) || empty($val['productId']) || empty($val['productName'])) {
                        unset($obj['equ'][$key]);
                    }
                }
            }
            if (isset($obj['tempId']) && $obj['contractId'] != $obj['oldId']) { //用于加载了临时保存记录后处理
                //合并临时保存记录删除掉的数据
                $tempObj = $this->getContractInfoAll($obj['tempId']);
                foreach ($forArr as $key => $val) {
                    foreach ($tempObj[$val] as $v) {
                        if ($v['isDel'] == '1') {
                            if (!isset($obj[$val])) {
                                $obj[$val] = array();
                            }
                            array_push($obj[$val], $v);
                        }
                    }
                }
                foreach ($forArr as $key => $val) {
                    foreach ($obj[$val] as $k => $v) {
                        $obj[$val][$k]['oldId'] = empty($obj[$val][$k]['originalId']) ? '0' : $obj[$val][$k]['originalId']; //从表的originalId对应源单的id
                    }
                }
            } else {
                foreach ($forArr as $key => $val) {
                    foreach ($obj[$val] as $k => $v) {
                        $obj[$val][$k]['oldId'] = $obj[$val][$k]['id'];
                    }
                }
            }
            $tempObjId = $changeLogDao->addLog($obj);
            //license变更处理(...)

            // 记录临时记录的开票信息jsonData
            if($tempObjId && $invoiceJsonData != ''){
                $this->invoiceTypeRecord($tempObjId,$invoiceJsonData);
            }

            //插入临时记录后，处理临时记录物料的conProductId ID2225 2016-11-21
            $this->reloadEquByTempObjId($tempObjId,$obj);

            //插入临时记录后，处理临时记录 借用转销售的proId
            $tempConArr = $this->getContractInfoWithTemp($tempObjId, null, 1);

            if (!empty($tempConArr['product'])) {
                foreach ($tempConArr['product'] as $k => $v) {
                    $this->updateConProductIdBybow_d($v['onlyProductId']);
                }
            }
            //删除本次加载的临时变更记录(若有)
            if (!empty($obj['tempId'])) {
                $sql = "select id,ExaStatus from oa_contract_changlog where objType = 'contract' and tempId=" . $obj['tempId'];
                $rs = $this->_db->getArray($sql);
                if (!empty($rs)) {
                    //取消加载变更记录，物料确认打回/变更审批打回的变更记录不删除
                    if (($rs[0]['ExaStatus'] != '物料确认打回' && $rs[0]['ExaStatus'] != '打回')
                        || (($rs[0]['ExaStatus'] == '物料确认打回' || $rs[0]['ExaStatus'] == '打回') && $obj['id'] != $obj['contractId'])
                    ) {
                        $delSql = "delete from oa_contract_changedetail where parentId=" . $rs[0]['id'];
                        $this->_db->query($delSql);
                        $delSql = "delete from oa_contract_changlog where objType = 'contract' and tempId=" . $obj['tempId'];
                        $this->_db->query($delSql);
                    }
                }
            }
            if ($obj['isSub'] == '0' && !empty($tempObjId)) { //保存时，将临时变更记录的审批状态改为保存
                $updateSql = "update oa_contract_changlog set ExaStatus = '保存' where objType = 'contract' and tempId=" . $tempObjId;
                $this->_db->query($updateSql);
            }
            //更新合同变更次数
            $becomeNumSql = "UPDATE oa_contract_contract c
							LEFT JOIN (
								SELECT
									COUNT(*) AS num,
									objId
								FROM
									oa_contract_changlog
								WHERE
									objId = {$obj['id']}
            				    GROUP BY
									objId
							) g ON c.id = g.objId
							SET c.becomeNum = g.num
							WHERE c.id = {$obj['id']}";
            $this->query($becomeNumSql);

            $this->commit_d();
            return $tempObjId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 根据临时合同ID 更新临时物料记录的对应产品ID
     */
    function reloadEquByTempObjId($contractId,$obj){
        $selectSql = "select id,conProductId,onlyProductId from oa_contract_product where contractId = '" . $contractId . "'";
        $selObj = $this->_db->getArray($selectSql);

        if(!empty($selObj)){
            foreach($selObj as $k => $v){
                if($obj['id'] != $obj['contractId']){// 兼容直接载入上一次临时变更合同内容的情况
                    $updateSql = "update oa_contract_equ  set conProductId = '" . $v['id'] . "',isBorrowToorder = 0 where contractId = '".$contractId."'and onlyProductId = '" . $v['onlyProductId'] . "' and isBorrowToorder<>1 and originalId = 0";
                }else{
                    $updateSql = "update oa_contract_equ  set conProductId = '" . $v['id'] . "',isBorrowToorder = 0 where contractId = '".$contractId."'and onlyProductId = '" . $v['onlyProductId'] . "' and isBorrowToorder<>1 and conProductId = '" . $v['conProductId'] . "'";
                }
                $this->_db->query($updateSql);
            }
        }
    }

    /**
     * 根据合同ID 产生合同变更临时记录
     */
    function getConTempById($cid, $changeReason)
    {
        try {
            $this->start_d();

            $obj = $this->getContractInfo($cid);
            unset($obj ['isTemp']);
            unset($obj ['originalId']);
            unset($obj ['ExaStatus']);
            //变更附件处理
            $changeLogDao = new model_common_changeLog('contract');
            //变更记录,拿到变更的临时主对象id
            $obj['oldId'] = $obj['id'];
            $forArr = array(
                "linkman",
                "product",
                "equ",
                "invoice",
                "income",
                "train",
                "payment",
                "invoiceType"
            );
            if (!empty($obj['equ'])) {
                foreach ($obj['equ'] as $key => $val) {
                    if (empty($val['productCode']) || empty($val['productId']) || empty($val['productName'])) {
                        unset($obj['equ'][$key]);
                    }
                }
            }
            foreach ($forArr as $key => $val) {
                foreach ($obj[$val] as $k => $v) {
                    $obj[$val][$k]['oldId'] = $obj[$val][$k]['id'];
                }
            }
            $obj['changeReason'] = $changeReason;
            $tempObjId = $changeLogDao->addLog($obj);
            //license变更处理(...)

            $this->commit_d();
            //							$this->rollBack();
            return $tempObjId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }
    /******************************/
    /**
     * 判断是否为变更的合同
     */
    function isTemp($conId)
    {
        $cond = array(
            "id" => $conId
        );
        $isTemp = $this->find($cond, '', 'isTemp');
        $isTemp = implode(',', $isTemp);
        return $isTemp;
    }

    /**
     * 单独附件上传
     */
    function uploadfile_d($row)
    {
        try {
            //处理附件名称和Id
            $this->updateObjWithFile($row['serviceId']);
            return true;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * 根据源单、从表ID 获取未执行数量
     */
    function getDocNotExeNum($docId, $docItemId)
    {
        $dao = new model_contract_contract_equ();
        $equinfo = $dao->get_d($docItemId);
        //	 	  $equinfo['exchangeBackNum']
        $noExeNum = $equinfo['number'] + $equinfo['backNum'] - $equinfo['executedNum'];
        return $noExeNum;
    }
    /******************************/

    /**
     * 确认合同
     */
    function confirmChange($spid)
    {
        try {
            $this->start_d();
            $otherdatas = new model_common_otherdatas();
            $folowInfo = $otherdatas->getWorkflowInfo($spid);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $contract = $this->get_d($objId);
                $originalId = $contract['originalId'];
                $contractOld = $this->get_d($originalId);
                $changeLogDao = new model_common_changeLog('contract');
                $changeLogDao->confirmChange_d($contract, null);
                $handleDao = new model_contract_contract_handle();
                //更新变更确认成本概算标识
                $this->update(array("id" => $contract['originalId']), array("isSubAppChange" => "0"));
                if ($contract['ExaStatus'] == "完成") {

                    // 检查临时记录是否存在开票信息记录,有的话将其替换掉原单的开票信息记录 PMS 647
                    $chkInvoiceRecords = $this->_db->get_one("select invoiceValues from oa_contract_extfields_data where contractId = '{$objId}';");
                    if($chkInvoiceRecords && !empty($chkInvoiceRecords['invoiceValues'])){
                        $this->invoiceTypeRecord($contract['originalId'],$chkInvoiceRecords['invoiceValues']);
                    }

                    if($contract['contractMoney'] < $contractOld['contractMoney']){
                        //插入合同确认数据
                        $conFirmArr['type'] = '合同变更(减少)';
                        $conFirmArr['money'] = $contractOld['contractMoney']*1 - $contract['contractMoney']*1;
                        $conFirmArr['state'] = '未确认';
                        $conFirmArr['contractId'] = $contract['originalId'];
                        $conFirmArr['contractCode'] = $contract['contractCode'];
                        $confirmDao = new model_contract_contract_confirm();
                        $confirmDao->add_d($conFirmArr);

                    }

                    //更新项目信息
                    $proDao = new model_contract_conproject_conproject();
                    $proDao->createProjectBySale_d($contract['originalId']);

                    //更新成本信息
                    $costDao = new model_contract_contract_cost();
                    $costDao->returnStateByCid($contract['originalId'], $objId);
                    //审批通过后处理新增产品下的新增物料的关联产品ID更新
                    $conProDao = new model_contract_contract_product();
                    $conEquDao = new model_contract_contract_equ();
                    $conProInfo = $conProDao->getDetail_d($contract['originalId']);
                    $conEquInfo = $conEquDao->getDetail_d($contract['originalId']);
                    foreach ($conProInfo as $v) {
                        if ($v['changeTips'] == '2') {
                            $equUpSql = "update oa_contract_equ set conProductId='" . $v['id'] . "'
                                where contractId='" . $contract['originalId'] . "' and changeTips=2 and conProductId='" . $v['tempId'] . "'";
                            $this->query($equUpSql);
                        }
                    }
                    //初始化需要删除的物料id数组，用于重启删除物料（相当于新增物料）后，需要删除原来的物料及配件
                    $delEquIdArr = array();
                    foreach ($conEquInfo as $v) {
                        if ($v['changeTips'] == '2' && !empty($v['isCon'])) {
                            $equUpSql = "update oa_contract_equ set parentEquId='" . $v['id'] . "'
                        		where contractId='" . $contract['originalId'] . "' and changeTips=2 and parentEquId='" . $v['tempId'] . "'";
                            $this->query($equUpSql);
                        }
                        if (!empty($v['remark']) && !empty($v['isCon'])) {
                            $rs = $conEquDao->find(array('id' => $v['remark']), null, 'isDel');
                            if ($rs['isDel'] == '1') {
                                array_push($delEquIdArr, $v['remark']);
                            }
                        }
                    }
                    //删除重启前的物料及配件
                    if (!empty($delEquIdArr)) {
                        $ids = implode(',', $delEquIdArr);
                        $equDelSql = "delete from oa_contract_equ where contractId='" . $contract['originalId'] . "' and (id in($ids) or parentEquId in($ids))";
                        $this->query($equDelSql);
                        $equUpSql = "update oa_contract_equ set remark = '' where contractId='" . $contract['originalId'] . "' and remark in($ids)";
                        $this->query($equUpSql);
                    }
                    //重置签单状态
                    if ($contract['isAcquiring'] == '1') {
                        $this->ajaxAcquiring_d($contract['originalId'], '0');
                    }
                    //更新 回款条款 金额及状态
                    $this->updateIcc($contract);
                    //调用工程接口
                    $esmDao = new model_engineering_project_esmproject();
                    $esmDao->updateContractMoney_d($contract['originalId']);

                    // 合同发生变化，则加入重算项目下达状态的处理
                    $this->updateProjectStatus_d($contract['originalId']);

                    //更新合同变更次数
                    $becomeNumSql = "UPDATE oa_contract_contract c
                        LEFT JOIN (
                            SELECT COUNT(*) AS num, objId FROM oa_contract_changlog
                            WHERE objId = {$contract['originalId']} GROUP BY objId
                        ) g ON c.id = g.objId
                        SET c.becomeNum = g.num
                        WHERE c.id = {$contract['originalId']}";
                    $this->query($becomeNumSql);

                    $this->updateOutStatus_d($contract['originalId']);
                    $this->updateShipStatus_d($contract['originalId']);
                    //源单状态处理
                    if ($contract['isNeedRestamp'] == 1 && $contract['isStamp'] == 1) { //需要重新盖章
                        //直接重置盖章状态位，将现有盖章记录关闭
                        $this->update(array(
                            'id' => $contract['originalId']
                        ), array(
                            'status' => 2,
                            'isStamp' => 0,
                            'isNeedRestamp' => 0,
                            'isNeedStamp' => 0,
                            'stampType' => ''
                        ));

                    } elseif ($contract['isNeedStamp'] == 1 && $contract['isStamp'] == 0) { //正在盖章的处理

                        $this->update(array(
                            'id' => $contract['originalId']
                        ), array(
                            'status' => 2,
                            'isStamp' => 0,
                            'isNeedRestamp' => 0,
                            'isNeedStamp' => 0,
                            'stampType' => ''
                        ));

                        $stampDao = new model_contract_stamp_stamp();
                        $newId = $stampDao->closeWaiting_d($contract['originalId'], 'HTGZYD-01');
                    } else { //非盖章处理
                        $this->update(array(
                            'id' => $contract['originalId']
                        ), array(
                            'status' => 2,
                            'isNeedRestamp' => 0
                        ));
                    }
                    // 借试用转销售关联商机处理
                    if (!empty($contract['turnChanceIds'])) {
                        $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    		`status` = '4',rObjCode = '" . $contract['objCode'] . "',
                    		contractCode = '" . $contract['contractCode'] . "'
                    		WHERE id IN(" . $contract['turnChanceIds'] . ")";
                        $this->_db->query($sql);
                    }
                    //变更审批完成后发送邮件
                    //获取默认发送人
                    include(WEB_TOR . "model/common/mailConfig.php");
                    $tomail = isset($mailUser['oa_contract_change']['TO_ID']) ? $mailUser['oa_contract_change']['TO_ID'] : null;
                    $addmsg = "合同编号为为《" . $contract['contractCode'] . "》,的合同已发生变更。";
                    $emailDao = new model_common_mail();
                    $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_change", "审批", "通过", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $contract['originalId'],
                        "stepName" => "审批通过",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));

                    //更新合同产品内的合同编号
                    $updateCodeSql = "update oa_contract_product p left join oa_contract_contract c on p.contractId=c.id set p.contractCode=c.contractCode where p.contractId='".$contract['originalId']."'";
                    $this->_db->query($updateCodeSql);

                    // 审批完成后,对特殊物料的发货数量处理
                    $specialProIdArr = explode(',', specialProId);
                    $equDao = new model_contract_contract_equ();
                    $catchParentIds = array();
                    $contractEquArr = $equDao->findAll(" contractId={$contract['originalId']}");
                    if($contractEquArr){
                        foreach ($contractEquArr as $equK => $equV){
                            $updateArr = array();
                            if(in_array($equV['productId'], $specialProIdArr)){
                                $catchParentIds[] = $equV['id'];// 收集父级特殊物料ID
                            }else if(in_array($equV['parentEquId'], $catchParentIds)){// 关联配件
                                $updateArr['id'] = $equV['id'];
                                $updateArr['executedNum'] = $updateArr['issuedShipNum'] = $equV['number'];
                                $equDao->updateById($updateArr);
                            }
                        }
                    }

                    // 审批完成后,检查变更内容里是否含有金额修改,有的话在关联此合同的所有项目执行轨迹中添加历史记录
                    $chkSql = "SELECT
                        c.changeManId,
                        c.changeManName,
                        cd.oldValue,
                        cd.NewValue
                    FROM
                        oa_contract_changedetail cd
                    LEFT JOIN oa_contract_changlog c ON cd.parentId = c.id
                    WHERE
                        c.objType = 'contract'
                    AND c.objId = {$originalId}
                    AND c.tempId = {$objId}
                    AND cd.changeFieldCn = '签约金额';";
                    $chkResult = $this->_db->getArray($chkSql);
                    $relProject = $this->_db->getArray("SELECT id FROM oa_esm_project WHERE contractId = '{$originalId}'");
                    if($chkResult && $relProject){
                        $changeManId = $chkResult[0]['changeManId'];
                        $changeManName = $chkResult[0]['changeManName'];
                        $oldValue = $chkResult[0]['oldValue'];
                        $NewValue = $chkResult[0]['NewValue'];
                        $operationTime = date('Y-m-d H:i:s');

                        foreach ($relProject as $k => $v){
                            $esmlogDao = new model_engineering_baseinfo_esmlog();
                            $insertObj = array(
                                'projectId' => $v['id'], 'operationType' => "合同金额变更", 'description' => "变更前金额:【{$oldValue}】-> 变更后金额:【{$NewValue}】",
                                'userId' => $changeManId,
                                'userName' => $changeManName,
                                'operationTime' => $operationTime
                            );
                            $esmlogDao->add_d($insertObj);
                        }
                    }

                } else {
                    if (empty($folowInfo['examines'])) {
                        $handleDao->handleAdd_d(array(
                            "cid" => $contract['originalId'],
                            "stepName" => "审批通过",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                    } else {
                        $handleDao->handleAdd_d(array(
                            "cid" => $contract['originalId'],
                            "stepName" => "审批打回",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                    }
                }
            }

            // 变更完成后更新相关的产品项目
            $conprojectDao = new model_contract_conproject_conproject();
            $conProjectsArr = $conprojectDao->findAll(array("contractId" => $contract['originalId']));
            if($conProjectsArr && !empty($conProjectsArr)){
                foreach ($conProjectsArr as $conproject){
                    $conprojectDao->updateSaleProjectVal_d($conproject['projectCode']);
                }
            }

            $this->commit_d();
            //			$this->rollBack();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    //按百分比更新合同汇款 金额 （变更 用）
    function updateIcc($contract){
       $cid = $contract['originalId'];
       $cMoney = $contract['contractMoney'];
        $sql = "select * from oa_contract_receiptplan where contractId = '".$cid."'";
        $rows = $this->_db->getArray($sql);
        if(!empty($rows)){
            foreach($rows as $k=>$v){
                $tmpMoney = $cMoney * $v['paymentPer'] / 100;
                if($tmpMoney != $v['money']){
                    $updateSQL = "update oa_contract_receiptplan set isCom=0,money = '".$tmpMoney."',planInvoiceMoney = '".$tmpMoney."' where id = '".$v['id']."'";
                    $this->_db->query($updateSQL);
                }
            }
        }

    }

    /**
     * 确认合同-用于物料变更无须审批
     * @param $id
     * @param int $contractChange // 后加参数,为了分辨是从物料变更过来的还是合同变更过来的,默认0为物料变更 关联PMS2373
     */
    function confirmChangeNoAudit($id,$contractChange = 0)
    {
        try {
            $this->start_d();
            if (!empty ($id)) {
                $contract = $this->get_d($id);
                $changeLogDao = new model_common_changeLog('contract');
                $changeLogDao->confirmChange_d($contract, null);
                $handleDao = new model_contract_contract_handle();
                //			  if($contract['isSubAppChange'] == '1'){
                //			  	//修改合同确认状态
                //				$dealStatusSql = "update oa_contract_contract set dealStatus=2 where id=" . $contract['originalId'] . "";
                //				$this->query($dealStatusSql);
                //			  }
                //更新变更确认成本概算标识
                $this->update(array("id" => $contract['originalId']), array("isSubAppChange" => "0"));

                // 检查临时记录是否存在开票信息记录,有的话将其替换掉原单的开票信息记录 PMS 647
                $chkInvoiceRecords = $this->_db->get_one("select invoiceValues from oa_contract_extfields_data where contractId = '{$id}';");
                if($chkInvoiceRecords && !empty($chkInvoiceRecords['invoiceValues'])){
                    $this->invoiceTypeRecord($contract['originalId'],$chkInvoiceRecords['invoiceValues']);
                }

                //审批通过后处理新增产品下的新增物料的关联产品ID更新
                $conProDao = new model_contract_contract_product();
                $conEquDao = new model_contract_contract_equ();
                $conProInfo = $conProDao->getDetail_d($contract['originalId']);
                $conEquInfo = $conEquDao->getDetail_d($contract['originalId']);
                foreach ($conProInfo as $v) {
                    if ($v['changeTips'] == '2') {
                        $equUpSql = "update oa_contract_equ set conProductId='" . $v['id'] . "'
                        	where contractId='" . $contract['originalId'] . "' and changeTips=2 and conProductId='" . $v['tempId'] . "'";
                        $this->query($equUpSql);
                    }
                }
                //初始化需要删除的物料id数组，用于重启删除物料（相当于新增物料）后，需要删除原来的物料及配件
                $delEquIdArr = array();
                foreach ($conEquInfo as $v) {
                    if ($v['changeTips'] == '2' && !empty($v['isCon'])) {
                        $equUpSql = "update oa_contract_equ set parentEquId='" . $v['id'] . "'
                        	where contractId='" . $contract['originalId'] . "' and changeTips=2 and parentEquId='" . $v['tempId'] . "'";
                        $this->query($equUpSql);
                    }
                    if (!empty($v['remark']) && !empty($v['isCon'])) {
                        $rs = $conEquDao->find(array('id' => $v['remark']), null, 'isDel');
                        if ($rs['isDel'] == '1') {
                            array_push($delEquIdArr, $v['remark']);
                        }
                    }
                }
                //删除重启前的物料及配件
                if (!empty($delEquIdArr)) {
                    $ids = implode(',', $delEquIdArr);
                    $equDelSql = "delete from oa_contract_equ where contractId='" . $contract['originalId'] . "' and (id in($ids) or parentEquId in($ids))";
                    $this->query($equDelSql);
                    $equUpSql = "update oa_contract_equ set remark = '' where contractId='" . $contract['originalId'] . "' and remark in($ids)";
                    $this->query($equUpSql);
                }
                //重置签单状态
                if ($contract['isAcquiring'] == '1') {
                    $this->ajaxAcquiring_d($contract['originalId'], '0');
                }
                //调用工程接口
                $esmDao = new model_engineering_project_esmproject();
                $esmDao->updateContractMoney_d($contract['originalId']);

                if($contractChange == 0){// 只是物料变更,非合同变更
                    // 合拼原合同非销售产线的概算信息 2017-01-05
                    $costDao = new model_contract_contract_cost();
                    $sql1 = "select * from oa_contract_cost where contractId = '" . $contract['originalId'] . "' AND issale <> 1;";
                    $oldCostRows = $this->_db->getArray($sql1);
//                    foreach($oldCostRows as $k => $v){
//                        unset($v['id']);
//                        $v['contractId'] = $id;
//                        $costDao->add_d($v);
//                    }// 在物料确认页已做了一次服务产线的概算迁移

                    //重新计算成本概算
                    $sql = "select * from oa_contract_cost where contractId = '" . $id . "'";
                    $rows = $this->_db->getArray($sql);



                    //更新销售的成本概算
                    if ($rows) {
                        $moneyAll = 0;
                        foreach ($rows as $key => $val) {
                            if($val['issale'] == 1){
                                $moneyAll += $val['confirmMoneyTax'];
                                //更新成本概算
                                $updateF = "update oa_contract_cost set confirmMoney=" . $val['confirmMoney'] . ",confirmMoneyTax=" . $val['confirmMoneyTax'] . " where contractId='" . $contract['originalId'] . "' AND productLine='" . $val['productLine'] . "' AND issale = 1";
                                $this->_db->query($updateF);
                            }
                        }
                    }

                    //更新回退 物料确认状态
                    $updateE = "update oa_contract_contract set saleCost=" . $moneyAll . " where id='" . $contract['originalId'] . "'";
                    $this->_db->query($updateE);

                    //重新计算成本概算
                    $exGross = $this->countCost2($contract['originalId'], $id);
                }

                // 合同变更,免审处理
                if($contractChange != 0 && $contract['ExaStatus'] == "完成"){
                    //更新成本信息
                    $costDao = new model_contract_contract_cost();
                    $costDao->returnStateByCid($contract['originalId'], $id);

                    //重新计算成本概算
                    $this->countCost($contract['originalId']);
                }

                //					$sql = "update oa_contract_contract set signStatus='2' where id= " . $contract['originalId'] . "";
                //					$this->query($sql);
                //更新合同变更次数
                $becomeNumSql = "UPDATE oa_contract_contract c
				    			LEFT JOIN (
					    			SELECT
					    				COUNT(*) AS num,
					    				objId
					    			FROM
					    				oa_contract_changlog
					    			WHERE
					    				objId = {$contract['originalId']}
    							    GROUP BY
										objId
				    			) g ON c.id = g.objId
				    			SET c.becomeNum = g.num
				    			WHERE c.id = {$contract['originalId']}";
                $this->query($becomeNumSql);
                $this->updateOutStatus_d($contract['originalId']);
                $this->updateShipStatus_d($contract['originalId']);
                //源单状态处理
                if ($contract['isNeedRestamp'] == 1 && $contract['isStamp'] == 1) { //需要重新盖章
                    //直接重置盖章状态位，将现有盖章记录关闭
                    $this->update(array(
                        'id' => $contract['originalId']
                    ), array(
                        'status' => 2,
                        'isStamp' => 0,
                        'isNeedRestamp' => 0,
                        'isNeedStamp' => 0,
                        'stampType' => ''
                    ));
                } elseif ($contract['isNeedStamp'] == 1 && $contract['isStamp'] == 0) { //正在盖章的处理
                    $this->update(array(
                        'id' => $contract['originalId']
                    ), array(
                        'status' => 2,
                        'isStamp' => 0,
                        'isNeedRestamp' => 0,
                        'isNeedStamp' => 0,
                        'stampType' => ''
                    ));
                    $stampDao = new model_contract_stamp_stamp();
                    $newId = $stampDao->closeWaiting_d($contract['originalId'], 'HTGZYD-01');
                } else { //非盖章处理
                    $this->update(array(
                        'id' => $contract['originalId']
                    ), array(
                        'status' => 2,
                        'isNeedRestamp' => 0
                    ));
                }

                // 审批完成后,对特殊物料的发货数量处理
                $specialProIdArr = explode(',', specialProId);
                $catchParentIds = array();
                $contractEquArr = $conEquDao->findAll(" contractId={$contract['originalId']}");
                if($contractEquArr){
                    foreach ($contractEquArr as $equK => $equV){
                        $updateArr = array();
                        if(in_array($equV['productId'], $specialProIdArr)){
                            $catchParentIds[] = $equV['id'];// 收集父级特殊物料ID
                        }else if(in_array($equV['parentEquId'], $catchParentIds)){// 关联配件
                            $updateArr['id'] = $equV['id'];
                            $updateArr['executedNum'] = $updateArr['issuedShipNum'] = $equV['number'];
                            $conEquDao->updateById($updateArr);
                        }
                    }
                }

                if($contractChange != 0 && $contract['ExaStatus'] == "完成"){
                    //获取默认发送人
                    include(WEB_TOR . "model/common/mailConfig.php");
                    $tomail = isset($mailUser['oa_contract_change']['TO_ID']) ? $mailUser['oa_contract_change']['TO_ID'] : null;
                    $addmsg = "合同编号为为《" . $contract['contractCode'] . "》,的合同已发生变更。";
                    $emailDao = new model_common_mail();
                    $emailInfo = $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_change", "审批", "通过", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $contract['originalId'],
                        "stepName" => "审批通过",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));

                    //更新合同产品内的合同编号
                    $updateCodeSql = "update oa_contract_product p left join oa_contract_contract c on p.contractId=c.id set p.contractCode=c.contractCode where p.contractId='".$contract['originalId']."'";
                    $this->_db->query($updateCodeSql);
                }

                // 添加免审记录
//                $this->addNoAuditRecord($contract['originalId'],$contract,'合同变更免审');
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * 确认合同
    */
    function confirmContract_d($spid)
    {
        try {
            $this->start_d();
            $otherdatas = new model_common_otherdatas();
            $handleDao = new model_contract_contract_handle();
            $folowInfo = $otherdatas->getWorkflowInfo($spid);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $contract = $this->get_d($objId);
                if ($contract['ExaStatus'] == "完成") {
                    //更新项目信息
                    $proDao = new model_contract_conproject_conproject();
                    $proDao->createProjectBySale_d($objId);


                    //合同审批通过，邮件通知去维护客户合同号
                    $this->mailDeal_d('contract_maintenance', NULL, array('id' => $objId));
                    //判断是否含有试用项目
                    if (!empty ($contract['chanceId'])) {
                        //更新商机里的状态，变成“已生成订单”
                        $chanceDao = new model_projectmanagent_chance_chance();
                        $condiction = array(
                            'id' => $contract['chanceId']
                        );
                        $chanceDao->update($condiction, array(
                            "contractTurnDate" => date("Y-m-d"
                            ), "status" => "4", "rObjCode" => $contract['objCode'], "contractCode" => $contract['contractCode']));

                        //判断是否含有试用项目
                        $chanceId = $contract['chanceId'];
                        $findSql = "select id from oa_trialproject_trialproject where chanceId = '" . $chanceId . "'";
                        $Arr = $this->_db->getArray($findSql);
                        foreach ($Arr as $k => $v) {
                            $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                            $RDcondiction = array(
                                'id' => $v['id']
                            );
                            $trialprojectDao->update($RDcondiction, array(
                                "isFail" => "1"));
                            //关闭试用项目关联的工程项目
                            //						$esmDao = new model_engineering_project_esmproject();
                            //						$esmInfo = $esmDao -> closeProjectByContractId_d($v['id']);
                            $emailDao = new model_common_mail();
                            $addmsg = " 相关信息： <br/> 关联合同：【" . $contract['contractCode'] . "】 已生效<br/>  已及时关闭工程项目：【" . $esmInfo['projectCode'] . "】<br/>";
                            $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "通过", $esmInfo['managerId'], $addmsg);
                        }

                    } else if (!empty($contract['trialprojectId'])) {
                        $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                        $RDcondiction = array(
                            'id' => $contract['trialprojectId']
                        );
                        $trialprojectDao->update($RDcondiction, array(
                            "isFail" => "1"));
                        //关闭试用项目关联的工程项目
                        $esmDao = new model_engineering_project_esmproject();
                        $esmInfo = $esmDao->closeProjectByContractId_d($contract['trialprojectId']);
                        $emailDao = new model_common_mail();
                        $addmsg = " 相关信息： <br/> 生效的合同号：【" . $contract['contractCode'] . "】<br/> 已失效的试用项目：【" . $contract['trialprojectCode'] . "】<br/> 已关闭的工程项目：【" . $esmInfo['projectCode'] . "】<br/>";
                        $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "通过", $esmInfo['managerId'], $addmsg);

                    } else if (!empty($contract['turnChanceIds'])) { // 借试用转销售关联商机处理
                        $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    		status = '4',rObjCode = '" . $contract['objCode'] . "',
                    		 contractCode = '" . $contract['contractCode'] . "'
                    		WHERE id IN(" . $contract['turnChanceIds'] . ")";
                        $this->_db->query($sql);
                    }

                    //更新审批人
                    $this->contractAppArr($objId);
                    //审批完成后发送邮件
                    //获取区域扩展字段值
                    $regionDao = new model_system_region_region();
                    $expand = $regionDao->getExpandbyId($contract['areaCode']);
//                    $tomailId = $regionDao->getTomailId($contract['areaCode']);
                    $tomailId = $regionDao->getAreaPrincipalId($contract['areaCode']);// 获取区域负责人

                    include(WEB_TOR . "model/common/mailConfig.php");
                    if ($expand == '1') {
                        $tomail = $contract['prinvipalId'] . "," .
                        isset($mailUser['contract_bx']['TO_ID']) ? $mailUser['contract_bx']['TO_ID'] : null;
                    } else {
                        $tomail = isset($mailUser['contract_bx']['TO_ID']) ? $mailUser['contract_bx']['TO_ID'] : null;
                    }
                    if (!empty($tomailId)) {
                        $tomail = $tomailId . "," . $tomail;
                    }


                    //对应产品线获取邮件接收人数组
                    $toMailId = '';
                    if(!empty($contract['productLineStr'])){
                        $toMailArr = $this->productLineToMailArr;
                        $productLine = $contract['productLineStr'];
                        if(strstr($productLine,',')){//存在多个执行部门
                            $productLineArr = explode(',',$productLine);
                            foreach ($productLineArr as $v){
                                if($v == 'HTCPX-YQYB'){//如果是仪器仪表事务部的，不含研发类产品则不发邮件
                                    $hasYF = 0;
                                    $productDao = new model_contract_contract_product();
                                    $products = $productDao->getCostInfoProBycId($contract['id']);
                                    foreach($products as $v){
                                        if($v['proTypeId'] == '18'){$hasYF += 1;}
                                    }
                                    if( $hasYF > 0 ){//如果存在研发类产品则发送邮件
                                        $toMailId = empty($toMailId) ? $toMailArr[$v] : $toMailId.','.$toMailArr[$v];
                                    }
                                }else{
                                    $toMailId = empty($toMailId) ? $toMailArr[$v] : $toMailId.','.$toMailArr[$v];
                                }
                            }
                        }else{
                            $toMailId = $toMailArr[$productLine];
                        }
                    }

                    // 添加执行轨迹记录
                    $tracksDao = new model_contract_contract_tracks();
                    $tracksObject = array(
                        'contractId' => $contract['id'],//合同ID
                        'contractCode'=> $contract['contractCode'],//合同编号
                        'exePortion' => $proDao->getConduleBycid($contract['id']),//合同执行进度
                        'schedule' => "",//合同进度
                        'modelName'=>'contractBegin',
                        'operationName'=>'合同开始执行',
                        'result'=>'1',
                        'recordTime'=>date("Y-m-d"),
                        'expand2'=>'model_contract_contract_contract:confirmContract_d'
                    );
                    $recordId = $tracksDao->addRecord($tracksObject);

                    //获取默认发送人
                    if( $contract['contractType'] == "HTLX-FWHT"){
                        $tomail = ( $toMailId == '' )? $tomail : $tomail.','.$toMailId;
                        $addmsg = $contract['contractCode'] . " 合同已经通过审批。请登陆OA至【工程管理--项目管理--服务合同】立项。<br/>";
                    }else{
                        include (WEB_TOR . "model/common/mailConfig.php");
                        $tomail = $mailUser['oa_contract_contract']['TO_ID'];
                        $addmsg = "《" . $contract['contractCode'] . "》,合同已经通过审批。<br/>";
                    }
                    $addmsgInit = $this->conProductFun($objId);
                    $addmsg .= $addmsgInit;

                    $emailDao = new model_common_mail();
                    $emailInfo = $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_contract", "审批", "通过", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $objId,
                        "stepName" => "审批通过",
                        "isChange" => 0,
                        "stepInfo" => "",
                    ));

                    // 审批完成后,对特殊物料的发货数量处理
                    $specialProIdArr = explode(',', specialProId);
                    $equDao = new model_contract_contract_equ();
                    $catchParentIds = array();
                    $contractEquArr = $equDao->findAll(" contractId={$contract['id']}");
                    if($contractEquArr){
                        foreach ($contractEquArr as $equK => $equV){
                            $updateArr = array();
                            if(in_array($equV['productId'], $specialProIdArr)){
                                $catchParentIds[] = $equV['id'];// 收集父级特殊物料ID
                            }else if(in_array($equV['parentEquId'], $catchParentIds)){// 处理关联配件
                                $updateArr['id'] = $equV['id'];
                                $updateArr['executedNum'] = $updateArr['issuedShipNum'] = $equV['number'];
                                $equDao->updateById($updateArr);
                            }
                        }
                    }

                } else if ($contract['ExaStatus'] == "打回") {
                    $updateB = "update oa_contract_equ_link set ExaStatus='打回' where contractId=$objId";
                    $this->_db->query($updateB);
                    $handleDao->handleAdd_d(array(
                        "cid" => $objId,
                        "stepName" => "审批打回",
                        "isChange" => 0,
                        "stepInfo" => "",
                    ));
                }
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            insertOperateLog($e->getMessage(), "失败");
        }
    }

    /**
     * 确认合同,无需审批合同用
     * 2017-01-09 为PMS2373创建
     * @param $contractId
     */
    function confirmContractWithoutAudit_d($contractId)
    {
        try{
            $this->start_d();
            $handleDao = new model_contract_contract_handle();
            if (!empty ($contractId)) {
                $contract = $this->get_d($contractId);
                if ($contract['ExaStatus'] == "完成") {
                    //更新项目信息
                    $proDao = new model_contract_conproject_conproject();
                    $proDao->createProjectBySale_d($contractId);

                    //合同审批通过，邮件通知去维护客户合同号
                    $this->mailDeal_d('contract_maintenance', NULL, array('id' => $contractId));
                    //判断是否含有试用项目
                    if (!empty ($contract['chanceId'])) {
                        //更新商机里的状态，变成“已生成订单”
                        $chanceDao = new model_projectmanagent_chance_chance();
                        $condiction = array(
                            'id' => $contract['chanceId']
                        );
                        $chanceDao->update($condiction, array(
                            "contractTurnDate" => date("Y-m-d"
                            ), "status" => "4", "rObjCode" => $contract['objCode'], "contractCode" => $contract['contractCode']));

                        //判断是否含有试用项目
                        $chanceId = $contract['chanceId'];
                        $findSql = "select id from oa_trialproject_trialproject where chanceId = '" . $chanceId . "'";
                        $Arr = $this->_db->getArray($findSql);
                        foreach ($Arr as $k => $v) {
                            $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                            $RDcondiction = array(
                                'id' => $v['id']
                            );
                            $trialprojectDao->update($RDcondiction, array(
                                "isFail" => "1"));
                            //关闭试用项目关联的工程项目
                            //						$esmDao = new model_engineering_project_esmproject();
                            //						$esmInfo = $esmDao -> closeProjectByContractId_d($v['id']);
                            $emailDao = new model_common_mail();
                            $addmsg = " 相关信息： <br/> 关联合同：【" . $contract['contractCode'] . "】 已生效<br/>  已及时关闭工程项目：【" . $esmInfo['projectCode'] . "】<br/>";
                            $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "通过", $esmInfo['managerId'], $addmsg);
                        }

                    } else if (!empty($contract['trialprojectId'])) {
                        $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                        $RDcondiction = array(
                            'id' => $contract['trialprojectId']
                        );
                        $trialprojectDao->update($RDcondiction, array(
                            "isFail" => "1"));
                        //关闭试用项目关联的工程项目
                        $esmDao = new model_engineering_project_esmproject();
                        $esmInfo = $esmDao->closeProjectByContractId_d($contract['trialprojectId']);
                        $emailDao = new model_common_mail();
                        $addmsg = " 相关信息： <br/> 生效的合同号：【" . $contract['contractCode'] . "】<br/> 已失效的试用项目：【" . $contract['trialprojectCode'] . "】<br/> 已关闭的工程项目：【" . $esmInfo['projectCode'] . "】<br/>";
                        $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "通过", $esmInfo['managerId'], $addmsg);

                    } else if (!empty($contract['turnChanceIds'])) { // 借试用转销售关联商机处理
                        $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    		`status` = '4',rObjCode = '" . $contract['objCode'] . "',
                    		 contractCode = '" . $contract['contractCode'] . "'
                    		WHERE id IN(" . $contract['turnChanceIds'] . ")";
                        $this->_db->query($sql);
                    }

                    //获取区域扩展字段值
                    $regionDao = new model_system_region_region();
                    $expand = $regionDao->getExpandbyId($contract['areaCode']);
                    $tomailId = $regionDao->getAreaPrincipalId($contract['areaCode']);// 获取区域负责人

                    include(WEB_TOR . "model/common/mailConfig.php");
                    if ($expand == '1') {
                        $tomail = $contract['prinvipalId'] . "," .
                        isset($mailUser['contract_bx']['TO_ID']) ? $mailUser['contract_bx']['TO_ID'] : null;
                    } else {
                        $tomail = isset($mailUser['contract_bx']['TO_ID']) ? $mailUser['contract_bx']['TO_ID'] : null;
                    }
                    if (!empty($tomailId)) {
                        $tomail = $tomailId . "," . $tomail;
                    }

                    //对应产品线获取邮件接收人数组
                    $toMailId = '';
                    if(!empty($contract['productLineStr'])){
                        $toMailArr = $this->productLineToMailArr;
                        $productLine = $contract['productLineStr'];
                        if(strstr($productLine,',')){//存在多个执行部门
                            $productLineArr = explode(',',$productLine);
                            foreach ($productLineArr as $v){
                                if($v == 'HTCPX-YQYB'){//如果是仪器仪表事务部的，不含研发类产品则不发邮件
                                    $hasYF = 0;
                                    $productDao = new model_contract_contract_product();
                                    $products = $productDao->getCostInfoProBycId($contract['id']);
                                    foreach($products as $v){
                                        if($v['proTypeId'] == '18'){$hasYF += 1;}
                                    }
                                    if( $hasYF > 0 ){//如果存在研发类产品则发送邮件
                                        $toMailId = empty($toMailId) ? $toMailArr[$v] : $toMailId.','.$toMailArr[$v];
                                    }
                                }else{
                                    $toMailId = empty($toMailId) ? $toMailArr[$v] : $toMailId.','.$toMailArr[$v];
                                }
                            }
                        }else{
                            $toMailId = $toMailArr[$productLine];
                        }
                    }

                    // 添加执行轨迹记录
                    $tracksDao = new model_contract_contract_tracks();
                    $tracksObject = array(
                        'contractId' => $contract['id'],//合同ID
                        'contractCode'=> $contract['contractCode'],//合同编号
                        'exePortion' => $proDao->getConduleBycid($contract['id']),//合同执行进度
                        'schedule' => "",//合同进度
                        'modelName'=>'contractBegin',
                        'operationName'=>'合同开始执行',
                        'result'=>'1',
                        'recordTime'=>date("Y-m-d"),
                        'expand2'=>'model_contract_contract_contract:confirmContract_d'
                    );
                    $recordId = $tracksDao->addRecord($tracksObject);

                    // 添加免审记录
//                    $this->addNoAuditRecord($contract['id'],$contract,'合同免审');

                    //获取默认发送人
                    if( $contract['contractType'] == "HTLX-FWHT"){
                        $tomail = ( $toMailId == '' )? $tomail : $tomail.','.$toMailId;
                        $addmsg = $contract['contractCode'] . " 合同已经通过审批。请登陆OA至【工程管理--项目管理--服务合同】立项。<br/>";
                    }else{
                        include (WEB_TOR . "model/common/mailConfig.php");
                        $tomail = $mailUser['oa_contract_contract']['TO_ID'];
                        $addmsg = "《" . $contract['contractCode'] . "》,合同已经通过审批。<br/>";
                    }
                    $addmsgInit = $this->conProductFun($contractId);
                    $addmsg .= $addmsgInit;

                    $emailDao = new model_common_mail();
                    $emailInfo = $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_contract", "审批", "通过", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $contractId,
                        "stepName" => "审批通过",
                        "isChange" => 0,
                        "stepInfo" => "该合同无需审批",
                    ));

                }
            }
            $this->commit_d();
        }catch (Exception $e) {
            $this->rollBack();
            insertOperateLog($e->getMessage(), "失败");
        }
    }

    /**
     * 记录免审信息
     * @param $obj
     * @return bool|int|mixed|string
     */
    function addNoAuditRecord($cid,$contract,$taskName){
        $userName = $_SESSION['USERNAME'];
        $userId = $_SESSION['USER_ID'];
        $date = date("Y-m-d H:i:s");
        $result = '';
        //step1 填入审批主表(wf_task)
        $task['Creator'] = $userId;
        $task['name'] = $taskName;// 合同免审 or 合同变更免审
        $task['code'] = 'oa_contract_contract';
        $task['examines'] = 'ok';
        $task['Status'] = 0;
        $task['start'] = $date;
        $task['finish'] = $date;
        $task['Pid'] = $cid;// 合同ID
        $task['objCode'] = $contract['contractCode'];// 合同编码
        $task['objName'] = $contract['contractName'];// 合同名称
        $task['objCustomer'] = $contract['customerName'];// 合同客户名称
        $task['objAmount'] = $contract['contractMoney'];// 合同金额
        $task['objUserName'] = $contract['prinvipalName'];// 合同负责人
        $task['objUser'] = $contract['prinvipalId'];// 合同负责人ID
        $this->tbl_name = 'wf_task';
        $workflowDao = new model_common_workflow_workflow();
        $taskId = $workflowDao->add_d($task);

        //step1.1 检查合同变更免审次数
        $SmallID = 1;
        $chkSql = "select * from wf_task where Pid ='{$cid}' and code='oa_contract_contract' and name = '合同变更免审' ORDER BY task desc;";
        $chkResult = $this->_db->getArray($chkSql);
        if($chkResult){
            $num = count($chkResult);
            $SmallID = $num;
        }

        //step2 填入流程步骤表(flow_step)
        if($taskId){
            $step['SmallID'] = $SmallID;
            $step['Wf_task_ID'] = $taskId;
            $step['Step'] = 1;
            $step['Item'] = '免审';
            $step['User'] = $userId;
            $step['status'] = 'ok';
            $step['Start'] = $date;
            $step['Endtime'] = $date;
            $workflowDao->tbl_name = 'flow_step';
            $stepId = $workflowDao->add_d($step);

            //step3 填入流程步骤处理表(flow_step_partent)
            if($stepId != '' && $stepId){
                $step_partent['StepID'] = $stepId;
                $step_partent['SmallID'] = $SmallID;
                $step_partent['Wf_task_ID'] = $taskId;
                $step_partent['User'] = $userId;
                $step_partent['Flag'] = 1;
                $step_partent['Result'] = 'ok';
                $step_partent['Content'] = '满足免审条件，系统直接审批通过。';
                $step_partent['START'] = $date;
                $step_partent['Endtime'] = $date;
                $workflowDao->tbl_name = 'flow_step_partent';
                $result = $workflowDao->add_d($step_partent);
            }
        }
        return $result;
    }

    /**
     * 添加执行轨迹记录 (ID2243 2016-12-8)
     * @param $obj
     * @return string
     */
    function addTracksRecord($obj){
        $proDao = new model_contract_conproject_conproject();
        $tracksDao = new model_contract_contract_tracks();
        $contractCode = '';
        if(!isset($obj['contractCode']) || $obj['contractCode'] == ''){
            $contractCode = $this->find(array('id'=>$obj['contractId']),'','contractCode');
        }
        $tracksObject = array(
            'contractId' => $obj['contractId'],//合同ID
            'contractCode'=> isset($obj['contractCode'])? $obj['contractCode'] : $contractCode['contractCode'],//合同编号
            'exePortion' => $proDao->getConduleBycid($obj['contractId']),//合同执行进度
            'modelName'=> $obj['modelName'],
            'operationName'=> $obj['operationName'],
            'result'=>$obj['result'],
            'recordTime'=>$obj['time'],
            'expand1'=>isset($obj['expand1'])? $obj['expand1'] : '',
            'expand2'=>$obj['expand2'],
            'remarks'=>isset($obj['remarks'])? $obj['remarks'] : ''
        );
        $recordId = $tracksDao->addRecord($tracksObject);
        return $recordId;
    }

    /**
     * 关闭审批确认
     */
    function confirmClose_d($spid)
    {
        try {
            $this->start_d();
            $otherdatas = new model_common_otherdatas();
            $folowInfo = $otherdatas->getWorkflowInfo($spid);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $contract = $this->get_d($objId);
                if ($folowInfo['examines'] == "ok") {
                    $sql = "update oa_contract_contract set state='7',closeTime='".date("Y-m-d H:i:s")."' where id='" . $objId . "'";
                    $this->query($sql);

                    // 更新所有关联的项目的合同状态字段信息 (PMS449)
                    $sql = "update oa_esm_project set statusName = '异常关闭',contractStatus = 7,updateTime = '".date("Y-m-d H:i:s")."',updateId = '{$_SESSION['USER_ID']}',updatename = '{$_SESSION['USERNAME']}' where contractId ='" . $objId . "'";
                    $this->query($sql);
                    $sql = "update oa_contract_project set contractstatus = 7,updateTime = '".date("Y-m-d H:i:s")."',updateId = '{$_SESSION['USER_ID']}',updatename = '{$_SESSION['USERNAME']}' where contractId ='" . $objId . "'";
                    $this->query($sql);

                    //插入合同确认数据
                    $conFirmArr['type'] = '异常关闭';
                    $conFirmArr['money'] = $contract['contractMoney'];
                    $conFirmArr['state'] = '未确认';
                    $conFirmArr['contractId'] = $contract['id'];
                    $conFirmArr['contractCode'] = $contract['contractCode'];
                    $confirmDao = new model_contract_contract_confirm();
                    $confirmDao->add_d($conFirmArr);

                    // 执行轨迹记录
                    $trackArr['contractId'] = $objId;$trackArr['modelName'] = 'contractClose';
                    $trackArr['operationName'] = '合同异常关闭';$trackArr['result'] = 7;
                    $trackArr['time'] = date("Y-m-d H:i:s");$trackArr['expand2'] = 'oa_contract_contract_contract:confirmClose_d';
                    $this->addTracksRecord($trackArr);

                    //获取默认发送人
                    include(WEB_TOR . "model/common/mailConfig.php");
                    if (!empty ($mailUser['contractClose']['TO_ID'])) {
                        $addmsg = "合同 《" . $contract['contractCode'] . " 》异常关闭申请已审批通过<br/>关闭信息 ：" . $contract['closeRegard'] . "";
                        $emailDao = new model_common_mail();
                        $emailDao->batchEmail('1', $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'contractClose', '审批通过', '', $mailUser['contractClose']['TO_ID'], $addmsg);
                    }
                }
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    //邮件里的产品信息处理
    function conProductFun($objId)
    {
        $sql = "select p.conProductName,c.id,c.contractCode,c.prinvipalName,p.conProductDes,p.number
					 			from oa_contract_product p left join oa_contract_contract c on c.id = p.contractId where p.contractId=" . $objId . "";
        $rows = $this->_db->getArray($sql);
        $str = '';
        //主表信息带入
        $mainInfo = $rows[0];
        $str .= '合同编号:<font color=red> ' . $mainInfo['contractCode'] . '</font>  合同负责人:' . $mainInfo['prinvipalName'] . '';

        if (is_array($rows)) {
            //从表信息带入
            $str .= '<table border=1 cellspacing=0  width=80% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>序号</td><td>产品名称</td><td>产品描述</td><td>数量</td></tr>';

            foreach ($rows as $key => $val) {
                $i = $key + 1;
                $str .= <<<EOT
				<tr><td>$i</td><td>$val[conProductName]</td><td>$val[conProductDes]</td><td>$val[number]</td></tr>
EOT;
            }
            $str .= '</table>';
        }

        //    print_r($mainInfo);
        return $str;
    }

    /**
     * 确认合同
     */
    function confirmAudit($spid)
    {
        try {
            $this->start_d();
            $otherdatas = new model_common_otherdatas();
            $folowInfo = $otherdatas->getWorkflowInfo($spid);
            $objId = $folowInfo['objId'];
            if (!empty ($objId)) {
                $contract = $this->get_d($objId);
                if ($contract['ExaStatus'] == "完成") {
                    $sql = "update oa_contract_contract set dealStatus='1' where id= '" . $contract['originalId'] . "'";
                    $this->query($sql);
                }

            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * 根据ID 获取合同全部信息
     * $contractId : 合同ID
     * $getInfoArr 需要的从表信息 例:$getInfoArr = array('linkman','product') 默认为空 取全部
     *       linkman-联系人 prodcut-产品  equ-物料
     *       invoice-开票 income-到款 trainListInfo-培训
     */
    function getContractInfo($contractId, $getInfoArr = null)
    {
        if (empty ($getInfoArr)) {
            $getInfoArr = array(
                'linkman',
                'product',
                'financialplan',
                'equ',
                'invoice',
                'income',
                'trainListInfo',
                'payment'
            );
        }
        $daoArr = array(
            "linkman" => "model_contract_contract_linkman",
            "product" => "model_contract_contract_product",
            "financialplan" => "model_contract_contract_financialplan",
            "equ" => "model_contract_contract_equ",
            "invoice" => "model_contract_contract_invoice",
            "income" => "model_contract_contract_receiptplan",
            "trainListInfo" => "model_contract_contract_trainingplan",
            "payment" => "model_contract_contract_receiptplan"
        );
        $contractInfo = $this->get_d($contractId);
        foreach ($getInfoArr as $key => $val) {
            $daoName = $daoArr[$val];
            $dao = new $daoName ();
            $contractInfo[$val] = $dao->getDetail_d($contractId);
        }
        return $contractInfo;
    }

    /**
     * 根据ID 获取合同全部信息
     * $contractId : 合同ID
     * $getInfoArr 需要的从表信息 例:$getInfoArr = array('linkman','product') 默认为空 取全部
     *       linkman-联系人 prodcut-产品  equ-物料
     *       invoice-开票 income-到款 trainListInfo-培训
     * @author zzx
     */
    function getContractInfoWithTemp($contractId, $getInfoArr = null, $isSubAppChange)
    {
        if (empty ($getInfoArr)) {
            $getInfoArr = array(
                'linkman',
                'product',
                'equ',
                'invoice',
                'income',
                'trainListInfo'
            );
        }
        $daoArr = array(
            "linkman" => "model_contract_contract_linkman",
            "product" => "model_contract_contract_product",
            "equ" => "model_contract_contract_equ",
            "invoice" => "model_contract_contract_invoice",
            "income" => "model_contract_contract_receiptplan",
            "trainListInfo" => "model_contract_contract_trainingplan"
        );
        $contractInfo = $this->get_d($contractId);
        foreach ($getInfoArr as $key => $val) {
            $daoName = $daoArr[$val];
            $dao = new $daoName ();
            if ($val == 'product') {
                $contractInfo[$val] = $dao->getDetailWithTemp_d($contractId, $isSubAppChange);
            } else {
                $contractInfo[$val] = $dao->getDetail_d($contractId, $isSubAppChange);
            }
        }
        return $contractInfo;
    }

    /**
     * 根据ID 获取合同全部信息(包括删除的记录)
     * $contractId : 合同ID
     * $getInfoArr 需要的从表信息 例:$getInfoArr = array('linkman','product') 默认为空 取全部
     *       linkman-联系人 prodcut-产品  equ-物料
     *       invoice-开票 income-到款 trainListInfo-培训
     */
    function getContractInfoAll($contractId, $getInfoArr = null)
    {
        if (empty ($getInfoArr)) {
            $getInfoArr = array(
                'linkman',
                'product',
                'financialplan',
                'equ',
                'invoice',
                'income',
                'trainListInfo',
                'payment'
            );
        }
        $daoArr = array(
            "linkman" => "model_contract_contract_linkman",
            "product" => "model_contract_contract_product",
            "financialplan" => "model_contract_contract_financialplan",
            "equ" => "model_contract_contract_equ",
            "invoice" => "model_contract_contract_invoice",
            "income" => "model_contract_contract_receiptplan",
            "trainListInfo" => "model_contract_contract_trainingplan",
            "payment" => "model_contract_contract_receiptplan"
        );
        $contractInfo = $this->get_d($contractId);
        foreach ($getInfoArr as $key => $val) {
            $daoName = $daoArr[$val];
            $dao = new $daoName ();
            $dao->searchArr ['contractId'] = $contractId;
            $contractInfo[$val] = $dao->list_d();
        }
        return $contractInfo;
    }

    /**
     * 根据合同编号 获取合同全部信息
     * $contractCode : 合同编号
     * $getInfoArr 需要的从表信息 例:$getInfoArr = array('linkman','product') 默认为空 取全部
     *       linkman-联系人 prodcut-产品  equ-物料
     *       invoice-开票 income-到款 trainListInfo-培训
     * $getInfoArr = "main" 只获取主表信息
     */
    function getContractInfoByCode($contractCode, $getInfoArr = null)
    {
        $sql = "select id from oa_contract_contract where contractCode='$contractCode' and isTemp = 0 and ExaStatus in ('完成','变更审批中')";
        $conIdarr = $this->_db->getArray($sql);
        $contractId = $conIdarr[0]['id'];
        if (empty ($getInfoArr) && $getInfoArr != "main") {
            $getInfoArr = array(
                'linkman',
                'product',
                'equ',
                'invoice',
                'income',
                'trainListInfo'
            );
        }
        $daoArr = array(
            "linkman" => "model_contract_contract_linkman",
            "product" => "model_contract_contract_product",
            "equ" => "model_contract_contract_equ",
            "invoice" => "model_contract_contract_invoice",
            "income" => "model_contract_contract_receiptplan",
            "trainListInfo" => "model_contract_contract_trainingplan"
        );
        $contractInfo = $this->get_d($contractId);
        if (!empty ($contractInfo) || $getInfoArr != "main") {
            foreach ($getInfoArr as $key => $val) {
                $daoName = $daoArr[$val];
                $dao = new $daoName ();
                $contractInfo[$val] = $dao->getDetail_d($contractId);
            }
        }
        return $contractInfo;
    }

    /**
     * 判断当前登录人是否合同创建人,负责人,区域负责人
     * 用于权限过滤
     * 2012-04-06
     * createBy show
     */
    function isKeyMan_d($id)
    {
        $thisUserId = $_SESSION['USER_ID'];
        $sql = 'select id from ' . $this->tbl_name . " where id = " . $id . " and ( prinvipalId ='" . $thisUserId . "' or areaPrincipalId ='" . $thisUserId . "')";
        return $this->_db->getArray($sql);
    }

    /*************************************************************************************************************************/
    /**
     * 合同签收
     */
    function signin_d($object)
    {
        $object['invoiceCode'] = implode(",", $object['invoiceCode']);
        $object['invoiceValue'] = implode(",", $object['invoiceValue']);
        //		echo "<pre>";
        //		print_r($object);
        //		die();
        try {
            $this->start_d();
            //处理付款条件
            $object = $this->handlePaymentData($object);
            $changeLogDao = new model_common_changeLog('contractSign', false);
            //添加OldId
            $object['oldId'] = $object['id'];

            $forArr = array(
                "linkman",
                "product",
                "invoice",
                "income",
                "train"
            );
            foreach ($forArr as $key => $val) {
                foreach ($object[$val] as $k => $v) {
                    $object[$val][$k]['oldId'] = $object[$val][$k]['id'];
                }
            }
            //变更记录,拿到变更的临时主对象id
            $changeLogDao->addLog($object);
            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict();
            $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            $object['signContractTypeName'] = $datadictDao->getDataNameByCode($object['signContractType']);
            $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
            $object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);

            //查找处理 所选产品的 最高类型
            $goodsTypeIds = "";
            foreach ($object['product'] as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    $goodsTypeIds .= $v['conProductId'] . ",";
                }
            }
            $goodsTypeIds = rtrim($goodsTypeIds, ',');
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //第一次查找，过滤出本身已经是最高类别的类型
                $goodsTypeA = $this->_db->getArray($sqlA);
                $goodsTypeStr = "";
                $goodsTypeStrTemp = "";
                foreach ($goodsTypeA as $k => $v) {
                    if ($v['parentId'] == "-1") {
                        $goodsTypeStr .= $v['id'] . ",";
                    } else {
                        $goodsTypeStrTemp .= $v['id'] . ",";
                    }
                }
                $goodsTypeStrTemp = rtrim($goodsTypeStrTemp, ',');
            }
            if (!empty ($goodsTypeStrTemp)) {
                //第二次查找，找到剩余产品的最高类别
                $sqlB = "
					               select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $object['goodsTypeStr'] = $goodsTypeStr;
            //产品线
            $sqlf = "select exeDeptCode from oa_goods_base_info where id in ($goodsTypeIds)";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $exeDeptNameStr = "";
            foreach ($exeDeptNameArr as $k => $v) {
                $exeDeptNameStr .= $v['exeDeptCode'] . ",";
            }
            $object['productLineStr'] = $exeDeptNameStr;
            //判断是否需要走到执行部，并处理显示字段
            $isSellArr = explode(",", $goodsTypeStr);
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
            } else {
                $object['isSell'] = "0";
            }

            //修改主表信息
            $object['id'] = $object['oldId'];
            $object['signStatus'] = '1';
            $object['signinDate'] = date('Y-m-d');
            parent :: edit_d($object, true);

            // 执行轨迹记录
            $trackArr['contractId'] = $object['id'];$trackArr['modelName'] = 'contractSignIn';
            $trackArr['operationName'] = '签收纸质合同';$trackArr['result'] = $object['signStatus'];
            $trackArr['time'] = $object['signinDate'];$trackArr['expand2'] = 'oa_contract_contract_contract:signin_d';
            $this->addTracksRecord($trackArr);

            $orderId = $object['oldId'];


            //插入从表信息
            //客户联系人
            $linkmanDao = new model_contract_contract_linkman();
            foreach ($object['linkman'] as $key => $val) {
                if (!empty ($val['oldId'])) {
                    $val['id'] = $val['oldId'];
                    $linkmanDao->edit_d($val);
                } else {
                    $linkmanDao->createBatch(array(
                        $object['linkman'][$key]
                    ), array(
                        'contractId' => $orderId
                    ), 'linkman');
                }
                if (isset ($val['isDelTag'])) {
                    $linkmanDao->updateField(array(
                        'id' => $val['oldId']
                    ), "isDel", "1");
                }
            }
            //设备

            $orderequDao = new model_contract_contract_product();
            foreach ($object['product'] as $key => $val) {
                if (!empty ($val['oldId'])) {
                    $val['id'] = $val['oldId'];
                    $orderequDao->edit_d($val);
                } else {
                    $orderequDao->createBatch(array(
                        $object['product'][$key]
                    ), array(
                        'contractId' => $orderId
                    ), 'conProductName');
                }
                if (isset ($val['isDelTag'])) {
                    $orderequDao->updateField(array(
                        'id' => $val['oldId']
                    ), "isDel", "1");
                }
            }
            //开票信息
            $orderInvoiceDao = new model_contract_contract_invoice();
            foreach ($object['invoice'] as $key => $val) {
                if (!empty ($val['oldId'])) {
                    $val['id'] = $val['oldId'];
                    $orderInvoiceDao->edit_d($val);
                } else {
                    $orderInvoiceDao->createBatch(array(
                        $object['invoice'][$key]
                    ), array(
                        'contractId' => $orderId
                    ), 'money');
                }
                if (isset ($val['isDelTag'])) {
                    $orderInvoiceDao->updateField(array(
                        'id' => $val['oldId']
                    ), "isDel", "1");
                }
            }
            //收款计划
            $orderReceiptplanDao = new model_contract_contract_receiptplan();
            foreach ($object['income'] as $key => $val) {
                if (!empty ($val['oldId'])) {
                    $val['id'] = $val['oldId'];
                    $orderReceiptplanDao->edit_d($val);
                } else {
                    $orderReceiptplanDao->createBatch(array(
                        $object['income'][$key]
                    ), array(
                        'contractId' => $orderId
                    ), 'money');
                }
                if (isset ($val['isDelTag'])) {
                    $orderInvoiceDao->updateField(array(
                        'id' => $val['oldId']
                    ), "isDel", "1");
                }
            }
            //培训计划
            $orderTrainingplanDao = new model_contract_contract_trainingplan();
            foreach ($object['train'] as $key => $val) {
                if (!empty ($val['oldId'])) {
                    $val['id'] = $val['oldId'];
                    $orderTrainingplanDao->edit_d($val);
                } else {
                    $orderTrainingplanDao->createBatch(array(
                        $object['train'][$key]
                    ), array(
                        'contractId' => $orderId
                    ), 'beginDT');
                }
                if (isset ($val['isDelTag'])) {
                    $orderTrainingplanDao->updateField(array(
                        'id' => $val['oldId']
                    ), "isDel", "1");
                }
            }

            //判断合同是否需要关闭
            $this->updateContractClose($orderId);
            $this->commit_d();
            //			$this->rollBack();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 合同信息列表统计金额
     */
    function getRowsallMoney_d($rows, $selectSql, $needFilt = false )
    {
        if($needFilt){

            // PMS 522 合同应收款特殊规则配置处理
            $filtCustomerTypes = $this->dealSpecRecordsForNoSurincome();
            $this->searchArr['customerTypeNotIn'] = implode(",",$filtCustomerTypes);
        }

        //查询记录合计
        $objArr = $this->listBySqlId($selectSql . '_sumMoney');
        if (is_array($objArr)) {
            $rsArr = $objArr[0];
            $rsArr['thisAreaName'] = '合计';
        } else {
            $rsArr = array(
                'id' => 'noId',
                'softMoney' => 0,
                'hardMoney' => 0,
                'repairMoney' => 0,
                'serviceMoney' => 0,
                'invoiceMoney' => 0
            );
        }
        $rows[] = $rsArr;
        return $rows;
    }

    /**
     * 根据合同业务编号获取合同信息
     */
    function getOrderByObjCode($objCode)
    {
        $objCode = explode(",", $objCode);
        $objCodeArr = "";
        foreach ($objCode as $key => $val) {
            $objCodeArr .= "'$val'" . ",";
        }
        $objCodeArr = Trim($objCodeArr, ',');
        if (!empty ($objCodeArr)) {
            $sql = "select * from oa_contract_contract where objCode in ($objCodeArr)";
            $rows = $this->_db->getArray($sql);
        } else {
            $rows = "";
        }
        return $rows;
    }
    /*************************************************************************************************************************/
    /**
     * 延迟发货处理
     */
    function inform_d($inform)
    {
        try {
            $this->start_d();
            //更新延迟发货状态
            $sql = "update oa_contract_contract set shipCondition = '0' where id=" . $inform['contractId'] . " ";
            $this->_db->query($sql);
            $addmsg = "延迟发货的合同已需要发货。 <br/>";
            $addmsgInit = $this->conProductFun($inform['contractId']);
            $addmsg .= $addmsgInit;
            //获取默认发送人
            include(WEB_TOR . "model/common/mailConfig.php");
            if (!empty ($mailUser['shipconditon']['sendUserId'])) {
                $emailDao = new model_common_mail();
                $emailInfo = $emailDao->batchEmail('1', $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'shipconditon', '发送发货通知', '', $mailUser['shipconditon']['sendUserId'], $addmsg);
            }
            $this->commit_d();
            return 1;
        } catch (Exception $e) {
            $this->rollBack();
            return 0;
        }

    }

    /**
     * 改变合同状态
     */
    function completeOrder_d($orderId)
    {
        $sql = "update oa_contract_contract set state = 4,completeDate=now() where id=" . $orderId . " ";
        $this->query($sql);

        // 执行轨迹记录
        $trackArr['contractId'] = $orderId;$trackArr['modelName'] = 'contractComplete';
        $trackArr['operationName'] = '合同执行结束';$trackArr['result'] = 4;
        $trackArr['time'] = now();$trackArr['expand2'] = 'oa_contract_contract_contract:completeOrder_d';
        $this->addTracksRecord($trackArr);

        //判断合同是否需要关闭
        $this->updateContractClose($orderId);

    }

    function exeOrder_d($orderId)
    {
        $sql = "update oa_contract_contract set state = 2 where id=" . $orderId . " ";
        $this->query($sql);
    }

    /**
     * 获取上一个版本合同信息
     */
    function getLastContractInfo_d($conId)
    {
        //获取审批过的有多少个数量
        $sql = "select count(id) as num from oa_contract_contract where originalId=$conId and ExaStatus='完成' ";
        $num = $this->queryCount($sql);
        if ($num == 0) { //没有变更过
            return false;
        } else
            if ($num == 2) { //变更过一次（第一次会变更确认会插入一条原始版本信息+临时记录则为两条）
                //取到原始版本
                $sql = "select max(id) as num from oa_contract_contract where originalId=$conId and ExaStatus='完成' ";
                $oldId = $this->queryCount($sql);
            } else {
                //取到上一个版本
                $sql = "select max(id) as num from oa_contract_contract where id!=" .
                    "(select max(id)  from oa_contract_contract " .
                    "where originalId=$conId and ExaStatus='完成') " .
                    "and originalId=$conId and ExaStatus='完成'";
                $oldId = $this->queryCount($sql);
            }
        return parent :: get_d($oldId);
    }

    //////////以下为锁定方法////////////////
    /**
     * 锁定处理的设备信息
     */
    function showDetaiInfo($rows)
    {
        $equDao = new model_contract_contract_equ();
        $equs = $equDao->getLockEqus($rows['id'], null);
        $rows['orderequ'] = $equDao->showLockEqusByContract($equs);
        return $rows;
    }

    /********************************************************发货相关******************************************************************************/

    /**
     * 根据合同id修改合同及发货计划状态
     */
    function updateOutStatus_d($id)
    {
        $orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_contract_equ o where o.contractId=" . $id . " and o.isTemp=0 and o.isDel=0) as executeNum
										 from (select e.contractId,(e.number-e.executedNum + e.exchangeBackNum) as remainNum from oa_contract_equ e
										where e.contractId=" . $id . " and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
        $remainNum = $this->_db->getArray($orderRemainSql);
        if ($remainNum[0]['countNum'] <= 0) { //已发货
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'YFH',
                'outstockDate' => day_date
            );
        } elseif ($remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0) { //未发货
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'WFH',
                'completeDate' => '',
                'outstockDate' => ''
            );

        } else { //部分发货
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'BFFH',
                'completeDate' => '',
                'outstockDate' => ''
            );
        }
        $this->updateById($statusInfo);
        $this->updateContractState($id);
        $this->updateProjectProcess(array(
            "id" => $id
        ));

        // 触发其他类合同的相关信息更新 PMS 607
        $this->updateOtherSalesPlanEndDate($id);
        return 0;
    }

    /**
     * 根据合同ID,更新所有含有该合同的【履约保证金】其他类合同的项目预计结束时间 PMS 607
     * @param $cid
     */
    function updateOtherSalesPlanEndDate($cid){
        $esmDao = new model_engineering_project_esmproject();
        $esmDao->getParam(array("contractId" => $cid)); //设置前台获取的参数信息
        $rows = $esmDao->pageBySqlId('select_defaultAndFee');
        $maxPlanEndDate = '';

        // 获取该合同的最大项目结束日期【服务项目用预计结束日期,产品项目用预计结束日期】
        foreach ($rows as $k => $v) {
            $pType = isset($v['pType']) ? $v['pType'] : 'esm';
            $planEndDate = '';
            if ($pType == "pro") {
                $conArr = $this->get_d($v['contractId']);
                $planEndDate = $conArr['outstockDate'];
            }else{
                $planEndDate = $v['planEndDate'];
            }
            if($maxPlanEndDate != ''){
                if($planEndDate != '' && strtotime($planEndDate) > strtotime($maxPlanEndDate)){
                    $maxPlanEndDate = $planEndDate;
                }
            }else{
                $maxPlanEndDate = $planEndDate;
            }
        }

        // 更新关联的所有其他类合同
        if(!empty($maxPlanEndDate)){
            $updateSql = "update oa_sale_other set projectPrefEndDate = '{$maxPlanEndDate}' where payForBusiness = 'FKYWLX-04' and contractId = '{$cid}';";
            $this->query($updateSql);
        }
    }

    /**
     * 改变发货状态 --- 关闭
     */
    function updateDeliveryStatus($id)
    {
        $condiction = array(
            "id" => $id
        );
        if ($this->updateField($condiction, "DeliveryStatus", "TZFH")) {

            $this->updateContractState($id);
            echo 1;
        } else
            echo 0;
    }

    /**
     * 根据发货情况修改合同及发货计划状态
     */
    function updateShipStatus_d($id)
    {
        $orderRemainSql = "select count(0) as equCount,sum(IF (c.remainNum>0,1,0)) AS countNum,
						(select sum(o.issuedShipNum) from oa_contract_equ o where o.contractId=" . $id . " and o.isTemp=0 and o.isDel=0) as issuedShipNum
						from (select e.contractId,(e.number-e.issuedShipNum + e.backNum) as remainNum from oa_contract_equ e
						where e.contractId=" . $id . " and e.isTemp=0 and e.isDel=0) c where 1=1 ";
        $remainNum = $this->_db->getArray($orderRemainSql);
        if ($remainNum[0]['countNum'] <= 0) { //已发货
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'YXD'
            );
        } elseif ($remainNum[0]['countNum'] > 0 && $remainNum[0]['issuedShipNum'] == 0) { //未发货
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'WXD'
            );
        } else { //部分发货
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'BFXD'
            );
        }
        $this->updateById($statusInfo);
        return 0;
    }

    //是否
    function rtYesOrNo_d($value)
    {
        if ($value == 1) {
            return '是';
        } else {
            return '否';
        }
    }

    /****************************  S 工程项目添加方法**************************/
    /**
     * 服务合同获取办事处权限
     */
    function getProvinceNames_d($limitArr)
    {
        $officeIdArr = array();
        $provinceArr = array();

        if (!empty ($limitArr['办事处'])) {
            array_push($officeIdArr, $limitArr['办事处']);
        }

        //获取办事处的id
        $officeInfoDao = new model_engineering_officeinfo_officeinfo();
        $officeIds = $officeInfoDao->getOfficeIds_d($_SESSION['USER_ID']);
        if (!empty ($officeIds)) {
            array_push($officeIdArr, $officeIds);
        }

        //如果返回的办事处id不为空，则查找省份名称
        if (!empty ($officeIdArr)) {
            $officeIds = implode($officeIdArr, ',');
            $proNames = $officeInfoDao->getProNamesByIds_d($officeIds);
            array_push($provinceArr, $proNames);
        }

        //权限系统省份权限
        if (isset($limitArr['省份权限']) && $limitArr['省份权限']) {
            array_push($provinceArr, $limitArr['省份权限']);
        }

        //获取个人省份权限
        $provinceDao = new model_system_procity_province();
        $provinceLimit = $provinceDao->getProvinces_d($_SESSION['USER_ID']);
        if ($provinceLimit) {
            array_push($provinceArr, $provinceLimit);
        }
        //构建新省份部分
        $newProNames = implode(array_unique(explode(',', implode($provinceArr, ','))), ',');

        return $newProNames;
    }

    /**************************** E 工程项目添加方法**************************/
    /**
     *审批成功后在盖章列表添加信息
     */
    function dealAfterAudit_d($objId)
    {
        $object = $this->get_d($objId);
        if ($object['isNeedStamp'] == "1" && $object['ExaStatus'] == AUDITED) {
            //			if ($userId == $object['createId']) {
            //				$userName = $object['createName'];
            //			} else {
            //				$userName = $object['principalName'];
            //			}
            $userId = $object['prinvipalId'];
            $userName = $object['prinvipalName'];
            //创建数组
            $stampObj = array(
                "contractId" => $object['id'],
                "contractCode" => $object['contractCode'],
                "contractType" => 'HTGZYD-04',
                "contractName" => $object['contractName'],
                "signCompanyName" => $object['customerName'],
                "signCompanyId" => $object['customerId'],
                "objCode" => $object['objCode'],
                "applyUserId" => $userId,
                "applyUserName" => $userName,
                "applyDate" => day_date,
                "stampType" => $object['stampType'],
                "status" => 0,
                "contractMoney" => $object['contractMoney']
            );
            $stampDao = new model_contract_stamp_stamp();
            $newId = $stampDao->addStamps_d($stampObj, true);

            // 邮件通知盖章负责人
            $stampInfoSql = "select st.applyUserName,sc.stampName,sc.principalId from oa_sale_stamp st left join oa_system_stamp_config sc on st.stampType = sc.stampName where st.contractId = '{$object['id']}'";
            $stampInfo = $this->_db->getArray($stampInfoSql);
            if($stampInfo && is_array($stampInfo)){
                foreach ($stampInfo as $key => $stamp){
                    $this->mailDeal_d('stampapply_passed', $stamp['principalId'], array('applyName' => $stamp['applyUserName'],'stampName' => $stamp['stampName']));
                }
            }

            return $newId;
        }
        return 1;
    }

    /**
     * 新增申请盖章信息
     */
    function stamp_d($obj)
    {
        $stampDao = new model_contract_stamp_stamp();
        try {
            $this->start_d();

            //获取对应对象的最大批次号
            $maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name, " contractType = 'HTGZYD-01' and contractId=" . $obj['contractId'], "max(batchNo)");
            $obj['batchNo'] = $maxBatchNo + 1;

            //新增盖章信息
            $obj['contractType'] = 'HTGZYD-04';
            $stampDao->addStamps_d($obj, true);

            //更新合同字段信息
            parent :: edit_d(array(
                'id' => $obj['contractId'],
                'isNeedStamp' => 1,
                'stampType' => $obj['stampType'],
                'isStamp' => 0
            ));

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /*************************************************************************/
    /**
     * 更新就合同数据至新合同
     */
    function updateAdd($object, $conType)
    {
        try {
            $this->start_d();
            //插入主表信息
            if (!empty ($object['info'])) {
                $linkArr = array();
                foreach ($object['info'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $contractCode = $val['contractCode'];
                    $oldContractType = $val['oldContractType'];
                    $contractType = $val['contractType'];
                    $oldObjcode = $val['objCode'];
                    $val['dealStatus'] = 1; //处理状态，导入的为1
                    $oldTableName = $val['oldTableName'];
                    //插入合同
                    $newId = parent :: add_d($val);
                    //插入中间表数据
                    $sql = "INSERT INTO oa_contract_initialize(contractId,contractCode,contractType,oldContractId,oldContractType,oldObjCode,oldTableName) VALUES ('$newId','$contractCode','$contractType','$oldId','$oldContractType','$oldObjcode','$oldTableName')";
                    $this->query($sql);

                    //物料确认审批表
                    $linkdao = new model_contract_contract_contequlink();
                    $link = array(
                        "contractId" => $newId,
                        "rObjCode" => $val['objCode'],
                        "contractCode" => $val['contractCode'],
                        "contractName" => $val['contractName'],
                        "contractType" => "oa_contract_contract",
                        "ExaStatus" => '完成',
                        "ExaDTOne" => $val['ExaDT'],
                        "ExaDT" => $val['ExaDT'],
                        "changeTips" => 0,
                        "updateTime" => $val['updateTime'],
                        "updateId" => $val['updateId'],
                        "updateName" => $val['updateName'],
                        "createTime" => $val['createTime'],
                        "createName" => $val['createName'],
                        "createId" => $val['createId']
                    );
                    $linkArr[$oldId] = $linkdao->create($link); //缓存linkId
                }
            }
            //插入从表信息
            //客户联系人
            if (!empty ($object['linkman'])) {
                foreach ($object['linkman'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $linkmanDao = new model_contract_contract_linkman();
                    $newId = $linkmanDao->add_d($val);
                    //插入中间表数据
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_linkman')";
                    $this->query($sql);
                }
                //更新从表与主表的关联关系
                $this->updateFromToList("oa_contract_linkman", $conType, $tablename);
            }
            //			//物料
            if (!empty ($object['orderequ'])) {
                foreach ($object['orderequ'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $val['linkId'] = $linkArr[$oldorderId];
                    $equDao = new model_contract_contract_equ();
                    $newId = $equDao->add_d($val);
                    //插入中间表数据
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_equ')";
                    $this->query($sql);
                }
                //更新从表与主表的关联关系
                $this->updateFromToList("oa_contract_equ", $conType, $tablename);
            }
            //			//开票计划
            if (!empty ($object['invoice'])) {
                foreach ($object['invoice'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $orderInvoiceDao = new model_contract_contract_invoice();
                    $newId = $orderInvoiceDao->add_d($val);
                    //插入中间表数据
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_invoice')";
                    $this->query($sql);
                }
                //更新从表与主表的关联关系
                $this->updateFromToList("oa_contract_invoice", $conType, $tablename);
            }
            //			//收款计划
            if (!empty ($object['receiptplan'])) {
                foreach ($object['receiptplan'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $orderReceiptplanDao = new model_contract_contract_receiptplan();
                    $newId = $orderReceiptplanDao->add_d($val);
                    //插入中间表数据
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_receiptplan')";
                    $this->query($sql);
                }
                //更新从表与主表的关联关系
                $this->updateFromToList("oa_contract_receiptplan", $conType, $tablename);
            }
            //			//培训计划
            if (!empty ($object['trainingplan'])) {
                foreach ($object['trainingplan'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $orderTrainingplanDao = new model_contract_contract_trainingplan();
                    $newId = $orderTrainingplanDao->add_d($val);
                    //插入中间表数据
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_trainingplan')";
                    $this->query($sql);
                }
                //更新从表与主表的关联关系
                $this->updateFromToList("oa_contract_trainingplan", $conType, $tablename);
            }

            //更新 合同负责人所在部门 和  开票类型 、客户类型名称
            $updateProDeptSql = "update oa_contract_contract  c left join  (
												     select  u.USER_ID,u.DEPT_ID,d.DEPT_NAME from user u left join department d on u.DEPT_ID=d.DEPT_ID
												)de   on c.prinvipalId=de.USER_ID set  c.prinvipalDeptId=de.DEPT_ID,c.prinvipalDept=de.DEPT_NAME";
            $this->query($updateProDeptSql);
            $updateinvoiceSql = "update oa_contract_contract c left join oa_system_datadict d on c.invoiceType=d.dataCode set c.invoiceTypeName=d.dataName";
            $this->query($updateinvoiceSql);
            $updateCusTypeSql = "update oa_contract_contract c left join oa_system_datadict d on c.customerType=d.dataCode set c.customerTypeName=d.dataName";
            $this->query($updateCusTypeSql);
            $this->commit_d();
            //$this->rollBack();

            return true;
        } catch (exception $e) {
            $this->rollBack();
            return $e;
        }
    }

    /**
     * 更新主表与从表的关联关系
     */
    function updateFromToList($updateTable, $conType, $tablename)
    {
        $sql = "update " . $updateTable . " e left join (
								  select  f.fromId,f.oldcontractId,i.contractId,i.oldContractType,f.fromType
								  	from oa_contract_initialize_from f left join oa_contract_initialize i on f.oldcontractId=i.oldContractId
								  	   where i.oldContractType='" . $conType . "'  and fromType='" . $tablename . "'
								) ef on e.id=ef.fromId set e.contractId=ef.contractId  where e.contractId is null";
        $this->query($sql);
    }
    /**************************************************合同设备统计操作 start****************************************************/
    /**
     * 采购设备-计划数组
     */
    function pageEqu_d()
    {
        $stockDao = new model_stock_stockinfo_systeminfo();
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $datadictDao = new model_system_datadict_datadict();
        $searchArr = $this->__GET("searchArr");
        $this->__SET('searchArr', $searchArr);
        $this->groupBy = 'productId';
        $rows = $this->getPagePlan("select_equ");
        $equIdArr = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $key => $val) {
                $equIdArr[] = $val['productId'];
            }
            $equIdStr = implode(',', $equIdArr);
            $stockArr = $stockDao->get_d(1);
            $equInvInfo = $inventoryDao->getInventoryInfos($stockArr['salesStockId'], $equIdStr);
            foreach ($rows as $key => $val) {
                $rows[$key]['inventoryNum'] = 0;
                foreach ($equInvInfo as $k => $v) {
                    if ($val['productId'] == $v['productId']) {
                        $rows[$key]['inventoryNum'] = $v['exeNum'];
                        $rows[$key]['remainNum'] = $val['number'] - $val['executedNum'];
                    }
                }
            }
            $i = 0;
            foreach ($rows as $key => $val) {
                $searchArr = $this->__GET("searchArr");
                $searchArr['productId'] = $val['productId'];
                $this->groupBy = "id";
                $this->sort = "id";
                $this->searchArr = $searchArr;
                $chiRows = $this->listBySqlId("select_cont");
                foreach ($chiRows as $key => $val) {
                    $chiRows[$key]['contractType'] = $datadictDao->getDataNameByCode($val['contractType']);
                }
                $rows[$i]['childArr'] = $chiRows;
                ++$i;
            }
            return $rows;
        } else {
            return false;
        }
    }

    /**采购管理-采购计划-设备清单显示模板
     *author can
     *2011-3-23
     */
    function showEqulist_s($rows)
    {
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            $addAllonWayNum = "";
            foreach ($rows as $key => $val) {
                $i++;
                $addAllAmount = 0;
                $strTab = "";
                foreach ($val['childArr'] as $chdKey => $chdVal) {
                    //					$i++;
                    $iClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                    //					if( isset( $chdVal['amountIssued']) && $chdVal['amountIssued']!="" ){
                    //						$amountOk = $chdVal['amountAll'] - $chdVal['amountIssued'];
                    //					}else{
                    //						$amountOk = $chdVal['amountAll'];
                    //					}
                    $addAllAmount += $chdVal['number'] - $chdVal['executedNum'];
                    $addAllonWayNum += $chdVal['onWayNum'];
                    $inventoryNum = $rows[$key]['inventoryNum'];

                    //					if( $amountOk==0 || $amountOk=="" ){
                    //						$checkBoxStr =<<<EOT
                    //				        	$chdVal[basicNumb]
                    //EOT;
                    //					}else{
                    //						<input type="checkbox" class="checkChild" >
                    //						$checkBoxStr =<<<EOT
                    //				    	$chdVal[orderTempCode]<input type="hidden" class="hidden" value="$chdVal[orgid]"/>
                    //EOT;
                    //					}

                    $strTab .= <<<EOT
						<tr align="center" height="28" class="$iClass">
			        		<td width="20%">
						    	$chdVal[contractCode]
					        </td>
					        <td  width="10%">
					             $chdVal[contractType]
					        </td>
					        <td  width="8%">
					            $chdVal[number]
					        </td>
					        <td  width="8%">
					            $chdVal[onWayNum]
					        </td>
					        <td  width="8%">
					            $chdVal[executedNum]
					        </td>
		            	</tr>
EOT;
                }

                $str .= <<<EOT
					<tr class="$iClass">
				        <td    height="30" width="4%">
				        	<p class="childImg">
				            <image src="images/expanded.gif" />$i
				        	</p>
				        </td>
				        <td >
				            $val[productNo]<br>$val[productName]
				        </td>
				        <td   width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$addAllAmount</p>
				            </p>
				        </td>
				        <td   width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$addAllonWayNum</p>
				            </p>
				        </td>
				        <td width="8%">
				        	<p class="checkAll">
				            	<p class="checkChildAll"><!--input type="checkbox"-->$inventoryNum</p>
				            </p>
				        </td>
				        <td width="65%" class="tdChange td_table" >
							<table width="100%"  class="shrinkTable main_table_nested">
								$strTab
				        	</table>
				        	<div class="readThisTable"><单击展开物料具体信息></div>
				        </td>
				    </tr>
EOT;
            }
        } else {
            $str = "<tr align='center'><td colspan='50'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /**
     * 合同设备总汇 分页
     * 2011年10月19日 16:24:57
     */
    function getPagePlan($sql)
    {
        $sql = $this->sql_arr[$sql];
        $countsql = "select count(0) as num " . substr($sql, strpos($sql, "from"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //拼装搜索条件
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //构建排序信息
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " order by " . $this->sort . " " . $asc;
        //构建获取记录数
        $sql .= " limit " . $this->start . "," . $this->pageSize;
        //		echo $sql;
        return $this->_db->getArray($sql);
    }
    /**************************************************合同设备统计操作 end***************************************/

    /*****************************************************************************************************************/
    /**
     * 上传EXCEl并导入其数据
     */
    function addFinalceMoneyExecel_d($objNameArr, $importInfo, $normType)
    {
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $excelData = array();
        $fileType = $_FILES["inputExcel"]["type"];

        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $upexcel = new model_contract_common_allcontract();
            $excelData = $upexcel->upExcelData($filename, $temp_name);
            spl_autoload_register('__autoload'); //改变加载类的方式
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr[$rNum][$fieldName] = $row[$index];
                    }
                }
                $arrinfo = array(); //导入结果
                foreach ($objectArr as $k => $v) {
                    //                	 //判断合同是否存在
                    $isBe = $this->findContract($v['contractCode'], $normType);
                    if (empty ($isBe)) {
                        array_push($arrinfo, array(
                            "docCode" => $v['contractCode'],
                            "result" => "导入失败,合同信息不存在"
                        ));
                    } else {
                        if (count($isBe) > 1) {
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "导入失败,合同号重复请使用业务编号导入"
                            ));
                        } else {
                            //更新合同信息
                            $this->updateConInfo($isBe[0]['id'], $isBe[0]['contractType'], $importInfo, $v);
                            $this->updateGross($isBe[0]['id'], $isBe[0]['contractType']); //更新 毛利，毛利率,财务确认总收入，财务确认总成本（冗余）
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "导入成功！"
                            ));
                        }
                    }
                }
                if ($arrinfo) {
                    echo util_excelUtil :: finalceResult($arrinfo, "导入结果", array(
                        "合同编号",
                        "结果"
                    ));
                }
            } else {
                echo "文件不存在可识别数据!";
            }
        } else {
            echo "上传文件类型不是EXCEL!";
        }

    }

    /**
     * 财务金额导入（按月单独导入）
     */
    function addFinalceMoneyExecelAlone_d($objNameArr, $infoArr, $normType)
    {
        $filename = $_FILES["inputExcel"]["name"];
        $temp_name = $_FILES["inputExcel"]["tmp_name"];
        $excelData = array();
        $fileType = $_FILES["inputExcel"]["type"];

        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $upexcel = new model_contract_common_allcontract();
            $excelData = $upexcel->upExcelData($filename, $temp_name);
            spl_autoload_register('__autoload'); //改变加载类的方式
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr[$rNum][$fieldName] = $row[$index];
                    }
                }
                //   echo "<pre>";
                //   print_R($infoArr);
                //   print_R($objectArr);
                $arrinfo = array(); //导入结果
                foreach ($objectArr as $k => $v) {
                    //合并信息
                    $objectArr = array_merge($v, $infoArr);
                    //判断合同是否存在
                    $isBe = $this->findContract($v['contractCode'], $normType);
                    if (empty ($isBe)) {
                        array_push($arrinfo, array(
                            "docCode" => $v['contractCode'],
                            "result" => "导入失败,合同信息不存在"
                        ));
                    } else {
                        if (count($isBe) > 1) {
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "导入失败,合同号重复请使用业务编号导入"
                            ));
                        } else {
                            //添加导入信息
                            $this->addfinancialInfo($objectArr, $isBe);
                            $this->updateGross($isBe[0]['id'], $isBe[0]['contractType']); //更新 毛利，毛利率,财务确认总收入，财务确认总成本（冗余）
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "导入成功！"
                            ));
                        }
                    }
                }
                if ($arrinfo) {
                    echo util_excelUtil :: finalceResult($arrinfo, "导入结果", array(
                        "合同编号",
                        "结果"
                    ));
                }
            } else {
                echo "文件不存在可识别数据!";
            }
        } else {
            echo "上传文件类型不是EXCEL!";
        }

    }

    /*
	 * 判断合同是否存在
	 */
    function findContract($contractCode, $normType)
    {
        if ($normType == "合同号") {
            $sql = "select id,contractType from oa_contract_contract where contractCode = '" . $contractCode . "' and isTemp=0";
        } else {
            $sql = "select id,contractType from oa_contract_contract where objCode = '" . $contractCode . "' and isTemp=0";
        }
        $cId = $this->_db->getArray($sql);
        return $cId;
    }

    /*
	 * 更新合同信息
	 */
    function updateConInfo($cId, $talbeName, $importInfo, $row)
    {
        if ($importInfo == "deductMoney") {
            //更新退款金额（累加）
            $updatedeductMoneySql = "update oa_contract_contract set deductMoney = deductMoney+" . $row['money'] . " where id=" . $cId . "";
            $this->query($updatedeductMoneySql);
        } else {
            //更新金额信息
            $updateMoneySql = "update oa_contract_contract  set $importInfo=" . $row['money'] . " where id=" . $cId . "";
            $this->query($updateMoneySql);
        }

    }

    /*
	 * 财务金额导入（按月单独导入） 添加方法
	 */
    function addfinancialInfo($row, $conInfo)
    {
        //判断数据是否为重复导入的数据
        $findSql = "select id from oa_contract_finalceMoney where contractId='" . $conInfo[0]['id'] . "' and importMonth='" . $row['importMonth'] . "' and moneyType='" . $row['moneyType'] . "'";
        $findId = $this->_db->getArray($findSql);
        if (!empty ($findId)) {
            //将历史导入数据的 使用标志位改为 1
            foreach ($findId as $k => $v) {
                $updateSql = "update oa_contract_finalceMoney set isUse=1 where id=" . $v['id'] . "";
                $this->query($updateSql);
            }
        }
        $files = "contractType,contractId,contractCode,importMonth,moneyType,moneyNum,importName,importNameId,importDate";
        $values = "'" . $conInfo[0]['contractType'] . "','" . $conInfo[0]['id'] . "','" . $row['contractCode'] . "','" . $row['importMonth'] . "','" . $row['moneyType'] . "','" . $row['money'] . "','" . $row['importName'] . "','" . $row['importNameId'] . "','" . $row['importDate'] . "'";
        $addSql = "insert into oa_contract_finalceMoney (" . $files . ") values (" . $values . ")";
        $this->query($addSql);
    }

    /**
     * 查找按月导入的数据是否重复导入
     */
    function getFimancialImport_d($importMonth, $importSub)
    {
        $month = date("Y") . $importMonth;
        $findSql = "select count(id) as num from oa_contract_finalceMoney where importMonth='" . $month . "' and moneyType='" . $importSub . "'";
        $findId = $this->_db->getArray($findSql);
        if ($findId[0]['num'] == '0') {
            return 0;
        } else {
            return 1;
        }
    }

    /**
     * 根据合同ID更新冗余字段（毛利，毛利率，财务确认总收入，财务确认总成本）
     */
    function updateGross($cId, $contractType)
    {
        //计算财务确认总成本，财务确认总收入
        $sql = "select   (c.serviceconfirmMoney +if(f.serviceconfirmMoney is null or f.serviceconfirmMoney='',0,f.serviceconfirmMoney) ) as serviceconfirmMoney,
			             (c.financeconfirmMoney +if(f.financeconfirmMoney is null or f.financeconfirmMoney='',0,f.financeconfirmMoney) ) as financeconfirmMoney,
			             (c.trialprojectCost +if(f.trialprojectCost is null or f.trialprojectCost='',0,f.trialprojectCost) ) as trialprojectCost,
			             (c.deliveryCosts +if(f.deliveryCosts is null or f.deliveryCosts='',0,f.deliveryCosts) ) as deliveryCosts,
			                   (c.deductMoney +if(f.deductMoney is null or f.deductMoney='',0,f.deductMoney) ) as deductMoney
		    			 from oa_contract_contract c
		    			  left join (
							 SELECT
							   contractId,
							   contractType,
								sum(if(moneyType = 'serviceconfirmMoney',moneyNum,0)) as  serviceconfirmMoney,
								sum(if(moneyType = 'financeconfirmMoney',moneyNum,0)) as  financeconfirmMoney,
								sum(if(moneyType = 'deliveryCosts',moneyNum,0)) as  deliveryCosts,
							    sum(if(moneyType = 'deductMoney',moneyNum,0)) as  deductMoney,
							    sum(if(moneyType = 'trialprojectCost',moneyNum,0)) as  trialprojectCost
							FROM
							   oa_contract_finalceMoney
							WHERE
								isUse = 0
							group by contractId
							)f  on c.id = f.contractId where id=" . $cId . "";
        $infoArr = $this->_db->getArray($sql);
        $serviceconfirmMoney = $infoArr[0]['serviceconfirmMoney']; //财务确认总收入
        $financeconfirmMoney = $infoArr[0]['financeconfirmMoney']; //财务确认总成本
        $deliveryCosts = $infoArr[0]['deliveryCosts']; //交付成本
        $deductMoney = $infoArr[0]['deductMoney']; //扣款金额
        $trialprojectCost = $infoArr[0]['trialprojectCost'];
        //计算毛利、毛利率
        $gross = round($serviceconfirmMoney - $financeconfirmMoney, 2);
        $rateOfGrossTemp = bcdiv($gross, $serviceconfirmMoney, 8);
        $rateOfGross = round(bcmul($rateOfGrossTemp, '100', 8),2);
        //更新冗余值
        $updateSql = "update oa_contract_contract set gross=" . $gross . ",rateOfGross=" . $rateOfGross . ",serviceconfirmMoneyAll=" . $serviceconfirmMoney . ",financeconfirmMoneyAll=" . $financeconfirmMoney . ",deliveryCostsAll=" . $deliveryCosts . ",trialprojectCostAll =" . $trialprojectCost . " where id=" . $cId . "";
        $this->query($updateSql);
    }

    /**
     * 获取财务导入金额详细信息
     */
    function getFinancialDetailInfo($conId, $tablename, $moneyType)
    {
        $sql = "SELECT
								LEFT(c.importMonth,4) as year,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'01') ,c.moneyNum,0)) as January,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'02') ,c.moneyNum,0)) as February,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'03') ,c.moneyNum,0)) as March,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'04') ,c.moneyNum,0)) as April,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'05') ,c.moneyNum,0)) as May,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'06') ,c.moneyNum,0)) as June,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'07') ,c.moneyNum,0)) as July,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'08') ,c.moneyNum,0)) as August,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'09') ,c.moneyNum,0)) as September,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'10') ,c.moneyNum,0)) as October,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'11') ,c.moneyNum,0)) as November,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'12') ,c.moneyNum,0)) as December
							FROM
								oa_contract_finalcemoney c

							  where c.isUse=0 and c.contractId=" . $conId . "  and moneyType='" . $moneyType . "'

							GROUP BY LEFT(c.importMonth,4)";
        //	echo $sql;
        $rows = $this->_db->getArray($sql);
        //初始化金额
        $initSql = "select " . $moneyType . " from oa_contract_contract where id=" . $conId . "";
        $initMoney = $this->_db->getArray($initSql);
        $initarr = array(
            "year" => "初始化金额",
            "January" => $initMoney[0][$moneyType]
        );
        $rows[] = $initarr;
        return $rows;
    }

    function getFinancialImportDetailInfo($conId, $tablename, $moneyType)
    {
        $sql = "select concat(LEFT(c.importMonth,4),'年',RIGHT(c.importMonth,2),'月') as improtMonth,
								       (case c.moneyType when 'serviceconfirmMoney' then '服务确认收入'
								                         when 'financeconfirmMoney' then '财务确认总成本'
								                         when 'deliveryCosts' then '交付成本'
											             when 'deductMoney' then '扣款金额'  end)  as moneyType,
								       c.moneyNum,c.moneyNum,c.importDate,c.importName,c.isUse
								  from oa_contract_finalcemoney c  where c.contractId=" . $conId . "  and moneyType='" . $moneyType . "'";
        //		$rows = $this->_db->getArray($sql);
        return $sql;
    }

    /**
     * 财务金额统计列表 获取数据方法
     */
    function getfinancialStatistics($conId, $tablename, $moneyType)
    {
        $sql = "select
								LEFT(c.importMonth,4) as year,c.contractCode,c.moneyType,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'01') ,c.moneyNum,0)) as January,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'02') ,c.moneyNum,0)) as February,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'03') ,c.moneyNum,0)) as March,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'04') ,c.moneyNum,0)) as April,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'05') ,c.moneyNum,0)) as May,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'06') ,c.moneyNum,0)) as June,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'07') ,c.moneyNum,0)) as July,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'08') ,c.moneyNum,0)) as August,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'09') ,c.moneyNum,0)) as September,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'10') ,c.moneyNum,0)) as October,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'11') ,c.moneyNum,0)) as November,
								sum(IF (c.importMonth = concat(LEFT(c.importMonth,4),'12') ,c.moneyNum,0)) as December
							from
								oa_contract_finalcemoney c

							  where 1=1 and c.isUse=0   ";
        //	echo $sql;
        $this->groupBy = "LEFT(c.importMonth,4),contractCode,c.moneyType";
        $rows = $this->pageBySql($sql);
        return $rows;
    }

    //接上面那个方法的 初始化金额
    function getfinancialStatisticsInitMoney($rows)
    {
        //初始化金额
        foreach ($rows as $key => $val) {
            $moneyType = $val['moneyType'];
            $initSql = "select " . $moneyType . " from oa_contract_contract where contractCode='" . $val['contractCode'] . "'";
            $initMoney = $this->_db->getArray($initSql);
            $rows[$key]["initMoney"] = $initMoney[0][$moneyType];
        }
        return $rows;
    }

    /**
     * 获取合同处理轨迹
     */
    function getContractTracks($contractId)
    {
        $conTrackDao = new model_contract_contract_tracks();
        function checkDateT($date)
        {
            if (!empty ($date) && $date != "0000-00-00" && $date != "0000-00-00 00:00:00") {
                return true;
            }
            return false;
        }

        $trackArr = array();
        $contract = $this->get_d($contractId);
        if (checkDateT($contract['ExaDTOne'])) {
            // 获取节点进度记录数据 ID2243
            $beginTrack = $conTrackDao->getRecord($contractId,'contractBegin','max');
            $begin_exePortion = ($beginTrack['msg'] == 'ok')? $beginTrack['data']['exePortion']: '';
            //合同开始执行
            $trackArr[] = array(
                "time" => $contract['ExaDTOne'],
                "exePortion" => $begin_exePortion,
                "contractBegin" => 1,
                "sort" => 10
            );
        }
        if (checkDateT($contract['completeDate'])) {
            // 获取节点进度记录数据 ID2243
            $completeTrack = $conTrackDao->getRecord($contractId,'contractComplete','max');
            $complete_exePortion = ($completeTrack['msg'] == 'ok')? $completeTrack['data']['exePortion']: '';
            //合同执行结束
            $trackArr[] = array(
                "time" => $contract['completeDate'],
                "exePortion" => $complete_exePortion,
                "completeDate" => 1,
                "sort" => 70
            );
        }
        if (checkDateT($contract['signinDate'])) {
            // 获取节点进度记录数据 ID2243
            $signinTrack = $conTrackDao->getRecord($contractId,'contractSignIn','max');
            $signin_exePortion = ($signinTrack['msg'] == 'ok')? $signinTrack['data']['exePortion']: '';
            //签收纸质合同
            $trackArr[] = array(
                "time" => $contract['signinDate'],
                "exePortion" => $signin_exePortion,
                "signinDate" => 1,
                "sort" => 60
            );
        }
        if (checkDateT($contract['closeTime'])) {
            // 获取节点进度记录数据 ID2243
            $closeTrack = $conTrackDao->getRecord($contractId,'contractClose','max');
            $close_exePortion = ($closeTrack['msg'] == 'ok')? $closeTrack['data']['exePortion']: '';
            //合同关闭
            $trackArr[] = array(
                "time" => substr($contract['closeTime'],
                    0,
                    10
                ), "closeTime" => 1, "exePortion" => $close_exePortion,"sort" => 80);
        }
        $contractMoney = $contract['contractMoney'] - $contract['deductMoney'] - $contract['badMoney']; //减去扣款及坏账的合同金额

        //获取所有开票记录
        $invoiceDao = new model_finance_invoice_invoice();
        $invoices = $invoiceDao->getInvoices_d($contractId, "KPRK-12");
        $allInvoiceMoney = 0;
        $i = 20;
        // 获取节点进度记录数据 ID2243
        $invoiceTrack = $conTrackDao->getRecord($contractId,'invoiceMoney','match','expand1');
        $invoice_exePortionArr = ($invoiceTrack['msg'] == 'ok')? $invoiceTrack['data']['detail']: array();
        foreach ($invoices as $key => $val) {
            if ($val['isRed'] == 1) {
                $val['invoiceMoney'] = -$val['invoiceMoney'];
            }
            $arr = array(
                "time" => $val['invoiceTime'],
                "exePortion" => isset($invoice_exePortionArr[$val['id']]['exePortion'])? $invoice_exePortionArr[$val['id']]['exePortion'] : "",
                "invoiceMoney" => $val['invoiceMoney'],
                "sort" => $i++
            );
            $allInvoiceMoney += $val['invoiceMoney'];
            $allInvoiceMoney += $contract['uninvoiceMoney'];
            if ($allInvoiceMoney >= $contractMoney) {
                $arr['invoiceComplete'] = 1;
            }
            $trackArr[] = $arr;
        }

        //获取所有到款记录
        $incomeDao = new model_finance_income_incomeAllot();
        $incomes = $incomeDao->getIncomes_d($contractId, "KPRK-12");
        $j = 40;
        $allIncomeMoney = "";

        // 获取节点进度记录数据 ID2243
        $incomeTrack = $conTrackDao->getRecord($contractId,'incomeMoney','match','expand1');
        $income_exePortionArr = ($incomeTrack['msg'] == 'ok')? $incomeTrack['data']['detail']: array();
        foreach ($incomes as $key => $val) {
            $arr = array(
                "time" => $val['allotDate'],
                "exePortion" => isset($income_exePortionArr[$val['id']]['exePortion'])? $income_exePortionArr[$val['id']]['exePortion'] : "",
                "incomeMoney" => $val['money'],
                "sort" => $j++
            );
            $allIncomeMoney += $val['money'];
            if ($allIncomeMoney >= $contractMoney) {
                $arr['incomeComplete'] = 1;
            }
            $trackArr[] = $arr;
        }

        function cmp($a, $b)
        {
            // if (strtotime($a['time']) == $b['time']) return 0;
            if (strtotime($a['time']) - strtotime($b['time']) == 0) {
                return ($a['sort']) < $b['sort'] ? -1 : 1;
            }
            return (strtotime($a['time']) < strtotime($b['time'])) ? -1 : 1;
        }

        usort($trackArr, "cmp");
        return $trackArr;
    }

    /*************以下为portlet相关信息******************************/
    /**
     * 最近新增的合同
     */
    function getLastAddContractNum()
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='完成' and isTemp=0 and TO_DAYS(NOW()) - TO_DAYS(createTime) <= 15";
        return $this->queryCount($sql);
    }

    /**
     * 最近变更的合同
     */
    function getLastChangeContractNum()
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='完成' and isTemp=0 and TO_DAYS(NOW()) - TO_DAYS(ExaDT) <= 15 and becomeNum>0";
        return $this->queryCount($sql);
    }

    /**
     * 签约一个月未完成合同
     */
    function getMonthContractNum($month)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where signStatus='1' and isTemp=0 and TO_DAYS(NOW()) - TO_DAYS(signDate) between $month*30 and ($month+1)*30";
        return $this->queryCount($sql);
    }

    /**
     * 未执行的发货需求
     */
    function getNotRunShipNum($condition = null)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='完成' and isTemp=0 and DeliveryStatus='WFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * 执行中的发货需求
     */
    function getRunningShipNum($condition = null)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='完成' and isTemp=0 and DeliveryStatus='BFFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * 星级的发货需求
     */
    function getReterStarShipNum()
    {
        $sql = "SELECT COUNT(*) as number,reterStart FROM oa_contract_contract where ExaStatus='完成'
						and isTemp=0 GROUP BY reterStart";
        return $this->listBySql($sql);
    }

    /**
     * 延期的发货需求
     */
    function getdelayShipNum($condition)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='完成' and isTemp=0 and DeliveryStatus='BFFH'  and TO_DAYS(NOW()) - TO_DAYS(signDate) > 0" . $condition;
        return $this->queryCount($sql);
    }

    /**
     * portlet发货需求页面渲染
     */
    function showCountShipPage_d()
    {
        $str = ""; //返回的模板字符串
        $shipTypeDao = new model_projectmanagent_shipment_shipmenttype();
        $typeArr = $shipTypeDao->list_d();
        $notRunShipNum = $this->getNotRunShipNum();
        $runningShipNum = $this->getRunningShipNum();
        $reterStarNumArr = $this->getReterStarShipNum();
        $delayNum = $this->getdelayShipNum(null);
        $NotRunShipTypeStr = "";
        $runningShipTypeStr = "";
        $starTypeStr = "";
        //未执行的发货需求
        if (is_array($typeArr) && count($typeArr) > 0) {
            $NotRunShipTypeStr = '<ul>';
            $runningShipTypeStr = '<ul>';
            foreach ($typeArr as $key => $val) {
                $number = $this->getNotRunShipNum('and customTypeId=' . $val['id']);
                $i = $key + 1;
                $typeId = $val['id'];
                $typeName = $val['type'];
                $NotRunShipTypeStr .= "<li>$i.[$typeName]类发货需求：<a id='NotRunNumHref_$typeId' href='javascript:void(0)' >
									<span id='notRunShipNum_$typeId'></span>$number</a>个。
									<input type='hidden' id='v_notRunShipNum_$typeId' value='$typeId'/></li>";
            }
            $NotRunShipTypeStr .= "</ul>";
            foreach ($typeArr as $key => $val) {
                $number = $this->getRunningShipNum('and customTypeId=' . $val['id']);
                $i = $key + 1;
                $typeId = $val['id'];
                $typeName = $val['type'];
                $runningShipTypeStr .= "<li>$i.[$typeName]类发货需求：<a id='RunningNumHref_$typeId' href='javascript:void(0)' >
									<span id='runningShipNum_$typeId'></span>$number</a>个。
									<input type='hidden' id='v_runningShipNum_$typeId' value='$typeId'/></li>";
            }
            $runningShipTypeStr .= "</ul>";
        }
        $starTypeStr .= "<ul>";
        for ($i = 0, $star = 5; $i <= $star; $i++) {
            $starNum = 0;
            $k = $i + 1;
            foreach ($reterStarNumArr as $key => $val) {
                if ($val['reterStart'] == $i) {
                    $starNum = $val['number'];
                }
            }
            $starTypeStr .= "<li>$k.[$i]星的发货需求：<a id='starNumHref_$i' href='javascript:void(0)' >
							<span id='starShipNum_$i'></span>$starNum</a>个。
							<input type='hidden' id='v_starShipNum_$i' value='$i'/></li>";
        }
        $starTypeStr .= "</ul>";
        $str .= <<<EOT
			<ul>
				<li>一、未执行的发货需求<a id="NotRunNumHref" href="javascript:void(0)" ><span id="notRunShipNum">$notRunShipNum</span></a>个。
					$NotRunShipTypeStr
				</li>
				<li>二、执行中的发货需求<a id="RunningNumHref" href="javascript:void(0)" ><span id="runningShipNum">$runningShipNum</span></a>个。
					$runningShipTypeStr
				</li>
				<li>三、按星级统计发货需求$starTypeStr
				</li>
				<li>四、延期的发货需求<a id="delayNumHref" href="javascript:void(0)" ><span id="delayShipNum">$delayNum</span></a>个。
				</li>
			</ul>
EOT;
        return $str;
    }
    /*************portlet相关信息结束******************************/

    /**
     * 根据合同ID  改变合同状态
     */
    function updateContractState($contractid)
    {
        try {
            $this->start_d();
            $rows = $this->getContractInfo($contractid);
            if ($rows['ExaStatus'] == '完成') {
                $DeliveryStatus = $rows['DeliveryStatus'];
                $contractType = $rows['contractType'];
                $closeType = $rows['closeType'];
                $objCode = $rows['objCode'];
                $date = date("Y-m-d");
                //获取管理项目状态
                $projectStateDao = new model_engineering_project_esmproject();
                $projectState = $projectStateDao->checkIsCloseByRobjcode_d($objCode);
                //判断合同是否有发货需求
                if (empty ($rows['equ'])) {
                    $shipTips = 0;
                } else {
                    $shipTips = 1;
                }
                // if ($contractType == "HTLX-FWHT" && $projectState != 2) {
                if($projectState != 2){
                    if ($shipTips == 0) {
                        if ($projectState == 1) {
                            $state = 4;
                        } else {
                            $state = 2;
                        }
                    } else {
                        if ($projectState == 1 && ($DeliveryStatus == "YFH" || $DeliveryStatus == "TZFH")) {
                            $state = 4;
                        } else {
                            $state = 2;
                        }
                    }
                } else {
                    if ($DeliveryStatus == "YFH" || $DeliveryStatus == "TZFH") {
                        $state = 4;
                    } else {
                        $state = 2;
                    }
                }

                if($closeType == "异常关闭"){// 有异常关闭审批通过的合同，若开启项目，待项目再次关闭的时候自动触发合同状态改为异常关闭 PMS 2789
                    $state = 7;
                    $updateSql = "update oa_contract_contract set state=" . $state . ",completeDate='" . $date . "' where id=" . $rows['id'] . "";
                }else if ($state == '4') {
                    $updateSql = "update oa_contract_contract set state=" . $state . ",completeDate='" . $date . "',outstockDate='" . $date . "' where id=" . $rows['id'] . "";

                    // 执行轨迹记录
                    $trackArr['contractId'] = $rows['id'];$trackArr['modelName'] = 'contractComplete';
                    $trackArr['operationName'] = '合同执行结束';$trackArr['result'] = $state;
                    $trackArr['time'] = $date;$trackArr['expand2'] = 'oa_contract_contract_contract:updateContractState';
                    $this->addTracksRecord($trackArr);

                } else {
                    $updateSql = "update oa_contract_contract set state=" . $state . " where id=" . $rows['id'] . "";
                }

                $this->query($updateSql);

                if($closeType != "异常关闭"){
                    //判断合同是否需要关闭
                    $this->updateContractClose($rows['id']);
                }
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }

    }

    /**
     * 自动关闭合同的方法-用于预警
     */
    function autoContractClose_d($obj)
    {
        return $this->updateContractClose($obj['id'], false); // 预警查出来的数据无须进一步验证
    }

    /**
     * 根据合同ID 判断合同是否需要关闭
     * @param $contractId 合同ID
     * @param $needCheck 是否需要进一步验证，默认为true
     * @return
     */
    function updateContractClose($contractId, $needCheck = true)
    {
        try {
            $this->start_d();

            $isMeet = true; // 默认满足
            $conArr = $this->get_d($contractId);
            $projectStateDao = new model_engineering_project_esmproject();
            $projectState = $projectStateDao->checkIsCloseByRobjcode_d($conArr['objCode'], true);
            if ($needCheck) {
                $isMeet = $this->isMeetClose($contractId);
            }
            if ($isMeet && ($projectState == '3' || $projectState == '2')) {
                $date = date("Y-m-d");
                $updateSql = "update oa_contract_contract set winRate='100%',state=3,closeTime='" . $date . "',closeType='正常关闭',closeRegard='系统自动关闭' where id=" . $contractId . "";
                $this->query($updateSql);

                // 执行轨迹记录
                $trackArr['contractId'] = $contractId;$trackArr['modelName'] = 'contractClose';
                $trackArr['operationName'] = '合同关闭';$trackArr['result'] = 3;
                $trackArr['time'] = $date;$trackArr['expand2'] = 'oa_contract_contract_contract:updateContractClose';
                $this->addTracksRecord($trackArr);
            }

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }

    }

    /**
     * 根据合同ID 进一步判断合同是否可以关闭
     * 条件1：合同状态为完成    c.state = '4'
     * 条件2：签收状态为已签收  c.signStatus = '1'
     * 条件3：未开票金额为0  c.contractMoney - c.deductMoney - c.invoiceMoney = 0
     * 条件4：财务未收款为0  c.invoiceMoney - c.incomeMoney - c.deductMoney - c.badMoney = 0
     * 条件5：合同未收款为0  c.contractMoney - c.incomeMoney - c.deductMoney - c.badMoney = 0
     * 条件6：发货完成6个月  DATEDIFF(CURDATE(), o.auditDate) >= 180
     *  //2017-06-23 PMS2603 更改为如下条件
     * 1.合同状态为完成    c.state = '4'
     * 2.未开票金额为0  c.contractMoney - c.deductMoney - c.invoiceMoney = 0
     * 3.合同未收款为0  c.contractMoney - c.incomeMoney - c.deductMoney - c.badMoney = 0
     * @param $contractid 合同ID
     * @return
     */
    function isMeetClose($contractid)
    {
        $sql = "SELECT
					c.id
				FROM
					oa_contract_contract c
				LEFT JOIN (
					SELECT
						max(auditDate) AS auditDate,
						contractId
					FROM
						oa_stock_outstock
					WHERE
						docStatus = 'YSH'
					AND (relDocType = 'XSCKFHJH')
					AND contractId = {$contractid}
					GROUP BY
						contractId
				) o ON c.id = o.contractId
				WHERE
					c.isTemp = 0
				AND (
					c.DeliveryStatus = 'YFH'
					OR c.DeliveryStatus = 'TZFH'
				)
				AND c.state = '4'
				AND (
				    (c.contractMoney - c.deductMoney - c.invoiceMoney - uninvoiceMoney <= 0)
				    OR (FIND_IN_SET('HTBKP',c.invoiceCode) > 0)
				)
				AND c.contractMoney - c.incomeMoney - c.deductMoney - c.badMoney <= 0
				AND c.id = {$contractid}";
        $rs = $this->_db->getArray($sql);

        if (empty($rs)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 合同信息列表工作量进度
     */
    function projectProcess_d($rows)
    {
        foreach ($rows as $key => $val) {
            if ($val['contractType'] == 'HTLX-FWHT') {
                $sql = "select count(id) as equNum from oa_contract_equ where contractId=" . $val['id'] . "";
                $equNum = $this->_db->getArray($sql); //判断物料
                $proProcessSql = "select projectProcess from oa_esm_project where rObjCode = '" . $val['objCode'] . "'";
                $proProcess = $this->_db->getArray($proProcessSql);
                if (empty ($proProcess)) {
                    $process = "0.00";
                } else {
                    $process = $proProcess[0]['projectProcess'];
                }
                if ($equNum[0]['equNum'] == '0') {
                    $processE = $process;
                } else {
                    if ($val['state'] == '4') {
                        $processE = $process;
                    } else {
                        $processE = "0.00";
                    }
                }
            } else {
                if ($val['state'] == '4') {
                    $processE = "100.00";
                } else {
                    $processE = "0.00";
                }
            }
            $conMoney = $val['contractMoney'];
            if (empty ($val['deductMoney'])) {
                $deductMoney = "0";
            } else {
                $deductMoney = $val['deductMoney'];
            }
            $processMoney = ($processE / 100) * ($conMoney - $deductMoney);
            $rows[$key]['projectProcess'] = $processE;
            $rows[$key]['processMoney'] = $processMoney;
        }
        return $rows;
    }

    /**
     * 更新合同冗余字段（工作量进度，按工作量进度执行合同额）
     * 参数说明
     * $type = objcode $param 为业务编号。
     * 否则 默认 $param 为数组格式如下
     * array("id"=>"xx","tablename"=>"xx") id为合同id contractType为合同类型
     */
    function updateProjectProcess($param, $type = "")
    {
        //根据业务编号获取合同信息
        if ($type == "objCode") {
            $sql = "select id,contractType,projectProcess,state,contractMoney,deductMoney,objCode from oa_contract_contract where objCode='" . $param . "'";
        } else {
            $sql = "select id,contractType,projectProcess,state,contractMoney,deductMoney,objCode from oa_contract_contract where id='" . $param['id'] . "'";
        }
        $orderInfoArr = $this->_db->getArray($sql);
        $orderInfo = $orderInfoArr[0];

        $sql = "select count(id) as equNum from oa_contract_equ where contractId=" . $orderInfo['id'] . "";
        $tablename = $orderInfo['tablename'];
        $equNum = $this->_db->getArray($sql); //判断物料
        $proProcess = $orderInfo['projectProcess'];
        if (empty ($proProcess)) {
            if ($orderInfo['state'] == '4') {
                $process = "100.00";
            } else {
                $process = "0.00";
            }
        } else {
            $process = $proProcess;
        }
        if ($equNum[0]['equNum'] == '0') {
            $processE = $process;
        } else {
            if ($orderInfo['state'] == '4') {
                $processE = $process;
            } else {
                $processE = "0.00";
            }
        }
        if (empty ($orderInfo['contractMoney'])) {
            $conMoney = "0.00";
        } else {
            $conMoney = $orderInfo['contractMoney'];
        }
        if (empty ($orderInfo['deductMoney'])) {
            $deductMoney = "0";
        } else {
            $deductMoney = $orderInfo['deductMoney'];
        }
        $processMoney = ($processE / 100) * ($conMoney - $deductMoney);
        //更新冗余值
        $updateSql = "update oa_contract_contract set projectProcessAll=" . $processE . ",processMoney=" . $processMoney . " where id=" . $orderInfo['id'] . "";
        $this->query($updateSql);

    }

    /**
     * 合同导入 add方法
     */
    function importAdd_d($object, $isAddInfo = false)
    {
        if ($isAddInfo) {
            $object = $this->addCreateInfo($object);
        }
        //加入数据字典处理 add by chengl 2011-05-15
        $newId = $this->create($object);
        return $newId;
    }

    /**
     * 确认收入核算方式
     */
    function incomeAccEdit_d($obj)
    {
        try {
            $this->start_d();
            $incomeAccounting = $obj['incomeAccounting'];
            $contractId = $obj['contractId'];
            $updateSql = "update oa_contract_contract set incomeAccounting='" . $incomeAccounting . "' where id=" . $contractId . "";
            $this->query($updateSql);

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }

    }

    /**
     * 关闭物料确认
     */
    function setEmailAfterCloseConfirm($id)
    {
        try {
            $this->start_d();
            $linkDao = new model_contract_contract_contequlink();
            $linkDao->update(array('contractId' => $id), array('ExaStatus' => '完成', 'ExaDT' => day_date));
            $addMsg = '该合同无需变更物料确认。';
            $mainObj = $this->getContractInfo($id);
            $updateKey = array(
                'dealStatus' => '3'
            );
            $this->update(array(
                'id' => $id
            ), $updateKey);
            $outmailArr = array(
                $mainObj['prinvipalId']
            );
            $outmailStr = implode(',', $outmailArr);
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'oa_contract_equ', '关闭', $mainObj['contractCode'], $outmailStr, $addMsg, '1');

            $this->commit_d();
            return 1;
        } catch (exception $e) {
            $this->rollBack();
            return 0;
        }
    }

    /**
     * 根据合同id 获取更新合同审批人（审批通过的审批人）
     */
    function contractAppArr($contractId)
    {
        if (!empty($contractId)) {
            $sql = "select f.`User` from wf_task w left join flow_step_partent f on w.task = f.Wf_task_id where w.Pid = '$contractId' and (w.name = '合同审批A' or w.name = '合同审批B')";
            $arr = $this->_db->getArray($sql);
        } else {
            $arr = "";
        }
        if (!empty($arr)) {
            $arrStrTemp = "";
            foreach ($arr as $k => $v) {
                $arrStrTemp .= $v['User'] . ",";
            }
            $arrStr = trim($arrStrTemp, ",");
            //           $arrStrArr = explode(",",$arrStr);
        } else {
            $arrStr = "";;
        }
        $updateSql = "update oa_contract_contract set appNameStr = '" . $arrStr . "' where id='" . $contractId . "'";
        $this->query($updateSql);
    }

    /**
     * 列表备注新增方法
     */
    function listremarkAdd_d($object)
    {
        try {
            $this->start_d();

            $dao = new model_contract_contract_remark();
            $object['content'] = nl2br($object['content']);
            $newId = $dao->add_d($object);
            //获取合同信息
            $contractArr = $this->get_d($object['contractId']);
            //发送邮件 ,当操作为提交时才发送
            if ($object['issend'] == 'y') {
                $emailDao = new model_common_mail();
                $contractCode = $contractArr['contractCode'];
                $content = $object['content'];
                $msg = "<span style='color:blue'>合同号</span> ：$contractCode<br/><span style='color:blue'>信息</span> ： $content" .
                    "<br/>相关信息请在沟通板内回复";
                $emailInfo = $emailDao->batchEmail($object['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'contractInfo', '添加了', null, $object['TO_ID'], $msg);
            }

            $this->commit_d();
            //$this->rollBack();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 获取备注数据
     */
    function getRemarkInfo_d($contractId)
    {
        $sql = "select * from oa_contract_remark where contractId=" . $contractId . "";
        $arr = $this->_db->getArray($sql);
        //处理数组
        $arrHTML = "";
        foreach ($arr as $k => $v) {
            $arrHTML .= "<b>" . $v['createName'] . "</b>(" . $v['createTime'] . ") : " . $v['content'] . "<br/>";
        }
        return $arrHTML;
    }

    /**
     *获取有备注信息的合同 数组
     */
    function getRemarkIs()
    {
        $sql = "select contractId from oa_contract_remark  GROUP BY contractId;";
        $arr = $this->_db->getArray($sql);
        foreach ($arr as $k => $v) {
            $arr[$k] = $v['contractId'];
        }
        return $arr;
    }

    /**
     * 修改 财务相关时间
     */
    function financialRelatedDateAdd_d($object)
    {
        try {
            $this->start_d();

            //修改主表信息
            parent :: edit_d($object, true);

            $this->commit_d();
            //$this->rollBack();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 处理付款条件
     */
    function handlePaymentData($rows)
    {
        if (!empty($rows['progresspaymentterm'])) {
            $rows['progresspaymentterm'] = implode(",", $rows['progresspaymentterm']);
        }
        if (!empty($rows['progresspayment'])) {
            $rows['progresspayment'] = implode(",", $rows['progresspayment']);
        }
        if (!empty($rows['otherpaymentterm'])) {
            $rows['otherpaymentterm'] = implode(",", $rows['otherpaymentterm']);
        }
        if (!empty($rows['otherpayment'])) {
            $rows['otherpayment'] = implode(",", $rows['otherpayment']);
        }

        return $rows;
    }

    /**
     * 处理确认成本概算信息
     */
    function handleSubConfirmCose($arr, $rows, $type)
    {
        //确认信息
        $contractId = $arr['contractId']; //确认合同id
        $costId = $arr['id']; //产线审核数据id
        //更新总信息并计算冗余值
        return $this->handleCost($contractId, $costId);
    }

    /**
     * 处理确认成本概算信息(新)
     */
    function handleSubConfirmCoseNew($contractId, $confirmTag)
    {
        //更新总信息并计算冗余值
        return $this->handleCostNew($contractId, $confirmTag);
    }

    /**
     * 根据合同id 获取售前商机费用
     */
    function getChanceCostByid($id)
    {
        $rows = $this->get_d($id);
        if (!empty($rows['chanceId'])) {
            $chanceId = $rows['chanceId'];
            $findSql = "select sum(amount) as trCost from cost_summary_list
			 where ProjectNo in (select projectCode from oa_esm_project where contractType = 'GCXMYD-04' and contractId in (
			  select id from oa_trialproject_trialproject where chanceId = '" . $chanceId . "'
			))";
            $trCostArr = $this->_db->getArray($findSql);
            $trCost = $trCostArr[0]['trCost'];
        }
        $str = "";
        if (!empty($trCost)) {
            $str .= <<<EOT
			     <tr style='background-color:#E0E0E0'>
				   <td width='20%'>试用项目费用</td>
				   <td width='10%' class="formatMoney">$trCost</td>
				   <td width='30%'>此费用不算入成本概算，仅供参考</td>
				   <td></td>
				</tr>
EOT;
        }
        return $str;
    }

    /**
     * 处理更新合同成本概算冗余值
     */
    function handleCost($contractId, $costId)
    {
        $invoiceArr = $this->get_d($contractId);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }
//        $invoiceValueArr = explode(",", $invoiceArr['invoiceValue']);
//        array_unshift($invoiceValueArr,'');
        $conInvoiceArr = $this->makeInvoiceValueArr($invoiceArr);
        $rows = $this->getContractInfo($contractId);
        //计算产品线成本（返回结果为数组array('saleCost'=>销售类总成本，'serCost'=>服务类总成本，'allCost'=>所有总成本)）
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $costArr['saleCost']; //销售成本概算
        $saleCostTax = $costArr['saleCostTax']; //销售成本概算（含税）
        $serCost = $costArr['serCost']; //服务成本概算
        $allCost = $costArr['allCost']; //总成本概算
        $allCostTax = $costArr['allCostTax']; //总成本概算（含税）

        //如果是租赁类的合同，计算租赁成本
        //开票金额计算
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// 开票类型含“不开票”的合同直接用合同金额计算
                $typeMoney = isset($invoiceArr['contractMoney'])? $invoiceArr['contractMoney'] : 0;
            }else{
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                    }
                }
            }
        }
//        foreach ($invoiceValueArr as $k => $v) {
//            if (!empty($v)) {
//                $rate = $valArrs[$i] / 100;
//                $rates = 1 + $rate;
//                $typeMoney += round(bcdiv($v, $rates,6),2);
//            }
//            $i++;
//        }
        if ($rows['contractType'] == 'HTLX-ZLHT') {
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //日期天数
            $costEstimates = round(bcadd(bcmul($days, bcdiv($saleCost, 720, 9), 9), $serCost, 9), 2);
            $costEstimatesTax = round(bcadd(bcmul($days, bcdiv($saleCostTax, 720, 9), 9), $serCost, 9), 2);
        } else {
            $costEstimates = $allCost;
            $costEstimatesTax = $allCostTax;
        }
        if (empty($typeMoney) || $typeMoney == '0') {
            $typeMoney = $invoiceArr['contractMoney'];
        }
        //计算预计毛利率
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);
        //判断是否已全部确认完成
        $productLineStr = rtrim($rows['newProLineStr'], ',');
        $confirmFlag = $costDao->confirmCostFlag($productLineStr, $contractId, $costId);

        if ($confirmFlag == "1") {
            return "none";
        } else {
            return $exGross;
        }
    }

    /**
     * 处理更新合同成本概算冗余值(新)
     */
    function handleCostNew($contractId, $confirmTag)
    {
        $invoiceArr = $this->get_d($contractId);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }
//        $invoiceValueArr = explode(",", $invoiceArr['invoiceValue']);
//        array_unshift($invoiceValueArr,'');
        $conInvoiceArr = $this->makeInvoiceValueArr($invoiceArr);
        $rows = $this->getContractInfo($contractId);
        //计算产品线成本（返回结果为数组array('saleCost'=>销售类总成本，'serCost'=>服务类总成本，'allCost'=>所有总成本)）
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $costArr['saleCost']; //销售成本概算
        $saleCostTax = $costArr['saleCostTax']; //销售成本概算（含税）
        $serCost = $costArr['serCost']; //服务成本概算
        $allCost = $costArr['allCost']; //总成本概算
        $allCostTax = $costArr['allCostTax']; //总成本概算（含税）

        //如果是租赁类的合同，计算租赁成本
        //开票金额计算
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// 开票类型含“不开票”的合同直接用合同金额计算
                $typeMoney = isset($invoiceArr['contractMoney'])? $invoiceArr['contractMoney'] : 0;
            }else{
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                    }
                }
            }
        }

//        foreach ($invoiceValueArr as $k => $v) {
//            if (!empty($v)) {
//                $rate = $valArrs[$i] / 100;
//                $rates = 1 + $rate;
//                $typeMoney += round(bcdiv($v, $rates,8),2);
//            }
//            $i++;
//        }
        if ($rows['contractType'] == 'HTLX-ZLHT') {
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //日期天数
            $costEstimates = round(bcadd(bcmul($days, bcdiv($saleCost, 720, 9), 9), $serCost, 9), 2);
            $costEstimatesTax = round(bcadd(bcmul($days, bcdiv($saleCostTax, 720, 9), 9), $serCost, 9), 2);
        } else {
            $costEstimates = $allCost;
            $costEstimatesTax = $allCostTax;
        }
        if (empty($typeMoney) || $typeMoney == '0') {
            $typeMoney = $invoiceArr['contractMoney'];
        }
        //计算预计毛利率
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);

        // 更新变更记录表的成本概算以及毛利率记录(变更时使用)
        $newContract = $this->getContractInfo($contractId);
        if($contractId != $newContract['originalId'] && $newContract['originalId'] != 0){
            $oldId = $newContract['originalId'];
            $oldRows = $this->getContractInfo($oldId);
            $oldExgross = $oldRows['exgross'];
            $oldCostTax = $oldRows['costEstimatesTax'];
            //查找变更记录主表id
            $sql = "select MAX(id) as mid from oa_contract_changlog where objId='" . $oldId . "';";
            $mArr = $this->_db->getArray($sql);
            $mid = $mArr[0]['mid'];
            $delSql = "delete from oa_contract_changedetail where parentId = '$mid' and (changeField = 'costEstimatesTax' or changeField = 'exgross')";
            $this->query($delSql);

            $oldCostTax = sprintf("%.2f", $oldCostTax);
            $costEstimatesTax = sprintf("%.2f", $costEstimatesTax);
            $oldExgross = sprintf("%.2f", $oldExgross);
            $exGross = sprintf("%.2f", $exGross);
            $sql = "INSERT INTO oa_contract_changedetail ( `parentId`, `parentType`, `objId`, `objField`, `detailTypeCn`, `detailType`, `tempId`, `detailId`, `changeFieldCn`, `changeField`, `oldValue`, `newValue`) " .
                "VALUES ('$mid', 'contract', '$oldId', '', '合同管理', 'contract', '$contractId', NULL, '成本概算（含税）', 'costEstimatesTax', '$oldCostTax', '$costEstimatesTax')," .
                "('$mid', 'contract', '$oldId', '', '合同管理', 'contract', '$contractId', NULL, '预计毛利率', 'exgross', '$oldExgross', '$exGross')";
            $this->query($sql);
        }

        //判断是否已全部确认完成
        if (!empty($rows['xfProLineStr'])) { // 产品线数量,此处记得去重
            $productLineNum = count(array_unique(explode(',', rtrim($rows['xfProLineStr'], ','))));
        } else {
            $productLineNum = count(array_unique(explode(',', rtrim($rows['newProLineStr'], ','))));
        }
        $costNum = $costDao->findCount(array('contractId' => $contractId)); // 成本概算记录数量
        $appCostNum = $costDao->findCount(array('contractId' => $contractId, 'state' => '1')); // 成本概算有效条数，解决混合类合同，物料确认完后，销售未确认，但服务确认后会提交审批问题
        if ($confirmTag == "3") {
            $appCostEquNum = $costDao->findCount(array('contractId' => $contractId, 'state' => '3'));
            $appCostNum += $appCostEquNum;
        }
        if ($productLineNum != $costNum) { // 产品线数量不等于成本概算记录数量,则确认未完成
            return "none";
        } else {
            if ($costNum != $appCostNum) {
                return "none";
            } else {
                return $exGross;
            }
        }
    }

    /**
     * 重新计算成本概算
     */
    function countCost($contractId, $change = null)
    {
        $invoiceArr = $this->get_d($contractId);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }
//        $invoiceValueArr = explode(",", $invoiceArr['invoiceValue']);
//        array_unshift($invoiceValueArr,'');
        $conInvoiceArr = $this->makeInvoiceValueArr($invoiceArr);
        $rows = $this->getContractInfo($contractId);
        //计算产品线成本（返回结果为数组array('saleCost'=>销售类总成本，'serCost'=>服务类总成本，'allCost'=>所有总成本)）
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $costArr['saleCost']; //销售成本概算
        $saleCostTax = $costArr['saleCostTax']; //销售成本概算（税收）
        $serCost = $costArr['serCost']; //服务成本概算
        $allCost = $costArr['allCost']; //总成本概算
        $allCostTax = $costArr['allCostTax']; //总成本概算（税收）

        //如果是租赁类的合同，计算租赁成本
        //开票金额计算
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// 开票类型含“不开票”的合同直接用合同金额计算
                $typeMoney = isset($invoiceArr['contractMoney'])? $invoiceArr['contractMoney'] : 0;
            }else{
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                    }
                }
            }
        }
//        foreach ($invoiceValueArr as $k => $v) {
//            if (!empty($v)) {
//                $rate = $valArrs[$i] / 100;
//                $rates = 1 + $rate;
//                $typeMoney += round(bcdiv($v, $rates,8),2);
//            }
//            $i++;
//        }
        if ($rows['contractType'] == 'HTLX-ZLHT') {
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //日期天数
            // 租赁期>=720天的，成本的租赁天数按720天计算。 2014-10-17 罗总要求
            if ($days > "720") {
                $days = "720";
            }
            $costEstimates = round(bcadd(bcmul($days, bcdiv($saleCost, 720, 9), 9), $serCost, 9), 2);
            $costEstimatesTax = round(bcadd(bcmul($days, bcdiv($saleCostTax, 720, 9), 9), $serCost, 9), 2);
        } else {
            $costEstimates = $allCost;
            $costEstimatesTax = $allCostTax;
        }
        if (empty($typeMoney) || $typeMoney == '0') {
            $typeMoney = $invoiceArr['contractMoney'];
        }
        //计算预计毛利率
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);

        //针对变更，更新变更记录表数据
        if ($change == "change") {
            $oldId = $rows['originalId'];
            $oldRows = $this->getContractInfo($oldId);
            $oldExgross = $oldRows['exgross'];
            $oldCostTax = $oldRows['costEstimatesTax'];
            //查找变更记录主表id
            $sql = "select MAX(id) as mid from oa_contract_changlog where objId='" . $oldId . "';";
            $mArr = $this->_db->getArray($sql);
            $mid = $mArr[0]['mid'];
            $delSql = "delete from oa_contract_changedetail where parentId = '$mid' and (changeField = 'costEstimatesTax' or changeField = 'exgross')";
            $this->query($delSql);

            $oldCostTax = sprintf("%.2f", $oldCostTax);
            $costEstimatesTax = sprintf("%.2f", $costEstimatesTax);
            $oldExgross = sprintf("%.2f", $oldExgross);
            $exGross = sprintf("%.2f", $exGross);
            $sql = "INSERT INTO oa_contract_changedetail ( `parentId`, `parentType`, `objId`, `objField`, `detailTypeCn`, `detailType`, `tempId`, `detailId`, `changeFieldCn`, `changeField`, `oldValue`, `newValue`) " .
                "VALUES ('$mid', 'contract', '$oldId', '', '合同管理', 'contract', '$contractId', NULL, '成本概算', 'costEstimatesTax', '$oldCostTax', '$costEstimatesTax')," .
                "('$mid', 'contract', '$oldId', '', '合同管理', 'contract', '$contractId', NULL, '预计毛利率', 'exgross', '$oldExgross', '$exGross')";
            $this->query($sql);
        }
        return $exGross;
    }

    /**
     * 物料确认页面- 动态计算 成本概算和预计毛利率
     */
    function getCostByEqu_d($contractId,$equArr,$isChange = false){
        $equDao = new model_contract_contract_equ();
        $equTaxRate = $equDao->_defaultTaxRate;
        $recordArr = array();
    	//循环计算物料成本
    	$equCostAll = $equCostTaxAll = 0;
    	foreach($equArr as $k => $v){
            if($v['conId'] != '' && $isChange){// 如果是变更物料而且原单有相关物料的,判断是否读原来的单价还是取当前的单价
                $equsql = "select id,productCode,number,productId,price,priceTax from oa_contract_equ where contractId = '{$contractId}' and id = '" . $v['conId'] . "'";
                $equChkArr = $this->_db->getArray($equsql);
                if($equChkArr && isset($equChkArr[0]) && isset($equChkArr[0]['id']) && $equChkArr[0]['number'] == $v['number']){// 如果原来的物料没改数量的话,获取原来新增时保存的单价
                    $equCost = $equChkArr[0]['price'];
                    $recordArr[$k]['chkId'] = $equChkArr[0]['id'];
                }else{// 否则读最新的物料单价
                    $sql = "select priCost from oa_stock_product_info where id='" . $v['productId'] . "'";
                    $equCostArr = $this->_db->getArray($sql);
                    $equCost = $equCostArr[0]['priCost'];
                    $recordArr[$k]['chkId'] = '';
                }

                $recordArr[$k]['price'] = $equCost;
                $recordArr[$k]['number'] = $v['number'];
                $recordArr[$k]['chkSQL'] = $equsql;
                // 按17%税率，计算税后金额
                $equCostTax = bcmul($equCost, bcadd($equTaxRate,1,2), 2)* $v['number'];
                $equCostTaxAll += $equCostTax;
                // 毛利率计算用税前金额
                $equCost = bcmul($equCost, $v['number'], 2);
                $equCostAll += $equCost;
            }else if (isset($v['amount']) && $v['amount'] !== "") {
                $equCostAll += $v['amount'];
//                $equCostTaxAll += $v['amount'];
                $equCostTaxAll += bcmul($v['amount'], bcadd($equTaxRate,1,2), 2);
            } else {
                $sql = "select priCost from oa_stock_product_info where id='" . $v['productId'] . "'";
                $equCostArr = $this->_db->getArray($sql);
                $equCost = $equCostArr[0]['priCost'];

                $recordArr[$k]['price'] = $equCost;
                $recordArr[$k]['number'] = $v['number'];
                // 按17%税率，计算税后金额
                $equCostTax = bcmul($equCost, bcadd($equTaxRate,1,2), 2)* $v['number'];
                $equCostTaxAll += $equCostTax;
                // 毛利率计算用税前金额
                $equCost = bcmul($equCost, $v['number'], 2);
                $equCostAll += $equCost;
            }
    	}

        $invoiceArr = $this->get_d($contractId);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }
//        $invoiceCodeArr = explode(",", $invoiceArr['invoiceCode']);
//        $invoiceValueArr = explode(",", $invoiceArr['invoiceValue']);
//        array_unshift($invoiceValueArr,'');
        $conInvoiceArr = $this->makeInvoiceValueArr($invoiceArr);
        $rows = $this->getContractInfo($contractId);
        //计算产品线成本（返回结果为数组array('saleCost'=>销售类总成本，'serCost'=>服务类总成本，'allCost'=>所有总成本)）
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $equCostAll; //销售成本概算
        $saleCostTax = $equCostTaxAll; //销售成本概算（税收）
        $serCost = $costArr['serCost']; //服务成本概算
        $allCost = $equCostAll + $serCost; //总成本概算
        $allCostTax = $equCostTaxAll + $serCost; //总成本概算(含税)

        //如果是租赁类的合同，计算租赁成本
        //开票金额计算
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// 开票类型含“不开票”的合同直接用合同金额计算
                $typeMoney = isset($invoiceArr['contractMoney'])? $invoiceArr['contractMoney'] : 0;
            }else{
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                    }
                }
            }
        }
//        foreach ($invoiceValueArr as $k => $v) {
//            if (!empty($v)) {
//                $rate = $valArrs[$i] / 100;
//                $rates = 1 + $rate;
//                $typeMoney += round(bcdiv($v, $rates,8),2);
//            }
//            $i++;
//        }
        if ($rows['contractType'] == 'HTLX-ZLHT') {
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //日期天数
            $saleCostTemp = bcdiv($saleCost, 720, 6);
            $saleCostTempTax = bcdiv($saleCostTax, 720, 6);
            // 租赁期>=720天的，成本的租赁天数按720天计算。 2014-10-17 罗总要求
            if ($days > "720") {
                $days = "720";
            }
            $costEstimates = bcadd(bcmul($days, $saleCostTemp, 2), $serCost, 2);
            $costEstimatesTax = bcadd(bcmul($days, $saleCostTempTax, 2), $serCost, 2);
        } else {
            $costEstimates = $allCost;
            $costEstimatesTax = $allCostTax;
        }
        $typeMoney = sprintf("%.3f", $typeMoney);
        if (empty($typeMoney) || $typeMoney == '0') {
            $typeMoney = $invoiceArr['contractMoney'];
            // 按17%税率，计算税后金额
            $typeMoney = bcmul($typeMoney, bcadd($equTaxRate,1,2), 2);
        }
        //计算预计毛利率
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);

        $rtnArr['typeMoney'] = $typeMoney;
        $rtnArr['costEstimatesNoTax'] = $costEstimates;
        $rtnArr['costEstimates'] = $costEstimatesTax;
        $rtnArr['exgross'] = $exGross;
        $rtnArr['recordArr'] = $recordArr;

        return $rtnArr;
    }


    /**
     * 重新计算成本概算
     */
    function countCost2($contractId, $cid)
    {
        $invoiceArr = $this->get_d($contractId);
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }
//        $invoiceCodeArr = explode(",", $invoiceArr['invoiceCode']);
//        $invoiceValueArr = explode(",", $invoiceArr['invoiceValue']);
//        array_unshift($invoiceValueArr,'');
        $conInvoiceArr = $this->makeInvoiceValueArr($invoiceArr);
        $rows = $this->getContractInfo($contractId);
        //计算产品线成本（返回结果为数组array('saleCost'=>销售类总成本，'serCost'=>服务类总成本，'allCost'=>所有总成本)）
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($cid);

        $saleCost = $costArr['saleCost']; //销售成本概算
        $saleCostTax = $costArr['saleCostTax']; //销售成本概算（税收）
        $serCost = $costArr['serCost']; //服务成本概算
        $allCost = $costArr['allCost']; //总成本概算
        $allCostTax = $costArr['allCostTax']; //总成本概算（税收）
        //如果是租赁类的合同，计算租赁成本
        //开票金额计算
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// 开票类型含“不开票”的合同直接用合同金额计算
                $typeMoney = isset($invoiceArr['contractMoney'])? $invoiceArr['contractMoney'] : 0;
            }else{
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                    }
                }
            }
        }
//        foreach ($invoiceValueArr as $k => $v) {
//            if (!empty($v)) {
//                $rate = $valArrs[$i] / 100;
//                $rates = 1 + $rate;
//                $typeMoney += round(bcdiv($v, $rates,8),2);
//            }
//            $i++;
//        }
        if ($rows['contractType'] == 'HTLX-ZLHT') {
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //日期天数
            $saleCostTemp = bcdiv($saleCost, 720, 6);
            $saleCostTempTax = bcdiv($saleCostTax, 720, 6);
            // 租赁期>=720天的，成本的租赁天数按720天计算。 2014-10-17 罗总要求
            if ($days > "720") {
                $days = "720";
            }
            $costEstimates = bcadd(bcmul($days, $saleCostTemp, 2), $serCost, 2);
            $costEstimatesTax = bcadd(bcmul($days, $saleCostTempTax, 2), $serCost, 2);
        } else {
            $costEstimates = $allCost;
            $costEstimatesTax = $allCostTax;
        }
        if (empty($typeMoney) || $typeMoney == '0') {
            $typeMoney = $invoiceArr['contractMoney'];
        }
        //计算预计毛利率
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);
        return $exGross;
    }


    /**
     * 根据合同id获取最近一次变更记录id
     */
    function findChangeId($id)
    {
        $sql = "select max(id) as Mid from oa_contract_contract where originalId = $id";
        $idArr = $this->_db->getArray($sql);
        return $idArr[0]['Mid'];
    }

    /**
     *验证合同编号唯一
     */
    function checkCode_d($code)
    {
        $sql = "select count(id) as num from oa_contract_contract where contractCode = '" . $code . "'";
        $arr = $this->_db->getArray($sql);
        if ($arr[0]['num'] == '0') {
            return "0";
        } else {
            return "1";
        }
    }

    /**
     * 合同收单
     */
    function ajaxAcquiring_d($id, $f = '1')
    {
        $date = date("Y-m-d");

        // 邮件信息
        $contract = $this->get_d($id);
        $otherDataDao = new model_common_otherdatas();
        $sendIds = $otherDataDao->getConfig('contractAcquiringSendIds');
        $uIds = "'".str_replace(",","','",rtrim($sendIds,","))."'";
        $sql = "select GROUP_CONCAT(EMAIL) as address  from user where USER_ID in(".$uIds.")";
        $adrsArr = $this->_db->getArray($sql);
        $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";
        $title = "合同签收通知";
        $ebody = "您好：  合同 {$contract['contractCode']} 已上传纸质合同附件，请知悉，谢谢！";

        try {
            if ($f == '1') {
                $sql = "update oa_contract_contract set isAcquiring = '1',isAcquiringDate = '$date' where id = '" . $id . "' ";
            } else {
                $sql = "update oa_contract_contract set isAcquiring = '0',isAcquiringDate = '$date' where id = '" . $id . "' ";
            }
            $this->_db->query($sql);

            $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','$title','$ebody','$addresses','',NOW(),'','','1')";
            $this->_db->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }

    /**
     * 合同执行状态
     */
    function exeStatusView_d($arr)
    {
        $state = $arr['state'];
        $isSubApp = $arr['isSubApp'];
        if ($state == '3' || $state == '7') {
            $name = ($state == '7')? '异常关闭' : '关闭';
            return array($name, "5");
        } else if ($state == '4') {
            return array("已完成", "4");
        } else if ($state == '2') {
            return array("执行中", "3");
        } else if ($state == '1') {
            return array("审批中", "2");
        } else if ($isSubApp == '1') {
            return array("成本确认", "1");
        } else {
            return array("未提交", "0");
        }

    }

    /*
	*
	*函数功能：计算两个以YYYY-MM-DD为格式的日期，相差几天
	*
	*/
    function getChaBetweenTwoDate($date1, $date2)
    {

        $Date_List_a1 = explode("-", $date1);
        $Date_List_a2 = explode("-", $date2);

        $d1 = mktime(0, 0, 0, $Date_List_a1[1], $Date_List_a1[2], $Date_List_a1[0]);

        $d2 = mktime(0, 0, 0, $Date_List_a2[1], $Date_List_a2[2], $Date_List_a2[0]);

        $Days = round(($d1 - $d2) / 3600 / 24);

        return $Days;
    }

    /**
     * 根据合同ID 获取 合同费用
     */
    function getContractFeeAll($cid, $isAmount = "1")
    {
        //获取合同数据
        $rows = $this->getContractInfo($cid);
        $conProCost = "0"; //合同项目费用
        $csCost = "0"; //售后费用
        $trCost = "0"; //试用项目费用
        $feeArr = array();


        //合同项目费用sql
        $conPsql = "SELECT
			sum(amount) as amount,replace(ProjectNo,'-','') as projeectNo
			FROM
			cost_summary_list
			WHERE
			`Status` = '完成' and
			replace(ProjectNo,'-','') IN (
			SELECT
			replace(projectCode,'-','') as projectCode
			FROM
			oa_esm_project
			WHERE
			contractType = 'GCXMYD-01'
			AND contractId = '" . $cid . "'
			)
			group by replace(ProjectNo,'-','')
			;";
        //售后sql
        $csql = "select
			if(sum(amount) is null,0,sum(amount)) as amount
			FROM
			cost_summary_list
			WHERE DetailType = 5 and contractId = '" . $cid . "' and `Status` = '完成'";

        //获取试用项目费用
        if (!empty($rows['chanceId'])) {
            $chanceId = $rows['chanceId'];
            $trSql = "SELECT
			sum(amount) as amount,replace(ProjectNo,'-','') as projeectNo
			FROM
			cost_summary_list
			WHERE
			`Status` = '完成' and
			replace(ProjectNo,'-','') IN (
			SELECT
			replace(projectCode,'-','') as projectCode
			FROM
			oa_esm_project
			WHERE
			contractType = 'GCXMYD-04'
			AND contractId  in (select id from oa_trialproject_trialproject where chanceId = '" . $chanceId . "')
			)
			group by replace(ProjectNo,'-','')";
            $trCostArr = $this->_db->getArray($trSql);

            $feeArr['syxm'] = $trCostArr;
            //	   	  echo "<pre>";
            //	   	  print_R($trCostArr);
            if (!empty($trCostArr)) {
                foreach ($trCostArr as $k => $v) {
                    $trCost += $v['amount'];
                }
            }
        }
        //合同项目费用
        $conProCostArr = $this->_db->getArray($conPsql);
        if (!empty($conProCostArr)) {
            foreach ($conProCostArr as $k => $v) {
                $conProCost += $v['amount'];
            }
        }
        $feeArr['htfy'] = $conProCostArr;
        //售后
        $csCostArr = $this->_db->getArray($csql);
        if (!empty($csCostArr)) {
            foreach ($csCostArr as $k => $v) {
                $csCost += $v['amount'];
            }
        }
        $feeArr['sh'] = $csCostArr;
        /**计算分类总金额**/
        $feeAmount = $conProCost + $csCost + $trCost;
        if ($isAmount == '1') {
            return $feeAmount;
        } else {
            return $feeArr;
        }
    }

    /**
     * 处理费用显示
     */
    function handleFeeHTML($arr, $rows)
    {
        /**************************************/
        //试用项目
        $syxmArr = $arr['syxm'];
        $syxmNum = count($syxmArr);
        $strSYXM = "";
        if (empty($syxmArr)) {
            $strSYXM .= <<<EOT
			<tr>
			    <th class="spec">试用项目费用</th>
			    <td>-</td>
			    <td>-</td>
			    <td class="formatMoney">0</td>
			  </tr>
EOT;
        } else {
            $conProCost = "";
            foreach ($syxmArr as $k => $v) {
                $conProCost += $v['amount'];
            }
            $no = $syxmArr[0]['projeectNo'];
            $am = $syxmArr[0]['amount'];
            $strSYXM = "";
            $strSYXM .= <<<EOT
			<tr>
			    <th class="spec" rowspan="$syxmNum">试用项目费用</th>
			    <td>$no</td>
			    <td>$am</td>
			    <td rowspan="$syxmNum" class="formatMoney">$conProCost</td>
			  </tr>
EOT;
            if ($syxmNum > 1) {
                unset($arr['syxm'][0]);
                foreach ($arr['syxm'] as $k => $v) {
                    $strSYXM .= <<<EOT
              <tr>
			    <td>$v[projeectNo]</td>
			    <td>$v[amount]</td>
			  </tr>
EOT;
                }
            }
        }
        /**************************************/
        /**************************************/
        //工程费用
        $htfyArr = $arr['htfy'];
        $htfyNum = count($htfyArr);
        if (empty($htfyArr)) {
            $strHTFY = "";
            $strHTFY .= <<<EOT
			<tr>
			    <th class="spec">工程费用</th>
			    <td>-</td>
			    <td>-</td>
			    <td class="formatMoney">0</td>
			  </tr>
EOT;
        } else {
            $htfyCost = "";
            foreach ($htfyArr as $k => $v) {
                $htfyCost += $v['amount'];
            }
            $noA = $htfyArr[0]['projeectNo'];
            $amA = $htfyArr[0]['amount'];
            $strHTFY = "";
            $strHTFY .= <<<EOT
			<tr>
			    <th class="spec" rowspan="$syxmNum">工程费用</th>
			    <td>$noA</td>
			    <td>$amA</td>
			    <td rowspan="$syxmNum" class="formatMoney">$htfyCost</td>
			  </tr>
EOT;
            if ($htfyNum > 1) {
                unset($arr['htfy'][0]);
                foreach ($arr['htfy'] as $k => $v) {
                    $strHTFY .= <<<EOT
              <tr>
			    <td>$v[projeectNo]</td>
			    <td>$v[amount]</td>
			  </tr>
EOT;
                }
            }
        }
        /**************************************/
        /**************************************/
        //售后
        $shArr = $arr['sh'];
        $shNum = count($shArr);
        if (empty($shArr)) {
            $strSH = "";
            $strSH .= <<<EOT
			<tr>
			    <th class="spec">售后费用</th>
			    <td>-</td>
			    <td>-</td>
			    <td class="formatMoney">0</td>
			  </tr>
EOT;
        } else {
            $shCost = "";
            foreach ($shArr as $k => $v) {
                $shCost += $v['amount'];
            }
            $noA = $rows['contractCode'];
            $amA = $shArr[0]['amount'];
            $strSH = "";
            $strSH .= <<<EOT
			<tr>
			    <th class="spec" rowspan="$syxmNum">售后费用</th>
			    <td>$noA</td>
			    <td>$amA</td>
			    <td rowspan="$syxmNum" class="formatMoney">$shCost</td>
			  </tr>
EOT;
            if ($shNum > 1) {
                unset($arr['sh'][0]);
                foreach ($arr['sh'] as $k => $v) {
                    $strSH .= <<<EOT
              <tr>
			    <td>$rows[contractCode]</td>
			    <td>$v[amount]</td>
			  </tr>
EOT;
                }
            }
        }
        /**************************************/
        //总计
        $sj = $shCost + $htfyCost + $conProCost;
        $strZJ = "";
        $strZJ .= <<<EOT
              <tr>
			    <tr>
			    <td></td>
			    <td></td>
			    <td>合计</td>
			    <td class="formatMoney">$sj</td>
			  </tr>
EOT;
        $str = $strSYXM . $strHTFY . $strSH . $strZJ;
        return $str;
    }

    /**
     * 回款列表处理
     */
    function financialTdayHTML($rows)
    {
        $str = "";
        if (!empty($rows)) {
            foreach ($rows as $k => $v) {
                $id = $v['id'];
                //根据付款条件判断并搜索计算T日
                $payDTtemp = $this->handlePayDT($v['contractId'], $v['paymenttermId'], $v['dayNum'], $v['schedulePer']);
                if (empty($v['Tday'])) {
                    $Tday = $payDTtemp;
                } else {
                    if ($v['Tday'] != '0000-00-00')
                        $Tday = $v['Tday'];
                }
                if ($v['payDT'] == '0000-00-00') {
                    $payDT = "-";
                } else {
                    $payDT = $v['payDT'];
                }
                $incomMoney = "";
                if ($v['isCom'] == '0') {
                    $isCom = "未完成";
                } else if ($v['isCom'] == '1') {
                    $isCom = "已完成";
                } else {
                    $isCom = "-";
                }
                if (empty($v['comDate']) || $v['comDate'] != '0000-00-00')
                    $comDate = $v['comDate'];

                //标识
                if ($v['Tday'] == '' || $v['Tday'] == '0000-00-00') {
                    $isFlag = "-";
                } else {
                    $isFlag = '<img src="images/icon/ok3.png">';
                }
                if ($v['TdayPush'] == '0') {
                    $htmlButton = ' <td><input type="button" class="txt_btn_a" value="确认" onclick = "confirmTday(' . $id . ',' . $k . ')"></td>';
                    $htmlInput = ' <td><input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '"></td>';
                } else {
                    $htmlButton = ' <td><input type="button" class="txt_btn_a" value="变更" onclick = "confirmTday(' . $id . ',' . $k . ')"></td>';
                    $htmlInput = ' <td><input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '"><span class="blue" onclick = "changeHistory(' . $id . ',' . $k . ');"> ' . '<img title="查看变更历史" src="images/icon/view.gif"></span>' . '<input type="hidden" id="tdayOld' . $k . '" value="' . $Tday . '"></td>';
                }
                //add by chenrf
                if ($v['isDel'] == '1') {
                    $isCom = "已删除";
                    $htmlButton = '<td><input type="button" class="txt_btn_a" value="确认" disabled="disabled"></td>';
                    $htmlInput = " <td><span style='text-decoration:line-through;color:red;'>{$Tday}</span></td>";
                }
                $str .= <<<EOT
			<tr>
			    <th class="spec">$isFlag</th>
			    <th class="spec">$v[contractCode]</th>
			    <td>$v[paymentterm]</td>
			    <td>$v[paymentPer]%</td>
			    <td  class="formatMoney">$v[money]</td>
			    <td>$v[remark]</td>
			    $htmlInput
			    <td class="formatMoney" style="cursor:pointer;color:blue" onclick="incomView($id,2)">$v[invoiceMoney]</td>
			    <td class="formatMoney" style="cursor:pointer;color:blue" onclick="incomView($id)">$v[incomMoney]</td>
			    <td>$v[deductMoney]</td>
			    <td>$isCom</td>
			    <td>$comDate</td>
			    <td>$v[periodName]</td>
			   $htmlButton
			  </tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 工程项目完调用并判断是否需要更新T日
     * @param $contractId 合同id
     */
    function resetTdayByProject($contractId){

        $tArr = $this->_db->getArray("SELECT * from oa_contract_receiptplan where contractId=".$contractId);
        if(is_array($tArr) && !empty($tArr)){
            $payconfigDao = new model_contract_config_payconfig();
            foreach($tArr as $val){
                $payconfigArr = $payconfigDao->get_d($val['paymenttermId']);
                if ($payconfigArr['dateCode'] == 'endDate') {
//                    $sql = "select date_add(if(actEndDate is null,planEndDate,actEndDate), interval " . $val['dayNum'] . " day) as payDT from oa_esm_project where contractId= '" . $contractId . "' group by contractId";
                    $sql = "SELECT if(t.actEndDate is null,t.planEndDate,t.actEndDate) AS payDT FROM (".
                        "SELECT ".
                            " max(date_add(actEndDate,INTERVAL {$val['dayNum']} DAY)) AS actEndDate,".
                            " max(date_add(planEndDate,INTERVAL {$val['dayNum']} DAY)) AS planEndDate".
                        " FROM oa_esm_project WHERE contractId = '{$contractId}'".
                    ")t;";// 如有多个项目,取最大的那个日期
                    $arr = $this->_db->getArray($sql);
                    if($arr[0]['payDT'] != $val['Tday'] && $arr[0]['payDT'] != "" && $arr[0]['payDT'] != NULL){
//                        $updateSql = "update oa_contract_receiptplan set Tday=null,TdayPush=0 where id = ".$val['id'];
                        $updateSql = "update oa_contract_receiptplan set Tday='{$arr[0]['payDT']}' where id = ".$val['id']." and Tday is not null;";// 如新旧T日确认日期对比，不一致，取新T日直接写到条款T日里，不改变确认状态。PMS 155
                        $updateResult = $this->_db->query($updateSql);
                        $res = ($updateResult)? "成功" : "失败";
                        $this->addOperateLog("项目完成更新T日", $contractId, "项目已完成,由于（原T日: {$val['Tday']}) 与（项目完成日期加缓冲日期: {$arr[0]['payDT']})不一致触发T日更新的方法。", $res, $updateSql);
                    }
                }
            }
        }
    }

    /**
     * 添加用户操作记录
     *
     * @param $type
     * @param $obj
     * @param $event
     * @param $res
     * @param string $exp
     */
    function addOperateLog($type, $obj, $event, $res, $exp = "")
    {
        try {
            $handler = isset($_SESSION["USER_ID"]) ? $_SESSION["USER_ID"] : "";
            $ip = $_SERVER["REMOTE_ADDR"];
            $url = urlencode($_SERVER["REQUEST_URI"]);
            $exp = addslashes($exp);
            $sql = " insert into user_operate_log (Handler,Obj,Event,DT,Url,IP,Type,Result,Exp) values ('$handler','$obj','$event',now(),'$url','$ip','$type','$res','$exp')";
            $this->_db->query($sql);
        } catch (Exception $e) {
            writeToLog("用户操作记录录入失败！", "log.txt");
        }
    }

    //update T
    function updateTday_d($id, $tday, $changeTips)
    {
        try {
            $this->start_d();
            $DT = date("Y-m-d H:i:s");
            $sql = "update oa_contract_receiptplan set changeRemark = '" . $changeTips . "',Tday = '" . $tday . "',updateTime='" . $DT . "',updateName='" . $_SESSION['USERNAME'] . "',updateId='" . $_SESSION['USER_ID'] . "',TdayPush = 1 where id = '" . $id . "'";
            $this->query($sql);
            $newArr['financialId'] = $_POST['id'];
            $newArr['financialType'] = 'tday';
            $newArr['financialNewV'] = $_POST['tday'];
            $newArr['financialOldV'] = $_POST['tdayOld'];
            $newArr['changeRemark'] = $changeTips;
            $newCheckArr = $this->addCreateInfo($newArr);

            $this->newCreate($newCheckArr);
            $dao = new model_contract_contract_receiptplan();
            $receArr = $dao->get_d($id);
            if ($receArr['comDate'] != '' && $receArr['comDate'] != '0000-00-00') {
                $receArr = $dao->dealEdit_d($id, 0, $receArr['comDate']);
            }
            //发送邮件
            $contractArr = $this->find(array('id' => $receArr['contractId']), null, 'prinvipalId,contractCode');
            $this->mailDeal_d('confirmReceiptPlan', $contractArr['prinvipalId'], array('contractCode' => $contractArr['contractCode'], 'paymentterm' => $receArr['paymentterm'], 'name' => $_SESSION['USERNAME']));
            $this->commit_d();
            return 1;
        } catch (exception $e) {
            $this->rollBack();
            return $e;
        }
    }

    //update T - 批量
    function updateTdayBatch_d($checkArr)
    {
        try {
            $this->start_d();
            if (is_array($checkArr)) {
                $dao = new model_contract_contract_receiptplan();
                foreach ($checkArr as $v) {
                    $tday = $v['tday'];
                    $id = $v['id'];
                    $DT = date("Y-m-d H:i:s");
                    $sql = "update oa_contract_receiptplan set Tday = '" . $tday . "',updateTime='" . $DT . "',updateName='" . $_SESSION['USERNAME'] . "',updateId='" . $_SESSION['USER_ID'] . "',TdayPush = 1 where id = '" . $id . "'";
                    $this->query($sql);
                    $newArr['financialId'] = $id;
                    $newArr['financialType'] = 'tday';
                    $newArr['financialNewV'] = $tday;
                    $newArr['financialOldV'] = "";
                    $newCheckArr = $this->addCreateInfo($newArr);
                    $this->newCreate($newCheckArr);
                    $receArr = $dao->get_d($id);
                    if ($receArr['comDate'] != '' && $receArr['comDate'] != '0000-00-00') {
                        $receArr = $dao->dealEdit_d($id, 0, $receArr['comDate']);
                    }
                    //发送邮件
                    $contractArr = $this->find(array('id' => $receArr['contractId']), null, 'prinvipalId,contractCode');
                    $this->mailDeal_d('confirmReceiptPlan', $contractArr['prinvipalId'], array('contractCode' => $contractArr['contractCode'], 'paymentterm' => $receArr['paymentterm'], 'name' => $_SESSION['USERNAME']));
                }
            }

            $this->commit_d();
            return 1;
        } catch (exception $e) {
            $this->rollBack();
            return 0;
        }
    }

    //回款T日变更记录
    function newCreate($row)
    {
        if (!is_array($row))
            return FALSE;
        foreach ($row as $key => $value) {
            $cols [] = $key;
            $vals [] = "'" . $this->__val_escape($value) . "'";
        }
        $col = join(',', $cols);
        $val = join(',', $vals);

        $sql = "INSERT INTO oa_contract_financialtday_changehistory ({$col}) VALUES ({$val})";
        if (FALSE != $this->_db->query($sql)) { // 获取当前新增的ID
            if ($newinserid = $this->_db->insert_id()) {
                return $newinserid;
            } else {
                //				return array_pop ( $this->find ( $row, "{$this->pk} DESC", $this->pk ) );
            }
        }
        return FALSE;
    }

    function getChanceHistory_d($id)
    {
        $financialsql = "select * from oa_contract_financialtday_changehistory where financialId='" . $id . "' and financialType='tday'";
        $financialInfo = $this->_db->getArray($financialsql);
        $boostStr = "";
        $boostList = "";
        foreach ($financialInfo as $k => $v) {
            $boostStr .= "-->" . "<span style='color:blue' title = '推进人： " . $v['createName'] . "
推进时间 ： " . $v['createTime'] . "
			        					'>" . $v['financialType'] . "<span>";
            $boostList .= "<tr><td style='text-align: left'>【" . $v['createName'] . "】于【" . $v['createTime'] . "】将财务T-日从 【 " . $v['financialOldV'] . " 】更新至 【 " . $v['financialNewV'] . " 】<br>【变更原因】 ： " . $v['changeRemark'] . "</td><tr>";
        }
        $str = "";
        if ($financialInfo) {
            $str .= <<<EOT
               $boostList
EOT;
        } else {
            $str .= <<<EOT
				<tr align="center">
					<td>
						<b>无变更信息</b>
					</td>

			</tr>
EOT;
        }
        return $str;
    }

    /**
     * 根据付款条件ID，合同id 处理并计算T日
     */
    function handlePayDT($contracrId, $paymenttermId, $days, $schedulePer = 0)
    {
        $payconfigDao = new model_contract_config_payconfig();
        $payconfigArr = $payconfigDao->get_d($paymenttermId);
        //		if(!empty($days)){
        //			$days = $payconfigArr['days'];
        //		}
        if ($payconfigArr['isNeedDate'] == '1') {
            if ($payconfigArr['dateCode'] == 'beginDate') {
                $sql = "select  date_add(if(actBeginDate is null,planBeginDate,actBeginDate), interval " . $days . " day) as payDT from oa_esm_project where contractId= '" . $contracrId . "'
					group by contractId";
                $arr = $this->_db->getArray($sql);
            } else if ($payconfigArr['dateCode'] == 'endDate') {
//                $sql = "select date_add(if(actEndDate is null,planEndDate,actEndDate), interval " . $days . " day) as payDT from oa_esm_project where contractId= '" . $contracrId . "'
//					group by contractId";
                $sql = "SELECT if(t.actEndDate is null,t.planEndDate,t.actEndDate) AS payDT FROM (".
                        "SELECT ".
                            " max(date_add(actEndDate,INTERVAL {$days} DAY)) AS actEndDate,".
                            " max(date_add(planEndDate,INTERVAL {$days} DAY)) AS planEndDate".
                        " FROM oa_esm_project WHERE contractId = '{$contracrId}'".
                    ")t;";
                $arr = $this->_db->getArray($sql);
            }else if($payconfigArr['dateCode'] == 'c_endDate'){
				$sql =  "select date_add(endDate, interval ".$days." day) as payDT from oa_contract_contract where id= '".$contracrId."' ";
				$arr = $this->_db->getArray($sql);
            } else if ($payconfigArr['dateCode'] == 'firstOutstockDate') { // 第一次出库日期
                $sql = "SELECT date_add(min(auditDate), interval ".$days." day) as payDT FROM oa_stock_outstock WHERE contractId = '".$contracrId."' AND docStatus = 'YSH'";
                $arr = $this->_db->getArray($sql);
            } else if ($payconfigArr['dateCode'] == 'esmPercentage' || $payconfigArr['dateCode'] == 'shipPercentage' || $payconfigArr['dateCode'] == 'schePercentage') { // 进度百分比
                $connodeDao = new model_contract_connode_connode();
                switch ($payconfigArr['dateCode']){
                    case 'esmPercentage':
                        $dateType = 'esm';
                        break;
                    case 'shipPercentage':
                        $dateType = 'pro';
                        break;
                    default:
                        $dateType = 'con';
                        break;
                }
                $dateStr = $connodeDao->getTDay_d($contracrId, $schedulePer, $dateType);
                $arr[0] = array();
                $days = $days;
                if($dateStr != ''){
                    $dateTime = strtotime($dateStr);
                    $dateTime += ($days*60*60*24);
                    $dateStr = date("Y-m-d",$dateTime);
                }
                $arr[0]['payDT'] = $dateStr;
			} else {
                $sql = "select  date_add(" . $payconfigArr['dateCode'] . ", interval " . $days . " day) as payDT " .
                    "from oa_contract_contract where id = '" . $contracrId . "'";
                $arr = $this->_db->getArray($sql);
            }
            if (!empty($arr) && $arr[0]['payDT']) {
                return $arr[0]['payDT'];
            } else {
                return "";
            }
        } else {
            return "";
        }

    }

    /**
     * 成本确认 产品线显示html
     */
    function costinfoView($costLimit, $cid, $issale = 0)
    {
        $costLimitArr = explode(",", $costLimit);
        //        $deptDao = new model_deptuser_dept_dept();
        $datadictDao = new model_system_datadict_datadict();
        $costDao = new model_contract_contract_cost();
        $conArr = $this->get_d($cid);
        $costAppRemark = "";
        $str = "";
        foreach ($costLimitArr as $k => $v) {
            if ($v != ";;") {
                // 查询该产品线是否存在非销售产品，不存在，不显示
                $sql = "SELECT * FROM oa_contract_product WHERE contractId = " . $cid . " AND newProLineCode = '" . $v . "' AND proTypeId <> 11 AND isDel = 0";
                $rs = $this->_db->getArray($sql);
                if (empty($rs)) {
                    continue;
                }
                //               $costLimitNameArr = $deptDao->getDeptById($v);
                //               $costLimitName = $costLimitNameArr['DEPT_NAME'];
                $costLimitName = $datadictDao->getDataNameByCode($v);
                $costEstimatesArr = $costDao->findMoneyByLine($cid, $v, $issale);
                if (!empty($costEstimatesArr['confirmMoney'])) {
                    $costEstimates = $costEstimatesArr['confirmMoney'];
                    $isExa = isset($costEstimatesArr['ExaState']) ? $costEstimatesArr['ExaState'] : 0;
                } else {
                    $costEstimates = "";
                    $isExa = "0";
                }
                if (($isExa == "0" || $isExa == "2") && strstr($conArr['newProLineStr'], $v)) {
                    $str .= <<<EOT
                    <tr>
                    <td class="form_text_left"><span style="color:blue">$costLimitName 成本概算</span> </td>
                    <td class="form_text_right" colspan="3">
                      <input type="text" id="costEstimates$k" class="txt formatMoney" name="contract[costEstimates][$v]" value="$costEstimates"/>
                    </td>
                    </tr>
EOT;
                }
                if (is_array($costEstimatesArr)) {
                    $costAppRemark .= $costEstimatesArr['costAppRemark'] . "<br/>";
                }
            }
        }
        $strArr['str'] = $str;
        $strArr['remark'] = $costAppRemark;

        return $strArr;
    }

    /**
     * 处理并插入成本确认明细
     */
    function handleCostInfo($arr, $type)
    {
        //      $deptDao = new model_deptuser_dept_dept();
        $datadictDao = new model_system_datadict_datadict();
        $costDao = new model_contract_contract_cost();
        $conArr = $this->get_d($arr['contractId']);
        if (!empty($arr['costEstimates'])) {
            foreach ($arr['costEstimates'] as $k => $v) {
                //            $costLimitNameArr = $deptDao->getDeptById($k);
                //            $costLimitName = $costLimitNameArr['DEPT_NAME'];
                $costLimitName = $datadictDao->getDataNameByCode($k);
                $temp['contractId'] = $arr['contractId'];
                $temp['productLine'] = $k;
                $temp['xfProductLine'] = $k . 'f'; // 用来区分同一产线不同产品类型的产品冗余，即某某产线销售类(x)/服务类(f)产品
                $temp['productLineName'] = $costLimitName;
                $temp['confirmName'] = $arr['engConfirmName'];
                $temp['confirmId'] = $arr['engConfirmId'];
                $temp['confirmDate'] = date("Y-m-d H:i:s");
                $temp['state'] = "1";
                $temp['ExaState'] = "0"; // 由于去掉成本审核，这里将0改成1 By weijb 2015.10.17
                $temp['confirmMoney'] = $v;
                $temp['confirmMoneyTax'] = $v; //服务类产品，不计算税后金额

                //判断数据是否存在
                $isRel = $costDao->findisEel($arr['contractId'], $k, "0");
                if ($isRel == "0") {
                    $nid = $costDao->add_d($temp);
                } else {
                    $temp['id'] = $isRel;
                    $nid = $isRel;
                    $costDao->edit_d($temp);
                }
                $handleDao = new model_contract_contract_handle();
                //如果是变更，更新原合同确认状态
                if ($type != "add") {
                    $isRel = $costDao->findisEel($type, $k, "0");
                    if ($isRel == "0") {
                        $temp['contractId'] = $type;
                        unset($temp['id']);
                        $nid = $costDao->add_d($temp);
                    }
                    $condition = array("productLine" => $k, "contractId" => $type);
                    $upArr = array("state" => "1", "ExaState" => "1");
                    $costDao->update($condition, $upArr);
                    //变更确认记录
                    $handleDao->handleAdd_d(array(
                        "cid" => $type,
                        "stepName" => "服务成本确认",
                        "isChange" => 2,
                        "stepInfo" => $k,
                    ));

                } else {
                    $handleDao->handleAdd_d(array(
                        "cid" => $arr['contractId'],
                        "stepName" => "服务成本确认",
                        "isChange" => 0,
                        "stepInfo" => $k,
                    ));
                }
//                //根据productLine 发送通知邮件
//                $tomail = $this->costConUserIdBycid($temp['productLine']);
//                $content = array(
//                    "contractCode" => $conArr['contractCode'],
//                    "contractName" => $conArr['contractName'],
//                    "customerName" => $conArr['customerName']
//                );
//                $this->mailDeal_d("contractCost_Confirm", $tomail, $content);
            }
            if (!empty($arr['costRemark'])) {
                //处理产品线确认备注
                $costDao->handleCostRemark($arr['costRemark'], $nid);
            }
        }

    }

    /**
     * 处理销售类产品线 成本确认 明细记录
     */
    function hadleSaleLineCostInfo($conCostArr, $ischange = "add", $audti = false)
    {
        //根据合同id获取销售类产品线 名称
        $equDao = new model_contract_contract_equ();
        $productDao = new model_contract_contract_product();

        if ($ischange == "add") {
            $conArr = $equDao->getDetail_d($conCostArr['id']);
        } else {
            $conArr = $equDao->getDetailTemp_d($conCostArr['id']);
        }
        //默认调整根据合同内所有销售类产生成产线审核记录， By LiuB 2014-11-28
        //        if (!empty ($conArr)) {
        $noProMoney = "";
        $noProMoneyTax = "";
        foreach ($conArr as $k => $v) {
            if (!empty($v['conProductId'])) {
                $goodId = $v['conProductId'];
            } else if (!empty($v['proId'])) {
                $goodId = $v['proId'];
            }
            if (!empty($goodId)) {
                //产品线
                $sqlf = "select i.newProLineName,i.newProLineCode from oa_contract_product i where i.id = '" . $goodId . "'";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                $costInfoArrEqu[] = array(
                    "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                    "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                    "confirmMoney" => $v['money'],
                    "confirmMoneyTax" => $v['moneyTax']
                );
            } else {
                $noProMoney += $v['money'];
                $noProMoneyTax += $v['moneyTax'];
            }
        }
        //        }else{
        if ($ischange == "add") {
            $proArr = $productDao->getDetail_d($conCostArr['id']);
        } else {
            $proArr = $productDao->getDetailTemp_d($conCostArr['id']);
        }

        foreach ($proArr as $k => $v) {
            //判断是否属于销售类产品
            $goodsTypeIds = $v['conProductId'];
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //第一次查找，过滤出本身已经是最高类别的类型
                $goodsTypeA = $this->_db->getArray($sqlA);
                $goodsTypeStr = "";
                $goodsTypeStrTemp = "";
                foreach ($goodsTypeA as $TypeA) {
                    if ($TypeA['parentId'] == "-1") {
                        $goodsTypeStr = $TypeA['id'];
                    } else {
                        $goodsTypeStrTemp = $TypeA['id'];
                    }
                }
            }
            if (!empty ($goodsTypeStrTemp)) {
                //第二次查找，找到剩余产品的最高类别
                $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $TypeBval) {
                    $goodsTypeStr = $TypeBval['id'];
                }
            }
            if ($goodsTypeStr == '17' || $goodsTypeStr == '18') {
                continue;
            }
            $goodId = $v['id'];
            if (!empty($goodId)) {
                //产品线
                $sqlf = "select i.newProLineName,i.newProLineCode from oa_contract_product i where i.id = '" . $goodId . "'";
                $exeDeptNameArr = $this->_db->getArray($sqlf);
                $costInfoArr[] = array(
                    "productLine" => $exeDeptNameArr[0]['newProLineCode'],
                    "productLineName" => $exeDeptNameArr[0]['newProLineName'],
                    "confirmMoney" => "0"
                );
            } else {
                $noProMoney += "0";
            }
        }
        //        }
        //整合相同产品线的金额数组
        //按产品计算产线成本数组，防止 无物料产品导致确认产线审核记录
        $ResultArr = array();
        foreach ($costInfoArr as $value) {
            $line = $value['productLine'];
            $lineName = $value['productLineName'];
            $sum = $value['confirmMoney'];
            if (array_key_exists($line, $ResultArr)) {
                $ResultArr[$line] += $sum;
            } else {
                $ResultArr[$line] = $sum;
            }
        }
        //按物料计算产线成本数组
        $ResultArrEqu = array();
        $ResultArrEquTax = array();
        foreach ($costInfoArrEqu as $value) {
            $line = $value['productLine'];
            $lineName = $value['productLineName'];
            $sum = $value['confirmMoney'];
            $sumTax = $value['confirmMoneyTax'];
            if (array_key_exists($line, $ResultArr)) {
                $ResultArrEqu[$line] += $sum;
                $ResultArrEquTax[$line] += $sumTax;
            } else {
                $ResultArrEqu[$line] = $sum;
                $ResultArrEquTax[$line] = $sumTax;
            }
        }

        //合并数组，解决 缺少产线及缺少成本问题
        foreach ($ResultArr as $key => $value) {
            $ResultArr[$key] = $value;
        }
        foreach ($ResultArrEqu as $key => $value) {
            $ResultArr[$key] = $value;
        }

        if (!empty($ResultArr)) {
            //把没产品的物料成本挂到第一个产品线下，（暂时处理）
            if (!empty($noProMoney)) {
                $keyArr = array_keys($ResultArr);
                $k = $keyArr[0];
                $ResultArr[$k] += $noProMoney;
                $ResultArrEquTax[$k] += $noProMoneyTax;
            }
            //            $deptDao = new model_deptuser_dept_dept();
            $datadictDao = new model_system_datadict_datadict();
            $costDao = new model_contract_contract_cost();
            $lineStr = "";
            //如果交付物料确认只是保存，则成本确认状态为0，提交则为3
            $state = $audti == true ? '3' : '0';
            foreach ($ResultArr as $k => $v) {
                //         		$costLimitNameArr = $deptDao->getDeptById($k);
                //                $costLimitName = $costLimitNameArr['DEPT_NAME'];
                $lineStr .= $k . ",";
                $costLimitName = $datadictDao->getDataNameByCode($k);
                $temp['contractId'] = $conCostArr['id'];
                $temp['productLine'] = $k;
                $temp['xfProductLine'] = $k . 'x'; // 用来区分同一产线不同产品类型的产品冗余，即某某产线销售类(x)/服务类(f)产品
                $temp['productLineName'] = $costLimitName;
                $temp['confirmName'] = $conCostArr['saleConfirmName'];
                $temp['confirmId'] = $conCostArr['saleConfirmId'];
                $temp['confirmDate'] = date("Y-m-d H:i:s");
                $temp['state'] = $state; // 0 表示交付保存，1 表示已确认， 2 打回  3 表示销售待确认
                $temp['ExaState'] = "0";
                $temp['confirmMoney'] = $v;
//                 $temp['confirmMoneyTax'] = bcmul($v, '1.17', 2);// 按17%税率，计算税后金额
                $temp['confirmMoneyTax'] = $ResultArrEquTax[$k];
                $temp['issale'] = "1";
                $temp['costRemark'] = $conCostArr['costRemark'];
                //判断数据是否存在
                $isRel = $costDao->findisEel($conCostArr['id'], $k, 1);
                if ($isRel == "0") {
                    unset($temp['id']);
                    $costDao->add_d($temp);
                } else {
                    $temp['id'] = $isRel;
                    $costDao->edit_d($temp);
//                     if($conCostArr['oldId'] && $audti){//交付提交物料确认才更新原合同成本信息
//                     	$isRelOld = $costDao->findisEel($conCostArr['oldId'],$k,1);
//                         unset($temp['id']);
//                         $temps['contractId'] = $conCostArr['oldId'];
//                         $temps['productLine'] = $k;
//                         $temps['productLineName'] = $costLimitName;
//                         $temps['confirmName'] = $conCostArr['saleConfirmName'];
//                         $temps['confirmId'] = $conCostArr['saleConfirmId'];
//                         $temps['confirmDate'] = date ( "Y-m-d H:i:s" );
//                         $temps['state'] = "3";// 1 表示已确认， 2 打回  3 表示销售待确认
//                         $temps['ExaState'] = "0";
//                         $temps['confirmMoney'] = $v;
//                         $temps['confirmMoneyTax'] = bcmul($v, '1.17', 2);// 按17%税率，计算税后金额
//                         $temps['issale'] = "1";
//                         $temps['id'] = $isRelOld;
//                         $costDao->edit_d($temps);
//                     }
                }
            }
        }
        return $lineStr;
    }

    /**
     * 动态获取当前登录人的成本确认状态
     */
    function getCostState($cid, $costLimit)
    {
        $obj = $this->get_d($cid);
        $costType = $obj['isSubAppChange'];
        if ($costType == '1') {
            $mid = $this->findChangeId($cid);
            $nid = $mid;
            $costLimitArr = explode(",", $costLimit);
            $tempArr = array();
            foreach ($costLimitArr as $k => $v) {
                $fsql = "select state from oa_contract_cost where contractId = '" . $nid . "' and productLine = '" . $v . "'";
                $tarr = $this->_db->getArray($fsql);
                if (!empty($tarr)) {
                    $tempArr[] = $tarr[0]['state'];
                }
            }
            foreach ($costLimitArr as $k => $v) {
                $fsql = "select state from oa_contract_cost where contractId = '" . $cid . "' and productLine = '" . $v . "'";
                $tarr = $this->_db->getArray($fsql);
                if (!empty($tarr)) {
                    $OldtempArr[] = $tarr[0]['state'];
                }
            }
            if (!empty($tempArr) && !in_array("0", $tempArr) && !in_array("2", $tempArr)) {
                return "1";
            } else {
                if (!empty($OldtempArr) && !in_array("0", $OldtempArr) && !in_array("2", $OldtempArr))
                    return "1";
                else
                    return "0";
            }
        } else {
            $costLimitArr = explode(",", $costLimit);
            $tempArr = array();
            foreach ($costLimitArr as $k => $v) {
                $fsql = "select state from oa_contract_cost where contractId = '" . $cid . "' and productLine = '" . $v . "'";
                $tarr = $this->_db->getArray($fsql);
                if (!empty($tarr)) {
                    $tempArr[] = $tarr[0]['state'];
                }
            }
            if (!empty($tempArr) && !in_array("0", $tempArr) && !in_array("2", $tempArr) && $obj['engConfirm'] != '1') {
                return "1";
            } else {
                return "0";
            }
        }
    }

    /**
     * 成本确认领导审核
     */
    function handleCostApp($act, $arr, $type, $rows)
    {
        $costDao = new model_contract_contract_cost();
        $userId = $_SESSION ['USER_ID'];
        $userName = $_SESSION ['USERNAME'];
        $costTime = date("Y-m-d H:i:s");
        $confirmMoney = $arr['confirmMoney'];
        $costAppRemark = $arr['costAppRemark'];
        $cid = $arr['contractId'];
        $line = $arr['productLine'];
        $id = $arr['id'];
        //判断是否已全部确认完成
        $productLineStr = rtrim($rows['newProLineStr'], ',');
        $confirmFlag = $costDao->confirmCostFlag($productLineStr, $cid, $id);
        //更新明细审核状态
        if ($act == "back") {
            $costDao->ajaxBack_d($id, $type, $costAppRemark);
        } else {
            if ($confirmFlag == '1') {
                if ($arr['isdeff'] == '2') {
                    $updateSql = "update oa_contract_cost" .
                        " set ExaState = '1',costAppName='" . $userName . "',costAppId='" . $userId . "',costAppDate='" . $costTime . "',costAppRemark='" . $costAppRemark . "' " .
                        "where contractId='" . $cid . "' and productLine='" . $line . "' ";
                } else {
                    $updateSql = "update oa_contract_cost" .
                        " set ExaState = '1',confirmMoney='" . $confirmMoney . "',costAppName='" . $userName . "',costAppId='" . $userId . "',costAppDate='" . $costTime . "',costAppRemark='" . $costAppRemark . "' " .
                        "where id = $id ";
                }
            } else {
                //相关状态更新移动至 审批流文件内，处理 审批部提交导致更新业务状态问题( 只保留其他更新数据，以防差错用)
                if ($arr['isdeff'] == '2') {
                    $updateSql = "update oa_contract_cost" .
                        " set costAppName='" . $userName . "',costAppId='" . $userId . "',costAppDate='" . $costTime . "',costAppRemark='" . $costAppRemark . "' " .
                        "where contractId='" . $cid . "' and productLine='" . $line . "' ";
                } else {
                    $updateSql = "update oa_contract_cost" .
                        " set confirmMoney='" . $confirmMoney . "',costAppName='" . $userName . "',costAppId='" . $userId . "',costAppDate='" . $costTime . "',costAppRemark='" . $costAppRemark . "' " .
                        "where id = $id ";
                }
            }
            $this->query($updateSql);
            $handleDao = new model_contract_contract_handle();
            if ($type == 'add') {
                $handleDao->handleAdd_d(array(
                    "cid" => $cid,
                    "stepName" => "执行部门审核",
                    "isChange" => 0,
                    "stepInfo" => $line,
                ));
            } else {
                $handleDao->handleAdd_d(array(
                    "cid" => $type,
                    "stepName" => "执行部门审核",
                    "isChange" => 2,
                    "stepInfo" => $line,
                ));
            }
            //       	     $this->query($updateSql);
        }

    }

    /**
     * 服务类领导审核完成后更新成本确认标志 engConfirm
     */
    function endTheEngTig($cid)
    {
        $sql = "update oa_contract_contract set engConfirm = '1' where id = '" . $cid . "'";
        $this->query($sql);
    }

    /**
     * 根据合同ID 获取最近一次变更原因
     */
    function getChangeReasonById($cid)
    {
        $sql = "select changeReason from oa_contract_changlog where objId = '" . $cid . "' order by id desc";
        $arr = $this->_db->getArray($sql);
        if (!empty($arr)) {
            return $arr[0]['changeReason'];
        }
        return "";
    }

    /**
     * 根据合同id  获取 关联项目相关费用
     */
    function getproBudgetByids($id)
    {
        $sql = "
				select
					c.contractId,sum(c.budgetAll) as budgetAll,c.feeOther,
					sum(c.budgetOutsourcing) as budgetOutsourcing,
					sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + if(op.money is null ,0,op.money)) as feeFieldCount,
					sum(c.feeOutsourcing) as feeOutsourcing,
					sum(if(l.feeFieldCount is null ,0,l.feeFieldCount) + if(op.money is null ,0,op.money) + c.feeOther + c.feeOutsourcing) as feeAll,
					round(sum(if(c.status = 'GCXMZT03',100,c.projectProcess)*c.workRate/100),2) as projectProcess
				from
					oa_esm_project c LEFT JOIN (
						SELECT
							sum(l.Amount) AS feeFieldCount, l.projectNo
						FROM
							cost_summary_list l WHERE  l.isproject = 1 AND l. STATUS <> '打回' GROUP BY l.projectNo
					) l ON replace(l.projectNo,'-','') =  replace(c.projectCode,'-','')
					left join (
						select
							p.expand3,sum(p.money) as money
						from
							oa_finance_payablesapply_detail p
						left join oa_finance_payablesapply pa on p.payapplyId = pa.id
						where
							p.expand1 = '工程项目' and pa.ExaStatus != '打回' and pa. status not in ('FKSQD-04', 'FKSQD-05')
							and p.objType = 'YFRK-02'
						group by
							p.expand3
					) op on c.id = op.expand3
					where c.contractType = 'GCXMYD-01' and contractId in (" . $id . ")
				GROUP BY c.contractId
			";

        $arr = $this->_db->getArray($sql);
        return $arr;
    }

    /**
     * 权限设置
     * 权限返回结果如下:
     * 如果包含权限，返回true
     * 如果无权限,返回false
     */
    function initLimit($customSql = null)
    {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        //权限配置数组
        $limitConfigArr = array(
            'areaLimit' => 'c.areaCode',
            'deptLimit' => 'c.prinvipalDeptId',
            'customerTypeLimit' => 'c.customerType',
            'contractTypeLimit' => 'c.contractType',
        );

        //权限数组
        $limitArr = array();
//         $limitArr['appNameStr'] = '1';
        //权限系统
        if (isset ($sysLimit['产品线']) && !empty ($sysLimit['产品线']))
            $limitArr['productLine'] = $sysLimit['产品线'];

        if (isset ($sysLimit['成本确认']) && !empty ($sysLimit['成本确认'])) {
            if (!empty($limitArr['productLine'])) {
                $limitArr['productLine'] .= "," . $sysLimit['成本确认'];
            } else {
                $limitArr['productLine'] = $sysLimit['成本确认'];
            }
        }
        if (isset ($sysLimit['成本确认审核']) && !empty ($sysLimit['成本确认审核'])) {
            if (!empty($limitArr['productLine'])) {
                $limitArr['productLine'] .= "," . $sysLimit['成本确认审核'];
            } else {
                $limitArr['productLine'] = $sysLimit['成本确认审核'];
            }
        }
        if (isset ($sysLimit['产品权限']) && !empty ($sysLimit['产品权限']))
            $limitArr['goodsLimit'] = $sysLimit['产品权限'];
        if (isset ($sysLimit['销售区域']) && !empty ($sysLimit['销售区域']))
            $limitArr['areaLimit'] = $sysLimit['销售区域'];
        if (isset ($sysLimit['部门权限']) && !empty ($sysLimit['部门权限']))
            $limitArr['deptLimit'] = $sysLimit['部门权限'];
        if (isset ($sysLimit['客户类型']) && !empty ($sysLimit['客户类型']))
            $limitArr['customerTypeLimit'] = $sysLimit['客户类型'];
        if (isset ($sysLimit['合同类型']) && !empty ($sysLimit['合同类型']))
            $limitArr['contractTypeLimit'] = $sysLimit['合同类型'];
        if (isset ($sysLimit['公司权限']) && !empty ($sysLimit['公司权限']))
            $limitArr['companyLimit'] = $sysLimit['公司权限'];
        if (isset ($sysLimit['执行区域']) && !empty ($sysLimit['执行区域']))
            $limitArr['exeDeptLimit'] = $sysLimit['执行区域'];
        if (strstr($limitArr['productLine'], ';;') || strstr($limitArr['goodsLimit'], ';;') ||
            strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') ||
            strstr($limitArr['customerTypeLimit'], ';;') || strstr($limitArr['contractTypeLimit'], ';;') ||
            strstr($limitArr['companyLimit'], ';;') || strstr($limitArr['exeDeptLimit'], ';;')
        ) {
            return true;
        } else {
            //区域负责人获取相关区域
            $regionDao = new model_system_region_region();
            $areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
            if (!empty ($areaPri)) {
                //区域权限合并
                $limitArr['areaLimit'] = implode(array_filter(array(
                    $limitArr['areaLimit'],
                    $areaPri
                )), ',');

            }

            //销售负责人读取对应省份和客户类型
            $saleArea = new model_system_saleperson_saleperson();
            $saleAreaInfo = $saleArea->getSaleArea($_SESSION['USER_ID']);
            if (!empty($saleAreaInfo)) {
                $limitArr['saleAreaInfo'] = $saleAreaInfo;
            }
            //			print_r($limitArr);
            if (empty ($limitArr)) {
                return false;
            } else {
                //增加销售负责人
                if (!empty($limitArr['saleAreaInfo'])) {
                    $saleAreaStr = "";
                    foreach ($saleAreaInfo as $sval) {
                        $saleTemp = "";
                        //客户类型
                        $saleTemp .= " c.customerType  in ('" . str_replace(',', "','", $sval['customerType']) . "') ";
                        //省份
                        if ($sval['provinceId'] != '0') { //全国过滤掉
                            $saleTemp .= "and c.contractProvinceId ='" . $sval['provinceId'] . "'  ";
                        }
                        $saleAreaStr .= " or ( " . $saleTemp . " ) ";
                    }
                    unset($limitArr['saleAreaInfo']); //消除
                }
                //配置混合权限
                $i = 0;
                $sqlStr = "sql:and ( ";
                $k = 0;
                if (!empty($limitArr['goodsLimit'])) {
                    $goodsLimitArr = explode(",", $limitArr['goodsLimit']);
                    $goodsLimitStr = "and (";
                    foreach ($goodsLimitArr as $k => $v) {
                        if ($k == 0) {
                            $goodsLimitStr .= "FIND_IN_SET($v,goodsTypeStr)";
                        } else {
                            $goodsLimitStr .= "or FIND_IN_SET($v,goodsTypeStr)";
                        }
                    }
                    $goodsLimitStr .= ")";
                    unset($limitArr['goodsLimit']);
                }
                //产品线权限
                if (!empty($limitArr['productLine'])) {
                    $productLineArr = explode(",", $limitArr['productLine']);
                    $productLineStr = "and (";
                    foreach ($productLineArr as $k => $v) {
                        if ($k == 0) {
                            $productLineStr .= "FIND_IN_SET('" . $v . "',newProLineStr)";
                        } else {
                            $productLineStr .= "or FIND_IN_SET('" . $v . "',newProLineStr)";
                        }
                    }
                    $productLineStr .= ")";
                    unset($limitArr['productLine']);
                }
                //执行区域权限
                if (!empty($limitArr['exeDeptLimit'])) {
                    $exeDeptArr = explode(",", $limitArr['exeDeptLimit']);
                    $exeDeptStr = "and (";
                    foreach ($exeDeptArr as $k => $v) {
                        if ($k == 0) {
                            $exeDeptStr .= "FIND_IN_SET('" . $v . "',exeDeptStr)";
                        } else {
                            $exeDeptStr .= "or FIND_IN_SET('" . $v . "',exeDeptStr)";
                        }
                    }
                    $exeDeptStr .= ")";
                    unset($limitArr['exeDeptLimit']);
                }
                //公司权限
                if (!empty($limitArr['companyLimit'])) {
                    $companyArr = explode(",", $limitArr['companyLimit']);
                    $companyStr = "and (";
                    foreach ($companyArr as $k => $v) {
                        if ($k == 0) {
                            $companyStr .= "c.businessBelong = '" . $v . "'";
                        } else {
                            $companyStr .= "or c.businessBelong = '" . $v . "'";
                        }
                    }
                    $companyStr .= ")";
                    unset($limitArr['companyLimit']);
                } else {
                    $companyStr = "and (c.businessBelong = '" . $_SESSION['Company'] . "')";
                }
                //判断审批人
                $USER = $_SESSION['USER_ID'];
                $appNameStr = "(FIND_IN_SET('" . $USER . "',appNameStr) or c.prinvipalId = '" . $_SESSION['USER_ID'] . "')";
//                 unset($limitArr['appNameStr']);
                if(!empty($limitArr)){$sqlStr .= "(";}
                foreach ($limitArr as $key => $val) {
                    $arr = explode(',', $val);
                    if (is_array($arr)) {
                        $val = "";
                        foreach ($arr as $v) {
                            $val .= "'" . $v . "',";
                        }
                        $val = substr($val, 0, -1);
                    }
                    if ($i == 0) {
                        $sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
                    } else {
                        $sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
                    }
                    $i++;
                }
                //加上销售负责区域
                if (!empty($saleAreaStr)) {
                    if (empty($limitArr)) {
                        $sqlStr .= $appNameStr . $saleAreaStr;
                        //$sqlStr = "";
                        //$sqlStr .= "sql: and ".$appNameStr;
                    } else {
                        $sqlStr .= "or " . $appNameStr . $saleAreaStr;
                        // if(!empty($limitArr)){$sqlStr .= ")";}
                        // $sqlStr .= ")";
                    }
                }
                if(!empty($limitArr)){
                    $sqlStr .= ")";

                    // 2018-03-01 与秋莲确认了相关的层级关系是客户类型的权限不应该与销售区域是或的关系,应该先是过滤出了指定的销售区域,再在此销售区域下去获取对应的客户类型
                    if(
                        isset($limitArr['areaLimit']) && !empty($limitArr['areaLimit'])
                        &&
                        (
                            (isset($limitArr['customerTypeLimit']) && !empty($limitArr['customerTypeLimit']))
                            || !empty($saleAreaStr)
                        )
                    ){
                        // 当同时存在销售区域以及客户类型的权限的时候在后面加多一个销售区域AND的关系条件
                        $arr = explode(',', $limitArr['areaLimit']);
                        if (is_array($arr)) {
                            $areaLimitVal = "";
                            foreach ($arr as $v) {
                                $areaLimitVal .= "'" . $v . "',";
                            }
                            $areaLimitVal = substr($areaLimitVal, 0, -1);
                        }
                        $sqlStr .= " and (" . $limitConfigArr['areaLimit'] . " in (" . $areaLimitVal . ")) ";
                    }
                }

                if (!empty($productLineStr)) {
                    if ($sqlStr == "sql:and ( ") {
                        $sqlStr .= " 1=1 " . $productLineStr;
                    } else {
                        $sqlStr .= $productLineStr;
                    }
                }
                if (!empty($exeDeptStr)) {
                    if ($sqlStr == "sql:and ( ") {
                        $sqlStr .= " 1=1 " . $exeDeptStr;
                    } else {
                        $sqlStr .= $exeDeptStr;
                    }
                }
                if (!empty($companyStr)) {
                    if ($sqlStr == "sql:and ( ") {
                        $sqlStr .= " 1=1 " . $companyStr;
                    } else {
                        $sqlStr .= $companyStr;
                    }
                }
                if (!empty($goodsLimitStr)) {
                    $sqlStr .= $goodsLimitStr;
                }

                if ($customSql) {
                    $sqlStr .= $customSql;
                }
                if ($sqlStr != "sql:and ( ") {
                    $sqlStr .= ")";
                    $this->searchArr['mySearchCondition'] = $sqlStr;
                }
                return true;
            }
        }
    }

    //T日报表 权限
    function initLimit_treport($customSql = null,$type){
        if(empty($type)){
            return false;
        }else{
            if($type == "t"){
                $limitCom = "T日表公司权限";
                $limitArea = "T日表销售区域";
            }else if($type == "r"){
                $limitCom = "应收款公司权限";
                $limitArea = "应收款销售区域";
            }
        }
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        //权限配置数组
        $limitConfigArr = array(
            'areaLimit' => 'c.areaCode'
        );
        //权限数组
        $limitArr = array();
        //权限系统
        if (isset ($sysLimit[$limitCom]) && !empty ($sysLimit[$limitCom]))
            $limitArr['companyLimit'] = $sysLimit[$limitCom];
        if (isset ($sysLimit[$limitArea]) && !empty ($sysLimit[$limitArea]))
            $limitArr['areaLimit'] = $sysLimit[$limitArea];
        if (strstr($limitArr['areaLimit'], ';;') && strstr($limitArr['companyLimit'], ';;')){
            $this->searchArr['mySearchCondition'] = "sql: " . $customSql;
            return true;
        }else {
            $orgAreaLimit = $limitArr['areaLimit'];
            $orgCompanyLimit = $limitArr['companyLimit'];
            if(strstr($limitArr['areaLimit'], ';;')){
                unset($limitArr['areaLimit']); //消除
            }
            if(strstr($limitArr['companyLimit'], ';;')){
                unset($limitArr['companyLimit']); //消除
            }

            //区域负责人获取相关区域
            $regionDao = new model_system_region_region();
            $areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
            if (!empty ($areaPri)) {
                //区域权限合并
                $limitArr['areaLimit'] = implode(array_filter(array(
                    $limitArr['areaLimit'],
                    $areaPri
                )), ',');

            }
            //销售负责人读取对应省份和客户类型
            $saleArea = new model_system_saleperson_saleperson();
            $saleAreaInfo = $saleArea->getSaleArea($_SESSION['USER_ID']);
            if (!empty($saleAreaInfo)) {
                $areaExt = "";
                foreach($saleAreaInfo as $val){
                    $areaExt .= $val['salesAreaId'].",";
                }
            }
            if (!empty ($areaExt)) {
                //区域权限合并
                $limitArr['areaLimit'] .= ",".$areaExt;
            }

            // 区域权限清除重复以及空的值
            $areaLimitArr = explode(",",$limitArr['areaLimit']);
            $areaLimitArr = array_unique($areaLimitArr);
            foreach ($areaLimitArr as $k => $v){
                if($v == ""){
                    unset($areaLimitArr[$k]);
                }
            }
            $areaLimitArr = implode($areaLimitArr,",");
            $limitArr['areaLimit'] = $areaLimitArr;

            // 重新验证一次销售与公司权限,如果有开全部的,则消除
            if(strstr($orgAreaLimit, ';;')){
                unset($limitArr['areaLimit']); //消除
            }
            if(strstr($orgCompanyLimit, ';;')){
                unset($limitArr['companyLimit']); //消除
            }

            if (empty ($limitArr)) {
                return false;
            }else{
                //配置混合权限
                $i = 0;
                $sqlStr = "sql:and ( ";
                //公司权限
                if (!empty($limitArr['companyLimit'])) {
                    $companyArr = explode(",", $limitArr['companyLimit']);
                    $companyStr = "and (";
                    foreach ($companyArr as $k => $v) {
                        if ($k == 0) {
                            $companyStr .= "c.businessBelong = '" . $v . "'";
                        } else {
                            $companyStr .= "or c.businessBelong = '" . $v . "'";
                        }
                    }
                    $companyStr .= ")";
                    unset($limitArr['companyLimit']);
                }

                foreach ($limitArr as $key => $val) {
                    $arr = explode(',', $val);
                    if (is_array($arr)) {
                        $val = "";
                        foreach ($arr as $v) {
                            $val .= "'" . $v . "',";
                        }
                        $val = substr($val, 0, -1);
                    }
                    if ($i == 0) {
                        $sqlStr .= $limitConfigArr[$key] . " in (" . $val . ")";
                    } else {
                        $sqlStr .= " or " . $limitConfigArr[$key] . " in (" . $val . ")";
                    }
                    $i++;
                }

                if (!empty($companyStr)) {
                    if ($sqlStr == "sql:and ( ") {
                        $sqlStr .= " 1=1 " . $companyStr;
                    } else {
                        $sqlStr .= $companyStr;
                    }
                }
                if ($customSql) {
                    if($sqlStr == "sql:and ( "){
                        $sqlStr .= " 1=1 " .$customSql;
                    }else{
                        $sqlStr .= $customSql;
                    }

                }

                if ($sqlStr != "sql:and ( ") {
                    $sqlStr .= ")";
                    $this->searchArr['mySearchCondition'] = $sqlStr;
                }

                return true;
            }
        }
    }

    /**
     * 筹建中的合同
     */
    function buildContract_d($conditions, $isExport = false)
    {
        if (!isset ($conditions['finishStatus'])) {
            $conditions['finishStatus'] = '3';
        }
        $conditions['isTemp'] = '0';
        $conditions['ExaStatus'] = '完成';

        if ($conditions['finishStatus'] == "3") {
//            $customSql = " and ExaDTOne <> '' and date_format(c.ExaDTOne,'%Y-%m-%d') > '2015-12-31'";
            $customSql = " and ExaDTOne <> '' and ExaDTOne <> '0000-00-00'";
        } else {
            if ($conditions['finishStatus'] == 1) {
                //                $customSql = " and ExaDTOne <> '' and deliveryDate <> ''  and checkStatus <> '未录入' and reNum!='0'";
//                $customSql = " and ExaDTOne <> '' and date_format(c.ExaDTOne,'%Y-%m-%d') > '2015-12-31' and ch.chNum!='0' and re.reNum!='0'";
                $customSql = " and ExaDTOne <> '' and ExaDTOne <> '0000-00-00' and ch.chNum!='0' and re.reNum!='0'";
            } else if ($conditions['finishStatus'] == 0) {
//                $customSql = " and ExaDTOne <> '' and date_format(c.ExaDTOne,'%Y-%m-%d') > '2015-12-31' and (ch.chNum is null OR re.reNum is null)  ";
                $customSql = " and ExaDTOne <> '' and ExaDTOne <> '0000-00-00' and (ch.chNum is null OR re.reNum is null)  ";
            }
        }

        if($conditions['isIncome'] == "all"){
            $customSql .= "";
        }else if($conditions['isIncome'] == '0'){
            $customSql .= " and (contractMoney-incomeMoney-badMoney-deductMoney > 0) ";
        }else if($conditions['isIncome'] == '1'){
            $customSql .= " and (contractMoney-incomeMoney-badMoney-deductMoney = 0) ";
        }

        $this->getParam($conditions);
        $this->initLimit($customSql);
        $this->groupBy = "c.id";
        if (empty($this->searchArr['mySearchCondition'])) {
            $this->searchArr['mySearchCondition'] = "sql: $customSql";
        }
        if ($isExport) { //用于导出
            return $this->listBysqlId('select_buildList');
        } else {
//            $data = $this->pageBysqlId('select_buildList');
//            echo $this->listSql;exit();
            return $this->pageBysqlId('select_buildList');
        }
    }

    /**
     * 文本归档
     */
    function contractArchive_d($customSql)
    {
        $this->searchArr = array('states' => '1,2,3,4,5,6,7',
            'isTemp' => '0',
            'ExaStatus' => '完成',
            'signStatusArr' => '0,2');
        //    	//14年的数据 = 暂时
        //        $customSql .= " and id in (".CONTOOLIDS.") ";
        $this->initLimit();
        $this->sort = "isSigned ASC,isArchiveOutDate";
        return $this->pageBysqlId('select_buildList');
    }

    /**
     * 新查看页面
     */
    function viewContract_d($id)
    {
        $rows = $this->searchArr = array('id' => $id);
        $row = $this->list_d('select_gridinfo');
        $con = new controller_contract_contract_contract();
        $row = $con->sconfig->md5Rows($row[0]);
        $skeyStr = "&skey=" . $row['skey_'];
        //发货计划链接
        switch ($row['DeliveryStatus']) {
            case 'YFH' :
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>已发货</a>";
                break;
            case 'BFFH':
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>部分发货</a>";
                break;
            case 'WFH':
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>未发货</a>";
                break;
            case 'TZFH':
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>停止发货</a>";
                break;
        }
        $row['invoiceMoney'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toInvoiceTab&obj[objType]=KPRK-12&obj[objCode]=" . $row['contractCode'] . "&obj[objId]=" . $id . $skeyStr . '",1,' . $id . ")'>" . $row['invoiceMoney'] . "</a>";
        $row['incomeMoney'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toIncomeTab&obj[objType]=KPRK-12&obj[objCode]=" . $row['contractCode'] . "&obj[objId]=" . $id . $skeyStr . '",1,' . $id . ")'>" . $row['incomeMoney'] . "</a>";
        $rows = $row;
        $rows = $this->processDatadict($rows);
        //获取项目信息
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectArr = $esmprojectDao->findAll(array('contractId' => $id));
        $esmprojectCon = new controller_engineering_project_esmproject();
        $esmprojectArr = $esmprojectCon->sconfig->md5Rows($esmprojectArr);
        //工程项目链接
        foreach ($esmprojectArr as $k => $v) {
            if ($v['status'] == 'GCXMZT01' && $v['ExaStatus'] == '部门审批') {
                $esmprojectArr[$k]['projectCode'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" . $v['id'] . '&skey=' . $v['skey_'] . '",1,' . $v['id'] . ')\'>' . $v['projectCode'] . "</a>";
            } else {
            	$esmprojectArr[$k]['projectCode'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" . $v['id'] . '&skey=' . $v['skey_'] . '",1,' . $v['id'] . ')\'>' . $v['projectCode'] . "</a>";
//                switch ($v['status']) {
//                    case 'GCXMZT01' :
//                        $esmprojectArr[$k]['projectCode'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=editTab&id=" . $v['id'] . '&skey=' . $v['skey_'] . '",1,' . $v['id'] . ')\'>' . $v['projectCode'] . "</a>";
//                        break;
//                    case 'GCXMZT02' :
//                        $esmprojectArr[$k]['projectCode'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=manageTab&id=" . $v['id'] . '&skey=' . $v['skey_'] . '",1,' . $v['id'] . ')\'>' . $v['projectCode'] . "</a>";
//                        break;
//                    case 'GCXMZT03' :
//                        $esmprojectArr[$k]['projectCode'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" . $v['id'] . '&skey=' . $v['skey_'] . '",1,' . $v['id'] . ')\'>' . $v['projectCode'] . "</a>";
//                        break;
//                    case 'GCXMZT04' :
//                        $esmprojectArr[$k]['projectCode'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" . $v['id'] . '&skey=' . $v['skey_'] . '",1,' . $v['id'] . ')\'>' . $v['projectCode'] . "</a>";
//                        break;
//                    default :
//                        $esmprojectArr[$k]['projectCode'] = $v['projectCode'];
//                }
            }
        }
        $rows['project'] = $esmprojectArr;
        //获取发货信息
        $equDao = new model_contract_contract_equ();
        $equDao->searchArr = array('contractId' => $id, 'isDel' => '0', 'isTemp' => '0');
        $equArr = $equDao->list_d();
        $rows['equ'] = $equArr;
        //获取财务信息
        $this->searchArr = array('id' => $id);
        $receiptplanArr = $this->list_d('select_financialTday');
        foreach ($receiptplanArr as $key => $val) {
            $receiptplanArr[$key]['incomMoneyHtml'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_income_incomecheck&action=checkList&payConId=" . $val['id'] . "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900\",1," . $val['id'] . ');\'>' . $receiptplanArr[$key]['incomMoney'] . "</a>";
            $receiptplanArr[$key]['invoiceMoneyHtml'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]=KPRK-12&obj[objCode]=".$val['contractCode']."&obj[objId]=".$val['contractId']."&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900\",1," . $val['id'] . ');\'>' . $receiptplanArr[$key]['invoiceMoney'] . "</a>";
        }
        $rows['receiptplan'] = $receiptplanArr;
        //获取验收信息
        $checkDao = new model_contract_checkaccept_checkaccept();
        $checkArr = $checkDao->findAll(array('contractId' => $id));
        foreach ($checkArr as $key => $val) {
            $checkArr[$key]['file'] = $this->getFilesByObjId($val['id'], false, 'oa_contract_check');
        }
        $rows['checkAccept'] = $checkArr;
        return $rows;
    }

    /**
     * 合同交付
     */
    function deliveryContract_d($conditions, $isExport = false)
    {
        $conditions['isTemp'] = '0';
        $conditions['ExaStatus'] = '完成';
        if (isset($conditions['state'])) {
            $conditions['states'] = $conditions['state'];
            unset($conditions['state']);

        }
        $customSql = " and ExaDTOne <> ''";
        $this->searchArr = $conditions;
        $this->initLimit($customSql);
        $this->sort = "isExceed";
        $this->asc = false;
        if ($isExport) { //用于导出
            return $this->listBysqlId('select_buildList');
        } else {
            return $this->pageBysqlId('select_buildList');
        }
    }

    /**
     * 更新合同签收超期提醒
     */
    function updateSignRemind_d($conditions)
    {
        $arr = $this->find(array('id' => $conditions['id']), null, 'signRemind');
        $this->update($conditions, array('signRemind' => $arr['signRemind'] + 1));
    }

    /**
     * 更新发货超期提醒
     */
    function updateOutGoodsRemind_d($conditions)
    {
        $arr = $this->find(array('id' => $conditions['docId']), null, 'outGoodsRemind');
        $this->update(array('id' => $conditions['docId']), array('outGoodsRemind' => $arr['outGoodsRemind'] + 1));
    }

    /**
     * 合同生命周期
     */
    function leftCycle_d($cid)
    {
        $arr = $this->get_d($cid);
        /*********合同建立******************/
        //获取变更信息
        $changeInfo = $this->changeinfo_html($cid);
        $html = "";
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="1"></a>
		        <span class="headline-1-index">1</span>
		        <span class="headline-content">合同建立</span>
		    </h2>
		    <div class="para">
		           <br><br>
		           【 $arr[createTime] 】  由  【 $arr[createName] 】 创建成功
                   $changeInfo
                   <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		    </div>
EOT;
        /*********成本概算******************/
        //获取变更信息
        $costInfo = $this->confirmCost_html($arr);
        $costInfoApp = $this->confirmCostApp_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="2"></a>
		        <span class="headline-2-index">2</span>
		        <span class="headline-content">成本概算</span>
		    </h2>

		    <div class="para">
		        2.1 成本确认 <br><br>
                    $costInfo
		         2.2 成本审核<br><br>
		            $costInfoApp
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>

		    </div>
EOT;
        /*********合同审批******************/
        //获取变更信息
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="3"></a>
		        <span class="headline-3-index">3</span>
		        <span class="headline-content">合同审批</span>
		    </h2>

		    <div class="para" style="width:75%">
		        <div id="appView">
				   <input type="hidden" id="type" value="contract" />
				   <input type="hidden" id="pid" value="$arr[id]" />
				   <input type="hidden" id="itemType" value="oa_contract_contract" />
				   <input type="hidden" id="isChange" value="all" /></td>
				</div>
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>
		    </div>
EOT;
        /*********项目立项******************/
        //获取变更信息
        $createProInfo = $this->createPorject_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="4"></a>
		        <span class="headline-4-index">4</span>
		        <span class="headline-content">项目立项</span>
		    </h2>

		    <div class="para" style="width:75%">
		        $createProInfo
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>
		    </div>
EOT;
        /*********项目执行******************/
        //获取变更信息
        $exeProInfo = $this->exePorject_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="5"></a>
		        <span class="headline-5-index">5</span>
		        <span class="headline-content">项目执行</span>
		    </h2>

		    <div class="para" style="width:75%">
		        $exeProInfo
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>
		    </div>
EOT;
        /*********合同关闭******************/
        //获取变更信息
        $closeInfo = $this->closeContract_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="6"></a>
		        <span class="headline-6-index">6</span>
		        <span class="headline-content">合同关闭</span>
		    </h2>

		    <div class="para" style="width:75%">
		        $closeInfo
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br>
		    </div>
EOT;


        return $html;
    }

    //根据合同id获取变更人变更时间
    function changeinfo_html($cid)
    {
        $sql = "SELECT changeTime,changeManName FROM `oa_contract_changlog` where objId = '" . $cid . "' and objType = 'contract'";
        $arr = $this->_db->getArray($sql);
        if (is_array($arr)) {
            $html = "";
            foreach ($arr as $k => $v) {
                $html .= <<<EOT
			    <div class="para">
			          【 $v[changeTime] 】  由  【 $v[changeManName] 】 提交变更
			    </div>
EOT;
            }
        }
        if (!empty($html)) {
            return $html;
        } else {
            return "";
        }
    }

    //成本确认
    function confirmCost_html($arr)
    {
        $productLineStr = $arr['productLineStr'];
        $productLineStr = rtrim($productLineStr, ',');
        $productLineArr = explode(",", $productLineStr);
        $productLineArr = array_flip($productLineArr);
        $productLineArr = array_flip($productLineArr);

        if (is_array($productLineArr)) {
            $html = "";
            $datadictDao = new model_system_datadict_datadict();
            $costDao = new model_contract_contract_cost();
            $isArr = array();
            foreach ($productLineArr as $k => $v) {
                //判断产品线是否存在
                $lineName = $datadictDao->getDataNameByCode($v);
                if (in_array($v, $isArr)) {
                    $cost = $costDao->getCostByidline($arr['id'], $v, "1");
                } else {
                    $cost = $costDao->getCostByidline($arr['id'], $v);
                }
                if (empty($cost) || $cost['state'] == '0' || $cost['state'] == '2' || $cost['state'] == '3') {
                    if ($cost['state'] == '2') {
                        if ($arr['isSubApp'] == '0') {
                            $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】  打回 至销售重新处理<br><br>
			    </div>
EOT;
                        } else {
                            $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】 由 【 $cost[costAppName] 】 在 【 $cost[costAppDate] 】 打回未确认<br><br>
			    </div>
EOT;
                        }

                    } else if ($cost['state'] == '3') {
                        $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】 销售人员未确认发货物料<br><br>
			    </div>
EOT;
                    } else {
                        $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】 成本未确认<br><br>
			    </div>
EOT;
                    }
                } else if ($cost['state'] == '1') {
                    $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】 由 【 $cost[confirmName] 】 在 【 $cost[confirmDate] 】 确认<br><br>
			    </div>
EOT;
                }
                //已经生成html的产品线数组
                $isArr[] = $v;
            }
        }
        return $html;
    }

    //成本审核
    function confirmCostApp_html($arr)
    {
        $productLineStr = $arr['productLineStr'];
        $productLineStr = rtrim($productLineStr, ',');
        $productLineArr = explode(",", $productLineStr);
        $productLineArr = array_flip($productLineArr);
        $productLineArr = array_flip($productLineArr);
        if (is_array($productLineArr)) {
            $html = "";
            $datadictDao = new model_system_datadict_datadict();
            $costDao = new model_contract_contract_cost();
            $isArr = array();
            foreach ($productLineArr as $k => $v) {
                //判断产品线是否存在
                $lineName = $datadictDao->getDataNameByCode($v);
                if (in_array($v, $isArr)) {
                    $cost = $costDao->getCostByidline($arr['id'], $v, "1");
                } else {
                    $cost = $costDao->getCostByidline($arr['id'], $v);
                }
                if (empty($cost) || $cost['ExaState'] == '0') {
                    $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】 成本未审核<br><br>
			    </div>
EOT;
                } else if ($cost['ExaState'] == '1') {
                    $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】 由 【 $cost[costAppName] 】 在 【 $cost[costAppDate] 】 审核通过<br><br>
			    </div>
EOT;
                } else {
                    $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          产品线 【 $lineName 】 由 【 $cost[costAppName] 】 在 【 $cost[costAppDate] 】 打回<br><br>
			    </div>
EOT;
                }
                //已经生成html的产品线数组
                $isArr[] = $v;
            }
        }
        return $html;
    }

    //项目立项信息
    function createPorject_html($arr)
    {
        $cid = $arr['id'];
        $sql = "select projectCode,createName,createTime from oa_esm_project where contractId = '" . $cid . "' and contractType='GCXMYD-01'";
        $arr = $this->_db->getArray($sql);
        $html = "";
        if (!empty($arr) && is_array($arr)) {
            foreach ($arr as $k => $v) {
                $html .= <<<EOT
			    <div class="para">
			          【$v[createTime]】  由  【$v[createName]】 建立项目 【 $v[projectCode] 】<br><br>
			    </div>
EOT;
            }
        } else {
            $html .= <<<EOT
			    <div class="para">
			         未找到立项信息
			    </div>
EOT;
        }
        return $html;
    }

    //项目执行
    function exePorject_html($arr)
    {
        $cid = $arr['id'];
        $sql = "select projectCode,createName,createTime,projectProcess,statusName from oa_esm_project where contractId = '" . $cid . "' and contractType='GCXMYD-01'";
        $arr = $this->_db->getArray($sql);
        if (!empty($arr) && is_array($arr)) {
            $html = "";
            foreach ($arr as $k => $v) {
                $html .= <<<EOT
			    <div class="para">
			          项目 【$v[projectCode]】  当前状态 【 $v[statusName] 】 进度 【 $v[projectProcess]% 】<br><br>
			    </div>
EOT;
            }
        } else {
            $html = "";
            $html .= <<<EOT
			    <div class="para">
			         未找到项目信息
			    </div>
EOT;
        }
        return $html;
    }

    //合同关闭信息
    function closeContract_html($arr)
    {
        $html = "";
        if ($arr['state'] == '3' || $arr['state'] == '7') {
            if ($arr['closeType'] == "正常关闭") {
                $html .= <<<EOT
			    <div class="para">
			          合同按进度正常关闭， 关闭时间 【 $arr[closeTime] 】<br><br>
			    </div>
EOT;
            } else if ($arr['closeType'] == "异常关闭") {
                $html .= <<<EOT
			    <div class="para">
			          由 【 $arr[closeName] 】 与 【 $arr[closeTime] 】 申请异常关闭<br>
			          异常关闭原因  【 $arr[closeRegard] 】<br>
			    </div>
EOT;
            }
        }
        return $html;
    }

    /**
     * 根据合同id 获取该合同是否需要进行确认发货物料
     */
    function getConfirmEqubyId($cid, $isSubAppChange)
    {

        if ($isSubAppChange == '1') {
            $changeId = $this->findChangeId($cid);
            $sql = "select count(id) as num from oa_contract_cost where contractId = '" . $changeId . "' and state='3' ";
        } else {
            $sql = "select count(id) as num from oa_contract_cost where contractId = '" . $cid . "' and state='3' ";
        }
        $arr = $this->_db->getArray($sql);
        $conArr = $this->get_d($cid);

        if ($arr[0]['num'] > 0) {
            if ($isSubAppChange == '1') {
                return "3";
            } else {
                return "1";
            }
        } else if ($conArr['dealStatus'] == '4') {
            return "2";
        } else {
            return "0";
        }
    }

    /**
     * 发货物料确认
     */
    function confirmEqu_d($cid, $act, $isSubAppChange, $oldId = null, $needSalesLine = true)
    {
        $handleDao = new model_contract_contract_handle();
        if ($isSubAppChange == '1') {
            $isChange = 2;
            $conId = $oldId;
        } else {
            $isChange = 0;
            $conId = $cid;
        }
        if ($act == 'back') {
            $userId = $_SESSION ['USER_ID'];
            $userName = $_SESSION ['USERNAME'];
            $costTime = date("Y-m-d H:i:s");
            //更新回退 物料确认状态
            $updateA = "update oa_contract_contract set dealStatus='0' where id='" . $cid . "'";
            $this->_db->query($updateA);
            $updateB = "update oa_contract_equ_link set ExaStatus='打回' where contractId='" . $cid . "'";
            $this->_db->query($updateB);
            $updateSql = "update oa_contract_cost set state='2',costAppName='" . $userName . "',costAppId='" . $userId . "',costAppDate='" . $costTime . "' where contractId = '" . $cid . "' and state='3' ";
            $this->_db->query($updateSql);

            $handleDao->handleAdd_d(array(
                "cid" => $conId,
                "stepName" => "打回物料确认",
                "isChange" => $isChange,
                "stepInfo" => "",
            ));

        } else {
            $updateSql = "update oa_contract_cost set state='1' where contractId = '" . $cid . "' and state='3' ";
            $this->_db->query($updateSql);
            if($needSalesLine){
                $handleDao->handleAdd_d(array(
                    "cid" => $conId,
                    "stepName" => "销售确认物料",
                    "isChange" => $isChange,
                    "stepInfo" => "",
                ));
            }

//	     	$sql = "select * from oa_contract_cost where contractId = '".$cid."'";
//	 		$rows = $this->_db->getArray($sql);
//			if ($rows) {
//				foreach ( $rows as $key => $val ) {
//					$moneyAll += $val['confirmMoney'];
//				}
//			}
//            //更新回退 物料确认状态
//            $updateE = "update oa_contract_contract set saleCost=".$moneyAll." where id='".$oldId."'";
//            $this->_db->query($updateE);
//            $this->countCost($oldId);
        }
    }

    //更新物料表的conProductId
    function updateConProductId_d($onlyProductId)
    {
        if (!empty($onlyProductId)) {
            $selectSql = "select id from oa_contract_product where onlyProductId = '" . $onlyProductId . "'";
            $selObj = $this->_db->get_one($selectSql);
            $updateSql = "update oa_contract_equ  set conProductId = '" . $selObj['id'] . "' where onlyProductId = '" . $onlyProductId . "' and isBorrowToorder != 1";
            $this->_db->query($updateSql);
        }
    }

    //更新借用转销售物料的 proId
    function updateConProductIdBybow_d($onlyProductId)
    {
        if (!empty($onlyProductId)) {
            $selectSql = "select id from oa_contract_product where onlyProductId = '" . $onlyProductId . "'";
            $selObj = $this->_db->get_one($selectSql);
            $updateSql = "update oa_contract_equ  set proId = '" . $selObj['id'] . "' where onlyProductId = '" . $onlyProductId . "' and isBorrowToorder=1";
            $this->_db->query($updateSql);
        }
    }

    /**
     * 判读 是否存在相同产线 不同类型产品
     * $ff 0  不存在 1 存在，但为确认成本 2，存在且已确认成本
     */
    function deffLinePro($costArr, $cid)
    {
        $proDao = new model_contract_contract_product();
        $proArr = $proDao->getCostInfoProBycId($cid);
        $issale = $costArr['issale'];
        $productLine = $costArr['productLine'];
        //判断产线确认记录除当前数据外是否存在同类型其他数据
        $sql = "select * from oa_contract_cost where contractId = '" . $cid . "' and Exastate='0' and state =1 and productLine='" . $productLine . "' and issale != '" . $issale . "'";
        $cArr = $this->_db->getArray($sql);
        $ff = 0;
        if (!empty($cArr)) {
            $ff = '2';
        } else {
            if ($costArr['issale'] == '1') { //销售类， 需判断是否存在同产线服务类产品是否存在及 是否已确认成本.
                foreach ($proArr as $k => $v) {
                    if (($v['goodsTypeId'] == '17') && ($v['exeDeptName'] == $costArr['productLineName'])) {
                        $ff = '1';
                    }
                }
            } else if ($costArr['issale'] == '0') { //服务类类， 需判断是否存在同产线销售类产品是否存在及 是否已确认成本.
                foreach ($proArr as $k => $v) {
                    if (($v['goodsTypeId'] == '11') && ($v['exeDeptName'] == $costArr['productLineName'])) {
                        $ff = '1';
                    }
                }
            }

        }
        return $ff;
    }

    /**
     * 获取流程图内 产品 执行部门字符串
     */
    function getProLineStr($cid, $type)
    {
        if ($type == '11') {
            $sql = "select GROUP_CONCAT(exeDeptId) as str from oa_contract_product where contractId=" . $cid . " and proTypeId in (11)";
        } else {
            $sql = "select GROUP_CONCAT(exeDeptId) as str from oa_contract_product where contractId=" . $cid . " and proTypeId in (17,18)";
        }
        $arr = $this->_db->getArray($sql);
        return $arr[0]['str'];
    }

    /**
     * 合同基础数据处理
     */
    function basicDataProcess_d($rows)
    {
        $esmDao = new model_engineering_project_esmproject();
        $conprojectDao = new model_contract_conproject_conproject();
        //获取项目相关实时数据
        foreach ($rows as $key => $val) {
            $rows[$key]['exgross'] = $rows[$key]['exgross'] . '%';
            $rows[$key]['rateOfGross'] = $rows[$key]['rateOfGross'] . '%';
            $contractId = $val['id'];
            $arr = $conprojectDao->findAll(array('contractId' => $contractId));
            foreach ($arr as $k => $v) {
                $esmId = $v['esmProjectId'];
                $esmArr = $esmDao->getProjectList_d(array($contractId));
                $conArr = $conprojectDao->getConPorjectNowInfoByCid($v, $esmArr[$esmId]);
                $projectCode = empty($esmArr[$esmId]['projectCode']) ? "--" : $esmArr[$esmId]['projectCode'];
                $projectName = empty($esmArr[$esmId]['projectName']) ? "--" : $esmArr[$esmId]['projectName'];
                $proMoney = empty($conArr['proMoney']) ? "--" : number_format($conArr['proMoney'], 2);
                $estimates = empty($conArr['estimates']) ? "--" : number_format($conArr['estimates'], 2);
                $cost = empty($conArr['cost']) ? "--" : number_format($conArr['cost'], 2);
                $earnings = empty($conArr['earnings']) ? "--" : number_format($conArr['earnings'], 2);
                $schedule = empty($conArr['schedule']) ? "--" : $conArr['schedule'] . "%";
                //项目编号
                if (!isset($rows[$key]['projectCode'])) {
                    $rows[$key]['projectCode'] = $projectCode;
                } else {
                    $rows[$key]['projectCode'] .= "\n" . $projectCode;
                }
                //项目名称
                if (!isset($rows[$key]['projectName'])) {
                    $rows[$key]['projectName'] = $projectName;
                } else {
                    $rows[$key]['projectName'] .= "\n" . $projectName;
                }
                //项目金额
                if (!isset($rows[$key]['proMoney'])) {
                    $rows[$key]['proMoney'] = $proMoney;
                } else {
                    $rows[$key]['proMoney'] .= "\n" . $proMoney;
                }
                //项目类型
                if (!isset($rows[$key]['projectType'])) {
                    if (empty($esmId)) {
                        $rows[$key]['projectType'] = "销售类";
                    } else {
                        $rows[$key]['projectType'] = "服务类";
                    }
                } else {
                    if (empty($esmId)) {
                        $rows[$key]['projectType'] .= "\n销售类";
                    } else {
                        $rows[$key]['projectType'] .= "\n服务类";
                    }
                }
                //项目概算
                if (!isset($rows[$key]['estimates'])) {
                    $rows[$key]['estimates'] = $estimates;
                } else {
                    $rows[$key]['estimates'] .= "\n" . $estimates;
                }
                //项目决算
                if (!isset($rows[$key]['cost'])) {
                    $rows[$key]['cost'] = $cost;
                } else {
                    $rows[$key]['cost'] .= "\n" . $cost;
                }
                //项目收入
                if (!isset($rows[$key]['earnings'])) {
                    $rows[$key]['earnings'] = $earnings;
                } else {
                    $rows[$key]['earnings'] .= "\n" . $earnings;
                }
                //项目进度
                if (!isset($rows[$key]['schedule'])) {
                    $rows[$key]['schedule'] = $schedule;
                } else {
                    $rows[$key]['schedule'] .= "\n" . $schedule;
                }
            }
        }
        return $rows;
    }

    /**
     * 获取借试用关联的商机数据
     */
    function getChanceByBorrowIds_d($borrowIds)
    {
        $rowLength = 8;
        $rowWidth = 100 / $rowLength;
        $sql = "SELECT
					c.id,
					c.chanceCode,
					c.`status`
				FROM
					oa_borrow_borrow b
				INNER JOIN oa_sale_chance c ON b.chanceId = c.id
				WHERE
					b.id IN ({$borrowIds})
				AND c.`status` <> '4'
		    	GROUP BY
					c.id";
        $rs = $this->_db->getArray($sql);
        $btn = '<input type="button" class="txt_btn_a" value=" 提  交 " onclick="toSubmit();"/>' .
            '&nbsp;&nbsp;<input type="button" class="txt_btn_a" value=" 关  闭 " onclick="tb_remove();"/>';
        if ($rs) {
            $rsLength = count($rs);
            $str = '<table class="main_table" style="width:800px;">' .
                '<td colspan="99" class="form_header"><input id="allCheckbox" type="checkbox" onclick="chanceCheckAll(this)"/>' .
                '全选 （共<span id="chanceAllNum">' . $rsLength . '</span>条记录,选中<span id="chanceNum">0</span>条）' .
                '</td>';
            $i = 0;
            foreach ($rs as $v) {
                if ($i == 0) $str .= "<tr>";
                $str .= '<td style="width:' . $rowWidth . '%;"><input type="checkbox" id="chanceId' . '-' . $v['id'] . '" name="contract[turnChance][]" value="' . $v['id'] . '" onclick="chanceCheckThis(this)"/>' .
                    '<a href="javascript:void(0);" style="color:blue;" onclick="chanceViewForm(\'' . $v['id'] . '\')">' . $v['chanceCode'] . '</a></td>';
                $i++;
                if ($i == $rowLength) {
                    $str .= '</tr>';
                    $i = 0;
                }
            }
            $leastNum = $rowLength - $i;
            if ($i != $rowLength) $str .= '<td colspan="' . $leastNum . '"></td></tr>';
            $str .= '<tr><td class="txt_btn" colspan="5">' . $btn . '</td></tr></table>';
            return $str;
        } else {
            return '<table class="main_table" style="width:800px;"><tr><td colspan="5"> - 没有找到相关的商机数据 - <br />' . $btn . '</td></tr></table>';
        }
    }

    /**
     * 计算 T日确认列表的 项目结束时间
     */
    function getTdayListEndDate($cid,$paymenttermId){
        $payconfigDao = new model_contract_config_payconfig();
        $payconfigArr = $payconfigDao->get_d($paymenttermId);

        if ($payconfigArr['dateCode'] == 'beginDate' || $payconfigArr['dateCode'] == 'endDate') {
            $sql = "select if(actEndDate is null,planEndDate,actEndDate) as endDate from oa_esm_project where contractId= '" . $cid . "'
				group by contractId";
            $arr = $this->_db->getArray($sql);
        } else {
            $sql = "select  outstockDate as endDate " .
                "from oa_contract_contract where id = '" . $cid . "'";
            $arr = $this->_db->getArray($sql);
        }
        if (!empty($arr[0]['endDate']) && $arr[0]['endDate'] != '0000-00-00') {
            return $arr[0]['endDate'];
        } else {
            return "-";
        }
    }
    function getTdayListEndState($cid,$paymenttermId){
        $payconfigDao = new model_contract_config_payconfig();
        $payconfigArr = $payconfigDao->get_d($paymenttermId);

        if ($payconfigArr['dateCode'] != 'beginDate' && $payconfigArr['dateCode'] != 'endDate') {
            $sql = "select if(actEndDate is null,planEndDate,actEndDate) as endDate from oa_esm_project where contractId= '" . $cid . "'
				group by contractId";
            $arr = $this->_db->getArray($sql);
        } else {
            $sql = "select  outstockDate as endDate " .
                "from oa_contract_contract where id = '" . $cid . "'";
            $arr = $this->_db->getArray($sql);
        }
        if (!empty($arr[0]['endDate']) && $arr[0]['endDate'] != '0000-00-00') {
            return $arr[0]['endDate'];
        } else {
            return "-";
        }
    }


    /**
     * 更新合同
     */
     function updateConState_d(){
     	ini_set("memory_limit","1000M");
     	set_time_limit(0);
     	  $this->titleInfo("准备获取合同数据...");
     	  $sql = "select * from oa_contract_contract where isTemp=0 and (ExaDTOne is not null or ExaDTOne <> '') ";
          $arr = $this->_db->getArray($sql);
          $this->titleInfo("获取合同数据成功，准备更新,时间稍长，请不要关闭页面...");
          $arrNum = count($arr) / 10;
          $p = 10;
          $a = $arrNum;
          foreach($arr as $k => $v){
          	 if($k >= $a){
          	 	$this->titleInfo("已更新".$p."%...");
          	 	$a = $a + $arrNum;
          	 	$p = $p + 10;
          	 }
          	 $this->updateOutStatus_d($v['id']);
          }
          $this->titleInfo("更新完成！");
     }
     //提示信息
	 function titleInfo($ff){
	 	echo str_pad($ff,4096).'<hr />';
		flush();
		ob_flush();
		sleep(0.1);
	 }


	 /**
	  * 根据id串 统计计算未回款信息金额
	  */
	function getUnIncomeArr($idStr,$overPointY='',$overPointM=''){
		$idArr = explode(",",$idStr);
		$reDao = new model_contract_contract_receiptplan();
        $Year = ($overPointY == '')? "Y" : $overPointY;
        $Month = ($overPointM == '')? "n" : $overPointM;
		$season = ceil((date($Month))/3);//当月是第几季度
		$Q_star = date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date($Year)));//本季度开始日期
		$Q_end =  date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date($Year))),date($Year)));//本季度结束日期
		$Q_Next_end = date("Y-m-d", strtotime("+3 months", strtotime($Q_end)));//下季度结束日期

        $overPointStr = ($overPointY!='' && $overPointM!='')? "{$overPointY}-{$overPointM}-1" : "Y-m-1";
		$t_3 = date("Y-m-1", strtotime("-3 months", strtotime(date($overPointStr))));//逾期3个月日期节点
		$t_6 = date("Y-m-1", strtotime("-6 months", strtotime(date($overPointStr))));//逾期6个月日期节点
		$t_12 = date("Y-m-1", strtotime("-12 months", strtotime(date($overPointStr))));//逾期12个月日期节点

        foreach($idArr as $k=>$v){
        	$arr = $reDao->getDetail_d($v);
        	$conArr = $this->get_d($v);
        	if($conArr['state'] != '7'){
                if(!empty($arr)){
                    foreach($arr as $key=>$val){
                        if(!empty($val['Tday'])){
                            if(strtotime(date($overPointStr)) > strtotime($val['Tday']) && ($val['money'] - $val['incomMoney']) > 0){
                                $rtnArr['unInomeMoney'] +=  $val['money'] - $val['incomMoney'];
                            }else if(strtotime($val['Tday']) > strtotime(date($overPointStr)) && strtotime($val['Tday']) < strtotime($Q_end) && ($val['money'] - $val['incomMoney']) > 0 ){
                                $rtnArr['unInomeMoney_q'] +=  $val['money'] - $val['incomMoney'];
                            }else if(strtotime($val['Tday']) > strtotime($Q_end) && strtotime($val['Tday']) < strtotime($Q_Next_end) && ($val['money'] - $val['incomMoney']) > 0 ){
                                $rtnArr['unInomeMoney_nq'] +=  $val['money'] - $val['incomMoney'];
                            }else{
                                $rtnArr['unInomeMoney_aq'] +=  $val['money'] - $val['incomMoney'];
                            }
                            //逾期金额.
                            if(strtotime(date($overPointStr)) > strtotime($val['Tday']) && strtotime($val['Tday']) > strtotime($t_3) && ($val['money'] - $val['incomMoney']) > 0){
                                $rtnArr['unInomeMoney3'] +=  $val['money'] - $val['incomMoney'];
                            }else if(strtotime($t_3) > strtotime($val['Tday']) && strtotime($val['Tday']) > strtotime($t_6) && ($val['money'] - $val['incomMoney']) > 0){
                                $rtnArr['unInomeMoney6'] +=  $val['money'] - $val['incomMoney'];
                            }else if(strtotime($t_6) > strtotime($val['Tday']) && strtotime($val['Tday']) > strtotime($t_12) && ($val['money'] - $val['incomMoney']) > 0){
                                $rtnArr['unInomeMoney12'] +=  $val['money'] - $val['incomMoney'];
                            }else if(strtotime($t_12) > strtotime($val['Tday']) && ($val['money'] - $val['incomMoney']) > 0){
                                $rtnArr['unInomeMoney24'] +=  $val['money'] - $val['incomMoney'];
                            }

                        }else{
                            $rtnArr['noTMoney'] +=  $val['money'] - $val['incomMoney'];
                        }
                    }
                }else{
                    $rtnArr['noTMoney'] +=  $conArr['contractMoney'];
                }
            }
        }
        return $rtnArr;
	}

	/**
	 * 根据合同id 获取合同决算
	 */
	function getConFeeByid($cid){
		$sql = "SELECT costMoney FROM oa_finance_cost WHERE isDel = 0 AND isTemp = 0 AND contractId = '".$cid."'";
		$rtn = $this->_db->getArray($sql);
		return $rtn[0]['costMoney'];
	}

	/**
	 * 审批流回调方法
	 */
	function workflowCallBack($spid){
        $otherdatas = new model_common_otherdatas();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo['objId'];
        $this->dealAfterAudit_d($objId);
        $this->confirmContract_d($spid);
	}

	/**
	 * 审批流回调方法_变更
	 */
	function workflowCallBack_change($spid){
       $this->confirmChange($spid);
	}
	/**
	 * 审批流回调方法_关闭
	 */
	function workflowCallBack_close($spid){
       $this->confirmClose_d($spid);
	}

    /**
     * 更新合同表冗余值
     */
    function updateConRedundancy_d(){

        //更新项目信息
        $proDao = new model_contract_conproject_conproject();
        $proDao->createProjectBySale_d(11708);

//        $this->searchArr['isTemp'] = "0";
//        $this->searchArr['states'] = "2,3,4";
//        $rows = $this->list_d();
//        //项目
//        $proDao = new model_contract_conproject_conproject();
//       foreach($rows as $k=>$v){
//           $comPoint = $this->getTxaRate($v);
//           $conProgress =$proDao->getConduleBycid($v['id'])/100;
//           $invoiceProgress = round($v['invoiceMoney']/($v['contractMoney']-$v['deductMoney']-$v['badMoney']-$v['uninvoiceMoney']),2);
//           $incomeProgress =  round($v['incomeMoney']/($v['contractMoney']-$v['deductMoney']-$v['badMoney']),2);
//           $updateSql = "update oa_contract_contract set comPoint='".$comPoint."',conProgress='".$conProgress."',invoiceProgress='".$invoiceProgress."',incomeProgress='".$incomeProgress."' where id='".$v[id]."'";
//           $this->query($updateSql);
//       }
    }

    //获取合同综合税率
    function getTxaRateNew($invoicTypeArr,$invoiceCode,$invoiceValue)
    {
        $conArr['invoiceCode'] = $invoiceCode;
        $conArr['invoiceValue'] = $invoiceValue;
        $conInvoiceArr = $this->makeInvoiceValueArr($conArr);

        $backArr = array("isNoInvoiceCont" => false,"cRate" => 0);
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// 开票类型含“不开票”的合同直接返回默认税点
                $backArr['isNoInvoiceCont'] = true;
            }else{
                //如果是租赁类的合同，计算租赁成本
                //开票金额计算
                $rate = 0;
                $typeMoney = 0;
                $cMoney = 0; //取实际开票金额做计算，而不是直接取合同额
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $v = sprintf("%.2f", $v);
                        $invoicTypeArr[$k] = sprintf("%.2f", $invoicTypeArr[$k]);
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                        $cMoney += $v;
                    }
                }

                //如果不是混合类开票，则直接返回该开票税点
                if (count($conInvoiceArr) > 1) {
                    $typeMoney = sprintf("%.3f", $typeMoney);
                    $cMoney = sprintf("%.3f", $cMoney);
                    $rate = round(bcsub(bcdiv($cMoney, $typeMoney, 8), 1, 8), 4);
                }

                $backArr['cRate'] = $rate;
            }
        }
        return $backArr;
    }

    //获取合同综合税率
    function getTxaRate($conArr)
    {
        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        foreach ($typeArr as $key => $val) {
            foreach ($val as $k => $v) {
                $valArrs[] = $v['expand1'];
                $invoicTypeArr[$v['dataCode']] = $v['expand1'];
            }
        }

        $conInvoiceArr = $this->makeInvoiceValueArr($conArr);

        $rate = 0; //综合税点
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// 开票类型含“不开票”的合同直接返回默认税点
                $rate = 0;// 暂时默认为0,如需要修改再做调整
            }else{
                //如果是租赁类的合同，计算租赁成本
                //开票金额计算
                $typeMoney = 0;
                $cMoney = 0; //取实际开票金额做计算，而不是直接取合同额
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                        $cMoney += $v;
                    }
                }

                //如果不是混合类开票，则直接返回该开票税点
                if (count($conInvoiceArr) > 1) {
                    $typeMoney = sprintf("%.3f", $typeMoney);
                    $rate = round(bcsub(bcdiv($cMoney, $typeMoney, 8), 1, 8), 4);
                }
            }
        }
        return $rate;
    }

    /**
     * 合同自检情况
     */
    function objCom_d($obj){
         //产品金额
         $pMoneySql = "select sum(money) as pMoney from oa_contract_product where isDel = 0 and contractId='".$obj['id']."'";
         $pMoneyArr = $this->_db->getArray($pMoneySql);
         if($obj['contractMoney'] == $pMoneyArr[0]['pMoney']){
             $rtn['pmoneyCheck'] = "<img src='images/icon/dui.png'/>";
         }else{
             $rtn['pmoneyCheck'] = "<img src='images/icon/cuo.png'/>";
         }

        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $cMoney = 0;
        foreach ($invoiceValueArr as $k => $v) {
            if (!empty($v)) {
                $cMoney += $v;
            } else {
                unset($invoiceValueArr[$k]);
            }
        }
        $cMoney = sprintf("%.3f", $cMoney);
        $contractMoney = sprintf("%.3f", $obj['contractMoney']);
        if(bcsub($contractMoney,$cMoney,3) <= 0.1){
            $rtn['invoiceCheck'] = "<img src='images/icon/dui.png'/>";
        }else{
            $rtn['invoiceCheck'] = "<img src='images/icon/cuo.png'/>";
        }
        if($obj['state'] == '4' || $obj['state'] == '3'){

            $compareMoney = $obj['contractMoney'] - $obj['deductMoney'] - $obj['uninvoiceMoney'];
            if(intVal($obj['invoiceMoney']) >= intVal($compareMoney)){
                $rtn['invoiceCheck_t'] = "<img src='images/icon/dui.png'/>";
            }else{
                $rtn['invoiceCheck_t'] = "<img src='images/icon/cuo.png'/>";
            }
        }else{
            $rtn['invoiceCheck_t'] = "<img src='images/icon/heng.png'/>";
        }

        $esmProject = new model_engineering_project_esmproject();
        $esmProject->getParam(array("contractId" => $obj['id'])); //设置前台获取的参数信息
        $rows = $esmProject->pageBySqlId('select_defaultAndFee');
        $allProjectFinish = true;
        foreach ($rows as $key => $project){
            if ($allProjectFinish && empty($project['projectProcess']) || $project['projectProcess'] < 100){
                $allProjectFinish = false;
            }
        }
        $rtn['invoiceCheck_t'] = ($allProjectFinish)? $rtn['invoiceCheck_t'] : "<img src='images/icon/heng.png'/>";

        return $rtn;
    }

    /**
     * 更新合同信息检查项字段
     * @param string $cid
     * @param string $from
     * @return bool
     */
    function updateContractObjComList_d($cid = '',$from = ''){
        set_time_limit(0);
        ini_set("memory_limit","1024M");

        $searchArr['isTemp'] = 0;
        if($cid != '' && $from == 'contractUpdate'){
            $searchArr['id'] = $cid;
        }

        $this->getParam($searchArr);
        $rows = $this->listBySqlId('select_gridinfo');
        $processNum = 0;

        if($rows){
            foreach ($rows as $row){
                $processNum += 1;
                $updateArr = array();
                $checkArr = $this->objComList_d($row);
                $updateArr['productCheck'] = $checkArr['productCheck'];
                $updateArr['projectCheck'] = $checkArr['projectCheck'];
                $updateArr['invoiceCheck'] = $checkArr['invoiceCheck'];
                $updateArr['invoiceTrueCheck'] = $checkArr['invoiceTrueCheck'];
                $updateArr['id'] = $row['id'];
                $this->updateById($updateArr);
            }
        }

        if($from != 'contractUpdate'){// 手动更新的不记录,只记录系统预警更新的
            // 日志写入
            $now = time();
            $thisMonth = date('n', $now);// 当前月
            $logDao = new model_engineering_baseinfo_esmlog();
            $logDao->addLog_d(-1, '更新合同检查项数据', $processNum . '|' . $thisMonth);
        }else{
            return true;
        }
    }

    /**
     * 合同自检情况 - 列表
     */
    function objComList_d($obj){
        //产品金额
        $pMoneySql = "select sum(money) as pMoney from oa_contract_product where contractId='".$obj['id']."'";
        $pMoneyArr = $this->_db->getArray($pMoneySql);
        if($obj['contractMoney'] == $pMoneyArr[0]['pMoney']){
            $rtn['productCheck'] = "对";
        }else{
            $rtn['productCheck'] = "错";
        }

        $invoiceValueArr = explode(",", $obj['invoiceValue']);
        $cMoney = 0;
        foreach ($invoiceValueArr as $k => $v) {
            if (!empty($v)) {
                $cMoney += $v;
            } else {
                unset($invoiceValueArr[$k]);
            }
        }
        if($obj['contractMoney'] == $cMoney){
            $rtn['invoiceCheck'] = "对";
        }else{
            $rtn['invoiceCheck'] = "错";
        }
        if($obj['state'] == '4' || $obj['state'] == '3'){

            $compareMoney = $obj['contractMoney'] - $obj['deductMoney'] - $obj['uninvoiceMoney'];
            if(intVal($obj['invoiceMoney']) == intVal($compareMoney)){
                $rtn['invoiceTrueCheck'] = "对";
            }else{
                $rtn['invoiceTrueCheck'] = "错";
            }
        }else{
            $rtn['invoiceTrueCheck'] = "-";
        }

        $esmDao = new model_engineering_project_esmproject();
        $comDao = new model_contract_conproject_conproject();
        $esmDao->searchArr['contractCode'] = $obj['contractCode'];
        $esmArr = $esmDao->pageBySqlId('select_defaultAndFee');
        $esmMoney = 0;
        foreach($esmArr as $ka => $va){
            $pType = isset($va['pType']) ? $va['pType'] : 'esm';
            // 只有合同项目才加入这些处理
            if ($va['contractType'] == 'GCXMYD-01' && $pType == 'esm') {
                $bb = $esmDao->contractDeal($va);
                $esmMoney += $bb['projectMoneyWithTax'];
            }else{//产品类 预留 ，目前默认加0
                $esmMoney += $comDao->getAccMoneyBycid($va['contractId'], $va['newProLine'], 11);//项目金额
            }
        }
        if($obj['state']=='2' || $obj['state']=='4' || $obj['state']=='3'){
            if($obj['contractMoney'] == $esmMoney){
                $rtn['projectCheck'] = "对";
            }else{
                $rtn['projectCheck'] = "错";
            }
        }else{
            $rtn['projectCheck'] = "-";
        }

        // 判断关联合同是否存在不开票的开票类型,
        $invoiceCodeArr = explode(",",$obj['invoiceCode']);
        foreach ($invoiceCodeArr as $Arrk => $Arrv){
            if($Arrv == "HTBKP"){
                $rtn['invoiceTrueCheck'] = "-";
                $rtn['invoiceCheck'] = "-";
            }
        }

        return $rtn;
    }

    /**
     * 更新项目状态
     * @param $id
     * @return string
     */
    function updateProjectStatus_d($id) {
        $projectDao = new model_engineering_project_esmproject();

        $obj = $this->get_d($id);
        //更新合同的立项状态
        $projectMoneyAll = 0;
        $projectList = $projectDao->getProjectListByContractCode_d($obj['contractCode']);
        foreach ($projectList as $v) {
            $projectMoneyAll = bcadd($projectMoneyAll, $v['projectMoneyWithTax'], 2);
        }

        try {
            $newObj = array(
                'id' => $id
            );
            $newObj['projectStatus'] = $projectMoneyAll == $obj['contractMoney'] ? 1 : 0;
            parent::edit_d($newObj);
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return 'ok';
    }

    /**
     * 更新合同项目数据的冗余值
     *
     * @param string $cid
     * @param string $from
     * @param string $contractStates 合同的更新范围，默认为非“已关闭”及“异常关闭”
     * @return bool
     */
    function updateSalesContractVal_d($cid = '',$from = '',$contractStates = '0,1,2,4'){
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $dataDao = new model_system_datadict_datadict();
        $typeArr = $dataDao->getDatadictsByParentCodes("KPLX");
        $typeArr = isset($typeArr['KPLX'])? $typeArr['KPLX'] : array();
        foreach ($typeArr as $k => $v) {
            $valArrs[] = $v['expand1'];
            $invoicTypeArr[$v['dataCode']] = $v['expand1'];
        }

        // PMS 522 合同应收款特殊规则配置处理
        $SpecRecordsForNoSurincomeArr = $this->dealSpecRecordsForNoSurincome();

        $esmprojectDao = new model_engineering_project_esmproject(); // 工程项目
        if($from == 'contractUpdate'){
            if($cid != '' && !is_array($cid)){
                $sql = "select id,contractCode,customerType,state,contractMoney,invoiceCode,invoiceValue,incomeMoney,deductMoney,invoiceMoney,uninvoiceMoney,badMoney,DeliveryStatus from oa_contract_contract where isTemp = 0 and id = {$cid}";
            }else{
                $sql = ($contractStates == '')?
                    "select id,contractCode,customerType,state,contractMoney,invoiceCode,invoiceValue,incomeMoney,deductMoney,invoiceMoney,uninvoiceMoney,badMoney,DeliveryStatus from oa_contract_contract where isTemp = 0" :
                    "select id,contractCode,customerType,state,contractMoney,invoiceCode,invoiceValue,incomeMoney,deductMoney,invoiceMoney,uninvoiceMoney,badMoney,DeliveryStatus from oa_contract_contract where isTemp = 0 and `state` in ({$contractStates})";
            }
        }else{
            $sql = ($contractStates == '')?
                "select id,contractCode,customerType,state,contractMoney,invoiceCode,invoiceValue,incomeMoney,deductMoney,invoiceMoney,uninvoiceMoney,badMoney,DeliveryStatus from oa_contract_contract where isTemp = 0" :
                "select id,contractCode,customerType,state,contractMoney,invoiceCode,invoiceValue,incomeMoney,deductMoney,invoiceMoney,uninvoiceMoney,badMoney,DeliveryStatus from oa_contract_contract where isTemp = 0 and `state` in ({$contractStates})";
        }

        $rows = $this->_db->getArray($sql);
        foreach ($rows as $k => $v) {
            // 根据合同扩展字段表重新读取开票类型以及对应字段信息
            $invoiceValInfo = "";
//            $extInfo = $this->getContractExtFields($v['id']);
//            if(!empty($extInfo)){
//                $invoiceValuesStr = $invoiceCodeStr = "";
//                $invoiceValuesArr = isset($extInfo['invoiceValues'])? util_jsonUtil::decode($extInfo['invoiceValues']) : array();
//                if($invoiceValuesArr && !empty($invoiceValuesArr)){
//                    foreach ($typeArr as $invk => $invv) {
//                        if(isset($invoiceValuesArr[$invv['dataCode']])){
//                            $invoiceValuesStr .= "{$invoiceValuesArr[$invv['dataCode']]},";
//                            $invoiceCodeStr .= "{$invv['dataCode']},";
//                        }else if($invk > 0){
//                            $invoiceValuesStr .= ",";
//                        }
//                    }
//                    $invoiceValuesStr = rtrim($invoiceValuesStr,",");
//                    $invoiceCodeStr = rtrim($invoiceCodeStr,",");
//                    if($invoiceValuesStr != "" && $invoiceCodeStr != ""){
//                        $v['invoiceCode'] = $invoiceCodeStr;
//                        $v['invoiceValue'] = $invoiceValuesStr;
//                        $invoiceValInfo = ",invoiceCode = '{$invoiceCodeStr}',invoiceValue = '{$invoiceValuesStr}'";
//                    }
//                }
//            }

            $cRateArr = $this->getTxaRateNew($invoicTypeArr,$v['invoiceCode'],$v['invoiceValue']);
            $cRate = $cRateArr['cRate'];
            $isNoInvoiceCont = $cRateArr['isNoInvoiceCont'];

            $budgetAll = $curIncome = $feeAll = $conProgress = $rateOfGross = $comPoint = $gross = $icomeMoney = $incomeProgress = $invoiceProgress = 0;
            $proTmp = $esmprojectDao->getProByCidForUpload($v['id'],$v);

            $allProjMoneyWithSchl = sprintf("%.3f", $proTmp['allProjMoneyWithSchl']);
            $conProgress = round(bcmul(bcdiv($allProjMoneyWithSchl,$v['contractMoney'],9),100,4),2);
            $conProgressT = bcmul(bcdiv($allProjMoneyWithSchl,$v['contractMoney'],9),100,9);

            $feeAll = $proTmp['feeAll'];
            $budgetAll = $proTmp['budgetAll'];
            $curIncome = $proTmp['curIncome'];
            $gross = round($curIncome - $feeAll,2);
            $rateOfGross = round(($curIncome - $feeAll)/$curIncome*100,2);
            $comPoint = sprintf("%.4f", $cRate);
            $icomeMoney = $v['contractMoney'] * $conProgressT / 100 - $v['incomeMoney'] - $v['deductMoney'] - $v['badMoney'];
            $incomeProgress = round($v['incomeMoney']/($v['contractMoney'] - $v['deductMoney']-$v['badMoney']),4);
            $invoiceProgress = ($isNoInvoiceCont)? 1 : round($v['invoiceMoney']/($v['contractMoney'] - $v['deductMoney']-$v['uninvoiceMoney']),4);

            // PMS 522 对于合同无关联项目的，无关联项目时，进度取开票和收款进度的最小值。
            if(!$proTmp['hasProject']){
                $conProgress = ($incomeProgress < $invoiceProgress)? $incomeProgress : $invoiceProgress;
                $conProgress = round(bcmul($conProgress,100,4),2);
            }

            if(!$proTmp['hasProject'] || in_array($v['customerType'],$SpecRecordsForNoSurincomeArr) || $v['state']== 7){
                $icomeMoney = 0;
            }

            $updateSql = "update oa_contract_contract set 
                                proj_budgetAll = '{$budgetAll}',
                                proj_curIncome = '{$curIncome}',
                                proj_feeAll = '{$feeAll}',
                                proj_conProgress = '{$conProgress}',
                                proj_gross = '{$gross}',
                                proj_rateOfGross = '{$rateOfGross}',
                                proj_comPoint = '{$comPoint}',
                                proj_icomeMoney = '{$icomeMoney}',
                                proj_incomeProgress = '{$incomeProgress}',
                                proj_invoiceProgress = '{$invoiceProgress}' {$invoiceValInfo}
                        where id = {$v['id']};";
            $this->_db->query($updateSql);
        }
        // 日志写入
        if($from != 'contractUpdate'){
            $now = time();
            $thisMonth = date('n', $now);// 当前月
            $logDao = new model_engineering_baseinfo_esmlog();
            $logDao->addLog_d(-1, '更新合同项目数据的冗余值', count($rows) . '|' . $thisMonth);
        }else{
            return true;
        }
    }

    /**
     * 合同应收款特殊规则配置处理
     * @param string $to
     * @param array $rows
     * @param array $dealFields
     * @return array
     */
    function dealSpecRecordsForNoSurincome($to = "getConfigs",$rows = array(),$dealFields = array()){
        // 获取特殊配置项参数
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('YSKPZ');

        // 如有相关配置, 做相应处理
        if($matchConfigItem){
            $filterCustomerType = $matchConfigItem[0]['config_itemSub1'];
            $filterCustomerTypeArr = explode(",",$filterCustomerType);

            switch($to){
                case 'rowsMatch':
                    foreach ($rows as $k => $v){
                        if(in_array($v['customerType'],$filterCustomerTypeArr) || $v['state'] == 7){
                            foreach ($dealFields as $fv){
                                $rows[$k][$fv] = 0;
                            }
                        }
                    }
                    return $rows;
                    break;
                case 'getConfigs':
                    return $filterCustomerTypeArr;
                    break;
            }
        }else{
            return $rows;
        }
    }

    /**
     * 将合同记录中的开票信息记录转成数组对应的格式
     * @param $object
     * @return array
     */
    function makeInvoiceValueArr($object){
        $invoiceCode = isset($object['invoiceCode']) ? explode(",", $object['invoiceCode']) : '';
        $invoiceValue = isset($object['invoiceValue']) ? explode(",", $object['invoiceValue']) : '';
        $invoiceData = array();
        if(!empty($invoiceCode) && !empty($invoiceValue)){
            $i = 0;
            if(in_array('HTBKP',$invoiceCode)){
                $invoiceData['HTBKP'] = "";
                $i = 1;
            }
            foreach ($invoiceValue as $k => $v){
                if($v !== '' && ($v >= 0 || $v <= 0)){
                    $invoiceData[$invoiceCode[$i]] = $v;
                    $i++;
                }
            }
        }
        return $invoiceData;
    }

    /**
     * 添加开票信息记录
     * @param $cid
     * @param $invoiceJsonData
     * @return bool|mixed|string
     */
    function invoiceTypeRecord($cid,$invoiceJsonData){
        $recordData = $this->_db->get_one("select id from oa_contract_extfields_data where contractId = '{$cid}';");
        $data = array(
            "contractId" => $cid,
            "invoiceValues" => $invoiceJsonData
        );
        $data = $this->addUpdateInfo($data);
        $this->tbl_name = "oa_contract_extfields_data";
        $result = false;
        if($recordData && $recordData['id'] > 0){
            $data['id'] = $recordData['id'];
            $result = $this->updateById($data);
        }else{
            $result = $this->add_d($data);
        }
        $this->tbl_name = "oa_contract_contract";
        return $result;
    }

    /**
     * 获取合同当前可修改的最小合同额
     * @param $cid
     * @return mixed
     */
    function getValidContractMoney($cid){
        $chkSql = <<<EOT
        select c.invoiceMoney,c.uninvoiceMoney,i.applyingInvoice,c.deductMoney from oa_contract_contract c 
        left join (
            select objId,sum(invoiceMoney-payedAmount) as applyingInvoice from oa_finance_invoiceapply where 
            objType = 'KPRK-12' and objId = '$cid' and exaStatus <> '完成'
        )i on i.objId = c.id
        where c.id = '$cid';
EOT;
        $result = $this->_db->get_one($chkSql);
        return $result;
    }

    /**
     * 获取合同额外的字段信息
     * @param string $cid
     * @return array
     */
    function getContractExtFields($cid = ''){
        $extData = array();
        if($cid != ''){
            $extData = $this->_db->get_one("select * from oa_contract_extfields_data where contractId = '{$cid}';");
        }
        return $extData;
    }
}