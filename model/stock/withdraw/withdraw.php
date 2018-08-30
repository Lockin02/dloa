<?php
/**
 * @author Administrator
 * @Date 2012年11月8日 11:04:46
 * @version 1.0
 * @description:收货通知单 Model层
 */
header("Content-type: text/html; charset=gb2312");

class model_stock_withdraw_withdraw extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_stock_withdraw";
        $this->sql_map = "stock/withdraw/withdrawSql.php";
        parent::__construct();
        $this->relatedStrategyArr = array( //不同类型出库申请策略类,根据需要在这里进行追加
            "oa_contract_exchangeapply" => "model_stock_withdraw_strategy_exchangedraw", //退货发货
        );
    }

    //公司权限处理
    protected $_isSetCompany = 1;

    /*===================================页面模板======================================*/
    /**
     * @description 发货计划列表显示模板
     * @param $rows
     */
    function showList($rows, drawStrategy $istrategy)
    {
        $istrategy->showList($rows);
    }

    /**
     * @description 新增发货计划时，清单显示模板
     * @param $rows
     */
    function showItemAdd($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemAdd($rows);
    }

    /**
     * @description 修改发货计划时，清单显示模板
     * @param $rows
     */
    function showItemEdit($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemEdit($rows);
    }

    /**
     * @description 变更发货计划时，清单显示模板
     * @param $rows
     */
    function showItemChange($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemChange($rows);
    }

    /**
     * @description 查看发货计划时，清单显示模板
     * @param $rows
     */
    function showItemView($rows, drawStrategy $istrategy)
    {
        return $istrategy->showItemView($rows);
    }

    /**
     * 查看相关业务信息
     * @param $paramArr
     */
    function viewRelInfo($paramArr = false, $skey = false, drawStrategy $istrategy)
    {
        return $istrategy->viewRelInfo($paramArr, $skey);
    }

    /**
     * 下推获取源单数据方法
     */
    function getDocInfo($id, drawStrategy $strategy)
    {
        $rows = $strategy->getDocInfo($id);
        return $rows;
    }

    /**
     * 新增发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtAdd(drawStrategy $istrategy, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtAdd($relItemArr);
    }

    /**
     * 变更发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtChange(drawStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtChange($paramArr, $relItemArr);
    }

    /**
     * 修改发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtEdit(drawStrategy $istrategy, $paramArr = false, $relItemArr = false)
    {
        return $istrategy->dealRelInfoAtEdit($paramArr, $relItemArr);
    }

    /**
     * 删除发货计划时源单据业务处理
     * @param $istorageapply 策略接口
     * @param  $paramArr 主表参数数组 :$relDocId->关联单据id;$relDocCode->关联单据编码;
     * @param  $relItemArr 从表清单信息
     */
    function ctDealRelInfoAtDel(drawStrategy $istrategy, $id)
    {
        return $istrategy->dealRelInfoAtDel($id);
    }
    /*--------------------------------------------业务操作--------------------------------------------*/

    /**
     * 新增保存
     * @see model_base::add_d()
     */
    function add_d($object)
    {
        try {
            $this->start_d();
            if (is_array($object ['items'])) {
                $object['issuedStatus'] = '1';
                $object['planIssuedDate'] = day_date;
                $codeRuleDao = new model_common_codeRule();
                $object['planCode'] = $codeRuleDao->sendDrawCode($this->tbl_name);
                $id = parent::add_d($object, true);
                $equDao = new model_stock_withdraw_equ();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $id, $object ['items']);
                $itemsObj = $equDao->saveDelBatch($itemsArr);
                $object ['items'] = $itemsObj; //只处理有效的数据
                $outStrategy = $this->relatedStrategyArr[$object['docType']];
                $storageproId = $this->ctDealRelInfoAtAdd(new $outStrategy (), $object);
            } else {
                throw new Exception ("单据信息不完整，请确认！");
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
     * @see model_base::edit_d()
     */
    function edit_d($object)
    {
        try {
            $this->start_d();
            if (is_array($object ['items'])) {
                $editResult = parent::edit_d($object, true);
                $equDao = new model_stock_withdraw_equ();
                $itemsArr = util_arrayUtil::setItemMainId("mainId", $object ['id'], $object ['items']);
                $itemsObj = $equDao->saveDelBatch($itemsArr);
            } else {
                throw new Exception ("单据信息不完整，请确认！");
            }
            $this->commit_d();
        } catch (Exception $e) {
            $this->rollBack();
            return null;
        }
    }

    /**
     * 通过id获取详细信息
     * @see model_base::get_d()
     */
    function get_d($id)
    {
        $object = parent::get_d($id);
        $equDao = new model_stock_withdraw_equ();
        $equDao->searchArr ['mainId'] = $id;
        $object ['items'] = $equDao->listBySqlId();
        return $object;
    }

    /**
     * 填写发货单后对应的业务操作
     * 1.修改发货计划单据状态
     */
    function updateBusinessByNotice($id)
    {
        $proNumSql = "SELECT
				sum(op.number) AS number,
				sum(op.executedNum) AS executedNum
			FROM
				oa_stock_withdraw_equ op
			WHERE
				op.mainId = $id";
        $proNum = $this->_db->getArray($proNumSql);
        //已执行数 = 申请数
        if ($proNum[0]['number'] == $proNum[0]['executedNum']) {
            $status = 'YZX';
        } else {
            $status = 'BFZX';
        }
        if (isset($status)) {
            return $this->update(array('id' => $id), array('status' => $status));
        } else {
            return true;
        }
    }

    /**
     * 更新docStatus
     */
    function updateDocStatus_d($id, $state)
    {
        try {
            $sql = "update $this->tbl_name set docStatus='$state' where id = $id ";
            $this->_db->query($sql);
            return true;
        } catch (Exception $e) {
            throw $e;
            return false;
        }
    }

    /**
     * 根据归还单id 判断并更新 处理状态
     */
    function updateStateAuto_d($id)
    {
        $sql = "select
                sum(number) as number,sum(qPassNum + qBackNum) as qNumber,
                sum(qBackNum) as qBackNum,sum(compensateNum) as compensateNum
            from oa_stock_withdraw_equ where mainId = '$id'";
        $numArr = $this->_db->getArray($sql);

        if ($numArr[0]['qBackNum'] == $numArr[0]['compensateNum'] && $numArr[0]['number'] == $numArr[0]['qNumber']) {
            $docStatus = '2';
        }
        //拿到状态才更新
        if (isset($docStatus)) {
            $this->update(array('id' => $id), array('docStatus' => $docStatus));
        }
    }

    /**
     * 质检申请打回后对应的业务操作
     */
    function updateBusinessByBack($id)
    {
        $proNumSql = "SELECT
    	sum(op.qualityNum) AS qualityNum
    	FROM
    	oa_stock_withdraw_equ op
    	WHERE
    	op.mainId = $id";
        $proNum = $this->_db->getArray($proNumSql);
        if ($proNum[0]['qualityNum'] == '0') {
            $status = 'WZX';
        } else {
            $status = 'BFZX';
        }
        if (isset($status)) {
            return $this->update(array('id' => $id), array('status' => $status));
        } else {
            return true;
        }
    }
}