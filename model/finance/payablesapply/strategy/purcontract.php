<?php
/*
 * Created on 2011-5-5
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/finance/payablesapply/ipayablesapply.php');

class model_finance_payablesapply_strategy_purcontract extends model_base implements ipayablesapply
{

    //���Զ�Ӧ��
    private $thisClass = 'model_purchase_contract_purchasecontract';

    //������ӱ� - �豸
    private $equClass = 'model_purchase_contract_equipment';

    //��Ӧ����
    private $thisCode = 'YFRK-01';

    //���Ƹ����ʱ��groupby�ֶ�
    public $groupByCodeForPush = 'c.payapplyId,c.objId,c.objType,c.expand1';

    //��չ�ֶ�˵��
    //expand1 : ��Ӧ�ɹ�����Id
    //expand3 : �������ϼ�˰�ϼ�

    //Դ����Ϣ��ȡ
    function getObjInfo_d($obj)
    {
        $innerObjDao = new $this->thisClass();

        $innerObjDao->searchArr = array('ids' => $obj['objId']);
        $innerObjDao->setCompany(0);
        $innerObj = $innerObjDao->list_d('select_leftjoin');

        $rtObj = $innerObj[0];

        //���õ���Դ������
        $rtObj['sourceType'] = $this->thisCode;

        $datadictDao = new model_system_datadict_datadict();
        $rtObj['sourceTypeCN'] = $datadictDao->getDataNameByCode($this->thisCode);

        $purOrderIds = array();
        foreach ($innerObj as $v) {
            if (!in_array($v['id'], $purOrderIds)) {
                $purOrderIds[] = $v['id'];
            }
        }

        // ����ⵥ��ȡ
        $stockInDao = new model_stock_instock_stockin();
        $rtObj["entryDate"] = $stockInDao->getEntryDateForPurOrderId_d(implode(',', $purOrderIds));

        //���ø�����ϸ
        $rtObj['detail'] = $innerObj;

        //��ȡ��ǰ��¼�˲���
        $otherDataDao = new model_common_otherdatas();
        $deptRows = $otherDataDao->getUserDatas($rtObj['createId'], array('DEPT_NAME', 'DEPT_ID'));
        $rtObj['deptName'] = $deptRows['DEPT_NAME'];
        $rtObj['deptId'] = $deptRows['DEPT_ID'];

        return $rtObj;
    }

    /**
     * ��Ⱦ����ҳ��ӱ�
     */
    //update chenrf ��Ӳɹ�����20130424
    function initAdd_d($object, $mainObj)
    {
        $str = null;
        $i = null;
//		print_r($object);
        if (is_array($object['detail'])) {

            $i = 0;
            $datadictDao = new model_system_datadict_datadict();
            $objTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			echo "<pre>";
//			print_r($object['detail']);	
            foreach ($object['detail'] as $val) {


                //����purchType��ȡ�ɹ�����
                $typeName = $datadictDao->find(array('dataCode' => $val['purchType']), null, 'dataName');

                $innerObjMoney = $mainObj->getApplyMoneyByPurExpand1_d($val['id'], $this->thisCode, $val['Pid']);
                $canApply = round(bcsub($val['moneyAll'], $innerObjMoney, 4),2);
                if ($canApply == 0) {
                    continue;
                }
                $i++;
                $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                $str .= <<<EOT
					<tr class="$thisClass">
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
						</td>
						<td>
							$i
						</td>
						
						<td>
							<input type="text" id="contractCode$i" value="$objTypeCN" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[hwapplyNumb]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[id]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$this->thisCode"/>
                            <input type="hidden" name="payablesapply[detail][$i][expand1]" value="$val[Pid]"/>
                            <input type="hidden" name="payablesapply[detail][$i][expand3]" value="$val[moneyAll]"/>
						</td>
						<td>
                            <input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$canApply" class="txtmiddle formatMoney"/>
                            <input type="hidden" id="oldMoney$i" value="$canApply"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[allMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][allAmount]" id="allCount$i" value="$val[moneyAll]" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][number]" id="number$i" value="$val[amountAll]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][price]" id="taxPrice$i" value="$val[applyPrice]" class="readOnlyTxtItem formatMoneySix" readonly="readonly"/>
                        </td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productNo]" id="productNo$i" value="$val[productNumb]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productName]" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][productId]" id="productId$i" value="$val[productId]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productModel]" id="productModel$i" value="$val[pattem]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][unitName]" id="unit$i" value="$val[units]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
						<td>
							<input type="text" id="purchType$i" value="{$typeName['dataName']}" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
					</tr>
EOT;
            }
        }
        return array($str, $i);
    }

    /**
     * ��Ⱦ����ҳ��ӱ� - ���ڵ�����
     */
    //update chenrf 20130424
    function initAddOne_d($object, $mainObj)
    {
        $str = null;
        $i = null;
//		print_r($object);
        if (is_array($object['detail'])) {
            $i = 0;
            $datadictDao = new model_system_datadict_datadict();
            $objTypeCN = $datadictDao->getDataNameByCode($this->thisCode);

            $interfObj = new model_common_interface_obj ();

//			echo "<pre>";
//			print_r($object['detail']);
            foreach ($object['detail'] as $val) {

                //����purchType��ȡ�ɹ�����
                $purchtypeCn = $interfObj->typeKToC($val['purchType']);
                if (count($object['detail']) == 1) {
                    $innerObjMoney = $mainObj->getApplyMoneyByPurAll_d($val['id'], $this->thisCode);
                    $allCanApply = $canApply = round(bcsub($val['moneyAll'], $innerObjMoney, 4),2);
                    if ($canApply == 0) {
                        continue;
                    }
                } else {
                    $innerObjMoney = $mainObj->getApplyMoneyByPurExpand1All_d($val['id'], $this->thisCode, $val['Pid']);
                    $canApply = round(bcsub($val['moneyAll'], $innerObjMoney, 4),2);
                    if ($canApply == 0) {
                        continue;
                    }
                    if (!isset($allCanApplyArr[$val['id']])) {
                        $allCanApplyArr[$val['id']]['money'] = bcsub(round($val['allMoney'], 2), $mainObj->getApplyMoneyByPurAll_d($val['id'], $this->thisCode), 2);
                    }
                }
                $i++;
                $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                $str .= <<<EOT
					<tr class="$thisClass" >
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
						</td>
						<td>
							$i
						</td>
						
						<td>
							<input type="text" id="contractCode$i" value="$objTypeCN" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[hwapplyNumb]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[id]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$this->thisCode"/>
                            <input type="hidden" name="payablesapply[detail][$i][expand1]" value="$val[Pid]"/>
                            <input type="hidden" name="payablesapply[detail][$i][expand3]" value="$val[moneyAll]"/>
                            <input type="hidden" name="payablesapply[detail][$i][expand2]" value="{$purchtypeCn}"/>
						</td>
						<td>
                            <input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$canApply" class="txtmiddle formatMoney"/>
                            <input type="hidden" id="oldMoney$i" value="$canApply"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[allMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][allAmount]" id="allCount$i" value="$val[moneyAll]" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][number]" id="number$i" value="$val[amountAll]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][price]" id="taxPrice$i" value="$val[applyPrice]" class="readOnlyTxtItem formatMoneySix" readonly="readonly"/>
                        </td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productNo]" id="productNo$i" value="$val[productNumb]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productName]" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][productId]" id="productId$i" value="$val[productId]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productModel]" id="productModel$i" value="$val[pattem]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][unitName]" id="unit$i" value="$val[units]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
						<td>
							<input type="text" id="purchType$i" value="{$purchtypeCn}" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
					</tr>
EOT;
            }
        }
        if (is_array($allCanApplyArr)) {
            $allCanApply = 0;
            foreach ($allCanApplyArr as $val) {
                $allCanApply = bcadd($allCanApply, $val['money'], 2);
            }
        }
        return array($str, $i, $allCanApply);
    }

    /**
     * ��Ⱦ�༭ҳ��ӱ�
     */

    function initEdit_d($object, $mainObj)
    {
        $str = null;
        $i = 0;
        if (is_array($object)) {
            $datadictDao = new model_system_datadict_datadict();
            $sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);

            foreach ($object as $val) {
                //update chenrf 20130424
                //����purchType��ȡ�ɹ�����

                //��ȡ������������
                $val['expand3'];
                $canApply = bcsub($val['expand3'], $mainObj->getApplyMoneyByPurExpand1All_d($val['objId'], $val['objType'], $val['expand1']), 2);
                //�����ݿ������� = ʣ��������� + �����ݽ��
                $canApply = bcadd($canApply, $val['money'], 2);

                $i++;
                $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';
                $str .= <<<EOT
                    <tr class="$thisClass">
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
						</td>
						<td>
							$i
						</td>
						
						<td>
							<input type="text" id="contractCode$i" value="$sourceTypeCN" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[objCode]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[objId]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$val[objType]"/>
							<input type="hidden" name="payablesapply[detail][$i][expand1]" value="$val[expand1]"/>
							<input type="hidden" name="payablesapply[detail][$i][expand3]" value="$val[expand3]"/>
							<input type="hidden" name="payablesapply[detail][$i][expand2]" value="{$val['expand2']}"/>
						</td>
						<td>
                            <input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$val[money]" class="txtmiddle formatMoney"/>
                            <input type="hidden" id="oldMoney$i" value="$canApply"/>
                            <input type="hidden" id="orgMoney$i" value="$val[money]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[purchaseMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][allAmount]" id="allCount$i" value="$val[allAmount]" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][number]" id="number$i" value="$val[number]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][price]" id="taxPrice$i" value="$val[price]" class="readOnlyTxtItem formatMoneySix" readonly="readonly"/>
                        </td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productNo]" id="productNo$i" value="$val[productNo]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productName]" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][productId]" id="productId$i" value="$val[productId]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productModel]" id="productModel$i" value="$val[productModel]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][unitName]" id="unit$i" value="$val[unitName]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
						<td>
							<input type="text" id="purchType$i" value="{$val['expand2']}" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
					</tr>
