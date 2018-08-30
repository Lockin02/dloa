<?php
/**
 * @author Administrator
 * @Date 2012��7��19�� ������ 16:20:22
 * @version 1.0
 * @description:�������� Model��
 */
class model_hr_recruitment_interviewComment  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_interview_comment";
		$this->sql_map = "hr/recruitment/interviewCommentSql.php";
		parent::__construct ();
	}
	/**
	 * �����������
	 * ���������
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