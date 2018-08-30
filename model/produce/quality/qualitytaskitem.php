<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 15:09:03
 * @version 1.0
 * @description:交检任务单清单 Model层
 */
class model_produce_quality_qualitytaskitem  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_produce_quality_taskitem";
		$this->sql_map = "produce/quality/qualitytaskitemSql.php";
		parent::__construct ();
	}

    //状态返回
    function rtStatus($thisVal){
		switch($thisVal){
			case "YJY" : return "已检验"; break;
			case "" : return "未检验"; break;
			case "YBCBG" : return "已保存报告"; break;
			case "BH" : return "驳回"; break;
			default : return "非法状态";
		}
    }

	/**
	 *
	 * 校验分配数量
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