EOT;
            }
        }
        return array($str, $i);
    }

    /**
     * ��Ⱦ�鿴ҳ��ӱ�
     */
    function initView_d($object)
    {
        $str = null;
        $i = 0;
        if (is_array($object)) {
            $datadictDao = new model_system_datadict_datadict();
            $sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
            $mark = null;

            //��ȡ�Ѹ������Ͻ��
            $paydetailDao = new model_finance_payablesapply_detail();


            $contractMoney = $applyMoney = $contractDetailAmount = 0;
            $contractDetailNum = $productPayed = $productPayedAmonut = $productCanPayAmount = $amountIssuedAll = 0;
            foreach ($object as $val) {
                //��ȡ�������
                $amountIssued = $this->get_table_fields('oa_purch_apply_equ', ' basicId = ' . $val['objId'] . ' and id = ' . $val['expand1'], 'amountIssued');
                //update chenrf 20130424
                //����purchType��ȡ�ɹ�����

                if (empty($mark) || $mark != $val['objId']) {
                    $i++;
                    $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                    //��ȡ�������Ѹ���Ϣ
                    $payedDetail = $paydetailDao->getPayedDetailAll_d($val['objId']);
                    //��־λ
                    $mark = $val['objId'];
                    //��ͬ���ϼ�
                    $contractMoney = bcadd($contractMoney, $val['purchaseMoney'], 2);
                    //��������ϼ�
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);
                    //�����Ѹ����
                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);
                    //���Ͽɸ����
                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    $str .= <<<EOT
						<tr class="$thisClass">
							<td>
								$i
							</td>
							<td>
								<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
							</td>
							<td class="formatMoney">
								$val[purchaseMoney]
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
							<td>
								$val[expand2]
							</td>
						</tr>
EOT;
                } else {
                    //�Ѹ�����
                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);

                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    //��������ϼ�
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);

                    $str .= <<<EOT
						<tr class="tr_even">
							<td colspan="3">
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
							<td>
								$val[expand2]
							</td>
						</tr>
