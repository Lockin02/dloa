<?php
/*
 * Created on 2010-9-14
 * ������־
 */
class model_rdproject_worklog_rdworklog extends model_base{
	function __construct() {
		$this->tbl_name = "oa_rd_worklog";
		$this->sql_map = "rdproject/worklog/rdworklogSql.php";
		parent::__construct ();
	}

	private $dateForInfo;

	private $allworklog = array('all' => 0 ,'project' => 0,'noproject' => 0);//���й���������Ŀ������������Ŀ������ͳ��

	private $projectWorklog ;//��Ŀ������ͳ��

	private $taskCount ;

	private $taskIds ;

	/***********************************�б���ʾ*****************************/
	/**
	 * Ĭ����ʾ��־
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
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[effortRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[warpRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[workloadDay]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[workloadSurplus]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[planEndDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[executionDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[updateTime]</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">���������־</td></tr>';
		}
		return $str;
	}

	/**
	 *  Ƕ�׵�������ʾ�б�
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
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[effortRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[warpRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[workloadDay]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[workloadSurplus]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[planEndDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[executionDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="�鿴��־��ϸ">$val[updateTime]</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">���������־</td></tr>';
		}
		return $str;
	}

	/**
	 * ���������¼�б���ʾ
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
					$imgSrc = '<img src="images/icon/red.gif" title="ƫ���ʣ�'.$val['warpRate'].'%">';
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
							<a href="?model=rdproject_task_rdtask&action=toReadTask&id=$val[id]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800" title="�鿴< $val[name] >" class="thickbox">$val[name]</a>
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
							$val[appraiseWorkload] Сʱ
						</td>
						<td>
							$val[putWorkload] Сʱ
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
			$str = '<tr><td colspan="50">���������־</td></tr>';
		}
		return $str;
	}

	/**
	 * ����������ʾ��־�б�
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
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="�鿴��־��ϸ">$val[createName]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="�鿴��־��ϸ">$val[effortRate] %</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="�鿴��־��ϸ">$val[workloadDay] Сʱ</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="�鿴��־��ϸ">$val[executionDate]</a>
						</td>
						<td>
							<a href="?model=rdproject_worklog_rdworklog&action=viewWorkLog&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700" class="thickbox" title="�鿴��־��ϸ">$val[description]</a>
						</td>
					</tr>
EOT;
			}
		}else {
			$str = '<tr><td colspan="50">���������־</td></tr>';
		}
		return $str;
	}

	/**
	 * ��ԱͶ�빤����͸��ͼ�����Ⱦ
	 */
	function worklogTable($rows,$all){
		$str =<<<EOT
			<table class="main_table">
				<tr class="main_tr_header">
					<th>
						���
					</th>
					<th>
						����
					</th>
					<th>
						Ͷ�빤����
					</th>
					<th>
						����
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
			return $str.='<tr><td colspan="20">�����������</td></tr>';
		}
		return $str;
	}

	/*********************************ҵ�����-�ⲿ�ӿ�******************************/
	/**
	 * ��д��Ӻ���
	 */
	function add_d($object){
		$weeklogDao = new model_rdproject_worklog_rdweeklog();
		//��������Ŀʱȥ��
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
			//�������������,������������������
            $isCharege = $tkactuserDao->isCharegeUser($object['taskId'],$_SESSION['USER_ID']);
			if($isCharege){
				$taskobject['warpRate'] = round($this->biasProportion($object['taskplanStartDate'],$object['taskplanEndDate']),2);

				$lateMark = $this->laterThanThisDate_d($object['taskId'],$object['executionDate']);
				//�ж��Ƿ����ִ��ʱ��ȵ�ǰִ��ʱ�������־,�������,���޸ĵ�ǰ���������������
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
			//��鵱ǰ����־�Ƿ���ڣ�������־Id
			$weeklogId = $weeklogDao->checkIsSet();
			$object['weekId'] = $weeklogId;

			//������־
			$newId = parent::add_d($object,true);

			//�޸�������Ϣ
			if(isset($object['taskId'])){
				//��ȡʵ�ʿ�ʼʱ��
				$actBeginDate = $taskDao->get_table_fields( $taskDao->tbl_name, "id=" . $object['taskId'] ,'actBeginDate' );
				if( empty($actBeginDate))
					$taskobject['actBeginDate'] =  $object['executionDate'];

				//�޸�������Ϣ
				$taskDao->updateById($taskobject);

				//����������Ͷ�빤����
				$taskDao->updatePutload($object['taskId'],$newPutload);
			}

            //��̱�����
            if(isset($object['stoneId'])&&$isCharege){
                $mildStoneDao = new model_rdproject_milestone_rdmilespoint();
                $mildStoneDao->updateMildstoneEff_d($object['stoneId']);
            }

			//�޸���־����
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
	 * �ж��ڴ�ִ���ں��Ƿ������־
	 */
	function laterThanThisDate_d($taskId,$executionDate){
		$this->searchArr['taskId'] = $taskId;
		$this->searchArr['laterDate'] = $executionDate;
		return $this->list_d('base_list');
	}

	/**
	 * �޸���־
	 */
	function edit_d($object){
		$taskDao = new model_rdproject_task_rdtask();
		//������������
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
			//������
			$taskDao->updateById($taskObject);
			//��־����
			parent::edit_d($object);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}
	}

	/**
	 * ��ȡ���������
	 */
	function getDateInTask($id){
		if($id){
			$task = new model_rdproject_task_rdtask();
			return $task->get_d($id);
		}
	}

	/**
	 * ��������ƫ����
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
	 *	��ȡ��־�Լ��������
	 */
	function getBase($id){
		$condition = array ("id" => $id );
		$rows = $this->find ( $condition );
		$task = new model_rdproject_task_rdtask();
		$rows['rdtask'] = $task->get_d($rows['taskId']);
		return $rows;
	}

	/**
	 * ��ȡ��־ - ��������id
	 */
	function getInfoByTaskAndDate($taskId,$executionDate,$userId){
		$condition = array ('taskId' => $taskId , 'executionDate' => $executionDate ,'createId'=>$userId);
		$rows = $this->find ( $condition );
		$task = new model_rdproject_task_rdtask();
		$rows['rdtask'] = $task->get_d($taskId);
		return $rows;
	}

	/**
	 * ��ȡ�����Ǳ����������
	 */
	function getDashResults($object){
		$rows = array();

		$logRs = $this->getWorkLog($object['beginDate'],$object['overDate'],$object['memberId']);

		//ʱ����ڵĹ���������
		$this->dateForInfo = $this->changeToDateView($logRs);
//		print_r($this->dateForInfo);

		//��������
		$rows['workloadCount'] = $this->workloadCount($object['beginDate'],$object['overDate']);
		//�����б�
		$rows['dateList'] = $this->getDateList($object['beginDate'],$object['overDate'],$logRs);
//		print_r($rows['workloadCount']);

		//�������ID
		$task = new model_rdproject_task_rdtask();
		$taskrows = $task->findTaskByIds($this->taskIds);
		$rows['statuslist'] = $this->taskStatus($taskrows);

		//��Ŀ������ͳ��
		$rows['projectWorklogList'] = $this->countProjectWorklog();
//		print_r($this->projectWorklog);

		return $rows;
	}

	/**
	 * �����Ǳ��� - �������ֲ�ͼ
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
	 * �����Ǳ��� - �������ֲ�ͼչʾ
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
							'exportAtClient'=>'0',               //0: �����������У� 1�� �ͻ�������
	                        'exportAction'=>"download"             //����Ƿ��������У�֧�����������or����������

		);
		echo $fusionCharts->showBarChart ($outArr,"MSColumnLine3D.swf",$chartConf);

	}

	/**
	 * �����Ǳ��� - �������ֲ�ͼ - ����
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
	 * ��ȡʱ��εĹ�����־
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
	 * �����Ϻ���������ֻ��ȡ������ִ������
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
	 * ������չ-�鿴��־
	 */
	function getSchedule($rows,$type = null){
		$arrLogs = $this->getWorkLog($rows['beginDate'],$rows['overDate'],$rows['user_id']);
		$taskIds = $this->createDateArr($arrLogs);

		$objTask = new model_rdproject_task_rdtask();
		$taskrows = $objTask->findTaskByIds($taskIds);
		return $this->showTaskLog($taskrows);
	}

	/**
	 * ������ϸ-�鿴��־
	 */
	function worklogInTask($taskId){
		$this->searchArr['taskId'] = $taskId;//����ID
		return $this->pageBySqlId('readLogIntask');
	}


/*********begin********************���ͳ�� sql**By LiuB**2011��7��27��10:16:55*************************************************/
   /**
    * ���ͳ�� �������ID �鵽����������ĿId
    * By LiuB 2011��7��27��9:33:08
    */
    function getGroup_d($gpId) {
		$sql = "select id  from oa_rd_project_view_group  where lft > (select  lft from oa_rd_group where id = $gpId )  AND  rgt<(select  rgt from oa_rd_group where id = $gpId)  AND type = 1";
		$arr = $this->findSql ( $sql );

		return $arr;
	}

   	/**
	 * ������Ŀ��ID�����и���Ŀ����Ա���й�����ͳ��
	 */
	function GroupWorklogByUserGPid($projectId){
		$sql = "select sum(c.workloadDay) as workload,c.createName from oa_rd_worklog c where 1=1 and c.projectId in ($projectId) group by c.createId order by id DESC";
		$arr = $this->findSql ( $sql );

		return $arr;
	}

	/**
	 * ������ĿID��ȡ���еĹ�����
	 */
	function GroupAllWorklogByPjId($projectId){
		$sql = "select sum(c.workloadDay) as workload from oa_rd_worklog c where 1=1  and c.projectId in ($projectId)";
		$arr = $this->findSql ( $sql );
		return $arr['0']['workload'];
	}
/************END*************************************************************************************/



	/**
	 * ������Ŀ��ID�����и���Ŀ����Ա���й�����ͳ��
	 */
	function getWorklogByUserGPid($projectId){
		$this->searchArr['projectId'] = $projectId;
		$this->groupBy = 'c.createId';
		return $this->listBySqlId('getWorklogByUserGPid');
	}

	/**
	 * ������ĿID��ȡ���еĹ�����
	 */
	function getAllWorklogByPjId($projectId){
		$this->searchArr['projectId'] = $projectId;
		$rows =  $this->listBySqlId('getAllWorklogByPjId');
		return $rows['0']['workload'];
	}

	/**
	 * ��ԱͶ�빤����͸��ͼ
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
		$chartConf = array('exportFileName'=>"��ԱͶ�빤����͸��ͼ",
					'caption'=>"��ԱͶ�빤����͸��ͼ",
					'exportAtClient'=>0,               //0: �����������У� 1�� �ͻ�������
                    'exportAction'=>"download"   ,          //����Ƿ��������У�֧�����������or����������
                    '$numberSuffix' =>"h"                //��׺

		);
		echo $fusionCharts->showCharts($outArr,"Pie2D.swf",$chartConf );
	}


	/**************************************���ݲ���-�ڲ�ʹ��*******************************/
	/**
	 *	������ͳ��
	 */
	private function workloadCount($beginDate,$overDate){
		//��������
		$countDays = (strtotime($overDate)-strtotime($beginDate))/86400 + 1;
		$average = round($this->allworklog['all']/$countDays,2);
		$str=<<<EOT
			<tr class="main_tr_header">
				<th>�ܹ�����(Сʱ)</th>
				<th>ƽ������(Сʱ)</th>
				<th>��Ŀ������(Сʱ)</th>
				<th>����Ŀ������(Сʱ)</th>
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
	 * ��Ŀ�������ֲ�ͳ��
	 */
	private function countProjectWorklog(){
		$str = "<tr class='main_tr_header'><td>���</th><th>��Ŀ����</th><th>������</th><th title='�����ڸ���Ŀ�Ĺ�������ռ����'>����������</th><th>������</th><th title='�����ڸ���Ŀ��ռ���������'>�������</th><th title='��Ŀ������ƽ�������'>ƽ�������</th></tr>";
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
					$key = '<font color="red">����Ŀ����</font>';
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
							{$val['logDay']} Сʱ
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
			return $str .'<tr><td colspan="20">�����������</td></tr>';
		}
	}

	/**
	 * ������ת������ʱ��Ϊ����������
	 */
	private function changeToDateView($rows){
		$arr = array();
		if($rows){
			$inarr = array();
			$this->taskIds = ',';
			foreach($rows as $val){

				/**************************���ڹ���������***************************/
				if(isset($arr[$val['executionDate']."^ids"])){
					/*************************������������*******************/
					$arr[$val['executionDate']."^ids"] .= ",".$val['id'];
					$arr[$val['executionDate']."^workloads"] .= ",".$val['workloadDay'];
					$arr[$val['executionDate']."^hours"] += $val['workloadDay'];

					/***********************���й�����****************************/
					$this->allworklog['all'] += $val['workloadDay'];

					/***********************��Ŀ�ͷ���Ŀ��������*********************/
					if(empty($val['projectId'])){
						$this->allworklog['noproject'] += $val['workloadDay'];
					}else{
						$this->allworklog['project'] += $val['workloadDay'];
					}
				}else{
					/*************************������������*******************/
					$arr[$val['executionDate']."^ids"] = $val['id'];
					$arr[$val['executionDate']."^workloads"] = $val['workloadDay'];
					$arr[$val['executionDate']."^hours"] = $val['workloadDay'];

					/***********************���й�����****************************/
					$this->allworklog['all'] += $val['workloadDay'];

					/***********************��Ŀ�ͷ���Ŀ��������*********************/
					if(empty($val['projectId'])){
						$this->allworklog['noproject'] += $val['workloadDay'];
					}else{
						$this->allworklog['project'] += $val['workloadDay'];
					}
				}
				/***************************��Ŀ����������********************************/
				if(isset($this->projectWorklog[$val['projectId']])){
					$this->projectWorklog[$val['projectId']]['logDay'] += $val['workloadDay'];
				}else{
					$this->projectWorklog[$val['projectId']]['projectName'] = $val['projectName'];
					$this->projectWorklog[$val['projectId']]['logDay'] = $val['workloadDay'];
				}

				/**************************����ID�ַ���***********************************/

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
	 * ������ͳ�������б�
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

			if($i == $dateMark || $i == $dateMark + 1 )//��ʽ��ĩ
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
	 * ��������
	 */
	private function filterArray($whatDate){
//		print_r($this->arr);
		$str = null;
		if(!empty($this->dateForInfo)&&isset($this->dateForInfo[$whatDate.'^ids'])){
			$str = '<a href="?model=rdproject_worklog_rdworklog&action=dashDetail&ids='. $this->dateForInfo[$whatDate.'^ids'] .'" title="��ϸ��־�б�">'.$this->dateForInfo[$whatDate.'^hours'].'</a>';
		}else{
			$str = '��';
		}
		return $str;
	}

	/**
	 * ������״̬ͳ��
	 */
	private function taskStatus($rows){
		$statusArr = array();
		$projectIsSet = array();
		$str =<<<EOT
			<tr class="main_tr_header">
				<th rowspan="2">
				 	����
				</th>
				<th rowspan="2">
					ƽ�������
				</th>
				<th rowspan="2">
					��Ŀ������
				</th>
				<th rowspan="2">
					����Ŀ������
				</th>
				<th colspan="20">
					״̬ͳ��
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
	 * ��ȡ����ͬʱ������������
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
	 * �����Ǳ���-ͼ��ʹ������
	 */
	private function picRows($rows){
//		print_r($rows);
		if($rows){
			$i = 0;
			foreach($rows as $key => $val ){
				if($key == ""){
					$val['projectName'] = "����Ŀ����";
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
