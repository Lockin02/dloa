<?php
/**
 * @author Administrator
 * @Date 2012-08-29 17:09:05
 * @version 1.0
 * @description:导师奖励管理--明细 Model层
 */
 class model_hr_tutor_rewardinfo  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_rewardinfo";
		$this->sql_map = "hr/tutor/rewardinfoSql.php";
		parent::__construct ();
	}

	/**
	 * 根据导师和学员获取信息
	 *
	 */
	 function getIsPublish_d($userNo,$studentAccount){
		$this->searchArr = array ('userAccount' => $userNo,'studentAccount'=>$studentAccount );
		$personnelRow= $this->listBySqlId ( "select_simple" );
		return $personnelRow['0']['isPublish'];
	 }
 }
?>