EOT;
                }

                //������
                $applyMoney = bcadd($applyMoney, $val['money'], 2);
                $contractDetailAmount = bcadd($contractDetailAmount, $val['allAmount'], 2);
                $contractDetailNum = bcadd($contractDetailNum, $val['number'], 2);
            }

            $str .= <<<EOT
				<tr class="tr_count">
					<td colspan="2"><b>��  ��</b></td>
					<td><b><span class="formatMoney">$contractMoney</span></b></td>
					<td><b><span class="formatMoney">$applyMoney</span></b></td>
					<td><b><span class="formatMoney">$productPayedAmonut</span></b></td>
					<td><b><span class="formatMoney">$productCanPayAmount</span></b></td>
					<td><b><span class="formatMoney">$contractDetailAmount</span></b></td>
					<td></td>
					<td><b><span class="formatMoney">$contractDetailNum</span></b></td>
					<td><b><span class="formatMoney">$amountIssuedAll</span></b></td>
					<td colspan="5" class="form_text_right">
					</td>
				</tr>
EOT;
        }
        return $str;
    }

    /**
     * ��Ⱦ��ӡҳ��ӱ�
     */
    function initPrint_d($object)
    {
        $str = null;
        $i = 0;
        if (is_array($object)) {
            $datadictDao = new model_system_datadict_datadict();
            $sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);
            //Դ����������
            $sourceCodeArr = array();
            $mark = null;
            foreach ($object as $val) {

                if (empty($mark) || $mark != $val['objId']) {
                    $i++;
                    $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';
                    $mark = $val['objId'];

                    //���Դ���Ų����ڣ�������Դ������
                    if (!in_array($val['objCode'], $sourceCodeArr)) {
                        array_push($sourceCodeArr, $val['objCode']);
                    }

                    $str .= <<<EOT
						<tr class="$thisClass">
							<td>
								$i
							</td>
							<td>
								$sourceTypeCN
							</td>
							<td>
								<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
							</td>
							<td class="formatMoney">
								$val[purchaseMoney]
							</td>
							<td class="formatMoney">
								$val[money]
							</td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[unitName]
							</td>
	                        <td>
	                            $val[number]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
						</tr>
EOT;
                } else {
                    $str .= <<<EOT
						<tr class="$thisClass">
							<td colspan="4">
							</td>
							<td class="formatMoney">
								$val[money]
							</td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[unitName]
							</td>
	                        <td>
	                            $val[number]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
						</tr>
EOT;
                }
            }
        }

        if (!empty($sourceCodeArr)) {
            return array($str, 'sourceCode' => implode($sourceCodeArr, ', '));
        } else {
            return $str;
        }
    }

    /**
     * ��Ⱦ��ӡҳ��ӱ�
     */
    function initAudit_d($object)
    {
        $str = null;
        $i = 0;
        if (is_array($object)) {
            $datadictDao = new model_system_datadict_datadict();
            $sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
            //��ʶ
            $mark = null;
            //�����������ϸid
            $thisDetailArr = array();

            //��ȡ�Ѹ�����
            $payablesDao = new model_finance_payables_payables();
            //��ȡ���ܷ�Ʊ���
            $invpurchasaeDao = new model_finance_invpurchase_invpurchase();
            //��ȡ�Ѹ������Ͻ��
            $paydetailDao = new model_finance_payablesapply_detail();
            //��ʼ���豸��Ϣ
            $equDao = new $this->equClass();

            $contractMoney = $applyMoney = $contractDetailAmount = $amountIssuedAll = 0;
            $contractDetailNum = $productPayed = $productPayedAmonut = $productCanPayAmount = 0;
            //����ϼ���
            $contractMoneyAll = $applyMoneyAll = $productPayedAmonutAll = $productCanPayAmountAll = $allCountAll = $contractDetailNumAll = $amountIssuedAllForm = 0;
            $payedDetail = array();
            //	print_r($object);
            foreach ($object as $val) {
                //��ȡ�������
                $amountIssued = $this->get_table_fields('oa_purch_apply_equ', ' basicId = ' . $val['objId'] . ' and id = ' . $val['expand1'], 'amountIssued');

                if (empty($mark) || $mark != $val['objId']) {
                    $i++;
                    $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';
                    // ��ȡ���ݵ�Դ���Ѹ������ϸ
                    $tempPayedDetail = $paydetailDao->getPayedDetailAll_d($val['objId']);
                    foreach ($tempPayedDetail as $tk => $tv) {
                        $payedDetail[$tk] = $tv;
                    }

                    //���в�����ϼ���
                    if (!empty($mark)) {
                        if ($contractDetailAmount != $contractMoney) {
                            //��ȡ����δ�������Ϣ
                            $thisDetailIds = implode(',', $thisDetailArr);
                            $rs = $equDao->getEquNotIn($mark, $thisDetailIds);
                            foreach ($rs as $v) {
                                //�Ѹ�����
                                $productPayed = isset($payedDetail[$v['id']]) ? $payedDetail[$v['id']] : 0;
                                $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);
                                //��ȡ�ɸ����
                                $productCanPay = bcsub($v['moneyAll'], $productPayed, 2);
                                $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);
                                //��ȡ�������
                                $amountIssued1 = $this->get_table_fields('oa_purch_apply_equ', ' basicId = ' . $mark . ' and id = ' . $v['id'], 'amountIssued');
                                $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued1, 2);

                                $contractDetailAmount = bcadd($contractDetailAmount, $v['moneyAll'], 2);
                                $contractDetailNum = bcadd($contractDetailNum, $v['amountAll'], 2);
                                $str .= <<<EOT
									<tr class="$thisClass" style='color:green'>
										<td colspan="3">
										</td>
										<td>
											<b><span class="formatMoney">0</span></b>
										</td>
				                        <td class="formatMoney">
				                            $productPayed
				                        </td>
				                        <td class="formatMoney">
				                            $productCanPay
				                        </td>
				                        <td class="formatMoney">
				                            $v[moneyAll]
				                        </td>
				                        <td class="formatMoneySix">
				                            $v[applyPrice]
				                        </td>
				                        <td class="formatMoney">
				                            $v[amountAll]
				                        </td>
				                        <td class="formatMoney">
				                        	$amountIssued1
				                        </td>
										<td>
											$v[productNumb]
										</td>
										<td>
											$v[productName]
										</td>
										<td>
											$v[pattem]
										</td>
										<td>
											$v[units]
										</td>
										
									</tr>
EOT;
                            }
                        }
                        //��ʼ������������ϸid
                        $thisDetailArr = array();

                        //�ɹ��������Ѹ����
                        $payedMoney = $payablesDao->getPayedMoneyByPur_d($mark, 'YFRK-01');
                        //�Ѹ����ٷֱ�
                        $payedPersent = bcmul(bcdiv($payedMoney, $contractMoney, 4), 100, 2);
                        //�ɹ������ύ��Ʊ���
                        $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($mark);

                        $str .= <<<EOT
							<tr class="tr_count">
								<td colspan="2"><b>С��</b></td>
								<td><b><span class="formatMoney">$contractMoney</span></b></td>
								<td><b><span class="formatMoney">$applyMoney</span></b></td>
								<td><b><span class="formatMoney">$productPayedAmonut</span></b></td>
								<td><b><span class="formatMoney">$productCanPayAmount</span></b></td>
								<td><b><span class="formatMoney">$contractDetailAmount</span></b></td>
								<td></td>
								<td><b><span class="formatMoney">$contractDetailNum</span></b></td>
								<td><b><span class="formatMoney">$amountIssuedAll</span></b></td>
								<td colspan="5" class="form_text_right">
									<b>�����Ѹ���:<span class="formatMoney">$payedMoney</span><span title="�Ѹ����ռ����������">($payedPersent%)</span></b> ,
									<b>������Ʊ��:<span class="formatMoney">$handInvoiceMoney</span></b>
								</td>
							</tr>
							<tr><td colspan="12"></td></tr>
EOT;
                        $contractMoneyAll = bcadd($contractMoneyAll, $contractMoney, 2);
                        $applyMoneyAll = bcadd($applyMoneyAll, $applyMoney, 2);
                        $productPayedAmonutAll = bcadd($productPayedAmonutAll, $productPayedAmonut, 2);
                        $productCanPayAmountAll = bcadd($productCanPayAmountAll, $productCanPayAmount, 2);
                        $allCountAll = bcadd($allCountAll, $contractDetailAmount, 2);
                        $contractDetailNumAll = bcadd($contractDetailNumAll, $contractDetailNum, 2);
                        $amountIssuedAllForm = bcadd($amountIssuedAllForm, $amountIssuedAll, 2);

                        //��ͬ���������ö�Ӧ��ֵ
                        $applyMoney = $contractMoney = $contractDetailAmount = $contractDetailNum = $amountIssuedAll = $productPayedAmonut = $productCanPayAmount = 0;
                    }
                    $mark = $val['objId'];

                    $contractMoney = bcadd($contractMoney, $val['purchaseMoney'], 2);
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);


                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);

                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    $str .= <<<EOT
						<tr class="$thisClass">
							<td>
								$i
							</td>
							<td>
								<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
							</td>
							<td class="formatMoney">
								$val[purchaseMoney]
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
							<td>
								$val[expand2]
							</td>
						</tr>
