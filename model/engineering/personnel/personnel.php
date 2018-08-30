<?php
/*
 * Created on 2010-11-23
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 员工管理
 */
class model_engineering_personnel_personnel extends model_base{
	function __construct(){
		$this->tbl_name = "oa_esm_personal_baseinfo";
		$this->sql_map = "engineering/personnel/personnelSql.php";
		parent::__construct();
	}

	function showlist($rows){
		return '';
	}


	/**
	 * 根据主键获取对象
	 */
	function get_d($id) {
		//return $this->getObject($id);
		$condition = array ("id" => $id );
		$temp = $this->find ( $condition );
		//echo "<pre>";
		//print_r($temp);
		$temp["sexC"] = ( isset($temp["sex"])&&$temp["sex"]==1 ) ?"女":"男";
		return $temp;
	}

/*
 * 将user表中传过来的数据导入到员工信息表中
 */
	function insertUser_d($setTemp){
		//		print_r($userArr);
		$temp = 0;
//		$userTempArr =array();
		foreach( $setTemp as $key => $val ){
			$userTempArr[$temp] = implode("','",$setTemp[$temp]);
			$temp++;
		}
//		print_r($userTempArr);
		$userInfo = implode("'),('",$userTempArr);
//		print_r($setTemp);


		$insertInfo = "insert into " . $this->tbl_name . " (userName,userCode,sex) " . "values" . " ('" . $userInfo . "')";
//		echo $insertInfo;
		$query = mysql_query($insertInfo);
	}
	function getEmpByUserCode($userCode){
		$this->searchArr=array("userCode"=>$userCode);
		$userArr=$this->listBySqlId("select_simple");
		return $userArr[0];
	}

	/**
	 * 根据当前登录ID获取信息
	 */
	function resume_d(){
		return $this->find(array( 'userCode'=> $_SESSION['USER_ID'] ));
	}

	/**
	 * 批量插入处理
	 */
	function loadIn_d($object){

		$this->searchArr['userBatch'] = substr($object['userID'],0,-1);
		$this->sort = 'USER_ID';
		$rows = $this->listBySqlId('in_user');
		$str = 'insert into '. $this->tbl_name .' (userName,userCode,sex,createId,createName,createTime,updateTime,attendStatus,officeName,officeId,leaderName,leaderCode,userLevel ) values ';
		foreach($rows as $key => $val){
			$str .= "('" . $val['USER_NAME'] ."','" . $val['USER_ID'] . "','" . $val['SEX'] . "','" . $_SESSION['USER_ID'] ."','" . $_SESSION['USERNAME'] ."',now(),now(),'KQZT-ZZ','"
			. $object['officeName'] ."','" . $object['officeId'] ."','" . $object['leaderName'] ."','" . $object['leaderCode'] ."','" . $object['userLevel'] ."'),";
		}
		$str = substr($str,0,-1);

		return $this->query($str);
	}

	/**
	 * 根据人员帐号userCode修改人员信息
	 */
	function editByCode_d($object){
		$condition = array ("userCode" => $object ['userCode'] );
		return $this->update ( $condition, $object );
	}

}
?>
