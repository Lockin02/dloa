<?php
/**
 * @author Administrator
 * @Date 2012-08-07 16:06:30
 * @version 1.0
 * @description:��ְ--��̸��¼�� Model��
 */
 class model_hr_leave_interview  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_interview";
		$this->sql_map = "hr/leave/interviewSql.php";
		parent::__construct ();
	}

	 /**
    * ��дadd����
    */
    function add_d($obj){
		try{
			$this->start_d();
			//������������
			$interviewId=parent::add_d($obj,true);
			//������̸��¼����ϸ
			$interviewDetailDao = new model_hr_leave_interviewDetail();


			$mainArr=array("parentId"=>$interviewId,"leaveId"=>$obj['leaveId']);
			$interviewer=explode(",",$obj['interviewer']);
			$interviewerId=explode(",",$obj['interviewerId']);

			//ѭ����̸�ߣ��ֱ����ӱ�
			foreach($interviewerId as $key =>$val){
				$itemsArr=null;$interviewerArray = null;
				$interviewerArray=array("interviewerId"=>$val,"interviewer"=>$interviewer[$key]);
				$itemsArr=array_merge($mainArr,$interviewerArray);
				$interviewDetailDao->add_d($itemsArr,true);
			}
			$mailDao = new model_common_mail();
			$title='��'.$obj['deptName'].'/'.$obj['userName'].'������ְ��̸';
			$mailContent='���ã�����'.$obj['deptName'].'/'.$obj['userName'].'������ְ��̸,����OA����д��ְ��̸��¼.OA·����������--->���˰칫--��������--������--->��ְ��̸';
			$mailDao->mailClear($title,$obj['interviewerId'],$mailContent,null);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
    }

    /**
	 * ��д�༭����
	 */
	function edit_d($obj){
		try{
			$this->start_d();
			//������������
			parent :: edit_d($obj, true);
			$parentId = $obj['id'];
			//����ģ��ֵ�ֶ�
			$interviewDetailDao = new model_hr_leave_interviewDetail();
			$mainArr=array("parentId"=>$obj['id']);
			$itemsArr=util_arrayUtil::setArrayFn($mainArr,$obj ['record']);

			$interviewDetailDao->saveDelBatch($itemsArr);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ��д��̸��¼
	 */
	function write_d($obj){
		try{
			$this->start_d();
			//������������
//			parent :: edit_d($obj, true);
			$parentId = $obj['id'];
			unset($obj['id']);
			//����ģ��ֵ�ֶ�
			$interviewDetailDao = new model_hr_leave_interviewDetail();
			$mainArr=array("parentId"=>$parentId,"interviewerId"=>$obj['interviewerId']);
			$itemsArr=util_arrayUtil::setArrayFn($mainArr,$obj);

			$interviewDetailDao->update($mainArr,$obj);

			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
	}
	/**
	 * ͨ����̸��id����̸id��ȡһ����̸��ϸ��¼
	 */
	 function getDetailByID($interviewerId,$parentId){
	 	$interviewDetailDao = new model_hr_leave_interviewDetail();
		return $interviewDetailDao->find(array("interviewerId"=>$interviewerId,"parentId"=>$parentId));
	 }
 }
?>