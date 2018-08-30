<?php
/**
 * @author Administrator
 * @Date 2013��7��11�� 20:30:47
 * @version 1.0
 * @description:��Ʊ���ܱ� Model��
 */
class model_flights_report_flreport extends model_base {

	function __construct() {
		$this->tbl_name = "oa_flights_report";
		$this->sql_map = "flights/report/flreportSql.php";
		parent :: __construct();
	}
	
	/**
	 * ����Ȩ�޺ϲ�
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