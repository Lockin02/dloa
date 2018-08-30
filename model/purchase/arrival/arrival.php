<?php
/**
 * @author Administrator
 * @Date 2011年5月4日 19:49:09
 * @version 1.0
 * @description:收料通知单信息 Model层
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
        $this->arrivalTypeArr = array( //到货关联业务类型数组
            "ARRIVALTYPE1" => array(
                "mainmodel" => "model_purchase_contract_purchasecontract",
                "itemmodel" => "model_purchase_contract_equipment",
                "updatefun" => "updateAmountIssued", //更新相关业务
                "getcomfun" => "getUnArrivalEqusByContractId" //获取未到货信息
            )
        );
        $this->demandTypeArr = array( //需求类型数组
            "order" => array(
                "name" => "销售订单", //名称
                "objassmodel" => "projectmanagent_order_order", //需求业务单据model
                //"objassmodel"=>"contract_sales_sales",//需求业务单据model
                "readfun" => "init&perm=view" //查看方法
                //"readfun"=>"readDetailedInfoNoedit"//查看方法
            ),
            "stock" => array(
                "name" => "补库计划",
                "objassmodel" => "stock_fillup_fillup",
                "readfun" => "init"
            )
        );
        $this->statusDao = new model_common_status ();
        $this->statusDao->status = array(
            0 => array(
                'statusEName' => 'wait',
                'statusCName' => '未执行',
                'key' => '0'
            ),
            1 => array(
                'statusEName' => 'execute',
                'statusCName' => '执行中',
                'key' => '1'
            ),
            2 => array(
                'statusEName' => 'close',
                'statusCName' => '已关闭',
                'key' => '2'
            ),
            3 => array(
                'statusEName' => 'delivered',
                'statusCName' => '发生退货',
                'key' => '3'
            ),
            4 => array(
                'statusEName' => 'part',
                'statusCName' => '部分执行',
                'key' => '4'
            )
        );
        //调用初始化对象关联类
        parent::setObjAss();
    }

    //公司权限处理 TODO
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分
    protected $_isSetMyList = 0; # 个人列表单据是否要区分公司,1为区分,0为不区分


    /**
     * 对采购合同新增到货单时，产品清单模板
     * @param $rows 产品清单数组
     */
    function showPurchAppProInfo($rows)
    {
        if ($rows) {
            $datadictDao = new model_system_datadict_datadict();
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            $rowNum = 1; //一个产品对应的采购申请单产品清单个数
            $arrivalDate = date("Y-m-d");
            $month = date("n");
            $datadictArr = $this->getDatadicts("ZJFS");

            $arrivalEquDao = new model_purchase_arrival_equipment();
            foreach ($rows as $key => $val) {
                $arrivalNum = 0;
                $arrivalEquRows = $arrivalEquDao->getItemByContractEquId_d($val['id']);
                if (is_array($arrivalEquRows)) { //获取某物料的收料情况
                    foreach ($arrivalEquRows as $arrKey => $arrVal) {
                        $arrivalNum = $arrivalNum + $arrVal['arrivalNum'];
                    }
                }
                $number = $val['amountAll'] - $arrivalNum;
                if ($number > 0) {
                    ++$i;
                    if ($val['productId']) {
                        $proCheckType = $this->get_table_fields('oa_stock_product_info', 'id=' . $val['productId'], 'checkType'); //质检方式
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
								<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
							</td>

						</tr>
EOT;
                }
            }
            return $str;

        }
    }

    /**
     * 对采购合同新增到货单时，产品清单模板（单独新增）
     * @param $rows 产品清单数组
     */
    function addPurchProInfo($rows)
    {
        if ($rows) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            $rowNum = 1; //一个产品对应的采购申请单产品清单个数
            $arrivalDate = date("Y-m-d");
            $month = date("n");
            $datadictArr = $this->getDatadicts("ZJFS");
            $arrivalEquDao = new model_purchase_arrival_equipment();
            foreach ($rows as $val) {
                $arrivalNum = 0;
                $arrivalEquRows = $arrivalEquDao->getItemByContractEquId_d($val['id']);
                if (is_array($arrivalEquRows)) { //获取某物料的收料情况
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
								<img title="删除行" onclick="mydel(this , 'mytable')" src="images/closeDiv.gif">
							</td>

							</tr>
EOT;
                }
            }
            return $str;

        }
    }

    /**
     * 根据关联单据id与到货类型获取未到货产品清单信息
     * @param $purAppId 关联单据id
     * @param $arrivalType 收料类型
     */
    function getAssUnArrPros($purAppId, $arrivalType)
    {
        $arrivalAssDaoName = $this->arrivalTypeArr[$arrivalType]['itemmodel'];
        $arrivalAssDaoFun = $this->arrivalTypeArr[$arrivalType]['getcomfun'];
        $arrivalAssDao = new $arrivalAssDaoName ();
        return $arrivalAssDao->$arrivalAssDaoFun ($purAppId);
    }

    /**
     *根据关联单据id与到货类型获取关联业务信息
     * @param $purAppId 关联单据id
     * @param $arrivalType 收料类型
     */
    function getAssInfoById($purAppId, $arrivalType)
    {
        $arrivalAssDaoName = $this->arrivalTypeArr[$arrivalType]['mainmodel'];
        $arrivalAssDao = new $arrivalAssDaoName ();
        return $arrivalAssDao->get_d($purAppId);
    }


    /**
     * 获取收料清单信息
     * @param $purAppId 收料通知单id
     *
     */
    function getEquipment_d($id)
    {
        $equipmentDao = new model_purchase_arrival_equipment();
        $rows = $equipmentDao->getItemByBasicIdId_d($id);
        return $rows;
    }

    /**
     * 添加收料通知单
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
            //执行收料关联对象新增操作
            $sql = "insert into oa_arrival_contract_ass(contractId,arrivalId) values('" . $object['purchaseId'] . "','" . $id . "')";
            $this->query($sql);

            $equipmentDao = new model_purchase_arrival_equipment();
            $equName = array();
            $qualityArr = array(); //质检数组

            //实例化一个数据字典
            $datadictDao = new model_system_datadict_datadict();

            foreach ($object ['equipment'] as $key => $equ) {
                //计划数量大于0并且采购计划物料名称不为空才进行操作
                if ($equ ['arrivalNum'] > 0 && $equ['productName'] != "") {
                    $i = isset($i) ? (++$i) : 1; //判断有多少条可用产品清单
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
                    //edit by kuangzw 免检类也需要生成质检申请单
                    if ($equ['qualityCode'] == 'ZJSXCG') {

                        //质检方式
                        $checkType = $datadictDao->getDataNameByCode($equ['checkType']);
                        //拼装质检申请数组
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
            //不存在产品时，抛出异常
            if ($i == 0) {
                throw new Exception('无可收料物料');
            }
            //拼装质检申请数据
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

            //发送邮件 ,当操作为提交时才发送
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $conEquDao = new model_purchase_contract_equipment ();
                $interfObj = new model_common_interface_obj ();
                $planDao = new model_purchase_plan_basic();
                $addmsg = "";
                if (is_array($object ['equipment'])) {
                    $j = 0;
                    $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td width='12%'><b>物料名称</b></td><td><b>数量</b></td><td><b>批次号</b></td><td><b>月份</b></td><td><b>到货日期</b></td><td  width='12%'><b>供应商</b></td><td><b>质检方式</b></td><td><b>订单号</b></td><td><b>采购申请单</b></td><td><b>源单(合同)号</b></td><td><b>客户名称</b></td><td><b>项目名称</b></td><td><b>采购用途</b></td><td><b>申请人</b></td><td><b>备注</b></td></tr>";
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
                            //获取采购申请ID,并获取项目名称
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
                                $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //获取采购类型对象名称
                                $supDao = new $supDaoName(); //实例化对象
                                $sourceRow = $supDao->getInfoList($conEquRows['sourceID']);
                                $applyName = $sourceRow['createName'];
                                $customer = $sourceRow['customerName'];
                            }
                            if ($conEquRows['purchType'] == "HTLX-XSHT" || $conEquRows['purchType'] == "HTLX-ZLHT" || $conEquRows['purchType'] == "HTLX-FWHT" || $conEquRows['purchType'] == "HTLX-YFHT") {
                                $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //获取采购类型对象名称
                                $supDao = new $supDaoName(); //实例化对象
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
                        $purchTypeC = $interfObj->typeKToC($conEquRows['purchType']); //类型名称
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
                    $addmsg .= "</table><br><font color=red>备注：</font><br>" . $object['remark'];
                }
                $emailDao = new model_common_mail();
                $emailDao->arrivalEmailWithEqu($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], '采购到货通知(' . $equName . ')', '该收料单采购负责人是：<font color=blue><b>' . $object["purchManName"] . '</b></font>。收料通知单号为：<font color=red><b>' . $object["arrivalCode"] . '</b></font>到货', $equName, $emailArr['TO_ID'], $addmsg, 1);
            }
            $this->commit_d();
            return $id;
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 修改收料通知单
     */
    function edit_d($arrivalinfo)
    {
        try {
            $this->start_d();
            $id = parent :: edit_d($arrivalinfo, "true");
            $arrivalproDao = new model_purchase_arrival_equipment();
            $arrivalAssDao = new model_purchase_contract_equipment ();

            /*start:修改收料产品清单信息*/
            if (is_array($arrivalinfo['equipment'])) {
                foreach ($arrivalinfo['equipment'] as $key => $arriPro) {
                    $arrivalproDao->edit_d($arriPro);
                    /*start:-------更新收料单关联业务的的已到货数量------*/
                    //$arrivalAssDao-> updateAmountIssued ($arriPro['contractId'], $arriPro['arrivalNum'], $arriPro['oldArrivalNum']);
                    /*end:-------更新收料单关联业务的的已到货数量------*/
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
     * 生成入库单时，根据收料单ID获取物料显示模板
     * @param $arrivalId 收料通知单id
     *
     */
    function getEquList_d($arrivalId)
    {
        $arrivalEquDao = new model_purchase_arrival_equipment();
        $rows = $arrivalEquDao->getItemByBasicIdId_d($arrivalId);
        // k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $list = $arrivalEquDao->showAddList($rows);
        return $list;
    }

    /**
     * 生成入库单时，根据收料单ID获取物料显示模板
     * @param $arrivalId 收料通知单id
     *
     */
    function getEquListJson_d($arrivalId)
    {
        $arrivalEquDao = new model_purchase_arrival_equipment();
        $rows = $arrivalEquDao->getItemByBasicIdId_d($arrivalId);
        // k3编码加载处理
        $productinfoDao = new model_stock_productinfo_productinfo();
        $rows = $productinfoDao->k3CodeFormatter_d($rows);
        $list = $arrivalEquDao->showAddListJson($rows);
        return $list;
    }

    /**
     *根据收料ID，更新收料通知单的状态为“未执行”
     *
     * @param $id 收料通知单ID
     */
    function updateArrival($id)
    {
        $state = $this->statusDao->statusEtoK('wait');
        $condiction = array('id' => $id);
        //修改状态为"已关闭"
        $updateTag = $this->updateField($condiction, 'state', $state);
        return $updateTag;
    }

    /**
     *根据收料ID，更新收料通知单的状态为“已关闭”
     *
     * @param $id 收料通知单ID
     */
    function updateArrivalForClose($id)
    {
        $state = $this->statusDao->statusEtoK('close');
        $condiction = array('id' => $id);
        //修改状态为"已关闭"
        $updateTag = $this->updateField($condiction, 'state', $state);
        return $updateTag;
    }

    /**
     * 审核入库时进行更新收料通知单信息
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
            $arrivalEupDao->updateNumb_d($id, $equId, $proNum); //更新收料的入库数量
            $state = $arrivalEupDao->isEndInstock_d($id); //判断是否已完成入库
            $arrivaObj = array("id" => $id,
                "state" => $state);
            $this->updateById($arrivaObj);
            $equRows = $arrivalEupDao->get_d($equId);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], $proNum, false, $docDate); //更新采购订单的物料到货数量
            //		$arrivalEupDao->updateOnWay_d($equRows['id'],$proNum);
//			$this->commit_d();
        } catch (exception $e) {
//			$this->rollBack();
            return null;
        }
    }

    /**
     * 反审核入库时进行更新收料通知单信息
     * @param  $id 收料通知单ID
     * @param  $equId 物料清单ID
     * @param  $productId 物料ID
     * @param  $proNum 入库数量
     */
    function updateInStockCancel($id, $equId, $productId, $proNum, $docDate = day_date)
    {
        try {
//			$this->start_d();
            $arrivalEupDao = new model_purchase_arrival_equipment();
            $arrivalEupDao->updateNumb_d($id, $equId, -$proNum);
            $state = $arrivalEupDao->isEndInstock_d($id); //判断是否已完成入库
            $arrivaObj = array("id" => $id,
                "state" => $state);
            $this->updateById($arrivaObj);
            $equRows = $arrivalEupDao->get_d($equId);
            $contractEquDao = new model_purchase_contract_equipment();
            $contractEquDao->updateAmountIssued($equRows['contractId'], -$proNum, false, $docDate); //更新采购订单的物料到货数量
            //		$arrivalEupDao->updateOnWay_d($equRows['id'],$proNum);
//			$this->commit_d();
        } catch (exception $e) {
//			$this->rollBack();
            return null;
        }
    }

    /**删除收料通知单
     * @param  $id 收料通知单ID
     */
    function deletesInfo_d($id)
    {
        try {
            $this->start_d();
            //减去关联采购任务设备清单的已申请数量

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

    /**资产收料确认
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
            //修改入库数量
            if (is_array($object['equipment'])) {
                foreach ($object['equipment'] as $val) {
                    $equDao->edit_d($val);
                    $contractEquDao->updateAmountIssued($val['contractId'], $val['storageNum']); //更新采购订单的物料到货数量
                }
            }
            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**根据物料信息，获取申请人名称和ID
     * @param  $rows 收料物料信息数组
     *
     */
    function getApplyers_d($rows)
    {
        $planDao = new model_purchase_plan_basic();
        $taskEquDao = new model_purchase_task_equipment ();
        $planIds = array();
        $applyNames = array(); //申请人名称
        $applyUserId = array(); //申请人ID
        foreach ($rows as $key => $val) { //根据采购订单物料ID，从采购关联表获取采购申请ID
            if ($val['purchType'] == "oa_asset_purchase_apply") { //区分是否为固定资产采购
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
            foreach ($planIds as $k => $v) { //获取申请人名称和ID
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

    /*****************************上查下查**************************/

    /**
     * 判断所传源单信息是否有下推的外购入库单
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
     *根据收料物料id,获取源单信息
     *
     */
    function getApplyInfo($itemRelDocId)
    {
        $equDao = new model_purchase_arrival_equipment();
        //获取收料物料信息
        $arrivalEquRow = $equDao->get_d($itemRelDocId);
        if ($arrivalEquRow['contractId']) {
            //获取订单物料信息
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
     * 获取待收料数量
     *
     */
    function pageStock_d()
    {
        $rows = $this->pageBySqlId('select_default');
        return $rows;
    }

    /**
     * 更新质检完成时间
     */
    function updateCompletionTime($id)
    {
        $this->update(array('id' => $id), array('completionTime' => date('Y-m-d H:i:s')));
    }

    /**
     * 发送收料通知
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
            $addmsg .= "<table border=1 cellspacing=0  width=100% bordercolorlight=#333333 bordercolordark=#efefef align=center ><tr bgcolor=#EAEAEA align=center ><td><b>序号</b></td><td><b>物料编号</b></td><td width='12%'><b>物料名称</b></td><td><b>数量</b></td><td><b>批次号</b></td><td><b>月份</b></td><td><b>到货日期</b></td><td  width='12%'><b>供应商</b></td><td><b>质检方式</b></td><td><b>订单号</b></td><td><b>采购申请单</b></td><td><b>源单(合同)号</b></td><td><b>客户名称</b></td><td><b>项目名称</b></td><td><b>采购用途</b></td><td><b>申请人</b></td><td><b>备注</b></td></tr>";
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
                    //获取采购申请ID,并获取项目名称
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
                        $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //获取采购类型对象名称
                        $supDao = new $supDaoName(); //实例化对象
                        $sourceRow = $supDao->getInfoList($conEquRows['sourceID']);
                        $applyName = $sourceRow['createName'];
                        $customer = $sourceRow['customerName'];
                    }
                    if ($conEquRows['purchType'] == "HTLX-XSHT" || $conEquRows['purchType'] == "HTLX-ZLHT" || $conEquRows['purchType'] == "HTLX-FWHT" || $conEquRows['purchType'] == "HTLX-YFHT") {
                        $supDaoName = $interfObj->typeKToObj($conEquRows['purchType']); //获取采购类型对象名称
                        $supDao = new $supDaoName(); //实例化对象
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
                $purchTypeC = $interfObj->typeKToC($conEquRows['purchType']); //类型名称
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
            $addmsg .= "</table><br><font color=red>备注：</font><br>" . $obj['mailContent'];
        }
        $equName = array_unique($equName);
        $equName = implode(",", $equName);
        $emailDao = new model_common_mail();
        $emailInfo = $emailDao->arrivalEmailWithEqu('y', $_SESSION['USERNAME'], $_SESSION['EMAIL'], '采购到货通知(' . $equName . ')', '该收料单采购负责人是：<font color=blue><b>' . $object["purchManName"] . '</b></font>。收料通知单号为：<font color=red><b>' . $object["arrivalCode"] . '</b></font>到货', $equName, $obj['receiverId'], $addmsg, 1);
        return $emailInfo;
    }
}