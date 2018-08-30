<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 15:09:03
 * @version 1.0
 * @description:���������嵥 Model��
 */
class model_produce_quality_qualitytaskitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_taskitem";
		$this->sql_map = "produce/quality/qualitytaskitemSql.php";
		parent::__construct ();
	}

    //״̬����
    function rtStatus($thisVal){
		switch($thisVal){
			case "YJY" : return "�Ѽ���"; break;
			case "" : return "δ����"; break;
			case "YBCBG" : return "�ѱ��汨��"; break;
			case "BH" : return "����"; break;
			default : return "�Ƿ�״̬";
		}
    }

	/**
	 *
	 * У���������
	 */
	function checkAssignNum($id,$applyItemId,$assignNum){
		$taskItemObj=$this->get_d($id);
		$applyItemDao=new model_produce_quality_qualityapplyitem();
		$applyItemObj=$applyItemDao->get_d($applyItemId);

		$notAssinNum=$applyItemObj['qualityNum']-$applyItemObj['assignNum']+$taskItemObj['assignNum'];

		if($assignNum>$notAssinNum){
			return 0;
		}else{
			return 1;
		}
	}
}
?>