EOT;
                } else {
                    //�Ѹ�����
                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);

                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    $str .= <<<EOT
						<tr class="tr_even">
							<td colspan="3">
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
							<td>
								$val[expand2]
							</td>
						</tr>
EOT;
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);
                }

                //�����������ϸid
                array_push($thisDetailArr, $val['expand1']);

                //������
                $applyMoney = bcadd($applyMoney, $val['money'], 2);
                $contractDetailAmount = bcadd($contractDetailAmount, $val['allAmount'], 2);
                $contractDetailNum = bcadd($contractDetailNum, $val['number'], 2);
            }

            $contractMoneyAll = bcadd($contractMoneyAll, $contractMoney, 2);
            $applyMoneyAll = bcadd($applyMoneyAll, $applyMoney, 2);
            $productPayedAmonutAll = bcadd($productPayedAmonutAll, $productPayedAmonut, 2);
            $productCanPayAmountAll = bcadd($productCanPayAmountAll, $productCanPayAmount, 2);
            $allCountAll = bcadd($allCountAll, $contractDetailAmount, 2);
            $contractDetailNumAll = bcadd($contractDetailNumAll, $contractDetailNum, 2);
            $amountIssuedAllForm = bcadd($amountIssuedAllForm, $amountIssuedAll, 2);

            $str .= <<<EOT
				<tr class="tr_count">
					<td colspan="2"><b>�ϼ�</b></td>
					<td><b><span class="formatMoney">$contractMoneyAll</span></b></td>
					<td><b><span class="formatMoney">$applyMoneyAll</span></b></td>
					<td><b><span class="formatMoney">$productPayedAmonutAll</span></b></td>
					<td><b><span class="formatMoney">$productCanPayAmountAll</span></b></td>
					<td><b><span class="formatMoney">$allCountAll</span></b></td>
					<td></td>
					<td><b><span class="formatMoney">$contractDetailNumAll</span></b></td>
					<td><b><span class="formatMoney">$amountIssuedAllForm</span></b></td>
					<td colspan="5" class="form_text_right"></td>
				</tr>
