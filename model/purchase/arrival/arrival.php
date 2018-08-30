<?php
/**
 * @author Administrator
 * @Date 2011��5��4�� 19:49:09
 * @version 1.0
 * @description:����֪ͨ����Ϣ Model��
 */
header("Content-type: text/html; charset=gb2312");

class model_purchase_arrival_arrival extends model_base
{

    function __construct()
    {
        include(WEB_TOR . "model/common/mailConfig.php");
        $this->tbl_name = "oa_purchase_arrival_info";
        $this->sql_map = "purchase/arrival/arrivalSql.php";
        $this->assetMailArr = isset($mailUser[$this->tbl_name . "_asset"]) ?
            $mailUser[$this->tbl_name . "_asset"] : array();
        $this->mailArr = $mailUser[$this->tbl_name];
        parent::__construct();
        $this->arrivalTypeArr = array( //��������ҵ����������
            "ARRIVALTYPE1" => array(
                "mainmodel" => "model_purchase_contract_purchasecontract",
                "itemmodel" => "model_purchase_contract_equipment",
                "updatefun" => "updateAmountIssued", //�������ҵ��
                "getcomfun" => "getUnArrivalEqusByContractId" //��ȡδ������Ϣ
            )
        );
        $this->demandTypeArr = array( //������������
            "order" => array(
                "name" => "���۶���", //����
                "objassmodel" => "projectmanagent_order_order", //����ҵ�񵥾�model
                //"objassmodel"=>"contract_sales_sales",//����ҵ�񵥾�model
                "readfun" => "init&perm=view" //�鿴����
                //"readfun"=>"readDetailedInfoNoedit"//�鿴����
            ),
            "stock" => array(
                "name" => "����ƻ�",
                "objassmodel" => "stock_fillup_fillup",
                "readfun" => "init"
            )
        );
        $this->statusDao = new model_common_status ();
        $this->statusDao->status = array(
            0 => array(
                'statusEName' => 'wait',
                'statusCName' => 'δִ��',
                'key' => '0'
            ),
            1 => array(
                'statusEName' => 'execute',
                'statusCName' => 'ִ����',
                'key' => '1'
            ),
            2 => array(
                'statusEName' => 'close',
                'statusCName' => '�ѹر�',
                'key' => '2'
            ),
            3 => array(
                'statusEName' => 'delivered',
                'statusCName' => '�����˻�',
                'key' => '3'
            ),
            4 => array(
                'statusEName' => 'part',
                'statusCName' => '����ִ��',
                'key' => '4'
            )
        );
        //���ó�ʼ�����������
        parent::setObjAss();
    }

    //��˾Ȩ�޴��� TODO
    protected $_isSetCompany = 1; # �����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������
    protected $_isSetMyList = 0; # �����б����Ƿ�Ҫ���ֹ�˾,1Ϊ����,0Ϊ������


