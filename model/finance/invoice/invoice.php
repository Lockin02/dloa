<?php

/**
 * 发票登记model层类
 */
class model_finance_invoice_invoice extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invoice";
        $this->sql_map = "finance/invoice/invoiceSql.php";
        parent::__construct();
    }

    public $chCode = array('1' => '一', '二', '三', '四');

    public $egCode = array('1' => 'One', 'Two', 'Three', 'Four');

    //开票信息列表，用于做变更比较后转为中文字段
    private $infoArr = array('invoiceUnitName' => '开票单位', 'objType' => '源单类型', 'objCode' => '源单编号',
        'invoiceType' => '发票类型', 'invoiceTime' => '开票日期', 'deptName' => '部门',
        'salesman' => '业务员', 'invoiceMoney' => '开票金额', 'softMoney' => '软件金额',
        'hardMoney' => '硬件金额', 'repairMoney' => '维修金额', 'serviceMoney' => '服务金额',
        'allAmount' => '开票数量', 'invoiceNo' => '发票号码', 'psType' => '产品/服务类型',
        'invoiceContent' => '货品名称/服务项目', 'managerName' => '主管',
        'invoiceUnitProvince' => '客户省份', 'invoiceUnitType' => '客户类型',
        'equRentalMoney' => '设备租赁金额', 'spaceRentalMoney' => '场地租赁金额', 'otherMoney' => '其他金额'
    );

    //需要处理的类型
    private $needDealArr = array('KPRK-12');

    public $datadictInfo = array('invoiceType', 'objType', 'invoiceUnitType');

    //公司权限处理
    protected $_isSetCompany = 1;

    //数据字典字段处理
    public $datadictFieldArr = array(
        'invoiceType', 'invoiceUnitType', 'objType'
    );

    /**
     * 发票类型过滤
     * @param $thisVal
     * @return null|string
     */
    function rtPostVla($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = 'KPRK-01,KPRK-02';
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = 'KPRK-03,KPRK-04';
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = 'KPRK-05,KPRK-06';
                break;
            case 'KPRK-07' :
            case 'KPRK-08' :
                $val = 'KPRK-07,KPRK-08';
                break;
            case 'KPRK-09' :
                $val = 'KPRK-09';
                break;
            case 'KPRK-10' :
                $val = 'KPRK-10';
                break;
            case 'KPRK-11' :
                $val = 'KPRK-11';
                break;
            case 'KPRK-12' :
                $val = 'KPRK-12';
                break;
            default :
                $val = '';
                break;
        }
        return $val;
    }

    /**
     * 发票类型过滤
     * @param $thisVal
     * @return null|string
     */
    function rtPostVlaForSelect($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = "'KPRK-01','KPRK-02'";
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = "'KPRK-03','KPRK-04'";
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = "'KPRK-05','KPRK-06'";
                break;
            case 'KPRK-07' :
            case 'KPRK-08' :
                $val = "'KPRK-07','KPRK-08'";
                break;
            case 'KPRK-09' :
                $val = "'KPRK-09'";
                break;
            case 'KPRK-10' :
                $val = "'KPRK-10'";
                break;
            case 'KPRK-11' :
                $val = "'KPRK-11'";
                break;
            case 'KPRK-12' :
                $val = "'KPRK-12'";
                break;
            default :
                $val = '';
                break;
        }
        return $val;
    }

    /**
     * 发票类型过滤
     * @param $thisVal
     * @return null|string
     */
    function rtTypeVla($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'all' :
                $val = '全部合同';
                break;
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = '销售合同(包含临时合同)';
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = '服务合同(包含临时合同)';
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = '租赁合同(包含临时合同)';
                break;
            case 'KPRK-07' :
            case 'KPRK-08' :
                $val = '研发合同(包含临时合同)';
                break;
            case 'KPRK-09' :
                $val = '其他合同';
                break;
            case 'KPRK-10' :
                $val = '配件订单';
                break;
            case 'KPRK-11' :
                $val = '维修申请单';
                break;
            case 'KPRK-12' :
                $val = '鼎利合同';
                break;
            default :
                $val = $thisVal;
                break;
        }
        return $val;
    }

    /**
     * 发票类型返回对应对象类
     * @param $thisVal
     * @return null|string
     */
    function rtTypeClass($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
            case 'KPRK-02' :
                $val = 'projectmanagent_order_order';
                break;
            case 'KPRK-03' :
            case 'KPRK-04' :
                $val = 'engineering_serviceContract_serviceContract';
                break;
            case 'KPRK-05' :
            case 'KPRK-06' :
                $val = 'contract_rental_rentalcontract';
                break;
            case 'KPRK-07' :
            case 'KPRK-08' :
                $val = 'rdproject_yxrdproject_rdproject';
                break;
            case 'KPRK-09' :
                $val = 'contract_other_other';
                break;
            case 'KPRK-10' :
                $val = 'service_accessorder_accessorder';
                break;
            case 'KPRK-11' :
                $val = 'service_repair_repairapply';
                break;
            case 'KPRK-12' :
                $val = 'contract_contract_contract';
                break;
            default :
                $val = $thisVal;
                break;
        }
        return $val;
    }

    /***************************************************************************************************
     * ------------------------------以下为页面模板显示调用方法------------------------------
     *************************************************************************************************/

    /**
     * 发票登记所有，一般用于显示某个开票申请的发票信息
     * @param $rows
     * @return array
     */
    function showapplylist($rows) {
        $str = null; //返回的模板字符串
        $allMoney = 0;
        if ($rows) {
            $i = 0; //列表记录序号
            foreach ($rows as $val) {
                $i++;
                if ($val['isMail']) {
                    $actStr = <<<EOT
                        <a href="?model=finance_invoice_invoice&action=toEditInApply&id=$val[id]&skey=$val[skey_]&remainMoney={remainMoney}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" title="编辑发票登记" class="thickbox">编辑</a>
EOT;
                    $actStr .= " <a href='#' onclick='viewMailInfo(" . $val['id'] . ",\"" . $val['invoiceNo'] . "\",\"" . $val['skey_'] . "\");'>邮寄信息</a>";
                } else {
                    $actStr = <<<EOT
                        <a href="?model=finance_invoice_invoice&action=toEditInApply&id=$val[id]&skey=$val[skey_]&remainMoney={remainMoney}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1000" title="编辑发票登记" class="thickbox">编辑</a>
EOT;
                    $actStr .= "  <a href='#' onclick='addMailInfo(" . $val['id'] . ",\"" . $val['invoiceNo'] . "\",\"" . $val['skey_'] . "\");'>录入邮寄</a>";
                }
                $str .= <<<EOT
						<tr>
							<td><input type="checkbox" name="datacb"  value="$val[id]" onclick="checkOne();"></td>
							<td>$i</td>
							<td><a href="?model=finance_invoice_invoice&action=init&perm=view&id=$val[id]&skey=$val[skey_]&remainMoney={remainMoney}&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=950" title="查看发票登记" class="thickbox">$val[invoiceNo]</a></td>
							<td class="formatMoney">$val[softMoneyCur]</td>
							<td class="formatMoney">$val[hardMoneyCur]</td>
							<td class="formatMoney">$val[repairMoneyCur]</td>
							<td class="formatMoney">$val[serviceMoneyCur]</td>
							<td class="formatMoney">$val[equRentalMoneyCur]</td>
							<td class="formatMoney">$val[spaceRentalMoneyCur]</td>
							<td class="formatMoney">$val[otherMoneyCur]</td>
							
							<td class="formatMoney">$val[dsEnergyChargeCur]</td>
                            <td class="formatMoney">$val[dsWaterRateMoneyCur]</td>
                            <td class="formatMoney">$val[houseRentalFeeCur]</td>
                            <td class="formatMoney">$val[installationCostCur]</td>
							
							<td class="formatMoney">$val[invoiceMoneyCur]</td>
							<td>$val[createName]</td>
							<td>$val[invoiceTime]
                                <input type="hidden" id="invoiceMoney$i" value="$val[invoiceMoneyCur]"/>
                                <input type="hidden" id="isRed$val[id]" value="$val[isRed]"/>
                                <input type="hidden" id="isMail$val[id]" value="$val[isMail]"/>
                            </td>
							<td>
                                $actStr
							</td>
						</tr>
EOT;
                $allMoney = bcadd($allMoney, $val['invoiceMoneyCur'], 2);
            }
        } else {
            $str = '<tr><td colspan="20">暂无相关信息</td></tr>';
        }
        return array($str, $allMoney);
    }

    /**
     * 显示发票归类批量处理页面
     * @param $rows
     * @return null|string
     */
    function showInvoiceBatchDeal($rows) {
        $str = null;
        if ($rows) {
            $i = 0;
            $datadictArr = $this->getDatadicts("CWCPLX");
            foreach ($rows as $val) {
                $i++;
                $productLineStr = $this->getDatadictsStr($datadictArr ['CWCPLX'], $val ['productModel']);
                $str .= <<<EOT
					<tr>
						<td>$val[invoiceNo]</td>
						<td>$val[invoiceTime]</td>
						<td>$val[productName]</td>
						<td class="formatMoney">$val[softMoney]</td>
						<td class="formatMoney">$val[hardMoney]</td>
						<td class="formatMoney">$val[repairMoney]</td>
						<td class="formatMoney">$val[serviceMoney]</td>
						<td class="formatMoney">$val[equRentalMoney]</td>
						<td class="formatMoney">$val[spaceRentalMoney]</td>
						<td class="formatMoney">$val[otherMoney]</td>
						<td>
							<select name="invoice[$i][productModel]">
								$productLineStr
							</select>
							<input type="hidden" name="invoice[$i][id]" value="$val[detailId]"/>
							<input type="hidden" name="invoice[$i][invoiceId]" value="$val[id]"/>
						</td>
					</tr>
EOT;
            }
        }
        return $str;
    }

    /***************************************************************************************************
     * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
     *************************************************************************************************/

    /**
     * 根据开票申请获取发票记录列表
     * @param $applyId
     * @return mixed
     */
    function getInvoicesByApplyId($applyId) {
        $this->setCompany(0);
        $this->searchArr = array("applyId" => $applyId);
        $this->asc = false;
        return parent::list_d();
    }

    /**
     * 根据合同编号获取发票信息
     * @param $contractNumber
     * @return mixed
     */
    function getInvoicesByContractNumber($contractNumber) {
        $this->setCompany(0);
        $this->searchArr = array("contractNumber" => $contractNumber);
        return parent::list_d();
    }

    /**
     * 根据源单id和源单类型获取开票记录
     * @param $objId
     * @param string $objType
     * @return mixed
     */
    function getInvoices_d($objId, $objType = 'KPRK-01') {
        $this->setCompany(0);
        $this->searchArr = array(
            'objId' => $objId,
            'objType' => $objType
        );
        $this->sort = 'c.invoiceTime asc,c.createTime';
        $this->asc = false;
        return parent::list_d();
    }

    /**
     * 根据开票申请id，获取申请单信息
     * @param $applyId
     * @return bool|mixed
     */
    function getInvoiceapply_d($applyId) {
        //获取开票申请信息
        $applyDao = new model_finance_invoiceapply_invoiceapply ();
        $rows = $applyDao->get_d($applyId, 'register');
        //获取申请人部门信息
        $otherDataDao = new model_common_otherdatas();
        $rs = $otherDataDao->getUserDatas($rows['createId'], array('DEPT_ID', 'DEPT_NAME'));
        $rows['deptId'] = $rs['DEPT_ID'];
        $rows['deptName'] = $rs['DEPT_NAME'];

        return $rows;
    }

    /**
     * 重写原生add_d方法
     * @param $object
     * @return bool
     */
    function add_d($object) {
        $codeRuleDao = new model_common_codeRule();
        $otherDataDao = new model_common_otherdatas();

        try {
            $this->start_d();

            //提取开票详细
            $invoiceDetail = isset($object['invoiceDetail']) ? $object['invoiceDetail'] : null;
            unset($object['invoiceDetail']);

            //获取邮寄记录
            $emailArr = $object['email'];
            unset($object['email']);

            // 如果是销售合同申请的开票, 邮件接收人添加合同负责人 PMS 651
            if(isset($object['objType']) && $object['objType'] == "KPRK-12"){
                $conDao = new model_contract_contract_contract();
                $conArr = $conDao->get_d($object['objId']);
                $toDoIdArr = explode(",",$emailArr['TO_ID']);
                if($conArr){
                    if(!in_array($conArr['prinvipalId'],$toDoIdArr)){
                        $emailArr['TO_NAME'] .= ($emailArr['TO_NAME'] == "")? $conArr['prinvipalName'] : ",".$conArr['prinvipalName'];
                        $emailArr['TO_ID'] .= ($emailArr['TO_ID'] == "")? $conArr['prinvipalId'] : ",".$conArr['prinvipalId'];
                    }
                }
            }

            //开票核销部分 create on 2013-08-14 by kuangzw
            if (isset($object['incomeCheck'])) {
                $incomeCheck = $object['incomeCheck'];
                unset($object['incomeCheck']);
            }

            //数据字典处理
            $object = $this->processDatadict($object);

            // 非人民币时处理
            if (!empty($invoiceDetail) && $object['currency'] != '人民币') {
                $invoiceDetailCur = $invoiceDetail; // 对应货币数据
                $this->dealCurrency_d($object, $invoiceDetail); // 本币数据;
            } else {
                $this->dealLocal_d($object); // 本币数据;
            }

            //开票记录编号
            $object['invoiceCode'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'KPJL');

            //申请人区域设置
            if (!isset($object['salemanArea']) || empty($object['salemanArea'])) {
                $userInfo = $otherDataDao->getUserDatas($object['salesmanId']);
                $object['salemanArea'] = $userInfo['areaname'];
            }
            if (!isset($object['managerId']) || $object['managerId'] == "") {
                $managerArr = $otherDataDao->getManager($object['salesmanId']);
                if ($managerArr) {
                    $object['managerId'] = $managerArr['managerId'];
                    $object['managerName'] = $managerArr['managerName'];
                }
            }

            $id = parent::add_d($object, true);

            //添加发票详细
            if (!empty($invoiceDetail)) {
                $invoiceDetailDao = new model_finance_invoice_invoiceDetail(); // 开票内容实例化
                $invoiceDetailDao->createBatch($invoiceDetail, array('invoiceId' => $id));

                // 非人民币时处理
                if ($object['currency'] != '人民币') {
                    $invoiceDetailCurDao = new model_finance_invoice_invoiceDetailCur();
                    $invoiceDetailCurDao->createBatch($invoiceDetailCur, array('invoiceId' => $id));
                }
            }

            // 如果不存在核销记录且分摊对象为合同，则系统去自动匹配可核销付款条件
            if (!empty($invoiceDetail) && empty($incomeCheck) && $object['objType'] == 'KPRK-12') {
                //实例化付款条件类
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($object['objId'] => $object['invoiceMoney']), 2, $object['isRed']);
            }
            //核销处理
            if ($incomeCheck) {
                $incomeCheckDao = new model_finance_income_incomecheck();
                $incomeCheck = util_arrayUtil::setArrayFn(
                    array('incomeId' => $id, 'incomeNo' => $object['invoiceCode'], 'incomeType' => 2, 'isRed' => $object['isRed']),
                    $incomeCheck
                );
                $incomeCheckDao->batchDeal_d($incomeCheck);
            }

            // 添加执行轨迹记录
            $proDao = new model_contract_conproject_conproject();
            $tracksDao = new model_contract_contract_tracks();
            $tracksObject = array(
                'contractId' => $object['objId'],//合同ID
                'contractCode'=> $object['objCode'],//合同编号
                'exePortion' => $proDao->getConduleBycid($object['objId']),//合同执行进度
                'modelName'=>'invoiceMoney',
                'operationName'=>'开票记录',
                'result'=>$object['invoiceMoney'],
                'recordTime'=>$object['invoiceTime'],
                'expand1'=>$id,
                'expand2'=>'model_finance_invoice_invoice:add_d',
                'remarks'=>'expand1为发票对应的ID'
            );
            $recordId = $tracksDao->addRecord($tracksObject);

            //业务对象处理方法 - 暂用
            $this->busiessDeal_d($object);

            //发送邮件
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $this->thisMail_d($emailArr, $object);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据主键修改对象
     * @param $object
     * @return bool
     */
    function edit_d($object) {
        try {
            $this->start_d();

            //提取开票详细
            $invoiceDetail = isset($object['invoiceDetail']) ? $object['invoiceDetail'] : null;
            unset($object['invoiceDetail']);

            //获取邮寄记录
            $emailArr = $object['email'];
            unset($object['email']);

            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {
                $orgObj = $this->find(array('id' => $object['id']), null, implode(array_keys($this->infoArr), ','));
            }

            //回款核销部分 create on 2013-08-14 by kuangzw
            if (isset($object['incomeCheck'])) {
                $incomeCheck = $object['incomeCheck'];
                unset($object['incomeCheck']);
            }

            // 非人民币时处理
            if (!empty($invoiceDetail) && $object['currency'] != '人民币') {
                $invoiceDetailCur = $invoiceDetail; // 对应货币数据
                $this->dealCurrency_d($object, $invoiceDetail); // 本币数据;
            } else {
                $this->dealLocal_d($object); // 本币数据;
            }

            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //添加发票详细
            if (!empty($invoiceDetail)) {
                $invoiceDetailDao = new model_finance_invoice_invoiceDetail(); // 开票内容实例化
                $invoiceDetailDao->delete(array('invoiceId' => $object['id']));
                $invoiceDetailDao->createBatch($invoiceDetail, array('invoiceId' => $object['id']));

                // 非人民币时处理
                if ($object['currency'] != '人民币') {
                    $invoiceDetailCurDao = new model_finance_invoice_invoiceDetailCur();
                    $invoiceDetailCurDao->delete(array('invoiceId' => $object['id']));
                    $invoiceDetailCurDao->createBatch($invoiceDetailCur, array('invoiceId' => $object['id']));
                }
            }

            // 如果不存在核销记录且分摊对象为合同，则系统去自动匹配可核销付款条件
            if (!empty($invoiceDetail) && empty($incomeCheck) && $object['objType'] == 'KPRK-12') {
                //实例化付款条件类
                $receiptPlanDao = new model_contract_contract_receiptplan();
                $incomeCheck = $receiptPlanDao->autoInitCheck_d(array($object['objId'] => $object['invoiceMoney']), 2, $object['isRed']);
            }
            //核销处理
            if ($incomeCheck) {
                $incomeCheckDao = new model_finance_income_incomecheck();
                $incomeCheck = util_arrayUtil::setArrayFn(
                    array('incomeId' => $object['id'], 'incomeNo' => $object['invoiceCode'], 'incomeType' => 2, 'isRed' => $object['isRed']),
                    $incomeCheck
                );
                $incomeCheckDao->batchDeal_d($incomeCheck);
            }

            //业务对象处理方法 - 暂用
            $this->busiessDeal_d($object,true);

            //发送邮件
            if ($emailArr['issend'] == 'y' && !empty($emailArr['TO_ID'])) {

                $newObj = $this->find(array('id' => $object['id']), null, implode(array_keys($this->infoArr), ','));

                $str = $this->changeArrToStr_d($orgObj, $newObj);

                $this->thisMailCustom_d($emailArr, $newObj, $str);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     */
    function deletes_d($id) {
        try {
            $this->start_d();
            //查看相关字段
            $obj = $this->find(array('id' => $id), null, 'objId,objType,applyId,invoiceTime,isRed,invoiceType,invoiceMoney');

            //获取核销记录
            $incomeCheckDao = new model_finance_income_incomecheck();
            $incomeCheckArr = $incomeCheckDao->getCheckList_d($id, '2');

            //删除
            $this->deletes($id);

            //重新计算
            $this->sumInoivceApply_d($obj['applyId']);

            //业务对象处理方法 - 暂用
            $this->busiessDeal_d($obj,true);

            //处理核销
            if ($incomeCheckArr) {
                $incomeCheckArr = util_arrayUtil::setArrayFn(
                    array('isDelTag' => 1, 'isRed' => $obj['isRed']),
                    $incomeCheckArr
                );
                $incomeCheckDao->batchDeal_d($incomeCheckArr);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 数组转义后生成字符串
     * @param $orgObj
     * @param $newObj
     * @return string
     */
    function changeArrToStr_d($orgObj, $newObj) {
        //比较数组差异
        $diffArr = array_diff_assoc($orgObj, $newObj);

        $str = "";
        $datadictDao = new model_system_datadict_datadict();
        if (is_array($diffArr)) {
            foreach ($diffArr as $key => $val) {
                if (in_array($key, $this->datadictInfo)) {
                    $newVal = $datadictDao->getDataNameByCode($newObj[$key]);
                    $orgVal = $datadictDao->getDataNameByCode($orgObj[$key]);
                    $str .= $this->infoArr[$key] . ' : ' . $orgVal . ' => ' . $newVal . '<br/>';
                } else {
                    $str .= $this->infoArr[$key] . ' : ' . $orgObj[$key] . ' => ' . $newObj[$key] . '<br/>';
                }
            }
        }
        return $str;
    }

    /**
     * 邮件发送
     * @param $emailArr
     * @param $object
     * @param null $addMsg
     */
    function thisMailCustom_d($emailArr, $object, $addMsg = null) {
        $str = $_SESSION['USERNAME'] . " 修改了发票号码为<font color='blue'>[ " . $object['invoiceNo'] . " ]</font>的开票记录,修改内容如下:<br/><br/>" . $addMsg;

        $emailDao = new model_common_mail();
        $emailDao->mailClear('开票记录', $emailArr['TO_ID'], $str);
    }

    /**
     * 获取开票详细
     * @param $id
     * @param null $perm
     * @return bool|mixed
     */
    function get_d($id, $perm = null) {
        //获取主表信息
        $obj = parent::get_d($id);

        //获取从表信息
        $invoiceDetailDao = new model_finance_invoice_invoiceDetail();
        $detailRow = $invoiceDetailDao->getDetailByInvoiceId($id);

        // 如果币种非人民币，则需要取比别开票内容
        $invoiceDetailCurDao = new model_finance_invoice_invoiceDetailCur();
        $invoiceDetailCurRow = $invoiceDetailCurDao->getDetailByInvoiceId($id);

        //渲染从表
        switch ($perm) {
            case 'view':
                $arr = $invoiceDetailDao->detailView($detailRow);

                // 如果币种非人民币，则需要取比别开票内容
                $invoiceDetailCurShow = $invoiceDetailDao->detailView($invoiceDetailCurRow);
                $obj['invoiceDetailCur'] = $invoiceDetailCurShow[0];
                break;
            case 'register':
                $arr = $invoiceDetailDao->detailEdit($detailRow);
                break;
            case 'red':
                if ($obj['currency'] != '人民币') {
                    $arr = $invoiceDetailDao->detailRed($invoiceDetailCurRow);
                    break;
                } else {
                    $arr = $invoiceDetailDao->detailRed($detailRow);
                    break;
                }
            case 'edit':
                if ($obj['currency'] != '人民币') {
                    $arr = $invoiceDetailDao->detailEdit($invoiceDetailCurRow);
                    // 数组转换
                    list(
                        $obj['invoiceMoney'], $obj['softMoney'], $obj['hardMoney'], $obj['repairMoney'], $obj['serviceMoney'],
                        $obj['equRentalMoney'], $obj['spaceRentalMoney'], $obj['otherMoney']
                        ) =
                        array(
                            $obj['invoiceMoneyCur'], $obj['softMoneyCur'], $obj['hardMoneyCur'], $obj['repairMoneyCur'],
                            $obj['serviceMoneyCur'], $obj['equRentalMoneyCur'], $obj['spaceRentalMoneyCur'], $obj['otherMoneyCur']
                        );
                } else {
                    $arr = $invoiceDetailDao->detailEdit($detailRow);
                }
                break;
            default:
                $arr = $invoiceDetailDao->detailEdit($detailRow);
                break;
        }
        $obj['invoiceDetail'] = $arr[0];
        $obj['invnumber'] = $arr[1];

        return $obj;
    }

    /**
     * 修改发票邮寄状态
     * 0.未邮寄
     * 1.已邮寄
     * @param $id
     * @param $thisVal
     * @return mixed
     */
    function changeMailStatus_d($id, $thisVal) {
        return parent::edit_d(array('id' => $id, 'isMail' => $thisVal));
    }

    /**
     * 验证是否已经存在红字发票
     * @param $id
     * @return bool|mixed
     */
    function hasRedInvoice_d($id) {
        return $this->find(array('belongId' => $id));
    }

    /**
     * 重新计算开票申请的开票金额
     * @param $applyId
     * @return bool
     */
    function sumInoivceApply_d($applyId) {
        $this->setCompany(0);
        $this->searchArr = array('applyId' => $applyId, 'isRed' => 0);
        $rows = $this->list_d('sumApplyMoney');
        $invoiceMoney = $rows[0]['invoiceMoney'] != 0 ? $rows[0]['invoiceMoney'] : 0;

        $applyDao = new model_finance_invoiceapply_invoiceapply();
        $applyDao->updateField(array('id' => $applyId), 'payedAmount', $invoiceMoney);

        return true;
    }

    /**
     * 获取合同的开票单据金额
     * @param $objCode
     * @param $type
     * @return array $invoiceMoney
     */
    function sumMoneyByObjCode_d($objCode, $type) {
        $invoiceMoney = '';
        if($objCode != ''){
            $sum_condition = '';
            switch($type){
               case 'red':
                   $sum_sql = "SUM(c.invoiceMoney) AS invoiceMoney";
                   $sum_condition = "and c.isRed = 1";
                   break;
               case 'blue':
                   $sum_sql = "SUM(c.invoiceMoney) AS invoiceMoney";
                   $sum_condition = "and c.isRed = 0";
                   break;
               default:
                   $sum_sql = "SUM(IF(isRed = 0,c.invoiceMoney,-c.invoiceMoney)) AS invoiceMoney";
                   break;
           }
           $sql = "SELECT
						".$sum_sql."
				    FROM
					    oa_finance_invoice c
					WHERE
						c.objCode = '".$objCode."' ".$sum_condition;
           $invoiceMoney = $this->_db->getArray($sql);
           $invoiceMoney[0]['sql'] = $sql;
        }
        return $invoiceMoney;
    }
    /**
     * 删除红字开票 - 并且计算存在对应开票申请的退票金额
     * @param $id
     * @param $belongId
     * @return bool
     */
    function deletesRed_d($id, $belongId) {
        try {
            $this->start_d();

            //删除记录
            $this->deletes($id);

            $blueObj = $this->find(array('id' => $belongId), null, 'applyId');

            //重新计算
            $this->sumInoivceApply_d($blueObj['applyId']);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->commit_d();
            return false;
        }
    }

    /**
     * 重新计算开票申请的退票金额
     * @param $applyId
     * @return bool
     */
    function sumReturnMoney_d($applyId) {
        $returnMoney = $this->get_table_fields($this->tbl_name, "applyId = '$applyId' and isRed = 1", 'sum(invoiceMoney)');

        $applyDao = new model_finance_invoiceapply_invoiceapply();
        $applyDao->updateField(array('id' => $applyId), 'returnMoney', $returnMoney);

        return true;
    }

    /**
     * 邮件发送
     * @param $emailArr
     * @param $object
     * @param string $thisAct
     */
    function thisMail_d($emailArr, $object, $thisAct = '新增') {
        $datadictDao = new model_system_datadict_datadict();
        $invoiceType = $datadictDao->getDataNameByCode($object['invoiceType']);
        $addMsg = '已经开出 : ' . $invoiceType . ',<br/>发票号码 ： ' . $object['invoiceNo'] . ',<br/>发票金额为：' . $object['invoiceMoney'] . ',<br/>开票单位为：' . $object['invoiceUnitName'];
		if($object['objType'] == 'KPRK-12'){// 如果是鼎利合同，带出合同名称，合同号
			$conDao = new model_contract_contract_contract();
			$rs = $conDao->find(array('id' => $object['objId']),null,'contractCode,contractName');
			if(!empty($rs)){
				$addMsg .= ',<br/>合同 : '.$rs['contractName'].'('.$rs['contractCode'].')';
			}
		}
        $emailDao = new model_common_mail();
        $emailDao->batchEmail($emailArr['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, $thisAct, $object['invoiceNo'], $emailArr['TO_ID'], $addMsg, '1');
    }

    /**
     *  获取默认邮件发送人
     */
    function getSendMen_d() {
        include(WEB_TOR . "model/common/mailConfig.php");
        return isset($mailUser[$this->tbl_name][0]) ? $mailUser[$this->tbl_name][0] : array('sendUserId' => '',
            'sendName' => '');
    }

    /*****************************开票管理页面*****************************/
    /**
     * 在详细条目整合到GRID中
     * @param $object
     * @param int $dealType
     * @param null $selectSql
     * @return array|mixed
     */
    public function rebuildList_d($object, $dealType = 1, $selectSql = null) {
        //获取数组内id，组成id串
        $ids = "";

        foreach ($object as $key => $val) {
            if ($key == 0) {
                $ids .= $val['id'];
            } else {
                $ids .= ',' . $val['id'];
            }
        }

        $invoiceDetailDao = new model_finance_invoice_invoiceDetail();
        //根据id串查找开票详细信息
        $detailRow = $invoiceDetailDao->getDetailByGroup_d($ids);
        //把数组转成key=>array 形式
        $detailRow = $this->changeIdForArr_d($detailRow);

        //组合数组
        $object = $this->mergeArr_d($object, $detailRow);

        if ($dealType == 1) {
            //统计金额，并且生成统计列
            $object = $this->countRows_d($object, $selectSql);
        }
        return $object;
    }

    /**
     * 重构列表
     * 不整合条目，主要获取合同信息
     * @param $object
     * @param int $dealType
     * @param null $selectSql
     * @return array
     */
    public function rebuildList2_d($object, $dealType = 1, $selectSql = null) {
        if ($dealType == 1) {
            //统计金额，并且生成统计列
            $object = $this->countRows_d($object, $selectSql);
        }
        return $object;
    }

    /**
     * 重构列表
     * 在详细条目整合到GRID中
     * @param $object
     * @return mixed
     */
    public function rebuildListNotMerge_d($object) {
        $customerDao = new model_customer_customer_customer();
        $customerArr = array();
        $dataArr = null;
        foreach ($object as $key => $val) {

            if (empty($val['customerType']) || empty($val['customerProvince'])) {
                if (isset($customerArr[$val['invoiceUnitId']])) {
                    $object[$key]['customerType'] = $customerArr[$val['invoiceUnitId']]['TypeOne'];
                    $object[$key]['customerProvince'] = $customerArr[$val['invoiceUnitId']]['Prov'];
                } else {
                    $customerArr[$val['invoiceUnitId']] = $customerDao->find(array('id' => $val['invoiceUnitId']), 'TypeOne,Prov');
                    $object[$key]['customerType'] = $customerArr[$val['invoiceUnitId']]['TypeOne'];
                    $object[$key]['customerProvince'] = $customerArr[$val['invoiceUnitId']]['Prov'];
                }
            }

            if (!isset($dataArr[$object[$key]['invoiceType']])) {
                $dataArr = $this->datadictArrSearch_d($dataArr, $object[$key]['invoiceType']);
            }
            $object[$key]['invoiceType'] = $dataArr[$object[$key]['invoiceType']];


            if (!isset($dataArr[$object[$key]['customerType']])) {
                $dataArr = $this->datadictArrSearch_d($dataArr, $object[$key]['customerType']);
            }
            $object[$key]['customerType'] = $dataArr[$object[$key]['customerType']];
        }

        return $object;
    }

    /**
     * 数据字典查询方法
     * @param $dataArr
     * @param $dataCode
     * @return mixed
     */
    function datadictArrSearch_d($dataArr, $dataCode) {
        $datadictDao = new model_system_datadict_datadict();
        $rtName = $datadictDao->getDataNameByCode($dataCode);
        $dataArr[$dataCode] = $rtName;
        return $dataArr;
    }

    /**
     * 整合数组,输出格式为:
     * array( 'id' => array(0 = '设备名称1.设备名称2' ， 1 => '数量') ）
     * @param $object
     * @return array
     */
    private function changeIdForArr_d($object) {
        $objArr = array();
        foreach ($object as $val) {
            $objArr[$val['invoiceId']]['productName'] = $val['productName'];
            $objArr[$val['invoiceId']]['amount'] = $val['amount'];
            $objArr[$val['invoiceId']]['psType'] = $val['psType'];
        }
        return $objArr;
    }

    /**
     * 合并主表和从表数组
     * @param $object
     * @param $objectDetail
     * @return mixed
     */
    private function mergeArr_d($object, $objectDetail) {
        foreach ($object as $key => $val) {
            if (isset($objectDetail[$val['id']])) {
                $object[$key] = array_merge($object[$key], $objectDetail[$val['id']]);
            }
        }
        return $object;
    }

    /**
     * 统计金额，并生成统计列
     * @param $object
     * @param $selectSql
     * @return array
     */
    private function countRows_d($object, $selectSql) {
        $thisPageArr = array(
            'id' => 'noId2',
            'amount' => 0,
            'softMoney' => 0,
            'hardMoney' => 0,
            'repairMoney' => 0,
            'serviceMoney' => 0,
            'equRentalMoney' => 0,
            'spaceRentalMoney' => 0,
            'otherMoney' => 0,
            'dsEnergyCharge' => 0,
            'dsWaterRateMoney' => 0,
            'houseRentalFee' => 0,
            'installationCost' => 0,
            'invoiceMoney' => 0
        );
        //本页统计合计
        if (is_array($object)) {
            foreach ($object as $val) {
                $thisPageArr['amount'] = bcadd($thisPageArr['amount'], $val['allAmount'], 2);
                $thisPageArr['softMoney'] = bcadd($thisPageArr['softMoney'], $val['softMoney'], 2);
                $thisPageArr['hardMoney'] = bcadd($thisPageArr['hardMoney'], $val['hardMoney'], 2);
                $thisPageArr['repairMoney'] = bcadd($thisPageArr['repairMoney'], $val['repairMoney'], 2);
                $thisPageArr['serviceMoney'] = bcadd($thisPageArr['serviceMoney'], $val['serviceMoney'], 2);
                $thisPageArr['equRentalMoney'] = bcadd($thisPageArr['equRentalMoney'], $val['equRentalMoney'], 2);
                $thisPageArr['spaceRentalMoney'] = bcadd($thisPageArr['spaceRentalMoney'], $val['spaceRentalMoney'], 2);
                $thisPageArr['otherMoney'] = bcadd($thisPageArr['otherMoney'], $val['otherMoney'], 2);
                $thisPageArr['dsEnergyCharge'] = bcadd($thisPageArr['dsEnergyCharge'], $val['dsEnergyCharge'], 2);
                $thisPageArr['dsWaterRateMoney'] = bcadd($thisPageArr['dsWaterRateMoney'], $val['dsWaterRateMoney'], 2);
                $thisPageArr['houseRentalFee'] = bcadd($thisPageArr['houseRentalFee'], $val['houseRentalFee'], 2);
                $thisPageArr['installationCost'] = bcadd($thisPageArr['installationCost'], $val['installationCost'], 2);
                $thisPageArr['invoiceMoney'] = bcadd($thisPageArr['invoiceMoney'], $val['invoiceMoney'], 2);

            }
        }
        //查询记录合计
        unset($this->sort);
        $objArr = $this->listBySqlId($selectSql . '_sum');
        if (is_array($objArr)) {
            $rsArr = $objArr[0];
            $rsArr['thisAreaName'] = '合计';
            $rsArr['id'] = 'noId';
        } else {
            $rsArr = array(
                'id' => 'noId',
                'amount' => 0,
                'softMoney' => 0,
                'hardMoney' => 0,
                'repairMoney' => 0,
                'serviceMoney' => 0,
                'equRentalMoney' => 0,
                'spaceRentalMoney' => 0,
                'otherMoney' => 0,
                'dsEnergyCharge' => 0,
                'dsWaterRateMoney' => 0,
                'houseRentalFee' => 0,
                'installationCost' => 0,
                'invoiceMoney' => 0
            );
        }

        $object[] = $thisPageArr;
        $object[] = $rsArr;
        return $object;
    }

    /*****************************开票管理页面*****************************/

    /***************************开票额度预览部分*******************************/
    /**
     * 获取开票预览
     */
    function getYearPlan_d() {
        $yearPlan = new model_finance_invoice_yearPlan();
        return $yearPlan->getYearPlan_d();
    }

    /**
     * 开票额度预览
     * @param $object
     * @return array
     */
    public function getInvoicePerView_d($object) {
        //年
        $year = $object['year'];

        //开票类型
        $objType = $this->rtPostVla($object['objType']);

        //本年累计开票金额 (按类型分)
        $allInvoiceRows = $this->getAllInvoiceInTheYear_d($year, $objType);

        //季度开票金额 － 根据开票类型分组 (按类型分)
        $invoicedGroupArr = $this->invoicedByType_d($year);

        //转化数组为季度未开金额
        $quarterRows = $this->quarterCanInvoice_d($invoicedGroupArr, $object);

        //合并未开金额
        $object = array_merge($object, $quarterRows);

        //合并累计开票金额
        $object = array_merge($object, $allInvoiceRows);

        //季度已开金额 - 统计季度金额
        $object['quarterArr'] = $this->getInvoiced_d($year, $objType);

        return $object;
    }

    /**
     * 获取全年开票金额
     * @param $year
     * @param null $objType
     * @return mixed
     */
    public function getAllInvoiceInTheYear_d($year, $objType = null) {
        $this->searchArr = array();
        $this->searchArr['year'] = $year;
        if ($objType) {
            $this->searchArr['objTypes'] = $objType;
        }
        $rows = $this->listBySqlId('allInvoiceInTheYear');
        return $rows[0];
    }

    /**
     * 获取季度已开金额
     * @param $year
     * @param null $objType
     * @return mixed
     */
    public function getInvoiced_d($year, $objType = null) {
        $this->searchArr = array();
        $this->searchArr['year'] = $year;
        if ($objType) {
            $this->searchArr['objTypes'] = $objType;
        }
        $this->groupBy = 'QUARTER(c.invoiceTime)';
        $this->sort = 'QUARTER(c.invoiceTime)';
        $this->asc = false;
        return $this->listBySqlId('invoiced');
    }

    /**
     * 季度开票数组转换HTML
     * @param $object
     * @return null|string
     */
    public function showQuarterList($object) {
        $i = 0;
        $str = null;
        $tempCode = null;
        foreach ($object as $val) {
            $i++;
            $trClass = ($i % 2) == 1 ? 'tr_odd' : 'tr_even';
            $tempCode = $this->chCode[$val['thisQuarter']];
            $str .= <<<EOT
				<tr class="$trClass">
		        	<td>第{$tempCode}季度</td>
		        	<td class="formatMoney">{$val['softMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['hardMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['repairMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['serviceMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['equRentalMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['spaceRentalMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['otherMoney_Quarter']}</td>
		        	<td class="formatMoney">{$val['money_Quarter']}</td>
	        	</tr>
EOT;
        }
        return $str;
    }

    /**
     * 季度已开金额 依据开票类型分组
     * @param $year
     * @return mixed
     */
    public function invoicedByType_d($year) {
        $this->searchArr = array();
        $this->searchArr['year'] = $year;
        $this->groupBy = 'QUARTER(c.invoiceTime),c.invoiceType ';
        $this->sort = 'QUARTER(c.invoiceTime)';
        $this->asc = false;
        return $this->listBySqlId('invoicedByType');
    }

    /**
     * @param $object
     * @param $planObject
     * @return array
     */
    private function quarterCanInvoice_d($object, $planObject) {
        $tmp = null;
        $rs = array(
            'salesCan_QuarterOne' => $planObject['salesOne'], 'serviceCan_QuarterOne' => $planObject['serviceOne'],
            'salesCan_QuarterTwo' => $planObject['salesTwo'], 'serviceCan_QuarterTwo' => $planObject['serviceTwo'],
            'salesCan_QuarterThree' => $planObject['salesThree'], 'serviceCan_QuarterThree' => $planObject['serviceThree'],
            'salesCan_QuarterFour' => $planObject['salesFour'], 'serviceCan_QuarterFour' => $planObject['serviceFour']
        );
        foreach ($object as $val) {
            if ($val['invoiceType'] == 'normal' || $val['invoiceType'] == 'added') {
                $tmp = 'salesCan_Quarter' . $this->egCode[$val['thisQuarter']];
                $rs[$tmp] = bcsub($rs[$tmp], $val['money_Quarter'], 2);
            } else {
                $tmp = 'serviceCan_Quarter' . $this->egCode[$val['thisQuarter']];
                $rs[$tmp] = bcsub($rs[$tmp], $val['money_Quarter'], 2);

            }
        }
        return $rs;
    }

    /***************************开票额度预览部分*******************************/

    /**************************************开票导入部分*********************************/
    /**
     * 开票导入功能
     * @param int $isCheck
     */
    function addExecelData_d($isCheck = 1) {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//结果数组
        $contArr = array();//合同信息数组
        $contDao = new model_contract_contract_contract();
        $customerArr = array();//客户信息数组
        $customerDao = new model_customer_customer_customer();
        $datadictArr = array();//客户信息数组
        $datadictDao = new model_system_datadict_datadict();
        $userArr = array();//合同信息数组
        $otherDataDao = new model_common_otherdatas();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                //行数组循环
                foreach ($excelData as $key => $val) {
                    $actNum = $key + 2;
                    if (empty($val[0]) && empty($val[1]) && empty($val[2]) && empty($val[3]) && empty($val[4]) && empty($val[5]) && empty($val[6]) && empty($val[7]) && empty($val[8]) && empty($val[9]) && empty($val[10]) && empty($val[11]) && empty($val[12])) {
                        continue;
                    } else {
                        if (!empty($val[0])) {
                            $val[0] = trim($val[0]);
                            //判断单据日期
                            $invoiceTime = date('Y-m-d', (mktime(0, 0, 0, 1, $val[0] - 1, 1900)));
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有开票日期';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[1])) {
                            $val[1] = trim($val[1]);
                            //客户名称
                            if (!isset($customerArr[$val[1]])) {
                                $rs = $customerDao->findCus($val[1]);
                                if (is_array($rs)) {
                                    $customerId = $customerArr[$val[1]]['id'] = $rs[0]['id'];
                                    $prov = $customerArr[$val[1]]['prov'] = $rs[0]['Prov'];
                                    $customerName = $val[1];
                                    $customerType = $customerArr[$val[1]]['typeOne'] = $rs[0]['TypeOne'];
                                    $areaName = $customerArr[$val[1]]['areaName'] = $rs[0]['AreaName'];
                                    $areaId = $customerArr[$val[1]]['areaId'] = $rs[0]['AreaId'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '插入失败!客户系统中不存在此客户';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $customerId = $customerArr[$val[1]]['id'];
                                $prov = $customerArr[$val[1]]['prov'];
                                $customerType = $customerArr[$val[1]]['TypeOne'];
                                $areaName = $customerArr[$val[1]]['areaName'];
                                $areaId = $customerArr[$val[1]]['areaId'];
                                $customerName = $val[1];
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有客户名称';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if ($isCheck == 1) {//类型一：需要验证合同号
                            if (!empty($val[2])) {
                                $val[2] = trim($val[2]);
                                //产生合同缓存数组
                                $contArr[$val[2]] = isset($contArr[$val[2]]) ? $contArr[$val[2]] : $contDao->getContractInfoByCode($val[2], 'main');
                                if (is_array($contArr[$val[2]])) {
                                    $orderId = $contArr[$val[2]]['id'];
                                    $rObjCode = $contArr[$val[2]]['objCode'];
                                    $orderCode = $val[2];
                                    $managerName = $contArr[$val[2]]['areaPrincipal'];
                                    $managerId = $contArr[$val[2]]['areaPrincipalId'];
                                    $areaName = $contArr[$val[2]]['areaName'];
                                    $areaId = $contArr[$val[2]]['areaCode'];
                                    $orderType = 'KPRK-12';

                                    if (empty($managerName)) {
                                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                                        $tempArr['result'] = '插入失败!对应合同中没有对应的区域负责人';
                                        array_push($resultArr, $tempArr);
                                        continue;
                                    }
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '插入失败!不存在的合同号';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '插入失败!没有合同号';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {//类型一：不需要验证关联号号
                            $orderCode = trim($val[2]);
                            $orderId = $orderType = $rObjCode = $managerName = $managerId = "";
                        }

                        /**
                         * 开票项目不做判断
                         */
                        if (!empty($val[3])) {
                            $val[3] = trim($val[3]);
                            $invoiceProduct = $val[3];
                            if (!isset($datadictArr[$val[3]])) {
                                $rs = $datadictDao->getCodeByName('KPXM', $val[3]);
                                if (!empty($rs)) {
                                    $invoiceProductCode = $datadictArr[$val[3]]['code'] = $rs;
                                } else {
                                    $invoiceProductCode = '';
                                }
                            } else {
                                $invoiceProductCode = $datadictArr[$val[3]]['code'];
                            }
                            //开票项目
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有开票项目';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //产品服务类型
                        if (!empty($val[4])) {
                            $val[4] = trim($val[4]);
                            if (!isset($datadictArr[$val[4]])) {
                                $rs = $datadictDao->getCodeByName('CPFWLX', $val[4]);
                                if (!empty($rs)) {
                                    $invoiceDetailType = $datadictArr[$val[4]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '插入失败!不存在的开票产品/服务类型';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $invoiceDetailType = $datadictArr[$val[4]]['code'];
                            }
                            //开票项目类型
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有开票产品/服务类型';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //开票金额
                        if (!empty($val[9])) {
                            //开票金额
                            $invoiceMoney = trim($val[9]);

                            if ($invoiceMoney >= 0) {
                                $isRed = 0;
                            } else {
                                $isRed = 1;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有开票合计金额或合计金额为0';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $invoiceMoney = abs($invoiceMoney);
                        $softMoney = empty($val[5]) ? 0 : sprintf("%f", abs(trim($val[5])));
                        $hardMoney = empty($val[6]) ? 0 : sprintf("%f", abs(trim($val[6])));
                        $repairMoney = empty($val[7]) ? 0 : sprintf("%f", abs(trim($val[7])));
                        $serviceMoney = empty($val[8]) ? 0 : sprintf("%f", abs(trim($val[8])));

                        $equRentalMoney = empty($val[14]) ? 0 : sprintf("%f", abs(trim($val[14])));
                        $spaceRentalMoney = empty($val[15]) ? 0 : sprintf("%f", abs(trim($val[15])));
                        $otherMoney = empty($val[16]) ? 0 : sprintf("%f", abs(trim($val[16])));

                        $dsEnergyCharge = empty($val[17]) ? 0 : sprintf("%f", abs(trim($val[17])));
                        $dsWaterRateMoney = empty($val[18]) ? 0 : sprintf("%f", abs(trim($val[18])));
                        $houseRentalFee = empty($val[19]) ? 0 : sprintf("%f", abs(trim($val[19])));
                        $installationCost = empty($val[20]) ? 0 : sprintf("%f", abs(trim($val[20])));

                        $softAndHard = bcadd($softMoney, $hardMoney, 2);
                        $repairAndService = bcadd($repairMoney, $serviceMoney, 2);
                        $equAndSpace = bcadd($equRentalMoney, $spaceRentalMoney, 2);
                        $thisAll = bcadd($repairAndService, $softAndHard, 2);
                        $thisAll = bcadd($thisAll, $equAndSpace, 2);
                        $thisAll = bcadd($thisAll, $otherMoney, 2);

                        $thisAll = bcadd($thisAll, $dsEnergyCharge, 2);
                        $thisAll = bcadd($thisAll, $dsWaterRateMoney, 2);
                        $thisAll = bcadd($thisAll, $houseRentalFee, 2);
                        $thisAll = bcadd($thisAll, $installationCost, 2);
                        if ($thisAll != $invoiceMoney) {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!总金额和分项金额不相等';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        //不判断发票号码
                        $invoiceNo = trim($val[10]);

                        //不判断数量
                        $amount = trim($val[11]);

                        if (!empty($val[12])) {
                            $val[12] = trim($val[12]);
                            if (!isset($datadictArr[$val[12]])) {
                                $rs = $datadictDao->getCodeByName('XSFP', $val[12]);
                                if (!empty($rs)) {
                                    $invoiceType = $datadictArr[$val[12]]['code'] = $rs;
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '插入失败!不存在的发票类型';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $invoiceType = $datadictArr[$val[12]]['code'];
                            }
                            //开票项目类型
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有填发票类型';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[13])) {//业务员
                            $val[13] = trim($val[13]);
                            if (!isset($userArr[$val[13]])) {
                                $rs = $otherDataDao->getUserInfo($val[13]);
                                if (!empty($rs)) {
                                    $userArr[$val[13]] = $rs;
                                    $salesmanId = $userArr[$val[13]]['USER_ID'];
                                    $deptId = $userArr[$val[13]]['DEPT_ID'];
                                    $deptName = $userArr[$val[13]]['DEPT_NAME'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '更新失败!不存在的业务员名称';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $salesmanId = $userArr[$val[13]]['USER_ID'];
                                $deptId = $userArr[$val[13]]['DEPT_ID'];
                                $deptName = $userArr[$val[13]]['DEPT_NAME'];
                            }
                            $salesman = $val[13];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有填写业务员名称';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (empty($val[11])) {
                            $allAmount = 0;
                        } else {
                            $allAmount = $val[11];
                        }

                        //归属公司
                        if (!empty($val[21])) {
                            $businessBelongName = trim($val[21]);
                            $branchDao = new model_deptuser_branch_branch();
                            $branchObj = $branchDao->find(array('NameCN' => $businessBelongName));
                            if (!empty($branchObj)) {
                                $businessBelong = $branchObj['NamePT'];
                                $formBelong = $branchObj['NamePT'];
                                $formBelongName = $branchObj['NameCN'];
                            } else {
                                $tempArr['docCode'] = '第' . $actNum . '条数据';
                                $tempArr['result'] = '插入失败!不存在该归属公司';
                                array_push($resultArr, $tempArr);
                                continue;
                            }
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '插入失败!没有填归属公司';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $inArr = array(
                            'invoiceNo' => $invoiceNo,
                            'invoiceTime' => $invoiceTime,
                            'invoiceUnitName' => $customerName,
                            'invoiceUnitId' => $customerId,
                            'contractUnitName' => $customerName,
                            'contractUnitId' => $customerId,
                            'invoiceType' => $invoiceType,
                            'isRed' => $isRed,
                            'objId' => $orderId,
                            'objCode' => $orderCode,
                            'objType' => $orderType,
                            'rObjCode' => $rObjCode,
                            'invoiceMoney' => $invoiceMoney,
                            'softMoney' => $softMoney,
                            'hardMoney' => $hardMoney,
                            'repairMoney' => $repairMoney,
                            'serviceMoney' => $serviceMoney,
                            'equRentalMoney' => $equRentalMoney,
                            'spaceRentalMoney' => $spaceRentalMoney,
                            'otherMoney' => $otherMoney,
                            'salesmanId' => $salesmanId,
                            'salesman' => $salesman,
                            'deptId' => $deptId,
                            'deptName' => $deptName,
                            'managerName' => $managerName,
                            'managerId' => $managerId,
                            'remark' => '系统导入数据',
                            'invoiceContent' => $val[3],
                            'psType' => $val[4],
                            'allAmount' => $allAmount,
                            'invoiceUnitType' => $customerType,
                            'invoiceUnitProvince' => $prov,
                            'areaName' => $areaName,
                            'areaId' => $areaId,
                            'businessBelongName' => $businessBelongName,
                            'businessBelong' => $businessBelong,
                            'formBelongName' => $formBelongName,
                            'formBelong' => $formBelong,
                            'currency' => '人民币',
                            'rate' => 1,
                            'invoiceDetail' => array(
                                array(
                                    'productName' => $invoiceProduct,
                                    'productId' => $invoiceProductCode,
                                    'amount' => $amount,
                                    'psType' => $invoiceDetailType,
                                    'softMoney' => $softMoney,
                                    'hardMoney' => $hardMoney,
                                    'serviceMoney' => $serviceMoney,
                                    'repairMoney' => $repairMoney,
                                    'equRentalMoney' => $equRentalMoney,
                                    'spaceRentalMoney' => $spaceRentalMoney,
                                    'otherMoney' => $otherMoney,
                                    'dsEnergyCharge' => $dsEnergyCharge,
                                    'dsWaterRateMoney' => $dsWaterRateMoney,
                                    'houseRentalFee' => $houseRentalFee,
                                    'installationCost' => $installationCost
                                )
                            )
                        );

                        try {
                            $this->start_d();

                            //数据插入
                            $this->add_d($inArr);

                            $this->commit_d();
                            $tempArr['result'] = '插入成功';
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                        } catch (Exception $e) {
                            $this->rollBack();
                            $tempArr['result'] = '插入失败';
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                        }
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    /**
     * 开票导入更新功能
     */
    function editExecelData_d() {
        set_time_limit(0);
        $filename = $_FILES ["inputExcel"] ["name"];
        $temp_name = $_FILES ["inputExcel"] ["tmp_name"];
        $fileType = $_FILES ["inputExcel"] ["type"];
        $resultArr = array();//结果数组
        $userArr = array();//合同信息数组
        $otherDataDao = new model_common_otherdatas();
        //判断导入类型是否为excel表
        if ($fileType == "application/vnd.ms-excel" || $fileType == "application/octet-stream") {
            $excelData = util_excelUtil::upReadExcelDataClear($filename, $temp_name);
            spl_autoload_register("__autoload");
            if (is_array($excelData)) {
                foreach ($excelData as $key => $val) {
                    $val[0] = trim($val[0]);
                    $val[1] = trim($val[1]);
                    $actNum = $key + 2;

                    if (empty($val[0]) && empty($val[1])) {
                        continue;
                    } else {
                        if (!empty($val[0])) {
                            $invoiceNo = $val[0];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写发票号码';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        if (!empty($val[1])) {
                            if (!isset($userArr[$val[1]])) {
                                $rs = $otherDataDao->getUserInfo($val[1]);
                                if (!empty($rs)) {
                                    $userArr[$val[1]] = $rs;
                                    $salesmanId = $userArr[$val[1]]['USER_ID'];
                                    $deptId = $userArr[$val[1]]['DEPT_ID'];
                                    $deptName = $userArr[$val[1]]['DEPT_NAME'];
                                } else {
                                    $tempArr['docCode'] = '第' . $actNum . '条数据';
                                    $tempArr['result'] = '更新失败!不存在的业务员名称';
                                    array_push($resultArr, $tempArr);
                                    continue;
                                }
                            } else {
                                $salesmanId = $userArr[$val[1]]['USER_ID'];
                                $deptId = $userArr[$val[1]]['DEPT_ID'];
                                $deptName = $userArr[$val[1]]['DEPT_NAME'];
                            }
                            $salesman = $val[1];
                        } else {
                            $tempArr['docCode'] = '第' . $actNum . '条数据';
                            $tempArr['result'] = '更新失败!没有填写业务员名称';
                            array_push($resultArr, $tempArr);
                            continue;
                        }

                        $updateRows = array(
                            'invoiceNo' => $invoiceNo,
                            'salesman' => $salesman,
                            'salesmanId' => $salesmanId,
                            'deptId' => $deptId,
                            'deptName' => $deptName
                        );
                        $conditionArr = array(
                            'invoiceNo' => $invoiceNo
                        );
                        $this->update($conditionArr, $updateRows);
                        if ($this->_db->affected_rows() == 0) {
                            $tempArr['result'] = '更新成功，但更新的数据条数为0';
                        } else {
                            $tempArr['result'] = '更新成功';
                        }
                        $tempArr['docCode'] = '第' . $actNum . '条数据';
                        array_push($resultArr, $tempArr);
                    }
                }
                return $resultArr;
            } else {
                msg("文件不存在可识别数据!");
            }
        } else {
            msg("上传文件类型不是EXCEL!");
        }
    }

    /**
     * 合同类型转换
     * @param $val
     * @param $thisType
     * @return string
     */
    function changeContType_d($val, $thisType) {
        if ($thisType == 1) {
            switch ($val) {
                case 'oa_sale_order':
                    return 'KPRK-01';
                    break;
                case 'oa_sale_service':
                    return 'KPRK-03';
                    break;
                case 'oa_sale_lease':
                    return 'KPRK-05';
                    break;
                case 'oa_sale_rdproject':
                    return 'KPRK-07';
                    break;
            }
        } else {
            switch ($val) {
                case 'oa_sale_order':
                    return 'KPRK-02';
                    break;
                case 'oa_sale_service':
                    return 'KPRK-04';
                    break;
                case 'oa_sale_lease':
                    return 'KPRK-06';
                    break;
                case 'oa_sale_rdproject':
                    return 'KPRK-08';
                    break;
            }
        }
    }

    /**
     * 获取开票信息和开票申请信息
     * @param $id
     * @return mixed
     */
    function getInvoiceAndApply_d($id) {
        $this->setCompany(0);
        $this->searchArr['id'] = $id;
        $rows = $this->listBySqlId('invoiceAndApply');
        return $rows[0];
    }

    /*************************发票归类*************************/
    /**
     * 发票归类批量处理
     * @param $object
     * @return bool
     */
    function batchDealAct_d($object) {
        try {
            $this->start_d();

            $idArr = array();

            $invoiceDetailDao = new model_finance_invoice_invoiceDetail();
            foreach ($object as $val) {
                $invoiceDetailDao->edit_d($val);
                array_push($idArr, $val['invoiceId']);
            }

            $idArr = array_unique($idArr);
            $this->changeStatus_d(implode($idArr, ','));

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 发票状态修改
     * @param $ids
     * @param int $status
     * @return mixed
     */
    function changeStatus_d($ids, $status = 1) {
        return $this->_db->query("update oa_finance_invoice set status = $status where id in ( $ids )");
    }

    /**
     * 根据源单号，源单类型，发票Id
     * @param $objId
     * @param $type
     * @return bool|mixed
     */
    function getInvId_d($objId, $type) {
        $conditions = array(
            'objId' => $objId,
            'objType' => $type
        );
        return $this->find($conditions, $sort = null, 'id');
    }

    /**
     * 获取合同已开票金额
     * @param $obj
     * @return int
     */
    function getInvoicedMoney_d($obj) {
        $this->setCompany(0);
        $this->searchArr = array('objId' => $obj['objId'], 'objTypes' => $this->rtPostVla($obj['objType']));
        $rs = $this->list_d('sumApplyMoney');
        if (is_array($rs)) {
            if (empty($rs[0]['allMoney'])) {
                return 0;
            }
            return $rs[0]['allMoney'];
        } else {
            return 0;
        }
    }

    /**
     * 获取所有开票类型 -  包括小类型金额
     * @param $obj
     * @return array
     */
    function getInvoiceAllMoney_d($obj) {
        $this->setCompany(0);
        $this->searchArr = array('objId' => $obj['objId'], 'objTypes' => $this->rtPostVla($obj['objType']));
        $rs = $this->list_d('sumAllMoney');
        if ($rs[0]['invoiceMoney']) {
            return $rs[0];
        } else {
            return array(
                'invoiceMoney' => 0,
                'softMoney' => 0,
                'hardMoney' => 0,
                'repairMoney' => 0,
                'serviceMoney' => 0,
                'equRentalMoney' => 0,
                'spaceRentalMoney' => 0,
                'otherMoney' => 0
            );
        }
    }

    /**
     * 获取退票金额
     * @param $obj
     * @return int|string
     */
    function getReturnMoney_d($obj) {
        $objType = $this->rtPostVlaForSelect($obj['objType']);
        $rs = $this->get_table_fields($this->tbl_name, " isRed = 1 and objId = " . $obj['objId'] . " and objType in ( " . $objType . " )", "sum(invoiceMoney)");
        return $rs === null ? 0 : $rs;
    }

    /**
     * 根据业务编号获取相关的开票金额
     * @param $rObjCodes
     * @return mixed
     */
    function getInvoiceMoneyByRObjCodes_d($rObjCodes) {
        $this->setCompany(0);
        $this->searchArr['rObjCodes'] = $rObjCodes;
        $this->groupBy = 'c.rObjCode';
        return $this->list_d('sumApplyMoneyrObjCode');
    }

    /************************ 新合同使用部分 *********************/
    /**
     * 新增业务处理方法-暂用
     * @param $object
     * @param $special 特殊处理标识，用于删除/编辑操作
     */
    function busiessDeal_d($object,$special = false) {
        if (in_array($object['objType'], $this->needDealArr)) {
            $thisClass = $this->rtTypeClass($object['objType']);
            $thisClass = 'model_' . $thisClass;
            $innerObjDao = new $thisClass();

            //获取已开票金额
            $rs = $this->getInvoiceAllMoney_d($object);

            if ($rs['invoiceMoney'] == 0) {
                $innerObjDao->_db->query("UPDATE {$innerObjDao->tbl_name} SET invoiceMoney=0,softMoney=0,
                    hardMoney=0,repairMoney=0,serviceMoney=0,equRentalMoney=0,spaceRentalMoney=0,
                    lastInvoiceDate=NULL WHERE id = {$object['objId']}" );
            } else {
                //更新已开票金额
                $innerObjDao->update(array('id' => $object['objId']),
                    array(
                        'invoiceMoney' => $rs['invoiceMoney'],'softMoney' => $rs['softMoney'],
                        'hardMoney' => $rs['hardMoney'],'repairMoney' => $rs['repairMoney'],
                        'serviceMoney' => $rs['serviceMoney'],'equRentalMoney' => $rs['equRentalMoney'],
                        'spaceRentalMoney' => $rs['spaceRentalMoney'],
                        'lastInvoiceDate' => $rs['invoiceTime']
                    )
                );
            }
            // 调用合同自动关闭方法
            $innerObjDao->updateContractClose($object['objId']);
            
            // 更新开票申请及合同信息的开票类型 add By Bingo 2015.9.2
            if(!empty($object['invoiceType'])){
            	$datadictDao = new model_system_datadict_datadict();
            	//单独开票的无须处理
            	if(!empty($object['applyId'])){
            		$invoiceapplyDao = new model_finance_invoiceapply_invoiceapply();
            		$invoiceapplyDao->update(array('id' => $object['applyId']),
            				array('invoiceType' => $object['invoiceType'], 'invoiceTypeName' => $object['invoiceTypeName']));
            	}
            	$rs = $datadictDao->find(array('dataCode' => $object['invoiceType']),null,'expand5');
            	if(!empty($rs['expand5'])){
            		$expand5 = trim($rs['expand5']);
            		$thisInvoiceMoney = 0;//本次开票类型总金额
            		$otherInvoiceMoney = 0;//其它开票类型总金额
                    if($thisClass == "model_contract_contract_contract"){
                        $rs = $innerObjDao->find(array('id' => $object['objId']),null,'invoiceValue,invoiceCode,contractMoney');
                        $contractMoney = $rs['contractMoney'];//合同金额
                        $invoiceValueArr = $innerObjDao->makeInvoiceValueArr($rs);
                    }else{
                        $rs = $innerObjDao->find(array('id' => $object['objId']),null,'invoiceValue,contractMoney');
                        $contractMoney = $rs['contractMoney'];//合同金额
                        $rs = explode(",", $rs['invoiceValue']);
                        $invoiceValueArr['CPXSFP'] = $rs['0'];
                        $invoiceValueArr['CPZLFP'] = $rs['1'];
                        $invoiceValueArr['HTFWFP'] = $rs['2'];
                        $invoiceValueArr['HTCKFP'] = $rs['3'];
                    }
            		if($object['isRed'] == '1' || $special){//红色单或特殊处理
            			$otherInvoiceValue = 0;//合同其它开票类型总金额
            			foreach ($invoiceValueArr as $k => $v){
            				if($expand5 != $k){
            					$otherInvoiceValue += $v;
            				}
            			}
            		}
            		// 获取该合同已开票的信息,并计算每种开票类型剩余可开票金额
            		$sql = "SELECT
								SUM(IF(isRed = 0,c.invoiceMoney,-c.invoiceMoney)) AS invoiceMoney,
								d.expand5
							FROM
								oa_finance_invoice c
							INNER JOIN oa_system_datadict d ON c.invoiceType = d.dataCode
							WHERE
								c.objId = ".$object['objId']."
							GROUP BY
								d.expand5";
            		$invoiceInfo = $this->_db->getArray($sql);
            		if(!empty($invoiceInfo)){
            			foreach ($invoiceInfo as $v){
            				if($v['expand5'] == $expand5){
            					if($special){//特殊处理时，以实际开票为准
            						$thisInvoiceMoney = $v['invoiceMoney'];
            					}else{
            						// 如果本次开票类型的历史开票记录总额大于合同该开票类型的金额，则要更新合同的开票金额
            						$thisInvoiceMoney = $v['invoiceMoney'] > $invoiceValueArr[$expand5] ?
            							$v['invoiceMoney'] : $invoiceValueArr[$expand5];
            					}
            					// 这里把本次申请的开票金额加回来
            					$invoiceValueArr[$expand5] += $object['invoiceMoney'];
            				}else{
            					$otherInvoiceMoney += $v['invoiceMoney'];
            				}
            				// 计算各开票类型剩余可开票金额
            				$invoiceValueArr[$v['expand5']] -= $v['invoiceMoney'];
            			}
            		}
            		$thisInvoiceValue = $invoiceValueArr[$expand5];//合同中本次开票类型剩余金额
            		$money = 0; // 用于计算实际开票与合同对应开票的差价
            		if(empty($thisInvoiceValue)){
            			$money = $object['invoiceMoney'];
            		}elseif($object['invoiceMoney'] > $thisInvoiceValue){
            			$money = bcsub($object['invoiceMoney'], $thisInvoiceValue);
            		}
            		if($object['isRed'] == '1' || $special){//红色单或特殊处理
            			// 应对场景：编辑时更改开票类型
            			// 红色单，其它开票类型，实际开票总额与合同总额之间，取大的值
            			if($special && $object['isRed'] == '1' && $otherInvoiceMoney > $otherInvoiceValue){
            				$otherInvoiceValue = $otherInvoiceMoney;
            			// 蓝色单，其它开票类型，实际开票总额与合同总额之间，取小的值
            			}elseif ($special && $object['isRed'] == '0' && $otherInvoiceValue - $otherInvoiceMoney < $money){
            				$otherInvoiceValue = $otherInvoiceMoney;
            			}
            			$invoiceMoney = $thisInvoiceMoney + $otherInvoiceValue;
            		}
            		if($money != 0){// 如果存在差价,则将差价分摊到其它开票类型上
            			foreach ($invoiceValueArr as $k => $v){
            				if($expand5 != $k){
            					if($money > $v){
            						$money = bcsub($money, $v);
            						$invoiceValueArr[$k] = '';
            					}else{
            						$invoiceValueArr[$k] = $v - $money;
            						break;
            					}
            				}
            			}
            		}
            		if(!empty($invoiceInfo)){
            			foreach ($invoiceInfo as $v){
            				if($v['expand5'] == $expand5){// 处理本次开票类型
            					if($special){//特殊处理
            						if($invoiceMoney < $contractMoney){//开票金额小于合同额
            							$thisInvoiceMoney = $contractMoney - $otherInvoiceValue;
            						}
            					}elseif($object['isRed'] == '1'){//红色单
									//如果开票总金额与合同额差额大于等于本次开票金额，则本次开票类型金额要减去红色单的金额
									if($invoiceMoney - $contractMoney >= $object['invoiceMoney']){
										$thisInvoiceMoney -= $object['invoiceMoney'];
									}else{//否则等于合同额减去其它开票类型的金额
										$thisInvoiceMoney = $contractMoney - $otherInvoiceValue;
									}
								}
								$invoiceValueArr[$expand5] = $thisInvoiceMoney;
            				}else{
            					// 把上面除去的各种开票类型的历史开票金额加回来
            					$invoiceValueArr[$v['expand5']] += $v['invoiceMoney'];
            					// 应对场景：编辑红色单，开票类型改为其它类型
            					if($special && $object['isRed'] == '1' && $v['invoiceMoney'] > $invoiceValueArr[$v['expand5']]){
            						$invoiceValueArr[$v['expand5']] = $v['invoiceMoney'];
            					}
            				}
            			}
            		}
            		//当本次开票类型实际金额为0时
            		if($thisInvoiceMoney == 0){
	            		if($invoiceMoney < $contractMoney){//其它开票类型金额小于合同额
	            			$thisInvoiceMoney = $contractMoney - $otherInvoiceValue;
	            		}
	            		$invoiceValueArr[$expand5] = $thisInvoiceMoney;
            		}
            		$invoiceCodeArr = array();
            		foreach ($invoiceValueArr as $k => $v){
            			if(!empty($v)){
            				array_push($invoiceCodeArr, $k);
            			}else{
            			    unset($invoiceValueArr[$k]);
                        }
            		}
            		$invoiceCode = implode(',', $invoiceCodeArr);
            		$invoiceValue = implode(',', $invoiceValueArr);
            		$innerObjDao->update(array('id' => $object['objId']),
            				array('invoiceCode' => $invoiceCode, 'invoiceValue' => $invoiceValue));
            	}
            }
        }
    }


    /**
     * 根据业务编号获取相关的开票金额
     * @param $objIds
     * @param $objType
     * @return mixed
     */
    function getInvoiceByIdsType_d($objIds, $objType) {
        $this->setCompany(0);
        $this->searchArr = array('objIds' => $objIds, 'objType' => $objType);
        $this->groupBy = 'c.objId';
        return $this->list_d('sumAllMoneyObjId');
    }

    /**
     * 处理货币转换的情况
     * @param $object
     * @param $invoiceDetail
     */
    function dealCurrency_d(&$object, &$invoiceDetail) {
        // 数组转换
        list(
            $object['invoiceMoneyCur'], $object['softMoneyCur'], $object['hardMoneyCur'], $object['repairMoneyCur'],
            $object['serviceMoneyCur'], $object['equRentalMoneyCur'], $object['spaceRentalMoneyCur'], $object['otherMoneyCur'],
            $object['dsEnergyChargeCur'], $object['dsWaterRateMoneyCur'], $object['houseRentalFeeCur'], $object['installationCostCur']
            ) =
            array(
                $object['invoiceMoney'], $object['softMoney'], $object['hardMoney'], $object['repairMoney'], $object['serviceMoney'],
                $object['equRentalMoney'], $object['spaceRentalMoney'], $object['otherMoney'],
                $object['dsEnergyCharge'], $object['dsWaterRateMoney'], $object['houseRentalFee'], $object['installationCost']
            );
        // 计算转换后的金额
        $mark = 0; // 标志是否初始化
        foreach ($invoiceDetail as $k => $v) {
            $invoiceDetail[$k]['softMoney'] = round(bcmul($v['softMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['hardMoney'] = round(bcmul($v['hardMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['repairMoney'] = round(bcmul($v['repairMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['serviceMoney'] = round(bcmul($v['serviceMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['equRentalMoney'] = round(bcmul($v['equRentalMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['spaceRentalMoney'] = round(bcmul($v['spaceRentalMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['otherMoney'] = round(bcmul($v['otherMoney'], $object['rate'], 6), 2);

            $invoiceDetail[$k]['dsEnergyCharge'] = round(bcmul($v['dsEnergyCharge'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['dsWaterRateMoney'] = round(bcmul($v['dsWaterRateMoney'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['houseRentalFee'] = round(bcmul($v['houseRentalFee'], $object['rate'], 6), 2);
            $invoiceDetail[$k]['installationCost'] = round(bcmul($v['installationCost'], $object['rate'], 6), 2);

            $object['softMoney'] = $mark ? bcadd($invoiceDetail[$k]['softMoney'], $object['softMoney'], 2) : $invoiceDetail[$k]['softMoney'];
            $object['hardMoney'] = $mark ? bcadd($invoiceDetail[$k]['hardMoney'], $object['hardMoney'], 2) : $invoiceDetail[$k]['hardMoney'];
            $object['repairMoney'] = $mark ? bcadd($invoiceDetail[$k]['repairMoney'], $object['repairMoney'], 2) : $invoiceDetail[$k]['repairMoney'];
            $object['serviceMoney'] = $mark ? bcadd($invoiceDetail[$k]['serviceMoney'], $object['serviceMoney'], 2) : $invoiceDetail[$k]['serviceMoney'];
            $object['equRentalMoney'] = $mark ? bcadd($invoiceDetail[$k]['equRentalMoney'], $object['equRentalMoney'], 2) : $invoiceDetail[$k]['equRentalMoney'];
            $object['spaceRentalMoney'] = $mark ? bcadd($invoiceDetail[$k]['spaceRentalMoney'], $object['spaceRentalMoney'], 2) : $invoiceDetail[$k]['spaceRentalMoney'];
            $object['otherMoney'] = $mark ? bcadd($invoiceDetail[$k]['otherMoney'], $object['otherMoney'], 2) : $invoiceDetail[$k]['otherMoney'];

            $object['dsEnergyCharge'] = $mark ? bcadd($invoiceDetail[$k]['dsEnergyCharge'], $object['dsEnergyCharge'], 2) : $invoiceDetail[$k]['dsEnergyCharge'];
            $object['dsWaterRateMoney'] = $mark ? bcadd($invoiceDetail[$k]['dsWaterRateMoney'], $object['dsWaterRateMoney'], 2) : $invoiceDetail[$k]['dsWaterRateMoney'];
            $object['houseRentalFee'] = $mark ? bcadd($invoiceDetail[$k]['houseRentalFee'], $object['houseRentalFee'], 2) : $invoiceDetail[$k]['houseRentalFee'];
            $object['installationCost'] = $mark ? bcadd($invoiceDetail[$k]['installationCost'], $object['installationCost'], 2) : $invoiceDetail[$k]['installationCost'];

            $mark = 1;// 设置已用
        }
        $object['invoiceMoney'] = bcadd($object['softMoney'], $object['hardMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['repairMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['serviceMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['equRentalMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['spaceRentalMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['otherMoney'], 2);

        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['dsEnergyCharge'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['dsWaterRateMoney'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['houseRentalFee'], 2);
        $object['invoiceMoney'] = bcadd($object['invoiceMoney'], $object['installationCost'], 2);
    }

    /**
     * 本币处理
     * @param $object
     */
    function dealLocal_d(&$object) {
        // 数组转换
        list(
            $object['invoiceMoneyCur'], $object['softMoneyCur'], $object['hardMoneyCur'], $object['repairMoneyCur'],
            $object['serviceMoneyCur'], $object['equRentalMoneyCur'], $object['spaceRentalMoneyCur'], $object['otherMoneyCur'],
            $object['dsEnergyChargeCur'], $object['dsWaterRateMoneyCur'], $object['houseRentalFeeCur'], $object['installationCostCur']
            ) =
            array(
                $object['invoiceMoney'], $object['softMoney'], $object['hardMoney'], $object['repairMoney'], $object['serviceMoney'],
                $object['equRentalMoney'], $object['spaceRentalMoney'], $object['otherMoney'],
                $object['dsEnergyCharge'], $object['dsWaterRateMoney'], $object['houseRentalFee'], $object['installationCost']
            );
    }
}