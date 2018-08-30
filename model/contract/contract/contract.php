<?php

//2012-12-27����

/**
 * @author Liub
 * @Date 2012��3��8�� 10:30:28
 * @version 1.0
 * @description:��ͬ���� Model��
 */

class model_contract_contract_contract extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_contract_contract";
        $this->sql_map = "contract/contract/contractSql.php";
        parent :: __construct();
    }

    //���ݲ�ͬ�Ĳ���,������Ӧ���ʼ�������
    public $productLineToMailArr = array(
        'HTCPX-YQYB' => 'quanzhou.luo',//�����Ǳ����񲿣���Ȩ��(ֻ�޺��з����Ʒ)
        'HTCPX-CTFW' => 'dongsheng.wang',//��ͳ����������
        'HTCPX-DSJ' => 'jianping.luo',//�����ݲ����޽�ƽ
        'HTCPX-XYWSYB' => '',//��ҵ����ҵ��
        'HTCPX-CWJS' => '',//�������
        'HTCPX-ZXJS' => ''//�������
    );

    /**-----------------------------ҳ��ģ����ʾ����-------------------------------------**/
    /**
     * @authorhuangzf
     * ��Ʒ���ʱ���嵥��ʾģ��
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
			                        <img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
     * ��ɫ���۳��ⵥ����������Ϣ
     * @param  $rows
     */
    function showProItemAtCkSales($rows)
    {
        if ($rows['equ']) {
            $productinfoDao = new model_stock_productinfo_productinfo();
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
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
						<img align="absmiddle" src="images/removeline.png" onclick="delItem(this);" title="ɾ����" />
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
						 <img src="images/add_snum.png" align="absmiddle" onclick="chooseSerialNo('$i');" title="ѡ�����к�">
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
     * ��ȡ��Ʒ�����������
     */
    function getDeptIds($object)
    {
        //���Ҵ��� ��ѡ��Ʒ�� �������
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
     * ��ȡ��Ʒ����������ţ�����ã�
     */
    function getDeptIdsByChange($result)
    {
        //���Ҵ��� ��ѡ��Ʒ�� �������
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
     * ��ͬ�����֤����
     * @param $object
     * @return string
     */
    function checkContractMoney_d($object) {
        if (!isset($object['contractMoney']) || !isset($object['product'])) {
            return "�����������";
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
            return "��ͬ�������ϸ��ƥ�䣬�����������ύ��";
        }
    }

    /**
     * ��дadd_d����
     */
    function add_d($object, $confirm, $invoiceJsonData = '')
    {
        $object['invoiceCode'] = isset($object['invoiceCode']) ? implode(",", $object['invoiceCode']) : '';
        $object['invoiceValue'] = isset($object['invoiceValue']) ? implode(",", $object['invoiceValue']) : '';
        try {
            $this->start_d();

            //��ȡ������չ�ֶ�ֵ
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($object['areaCode']);

            //����������
            $object = $this->handlePaymentData($object);

            //���������ֵ��ֶ�
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
                //��ͬ���
                $codeRule = new model_common_codeRule();
                $object['contractCode'] = $codeRule->newContractCode($object);
            }
            //ҵ����
            if (!empty ($object['prinvipalId']) && !empty($object['contractType'])) {
                $prinvipalId = $object['prinvipalId'];
                $orderCodeDao = new model_common_codeRule();
                $deptDao = new model_deptuser_dept_dept();
                $dept = $deptDao->getDeptByUserId($prinvipalId);
                $object['objCode'] = $orderCodeDao->getObjCode($object['contractType'], $dept['Code']);
            }
            //���Ҵ��� ��ѡ��Ʒ�� �������
            $goodsTypeIds = "";
            $exeDeptNameStr = "";
            $newProLineStr = "";
            $newExeDeptStr = "";
            $xfProLineStr = ""; // ��������ͬһ���߲�ͬ��Ʒ���͵Ĳ�Ʒ���࣬��ĳĳ����������(x)/������(f)��Ʒ
            if (isset($object['product'])) {
                foreach ($object['product'] as $k => $v) {
                    if ($v['isDelTag'] != "1") {
                        $goodsTypeIds .= $v['conProductId'] . ",";
                        $exeDeptNameStr .= $v['exeDeptId'] . ",";
                        $newProLineStr .= $v['newProLineCode'] . ",";
                        if ($v['newProLineCode'] == 'HTCPX-CTFW') { // ��Ʒ��Ϊ��ͳ����Ĳ�Ʒ������ִ�������������
                            $newExeDeptStr .= $v['exeDeptId'] . ",";
                        }
                        if ($v['proTypeId'] == '11') { // �������Ʒ
                            $xfProLineStr .= $v['newProLineCode'] . "x,";
                        } else { // �������Ʒ
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
                //��һ�β��ң����˳������Ѿ��������������
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
                //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $object['goodsTypeStr'] = $goodsTypeStr;
            //��Ʒ��
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
            //�ж��Ƿ���Ҫ�ߵ�ִ�в�����������ʾ�ֶ�
            $isSellArr = explode(",", $goodsTypeStr);
            //����
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
                $object['isSaleConfirm'] = "1";
            } else {
                $object['isSell'] = "0";
            }
            //����
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
            //�з�
            if (in_array(RDPRODUCTTYPE, $isSellArr) && $expand != '1') {
                $object['isRdproConfirm'] = "1";
            }
            //�ж��Ƿ��ύ
            if ($confirm == "app" && $_SESSION['DEPT_ID'] != hwDeptId) { // �����ͬ���ύ״̬���������ļ��ﴦ��
                $object['isSubApp'] = "1";
            }
            if (!empty ($object['contractCode'])) {
                //����������Ϣ
                $newId = parent :: add_d($object, true);

                if($newId && $invoiceJsonData != ''){
                    $this->invoiceTypeRecord($newId,$invoiceJsonData);
                }

                if (!empty ($object['chanceId'])) {
                    //���̻��Ĺ�ͨ����Ϣ�����ͬ��
                    $sql = "select createName,createId,createTime,content from oa_chance_remark where chanceId = '" . $object['chanceId'] . "'";
                    $arr = $this->_db->getArray($sql);
                    if (!empty($arr)) {
                        $remarkDao = new model_contract_contract_remark();
                        $remarkDao->createBatch($arr, array(
                            'contractId' => $newId
                        ));
                    }
                }

                //����ӱ���Ϣ
                //�ͻ���ϵ��
                if (!empty ($object['linkman'])) {
                    $linkmanDao = new model_contract_contract_linkman();
                    $linkmanDao->createBatch($object['linkman'], array(
                        'contractId' => $newId
                    ), 'linkmanName');
                }
                //��ͬ�տ��ƻ�
                if (!empty ($object['financialplan'])) {
                    $financialplanDao = new model_contract_contract_financialplan();
                    $financialplanDao->createBatch($object['financialplan'], array(
                        'contractId' => $newId
                    ), 'planDate');
                }
                //�豸
                if (!empty ($object['product'])) {
                    $product = $object['product'];
                    $orderequDao = new model_contract_contract_product();
                    $orderequDao->createBatch($object['product'], array(
                        'contractId' => $newId,
                        'contractCode' => $object['contractCode']
                    ), 'conProductName');
                }
                //����
                $equDao = new model_contract_contract_equ();
                $equTaxRate = $equDao->_defaultTaxRate;
                if (!empty($object['equ'])) {
                    foreach ($object['equ'] as &$val) { // ��17%˰�ʣ�����˰����
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


                //����ȷ�ϲ���
                $linkDao = new model_contract_contract_contequlink();
                if (!empty($object['material'])) {
                    foreach ($object['material'] as &$val) { // ��17%˰�ʣ�����˰����
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
                    //���������
                    $linkArr = array(
                        "contractId" => $newId,
                        "contractCode" => $object['contractCode'],
                        "contractName" => $object['contractName'],
                        "contractType" => 'oa_contract_contract',
                        "contractTypeName" => $object['contractTypeName'],
                        "ExaStatus" => 'δ�ύ'
                    );
                    $linkId = $linkDao->add_d($linkArr, true);
                }
                //�տ�ƻ�
                $orderReceiptplanDao = new model_contract_contract_receiptplan();
                if (!empty ($object['payment'])) {
                    $orderReceiptplanDao->createBatch($object['payment'], array(
                        'contractId' => $newId
                    ), 'paymentterm');
                }
                //���������ƺ�Id
                $this->updateObjWithFile($newId);
            }
            $this->commit_d();
            $handleDao = new model_contract_contract_handle();
            //									$this->rollBack();
            //�ж��Ƿ���Ҫ�����̲�ȷ�� �ɱ����� in_array(ESMPRODUCTTYPE, $isSellArr) &&
            if ($confirm == "app") {
                $handleDao->handleAdd_d(array(
                    "cid" => $newId,
                    "stepName" => "��ͬ¼��",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                //�ж��Ƿ�Ϊ���ⲿ���ύ�ĺ�ͬ
                if ($_SESSION['DEPT_ID'] == hwDeptId) {
                    return $newId;
                }
                $handleDao->handleAdd_d(array(
                    "cid" => $newId,
                    "stepName" => "�ύ�ɱ�ȷ��",
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
                    "stepName" => "��ͬ¼��",
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
     * ���ݲ�Ʒ�߻�ȡ ��Ʒ�߳ɱ�ȷ����
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
     * ��д�༭����
     */
    function edit_d($object, $confirm)
    {
        $object['invoiceCode'] = isset($object['invoiceCode']) ? implode(",", $object['invoiceCode']) : '';
        $object['invoiceValue'] = isset($object['invoiceValue']) ? implode(",", $object['invoiceValue']) : '';
        try {
            $this->start_d();

            $conId = $object['id'];// ��ͬID

            //��ȡ������չ�ֶ�ֵ
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($object['areaCode']);

            //����������
            $object = $this->handlePaymentData($object);
            $oldArr = $this->get_d($object['id']);

            //����dealStatusΪ0���Է�ֹdealStatus��Ϊ0��������糷��������
            if ($oldArr['dealStatus'] != '0') {
                $object['dealStatus'] = '0';
            }

            //���������ֵ��ֶ�
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
            //2017-2-4 PMS 2424  һ����ͬ������ɣ����ۺ�ͬ�Ƿ���Ч���ٴα༭/���Ӱ���ŵ����ݣ���ͬ��Ŷ�����
//            if (ORDER_INPUT == '1' && $object['customerId'] != '1058') { //�ų�������ĺ�ͬ������ĺ�ͬ�޸Ĳ������µĺ�ͬ���
//                //��ͬ���
//                $codeRule = new model_common_codeRule();
//                $object['contractCode'] = $codeRule->newContractCode($object);
//            }
            //ҵ����
            if (empty($oldArr['objCode']) && !empty ($object['prinvipalId']) && !empty($object['contractType'])) {
                $prinvipalId = $object['prinvipalId'];
                $orderCodeDao = new model_common_codeRule();
                $deptDao = new model_deptuser_dept_dept();
                $dept = $deptDao->getDeptByUserId($prinvipalId);
                $object['objCode'] = $orderCodeDao->getObjCode($object['contractType'], $dept['Code']);
            }
            //���Ҵ��� ��ѡ��Ʒ�� �������
            $goodsTypeIds = "";
            $exeDeptNameStr = "";
            $newProLineStr = "";
            $newExeDeptStr = "";
            $xfProLineStr = ""; // ��������ͬһ���߲�ͬ��Ʒ���͵Ĳ�Ʒ���࣬��ĳĳ����������(x)/������(f)��Ʒ
            if (isset($object['product'])) {
                foreach ($object['product'] as $k => $v) {
                    if ($v['isDelTag'] != "1") {
                        $goodsTypeIds .= $v['conProductId'] . ",";
                        $goodsIdArr[] = $v['id'];
                        $exeDeptNameStr .= $v['exeDeptId'] . ",";
                        $newProLineStr .= $v['newProLineCode'] . ",";
                        if ($v['newProLineCode'] == 'HTCPX-CTFW') { // ��Ʒ��Ϊ��ͳ����Ĳ�Ʒ������ִ�������������
                            $newExeDeptStr .= $v['exeDeptId'] . ",";
                        }
                        if ($v['proTypeId'] == '11') { // �������Ʒ
                            $xfProLineStr .= $v['newProLineCode'] . "x,";
                        } else { // �������Ʒ
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
                //��һ�β��ң����˳������Ѿ��������������
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
                //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $object['goodsTypeStr'] = $goodsTypeStr;
            //��Ʒ��
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
            //�ж��Ƿ���Ҫ�ߵ�ִ�в�����������ʾ�ֶ�
            $isSellArr = explode(",", $goodsTypeStr);
            //����
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
                $object['isSaleConfirm'] = "1";
            } else {
                $object['isSell'] = "0";
                $object['isSaleConfirm'] = "0";
            }
            //����
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
            //�з�
            if (in_array(RDPRODUCTTYPE, $isSellArr)) {
                $object['isRdproConfirm'] = "1";
            } else {
                $object['isRdproConfirm'] = "0";
            }
            //�ж��Ƿ��ύ
            if ($confirm == "app" && $_SESSION['DEPT_ID'] != hwDeptId) { // �����ͬ���ύ״̬���������ļ��ﴦ��
                $object['isSubApp'] = "1";
            }

            //�޸�������Ϣ
            parent :: edit_d($object, true);
            $orderId = $object['id'];

            //����ӱ���Ϣ
            //�ͻ���ϵ��
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
            //�豸
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
            //�տ��ƻ�
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
            //��Ժ����ͬ�༭��������ĺ�ͬ�༭������������Ϣ����ʱ����
            $conArr = $this->get_d($object['id']);
            $equDao = new model_contract_contract_equ();
            $equTaxRate = $equDao->_defaultTaxRate;
            if (empty($conArr['parentName'])) {
                if (!empty ($object['equ']) || !empty ($object['material'])) {
                    $equDao->delete(array(
                        'contractId' => $orderId
                    ));
                }
                //������ת��������
                if (!empty ($object['equ'])) {
                    foreach ($object['equ'] as $k => $v) {
                        if ($v['isDelTag'] == '1') {
                            unset ($object['equ'][$k]);
                        } else { // ��17%˰�ʣ�����˰����
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
                //����ȷ��
                //���Ϲ�������������ڹ�����¼����Ҫɾ������Ȼ�������������ύ�������޷�����ȷ�����ϲ���
                $linkDao = new model_contract_contract_contequlink();
                $linkDao->delete(array(
                    'contractId' => $orderId
                ));
                if (!empty ($object['material'])) {
                    foreach ($object['material'] as &$val) { // ��17%˰�ʣ�����˰����
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
                    //���������
                    $linkArr = array(
                        "contractId" => $orderId,
                        "contractCode" => $object['contractCode'],
                        "contractName" => $object['contractName'],
                        "contractType" => 'oa_contract_contract',
                        "contractTypeName" => $object['contractTypeName'],
                        "ExaStatus" => 'δ�ύ'
                    );
                    $linkId = $linkDao->add_d($linkArr, true);
                }
            }
            if (!empty($object['payment'])) {
                //�տ�ƻ�
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
            //����ɾ����Ʒ�������Ĺ�������
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
            //�ж��Ƿ���Ҫ�����̲�ȷ�� �ɱ����� in_array(ESMPRODUCTTYPE, $isSellArr) &&
            if ($confirm == "app") {
                //ɾ���ɱ���¼
                $constSql = "delete from oa_contract_cost where contractId = '" . $orderId . "'";
                $this->_db->query($constSql);
                $handleDao->handleAdd_d(array(
                    "cid" => $orderId,
                    "stepName" => "��ͬ�༭",
                    "isChange" => 0,
                    "stepInfo" => "",
                ));
                //�ж��Ƿ�Ϊ���ⲿ���ύ�ĺ�ͬ
                if ($_SESSION['DEPT_ID'] == hwDeptId) {
                    return $orderId;
                }
                $handleDao->handleAdd_d(array(
                    "cid" => $orderId,
                    "stepName" => "�ύ�ɱ�ȷ��",
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
                    "stepName" => "��ͬ�༭",
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
     * ��д�༭����
     * ���ṩ����ʹ��
     */
    function hwedit_d($object)
    {
        $object['invoiceCode'] = implode(",", $object['invoiceCode']);
        $object['invoiceValue'] = implode(",", $object['invoiceValue']);
        try {
            $this->start_d();
            //����������
            $object = $this->handlePaymentData($object);
            $oldArr = $this->get_d($object['id']);
            //���������ֵ��ֶ�
            $datadictDao = new model_system_datadict_datadict();
            $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            $object['contractTypeName'] = $datadictDao->getDataNameByCode($object['contractType']);
            $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);

            if (ORDER_INPUT == '1' && $object['businessBelong'] != $oldArr['businessBelong']
                && $object['customerId'] != '1058'
            ) { //�ų�������ĺ�ͬ������ĺ�ͬ�޸Ĳ������µĺ�ͬ���
                //��ͬ���
                $codeRule = new model_common_codeRule();
                $object['contractCode'] = $codeRule->newContractCode($object);
            }
            //���Ҵ��� ��ѡ��Ʒ�� �������
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
                //��һ�β��ң����˳������Ѿ��������������
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
                //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
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
            //��Ʒ��
            $sqlf = "select exeDeptCode from oa_goods_base_info where id in ($goodsTypeIds)";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $proLineStr = "";
            foreach ($exeDeptNameArr as $k => $v) {
                $proLineStr .= $v['exeDeptCode'] . ",";
            }
            $object['productLineStr'] = $proLineStr;
            $object['exeDeptStr'] = $exeDeptNameStr;
            //�ж��Ƿ���Ҫ�ߵ�ִ�в�����������ʾ�ֶ�
            $isSellArr = explode(",", $goodsTypeStr);
            //����
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
                $object['isSaleConfirm'] = "1";
            } else {
                $object['isSell'] = "0";
                $object['isSaleConfirm'] = "0";
            }
            //����
            $fStr = ESMPRODUCTTYPE;
            $serTempArr = explode(",", $fStr);
            $serF = "0";
            foreach ($serTempArr as $k => $v) {
                if (in_array($v, $isSellArr)) {
                    $serF = "1";
                }
            }
            //��ȡ������չ�ֶ�ֵ
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($object['areaCode']);
            if (($serF == "1" || $object['contractType'] == 'HTLX-FWHT') && $expand != '1') {
                $object['isEngConfirm'] = "1";
            } else {
                $object['isEngConfirm'] = "0";
            }
            //�з�
            if (in_array(RDPRODUCTTYPE, $isSellArr)) {
                $object['isRdproConfirm'] = "1";
            } else {
                $object['isRdproConfirm'] = "0";
            }
            //�޸�������Ϣ
            parent :: edit_d($object, true);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * �رպ�ͬ����
     */
    function close_d($object, $isEditInfo = false)
    {
        if ($isEditInfo) {
            $object = $this->addUpdateInfo($object);
        }
        //���������ֵ䴦�� add by chengl 2011-05-15
        $this->processDatadict($object);

        $feeType='<br/>����ִͬ���쳣�رպ��������ĳɱ����� ��';
        if($object['fee'] == '0'){
            $feeType .= "ת�����۷���";
        }else if($object['fee'] == '1'){
            $feeType .= "�ϲ����º�ͬ  ".$object['contractCode'];
        }
        unset($object['fee']);
        unset($object['contractCode']);
        $object['closeRegard'] .= $feeType;

        return $this->updateById($object);
    }

    /**
     * ����Ҫ������������޲�Ʒ.�������
     */
    function changeNotApp_d($object, $invoiceJsonData = '')
    {
        $object['invoiceCode'] = implode(",", $object['invoiceCode']);
        $object['invoiceValue'] = implode(",", $object['invoiceValue']);
//            	echo "<pre>";
//        		print_r($invoiceArrs);
//        		die();
        try {

            //����������
            $object = $this->handlePaymentData($object);
            //���������ֵ��ֶ�
            $datadictDao = new model_system_datadict_datadict();
            $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            $object['contractTypeName'] = $datadictDao->getDataNameByCode($object['contractType']);
            $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
            $object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);
            unset($object['product']);
            unset($object['equ']);

            //�������
            $changeLogDao = new model_common_changeLog('contract', false);

            $changeLogDao->addLog($object);
            //�޸�������Ϣ
            parent :: edit_d($object, true);
            $orderId = $object['id'];

            // ��¼��ʱ��¼�Ŀ�Ʊ��ϢjsonData
            if($object['id'] && $invoiceJsonData != ''){
                $this->invoiceTypeRecord($object['id'],$invoiceJsonData);
            }

            //�ͻ���ϵ��
            $linkmanDao = new model_contract_contract_linkman();
            $linkmanDao->delete(array('contractId' => $orderId));
            foreach ($object['linkman'] as $k => $v) {
                if ($v['isDelTag'] == '1') {
                    unset ($object['linkman'][$k]);
                }
            }
            $linkmanDao->createBatch($object['linkman'], array('contractId' => $orderId), 'linkmanName');

            //�տ��ƻ�
            $orderequDao = new model_contract_contract_financialplan();
            $orderequDao->delete(array('contractId' => $orderId));
            foreach ($object['financialplan'] as $k => $v) {
                if ($v['isDelTag'] == '1') {
                    unset ($object['financialplan'][$k]);
                }
            }
            $orderequDao->createBatch($object['financialplan'], array('contractId' => $orderId), 'planDate');

            //�������� add by chenrf
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

            //ɾ�����μ��ص���ʱ�����¼(����)
            if (!empty($object['tempId'])) {
                $sql = "select id,ExaStatus from oa_contract_changlog where objType = 'contract' and tempId=" . $object['tempId'];
                $rs = $this->_db->getArray($sql);
                if (!empty($rs)) {
                    //ȡ�����ر����¼������ȷ�ϴ��/���������صı����¼��ɾ��
                    if (($rs[0]['ExaStatus'] != '����ȷ�ϴ��' && $rs[0]['ExaStatus'] != '���')
                        || (($rs[0]['ExaStatus'] == '����ȷ�ϴ��' || $rs[0]['ExaStatus'] == '���') && $object['id'] != $object['contractId'])
                    ) {
                        $delSql = "delete from oa_contract_changedetail where parentId=" . $rs[0]['id'];
                        $this->_db->query($delSql);
                        $delSql = "delete from oa_contract_changlog where objType = 'contract' and tempId=" . $object['tempId'];
                        $this->_db->query($delSql);
                    }
                }
            }
            //���º�ͬ�������
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
            // ������ת���۹����̻�����
            $obj = $this->get_d($object['id']);
            if (!empty($object['turnChanceIds'])) {
                $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    	`status` = '4',rObjCode = '" . $obj['objCode'] . "',
                    	contractCode = '" . $obj['contractCode'] . "'
                    	WHERE id IN(" . $obj['turnChanceIds'] . ")";
                $this->_db->query($sql);
            }

            // ������ת���۹������ϴ��� create by huanghaojin (������ת���۱��������������Բ�����������)
//            $equDao = new model_contract_contract_equ();
//            $productDao = new model_contract_contract_product();
//            if(!empty($object['material'])){
//                foreach ($object['material'] as $k => $v) {
//                    if($object['material'][$k]['id']!=''){//ɾ��ԭ����
//                        unset($object['material'][$k]);
//                    }else{//����������
//                        // ��17%˰�ʣ�����˰����
//                        $priceTax = bcmul($object['material'][$k]['price'], '1.17', 2);
//                        $object['material'][$k]['priceTax'] = $priceTax;
//                        $object['material'][$k]['moneyTax'] = bcmul($priceTax, $object['material'][$k]['number'], 2);
//
//                        // ������صĺ�ͬ��Ϣ
//                        $object['material'][$k]['contractTypeName'] = $object['contractTypeName'];
//                        $object['material'][$k]['contractType'] = $object['contractType'];
//                        $object['material'][$k]['contractName'] = $object['contractName'];
//
//                        // ������صĲ�Ʒ��Ϣ
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
//                //�������
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
     * ������ۺ�ͬ
     */
    function change_d($obj, $isMoney, $isbo, $invoiceJsonData = '')
    {
        $obj['contractMoney'] = sprintf("%.2f", $obj['contractMoney']);
        if (!empty($obj['material'])) { //��������ɾ��������ת�������Ϻ�����Ĵ���
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
        //�ϲ����Ϻͽ���ת��������
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
            //����������
//          $obj = $this->handlePaymentData($obj);

            //���������ֵ��ֶ�
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

            //���Ҵ��� ��ѡ��Ʒ�� �������
            $goodsTypeIds = "";
            $exeDeptNameStr = "";
            $newProLineStr = "";
            $newExeDeptStr = "";
            $xfProLineStr = ""; // ��������ͬһ���߲�ͬ��Ʒ���͵Ĳ�Ʒ���࣬��ĳĳ����������(x)/������(f)��Ʒ
            foreach ($obj['product'] as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    $goodsTypeIds .= $v['conProductId'] . ",";
                    $exeDeptNameStr .= $v['exeDeptId'] . ",";
                    $newProLineStr .= $v['newProLineCode'] . ",";

//                    if(isset($v['exeDeptId']) && $v['exeDeptId'] != '' && (!isset($v['exeDeptName']) || (isset($v['exeDeptName']) && $v['exeDeptName'] == ''))){
//                        $exeDeptName = $datadictDao->getDataNameByCode($v['exeDeptId']);// ����ѡ�е�ִ������ID����ƥ�������� PMS2741
//                        if($exeDeptName && !empty($exeDeptName)){
//                            $obj['product'][$k]['exeDeptName'] = $exeDeptName;
//                        }else{// �������������û�ܶ�����ص�ִ����������,��ȥ���۸����˹�����ڲ�
//                            $salepersonDao = new model_system_saleperson_saleperson();
//                            $exeDeptArr = $salepersonDao->find(array("exeDeptCode" => $v['exeDeptId']));
//                            if($exeDeptArr && !empty($exeDeptArr)){
//                                $obj['product'][$k]['exeDeptName'] = $exeDeptArr['exeDeptName'];
//                            }
//                        }
//                    }

                    // ֮ǰ���������֤��Ÿ����ֶ�ֵ,����ֻҪ�鵽ƥ���ֱ���滻 PMS 2848
                    if(isset($v['exeDeptId']) && $v['exeDeptId'] != ''){
                        $salepersonDao = new model_system_saleperson_saleperson();
                        $exeDeptSql = "select c.dataName,c.dataCode from oa_system_datadict c where 1=1 and(( c.parentCode = 'GCSCX')) and c.dataCode = '{$v['exeDeptId']}';";
                        $exeDeptArr = $salepersonDao->_db->get_one($exeDeptSql);
                        if($exeDeptArr && !empty($exeDeptArr)){
                            $obj['product'][$k]['exeDeptName'] = $exeDeptArr['dataName'];
                        }
                    }

                    if ($v['newProLineCode'] == 'HTCPX-CTFW') { // ��Ʒ��Ϊ��ͳ����Ĳ�Ʒ������ִ�������������
                        $newExeDeptStr .= $v['exeDeptId'] . ",";
                    }
                    if ($v['proTypeId'] == '11') { // �������Ʒ
                        $xfProLineStr .= $v['newProLineCode'] . "x,";
                    } else { // �������Ʒ
                        $xfProLineStr .= $v['newProLineCode'] . "f,";
                    }
                }
                $obj['product'][$k]['money'] = sprintf("%.2f", $v['money']);
            }
            $goodsTypeIds = rtrim($goodsTypeIds, ',');
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //��һ�β��ң����˳������Ѿ��������������
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
                //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
                $sqlB = "select a.id from oa_goods_type a INNER JOIN (select * from oa_goods_type " .
                    "where id in ($goodsTypeStrTemp)) b ON  b.lft >a.lft and b.rgt <a.rgt and a.parentId=-1";
                $goodsTypeB = $this->_db->getArray($sqlB);
                foreach ($goodsTypeB as $k => $v) {
                    $goodsTypeStr .= $v['id'] . ",";
                }
            }
            $goodsTypeStr = rtrim($goodsTypeStr, ',');
            $obj['goodsTypeStr'] = $goodsTypeStr;
            //��Ʒ��
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
            //�ж��Ƿ���Ҫ�ߵ�ִ�в�����������ʾ�ֶ�
            $isSellArr = explode(",", $goodsTypeStr);
            if (in_array(isSell, $isSellArr) || (empty ($obj['product']) && !empty ($obj['equ']))) {
                $obj['isSell'] = "1";
                $obj['isSellchange'] = "1";
            } else {
                $obj['isSell'] = "0";
                $obj['isSellchange'] = "0";
            }
            //��ȡ������չ�ֶ�ֵ
            $regionDao = new model_system_region_region();
            $expand = $regionDao->getExpandbyId($obj['areaCode']);
            if ($isMoney == '0' && $obj['isSub'] == '1') { //�ύʱִ��
                if ($expand != '1') {
                    $this->update(array("id" => $obj['id']), array("isSubAppChange" => "1"));
                    $this->update(array("id" => $obj['id']), array("isSubApp" => "1"));
                }
                //���ⲿ���ύ�ĺ�ͬ������봦��ɱ�ȷ����Ϣ
                if ($_SESSION['DEPT_ID'] != hwDeptId && $obj['isSub'] == '1') {
                    //����
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
                    //����
                    if ((in_array(ESMPRODUCTTYPE, $isSellArr) || $obj['contractType'] == 'HTLX-FWHT') && $expand != '1') {
                        $this->update(array("id" => $obj['id']), array("isEngConfirm" => "1"));
                        $this->update(array("id" => $obj['id']), array("engConfirm" => "0"));
                    } else {
                        $this->update(array("id" => $obj['id']), array("isEngConfirm" => "0"));
                        $this->update(array("id" => $obj['id']), array("engConfirm" => "0"));
                    }
                    //�з�
                    if (in_array(RDPRODUCTTYPE, $isSellArr)) {
                        $this->update(array("id" => $obj['id']), array("isRdproConfirm" => "1"));
                        $this->update(array("id" => $obj['id']), array("rdproConfirm" => "0"));
                    } else {
                        $this->update(array("id" => $obj['id']), array("isRdproConfirm" => "0"));
                        $this->update(array("id" => $obj['id']), array("rdproConfirm" => "0"));
                    }
                }
            }

            //1.���ⲿ���ύ�ĺ�ͬ������봦��ɱ�ȷ����Ϣ
            //2.����ĺ�ͬ������봦��ɱ�ȷ����Ϣ
            if ($_SESSION['DEPT_ID'] != hwDeptId && $obj['isSub'] == '1') {
                //������ò�Ʒ�߳ɱ�ȷ��״̬
                $costDao = new model_contract_contract_cost();
                $costDao->resetStateByCid($obj);
            }
            //����ԭ��ͬ�ڱ����Ĳ�Ʒ������ֵ�� ��ɾ����Ʒ��������غ󣬻���bug����ʱ��ô����
            if ($obj['isSub'] == '1') { //�ύʱִ��
                $this->update(array("id" => $obj['id']), array("goodsTypeStr" => $obj['goodsTypeStr'], "exeDeptStr" => $obj['exeDeptStr'], "newProLineStr" => $obj['newProLineStr']));
            }
            //�������������ʱֻ����������ļ�
            $changeLogDao = new model_common_changeLog('contract');
            //             $obj ['uploadFiles'] = $changeLogDao->processUploadFile ( $obj, $this->tbl_name );
            if ($obj['id'] != $obj['contractId']) { //������ʱ�����ͬ
                $obj['oldId'] = $obj['contractId'];
            }
            $obj['uploadFiles'] = $changeLogDao->processUploadFile($obj, 'oa_contract_contract2');
            if ($obj['id'] != $obj['contractId']) { //��ʱ�����ͬ��������
                foreach ($obj['uploadFiles'] as $k => $v) {
                    if ($v['originalId'] == '0') { //���Դ����˵�������ĸ���
                        unset($obj['uploadFiles'][$k]['oldId']);
                    } else {
                        $obj['uploadFiles'][$k]['oldId'] = $obj['uploadFiles'][$k]['originalId'];
                    }
                }
            }
            //�����¼,�õ��������ʱ������id
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
            if (isset($obj['tempId']) && $obj['contractId'] != $obj['oldId']) { //���ڼ�������ʱ�����¼����
                //�ϲ���ʱ�����¼ɾ����������
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
                        $obj[$val][$k]['oldId'] = empty($obj[$val][$k]['originalId']) ? '0' : $obj[$val][$k]['originalId']; //�ӱ��originalId��ӦԴ����id
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
            //license�������(...)

            // ��¼��ʱ��¼�Ŀ�Ʊ��ϢjsonData
            if($tempObjId && $invoiceJsonData != ''){
                $this->invoiceTypeRecord($tempObjId,$invoiceJsonData);
            }

            //������ʱ��¼�󣬴�����ʱ��¼���ϵ�conProductId ID2225 2016-11-21
            $this->reloadEquByTempObjId($tempObjId,$obj);

            //������ʱ��¼�󣬴�����ʱ��¼ ����ת���۵�proId
            $tempConArr = $this->getContractInfoWithTemp($tempObjId, null, 1);

            if (!empty($tempConArr['product'])) {
                foreach ($tempConArr['product'] as $k => $v) {
                    $this->updateConProductIdBybow_d($v['onlyProductId']);
                }
            }
            //ɾ�����μ��ص���ʱ�����¼(����)
            if (!empty($obj['tempId'])) {
                $sql = "select id,ExaStatus from oa_contract_changlog where objType = 'contract' and tempId=" . $obj['tempId'];
                $rs = $this->_db->getArray($sql);
                if (!empty($rs)) {
                    //ȡ�����ر����¼������ȷ�ϴ��/���������صı����¼��ɾ��
                    if (($rs[0]['ExaStatus'] != '����ȷ�ϴ��' && $rs[0]['ExaStatus'] != '���')
                        || (($rs[0]['ExaStatus'] == '����ȷ�ϴ��' || $rs[0]['ExaStatus'] == '���') && $obj['id'] != $obj['contractId'])
                    ) {
                        $delSql = "delete from oa_contract_changedetail where parentId=" . $rs[0]['id'];
                        $this->_db->query($delSql);
                        $delSql = "delete from oa_contract_changlog where objType = 'contract' and tempId=" . $obj['tempId'];
                        $this->_db->query($delSql);
                    }
                }
            }
            if ($obj['isSub'] == '0' && !empty($tempObjId)) { //����ʱ������ʱ�����¼������״̬��Ϊ����
                $updateSql = "update oa_contract_changlog set ExaStatus = '����' where objType = 'contract' and tempId=" . $tempObjId;
                $this->_db->query($updateSql);
            }
            //���º�ͬ�������
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
     * ������ʱ��ͬID ������ʱ���ϼ�¼�Ķ�Ӧ��ƷID
     */
    function reloadEquByTempObjId($contractId,$obj){
        $selectSql = "select id,conProductId,onlyProductId from oa_contract_product where contractId = '" . $contractId . "'";
        $selObj = $this->_db->getArray($selectSql);

        if(!empty($selObj)){
            foreach($selObj as $k => $v){
                if($obj['id'] != $obj['contractId']){// ����ֱ��������һ����ʱ�����ͬ���ݵ����
                    $updateSql = "update oa_contract_equ  set conProductId = '" . $v['id'] . "',isBorrowToorder = 0 where contractId = '".$contractId."'and onlyProductId = '" . $v['onlyProductId'] . "' and isBorrowToorder<>1 and originalId = 0";
                }else{
                    $updateSql = "update oa_contract_equ  set conProductId = '" . $v['id'] . "',isBorrowToorder = 0 where contractId = '".$contractId."'and onlyProductId = '" . $v['onlyProductId'] . "' and isBorrowToorder<>1 and conProductId = '" . $v['conProductId'] . "'";
                }
                $this->_db->query($updateSql);
            }
        }
    }

    /**
     * ���ݺ�ͬID ������ͬ�����ʱ��¼
     */
    function getConTempById($cid, $changeReason)
    {
        try {
            $this->start_d();

            $obj = $this->getContractInfo($cid);
            unset($obj ['isTemp']);
            unset($obj ['originalId']);
            unset($obj ['ExaStatus']);
            //�����������
            $changeLogDao = new model_common_changeLog('contract');
            //�����¼,�õ��������ʱ������id
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
            //license�������(...)

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
     * �ж��Ƿ�Ϊ����ĺ�ͬ
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
     * ���������ϴ�
     */
    function uploadfile_d($row)
    {
        try {
            //���������ƺ�Id
            $this->updateObjWithFile($row['serviceId']);
            return true;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * ����Դ�����ӱ�ID ��ȡδִ������
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
     * ȷ�Ϻ�ͬ
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
                //���±��ȷ�ϳɱ������ʶ
                $this->update(array("id" => $contract['originalId']), array("isSubAppChange" => "0"));
                if ($contract['ExaStatus'] == "���") {

                    // �����ʱ��¼�Ƿ���ڿ�Ʊ��Ϣ��¼,�еĻ������滻��ԭ���Ŀ�Ʊ��Ϣ��¼ PMS 647
                    $chkInvoiceRecords = $this->_db->get_one("select invoiceValues from oa_contract_extfields_data where contractId = '{$objId}';");
                    if($chkInvoiceRecords && !empty($chkInvoiceRecords['invoiceValues'])){
                        $this->invoiceTypeRecord($contract['originalId'],$chkInvoiceRecords['invoiceValues']);
                    }

                    if($contract['contractMoney'] < $contractOld['contractMoney']){
                        //�����ͬȷ������
                        $conFirmArr['type'] = '��ͬ���(����)';
                        $conFirmArr['money'] = $contractOld['contractMoney']*1 - $contract['contractMoney']*1;
                        $conFirmArr['state'] = 'δȷ��';
                        $conFirmArr['contractId'] = $contract['originalId'];
                        $conFirmArr['contractCode'] = $contract['contractCode'];
                        $confirmDao = new model_contract_contract_confirm();
                        $confirmDao->add_d($conFirmArr);

                    }

                    //������Ŀ��Ϣ
                    $proDao = new model_contract_conproject_conproject();
                    $proDao->createProjectBySale_d($contract['originalId']);

                    //���³ɱ���Ϣ
                    $costDao = new model_contract_contract_cost();
                    $costDao->returnStateByCid($contract['originalId'], $objId);
                    //����ͨ������������Ʒ�µ��������ϵĹ�����ƷID����
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
                    //��ʼ����Ҫɾ��������id���飬��������ɾ�����ϣ��൱���������ϣ�����Ҫɾ��ԭ�������ϼ����
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
                    //ɾ������ǰ�����ϼ����
                    if (!empty($delEquIdArr)) {
                        $ids = implode(',', $delEquIdArr);
                        $equDelSql = "delete from oa_contract_equ where contractId='" . $contract['originalId'] . "' and (id in($ids) or parentEquId in($ids))";
                        $this->query($equDelSql);
                        $equUpSql = "update oa_contract_equ set remark = '' where contractId='" . $contract['originalId'] . "' and remark in($ids)";
                        $this->query($equUpSql);
                    }
                    //����ǩ��״̬
                    if ($contract['isAcquiring'] == '1') {
                        $this->ajaxAcquiring_d($contract['originalId'], '0');
                    }
                    //���� �ؿ����� ��״̬
                    $this->updateIcc($contract);
                    //���ù��̽ӿ�
                    $esmDao = new model_engineering_project_esmproject();
                    $esmDao->updateContractMoney_d($contract['originalId']);

                    // ��ͬ�����仯�������������Ŀ�´�״̬�Ĵ���
                    $this->updateProjectStatus_d($contract['originalId']);

                    //���º�ͬ�������
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
                    //Դ��״̬����
                    if ($contract['isNeedRestamp'] == 1 && $contract['isStamp'] == 1) { //��Ҫ���¸���
                        //ֱ�����ø���״̬λ�������и��¼�¼�ر�
                        $this->update(array(
                            'id' => $contract['originalId']
                        ), array(
                            'status' => 2,
                            'isStamp' => 0,
                            'isNeedRestamp' => 0,
                            'isNeedStamp' => 0,
                            'stampType' => ''
                        ));

                    } elseif ($contract['isNeedStamp'] == 1 && $contract['isStamp'] == 0) { //���ڸ��µĴ���

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
                    } else { //�Ǹ��´���
                        $this->update(array(
                            'id' => $contract['originalId']
                        ), array(
                            'status' => 2,
                            'isNeedRestamp' => 0
                        ));
                    }
                    // ������ת���۹����̻�����
                    if (!empty($contract['turnChanceIds'])) {
                        $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    		`status` = '4',rObjCode = '" . $contract['objCode'] . "',
                    		contractCode = '" . $contract['contractCode'] . "'
                    		WHERE id IN(" . $contract['turnChanceIds'] . ")";
                        $this->_db->query($sql);
                    }
                    //���������ɺ����ʼ�
                    //��ȡĬ�Ϸ�����
                    include(WEB_TOR . "model/common/mailConfig.php");
                    $tomail = isset($mailUser['oa_contract_change']['TO_ID']) ? $mailUser['oa_contract_change']['TO_ID'] : null;
                    $addmsg = "��ͬ���ΪΪ��" . $contract['contractCode'] . "��,�ĺ�ͬ�ѷ��������";
                    $emailDao = new model_common_mail();
                    $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_change", "����", "ͨ��", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $contract['originalId'],
                        "stepName" => "����ͨ��",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));

                    //���º�ͬ��Ʒ�ڵĺ�ͬ���
                    $updateCodeSql = "update oa_contract_product p left join oa_contract_contract c on p.contractId=c.id set p.contractCode=c.contractCode where p.contractId='".$contract['originalId']."'";
                    $this->_db->query($updateCodeSql);

                    // ������ɺ�,���������ϵķ�����������
                    $specialProIdArr = explode(',', specialProId);
                    $equDao = new model_contract_contract_equ();
                    $catchParentIds = array();
                    $contractEquArr = $equDao->findAll(" contractId={$contract['originalId']}");
                    if($contractEquArr){
                        foreach ($contractEquArr as $equK => $equV){
                            $updateArr = array();
                            if(in_array($equV['productId'], $specialProIdArr)){
                                $catchParentIds[] = $equV['id'];// �ռ�������������ID
                            }else if(in_array($equV['parentEquId'], $catchParentIds)){// �������
                                $updateArr['id'] = $equV['id'];
                                $updateArr['executedNum'] = $updateArr['issuedShipNum'] = $equV['number'];
                                $equDao->updateById($updateArr);
                            }
                        }
                    }

                    // ������ɺ�,������������Ƿ��н���޸�,�еĻ��ڹ����˺�ͬ��������Ŀִ�й켣�������ʷ��¼
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
                    AND cd.changeFieldCn = 'ǩԼ���';";
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
                                'projectId' => $v['id'], 'operationType' => "��ͬ�����", 'description' => "���ǰ���:��{$oldValue}��-> �������:��{$NewValue}��",
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
                            "stepName" => "����ͨ��",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                    } else {
                        $handleDao->handleAdd_d(array(
                            "cid" => $contract['originalId'],
                            "stepName" => "�������",
                            "isChange" => 2,
                            "stepInfo" => "",
                        ));
                    }
                }
            }

            // �����ɺ������صĲ�Ʒ��Ŀ
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

    //���ٷֱȸ��º�ͬ��� ��� ����� �ã�
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
     * ȷ�Ϻ�ͬ-�������ϱ����������
     * @param $id
     * @param int $contractChange // ��Ӳ���,Ϊ�˷ֱ��Ǵ����ϱ�������Ļ��Ǻ�ͬ���������,Ĭ��0Ϊ���ϱ�� ����PMS2373
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
                //			  	//�޸ĺ�ͬȷ��״̬
                //				$dealStatusSql = "update oa_contract_contract set dealStatus=2 where id=" . $contract['originalId'] . "";
                //				$this->query($dealStatusSql);
                //			  }
                //���±��ȷ�ϳɱ������ʶ
                $this->update(array("id" => $contract['originalId']), array("isSubAppChange" => "0"));

                // �����ʱ��¼�Ƿ���ڿ�Ʊ��Ϣ��¼,�еĻ������滻��ԭ���Ŀ�Ʊ��Ϣ��¼ PMS 647
                $chkInvoiceRecords = $this->_db->get_one("select invoiceValues from oa_contract_extfields_data where contractId = '{$id}';");
                if($chkInvoiceRecords && !empty($chkInvoiceRecords['invoiceValues'])){
                    $this->invoiceTypeRecord($contract['originalId'],$chkInvoiceRecords['invoiceValues']);
                }

                //����ͨ������������Ʒ�µ��������ϵĹ�����ƷID����
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
                //��ʼ����Ҫɾ��������id���飬��������ɾ�����ϣ��൱���������ϣ�����Ҫɾ��ԭ�������ϼ����
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
                //ɾ������ǰ�����ϼ����
                if (!empty($delEquIdArr)) {
                    $ids = implode(',', $delEquIdArr);
                    $equDelSql = "delete from oa_contract_equ where contractId='" . $contract['originalId'] . "' and (id in($ids) or parentEquId in($ids))";
                    $this->query($equDelSql);
                    $equUpSql = "update oa_contract_equ set remark = '' where contractId='" . $contract['originalId'] . "' and remark in($ids)";
                    $this->query($equUpSql);
                }
                //����ǩ��״̬
                if ($contract['isAcquiring'] == '1') {
                    $this->ajaxAcquiring_d($contract['originalId'], '0');
                }
                //���ù��̽ӿ�
                $esmDao = new model_engineering_project_esmproject();
                $esmDao->updateContractMoney_d($contract['originalId']);

                if($contractChange == 0){// ֻ�����ϱ��,�Ǻ�ͬ���
                    // ��ƴԭ��ͬ�����۲��ߵĸ�����Ϣ 2017-01-05
                    $costDao = new model_contract_contract_cost();
                    $sql1 = "select * from oa_contract_cost where contractId = '" . $contract['originalId'] . "' AND issale <> 1;";
                    $oldCostRows = $this->_db->getArray($sql1);
//                    foreach($oldCostRows as $k => $v){
//                        unset($v['id']);
//                        $v['contractId'] = $id;
//                        $costDao->add_d($v);
//                    }// ������ȷ��ҳ������һ�η�����ߵĸ���Ǩ��

                    //���¼���ɱ�����
                    $sql = "select * from oa_contract_cost where contractId = '" . $id . "'";
                    $rows = $this->_db->getArray($sql);



                    //�������۵ĳɱ�����
                    if ($rows) {
                        $moneyAll = 0;
                        foreach ($rows as $key => $val) {
                            if($val['issale'] == 1){
                                $moneyAll += $val['confirmMoneyTax'];
                                //���³ɱ�����
                                $updateF = "update oa_contract_cost set confirmMoney=" . $val['confirmMoney'] . ",confirmMoneyTax=" . $val['confirmMoneyTax'] . " where contractId='" . $contract['originalId'] . "' AND productLine='" . $val['productLine'] . "' AND issale = 1";
                                $this->_db->query($updateF);
                            }
                        }
                    }

                    //���»��� ����ȷ��״̬
                    $updateE = "update oa_contract_contract set saleCost=" . $moneyAll . " where id='" . $contract['originalId'] . "'";
                    $this->_db->query($updateE);

                    //���¼���ɱ�����
                    $exGross = $this->countCost2($contract['originalId'], $id);
                }

                // ��ͬ���,������
                if($contractChange != 0 && $contract['ExaStatus'] == "���"){
                    //���³ɱ���Ϣ
                    $costDao = new model_contract_contract_cost();
                    $costDao->returnStateByCid($contract['originalId'], $id);

                    //���¼���ɱ�����
                    $this->countCost($contract['originalId']);
                }

                //					$sql = "update oa_contract_contract set signStatus='2' where id= " . $contract['originalId'] . "";
                //					$this->query($sql);
                //���º�ͬ�������
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
                //Դ��״̬����
                if ($contract['isNeedRestamp'] == 1 && $contract['isStamp'] == 1) { //��Ҫ���¸���
                    //ֱ�����ø���״̬λ�������и��¼�¼�ر�
                    $this->update(array(
                        'id' => $contract['originalId']
                    ), array(
                        'status' => 2,
                        'isStamp' => 0,
                        'isNeedRestamp' => 0,
                        'isNeedStamp' => 0,
                        'stampType' => ''
                    ));
                } elseif ($contract['isNeedStamp'] == 1 && $contract['isStamp'] == 0) { //���ڸ��µĴ���
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
                } else { //�Ǹ��´���
                    $this->update(array(
                        'id' => $contract['originalId']
                    ), array(
                        'status' => 2,
                        'isNeedRestamp' => 0
                    ));
                }

                // ������ɺ�,���������ϵķ�����������
                $specialProIdArr = explode(',', specialProId);
                $catchParentIds = array();
                $contractEquArr = $conEquDao->findAll(" contractId={$contract['originalId']}");
                if($contractEquArr){
                    foreach ($contractEquArr as $equK => $equV){
                        $updateArr = array();
                        if(in_array($equV['productId'], $specialProIdArr)){
                            $catchParentIds[] = $equV['id'];// �ռ�������������ID
                        }else if(in_array($equV['parentEquId'], $catchParentIds)){// �������
                            $updateArr['id'] = $equV['id'];
                            $updateArr['executedNum'] = $updateArr['issuedShipNum'] = $equV['number'];
                            $conEquDao->updateById($updateArr);
                        }
                    }
                }

                if($contractChange != 0 && $contract['ExaStatus'] == "���"){
                    //��ȡĬ�Ϸ�����
                    include(WEB_TOR . "model/common/mailConfig.php");
                    $tomail = isset($mailUser['oa_contract_change']['TO_ID']) ? $mailUser['oa_contract_change']['TO_ID'] : null;
                    $addmsg = "��ͬ���ΪΪ��" . $contract['contractCode'] . "��,�ĺ�ͬ�ѷ��������";
                    $emailDao = new model_common_mail();
                    $emailInfo = $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_change", "����", "ͨ��", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $contract['originalId'],
                        "stepName" => "����ͨ��",
                        "isChange" => 2,
                        "stepInfo" => "",
                    ));

                    //���º�ͬ��Ʒ�ڵĺ�ͬ���
                    $updateCodeSql = "update oa_contract_product p left join oa_contract_contract c on p.contractId=c.id set p.contractCode=c.contractCode where p.contractId='".$contract['originalId']."'";
                    $this->_db->query($updateCodeSql);
                }

                // ��������¼
//                $this->addNoAuditRecord($contract['originalId'],$contract,'��ͬ�������');
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    /**
     * ȷ�Ϻ�ͬ
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
                if ($contract['ExaStatus'] == "���") {
                    //������Ŀ��Ϣ
                    $proDao = new model_contract_conproject_conproject();
                    $proDao->createProjectBySale_d($objId);


                    //��ͬ����ͨ�����ʼ�֪ͨȥά���ͻ���ͬ��
                    $this->mailDeal_d('contract_maintenance', NULL, array('id' => $objId));
                    //�ж��Ƿ���������Ŀ
                    if (!empty ($contract['chanceId'])) {
                        //�����̻����״̬����ɡ������ɶ�����
                        $chanceDao = new model_projectmanagent_chance_chance();
                        $condiction = array(
                            'id' => $contract['chanceId']
                        );
                        $chanceDao->update($condiction, array(
                            "contractTurnDate" => date("Y-m-d"
                            ), "status" => "4", "rObjCode" => $contract['objCode'], "contractCode" => $contract['contractCode']));

                        //�ж��Ƿ���������Ŀ
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
                            //�ر�������Ŀ�����Ĺ�����Ŀ
                            //						$esmDao = new model_engineering_project_esmproject();
                            //						$esmInfo = $esmDao -> closeProjectByContractId_d($v['id']);
                            $emailDao = new model_common_mail();
                            $addmsg = " �����Ϣ�� <br/> ������ͬ����" . $contract['contractCode'] . "�� ����Ч<br/>  �Ѽ�ʱ�رչ�����Ŀ����" . $esmInfo['projectCode'] . "��<br/>";
                            $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "ͨ��", $esmInfo['managerId'], $addmsg);
                        }

                    } else if (!empty($contract['trialprojectId'])) {
                        $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                        $RDcondiction = array(
                            'id' => $contract['trialprojectId']
                        );
                        $trialprojectDao->update($RDcondiction, array(
                            "isFail" => "1"));
                        //�ر�������Ŀ�����Ĺ�����Ŀ
                        $esmDao = new model_engineering_project_esmproject();
                        $esmInfo = $esmDao->closeProjectByContractId_d($contract['trialprojectId']);
                        $emailDao = new model_common_mail();
                        $addmsg = " �����Ϣ�� <br/> ��Ч�ĺ�ͬ�ţ���" . $contract['contractCode'] . "��<br/> ��ʧЧ��������Ŀ����" . $contract['trialprojectCode'] . "��<br/> �ѹرյĹ�����Ŀ����" . $esmInfo['projectCode'] . "��<br/>";
                        $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "ͨ��", $esmInfo['managerId'], $addmsg);

                    } else if (!empty($contract['turnChanceIds'])) { // ������ת���۹����̻�����
                        $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    		status = '4',rObjCode = '" . $contract['objCode'] . "',
                    		 contractCode = '" . $contract['contractCode'] . "'
                    		WHERE id IN(" . $contract['turnChanceIds'] . ")";
                        $this->_db->query($sql);
                    }

                    //����������
                    $this->contractAppArr($objId);
                    //������ɺ����ʼ�
                    //��ȡ������չ�ֶ�ֵ
                    $regionDao = new model_system_region_region();
                    $expand = $regionDao->getExpandbyId($contract['areaCode']);
//                    $tomailId = $regionDao->getTomailId($contract['areaCode']);
                    $tomailId = $regionDao->getAreaPrincipalId($contract['areaCode']);// ��ȡ��������

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


                    //��Ӧ��Ʒ�߻�ȡ�ʼ�����������
                    $toMailId = '';
                    if(!empty($contract['productLineStr'])){
                        $toMailArr = $this->productLineToMailArr;
                        $productLine = $contract['productLineStr'];
                        if(strstr($productLine,',')){//���ڶ��ִ�в���
                            $productLineArr = explode(',',$productLine);
                            foreach ($productLineArr as $v){
                                if($v == 'HTCPX-YQYB'){//����������Ǳ����񲿵ģ������з����Ʒ�򲻷��ʼ�
                                    $hasYF = 0;
                                    $productDao = new model_contract_contract_product();
                                    $products = $productDao->getCostInfoProBycId($contract['id']);
                                    foreach($products as $v){
                                        if($v['proTypeId'] == '18'){$hasYF += 1;}
                                    }
                                    if( $hasYF > 0 ){//��������з����Ʒ�����ʼ�
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

                    // ���ִ�й켣��¼
                    $tracksDao = new model_contract_contract_tracks();
                    $tracksObject = array(
                        'contractId' => $contract['id'],//��ͬID
                        'contractCode'=> $contract['contractCode'],//��ͬ���
                        'exePortion' => $proDao->getConduleBycid($contract['id']),//��ִͬ�н���
                        'schedule' => "",//��ͬ����
                        'modelName'=>'contractBegin',
                        'operationName'=>'��ͬ��ʼִ��',
                        'result'=>'1',
                        'recordTime'=>date("Y-m-d"),
                        'expand2'=>'model_contract_contract_contract:confirmContract_d'
                    );
                    $recordId = $tracksDao->addRecord($tracksObject);

                    //��ȡĬ�Ϸ�����
                    if( $contract['contractType'] == "HTLX-FWHT"){
                        $tomail = ( $toMailId == '' )? $tomail : $tomail.','.$toMailId;
                        $addmsg = $contract['contractCode'] . " ��ͬ�Ѿ�ͨ�����������½OA�������̹���--��Ŀ����--�����ͬ�����<br/>";
                    }else{
                        include (WEB_TOR . "model/common/mailConfig.php");
                        $tomail = $mailUser['oa_contract_contract']['TO_ID'];
                        $addmsg = "��" . $contract['contractCode'] . "��,��ͬ�Ѿ�ͨ��������<br/>";
                    }
                    $addmsgInit = $this->conProductFun($objId);
                    $addmsg .= $addmsgInit;

                    $emailDao = new model_common_mail();
                    $emailInfo = $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_contract", "����", "ͨ��", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $objId,
                        "stepName" => "����ͨ��",
                        "isChange" => 0,
                        "stepInfo" => "",
                    ));

                    // ������ɺ�,���������ϵķ�����������
                    $specialProIdArr = explode(',', specialProId);
                    $equDao = new model_contract_contract_equ();
                    $catchParentIds = array();
                    $contractEquArr = $equDao->findAll(" contractId={$contract['id']}");
                    if($contractEquArr){
                        foreach ($contractEquArr as $equK => $equV){
                            $updateArr = array();
                            if(in_array($equV['productId'], $specialProIdArr)){
                                $catchParentIds[] = $equV['id'];// �ռ�������������ID
                            }else if(in_array($equV['parentEquId'], $catchParentIds)){// ����������
                                $updateArr['id'] = $equV['id'];
                                $updateArr['executedNum'] = $updateArr['issuedShipNum'] = $equV['number'];
                                $equDao->updateById($updateArr);
                            }
                        }
                    }

                } else if ($contract['ExaStatus'] == "���") {
                    $updateB = "update oa_contract_equ_link set ExaStatus='���' where contractId=$objId";
                    $this->_db->query($updateB);
                    $handleDao->handleAdd_d(array(
                        "cid" => $objId,
                        "stepName" => "�������",
                        "isChange" => 0,
                        "stepInfo" => "",
                    ));
                }
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            insertOperateLog($e->getMessage(), "ʧ��");
        }
    }

    /**
     * ȷ�Ϻ�ͬ,����������ͬ��
     * 2017-01-09 ΪPMS2373����
     * @param $contractId
     */
    function confirmContractWithoutAudit_d($contractId)
    {
        try{
            $this->start_d();
            $handleDao = new model_contract_contract_handle();
            if (!empty ($contractId)) {
                $contract = $this->get_d($contractId);
                if ($contract['ExaStatus'] == "���") {
                    //������Ŀ��Ϣ
                    $proDao = new model_contract_conproject_conproject();
                    $proDao->createProjectBySale_d($contractId);

                    //��ͬ����ͨ�����ʼ�֪ͨȥά���ͻ���ͬ��
                    $this->mailDeal_d('contract_maintenance', NULL, array('id' => $contractId));
                    //�ж��Ƿ���������Ŀ
                    if (!empty ($contract['chanceId'])) {
                        //�����̻����״̬����ɡ������ɶ�����
                        $chanceDao = new model_projectmanagent_chance_chance();
                        $condiction = array(
                            'id' => $contract['chanceId']
                        );
                        $chanceDao->update($condiction, array(
                            "contractTurnDate" => date("Y-m-d"
                            ), "status" => "4", "rObjCode" => $contract['objCode'], "contractCode" => $contract['contractCode']));

                        //�ж��Ƿ���������Ŀ
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
                            //�ر�������Ŀ�����Ĺ�����Ŀ
                            //						$esmDao = new model_engineering_project_esmproject();
                            //						$esmInfo = $esmDao -> closeProjectByContractId_d($v['id']);
                            $emailDao = new model_common_mail();
                            $addmsg = " �����Ϣ�� <br/> ������ͬ����" . $contract['contractCode'] . "�� ����Ч<br/>  �Ѽ�ʱ�رչ�����Ŀ����" . $esmInfo['projectCode'] . "��<br/>";
                            $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "ͨ��", $esmInfo['managerId'], $addmsg);
                        }

                    } else if (!empty($contract['trialprojectId'])) {
                        $trialprojectDao = new model_projectmanagent_trialproject_trialproject();
                        $RDcondiction = array(
                            'id' => $contract['trialprojectId']
                        );
                        $trialprojectDao->update($RDcondiction, array(
                            "isFail" => "1"));
                        //�ر�������Ŀ�����Ĺ�����Ŀ
                        $esmDao = new model_engineering_project_esmproject();
                        $esmInfo = $esmDao->closeProjectByContractId_d($contract['trialprojectId']);
                        $emailDao = new model_common_mail();
                        $addmsg = " �����Ϣ�� <br/> ��Ч�ĺ�ͬ�ţ���" . $contract['contractCode'] . "��<br/> ��ʧЧ��������Ŀ����" . $contract['trialprojectCode'] . "��<br/> �ѹرյĹ�����Ŀ����" . $esmInfo['projectCode'] . "��<br/>";
                        $emailInfo = $emailDao->toCloseEsmMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "esmClose", $contract['contractCode'], "ͨ��", $esmInfo['managerId'], $addmsg);

                    } else if (!empty($contract['turnChanceIds'])) { // ������ת���۹����̻�����
                        $sql = "UPDATE oa_sale_chance SET contractTurnDate = '" . date("Y-m-d") . "',
                    		`status` = '4',rObjCode = '" . $contract['objCode'] . "',
                    		 contractCode = '" . $contract['contractCode'] . "'
                    		WHERE id IN(" . $contract['turnChanceIds'] . ")";
                        $this->_db->query($sql);
                    }

                    //��ȡ������չ�ֶ�ֵ
                    $regionDao = new model_system_region_region();
                    $expand = $regionDao->getExpandbyId($contract['areaCode']);
                    $tomailId = $regionDao->getAreaPrincipalId($contract['areaCode']);// ��ȡ��������

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

                    //��Ӧ��Ʒ�߻�ȡ�ʼ�����������
                    $toMailId = '';
                    if(!empty($contract['productLineStr'])){
                        $toMailArr = $this->productLineToMailArr;
                        $productLine = $contract['productLineStr'];
                        if(strstr($productLine,',')){//���ڶ��ִ�в���
                            $productLineArr = explode(',',$productLine);
                            foreach ($productLineArr as $v){
                                if($v == 'HTCPX-YQYB'){//����������Ǳ����񲿵ģ������з����Ʒ�򲻷��ʼ�
                                    $hasYF = 0;
                                    $productDao = new model_contract_contract_product();
                                    $products = $productDao->getCostInfoProBycId($contract['id']);
                                    foreach($products as $v){
                                        if($v['proTypeId'] == '18'){$hasYF += 1;}
                                    }
                                    if( $hasYF > 0 ){//��������з����Ʒ�����ʼ�
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

                    // ���ִ�й켣��¼
                    $tracksDao = new model_contract_contract_tracks();
                    $tracksObject = array(
                        'contractId' => $contract['id'],//��ͬID
                        'contractCode'=> $contract['contractCode'],//��ͬ���
                        'exePortion' => $proDao->getConduleBycid($contract['id']),//��ִͬ�н���
                        'schedule' => "",//��ͬ����
                        'modelName'=>'contractBegin',
                        'operationName'=>'��ͬ��ʼִ��',
                        'result'=>'1',
                        'recordTime'=>date("Y-m-d"),
                        'expand2'=>'model_contract_contract_contract:confirmContract_d'
                    );
                    $recordId = $tracksDao->addRecord($tracksObject);

                    // ��������¼
//                    $this->addNoAuditRecord($contract['id'],$contract,'��ͬ����');

                    //��ȡĬ�Ϸ�����
                    if( $contract['contractType'] == "HTLX-FWHT"){
                        $tomail = ( $toMailId == '' )? $tomail : $tomail.','.$toMailId;
                        $addmsg = $contract['contractCode'] . " ��ͬ�Ѿ�ͨ�����������½OA�������̹���--��Ŀ����--�����ͬ�����<br/>";
                    }else{
                        include (WEB_TOR . "model/common/mailConfig.php");
                        $tomail = $mailUser['oa_contract_contract']['TO_ID'];
                        $addmsg = "��" . $contract['contractCode'] . "��,��ͬ�Ѿ�ͨ��������<br/>";
                    }
                    $addmsgInit = $this->conProductFun($contractId);
                    $addmsg .= $addmsgInit;

                    $emailDao = new model_common_mail();
                    $emailInfo = $emailDao->contractChangeMail(1, $_SESSION['USERNAME'], $_SESSION['EMAIL'], "oa_contract_contract", "����", "ͨ��", $tomail, $addmsg);

                    $handleDao->handleAdd_d(array(
                        "cid" => $contractId,
                        "stepName" => "����ͨ��",
                        "isChange" => 0,
                        "stepInfo" => "�ú�ͬ��������",
                    ));

                }
            }
            $this->commit_d();
        }catch (Exception $e) {
            $this->rollBack();
            insertOperateLog($e->getMessage(), "ʧ��");
        }
    }

    /**
     * ��¼������Ϣ
     * @param $obj
     * @return bool|int|mixed|string
     */
    function addNoAuditRecord($cid,$contract,$taskName){
        $userName = $_SESSION['USERNAME'];
        $userId = $_SESSION['USER_ID'];
        $date = date("Y-m-d H:i:s");
        $result = '';
        //step1 ������������(wf_task)
        $task['Creator'] = $userId;
        $task['name'] = $taskName;// ��ͬ���� or ��ͬ�������
        $task['code'] = 'oa_contract_contract';
        $task['examines'] = 'ok';
        $task['Status'] = 0;
        $task['start'] = $date;
        $task['finish'] = $date;
        $task['Pid'] = $cid;// ��ͬID
        $task['objCode'] = $contract['contractCode'];// ��ͬ����
        $task['objName'] = $contract['contractName'];// ��ͬ����
        $task['objCustomer'] = $contract['customerName'];// ��ͬ�ͻ�����
        $task['objAmount'] = $contract['contractMoney'];// ��ͬ���
        $task['objUserName'] = $contract['prinvipalName'];// ��ͬ������
        $task['objUser'] = $contract['prinvipalId'];// ��ͬ������ID
        $this->tbl_name = 'wf_task';
        $workflowDao = new model_common_workflow_workflow();
        $taskId = $workflowDao->add_d($task);

        //step1.1 ����ͬ����������
        $SmallID = 1;
        $chkSql = "select * from wf_task where Pid ='{$cid}' and code='oa_contract_contract' and name = '��ͬ�������' ORDER BY task desc;";
        $chkResult = $this->_db->getArray($chkSql);
        if($chkResult){
            $num = count($chkResult);
            $SmallID = $num;
        }

        //step2 �������̲����(flow_step)
        if($taskId){
            $step['SmallID'] = $SmallID;
            $step['Wf_task_ID'] = $taskId;
            $step['Step'] = 1;
            $step['Item'] = '����';
            $step['User'] = $userId;
            $step['status'] = 'ok';
            $step['Start'] = $date;
            $step['Endtime'] = $date;
            $workflowDao->tbl_name = 'flow_step';
            $stepId = $workflowDao->add_d($step);

            //step3 �������̲��账���(flow_step_partent)
            if($stepId != '' && $stepId){
                $step_partent['StepID'] = $stepId;
                $step_partent['SmallID'] = $SmallID;
                $step_partent['Wf_task_ID'] = $taskId;
                $step_partent['User'] = $userId;
                $step_partent['Flag'] = 1;
                $step_partent['Result'] = 'ok';
                $step_partent['Content'] = '��������������ϵͳֱ������ͨ����';
                $step_partent['START'] = $date;
                $step_partent['Endtime'] = $date;
                $workflowDao->tbl_name = 'flow_step_partent';
                $result = $workflowDao->add_d($step_partent);
            }
        }
        return $result;
    }

    /**
     * ���ִ�й켣��¼ (ID2243 2016-12-8)
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
            'contractId' => $obj['contractId'],//��ͬID
            'contractCode'=> isset($obj['contractCode'])? $obj['contractCode'] : $contractCode['contractCode'],//��ͬ���
            'exePortion' => $proDao->getConduleBycid($obj['contractId']),//��ִͬ�н���
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
     * �ر�����ȷ��
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

                    // �������й�������Ŀ�ĺ�ͬ״̬�ֶ���Ϣ (PMS449)
                    $sql = "update oa_esm_project set statusName = '�쳣�ر�',contractStatus = 7,updateTime = '".date("Y-m-d H:i:s")."',updateId = '{$_SESSION['USER_ID']}',updatename = '{$_SESSION['USERNAME']}' where contractId ='" . $objId . "'";
                    $this->query($sql);
                    $sql = "update oa_contract_project set contractstatus = 7,updateTime = '".date("Y-m-d H:i:s")."',updateId = '{$_SESSION['USER_ID']}',updatename = '{$_SESSION['USERNAME']}' where contractId ='" . $objId . "'";
                    $this->query($sql);

                    //�����ͬȷ������
                    $conFirmArr['type'] = '�쳣�ر�';
                    $conFirmArr['money'] = $contract['contractMoney'];
                    $conFirmArr['state'] = 'δȷ��';
                    $conFirmArr['contractId'] = $contract['id'];
                    $conFirmArr['contractCode'] = $contract['contractCode'];
                    $confirmDao = new model_contract_contract_confirm();
                    $confirmDao->add_d($conFirmArr);

                    // ִ�й켣��¼
                    $trackArr['contractId'] = $objId;$trackArr['modelName'] = 'contractClose';
                    $trackArr['operationName'] = '��ͬ�쳣�ر�';$trackArr['result'] = 7;
                    $trackArr['time'] = date("Y-m-d H:i:s");$trackArr['expand2'] = 'oa_contract_contract_contract:confirmClose_d';
                    $this->addTracksRecord($trackArr);

                    //��ȡĬ�Ϸ�����
                    include(WEB_TOR . "model/common/mailConfig.php");
                    if (!empty ($mailUser['contractClose']['TO_ID'])) {
                        $addmsg = "��ͬ ��" . $contract['contractCode'] . " ���쳣�ر�����������ͨ��<br/>�ر���Ϣ ��" . $contract['closeRegard'] . "";
                        $emailDao = new model_common_mail();
                        $emailDao->batchEmail('1', $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'contractClose', '����ͨ��', '', $mailUser['contractClose']['TO_ID'], $addmsg);
                    }
                }
            }

            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
        }
    }

    //�ʼ���Ĳ�Ʒ��Ϣ����
    function conProductFun($objId)
    {
        $sql = "select p.conProductName,c.id,c.contractCode,c.prinvipalName,p.conProductDes,p.number
					 			from oa_contract_product p left join oa_contract_contract c on c.id = p.contractId where p.contractId=" . $objId . "";
        $rows = $this->_db->getArray($sql);
        $str = '';
        //������Ϣ����
        $mainInfo = $rows[0];
        $str .= '��ͬ���:<font color=red> ' . $mainInfo['contractCode'] . '</font>  ��ͬ������:' . $mainInfo['prinvipalName'] . '';

        if (is_array($rows)) {
            //�ӱ���Ϣ����
            $str .= '<table border=1 cellspacing=0  width=80% bordercolorlight=#333333 bordercolordark=#efefef style="font-size:14"><tr><td>���</td><td>��Ʒ����</td><td>��Ʒ����</td><td>����</td></tr>';

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
     * ȷ�Ϻ�ͬ
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
                if ($contract['ExaStatus'] == "���") {
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
     * ����ID ��ȡ��ͬȫ����Ϣ
     * $contractId : ��ͬID
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('linkman','product') Ĭ��Ϊ�� ȡȫ��
     *       linkman-��ϵ�� prodcut-��Ʒ  equ-����
     *       invoice-��Ʊ income-���� trainListInfo-��ѵ
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
     * ����ID ��ȡ��ͬȫ����Ϣ
     * $contractId : ��ͬID
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('linkman','product') Ĭ��Ϊ�� ȡȫ��
     *       linkman-��ϵ�� prodcut-��Ʒ  equ-����
     *       invoice-��Ʊ income-���� trainListInfo-��ѵ
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
     * ����ID ��ȡ��ͬȫ����Ϣ(����ɾ���ļ�¼)
     * $contractId : ��ͬID
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('linkman','product') Ĭ��Ϊ�� ȡȫ��
     *       linkman-��ϵ�� prodcut-��Ʒ  equ-����
     *       invoice-��Ʊ income-���� trainListInfo-��ѵ
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
     * ���ݺ�ͬ��� ��ȡ��ͬȫ����Ϣ
     * $contractCode : ��ͬ���
     * $getInfoArr ��Ҫ�Ĵӱ���Ϣ ��:$getInfoArr = array('linkman','product') Ĭ��Ϊ�� ȡȫ��
     *       linkman-��ϵ�� prodcut-��Ʒ  equ-����
     *       invoice-��Ʊ income-���� trainListInfo-��ѵ
     * $getInfoArr = "main" ֻ��ȡ������Ϣ
     */
    function getContractInfoByCode($contractCode, $getInfoArr = null)
    {
        $sql = "select id from oa_contract_contract where contractCode='$contractCode' and isTemp = 0 and ExaStatus in ('���','���������')";
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
     * �жϵ�ǰ��¼���Ƿ��ͬ������,������,��������
     * ����Ȩ�޹���
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
     * ��ͬǩ��
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
            //����������
            $object = $this->handlePaymentData($object);
            $changeLogDao = new model_common_changeLog('contractSign', false);
            //���OldId
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
            //�����¼,�õ��������ʱ������id
            $changeLogDao->addLog($object);
            //���������ֵ��ֶ�
            $datadictDao = new model_system_datadict_datadict();
            $object['contractNatureName'] = $datadictDao->getDataNameByCode($object['contractNature']);
            $object['signContractTypeName'] = $datadictDao->getDataNameByCode($object['signContractType']);
            $object['invoiceTypeName'] = $datadictDao->getDataNameByCode($object['invoiceType']);
            $object['customerTypeName'] = $datadictDao->getDataNameByCode($object['customerType']);
            $object['moduleName'] = $datadictDao->getDataNameByCode($object['module']);

            //���Ҵ��� ��ѡ��Ʒ�� �������
            $goodsTypeIds = "";
            foreach ($object['product'] as $k => $v) {
                if ($v['isDelTag'] != "1") {
                    $goodsTypeIds .= $v['conProductId'] . ",";
                }
            }
            $goodsTypeIds = rtrim($goodsTypeIds, ',');
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //��һ�β��ң����˳������Ѿ��������������
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
                //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
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
            //��Ʒ��
            $sqlf = "select exeDeptCode from oa_goods_base_info where id in ($goodsTypeIds)";
            $exeDeptNameArr = $this->_db->getArray($sqlf);
            $exeDeptNameStr = "";
            foreach ($exeDeptNameArr as $k => $v) {
                $exeDeptNameStr .= $v['exeDeptCode'] . ",";
            }
            $object['productLineStr'] = $exeDeptNameStr;
            //�ж��Ƿ���Ҫ�ߵ�ִ�в�����������ʾ�ֶ�
            $isSellArr = explode(",", $goodsTypeStr);
            if (in_array(isSell, $isSellArr)) {
                $object['isSell'] = "1";
            } else {
                $object['isSell'] = "0";
            }

            //�޸�������Ϣ
            $object['id'] = $object['oldId'];
            $object['signStatus'] = '1';
            $object['signinDate'] = date('Y-m-d');
            parent :: edit_d($object, true);

            // ִ�й켣��¼
            $trackArr['contractId'] = $object['id'];$trackArr['modelName'] = 'contractSignIn';
            $trackArr['operationName'] = 'ǩ��ֽ�ʺ�ͬ';$trackArr['result'] = $object['signStatus'];
            $trackArr['time'] = $object['signinDate'];$trackArr['expand2'] = 'oa_contract_contract_contract:signin_d';
            $this->addTracksRecord($trackArr);

            $orderId = $object['oldId'];


            //����ӱ���Ϣ
            //�ͻ���ϵ��
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
            //�豸

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
            //��Ʊ��Ϣ
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
            //�տ�ƻ�
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
            //��ѵ�ƻ�
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

            //�жϺ�ͬ�Ƿ���Ҫ�ر�
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
     * ��ͬ��Ϣ�б�ͳ�ƽ��
     */
    function getRowsallMoney_d($rows, $selectSql, $needFilt = false )
    {
        if($needFilt){

            // PMS 522 ��ͬӦ�տ�����������ô���
            $filtCustomerTypes = $this->dealSpecRecordsForNoSurincome();
            $this->searchArr['customerTypeNotIn'] = implode(",",$filtCustomerTypes);
        }

        //��ѯ��¼�ϼ�
        $objArr = $this->listBySqlId($selectSql . '_sumMoney');
        if (is_array($objArr)) {
            $rsArr = $objArr[0];
            $rsArr['thisAreaName'] = '�ϼ�';
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
     * ���ݺ�ͬҵ���Ż�ȡ��ͬ��Ϣ
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
     * �ӳٷ�������
     */
    function inform_d($inform)
    {
        try {
            $this->start_d();
            //�����ӳٷ���״̬
            $sql = "update oa_contract_contract set shipCondition = '0' where id=" . $inform['contractId'] . " ";
            $this->_db->query($sql);
            $addmsg = "�ӳٷ����ĺ�ͬ����Ҫ������ <br/>";
            $addmsgInit = $this->conProductFun($inform['contractId']);
            $addmsg .= $addmsgInit;
            //��ȡĬ�Ϸ�����
            include(WEB_TOR . "model/common/mailConfig.php");
            if (!empty ($mailUser['shipconditon']['sendUserId'])) {
                $emailDao = new model_common_mail();
                $emailInfo = $emailDao->batchEmail('1', $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'shipconditon', '���ͷ���֪ͨ', '', $mailUser['shipconditon']['sendUserId'], $addmsg);
            }
            $this->commit_d();
            return 1;
        } catch (Exception $e) {
            $this->rollBack();
            return 0;
        }

    }

    /**
     * �ı��ͬ״̬
     */
    function completeOrder_d($orderId)
    {
        $sql = "update oa_contract_contract set state = 4,completeDate=now() where id=" . $orderId . " ";
        $this->query($sql);

        // ִ�й켣��¼
        $trackArr['contractId'] = $orderId;$trackArr['modelName'] = 'contractComplete';
        $trackArr['operationName'] = '��ִͬ�н���';$trackArr['result'] = 4;
        $trackArr['time'] = now();$trackArr['expand2'] = 'oa_contract_contract_contract:completeOrder_d';
        $this->addTracksRecord($trackArr);

        //�жϺ�ͬ�Ƿ���Ҫ�ر�
        $this->updateContractClose($orderId);

    }

    function exeOrder_d($orderId)
    {
        $sql = "update oa_contract_contract set state = 2 where id=" . $orderId . " ";
        $this->query($sql);
    }

    /**
     * ��ȡ��һ���汾��ͬ��Ϣ
     */
    function getLastContractInfo_d($conId)
    {
        //��ȡ���������ж��ٸ�����
        $sql = "select count(id) as num from oa_contract_contract where originalId=$conId and ExaStatus='���' ";
        $num = $this->queryCount($sql);
        if ($num == 0) { //û�б����
            return false;
        } else
            if ($num == 2) { //�����һ�Σ���һ�λ���ȷ�ϻ����һ��ԭʼ�汾��Ϣ+��ʱ��¼��Ϊ������
                //ȡ��ԭʼ�汾
                $sql = "select max(id) as num from oa_contract_contract where originalId=$conId and ExaStatus='���' ";
                $oldId = $this->queryCount($sql);
            } else {
                //ȡ����һ���汾
                $sql = "select max(id) as num from oa_contract_contract where id!=" .
                    "(select max(id)  from oa_contract_contract " .
                    "where originalId=$conId and ExaStatus='���') " .
                    "and originalId=$conId and ExaStatus='���'";
                $oldId = $this->queryCount($sql);
            }
        return parent :: get_d($oldId);
    }

    //////////����Ϊ��������////////////////
    /**
     * ����������豸��Ϣ
     */
    function showDetaiInfo($rows)
    {
        $equDao = new model_contract_contract_equ();
        $equs = $equDao->getLockEqus($rows['id'], null);
        $rows['orderequ'] = $equDao->showLockEqusByContract($equs);
        return $rows;
    }

    /********************************************************�������******************************************************************************/

    /**
     * ���ݺ�ͬid�޸ĺ�ͬ�������ƻ�״̬
     */
    function updateOutStatus_d($id)
    {
        $orderRemainSql = "select count(0) as countNum,(select sum(o.executedNum) from oa_contract_equ o where o.contractId=" . $id . " and o.isTemp=0 and o.isDel=0) as executeNum
										 from (select e.contractId,(e.number-e.executedNum + e.exchangeBackNum) as remainNum from oa_contract_equ e
										where e.contractId=" . $id . " and e.isTemp=0 and e.isDel=0) c where c.remainNum>0";
        $remainNum = $this->_db->getArray($orderRemainSql);
        if ($remainNum[0]['countNum'] <= 0) { //�ѷ���
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'YFH',
                'outstockDate' => day_date
            );
        } elseif ($remainNum[0]['countNum'] > 0 && $remainNum[0]['executeNum'] == 0) { //δ����
            $statusInfo = array(
                'id' => $id,
                'DeliveryStatus' => 'WFH',
                'completeDate' => '',
                'outstockDate' => ''
            );

        } else { //���ַ���
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

        // �����������ͬ�������Ϣ���� PMS 607
        $this->updateOtherSalesPlanEndDate($id);
        return 0;
    }

    /**
     * ���ݺ�ͬID,�������к��иú�ͬ�ġ���Լ��֤���������ͬ����ĿԤ�ƽ���ʱ�� PMS 607
     * @param $cid
     */
    function updateOtherSalesPlanEndDate($cid){
        $esmDao = new model_engineering_project_esmproject();
        $esmDao->getParam(array("contractId" => $cid)); //����ǰ̨��ȡ�Ĳ�����Ϣ
        $rows = $esmDao->pageBySqlId('select_defaultAndFee');
        $maxPlanEndDate = '';

        // ��ȡ�ú�ͬ�������Ŀ�������ڡ�������Ŀ��Ԥ�ƽ�������,��Ʒ��Ŀ��Ԥ�ƽ������ڡ�
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

        // ���¹����������������ͬ
        if(!empty($maxPlanEndDate)){
            $updateSql = "update oa_sale_other set projectPrefEndDate = '{$maxPlanEndDate}' where payForBusiness = 'FKYWLX-04' and contractId = '{$cid}';";
            $this->query($updateSql);
        }
    }

    /**
     * �ı䷢��״̬ --- �ر�
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
     * ���ݷ�������޸ĺ�ͬ�������ƻ�״̬
     */
    function updateShipStatus_d($id)
    {
        $orderRemainSql = "select count(0) as equCount,sum(IF (c.remainNum>0,1,0)) AS countNum,
						(select sum(o.issuedShipNum) from oa_contract_equ o where o.contractId=" . $id . " and o.isTemp=0 and o.isDel=0) as issuedShipNum
						from (select e.contractId,(e.number-e.issuedShipNum + e.backNum) as remainNum from oa_contract_equ e
						where e.contractId=" . $id . " and e.isTemp=0 and e.isDel=0) c where 1=1 ";
        $remainNum = $this->_db->getArray($orderRemainSql);
        if ($remainNum[0]['countNum'] <= 0) { //�ѷ���
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'YXD'
            );
        } elseif ($remainNum[0]['countNum'] > 0 && $remainNum[0]['issuedShipNum'] == 0) { //δ����
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'WXD'
            );
        } else { //���ַ���
            $statusInfo = array(
                'id' => $id,
                'makeStatus' => 'BFXD'
            );
        }
        $this->updateById($statusInfo);
        return 0;
    }

    //�Ƿ�
    function rtYesOrNo_d($value)
    {
        if ($value == 1) {
            return '��';
        } else {
            return '��';
        }
    }

    /****************************  S ������Ŀ��ӷ���**************************/
    /**
     * �����ͬ��ȡ���´�Ȩ��
     */
    function getProvinceNames_d($limitArr)
    {
        $officeIdArr = array();
        $provinceArr = array();

        if (!empty ($limitArr['���´�'])) {
            array_push($officeIdArr, $limitArr['���´�']);
        }

        //��ȡ���´���id
        $officeInfoDao = new model_engineering_officeinfo_officeinfo();
        $officeIds = $officeInfoDao->getOfficeIds_d($_SESSION['USER_ID']);
        if (!empty ($officeIds)) {
            array_push($officeIdArr, $officeIds);
        }

        //������صİ��´�id��Ϊ�գ������ʡ������
        if (!empty ($officeIdArr)) {
            $officeIds = implode($officeIdArr, ',');
            $proNames = $officeInfoDao->getProNamesByIds_d($officeIds);
            array_push($provinceArr, $proNames);
        }

        //Ȩ��ϵͳʡ��Ȩ��
        if (isset($limitArr['ʡ��Ȩ��']) && $limitArr['ʡ��Ȩ��']) {
            array_push($provinceArr, $limitArr['ʡ��Ȩ��']);
        }

        //��ȡ����ʡ��Ȩ��
        $provinceDao = new model_system_procity_province();
        $provinceLimit = $provinceDao->getProvinces_d($_SESSION['USER_ID']);
        if ($provinceLimit) {
            array_push($provinceArr, $provinceLimit);
        }
        //������ʡ�ݲ���
        $newProNames = implode(array_unique(explode(',', implode($provinceArr, ','))), ',');

        return $newProNames;
    }

    /**************************** E ������Ŀ��ӷ���**************************/
    /**
     *�����ɹ����ڸ����б������Ϣ
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
            //��������
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

            // �ʼ�֪ͨ���¸�����
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
     * �������������Ϣ
     */
    function stamp_d($obj)
    {
        $stampDao = new model_contract_stamp_stamp();
        try {
            $this->start_d();

            //��ȡ��Ӧ�����������κ�
            $maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name, " contractType = 'HTGZYD-01' and contractId=" . $obj['contractId'], "max(batchNo)");
            $obj['batchNo'] = $maxBatchNo + 1;

            //����������Ϣ
            $obj['contractType'] = 'HTGZYD-04';
            $stampDao->addStamps_d($obj, true);

            //���º�ͬ�ֶ���Ϣ
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
     * ���¾ͺ�ͬ�������º�ͬ
     */
    function updateAdd($object, $conType)
    {
        try {
            $this->start_d();
            //����������Ϣ
            if (!empty ($object['info'])) {
                $linkArr = array();
                foreach ($object['info'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $contractCode = $val['contractCode'];
                    $oldContractType = $val['oldContractType'];
                    $contractType = $val['contractType'];
                    $oldObjcode = $val['objCode'];
                    $val['dealStatus'] = 1; //����״̬�������Ϊ1
                    $oldTableName = $val['oldTableName'];
                    //�����ͬ
                    $newId = parent :: add_d($val);
                    //�����м������
                    $sql = "INSERT INTO oa_contract_initialize(contractId,contractCode,contractType,oldContractId,oldContractType,oldObjCode,oldTableName) VALUES ('$newId','$contractCode','$contractType','$oldId','$oldContractType','$oldObjcode','$oldTableName')";
                    $this->query($sql);

                    //����ȷ��������
                    $linkdao = new model_contract_contract_contequlink();
                    $link = array(
                        "contractId" => $newId,
                        "rObjCode" => $val['objCode'],
                        "contractCode" => $val['contractCode'],
                        "contractName" => $val['contractName'],
                        "contractType" => "oa_contract_contract",
                        "ExaStatus" => '���',
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
                    $linkArr[$oldId] = $linkdao->create($link); //����linkId
                }
            }
            //����ӱ���Ϣ
            //�ͻ���ϵ��
            if (!empty ($object['linkman'])) {
                foreach ($object['linkman'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $linkmanDao = new model_contract_contract_linkman();
                    $newId = $linkmanDao->add_d($val);
                    //�����м������
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_linkman')";
                    $this->query($sql);
                }
                //���´ӱ�������Ĺ�����ϵ
                $this->updateFromToList("oa_contract_linkman", $conType, $tablename);
            }
            //			//����
            if (!empty ($object['orderequ'])) {
                foreach ($object['orderequ'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $val['linkId'] = $linkArr[$oldorderId];
                    $equDao = new model_contract_contract_equ();
                    $newId = $equDao->add_d($val);
                    //�����м������
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_equ')";
                    $this->query($sql);
                }
                //���´ӱ�������Ĺ�����ϵ
                $this->updateFromToList("oa_contract_equ", $conType, $tablename);
            }
            //			//��Ʊ�ƻ�
            if (!empty ($object['invoice'])) {
                foreach ($object['invoice'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $orderInvoiceDao = new model_contract_contract_invoice();
                    $newId = $orderInvoiceDao->add_d($val);
                    //�����м������
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_invoice')";
                    $this->query($sql);
                }
                //���´ӱ�������Ĺ�����ϵ
                $this->updateFromToList("oa_contract_invoice", $conType, $tablename);
            }
            //			//�տ�ƻ�
            if (!empty ($object['receiptplan'])) {
                foreach ($object['receiptplan'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $orderReceiptplanDao = new model_contract_contract_receiptplan();
                    $newId = $orderReceiptplanDao->add_d($val);
                    //�����м������
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_receiptplan')";
                    $this->query($sql);
                }
                //���´ӱ�������Ĺ�����ϵ
                $this->updateFromToList("oa_contract_receiptplan", $conType, $tablename);
            }
            //			//��ѵ�ƻ�
            if (!empty ($object['trainingplan'])) {
                foreach ($object['trainingplan'] as $key => $val) {
                    $oldId = $val['oldId'];
                    $oldorderId = $val['oldorderId'];
                    $tablename = $val['tablename'];
                    $orderTrainingplanDao = new model_contract_contract_trainingplan();
                    $newId = $orderTrainingplanDao->add_d($val);
                    //�����м������
                    $sql = "INSERT INTO oa_contract_initialize_from(fromId,oldfromId,oldcontractId,fromType,toTable) VALUES ('$newId','$oldId','$oldorderId','$tablename','oa_contract_trainingplan')";
                    $this->query($sql);
                }
                //���´ӱ�������Ĺ�����ϵ
                $this->updateFromToList("oa_contract_trainingplan", $conType, $tablename);
            }

            //���� ��ͬ���������ڲ��� ��  ��Ʊ���� ���ͻ���������
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
     * ����������ӱ�Ĺ�����ϵ
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
    /**************************************************��ͬ�豸ͳ�Ʋ��� start****************************************************/
    /**
     * �ɹ��豸-�ƻ�����
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

    /**�ɹ�����-�ɹ��ƻ�-�豸�嵥��ʾģ��
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
				        	<div class="readThisTable"><����չ�����Ͼ�����Ϣ></div>
				        </td>
				    </tr>
EOT;
            }
        } else {
            $str = "<tr align='center'><td colspan='50'>���������Ϣ</td></tr>";
        }
        return $str;
    }

    /**
     * ��ͬ�豸�ܻ� ��ҳ
     * 2011��10��19�� 16:24:57
     */
    function getPagePlan($sql)
    {
        $sql = $this->sql_arr[$sql];
        $countsql = "select count(0) as num " . substr($sql, strpos($sql, "from"));
        $countsql = $this->createQuery($countsql, $this->searchArr);
        $this->count = $this->queryCount($countsql);
        //ƴװ��������
        $sql = $this->createQuery($sql, $this->searchArr);
        //print($sql);
        //����������Ϣ
        $asc = $this->asc ? "DESC" : "ASC";
        //echo $this->asc;
        $sql .= " order by " . $this->sort . " " . $asc;
        //������ȡ��¼��
        $sql .= " limit " . $this->start . "," . $this->pageSize;
        //		echo $sql;
        return $this->_db->getArray($sql);
    }
    /**************************************************��ͬ�豸ͳ�Ʋ��� end***************************************/

    /*****************************************************************************************************************/
    /**
     * �ϴ�EXCEl������������
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
            spl_autoload_register('__autoload'); //�ı������ķ�ʽ
            if ($excelData) {
                $objectArr = array();
                foreach ($excelData as $rNum => $row) {
                    foreach ($objNameArr as $index => $fieldName) {
                        $objectArr[$rNum][$fieldName] = $row[$index];
                    }
                }
                $arrinfo = array(); //������
                foreach ($objectArr as $k => $v) {
                    //                	 //�жϺ�ͬ�Ƿ����
                    $isBe = $this->findContract($v['contractCode'], $normType);
                    if (empty ($isBe)) {
                        array_push($arrinfo, array(
                            "docCode" => $v['contractCode'],
                            "result" => "����ʧ��,��ͬ��Ϣ������"
                        ));
                    } else {
                        if (count($isBe) > 1) {
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "����ʧ��,��ͬ���ظ���ʹ��ҵ���ŵ���"
                            ));
                        } else {
                            //���º�ͬ��Ϣ
                            $this->updateConInfo($isBe[0]['id'], $isBe[0]['contractType'], $importInfo, $v);
                            $this->updateGross($isBe[0]['id'], $isBe[0]['contractType']); //���� ë����ë����,����ȷ�������룬����ȷ���ܳɱ������ࣩ
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "����ɹ���"
                            ));
                        }
                    }
                }
                if ($arrinfo) {
                    echo util_excelUtil :: finalceResult($arrinfo, "������", array(
                        "��ͬ���",
                        "���"
                    ));
                }
            } else {
                echo "�ļ������ڿ�ʶ������!";
            }
        } else {
            echo "�ϴ��ļ����Ͳ���EXCEL!";
        }

    }

    /**
     * ������루���µ������룩
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
            spl_autoload_register('__autoload'); //�ı������ķ�ʽ
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
                $arrinfo = array(); //������
                foreach ($objectArr as $k => $v) {
                    //�ϲ���Ϣ
                    $objectArr = array_merge($v, $infoArr);
                    //�жϺ�ͬ�Ƿ����
                    $isBe = $this->findContract($v['contractCode'], $normType);
                    if (empty ($isBe)) {
                        array_push($arrinfo, array(
                            "docCode" => $v['contractCode'],
                            "result" => "����ʧ��,��ͬ��Ϣ������"
                        ));
                    } else {
                        if (count($isBe) > 1) {
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "����ʧ��,��ͬ���ظ���ʹ��ҵ���ŵ���"
                            ));
                        } else {
                            //��ӵ�����Ϣ
                            $this->addfinancialInfo($objectArr, $isBe);
                            $this->updateGross($isBe[0]['id'], $isBe[0]['contractType']); //���� ë����ë����,����ȷ�������룬����ȷ���ܳɱ������ࣩ
                            array_push($arrinfo, array(
                                "docCode" => $v['contractCode'],
                                "result" => "����ɹ���"
                            ));
                        }
                    }
                }
                if ($arrinfo) {
                    echo util_excelUtil :: finalceResult($arrinfo, "������", array(
                        "��ͬ���",
                        "���"
                    ));
                }
            } else {
                echo "�ļ������ڿ�ʶ������!";
            }
        } else {
            echo "�ϴ��ļ����Ͳ���EXCEL!";
        }

    }

    /*
	 * �жϺ�ͬ�Ƿ����
	 */
    function findContract($contractCode, $normType)
    {
        if ($normType == "��ͬ��") {
            $sql = "select id,contractType from oa_contract_contract where contractCode = '" . $contractCode . "' and isTemp=0";
        } else {
            $sql = "select id,contractType from oa_contract_contract where objCode = '" . $contractCode . "' and isTemp=0";
        }
        $cId = $this->_db->getArray($sql);
        return $cId;
    }

    /*
	 * ���º�ͬ��Ϣ
	 */
    function updateConInfo($cId, $talbeName, $importInfo, $row)
    {
        if ($importInfo == "deductMoney") {
            //�����˿���ۼӣ�
            $updatedeductMoneySql = "update oa_contract_contract set deductMoney = deductMoney+" . $row['money'] . " where id=" . $cId . "";
            $this->query($updatedeductMoneySql);
        } else {
            //���½����Ϣ
            $updateMoneySql = "update oa_contract_contract  set $importInfo=" . $row['money'] . " where id=" . $cId . "";
            $this->query($updateMoneySql);
        }

    }

    /*
	 * ������루���µ������룩 ��ӷ���
	 */
    function addfinancialInfo($row, $conInfo)
    {
        //�ж������Ƿ�Ϊ�ظ����������
        $findSql = "select id from oa_contract_finalceMoney where contractId='" . $conInfo[0]['id'] . "' and importMonth='" . $row['importMonth'] . "' and moneyType='" . $row['moneyType'] . "'";
        $findId = $this->_db->getArray($findSql);
        if (!empty ($findId)) {
            //����ʷ�������ݵ� ʹ�ñ�־λ��Ϊ 1
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
     * ���Ұ��µ���������Ƿ��ظ�����
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
     * ���ݺ�ͬID���������ֶΣ�ë����ë���ʣ�����ȷ�������룬����ȷ���ܳɱ���
     */
    function updateGross($cId, $contractType)
    {
        //�������ȷ���ܳɱ�������ȷ��������
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
        $serviceconfirmMoney = $infoArr[0]['serviceconfirmMoney']; //����ȷ��������
        $financeconfirmMoney = $infoArr[0]['financeconfirmMoney']; //����ȷ���ܳɱ�
        $deliveryCosts = $infoArr[0]['deliveryCosts']; //�����ɱ�
        $deductMoney = $infoArr[0]['deductMoney']; //�ۿ���
        $trialprojectCost = $infoArr[0]['trialprojectCost'];
        //����ë����ë����
        $gross = round($serviceconfirmMoney - $financeconfirmMoney, 2);
        $rateOfGrossTemp = bcdiv($gross, $serviceconfirmMoney, 8);
        $rateOfGross = round(bcmul($rateOfGrossTemp, '100', 8),2);
        //��������ֵ
        $updateSql = "update oa_contract_contract set gross=" . $gross . ",rateOfGross=" . $rateOfGross . ",serviceconfirmMoneyAll=" . $serviceconfirmMoney . ",financeconfirmMoneyAll=" . $financeconfirmMoney . ",deliveryCostsAll=" . $deliveryCosts . ",trialprojectCostAll =" . $trialprojectCost . " where id=" . $cId . "";
        $this->query($updateSql);
    }

    /**
     * ��ȡ����������ϸ��Ϣ
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
        //��ʼ�����
        $initSql = "select " . $moneyType . " from oa_contract_contract where id=" . $conId . "";
        $initMoney = $this->_db->getArray($initSql);
        $initarr = array(
            "year" => "��ʼ�����",
            "January" => $initMoney[0][$moneyType]
        );
        $rows[] = $initarr;
        return $rows;
    }

    function getFinancialImportDetailInfo($conId, $tablename, $moneyType)
    {
        $sql = "select concat(LEFT(c.importMonth,4),'��',RIGHT(c.importMonth,2),'��') as improtMonth,
								       (case c.moneyType when 'serviceconfirmMoney' then '����ȷ������'
								                         when 'financeconfirmMoney' then '����ȷ���ܳɱ�'
								                         when 'deliveryCosts' then '�����ɱ�'
											             when 'deductMoney' then '�ۿ���'  end)  as moneyType,
								       c.moneyNum,c.moneyNum,c.importDate,c.importName,c.isUse
								  from oa_contract_finalcemoney c  where c.contractId=" . $conId . "  and moneyType='" . $moneyType . "'";
        //		$rows = $this->_db->getArray($sql);
        return $sql;
    }

    /**
     * ������ͳ���б� ��ȡ���ݷ���
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

    //�������Ǹ������� ��ʼ�����
    function getfinancialStatisticsInitMoney($rows)
    {
        //��ʼ�����
        foreach ($rows as $key => $val) {
            $moneyType = $val['moneyType'];
            $initSql = "select " . $moneyType . " from oa_contract_contract where contractCode='" . $val['contractCode'] . "'";
            $initMoney = $this->_db->getArray($initSql);
            $rows[$key]["initMoney"] = $initMoney[0][$moneyType];
        }
        return $rows;
    }

    /**
     * ��ȡ��ͬ����켣
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
            // ��ȡ�ڵ���ȼ�¼���� ID2243
            $beginTrack = $conTrackDao->getRecord($contractId,'contractBegin','max');
            $begin_exePortion = ($beginTrack['msg'] == 'ok')? $beginTrack['data']['exePortion']: '';
            //��ͬ��ʼִ��
            $trackArr[] = array(
                "time" => $contract['ExaDTOne'],
                "exePortion" => $begin_exePortion,
                "contractBegin" => 1,
                "sort" => 10
            );
        }
        if (checkDateT($contract['completeDate'])) {
            // ��ȡ�ڵ���ȼ�¼���� ID2243
            $completeTrack = $conTrackDao->getRecord($contractId,'contractComplete','max');
            $complete_exePortion = ($completeTrack['msg'] == 'ok')? $completeTrack['data']['exePortion']: '';
            //��ִͬ�н���
            $trackArr[] = array(
                "time" => $contract['completeDate'],
                "exePortion" => $complete_exePortion,
                "completeDate" => 1,
                "sort" => 70
            );
        }
        if (checkDateT($contract['signinDate'])) {
            // ��ȡ�ڵ���ȼ�¼���� ID2243
            $signinTrack = $conTrackDao->getRecord($contractId,'contractSignIn','max');
            $signin_exePortion = ($signinTrack['msg'] == 'ok')? $signinTrack['data']['exePortion']: '';
            //ǩ��ֽ�ʺ�ͬ
            $trackArr[] = array(
                "time" => $contract['signinDate'],
                "exePortion" => $signin_exePortion,
                "signinDate" => 1,
                "sort" => 60
            );
        }
        if (checkDateT($contract['closeTime'])) {
            // ��ȡ�ڵ���ȼ�¼���� ID2243
            $closeTrack = $conTrackDao->getRecord($contractId,'contractClose','max');
            $close_exePortion = ($closeTrack['msg'] == 'ok')? $closeTrack['data']['exePortion']: '';
            //��ͬ�ر�
            $trackArr[] = array(
                "time" => substr($contract['closeTime'],
                    0,
                    10
                ), "closeTime" => 1, "exePortion" => $close_exePortion,"sort" => 80);
        }
        $contractMoney = $contract['contractMoney'] - $contract['deductMoney'] - $contract['badMoney']; //��ȥ�ۿ���˵ĺ�ͬ���

        //��ȡ���п�Ʊ��¼
        $invoiceDao = new model_finance_invoice_invoice();
        $invoices = $invoiceDao->getInvoices_d($contractId, "KPRK-12");
        $allInvoiceMoney = 0;
        $i = 20;
        // ��ȡ�ڵ���ȼ�¼���� ID2243
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

        //��ȡ���е����¼
        $incomeDao = new model_finance_income_incomeAllot();
        $incomes = $incomeDao->getIncomes_d($contractId, "KPRK-12");
        $j = 40;
        $allIncomeMoney = "";

        // ��ȡ�ڵ���ȼ�¼���� ID2243
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

    /*************����Ϊportlet�����Ϣ******************************/
    /**
     * ��������ĺ�ͬ
     */
    function getLastAddContractNum()
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='���' and isTemp=0 and TO_DAYS(NOW()) - TO_DAYS(createTime) <= 15";
        return $this->queryCount($sql);
    }

    /**
     * �������ĺ�ͬ
     */
    function getLastChangeContractNum()
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='���' and isTemp=0 and TO_DAYS(NOW()) - TO_DAYS(ExaDT) <= 15 and becomeNum>0";
        return $this->queryCount($sql);
    }

    /**
     * ǩԼһ����δ��ɺ�ͬ
     */
    function getMonthContractNum($month)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where signStatus='1' and isTemp=0 and TO_DAYS(NOW()) - TO_DAYS(signDate) between $month*30 and ($month+1)*30";
        return $this->queryCount($sql);
    }

    /**
     * δִ�еķ�������
     */
    function getNotRunShipNum($condition = null)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='���' and isTemp=0 and DeliveryStatus='WFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * ִ���еķ�������
     */
    function getRunningShipNum($condition = null)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='���' and isTemp=0 and DeliveryStatus='BFFH' " . $condition;
        return $this->queryCount($sql);
    }

    /**
     * �Ǽ��ķ�������
     */
    function getReterStarShipNum()
    {
        $sql = "SELECT COUNT(*) as number,reterStart FROM oa_contract_contract where ExaStatus='���'
						and isTemp=0 GROUP BY reterStart";
        return $this->listBySql($sql);
    }

    /**
     * ���ڵķ�������
     */
    function getdelayShipNum($condition)
    {
        $sql = "select count(id) as num from oa_contract_contract " .
            "where ExaStatus='���' and isTemp=0 and DeliveryStatus='BFFH'  and TO_DAYS(NOW()) - TO_DAYS(signDate) > 0" . $condition;
        return $this->queryCount($sql);
    }

    /**
     * portlet��������ҳ����Ⱦ
     */
    function showCountShipPage_d()
    {
        $str = ""; //���ص�ģ���ַ���
        $shipTypeDao = new model_projectmanagent_shipment_shipmenttype();
        $typeArr = $shipTypeDao->list_d();
        $notRunShipNum = $this->getNotRunShipNum();
        $runningShipNum = $this->getRunningShipNum();
        $reterStarNumArr = $this->getReterStarShipNum();
        $delayNum = $this->getdelayShipNum(null);
        $NotRunShipTypeStr = "";
        $runningShipTypeStr = "";
        $starTypeStr = "";
        //δִ�еķ�������
        if (is_array($typeArr) && count($typeArr) > 0) {
            $NotRunShipTypeStr = '<ul>';
            $runningShipTypeStr = '<ul>';
            foreach ($typeArr as $key => $val) {
                $number = $this->getNotRunShipNum('and customTypeId=' . $val['id']);
                $i = $key + 1;
                $typeId = $val['id'];
                $typeName = $val['type'];
                $NotRunShipTypeStr .= "<li>$i.[$typeName]�෢������<a id='NotRunNumHref_$typeId' href='javascript:void(0)' >
									<span id='notRunShipNum_$typeId'></span>$number</a>����
									<input type='hidden' id='v_notRunShipNum_$typeId' value='$typeId'/></li>";
            }
            $NotRunShipTypeStr .= "</ul>";
            foreach ($typeArr as $key => $val) {
                $number = $this->getRunningShipNum('and customTypeId=' . $val['id']);
                $i = $key + 1;
                $typeId = $val['id'];
                $typeName = $val['type'];
                $runningShipTypeStr .= "<li>$i.[$typeName]�෢������<a id='RunningNumHref_$typeId' href='javascript:void(0)' >
									<span id='runningShipNum_$typeId'></span>$number</a>����
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
            $starTypeStr .= "<li>$k.[$i]�ǵķ�������<a id='starNumHref_$i' href='javascript:void(0)' >
							<span id='starShipNum_$i'></span>$starNum</a>����
							<input type='hidden' id='v_starShipNum_$i' value='$i'/></li>";
        }
        $starTypeStr .= "</ul>";
        $str .= <<<EOT
			<ul>
				<li>һ��δִ�еķ�������<a id="NotRunNumHref" href="javascript:void(0)" ><span id="notRunShipNum">$notRunShipNum</span></a>����
					$NotRunShipTypeStr
				</li>
				<li>����ִ���еķ�������<a id="RunningNumHref" href="javascript:void(0)" ><span id="runningShipNum">$runningShipNum</span></a>����
					$runningShipTypeStr
				</li>
				<li>�������Ǽ�ͳ�Ʒ�������$starTypeStr
				</li>
				<li>�ġ����ڵķ�������<a id="delayNumHref" href="javascript:void(0)" ><span id="delayShipNum">$delayNum</span></a>����
				</li>
			</ul>
EOT;
        return $str;
    }
    /*************portlet�����Ϣ����******************************/

    /**
     * ���ݺ�ͬID  �ı��ͬ״̬
     */
    function updateContractState($contractid)
    {
        try {
            $this->start_d();
            $rows = $this->getContractInfo($contractid);
            if ($rows['ExaStatus'] == '���') {
                $DeliveryStatus = $rows['DeliveryStatus'];
                $contractType = $rows['contractType'];
                $closeType = $rows['closeType'];
                $objCode = $rows['objCode'];
                $date = date("Y-m-d");
                //��ȡ������Ŀ״̬
                $projectStateDao = new model_engineering_project_esmproject();
                $projectState = $projectStateDao->checkIsCloseByRobjcode_d($objCode);
                //�жϺ�ͬ�Ƿ��з�������
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

                if($closeType == "�쳣�ر�"){// ���쳣�ر�����ͨ���ĺ�ͬ����������Ŀ������Ŀ�ٴιرյ�ʱ���Զ�������ͬ״̬��Ϊ�쳣�ر� PMS 2789
                    $state = 7;
                    $updateSql = "update oa_contract_contract set state=" . $state . ",completeDate='" . $date . "' where id=" . $rows['id'] . "";
                }else if ($state == '4') {
                    $updateSql = "update oa_contract_contract set state=" . $state . ",completeDate='" . $date . "',outstockDate='" . $date . "' where id=" . $rows['id'] . "";

                    // ִ�й켣��¼
                    $trackArr['contractId'] = $rows['id'];$trackArr['modelName'] = 'contractComplete';
                    $trackArr['operationName'] = '��ִͬ�н���';$trackArr['result'] = $state;
                    $trackArr['time'] = $date;$trackArr['expand2'] = 'oa_contract_contract_contract:updateContractState';
                    $this->addTracksRecord($trackArr);

                } else {
                    $updateSql = "update oa_contract_contract set state=" . $state . " where id=" . $rows['id'] . "";
                }

                $this->query($updateSql);

                if($closeType != "�쳣�ر�"){
                    //�жϺ�ͬ�Ƿ���Ҫ�ر�
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
     * �Զ��رպ�ͬ�ķ���-����Ԥ��
     */
    function autoContractClose_d($obj)
    {
        return $this->updateContractClose($obj['id'], false); // Ԥ������������������һ����֤
    }

    /**
     * ���ݺ�ͬID �жϺ�ͬ�Ƿ���Ҫ�ر�
     * @param $contractId ��ͬID
     * @param $needCheck �Ƿ���Ҫ��һ����֤��Ĭ��Ϊtrue
     * @return
     */
    function updateContractClose($contractId, $needCheck = true)
    {
        try {
            $this->start_d();

            $isMeet = true; // Ĭ������
            $conArr = $this->get_d($contractId);
            $projectStateDao = new model_engineering_project_esmproject();
            $projectState = $projectStateDao->checkIsCloseByRobjcode_d($conArr['objCode'], true);
            if ($needCheck) {
                $isMeet = $this->isMeetClose($contractId);
            }
            if ($isMeet && ($projectState == '3' || $projectState == '2')) {
                $date = date("Y-m-d");
                $updateSql = "update oa_contract_contract set winRate='100%',state=3,closeTime='" . $date . "',closeType='�����ر�',closeRegard='ϵͳ�Զ��ر�' where id=" . $contractId . "";
                $this->query($updateSql);

                // ִ�й켣��¼
                $trackArr['contractId'] = $contractId;$trackArr['modelName'] = 'contractClose';
                $trackArr['operationName'] = '��ͬ�ر�';$trackArr['result'] = 3;
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
     * ���ݺ�ͬID ��һ���жϺ�ͬ�Ƿ���Թر�
     * ����1����ͬ״̬Ϊ���    c.state = '4'
     * ����2��ǩ��״̬Ϊ��ǩ��  c.signStatus = '1'
     * ����3��δ��Ʊ���Ϊ0  c.contractMoney - c.deductMoney - c.invoiceMoney = 0
     * ����4������δ�տ�Ϊ0  c.invoiceMoney - c.incomeMoney - c.deductMoney - c.badMoney = 0
     * ����5����ͬδ�տ�Ϊ0  c.contractMoney - c.incomeMoney - c.deductMoney - c.badMoney = 0
     * ����6���������6����  DATEDIFF(CURDATE(), o.auditDate) >= 180
     *  //2017-06-23 PMS2603 ����Ϊ��������
     * 1.��ͬ״̬Ϊ���    c.state = '4'
     * 2.δ��Ʊ���Ϊ0  c.contractMoney - c.deductMoney - c.invoiceMoney = 0
     * 3.��ͬδ�տ�Ϊ0  c.contractMoney - c.incomeMoney - c.deductMoney - c.badMoney = 0
     * @param $contractid ��ͬID
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
     * ��ͬ��Ϣ�б���������
     */
    function projectProcess_d($rows)
    {
        foreach ($rows as $key => $val) {
            if ($val['contractType'] == 'HTLX-FWHT') {
                $sql = "select count(id) as equNum from oa_contract_equ where contractId=" . $val['id'] . "";
                $equNum = $this->_db->getArray($sql); //�ж�����
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
     * ���º�ͬ�����ֶΣ����������ȣ�������������ִ�к�ͬ�
     * ����˵��
     * $type = objcode $param Ϊҵ���š�
     * ���� Ĭ�� $param Ϊ�����ʽ����
     * array("id"=>"xx","tablename"=>"xx") idΪ��ͬid contractTypeΪ��ͬ����
     */
    function updateProjectProcess($param, $type = "")
    {
        //����ҵ���Ż�ȡ��ͬ��Ϣ
        if ($type == "objCode") {
            $sql = "select id,contractType,projectProcess,state,contractMoney,deductMoney,objCode from oa_contract_contract where objCode='" . $param . "'";
        } else {
            $sql = "select id,contractType,projectProcess,state,contractMoney,deductMoney,objCode from oa_contract_contract where id='" . $param['id'] . "'";
        }
        $orderInfoArr = $this->_db->getArray($sql);
        $orderInfo = $orderInfoArr[0];

        $sql = "select count(id) as equNum from oa_contract_equ where contractId=" . $orderInfo['id'] . "";
        $tablename = $orderInfo['tablename'];
        $equNum = $this->_db->getArray($sql); //�ж�����
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
        //��������ֵ
        $updateSql = "update oa_contract_contract set projectProcessAll=" . $processE . ",processMoney=" . $processMoney . " where id=" . $orderInfo['id'] . "";
        $this->query($updateSql);

    }

    /**
     * ��ͬ���� add����
     */
    function importAdd_d($object, $isAddInfo = false)
    {
        if ($isAddInfo) {
            $object = $this->addCreateInfo($object);
        }
        //���������ֵ䴦�� add by chengl 2011-05-15
        $newId = $this->create($object);
        return $newId;
    }

    /**
     * ȷ��������㷽ʽ
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
     * �ر�����ȷ��
     */
    function setEmailAfterCloseConfirm($id)
    {
        try {
            $this->start_d();
            $linkDao = new model_contract_contract_contequlink();
            $linkDao->update(array('contractId' => $id), array('ExaStatus' => '���', 'ExaDT' => day_date));
            $addMsg = '�ú�ͬ����������ȷ�ϡ�';
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
            $emailInfo = $emailDao->batchEmail("y", $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'oa_contract_equ', '�ر�', $mainObj['contractCode'], $outmailStr, $addMsg, '1');

            $this->commit_d();
            return 1;
        } catch (exception $e) {
            $this->rollBack();
            return 0;
        }
    }

    /**
     * ���ݺ�ͬid ��ȡ���º�ͬ�����ˣ�����ͨ���������ˣ�
     */
    function contractAppArr($contractId)
    {
        if (!empty($contractId)) {
            $sql = "select f.`User` from wf_task w left join flow_step_partent f on w.task = f.Wf_task_id where w.Pid = '$contractId' and (w.name = '��ͬ����A' or w.name = '��ͬ����B')";
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
     * �б�ע��������
     */
    function listremarkAdd_d($object)
    {
        try {
            $this->start_d();

            $dao = new model_contract_contract_remark();
            $object['content'] = nl2br($object['content']);
            $newId = $dao->add_d($object);
            //��ȡ��ͬ��Ϣ
            $contractArr = $this->get_d($object['contractId']);
            //�����ʼ� ,������Ϊ�ύʱ�ŷ���
            if ($object['issend'] == 'y') {
                $emailDao = new model_common_mail();
                $contractCode = $contractArr['contractCode'];
                $content = $object['content'];
                $msg = "<span style='color:blue'>��ͬ��</span> ��$contractCode<br/><span style='color:blue'>��Ϣ</span> �� $content" .
                    "<br/>�����Ϣ���ڹ�ͨ���ڻظ�";
                $emailInfo = $emailDao->batchEmail($object['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], 'contractInfo', '�����', null, $object['TO_ID'], $msg);
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
     * ��ȡ��ע����
     */
    function getRemarkInfo_d($contractId)
    {
        $sql = "select * from oa_contract_remark where contractId=" . $contractId . "";
        $arr = $this->_db->getArray($sql);
        //��������
        $arrHTML = "";
        foreach ($arr as $k => $v) {
            $arrHTML .= "<b>" . $v['createName'] . "</b>(" . $v['createTime'] . ") : " . $v['content'] . "<br/>";
        }
        return $arrHTML;
    }

    /**
     *��ȡ�б�ע��Ϣ�ĺ�ͬ ����
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
     * �޸� �������ʱ��
     */
    function financialRelatedDateAdd_d($object)
    {
        try {
            $this->start_d();

            //�޸�������Ϣ
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
     * ����������
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
     * ����ȷ�ϳɱ�������Ϣ
     */
    function handleSubConfirmCose($arr, $rows, $type)
    {
        //ȷ����Ϣ
        $contractId = $arr['contractId']; //ȷ�Ϻ�ͬid
        $costId = $arr['id']; //�����������id
        //��������Ϣ����������ֵ
        return $this->handleCost($contractId, $costId);
    }

    /**
     * ����ȷ�ϳɱ�������Ϣ(��)
     */
    function handleSubConfirmCoseNew($contractId, $confirmTag)
    {
        //��������Ϣ����������ֵ
        return $this->handleCostNew($contractId, $confirmTag);
    }

    /**
     * ���ݺ�ͬid ��ȡ��ǰ�̻�����
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
				   <td width='20%'>������Ŀ����</td>
				   <td width='10%' class="formatMoney">$trCost</td>
				   <td width='30%'>�˷��ò�����ɱ����㣬�����ο�</td>
				   <td></td>
				</tr>
EOT;
        }
        return $str;
    }

    /**
     * ������º�ͬ�ɱ���������ֵ
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
        //�����Ʒ�߳ɱ������ؽ��Ϊ����array('saleCost'=>�������ܳɱ���'serCost'=>�������ܳɱ���'allCost'=>�����ܳɱ�)��
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $costArr['saleCost']; //���۳ɱ�����
        $saleCostTax = $costArr['saleCostTax']; //���۳ɱ����㣨��˰��
        $serCost = $costArr['serCost']; //����ɱ�����
        $allCost = $costArr['allCost']; //�ܳɱ�����
        $allCostTax = $costArr['allCostTax']; //�ܳɱ����㣨��˰��

        //�����������ĺ�ͬ���������޳ɱ�
        //��Ʊ������
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ���ú�ͬ������
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
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //��������
            $costEstimates = round(bcadd(bcmul($days, bcdiv($saleCost, 720, 9), 9), $serCost, 9), 2);
            $costEstimatesTax = round(bcadd(bcmul($days, bcdiv($saleCostTax, 720, 9), 9), $serCost, 9), 2);
        } else {
            $costEstimates = $allCost;
            $costEstimatesTax = $allCostTax;
        }
        if (empty($typeMoney) || $typeMoney == '0') {
            $typeMoney = $invoiceArr['contractMoney'];
        }
        //����Ԥ��ë����
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);
        //�ж��Ƿ���ȫ��ȷ�����
        $productLineStr = rtrim($rows['newProLineStr'], ',');
        $confirmFlag = $costDao->confirmCostFlag($productLineStr, $contractId, $costId);

        if ($confirmFlag == "1") {
            return "none";
        } else {
            return $exGross;
        }
    }

    /**
     * ������º�ͬ�ɱ���������ֵ(��)
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
        //�����Ʒ�߳ɱ������ؽ��Ϊ����array('saleCost'=>�������ܳɱ���'serCost'=>�������ܳɱ���'allCost'=>�����ܳɱ�)��
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $costArr['saleCost']; //���۳ɱ�����
        $saleCostTax = $costArr['saleCostTax']; //���۳ɱ����㣨��˰��
        $serCost = $costArr['serCost']; //����ɱ�����
        $allCost = $costArr['allCost']; //�ܳɱ�����
        $allCostTax = $costArr['allCostTax']; //�ܳɱ����㣨��˰��

        //�����������ĺ�ͬ���������޳ɱ�
        //��Ʊ������
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ���ú�ͬ������
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
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //��������
            $costEstimates = round(bcadd(bcmul($days, bcdiv($saleCost, 720, 9), 9), $serCost, 9), 2);
            $costEstimatesTax = round(bcadd(bcmul($days, bcdiv($saleCostTax, 720, 9), 9), $serCost, 9), 2);
        } else {
            $costEstimates = $allCost;
            $costEstimatesTax = $allCostTax;
        }
        if (empty($typeMoney) || $typeMoney == '0') {
            $typeMoney = $invoiceArr['contractMoney'];
        }
        //����Ԥ��ë����
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);

        // ���±����¼��ĳɱ������Լ�ë���ʼ�¼(���ʱʹ��)
        $newContract = $this->getContractInfo($contractId);
        if($contractId != $newContract['originalId'] && $newContract['originalId'] != 0){
            $oldId = $newContract['originalId'];
            $oldRows = $this->getContractInfo($oldId);
            $oldExgross = $oldRows['exgross'];
            $oldCostTax = $oldRows['costEstimatesTax'];
            //���ұ����¼����id
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
                "VALUES ('$mid', 'contract', '$oldId', '', '��ͬ����', 'contract', '$contractId', NULL, '�ɱ����㣨��˰��', 'costEstimatesTax', '$oldCostTax', '$costEstimatesTax')," .
                "('$mid', 'contract', '$oldId', '', '��ͬ����', 'contract', '$contractId', NULL, 'Ԥ��ë����', 'exgross', '$oldExgross', '$exGross')";
            $this->query($sql);
        }

        //�ж��Ƿ���ȫ��ȷ�����
        if (!empty($rows['xfProLineStr'])) { // ��Ʒ������,�˴��ǵ�ȥ��
            $productLineNum = count(array_unique(explode(',', rtrim($rows['xfProLineStr'], ','))));
        } else {
            $productLineNum = count(array_unique(explode(',', rtrim($rows['newProLineStr'], ','))));
        }
        $costNum = $costDao->findCount(array('contractId' => $contractId)); // �ɱ������¼����
        $appCostNum = $costDao->findCount(array('contractId' => $contractId, 'state' => '1')); // �ɱ�������Ч���������������ͬ������ȷ���������δȷ�ϣ�������ȷ�Ϻ���ύ��������
        if ($confirmTag == "3") {
            $appCostEquNum = $costDao->findCount(array('contractId' => $contractId, 'state' => '3'));
            $appCostNum += $appCostEquNum;
        }
        if ($productLineNum != $costNum) { // ��Ʒ�����������ڳɱ������¼����,��ȷ��δ���
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
     * ���¼���ɱ�����
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
        //�����Ʒ�߳ɱ������ؽ��Ϊ����array('saleCost'=>�������ܳɱ���'serCost'=>�������ܳɱ���'allCost'=>�����ܳɱ�)��
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $costArr['saleCost']; //���۳ɱ�����
        $saleCostTax = $costArr['saleCostTax']; //���۳ɱ����㣨˰�գ�
        $serCost = $costArr['serCost']; //����ɱ�����
        $allCost = $costArr['allCost']; //�ܳɱ�����
        $allCostTax = $costArr['allCostTax']; //�ܳɱ����㣨˰�գ�

        //�����������ĺ�ͬ���������޳ɱ�
        //��Ʊ������
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ���ú�ͬ������
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
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //��������
            // ������>=720��ģ��ɱ�������������720����㡣 2014-10-17 ����Ҫ��
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
        //����Ԥ��ë����
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);

        //��Ա�������±����¼������
        if ($change == "change") {
            $oldId = $rows['originalId'];
            $oldRows = $this->getContractInfo($oldId);
            $oldExgross = $oldRows['exgross'];
            $oldCostTax = $oldRows['costEstimatesTax'];
            //���ұ����¼����id
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
                "VALUES ('$mid', 'contract', '$oldId', '', '��ͬ����', 'contract', '$contractId', NULL, '�ɱ�����', 'costEstimatesTax', '$oldCostTax', '$costEstimatesTax')," .
                "('$mid', 'contract', '$oldId', '', '��ͬ����', 'contract', '$contractId', NULL, 'Ԥ��ë����', 'exgross', '$oldExgross', '$exGross')";
            $this->query($sql);
        }
        return $exGross;
    }

    /**
     * ����ȷ��ҳ��- ��̬���� �ɱ������Ԥ��ë����
     */
    function getCostByEqu_d($contractId,$equArr,$isChange = false){
        $equDao = new model_contract_contract_equ();
        $equTaxRate = $equDao->_defaultTaxRate;
        $recordArr = array();
    	//ѭ���������ϳɱ�
    	$equCostAll = $equCostTaxAll = 0;
    	foreach($equArr as $k => $v){
            if($v['conId'] != '' && $isChange){// ����Ǳ�����϶���ԭ����������ϵ�,�ж��Ƿ��ԭ���ĵ��ۻ���ȡ��ǰ�ĵ���
                $equsql = "select id,productCode,number,productId,price,priceTax from oa_contract_equ where contractId = '{$contractId}' and id = '" . $v['conId'] . "'";
                $equChkArr = $this->_db->getArray($equsql);
                if($equChkArr && isset($equChkArr[0]) && isset($equChkArr[0]['id']) && $equChkArr[0]['number'] == $v['number']){// ���ԭ��������û�������Ļ�,��ȡԭ������ʱ����ĵ���
                    $equCost = $equChkArr[0]['price'];
                    $recordArr[$k]['chkId'] = $equChkArr[0]['id'];
                }else{// ��������µ����ϵ���
                    $sql = "select priCost from oa_stock_product_info where id='" . $v['productId'] . "'";
                    $equCostArr = $this->_db->getArray($sql);
                    $equCost = $equCostArr[0]['priCost'];
                    $recordArr[$k]['chkId'] = '';
                }

                $recordArr[$k]['price'] = $equCost;
                $recordArr[$k]['number'] = $v['number'];
                $recordArr[$k]['chkSQL'] = $equsql;
                // ��17%˰�ʣ�����˰����
                $equCostTax = bcmul($equCost, bcadd($equTaxRate,1,2), 2)* $v['number'];
                $equCostTaxAll += $equCostTax;
                // ë���ʼ�����˰ǰ���
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
                // ��17%˰�ʣ�����˰����
                $equCostTax = bcmul($equCost, bcadd($equTaxRate,1,2), 2)* $v['number'];
                $equCostTaxAll += $equCostTax;
                // ë���ʼ�����˰ǰ���
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
        //�����Ʒ�߳ɱ������ؽ��Ϊ����array('saleCost'=>�������ܳɱ���'serCost'=>�������ܳɱ���'allCost'=>�����ܳɱ�)��
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($contractId);

        $saleCost = $equCostAll; //���۳ɱ�����
        $saleCostTax = $equCostTaxAll; //���۳ɱ����㣨˰�գ�
        $serCost = $costArr['serCost']; //����ɱ�����
        $allCost = $equCostAll + $serCost; //�ܳɱ�����
        $allCostTax = $equCostTaxAll + $serCost; //�ܳɱ�����(��˰)

        //�����������ĺ�ͬ���������޳ɱ�
        //��Ʊ������
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ���ú�ͬ������
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
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //��������
            $saleCostTemp = bcdiv($saleCost, 720, 6);
            $saleCostTempTax = bcdiv($saleCostTax, 720, 6);
            // ������>=720��ģ��ɱ�������������720����㡣 2014-10-17 ����Ҫ��
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
            // ��17%˰�ʣ�����˰����
            $typeMoney = bcmul($typeMoney, bcadd($equTaxRate,1,2), 2);
        }
        //����Ԥ��ë����
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
     * ���¼���ɱ�����
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
        //�����Ʒ�߳ɱ������ؽ��Ϊ����array('saleCost'=>�������ܳɱ���'serCost'=>�������ܳɱ���'allCost'=>�����ܳɱ�)��
        $costDao = new model_contract_contract_cost();
        $costArr = $costDao->countCost($cid);

        $saleCost = $costArr['saleCost']; //���۳ɱ�����
        $saleCostTax = $costArr['saleCostTax']; //���۳ɱ����㣨˰�գ�
        $serCost = $costArr['serCost']; //����ɱ�����
        $allCost = $costArr['allCost']; //�ܳɱ�����
        $allCostTax = $costArr['allCostTax']; //�ܳɱ����㣨˰�գ�
        //�����������ĺ�ͬ���������޳ɱ�
        //��Ʊ������
        $i = 0;
        $typeMoney = 0;
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ���ú�ͬ������
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
            $days = abs($this->getChaBetweenTwoDate($rows['beginDate'], $rows['endDate'])); //��������
            $saleCostTemp = bcdiv($saleCost, 720, 6);
            $saleCostTempTax = bcdiv($saleCostTax, 720, 6);
            // ������>=720��ģ��ɱ�������������720����㡣 2014-10-17 ����Ҫ��
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
        //����Ԥ��ë����
        $typeMoney = sprintf("%.3f", $typeMoney);
        $exGrossTemp = bcdiv(($typeMoney - $costEstimates), $typeMoney, 8);
        $exGross = round(bcmul($exGrossTemp, '100', 8),2);
        $sql = "update oa_contract_contract set exgross='" . $exGross . "',costEstimates='" . $costEstimates . "',costEstimatesTax='" . $costEstimatesTax . "' where id='" . $contractId . "'";
        $this->query($sql);
        return $exGross;
    }


    /**
     * ���ݺ�ͬid��ȡ���һ�α����¼id
     */
    function findChangeId($id)
    {
        $sql = "select max(id) as Mid from oa_contract_contract where originalId = $id";
        $idArr = $this->_db->getArray($sql);
        return $idArr[0]['Mid'];
    }

    /**
     *��֤��ͬ���Ψһ
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
     * ��ͬ�յ�
     */
    function ajaxAcquiring_d($id, $f = '1')
    {
        $date = date("Y-m-d");

        // �ʼ���Ϣ
        $contract = $this->get_d($id);
        $otherDataDao = new model_common_otherdatas();
        $sendIds = $otherDataDao->getConfig('contractAcquiringSendIds');
        $uIds = "'".str_replace(",","','",rtrim($sendIds,","))."'";
        $sql = "select GROUP_CONCAT(EMAIL) as address  from user where USER_ID in(".$uIds.")";
        $adrsArr = $this->_db->getArray($sql);
        $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";
        $title = "��ͬǩ��֪ͨ";
        $ebody = "���ã�  ��ͬ {$contract['contractCode']} ���ϴ�ֽ�ʺ�ͬ��������֪Ϥ��лл��";

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
     * ��ִͬ��״̬
     */
    function exeStatusView_d($arr)
    {
        $state = $arr['state'];
        $isSubApp = $arr['isSubApp'];
        if ($state == '3' || $state == '7') {
            $name = ($state == '7')? '�쳣�ر�' : '�ر�';
            return array($name, "5");
        } else if ($state == '4') {
            return array("�����", "4");
        } else if ($state == '2') {
            return array("ִ����", "3");
        } else if ($state == '1') {
            return array("������", "2");
        } else if ($isSubApp == '1') {
            return array("�ɱ�ȷ��", "1");
        } else {
            return array("δ�ύ", "0");
        }

    }

    /*
	*
	*�������ܣ�����������YYYY-MM-DDΪ��ʽ�����ڣ�����
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
     * ���ݺ�ͬID ��ȡ ��ͬ����
     */
    function getContractFeeAll($cid, $isAmount = "1")
    {
        //��ȡ��ͬ����
        $rows = $this->getContractInfo($cid);
        $conProCost = "0"; //��ͬ��Ŀ����
        $csCost = "0"; //�ۺ����
        $trCost = "0"; //������Ŀ����
        $feeArr = array();


        //��ͬ��Ŀ����sql
        $conPsql = "SELECT
			sum(amount) as amount,replace(ProjectNo,'-','') as projeectNo
			FROM
			cost_summary_list
			WHERE
			`Status` = '���' and
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
        //�ۺ�sql
        $csql = "select
			if(sum(amount) is null,0,sum(amount)) as amount
			FROM
			cost_summary_list
			WHERE DetailType = 5 and contractId = '" . $cid . "' and `Status` = '���'";

        //��ȡ������Ŀ����
        if (!empty($rows['chanceId'])) {
            $chanceId = $rows['chanceId'];
            $trSql = "SELECT
			sum(amount) as amount,replace(ProjectNo,'-','') as projeectNo
			FROM
			cost_summary_list
			WHERE
			`Status` = '���' and
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
        //��ͬ��Ŀ����
        $conProCostArr = $this->_db->getArray($conPsql);
        if (!empty($conProCostArr)) {
            foreach ($conProCostArr as $k => $v) {
                $conProCost += $v['amount'];
            }
        }
        $feeArr['htfy'] = $conProCostArr;
        //�ۺ�
        $csCostArr = $this->_db->getArray($csql);
        if (!empty($csCostArr)) {
            foreach ($csCostArr as $k => $v) {
                $csCost += $v['amount'];
            }
        }
        $feeArr['sh'] = $csCostArr;
        /**��������ܽ��**/
        $feeAmount = $conProCost + $csCost + $trCost;
        if ($isAmount == '1') {
            return $feeAmount;
        } else {
            return $feeArr;
        }
    }

    /**
     * ���������ʾ
     */
    function handleFeeHTML($arr, $rows)
    {
        /**************************************/
        //������Ŀ
        $syxmArr = $arr['syxm'];
        $syxmNum = count($syxmArr);
        $strSYXM = "";
        if (empty($syxmArr)) {
            $strSYXM .= <<<EOT
			<tr>
			    <th class="spec">������Ŀ����</th>
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
			    <th class="spec" rowspan="$syxmNum">������Ŀ����</th>
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
        //���̷���
        $htfyArr = $arr['htfy'];
        $htfyNum = count($htfyArr);
        if (empty($htfyArr)) {
            $strHTFY = "";
            $strHTFY .= <<<EOT
			<tr>
			    <th class="spec">���̷���</th>
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
			    <th class="spec" rowspan="$syxmNum">���̷���</th>
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
        //�ۺ�
        $shArr = $arr['sh'];
        $shNum = count($shArr);
        if (empty($shArr)) {
            $strSH = "";
            $strSH .= <<<EOT
			<tr>
			    <th class="spec">�ۺ����</th>
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
			    <th class="spec" rowspan="$syxmNum">�ۺ����</th>
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
        //�ܼ�
        $sj = $shCost + $htfyCost + $conProCost;
        $strZJ = "";
        $strZJ .= <<<EOT
              <tr>
			    <tr>
			    <td></td>
			    <td></td>
			    <td>�ϼ�</td>
			    <td class="formatMoney">$sj</td>
			  </tr>
EOT;
        $str = $strSYXM . $strHTFY . $strSH . $strZJ;
        return $str;
    }

    /**
     * �ؿ��б���
     */
    function financialTdayHTML($rows)
    {
        $str = "";
        if (!empty($rows)) {
            foreach ($rows as $k => $v) {
                $id = $v['id'];
                //���ݸ��������жϲ���������T��
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
                    $isCom = "δ���";
                } else if ($v['isCom'] == '1') {
                    $isCom = "�����";
                } else {
                    $isCom = "-";
                }
                if (empty($v['comDate']) || $v['comDate'] != '0000-00-00')
                    $comDate = $v['comDate'];

                //��ʶ
                if ($v['Tday'] == '' || $v['Tday'] == '0000-00-00') {
                    $isFlag = "-";
                } else {
                    $isFlag = '<img src="images/icon/ok3.png">';
                }
                if ($v['TdayPush'] == '0') {
                    $htmlButton = ' <td><input type="button" class="txt_btn_a" value="ȷ��" onclick = "confirmTday(' . $id . ',' . $k . ')"></td>';
                    $htmlInput = ' <td><input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '"></td>';
                } else {
                    $htmlButton = ' <td><input type="button" class="txt_btn_a" value="���" onclick = "confirmTday(' . $id . ',' . $k . ')"></td>';
                    $htmlInput = ' <td><input type="text" class="txtshort"  onfocus="WdatePicker()" id="tday' . $k . '" value="' . $Tday . '"><span class="blue" onclick = "changeHistory(' . $id . ',' . $k . ');"> ' . '<img title="�鿴�����ʷ" src="images/icon/view.gif"></span>' . '<input type="hidden" id="tdayOld' . $k . '" value="' . $Tday . '"></td>';
                }
                //add by chenrf
                if ($v['isDel'] == '1') {
                    $isCom = "��ɾ��";
                    $htmlButton = '<td><input type="button" class="txt_btn_a" value="ȷ��" disabled="disabled"></td>';
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
     * ������Ŀ����ò��ж��Ƿ���Ҫ����T��
     * @param $contractId ��ͬid
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
                    ")t;";// ���ж����Ŀ,ȡ�����Ǹ�����
                    $arr = $this->_db->getArray($sql);
                    if($arr[0]['payDT'] != $val['Tday'] && $arr[0]['payDT'] != "" && $arr[0]['payDT'] != NULL){
//                        $updateSql = "update oa_contract_receiptplan set Tday=null,TdayPush=0 where id = ".$val['id'];
                        $updateSql = "update oa_contract_receiptplan set Tday='{$arr[0]['payDT']}' where id = ".$val['id']." and Tday is not null;";// ���¾�T��ȷ�����ڶԱȣ���һ�£�ȡ��T��ֱ��д������T������ı�ȷ��״̬��PMS 155
                        $updateResult = $this->_db->query($updateSql);
                        $res = ($updateResult)? "�ɹ�" : "ʧ��";
                        $this->addOperateLog("��Ŀ��ɸ���T��", $contractId, "��Ŀ�����,���ڣ�ԭT��: {$val['Tday']}) �루��Ŀ������ڼӻ�������: {$arr[0]['payDT']})��һ�´���T�ո��µķ�����", $res, $updateSql);
                    }
                }
            }
        }
    }

    /**
     * ����û�������¼
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
            writeToLog("�û�������¼¼��ʧ�ܣ�", "log.txt");
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
            //�����ʼ�
            $contractArr = $this->find(array('id' => $receArr['contractId']), null, 'prinvipalId,contractCode');
            $this->mailDeal_d('confirmReceiptPlan', $contractArr['prinvipalId'], array('contractCode' => $contractArr['contractCode'], 'paymentterm' => $receArr['paymentterm'], 'name' => $_SESSION['USERNAME']));
            $this->commit_d();
            return 1;
        } catch (exception $e) {
            $this->rollBack();
            return $e;
        }
    }

    //update T - ����
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
                    //�����ʼ�
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

    //�ؿ�T�ձ����¼
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
        if (FALSE != $this->_db->query($sql)) { // ��ȡ��ǰ������ID
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
            $boostStr .= "-->" . "<span style='color:blue' title = '�ƽ��ˣ� " . $v['createName'] . "
�ƽ�ʱ�� �� " . $v['createTime'] . "
			        					'>" . $v['financialType'] . "<span>";
            $boostList .= "<tr><td style='text-align: left'>��" . $v['createName'] . "���ڡ�" . $v['createTime'] . "��������T-�մ� �� " . $v['financialOldV'] . " �������� �� " . $v['financialNewV'] . " ��<br>�����ԭ�� �� " . $v['changeRemark'] . "</td><tr>";
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
						<b>�ޱ����Ϣ</b>
					</td>

			</tr>
EOT;
        }
        return $str;
    }

    /**
     * ���ݸ�������ID����ͬid ��������T��
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
            } else if ($payconfigArr['dateCode'] == 'firstOutstockDate') { // ��һ�γ�������
                $sql = "SELECT date_add(min(auditDate), interval ".$days." day) as payDT FROM oa_stock_outstock WHERE contractId = '".$contracrId."' AND docStatus = 'YSH'";
                $arr = $this->_db->getArray($sql);
            } else if ($payconfigArr['dateCode'] == 'esmPercentage' || $payconfigArr['dateCode'] == 'shipPercentage' || $payconfigArr['dateCode'] == 'schePercentage') { // ���Ȱٷֱ�
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
     * �ɱ�ȷ�� ��Ʒ����ʾhtml
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
                // ��ѯ�ò�Ʒ���Ƿ���ڷ����۲�Ʒ�������ڣ�����ʾ
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
                    <td class="form_text_left"><span style="color:blue">$costLimitName �ɱ�����</span> </td>
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
     * ��������ɱ�ȷ����ϸ
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
                $temp['xfProductLine'] = $k . 'f'; // ��������ͬһ���߲�ͬ��Ʒ���͵Ĳ�Ʒ���࣬��ĳĳ����������(x)/������(f)��Ʒ
                $temp['productLineName'] = $costLimitName;
                $temp['confirmName'] = $arr['engConfirmName'];
                $temp['confirmId'] = $arr['engConfirmId'];
                $temp['confirmDate'] = date("Y-m-d H:i:s");
                $temp['state'] = "1";
                $temp['ExaState'] = "0"; // ����ȥ���ɱ���ˣ����ｫ0�ĳ�1 By weijb 2015.10.17
                $temp['confirmMoney'] = $v;
                $temp['confirmMoneyTax'] = $v; //�������Ʒ��������˰����

                //�ж������Ƿ����
                $isRel = $costDao->findisEel($arr['contractId'], $k, "0");
                if ($isRel == "0") {
                    $nid = $costDao->add_d($temp);
                } else {
                    $temp['id'] = $isRel;
                    $nid = $isRel;
                    $costDao->edit_d($temp);
                }
                $handleDao = new model_contract_contract_handle();
                //����Ǳ��������ԭ��ͬȷ��״̬
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
                    //���ȷ�ϼ�¼
                    $handleDao->handleAdd_d(array(
                        "cid" => $type,
                        "stepName" => "����ɱ�ȷ��",
                        "isChange" => 2,
                        "stepInfo" => $k,
                    ));

                } else {
                    $handleDao->handleAdd_d(array(
                        "cid" => $arr['contractId'],
                        "stepName" => "����ɱ�ȷ��",
                        "isChange" => 0,
                        "stepInfo" => $k,
                    ));
                }
//                //����productLine ����֪ͨ�ʼ�
//                $tomail = $this->costConUserIdBycid($temp['productLine']);
//                $content = array(
//                    "contractCode" => $conArr['contractCode'],
//                    "contractName" => $conArr['contractName'],
//                    "customerName" => $conArr['customerName']
//                );
//                $this->mailDeal_d("contractCost_Confirm", $tomail, $content);
            }
            if (!empty($arr['costRemark'])) {
                //�����Ʒ��ȷ�ϱ�ע
                $costDao->handleCostRemark($arr['costRemark'], $nid);
            }
        }

    }

    /**
     * �����������Ʒ�� �ɱ�ȷ�� ��ϸ��¼
     */
    function hadleSaleLineCostInfo($conCostArr, $ischange = "add", $audti = false)
    {
        //���ݺ�ͬid��ȡ�������Ʒ�� ����
        $equDao = new model_contract_contract_equ();
        $productDao = new model_contract_contract_product();

        if ($ischange == "add") {
            $conArr = $equDao->getDetail_d($conCostArr['id']);
        } else {
            $conArr = $equDao->getDetailTemp_d($conCostArr['id']);
        }
        //Ĭ�ϵ������ݺ�ͬ����������������ɲ�����˼�¼�� By LiuB 2014-11-28
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
                //��Ʒ��
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
            //�ж��Ƿ������������Ʒ
            $goodsTypeIds = $v['conProductId'];
            if (!empty ($goodsTypeIds)) {
                $sqlA = "select id,parentId from oa_goods_type where id in (select goodsTypeId from  oa_goods_base_info where id in ($goodsTypeIds))";
                //��һ�β��ң����˳������Ѿ��������������
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
                //�ڶ��β��ң��ҵ�ʣ���Ʒ��������
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
                //��Ʒ��
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
        //������ͬ��Ʒ�ߵĽ������
        //����Ʒ������߳ɱ����飬��ֹ �����ϲ�Ʒ����ȷ�ϲ�����˼�¼
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
        //�����ϼ�����߳ɱ�����
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

        //�ϲ����飬��� ȱ�ٲ��߼�ȱ�ٳɱ�����
        foreach ($ResultArr as $key => $value) {
            $ResultArr[$key] = $value;
        }
        foreach ($ResultArrEqu as $key => $value) {
            $ResultArr[$key] = $value;
        }

        if (!empty($ResultArr)) {
            //��û��Ʒ�����ϳɱ��ҵ���һ����Ʒ���£�����ʱ����
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
            //�����������ȷ��ֻ�Ǳ��棬��ɱ�ȷ��״̬Ϊ0���ύ��Ϊ3
            $state = $audti == true ? '3' : '0';
            foreach ($ResultArr as $k => $v) {
                //         		$costLimitNameArr = $deptDao->getDeptById($k);
                //                $costLimitName = $costLimitNameArr['DEPT_NAME'];
                $lineStr .= $k . ",";
                $costLimitName = $datadictDao->getDataNameByCode($k);
                $temp['contractId'] = $conCostArr['id'];
                $temp['productLine'] = $k;
                $temp['xfProductLine'] = $k . 'x'; // ��������ͬһ���߲�ͬ��Ʒ���͵Ĳ�Ʒ���࣬��ĳĳ����������(x)/������(f)��Ʒ
                $temp['productLineName'] = $costLimitName;
                $temp['confirmName'] = $conCostArr['saleConfirmName'];
                $temp['confirmId'] = $conCostArr['saleConfirmId'];
                $temp['confirmDate'] = date("Y-m-d H:i:s");
                $temp['state'] = $state; // 0 ��ʾ�������棬1 ��ʾ��ȷ�ϣ� 2 ���  3 ��ʾ���۴�ȷ��
                $temp['ExaState'] = "0";
                $temp['confirmMoney'] = $v;
//                 $temp['confirmMoneyTax'] = bcmul($v, '1.17', 2);// ��17%˰�ʣ�����˰����
                $temp['confirmMoneyTax'] = $ResultArrEquTax[$k];
                $temp['issale'] = "1";
                $temp['costRemark'] = $conCostArr['costRemark'];
                //�ж������Ƿ����
                $isRel = $costDao->findisEel($conCostArr['id'], $k, 1);
                if ($isRel == "0") {
                    unset($temp['id']);
                    $costDao->add_d($temp);
                } else {
                    $temp['id'] = $isRel;
                    $costDao->edit_d($temp);
//                     if($conCostArr['oldId'] && $audti){//�����ύ����ȷ�ϲŸ���ԭ��ͬ�ɱ���Ϣ
//                     	$isRelOld = $costDao->findisEel($conCostArr['oldId'],$k,1);
//                         unset($temp['id']);
//                         $temps['contractId'] = $conCostArr['oldId'];
//                         $temps['productLine'] = $k;
//                         $temps['productLineName'] = $costLimitName;
//                         $temps['confirmName'] = $conCostArr['saleConfirmName'];
//                         $temps['confirmId'] = $conCostArr['saleConfirmId'];
//                         $temps['confirmDate'] = date ( "Y-m-d H:i:s" );
//                         $temps['state'] = "3";// 1 ��ʾ��ȷ�ϣ� 2 ���  3 ��ʾ���۴�ȷ��
//                         $temps['ExaState'] = "0";
//                         $temps['confirmMoney'] = $v;
//                         $temps['confirmMoneyTax'] = bcmul($v, '1.17', 2);// ��17%˰�ʣ�����˰����
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
     * ��̬��ȡ��ǰ��¼�˵ĳɱ�ȷ��״̬
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
     * �ɱ�ȷ���쵼���
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
        //�ж��Ƿ���ȫ��ȷ�����
        $productLineStr = rtrim($rows['newProLineStr'], ',');
        $confirmFlag = $costDao->confirmCostFlag($productLineStr, $cid, $id);
        //������ϸ���״̬
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
                //���״̬�����ƶ��� �������ļ��ڣ����� �������ύ���¸���ҵ��״̬����( ֻ���������������ݣ��Է������)
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
                    "stepName" => "ִ�в������",
                    "isChange" => 0,
                    "stepInfo" => $line,
                ));
            } else {
                $handleDao->handleAdd_d(array(
                    "cid" => $type,
                    "stepName" => "ִ�в������",
                    "isChange" => 2,
                    "stepInfo" => $line,
                ));
            }
            //       	     $this->query($updateSql);
        }

    }

    /**
     * �������쵼�����ɺ���³ɱ�ȷ�ϱ�־ engConfirm
     */
    function endTheEngTig($cid)
    {
        $sql = "update oa_contract_contract set engConfirm = '1' where id = '" . $cid . "'";
        $this->query($sql);
    }

    /**
     * ���ݺ�ͬID ��ȡ���һ�α��ԭ��
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
     * ���ݺ�ͬid  ��ȡ ������Ŀ��ط���
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
							cost_summary_list l WHERE  l.isproject = 1 AND l. STATUS <> '���' GROUP BY l.projectNo
					) l ON replace(l.projectNo,'-','') =  replace(c.projectCode,'-','')
					left join (
						select
							p.expand3,sum(p.money) as money
						from
							oa_finance_payablesapply_detail p
						left join oa_finance_payablesapply pa on p.payapplyId = pa.id
						where
							p.expand1 = '������Ŀ' and pa.ExaStatus != '���' and pa. status not in ('FKSQD-04', 'FKSQD-05')
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
     * Ȩ������
     * Ȩ�޷��ؽ������:
     * �������Ȩ�ޣ�����true
     * �����Ȩ��,����false
     */
    function initLimit($customSql = null)
    {
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        //Ȩ����������
        $limitConfigArr = array(
            'areaLimit' => 'c.areaCode',
            'deptLimit' => 'c.prinvipalDeptId',
            'customerTypeLimit' => 'c.customerType',
            'contractTypeLimit' => 'c.contractType',
        );

        //Ȩ������
        $limitArr = array();
//         $limitArr['appNameStr'] = '1';
        //Ȩ��ϵͳ
        if (isset ($sysLimit['��Ʒ��']) && !empty ($sysLimit['��Ʒ��']))
            $limitArr['productLine'] = $sysLimit['��Ʒ��'];

        if (isset ($sysLimit['�ɱ�ȷ��']) && !empty ($sysLimit['�ɱ�ȷ��'])) {
            if (!empty($limitArr['productLine'])) {
                $limitArr['productLine'] .= "," . $sysLimit['�ɱ�ȷ��'];
            } else {
                $limitArr['productLine'] = $sysLimit['�ɱ�ȷ��'];
            }
        }
        if (isset ($sysLimit['�ɱ�ȷ�����']) && !empty ($sysLimit['�ɱ�ȷ�����'])) {
            if (!empty($limitArr['productLine'])) {
                $limitArr['productLine'] .= "," . $sysLimit['�ɱ�ȷ�����'];
            } else {
                $limitArr['productLine'] = $sysLimit['�ɱ�ȷ�����'];
            }
        }
        if (isset ($sysLimit['��ƷȨ��']) && !empty ($sysLimit['��ƷȨ��']))
            $limitArr['goodsLimit'] = $sysLimit['��ƷȨ��'];
        if (isset ($sysLimit['��������']) && !empty ($sysLimit['��������']))
            $limitArr['areaLimit'] = $sysLimit['��������'];
        if (isset ($sysLimit['����Ȩ��']) && !empty ($sysLimit['����Ȩ��']))
            $limitArr['deptLimit'] = $sysLimit['����Ȩ��'];
        if (isset ($sysLimit['�ͻ�����']) && !empty ($sysLimit['�ͻ�����']))
            $limitArr['customerTypeLimit'] = $sysLimit['�ͻ�����'];
        if (isset ($sysLimit['��ͬ����']) && !empty ($sysLimit['��ͬ����']))
            $limitArr['contractTypeLimit'] = $sysLimit['��ͬ����'];
        if (isset ($sysLimit['��˾Ȩ��']) && !empty ($sysLimit['��˾Ȩ��']))
            $limitArr['companyLimit'] = $sysLimit['��˾Ȩ��'];
        if (isset ($sysLimit['ִ������']) && !empty ($sysLimit['ִ������']))
            $limitArr['exeDeptLimit'] = $sysLimit['ִ������'];
        if (strstr($limitArr['productLine'], ';;') || strstr($limitArr['goodsLimit'], ';;') ||
            strstr($limitArr['areaLimit'], ';;') || strstr($limitArr['deptLimit'], ';;') ||
            strstr($limitArr['customerTypeLimit'], ';;') || strstr($limitArr['contractTypeLimit'], ';;') ||
            strstr($limitArr['companyLimit'], ';;') || strstr($limitArr['exeDeptLimit'], ';;')
        ) {
            return true;
        } else {
            //�������˻�ȡ�������
            $regionDao = new model_system_region_region();
            $areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
            if (!empty ($areaPri)) {
                //����Ȩ�޺ϲ�
                $limitArr['areaLimit'] = implode(array_filter(array(
                    $limitArr['areaLimit'],
                    $areaPri
                )), ',');

            }

            //���۸����˶�ȡ��Ӧʡ�ݺͿͻ�����
            $saleArea = new model_system_saleperson_saleperson();
            $saleAreaInfo = $saleArea->getSaleArea($_SESSION['USER_ID']);
            if (!empty($saleAreaInfo)) {
                $limitArr['saleAreaInfo'] = $saleAreaInfo;
            }
            //			print_r($limitArr);
            if (empty ($limitArr)) {
                return false;
            } else {
                //�������۸�����
                if (!empty($limitArr['saleAreaInfo'])) {
                    $saleAreaStr = "";
                    foreach ($saleAreaInfo as $sval) {
                        $saleTemp = "";
                        //�ͻ�����
                        $saleTemp .= " c.customerType  in ('" . str_replace(',', "','", $sval['customerType']) . "') ";
                        //ʡ��
                        if ($sval['provinceId'] != '0') { //ȫ�����˵�
                            $saleTemp .= "and c.contractProvinceId ='" . $sval['provinceId'] . "'  ";
                        }
                        $saleAreaStr .= " or ( " . $saleTemp . " ) ";
                    }
                    unset($limitArr['saleAreaInfo']); //����
                }
                //���û��Ȩ��
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
                //��Ʒ��Ȩ��
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
                //ִ������Ȩ��
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
                //��˾Ȩ��
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
                //�ж�������
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
                //�������۸�������
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

                    // 2018-03-01 ������ȷ������صĲ㼶��ϵ�ǿͻ����͵�Ȩ�޲�Ӧ�������������ǻ�Ĺ�ϵ,Ӧ�����ǹ��˳���ָ������������,���ڴ�����������ȥ��ȡ��Ӧ�Ŀͻ�����
                    if(
                        isset($limitArr['areaLimit']) && !empty($limitArr['areaLimit'])
                        &&
                        (
                            (isset($limitArr['customerTypeLimit']) && !empty($limitArr['customerTypeLimit']))
                            || !empty($saleAreaStr)
                        )
                    ){
                        // ��ͬʱ�������������Լ��ͻ����͵�Ȩ�޵�ʱ���ں���Ӷ�һ����������AND�Ĺ�ϵ����
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

    //T�ձ��� Ȩ��
    function initLimit_treport($customSql = null,$type){
        if(empty($type)){
            return false;
        }else{
            if($type == "t"){
                $limitCom = "T�ձ�˾Ȩ��";
                $limitArea = "T�ձ���������";
            }else if($type == "r"){
                $limitCom = "Ӧ�տ˾Ȩ��";
                $limitArea = "Ӧ�տ���������";
            }
        }
        $otherDataDao = new model_common_otherdatas();
        $sysLimit = $otherDataDao->getUserPriv('contract_contract_contract', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
        //Ȩ����������
        $limitConfigArr = array(
            'areaLimit' => 'c.areaCode'
        );
        //Ȩ������
        $limitArr = array();
        //Ȩ��ϵͳ
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
                unset($limitArr['areaLimit']); //����
            }
            if(strstr($limitArr['companyLimit'], ';;')){
                unset($limitArr['companyLimit']); //����
            }

            //�������˻�ȡ�������
            $regionDao = new model_system_region_region();
            $areaPri = $regionDao->getUserAreaId($_SESSION['USER_ID'], 0);
            if (!empty ($areaPri)) {
                //����Ȩ�޺ϲ�
                $limitArr['areaLimit'] = implode(array_filter(array(
                    $limitArr['areaLimit'],
                    $areaPri
                )), ',');

            }
            //���۸����˶�ȡ��Ӧʡ�ݺͿͻ�����
            $saleArea = new model_system_saleperson_saleperson();
            $saleAreaInfo = $saleArea->getSaleArea($_SESSION['USER_ID']);
            if (!empty($saleAreaInfo)) {
                $areaExt = "";
                foreach($saleAreaInfo as $val){
                    $areaExt .= $val['salesAreaId'].",";
                }
            }
            if (!empty ($areaExt)) {
                //����Ȩ�޺ϲ�
                $limitArr['areaLimit'] .= ",".$areaExt;
            }

            // ����Ȩ������ظ��Լ��յ�ֵ
            $areaLimitArr = explode(",",$limitArr['areaLimit']);
            $areaLimitArr = array_unique($areaLimitArr);
            foreach ($areaLimitArr as $k => $v){
                if($v == ""){
                    unset($areaLimitArr[$k]);
                }
            }
            $areaLimitArr = implode($areaLimitArr,",");
            $limitArr['areaLimit'] = $areaLimitArr;

            // ������֤һ�������빫˾Ȩ��,����п�ȫ����,������
            if(strstr($orgAreaLimit, ';;')){
                unset($limitArr['areaLimit']); //����
            }
            if(strstr($orgCompanyLimit, ';;')){
                unset($limitArr['companyLimit']); //����
            }

            if (empty ($limitArr)) {
                return false;
            }else{
                //���û��Ȩ��
                $i = 0;
                $sqlStr = "sql:and ( ";
                //��˾Ȩ��
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
     * �ｨ�еĺ�ͬ
     */
    function buildContract_d($conditions, $isExport = false)
    {
        if (!isset ($conditions['finishStatus'])) {
            $conditions['finishStatus'] = '3';
        }
        $conditions['isTemp'] = '0';
        $conditions['ExaStatus'] = '���';

        if ($conditions['finishStatus'] == "3") {
//            $customSql = " and ExaDTOne <> '' and date_format(c.ExaDTOne,'%Y-%m-%d') > '2015-12-31'";
            $customSql = " and ExaDTOne <> '' and ExaDTOne <> '0000-00-00'";
        } else {
            if ($conditions['finishStatus'] == 1) {
                //                $customSql = " and ExaDTOne <> '' and deliveryDate <> ''  and checkStatus <> 'δ¼��' and reNum!='0'";
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
        if ($isExport) { //���ڵ���
            return $this->listBysqlId('select_buildList');
        } else {
//            $data = $this->pageBysqlId('select_buildList');
//            echo $this->listSql;exit();
            return $this->pageBysqlId('select_buildList');
        }
    }

    /**
     * �ı��鵵
     */
    function contractArchive_d($customSql)
    {
        $this->searchArr = array('states' => '1,2,3,4,5,6,7',
            'isTemp' => '0',
            'ExaStatus' => '���',
            'signStatusArr' => '0,2');
        //    	//14������� = ��ʱ
        //        $customSql .= " and id in (".CONTOOLIDS.") ";
        $this->initLimit();
        $this->sort = "isSigned ASC,isArchiveOutDate";
        return $this->pageBysqlId('select_buildList');
    }

    /**
     * �²鿴ҳ��
     */
    function viewContract_d($id)
    {
        $rows = $this->searchArr = array('id' => $id);
        $row = $this->list_d('select_gridinfo');
        $con = new controller_contract_contract_contract();
        $row = $con->sconfig->md5Rows($row[0]);
        $skeyStr = "&skey=" . $row['skey_'];
        //�����ƻ�����
        switch ($row['DeliveryStatus']) {
            case 'YFH' :
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>�ѷ���</a>";
                break;
            case 'BFFH':
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>���ַ���</a>";
                break;
            case 'WFH':
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>δ����</a>";
                break;
            case 'TZFH':
                $row['DeliveryStatusName'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=stock_outplan_outplan&action=listByOrderId&id=" . $id . "&objType=oa_contract_contract" . $skeyStr . '",1,' . $id . ")'>ֹͣ����</a>";
                break;
        }
        $row['invoiceMoney'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toInvoiceTab&obj[objType]=KPRK-12&obj[objCode]=" . $row['contractCode'] . "&obj[objId]=" . $id . $skeyStr . '",1,' . $id . ")'>" . $row['invoiceMoney'] . "</a>";
        $row['incomeMoney'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_contract_contract&action=toIncomeTab&obj[objType]=KPRK-12&obj[objCode]=" . $row['contractCode'] . "&obj[objId]=" . $id . $skeyStr . '",1,' . $id . ")'>" . $row['incomeMoney'] . "</a>";
        $rows = $row;
        $rows = $this->processDatadict($rows);
        //��ȡ��Ŀ��Ϣ
        $esmprojectDao = new model_engineering_project_esmproject();
        $esmprojectArr = $esmprojectDao->findAll(array('contractId' => $id));
        $esmprojectCon = new controller_engineering_project_esmproject();
        $esmprojectArr = $esmprojectCon->sconfig->md5Rows($esmprojectArr);
        //������Ŀ����
        foreach ($esmprojectArr as $k => $v) {
            if ($v['status'] == 'GCXMZT01' && $v['ExaStatus'] == '��������') {
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
        //��ȡ������Ϣ
        $equDao = new model_contract_contract_equ();
        $equDao->searchArr = array('contractId' => $id, 'isDel' => '0', 'isTemp' => '0');
        $equArr = $equDao->list_d();
        $rows['equ'] = $equArr;
        //��ȡ������Ϣ
        $this->searchArr = array('id' => $id);
        $receiptplanArr = $this->list_d('select_financialTday');
        foreach ($receiptplanArr as $key => $val) {
            $receiptplanArr[$key]['incomMoneyHtml'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_income_incomecheck&action=checkList&payConId=" . $val['id'] . "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900\",1," . $val['id'] . ');\'>' . $receiptplanArr[$key]['incomMoney'] . "</a>";
            $receiptplanArr[$key]['invoiceMoneyHtml'] = "<a href='javascript:void(0)' onclick='showModalWin(\"?model=finance_invoice_invoice&action=getInvoiceRecords&obj[objType]=KPRK-12&obj[objCode]=".$val['contractCode']."&obj[objId]=".$val['contractId']."&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900\",1," . $val['id'] . ');\'>' . $receiptplanArr[$key]['invoiceMoney'] . "</a>";
        }
        $rows['receiptplan'] = $receiptplanArr;
        //��ȡ������Ϣ
        $checkDao = new model_contract_checkaccept_checkaccept();
        $checkArr = $checkDao->findAll(array('contractId' => $id));
        foreach ($checkArr as $key => $val) {
            $checkArr[$key]['file'] = $this->getFilesByObjId($val['id'], false, 'oa_contract_check');
        }
        $rows['checkAccept'] = $checkArr;
        return $rows;
    }

    /**
     * ��ͬ����
     */
    function deliveryContract_d($conditions, $isExport = false)
    {
        $conditions['isTemp'] = '0';
        $conditions['ExaStatus'] = '���';
        if (isset($conditions['state'])) {
            $conditions['states'] = $conditions['state'];
            unset($conditions['state']);

        }
        $customSql = " and ExaDTOne <> ''";
        $this->searchArr = $conditions;
        $this->initLimit($customSql);
        $this->sort = "isExceed";
        $this->asc = false;
        if ($isExport) { //���ڵ���
            return $this->listBysqlId('select_buildList');
        } else {
            return $this->pageBysqlId('select_buildList');
        }
    }

    /**
     * ���º�ͬǩ�ճ�������
     */
    function updateSignRemind_d($conditions)
    {
        $arr = $this->find(array('id' => $conditions['id']), null, 'signRemind');
        $this->update($conditions, array('signRemind' => $arr['signRemind'] + 1));
    }

    /**
     * ���·�����������
     */
    function updateOutGoodsRemind_d($conditions)
    {
        $arr = $this->find(array('id' => $conditions['docId']), null, 'outGoodsRemind');
        $this->update(array('id' => $conditions['docId']), array('outGoodsRemind' => $arr['outGoodsRemind'] + 1));
    }

    /**
     * ��ͬ��������
     */
    function leftCycle_d($cid)
    {
        $arr = $this->get_d($cid);
        /*********��ͬ����******************/
        //��ȡ�����Ϣ
        $changeInfo = $this->changeinfo_html($cid);
        $html = "";
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="1"></a>
		        <span class="headline-1-index">1</span>
		        <span class="headline-content">��ͬ����</span>
		    </h2>
		    <div class="para">
		           <br><br>
		           �� $arr[createTime] ��  ��  �� $arr[createName] �� �����ɹ�
                   $changeInfo
                   <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		    </div>
EOT;
        /*********�ɱ�����******************/
        //��ȡ�����Ϣ
        $costInfo = $this->confirmCost_html($arr);
        $costInfoApp = $this->confirmCostApp_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="2"></a>
		        <span class="headline-2-index">2</span>
		        <span class="headline-content">�ɱ�����</span>
		    </h2>

		    <div class="para">
		        2.1 �ɱ�ȷ�� <br><br>
                    $costInfo
		         2.2 �ɱ����<br><br>
		            $costInfoApp
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>

		    </div>
EOT;
        /*********��ͬ����******************/
        //��ȡ�����Ϣ
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="3"></a>
		        <span class="headline-3-index">3</span>
		        <span class="headline-content">��ͬ����</span>
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
        /*********��Ŀ����******************/
        //��ȡ�����Ϣ
        $createProInfo = $this->createPorject_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="4"></a>
		        <span class="headline-4-index">4</span>
		        <span class="headline-content">��Ŀ����</span>
		    </h2>

		    <div class="para" style="width:75%">
		        $createProInfo
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>
		    </div>
EOT;
        /*********��Ŀִ��******************/
        //��ȡ�����Ϣ
        $exeProInfo = $this->exePorject_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="5"></a>
		        <span class="headline-5-index">5</span>
		        <span class="headline-content">��Ŀִ��</span>
		    </h2>

		    <div class="para" style="width:75%">
		        $exeProInfo
		       <br><br><br><br><br><br><br><br><br><br><br>
		       <br><br><br><br><br><br><br><br><br><br><br>
		    </div>
EOT;
        /*********��ͬ�ر�******************/
        //��ȡ�����Ϣ
        $closeInfo = $this->closeContract_html($arr);
        $html .= <<<EOT
			<h2 class="headline-1">
		        <a class="anchor-1" name="6"></a>
		        <span class="headline-6-index">6</span>
		        <span class="headline-content">��ͬ�ر�</span>
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

    //���ݺ�ͬid��ȡ����˱��ʱ��
    function changeinfo_html($cid)
    {
        $sql = "SELECT changeTime,changeManName FROM `oa_contract_changlog` where objId = '" . $cid . "' and objType = 'contract'";
        $arr = $this->_db->getArray($sql);
        if (is_array($arr)) {
            $html = "";
            foreach ($arr as $k => $v) {
                $html .= <<<EOT
			    <div class="para">
			          �� $v[changeTime] ��  ��  �� $v[changeManName] �� �ύ���
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

    //�ɱ�ȷ��
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
                //�жϲ�Ʒ���Ƿ����
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
			          ��Ʒ�� �� $lineName ��  ��� ���������´���<br><br>
			    </div>
EOT;
                        } else {
                            $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          ��Ʒ�� �� $lineName �� �� �� $cost[costAppName] �� �� �� $cost[costAppDate] �� ���δȷ��<br><br>
			    </div>
EOT;
                        }

                    } else if ($cost['state'] == '3') {
                        $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          ��Ʒ�� �� $lineName �� ������Աδȷ�Ϸ�������<br><br>
			    </div>
EOT;
                    } else {
                        $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          ��Ʒ�� �� $lineName �� �ɱ�δȷ��<br><br>
			    </div>
EOT;
                    }
                } else if ($cost['state'] == '1') {
                    $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          ��Ʒ�� �� $lineName �� �� �� $cost[confirmName] �� �� �� $cost[confirmDate] �� ȷ��<br><br>
			    </div>
EOT;
                }
                //�Ѿ�����html�Ĳ�Ʒ������
                $isArr[] = $v;
            }
        }
        return $html;
    }

    //�ɱ����
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
                //�жϲ�Ʒ���Ƿ����
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
			          ��Ʒ�� �� $lineName �� �ɱ�δ���<br><br>
			    </div>
EOT;
                } else if ($cost['ExaState'] == '1') {
                    $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          ��Ʒ�� �� $lineName �� �� �� $cost[costAppName] �� �� �� $cost[costAppDate] �� ���ͨ��<br><br>
			    </div>
EOT;
                } else {
                    $html .= <<<EOT
			    <div class="para">
			          &nbsp;&nbsp;&nbsp;
			          ��Ʒ�� �� $lineName �� �� �� $cost[costAppName] �� �� �� $cost[costAppDate] �� ���<br><br>
			    </div>
EOT;
                }
                //�Ѿ�����html�Ĳ�Ʒ������
                $isArr[] = $v;
            }
        }
        return $html;
    }

    //��Ŀ������Ϣ
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
			          ��$v[createTime]��  ��  ��$v[createName]�� ������Ŀ �� $v[projectCode] ��<br><br>
			    </div>
EOT;
            }
        } else {
            $html .= <<<EOT
			    <div class="para">
			         δ�ҵ�������Ϣ
			    </div>
EOT;
        }
        return $html;
    }

    //��Ŀִ��
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
			          ��Ŀ ��$v[projectCode]��  ��ǰ״̬ �� $v[statusName] �� ���� �� $v[projectProcess]% ��<br><br>
			    </div>
EOT;
            }
        } else {
            $html = "";
            $html .= <<<EOT
			    <div class="para">
			         δ�ҵ���Ŀ��Ϣ
			    </div>
EOT;
        }
        return $html;
    }

    //��ͬ�ر���Ϣ
    function closeContract_html($arr)
    {
        $html = "";
        if ($arr['state'] == '3' || $arr['state'] == '7') {
            if ($arr['closeType'] == "�����ر�") {
                $html .= <<<EOT
			    <div class="para">
			          ��ͬ�����������رգ� �ر�ʱ�� �� $arr[closeTime] ��<br><br>
			    </div>
EOT;
            } else if ($arr['closeType'] == "�쳣�ر�") {
                $html .= <<<EOT
			    <div class="para">
			          �� �� $arr[closeName] �� �� �� $arr[closeTime] �� �����쳣�ر�<br>
			          �쳣�ر�ԭ��  �� $arr[closeRegard] ��<br>
			    </div>
EOT;
            }
        }
        return $html;
    }

    /**
     * ���ݺ�ͬid ��ȡ�ú�ͬ�Ƿ���Ҫ����ȷ�Ϸ�������
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
     * ��������ȷ��
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
            //���»��� ����ȷ��״̬
            $updateA = "update oa_contract_contract set dealStatus='0' where id='" . $cid . "'";
            $this->_db->query($updateA);
            $updateB = "update oa_contract_equ_link set ExaStatus='���' where contractId='" . $cid . "'";
            $this->_db->query($updateB);
            $updateSql = "update oa_contract_cost set state='2',costAppName='" . $userName . "',costAppId='" . $userId . "',costAppDate='" . $costTime . "' where contractId = '" . $cid . "' and state='3' ";
            $this->_db->query($updateSql);

            $handleDao->handleAdd_d(array(
                "cid" => $conId,
                "stepName" => "�������ȷ��",
                "isChange" => $isChange,
                "stepInfo" => "",
            ));

        } else {
            $updateSql = "update oa_contract_cost set state='1' where contractId = '" . $cid . "' and state='3' ";
            $this->_db->query($updateSql);
            if($needSalesLine){
                $handleDao->handleAdd_d(array(
                    "cid" => $conId,
                    "stepName" => "����ȷ������",
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
//            //���»��� ����ȷ��״̬
//            $updateE = "update oa_contract_contract set saleCost=".$moneyAll." where id='".$oldId."'";
//            $this->_db->query($updateE);
//            $this->countCost($oldId);
        }
    }

    //�������ϱ��conProductId
    function updateConProductId_d($onlyProductId)
    {
        if (!empty($onlyProductId)) {
            $selectSql = "select id from oa_contract_product where onlyProductId = '" . $onlyProductId . "'";
            $selObj = $this->_db->get_one($selectSql);
            $updateSql = "update oa_contract_equ  set conProductId = '" . $selObj['id'] . "' where onlyProductId = '" . $onlyProductId . "' and isBorrowToorder != 1";
            $this->_db->query($updateSql);
        }
    }

    //���½���ת�������ϵ� proId
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
     * �ж� �Ƿ������ͬ���� ��ͬ���Ͳ�Ʒ
     * $ff 0  ������ 1 ���ڣ���Ϊȷ�ϳɱ� 2����������ȷ�ϳɱ�
     */
    function deffLinePro($costArr, $cid)
    {
        $proDao = new model_contract_contract_product();
        $proArr = $proDao->getCostInfoProBycId($cid);
        $issale = $costArr['issale'];
        $productLine = $costArr['productLine'];
        //�жϲ���ȷ�ϼ�¼����ǰ�������Ƿ����ͬ������������
        $sql = "select * from oa_contract_cost where contractId = '" . $cid . "' and Exastate='0' and state =1 and productLine='" . $productLine . "' and issale != '" . $issale . "'";
        $cArr = $this->_db->getArray($sql);
        $ff = 0;
        if (!empty($cArr)) {
            $ff = '2';
        } else {
            if ($costArr['issale'] == '1') { //�����࣬ ���ж��Ƿ����ͬ���߷������Ʒ�Ƿ���ڼ� �Ƿ���ȷ�ϳɱ�.
                foreach ($proArr as $k => $v) {
                    if (($v['goodsTypeId'] == '17') && ($v['exeDeptName'] == $costArr['productLineName'])) {
                        $ff = '1';
                    }
                }
            } else if ($costArr['issale'] == '0') { //�������࣬ ���ж��Ƿ����ͬ�����������Ʒ�Ƿ���ڼ� �Ƿ���ȷ�ϳɱ�.
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
     * ��ȡ����ͼ�� ��Ʒ ִ�в����ַ���
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
     * ��ͬ�������ݴ���
     */
    function basicDataProcess_d($rows)
    {
        $esmDao = new model_engineering_project_esmproject();
        $conprojectDao = new model_contract_conproject_conproject();
        //��ȡ��Ŀ���ʵʱ����
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
                //��Ŀ���
                if (!isset($rows[$key]['projectCode'])) {
                    $rows[$key]['projectCode'] = $projectCode;
                } else {
                    $rows[$key]['projectCode'] .= "\n" . $projectCode;
                }
                //��Ŀ����
                if (!isset($rows[$key]['projectName'])) {
                    $rows[$key]['projectName'] = $projectName;
                } else {
                    $rows[$key]['projectName'] .= "\n" . $projectName;
                }
                //��Ŀ���
                if (!isset($rows[$key]['proMoney'])) {
                    $rows[$key]['proMoney'] = $proMoney;
                } else {
                    $rows[$key]['proMoney'] .= "\n" . $proMoney;
                }
                //��Ŀ����
                if (!isset($rows[$key]['projectType'])) {
                    if (empty($esmId)) {
                        $rows[$key]['projectType'] = "������";
                    } else {
                        $rows[$key]['projectType'] = "������";
                    }
                } else {
                    if (empty($esmId)) {
                        $rows[$key]['projectType'] .= "\n������";
                    } else {
                        $rows[$key]['projectType'] .= "\n������";
                    }
                }
                //��Ŀ����
                if (!isset($rows[$key]['estimates'])) {
                    $rows[$key]['estimates'] = $estimates;
                } else {
                    $rows[$key]['estimates'] .= "\n" . $estimates;
                }
                //��Ŀ����
                if (!isset($rows[$key]['cost'])) {
                    $rows[$key]['cost'] = $cost;
                } else {
                    $rows[$key]['cost'] .= "\n" . $cost;
                }
                //��Ŀ����
                if (!isset($rows[$key]['earnings'])) {
                    $rows[$key]['earnings'] = $earnings;
                } else {
                    $rows[$key]['earnings'] .= "\n" . $earnings;
                }
                //��Ŀ����
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
     * ��ȡ�����ù������̻�����
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
        $btn = '<input type="button" class="txt_btn_a" value=" ��  �� " onclick="toSubmit();"/>' .
            '&nbsp;&nbsp;<input type="button" class="txt_btn_a" value=" ��  �� " onclick="tb_remove();"/>';
        if ($rs) {
            $rsLength = count($rs);
            $str = '<table class="main_table" style="width:800px;">' .
                '<td colspan="99" class="form_header"><input id="allCheckbox" type="checkbox" onclick="chanceCheckAll(this)"/>' .
                'ȫѡ ����<span id="chanceAllNum">' . $rsLength . '</span>����¼,ѡ��<span id="chanceNum">0</span>����' .
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
            return '<table class="main_table" style="width:800px;"><tr><td colspan="5"> - û���ҵ���ص��̻����� - <br />' . $btn . '</td></tr></table>';
        }
    }

    /**
     * ���� T��ȷ���б�� ��Ŀ����ʱ��
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
     * ���º�ͬ
     */
     function updateConState_d(){
     	ini_set("memory_limit","1000M");
     	set_time_limit(0);
     	  $this->titleInfo("׼����ȡ��ͬ����...");
     	  $sql = "select * from oa_contract_contract where isTemp=0 and (ExaDTOne is not null or ExaDTOne <> '') ";
          $arr = $this->_db->getArray($sql);
          $this->titleInfo("��ȡ��ͬ���ݳɹ���׼������,ʱ���Գ����벻Ҫ�ر�ҳ��...");
          $arrNum = count($arr) / 10;
          $p = 10;
          $a = $arrNum;
          foreach($arr as $k => $v){
          	 if($k >= $a){
          	 	$this->titleInfo("�Ѹ���".$p."%...");
          	 	$a = $a + $arrNum;
          	 	$p = $p + 10;
          	 }
          	 $this->updateOutStatus_d($v['id']);
          }
          $this->titleInfo("������ɣ�");
     }
     //��ʾ��Ϣ
	 function titleInfo($ff){
	 	echo str_pad($ff,4096).'<hr />';
		flush();
		ob_flush();
		sleep(0.1);
	 }


	 /**
	  * ����id�� ͳ�Ƽ���δ�ؿ���Ϣ���
	  */
	function getUnIncomeArr($idStr,$overPointY='',$overPointM=''){
		$idArr = explode(",",$idStr);
		$reDao = new model_contract_contract_receiptplan();
        $Year = ($overPointY == '')? "Y" : $overPointY;
        $Month = ($overPointM == '')? "n" : $overPointM;
		$season = ceil((date($Month))/3);//�����ǵڼ�����
		$Q_star = date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date($Year)));//�����ȿ�ʼ����
		$Q_end =  date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date($Year))),date($Year)));//�����Ƚ�������
		$Q_Next_end = date("Y-m-d", strtotime("+3 months", strtotime($Q_end)));//�¼��Ƚ�������

        $overPointStr = ($overPointY!='' && $overPointM!='')? "{$overPointY}-{$overPointM}-1" : "Y-m-1";
		$t_3 = date("Y-m-1", strtotime("-3 months", strtotime(date($overPointStr))));//����3�������ڽڵ�
		$t_6 = date("Y-m-1", strtotime("-6 months", strtotime(date($overPointStr))));//����6�������ڽڵ�
		$t_12 = date("Y-m-1", strtotime("-12 months", strtotime(date($overPointStr))));//����12�������ڽڵ�

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
                            //���ڽ��.
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
	 * ���ݺ�ͬid ��ȡ��ͬ����
	 */
	function getConFeeByid($cid){
		$sql = "SELECT costMoney FROM oa_finance_cost WHERE isDel = 0 AND isTemp = 0 AND contractId = '".$cid."'";
		$rtn = $this->_db->getArray($sql);
		return $rtn[0]['costMoney'];
	}

	/**
	 * �������ص�����
	 */
	function workflowCallBack($spid){
        $otherdatas = new model_common_otherdatas();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo['objId'];
        $this->dealAfterAudit_d($objId);
        $this->confirmContract_d($spid);
	}

	/**
	 * �������ص�����_���
	 */
	function workflowCallBack_change($spid){
       $this->confirmChange($spid);
	}
	/**
	 * �������ص�����_�ر�
	 */
	function workflowCallBack_close($spid){
       $this->confirmClose_d($spid);
	}

    /**
     * ���º�ͬ������ֵ
     */
    function updateConRedundancy_d(){

        //������Ŀ��Ϣ
        $proDao = new model_contract_conproject_conproject();
        $proDao->createProjectBySale_d(11708);

//        $this->searchArr['isTemp'] = "0";
//        $this->searchArr['states'] = "2,3,4";
//        $rows = $this->list_d();
//        //��Ŀ
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

    //��ȡ��ͬ�ۺ�˰��
    function getTxaRateNew($invoicTypeArr,$invoiceCode,$invoiceValue)
    {
        $conArr['invoiceCode'] = $invoiceCode;
        $conArr['invoiceValue'] = $invoiceValue;
        $conInvoiceArr = $this->makeInvoiceValueArr($conArr);

        $backArr = array("isNoInvoiceCont" => false,"cRate" => 0);
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ�ӷ���Ĭ��˰��
                $backArr['isNoInvoiceCont'] = true;
            }else{
                //�����������ĺ�ͬ���������޳ɱ�
                //��Ʊ������
                $rate = 0;
                $typeMoney = 0;
                $cMoney = 0; //ȡʵ�ʿ�Ʊ��������㣬������ֱ��ȡ��ͬ��
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

                //������ǻ���࿪Ʊ����ֱ�ӷ��ظÿ�Ʊ˰��
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

    //��ȡ��ͬ�ۺ�˰��
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

        $rate = 0; //�ۺ�˰��
        if(!empty($conInvoiceArr)){
            if(isset($conInvoiceArr['HTBKP'])){// ��Ʊ���ͺ�������Ʊ���ĺ�ֱͬ�ӷ���Ĭ��˰��
                $rate = 0;// ��ʱĬ��Ϊ0,����Ҫ�޸���������
            }else{
                //�����������ĺ�ͬ���������޳ɱ�
                //��Ʊ������
                $typeMoney = 0;
                $cMoney = 0; //ȡʵ�ʿ�Ʊ��������㣬������ֱ��ȡ��ͬ��
                foreach ($conInvoiceArr as $k => $v) {
                    if ($v >=0) {
                        $rate = $invoicTypeArr[$k] / 100;
                        $rates = 1 + $rate;
                        $typeMoney += bcdiv($v, $rates, 8);
                        $cMoney += $v;
                    }
                }

                //������ǻ���࿪Ʊ����ֱ�ӷ��ظÿ�Ʊ˰��
                if (count($conInvoiceArr) > 1) {
                    $typeMoney = sprintf("%.3f", $typeMoney);
                    $rate = round(bcsub(bcdiv($cMoney, $typeMoney, 8), 1, 8), 4);
                }
            }
        }
        return $rate;
    }

    /**
     * ��ͬ�Լ����
     */
    function objCom_d($obj){
         //��Ʒ���
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
        $esmProject->getParam(array("contractId" => $obj['id'])); //����ǰ̨��ȡ�Ĳ�����Ϣ
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
     * ���º�ͬ��Ϣ������ֶ�
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

        if($from != 'contractUpdate'){// �ֶ����µĲ���¼,ֻ��¼ϵͳԤ�����µ�
            // ��־д��
            $now = time();
            $thisMonth = date('n', $now);// ��ǰ��
            $logDao = new model_engineering_baseinfo_esmlog();
            $logDao->addLog_d(-1, '���º�ͬ���������', $processNum . '|' . $thisMonth);
        }else{
            return true;
        }
    }

    /**
     * ��ͬ�Լ���� - �б�
     */
    function objComList_d($obj){
        //��Ʒ���
        $pMoneySql = "select sum(money) as pMoney from oa_contract_product where contractId='".$obj['id']."'";
        $pMoneyArr = $this->_db->getArray($pMoneySql);
        if($obj['contractMoney'] == $pMoneyArr[0]['pMoney']){
            $rtn['productCheck'] = "��";
        }else{
            $rtn['productCheck'] = "��";
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
            $rtn['invoiceCheck'] = "��";
        }else{
            $rtn['invoiceCheck'] = "��";
        }
        if($obj['state'] == '4' || $obj['state'] == '3'){

            $compareMoney = $obj['contractMoney'] - $obj['deductMoney'] - $obj['uninvoiceMoney'];
            if(intVal($obj['invoiceMoney']) == intVal($compareMoney)){
                $rtn['invoiceTrueCheck'] = "��";
            }else{
                $rtn['invoiceTrueCheck'] = "��";
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
            // ֻ�к�ͬ��Ŀ�ż�����Щ����
            if ($va['contractType'] == 'GCXMYD-01' && $pType == 'esm') {
                $bb = $esmDao->contractDeal($va);
                $esmMoney += $bb['projectMoneyWithTax'];
            }else{//��Ʒ�� Ԥ�� ��ĿǰĬ�ϼ�0
                $esmMoney += $comDao->getAccMoneyBycid($va['contractId'], $va['newProLine'], 11);//��Ŀ���
            }
        }
        if($obj['state']=='2' || $obj['state']=='4' || $obj['state']=='3'){
            if($obj['contractMoney'] == $esmMoney){
                $rtn['projectCheck'] = "��";
            }else{
                $rtn['projectCheck'] = "��";
            }
        }else{
            $rtn['projectCheck'] = "-";
        }

        // �жϹ�����ͬ�Ƿ���ڲ���Ʊ�Ŀ�Ʊ����,
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
     * ������Ŀ״̬
     * @param $id
     * @return string
     */
    function updateProjectStatus_d($id) {
        $projectDao = new model_engineering_project_esmproject();

        $obj = $this->get_d($id);
        //���º�ͬ������״̬
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
     * ���º�ͬ��Ŀ���ݵ�����ֵ
     *
     * @param string $cid
     * @param string $from
     * @param string $contractStates ��ͬ�ĸ��·�Χ��Ĭ��Ϊ�ǡ��ѹرա������쳣�رա�
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

        // PMS 522 ��ͬӦ�տ�����������ô���
        $SpecRecordsForNoSurincomeArr = $this->dealSpecRecordsForNoSurincome();

        $esmprojectDao = new model_engineering_project_esmproject(); // ������Ŀ
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
            // ���ݺ�ͬ��չ�ֶα����¶�ȡ��Ʊ�����Լ���Ӧ�ֶ���Ϣ
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

            // PMS 522 ���ں�ͬ�޹�����Ŀ�ģ��޹�����Ŀʱ������ȡ��Ʊ���տ���ȵ���Сֵ��
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
        // ��־д��
        if($from != 'contractUpdate'){
            $now = time();
            $thisMonth = date('n', $now);// ��ǰ��
            $logDao = new model_engineering_baseinfo_esmlog();
            $logDao->addLog_d(-1, '���º�ͬ��Ŀ���ݵ�����ֵ', count($rows) . '|' . $thisMonth);
        }else{
            return true;
        }
    }

    /**
     * ��ͬӦ�տ�����������ô���
     * @param string $to
     * @param array $rows
     * @param array $dealFields
     * @return array
     */
    function dealSpecRecordsForNoSurincome($to = "getConfigs",$rows = array(),$dealFields = array()){
        // ��ȡ�������������
        $configuratorDao = new model_system_configurator_configurator();
        $matchConfigItem = $configuratorDao->getConfigItems('YSKPZ');

        // �����������, ����Ӧ����
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
     * ����ͬ��¼�еĿ�Ʊ��Ϣ��¼ת�������Ӧ�ĸ�ʽ
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
     * ��ӿ�Ʊ��Ϣ��¼
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
     * ��ȡ��ͬ��ǰ���޸ĵ���С��ͬ��
     * @param $cid
     * @return mixed
     */
    function getValidContractMoney($cid){
        $chkSql = <<<EOT
        select c.invoiceMoney,c.uninvoiceMoney,i.applyingInvoice,c.deductMoney from oa_contract_contract c 
        left join (
            select objId,sum(invoiceMoney-payedAmount) as applyingInvoice from oa_finance_invoiceapply where 
            objType = 'KPRK-12' and objId = '$cid' and exaStatus <> '���'
        )i on i.objId = c.id
        where c.id = '$cid';
EOT;
        $result = $this->_db->get_one($chkSql);
        return $result;
    }

    /**
     * ��ȡ��ͬ������ֶ���Ϣ
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