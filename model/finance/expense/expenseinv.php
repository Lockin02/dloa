<?php

/**
 * @author Show
 * @Date 2012��9��28�� ������ 11:18:08
 * @version 1.0
 * @description:Ŀ���Ƿ�Ʊ�����ܱ� Model��
 */
class model_finance_expense_expenseinv extends model_base
{

    function __construct() {
        $this->tbl_name = "bill_detail";
        $this->sql_map = "finance/expense/expenseinvSql.php";
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
     */
    function saveDelBatch($objs) {
        if (!is_array($objs)) {
            throw new Exception ("������������飡");
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
     * ���������޸ļ�¼
     */
    function updateById($object) {
        return $this->update(array("ID" => $object ['ID']), $object);
    }

    /**
     * ɾ����Ʊ��Ϣ
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
     * ɾ�����ܱ�ʱ��ձ���Ϣ
     */
    function clearBillNoInfo_d($BillNo) {
        try {
            //����
            $this->update(array('BillNo' => $BillNo), array('BillNo' => ''));

            return true;
        } catch (exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * �����Ч�ķ�Ʊ
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
            //����
            $this->_db->query($sql);

            return true;
        } catch (exception $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    /**
     * ��ȡ��Ӧ��ϸ
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
     * ��Ⱦ��Ӧ��ϸ
     */
    function initInvDetailView_d($BillDetail) {
        $str = '';
        if ($BillDetail) {
            //��ȡ��Ʊ����
            $sql = "select id,name from bill_type";
            $billTypeArr = $this->_db->getArray($sql);

            foreach ($BillDetail as $k => $v) {
                //��������ת��
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
     * ��Ⱦ��Ӧ��ϸ
     */
    function initInvDetailViewEdit_d($BillDetail) {
        $str = '';
        if ($BillDetail) {
            //��ȡ��Ʊ����
            $sql = "select id,name from bill_type";
            $billTypeArr = $this->_db->getArray($sql);

            foreach ($BillDetail as $k => $v) {
                //��������ת��
                $thisCostType = $this->initBillView_d($billTypeArr, $v['BillTypeID']);

                $trClass = count($k) % 2 == 0 ? 'tr_odd' : 'tr_even';
                $str .= <<<EOT
	            	<tr class="$trClass">
		                <td valign="top" class="form_text_right">
							<img src="images/changeedit.gif" id="imgInv$v[BillTypeID]" onclick="changeInv('$v[BillTypeID]','$thisCostType')" title="�޸ķ�Ʊ����"/>
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

    //�鿴��Ʊֵ
    function initBillView_d($object, $thisVal = null) {
        $str = null;
        foreach ($object as $key => $val) {
            if ($thisVal == $val['id']) {
                return $val['name'];
            }
        }
        return null;
    }

    //��Ⱦ��Ʊ��Ϣ
    function initBillOption_d($thisVal) {
        //��ѯģ��С����
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

    //�޸ķ�Ʊ����
    function editBillTypeID_d($billTypeId, $newBillTypeId, $BillNo) {
        try {
            $this->start_d();

            //�޸ķ�Ʊ����
            $this->update(array('BillTypeID' => $billTypeId, 'BillNo' => $BillNo), array('BillTypeID' => $newBillTypeId));

            $this->commit_d();
            return true;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    //��ȡ���÷�Ʊ����
    function getBillType_d() {
        $sql = "select id,name from bill_type where TypeFlag=1 and closeflag='0'";
        return $this->_db->getArray($sql);
    }

    //���ض�Ӧ�ķ�Ʊ����
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

    //�������ʼ����optionѡ��
    function initBillType_d($object, $thisVal = null, $defaultVal = null, $isReplace = 1) {
        $str = null;
        $title = $isReplace ? '�˷���������Ʊ' : '�˷��ò�������Ʊ';
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