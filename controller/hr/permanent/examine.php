<?php
/**
 * @author Administrator
 * @Date 2012年8月6日 21:39:45
 * @version 1.0
 * @description:员工转正考核评估表控制层
 */
class controller_hr_permanent_examine extends controller_base_action {

	function __construct() {
		$this->objName = "examine";
		$this->objPath = "hr_permanent";
		parent::__construct();
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$this->checkSubmit(); //验证是否重复提交
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '提交成功！';
		if ($id) {
			msg ($msg);
		}
	}

	/*
	 * 跳转到员工转正考核评估表列表（员工列表）
	 */
	function c_page() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('list');
	}

	/*
	 * 跳转到员工转正考核评估表列表(导师列表)
	 */
	function c_masterPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('masterlist');
	}

	/*
	 * 跳转到员工转正考核评估表列表(领导列表)
	 */
	function c_leaderPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('leaderlist');
	}

	/*
	 * 跳转到员工转正考核评估表列表(总监列表)
	 */
	function c_directorPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('directorlist');
	}

	/*
	 * 跳转到员工转正考核评估表列表(总监列表)
	 */
	function c_hrPage() {
		$this->view('hrlist');
	}

	/**
	 * 对应json
	 */
	function c_hrJson(){
		$service = $this->service;

		$service->getParam ( $_REQUEST );
		$rows = $service->page_d ('hrsql');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		$deptDao = new model_deptuser_dept_dept();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//转换成中文
				$rows[$key]['statusC'] = $service -> statusDao -> statusKtoC($rows[$key]['status']);
				$info = $deptDao->find(array("DEPT_ID"=>$rows[$key]['deptId']));
				$row[$key]['directorId'] = $info['Leader_id'];
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 跳转到员工转正考核评估表列表(整合列表)
	 */
	function c_allPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('alllist');
	}

	/**
	 * 跳转到员工转正考核评估表列表(整合列表)
	 */
	function c_nopassPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('status');
	}

	/**
	 * 跳转到员工转正考核评估表列表(整合列表)
	 */
	function c_passPage() {
		$this->assign("userid", $_SESSION['USER_ID']);
		$this->view('passed');
	}

	/**
	 * 跳转到员工转正考核评估表列表(整合列表)
	 */
	function c_tabPage() {
		$this->view('tabpage');
	}

	/**
	 * 跳转到新增员工转正考核评估表页面
	 */
	function c_toAdd() {
		$personnel = new model_hr_personnel_personnel();
		$getinfo = $personnel->find(array('userAccount'=>$_SESSION['USER_ID']));
		if(is_array($getinfo)) {//判断是否有档案信息
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
		//获取合同信息
		$contDao = new model_hr_contract_contract();
		$conInfo = $contDao->getConInfoByUserId($_SESSION['USER_ID'],'HRHTLX-05,HRHTLX-01,HRHTLX-06');
		if( is_array($conInfo)){
			$this->assign('begintime', $conInfo['0']['trialBeginDate']);
			$this->assign('finishtime', $conInfo['0']['trialEndDate']);
		}else{
			$this->assign('begintime', $getinfo['entryDate']);
			$this->assign('finishtime', $getinfo['becomeDate']);
		}

		//获取导师信息
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
	 * 跳转到编辑员工转正考核评估表页面
	 */
	function c_toEdit() {
		$this -> permCheck(); //安全校验
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
	 * 跳转到编辑员工转正考核评估表页面
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
	 * 跳转到编辑员工转正考核评估表页面（导师列表）
	 */
	function c_toMasterEdit() {
		$this -> permCheck(); //安全校验
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
	 * 跳转到编辑员工转正考核评估表页面（领导列表）
	 */
	function c_toLeaderEdit() {
		$this -> permCheck(); //安全校验
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
	 * 跳转到建议薪点页面
	 */
	function c_toEditSalary(){
		$this -> permCheck(); //安全校验
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
	 * 跳转到编辑员工转正考核评估表页面（总监列表）
	 */
	function c_toDirectorEdit() {
		$this -> permCheck(); //安全校验
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
					<td class="form_text_left_three">导师意见</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">负责导师</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">导师确认时间</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">直接领导意见</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">直接领导</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">领导确认时间</td>
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
	 * 跳转到修正员工转正考核评估表页面（总监列表）
	 */
	function c_toDirectorSet() {
		$this -> permCheck(); //安全校验
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
					<td class="form_text_left_three">导师意见</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">负责导师</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">导师确认时间</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">直接领导意见</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">直接领导</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">领导确认时间</td>
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
	 * 跳转到编辑员工转正考核评估表页面（员工意见列表）
	 */
	function c_toLastEdit() {
		$this -> permCheck();
		//安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$message = '';
		if($obj['status']>=$this->service->statusDao->statusEtoK ( 'hrcheck' )){
			$directorComment = $obj['directorComment'];
			$permanent = $obj['permanentType'];
			if($permanent=="提前转正"){
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
							<h4>评语:</h4>$directorComment
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three" id="ZZYJ">
							<h4>转正意见:</h4><font color=blue>$permanent</font>
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three">
							<h4>转正后工资与职位确认:</h4>
							转正前工资:<font color=blue>$beforeSalary</font> 元/月，转正后工资:<font color=blue>$afterSalary</font> 元/月<br>
							转正后职位:<font color=blue>$afterPositionName</font>
							，转正后职级:<font color=blue>$levelName</font>
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
					<td class="form_text_left_three">导师意见</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">负责导师</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">导师确认时间</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">直接领导意见</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">直接领导</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">领导确认时间</td>
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
	 * 跳转到查看员工转正考核评估表页面
	 */
	function c_toView() {
		$this -> permCheck(); //安全校验
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
			if($permanent == "提前转正"){
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
							<h4>评语:</h4>$directorComment
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three" id="ZZYJ">
							<h4>转正意见:</h4><font color=blue>$permanent</font>
						</td>
					</tr>
					<tr>
						<td class="form_text_right_three">
							<h4>转正后工资与职位确认:</h4>转正前工资:<font color=blue>$beforeSalary</font> 元/月，转正后工资:<font color=blue>$afterSalary</font> 元/月<br>
							转正后职位:<font color=blue>$afterPositionName</font>
							，转正后职级:<font color=blue>$levelName</font>
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
					<td class="form_text_left_three">导师意见</td>
					<td class="form_text_right_three">
						$mastercomment
					</td>
					<td class="form_text_left_three">负责导师</td>
					<td class="form_text_right_three">
						$masterName
					</td>
					<td class="form_text_left_three">导师确认时间</td>
					<td class="form_text_right_three">
						$masterDate
					</td>
				</tr>
				<tr>
					<td class="form_text_left_three">直接领导意见</td>
					<td class="form_text_right_three">
						$leadercomment
					</td>
					<td class="form_text_left_three">直接领导</td>
					<td class="form_text_right_three">
						$leaderName
					</td>
					<td class="form_text_left_three">领导确认时间</td>
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
			$this->assign("isAgreeComment","未填写");
		} else if($obj['isAgree'] == 1) {
			$this->assign("isAgreeComment","同意");
		} else if($obj['isAgree'] == 2) {
			$this->assign("isAgreeComment","不同意");
		}
		if ($obj['planstatus'] == 0 && $obj['summarystatus'] == 0) {
			$this->view('view');
		} else if ($obj['planstatus'] == 1 && $obj['summarystatus'] == 1) {
			$this->view('viewhigh');
		}
	}

	/**
	 * 跳转到查看员工转正考核评估表页面（领导）
	 */
	function c_toLeaderView() {
		$this -> permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('leaderview');
	}

	/**
	 * 跳转到查看员工转正考核评估表页面（总监）
	 */
	function c_toDirectorView() {
		$this -> permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('directorview');
	}

	/**
	 * 跳转到查看员工转正考核评估表页面
	 */
	function c_toLastView() {
		$this -> permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('lastview');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this -> service;

		$service -> getParam($_REQUEST);
		$rows = $service -> page_d();
		//数据加入安全码
		$rows = $this -> sconfig -> md5Rows($rows);
		$deptDao = new model_deptuser_dept_dept();
		if (is_array($rows)) {
			foreach ($rows as $key => $val) {
				//转换成中文
				$rows[$key]['statusC'] = $service -> statusDao -> statusKtoC($rows[$key]['status']);
				$info = $deptDao->find(array("DEPT_ID"=>$rows[$key]['deptId']));
				$row[$key]['directorId'] = $info['Leader_id'];
			}
		}
		$arr = array();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service -> count ? $service -> count : ($rows ? count($rows) : 0);
		$arr['page'] = $service -> page;
		$arr['advSql'] = $service -> advSql;
		echo util_jsonUtil::encode($arr);
	}

	/*
	 * 提交给导师
	 */
	function c_giveMaster() {
		$info = $this->service->get_d($_POST['id']);
		$info['status'] = $this->service->statusDao -> statusEtoK('mastercheck');
		$addinfo = <<<EOT
			请到以下路径处理:导航栏--->个人办公--->申请|查询--->人事类--->试用转正
EOT;
		$this->service->postInMail($info, $info['tutorId'], $addinfo);
		$id=$this->service->updateById($info);
		echo $id;
	}

	/*
	 * 提交给领导
	 */
	function c_giveLeader() {
		try {
			$info = $this->service->get_d($_POST['id']);
			$info['status'] = $this->service->statusDao -> statusEtoK('leadercheck');
			$username = $info['tutor'];
			$comment = $info['masterComment'];
			$addinfo = <<<EOT
				<br>导师：<font color=blue>$username</font><br>
				导师意见：<font color=blue>$comment</font><br>
				请到以下路径处理:导航栏--->个人办公--->申请|查询--->人事类--->试用转正
EOT;
			$flag=$this->service->postInMail($info, $info['leaderId'], $addinfo);
			$id=$this->service->updateById($info);
		} catch(exception $e) {
			echo $e;
		}
		echo $id;
	}

	/*
	 * 提交给Hr
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
				<br>导师：<font color=blue>$username_tutor</font><br>
				导师意见：<font color=blue>$comment_tutor</font><br>
				<br>领导：<font color=blue>$username_leader</font><br>
				领导意见：<font color=blue>$comment_leader</font><br>
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
	 * 发邮件给相关人员
	 */
	function c_applyTothem() {
		$applyinfo = isset($_GET['rows']) ? $_GET['rows'] : null;
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
		if(is_array($applyinfo)&&sizeof($applyinfo)>0&&$folowInfo['Result']=="ok"){
			$this->service->setApply($applyinfo); //修改信息
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/*
	 * 提交给员工
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
	 * 确认提交
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
	 * 确认提交
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
	 * 获取是否已经申请过
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
	 * 获取是否已经申请过
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

		set_time_limit(0); // 执行时间为无限制
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
	 * 邮件发送
	 */
	function c_toMailMsg(){
		$this->assignFunc($_GET); //渲染一些信息
		$this->view('mailmsg',true);
	}

	/**
	 * 邮件发送
	 */
	function c_mailMsg(){
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		$rs = $this->service->mailMsg_d($object);
		if($rs){
			msg('邮件已发送');
		}
	}

	//确认查看页面
	function c_affirmCheck($isEditInfo = false) {
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if($object['ok']==" 确 认 "){
			$object['isAgree']=1;
		}
		if ($this->service->affirmCheck_d ( $object, true )) {
			msg ( '确认成功！' );
		}
	}

	//直接提交审批
	function c_edit(){
		$this->checkSubmit(); //验证是否重复提交
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
			if ("audit" == $actType) {//提交工作流
				succ_show ( 'controller/hr/permanent/ewf_hr_index2.php?actTo=ewfSelect&billId='.$_POST[$this->objName]['id'].'&examCode=oa_hr_permanent_examine&formName=试用考核评估&billDept='.$_POST[$this->objName]['deptId']);
			} else {
				if ($obj['submitInfo'] == 1) {
					msg('提交成功');
				}else {
					msg('保存成功');
				}
			}
		} else {
			if ($obj['submitInfo'] == 1) {
				msg('提交失败');
			}else {
				msg('保存失败');
			}
		}
	}

	/**
	 * 导出页面信息
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
				case 0 : $exportData[$key]['isAgree'] = '未填写';break;
				case 1 : $exportData[$key]['isAgree'] = '同意';break;
				case 2 : $exportData[$key]['isAgree'] = '不同意';break;
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