<?php

/**
 * @author Show
 * @Date 2012��11��2�� ������ 11:43:46
 * @version 1.0
 * @description:�������ͱ� Model��
 */
class model_finance_expense_costtype extends model_base
{

    function __construct()
    {
        $this->tbl_name = "cost_type";
        $this->sql_map = "finance/expense/costtypeSql.php";
        $this->pk_id = "CostTypeID";
        parent:: __construct();
    }

    /**
     * ���������޸ļ�¼ -- ��д�������������HeadID����
     * @param $object
     * @return boolean
     */
    function updateById($object)
    {
        return $this->update(array("CostTypeID" => $object ['CostTypeID']), $object);
    }

    /**
     * ��дedit����
     * chenrf 20130425
     * @param $object
     * @return string
     * @throws $e
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            $childIdArr = $this->findTreeIds_d($object['CostTypeID']);
            $childIdArr = array_filter($childIdArr);
            //�ж��Ƿ�Ϊ�����˵�
            if (!empty($childIdArr)) {
                $isClose = $object['isClose'] == '1' ? 1 : 0;
                $childIdArr = array_keys($childIdArr);
                $childId = '"' . implode('","', $childIdArr) . '"';
                $sql = 'UPDATE cost_type SET isClose=' . $isClose . ' WHERE CostTypeID IN (' . $childId . ')';
                $this->query($sql);
            }
            parent::edit_d($object);
            $this->commit_d();
            return 1;
        } catch (Exception $e) {
            $this->rollBack();
            echo '0';
        }
    }

    /**
     * ��дget_d
     * @param $id
     * @return array
     */
    function get_d($id)
    {
        return $this->find($condition = array('CostTypeID' => $id));
    }

    /**
     * ������������ɾ�����¼,��������id�ַ���","���Ÿ������е��������¼��ɾ��
     * @param $ids
     * @return true
     * @throws $e
     */
    function deletes($ids)
    {
        if (!mysql_query("DELETE FROM " . $this->tbl_name . " WHERE " . $this->pk_id . " IN(" . $ids . ")")) {
            throw new Exception (mysql_error());
        }
        return true;
    }

    /**
     * �ݹ����
     * @param $id
     * @param $idArr
     * @return array
     */
    function findTreeIds_d($id, $idArr = array())
    {
        $rs = $this->findAll(array('ParentCostTypeID' => $id, 'isNew' => 1), null, 'CostTypeID');
        if (is_array($rs)) {
            foreach ($rs as $key => $val) {
                $idArr[$val['CostTypeID']] = 'a';
                $idArr = $this->myMerge_d($idArr, $this->findTreeIds_d($val['CostTypeID'], $idArr));
            }
        }
        return $idArr;
    }

    /**
     * �ϲ�����
     * @param $arr1
     * @param $arr2
     * @return array
     */
    function myMerge_d($arr1, $arr2)
    {
        if (is_array($arr2)) {
            foreach ($arr2 as $key => $val) {
                $arr1[$key] = $val;
            }
        }
        return $arr1;
    }