EOT;
//			print_r($thisDetailArr);
        }
        return $str;
    }


    /***************************  S �˿�ִ��� *******************************/
    /**
     * ��Ⱦ�˿�����ҳ��
     */
    function initAddOneRefund_d($object, $mainObj)
    {
        $str = null;
        $i = null;
//		print_r($object);
        if (is_array($object['detail'])) {
            $i = 0;
            $datadictDao = new model_system_datadict_datadict();
            $objTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			echo "<pre>";
//			print_r($object['detail']);
            foreach ($object['detail'] as $val) {
//			print_r($val);

                //�˿����ж�
                if (count($object['detail']) == 1) {
                    $innerObjMoney = $mainObj->getApplyMoneyByPurAll_d($val['id'], $this->thisCode);
                    $allCanApply = $canApply = $innerObjMoney;
                    if ($canApply == 0) {
                        continue;
                    }
                } else {
                    $innerObjMoney = $mainObj->getApplyMoneyByPurExpand1All_d($val['id'], $this->thisCode, $val['Pid']);
                    $canApply = $innerObjMoney;
                    if ($canApply == 0) {
                        continue;
                    }
                    if (!isset($allCanApplyArr[$val['id']])) {
                        $allCanApplyArr[$val['id']]['money'] = $mainObj->getApplyMoneyByPurAll_d($val['id'], $this->thisCode);
                    }
                }

                $i++;
                $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                $str .= <<<EOT
					<tr class="$thisClass">
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
						</td>
						<td>
							$i
						</td>
						<td>
							<input type="text" id="contractCode$i" value="$objTypeCN" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[hwapplyNumb]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[id]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$this->thisCode"/>
                            <input type="hidden" name="payablesapply[detail][$i][expand1]" value="$val[Pid]"/>
                            <input type="hidden" name="payablesapply[detail][$i][expand3]" value="$val[moneyAll]"/>
						</td>
						<td>
                            <input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$canApply" class="txtmiddle formatMoney"/>
                            <input type="hidden" id="oldMoney$i" value="$canApply"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[allMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][allAmount]" id="allCount$i" value="$val[moneyAll]" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][number]" id="number$i" value="$val[amountAll]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][price]" id="taxPrice$i" value="$val[applyPrice]" class="readOnlyTxtItem formatMoneySix" readonly="readonly"/>
                        </td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productNo]" id="productNo$i" value="$val[productNumb]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productName]" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][productId]" id="productId$i" value="$val[productId]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productModel]" id="productModel$i" value="$val[pattem]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][unitName]" id="unit$i" value="$val[units]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
					</tr>
EOT;
            }
        }
        if (is_array($allCanApplyArr)) {
            $allCanApply = 0;
            foreach ($allCanApplyArr as $val) {
                $allCanApply = bcadd($allCanApply, $val['money'], 2);
            }
        }
        return array($str, $i, $allCanApply);
    }

    /**
     * ��Ⱦ�༭ҳ��ӱ�
     */
    function initEditRefund_d($object, $mainObj)
    {
        $str = null;
        $i = 0;
        if (is_array($object)) {
            $datadictDao = new model_system_datadict_datadict();
            $sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
//			print_r($object['detail']);

            foreach ($object as $val) {
                //��ȡ������������
                $canApply = $mainObj->getApplyMoneyByPurExpand1All_d($val['objId'], $val['objType'], $val['expand1']);
                //�����ݿ������� = ʣ��������� + �����ݽ��
                $canApply = bcadd($canApply, $val['money'], 2);

                $i++;
                $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';
                $str .= <<<EOT
                    <tr class="$thisClass">
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="ɾ����">
						</td>
						<td>
							$i
						</td>
						<td>
							<input type="text" id="contractCode$i" value="$sourceTypeCN" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][objCode]" id="objCode$i" value="$val[objCode]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][objId]" id="objId$i" value="$val[objId]"/>
							<input type="hidden" name="payablesapply[detail][$i][objType]" id="objType$i" value="$val[objType]"/>
							<input type="hidden" name="payablesapply[detail][$i][expand1]" value="$val[expand1]"/>
							<input type="hidden" name="payablesapply[detail][$i][expand3]" value="$val[expand3]"/>
						</td>
						<td>
                            <input type="text" name="payablesapply[detail][$i][money]" id="money$i" onblur="checkMax($i);countAll();" value="$val[money]" class="txtmiddle formatMoney"/>
                            <input type="hidden" id="oldMoney$i" value="$canApply"/>
                            <input type="hidden" id="orgMoney$i" value="$val[money]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][purchaseMoney]" id="purchaseMoney$i" value="$val[purchaseMoney]" class="readOnlyTxtMiddle formatMoney" readonly="readonly"/>
						</td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][allAmount]" id="allCount$i" value="$val[allAmount]" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][number]" id="number$i" value="$val[number]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="payablesapply[detail][$i][price]" id="taxPrice$i" value="$val[price]" class="readOnlyTxtItem formatMoneySix" readonly="readonly"/>
                        </td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productNo]" id="productNo$i" value="$val[productNo]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productName]" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="payablesapply[detail][$i][productId]" id="productId$i" value="$val[productId]"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][productModel]" id="productModel$i" value="$val[productModel]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][unitName]" id="unit$i" value="$val[unitName]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="payablesapply[detail][$i][expand2]" id="unit$i" value="$val[expand2]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
					</tr>
EOT;
            }
        }
        return array($str, $i);
    }

    /**
     * ��Ⱦ�鿴ҳ��ӱ�
     */
    function initViewRefund_d($object)
    {
        $str = null;
        $i = 0;
        if (is_array($object)) {
            $datadictDao = new model_system_datadict_datadict();
            $sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
            $mark = null;

            //��ȡ�Ѹ������Ͻ��
            $paydetailDao = new model_finance_payablesapply_detail();

            $contractMoney = $applyMoney = $contractDetailAmount = 0;
            $contractDetailNum = $productPayed = $productPayedAmonut = $productCanPayAmount = $amountIssuedAll = 0;
            foreach ($object as $val) {
                //��ȡ�������
                $amountIssued = $this->get_table_fields('oa_purch_apply_equ', ' basicId = ' . $val['objId'] . ' and id = ' . $val['expand1'], 'amountIssued');

                if (empty($mark) || $mark != $val['objId']) {
                    $i++;
                    $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                    //��ȡ�������Ѹ���Ϣ
                    $payedDetail = $paydetailDao->getPayedDetailAll_d($val['objId']);
                    //��־λ
                    $mark = $val['objId'];
                    //��ͬ���ϼ�
                    $contractMoney = bcadd($contractMoney, $val['purchaseMoney'], 2);
                    //��������ϼ�
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);
                    //�����Ѹ����
                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);
                    //���Ͽɸ����
                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    $str .= <<<EOT
						<tr class="$thisClass">
							<td>
								$i
							</td>
							<td>
								<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
							</td>
							<td class="formatMoney">
								$val[purchaseMoney]
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
						</tr>
EOT;
                } else {
                    //�Ѹ�����
                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);

                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    //��������ϼ�
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);

                    $str .= <<<EOT
						<tr class="tr_even">
							<td colspan="3">
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
						</tr>
