<?php
/**
 * @author Administrator
 * @Date 2012-08-24 14:38:04
 * @version 1.0
 * @description:新员工周报表 Model层
 */
 class model_hr_tutor_weekly  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_hr_tutor_weekly";
		$this->sql_map = "hr/tutor/weeklySql.php";
		parent::__construct ();
	}

	/**
	 * 获取员工辅导周报数据 处理加上是否准时批复isOnTime
	 */
	 function pageForRead($rows){

		foreach($rows as $key => $val ){
			if($val['signDate']!=null&&$val['signDate']!=''){
				if($this->get_weekend_days($val['submitDate'],$val['signDate'],true)>2){
					$rows[$key]['isOnTime']= 0 ;
				}else{
					$rows[$key]['isOnTime']= 1 ;
				}
			}else{
				if($this->get_weekend_days($val['submitDate'],date("Y-m-d"),true)>2){
					$rows[$key]['isOnTime']= 0 ;
				}else{
					$rows[$key]['isOnTime']= null ;
				}
			}
		}
		return $rows;
	 }
	/**
	 * 添加对象
	 */
	function add_d($object, $isAddInfo = false) {
	 	try{
			$this->start_d();
		//获取邮件内容
		if(isset($object['email'])){
			$emailArr = $object['email'];
			unset($object['email']);
		}

		$newId = parent::add_d( $object,true );
		if($object['state']==1&&isset($emailArr)){
			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
				$this->thisMail_d($emailArr,$object);
			}
		}
			$this->commit_d();
			return $newId;
		 }catch(Exception $e){
			$this->rollBack();
			return $newId;
		}
	}


	/**
	 * 根据主键修改对象
	 */
	function editWeekly_d($object, $isEditInfo) {
	 	try{
			$this->start_d();
			//获取邮件内容
			if(isset($object['email'])){
				$emailArr = $object['email'];
				unset($object['email']);
			}
			$id=parent::edit_d($object,true);
			if($object['state']==1&&isset($emailArr)){
				if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
					$this->thisMail_d($emailArr,$object);
				}
			}
			$this->commit_d();
			return $id;
		 }catch(Exception $e){
			$this->rollBack();
			return $id;
		}
	}


	/**
	 * 根据主键修改对象
	 */
	function edit_d($object, $isEditInfo) {
		//获取邮件内容
		if(isset($object['email'])){
			$emailArr = $object['email'];
			unset($object['email']);
		}

		if ($isEditInfo) {
			$object = $this->addUpdateInfo ( $object );
		}
		if(isset($emailArr)){
			if( $emailArr['issend'] == 'y'&&!empty($emailArr['TO_ID'])){
				$this->replyMail_d($emailArr,$object);
			}
		}

		return $this->updateById ( $object );
	}


	/**
	 * 邮件发送
	 */
	function thisMail_d($emailArr,$object,$thisAct = '新增'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = "学员 [". $object['studentName'] ."] 已向导师 [".$object['userName'] . "] 提交周报 ， 请回复。";

		$emailDao = new model_common_mail();
		$title = '请就'.$object['studentName'].'学员的周报回复指导意见或建议';
		$emailDao->mailClear($title,$emailArr['TO_ID'],$addMsg,$emailArr['ADDIDS']);
	}

	/**
	 * 邮件发送
	 */
	function replyMail_d($emailArr,$object,$thisAct = '新增'){
		$nameStr = $emailArr['TO_NAME'];
		$addMsg = "导师 [". $object['userName'] ."] 已批复学员 [".$object['studentName'] . "] 提交的周报 。";

		$emailDao = new model_common_mail();
		$emailDao->mailClear('批复周报',$emailArr['TO_ID'],$addMsg,$emailArr['ADDIDS']);
	}
	/**
	 * @param char|int $start_date 一个有效的日期格式，例如：20091016，2009-10-16
     * @param char|int $end_date 同上
     * $is_workday 为true时返回日期间的工作天数 false 时返回日期间的周末天数
	 */
	function get_weekend_days($start_date,$end_date,$is_workday = false){

			if (strtotime($start_date) > strtotime($end_date)) list($start_date, $end_date) = array($end_date, $start_date);
				$start_reduce = $end_add = 0;
				$start_N = date('N',strtotime($start_date));
				$start_reduce = ($start_N == 7) ? 1 : 0;
				$end_N = date('N',strtotime($end_date));
				in_array($end_N,array(6,7)) && $end_add = ($end_N == 7) ? 2 : 1;
				$alldays = abs(strtotime($end_date) - strtotime($start_date))/86400 + 1;
				$weekend_days = floor(($alldays + $start_N - 1 - $end_N) / 7) * 2 - $start_reduce + $end_add;
			if ($is_workday){
				$workday_days = $alldays - $weekend_days;
				return $workday_days;
				}
			return $weekend_days;
	}

 }
?>