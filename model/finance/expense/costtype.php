<?php

/**
 * @author Show
 * @Date 2012年11月2日 星期五 11:43:46
 * @version 1.0
 * @description:费用类型表 Model层
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
     * 根据主键修改记录 -- 重写这个方法，根据HeadID更新
     * @param $object
     * @return boolean
     */
    function updateById($object)
    {
        return $this->update(array("CostTypeID" => $object ['CostTypeID']), $object);
    }

    /**
     * 重写edit方法
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
            //判断是否为父级菜单
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
     * 重写get_d
     * @param $id
     * @return array
     */
    function get_d($id)
    {
        return $this->find($condition = array('CostTypeID' => $id));
    }

    /**
     * 根据主键批量删除表记录,根据所传id字符中","符号个数进行单或多条记录的删除
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
     * 递归查找
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
     * 合并处理
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
     * 查询费用信息
     * @param $contentIds
     * @param null $isEsm
     * @return mixed
     */
    function getCostTypeList_d($contentIds, $isEsm = null)
    {
        //如果是工程类，补贴类费用不能获取
        if ($isEsm) {
            $isSubsidy = " AND c.isSubsidy = 0 ";
        }
        //查询模板小类型
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
     * 查询费用子项
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

    /********************* 费用类型选择部分 ***************/
    /**
     * 获取费用表
     * @param $isEsm
     * @param $isAction
     * @return string
     */
    function getCostType_d($isEsm, $isAction = 0)
    {
        $imgLine = 'images/menu/tree_line.gif'; //直线
        $imgMinus = 'images/menu/tree_minus.gif'; //缩减符号
        $imgBlank = 'images/menu/tree_blank.gif'; //分支符号
        $isSubsidy = $isEsm == "1" ? " and isSubsidy = '0' " : ""; // 是否工程使用
        //update chenrf 20130425添加关闭功能
        //查询模板小类型
        $sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy"
            . " from cost_type where CostTypeLeve=1 and isNew = '1' and isClose = 0 $isSubsidy order by orderNum";

        $costTypeArr = $this->_db->getArray($sql);
        //模板实例化字符串
        $str = null;
        if ($costTypeArr) {
            foreach ($costTypeArr as $key => $val) {
                $str .= "<div class='box'><table  class='form_in_table'>";
                //行变色
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
                //update chenrf 20130425添加关闭功能
                //二级数据处理
                $sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,isSubsidy"
                    . " from cost_type where ParentCostTypeID ='" . $val['CostTypeID']
                    . "' and CostTypeLeve=2 and isNew = '1' and isClose = 0 $isSubsidy order by orderNum";

                $costLv2Arr = $this->_db->getArray($sql);
                if ($costLv2Arr) {
                    //记录1级类
                    $lv1Cls = "ct_" . $val['CostTypeID'];
                    foreach ($costLv2Arr as $lv2Key => $lv2Val) {
                        //update chenrf 20130425添加关闭功能
                        //三级数据处理
                        $sql = "select CostTypeID,CostTypeName,showDays,isReplace,isEqu,invoiceType,invoiceTypeName,"
                            . "isSubsidy from cost_type where ParentCostTypeID ='" . $lv2Val['CostTypeID']
                            . "' and CostTypeLeve=3 and isNew = '1' and isClose = 0 $isSubsidy order by orderNum";

                        $costLv3Arr = $this->_db->getArray($sql);
                        //如果有三级数据
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

                        //三级数据
                        if ($costLv3Arr) {
                            //记录1级类
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

    /********************* 发票类型部分 ******************/
    /**
     * 获取发票类型
     */
    function getBillType_d()
    {
        return $this->_db->getArray("select id,name from bill_type where TypeFlag=1 and closeflag=0");
    }

    /**
     * 发票选择渲染那
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
     * 获取当前存在的补贴类费用类型
     * @return array
     */
    function getIsSubsidy_d()
    {
        $rs = $this->findAll(array('isNew' => 1, 'isSubsidy' => '1'), null, 'CostTypeID');
        //返回的补贴费用id
        $rtRs = array();
        if ($rs) {
            foreach ($rs as $val) {
                array_push($rtRs, $val['CostTypeID']);
            }
        }
        return $rtRs;
    }

    /**
     * 报销用发票选项内容渲染
     * @param $object
     * @param null $thisVal
     * @param null $defaultVal
     * @param int $isReplace
     * @return null|string
     */
    function initBillType_d($object, $thisVal = null, $defaultVal = null, $isReplace = 1)
    {
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

    /**
     * 返回对应的发票类型
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
     * 根据费用类型名称获取相应的id和上级id
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
     * 返回预算类型对应的费用信息
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
     * 返回费用类型名和上级信息 - 专门为了兼容旧费用处理
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
     * 根据名称返回id
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