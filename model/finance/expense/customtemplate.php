<?php

/**
 * @author Show
 * @Date 2012年12月21日 星期五 9:45:04
 * @version 1.0
 * @description:个人费用模板 Model层
 */
class model_finance_expense_customtemplate extends model_base
{

    function __construct() {
        $this->tbl_name = "cost_customtemplate";
        $this->sql_map = "finance/expense/customtemplateSql.php";
        parent:: __construct();
    }

    /**
     * 读取人员模板
     */
    function getModelType_d($userId = null) {
        if (!$userId) {
            $userId = $_SESSION['USER_ID'];
        }
        return $this->find(array('userId' => $userId), 'updateTime desc', 'templateName as modelTypeName,id as modelType');
    }

    /**
     * 读取人员模板
     */
    function getTemplate_d($userId = null) {
        if (!$userId) {
            $userId = $_SESSION['USER_ID'];
        }
        return $this->find(array('userId' => $userId), 'updateTime desc', 'templateName,id as templateId');
    }

    //异步保存
    function ajaxSave_d($object) {
        $object['content'] = util_jsonUtil::iconvUTF2GB($object['content']); //转码
        $object['templateName'] = util_jsonUtil::iconvUTF2GB($object['templateName']); //转码

        $rs = $this->find(array('templateName' => $object['templateName'], 'userId' => $_SESSION['USER_ID']), null, 'id');

        $object['userId'] = $_SESSION['USER_ID'];
        $object['userName'] = $_SESSION['USERNAME'];
        $object['updateTime'] = date('Y-m-d H:i:s');
        if ($rs) {
            $object['id'] = $rs['id'];
            parent::edit_d($object);
            return $object['id'];
        } else {
            return parent::add_d($object);
        }
    }

    /**
     * 根据模板id查询费用项 - 返回列表
     */
    function getTemplateCostType_d($id, $isEsm = null) {
        $obj = $this->get_d($id);
        //获取费用类型
        $costTypeDao = new model_finance_expense_costtype();
        return $costTypeDao->getCostTypeList_d($obj['contentId'], $isEsm);
    }

    /**
     * 获取个人最新模板
     */
    function initTemplate_d($id = null, $isEsm) {
        //模板获取
        if ($id) {
            $condition = array('id' => $id);
        } else {
            $condition = array('userId' => $_SESSION['USER_ID']);
        }
        $obj = $this->find($condition, 'updateTime desc');
        if ($obj) {
            $obj['templateStr'] = $this->initTempAdd_d($obj['contentId'], $isEsm);
            return $obj;
        } else {
            return "";
        }
    }

    /**
     * 实例化模板
     */
    function initTempAdd_d($contentIds, $isEsm) {
        //渲染发票部分内容
        $costTypeDao = new model_finance_expense_costtype();

        //如果是工程类，补贴类费用不能获取
        if ($isEsm) {
            $isSubsidy = " and c.isSubsidy = 0 ";
        }
        //查询模板小类型
        $sql = "select
                    c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
                    c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy
                from
                    cost_type c
                where c.CostTypeID in(" . $contentIds . ") and c.isNew = '1' $isSubsidy order by c.ParentCostTypeID,c.orderNum,c.CostTypeID";
        $costTypeArr = $this->_db->getArray($sql);

        //获取发票类型
        $billTypeArr = $costTypeDao->getBillType_d();
        $str = '';
        foreach ($costTypeArr as $k => $v) {
            $countI = $v['CostTypeID'];
            $trClass = $k % 2 == 0 ? 'tr_odd' : 'tr_even';
            $thisI = $countI . "_0";

            $str .= <<<EOT
                <tr class="$trClass" id="tr$v[CostTypeID]">
                    <td valign="top">
                        <img style="cursor:pointer;" src="images/removeline.png" title="删除费用" onclick="deleteCostType($countI)"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[ParentCostType]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostType]" value="$v[ParentCostType]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][parentCostTypeId]" value="$v[ParentCostTypeID]"/>
                    </td>
                    <td valign="top" class="form_text_right">
                        $v[CostTypeName]
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costType]" id="costType$countI" value="$v[CostTypeName]"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][costTypeId]" id="costTypeId$countI" value="$v[CostTypeID]"/>
                        <input type="hidden" id="defaultInvoice$countI" value="$v[invoiceType]"/>
                        <input type="hidden" id="defaultInvoiceName$countI" value="$v[invoiceTypeName]"/>
                        <input type="hidden" id="isReplace$countI" value="$v[isReplace]"/>
                        <input type="hidden" id="isEqu$countI" value="$v[isEqu]"/>
                        <input type="hidden" id="isSubsidy$countI" value="$v[isSubsidy]"/>
                        <input type="hidden" id="showDays$countI" value="$v[showDays]"/>
                    </td>
                    <td valign="top" class="form_text_right">
