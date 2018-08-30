<?php

/**
 * 开票申请model层类
 */
class model_finance_invoiceapply_invoiceapply extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_invoiceapply";
        $this->sql_map = "finance/invoiceapply/invoiceapplySql.php";
        parent::__construct();
    }

    /********************新策略部分使用************************/
    private $relatedStrategyArr = array(//不同类型入库申请策略类,根据需要在这里进行追加
        'KPRK-01' => 'model_finance_invoiceapply_strategy_salesorder', //销售订单
        'KPRK-02' => 'model_finance_invoiceapply_strategy_salesorder', //销售订单
        'KPRK-03' => 'model_finance_invoiceapply_strategy_service', //服务合同
        'KPRK-04' => 'model_finance_invoiceapply_strategy_service', //服务合同
        'KPRK-05' => 'model_finance_invoiceapply_strategy_rental', //租赁合同
        'KPRK-06' => 'model_finance_invoiceapply_strategy_rental', //租赁合同
        'KPRK-07' => 'model_finance_invoiceapply_strategy_rdproject', //研发合同
        'KPRK-08' => 'model_finance_invoiceapply_strategy_rdproject',  //研发合同
        'KPRK-09' => 'model_finance_invoiceapply_strategy_other', //零配件订单
        'KPRK-10' => 'model_finance_invoiceapply_strategy_accessorder', //零配件订单
        'KPRK-11' => 'model_finance_invoiceapply_strategy_repairapply', //维修申请单
        'KPRK-12' => 'model_finance_invoiceapply_strategy_contract' //主营合同
    );

    private $relatedCode = array(
        'KPRK-01' => 'salesorder',
        'KPRK-02' => 'salesorder',
        'KPRK-03' => 'service',
        'KPRK-04' => 'service',
        'KPRK-05' => 'rental',
        'KPRK-06' => 'rental',
        'KPRK-07' => 'rdproject',
        'KPRK-08' => 'rdproject',
        'KPRK-09' => 'other',
        'KPRK-10' => 'accessorder',
        'KPRK-11' => 'repairapply',
        'KPRK-12' => 'contract'
    );

    /**
     * 发票类型返回对应对象类
     * @param $thisVal
     * @return null|string
     */
    function rtTypeClass($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = 'projectmanagent_order_order';
                break;
            case 'KPRK-02' :
                $val = 'projectmanagent_order_order';
                break;
            case 'KPRK-03' :
                $val = 'engineering_serviceContract_serviceContract';
                break;
            case 'KPRK-04' :
                $val = 'engineering_serviceContract_serviceContract';
                break;
            case 'KPRK-05' :
                $val = 'contract_rental_rentalcontract';
                break;
            case 'KPRK-06' :
                $val = 'contract_rental_rentalcontract';
                break;
            case 'KPRK-07' :
                $val = 'rdproject_yxrdproject_rdproject';
                break;
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

    /**
     * 发票类型过滤
     * @param $thisVal
     * @return null|string
     */
    function rtPostVla($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = "'KPRK-01','KPRK-02'";
                break;
            case 'KPRK-02' :
                $val = "'KPRK-01','KPRK-02'";
                break;
            case 'KPRK-03' :
                $val = "'KPRK-03','KPRK-04'";
                break;
            case 'KPRK-04' :
                $val = "'KPRK-03','KPRK-04'";
                break;
            case 'KPRK-05' :
                $val = "'KPRK-05','KPRK-06'";
                break;
            case 'KPRK-06' :
                $val = "'KPRK-05','KPRK-06'";
                break;
            case 'KPRK-07' :
                $val = "'KPRK-07','KPRK-08'";
                break;
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
    function rtPostVla2($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = 'KPRK-01,KPRK-02';
                break;
            case 'KPRK-02' :
                $val = 'KPRK-01,KPRK-02';
                break;
            case 'KPRK-03' :
                $val = 'KPRK-03,KPRK-04';
                break;
            case 'KPRK-04' :
                $val = 'KPRK-03,KPRK-04';
                break;
            case 'KPRK-05' :
                $val = 'KPRK-05,KPRK-06';
                break;
            case 'KPRK-06' :
                $val = 'KPRK-05,KPRK-06';
                break;
            case 'KPRK-07' :
                $val = 'KPRK-07,KPRK-08';
                break;
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
     * 返回表
     * @param $thisVal
     * @return null|string
     */
    function rtTableName($thisVal) {
        $val = null;
        switch ($thisVal) {
            case 'KPRK-01' :
                $val = 'oa_sale_order';
                break;
            case 'KPRK-02' :
                $val = 'oa_sale_order';
                break;
            case 'KPRK-03' :
                $val = 'oa_sale_service';
                break;
            case 'KPRK-04' :
                $val = 'oa_sale_service';
                break;
            case 'KPRK-05' :
                $val = 'oa_sale_lease';
                break;
            case 'KPRK-06' :
                $val = 'oa_sale_lease';
                break;
            case 'KPRK-07' :
                $val = 'oa_sale_rdproject';
                break;
            case 'KPRK-08' :
                $val = 'oa_sale_rdproject';
                break;
            case 'KPRK-09' :
                $val = 'oa_sale_other';
                break;
            case 'KPRK-10' :
                $val = 'oa_service_accessorder';
                break;
            case 'KPRK-11' :
                $val = 'oa_service_repair_apply';
                break;
            case 'KPRK-12' :
                $val = 'oa_contract_contract';
                break;
            default :
                $val = '';
                break;
        }
        return $val;

    }

    private $mailStatus = array(
        '否', '是'
    );

    private $stampStatus = array(
        '否', '是'
    );

    //公司权限处理
    protected $_isSetCompany = 1; # 单据是否要区分公司,1为区分,0为不区分

    /**
     * 根据类型返回业务名称
     * @param $objType
     * @return mixed
     */
    public function getBusinessCode($objType) {
        return $this->relatedCode[$objType];
    }

    /**
     * 根据数据类型返回类
     * @param $objType
     * @return mixed
     */
    public function getClass($objType) {
        return $this->relatedStrategyArr[$objType];
    }

    /**
     * 根据值返回邮寄状态
     * @param $thisVal
     * @return mixed
     */
    public function getMailStatus($thisVal) {
        return $this->mailStatus[$thisVal];
    }

    /**
     * 根据值返回盖章状态
     * @param $thisVal
     * @return mixed
     */
    public function getStampStatus($thisVal) {
        return $this->stampStatus[$thisVal];
    }

    /**
     * 获取数据信息
     * @param $obj
     * @param iinvoiceapply $strategy
     * @return mixed
     */
    public function getObjInfo_d($obj, iinvoiceapply $strategy) {
        //渲染内容
        return $strategy->getObjInfo_d($obj);
    }

    /**
     * 获取数据信息 - 查看修改时使用
     * @param $obj
     * @param $perm
     * @param iinvoiceapply $strategy
     * @return array
     */
    public function getObjInfoInit_d($obj, $perm, iinvoiceapply $strategy) {
        //获取内容
        $rs = $strategy->getObjInfoInit_d($obj);
        //开票关联ids
        $planIds = null;

        //渲染内容
        if ($perm == 'view') {
            return $strategy->initView_d($rs);
        } else {
            $rs = $strategy->initEdit_d($rs);
            return array($rs, $planIds);
        }
    }

    /**
     * 回调处理
     * @param $obj
     * @param iinvoiceapply $strategy
     * @return mixed
     */
    public function businessDeal_i($obj, iinvoiceapply $strategy) {
        return $strategy->businessDeal_i($obj, $this);
    }

    /**
     * 邮件配置获取
     * @param int $thisVal
     * @return array
     */
    function getMailInfo_d($thisVal = 0) {
        $thisKey = $thisVal ? 'financeInvoiceapplyOffSite' : 'financeInvoiceapply'; // 邮件配置获取
        include(WEB_TOR . "model/common/mailConfig.php");
        return isset($mailUser[$thisKey]) ? $mailUser[$thisKey] : array('sendUserId' => '',
            'sendName' => '');
    }

    //数据字典字段处理
    public $datadictFieldArr = array(
        'invoiceType', 'customerType', 'objType'
    );

    /***************************************************************************************************
     * ------------------------------以下为页面模板显示调用方法------------------------------
     *************************************************************************************************/

    /**
     * 显示开票申请历史列表模板
     * @param $hisApplys
     * @return string
     */
    function showhisapplylist($hisApplys) {
        if ($hisApplys) {
            $i = 0; //列表记录序号
            $str = ""; //返回的模板字符串
            $datadictDao = new model_system_datadict_datadict();
            foreach ($hisApplys as $val) {
                $i++;
                $invoiceType = $datadictDao->getDataNameByCode($val['invoiceType']);
                $str .= <<<EOT
					<tr height="28px">
						<td>$i</td>
						<td><a href="?model=finance_invoiceapply_invoiceapply&action=init&id=$val[id]&perm=view">$val[applyNo]</a></td>
						<td>$val[applyDate]</td>
						<td>$invoiceType</td>
						<td class="formatMoney">$val[invoiceMoney]</td>
						<td class="formatMoney">$val[payedAmount]</td>
						<td>{$val['ExaStatus']}</td>
					</tr>
EOT;
            }
        } else {
            $str = '<tr height="28px"><td colspan="7">暂无</td></tr>';
        }
        return $str;
    }

    /***************************************************************************************************
     * ------------------------------以下为公用接口方法,可以为其他模块所调用------------------------------
     *************************************************************************************************/

    /**
     * 重写add_d
     * @param $object
     * @param null $act
     * @return bool
     */
    function add_d($object, $act = null) {
        $codeRuleDao = new model_common_codeRule();
        $otherDataDao = new model_common_otherdatas();
        $userInfo = $otherDataDao->getUserDatas($_SESSION['USER_ID']);

        if ($rs = $this->infoCheck_d($object)) {
            return $rs;
        }

        try {
            $this->start_d();
            //自动产生开票申请单号
            $object ['applyNo'] = $codeRuleDao->financeCode($this->tbl_name, 'DL', 'KPSQ');
            $object ['applyDate'] = day_date;
            $object ['ExaStatus'] = WAITAUDIT;
            $apply ['status'] = 'KPTZ-00';
            $apply ['status'] = 'KPTZ-00';

            //提取开票申请详细
            if (isset($object['invoiceDetail'])) {
                $detailRows = $object['invoiceDetail'];
                unset($object['invoiceDetail']);
            }

            //提取扣款信息
            if (isset($object['deductinfo'])) {
                $deductinfoRows = $object['deductinfo'];
                unset($object['deductinfo']);
            }

            //数据字典处理
            $object = $this->processDatadict($object);

            //申请人区域设置
            $object['salemanArea'] = $userInfo['areaname'];
            if (empty($object['managerId'])) {
                $managerArr = $otherDataDao->getManager($_SESSION['USER_ID']);
                $object['managerId'] = $managerArr['managerId'];
                $object['managerName'] = $managerArr['managerName'];
            }

            //插入开票申请主表信息
            $newId = parent::add_d($object, true);

            //插入开票详细
            if (isset($detailRows)) {
                $invoiceDetailDao = new model_finance_invoiceapply_invoiceDetail();
                $invoiceDetailDao->createBatch($detailRows, array('invoiceApplyId' => $newId), 'productName');
            }

            //插入扣款信息
            if (isset($deductinfoRows)) {
                $deductinfoDao = new model_finance_invoiceapply_invoiceDeductinfo();
                $deductinfoDao->createBatch($deductinfoRows, array('invoiceApplyId' => $newId), 'grade');
            }

            //业务信息处理
            $newClass = $this->getClass($object['objType']);
            if (!empty($newClass)) {
                $initObj = new $newClass();
                $this->businessDeal_i($object, $initObj);
            }

            //发送邮件 ,当操作为提交时才发送
            if ($act == 'audit') {
                $emailDao = new model_common_mail();
                $emailDao->batchEmail($object['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '录入', $object['applyNo'], $object['TO_ID']);
            }

            //更新附件关联关系
            $this->updateObjWithFile($newId, $object['orderCode']);
            $this->commit_d();
            return $newId;
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 根据主键修改开票申请
     * @param $object
     * @param null $act
     * @return bool
     */
    function edit_d($object, $act = null) {
        $rs = $this->infoCheck_d($object);
        if ($rs) {
            return $rs;
        }

        try {
            $this->start_d();

            //修改开票详细
            $detailRows = $object['invoiceDetail'];
            unset($object['invoiceDetail']);

            //提取扣款信息
            if (isset($object['deductinfo'])) {
                $deductinfoRows = $object['deductinfo'];
                unset($object['deductinfo']);
            }

            //数据字典处理
            $object = $this->processDatadict($object);

            $this->deleteDetail_d($object['id']);

            if (!empty($detailRows)) {
                $invoiceDetailDao = new model_finance_invoiceapply_invoiceDetail();
                $invoiceDetailDao->createBatch($detailRows, array('invoiceApplyId' => $object['id']), 'productName');
            }

            //插入扣款信息
            $deductinfoDao = new model_finance_invoiceapply_invoiceDeductinfo();
            $deductinfoDao->delete(array('invoiceApplyId' => $object['id']));
            if (isset($deductinfoRows)) {
                $deductinfoDao->createBatch($deductinfoRows, array('invoiceApplyId' => $object['id']), 'grade');
            }

            //邮件发送
            if ($act == 'audit') {
                $emailDao = new model_common_mail();
                $emailDao->batchEmail($object['issend'], $_SESSION['USERNAME'], $_SESSION['EMAIL'], $this->tbl_name, '提交', $object['applyNo'], $object['TO_ID']);
            }
            //修改自身主表
            parent::edit_d($object, true);

            //业务信息处理
            $newClass = $this->getClass($object['objType']);
            if (!empty($newClass)) {
                $initObj = new $newClass();
                $this->businessDeal_i($object, $initObj);
            }

            $this->commit_d();
            return $object['id'];
        } catch (exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 源生编辑方法
     * @param $object
     * @return mixed
     */
    function orgEdit_d($object) {
        //数据字典处理
        $object = $this->processDatadict($object);
        return parent::edit_d($object, true);
    }

    /**
     * 获取开票申请
     * @param $id
     * @param null $perm
     * @return bool|mixed
     */
    function get_d($id, $perm = null) {
        $apply = parent::get_d($id);

        $invoiceDetailDao = new model_finance_invoiceapply_invoiceDetail();
        $detailRow = $invoiceDetailDao->getDetailByInvoiceApplyId($id);

        $invoiceDeductinfoDao = new model_finance_invoiceapply_invoiceDeductinfo();
        $DeductinfoRow = $invoiceDeductinfoDao->getDeductinfoByInvoiceApplyId($id);

        if ($perm == 'view') {
            $arr = $invoiceDetailDao->rowsToView($detailRow);
            $arr2 = $invoiceDeductinfoDao->rowsToView($DeductinfoRow);
        } else if ($perm == 'audit') {
            $arr = $invoiceDetailDao->rowsToView($detailRow);
            $arr2 = $invoiceDeductinfoDao->rowsToView($DeductinfoRow);
        } else if ($perm == 'register') {
            $arr = $invoiceDetailDao->rowsToRegister($detailRow);
            $apply['allThisMoney'] = $arr[2];
            $apply['softMoney'] = $arr[3];
            $apply['hardMoney'] = $arr[4];
            $apply['repairMoney'] = $arr[5];
            $apply['serviceMoney'] = $arr[6];
            $apply['equRenalMoney'] = $arr[7];
            $apply['spaceRentalMoney'] = $arr[8];
            $apply['otherMoney'] = $arr[9];
        } else {
            $arr = $invoiceDetailDao->rowsToEdit($detailRow);
            $arr2 = $invoiceDeductinfoDao->rowsToEdit($DeductinfoRow);
        }
        $apply['detail'] = $arr[0];
        $apply['invnumber'] = $arr[1];
        $apply['deductinfo'] = $arr2[0];
        $apply['invnumber2'] = $arr2[1];
        return $apply;
    }

    /**
     * 获取开票申请的信息
     * @param $id
     * @return bool|mixed
     */
    function getInfo_d($id) {
        return parent::get_d($id);
    }

    /**
     * 根据业务信息获取开票申请信息
     * @param $objCode
     * @param $objType
     * @param null $applyNo
     * @return mixed
     */
    function getInvoiceapplyByObj($objCode, $objType, $applyNo = null) {
        $this->searchArr = array('objCode' => $objCode, 'objType' => $objType);
        if (!empty($applyNo)) {
            $this->searchArr['noApplyNo'] = $applyNo;
        }
        return $this->list_d();
    }

    /**
     * 根据开票申请id获取相关开票
     * @param $id
     * @return array
     */
    function getInvoicesByApplyId_d($id) {
        $invoiceDao = new model_finance_invoice_invoice ();
        $invoices = $invoiceDao->getInvoicesByApplyId($id);

        $sconfigDao = new model_common_securityUtil ('invoice');
        $invoices = $sconfigDao->md5Rows($invoices);
        return $invoiceDao->showapplylist($invoices);
    }

    /**
     * 删除开票详细
     * @param $applyId
     * @return mixed
     */
    function deleteDetail_d($applyId) {
        return $this->_db->query('delete from oa_finance_invoiceapply_detail where invoiceApplyId=' . $applyId);
    }

    /**
     * 获取业务对象已申请开票金额或已开票金额
     * @param $obj
     * @return int
     */
    function getApplyedMoney_d($obj) {
        $objs = $this->rtPostVla($obj['objType']);
        $sql = "select if(sum(c.invoiceMoney) is null,0,sum(c.invoiceMoney)) as applyedMoney from oa_finance_invoiceapply c where c.ExaStatus <> '打回' and c.objId = '" . $obj['objId'] . "' and c.objType in (" . $objs . ")";
        $rs = $this->_db->getArray($sql);
        return is_array($rs) ? $rs[0]['applyedMoney'] : 0;
    }

    /**
     * 返回开票申请和开票记录中的最大值
     * @param $obj
     * @return mixed
     */
    function getMaxInvoiceMoney_d($obj) {
        //已申请金额
        $applyedMoney = $this->getApplyedMoney_d($obj);

        //已开票金额
        $invoiceDao = new model_finance_invoice_invoice();
        $invoicedMoney = $invoiceDao->getInvoicedMoney_d($obj);

        //获取退票金额
        $returnMoney = $invoiceDao->getReturnMoney_d($obj);

        //实际可申请 = 已申请 - 已退票
        $actCanApply = bcsub($applyedMoney, $returnMoney, 2);

        return max($actCanApply, $invoicedMoney);
    }

    /**
     * 获取合同信息
     * @param $objId
     * @param $objType
     * @return array
     */
    function getContractInfo_d($objId, $objType) {
        $receviableDao = new model_finance_receviable_receviable();
        return $receviableDao->getInvoiceAndIncome_d($objId, $objType);
    }

    /**
     * 合同信息转成表格显示
     * @param $row
     * @param $object
     * @return null|string
     */
    function showContractInfo($row, $object) {
        $str = null;
        if (is_array($row)) {
            $financeNeedReceviable = bcsub($row['invoiceMoney'], $row['incomeMoney']);
            $contractNeedReceviable = bcsub($object['contAmount'], $row['incomeMoney']);
            if ($object['invoiceMoney'] >= $object['contAmount']) {
                $red = "red";
            } else {
                $red = null;
            }
            $str = <<<EOT
				<tr>
					<td>$object[objCode]</td>
					<td class="formatMoney">$object[contAmount]</td>
					<td><span  class="formatMoney $red">$row[invoiceMoney]</span></td>
					<td class="formatMoney">$row[incomeMoney]</td>
					<td class="formatMoney">$financeNeedReceviable</td>
					<td class="formatMoney">$contractNeedReceviable</td>
				</tr>
EOT;
        }
        return $str;
    }

    /**
     * 内容验证函数
     * @param $object
     * @return bool|string
     */
    function infoCheck_d($object) {
        $object['customerName'] = trim($object['customerName']);
        $object['linkMan'] = trim($object['linkMan']);
        $object['linkPhone'] = trim($object['linkPhone']);
        $object['unitAddress'] = trim($object['unitAddress']);
        $object['linkAddress'] = trim($object['linkAddress']);

        if (empty($object['customerName'])) {
            return '客户名称没有填写,申请不成功';
        }
        if (empty($object['linkMan'])) {
            return '联系人没有填写,申请不成功';
        }
        if (empty($object['linkPhone'])) {
            return '电话没有填写,申请不成功';
        }

        if (empty($object['unitAddress']) && empty($object['linkAddress'])) {
            return '开票单位地址或发票邮寄地址必须填写一个';
        }

        if ($object['invoiceMoney'] == 0) {
            return '开票申请金额为0,申请不成功,如单据中相关金额数据不能够自动计算,请更换浏览器尝试';
        }

        $allInvoiceMoney = 0;
        $allAmount = 0;

        foreach ($object['invoiceDetail'] as $val) {
            $rowAllMoney = 0;//行金额
            $val['productName'] = trim($val['productName']);
            if (empty($val['productName'])) {
                return '开票申请详细中的货品名称没有填写,申请不成功';
            }

            if ($val['amount'] == 0 || empty($val['amount'])) {
                return '开票数量为0,申请不成功';
            }

            if (empty($val['psTyle'])) {
                return '开票申请详细中的产品服务类型没有填写,申请不成功';
            }

            $rowAllMoney = bcadd($rowAllMoney, $val['softMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['hardMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['repairMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['serviceMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['equRentalMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['spaceRentalMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['otherMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['dsEnergyCharge'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['dsWaterRateMoney'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['houseRentalFee'], 2);
            $rowAllMoney = bcadd($rowAllMoney, $val['installationCost'], 2);
            if ($rowAllMoney == 0) {
                return '开票申请详细中存在单行金额为0,申请不成功,如单据中相关金额数据不能够自动计算,请更换浏览器尝试';
            }
            $allAmount = bcadd($allAmount, $val['amount'], 2);
            $allInvoiceMoney = bcadd($allInvoiceMoney, $rowAllMoney, 2);
        }
        if ($allInvoiceMoney != $object['invoiceMoney']) {
            return '开票申请明细总金额与单据申请总金额不等,申请不成功,如单据中相关金额数据不能够自动计算,请更换浏览器尝试';
        }
        return false;
    }

    /************************** 新合同处理部分 **************************/
    /**
     * 获取开票申请金额
     * @param $objId
     * @param string $objType
     * @return int
     */
    function getApplyedMoneyNew_d($objId, $objType = 'KPRK-12') {
        $sql = "SELECT SUM(invoiceLocal) AS invoiceLocal FROM " . $this->tbl_name .
            ' WHERE objId = ' . $objId . ' AND objType ="' . $objType . '"';
        $rs = $this->_db->getArray($sql);
        if ($rs[0]['invoiceLocal']) {
            return $rs[0]['invoiceLocal'];
        } else {
            return 0;
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
            $obj = $this->find(array('id' => $id), null, 'objId,objType');

            $this->deletes($id);

            //业务信息处理
            $newClass = $this->getClass($obj['objType']);
            if (!empty($newClass)) {
                $initObj = new $newClass();
                $this->businessDeal_i($obj, $initObj);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 默认物流公司获取
     * @return bool|mixed
     */
    function getDefaultExpressCom_d() {
        $logisticsDao = new model_mail_logistics_logistics();
        $rs = $logisticsDao->find(array('isDefault' => 1), null, 'companyName,id');
        if ($rs) {
            return $rs;
        } else {
            return $logisticsDao->find(null, 'id desc', 'companyName,id');
        }
    }

    /**
     * 审批成功后在盖章列表添加信息
     * @param $spid
     * @return int
     * @throws Exception
     */
    function dealAfterAudit_d($spid) {
        $otherdatas = new model_common_otherdatas ();
        $folowInfo = $otherdatas->getStepInfo($spid);
        $objId = $folowInfo ['objId'];
        $userId = $folowInfo['Enter_user'];

        $object = $this->getInfo_d($objId);
        if ($object['isNeedStamp'] == "1") {

            if ($userId == $object['createId']) {
                $userName = $object['createName'];
            } else {
                $userName = $object['principalName'];
            }

            //创建数组
            $object = array(
                "contractId" => $object['id'],
                "contractCode" => $object['applyNo'],
                "contractType" => 'HTGZYD-06',
                "signCompanyName" => $object['customerName'],
                "signCompanyId" => $object['customerId'],
                "contractMoney" => 0,
                "applyUserId" => $userId,
                "applyUserName" => $userName,
                "applyDate" => day_date,
                "stampType" => $object['stampType'],
                "status" => 0
            );
            $stampDao = new model_contract_stamp_stamp();
            $stampDao->addStamps_d($object, true);
            return 1;
        }
        return 1;
    }
}