EOT;
                }

                //������
                $applyMoney = bcadd($applyMoney, $val['money'], 2);
                $contractDetailAmount = bcadd($contractDetailAmount, $val['allAmount'], 2);
                $contractDetailNum = bcadd($contractDetailNum, $val['number'], 2);
            }

            $str .= <<<EOT
				<tr class="tr_count">
					<td colspan="2"><b>��  ��</b></td>
					<td><b><span class="formatMoney">$contractMoney</span></b></td>
					<td><b><span class="formatMoney">$applyMoney</span></b></td>
					<td><b><span class="formatMoney">$productPayedAmonut</span></b></td>
					<td><b><span class="formatMoney">$productCanPayAmount</span></b></td>
					<td><b><span class="formatMoney">$contractDetailAmount</span></b></td>
					<td></td>
					<td><b><span class="formatMoney">$contractDetailNum</span></b></td>
					<td><b><span class="formatMoney">$amountIssuedAll</span></b></td>
					<td colspan="5" class="form_text_right">
					</td>
				</tr>
EOT;
        }
        return $str;
    }

    /**
     * ��Ⱦ��ӡҳ��ӱ�
     */
    function initAuditRefund_d($object)
    {
        $str = null;
        $i = 0;
        if (is_array($object)) {
            $datadictDao = new model_system_datadict_datadict();
            $sourceTypeCN = $datadictDao->getDataNameByCode($this->thisCode);
            $mark = null;
            //�����������ϸid
            $thisDetailArr = array();

            //��ȡ�Ѹ�����
            $payablesDao = new model_finance_payables_payables();
            //��ȡ���ܷ�Ʊ���
            $invpurchasaeDao = new model_finance_invpurchase_invpurchase();
            //��ȡ�Ѹ������Ͻ��
            $paydetailDao = new model_finance_payablesapply_detail();
            //��ʼ���豸��Ϣ
            $equDao = new $this->equClass();

            $contractMoney = $applyMoney = $contractDetailAmount = $amountIssuedAll = 0;
            $contractDetailNum = $productPayed = $productPayedAmonut = $productCanPayAmount = 0;
            //����ϼ���
            $contractMoneyAll = $applyMoneyAll = $productPayedAmonutAll = $productCanPayAmountAll = $allCountAll = $contractDetailNumAll = $amountIssuedAllForm = 0;
            foreach ($object as $val) {
                //��ȡ�������
                $amountIssued = $this->get_table_fields('oa_purch_apply_equ', ' basicId = ' . $val['objId'] . ' and id = ' . $val['expand1'], 'amountIssued');

                if (empty($mark) || $mark != $val['objId']) {
                    $i++;
                    $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                    $payedDetail = $paydetailDao->getPayedDetailAll_d($val['objId']);

                    //���в�����ϼ���
                    if (!empty($mark)) {

                        $payedMoney = $payablesDao->getPayedMoneyByPur_d($mark, 'YFRK-01');
                        $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($mark);

                        $payedPersent = bcmul(bcdiv($payedMoney, $contractMoney, 4), 100, 2);

                        $str .= <<<EOT
							<tr class="tr_count">
								<td colspan="2"><b>С��</b></td>
								<td><b><span class="formatMoney">$contractMoney</span></b></td>
								<td><b><span class="formatMoney">$applyMoney</span></b></td>
								<td><b><span class="formatMoney">$productPayedAmonut</span></b></td>
								<td><b><span class="formatMoney">$productCanPayAmount</span></b></td>
								<td><b><span class="formatMoney">$contractDetailAmount</span></b></td>
								<td></td>
								<td><b><span class="formatMoney">$contractDetailNum</span></b></td>
								<td><b><span class="formatMoney">$amountIssuedAll</span></b></td>
								<td colspan="4" class="form_text_right">
									<b>�����Ѹ���:<span class="formatMoney">$payedMoney</span><span title="�Ѹ����ռ����������">($payedPersent%)</span></b> ,
									<b>������Ʊ��:<span class="formatMoney">$handInvoiceMoney</span></b>
								</td>
							</tr>
							<tr><td colspan="12"></td></tr>
EOT;
                        $contractMoneyAll = bcadd($contractMoneyAll, $contractMoney, 2);
                        $applyMoneyAll = bcadd($applyMoneyAll, $applyMoney, 2);
                        $productPayedAmonutAll = bcadd($productPayedAmonutAll, $productPayedAmonut, 2);
                        $productCanPayAmountAll = bcadd($productCanPayAmountAll, $productCanPayAmount, 2);
                        $allCountAll = bcadd($allCountAll, $contractDetailAmount, 2);
                        $contractDetailNumAll = bcadd($contractDetailNumAll, $contractDetailNum, 2);
                        $amountIssuedAllForm = bcadd($amountIssuedAllForm, $amountIssuedAll, 2);

                        //��ͬ���������ö�Ӧ��ֵ
                        $applyMoney = $contractMoney = $contractDetailAmount = $contractDetailNum = $amountIssuedAll = $productPayedAmonut = $productCanPayAmount = 0;
                    }
                    $mark = $val['objId'];

                    $contractMoney = bcadd($contractMoney, $val['purchaseMoney'], 2);
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);


                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);

                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    $str .= <<<EOT
						<tr class="$thisClass">
							<td>
								$i
							</td>
							<td>
								<a href="#" onclick="openContract($val[objId],'$sourceTypeCN')">$val[objCode]</a>
							</td>
							<td class="formatMoney">
								$val[purchaseMoney]
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
						</tr>
EOT;
                } else {
                    //�Ѹ�����
                    $productPayed = isset($payedDetail[$val['expand1']]) ? $payedDetail[$val['expand1']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);

                    $productCanPay = bcsub($val['allAmount'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);

                    $str .= <<<EOT
						<tr class="tr_even">
							<td colspan="3">
							</td>
							<td>
								<b><span class="formatMoney">$val[money]</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $val[allAmount]
	                        </td>
	                        <td class="formatMoneySix">
	                            $val[price]
	                        </td>
	                        <td class="formatMoney">
	                            $val[number]
	                        </td>
	                        <td class="formatMoney">
	                            $amountIssued
	                        </td>
							<td>
								$val[productNo]
							</td>
							<td>
								$val[productName]
							</td>
							<td>
								$val[productModel]
							</td>
							<td>
								$val[unitName]
							</td>
						</tr>
EOT;
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);
                }

                //�����������ϸid
                array_push($thisDetailArr, $val['expand1']);

                //������
                $applyMoney = bcadd($applyMoney, $val['money'], 2);
                $contractDetailAmount = bcadd($contractDetailAmount, $val['allAmount'], 2);
                $contractDetailNum = bcadd($contractDetailNum, $val['number'], 2);
            }


            if ($contractDetailAmount != $contractMoney) {
                //��ȡ����δ�������Ϣ
                $thisDetailIds = implode(',', $thisDetailArr);
                $rs = $equDao->getEquNotIn($mark, $thisDetailIds);
//				print_r($rs);
                foreach ($rs as $v) {
                    //�Ѹ�����
                    $productPayed = isset($payedDetail[$v['id']]) ? $payedDetail[$v['id']] : 0;
                    $productPayedAmonut = bcadd($productPayedAmonut, $productPayed, 2);
                    //��ȡ�ɸ����
                    $productCanPay = bcsub($v['moneyAll'], $productPayed, 2);
                    $productCanPayAmount = bcadd($productCanPayAmount, $productCanPay, 2);
                    //��ȡ�������
                    $amountIssued = $this->get_table_fields('oa_purch_apply_equ', ' basicId = ' . $mark . ' and id = ' . $v['id'], 'amountIssued');
                    $amountIssuedAll = bcadd($amountIssuedAll, $amountIssued, 2);

                    $contractDetailAmount = bcadd($contractDetailAmount, $v['moneyAll'], 2);
                    $contractDetailNum = bcadd($contractDetailNum, $v['amountAll'], 2);
                    $str .= <<<EOT
						<tr class="$thisClass" style='color:green'>
							<td colspan="3">
							</td>
							<td>
								<b><span class="formatMoney">0</span></b>
							</td>
	                        <td class="formatMoney">
	                            $productPayed
	                        </td>
	                        <td class="formatMoney">
	                            $productCanPay
	                        </td>
	                        <td class="formatMoney">
	                            $v[moneyAll]
	                        </td>
	                        <td class="formatMoneySix">
	                            $v[applyPrice]
	                        </td>
	                        <td class="formatMoney">
	                            $v[amountAll]
	                        </td>
	                        <td class="formatMoney">
	                        	$amountIssued
	                        </td>
							<td>
								$v[productNumb]
							</td>
							<td>
								$v[productName]
							</td>
							<td>
								$v[pattem]
							</td>
							<td>
								$v[units]
							</td>
						</tr>
EOT;
                }
            }

            $payedMoney = $payablesDao->getPayedMoneyByPur_d($mark, 'YFRK-01');
            $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($mark);

            $payedPersent = bcmul(bcdiv($payedMoney, $contractMoney, 4), 100, 2);

            $str .= <<<EOT
				<tr class="tr_count">
					<td colspan="2"><b>С��</b></td>
					<td><b><span class="formatMoney">$contractMoney</span></b></td>
					<td><b><span class="formatMoney">$applyMoney</span></b></td>
					<td><b><span class="formatMoney">$productPayedAmonut</span></b></td>
					<td><b><span class="formatMoney">$productCanPayAmount</span></b></td>
					<td><b><span class="formatMoney">$contractDetailAmount</span></b></td>
					<td></td>
					<td><b><span class="formatMoney">$contractDetailNum</span></b></td>
					<td><b><span class="formatMoney">$amountIssuedAll</span></b></td>
					<td colspan="4" class="form_text_right">
						<b>�����Ѹ���:<span class="formatMoney">$payedMoney</span><span title="�Ѹ����ռ����������">($payedPersent%)</span></b> ,
						<b>������Ʊ��:<span class="formatMoney">$handInvoiceMoney</span></b>
					</td>
				</tr>
