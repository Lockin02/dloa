<?php
/**
 * @author suxc
 * @Date 2011��5��6�� 9:52:24
 * @version 1.0
 * @description:����֪ͨ����Ϣ Model��
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
        $this->statusDao->status = array(0 => array('statusEName' => 'wait', 'statusCName' => 'δִ��', 'key' => '0'),
            1 => array('statusEName' => 'end', 'statusCName' => '���', 'key' => '1'),
            2 => array('statusEName' => 'close', 'statusCName' => '�ر�', 'key' => '2'));
        //���ó�ʼ�����������
        parent::setObjAss();
    }

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
    protected $_isSetMyList = 0; # �����б����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������


    /**
     * ���������ϵ�ʱ����Ʒ�嵥ģ��
     * $rows  ������������
     */
    function showPurchAppProInfo($rows)
    {
        if ($rows) {
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���


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
							<img title="ɾ����" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
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
     * �������֪ͨ��
     *
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            $codeDao = new model_common_codeRule();
            $object ['returnCode'] = $codeDao->purchaseCode("oa_purchase_delivered");
            $object ['state'] = $this->statusDao->statusEtoK('wait');
            $object ['ExaStatus'] = 'δ�ύ';
            $id = parent::add_d($object, true);
            //ִ�����Ϲ���������������
            $sql = "insert into oa_purchase_delivered_ass(sourceId,sourceNumb,sourceType,returnId,returnNumb) values('" . $object ['sourceId'] . "','" . $object ['sourceCode'] . "','" . $object ['returnType'] . "','" . $id . "','" . $object ['returnCode'] . "')";
            $this->query($sql);

            //�����ϵ����з��ر�
            //			$arrivalDao=new model_purchase_arrival_arrival();
            //			$arrivalDao->updateArrival($object['sourceId']);

            $equipmentDao = new model_purchase_delivered_equipment ();
            //			$arrivalEupDao=new model_purchase_arrival_equipment();
            foreach ($object ['equipment'] as $key => $equ) {
                //�ƻ���������0�����������Ʋ�Ϊ�ղŽ��в���
                if ($equ ['deliveredNum'] > 0 && $equ ['productId'] != "") {
                    $i = isset ($i) ? (++$i) : 1; //�ж��ж��������ò�Ʒ�嵥
                    $equ ['basicId'] = $id;
                    $equ ['factNum'] = 0;
                    $equ ['stockId'] = $object['stockId'];
                    $equ ['stockName'] = $object['stockName'];
                    $equId = $equipmentDao->add_d($equ);

                    //�����������ϵ��������
                    //					$arrivalEupDao->updateNumb_d($object['sourceId'],$equ['productId'],-$equ['deliveredNum']);
                }
            }
            //�����ڲ�Ʒʱ���׳��쳣
            if ($i == 0) {
                throw new Exception ('�޿���������');
            }
            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * ����̶��ʲ�����֪ͨ
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
            $addAsset ['ExaStatus'] = '���';
            $id = parent::add_d($addAsset, true);
            $dequipmentDao = new model_purchase_delivered_equipment ();
            $aequipmentDao = new model_purchase_arrival_equipment();
            $emailArr = $addAsset['email'];
            if (is_array($addAsset ['equipment'])) {
                $j = 0;
                $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td width='10%'><b>���</b></td><td width='15%'><b>���ϱ��</b></td><td width='40%'><b>��������</b></td><td width='15%'><b>��������</b></td><td width='20%'><b>���ϵ���</b></td></tr>";

                foreach ($addAsset ['equipment'] as $key => $equ) {
                    $j++;
                    $equ['basicId'] = $id;
                    $equId = $dequipmentDao->add_d($equ);
                    //���������˿���������������
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
                $addmsg .= "</table><br><font color=red>��ע��</font><br>" . $addAsset['remark'];
            } else {
                throw new Exception("���ݲ�����!");
            }
            $emailDao = new model_common_mail();
            $emailInfo = $emailDao->arrivalEmailWithEqu($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], '�ʲ��ɹ���֪ͨ', '���ʼ��������ǣ�<font color=blue><b>' . $_SESSION['USERNAME'] . '</b></font>������֪ͨ����Ϊ��<font color=red><b>' . $addAsset ['returnCode'] . '</b></font>', '', $emailArr['TO_ID'], $addmsg, 1);


            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * �޸�����֪ͨ��
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            $id = parent :: edit_d($object, "true");
            $deliveredproDao = new model_purchase_delivered_equipment();

            /*start:�޸����ϲ�Ʒ�嵥��Ϣ*/
            //ɾ���ӱ�����ݣ������������
            $deleteCondition = array('basicId' => $object['id']);
            $deliveredproDao->delete($deleteCondition);
            foreach ($object ['equipment'] as $key => $equ) {
                //�ƻ���������0�����������Ʋ�Ϊ�ղŽ��в���
                if ($equ ['deliveredNum'] > 0 && $equ ['productId'] != "") {
                    $i = isset ($i) ? (++$i) : 1; //�ж��ж��������ò�Ʒ�嵥
                    $equ ['basicId'] = $object['id'];
                    $equ ['factNum'] = 0;
                    $equId = $deliveredproDao->add_d($equ);
                }
            }
            /*end:�޸����ϵ���Ʒ�嵥��Ϣ*/
            $this->commit_d();
            return $id;
        } catch (exception $e) {
            $this->rollBack();
            return null;
        }

    }

    /**
     * ��ȡ�����嵥��Ϣ
     * @param $id ����֪ͨ��ID
     *
     */
    function getEquipment_d($id)
    {
        $equipmentDao = new model_purchase_delivered_equipment ();
        $rows = $equipmentDao->getItemByBasicIdId_d($id);
        return $rows;
    }

    /**
     *��������ID����������֪ͨ����״̬Ϊ���ѹرա�
     *
     * @param $id ����֪ͨ��ID
     */
    function updateDelivered($id)
    {
        $state = $this->statusDao->statusEtoK('close');
        $condiction = array('id' => $id);
        //�޸�״̬Ϊ"�ѹر�"
        $updateTag = $this->updateField($condiction, 'state', $state);
        return $updateTag;
    }

    /**
     * ������ⵥʱ���������ϵ�ID��ȡ������ʾģ��
     * @param $deliveredId ����֪ͨ��ID
     *
     */
    function getEquList_d($deliveredId)
    {
        $arrivalEquDao = new model_purchase_delivered_equipment ();
        $rows = $arrivalEquDao->getItemByBasicIdId_d($deliveredId);
        // k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $list = $arrivalEquDao->showAddList($rows);
        return $list;
    }

    /**
     * ������ʱ���и�������֪ͨ����Ϣ
     * @param  $id ����֪ͨ��ID
     * @param  $equId �����嵥ID
     * @param  $productId ����ID
     * @param  $proNum ��������
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

            //�����ϵ����з��ر�
            $object = $this->get_d($id);
            $arrivalDao = new model_purchase_arrival_arrival();
            $arrivalDao->updateArrival($object['sourceId']);

            //�����������ϵ��������
            $arrivalEupDao = new model_purchase_arrival_equipment();
            $arrivalEupDao->updateNumb_d($object['sourceId'], $equRow['businessId'], $proNum);
            //			$arrivalEupDao->updateNumb_d($id,$equId,-$proNum);
            $equRows = $arrivalEupDao->get_d($equRow['businessId']);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], $proNum, false, $docDate); //���²ɹ����������ϵ�������
            //			$this->commit_d();
        } catch (exception $e) {
            //			$this->rollBack();
            return null;
        }
    }

    /**
     * ��������ʱ���и�������֪ͨ����Ϣ
     * @param  $id ����֪ͨ��ID
     * @param  $equId �����嵥ID
     * @param  $productId ����ID
     * @param  $proNum ��������
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

            //�����ϵ����з��ر�
            $object = $this->get_d($id);
            $arrivalDao = new model_purchase_arrival_arrival();
            $arrivalDao->updateArrivalForClose($object['sourceId']);

            //�����������ϵ��������
            $arrivalEupDao = new model_purchase_arrival_equipment();
            $arrivalEupDao->updateNumb_d($object['sourceId'], $equRow['businessId'], -$proNum);
            $equRows = $arrivalEupDao->get_d($equRow['businessId']);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], -$proNum, false, $docDate); //���²ɹ����������ϵ�������
            //			$this->commit_d();
        } catch (exception $e) {
            //			$this->rollBack();
            return null;
        }
    }

    /**
     * ��������ͨ����������������
     */
    function updateArrivalNum_d($deliveredId)
    {
        $sourceCode = $this->get_table_fields('oa_purchase_delivered', 'id=' . $deliveredId, 'sourceCode');
        //��ȡ�ɹ�����������Ϣ
        $equipment = new model_purchase_arrival_equipment();
        $equipmentRows = $equipment->findAll("arrivalCode=" . $sourceCode);
        //��ȡ���ϵ���Ϣ
        $delequipment = new model_purchase_delivered_equipment();
        $delequipmentRows = $delequipment->findAll('basicId=' . $deliveredId);
        //���²ɹ�����������Ϣ
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