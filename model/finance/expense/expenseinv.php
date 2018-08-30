<?php

/**
 * @author Show
 * @Date 2012年9月28日 星期五 11:18:08
 * @version 1.0
 * @description:目测是发票金额汇总表 Model层
 */
class model_finance_expense_expenseinv extends model_base
{

    function __construct() {
        $this->tbl_name = "bill_detail";
        $this->sql_map = "finance/expense/expenseinvSql.php";
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
     */
    function saveDelBatch($objs) {
        if (!is_array($objs)) {
            throw new Exception ("传入对象不是数组！");
        }
        $returnObjs = array();
        foreach ($objs as $key => $val) {
            $val = $this->addCreateInfo($val);
            $isDelTag = isset($val ['isDelTag']) ? $val ['isDelTag'] : NULL;
            if (empty ($val ['ID']) && $isDelTag == 1) {

            } else if (empty ($val ['ID'])) {
                $id = $this->add_d($val);
                $val ['ID'] = $id;
                array_push($returnObjs, $val);
            } else if ($isDelTag == 1) {
                $this->deletes($val ['ID']);
            } else {
                $this->edit_d($val);
                array_push($returnObjs, $val);
            }
        }
        return $returnObjs;
    }

    /**
     * 根据主键修改记录
     */
    function updateById($object) {
        return $this->update(array("ID" => $object ['ID']), $object);
    }

    /**
     * 删除发票信息
     */
    function deleteByDetailId($BillDetailID) {
        try {
            $this->delete(array('BillDetailID' => $BillDetailID));
            return true;
        } catch (exception $e) {
            throw $e;
        }
    }

    /**
     * 删除汇总表时清空表单信息
     */
    function clearBillNoInfo_d($BillNo) {
        try {
            //更新
            $this->update(array('BillNo' => $BillNo), array('BillNo' => ''));

            return true;
        } catch (exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * 清除无效的发票
     */
    function clearInvoice_d($BillNo, $esmcostdetailId, $id) {
        $sql = "delete from bill_detail
			where
				BillNo = '$BillNo'
				and
				BillDetailID = '$id'
				and
				BillTypeID not in (select invoiceTypeId from oa_esm_costdetail c inner join oa_esm_costdetail_invoicedetail d on c.id = d.costDetailId where c.id in ($esmcostdetailId))";
        try {
            //更新
            $this->_db->query($sql);

            return true;
        } catch (exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * 获取对应明细
     */
    function getInvDetail_d($BillNo) {
        $this->searchArr = array(
            'BillNo' => $BillNo,
            'AmountNo' => 0,
            'isSubsidy' => 0
        );
        $this->groupBy = 'c.BillTypeID';
        $this->asc = false;
        $rows = $this->list_d('select_count');
        if ($rows) {
            return $rows;
        } else {
            return false;
        }
    }

    /**
     * 渲染对应明细
     */
    function initInvDetailView_d($BillDetail) {
        $str = '';
        if ($BillDetail) {
            //获取发票类型
            $sql = "select id,name from bill_type";
            $billTypeArr = $this->_db->getArray($sql);

            foreach ($BillDetail as $k => $v) {
                //费用类型转换
                $thisCostType = $this->initBillView_d($billTypeArr, $v['BillTypeID']);

                $trClass = count($k) % 2 == 0 ? 'tr_odd' : 'tr_even';
                $str .= <<<EOT
	            	<tr class="$trClass">
		                <td valign="top" class="form_text_right">
							$thisCostType
		                </td>
	                    <td valign="top" style="text-align:right">
		                    <span class="formatMoney">$v[Amount]</span>
	                    </td>
	                    <td valign="top" style="text-align:right">
		                    $v[invoiceNumber]
	                    </td>
						<td></td>
		            </tr>
EOT;
            }
        }
        return $str;
    }

    /**
     * 渲染对应明细
     */
    function initInvDetailViewEdit_d($BillDetail) {
        $str = '';
        if ($BillDetail) {
            //获取发票类型
            $sql = "select id,name from bill_type";
            $billTypeArr = $this->_db->getArray($sql);

            foreach ($BillDetail as $k => $v) {
                //费用类型转换
                $thisCostType = $this->initBillView_d($billTypeArr, $v['BillTypeID']);

                $trClass = count($k) % 2 == 0 ? 'tr_odd' : 'tr_even';
                $str .= <<<EOT
	            	<tr class="$trClass">
		                <td valign="top" class="form_text_right">
							<img src="images/changeedit.gif" id="imgInv$v[BillTypeID]" onclick="changeInv('$v[BillTypeID]','$thisCostType')" title="修改发票类型"/>
							<span id="spanInv$v[BillTypeID]" title="$v[BillTypeID]">$thisCostType</span>
		                </td>
	                    <td valign="top" style="text-align:right">
		                    <span class="formatMoney">$v[Amount]</span>
	                    </td>
	                    <td valign="top" style="text-align:right" id="detailInvoiceNumber$k">
		                    $v[invoiceNumber]
	                    </td>
						<td></td>
		            </tr>
EOT;
            }
        }
        return $str;
    }

    //查看发票值
    function initBillView_d($object, $thisVal = null) {
        $str = null;
        foreach ($object as $key => $val) {
            if ($thisVal == $val['id']) {
                return $val['name'];
            }
        }
        return null;
    }

    //渲染发票信息
    function initBillOption_d($thisVal) {
        //查询模板小类型
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        $billTypeArr = $this->_db->getArray($sql);

        $str = null;
        foreach ($billTypeArr as $key => $val) {
            if ($thisVal == $val['id']) {
                $str .= '<option value="' . $val['id'] . '" selected="selected">' . $val['name'] . '</option>';
            } else {
                $str .= '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
            }
        }
        return $str;
    }

    //修改发票类型
    function editBillTypeID_d($billTypeId, $newBillTypeId, $BillNo) {
        try {
            $this->start_d();

            //修改发票类型
            $this->update(array('BillTypeID' => $billTypeId, 'BillNo' => $BillNo), array('BillTypeID' => $newBillTypeId));

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    //获取可用发票类型
    function getBillType_d() {
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        return $this->_db->getArray($sql);
    }

    //返回对应的发票类型
    function getBillArr_d($object, $defaultVal = null) {
        if ($defaultVal) {
            $rtArr = array();
            foreach ($object as $key => $val) {
                if ($val['id'] == $defaultVal) {
                    $rtArr = $val;
                    break;
                }
            }
            return $rtArr;
        } else {
            return array(
                'name' => '',
                'id' => ''
            );
        }
    }

    //将数组初始化成option选项
    function initBillType_d($object, $thisVal = null, $defaultVal = null, $isReplace = 1) {
        $str = null;
        $title = $isReplace ? '此费用允许替票' : '此费用不允许替票';
        foreach ($object as $key => $val) {
            if ($thisVal == $val['id']) {
                $str .= '<option value="' . $val['id'] . '" selected="selected" title="' . $title . '">' . $val['name'] . '</option>';
            } elseif ($defaultVal == $val['id']) {
                if ($thisVal) {
                    $str .= '<option value="' . $val['id'] . '" title="' . $title . '">' . $val['name'] . '</option>';
                } else {
                    $str .= '<option value="' . $val['id'] . '" selected="selected" title="' . $title . '">' . $val['name'] . '</option>';
                }
            } else {
                if ($isReplace) {
                    $str .= '<option value="' . $val['id'] . '" title="' . $title . '">' . $val['name'] . '</option>';
                }
            }
        }
        return $str;
    }
}