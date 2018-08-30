<?php

/**
 * @author show
 * @Date 2013年10月24日 19:30:28
 * @version 1.0
 * @description:赔偿单 Model层
 */
class model_finance_compensate_compensate extends model_base
{

    function __construct() {
        $this->tbl_name = "oa_finance_compensate";
        $this->sql_map = "finance/compensate/compensateSql.php";
        parent:: __construct();
    }

    public $datadictFieldArr = array(
        'dutyType', 'payType'
    );

    /**
     * 状态
     * @param $v string
     * @return string
     */
    function rtStatus_d($v) {
        switch ($v) {
            case '0' :
                return '待确认';
            case '1' :
                return '金额已确认';
            case '2' :
                return '待赔偿确认';
            case '3' :
                return '待经理审核';
            case '4' :
                return '已完成';
            case '5' :
                return '关闭';
            default :
                return $v;
        }
    }

    /********************** S 策略部分 ***********************/
    /**
     *  源单配置
     */
    private $relDocType = array(
        'PCYDLX-01' => 'model_finance_compensate_strategy_sborrowreturn',
        'PCYDLX-02' => 'model_finance_compensate_strategy_swithdraw'
    );

    /**
     * 返回配置
     * @param $v string
     * @return string
     */
    public function getRelType($v) {
        return $this->relDocType[$v];
    }

    /**
     * 新增赔偿时调用策略
     * @param $relId
     * @param $strategy
     * @return mixed
     */
    function businessGet_d($relId, icompensate $strategy) {
        return $strategy->businessGet_i($relId, $this);
    }

    /**
     * 获取源单赔偿信息 - 明细
     * @param $condition
     * @param $strategy
     * @return mixed
     */
    function businessGetDetail_d($condition, icompensate $strategy) {
        return $strategy->businessGetDetail_i($condition, $this);
    }

    /**
     * 获取源单赔偿信息 - 明细
     * @param $condition
     * @param $strategy
     * @return mixed
     */
    function getSerialNos_d($condition, icompensate $strategy) {
        return $strategy->getSerialNos_i($condition, $this);
    }

    /**
     *  新增赔偿时调用策略
     * @param $obj
     * @param $detail
     * @param $strategy
     * @return mixed
     */
    function businessAdd_d($obj, $detail, icompensate $strategy) {
        return $strategy->businessAdd_i($obj, $detail, $this);
    }

    /********************** E 策略部分 ***********************/

