<?php
//引入接口
include_once WEB_TOR . 'model/produce/quality/strategy/iqualityapply.php';
/**
 * 借试用归还质检申请
 * @author kuangzw
 */
class model_produce_quality_strategy_exchangequalityapply extends model_base implements iqualityapply {
	/**
	 * 私有成员 - 业务对象类
	 */
	private $mainClass = "model_stock_withdraw_withdraw";
	private $detailClass = "model_stock_withdraw_equ";

	/**
	 * 新增质检申请时处理相关业务信息
	 * @param $paramArr
	 * @param $relItemArr
	 */
	function dealRelInfoAtAdd($paramArr = false, $relItemArr = false) {
		$detailDao = new $this->detailClass();
		if (is_array ( $relItemArr )) {
			foreach ( $relItemArr as $key => $value ) {
				$sql = "update $detailDao->tbl_name set  qualityNum=qualityNum+{$value['qualityNum']} where id={$value['relDocItemId']}";
//				echo $sql;
				$detailDao->query ( $sql );
			}
            //加个状态更新的方法 - 是否已经完全下达质检，是则更新状态为质检完成
			$mainDao = new $this->mainClass();
			$mainDao->updateBusinessByNotice($paramArr['relDocId']);
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
		return $detailDao->editQualityInfo("",$relDocItemId,$qualityNum);
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
    }

	/**
	 * 审核质检申请时源单据业务处理
	 * @param  $paramArr 更新质检合格数的数组
	 */
	function dealRelInfoAtConfirm($paramArr = false) {
		$detailDao=new $this->detailClass();
		$detailDao->editQualityInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);
	}

    /**
     * 审核之间退回时处理收料单
     * @param  $paramArr 更新质检合格数的数组
     */
    function dealRelInfoAtBack($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityBackInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);
        $mainDao = new $this->mainClass();
        $mainDao->updateDocStatus_d($paramArr['relDocId'],1);
    }

    /**
     * 审核之间让步接收时处理收料单
     * @param  $paramArr 更新质检合格数的数组
     */
    function dealRelInfoAtReceive($paramArr = false) {
		$detailDao=new $this->detailClass();
    	$detailDao->editQualityReceiceInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['passNum'],$paramArr['receiveNum']);
        //更新赔偿状态
        if($paramArr['backNum']){
            $mainDao = new $this->mainClass();
            $mainDao->updateDocStatus_d($paramArr['relDocId'],1);
        }
    }

    /**
     * 撤销质检报告
     */
	function dealRelInfoAtUnconfirm($paramArr = false){
		$detailDao=new $this->detailClass();
        $detailDao->editQualityUnconfirmInfo($paramArr['relDocId'],$paramArr['relDocItemId'],$paramArr['thisCheckNum']);
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
            'applyUserCode' => $mainObj['docApplicantId'],
            'applyUserName' => $mainObj['docApplicant']
		);
		return $rtObj;
	}

	/**
	 * 获取从表数据
	 */
	function getRelDetailInfo($id){
		$detailDao = new $this->detailClass();
		$detailDao->searchArr = array('mainId' => $id);
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
                    'pattern' => $val['productModel'],
                    'unitName' => $productInfo['unitName'],
                    'qualityNum' => $canQualityNum,
                    'relDocItemId' => $val['id'],
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
		$sql = "SELECT
				ifnull(sum(sp.number), 0) AS spNum
			FROM
				oa_stock_withdraw_equ op
			LEFT JOIN oa_stock_innotice_equ sp ON (op.id = sp.planEquId)
			WHERE
				op.id in ($itemIds) ";
		$rs = $detailDao->_db->getArray($sql);
		//如果已经有数量，则返回false
		if($rs[0]['spNum']){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 调用主业务状态更新方法
	 */
	function dealRelInfoCompleted($relDocId){
		try{
			//更新单据状态
			$mainDao = new $this->mainClass();
			$mainDao->updateBusinessByNotice($relDocId);
		}catch(Exception $e){
			throw $e;
		}
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