<?php
/*
 * Created on 2010-9-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 日志工作汇总
 */
class model_rdproject_worklog_rdsummary extends model_base{
	function __construct() {
		$this->tbl_name = "oa_rd_worklog_summary";
		$this->sql_map = "rdproject/worklog/rdsummarySql.php";
		parent::__construct ();
		$this->week = array('周日','周一','周二','周三','周四','周五','周六');
	}

	//计算出给出的日期是星期几
	function GetWeekDay($date) {
		$dateArr = explode("-", $date);
	    return date("w", mktime(0,0,0,$dateArr[1],$dateArr[2],$dateArr[0]));
	}

	/***********************************页面显示**********************************/
	/**
	 * 工作情况总结，可编辑
	 */
	function showlistEdit($rows=null){
		$str = null;
		if($rows){
			$i = 0;
//			print_r($rows);
			foreach($rows as $key => $val ){
				$weekday = $this->week[$this->GetWeekDay($val['logDate'])];
				$i++;
				$str.=<<<EOT
				<tr>
					<td rowspan=2 width="15%">
						$weekday
						<input type="hidden" name="rdweeklog[rdsummary][$i][id]" value="$val[id]"/>
						<br>
						$val[logDate]
					</td>
					<td width="10%">
						工作情况
					</td>
					<td class="form_view_right">
						<textarea name="rdweeklog[rdsummary][$i][description]" class="txt_txtarea_font">$val[description]</textarea>
					</td>
				</tr>
				<tr>
					<td>
						存在问题
					</td>
					<td class="form_view_right">
						<textarea name="rdweeklog[rdsummary][$i][problem]" class="txt_txtarea_font">$val[problem]</textarea>
					</td>
				</tr>
EOT;
			}
		}
		return $str;
	}

	/**
	 * 工作情况总结-不可编辑
	 */
	function showlistView($rows=null){
		$str = null;
		if($rows){
			$i = 0;
//			print_r($rows);
			foreach($rows as $key => $val ){
				$weekday = $this->week[$this->GetWeekDay($val['logDate'])];
				$i++;
				$description = nl2br($val['description']);
				$problem = nl2br($val['problem']);
				$str.=<<<EOT
				<tr>
					<td rowspan=2 width="15%">
						$weekday
						<input type="hidden" name="rdweeklog[rdsummary][$i][id]" value="$val[id]"/>
						<br>
						$val[logDate]
					</td>
					<td width="10%">
						工作情况
					</td>
					<td class="form_view_right">
						$description
					</td>
				</tr>
				<tr>
					<td>
						存在问题
					</td>
					<td class="form_view_right">
						$problem
					</td>
				</tr>
EOT;
			}
		}
		return $str;
	}

	/*******************************************业务操作******************************/

	/**
	 * 添加一个星期的日志汇总
	 */
	function addWeekSummary($weeklogId,$thisWeek){
		$sql = "insert into " .$this->tbl_name . "( weekId , logDate , createId , createName , createTime ) values  " ;
		foreach($thisWeek as $key => $val){
			$sql .= "( '$weeklogId' , '".$val ."','". $_SESSION['USER_ID'] ."','". $_SESSION['USERNAME'] ."','". date('Y-m-d h:i:s') ."' ),";
		}
		$sql = substr($sql,0,-1);
		try{
			$this->query($sql);
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 获取时间段内日志汇总
	 */
	function getThisSummary($monday,$sunday){
		$this->searchArr['user_id'] = $_SESSION['USER_ID'];
		$this->searchArr['startDate'] = $monday;
		$this->searchArr['endDate'] = $sunday;
		$this->asc = false;
		$rows = $this->listBySqlId('getSummary');
		return $rows;
	}

	/**
	 * 根据周志ID获取对应的日志汇总
	 */
	function getSummaryById($weekId){
		$this->searchArr['weekId'] = $weekId;
		$this->asc = false;
		return $this->listBySqlId('getSummary');
	}

	/**
	 * 根据数组修改数据
	 */
	function editForRows($rows){
		try{
			foreach( $rows as $val ){
				parent::edit_d($val);

			}
		}catch(exception $e){
			throw $e;
		}
	}

	/**
	 * 自动写入工作情况和存在问题
	 */
	function appendThis_d($thisDate,$worklog){
		if(!empty($worklog['description']) || !empty($worklog['problem'])){
			$thisTime = date('Y-m-d H:i:s');
			$rs = $this->find(array('createId' => $_SESSION['USER_ID'],'logDate' => $thisDate),null,'description,problem');
			if(!empty($worklog['description'])){
				$thisObj['description'] = $rs['description'] .'['. $worklog['taskName'] . '] '.$thisTime. "\n ". $worklog['description'] ."\n \n";
			}

			if(!empty($worklog['problem'])){
				$thisObj['problem'] = $rs['problem'] .'['. $worklog['taskName'] . '] '.$thisTime. "\n ". $worklog['problem'] ."\n \n";
			}
			$this->update(array('createId' => $_SESSION['USER_ID'],'logDate' => $thisDate),$thisObj );
		}
	}
}
?>
