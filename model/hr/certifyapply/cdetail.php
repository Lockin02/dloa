<?php

/**
 * @author Show
 * @Date 2012年8月23日 星期四 9:40:38
 * @version 1.0
 * @description:任职资格等级认证评价表明细 Model层
 */
class model_hr_certifyapply_cdetail extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_certifyapplyassess_detail";
		$this->sql_map = "hr/certifyapply/cdetailSql.php";
		parent :: __construct();
	}

	/**
	 * 列表数据获取
	 */
	function getCdetail_d($assessId){
		$this->searchArr = array(
			'assessId' => $assessId
		);
		$this->asc = false;

		return $this->list_d();
	}
	/*
	 * 根据assessId获取各评委评分更新评价明细表
	 */
	 function updateByAssessId($assessId){
	 	$sql1 = "select c.managerName,c.managerId,c.memberName,c.memberId from oa_hr_certifyapplyassess c where c.id=$assessId";
	 	$managers = $this->_db->getArray($sql1);
	 	$mainManager = $managers[0]['managerId'];
	 	$otherManagers = split(",",$managers[0]['memberId']);
		$managersNum = count($otherManagers)+1;

		$sql2 = "select c.id,c.detailName,c.weights from oa_hr_certifyapplyassess_detail c where c.assessId=$assessId";
		$details = $this->_db->getArray($sql2);

		$sql3 = "select c.cdetailId,c.detailName,c.managerName,c.managerId,c.weights,c.score from oa_hr_certifyapplyassess_scoredetail c where c.assessId=$assessId";
		$scores = $this->_db->getArray($sql3);

		foreach($details as $key1 => $val1){
			$scoreDetails=array();
			$sum = 0;//所有评委评分之和
			$maxscore = 0;
			$minscore = 10;
			foreach($scores as $key => $val){
				if($scores[$key]['cdetailId']==$details[$key1]['id']){
					if($scores[$key]['managerId']==$mainManager){
						$mainScore = $scores[$key]['score'];
					}
					if($scores[$key]['score']>$maxscore){
						$maxscore = $scores[$key]['score'];
					}
					if($scores[$key]['score']<$minscore){
						$minscore = $scores[$key]['score'];
					}
					//echo $scores[$key]['score'];
					$sum += $scores[$key]['score'];
				}
			}
			//主管与评委平均分分差
			$averageDifference =abs($mainScore - round(($sum-$mainScore)/($managersNum-1),2));
			//
			$scoreDetails=array("id"=>$val1['id'],"weights"=>$details[$key1]['weights'],"averageScore"=>round($sum/$managersNum,2),"weightScore"=>round(round($sum/$managersNum,2)*$details[$key1]['weights']/100,2),"averageDifference"=>$averageDifference,"maxDifference"=>round($maxscore-$minscore,2));
			parent::edit_d($scoreDetails,true);
		}
	 }
}
?>