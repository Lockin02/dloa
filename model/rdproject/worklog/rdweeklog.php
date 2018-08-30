<?php
/*
 * Created on 2010-9-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class model_rdproject_worklog_rdweeklog extends model_base{
	function __construct() {
		$this->tbl_name = "oa_rd_worklog_week";
		$this->sql_map = "rdproject/worklog/rdweeklogSql.php";
		parent::__construct ();
	}

	/***********************************列表显示*****************************/
	/**
	 * 所有日志
	 */
	function showlist($rows){
		$personfocusDao=new model_rdproject_personfocus_personfocus();
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				if($personfocusDao->isFocused($val['createId'],$_SESSION['USER_ID'])){
					$htmstr='已关注|';
				}else{
					$htmstr='<a href="?model=rdproject_personfocus_personfocus&action=makeSure&id='.$val[id].'&user_id='.$val[createId].'&user_name='.$val[createName].'&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300" class="thickbox" title="添加关注">添加关注</a> |';

				}
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" title="$val[id]">
						<td>
							$i
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=logList&weekId=$val[id]">$val[weekTitle]</a>
						</td>
						<td>
							$val[depName]
						</td>
						<td>
							$val[updateTime]
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdweeklog&action=view&id=$val[id]">打开</a> |
							$htmstr
							<a href="?model=rdproject_worklog_rdweeklog&action=addremark&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="添加备注">添加备注</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">暂无相关日志</td></tr>';
		}
		return $str;
	}

	/**
	 * 我的工作日志
	 */
	function showmylist($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" title="$val[id]">
						<td>
							$i
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=myLogList&weekId=$val[id]">$val[weekTitle]</a>
						</td>
						<td>
							$val[depName]
						</td>
						<td>
							$val[updateTime]
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdweeklog&action=read&id=$val[id]">打开</a> |
							<a href="?model=rdproject_worklog_rdweeklog&action=addremark&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox">添加备注</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">暂无相关日志</td></tr>';
		}
		return $str;
	}

	/**
	 * 查询日志列表
	 */
	function showResultList($rows ,$object){
		if($rows){
			$i = 0;
			$str = null;
			$insertstr = null;
//			print_r($object);
			if(isset($object['w_projectId'])){
				$insertstr = "&projectId=" . $object['w_projectId'];
			}
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$i++;
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$val[createName]
						</td>
						<td>
							$val[depName]
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=logList&weekId=$val[id]$insertstr">$val[weekTitle]</a>
						</td>
						<td>
							$val[updateTime]
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">暂无相关日志</td></tr>';
		}
		return $str;
	}

	/*************************************业务方法***********************************/

	/**
	 * 重写获取数据方法
	 */
	function get_d($id,$type = null) {
		$condition = array ("id" => $id );
		$rows = $this->find ( $condition );
		//获取行政职位
		$user = new includes_class_global();
		$rows['position'] = $user->GetUserinfo($rows['createId'],'jobs_name');

		//工作任务记录
		$worklog = new model_rdproject_worklog_rdworklog();
		$rows['taskInDate'] = $worklog->getSchedule(array('beginDate' => $rows['weekBeginDate'],'overDate'=>$rows['weekEndDate'],'user_id' => $rows['createId']),'nopages');

//		print_r($rowsWorklog);
		//获取任务
		$task = new model_rdproject_task_rdtask();
		$rows['completedTask'] = $task->showEndTask($task->getTkBetweenDate($rows['weekBeginDate'],$rows['weekEndDate'],$rows['createId']));

		//获取情况总结
		$summary = new model_rdproject_worklog_rdsummary();
		if(empty($type)){
			$rows['rdsummary'] = $summary->showlistEdit($summary->getSummaryById($id));
		}else{
			$rows['rdsummary'] = $summary->showlistView($summary->getSummaryById($id));
		}

		return $rows;
	}

	/**
	 * 只获取本表的内容
	 */
	function getBase($id){
		return parent::get_d($id);
	}

	/**
	 * 功能：查找本周是否有周志，
	 * 如果有，则返回周志Id
	 * 如果没有，新建一条周志，然后返回周志Id
	 */
	function checkIsSet(){
		$this->searchArr['user_id'] = $_SESSION['USER_ID'];
		$this->searchArr['startDate'] = $this->searchArr['endDate'] = day_date;
		$weeklogId = $this->listBySqlId('checkIsSet');
//		print_r($weeklogId[0]);
		if(empty($weeklogId)){
			$weeklogId = $this->addWeeklog();
		}else{
			$object['id'] = $weeklogId[0]['id'];
			$object = $this->addUpdateInfo($object);
			$this->updateById($object);
			$weeklogId = $object['id'];
		}
		return $weeklogId;
	}

	/**
	 * 功能：添加一个周报
	 */
	function addWeeklog(){
		$otherdatasDao = new model_common_otherdatas();
		$summaryId = new model_rdproject_worklog_rdsummary();
		$thisWeek  = $this->getThatDate();
//		print_r($thisWeek);
		$object['weekTitle'] = $_SESSION['USERNAME'] .'工作日志( '.$thisWeek['0'] . ' ~ ' .$thisWeek['6'] .')';
		$object['weekBeginDate'] = $thisWeek['0'];
		$object['weekEndDate'] = $thisWeek['6'];
		$object['depId'] = $_SESSION['DEPT_ID'];
		$object['depName'] = $otherdatasDao->getUserDatas($_SESSION['USER_ID'],'DEPT_NAME');
		$object = $this->addCreateInfo($object);
		//添加周志
		try{
			$weeklogId =  $this->add_d($object);
			//检查当天日志汇总是否存在
			$summaryId->addWeekSummary($weeklogId,$thisWeek);
			return $weeklogId;
		}catch(exception $e){
			throw $e;
		}
	}

	function getThatDate(){
		$now = time(); //当前时间戳
		$w = date('w',$now); //当前星期几
		$thisWeek = array();
		switch ($w) {
			case '0':$toModay = '6';$toSunday = '0';break;
			case '1':$toModay = '0';$toSunday = '6';break;
			case '2':$toModay = '1';$toSunday = '5';break;
			case '3':$toModay = '2';$toSunday = '4';break;
			case '4':$toModay = '3';$toSunday = '3';break;
			case '5':$toModay = '4';$toSunday = '2';break;
			case '6':$toModay = '5';$toSunday = '1';break;

			default:
				break;
		}
		$begin = $now - $toModay*24*60*60 ; //当前时间减去一天
		$end = $now + $toSunday*24*60*60 ; //当前时间减去一天

		$beginDate = date('w',$begin);
		$endDate = date('w',$end);

		if($endDate > $beginDate){
			$countDate = $endDate - $beginDate;
		}else{
			$countDate = 6;
		}
		for($i = 0 ;$i <= $countDate ;$i++ ){
			$t = $begin + $i*24*60*60 ;
			$tw = date('w',$t);//这一天星期几
			$thisWeek[$i] =  date('Y-m-d',$t);
		}
		return $thisWeek;
	}

	/**
	 * 保存周志修改
	 */
	function editLog($object){
		$object = $this->addUpdateInfo($object);
//		print_r($object);
		$summary = new model_rdproject_worklog_rdsummary();
		try{
			$this->start_d();
			//修改工作汇总
			$summary->editForRows($object['rdsummary']);
			unset($object['rdsummary']);
			//修改周志
//			parent::edit_d($object);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}

	}

	/**
	 * getSearchList获取查询到的结果
	 */
	 function getSearchList(){
	 	//选取第一条SQL
	 	$sql = $this->sql_arr['search_list'];

		//部门选择
	 	if(isset($this->searchArr['departmentIds'])){
			$sql .= " and c.depId in ( ". $this->searchArr['departmentIds']  ." )";
	 	}

		//人员选择
	 	if(isset($this->searchArr['personIds'])){
			$sql .= " and c.createId in ( ". $this->strManage($this->searchArr['personIds'])  ." )";
	 	}

		//项目选择
	 	if(isset($this->searchArr['w_projectId'])){
			$sql .= " and w.projectId = ' ".$this->searchArr['w_projectId'] ." '";
		}

		//起始时间
		if(isset($this->searchArr['beginDate'])){
			$sql .= " and (c.weekEndDate > '" .$this->searchArr['beginDate'] . "'  or c.weekEndDate = '" .$this->searchArr['beginDate'] . "' ) ";
		}

		//终止时间
		if(isset($this->searchArr['overDate'])){
			$sql .= " and (c.weekBeginDate < '" .$this->searchArr['overDate'] . "' or c.weekBeginDate = '" .$this->searchArr['overDate'] . "'  ) ";
		}

//	 	print_r($this->searchArr);
	 	//构建group by
	 	$groupBy = "c.id";
//		$groupBy = $this->groupBy;
		if (isset ( $groupBy ) && $groupBy != "" && $groupBy != "id") {
			$groupBy = " group By $groupBy ";
			$countsql = "select 0 " . substr ( $sql, strpos ( $sql, "from" ) );
			$countsql = "select count(0) as num from ( " . $countsql . " " . $groupBy . " ) as t";
		} else {
			//构造获取记录数sql
			$countsql = "select count(0) as num " . substr ( $sql, strpos ( $sql, "from" ) );
		}
		//print($countsql);
		$this->count = $this->queryCount ( $countsql );
		//拼装搜索条件
//		$sql = $this->createQuery ( $sql, $this->searchArr );
		//print($sql);
		//构建排序信息
		$asc = $this->asc ? "DESC" : "ASC";
		$sql .= " $groupBy order by " . $this->sort . " " . $asc;
		//构建获取记录数
		$sql .= " limit " . $this->start . "," . $this->pageSize;
//								echo($sql);
		return $this->_db->getArray ( $sql );

	 }

	/**
	 * in  字符串处理 为每个字符串添加单引号
	 */
	function strManage($str){
		$arr = explode(',',$str);
		$restr = null;
		foreach($arr as $val){
			if(empty($restr)){
				$restr = "'".$val."'";
			}else{
				$restr = ",'".$val."'";
			}
		}
		return $restr;
	}

	/**
	 * 获取下属日志
	 */
	function getSubordinateLog(){
		$this->groupBy = 'w.id';
		return $this->pageBySqlId ('subordinateLog');
	}
}
?>