EOT;
            //如果需要显示天数，则显示
            if ($v['showDays']) {
                $str .= <<<EOT
                        <span>
                            <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" class="txtshort formatMoney" style="width:60px" onblur="detailSet($countI);countAll();"/>
                            X
                            天数
                            <input type="text" name="esmworklog[esmcostdetail][$countI][days]" class="readOnlyTxtMin" id="days$countI" value="1" readonly="readonly"/>
                        </span>
EOT;
            } else {
                $str .= <<<EOT
                        <input type="text" name="esmworklog[esmcostdetail][$countI][costMoney]" id="costMoney$countI" style="width:146px" class="txtmiddle formatMoney" onblur="detailSet($countI);countAll();"/>
                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][days]" id="days$countI" value="1"/>
EOT;
            }

            // 是否不需要发票
            if ($v['isSubsidy'] == 1) {
                $billArr = $costTypeDao->getBillArr_d($billTypeArr, $v['invoiceType']);
                $str .= <<<EOT
                        </td>
                        <td valign="top" colspan="4" class="innerTd">
                            <table class="form_in_table" id="table_$countI">
                                <tr id="tr_$thisI">
                                    <td width="30%">
                                        <input type="text" id="select_$thisI" style="width:90px" class="readOnlyTxtShort" value="$billArr[name]" readonly="readonly"/>
                                        <input type="hidden" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" value="$billArr[id]"/>
                                    </td>
                                    <td width="25%">
                                        <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort formatMoney" readonly="readonly"/>
                                    </td>
                                    <td width="25%">
                                        <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" class="readOnlyTxtShort" readonly="readonly"/>
                                    </td>
                                    <td width="20%">
                                        <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="alert('该类型不需要录入发票，不能进行新增操作');"/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="top">
                            <textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" class="txtlong"></textarea>
                        </td>
                    </tr>
EOT;
            } else {
                $billTypeStr = $costTypeDao->initBillType_d($billTypeArr, null, $v['invoiceType'], $v['isReplace']);//模板实例化字符串
                $str .= <<<EOT
                        </td>
                        <td valign="top" colspan="4" class="innerTd">
                            <table class="form_in_table" id="table_$countI">
                                <tr id="tr_$thisI">
                                    <td width="30%">
                                        <select id="select_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceTypeId]" style="width:90px"><option value="">请选择发票</option>$billTypeStr</select>
                                    </td>
                                    <td width="25%">
                                        <input type="text" id="invoiceMoney_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceMoney]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="invMoneySet('$thisI');countInvoiceMoney()" class="txtshort formatMoney"/>
                                    </td>
                                    <td width="25%">
                                        <input type="text" id="invoiceNumber_$thisI" name="esmworklog[esmcostdetail][$countI][invoiceDetail][0][invoiceNumber]" costTypeId="$v[CostTypeID]" rowCount="$thisI" onblur="countInvoiceNumber(this)" class="txtshort"/>
                                    </td>
                                    <td width="20%">
                                        <img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice($countI)"/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td valign="top">
                            <textarea name="esmworklog[esmcostdetail][$countI][remark]" id="remark$countI" class="txtlong"></textarea>
                        </td>
                    </tr>
EOT;
            }
        }
        return $str;
    }
}