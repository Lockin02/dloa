<?php
/**
 * @author Administrator
 * @Date 2013年7月11日 20:30:47
 * @version 1.0
 * @description:订票汇总表 Model层
 */
class model_flights_report_flreport extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_report";
		$this->sql_map = "flights/report/flreportSql.php";
		parent :: __construct();
	}
	
	/**
	 * 部门权限合并
	 */
	function regionMerge($privlimit,$deptId){
		$str = null;
		if(!empty($privlimit)||!empty($deptId)){
			if($privlimit){
				$str .= $privlimit;
			}
			if(!empty($str) && !empty($deptId)){
				$str .= ','.$deptId;
			}else{
				$str .= $deptId;
			}
			return $str;
		}else{
			return null;
		}
	}
}
?>