<?php
class model_administration_appraisal_performance_config extends model_base {
	
	function __construct() {
		parent::__construct ();
		$this->tbl_name = 'appraisal_performance_config';
	}
	
	function getConfigProjectManager($page = null, $rows = null) {
		if ($page && $rows && ! $this->num) {
			$this->num = $this->GetCount ( " `key` = 'Project Manager' " );
		}
		if ($page && $rows && $this->num > 0) {
			$pagenum = $rows ? $rows : pagenum;
			$start = $page ? ($page == 1 ? 0 : ($page - 1) * $pagenum) : $this->start;
			$limit = $page && $rows ? $start . ',' . $pagenum : '';
		}
		$SQL = "
				SELECT 
				pc.id AS id,
				pc.key AS title,
				pc.value AS account,
				u.USER_NAME AS username
				FROM {$this->tbl_name} AS pc  
				left join USER AS u on  u.USER_ID = pc.value 
				WHERE pc.key = 'Project Manager'
				";
		$query = $this->query ( $SQL );
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$data [] = $rs;
		}
		
		return $data;
	}
	
	/*
	function getConfigOtherSetting($key){
		$SQL = "
				SELECT 
				pc.id AS id,
				pc.type AS type,
				pc.value AS value,
				FROM {$this->tbl_name} AS pc  
				WHERE pc.key = '{$key}'
		";
		$query = $this->query ( $SQL );
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$data [] = $rs;
		}
		
		return $data;
	}
	*/
	
	function getValueByKey($key){
		$str = '';
		$SQL = "
				SELECT 
				`value` 
				FROM {$this->tbl_name}
				WHERE `key` = '{$key}'
		";
		$result = $this->get_one ( $SQL );
		if(isset($result['value'])){
			$str = $result['value'];
		}
		return $str;
	}
	
	function getYearInDB(){
		return $this->getValueByKey('year');
	}
	
	function getSeasonInDB(){
		return $this->getValueByKey('season');
	}
	
	function createData($data) {
		$newId = $this->Add ( $data );
		return $newId;
	}
	
	function updateData($data, $id) {
		return $this->Edit ( $data, $id );
	}
	
	function setConfig($key, $value){
		$updateDate = date('Y-m-d H:i:s');
		$SQL = "UPDATE {$this->tbl_name} SET `value` = '{$value}', `updatedate` = '{$updateDate}' WHERE `key` = '{$key}'";
		return $this->_db->query($SQL);
	}
	
	function setYear($year){
		return $this->setConfig('year', $year);
	}
	
	function setSeason($season){
		return $this->setConfig('season', $season);
	}
	
	/*
	function getConfig(){
		$SQL = "SELECT * FROM {$this->tbl_name} WHERE `key` = 'season' AND `key` = 'year'";
		$query = $this->query ( $SQL );
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$data [] = $rs;
		}
		
		return $data;
	}
	*/
	function removeData($conditions) {
		return $this->delete ( $conditions );
	}
	
	function removeProjectManager($id){
		$conditions = " id = '{$id}'";
		return $this->removeData($conditions);
	}
	
	/**
	 * 获取所有用户名数组，KEY=USER_ID,VALUE=USER_NAME
	 */
	function get_username($key_type = 'user_id') {
		$query = $this->query ( "select user_id,user_name from user" );
		$data = array ();
		if ($key_type == 'user_id') {
			while ( ($rs = $this->fetch_array ( $query )) != false ) {
				$data [$rs ['user_id']] = $rs ['user_name'];
			}
		} else {
			while ( ($rs = $this->fetch_array ( $query )) != false ) {
				$data [$rs ['user_name']] = $rs ['user_id'];
			}
		}
		return $data;
	}
	
	/**
	 * 获取用户姓名返回用户ID数组
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $username_str
	 */
	function get_userid_str($data, $username_str) {
		if ($data && $username_str) {
			$arr = explode ( ',', $username_str );
			$userid = array ();
			if ($arr) {
				foreach ( $arr as $val ) {
					$val = trim ( $val );
					if ($val)
						$userid [$val] = $data [$val];
				}
			}
			return $userid;
		} else {
			return false;
		}
	}
		
	function isExist($conditions){
		return $this->GetCount ( $conditions );
	}
	
	function isExistBothKeyValue($value, $key){
		$conditions = " `key` = '{$key}' AND `value` = '{$value}' ";
		return $this->isExist($conditions);
	}
	
	function isExistForKey($key){
		$conditions = " `key` = '{$key}' ";
		return $this->isExist($conditions);
	}
	
	function checkProjectManagerIsExist($account) {
		return $this->isExistBothKeyValue($account, 'Project Manager');
	}
	
	function checkSeasonIsExist($value) {
		return $this->isExistForKey($value, 'season');
	}
	
	function checkYearIsExist($value) {
		return $this->isExistForKey($value, 'season');
	}
	
	function getYearList(){
		$thisYear = date('Y');
		$yearList = array();
		for($i = 0; $i < 10; $i++){
			$yearList[$thisYear] = $thisYear;
			$thisYear--;
		}
		return $yearList;
	}
	
	function getSeason(){
		$month = date('m');
		$seasonList = $this->seasonSet();
		$season = 1;
		foreach($seasonList as $sKey => $season){
			foreach ($season as $mKey => $sMonth){
				if($month == $sMonth){
					$season = $sKey;
				}
			}
		}
		return $season;
	}
	
	function getDataListByType($fields, $type){
		$SQL = "SELECT {$fields} FROM {$this->tbl_name} WHERE `key` = '{$type}'";
		$query = $this->query ( $SQL );
		$data = array ();
		while ( ($rs = $this->fetch_array ( $query )) != false ) {
			$data [] = $rs;
		}
		
		return $data;
	}
	
	function getProjectManagerList($needMarks){
		$data = array();
		$result = $this->getDataListByType('`value`','Project Manager');
		if(count($result) > 0){
			foreach ($result as $key => $value){
				if(isset($value['value'])){
					if($needMarks){
						$data[] = "'".$value['value']."'";
					}else{
						$data[] = $value['value'];
					}
				}
			}
		}
		return $data;
	}

	function seasonChange($season){
		$seasonChangeList = array(
			1 => '一',
			2 => '二',
			3 => '三',
			4 => '四'
		);
		return $seasonChangeList[$season];
		
	}
	
	private function seasonSet(){
		return array(
			1 => array(
				1 => 1,
				2 => 2,
				3 => 3
			),
			2 => array(
				4 => 4,
				5 => 5,
				6 => 6
			),
			3 => array(
				7 => 7,
				8 => 8,
				9 => 9
			),
			4 => array(
				10 => 10,
				11 => 11,
				12 => 12
			)
		);
	}
		
}