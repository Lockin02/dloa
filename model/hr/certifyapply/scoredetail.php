<?php
/**
 * @author Show
 * @Date 2012年8月24日 星期五 11:43:13
 * @version 1.0
 * @description:任职资格评委打分表 Model层
 */
class model_hr_certifyapply_scoredetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyapplyassess_scoredetail";
		$this->sql_map = "hr/certifyapply/scoredetailSql.php";
		parent :: __construct();
	}


	/**
	 * 获取评委打分汇总 - 特殊处理
	 * 数组为
	 * array(
	 *     'admin' => array(
	 *	       '行为要项id' => '得分',
	 *         '行为要项id' => '得分',
	 * 	   )
	 * )
	 */
	function getScoreDetail_d($assessId){
		$this->searchArr = array('assessId' => $assessId);
		$rs = $this->list_d();
		if($rs){
			//返回数组
			$rtArr = array();

			foreach($rs as $key => $val){
				$rtArr[$val['managerId']][$val['detailId']]['score'] = $val['score'];
				$rtArr[$val['managerId']][$val['detailId']]['id'] = $val['id'];
				$rtArr[$val['managerId']][$val['detailId']]['scoreId'] = $val['scoreId'];
			}

			return $rtArr;
		}else{
			return false;
		}
	}
}
?>