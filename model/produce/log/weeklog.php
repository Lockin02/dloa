<?php
/**
 * @author Administrator
 * @Date 2012年5月16日 星期三 14:23:03
 * @version 1.0
 * @description:工作周报 Model层 
 */
class model_produce_log_weeklog extends model_base {
	
	function __construct() {
		$this->tbl_name = "oa_produce_weeklog";
		$this->sql_map = "produce/log/weeklogSql.php";
		parent::__construct ();
	}
	
	/**
	 * 判断该周日志是否存在
	 */
	 function isExit($id){
	 	if($get = $this->find(array('createId'=>$id),'weekBeginDate DESC','')){
	 		$time_span = strtotime(date("Y-m-d")) - strtotime($get['weekBeginDate']);
			//echo $time_span;
			if ($time_span <= 60 * 60 * 24 * 7)
				return $get ['id'];
			else
				return FALSE;
		} else
			return FALSE;
	
	}
	
	/* 
	  * 生成周日志
	  */
	function createweek() {
		$begin = date ( "Y-m-d" );
		$time = explode ( '-', $begin );
		$year = $time [0];
		$month = $time [1];
		$day = $time [2];
		$date_s = mktime ( 0, 0, 0, $month, $day, $year );
		$day_s = 3600 * 24 * 7; //一天 3600×24秒
		$end = date ( "Y-m-d", $date_s + $day_s );
		
		$name = $_SESSION ['USERNAME'] . '的工作日志';
		$id = $this->add_d ( array_merge ( array ("weekBeginDate" => $begin, 'weekEndDate' => $end, 'weekTitle' => $name, 'createId' => $_SESSION ['USER_ID'], 'depName' => $_SESSION ['DEPT_NAME'], 'depId' => $_SESSION ['DEPT_ID'] ), $_POST ['worklog'] ), true );
		return $id;
	}
}
?>