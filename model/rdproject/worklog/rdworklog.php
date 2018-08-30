<?php
/*
 * Created on 2010-9-14
 * 工作日志
 */
class model_rdproject_worklog_rdworklog extends model_base{
	function __construct() {
		$this->tbl_name = "oa_rd_worklog";
		$this->sql_map = "rdproject/worklog/rdworklogSql.php";
		parent::__construct ();
	}

	private $dateForInfo;

	private $allworklog = array('all' => 0 ,'project' => 0,'noproject' => 0);//所有工作量，项目工作量，非项目工作量统计

	private $projectWorklog ;//项目工作量统计

	private $taskCount ;

	private $taskIds ;

	/***********************************列表显示*****************************/
	/**
	 * 默认显示日志
	 */
	function showlist($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" title="$val[id]">
						<td>
							$i<input type="hidden" id="worklog$val[id]" value="$val[status]" />
						</td>
						<td>
							$val[projectName]
						</td>
						<td>
							$val[taskName]
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[effortRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[warpRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[workloadDay]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[workloadSurplus]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[planEndDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[executionDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[updateTime]</a>
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
	 *  嵌套弹出中显示列表
	 */
	function showlistInWindow($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$i++;
				$str.=<<<EOT
					<tr class="$classCss" title="$val[id]">
						<td>
							$i<input type="hidden" id="worklog$val[id]" value="$val[status]" />
						</td>
						<td>
							$val[projectName]
						</td>
						<td>
							$val[taskName]
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[effortRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[warpRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[workloadDay]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[workloadSurplus]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[planEndDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[executionDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="查看日志详细">$val[updateTime]</a>
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
	 * 工作任务记录列表显示
	 */
	function showTaskLog($rows){
		if($rows){
			$datadictDao = new model_system_datadict_datadict ();
			$i = 0;
			$str = null;
			foreach($rows as $key => $val){
				$i++;
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
//				$status = $this->getDatadicts($val['status']);
				if(isset($this->dateForInfo[$val['id'].'^1'])){
					$mon = $this->dateForInfo[$val['id'].'^1'];
				}else{
					$mon = 0;
				}
				if(isset($this->dateForInfo[$val['id'].'^2'])){
					$tus = $this->dateForInfo[$val['id'].'^2'];
				}else{
					$tus = 0;
				}
				if(isset($this->dateForInfo[$val['id'].'^3'])){
					$wen = $this->dateForInfo[$val['id'].'^3'];
				}else{
					$wen = 0;
				}
				if(isset($this->dateForInfo[$val['id'].'^4'])){
					$thur = $this->dateForInfo[$val['id'].'^4'];
				}else{
					$thur = 0;
				}
				if(isset($this->dateForInfo[$val['id'].'^5'])){
					$fri = $this->dateForInfo[$val['id'].'^5'];
				}else{
					$fri = 0;
				}
				if(isset($this->dateForInfo[$val['id'].'^6'])){
					$sat = $this->dateForInfo[$val['id'].'^6'];
				}else{
					$sat = 0;
				}
				if(isset($this->dateForInfo[$val['id'].'^0'])){
					$sun = $this->dateForInfo[$val['id'].'^0'];
				}else{
					$sun = 0;
				}
				$all = $mon + $tus + $wen + $thur + $fri + $sat + $sun;

				if($val['warpRate']>0){
					$imgSrc = '<img src="images/icon/red.gif" title="偏差率：'.$val['warpRate'].'%">';
				}else{
					$imgSrc = '<img src="images/icon/green.gif" title="0%">';
				}
				$tkStatus = $datadictDao->getDataNameByCode ( $val ['status'] );
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$imgSrc
						</td>
						<td>
							<a href="?model=rdproject_task_rdtask&action=toReadTask&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800" title="查看< $val[name] >" class="thickbox">$val[name]</a>
						</td>
						<td>
							$val[projectName]
						</td>
						<td>
							$tkStatus
						</td>
						<td>
							$val[effortRate] %
						</td>
						<td>
							$val[appraiseWorkload] 小时
						</td>
						<td>
							$val[putWorkload] 小时
						</td>
						<td>
							$mon
						</td>
						<td>
							$tus
						</td>
						<td>
							$wen
						</td>
						<td>
							$thur
						</td>
						<td>
							$fri
						</td>
						<td>
							$sat
						</td>
						<td>
							$sun
						</td>
						<td>
							$all
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
	 * 在任务中显示日志列表
	 */
	function showLogInTask($rows){
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				$i++;
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="查看日志详细">$val[createName]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="查看日志详细">$val[effortRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="查看日志详细">$val[workloadDay] 小时</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="查看日志详细">$val[executionDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="查看日志详细">$val[description]</a>
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
	 * 人员投入工作量透视图表格渲染
	 */
	function worklogTable($rows,$all){
		$str =<<<EOT
			<table class="main_table">
				<tr class="main_tr_header">
					<th>
						序号
					</th>
					<th>
						名称
					</th>
					<th>
						投入工作量
					</th>
					<th>
						比例
					</th>
				</tr>
EOT;
		if($rows){
			$i = 0;
			foreach($rows as $key=> $val){
				$i++;
				$classCss = (($i%2) == 0)?'tr_even':'tr_odd';
				if(empty($all)||$all == 0){
					$proportion = 0;
				}else{
					$proportion = round($val['workload']/$all*100,2);
				}
				$str.=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$val[createName]
						</td>
						<td>
							$val[workload]
						</td>
						<td>
							$proportion %
						</td>
					</tr>
EOT;
			}
		}else{
			return $str.='<tr><td colspan="20">暂无相关数据</td></tr>';
		}
		return $str;
	}

	/*********************************业务操作-外部接口******************************/
	/**
	 * 重写添加函数
	 */
	function add_d($object){
		$weeklogDao = new model_rdproject_worklog_rdweeklog();
		//不关联项目时去掉
		if(empty($object['projectId'])){
			unset($object['projectId']);
		}

		if(empty($object['taskId'])){
			unset($object['taskId']);
		}else{
			$taskDao = new model_rdproject_task_rdtask();
			$tkactuserDao = new model_rdproject_task_tkactuser();

			$lateMark = null;
			$taskobject['id'] = $object['taskId'];
			$taskobject['status'] ='JXZ';
			$newPutload = $object['workloadDay'];
			//如果是任务负责人,则更新任务总体完成率
            $isCharege = $tkactuserDao->isCharegeUser($object['taskId'],$_SESSION['USER_ID']);
			if($isCharege){
				$taskobject['warpRate'] = round($this->biasProportion($object['taskplanStartDate'],$object['taskplanEndDate']),2);

				$lateMark = $this->laterThanThisDate_d($object['taskId'],$object['executionDate']);
				//判断是否存在执行时间比当前执行时间晚的日志,如果存在,则不修改当前的任务总体完成率
				if( !$lateMark ){
					$taskobject['effortRate'] = $object['effortRate'];
				}else{
					$object['workloadSurplus'] = '--';
				}
			}
			unset($object['taskplanStartDate']);
			unset($object['taskplanEndDate']);
		}
		try{
			$this->start_d();
			//检查当前周周志是否存在，返回周志Id
			$weeklogId = $weeklogDao->checkIsSet();
			$object['weekId'] = $weeklogId;

			//新增日志
			$newId = parent::add_d($object,true);

			//修改任务信息
			if(isset($object['taskId'])){
				//获取实际开始时间
				$actBeginDate = $taskDao->get_table_fields( $taskDao->tbl_name, "id=" . $object['taskId'] ,'actBeginDate' );
				if( empty($actBeginDate))
					$taskobject['actBeginDate'] =  $object['executionDate'];

				//修改任务信息
				$taskDao->updateById($taskobject);

				//更新任务已投入工作量
				$taskDao->updatePutload($object['taskId'],$newPutload);
			}

            //里程碑处理
            if(isset($object['stoneId'])&&$isCharege){
                $mildStoneDao = new model_rdproject_milestone_rdmilespoint();
                $mildStoneDao->updateMildstoneEff_d($object['stoneId']);
            }

			//修改日志描述
			$summaryDao = new model_rdproject_worklog_rdsummary();
			$summaryDao->appendThis_d($object['executionDate'],$object);

//            $this->rollBack();
			$this->commit_d();
			return $newId ;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 判断在次执行期后是否存在日志
	 */
	function laterThanThisDate_d($taskId,$executionDate){
		$this->searchArr['taskId'] = $taskId;
		$this->searchArr['laterDate'] = $executionDate;
		return $this->list_d('base_list');
	}

	/**
	 * 修改日志
	 */
	function edit_d($object){
		$taskDao = new model_rdproject_task_rdtask();
		//更新任务数据
		if($object['newWorkloadDay'] > $object['workloadDay']){
			$taskObject['putWorkload'] += $object['taskputWorkload'] + $object['newWorkloadDay'] - $object['workloadDay'];
		}elseif($object['newWorkloadDay'] <$object['workloadDay']) {
			$taskObject['putWorkload'] += $object['taskputWorkload'] - $object['workloadDay'] + $object['newWorkloadDay'];
		}else{
			$taskObject['putWorkload'] = $object['taskputWorkload'];
		}

		$tkactuserDao = new model_rdproject_task_tkactuser();
		if($tkactuserDao->isCharegeUser($object['taskId'],$_SESSION['USER_ID'])){
			$taskObject['effortRate'] = $object['effortRate'];
		}

		$taskObject['id'] = $object['taskId'];

		$object['workloadDay'] = $object['newWorkloadDay'];

//		print_r($taskObject);
		try{
			$this->start_d();
			//任务处理
			$taskDao->updateById($taskObject);
			//日志处理
			parent::edit_d($object);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * 获取任务的数据
	 */
	function getDateInTask($id){
		if($id){
			$task = new model_rdproject_task_rdtask();
			return $task->get_d($id);
		}
	}

	/**
	 * 计算任务偏差率
	 */
	function biasProportion($startDate,$endDate,$now = null){
		$startStamp = strtotime($startDate);
		$endStamp = strtotime($endDate);
		if($now == null ){
			$now = strtotime(day_date);
		}
		$rs = ((($now - $startStamp)/86400 - ($endStamp - $startStamp)/86400)/($endStamp/86400 - $startStamp/86400 + 1)) * 100;
		if($rs >= 0){
			return $rs;
		}else{
			return 0;
		}
	}

	/**
	 *	获取日志以及相关任务
	 */
	function getBase($id){
		$condition = array ("id" => $id );
		$rows = $this->find ( $condition );
		$task = new model_rdproject_task_rdtask();
		$rows['rdtask'] = $task->get_d($rows['taskId']);
		return $rows;
	}

	/**
	 * 获取日志 - 根据任务id
	 */
	function getInfoByTaskAndDate($taskId,$executionDate,$userId){
		$condition = array ('taskId' => $taskId , 'executionDate' => $executionDate ,'createId'=>$userId);
		$rows = $this->find ( $condition );
		$task = new model_rdproject_task_rdtask();
		$rows['rdtask'] = $task->get_d($taskId);
		return $rows;
	}

	/**
	 * 获取个人仪表盘相关数据
	 */
	function getDashResults($object){
		$rows = array();

		$logRs = $this->getWorkLog($object['beginDate'],$object['overDate'],$object['memberId']);

		//时间段内的工作量数组
		$this->dateForInfo = $this->changeToDateView($logRs);
//		print_r($this->dateForInfo);

		//工作量表
		$rows['workloadCount'] = $this->workloadCount($object['beginDate'],$object['overDate']);
		//日期列表
		$rows['dateList'] = $this->getDateList($object['beginDate'],$object['overDate'],$logRs);
//		print_r($rows['workloadCount']);

		//输出任务ID
		$task = new model_rdproject_task_rdtask();
		$taskrows = $task->findTaskByIds($this->taskIds);
		$rows['statuslist'] = $this->taskStatus($taskrows);

		//项目工作量统计
		$rows['projectWorklogList'] = $this->countProjectWorklog();
//		print_r($this->projectWorklog);

		return $rows;
	}

	/**
	 * 个人仪表盘 - 工作量分布图
	 */
	function loadSpread($beginDate,$overDate,$memberId = null ){
		$this->searchArr['beginDate'] = $beginDate;
		$this->searchArr['overDate'] = $overDate;
		if(!empty($memberId)){
			$this->searchArr['user_id'] = $memberId;
		}else{
			$this->searchArr['user_id'] = $_SESSION['USER_ID'];
		}
		$this->groupBy = 'c.projectId';
		$this->sort = 'c.id';
		return $this->listBySqlId('dashLoadSpead');
	}

	/**
	 * 个人仪表盘 - 工作量分布图展示
	 */
	function showChartsLoadSpead($rows,$title,$dateName=null){
		if($rows){
			$i = 0;
			$outArr = array ();
			foreach($rows as $val){
				if(empty($val['dataName'])){
					$outArr[$i][1] = $dateName;
				}else{
					$outArr[$i][1] = $val['dataName'];
				}
				$outArr[$i][3] = $val['dataNumber'];
				$outArr[$i][2] = $val['appraiseWorkload'];
				$i++;
			}
		}
		$fusionCharts = new model_common_fusionCharts();
	//	return $fusionCharts->showCharts($outArr,$title,null,'h','700','450');

		$chartConf = array('exportFileName'=>"$title",
							'caption'=>"$title",
							'exportAtClient'=>'0',               //0: 服务器端运行， 1： 客户端运行
	                        'exportAction'=>"download"             //如果是服务器运行，支持浏览器下载or服务器保存

		);
		echo $fusionCharts->showBarChart ($outArr,"MSColumnLine3D.swf",$chartConf);

	}

	/**
	 * 个人仪表盘 - 工作量分布图 - 任务
	 */
	function loadSpreadByTask($beginDate,$overDate,$memberId = null){
		$this->searchArr['beginDate'] = $beginDate;
		$this->searchArr['overDate'] = $overDate;
		if(!empty($memberId)){
			$this->searchArr['user_id'] = $memberId;
		}else{
			$this->searchArr['user_id'] = $_SESSION['USER_ID'];
		}
		$this->groupBy = 'c.taskId';
		$this->sort = 'c.id';
		return $this->listBySqlId('dashLoadSpeadByTask');
	}

	/**
	 * 获取时间段的工作日志
	 */
	function getWorkLog($beginDate,$overDate,$user=null,$taskId = null){
		$this->searchArr['beginDate'] = $beginDate;
		$this->searchArr['overDate'] = $overDate;
		if(!empty($user)){
			$this->searchArr['user_id'] = $user;
		}
		if(!empty($taskId)){
			$this->searchArr['taskId'] = $taskId;
		}
		return $this->listBySqlId('dashboard_list');
	}

	/**
	 * 由以上函数派生，只获取工作量执行日期
	 */
	function getWorkDay($beginDate,$overDate,$user=null,$taskId = null){
		$this->searchArr['beginDate'] = $beginDate;
		$this->searchArr['overDate'] = $overDate;
		if(!empty($user)){
			$this->searchArr['user_id'] = $user;
		}
		if(!empty($taskId)){
			$this->searchArr['taskId'] = $taskId;
		}
		return $this->listBySqlId('getWorkDay');
	}

	/**
	 * 工作进展-查看周志
	 */
	function getSchedule($rows,$type = null){
		$arrLogs = $this->getWorkLog($rows['beginDate'],$rows['overDate'],$rows['user_id']);
		$taskIds = $this->createDateArr($arrLogs);

		$objTask = new model_rdproject_task_rdtask();
		$taskrows = $objTask->findTaskByIds($taskIds);
		return $this->showTaskLog($taskrows);
	}

	/**
	 * 任务详细-查看日志
	 */
	function worklogInTask($taskId){
		$this->searchArr['taskId'] = $taskId;//任务ID
		return $this->pageBySqlId('readLogIntask');
	}


/*********begin********************组合统计 sql**By LiuB**2011年7月27日10:16:55*************************************************/
   /**
    * 组合统计 根据组合ID 查到所有所属项目Id
    * By LiuB 2011年7月27日9:33:08
    */
    function getGroup_d($gpId) {
		$sql = "select id  from oa_rd_project_view_group  where lft > (select  lft from oa_rd_group where id = $gpId )  AND  rgt<(select  rgt from oa_rd_group where id = $gpId)  AND type = 1";
		$arr = $this->findSql ( $sql );

		return $arr;
	}

   	/**
	 * 根据项目的ID对所有该项目的人员进行工作量统计
	 */
	function GroupWorklogByUserGPid($projectId){
		$sql = "select sum(c.workloadDay) as workload,c.createName from oa_rd_worklog c where 1=1 and c.projectId in ($projectId) group by c.createId order by id DESC";
		$arr = $this->findSql ( $sql );

		return $arr;
	}

	/**
	 * 根据项目ID获取所有的工作量
	 */
	function GroupAllWorklogByPjId($projectId){
		$sql = "select sum(c.workloadDay) as workload from oa_rd_worklog c where 1=1  and c.projectId in ($projectId)";
		$arr = $this->findSql ( $sql );
		return $arr['0']['workload'];
	}
/************END*************************************************************************************/



	/**
	 * 根据项目的ID对所有该项目的人员进行工作量统计
	 */
	function getWorklogByUserGPid($projectId){
		$this->searchArr['projectId'] = $projectId;
		$this->groupBy = 'c.createId';
		return $this->listBySqlId('getWorklogByUserGPid');
	}

	/**
	 * 根据项目ID获取所有的工作量
	 */
	function getAllWorklogByPjId($projectId){
		$this->searchArr['projectId'] = $projectId;
		$rows =  $this->listBySqlId('getAllWorklogByPjId');
		return $rows['0']['workload'];
	}

	/**
	 * 人员投入工作量透视图
	 */
	function worklogCharts($rows){
		if($rows){
			$i = 0;
			$outArr = array ();
			foreach($rows as $val){
				$outArr[$i][1] = $val['createName'];
				$outArr[$i][2] = $val['workload'];
				$i++;
			}
		}
		$fusionCharts = new model_common_fusionCharts();
		$chartConf = array('exportFileName'=>"人员投入工作量透视图",
					'caption'=>"人员投入工作量透视图",
					'exportAtClient'=>0,               //0: 服务器端运行， 1： 客户端运行
                    'exportAction'=>"download"   ,          //如果是服务器运行，支持浏览器下载or服务器保存
                    '$numberSuffix' =>"h"                //后缀

		);
		echo $fusionCharts->showCharts($outArr,"Pie2D.swf",$chartConf );
	}


	/**************************************数据操作-内部使用*******************************/
	/**
	 *	工作量统计
	 */
	private function workloadCount($beginDate,$overDate){
		//天数计算
		$countDays = (strtotime($overDate)-strtotime($beginDate))/86400 + 1;
		$average = round($this->allworklog['all']/$countDays,2);
		$str=<<<EOT
			<tr class="main_tr_header">
				<th>总工作量(小时)</th>
				<th>平均作量(小时)</th>
				<th>项目工作量(小时)</th>
				<th>非项目工作量(小时)</th>
			</tr>
			<tr>
				<td>{$this->allworklog['all']}</td>
				<td>$average</td>
				<td>{$this->allworklog['project']}</td>
				<td>{$this->allworklog['noproject']}</td>
			</tr>
EOT;
//		print_r($this->allworklog);
		return $str;
	}

	/**
	 * 项目工作量分布统计
	 */
	private function countProjectWorklog(){
		$str = "<tr class='main_tr_header'><td>序号</th><th>项目名称</th><th>工作量</th><th title='本周内该项目的工作量所占比例'>工作量比例</th><th>任务数</th><th title='本周内该项目所占的任务比例'>任务比例</th><th title='项目内任务平均完成率'>平均完成率</th></tr>";
		if($this->projectWorklog){
			$i = 0;
			foreach($this->projectWorklog as $key => $val){
				$i++;
				$classCss = (($i%2) == 0)?"tr_even":"tr_odd";
				$percentage = round(($val['logDay']/$this->allworklog['all'])*100,2);
				if(isset($val['taskNum'])){
					$taskNum = $val['taskNum'];
					$taskProportion = round(($val['taskNum']/$this->taskCount)*100,2);
					$perpercentage = round($val['effortRate']/$val['taskNum'],2);
				}else{
					$taskNum = 0;
					$taskProportion = 0;
					$perpercentage = 0;
				}
				if($key == ""){
					$key = '<font color="red">无项目任务</font>';
				}else{
					$key = $val['projectName'];
				}
				$str .=<<<EOT
					<tr class="$classCss">
						<td>
							$i
						</td>
						<td>
							$key
						</td>
						<td>
							{$val['logDay']} 小时
						</td>
						<td>
							$percentage %
						</td>
						<td>
							$taskNum
						</td>
						<td>
							$taskProportion %
						</td>
						<td>
							$perpercentage %
						</td>
					</tr>
EOT;
			}
			return $str;
		}else{
			return $str .'<tr><td colspan="20">暂无相关数据</td></tr>';
		}
	}

	/**
	 * 将数组转化成以时间为索引的数组
	 */
	private function changeToDateView($rows){
		$arr = array();
		if($rows){
			$inarr = array();
			$this->taskIds = ',';
			foreach($rows as $val){

				/**************************日期工作量数组***************************/
				if(isset($arr[$val['executionDate']."^ids"])){
					/*************************具体任务工作量*******************/
					$arr[$val['executionDate']."^ids"] .= ",".$val['id'];
					$arr[$val['executionDate']."^workloads"] .= ",".$val['workloadDay'];
					$arr[$val['executionDate']."^hours"] += $val['workloadDay'];

					/***********************所有工作量****************************/
					$this->allworklog['all'] += $val['workloadDay'];

					/***********************项目和非项目任务工作量*********************/
					if(empty($val['projectId'])){
						$this->allworklog['noproject'] += $val['workloadDay'];
					}else{
						$this->allworklog['project'] += $val['workloadDay'];
					}
				}else{
					/*************************具体任务工作量*******************/
					$arr[$val['executionDate']."^ids"] = $val['id'];
					$arr[$val['executionDate']."^workloads"] = $val['workloadDay'];
					$arr[$val['executionDate']."^hours"] = $val['workloadDay'];

					/***********************所有工作量****************************/
					$this->allworklog['all'] += $val['workloadDay'];

					/***********************项目和非项目任务工作量*********************/
					if(empty($val['projectId'])){
						$this->allworklog['noproject'] += $val['workloadDay'];
					}else{
						$this->allworklog['project'] += $val['workloadDay'];
					}
				}
				/***************************项目工作量数组********************************/
				if(isset($this->projectWorklog[$val['projectId']])){
					$this->projectWorklog[$val['projectId']]['logDay'] += $val['workloadDay'];
				}else{
					$this->projectWorklog[$val['projectId']]['projectName'] = $val['projectName'];
					$this->projectWorklog[$val['projectId']]['logDay'] = $val['workloadDay'];
				}

				/**************************任务ID字符串***********************************/

				if(!strstr($this->taskIds,','.$val['taskId'])){
					$this->taskIds .= $val['taskId'].',';
				}
			}
			$this->taskIds = substr($this->taskIds,1,-1);
		}
//		print_r($arr);
		return $arr;
	}

	/**
	 * 工作量统计日期列表
	 */
	private function getDateList($beginDate,$overDate,$logRs){
		$i=0;
		$str = null;
		$beginStamp = strtotime($beginDate);
		$overStamp = strtotime($overDate);

		$when = date('w',$beginStamp);
		$dateMark = 6 - $when;
		for($beginStamp;$beginStamp <= $overStamp ;$beginStamp+=86400){
			if($i%7 == 0){
				$str .= "<tr>";
			}

			if($i == $dateMark || $i == $dateMark + 1 )//表式周末
				$str .= "<td class='tr_odd'><p style='width:50%'>".date("Y-m-d",$beginStamp)."</p><hr><p style='width:50%'>
					".$this->filterArray(date("Y-m-d",$beginStamp))."</p></td>" ;
			else
				$str .= "<td class='tr_even'><p style='width:50%'>".date("Y-m-d",$beginStamp)."</p><hr><p style='width:50%'>" .
				$this->filterArray(date("Y-m-d",$beginStamp))."</p></td>" ;

			$i ++ ;

			if($i%7 == 0){
				$str .= "</tr>";
				$i = 0;
			}
		}
		return $str;
	}

	/**
	 * 过滤数组
	 */
	private function filterArray($whatDate){
//		print_r($this->arr);
		$str = null;
		if(!empty($this->dateForInfo)&&isset($this->dateForInfo[$whatDate.'^ids'])){
			$str = '<a href="?model=rdproject_worklog_rdworklog&action=dashDetail&ids='. $this->dateForInfo[$whatDate.'^ids'] .'" title="详细日志列表">'.$this->dateForInfo[$whatDate.'^hours'].'</a>';
		}else{
			$str = '无';
		}
		return $str;
	}

	/**
	 * 按任务状态统计
	 */
	private function taskStatus($rows){
		$statusArr = array();
		$projectIsSet = array();
		$str =<<<EOT
			<tr class="main_tr_header">
				<th rowspan="2">
				 	总数
				</th>
				<th rowspan="2">
					平均完成率
				</th>
				<th rowspan="2">
					项目任务数
				</th>
				<th rowspan="2">
					非项目任务数
				</th>
				<th colspan="20">
					状态统计
				</th>
			</tr>
			<tr class="main_tr_header">
EOT;
		$taskrows = array('all'=> 0,'effortRate' => 0, 'project' => 0,'noproject' => 0);
		$datadicrows = $this->getDatadicts('XMRWZT');
		foreach($datadicrows['XMRWZT'] as $val){
			$taskrows[$val['dataCode']] = 0;
			$str.=<<<EOT
				<th>
					$val[dataName]
				</th>
EOT;
		}
		$str.='</tr>';
//		print_r($rows);
		if($rows){
			foreach($rows as $key => $val){

				$taskrows['all'] += 1;
				$taskrows['effortRate'] += $val['effortRate'];
				if(empty($val['projectId']))
					$taskrows['noproject'] += 1;
				else
					$taskrows['project'] += 1;
				if(isset($taskrows[$val['status']])){
					$taskrows[$val['status']] += 1;
				}

				if(isset($projectIsSet[$val['projectId']])){
					$this->projectWorklog[$val['projectId']]['taskNum'] += 1;
					$this->projectWorklog[$val['projectId']]['effortRate'] += $val['effortRate'];
				}else{
					$projectIsSet[$val['projectId']] = 1;
					$this->projectWorklog[$val['projectId']]['taskNum'] = 1;
					$this->projectWorklog[$val['projectId']]['effortRate'] = $val['effortRate'];
				}
			}
		}

		$this->taskCount = $taskrows['all'];
//		print_r($taskrows);

		$str.='<tr class="tr_even">';
		foreach($taskrows as $key => $val){
			if($key == 'effortRate'){
				if($taskrows['all']){
					$val = round($taskrows['effortRate'] / $taskrows['all'],2) . "%";
				}else{
					$val = 0;
				}
			}

			$str.=<<<EOT
				<td>
					$val
				</td>
EOT;
		}
		$str.='</tr>';
		return $str;
	}

	/**
	 * 获取任务串同时生成日期数组
	 */
	private function createDateArr($rows){
		if($rows){
//			print_r($rows);
			$str = ',';
			foreach($rows as $val){
				if(!strstr($str,','.$val['taskId'])){
					$str .= $val['taskId'].',';
				}
				$time = date('w',strtotime($val['executionDate']));
				if(isset($this->dateForInfo[$val['taskId'].'^'.$time])){
					$this->dateForInfo[$val['taskId'].'^'.$time] += $val['workloadDay'];
				}else{
					$this->dateForInfo[$val['taskId'].'^'.$time] = $val['workloadDay'];
				}
			}
//			print_r($this->dateForInfo);
			return substr($str,1,-1);
		}else
			return ;
	}

	/**
	 * 个人仪表盘-图表使用数组
	 */
	private function picRows($rows){
//		print_r($rows);
		if($rows){
			$i = 0;
			foreach($rows as $key => $val ){
				if($key == ""){
					$val['projectName'] = "无项目任务";
				}
				$arrData[$i][1] = $val['projectName'];
				$arrData[$i][2] = $val['logDay'];
				$i++;
			}
			return $arrData;
		}
	}
}

?>
