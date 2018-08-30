<?php
/**
 * @author show
 * @Date 2013年10月24日 19:30:28
 * @version 1.0
 * @description:赔偿单明细 Model层
 */
class model_finance_compensate_compensatedetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_finance_compensate_detail";
		$this->sql_map = "finance/compensate/compensatedetailSql.php";
		parent :: __construct();
	}

	public $datadictFieldArr = array(
    	'compensateType'
    );

    /**
     * 重新add_d
     */
    function add_d($object){
    	$object = $this->processDatadict($object);
    	return parent::add_d($object);
    }

    /**
     * 重新edit_d
     */
    function edit_d($object){
    	$object = $this->processDatadict($object);
    	return parent::edit_d($object);
    }

    /**
     * 关闭
     */
    function close_d($mainId){
		try{
			$this->start_d();

			$datas = $this->findAll(array('mainId' => $mainId));
			foreach($datas as $val){
				$val['compensateType'] = 'PCFSX-02';
				$val['compensateRate'] = 0;
				$val['compensateMoney'] = 0;
				$this->edit_d($val);
			}

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			throw $e;
			return false;
		}
    }

    /**
     * 获取已赔偿数量
     */
    function getCompensateNum_d($returnequId){
		$sql = "select sum(number) as number from $this->tbl_name where returnequId = $returnequId";
		$rs = $this->_db->getArray($sql);
		if($rs[0]['number']){
			return $rs[0]['number'];
		}else{
			return 0;
		}
    }

    /**
     * 更新赔偿金额
     */
    function updateCompensateMoney_d($id,$detailCompensateMoney,$mainId,$compensateMoney){
        try{
            $this->start_d();

            //更新金额
            $this->update(array('id' => $id),array('compensateMoney' => $detailCompensateMoney));

            //更新单据总金额
            $compensateDao = new model_finance_compensate_compensate();
            $compensateDao->updateCompansate_d($mainId,$compensateMoney);

            $this->commit_d();
            return true;
        }catch(Exception $e){
            $this->rollBack();
            return false;
        }
    }
}