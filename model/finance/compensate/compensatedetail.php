<?php
/**
 * @author show
 * @Date 2013��10��24�� 19:30:28
 * @version 1.0
 * @description:�⳥����ϸ Model��
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
     * ����add_d
     */
    function add_d($object){
    	$object = $this->processDatadict($object);
    	return parent::add_d($object);
    }

    /**
     * ����edit_d
     */
    function edit_d($object){
    	$object = $this->processDatadict($object);
    	return parent::edit_d($object);
    }

    /**
     * �ر�
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
     * ��ȡ���⳥����
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
     * �����⳥���
     */
    function updateCompensateMoney_d($id,$detailCompensateMoney,$mainId,$compensateMoney){
        try{
            $this->start_d();

            //���½��
            $this->update(array('id' => $id),array('compensateMoney' => $detailCompensateMoney));

            //���µ����ܽ��
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