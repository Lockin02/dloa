<?php

/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:07:46
 * @version 1.0
 * @description:�������������ϸ(���ű���) Model��
 */
class model_finance_expense_expensedetail extends model_base
{

    function __construct() {
        $this->tbl_name = "cost_detail";
        $this->sql_map = "finance/expense/expensedetailSql.php";
        parent:: __construct();
    }

    /**
     * ���ݴ���Ķ��������Զ������������޸ģ�ɾ��(��Ҫ���ڽ�����ӱ��жԴӱ�������������)
     * �жϹ���
     * 1.���idΪ����isDelTag����Ϊ1����������������������Ӻ�ɾ�����,��̨ɶ��������
     * 2.���idΪ�գ�������
     * 3.���isDelTag����Ϊ1����ɾ��
     * 4.�����޸�
     * @param Array $objs
     * @return array|bool
     * @throws Exception
     */
    function saveDelBatch($objs) {
        //ʵ������Ʊ��ϸ
        $expenseinvDao = new model_finance_expense_expenseinv();

        try {
            $returnObjs = array();
            foreach ($objs as $key => $val) {
                $isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
                if ((empty ($val ['ID']) && $isDelTag == 1)) {

                } else if (empty ($val ['ID'])) {
                    //����������ݽ��Ϊ0���ɵ�
                    if ($val['CostMoney'] == 0) {
                        continue;
                    }
                    $expenseinv = $val['expenseinv'];
                    unset($val['expenseinv']);

                    //���������ò���
                    $id = $this->add_d($val);
                    $val ['ID'] = $id;
                    array_push($returnObjs, $val);

                    //��������Ʊ����
                    $addArr = array(
                        'BillDetailID' => $id,
                        'BillAssID' => $val['AssID'],
                        'BillNo' => $val['BillNo']
                    );
                    $expenseinv = util_arrayUtil::setArrayFn($addArr, $expenseinv);
                    $expenseinvDao->saveDelBatch($expenseinv);
                } else if ($isDelTag == 1) {
                    //��ɾ������
                    $this->deletes($val ['ID']);

                    //��ɾ����Ʊ
                    $expenseinvDao->deleteByDetailId($val['ID']);
                } else {
                    //����༭���Ϊ0���ɵ�
                    if ($val['CostMoney'] == 0) {
                        //��ɾ������
                        $this->deletes($val ['ID']);

                        //��ɾ����Ʊ
                        $expenseinvDao->deleteByDetailId($val['ID']);
                    } else {

                        //��ȥ��Ʊ��Ϣ
                        $expenseinv = $val['expenseinv'];
                        unset($val['expenseinv']);
                        //�ȱ༭���ò���
                        //    					echo "<pre>";
                        //						print_r($val);
                        $this->edit_d($val);
                        array_push($returnObjs, $val);

                        //�ٱ༭��Ʊ��ϸ����
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
     * ���������޸ļ�¼
     * @param $object
     * @return mixed
     */
    function updateById($object) {
        $condition = array("ID" => $object ['ID']);
        return $this->update($condition, $object);
    }

    /**
     * ��ȡ��Ӧ��ϸ
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
     * ��Ⱦ��Ӧ��ϸ
     * @param $BillDetail
     * @return string
     */
    function initBillDetailView_d($BillDetail) {
        if ($BillDetail) {
            //��ѯģ��С����
            $sql = "select CostTypeID as id,CostTypeName as name from cost_type";
            $costTypeArr = $this->_db->getArray($sql);

            //��־λ
            $markArr = array();
            //��ͬ���ü�����
            $countArr = array();
            //��ͬ���ü���
            foreach ($BillDetail as $key => $val) {
                if (isset($countArr[$val['MainTypeId']])) {
                    $countArr[$val['MainTypeId']] = $countArr[$val['MainTypeId']] + 1;
                } else {
                    $countArr[$val['MainTypeId']] = 1;
                }
            }

            $str = "";
            foreach ($BillDetail as $k => $v) {
                //��������ת��
                $thisCostType = $this->initBillView_d($costTypeArr, $v['CostTypeID']);
                $thisCostType = "<span class='detailCostType{$v['CostTypeID']}'>{$thisCostType}</span>";
                if($v['isAddByAuditor'] == 1){
                    $thisCostType = "<span class=\"removeBn deleteNewCostType\" id=\"deleteCostType_$v[CostTypeID]\" onclick=\"deleteCostType('$v[CostTypeID]','$thisCostType')\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>{$thisCostType}";
                }

                //�����������1�����ע
                $green = $v['days'] > 1 ? "green" : "";
                $thisTitle = $v['days'] > 1 ? "����:" . $v['CostPrice'] . " X ����:" . $v['days'] : "";

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
     * ��Ⱦ��Ӧ��ϸ
     * @param $BillDetail
     * @return string
     */
    function initBillDetailViewEdit_d($BillDetail) {
        if ($BillDetail) {
            //��ѯģ��С����
            $sql = "select CostTypeID as id,CostTypeName as name,showDays from cost_type";
            $costTypeArr = $this->_db->getArray($sql);

            //��־λ
            $markArr = array();
            //��ͬ���ü�����
            $countArr = array();
            //��ͬ���ü���
            foreach ($BillDetail as $key => $val) {
                if (isset($countArr[$val['MainTypeId']])) {
                    $countArr[$val['MainTypeId']] = $countArr[$val['MainTypeId']] + 1;
                } else {
                    $countArr[$val['MainTypeId']] = 1;
                }
            }

            $str = "";
            foreach ($BillDetail as $k => $v) {
                //��������ת��
                $thisCostType = $this->initExpenseEdit_d($costTypeArr, $v['CostTypeID']);

                //�����������1�����ע
                $green = $v['days'] > 1 ? "green" : "";
                $thisTitle = $v['days'] > 1 ? "����:" . $v['CostPrice'] . " X ����:" . $v['days'] : "";

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
                    //���showday��1�����ݲ������޸�
                    if($v['isAddByAuditor'] != 1){
                        if ($thisCostType[1] == 1) {
                            $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="alert('������Ϊ����¼����ã��ݲ��ܽ����޸�')" title="������Ϊ����¼����ã��ݲ��ܽ����޸�"/>
EOT;
                        } else {
                            $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="changeDetail('$v[CostTypeID]','$thisCostType[0]','$v[MainTypeId]','$v[MainType]')" title="�޸ķ�������"/>
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
                    //���showday��1�����ݲ������޸�
                    if ($thisCostType[1] == 1) {
                        $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="alert('������Ϊ����¼����ã��ݲ��ܽ����޸�')" title="������Ϊ����¼����ã��ݲ��ܽ����޸�"/>
EOT;
                    } else {
                        $str .= <<<EOT
							<img src="images/changeedit.gif" id="imgDetail$v[CostTypeID]" onclick="changeDetail('$v[CostTypeID]','$thisCostType[0]','$v[MainTypeId]','$v[MainType]')" title="�޸ķ�������"/>
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
     * �鿴��Ʊֵ
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
     * ���ط�����������
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

    /**************** �����޸ķ�Ʊ���Ͳ��� **************/
    /**
     * ��Ⱦ��Ʊ��Ϣ
     * @param $thisVal
     * @return string
     */
    function initCostOption_d($thisVal = '',$specialGroupId = '') {
        //��ѯģ��С����
        $specialCont = ($specialGroupId != '')? " AND (c.CostTypeID = '{$specialGroupId}' or c.ParentCostTypeID = '{$specialGroupId}') " : "";
        $sql = "select
                c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.CostTypeLeve,
                c.ParentCostType as ParentCostTypeName
            from
                cost_type c
            where (c.isNew = 1 or c.CostTypeID = 1) and c.showDays = 0 {$specialCont} order by c.ParentCostTypeID,c.CostTypeID asc";
        $costTypeArr = $this->_db->getArray($sql);

        //����ģ������
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
        //��������飬ֱ��ѭ��
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
     * �޸ķ�Ʊ����
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

            //�޸ķ�Ʊ����
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
     * ������ϸ����
     * @param $object
     * @return bool|int
     */
    function editDetail_d($object) {
        try {
            $this->start_d();

            $idArr = explode(',', $object['ID']);
            if (count($idArr) == 1) {
                //�����Լ�
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

                //���·�Ʊ��ϸ
                $expenseinvDao = new model_finance_expense_expenseinv();
                $appArr = array('BillNo' => $object['BillNo'], 'BillDetailID' => $object['ID'], 'BillAssID' => $object['AssID']);
                $object['expenseinv'] = util_arrayUtil::setArrayFn($appArr, $object['expenseinv']);
                $expenseinvDao->saveDelBatch($object['expenseinv']);
            } else {
                $billMoneyArray = array();
                //���·�Ʊ��ϸ
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
                    //�����Լ�
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

            //���µ���
            $expenseDao = new model_finance_expense_expense();
            $expenseObj = $expenseDao->find(array('BillNo' => $object['BillNo']), null, 'ID');
            $expenseDao->recountExpense_d($expenseObj['ID'], $this, $expenseinvDao);

            //���·�̯��ϸ
            $expensecostshareDao = new model_finance_expense_expensecostshare();
            $expensecostshareDao->editDetail_d($object);
            
            $this->commit_d();
            //����������ȣ��򷵻ز�һ���Ľ��
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
     * ������������
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

        // ������������
        $costTypeInsertSql = "INSERT INTO cost_detail SET  HeadID = '{$HeadID}',RNo = '1',CostTypeID = '{$CostTypeID}',CostMoney = '{$CostMoney}',days = '1',Remark = '{$Remark}',BillNo = '{$BillNo}',AssID = '{$AssID}',MainType = '{$MainType}',MainTypeId = '{$MainTypeId}',isAddByAuditor = 1,toTakeOutTypeId = '{$toTakeOutTypeId}';";
        if (FALSE != $this->_db->query($costTypeInsertSql)) { // ��ȡ��ǰ������ID
            $newcostTypeId = $this->_db->insert_id();
            if ($newcostTypeId) {
                // ������Ʊ��Ϣ
                $expenseinv = isset($object['expenseinv'])? $object['expenseinv'] : array();
                if(!empty($expenseinv) && is_array($expenseinv)){
                    //��������Ʊ����
                    $addArr = array(
                        'BillDetailID' =>$newcostTypeId,
                        'BillAssID' =>$AssID,
                        'BillNo' => $BillNo
                    );
                    $expenseinv = util_arrayUtil::setArrayFn($addArr,$expenseinv);
                    $expenseinvDao->saveDelBatch($expenseinv);
                }

                // ������̯��Ϣ
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
                    //�����̯��ϸ
                    $expensecostshareDao->add_d($expensecostshare[0]);
                }

                // ���µֿ۷������͵���ؽ�������Ϣ, ��Ʊ��Ϣ, ��̯��
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
     * ��ȡ�������µ��ر����������
     * ����array('no1','no2')
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
     * ��ȡ�ر�����Ĵ���
     * ��� array('no1','no2')
     * ���� array(array('no1'=>'1'))
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