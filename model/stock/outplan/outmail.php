<?php
/**
 * @author Administrator
 * @Date 2011年9月2日 11:34:47
 * @version 1.0
 * @description:发货计划邮寄接受人 Model层
 */
 class model_stock_outplan_outmail  extends model_base {

	function __construct() {
		$this->tbl_name = "oa_stock_outplan_mail";
		$this->sql_map = "stock/outplan/outmailSql.php";
		parent::__construct ();
	}

	/**
	 * 新增邮件接收人
	 */
	 function add_d($mailmanArr){
		$delSql = "delete from " . $this->tbl_name;
		$this->_db->query($delSql);
		if($this->createBatch($mailmanArr)){
			return true;
		}else{
			return false;
		}
	 }

	 function selectFun(){
	 	$sql = "select * from ".$this->tbl_name;
	 	$mailmanArr = $this->listBySql($sql);
	 	$nameArr = array();
	 	$idArr = array();
	 	foreach( $mailmanArr as $key=>$val ){
	 		$nameArr[$key]=$mailmanArr[$key]['mailmanName'];
	 		$idArr[$key]=$mailmanArr[$key]['mailmanId'];
	 	}
	 	$idStr = implode(',',$idArr);
	 	$$nameStr = implode(',',$nameArr);
	 	return array($idStr,$$nameStr);
	 }

 }
?>