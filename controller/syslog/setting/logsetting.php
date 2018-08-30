<?php
/**
 * @author huangzf
 * @Date 2011年11月1日 11:20:04
 * @version 1.0
 * @description:系统日志设置控制层 
 */
class controller_syslog_setting_logsetting extends controller_base_action {
	
	function __construct() {
		$this->objName = "logsetting";
		$this->objPath = "syslog_setting";
		parent::__construct ();
	}
	
	/**
	 * 跳转到系统日志设置
	 */
	function c_page() {
		$this->view ( 'list' );
	}
	
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$mySql = new mysql ();
		
		$result = $mySql->list_tables ();
		while ( $row = mysql_fetch_row ( $result ) ) {
			//			print_r($row);
			$tableArr [] = $row [0];
		}
		//		echo   " <PRE> "; 
		//		print_r ( $tableArr );
		$opStr = "";
		foreach ( $tableArr as $key => $value ) {
			$opStr .= "<option value='$value'>$value</option>";
		}
		
		$this->assign ( "tableSelOption", $opStr );
		$this->view ( 'add' );
	}
	/**
	 * 跳转到修改页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtEdit ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'edit' );
	}
	/**
	 * 跳转到查看页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign ( "itemsList", $this->service->showItemAtView ( $obj ['items'] ) );
		$this->assign ( "itemscount", count ( $obj ['items'] ) );
		$this->view ( 'view' );
	}
	
	/**
	 * 
	 * 获取对应表名的列选项
	 */
	function c_findTableColumn() {
		//include ('config.php');
		//include (WEB_TOR . 'includes/Mysql.class.php');
		$tableName = $_POST ['tableName'] ? $_POST ['tableName'] : null;
		$result = $this->service->query ( "SHOW   COLUMNS   FROM  ".$tableName );
		
		while ( $row = mysql_fetch_array ( $result, MYSQL_ASSOC ) ) {
			$columnArr [] = $row;
		}
		
		foreach ( $columnArr as $key => $value ) {
			$valueArr [$key] = $value ['Field'];
		}
		
		//		echo   " </PRE> ";
		echo util_jsonUtil::encode ( $valueArr );
	}

	/**
	 * 
	 * 检查表名是否重复
	 */
	function c_checkTableRepeat(){
		$tableName = isset ( $_GET ['tableName'] ) ? $_GET ['tableName'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("tableName" => $tableName );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}		
	}
}
?>