<?php

/**
 * @author chengl
 * @Date 2013-4-16 13:39:28
 * @version 1.0
 * @description: 终端关联信息 model层
 */
class model_product_terminal_terminalfunction extends model_base {

	function __construct() {
		$this->tbl_name = "oa_terminal_terminalfunction";
		$this->sql_map = "product/terminal/terminalfunctionSql.php";
		parent::__construct ();
	}

	/**
	 * 保存终端关联
	 */
	function save($terminalfunction){
		if (util_jsonUtil::is_utf8 ( $terminalfunction['functionTip'] )) {
			$terminalfunction['functionTip'] = util_jsonUtil::iconvUTF2GB ( $terminalfunction['functionTip'] );
		}
		$terminalfunction['editName'] = $_SESSION['USERNAME'];
		$terminalfunction['editNameId'] = $_SESSION['USER_ID'];
		$terminalfunction['editDT'] = date("Y-m-d");
		//print_r($terminalfunction);
		if(empty($terminalfunction['id'])){
			$this->add_d($terminalfunction);
		}else{
			$this->edit_d($terminalfunction);
		}
	}

	/**
	 * 版本保存
	 */
	function saveVeersion_d(){

		$editName = $_SESSION['USERNAME'];
		$editNameid = $_SESSION['USER_ID'];
		$editDT =  date("Y-m-d");
		$version = $this->createVersion();
		$sql = "
      		Insert into oa_terminal_terminalfunction_version (
			   productId,terminalId,functionId,functionTip,editName,editNameId,editDT,version
			)
			select
			   productId,terminalId,functionId,functionTip,'".$editName."' as editName,'".$editNameid."' as editNameId,'".$editDT."' as editDT,'".$version."' as version
			from oa_terminal_terminalfunction
	        ";
        $this->_db->query($sql);
	}
	//版本号生成
	function createVersion(){
        $billCode = "V".date("Ymd");
//        $billCode = "V201208";
		$sql="select max(RIGHT(c.version,2)) as maxCode,left(c.version,9) as _maxbillCode " .
				"from oa_terminal_terminalfunction_version c group by _maxbillCode having _maxbillCode='".$billCode."'";

		$resArr=$this->findSql($sql);
		$res=$resArr[0];
		if(is_array($res)){
			$maxCode=$res['maxCode'];
			$maxBillCode=$res['maxbillCode'];
			$newNum=$maxCode+1;
			switch(strlen($newNum)){
				case 1:$codeNum="0".$newNum;break;
				case 2:$codeNum=$newNum;break;
			}
			$billCode.=$codeNum;
		}else{
			$billCode.="01";
		}

		return $billCode;
	}
	//版本数据
	function versionJson(){
		$sql = " select version from oa_terminal_terminalfunction_version group by version";
		$resArr=$this->findSql($sql);
		return $resArr;
	}

	/**
	 * 历史数据
	 */
	 function listJsonVersion_d($productId,$version){
	 	$sql = "select id,productId,terminalId,functionId,functionTip " .
	 			"from oa_terminal_terminalfunction_version where version='".$version."' and productId = '".$productId."' order by id  desc";
	 	$arr = $this->_db->getArray($sql);
	 	return $arr;
	 }
}
?>
