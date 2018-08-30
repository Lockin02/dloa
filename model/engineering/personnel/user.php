<?php
/*
 * Created on 2010-11-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 员工管理
 */
class model_engineering_personnel_user extends model_base{
	function __construct(){
		$this->tbl_name = "user";
		$this->sql_map = "engineering/personnel/userSql.php";
		parent::__construct();
	}

	function showlist($rows){
		return '';
	}



	/**
	 * 获取对象分页列表数组
	 */
	function page_d($sqlId = '') {
		//$this->echoSelect();
		if (! isset ( $this->sql_arr )) {
			return $this->pageBySql ( "select * from " . $this->tbl_name ." c" );
		} else {
			//var_dump($this->pageBySqlId ());
			return $this->pageBySqlId ( $sqlId );
		}

	}
/*
 * 从用户表中取出数据
 */
	function selectUser_d( $idArr ){
		$i = 0;
		if( isset( $idArr )) {
			foreach( $idArr as $key => $val){
				$write = "select * from " . $this->tbl_name . " where USER_ID = " . "'" . $val . "'";
				$query = mysql_query($write);
				while($row=mysql_fetch_array($query)){
					echo "<pre>";
					print_r($row) ;
					$userArr[$i] = array(
						"USER_NAME" => $row['USER_NAME'],
						"userCode" => $row['userCode'],
						"SEX" => $row['SEX'],
					);
				}
				$i++;
			}
		}
		$insert = new model_engineering_personnel_personnel();
		$insert->insertUser_d($userArr);

	}

}
?>
