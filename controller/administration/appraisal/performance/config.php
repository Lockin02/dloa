<?php
class controller_administration_appraisal_performance_config extends model_administration_appraisal_performance_config {
	function __construct() {
		parent::__construct ();
		$this->show = new show ();
		$this->show->path = 'administration/appraisal/performance/';
	}
	
	/**
	 * 默认访问
	 */
	function c_index() {
		return $this->c_list ();
	}
	
	/**
	 * 列表
	 */
	function c_list() {
		
		$year = $this->getYearInDB();
		
		$yearList = $this->getYearList();
		$str = "<option value='NaN'>请选择年份</option>";
		$thisYear = date('Y');
		if(count($yearList) > 0 ){
			foreach($yearList as $key => $value){
				if($year == ''? trim($thisYear) : $year == trim($key)){
					$str .= "<option value='{$key}' selected >{$value}</option>";
				}else{
					$str .= "<option value='{$key}'>{$value}</option>";
				}
				
			}
		}
		if($year == ""){
			$year = "年份未设置";
		}
		
		$season = $this->getSeasonInDB();
		
		$thisSeason = $this->getSeason();
		$seasonStr = "<option value='NaN' >请选择季度</option>";
		for ($i = 1; $i <= 4; $i++){
			if ($season == "" ? $thisSeason : $season == $i){
				$seasonStr .= "<option value='{$i}' selected >第{$this->seasonChange($i)}季度</option>";
			}else{
				$seasonStr .= "<option value='{$i}' >第{$this->seasonChange($i)}季度</option>";
			}
		}
		if($season == ""){
			$season = "季度未设置";
		}
		
		
		
		$this->show->assign('year_in_db', $year);
		$this->show->assign('season_in_db', $season);
		$this->show->assign('yearList', $str);
		$this->show->assign('seasonStr', $seasonStr);
		$this->show->display ( 'config' );
		
	}
	
	function c_list_data() {
		$data = $this->getConfigProjectManager ( $_POST ['page'], $_POST ['rows'] );
		
		$json = array ('total' => $this->num );
		if ($data) {
			$json ['rows'] = un_iconv ( $data );
		} else {
			$json ['rows'] = array ();
		}
		echo json_encode ( $json );
	}
	
	function c_save() {
		
		$flag = 0;
		$_POST = mb_iconv ( $_POST );
		if (isset ( $_POST ['username'] ) and ! empty ( $_POST ['username'] )) {
			
			$user_data = $this->get_username ( 'user_name' );
			
			$data ['value'] = $user_data [$_POST ['username']];
			$data ['updatedate'] = date ( 'Y-m-d H:i:s' );
			$isExist = $this->checkProjectManagerIsExist($data ['value']);
			if (!$isExist) {
				if (isset ( $_POST ['id'] ) and ! empty ( $_POST ['id'] )) {
					if ($this->updateData ( $data, $_POST ['id'] )) {
						$flag = 1;
					}
				} else {
					$data ['key'] = 'Project Manager';
					$data ['createdate'] = date ( 'Y-m-d H:i:s' );
					if ($this->createData ( $data )) {
						$flag = 1;
					}
				}
			} else {
					$flag = 2;
				}
		}
		echo $flag;
	}
	
	function c_del() {
		$id = $_GET ['id'] ? $_GET ['id'] : $_POST ['id'];
		if ($id) {
			if ($this->removeProjectManager( $id)) {
				echo 1;
			} else {
				echo - 1;
			}
		} else {
			echo - 1;
		}
	}
	
	function c_set_config(){
		
		$flag = 0;
		if(isset($_POST['year']) && isset($_POST['season'])){
			$isSetSeason = $this->checkSeasonIsExist('season');
			$isSetYear = $this->checkYearIsExist('year');
			
			if(!$isSetYear){
				$setYearData['key'] = 'year';
				$setYearData['value'] = $_POST['year'];
				$setYearData ['createdate'] = date ( 'Y-m-d H:i:s' );
				$setYearData ['updatedate'] = date ( 'Y-m-d H:i:s' );
				if($this->createData($setYearData)){
					$flag = 1;
				}
			}else{
				if($this->setYear($_POST['year'])){
					$flag = 1;
				}
				
			}
			
			if(!$isSetSeason){
				$setYearData['key'] = 'season';
				$setYearData['value'] = $_POST['season'];
				$setYearData ['createdate'] = date ( 'Y-m-d H:i:s' );
				$setYearData ['updatedate'] = date ( 'Y-m-d H:i:s' );
				if($this->createData($setYearData)){
					$flag = 1;
				}
			}else{
				if($this->setSeason($_POST['season'])){
					$flag = 1;
				}
			}
		}
		echo $flag;
	}
}