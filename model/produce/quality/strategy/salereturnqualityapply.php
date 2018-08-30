<?php
//引入接口
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';
/**
 * 借试用归还质检申请
 * @author kuangzw
 */
class model_produce_quality_strategy_salereturnqualityapply extends model_base implements iqualityapply {
	/**
	 * 私有成员 - 业务对象类
	 */
	private $mainClass = "model_projectmanagent_return_return";
	private $detailClass = "model_projectmanagent_return_returnequ";

	/**
	 * 新增质检申请时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$detailDao = new $this->detailClass();
		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $value ) {
				$sql = "update $detailDao->tbl_name set  qualityNum=qualityNum+{$value['qualityNum']} where id={$value['relDocItemId']}";
//				echo $sql;
				$detailDao->query ( $sql );
			}
            //加个状态更新的方法 - 下达质检后状态变为质检中
			$mainDao = new $this->mainClass();
			$mainDao->updateDisposeState_d($paramArr['relDocId'],1);
		}
	}

	/**
	 * 修改质检申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE) {

	}

	/**
	 * 通过质检调用方法
	 * 归还申请id
	 * 归还明细id
	 * 通过数量
	 */
	function dealRelItemPass( $relDocId,$relDocItemId,$qualityNum ){
//		实例化收料明细,更新收料数量
		$detailDao = new $this->detailClass();
		$detailDao->editQualityInfo("",$relDocItemId,$qualityNum);

        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($relDocId,3);//质检完成
	}

    /**
     * 损坏方形调用方法
     * @param $relDocId
     * @param $relDocItemId
     * @param $qualityNum
     */
    function dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum) {
        // 实例化收料明细,更新收料数量
        $detailDao = new $this->detailClass();
        $detailDao->editQualityBackInfo("", $relDocItemId, 0, $qualityNum);

        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($relDocId, 3); //质检完成
        // 更新赔偿状态
        $mainDao->updateState_d($relDocId, 1);
    }

	/**
	 * 审核质检申请时源单据业务处理
	 * @param  $paramArr 更新质检合格数的数组
	 */
	function dealRelInfoAtConfirm($paramArr = false) {
		$detailDao=new $this->detailClass();
		$detailDao->editQualityInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);

        //质检完成
        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],3);
	}

    /**
     * 审核之间退回时处理收料单
     * @param  $paramArr 更新质检合格数的数组
     */
    function dealRelInfoAtBack($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityBackInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);

        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],3);//质检完成
    }

    /**
     * 审核之间让步接收时处理收料单
     * @param  $paramArr 更新质检合格数的数组
     * @param  $relItemArr 质检报告书内容
     */
    function dealRelInfoAtReceive($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityReceiceInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);
        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],3);//质检完成
    }

    /**
     * 撤销质检报告
     */
	function dealRelInfoAtUnconfirm($paramArr = false){
		$detailDao=new $this->detailClass();
        $detailDao->editQualityUnconfirmInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);
        $mainDao = new $this->mainClass();
        $mainDao->updateDisposeState_d($paramArr['relDocId'],1);//质检完成
	}

	/**
	 * 获取源单清单信息
	 */
	function getRelDocInfo($id) {
		$mainDao = new $this->mainClass();
		$mainObj = $mainDao->get_d ( $id );
		$conDao = new model_contract_contract_contract();
		$conInfo = $conDao->get_d($mainObj['contractId']);
		$rtObj = array(
			'supplierName' => $conInfo['customerName'],
            'supplierId' => $conInfo['customerId'],
			'applyUserCode' => $mainObj['createId'],
            'applyUserName' => $mainObj['createName']
		);
		return $rtObj;
	}

	/**
	 * 获取从表数据
	 */
	function getRelDetailInfo($id){
		$detailDao = new $this->detailClass();
		$detailDao->searchArr = array('returnId' => $id);
        $detailArr = $detailDao->list_d();

        //构建数据
        $rtArr = array();
        if(!empty($detailArr)){
            //实例化物料
            $productInfoDao = new model_stock_productinfo_productinfo();
            //数据字典
            $datadictDao = new model_system_datadict_datadict();
            foreach($detailArr as $val){
            	$canQualityNum = $val['number'] - $val['qualityNum'];
                if($canQualityNum <= 0){
                    continue;
                }
                //质检方式
                $productInfo = $productInfoDao->get_d($val['productId']);
                array_push($rtArr,array(
                    'productId' => $val['productId'],
                    'productCode' => $val['productCode'],
                    'productName' => $val['productName'],
                    'pattern' => $productInfo['pattern'],
                    'unitName' => $productInfo['unitName'],
                    'qualityNum' => $canQualityNum,
                    'relDocItemId' => $val['id'],
					'serialId' => "",
                    'serialName' => "",
                    'checkType' => $productInfo['checkType'],
                    'checkTypeName' => $datadictDao->getDataNameByCode($productInfo['checkType'])
                ));
            }
        }
		return $rtArr;
	}

	/**
	 * 判断是否可撤销
	 */
	function checkCanBack_d($itemIdArr){
		$itemIds = implode(',',$itemIdArr);
		$detailDao = new $this->detailClass();
		$sql = "select sum(disposeNumber) as disposeNumber from $detailDao->tbl_name where id in ($itemIds) ";
		$rs = $detailDao->_db->getArray($sql);
		//如果已经有数量，则返回false
		if($rs[0]['disposeNumber']){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 调用主业务状态更新方法 -- 审核时直接处理完成,不再做单据更新处理
	 */
	function dealRelInfoCompleted($relDocId){
        return $relDocId;
	}

	/**
	 * 质检申请打回调用方法
	 * 源单id
	 * 源单明细id
	 * 打回数量
	 */
	function dealRelItemBack( $relDocId,$relDocItemId,$qualityNum ){
		$detailDao = new $this->detailClass();
		return $detailDao->editQualityInfoAtBack("",$relDocItemId,$qualityNum);
	}

	/**
	 * 调用主业务状态更新方法 -用于质检申请打回
	 */
	function dealRelInfoBack($relDocId){
		try{
			//更新单据状态
			$mainDao = new $this->mainClass();
			$mainDao->updateBusinessByBack($relDocId);
		}catch(Exception $e){
			throw $e;
		}
	}
}