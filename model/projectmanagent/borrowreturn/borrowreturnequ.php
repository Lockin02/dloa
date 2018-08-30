<?php
/**
 * @author Administrator
 * @Date 2012-12-20 10:33:32
 * @version 1.0
 * @description:借试用归还物料从表 Model层
 */
class model_projectmanagent_borrowreturn_borrowreturnequ extends model_base {

	function __construct() {
		$this->tbl_name = "oa_borrow_return_equ";
		$this->sql_map = "projectmanagent/borrowreturn/borrowreturnequSql.php";
		parent :: __construct();
	}

	/**
	 * 获取借试用归还物料明细
	 */
	function getDetail_d($returnId){
		$this->searchArr = array('returnId' => $returnId);
		return $this->list_d();
	}

    /**
     * 获取借试用归还物料明细
     */
    function getDamagePassDetail_d($returnId){
        $this->searchArr = array('returnId' => $returnId);
        return $this->list_d();
    }

	/**
	 * 根据equId统计数量
	 */
	function getNumByEquId_d($equId){
		$rs = $this->_db->getArray("select sum(number) as number from $this->tbl_name where equId = $equId");
		return $rs[0]['number'] ? $rs[0]['number'] : 0;
	}

	/**
	 * 更新物料的质检数量.
	 * @param  $arrivalId 收料通知单ID
	 * @param  $productId 物料Id
	 * @param  $proNum   质检数量
	 */
	function editQualityInfo($arrivalId,$equId,$proNum) {
    	$sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum+$proNum) where c.id=$equId";
    	$this->query($sql);
	}

    /**
     * 更新物料的收料数量 - 用于质检退回
     * @param  $arrivalId   收料通知单ID
     * @param  $productId   物料Id
     * @param  $proNum   质检数量
     */
    function editQualityBackInfo($arrivalId,$equId,$passNum,$receiveNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $passNum),c.qBackNum=(c.qBackNum+$receiveNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新物料的质检数量. - 用于质检让步接收
     * @param  $arrivalId   收料通知单ID
     * @param  $productId   物料Id
     * @param  $proNum   质检数量
     */
    function editQualityReceiceInfo($arrivalId,$equId,$proNum,$backNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum + $proNum),c.qBackNum=(c.qBackNum+$backNum) where  c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新物料的质检数量. - 用于质检报告撤销
     * @param  $arrivalId   收料通知单ID
     * @param  $productId   物料Id
     * @param  $proNum   质检数量
     */
    function editQualityUnconfirmInfo($arrivalId,$equId,$proNum) {
        $sql = "update $this->tbl_name c set c.qPassNum=(c.qPassNum-$proNum) where c.id=$equId";
        $this->query($sql);
    }

    /**
     * 更新下达数量
     */
    function editDisposeNumber($equId,$disposeNum){
		$sql = "update $this->tbl_name set disposeNumber = disposeNumber+$disposeNum where id = '$equId'";
        $this->query($sql);
    }

    /**
     * 更新下达出库数量
     */
    function editOutNumber($equId,$outNum){
		$sql = "update $this->tbl_name set outNum = outNum+$outNum where id = '$equId'";
        $this->query($sql);
    }

    /**
     * 更新下达数量
     */
    function editCompensateNumber($equId,$num){
		$sql = "update $this->tbl_name set compensateNum = compensateNum+$num where id = '$equId'";
        $this->query($sql);
    }

    /**
     * 数据过滤
     */
    function filterArr_d($rows,$applyType){
		if(!empty($rows)){
			//初始值设定
            if($applyType == 'JYGHSQLX-01'){
                foreach($rows as &$val){
                    //初始化归还序列号的值
                    $failureSerailNums = $failureSerailIds = '';

                    $val['borrowequId'] = $val['equId'];
                    $val['disposeNum'] = $val['qPassNum'] + $val['qBackNum'] - $val['disposeNumber'];
                    //如果存在不合格数量，查找已下达出库数量
                    $outNum = 0;
                    if(!empty($val['qBackNum'])){
                        if(!isset($returnDisEquDao)){
                            $returnDisEquDao = new model_projectmanagent_borrowreturn_borrowreturnDisequ();
                        }
                        //查询下达出库的数量
                        $outNum = $returnDisEquDao->getOutNum_d($val['equId']);

                        //当设备含有序列号时
                        if($val['serialName']){
                            //含有不合格数量的时候获取不合格序列号
                            if(!isset($failureItemDao)){
                                $failureItemDao = new model_produce_quality_failureitem();
                            }
                            $failureSerailNums = $failureItemDao->getSerailNums_d($val['id']);
                            //如果含有序列号，匹配出相应id
                            if($failureSerailNums){
                                $serialNameArr = array_flip(explode(',',$val['serialName']));// 序列号的反转，用于匹配id序号
                                $serialIdArr = explode(',',$val['serialId']);
                                $serialIdOutArr = array();
                                $failureSerailNumArrs = explode(',',$failureSerailNums);
                                foreach($failureSerailNumArrs as $v){
                                    if($serialNameArr[$v] !== false){
                                        array_push($serialIdOutArr,$serialIdArr[$serialNameArr[$v]]);
                                    }
                                }
                                //id数组转成string
                                $failureSerailIds = implode(',',$serialIdOutArr);
                            }
                        }
                    }
                    $val['outNum'] = $val['qBackNum'] - $outNum;
                    $val['outNum'] = $val['outNum'] > $val['disposeNum'] ? $val['disposeNum'] : $val['outNum'];
                    $val['serialOutName'] = $failureSerailNums;
                    $val['serialOutId'] = $failureSerailIds;
                }
            }else{
                foreach($rows as &$val){
                    $val['outNum'] = $val['number'];
                    $val['disposeNum'] = $val['number'];
                    $val['serialOutName'] = $val['serialName'];
                    $val['serialOutId'] = $val['serialId'];
                }
            }
		}
		return $rows;
    }

    /**
     * 数据过滤
     */
    function filterArrCompensate_d($rows,$applyType){
		if(!empty($rows)){
			//初始值设定
			foreach($rows as &$val){
				$val['borrowequId'] = $val['equId'];
				if($applyType == 'JYGHSQLX-01'){
					$val['disposeNum'] = $val['qPassNum'] + $val['qBackNum'] - $val['disposeNumber'];
					//如果存在不合格数量，查找已下达出库数量
					if(!empty($val['qBackNum'])){
						if(!isset($compesateDetailDao)){
							$compesateDetailDao = new model_finance_compensate_compensatedetail();
						}
						//查询下达出库的数量
						$compensateNum = $compesateDetailDao->getCompensateNum_d($val['returnequId']);
					}
					$val['number'] = $val['qBackNum'] - $compensateNum;
					$val['allowNum'] = $val['number'];
				}else{
					$val['allowNum'] = $val['number'];
				}
			}
		}
		return $rows;
    }
    
    /**
     * 更新物料的质检数量. - 用于质检申请打回
     * @param  $mainId	收料通知单ID
     * @param  $equId	明细Id
     * @param  $proNum	质检数量
     */
    function editQualityInfoAtBack($mainId, $equId, $proNum) {
    	$sql = "update $this->tbl_name c set c.qualityNum=(c.qualityNum-$proNum) where c.id=$equId";
    	$this->query($sql);
    }
}