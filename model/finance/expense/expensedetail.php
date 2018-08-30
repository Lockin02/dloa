<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:07:46
 * @version 1.0
 * @description:报销申请费用明细(部门报销) Model层
 */
class model_finance_expense_expensedetail extends model_base
{

    function __construct() {
        $this->tbl_name = "cost_detail";
        $this->sql_map = "finance/expense/expensedetailSql.php";
        parent:: __construct();
    }

    /**
     * 根据传入的对象数组自动进行新增，修改，删除(主要用于解决主从表中对从表对象的批量操作)
     * 判断规则：
     * 1.如果id为空且isDelTag属性为1（这种情况属于如界面上添加后删除情况,后台啥都不做）
     * 2.如果id为空，则新增
     * 3.如果isDelTag属性为1，则删除
     * 4.否则修改
     * @param Array $objs
     * @return array|bool
     * @throws Exception
     */
    function saveDelBatch($objs) {
        //实例化发票明细
        $expenseinvDao = new model_finance_expense_expenseinv();

        try {
            $returnObjs = array();
            foreach ($objs as $key => $val) {
                $isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
                if ((empty ($val ['ID']) && $isDelTag == 1)) {

                } else if (empty ($val ['ID'])) {
                    //如果新增数据金额为0，干掉
                    if ($val['CostMoney'] == 0) {
                        continue;
                    }
                    $expenseinv = $val['expenseinv'];
                    unset($val['expenseinv']);

                    //先新增费用部分
                    $id = $this->add_d($val);
                    $val ['ID'] = $id;
                    array_push($returnObjs, $val);

                    //再新增发票部分
                    $addArr = array(
                        'BillDetailID' => $id,
                        'BillAssID' => $val['AssID'],
                        'BillNo' => $val['BillNo']
                    );
                    $expenseinv = util_arrayUtil::setArrayFn($addArr, $expenseinv);
                    $expenseinvDao->saveDelBatch($expenseinv);
                } else if ($isDelTag == 1) {
                    //先删除费用
                    $this->deletes($val ['ID']);

                    //再删除发票
                    $expenseinvDao->deleteByDetailId($val['ID']);
                } else {
                    //如果编辑金额为0，干掉
                    if ($val['CostMoney'] == 0) {
                        //先删除费用
                        $this->deletes($val ['ID']);

                        //再删除发票
                        $expenseinvDao->deleteByDetailId($val['ID']);
                    } else {

                        //出去发票信息
                        $expenseinv = $val['expenseinv'];
                        unset($val['expenseinv']);
                        //先编辑费用部分
                        //    					echo "<pre>";
                        //						print_r($val);
                        $this->edit_d($val);
                        array_push($returnObjs, $val);

                        //再编辑发票明细部分
                        $addArr = array(
                            'BillDetailID' => $val['ID'],
                            'BillAssID' => $val['AssID'],
                            'BillNo' => $val['BillNo']
                        );
                        $expenseinv = util_arrayUtil::setArrayFn($addArr, $expenseinv);
                        $expenseinvDao->saveDelBatch($expenseinv);
                    }
                }
            }
            return $returnObjs;
        } catch (Exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * 根据主键修改记录
     * @param $object
     * @return mixed
     */
    function updateById($object) {
        $condition = array("ID" => $object ['ID']);
        return $this->update($condition, $object);
    }

    /**
     * 获取对应明细
     * @param $BillNo
     * @return bool
     */
    function getBillDetail_d($BillNo) {
        $this->searchArr = array(
            'BillNo' => $BillNo
        );
        $this->groupBy = 'c.CostTypeID';
        $this->sort = 'c.MainTypeId asc,c.ID ';
        $this->asc = false;
        return $this->list_d('select_count');
    }

    /**
     * 渲染对应明细
     * @param $BillDetail
     * @return string
     */
    function initBillDetailView_d($BillDetail) {
        if ($BillDetail) {
            //查询模板小类型
            $sql = "select CostTypeID as id,CostTypeName as name from cost_type";
            $costTypeArr = $this->_db->getArray($sql);

            //标志位
            $markArr = array();
            //相同费用计算列
            $countArr = array();
            //相同费用计算
            foreach ($BillDetail as $key => $val) {
                if (isset($countArr[$val['MainTypeId']])) {
                    $countArr[$val['MainTypeId']] = $countArr[$val['MainTypeId']] + 1;
                } else {
                    $countArr[$val['MainTypeId']] = 1;
                }
            }

            $str = "";
            foreach ($BillDetail as $k => $v) {
                //费用类型转换
                $thisCostType = $this->initBillView_d($costTypeArr, $v['CostTypeID']);
                $thisCostType = "<span class='detailCostType{$v['CostTypeID']}'>{$thisCostType}</span>";
                if($v['isAddByAuditor'] == 1){
                    $thisCostType = "<span class=\"removeBn deleteNewCostType\" id=\"deleteCostType_$v[CostTypeID]\" onclick=\"deleteCostType('$v[CostTypeID]','$thisCostType')\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>{$thisCostType}";
                }

                //如果天数大于1，则标注
                $green = $v['days'] > 1 ? "green" : "";
                $thisTitle = $v['days'] > 1 ? "单价:" . $v['CostPrice'] . " X 天数:" . $v['days'] : "";

                if (!in_array($v['MainTypeId'], $markArr)) {
                    $trClass = count($markArr) % 2 == 0 ? 'tr_odd' : 'tr_even';

                    $invSize = $countArr[$v['MainTypeId']];

                    $str .= <<<EOT
		            	<tr class="$trClass">
		                    <td valign="top" class="form_text_right" rowspan="$invSize">
		                        <span id="MainTypeName$k">$v[MainType]</span>
		                    </td>
		                    <td valign="top" class="form_text_right">
		                        $thisCostType
		                    </td>
			                <td style="text-align:right" valign="top">
			                    <span class="formatMoney $green" title="$thisTitle">$v[CostMoney]</span>
			                </td>
		                    <td valign="top" class="form_text_right">
                                <a href="javascript:void(0);" onclick="viewSpecialApply('$v[specialApplyNo]');">$v[specialApplyNo]</a>
		                    </td>
		                    <td valign="top" class="form_text_right">
								$v[Remark]
		                    </td>
			            </tr>
EOT;
                    array_push($markArr, $v['MainTypeId']);
                } else {
                    $str .= <<<EOT
		            	<tr class="$trClass">
		                    <td valign="top" class="form_text_right">
		                        $thisCostType
		                    </td>
			                <td style="text-align:right" valign="top">
			                    <span class="formatMoney $green" title="$thisTitle">$v[CostMoney]</span>
			                </td>
		                    <td valign="top" class="form_text_right">
                                <a href="javascript:void(0);" onclick="viewSpecialApply('$v[specialApplyNo]');">$v[specialApplyNo]</a>
		                    </td>
		                    <td valign="top" class="form_text_right">
								$v[Remark]
		                    </td>
			            </tr>
EOT;
                }
            }
            return $str;
        }
    }

    /**
     * 渲染对应明细
     * @param $BillDetail
     * @return string
     */
    function initBillDetailViewEdit_d($BillDetail) {
        if ($BillDetail) {
            //查询模板小类型
            $sql = "select CostTypeID as id,CostTypeName as name,showDays from cost_type";
            $costTypeArr = $this->_db->getArray($sql);

            //标志位
            $markArr = array();
            //相同费用计算列
            $countArr = array();
            //相同费用计算
            foreach ($BillDetail as $key => $val) {
                if (isset($countArr[$val['MainTypeId']])) {
                    $countArr[$val['MainTypeId']] = $countArr[$val['MainTypeId']] + 1;
                } else {
                    $countArr[$val['MainTypeId']] = 1;
                }
            }

            $str = "";
            foreach ($BillDetail as $k => $v) {
                //费用类型转换
                $thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

                //如果天数大于1，则标注
                $green = $v['days'] > 1 ? "green" : "";
                $thisTitle = $v['days'] > 1 ? "单价:" . $v['CostPrice'] . " X 天数:" . $v['days'] : "";

                if (!in_array($v['MainTypeId'], $markArr)) {
                    $trClass = count($markArr) % 2 == 0 ? 'tr_odd' : 'tr_even';

                    $invSize = $countArr[$v['MainTypeId']];
                    $str .= <<<EOT
		            	<tr class="$trClass">
		                    <td valign="top" class="form_text_right" rowspan="$invSize">
		                        <span id="MainTypeName$k">$v[MainType]</span>
								<input type="hidden" id="MainType$v[MainTypeId]" value="$v[MainTypeId]"/>
		                    </td>
		                    <td valign="top" class="form_text_right">
EOT;
                    //如果showday是1，则暂不允许修改
                    if($v['isAddByAuditor'] != 1){
                        if ($thisCostType[1] == 1) {
                            $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="alert('此类型为按天录入费用，暂不能进行修改')" title="此类型为按天录入费用，暂不能进行修改"/>
EOT;
                        } else {
                            $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="changeDetail('$v[CostTypeID]','$thisCostType[0]','$v[MainTypeId]','$v[MainType]')" title="修改费用类型"/>
EOT;
                        }
                    }else{
                        $str .= <<<EOT
							<span class="removeBn deleteNewCostType" id="deleteCostType_$v[CostTypeID]" onclick="deleteCostType('$v[CostTypeID]','$thisCostType[0]')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
EOT;
                    }

                    $str .= <<<EOT
								<span id="spanDetail$v[CostTypeID]" title="$v[CostTypeID]">$thisCostType[0]</span>
		                    </td>
			                <td style="text-align:right" valign="top">
			                    <span class="formatMoney $green" title="$thisTitle">$v[CostMoney]</span>
			                </td>
                            <td valign="top" class="form_text_right">
                                <a href="javascript:void(0);" onclick="viewSpecialApply('$v[specialApplyNo]');">$v[specialApplyNo]</a>
                            </td>
		                    <td valign="top" class="form_text_right">
								$v[Remark]
		                    </td>
			            </tr>
EOT;
                    array_push($markArr, $v['MainTypeId']);
                } else {
                    $str .= <<<EOT
		            	<tr class="$trClass">
		                    <td valign="top" class="form_text_right">
EOT;
                    //如果showday是1，则暂不允许修改
                    if ($thisCostType[1] == 1) {
                        $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="alert('此类型为按天录入费用，暂不能进行修改')" title="此类型为按天录入费用，暂不能进行修改"/>
EOT;
                    } else {
                        $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="changeDetail('$v[CostTypeID]','$thisCostType[0]','$v[MainTypeId]','$v[MainType]')" title="修改费用类型"/>
EOT;
                    }
                    $str .= <<<EOT
								<span id="spanDetail$v[CostTypeID]" title="$v[CostTypeID]">$thisCostType[0]</span>
		                    </td>
			                <td style="text-align:right" valign="top">
			                    <span class="formatMoney $green" title="$thisTitle">$v[CostMoney]</span>
			                </td>
                            <td valign="top" class="form_text_right">
                                <a href="javascript:void(0);" onclick="viewSpecialApply('$v[specialApplyNo]');">$v[specialApplyNo]</a>
                            </td>
		                    <td valign="top" class="form_text_right">
								$v[Remark]
		                    </td>
			            </tr>
EOT;
                }
            }

            return $str;
        }
    }

    /**
     * 查看发票值
     * @param $object
     * @param null $thisVal
     * @return null
     */
    function initBillView_d($object, $thisVal = null) {
        $str = null;
        foreach ($object as $key => $val) {
            if ($thisVal == $val['id']) {
                return $val['name'];
            }
        }
        return null;
    }

    /**
     * 返回费用类型名称
     * @param $object
     * @param null $thisVal
     * @return array|null
     */
    function initExpenseEdit_d($object, $thisVal = null) {
        $str = null;
        foreach ($object as $key => $val) {
            if ($thisVal == $val['id']) {
                return array($val['name'], $val['showDays']);
            }
        }
        return null;
    }

    /**************** 审批修改发票类型部分 **************/
    /**
     * 渲染发票信息
     * @param $thisVal
     * @return string
     */
    function initCostOption_d($thisVal = '',$specialGroupId = '') {
        //查询模板小类型
        $specialCont = ($specialGroupId != '')? " AND (c.CostTypeID = '{$specialGroupId}' or c.ParentCostTypeID = '{$specialGroupId}') " : "";
        $sql = "select
                c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.CostTypeLeve,
                c.ParentCostType as ParentCostTypeName
            from
                cost_type c
            where (c.isNew = 1 or c.CostTypeID = 1) and c.showDays = 0 {$specialCont} order by c.ParentCostTypeID,c.CostTypeID asc";
        $costTypeArr = $this->_db->getArray($sql);

        //整理模板数据
        $templateArr = array();
        foreach ($costTypeArr as $key => $val) {
            $templateArr[$val['ParentCostTypeID']][$val['CostTypeID']] = $val;
        }

        $this->initCostSel_d($templateArr, $templateArr[1], $thisVal);
        return $this->outCostStr;
    }

    public $outCostStr = "";

    /**
     * @param $templateArr
     * @param $thisArr
     * @param $thisVal
     */
    function initCostSel_d($templateArr, $thisArr, $thisVal) {
        //如果是数组，直接循环
        if (is_array($thisArr)) {
            foreach ($thisArr as $key => $val) {
                $optionVal = str_repeat("&nbsp;", $val['CostTypeLeve'] * 3) . '|--' . $val['CostTypeName'];
                if ($thisVal == $val['CostTypeID']) {
                    $this->outCostStr .= '<option value="' . $val['CostTypeID'] . '" selected="selected" parentId="' .
                        $val['ParentCostTypeID'] . '" parentName="' . $val['ParentCostTypeName'] . '" title="' .
                        $val['CostTypeName'] . '">' . $optionVal . '</option>';
                } else {
                    $this->outCostStr .= '<option value="' . $val['CostTypeID'] . '" parentId="' .
                        $val['ParentCostTypeID'] . '" parentName="' . $val['ParentCostTypeName'] . '" title="' .
                        $val['CostTypeName'] . '">' . $optionVal . '</option>';
                }
                if (isset($templateArr[$key])) {
                    $this->initCostSel_d($templateArr, $templateArr[$key], $thisVal);
                }
            }
        }
    }

    /**
     * 修改发票类型
     * @param $costTypeId
     * @param $newCostTypeId
     * @param $BillNo
     * @param $newMainTypeId
     * @param $newMainType
     * @return bool
     */
    function editCostTypeID_d($costTypeId, $newCostTypeId, $BillNo, $newMainTypeId, $newMainType) {
        try {
            $this->start_d();

            //修改发票类型
            $this->update(
                array('CostTypeID' => $costTypeId, 'BillNo' => $BillNo),
                array('CostTypeID' => $newCostTypeId, 'MainTypeId' => $newMainTypeId, 'MainType' => $newMainType)
            );

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 费用明细调整
     * @param $object
     * @return bool|int
     */
    function editDetail_d($object) {
        try {
            $this->start_d();

            $idArr = explode(',', $object['ID']);
            if (count($idArr) == 1) {
                //更新自己
                $this->update(
                    array('ID' => $object['ID']),
                    array(
                        'CostTypeID' => $object['CostTypeID'],
                        'MainTypeId' => $object['MainType'],
                        'MainType' => $object['MainTypeName'],
                        'CostMoney' => $object['CostMoney'],
                        'Remark' => $object['Remark']
                    )
                );

                //更新发票明细
                $expenseinvDao = new model_finance_expense_expenseinv();
                $appArr = array('BillNo' => $object['BillNo'], 'BillDetailID' => $object['ID'], 'BillAssID' => $object['AssID']);
                $object['expenseinv'] = util_arrayUtil::setArrayFn($appArr, $object['expenseinv']);
                $expenseinvDao->saveDelBatch($object['expenseinv']);
            } else {
                $billMoneyArray = array();
                //更新发票明细
                $expenseinvDao = new model_finance_expense_expenseinv();
                $appArr = array('BillNo' => $object['BillNo'], 'BillAssID' => $object['AssID']);
                $object['expenseinv'] = util_arrayUtil::setArrayFn($appArr, $object['expenseinv']);
                $actExpenseInvArr = $expenseinvDao->saveDelBatch($object['expenseinv']);

                foreach ($actExpenseInvArr as $v) {
                    if (isset($billMoneyArray[$v['BillDetailID']])) {
                        $billMoneyArray[$v['BillDetailID']] = bcadd($v['Amount'], $billMoneyArray[$v['BillDetailID']], 2);
                    } else {
                        $billMoneyArray[$v['BillDetailID']] = $v['Amount'];
                    }
                }

                foreach ($billMoneyArray as $k => $v) {
                    //更新自己
                    $this->update(
                        array('ID' => $k),
                        array(
                            'CostTypeID' => $object['CostTypeID'],
                            'MainTypeId' => $object['MainType'],
                            'MainType' => $object['MainTypeName'],
                            'CostMoney' => $v,
                            'Remark' => $object['Remark']
                        )
                    );
                }
            }

            //更新单据
            $expenseDao = new model_finance_expense_expense();
            $expenseObj = $expenseDao->find(array('BillNo' => $object['BillNo']), null, 'ID');
            $expenseDao->recountExpense_d($expenseObj['ID'], $this, $expenseinvDao);

            //更新分摊明细
            $expensecostshareDao = new model_finance_expense_expensecostshare();
            $expensecostshareDao->editDetail_d($object);
            
            $this->commit_d();
            //如果两个金额不等，则返回不一样的结果
            if ($object['CostMoney'] != $object['orgCostMoney']) {
                return 1;
            } else {
                return 2;
            }
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 新增费用类型
     * @param $object
     */
    function addDetail_d($object) {
        $BillNo = isset($object['BillNo'])? $object['BillNo'] : '';
        $HeadID = isset($object['HeadID'])? $object['HeadID'] : '';
        $AssID = isset($object['AssID'])? $object['AssID'] : '';
        $MainType = isset($object['MainTypeName'])? $object['MainTypeName'] : '';
        $MainTypeId = isset($object['MainType'])? $object['MainType'] : '';
        $CostType = isset($object['CostType'])? $object['CostType'] : '';
        $CostTypeID = isset($object['CostTypeID'])? $object['CostTypeID'] : '';
        $CostMoney = isset($object['CostMoney'])? $object['CostMoney'] : '';
        $Remark = isset($object['Remark'])? $object['Remark'] : '';
        $toTakeOutTypeId = isset($object['toTakeOutID'])? $object['toTakeOutID'] : '';
        $RemarkCostshare = isset($object['RemarkCostshare'])? $object['RemarkCostshare'] : '';

        $datadictDao = new model_system_datadict_datadict ();
        $expenseinvDao = new model_finance_expense_expenseinv();
        $expensecostshareDao = new model_finance_expense_expensecostshare();

        // 新增费用类型
        $costTypeInsertSql = "INSERT INTO cost_detail SET  HeadID = '{$HeadID}',RNo = '1',CostTypeID = '{$CostTypeID}',CostMoney = '{$CostMoney}',days = '1',Remark = '{$Remark}',BillNo = '{$BillNo}',AssID = '{$AssID}',MainType = '{$MainType}',MainTypeId = '{$MainTypeId}',isAddByAuditor = 1,toTakeOutTypeId = '{$toTakeOutTypeId}';";
        if (FALSE != $this->_db->query($costTypeInsertSql)) { // 获取当前新增的ID
            $newcostTypeId = $this->_db->insert_id();
            if ($newcostTypeId) {
                // 新增发票信息
                $expenseinv = isset($object['expenseinv'])? $object['expenseinv'] : array();
                if(!empty($expenseinv) && is_array($expenseinv)){
                    //再新增发票部分
                    $addArr = array(
                        'BillDetailID' =>$newcostTypeId,
                        'BillAssID' =>$AssID,
                        'BillNo' => $BillNo
                    );
                    $expenseinv = util_arrayUtil::setArrayFn($addArr,$expenseinv);
                    $expenseinvDao->saveDelBatch($expenseinv);
                }

                // 新增分摊信息
                $expensecostshare = isset($object['expensecostshare'])? $object['expensecostshare'] : array();
                if(!empty($expensecostshare) && is_array($expensecostshare)){
                    foreach ($expensecostshare as $k => $v){
                        $expensecostshare[$k]['moduleName'] = $datadictDao->getDataNameByCode($v['module']);
                    }
                    $addArr = array(
                        'ID' => '',
                        'BillNo' => $BillNo,
                        'CostType' => $CostType,
                        'CostTypeID' => $CostTypeID,
                        'Remark' => $RemarkCostshare,
                        'MainType' => $MainType,
                        'MainTypeId' => $MainTypeId,
                        'relativeBillDetailId' => $newcostTypeId
                    );
                    $expensecostshare = util_arrayUtil::setArrayFn($addArr, $expensecostshare);
                    //插入分摊明细
                    $expensecostshareDao->add_d($expensecostshare[0]);
                }

                // 更新抵扣费用类型的相关金额【费用信息, 发票信息, 分摊金额】
                $toTakeOutBillDetail = array();
                $toTakeOutExpenseinv = isset($object['toTakeOut_expenseinv'])? $object['toTakeOut_expenseinv'] : array();
                $toTakeOutExpensecostshare = isset($object['toTakeOut_expensecostshare'])? $object['toTakeOut_expensecostshare'] : array();
                if($toTakeOutExpenseinv && is_array($toTakeOutExpenseinv)){
                    foreach ($toTakeOutExpenseinv as $k => $v){
                        $expenseinvDao->update(array('ID' => $v['ID']), array('Amount' => $v['Amount']));
                        if(isset($toTakeOutBillDetail[$v['BillDetailID']])){
                            $toTakeOutBillDetail[$v['BillDetailID']] += $v['Amount'];
                        }else{
                            $toTakeOutBillDetail[$v['BillDetailID']] = $v['Amount'];
                        }
                    }
                }

                if($toTakeOutExpensecostshare && is_array($toTakeOutExpensecostshare)){
                    foreach ($toTakeOutExpensecostshare as $k => $v){
                        $expensecostshareDao->update(array('ID' => $v['ID']), array('CostMoney' => $v['CostMoney']));
                    }
                }

                if(!empty($toTakeOutBillDetail)){
                    foreach ($toTakeOutBillDetail as $k => $v){
                        $this->update(array('ID' => $k), array('CostMoney' => $v));
                    }
                }
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 获取报销单下的特别事项申请号
     * 返回array('no1','no2')
     * @param $BillNo
     * @return array
     */
    function getSpecialApplyNos_d($BillNo) {
        $sql = "select specialApplyNo from cost_detail where BillNo = '$BillNo' and specialApplyNo <> ''
            and specialApplyNo is not null GROUP BY specialApplyNo";
        $rs = $this->_db->getArray($sql);
        if ($rs) {
            $rtArr = array();
            foreach ($rs as $val) {
                array_push($rtArr, $val['specialApplyNo']);
            }
            return $rtArr;
        } else {
            return array();
        }
    }

    /**
     * 获取特别申请的次数
     * 入口 array('no1','no2')
     * 返回 array(array('no1'=>'1'))
     * @param $specialApplyArr
     * @return array
     */
    function getSpecialApplyTimes_d($specialApplyArr) {
        $rtArr = array();
        foreach ($specialApplyArr as $val) {
            $sqlTimes = "select BillNo from cost_detail where specialApplyNo = '$val' group by BillNo";
            $timesRs = $this->_db->getArray($sqlTimes);
            if (empty($timesRs)) {
                $rtArr[$val]['useFormNos'] = '';
                $rtArr[$val]['usedTimes'] = 0;
            } else {
                $rtArr[$val]['usedTimes'] = count($timesRs);
                $BillNoArr = array();
                foreach ($timesRs as $v) {
                    array_push($BillNoArr, $v['BillNo']);
                }
                $rtArr[$val]['useFormNos'] = implode(',', $BillNoArr);
            }
        }
        return $rtArr;
    }
}