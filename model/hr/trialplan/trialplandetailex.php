<?php
/**
 * @author Show
 * @Date 2012年9月4日 星期二 13:30:45
 * @version 1.0
 * @description:任务扩展信息 Model层
 */
 class model_hr_trialplan_trialplandetailex  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_trialplan_expand";
		$this->sql_map = "hr/trialplan/trialplandetailexSql.php";
		parent::__construct ();
	}

	/**
	 * 设置积分规则
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

                //获取最大最小
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
	 * 计算积分
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
	 * 获取积分信息
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