EOT;

            $contractMoneyAll = bcadd($contractMoneyAll, $contractMoney, 2);
            $applyMoneyAll = bcadd($applyMoneyAll, $applyMoney, 2);
            $productPayedAmonutAll = bcadd($productPayedAmonutAll, $productPayedAmonut, 2);
            $productCanPayAmountAll = bcadd($productCanPayAmountAll, $productCanPayAmount, 2);
            $allCountAll = bcadd($allCountAll, $contractDetailAmount, 2);
            $contractDetailNumAll = bcadd($contractDetailNumAll, $contractDetailNum, 2);
            $amountIssuedAllForm = bcadd($amountIssuedAllForm, $amountIssuedAll, 2);

            $str .= <<<EOT
				<tr class="tr_count">
					<td colspan="2"><b>�ϼ�</b></td>
					<td><b><span class="formatMoney">$contractMoneyAll</span></b></td>
					<td><b><span class="formatMoney">$applyMoneyAll</span></b></td>
					<td><b><span class="formatMoney">$productPayedAmonutAll</span></b></td>
					<td><b><span class="formatMoney">$productCanPayAmountAll</span></b></td>
					<td><b><span class="formatMoney">$allCountAll</span></b></td>
					<td></td>
					<td><b><span class="formatMoney">$contractDetailNumAll</span></b></td>
					<td><b><span class="formatMoney">$amountIssuedAllForm</span></b></td>
					<td colspan="4" class="form_text_right"></td>
				</tr>
