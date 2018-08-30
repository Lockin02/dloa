<?php
/**
 * @author Administrator
 * @Date 2012-08-07 16:06:30
 * @version 1.0
 * @description:离职--面谈记录表 Model层
 */
 class model_hr_leave_interview  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_leave_interview";
		$this->sql_map = "hr/leave/interviewSql.php";
		parent::__construct ();
	}

	 /**
    * 重写add方法
    */
    function add_d($obj){
		try{
			$this->start_d();
			//处理主表数据
			$interviewId=parent::add_d($obj,true);
			//处理面谈记录表详细
			$interviewDetailDao = new model_hr_leave_interviewDetail();


			$mainArr=array("parentId"=>$interviewId,"leaveId"=>$obj['leaveId']);
			$interviewer=explode(",",$obj['interviewer']);
			$interviewerId=explode(",",$obj['interviewerId']);

			//循环面谈者，分别插入从表
			foreach($interviewerId as $key =>$val){
				$itemsArr=null;$interviewerArray = null;
				$interviewerArray=array("interviewerId"=>$val,"interviewer"=>$interviewer[$key]);
				$itemsArr=array_merge($mainArr,$interviewerArray);
				$interviewDetailDao->add_d($itemsArr,true);
			}
			$mailDao = new model_common_mail();
			$title='对'.$obj['deptName'].'/'.$obj['userName'].'进行离职面谈';
			$mailContent='您好！请与'.$obj['deptName'].'/'.$obj['userName'].'进行离职面谈,并在OA上填写离职面谈记录.OA路径：导航栏--->个人办公--工作任务--人事类--->离职面谈';
			$mailDao->mailClear($title,$obj['interviewerId'],$mailContent,null);
			$this->commit_d();
			return true;
		}catch(Exception $e){
			$this->rollBack();
			return false;
		}
    }

    /**
	 * 重写编辑方法
	 */
	function edit_d($obj){
		try{
			$this->start_d();
			//处理主表数据
			parent :: edit_d($obj, true);
			$parentId = $obj['id'];
			//处理模板值字段
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
	 * 填写面谈记录
	 */
	function write_d($obj){
		try{
			$this->start_d();
			//处理主表数据
//			parent :: edit_d($obj, true);
			$parentId = $obj['id'];
			unset($obj['id']);
			//处理模板值字段
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
	 * 通过面谈者id和面谈id获取一条面谈详细记录
	 */
	 function getDetailByID($interviewerId,$parentId){
	 	$interviewDetailDao = new model_hr_leave_interviewDetail();
		return $interviewDetailDao->find(array("interviewerId"=>$interviewerId,"parentId"=>$parentId));
	 }
 }
?>