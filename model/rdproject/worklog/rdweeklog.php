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

	/***********************************�б���ʾ*****************************/
	/**
	 * ������־
	 */
	function showlist($rows){
		$personfocusDao=new model_rdproject_personfocus_personfocus();
		if($rows){
			$i = 0;
			$str = "";
			foreach($rows as $key => $val){
				if($personfocusDao->isFocused($val['createId'],$_SESSION['USER_ID'])){
					$htmstr='�ѹ�ע|';
				}else{
					$htmstr='<a href="?model=rdproject_personfocus_personfocus&action=makeSure&id='.$val[id].'&user_id='.$val[createId].'&user_name='.$val[createName].'&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=300" class="thickbox" title="��ӹ�ע">��ӹ�ע</a> |';

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
							<a href="?model=rdproject_worklog_rdweeklog&action=view&id=$val[id]">��</a> |
							$htmstr
							<a href="?model=rdproject_worklog_rdweeklog&action=addremark&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox" title="��ӱ�ע">��ӱ�ע</a>
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
	 * �ҵĹ�����־
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
							<a href="?model=rdproject_worklog_rdweeklog&action=read&id=$val[id]">��</a> |
							<a href="?model=rdproject_worklog_rdweeklog&action=addremark&id=$val[id]&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800" class="thickbox">��ӱ�ע</a>
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
	 * ��ѯ��־�б�
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
			$str = '<tr><td colspan="50">���������־</td></tr>';
		}
		return $str;
	}

	/*************************************ҵ�񷽷�***********************************/

	/**
	 * ��д��ȡ���ݷ���
	 */
	function get_d($id,$type = null) {
		$condition = array ("id" => $id );
		$rows = $this->find ( $condition );
		//��ȡ����ְλ
		$user = new includes_class_global();
		$rows['position'] = $user->GetUserinfo($rows['createId'],'jobs_name');

		//���������¼
		$worklog = new model_rdproject_worklog_rdworklog();
		$rows['taskInDate'] = $worklog->getSchedule(array('beginDate' => $rows['weekBeginDate'],'overDate'=>$rows['weekEndDate'],'user_id' => $rows['createId']),'nopages');

//		print_r($rowsWorklog);
		//��ȡ����
		$task = new model_rdproject_task_rdtask();
		$rows['completedTask'] = $task->showEndTask($task->getTkBetweenDate($rows['weekBeginDate'],$rows['weekEndDate'],$rows['createId']));

		//��ȡ����ܽ�
		$summary = new model_rdproject_worklog_rdsummary();
		if(empty($type)){
			$rows['rdsummary'] = $summary->showlistEdit($summary->getSummaryById($id));
		}else{
			$rows['rdsummary'] = $summary->showlistView($summary->getSummaryById($id));
		}

		return $rows;
	}

	/**
	 * ֻ��ȡ���������
	 */
	function getBase($id){
		return parent::get_d($id);
	}

	/**
	 * ���ܣ����ұ����Ƿ�����־��
	 * ����У��򷵻���־Id
	 * ���û�У��½�һ����־��Ȼ�󷵻���־Id
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
	 * ���ܣ����һ���ܱ�
	 */
	function addWeeklog(){
		$otherdatasDao = new model_common_otherdatas();
		$summaryId = new model_rdproject_worklog_rdsummary();
		$thisWeek  = $this->getThatDate();
//		print_r($thisWeek);
		$object['weekTitle'] = $_SESSION['USERNAME'] .'������־( '.$thisWeek['0'] . ' ~ ' .$thisWeek['6'] .')';
		$object['weekBeginDate'] = $thisWeek['0'];
		$object['weekEndDate'] = $thisWeek['6'];
		$object['depId'] = $_SESSION['DEPT_ID'];
		$object['depName'] = $otherdatasDao->getUserDatas($_SESSION['USER_ID'],'DEPT_NAME');
		$object = $this->addCreateInfo($object);
		//�����־
		try{
			$weeklogId =  $this->add_d($object);
			//��鵱����־�����Ƿ����
			$summaryId->addWeekSummary($weeklogId,$thisWeek);
			return $weeklogId;
		}catch(exception $e){
			throw $e;
		}
	}

	function getThatDate(){
		$now = time(); //��ǰʱ���
		$w = date('w',$now); //��ǰ���ڼ�
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
		$begin = $now - $toModay*24*60*60 ; //��ǰʱ���ȥһ��
		$end = $now + $toSunday*24*60*60 ; //��ǰʱ���ȥһ��

		$beginDate = date('w',$begin);
		$endDate = date('w',$end);

		if($endDate > $beginDate){
			$countDate = $endDate - $beginDate;
		}else{
			$countDate = 6;
		}
		for($i = 0 ;$i <= $countDate ;$i++ ){
			$t = $begin + $i*24*60*60 ;
			$tw = date('w',$t);//��һ�����ڼ�
			$thisWeek[$i] =  date('Y-m-d',$t);
		}
		return $thisWeek;
	}

	/**
	 * ������־�޸�
	 */
	function editLog($object){
		$object = $this->addUpdateInfo($object);
//		print_r($object);
		$summary = new model_rdproject_worklog_rdsummary();
		try{
			$this->start_d();
			//�޸Ĺ�������
			$summary->editForRows($object['rdsummary']);
			unset($object['rdsummary']);
			//�޸���־
//			parent::edit_d($object);
			$this->commit_d();
			return true;
		}catch(exception $e){
			$this->rollBack();
			return false;
		}

	}

	/**
	 * getSearchList��ȡ��ѯ���Ľ��
	 */
	 function getSearchList(){
	 	//ѡȡ��һ��SQL
	 	$sql = $this->sql_arr['search_list'];

		//����ѡ��
	 	if(isset($this->searchArr['departmentIds'])){
			$sql .= " and c.depId in ( ". $this->searchArr['departmentIds']  ." )";
	 	}

		//��Աѡ��
	 	if(isset($this->searchArr['personIds'])){
			$sql .= " and c.createId in ( ". $this->strManage($this->searchArr['personIds'])  ." )";
	 	}

		//��Ŀѡ��
	 	if(isset($this->searchArr['w_projectId'])){
			$sql .= " and w.projectId = ' ".$this->searchArr['w_projectId'] ." '";
		}

		//��ʼʱ��
		if(isset($this->searchArr['beginDate'])){
			$sql .= " and (c.weekEndDate > '" .$this->searchArr['beginDate'] . "'  or c.weekEndDate = '" .$this->searchArr['beginDate'] . "' ) ";
		}

		//��ֹʱ��
		if(isset($this->searchArr['overDate'])){
			$sql .= " and (c.weekBeginDate < '" .$this->searchArr['overDate'] . "' or c.weekBeginDate = '" .$this->searchArr['overDate'] . "'  ) ";
		}

//	 	print_r($this->searchArr);
	 	//����group by
	 	$groupBy = "c.id";
//		$groupBy = $this->groupBy;
		if (isset ( $groupBy ) && $groupBy != "" && $groupBy != "id") {
			$groupBy = " group By $groupBy ";
			$countsql = "select 0 " . substr ( $sql, strpos ( $sql, "from" ) );
			$countsql = "select count(0) as num from ( " . $countsql . " " . $groupBy . " ) as t";
		} else {
			//�����ȡ��¼��sql
			$countsql = "select count(0) as num " . substr ( $sql, strpos ( $sql, "from" ) );
		}
		//print($countsql);
		$this->count = $this->queryCount ( $countsql );
		//ƴװ��������
//		$sql = $this->createQuery ( $sql, $this->searchArr );
		//print($sql);
		//����������Ϣ
		$asc = $this->asc ? "DESC" : "ASC";
		$sql .= " $groupBy order by " . $this->sort . " " . $asc;
		//������ȡ��¼��
		$sql .= " limit " . $this->start . "," . $this->pageSize;
//								echo($sql);
		return $this->_db->getArray ( $sql );

	 }

	/**
	 * in  �ַ������� Ϊÿ���ַ�����ӵ�����
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
	 * ��ȡ������־
	 */
	function getSubordinateLog(){
		$this->groupBy = 'w.id';
		return $this->pageBySqlId ('subordinateLog');
	}
}
?>
