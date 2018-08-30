<?php
//引入接口
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';
/**
 * 生产计划质检申请
 * @author kuangzw
 */
class model_produce_quality_strategy_producequalityapply extends model_base implements iqualityapply {
	/**
	 * 私有成员 - 业务对象类
	 */
	private $mainClass = "model_produce_plan_produceplan";

	/**
	 * 新增质检申请时处理相关业务信息
	 * @param $paramArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$mainDao = new $this->mainClass();
		if (is_array ( $relItemArr )) {
			$sql = "update $mainDao->tbl_name set qualityNum=qualityNum+{$relItemArr[0]['qualityNum']},docStatus='9' where id={$relItemArr[0]['relDocItemId']}";
			$mainDao->query( $sql );
		}
	}

	/**
	 * 修改质检申请时源单据业务处理
	 * @param  $paramArr
	 * @param  $relItemArr
	 */
	function dealRelInfoAtEdit($paramArr = false, $relItemArr = false,$lastItemArr=FALSE) {
		return true;
	}

	/**
	 * 通过质检调用方法
	 * 生产计划id
	 * 通过数量
	 */
	function dealRelItemPass($relDocId ,$relDocItemId ,$qualityNum) {
		//实例化更新收料数量
		$mainDao = new $this->mainClass();
		$mainDao->editQualifiedNum_d($relDocId ,$qualityNum);
		//更新计划单状态
		$mainDao->updateDocStatusByQuality($relDocId);
	}

    /**
     * 损坏方形调用方法
     * @param $relDocId
     * @param $relDocItemId
     * @param $qualityNum
     */
    function dealRelItemDamagePass($relDocId, $relDocItemId, $qualityNum) {
        //实例化更新收料数量
        $mainDao = new $this->mainClass();
        $mainDao->editQualifiedNum_d($relDocId ,$qualityNum);
        //更新计划单状态
        $mainDao->updateDocStatusByQuality($relDocId);
    }

	/**
	 * 审核质检申请时源单据业务处理
	 * @param  $paramArr 更新质检合格数的数组
	 */
	function dealRelInfoAtConfirm($paramArr = false) {
        //更新质检完成数量
        $mainDao = new $this->mainClass();
        $mainDao->editQualifiedNum_d($paramArr['relDocId'] ,$paramArr['thisCheckNum']);
        //更新计划单状态
        $mainDao->updateDocStatusByQuality($paramArr['relDocId']);
	}

    /**
     * 审核质检退回时处理收料单
     * @param  $paramArr 更新质检合格数的数组
     */
    function dealRelInfoAtBack($paramArr = false) {
		$mainDao = new $this->mainClass();
        $mainDao->editBackNum_d($paramArr);
    }

    /**
     * 审核质检让步接收时处理收料单
     * @param  $paramArr 更新质检合格数的数组
     * @param  $relItemArr 质检报告书内容
     */
    function dealRelInfoAtReceive($paramArr = false) {
    	return true;
    }

    /**
     * 撤销质检报告
     */
	function dealRelInfoAtUnconfirm($paramArr = false){
		//更新质检完成数量
		$mainDao = new $this->mainClass();
        $mainDao->editQualityUnconfirmInfo($paramArr['relDocId'],$paramArr['thisCheckNum']);
        //更新计划单状态
        $mainDao->updateDocStatusByQuality($paramArr['relDocId']);
	}
	
	/**
	 * 质检申请打回调用方法
	 * @param $relDocId 源单id
	 * @param $relDocItemId 源单明细id
	 * @param $qualityNum 打回数量
	 */
	function dealRelItemBack($relDocId,$relDocItemId,$qualityNum){
		//实例化
		$mainDao = new $this->mainClass();
		$mainDao->editQualityInfoAtBack($relDocId,$qualityNum);
		//更新计划单状态
		$mainDao->updateDocStatusByQuality($relDocId);
	}
	
	/**
	 * 调用主业务状态更新方法 -用于质检申请打回
	 */
	function dealRelInfoBack($relDocId){
		return true;
	}
	
	/**
	 * 获取源单清单信息
	 */
	function getRelDocInfo($id) {
		$mainDao = new $this->mainClass();
		$mainObj = $mainDao->get_d ( $id );
		$rtObj = array(
			'supplierName' => $mainObj['customerName'],
            'supplierId' => $mainObj['customerId'],
			'applyUserCode' => $_SESSION['USER_ID'],
            'applyUserName' => $_SESSION['USERNAME']
		);
		return $rtObj;
	}

	/**
	 * 没有从表,取主表质检物料
	 */
	function getRelDetailInfo($id){
		$mainDao = new $this->mainClass();
		$mainObj = $mainDao->get_d ( $id );
		
		$canQualityNum = $mainObj['planNum'] - $mainObj['qualityNum'];
		//构建数据
		$rtArr = array();
		if($canQualityNum > 0){
			//实例化物料
			$productInfoDao = new model_stock_productinfo_productinfo();
			$productInfo = $productInfoDao->get_d($mainObj['productId']);
			//数据字典
			$datadictDao = new model_system_datadict_datadict();
			array_push($rtArr,array(
				'productId' => $mainObj['productId'],
				'productCode' => $mainObj['productCode'],
				'productName' => $mainObj['productName'],
				'pattern' => $mainObj['pattern'],
				'unitName' => $mainObj['unitName'],
				'qualityNum' => $canQualityNum,
				'relDocItemId' => $mainObj['id'],
				'serialId' => $mainObj['serialId'],
				'serialName' => $mainObj['serialName'],
				'checkType' => $productInfo['checkType'],
				'checkTypeName' => $datadictDao->getDataNameByCode($productInfo['checkType'])
			));
		}
		return $rtArr;
	}

	/**
	 * 判断是否可撤销
	 * @param $idArr 计划单id数组
	 */
	function checkCanBack_d($idArr){
		$ids = implode(',',$idArr);
		//实例化
		$mainDao = new $this->mainClass();
		return $mainDao->checkCanBack_d($ids);
	}

	/**
	 * 调用主业务状态更新方法 -- 审核时直接处理完成,不再做单据更新处理
	 */
	function dealRelInfoCompleted($relDocId){
        return true;
	}
}