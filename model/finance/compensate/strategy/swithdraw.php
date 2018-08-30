<?php
/*
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/compensate/icompensate.php');

/**
 * 试用项目策略
 */
class model_finance_compensate_strategy_swithdraw implements icompensate{
	//对应业务类
	private $thisClass = 'model_stock_withdraw_withdraw';
	//明细
	private $detailClass = 'model_stock_withdraw_equ';

	/**
	 * 业务数据获取
	 */
	function businessGet_i($relId,$mainDao){
		//对应业务
		$thisDao = new $this->thisClass();
		$thisObj = $thisDao->get_d($relId);

		$thisObj['objCode'] = $thisObj['docCode'];
		$thisObj['objId'] = $thisObj['docId'];
		$thisObj['objType'] = 'PCYWLX-02';
		$thisObj['chargerName'] = $thisObj['createName'];
		$thisObj['chargerId'] = $thisObj['createId'];
		$thisObj['applyTypeName'] = '';
		$thisObj['applyType'] = '';
		$thisObj['relDocCode'] = $thisObj['planCode'];

        //获取部门信息
        $otherdataDao = new model_common_otherdatas();
        $userInfo = $otherdataDao->getUserDatas($thisObj['createId']);
        $thisObj['deptName'] = $userInfo['DEPT_NAME'];
        $thisObj['deptId'] = $userInfo['DEPT_ID'];

        //商机信息
		$thisObj['chanceId'] = '';
		$thisObj['chanceCode'] = '';
		$thisObj['chanceName'] = '';
		$thisObj['province'] = '';
		$thisObj['salemanId'] = '';
		$thisObj['salemanName'] = '';
		$thisObj['qualityObjType'] = "ZJSQYDHH";

		return $thisObj;
	}

    /**
     * 获取赔偿单明细
     */
    function businessGetDetail_i($condition){
        $detailDao = new $this->detailClass();
        $applyType = $condition['applyType'];//申请类型
        unset($condition['applyType']);
        $condition['mainId'] = $condition['relDocId'];//源单ID
        //如果是申请归还
        $condition['numSql'] = 'sql:and (c.compensateNum < c.qBackNum)';
        $detailDao->getParam($condition);
        $rows = $detailDao->list_d();

        //数据处理
        if($rows){
            foreach($rows as &$val){
                $val['productNo'] = $val['productCode'];
                $val['returnequId'] = $val['id'];

				if(!isset($compesateDetailDao)){
					$compesateDetailDao = new model_finance_compensate_compensatedetail();
				}
				//查询下达出库的数量
				$compensateNum = $compesateDetailDao->getCompensateNum_d($val['returnequId']);

				$val['allowNum'] = $val['qBackNum'] - $compensateNum;
				$val['number'] = $val['allowNum'];
            }
        }

        return $rows;
    }

    /**
     * 获取赔偿单明细
     */
    function getSerialNos_i($condition,$mainDao){
        return array();
    }

	/**
	 * 新增业务处理
	 */
	function businessAdd_i($obj,$detail,$mainDao){
		//更新源单状态为 执行中
		$detailDao = new $this->detailClass();
		//对应业务
		$thisDao = new $this->thisClass();

		try{
			$detailDao->start_d();

			//更新源单相关数量
			foreach($detail as $val){
				$detailDao->editCompensateNumber($val['returnequId'],$val['number']);
			}

			//更新主表业务
			$thisDao->updateStateAuto_d($obj['relDocId']);

			$detailDao->commit_d();
			return true;
		}catch(Exception $e){
			$detailDao->rollBack();
			throw $e;
			return false;
		}
	}
}