    /**
     * ��ѯ������Ϣ
     * @param $contentIds
     * @param null $isEsm
     * @return mixed
     */
    function getCostTypeList_d($contentIds, $isEsm = null)
    {
        //����ǹ����࣬��������ò��ܻ�ȡ
        if ($isEsm) {
            $isSubsidy = " AND c.isSubsidy = 0 ";
        }
        //��ѯģ��С����
        $sql = "SELECT
                    c.showDays,c.CostTypeID,c.CostTypeName,c.ParentCostTypeID,c.ParentCostType,c.invoiceType,
                    c.invoiceTypeName,c.isReplace,c.isEqu,c.isSubsidy,c.ParentCostTypeID AS parentId,
                    c.ParentCostType AS parentName,c.CostTypeID AS budgetId,c.CostTypeName AS budgetName
                FROM
                    cost_type c
                WHERE c.isNew = 1 AND c.isClose = 0 AND c.CostTypeID IN(" . $contentIds
            . ") $isSubsidy ORDER BY c.ParentCostTypeID,c.orderNum,c.CostTypeID";
        return $this->_db->getArray($sql);
    }

    /**
     * ��ѯ��������
     * @param string $key
     * @return array
     */
    function getCostTypeLeafList_d($key = 'costTypeId')
    {
        $sql = "SELECT
                    c.CostTypeID AS costTypeId,c.CostTypeName AS costTypeName,
                    c.ParentCostTypeID AS parentId,c.ParentCostType AS parentName
                FROM
                    cost_type c
                WHERE c.isNew = 1 AND c.ParentCostTypeID <> 1 ORDER BY c.ParentCostTypeID,c.orderNum,c.CostTypeID";
        $list = $this->_db->getArray($sql);
        $returnList = array();
        foreach ($list as $v) {
            $returnList[$v[$key]] = $v;
        }
        return $returnList;
    }

    /********************* ��������ѡ�񲿷� ***************/
    /**
     * ��ȡ���ñ�
     * @param $isEsm
     * @param $isAction
     * @return string
     */
    function getCostType_d($isEsm, $isAction = 0)
    {
        $imgLine = 'images/menu/tree_line.gif'; //ֱ��
        $imgMinus = 'images/menu/tree_minus.gif'; //��������
        $imgBlank = 'images/menu/tree_blank.gif'; //��֧����
        $isSubsidy = $isEsm == "1" ? " and isSubsidy = '0' " : ""; // �Ƿ񹤳�ʹ��
        //update chenrf 20130425��ӹرչ���
        //��ѯģ��С����
        $sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy"
            . " from cost_type where CostTypeLeve=1 and isNew = '1' and isClose = 0 $isSubsidy order by orderNum";

        $costTypeArr = $this->_db->getArray($sql);
        //ģ��ʵ�����ַ���
        $str = null;
        if ($costTypeArr) {
            foreach ($costTypeArr as $key => $val) {
                $str .= "<div class='box'><table  class='form_in_table'>";
                //�б�ɫ
                $trClass = 'tr_odd';
                $CostTypeShowAndHide = $isAction ? 'onclick="CostTypeShowAndHide(' . $val['CostTypeID'] . ')"' : '';

                $str .= <<<EOT
                    <tr class="$trClass">
                        <td class="form_text_right" valign="top">
                            <img src="$imgMinus" id="$val[CostTypeID]" $CostTypeShowAndHide/>
                            <font style="font-weight:bold;">$val[CostTypeName]</font>
                        </td>
                    </tr>
EOT;
                //update chenrf 20130425��ӹرչ���
                //�������ݴ���
                $sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy"
                    . " from cost_type where ParentCostTypeID ='" . $val['CostTypeID']
                    . "' and CostTypeLeve=2 and isNew = '1' and isClose = 0 $isSubsidy order by orderNum";

                $costLv2Arr = $this->_db->getArray($sql);
                if ($costLv2Arr) {
                    //��¼1����
                    $lv1Cls = "ct_" . $val['CostTypeID'];
                    foreach ($costLv2Arr as $lv2Key => $lv2Val) {
                        //update chenrf 20130425��ӹرչ���
                        //�������ݴ���
                        $sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,"
                            . "isSubsidy from cost_type where ParentCostTypeID ='" . $lv2Val['CostTypeID']
                            . "' and CostTypeLeve=3 and isNew = '1' and isClose = 0 $isSubsidy order by orderNum";

                        $costLv3Arr = $this->_db->getArray($sql);
                        //�������������
                        if ($costLv3Arr) {
                            $treeImg = $imgMinus;
                            $chkHtml = "";
                            $secLine = '<img src="' . $imgLine . '"/>';
                        } else {
                            $secLine = '';
                            $treeImg = $imgBlank;
                            $setCustomCostType = $isAction ? 'onclick="setCustomCostType(' . $lv2Val['CostTypeID'] . ',this)"' : '';
                            $chkHtml = <<<EOT
                                <input type="checkbox"
                                    id="chk$lv2Val[CostTypeID]"
                                    value="$lv2Val[CostTypeID]"
                                    name="$lv2Val[CostTypeName]"
                                    parentId="$val[CostTypeID]"
                                    parentName="$val[CostTypeName]"
                                    showDays="$lv2Val[showDays]"
                                    isReplace="$lv2Val[isReplace]"
                                    isEqu="$lv2Val[isEqu]"
                                    invoiceType="$lv2Val[invoiceType]"
                                    invoiceTypeName="$lv2Val[invoiceTypeName]"
                                    isSubsidy="$lv2Val[isSubsidy]"
                                    $setCustomCostType
                                />
EOT;
                        }
                        $CostType2View = $isAction ? 'onclick="CostType2View(' . $lv2Val['CostTypeID'] . ',this)"' : '';
                        $str .= <<<EOT
                            <tr class="$trClass $lv1Cls" isView="1">
                                <td class="form_text_right" valign="top">
                                    $secLine
                                    <img src="$treeImg" id="$lv2Val[CostTypeID]" $CostType2View/>
                                    $chkHtml
                                    <span id="view$lv2Val[CostTypeID]">$lv2Val[CostTypeName]</span>
                                </td>
                            </tr>
EOT;

                        //��������
                        if ($costLv3Arr) {
                            //��¼1����
                            $lv2Cls = "ct_" . $lv2Val['CostTypeID'];
                            foreach ($costLv3Arr as $lv3Key => $lv3Val) {
                                $CostType2View = $isAction ? 'onclick="setCustomCostType(' . $lv3Val['CostTypeID'] . ',this)"' : '';
                                $str .= <<<EOT
                                    <tr class="$trClass $lv1Cls $lv2Cls" isView="1">
                                        <td class="form_text_right" valign="top">
                                            <img src="$imgLine"/>
                                            <img src="$imgBlank"/>
                                            <input type="checkbox"
                                                id="chk$lv3Val[CostTypeID]"
                                                value="$lv3Val[CostTypeID]"
                                                name="$lv3Val[CostTypeName]"
                                                parentId="$lv2Val[CostTypeID]"
                                                parentName="$lv2Val[CostTypeName]"
                                                showDays="$lv3Val[showDays]"
                                                isReplace="$lv3Val[isReplace]"
                                                isEqu="$lv3Val[isEqu]"
                                                invoiceType="$lv3Val[invoiceType]"
                                                invoiceTypeName="$lv3Val[invoiceTypeName]"
                                                isSubsidy="$lv3Val[isSubsidy]"
                                                $CostType2View
                                            />
                                            <span id="view$lv3Val[CostTypeID]">$lv3Val[CostTypeName]</span>
                                        </td>
                                    </tr>
EOT;
                            }
                        }
                    }
                }
                $str .= "</table></div>";
            }
        }
        return $str . "<input type='hidden' id='costTypeSelectedHidden'/>";
    }

    /********************* ��Ʊ���Ͳ��� ******************/
    /**
     * ��ȡ��Ʊ����
     */
    function getBillType_d()
    {
        return $this->_db->getArray("select id,name from bill_type where TypeFlag=1 and closeflag=0");
    }

    /**
     * ��Ʊѡ����Ⱦ��
     * @param $billTypeArr
     * @param $thisVal
     * @return string
     */
    function initBillTypeClear_d($billTypeArr, $thisVal = null)
    {
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

    /**
     * ��ȡ��ǰ���ڵĲ������������
     * @return array
     */
    function getIsSubsidy_d()
    {
        $rs = $this->findAll(array('isNew' => 1, 'isSubsidy' => '1'), null, 'CostTypeID');
        //���صĲ�������id
        $rtRs = array();
        if ($rs) {
            foreach ($rs as $val) {
                array_push($rtRs, $val['CostTypeID']);
            }
        }
        return $rtRs;
    }

    /**
     * �����÷�Ʊѡ��������Ⱦ
     * @param $object
     * @param null $thisVal
     * @param null $defaultVal
     * @param int $isReplace
     * @return null|string
     */
    function initBillType_d($object, $thisVal = null, $defaultVal = null, $isReplace = 1)
    {
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

    /**
     * ���ض�Ӧ�ķ�Ʊ����
     * @param $object
     * @param null $defaultVal
     * @return array
     */
    function getBillArr_d($object, $defaultVal = null)
    {
        if ($defaultVal) {
            $rtArr = array();
            foreach ($object as $val) {
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

    /**
     * ���ݷ����������ƻ�ȡ��Ӧ��id���ϼ�id
     * @param $CostTypeName
     * @return mixed
     */
    function getIdAndParentIdByName($CostTypeName)
    {
        $sql = "
    		SELECT
				CostTypeID,ParentCostTypeID
			FROM " . $this->tbl_name . "
			WHERE
				CostTypeName = '" . $CostTypeName . "'
			AND isNew = 1";
        return $this->_db->get_one($sql);
    }

    /**
     * ����Ԥ�����Ͷ�Ӧ�ķ�����Ϣ
     * @param $budgetType
     * @return mixed
     */
    function getCostTypeByBudgetType_d($budgetType)
    {
        $sql = "
    		SELECT
				CostTypeID AS costTypeId, CostTypeName AS costTypeName,
				ParentCostTypeID AS parentTypeId, ParentCostType AS parentTypeName
			FROM " . $this->tbl_name . "
			WHERE
				budgetType = '" . $budgetType . "'
			AND isNew = 1";
        return $this->_db->get_one($sql);
    }

    /**
     * ���ط������������ϼ���Ϣ - ר��Ϊ�˼��ݾɷ��ô���
     * @param $costTypeId
     * @return mixed
     */
    function get2_d($costTypeId)
    {
        $sql = "SELECT 
		        t1.CostTypeID AS costTypeId, t1.CostTypeName AS costTypeName,
				t1.ParentCostTypeID AS parentTypeId, t1.CostTypeName AS parentTypeName
            FROM cost_type t1 LEFT JOIN cost_type t2 ON t1.ParentCostTypeID = t2.CostTypeID
            WHERE t1.CostTypeID = '" . $costTypeId . "'";
        return $this->_db->get_one($sql);
    }

    /**
     * �������Ʒ���id
     * @param $names
     * @return string
     */
    function nameToId_d($names)
    {
        $sql = "
    		SELECT
				CostTypeID
			FROM " . $this->tbl_name . "
			WHERE
				CostTypeName IN(" . util_jsonUtil::strBuild($names) . ")";
        $data = $this->_db->getArray($sql);

        $rst = array();
        foreach ($data as $v) {
            $rst[] = $v['CostTypeID'];
        }
        return implode(',', $rst);
    }
}