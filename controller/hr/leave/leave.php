<?php
/**
 * @author Administrator
 * @Date 2012-08-07 09:38:05
 * @version 1.0
 * @description:离职管理控制层
 */
class controller_hr_leave_leave extends controller_base_action {

	function __construct() {
		$this->objName = "leave";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * 跳转到离职管理列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增离职管理页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 离职个人列表
	 */
	function c_proList(){
		$this->assign("userId" ,$_SESSION['USER_ID']);
		$this->view ( 'prolist' );
	}

	/**
	 * 跳转到编辑离职管理页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);
		$this->assign('parentDeptId',$arr['deptId']);
		$this->assign('thisDate',date("Y-m-d"));
		$this->view ('edit' ,true);
	}

	/**
	 * 跳转到离职类型确认页面
	 */
	function c_toEditType() {
		$obj = $this->service->get_d ( $_GET ['id'] );

		//提取离职原因，重新组合
		$str = substr($obj['quitReson'] ,-5);
		if ($str == "^nbsp") { //没有包含其他原因
			$obj['quitReson'] = str_replace('^nbsp' ,"； " ,$obj['quitReson']);
		} else {
			$quitReson = '';
			$arr = explode("^nbsp" ,$obj['quitReson']);
			for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //不处理其他原因
				$quitReson .= $arr[$i]."；";
			}
			$obj['quitReson'] = $quitReson.$arr[$i]."：".$arr[$i + 1];
		}

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$this->showDatadicts ( array ('quitTypeCode' => 'YGZTLZ' ), $obj ['quitTypeCode'] );//HRLZLX
		$this->view ('edit-type' ,true);
	}

	/**
	 * 跳转到离职证明打印页面
	 */
	function c_toLeaveProof() {
		$obj = $this->service->getLeaveUserInfo ( $_GET ['id'] );
		$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);