    /**
     * 重写add
     * @param $object array
     * @return boolean
     */
    function add_d($object) {
        $detail = $object['detail'];
        foreach ($detail as $dk => $dv){
            $detail[$dk]['price'] = str_replace(',','',$dv['price']);
        }
        unset($object['detail']);
        try {
            $this->start_d();

            //赋值
            $codeRuleDao = new model_common_codeRule();
            $object['ExaStatus'] = WAITAUDIT;
            $object['formCode'] = $codeRuleDao->commonCode('赔偿单', $this->tbl_name, 'PCD');
            $object = $this->processDatadict($object);

            //新增主表信息
            $newId = parent::add_d($object, true);

            //新增从表信息
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $detail = util_arrayUtil::setArrayFn(array('mainId' => $newId), $detail);
            $compensateDetailDao->saveDelBatch($detail);

            //源单部分策略处理
            $relClass = $this->relDocType[$object['relDocType']];
            if ($relClass) {
                $relClassM = new $relClass();//策略实例
                $this->businessAdd_d($object, $detail, $relClassM);
            }

            //业务策略 -- 暂无

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 重写edit
     * @param $object array
     * @return boolean
     */
    function edit_d($object) {
        $detail = $object['detail'];
        foreach ($detail as $dk => $dv){
            $detail[$dk]['price'] = str_replace(',','',$dv['price']);
        }
        unset($object['detail']);
        //费用分摊明细
        $costShare = $object['costshare'];
        unset($object['costshare']);
        try {
            $this->start_d();

            //如果是确认,加入确认信息
            if (!empty($object['isConfirm'])) {
                $object['confirmId'] = $_SESSION['USER_ID'];
                $object['confirmName'] = $_SESSION['USERNAME'];
                $object['confirmTime'] = date('Y-m-d H:i:s');
                $object['formStatus'] = 1;
            }

            //新增主表信息
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //新增从表信息
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $compensateMoneyTotal = 0;
            foreach ($detail as $k => $v){
                if($v['money'] == 0){
                    $detail[$k]['compensateMoney'] = $v['price'];
                    $compensateMoneyTotal += $v['price'];
                }else{
                    $compensateMoneyTotal += $v['money'];
                }
            }

            //更新单据总金额
            $compensateDao = new model_finance_compensate_compensate();
            $compensateDao->updateCompansate_d($object['id'],$compensateMoneyTotal);
            $detail = util_arrayUtil::setArrayFn(array('mainId' => $object['id']), $detail);
            $compensateDetailDao->saveDelBatch($detail);

            //费用分摊处理
            if ($costShare) {
                $costShareDao = new model_finance_cost_costshare();
                $costShare = util_arrayUtil::setArrayFn(array('objId' => $object['id'], 'objType' => '1'), $costShare);
                $costShareDao->saveDelBatch($costShare);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 确认赔偿 - 新
     * @param $object array
     * @return boolean
     */
    function confirm_d($object) {
        //费用分摊明细
        $costShare = $object['costshare'];
        unset($object['costshare']);
        try {
            $this->start_d();

            //如果是确认,加入确认信息
            $object['comConfirmId'] = $_SESSION['USER_ID'];
            $object['comConfirmName'] = $_SESSION['USERNAME'];
            $object['comConfirmTime'] = date('Y-m-d H:i:s');
            $object['formStatus'] = 3;

            //新增主表信息
            $object = $this->processDatadict($object);
            parent::edit_d($object, true);

            //费用分摊处理
            if ($costShare) {
                $costShareDao = new model_finance_cost_costshare();
                $costShare = util_arrayUtil::setArrayFn(
                    array('objId' => $object['id'], 'objCode' => $object['formCode'], 'objType' => '1',
                        'company' => $_SESSION['USER_COM'], 'companyName' => $_SESSION['USER_COM_NAME']),
                    $costShare
                );
                $costShareDao->saveDelBatch($costShare);
                $costShareDao->setDataAudited_d($object['id'], 1);
            }

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 确认赔偿
     * @param $id string
     * @return mixed
     */
    function comConfirm_d($id) {
        $condition = array("id" => $id);
        $object = array(
            'formStatus' => 3, 'comConfirmId' => $_SESSION['USER_ID'],
            'comConfirmTime' => date('Y-m-d H:i:s'), 'comConfirmName' => $_SESSION['USERNAME']
        );
        return $this->update($condition, $object);
    }

    /**
     * 确认赔偿
     * @param $id string
     * @return boolean
     */
    function unComConfirm_d($id) {
        try {
            $this->start_d();

            //取消赔偿确认
            $condition = array("id" => $id);
            $object = array(
                'formStatus' => 2, 'comConfirmId' => '',
                'comConfirmTime' => '0000-00-00 00:00:00', 'comConfirmName' => ''
            );
            $this->update($condition, $object);

            //取消分摊记录审核
            $costShareDao = new model_finance_cost_costshare();
            $costShareDao->setDataUnAudited_d($id, 1);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 确认赔偿
     * @param $id string
     * @return mixed
     */
    function audit_d($id) {
        $condition = array("id" => $id);
        $object = array(
            'formStatus' => 4, 'auditorId' => $_SESSION['USER_ID'],
            'auditTime' => date('Y-m-d H:i:s'), 'auditorName' => $_SESSION['USERNAME']
        );
        return $this->update($condition, $object);
    }

    /**
     * 确认赔偿
     * @param $id string
     * @return mixed
     */
    function unAudit_d($id) {
        $condition = array("id" => $id);
        $object = array(
            'formStatus' => 3, 'auditorId' => '',
            'auditTime' => '0000-00-00 00:00:00', 'auditorName' => ''
        );
        return $this->update($condition, $object);
    }

    /**
     * 关闭
     * @param $id string
     * @return boolean
     */
    function close_d($id) {
        try {
            $this->start_d();

            //主表内容更新
            $condition = array("id" => $id);
            $object = array(
                'formStatus' => 5, 'closerId' => $_SESSION['USER_ID'],
                'closeTime' => date('Y-m-d H:i:s'), 'closerName' => $_SESSION['USERNAME'],
                'compensateMoney' => 0
            );
            $this->update($condition, $object);

            //从表内容更新 - 设为免赔
            $compensateDetailDao = new model_finance_compensate_compensatedetail();
            $compensateDetailDao->close_d($id);

            $this->commit_d();
            return true;
        } catch (Exception $e) {
            $this->rollBack();
            return false;
        }
    }

    /**
     * 更新赔偿主体信息
     * @param $id
     * @param $dutyType
     * @param $dutyObjId
     * @param $dutyObjName
     * @return bool
     */
    function updateDutyInfo_d($id, $dutyType, $dutyObjId, $dutyObjName) {
        $object = $this->processDatadict(array(
            'id' => $id,
            'dutyType' => $dutyType,
            'dutyObjId' => $dutyObjId,
            'dutyObjName' => $dutyObjName
        ));
        return parent::edit_d($object, true);
    }

    /**
     * 审批调用
     * @param $spid int
     * @return int
     */
    function workflowCallBack($spid) {
        $otherDatas = new model_common_otherdatas ();
        $flowInfo = $otherDatas->getStepInfo($spid);
        $objId = $flowInfo['objId'];
        $obj = $this->get_d($objId);
        if ($obj['ExaStatus'] == AUDITED) {
            // 发送审核邮件到设备责任人
            $this->mailDeal_d('compensateAudit', $obj['chargerId'], array('id' => $objId));

            if($obj['dutyType'] == "PCZTLX-01"){// 如果赔偿主体是个人的, 增加邮件通知赔偿责任人
                // 部门审批人 (确认后取第一步审批人,既责任人部门总监)
                $getFirstAuditManIdSql = "select u.USER_NAME as userName,p.User as userId from flow_step_partent p left join user u on p.User = u.USER_ID where p.Wf_task_ID = {$flowInfo['task']} and p.SmallID = 1;";
                $firstAuditor = $this->_db->getArray($getFirstAuditManIdSql);
                $firstAuditor = ($firstAuditor)? $firstAuditor[0]['userName'] : '';


                // 邮件通知赔偿责任人
                $this->mailDeal_d('compensateAuditForPersonal', $obj['chargerId'], array('objCode' => $obj['objCode'],'compensateMoney' => $obj['compensateMoney'],'firstAuditor' => $firstAuditor));
//                $sendIds = rtrim($obj['chargerId'],",");
//                $sql = "select GROUP_CONCAT(EMAIL) as address  from user where USER_ID in('{$sendIds}')";
//                $adrsArr = $this->_db->getArray($sql);
//                $addresses = ($adrsArr)? $adrsArr[0]["address"] : "";
//                $mailContent = "您好！由于借用单：{$obj['objCode']} 有设备遗失，需赔偿公司 {$obj['compensateMoney']}元。如有疑问可咨询部门领导：{$firstAuditor}。谢谢！";
//                $title = "借款单赔偿通知";
//
//                $sql = "insert into tasks_email(userid,title,content,address,ccAddress,sendTime,attPath,attFileStr,fromType)values('".$_SESSION['USER_ID']."','{$title}','{$mailContent}','{$addresses}','',NOW(),'','','1')";
//                $this->_db->query($sql);
            }
        }

        return $spid;
    }

    /**
     * 更新赔偿金额
     * @param $id
     * @param $compansateMoney
     * @return mixed
     */
    function updateCompansate_d($id, $compansateMoney) {
        return $this->update(array('id' => $id), array('compensateMoney' => $compansateMoney));
    }
    
    /**
     * 获取赔偿单已录入扣款金额
     * @param $id
     * @return mixed
     */
    function getDeductMoney_d($id) {
    	$sql = "
    		SELECT
				SUM(deductMoney) AS deductMoney
			FROM
				oa_finance_compensate_deduct
			WHERE
				compensateId = ".$id;
    	$rs = $this->findSql($sql);
    	return $rs[0]['deductMoney'];
    }
    
    /**
     * 录入扣款方法
     * @param $object array
     * @return boolean
     */
    function deduct_d($object) {
    	try {
    		$this->start_d();
    		
    		//增加操作人信息
    		$object['operateId'] = $_SESSION['USER_ID'];
    		$object['operateName'] = $_SESSION['USERNAME'];
    		$object['operateTime'] = date('Y-m-d H:i:s');
    		//数据字典处理
    		$object = $this->processDatadict($object);
    		//新增录入扣款记录
			$deductDao = new model_finance_compensate_compensatededuct();
    		$deductDao->add_d($object);
    		//更新赔偿单状态
    		$id = $object['compensateId'];//赔偿单id
    		$deductMoney = $this->getDeductMoney_d($id);//赔偿单已录入扣款金额
			$rs = $this->find(array('id' => $id),null,'compensateMoney');
			$compensateMoney = $rs['compensateMoney'];//赔偿单确认赔偿金额
			if($deductMoney == $compensateMoney){
				$this->update(array('id' => $id), array('formStatus' => '4'));
			}
    
    		$this->commit_d();
    		return true;
    	} catch (Exception $e) {
    		$this->rollBack();
    		return false;
    	}
    }
}