    /**
     * �Բɹ���ͬ����������ʱ����Ʒ�嵥ģ��
     * @param $rows ��Ʒ�嵥����
     */
    function showPurchAppProInfo($rows)
    {
        if ($rows) {
            $datadictDao = new model_system_datadict_datadict();
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
            $rowNum = 1; //һ����Ʒ��Ӧ�Ĳɹ����뵥��Ʒ�嵥����
            $arrivalDate = date("Y-m-d");
            $month = date("n");
            $datadictArr = $this->getDatadicts("ZJFS");

            $arrivalEquDao = new model_purchase_arrival_equipment();
            foreach ($rows as $key => $val) {
                $arrivalNum = 0;
                $arrivalEquRows = $arrivalEquDao->getItemByContractEquId_d($val['id']);
                if (is_array($arrivalEquRows)) { //��ȡĳ���ϵ��������
                    foreach ($arrivalEquRows as $arrKey => $arrVal) {
                        $arrivalNum = $arrivalNum + $arrVal['arrivalNum'];
                    }
                }
                $number = $val['amountAll'] - $arrivalNum;
                if ($number > 0) {
                    ++$i;
                    if ($val['productId']) {
                        $proCheckType = $this->get_table_fields('oa_stock_product_info', 'id=' . $val['productId'], 'checkType'); //�ʼ췽ʽ
                        $checkTypeName = $datadictDao->getDataNameByCode($proCheckType);
                    } else {
                        $proCheckType = "";
                        $checkTypeName = "";
                    }
                    $checkTypeStr = $this->getDatadictsStr($datadictArr ['ZJFS'], $proCheckType);
                    $str .= <<<EOT
						<tr align="center" class="TableHeader">
							<td>$i</td>
							<td rowspan=$rowNum >
								<input type="text"  class="readOnlyTxtItem" value="$val[productNumb]" readOnly  name="arrival[equipment][$i][sequence]" >
							</td>
							<td rowspan=$rowNum >
								<input type="text" value="$val[productName]" readOnly  class="readOnlyTxtItem" name="arrival[equipment][$i][productName]" />
								<input type="hidden" value="$val[productId]" name="arrival[equipment][$i][productId]" />
							</td>
							<td rowspan=$rowNum >
								<input type="text"  class="readOnlyTxtItem" value="$val[pattem]"  name="arrival[equipment][$i][pattem]" readOnly >
							</td>
							<td rowspan=$rowNum >
								<input type="text" id=""   class="readOnlyTxtMin" value="$val[units]" name="arrival[equipment][$i][units]"  readOnly/>
							</td>
							<td rowspan=$rowNum >
								<input type="hidden" value="$val[qualityCode]" name="arrival[equipment][$i][qualityCode]" />
								<input type="text" class="readOnlyTxtShort"  value="$val[qualityName]" name="arrival[equipment][$i][qualityName]" readonly/>
							</td>
							<td rowspan=$rowNum >
								<input type="text" id=""   class="readOnlyTxtShort" value="$checkTypeName" name=""  readOnly/>
								<input type="hidden" id=""   class="readOnlyTxtShort" value="$proCheckType" name="arrival[equipment][$i][checkType]"  readOnly/>
							</td>
							<td rowspan=$rowNum >
								<input type="text"  class="txtshort" value="$val[batchNumb]" name="arrival[equipment][$i][batchNum]" >
							</td>
							<td>
								<input type="hidden" readOnly  value="$val[price]" name="arrival[equipment][$i][price]" />
								<input type="hidden" readOnly  value="$val[id]" name="arrival[equipment][$i][contractId]" />
								<input type="hidden" readOnly class="readOnlyTxt" name="arrival[equipment][$i][purchType]" >

								<input type="text"  class="txtshort"  value="$number" name="arrival[equipment][$i][arrivalNum]"  onblur="checkSize($number,this);checkNum(this);"/>
								<input type="hidden"   value="$number" />
							</td>
							<td>
								<!--<input type="text"  class="txtshort"  value="$month"   name="arrival[equipment][$i][month]" >-->
								<select id="month$i" class="txtshort" name="arrival[equipment][$i][month]">
									<SCRIPT language=JavaScript>
										for(i=1;i<13;i++){
											jQuery("#month$i").append('<option id="month$i'+i+'">'+i+'</option>');
											if(i==$month){jQuery("#month$i"+i).attr("SELECTED","SELECTED");}
										}
									</SCRIPT>
								</select>
							</td>
							<td>
								<input type="text" class="txtshort"  onfocus="WdatePicker()" value="$arrivalDate"  name="arrival[equipment][$i][arrivalDate]" />
							</td>
							<!--
							<td>

								<select id="checkType$i" class="txtshort" name="arrival[equipment][$i][checkType]">$checkTypeStr</select>
							</td>
							-->
							<td>
								<img title="ɾ����" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
							</td>

						</tr>
EOT;
                }
            }
            return $str;

        }
    }