		$entryYear='-';
		$entryMon='-';
		$entryDay='-';
		$leaveYear='-';
		$leaveMon='-';
		$leaveDay='-';
		$contractYear='-';
		$contractMon='-';
		$contractDay='-';
		$contractEndYear='-';
		$contractEndMon='-';
		$contractEndDay='-';
		$leaveYears='--年';
		$userName=$obj['userName'];
		$identityCard= $obj['identityCard'];
		if($arr['beginDate']!='0000-00-00'&&$arr['beginDate']!=''){
			$contractYear= date("Y",strtotime($arr['beginDate']));
			$contractMon=date("m",strtotime($arr['beginDate']));
			$contractDay= date("d",strtotime($arr['beginDate']));
		}
		if($arr['closeDate']!='0000-00-00'&&$arr['closeDate']!=''){
			$contractEndYear= date("Y",strtotime($arr['closeDate']));
			$contractEndMon=date("m",strtotime($arr['closeDate']));
			$contractEndDay= date("d",strtotime($arr['closeDate']));
		}
		//入职时间
		if($obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
			$entryYear= date("Y",strtotime($obj['entryDate']));
			$entryMon=date("m",strtotime($obj['entryDate']));
			$entryDay= date("d",strtotime($obj['entryDate']));
		}
		//离职时间
		if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''){
			$leaveYear=date("Y",strtotime($obj['comfirmQuitDate']));
			$leaveMon=date("m",strtotime($obj['comfirmQuitDate']));
			$leaveDay=date("d",strtotime($obj['comfirmQuitDate']));
		}
		$leaveTypeS=array('YGZTJC','YGZTWJJC','YGZTCZ','YGZTCT');
		if (in_array($obj['quitTypeCode'] ,$leaveTypeS)) {
			$leaveType = '解除';
			$leaveRemark = '自双方解除劳动合同关系之日起';
		} else {
			$leaveType = '终止';
			$leaveRemark = '自双方终止劳动合同关系之日起';
		}
		if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''&&$obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
			$leaveYears = $obj['leaveYears'] * 12;
			$allMonth = (int)($leaveYears + $obj['leaveMonth']);
			if($obj['leaveDay'] > 15) {		//天数大于15天的算一个月
				$allMonth = $allMonth + 1;
			}else if($obj['leaveDay'] < -15){		//小于-15的剪掉一个月
				$allMonth = $allMonth - 1;
			}
			$remainYear = floor($allMonth / 12);	//算工作了多少年
			if($remainYear == 0) {
				if($allMonth == 0) {
					$leaveYears = $obj['leaveDay'].'天';		//工作不足一个月的算天数
				}else{
					$leaveYears = $allMonth.'个月';
				}
			}else if($remainYear>0){
				$month = $allMonth%12;
				if($month!=0){
					$leaveYears=$remainYear.'年'.$month.'个月';
				}else{
					$leaveYears=$remainYear.'年';
				}
			}
		}
		$leaveContent="兹有我公司员工".$userName."（身份证号：".$identityCard."），最新劳动合同起止期限为".$contractYear."年".$contractMon."月".$contractDay."日至".$contractEndYear."年".$contractEndMon."月".$contractEndDay."日止。并于".$entryYear."年".$entryMon."月".$entryDay."日至".$leaveYear."年".$leaveMon."月".$leaveDay."日在我公司工作，离职时职务为$obj[jobName]，本司工作年限为".$leaveYears."，现已于".$leaveYear."年".$leaveMon."月".$leaveDay."日因个人原因与我公司".$leaveType."劳动合同关系，并已办理完毕各项工作交接和离职手续。";
		if($obj['NamePT']=='dl'){
			 $companyName="珠海世纪鼎利科技股份有限公司";
			 $photoName='sjdl';
		}elseif($obj['NamePT']=='br'){
			$companyName="广州市贝软电子科技有限公司";
			 $photoName='gzbr';
		}elseif($obj['NamePT']=='sy'){
			$companyName="北京世源信通科技有限公司";
			 $photoName='bjsy';
		}elseif($obj['NamePT']=='bx'){
			$companyName="广州市贝讯电子科技有限公司";
			 $photoName='sjdl';
		}else{
			$companyName=$obj['NameCN']?$obj['NameCN']:'无';
			 $photoName='sjdl';
		}

		$this->assign ( 'leaveContent', $leaveContent );
		$this->assign ( 'companyName', $companyName );
		$this->assign ( 'photoName', $photoName );
		$this->assign ( 'leaveRemark', $leaveRemark );
		//打印时间
		$this->assign ( 'todayYear', date("Y") );
		$this->assign ( 'todayMon', date("m") );
		$this->assign ( 'todayDay', date("d") );
        $this->view ( 'proof');
   }

	/**
	 * 查看页面Tab页
	 */
	function c_toViewTab() {
		$this->assign("id",$_GET['id']);
		$row=$this->service->get_d($_GET['id']);
		$this->assign("userAccount",$row['userAccount']);
		$this->view ( 'view-tab');
	}

	/**
	 * 跳转到查看离职管理页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$actType = isset ( $_GET ['actType'] ) ? $_GET ['actType'] : null;
		$this->assign ( 'actType', $actType );
		$obj = $this->service->get_d ( $_GET ['id'] );
		//提取离职原因，重新组合
		$str = substr($obj['quitReson'] ,-5);
		if ($str == "^nbsp") { //没有包含其他原因
			$obj['quitReson'] = str_replace('^nbsp' ,"； " ,$obj['quitReson']);
		} else {
			$quitReson = '';
			$arr = explode("^nbsp" ,$obj['quitReson']);
			for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //不处理其他原因
				$quitReson .= $arr[$i]."；";
			}
			$obj['quitReson'] = $quitReson.$arr[$i]."：".$arr[$i + 1];
		}

		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
//		去掉只能在第一步审批时修改的限制
// 		$otherdatasDao = new model_common_otherdatas();
// 		$flag=$otherdatasDao->isFirstStep($_GET['id'] ,$this->service->tbl_name);
// 		if($actType && $flag){
		if($actType){
			$comfirmQuitDate = $obj['comfirmQuitDate'];
			$salaryEndDate = $obj['salaryEndDate'];
			if($comfirmQuitDate == '0000-00-00' || empty($comfirmQuitDate) || $salaryEndDate == '0000-00-00' || empty($salaryEndDate)){
				$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);
				switch($obj['quitTypeCode']){
					case "YGZTHTYGBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//合同到期员工不续
					case "YGZTHTGSBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//合同到期公司不续
					case "YGZTCT":$comfirmQuitDate=$arr['becomeDate'];$salaryEndDate=$arr['becomeDate'];break;//试用期辞退
					case "YGZTJC":$comfirmQuitDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));$salaryEndDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));break;//协商解除
					case "YGZTCZ":$comfirmQuitDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));$salaryEndDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));break;//辞职
					case "YGZTTXGSBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//退休公司不续
					case "YGZTYGBX":$comfirmQuitDate=$arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;//退休员工不续
					case "YGZTWJJC":$comfirmQuitDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));$salaryEndDate=date("Y-m-d", strtotime("-1 days",strtotime("+1 months", strtotime(date("Y-m-d")))));break;//违纪解除
					default :$comfirmQuitDate = $arr['closeDate'];$salaryEndDate=$arr['closeDate'];break;
				}
			}
			$this->assign ( 'comfirmQuitDate', $comfirmQuitDate );
			$this->assign ( 'salaryEndDate', $salaryEndDate );
			$this->view ( 'editview' );
		} else {
			$this->view ( 'view' );
		}
   }

	/**
	 * 跳转到离职进度备注页面
	 */
	function c_toEditRemark() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ('edit-remark' ,true);
	}

	/**
	 * 跳转到HR修改离职信息页面
	 */
	function c_toEditLeaveInfo() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('hr-edit' ,true);
	}

	/**
	 * 跳转到HR导入excel修改离职信息页面
	 */
	function c_toEditLeaveInfoExcel() {
		$this->permCheck (); //安全校验
		$this->view('hr-editexcel');
	}

	/**
	 * 跳转到发送邮件页面
	 */
	function c_toSendEmail(){
		$this->permCheck (); //安全校验

		$leaveDao = new model_hr_leave_leave();
		$obj = $leaveDao->get_d ( $_GET ['leaveId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('mail' ,true);
	}

	/**
	 * 跳转到离职指引发送邮件页面
	 */
	function c_toSendEmailguide(){
		$this->permCheck (); //安全校验

		$leaveDao = new model_hr_leave_leave();
		$obj = $leaveDao->get_d ( $_GET ['leaveId'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('mailguide' ,true);
	}

	/**
	 * 调整到批量打印确认页面
	 */
	function c_toConfirmation(){
		$this->permCheck (); //安全校验
		$idStr = $_GET ['idStr'];
		$checked = $this->service->getChecked_d($idStr);
		$this->assign ( 'checked', $checked );
		if($_GET ['type'] == 'prove') {
			$this->view('confirmation');
		} else {
			$this->view('transfer');
		}
	}

	/**
	 * 给指定收件人发送邮件
	 */
	function c_sendEmail(){
		$this->checkSubmit();
		if($this->service->sendEmail($_POST['mail'])){
			msg('发送成功!');
		}
	}

	/**
	 * 给指定收件人发送邮件-离职指引
	 */
	function c_sendEmailguide(){
		$this->checkSubmit();
		$uploadFile = new model_file_uploadfile_management ();
		$file = $uploadFile->getFilesByObjId ( $_POST['mail']['id'], 'oa_hr_leave_email' );
		$obj = $_POST['mail'];
		if ($file) {
			foreach ($file as $key => $val) {
				$obj['attachment'][$val['uploadPath'].$val['newName']] = $val['originalName'];
			}
		}
		if($this->service->sendEmailguide($obj)){
			msg('发送成功!');
		}
	}

	/**
	 * 确认修改
	 */
	function c_confirmEdit($isEditInfo = true){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 修改员工离职信息
	 */
	function c_leaveInfoEdit($isEditInfo = true){
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		$oldObj = $this->service->get_d($object['id']);
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			if($oldObj['comfirmQuitDate']!=$object['comfirmQuitDate']){
				// 修改交接清单离职日期
				$handoverDao=new model_hr_leave_handover();
				$handoverDao->updateField('leaveId='.$object['id'],'quitDate',$object['comfirmQuitDate']);
				$obj=$this->service->get_d($object['id']);
				//重新发送邮件通知各部门
				$this->service->sendMailToFinan($obj);
				//重新通知各交接人
				$handoverDao->mailByLeaveId($object['id']);
			}
			msg ( '保存成功！' );
		}
	}

	/*
	 * 批量修改员工离职信息
	 */
	function c_editLeaveInfoExcel() {
		set_time_limit(0);
		$resultArr = $this->service->editLeaveInfoExcel_d ();

		$title = '修改离职信息导入结果列表';
		$thead = array( '数据信息','导入结果' );
		echo util_excelUtil::showResult($resultArr,$title,$thead);
	}

	/**
	 * ajax获取人事信息
	 */
	function c_getPersonnelInfo(){
		$userAccount = $_POST['userAccount'];
		$rows = $this->service->getPersonnelInfo_d($userAccount);
		$rows = util_jsonUtil :: encode($rows);
		echo $rows;
	}

	/**
	 * ajax验证是否已存在离职清单
	 */
	function c_getLeaveInfo(){
		$userAccount = $_POST['userAccount'];
		$falg = $this->service->getLeaveInfo_d($userAccount);
		echo $falg;
	}

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];
		//拼装离职原因
		$rows['quitReson'] = '';
		if(is_array($rows['checkbox'])) {
			foreach($rows['checkbox'] as $key => $val){
				$rows['quitReson'] = $rows['quitReson'].$val;
			}
			$rows['quitReson'] = $rows['quitReson'].$rows['comOther'];
		}

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		$rows['state']=2;
		$rows['leaveApplyDate'] = date('Y-m-d',time());
		$id = $this->service->add_d ($rows , $isAddInfo );
		if($id){
			if($actType==''){
				msg ( '保存成功！' );
			}else if($rows['quitTypeCode']=='YGZTCZ'){
				$row=$this->service->get_d($id);
				$arr = $this->service->getPersonnelInfo_d($row['userAccount']);
				switch($row['wageLevelCode']){
					case "GZJBFGL":$auditType='5';break;//非管理层
					case "GZJBJL":$auditType='15';break;//经理
					case "GZJBZG":$auditType='25';break;//主管
					case "GZJBZJ":$auditType='35';break;//总监
					case "GZJBFZ":$auditType='45';break;//副总
					case "GZJBZJL":$auditType='75';break;//总经理
				}
				succ_show('controller/hr/leave/ewf_index1.php?actTo=ewfSelect&billId=' . $row['id'].'&billDept='.$row['deptId'].'&flowMoney='.$auditType.'&proSid='.$row['projectManagerId'].'&eUserId='.$row['userAccount']);
			} else {
				msg ( '保存成功！' );
			}
		} else {
			msg ( '保存失败！' );
		}
	}

   	/**
	 * 新增对象操作
	 */
	function c_staffAdd() {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];
		//拼装离职原因
		$rows['quitReson'] = '';
		if(is_array($rows['checkbox'])) {
			foreach($rows['checkbox'] as $key => $val){
				$rows['quitReson'] = $rows['quitReson'].$val;
			}
			$rows['quitReson'] = $rows['quitReson'].$rows['comOther'];
		}

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'staff') {
			$rows['state'] = 1;
			$rows['leaveApplyDate'] = date('Y-m-d' ,time());
		}
		$id = $this->service->add_d ($rows);
		if($id) {
			if($actType != '') {
				//发送邮件通知HR
				$this->service->mailForSubmit($id);
				msgGo ( '提交成功！' ,'?model=hr_leave_leave&action=toAttention');
			} else {
				msg ( '保存成功！' );
			}
		} else {
			if($actType != '') {
				msg ( '提交失败！' );
			} else {
				msg ( '保存失败！' );
			}
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_staffEdit() {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];
		//拼装离职原因
		$rows['quitReson'] = '';
		if(is_array($rows['checkbox'])) {
			foreach($rows['checkbox'] as $key => $val) {
				$rows['quitReson'] = $rows['quitReson'].$val;
			}
			$rows['quitReson'] = $rows['quitReson'].$rows['comOther'];
		}

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : null;
		if($actType == 'staff') {
			$rows['state'] = 1;
			$rows['leaveApplyDate'] = date('Y-m-d' ,time());
		}
		$id = $this->service->staffEdit_d ($rows);
		if($id) {
			if($actType != '') {
				//发送邮件通知HR
				$this->service->mailForSubmit($rows['id']);
				msg ( '提交成功！' );
			} else {
				msg ( '保存成功！' );
			}
		} else {
			if($actType != '') {
				msg ( '提交失败！' );
			} else {
				msg ( '保存失败！' );
			}
		}
	}

	/**
	 * 确认离职类型
	 */
	function c_editType() {
		$this->checkSubmit();
		$rows = $_POST [$this->objName];

		$actType = isset ($_GET['actType']) ? $_GET['actType'] : '';
		$id = $this->service->editType_d ($rows);
		if($id){
			if($actType==''){
				msg ( '确认成功！' );
			}else if($rows['quitTypeCode']=='YGZTCZ'){
				$row=$this->service->get_d($rows['id']);
				$arr = $this->service->getPersonnelInfo_d($row['userAccount']);
				switch($row['wageLevelCode']){
					case "GZJBFGL":$auditType='5';break;//非管理层
					case "GZJBJL":$auditType='15';break;//经理
					case "GZJBZG":$auditType='25';break;//主管
					case "GZJBZJ":$auditType='35';break;//总监
					case "GZJBFZ":$auditType='45';break;//副总
					case "GZJBZJL":$auditType='75';break;//总经理
				}
				succ_show('controller/hr/leave/ewf_index1.php?actTo=ewfSelect&billId=' . $rows['id'].'&billDept='.$rows['deptId'].'&flowMoney='.$auditType.'&proSid='.$row['projectManagerId'].'&eUserId='.$row['userAccount']);
			} else {
				msg ( '确认成功！' );
			}
		} else {
			msg ( '确认失败！' );
		}
	}

	/**
	 * 离职申请（员工）
	 */
	function c_staffToAdd(){
		msg ( '您好，新OA已上线，请到新OA提交申请。谢谢！' );
		$userId = $_SESSION['USER_ID'];
		$arr = $this->service->getPersonnelInfo_d($userId);
		$this->assign('parentDeptId',$arr['deptId']);
		$arr['userName'] = $arr['staffName'];
		$arr['userAccount'] = $_SESSION['USER_ID'];
		$arr['deptName'] = $arr['belongDeptName'];
		$arr['deptId'] = $arr['belongDeptId'];
		$this->assign('jobName','');
		$this->assign('beginDate','');
		$this->assign('closeDate','');
		$this->assign('entryDate','');
		$this->assign('wageLevelName','');
		foreach ( $arr as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->assign('thisDate',date("Y-m-d"));
		$this->view("stafftoadd" ,true);
	}

	/**
	 * 离职审批后发送邮件
	 */
	function c_leaveMail(){
		$rows=isset($_GET['rows'])?$_GET['rows']:null;
		if (! empty ( $_GET ['spid'] )) {
			$otherdatas = new model_common_otherdatas ();
			$folowInfo = $otherdatas->getWorkflowInfo ( $_GET ['spid'] );
			if($folowInfo['Result']=="ok"&&is_array($rows)){
				$rows['handitem']=array();
				$handContentArr=explode('|',$rows['handContent']);
				$recipientNameArr=explode(',',$rows['recipientName']);
				$recipientIdArr=explode(',',$rows['recipientId']);
				foreach($handContentArr as $key=>$val){
					$rows['handitem'][$key]['handContent']=$val;
					$rows['handitem'][$key]['recipientName']=$recipientNameArr[$key];
					$rows['handitem'][$key]['recipientId']=$recipientIdArr[$key];
					$rows['handitem'][$key]['mainId']=$rows['id'];
				}
				$this->service->edit_d ( $rows);
			}
			if($folowInfo['examines']=="ok"){  //审批通过
				$obj = $this->service->get_d ( $folowInfo['objId'] );
				$this->service->sendLeaveMail($obj,$rows);
			}
		}
		echo "<script>this.location='index1.php?model=common_workflow_workflow&action=auditingList'</script>";
	}

	/**
	 * 重写pageJason
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		$this->service->groupBy = 'c.id';
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->page_d ();
		$handoverDao=new model_hr_leave_handover();
		$handoverlistDao=new model_hr_leave_handoverlist();
		if(is_array($rows)){
			foreach($rows as $k => $v){
				$rows[$k]['nowDate'] = date('Y-m-d',time());
				$handoverRow = $handoverDao->getnfo_d($v['id']);
				if(is_array($handoverRow)){
					$rows[$k]['isHandover']='1';
					if($handoverlistDao->isAffirmAll_d($v['id'])) {
						$rows[$k]['isAffirmAll'] = '1';
					}else {
						$rows[$k]['isAffirmAll'] = '0';
					}
				}else{
					$rows[$k]['isHandover']='0';
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 离职管理
	 */
	function c_pageJsonLeave() {
		$service = $this->service;
		$rows = array();
		$service->getParam ( $_REQUEST );
		$this->service->groupBy = 'c.id';
		$service->getParam($_POST); //设置前台获取的参数信息
		$comLimit = $service->this_limit['公司权限'];
		$comLimitArr = array();
		if(!strstr($comLimit,';;')&&!empty($comLimit)){
			$comLimitArr = explode(',',$comLimit);
			foreach($comLimitArr as $key=>$val){
				$nameCN .=$this->service->get_table_fields('branch_info',"NamePt='".$val."'",'NameCN').",";
			}
			$nameCN = substr_replace($nameCN,'','-1');		//去掉最后一个逗号
			$service->searchArr['companyNameI']=$nameCN;
		} else if(empty($comLimit)){
			$service->searchArr['companyNameI']='0';
		}
		$rows = $service->page_d ();
		$handoverDao=new model_hr_leave_handover();
		$handoverlistDao=new model_hr_leave_handoverlist();
		if(is_array($rows)){
			foreach($rows as $k => $v){
				$rows[$k]['nowDate'] = date('Y-m-d',time());
				$handoverRow = $handoverDao->getnfo_d($v['id']);
				if(is_array($handoverRow)){
					$rows[$k]['isHandover']='1';
					if($handoverlistDao->isAffirmAll_d($v['id'])) {
						$rows[$k]['isAffirmAll'] = '1';
					}else {
						$rows[$k]['isAffirmAll'] = '0';
					}
				}else{
					$rows[$k]['isHandover']='0';
				}
			}
		}
		$arr = array ();
		$arr ['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * 根据ID提交增员申请,并发送邮件
	 */
	function c_ajaxSubmit(){
		$id = $_POST['id'];
		$state = $_POST['state'];
		if($this->service->changeState($id,$state)){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 撤回申请
	 */
	function c_backSubmit(){
		$id = $_POST['id'];
		$state = $_POST['state'];
		if($this->service->getState($id,$state)){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 导出
	 */
	function c_toExport(){
		$this->view('exportview');
	}

	/**
	 * 更新离职员工档案
	 */
	function c_updatePersonInfo(){
		$object = $_POST['id'];
		if($object){
			$result = $this->service->updatePersonInfo($object);
		}
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 离职申请，撤销功能
	 */
	function c_updateExaStatus(){
		$object['id'] = $_POST['id'];
		$object['ExaStatus'] = '未提交';
		if($object){
			$result = $this->service->updateExaStatus_d($object);
		}
		if($result){
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 离职未确认信息导出
	 */
	function c_toExpportunconfirm() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];
		$this->service->sort='d.recipientName';
		$rows = $this->service->listBySqlId('select_unconfirm');
		if(is_array($rows)) {
			for ($i = 0 ;$i < count($rows) ;$i++) {
				unset($rows[$i]['id']);
			}
			$colArr = array();
			$modelName = '离职未确认信息';
			return model_hr_basicinfo_export2ExcelUtil::export2ExcelUtil($colArr, $rows, $modelName);
		}
	}

	function c_ExamineExport(){
		$object = $_POST[$this->objName];//获取查询数组
		$this->service->searchArr['state'] = '1,2,3,4';
		$this->service->groupBy='c.id';
		if(!empty($object['leaveCode']))//单据编号  如果入职日期不为空就设置查询条件
			$this->service->searchArr['djbh'] = $object['leaveCode'];

		if(!empty($object['userNo']))//员工编号
			$this->service->searchArr['ygbh'] = $object['userNo'];

		if(!empty($object['userName']))//离职人员
			$this->service->searchArr['userNameSame'] = $object['userName'];

		if(!empty($object['state'])){//单据状态
			switch($object['state']){
				case '2.1':$this->service->searchArr['djzt'] = '2';
					$this->service->searchArr['qdti'] = date('Y-m-d',time());
					break;
				case '2.2':$this->service->searchArr['djzt'] = '2';
					$this->service->searchArr['spzt'] = '完成';
					$this->service->searchArr['xlzrq'] = date('Y-m-d',time());
					break;
				default :$this->service->searchArr['djzt'] = $object['state'];
			}
		}
		if(!empty($object['userSelfCstatus']))//员工确认状态
			$this->service->searchArr['ygqrzt'] = $object['userSelfCstatus'];

		if(!empty($object['handoverCstatus'])){//交接清单状态
			switch($object['handoverCstatus']){
				case '1':$this->service->searchArr['handoverIdN']="1";
						 break;
				case 'WQR':$this->service->searchArr['handoverId']="1";
						   $this->service->searchArr['handoverCstatus'] = $object['handoverCstatus'];
						   break;
				case 'YQR':$this->service->searchArr['handoverId']="1";
						   $this->service->searchArr['handoverCstatus'] = $object['handoverCstatus'];
						   break;
			}
		}
		if(!empty($object['companyName']))//公司
			$this->service->searchArr['gs'] = $object['companyName'];

		if(!empty($object['deptId']))//直属部门ID
			$this->service->searchArr['deptIdSame'] = $object['deptId'];

		if(!empty($object['deptIdS']))//二级部门ID
			$this->service->searchArr['deptIdSameS'] = $object['deptIdS'];

		if(!empty($object['deptIdT']))//三级部门ID
			$this->service->searchArr['deptIdSameT'] = $object['deptIdT'];

        if(!empty($object['deptIdF']))//四级部门ID
            $this->service->searchArr['deptIdSameF'] = $object['deptIdF'];

		if(!empty($object['jobName']))//职位
			$this->service->searchArr['zw'] = $object['jobName'];

		if(!empty($object['entryDate']))//入职日期
			$this->service->searchArr['rzrq'] = $object['entryDate'];

		if(!empty($object['entryDate2']))//入职日期2
			$this->service->searchArr['rzrq2'] = $object['entryDate2'];

		if(!empty($object['quitTypeCode']))//离职类型
			$this->service->searchArr['quitTypeCodeSame'] = $object['quitTypeCode'];

		if(!empty($object['requireDate']))//期望离职日期
			$this->service->searchArr['qwlzrq'] = $object['requireDate'];

		if(!empty($object['requireDate2']))//期望离职日期2
			$this->service->searchArr['qwlzrq2'] = $object['requireDate2'];

		if(!empty($object['leaveDate']))//确认离职日期
			$this->service->searchArr['lzrq'] = $object['leaveDate'];

		if(!empty($object['leaveDate2']))//确认离职日期2
			$this->service->searchArr['lzrq2'] = $object['leaveDate2'];

		if(!empty($object['salaryEndDate']))//工资结算截止日期
			$this->service->searchArr['gzjsjzrq'] = $object['salaryEndDate'];

		if(!empty($object['salaryEndDate2']))//工资结算截止日期2
			$this->service->searchArr['gzjsjzrq2'] = $object['salaryEndDate2'];

		if(!empty($object['salaryPayDate']))//工资支付日期
			$this->service->searchArr['gzzfrq'] = $object['salaryPayDate'];

		if(!empty($object['salaryPayDate2']))//工资支付日期2
			$this->service->searchArr['gzzfrq2'] = $object['salaryPayDate2'];

		if(!empty($object['pensionReduction']))//社保减员
			$this->service->searchArr['sbjy'] = $object['pensionReduction'];

		if(!empty($object['fundReduction']))//公积金减员
			$this->service->searchArr['gjjjy'] = $object['fundReduction'];

		if(!empty($object['employmentEnd']))//用工终止
			$this->service->searchArr['ygzz'] = $object['employmentEnd'];

		if($object['softSate']!="")//办公软件状态
			$this->service->searchArr['bgrjzt'] = $object['softSate'];

		if(!empty($object['ExaStatus']))//审批状态
			$this->service->searchArr['spzt'] = $object['ExaStatus'];

		if(!empty($object['remark']))//进度备注
			$this->service->searchArr['jdbz'] = $object['remark'];

		if(!empty($object['quitReson']))//离职原因
			$this->service->searchArr['lzyy'] = $object['quitReson'];

		set_time_limit(0);// 执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		$examineRows = $this->service->listBySqlId("select_default");//默认查询语句得到所要的值

		$exportData = array();//设置导出数据数组
		//处理数组
		if(is_array($examineRows)) {
			$handoverDao = new model_hr_leave_handover();
			foreach($examineRows as $key => $val){
				//提取离职原因，去除特殊字符，重新组合
				$str = substr($val['quitReson'] ,-5);
				if ($str == "^nbsp") { //没有包含其他原因
					$examineRows[$key]['quitReson'] = str_replace('^nbsp' ,"； " ,$val['quitReson']);
				} else {
					$quitReson = '';
					$arr = explode("^nbsp" ,$val['quitReson']);
					for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //不处理其他原因
						$quitReson .= $arr[$i]."；";
					}
					$examineRows[$key]['quitReson'] = $quitReson.$arr[$i]."：".$arr[$i + 1];
				}

				//单据状态
				$handoverRow = $handoverDao->getnfo_d($val['id']);
				if(!is_array($handoverRow)){
					$examineRows[$key]['handoverCstatus'] = '0';
				}
				if($val['state'] == '2') {
					if($val['ExaStatus'] == '完成' && $val['comfirmQuitDate'] < date('Y-m-d',time())) {
						$examineRows[$key]['state'] = '档案待更新';
					} else{
						$examineRows[$key]['state'] = '已确认类型';
					}
				}

				//当时间显示为0000-00-00默认为空值
				if($val['entryDate'] == '0000-00-00') {
					$examineRows[$key]['entryDate'] = '';
				}
				if($val['leaveApplyDate'] == '0000-00-00') {
					$examineRows[$key]['leaveApplyDate'] = '';
				}
				if($val['requireDate'] == '0000-00-00') {
					$examineRows[$key]['requireDate'] = '';
				}
				if($val['comfirmQuitDate'] == '0000-00-00') {
					$examineRows[$key]['comfirmQuitDate'] = '';
				}
				if($val['salaryEndDate'] == '0000-00-00') {
					$examineRows[$key]['salaryEndDate'] = '';
				}
				if($val['salaryPayDate'] == '0000-00-00') {
					$examineRows[$key]['salaryPayDate'] = '';
				}

				//黑名单
				if ($val['isBack'] == 1) {
					$examineRows[$key]['isBack'] = '是';
				} else {
					$examineRows[$key]['isBack'] = '否';
				}
			}
		}

		foreach ( $examineRows as $key => $val ) {//循环查询结果数组
			$exportData[$key]['id'] = $key + 1;
			$exportData[$key]['leaveCode'] = $val['leaveCode'];
			$exportData[$key]['userNo'] = $val['userNo'];
			$exportData[$key]['userName'] = $val['userName'];
			$exportData[$key]['state'] = $val['state'];
			$exportData[$key]['userSelfCstatus'] = $val['userSelfCstatus'];
			$exportData[$key]['handoverCstatus'] = $val['handoverCstatus'];
			$exportData[$key]['companyName'] = $val['companyName'];
			$exportData[$key]['personnelTypeName'] = $val['personnelTypeName'];
			$exportData[$key]['deptName'] = $val['deptName'];
			$exportData[$key]['deptNameS'] = $val['deptNameS'];
			$exportData[$key]['deptNameT'] = $val['deptNameT'];
            $exportData[$key]['deptNameF'] = $val['deptNameF'];
			$exportData[$key]['jobName'] = $val['jobName'];
			$exportData[$key]['workProvince'] = $val['workProvince'];
			$exportData[$key]['entryDate'] = $val['entryDate'];
			$exportData[$key]['quitTypeName'] = $val['quitTypeName'];
			$exportData[$key]['leaveApplyDate'] = substr($val['leaveApplyDate'] ,0 ,10);
			$exportData[$key]['requireDate'] = $val['requireDate'];
			$exportData[$key]['comfirmQuitDate'] = $val['comfirmQuitDate'];
			$exportData[$key]['salaryEndDate'] = $val['salaryEndDate'];
			$exportData[$key]['salaryPayDate'] = $val['salaryPayDate'];
			$exportData[$key]['pensionReduction'] = $val['pensionReduction'];
			$exportData[$key]['fundReduction'] = $val['fundReduction'];
			$exportData[$key]['employmentEnd'] = $val['employmentEnd'];
			$exportData[$key]['softSate'] = $val['softSate'];
			$exportData[$key]['ExaStatus'] = $val['ExaStatus'];
			$exportData[$key]['createName'] = $val['createName'];
			$exportData[$key]['remark'] = $val['remark'];
			$exportData[$key]['mobile'] = $val['mobile'];
			$exportData[$key]['personEmail'] = $val['personEmail'];
			$exportData[$key]['postAddress'] = $val['postAddress'];
			$exportData[$key]['quitReson'] = $val['quitReson'];
			$exportData[$key]['isBack'] = $val['isBack'];
			$exportData[$key]['realReason'] = $val['realReason'];
		}
		return model_hr_leave_leaveExportUtil::export2ExcelUtil ( $exportData );//把数据输出数组添加到E表
	}

	function c_toSearch(){//跳转高级查询
		$this->view('search');
	}

	/**
	 * 批量打印离职证明
	 */
	function c_printAll(){
		//id串
		$ids = null;
		if(isset($_POST['leave'])){
			$leaveDatas = $_POST['leave'];
			$ids = explode(',',$leaveDatas['ids']);
			if($leaveDatas['ids']&&$leaveDatas['idchecked']){
				$idArr = array_merge($ids,$leaveDatas['idchecked']);
			} else if($leaveDatas['idchecked']) {
				$idArr = $leaveDatas['idchecked'];
			} else if($leaveDatas['ids']) {
				$idArr = $ids;
			}
		}
		$ids = implode(',',$idArr);
		$this->assign('allNumH',count($idArr));
		$this->display('printHead');
		foreach($idArr as $key=>$val){
			$obj = $this->service->getLeaveUserInfo ( $val );
			$arr = $this->service->getPersonnelInfo_d($obj['userAccount']);
			$entryYear='-';
			$entryMon='-';
			$entryDay='-';
			$leaveYear='-';
			$leaveMon='-';
			$leaveDay='-';
			$contractYear='-';
			$contractMon='-';
			$contractDay='-';
			$contractEndYear='-';
			$contractEndMon='-';
			$contractEndDay='-';
			$leaveYears='--年';
			$userName=$obj['userName'];
			$identityCard= $obj['identityCard'];
			if($arr['beginDate']!='0000-00-00'&&$arr['beginDate']!=''){
				$contractYear= date("Y",strtotime($arr['beginDate']));
				$contractMon=date("m",strtotime($arr['beginDate']));
				$contractDay= date("d",strtotime($arr['beginDate']));
			}
			if($arr['closeDate']!='0000-00-00'&&$arr['closeDate']!=''){
				$contractEndYear= date("Y",strtotime($arr['closeDate']));
				$contractEndMon=date("m",strtotime($arr['closeDate']));
				$contractEndDay= date("d",strtotime($arr['closeDate']));
			}
			//入职时间
			if($obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
				$entryYear= date("Y",strtotime($obj['entryDate']));
				$entryMon=date("m",strtotime($obj['entryDate']));
				$entryDay= date("d",strtotime($obj['entryDate']));
			}
			//离职时间
			if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''){
				$leaveYear=date("Y",strtotime($obj['comfirmQuitDate']));
				$leaveMon=date("m",strtotime($obj['comfirmQuitDate']));
				$leaveDay=date("d",strtotime($obj['comfirmQuitDate']));
			}
			$leaveTypeS=array('YGZTJC','YGZTWJJC','YGZTCZ','YGZTCT');
			if(in_array($obj['quitTypeCode'],$leaveTypeS)){
				$leaveType='解除';
				$leaveRemark='自双方解除劳动合同关系之日起';
			}else{
				$leaveType='终止';
				$leaveRemark='自双方终止劳动合同关系之日起';
			}
			if($obj['comfirmQuitDate']!='0000-00-00'&&$obj['comfirmQuitDate']!=''&&$obj['entryDate']!='0000-00-00'&&$obj['entryDate']!=''){
				$leaveYears=$obj['leaveYears']*12;
				$allMonth=(int)($leaveYears+$obj['leaveMonth']);
				if($obj['leaveDay']>15){		//天数大于15天的算一个月
					$allMonth = $allMonth + 1;
				}else if($obj['leaveDay']<-15){		//小于-15的剪掉一个月
					$allMonth = $allMonth - 1;
				}
				$remainYear = floor($allMonth/12);	//算工作了多少年
				if($remainYear==0){
					if($allMonth==0){
						$leaveYears = $obj['leaveDay'].'天';		//工作不足一个月的算天数
					}else{
						$leaveYears=$allMonth.'个月';
					}
				}else if($remainYear>0){
					$month = $allMonth%12;
					if($month!=0){
						$leaveYears=$remainYear.'年'.$month.'个月';
					}else{
						$leaveYears=$remainYear.'年';
					}
				}
			}
			$leaveContent="兹有我公司员工".$userName."（身份证号：".$identityCard."），最新劳动合同起止期限为".$contractYear."年".$contractMon."月".$contractDay."日至".$contractEndYear."年".$contractEndMon."月".$contractEndDay."日止。并于".$entryYear."年".$entryMon."月".$entryDay."日至".$leaveYear."年".$leaveMon."月".$leaveDay."日在我公司工作，离职时职务为$obj[jobName]，本司工作年限为".$leaveYears."，现已于".$leaveYear."年".$leaveMon."月".$leaveDay."日因个人原因与我公司".$leaveType."劳动合同关系，并已办理完毕各项工作交接和离职手续。";
			if($obj['NamePT']=='dl'){
				$companyName="珠海世纪鼎利科技股份有限公司";
				$photoName='sjdl';
			}elseif($obj['NamePT']=='br'){
				$companyName="广州市贝软电子科技有限公司";
				$photoName='gzbr';
			}elseif($obj['NamePT']=='sy'){
				$companyName="北京世源信通科技有限公司";
				$photoName='bjsy';
			}elseif($obj['NamePT']=='bx'){
				$companyName="广州市贝讯电子科技有限公司";
				$photoName='sjdl';
			}else{
				$companyName=$obj['NameCN']?$obj['NameCN']:'无';
				$photoName='sjdl';
			}

			$this->assign ( 'leaveContent', $leaveContent );
			$this->assign ( 'companyName', $companyName );
			$this->assign ( 'photoName', $photoName );
			$this->assign ( 'leaveRemark', $leaveRemark );
			//打印时间
			$this->assign ( 'todayYear', date("Y") );
			$this->assign ( 'todayMon', date("m") );
			$this->assign ( 'todayDay', date("d") );
			$this->assign ( 'id', $val );
			$this->view ( 'batchPrint' );
		}
		$this->assign('allNum',count($idArr));
		$this->assign('ids',$ids);
		$this->display("printTail");
	}

	/**
	 * 跳转到离职申请打回页面
	 */
	function c_toBack() {
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );
		$this->view('back' ,true);
	}

	/**
	 * 离职申请打回
	 */
	function c_back() {
		$this->checkSubmit();
		$rs = $this->service->back_d( $_POST[$this->objName] );
		if ($rs) {
			msg('打回成功');
		} else {
			msg('打回失败');
		}
	}

	/**
	 * 跳转到离职申请关闭页面
	 */
	function c_toClose() {
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );
		$this->view('close' ,true);
	}

	/**
	 * 跳转到查看离职申请关闭原因页面
	 */
	function c_toCloseReason() {
		$obj = $this->service->get_d( $_GET['id'] );
		$this->assignFunc( $obj );
		$this->view( 'closeReason' );
	}

	/**
	 * 跳转到离职注意事项的对话框
	 */
	function c_toAttention () {
		$this->view( 'attention' );
	}

	/**
	 * 跳转到编辑真实离职原因页面
	 */
	function c_toEditReal() {
		$obj = $this->service->get_d( $_GET['id'] );
		//提取离职原因，重新组合
		$str = substr($obj['quitReson'] ,-5);
		if ($str == "^nbsp") { //没有包含其他原因
			$obj['quitReson'] = str_replace('^nbsp' ,"； " ,$obj['quitReson']);
		} else {
			$quitReson = '';
			$arr = explode("^nbsp" ,$obj['quitReson']);
			for ($i = 0 ;$i < count($arr) - 2 ;$i++) { //不处理其他原因
				$quitReson .= $arr[$i]."；";
			}
			$obj['quitReson'] = $quitReson.$arr[$i]."：".$arr[$i + 1];
		}
		$this->assignFunc( $obj );
		$this->view('edit-real' ,true);
	}

	/**
	 * 编辑真实离职原因
	 */
	function c_editReal() {
		$this->checkSubmit();
		if ($this->service->editReal_d($_POST[$this->objName])) {
			msg('保存成功！');
		} else {
			msg('保存失败！');
		}
	}
 }
?>