<?php
/**
 * @author suxc
 * @Date 2011年5月6日 9:52:24
 * @version 1.0
 * @description:退料通知单信息 Model层
 */
header("Content-type: text/html; charset=gb2312");
class model_purchase_delivered_delivered extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_purchase_delivered";
        $this->sql_map = "purchase/delivered/deliveredSql.php";
        parent::__construct();

        $this->statusDao = new model_common_status ();
        $this->statusDao->status = array(0 => array('statusEName' => 'wait', 'statusCName' => '未执行', 'key' => '0'),
            1 => array('statusEName' => 'end', 'statusCName' => '完成', 'key' => '1'),
            2 => array('statusEName' => 'close', 'statusCName' => '关闭', 'key' => '2'));
        //调用初始化对象关联类
        parent::setObjAss();
    }

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分
    protected $_isSetMyList = 0; # 个人列表单据是否要区分公司,1为区分,0为不区分


    /**
     * 对新增退料单时，产品清单模板
     * $rows  收料物料数组
     */
    function showPurchAppProInfo($rows)
    {
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串


            foreach ($rows as $key => $val) {
                $num = $i + 1;
                $notDeliveredNum = $val['arrivalNum'];
                if ($notDeliveredNum > 0) {
                    $str .= <<<EOT
					<tr align="center" class="TableHeader">
						<td>$num</td>
						<td >
							<input type="text" value="$val[sequence]" readOnly class="readOnlyTxtItem" name="delivered[equipment][$i][productNumb]" >
						</td>
						<td >
							<input type="text"  value="$val[productName]" readOnly class="readOnlyTxtItem" name="delivered[equipment][$i][productName]" />
							<input type="hidden" value="$val[productId]" name="delivered[equipment][$i][productId]" />
						</td>
						<td  >
							<input type="text"  class="readOnlyTxtItem" value="$val[pattem]"  name="delivered[equipment][$i][pattem]"  readOnly >
						</td>
						<td >
							<input type="text"  class="readOnlyTxtItem" value="$val[units]" name="delivered[equipment][$i][units]"   readOnly />
						</td>
						<td >
							<input type="text"  class="readOnlyTxtItem"  value="$val[batchNum]" name="delivered[equipment][$i][batchNum]"  readOnly >
						</td>
						<td>
							<input type="hidden" readOnly  value="$val[price]" name="delivered[equipment][$i][price]" />
							<input type="hidden" readOnly  value="$val[id]" name="delivered[equipment][$i][businessId]" />

							<input type="text" class="txtshort" value="$notDeliveredNum" name="delivered[equipment][$i][deliveredNum]" onblur="checkNum(this);"/>
							<input type="hidden" id="delivered$i"   value="$notDeliveredNum" />
						</td>
						<td>
							<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
						</td>

					</tr>
EOT;
                    $i++;
                }
            }
            return $str;

        }
    }

    /**
     * 添加退料通知单
     *
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            $codeDao = new model_common_codeRule();
            $object ['returnCode'] = $codeDao->purchaseCode("oa_purchase_delivered");
            $object ['state'] = $this->statusDao->statusEtoK('wait');
            $object ['ExaStatus'] = '未提交';
            $id = parent::add_d($object, true);
            //执行退料关联对象新增操作
            $sql = "insert into oa_purchase_delivered_ass(sourceId,sourceNumb,sourceType,returnId,returnNumb) values('" . $object ['sourceId'] . "','" . $object ['sourceCode'] . "','" . $object ['returnType'] . "','" . $id . "','" . $object ['returnCode'] . "')";
            $this->query($sql);

            //对收料单进行反关闭
            //			$arrivalDao=new model_purchase_arrival_arrival();
            //			$arrivalDao->updateArrival($object['sourceId']);

            $equipmentDao = new model_purchase_delivered_equipment ();
            //			$arrivalEupDao=new model_purchase_arrival_equipment();
            foreach ($object ['equipment'] as $key => $equ) {
                //计划数量大于0并且物料名称不为空才进行操作
                if ($equ ['deliveredNum'] > 0 && $equ ['productId'] != "") {
                    $i = isset ($i) ? (++$i) : 1; //判断有多少条可用产品清单
                    $equ ['basicId'] = $id;
                    $equ ['factNum'] = 0;
                    $equ ['stockId'] = $object['stockId'];
                    $equ ['stockName'] = $object['stockName'];
                    $equId = $equipmentDao->add_d($equ);

                    //更新收料物料的入库数量
                    //					$arrivalEupDao->updateNumb_d($object['sourceId'],$equ['productId'],-$equ['deliveredNum']);
                }
            }
            //不存在产品时，抛出异常
            if ($i == 0) {
                throw new Exception ('无可退料物料');
            }
            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 保存固定资产退料通知
     *
     * @param  $object
     */
    function addAsset($addAsset)
    {
        try {
            $this->start_d();
            $codeDao = new model_common_codeRule();
            $addAsset ['returnCode'] = $codeDao->purchaseCode("oa_purchase_delivered");
            $addAsset ['state'] = "2";
            $addAsset ['ExaStatus'] = '完成';
            $id = parent::add_d($addAsset, true);
            $dequipmentDao = new model_purchase_delivered_equipment ();
            $aequipmentDao = new model_purchase_arrival_equipment();
            $emailArr = $addAsset['email'];
            if (is_array($addAsset ['equipment'])) {
                $j = 0;
                $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td width='10%'><b>序号</b></td><td width='15%'><b>物料编号</b></td><td width='40%'><b>物料名称</b></td><td width='15%'><b>退料数量</b></td><td width='20%'><b>收料单号</b></td></tr>";

                foreach ($addAsset ['equipment'] as $key => $equ) {
                    $j++;
                    $equ['basicId'] = $id;
                    $equId = $dequipmentDao->add_d($equ);
                    //更新收料退库数量及收料数量
                    $sql = "update oa_purchase_arrival_equ set deliveredNum=deliveredNum +" . $equ['deliveredNum'] . ",arrivalNum=arrivalNum-" . $equ['deliveredNum'] . " where id='" . $equ['businessId'] . "'";
                    $aequipmentDao->query($sql);
                    $productNumb = $equ['productNumb'];
                    $productName = $equ['productName'];
                    $arrivalNum = $addAsset ['sourceCode'];
                    $deliveredNum = $equ ['deliveredNum'];
                    $addmsg .= <<<EOT
					<tr align="center" >
						<td  width='10%'>$j</td>
						<td width='15%'>$productNumb</td>
						<td  align='left'  width='40%'>$productName</td>
						<td width='15%'>$deliveredNum</td>
						<td  width='20%'>$arrivalNum</td>
					</tr>
EOT;
                }
                $addmsg .= "</table><br><font color=red>备注：</font><br>" . $addAsset['remark'];
            } else {
                throw new Exception("数据不完整!");
            }
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->arrivalEmailWithEqu($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], '资产采购退通知', '该邮件发送人是：<font color=blue><b>' . $_SESSION['USERNAME'] . '</b></font>。退料通知单号为：<font color=red><b>' . $addAsset ['returnCode'] . '</b></font>', '', $emailArr['TO_ID'], $addmsg, 1);


            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 修改退料通知单
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            $id = parent :: edit_d($object, "true");
            $deliveredproDao = new model_purchase_delivered_equipment();

            /*start:修改收料产品清单信息*/
            //删除从表的内容，并添加新内容
            $deleteCondition = array('basicId' => $object['id']);
            $deliveredproDao->delete($deleteCondition);
            foreach ($object ['equipment'] as $key => $equ) {
                //计划数量大于0并且物料名称不为空才进行操作
                if ($equ ['deliveredNum'] > 0 && $equ ['productId'] != "") {
                    $i = isset ($i) ? (++$i) : 1; //判断有多少条可用产品清单
                    $equ ['basicId'] = $object['id'];
                    $equ ['factNum'] = 0;
                    $equId = $deliveredproDao->add_d($equ);
                }
            }
            /*end:修改收料单产品清单信息*/
            $this->commit_d();
            return $id;
        } catch (exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**
     * 获取退料清单信息
     * @param $id 退料通知单ID
     *
     */
    function getEquipment_d($id)
    {
        $equipmentDao = new model_purchase_delivered_equipment ();
        $rows = $equipmentDao->getItemByBasicIdId_d($id);
        return $rows;
    }

    /**
     *根据退料ID，更新退料通知单的状态为“已关闭”
     *
     * @param $id 收料通知单ID
     */
    function updateDelivered($id)
    {
        $state = $this->statusDao->statusEtoK('close');
        $condiction = array('id' => $id);
        //修改状态为"已关闭"
        $updateTag = $this->updateField($condiction, 'state', $state);
        return $updateTag;
    }

    /**
     * 生成入库单时，根据退料单ID获取物料显示模板
     * @param $deliveredId 退料通知单ID
     *
     */
    function getEquList_d($deliveredId)
    {
        $arrivalEquDao = new model_purchase_delivered_equipment ();
        $rows = $arrivalEquDao->getItemByBasicIdId_d($deliveredId);
        // k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $list = $arrivalEquDao->showAddList($rows);
        return $list;
    }

    /**
     * 审核入库时进行更新退料通知单信息
     * @param  $id 退料通知单ID
     * @param  $equId 物料清单ID
     * @param  $productId 物料ID
     * @param  $proNum 退料数量
     */
    function updateInStock($id, $equId, $productId, $proNum, $docDate = day_date)
    {
        try {
            //			$this->start_d();
            $deliveredObj = array("id" => $id, "state" => "2");
            $this->updateById($deliveredObj);
            $deliverEupDao = new model_purchase_delivered_equipment ();
            $equRow = $deliverEupDao->get_d($equId);
            $deliverEupDao->updateNumb_d($id, $equId, -$proNum);

            //对收料单进行反关闭
            $object = $this->get_d($id);
            $arrivalDao = new model_purchase_arrival_arrival();
            $arrivalDao->updateArrival($object['sourceId']);

            //更新收料物料的入库数量
            $arrivalEupDao = new model_purchase_arrival_equipment();
            $arrivalEupDao->updateNumb_d($object['sourceId'], $equRow['businessId'], $proNum);
            //			$arrivalEupDao->updateNumb_d($id,$equId,-$proNum);
            $equRows = $arrivalEupDao->get_d($equRow['businessId']);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], $proNum, false, $docDate); //更新采购订单的物料到货数量
            //			$this->commit_d();
        } catch (exception $e) {
            //			$this->rollBack();
            return null;
        }
    }

    /**
     * 反审核入库时进行更新退料通知单信息
     * @param  $id 退料通知单ID
     * @param  $equId 物料清单ID
     * @param  $productId 物料ID
     * @param  $proNum 退料数量
     */
    function updateInStockCancel($id, $equId, $productId, $proNum, $docDate = day_date)
    {
        try {
            //			$this->start_d();
            $deliveredObj = array("id" => $id, "state" => "0");
            $this->updateById($deliveredObj);
            $deliverEupDao = new model_purchase_delivered_equipment ();
            $equRow = $deliverEupDao->get_d($equId);
            $deliverEupDao->updateNumb_d($id, $equId, $proNum);

            //对收料单进行反关闭
            $object = $this->get_d($id);
            $arrivalDao = new model_purchase_arrival_arrival();
            $arrivalDao->updateArrivalForClose($object['sourceId']);

            //更新收料物料的入库数量
            $arrivalEupDao = new model_purchase_arrival_equipment();
            $arrivalEupDao->updateNumb_d($object['sourceId'], $equRow['businessId'], -$proNum);
            $equRows = $arrivalEupDao->get_d($equRow['businessId']);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], -$proNum, false, $docDate); //更新采购订单的物料到货数量
            //			$this->commit_d();
        } catch (exception $e) {
            //			$this->rollBack();
            return null;
        }
    }

    /**
     * 退料审批通过，更新收料数量
     */
    function updateArrivalNum_d($deliveredId)
    {
        $sourceCode = $this->get_table_fields('oa_purchase_delivered', 'id=' . $deliveredId, 'sourceCode');
        //获取采购订单物料信息
        $equipment = new model_purchase_arrival_equipment();
        $equipmentRows = $equipment->findAll("arrivalCode=" . $sourceCode);
        //获取退料单信息
        $delequipment = new model_purchase_delivered_equipment();
        $delequipmentRows = $delequipment->findAll('basicId=' . $deliveredId);
        //更新采购订单物料信息
        foreach ($delequipmentRows as $key => $val) {
            foreach ($equipmentRows as $k => $v) {
                if ($val['productId'] == $v['productId']) {
                    $equipmentRows[$k]['arrivalNum'] = $equipmentRows[$k]['arrivalNum'] - $delequipmentRows[$key]['deliveredNum'];
                    $equipment->edit_d($equipmentRows[$k]);
                }
            }
        }
    }
}

?>