    /**
     * �Բɹ���ͬ����������ʱ����Ʒ�嵥ģ�壨����������
     * @param $rows ��Ʒ�嵥����
     */
    function addPurchProInfo($rows)
    {
        if ($rows) {
            $i = 0; //�б��¼���
            $str = ""; //���ص�ģ���ַ���
            $rowNum = 1; //һ����Ʒ��Ӧ�Ĳɹ����뵥��Ʒ�嵥����
            $arrivalDate = date("Y-m-d");
            $month = date("n");
            $datadictArr = $this->getDatadicts("ZJFS");
            $arrivalEquDao = new model_purchase_arrival_equipment();
            foreach ($rows as $val) {
                $arrivalNum = 0;
                $arrivalEquRows = $arrivalEquDao->getItemByContractEquId_d($val['id']);
                if (is_array($arrivalEquRows)) { //��ȡĳ���ϵ��������
                    foreach ($arrivalEquRows as $arrVal) {
                        $arrivalNum = $arrivalNum + $arrVal['arrivalNum'];
                    }
                }
                $number = $val['amountAll'] - $arrivalNum;
                if ($number > 0) {
                    ++$i;
                    if ($val['productId']) {
                        $proCheckType = $this->get_table_fields('oa_stock_product_info', 'id=' . $val['productId'], 'checkType');
                    } else {
                        $proCheckType = "";
                    }
                    $checkTypeStr = $this->getDatadictsStr($datadictArr ['ZJFS'], $proCheckType);
                    $str .= <<<EOT
						<tr align="center" class="TableHeader">
							<td>$i</td>
							<td rowspan=$rowNum >
								<input type="text"  class="readOnlyTxtItem" value="$val[productNumb]" readOnly  name="arrival[equipment][$i][sequence]" >
							</td>
							<td rowspan=$rowNum >
								<input type="text" value="$val[productName]" readOnly  class="readOnlyTxtItem" name="arrival[equipment][$i][productName]" />
								<input type="hidden" value="$val[productId]" name="arrival[equipment][$i][productId]" />
								<input type="hidden" value="$val[qualityCode]" name="arrival[equipment][$i][qualityCode]" />
								<input type="hidden" value="$val[qualityName]" name="arrival[equipment][$i][qualityName]" />
							</td>
							<td rowspan=$rowNum >
								<input type="text"  class="readOnlyTxtItem" value="$val[pattem]"  name="arrival[equipment][$i][pattem]" readOnly >
							</td>
							<td rowspan=$rowNum >
								<input type="text" class="readOnlyTxtItem" value="$val[units]" name="arrival[equipment][$i][units]"  readOnly/>
							<td rowspan=$rowNum >
								<input type="text" class="txtshort" value="$val[batchNumb]" name="arrival[equipment][$i][batchNum]" >
							</td>
							<td>
								<input type="hidden" readOnly  value="$val[price]" name="arrival[equipment][$i][price]" />
								<input type="hidden" readOnly  value="$val[id]" name="arrival[equipment][$i][contractId]" />
								<input type="hidden" readOnly class="readOnlyTxt" name="arrival[equipment][$i][purchType]" >

								<input type="text" class="txtshort"  value="$number" name="arrival[equipment][$i][arrivalNum]"  onblur="checkSize($number,this);checkNum(this);"/>
								<input type="hidden" value="$number" />
							</td>
							<td>
								<!--<input type="text" class="txtshort" value="$month" name="arrival[equipment][$i][month]" >-->
								<select id="month$i" class="txtshort" name="arrival[equipment][$i][month]">
									<SCRIPT language=JavaScript>
										for(i=1;i<13;i++){
											jQuery("#month$i").append('<option id="month$i'+i+'">'+i+'</option>');
											if(i==$month){jQuery("#month$i"+i).attr("SELECTED","SELECTED");}
										}
									</SCRIPT>
								</select>
							</td>
							<td>
								<input type="text" class="txtshort"  onfocus="WdatePicker()" value="$arrivalDate"  name="arrival[equipment][$i][arrivalDate]" />
							</td>
							<td  >
								<input type="text"  class="readOnlyTxtShort" value="$val[qualityName]"   readOnly >
							</td>
							<td>
								<select id="checkType$i" class="txtshort" name="arrival[equipment][$i][checkType]">$checkTypeStr</select>
							</td>
							<td>
								<img title="ɾ����" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
							</td>

							</tr>
EOT;
                }
            }
            return $str;

        }
    }

    /**
     * ���ݹ�������id�뵽�����ͻ�ȡδ������Ʒ�嵥��Ϣ
     * @param $purAppId ��������id
     * @param $arrivalType ��������
     */
    function getAssUnArrPros($purAppId, $arrivalType)
    {
        $arrivalAssDaoName = $this->arrivalTypeArr[$arrivalType]['itemmodel'];
        $arrivalAssDaoFun = $this->arrivalTypeArr[$arrivalType]['getcomfun'];
        $arrivalAssDao = new $arrivalAssDaoName ();
        return $arrivalAssDao->$arrivalAssDaoFun ($purAppId);
    }

    /**
     *���ݹ�������id�뵽�����ͻ�ȡ����ҵ����Ϣ
     * @param $purAppId ��������id
     * @param $arrivalType ��������
     */
    function getAssInfoById($purAppId, $arrivalType)
    {
        $arrivalAssDaoName = $this->arrivalTypeArr[$arrivalType]['mainmodel'];
        $arrivalAssDao = new $arrivalAssDaoName ();
        return $arrivalAssDao->get_d($purAppId);
    }


