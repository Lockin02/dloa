<?php
/**
 * @description: 采购合同
 * @date 2010-12-29 下午09:01:16
 */
header("Content-type: text/html; charset=gb2312");

class model_purchase_contract_purchasecontract extends model_base
{
    /*
	 * @desription 构造函数
	 * @author qian
	 * @date 2010-12-29 下午09:02:22
	 */
    function __construct()
    {
        $this->tbl_name = "oa_purch_apply_basic";
        $this->sql_map = "purchase/contract/purchasecontractSql.php";
        $this->datadictFieldArr = array("billingType", "paymentType", "paymentCondition");
        parent::__construct();
        //调用初始化对象关联类
        parent::setObjAss();
        $this->state = array(0 => array("stateEName" => "save", "stateCName" => "保存", "stateVal" => "0"), 1 => array("stateEName" => "approval", "stateCName" => "审批中", "stateVal" => "1"), 2 => array("stateEName" => "Locking", "stateCName" => "锁定", "stateVal" => "2"), 3 => array("stateEName" => "fightback", "stateCName" => "打回", "stateVal" => "3"), 4 => array("stateEName" => "execute", "stateCName" => "执行中", "stateVal" => "4"), 5 => array("stateEName" => "end", "stateCName" => "完成", "stateVal" => "5"), 6 => array("stateEName" => "close", "stateCName" => "中止", "stateVal" => "6"), 7 => array("stateEName" => "wite", "stateCName" => "正在执行", "stateVal" => "7"), 8 => array("stateEName" => "changeAuditing", "stateCName" => "变更审批中", "stateVal" => "8"), 9 => array("stateEName" => "delOrder", "stateCName" => "废除订单", "stateVal" => "9"), 10 => array("stateEName" => "closeOrder", "stateCName" => "关闭", "stateVal" => "10"));

    }

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分
    protected $_isSetMyList = 0; # 个人列表单据是否要区分公司,1为区分,0为不区分

    /*****************************************************显示分割线**********************************************/

    /*
	 * 通过value查找状态
	 */
    function stateToVal($stateVal)
    {
        $returnVal = false;
        try {
            foreach ($this->state as $key => $val) {
                if ($val['stateVal'] == $stateVal) {
                    $returnVal = $val['stateCName'];
                }
            }
        } catch (Exception $e) {
            throw $e;
            return false;
        }
        return $returnVal;
    }

    /*
	 * 通过状态查找value
	 */
    function stateToSta($stateSta)
    {
        $returnVal = false;
        foreach ($this->state as $key => $val) {
            if ($val['stateEName'] == $stateSta) {
                $returnVal = $val['stateVal'];
            }
        }
        //TODO:添加异常操作
        return $returnVal;
    }

    /*****************************************************显示分割线**********************************************/

    /**********************************************模板类替换方法**************************************************/
    /*
	 * @desription 未执行的采购合同列表替换方法
	 * @param tags
	 * @author qian
	 * @date 2010-12-30 下午02:44:08
	 */
    function showUnExecuteList_s($rows)
    {
        //TODO:列表视角为--一条采购任务对应多种设备
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $cssClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $equsStr = $this->purEquDate_d($val['equs']);
                $str .= <<<EOT
				<tr class="$cssClass">
					<td>
						<p class="childImg"><img src="images/expanded.gif" />$i</p>
					</td>
					<td>$val[applyNumb]</td>
					<td>$val[dateHope]</td>
					<td>$val[suppName]</td>
					<td>$val[statusC]</td>
					<td class="tdchange td_table">
						<table class="shrinkTable main_table_nested">
							$equsStr
						</table>
						<div class="readThisTable"><单击展开物料具体信息></div>
					</td>
					<td>
						<select class="myUnExecute">
							<option >请选择操作</option>
							<option value="viewpurchase">查看</option>
							<option value="editpurchase">编辑</option>
							<option value="begin">启动</option>
							<option value="delete">删除</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
					</td>
				</tr>
EOT;
            }
            $str .= <<<EOT

EOT;
        } else {
            $str = "<tr><td colspan='7'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /*
	 * @desription 待提交审批
	 * 的采购合同--模板替换方法
	 * @param tags
	 * @author qian
	 * @date 2011-1-6 下午09:10:00
	 */
    function showMyWaitList_s($rows)
    {
        //TODO:列表视角为--一条采购任务对应多种设备
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $cssClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $equsStr = $this->purEquDate_d($val['equs']);
                $str .= <<<EOT
				<tr class="$cssClass" title="双击查看订单详细信息"  ondblclick="showOpenWin('?model=purchase_contract_purchasecontract&action=toReadTab&id=$val[id]&skey=$val[skey_]');">
					<td name="tdth01">
						<p class="childImg"><img src="images/expanded.gif" />$i</p>
					</td>
					<td name="tdth02">$val[hwapplyNumb]</td>
					<td name="tdth03">$val[dateHope]</td>
					<td name="tdth04">$val[suppName]</td>
					<td name="tdth05">$val[statusC]</td>
					<td class="tdchange td_table"  width="50%" >
						<table class="shrinkTable main_table_nested">
							$equsStr
						</table>
						<div class="readThisTable"><单击展开物料具体信息></div>
					</td>
					<td name="tdth16">
						<select class="myUnExecute">
							<option >请选择操作</option>
							<option value="viewpurchase">查看</option>
							<option value="editpurchase">编辑</option>
							<option id="uploadFile" value="uploadFile">签约状态及附件</option>
							<option id="export" value="export">导出订单</option>
							<option value="approval">提交审批</option>
							<option value="delete">删除</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
						<ipnut type="hidden" value='{taskId}' />
						<input type="hidden" value='{spId}' />
						<input type="hidden" id="checkkey$val[id]" value='$val[skey_]' />
					</td>
				</tr>
EOT;
            }
            $str .= <<<EOT

EOT;
        } else {
            $str = "<tr><td colspan='7'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /*
	 * @desription 审批中的采购合同--列表替换方法
	 * @param $rows   采购订单数组
	 * @author qian
	 * @date 2011-1-7 下午02:25:10
	 */
    function showApprovalList_s($rows)
    {
        //TODO:列表视角为--一条采购任务对应多种设备
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $cssClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $equsStr = $this->purEquDate_d($val['equs']);
                $str .= <<<EOT
				<tr class="$cssClass" title="双击查看订单详细信息"  ondblclick="showOpenWin('?model=purchase_contract_purchasecontract&action=toReadTab&id=$val[id]&skey=$val[skey_]');">
					<td  name="tdth01">
						<p class="childImg"><img src="images/expanded.gif" />$i</p>
					</td>
					<td  name="tdth02">$val[hwapplyNumb]</td>
					<td  name="tdth03">$val[dateHope]</td>
					<td  name="tdth04">$val[suppName]</td>
					<td class="tdchange td_table" width="45%">
						<table class="shrinkTable main_table_nested">
							$equsStr
						</table>
						<div class="readThisTable"><单击展开物料具体信息></div>
					</td>
					<td  name="tdth16">
						<select class="myUnExecute">
							<option >请选择操作</option>
							<option value="viewinfo">查看</option>
							<option value="viewapproval">查看审批</option>
							<option id="uploadFile" value="uploadFile">签约状态及附件</option>
							<option id="seal" value="seal">申请盖章</option>
							<option id="export" value="export">导出订单</option>
							<option id="cancel" value="cancel">撤消审批</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
						<ipnut type="hidden" value='{taskId}' />
						<input type="hidden" value='{spId}' />
						<input type="hidden" id="checkkey$val[id]" value='$val[skey_]' />
					</td>
				</tr>
EOT;
            }
            $str .= <<<EOT

EOT;
        } else {
            $str = "<tr><td colspan='7'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /*
	 * @desription 执行中的购合同--列表替换方法
	 * @param $rows    采购订单数组
	 * @author qian
	 * @date 2011-1-1 下午02:09:38
	 */
    function showMyExecutionList_s($rows)
    {
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $cssClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $equsStr = $this->purEquDate_d($val['equs']);
                if ($val['payed'] > 0) {
                    $payedMoneyStyle = "style='color:blue'";
                } else {
                    $payedMoneyStyle = "style='color:none'";
                }
                if ($val['applyed'] > 0) {
                    $payapplyMoneyStyle = "style='color:blue'";
                } else {
                    $payapplyMoneyStyle = "style='color:none'";
                }
                if ($val['handInvoiceMoney'] > 0) {
                    $handInvoiceMoneyStyle = "style='color:blue'";
                } else {
                    $handInvoiceMoneyStyle = "style='color:none'";
                }
                $applyNumbPre = substr($val['hwapplyNumb'], 0, 3);
                $applyNumbDate = substr($val['hwapplyNumb'], 3, 6);
                $applyNumbLast = substr($val['hwapplyNumb'], 9);
                if ($val['isStamp'] == 1) {
                    $isStamp = '<img src="images/icon/icon088.gif" title="已盖章"/>';
                } else {
                    $isStamp = "";
                }

                $toInvoice = "";//添加资产采购录入采购发票 add by haojin 2016-11-23 ID2209
                if ($val['purchType'] == "assets" || $val['purchType'] == "oa_asset_purchase_apply") {
                    $toInvoice = '<option id="entryPurchInvoice" value="entryPurchInvoice">录入采购发票</option>';
                }
                $str .= <<<EOT
				<tr class="$cssClass" title="双击查看订单详细信息" ondblclick="showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id=$val[id]&skey=$val[skey_]');">
					<td  name="tdth01" width="2%">
						<p class="childImg"><img src="images/expanded.gif" />$i</p>
					</td>
					<td  name="tdth02" width="12%">$applyNumbPre<font color='blue'>$applyNumbDate</font>$applyNumbLast $isStamp</td>
					<td  name="tdth03" width="4%">$val[dateHope]</td>
					<td  name="tdth04" width="8%">$val[suppName]</td>
					<td  name="tdth05" width="5%">$val[signStatusCn]</td>
					<td class="formatMoney" name="tdth06" width="5%">$val[allMoney]</td>
					<td class="formatMoney" $payedMoneyStyle name="tdth07" width="5%">$val[payed]</td>
					<td class="formatMoney" $payapplyMoneyStyle name="tdth08"  width="5%">$val[applyed]</td>
					<td class="formatMoney" $handInvoiceMoneyStyle name="tdth09"  width="5%">$val[handInvoiceMoney]</td>
					<td class="tdchange td_table" width="35%">
						<table  class="shrinkTable main_table_nested">
							$equsStr
						</table>
						<div class="readThisTable"><单击展开物料具体信息></div>
					</td>
					<td class="states"  name="tdth16">
						<select class="myExecute">
							<option value="">请选择操作</option>
							<option id="view" value="view">查看</option>
							<option id="viewapproval" value="viewapproval">查看审批情况</option>
							<option id="uploadFile" value="uploadFile">签约状态及附件</option>
							<option id="seal" value="seal">申请盖章</option>
							<option id="change" value="change">变更</option>
							<option id="purchaseform" value="purchaseform">填写收料通知单</option>
							<!--<option id="store" value="store">下推外购入库单</option>-->
							<option id="payform" value="payform">申请付款</option>
							<option id="paybackform" value="paybackform">申请退款</option>
							<!--<option id="entryInvoice" value="entryInvoice">录入发票</option>-->
							$toInvoice
							<option id="export" value="export">导出订单</option>
							<option id="finish" value="finish">完成</option>
							<option id="close" value="close">中止</option>
							<option id="closeOrder" value="closeOrder">关闭</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]'/>
						<ipnut type="hidden" value='{taskId}' />
						<input type="hidden" value='{spId}' />
						<input type="hidden" value='$val[hwapplyNumb]'/>
						<input type="hidden" id="checkkey$val[id]" value='$val[skey_]' />
						<input type="hidden" id="payapplyMoney$val[id]" value="0"/>
						<input type="hidden" id="contractMoney$val[id]" value="$val[allMoney]" />
					</td>
				</tr>
EOT;
            }

            $str .= <<<EOT

EOT;
        } else {
            $str = "<tr><td colspan='7'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /*
	 * @desription 执行中的购合同--列表替换方法
	 * @param $rows    采购订单数组
	 * @author qian
	 * @date 2011-1-1 下午02:09:38
	 */
    function showExecutionList_s($rows)
    {
        //TODO:列表视角为--一条采购任务对应多种设备
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $invpurchasaeDao = new model_finance_invpurchase_invpurchase();
        $payablesDao = new model_finance_payables_payables();
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                //获取已付款金额
                $payedMoney = $payablesDao->getPayedMoneyByPur_d($val['id'], 'YFRK-01');
                //获取已申请付款金额
                $payapplyMoney = $payablesapplyDao->getApplyMoneyByPur_d($val['id'], 'YFRK-01');
                //获取已收发票金额
                $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($val['id']);
                $i++;
                $cssClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $equsStr = $this->purEquDate_d($val['equs']);

                $str .= <<<EOT
				<tr class="$cssClass" title="双击查看订单详细信息" ondblclick="showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id=$val[id]&skey=$val[skey_]');">
					<td  name="tdth01">
						<p class="childImg"><img src="images/expanded.gif" />$i</p>
					</td>
					<td  name="tdth02">$val[hwapplyNumb]</td>
					<td  name="tdth03">$val[dateHope]</td>
					<td  name="tdth04">$val[suppName]</td>
					<td  name="tdth05">$val[signStatusCn]</td>
					<td class="formatMoney"  name="tdth06">$val[allMoney]</td>
					<td  name="tdth07">$payedMoney</td>
					<td  name="tdth08">$payapplyMoney</td>
					<td  name="tdth09">$handInvoiceMoney</td>
					<td class="tdchange td_table"  width="35%">
						<table class="shrinkTable main_table_nested">
							$equsStr
						</table>
						<div class="readThisTable"><单击展开物料具体信息></div>
					</td>
					<td class="states"  name="tdth16">
						<select class="myExecute">
							<option >请选择操作</option>
							<option id="view" value="view">查看</option>
							<option id="viewapproval" value="viewapproval">查看审批情况</option>
							<option id="uploadFile" value="uploadFile">签约状态及附件</option>
							<option id="change" value="change">变更</option>
							<option id="purchaseform" value="purchaseform">填写收料通知单</option>
							<!--<option id="store" value="store">下推外购入库单</option>-->
							<option id="payform" value="payform">填写付款申请单</option>
							<!--<option id="entryInvoice" value="entryInvoice">录入发票</option>-->
							<option id="export" value="export">导出订单</option>
							<option id="finish" value="finish">完成</option>
							<option id="close" value="close">中止</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]'/>
						<ipnut type="hidden" value='{taskId}' />
						<input type="hidden" value='{spId}' />
						<input type="hidden" id="checkkey$val[id]" value='$val[skey_]' />
					</td>
				</tr>
EOT;
            }

            $str .= <<<EOT

EOT;
        } else {
            $str = "<tr><td colspan='7'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /*
	 * @desription 拼装起采购-设备列表数据
	 * @param $equs   物料数组
	 * @author qian
	 * @date 2010-12-31 下午02:41:16
	 */
    function purEquDate_d($equs)
    {
        $interfObj = new model_common_interface_obj();
        $arrivalEquDao = new model_purchase_arrival_equipment();
        $orderEquDao = new model_purchase_contract_equipment();
        $str = "";
        if (is_array($equs)) {
            foreach ($equs as $k => $v) {
                $arrivalEquRows = $arrivalEquDao->getItemByContractEquId_d($v['id']);
                $arrivalNum = 0;
                if (is_array($arrivalEquRows)) {   //获取某物料的收料情况
                    foreach ($arrivalEquRows as $arrKey => $arrVal) {
                        $arrivalNum = $arrivalNum + $arrVal['arrivalNum'];
                    }
                }
                if ($arrivalNum == 0) {
                    $arrivalNumStr = '<td width="10%" name="tdth14"  class="formatMoney">' . $arrivalNum . '</td>';
                } else {
                    $arrivalNum = bcadd($arrivalNum, 0, 2);
                    $showStr = "'?model=purchase_contract_purchasecontract&action=itemView&orderId=" . $v['basicId'] . "'";
                    $arrivalNumStr = '<td width="10%" name="tdth14" title="单击查看收料详细信息" onclick="showOpenWin(' . $showStr . ')"><font color="blue">' . $arrivalNum . '</font></td>';
                }


                if ($v['purchType'] == "" && $v['taskEquId'] != "") {
                    //获取采购申请单信息
                    $applyRows = $orderEquDao->getApplyRows($v['taskEquId']);
                    $purchTypeName = $interfObj->typeKToC($applyRows['purchType']); //类型名称
                } else {
                    $purchTypeName = $interfObj->typeKToC($v['purchType']); //类型名称
                }
                if ($v['amountIssued'] > 0) {
                    $amountIssued = bcadd($v['amountIssued'], 0, 2);
                    $amountIssued = "<font color=blue>" . $amountIssued . "</font>";
                } else {
                    $amountIssued = "0.00";
                }
                $str .= <<<EOT
					<tr>
						<td width="45%"  name="tdth10">$v[productNumb]<br>$v[productName]</td>
						<td width="10%"  name="tdth11">$purchTypeName</td>
						<td width="10%"  class="formatMoney" name="tdth12">$v[amountAll]</td>
						<td width="10%" name="tdth13">$amountIssued</td>
						$arrivalNumStr
						<td width="20%" class="formatMoneySix"  name="tdth15">$v[applyPrice]</td>
						<!--
						<td width="25%" >
							<span class="ellipsis" title="$v[remark]">$v[remark]</span>
						</td> -->
					</tr>
EOT;
            }
        }
        return $str;
    }

    /*
	 * @desription 已关闭的合同
	 * @param $rows    采购订单数组
	 * @author qian
	 * @date 2011-1-3 下午07:32:47
	 */
    function showCloseList_s($rows)
    {
        //TODO:列表视角为--一条采购任务对应多种设备
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $cssClass = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                if ($val['isStamp'] == 1) {
                    $isStamp = '<img src="images/icon/icon088.gif" title="已盖章"/>';
                } else {
                    $isStamp = "";
                }
                $equsStr = $this->purEquDate_d($val['equs']);
                //				$url = "?model=purchase_contract_purchasecontract&action=init&perm=view&id=".$val['id'];
                //有历史版本的查看
                $url = "?model=purchase_contract_purchasecontract&action=toTabRead&id=" . $val['id'] . "&applyNumb=" . $val['applyNumb'] . "&skey=" . $val[skey_];
                $str .= <<<EOT
				<tr class="$cssClass" title="双击查看订单详细信息"  ondblclick="showOpenWin('?model=purchase_contract_purchasecontract&action=toTabRead&id=$val[id]&skey=$val[skey_]');">
					<td name="tdth01">
						<p class="childImg"><img src="images/expanded.gif" />$i</p>
					</td>
					<td name="tdth02">$val[hwapplyNumb] $isStamp</td>
					<td name="tdth03">$val[sendName]</td>
					<td name="tdth04">$val[dateHope]</td>
					<td name="tdth05">$val[dateFact]</td>
					<td name="tdth06">$val[suppName]</td>
					<td name="tdth07">$val[statusC]</td>
					<td class="tdchange td_table" width="45%" >
						<table class="shrinkTable main_table_nested">
							$equsStr
						</table>
						<div class="readThisTable"><单击展开物料具体信息></div>
					</td>
					<td name="tdth16">
						<select class="close">
							<option >请选择操作</option>
							<option id="view" value="view">查看</option>
							<option id="export" value="export">导出订单</option>
							<option id="seal" value="seal">申请盖章</option>
							<option id="uploadFile" value="uploadFile">签约状态及附件</option>
						</select>
						<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]'/>
						<ipnut type="hidden" value='{taskId}' />
						<input type="hidden" value='{spId}' />
						<input type="hidden" id="checkkey$val[id]" value='$val[skey_]' />
						<!--<a target="_blank" href="$url">查看</a>-->
					</td>
					<input type="hidden" value='$val[id]' />
						<input type="hidden" value='$val[applyNumb]' />
				</tr>
EOT;
            }
            $str .= <<<EOT

