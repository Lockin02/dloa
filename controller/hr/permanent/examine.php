<?php
/**
 * @author Administrator
 * @Date 2012��8��6�� 21:39:45
 * @version 1.0
 * @description:Ա��ת��������������Ʋ�
 */
class controller_hr_permanent_examine extends controller_base_action {

	function __construct() {
		$this->objName = "examine";
		$this->objPath = "hr_permanent";
		parent::__construct();
	}

	/**
	 * �����������
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '�ύ�ɹ���';
		if ($id) {
			msg ($msg);
		}
	}

	/*
	 * ��ת��Ա��ת�������������б�Ա���б�
	 */
	function c_page() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('list');
	}

	/*
	 * ��ת��Ա��ת�������������б�(��ʦ�б�)
	 */
	function c_masterPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('masterlist');
	}

	/*
	 * ��ת��Ա��ת�������������б�(�쵼�б�)
	 */
	function c_leaderPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('leaderlist');
	}

	/*
	 * ��ת��Ա��ת�������������б�(�ܼ��б�)
	 */
	function c_directorPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('directorlist');
	}

	/*
	 * ��ת��Ա��ת�������������б�(�ܼ��б�)
	 */
	function c_hrPage() {
		$this->view('hrlist');
	}

	/**
	 * ��Ӧjson
	 */
	function c_hrJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ('hrsql');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		$deptDao = new model_deptuser_dept_dept();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//ת��������
				$rows[$key]['statusC'] = $service -> statusDao -> statusKtoC($rows[$key]['status']);
				$info = $deptDao->find(array("DEPT_ID"=>$rows[$key]['deptId']));
				$row[$key]['directorId'] = $info['Leader_id'];
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת��Ա��ת�������������б�(�����б�)
	 */
	function c_allPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('alllist');
	}

	/**
	 * ��ת��Ա��ת�������������б�(�����б�)
	 */
	function c_nopassPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('status');
	}

	/**
	 * ��ת��Ա��ת�������������б�(�����б�)
	 */
	function c_passPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('passed');
	}

	/**
	 * ��ת��Ա��ת�������������б�(�����б�)
	 */
	function c_tabPage() {
		$this->view('tabpage');
	}

	/**
	 * ��ת������Ա��ת������������ҳ��
	 */
	function c_toAdd() {
		$personnel = new model_hr_personnel_personnel();
		$getinfo = $personnel->find(array('userAccount'=>$_SESSION['USER_ID']));
		if(is_array($getinfo)) {//�ж��Ƿ��е�����Ϣ
			foreach ($getinfo as $key => $val) {
				$this->assign($key, $val);
			}
		} else {
			$this->assign('userNo', "");
			$this->assign('userName', "");
			$this->assign('sex', "");
			$this->assign('age', "");
			$this->assign('deptNameS', "");
			$this->assign('userAccount', "");
			$this->assign('belongDeptId', "");
			$this->assign('belongDeptName', "");
			$this->assign('belongDeptCode', "");
			$this->assign('jobId', "");
			$this->assign('jobName', "");
			$this->assign('highEducationName', "");
			$this->assign('highEducation', "");
			$this->assign('highSchool', "");
			$this->assign('professionalName', "");
			$this->assign('mobile', "");
			$this->assign('schemeName', "");
			$this->assign('schemeId', "");
			$this->assign('tutorId', "");
			$this->assign('tutor', "");
		}
		//��ȡ��ͬ��Ϣ
		$contDao = new model_hr_contract_contract();
		$conInfo = $contDao->getConInfoByUserId($_SESSION['USER_ID'],'HRHTLX-05,HRHTLX-01,HRHTLX-06');
		if( is_array($conInfo)){
			$this->assign('begintime', $conInfo['0']['trialBeginDate']);
			$this->assign('finishtime', $conInfo['0']['trialEndDate']);
		}else{
			$this->assign('begintime', $getinfo['entryDate']);
			$this->assign('finishtime', $getinfo['becomeDate']);
		}

		//��ȡ��ʦ��Ϣ
		$tutorDao=new model_hr_tutor_tutorrecords();
		$tutorRow=$tutorDao->getInfoByStuUserNo_d($getinfo['userNo']);
		if(is_array($tutorRow)){
			$this->assign('leaderName', $tutorRow['0']['studentSuperior']);
			$this->assign('leaderId', $tutorRow['0']['studentSuperiorId']);
			$this->assign('tutor', $tutorRow['0']['userName']);
			$this->assign('tutorId', $tutorRow['0']['userAccount']);
		} else {
			$this->assign('leaderName', '');
			$this->assign('leaderId', '');
		}
		$this->assign("reformDT", date('Y-m-d'));

		if($getinfo['wageLevelCode'] == 'GZJBFGL') {
			$this->assign("summarystatus", '0');
			$this->assign("planstatus", '0');
			$this->assign("summarysetting"," <div id='summaryTable'></div>");
			$this->assign("plansetting"," <div id='planTable'></div>");
		} else {
			$this->assign("summarystatus", '1');
			$this->assign("planstatus", '1');
			$this->assign("summarysetting"," <textarea class='txt_txtarea_font' style='width: 75%;' id='summary' name='examine[summary]' ></textarea>");
			$this->assign("plansetting"," <textarea class='txt_txtarea_font' style='width: 75%;'  id='plan' name='examine[plan]' ></textarea>");
		}
		$this->view('add',true);
	}

	/**
	 * ��ת���༭Ա��ת������������ҳ��
	 */
	function c_toEdit() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0){
			$this->assign("summaryinfo","<div id='summaryTable'></div>");
			$this->assign("planinfo","<div id='planTable'></div>");
		} else if ($obj['planstatus'] == 1 && $obj['summarystatus'] == 1) {
			$this->assign("summaryinfo",$obj['summary']);
			$this->assign("planinfo",$obj['plan']);
		}
		$this->view('edit',true);
	}

	function autoEdit(){
		$obj = $_POST[$this->objName];
		$this->service->autoEdit_d($obj);
	}

	/**
	 * ��ת���༭Ա��ת������������ҳ��
	 */
	function c_isAccept() {
		$personDao = new model_hr_permanent_scheme();

		$find = $personDao->get_d($_GET['schemeId']);

		if($find['schemeTypeCode']=='HRKHSJBM'){
			succ_show('controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&examCode=oa_hr_permanent_examine&billId=' . $_GET['id']);
		}elseif($find['schemeTypeCode']=='KHJBZJ'){
			succ_show('controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&examCode=oa_hr_permanent_examine&billId=' . $_GET['id']);
		}elseif($find['schemeTypeCode']=='KHJBGG'){
			succ_show('controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&examCode=oa_hr_permanent_examine&billId=' . $_GET['id']);
		}else{
			succ_show('controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&examCode=oa_hr_permanent_examine&billId=' . $_GET['id']);
		}
	}

	/**
	 * ��ת���༭Ա��ת������������ҳ�棨��ʦ�б�
	 */
	function c_toMasterEdit() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("tutorDate", date('Y-m-d'));
		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0){
			$this->assign("summaryinfo","<div id='summaryTable'></div>");
			$this->assign("planinfo","<div id='planTable'></div>");
		} else if ($obj['planstatus'] == 1 && $obj['summarystatus'] == 1){
			$this->assign("summaryinfo",$obj['summary']);
			$this->assign("planinfo",$obj['plan']);
		}
		$this->view('masteredit',true);
	}

	/**
	 * ��ת���༭Ա��ת������������ҳ�棨�쵼�б�
	 */
	function c_toLeaderEdit() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("leaderDate", date('Y-m-d'));
		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0) {
			$this->assign("summaryinfo","<div id='summaryTable'></div>");
			$this->assign("planinfo","<div id='planTable'></div>");
		} else if ($obj['planstatus'] == 1 && $obj['summarystatus'] == 1) {
			$this->assign("summaryinfo",$obj['summary']);
			$this->assign("planinfo",$obj['plan']);
		}
		$this->view('leaderedit' ,true);
	}

	/**
	 * ��ת������н��ҳ��
	 */
	function c_toEditSalary(){
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$personnel = new model_hr_personnel_personnel();
		$row=$personnel->getPersonnelSimpleInfo_d($obj['userAccount']);
		$entryDao = new model_hr_recruitment_entryNotice();
		$entryRow=$entryDao->getInfoByUserID_d($obj['userAccount']);
		if($obj['interviewSalary']!=''){
			$this->assign('interviewSalary', $obj['interviewSalary']);
		}else if(is_array($entryRow)){
			$this->assign('interviewSalary', $entryRow['useFormalWage']);
		}else{
			$this->assign('interviewSalary', '');
		}
		if($obj['suggestJobName']!=''){
			$this->assign('suggestJobName', $obj['suggestJobName']);
			$this->assign('suggestJobId', $obj['suggestJobId']);
			$this->assign('suggestLevelName', $obj['suggestLevelName']);
		}else{
			$this->assign('suggestJobName', $row['jobName']);
			$this->assign('suggestJobId', $row['jobId']);
			$this->assign('suggestLevelName', $row['jobLevel']);
		}
		$this->assign('mainId', $row['id']);
		$this->view('salaryedit' ,true);
	}

	/**
	 * ��ת���༭Ա��ת������������ҳ�棨�ܼ��б�
	 */
	function c_toDirectorEdit() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this -> showDatadicts(array('levelCode' => 'HRGZJB'), $obj['levelCode'], true);
		$otherdatasDao=new model_common_otherdatas();
		$flag = $otherdatasDao->isFirstStep($_GET['id'] ,$this->service->tbl_name);
		$this -> showDatadicts(array('levelCode' => 'HRGZJB'), $obj['levelCode'], true);
		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0) {
			$this->assign("summaryinfo","<div id='summaryTable'></div>");
			$this->assign("planinfo","<div id='planTable'></div>");
		} else {
			$this->assign("summaryinfo",$obj['summary']);
			$this->assign("planinfo",$obj['plan']);
		}
		if($obj['planstatus'] == 0 && $obj['summarystatus'] == 0) {
			$userDao = new model_deptuser_user_user();
			$mastercomment = $obj['masterComment'];
			$leadercomment = $obj['leaderComment'];
			$master = $userDao->find(array('USER_ID'=>$obj['tutorId']));
			$leader = $userDao->find(array('USER_ID'=>$obj['leaderId']));
			$masterName = $master['USER_NAME'];
			$leaderName = $leader['USER_NAME'];
			if( $obj['tutorDate'] == '0000-00-00') {
				$masterDate = '';
			} else {
				$masterDate = $obj['tutorDate'];
			}
			if( $obj['leaderDate'] == '0000-00-00') {
				$leaderDate = '';
			} else {
				$leaderDate = $obj['leaderDate'];
			}

			$message2 = <<<EOT
				<tr>
					<td class="form_text_left_three">��ʦ���</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">����ʦ</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">��ʦȷ��ʱ��</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">ֱ���쵼���</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">ֱ���쵼</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">�쵼ȷ��ʱ��</td>
					<td class="form_text_right_three">
						$leaderDate
					</td>
				</tr>
EOT;
		} else {
			$message2 = '';
		}
		$this->assign("Commentinfo",$message2);
		if($flag) {
			$this->view('directoredit' ,true);
		} else {
			$this -> c_toView();
			return;
		}
	}

	/**
	 * ��ת������Ա��ת������������ҳ�棨�ܼ��б�
	 */
	function c_toDirectorSet() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			if($key == 'permanentTypeCode') {
				if ($val == "ZZLXTQZZ") {
					$this->assign("chec1" ,"checked");
				}
				if ($val == "ZZLXAQZZ") {
					$this->assign("chec2" ,"checked");
				}
				if ($val == "ZZLXCT") {
					$this->assign("chec3" ,"checked");
				}
			} else {
				$this->assign($key, $val);
			}
		}

		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0){
			$this->assign("summaryinfo","<div id='summaryTable'></div>");
			$this->assign("planinfo","<div id='planTable'></div>");
		} else {
			$this->assign("summaryinfo",$obj['summary']);
			$this->assign("planinfo",$obj['plan']);
		}
		if($obj['planstatus'] == 0 && $obj['summarystatus'] == 0) {
			$userDao = new model_deptuser_user_user();
			$mastercomment = $obj['masterComment'];
			$leadercomment = $obj['leaderComment'];
			$master = $userDao->find(array('USER_ID'=>$obj['tutorId']));
			$leader = $userDao->find(array('USER_ID'=>$obj['leaderId']));
			$masterName = $master['USER_NAME'];
			$leaderName = $leader['USER_NAME'];
			$masterDate = $obj['tutorDate'];
			$leaderDate = $obj['leaderDate'];

			$message2 = <<<EOT
				<tr>
					<td class="form_text_left_three">��ʦ���</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">����ʦ</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">��ʦȷ��ʱ��</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">ֱ���쵼���</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">ֱ���쵼</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">�쵼ȷ��ʱ��</td>
					<td class="form_text_right_three">
						$leaderDate
					</td>
				</tr>
EOT;
		} else {
			$message2 = '';
		}
		$this->assign("Commentinfo" ,$message2);
		$this->view('directorset' ,true);
	}

	/**
	 * ��ת���༭Ա��ת������������ҳ�棨Ա������б�
	 */
	function c_toLastEdit() {
		$this -> permCheck();
		//��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$message = '';
		if($obj['status']>=$this->service->statusDao->statusEtoK ( 'hrcheck' )){
			$directorComment = $obj['directorComment'];
			$permanent = $obj['permanentType'];
			if($permanent=="��ǰת��"){
				$permanent .="   ".$obj['permanentDate'];
			}
			$beforeSalary = $obj['beforeSalary'];
			$afterSalary = $obj['afterSalary'];
			$afterPositionName = $obj['afterPositionName'];
			$levelName = $obj['levelCode'];
			$message = <<<EOT
				 <table class="form_main_table">
					<tr>
						<td class="form_text_right_three">
							<h4>����:</h4>$directorComment
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three" id="ZZYJ">
							<h4>ת�����:</h4><font color=blue>$permanent</font>
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three">
							<h4>ת��������ְλȷ��:</h4>
							ת��ǰ����:<font color=blue>$beforeSalary</font> Ԫ/�£�ת������:<font color=blue>$afterSalary</font> Ԫ/��<br>
							ת����ְλ:<font color=blue>$afterPositionName</font>
							��ת����ְ��:<font color=blue>$levelName</font>
						</td>
					<tr>
				 </table>
EOT;

		}
		$this->assign("directorMessage",$message);
		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0){
			$this->assign("summaryinfo","<div id='summaryTable'></div>");
			$this->assign("planinfo","<div id='planTable'></div>");
		} else if ($obj['planstatus'] == 1 && $obj['summarystatus'] == 1){
			$this->assign("summaryinfo",$obj['summary']);
			$this->assign("planinfo",$obj['plan']);
		}
		if($obj['planstatus']==0&&$obj['summarystatus']==0){
			$userDao = new model_deptuser_user_user();
			$mastercomment = $obj['masterComment'];
			$leadercomment = $obj['leaderComment'];
			$master = $userDao->find(array('USER_ID'=>$obj['tutorId']));
			$leader = $userDao->find(array('USER_ID'=>$obj['leaderId']));
			$masterName = $master['USER_NAME'];
			$leaderName = $leader['USER_NAME'];
			$masterDate = $obj['tutorDate'];
			$leaderDate = $obj['leaderDate'];

			$message2 = <<<EOT
				<tr>
					<td class="form_text_left_three">��ʦ���</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">����ʦ</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">��ʦȷ��ʱ��</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">ֱ���쵼���</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">ֱ���쵼</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">�쵼ȷ��ʱ��</td>
					<td class="form_text_right_three">
						$leaderDate
					</td>
				</tr>
EOT;
		} else {
			$message2 = '';
		}
		$this->assign("Commentinfo",$message2);
		$this->view('lastedit',true);
	}

	/**
	 * ��ת���鿴Ա��ת������������ҳ��
	 */
	function c_toView() {
		$this -> permCheck(); //��ȫУ��
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
	  	$this->assign ( 'actType', $actType );
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$message = '';
		if($obj['status']>=$this->service->statusDao->statusEtoK ( 'directorcheck' )){
			$directorComment = $obj['directorComment'];
			$permanent = $obj['permanentType'];
			if($permanent == "��ǰת��"){
				$permanent .= "   " .$obj['permanentDate'];
			}
			if($_SESSION['USER_ID'] == $obj['tutorId'] || $_SESSION['USER_ID'] == $obj['leaderId']) {
				$beforeSalary = '***';
				$afterSalary = '***';
			} else {
				$beforeSalary = $obj['beforeSalary'];
				$afterSalary = $obj['afterSalary'];
			}
			$afterPositionName = $obj['afterPositionName'];
			$levelName = $obj['levelCode'];
			$message = <<<EOT
				<table class="form_main_table">
					<tr>
						<td class="form_text_right_three">
							<h4>����:</h4>$directorComment
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three" id="ZZYJ">
							<h4>ת�����:</h4><font color=blue>$permanent</font>
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three">
							<h4>ת��������ְλȷ��:</h4>ת��ǰ����:<font color=blue>$beforeSalary</font> Ԫ/�£�ת������:<font color=blue>$afterSalary</font> Ԫ/��<br>
							ת����ְλ:<font color=blue>$afterPositionName</font>
							��ת����ְ��:<font color=blue>$levelName</font>
						</td>
					<tr>
				</table>
EOT;
		}
		if($obj['planstatus'] == 0 && $obj['summarystatus'] == 0) {
			$userDao = new model_deptuser_user_user();
			$mastercomment = $obj['masterComment'];
			$leadercomment = $obj['leaderComment'];
			$master = $userDao->find(array('USER_ID'=>$obj['tutorId']));
			$leader = $userDao->find(array('USER_ID'=>$obj['leaderId']));
			$masterName = $master['USER_NAME'];
			$leaderName = $leader['USER_NAME'];
			$masterDate = $obj['tutorDate'] == '0000-00-00'?'':$obj['tutorDate'];
			$leaderDate = $obj['leaderDate'] == '0000-00-00'?'':$obj['leaderDate'];

			$message2 = <<<EOT
				<tr>
					<td class="form_text_left_three">��ʦ���</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">����ʦ</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">��ʦȷ��ʱ��</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">ֱ���쵼���</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">ֱ���쵼</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">�쵼ȷ��ʱ��</td>
					<td class="form_text_right_three">
						$leaderDate
					</td>
				</tr>
EOT;
		} else {
			$message2 = '';
		}
		if($_GET['isDirector'] == 1) {
			$this->assign("isDirector" ,'1');
		} else {
			$this->assign("isDirector" ,'0');
		}
		$this->assign("directorMessage" ,$message);
		$this->assign("Commentinfo" ,$message2);
		if($obj['isAgree'] == 0) {
			$this->assign("isAgreeComment","δ��д");
		} else if($obj['isAgree'] == 1) {
			$this->assign("isAgreeComment","ͬ��");
		} else if($obj['isAgree'] == 2) {
			$this->assign("isAgreeComment","��ͬ��");
		}
		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0) {
			$this->view('view');
		} else if ($obj['planstatus'] == 1 && $obj['summarystatus'] == 1) {
			$this->view('viewhigh');
		}
	}

	/**
	 * ��ת���鿴Ա��ת������������ҳ�棨�쵼��
	 */
	function c_toLeaderView() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('leaderview');
	}

	/**
	 * ��ת���鿴Ա��ת������������ҳ�棨�ܼࣩ
	 */
	function c_toDirectorView() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('directorview');
	}

	/**
	 * ��ת���鿴Ա��ת������������ҳ��
	 */
	function c_toLastView() {
		$this -> permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('lastview');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this -> service;

		$service -> getParam($_REQUEST);
		$rows = $service -> page_d();
		//���ݼ��밲ȫ��
		$rows = $this -> sconfig -> md5Rows($rows);
		$deptDao = new model_deptuser_dept_dept();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//ת��������
				$rows[$key]['statusC'] = $service -> statusDao -> statusKtoC($rows[$key]['status']);
				$info = $deptDao->find(array("DEPT_ID"=>$rows[$key]['deptId']));
				$row[$key]['directorId'] = $info['Leader_id'];
			}
		}
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service -> count ? $service -> count : ($rows ? count($rows) : 0);
		$arr['page'] = $service -> page;
		$arr['advSql'] = $service -> advSql;
		echo util_jsonUtil::encode($arr);
	}

	/*
	 * �ύ����ʦ
	 */
	function c_giveMaster() {
		$info = $this->service->get_d($_POST['id']);
		$info['status'] = $this->service->statusDao -> statusEtoK('mastercheck');
		$addinfo = <<<EOT
			�뵽����·������:������--->���˰칫--->����|��ѯ--->������--->����ת��
EOT;
		$this->service->postInMail($info, $info['tutorId'], $addinfo);
		$id=$this->service->updateById($info);
		echo $id;
	}

	/*
	 * �ύ���쵼
	 */
	function c_giveLeader() {
		try {
			$info = $this->service->get_d($_POST['id']);
			$info['status'] = $this->service->statusDao -> statusEtoK('leadercheck');
			$username = $info['tutor'];
			$comment = $info['masterComment'];
			$addinfo = <<<EOT
				<br>��ʦ��<font color=blue>$username</font><br>
				��ʦ�����<font color=blue>$comment</font><br>
				�뵽����·������:������--->���˰칫--->����|��ѯ--->������--->����ת��
EOT;
			$flag=$this->service->postInMail($info, $info['leaderId'], $addinfo);
			$id=$this->service->updateById($info);
		} catch(exception $e) {
			echo $e;
		}
		echo $id;
	}

	/*
	 * �ύ��Hr
	 */
	function c_giveHr() {
		try {
			$info = $this->service->get_d($_POST['id']);
			$info['status'] = $this->service->statusDao -> statusEtoK('hrcheck');
			$username_tutor = $info['tutor'];
			$comment_tutor = $info['masterComment'];
			$username_leader = $info['leaderName'];
			$comment_leader = $info['leaderComment'];
			$addinfo = <<<EOT
				<br>��ʦ��<font color=blue>$username_tutor</font><br>
				��ʦ�����<font color=blue>$comment_tutor</font><br>
				<br>�쵼��<font color=blue>$username_leader</font><br>
				�쵼�����<font color=blue>$comment_leader</font><br>
EOT;
			include (WEB_TOR . "model/common/mailConfig.php");
			$mailUser = $mailUser['oa_permanent_examie']['TO_ID'];
			$flag = $this->service->postInMail($info, $mailUser, $addinfo);
			$id=$this->service->updateById($info);
		} catch(exception $e) {
			echo $e;
		}
		echo $id;
	}

	/*
	 * ���ʼ��������Ա
	 */
	function c_applyTothem() {
		$applyinfo = isset($_GET['rows']) ? $_GET['rows'] : null;
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		if(is_array($applyinfo)&&sizeof($applyinfo)>0&&$folowInfo['Result']=="ok"){
			$this->service->setApply($applyinfo); //�޸���Ϣ
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/*
	 * �ύ��Ա��
	 */
	function c_giveLast() {
		try {
			$info = $this->service->get_d($_POST['id']);
			$info['status'] = $this->service->statusDao -> statusEtoK('lastcheck');
			$this->service->updateById($info);
		} catch(exception $e) {
			echo $e;
		}
		echo 1;
	}

	/*
	 * ȷ���ύ
	 */
	function c_giveConfirm() {
		try {
			$info = $this->service->get_d($_POST['id']);
			$info['status'] = $this->service->statusDao -> statusEtoK('finish');
			$this->service->updateById($info);
		} catch(exception $e) {
			echo $e;
		}
		echo 1;
	}

	/*
	 * ȷ���ύ
	 */
	function c_giveClose() {
		try {
			$info = $this->service->get_d($_POST['id']);
			$info['status'] = $this->service->statusDao -> statusEtoK('close');
			$this->service->updateById($info);
		} catch(exception $e) {
			echo $e;
		}
		echo 1;
	}

	/**
	 * ��ȡ�Ƿ��Ѿ������
	 */
	function c_findData() {
		try {
			$info = $this->service->find(array("userAccount"=>$_POST['userAccount']));
			if(is_array($info))
					echo 1;
		} catch(exception $e) {
			echo $e;
		}
	}

	/**
	 * ��ȡ�Ƿ��Ѿ������
	 */
	function c_tutorExist() {
		try {
			$tutorDao=new model_hr_tutor_tutorrecords();
			$tutorRow=$tutorDao->getInfoByStuUserNo_d($_POST['userNo']);
			if($tutorRow['0']['userAccount']!=''&&$tutorRow['0']['userAccount']!='NULL')
				echo 1;
			else
				echo 0;
		} catch(exception $e) {
			echo $e;
		}
	}

	function c_toExport(){
	 	$this->view('exportview');
	 }

	function c_ExamineExport(){
	 	$object = $_POST[$this->objName];
		if(!empty($object['beginDate']))
		 	$this->service->searchArr['preDateHope'] = $object['beginDate'];
		if(!empty($object['endDate']))
		 	$this->service->searchArr['afterDateHope'] = $object['endDate'];
		if(!empty($object['beginTime']))
		 	$this->service->searchArr['begintimeCmp'] = $object['beginTime'];
		if(!empty($object['finishTime']))
			$this->service->searchArr['finishtimeCmp'] = $object['finishTime'];
		if(!empty($object['deptId']))
			$this->service->searchArr['deptNameSame'] = $object['deptId'];
		if(!empty($object['deptIdS']))
			$this->service->searchArr['deptNameSSame'] = $object['deptIdS'];
		if(!empty($object['deptIdT']))
			$this->service->searchArr['deptNameTSame'] = $object['deptIdT'];
		if(!empty($object['deptIdF']))
			$this->service->searchArr['deptIdF'] = $object['deptIdF'];

		set_time_limit(0); // ִ��ʱ��Ϊ������
		$examineRows = $this->service->listBySqlId('examine_export_list');

		$exportData = array();
		foreach ( $examineRows as $key => $val ){
			$exportData[$key]['id'] = $key + 1;
			$exportData[$key]['userNo'] = $examineRows[$key]['userNo'];
			$exportData[$key]['userName'] = $examineRows[$key]['useName'];
			$exportData[$key]['Company'] = $examineRows[$key]['companyName'];
			$exportData[$key]['deptName'] = $examineRows[$key]['deptName'];
			$exportData[$key]['deptNameS'] = $examineRows[$key]['deptNameS'];
			$exportData[$key]['deptNameT'] = $examineRows[$key]['deptNameT'];
			$exportData[$key]['deptNameF'] = $examineRows[$key]['deptNameF'];
			$exportData[$key]['positionName'] = $examineRows[$key]['positionName'];
			$exportData[$key]['begintime'] = $examineRows[$key]['begintime'];
			$exportData[$key]['finishtime'] = $examineRows[$key]['finishtime'];
			$exportData[$key]['permanentDate'] = $examineRows[$key]['permanentDate'];
			$exportData[$key]['beforeSalary'] = $examineRows[$key]['beforeSalary'];
			$exportData[$key]['afterSalary'] = $examineRows[$key]['afterSalary'];
		}
		return model_hr_permanent_examineExportUtil::export2ExcelUtil ( $exportData );
	}

	/**
	 * �ʼ�����
	 */
	function c_toMailMsg(){
		$this->assignFunc($_GET); //��ȾһЩ��Ϣ
		$this->view('mailmsg',true);
	}

	/**
	 * �ʼ�����
	 */
	function c_mailMsg(){
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$rs = $this->service->mailMsg_d($object);
		if($rs){
			msg('�ʼ��ѷ���');
		}
	}

	//ȷ�ϲ鿴ҳ��
	function c_affirmCheck($isEditInfo = false) {
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if($object['ok']==" ȷ �� "){
			$object['isAgree']=1;
		}
		if ($this->service->affirmCheck_d ( $object, true )) {
			msg ( 'ȷ�ϳɹ���' );
		}
	}

	//ֱ���ύ����
	function c_edit(){
		$this->checkSubmit(); //��֤�Ƿ��ظ��ύ
		$obj = $_POST[$this->objName];
		if ($obj['submitInfo'] == 1) {
			$id = $this->service->edit_d($obj);
		}else {
			$id = $this->service->edit_d($obj ,true);
		}
		$persDao = new model_hr_personnel_personnel();
		if ($obj['mainId']!=""){
			$persDao->update(array("id"=>$obj['mainId']),array("jobLevel"=>$obj['suggestLevelName']));
		}
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		if($id){
			if ("audit" == $actType) {//�ύ������
				succ_show ( 'controller/hr/permanent/ewf_hr_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&examCode=oa_hr_permanent_examine&formName=���ÿ�������&billDept='.$_POST[$this->objName]['deptId']);
			} else {
				if ($obj['submitInfo'] == 1) {
					msg('�ύ�ɹ�');
				}else {
					msg('����ɹ�');
				}
			}
		} else {
			if ($obj['submitInfo'] == 1) {
				msg('�ύʧ��');
			}else {
				msg('����ʧ��');
			}
		}
	}

	/**
	 * ����ҳ����Ϣ
	 */
	function c_excelOut(){
		set_time_limit(0);
		$service = $this->service;
		if(!empty($_GET['status']))
			$service->searchArr['status'] = $_GET['status'];
		if(!empty($_GET['ExaStatus']))
			$service->searchArr['ExaStatus'] = $_GET['ExaStatus'];
		$examineRows = $this->service->listBySqlId('hrsql');
		$exportData = array();
		foreach ( $examineRows as $key => $val ){
			$exportData[$key]['userNo'] = $examineRows[$key]['userNo'];
			$exportData[$key]['userName'] = $examineRows[$key]['useName'];
			$exportData[$key]['permanentMonth'] = $examineRows[$key]['permanentMonth'];
			$exportData[$key]['statusC'] = $service -> statusDao -> statusKtoC($examineRows[$key]['status']);
			$exportData[$key]['formCode'] = $examineRows[$key]['formCode'];
			$exportData[$key]['formDate'] = $examineRows[$key]['formDate'];
			$exportData[$key]['ExaStatus'] = $examineRows[$key]['ExaStatus'];
			switch ($examineRows[$key]['isAgree']) {
				case 0 : $exportData[$key]['isAgree'] = 'δ��д';break;
				case 1 : $exportData[$key]['isAgree'] = 'ͬ��';break;
				case 2 : $exportData[$key]['isAgree'] = '��ͬ��';break;
				default : $exportData[$key]['isAgree'] = '';
			};
			$exportData[$key]['permanentType'] = $examineRows[$key]['permanentType'];
			$exportData[$key]['sex'] = $examineRows[$key]['sex'];
			$exportData[$key]['deptName'] = $examineRows[$key]['deptName'];
			$exportData[$key]['positionName'] = $examineRows[$key]['positionName'];
			$exportData[$key]['begintime'] = $examineRows[$key]['begintime'];
			$exportData[$key]['finishtime'] = $examineRows[$key]['finishtime'];
			$exportData[$key]['permanentDate'] = $examineRows[$key]['permanentDate'];
			$exportData[$key]['selfScore'] = $examineRows[$key]['selfScore'];
			$exportData[$key]['totalScore'] = $examineRows[$key]['totalScore'];
			$exportData[$key]['leaderScore'] = $examineRows[$key]['leaderScore'];
			$exportData[$key]['interviewSalary'] = $examineRows[$key]['interviewSalary'];
			$exportData[$key]['suggestSalary'] = $examineRows[$key]['suggestSalary'];
			$exportData[$key]['suggestJobName'] = $examineRows[$key]['suggestJobName'];
		}
		return model_hr_permanent_examineExportUtil::exportExcelUtil ( $exportData );
	}
}
?>