    /**
     * ��ȡ�����嵥��Ϣ
     * @param $purAppId ����֪ͨ��id
     *
     */
    function getEquipment_d($id)
    {
        $equipmentDao = new model_purchase_arrival_equipment();
        $rows = $equipmentDao->getItemByBasicIdId_d($id);
        return $rows;
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
            $object['arrivalCode'] = $codeDao->purchaseCode("oa_purchase_arrival_info");
            $object['state'] = $this->statusDao->statusEtoK('wait');
            $emailArr = $object['email'];
            $object['recipientName'] = $emailArr['TO_NAME'];
            $object['recipientId'] = $emailArr['TO_ID'];
            unset($object['email']);
            $id = parent::add_d($object, true);
            //ִ�����Ϲ���������������
            $sql = "insert into oa_arrival_contract_ass(contractId,arrivalId) values('" . $object['purchaseId'] . "','" . $id . "')";
            $this->query($sql);

            $equipmentDao = new model_purchase_arrival_equipment();
            $equName = array();
            $qualityArr = array(); //�ʼ�����

            //ʵ����һ�������ֵ�
            $datadictDao = new model_system_datadict_datadict();

            foreach ($object ['equipment'] as $key => $equ) {
                //�ƻ���������0���Ҳɹ��ƻ��������Ʋ�Ϊ�ղŽ��в���
                if ($equ ['arrivalNum'] > 0 && $equ['productName'] != "") {
                    $i = isset($i) ? (++$i) : 1; //�ж��ж��������ò�Ʒ�嵥
                    $equ ['arrivalId'] = $id;
                    $equ ['oldArrivalNum'] = $equ ['arrivalNum'];
                    $equ ['arrivalCode'] = $object['arrivalCode'];
                    if ($equ['qualityCode'] != 'ZJSXCG') {
                        $equ ['qualityPassNum'] = $equ['arrivalNum'];
                    }
                    $equId = $equipmentDao->add_d($equ);
                    $equName[] = $equ['productName'];

                    if ($equ['productId'] > 0 && $equ['qualityCode'] == 'ZJSXCG') {
                        $equ['checkType'] = $this->get_table_fields('oa_stock_product_info', 'id=' . $equ['productId'], 'checkType');
                    } else {
                        $equ['checkType'] = "";
                    }
                    //edit by kuangzw �����Ҳ��Ҫ�����ʼ����뵥
                    if ($equ['qualityCode'] == 'ZJSXCG') {

                        //�ʼ췽ʽ
                        $checkType = $datadictDao->getDataNameByCode($equ['checkType']);
                        //ƴװ�ʼ���������
                        $qualityArr['items'][$key] = array(
                            'relDocItemId' => $equId,
                            'productId' => $equ['productId'],
                            'productCode' => $equ['sequence'],
                            'productName' => $equ['productName'],
                            'pattern' => $equ['pattem'],
                            'unitName' => $equ['units'],
                            'fittings' => $equ['productName'],
                            'qualityNum' => $equ['arrivalNum'],
                            'planEndDate' => date("Y-m-d"),
                            'checkType' => $equ['checkType'],
                            'checkTypeName' => $checkType,
                            'batchNum' => $equ['batchNum']
                        );
                    }
                }
            }
            //�����ڲ�Ʒʱ���׳��쳣
            if ($i == 0) {
                throw new Exception('�޿���������');
            }
            //ƴװ�ʼ���������
            if (isset($qualityArr['items'])) {
                $qualityArr['relDocType'] = 'ZJSQYDSL';
                $qualityArr['relDocCode'] = $object['arrivalCode'];
                $qualityArr['relDocId'] = $id;
                $qualityArr['applyUserName'] = $object['purchManName'];
                $qualityArr['applyUserCode'] = $object['purchManId'];
                $qualityArr['supplierName'] = $object['supplierName'];
                $qualityArr['supplierId'] = $object['supplierId'];
                $qualityapplyDao = new model_produce_quality_qualityapply();
                $qualityapplyDao->add_d($qualityArr);
            }


            $equName = array_unique($equName);
            $equName = implode(",", $equName);

            //�����ʼ� ,������Ϊ�ύʱ�ŷ���
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $conEquDao = new model_purchase_contract_equipment ();
                $interfObj = new model_common_interface_obj ();
                $planDao = new model_purchase_plan_basic();
                $addmsg = "";
                if (is_array($object ['equipment'])) {
                    $j = 0;
                    $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>���ϱ��</b></td><td width='12%'><b>��������</b></td><td><b>����</b></td><td><b>���κ�</b></td><td><b>�·�</b></td><td><b>��������</b></td><td  width='12%'><b>��Ӧ��</b></td><td><b>�ʼ췽ʽ</b></td><td><b>������</b></td><td><b>�ɹ����뵥</b></td><td><b>Դ��(��ͬ)��</b></td><td><b>�ͻ�����</b></td><td><b>��Ŀ����</b></td><td><b>�ɹ���;</b></td><td><b>������</b></td><td><b>��ע</b></td></tr>";
                    foreach ($object ['equipment'] as $equ) {
                        $j++;
                        $checkType = $datadictDao->getDataNameByCode($equ['checkType']);
                        $conEquRows = $conEquDao->get_d($equ['contractId']);
                        $projectName = "";
                        $fippupRemark = "";
                        $planNumb = "";
                        $customer = '';
                        $applyName = "";
                        if ($conEquRows['purchType'] != "oa_asset_purchase_apply") {
                            //��ȡ�ɹ�����ID,����ȡ��Ŀ����
                            if ($conEquRows['taskEquId']) {
                                $condiction = "taskEquId=" . $conEquRows['taskEquId'] . " and applyEquId=" . $conEquRows['id'] . " and applyId=" . $conEquRows['basicId'];
                                $planId = $this->get_table_fields('oa_purch_objass', $condiction, 'planId');
                                if ($planId) {
                                    $planRow = $planDao->get_d($planId);
                                    $planNumb = $planRow['planNumb'];
                                    $projectName = $planRow['projectName'];
                                    $applyName = $planRow['sendName'];
                                }
                                if ($conEquRows['purchType'] == "stock" && $conEquRows['sourceID'] > 0) {
                                    $fippupRemark = $this->get_table_fields('oa_stock_fillup', 'id=' . $conEquRows['sourceID'], 'remark');
                                }
                            }
                        }
                        if ($conEquRows['sourceID'] > 0) {
                            if ($conEquRows['purchType'] == "oa_present_present" || $conEquRows['purchType'] == "oa_borrow_borrow") {
                                $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //��ȡ�ɹ����Ͷ�������
                                $supDao = new $supDaoName(); //ʵ��������
                                $sourceRow = $supDao->getInfoList($conEquRows['sourceID']);
                                $applyName = $sourceRow['createName'];
                                $customer = $sourceRow['customerName'];
                            }
                            if ($conEquRows['purchType'] == "HTLX-XSHT" || $conEquRows['purchType'] == "HTLX-ZLHT" || $conEquRows['purchType'] == "HTLX-FWHT" || $conEquRows['purchType'] == "HTLX-YFHT") {
                                $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //��ȡ�ɹ����Ͷ�������
                                $supDao = new $supDaoName(); //ʵ��������
                                $sourceRow = $supDao->getInfoList($conEquRows['sourceID']);
                                $applyName = $sourceRow['prinvipalName'];
                                $customer = $sourceRow['customerName'];
                            }
                        }
                        $purchaseCodeArr = model_common_util::subWordInArray($object['purchaseCode'], 10);
                        $purchaseCode = implode("<br />", $purchaseCodeArr);
                        $sourceCodeArr = model_common_util::subWordInArray($conEquRows['sourceNumb'], 10);
                        $sourceCode = implode("<br />", $sourceCodeArr);
                        $planNumbArr = model_common_util::subWordInArray($planNumb, 10);
                        $applyNumb = implode("<br />", $planNumbArr);
                        $purchTypeC = $interfObj->typeKToC($conEquRows['purchType']); //��������
                        $sequence = $equ['sequence'];
                        $productName = $equ['productName'];
                        $arrivalNum = $equ ['arrivalNum'];
                        $batchNum = $equ ['batchNum'];
                        $month = $equ ['month'];
                        $arrivalDate = $equ ['arrivalDate'];
                        $supplierName = $object['supplierName'];
                        $project = $projectName;
                        $remark = $fippupRemark;
                        $addmsg .= <<<EOT
						<tr align="center" >
							<td>$j</td>
							<td>$sequence</td>
							<td  align='left'  width='12%'>$productName</td>
							<td>$arrivalNum</td>
							<td>$batchNum</td>
							<td>$month</td>
							<td>$arrivalDate</td>
							<td  align='left'  width='12%'>$supplierName</td>
							<td>$checkType</td>
							<td>$purchaseCode</td>
							<td >$applyNumb</td>
							<td>$sourceCode</td>
							<td>$customer</td>
							<td>$project</td>
							<td>$purchTypeC</td>
							<td>$applyName</td>
							<td align='left' width='10%'>$remark</td>
						</tr>
EOT;
                    }
                    $addmsg .= "</table><br><font color=red>��ע��</font><br>" . $object['remark'];
                }
                $emailDao = new model_common_mail();
                $emailDao->arrivalEmailWithEqu($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], '�ɹ�����֪ͨ(' . $equName . ')', '�����ϵ��ɹ��������ǣ�<font color=blue><b>' . $object["purchManName"] . '</b></font>������֪ͨ����Ϊ��<font color=red><b>' . $object["arrivalCode"] . '</b></font>����', $equName, $emailArr['TO_ID'], $addmsg, 1);
            }
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
    function edit_d($arrivalinfo)
    {
        try {
            $this->start_d();
            $id = parent :: edit_d($arrivalinfo, "true");
            $arrivalproDao = new model_purchase_arrival_equipment();
            $arrivalAssDao = new model_purchase_contract_equipment ();

            /*start:�޸����ϲ�Ʒ�嵥��Ϣ*/
            if (is_array($arrivalinfo['equipment'])) {
                foreach ($arrivalinfo['equipment'] as $key => $arriPro) {
                    $arrivalproDao->edit_d($arriPro);
                    /*start:-------�������ϵ�����ҵ��ĵ��ѵ�������------*/
                    //$arrivalAssDao-> updateAmountIssued ($arriPro['contractId'], $arriPro['arrivalNum'], $arriPro['oldArrivalNum']);
                    /*end:-------�������ϵ�����ҵ��ĵ��ѵ�������------*/
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
     * ������ⵥʱ���������ϵ�ID��ȡ������ʾģ��
     * @param $arrivalId ����֪ͨ��id
     *
     */
    function getEquList_d($arrivalId)
    {
        $arrivalEquDao = new model_purchase_arrival_equipment();
        $rows = $arrivalEquDao->getItemByBasicIdId_d($arrivalId);
        // k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $list = $arrivalEquDao->showAddList($rows);
        return $list;
    }

    /**
     * ������ⵥʱ���������ϵ�ID��ȡ������ʾģ��
     * @param $arrivalId ����֪ͨ��id
     *
     */
    function getEquListJson_d($arrivalId)
    {
        $arrivalEquDao = new model_purchase_arrival_equipment();
        $rows = $arrivalEquDao->getItemByBasicIdId_d($arrivalId);
        // k3������ش���
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $list = $arrivalEquDao->showAddListJson($rows);
        return $list;
    }

    /**
     *��������ID����������֪ͨ����״̬Ϊ��δִ�С�
     *
     * @param $id ����֪ͨ��ID
     */
    function updateArrival($id)
    {
        $state = $this->statusDao->statusEtoK('wait');
        $condiction = array('id' => $id);
        //�޸�״̬Ϊ"�ѹر�"
        $updateTag = $this->updateField($condiction, 'state', $state);
        return $updateTag;
    }

    /**
     *��������ID����������֪ͨ����״̬Ϊ���ѹرա�
     *
     * @param $id ����֪ͨ��ID
     */
    function updateArrivalForClose($id)
    {
        $state = $this->statusDao->statusEtoK('close');
        $condiction = array('id' => $id);
        //�޸�״̬Ϊ"�ѹر�"
        $updateTag = $this->updateField($condiction, 'state', $state);
        return $updateTag;
    }

    /**
     * ������ʱ���и�������֪ͨ����Ϣ
     * @param $id
     * @param $equId
     * @param $productId
     * @param $proNum
     * @param $docDate
     * @return null
     */
    function updateInStock($id, $equId, $productId, $proNum, $docDate = day_date)
    {
        try {
//			$this->start_d();
            $arrivalEupDao = new model_purchase_arrival_equipment();
            $arrivalEupDao->updateNumb_d($id, $equId, $proNum); //�������ϵ��������
            $state = $arrivalEupDao->isEndInstock_d($id); //�ж��Ƿ���������
            $arrivaObj = array("id" => $id,
                "state" => $state);
            $this->updateById($arrivaObj);
            $equRows = $arrivalEupDao->get_d($equId);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], $proNum, false, $docDate); //���²ɹ����������ϵ�������
            //		$arrivalEupDao->updateOnWay_d($equRows['id'],$proNum);
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
     * @param  $proNum �������
     */
    function updateInStockCancel($id, $equId, $productId, $proNum, $docDate = day_date)
    {
        try {
//			$this->start_d();
            $arrivalEupDao = new model_purchase_arrival_equipment();
            $arrivalEupDao->updateNumb_d($id, $equId, -$proNum);
            $state = $arrivalEupDao->isEndInstock_d($id); //�ж��Ƿ���������
            $arrivaObj = array("id" => $id,
                "state" => $state);
            $this->updateById($arrivaObj);
            $equRows = $arrivalEupDao->get_d($equId);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], -$proNum, false, $docDate); //���²ɹ����������ϵ�������
            //		$arrivalEupDao->updateOnWay_d($equRows['id'],$proNum);
//			$this->commit_d();
        } catch (exception $e) {
//			$this->rollBack();
            return null;
        }
    }

    /**ɾ������֪ͨ��
     * @param  $id ����֪ͨ��ID
     */
    function deletesInfo_d($id)
    {
        try {
            $this->start_d();
            //��ȥ�����ɹ������豸�嵥������������

            $equDao = new model_purchase_arrival_equipment();
            //$equDao->del_d($id);

            $this->deletes($id);
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**�ʲ�����ȷ��
     */
    function confAsset_d($object)
    {
        try {
            $this->start_d();
            $equDao = new model_purchase_arrival_equipment();
            $contractEquDao = new model_purchase_contract_equipment();
            $arrivaObj = array("id" => $object['id'],
                "state" => "2");
            $this->updateById($arrivaObj);
            //�޸��������
            if (is_array($object['equipment'])) {
                foreach ($object['equipment'] as $val) {
                    $equDao->edit_d($val);
                    $contractEquDao->updateAmountIssued($val['contractId'], $val['storageNum']); //���²ɹ����������ϵ�������
                }
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**����������Ϣ����ȡ���������ƺ�ID
     * @param  $rows ����������Ϣ����
     *
     */
    function getApplyers_d($rows)
    {
        $planDao = new model_purchase_plan_basic();
        $taskEquDao = new model_purchase_task_equipment ();
        $planIds = array();
        $applyNames = array(); //����������
        $applyUserId = array(); //������ID
        foreach ($rows as $key => $val) { //���ݲɹ���������ID���Ӳɹ��������ȡ�ɹ�����ID
            if ($val['purchType'] == "oa_asset_purchase_apply") { //�����Ƿ�Ϊ�̶��ʲ��ɹ�
                if ($key == 0) {
                    $applyDao = new model_asset_purchase_apply_apply();
                }
                $taskEquRow = $taskEquDao->get_d($val['taskEquId']);
                $applyRow = $applyDao->get_d($taskEquRow['applyId']);
                $applyNames[] = $applyRow['applicantName'];
                $applyUserId[] = $applyRow['applicantId'];
            } else {
                $sql = "select planId from oa_purch_objass where applyEquId=" . $val['id'];
                $res = $this->query($sql);
                $row = mysql_fetch_row($res);
                foreach ($row as $valId) {
                    $planIds[] = $valId;
                }

            }
        }
        $planIds = array_unique($planIds);
        if (is_array($planIds)) {
            foreach ($planIds as $k => $v) { //��ȡ���������ƺ�ID
                $planRows = $planDao->get_d($v);
                $applyNames[] = $planRows['sendName'];
                $applyUserId[] = $planRows['sendUserId'];
            }
        }
        $applyNames = array_unique($applyNames);
        $applyUserId = array_unique($applyUserId);
        $applyNames = implode(",", $applyNames);
        $applyUserId = implode(",", $applyUserId);
        $applyNames = $applyNames . ",";
        $applyUserId = $applyUserId . ",";
        $applyers[0] = $applyNames;
        $applyers[1] = $applyUserId;
        return $applyers;

    }

    /*****************************�ϲ��²�**************************/

    /**
     * �ж�����Դ����Ϣ�Ƿ������Ƶ��⹺��ⵥ
     */
    function hasSource($objId, $objType = null)
    {
        $this->searchArr = array('purchaseId' => $objId);
        if (is_array($this->listBySqlId('select_default'))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *������������id,��ȡԴ����Ϣ
     *
     */
    function getApplyInfo($itemRelDocId)
    {
        $equDao = new model_purchase_arrival_equipment();
        //��ȡ����������Ϣ
        $arrivalEquRow = $equDao->get_d($itemRelDocId);
        if ($arrivalEquRow['contractId']) {
            //��ȡ����������Ϣ
            $contEquDao = new model_purchase_contract_equipment();
            $contEquRow = $contEquDao->get_d($arrivalEquRow['contractId']);
        }
        $object = array();
        if (is_array($contEquRow)) {
            $object = array(
                "applyType" => $contEquRow['purchType'], "applyId" => $contEquRow['sourceID'], "applyCode" => $contEquRow['sourceNumb']
            );
        }
        return $object;
    }

    /**
     * ��ȡ����������
     *
     */
    function pageStock_d()
    {
        $rows = $this->pageBySqlId('select_default');
        return $rows;
    }

    /**
     * �����ʼ����ʱ��
     */
    function updateCompletionTime($id)
    {
        $this->update(array('id' => $id), array('completionTime' => date('Y-m-d H:i:s')));
    }

    /**
     * ��������֪ͨ
     */
    function receiveNotice_d($obj)
    {
        $object = $this->get_d($obj['id']);
        $equipmentDao = new model_purchase_arrival_equipment();
        $conEquDao = new model_purchase_contract_equipment ();
        $interfObj = new model_common_interface_obj ();
        $planDao = new model_purchase_plan_basic();
        $datadictDao = new model_system_datadict_datadict();
        $object['equipment'] = $equipmentDao->findAll(array('arrivalId' => $obj['id']));
        $addmsg = "";
        if (is_array($object['equipment'])) {
            $j = 0;
            $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>���</b></td><td><b>���ϱ��</b></td><td width='12%'><b>��������</b></td><td><b>����</b></td><td><b>���κ�</b></td><td><b>�·�</b></td><td><b>��������</b></td><td  width='12%'><b>��Ӧ��</b></td><td><b>�ʼ췽ʽ</b></td><td><b>������</b></td><td><b>�ɹ����뵥</b></td><td><b>Դ��(��ͬ)��</b></td><td><b>�ͻ�����</b></td><td><b>��Ŀ����</b></td><td><b>�ɹ���;</b></td><td><b>������</b></td><td><b>��ע</b></td></tr>";
            foreach ($object['equipment'] as $key => $equ) {
                $j++;
                $equName[] = $equ['productName'];
                $checkType = $datadictDao->getDataNameByCode($equ['checkType']);
                $conEquRows = $conEquDao->get_d($equ['contractId']);
                $projectName = "";
                $fippupRemark = "";
                $planNumb = "";
                $customer = '';
                $applyName = "";
                if ($conEquRows['purchType'] != "oa_asset_purchase_apply") {
                    //��ȡ�ɹ�����ID,����ȡ��Ŀ����
                    if ($conEquRows['taskEquId']) {
                        $condiction = "taskEquId=" . $conEquRows['taskEquId'] . " and applyEquId=" . $conEquRows['id'] . " and applyId=" . $conEquRows['basicId'];
                        $planId = $this->get_table_fields('oa_purch_objass', $condiction, 'planId');
                        if ($planId) {
                            $planRow = $planDao->get_d($planId);
                            $planNumb = $planRow['planNumb'];
                            $projectName = $planRow['projectName'];
                            $applyName = $planRow['sendName'];
                        }
                        if ($conEquRows['purchType'] == "stock" && $conEquRows['sourceID'] > 0) {
                            $fippupRemark = $this->get_table_fields('oa_stock_fillup', 'id=' . $conEquRows['sourceID'], 'remark');
                        }
                    }
                }
                if ($conEquRows['sourceID'] > 0) {
                    if ($conEquRows['purchType'] == "oa_present_present" || $conEquRows['purchType'] == "oa_borrow_borrow") {
                        $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //��ȡ�ɹ����Ͷ�������
                        $supDao = new $supDaoName(); //ʵ��������
                        $sourceRow = $supDao->getInfoList($conEquRows['sourceID']);
                        $applyName = $sourceRow['createName'];
                        $customer = $sourceRow['customerName'];
                    }
                    if ($conEquRows['purchType'] == "HTLX-XSHT" || $conEquRows['purchType'] == "HTLX-ZLHT" || $conEquRows['purchType'] == "HTLX-FWHT" || $conEquRows['purchType'] == "HTLX-YFHT") {
                        $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //��ȡ�ɹ����Ͷ�������
                        $supDao = new $supDaoName(); //ʵ��������
                        $sourceRow = $supDao->getInfoList($conEquRows['sourceID']);
                        $applyName = $sourceRow['prinvipalName'];
                        $customer = $sourceRow['customerName'];
                    }
                }
                $purchaseCodeArr = model_common_util::subWordInArray($object['purchaseCode'], 10);
                $purchaseCode = implode("<br />", $purchaseCodeArr);
                $sourceCodeArr = model_common_util::subWordInArray($conEquRows['sourceNumb'], 10);
                $sourceCode = implode("<br />", $sourceCodeArr);
                $planNumbArr = model_common_util::subWordInArray($planNumb, 10);
                $applyNumb = implode("<br />", $planNumbArr);
                $purchTypeC = $interfObj->typeKToC($conEquRows['purchType']); //��������
                $sequence = $equ['sequence'];
                $productName = $equ['productName'];
                $arrivalNum = $equ ['arrivalNum'];
                $batchNum = $equ ['batchNum'];
                $month = $equ ['month'];
                $arrivalDate = $equ ['arrivalDate'];
                $supplierName = $object['supplierName'];
                $project = $projectName;
                $remark = $fippupRemark;
                $addmsg .= <<<EOT
				<tr align="center" >
					<td>$j</td>
					<td>$sequence</td>
					<td  align='left'  width='12%'>$productName</td>
					<td>$arrivalNum</td>
					<td>$batchNum</td>
					<td>$month</td>
					<td>$arrivalDate</td>
					<td  align='left'  width='12%'>$supplierName</td>
					<td>$checkType</td>
					<td>$purchaseCode</td>
					<td >$applyNumb</td>
					<td>$sourceCode</td>
					<td>$customer</td>
					<td>$project</td>
					<td>$purchTypeC</td>
					<td>$applyName</td>
					<td align='left' width='10%'>$remark</td>
				</tr>
EOT;
            }
            $addmsg .= "</table><br><font color=red>��ע��</font><br>" . $obj['mailContent'];
        }
        $equName = array_unique($equName);
        $equName = implode(",", $equName);
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->arrivalEmailWithEqu('y', $_SESSION['USERNAME'], $_SESSION['EMAIL'], '�ɹ�����֪ͨ(' . $equName . ')', '�����ϵ��ɹ��������ǣ�<font color=blue><b>' . $object["purchManName"] . '</b></font>������֪ͨ����Ϊ��<font color=red><b>' . $object["arrivalCode"] . '</b></font>����', $equName, $obj['receiverId'], $addmsg, 1);
        return $emailInfo;
    }
}