EOT;
        } else {
            $str = "<tr><td colspan='8'>暂无相关信息</td></tr>";
        }
        return $str;
    }

    /**
     * 合同设备清单
     * $listEqu   物料数组
     */
    function showEquipmentList($listEqu)
    {
        $taskEquDao = new model_purchase_task_equipment ();
        $planDao = new model_purchase_plan_basic();
        $orderEquDao = new model_purchase_contract_equipment();
        $interfObj = new model_common_interface_obj ();
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $systeminfoDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $systeminfoDao->get_d("1");
        $saleStockId = $stockSysObj['salesStockId'];
        $str = "";
        $i = 0;
        if (is_array($listEqu)) {
            foreach ($listEqu as $key => $val) {
                $rows = $taskEquDao->get_d($val['takeEquId']);
                $conNumUse = $rows['amountAll'] - $rows['contractAmount'];
                if ($val[amount] > $conNumUse) {
                    $val[amount] = $conNumUse;
                }
                //获取采购申请单信息
                if ($val['purchType'] == "oa_asset_purchase_apply") {//区分是由固定资产系统来的需求还是采购系统的需求
                    if ($i == 0) {
                        $applyDao = new model_asset_purchase_apply_apply();
                    }
                    $purchTypeName = $interfObj->typeKToC($val['purchType']); //类型名称
                    $stockNum = "--";
                    $purchType = $val['purchType'];
                    //获取固定资产采购申请信息
                    $assetRow = $applyDao->get_d($rows['applyId']);
                    $departId = $assetRow['applyDetId'];
                    $department = $assetRow['applyDetName'];
                    $sourceId = $assetRow['id'];
                    $sourceNumb = $assetRow['formCode'];
                    $rObjCode = "";

                } else {
                    $taskEquRow = $taskEquDao->get_d($val['takeEquId']);
                    $applyRows = $planDao->get_d($taskEquRow['planId']);
//					$applyRows=$orderEquDao->getApplyRows($val['takeEquId']);
                    //根据不同的采购类型，物料的源单获取不同的值
                    switch ($applyRows['purchType']) {
                        case "oa_sale_order":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_sale_service":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_sale_lease":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_sale_rdproject":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_borrow_borrow":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_present_present":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "stock":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "rdproject":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['projectName'];
                            break;
                        case "assets":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "produce":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "HTLX-XSHT":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "HTLX-ZLHT":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "HTLX-FWHT":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "HTLX-YFHT":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;

                    }
                    $purchTypeName = $interfObj->typeKToC($applyRows['purchType']); //类型名称
                    $departId = $applyRows['departId'];
                    $department = $applyRows['department'];
                    $purchType = $applyRows['purchType'];
                    $rObjCode = $applyRows['rObjCode'];

                    $inventoryDao->searchArr = array("stockId" => $saleStockId, "productId" => $val['productId']);
                    $inventoryArr = $inventoryDao->listBySqlId();
                    //$stockNum= $inventoryDao->getActNumByProId( $val['productId']);1
                    $stockNum = $inventoryArr[0]['exeNum'];
                    if (!is_array($inventoryArr)) {
                        $stockNum = "0";
                    }

                }
                $valId = $val['id'];
                $moneyAll = $val[amount] * $val[price];
                $rate = $val['taxRate'] * 0.01 + 1;
                $price = bcdiv($val['price'], $rate, 6);//计算不含税单价
                $i++;
                if ($val['units'] == "NULL") {
                    $val['units'] = "";
                }
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
						<td>
							$val[productNumb]<br/>$val[productName]
							<input type="hidden" name="contract[equs][$i][inquiryEquId]" value="$val[inquiryEquid]"/>
							<input type="hidden" name="contract[equs][$i][taskEquId]" value="$val[takeEquId]"/>
							<input type="hidden" name="contract[equs][$i][productNumb]" value="$val[productNumb]"/>
							<input type="hidden" name="contract[equs][$i][productId]" value="$val[productId]"/>
							<input type="hidden"  name="contract[equs][$i][productName]" value="$val[productName]">
							<input type="hidden"  name="contract[equs][$i][batchNumb]" value="$val[batchNumb]">
							<input type="hidden" class="readOnlyTxt"  value="$val[productName]" readonly></td>
						<td>
							<input type="text" class="readOnlyTxtItem"  name="contract[equs][$i][pattem]" value="$val[pattem]">
						</td>
						<td>
							<input type="text" class="readOnlyTxtMin"  name="contract[equs][$i][units]" value="$val[units]" readonly/>
						</td>
						<td>
								<a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]">$stockNum</a>
						</td>
						<td>
							<input type="text" class="txtmin amount formatMoney"  id="amountAll$i" name="contract[equs][$i][amountAll]" value="$val[amount]" onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumAllMoney();">
							<input type="hidden" name="amountAll" value="$conNumUse" >
						</td>
						<td>
							<input type="text" class="txtshort" name="contract[equs][$i][dateHope]"   id="equDateHope$i" onfocus="WdatePicker()"  value="$val[deliveryDate]" readonly />
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort" name="contract[equs][$i][dateIssued]"  id="deliveryDate$i" value="$val[deliveryDate]" readonly />
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort price formatMoneySix" id="price$i" name="contract[equs][$i][price]" value="$price" readonly/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort price formatMoneySix" id="applyPrice$i" name="contract[equs][$i][applyPrice]" value="$val[price]" onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumPrice($i);sumAllMoney();"/>
							<input type="hidden"  value="$val[price]" />
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"   value="$val[taxRate]%">
							<input type="hidden" class="readOnlyTxtShort" id="taxRate$i"  name="contract[equs][$i][taxRate]" value="$val[taxRate]">
							<!--<select type="text" class="txtshort" id="taxRate$i"  name="contract[equs][$i][taxRate]" >{taxRate$i}</select>
							<SCRIPT language=JavaScript>
										jQuery("#taxRate$i").bind('change',function(){sumPrice($i);sumAllMoney();});
								</SCRIPT>-->
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort  formatMoney" id="moneyAll$i" name="contract[equs][$i][moneyAll]" value="$moneyAll" onblur="sumAllMoney();"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$purchTypeName"/>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][applyDeptId]"  value="$departId"></input>
							<input type="text" class="readOnlyTxtShort" name="contract[equs][$i][applyDeptName]" value="$department" readonly></input>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][sourceID]"  value="$sourceId"></input>
							<input type="text" class="readOnlyTxtItem" name="contract[equs][$i][sourceNumb]" value="$sourceNumb" readonly></input>
							<input type="hidden" name="contract[equs][$i][purchType]"  value="$purchType"></input>
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" name="contract[equs][$i][rObjCode]" value="$rObjCode" readonly></input>
						</td>
						<td>
							<input class="txtshort" name="contract[equs][$i][remark]" ></input>
						</td>
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 由采购任务生成采购订单
     * $listEqu        物料数组
     */
    function showEquList($listEqu)
    {
        $planDao = new model_purchase_plan_basic();
        $taskEquDao = new model_purchase_task_equipment ();
        $orderEquDao = new model_purchase_contract_equipment();
        $interfObj = new model_common_interface_obj ();
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $systeminfoDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $systeminfoDao->get_d("1");
        $saleStockId = $stockSysObj['salesStockId'];
        $str = "";
        $i = 0;
        if (is_array($listEqu)) {
            foreach ($listEqu as $key => $val) {
                $conNumUse = $val['amountAll'] - $val['contractAmount'];
                if ($val[amount] > $conNumUse) {
                    $val[amount] = $conNumUse;
                }
                //获取采购申请单信息
                if ($val['purchType'] == "oa_asset_purchase_apply") {//区分是由固定资产系统来的需求还是采购系统的需求
                    if ($i == 0) {
                        $applyDao = new model_asset_purchase_apply_apply();
                    }
                    $purchTypeName = $interfObj->typeKToC($val['purchType']); //类型名称
                    $stockNum = "--";
                    $purchType = $val['purchType'];
                    //获取固定资产采购申请信息
                    $assetRow = $applyDao->get_d($val['applyId']);
                    $departId = $assetRow['applyDetId'];
                    $department = $assetRow['applyDetName'];
                    $sourceId = $assetRow['id'];
                    $sourceNumb = $assetRow['formCode'];
                    $rObjCode = "";

                } else {
                    $applyRows = $planDao->get_d($val['planId']);
//					$applyRows=$orderEquDao->getApplyRows($val['takeEquId']);
                    //根据不同的采购类型，物料的源单获取不同的值
                    //根据不同的采购类型，物料的源单获取不同的值
                    switch ($applyRows['purchType']) {
                        case "oa_sale_order":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_sale_service":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_sale_lease":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_sale_rdproject":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_present_present":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "oa_borrow_borrow":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "stock":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "rdproject":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "assets":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;
                        case "produce":
                            $sourceId = $applyRows['sourceID'];
                            $sourceNumb = $applyRows['sourceNumb'];
                            break;

                    }
                    $purchTypeName = $interfObj->typeKToC($applyRows['purchType']); //类型名称
                    $departId = $applyRows['departId'];
                    $department = $applyRows['department'];
                    $purchType = $applyRows['purchType'];
                    $rObjCode = $applyRows['rObjCode'];
                    $stockNum = $inventoryDao->getActNumByProId($val['productId']);

                    $inventoryDao->searchArr = array("stockId" => $saleStockId, "productId" => $val['productId']);
                    $inventoryArr = $inventoryDao->listBySqlId();
                    //$stockNum= $inventoryDao->getActNumByProId( $val['productId']);
                    $stockNum = $inventoryArr[0]['exeNum'];
                    if (!is_array($inventoryArr)) {
                        $stockNum = "0";
                    }

                }
                $valId = $val['id'];
                $amountAll = $val['amountAll'] - $val['contractAmount'];
                $purchTypeName = $interfObj->typeKToC($purchType); //类型名称
                $i++;
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
					    <td>
							<input type="text"class="readOnlyTxtShort"  name="contract[equs][$i][productNumb]" value="$val[productNumb]"/></td>
						<td>
							<input type="hidden" name="contract[equs][$i][taskEquId]" value="$val[id]"/>
							<input type="hidden" name="contract[equs][$i][productId]" value="$val[productId]"/>
							<input type="hidden"  name="contract[equs][$i][productName]" value="$val[productName]">
							<input type="text" class="readOnlyTxtItem"  value="$val[productName]" readonly></td>
						<td>
							<input type="text" class="readOnlyTxtShort"  name="contract[equs][$i][pattem]" value="$val[pattem]">
						</td>
						<td>
							<input type="text" class="readOnlyTxtMin"  name="contract[equs][$i][units]" value="$val[unitName]" readonly>
						</td>
						<td width="5%">
								<a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]">$stockNum</a>
						</td>
						<td>
							<input type="text" id="amountAll$i" class="txtmin amount formatMoney"  name="contract[equs][$i][amountAll]" value="$amountAll"  onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumAllMoney();">
							<input type="hidden" name="amountAll" value="$amountAll" >
						</td>
						<td>
							<input type="text" class="txtshort" name="contract[equs][$i][dateHope]" size="9" maxlength="12"  id="equDateHope$i" onfocus="WdatePicker()"  value="$val[dateHope]" readonly />
						</td>
						<td>
							<input type="text" class="txtshort" name="contract[equs][$i][dateIssued]" size="9" maxlength="12"  id="deliveryDate$i" onfocus="WdatePicker()"  value="$val[deliveryDate]" readonly />
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort price formatMoneySix" id="price$i" name="contract[equs][$i][price]" value="" readonly/>
						</td>
						<td>
							<input type="text" id="applyPrice$i" class="txtshort formatMoneySix price"  name="contract[equs][$i][applyPrice]" value="" onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumPrice($i);sumAllMoney();"/>
							<input type="hidden"  value="" />
								<SCRIPT language=JavaScript>
										jQuery("#applyPrice$i"+"_v").bind('blur',function(){sumPrice($i);sumAllMoney();});
								</SCRIPT>
						</td>
						<td>
							<select type="text" class="txtshort" id="taxRate$i"  name="contract[equs][$i][taxRate]" >{taxRate}</select>
							<SCRIPT language=JavaScript>
										jQuery("#taxRate$i").bind('change',function(){sumPrice($i);sumAllMoney();});
								</SCRIPT>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort  formatMoney" id="moneyAll$i" name="contract[equs][$i][moneyAll]" value="" onblur="sumAllMoney();"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$purchTypeName" readonly/>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][applyDeptId]"  value="$departId"></input>
							<input type="text" class="readOnlyTxtShort" name="contract[equs][$i][applyDeptName]" value="$department" readonly></input>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][sourceID]"  value="$sourceId"></input>
							<input type="text" class="readOnlyTxtItem" name="contract[equs][$i][sourceNumb]" value="$sourceNumb" readonly></input>
							<input type="hidden" name="contract[equs][$i][purchType]"  value="$purchType"></input>
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" name="contract[equs][$i][rObjCode]" value="$rObjCode" readonly></input>
						</td>
						<td>
							<input type="text" class="txtshort"  name="contract[equs][$i][remark]">
						</td>
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 由采购任务生成采购订单
     * $listEqu        物料数组
     */
    function showOrderEquList($listEqu)
    {
        $planDao = new model_purchase_plan_basic();
        $taskEquDao = new model_purchase_task_equipment ();
        $orderEquDao = new model_purchase_contract_equipment();
        $interfObj = new model_common_interface_obj ();
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $systeminfoDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $systeminfoDao->get_d("1");
        $saleStockId = $stockSysObj['salesStockId'];
        $str = "";
        $i = 0;
        if (is_array($listEqu)) {
            foreach ($listEqu as $key => $val) {
                $conNumUse = $val['amountAll'] - $val['contractAmount'];
                if ($val[amount] > $conNumUse) {
                    $val[amount] = $conNumUse;
                }
                if ($conNumUse > 0) {
                    //获取采购申请单信息
                    if ($val['purchType'] == "oa_asset_purchase_apply") {//区分是由固定资产系统来的需求还是采购系统的需求
                        if ($i == 0) {
                            $applyDao = new model_asset_purchase_apply_apply();
                        }
                        $purchTypeName = $interfObj->typeKToC($val['purchType']); //类型名称
                        $stockNum = "--";
                        $purchType = $val['purchType'];
                        //获取固定资产采购申请信息
                        $assetRow = $applyDao->get_d($val['applyId']);
                        $departId = $assetRow['applyDetId'];
                        $department = $assetRow['applyDetName'];
                        $sourceId = $assetRow['id'];
                        $sourceNumb = '';
                        $rObjCode = "";

                    } else {
                        $applyRows = $planDao->get_d($val['planId']);
                        //根据不同的采购类型，物料的源单获取不同的值
                        switch ($applyRows['purchType']) {
                            case "oa_sale_order":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "oa_sale_service":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "oa_sale_lease":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "oa_sale_rdproject":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "oa_borrow_borrow":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "oa_present_present":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "stock":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "rdproject":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['projectName'];
                                break;
                            case "assets":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "produce":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "HTLX-XSHT":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "HTLX-ZLHT":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "HTLX-FWHT":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;
                            case "HTLX-YFHT":
                                $sourceId = $applyRows['sourceID'];
                                $sourceNumb = $applyRows['sourceNumb'];
                                break;

                        }
                        $purchTypeName = $interfObj->typeKToC($applyRows['purchType']); //类型名称
                        $departId = $applyRows['departId'];
                        $department = $applyRows['department'];
                        $purchType = $applyRows['purchType'];
                        $rObjCode = $applyRows['rObjCode'];
                        $stockNum = $inventoryDao->getActNumByProId($val['productId']);

                        $inventoryDao->searchArr = array("stockId" => $saleStockId, "productId" => $val['productId']);
                        $inventoryArr = $inventoryDao->listBySqlId();
                        //$stockNum= $inventoryDao->getActNumByProId( $val['productId']);
                        $stockNum = $inventoryArr[0]['exeNum'];
                        if (!is_array($inventoryArr)) {
                            $stockNum = "0";
                        }

                    }
                    $valId = $val['id'];
                    $amountAll = $val['amountAll'] - $val['contractAmount'];
                    $purchTypeName = $interfObj->typeKToC($purchType); //类型名称
                    $i++;
                    $str .= <<<EOT
					<tr>
					    <td>$i</td>
					    <td>
							<input type="text"class="readOnlyTxtShort"  name="contract[equs][$i][productNumb]" value="$val[productNumb]"/></td>
						<td>
							<input type="hidden" name="contract[equs][$i][taskEquId]" value="$val[id]"/>
							<input type="hidden" name="contract[equs][$i][productId]" value="$val[productId]"/>
							<input type="hidden"  name="contract[equs][$i][productName]" value="$val[productName]">
							<input type="hidden" name="contract[equs][$i][qualityCode]" value="$val[qualityCode]"/>
							<input type="hidden"  name="contract[equs][$i][qualityName]" value="$val[qualityName]">
							<input type="hidden"  name="contract[equs][$i][batchNumb]" value="$val[batchNumb]">
							<input type="hidden"  name="contract[equs][$i][formBelong]" value="$val[formBelong]">
							<input type="hidden"  name="contract[equs][$i][formBelongName]" value="$val[formBelongName]">
							<input type="hidden"  name="contract[equs][$i][businessBelong]" value="$val[businessBelong]">
							<input type="hidden"  name="contract[equs][$i][businessBelongName]" value="$val[businessBelongName]">
							<input type="text" class="readOnlyTxtItem"  value="$val[productName]" readonly></td>
						<td>
							<input type="text" class="readOnlyTxtShort"  name="contract[equs][$i][pattem]" value="$val[pattem]">
						</td>
						<td>
							<input type="text" class="readOnlyTxtMin"  name="contract[equs][$i][units]" value="$val[unitName]" readonly>
						</td>
						<td width="5%">
								<a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]">$stockNum</a>
						</td>
						<td>
							<input type="text" id="amountAll$i" class="txtmin amount"  name="contract[equs][$i][amountAll]" value="$amountAll" >
							<input type="hidden" name="amountAll" value="$amountAll">
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$purchTypeName" readonly/>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][applyDeptId]"  value="$departId"></input>
							<input type="text" class="readOnlyTxtShort" name="contract[equs][$i][applyDeptName]" value="$department" readonly></input>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][sourceID]"  value="$sourceId"></input>
							<input type="text" class="readOnlyTxtItem" name="contract[equs][$i][sourceNumb]" value="$sourceNumb" readonly></input>
							<input type="hidden" name="contract[equs][$i][purchType]"  value="$purchType"></input>
						</td>
						<td>
							<input type="text" class="readOnlyTxtItem" name="contract[equs][$i][rObjCode]" value="$rObjCode" readonly></input>
						</td>
						<td>
							<input type="text" class="txtshort"  name="contract[equs][$i][remark]">
						</td>
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td>
					</tr>
EOT;

                }
            }
        }
        return $str;
    }


    /**
     * 由资产采购任务生成采购订单
     * $listEqu        物料数组
     */
    function showAssetEquList($listEqu)
    {
        $orderEquDao = new model_purchase_contract_equipment();
        $interfObj = new model_common_interface_obj ();
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $systeminfoDao = new model_stock_stockinfo_systeminfo();
        $applyDao = new model_asset_purchase_apply_apply();
        $str = "";
        $i = 0;
        if (is_array($listEqu)) {
            foreach ($listEqu as $key => $val) {
                $conNumUse = $val['taskAmount'] - $val['issuedAmount'];
                if ($val[amount] > $conNumUse) {
                    $val[amount] = $conNumUse;
                }
                $stockNum = "--";
                $purchType = "oa_asset_purchase_apply";
                //获取固定资产采购申请信息
                $assetRow = $applyDao->get_d($val['applyId']);
                $departId = $assetRow['applyDetId'];
                $department = $assetRow['applyDetName'];
                $sourceId = $assetRow['id'];
                $sourceNumb = $assetRow['formCode'];
                $valId = $val['id'];
                $amountAll = $val['taskAmount'] - $val['issuedAmount'];
                $price = bcdiv($val['price'], 1.17, 6);//计算不含税单价
                $moneyAll = $amountAll * $val[price];
                $purchTypeName = $interfObj->typeKToC($purchType); //类型名称
                $i++;
                $str .= <<<EOT
					<tr>
					    <td>$i</td>
					    <td>
							<input type="hidden" name="contract[equs][$i][applyEquId]" value="$val[id]"/>
							<input type="hidden" name="contract[equs][$i][applyId]" value="$val[applyId]"/>
							<input type="hidden" name="contract[equs][$i][productId]" value="$val[productId]"/>
							<input type="hidden"  name="contract[equs][$i][productName]" value="$val[productName]">
							<input type="text" class="readOnlyTxtItem"  value="$val[productName]" readonly></td>
						<td>
							<input type="text" class="readOnlyTxtShort"  name="contract[equs][$i][pattem]" value="$val[pattem]">
						</td>
						<td>
							<input type="text" class="readOnlyTxtMin"  name="contract[equs][$i][units]" value="$val[unitName]" readonly>
						</td>
						<td>
							<input type="text" id="amountAll$i" class="txtmin amount"  name="contract[equs][$i][amountAll]" value="$amountAll"  onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumAllMoney();">
							<input type="hidden" name="amountAll" value="$amountAll" >
						</td>
						<td>
							<input type="text" class="txtshort" name="contract[equs][$i][dateHope]" size="9" maxlength="12"  id="equDateHope$i" onfocus="WdatePicker()"  value="$val[dateHope]" readonly />
						</td>
						<td>
							<input type="text" class="txtshort" name="contract[equs][$i][dateIssued]" size="9" maxlength="12"  id="deliveryDate$i" onfocus="WdatePicker()"  value="$val[deliveryDate]" readonly />
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort price formatMoney" id="price$i" name="contract[equs][$i][price]" value="$price" readonly/>
						</td>
						<td>
							<input type="text" id="applyPrice$i" class="txtshort formatMoney price"  name="contract[equs][$i][applyPrice]" value="$val[price]" onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumPrice($i);sumAllMoney();"/>
							<input type="hidden"  value="" />
								<SCRIPT language=JavaScript>
										jQuery("#applyPrice$i"+"_v").bind('blur',function(){sumPrice($i);sumAllMoney();});
								</SCRIPT>
						</td>
						<td>
							<select type="text" class="txtshort" id="taxRate$i"  name="contract[equs][$i][taxRate]" >{taxRate}</select>
							<SCRIPT language=JavaScript>
										jQuery("#taxRate$i").bind('change',function(){sumPrice($i);sumAllMoney();});
								</SCRIPT>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort  formatMoney" id="moneyAll$i" name="contract[equs][$i][moneyAll]" value="$moneyAll" onblur="sumAllMoney();"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$purchTypeName" readonly/>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][applyDeptId]"  value="$departId"></input>
							<input type="text" class="readOnlyTxtShort" name="contract[equs][$i][applyDeptName]" value="$department" readonly></input>
						</td>
						<td>
							<input type="hidden" name="contract[equs][$i][sourceID]"  value="$sourceId"></input>
							<input type="text" class="readOnlyTxtItem" name="contract[equs][$i][sourceNumb]" value="$sourceNumb" readonly></input>
							<input type="hidden" name="contract[equs][$i][purchType]"  value="$purchType"></input>
						</td>
						<td>
							<input type="text" class="txtshort"  name="contract[equs][$i][remark]">
						</td>
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /*
	 * @desription 合同设备--添加页
	 * @param $listEqu 订单物料数组
	 * @author qian
	 * @date 2011-1-5 下午07:45:52
	 */
    function addContractEquList_s($listEqu)
    {
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $systeminfoDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $systeminfoDao->get_d("1");
        $saleStockId = $stockSysObj['salesStockId'];
        $str = "";
        $i = 0;
        $sumNumb = 0;
        $sumMoney = 0.00;
        if ($listEqu) {
            foreach ($listEqu as $key => $val) {
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
                        $souceNum = $val[sourceNumb];
                        break;
                }

                $inventoryDao->searchArr = array("stockId" => $saleStockId, "productId" => $val['productId']);
                $inventoryArr = $inventoryDao->listBySqlId();
                //$stockNum= $inventoryDao->getActNumByProId( $val['productId']);
                $stockNum = bcadd($inventoryArr[0]['exeNum'], 0, 2);
                $sumMoney = bcadd($sumMoney, $val[moneyAll], 6);
                $sumNumb = $sumNumb + $val['amountAll'];
                if (!is_array($inventoryArr)) {
                    $stockNum = "0.00";
                }
                if ($val['taxRate'] != "") {
                    $val['taxRate'] = $val['taxRate'] . "%";
                }
                //获取K3编码
                $k3Condition = "id=" . $val['productId'];
                $k3Code = $this->get_table_fields('oa_stock_product_info', $k3Condition, 'ext2');
                $i++;
                if ($val['units'] == "NULL") {
                    $val['units'] = "";
                }
                //获取采购申请信息
                $remark = $val[remark];
                if ($val['taskEquId'] > 0) {
                    $taskEquIdCondition = "id=" . $val['taskEquId'];
                    $planId = $this->get_table_fields('oa_purch_task_equ', $taskEquIdCondition, 'planId');
                    if ($planId > 0) {
                        $planIdCondition = "id=" . $planId;
                        $planRemark = $this->get_table_fields('oa_purch_plan_basic', $planIdCondition, 'instruction');
                        if ($planRemark != "") {
                            $remark .= "*" . $planRemark;
                        }
                    }
                }
//			<a href="?model=purchase_arrival_arrival&action=itemView&equId=$val[id]&productName=$val[productName]&formType=blue&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看收料详细信息">$val[amountIssued]</a>
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss" >
					    <td>$i</td>
						<td>
						<input type="hidden" name="contract[equs][$i][inquiryEquId]" value="$val[id]"/>
						<input type="hidden" name="contract[equs][$i][taskEquId]" value="$val[taskEquId]"/>
						<input type="hidden" name="contract[equs][$i][productNumb]" value="$val[productNumb]"/>
						<input type="hidden" name="contract[equs][$i][productId]" value="$val[productId]"/>
							$val[productNumb]<br>
							$val[productName]
						</td>
						<td>$val[pattem]</td>
						<td>$val[units]</td>
						<td>
							$k3Code
						</td>
						<td width="5%" >
								<a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]&stockId=$saleStockId">$stockNum</a>
						</td>
						<td class="formatMoney">
							$val[amountAll]
						</td>
						<td class="formatMoney">
							$val[amountIssued]
						</td>
						<td>
							$val[dateHope]
						</td>
						<td>
							$val[dateIssued]
						</td>
						<td class="formatMoneySix">
							$val[price]
						</td>
						<td class="formatMoneySix">
							$val[applyPrice]
						</td>
						<td>
							$val[taxRate]
						</td>
						<td class="formatMoney">
							$val[moneyAll]
						</td>
						<td>
							$val[purchTypeC]
						</td>
						<td>
							$val[applyDeptName]
						</td>
						<td>
							$souceNum
						</td>
						<td>
							$val[rObjCode]
						</td>
						<td>
							<textarea class="textarea_read_blue" readonly>$remark</textarea>
						</td>
					</tr>
EOT;
            }
            $str .= "<tr class='tr_count'><td>合计</td><td colspan='5'></td><td class='formatMoney'>$sumNumb</td><td colspan='6'></td><td class='formatMoney'>$sumMoney</td><td colspan='5'></td></tr>";
        } else {
            $str = "<tr><td colspan='15'>无相关物料信息</td></tr>";
        }
        return $str;
    }

    /*
	 * @desription 产品清单--编辑页
	 * @param $listEqu	订单物料数组
	 * @author qian
	 * @date 2011-1-12 下午03:18:01
	 */
    function editContractEquList_s($listEqu)
    {
        $taskEquDao = new model_purchase_task_equipment ();
        $inventoryDao = new model_stock_inventoryinfo_inventoryinfo();
        $systeminfoDao = new model_stock_stockinfo_systeminfo();
        $stockSysObj = $systeminfoDao->get_d("1");
        $saleStockId = $stockSysObj['salesStockId'];
        $str = "";
        $i = 0;
        $sumMoney = $sumNumb = 0;
        if ($listEqu) {
            foreach ($listEqu as $key => $val) {
                $rows = $taskEquDao->get_d($val['taskEquId']);
                $conNumUse = $rows['amountAll'] - $rows['contractAmount'] + $val['amountAll'];
                $sumMoney = bcadd($sumMoney, $val[moneyAll], 2);
                $sumNumb = $sumNumb + $val['amountAll'];

                $inventoryDao->searchArr = array("stockId" => $saleStockId, "productId" => $val['productId']);
                $inventoryArr = $inventoryDao->listBySqlId();
                //$stockNum= $inventoryDao->getActNumByProId( $val['productId']);
                $stockNum = $inventoryArr[0]['exeNum'];
                if (!is_array($inventoryArr)) {
                    $stockNum = "0";
                }
                if ($val['units'] == "NULL") {
                    $val['units'] = "";
                }
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
						<td>
						<input type="hidden" name="equs[$i][id]" value="$val[id]"/>
						<input type="hidden" name="equs[$i][taskEquId]" value="$val[taskEquId]"/>
						<input type="hidden" name="equs[$i][applyEquId]" value="$val[applyEquId]"/>
						<input type="hidden" name="equs[$i][productNumb]" value="$val[productNumb]"/>
						<input type="hidden" name="equs[$i][productId]" value="$val[productId]"/>
							$val[productNumb]<br>
							$val[productName]
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  name="equs[$i][pattem]" value="$val[pattem]"  readonly>
						</td>
						<td>
							<input type="text" class="readOnlyTxtMin"  name="equs[$i][units]" value="$val[units]" readonly>
						</td>
						<td width="5%">
								<a target="_blank" title="查看即时库存" href="?model=stock_inventoryinfo_inventoryinfo&action=toInTimeList&productId=$val[productId]">$stockNum</a>
						</td>
						<td>
							<input type="text" class="amount txtmin formatMoney"  id="amountAll$i" name="equs[$i][amountAll]" value="$val[amountAll]" onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumAllMoneyInEdit(this)"/>
							<input type="hidden" name="amountAll" value="$conNumUse">
							<input type="hidden"  id="amountOld" name="equs[$i][amountOld]" value="$val[amountAll]">
						</td>
						<td>
							<input type="text" class="txtshort" id="dateHope$i" name="equs[$i][dateHope]" value="$val[dateHope]" onfocus="WdatePicker()" readonly>
						</td>
						<td>
							<input type="text" class="txtshort" id="dateIssued$i" name="equs[$i][dateIssued]" value="$val[dateIssued]" onfocus="WdatePicker()" readonly>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort price formatMoneySix" id="price$i" name="equs[$i][price]" value="$val[price]" readonly/>
						</td>
						<td>
							<input type="text" class=" price readOnlyTxtShort formatMoneySix" id="applyPrice$i" name="equs[$i][applyPrice]" value="$val[applyPrice]"  onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumPrice($i);sumAllMoneyInEdit(this)">
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"   value="$val[taxRate]%">
							<input type="hidden" class="readOnlyTxtShort" id="taxRate$i"  name="contract[equs][$i][taxRate]" value="$val[taxRate]">
							<!--
							<select type="text" class="txtshort" id="taxRate$i"  name="equs[$i][taxRate]" >{taxRate$i}</select>
							<SCRIPT language=JavaScript>
									jQuery("#taxRate$i").bind('change',function(){sumPrice($i);sumAllMoney();});
							</SCRIPT>-->
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort formatMoney" id="moneyAll$i" name="equs[$i][moneyAll]" value="$val[moneyAll]"  onblur="sumAllMoneyInEdit(this)">
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$val[purchTypeC]"  readonly/>
						</td>
						<td>
							<input class="readOnlyTxtShort" id="" name="equs[$i][applyDeptName]" value="$val[applyDeptName]" readonly></input>
						</td>
						<td>
							<input class="readOnlyTxtItem" id="" name="equs[$i][sourceNumb]" value="$val[sourceNumb]" readonly></input>
						</td>
						<td>
							<input class="readOnlyTxtItem" id="" name="equs[$i][rObjCode]" value="$val[rObjCode]" readonly></input>
						</td>
						<td>
							<input class="txtshort" id="remark$i" name="equs[$i][remark]" value="$val[remark]"></input>
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 变更采购合同
     *
     * @param $listEqu    订单物料数组
     */
    function chageContractList_s($listEqu)
    {
        $taskEquDao = new model_purchase_task_equipment ();
        $interfObj = new model_common_interface_obj ();

        $str = "";
        $i = 0;
        if ($listEqu) {
            foreach ($listEqu as $key => $val) {
                $rows = $taskEquDao->get_d($val['taskEquId']);
                $conNumUse = $rows['amountAll'] - $rows['contractAmount'] + $val['amountAll'];

                $listEqu[$key]['purchTypeC'] = $interfObj->typeKToC($val['oPurchType']); //类型名称


                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                if (empty($val['originalId'])) {
                    $str .= '<input type="hidden" name="contract[equs][' . $i . '][oldId]" value="' . $val['id'] . '" />';
                } else {
                    $str .= '<input type="hidden" name="contract[equs][' . $i . '][oldId]" value="' . $val['originalId'] . '" />';
                }
                if ($val['taxRate'] == "") {
                    $price = bcdiv($val['applyPrice'], 1.17, 6);//计算不含税单价
                } else {
                    $price = $val['price'];
                }
                if ($val['units'] == "NULL") {
                    $val['units'] = "";
                }
                $str .= <<<EOT
					<tr class="$classCss">
					    <td>$i</td>
						<td>

							<input type="hidden" name="contract[equs][$i][basicNumb]" value="$val[basicNumb]" id="basicNumb"/>
							<input type="hidden" name="contract[equs][$i][applyNumb]" value="$val[applyNumb]" />
							<input type="hidden" name="contract[equs][$i][taskEquId]" value="$val[taskEquId]"/>
							<input type="hidden" name="contract[equs][$i][productNumb]" value="$val[productNumb]"/>
							<input type="hidden" name="contract[equs][$i][productName]" value="$val[productName]" />
							<input type="hidden" name="contract[equs][$i][inquiryEquId]" value="$val[inquiryEquId]"/>
							<input type="hidden" name="contract[equs][$i][applyFactPrice]" value="$val[applyFactPrice]"/>
							<input type="hidden" name="contract[equs][$i][productId]" value="$val[productId]"/>
							<input type="hidden" name="contract[equs][$i][version]" />
							<input type="hidden" name="contract[equs][$i][isChanged]" value="0" />
							<input type="hidden" name="contract[equs][$i][changeType]" value="0" />
							$val[productNumb]
						</td>
						<td>
							$val[productName]
						</td>
						<td>
							$val[pattem]
							<input type="hidden" name="contract[equs][$i][basicNumb]" value="$val[basicNumb]" />
						</td>
						<td>
							$val[units]
							<input type="hidden" name="contract[equs][$i][purchTypeC]" value="$val[purchTypeC]" />
							<input type="hidden" name="contract[equs][$i][planEquType]" value="$val[oPurchType]" />
						</td>
						<td>
							<input type="text" class=" txtmin formatMoney" id="amountAll$i" name="contract[equs][$i][amountAll]" value="$val[amountAll]"  onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumAllMoneyInEdit(this)"/ >
							<input type="hidden" class="txtshort"  name="amountAll" value="$conNumUse">
						</td>
						<td>
							$val[amountIssued]
							<input type="hidden" name="contract[equs][$i][amountIssued]" value="$val[amountIssued]" />
						</td>
						<td>
							<input type="text" class="txtshort" id="dateHope" name="contract[equs][$i][dateHope]" value="$val[dateHope]" onfocus="WdatePicker()" readonly>
						</td>
						<td>
							<input type="text" class="txtshort" id="dateIssued$i" name="contract[equs][$i][dateIssued]" value="$val[dateIssued]" onfocus="WdatePicker()" readonly>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort price formatMoneySix" id="price$i" name="contract[equs][$i][price]" value="$price" readonly/>
						</td>
						<td>
							<input type="text" class="price txtshort formatMoneySix" id="applyPrice$i" name="contract[equs][$i][applyPrice]" value="$val[applyPrice]"  onblur="FloatMul('amountAll$i','applyPrice$i','moneyAll$i');sumPrice($i);sumAllMoneyInEdit(this)"/>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort taxRate "   numflag="$i"  id="taxRate$i"  name="contract[equs][$i][taxRate]"  value="$val[taxRate]"  readonly/>
							<SCRIPT language=JavaScript>
									jQuery("#taxRate$i").bind('change',function(){sumPrice($i);sumAllMoney();});
							</SCRIPT>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort formatMoney" id="moneyAll$i" name="contract[equs][$i][moneyAll]" value="$val[moneyAll]" onblur="sumAllMoneyInEdit(this)" readonly>
						</td>
						<td>
							<input type="text" class="readOnlyTxtShort"  value="$val[purchTypeC]" readonly/>
						</td>
						<td>
							<input class="readOnlyTxtShort" id="" name="equs[$i][applyDeptName]" value="$val[applyDeptName]" readonly></input>
						</td>
						<td>
							<input class="readOnlyTxtItem" id="" name="equs[$i][sourceNumb]" value="$val[sourceNumb]" readonly></input>
						</td>
						<td>
							<input class="readOnlyTxtItem" id="" name="equs[$i][rObjCode]" value="$val[rObjCode]" readonly></input>
						</td>
						<td>
							<input  id="remark"  class="txtshort" name="contract[equs][$i][remark]" value="$val[remark]"></input>
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /*
	 * @desription 合同设备--查看页
	 * @param $rows 	订单物料数组
	 * @author qian
	 * @date 2011-1-4 下午02:01:22
	 */
    function showContractEquList_s($rows)
    {
        $str = "";
        $i = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                $i++;
                $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                $str .= <<<EOT
			<tr class="$classCss">
				<td>$i</td>
				<td>
					$val[productNumb]<br>
					$val[productName]
				</td>
				<td>$val[basicNumb]</td>
				<td>采购类型</td>
				<td>$val[amountAll]</td>
				<td>$val[amountIssued]</td>
				<td>$val[dateHope]</td>
				<td>$val[applyPrice]</td>
				<td>$val[remark]</td>
			</tr>
EOT;
            }
        } else {
            $str = "<tr><td colspan='9'>暂无相关设备</td></tr>";
        }
        return $str;
    }

    //付款申请时，根据采购订单ID，获取表格模板
    function showPayModeList($id)
    {
        //主表信息
        $orderRow = $this->get_d($id);
        //从表信息
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusByContractId($id);  //根据订单ID获取物料信息
        $str = "";
        if (is_array($orderRow)) {
            $str .= <<<EOT
					<table class="form_in_table">
			                <tr>
			                	<td class="form_text_left">
			                        订单总金额
			                    </td>
			                    <td  class="form_text_right formatMoney" >
			                       &nbsp;
			                       $orderRow[allMoney]
			                    </td>
			                    <td class="form_text_left">
			                        付款条件
			                    </td>
			                    <td class="form_text_right">
			                        &nbsp;
			                        $orderRow[paymentConditionName] &nbsp; $orderRow[payRatio]
			                    </td>
			                </tr>
							<tr>
								<td colspan="7" class="innerTd">
								<table class="form_in_table">
								<thead>

									<tr >
										<td colspan="7" class="form_text_right">采购订单清单</td>
									</tr>

									<tr class="main_tr_header">
										<th>序号</th>
										<th>物料编号</th>
										<th>物料名称</th>
										<th>订单数量</th>
										<th>已入库数量</th>
										<th>含税单价</th>
										<th>金额</th>
									</tr>
								</thead>
EOT;
            if (is_array($equRows)) {
                $i = 0;
                foreach ($equRows as $key => $val) {
                    $i++;
                    $classCss = (($i % 2) == 0) ? "tr_even" : "tr_odd";
                    $str .= <<<EOT
						<tr class="$classCss">
							<td>$i</td>
							<td>$val[productNumb]</td>
							<td>
								$val[productName]
							</td>
							<td>$val[amountAll]</td>
							<td>$val[amountIssued]</td>
							<td class='formatMoney'>$val[applyPrice]</td>
							<td class='formatMoney'>$val[moneyAll]</td>
						</tr>
EOT;
                }
            } else {
                $str = "<tr><td colspan='9'>无物料清单信息</td></tr>";
            }
            $str .= <<<EOT
								</table>
								</td>
							</tr>
					</table>
EOT;

        }
        return $str;
    }

    /**
     * 查询条件模板
     *
     */
    function selectList($logicArr, $fieldArr, $relationArr, $valuesArr)
    {
        $str = "";
        if (is_array($logicArr)) {
            $i = 0;
            foreach ($logicArr as $key => $val) {
                $i++;
                if ($val != "") {
                    if ($fieldArr[$key] == "purchType") {
                        $tdStr = '<select id="values' . $key . '" class="select field"  name="contract[' . $key . '][values]">' .
                            '<option value="oa_sale_order">销售合同采购</option>' .
                            '<option value="oa_sale_lease">租赁合同采购</option>' .
                            '<option value="oa_sale_service">服务合同采购</option>' .
                            '<option value="oa_sale_rdproject">研发合同采购</option>' .
                            '<option value="stock">补库采购</option>' .
                            '<option value="assets">资产采购</option>' .
                            '<option value="rdproject">研发采购</option>' .
                            '<option value="produce">生产采购</option>' .
                            '</select>';
                    } else {
                        $tdStr = '<input type="text" id="values' . $key . '" class="txt value"  name="contract[' . $key . '][values]" value="' . $valuesArr[$key] . '" onblur="trimSpace(this);"/>';
                    }
                    $str .= <<<EOT
						<tr>
							<td>$i</td>
							<td>
								<select id="logic$key" class="selectshort logic" name="contract[$key][logic]">
									<option value="and">并且</option>
									<option value="or">或者</option>
									<SCRIPT language=JavaScript>
									var logic="$val";
									$("select[id='logic$key'] option").each(function(){
												if($(this).val()==logic){
													$(this).attr("selected","selected");
												}
											});
									</SCRIPT>
								</select>
							</td>
							<td>
								<select id="field$key" class="selectmiddel field"  name="contract[$key][field]">
										<option value="suppName">供应商</option>
										<option value="productName">物料名称</option>
										<option value="productNumb">物料代码</option>
										<option value="purchType">采购类型</option>
										<option value="createTime">单据日期</option>
										<option value="moneyAll">订单金额</option>
										<option value="sendName">业务员</option>
										<option value="hwapplyNumb">订单编号</option>
										<option value="batchNumb">批次号</option>
										<option value="sourceNumb">鼎利合同</option>
									<SCRIPT language=JavaScript>
									var field="$fieldArr[$key]";
									$("select[id='field$key'] option").each(function(){
												if($(this).val()==field){
													$(this).attr("selected","selected");
												}
											});

									$(function() {
										$("#field$key").bind("change",function(){
												if($(this).val()=="purchType"){//判断查询字段是否为“采购类型”，如果是则追加选择框
													var tdHtml='<select id="values$key" class="select field"  name="contract[$key][values]">'+
																	'<option value="oa_sale_order">销售合同采购</option>'+
																	'<option value="oa_sale_lease">租赁合同采购</option>'+
																	'<option value="oa_sale_service">服务合同采购</option>'+
																	'<option value="oa_sale_rdproject">研发合同采购</option>'+
																	'<option value="stock">补库采购</option>'+
																	'<option value="assets">资产采购</option>'+
																	'<option value="rdproject">研发采购</option>'+
																	'<option value="produce">生产采购</option>'+
																'</select>';
													$("#type$key").html("");
													$("#type$key").html(tdHtml);
													$("#relation$key").val("equal");
												}else {
													var tdHtml='<input type="text" id="value$key" class="txt value"  name="contract[$key][values]" value="" onblur="trimSpace(this);"/>';
													$("#type$key").html(tdHtml);
													$("#relation$key").val("in");
												}
										});
									});
									</SCRIPT>
								</select>
							</td>
							<td>
								<select id="relation$key" class="selectshort relation"  name="contract[$key][relation]">
									<option value="in">包含</option>
									<option value="equal">等于</option>
									<option value="notequal">不等于</option>
									<option value="greater">大于</option>
									<option value="less">小于</option>
									<option value="notin">不包含</option>
									<SCRIPT language=JavaScript>
									var relation="$relationArr[$key]";
									$("select[id='relation$key'] option").each(function(){
												if($(this).val()==relation){
													$(this).attr("selected","selected");
												}
										});
									</SCRIPT>
								</select>
							</td>
							<td>
								<div id="type$key">$tdStr</div>
								<SCRIPT language=JavaScript>
									var values="$valuesArr[$key]";
									$("select[id='values$key'] option").each(function(){
												if($(this).val()==values){
													$(this).attr("selected","selected");
												}
											});
									</SCRIPT>
							</td>
							<td><img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif"/></td>
						</tr>
EOT;

                }

            }
        } else {
            $str .= "";
        }
        return $str;
    }

    /**
     * 查看询价单的供应商的报价详细.
     *
     * @param $suppRows 供应商报价物料数组
     * @param $inuqiryEqu 询价单物料数组
     */
    function showSupp_s($suppRows, $inuqiryEqu)
    {
        $orderEquDao = new model_purchase_contract_equipment();
        $str = '';
        if ($inuqiryEqu) {
            $orderDateTime = $this->get_table_fields($this->tbl_name, 'id=' . $inuqiryEqu['0']['basicId'], 'createTime');//询价日期
            foreach ($inuqiryEqu as $key => $val) {
                $rows = $orderEquDao->getHistoryInfo_d($val['productNumb'], $orderDateTime);//获取最新历史价格

                $str .= "<tr><td>";
                $str .= $val['productNumb'] . '<img src="images/icon/view.gif" title="查看物料详细信息" onclick="viewProduct(\'' . $val[productId] . '\');"/>' . "<br>";
                $str .= $inuqiryEqu[$key]['name'] = $val['productName'] . "<br>";
                $str .= "数量：" . $val['amountAll'];
                $str .= "</td>";
                foreach ($suppRows as $suppKey => $suppVal) {
                    //判断供应商是否有报价，如果没有报价则设为“暂无报价”
                    if (is_array($suppVal['child'])) {
                        foreach ($suppVal['child'] as $equKey => $equVal) {
                            if ($val['productNumb'] == $equVal['productNumb'] && $val['id'] == $equVal['applyEquId'] && $equVal['parentId'] == $suppVal['id']) {
                                $tax = "<<span class='formatMoney'>$equVal[taxRate]</span>%>";
                                $str .= <<<EOT
							 <td>
							<font color='blue' class='formatMoneySix'>$equVal[price]</font>$tax
							</td>
EOT;
                            }
                        }
                    } else {
                        $str .= "<td>暂无报价</td>";
                    }
                }
                if (is_array($rows)) {
                    $str .= <<<EOT
				 <td>
					<font color='blue' class='formatMoneySix'>$rows[applyPrice]</font><<span class='formatMoney'>$rows[taxRate]</span>%>
				</td>
				 <td class='form_text_right'>
					供应商：<b>$rows[suppName]</b><br/>
					付款条件：<b>$rows[paymentConditionName]  $rows[payRatio]</b><br/>
					订单日期：<b>$rows[orderTime]</b><br/>
					数  量：<b>$rows[amountAll]</b><br/>
				</td>
EOT;
                } else {
                    $str .= <<<EOT
				 <td>
					无历史价格
				</td>
				 <td>
				</td>
EOT;
                }
                $str .= <<<EOT
				<td class='form_text_right'>
					已下单数量：<b>$val[amount]</b><br/>
					$val[referPrice]
					</b><br/>
				</td>
EOT;
            }
            $str .= "</tr>";
        } else {
            $str = "<tr align='center'><td colspan='50'>暂无相应信息</td></tr>";
        }
        return $str;
    }

    /**
     * 渲染付款页面从表
     */
    function payEquList_d($id, $suppId)
    {
        $str = null;
        $i = null;


        $equDao = new model_purchase_contract_equipment ();
        $equRows = $equDao->getEqusByContractId($id);
        if (is_array($equRows)) {
            //获取指定供应商数据表Id
            $suppTableId = $this->get_table_fields('oa_purch_apply_supp', 'parentId=' . $id . " and suppId=" . $suppId, 'id');
            //获取报表物料信息
            $applysuppequDao = new model_purchase_contract_applysuppequ();
            $applysuppequRows = $applysuppequDao->getProByParentId($suppTableId);
            $applysuppDao = new model_purchase_contract_applysupp();
            $applysuppRow = $applysuppDao->get_d($suppTableId);
            $payRatio = "";
            if ($applysuppRow['paymentCondition'] == 'YFK') {
                $payRatio = substr($applysuppRow['payRatio'], 0, -1);
            }

            foreach ($equRows as $key => $val) {
                foreach ($applysuppequRows as $supKey => $supVal) {
                    if ($val['id'] == $supVal['applyEquId']) {
                        $equRows[$key]['applyPrice'] = $supVal['price'];
                        $equRows[$key]['taxRate'] = $supVal['taxRate'];
                        $equRows[$key]['moneyAll'] = round(bcmul($supVal['price'], $val['amountAll'], 3),2);
                        $equRows[$key]['price'] = bcdiv($supVal['price'], round(bcadd(bcdiv($supVal['taxRate'], 100, 2), 1, 2), 7), 6);
                        if ($payRatio != "") {
                            $equRows[$key]['applyMoney'] = bcmul($equRows[$key]['moneyAll'], $payRatio * 0.01, 2);
                        } else {
                            $equRows[$key]['applyMoney'] = $equRows[$key]['moneyAll'];
                        }
                    }
                }
            }
        }
        if (is_array($equRows)) {
            $i = 0;
            foreach ($equRows as $key => $val) {
                $i++;
                $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                $str .= <<<EOT
					<tr class="$thisClass">
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="删除行">
						</td>
						<td>
							$i
						</td>
						<td>
                            <input type="text" name="contract[payablesapply][detail][$i][payMoney]" id="money$i" onblur="checkMax($i);countAll();" value="$val[applyMoney]" class="txtmiddle formatMoney"/>
                            <input type="hidden" id="oldMoney$i" value="$val[applyMoney]"/>
						</td>
                        <td>
                            <input type="text" name="" id="allCount$i" value="$val[moneyAll]" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="" id="number$i" value="$val[amountAll]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="" id="taxPrice$i" value="$val[applyPrice]" class="readOnlyTxtItem formatMoneySix" readonly="readonly"/>
                        </td>
						<td>
							<input type="text" name="" id="productNo$i" value="$val[productNumb]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text"  name="" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="contract[payablesapply][detail][$i][id]" id="id$i" value="$val[id]"/>
						</td>
						<td>
							<input type="text" name="" id="productModel$i" value="$val[pattem]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="" id="unit$i" value="$val[units]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 渲染付款页面从表
     */
    function payEquEditList_d($id)
    {
        $str = null;
        $i = null;

        $equDao = new model_purchase_contract_equipment ();
        $equRows = $equDao->getEqusByContractId($id);
        if (is_array($equRows)) {
            $i = 0;
            foreach ($equRows as $key => $val) {
                if ($val[payMoney] > 0) {
                    $i++;
                    $thisClass = $i % 2 == 1 ? 'tr_even' : 'tr_odd';

                    $str .= <<<EOT
					<tr class="$thisClass">
						<td>
							<img src="images/removeline.png" onclick="mydel(this,'invbody')" title="删除行">
						</td>
						<td>
							$i
						</td>
						<td>
                            <input type="text" name="contract[payablesapply][detail][$i][payMoney]" id="money$i" onblur="checkMax($i);countAll();" value="$val[payMoney]" class="txtmiddle formatMoney"/>
                            <input type="hidden" id="oldMoney$i" value="$val[moneyAll]"/>
						</td>
                        <td>
                            <input type="text" name="" id="allCount$i" value="$val[moneyAll]" class="readOnlyTxtItem formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="" id="number$i" value="$val[amountAll]" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                        </td>
                        <td>
                            <input type="text" name="" id="taxPrice$i" value="$val[applyPrice]" class="readOnlyTxtItem formatMoneySix" readonly="readonly"/>
                        </td>
						<td>
							<input type="text" name="" id="productNo$i" value="$val[productNumb]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text"  name="" id="productName$i" value="$val[productName]" class="readOnlyTxtNormal" readonly="readonly"/>
							<input type="hidden" name="contract[payablesapply][detail][$i][id]" id="id$i" value="$val[id]"/>
						</td>
						<td>
							<input type="text" name="" id="productModel$i" value="$val[pattem]" class="readOnlyTxtItem" readonly="readonly"/>
						</td>
						<td>
							<input type="text" name="" id="unit$i" value="$val[units]" class="readOnlyTxtShort" readonly="readonly"/>
						</td>
					</tr>
EOT;

                }
            }
        }
        return $str;
    }

    /**********************************************外部接口类方法**************************************************/

    /*
	 * @desription 根据询价单ID号获取相应的数据
	 * @param $inquiryId	询价单ID
	 * @author qian
	 * @date 2011-1-3 上午09:36:09
	 */
    function listPC_d($inquiryId)
    {
        //根据询价单ID值检索出询价单的数据
        $inquiryDao = new model_purchase_inquiry_inquirysheet ();
        $inquiryDao->searchArr = array('id' => $inquiryId);
        $inquiryInfo = $inquiryDao->listBySqlId("inquirysheet_list");
        return $inquiryInfo;
    }

    /**
     * @description 根据询价单ID值获取数据
     * @date 2011-03-10 16:01
     * @param $inquiryId    询价单ID
     * @author qian
     */
    function getInquirySheet_d($inquiryId)
    {
        if ($inquiryId) {
            $inquirySheetDao = new model_purchase_inquiry_inquirysheet ();
            $rows = $inquirySheetDao->get_d($inquiryId);

            return $rows;
        } else {
            return null;
        }
    }

    /*
	 * @desription 采购合同的添加方法
	 * @param $contract 采购订单数组
	 * @author qian
	 * @date 2011-1-1 下午01:42:19
	 */
    function add_d($contract)
    {
        try {
            $this->start_d();
            $contract['state'] = 0;
            $contract['isTemp'] = 0;
            $contract['signState'] = 0;
            $contract['objCode'] = $this->objass->codeC("purch_contract");
            $contract['applyNumb'] = "purchcontract-" . generatorSerial();
            $codeDao = new model_common_codeRule();
            $contract['hwapplyNumb'] = $codeDao->purchOrderCode($this->tbl_name, $contract['sendUserId']);
            if ($contract['paymentCondition'] != "YFK") {
                $contract['payRatio'] = "";
            }

            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict ();
            $contract['paymentConditionName'] = $datadictDao->getDataNameByCode($contract['paymentCondition']);
            $contract['paymentTypeName'] = $datadictDao->getDataNameByCode($contract['paymentType']);
            $contract['billingTypeName'] = $datadictDao->getDataNameByCode($contract['billingType']);

            $contractId = parent::add_d($contract, true);
            $inquiryArr = array();
            if (is_array($contract['equs'])) {
                $equDao = new model_purchase_contract_equipment ();
                $taskEquDao = new model_purchase_task_equipment ();
                foreach ($contract['equs'] as $k => $v) {
                    if ($v['productName'] != "") {
                        $v['basicId'] = $contractId;
                        $v['basicNumb'] = $contract['applyNumb'];
                        $v['amountIssued'] = 0;
                        $v['isTemp'] = 0;
                        $v['status'] = 0;
                        $equId = $equDao->add_d($v);
                        if ($v['inquiryEquId'] > 0) {
                            //设置总关联表数据，这里根据设备清单id去找总关联表，如果要更新的数据为空，则进行update操作，否则复制不为空的数据进行新增操作
                            $this->objass->saveModelObjs("purch", array("inquiryEquId" => $v['inquiryEquId']), array("applyCode" => $contract['hwapplyNumb'], "applyId" => $contractId, "applyEquId" => $equId));
                            $inquiryId = $this->get_table_fields('oa_purch_inquiry_equ', 'id=' . $v['inquiryEquId'], 'parentId');
                            array_push($inquiryArr, $inquiryId);
                        }
                        if ($v['taskEquId'] > 0) {
                            //设置总关联表数据，这里根据设备清单id去找总关联表，如果要更新的数据为空，则进行update操作，否则复制不为空的数据进行新增操作
                            $this->objass->saveModelObjs("purch", array("taskEquId" => $v['taskEquId']), array("applyCode" => $contract['hwapplyNumb'], "applyId" => $contractId, "applyEquId" => $equId));
                            //更新采购任务设备的已下达/合同申请数量
                            $taskEquDao->updateContractAmount($v['taskEquId'], $v['amountAll']);

                        }
                        if ($contract['orderType'] == "asset") {
                            $taskItemDao = new model_asset_purchase_task_taskItem();
                            $taskItemDao->updateIssuedAmount($v['applyEquId'], $v['amountAll']);
                        }

                    }
                }
            }


            //由采购询价单下推采购订单时，更新采购询价单的状态
            if (isset($contract['inquiryId']) && $contract['inquiryId'] != "") {
                $inquiryDao = new model_purchase_inquiry_inquirysheet();
                $inquiryIdArr = explode(',', $contract['inquiryId']);
                foreach ($inquiryIdArr as $key => $val) {
                    $inquiryDao->isAddPurchOrder($val, "4");
                }
            } else if (is_array($inquiryArr)) {//判断采购询价是否能把状态改为“已生成订单”
                $inquiryArr = array_unique($inquiryArr);
                $inquiryDao = new model_purchase_inquiry_inquirysheet();
                foreach ($inquiryArr as $key => $val) {
                    if ($inquiryDao->isEndInquiry_d($val)) {
                        $inquiryDao->isAddPurchOrder($val, "4");
                    }
                }
            }

            //更新附件关联关系
            $this->updateObjWithFile($contractId, $contract['applyNumb']);

            //附件处理
            if (isset ($_POST['fileuploadIds']) && is_array($_POST['fileuploadIds'])) {
                $uploadFile = new model_file_uploadfile_management ();
                $uploadFile->updateFileAndObj($_POST['fileuploadIds'], $contractId, $contract['applyNumb']);
            }
            $this->commit_d();
            return $contractId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**
     *
     *
     */
    function addOrder_d($contract)
    {
        try {
            $this->start_d();
            $contract['state'] = 0;
            $contract['isTemp'] = 0;
            $contract['signState'] = 0;
            $contract['objCode'] = $this->objass->codeC("purch_contract");
            $contract['applyNumb'] = "purchcontract-" . generatorSerial();
            $codeDao = new model_common_codeRule();
            $contract['hwapplyNumb'] = $codeDao->purchOrderCode($this->tbl_name, $contract['sendUserId']);

            $contractId = parent::add_d($contract, true);
            if (is_array($contract['equs'])) {
                $equDao = new model_purchase_contract_equipment ();
                $taskEquDao = new model_purchase_task_equipment ();
                foreach ($contract['equs'] as $k => $v) {
                    if ($v['productName'] != "") {
                        $v['basicId'] = $contractId;
                        $v['basicNumb'] = $contract['applyNumb'];
                        $v['amountIssued'] = 0;
                        $v['isTemp'] = 0;
                        $v['status'] = 0;
                        $equId = $equDao->add_d($v);
                        if ($v['taskEquId'] > 0) {
                            //设置总关联表数据，这里根据设备清单id去找总关联表，如果要更新的数据为空，则进行update操作，否则复制不为空的数据进行新增操作
                            $this->objass->saveModelObjs("purch", array("taskEquId" => $v['taskEquId']), array("applyCode" => $contract['hwapplyNumb'], "applyId" => $contractId, "applyEquId" => $equId));
                            //更新采购任务设备的已下达/合同申请数量
                            $taskEquDao->updateContractAmount($v['taskEquId'], $v['amountAll']);

                        }
                        if ($contract['orderType'] == "asset") {
                            $taskItemDao = new model_asset_purchase_task_taskItem();
                            $taskItemDao->updateIssuedAmount($v['applyEquId'], $v['amountAll']);
                        }
                    }
                }
            } else {
                throw new Exception('无物料信息');
            }
            $this->commit_d();
            return $contractId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**
     *新订单保存
     *
     */
    function addOrderEdit_d($rows)
    {
        try {
            $this->start_d();

            if ($rows['paymentCondition'] != "YFK") {  //判断付款条件是否为预付款
                $rows['payRatio'] = "";
            }

            //处理数据字典字段
            $datadictDao = new model_system_datadict_datadict ();
            $rows['paymentConditionName'] = $datadictDao->getDataNameByCode($rows['paymentCondition']);
            $rows['paymentTypeName'] = $datadictDao->getDataNameByCode($rows['paymentType']);
            $rows['billingTypeName'] = $datadictDao->getDataNameByCode($rows['billingType']);

            $rows['isApplyPay'] = isset($rows['isApplyPay']) ? $rows['isApplyPay'] : '0';
            if (is_array($rows)) {
                $id = $this->edit_d($rows, true);
            }

            $equDao = new model_purchase_contract_equipment ();
            $equRows = $equDao->getEqusByContractId($rows['id']);
            if (is_array($equRows)) {
                //获取指定供应商数据表Id
                $suppTableId = $this->get_table_fields('oa_purch_apply_supp', 'parentId=' . $rows['id'] . " and suppId=" . $rows['suppId'], 'id');
                //获取报表物料信息
                $applysuppequDao = new model_purchase_contract_applysuppequ();
                $applysuppequRows = $applysuppequDao->getProByParentId($suppTableId);
                foreach ($equRows as $key => $val) {
                    foreach ($applysuppequRows as $supKey => $supVal) {
                        if ($val['id'] == $supVal['applyEquId']) {
                            $val['applyPrice'] = $supVal['price'];
                            $val['taxRate'] = $supVal['taxRate'];
                            $val['moneyAll'] = round(bcmul($supVal['price'], $val['amountAll'], 4),2);
                            $val['price'] = round(bcdiv($supVal['price'], bcadd(bcdiv($supVal['taxRate'], 100, 4), 1, 4), 7), 6);
                            $val['dateHope'] = $supVal['deliveryDate'];
                        }
                    }
                    $equDao->edit_d($val, true);
                }
            }
            //如果进行预付款
            if ($rows['isApplyPay'] == 1) {
                $payRow = array();
                $payRow['id'] = $rows['id'];
                $payRow['payMoney'] = $rows['payablesapply']['payMoney'];
                $payRow['payType'] = $rows['payablesapply']['payType'];
                $payRow['payDate'] = $rows['payablesapply']['payDate'];
                $payRow['planPayDate'] = $rows['payablesapply']['planPayDate'];
                $payRow['place'] = $rows['payablesapply']['place'];
                $payRow['currency'] = $rows['currency'];
                $payRow['currencyCode'] = $rows['currencyCode'];
                $payRow['rate'] = $rows['rate'];
                $payRow['payRemark'] = $rows['payablesapply']['payRemark'];
                $payRow['payDesc'] = $rows['payablesapply']['payDesc'];
                $id = $this->edit_d($payRow, true);
                if (is_array($rows['payablesapply']['detail'])) {
                    foreach ($rows['payablesapply']['detail'] as $paykey => $payval) {
                        $equDao->edit_d($payval, true);
                    }

                }
            }
            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }


    }

    /**添加供应商
     *author can
     *2010-12-29
     * @param $supp 供应商数组
     */
    function addSupp_d($supp)
    {
        $arr['parentId'] = $supp['parentId'];
        $arr['suppId'] = $supp['supplierId'];
        $arr['parentCode'] = $supp['parentCode'];
        if (util_jsonUtil::is_utf8($supp['supplierName'])) {
            $arr['suppName'] = util_jsonUtil::iconvUTF2GB($supp['supplierName']);
        } else {
            $arr['suppName'] = $supp['supplierName'];
        }
        if (util_jsonUtil::is_utf8($supp['supplierPro'])) {
            $arr['suppTel'] = util_jsonUtil::iconvUTF2GB($supp['supplierPro']);
        } else {
            $arr['suppTel'] = $supp['supplierPro'];
        }
        $supplierDao = new model_purchase_contract_applysupp();
        $suppId = $supplierDao->add_d($arr);
        return $suppId;

    }

    /**如果已选供应商，则重新保存
     */
    function suppAdd_d($supp)
    {
        $id = $supp['suppIds'];
        $arr['parentId'] = $supp['parentId'];
        $arr['suppId'] = $supp['supplierId'];
        if (util_jsonUtil::is_utf8($supp['supplierName'])) {
            $arr['suppName'] = util_jsonUtil::iconvUTF2GB($supp['supplierName']);
        } else {
            $arr['suppName'] = $supp['supplierName'];
        }
        if (util_jsonUtil::is_utf8($supp['supplierPro'])) {
            $arr['suppTel'] = util_jsonUtil::iconvUTF2GB($supp['supplierPro']);
        } else {
            $arr['suppTel'] = $supp['supplierPro'];
        }
        $condiction = array('id' => $id);
        $supplierDao = new model_purchase_contract_applysupp();
        $supplierDao->updateField($condiction, 'suppName', $arr['suppName']);
        $supplierDao->updateField($condiction, 'suppId', $arr['suppId']);
        $supplierDao->updateField($condiction, 'parentId', $arr['parentId']);
        $supplierDao->updateField($condiction, 'suppTel', $arr['suppTel']);
        return $id;
    }

    /**
     * 获取合同分页及合同设备信息
     * $basicId  采购订单ID
     */
    function getContracts()
    {
        //		$this->service->searchArr = array( 'id' => $basicId );
        $contracts = $this->page_d();
        $equDao = new model_purchase_contract_equipment ();
        foreach ($contracts as $k => $v) {
            $equs = $equDao->getEqusByContractId($v['id']); //这里会导致N+1问题，由于时间问题，暂时先这样做，后面优化
            $contracts[$k]['equs'] = $equs;
            $contracts[$k]['statusC'] = $this->stateToVal($contracts[$k]['state']);
            $contracts[$k]['signStatusCn'] = $this->signStatus_d($contracts[$k]['signStatus']);
        }
        return $contracts;
    }

    /**
     * 获取合同分页及合同设备信息包括订单付款金额，发票金额
     * $basicId  采购订单ID
     */
    function getOrderList_d()
    {
        $this->groupBy = 'c.id';
        $contracts = $this->pageBySqlId("orderinfo_money");
        $equDao = new model_purchase_contract_equipment ();
        foreach ($contracts as $k => $v) {
            $equs = $equDao->getEqusByContractId($v['id']); //这里会导致N+1问题，由于时间问题，暂时先这样做，后面优化
            $contracts[$k]['equs'] = $equs;
            $contracts[$k]['statusC'] = $this->stateToVal($contracts[$k]['state']);
            $contracts[$k]['signStatusCn'] = $this->signStatus_d($contracts[$k]['signStatus']);
        }
        return $contracts;
    }

    /*
	 * @desription 获取合同设备信息
	 * @param $basicId	采购订单ID
	 * @author qian
	 * @date 2011-1-8 下午05:32:30
	 */
    function getEquipments_d($basicId)
    {
        $equDao = new model_purchase_contract_equipment ();
        $equDao->searchArr = array('basicId' => $basicId);
        //$equDao->sort = 'c.id';
        $rows = $equDao->listBySqlId("equipment_list");
        $interfObj = new model_common_interface_obj ();
        foreach ($rows as $key => $val) {
            $rows[$key]['purchTypeC'] = $interfObj->typeKToC($val['purchType']); //类型名称
        }
        return $rows;
    }

    /**
     * 获取采购订单的预计付款日期
     * @param $objId
     * @param $obj
     * @return string
     */
    function getPlanPayDateForPush($objId,$obj){
        $planPayDate = "";
        $lastStockInDate = $this->getLastStockInDate($objId);
        if(!empty($lastStockInDate) && isset($obj['paymentCondition'])){
            switch ($obj['paymentCondition']){
                case 'YJ':// 月结
                    $planPayDate = date("Y-m-d",strtotime("{$lastStockInDate} +30 days"));
                    break;
                case 'HDFK':// 货到7天
                    $planPayDate = date("Y-m-d",strtotime("{$lastStockInDate} +7 days"));
                    break;
                case 'HD15T':// 货到15天
                    $planPayDate = date("Y-m-d",strtotime("{$lastStockInDate} +15 days"));
                    break;
                default:// 预付款类
                    if(isset($obj['payDaysAfterArrival']) && $obj['payDaysAfterArrival'] > 0){
                        $planPayDate = date("Y-m-d",strtotime("{$lastStockInDate} +{$obj['payDaysAfterArrival']} days"));
                    }
                    break;
            }
        }

        return $planPayDate;
    }

    /**
     * 获取采购订单关联的最新一次入库单的入库时间
     * @param $objId
     * @return string
     */
    function getLastStockInDate($objId){
        $result = $this->_db->get_one("select id,purOrderCode,auditDate from oa_stock_instock where docType = 'RKPURCHASE' and docStatus = 'YSH' and isRed = 0 and purOrderId = '{$objId}' order by auditDate desc;");
        $lastStockInDate = ($result && isset($result['auditDate']))? $result['auditDate'] : date("Y-m-d");
        return $lastStockInDate;
    }

    /**
     * 检查入库状态
     * @param $basicId
     * @return array
     */
    function chkEquShipStatus($basicId,$sourceCode = ''){
        $backArr = array();

        if(empty($basicId) && !empty($sourceCode)){
            $getBasicIdSql = "select id from oa_purch_apply_basic where hwapplyNumb = '{$sourceCode}' and isTemp = 0";
            $baseId = $this->_db->get_one($getBasicIdSql);
            $basicId = ($baseId && isset($baseId['id']))? $baseId['id'] : 0;
        }

        if($basicId > 0){
            $chkSql = "select sum(amountAll) as amountAll,sum(amountIssued) as amountIssued from oa_purch_apply_equ where basicId = '{$basicId}' and isTemp = 0 group by basicId";
            $result = $this->_db->get_one($chkSql);
            $backArr = $result;
            $backArr['Status'] = '';
            if(isset($result['amountIssued'])){
                if($result['amountIssued'] == 0){
                    $backArr['Status'] = 'WRK';
                    $backArr['StatuName'] = '未入库';
                }else if(isset($result['amountAll']) && $result['amountAll'] > $result['amountIssued']){
                    $backArr['Status'] = 'BFRK';
                    $backArr['StatuName'] = '部分入库';
                }else if(isset($result['amountAll']) && $result['amountAll'] == $result['amountIssued']){
                    $backArr['Status'] = 'YRK';
                    $backArr['StatuName'] = '全部入库';
                }
            }
        }
        return $backArr;
    }

    /*
	 * @desription 获取合同设备信息，物料唯一
	 * @param $basicId	采购订单ID
	 * @author qian
	 * @date 2011-1-8 下午05:32:30
	 */
    function getUniqueEqus_d($basicId)
    {
        $equDao = new model_purchase_contract_equipment ();
        $equDao->searchArr = array('basicId' => $basicId);
        $equDao->groupBy = 'p.productNumb';
        $rows = $equDao->listBySqlId("equipment_list");
        return $rows;
    }

    /**
     * @exclude 通过申请单Id找编号
     * @author ouyang
     * @param $id 申请单Id
     * @return 申请单编号
     * @version 2010-8-10 下午07:06:51
     */
    function findNumbById_d($id)
    {
        $searchArr = array("id" => $id);
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId();
        if ($rows) {
            return $rows['0']['applyNumb'];
        } else {
            return false;
        }
    }

    /*
	 * @desription 完成采购合同
	 * @author qian
	 * @date 2011-1-4 上午10:44:25
	 * @param $id 订单Id
	 */
    function end_d($id)
    {
        $rows = $this->get_d($id);
        $contractEquDao = new model_purchase_contract_equipment ();
        $invpurchasaeDao = new model_finance_invpurchase_invpurchase();
        $payablesDao = new model_finance_payables_payables();
        //获取已付款金额
        $payedMoney = $payablesDao->getPayedMoneyByPur_d($id, 'YFRK-01');
        //获取已发票金额
        $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($id);
        if ($contractEquDao->endByBasicId_d($rows['id']) && $rows['allMoney'] == $payedMoney && $rows['allMoney'] == $handInvoiceMoney) {
            $obj = array("id" => $id, "state" => $this->stateToSta('end'), "dateFact" => date("Y-m-d"));
            if (parent::updateById($obj)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }

    /*
	 * @desription重新启动采购订单
	 * @param $id 订单Id
	 */
    function startOrder_d($id)
    {
        $rows = $this->get_d($id);
        $contractEquDao = new model_purchase_contract_equipment ();
        $invpurchasaeDao = new model_finance_invpurchase_invpurchase();
        $payablesDao = new model_finance_payables_payables();
        //获取已付款金额
        $payedMoney = $payablesDao->getPayedMoneyByPur_d($id, 'YFRK-01');
        //获取已发票金额
        $handInvoiceMoney = $invpurchasaeDao->getInvMoneyByPur_d($id);
        if ($rows['allMoney'] != $payedMoney && $rows['allMoney'] != $handInvoiceMoney) {
            $obj = array("id" => $id, "state" => $this->stateToSta('wite'));
            if (parent::updateById($obj)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 2;
        }
    }


    /**
     * 申请变更添加方法
     *author can
     *2011-1-12
     */
    function change_d($obj)
    {
        $this->start_d();
        $contractEquDao = new model_purchase_contract_equipment ();
        $inquiryEquDao = new model_purchase_inquiry_equmentInquiry ();
        //var_dump($obj['equs']);
        if ($obj['paymentCondition'] != "YFK") {
            $obj['payRatio'] = "";
        }
        //删除设备处理
        foreach ($obj['equs'] as $key => $val) {
            if ($val['amountAll'] == 0) {
                //	$obj['equs'][$key]['isDel'] = 1;
            }
        }
        //临时设备处理
        if (is_array($obj['temp'])) {
            if (is_array($obj['equs'])) {
                $obj['equs'] = array_merge($obj['equs'], $obj['temp']);
            } else {
                $obj['equs'] = $obj['temp'];
            }

        }

        //变更附件处理
        $changeLogDao = new model_common_changeLog ('purchasecontract');
        $obj['uploadFiles'] = $changeLogDao->processUploadFile($obj, $this->tbl_name);
        //变更记录,拿到变更的临时主对象id
        $obj['signStatus_cn'] = $this->signStatus_d($obj['signStatus']);
        $tempObjId = $changeLogDao->addLog($obj);

        $emailArr = $obj['email'];
        unset($obj['email']);

        //发送邮件 ,当操作为提交时才发送
        if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->batchEmail($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '变更', $obj['hwapplyNumb'], $emailArr['TO_ID'], $addmsg = null, 1);
        }

        $this->commit_d();
        return $tempObjId;
    }

    //变更审批处理方法
    function dealChange_d($obj)
    {
        $contractEquDao = new model_purchase_contract_equipment ();
        $taskEquDao = new model_purchase_task_equipment ();
        $taskDao = new model_purchase_task_basic();
        $productInfoDao = new model_stock_productinfo_productinfo();
        $arrivalEquDao = new model_purchase_arrival_equipment();
        $stockEquDao = new model_stock_instock_stockinitem();
        if ($obj['ExaStatus'] == "完成") {
            $contractEquRows = $contractEquDao->getEqusByContractIdNew($obj['id']);
            if (is_array($contractEquRows)) {
                foreach ($contractEquRows as $key => $val) {
                    $oldRow = $contractEquDao->get_d($val['originalId']);
                    if ($val['originalId'] > 0 && $val['taskEquId'] > 0) {
                        if ($oldRow['amountAll'] - $val['amountAll'] > 0) {
                            $taskequRow = $taskEquDao->get_d($val['taskEquId']);
                            //更新采购任务的状态，重新开启采购任务
                            $taskDao->startTask_d($taskequRow['basicId']);
                            //更新采购任务的合同数量
                            $taskEquDao->updateContractAmount($val['taskEquId'], 0, $oldRow['amountAll'] - $val['amountAll']);
                            //更新采购任务的询价数量
//							$taskEquDao->updateAmountIssued ( $val['taskEquId'], 0, $oldRow['amountAll']-$val['amountAll'] );
                        }
                    }
                    //更新收料单的物料单价
                    $arrivalEquDao->update(array('contractId' => $oldRow['id']), array('price' => $val['price']));
                    //更新入库单的物料单价
                    $arrivaalEquRow = $arrivalEquDao->getItemByContractEquId_d($oldRow['id']);
                    if (is_array($arrivaalEquRow)) {
                        foreach ($arrivaalEquRow as $aKey => $aVal) {
                            $stockEquDao->update(array('relDocId' => $aVal['id']), array('price' => $val['price'], 'subPrice' => $val['price'], 'unHookAmount' => $val['price']));
                        }
                    }
                    //更新物料最新价格
                    $productInfoDao->update(array('id' => $val['productId'], 'priceLock' => 0), array('priCost' => $val['price']));
                }
            }

        }
    }

    /**签收订单
     */
    function signOrder_d($obj)
    {
        $this->start_d();
        if ($obj['paymentCondition'] != "YFK") {
            $obj['payRatio'] = "";
        }
        //变更附件处理
        $changeLogDao = new model_common_changeLog ('purchasesign', false);
        $obj['uploadFiles'] = $changeLogDao->processUploadFile($obj, $this->tbl_name);
        //变更记录,拿到变更的临时主对象id
        $obj['signStatus_cn'] = $this->signStatus_d($obj['signStatus']);
        $changeLogDao->addLog($obj);

        //修改合同
        if (is_array($obj)) {
            $obj['id'] = $obj['oldId'];
            $obj['signState'] = 1;
            $obj['signTime'] = date("Y-m-d H:i:s");
            $flag = $this->edit_d($obj, true);
        }
        if (is_array($obj['equs'])) {
            $equDao = new model_purchase_contract_equipment ();
            foreach ($obj['equs'] as $key => $val) {
                $val['id'] = $val['oldId'];
                $equDao->edit_d($val, true);
            }
        }
        $this->commit_d();
        return $flag;


    }


    /**删除采购合同，并维护采购任务的合同设备数量
     *author can
     *2011-1-13
     * @param $id 订单Id
     */
    function delete_d($id)
    {
        try {
            $this->start_d();
            $contractRows = $this->get_d($id);
            $taskEquDao = new model_purchase_task_equipment ();
            $contractEquDao = new model_purchase_contract_equipment ();
            $taskDao = new model_purchase_task_basic();
            $contractEquRows = $contractEquDao->getEqusByContractId($id);
            $this->deletes($id);
            //更新采购任务的合同数量
            foreach ($contractEquRows as $key => $val) {
                if (!isset ($val['amountAll'])) {
                    $val['amountAll'] = 0;
                }
                if ($val['taskEquId']) {
                    $taskEquDao->updateContractAmount($val['taskEquId'], 0, $val['amountAll']);
                    $taskequRow = $taskEquDao->get_d($val['taskEquId']);
                    //更新采购任务的状态，重新开启采购任务
                    $taskDao->startTask_d($taskequRow['basicId']);
                }
            }
            //如果采购订单是由采购询价单生成的，删除时则更采购询价单的状态
            $sql = "select inquiryId from oa_purch_objass where applyId=" . $id . " and applyCode='" . $contractRows['hwapplyNumb'] . "'";
            $inquiryId = $this->findSql($sql);
            if ($inquiryId) {
                $inquiryDao = new model_purchase_inquiry_inquirysheet();
                foreach ($inquiryId as $key => $val) {
                    $inquiryDao->isAddPurchOrder($val['inquiryId'], "2");
                }

            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 根据主键获取对象
     * @param $id 订单Id
     */
    function get_d($id)
    {
        //return $this->getObject($id);
        $condition = array("id" => $id);
        $rows = $this->find($condition);
        $rows['signStatus_cn'] = $this->signStatus_d($rows['signStatus']);
        return $rows;
    }

    /**
     * @description 对合同的签约合同的值进行转换
     * @author qian
     * @date 2011-03-09 16:23
     *
     * @param $signStatus 签约状态
     */
    function signStatus_d($signStatus)
    {
        switch ($signStatus) {
            case "0" :
                return "未签约";
            case "1" :
                return "已签约";
            case "2" :
                return "已拿到纸质合同";
            case "3" :
                return "已提交纸质合同";
        }
    }

    /**
     * @description 编辑修改签约合同的状态位
     * @author qian
     * @date 2011-03-09
     * @param $value 签约状态
     */
    function editSignStatus_d($value)
    {
        if ($value == "0") {
            $val1 = "checked";
            $val2 = "";
            $val3 = "";
            $val4 = "";
        } elseif ($value == "1") {
            $val1 = "";
            $val2 = "checked";
            $val3 = "";
            $val4 = "";
        } elseif ($value == "2") {
            $val1 = "";
            $val2 = "";
            $val3 = "checked";
            $val4 = "";
        } elseif ($value == "3") {
            $val1 = "";
            $val2 = "";
            $val3 = "";
            $val4 = "checked";
        }
        $str = <<<EOT
			<input type="radio" name="sales[signStatus]" value="0" $val1>未签约
	 		<input type="radio" name="sales[signStatus]" value="1" $val2>已签约
	 		<input type="radio" name="sales[signStatus]" value="2" $val3>已拿到纸质合同
	 		<input type="radio" name="sales[signStatus]" value="3" $val4>已提交纸质合同
EOT;
        return $str;

    }

    /**根据合同类型，合同物料id,查找在途数量
     * $purchType 采购类型
     * $equId  合同物料id
     *author can
     *2011-6-15
     */
    function getOrderNumber($purchType, $equId)
    {
        //获取采购订单物料Id
        $equSql = "select applyEquId from oa_purch_objass where planAssType='" . $purchType . "' and planAssEquId=" . $equId;
        //获取采购订单id
        $orderSql = " select id from oa_purch_apply_basic where ExaStatus='完成' and  id in (select applyId from oa_purch_objass where planAssType='" . $purchType . "' and planAssEquId=" . $equId . ")";
        //统计合同物料的数量
        $sql = "select sum(amountAll-) from oa_purch_apply_equ where  basicId in(" . $orderSql . ") and id in (" . $equSql . ")";
        $res = $this->query($sql);
        $arr = mysql_fetch_row($res);
        if ($arr[0]) {
            $number = $arr[0];
        } else {
            $number = 0;
        }
        return $number;
    }

    /**更新合同的在途数量
     *author can
     *$objId 采购订单ID
     *2011-6-16
     * $objId  订单id
     */
    function updateOnWayNumb_d($objId)
    {
        //获取订单物料信息
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusByContractId($objId);

        $contractDao = new model_contract_contract_equ();
        $productInfoDao = new model_stock_productinfo_productinfo();
        $taskIds = array();
        foreach ($equRows as $key => $val) {
            //获取采购类型与申请采购的物料源ID
            if ($val['taskEquId'] && $val['purchType'] != "oa_asset_purchase_apply") {
                $sql = "select planAssType,planAssEquId,taskId from oa_purch_objass where taskEquId=" . $val['taskEquId'] . " and applyId=" . $objId;
                $res = $this->query($sql);
                $arr = mysql_fetch_row($res);
                if (is_array($arr)) {
                    if ($arr[0] == "HTLX-YFHT" || $arr[0] == "HTLX-FWHT" || $arr[0] == "HTLX-ZLHT" || $arr[0] == "HTLX-XSHT") {  //更新合同的在途数量
                        $contractDao->updateOnWayNum($arr[1], $val['amountAll'], "add");
                    }
                    array_push($taskIds, $arr[2]);
                }
            }

            //更新物料最新价格
            $productInfoDao->update(array('id' => $val['productId'], 'priceLock' => 0), array('priCost' => $val['price']));
        }
        $taskIdArr = array_unique($taskIds);
        if (is_array($taskIdArr)) {
            $taskDao = new model_purchase_task_basic();
            foreach ($taskIdArr as $taskKey => $taskVal) {//判断采购任务的物料是否已全部下达,若全部下达，则任务的状态改为完成
                $taskDao->end_d($taskVal);
            }
        }
    }

    /**
     *其他合同立项付款申请审批成功后处理
     */
    function dealAfterAuditPayapply_d($object, $spid)
    {
        try {
            $this->start_d();
            $payablesapplyDao = new model_finance_payablesapply_payablesapply();
            $otherdatasDao = new model_common_otherdatas();
            $userinfo = $otherdatasDao->getUserInfo($object['sendName']);
            $folowInfo = $otherdatasDao->getStepInfo($spid);
            //获取订单物料信息
            $equDao = new model_purchase_contract_equipment();
            $equRows = $equDao->getEqusByContractId($object['id']);
            $detailArr = array();
            if (is_array($equRows)) {
                foreach ($equRows as $key => $val) {
                    $detailArr[$key]['objCode'] = $object['hwapplyNumb'];
                    $detailArr[$key]['purchaseMoney'] = $object['allMoney'];
                    $detailArr[$key]['objId'] = $object['id'];
                    $detailArr[$key]['objType'] = 'YFRK-01';
                    $detailArr[$key]['expand1'] = $val['id'];
                    $detailArr[$key]['expand3'] = $val['moneyAll'];
                    $detailArr[$key]['money'] = $val['payMoney'];
                    $detailArr[$key]['allAmount'] = $val['moneyAll'];
                    $detailArr[$key]['number'] = $val['amountAll'];
                    $detailArr[$key]['price'] = $val['applyPrice'];
                    $detailArr[$key]['productNo'] = $val['productNumb'];
                    $detailArr[$key]['productName'] = $val['productName'];
                    $detailArr[$key]['productId'] = $val['productId'];
                    $detailArr[$key]['productModel'] = $val['pattem'];
                    $detailArr[$key]['unitName'] = $val['units'];
                }
            }
            //构建付款申请数组
            $payablesapplyArr = array(
                'deptName' => $userinfo['DEPT_NAME'],
                'deptId' => $userinfo['DEPT_ID'],
                'salesman' => $object['sendName'],
                'salesmanId' => $object['sendUserId'],
                'supplierName' => $object['suppName'],
                'supplierId' => $object['suppId'],
                'payMoney' => $object['payMoney'],
                'payMoneyCur' => $object['payMoney'] * $object['rate'],
                'payDate' => $object['payDate'],
                'planPayDate' => $object['planPayDate'],
                'auditDate' => $object['payDate'],
                'formDate' => day_date,
                'feeDeptName' => '',
                'feeDeptId' => '',
                'bank' => $object['suppBankName'],
                'account' => $object['suppAccount'],
                'payFor' => 'FKLX-01',
                'payType' => $object['payType'],
                'remark' => $object['payRemark'],
                'payCondition' => $object['paymentConditionName'] . ' ' . $object['payRatio'],
                'sourceCode' => $object['hwapplyNumb'],
                'sourceType' => 'YFRK-01',
                'ExaStatus' => '完成',
                'ExaDT' => day_date,
                'exaId' => $object['id'],
                'exaCode' => $this->tbl_name,
                'ExaUser' => $folowInfo['USER_NAME'],
                'ExaUserId' => $folowInfo['USER_ID'],
                'ExaContent' => $folowInfo['content'],
                'payDesc' => $object['payDesc'],
                'isEntrust' => '',
                'currency' => $object['currency'],
                'currencyCode' => $object['currencyCode'],
                'place' => $object['place'],
                'rate' => $object['rate'],
                'detail' => $detailArr
            );
            $payablesapplyArr['createId'] = $payablesapplyArr['updateId'] = $object['sendUserId'];
            $payablesapplyArr['createName'] = $payablesapplyArr['updateName'] = $object['sendName'];
            $payablesapplyArr['createTime'] = $payablesapplyArr['updateTime'] = date("Y-m-d H:i:s");
            $payablesapplyId = $payablesapplyDao->addOnly_d($payablesapplyArr);

            $this->commit_d();
            return 1;
        } catch (exception $e) {
            $this->rollBack();
        }
    }

    /**被打回的采购订单提交审批的处理方法
     * @param $rows 采购订单信息数组
     *author can
     *2011-7-27
     */
    function dealApproval_d($rows)
    {
        try {
            $this->start_d();
            //获取订单物料信息
            $equDao = new model_purchase_contract_equipment();
            $equRows = $equDao->getEqusByContractId($rows['id']);
            //修改原采购订单的状态为“关闭”
            $condiction = array('id' => $rows['id']);
            $this->updateField($condiction, 'state', $this->stateToSta('close'));
            unset($rows['id']);
            $newId = $this->addOther_d($rows, true);
            foreach ($equRows as $key => $val) {
                unset($val['id']);
                $val['basicId'] = $newId;
                $equDao->add_d($val);
            }
            $this->commit_d();
            return $newId;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**新增方法，与add_d()区分
     *author can
     *2011-7-27
     */
    function addOther_d($object, $isAddInfo = false)
    {
        if ($isAddInfo) {
            $object = $this->addCreateInfo($object);
        }
        //加入数据字典处理 add by chengl 2011-05-15
        $newId = $this->create($object);
        return $newId;
    }

    /**
     * 审核入库时进行更新收料通知单信息
     * @param  $id   采购订单ID
     * @param  $equId   物料清单ID
     * @param  $productId   物料ID
     * @param  $proNum    入库数量
     */
    function updateInStock($id, $equId, $productId, $proNum, $docDate = day_date)
    {
        $eupDao = new model_purchase_contract_equipment();
        $eupDao->updateAmountIssued($equId, $proNum, false, $docDate);
    }

    /**
     * 反审核入库时进行更新收料通知单信息
     * @param  $id   采购订单单ID
     * @param  $equId   物料清单ID
     * @param  $productId   物料ID
     * @param  $proNum    入库数量
     */
    function updateInStockCancel($id, $equId, $productId, $proNum, $docDate = day_date)
    {
        $eupDao = new model_purchase_contract_equipment();
        $eupDao->updateAmountIssued($equId, -$proNum, false, $docDate);
//		$equRows=$arrivalEupDao->getItemIDByBasicIdId_d($id);
//		$arrivalEupDao->updateOnWay_d($equRows['id'],$proNum);
    }

    /**
     * 判断是否为变更的合同
     */
    function isTemp($conId)
    {
        $cond = array("id" => $conId);
        $isTemp = $this->find($cond, '', 'isTemp');
        $isTemp = implode(',', $isTemp);
        return $isTemp;
    }

    /**
     * 单独附件上传
     */
    function uploadfile_d($service)
    {
        try {
            //处理附件名称和Id
            $rows = $this->get_d($service['id']);
            $id = $this->edit_d($service);
            $this->updateObjWithFile($service['serviceId']);

            //发送邮件进行通知
            $emailArr = $service['email'];
            unset($service['email']);
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $signStatusCn = $this->signStatus_d($service['signStatus']);
                $msg = "采购订单编号为《<font color=red><b>" . $rows['hwapplyNumb'] . "</b></font>》的签约状态已改变:<font color=blue><b>" . $rows['signStatus_cn'] . "</b></font>--><font color=blue><b>" . $signStatusCn . "</b></font>";
                $addmsg = "";
                $emailDao = new model_common_mail();
                $emailInfo = $emailDao->contractEmail($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $msg, '', $emailArr['TO_ID'], $addmsg, 0);
            }

            return $id;
        } catch (exception $e) {
            return false;
        }
    }

    /**
     * 盖章申请
     */
    function seal_d($service)
    {
        try {
            //处理附件名称和Id
            $rows = $this->get_d($service['id']);
            $object = array();
            $object['id'] = $service['id'];
            $object['stampType'] = $service['stamp']['stampType'];
            $object['isStamp'] = 0;
            $object['isNeedStamp'] = 1;

            $id = $this->edit_d($object);
            $this->updateObjWithFile($service['serviceId']);
            $service['stamp']['contractMoney'] = $rows['allMoney'];
            $stampDao = new model_contract_stamp_stamp();

            //获取对应对象的最大批次号
            $maxBatchNo = $stampDao->get_table_fields($stampDao->tbl_name, " contractType = 'HTGZYD-03' and contractId=" . $service['id'], "max(batchNo)");
            $service['stamp']['batchNo'] = $maxBatchNo + 1;
            $stampDao->add_d($service['stamp'], true);
            return $id;
        } catch (exception $e) {
            return false;
        }
    }

    /**根据采购订单id,获取物料入库信息
     * @param $orderId 采购订单ID
     */
    function getStockInfo_d($orderId)
    {
        $param = array();
        $arrivalIds = array();
        //根据采购订单ID,获取收料通知单的id
        $sql = "select id from oa_purchase_arrival_info where purchaseId=" . $orderId;
        $arrivalId = $this->findSql($sql);
        if (is_array($arrivalId)) {
            foreach ($arrivalId as $key => $val) {
                $arrivalIds[] = $val['id'];
            }
        }
        $arrIdsStr = implode(',', $arrivalIds);   //收料通知单ID转换为字符串
        $param['RCGDD'] = $orderId;
        $param['RSLTZD'] = $arrIdsStr;

    }

    /**
     * 根据采购订单ID，获取采购询价单ID
     *
     */
    function getInquiryId_d($id)
    {
        $sql = "select inquiryEquId from oa_purch_apply_equ where basicId=" . $id;
        $inquiryEquId = $this->_db->getArray($sql);
        $inquiryIdArr = array();
//		if($inquiryEquId['0']['inquiryEquId']!=""){
        //获取采购询价单ID
        foreach ($inquiryEquId as $key => $val) {
            if ($val['inquiryEquId'] != "") {
                $inquCondition = "id=" . $val['inquiryEquId'];
                $inquId = $this->get_table_fields('oa_purch_inquiry_equ', $inquCondition, 'parentId');
                array_push($inquiryIdArr, $inquId);

            }
        }
//		}
        $inquiryIdStr = "";
        if ($inquiryIdArr) {
            $inquiryIdStr = implode(',', array_unique($inquiryIdArr));
        }
        return $inquiryIdStr;
    }

    // create by huanghaojin
    /**
     * 采购订单审批通过后，发送邮件
     * @param $id 采购订单Id
     * @return $rows
     */
    function getApplyBasic_d($id)
    {
        $sql = "select * from oa_purch_plan_basic where id IN (select planId from oa_purch_objass where applyId = '$id' group by planId)";
        $rows = $this->findSql($sql);
        return $rows;
    }

    /**
     * 采购订单审批通过后，发送邮件
     * @param $rows 采购订单信息
     */
    function sendEmail_d($rows)
    {
        try {
            $this->start_d();

            $emailArr = array();
            $emailArr['issend'] = 'y';
            //采购员
//			$emailArr['TO_ID']=$rows['sendUserId'];
//			$emailArr['TO_NAME']=$rows['sendName'];
            // 申请人
            $emailArr['TO_ID'] = $rows['applyBasic']['sendUserId'];
            $emailArr['TO_NAME'] = $rows['applyBasic']['sendName'];
            if ($emailArr['TO_ID'] != "") {
                $addmsg = "";
                $interfObj = new model_common_interface_obj ();
                //获取采购订单物料信息
                $equDao = new model_purchase_contract_equipment();
                $equRows = $equDao->getEqusByContractId($rows['id']);
                if (is_array($equRows)) {
                    $j = 0;
                    //构造表格详细信息
                    $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料名称</b></td><td><b>规格型号</b></td><td><b>单位</b></td><td><b>订单数量</b></td><td><b>期望到货时间</b></td><td><b>到货时间</b></td><td><b>采购用途</b></td><td><b>申请部门</b></td><td><b>源单编号</b></td></tr>";
                    foreach ($equRows as $key => $equ) {
                        $j++;
                        $productName = $equ['productName'];
                        $pattem = $equ['pattem'];
                        $unitName = $equ['units'];
                        $amountAll = $equ['amountAll'];
                        $dateIssued = $equ['dateIssued'];
                        $dateHope = $equ['dateHope'];
                        $purchTypeCn = $interfObj->typeKToC($equ['purchType']); //类型名称
                        $applyDeptName = $equ['applyDeptName'];
                        $sourceNumb = $equ['sourceNumb'];
                        $addmsg .= <<<EOT
						<tr align="center" >
									<td>$j</td>
									<td>$productName</td>
									<td>$pattem</td>
									<td>$unitName</td>
									<td>$amountAll</td>
									<td>$dateIssued</td>
									<td>$dateHope</td>
									<td>$purchTypeCn</td>
									<td>$applyDeptName</td>
									<td>$sourceNumb</td>
								</tr>
EOT;
                    }
                    $addmsg .= "</table>";
                }
                $emailDao = new model_common_mail();
                $emailInfo = $emailDao->emailInquiry($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '采购订单已审批通过,采购订单编号为：<font color=red><b>' . $rows['hwapplyNumb'] . '</b></font>', '', $emailArr['TO_ID'], $addmsg, 1);

            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**
     * 在执行的订单数组处理
     *
     */
    function dealExeRows_d($rows, $sumrows)
    {
        $interfObj = new model_common_interface_obj ();
        $changeLogDao = new model_common_changeLog('purchasecontract');
        if (is_array($rows)) {
            /*小计统计*/
            $payedAll = 0;
            $sumMount = 0;
            $amountIssued = 0;
            $sumMoneyAll = 0;
            $unpayedAll = 0;
            $handInvoiceMoneyAll = 0;
            foreach ($rows as $key => $val) {
                //计算数量总数量
                $sumMount = bcadd($sumMount, $val['amountAll']);
                $amountIssued = bcadd($amountIssued, $val['amountIssued']);
                $sumMoneyAll = bcadd($sumMoneyAll, $val['allMoney'], 6);
                $rows[$key]['isChanging'] = $changeLogDao->isChanging($val['id']);//是否变更中
                $rows[$key]['stateC'] = $this->stateToVal($val['state']);
                $rows[$key]['paymentConditionName'] = $val['paymentConditionName'] . "   " . $val['payRatio'];
                $rows[$key]['purchType'] = $interfObj->typeKToC($val['purchType']); //类型名称
                if ($val['payRatio']) {//计算预付款金额
                    $rows[$key]['newPayRation'] = substr($val['payRatio'], 0, -1);
                    $rows[$key]['YFPay'] = $val['allMoney'] * ($rows[$key]['newPayRation'] / 100);
                }
                if ($val['payed'] == 0) {
                    $rows[$key]['payState'] = '未付款';
                } else if ($val['payed'] > 0 && $val['payed'] < $val['allMoney']) {
                    $rows[$key]['payState'] = '部分付款';
                } else {
                    $rows[$key]['payState'] = '完成付款';
                }
                $rows[$key]['viewType'] = 1;//用来区分是数据还是总计
                $payedAll = bcadd($payedAll, $rows[$key]['payed'], 6);
                $unpayedAll = bcadd($unpayedAll, $rows[$key]['unpayed'], 6);
                $handInvoiceMoneyAll = bcadd($handInvoiceMoneyAll, $rows[$key]['handInvoiceMoney'], 6);
            }
            $rows[$key + 1]['paymentConditionName'] = "单页小计:";
            $rows[$key + 1]['amountAll'] = $sumMount;
            $rows[$key + 1]['allMoney'] = $sumMoneyAll;
            $rows[$key + 1]['payed'] = $payedAll;
            $rows[$key + 1]['unpayed'] = $unpayedAll;
            $rows[$key + 1]['amountIssued'] = $amountIssued;
            $rows[$key + 1]['handInvoiceMoney'] = $handInvoiceMoneyAll;
            $rows[$key + 1]['viewType'] = 0;//用来区分是数据还是总计
            //总计统计
            $sumPayedAll = 0;
            $sumMountAll = 0;
            $sumAmountIssued = 0;
            $sumMoney = 0;
            $sumUnpayedAll = 0;
            $sumHandInvoiceMoneyAll = 0;

            foreach ($sumrows as $sumKey => $sumVal) {
                //计算数量总数量
                $sumMountAll = bcadd($sumMountAll, $sumVal['amountAll']);
                $sumAmountIssued = bcadd($sumAmountIssued, $sumVal['amountIssued']);
                $sumMoney = bcadd($sumMoney, $sumVal['allMoney'], 6);
                $sumPayedAll = bcadd($sumPayedAll, $sumrows[$sumKey]['payed'], 6);
                $sumUnpayedAll = bcadd($sumUnpayedAll, $sumrows[$sumKey]['unpayed'], 6);
                $sumHandInvoiceMoneyAll = bcadd($sumHandInvoiceMoneyAll, $sumrows[$sumKey]['handInvoiceMoney'], 6);
                $rows[$key + 2]['paymentConditionName'] = "总计:";
                $rows[$key + 2]['amountAll'] = $sumMountAll;
                $rows[$key + 2]['allMoney'] = $sumMoney;
                $rows[$key + 2]['payed'] = $sumPayedAll;
                $rows[$key + 2]['unpayed'] = $sumUnpayedAll;
                $rows[$key + 2]['amountIssued'] = $sumAmountIssued;
                $rows[$key + 2]['handInvoiceMoney'] = $sumHandInvoiceMoneyAll;
                $rows[$key + 2]['viewType'] = 0;//用来区分是数据还是总计

            }
        }
        return $rows;
    }

    /**
     * 执行完毕的订单数组处理
     *
     */
    function dealEndRows_d($rows, $sumrows)
    {
        $interfObj = new model_common_interface_obj ();
        $payedAll = 0;
        $sumMount = 0;
        $amountIssued = 0;
        $sumMoneyAll = 0;
        $unpayedAll = 0;
        $handInvoiceMoneyAll = 0;
        if (is_array($rows)) {
            foreach ($rows as $key => $val) {
                //计算数量总数量
                $sumMount = bcadd($sumMount, $val['amountAll']);
                $amountIssued = bcadd($amountIssued, $val['amountIssued']);
                $sumMoneyAll = bcadd($sumMoneyAll, $val['allMoney'], 6);
                $rows[$key]['stateC'] = $this->stateToVal($val['state']);
                $rows[$key]['purchType'] = $interfObj->typeKToC($val['purchType']); //类型名称
                $payedAll = bcadd($payedAll, $rows[$key]['payed'], 6);
                $handInvoiceMoneyAll = bcadd($handInvoiceMoneyAll, $rows[$key]['handInvoiceMoney'], 6);
                $rows[$key]['viewType'] = 1;//用来区分是数据还是总计
            }
            $rows[$key + 1]['sendName'] = "单页小计:";
            $rows[$key + 1]['amountAll'] = $sumMount;
            $rows[$key + 1]['allMoney'] = $sumMoneyAll;
            $rows[$key + 1]['payed'] = $payedAll;
            $rows[$key + 1]['amountIssued'] = $amountIssued;
            $rows[$key + 1]['handInvoiceMoney'] = $handInvoiceMoneyAll;
            $rows[$key + 1]['viewType'] = 0;//用来区分是数据还是总计

            //总计统计
            $sumPayedAll = 0;
            $sumMountAll = 0;
            $sumAmountIssued = 0;
            $sumMoney = 0;
            $sumUnpayedAll = 0;
            $sumHandInvoiceMoneyAll = 0;

            foreach ($sumrows as $sumKey => $sumVal) {
                //计算数量总数量
                $sumMountAll = bcadd($sumMountAll, $sumVal['amountAll']);
                $sumAmountIssued = bcadd($sumAmountIssued, $sumVal['amountIssued']);
                $sumMoney = bcadd($sumMoney, $sumVal['allMoney'], 6);
                $sumPayedAll = bcadd($sumPayedAll, $sumrows[$sumKey]['payed'], 6);
                $sumUnpayedAll = bcadd($sumUnpayedAll, $sumrows[$sumKey]['unpayed'], 6);
                $sumHandInvoiceMoneyAll = bcadd($sumHandInvoiceMoneyAll, $sumrows[$sumKey]['handInvoiceMoney'], 6);

            }
            $rows[$key + 2]['sendName'] = "总计:";
            $rows[$key + 2]['amountAll'] = $sumMountAll;
            $rows[$key + 2]['allMoney'] = $sumMoney;
            $rows[$key + 2]['payed'] = $sumPayedAll;
            $rows[$key + 2]['amountIssued'] = $sumAmountIssued;
            $rows[$key + 2]['handInvoiceMoney'] = $sumHandInvoiceMoneyAll;
            $rows[$key + 2]['viewType'] = 0;//用来区分是数据还是总计
        }
        return $rows;
    }

    /**
     * 采购信息数组处理
     *
     */
    function dealEquRows_d($rows)
    {
        $interfObj = new model_common_interface_obj ();
        if (is_array($rows)) {
            $sumMount = 0;//数量总数量
            $sumNoTaxMoney = 0;//不含税总金额
            $sumMoneyAll = 0;//含税总金额
            foreach ($rows as $key => $val) {
                if ($rows[$key - 1]['id'] == $rows[$key]['id']) {
                    $rows[$key]['hwapplyNumb'] = "";
                    $rows[$key]['suppName'] = "";
                    $rows[$key]['createTime'] = "";
                }
                //计算数量总数量
                $sumMount = bcadd($sumMount, $val['amountAll']);
                $sumNoTaxMoney = bcadd($sumNoTaxMoney, $val['noTaxMoney'], 6);
                $sumMoneyAll = bcadd($sumMoneyAll, $val['moneyAll'], 6);
                $rows[$key]['taxRate'] = $val['taxRate'] . "%";
                $rows[$key]['stateC'] = $this->stateToVal($val['state']);
                $rows[$key]['purchType'] = $interfObj->typeKToC($val['purchType']); //类型名称
            }
            $rows[$key + 1]['sendName'] = "合计:";
            $rows[$key + 1]['amountAll'] = $sumMount;
            $rows[$key + 1]['noTaxMoney'] = $sumNoTaxMoney;
            $rows[$key + 1]['moneyAll'] = $sumMoneyAll;

        }
        return $rows;
    }

    /**
     * 采购信息数组处理
     *
     */
    function dealExecutequRows_d($rows)
    {
        $interfObj = new model_common_interface_obj ();
        $arrivalEquDao = new model_purchase_arrival_equipment();
        $datadictDao = new model_system_datadict_datadict ();
        if (is_array($rows)) {
            $sumMount = 0;
            foreach ($rows as $key => $val) {
                $arrivalEquRows = $arrivalEquDao->getItemByContractEquId_d($val['Pid']);
                $arrivalNum = 0;
                if (is_array($arrivalEquRows)) {   //获取某物料的收料情况
                    foreach ($arrivalEquRows as $arrKey => $arrVal) {
                        $arrivalNum = $arrivalNum + $arrVal['arrivalNum'];
                    }
                }
                //计算数量总数量
                $sumMount = bcadd($sumMount, $val['amountAll']);
                $rows[$key]['checkType'] = $this->get_table_fields("oa_stock_product_info", "id=" . $val['productId'], "checkType");
                $rows[$key]['purchType'] = $interfObj->typeKToC($val['purchType']); //类型名称
                $rows[$key]['checkTypeName'] = $datadictDao->getDataNameByCode($rows[$key]['checkType']);
                $rows[$key]['arrivalNum'] = $arrivalNum;
                $rows[$key]['today'] = date('Y-m-d');
            }
        }
        return $rows;
    }

    /*判断是否能完成采购订单
	  * @param $id 订单ID
	  */
    function completeOrder_d($id)
    {

    }

    /**
     * 获取采购任务的备注信息
     * @param $equRows 订单物料信息
     */
    function getRemark_d($equRows)
    {
        $taskEquDao = new model_purchase_task_equipment ();
        $taskDao = new model_purchase_task_basic();
        $infoRow = array("instruction" => "", "remark" => "");
        if (is_array($equRows)) {
            $taskId = array();
            foreach ($equRows as $key => $val) {
                if ($val['taskEquId']) {
                    $taskId[] = $this->get_table_fields("oa_purch_task_equ", "id=" . $val['taskEquId'], "basicId");//获取采购任务ID
                }
            }
            if (is_array(array_unique($taskId))) {
                foreach (array_unique($taskId) as $tKey => $tVal) {
                    $taskRow = $taskDao->get_d($tVal);
                    if ($taskRow['instruction'] != "") {//拼接采购说明
                        $infoRow['instruction'] .= "任务编号<" . $taskRow['taskNumb'] . ">:" . $taskRow['instruction'] . "\n";
                    }
                    if ($taskRow['remark'] != "") {//拼接采购备注
                        $infoRow['remark'] .= "任务编号<" . $taskRow['taskNumb'] . ">:" . $taskRow['remark'] . "\n";
                    }
                }
            }
        }
        return $infoRow;
    }

    /**
     * 从询价单生成订单时获取采购任务的备注信息
     * @param $equRows 询价物料信息
     */
    function getTaskRemarkByInquiry_d($equRows)
    {
        $taskEquDao = new model_purchase_task_equipment ();
        $taskDao = new model_purchase_task_basic();
        $infoRow = array("instruction" => "", "remark" => "");
        if (is_array($equRows)) {
            $taskId = array();
            foreach ($equRows as $key => $val) {
                if ($val['takeEquId']) {
                    $taskId[] = $this->get_table_fields("oa_purch_task_equ", "id=" . $val['takeEquId'], "basicId");//获取采购任务ID
                }
            }
            if (is_array(array_unique($taskId))) {
                foreach (array_unique($taskId) as $tKey => $tVal) {
                    $taskRow = $taskDao->get_d($tVal);
                    if ($taskRow['instruction'] != "") {//拼接采购说明
                        $infoRow['instruction'] .= "任务编号<" . $taskRow['taskNumb'] . ">:" . $taskRow['instruction'] . "\n";
                    }
                    if ($taskRow['remark'] != "") {//拼接采购备注
                        $infoRow['remark'] .= "任务编号<" . $taskRow['taskNumb'] . ">:" . $taskRow['remark'] . "\n";
                    }
                }
            }
        }
        return $infoRow;
    }

    /**
     * 由采购任务下推订单时获取采购任务的备注信息
     * @param $equRows 订单物料信息
     */
    function getTaskRemark_d($equRows)
    {
        $taskEquDao = new model_purchase_task_equipment ();
        $taskDao = new model_purchase_task_basic();
        $infoRow = array("instruction" => "", "remark" => "");
        if (is_array($equRows)) {
            $taskId = array();
            foreach ($equRows as $key => $val) {
                if ($val['basicId']) {
                    $taskId[] = $val['basicId'];//获取采购任务ID
                }
            }
            if (is_array(array_unique($taskId))) {
                foreach (array_unique($taskId) as $tKey => $tVal) {
                    $taskRow = $taskDao->get_d($tVal);
                    if ($taskRow['instruction'] != "") {//拼接采购说明
                        $infoRow['instruction'] .= "任务编号<" . $taskRow['taskNumb'] . ">:" . $taskRow['instruction'] . "\n";
                    }
                    if ($taskRow['remark'] != "") {//拼接采购备注
                        $infoRow['remark'] .= "任务编号<" . $taskRow['taskNumb'] . ">:" . $taskRow['remark'] . "\n";
                    }
                }
            }
        }
        return $infoRow;
    }

    //判断订单是否已有物料入库或者订单是否已申请付款
    function  isPayApply_d($id)
    {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        //获取已申请付款金额
        $payapplyMoney = $payablesapplyDao->getApplyMoneyByPur_d($id, 'YFRK-01');
        //获取采购订单物料信息
        $equDao = new model_purchase_contract_equipment();
        $equRows = $equDao->getEqusByContractId($id);
        $isStockNum = true;
        if (is_array($equRows)) {//判断是否有物料已入库
            foreach ($equRows as $key => $val) {
                if ($val['amountIssued'] > 0) {
                    $isStockNum = false;
                    break;
                }
            }
        }
        //判断是否可以满足未付款和未入库的条件
        if ($payapplyMoney == 0 && $isStockNum) {
            return 1;
        } else {
            return 0;
        }
    }

    /**订单
     *
     */
    function dealClose_d($rows)
    {
        $taskDao = new model_purchase_task_basic();
        $taskEquDao = new model_purchase_task_equipment ();
        $contractEquDao = new model_purchase_contract_equipment ();
        $contractEquRows = $contractEquDao->getEqusByContractId($rows['id']);
        //更新采购任务的合同数量
        foreach ($contractEquRows as $key => $val) {
            if (!isset ($val['amountAll'])) {
                $val['amountAll'] = 0;
            }
            if ($val['taskEquId']) {
                $taskequRow = $taskEquDao->get_d($val['taskEquId']);
                //更新采购任务的状态，重新开启采购任务
                $taskDao->startTask_d($taskequRow['basicId']);
                //更新采购任务的合同数量
                $taskEquDao->updateContractAmount($val['taskEquId'], 0, $val['amountAll']);
                //更新采购任务的询价数量
                $taskEquDao->updateAmountIssued($val['taskEquId'], 0, $val['amountAll']);
            }
        }

    }

    /**获取最小希望希望时间
     * @author Administrator
     *
     */
    function getMinHopeDate_d($inquiryIdArr, $suppId)
    {
        $searchArr = array("inquiryIdArr" => $inquiryIdArr, "suppId" => $suppId);
        $this->getParam($_GET);
        $this->asc = false;
        $this->sort = "a.arrivalDate";
        $this->__SET('groupBy', "b.id");
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId('get_suppInfo_minDate');
        return $rows['0']['dateHope'];
    }

    /**获取最小希望希望时间
     * @author Administrator
     *
     */
    function getMinHopeDateByEqu_d($inquiryIdEquArr)
    {
        $searchArr = array("inquiryIdEquArr" => $inquiryIdEquArr);
        $this->getParam($_GET);
        $this->asc = false;
        $this->sort = "a.arrivalDate";
        $this->__SET('groupBy', "b.id");
        $this->__SET('searchArr', $searchArr);
        $rows = $this->listBySqlId('get_suppInfo_minDate');
        return $rows['0']['dateHope'];
    }

    /************************ 采购付款申请可申请验证 ********************/
    /**
     * 验证方法
     */
    function canPayapply_d($id)
    {
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $rs = $payablesapplyDao->isExistence_d($id, 'YFRK-01', 'back');
        if ($rs) {
            return 'hasBack';
        }

//		return $id;

        //获取订单信息
        $obj = $this->find(array('id' => $id), null, 'paymentCondition,allMoney,dateHope');

        //获取已申请金额，如果已申请完成，返回不能继续申请，否则进入付款期限判断
        $payablesapplyDao = new model_finance_payablesapply_payablesapply();
        $payedMoney = $payablesapplyDao->getApplyMoneyByPur_d($id, 'YFRK-01');

        //获取最后一次入库日期
        $stockInDao = new model_stock_instock_stockin();
        $stockInfo = $stockInDao->getRecentlyDate($id);

        if ($payedMoney >= $obj['allMoney']) {
            return 'No';
        }

        $datadictDao = new model_system_datadict_datadict();
        $payConditionInfo = $datadictDao->find(array('dataCode' => $obj['paymentCondition']), null, 'expand1,expand2,expand3');
        if (!empty($payConditionInfo['expand1'])) { //货到付款判断

            //到款日期判断
            if (is_array($stockInfo)) {
                $lastDate = $stockInfo[0]['auditDate'];
            } else {
                return -1;
            }

            $days = (strtotime(day_date) - strtotime($lastDate)) / 86400;
            if ($days < $payConditionInfo['expand1']) {
                return $payConditionInfo['expand1'] - $days;
            } else {
                return 'Yes';
            }
        } else if (!empty($payConditionInfo['expand2']) && !empty($payConditionInfo['expand3'])) { //月结判断

            //到款日期判断
            if (is_array($stockInfo)) {
                $lastDate = $stockInfo[0]['auditDate'];
            } else {
                return -1;
            }
//			echo date('m', strtotime($lastDate));
            $nextYearMonth = date('Y-m', strtotime('+1 month', strtotime($lastDate)));

            $payDate = $nextYearMonth . '-' . $payConditionInfo['expand3'];
            $days = (strtotime($payDate) - strtotime(day_date)) / 86400;
            if ($days > 0) {
                return $days;
            } else {
                return 'Yes';
            }
        } else {
            return 'Yes';
        }

    }

    /**
     * 退款申请验证
     */
    function canPayapplyBack_d($id)
    {

        //获取已付款金额(包含退款)
        $payablesDao = new model_finance_payables_payables();
        $payedMoney = $payablesDao->getPayedMoneyByPur_d($id, 'YFRK-01');
        if ($payedMoney * 1 != 0) {

            $payablesapplyDao = new model_finance_payablesapply_payablesapply();
            $rs = $payablesapplyDao->isExistence_d($id, 'YFRK-01');
            if ($rs) {
                return 'hasBack';
            }
            $payedApplyMoney = $payablesapplyDao->getApplyMoneyByPurAll_d($id);
            if ($payedApplyMoney * 1 != 0) {
                return $payedApplyMoney;
            } else {
                return -1;
            }
        } else {
            return 0;
        }
    }
    /************************ 采购付款申请可申请验证 ********************/
    /**
     * 审批流回调方法
     */
    function workflowCallBack($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo['objId'];
        if (!empty ($objId)) {
            $rows = $this->get_d($objId);
            if ($rows['ExaStatus'] == "完成") {
                //如果审批通过则更新在途数量
                $this->updateOnWayNumb_d($objId);
                //发送邮件通知采购员
//					$this->service->sendEmail_d($rows);

                //发送邮件通知申请人 created by huanghaojin
                $applyBasic = $this->getApplyBasic_d($objId);
                foreach ($applyBasic as $k => $v) {//后来修改 2198
                    $rows['applyBasic'] = $v;
                    $this->sendEmail_d($rows);
                }

                //盖章申请新增部分 create by kuangzw
                if ($rows['isNeedStamp'] == "1") {
                    //创建数组
                    $object = array(
                        "contractId" => $rows['id'],
                        "contractCode" => $rows['hwapplyNumb'],
                        "contractType" => 'HTGZYD-03',
                        "contractMoney" => $rows['allMoney'],
                        "signCompanyName" => $rows['suppName'],
                        "signCompanyId" => $rows['suppId'],
                        "applyUserId" => $rows['sendUserId'],
                        "applyUserName" => $rows['sendName'],
                        "applyDate" => day_date,
                        "stampType" => $rows['stampType'],
                        "status" => 0
                    );
                    $stampDao = new model_contract_stamp_stamp();
                    $stampArr = $stampDao->find(array('contractId' => $rows['id'], 'contractType' => 'HTGZYD-03'), null, 'id');
                    if (empty($stampArr)) {
                        $stampDao->add_d($object, true);
                    }
                }

                //付款申请新增部分 create by kuangzw
                if ($rows['isApplyPay'] == "1") {
                    $this->dealAfterAuditPayapply_d($rows, $spid);
                }
            }
        }
    }

    /**
     * 审批后回调方法- 终止审批
     */
    function workflowCallBack_close($spid)
    {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getWorkflowInfo($spid);
        $objId = $folowInfo['objId'];
        if (!empty ($objId)) {
            $rows = $this->get_d($objId);
            if ($rows['ExaStatus'] == "完成" && $rows['closeType'] == 2) {
                //发送邮件通知采购员
                $this->dealClose_d($rows);
            }
        }
    }
}