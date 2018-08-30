<?php

/**
 * @author huangzf
 * @Date 2012��3��1�� 20:09:22
 * @version 1.0
 * @description:��Ʒ��������(����) Model��
 */
class model_goods_goods_properties extends model_treeNode
{

    function __construct() {
        $this->tbl_name = "oa_goods_properties";
        $this->sql_map = "goods/goods/propertiesSql.php";
        parent::__construct();
    }

    /**
     * ��ϵѡ��ҳ����ʾģ��
     * @param  $rows
     * @return string
     */
    function showItemsAtAss($rows) {
        if ($rows) {
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
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
     * ��װ��ʾ������ʾ(����ѡ��)
     * @param $rows
     * @param $numberGroup 1 ��Ʒ�� ��Ʒ�豸Ĭ������ = Ĭ������ * ��Ʒ��
     * @return string
     */
    function showPropertiesForChoose($rows, $numberGroup = 1) {
        $str = ""; //���ص�ģ���ַ���
        if ($rows) {
            $i = 0; //�б��¼���
            foreach ($rows as $key => $val) {
                $optionStr = "";
                $propertiesName = $val ['propertiesName'];
                if ($val ['propertiesType'] == "0") { //��ѡ
                    if (is_array($val ['items'])) {
                        foreach ($val ['items'] as $key => $proItem) {
                            $checked = ""; //�Ƿ�Ĭ��ѡ������
                            $disabled = ""; //�Ƿ��ѡ
                            $parentIdStr = ""; //����ѡ��id
                            $span_diplay = "none"; //ѡ�����ط�
                            $licenseAbled = "disabled";//disabled
                            $TCAbled = "";//ͣ������ѡ
                            $rel = "rel='?model=goods_goods_properties&action=toViewTip&id=" . $proItem ['id'] . "'";
                            if (is_array($proItem ['assItems'])) {
                                foreach ($proItem ['assItems'] as $key => $value) {
                                    $parentIdStr .= $value ['itemIds'] . "_";
                                }
                            } else {
                                $parentIdStr = "none";
                                $span_diplay = "block";
                            }

                            if ($proItem ['isDefault'] == "on") { //�Ƿ�Ĭ��ѡ��
                                $checked = "checked";
                                $licenseAbled = "";
                            }
                            if ($proItem ['isNeed'] == "on") { //�Ƿ�Ĭ�ϱ�ѡ
                                $disabled = "disabled checked";
                            }
                            if ($proItem ['status'] == "TC") { //ͣ������ѡ
                                $TCAbled = "disabled";
                            }
                            //ʵ����Ⱦ����
                            $actNum = $proItem ['defaultNum'] * $numberGroup;

                            // ѡ�񲿷�
                            $optionStr .= "<span class='option' grouprow='$val[id]' group='$val[orderNum]' id='span_$proItem[id]' parentId='$parentIdStr' $rel style='display:$span_diplay'>" .
                                "<input grouprow='$val[id]' class='tipTrigger' type='radio' id='radio_$proItem[id]' name='$propertiesName' value='$proItem[id]' tip='$proItem[remark]' $disabled $checked onclick='checkedItem(this,$proItem[id],0)' $TCAbled/>";
                            if ($proItem['productId']) { // ���ڹ�������
                                $optionStr .= "&nbsp;<a href='javascript:void(0);' onclick='toViewProductInfo(" . $proItem ['productId'] . ")' >(" . $proItem['productCode'].")".$proItem ['itemContent'] . "</a>";
                            } else {
                                $optionStr .= "&nbsp;" . $proItem ['itemContent'];
                            }

                            if ($val ['existNum'] == "on" && $proItem ['status'] != "TC") { //������������
                                $optionStr .= "&nbsp;&nbsp;����:&nbsp;<input defVal='$actNum' class='num_input' type='text'  grouprow='$val[id]'  parentId='' id='numinput_$proItem[id]' name='$propertiesName' value='$actNum' />";
                            }

                            if ($proItem['remark']) { // ������ϸ��Ϣ
                                $optionStr .= "&nbsp;&nbsp;<a href='javascript:void(0);' onclick='toViewTip(" . $proItem ['id'] . ")' >��ϸ</a>&nbsp;&nbsp;";
                            }
                            // ���license�ж� by chengl 2012-08-14
                            if (!empty($proItem['licenseTypeCode'])) {
                                $licenseTypeCode = $proItem['licenseTypeCode'];
                                $templateId = $proItem['licenseTemplateId'];
                                //��֤���������Ƿ���ڼ�����,0Ϊ��1Ϊ��
                                $encryptionLock = 0;
                                if ($proItem['productId']) {
                                    $productInfoDao = new model_stock_productinfo_productinfo();
                                    $rs = $productInfoDao->find(array('id' => $proItem['productId']), null, 'encryptionLock');
                                    $encryptionLock = $rs['encryptionLock'];
                                }
                                if ($proItem ['status'] != "TC") {//ͣ������ѡʱ����ʾ�������ð�ť
                                    $optionStr .= '&nbsp;<input grouprow="license' . $val['id'] . '" id="license' . $proItem['id'] . '" type="button" class="" ' . $licenseAbled . ' value="��������" onclick="var Id=jQuery(this).attr(\'Id\');var lId=jQuery(this).attr(\'licenseId\');lId=lId?lId:0;var licenseId=showModalDialog(\'?model=yxlicense_license_tempKey&action=toSetLicense&licenseType=' . $licenseTypeCode . '&templateId=' . $templateId . '&licenseId=\'+lId+\'&parentElmId=\'+Id,\'window_\'+Id,\'height=700,width=1000,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no\');" encryptionLock=' . $encryptionLock . '>';
                                }
                            }
                            if ($proItem ['status'] == "TC") {
                                $optionStr .= "&nbsp;&nbsp;<ͣ��>";
                            }
                            $optionStr .= "<br/><br/></span>";
                        }
                    }
                    if ($val ['isInput'] == "on" && $proItem ['status'] != "TC") { //�Ƿ���������
                        $optionStr .= "	����:&nbsp;&nbsp;<input class='other_input' type='text' grouprow='$val[id]' parentId=''   name='$propertiesName' />";
                    }

                }

                if ($val ['propertiesType'] == "1") { //��ѡ
                    if (is_array($val ['items'])) {
                        foreach ($val ['items'] as $key => $proItem) {
                            $checked = ""; //�Ƿ�Ĭ��ѡ������
                            $disabled = ""; //�Ƿ��ѡ
                            $parentIdStr = ""; //����ѡ��id
                            $span_diplay = "none"; //ѡ�����ط�
                            $licenseAbled = "disabled";//disabled
                            $TCAbled = "";//ͣ������ѡ
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
                            if ($proItem ['isNeed'] == "on") { //�Ƿ�Ĭ�ϱ�ѡ
                                $disabled = "disabled checked";
                            }
                            if ($proItem ['status'] == "TC") { //ͣ������ѡ
                                $TCAbled = "disabled";
                            }
                            //ʵ����Ⱦ����
                            $actNum = $proItem ['defaultNum'] * $numberGroup;

                            // ѡ�񲿷�
                            $optionStr .= "<span class='option' grouprow='$val[id]' group='$val[orderNum]' id='span_$proItem[id]' parentId='$parentIdStr' $rel style='display:$span_diplay'>" .
                                "<input class='tipTrigger' type='checkbox' grouprow='group_$val[id]' id='checkbox_$proItem[id]' name='$propertiesName' value='$proItem[id]' $disabled $checked $rel onclick='checkedItem(this,$proItem[id],1)' $TCAbled/>";
                            if ($proItem['productId']) { // ���ڹ�������
                                $optionStr .= "&nbsp;<a href='javascript:void(0);' onclick='toViewProductInfo(" . $proItem ['productId'] . ")' >(" . $proItem['productCode'].")".$proItem ['itemContent'] . "</a>";
                            } else {
                                $optionStr .= "&nbsp;" . $proItem ['itemContent'];
                            }

                            if ($val ['existNum'] == "on" && $proItem ['status'] != "TC") { //������������
                                $optionStr .= "&nbsp;&nbsp;����:&nbsp;<input defVal='$actNum' class='num_input' type='text' grouprow='$val[id]' id='numinput_$proItem[id]' parentId='' name='$propertiesName' value='$actNum'/>";
                            }

                            if ($proItem['remark']) { // ������ϸ��Ϣ
                                $optionStr .= "&nbsp;&nbsp;<a href='javascript:void(0);' onclick='toViewTip(" . $proItem ['id'] . ")' >��ϸ</a>&nbsp;&nbsp;";
                            }

                            // ���license�ж� by chengl 2012-08-14
                            if (!empty($proItem['licenseTypeCode'])) {
                                $licenseTypeCode = $proItem['licenseTypeCode'];
                                $templateId = $proItem['licenseTemplateId'];
                                //��֤���������Ƿ���ڼ�����,0Ϊ��1Ϊ��
                                $encryptionLock = 0;
                                if ($proItem['productId']) {
                                    $productInfoDao = new model_stock_productinfo_productinfo();
                                    $rs = $productInfoDao->find(array('id' => $proItem['productId']), null, 'encryptionLock');
                                    $encryptionLock = $rs['encryptionLock'];
                                }
                                if ($proItem ['status'] != "TC") {//ͣ������ѡʱ����ʾ�������ð�ť
                                    $optionStr .= '&nbsp;&nbsp;<input grouprow="license' . $val['id'] . '" id="license' . $proItem['id'] . '" type="button" class="" ' . $licenseAbled . ' value="��������" onclick="var Id=jQuery(this).attr(\'Id\');var lId=jQuery(this).attr(\'licenseId\');lId=lId?lId:0;var licenseId=window.open(\'?model=yxlicense_license_tempKey&action=toSetLicense&licenseType=' . $licenseTypeCode . '&templateId=' . $templateId . '&licenseId=\'+lId+\'&parentElmId=\'+Id,\'window_\'+Id,\'height=700,width=1000,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no\');" encryptionLock=' . $encryptionLock . '>';
                                }
                            }
                            if ($proItem ['status'] == "TC") {
                                $optionStr .= "&nbsp;&nbsp;<ͣ��>";
                            }
                            $optionStr .= "<br/><br/></span>";
                        }
                    }
                    if ($val ['isInput'] == "on" && $proItem ['status'] != "TC") { //�Ƿ���������
                        $optionStr .= "	����:&nbsp;&nbsp;<input class='other_input' type='text' id='$val[id]' grouprow='$val[id]'  name='$propertiesName' />";
                    }
                }

                if ($val ['propertiesType'] == "2") { //�ı���
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

    /*--------------------------------------------ҵ�����--------------------------------------------*/
    /**
     * �������ڵ�
     * @param array $object
     * @return mixed
     */
    public function add_d($object) {
        try {
            $this->start_d();
            //���������߲������ҽڵ�id
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
     * �޸ı���
     * @param $object
     * @return mixed
     */
    function edit_d($object) {
        //        var_dump($object);die();
        // ����������Ϣ����
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
                // ����������Ը���������������Ӧ��Ҳ�����仯
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

                    // �����Ʒ�������Ϸ�������������������Ϣ
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
     * ͨ��id��ȡ��ϸ��Ϣ
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
     * ͨ����Ʒid��ȡ����������Ϣ
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
     * ��ȡĳ����Ʒĳ�����֮ǰ������
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