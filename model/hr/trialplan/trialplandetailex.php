<?php
/**
 * @author Show
 * @Date 2012��9��4�� ���ڶ� 13:30:45
 * @version 1.0
 * @description:������չ��Ϣ Model��
 */
 class model_hr_trialplan_trialplandetailex  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialplan_expand";
		$this->sql_map = "hr/trialplan/trialplandetailexSql.php";
		parent::__construct ();
	}

	/**
	 * ���û��ֹ���
	 */
	function setRule_d($object){
        $upperLimit = $lowerLimit = null;
		try{
			$this->start_d();
			$idArr = array ();
			foreach ( $object as $key => $val ) {
				unset($val['id']);
				$id = $this->add_d ( $val );
				array_push ( $idArr, $id );

                //��ȡ�����С
                if($upperLimit === null){
                    $upperLimit = $val['upperLimit'];
                    $lowerLimit = $val['lowerLimit'];
                }else{
                    $upperLimit = max($upperLimit,$val['upperLimit']);
                    $lowerLimit = min($lowerLimit,$val['lowerLimit']);
                }
			}
			$this->commit_d();
			return array( 'isRule' => implode($idArr,','),'upperLimit' => $upperLimit ,'lowerLimit' => $lowerLimit );
		}catch(exception $e){
			echo $e;
			$this->rollBack();
			return false;
		}
	}

	/**
	 * �������
	 */
	function calScore_d($ids,$score){
		$this->searchArr = array(
			'ids' => $ids,
			'inScore' => $score
		);
		$rs = $this->list_d();

		if($rs){
			return $rs[0]['score'];
		}else{
			return 0;
		}
	}

	/**
	 * ��ȡ������Ϣ
	 */
	function getRuleInfo_d($ids){
		$this->searchArr = array(
			'ids' => $ids
		);
		$rs = $this->list_d('select_maxmin');
		if($rs[0]['maxScore']){
			return $rs[0];
		}else{
			return false;
		}
	}
}
?>