EOT;
        }
        return $str;
    }

    /*************************** E �˿�ִ��� *******************************/


    /**
     * ���Ӹ�����Ϣ
     */
    function initAddInfo_d($object)
    {
        if (is_array($object)) {
            $objIdArr = array();
            foreach ($object as $val) {
                if (!in_array($val['objId'], $objIdArr)) {
                    array_push($objIdArr, $val['objId']);
                }
            }
            $objIds = implode($objIdArr, ',');
        }


        $datadictDao = new model_system_datadict_datadict();

        $invpurDetailDao = new model_finance_invpurchase_invpurdetail();
        $rs = $invpurDetailDao->getDetailByContractIds_d($objIds);

        if (is_array($rs)) {
            $str = null;
            $i = 0;
            $mark = null;
            //��ͬ���������ö�Ӧ��ֵ
            $allAmount = $allNumber = $countNumber = $countAmount = 0;
            foreach ($rs as $val) {
                $i++;

                if (empty($mark) || $mark != $val['formCode']) {
                    //���в�����ϼ���
                    if (!empty($mark)) {
                        $str .= <<<EOT
							<tr class="tr_count">
								<td colspan="2"><b>С��</b></td>
								<td colspan="7"></td>
								<td><b><span class="formatMoney">$allNumber</span></b></td>
								<td></td>
								<td><b><span class="formatMoney">$allAmount</span></b></td>
							</tr>
EOT;
                        $countNumber = bcadd($countNumber, $allNumber, 2);
                        $countAmount = bcadd($countAmount, $allAmount, 2);
                        //��ͬ���������ö�Ӧ��ֵ
                        $allAmount = $allNumber = 0;
                    }

                    $invoiceTypeCN = $datadictDao->getDataNameByCode($val['invType']);
                    $mark = $val['formCode'];

                    $allAmount = bcadd($allAmount, $val['allCount'], 2);
                    $allNumber = bcadd($allNumber, $val['number'], 2);

                    $str .= <<<EOT
						<tr class="tr_even">
							<td>
								$i
							</td>
							<td>
								$val[contractCode]
							</td>
							<td>
								$val[formNo]
							</td>
							<td>
								$invoiceTypeCN
							</td>
	                        <td>
	                            $val[formDate]
	                        </td>
	                        <td>
	                            $val[productNo]
	                        </td>
							<td>
								$val[productName]
							</td>
	                        <td>
	                            $val[productModel]
	                        </td>
	                        <td>
	                            $val[unit]
	                        </td>
							<td>
								$val[number]
							</td>
	                        <td class="formatMoneySix">
	                            $val[taxPrice]
	                        </td>
	                        <td class="formatMoney">
								$val[allCount]
							</td>
						</tr>
EOT;
                } else {
                    $str .= <<<EOT
						<tr class="tr_even">
							<td colspan="5">
							</td>
	                        <td>
	                            $val[productNo]
	                        </td>
							<td>
								$val[productName]
							</td>
	                        <td>
	                            $val[productModel]
	                        </td>
	                        <td>
	                            $val[unit]
	                        </td>
							<td>
								$val[number]
							</td>
	                        <td class="formatMoneySix">
	                            $val[taxPrice]
	                        </td>
	                        <td class="formatMoney">
								$val[allCount]
							</td>
						</tr>
EOT;

                    $allAmount = bcadd($allAmount, $val['allCount'], 2);
                    $allNumber = bcadd($allNumber, $val['number'], 2);

                }
            }

            $str .= <<<EOT
				<tr class="tr_count">
					<td colspan="2"><b>С��</b></td>
					<td colspan="7"></td>
					<td><b><span class="formatMoney">$allNumber</span></b></td>
					<td></td>
					<td><b><span class="formatMoney">$allAmount</span></b></td>
				</tr>
EOT;
            //�����ܼƺͼ�˰�ܼƴ���
            $countNumber = bcadd($countNumber, $allNumber, 2);
            $countAmount = bcadd($countAmount, $allAmount, 2);
            $str .= <<<EOT
				<tr class="tr_count">
					<td colspan="2"><b>�ϼ�</b></td>
					<td colspan="7"></td>
					<td><b><span class="formatMoney">$countNumber</span></b></td>
					<td></td>
					<td><b><span class="formatMoney">$countAmount</span></b></td>
				</tr>
EOT;


            return $str;
        } else {
            return "<tr><td colspan='30'>�����������</td></tr>";
        }
    }

    /******************************** �����������Ƹ���� *******************************/
    /**
     * �����������Ƹ����
     */
    function initPayablesAdd_i($object)
    {
        $str = ""; //���ص�ģ���ַ���
        $i = 0; //�б��¼���
        $firstOption = null;
        if ($object) {
            $datadictArr = $this->getDatadicts('YFRK');
            foreach ($object as $val) {
                $i++;
                if (empty($val['objCode'])) {
                    $firstOption = "<option value=''></option>";
                }
                $objTypeArr = $this->getDatadictsStr($datadictArr ['YFRK'], $val ['objType']);
                $payContent = $this->initPayContent_i($val);
                $str .= <<<EOT
					<tr><td>$i</td>
						<td>
							<select class="selectmiddel" id="objTypeList$i"  disabled='true' value="$val[objType]" name="payables[detail][$i][objType]">
								$firstOption
								$objTypeArr
							</select>
						</td>
						<td>
							<input type="text" class="readOnlyTxtNormal" id="objCode$i" readonly='readonly' value="$val[objCode]" name="payables[detail][$i][objCode]"/>
							<input type="hidden" id="objType$i" value="$val[objType]" name="payables[detail][$i][objType]"/>
							<input type="hidden" id="objId$i" value="$val[objId]" name="payables[detail][$i][objId]"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxt formatMoney" readonly='readonly' id="money$i" value="$val[money]" name="payables[detail][$i][money]"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtLong" readonly='readonly' value="$payContent" name="payables[detail][$i][payContent]"/>
							<input type="hidden" name="payables[detail][$i][expand1]" value="$val[expand1]"/>
						</td>
					</tr>
EOT;
            }
        }
        return array(
            $str,
            $i
        );
    }

    /**
     * ��ʼ��������ϸ
     */
    function initPayContent_i($object)
    {
//		print_r($object);
        $str = null;
        if (is_array($object)) {
            $str = " ���� [" . $object['productName'] . "(" . $object['productNo'] . ")]�Ĳɹ�����";
        }
        return $str;
    }

    /**
     * ����ȷ�ϸ���������֯��չ�ֶ�
     */
    function rebuildExpandArr_i($object)
    {
        $rebuildArr['expand1'] = $object['expand1'];
        $rebuildArr['payContent'] = $this->initPayContent_i($object);

        return $rebuildArr;
    }
}