<?php
/**
 * @author Administrator
 * @Date 2012年7月19日 星期四 16:20:22
 * @version 1.0
 * @description:面试评语 Model层
 */
class model_hr_recruitment_interviewComment  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_interview_comment";
		$this->sql_map = "hr/recruitment/interviewCommentSql.php";
		parent::__construct ();
	}
	/**
	 * 不存在则添加
	 * 存在则更新
	 */
	function addOrUpdate($obj){
		$condition=array('invitationId'=>$obj['invitationId'],'interviewerId'=>$obj['interviewerId']);
		$count=$this->findCount($condition);
		if($count){
			$re=$this->update($condition,array('useWriteEva'=>$obj['useWriteEva'],'interviewEva'=>$obj['interviewEva']));
			if($re) $re='update';
		}else{
			$re=$this->add_d($obj,true);
		}
		return $re;
	}
}
?>