<?php

/**
 * @author huangzf
 * @Date 2012年3月1日 20:09:22
 * @version 1.0
 * @description:产品属性配置(树形) Model层
 */
class model_goods_goods_properties extends model_treeNode
{

    function __construct() {
        $this->tbl_name = "oa_goods_properties";
        $this->sql_map = "goods/goods/propertiesSql.php";
        parent::__construct();
    }

    /**
     * 关系选项页面显示模板
     * @param  $rows
     * @return string
     */
    function showItemsAtAss($rows) {
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            foreach ($rows as $key => $opItem) {
                $chooseStr = "";
                if ($opItem ['propertiesType'] == "2") continue;
                if (is_array($opItem ['items'])) {
                    foreach ($opItem ['items'] as $key => $value) {
                        $chooseStr .= "&nbsp;&nbsp;<input type='checkbox' tip='$value[itemContent]' id='$value[id]' />" . $value ['itemContent'];
                    }
                }

                $str .= <<<EOT
                    <tr>
                        <td class="form_text_left" id="$opItem[id]_head">
                            $opItem[propertiesName]
                        </td>
                        <td class="form_text_right" style="align:center;height: 30px;" id="$opItem[id]_body" >
                            $chooseStr
                        </td>
                    </tr>
EOT;
                $i++;
            }
            return $str;
        }
    }

    /**
     * 组装显示配置显示(用来选择)
     * @param $rows
     * @param $numberGroup 1 产品组 产品设备默认数量 = 默认数量 * 产品组
     * @return string
     */
    function showPropertiesForChoose($rows, $numberGroup = 1) {
        $str = ""; //返回的模板字符串
        if ($rows) {
            $i = 0; //列表记录序号
            foreach ($rows as $key => $val) {
                $optionStr = "";
                $propertiesName = $val ['propertiesName'];
                if ($val ['propertiesType'] == "0") { //单选
                    if (is_array($val ['items'])) {
                        foreach ($val ['items'] as $key => $proItem) {
                            $checked = ""; //是否默认选中属性
                            $disabled = ""; //是否必选
                            $parentIdStr = ""; //关联选项id
                            $span_diplay = "none"; //选项隐藏否
                            $licenseAbled = "disabled";//disabled
                            $TCAbled = "";//停产不可选
                            $rel = "rel='?model=goods_goods_properties&action=toViewTip&id=" . $proItem ['id'] . "'";
                            if (is_array($proItem ['assItems'])) {
                                foreach ($proItem ['assItems'] as $key => $value) {
                                    $parentIdStr .= $value ['itemIds'] . "_";
                                }
                            } else {
                                $parentIdStr = "none";
                                $span_diplay = "block";
                            }

                            if ($proItem ['isDefault'] == "on") { //是否默认选中
                                $checked = "checked";
                                $licenseAbled = "";
                            }
                            if ($proItem ['isNeed'] == "on") { //是否默认必选
                                $disabled = "disabled checked";
                            }
                            if ($proItem ['status'] == "TC") { //停产不可选
                                $TCAbled = "disabled";
                            }
                            //实际渲染数量
                            $actNum = $proItem ['defaultNum'] * $numberGroup;

                            // 选择部分
                            $optionStr .= "<span class='option' grouprow='$val[id]' group='$val[orderNum]' id='span_$proItem[id]' parentId='$parentIdStr' $rel style='display:$span_diplay'>" .
                                "<input grouprow='$val[id]' class='tipTrigger' type='radio' id='radio_$proItem[id]' name='$propertiesName' value='$proItem[id]' tip='$proItem[remark]' $disabled $checked onclick='checkedItem(this,$proItem[id],0)' $TCAbled/>";
                            if ($proItem['productId']) { // 存在关联物料
                                $optionStr .= "&nbsp;<a href='javascript:void(0);' onclick='toViewProductInfo(" . $proItem ['productId'] . ")' >(" . $proItem['productCode'].")".$proItem ['itemContent'] . "</a>";
                            } else {
                                $optionStr .= "&nbsp;" . $proItem ['itemContent'];
                            }

                            if ($val ['existNum'] == "on" && $proItem ['status'] != "TC") { //允许数量输入
                                $optionStr .= "&nbsp;&nbsp;数量:&nbsp;<input defVal='$actNum' class='num_input' type='text'  grouprow='$val[id]'  parentId='' id='numinput_$proItem[id]' name='$propertiesName' value='$actNum' />";
                            }

                            if ($proItem['remark']) { // 存在详细信息
                                $optionStr .= "&nbsp;&nbsp;<a href='javascript:void(0);' onclick='toViewTip(" . $proItem ['id'] . ")' >详细</a>&nbsp;&nbsp;";
                            }
                            // 添加license判断 by chengl 2012-08-14
                            if (!empty($proItem['licenseTypeCode'])) {
                                $licenseTypeCode = $proItem['licenseTypeCode'];
                                $templateId = $proItem['licenseTemplateId'];
                                //验证关联物料是否存在加密锁,0为否，1为是
                                $encryptionLock = 0;
                                if ($proItem['productId']) {
                                    $productInfoDao = new model_stock_productinfo_productinfo();
                                    $rs = $productInfoDao->find(array('id' => $proItem['productId']), null, 'encryptionLock');
                                    $encryptionLock = $rs['encryptionLock'];
                                }
                                if ($proItem ['status'] != "TC") {//停产不可选时不显示加密配置按钮
                                    $optionStr .= '&nbsp;<input grouprow="license' . $val['id'] . '" id="license' . $proItem['id'] . '" type="button" class="" ' . $licenseAbled . ' value="加密配置" onclick="var Id=jQuery(this).attr(\'Id\');var lId=jQuery(this).attr(\'licenseId\');lId=lId?lId:0;var licenseId=showModalDialog(\'?model=yxlicense_license_tempKey&action=toSetLicense&licenseType=' . $licenseTypeCode . '&templateId=' . $templateId . '&licenseId=\'+lId+\'&parentElmId=\'+Id,\'window_\'+Id,\'height=700,width=1000,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no\');" encryptionLock=' . $encryptionLock . '>';
                                }
                            }
                            if ($proItem ['status'] == "TC") {
                                $optionStr .= "&nbsp;&nbsp;<停产>";
                            }
                            $optionStr .= "<br/><br/></span>";
                        }
                    }
                    if ($val ['isInput'] == "on" && $proItem ['status'] != "TC") { //是否允许输入
                        $optionStr .= "	其他:&nbsp;&nbsp;<input class='other_input' type='text' grouprow='$val[id]' parentId=''   name='$propertiesName' />";
                    }

                }

                if ($val ['propertiesType'] == "1") { //多选
                    if (is_array($val ['items'])) {
                        foreach ($val ['items'] as $key => $proItem) {
                            $checked = ""; //是否默认选中属性
                            $disabled = ""; //是否必选
                            $parentIdStr = ""; //关联选项id
                            $span_diplay = "none"; //选项隐藏否
                            $licenseAbled = "disabled";//disabled
                            $TCAbled = "";//停产不可选
                            $rel = "rel='?model=goods_goods_properties&action=toViewTip&id=" . $proItem ['id'] . "'";
                            if (is_array($proItem ['assItems'])) {
                                foreach ($proItem ['assItems'] as $key => $value) {
                                    $parentIdStr .= $value ['itemIds'] . "_";
                                }
                            } else {
                                $parentIdStr = "none";
                                $span_diplay = "block";
                            }
                            if ($proItem ['isDefault'] == "on") {
                                $checked = "checked";
                                $licenseAbled = "";
                            }
                            if ($proItem ['isNeed'] == "on") { //是否默认必选
                                $disabled = "disabled checked";
                            }
                            if ($proItem ['status'] == "TC") { //停产不可选
                                $TCAbled = "disabled";
                            }
                            //实际渲染数量
                            $actNum = $proItem ['defaultNum'] * $numberGroup;

                            // 选择部分
                            $optionStr .= "<span class='option' grouprow='$val[id]' group='$val[orderNum]' id='span_$proItem[id]' parentId='$parentIdStr' $rel style='display:$span_diplay'>" .
                                "<input class='tipTrigger' type='checkbox' grouprow='group_$val[id]' id='checkbox_$proItem[id]' name='$propertiesName' value='$proItem[id]' $disabled $checked $rel onclick='checkedItem(this,$proItem[id],1)' $TCAbled/>";
                            if ($proItem['productId']) { // 存在关联物料
                                $optionStr .= "&nbsp;<a href='javascript:void(0);' onclick='toViewProductInfo(" . $proItem ['productId'] . ")' >(" . $proItem['productCode'].")".$proItem ['itemContent'] . "</a>";
                            } else {
                                $optionStr .= "&nbsp;" . $proItem ['itemContent'];
                            }

                            if ($val ['existNum'] == "on" && $proItem ['status'] != "TC") { //允许数量输入
                                $optionStr .= "&nbsp;&nbsp;数量:&nbsp;<input defVal='$actNum' class='num_input' type='text' grouprow='$val[id]' id='numinput_$proItem[id]' parentId='' name='$propertiesName' value='$actNum'/>";
                            }

                            if ($proItem['remark']) { // 存在详细信息
                                $optionStr .= "&nbsp;&nbsp;<a href='javascript:void(0);' onclick='toViewTip(" . $proItem ['id'] . ")' >详细</a>&nbsp;&nbsp;";
                            }

                            // 添加license判断 by chengl 2012-08-14
                            if (!empty($proItem['licenseTypeCode'])) {
                                $licenseTypeCode = $proItem['licenseTypeCode'];
                                $templateId = $proItem['licenseTemplateId'];
                                //验证关联物料是否存在加密锁,0为否，1为是
                                $encryptionLock = 0;
                                if ($proItem['productId']) {
                                    $productInfoDao = new model_stock_productinfo_productinfo();
                                    $rs = $productInfoDao->find(array('id' => $proItem['productId']), null, 'encryptionLock');
                                    $encryptionLock = $rs['encryptionLock'];
                                }
                                if ($proItem ['status'] != "TC") {//停产不可选时不显示加密配置按钮
                                    $optionStr .= '&nbsp;&nbsp;<input grouprow="license' . $val['id'] . '" id="license' . $proItem['id'] . '" type="button" class="" ' . $licenseAbled . ' value="加密配置" onclick="var Id=jQuery(this).attr(\'Id\');var lId=jQuery(this).attr(\'licenseId\');lId=lId?lId:0;var licenseId=window.open(\'?model=yxlicense_license_tempKey&action=toSetLicense&licenseType=' . $licenseTypeCode . '&templateId=' . $templateId . '&licenseId=\'+lId+\'&parentElmId=\'+Id,\'window_\'+Id,\'height=700,width=1000,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no\');" encryptionLock=' . $encryptionLock . '>';
                                }
                            }
                            if ($proItem ['status'] == "TC") {
                                $optionStr .= "&nbsp;&nbsp;<停产>";
                            }
                            $optionStr .= "<br/><br/></span>";
                        }
                    }
                    if ($val ['isInput'] == "on" && $proItem ['status'] != "TC") { //是否允许输入
                        $optionStr .= "	其他:&nbsp;&nbsp;<input class='other_input' type='text' id='$val[id]' grouprow='$val[id]'  name='$propertiesName' />";
                    }
                }

                if ($val ['propertiesType'] == "2") { //文本框
                    $optionStr .= "<textarea class='textarea' id='$val[id]'  name='$propertiesName' style='width: 90%;height:150px;' ></textarea>";
                }

                $needMark = "";
                if ($val [isLeast] == "on") {
                    $needMark = "<font color='red'>*</font>";
                }
                $str .= <<<EOT
				<tr isLeast='$val[isLeast]' propertyName='$val[propertiesName]' >
                    <td class="form_text_left">
                        <b>$val[propertiesName]$needMark</b> :
                    </td>
                   <td class="form_text_right">
                        $optionStr
                    </td>
                </tr>
EOT;
                $i++;
            }
        }
        return $str;
    }

    /*--------------------------------------------业务操作--------------------------------------------*/
    /**
     * 保存树节点
     * @param array $object
     * @return mixed
     */
    public function add_d($object) {
        try {
            $this->start_d();
            //调用树工具产生左右节点id
            $object = $this->createNode($object);
            $id = parent::add_d($object, true);
            if (is_array($object ['items'])) {
                $propertiesitemDao = new model_goods_goods_propertiesitem ();
                $itemsArr = array();
                foreach ($object ['items'] as $key => $value) {
                    echo $_SESSION [$value ['rkey']];
                    if (isset ($_SESSION [$value ['rkey']])) {
                        $value ['remark'] = $_SESSION [$value ['rkey']];
                    } else {
                        unset ($value ['remark']);
                    }

                    $value ['mainId'] = $id;
                    array_push($itemsArr, $value);
                }

                $itemsObj = $propertiesitemDao->saveDelBatch($itemsArr);
                $asslistDao = new model_goods_goods_asslist ();
                foreach ($itemsObj as $key => $value) {
                    if (!empty ($value ['assitemIdStr'])) {
                        $assitemIdArr = explode(",", $value ['assitemIdStr']);
                        $assitemTipArr = explode(",", $value ['assitemTipStr']);
                        foreach ($assitemIdArr as $key => $val) {
                            if (!empty ($val)) {
                                $asslistObj = array("itemIds" => $val, "itemNames" => $assitemTipArr [$key], "mainId" => $value ['id'], "goodsId" => $object ['mainId']);
                                $asslistDao->add_d($asslistObj);
                            }
                        }
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

    /**
     * 修改保存
     * @param $object
     * @return mixed
     */
    function edit_d($object) {
        //        var_dump($object);die();
        // 加载物料信息进来
        $productInfoDao = new model_stock_productinfo_productinfo();
        $productInfoArr = $productInfoDao->getRelProduct_d($object['id']);

        try {
            $this->start_d();
            if (empty($object['isLeast'])) {
                $object['isLeast'] = "";
            }
            if (empty($object['isInput'])) {
                $object['isInput'] = "";
            }
            if (empty($object['existNum'])) {
                $object['existNum'] = "";
            }
            if ($productInfoArr) {
                $oldObj = $this->get_d($object['id']);
                // 如果关联属性改名，则物料中相应的也发生变化
                if ($oldObj['propertiesName'] != $object['propertiesName']) {
                    $productInfoDao->updateRelGoodsProName_d(implode(',', array_keys($productInfoArr)), $object['propertiesName']);
                }
                $oldObjItem = array();// formatted item
                foreach ($oldObj['items'] as $v) {
                    $oldObjItem[$v['id']] = $v;
                }
            }
            $editResult = parent::edit_d($object, true);

            if (is_array($object['items'])) {
                $propertiesItemDao = new model_goods_goods_propertiesitem ();

                $resultArr = array();
                foreach ($object['items'] as $key => $value) {
                    if (isset($_SESSION [$value ['rkey']])) {
                        $value['remark'] = $_SESSION[$value['rkey']];
                    } else {
                        unset($value['remark']);
                    }

                    if (empty($value['isNeed'])) {
                        $value['isNeed'] = "";
                    }
                    if (empty($value['isDefault'])) {
                        $value['isDefault'] = "";
                    }
                    $value['mainId'] = $object['id'];
                    array_push($resultArr, $value);

                    // 如果产品配置物料发生变更，则调整物料信息
                    if ($value['id'] && $value['productId'] && isset($oldObjItem) && isset($productInfoArr[$oldObjItem[$value['id']]['productId']])) {
                        if (isset($value['isDelTag']) || $oldObjItem[$value['id']]['productId'] != $value['productId']) {
                            $productInfoDao->clearRelGoodsPro_d($oldObjItem[$value['id']]['productId']);
                        }
                    }
                }

                $itemsObj = $propertiesItemDao->saveDelBatch($resultArr);
                $assListDao = new model_goods_goods_asslist ();
                foreach ($itemsObj as $key => $value) {
                    if (!empty($value['assitemIdStr'])) {
                        $assListDao->delete(array("mainId" => $value ['id']));
                        $assItemIdArr = explode(",", $value['assitemIdStr']);
                        $assItemTipArr = explode(",", $value['assitemTipStr']);
                        foreach ($assItemIdArr as $key => $val) {
                            if (!empty ($val)) {
                                $assListObj = array("itemIds" => $val, "itemNames" => $assItemTipArr[$key],
                                    "mainId" => $value['id'], "goodsId" => $object['mainId']);
                                $assListDao->add_d($assListObj);
                            }
                        }
                    }
                }
            }
            $this->commit_d();
            return $editResult;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 通过id获取详细信息
     * @param  $id
     * @return array
     */
    function get_d($id) {
        $object = parent::get_d($id);
        $propertiesItemDao = new model_goods_goods_propertiesitem ();
        $propertiesItemDao->searchArr ['mainId'] = $id;
        $object ['items'] = $propertiesItemDao->listBySqlId();
        return $object;
    }

    /**
     * 通过产品id获取所有配置信息
     * @param  $goodsId
     * @return array
     */
    function getProByGoodsId($goodsId) {
        $this->sort = "c.orderNum";
        $this->asc = false;
        $this->searchArr = array("mainId" => $goodsId);
        $rows = $this->listBySqlId();
        $propertiesItemDao = new model_goods_goods_propertiesitem ();
        $resultArr = array();
        if (is_array($rows)) {
            foreach ($rows as $key => $value) {
                $value ['items'] = $propertiesItemDao->getProItems($value ['id']);
                array_push($resultArr, $value);
            }
        }
        return $resultArr;
    }

    /**
     * 获取某个商品某个序号之前的属性
     * @param  $goodsId
     * @param  $orderNum
     * @return array
     */
    function getProBeforeOrderNum($goodsId, $orderNum) {
        $this->sort = "c.orderNum";
        $this->asc = false;
        $this->searchArr = array("mainId" => $goodsId, "xyOrderNum" => $orderNum);
        $rows = $this->listBySqlId();
        $propertiesItemDao = new model_goods_goods_propertiesitem ();
        $resultArr = array();
        foreach ($rows as $key => $value) {
            $value ['items'] = $propertiesItemDao->getProItems($value ['id']);
            array_push($resultArr, $value);
        }
        return $resultArr;
    }
}